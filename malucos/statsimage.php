<?php
$id = (int)$_GET["id"];
if ($id <= 0) die;

require_once("backend/functions.php");
dbconn(false);

$bg = "images/userstatsbg.jpg";
$cachedir = './cache/statsimages/';
$cachetime = 30;
$usecaching = false;
$font = "backend/fonts/verdanab.ttf";
$size = 15;
$colour["R"] = 255;
$colour["G"] = 255;
$colour["B"] = 255;

if ($usecaching) {
        $cachefile = "$cachedir$id.cache";

        $cachefile_created = @filemtime($cachefile);
        @clearstatcache();
        $cachetime = $cachetime * 60;

        if (time() - $cachetime < $cachefile_created) {
                header("Content-Type: image/png");
                @readfile($cachefile);
                die;
        }
}

$res = mysql_query("SELECT title, avatar,country, title, username, seedbonus, page, age, level, uploaded, downloaded, gender, class, warned, donated, COUNT(distinct torrents.id) as torrents FROM users LEFT JOIN torrents ON users.id=torrents.owner LEFT JOIN groups ON groups.group_id=users.class WHERE users.id = $id GROUP BY users.id") or die(mysql_error());
$row = mysql_fetch_array($res) or die;

$res = mysql_query("SELECT name FROM countries WHERE id=".$row["country"]." LIMIT 1") or die(mysql_error());
if (mysql_num_rows($res) == 1){
        $arr = mysql_fetch_assoc($res);
        $country = "$arr[name]";
}

//$numseed
$res = mysql_query("SELECT COUNT(*) FROM peers WHERE userid='$id' AND seeder='yes'");
$arr = mysql_fetch_row($res);
$numseed = $arr[0];

//$numseed
$res = mysql_query("SELECT COUNT(*) FROM peers WHERE userid='$id' AND seeder='no'");
$arr = mysql_fetch_row($res);
$numleech = $arr[0];

//Nombres de torrents telecharger
$res = mysql_query("SELECT COUNT(*) FROM completed WHERE userid='$id'");
$arr = mysql_fetch_row($res);
$numdown = $arr[0];

//Nombres de messages poster dans le forums
$res = mysql_query("SELECT COUNT(*) FROM forum_posts WHERE userid='$id'");
$arr = mysql_fetch_row($res);
$numposte = $arr[0];

//Nombres de commentaires poster
$res = mysql_query("SELECT COUNT(*) FROM comments WHERE user=$id") or die(mysql_error());
$arr = mysql_fetch_row($res);
$numcomments = $arr[0];

//Nombres de commentaires poster
$res = mysql_query("SELECT COUNT(*) FROM messages WHERE sender=$id");
$arr = mysql_fetch_row($res);
$nummess = $arr[0];

if ($row[downloaded] == 0 && $row[uploaded] > 0)
        $ratio = "Inf.";
elseif ($row[downloaded] == 0 && $row[uploaded] == 0)
        $ratio = "N/A";
else
        $ratio = number_format($row["uploaded"] / $row["downloaded"],2);


        
$im = imagecreatefromjpeg($bg);

$colour = imagecolorallocate($im, $colour["R"], $colour["G"], $colour["B"]);



imagettftext($im, 13, 0, 30, 27, $colour, $font, "$row[username]");
imagettftext($im, $size, 0, 10, 45, $colour, $font, "Uploader: ".mksize($row["uploaded"]));
imagettftext($im, $size, 0, 10, 60, $colour, $font, "Telecharger: ".mksize($row["downloaded"]));
imagettftext($im, $size, 0, 10, 75, $colour, $font, "Ratio: $ratio");
imagettftext($im, $size, 0, 190, 75, $colour, $font, "Genre: $row[gender]");
imagettftext($im, $size, 0, 370, 20, $colour, $font, "Age: $row[age] Ans");
imagettftext($im, $size, 0, 190, 60, $colour, $font, "Torrents Telecharger: $numdown");


header("Content-Type: image/png");
if ($usecaching) {
        imagepng($im, $cachefile);
        readfile($cachefile);
} else
        imagepng($im);
?>