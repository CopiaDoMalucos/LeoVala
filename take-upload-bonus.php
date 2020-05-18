<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn(false);
loggedinonly();

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Sysop" ){
	show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);

stdhead("Upload Manager");
begin_frame("Upload Manager");

$class = $_POST['class'];

#-----------------------------------------------
# + 1GB
#-----------------------------------------------
if($_POST['1gig'] == "+1 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+1073741824 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 1 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
} 
}
  
#-----------------------------------------------
# + 2GB
#-----------------------------------------------
if($_POST['2gig'] == "+2 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+2147483648 WHERE class $class");
  
if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 2 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# + 5GB
#-----------------------------------------------
if($_POST['5gig'] == "+5 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+5368709120 WHERE class $class");
  
if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 5 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# + 10GB
#-----------------------------------------------
if($_POST['10gig'] == "+10 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+10737418240 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 10 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# + 15GB
#-----------------------------------------------
if($_POST['15gig'] == "+15 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+16106127360 WHERE class $class");
  
if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 15 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# + 25GB
#-----------------------------------------------
if($_POST['25gig'] == "+25 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+26843545600 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 25 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# + 50GB
#-----------------------------------------------
if($_POST['50gig'] == "+50 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+53687091200 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 50 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# + 75GB
#-----------------------------------------------
if($_POST['75gig'] == "+75 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+80530636800 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 75 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# + 100GB
#-----------------------------------------------
if($_POST['100gig'] == "+100 GB"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded+107374182400 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message'] .
"[size=2][color=#FF2500][b]Hey, we have added 100 GB upload to your account![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# - 1GB
#-----------------------------------------------
  if($_POST['1gig2'] == "-1 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-1073741824 WHERE class $class");
}

#-----------------------------------------------
# - 2GB
#-----------------------------------------------
  if($_POST['2gig2'] == "-2 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-2147483648 WHERE class $class");
}

#-----------------------------------------------
# - 5GB
#-----------------------------------------------  
  if($_POST['5gig2'] == "-5 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-5368709120 WHERE class $class");
}

#-----------------------------------------------
# - 10GB
#-----------------------------------------------  
  if($_POST['10gig2'] == "-10 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-10737418240 WHERE class $class");
}

#-----------------------------------------------
# - 15GB
#-----------------------------------------------
  if($_POST['15gig2'] == "-15 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-16106127360 WHERE class $class");
}

#-----------------------------------------------
# - 25GB
#-----------------------------------------------
  if($_POST['25gig2'] == "-25 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-26843545600 WHERE class $class");
}

#-----------------------------------------------
# - 50GB
#-----------------------------------------------
  if($_POST['50gig2'] == "-50 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-53687091200 WHERE class $class");
}

#-----------------------------------------------
# - 75GB
#-----------------------------------------------
  if($_POST['75gig2'] == "-75 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-80530636800 WHERE class $class");
}

#-----------------------------------------------
# - 100GB
#-----------------------------------------------
  if($_POST['100gig2'] == "-100 GB"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded-107374182400 WHERE class $class");
}

#-----------------------------------------------
# MULTIPLY X 2
#-----------------------------------------------
if($_POST['x2'] == "X 2"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded*2 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message']  . 
"[size=2][color=#FF2500][b]Hey, we have MULTIPLIED your upload by 2![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# MULTIPLY X 4
#-----------------------------------------------
if($_POST['x4'] == "X 4"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded*4 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message']  . 
"[size=2][color=#FF2500][b]Hey, we have MULTIPLIED your upload by 4![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# MULTIPLY X 5
#-----------------------------------------------
if($_POST['x5'] == "X 5"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded*5 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message']  . 
"[size=2][color=#FF2500][b]Hey, we have MULTIPLIED your upload by 5![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# MULTIPLY X 6
#-----------------------------------------------
if($_POST['x6'] == "X 6"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded*6 WHERE class $class");
  
if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message']  . 
"[size=2][color=#FF2500][b]Hey, we have MULTIPLIED your upload by 6![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# MULTIPLY X 8
#-----------------------------------------------
if($_POST['x8'] == "X 8"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded*8 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message']  . 
"[size=2][color=#FF2500][b]Hey, we have MULTIPLIED your upload by 8![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# MULTIPLY X 10
#-----------------------------------------------
if($_POST['x10'] == "X 10"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded*10 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message']  . 
"[size=2][color=#FF2500][b]Hey, we have MULTIPLIED your upload by 10![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# MULTIPLY X 15
#-----------------------------------------------
if($_POST['x15'] == "X 15"){
$res = SQL_Query_exec("UPDATE users SET uploaded = uploaded*15 WHERE class $class");

if ($num == $_POST["numclasses"]){
$res = SQL_Query_exec("SELECT id FROM users WHERE class $class");
$msg = $_POST['message']  . 
"[size=2][color=#FF2500][b]Hey, we have MULTIPLIED your upload by 15![/b][/color][/size]
 Please do not think this is a time to not seed. The same rules as always apply and we hate leechers...
 So, if you're not a leecher, download something nice and enjoy!";
}else{
 $res = SQL_Query_exec("SELECT id FROM users where id = 1".$querystring) or sqlerr();
}
if ($_POST["fromsystem"] == "yes"){ $sender_id="0";}else{$sender_id = $CURUSER["id"];}
while($arr = mysql_fetch_row($res))
{
 SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ($sender_id, $arr[0], '" . get_date_time() . "', " . sqlesc($msg) . ", $sender_id)") or sqlerr();
}
}

#-----------------------------------------------
# DIVIDE by 2
#-----------------------------------------------
  if($_POST['d2'] == "/ 2"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded/2 WHERE class $class");
}

#-----------------------------------------------
# DIVIDE by 4
#-----------------------------------------------
  if($_POST['d4'] == "/ 4"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded/4 WHERE class $class");
}

#-----------------------------------------------
# DIVIDE by 5
#-----------------------------------------------
  if($_POST['d5'] == "/ 5"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded/5 WHERE class $class");
}

#-----------------------------------------------
# DIVIDE by 6
#-----------------------------------------------
  if($_POST['d6'] == "/ 6"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded/6 WHERE class $class");
}

#-----------------------------------------------
# DIVIDE by 8
#-----------------------------------------------
  if($_POST['d8'] == "/ 8"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded/8 WHERE class $class");
}

#-----------------------------------------------
# DIVIDE by 10
#-----------------------------------------------
  if($_POST['d10'] == "/ 10"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded/10 WHERE class $class");
}

#-----------------------------------------------
# DIVIDE by 15
#-----------------------------------------------
  if($_POST['d15'] == "/ 15"){
  $res = SQL_Query_exec("UPDATE users SET uploaded = uploaded/15 WHERE class $class");
}

#-----------------------------------------------
# END
#-----------------------------------------------
end_frame();
?>
  
<META HTTP-EQUIV="Refresh"
	CONTENT="1; URL=upload-bonus.php">
<style type="text/css">
<!--
.style1 {
font-size: 12px;
font-weight: bold;
}
-->
</style>
<br />
<span class="style1">Operation successfully! ....Redirecting</span>
