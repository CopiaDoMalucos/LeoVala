<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once ("backend/functions.php");
require_once ("backend/bbcode.php");
dbconn(false);

loggedinonly();

if (!$CURUSER || $CURUSER["djstaff"]!="yes" ){
	show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);
}

foreach($_POST as $key=>$value) $$key=$value;
foreach($_GET as $key=>$value) $$key=$value;

$sure = $_GET['sure'];
$del = $_GET['del'];
$team = htmlspecialchars($_GET['team']);
$edited = (int)$_GET['edited'];
$id = (int)$_GET['id'];
$team_name = $_GET['team_name'];
$team_info = $_GET['team_info'];
$team_image = $_GET['team_image'];
$team_genre = $_GET['team_genre'];
$team_day = $_GET['team_day'];
$team_description = $_GET['team_description'];
$teamownername = $_GET['team_owner'];
$editid = $_GET['editid'];
$editmembers = $_GET['editmembers'];
$name = $_GET['name'];
$image = $_GET['image'];
$genre = $_GET['genre'];
$day = $_GET['day'];
$owner = $_GET['owner'];
$info = $_GET['info'];
$add = $_GET['add'];



stdhead("DJ Painel Staff");
begin_framec("DJ Painel Staff");


//Delete Team
if($sure == "yes") {
    


    $query = "DELETE FROM dj WHERE id=" .sqlesc($del) . " LIMIT 1";
    $sql = SQL_Query_exec($query);
        
    echo("Dj excluído com sucesso![<a href='djmanage.php'>Voltar</a>]");

    end_framec();
    stdfoot();
    die();
}

if($del > 0) {
	echo("Você deseja excluir dj? ($team) ( <b><a href='djmanage.php?del=$del&team=$team&sure=yes'>Sim!</a></b> / <b><a href='djmanage.php'>Não!</a></b> )");
	end_framec();
	stdfoot();
	die();
}

//Edit Team
if($edited == 1) {
	$aa = SQL_Query_exec("SELECT id FROM dj WHERE id='$id'");
	$ar = mysql_fetch_assoc($aa);
	$id = $ar["id"];
	$query = "UPDATE dj SET name='$name', day='$team_day', startime='$startime', endtime='$endtime', image='$team_image', genre='$team_genre' WHERE id=".sqlesc($id);
	$sql = SQL_Query_exec($query);

	SQL_Query_exec("UPDATE users SET team = '$id' WHERE id= '$team_owner'");
	
	
	
	
	
	
	
	
       

    if($sql) {
        echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
        echo("<tr><td><div align='center'><b>Successfully Edited</b>[<a href='djmanage.php'>Back</a>]</div></tr>");
        echo("</table>");

        end_framec();
        stdfoot();
        die();
    }
}

if($editid > 0) {
	echo("<form name='smolf3d' method='get' action='djmanage.php'>");
	echo("<CENTER><table cellspacing=0 cellpadding=5 width=50%>");
	echo("<div align='center'><input type='hidden' name='edited' value='1'></div>");
	echo("<br>");
	echo("<input type='hidden' name='id' value='$editid'><table class=main cellspacing=0 cellpadding=5 width=50%>");
	echo("<tr><td>Dj: </td><td align='right'><input type='text' size=50 name='name' value='$name'></td></tr>");
	echo("<tr><td>Logo Dj: </td><td align='right'><input type='text' size=50 name='team_image' value='$image'></td></tr>");
	
	echo("<tr><td>Dia: </td><td align='right'><input type='text' size=50 name='team_day' value='$day'></td></tr>");
	echo("<tr><td>Estilo: </td><td align='right'><input type='text' size=50 name='team_genre' value='$genre'></td></tr>");
    
	echo("<tr><td>Início: </td><td align='right'><input type='time' size=50 name='startime' value='$startime'></td></tr>");
    echo("<tr><td>Fim: </td><td align='right'><input type='time' size=50 name='endtime' value='$endtime'></td></tr>");
	echo("<tr><td></td><td><div align='right'><input type='Submit' value=Update></div></td></tr>");
	echo("</table></CENTER></form>");
	end_framec();
	stdfoot();
	die();
}

