<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(false);
loggedinonly();
require ("backend/conexao.php");

$pdo = conectar();

if($CURUSER["edit_users"]!="yes")
	show_error_msg(T_("ACCESS_DENIED"),T_("YOU_DONT_HAVE_EDIT_USER_PERM"),1);

$action = $_POST["action"];

if (!$action)
	show_error_msg(T_("ERROR"), T_("TASK_NOT_FOUND"), 1);

if ($action == 'edituser'){
	$userid = $_POST["userid"];
	$title = $_POST["title"];
	$seedbonus = $_POST["seedbonus"];
	$signature = $_POST["signature"];
	$avatar = $_POST["avatar"];
	$ip = $_POST["ip"];
	$class = (int) $_POST["class"];
	$password = $_POST["password"];
	$warned = $_POST["warned"];
	$forumbanned = $_POST["forumbanned"];
	$hideshoutbox = $_POST["hideshoutbox"];
	$dj = $_POST["dj"];
	$djstaff = $_POST["djstaff"];
	$modcomment = $_POST["modcomment"];
	$enabled = $_POST["enabled"];
	$invites =(int) $_POST["invites"];
	$class = (int)$_POST["class"];
		if($CURUSER["level"]=="Administrador" ){
	$email = $_POST["email"];
	
		if (!validemail($email))
		show_error_msg(T_("EDITING_FAILED"), T_("EMAIL_ADDRESS_NOT_VALID"), 1);
		}

	if (!is_valid_id($userid))
		show_error_msg("Edição de Falha", "Invalido UserID",1);


	//change user class
	$res = SQL_Query_exec("SELECT class FROM users WHERE id=$userid");
	$arr = mysql_fetch_row($res);
	$uc = $arr[0];

	// skip if class is same as current
	if ($uc != $class && $class > 0) {
		if ($userid == $CURUSER["id"]) {
			show_error_msg(T_("EDITING_FAILED"), T_("YOU_CANT_DEMOTE_YOURSELF"),1);
		} elseif ($uc >= get_user_class()) {
			show_error_msg(T_("EDITING_FAILED"), T_("YOU_CANT_DEMOTE_SOMEONE_SAME_LVL"),1);
		} else {
			@SQL_Query_exec("UPDATE users SET class=$class WHERE id=$userid");
			// Notify user
			$prodemoted = ($class > $uc ? "promovido" : "rebaixado");
			$msg = sqlesc("Prezado usuário, 
			
			Você foi $prodemoted a '" . get_user_class_name($class) . "' por " . $CURUSER["username"] . "
			Parabéns e boa sorte.
			
			Equipe MS");
			$added = sqlesc(get_date_time());
			@SQL_Query_exec("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)");
write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] promoveu o usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url] para '" . get_user_class_name($class) . "'\n");	
		}
	}
	//continue updates
   if ($seedbonus > 0 )
   {
	write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] adicionou + ".$seedbonus." pontos para o usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");

   }
      if ($invites > 0 )
   {
	write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] adicionou + ".$invites." convites para o usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");

   }
         $r = @SQL_Query_exec("SELECT * FROM users WHERE id=$userid");
