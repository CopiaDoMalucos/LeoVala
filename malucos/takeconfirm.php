<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();
$id = 0 + $_GET["id"];

mysql_query("UPDATE users SET status = 'confirmed' WHERE id IN (" . implode(", ", $_POST[conusr]) . ") AND status='pending'");
mysql_query("UPDATE invites SET confirmed='yes' WHERE inviteid IN (" . implode(", ", $_POST[conusr]) . ") AND confirmed='no'"); 

$r = @mysql_query("SELECT * FROM users WHERE id IN (" . implode(", ", $_POST[conusr]) . ")");
$user = mysql_fetch_array($r);

$email = $user["email"];

$body = <<<EOD
 
Bem vindo ao Malucos-Share sua conta foi confirmada pelo seu padrinho, agora você já pode acessar o site.

Bons Downs e Ups^^



$site_config[SITEURL]/account-login.php

Malucos-share.org

EOD;

				sendmail($email, "$site_config[SITENAME] Convite", $body, "para: $site_config[SITEEMAIL]", "-f$site_config[SITEEMAIL]");
				$mailsent = 1;
header("Refresh: 0; url=convites.php?id=$id");
?>