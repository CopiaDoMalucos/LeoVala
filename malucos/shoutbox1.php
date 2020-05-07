<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################ 

require_once("backend/functions1.php");

dbconn();
loggedinonly();


$dossier = $CURUSER['bbcode'];
function quickbbshout($dossier){


echo "<td float:left; width=22><a href=\"javascript:bbshout('[b]', '[/b]')\"><img src=images/bbcode/$dossier/bbcode_bold1.gif border=0 alt='Bold' id=button1></a></td>";

echo "<td float:left; width=22><a href=\"javascript:bbshout('[i]', '[/i]')\"><img src=images/bbcode/$dossier/bbcode_italic.gif border=0 alt='Italic'></a></td>";

echo "<td float:left; width=22><a href=\"javascript:bbshout('[u]', '[/u]')\"><img src=images/bbcode/$dossier/bbcode_underline.gif border=0 alt='Underline'></a></td>";

echo "<td float:left; width=22><a href=\"javascript:clink('[url]', '[/url]')\"><img src=images/bbcode/$dossier/bbcode_url.gif border=0 alt='Url'></a></td>";

echo "</tr></table></left>";

}


	


//DELETE MESSAGES
if (isset($_GET['del'])){

	if (is_numeric($_GET['del'])){
		$query = "SELECT * FROM shoutbox WHERE msgid=".$_GET['del'] ;
		$result = SQL_Query_exec($query);
	}else{
		echo "invalid msg id STOP TRYING TO INJECT SQL";
		exit;
	}

	$row = mysql_fetch_row($result);
	
	$row123456 = mysql_fetch_assoc(SQL_Query_exec("SELECT * FROM `shoutbox` WHERE msgid=".$_GET['del'].""));	
	
	if ($CURUSER && $CURUSER["class"] >= 79) {
	    write_logstaff("Shoutbox","#FF0000","A mensagen do [url=http://www.malucos-share.org/account-details.php?id=".$row123456['userid']."]".$row123456['user']."[/url] foi apagada por [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] \n");
		$query = "DELETE FROM shoutbox WHERE msgid=".$_GET['del'] ;
		SQL_Query_exec($query);	
	}
}

//INSERT MESSAGE
if (!empty($_POST['message']) && $CURUSER) {	

	$_POST['message'] = sqlesc($_POST['message']);


	$query = "SELECT COUNT(*) FROM shoutbox WHERE message=".$_POST['message']." AND user='".$CURUSER['username']."' AND UNIX_TIMESTAMP('".get_date_time()."')-UNIX_TIMESTAMP(date) < 30";
	$result = SQL_Query_exec($query);
	$row = mysql_fetch_row($result);
								    ?>

		<body onLoad="document.shoutboxform.message.focus()">

<?php
	if ($row[0] == '0') {
		$query = "INSERT INTO shoutbox (msgid, user, message, date, userid) VALUES (NULL, '".$CURUSER['username']."', ".$_POST['message'].", '".get_date_time()."', '".$CURUSER['id']."')";
		SQL_Query_exec($query);
	}
}

//GET CURRENT USERS THEME AND LANGUAGE
if ($CURUSER){
	$ss_a = @mysql_fetch_assoc(@SQL_Query_exec("select uri from stylesheets where id=" . $CURUSER["stylesheet"]));
	if ($ss_a)
		$THEME = $ss_a["uri"];
}else{//not logged in so get default theme/language
	$ss_a = mysql_fetch_assoc(SQL_Query_exec("select uri from stylesheets where id='" . $site_config['default_theme'] . "'"));
	if ($ss_a)
		$THEME = $ss_a["uri"];
}
//replace [url] tags with a proper link
$msg = preg_replace("/\[url\]([^[]+)\[\/url\]/i","<a href=\"\\1\" target=\"_blank\">\\1</a>",$msg);

