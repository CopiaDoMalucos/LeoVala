<?php

require_once("backend/functions.php");
dbconn(true);



stdhead("Home");


//Site News
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
        begin_frame("Vesti");
        $res = mysql_query("SELECT * FROM news WHERE ADDDATE(added, INTERVAL 45 DAY) > '".get_date_time()."' ORDER BY added DESC LIMIT 10") or die(mysql_error());
        if (mysql_num_rows($res) > 0){
                print("<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>\n<ul>");
                $news_flag = 0;

                while($array = mysql_fetch_array($res)){
                        $user = mysql_fetch_assoc(mysql_query("SELECT username FROM users WHERE id = $array[userid]")) or die(mysql_error());

                        $numcomm = number_format(get_row_count("comments", "WHERE news='".$array['id']."'"));

                        if ($news_flag < 0) { //show first 0 items expanded

                                print("<BR><a href=\"java script: klappe_news('a".$array['id']."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/minus.gif\" id=\"pica".$array['id']."\" alt=\"Show/Hide\">");
                                print("&nbsp;<b>". $array['title'] . "</b></a> - <B>Postirano:</B> " . date("d-M-y", utc_to_tz_time($array['added'])) . " <B>Od:</B> $user[username]");
                                
                                print("<div id=\"ka".$array['id']."\" style=\"display: block;\"> ".format_comment($array["body"],0)." <BR><BR>Komentari (<a href=comments.php?type=news&id=".$array['id'].">".$numcomm."</a>)</div><br> ");

                                $news_flag = ($news_flag + 1);

                        }else{

                                print("<BR><a href=\"java script: klappe_news('a".$array['id']."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/plus.gif\" id=\"pica".$array['id']."\" alt=\"Show/Hide\">");
                                print("&nbsp;<b>". $array['title'] . "</b></a> - <B>Postirano:</B> " . date("d-M-y",utc_to_tz_time($array['added'])) . " <B>Od:</B> $user[username]");
                                
                                print("<div id=\"ka".$array['id']."\" style=\"display: none;\"> ".format_comment($array["body"],0)." <BR><BR>Komentari (<a href=comments.php?type=news&id=".$array['id'].">".number_format($numcomm)."</a>)</div><br> ");
                        }
                }
                print("</ul></td></tr></table>\n");
        }else{
                echo "<BR><b><font color=#FF8080>Dragi ljubitelji Privatnog Trackera SRBIJANET Torrent!Velika nam je cast sto ste nam ostali verni do sada sto se nadamo i u buduce.Lepo je videti kada nas ima vise i kao sto ste zapazili sve novi nam dolaze sto je i dovelo do ovolikog uspeha i daljeg razvoja Srbijanet Trackera.Nama kao i svima vama je drago sto se ovako i na ovaj nacin svi lepo druzimo.Pokusavamo maximalno da svakom izadjemo u susret bila to pomoc ili neki dobar savet,tu smo.Mada nebi bilo lose i neko nas da posavetuje u pozitivnom smislu naravno.E pa zelimo vam ugodno vreme i lep provod na    Srbijanet Torrent TRACKERU!DOBRODOSLI I SRECNO!</b>";
        }echo 
        end_frame();
}

if (!$site_config["MEMBERSONLY"] || $CURUSER) {
        begin_frame("Pricalica");
        echo '<IFRAME name="shout_frame" src="'.$site_config["SITEURL"].'/shoutbox.php" frameborder="0" marginheight="0" marginwidth="0" width="99%" height="550" width=450 scrolling="no" align="middle"></IFRAME>';
        echo "(Pricalica se osvezava svake 1 minute)<BR>";
        echo "<font color=red>Osvezite vas Browser i izbrisite  kolacice  dabi mogli pisati na Pricalici!<BR>";
        end_frame();
}
//USERS ONLINE
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
begin_block("Prisutni");
$file = "".$site_config["cache_dir"]."/cache_usersonlineblock.txt";
$expire = 20; // time in seconds
if (file_exists($file) &&    
    filemtime($file) > (time() - $expire)) {
    $us