<?php

require "backend/functions.php";
dbconn(false);
  loggedinonly();


//DONATOR TABLE FUNCTION
 function donortable($res, $frame_caption) {
 print ("<div align=left><B>$frame_caption </B><BR>");
   if (mysql_num_rows($res) > 0)
{
 print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
 $num = 0;

 while ($a = mysql_fetch_assoc($res)) {
   ++$num;
$dis = $a["donated"];
if ($dis == "0")
     break;
if ($dis == $last)
  $rank = " ";
else
  $rank = $num;
if ($rank && $num > 10)
         break;
   if ($menu != "1") {
     echo "<tr>"
      ."<td class=table_head>Rank</td>"
      ."<td class=table_head>" . ACCOUNT_USER . "</td>"
      ."<td class=table_head>" . DONATED . "</td>"
     ."</tr>";
     $menu = 1;
   }
   print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><a href=account-details.php?id=$a[id]><b>$a[username]" .
    "</b></a></td><td class=table_col1 align=right>R$$dis</td></tr>");
$last = $dis;
 }
 echo "</table></div>";
  }else{
 echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
}
}


function usertable($res, $frame_caption) {
  print ("<div align=left><B>$frame_caption </B><BR>");
    if (mysql_num_rows($res) > 0)
	{
	print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
  $num = 0;
  while ($a = mysql_fetch_assoc($res)) {
    ++$num;
    if ($a["uploaded"] == "0")
	  break;
    if ($a["downloaded"]) {
      $ratio = $a["uploaded"] / $a["downloaded"];
      $color = get_ratio_color($ratio);
      $ratio = number_format($ratio, 2);
      if ($color)
        $ratio = "<font color=$color>$ratio</font>";
    }
    else
      $ratio = "Inf.";
    if ($menu != "1") {
      echo "<tr>"
		."<td class=table_head>Rank</td>"
		."<td class=table_head align=left>" . ACCOUNT_USER . "</td>"
		."<td class=table_head>" . UPLOADED . "</td>"
		."<td class=table_head>" . DOWNLOADED . "</td>"
	    ."<td class=table_head align=right>" . RATIO . "</td>"
	    ."</tr>";
      $menu = 1;
    }
    print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><a href=account-details.php?id=" . $a["id"] . "><b>" . $a["username"] .
          "</b></a></td><td class=table_col1 align=right>" . mksize($a["uploaded"]) .
          "</td><td class=table_col2 align=right>" . mksize($a["downloaded"]) .
          "</td><td class=table_col1 align=right>" . $ratio . "</td></tr>");
  }
  echo "</table></div>";
  	}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}

function _torrenttable($res, $frame_caption) {
  print ("<div align=left><B>$frame_caption </B><BR>");
  if (mysql_num_rows($res) > 0)
	{
	  print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
  $num = 0;
  while ($a = mysql_fetch_assoc($res)) {
      ++$num;
      if ($a["leechers"])
      {
        $r = $a["seeders"] / $a["leechers"];
        $ratio = "<font color=" . get_ratio_color($r) . ">" . number_format($r, 2) . "</font>";
      }
      else
        $ratio = "Inf.";
        if ($menu != "1") {
          echo "<tr>"
		      ."<td class=ttable_head>Rank</td>"
		      ."<td class=ttable_head align=left>" . NAME . "</td>"
		      ."<td class=ttable_head align=right>" . COMPLETED . "</td>"
		      ."<td class=ttable_head align=right>" . SEEDS . "</td>"
		      ."<td class=ttable_head align=right>" . LEECH . "</td>"
		      ."<td class=ttable_head align=right>" . PEERS . "</td>"
	 	      ."<td class=ttable_head align=right>" . RATIO . "</td>"
 	          ."</tr>";
 	      $menu = 1;
        }
        print("<tr><td class=ttable_col1>$num</td><td class=ttable_col2 align=left><a href=torrents-details.php?id=" . $a["id"] . "&hit=1><b>" .
        $a["name"] . "</b></a></td><td class=ttable_col1 align=center>" . number_format($a["times_completed"]) .
        "</td><td class=ttable_col2 align=center>" . number_format($a["seeders"]) .
        "</td><td class=ttable_col1 align=center>" . number_format($a["leechers"]) .
        "</td><td class=ttable_col2 align=center>" . ($a["leechers"] + $a["seeders"]) .
        "</td><td class=ttable_col1 align=right>$ratio</td>\n");
    }
    echo "</table></div>";
	}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}

