<?php

############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

	begin_block("Rank");
	
		


$count = get_row_count("users", "WHERE  status='confirmed'  ORDER BY uploader_rank DESC, added ASC LIMIT 20; ");

	$sql = "SELECT id,username,uploader_rank FROM users WHERE status='confirmed'  ORDER BY uploader_rank DESC, added ASC LIMIT 20;";
		$res2 = SQL_Query_exec($sql);
		

	
		  $count = 0;
 	 $rank = $count;
		
	echo"<TABLE class='tab1'  cellpadding='0' cellspacing='1' align='center'>";
print("<tr><td class=ttable_headp  width=1 align=left>Posição</td><td class=ttable_headp align=center>Usuário</td></tr>");

	while($row = mysql_fetch_array($res2)){


++$num;


 
 $upload = $upload +  $row['uploader_rank'];

 $perc1 = $row['uploader_rank'];
 
 if ( $perc1 == 0) 
{
$perc = 0 ;
}else{
$perc= $perc1*100/$upload;
}
if ($perc<= 1) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 20) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 30) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 40) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 50) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 60) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
elseif ($perc<= 70) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
elseif ($perc<= 80) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
elseif ($perc<= 90) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
else { 
$pic = "images/loadbargreen.gif "; $width = "100"; }

 echo"<tr><td  align=center  class=tab1_col3 ><b>".$num."</b></td><td   align=center class=tab1_col3  ><a href='account-details.php?id=".$row['id']."'>".$row['username']." </a></td></tr>";
}
 echo"</TABLE>";	




		print("<a href=/rank_promo.php><font color=#FF0000><CENTER><b>Explicação do Rank</b></CENTER></font></a>");
	end_block();


?>
