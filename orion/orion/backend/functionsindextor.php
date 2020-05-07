<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

error_reporting(E_ALL ^ E_NOTICE);

// Prefer unescaped. Data will be escaped as needed.
if (ini_get("magic_quotes_gpc")) {
	$_POST = array_map_recursive("unesc", $_POST);
	$_GET = array_map_recursive("unesc", $_GET);
	$_REQUEST = array_map_recursive("unesc", $_REQUEST);
	$_COOKIE = array_map_recursive("unesc", $_COOKIE);
}

if (function_exists("date_default_timezone_set"))
	date_default_timezone_set("America/Brasilia"); // Do NOT change this. All times are converted to user's chosen timezone.

define("BASEPATH", str_replace("backend", "", dirname(__FILE__)));
$BASEPATH = BASEPATH;
define("BACKEND", dirname(__FILE__));
$BACKEND = BACKEND;

require_once(BACKEND."/mysql.php"); //Get MYSQL Connection Info
require_once(BACKEND."/config.php");  //Get Site Settings and Vars ($site_config)
require(BACKEND."/tzs.php"); // Get Timezones
require_once(BACKEND."/cache.php"); // Caching
require_once(BACKEND."/mail.php"); // Mail functions
require_once(BACKEND."/mysql.class.php");


$GLOBALS['tstart'] = array_sum(explode(" ", microtime()));


/**
 * Connect to the database and load user details
 *
 * @param $autoclean
 *   (optional) boolean - Check whether or not to run cleanup (default: false)
 */
function dbconn($autoclean = false) {
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $THEME, $LANGUAGE, $LANG, $site_config;
	$THEME = $LANGUAGE = null;

	if (!ob_get_level()) {
		if (extension_loaded('zlib') && !ini_get('zlib.output_compression'))
			ob_start('ob_gzhandler');
		else
			ob_start();
	}

		function_exists("mysql_connect") or die("MySQL support not available.");
header("Content-Type: text/html; charset=utf-8");
	@mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die('DATABASE: mysql_connect: ' . mysql_error());
	@mysql_select_db($mysql_db) or die('DATABASE: mysql_select_db: ' . mysql_error());
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
	unset($mysql_pass); //security

	userlogin(); //Get user info

	//Get language and theme
	$CURUSER = $GLOBALS["CURUSER"];

    $ss_a = mysql_fetch_assoc(SQL_Query_exec("select uri from stylesheets where id='" . ( $CURUSER ? $CURUSER['stylesheet'] : $site_config['default_theme'] )  . "'"));
	$THEME = $ss_a["uri"];



	if ($autoclean)
		autoclean();
}

// Main Cleanup
function autoclean() {
	global $site_config;
    require_once("cleanup.php");

    $now = gmtime();

    $res = SQL_Query_exec("SELECT last_time FROM tasks WHERE task='cleanup'");
    $row = mysql_fetch_row($res);
    if (!$row) {
        SQL_Query_exec("INSERT INTO tasks (task, last_time) VALUES ('cleanup',$now)");
        return;
    }
    $ts = $row[0];
    if ($ts + $site_config["autoclean_interval"] > $now)
        return;
    SQL_Query_exec("UPDATE tasks SET last_time=$now WHERE task='cleanup' AND last_time = $ts");
    if (!mysql_affected_rows())
        return;

    do_cleanup();
}
function get_dt_num(){
	return gmdate("YmdHis");
}
// IP Validation
function validip($ip) {
	if (extension_loaded("filter")) {
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
	}

	if (preg_match('![:a-f0-9]!i', $ip))
		return true;

	if (!empty($ip) && $ip == long2ip(ip2long($ip))) {
		$reserved_ips = array (
				array('0.0.0.0','2.255.255.255'),
				array('10.0.0.0','10.255.255.255'),
				array('127.0.0.0','127.255.255.255'),
				array('169.254.0.0','169.254.255.255'),
				array('172.16.0.0','172.31.255.255'),
				array('192.0.2.0','192.0.2.255'),
				array('192.168.0.0','192.168.255.255'),
				array('255.255.255.0','255.255.255.255')
		);

		foreach ($reserved_ips as $r) {
				$min = ip2long($r[0]);
				$max = ip2long($r[1]);
				if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
		}
		return true;
	}
		return false;
}

//== Updated getip with validation
function getip()
{
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        return $ip;
    }
    foreach (array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_CF_CONNECTING_IP',
        'REMOTE_ADDR'
    ) as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
}

function userlogin() {
	$ip = getip();
	// If there's no IP a script is being ran from CLI. Any checks here will fail, skip all.
	if ($ip == "") return;

	GLOBAL $CURUSER;
	unset($GLOBALS["CURUSER"]);

	//Check IP bans
	if (is_ipv6($ip))
		$nip = ip2long6($ip);
	else
		$nip = ip2long($ip);
	$res = SQL_Query_exec("SELECT * FROM bans");
	while ($row = mysql_fetch_assoc($res)) {
		$banned = false;
		if (is_ipv6($row["first"]) && is_ipv6($row["last"]) && is_ipv6($ip)) {
			$row["first"] = ip2long6($row["first"]);
			$row["last"] = ip2long6($row["last"]);
			$banned = bccomp($row["first"], $nip) != -1 && bccomp($row["last"], $nip) != -1;
		} else {
			$row["first"] = ip2long($row["first"]);
			$row["last"] = ip2long($row["last"]);
			$banned = $nip >= $row["first"] && $nip <= $row["last"];
		}
		if ($banned) {
			header("HTTP/1.0 403 Forbidden");
			echo "<html><head><title>Forbidden</title></head><body><h1>Forbidden</h1>Endereço IP não autorizado.<br />".
			"Reason for banning: $row[comment]</body></html>";
			die;
		}
	}

	//Check The Cookie and get CURUSER details
	if (strlen($_COOKIE["pass"]) != 40 || !is_numeric($_COOKIE["uid"])) {
		logoutcookie();
		return;
	}
        
	//Get User Details And Permissions
	$res = SQL_Query_exec("SELECT * FROM users INNER JOIN groups ON users.class=groups.group_id WHERE id=$_COOKIE[uid] AND users.enabled='yes' AND users.status = 'confirmed'");
	$row = mysql_fetch_assoc($res);   



    if (!$row || sha1($row["id"].$row["secret"].$row["password"].$row["secret"]) != $_COOKIE["pass"]) {  
		logoutcookie();
		return;
	}

	$where = where ($_SERVER["SCRIPT_FILENAME"], $row["id"], 0);
	SQL_Query_exec("UPDATE users SET last_access='" . get_date_time() . "', ip=".sqlesc($ip).", page=".sqlesc($where)." WHERE id=" . $row["id"]);


	$GLOBALS["CURUSER"] = $row;
	unset($row);
}

