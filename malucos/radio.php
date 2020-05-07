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
	
?>
	
<body onLoad="setSurface();" >

<center>
<A HREF="#" class="Style3" onClick="window.open('pop_up_radio.php','player','toolbar=0, location=0, directories=0, status=0,  resizable=0, copyhistory=0, menuBar=0, width=590, height=490, left=0, top=0');return(false)"><b>[<?php echo T_("RADIO_CRAZYS_POP_UP"); ?>]</b></a>
<a href="<?php echo $site_config["SITEURL"]; ?>/pedirmusica.php"><b>[<?php echo T_("RADIO_CRAZYS_PEDIR"); ?>]</b></a>
<a href="<?php echo $site_config["SITEURL"]; ?>/verpedidos.php"><b>[<?php echo T_("RADIO_CRAZYS_VER"); ?>]</b></a>
</center>

<div id="tour_player_ecout" ></div>
 <CENTER> <img title="Rádio Crazys"  src="/images3/BrowserPreview.png" alt="radio."></CENTER>

<div id="emission_en_cours">




		<span>

    

				<CENTER><iframe src="http://www.malucos-share.org/player/index.php" 
marginwidth="0" marginheight="0" width="358" frameborder="0" 
height="240" scrolling="No"></iframe></CENTER></div>
	<CENTER><a href="http://198.143.132.154:7110/listen.pls"><img src="http://painel.streamjei.com.br/img-icone-player-winamp.png" width="32" height="32" title="Ouvir no Winamp" /></a>
<a href='http://suaradio.taaqui.org/player/8108/mediaplayer'><img src='http://suaradio.taaqui.org/admin/img/img-player-mediaplayer.gif' title='' border='0' height='25' width='25'></a>
<a href='http://suaradio.taaqui.org/player/8108/realplayer'><img src='http://suaradio.taaqui.org/admin/img/img-player-realplayer.gif' title='' border='0' height='25' width='25'></a>
<a href="http://suaradio.taaqui.org/ios/8108.m3u"><img src="http://suaradio.taaqui.org/celulares/iphone-ipod-ipad-tablet.png" width="25" height="25" /></a>
<a href="rtsp://198.15.120.190:1935/shoutcast/mob8108.stream"><img src="http://suaradio.taaqui.org/celulares/android.png" width="25" height="25" /></a>
<a href="rtsp://198.15.120.190:1935/shoutcast/mob8108.stream"><img src="http://suaradio.taaqui.org/celulares/blackberry.png" width="25" height="25" /></a>
<a href="http://198.15.120.190:1935/shoutcast/mob8108.stream/playlist.m3u8"><img src="http://suaradio.taaqui.org/celulares/iphone-ipod-ipad-tablet.png" width="25" height="25" /></a>
<a href="http://198.15.120.190:1935/shoutcast/mob8108.stream/Manifest"><img src="http://suaradio.taaqui.org/celulares/phone-silverlight.png" width="25" height="25" /></a>
	</CENTER>
	
	


	
	
	

    <?
	



	
	end_framec();
	
stdfoot();
?>