function countriestable($res, $frame_caption) {
    print ("<div align=left><B>$frame_caption </B><BR>");
	  if (mysql_num_rows($res) > 0)
	{
    print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
	
	echo "<tr>";
	echo "<td class=table_head>Rank</td>";
	echo "<td class=table_head align=left>" . COUNTRY . "</td>";
	echo "<td class=table_head align=right>" . USERS . "</td>";
	echo "</tr>";
	
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><img align=center src=images/languages/$a[flagpic]>&nbsp;<b>$a[name]</b></td><td align=right class=table_col1>$a[num]</td></tr>\n");
    }
    echo "</table></div>";
		}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}



function postertable($res, $frame_caption) {
    print ("<div align=left><B>$frame_caption </B><BR>");
	  if (mysql_num_rows($res) > 0)
	{
	print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
	
	echo "<tr>";
	echo "<td class=table_head width=80>Posição</td>";
	echo "<td class=table_head align=left>Usuário</td>";
	echo "<td class=table_head align=left width=100>Quantidade</td>";
	echo "</tr>";
	
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><a href=account-details.php?id=$a[id]><b>$a[username]</b></td><td align=right class=table_col1>$a[num]</td></tr>\n");
    }
    echo "</table></div>";
		}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}

function postertablecoment($res, $frame_caption) {
    print ("<div align=left><B>$frame_caption </B><BR>");
	  if (mysql_num_rows($res) > 0)
	{
	print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
	
	echo "<tr>";
	echo "<td class=table_head width=80>Posição</td>";
	echo "<td class=table_head align=left>Usuário</td>";
	echo "<td class=table_head align=left width=100>Quantidade</td>";
	echo "</tr>";
	
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><a href=account-details.php?id=$a[id]><b>$a[username]</b></td><td align=right class=table_col1>$a[num]</td></tr>\n");
    }
    echo "</table></div>";
		}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}
function postertablepost($res, $frame_caption) {
    print ("<div align=left><B>$frame_caption </B><BR>");
	  if (mysql_num_rows($res) > 0)
	{
	print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
	
	echo "<tr>";
	echo "<td class=table_head width=80>Posição</td>";
	echo "<td class=table_head align=left>Usuário</td>";
	echo "<td class=table_head align=left width=100>Quantidade</td>";
	echo "</tr>";
	
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><a href=account-details.php?id=$a[id]><b>$a[username]</b></td><td align=right class=table_col1>$a[num]</td></tr>\n");
    }
    echo "</table></div>";
		}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}
function postertablebox($res, $frame_caption) {
    print ("<div align=left><B>$frame_caption </B><BR>");
	  if (mysql_num_rows($res) > 0)
	{
	print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
	
	echo "<tr>";
	echo "<td class=table_head width=80>Posição</td>";
	echo "<td class=table_head align=left>Usuário</td>";
	echo "<td class=table_head align=left width=100>Quantidade</td>";
	echo "</tr>";
	
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><a href=account-details.php?id=$a[id]><b>$a[username]</b></td><td align=right class=table_col1>$a[num]</td></tr>\n");
    }
    echo "</table></div>";
		}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}
function postertableconvites($res, $frame_caption) {
    print ("<div align=left><B>$frame_caption </B><BR>");
	  if (mysql_num_rows($res) > 0)
	{
	print("<table border=1 cellspacing=0 cellpadding=2 class=table_table>\n");
	
	echo "<tr>";
	echo "<td class=table_head width=80>Posição</td>";
	echo "<td class=table_head align=left>Usuário</td>";
	echo "<td class=table_head align=left width=100>Quantidade</td>";
	echo "</tr>";
	
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      print("<tr><td class=table_col1>$num</td><td class=table_col2 align=left><a href=account-details.php?id=$a[id]><b>$a[username]</b></td><td align=right class=table_col1>$a[num]</td></tr>\n");
    }
    echo "</table></div>";
		}else{
		echo "<font color=red>" . NOTHING_TO_SHOW . "</font></div>";
	}
}

//main stats here
$a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1"));
if ($CURUSER)
  $latestuser = "<a href=account-details.php?id=" . $a["id"] . ">" . $a["username"] . "</a>";
else
  $latestuser = "<b>$a[username]</b>";
$registered = number_format(get_row_count("users"));
$torrents = number_format(get_row_count("torrents"));

$result = mysql_query("SELECT SUM(downloaded) AS totaldl FROM users") or sqlerr(__FILE__, __LINE__); 

while ($row = mysql_fetch_array ($result)) 
{ 
$totaldownloaded      = $row["totaldl"]; 
} 
$result = mysql_query("SELECT SUM(uploaded) AS totalul FROM users") or sqlerr(__FILE__, __LINE__); 

while ($row = mysql_fetch_array ($result)) 
{ 
$totaluploaded      = $row["totalul"]; 
}
$seeders = get_row_count("peers", "WHERE seeder='yes'");
$leechers = get_row_count("peers", "WHERE seeder='no'");
$usersactive = 0;
if ($leechers == 0)
  $ratio = "100";
