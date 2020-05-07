<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
//Turn on display_errors 
ini_set('display_errors','1');

// Display ALL errors including notices 
error_reporting (E_ALL);
require_once("backend/functions.php");
require_once("backend/bbcode.php");
dbconn(false);


$site_config["RIGHTNAV"] = $site_config["MIDDLENAV"] = $site_config["RIGHTNAV"] = false;
if (!$site_config["FORUMS_GUESTREAD"])
	loggedinonly();

    function showerror($heading = "Erro", $text, $sort = "Negado") {
        stdhead("$sort: $heading");
        begin_framec("<span class='error'>$sort: $heading</span>");
        echo $text;
        end_framec();
        stdfoot();
        die;
    }

$action = strip_tags($_REQUEST["action"]);
    
if (!$CURUSER && ($action == "newtopic" || $action == "post")) 
    showerror("Fórum erro", "Fórum ID não encotrada.");

	
	
	
	
	$usertempo = @SQL_Query_exec("SELECT * FROM usermoderado WHERE tipo = 'forum'  AND  uid=".$CURUSER['id']."  ");
	
$row_usertempo = mysql_fetch_array($usertempo);

	$data1 = $row_usertempo['added'];
$data2 = date('Y-m-d H:i:s');  

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);
$nMinutos = (($unix_data2 - $unix_data1) % 3600) / 60;
$mederandotempo = sprintf('%02d', $nMinutos);
$mederandotempo + 1;

	if (mysql_num_rows($usertempo) != 0){
if ( sqlesc($mederandotempo) < 30 ){
    showerror("Fórum Banido","<center>Você terá que esperar 30 minutos para postar novamente devido a uma proibição sofrida, tempo decorrido  <B>$mederandotempo minutos</B>.</center>");	
}
}

//Here we decide if the forums is on or off
if ($site_config["FORUMS"]) {
$themedir = "themes/".$THEME."/forums/";
$dossier = $CURUSER['bbcode'];

//setup the forum head aread
function forumheader($location){
echo "

  <table width='100%' border='0'>
    <tr>
     <td align='left' valign='bottom'>&nbsp;</td>
      <td align='right' valign='bottom'><b></b>  <b>[<a href='forums.php?action=viewunread'>Ver Novos Posts </a>|<a href='forum_fav.php'> Favoritos</a>| <a href='rss.php'> RSS </a>|<a href='?catchup'>".T_("FORUM_MARK_READ")." </a>|<a href='forums.php?action=search'>Pesquisa</a>]</b></td>
    </tr>
  </table>


<br />";
    if (!$location){
print ("<div> &nbsp;<a href='forums.php'>Índice do Fórum</a> <b style='vertical-align:right'><BR>&nbsp;&nbsp;&nbsp;$location </b></div>");
}else
print ("<div> &nbsp;<a href='forums.php'>Índice do Fórum</a> <b style='vertical-align:right'><BR>&nbsp;&nbsp;&nbsp; <img border='0' src='images/arrow_left.png'> $location </b></div>");
}

// Mark all forums as read
function catch_up(){ 
	global $CURUSER;
	
    if (!$CURUSER)
		 return;
    
	$userid = $CURUSER["id"];
	$res = mysql_query("SELECT id, lastpost FROM forum_topics");
	while ($arr = mysql_fetch_assoc($res)) {
		$topicid = $arr["id"];
		$postid = $arr["lastpost"];
		$r = mysql_query("SELECT id,lastpostread FROM forum_readposts WHERE userid=$userid and topicid=$topicid");
		if (mysql_num_rows($r) == 0){
			mysql_query("INSERT INTO forum_readposts (userid, topicid, lastpostread) VALUES($userid, $topicid, $postid)");
		}else{
			$a = mysql_fetch_assoc($r);
			if ($a["lastpostread"] < $postid)
			mysql_query("UPDATE forum_readposts SET lastpostread=$postid WHERE id=" . $a["id"]);
		}
	}
}

// Returns the minimum read/write class levels of a forum
function get_forum_access_levels($forumid){ 
	$res = mysql_query("SELECT minclassread, minclasswrite FROM forum_forums WHERE id=$forumid");
	if (mysql_num_rows($res) != 1)
		return false;
	$arr = mysql_fetch_assoc($res);
		return array("read" => $arr["minclassread"], "write" => $arr["minclasswrite"]);
}

// Returns the forum ID of a topic, or false on error
function get_topic_forum($topicid) {
    $res = mysql_query("SELECT forumid FROM forum_topics WHERE id=$topicid");
    if (mysql_num_rows($res) != 1)
      return false;
    $arr = mysql_fetch_row($res);
    return $arr[0];
}

// Returns the ID of the last post of a forum
function update_topic_last_post($topicid) {
    $res = mysql_query("SELECT id FROM forum_posts WHERE topicid=$topicid ORDER BY id DESC LIMIT 1");
    $arr = mysql_fetch_row($res) or showerror("Fórum erro", "No post found");
    $postid = $arr[0];
    mysql_query("UPDATE forum_topics SET lastpost=$postid WHERE id=$topicid");
}

function get_forum_last_post($forumid)  {
    $res = mysql_query("SELECT lastpost FROM forum_topics WHERE forumid=$forumid ORDER BY lastpost DESC LIMIT 1");
    $arr = mysql_fetch_row($res);
    $postid = $arr[0];
    if ($postid)
      return $postid;
    else
      return 0;

}

//Top forum posts

function forumpostertable($res) {
	print("<div  align='center'  ><br /><table align='center' width='160'><tr><td>	</div>\n");
	print("<div  align='center'  ><table cellpadding='3' cellspacing='0' class='ttable_headinner' width='100%'>	</div>");
	
    ?>

    <tr>
      <th class='ttable_head' width='10' align='center'>
      <font size='1'><?php echo T_("FORUM_RANK"); ?></font>
      </th>
      <th class='ttable_head' width='130' align='center'>
      <font size='1'><?php echo T_("FORUM_USER"); ?></font>
      </th>
      <th class='ttable_head' width='10' align='center'>
      <font size='1'><?php echo T_("FORUM_POST"); ?></font>
      </th>
    </tr>
    
    <?php

    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      print("<tr class='t-row'><td align='center' class='ttable_col1'>$num</td><td class='ttable_col2' style='text-align: justify'><a href='account-details.php?id=$a[id]'><b>$a[username]</b></a></td><td align='center' class='ttable_col1'>$a[num]</td></tr>\n");
    }
    
    if ($num == 0)
    print("<tr class='t-row'><td align='center' class='ttable_col1' colspan='3'><b>Não temos posts nesse fórum/b></td></tr>");
    
  print("</table>");
	 print("</td></tr></table>\n");
}

// Inserts a quick jump menu
function insert_quick_jump_menu($currentforum = 0) {
    print("<div class='f-form' style='text-align:right'><form class='f-form' method='get' action='?' name='jump'>\n");
    print("<input type='hidden' name='action' value='viewforum' />\n");
    $res = mysql_query("SELECT * FROM forum_forums ORDER BY name");
   
    if ( mysql_num_rows($res) > 0 ) 
    {
         print( T_("FORUM_JUMP") . ": ");
         print("<select class='styled' name='forumid' onchange='if(this.options[this.selectedIndex].value != -1){ forms[jump].submit() }'>\n");
   
         while ($arr = mysql_fetch_assoc($res))
         {
             if (get_user_class() >= $arr["minclassread"] || (!$CURUSER && $arr["guest_read"] == "yes"))
                 print("<option value='" . $arr["id"] . "'" . ($currentforum == $arr["id"] ? " selected='selected'>" : ">") . $arr["name"] . "</option>\n");
         }
         
         print("</select>\n");
         print("<input type='submit' value='".T_("FORUM_GO")."' />\n");
    }

   // print("<input type='submit' value='Go!'>\n");
    print("</form>\n</div>");
}

