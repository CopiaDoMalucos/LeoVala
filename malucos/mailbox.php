<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
require_once("mailbox-functions.php");
dbconn(false);
loggedinonly();

$readme = add_get('read').'=';
$unread = false;

if (isset($_REQUEST['Escrever'])); // This blocks everything until done...

if (isset($_GET['Entrada']))
{
$pagename = T_("Entrada");
$tablefmt = "&nbsp;,Sender,Subject,Date";
$where = "`receiver` = $CURUSER[id] AND `location` IN ('in','both')";
$type = "Mail";
}
elseif (isset($_GET['Saida']))
{
$pagename = "Saida";
$tablefmt = "&nbsp;,Sent_to,Subject,Date";
$where = "`sender` = $CURUSER[id] AND `location` IN ('out','both')";
$type = "Mail";
}
elseif (isset($_GET['Rascunhos']))
{
$pagename = "Rascunhos";
$tablefmt = "&nbsp;,Sent_to,Subject,Date";
$where = "`sender` = $CURUSER[id] AND `location` = 'draft'";
$type = "Mail";
}
elseif (isset($_GET['Modelos']))
{
$pagename = "Modelos";
$tablefmt = "&nbsp;,Subject,Date";
$where = "`sender` = $CURUSER[id] AND `location` = 'template'";
$type = "Mail";
}
else
{
$pagename = "Visão geral";
$type = "Geral";
}

//****** Send a message, or save after editing ******
if (isset($_POST['send']) || isset($_POST['draft']) || isset($_POST['template']))
{
$username = $_POST['userid'];




		$resuse = mysql_query("SELECT id, username FROM users WHERE username='$username'");
					$user12 = mysql_fetch_array($resuse);



$validaruser = $user12["id"];

if (!is_valid_id($validaruser)){
  show_error_msg(("Erro"), "O usuário especificado não existe.",1);
}




if (!isset($_POST['template']) && !isset($_POST['change']) && (!isset($validaruser) || !is_valid_id($validaruser))) $error = "Unknown recipient";
else
{
   $sendto = (@$_POST['template'] ? $CURUSER['id'] : @$validaruser);
   if (isset($_POST['usetemplate']) && is_valid_id($_POST['usetemplate']))
   {
     $res = SQL_Query_exec("SELECT * FROM messages WHERE `id` = $_POST[usetemplate] AND `location` = 'template' LIMIT 1");
     $arr = mysql_fetch_array($res);
     $subject = $arr['subject'].(@$_POST['oldsubject'] ? " (was ".$_POST['oldsubject'].")" : "");
     $msg = sqlesc($arr['msg']);
   } else {
     $subject = @$_POST['subject'];
     $msg = sqlesc(@$_POST['msg']);
   }
   if ($msg)
   {
     $subject = sqlesc($subject);
     if ((isset($_POST['draft']) || isset($_POST['template'])) && isset($_POST['msgid'])) SQL_Query_exec("UPDATE messages SET `subject` = $subject, `msg` = $msg WHERE `id` = $_POST[msgid] AND `sender` = $CURUSER[id]") or die("arghh");
     else
     {
       $to = (@$_POST['draft'] ? 'draft' : (@$_POST['template'] ? 'template' : (@$_POST['save'] ? 'both' : 'in')));
       $status = (@$_POST['send'] ? 'yes' : 'no');
       SQL_Query_exec("INSERT INTO `messages` (`sender`, `receiver`, `added`, `subject`, `msg`, `unread`, `location`) VALUES ('$CURUSER[id]', '$sendto', '".get_date_time()."', $subject, $msg, '$status', '$to')") or die("Aargh!");



       if (isset($_POST['msgid'])) SQL_Query_exec("DELETE FROM messages WHERE `location` = 'draft' AND `sender` = $CURUSER[id] AND `id` = $_POST[msgid]") or die("arghh");
     }
     if (isset($_POST['send']))   show_error_msg(("Sucesso"), "Mensagem enviada com sucesso.",1);  
     else show_error_msg(("Sucesso"), "Mensagem salva com sucesso.",1);  
	 
   }
   else show_error_msg(("Erro"), "Não é possível enviar mensagem.",1); 
}
}