$user1 = mysql_fetch_array($r);
$baniruserf = $user1['username'];
$added_forum = get_date_time();
   $enabled_log = $_POST['enabled']=='no' ? true : false;
   if ($enabled_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET enabled = :enabled WHERE id= :userid ");
    $row_enabled->bindParam(':enabled', $enabled);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] desativou a conta do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
      $enabled_log = $_POST['enabled']=='yes' ? true : false;
   if ($enabled_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET enabled = :enabled WHERE id= :userid ");
    $row_enabled->bindParam(':enabled', $enabled);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] ativou a conta do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
   
   $warned_log = $_POST['warned']=='no' ? true : false;
   if ($warned_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET warned = :warned WHERE id= :userid ");
    $row_enabled->bindParam(':warned', $warned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] removeu a advertência do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
      $warned_log = $_POST['warned']=='yes' ? true : false;
   if ($warned_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET warned = :warned WHERE id= :userid ");
    $row_enabled->bindParam(':warned', $warned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] advertiu o [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }   
      $forumbanned_log = $_POST['forumbanned']=='no' ? true : false;
   if ($forumbanned_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET forumbanned = :forumbanned WHERE id= :userid ");
    $row_enabled->bindParam(':forumbanned', $forumbanned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] liberou o forúm ao usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
      SQL_Query_exec("DELETE FROM usermoderado WHERE tipo = 'forum' AND uid = $userid ");	
   }
      $forumbanned_log = $_POST['forumbanned']=='yes' ? true : false;
   if ($forumbanned_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET forumbanned = :forumbanned WHERE id= :userid ");
    $row_enabled->bindParam(':forumbanned', $forumbanned);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	$tipo_forum = 'forum';
	$forumbanneduser =    $CURUSER["username"]; 
	$forumbannedname =    $CURUSER["id"]; 
	$forumbannedmod=$pdo->prepare("INSERT INTO usermoderado (uid, username, uidmod, usernamemod, added, tipo ) VALUES (:uid, :username, :uidmod, :usernamemod, :added, :tipo )");
	$forumbannedmod->bindParam(':uid', $userid);
    $forumbannedmod->bindParam(':username', $baniruserf);
    $forumbannedmod->bindParam(':added', $added_forum);	
    $forumbannedmod->bindParam(':usernamemod', $forumbanneduser);	
    $forumbannedmod->bindParam(':uidmod', $forumbannedname);		
    $forumbannedmod->bindParam(':tipo', $tipo_forum);	
    $forumbannedmod->execute(); 		
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] baniu o usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url] do fórum \n");
   }  
   
         $hideshoutbox_log = $_POST['hideshoutbox']=='no' ? true : false;
   if ($hideshoutbox_log) {
	$row_enabled=$pdo->prepare("UPDATE users SET hideshoutbox = :hideshoutbox WHERE id= :userid ");
    $row_enabled->bindParam(':hideshoutbox', $hideshoutbox);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] liberou o shoutbox ao usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   SQL_Query_exec("DELETE FROM usermoderado WHERE tipo = 'shoutbox' AND uid = $userid ");	
   }
   
      $hideshoutbox_log = $_POST['hideshoutbox']=='yes' ? true : false;
   if ($hideshoutbox_log) {
   	$row_enabled=$pdo->prepare("UPDATE users SET hideshoutbox = :hideshoutbox WHERE id= :userid ");
    $row_enabled->bindParam(':hideshoutbox', $hideshoutbox);
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	$tipo_forum = 'shoutbox';
	$forumbanneduser =    $CURUSER["username"]; 
	$forumbannedname =    $CURUSER["id"]; 
	$forumbannedmod=$pdo->prepare("INSERT INTO usermoderado (uid, username, uidmod, usernamemod, added, tipo ) VALUES (:uid, :username, :uidmod, :usernamemod, :added, :tipo )");
	$forumbannedmod->bindParam(':uid', $userid);
    $forumbannedmod->bindParam(':username', $baniruserf);
    $forumbannedmod->bindParam(':added', $added_forum);	
    $forumbannedmod->bindParam(':usernamemod', $forumbanneduser);	
    $forumbannedmod->bindParam(':uidmod', $forumbannedname);		
    $forumbannedmod->bindParam(':tipo', $tipo_forum);	
    $forumbannedmod->execute(); 			
    write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] baniu o usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url] do shoutbox \n");
   }    

   if ($user1['email'] != $email){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o e-mail do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   }
 
   if ($user1['avatar'] != $avatar){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o avatar do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }
   if ($user1['title'] != $title){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o título do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }
   if ($user1['signature'] != $signature){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou a assinatura do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }

      if ($user1['modcomment'] != $modcomment){
      write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] alterou o comentário moderação do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
   
   }   
	if($CURUSER["level"]=="Administrador" ){
   	$row_enabled=$pdo->prepare("UPDATE users SET title = :title, email = :email, signature = :signature, avatar = :avatar, ip = :ip, dj = :dj, djstaff = :djstaff,  modcomment= :modcomment,  seedbonus = seedbonus + :seedbonus,  invites = invites + :invites  WHERE id= :userid ");
    $row_enabled->bindParam(':title', $title);
	$row_enabled->bindParam(':email', $email);
	$row_enabled->bindParam(':signature', $signature);
	$row_enabled->bindParam(':avatar', $avatar);
	$row_enabled->bindParam(':ip', $ip);	
	$row_enabled->bindParam(':dj', $dj);	
	$row_enabled->bindParam(':djstaff', $djstaff);	
    $row_enabled->bindParam(':modcomment', $modcomment);	
    $row_enabled->bindParam(':seedbonus', $seedbonus);	
    $row_enabled->bindParam(':invites', $invites);			
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	
	}else
	{
	
	   	$row_enabled=$pdo->prepare("UPDATE users SET title = :title, signature = :signature, avatar = :avatar, ip = :ip,  dj = :dj, djstaff = :djstaff,  modcomment= :modcomment,  seedbonus = seedbonus + :seedbonus,  invites = invites + :invites  WHERE id= :userid ");
    $row_enabled->bindParam(':title', $title);
	$row_enabled->bindParam(':signature', $signature);
	$row_enabled->bindParam(':avatar', $avatar);
	$row_enabled->bindParam(':ip', $ip);	
	$row_enabled->bindParam(':dj', $dj);	
	$row_enabled->bindParam(':djstaff', $djstaff);	
    $row_enabled->bindParam(':modcomment', $modcomment);	
    $row_enabled->bindParam(':seedbonus', $seedbonus);	
    $row_enabled->bindParam(':invites', $invites);			
	$row_enabled->bindParam(':userid', $userid);	
    $row_enabled->execute();
	}
	



	$chgpasswd = $_POST['chgpasswd']=='yes' ? true : false;
	if ($chgpasswd) {
		$passreq = SQL_Query_exec("SELECT password FROM users WHERE id=$userid");
		$passres = mysql_fetch_assoc($passreq);
		if($password != $passres['password']){
			$password = passhash($password);
			SQL_Query_exec("UPDATE users SET password='$password' WHERE id=$userid");
			write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] mudou a senha do usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");

		}
	}
  
  header("Location: account-details.php?id=$userid");
  die;
}

