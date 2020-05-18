<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
require ("backend/conexao.php");
dbconn();
loggedinonly();
$pdo = conectar();

stdhead("Pedidos pesquisa");


?>
<script>
function toggle(nome, pnome) {
if(document.getElementById(nome).style.display=='none'){
document.getElementById(nome).style.display = '';
document.getElementById(pnome).style.display = 'none';
} else {
document.getElementById(nome).style.display = 'none';
document.getElementById(pnome).style.display = '';
}
}
</script> 


<?php 

$categ = (int)$_GET["category"];
$requestorid = (int)$_GET["requestorid"];
$sort = $_GET["sort"];
$search = $_GET["search"];
$filter = $_GET["filter"];

$search = " AND requests.request like '%$search%' ";



$sort = " order by added asc ";


if ($filter == "true")
$filter = " AND requests.filledby = 0 ";
else
$filter = "";


if ($requestorid <> NULL)
{
if (($categ <> NULL) && ($categ <> 0))
 $categ = "WHERE requests.cat = " . $categ . " AND requests.userid = " . $requestorid;
else
 $categ = "WHERE requests.userid = " . $requestorid;
}

else if ($categ == 0)
$categ = '';
else
$categ = "WHERE requests.cat = " . $categ;


$conttorrent = "SELECT count(requests.id) FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id  $categ $filter $search AND  liberado != 'yes'"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->execute(); 
$count = $conttorr->fetchColumn() ; 

if ($count == 0){

show_error_msg("Ops", "Não temos torrent para atender no momento.");
}
print("<center>[ <a href='/pedidos_torrents.php'> Pedidos </a> | <a href='/pedido_add.php'> Fazer pedido </a> | <a href='/pesquisa_pedidos.php'> Pesquisar pedidos </a> | <a href='/pedido_liberacao.php'> Aguardando verificação </a> ]</center>");


$perpage = 15;

list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" . "category=" . $_GET["category"] . "&sort=" . $_GET["sort"] . "&" );


$res = $pdo->prepare("SELECT users.downloaded, users.uploaded, users.username, users.privacy, requests.filled, requests.comments,
requests.filledby, requests.id, requests.userid, requests.request, requests.descr, requests.added, requests.hits, categories.parent_cat as parent_cat, categories.name AS cat_name, categories.image AS cat_pic 
FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id  $categ 
$filter $search AND  liberado != 'yes' $sort $limit "); 
$res->execute(); 

print("<CENTER><table class='tab1' cellpadding='0' cellspacing='1' align='center' >");
print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><B>Pesquisa de Pedidos</B></td></tr>");
print("<br><br><CENTER><form method=get action=pesquisa_pedidos.php>");
print("<tr><td width=100% align=center colspan=2 class=tab1_col3p>Pesquisar por: <input type=text size=30 name=search></td></tr>");
print("<tr><td width=100% align=center colspan=2 class=tab1_col3p><input type=submit align=center value='Pesquisar!' style='height: 22px'></td></tr>");
print("</form></CENTER><br>");
print("</table></CENTER>\n");
echo"<br>";
echo $pagertop;
echo"<br>";

print("<form method=post action=takedelreq.php>");
print("<table class='tab1' cellpadding='0' cellspacing='1' align='center' >\n");
print("<tr><td class=ttable_headp align=left>Tipo</td><td class=ttable_headp align=center>Nome</td><td 
class=ttable_headp align=center width=150>Data do pedido</td>
<td class=ttable_headp align=center width=60> Votos </td>
<td class=ttable_headp align=center>Por</td>");
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){
print("<td class=ttable_headp width=1 align=center>Apagar</td>");
}
print("</tr>\n");
while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
{






$char1 = 60; //cut length
$shortname = CutName(htmlspecialchars($row["descr"]), $char1);


print("<tr><td class=tab1_col3p width='1%' align=center><img border=0 src=\"" . $site_config['SITEURL'] . "/images/categories/" . $row["cat_pic"] . "\" alt=\"" . $row["cat_name"] . "\" /></td>" .
"<td class=tab1_col3p align=left><b>".htmlspecialchars($row["request"])."</b><br>

<div id=\"p".$row['id']."\" style=\"\">
".$shortname." (
<a class=\"lastpost\" href=\"#\" onclick=\"toggle('n".$row['id']."', 'p".$row['id']."');return false;\" style=\"cursor: hand;\">mais</a>
)
</div>
<div id=\"n".$row['id']."\" style=\"display: none;\">
".htmlspecialchars($row["descr"])." (
<a class=\"lastpost\" href=\"#\" onclick=\"toggle('n".$row['id']."', 'p".$row['id']."');return false;\" style=\"cursor: hand;\">menos</a>
)

</td>

<td align=center class=tab1_col3p  width='15%'>".date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "</td>
<td class=tab1_col3p  width='10%'><center><b>$row[hits] voto(s) <br><a href=pedidos_votar.php?id=$row[id]>[ votar ]</a></center></b></td>
<td class=tab1_col3p  width='20%' align=center><a href=account-details.php?id=$row[userid]><b>" . $row[username] ."");
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){
 print("<td class=tab1_col3p  width='1%' align=center><input type=\"checkbox\" name=\"delreq[]\" value=\"" . $row[id] . "\" /></td>");
}


}
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){
print("<tr><td width=100% align=center colspan=6 class=tab1_col3p><input type=submit  style='width:30%; height: 25px' value='Apagar pedido'></td>");
} 
print("</tr></table>\n");
print("</form>");

echo $pagerbottom;


stdfoot();
//die;

?>
