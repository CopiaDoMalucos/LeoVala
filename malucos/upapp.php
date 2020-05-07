<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";

dbconn();
loggedinonly();



if($CURUSER["class"]== 9)

$id = 0 + $_GET['id'];

stdhead("Approval List");
//////////PAGER////////////
	$uploader = "AND torrents.owner = '".$CURUSER["id"]."'";
$res2 = mysql_query("SELECT COUNT(*) FROM torrents WHERE torrents.safe='no' ".$uploader."  ORDER BY name ASC");
        $row = mysql_fetch_array($res2);
        $count = $row[0];
$perpage = 19;
    list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" );
//////////END PAGER///////////
$res = mysql_query("SELECT * FROM torrents WHERE torrents.safe='no' ".$uploader."  ORDER BY name ASC $limit") or sqlerr();
$num = mysql_num_rows($res);
if($num == "0")
{
begin_framec("Torrents aguardando Aprovação");
print("<center>Todos os torrents foram aceitos</center>");
end_framec();  
} else {
begin_framec("Torrents para aprovar");

echo $pagertop;
print("<table border=1 width=100% cellspacing=0 cellpadding=5>\n");
print("<tr><td class=ttable_head align=center> Nome do Torrent</td><td class=ttable_head align=center>Adicionado</td><td class=ttable_head align=center>Enviado por</td><td class=ttable_head align=center>Aprovar?</td><td class=ttable_head align=center>Apagar?</td>\n");

for ($i = 0; $i < $num; ++$i)
{
  $arr = mysql_fetch_assoc($res);
  {
   $res321 = mysql_query("SELECT users.id, users.username, torrents.name, torrents.owner FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id =". $arr["id"] ." ") or sqlerr();
$row123 = mysql_fetch_array($res321);
$res12 = mysql_query("SELECT safe, markedby FROM torrents WHERE id=".$arr['id']."");
               $row12 = mysql_fetch_array($res12);
if ($row12["safe"] == "no")
          $safe = "<a href=upmark.php?id=". $arr["id"] .">Aprovar</a>";
          else
          $safe = "";
$reason2 = "unaproved";          
$smallname =substr(htmlspecialchars($arr["name"]) , 0, $MAXDISPLAYLENGTH);
if ($torrent_name = htmlspecialchars($arr["name"])) { $torrent_name .= ""; }
print("<tr>\n");
print("<td align=left><a href=torrents-details.php?id=". $arr["id"] ."><u><b>$torrent_name</b></u></a></td><td align=left>". utc_to_tz($arr['added']) ."</td>" .
"<td align=center><a href=account-details.php?id=" . $arr["owner"] . "><b>" . htmlspecialchars($row123["username"]) . "</b></a><td align=center>$safe</td>");
print("<td align=center><a href=app.php?act=deltorrent&id=$arr[id]>Apagar</a></td>");
print("</td></tr>\n");
}
}
print("</table>\n");

end_framec();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
if($act == "deltorrent")
{
$id = 0 + $id;
if (!$id)
    die();
$res = mysql_query("SELECT name,owner,seeders,image1,image2 FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
$remover = $row['owner'];
if (!$row)
    die();

if ($CURUSER["id"] != $row["owner"] && get_user_class() < 5){
bark("Error", "" . CANT_EDIT_TORRENT . "");
die;
}
deletetorrent($id);
///////////////////////////HACH UPLOAD IMAGES///////////////////////////////////
if ($row["image1"]) {
$img1 = "/uploads/images/$row[image1]";
$del = unlink($img1);
}
if ($row["image2"]) {
$img2 = "/uploads/images/$row[image2]";
$del = unlink($img2);
}

write_log("Torrent $id ($row[name]) foi apagado por: $CURUSER[username] Ele foi recusado\n");
$nomtorrent = $row['name'];
$msg = "Desculpe!\n\nSeu torrent ($nomtorrent) n não foi aprovado e foi excluído porque não estava de acordo com as regras,  e nenhuma alteração foi feita .Você é bem-vindos no nosso tracker, mas deveria ter corrigido o que estava faltando, Obrigado(a). $CURUSER[username].\n\n";
            $sql = "INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $remover, \"". stripslashes ($msg)."\", NOW())";
            mysql_query($sql);
begin_framec("Torrent Deleted", center);
print("<b>Torrent (".$nomtorrent.") excluída </b><br/>");
print("<b>A MP foi enviar ao " . htmlspecialchars($row123["username"]) . "</b>");
print("<br><br><a href='app.php'> Atualizar </a>");
end_framec();
///////////////////////////////////////////////////////////////////////////////////////////////////////
}
stdfoot();
?>