//****** Delete a message ******
if (isset($_POST['remove']) && (isset($_POST['msgs']) || is_array($_POST['remove'])))
{
if (is_array($_POST['remove'])) $tmp[] = key($_POST['remove']);
else foreach($_POST['msgs'] as $key => $value) if (is_valid_id($key)) $tmp[] = $key;
$msgs = implode(', ', $tmp);
if ($msgs)
{
   if (isset($_GET['Entrada']))
   {
     SQL_Query_exec("DELETE FROM messages WHERE `location` = 'in' AND `receiver` = $CURUSER[id] AND `id` IN ($msgs)");
     SQL_Query_exec("UPDATE messages SET `location` = 'out' WHERE `location` = 'both' AND `receiver` = $CURUSER[id] AND `id` IN ($msgs)");
   } else {                                                                                                                                                                          
     if (isset($_GET['Saida'])) SQL_Query_exec("UPDATE messages SET `location` = 'in' WHERE `location` = 'both' AND `sender` = $CURUSER[id] AND `id` IN ($msgs)");
     SQL_Query_exec("DELETE FROM messages WHERE `location` IN ('out', 'draft', 'template') AND `sender` = $CURUSER[id] AND `id` IN ($msgs)");
   }
   $info = count($tmp)." ".P_("message", count($tmp))." excluído";
}
else $error = "Nenhuma mensagem para apagar";
}

//****** Mark a message as read - only if you're the recipient ******
if (isset($_POST['mark']) && (isset($_POST['msgs']) || is_array($_POST['mark'])))
{
if (is_array($_POST['mark'])) $tmp[] = key($_POST['mark']);
else foreach($_POST['msgs'] as $key => $value) if (is_valid_id($key)) $tmp[] = $key;
$msgs = implode(', ', $tmp);
if ($msgs)
{
   SQL_Query_exec("UPDATE messages SET `unread` = 'no' WHERE `id` IN ($msgs) AND `receiver` = $CURUSER[id]");
   $info = count($tmp)." ".P_("message",  count($tmp))." marked as read";
}
else $error = "Nenhuma mensagem marcada como lida";
}


stdhead($pagename, false);

if (isset($_REQUEST['Escrever']))
{
begin_framec("Escrever");
$userid = @$_REQUEST['id'];
$subject = ''; $msg = ''; $to = ''; $hidden = ''; $output = ''; $reply = false;
if (is_array($_REQUEST['Escrever'])) // In reply or followup to another msg
{
   $msgid = key($_REQUEST['Escrever']);
   if (is_valid_id($msgid))
   {
     $res = SQL_Query_exec("SELECT * FROM `messages` WHERE `id` = $msgid AND '$CURUSER[id]' IN (`sender`,`receiver`) LIMIT 1");
     if ($arr = mysql_fetch_assoc($res))
     {
       $subject = htmlspecialchars($arr['subject']);
       $msg .= htmlspecialchars($arr['msg']);
       if (current($_REQUEST['Escrever']) == 'Reply')
       {
         if ($arr['unread'] == 'yes' && $arr['receiver'] == $CURUSER['id']) SQL_Query_exec("UPDATE messages SET `unread` = 'no' WHERE `id` = $arr[id]");
         $reply = true;
         $userid = $arr['sender'];
         if (substr($arr['subject'],0,4) != 'Re: ') $subject = "Re: $subject";
       }
       else $userid = $arr['receiver'];
       $hidden .= "<input type=\"hidden\" name=\"msgid\" value=\"$msgid\" />";
     }
   }
}
if (isset($_GET['templates'])) $to = 'who cares';
 elseif (is_valid_id($userid))
 {                                                
                                    
   $res = SQL_Query_exec("SELECT `username`, `acceptpms` FROM `users` WHERE `id` = $userid");
   $row = mysql_fetch_assoc($res);
   
   if (($row) || ($row["acceptpms"] == "no") && ($CURUSER["edit_users"] == "yes"))
   {                       
     $to = $row["username"];
     if ($reply) $msg = "\n\n======================= $to escreveu: =======================\n$msg";





		$resuse1 = mysql_query("SELECT id, username FROM users WHERE id=$userid");
					$user121 = mysql_fetch_array($resuse1);



$validaruser1 = $user121["username"];
     $hidden .= "<input type=\"hidden\" name=\"userid\" value=\"$validaruser1\">";
     $to = "<b>$to</b>";
   }
 }
 else
 {
 
   $acceptpms = ($CURUSER["edit_users"] == "no") ? "users.acceptpms = 'yes' AND" : "";   

   $res = SQL_Query_exec("SELECT users.id, users.username, users.acceptpms FROM users WHERE $acceptpms users.privacy!='strong' AND users.class<'2' ORDER BY users.username");
   if (mysql_num_rows($res))
   {
   

  
     $to = '<input type="text" name="userid" value="" size="40">';
	 
   }
 }
if (isset($_GET['id']) && !$to) print T_("INVALID_USER_ID");
elseif (!isset($_GET['id']) && !$to) print T_("NO_FRIENDS");
else
{
     /******** compose frame ********/

   begin_form(rem_get('Escrever'),'name="Escrever"');
   if ($subject) $hidden .= "<input type=\"hidden\" name=\"oldsubject\" value=\"$subject\" />";
        if ($hidden) print($hidden);
    echo "<table width='600px' border='0' align='center' cellpadding='0' cellspacing='0'>";
   if (!isset($_GET['templates'])){
     tr2("Para:", $to, 1);
     $res = SQL_Query_exec("SELECT * FROM `messages` WHERE `sender` = $CURUSER[id] AND `location` = 'template' ORDER BY `subject`");
     if (mysql_num_rows($res))
     {
       $tmp = "<select name=\"usetemplate\" onchange=\"toggleTemplate(this);\">\n<option name=\"0\">---</option>\n";
       while ($arr = mysql_fetch_assoc($res)) $tmp .= "<option value=\"$arr[id]\">$arr[subject]</option>\n";
       $tmp .= "</select><br />\n";
       tr2("Modelos:", $tmp, 1);
     }
   }
   tr2("Assunto:", "<input name=\"subject\" type=\"text\" size=\"60\" value=\"$subject\" />", 1);
//
//   tr2("Message","<textarea name=\"msg\" cols=\"50\" rows=\"15\">$msg</textarea>", 1);
require_once("backend/bbcode.php");
echo "</table>";
$dossier = $CURUSER['bbcode'];
print ("".textbbcode("compose","msg",$dossier,$msg)."");
echo "<table width='600px' border='0' align='center' cellpadding='4' cellspacing='0'>";

if (!isset($_GET['templates'])) $output .= "<input type=\"submit\" name=\"send\" value=\"Enviar\" />&nbsp;<label><input type=\"checkbox\" name=\"save\" checked='checked' />Salvar cópia na caixa de saída</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"draft\" value=\"Salvar rascunho\" />&nbsp;";
   tr2($output."<input type=\"submit\" name=\"template\" value=\"Salvar modelo\" />");
   echo "</table>";
   end_form();
   end_framec();
   stdfoot();
   die;
}
end_framec();
}

