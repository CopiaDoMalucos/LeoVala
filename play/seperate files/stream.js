// Mike Gieson
// www.wimpyplayer.com
// © 2006 Plaion Inc.
// v1.0


// CONFIGS:

wimpySwf = "wimpy_button.swf";
server = "stream.php";
icecastInterval = "100";
width = "70";
height = "70";
configs = "theFile="+server+"&icecast="+icecastInterval+"&buttonStyle=circle&tptBkgd=yes";
objectID = "wimpybutton";


function writeWimpyButton(){
	outputHMTL = "";
	outputHMTL += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
	outputHMTL += 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,47,0" ';
	outputHMTL += 'width="'+width+'" ';
	outputHMTL += 'height="'+height+'" ';
	outputHMTL += 'id="'+objectID+'">';
	outputHMTL += '<param name="movie" value="'+wimpySwf+'" />';
	outputHMTL += '<param name="loop" value="false" />';
	outputHMTL += '<param name="menu" value="false" />';
	outputHMTL += '<param name="quality" value="high" />';
	outputHMTL += '<param name="bgcolor" value="#FFFFFF" />';
	outputHMTL += '<param name="flashvars" value="'+configs+'" />';
	outputHMTL += '<embed src="'+wimpySwf+'" ';
	outputHMTL += 'flashvars="'+configs+'" ';
	outputHMTL += 'width="'+width+'" ';
	outputHMTL += 'height="'+height+'" ';
	outputHMTL += 'bgcolor="#FFFFFF" ';
	outputHMTL += 'loop="false" ';
	outputHMTL += 'menu="false" ';
	outputHMTL += 'quality="high" ';
	outputHMTL += 'name="'+objectID+'" ';
	outputHMTL += 'align="middle" ';
	outputHMTL += 'allowScriptAccess="sameDomain" ';
	outputHMTL += 'type="application/x-shockwave-flash" ';
	outputHMTL += 'pluginspage="http://www.macromedia.com/go/getflashplayer" />';
	outputHMTL += '</object>';
	document.write(outputHMTL);
	//document.write('<br><textarea name="textarea" cols="40" rows="10">'+outputHMTL+'</textarea><br>');
}