// Inserts a compose frame
function insert_compose_frame($id, $newtopic = true) {
    global $maxsubjectlength;

	if ($newtopic) {
		$res = mysql_query("SELECT name FROM forum_forums WHERE id=$id");
		$arr = mysql_fetch_assoc($res) or showerror("Forum erro", "ID fórum Bad");
		$forumname = stripslashes($arr["name"]);

		print("<p align='center'><b>".T_("FORUM_NEW_TOPIC")." <a href='forums.php?action=viewforum&amp;forumid=$id'>$forumname</a></b></p>\n");
	}else{
		$res = mysql_query("SELECT * FROM forum_topics WHERE id=$id");
		$arr = mysql_fetch_assoc($res) or showerror(T_("FORUM_ERROR"), T_("FORUM_TOPIC_NOT_FOUND"));
		$subject = stripslashes($arr["subject"]);
		print("<p align='center'>".T_("FORUM_REPLY_TOPIC").": <a href='forums.php?action=viewtopic&amp;topicid=$id'>$subject</a></p>");
	}

    # Language Marker #
    print("<p align='center'><b><font color='red'>Atenção</b></font></br>
O tópico deverá estar de acordo com as <a href='rules.php'>regras</a>.</br>
Caso contrário, ele poderá ser deletado sem aviso prévio.<br/><br/><b>");


  #begin_framec("Compose Message", true);
     print("<fieldset class='download'>");
     print("<legend><b>Escrever mensagem</b></legend>");
     print("<div>");
    print("<form class='f-form' name='Form' method='post' action='?action=post'>\n");
    if ($newtopic)
      print("<input type='hidden' name='forumid' value='$id' />\n");
    else
      print("<input type='hidden' name='topicid' value='$id' />\n");

    if ($newtopic){
			print("<center><br /><table cellpadding='3' cellspacing='0'><tr><td><strong>Assunto:</strong>  <input type='text' size='70' maxlength='$maxsubjectlength' name='subject' /></td></tr>");
			print("<tr><td align='center'>");
			textbbcode("Form", "body",$dossier);
			print("</td></tr><tr><td align='center'><br /><input type='submit' value='Submit' /><br /><br /></td></tr></table>");
	
	}
    print("<br /></center>");
    print("</form>\n");
    print("</div>");
    print("</fieldset><br />");
    #end_framec();

	insert_quick_jump_menu();
}

//LASTEST FORUM POSTS
function latestforumposts() {
print("<table cellpadding='3' cellspacing='0' class='f-border' width='100%'><tr class='f-title f-border'>".
"<th align='left'  width=''>Títulos recentes</th>". 
"<th align='center' width='47'>Respostas</th>".
"<th align='center' width='47'>Visitas</th>".
"<th align='center' width='85'>Autor</th>".
"<th align='right' width='150'>Último Post</th>".
"</tr>");


/// HERE GOES THE QUERY TO RETRIEVE DATA FROM THE DATABASE AND WE START LOOPING ///
$for = mysql_query("SELECT * FROM forum_topics ORDER BY lastpost DESC LIMIT 5");

if (mysql_num_rows($for) == 0)
    print("<tr class='f-row f-border'><td class='alt1 f-border' align='center' colspan='5'><b>Não Últimos Tópicos</b></td></tr>");

while ($topicarr = mysql_fetch_assoc($for)) {
// Set minclass
$whereaad=array();



if ($CURUSER["ver_xxx"]!="yes") {
    $whereaad[] = "forum_forums.id != '43'";
    $whereaad[] = "forum_forums.id != '16'";

}
     $whereaad[] = "forum_forums.id != '0'";
$wheread = implode(" AND ", $whereaad);
    //


$res = mysql_query("SELECT name,minclassread,guest_read FROM forum_forums WHERE id=$topicarr[forumid] AND ".$wheread."");
$forum = mysql_fetch_assoc($res);

if ($forum && get_user_class() >= $forum["minclassread"] || $forum["guest_read"] == "yes") {
$forumname = "<a href='?action=viewforum&amp;forumid=$topicarr[forumid]'><b>" . htmlspecialchars($forum["name"]) . "</b></a>";

$topicid = $topicarr["id"];
$topic_title = stripslashes($topicarr["subject"]);
$topic_userid = $topicarr["userid"];
// Topic Views
$views = $topicarr["views"];
// End

/// GETTING TOTAL NUMBER OF POSTS ///
$res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE topicid=$topicid");
$arr = mysql_fetch_row($res);
$posts = $arr[0];
$replies = max(0, $posts - 1);

/// GETTING USERID AND DATE OF LAST POST ///   
$res = mysql_query("SELECT * FROM forum_posts WHERE topicid=$topicid ORDER BY id DESC LIMIT 1");
$arr = mysql_fetch_assoc($res);
$postid = 0 + $arr["id"];
$userid = 0 + $arr["userid"];
$added = utc_to_tz($arr["added"]);

/// GET NAME OF LAST POSTER ///
$res = mysql_query("SELECT id, username FROM users WHERE id=$userid");
if (mysql_num_rows($res) == 1) {
$arr = mysql_fetch_assoc($res);
$username = "<a href='account-details.php?id=$userid'>$arr[username]</a>";
}
else
$username = "Unknown[$topic_userid]";

/// GET NAME OF THE AUTHOR ///
$res = mysql_query("SELECT username FROM users WHERE id=$topic_userid");
if (mysql_num_rows($res) == 1) {
$arr = mysql_fetch_assoc($res);
$author = "<a href='account-details.php?id=$topic_userid'>$arr[username]</a>";
}
else
$author = "Unknown[$topic_userid]";

/// GETTING THE LAST INFO AND MAKE THE TABLE ROWS ///
$r = mysql_query("SELECT lastpostread FROM forum_readposts WHERE userid=$userid AND topicid=$topicid");
$a = mysql_fetch_row($r);
$new = !$a || $postid > $a[0];
$subject = "<a href='forums.php?action=viewtopic&amp;topicid=$topicid'><b>" . stripslashes(encodehtml($topicarr["subject"])) . "</b></a>";

print("<tr class='f-row f-border'><td class='f-img f-border' style='padding-right: 5px' width='100%'>$subject</td>".
"<td class='alt2 f-border' align='center'>$replies</td>" .
"<td class='alt3 f-border' align='center'>$views</td>" .
"<td class='alt2 f-border' align='center'>$author</td>" .
"<td class='alt3 f-border' align='right'><span class='small'>por&nbsp;$username<br /><span style='white-space: nowrap'>$added</span></span></td>");

print("</tr>");
} // while
}
print("</table><br />");
} // end function

//Global variables
$postsperpage = 20;
$maxsubjectlength = 10;
if ($action == 'add_favoritos')
{
        $addfav = intval($_GET['addfav']);
        $pr  = intval($_GET['pr']);
		
		        $res2356 = mysql_query("SELECT * FROM `forum_topics` WHERE `id` = $addfav");
        if ( ! ( mysql_num_rows( $res2356 ) )  ){
		     autolink($_SERVER["HTTP_REFERER"], "Tópico não encontrado");
		}
		
		
		

		
		
		
        	    $res_favo = mysql_query("SELECT pid FROM forum_favoritos WHERE pid=$addfav AND uid = $CURUSER[id] ");
	    $resp_favor = mysql_fetch_assoc($res_favo);
			 if ($resp_favor["pid"]== $addfav ) {
	         autolink($_SERVER["HTTP_REFERER"], "Você já adicionou esse tópico aos seus favoritos!");
        }
        mysql_query("INSERT INTO forum_favoritos (pid, uid, pr) VALUES ($addfav, $CURUSER[id], '" . ( $pr ? 'g' : 'b' ) . "')");
        
        autolink($_SERVER["HTTP_REFERER"], "Tópico adicionado aos favoritos com sucesso!");
}
if ($action == 'rating')
{
        $pid = intval($_GET['pid']);
        $pr  = intval($_GET['pr']);
        
        $res = SQL_Query_exec("SELECT * FROM `forum_posts` WHERE `id` = $pid");
        
        if ( ! ( mysql_num_rows( $res ) ) || empty( $_SERVER["HTTP_REFERER"] ) )
        {
                 showerror(T_("FORUM_ERROR"), "An Error Occured.");
        }
        
        # Perhaps, They've changed there minds...
        $num = get_row_count("forum_ratings", "WHERE pid = $pid AND uid = $CURUSER[id]");
        
        if ( $num > 0 )
        {
                 SQL_Query_exec("DELETE FROM forum_ratings WHERE pid = $pid AND uid = $CURUSER[id]");
        }
        
        SQL_Query_exec("INSERT INTO forum_ratings (pid, uid, pr) VALUES ($pid, $CURUSER[id], '" . ( $pr ? 'g' : 'b' ) . "')");
        
        autolink($_SERVER["HTTP_REFERER"], "Atualizando Fórum...");
}
//Action: New topic
if ($action == "newtopic") {
    $forumid = $_GET["forumid"];
    if (!is_valid_id($forumid))
    showerror("Fórum erro", "Fórum ID No. $forumid");

    stdhead("Novo tópico");
    begin_framec("Novo tópico");

	forumheader("Criar um novo tópico");

    insert_compose_frame($forumid,$newtopic = true,$dossier);
    end_framec();
    stdfoot();
    die;
}

///////////////////////////////////////////////////////// Action: POST
if ($action == "post") {
	$forumid = $_POST["forumid"];
	$topicid = $_POST["topicid"];

	if (!is_valid_id($forumid) && !is_valid_id($topicid))
		    showerror("Fórum erro", "w00t");
	$newtopic = $forumid > 0;
	$subject = $_POST["subject"];
	if ($newtopic) {
		if (!$subject)
			showerror("Erro", "Você deve digitar um assunto.");
		$subject = trim($subject);
		//if (!$subject)
			//showerror("Error", "You must enter a subject.");
		//showerror("Error", "Subject is limited to $maxsubjectlength characters.");
	}else{
      $forumid = get_topic_forum($topicid) or showerror("Forum error","Bad tópico ID");
	}
	$res65 = mysql_query("SELECT topicid,userid,added FROM forum_posts WHERE topicid=$topicid ORDER BY added DESC LIMIT 1");
$arr56 = mysql_fetch_assoc($res65);	
   	if ($arr56["userid"] == $CURUSER["id"] ){
		showerror("Erro","Não é permitido duplo post. Favor editar a sua postagem anterior.");
				
	}
      if (strlen(trim($_POST["body"])) < 2)
      {
     showerror("Fórum erro","O Assunto tem de ter 2 caracteres, no mínimo., ");
        }
    ////// Make sure sure user has write access in forum
	$arr = get_forum_access_levels($forumid) or showerror("Forum error","Bad fórum ID");
	if (get_user_class() < $arr["write"])
		showerror("Fórum erro","Não é permitido");
	$body = trim($_POST["body"]);
	if (!$body)
		showerror("Erro", "No corpo do texto.");
	$userid = $CURUSER["id"];

	if ($newtopic) { //Create topic
		$subject = sqlesc($subject);
		mysql_query("INSERT INTO forum_topics (userid, forumid, subject) VALUES($userid, $forumid, $subject)");
		$topicid = mysql_insert_id() or showerror("Fórum erro","No ID tópico voltou");

	}else{
		//Make sure topic exists and is unlocked
		$res = mysql_query("SELECT * FROM forum_topics WHERE id=$topicid");
		$arr = mysql_fetch_assoc($res) or showerror("Forum error","ID tópico n/a");
		if ($arr["locked"] == 'yes')
        showerror("Fórum erro","Tópico bloqueado");
		//Get forum ID
		$forumid = $arr["forumid"];
    }

    //Insert the new post
    $added = "'" . get_date_time() . "'";
    $body = sqlesc($body);
    mysql_query("INSERT INTO forum_posts (topicid, userid, added, body) VALUES($topicid, $userid, $added, $body)");
    $postid = mysql_insert_id() or showerror("Forum erro","Post id n/a");

    //Update topic last post
    update_topic_last_post($topicid);

    //All done, redirect user to the post
    $headerstr = "Location: $site_config[SITEURL]/forums.php?action=viewtopic&topicid=$topicid&page=last";
    if ($newtopic)
		header($headerstr);
    else
		header("$headerstr#post$postid");
    die;
}

///////////////////////////////////////////////////////// Action: VIEW TOPIC
if ($action == "viewtopic") {
	$topicid = $_GET["topicid"];
	$page = $_GET["page"];

	if (!is_valid_id($topicid))
        showerror("Forum erro","Topic Not Valid");
	$userid = $CURUSER["id"];

    //------ Get topic info
    $res = mysql_query("SELECT * FROM forum_topics WHERE id=$topicid");
    $arr = mysql_fetch_assoc($res) or showerror("Forum erro", "Tópico não encontrado");
    $locked = ($arr["locked"] == 'yes');
    $subject = stripslashes($arr["subject"]);
	$sticky = $arr["sticky"] == "yes";
    $forumid = $arr["forumid"];
	
	
$whereaad=array();



if ($CURUSER["ver_xxx"]!="yes") {
    $whereaad[] = "forum_forums.id != '43'";
    $whereaad[] = "forum_forums.id != '16'";

}
     $whereaad[] = "forum_forums.id != '0'";
$wheread = implode(" AND ", $whereaad);
    //



	// Check if user has access to this forum
	$res2 = mysql_query("SELECT minclassread, guest_read FROM forum_forums WHERE id=$forumid  AND ".$wheread."");
    $arr2 = mysql_fetch_assoc($res2);
    if (!$arr2 || get_user_class() < $arr2["minclassread"] && $arr2["guest_read"] == "no")
        show_error_msg("Erro","Acesso Negado");

	// Update Topic Views
	$viewsq = mysql_query("SELECT views FROM forum_topics WHERE id=$topicid");
	$viewsa = mysql_fetch_array($viewsq);
	$views = $viewsa[0];
	$new_views = $views+1;
	$uviews = mysql_query("UPDATE forum_topics SET views = $new_views WHERE id=$topicid");
	// End

    //------ Get forum
    $res = mysql_query("SELECT * FROM forum_forums WHERE id=$forumid AND ".$wheread."");
    $arr = mysql_fetch_assoc($res) or showerror("Fórum erro", "Fórum está vazio");
    $forum = stripslashes($arr["name"]);

    //------ Get post count
    $res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE topicid=$topicid");
    $arr = mysql_fetch_row($res);
    $postcount = $arr[0];

    //------ Make page menu
    $pagemenu = "<br /><span class='small'>\n";
    $perpage = $postsperpage;
    $pages = floor($postcount / $perpage);
    if ($pages * $perpage != $postcount)
		++$pages;
    if ($page == "last")
		$page = $pages;
    else {
		if($page < 1)
			$page = 1;
		elseif ($page > $pages)
			$page = $pages;
    }
    $offset = $page * $perpage - $perpage;
	//
    if ($page == 1)
      $pagemenu .= "<b>«« Página Anterior</b>";
    else
      $pagemenu .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=" . ($page - 1) . "'><b>«« Página Anterior</b></a>";
	//
	$pagemenu .= "&nbsp;&nbsp;";
	    for ($i = 1; $i <= $pages; ++$i) {
      if ($i == $page)
        $pagemenu .= "<b>$i</b>\n";
      else
        $pagemenu .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'><b>$i</b></a>\n";
    }
	//
    $pagemenu .= "&nbsp;&nbsp;";
    if ($page == $pages)
      $pagemenu .= "<b>Próxima Página  »»</b><br /><br />\n";
    else
      $pagemenu .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=" . ($page + 1) . "'><b>Próxima Página »»</b></a><br /><br />\n";
    $pagemenu .= "</span>";
      
//Get topic posts
    $res = mysql_query("SELECT * FROM forum_posts WHERE topicid=$topicid ORDER BY id LIMIT $offset,$perpage");

    stdhead("View Topic: $subject");
    begin_framec("$forum &gt; $subject");
	forumheader("<a href='forums.php?action=viewforum&amp;forumid=$forumid'>$forum</a> <b style='font-size:16px; vertical-align:middle'><BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img border='0' src='images/arrow_left.png'></b> $subject");
	
	print ("<div style='padding: 0px'>");
	
	$levels = get_forum_access_levels($forumid) or die;
	if (get_user_class() >= $levels["write"])
		$maypost = true;
	else
		$maypost = false;
	
	if (!$locked && $maypost){
			
		$like = get_row_count("forum_favoritos", "WHERE pr = 'g' AND pid = $topicid");
        $dislike = get_row_count("forum_favoritos", "WHERE pr = 'b' AND pid = $topicid");

	print ("<BR><BR><div align='right'><a href='forums.php?action=add_favoritos&amp;pr=1&amp;addfav=$topicid'><img src='". $themedir ."favoritos.png' border='0' alt='' /></a> <font color='green'></font>&nbsp;<a href='#bottom'><img src='". $themedir ."button_reply.png' border='0' alt='' /></a></div>"); 

		

		
		
		
	}else{
		print ("<div align='right'><img src='" . $themedir . "button_locked.png'  alt='Trancar' /></div>");
	}
	print ("</div>");
	print("<center> ".$pagemenu."</center> ");
//------ Print table of posts
    $pc = mysql_num_rows($res);
    $pn = 0;
	if ($CURUSER) {
	    $r = mysql_query("SELECT lastpostread FROM forum_readposts WHERE userid=$CURUSER[id] AND topicid=$topicid");
	    $a = mysql_fetch_row($r);
	    $lpr = $a[0];
	    if (!$lpr)
			mysql_query("INSERT INTO forum_readposts (userid, topicid) VALUES($userid, $topicid)");
	}

    while ($arr = mysql_fetch_assoc($res)) {
		++$pn;
		$postid = $arr["id"];
		$posterid = $arr["userid"];
		$added = utc_to_tz($arr["added"])."(" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($arr["added"]))) . "  atrás)";
                $numtorrents = get_row_count("torrents", "WHERE owner = $posterid");
		//---- Get poster details
		$res4 = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE userid=$posterid") or forumsqlerr();
		$arr33 = mysql_fetch_row($res4);
		$forumposts = $arr33[0];

		$res2 = mysql_query("SELECT * FROM users WHERE id=$posterid") or forumsqlerr(__FILE__, __LINE__);
		$arr2 = mysql_fetch_assoc($res2);
		$postername = $arr2["username"];
		


			if ($postername == "") {
				$by = "Desativada";
				$title = "Conta excluída";
				$privacylevel = "Forte";
				$usersignature = " ";
				$userdownloaded = "0";
				$useruploaded = "0";
				$avatar = "";
				$nposts = "-";
				$tposts = "-";
				$online = "";
				$sexo = "";
				$palca="";
			}else{
					$resgrupos = mysql_query("SELECT name,id,image FROM teams WHERE id = ".$arr2["team"]." LIMIT 1");
	    $arrgrupos = mysql_fetch_assoc($resgrupos);
	    $res1grupos = mysql_query("SELECT status FROM usergroups WHERE gid=".$arr2["team"]." AND uid = $posterid  ");
	    $arr1grupos = mysql_fetch_assoc($res1grupos);
				$avatar = htmlspecialchars($arr2["avatar"]);
				$userdownloaded = mksize($arr2["downloaded"]);
				$useruploaded = mksize($arr2["uploaded"]);
				$privacylevel = $arr2["privacy"];
				$usersignature = stripslashes(format_comment($arr2["signature"]));
					if ($arr2["downloaded"] > 0) {
						$userratio = number_format($arr2["uploaded"] / $arr2["downloaded"], 2);
					}else
						if ($arr2["uploaded"] > 0)
							$userratio = "Inf.";
						else
							$userratio = "---";
							
if ( $arr1grupos["status"] == 'membrogrupo'){ 
$grupos_exe ="<font color=#191970><B><center>Membro da Equipe</B></font></center><center> <a href='grupos_lancamentos.php?id=".$arrgrupos["id"]."'>".$arrgrupos["name"]."</a></center>";
}							
elseif ( $arr1grupos["status"] == 'submoderadores'){ 
$grupos_exe ="<font color=#56690B><B><center>Sub-moderador da Equipe</B></font></center><center> <a href='grupos_lancamentos.php?id=".$arrgrupos["id"]."'>".$arrgrupos["name"]."</a></center>";
}	
elseif ( $arr1grupos["status"] == 'moderadores'){ 
$grupos_exe ="<font color=#228B22><B><center>Moderador da Equipe</B></font></center><br><center>  <a href='grupos_lancamentos.php?id=".$arrgrupos["id"]."'>".$arrgrupos["name"]."</a></center>";
} 
else{
$grupos_exe="";
}
       
$placa = $arr2;


if ($arr2["class"] == 100){
$placa="&nbsp;<img src=images/sysop.png alt=Sysops	 title=Sysops  border=0>";}

elseif ($arr2["class"] == 95){
$placa="&nbsp;<img src=images/adm.png alt=Administrador	 title=Administrador border=0>";}

elseif ($arr2["class"] == 86){
$placa="&nbsp;<img src=images/S.Moderador.png alt=Moderador title=Moderador border=0>";}

elseif ($arr2["class"] == 85){
$placa="&nbsp;<img src=images/MODERADOR.png alt=Moderador title=Moderador border=0>";}

elseif ($arr2["class"] == 75){
$placa="&nbsp;<img src=images/LIBERADOR-DE-TORRENTS.png alt=Liberador de Torrents title=Liberador de Torrents border=0>";}

elseif ($arr2["class"] == 80){
$placa="&nbsp;<img src=images/COLABORADOR.png alt=Colaborador title=Colaborador border=0>";}

elseif ($arr2["class"] == 70){
$placa="&nbsp;<img src=images/DESIGNER.png alt=Designer title=Designer border=0>";}

elseif ($arr2["class"] == 71){
$placa="&nbsp;<img src=images/Coord.Designer.png alt=Coord de designer title=Coord de designer border=0>";}

elseif ($arr2["class"] == 69){ 
$placa="&nbsp;<img src=images/DJs.png alt=DJ's de Torrents title=DJ's border=0>";}

elseif ($arr2["class"] == 50){
$placa="&nbsp;<img src=images/UPLOADER.png alt=Uploader title=Uploader border=0>";}
elseif ($arr2["class"] == 1){
$placa="&nbsp;<b>Usuário</b>";


}
else{
$placa="";
}
				  
	$sexo= $arr2;		

if ($arr2["gender"] =='Male'){
$sexo = "Masculino";}
elseif ($arr2["gender"] =='Female'){
$sexo = "Feminino";}	
else{
$sexo="Indefinido";
}
$datetime = get_date_time(gmtime() - 180);
					  

              
						if($arr2["last_access"] > $datetime){
						           $online = "<font class='online'>Online</font>";
					}else{
				        		  $online = "Offline";
					}
				
				
				
					if(!$arr2["country"]){
						$usercountry = "Desconhecido";
					}else{
						$res4 = mysql_query("SELECT name,flagpic FROM countries WHERE id=$arr2[country] LIMIT 1") or forumsqlerr();
						$arr4 = mysql_fetch_assoc($res4);
						$usercountry = $arr4["name"];
					}

				$title = strip_tags($arr2["title"]);
				$donated = $arr2['donated'];
				$by = "<a href='account-details.php?id=$posterid'><b>$postername</b></a>" . ($donated > 0 ? "<img src='".$site_config['SITEURL']."/images/star.gif' alt='Donated'>" : "") . "";
			}

		 if (!$avatar)
            $avatar = $site_config['SITEURL']."/images/default_avatar.gif";
      # print("<a name=$postid>\n");
        print("<a name='last' id='$postid'></a>");
        if ($pn == $pc) {
         #   print("<a name=last>\n");
            if ($postid > $lpr && $CURUSER)
                mysql_query("UPDATE forum_readposts SET lastpostread=$postid WHERE userid=$userid AND topicid=$topicid") or forumsqlerr(__FILE__, __LINE__);
        }
//working here
//Post Top

		print("<div class='f-post' style='padding-bottom:6px'><table align='center' cellpadding='3' cellspacing='0' style='border-collapse: collapse' class='f-border' width='100%' ><tr ><td width='150' align='center' class='p-title-barra '>$by<td with='100%' align='left' class='p-title-lado'><span class='small' >Postado em $added </span></tr>");
	//Post Middle

		$body = stripslashes(format_comment($arr["body"]));

		if (is_valid_id($arr['editedby'])) {
			$res2 = mysql_query("SELECT username FROM users WHERE id=$arr[editedby]");

			if (mysql_num_rows($res2) == 1) {
				$arr2 = mysql_fetch_assoc($res2);
				//edited by comment out if needed
				$body .= "<br><br><span class='small'><i>Última edição por <a href='account-details.php?id=$arr[editedby]'>$arr2[username]</b></a> em ".utc_to_tz($arr["editedat"])."</i></span><br>\n";
				$body .= "\n";
			}
		}

		$quote = htmlspecialchars($arr["body"]);

		$postcount1 = mysql_query("SELECT COUNT(forum_posts.userid) FROM forum_posts WHERE id=$posterid") or forumsqlerr();

		while($row = mysql_fetch_array($postcount1)) {

			if  ($privacylevel == "strong" && $CURUSER["control_panel"] != "yes"){//hide stats, but not from staff
				$useruploaded = "---";
				$userdownloaded = "---";
				$userratio = "---";
				$nposts = "-";
				$tposts = "-";
			}
			print ("<tr valign='top'><td width='160' align='left' class='f-border comment-details'><center><img width='150' hspace='5' height='250' src='$avatar'></center><center><font class=tipo_forum>$grupos_exe</font></center><center><br><font class=tipo_forum><b>$placa</B></font><center><br><font class=tipo_forum>Ratio:  <B> $userratio </B></font><br></center><center><font class=tipo_forum>[</font><font color=#00CC00><B> $useruploaded</B></font><font color=#FF0000>  <B>$userdownloaded </B></font> <font class=tipo_forum>]</font></center><br><font class=tipo_forum>Sexo: <B>$sexo</B></font><br><font class=tipo_forum>Pais: <B>$usercountry</B></font><br><br><font class=tipo_forum>Posts:<B>$forumposts</B></font><br><font class=tipo_forum>Lançamentos:<B>$numtorrents</B></font><br><br><font class=tipo_forum>Status: <B>$online</B></font><br><br></td>");

			print ("<td class='f-border comment'><font class=tipo_forum>$body</font><br>");

			if (!$usersignature){
				print("<br><br></td></tr>\n");
			}else{
				print("<br><br><font class=tipo_forum>---------------</font><br>$usersignature</td></tr>\n");
			}
		}

//Post Bottom

	
	print("<tr  class='p-title-barra '><td width='150' align='center'><nobr> <a href='account-details.php?id=$posterid'><img src=".$themedir."icon_profile.gif ></a> <a href='mailbox.php?Escrever&id=$posterid'><img src='".$themedir."icon_pm.gif' ></a> </nobr><td with='100%'>");

	print ("<div style='float: left;'><a href='report.php?forumid=$topicid&forumpost=$postid'><img src='".$themedir."p_report.gif'  alt='Report This Post'></a>&nbsp;<a href='javascript:scroll(0,0);'><img src='".$themedir."p_up.gif'  alt='Go to the top of the page'></a></div><div align='right'>");
	$like = get_row_count("forum_ratings", "WHERE pr = 'g' AND pid = $postid");
        $dislike = get_row_count("forum_ratings", "WHERE pr = 'b' AND pid = $postid");
        
        print ("<a href='forums.php?action=rating&amp;pr=0&amp;pid=$postid'><img src='images/ok.gif' alt='' title='' border='0' /></a> <font color='#ff0000'>$dislike</font>
                        <a href='forums.php?action=rating&amp;pr=1&amp;pid=$postid'><img src='images/nao.gif' alt='' title='' border='0' /></a> <font color='green'>$like</font>&nbsp;");
 
	//define buttons and who can use them
	if ($CURUSER["id"] == $posterid || $CURUSER["edit_forum"] == "yes" || $CURUSER["delete_forum"] == "yes"){
		print ("<a href='forums.php?action=editpost&amp;postid=$postid'><img src='".$themedir."p_edit.png' border='0' alt='' /></a>&nbsp;");
	}
	if ($CURUSER["delete_forum"] == "yes"){
		print ("<a href='forums.php?action=deletepost&amp;postid=$postid&amp;sure=0'><img src='".$themedir."p_delete.png' border='0' alt='' /></a>&nbsp;");
	}
	if (!$locked && $maypost) {
		print ("<a href=\"javascript:SmileIT('[quote=$postername] $quote [/quote]', 'Form', 'body');\"><img src='".$themedir."p_quote.png' border='0' alt='' /></a>&nbsp;");
		print ("<a href='#bottom'><img src='".$themedir."p_reply.png' alt='' /></a>");
	}
		print("&nbsp;</div></td></tr></table></div>");
	}
//-------- end posts table ---------//
	print("<center> ".$pagemenu."</center> ");

	//quick reply
	if (!$locked && $CURUSER){
	//begin_framec("Reply", $newtopic = false);

	$newtopic = false;
	print("<a name='bottom'></a>");
    print("<form class='f-form' name='Form' method='post' action='?action=post'>\n");
    if ($newtopic)
		print("<input type='hidden' name='forumid' value='$id' />\n");
    else
		print("<input type='hidden' name='topicid' value='$topicid' />\n");
    print("<table cellspacing='0' cellpadding='0' align='center'>");

    if ($newtopic)
		print("<tr><td class='alt2 f-border'>Assunto</td><td class='alt1 f-border' align='left' style='padding: 0px'><input type='text' size='100' maxlength='$maxsubjectlength' name='subject' style='border: 0px; height: 19px' /></td></tr>\n");

	echo "<center><tr><td  colspan='3'>";
	textbbcode("Form", "body",$dossier, htmlspecialchars($arr["body"]));
	echo "</td></tr></center>\n";
    print("<tr><td colspan='3' align='center'><br /><center><input type='image' src='". $themedir ."button_reply.png' alt='' /></center></td></tr>\n");
	
    print("</table></form>\n");
	//end_framec();
	print (" </fieldset>");
	}else{
	print ("<center><img src='".$themedir."topic_lock.png' alt='Trancado' /></center><br />");
	}
	//end quick reply

	if ($locked)
		print("<center><b>Tópico trancado pela moderação.<br>
		Não é permitido responder a este tópico.</b></center>\n\n");
	elseif (!$maypost)
		print("<center><i>Você não tem permissão para postar neste fórum.</i></center>\n");
    //insert page numbers and quick jump

   // insert_quick_jump_menu($forumid);

	// MODERATOR OPTIONS
     if ($CURUSER["delete_forum"] == "yes" || $CURUSER["edit_forum"] == "yes") {
      print("<br /><div class='f-cat f-border' align='center'>Opções Moderação</div>\n");
     $res = mysql_query("SELECT id,name,minclasswrite FROM forum_forums  ORDER BY name");
      print("<div class='f-border f-form' style='padding:3px'>\n");
      print("<form method='post' action='forums.php?action=renametopic'>\n");
      print("<input type='hidden' name='topicid' value='$topicid' />\n");
      print("<input type='hidden' name='returnto' value='forums.php?action=viewtopic&amp;topicid=$topicid' />\n");
	  print("<div align='center'  style='padding:3px'>Renomear tópico: <input type='text' name='subject' size='60' maxlength='$maxsubjectlength' value='" . stripslashes(htmlspecialchars($subject)) . "' />\n");
      print("<input type='submit' value='Salvar' />");
      print("</div></form>\n");
      print("<form method='post' action='forums.php?action=movetopic&amp;topicid=$topicid'>\n");
      print("<div class='f-form' align='center' style='padding:3px'>");
      print("Mover tópico para: <select name='forumid'>");
      while ($arr = mysql_fetch_assoc($res))
        if ($arr["id"] != $forumid && get_user_class() >= $arr["minclasswrite"])
          print("<option value='" . $arr["id"] . "'>" . $arr["name"] . "</option>\n");
      print("</select> <input type='submit' value='Salvar' /></div></form>\n");
 print("<div align='center'>\n");
			if ($locked)
				print("Destrancar: <a href='forums.php?action=unlocktopic&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Destrancar'><img src='". $themedir ."topic_unlock.png' alt='UnLock Tópico' /></a>\n");
			else
				print("Trancar: <a href='forums.php?action=locktopic&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Travar'><img src='". $themedir ."topic_lock.png' alt='Bloquear Tópico' /></a>\n");
			print("Excluir tópico: <a href='forums.php?action=deletetopic&amp;topicid=$topicid&amp;sure=0' title='Excluir'><img src='". $themedir ."topic_delete.png' alt='Excluir Tópico' /></a>\n");
			if ($sticky)
			   print("Desfixar: <a href='forums.php?action=unsetsticky&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Desfixar'><img src='". $themedir ."folder_sticky_new.png' alt='Tópico Fixo' /></a>\n");
			else
			   print("Fixar: <a href='forums.php?action=setsticky&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Fixar'><img src='". $themedir ."folder_sticky.png' alt='Tópico Fixo' /></a>\n");
			print("</div><br /></div>\n");

    }
    end_framec();

    stdfoot();
    die;
}

///////////////////////////////////////////////////////// Action: REPLY
if ($action == "reply") {
	$topicid = $_GET["topicid"];
	if (!is_valid_id($topicid))
    showerror("Fórum erro", "No ID Topic Fórum $topicid");
	stdhead("Publicar resposta");
	begin_framec("Publicar resposta");
	insert_compose_frame($topicid, false);
	end_framec();
	stdfoot();
	die;
}

///////////////////////////////////////////////////////// Action: MOVE TOPIC
if ($action == "movetopic") {
    $forumid = $_POST["forumid"];
    $topicid = $_GET["topicid"];
    if (!is_valid_id($forumid) || !is_valid_id($topicid) || $CURUSER["delete_forum"] != "yes" || $CURUSER["edit_forum"] != "yes")
         showerror("Fórum erro", "Não Fórum Válido ID $forumid Or Not ID Tópico Válido Fórum $topicid");

    // Make sure topic and forum is valid
    $res = @mysql_query("SELECT minclasswrite FROM forum_forums WHERE id=$forumid");
    if (mysql_num_rows($res) != 1)
      showerror("Erro", "Fórum não encontrado.");
    $arr = mysql_fetch_row($res);
    if (get_user_class() < $arr[0])
    showerror("Forum erro", "Não Permitido");
    $res = @mysql_query("SELECT subject,forumid FROM forum_topics WHERE id=$topicid");
    if (mysql_num_rows($res) != 1)
      showerror("Erro", "Tópico não encontrado.");
    $arr = mysql_fetch_assoc($res);
    if ($arr["forumid"] != $forumid)
      @mysql_query("UPDATE forum_topics SET forumid=$forumid, moved='yes' WHERE id=$topicid");

    // Redirect to forum page
    header("Location: $site_config[SITEURL]/forums.php?action=viewforum&forumid=$forumid");
    die;
}

///////////////////////////////////////////////////////// Action: DELETE TOPIC
if ($action == "deletetopic") {
	$topicid = $_GET["topicid"];
	if (!is_valid_id($topicid) || $CURUSER["delete_forum"] != "yes")
        showerror("Erro", "Acesso Negado!");
	
	$sure = $_GET["sure"];
	if ($sure == "0") 
		showerror("Excluir tópico", "Verificação de sanidade: Você está prestes a excluir um tópico. clique <a href='forums.php?action=deletetopic&amp;topicid=$topicid&amp;sure=1'>Aqui</a> se tiver certeza.");

	mysql_query("DELETE FROM forum_topics WHERE id=$topicid");
	mysql_query("DELETE FROM forum_posts WHERE topicid=$topicid");
    mysql_query("DELETE FROM forum_readposts WHERE topicid=$topicid");
	header("Location: $site_config[SITEURL]/forums.php");
	die;
}

///////////////////////////////////////////////////////// Action: EDIT TOPIC
if ($action == "editpost") {
	$postid = $_GET["postid"];
	if (!is_valid_id($postid))
        showerror("Erro", "Acesso Negado!");
    $res = mysql_query("SELECT * FROM forum_posts WHERE id=$postid");
	if (mysql_num_rows($res) != 1)
		showerror("Erro", "No post com ID $postid.");
	$arr = mysql_fetch_assoc($res);
    if ($CURUSER["id"] != $arr["userid"] && $CURUSER["delete_forum"] != "yes" && $CURUSER["edit_forum"] != "yes")
		showerror("Erro", "Acesso Negado!");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$body = $_POST['body'];
			if ($body == "")
				showerror("Erro", "Corpo não pode estar vazio!");
		$body = sqlesc($body);
		$editedat = sqlesc(get_date_time());
		mysql_query("UPDATE forum_posts SET body=$body, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$postid");
		$returnto = $_POST["returnto"];
			if ($returnto != "")
				header("Location: $returnto");
			else
				showerror("Sucesso", "Mensagem editada com sucesso.");
	}

    stdhead();

    begin_framec("Edit Post");
    print("<form class='f-form' name='Form' method='post' action='?action=editpost&amp;postid=$postid'>\n");
    print("<input type='hidden' name='returnto' value='" . htmlspecialchars($_SERVER["HTTP_REFERER"]) . "' />\n");
    print("<center><table  cellspacing='0' cellpadding='5'>\n");
    print("<tr><td colspan='2'>\n");
    textbbcode("Form", "body",$dossier, htmlspecialchars($arr["body"]));
    print("</td></tr>");

	
    print("<tr><td align='center' colspan='2'><input type='submit' value='Submit' /></td></tr>\n");
    print("</table></center>\n");
    print("</form>\n");
    end_framec();
    stdfoot();
    die;
}