function logincookie($id, $password, $secret, $remember = false, $updatedb = 1, $expires = 0x7fffffff) {
    
    if ( !$remember ) $expires = null;
    
    $hash = sha1($id.$secret.$password.$secret);
    setcookie("pass", $hash, $expires, "/");
    setcookie("uid", $id, $expires, "/");

    if ($updatedb)
        SQL_Query_exec("UPDATE users SET last_login = '".get_date_time()."' WHERE id = $id");
}
function logoutcookie() {
	setcookie("pass", null, time(), "/");
	setcookie("uid", null, time(), "/");
}
         function seedtime($time = 0)
                {
                        $days = floor($time / 86400);
                        $hour = floor($time / 3600);
                        $mins = floor(($time / 60) % 60);
                        $secs = $time % 60;
                        
                        return "$days dias, $hour horas, $mins minutos, $secs secs";
                }

function stdhead($title = "") {
	global $site_config, $CURUSER, $THEME, $LANGUAGE;  //Define globals
 
	//site online check
	if (!$site_config["SITE_ONLINE"]){
		if ($CURUSER["control_panel"] != "yes") {
			echo '<br /><br /><br /><center>'. stripslashes($site_config["OFFLINEMSG"]) .'</center><br /><br />';
			die;
		}else{
			echo '<br /><br /><br /><center><b><font color="#ff0000">SITE OFFLINE, STAFF ONLY VIEWING! DO NOT LOGOUT</font></b><br />If you logout please edit backend/config.php and set SITE_ONLINE to true </center><br /><br />';
		}
	}
	//end check

    if (!$CURUSER)
		guestadd();

    if ($title == "")
        $title = $site_config['SITENAME'];
    else
        $title = $site_config['SITENAME']. " : ". htmlspecialchars($title);

	require_once("themes/" . $THEME . "/block.php");
	require_once("themes/" . $THEME . "/header.php");
}

function stdfoot() {
	global $site_config, $CURUSER, $THEME, $LANGUAGE;
	require_once("themes/" . $THEME . "/footer.php");
	mysql_close();
}

function leftblocks() {
    global $site_config, $CURUSER, $THEME, $LANGUAGE, $TTCache, $blockfilename;  //Define globals

    if (($blocks=$TTCache->get("blocks_left", 900)) === false) {
        $res = SQL_Query_exec("SELECT * FROM blocks WHERE position='left' AND enabled=1 ORDER BY sort");
        $blocks = array();
        while ($result = mysql_fetch_assoc($res)) {
                $blocks[] = $result["name"];
        }
        $TTCache->Set("blocks_left", $blocks, 900);
    }

    foreach ($blocks as $blockfilename){
        include("blocks/".$blockfilename."_block.php");
    }
}

function rightblocks() {
    global $site_config, $CURUSER, $THEME, $LANGUAGE, $TTCache, $blockfilename;  //Define globals

    if (($blocks=$TTCache->get("blocks_right", 0)) === false) {
        $res = SQL_Query_exec("SELECT * FROM blocks WHERE position='right' AND enabled=1 ORDER BY sort");
        $blocks = array();
        while ($result = mysql_fetch_assoc($res)) {
                $blocks[] = $result["name"];
        }
        $TTCache->Set("blocks_right", $blocks, 0);
    }

    foreach ($blocks as $blockfilename){
        include("blocks/".$blockfilename."_block.php");
    }
}

function middleblocks() {
    global $site_config, $CURUSER, $THEME, $LANGUAGE, $TTCache;  //Define globals

    if (($blocks=$TTCache->get("blocks_middle", 900)) === false) {
        $res = SQL_Query_exec("SELECT * FROM blocks WHERE position='middle' AND enabled=1 ORDER BY sort");
        $blocks = array();
        while ($result = mysql_fetch_assoc($res)) {
                $blocks[] = $result["name"];
        }
        $TTCache->Set("blocks_middle", $blocks, 900);
    }

    foreach ($blocks as $blockfilename){
        include("blocks/".$blockfilename."_block.php");
    }
}
//Registration with invite and full web alert box
function reg_error2($title, $message, $wrapper = "1") {
print("<p align='center' class='regbox'><b>" . $message . "</b></p>\n");
        if ($wrapper=="1"){ die(); }
}
//Login, recover, registration popup Error
function show_error2($title, $message, $wrapper = "0") {
print("<p align='center' id='msgError' class='message clear closable'><b>" . $message . "</b></p>\n");
}


function show_error_msg($title, $message, $wrapper = "1") {
	if ($wrapper) {
        ob_start();
        ob_clean();
		stdhead($title);
	}
	begin_framec("<font class='error'>". htmlspecialchars($title) ."</font>");
	print("<center><b>" . $message . "</b></center>\n");
	end_framec();

	if ($wrapper){
		stdfoot();
		die();
	}
}

