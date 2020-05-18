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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo "".CON_REDIR."";?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="login/theme/confirm.css">
<script type="text/javascript">
window.onload=function() {
function countdown() {
if ( typeof countdown.counter == 'undefined' ) {
    countdown.counter = 15; // initial count
    }
if(countdown.counter > 0) {
	document.getElementById('count').innerHTML = countdown.counter--;
    setTimeout(countdown, 1000);
    }
else {
    location.href = 'account-login.php';
    }
}
countdown();
};
</script>
</head>
<body>

<div id="countdown"><?php echo "".CON_REDIR."";?> <b id="count"></b> <?php echo "".CON_SECS."";?></div>

<?php

$type = $_GET["type"];
$email = $_GET["email"];

if (!$type)
	die;


if ($type =="noconf"){//email conf is disabled?

	print("<div class='confirmb'><div class='success-box'>".PLEASE_NOW_LOGIN_REST."</div></div>");

	die();
}

if ($type == "signup" && validemail($email)) {
		if (!$site_config["ACONFIRM"]) {
			print("<div class='confirmb'><div class='success-box'>".EMAIL_CHANGE_SEND."". " (" . htmlspecialchars($email) . "). " ."".ACCOUNT_CONFIRM_SENT_TO_ADDY_REST."". "</div></div>");
		} else {
			print("<div class='confirmb'><div class='success-box'>".EMAIL_CHANGE_SEND."". " (" . htmlspecialchars($email) . "). " ."".ACCOUNT_CONFIRM_SENT_TO_ADDY_ADMIN."". "</div></div>");
		}
}
elseif ($type == "confirmed") {
	print("<div class='confirmb'><div class='success-box'>".ACCOUNT_ALREADY_CONFIRMED."</div></div>");
}

//invite code
elseif ($type == "invite" && $_GET["email"]) {
		Print("<div class='confirmb'><div class='success-box'>"."".INVITE_SUCCESSFUL.""."<br /><br />"."".A_CONFIRMATION_EMAIL_HAS_BEEN_SENT.""." (" . htmlspecialchars($email) . "). "."".THEY_NEED_TO_READ_AND_RESPOND_TO_THIS_EMAIL.""."</div></div>");
}//end invite code

//invite code
elseif ($type == "invite") {
		Print("<div class='confirmb'><div class='success-box'>"."".CADASTROCONVITE.""."<br /><br />"."</div></div>");
}//end invite code


elseif ($type == "confirm") {
	if (isset($CURUSER)) {
		print("<div class='confirmb'><div class='success-box'>".ACCOUNT_ACTIVATED." <a href='". $site_config["SITEURL"] ."/index.php'> ".ACCOUNT_ACTIVATED_REST."</div></div>");
		print("<div class='confirmb'><div class='success-box'>".ACCOUNT_BEFOR_USING."  ".$site_config["SITENAME"]." ".ACCOUNT_BEFOR_USING_REST."</div></div>");
	}
	else {
		print("<div class='confirmb'><div class='success-box'>".ACCOUNT_ACTIVATED."</div></div>");
	}
}
else
	die();

?>
</body>