///////////////////////////////////////////////////////// Action: DELETE POST
if ($action == "deletepost") {
	$postid = $_GET["postid"];
	$sure = $_GET["sure"];
	if ($CURUSER["delete_forum"] != "yes" || !is_valid_id($postid))
        showerror("Erro", "Acesso Negado!");

    //SURE?
	if ($sure == "0") {
		showerror("Excluir post", "Verificação de sanidade: Você está prestes a apagar um post. clique <a href='forums.php?action=deletepost&amp;postid=$postid&amp;sure=1'>Aqui</a> se tiver certeza.");
    }

	//------- Get topic id
    $res = mysql_query("SELECT topicid FROM forum_posts WHERE id=$postid");
    $arr = mysql_fetch_row($res) or showerror("Error", "Mensagem não encontrada");
    $topicid = $arr[0];

    //------- We can not delete the post if it is the only one of the topic
    $res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE topicid=$topicid");
    $arr = mysql_fetch_row($res);
    if ($arr[0] < 2)
		showerror("Erro", "Não é possível excluir pós, é o post só sobre o tema. você deveria <a href='forums.php?action=deletetopic&amp;topicid=$topicid&amp;sure=1'>Excluir o tópico</a> em vez.\n");

    //------- Delete post
    mysql_query("DELETE FROM forum_posts WHERE id=$postid");

    //------- Update topic
    update_topic_last_post($topicid);
    header("Location: $site_config[SITEURL]/forums.php?action=viewtopic&topicid=$topicid");
    die;
}

