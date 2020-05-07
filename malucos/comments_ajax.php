<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
//
require("backend/functions.php");
require_once("backend/BDecode.php");
dbconn(false);
if ($site_config["MEMBERSONLY"] && !$CURUSER) die;

require_once("backend/bbcode.php");

$id = (int) $_GET["id"];
if (!$id) die;

$res = mysql_query("SELECT id FROM torrents WHERE id=$id");
if (mysql_num_rows($res) != 1) die;

$do = $_REQUEST['do'];
$cid = (int)$_GET["cid"];
if ($CURUSER["delete_torrents"] == "yes" || $CURUSER["delete_forum"] == "yes") {
    if ($do == "del") {
        if ($cid) {
            mysql_query("DELETE FROM comments WHERE id=$cid AND torrent=$id ");
            if (mysql_affected_rows() == 1)
                mysql_query("UPDATE torrents SET comments=comments-1 WHERE id=$id");
        }
        die;
    }
}

if ($CURUSER) {
    if ($do == "takecomment") {
        $body = $_POST['body'];
        if (!$body) die;
            mysql_query("INSERT INTO comments (user, torrent, added, text) VALUES (".$CURUSER["id"].", ".$id.", '" .get_date_time(). "', " . sqlesc($body).")");
			//PM NOTIF


$res = SQL_Query_exec("SELECT name, owner FROM torrents WHERE id = $id") or die (mysql_error());
$arr = mysql_fetch_array($res);

					$res12 = SQL_Query_exec("SELECT id, username, ver_com FROM users WHERE id=".$arr["owner"]."");
					$arr12 = MYSQL_FETCH_ARRAY($res12);
if ($arr12["ver_com"] == "yes" ){
 
                                if($CURUSER['id'] != $arr["owner"]) {  
$msg = "você recebeu um comentario do seu torrent:) [url=". $site_config[SITEURL]
."/torrents-details.php?id=$id]" . $arr[name] . "[/url]";
SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES('0','0', " . $arr['owner'] . ", " .
sqlesc($msg) . ", '" . get_date_time() . "','Novo comentário!')") or die (mysql_error());
          }           }  
//PM NOTIF
            if (mysql_affected_rows() == 1)
                mysql_query("UPDATE torrents SET comments = comments + 1 WHERE id = $id");
            echo "<script type='text/javascript'>parent.loadComments(-1)</script>";
            header("Refresh: 1;url=comments_ajax.php?id=$id&do=postcomment");
        die;
    }
    if ($do == "postcomment") {
		$dossier = $CURUSER['bbcode'];
        echo "<center><form id=\"postcomment\" name=\"comment\" method=\"post\" action=\"comments_ajax.php?id=$id&do=takecomment\">";
				echo "".textbbcode("comment","body","$dossier")."<br>";
        echo "<input type=\"submit\" class=btn value=\"Enviar comentário\"/>";
        echo "</form></center>";
        die;
    }
    if ($do == "edit") {
        if (!$cid) die;
        $row = mysql_fetch_assoc(mysql_query("SELECT user FROM comments WHERE id=$cid"));

        if (($CURUSER["edit_torrents"] == "no" || $CURUSER["edit_forum"] == "no") && $CURUSER['id'] != $row['user']) {
            header("Location: comments_ajax.php?do=postcomment");
            die;
        }


        $res = mysql_query("SELECT * FROM comments WHERE id=$cid ORDER BY added desc");
        $arr = mysql_fetch_array($res);

        print("<center><b>Editar comentário </b><p>\n");
        print("<form method=\"post\" name=\"comment\" action=\"comments_ajax.php?id=$id&cid=$cid&do=takeedit\">\n");
		$dossier = $CURUSER['bbcode'];
       print(textbbcode("comment", "text", $CURUSER["bbcode"], htmlspecialchars($arr["text"])));
        print("<p><input type=\"submit\" class=btn value=\"Submit Changes\" /></p></form></center>\n");
        
        die;
    }
    if ($do == "takeedit") {
        $text = sqlesc($_POST['text']);
        mysql_query("UPDATE comments SET text=$text WHERE id=$cid");
        write_log($CURUSER['username']." has edited comment: ID:$id");
        echo "Comment Edited.<script>parent.loadComments(-1)</script>";
        header("Refresh: 3;url=comments_ajax.php?id=$id&do=postcomment");
        die;
    }
}

if ($_GET["page"] == -1)
    unset($_GET["page"]);

$commcount = get_row_count("comments", "WHERE torrent = $id");

