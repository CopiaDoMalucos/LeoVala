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
loggedinonly();

$musica = $_POST['musica'];
$artista = $_POST['artista'];
$ouvinte = $CURUSER['username'];
$page = (string) $_GET["page"];
$action = (string) $_GET["action"];

stdhead("Pedir Uma Música");
	begin_framec("Rádio BR");
	
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

    

				<CENTER><iframe src="http://www.brshares.com/player/index.php" 
marginwidth="0" marginheight="0" width="358" frameborder="0" 
height="240" scrolling="No"></iframe></CENTER></div>
	<CENTER>
<a href="http://streaming.autodjhost.com.br/player/14448/winamp.pls"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-winamp.png" width="32" height="32" title="Ouvir no Winamp" /></a>
<a href="http://streaming.autodjhost.com.br/player/14448/mediaplayer.asx"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-mediaplayer.png" width="32" height="32" title="Ouvir no MediaPlayer" /></a>
<a href="http://streaming.autodjhost.com.br/player/14448/realplayer.rm"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-realplayer.png" width="32" height="32" title="Ouvir no RealPlayer" /></a>
<a href="http://streaming.autodjhost.com.br/player/14448/vlc.m3u"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-vlc.png" width="32" height="32" title="Ouvir no RealPlayer" /></a>
<a href="http://streaming.autodjhost.com.br/player/14448/quicktime.qtl"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-qt.png" width="32" height="32" title="Ouvir no RealPlayer" /></a>
<a href="http://streaming.autodjhost.com.br/player/14448/itunes.pls"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-itunes.png" width="32" height="32" title="Ouvir no Winamp" /></a>
<a href="http://streaming.autodjhost.com.br/player/14448/iphone.m3u"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-iphone.png" width="32" height="32" title="Ouvir no iphone" /></a>
<a href="rtsp://rtmp2.srvstm.com/14448/14448.stream"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-android.png" width="32" height="32" title="Ouvir no Android" /></a>
<a href="rtsp://rtmp2.srvstm.com/14448/14448.stream"><img src="http://cdn.srvstm.com/img/icones/img-icone-player-blackberry.png" width="32" height="32" title="Ouvir no BlackBerry" /></a>
	</CENTER>
	
	


	
	
	

    <?
	



	
	end_framec();
	
stdfoot();
?>