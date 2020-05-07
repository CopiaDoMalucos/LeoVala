<?
include "getinfo.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Radio Player" />
<meta name="keywords" content="Radio Player" />
<meta name="author" content="Radio Player originaly made by: Niklas Pull - http://pull.zapto.org" />
<title>Radio Player</title>
<style type="text/css">
<!--
body {margin:0; padding: 0; font-family: Verdana; font-size: 10px;}
a{text-decoration:none; background-color:inherit; color:#26c;}
a:hover{text-decoration:underline;}
#main {width: 350px; height: 100px; position: relative; left: 0px; top: 0px; margin:0; padding:0; background-image: url(img/player.png); background-repeat: no-repeat; background-attachment: fixed; background-color: #eeeeee}
#display {width: 315px; height: 39px; position: relative; left: 18px; top: 18px;}
#display_low {width: 310px; height: 21px; position: relative; left: 3px; top: 3px;}
#bottom {width: 350px; height: 39px; position: relative; left: 0px; top: 22px;}
#songtitle {width: 310px; height: 12px; position: relative; left: 3px; top: 3px;}
#station {width: 150px; position: relative; left: 23px; top: 30px;}
#bitrate {width: 55px; position: relative; left: 185px; top: 18px;}
#equalizer {width: 55px; position: relative; left: 280px; top: 0px;}
#icon {width: 55px; position: relative; left: 300px; top: 10px;}
#iframe {width: 0px; position: relative; left: 0px; top: 0px;}
.table {font-family: Verdana; font-size: 10px;}
-->
</style>
<script>
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

var http = createRequestObject();

function sndReq() {
    http.open('get', 'cur_song.php');
    http.onreadystatechange = handleResponse;
    http.send(null);
    setTimeout("sndReq()", 2000);
}

function handleResponse() {
    if(http.readyState == 4){
        var response = http.responseText;
        if (response != responseold || responsecheck != 1) {
            var responsecheck = 1;
            document.getElementById("messages").innerHTML = http.responseText;
            var responseold = response;
        }
    }
}


</script>
</head>
<body onLoad="javascript:sndReq();">

<div id="main">

 <div id="display">
  <div id="songtitle">
   <div id="messages">
   </div>
  </div>
  <div id="display_low">
   <table border="0" cellpadding="0" cellspacing="0" width="300" height="21">
    <tr class="table">
     <td align="left" valign="bottom">
      <i>Station: <b><?=$servertitle;?></b></i>
     </td>
     <td align="center" valign="bottom">
      <?=$bitrate;?> kbps
     </td>
     <td align="right" valign="bottom">
      <img src="img/equalizer_<?=$status;?>.gif" height="21">
     </td>
    </tr>
   </table>
  </div>
 </div>

 <div id="bottom">
  <table border="0" cellpadding="0" cellspacing="0" width="350" height="39">
   <tr class="table">
    <td width="193">
     <img src="img/player2.png" width="193" height="39" usemap="#play" border="0">
    </td>
    <td width="157" height="39" background="img/player3_<?=$z;?>.png">
     Switch to: <a href="radioplayer.php?z=<?=$mode;?>"><img src="img/small_<?=$mode;?>.png" border="0"></a>
    </td>
   </tr>
  </table>
 </div>

</div>

<div id="iframe">
 <iframe src="<?=$status;?>_<?=$z;?>.php" name="stream" width="0" height="0"></iframe>
</div>

<map name="play">
<area shape="rect" alt="Stop" coords="55,0,78,27" href="radioplayer.php?z=<?=$z;?>" target="_self">
<area shape="rect" alt="Play" coords="21,0,47,28" href="radioplayer.php?z=<?=$z;?>&status=play" target="_self">
</map>

</body>
</html>
