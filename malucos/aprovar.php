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
	if( $CURUSER["level"]=="Uploader" ){

if ($_GET['do'] == "ap") {
			if (!@count($_POST["del"])) 
			show_error_msg("Error", "Nada selecionado", 1);	
			$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);
	
			mysql_query("UPDATE torrents SET safe='yes', markedby='$CURUSER[username]' WHERE id IN ($ids)") or die(mysql_error());
mysql_query("UPDATE torrents SET safe='yes', markdate='".get_date_time()."' WHERE id IN ($ids)") or die(mysql_error());
$res1 = mysql_query("SELECT torrents.id, torrents.size, torrents.safe, torrents.name, torrents.owner FROM torrents WHERE id IN ($ids)");



$res1236 = mysql_query("SELECT torrents.id, torrents.size, torrents.safe, torrents.name, torrents.owner FROM torrents WHERE id IN ($ids)");
		$arr1236 = mysql_fetch_assoc($res1236);

	

	
	


                while ($arr1 = mysql_fetch_assoc($res1))
                {       
				$owner = $arr1["owner"];
$torrentname = $arr1["name"];
$torrentid = $arr1["id"];

			write_loguser("Auto-liberado","#FF0000","O torrent [url=http://www.malucos-share.org/torrents-details.php?id=".$torrentid."]".$torrentname."[/url] foi liberado por [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url]\n");

 				$res_qualidade = mysql_query("SELECT filmeresolucao, filmeresolucalt, category FROM torrents WHERE id IN ($ids)");
                $row_qualidade = mysql_fetch_array($res_qualidade);
					    if ( $row_qualidade["category"] == 95 ){
               if ($row_qualidade["filmeresolucao"] > 1200 ||  $row_qualidade["filmeresolucalt"] > 720 )
 {
			   
		mysql_query("UPDATE torrents SET freeleech='1'  WHERE id IN ($ids)") or die(mysql_error());
        }	
		}
						if($arr1["size"] >= 4294967296){
					SQL_Query_exec("UPDATE torrents SET freeleech='1' WHERE id IN ($ids)");
				     
						}	

					$pontos = (int) 0;
						if($arr1["size"]< 10485760) $pontos = "1";
						if($arr1["size"] >= 10485760 && $tor["size"] < 53477375) $pontos = "1";
						if($arr1["size"] >= 53477376 && $tor["size"] < 157286399) $pontos = "3";
						if($arr1["size"] >= 157286400 && $tor["size"] < 524288000) $pontos = "6";
						if($arr1["size"] >= 524288001 && $tor["size"] < 734003200) $pontos = "8";
						if($arr1["size"] >= 734003201 && $tor["size"] < 1610612735) $pontos = '12';
						if($arr1["size"] >= 1610612736 && $tor["size"] < 4294967295) $pontos = "15";
						if($arr1["size"] >= 4294967296 && $tor["size"] < 6442450943) $pontos = "20";
						if($arr1["size"] >= 6442450944 && $tor["size"] < 16106127359) $pontos = "30";
						if($arr1["size"] > 16106127360)  $pontos = "40";
				
						 	SQL_Query_exec("UPDATE users SET seedbonus=seedbonus+{$pontos} WHERE id=$owner ");
							 $torupado="INSERT INTO `torrentlancado` (`uid`, `app`, `aprovado`, `added`, `infohash`) VALUES ('".$CURUSER['id']."', '$torrentname', '1','".get_date_time()."','$torrentid')";
                         @mysql_query($torupado);

                }
                

					
		header("Refresh: 2;url=aprovar.php");
		stdhead();
		show_error_msg(T_("SUCCESS"), 'Todos os torrents foram aprovados', 0);
		stdfoot();
		die;
	}

stdhead(T_("LOG_USER_LOG"));
$wherea=array();

$wherea[] = "seeders > '0'";
	$uploader = "AND owner = '".$CURUSER["id"]."'";

$where = implode(" AND ", $wherea);

	$res2 = mysql_query("SELECT COUNT(*) FROM torrents WHERE safe='no' ".$uploader."  AND ". $where ."  ORDER BY added ASC");
        $row = mysql_fetch_array($res2);
        $count = $row[0];
$perpage = 19;
    list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" );
	
	
	$res = mysql_query("SELECT * FROM torrents WHERE safe='no' ".$uploader."  AND ". $where ."  ORDER BY added ASC $limit") or sqlerr();




  if(mysql_num_rows($res)==0){


show_error_msg("Erro", "Desculpe mais não temos torrent na fila");

  }
 

begin_framec("Liberação");
	

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
				<td class=tab1_cab1 align=left width="5%"><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>
				<td class=tab1_cab1 align=center width="20%">Adicionado</td>
				<td class=tab1_cab1 align=center>Nome do Torrent</td>

			</tr>
	<?php
	

	$rqq = "SELECT * FROM torrents WHERE safe='no' ".$uploader."   ORDER BY added ASC $limit";
	$res = mysql_query($rqq);



			   
	echo "<form action='aprovar.php?action=aprovar&do=ap' method='POST'>";
	 while ($arr = mysql_fetch_array($res)){
	 	
		
	$tm_sql="SELECT * from moderation WHERE infohash=".$arr['id']."";
$tm_r=mysql_query($tm_sql);	
		

//echo $sql;


if(mysql_num_rows($tm_r)==0){
$username1 = 'O torrent ainda não foi moderado';

} else {
while ($tm_a = mysql_fetch_array($tm_r)){

  
//    $u=mysql_query("[url=".$site_config['SITEURL']."/account-details.php?id=".$CURUSER['id']."]" .$CURUSER['username']. "[/url]')") or die(mysql_error());

$res1234 = mysql_query("SELECT username FROM users WHERE id = " . $tm_a['uid'] . "") or die (mysql_error());
$arr1234 = mysql_fetch_array($res1234);
$username1 = htmlspecialchars($arr1234['username'], ENT_QUOTES);


}
//    echo $pmd;
}


	
   $res321 = mysql_query("SELECT users.id, users.username, torrents.name, torrents.owner FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id =". $arr["id"] ." ");
$row123 = mysql_fetch_array($res321);
$torrent_name = htmlspecialchars($arr["name"]);
		print("
			<tr>
				<td class=table_col2><input type='checkbox' name='del[]' value='$arr[id]'></td>
				<td class=table_col2>" . date("d-m-Y H:i:s", utc_to_tz_time($arr["added"])) . "</td>
				<td class=table_col1><center><a href=torrents-details.php?id=". $arr["id"] ."><u><b>$torrent_name</b></u></a></center></td>
                
			</tr>\n");
	 }
	echo "</table></center>\n";



	echo "<input type='submit' value='Aprovar seleccionado'></form>";

	print($pagerbottom);
	
	end_framec();
	
}else{

show_error_msg("Erro", "Acesso Negado");
end_framec();
}


	
	stdfoot();
?>