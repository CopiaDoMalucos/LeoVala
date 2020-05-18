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
require_once(BACKEND."/languages.php");

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

	$lng_a = mysql_fetch_assoc(SQL_Query_exec("select uri from languages where id='" .  ( $CURUSER ? $CURUSER['language'] : $site_config['default_language'] )  . "'"));
	$LANGUAGE = $lng_a["uri"];
	
	if (is_readable("languages/$LANGUAGE")) {
     require_once("languages/$LANGUAGE");
}
	if ($autoclean)
		autoclean();
}

function get_dt_num(){
	return gmdate("YmdHis");
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
			echo "<html><head><title>Forbidden</title></head><body><h1>Forbidden</h1>Unauthorized IP address.<br />".
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
    global $site_config, $CURUSER, $THEME, $LANGUAGE, $TTCache;  //Define globals

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
    global $site_config, $CURUSER, $THEME, $LANGUAGE, $TTCache;  //Define globals

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
	begin_framec("<font color='#ff0000'>". htmlspecialchars($title) ."</font>");
	print("<p align='center'><b>" . $message . "</b></p>\n");
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

/**
 * Scrape torrent and return stats
 *
 * @param $scrape
 *   string: Scrape URL
 * @param $hash
 *   string: SHA1 hash (info_hash) of torrent
 * @return
 *   array:
 *     All -1 if failed
 *     - seeds: integer - number of seeders
 *     - leechers: integer - number of leechers
 *     - downloaded: integer - number of complete downloads
 *
 */
function torrent_scrape_url($scrape, $hash) {
	if (function_exists("curl_exec")) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt ($ch, CURLOPT_URL, $scrape.'?info_hash='.escape_url($hash));
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$fp = curl_exec($ch);
		curl_close($ch);
	} else {
		ini_set('default_socket_timeout',10); 
		$fp = @file_get_contents($scrape.'?info_hash='.escape_url($hash));
	}
	$ret = array();
	if ($fp) {
		$stats = BDecode($fp);
		$binhash = pack("H*", $hash);
		$seeds = $stats['files'][$binhash]['complete'];
		$peers = $stats['files'][$binhash]['incomplete'];
		$downloaded = $stats['files'][$binhash]['downloaded'];
		$ret['seeds'] = $seeds;
		$ret['peers'] = $peers;
		$ret['downloaded'] = $downloaded;
	}
	if ($ret['seeds'] === null) {
		$ret['seeds'] = -1;
		$ret['peers'] = -1;
		$ret['downloaded'] = -1;
	}

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
		header('location:http://www.brshares.com/account-login.php?returnto=%2Findex.php'); die();
		
		exit();
	}
}

function validfilename($name) {
    return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
}

function validemail($email) {
	if (function_exists("filter_var"))
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	return preg_match('/^([a-z0-9._-](\+[a-z0-9])*)+@[a-z0-9.-]+\.[a-z]{2,6}$/i', $email);
}

function mksecret($len = 20) {
	$chars = array_merge(range(0, 9), range("A", "Z"), range("a", "z"));
	shuffle($chars);
	$x = count($chars) - 1;
	for ($i = 1; $i <= $len; $i++)
		$str .= $chars[mt_rand(0, $x)];
	return $str;
}

function deletetorrent($id) {
	global $site_config;

	$row = @mysql_fetch_assoc(@SQL_Query_exec("SELECT image1,image2 FROM torrents WHERE id=$id"));

	foreach(explode(".","peers.comments.ratings.files.announce") as $x)
		SQL_Query_exec("DELETE FROM $x WHERE torrent = $id");

	SQL_Query_exec("DELETE FROM completed WHERE torrentid = $id");

    if (file_exists($site_config["torrent_dir"] . "/$id.torrent"))
        unlink($site_config["torrent_dir"] . "/$id.torrent");
    
    if ($row["image1"]) {                             
        unlink($site_config['torrent_dir'] . "/images/" . $row["image1"]);
    }
    
    if ($row["image2"]) {
        unlink($site_config['torrent_dir'] . "/images/" . $row["image2"]);
    }

	@unlink($site_config["nfo_dir"]."/$id.nfo");

	SQL_Query_exec("DELETE FROM torrents WHERE id = $id");
    SQL_Query_exec("DELETE FROM reports WHERE votedfor = $id AND type = 'torrent'");
	SQL_Query_exec("DELETE FROM coins WHERE torrentid = $id");
	SQL_Query_exec("DELETE FROM bookmarks WHERE torrentid = $id");
}

function deleteaccount($userid) 
{
	SQL_Query_exec("DELETE FROM users WHERE id = $userid");
	SQL_Query_exec("DELETE FROM warnings WHERE userid = $userid");
	SQL_Query_exec("DELETE FROM ratings WHERE user = $userid");
	SQL_Query_exec("DELETE FROM peers WHERE userid = $userid");
    SQL_Query_exec("DELETE FROM completed WHERE userid = $userid"); 
    SQL_Query_exec("DELETE FROM reports WHERE addedby = $userid");
    SQL_Query_exec("DELETE FROM reports WHERE votedfor = $userid AND type = 'user'");
    SQL_Query_exec("DELETE FROM forum_readposts WHERE userid = $userid");
    SQL_Query_exec("DELETE FROM pollanswers WHERE userid = $userid");
	SQL_Query_exec("DELETE FROM friends WHERE friendid = $userid OR userid = $userid");
	SQL_Query_exec("DELETE FROM bookmarks WHERE userid = $userid");
}


function genrelist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories ORDER BY parent_cat ASC, sort_index ASC");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function kittipocat() {
    $ret = array();
        $res = SQL_Query_exec("SELECT id, name, parent_cat FROM kittipo ORDER BY parent_cat ASC, sort_index ASC");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
 
function genrelist1() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Filmes' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist2() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Aplicativos' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist3() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Músicas' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist4() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Jogos' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist5() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='VideoClipes' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist6() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Televisão' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist7() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Shows' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist8() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Séries' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist9() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Livros/Revist' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist10() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  parent_cat='Fotos' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}

function genrelist11() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  id='9' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}

function genrelist12() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  id='111' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist13() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  id='47' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist14() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  id='106' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist15() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  id='104' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
function genrelist16() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE  id='108' ");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}