function pager2($rpp, $count, $opts = array()) {
    $pages = ceil($count / $rpp);

    if (!$opts["lastpagedefault"])
        $pagedefault = 0;
    else {
        $pagedefault = floor(($count - 1) / $rpp);
        if ($pagedefault < 0)
            $pagedefault = 0;
    }

    if (isset($_GET["page"])) {
        $page = (int)$_GET["page"];
        if ($page < 0)
            $page = $pagedefault;
    }
    else
        $page = $pagedefault;

    $pager = "";

    $mp = $pages - 1;
    $as = "<b>&lt;&lt;&nbsp;Página Anterior</b>";
    if ($page >= 1) {
        $pager .= "<a href=\"#comments\" onclick=\"loadComments(" . ($page - 1) . ")\">";
        $pager .= $as;
        $pager .= "</a>";
    }
    else
        $pager .= $as;
    $pager .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $as = "<b>Próxima Página&nbsp;&gt;&gt;</b>";
    if ($page < $mp && $mp >= 0) {
        $pager .= "<a href=\"#comments\" onclick=\"loadComments(" . ($page + 1) . ")\">";
        $pager .= $as;
        $pager .= "</a>";
    }
    else
        $pager .= $as;

    if ($count) {
        $pagerarr = array();
        $dotted = 0;
        $dotspace = 3;
        $dotend = $pages - $dotspace;
        $curdotend = $page - $dotspace;
        $curdotstart = $page + $dotspace;
        for ($i = 0; $i < $pages; $i++) {
            if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
                if (!$dotted)
                    $pagerarr[] = "...";
                $dotted = 1;
                continue;
            }
            $dotted = 0;
            $start = $i * $rpp + 1;
            $end = $start + $rpp - 1;
            if ($end > $count)
                $end = $count;
            $text = "$start&nbsp;-&nbsp;$end";
            if ($i != $page)
                $pagerarr[] = "<a href=\"#comments\" onclick=\"loadComments($i)\" style=\"text-decoration: underline\"><b>$text</b></a>";
            else
                $pagerarr[] = "<b>$text</b>";
        }
        $pagerstr = join(" | ", $pagerarr);
        $pagertop = "<p align=\"center\">$pager<br>$pagerstr</p>\n";
        $pagerbottom = "<p align=\"center\">$pagerstr<br>$pager</p>\n";
    }
    else {
        $pagertop = "<p align=\"center\">$pager</p>\n";
        $pagerbottom = $pagertop;
    }

    $start = $page * $rpp;

    return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
}