if ($action == 'addwarning'){
	$userid = (int)$_POST["userid"];
	$reason = mysql_real_escape_string($_POST["reason"]);
	$expiry = (int)$_POST["expiry"];
	$type = mysql_real_escape_string($_POST["type"]);

	if (!is_valid_id($userid))
		show_error_msg(T_("EDITING_FAILED"), T_("INVALID_USERID"),1);

	if (!$reason || !$expiry || !$type){
		show_error_msg(T_("ERROR"), T_("MISSING_FORM_DATA").".", 1);
	}

	$timenow = get_date_time();

	$expiretime = get_date_time(gmtime() + (86400 * $expiry));

	$ret = SQL_Query_exec("INSERT INTO warnings (userid, reason, added, expiry, warnedby, type) VALUES ('$userid','$reason','$timenow','$expiretime','".$CURUSER['id']."','$type')");

	$ret = SQL_Query_exec("UPDATE users SET warned='yes' WHERE id='$userid'");

	$msg = sqlesc("Você foi avisado por " . $CURUSER["username"] . " - razão: ".$reason." - expiração: ".$expiretime."");
	$added = sqlesc(get_date_time());
	@SQL_Query_exec("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)");
write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] adicionou um aviso para o usuário [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
	header("Location: account-details.php?id=$userid");
	die;
}


if ($action == "deleteaccount"){
    
    if ($CURUSER["delete_users"] != "yes")//only allow admins to delete users
		show_error_msg(T_("ERROR"), T_("TASK_ADMIN"),1);

	$userid = (int)$_POST["userid"];
	$username = sqlesc($_POST["username"]);
	$delreason = sqlesc($_POST["delreason"]);

	if (!is_valid_id($userid))
		show_error_msg(T_("FAILED"), T_("INVALID_USERID"),1);

    if ($CURUSER["id"] == $userid) 
        show_error_msg("Error", "You cannot delete yourself.", 1);
        
	if (!$delreason){
		show_error_msg(T_("ERROR"), T_("MISSING_FORM_DATA"), 1);
	}

	deleteaccount($userid);
write_logstaff("Usuário","#FF0000","O usuário [url=http://www.brshares.com/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] apagou a conta do [url=http://www.brshares.com/account-details.php?id=".$userid."]".$userid."[/url]\n");
	show_error_msg(T_("COMPLETED"), T_("USER_DELETE"), 1);
	die;
}

/*

if ($action == "banuser")
{
  $userid = $_POST["userid"];
  $what = $_POST["what"];
  if (!is_valid_id($userid))
    genbark("Not a vaild Userid");
  $comment = $_POST['comment'];
  if (!$comment)
    genbark("".T_("ERROR").":", "Please explain why you are banning this user!");
  $r = SQL_Query_exec("SELECT username,ip FROM users WHERE id=$userid") or sqlerr();
  $a = mysql_fetch_assoc($r);
  $username = $a["username"];
  $ip = $a["ip"];
  if ($what == "subnet")
  	$ip = substr($ip, 0, strrpos($ip, ".")) . ".*";
  else
    if ($what == 'ip')
      $extra = " OR ip='" . substr($ip, 0, strrpos($ip, ".")) . ".*'";
    else
      genbark("Heh", "Select what to ban!");
  $r = SQL_Query_exec("SELECT * FROM bans WHERE ip='$ip'$extra") or sqlerr();
  if (mysql_num_rows($r) > 0)
    genbark(T_("ERROR"), "IP/subnet is already banned");
  else {
    $dt = get_date_time();
    $comment = sqlesc($comment);
    SQL_Query_exec("INSERT INTO bans (userid, first, last, added, addedby, comment) VALUES($userid, '$ip', '$ip', '$dt', $CURUSER[id], $comment)") or sqlerr();
    SQL_Query_exec("UPDATE users SET secret='' WHERE id=$userid") or sqlerr();
    $returnto = $_POST["returnto"];
    header("Location: $returnto");
    die;
  }
}

if ($action == "enableaccount")
{
  $userid = $_POST["id"];
  $res = SQL_Query_exec("SELECT * FROM users WHERE id='$userid'") or sqlerr();
  if (mysql_num_rows($res) != 1)
    genbark("User $userid not found!");
  $secret = sqlesc(mksecret());
  SQL_Query_exec("UPDATE users SET secret=" . $secret . " WHERE id=$userid") or sqlerr();
  header("Location: account-details.php?id=$userid");
  die;
}
*/
?>