/////Filme Ano de lançamento
function anoslist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmeano ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Ano de lançamento
/////Filme audio list
function filmeaudilist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmeaudio ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme audio list
/////Filme Extensão list
function filmeextelist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmeextensao ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Extensão list
/////Filme Qualidade list
function filmequalidlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmequalidade ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Qualidade list
/////Filme Filme em 3D list
function filme3dlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filme3d ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Filme em 3D list
/////Filme Filme Codecs de Vídeo list
function filmecodvilist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmecodecvid ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Codecs de Vídeo list
/////Filme Filme Codecs de Audio list
function filmecodecaudlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmecodecaud ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Codecs de Audio list
/////Filme Filme Idioma Original list
function filmeidiorilist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmeidiomaorigi ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Idioma Original list
/////Filme Duração hora list
function filmedurhorlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmeduracaoh ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Duração hora list
/////Filme Duração minutos list
function filmeduraminulist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM filmeduracaomi ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme Duração minutos list
/////Filme legenda list
function legendalist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM legenda ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Filme legenda list
/////aplicativo list formato
function aplicatilist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM apliformarq ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////aplicativo list formato

/////aplicativo list formato Crack
function aplicaticracklist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM aplicrack ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////aplicativo list formato Crack

/////Extensão músicas 
function musicasextlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM musicatensao ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Extensão músicas fim


/////Qaulidade músicas 
function musicasqualilist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM musicaqualidade ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////Qualidade músicas fim

/////jogos genero
function jogosgenerolist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM  jogosgenero ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////jogos genero fim
/////jogos formato
function jogosformatlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM  jogosformato ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////jogos formato fim
/////jogos multi
function jogosmultiplaytilist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM  jogosmultiplay ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////jogos multi
/////musicas extensão
function revistalist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name FROM  revistatensao ORDER BY sort_index, id");
    while ($row = mysql_fetch_array($res))
        $ret[] = $row;
    return $ret;
}
/////musicas extensão

function langlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT id, name, image FROM torrentlang ORDER BY sort_index, id");
    while ($row = mysql_fetch_assoc($res))
        $ret[] = $row;
    return $ret;
}

