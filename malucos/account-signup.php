<?php
############################################################
#######                                             ########
#######                                             ########
#######           Malucos-share.net 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
include_once("login/common.php");
dbconn();

$registered = number_format(get_row_count("users"));
$liko = $site_config['maxusers'] - $registered;
$maxreg = $site_config['maxusers'];
	

$username_length = 15; // Max username length. You shouldn't set this higher without editing the database first
$password_minlength = 6;
$password_maxlength = 40;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo $site_config['SITENAME']?> <?php echo "".REGTIT."";?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="login/theme/style.css">
<link rel="stylesheet" type="text/css" href="login/theme/shead.css">
<script src="login/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="login/js/jquery.tools.min.js" type="text/javascript"></script>
<script src="login/js/login.js" type="text/javascript"></script>

<script>
    $(document).ready(function () {
       $('div#login').hide();
        $('div#login').fadeIn(2500);
    });
</script>

</head>
<body>

<div align="center" class="usersinfo">
<div class="uinfoimg"><a href="account-login.php"><img src="login/theme/images/web.png" title="<?php echo "Volta tela de login";?>"></a></div> 
<div class="uinfotext"><b><?php echo "Olá,";?> <font color='green'><?php echo "Seja Bem-vindo"; ?></font> </b></div>
</div>


<div id="login">
	<h2 class="head-alt"><?php echo "Formulário de cadastro";?></h2>
	<ul class="tabs">
		<li><a href="#"><?php echo "Cadastro";?></a></li>
		<li><a href="#">Denunciar venda de convite</a></li>
	</ul>
<div class="loglang">

</div>
	
	<div class="panes">
		<div>

<?php

// Disable checks if we're signing up with an invite
if (!is_valid_id($_REQUEST["invite"]) || strlen($_REQUEST["secret"]) != 32) {

 
	//get max members, and check how many users there is
	$numsitemembers = get_row_count("users");
	if ($numsitemembers >= $site_config["maxusers"])
		reg_error2(T_("SORRY")."...", T_("SITE_FULL_LIMIT_MSG") . number_format($site_config["maxusers"])." ".T_("SITE_FULL_LIMIT_REACHED_MSG")." ".number_format($numsitemembers)." members",1);
} else {
	$res = mysql_query("SELECT id FROM users WHERE id = $_REQUEST[invite] AND MD5(secret) = ".sqlesc($_REQUEST["secret"]));
	$invite_row = mysql_fetch_array($res);
	if (!$invite_row) {
		reg_error2(T_("ERROR"), T_("INVITE_ONLY_NOT_FOUND")." ".($site_config['signup_timeout']/86400)." days.", 1);
	}
}