//View Members
if($editmembers > 0) {
	echo("<CENTER><table class=table_table cellspacing=0 cellpadding=3>");
	echo("<td class=tab1_cab1>Username</td><td class=tab1_cab1>".T_("UPLOADED").": </td><td class=tab1_cab1>Downloaded</td></tr>");
	$query = "SELECT id,username,uploaded,downloaded FROM users WHERE team=$editmembers";
	$sql = SQL_Query_exec($query);
	while ($row = mysql_fetch_array($sql)) {
		$username = htmlspecialchars($row['username']);
		$uploaded = mksize($row['uploaded']);
		$downloaded = mksize($row['downloaded']);
		
		echo("<tr><td class=table_col1><a href=account-details.php?id=$row[id]>$username</a></td><td class=table_col2>$uploaded</td><td class=table_col1>$downloaded</td></tr>");
	}
	echo "</table></CENTER>";
	end_framec();
	stdfoot();
	die();
}


//Add Team
if($add == 'true') {


	$query = "INSERT INTO dj SET name = '$team_name', day='$team_day', startime='$startime', endtime='$endtime', image = '$team_image', genre = '$team_genre'";
	$sql = SQL_Query_exec($query);

	
	if($sql) {

		$success = TRUE;
	}else{
		$success = FALSE;
	}
}

print("<b>Adicionar Novo Dj:</b>");
print("<br>");
print("<br>");
echo("<form name='smolf3d' method='get' action='djmanage.php'>");
echo("<CENTER><table cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td>Dj: </td><td align='left'><input type='text' size=50 name='team_name'></td></tr>");
echo("<tr><td>Logo Dj:: </td><td align='left'><input type='text' size=50 name='team_image'><input type='hidden' name='add' value='true'></td></tr>");

echo("<tr><td>Dia: </td><td align='left'><input type='text' size=50 name='team_day'></td></tr>");
echo("<tr><td>Estilo: </td><td align='right'><input type='text' size=50 name='team_genre' value='$genre'></td></tr>");
echo("<tr><td>Início: </td><td align='right'><input type='time' size=50 name='startime' value='$startime'></td></tr>");
echo("<tr><td>Fim: </td><td align='right'><input type='time' size=50 name='endtime' value='$endtime'></td></tr>");
echo("<tr><td></td><td><div align='left'><input value='Add Novo DJ' type='Submit'></div></td></tr>");
echo("</table></CENTER>");
if($success == TRUE) {
	print("<b>team successfully added!</b>");
}
echo("<br>");
echo("</form>");

//ELSE Display ".T_("TEAMS")."
print("<b>Atual Dj:</b>");
print("<br>");
print("<br>");
echo("<CENTER><table class=table_table cellspacing=0 cellpadding=3>");
echo("<td class=tab1_cab1>ID</td><td class=tab1_cab1>Logo Dj</td><td class=tab1_cab1>Dia</td><td class=tab1_cab1>DJ</td><td class=tab1_cab1>Genero</td><td class=tab1_cab1>Início</td><td class=tab1_cab1>Fim</td><td class=tab1_cab1>".T_("OTHER")."</td>");
$query = "SELECT * FROM dj";
$sql = SQL_Query_exec($query);
while ($row = mysql_fetch_array($sql)) {
	$id = (int)$row['id'];
	$name = htmlspecialchars($row['name']);
	$image = htmlspecialchars($row['image']);
	$day = htmlspecialchars($row['day']);
	$startime = date($row['startime']);
    $endtime = date($row['endtime']);
	$genre = htmlspecialchars($row['genre']);
	$owner = (int)$row['owner'];
	$info = format_comment($row['info']);
	$OWNERNAME1 = SQL_Query_exec("SELECT username, class FROM users WHERE id=$owner");
	$OWNERNAME2 = mysql_fetch_array($OWNERNAME1);
	$OWNERNAME = $OWNERNAME2['username'];

	echo("<tr><td class=table_col1><b>$id</b> </td> <td class=table_col2 align=center><img src='$image'></td> <td class=table_col1><b>$day</b></td><td class=table_col2><b>$name</b></td><td class=table_col2><b>$genre</b></td><td class=table_col1>$startime</td><td class=table_col1>$endtime</td><td class=table_col2>&nbsp;<a href='djmanage.php?editid=$id&name=$name&image=$image&day=$day&startime=$startime&endtime=$endtime&genre=$genre'>[EDITAR]</a>&nbsp;<a href='djmanage.php?del=$id&team=$name'>[APAGAR]</a></td></tr>");
}
echo "</table></CENTER>";

end_framec();
stdfoot();

?> 