function commenttable2 ($res, $type = null) {

  global $site_config, $CURUSER, $THEME, $LANGUAGE;  //Define globals

	while ($row = mysql_fetch_assoc($res)) {
	$res123 = SQL_Query_exec("SELECT * FROM users WHERE id=".$row['user'].""); 
		$arr123 = mysql_fetch_assoc($res123);
		$postername123 = $arr123["username"];
$datetime1 = get_date_time(gmtime() - 180);
					  

              
						if($arr123["last_access"] > $datetime1){
						           $online = "<font class='online'>Online</font>";
					}else{
				        		  $online = "Offline";
					}
                
//$numtorrents
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE owner=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numtorrents = $arr1[0];

//$numcomments
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM comments WHERE user=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numcomments = $arr1[0];
			$placa = $row;

if ($row["class"] == 100){
$placa="&nbsp;<img src=images/sysop.png alt=Sysops	 title=Sysops  border=0>";}

elseif ($row["class"] == 95){
$placa="&nbsp;<img src=images/adm.png alt=Administrador	 title=Administrador border=0>";}

elseif ($row["class"] == 86){
$placa="&nbsp;<img src=images/S.Moderador.png alt=Moderador title=Moderador border=0>";}

elseif ($row["class"] == 85){
$placa="&nbsp;<img src=images/MODERADOR.png alt=Moderador title=Moderador border=0>";}

elseif ($row["class"] == 75){
$placa="&nbsp;<img src=images/LIBERADOR-DE-TORRENTS.png alt=Liberador de Torrents title=Liberador de Torrents border=0>";}

elseif ($row["class"] == 80){
$placa="&nbsp;<img src=images/COLABORADOR.png alt=Colaborador title=Colaborador border=0>";}

elseif ($row["class"] == 70){
$placa="&nbsp;<img src=images/DESIGNER.png alt=Designer title=Designer border=0>";}

elseif ($row["class"] == 71){
$placa="&nbsp;<img src=images/Coord.Designer.png alt=Coord de designer title=Coord de designer border=0>";}

elseif ($row["class"] == 69){ 
$placa="&nbsp;<img src=images/DJs.png alt=DJ's de Torrents title=DJ's border=0>";}

elseif ($row["class"] == 50){
$placa="&nbsp;<img src=images/UPLOADER.png alt=Uploader title=Uploader border=0>";}
elseif ($row["class"] == 1){
$placa="&nbsp;<b>Usuário</b>";
}

else{
$placa="";

} 
		$postername = "<a href=account-details.php?id=".$row['user'].">$row[username]</a>";
		if ($postername == "") {
			$postername = T_("DELUSER");
			$title = T_("DELETED_ACCOUNT");
			
			$avatar = "";
			$usersignature = stripslashes(format_comment($row["signature"]));
			$userdownloaded = "";
			$useruploaded = "";
		}else {
			$privacylevel = $row["privacy"];
			$avatar = htmlspecialchars($row["avatar"]);
			$title =  format_comment($row["title"]);
			$usersignature = stripslashes(format_comment($row["signature"]));
			$userdownloaded = mksize($row["downloaded"]);
			$useruploaded = mksize($row["uploaded"]);
		}
		$postername = $row["username"];
			if ($postername == "") {	
			$postername = 'Desativada';
			}else{
				$postername = "<a href=account-details.php?id=".$row['user'].">$row[username]</a>";
$res123568 = SQL_Query_exec("SELECT * FROM users WHERE id=".$row['user'].""); 
		$arr123853 = mysql_fetch_assoc($res123568);
$resgrupos = mysql_query("SELECT name,id,image FROM teams WHERE id = ".$arr123853["team"]." LIMIT 1");
	    $arrgrupos = mysql_fetch_assoc($resgrupos);
	    $res1grupos = mysql_query("SELECT status FROM usergroups WHERE gid=".$arr123853["team"]." AND uid = ".$arr123853['id']."  ");
	    $arr1grupos = mysql_fetch_assoc($res1grupos);
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
	}


		if ($row["downloaded"] > 0)
			$userratio = number_format($row["uploaded"] / $row["downloaded"], 2);
		else
			$userratio = "---";

		if (!$avatar)
			$avatar = $site_config["SITEURL"]."/images/default_avatar.gif";

		$commenttext = format_comment($row["text"]);
    $text = format_comment($row["text"]);

        print("<table border=0 width=100% cellpadding=3 cellspacing=0>\n");



        if( $CURUSER["edit_forum"]=="yes" || $CURUSER['id'] == $row['user']){
			 $edit = "<a href='#commentframe' onclick='document.getElementById(\"commentframe\").src=\"comments_ajax.php?id=$row[torrent]&cid=$row[id]&do=edit\"'><img src='themes/$THEME/forums/p_edit.png'  alt='Editar'></a>&nbsp;";
        }
        if($CURUSER["delete_forum"]=="yes"){
			  $delete = "<a href='#commentsdel' onclick='deleteComment($row[id])'><img src='themes/$THEME/forums/p_delete.png'  alt='Deletar'></a>&nbsp;";
        }

        print('<tr class="comenttorre">');
        print('<td align="center" width="17%" class="comenttorre">'.$postername.'</td>');
        print('<td align="right" width="83%" class="comenttorre" > Postado em: '.date("d/m/Y \\à\\s H:i:s", utc_to_tz_time($row["added"])).'<a id="comment'.$row["id"].'"></a></td>');
        print('</tr>');
        print('<tr valign="top">');
        
   print('<td class="f-border comment-details" align="left" width="130"><center><img src="'.$avatar.'" alt="" /><br /><font class=tipo_forum>'.$grupos_exe.'</font><center><br><font class=tipo_forum><b>'.$placa.'</B></font><center><font class=tipo_forum></font></center><br><font class=tipo_forum>Ratio: </font> <B><font class=tipo_forum> '.$userratio.' </font></B><center></font><font color=#151515>[</font> <font color=#00CC00><B> '.$useruploaded.' </B></font><font color=#151515>]</font><font color=#151515>|</font><font color=#151515>[</font><font color=#FF0000><B> '.$userdownloaded.' </B></font><font color=#151515><font color=#151515>]</font></center><center><font class=tipo_forum></font></center></center><center><br><font class=tipo_forum>Lançamentos:  </font><B><font class=tipo_forum> ' . number_format($numtorrents) . ' </font></B></center><center><font class=tipo_forum>Comentários:  <B> ' . number_format($numcomments) . ' </font></B><br></center><br><center><B> '.$online.' </B><br></center></td>');
        
        print('<td class="f-border comment"><font class=tipo_forum>'.$commenttext.' </font><br><br><font class=tipo_forum>---------------</font><br><font class=tipo_forum >'.$usersignature.'</font></td>');
		
        print('</tr>');
          print('<tr class="comenttorre">');
        print('<td align="center" width="17%" class="comenttorre"><a href="account-details.php?id='.$row["user"].'"><img src="themes/'.$THEME.'/forums/icon_profile.gif" border="" alt="" /></a> <a href="mailbox.php?Escrever&amp;id='.$row["user"].'"><img src="themes/'.$THEME.'/forums/icon_pm.gif" border="0" alt="" /></a></center></td>');
        print("<td align='right' width='83%' class='comenttorre' > $edit  $delete <a href='report.php?comment='.$row[id].''><img src='themes/$THEME/forums/p_report.gif'  alt='Reporta'></a>&nbsp;<a href='javascript:scroll(0,0);'><img src='themes/$THEME/forums/p_up.gif'  alt='Go to the top of the page'></a></td>");
        print('</tr>');
        print('<tr valign="top">');
        print('</table><br />');  
		
		
		
		
		
		
		
		
		
		
		
    }
}


if ($commcount) {
    list($pagertop, $pagerbottom, $limit) = pager2(10, $commcount);
    $commquery = "SELECT comments.id, torrent, text, user, comments.added, avatar, signature, username, title, class, uploaded, downloaded, privacy, donated FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = $id ORDER BY comments.added desc $limit";
    $commres = mysql_query($commquery) or die(mysql_error());
} else
    unset($commres);

if ($commcount) {
    print($pagertop);
    commenttable2($commres);
    print($pagerbottom);
} else
    print("<BR><b><CENTER>Este torrent não possui nenhum comentário.</CENTER></b><BR>\n");

?>