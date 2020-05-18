<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(true);
loggedinonly();  
header("Content-Type: text/html; charset=ISO-8859-1");
echo"<body style='background-color:#6E6E6E;'>";
?>

<script Language="JavaScript">
<!--

function InsertSmilie(texttoins)
{
window.opener.document.shoutboxform.message.value = window.opener.document.shoutboxform.message.value+' '+texttoins+' ';
window.opener.document.shoutboxform.message.focus();
window.close();
}



//-->
</SCRIPT>

<?php
dbconn(false);

$query = 'SELECT * FROM shoutbox_emoticons GROUP BY image';

$result = SQL_Query_exec($query);
$alt = false;

while ($row = mysql_fetch_assoc($result)) {

echo "
<img src='".$SITEURL."/images/smilies/".$row['image']."' onClick=\"InsertSmilie('".$row['text']."');\">";

}
?>