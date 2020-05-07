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
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-35280991-1', 'auto');
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
<div class="uinfotext"><b><?php echo "Olá,";?> <font color='green'><?php echo "Seja Bem-vindo Malucos"; ?></font> </b></div>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>Sign in to continue to gmail style - Bootsnipp.com</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
    padding-top: 5px;
}
.form-signin .form-signin-heading, .form-signin .checkbox
{
    margin-bottom: 10px;
}
.form-signin .checkbox
{
    font-weight: normal;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
}
.account-wall
{
    margin-top: 20px;
    padding: 40px 0px 20px 0px;
    background-color: #f7f7f7;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
.login-title
{
    color: #555;
    font-size: 18px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.profile-name {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin: 10px 0 0;
    height: 1em;
}
.profile-email {
    display: block;
    padding: 0 8px;
    font-size: 15px;
    color: #404040;
    line-height: 2;
    font-size: 14px;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.need-help
{
    display: block;
    margin-top: 10px;
}
.new-account
{
    display: block;
    margin-top: 10px;
}
    </style>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Sign in to continue to Gmail</h1>
            <div class="account-wall">
                <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
                <p class="profile-name">Bhaumik Patel</p>
                <span class="profile-email">example@gmail.com</span>
                <form class="form-signin">
                <input type="password" class="form-control" placeholder="Password" required autofocus>
                <button class="btn btn-lg btn-primary btn-block" type="submit">
                    Sign in</button>
                <a href="#" class="need-help">Need help? </a><span class="clearfix"></span>
                </form>
            </div>
            <a href="#" class="text-center new-account">Sign in with a different account</a>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
</body>
</html>

<?php
//if (!empty($_REQUEST["returnto"]))
	//print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_REQUEST["returnto"]) . "\" />\n");
?>
<div align="center" >
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" /></a><br />Este obra foi licenciado sob uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/deed.pt_BR">Licença Creative Commons</a>.
	</div>
</body>
</html>