function health($leechers, $seeders) {
	if (($leechers == 0 && $seeders == 0) || ($leechers > 0 && $seeders == 0))
		return 0;
	elseif ($seeders > $leechers)
		return 10;

	$ratio = $seeders / $leechers * 100;
	if ($ratio > 0 && $ratio < 15)
		return 1;
	elseif ($ratio >= 15 && $ratio < 25)
		return 2;
	elseif ($ratio >= 25 && $ratio < 35)
		return 3;
	elseif ($ratio >= 35 && $ratio < 45)
		return 4;
	elseif ($ratio >= 45 && $ratio < 55)
		return 5;
	elseif ($ratio >= 55 && $ratio < 65)
		return 6;
	elseif ($ratio >= 65 && $ratio < 75)
		return 7;
	elseif ($ratio >= 75 && $ratio < 85)
		return 8;
	elseif ($ratio >= 85 && $ratio < 95)
		return 9;
	else
		return 10;
}

function anti_injection($sql)
{

$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql);
$sql = trim($sql);
$sql = strip_tags($sql);
$sql = addslashes($sql);
return $sql;
}
// MySQL escaping
function sqlesc($x) {
   if (!is_numeric($x)) {
       $x = "'".mysql_real_escape_string($x)."'";
   }
   return $x;
}


function unesc($x) {
	if (get_magic_quotes_gpc())
		return stripslashes($x);
	return $x;
}

/**
 * Convert bytes to readable format
 *
 * @param $s
 *   integer: bytes
 * @param $precision
 *   (optional) integer: decimal precision (default: 2)
 * @return
 *   string: formatted size
 */
