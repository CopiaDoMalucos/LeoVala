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

if($CURUSER){
       // Get current datetime
    $dt = get_date_time(gmtime() - 180);
    // Search User Database for Moderators and above and display in alphabetical order
    $res = mysql_query("SELECT id, username, class,last_access FROM users WHERE class >=1 AND djstaff='yes' ORDER BY username") or sqlerr();
    $num = mysql_num_rows($res);
    while ($arr = mysql_fetch_assoc($res))
    {
        $staff_table[$arr['class']]=$staff_table[$arr['class']].
            "<img src=images/button_o".($arr[last_access]>$dt?"n":"ff")."line.gif>".
            "<a href=account-details.php?id=$arr[id]>$arr[username]</a>".
               "<a href=mailbox.php?Escrever&id=$arr[id]>".
            "<img src=images/button_pm.gif border=0></a>";
        // Show 3 staff per row, separated by an empty column
        ++ $col[$arr['class']];
        if ($col[$arr['class']]<=1)
            $staff_table[$arr['class']]=$staff_table[$arr['class']]."";
        else
        {
            $staff_table[$arr['class']]=$staff_table[$arr['class']]."";
            $col[$arr['class']]=1;
        }
    }


   stdhead("Programação da rádio");

begin_framec("Coordenador da Rádio");

print(" <br><B>Você quer fazer parte da equipe de DJ´s do site, entre em contato com os coordenadores e saiba mais como participar.</B><br><br> </br>    &nbsp;|&nbsp; $staff_table[85] &nbsp;|&nbsp; $staff_table[25] &nbsp $staff_table[30] &nbsp $staff_table[35] &nbsp $staff_table[40] &nbsp $staff_table[45] &nbsp $staff_table[50] &nbsp $staff_table[55] &nbsp $staff_table[9] &nbsp $staff_table[60] &nbsp $staff_table[65] &nbsp $staff_table[70] &nbsp $staff_table[75] &nbsp $staff_table[80]&nbsp $staff_table[90]&nbsp $staff_table[95]<br>");
end_framec();
begin_framec("Segunda-Feira");

echo("<CENTER><table class=table_table cellspacing=1 cellpadding=3 width=100% >");
echo("<td class=tab1_cab1>Dj</td><td class=tab1_cab1>Logo DJ</td><td class=tab1_cab1>Estilo</td><td 
class=tab1_cab1>Início</td><td 
class=tab1_cab1>Fim</td>");

$query = "SELECT * FROM dj WHERE day = '1' ORDER BY startime";
$sql = mysql_query($query);

while ($row = mysql_fetch_array($sql)) {
    $id = (int)$row['id'];
    $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
    $bio = htmlspecialchars($row['bio']);
    $genre = $row['genre'];        
        $startime = $row['startime'];
        $endtime = $row['endtime'];

echo("<tr><td width=30% class=table_col1><b>$name</b></td><td class=table_col2 align=center><img height='50' width='50'  src='$image'></td><td width=20% class=table_col2><b>$genre</b></td><td class=table_col1><b>$startime</b></td><td class=table_col2><b>$endtime</b></td></tr>");
}
echo "</table></CENTER>";

end_framec();

begin_framec("Terça-Feira");

echo("<CENTER><table class=table_table cellspacing=1 cellpadding=3 width=100%>");
echo("<td class=tab1_cab1>Dj</td><td class=tab1_cab1>Logo DJ</td><td class=tab1_cab1>Estilo</td><td 
class=tab1_cab1>Início</td><td 
class=tab1_cab1>Fim</td>");

$query = "SELECT * FROM dj WHERE day = '2' ORDER BY startime";
$sql = mysql_query($query);

while ($row = mysql_fetch_array($sql)) {
    $id = (int)$row['id'];
    $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
    $bio = htmlspecialchars($row['bio']);
    $genre = $row['genre'];        
        $startime = $row['startime'];
        $endtime = $row['endtime'];

echo("<tr><td width=30% class=table_col1><b>$name</b></td><td class=table_col2 align=center><img height='50' width='50'  src='$image'></td><td width=20% class=table_col2><b>$genre</b></td><td class=table_col1><b>$startime</b></td><td class=table_col2><b>$endtime</b></td></tr>");
}
echo "</table></CENTER>";

end_framec();

begin_framec("Quarta-Feira");

echo("<CENTER><table class=table_table cellspacing=1 cellpadding=3 width=100%>");
echo("<td class=tab1_cab1>Dj</td><td class=tab1_cab1>Logo DJ</td><td class=tab1_cab1>Estilo</td><td 
class=tab1_cab1>Início</td><td 
class=tab1_cab1>Fim</td>");

$query = "SELECT * FROM dj WHERE day = '3' ORDER BY startime";
$sql = mysql_query($query);

while ($row = mysql_fetch_array($sql)) {
    $id = (int)$row['id'];
    $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
    $bio = htmlspecialchars($row['bio']);
    $genre = $row['genre'];        
        $startime = $row['startime'];
        $endtime = $row['endtime'];

echo("<tr><td width=30% class=table_col1><b>$name</b></td><td class=table_col2 align=center><img height='50' width='50'  src='$image'></td><td width=20% class=table_col2><b>$genre</b></td><td class=table_col1><b>$startime</b></td><td class=table_col2><b>$endtime</b></td></tr>");
}
echo "</table></CENTER>";

end_framec();

begin_framec("Quinta-Feira");

echo("<CENTER><table class=table_table cellspacing=1 cellpadding=3 width=100%>");
echo("<td class=tab1_cab1>Dj</td><td class=tab1_cab1>Logo DJ</td><td class=tab1_cab1>Estilo</td><td 
class=tab1_cab1>Início</td><td 
class=tab1_cab1>Fim</td>");

$query = "SELECT * FROM dj WHERE day = '4' ORDER BY startime";
$sql = mysql_query($query);

while ($row = mysql_fetch_array($sql)) {
    $id = (int)$row['id'];
    $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
    $bio = htmlspecialchars($row['bio']);
    $genre = $row['genre'];        
        $startime = $row['startime'];
        $endtime = $row['endtime'];

echo("<tr><td width=30% class=table_col1><b>$name</b></td><td class=table_col2 align=center><img height='50' width='50' src='$image'></td><td width=20% class=table_col2><b>$genre</b></td><td class=table_col1><b>$startime</b></td><td class=table_col2><b>$endtime</b></td></tr>");
}
echo "</table></CENTER>";

end_framec();

begin_framec("Sexta-Feira");

echo("<CENTER><table class=table_table cellspacing=1 cellpadding=3 width=100%>");
echo("<td class=tab1_cab1>Dj</td><td class=tab1_cab1>Logo DJ</td><td class=tab1_cab1>Estilo</td><td 
class=tab1_cab1>Início</td><td 
class=tab1_cab1>Fim</td>");

$query = "SELECT * FROM dj WHERE day = '5' ORDER BY startime";
$sql = mysql_query($query);

while ($row = mysql_fetch_array($sql)) {
    $id = (int)$row['id'];
    $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
    $bio = htmlspecialchars($row['bio']);
    $genre = $row['genre'];        
        $startime = $row['startime'];
        $endtime = $row['endtime'];

echo("<tr><td width=30% class=table_col1><b>$name</b></td><td class=table_col2 align=center><img height='50' width='50'  src='$image'></td><td width=20% class=table_col2><b>$genre</b></td><td class=table_col1><b>$startime</b></td><td class=table_col2><b>$endtime</b></td></tr>");
}
echo "</table></CENTER>";

end_framec();

begin_framec("Sábado");

echo("<CENTER><table class=table_table cellspacing=1 cellpadding=3 width=100%>");
echo("<td class=tab1_cab1>Dj</td><td class=tab1_cab1>Logo DJ</td><td class=tab1_cab1>Estilo</td><td 
class=tab1_cab1>Início</td><td 
class=tab1_cab1>Fim</td>");

$query = "SELECT * FROM dj WHERE day = '6' ORDER BY startime";
$sql = mysql_query($query);

while ($row = mysql_fetch_array($sql)) {
    $id = (int)$row['id'];
    $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
    $bio = htmlspecialchars($row['bio']);
    $genre = $row['genre'];        
        $startime = $row['startime'];
        $endtime = $row['endtime'];

echo("<tr><td width=30% class=table_col1><b>$name</b></td><td class=table_col2 align=center><img height='50' width='50'  src='$image'></td><td width=20% class=table_col2><b>$genre</b></td><td class=table_col1><b>$startime</b></td><td class=table_col2><b>$endtime</b></td></tr>");
}
echo "</table></CENTER>";

end_framec();

begin_framec("Domingo");

echo("<CENTER><table class=table_table cellspacing=1 cellpadding=3 width=100%>");
echo("<td class=tab1_cab1>Dj</td><td class=tab1_cab1>Logo DJ</td><td class=tab1_cab1>Estilo</td><td 
class=tab1_cab1>Início</td><td 
class=tab1_cab1>Fim</td>");

$query = "SELECT * FROM dj WHERE day = '7' ORDER BY startime";
$sql = mysql_query($query);

while ($row = mysql_fetch_array($sql)) {
    $id = (int)$row['id'];
    $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
    $bio = htmlspecialchars($row['bio']);
    $genre = $row['genre'];        
        $startime = $row['startime'];
        $endtime = $row['endtime'];

echo("<tr><td width=30% class=table_col1><b>$name</b></td><td class=table_col2 align=center><img height='50' width='50' src='$image'></td><td width=20% class=table_col2><b>$genre</b></td><td class=table_col1><b>$startime</b></td><td class=table_col2><b>$endtime</b></td></tr>");
}
echo "</table></CENTER>";

end_framec();
}
   stdfoot();

?>