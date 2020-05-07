<?php
require_once("backend/functions.php");

dbconn(false);

global $CURUSER;

//GET CURRENT USERS THEME AND LANGUAGE
if ($CURUSER){
	$ss_a = @mysql_fetch_array(@mysql_query("select uri from stylesheets where id=" . $CURUSER["stylesheet"])) or die(mysql_error());
	if ($ss_a)
		$THEME = $ss_a[uri];
		$lng_a = @mysql_fetch_array(@mysql_query("select uri from languages where id=" . $CURUSER["language"])) or die(mysql_error());
	if ($lng_a)
		$LANGUAGE =$lng_a[uri];
}else{//not logged in so get default theme/language
	$ss_a = mysql_fetch_array(mysql_query("select uri from stylesheets where id='" . $site_config['default_theme'] . "'")) or die(mysql_error());
	if ($ss_a)
		$THEME = $ss_a[uri];
	$lng_a = mysql_fetch_array(mysql_query("select uri from languages where id='" . $site_config['default_language'] . "'")) or die(mysql_error());
	if ($lng_a)
		$LANGUAGE = $lng_a[uri];
}
@mysql_free_result($lng_a);
@mysql_free_result($ss_a);
if ($CURUSER){
if(!isset($_GET['history'])){ 
?>
<HTML>
<HEAD>
<TITLE><?=$site_config['SITENAME']?>Shoutbox</TITLE>
<META HTTP-EQUIV="refresh" content="100">
<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/theme.css" />
</HEAD>
<body class="shoutbox_body">
<?
	echo '<div class="shoutbox_contain"><table border="0" background="#ffffff" style="width: 99%; table-layout:fixed">';
}else{
?>
<HTML>
<HEAD>
<TITLE><?=$site_config['SITENAME']?>Shoutbox History</TITLE>
<META HTTP-EQUIV="refresh" content="100">
<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/theme.css" />
</HEAD>
<body class="shoutbox_body">
<?php
	//stdhead("Shoutbox History",0);
	//begin_framec("Shoutbox History");
	echo '<div class="shoutbox_history">';

	$query = 'SELECT COUNT(ajshoutbox.id) FROM ajshoutbox';
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	echo '<div align="middle">Pages: ';
	$count = $row[0];
	$perpage = 20;
	//$i = 1;
	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?history=1&");
		
		echo $pagertop;
	

	echo '</div></br><table border="0" background="#ffffff" style="width: 99%; table-layout:fixed">';
}
@mysql_free_result($result);

		
		
	$query = 'SELECT s.*, u.avatar FROM ajshoutbox s left join users u on s.uid=u.id left join groups g on g.group_id=u.class ORDER BY id DESC '.$limit;

$result = mysql_query($query);
$alt = false;

while ($row = mysql_fetch_assoc($result)) {

     $i = 0; $i < $num; ++$i;
	if ($alt){	
		echo '<tr class="shoutbox_noalt">';
		$alt = false;
	}else{
		echo '<tr class="shoutbox_alt">';
		$alt = true;
	}

	echo '<td style="font-size: 9px; width: 118px;">';
	echo "<div align='left' style='float: left'>";
if ($CURUSER){
	echo date('jS M, g:ia', utc_to_tz_time($row['date']));

$avatar=$row['avatar'];
//$AgetHeaders = @get_headers($avatar);
if ($avatar) {
$avatar="<img src=".stripslashes($row['avatar'])." border='0' width='50' height='50'>";
} 
elseif($row["userid"]=="0"){
$avatar="<img src='HD.png' border='0' width='50' height='50'>";
}
else if(!$avatar || $avatar=="") {
$avatar="<img src='images/default_avatar.gif' border='0' width='50' height='50'>";
}
echo "<center>$avatar</center>";
}
	echo "</div>";

$warn=$row['warned'];
if($warn=="yes"){
$warn="<img src=images/warn.gif alt=warned title=warned border=0>";
}else{
$warn="";
}
$don=$row['donated'];
if($don>0){
$don="<img src=images/star.gif alt=donor title=donor border=0>";
}
else{
$don="";
}
if($row["userid"]=="2438"){
$don="";
}

	if ( ($CURUSER["edit_users"]=="yes") || ($CURUSER['username'] == $row['user']) ){
		echo "<div align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$site_config['SITEURL']."/shoutbox.php?del=".$row['msgid']."' style='font-size: 8px'>[D]</a>|<a href='".$site_config['SITEURL']."/shoutedit.php?action=edit&msgid=".$row['msgid']."' style='font-size: 8px'>[E]</a><div>";
	}
$a = mysql_query("SELECT class FROM `users` WHERE id = ".$row[uid]." ");
$ab = @mysql_fetch_row($a);
switch ($ab[0]) {
case 8: // SYS:
$user = "<font color=#15CFCD>$row[name]</font>";
break;
case 7: // ADMINISTRATORSYS:
$user = "<font color=#ff0000>$row[name]</font>";
break;
case 6: // Administrator
$user = "<font color=teal>$row[name]</font>";
break;
case 5: // Super Moderator
$user = "<font color=#3300CC>$row[name]</font>";
break;
case 4: // Moderator
$user = "<font color=#006600>$row[name]</font>";
break;
case 3: // VIP
$user = "<font color=#FFFF00>$row[name]</font>";
break;
case 2: // Uploader
$user = "<font color=#33FF33>$row[name]</font>";
break;
default: // User
$user = $row[name];
}
@mysql_free_result($a);


if($row["userid"]=="2438"){
$user="<font size=\"4\">
<script>

// ********** MAKE YOUR CHANGES HERE

var text=\"kickass\"     //   YOUR TEXT
var speed=80    //   SPEED OF FADE - Higher=faster/Lower=slower

// ********** LEAVE THE NEXT BIT ALONE!

// **** Do Not Alter Code Below ****
if (document.all||document.getElementById){
document.write('<span id=\"highlight\">' + text + '</span>')
var storetext=document.getElementById? document.getElementById(\"highlight\") : document.all.highlight
}
else
document.write(text)
var hex=new Array(\"00\",\"14\",\"28\",\"3C\",\"50\",\"64\",\"78\",\"8C\",\"A0\",\"B4\",\"C8\",\"DC\",\"F0\")
var r=1
var g=1
var b=1
var seq=1
function changetext(){
rainbow=\"#\"+hex[r]+hex[g]+hex[b]
storetext.style.color=rainbow
}
function change(){
if (seq==6){
b--
if (b==0)
seq=1
}
if (seq==5){
r++
if (r==12)
seq=6
}
if (seq==4){
g--
if (g==0)
seq=5
}
if (seq==3){
b++
if (b==12)
seq=4
}
if (seq==2){
r--
if (r==0)
seq=3
}
if (seq==1){
g++
if (g==12)
seq=2
}
changetext()
}
function starteffect(){
if (document.all||document.getElementById)
flash=setInterval(\"change()\",speed)
}
starteffect()
</script>
</font></b>
";
}


// end online status

//strong privacy we will gide status

if($row["privacy"]=="strong"){
$status="";
}


if ($CURUSER){
	echo	'</td><td style="font-size: 12px; padding-left: 5px"><a href="javascript:windowunder(\'account-details.php?id='.$row[uid].'\');"><b>'.$user.'&nbsp;:</b>'.$warn.''.$don.'</a>&nbsp;&nbsp;'.nl2br(format_comment($row['text']));
	echo	'</td></tr>';
}
}
?>
<script>
function SmileIT(smile){
    document.forms['shoutboxform'].elements['message'].value = document.forms['shoutboxform'].elements['message'].value+" "+smile+" ";  //this non standard attribute prevents firefox' autofill function to clash with this script
    document.forms['shoutboxform'].elements['message'].focus();
}
function PopMoreSmiles(form,name) {
         link='moresmiles.php?form='+form+'&text='+name
         newWin=window.open(link,'moresmile','height=500,width=350,resizable=yes,scrollbars=yes');
         if (window.focus) {newWin.focus()}
}
function Pophistory() {
         link='shoutbox.php?history=1&page=0'
         newWin=window.open(link,'moresmile','height=500,width=500,resizable=yes,scrollbars=yes');
         if (window.focus) {newWin.focus()}
}
function windowunder(link)
{
  window.opener.document.location=link;
  window.close();
}
</script>

</table><br><br>
<div valign=bottom style="margin-bottom:40px;"><center><a href='javascript:window.close();'>Close</a></center></div><br>
<?php echo $pagerbottom;?>
</div>
<br>
<script language=javascript>

function GiveMsgBoxFocus()
{
document.shoutboxform.message.focus();
}
</script>
<?php
}
?>