if ($_GET["takesignup"] == "1") {
	$email1 = $_POST["email"];
// Disable checks if we're signing up with an invite
if ($site_config["INVITEONLY"]){
        $invite = $_POST["invite"];
          $r = mysql_query("SELECT inviter, inviteid, confirmed FROM invites WHERE email='$email1' AND invite=".sqlesc($invite));
          $i = mysql_fetch_assoc($r);
          if (mysql_num_rows($r) == 0)
		   $message = sprintf("".convite_não_encontrado."", htmlspecialchars($invite));
          if ($i["inviteid"] != 0)
		  $message = sprintf("".O_seu_convite_não."", htmlspecialchars($invite));
          }

          $message == "";

function validusername($username) {
		$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for ($i = 0; $i < strlen($username); ++$i)
			if (strpos($allowedchars, $username[$i]) === false)
			return false;
		return true;
}

	$wantusername = $_POST["wantusername"];
	$email = $_POST["email"];
	$wantpassword = $_POST["wantpassword"];
	$passagain = $_POST["passagain"];
	$country = $_POST["country"];
	$gender = $_POST["gender"];
	$client = $_POST["client"];
	$regras1 = $_POST["regras1"];
	$regras2 = $_POST["regras2"];
	$regras3 = $_POST["regras3"];
	$age = (int) $_POST["age"];

  if (empty($wantpassword) || (empty($email) && !$invite_row) || empty($wantusername) )
	$message = T_("DONT_LEAVE_ANY_FIELD_BLANK");
  elseif (empty($regras1) || empty($regras2)  || empty($regras3))
		$message = "Você deve concordar com os termos e regras";
 elseif (strlen($wantusername) > $username_length)
		$message = sprintf(T_("USERNAME_TOO_LONG"), $username_length);
  elseif ($wantpassword != $passagain)
		$message = T_("PASSWORDS_NOT_MATCH");
  elseif (strlen($wantpassword) < $password_minlength)
	$message = sprintf(T_("PASS_TOO_SHORT_2"), $password_minlength);
  elseif (strlen($wantpassword) > $password_maxlength)
	$message = sprintf(T_("PASS_TOO_LONG_2"), $password_maxlength);
  elseif ($wantpassword == $wantusername)
 	$message = T_("PASS_CANT_MATCH_USERNAME");
  elseif (!validusername($wantusername))
	$message = "Invalid username.";
  elseif (!$invite_row && !validemail($email))
		$message = "Isso não se parece com um email válido.";

	if ($message == "") {
		// Certain checks must be skipped for invites
		if (!$invite_row) {
			//check email isnt banned
			$maildomain = (substr($email, strpos($email, "@") + 1));
			$a = (@mysql_fetch_row(@mysql_query("select count(*) from email_bans where mail_domain='$email'")));
			if ($a[0] != 0)
				$message = sprintf("".EMAIL_ADDRESS_BANNED_S."", $email);

			$a = (@mysql_fetch_row(@mysql_query("select count(*) from email_bans where mail_domain='$maildomain'")));
			if ($a[0] != 0)
				$message = sprintf("".EMAIL_ADDRESS_BANNED_S."", $email);

		  // check if email addy is already in use
		  $a = (@mysql_fetch_row(@mysql_query("select count(*) from users where email='$email'")));
		  if ($a[0] != 0)
			$message = sprintf("".EMAIL_ADDRESS_INUSE_S."", $email);
		}

	   //check username isnt in use
	  $a = (@mysql_fetch_row(@mysql_query("select count(*) from users where username='$wantusername'")));
	  if ($a[0] != 0)
		$message = sprintf("".USERNAME_INUSE_S."", $wantusername); 

	  $secret = mksecret(); //generate secret field

	  $wantpassword = passhash($wantpassword);// hash the password
	}
if ($_POST["vehicle3"] !="") {
			$message = "".BAD_NAME."";
}
	if ($message != "")
		show_error2("".SIGNUP_FAILED."", $message, 1);

  if ($message == "") {
  if ($site_config["CONFIRMEMAIL"]){//req confirm email true/false
                $status = "pending";
        }else{
        if ($site_config["INVITEONLY"]){
        $status = "pending";
        }else{
                $status = "confirmed";
        }
   }
	//make first member admin
	if ($numsitemembers == '0')
		$signupclass = '7';
	else
		$signupclass = '1';

   if ($site_config["INVITEONLY"]){
       $ret = mysql_query("INSERT INTO users (username, password, secret, email, status, added, age, country, gender, client, stylesheet, language, class, ip, invited_by, invitedate) VALUES (" .
      implode(",", array_map("sqlesc", array($wantusername, $wantpassword, $secret, $email, $status, get_date_time(), $age, $country, $gender, $client, $site_config["default_theme"], $site_config["default_language"], $signupclass, getip(),$i["inviter"],get_date_time()))).")");
   } else {
       $ret = mysql_query("INSERT INTO users (username, password, secret, email, status, added, age, country, gender, client, stylesheet, language, class, ip) VALUES (" .
	  implode(",", array_map("sqlesc", array($wantusername, $wantpassword, $secret, $email, $status, get_date_time(), $age, $country, $gender, $client, $site_config["default_theme"], $site_config["default_language"], $signupclass, getip()))).")");
  
  }
    $id = mysql_insert_id();
   if ($site_config["INVITEONLY"]){
      mysql_query("UPDATE invites SET inviteid = '$id', confirmed='no', simounao='yes'  WHERE invite=".sqlesc($invite));


   }
      if($id > 0 ){
		write_loguser("Cadastro","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$id."]".$wantusername."[/url] efetuou o cadastro no site.");

   }
    $psecret = md5($secret);
    $thishost = $_SERVER["HTTP_HOST"];
    $thisdomain = preg_replace('/^www\./is', "", $thishost);

	//ADMIN CONFIRM
	if ($site_config["ACONFIRM"]) {
		$body = "".YOUR_ACCOUNT_AT.""." ".$site_config['SITENAME']." "."".HAS_BEEN_CREATED_YOU_WILL_HAVE_TO_WAIT.""."\n\n".$site_config['SITENAME']." "."".ADMIN."";
	}else{//NO ADMIN CONFIRM, BUT EMAIL CONFIRM
		$body = "Sua conta no foi criada com sucesso!!!"."\n\n"."Para confirmar o seu registo, clique no link abaixo:"."\n\n	".$site_config['SITEURL']."/account-confirm.php?id=$id&secret=$psecret\n\n"."Somente após a confirmação, você será capaz de acessar o site."."\n\n	"."Caso não haja a confirmação da conta,ela será excluída em alguns dias."."\n\n".$site_config['SITENAME']." "."";
	    $body = utf8_decode($body);
	}


  if ($site_config["CONFIRMEMAIL"] && !$site_config["INVITEONLY"]){ //email confirmation is on
//                ini_set("sendmail_from", "");
                sendmail($email, "Your ".$site_config['SITENAME']." User Account", $body, "From: ".$site_config['SITENAME']." <".$site_config['SITEEMAIL'].">");
                header("Refresh: 0; url=account-confirm-ok.php?type=signup&email=" . urlencode($email));
        }else{
                if ($site_config["INVITEONLY"]){ //invite only is on
                header("Refresh: 0; url=account-confirm-ok.php?type=invite");
                }else{
                header("Refresh: 0; url=account-confirm-ok.php?type=noconf");
        }
}
	if ($site_config["CONFIRMEMAIL"]){ //email confirmation is on
		sendmail($email, "Contato malucos-share", $body, "", "-f$site_config[SITEEMAIL]");
		header("Refresh: 0; url=account-confirm-ok.php?type=signup&email=" . urlencode($email));
	}
	//send pm to new user
	if ($site_config["WELCOMEPMON"]){
		$dt = sqlesc(get_date_time());
		$msg = sqlesc($site_config["WELCOMEPMMSG"]);
		mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $id, $dt, $msg, 0)");
	}

    die;
  }

}//end takesignup



