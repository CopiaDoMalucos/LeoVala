<?php
//-----------------------------------------------------------------------//
//-----------------------------------------------------------------------//
//-----------------------------THEME BY:---------------------------------//
//-----------------------------------------------------------------------//
//--------------------------Ralphie & Nikkbu-----------------------------//
//-----------------------------------------------------------------------//
//-----------------------------------------------------------------------//
$GLOBALS['tstart'] = array_sum(explode(" ", microtime()));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?= $site_config["CHARSET"]; ?>">
<link rel="stylesheet" type="text/css" href="themes/Dark-Vista/theme.css">
<script type="text/javascript" src="<?= $site_config["SITEURL"]; ?>/backend/java_klappe.js"></script>
<style type="text/css">
<!--
img {
	behavior: url(themes/NB/images/iepngfix.htc);
	border: 0px;
}
-->
</style>
</head>

<BODY id="NB-Body">
<!-- Theme By: Ralphie & Nikkbu -->
<div id="container">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="18" height="112"><img src="themes/Dark-Vista/images/head-l.png" width="18" height="112" /></td>
<td height="112" align="center" background="themes/Dark-Vista/images/head-bg.png"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" height="112" align="right" valign="top" id="logo"><div id="infobar">
<!-- START INFOBAR CODE -->
<?
if (!$CURUSER){
	echo "[<a href=\"account-login.php\">". LOGIN . "</a>]<B> or </B>[<a href=\"account-signup.php\">" . SIGNUP . "</a>]&nbsp;&nbsp;";
}else{
	print ("".LOGGEDINAS.": ".$CURUSER["username"].""); 
	echo " <a href=\"account-logout.php\">[".LOGOUT."]</a> - ";
	if ($CURUSER["control_panel"]=="yes") {
		print("<a href=admincp.php>[" . STAFFCP . "]</a> - ");
	}

	//check for new pm's
	$res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " and unread='yes' AND location IN ('in','both')") or print(mysql_error());
	$arr = mysql_fetch_row($res);
	$unreadmail = $arr[0];
	if ($unreadmail){
		print("<font color=#FF0000><B>[New PM] (<a href=mailbox.php?inbox>$unreadmail</a>)</b></a></font>&nbsp;&nbsp;");
	}else{
		print("<a href=mailbox.php>[My Messages]</a>&nbsp;&nbsp;");
	}
	//end check for pm's
}
?>
<!-- END INFOBAR CODE -->
</div></td>
</tr>
</table></td>
<td width="18" height="112"><img src="themes/Dark-Vista/images/head-r.png" width="18" height="112" /></td>
</tr>
</table><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="14" height="30"><img src="themes/Dark-Vista/images/nav-l.png" width="14" height="30" /></td>
<td height="30" align="left" background="themes/Dark-Vista/images/nav-bg.png"><table border="0" align="left" cellpadding="0" cellspacing="0">
<tr>
<td height="30" background="themes/Dark-Vista/images/subnav-bg.png">
<!-- START SUBNAV CODE -->
<div id="nav">
    <ul>
    <li><a href="index.php"><span>Home</span></a></li>
    <li><a href="torrents-upload.php"><span>Upload Torrents</span></a></li>
    <li><a href="torrents.php"><span>Browse Torrents</span></a></li>
    <li><a href="torrents-today.php"><span>Todays Torrents</span></a></li>
    <li><a  href="torrents-search.php"><span>Search</span></a></li>
    <li><a  href="faq.php"><span>FAQ</span></a></li>
    </ul>
</div>
<!-- END SUBNAV CODE -->
</td>
<td width="51" height="30"><img src="themes/Dark-Vista/images/subnav-r.png" width="51" height="30" /></td>
</tr>
</table></td>
<td width="14" height="30"><img src="themes/Dark-Vista/images/nav-r.png" width="14" height="30" /></td>
</tr>
</table><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="10" background="themes/Dark-Vista/images/body-ml.png"><img src="themes/Dark-Vista/images/blank.gif" width="10" height="12" /></td>
<td width="100%" valign="top" background="themes/Dark-Vista/images/body-mm.png"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tboby>
<tr>
<td valign="top">
<!-- START CONTENT CODE -->
<TABLE cellSpacing="8" cellPadding="0" width="100%" border="0" >
<TBODY>
<TR>

<?if ($site_config["LEFTNAV"]){?>
<TD vAlign="top" width="180">
<?leftblocks();?>
</TD>
<? } //LEFTNAV ON/OFF END?>

<TD vAlign="top"><!-- MAIN CENTER CONTENT START -->

<?
if ($site_config["MIDDLENAV"]){
    middleblocks();
} //MIDDLENAV ON/OFF END
?>