begin_framec($pagename);

echo "<center>";
print submenu('Geral,Entrada,Saida,Escrever,Rascunhos,Modelos','overview');
echo "</center><hr /><br />";
 

if ($type == "Geral")
{
begin_table();
$res = SQL_Query_exec("SELECT COUNT(*), COUNT(`unread` = 'yes') FROM messages WHERE `receiver` = $CURUSER[id] AND `location` IN ('in','both')");
$res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND `location` IN ('in','both')");
$inbox = mysql_result($res, 0);
   $res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE `receiver` = " . $CURUSER["id"] . " AND `location` IN ('in','both') AND `unread` = 'yes'");
   $unread = mysql_result($res, 0);
$res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE `sender` = " . $CURUSER["id"] . " AND `location` IN ('out','both')");
$outbox = mysql_result($res, 0);
$res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE `sender` = " . $CURUSER["id"] . " AND `location` = 'draft'");
$draft = mysql_result($res, 0);
$res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE `sender` = " . $CURUSER["id"] . " AND `location` = 'template'");
$template = mysql_result($res, 0);
tr2('<a href="mailbox.php?Entrada">'.T_("INBOX").' </a> ', " $inbox ".P_("message", $inbox)." ($unread não lido)");
tr2('<a href="mailbox.php?Saida">'.T_("OUTBOX").' </a> ', " $outbox ".P_("message", $outbox));
tr2('<a href="mailbox.php?Rascunhos">'.T_("DRAFT").' </a> ', " $draft ".P_("message", $draft));
tr2('<a href="mailbox.php?Modelos">Modelos </a> ', " $template ".P_("message", $template));
end_table();
echo"<br /><br />";
}
elseif ($type == "Mail")
{
$order = order("added,sender,sendto,subject", "added", true);
$res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE $where");
$count = mysql_result($res, 0);
list($pagertop, $pagerbottom, $limit) = pager2(20, $count);

print($pagertop);
begin_form();
begin_table(0,"list");
$table['&nbsp;']  = th("<input type=\"checkbox\" onclick=\"toggleChecked(this.checked);this.form.remove.disabled=true;\" />", 1);
$table['Sender']  = th_left("Remetente",'sender');
$table['Sent_to'] = th_left("Enviar para",'sendto');
$table['Subject'] = th_left("Assunto",'subject');
$table['Date']    = th_left("Data",'added');
table($table, $tablefmt);

$res = SQL_Query_exec("SELECT * FROM messages WHERE $where $order $limit");
while ($arr = mysql_fetch_assoc($res))
{
   unset($table);
   $userid = 0;
   $format = '';
   $reading = false;

   if ($arr["sender"] == $CURUSER['id']) $sender = "Yourself";
   elseif (is_valid_id($arr["sender"]))
   {
     $res2 = SQL_Query_exec("SELECT username FROM users WHERE `id` = $arr[sender]");
     $arr2 = mysql_fetch_assoc($res2);
     $sender = "<a href=\"account-details.php?id=$arr[sender]\">".($arr2["username"] ? $arr2["username"] : "[Deleted]")."</a>";
   }
   else $sender = "System";
//    $sender = $arr['sendername'];

   if ($arr["receiver"] == $CURUSER['id']) $sentto = "Yourself";
   elseif (is_valid_id($arr["receiver"]))
   {
     $res2 = SQL_Query_exec("SELECT username FROM users WHERE `id` = $arr[receiver]");
     $arr2 = mysql_fetch_assoc($res2);
     $sentto = "<a href=\"account-details.php?id=$arr[receiver]\">".($arr2["username"] ? $arr2["username"] : "[Deleted]")."</a>";
   }
   else $sentto = "System";

   $subject = ($arr['subject'] ? htmlspecialchars($arr['subject']) : "no subject");

   if (@$_GET['read'] == $arr['id'])
   {
     $reading = true;
     if (isset($_GET['Entrada']) && $arr["unread"] == "yes") SQL_Query_exec("UPDATE messages SET `unread` = 'no' WHERE `id` = $arr[id] AND `receiver` = $CURUSER[id]");
   }
   if ($arr["unread"] == "yes")
   {
     $format = "font-weight: bold; color:#487BF0;";
     $unread = true;
   }

   $table['&nbsp;']  = th_left("<input type=\"checkbox\" name=\"msgs[$arr[id]]\" ".($reading ? "checked='checked'" : "")." onclick=\"this.form.remove.disabled=true;\" />", 1);
   $table['Sender']  = th_left("$sender", 1, $format);
   $table['Sent_to'] = th_left("$sentto", 1, $format);
   $table['Subject'] = th_left("<a href=\"javascript:read($arr[id]);\"><img src=\"".$site_config["SITEURL"]."/images/plus.gif\" id=\"img_$arr[id]\" class=\"read\" border=\"0\" alt='' /></a>&nbsp;<a href=\"javascript:read($arr[id]);\">$subject</a>", 1, $format);
   $table['Date']    = th_left(date("d/m/y", utc_to_tz_time($arr['added']))." às ". date("H:i:s", utc_to_tz_time($arr['added'])), 1, $format);

   table($table, $tablefmt);

   $display = "<div>".format_comment($arr['msg'])."<br /><br />";
   if (isset($_GET['Entrada']) && is_valid_id($arr["sender"]))   $display .= "<input type=\"submit\" name=\"Escrever[$arr[id]]\" value=\"Reply\" />&nbsp;\n";
   elseif (isset($_GET['Rascunhos']) || isset($_GET['templates'])) $display .= "<input type=\"submit\" name=\"Escrever[$arr[id]]\" value=\"Editar\" />&nbsp;";
   if (isset($_GET['Entrada']) && $arr['unread'] == 'yes') $display .= "<input type=\"submit\" name=\"mark[$arr[id]]\" value=\"Marcar como lida\" />&nbsp;\n";
   $display .= "<input type=\"submit\" name=\"remove[$arr[id]]\" value=\"Apagar\" />&nbsp;\n";
   $display .= "</div>";
   table(td_left($display, 1, "padding:0 6px 6px 6px"), $tablefmt, "id=\"msg_$arr[id]\" style=\"display:none;\"");
}

// if ($count)
//{
   $buttons = "<input type=\"button\" value=\"Apagar selecionada\" onclick=\"this.form.remove.disabled=!this.form.remove.disabled;\" />";
   $buttons .= "<input type=\"submit\" name=\"remove\" value=\"...Confirmar\" disabled=\"disabled\" />";
   if (isset($_GET['Entrada']) && $unread) $buttons .= "&nbsp;<input type=\"button\" value=\"Marcar selecionada como lida\" onclick=\"this.form.mark.disabled=!this.form.mark.disabled;\" /><input type=\"submit\" name=\"mark\" value=\"...Confirmar\" disabled=\"disabled\" />";
   if (isset($_GET['Modelos'])) $buttons .= "&nbsp;<input type=\"submit\" name=\"compose\" value=\"Criar novo modelo\" />";
   table(td_left($buttons, 1, "border:0"), $tablefmt);
//}
end_table();
end_form();
print($pagerbottom);
}
end_framec();

stdfoot();
?>