///////////////////////////////////////////////////////// Action: LOCK TOPIC
if ($action == "locktopic") {
	$forumid = $_GET["forumid"];
	$topicid = $_GET["topicid"];
	$page = $_GET["page"];
	if (!is_valid_id($topicid) || $CURUSER["delete_forum"] != "yes" || $CURUSER["edit_forum"] != "yes")
        showerror("Erro", "Acesso Negado!");
	mysql_query("UPDATE forum_topics SET locked='yes' WHERE id=$topicid");
	header("Location: $site_config[SITEURL]/forums.php?action=viewforum&forumid=$forumid&page=$page");
	die;
}

///////////////////////////////////////////////////////// Action: UNLOCK TOPIC
if ($action == "unlocktopic") {
    $forumid = $_GET["forumid"];
    $topicid = $_GET["topicid"];
    $page = $_GET["page"];
    if (!is_valid_id($topicid) || $CURUSER["delete_forum"] != "yes" || $CURUSER["edit_forum"] != "yes")
        showerror("Erro", "Acesso Negado!");
    mysql_query("UPDATE forum_topics SET locked='no' WHERE id=$topicid");
    header("Location: $site_config[SITEURL]/forums.php?action=viewforum&forumid=$forumid&page=$page");
    die;
}

///////////////////////////////////////////////////////// Action: STICK TOPIC
if ($action == "setsticky") {
   $forumid = $_GET["forumid"];
   $topicid = $_GET["topicid"];
   $page = $_GET["page"];
   if (!is_valid_id($topicid) || ($CURUSER["delete_forum"] != "yes" && $CURUSER["edit_forum"] != "yes"))
        showerror("Erro", "Acesso Negado!");
   mysql_query("UPDATE forum_topics SET sticky='yes' WHERE id=$topicid");
   header("Location: $site_config[SITEURL]/forums.php?action=viewforum&forumid=$forumid&page=$page");
   die;
}