function is_valid_id($id){
	return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

function is_valid_int($id){
	return is_numeric($id) && (floor($id) == $id);
}

function sql_timestamp_to_unix_timestamp($s){
	return mktime(substr($s, 11, 2), substr($s, 14, 2), substr($s, 17, 2), substr($s, 5, 2), substr($s, 8, 2), substr($s, 0, 4));
}

function write_loguser($type,$couleur,$text){

	$text = sqlesc($text);
	$type = sqlesc($type);
	$couleur = sqlesc($couleur);
	$added = sqlesc(get_date_time());
	SQL_Query_exec("INSERT INTO loguser (added, type, couleur, txt) VALUES($added, $type, $couleur, $text)");
}

function write_log($type,$couleur,$text){

	$text = sqlesc($text);
	$type = sqlesc($type);
	$couleur = sqlesc($couleur);
	$added = sqlesc(get_date_time());
	SQL_Query_exec("INSERT INTO log (added, type, couleur, txt) VALUES($added, $type, $couleur, $text)");
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



function guestadd() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = gmtime();
    SQL_Query_exec("INSERT INTO `guests` (`ip`, `time`) VALUES ('$ip', '$time') ON DUPLICATE KEY UPDATE `time` = '$time'");
}
                                
function getguests() {
    $past = (gmtime() - 2400);
    SQL_Query_exec("DELETE FROM `guests` WHERE `time` < $past");
    return get_row_count("guests");
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
		return $date->format('Y-m-d H:i:s');
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
	$s = format_urls($s);
	//[align=(center|left|right|justify)]text[/align]
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

     //[hr]
        $s = preg_replace("/\[hr\]/i", "<hr />", $s);

     //[hr=#ffffff] [hr=red]
        $s = preg_replace("/\[hr=((#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])|([a-zA-z]+))\]/i", "<hr color=\"\\1\"/>", $s);

        //[swf]http://somesite.com/test.swf[/swf]
        $s = preg_replace("/\[swf\]((www.|http:\/\/|https:\/\/)[^\s]+(\.swf))\[\/swf\]/i",
        "<param name='movie' value='\\1'/><embed width='470' height='310' src='\\1'></embed>", $s);

        //[swf=http://somesite.com/test.swf]
        $s = preg_replace("/\[swf=((www.|http:\/\/|https:\/\/)[^\s]+(\.swf))\]/i",
        "<param name='movie' value='\\1'/><embed width='470' height='310' src='\\1'></embed>", $s);
		 
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

		
	// Linebreaks
	$s = nl2br($s);

	// Maintain spacing
	$s = str_replace("  ", " &nbsp;", $s);

	// Smilies
	require_once("smilies.php");
	reset($smilies);
	while (list($code, $url) = each($smilies))
        $s = str_replace($code, '<img border="0" src="'.$site_config["SITEURL"].'/images/smilies/'.$url.'" alt="'.$code.'" title="'.$code.'" />', $s);

	if($site_config["OLD_CENSOR"])
	{
    $r = SQL_Query_exec("SELECT * FROM censor");
	while($rr=mysql_fetch_row($r))
	$s = preg_replace("/".preg_quote($rr[0])."/i", $rr[1], $s);
    }
	else
	{
	
    $f = @fopen("censor.txt","r");
    
    if ($f && filesize("censor.txt") != 0) {
    
       $bw = fread($f, filesize("censor.txt"));
       $badwords = explode("\n",$bw);
       
       for ($i=0; $i<count($badwords); ++$i)
           $badwords[$i] = trim($badwords[$i]);
       $s = str_replace($badwords, "<img src='images/censored.png' border='0' alt='Censored' title='Censored' />", $s);
    }
    @fclose($f);
	}

	return $s;
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
	
	// Expanding Area
	$expandrows = array();
	if (!empty($site_config["torrenttable_expand"])) {
		$expandrows = explode(",", $site_config["torrenttable_expand"]);
		$expandrows = array_map("strtolower", $expandrows);
		$expandrows = array_map("trim", $expandrows);
	}
	// End
	
	echo '<table align="center" cellpadding="0" cellspacing="0" class="ttable_headinner" width="99%"><thead><tr>';

	foreach ($cols as $col) {
		switch ($col) {
			case 'category':
				echo "<th class='ttable_head'>".T_("TYPE")."</th>";
			break;
			case 'name':
				echo "<th class='ttable_head'>".T_("NAME")."</th>";
			break;
			case 'dl':
				echo "<th class='ttable_head'>".T_("DL")."</th>";
			break;
			case 'comments':
				echo "<th class='ttable_head'>".T_("COMM")."</th>";
			break;
			case 'nfo':
				echo "<th class='ttable_head'>".T_("NFO")."</th>";
			break;
			case 'size':
				echo "<th class='ttable_head'>".T_("SIZE")."</th>";
			break;
			case 'completed':
				echo "<th class='ttable_head'>".T_("C")."</th>";
			break;
			case 'seeders':
				echo "<th class='ttable_head'>".T_("S")."</th>";
			break;
			case 'leechers':
				echo "<th class='ttable_head'>".T_("L")."</th>";
			break;
			case 'added':
				echo "<th class='ttable_head'>".T_("ADDED")."</th>";
			break;


		}
	}
	if ($wait && !in_array("wait", $cols))
		echo "<th class='ttable_head'>".T_("WAIT")."</th>";
	
	echo "</tr></thead>";

	while ($row = mysql_fetch_assoc($res)) {
		$id = $row["id"];

		print("<tr>\n");

	$x = 1;

	foreach ($cols as $col) {
		switch ($col) {
			case 'category':
				print("<td class='ttable_col$x' align='center' valign='middle'>");
				if (!empty($row["cat_name"])) {
					print("<a href=\"torrents.php?cat=" . $row["category"] . "\">");
					if (!empty($row["cat_pic"]) && $row["cat_pic"] != "")
						print("<img border=\"0\"src=\"" . $site_config['SITEURL'] . "/images/categories/" . $row["cat_pic"] . "\" alt=\"" . $row["cat_name"] . "\" title=\"Categoria: " . $row["cat_name"] . "\" />");
					else
						print($row["cat_parent"].": ".$row["cat_name"]);
					print("</a>");
				} else
					print("-");
				print("</td>\n");
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
					

					if ($row["banned"] == "yes")
					$dispname .= " <b>[<font color='red'>Este torrent foi bloqueado para novos downloads!</font>]</b>";	
	
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

				$balon =($row["screens1"] ? "" . htmlspecialchars($row["screens1"]) : "images/nocover.jpg");
                             
				///adultos				  
			 if ($row["category"] == 47 || $row["category"] == 106 ){			  

              			     print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img src=images/conteudoo.png width=150 height=150 ></td><td><div align=left>Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");    
				 }
            ///filmes		 
	           elseif ($row["category"] == 2 || $row["category"] == 3 || $row["category"] == 4 || $row["category"] == 5 || $row["category"] == 6 || $row["category"] == 7 || $row["category"] == 23 || $row["category"] == 24 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 36 || $row["category"] == 37 || $row["category"] == 39 || $row["category"] == 40 || $row["category"] == 41 || $row["category"] == 42 || $row["category"] == 49 || $row["category"] == 95 || $row["category"] == 96 || $row["category"] == 97 || $row["category"] == 98 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 101 || $row["category"] == 103 || $row["category"] == 110 || $row["category"] == 118 || $row["category"] == 114 || $row["category"] == 117 || $row["category"] == 120 || $row["category"] == 124 || $row["category"] == 112) 
	              {			  
			     print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");
                        }	
						///Cursos
				        elseif ($row["category"] == 9 || $row["category"] == 109 || $row["category"] == 113 ) 
	              {			
                         print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");				  
                        }	
										///Cursos videos
				        elseif ($row["category"] == 111 ) 
	              {			
                         print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");				  
                        }	
						///revista xx
						elseif ( $row["category"] == 104) 
	              {			
                         print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img src=images/conteudoo.png width=150 height=150 ></td><td><div align=left>Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");				  
                        }
				 			///jogos
				       elseif ($row["category"] == 10 || $row["category"] == 11 || $row["category"] == 12 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 15 || $row["category"] == 16 || $row["category"] == 43 || $row["category"] == 44 ||   $row["category"] == 120  || $row["category"] == 121  || $row["category"] == 105)    
	              {			
                        print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Plataforma: &lt;B&gt;" .$row["cat_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Genero: &lt;B&gt;" .$row["jogosgenero_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");
                        }	
						 			///aplicativos
				      elseif ($row["category"] == 18 || $row["category"] == 20 || $row["category"] == 94 || $row["category"] == 115 || $row["category"] == 122 || $row["category"] == 123 ) 
	              {			  
                        print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extensão: &lt;B&gt;" .$row["apliformarq_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Crack: &lt;B&gt;" .$row["aplicrack_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");
                        }	
							 			///músicas
				      elseif ($row["category"] == 51 || $row["category"] == 52 || $row["category"] == 82 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 57 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 61 || $row["category"] == 62 || $row["category"] == 64 || $row["category"] == 65 || $row["category"] == 66 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89 || $row["category"] == 90 || $row["category"] == 91 ) 
	              {			  
                  print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extensão: &lt;B&gt;" .$row["musicatensao_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["musicaqualidade_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");
                   	   }		
								    
					elseif ($row["category"] == 108 ) 
	              {			  
				  print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");	
                        }
				 else{
             	 print("<td class=ttable_col$x nowrap>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a>");	
}					  
								  
			
			break;
			case 'dl':
				print("<td class=ttable_col$x align=center><a href=\"download.php?id=$id\"><img src=" . $site_config['SITEURL'] . "/images/icon_download.gif border=0 alt=\"Download .torrent\"></a></td>");
			break;
			case 'uploader':
				echo "<td class=ttable_col$x align=center>";
				if (($row["anon"] == "yes" || $row["privacy"] == "strong") && $CURUSER["id"] != $row["owner"] && $CURUSER["edit_torrents"] != "yes")
					echo "Anonymous";
				elseif ($row["username"])
					echo "<a href='account-details.php?id=$row[owner]'>$row[username]</a>";
				else
					echo "Unknown";
				echo "</td>";
			break;
			case 'comments':
				print("<td class='ttable_col$x' align='center'><font size='1' face='verdana'><a href='comments.php?type=torrent&amp;id=$id'>" . number_format($row["comments"]) . "</a></font></td>\n");
			break;

			case 'size':
				print("<td class='ttable_col$x' align='center'>".mksize($row["size"])."</td>\n");
			break;
			case 'completed':    
				print("<td class='ttable_col$x' align='center'><font color='orange'><b>".number_format($row["times_completed"])."</b></font></td>");
			break;
			case 'seeders':
				print("<td class='ttable_col$x' align='center'><font color='green'><b>".number_format($row["seeders"])."</b></font></td>\n");
			break;
			case 'leechers':
				print("<td class='ttable_col$x' align='center'><font color='#ff0000'><b>" . number_format($row["leechers"]) . "</b></font></td>\n");
			break;


			case 'added':
				print("<td class='ttable_col$x' align='center'>".date("d-m-Y H:i:s", utc_to_tz_time($row['added']))."</td>");
			break;

		}
		if ($x == 2)
			$x--;
		else
			$x++;
	}

	

		
		print("</tr>\n");

		//Expanding area
		if (count($expandrows)) {
			print("<tr><td class='ttable_col$x' colspan='$colspan'><div id=\"kt".$row['id']."\" style=\"margin-left: 2px; display: none;\">");
			print("<table width='100%' border='0' cellspacing='0' cellpadding='0'>");
			foreach ($expandrows as $expandrow) {
				switch ($expandrow) {
					case 'size':
						print("<tr><td><b>".T_("SIZE")."</b>: ".mksize($row['size'])."</td></tr>");
					break;
				
					case 'added':
						print("<tr><td><b>".T_("ADDED").":</b> ".date("d-m-Y \\a\\t H:i:s", utc_to_tz_time($row['added']))."</td></tr>");
					break;
					case 'tracker':
					if ($row["external"] == "yes")
						print("<tr><td><b>".T_("TRACKER").":</b> ".htmlspecialchars($row["announce"])."</td></tr>");
					break;
					case 'completed':
						print("<tr><td><b>".T_("COMPLETED")."</b>: ".number_format($row['times_completed'])."</td></tr>");
					break;
				}
			}
				print("</table></div></td></tr>\n");
		}
		//End Expanding Area


	}

	print("</table><br />\n");

}

function pager($rpp, $count, $href, $opts = array()) {
    $pages = ceil($count / $rpp);

    if (!$opts["lastpagedefault"])
        $pagedefault = 0;
    else {
        $pagedefault = floor(($count - 1) / $rpp);
        if ($pagedefault < 0)
            $pagedefault = 0;
    }

    if (isset($_GET["page"])) {
        $page = (int) $_GET["page"];
        if ($page < 0)
            $page = $pagedefault;
    }
    else
        $page = $pagedefault;

    $pager = "";

    $mp = $pages - 1;
    $as = "<b>&lt;&lt;&nbsp;".T_("PREVIOUS")."</b>";
    if ($page >= 1) {
        $pager .= "<a href=\"{$href}page=" . ($page - 1) . "\">";
        $pager .= $as;
        $pager .= "</a>";
    }
    else
        $pager .= $as;
    $pager .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $as = "<b>".T_("NEXT")."&nbsp;&gt;&gt;</b>";
    if ($page < $mp && $mp >= 0) {
        $pager .= "<a href=\"{$href}page=" . ($page + 1) . "\">";
        $pager .= $as;
        $pager .= "</a>";
    }
    else
        $pager .= $as;

    if ($count) {
        $pagerarr = array();
        $dotted = 0;
        $dotspace = 3;
        $dotend = $pages - $dotspace;
        $curdotend = $page - $dotspace;
        $curdotstart = $page + $dotspace;
        for ($i = 0; $i < $pages; $i++) {
            if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
                if (!$dotted)
                    $pagerarr[] = "...";
                $dotted = 1;
                continue;
            }
            $dotted = 0;
            $start = $i * $rpp + 1;
            $end = $start + $rpp - 1;
            if ($end > $count)
                $end = $count;
            $text = "$start&nbsp;-&nbsp;$end";
            if ($i != $page)
                $pagerarr[] = "<a href=\"{$href}page=$i\"><b>$text</b></a>";
            else
                $pagerarr[] = "<b>$text</b>";
        }
        $pagerstr = join(" | ", $pagerarr);
        $pagertop = "<p align=\"center\">$pager<br />$pagerstr</p>\n";
        $pagerbottom = "<p align=\"center\">$pagerstr<br />$pager</p>\n";
    }
    else {
        $pagertop = "<p align=\"center\">$pager</p>\n";
        $pagerbottom = $pagertop;
    }

    $start = $page * $rpp;

    return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
}

