<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
/*
function autoinvites($interval, $minlimit, $maxlimit, $minratio, $invites, $maxinvites) {
	$time = gmtime() - ($interval*86400);
	$minlimit = $minlimit*1024*1024*1024;
	$maxlimit = $maxlimit*1024*1024*1024;
$res = SQL_Query_exec("SELECT id, username, class, invites FROM users WHERE enabled = 'yes' AND status = 'confirmed' AND (downloaded >= '$minlimit') AND (downloaded < '$maxlimit') AND (uploaded / downloaded >= '$minratio') AND warned = 'no' AND UNIX_TIMESTAMP(invitedate) <= '$time'");



	if (mysql_num_rows($res) > 0) {                                                                                                              
		while ($arr = mysql_fetch_assoc($res)) {
			$maxninvites = $maxinvites[$arr['class']];
			if ($arr['invites'] >= $maxninvites)
				continue;
			if (($maxninvites-$arr['invites']) < $invites)
				$invites = $maxninvites - $arr['invites'];

			SQL_Query_exec("UPDATE users SET invites = invites+$invites, invitedate = NOW() WHERE id=$arr[id]");

		}
	}
}
*/
function do_cleanup() {
	global $site_config;
 
	//LOCAL TORRENTS - GET PEERS DATA AND UPDATE BROWSE STATS
	//DELETE OLD NON-ACTIVE PEERS
    $deadtime = get_date_time(gmtime() - $site_config['announce_interval']);
    SQL_Query_exec("DELETE FROM peers WHERE last_action < '$deadtime'");
    
	$torrents = array();
	$res = SQL_Query_exec("SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder");
	while ($row = mysql_fetch_assoc($res)) {
		if ($row["seeder"] == "yes")
			$key = "seeders";
		else
			$key = "leechers";
		$torrents[$row["torrent"]][$key] = $row["c"];
	}

	$res = SQL_Query_exec("SELECT torrent, COUNT(torrent) as c FROM comments WHERE torrent > 0 GROUP BY torrent");
	while ($row = mysql_fetch_assoc($res)) {
		$torrents[$row["torrent"]]["comments"] = $row["c"];
	}

	$fields = explode(":", "comments:leechers:seeders");
	$res = SQL_Query_exec("SELECT id, external, seeders, leechers, comments FROM torrents WHERE banned = 'no'");
	while ($row = mysql_fetch_assoc($res)) {
		$id = $row["id"];
		$torr = $torrents[$id];
		foreach ($fields as $field) {
			if (!isset($torr[$field]))
				$torr[$field] = 0;
		}
		$update = array();
		foreach ($fields as $field) {
			if ($row["external"] == "no" || $field == "comments") {
				if ($torr[$field] != $row[$field])
					$update[] = "$field = " . $torr[$field];
			}
		}
		if (count($update))
			SQL_Query_exec("UPDATE torrents SET " . implode(",", $update) . " WHERE id = $id");
	}

	$bonuspay="1.0";
   $res = SQL_Query_exec("SELECT DISTINCT userid FROM peers WHERE seeder = 'yes'") or die (mysql_error());
   if (mysql_num_rows($res) > 0)
   {
       while ($arr = mysql_fetch_assoc($res))
       {
	   	   SQL_Query_exec("UPDATE users SET seedtime = seedtime + $site_config[autoclean_interval] WHERE id = $arr[userid]") or die (mysql_error());
       SQL_Query_exec("UPDATE users SET seedbonus = seedbonus + $bonuspay WHERE id = $arr[userid]") or die (mysql_error());
       }
   }
   


/*
$date_time1='2013-06-17 03:00:00'; 
   $res_torr9 = SQL_Query_exec("SELECT id, size, times_completed, adota_yes_no, owner FROM  torrents WHERE adota_yes_no = 'no' AND added >= '$date_time1'"); 

   
  while ($arr_torr9 = mysql_fetch_array ($res_torr9)) 
{ 

    
	                   $uploader_rank = (int) 0;
						if($arr_torr9["size"] >= 4294967296 && $arr_torr9["times_completed"] >= 4)
{						$uploader_rank = "8"; 
	   SQL_Query_exec("UPDATE users SET uploader_rank = uploader_rank + $uploader_rank WHERE id = $arr_torr9[owner]");
	   SQL_Query_exec("UPDATE torrents SET adota_yes_no = 'yes' WHERE id = $arr_torr9[id]");
}

						if($arr_torr9["size"] >= 21474836648 && $arr_torr9["times_completed"] >= 6)
{						$uploader_rank = "6";
	   SQL_Query_exec("UPDATE users SET uploader_rank = uploader_rank + $uploader_rank WHERE id = $arr_torr9[owner]");
	   SQL_Query_exec("UPDATE torrents SET adota_yes_no = 'yes' WHERE id = $arr_torr9[id]");
}
						if($arr_torr9["size"] >= 1073741824 && $arr_torr9["times_completed"] >= 8)
						
{						$uploader_rank = "4";
	   SQL_Query_exec("UPDATE users SET uploader_rank = uploader_rank + $uploader_rank WHERE id = $arr_torr9[owner]");
	   SQL_Query_exec("UPDATE torrents SET adota_yes_no = 'yes' WHERE id = $arr_torr9[id]");
}



       }*/
   ////////////
   
//LOCAL TORRENTS - MAKE NON-ACTIVE/OLD TORRENTS INVISIBLE
$deadtime = gmtime() - $site_config["max_dead_torrent_time"];
SQL_Query_exec("UPDATE torrents SET visible='no' WHERE visible='yes' AND last_action < FROM_UNIXTIME($deadtime) AND seeders = '0' AND leechers = '0' AND external !='yes'");


//DELETE PENDING USER ACCOUNTS OVER TIMOUT AGE
$deadtime = gmtime() - $site_config["signup_timeout"];
SQL_Query_exec("DELETE FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime)");

// DELETE OLD LOG ENTRIES
$ts = gmtime() - $site_config["LOGCLEAN"];
SQL_Query_exec("DELETE FROM log WHERE added < FROM_UNIXTIME($ts)");

//LEECHWARN USERS WITH LOW RATIO
				
if ($site_config["ratiowarn_enable"]){
	$minratio = $site_config["ratiowarn_minratio"];
	$downloaded = $site_config["ratiowarn_mingigs"]*1024*1024*1024;
	$length = $site_config["ratiowarn_daystowarn"];



	
	
	//ADD WARNING
	$addwarning = gmtime() - 31 * 86400;//add warning
	$res_warn1 = SQL_Query_exec("SELECT id,username,added FROM users WHERE class = 1 AND warned = 'no' AND enabled='yes' AND added< FROM_UNIXTIME($addwarning) AND (uploaded / downloaded < '$minratio') AND (downloaded >= '$downloaded')");

if (mysql_num_rows($res_warn1) > 0){
$timenow = get_date_time();
		$reason = "Prezado usuário,
		
		Você está sendo advertido pelo sistema, devido a estar com ratio abaixo de 0.80. Você precisa estar com o ratio no mínimo de ".$minratio." em até  ".$length." dias ou sua conta poderá ser excluida.
		
		Para que sua conta não seja excluida, e não seja mais advertida, mantenha semeando os arquivos baixados o máximo possível..
		Em caso de dúvidas procure nossa Equipe.

		Equipe MS";
$expiretime = gmdate("Y-m-d H:i:s", gmtime() + (86400 * $length));
while ($arr_warn1 = mysql_fetch_assoc($res_warn1)){
SQL_Query_exec("INSERT INTO warnings (userid, reason, added, expiry, warnedby, type) VALUES ('".$arr_warn1["id"]."','".$reason."','".$timenow."','".$expiretime."','0','Auto Ratio')");
SQL_Query_exec("UPDATE users SET warned='yes' WHERE id='".$arr_warn1["id"]."'");
SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ('0', '".$arr_warn1["id"]."', '".$timenow."', '".$reason."', '0')");
}
	}
	
	
	
	
	
	
	
	

    //REMOVE WARNING
	$res1 = SQL_Query_exec("SELECT users.id, users.username FROM users INNER JOIN warnings ON users.id=warnings.userid WHERE type='Poor Ratio' AND active = 'yes' AND warned = 'yes'  AND enabled='yes' AND (uploaded / downloaded  >= '$minratio') AND (downloaded >= '$downloaded')");
	if (mysql_num_rows($res1) > 0){                                                                                                                                                                   
		$timenow = get_date_time();
		$reason = "Presado usuário,
		
		Sua advertência de ratio baixo foi removida automaticamente pelo sistema.
		
		Recomendamos que você mantenha seu ratio sempre acima de 0.80 para que não seja advertido novamente.\n
		
		Atenciosamente 
		
		Staff MS";

		while ($arr1 = mysql_fetch_assoc($res1)){

				
			SQL_Query_exec("UPDATE users SET warned = 'no' WHERE id = '".$arr1["id"]."'");
			SQL_Query_exec("UPDATE warnings SET expiry = '$timenow', active = 'no' WHERE userid = $arr1[id]");
			SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES ('0', '".$arr1["id"]."', '".$timenow."', '".$reason."', '0')");
		}
	}

	//BAN WARNED USERS
	$res = SQL_Query_exec("SELECT users.id, users.username, UNIX_TIMESTAMP(warnings.expiry) AS expiry FROM users INNER JOIN warnings ON users.id=warnings.userid WHERE type='Poor Ratio' AND active = 'yes' AND class = 1 AND enabled='yes' AND warned = 'yes' AND (uploaded / downloaded  < '$minratio') AND (downloaded >= '$downloaded')");
                                                                                                                                                                                                                                                                                    
	if (mysql_num_rows($res) > 0){
		$timenow = get_date_time();
		$expires = (86400 * $length);
		while ($arr = mysql_fetch_assoc($res)){
			if (gmtime() - $arr["expiry"] >= 0) {
				///SQL_Query_exec("UPDATE users SET enabled='no', warned='no' WHERE id='".$arr["id"]."'");

			}
		}
	}
	
}//check if warning system is on
// REMOVE WARNINGS
$res = SQL_Query_exec("SELECT users.id, users.username, warnings.expiry FROM users INNER JOIN warnings ON users.id=warnings.userid WHERE type != 'Poor Ratio' AND warned = 'yes'  AND enabled='yes' AND warnings.active = 'yes' AND warnings.expiry < '".get_date_time()."'");
while ($arr1 = mysql_fetch_assoc($res)){
	SQL_Query_exec("UPDATE users SET warned = 'no' WHERE id = $arr1[id]");
	SQL_Query_exec("UPDATE warnings SET active = 'no' WHERE userid = $arr1[id] AND expiry < '".get_date_time()."'");

}
// WARN USERS THAT STILL HAVE ACTIVE WARNINGS
///SQL_Query_exec("UPDATE users SET warned = 'yes' WHERE warned = 'no' AND id IN (SELECT userid FROM warnings WHERE active = 'yes')");
//END//

/*
	// START INVITES UPDATE
	// SET INVITE AMOUNTS ACCORDING TO RATIO/GIGS ETC
	// autoinvites(interval to give invites (days), min downloaded GB, max downloaded GB, min ratio, invites to give, max invites allowed (array))
	// $maxinvites[CLASS ID] = max # of invites;
	$maxinvites[1] = 5;   // User
	$maxinvites[2] = 10;  // Power User
	$maxinvites[3] = 20;  // VIP
	$maxinvites[4] = 25;  // Uploader
	$maxinvites[5] = 100; // Moderator
	$maxinvites[6] = 100; // Super Moderator
	$maxinvites[7] = 400; // Administrator

	// Give 1 invite every 21 days to users with > 1GB downloaded AND < 4GB downloaded AND ratio > 0.50
	autoinvites(21, 1, 4, 0.50, 1, $maxinvites);
	autoinvites(14, 1, 4, 0.90, 2, $maxinvites);
	autoinvites(14, 4, 7, 0.95, 2, $maxinvites);

	$maxinvites[1] = 7; // User
	autoinvites(14, 7, 10, 1.00, 3, $maxinvites);

	$maxinvites[1] = 10; // User
	autoinvites(14, 10, 100000, 1.05, 4, $maxinvites);
	//END INVITES
	*/
    $query = mysql_query("SELECT * FROM torrents WHERE freeleech = '1' AND freeleechexpire != '0000-00-00 00:00:00' AND freeleechexpire < '" . get_date_time() . "'");
    if (mysql_num_rows($query) > 0)
    {
        while ($row = mysql_fetch_array($query))
        {
            mysql_query("UPDATE torrents SET freeleech = '0', freeleechexpire = '0000-00-00 00:00:00' WHERE id = '" . $row["id"] . "'");
            write_log("Torrent - <a href='torrents-details.php?id=" . $row["id"] . "'>" . $row["name"] . "'s</a> freeleech has expired.");
        }
    }
    //OPTIMIZE TABLES
    	   $query = mysql_query("SELECT `id`, `username` FROM `users` WHERE `freeleechuser` = 'yes' AND `freeleechexpire` < '".get_date_time()."'") or die (mysql_error());
    if (mysql_num_rows($query) > 0)
    {
        $subject  = sqlesc("Plano vip");
        $message  = sqlesc("Informamos que seu plano VIP, acaba de expirar,
		Obrigado pela sua contribuição é com essas ações que mantemos nosso site no ar.
		Não deixe de semear os arquivos baixados, e sempre que desejar estamos a disposição.
		
		Atenciosamente,
		Equipe MS");
        $datetime = sqlesc(get_date_time());
        while ($row = mysql_fetch_array($query))
        {
            mysql_query("UPDATE `users` SET `freeleechuser` = 'no', `freeleechexpire` = '0000-00-00 00:00:00', donated='0', donator='n' WHERE `id` = " . $row["id"] . "") or die (mysql_error());
            mysql_query("INSERT INTO `messages` (`sender`, `receiver`, `added`, `msg`, `subject`) VALUES (0, $row[id], $datetime, $message, $subject)") or die (mysql_error());

        }
    }
    $res = SQL_Query_exec("SHOW TABLES");
   
    while ( $table = mysql_fetch_row($res) )
    {
        SQL_Query_exec("OPTIMIZE TABLE `$table[0]`;");
    }

$messages = gmtime() - 20 * 86400;//delete read Messages(PM's) after 31 days
SQL_Query_exec("DELETE FROM messages WHERE  added < FROM_UNIXTIME($messages)");	


SQL_Query_exec ("DELETE FROM shoutbox WHERE ".gmtime()."-UNIX_TIMESTAMP(date) >= 1728000");

$grupoconvite = gmtime() - 7 * 86400;//delete read Messages(PM's) after 7 days
SQL_Query_exec("DELETE FROM grupoaceita WHERE simounao = 'yes' AND datepedi < FROM_UNIXTIME($grupoconvite)");		

$loguser = gmtime() - 20 * 86400;//delete read Messages(PM's) after 20 days
SQL_Query_exec("DELETE FROM loguser WHERE  added < FROM_UNIXTIME($loguser)");	


$convites = gmtime() - 7 * 86400;//delete read Messages(PM's) after 7 days
SQL_Query_exec("DELETE FROM invites WHERE confirmed = 'no' AND simounao = 'no' AND time_invited < FROM_UNIXTIME($convites)");	

     $updategru = date('Y-m-d H:i:s'); 
	 $updategru = date("d", utc_to_tz_time($updategru)); 
	 
 if ($updategru == '1' || $updategru == '25'){
 
 $verificargru = mysql_query("SELECT chave FROM teams");  

 

	         while ($rowgrupos = mysql_fetch_assoc($verificargru))
        {
	 if ($updategru == '1' &&  $rowgrupos["chave"] == 'no'){

	 	SQL_Query_exec("UPDATE teams SET chave = 'yes', bonusteam = 1000.0 ");
		SQL_Query_exec("DELETE FROM grupobonus");	
	 }
	 if ($updategru == '25' &&  $rowgrupos["chave"] == 'yes'){

	 	SQL_Query_exec("UPDATE teams SET chave = 'no'");
	 }
	 }
}

/*
$messages_forum = gmtime() - 1 * 8640;//delete read Messages(PM's) after 31 days


$res_log = SQL_Query_exec("SELECT * FROM usermoderado WHERE added < FROM_UNIXTIME($messages_forum)");
while ($arr_log = mysql_fetch_array($res_log)){
	SQL_Query_exec("UPDATE users SET forumbanned = 'no' WHERE id = $arr_log[uid]");
	SQL_Query_exec("UPDATE users SET hideshoutbox = 'no' WHERE id = $arr_log[uid]");
}

SQL_Query_exec("DELETE FROM usermoderado WHERE  added < FROM_UNIXTIME($messages_forum)");
*/	
////
$ide_dever = gmtime() - 90 * 86400;//delete read Messages(PM's) after 60 days
$ide_deketeuser = gmtime() - 100 * 86400;//delete read Messages(PM's) after 100 days
$res_deleteuser = SQL_Query_exec("SELECT * FROM users WHERE enabled = 'yes' AND last_access < FROM_UNIXTIME($ide_deketeuser) AND added < FROM_UNIXTIME($ide_dever)  LIMIT 5 ");
while ($arr_deleteuser = mysql_fetch_array($res_deleteuser)){
	SQL_Query_exec("DELETE FROM invites WHERE inviteid = $arr_deleteuser[id]");
    SQL_Query_exec("DELETE FROM users WHERE id = $arr_deleteuser[id]");
	SQL_Query_exec("DELETE FROM snatched WHERE userid = $arr_deleteuser[id]");
	SQL_Query_exec("DELETE FROM warnings WHERE userid = $arr_deleteuser[id]");
	SQL_Query_exec("DELETE FROM ratings WHERE user = $arr_deleteuser[id]");
	SQL_Query_exec("DELETE FROM peers WHERE userid = $arr_deleteuser[id]");
    SQL_Query_exec("DELETE FROM completed WHERE userid = $arr_deleteuser[id]"); 
    SQL_Query_exec("DELETE FROM reports WHERE addedby = $arr_deleteuser[id]");
    SQL_Query_exec("DELETE FROM reports WHERE votedfor = $arr_deleteuser[id] AND type = 'user'");
    SQL_Query_exec("DELETE FROM forum_readposts WHERE userid = $arr_deleteuser[id]");
	SQL_Query_exec("DELETE FROM `snatched_t` WHERE `uid` = $arr_deleteuser[id]");
    SQL_Query_exec("DELETE FROM pollanswers WHERE userid = $arr_deleteuser[id]");
	SQL_Query_exec("DELETE FROM friends WHERE friendid = $arr_deleteuser[id] OR userid = $arr_deleteuser[id]");
	SQL_Query_exec("DELETE FROM bookmarks WHERE userid = $arr_deleteuser[id]");
	
$emaildel = $arr_deleteuser[email];	
require_once(BACKEND."/mail.php"); 	
		

	$body = <<<EOD
Prezado usuário,

Gostariamos de informar que sua conta do site www.malucos-share.org foi cancelada devido a inatividade, caso deseje participar novamente da comunidade, poderá efetuar um novo cadastro, esse deverá ser através de convite de um membro existente. Ou através da nossa comunidade oficial no Facebbok http://www.facebook.com/groups/242957942441666/

Caso tenha dúvidas entre em contato conosco, será um prazer atende-lo.
contato@malucos-share.org

Atenciosamente,
Malucos-share.org


EOD;

				sendmail($emaildel, "Sua conta foi desativada Malucos-Share", $body, "para: $site_config[SITEEMAIL]", "-f$site_config[SITEEMAIL]");
				$mailsent = 1;
	
	
	


	
	
	
}


///





	
// START LOTTERY
dbconn();
$dataatual =  get_date_time()  ;
$res = mysql_query("SELECT * FROM lottery_config") or sqlerr(__FILE__, __LINE__);
while ($arr = mysql_fetch_assoc($res))
$arr_config[$arr['name']] = $arr['value'];

if ($arr_config['enable'] == 1)
{
 if ( date("d-m-Y H:i:s", utc_to_tz_time($dataatual))  > $arr_config["end_date"])
{
if ($arr_config["ticket_amount_type"] == GB)
$arr_config['ticket_amount'] = 1024 * 1024 * 1024 * $arr_config['ticket_amount'];
else if ($arr_config["ticket_amount_type"] == MB)
$arr_config['ticket_amount'] = 1024 * 1024 * $arr_config['ticket_amount'];
$size = $arr_config['ticket_amount'];

if ($arr_config["ticket_amount_type"] == GB)
$arr_config['prize_fund'] = 1024 * 1024 * 1024 * $arr_config['prize_fund'];
else if ($arr_config["ticket_amount_type"] == MB)
$arr_config['prize_fund'] = 1024 * 1024 * $arr_config['prize_fund'];
$prize_fund = $arr_config['prize_fund'];

$total = mysql_num_rows(mysql_query("SELECT * FROM tickets"));
if ($arr_config["use_prize_fund"])
{
$pot = $prize_fund / $arr_config['total_winners'];
$res = mysql_query("SELECT user FROM tickets ORDER BY RAND() LIMIT $arr_config[total_winners]") or sqlerr();
$who_won = array();
$msg = sqlesc("Parabéns!\n\nVocê ganhou: [size=2][color=green]".mksize($pot)." [/color][/size]\n\n Isso foi adicionado à sua quantidade de upload.\n
Obrigado por jogar na Loteria.");
while ($arr = mysql_fetch_assoc($res))
{
$res2 = mysql_query("SELECT modcomment FROM users WHERE id = $arr[user]") or sqlerr(__FILE__, __LINE__);
$arr2 = mysql_fetch_assoc($res2);
$modcomment = $arr2['modcomment'];
$modcom = sqlesc("User won the lottery: " . mksize($pot) . " at " . get_date_time() . "\n" . $modcomment);
mysql_query("UPDATE users SET uploaded = uploaded + $pot, modcomment = $modcom WHERE id = $arr[user]") or sqlerr();
mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[user], NOW(), $msg, 0)") or sqlerr(__FILE__, __LINE__);
$who_won[] = $arr['user'];
}
}
else
{
$pot = $total * $size / $arr_config['total_winners'];
$res = mysql_query("SELECT user FROM tickets ORDER BY RAND() LIMIT $arr_config[total_winners]") or sqlerr();
$who_won = array();
$msg = sqlesc("Parabéns!\n\nVocê ganhou: [size=2][color=green]".mksize($pot)." [/color][/size]\n\n Isso foi adicionado à sua quantidade de upload.\n
Obrigado por jogar na Loteria.");
while ($arr = mysql_fetch_assoc($res))
{
$res2 = mysql_query("SELECT modcomment FROM users WHERE id = $arr[user]") or sqlerr(__FILE__, __LINE__);
$arr2 = mysql_fetch_assoc($res2);
$modcomment = $arr2['modcomment'];
$modcom = sqlesc("User won the lottery: " . mksize($pot) . " at " . get_date_time() . "\n" . $modcomment);
mysql_query("UPDATE users SET uploaded = uploaded + $pot WHERE id = $arr[user]") or sqlerr();
mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[user], NOW(), $msg, 0)") or sqlerr(__FILE__, __LINE__);
$who_won[] = $arr['user'];
}
}
$who_won = implode("|", $who_won);
$who_won_date = get_date_time();
$who_won_prize = $pot;
mysql_query("TRUNCATE TABLE tickets") or sqlerr(__FILE__, __LINE__);
if ($who_won != '')
{
mysql_query("UPDATE lottery_config SET value = '$who_won' WHERE name = 'lottery_winners'") or sqlerr(__FILE__, __LINE__);
mysql_query("UPDATE lottery_config SET value = '$who_won_prize' WHERE name = 'lottery_winners_amount'") or sqlerr(__FILE__, __LINE__);
mysql_query("UPDATE lottery_config SET value = '$who_won_date' WHERE name = 'lottery_winners_time'") or sqlerr(__FILE__, __LINE__);
}
mysql_query("UPDATE lottery_config SET value = '0' WHERE name = 'enable'") or sqlerr(__FILE__, __LINE__);
}
}
// END LOTTERY
}

?>