///////////////////////////////////////////////////////// Action: UNSTICK TOPIC
if ($action == "unsetsticky") {
   $forumid = $_GET["forumid"];
   $topicid = $_GET["topicid"];
   $page = $_GET["page"];
   if (!is_valid_id($topicid) || ($CURUSER["delete_forum"] != "yes" && $CURUSER["edit_forum"] != "yes"))
        showerror("Erro", "Acesso Negado!");
   mysql_query("UPDATE forum_topics SET sticky='no' WHERE id=$topicid");
   header("Location: $site_config[SITEURL]/forums.php?action=viewforum&forumid=$forumid&page=$page");
   die;
}

///////////////////////////////////////////////////////// Action: RENAME TOPIC
if ($action == 'renametopic') {
	if ($CURUSER["delete_forum"] != "yes" && $CURUSER["edit_forum"] != "yes")
        showerror("Erro", "Acesso Negado!");
  	$topicid = $_POST['topicid'];
  	if (!is_valid_id($topicid))
        showerror("Erro", "Acesso Negado!");
  	$subject = $_POST['subject'];
 	if ($subject == '')
		showerror('Erro', 'Você deve digitar um novo título!');
  	$subject = sqlesc($subject);
  	mysql_query("UPDATE forum_topics SET subject=$subject WHERE id=$topicid");
  	$returnto = $_POST['returnto'];
  	if ($returnto)
		header("Location: $returnto");
  	die;
}

