<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn();
loggedinonly();
require ("backend/conexao.php");
$pdo = conectar();

stdhead("Torrents aguardando Aprovação");
$conttorrent = "SELECT count(*) FROM  torrents  WHERE  safe = 'no'  AND seeders > '0'"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->execute(); 
$rowconttor = $conttorr->fetchColumn() ; 

if($rowconttor ==0){
show_error_msg("Ops", "Todos os torrents foram aceitos.");
}

begin_framec("Torrents aguardando Aprovação");


echo"<center><b>Apenas torrents com seeder aparecem na lista.</b></center><br>";
$stmt = $pdo->prepare("SELECT * FROM users  LEFT JOIN torrents ON users.id = torrents.owner WHERE users.class != '50' AND  torrents.safe = 'no'  AND torrents.seeders > '0' ORDER BY torrents.added ASC"); 
$stmt->execute(); 

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
$rescat = $pdo->prepare("SELECT * FROM categories WHERE id=".$row['category'].""); 
$rescat->execute(); 
$rowcat = $rescat->fetch(PDO::FETCH_ASSOC);  

$resmod = $pdo->prepare("SELECT * FROM moderation WHERE infohash=".$row['id'].""); 
$resmod->execute(); 
$rowmod = $resmod->fetch(PDO::FETCH_ASSOC);  


$sqlcont = "SELECT count(*) FROM  moderation WHERE infohash=".$row['id'].""; 
$modcont = $pdo->prepare($sqlcont); 
$modcont->execute(); 
$rowcont = $modcont->fetchColumn() ; 





if($rowcont ==0){
$moderarmod = "e ainda não está sendo moderado.";
}else
{
$moderarmod = "já está sendo moderado e ainda não foi liberado.";
}
if ($rowmod["pendete"] == 'yes'){
$moderarmod = "já está sendo moderado e não foi liberado ainda pelo seguinte motivo: <B>".$rowmod["com"]."</B>";
}

echo "<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";
 echo("<tr><td width=100% align=center colspan=2 class=tab1_cab1><b><font color=white a href='torrents-details.php?id=" . $row["id"] . "'>" . $row["name"] . "</font></a></b> (" . $rowcat["parent_cat"] . " >" . $rowcat["name"] . ")</tr>");
  print("<tr><td align=center class=tab1_col3 colspan=2>O torrent foi lançado em <B>".date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "</B>, ".$moderarmod."</td></tr>\n");
 
echo "</table><BR>";

}
end_framec();




stdfoot();

?>