if(!isset($_GET['history'])){ 
?>
<html>
<head>
<title><?php echo $site_config['SITENAME'] . T_("SHOUTBOX"); ?></title>
<?php /* If you do change the refresh interval, you should also change index.php printf(T_("SHOUTBOX_REFRESH"), 5) the 5 is in minutes */ ?>
<meta http-equiv="refresh" content="300" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL']?>/themes/<?php echo $THEME; ?>/theme.css" />
<script type="text/javascript" src="<?php echo $site_config['SITEURL']; ?>/backend/java_klappe.js"></script>

<script type="text/javascript">
<!--
function bbshout(repdeb, repfin) {
  var input = document.forms['shoutboxform'].elements['message'];
  input.focus();
  if(typeof document.selection != 'undefined') {
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = repdeb + insText + repfin;
    range = document.selection.createRange();
    if (insText.length == 0) {
      range.move('character', -repfin.length);
    } else {
      range.moveStart('character', repdeb.length + insText.length + repfin.length);
    }
    range.select();
  }
  else if(typeof input.selectionStart != 'undefined')
  {
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + repdeb + insText + repfin + input.value.substr(end);
    var pos;
    if (insText.length == 0) {
      pos = start + repdeb.length;
    } else {
      pos = start + repdeb.length + insText.length + repfin.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
  }
  else
  {
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)) {
      pos = prompt("Insertion à la position (0.." + input.value.length + "):", "0");
    }
    if(pos > input.value.length) {
      pos = input.value.length;
    }
    var insText = prompt("Veuillez entrer le texte à formater:");
    input.value = input.value.substr(0, pos) + repdeb + insText + repfin + input.value.substr(pos);
  }
}
function bbcolor() {
	var colorvalue = document.forms['shoutboxform'].elements['color'].value;
	bbshout("[color="+colorvalue+"]", "[/color]");
}
function bbfont() {
	var fontvalue = document.forms['shoutboxform'].elements['font'].value;
	bbshout("[font="+fontvalue+"]", "[/font]");
}
function bbsize() {
    var sizevalue = document.forms['shoutboxform'].elements['size'].value;
    bbshout("[size="+sizevalue+"]", "[/size]");
}
function bbimg() {
    var imgvalue = document.forms['shoutboxform'].elements['img'].value;
    bbshout("[img="+sizevalue+"]", "[/img]");
}
function bbrainbow() {
    var rainbow = document.forms['shoutboxform'].elements['rainbow'].value;
    bbshout("[rainbow="+rainbowvalue+"]", "[/rainbow");
}

	function clink()
	{
		var linkTitle;
		var linkAddr;
		
		linkAddr = prompt("<?php echo"Digite o link a ser inserido";?>","http://");
		if(linkAddr && linkAddr != "http://")
		linkTitle = prompt("<?php echo"".Please_enter_the_title."";?>", " ");
		
	  if(linkAddr && linkTitle)

					bbshout("[url="+linkAddr+"]",""+linkTitle+"[/url]");

	  
	}
      function code_to_html(t) {

	t=nl2khol(t)
// balise Center
	t=deblaie(/(\[\/center\])/g,t)
	t=remplace_tag(/\[center\](.+)\[\/center\]/g,'<center>$1</center>',t)  
	t=remblaie(t)		
// balise Gras
	t=deblaie(/(\[\/b\])/g,t)
	t=remplace_tag(/\[b\](.+)\[\/b\]/g,'<b>$1</b>',t)  
	t=remblaie(t)
// balise Italique
	t=deblaie(/(\[\/i\])/g,t)
	t=remplace_tag(/\[i\](.+)\[\/i\]/g,'<i>$1</i>',t)  
	t=remblaie(t)
// balise Underline
	t=deblaie(/(\[\/u\])/g,t)
	t=remplace_tag(/\[u\](.+)\[\/u\]/g,'<u>$1</u>',t)  
	t=remblaie(t)
// balise quote
	t=deblaie(/(\[\/quote\])/g,t)
	t=remplace_tag(/\[quote\](.+)\[\/quote\]/g,'<p class=sub><b>Citation:</b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$1</td></tr></table>',t)  
	t=remblaie(t)

// balise blink	
	t=deblaie(/(\[\/blink\])/g,t)
	t=remplace_tag(/\[blink\](.+)\[\/blink\]/g,'<blink>$1</blink>',t)  
	t=remblaie(t)
// balise df	
	t=deblaie(/(\[\/df\])/g,t)
	t=remplace_tag(/\[df\](.+)\[\/df\]/g,'<marquee>$1</marquee>',t)  
	t=remblaie(t)
// balise [audio]..[/audio]
	t=deblaie(/(\[\/audio\])/g,t)
	t=remplace_tag(/\[audio\]((www.|http:\/\/|https:\/\/)[^\s]+(\.mp3))\[\/audio\]/g,'<param name=movie value=$1/><embed width=470 height=310 src=$1></embed>',t)  
	t=remblaie(t)	
// balise [swf]..[/swf]
	t=deblaie(/(\[\/swf\])/g,t)
	t=remplace_tag(/\[swf\]((http|https):\/\/[^<>\s]+?)\[\/swf\]/g,'<embed autostart=false loop=false controller=true width=680 height=440 src=$1></embed>',t)  
	t=remblaie(t)
// balise [video]..[/video] pour youtube
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\][^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\[\/video\]/img,'<object width="680" height="440"><param name="movie" value="http://www.youtube.com/v/$1"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
	t=remblaie(t)
// balise [video=...] pour youtube
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video=[^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\]/img,'<object width="680" height="440"><param name="movie" value="http://www.youtube.com/v/$1"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
	t=remblaie(t)
// balise [video]..[/video] pour mp4
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\]((www.|http:\/\/|https:\/\/)[^\s]+(\.mp4))\[\/video\]/g,'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="680" height="440" id="player1" name="player1"><param name="movie" value="$1"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><embed  src="$1" name="player1"  width="680"  height="440" allowscriptaccess="always" allowfullscreen="true"></embed></object>',t)  
	t=remblaie(t)