///////////////////////////////////////////////////////// Action: VIEW FORUM
if ($action == "viewforum") {
	$forumid = $_GET["forumid"];
	if (!is_valid_id($forumid))
        showerror("Erro", "Acesso Negado!");
    $page = $_GET["page"];
    $userid = $CURUSER["id"];

    //------ Get forum name
    $res = mysql_query("SELECT name, minclassread, guest_read FROM forum_forums WHERE id=$forumid");
    $arr = mysql_fetch_assoc($res);
    $forumname = $arr["name"];
    if (!$forumname || get_user_class() < $arr["minclassread"] && $arr["guest_read"] == "no")
		showerror("Erro", "Não é permitido");

    //------ Get topic count
    $perpage = 20;
    $res = mysql_query("SELECT COUNT(*) FROM forum_topics WHERE forumid=$forumid");
    $arr = mysql_fetch_row($res);
    $num = $arr[0];
    if ($page == 0)
      $page = 1;
    $first = ($page * $perpage) - $perpage + 1;
    $last = $first + $perpage - 1;
    if ($last > $num)
      $last = $num;
    $pages = floor($num / $perpage);
    if ($perpage * $pages < $num)
      ++$pages;

    //------ Build menu
    $menu = "<p align='center'><b>\n";
    $lastspace = false;
    for ($i = 1; $i <= $pages; ++$i) {
      if ($i == $page)
        $menu .= "<span class='next-prev'>$i</span>\n";
      elseif ($i > 3 && ($i < $pages - 2) && ($page - $i > 3 || $i - $page > 3)) {
    	if ($lastspace)
          continue;
   	    $menu .= "... \n";
    	$lastspace = true;
      }
      else {
        $menu .= "<a href='forums.php?action=viewforum&amp;forumid=$forumid&amp;page=$i'>$i</a>\n";
        $lastspace = false;
      }
      if ($i < $pages)
        $menu .= "</b>|<b>\n";
    }
    $menu .= "<br />\n";
    if ($page == 1)
      $menu .= "<span class='next-prev'>«« Página Anterior</span>";
    else
      $menu .= "<a href='forums.php?action=viewforum&amp;forumid=$forumid&amp;page=" . ($page - 1) . "'>«« Página Anterior</a>";
    $menu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if ($last == $num)
      $menu .= "<span class='next-prev'>Próxima Página »»</span>";
    else
      $menu .= "<a href='forums.php?action=viewforum&amp;forumid=$forumid&page=" . ($page + 1) . "'>Próxima Página »» </a>";
    $menu .= "</b></p>\n";
    $offset = $first - 1;

    //------ Get topics data and display category
    $topicsres = mysql_query("SELECT * FROM forum_topics WHERE forumid=$forumid ORDER BY sticky, lastpost DESC LIMIT $offset,$perpage");

    stdhead("Forum : $forumname");
    $numtopics = mysql_num_rows($topicsres);
    begin_framec("$forumname");
	forumheader("<a href='forums.php?action=viewforum&amp;forumid=$forumid'>$forumname</a>");
	
	if ($CURUSER)
		print ("<table cellpadding='0' cellspacing='5' width='100%'><tr><td><div align='right'><a href='forums.php?action=newtopic&amp;forumid=$forumid'><img src='". $themedir. "button_new_post.png' alt='' /></a></div></td></tr></table>");

    if ($numtopics > 0) {
	print("<table cellpadding='3' cellspacing='0' class='f-border' width='100%'>");

	print("<tr class='f-title f-border'><th align='left' colspan='2' width='100%'>Tópico</th><th>Respostas</th><th>Visitas</th><th>Autor</th><th align='right'>Último Post</th>\n");
		if ($CURUSER["edit_forum"] == "yes" || $CURUSER["delete_forum"] == "yes")
			print("<th>Moderador</th>");
      print("</tr>\n");
      while ($topicarr = mysql_fetch_assoc($topicsres)) {
			$topicid = $topicarr["id"];
			$topic_userid = $topicarr["userid"];
			$locked = $topicarr["locked"] == "yes";
			$moved = $topicarr["moved"] == "yes";
			$sticky = $topicarr["sticky"] == "yes";
			//---- Get reply count
			$res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE topicid=$topicid");
			$arr = mysql_fetch_row($res);
			$posts = $arr[0];
			$replies = max(0, $posts - 1);
			$tpages = floor($posts / $postsperpage);
			if ($tpages * $postsperpage != $posts)
			  ++$tpages;
			if ($tpages > 1) {
			  $topicpages = " (<img src='". $site_config['SITEURL'] ."/images/forum/multipage.png' alt='' />";
			  for ($i = 1; $i <= $tpages; ++$i)
				$topicpages .= " <a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'>$i</a>";
			  $topicpages .= ")";
        }
        else
          $topicpages = "";





    //



        //---- Get userID and date of last post
        $res = mysql_query("SELECT * FROM forum_posts WHERE topicid=$topicid   ORDER BY id DESC LIMIT 1");
        $arr = mysql_fetch_assoc($res);
        $lppostid = $arr["id"];
        $lpuserid = $arr["userid"];
        $lpadded = utc_to_tz($arr["added"]);

        //------ Get name of last poster
        $res = mysql_query("SELECT * FROM users WHERE id=$lpuserid");
        if (mysql_num_rows($res) == 1) {
          $arr = mysql_fetch_assoc($res);
          $lpusername = "<a href='account-details.php?id=$lpuserid'>$arr[username]</a>";
        }
        else
          $lpusername = "Desativado";

        //------ Get author
        $res = mysql_query("SELECT username FROM users WHERE id=$topic_userid");
        if (mysql_num_rows($res) == 1) {
          $arr = mysql_fetch_assoc($res);
          $lpauthor = "<a href='account-details.php?id=$topic_userid'>$arr[username]</a>";
        }
        else
          $lpauthor = "Desativado";

		// Topic Views
		$viewsq = mysql_query("SELECT views FROM forum_topics WHERE id=$topicid");
		$viewsa = mysql_fetch_array($viewsq);
		$views = $viewsa[0];
		// End

        //---- Print row
		if ($CURUSER) {
			$r = mysql_query("SELECT lastpostread FROM forum_readposts WHERE userid=$userid AND topicid=$topicid");
			$a = mysql_fetch_row($r);
		}
        $new = !$a || $lppostid > $a[0];
        $topicpic = ($locked ? ($new ? "folder_locked_new" : "folder_locked") : ($new ? "folder_new" : "folder"));
        $subject = ($sticky ? "<b>Fixo: </b>" : "") . "<a href='forums.php?action=viewtopic&amp;topicid=$topicid'><b>" .
        encodehtml(stripslashes($topicarr["subject"])) . "</b></a>$topicpages";
        print("<tr class='f-row f-border'><td class='f-img' valign='middle'><img src='". $themedir ."$topicpic.png' alt='' />" .
         "</td><td class='alt3' align='left' width='100%'>\n" .
         "$subject</td><td class='alt2 f-border' align='center'>$replies</td>\n" .
		 "<td class='alt3 f-border' align='center'>$views</td>\n" .
         "<td class='alt2 f-border' align='center'>$lpauthor</td>\n" .
         "<td class='alt3 f-border' align='right'><span class='small'>por&nbsp;$lpusername<br /><span style='white-space: nowrap'>$lpadded</span></span></td>\n");
	     if ($CURUSER["edit_forum"] == "yes" || $CURUSER["delete_forum"] == "yes") {
			  print("<td class='alt3 f-border' align='center'><span style='white-space: nowrap'>\n");
			if ($locked)
				print("<a href='forums.php?action=unlocktopic&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Destrancar'><img src='". $themedir ."topic_unlock.png' alt='UnLock Tópico' /></a>\n");
			else
				print("<a href='forums.php?action=locktopic&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Trancar'><img src='". $themedir ."topic_lock.png' alt='Bloquear Tópico' /></a>\n");
				print("<a href='forums.php?action=deletetopic&amp;topicid=$topicid&amp;sure=0' title='Excluir'><img src='". $themedir ."topic_delete.png' alt='Excluir Tópico' /></a>\n");
			if ($sticky)
			   print("<a href='forums.php?action=unsetsticky&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Desfixar'><img src='". $themedir ."folder_sticky_new.png' alt='Desfixar' /></a>\n");
			else
			   print("<a href='forums.php?action=setsticky&amp;forumid=$forumid&amp;topicid=$topicid&amp;page=$page' title='Fixar'><img src='". $themedir ."folder_sticky.png' alt='Fixar' /></a>\n");
			  print("</span></td>\n");
        }
        print("</tr>\n");
      } // while
   //   end_table();
   print("</table>");
      print($menu);
    } // if
    else
      print("<p align='center'>Nenhum tópico encontrado</p>\n");
    print("<table cellspacing='5' cellpadding='0'><tr valign='middle'>\n");
    print("<td><img src='". $themedir ."folder_new.png' style='margin-right: 5px' alt='' /></td><td >Novo post</td>\n");
	 print("<td><img src='". $themedir ."folder.png' style='margin-left: 10px; margin-right: 5px' alt='' />" .
     "</td><td>Sem novas mensagens</td>\n");
    print("<td><img src='". $themedir ."folder_locked.png' style='margin-left: 10px; margin-right: 5px' alt='' />" .
     "</td><td>Tópico bloqueado</td></tr></table>\n");
    $arr = get_forum_access_levels($forumid) or die;
    $maypost = get_user_class() >= $arr["write"];
    if (!$maypost)
		print("<p><i>Você não tem permissão para postar neste fórum.</i></p>\n");
    print("<table cellspacing='0' cellpadding='0'><tr>\n");

    if ($maypost)
		print("<td><a href='forums.php?action=newtopic&amp;forumid=$forumid'><img src='" . $themedir . "button_new_post.png' alt='' /></a></td>\n");
    print("</tr></table>\n");
    insert_quick_jump_menu($forumid);
    end_framec();
    stdfoot();
    die;
}

///////////////////////////////////////////////////////// Action: VIEW NEW POSTS
if ($action == "viewunread") {
	$userid = $CURUSER['id'];
	$maxresults = 25;
	$res = mysql_query("SELECT id, forumid, subject, lastpost FROM forum_topics ORDER BY lastpost");
    stdhead();
	begin_framec("Tópicos com mensagens não lidas");
	forumheader("Novos Tópicos");

    $n = 0;
    $uc = get_user_class();
    while ($arr = mysql_fetch_assoc($res)) {
      $topicid = $arr['id'];
      $forumid = $arr['forumid'];

      //---- Check if post is read
	  if ($CURUSER) {
		$r = mysql_query("SELECT lastpostread FROM forum_readposts WHERE userid=$userid AND topicid=$topicid");
		$a = mysql_fetch_row($r);
	  }
      if ($a && $a[0] == $arr['lastpost'])
        continue;
$whereaad=array();



if ($CURUSER["ver_xxx"]!="yes") {
    $whereaad[] = "forum_forums.id != '43'";
    $whereaad[] = "forum_forums.id != '16'";

}
     $whereaad[] = "forum_forums.id != '0'";
$wheread = implode(" AND ", $whereaad);
    //

      //---- Check access & get forum name
      $r = mysql_query("SELECT name, minclassread, guest_read FROM forum_forums WHERE id=$forumid AND ".$wheread." ");
      $a = mysql_fetch_assoc($r);
      if ($uc < $a['minclassread'] && $a["guest_read"] == "no")
        continue;
      ++$n;
      if ($n > $maxresults)
        break;
      $forumname = $a['name'];
      if ($n == 1) {
        print("<table class='f-border' cellspacing='0' cellpadding='6' width='100%'>\n");
        print("<tr class='f-title f-border'><th align='left'>Tópico</th><th align='left' colspan='2'>Fórum</th></tr>\n");
      }
      print("<tr class='f-row f-border'><td class='f-img' valign='middle'>" .
       "<img src='". $themedir ."folder_unlocked_new.png' style='margin: 5px' alt='Destrancar' /></td><td class='alt3'>" .
       "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=last#last'><b>" . stripslashes(htmlspecialchars($arr["subject"])) ."</b></a></td><td class='alt2' align='left'><a href='forums.php?action=viewforum&amp;forumid=$forumid'><b>$forumname</b></a></td></tr>\n");
    }
    if ($n > 0) {
      print("</table><br />\n");
      if ($n > $maxresults)
        print("<p>mais do que $maxresultsitens encontrados, exibindo primeiro $maxresults.</p>\n");
      print("<center><a href='forums.php?catchup'><b>Marcar Fóruns como Lidos.</b></a></center><br />\n");
    }
    else
      print("<b>Nothing found</b>");
	 end_framec();
    stdfoot();
    die;
}

