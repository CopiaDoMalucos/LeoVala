<?php

if ($site_config['NEWSON']){ //check news is turned on first   
	begin_block(T_("LATEST_NEWS"));

	$expire = 900; // time in seconds
if (($latestuploadsrecords = $TTCache->Get("latestuploadsblock")) === false) {
		$latestuploadsquery = SQL_Query_exec("SELECT id, name, added, image1, image2, seeders, leechers FROM torrents WHERE banned = 'no'  AND torrents.recommended='yes' ORDER BY rand() DESC LIMIT 8");

		$latestuploadsrecords = array();
		while ($latestuploadsrecord = mysql_fetch_assoc($latestuploadsquery))
			$latestuploadsrecords[] = $latestuploadsrecord;
		$TTCache->Set("latestuploadsblock", $latestuploadsrecords);
	}
print("<table width=100% border=0 valign=top></CENTER><DIV><tr><CENTER>");
	if ($latestuploadsrecords) {
		foreach ($latestuploadsrecords as $row) { 
		
			$char1 = 18; //cut length 
			$smallname = htmlspecialchars(CutName($row["name"], $char1));
			$where = "WHERE banned = 'no'  AND torrents.id='$row[id]'";
			$res = SQL_Query_exec("SELECT torrents.id, torrents.screens1, torrents.name, torrents.image2, torrents.added, torrents.seeders, torrents.leechers, categories.name AS cat_name FROM torrents LEFT JOIN categories ON torrents.category = categories.id $where  ORDER BY rand() DESC LIMIT 8 ");
$row12 = mysql_fetch_array($res);
			$img1 = "</CENTER><a href='{$site_config[SITEURL]}/torrents-details.php?id=$row12[id]'><img class='expando' border='0'src='{$row12[screens1]}' alt=\"$altname / $cat\" height=150 width=120></a><br><img src='images/seed.png' width='13' height='16' border='0' title='Seeders'> <font color='#009900'> &nbsp;" . $row12["seeders"] . "|&nbsp;<img src='images/down.gif' width='13' height='16' border='0' title='Leechers'> <font color='#FF0000'> &nbsp;" . $row12["leechers"] . "</b><br><a href='torrents-details.php?id=$row[id]' title='".htmlspecialchars($row["name"])."'>$smallname</a><br />\n";

	?>
	<style type="text/css">

	#marqueecontainer{
	position: relative;
	/*width: 200px; marquee width */
	height: 200px; /*marquee height */
	background-color: white;
	overflow: hidden;
	/*border: 3px solid orange;*/
	padding: 2px;
	padding-left: 4px;
	}

	</style>

	<script type="text/javascript">

	/***********************************************
	* Cross browser Marquee II- ? Dynamic Drive (www.dynamicdrive.com)
	* This notice MUST stay intact for legal use
	* Visit http://www.dynamicdrive.com/ for this script and 100s more.
	***********************************************/

	var delayb4scroll=2000 //Specify initial delay before marquee starts to scroll on page (2000=2 seconds)
	var marqueespeed=1 //Specify marquee scroll speed (larger is faster 1-10)
	var pauseit=1 //Pause marquee onMousever (0=no. 1=yes)?

	////NO NEED TO EDIT BELOW THIS LINE////////////

	var copyspeed=marqueespeed
	var pausespeed=(pauseit==0)? copyspeed: 0
	var actualheight=''

	function scrollmarquee(){
	if (parseInt(cross_marquee.style.top)>(actualheight*(-1)+8))
	cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
	else
	cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
	}

	function initializemarquee(){
	cross_marquee=document.getElementById("vmarquee")
	cross_marquee.style.top=0
	marqueeheight=document.getElementById("marqueecontainer").offsetHeight
	actualheight=cross_marquee.offsetHeight
	if (window.opera || navigator.userAgent.indexOf("Netscape/7")!=-1){ //if Opera or Netscape 7x, add scrollbars to scroll and exit
	cross_marquee.style.height=marqueeheight+"px"
	cross_marquee.style.overflow="scroll"
	return
	}
	setTimeout('lefttime=setInterval("scrollmarquee()",30)', delayb4scroll)
	}

<?php if (mysql_num_rows($res) > 3) {?>
	if (window.addEventListener)
	window.addEventListener("load", initializemarquee, false)
	else if (window.attachEvent)
	window.attachEvent("onload", initializemarquee)
	else if (document.getElementById)
	window.onload=initializemarquee
<?php } ?>

	</script>

	<div id="marqueecontainer" onmouseover="copyspeed=pausespeed" onmouseout="copyspeed=marqueespeed" style="background-color: transparent;">
	<div id="vmarquee" style="position: absolute; width: 100%; background-color: transparent;">

	<!--YOUR SCROLL CONTENT HERE-->
	<?php

	print("<TR><td align=center>" .$img1. "</td>");
		}
	} else {
		print("<center>".T_("NOTHING_FOUND")."</center>\n");
	}

	?>
	</div>
	</div>
	<?php

	end_block();
}//end newson check
?>