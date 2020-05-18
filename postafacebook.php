<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();





session_start();

require_once 'malucos/src/Google_Client.php';
require_once 'malucos/src/contrib/Google_BloggerService.php'; 

$scriptUri = 'http://www.brshares.com/postafacebook.php';
$client = new Google_Client();
$client->setAccessType('online'); // default: offline
$client->setApplicationName('malucos'); //name of the application
$client->setClientId('993621654491-lh9jbbr3omlhot87m2068llm9gnn8ud4.apps.googleusercontent.com'); //insert your client id
$client->setClientSecret('8F4dkuyzs87BMYawi-We66fg'); //insert your client secret
$client->setRedirectUri($scriptUri); //redirects to same url
$client->setDeveloperKey('AIzaSyDHnA5eMpbvs66QjZG3d2jtmhFMKbeM-Ik'); // API key (at bottom of page)
$client->setScopes(array('https://www.googleapis.com/auth/blogger')); //since we are going to use blogger services

$blogger = new Google_BloggerService($client);

if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
 die('Logged out.');
}

if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
}

if (isset($_SESSION['token'])) { // extract token from session and configure client
    $token = $_SESSION['token'];
    $client->setAccessToken($token);
}
 
if (!$client->getAccessToken()) { // auth call to google
    $authUrl = $client->createAuthUrl();
    header("Location: ".$authUrl);
    die;
}
//you can get the data about the blog by getByUrl
//creates a post object
$semmod = mysql_query("SELECT * FROM torrents WHERE adota = '1'") or sqlerr();	

$ressemmod = mysql_fetch_array($semmod);

$torrentname = $ressemmod["name"];
$torrentscreem = $ressemmod["screens1"];

$mypost = new Google_Post();
$mypost->setTitle("<CENTER>".$torrentname."</CENTER>");
$mypost->setContent ("<CENTER><img height='400' src='".$torrentscreem."' width='270'/><BR></BR>".$ressemmod["filmesinopse"]."<BR></BR>
 <a href='http://www.brshares.com/account-signup.php?convite=promocional' rel='nofollow'>Baixe aqui</a></CENTER>");


$data = $blogger->posts->insert('6659710878567552267', $mypost); //post id needs here - put your blogger blog id
mysql_query("UPDATE torrents SET adota='2' WHERE id = ".$ressemmod["id"]."") or die(mysql_error());



  
 




?>