///////////////////////////////////////////////////////// Action: SEARCH
if ($action == "search") {
	stdhead("Pesquisa avançada de fóruns");
	begin_framec("Pesquisa avançada de fóruns");
	forumheader("Pesquisa avançada de fóruns");


$keywords = trim($_GET["keywords"]);

$author= trim($_GET['author']);

if ($author!=""){
$queryusers= "select id from users where username=".sqlesc($author)." limit 1";
$userquery = mysql_query($queryusers);
$num_res = mysql_num_rows($userquery);
if ($num_res<1){
print("<b><br>Autor pesquisa não existe, por favor verifique o nome digitado e tente novamente...</b><br>");
$userfilter="";
}
else {
$userfilterid= mysql_fetch_assoc($userquery);
$userfilterid= $userfilterid['id'];
$userfilter= " AND forum_posts.userid=".$userfilterid;
}
}


$sort = (int) $_GET['sort'];
switch ($sort){
case 0:
$sortSel0 = "selected=\"selected\"";
$order_by="matchweight";
break;
case 1:
$sortSel1 = "selected=\"selected\"";
$order_by="forumid";
break;
case 2:
$sortSel2 = "selected=\"selected\"";
$order_by="subject";
break;
case 3:
$sortSel3 = "selected=\"selected\"";
$order_by="added";
break;
case 4:
$sortSel4 = "selected=\"selected\"";
$order_by="lastpost_time";
break;
case 5:
$sortSel5 = "selected=\"selected\"";
$order_by="views";
break;
case 6:
$sortSel6 = "selected=\"selected\"";
$order_by="replies";
break;
default:
$sortSel0 = "selected=\"selected\"";
$order_by="matchweight";
}


$sort_dir = (int) $_GET['sort_dir'];
if ($sort_dir==1){
$sortDirSel1 = "checked=\"checked\"";
$sort_order= 'ASC';
}
else{
$sortDirSel2 = "checked=\"checked\"";
$sort_order= 'DESC';
}

$numres = (int) $_GET["numres"];
switch ($numres){
case 0:
$numSel1 = "selected=\"selected\"";
$maxresults=25;
break;
case 1:
$numSel2 = "selected=\"selected\"";
$maxresults=50;
break;
case 2:
$numSel3 = "selected=\"selected\"";
$maxresults=100;
break;
case 3:
$numSel4 = "selected=\"selected\"";
$maxresults=200;
break;
case 4:
$numSel5 = "selected=\"selected\"";
$maxresults=300;
break;
default:
$numSel1 = "selected=\"selected\"";
$maxresults=25;
}


$search_time = (int) $_GET["search_time"];
switch ($search_time){
case 0:
$whenSel= "selected=\"selected\"";
$searchWhen="";
break;
case 1:
$whenSel1= "selected=\"selected\"";
$dt24 = gmtime() - 24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 2:
$whenSel2= "selected=\"selected\"";
$dt24 = gmtime() - 2*24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 3:
$whenSel3= "selected=\"selected\"";
$dt24 = gmtime() - 3*24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 4:
$whenSel4= "selected=\"selected\"";
$dt24 = gmtime() - 4* 24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 5:
$whenSel5= "selected=\"selected\"";
$dt24 = gmtime() - 5*24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 6:
$whenSel6= "selected=\"selected\"";
$dt24 = gmtime() - 6 *24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;

case 7:
$whenSel7= "selected=\"selected\"";
$dt24 = gmtime() - 7*24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 14:
$whenSel8= "selected=\"selected\"";
$dt24 = gmtime() - 14* 24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 30:
$whenSel9= "selected=\"selected\"";
$dt24 = gmtime() - 30*24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
case 90:
$whenSel10= "selected=\"selected\"";
$dt24 = gmtime() - 90*24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;

case 180:
$whenSel11= "selected=\"selected\"";
$dt24 = gmtime() - 180* 24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;

case 364:
$whenSel12= "selected=\"selected\"";
$dt24 = gmtime() - 364*24 * 60 * 60;
$searchWhen=" AND added>='".get_date_time($dt24)."'";
break;
default:
$whenSel= "selected=\"selected\"";
$searchWhen="";

}




if ($all) {
$wherecatina = array();
$addparam = "";
}
if ($sort_dir==1) $sort_dir=0;
else $sort_dir=1;

$addparam.= "author=".htmlspecialchars($author)."&amp;";
$addparam.= "sort_dir=".$sort_dir."&amp;";
$addparam.= "search_time=$search_time&amp;";
$addparam.= "numres=$numres&amp;";
$addparam.= "keywords=".trim($_GET["keywords"]);

if (count($wherecatina) > 1) $wherecatin = implode(",",$wherecatina);
elseif (count($wherecatina) == 1) $wherea[] = "forumid = ".$wherecatina[0];

if (sizeof($wherea)!=0)
$where = implode(" AND ", $wherea);

if ($wherecatin) $where .= ($where ? " AND " : "") . "forumid IN(" . $wherecatin . ")";
if ($where !="") $where = " AND ".$where;

if (($keywords != "")||((($author!="")&&($userfilter!=""))||(($search_time<8)&&($search_time!=0))))
{
print("<p>Por: <b>" . htmlspecialchars($keywords) . "</b></p>\n");
// $maxresults = 50;
$kw = sqlesc($keywords);

if ($keywords =="")
$fields=" 'x'='x'";
else{
if (($_GET['body']==1)&&($_GET['topic']==1))
$fields= "(subject like ".sqlesc('%'.$keywords.'%')." OR MATCH (body) AGAINST ($kw) )";
else if ($_GET['topic']==1)
$fields="subject like ".sqlesc('%'.$keywords.'%');
else
$fields="MATCH (body) AGAINST ($kw)";
}

$query = "SELECT forum_posts.id,body,topicid,forum_posts.userid,added,forumid, subject, views,match(body) against ($kw) as matchweight FROM forum_posts,forum_topics WHERE $fields and forum_posts.topicid=forum_topics.id $where $searchWhen $userfilter order by $order_by $sort_order LIMIT " . ($maxresults + 1);
// print($query);
$res = mysql_query($query);
// search and display results...
$num = mysql_num_rows($res);
if ($num > $maxresults)
{
$num = $maxresults;
print("<p>Encontrado mais de $maxresults posts; exibição de primeira $num.</p>\n");
}
else
print("<p>Encontrado $num resultados</p>\n");

if ($num == 0)
print("<p><b>Desculpe, nada encontrado!</b></p>");
else
{
print("<p><table border=1 cellspacing=0 width=100%' cellpadding=5>\n");
print("<tr><td class=colhead><a href=forums.php?action=search&$addparam&sort=3>Posts</a></td>".
"<td class=colhead align=left><a href=forums.php?action=search&$addparam&sort=2>Tópico</a></td>".
"<td class=colhead align=left><a href=forums.php?action=search&$addparam&sort=1>Fórum</a></td>".
"<td class=colhead><a href=forums.php?action=search&$addparam&sort=5>Visitas</a></td>".
"<td class=colhead align=left>Postado por</td></tr>\n");
for ($i = 0; $i < $num; ++$i)
{
$post = mysql_fetch_assoc($res);
// $res2 = do_mysql_query("SELECT forumid, subject FROM topics WHERE id=$post[topicid]") or
// sqlerr(__FILE__, __LINE__);
// $topic = mysql_fetch_assoc($res2);
$res2 = mysql_query("SELECT name,minclassread FROM forum_forums WHERE id=$post[forumid]") or
sqlerr(__FILE__, __LINE__);
$forum = mysql_fetch_assoc($res2);
if ($forum["name"] == "" || $forum["minclassread"] > $CURUSER["class"])
continue;
$res2 = mysql_query("SELECT username,id FROM users WHERE id=$post[userid]") or
sqlerr(__FILE__, __LINE__);
$user = mysql_fetch_assoc($res2);
if ($user["username"] == "")
$user["username"] = "[$post[userid]]";

++$klap;


$body_print = "<a href=\"javascript: klappe_news('a$klap')\"><b>$post[id]</b>&nbsp;<img width=13 border=0 src=/images/plus.gif></a>";
print("<tr><td $style>$body_print</td><td align=left $style><a href=?action=viewtopic&highlight=".
urlencode(htmlspecialchars($keywords)) . "&topicid=$post[topicid]&page=p$post[id]#$post[id]><b>".
htmlspecialchars($post["subject"]) . "</b></a></td><td align=left $style><a href=?action=viewforum&forumid=$post[forumid]><b>". htmlspecialchars($forum["name"]).
"</b></a><td align=left $style>$post[views]</td><td align=left $style><b><a href=account-details.php?id=$user[id]>$user[username]</a></b><br>" . date("d-m-Y  H:i:s", utc_to_tz_time($post["added"])) . "</tr>".
"<tr><td colspan=6 $style><div id=\"ka$klap\" style=\"display: none;\">".stripslashes(format_comment($post["body"]))."</div></td></tr> \n");

}
print("</table></p>\n");
print("<p><b>Pesquisar novamente</b></p>\n");
}
}

$chtopic = ($_GET['topic']==1 ? "checked " : "");
$chbody = ($_GET['body']==1? "checked " :"");

print("<form method=get action=?>\n");
print("<input type=hidden name=action value=search>\n");
print("<table border=0 cellspacing=0 cellpadding=5>\n");



print("</table><br><br><center><table border=1 cellspacing=0 width=50% cellpadding=5><tr><td class=rowhead>Termo de pesquisa</td><td align=left><input type=text size=40 name=keywords value=\"".htmlspecialchars($keywords)."\"><br>\n" .
"<font class=small size=-1>Digite uma ou mais palavras para pesquisar.</font></td></tr>\n");

// Search in author
print("<tr><td class=rowhead>Autor do post:</td><td align=left><input type=text size=15 name=author value=\"".htmlspecialchars($author)."\"> Exibir apenas os post deste autor");

print("<tr><td colspan=2><table border=0 cellspacing=0 cellpadding=5>");

// When to search in
print("<tr><td class=rowhead style=\"border:none\">Pesquisar por data:</td><td style=\"border:none\"> <select name=\"search_time\"><option value=\"0\" $whenSel>Todos post</option><option value=\"1\" $whenSel1>1 Dia</option><option value=\"2\" $whenSel2>2 Dias</option><option value=\"3\" $whenSel3>3 Dias</option><option value=\"4\" $whenSel4>4 Dias</option><option value=\"5\" $whenSel5>5 Dias</option><option value=\"6\" $whenSel6>6 Dias</option><option value=\"7\" $whenSel7>1 Semana</option><option value=\"14\" $whenSel8>2 Semana</option><option value=\"30\" $whenSel9>1 Mês</option><option value=\"90\" $whenSel10>3 Meses</option><option value=\"180\" $whenSel11>6 Meses</option><option value=\"364\" $whenSel12>1 Ano</option></select></td></tr>");

// Where to search in
print("<tr><td class=rowhead style=\"border:none\">Buscar em:</td>".
"<td style=\"border:none\"><table border=0 cellspacing=0 cellpadding-5><tr>".
"<td style=\"border:none\"><input style=\"padding:0px;margin:0px;margin-right:3px;\" name=topic type=\"checkbox\" value=1 $chtopic> Título do Tópico</td></tr>".
"<tr><td style=\"border:none\"><input style=\"padding:0px;margin:0px;margin-right:3px;\" name=body type=\"checkbox\" value=1 $chbody> Post</td></tr>".
"</table></td></tr>");

//Sorting options
print("<tr><td class=rowhead style=\"border:none\">Classificar por:</td><td style=\"border:none\">");
print("<select name=\"sort\"><option value=\"0\" $sortSel0>Relevância</option><option value=\"1\" $sortSel1>Fórum</option><option value=\"2\" $sortSel2>Tópico</option><option value=\"3\" $sortSel3>Post recente</option><option value=\"4\" $sortSel4>Último Post Hora</option><option value=\"5\" $sortSel5>Visualizações</option></select>&nbsp;<input type=\"radio\" name=\"sort_dir\" value=\"1\" $sortDirSel1/>Ascendente&nbsp;<input type=\"radio\" name=\"sort_dir\" value=\"0\" $sortDirSel2/> Descendente</select></td></tr>");

// Number of results
print("<tr><td class=rowhead style=\"border:none\">Retornar os primeiros:</td><td style=\"border:none\">");
print("<select name=\"numres\"><option value=\"0\" $numSel1>25</option><option value=\"1\" $numSel2>50</option><option value=\"2\" $numSel3>100</option><option value=\"3\" $numSel4>200</option><option value=\"4\" $numSel5>300</option></select> resultados encontrados</td></tr>");

//Display posts summary options/topics

print ("</table></td></tr>");

print("<tr><td colspan=2 align=center><input type=submit value='Pesquisar!' class=btn>&nbsp;</td></tr>\n");
print("</table></center>\n</form>\n");
stdfoot();
die;


    } else if ($action == 'forumview') {
        $ovfid = (isset($_GET["forid"]) ? (int)$_GET["forid"] : 0);
        if (!is_valid_id($ovfid))
            stderr('Error', 'Invalid ID!');

        $res = sql_query("SELECT name FROM overforums WHERE id = $ovfid") or sqlerr(__FILE__, __LINE__);
        $arr = mysql_fetch_assoc($res) or stderr('Sorry', 'No forums with that ID!');

        sql_query("UPDATE users SET forum_access = " . sqlesc(get_date_time()) . " WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__, __LINE__);

        stdhead("Forums");
        if ($FORUMS_ONLINE == '0')
            stdmsg('Warning', ''.$language['maint'].'');
        begin_main_frame();

        ?>
	<h1 align="center"><b><a href='<?php echo $_SERVER['PHP_SELF'];
        ?>'>Fóruns</a></b> -> <?php echo htmlspecialchars($arr["name"]);
        ?></h1>

	<table border=1 cellspacing=0 cellpadding=5 width='<?php echo $forum_width;
        ?>'>
		<tr>
        	<td class=colhead align=left>Fóruns</td>
            <td class=colhead align=right>Topics</td>
		<td class=colhead align=right>Posts</td>
		<td class=colhead align=left>Last post</td>
	</tr>
	<?php

        show_forums($ovfid);

        end_table();
	stdfoot();
	die;
}



///////////////////////////////////////////////////////// Action: UNKNOWN
if ($action != "")
    showerror("Fórum Erro", "ação desconhecida '$action'.");

///////////////////////////////////////////////////////// Action: DEFAULT ACTION (VIEW FORUMS)
if (isset($_GET["catchup"]))
	catch_up();
$whereaad=array();



if ($CURUSER["ver_xxx"]!="yes") {
    $whereaad[] = "forum_forums.id != '43'";
    $whereaad[] = "forum_forums.id != '16'";

}
     $whereaad[] = "forum_forums.id != '0'";
$wheread = implode(" AND ", $whereaad);
    //


///////////////////////////////////////////////////////// Action: SHOW MAIN FORUM INDEX
$forums_res = mysql_query("SELECT forumcats.id AS fcid, forumcats.name AS fcname, forum_forums.* FROM forum_forums LEFT JOIN forumcats ON forumcats.id = forum_forums.category WHERE  ".$wheread."  ORDER BY forumcats.sort, forum_forums.sort, forum_forums.name");

stdhead("Forums");
begin_framec("Fóruns Home");
forumheader("");
latestforumposts();

print("<table cellspacing='0' cellpadding='3' class='f-border' width='100%'>");// MAIN LAYOUT

print("<tr class='f-title f-border'><th align='left' colspan='2'>Fórum</th><th width='37' align='right'>Respostas</th><th width='47' align='right'>Visitas</th><th align='right' width='180'>Último Post</th></tr>\n");// head of forum index
  
if (mysql_num_rows($forums_res) == 0)
    print("<tr class='f-cat f-border'><th colspan='5'>No Forum Categories</th></tr>\n");  
  
$fcid = 0;
 
while ($forums_arr = mysql_fetch_assoc($forums_res)){
	
    if (get_user_class() < $forums_arr["minclassread"] && $forums_arr["guest_read"] == "no")
        continue;
        
    if ($forums_arr['fcid'] != $fcid) {// add forum cat headers
		print("<tr class='f-cat f-border'><td colspan='5' align='center' class='f-border'>".htmlspecialchars($forums_arr['fcname'])."</td></tr>\n");

		$fcid = $forums_arr['fcid'];
	}

    $forumid = 0 + $forums_arr["id"];

    $forumname = htmlspecialchars($forums_arr["name"]);

    $forumdescription = htmlspecialchars($forums_arr["description"]);
    $postcount = number_format(get_row_count("forum_posts", "WHERE topicid IN (SELECT id FROM forum_topics WHERE forumid=$forumid)"));
    $topiccount = number_format(get_row_count("forum_topics", "WHERE forumid = $forumid"));


    // Find last post ID
    $lastpostid = get_forum_last_post($forumid);

    // Get last post info
    $post_res = mysql_query("SELECT added,topicid,userid FROM forum_posts WHERE id=$lastpostid");
    if (mysql_num_rows($post_res) == 1) {
		$post_arr = mysql_fetch_assoc($post_res) or showerror("Error", "Bad forum last_post");
		$lastposterid = $post_arr["userid"];
		$lastpostdate = utc_to_tz($post_arr["added"]);
		$lasttopicid = $post_arr["topicid"];
		$user_res = mysql_query("SELECT username FROM users WHERE id=$lastposterid");
		$user_arr = mysql_fetch_assoc($user_res);
		$lastposter = htmlspecialchars($user_arr['username']);
		$topic_res = mysql_query("SELECT subject FROM forum_topics WHERE id=$lasttopicid");
		$topic_arr = mysql_fetch_assoc($topic_res);
		$lasttopic = stripslashes(htmlspecialchars($topic_arr['subject']));
		
		//cut last topic
		$latestleng = 15;

		$lastpost = "<span class='small'><a href='forums.php?action=viewtopic&amp;topicid=$lasttopicid&amp;page=last#last'>" . CutName($lasttopic, $latestleng) . "</a> por <a href='account-details.php?id=$lastposterid'>$lastposter</a><br />$lastpostdate</span>";


		if ($CURUSER) {
			$r = mysql_query("SELECT lastpostread FROM forum_readposts WHERE userid=$CURUSER[id] AND topicid=$lasttopicid");
			$a = mysql_fetch_row($r);
		}

		//define the images for new posts or not on index
		if ($a && $a[0] == $lastpostid)
			$img = "folder";
		else
		$img = "folder_new";
    }else{
		$lastpost = "<span class='small'>não </span>";
		$img = "folder";
    }
	//following line is each forums display
    print("<tr class='f-row f-border'><td class='f-img'><img src='". $themedir ."$img.png' alt='' /></td><td align='left' width='100%' class='alt3'><a href='forums.php?action=viewforum&amp;forumid=$forumid'><b>$forumname</b></a><br />\n" .
    "<span class='small'>- $forumdescription</span></td><td class='alt2 f-border' align='center' width='40'>$topiccount</td><td class='alt2 f-border' align='center' width='40'>$postcount</td>" .
    "<td class='alt3 f-border' align='right' width='110'><span style='white-space: nowrap'>$lastpost</span></td></tr>\n");
}
print("</table>");
//forum Key
print("<table cellspacing='0' cellpadding='3'><tr valign='middle'>\n");
print("<td><img src='". $themedir ."folder_new.png' style='margin: 5px' alt='' /></td><td>Novas mensagens</td>\n");
print("<td><img src='". $themedir ."folder.png' style='margin: 5px' alt='' /></td><td>Sem novas mensagens</td>\n");
print("<td><img src='". $themedir ."folder_locked.png' style='margin: 5px' alt='' /></td><td>Tópico Trancado</td>\n");
print("<td><img src='". $themedir ."folder_sticky.png' style='margin: 5px' alt='' /></td><td>Tópico Fixo</td>\n");
print("</tr></table>\n");

//Top posters
$r = mysql_query("SELECT users.id, users.username, COUNT(forum_posts.userid) as num FROM forum_posts LEFT JOIN users ON users.id = forum_posts.userid GROUP BY userid ORDER BY num DESC LIMIT 10");
forumpostertable($r);

//topic count and post counts
$postcount = number_format(get_row_count("forum_posts"));
$topiccount = number_format(get_row_count("forum_topics"));
print("<br /><center><i><b>Nossos membros fizeram " . $postcount . " postagens em  " . $topiccount . " tópicos</i></b></center><br />");

insert_quick_jump_menu();
end_framec();
stdfoot();

}else{//HEY IF FORUMS ARE OFF, SHOW THIS...
    showerror("Nota", "Infelizmente, o fórum não está disponível.");
}

?>
