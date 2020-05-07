<?php
############################################################
#######                                             ########
#######                                             ########
#######           Malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(true);

loggedinonly();  
stdhead(T_("HOME"));


if ($_GET['do'] == "ap") {
			if (!@count($_POST["del"])) 
			show_error_msg("Error", "Nada selecionado", 1);	
			$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);
	

          SQL_Query_exec("DELETE FROM forum_favoritos WHERE uid = $CURUSER[id] AND  pid IN ($ids)");
		stdhead();
		show_error_msg(T_("SUCCESS"), 'Tópico(s) deletado(s) da lista com sucesso!', 0);
		  ?>

  
  <p align="center"><a href="forum_fav.php"><?php echo 'Voltar' ?></a></p>
  
  <?php
		stdfoot();
		die;
	}
		$res2 = mysql_query("SELECT COUNT(*) FROM forum_favoritos WHERE uid = $CURUSER[id]  ");
        $row1 = mysql_fetch_array($res2);
        $count = $row1[0];
$perpage = 5;
    list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" );


		$res_favo1 = mysql_query("SELECT * FROM forum_favoritos WHERE uid = $CURUSER[id] $limit ");

		
if(mysql_num_rows($res_favo1)==0){
  show_error_msg("Error", "Você ainda não possui nenhum tópico na lista de favoritos.", 1);

}	
else{
// Latest Forum Topics
begin_framec("Tópicos Favoritos");
	
	echo $pagertop;
?>
	
	<script language="JavaScript" type="text/Javascript">
		function checkAll(box) {
			var x = document.getElementsByTagName('input');
			for(var i=0;i<x.length;i++) {
				if(x[i].type=='checkbox') {
					if (box.checked)
						x[i].checked = false;
					else
						x[i].checked = true;
				}
			}
			if (box.checked)
				box.checked = false;
			else
				box.checked = true;
		}
	</script>

	<center>
		<table align=center cellpadding="0" cellspacing="0" class="table_table" width="100%" border="1">
			<tr>
				
				<td class=tab1_cab1 align=center width="60%">Tópico</td>
				<td class=tab1_cab1 align=center>Visitas</td>
				<td class=tab1_cab1 align=center>Autor</td>
				<td class=tab1_cab1 align=center >Ultimo Post</td>
				<td class=tab1_cab1 align=left width="1%"><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>
			</tr>
	<?php





  

		$res_favo = mysql_query("SELECT * FROM forum_favoritos WHERE uid = $CURUSER[id] $limit ");

		



		
while ($resp_favor = mysql_fetch_array($res_favo)) {


$for = mysql_query("SELECT * FROM forum_topics WHERE id =  $resp_favor[pid]  ");


while ($topicarr = mysql_fetch_assoc($for)) {
// Set minclass




$topicid = $topicarr["id"];
$topic_title = stripslashes($topicarr["subject"]);
$topic_userid = $topicarr["userid"];
// Topic Views
$views = $topicarr["views"];
// End

/// GETTING TOTAL NUMBER OF POSTS ///
$res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE topicid=$topicid") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_row($res);

$postsperpage = 20;
/// GETTING TOTAL NUMBER OF POSTS ///
$res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE topicid=$topicid") or sqlerr(__FILE__, __LINE__);
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

/// GETTING USERID AND DATE OF LAST POST ///  
$res = mysql_query("SELECT * FROM forum_posts WHERE topicid=$topicid ORDER BY id DESC ") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_assoc($res);
$postid = 0 + $arr["id"];
$userid = 0 + $arr["userid"];
$added = "<nobr>" . date("d-m-Y H:i:s", utc_to_tz_time($arr["added"])) . "</nobr>";

/// GET NAME OF LAST POSTER ///
$res = mysql_query("SELECT id, username FROM users WHERE id=$userid") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) == 1) {
$arr = mysql_fetch_assoc($res);
$username = "<a href=account-details.php?id=$userid>$arr[username]</a>";
}
else
$username = "[Desconhecido]";

/// GET NAME OF THE AUTHOR ///
$res = mysql_query("SELECT username FROM users WHERE id=$topic_userid") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) == 1) {
$arr = mysql_fetch_assoc($res);
$author = "<a href=account-details.php?id=$topic_userid>$arr[username]</a>";
}
else
$author = "[Desconhecido]";

/// GETTING THE LAST INFO AND MAKE THE TABLE ROWS ///
$r = mysql_query("SELECT lastpostread FROM forum_readposts WHERE userid=$userid AND topicid=$topicid") or sqlerr(__FILE__, __LINE__);
$a = mysql_fetch_row($r);
$subject = "<a href=forums.php?action=viewtopic&topicid=$topicid><b>" . stripslashes(encodehtml($topicarr["subject"])) . "</b></a>$topicpages";
	echo "<form action='forum_fav.php?action=apagar&do=ap' method='POST'>";
print("<tr  ><td class=table_col1>$subject</td>" .
"<td class=table_col1 align=center ><font class=tipo_forum1>$views</font></td>" .
"<td class=ttable_col5 align=center >$author</td>" .
"<td class=ttable_col5 align=right ><font class=tipo_forum1>por</font>&nbsp;$username<br><font class=tipo_forum1>$added</font></td>	<td class=table_col2 width='1%'><input type='checkbox' name='del[]' value='$topicarr[id]'></td>");

print("</tr>");

}

}

print("</table><br>");
print($pagerbottom);

	echo "<input type='submit' value='Deletar selecionados'></form>";
}

	end_framec();



stdfoot();
?>