function mksize ($s, $precision = 2) {
	$suf = array("B", "kB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

	for ($i = 1, $x = 0; $i <= count($suf); $i++, $x++) {
		if ($s < pow(1024, $i) || $i == count($suf)) // Change 1024 to 1000 if you want 0.98GB instead of 1,0000MB
			return number_format($s/pow(1024, $x), $precision)." ".$suf[$x];
	}
}

function escape_url($url) {
	$ret = '';
	for($i = 0; $i < strlen($url); $i+=2)
	$ret .= '%'.$url[$i].$url[$i + 1];
	return $ret;
}


function mkprettytime($s) {
	if ($s < 0)
		$s = 0;

	$t = array();
	$t["day"] = floor($s / 86400);
	$s -= $t["day"] * 86400;

	$t["hour"] = floor($s / 3600);
	$s -= $t["hour"] * 3600;

	$t["min"] = floor($s / 60);
	$s -= $t["min"] * 60;

	$t["sec"] = $s;

	if ($t["day"])
		return $t["day"] . "d " . sprintf("%02d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
	if ($t["hour"])
		return sprintf("%d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
        return sprintf("%d:%02d", $t["min"], $t["sec"]);
}

function gmtime() {
	return sql_timestamp_to_unix_timestamp(get_date_time());
}

function loggedinonly() {
	global $CURUSER;
	if (!$CURUSER) {
		header("Refresh: 0; url=account-login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]));
		exit();
	}
}




function is_valid_id($id){
	return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

function sql_timestamp_to_unix_timestamp($s){
	return mktime(substr($s, 11, 2), substr($s, 14, 2), substr($s, 17, 2), substr($s, 5, 2), substr($s, 8, 2), substr($s, 0, 4));
}




function get_elapsed_time($ts){
  $mins = floor((gmtime() - $ts) / 60);
  $hours = floor($mins / 60);
  $mins -= $hours * 60;
  $days = floor($hours / 24);
  $hours -= $days * 24;
  $weeks = floor($days / 7);
  $days -= $weeks * 7;
    $weeks1 = floor($weeks / 4);
  $weeks -= $weeks1 * 4;
  $t = "";
  if ($weeks1 > 0)
    return "$weeks1 Mese" . ($weeks1 > 1 ? "s" : "");
	  if ($weeks > 0)
    return "$weeks semana" . ($weeks > 1 ? "s" : "");
  if ($days > 0)
    return "$days dia" . ($days > 1 ? "s" : "");
  if ($hours > 0)
    return "$hours hr" . ($hours > 1 ? "s" : "");
  if ($mins > 0)
    return "$mins Min" . ($mins > 1 ? "s" : "");
  return "< 1 min";
}

function time_ago($addtime) {
   $addtime = get_elapsed_time(sql_timestamp_to_unix_timestamp($addtime));
   return $addtime;
}

function CutName ($vTxt, $Car) {
	if (strlen($vTxt) > $Car) {
		return substr($vTxt, 0, $Car) . "...";
	}
	return $vTxt;
}

function searchfield($s) {
    return preg_replace(array('/[^a-z0-9]/si', '/^\s*/s', '/\s*$/s', '/\s+/s'), array(" ", "", "", " "), $s);
}

function get_row_count($table, $suffix = "") {
    $res = SQL_Query_exec("SELECT COUNT(*) FROM $table $suffix");
    $row = mysql_fetch_row($res);
    return $row[0];
}
function get_row_count1($table, $suffix = "") {
  ($r = mysql_query("SELECT COUNT(*) FROM $table $suffix")) or die(mysql_error());
  ($a = mysql_fetch_row($r)) or die(mysql_error());
  return $a[0];
}
function get_date_time($timestamp = 0){
	if ($timestamp)
		return date("Y-m-d H:i:s", $timestamp);
	else
		return gmdate("Y-m-d H:i:s");
}

// Convert UTC to user's timezone
function utc_to_tz ($timestamp=0) {
	GLOBAL $CURUSER, $tzs;

	if (method_exists("DateTime", "setTimezone")) {
		if (!$timestamp)
			$timestamp = get_date_time();
		$date = new DateTime($timestamp, new DateTimeZone("UTC"));

		$date->setTimezone(new DateTimeZone($CURUSER ? $tzs[$CURUSER["tzoffset"]][1] : "Europe/London"));
		return $date->format("d/m/Y ~ H:i:s");
	}
	if (!is_numeric($timestamp))
		$timestamp = sql_timestamp_to_unix_timestamp($timestamp);
	if ($timestamp == 0)
		$timestamp = gmtime();

	$timestamp = $timestamp + ($CURUSER['tzoffset']*60);
	if (date("I")) $timestamp += 3600; // DST Fix
	return date("Y-m-d H:i:s", $timestamp);
}

function utc_to_tz_time ($timestamp=0) {
	GLOBAL $CURUSER, $tzs;

	if (method_exists("DateTime", "setTimezone")) {
		if (!$timestamp)
			$timestamp = get_date_time();
		$date = new DateTime($timestamp, new DateTimeZone("UTC"));
		$date->setTimezone(new DateTimeZone($CURUSER ? $tzs[$CURUSER["tzoffset"]][1] : "Europe/London"));
		return sql_timestamp_to_unix_timestamp($date->format('Y-m-d H:i:s'));
	}

	if (!is_numeric($timestamp))
		$timestamp = sql_timestamp_to_unix_timestamp($timestamp);
	if ($timestamp == 0)
		$timestamp = gmtime();

	$timestamp = $timestamp + ($CURUSER['tzoffset']*60);
	if (date("I")) $timestamp += 3600; // DST Fix

	return $timestamp;
}

function encodehtml($s, $linebreaks = true) {
	  $s = str_replace("<", "&lt;", str_replace("&", "&amp;", $s));
	  if ($linebreaks)
		$s = nl2br($s);
	  return $s;
}


function format_urls($s){
        return preg_replace(
        "/(\A|[^=\]'\"a-zA-Z0-9])((http|ftp|https|ftps|irc):\/\/[^<>\s]+)/i",
        "\\1<a href='\\2' target='_blank'>\\2</a>", $s);
}

function format_comment($text) {
	global $site_config, $smilies;

	$s = $text;

	$s = htmlspecialchars($s);


	// [*]
	$s = preg_replace("/\[\*\]/", "<li>", $s);
	
	$s = format_urls($s);
	//[align=(center|left|right|justify)]text[/align]
  // Linebreaks

	$s = preg_replace("/\[align=([a-zA-Z]+)\]((\s|.)+?)\[\/align\]/i","<div style=\"text-align:\\1\">\\2</div>", $s);

    //strike
$s = preg_replace("/\[s\]((\s|.)+?)\[\/s\]/i", "<s>\\1</s>", $s);

    //[mail]mail[/mail]
$s = preg_replace("/\[mail\]((\s|.)+?)\[\/mail\]/i","<a href=\"mailto:\\1\" target=\"_blank\">\\1</a>", $s);
    
    ///[fil=]...[/fil]
    $s = preg_replace("/\[fil=([0-9]{1,3})\](http|https:\/\/[^\s'\"<>]+(\.(jpg|jpeg|gif|png)))\[\/fil\]/i", "<img src=\"\\2\" alt=\"bbcode image\" width=\"\\1\"/>", $s);
	// [*]
	$s = preg_replace("/\[\*\]/", "<li>", $s);

	   //[code]Text[/code]
	$s = preg_replace(
		"/\[code\]\s*((\s|.)+?)\s*\[\/code\]\s*/i",
		"<p class=sub><b>Code:</b></p><table class=main border=1 cellspacing=0 cellpadding=10><tr><td style='border: 1px black dotted'>\\1</td></tr></table><br />", $s);
			
	// [*]
	$s = preg_replace("/\[\*\]/", "<li>", $s);

	// [b]Bold[/b]
	$s = preg_replace("/\[b\]((\s|.)+?)\[\/b\]/", "<b>\\1</b>", $s);

	// [i]Italic[/i]
	$s = preg_replace("/\[i\]((\s|.)+?)\[\/i\]/", "<i>\\1</i>", $s);

	// [u]Underline[/u]
	$s = preg_replace("/\[u\]((\s|.)+?)\[\/u\]/", "<u>\\1</u>", $s);

	// [u]Underline[/u]
	$s = preg_replace("/\[u\]((\s|.)+?)\[\/u\]/i", "<u>\\1</u>", $s);

	// [img]http://www/image.gif[/img]
	$s = preg_replace("/\[img\]((http|https):\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png|\.bmp|\.jpeg))\[\/img\]/i", "<img border='0' src=\"\\1\" alt='' />", $s);

	// [img=http://www/image.gif]
	$s = preg_replace("/\[img=((http|https):\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png|\.bmp|\.jpeg))\]/i", "<img border='0' src=\"\\1\" alt='' />", $s);
	
		// [screm]http://www/image.gif[/screem]
	
	while( preg_match( "#\[screem\](.+?)\[/screem\]#is" , $s ) )
  {
    $s = preg_replace("/\[screem\]((http|https):\/\/[^\s'\"<>]+(gif|jpg|png|bmp|jpeg))\[\/screem\]/i", "<a href='\\1' rel='colorbox-{$pn}'><img style='max-width:400px;' src=\"\\1\" </a><br><small>[ Ver em tamanho real ]</small></a>", $s);
    $cboxelement[ $pn ] = "colorbox-{$pn}";
  }
	// [SCREEM]http://www/image.gif[/SCREEM]
	while( preg_match( "#\[SCREEM\](.+?)\[/SCREEM\]#is" , $s ) )
  {
   $s = preg_replace("/\[SCREEM\]((http|https):\/\/[^\s'\"<>]+(gif|jpg|png|bmp|jpeg))\[\/SCREEM\]/i", "<a href='\\1' rel='colorbox-{$pn}'><img style='max-width:400px;' src=\"\\1\" </a><br><small>[ Ver em tamanho real ]</small></a>", $s);
    $cboxelement[ $pn ] = "colorbox-{$pn}";
  }	
	// [screen]http://www/image.gif[/screen]
	
	while( preg_match( "#\[screen\](.+?)\[/screen\]#is" , $s ) )
  {
    $s = preg_replace("/\[screen\]((http|https):\/\/[^\s'\"<>]+(gif|jpg|png|bmp|jpeg))\[\/screen\]/i", "<a href='\\1' rel='colorbox-{$pn}'><img style='max-width:400px;' src=\"\\1\" </a><br><small>[ Ver em tamanho real ]</small></a>", $s);
    $cboxelement[ $pn ] = "colorbox-{$pn}";
  }
	// [SCREEN]http://www/image.gif[/SCREEN]
	while( preg_match( "#\[SCREEN\](.+?)\[/SCREEN\]#is" , $s ) )
  {
   $s = preg_replace("/\[SCREEN\]((http|https):\/\/[^\s'\"<>]+(gif|jpg|png|bmp|jpeg))\[\/SCREEN\]/i", "<a href='\\1' rel='colorbox-{$pn}'><img style='max-width:400px;' src=\"\\1\" </a><br><small>[ Ver em tamanho real ]</small></a>", $s);
    $cboxelement[ $pn ] = "colorbox-{$pn}";
  }		
		while( preg_match( "#\[capa\](.+?)\[/capa\]#is" , $s ) )
  {
    $s = preg_replace("/\[capa\]((http|https):\/\/[^\s'\"<>]+(gif|jpg|png|bmp|jpeg))\[\/capa\]/i", "<a href='\\1' rel='colorbox-{$pn}'><img style='max-width:400px;' src=\"\\1\" </a><br><small>[ Ver em tamanho real ]</small></a>", $s);
    $cboxelement[ $pn ] = "colorbox-{$pn}";
  }

	
	
	
	// [color=blue]Text[/color]
	$s = preg_replace(
		"/\[color=([a-zA-Z]+)\]((\s|.)+?)\[\/color\]/i",
		"<font color='\\1'>\\2</font>", $s);

	// [color=#ffcc99]Text[/color]
	$s = preg_replace(
		"/\[color=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\]((\s|.)+?)\[\/color\]/i",
		"<font color='\\1'>\\2</font>", $s);

    // [url=http://www.example.com]Text[/url]
        $s = preg_replace(
                "/\[url=((http|ftp|https|ftps|irc):\/\/[^<>\s]+?)\]((\s|.)+?)\[\/url\]/i",
                "<a href='\\1' target='_blank'>\\3</a>", $s);

        // [url]http://www.example.com[/url]
        $s = preg_replace(
                "/\[url\]((http|ftp|https|ftps|irc):\/\/[^<>\s]+?)\[\/url\]/i",
                "<a href='\\1' target='_blank'>\\1</a>", $s);
  
	// [size=4]Text[/size]
	$s = preg_replace(
		"/\[size=([1-7])\]((\s|.)+?)\[\/size\]/i",
		"<font size='\\1'>\\2</font>", $s);

	// [font=Arial]Text[/font]
	$s = preg_replace(
		"/\[font=([a-zA-Z ,]+)\]((\s|.)+?)\[\/font\]/i",
		"<font face=\"\\1\">\\2</font>", $s);

	//[quote]Text[/quote]
	while (preg_match("/\[quote\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i", $s))
	$s = preg_replace(
		"/\[quote\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i",
		"<p class='sub'><b>Quote:</b></p><table class='main' border='1' cellspacing='0' cellpadding='10'><tr><td style='border: 1px black dotted'>\\1</td></tr></table><br />", $s);

	//[quote=Author]Text[/quote]
	while (preg_match("/\[quote=(.+?)\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i", $s))
	$s = preg_replace(
		"/\[quote=(.+?)\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i",
		"<p class='sub'><b>\\1 wrote:</b></p><table class='main' border='1' cellspacing='0' cellpadding='10'><tr><td style='border: 1px black dotted'>\\2</td></tr></table><br />", $s);

	// [spoiler]Text[/spoiler]
	$r = substr(md5($text), 0, 4);
	$i = 0;
	while (preg_match("/\[spoiler\]\s*((\s|.)+?)\s*\[\/spoiler\]\s*/i", $s)) {
		$s = preg_replace("/\[spoiler\]\s*((\s|.)+?)\s*\[\/spoiler\]\s*/i",
		"<br /><img src='images/plus.gif' id='pic$r$i' title='Spoiler' onclick='klappe_torrent(\"$r$i\")' alt='' /><div id='k$r$i' style='display: none;'>\\1<br /></div>", $s);
		$i++;
	}

	// [spoiler=Heading]Text[/spoiler]
	while (preg_match("/\[spoiler=(.+?)\]\s*((\s|.)+?)\s*\[\/spoiler\]\s*/i", $s)) {
		$s = preg_replace("/\[spoiler=(.+?)\]\s*((\s|.)+?)\s*\[\/spoiler\]\s*/i",
		"<br /><img src='images/plus.gif' id='pic$r$i' title='Spoiler' onclick='klappe_torrent(\"$r$i\")' alt='' /><b>\\1</b><div id='k$r$i' style='display: none;'>\\2<br /></div>", $s);
		$i++;
	}
		$s = preg_replace("/\[center\]((\s|.)+?)\[\/center\]/", "<center>\\1</center>", $s);


     //[hr]
        $s = preg_replace("/\[hr\]/i", "<hr />", $s);

     //[hr=#ffffff] [hr=red]
        $s = preg_replace("/\[hr=((#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])|([a-zA-z]+))\]/i", "<hr color=\"\\1\"/>", $s);

        //[swf]http://somesite.com/test.swf[/swf]
        $s = preg_replace("/\[swf\]((www.|http:\/\/|https:\/\/)[^\s]+(\.swf))\[\/swf\]/i",
        "<param name='movie' value='\\1'/><embed width='400' height='200' src='\\1'></embed>", $s);

        //[swf=http://somesite.com/test.swf]
        $s = preg_replace("/\[swf=((www.|http:\/\/|https:\/\/)[^\s]+(\.swf))\]/i",
        "<param name='movie' value='\\1'/><embed width='400' height='200' src='\\1'></embed>", $s);
		 	//[swf XXX,YYY]swf[/swf]
        $s = preg_replace("/\[swf (.*?),(.*?)\]((www.|http:\/\/|https:\/\/)[^\s]+(\.swf))\[\/swf\]/i",
    "<param name='movie' value='\\3'/><embed width='\\1' height='\\2' src='\\3'></embed>", $s); 

	//[flash XXX,YYY]swf[/flash]
        $s = preg_replace("/\[flash (.*?),(.*?)\]((www.|http:\/\/|https:\/\/)[^\s]+(\.swf))\[\/flash\]/i",
    "<param name='movie' value='\\3'/><embed width='\\1' height='\\2' src='\\3'></embed>", $s); 
		 		
		 // [df]defilement[/df]
    $s = preg_replace("/\[df\]((\s|.)+?)\[\/df\]/", "<marquee>\\1</marquee>", $s);
    $s = preg_replace("/\[DF\]((\s|.)+?)\[\/DF\]/", "<marquee>\\1</marquee>", $s);
		
			// YouTube Vids [video=http://www.youtube.com]
     $s = preg_replace("/\[video=[^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\]/ims", "<object width=\"680\" height=\"440\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" width=\"400\" height=\"440\"></embed></object>", $s);

     $s = preg_replace("/\[VIDEO=[^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\]/ims", "<object width=\"680\" height=\"440\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" width=\"400\" height=\"440\"></embed></object>", $s);

     // Google Vids [video]http://www.video.google.com[/video]
     $s = preg_replace("/\[video\][^\s'\"<>]*video.google.com.*docid=(-?[0-9]+).*\[\/video\]/ims", "<embed style=\"width:680px; height:440px;\" id=\"VideoPlayback\" align=\"middle\" type=\"application/x-shockwave-flash\" src=\"http://video.google.com/googleplayer.swf?docId=\\1\" allowScriptAccess=\"sameDomain\" quality=\"best\" bgcolor=\"#ffffff\" scale=\"noScale\" wmode=\"window\" salign=\"TL\" FlashVars=\"playerMode=embedded\"> </embed>", $s);

     $s = preg_replace("/\[VIDEO\][^\s'\"<>]*video.google.com.*docid=(-?[0-9]+).*\[\/VIDEO\]/ims", "<embed style=\"width:680px; height:440px;\" id=\"VideoPlayback\" align=\"middle\" type=\"application/x-shockwave-flash\" src=\"http://video.google.com/googleplayer.swf?docId=\\1\" allowScriptAccess=\"sameDomain\" quality=\"best\" bgcolor=\"#ffffff\" scale=\"noScale\" wmode=\"window\" salign=\"TL\" FlashVars=\"playerMode=embedded\"> </embed>", $s);

     //[video]YouTube Vids url[/video]
     $s = preg_replace("/\[video\][^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\[\/video\]/ims", "<object width=\"680\" height=\"440\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" width=\"400\" height=\"440\"></embed></object>", $s);
$s = preg_replace('#\[googlemaps\](.*?)\[/googlemaps\]#si',
'<iframe width="100%" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.co.uk/maps?f=d&source=s_d&saddr=\1&output=embed"></iframe>', $s);
	
     //[video2]YouTube Vids url[/video2]
     $s = preg_replace("/\[video2\][^\s'\"<>]*youtube.com.*v=([^\s'\"<>]+)\[\/video2\]/ims", "<object width=\"680\" height=\"440\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1\"></param><embed src=\"http://www.youtube.com/v/\\1\" type=\"application/x-shockwave-flash\" width=\"400\" height=\"440\"></embed></object>", $s);

	
	// Linebreaks
	$s = nl2br($s);

	// Maintain spacing
	$s = str_replace("  ", " &nbsp;", $s);



	

    


	return $s;
}

function genrelist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories ORDER BY parent_cat ASC, sort_index ASC");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}

function torrenttable($res) {
	global $site_config, $CURUSER, $THEME, $LANGUAGE;  //Define globals

	if ($site_config["MEMBERSONLY_WAIT"] && $site_config["MEMBERSONLY"] && in_array($CURUSER["class"], explode(",",$site_config["WAIT_CLASS"]))) {
		$gigs = $CURUSER["uploaded"] / (1024*1024*1024);
		$ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
		if ($ratio < 0 || $gigs < 0) $wait = $site_config["WAITA"];
		elseif ($ratio < $site_config["RATIOA"] || $gigs < $site_config["GIGSA"]) $wait = $site_config["WAITA"];
		elseif ($ratio < $site_config["RATIOB"] || $gigs < $site_config["GIGSB"]) $wait = $site_config["WAITB"];
		elseif ($ratio < $site_config["RATIOC"] || $gigs < $site_config["GIGSC"]) $wait = $site_config["WAITC"];
		elseif ($ratio < $site_config["RATIOD"] || $gigs < $site_config["GIGSD"]) $wait = $site_config["WAITD"];
		else $wait = 0;
	}

	// Columns
	$cols = explode(",", $site_config["torrenttable_columns"]);
	$cols = array_map("strtolower", $cols);
	$cols = array_map("trim", $cols);
	$colspan = count($cols);
	// End
	//tri
          if (isset($_GET["sort"]))
                           $sort=htmlentities(urldecode($_GET["sort"]));
                  else
                          $sort="id";

                  if (isset($_GET["order"]))
                          $order=htmlentities(urldecode($_GET["order"]));
                  else
                          $order="desc";

                 if ($addparam!="")
                        $addparam.="&";

         $scriptname= $_SERVER["PHP_SELF"];
         
          if ($order=="desc")
                        $fleche="&nbsp;&#8593";
                else
                        $fleche="&nbsp;&#8595";
//fin tri
	// Expanding Area
	$expandrows = array();
	if (!empty($site_config["torrenttable_expand"])) {
		$expandrows = explode(",", $site_config["torrenttable_expand"]);
		$expandrows = array_map("strtolower", $expandrows);
		$expandrows = array_map("trim", $expandrows);
	}
	// End
		 $idteam = (int) $_GET['cat'];
		 $parent_catid = $_REQUEST['parent_cat'];


echo '<ul style="margin:0;padding:0;" class="alista">';
		echo '<li class="listhead">';

	foreach ($cols as $col) {
		switch ($col) {
			             case 'category':
                               echo "<div style='width: 50px;' class='divhead'><div style='width: 50px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_catid."&sort=category&order=".($sort=="category" && $order=="desc"?"asc":"desc")."\"><font color='white'>Tipo</font></a>".($sort=="category"?$fleche:"")."</div></div>";
                        break;
                        case 'name':
                                echo "<div style='text-align: left; min-width: 200px; width: 757px;' class='divhead'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_catid."&sort=name&order=".($sort=="name" && $order=="desc"?"asc":"desc")."\"><font color='white'>Nome</font></a>".($sort=="name"?$fleche:"")."</div>";
                        break;
                        case 'dl':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px;'>Down</div></div>";
                        break;
                        case 'comments':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_catid."&sort=comments&order=".($sort=="comments" && $order=="desc"?"asc":"desc")."\"><font color='white'>Com</font></a>".($sort=="comments"?$fleche:"")."</div></div>";
                        break;
                        case 'size':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_catid."&sort=size&order=".($sort=="size" && $order=="desc"?"asc":"desc")."\"><font color='white'>Tamanho</font></a>".($sort=="size"?$fleche:"")."</div></div>";
                        break;
                        case 'seeders':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_catid."&sort=seeders&order=".($sort=="seeders" && $order=="desc"?"asc":"desc")."\"><font color='white'>S</font></a>".($sort=="seeders"?$fleche:"")."</div></div>";
                        break;
                        case 'leechers':
                                echo "<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href=\"$scriptname?$addparam"."cat=".$idteam."&parent_cat=".$parent_catid."&sort=leechers&order=".($sort=="leechers" && $order=="desc"?"asc":"desc")."\"><font color='white'>L</font></a>".($sort=="leechers"?$fleche:"")."</div></div>";
                        break;
					
							
		}
	}

	  echo "</li>";

	while ($row = mysql_fetch_assoc($res)) {
		$id = $row["id"];

		print("<li style='' class='listlist'>");
 
	$x = 1;

	foreach ($cols as $col) {
		switch ($col) {
			case 'category':
				print("<div style='width: 50px;' class='divlista'><div style='width: 50px;'>");
				if (!empty($row["cat_name"])) {
					print("<a href=\"torrents.php?cat=" . $row["category"] . "&parent_cat=".$parent_catid."&sort=0&order=".($sort=="size" && $order=="desc"?"asc":"desc")."\">");
					if (!empty($row["cat_pic"]) && $row["cat_pic"] != "")
						print("<img border=\"0\"src=\"".$site_config['SITEURL']."/images/categories/".$row["cat_pic"]."\" alt=\"" . $row["cat_name"] . "\" title=\"Categoria: " . $row["cat_name"] . "\" />");
					else
						print($row["cat_parent"].": ".$row["cat_name"]);
					print("</a>");
				} else
					print("-");
				print("</div></div>");
			break;
			case 'name':
			$char1 = 100; //cut name length 
				$smallname = htmlspecialchars(CutName($row["name"], $char1));
			if ($row["freeleechuser"] == 'yes'){
					$vip = "<img src=/images/star.gif width=13 height=13 >";
					}else{
					$vip = "";
					}
                            $dispname = "<b>".$smallname." ".$vip." </b><br>";		
	                
			

					if ($row["freeleech"] == 1)
					$dispname .= " <b>[<font color='#00CC00'>FREE</font>]</b>";
					

			
               if ($row["filmeresolucao"] > 1200 ||  $row["filmeresolucalt"] > 720 )
 {
			   
                $dispname .= "<b>[<font color='#FF3635 '>HD</font>]</b>";
        }		
								///qualidade 3d

               if ($row["filme3d"] == 20 ) {
                $dispname .= "<b>[<font color='#FF4500'>3D</font>]</b>";
        }	

		
    $exe_grupo = mysql_query("SELECT * FROM teams  LEFT JOIN users ON teams.id = users.team WHERE users.id=".$row["owner"]." ");  
	
	

			$arr_grupo = mysql_fetch_assoc($exe_grupo);
	
	 if (mysql_num_rows($exe_grupo) == 0)
	{
		$grupo = "";
	}else{

	$grupo = "[<span class='grupoclass'>".$arr_grupo["name"]."</span>]";
}
    $dispname .= "<b>$grupo</b>";
		if ($row["banned"] == "yes")
					$dispname .= " <b>[<font color='red'>Este torrent foi bloqueado para novos downloads!</font>]</b>";	
	
				$balon =($row["screens1"] ? "" . htmlspecialchars($row["screens1"]) : "images/nocover.jpg");
                             
				///adultos				  
			 if ($row["category"] == 47 || $row["category"] == 106 ){			  

              			     print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img src=images/conteudoo.png width=150 height=150 ></td><td><div align=left>Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");    
				 }
            ///filmes		 
	           elseif ($row["category"] == 2 || $row["category"] == 3 || $row["category"] == 4 || $row["category"] == 5 || $row["category"] == 6 || $row["category"] == 7 || $row["category"] == 23 || $row["category"] == 24 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 36 || $row["category"] == 37 || $row["category"] == 39 || $row["category"] == 40 || $row["category"] == 41 || $row["category"] == 42 || $row["category"] == 49 || $row["category"] == 95 || $row["category"] == 96 || $row["category"] == 97 || $row["category"] == 98 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 101 || $row["category"] == 103 || $row["category"] == 110 || $row["category"] == 118 || $row["category"] == 114 || $row["category"] == 117 || $row["category"] == 120 || $row["category"] == 124 || $row["category"] == 112) 
	              {			  
			     print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                        }	
						///Cursos
				        elseif ($row["category"] == 9 || $row["category"] == 109 || $row["category"] == 113 ) 
	              {			
                         print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");				  
                        }	
										///Cursos videos
				        elseif ($row["category"] == 111 ) 
	              {			
                         print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");				  
                        }	
						///revista xx
						elseif ( $row["category"] == 104) 
	              {			
                         print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img src=images/conteudoo.png width=150 height=150 ></td><td><div align=left>Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");				  
                        }
				 			///jogos
				       elseif ($row["category"] == 10 || $row["category"] == 11 || $row["category"] == 12 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 15 || $row["category"] == 16 || $row["category"] == 43 || $row["category"] == 44 ||   $row["category"] == 120  || $row["category"] == 121  || $row["category"] == 105)    
	              {			
                        print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Plataforma: &lt;B&gt;" .$row["cat_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Genero: &lt;B&gt;" .$row["jogosgenero_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                        }	
						 			///aplicativos
				      elseif ($row["category"] == 18 || $row["category"] == 20 || $row["category"] == 94 || $row["category"] == 115 || $row["category"] == 122 || $row["category"] == 123 ) 
	              {			  
                        print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extensão: &lt;B&gt;" .$row["apliformarq_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Crack: &lt;B&gt;" .$row["aplicrack_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                        }	
							 			///músicas
				      elseif ($row["category"] == 51 || $row["category"] == 52 || $row["category"] == 82 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 57 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 61 || $row["category"] == 62 || $row["category"] == 64 || $row["category"] == 65 || $row["category"] == 66 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89 || $row["category"] == 90 || $row["category"] == 91 ) 
	              {			  
                  print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extensão: &lt;B&gt;" .$row["musicatensao_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["musicaqualidade_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                   	   }		
								    
					elseif ($row["category"] == 108 ) 
	              {			  
				  print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");	
                        }
				 else{
             	 print("<div style='text-align: left; min-width: 200px; width: 757px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");	
}					  
								  
			
			break;
			case 'dl':
				print("<div style='width: 60px;' class='divlista'><div style='width: 60px;'><a href=\"download.php?id=$id\"><img src='" . $site_config['SITEURL'] . "/images/icon_download.gif' border='0' alt=\"Download .torrent\" /></a></div></div>");
			break;
			case 'comments':
				print("<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><a href='comments.php?type=torrent&amp;id=$id'>" . number_format($row["comments"]) . "</a></div></div>\n");
			break;
			case 'size':
				print("<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'>".mksize($row["size"])."</div></div>\n");
			break;
			case 'completed':    
				print("<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><font color='orange'><b>".number_format($row["times_completed"])."</b></font></div></div>");
			break;
			case 'seeders':
				print("<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><font color='green'><b>".number_format($row["seeders"])."</b></font></div></div>\n");
			break;
			case 'leechers':
				print("<div style='width: 60px;' class='divhead'><div style='width: 60px; font-size: 10px;'><font color='#ff0000'><b>" . number_format($row["leechers"]) . "</b></font></div></div>\n");
			break;
		




		}
		if ($x == 2)
			$x--;
		else
			$x++;
	}
	
		print("</li>");




	}

	print("</ul>");

}

function where ($scriptname = "index", $userid, $update=1){
	if (!is_valid_id($userid))
		die;
	if (preg_match("/torrents-details/i", $scriptname))
		$where = "Browsing Torrents Details (ID: $_GET[id])...";
	elseif (preg_match("/torrents.php/i", $scriptname))
		$where = "Browsing Torrents...";
	elseif (preg_match("/account-details/i", $scriptname))
		$where = "Browsing Account Details (ID: $_GET[id])...";
	elseif (preg_match("/torrents-upload/i", $scriptname))
		$where = "Uploading Torrent..";
	elseif (preg_match("/account/i", $scriptname))
		$where = "Browsing User Control Panel...";
	elseif (preg_match("/torrents-search/i", $scriptname))
		$where = "Searching Torrents...";
	elseif (preg_match("/forums/i", $scriptname))
		$where = "Browsing Forums...";
	elseif (preg_match("/index/i", $scriptname))
		$where = "Browsing Homepage...";
	else
		$where = "Unknown Location...";


		return $where;
}



function get_user_class(){
	return $GLOBALS["CURUSER"]["class"];
}




function strtobytes ($str) {
	$str = trim($str);
	if (!preg_match('!^([\d\.]+)\s*(\w\w)?$!', $str, $matches))
		return 0;

	$num = $matches[1];
	$suffix = strtolower($matches[2]);
	switch ($suffix) {
		case "tb": // TeraByte
			return $num * 1099511627776;
		case "gb": // GigaByte
			return $num * 1073741824;
		case "mb": // MegaByte
			return $num * 1048576;
		case "kb": // KiloByte
			return $num * 1024;
		case "b": // Byte
		default:
			return $num;
	}
}

function cleanstr ($s) {
	if (function_exists("filter_var")) {
		return filter_var($s, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	} else {
		return preg_replace('/[\x00-\x1F]/', "", $s);
	}
}

function is_ipv6 ($s) {
	return is_int(strpos($s, ":"));
}

// Taken from php.net comments
function ip2long6($ipv6) {
  $ip_n = inet_pton($ipv6);
  $bits = 15; // 16 x 8 bit = 128bit
  while ($bits >= 0) {
    $bin = sprintf("%08b",(ord($ip_n[$bits])));
    $ipv6long = $bin.$ipv6long;
    $bits--;
  }
  return gmp_strval(gmp_init($ipv6long,2),10);
}

function long2ip6($ipv6long) {

  $bin = gmp_strval(gmp_init($ipv6long,10),2);
  if (strlen($bin) < 128) {
    $pad = 128 - strlen($bin);
    for ($i = 1; $i <= $pad; $i++) {
    $bin = "0".$bin;
    }
  }
  $bits = 0;
  while ($bits <= 7) {
    $bin_part = substr($bin,($bits*16),16);
    $ipv6 .= dechex(bindec($bin_part)).":";
    $bits++;
  }
  // compress

  return inet_ntop(inet_pton(substr($ipv6,0,-1)));
} 

function passhash ($text) {
	GLOBAL $site_config;

	switch (strtolower($site_config["passhash_method"])) {
		case "sha1":
			return sha1($text);
		break;
		case "md5":
			return md5($text);
		break;
		case "hmac":
			$ret = hash_hmac($site_config["passhash_algorithm"], $text, $site_config["passhash_salt"]);
			return $ret ? $ret : die("Config Error: Unknown algorithm '$site_config[passhash_algorithm]'");
		break;
		default:
			die("Unrecognised passhash_method. Check your config.");
	}
}


     
?>