// balise [video]..[/video] pour wmv
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\]((www.|http:\/\/|https:\/\/)[^\s]+(\.wmv))\[\/video\]/g,'<param name=filename value=$1/><embed width=680 height=440 src=$1></embed>',t)  
	t=remblaie(t)	
// balise [video]..[/video] pour dailymotion
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\][^\s'\"<>]*dailymotion.com\/video\/([^\s'\"<>]+)\[\/video\]/img,'<object width="680" height="440"><param name="movie" value="http://www.dailymotion.com/swf/$1"></param><embed src="http://www.dailymotion.com/swf/$1" type="application/x-shockwave-flash" width="680" height="440"></embed></object>',t)  
	t=remblaie(t)	
// balise [video]..[/video] pour google video
	t=deblaie(/(\[\/video\])/g,t)
	t=remplace_tag(/\[video\][^\s'\"<>]*video.google.com.*docid=(-?[0-9]+).*\[\/video\]/img,'<embed style="width:680px; height:440px;" id="VideoPlayback" align="middle" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=$1" allowScriptAccess="sameDomain" quality="best" bgcolor="#ffffff" scale="noScale" wmode="window" salign="TL"  FlashVars="playerMode=embedded"></embed>',t)  
	t=remblaie(t)	
// balise font	
	t=deblaie(/(\[\/font\])/g,t)
	t=remplace_tag(/\[font=(#[a-fA-F0-9]{6})\](.+)\[\/font\]/g,'<font face="$1">$2</font>',t)
	t=remblaie(t)
	t=deblaie(/(\[\/font\])/g,t)
    t=remplace_tag(/\[font=([a-zA-Z]+)\]((\s|.)+?)\[\/font\]/g,'<font face="$1">$2</font>',t)
    t=remblaie(t)
//balise fil 
	t=deblaie(/(\[\/fil\])/g,t)
	t=remplace_tag(/\[fil=([0-9]{1,3})\](http|https:\/\/[^\s'\"<>]+(\.(jpg|jpeg|gif|png)))\[\/fil\]/g,'<img src="$2"" width="$1">',t)
	t=remblaie(t)
	t=deblaie(/(\[\/color\])/g,t)
// balise code	
	t=deblaie(/(\[\/code\])/g,t)
	t=remplace_tag(/\[code\](.+)\[\/code\]/g,'<p class=sub><b>Code:</b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style="border: 1px black dotted">$1</td></tr></table>',t)  
	t=remblaie(t)
// balise Img
	t=deblaie(/(\[\/img\])/g,t)
	t=remplace_tag(/\[img\](.+)\[\/img\]/g,'<img src="$1" />',t)
	t=remblaie(t)
// balise URL	
	t=deblaie(/(\[\/url\])/g,t)
    t=remplace_tag(/\[url=([^\s<>]+)\](.+)\[\/url\]/g,'<a href="$1" target="_blank">$2</a>',t)
    t=remblaie(t)

    t=deblaie(/(\[\/url\])/g,t)
    t=remplace_tag(/\[url\]([^\s<>]+)\[\/url\]/g,'<a href="$1" target="_blank">$1</a>',t)
    t=remblaie(t)

    t=remplace_tag(/\[\/url\]/g,'</a>',t)
    t=remblaie(t)
// balise Couleur	
	t=deblaie(/(\[\/color\])/g,t)
	t=remplace_tag(/\[color=(#[a-fA-F0-9]{6})\](.+)\[\/color\]/g,'<font color="$1">$2</font>',t)
	t=remblaie(t)
	t=deblaie(/(\[\/color\])/g,t)
    t=remplace_tag(/\[color=([a-zA-Z]+)\]((\s|.)+?)\[\/color\]/g,'<font color="$1">$2</font>',t)
    t=remblaie(t)
// alignement
	t=deblaie(/(\[\/align\])/g,t)
    t=remplace_tag(/\[align=([a-zA-Z]+)\]((\s|.)+?)\[\/align\]/g,'<div style="text-align:$1">$2</div>',t)
    t=remblaie(t)
// balise size	
	t=deblaie(/(\[\/size\])/g,t)
	t=remplace_tag(/\[size=([+-]?[0-9])\](.+)\[\/size\]/g,'<font size="$1">$2</font>',t)
	t=remblaie(t)
	t=unkhol(t)
	t=nl2br(t)
// balise mail
	t=deblaie(/(\[\/mail\])/g,t)
	t=remplace_tag(/\[mail\](.+)\[\/mail\]/g,'<a href="mailto:$1" target="_blank">$1</a>',t)  
	t=remblaie(t)
//smilies
	t=remplace_tag(/:\)/g,'<img src="images/smilies/smile1.gif" alt="" />',t) 
	t=remplace_tag(/:smile:/g,'<img src="images/smilies/smile2.gif" alt="" />',t) 
	t=remplace_tag(/:-D/g,'<img src="images/smilies/grin.gif" alt="" />',t) 
	t=remplace_tag(/:w00t:/g,'<img src="images/smilies/w00t.gif" alt="" />',t) 
	
	t=remplace_tag(/:-P/g,'<img src="images/smilies/tongue.gif" alt="" />',t) 
	t=remplace_tag(/;-\)/g,'<img src="images/smilies/wink.gif" alt="" />',t) 
	t=remplace_tag(/:-\|/g,'<img src="images/smilies/noexpression.gif" alt="" />',t) 
	t=remplace_tag(/:-\//g,'<img src="images/smilies/confused.gif" alt="" />',t) 
	
	t=remplace_tag(/:-\(/g,'<img src="images/smilies/sad.gif" alt="" />',t)
	t=remplace_tag(/:baby:/g,'<img src="images/smilies/baby.gif" alt="" />',t) 
	t=remplace_tag(/:-O/g,'<img src="images/smilies/ohmy.gif" alt="" />',t) 
	t=remplace_tag(/\|-\)/g,'<img src="images/smilies/sleeping.gif" alt="" />',t) 
	
	t=remplace_tag(/8\)/g,'<img src="images/smilies/cool1.gif" alt="" />',t) 
	t=remplace_tag(/:unsure:/g,'<img src="images/smilies/unsure.gif" alt="" />',t) 
	t=remplace_tag(/:closedeyes:/g,'<img src="images/smilies/closedeyes.gif" alt="" />',t) 
	t=remplace_tag(/:cool:/g,'<img src="images/smilies/cool2.gif" alt="" />',t) 
	
	t=remplace_tag(/:thumbsup:/g,'<img src="images/smilies/thumbsup.gif" alt="" />',t) 
	t=remplace_tag(/:blush:/g,'<img src="images/smilies/blush.gif" alt="" />',t) 
	t=remplace_tag(/:yes:/g,'<img src="images/smilies/yes.gif" alt="" />',t) 
	t=remplace_tag(/:no:/g,'<img src="images/smilies/no.gif" alt="" />',t) 
	
	t=remplace_tag(/:love:/g,'<img src="images/smilies/love.gif" alt="" />',t) 
	t=remplace_tag(/:\?:/g,'<img src="images/smilies/question.gif" alt="" />',t) 
	t=remplace_tag(/:!:/g,'<img src="images/smilies/excl.gif" alt="" />',t) 
	t=remplace_tag(/:idea:/g,'<img src="images/smilies/idea.gif" alt="" />',t) 
	
	t=remplace_tag(/:arrow:/g,'<img src="images/smilies/arrow.gif" alt="" />',t) 
	t=remplace_tag(/:ras:/g,'<img src="images/smilies/ras.gif" alt="" />',t) 
	t=remplace_tag(/:hmm:/g,'<img src="images/smilies/hmm.gif" alt="" />',t) 
	t=remplace_tag(/:lol:/g,'<img src="images/smilies/laugh.gif" alt="" />',t) 
    
	t=remplace_tag(/:mario:/g,'<img src="images/smilies/mario.gif" alt="" />',t) 
	t=remplace_tag(/:rolleyes:/g,'<img src="images/smilies/rolleyes.gif" alt="" />',t) 
	t=remplace_tag(/:kiss:/g,'<img src="images/smilies/kiss.gif" alt="" />',t) 
	t=remplace_tag(/:shifty:/g,'<img src="images/smilies/shifty.gif" alt="" />',t) 
	
	t=remblaie(t)
	
	return t

	}

function deblaie(reg,t) {
	texte=new String(t);
	return texte.replace(reg,'$1\n');
}
function remblaie(t) {
	texte=new String(t);
	return texte.replace(/\n/g,'');
}
function remplace_tag(reg,rep,t) {
	texte=new String(t);
	return texte.replace(reg,rep);
}
function nl2br(t) {
	texte=new String(t);
	return texte.replace(/\n/g,'<br/>');
}
function nl2khol(t) {
	texte=new String(t);
	return texte.replace(/\n/g,ptag);
}
function unkhol(t) {
	texte=new String(t);
	return texte.replace(new RegExp(ptag,'g'),'\n');
}
     //-->
</script>


<script language="javascript">
function SmileIT(smile,form,text){
   document.forms[form].elements[text].value = document.forms[form].elements[text].value+" "+smile+" ";
   document.forms[form].elements[text].focus();
}
</script>


<script LANGUAGE="JavaScript"><!--
function mySubmit() {
   setTimeout('document.shbox.reset()',1);
}
function Smilies(Smilie)
{
document.shoutboxform.message.value+=Smilie+" ";
document.shoutboxform.message.focus();
}
//--></SCRIPT>









<script type='text/javascript'>
function ShoutResposta(smile,form,text){
   document.forms[form].elements[text].value = document.forms[form].elements[text].value+""+smile+" ";
   document.forms[form].elements[text].focus();
}
</SCRIPT>
</head>
<body class="shoutbox_body" onLoad="document.shoutboxform.message">
<?php
	echo '<div class="shoutbox_contain"><table border="0" style="width: 99%; table-layout:fixed">';
}else{
    
    if ($site_config["MEMBERSONLY"]) {
        loggedinonly();
    }
    
	stdhead();
		?>
<script type='text/javascript'>
<!--
function ShowSmilies() {
  var SmiliesWindow = window.open("<?php echo $site_config['SITEURL'] ;?>/mysmilies.php", "Smilies","width=300,height=600,resizable=yes,scrollbars=yes,toolbar=no,location=no,dir
ectories=no,status=no");
}
//-->
</SCRIPT>

<?php
	begin_framec(T_("SHOUTBOX_HISTORY"));
	echo '<div class="shoutbox_history">';

	$query = 'SELECT COUNT(*) FROM shoutbox';
	$result = SQL_Query_exec($query);
	$row = mysql_fetch_row($result);
	echo '<div align="center">Pages: ';
	$pages = round($row[0] / 1000) + 1;
	$i = 1;
	while ($pages > 0){
		echo "<a href='".$site_config['SITEURL']."/shoutbox.php?history=1&amp;page=".$i."'>[".$i."]</a>&nbsp;";
		$i++;
		$pages--;
	}

	echo '</div><br /><table border="0" style="width: 99%; table-layout:fixed">';
}

if (isset($_GET['history'])) {
	if (isset($_GET['page'])) {
		if($_GET['page'] > '1') {
			$lowerlimit = $_GET['page'] * 100 - 100;
			$upperlimit = $_GET['page'] * 100;
		}else{
			$lowerlimit = 0;
			$upperlimit = 100;
		}
	}else{
		$lowerlimit = 0;
		$upperlimit = 100;
	}	
	$query = 'SELECT shoutbox.message, shoutbox.userid, shoutbox.date, shoutbox.user, shoutbox.msgid, users.class, users.dj  FROM shoutbox  LEFT JOIN users ON shoutbox.userid = users.id ORDER BY msgid DESC LIMIT '.$lowerlimit.','.$upperlimit;
}else{
	$query = 'SELECT shoutbox.message, shoutbox.userid, shoutbox.date, shoutbox.user, shoutbox.msgid, users.class, users.dj  FROM shoutbox  LEFT JOIN users ON shoutbox.userid = users.id ORDER BY msgid DESC LIMIT 25';
}





$result = SQL_Query_exec($query);
$alt = false;




while ($row = mysql_fetch_assoc($result)) {




$string11=explode(":", $row["message"]);
$teste12 = "$string11[0]";



	if ($teste12 == $CURUSER["username"]) {
	
	echo '<tr class="msreplica">';
}
else{
	if ($alt){	
		echo '<tr class="shoutbox_noalt">';
		$alt = false;
	}else{
		echo '<tr class="shoutbox_alt">';
		$alt = true;
	}
	}

	
		if ($CURUSER && $CURUSER["class"] >= 80) {
	echo '<td style="font-size: 9px; width: 130px;">';
	}else{
		echo '<td style="font-size: 9px; width: 85px;">';}
	echo "<div align='left' style='float: left'>";

		  echo date("d/m  H:i", utc_to_tz_time($row['date']));
	

	echo "</div>";

		if ($CURUSER && $CURUSER["class"] >= 80) {

			    	if ($row["userid"] !== $CURUSER["id"]) {
					
					$respostaeu = "<a href=\"javascript:ShoutResposta('".$row['user'].": ','shoutboxform','message')\">[R]</a>";
		
					}else
					{
							$respostaeu = "";
					
					}
					
		
        echo "<div align='right' style='float: right'>".$respostaeu."&nbsp;&nbsp;<a href='".$site_config['SITEURL']."/shoutedit.php?action=edit&msgid=".$row['msgid']."' style='font-size: 10px'>[E]</a>&nbsp;&nbsp;<a href='".$site_config['SITEURL']."/shoutbox.php?del=".$row['msgid']."' style='font-size: 10px'>[D]</a><div>";
      }else{
	  
	    	if ($row["userid"] !== $CURUSER["id"]) {
	  echo "<div align='right' style='float: right'><a href=\"javascript:ShoutResposta('".$row['user'].": ','shoutboxform','message')\">[R]</a><div>";
	 
	 }
	  
	  }
	  
	  
	  

switch ($row['class']) {
case 100: // Sysop
$user = "<font color=#FF0000><B>$row[user]</B></font>";
break;
case 95: // Administrator
$user = "<font color=#FF0000><B>$row[user]</B></font>";
break;
case 86: // S.Moderador
$user = "<font color=#8b1a1a><B>$row[user]</b></font>";
break;
case 85: // Moderador
$user = "<font color=#000000><B>$row[user]</b></font>";
break;
case 80: // Colaborador
$user = "<font color=#330066>$row[user]</font>";
break;
case 79: // DJ
$user = "$row[user] <img src=images/dj.png border=0 alt='DJ'>";
break;
case 75: // Liberador
$user = "<font color=#16ADAD>$row[user]</font>";
break;
case 70: // Designer
$user = "<font color=#0000CD>$row[user]</font>";
break;
case 71: //Coord Designer
$user = "<font color=#ffd700>$row[user]</font>";
break;
case 65: // moderador de grupo
$user = "<font color=#B8860B>$row[user]</font>";
break;
case 60: // sub de grupo
$user = "<font color=#B8860B>$row[user]</font>";
break;
case 50: // upload
$user = "<font color=#B8860B>$row[user]</font>";
break;
case 30: // vip
$user = "$row[user] <img src=images/star.gif border=0 alt='VIP'>";
break;
case 35: // vip
$user = "$row[user] <img src=images/star.gif border=0 alt='VIP'>";
break;
case 40: // vip
$user = "$row[user] <img src=images/star.gif border=0 alt='VIP'>";
break;
case 45: // vip
$user = "$row[user] <img src=images/star.gif border=0 alt='Vip'>";
break;
default: // User
$user = $row[user];
 }
 if ($row["class"] != '79' ) {
 	if ($row["dj"] == 'yes' ) {
$user =  "$user <img src=images/dj.png border=0 alt='DJ'>";
}
}
if ($row["vipedby"] =='yes' ) {
$user = "$user <img src=images/star.gif border=o alt='VIP'>";

}	

  	if ($teste12 == $CURUSER["username"]) {
$busca = "".$teste12 ."";
}
else{
$busca = "";
}

$str = "".nl2br(format_comment($row['message']))."";
$verdade = preg_replace("/($busca)/is", "<strong><font style='text-decoration: underline;'>\\1</u></font></strong>", $str);





   	if ($teste12 == $CURUSER["username"]) {


echo '</td><td style="font-size: 12px; padding-left: 5px"><a href="'.$site_config['SITEURL'].'/account-details.php?id='.$row['userid'].'" target="_parent"><b>' .$user. '</b></a>:&nbsp;&nbsp;'.$verdade;
} else{
   	if ($user == 'System'){
	?>
</td><td style="font-size: 12px; padding-left: 5px"><center></center>&nbsp;&nbsp;<?php echo $row['message']?>
<?php
}else{
echo '</td><td style="font-size: 12px; padding-left: 5px"><a href="'.$site_config['SITEURL'].'/account-details.php?id='.$row['userid'].'" target="_parent"><b>' .$user. '</b></a>:&nbsp;&nbsp;'.nl2br(format_comment($row['message']));
}
}
echo "</td></tr>";
 }

?>

</table>
</div>
<br />

<?php

//if the user is logged in, show the shoutbox, if not, dont.
if(!isset($_GET['history'])) {
	if (isset($_COOKIE["pass"])){
	
		echo "<form name='shoutboxform' action='shoutbox.php' method='post'>";
		echo "<center><table width='100%' border='0' cellpadding='1' cellspacing='1'>";
		echo "<tr class='shoutbox_messageboxback'>";
		echo "<td width='100%' align='center'>";
	
		echo "<input type='text' name='message' class='shoutbox_msgbox' />";
		echo "</td>";
		echo "<td>";


		echo "<input type='submit' name='submit' value='Enviar' class='shoutbox_shoutbtn' />";
		echo "</td>";
		echo "</tr>";
        echo "<br><td>";
                echo "</td>";
				echo "</table></center>";
						    ?>



<?php
echo "<center><table border=0 cellpadding=0 cellspacing=2><tr>";
echo "<td float:left; width=22><a  href=\"javascript:Smilies(':smile1')\"><img src=images/smilies/smile1.gif border=0 alt=':smile1'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':grin')\"><img src=images/smilies/grin.gif border=0 alt=':grin'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':sad')\"><img src=images/smilies/sad.gif border=0 alt=':sad'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':wacko')\"><img src=images/smilies/wacko.gif border=0 alt=':wacko'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':angry')\"><img src=images/smilies/angry.gif border=0 alt=':angry'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':happy')\"><img src=images/smilies/happy.gif border=0 alt=':happy'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':tongue')\"><img src=images/smilies/tongue.gif border=0 alt=':tongue'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':blink')\"><img src=images/smilies/blink.gif border=0 alt=':blink'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':cool2')\"><img src=images/smilies/cool2.gif border=0 alt='cool2'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':yes')\"><img src=images/smilies/yes.gif border=0 alt=':yes'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':evil')\"><img src=images/smilies/evil.gif border=0 alt=':evil'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':cry')\"><img src=images/smilies/cry.gif border=0 alt=':cry'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':wub')\"><img src=images/smilies/wub.gif border=0 alt=':wub'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':w00t')\"><img src=images/smilies/w00t.gif border=0 alt=':w00t'></a></td>";
echo "<td float:left; width=22><a href=\"javascript:Smilies(':thumbsup')\"><img src=images/smilies/thumbsup.gif border=0 alt=':thumbsup'></a></td>";
      quickbbshout($dossier);  

   ?>
<div class="shoutbox_texto">

<script language="JavaScript">
var countDownInterval=200;
var countDownTime=countDownInterval+1;
function countDown(){
countDownTime--;
if (countDownTime <=0){
countDownTime=countDownInterval;
clearTimeout(counter)
window.location="http://www.malucos-share.org/shoutbox.php";
return
}
if (document.all) //if IE 4+
document.all.countDownText.innerText = countDownTime+" ";
else if (document.getElementById) //else if NS6+
document.getElementById("countDownText").innerHTML=countDownTime+" "
else if (document.layers){ //CHANGE TEXT BELOW TO YOUR OWN
document.c_reload.document.c_reload2.document.write('Próxima <a href=shoutbox.php>atualização</a>: <b id="countDownText">'+countDownTime+' </b> segs')
document.c_reload.document.c_reload2.document.close()
}
counter=setTimeout("countDown()", 1000);
}

function startit(){
if (document.all||document.getElementById) //CHANGE TEXT BELOW TO YOUR OWN
document.write('Próxima <a href=shoutbox.php>atualização</a>: <b id="countDownText">'+countDownTime+' </b> segs')
countDown()
}

if (document.all||document.getElementById)
startit()

</script>

<?php

  
       		?>

<A HREF="#" class="Style5" onClick="window.open('shoutbox.php','shoutbox','toolbar=0, location=0, directories=0, status=0,  resizable=0, copyhistory=0, menuBar=0, width=750, height=485, left=0, top=0');return(false)"><small><B class="shoutbox_texto">[<?php echo T_("SHOUTBOX_POPUP"); ?>]</B></small></a>&nbsp



<?php
		?>

<A HREF="#" class="Style3" onClick="window.open('mysmilies.php','player','toolbar=0, location=0, directories=0, status=0,  resizable=0, copyhistory=0, menuBar=0, width=590, height=490, left=0, top=0');return(false)"><small><B class="shoutbox_texto">[+<?php echo T_("SHOUTBOX_SMILES"); ?>]</B></small></a>




<?php

        echo '<small class="shoutbox_texto"> -</small> <a href="javascript:PopMoreTags();"><B class="shoutbox_texto">[ Tags ]</B></small></a>' ;
	           
		echo "<small class='shoutbox_texto'> -</small> <a href='".$site_config['SITEURL']."/shoutbox.php?history=1' target='_blank'><small><B class='shoutbox_texto'>[ Histórico ]</B></small></a>";
		echo "
<select name='color' size='0.5' onChange=\"javascript:bbcolor()\">
<option selected='selected'>Cor</option>
<option value=blue style=color:blue>Azul</option>
<option value=darkblue style=color:darkblue>Azul Escuro</option>
<option value=indigo style=color:indigo>Roxo</option>
<option value=sienna style=color:sienna>Marrom</option>
<option value=red style=color:red>Vermelho</option>
<option value=orange style=color:orange>Laranja</option>
<option value=deeppink style=color:deeppink>Rosa</option>
<option value=green style=color:green>Verde</option>
<option value=silver style=color:silver>Cinza</option>
</select>";
echo "";

	
		echo "</table></center></tr></div>";
		echo "</form>";
		
	}else{
		echo "<br /><div class='shoutbox_error'>".T_("SHOUTBOX_MUST_LOGIN")."</div>";
	}
}

if(!isset($_GET['history'])){ 
	echo "</body></html>";
}else{
	end_framec();
	stdfoot();
}



?>