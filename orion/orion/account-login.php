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
dbconn();
session_start();

$registered = number_format(get_row_count("users"));
$liko = $site_config['maxusers'] - $registered;
$maxreg = $site_config['maxusers']

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-78936817-1', 'auto');
  ga('send', 'pageview');

</script>
<title><?php echo $site_config['SITENAME']?> <?php echo "".LOGTIT."";?></title>
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
<div class="uinfoimg"><a href="account-signup.php"><img src="login/theme/images/add.png" title="<?php echo "Cadastro";?>"></a></div> 
<div class="uinfotext"><b><?php echo "Olá,";?> <font color='green'><?php echo "Seja Bem-Vindo ao BRShares"; ?></font> </b></div>
</div>


<div id="login">
	<h2 class="head-alt"><?php echo "Formulário de login";?></h2>
	<ul class="tabs">

	</ul>
<div class="loglang">

</div>
	
	<div class="panes">
		<div>

<?php 



if ($_POST["username"] && $_POST["password"]) {
	$password = passhash($_POST["password"]);

	if (!empty($_POST["username"]) && !empty($_POST["password"])) {
		$res = SQL_Query_exec("SELECT id, password, secret, status, enabled FROM users WHERE username = " . sqlesc($_POST["username"]) . "");
		$row = mysql_fetch_array($res);


                if (!$row)
                        $message = T_("USERNAME_INCORRECT");	
		elseif ($row["status"] == "pending")
				$message = T_("ACCOUNT_PENDING");
		elseif ($row["password"] != $password)
			$message = T_("PASSWORD_INCORRECT");
		elseif ($row["enabled"] == "no")
		$message = T_("ACCOUNT_DISABLED");
	} else
			$message = T_("NO_EMPTY_FIELDS");
   unset($_SESSION['qaptcha_key']);
	if (!$message){
	 logincookie($row["id"], $row["password"], $row["secret"], $_POST["remember"]);


		if (!empty($_POST["returnto"])) {
			header("Refresh: 0; url=" . $_POST["returnto"]);
			die();
		}
		else {
			header("Refresh: 0; url=index.php");
			die();
		}
	}else{
		show_error2("".ACCES_DIENED."", $message, 1);
	}
}

logoutcookie();

 
 //if ($nowarn)
      //show_succes2("Error", $nowarn, 0);
      
?>

			<form method="post" action="account-login.php">
				<fieldset>
				<legend><?php echo T_("COOKIES");?></legend>
				<label for="username"><?php echo T_("USERNAME"); ?>:</label><input id="username" type="text" name="username" />
				<label for="password">Senha: <a href="account-recover.php">RECUPERAR CONTA</a></label><input id="password" type="password" name="password" /><br />
				<tr><td align="center"><b>Permanecer Logado?:</b> <input type="checkbox"  name="remember" value="yes" /></td></tr>
				<br>
		
				</fieldset>
						<center><input class="button" type="submit" value="<?php echo T_("LOGIN"); ?>" /></center>
			</form>
		</div>
		<div>
	
		</div>
	</div>
</div>


<script type="text/javascript" src="jquery/jquery.js"></script>
<script type="text/javascript" src="jquery/jquery-ui.js"></script>
<script type="text/javascript" src="jquery/jquery.ui.touch.js"></script>


<?php
//if (!empty($_REQUEST["returnto"]))
	//print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_REQUEST["returnto"]) . "\" />\n");
?>
<div align="center" >
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" /></a><br />Este obra foi licenciado sob uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR">Licença Creative Commons</a>.
	</div>
</body>
</html>