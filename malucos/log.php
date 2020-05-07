<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################

	
	require_once("backend/functions.php");
	dbconn();
	
	loggedinonly();
	



if ($_GET['do'] == "del") {
		if ($_POST["delall"])
			SQL_Query_exec("DELETE FROM `loguser`");
		else {
			if (!@count($_POST["del"])) 
				show_error_msg(T_("ERROR"), T_("LOG_USER_ERRO"), 1);		
			$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);
			SQL_Query_exec("DELETE FROM `loguser` WHERE `id` IN ($ids)");
		}
		header("Refresh: 2;url=log.php?action=sitelog");
		stdhead();
		show_error_msg(T_("SUCCESS"), T_("LOG_USER_DELETADO"), 0);
		stdfoot();
		die;
	}
stdhead(T_("LOG_USER_LOG"));


	$param ="";
	$search = trim($_GET["search"]);
	$type = $_GET["type"];
    $wherea = array();
	
    	if ($search != '' ){
			$wherea[] = " txt LIKE " . sqlesc("%$search%") . "";
			$param .= "search=$search&amp;";
							}
	
   		if($type != '') {
			$wherea[] = " type ='$type'";
			$param .= "type=$type&amp;";
						}
								
												
    $where = implode(" AND ", $wherea);
	
	if ($where != "")
	$where = "WHERE $where";
	
	$res2 = SQL_Query_exec("SELECT COUNT(*) FROM loguser $where");
	$row = mysql_fetch_array($res2);
	$count = $row[0];

	$perpage = 50;

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "log.php?action=sitelog&".$param);


begin_framec("Log");
	print("<center><form method=get action=?>\n");
	print("<input type=hidden name=action value=sitelog>\n");
	print(" <input type=text size=30 name=search value=\"".stripslashes(htmlspecialchars($search))."\">\n");
	$res3 = SQL_Query_exec("SELECT DISTINCT type,couleur FROM loguser WHERE type !='' ORDER by type");
	print("<select name=type>");
	print("<option value=>" .T_("LOG_USER_TODOS"). "</option>");
	while ($arr = mysql_fetch_array($res3))
	{
    print("<option  value=".htmlspecialchars($arr[type]).">".htmlspecialchars($arr[type])."</option>");
	}
	print("<input type=submit value='" .T_("LOG_USER_PESQUISA"). "'>\n");
	print("</form></center>\n");
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
		<table class='tab1' cellpadding='0' cellspacing='1' align='center' width="100%" border="0" >
			<tr>
				<td class="tab1_cab1" width="1%" align=left><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>
				<td class="tab1_cab1" width="10%" align=center>Data / Hora</td>
				<td class="tab1_cab1" width="84%" align=center><?php echo T_("LOG_USER_EVENTO"); ?></td>
			</tr>
	<?php
	
	
	$rqq = "SELECT * FROM loguser $where ORDER BY id DESC $limit";
	$res = SQL_Query_exec($rqq);

	echo "<form action='log.php?action=sitelog&do=del' method='POST'>";
	 while ($arr = MYSQL_FETCH_ARRAY($res)){
		$arr['added'] = date("d/m \à\s H:i",utc_to_tz_time(($arr['added'])));
		$date = substr($arr['added'], 0, strpos($arr['added'], " "));
		$time = substr($arr['added'], strpos($arr['added'], " ") + 1);
		print("
			<tr>
			<td class=tab1_col3 ><input type='checkbox' name='del[]' value='$arr[id]'></td>
				<td class=tab1_col3 ><center>".$date." ".$time."</center></td>
				<td class=tab1_col3 >".format_comment($arr['txt'])."</td>
			</tr>\n");
	 }
	echo "</table></center>\n";
if ($CURUSER["id"] =="1"){ 



	echo "<input type='submit' value='Apagar seleccionado'> <input type='submit' value='Apagar todos' name='delall'></form>";
}
	print($pagerbottom);



	end_framec();
	stdfoot();
?>