?>

	<form method="post" action="account-signup.php?takesignup=1">
		<?php if ($invite_row) { ?>
			<input type="hidden" name="invite" value="<?php echo $_GET["invite"]; ?>" />
			<input type="hidden" name="secret" value="<?php echo $_GET["secret"]; ?>" />
		<?php } ?>
<br><br><br><br><br><br><br>
	<center><legend><?php echo ("<center><b>Obs: Os cookies deverão estar ativos para você poder se cadastrar ou logar.</center></b>");?></legend></center>
<?php
if ($site_config["INVITEONLY"]):
?>

<?php endif; ?>

	<table cellspacing="0"  border="0" align="center" width="80%" >

		    <tr>
				<td><?php echo T_("USERNAME"); ?>:</td>
				<td><input id="username" type="text" type="text" size="40" name="wantusername" /><font style="color:red">*</font></td>
			</tr>
			<tr>
				<td><?php echo T_("PASSWORD"); ?>:</td>
				<td><input type="password" size="40" id="password" type="password"  name="wantpassword" /><font style="color:red">*</font></td>
			</tr>	
				<td><?php echo T_("CONFIRM"); ?>:</td>
				<td><input type="password" size="40" id="password" type="password" name="passagain" /><font style="color:red">*</font></td>
			</tr>	
				<tr>
				<td><?php echo T_("EMAIL"); ?>:</td>
				<td><input type="text" size="40" name="email" /><font  style="color:red">*</font></td>
			</tr>

			       <?php
                           if ($site_config["INVITEONLY"]):
                           ?>
                         <tr><td>Código do Convite:</td>
                                <td><input type="text" size="60" name="invite" value="<?php echo  $_REQUEST["convite"]?>"/><font class="small"><font style="color:red">*</font></td>
                        </tr>
                         <?php endif; ?>
			
			
			
			
			
			
			</tr>	
				<td><?php echo T_("AGE"); ?>:</td>
				<td><input id="age" type="text" name="age" maxlength="2" /></td>
			</tr>
			<tr>
			 <td><?php echo T_("COUNTRY"); ?>:</td>
				<td>
					<select name="country" size="1">
						<?php
						$countries = "<option value=\"0\">---- ".T_("NONE_SELECTED")." ----</option>\n";
						$ct_r = mysql_query("SELECT id,name,domain from countries ORDER BY name");
						while ($ct_a = mysql_fetch_assoc($ct_r)) {
							$countries .= "<option value=\"$ct_a[id]\">$ct_a[name]</option>\n";
						}
						?>
						<?php echo $countries; ?>
					</select>
				</td>
			</tr>
	        <tr>
				<td><?php echo T_("GENDER"); ?>:</td>
				<td>
					<input type="radio" name="gender" value="Male" /><?php echo T_("MALE"); ?>
					&nbsp;&nbsp;
					<input type="radio" name="gender" value="Female" /><?php echo T_("FEMALE"); ?>
				</td>
			</tr>
	</table>
	<center>	
	<table>
						<center>						
	 <tr>
				<td>			 <input type="checkbox" name="regras1" value="Bike">Eu concordo em ler as Regras.</td>
		
			
			</tr>

			       <tr>
				<td> <input type="checkbox" name="regras2" value="Bike">Eu concordo em ler o FAQ.</td>

			</tr>
		</center>
				<br>
					<center>
				<tr>
				<td>	 <input type="checkbox" name="regras3" value="Bike">Eu tenho mais que 18 anos.</td>
		      	</tr>
	</center>
			
	
	<tr>
	          <center>
				<td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="button" type="submit" value="<?php echo T_("SIGNUP"); ?>" />
              </td>
			  </center>
			</tr>
			
			</table>
				</center>	
	</form>
</div>

		<div class="infobox">
		<center><b></b></center><br>
		<?php

		echo "Nome:<br>";
		echo "E-mail para recebimento de um novo código:</br>";
		echo "E-mail de quem te enviou o convite:</br>";
		echo "Onde foi comprado o convite?:</br>";
		echo "printscreen do pagamento:</br></br>";
		echo "Favor enviar estes dados para este e-mail:<a href='mailto:contato@malucos-share.org'>>>>>Aqui<<<<<</a></br>";
		?>
		</div>
		
</div>
</div>
<div align="center" >
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" /></a><br />Este obra foi licenciado sob uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR">Licença Creative Commons</a>.
	</div>
	
</body>
</html>