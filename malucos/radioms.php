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
loggedinonly();

$musica = $_POST['musica'];
$artista = $_POST['artista'];
$ouvinte = $CURUSER['username'];
$page = (string) $_GET["page"];
$action = (string) $_GET["action"];

stdhead("Pedir Uma Música");
	begin_framec("Rádio MS");
	
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
("P2BS Live Feed");
$showradioinfo="on";
include("myradio.php");

}
	



	
	end_framec();
	
stdfoot();
?>