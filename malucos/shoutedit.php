<?php
require_once("backend/functions.php");
dbconn();
	if ($CURUSER && $CURUSER["class"] >= 79) {
function linkit($al_url, $al_msg)        // create autolink
{
                echo "\n<meta http-equiv=\"refresh\" content=\"3; URL=$al_url\">\n";
                echo "<center>\n";
                echo "<b>$al_msg</b>\n";
                echo "<p>\n<b>Redirecting ...</b>\n";
                echo "<p>\n[ <a href='$al_url'>link</a> ]\n";
                echo "</td>\n</tr>\n</table>\n</td>\n</tr>\n</table>\n</body>\n</html>\n";
                echo "</center>\n";
                exit;
}

if ($_GET['action'] == 'edit') {
    $msgid = $_GET["msgid"];
          if (!is_valid_id($msgid))
        die;
    $res = mysql_query("SELECT * FROM shoutbox WHERE msgid=".$_GET['msgid']) or sqlerr();
	if (mysql_num_rows($res) != 1)
		showerror("Error", "No message with ID $msgid.");
	$arr = mysql_fetch_assoc($res);
    if ($CURUSER["id"] != $arr["userid"] && get_user_class($CURUSER) < 6)
		showerror("Error", "Denied!");
    $save = (int)$_GET["save"];
    if ($save) {
		$message = $_POST['message'];
			if ($message == "")
				showerror("Error", "Message cannot be empty!");
		$message = sqlesc($message);
		mysql_query("UPDATE shoutbox SET message=$message WHERE msgid=$msgid") or sqlerr();
                linkit("shoutbox.php", "Edit complete....");
	}
    print("<center><font size=3><b>Editar Mensagem</b></font></center>\n");
    print("<form name=Form method=post action=shoutedit.php?action=edit&save=1&msgid=$msgid>\n");
    print("<center><table border=0 cellspacing=0 cellpadding=5>\n");
    print("<tr><td>\n");
    print("</td><td style='padding: 0px'><textarea name=message cols=50 rows=20 >" . stripslashes(htmlspecialchars($arr["message"])) . "</textarea></td></tr>\n");
    print("<tr><td align=center colspan=2><input type=submit value='Salvar Alteração' class=btn></td></tr>\n");
    print("</table></center>\n");
    print("</form>\n");
}
}
else
{
		showerror("Error", "Message cannot be empty!");
}
?>