function commenttable($res, $type = null) {
	global $site_config, $CURUSER, $THEME, $LANGUAGE;  //Define globals

	while ($row = mysql_fetch_assoc($res)) {
	$res123 = SQL_Query_exec("SELECT * FROM users WHERE id=".$row['user'].""); 
		$arr123 = mysql_fetch_assoc($res123);
		$postername123 = $arr123["username"];
$datetime1 = get_date_time(gmtime() - 180);		
$online = "<img src=images/button_o".($arr123["last_access"] > $datetime1 ? "n":"ff")."line.gif>";
                
//$numtorrents
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE owner=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numtorrents = $arr1[0];

//$numcomments
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM comments WHERE user=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numcomments = $arr1[0];
			$placa = $row;

if ($row["class"] == 95){
$placa="&nbsp;<img src=images/adm.png alt=Administrador	 title=Administrador border=0>";}

elseif ($row["class"] == 85){
$placa="&nbsp;<img src=images/MODERADOR.png alt=Moderador title=Moderador border=0>";}

elseif ($row["class"] == 75){
$placa="&nbsp;<img src=images/LIBERADOR-DE-TORRENTS.png alt=Liberador de Torrents title=Liberador de Torrents border=0>";}

elseif ($row["class"] == 80){
$placa="&nbsp;<img src=images/COLABORADOR.png alt=Colaborador title=Colaborador border=0>";}

elseif ($row["class"] == 70){
$placa="&nbsp;<img src=images/DESIGNER.png alt=Designer title=Designer border=0>";}

elseif ($row["class"] == 50){
$placa="&nbsp;<img src=images/UPLOADER.png alt=Uploader title=Uploader border=0>";}

elseif ($row["class"] == 65){
$placa="&nbsp;<img src=images/MODERADOR-DE-GRUPO.png alt=Moderador de Grupo title=Moderador de Grupo border=0>";}

elseif ($row["class"] == 60){
$placa="&nbsp;<img src=images/SUB-MODERADOR-DE-GRUPO.png alt=Sub Moderador de Grupo title=Sub Moderador de Grupo border=0>";}

elseif ($row["class"] == 55){
$placa="&nbsp;<img src=images/MEMBRO-DE-GRUPO.png alt=Membro de Grupo title=Membro de Grupo border=0>";}

elseif ($row["class"] == 5){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Ouro title=Vip Ouro border=0>";}

elseif ($row["class"] == 4){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Prata title=Vip Prata border=0>";}

elseif ($row["class"] == 3){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Bronze title=Vip Bronze border=0>";}

elseif ($row["class"] == 2){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip title=Vip border=0>";}

elseif ($row["class"] == 20){
$placa="&nbsp;<img src=images/USUARIO.png alt=Usuário title=Usuário border=0>";}

else{
$placa="";

}
		$postername = htmlspecialchars($row["username"]);
		if ($postername == "") {
			$postername = T_("DELUSER");
			$title = T_("DELETED_ACCOUNT");
			
			$avatar = "";
			$usersignature = stripslashes(format_comment($row["signature"]));
			$userdownloaded = "";
			$useruploaded = "";
		}else {
			$privacylevel = $row["privacy"];
			$avatar = htmlspecialchars($row["avatar"]);
			$title =  format_comment($row["title"]);
			$usersignature = stripslashes(format_comment($row["signature"]));
			$userdownloaded = mksize($row["downloaded"]);
			$useruploaded = mksize($row["uploaded"]);
		}

		if ($row["downloaded"] > 0)
			$userratio = number_format($row["uploaded"] / $row["downloaded"], 2);
		else
			$userratio = "---";

		if (!$avatar)
			$avatar = $site_config["SITEURL"]."/images/default_avatar.gif";

		$commenttext = format_comment($row["text"]);
    $text = format_comment($row["text"]);
        if ($row["editedby"])
            $text .= "<p><font size=1 class=small>Editado por <a href=userdetails.php?id=$row[editedby]><b>$row[username]</b></a> $row[editedat] GMT</font></p>\n";
        $edit = null;
        if ($type == "torrent" && $CURUSER["edit_torrents"] == "yes" || $type == "news" && $CURUSER["edit_news"] == "yes" || $CURUSER['id'] == $row['user']) 
            $edit = '[<a href="comments.php?id='.$row["id"].'&amp;type='.$type.'&amp;edit=1">Edit</a>]&nbsp;';
       
        $delete = null;
        if ($type == "torrent" && $CURUSER["delete_torrents"] == "yes" || $type == "news" && $CURUSER["class"] == "95")
            $delete = '[<a href="comments.php?id='.$row["id"].'&amp;type='.$type.'&amp;delete=1">Delete</a>]&nbsp;';
        
        print('<table style="border-collapse: collapse;" class="f-border" align="center" cellpadding="3" cellspacing="0" width="100%">');
        print('<tr class="p-title">');
        print('<td align="center" width="17%"></td>');
        print('<td align="right" width="83%">' . $edit . $delete . '[<a href="report.php?comment='.$row["id"].'">Report</a>] Posted: '.date("d-m-Y \\a\\t H:i:s", utc_to_tz_time($row["added"])).'<a id="comment'.$row["id"].'"></a></td>');
        print('</tr>');
        print('<tr valign="top">');
        
            print('<td class="f-border comment-details" align="left" width="17%"><center><b>'.$postername.'</b><br /><i>'.$title.'</i><br /><img width="120" height="150" src="'.$avatar.'" alt="" /><br /><center><br>'.$placa.'<br><center>_____________________</center><br /><br>Ratio:  <B> '.$userratio.' </B><center>[<font color=#FF0000><B> '.$useruploaded.' </font><font color=#00CC00> '.$userdownloaded.' </B></font>]</center><center>_____________________</center></center><center><br>Lançamentos:  <B> ' . number_format($numtorrents) . ' </B></center><center>Comentários:  <B> ' . number_format($numcomments) . ' </B><br></center><br><center><B> '.$online.' </B><br></center><br /><a href="account-details.php?id='.$row["user"].'"><img src="themes/'.$THEME.'/forums/icon_profile.gif" border="" alt="" /></a> <a href="enviarmp.php?receiver='.$row["username"].'"><img src="themes/'.$THEME.'/forums/icon_pm.gif" border="0" alt="" /></a></center></td>'); 
        
        
        print('<td class="f-border comment">'.$commenttext.' <br><br>---------------<br>'.$usersignature.'</td>');
		
        print('</tr>');
        print('</table>');  
        print('<br />');      
	}
}

function where ($scriptname = "index", $userid, $update=1){
	if (!is_valid_id($userid))
		die;
	if (preg_match("/torrents-details/i", $scriptname))
		$where = "Navegação Detalhes Torrents  (ID: $_GET[id])...";
	elseif (preg_match("/torrents.php/i", $scriptname))
		$where = "Torrents navegação...";
	elseif (preg_match("/account-details/i", $scriptname))
		$where = "Navegação Detalhes da Conta (ID: $_GET[id])...";
	elseif (preg_match("/torrents-upload/i", $scriptname))
		$where = "Torrent upload..";
	elseif (preg_match("/account/i", $scriptname))
		$where = "Painel de controle do usuário...";
	elseif (preg_match("/torrents-search/i", $scriptname))
		$where = "Torrents busca...";
	elseif (preg_match("/forums/i", $scriptname))
		$where = "Fóruns navegação...";
	elseif (preg_match("/index/i", $scriptname))
		$where = "navegação Homepage...";
	else
		$where = "Localização desconhecida...";

	if ($update) {
		$query = sprintf("UPDATE users SET page=".sqlesc($where)." WHERE id ='%s'", mysql_real_escape_string($userid));
		$result = SQL_Query_exec($query);
	}
		return $where;
}

function get_user_class_name($i){
	GLOBAL $CURUSER;
	if ($i == $CURUSER["class"])
		return $CURUSER["level"];

	$res=SQL_Query_exec("SELECT level FROM groups WHERE group_id=".$i."");
	$row=mysql_fetch_row($res);
	return $row[0];
}

function get_user_class(){
	return $GLOBALS["CURUSER"]["class"];
}
function reqcommenttable ($rows) {

    GLOBAL $CURUSER;
   
    $count = 0;

    foreach ($rows as $row) {
        if (isset($row["username"])) {
            $username = $row["username"];
            $ratres = mysql_query("SELECT uploaded, downloaded from users where username='$username'");
            $rat = mysql_fetch_array($ratres);
            if ($rat["downloaded"] > 0) {
                $ratio = $rat['uploaded'] / $rat['downloaded'];
                $ratio = number_format($ratio, 3);
                $color = get_ratio_color($ratio);
                if ($color)
                    $ratio = "<font color=$color>$ratio</font>";
            } elseif ($rat["uploaded"] > 0)
                $ratio = "Inf.";
            else
                $ratio = "---";

            $title = $row["title"];
            if ($title == "")
                $title = get_user_class_name($row["class"]);
            else
                $title = htmlspecialchars($title);
            print("<br><br><a ><b></b></a>" . ($row["donor"] == "yes" ? "<img src=images/star.gif alt='Donor'>" : "") . ($row["warned"] == "yes" ? "<img src="."/images/warned.gif alt=\"Warned\">" : "") . " (Ratio: $ratio)\n");
        } else
            print("<a name=\"comm" . $row["id"] . "\"><i>(orphaned)</i></a>\n");
        print(" at " . $row["added"] . " GMT" .($row["user"] == $CURUSER["id"] || get_user_class() >= 5 ? "- [<a href=reqcomment.php.php?action=edit&amp;cid=$row[id]>Edit</a>]" : "") . (get_user_class() >= 10 ? "- [<a href=reqcomment.php?action=delete&amp;cid=$row[id]>Delete</a>]" : "") . ($row["editedby"] && get_user_class() >= 5 ? "- [<a href=reqcomment.php?action=vieworiginal&amp;cid=$row[id]>View original</a>]" : "") . "</p>\n");
        $avatar = ($CURUSER["avatar"] != "" ? htmlspecialchars($row["avatar"]) : "");
        if (!$avatar)
            $avatar = "/images/default_avatar.gif";
			$placa = $row;

if ($row["class"] == 14){
$placa="&nbsp;<img src=images/adm.png alt=Administrador	 title=Administrador border=0>";}
elseif ($row["class"] == 13){
$placa="&nbsp;<img src=images/MODERADOR.png alt=Moderador title=Moderador border=0>";}
elseif ($row["class"] == 12){
$placa="&nbsp;<img src=images/LIBERADOR-DE-TORRENTS.png alt=Liberador de Torrents title=Liberador de Torrents border=0>";}
elseif ($row["class"] == 11){
$placa="&nbsp;<img src=images/COLABORADOR.png alt=Colaborador title=Colaborador border=0>";}
elseif ($row["class"] == 10){
$placa="&nbsp;<img src=images/DESIGNER.png alt=Designer title=Designer border=0>";}
elseif ($row["class"] == 9){
$placa="&nbsp;<img src=images/UPLOADER.png alt=Uploader title=Uploader border=0>";}
elseif ($row["class"] == 8){
$placa="&nbsp;<img src=images/MODERADOR-DE-GRUPO.png alt=Moderador de Grupo title=Moderador de Grupo border=0>";}
elseif ($row["class"] == 7){
$placa="&nbsp;<img src=images/SUB-MODERADOR-DE-GRUPO.png alt=Sub Moderador de Grupo title=Sub Moderador de Grupo border=0>";}
elseif ($row["class"] == 6){
$placa="&nbsp;<img src=images/MEMBRO-DE-GRUPO.png alt=Membro de Grupo title=Membro de Grupo border=0>";}
elseif ($row["class"] == 5){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Ouro title=Vip Ouro border=0>";}
elseif ($row["class"] == 4){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Prata title=Vip Prata border=0>";}
elseif ($row["class"] == 3){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Bronze title=Vip Bronze border=0>";}
elseif ($row["class"] == 2){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip title=Vip border=0>";}
elseif ($row["class"] == 1){
$placa="&nbsp;<img src=images/USUARIO.png alt=Usuário title=Usuário border=0>";}
else{
$placa="";

}
		$postername = htmlspecialchars($row["username"]);
		if ($postername == "") {
			$postername = T_("DELUSER");
			$title = T_("DELETED_ACCOUNT");
			$avatar = "";
			$usersignature = "";
			$userdownloaded = "";
			$useruploaded = "";
			$online = "";
			
		}else {
			$privacylevel = $row["privacy"];
			$avatar = htmlspecialchars($row["avatar"]);
			$title =  htmlspecialchars($row["title"]);
			$usersignature = stripslashes(format_comment($row["signature"]));
			$userdownloaded = mksize($row["downloaded"]);
			$useruploaded = mksize($row["uploaded"]);
			$online = "";
		}

	   $ratres = mysql_query("SELECT uploaded, downloaded, signature from users where username='$username'");
            $rat = mysql_fetch_array($ratres);
					$privacylevel = $row["privacy"];
			$avatar = htmlspecialchars($row["avatar"]);
			$title =  htmlspecialchars($rat["title"]);
			$usersignature = stripslashes(format_comment($rat["signature"]));
			$userdownloaded = mksize($rat["downloaded"]);
			$useruploaded = mksize($rat["uploaded"]);
			$online = "";
            if ($rat["downloaded"] > 0) {
                $ratio = $rat['uploaded'] / $rat['downloaded'];
                $ratio = number_format($ratio, 3);
                $color = get_ratio_color($ratio);
                if ($color)
                    $ratio = "<font color=$color>$ratio</font>";
            } elseif ($rat["uploaded"] > 0)
                $ratio = "Inf.";
            else
                $ratio = "---";
$res123 = SQL_Query_exec("SELECT * FROM users WHERE id=".$row['user'].""); 
		$arr123 = mysql_fetch_assoc($res123);
		$postername123 = $arr123["username"];
$datetime1 = get_date_time(gmtime() - 180);		
$online = "<img src=images/button_o".($arr123["last_access"] > $datetime1 ? "n":"ff")."line.gif>";
 $themedir = "themes/".$GLOBALS["THEME"]."/forums/";               
//$numtorrents
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE owner=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numtorrents = $arr1[0];

//$numcomments
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM comments WHERE user=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numcomments = $arr1[0];
        $text = format_comment($row["text"]);
        if ($row["editedby"])
            $text .= "<p><font size=1 class=small>Editado por <a href=userdetails.php?id=$row[editedby]><b>$row[username]</b></a> $row[editedat] GMT</font></p>\n";
        begin_table(true);
				print("<table border=0 width=100% cellpadding=4>\n");

		print("<tr><td colspan=2 align=right class=table_col1>");
        print("<tr valign=top>\n");
       print ("<tr valign='top'><td width='160' align='left' class='f-border comment-details'><center><b>$postername</b><br><i>$title</i></center><br><center><img width='120' height='150' src='$avatar'></center><center><br>$placa<br><center>_____________________</center><center><br>Ratio:  <B> $userratio </B><center>[<font color=#FF0000><B> $useruploaded </font><font color=#00CC00> $userdownloaded </B></font>]</center><center>_____________________</center></center><center><br>Lançamentos:  <B> " . number_format($numtorrents) . " </B></center><center>Comentários:  <B> " . number_format($numcomments) . " </B><br></center><br><center><B> $online </B><br></center><br><nobr> <a href='account-details.php?id=".$row['user']."'><img src=".$themedir."icon_profile.gif ></a> <a href='enviarmp.php?receiver=".$row['username']."'><img src='".$themedir."icon_pm.gif' ></a> </nobr></td>");
      	print("<td valign=top width='75%' class=table_col2>$text<br><br>---------------<br>$usersignature</td>");

        print("</tr>\n");
        end_table();
    }

}


















function reqcommenttablekit ($rows) {

    GLOBAL $CURUSER;
   
    $count = 0;

    foreach ($rows as $row) {
        if (isset($row["username"])) {
            $username = $row["username"];
            $ratres = mysql_query("SELECT uploaded, downloaded from users where username='$username'");
            $rat = mysql_fetch_array($ratres);
            if ($rat["downloaded"] > 0) {
                $ratio = $rat['uploaded'] / $rat['downloaded'];
                $ratio = number_format($ratio, 3);
                $color = get_ratio_color($ratio);
                if ($color)
                    $ratio = "<font color=$color>$ratio</font>";
            } elseif ($rat["uploaded"] > 0)
                $ratio = "Inf.";
            else
                $ratio = "---";

            $title = $row["title"];
            if ($title == "")
                $title = get_user_class_name($row["class"]);
            else
                $title = htmlspecialchars($title);
            print("<br><br><a ><b></b></a>" . ($row["donor"] == "yes" ? "<img src=images/star.gif alt='Donor'>" : "") . ($row["warned"] == "yes" ? "<img src="."/images/warned.gif alt=\"Warned\">" : "") . " (Ratio: $ratio)\n");
        } else
            print("<a name=\"comm" . $row["id"] . "\"><i>(orphaned)</i></a>\n");
        print(" at " . $row["added"] . " GMT" .($row["user"] == $CURUSER["id"] || get_user_class() >= 5 ? "- [<a href=pkitcomment.php?action=edit&amp;cid=$row[id]>Edit</a>]" : "") . (get_user_class() >= 10 ? "- [<a href=pkitcomment.php?action=delete&amp;cid=$row[id]>Delete</a>]" : "") . ($row["editedby"] && get_user_class() >= 5 ? "- [<a href=reqcomment.php?action=vieworiginal&amp;cid=$row[id]>View original</a>]" : "") . "</p>\n");
        $avatar = ($CURUSER["avatar"] != "" ? htmlspecialchars($row["avatar"]) : "");
        if (!$avatar)
            $avatar = "/images/default_avatar.gif";
			$placa = $row;

if ($row["class"] == 14){
$placa="&nbsp;<img src=images/adm.png alt=Administrador	 title=Administrador border=0>";}
elseif ($row["class"] == 13){
$placa="&nbsp;<img src=images/MODERADOR.png alt=Moderador title=Moderador border=0>";}
elseif ($row["class"] == 12){
$placa="&nbsp;<img src=images/LIBERADOR-DE-TORRENTS.png alt=Liberador de Torrents title=Liberador de Torrents border=0>";}
elseif ($row["class"] == 11){
$placa="&nbsp;<img src=images/COLABORADOR.png alt=Colaborador title=Colaborador border=0>";}
elseif ($row["class"] == 10){
$placa="&nbsp;<img src=images/DESIGNER.png alt=Designer title=Designer border=0>";}
elseif ($row["class"] == 9){
$placa="&nbsp;<img src=images/UPLOADER.png alt=Uploader title=Uploader border=0>";}
elseif ($row["class"] == 8){
$placa="&nbsp;<img src=images/MODERADOR-DE-GRUPO.png alt=Moderador de Grupo title=Moderador de Grupo border=0>";}
elseif ($row["class"] == 7){
$placa="&nbsp;<img src=images/SUB-MODERADOR-DE-GRUPO.png alt=Sub Moderador de Grupo title=Sub Moderador de Grupo border=0>";}
elseif ($row["class"] == 6){
$placa="&nbsp;<img src=images/MEMBRO-DE-GRUPO.png alt=Membro de Grupo title=Membro de Grupo border=0>";}
elseif ($row["class"] == 5){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Ouro title=Vip Ouro border=0>";}
elseif ($row["class"] == 4){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Prata title=Vip Prata border=0>";}
elseif ($row["class"] == 3){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip Bronze title=Vip Bronze border=0>";}
elseif ($row["class"] == 2){
$placa="&nbsp;<img src=images/DOADOR.png alt=Vip title=Vip border=0>";}
elseif ($row["class"] == 1){
$placa="&nbsp;<img src=images/USUARIO.png alt=Usuário title=Usuário border=0>";}
else{
$placa="";

}
		$postername = htmlspecialchars($row["username"]);
		if ($postername == "") {
			$postername = T_("DELUSER");
			$title = T_("DELETED_ACCOUNT");
			$avatar = "";
			$usersignature = "";
			$userdownloaded = "";
			$useruploaded = "";
			$online = "";
			
		}else {
			$privacylevel = $row["privacy"];
			$avatar = htmlspecialchars($row["avatar"]);
			$title =  htmlspecialchars($row["title"]);
			$usersignature = stripslashes(format_comment($row["signature"]));
			$userdownloaded = mksize($row["downloaded"]);
			$useruploaded = mksize($row["uploaded"]);
			$online = "";
		}

	   $ratres = mysql_query("SELECT uploaded, downloaded, signature from users where username='$username'");
            $rat = mysql_fetch_array($ratres);
					$privacylevel = $row["privacy"];
			$avatar = htmlspecialchars($row["avatar"]);
			$title =  htmlspecialchars($rat["title"]);
			$usersignature = stripslashes(format_comment($rat["signature"]));
			$userdownloaded = mksize($rat["downloaded"]);
			$useruploaded = mksize($rat["uploaded"]);
			$online = "";
            if ($rat["downloaded"] > 0) {
                $ratio = $rat['uploaded'] / $rat['downloaded'];
                $ratio = number_format($ratio, 3);
                $color = get_ratio_color($ratio);
                if ($color)
                    $ratio = "<font color=$color>$ratio</font>";
            } elseif ($rat["uploaded"] > 0)
                $ratio = "Inf.";
            else
                $ratio = "---";
$res123 = SQL_Query_exec("SELECT * FROM users WHERE id=".$row['user'].""); 
		$arr123 = mysql_fetch_assoc($res123);
		$postername123 = $arr123["username"];
$datetime1 = get_date_time(gmtime() - 180);		
$online = "<img src=images/button_o".($arr123["last_access"] > $datetime1 ? "n":"ff")."line.gif>";
 $themedir = "themes/".$GLOBALS["THEME"]."/forums/";               
//$numtorrents
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE owner=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numtorrents = $arr1[0];

//$numcomments
$res1 = SQL_Query_exec("SELECT COUNT(*) FROM comments WHERE user=".$row['user']."");
$arr1 = mysql_fetch_row($res1);
$numcomments = $arr1[0];
        $text = format_comment($row["text"]);
        if ($row["editedby"])
            $text .= "<p><font size=1 class=small>Editado por <a href=userdetails.php?id=$row[editedby]><b>$row[username]</b></a> $row[editedat] GMT</font></p>\n";
        begin_table(true);
				print("<table border=0 width=100% cellpadding=4>\n");

		print("<tr><td colspan=2 align=right class=table_col1>");
        print("<tr valign=top>\n");
       print ("<tr valign='top'><td width='160' align='left' class='f-border comment-details'><center><b>$postername</b><br><i>$title</i></center><br><center><img width='120' height='150' src='$avatar'></center><center><br>$placa<br><center>_____________________</center><center><br>Ratio:  <B> $userratio </B><center>[<font color=#FF0000><B> $useruploaded </font><font color=#00CC00> $userdownloaded </B></font>]</center><center>_____________________</center></center><center><br>Lançamentos:  <B> " . number_format($numtorrents) . " </B></center><center>Comentários:  <B> " . number_format($numcomments) . " </B><br></center><br><center><B> $online </B><br></center><br><nobr> <a href='account-details.php?id=".$row['user']."'><img src=".$themedir."icon_profile.gif ></a> <a href='mailbox.php?Escrever&id=".$row['user']."'><img src='".$themedir."icon_pm.gif' ></a> </nobr></td>");
      	print("<td valign=top width='75%' class=table_col2>$text<br><br>---------------<br>$usersignature</td>");

        print("</tr>\n");
        end_table();
    }

}
function get_ratio_color($ratio) {
	if ($ratio < 0.1) return "#ff0000";
	if ($ratio < 0.2) return "#ee0000";
	if ($ratio < 0.3) return "#dd0000";
	if ($ratio < 0.4) return "#cc0000";
	if ($ratio < 0.5) return "#bb0000";
	if ($ratio < 0.6) return "#aa0000";
	if ($ratio < 0.7) return "#990000";
	if ($ratio < 0.8) return "#880000";
	if ($ratio < 0.9) return "#770000";
	if ($ratio < 1) return "#660000";
	return "#000000";
}

function ratingpic($num) {
	GLOBAL $site_config;
    $r = round($num * 2) / 2;
	if ($r != $num) {
		$n = $num-$r;
		if ($n < .25)
			$n = 0;
		elseif ($n >= .25 && $n < .75)
			$n = .5;
		$r += $n;
	}
    if ($r < 1 || $r > 5)
        return;

    return "<img src=\"".$site_config["SITEURL"]."/images/rating/$r.png\" border=\"0\" alt=\"rating: $num/5\" title=\"rating: $num/5\" />";
}

function DateDiff ($start, $end) {
	if (!is_numeric($start))
		$start = sql_timestamp_to_unix_timestamp($start);
	if (!is_numeric($end))
		$end = sql_timestamp_to_unix_timestamp($end);
	return ($end - $start);
}

function classlist() {
    $ret = array();
    $res = SQL_Query_exec("SELECT * FROM groups ORDER BY group_id ASC");
    while ($row = mysql_fetch_assoc($res))
        $ret[] = $row;
    return $ret;
}

function array_map_recursive ($callback, $array) {
	$ret = array();

	if (!is_array($array))
		return $callback($array);

	foreach ($array as $key => $val) {
		$ret[$key] = array_map_recursive($callback, $val);
	}
	return $ret;
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
function rank($i) {
    
    switch ( $i )
    {
        case 1:
         $prefix = 'st'; break;
        case 2:
         $prefix = 'nd'; break;
        case 3:
         $prefix = 'rd'; break;
        default:
         $prefix = 'th'; break;
    }
    
    return $i . $prefix;
}
  function autolink($al_url, $al_msg) {
      stdhead();
      begin_framec("");
      echo "\n<meta http-equiv=\"refresh\" content=\"3; url=$al_url\">\n";
      echo "<b>$al_msg</b>\n";
      echo "\n<b>Redirecting ...</b>\n";
      echo "\n[ <a href='$al_url'>link</a> ]\n";
      end_framec();
      stdfoot();
      exit;
  }

?>