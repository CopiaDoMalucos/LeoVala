<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
include_once("login/common.php");
dbconn(false);

$registered = number_format(get_row_count("users"));
$liko = $site_config['maxusers'] - $registered;
$maxreg = $site_config['maxusers'];
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo $site_config['SITENAME']?> <?php echo "".RECTIT."";?></title>
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
<div class="uinfotext"><b><?php echo "Olá,";?> <font color='green'><?php echo "Seja Bem-Vindo ao BRShares"; ?></font></b></div>
</div>

<div id="login">
	<h2 class="head-alt"><?php echo "Formulário recuperar senha";?></h2>
	<ul class="tabs">
		<li><a href="#"><?php echo "Recuperar";?></a></li>
		<li><a href="#"><?php echo "Infor";?></a></li>
	</ul>
<div class="loglang">

</div>

	<div class="panes">
		</div>
<?php

$kind = "0";

if (is_valid_id($_POST["id"]) && strlen($_POST["secret"]) == 32) {
    $password = $_POST["password"];
    $password1 = $_POST["password1"];
    if (empty($password) || empty($password1)) {
         $kind = T_("ERROR");
        $msg =  T_("NO_EMPTY_FIELDS");
    } elseif ($password != $password1) {
       $kind = T_("ERROR");
        $msg = T_("PASSWORD_NO_MATCH");
    } else {
	$n = get_row_count("users", "WHERE `id`=".intval($_POST["id"])." AND MD5(`secret`) = ".sqlesc($_POST["secret"]));
	if ($n != 1)
		show_error_msg(T_("ERROR"), T_("NO_SUCH_USER"));
        $newsec = sqlesc(mksecret());
        SQL_Query_exec("UPDATE `users` SET `password` = '".passhash($password)."', `secret` = $newsec WHERE `id`=".intval($_POST['id'])." AND MD5(`secret`) = ".sqlesc($_POST["secret"]));
          $kind = T_("SUCCESS");
        $msg =  T_("PASSWORD_CHANGED_OK");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["take"] == 1) {
    $email = trim($_POST["email"]);

    if (!validemail($email)) {
        $msg = T_("EMAIL_ADDRESS_NOT_VAILD");
        $kind = T_("ERROR");
    }else{
        $res = SQL_Query_exec("SELECT id, username, email FROM users WHERE email=" . sqlesc($email) . " LIMIT 1");
        $arr = mysql_fetch_assoc($res);

        if (!$arr) {
             $msg = T_("EMAIL_ADDRESS_NOT_FOUND");
            $kind = T_("ERROR");
        }

        if ($arr) {
              $sec = mksecret();
            $secmd5 = md5($sec);
            $id = $arr['id'];

             $body = "Olá, alguém do IP " . $_SERVER["REMOTE_ADDR"] . ", esperamos que seja você, solicitou os detalhes da conta associada a este endereço de email ($email) para redefinição da senha. \r\n\r\n Abaixo estão as informações da conta e também o link para alterar sua senha: \r\n\r\n Nome de Usuário: ".$arr["username"]." \r\n Para alterar sua senha, clique no link abaixo:\n\n$site_config[SITEURL]/account-recover.php?id=$id&secret=$secmd5\n\n\n".$site_config["SITENAME"]."\r\n";
	         $body = utf8_decode($body);
           @sendmail($arr["email"], "Alteração de Senha", $body, "", "-f".$site_config['SITEEMAIL']);

              $res2 = SQL_Query_exec("UPDATE `users` SET `secret` = ".sqlesc($sec)." WHERE `email`= ". sqlesc($email) ." LIMIT 1");

              $msg = sprintf(T_('MAIL_RECOVER'), htmlspecialchars($email));

              $kind = T_("SUCCESS");
        }
    }
}


if ($kind != "0") {
    show_error2("Aviso", "$kind: $msg", 0);
}

if (is_valid_id($_GET["id"]) && strlen($_GET["secret"]) == 32) {?>

		<div>
			<form method="post" action="account-recover.php">
				<fieldset>
				<legend><?php echo T_("NEW_PASSWORD"); ?></legend>
				<input type="hidden" name="secret" value="<?php echo $_GET['secret']; ?>" />
				<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
				<label for="password"><?php echo T_("REPEAT"); ?>:</label><input id="password" name="password" type="password" />
				<label for="password"><?php echo "Repeater nova senha";?>:</label><input id="password" name="password1" type="password" />
				<input class="button" type="submit" value="<?php echo T_("SUBMIT"); ?>" />
				</fieldset>
			</form>
		</div>
		
<?php } else {  ?>

		<div>
			<form method="post" action="account-recover.php?take=1">
				<fieldset>
				<legend><?php echo "Digite o seu e-mail para obter nova senha!";?></legend>
				<label for="email"><?php echo T_("EMAIL_ADDRESS"); ?>:</label><input id="email" name="email" type="text" />
				<input class="button" type="submit" value="<?php echo T_("SUBMIT");?>" />
				</fieldset>
			</form>
			
		</div>
		</div>
<?php
}
?>

<div align="center" >
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" /></a><br />Este obra foi licenciado sob uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR">Licença Creative Commons</a>.
	</div> 
</body>
		
</html>