else
  $ratio = round($seeders / $leechers * 100);
 if ($ratio < 20)
    $ratio = "<font class=red>" . $ratio . "%</font>";
 else
	$ratio .= "%";
$peers = number_format($seeders + $leechers);
$seeders = number_format($seeders);
$leechers = number_format($leechers);
//start count visited today
$res = mysql_query("SELECT COUNT(*) FROM users WHERE UNIX_TIMESTAMP(" . get_dt_num() . ") - UNIX_TIMESTAMP(last_access) < 86400");
$arr3 = mysql_fetch_row($res);
$totaltoday = $arr3[0];
// start count registered today
$res = mysql_query("SELECT COUNT(*) FROM users WHERE UNIX_TIMESTAMP(" . get_dt_num() . ") - UNIX_TIMESTAMP(added) < 86400");
$arr44 = mysql_fetch_row($res);
$regtoday = $arr44[0];
//start count online now
$res = mysql_query("SELECT COUNT(*) FROM users WHERE UNIX_TIMESTAMP(" . get_dt_num() . ") - UNIX_TIMESTAMP(last_access) < 900");
$arr4 = mysql_fetch_row($res);
$totalnow = $arr4[0];
if ($CURUSER)
	guestadd();
if (!$activepeople)
  $activepeople = "" . NO_USERS . "";

  if (!$todayactive)
  $todayactive = "" . NO_USERS . "";
$guests = getguests();
if (!$guests)
	$guests = "0";

function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = getmicrotime();
//end here

///////////////////////////////////////// PAGE LAYOUT //////////////////////////////

  stdhead();

  begin_framec("Promoção", center);


/////////////////////////////////////////
$date_time='2013-05-20 03:00:00'; 

$date_time1='2013-05-27 03:00:00'; 
  $r = mysql_query("SELECT users.id, users.username, COUNT(torrents.owner) as num FROM torrents LEFT JOIN users ON users.id = torrents.owner WHERE torrents.added>='$date_time' AND  torrents.added <='$date_time1' AND users.class < '66' AND users.class != '50' GROUP BY owner  ORDER BY num DESC LIMIT 10");
  postertable($r, "lançamentos  entre 20/05/13 a 26/05/13 </font>"); echo "<br>";

  
  $r = mysql_query("SELECT users.id, users.username, COUNT(comments.user) as num FROM comments LEFT JOIN users ON users.id = comments.user WHERE comments.added>='$date_time' AND  comments.added <='$date_time1' AND users.class < '66' AND users.class != '50' GROUP BY user  ORDER BY num DESC LIMIT 10");
  postertablecoment($r, "Comentários entre 20/05/13 a 26/05/13 </font>"); echo "<br>";  
  
    $r = mysql_query("SELECT users.id, users.username, COUNT(forum_posts.userid) as num FROM forum_posts LEFT JOIN users ON users.id = forum_posts.userid WHERE forum_posts.added>='$date_time' AND  forum_posts.added <='$date_time1' AND users.class < '66' AND users.class != '50' GROUP BY userid  ORDER BY num DESC LIMIT 10");
  postertablepost($r, "Post entre 20/05/13 a 26/05/13 </font>"); echo "<br>";  
  
      $r = mysql_query("SELECT users.id, users.username, COUNT(shoutbox.userid) as num FROM shoutbox LEFT JOIN users ON users.id = shoutbox.userid WHERE shoutbox.date>='$date_time' AND  shoutbox.date <='$date_time1' AND users.class < '66' AND users.class != '50' GROUP BY userid  ORDER BY num DESC LIMIT 10");
  postertablebox($r, "Shoutbox entre 20/05/13 a 26/05/13 </font>"); echo "<br>";  

  
        $r = mysql_query("SELECT users.id, users.username, COUNT(invites.inviter) as num FROM invites LEFT JOIN users ON users.id = invites.inviter WHERE invites.time_invited>='$date_time' AND  invites.time_invited <='$date_time1' AND invites.confirmed= 'yes' AND users.class < '66' AND users.class != '50' GROUP BY inviter  ORDER BY num DESC LIMIT 10");
  postertableconvites($r, "Convites entre 20/05/13 a 26/05/13 </font>"); echo "<br>";  





	$res123 = SQL_Query_exec("SELECT * FROM users  WHERE class < '66' AND class != '50'  ORDER BY seedtime DESC LIMIT 10"); 
			while ($rowtempo = mysql_fetch_assoc($res123)) {
  $leechtime = ( $rowtempo[ seedtime ] ) ? seedtimenovo( $rowtempo[ seedtime ] ) : '-';
  $usermaster = $rowtempo["username"];
  
  
  echo"$usermaster >>>>>> $leechtime<br><br>";

  }
  end_framec();
  stdfoot();
?>