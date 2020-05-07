<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(false); 

if (isset($_GET["custom"])){
	stdhead("Saída RSS");
	begin_framec("Saída RSS");

	$rqt = "SELECT id, name, parent_cat FROM categories ORDER BY parent_cat ASC, sort_index ASC";
	$resqn = SQL_Query_exec($rqt);

	if ($_POST) {
		$params = array();

		if ($cats = $_POST["cats"]) {
			$catlist = array();
			foreach ($cats as $cat) {
				if (is_numeric($cat)) {
					$catlist[] = $cat;
				}
			}
			if ($catlist)
				$params[] = "cat=".implode(",", $catlist);
		}

		if ($_POST["incldead"])
			$params[] = "incldead=1";

		if ($_POST["dllink"])
			$params[] = "dllink=1";

		if (!$_POST["cookies"] && $CURUSER)
			$params[] = "passkey=$CURUSER[passkey]";

		if ($params)
			$param = "?".implode("&amp;", $params);
		else
			$param = "";


	}
	?>
	<form action="rss.php?custom" method="post">
	<table class='tab1' cellpadding='0' cellspacing='1' align='center' width="100%" border="0" >
				<tr>
				<td class="tab1_cab1" colspan="2" width="100%" align=center>O que é RSS? De uma olhada no <a href="http://wikipedia.org/wiki/RSS_%28file_format%29">Wiki</a> para <a href="http://wikipedia.org/wiki/RSS_%28file_format%29">Ler mais</a>.
				<br>
				<center>Cada saída mostra os últimos 20 torrents lançados da respectiva categoria.</center>
				</td>
			</tr>
		<tr>
				<td class="ttable_head" width="40%" align=left>Categoria</td>
				<td class="ttable_head" width="60%"align=center>Link do rss</td>

			</tr>
	
			<?php
echo '<td class="tab1_col3"><center> Todas </center></td>';
	?> 

		<td class="tab1_col3">
<?php  echo "<a href=\"$site_config[SITEURL]/rss.php\">$site_config[SITEURL]/rss.php</a><br/><br/></td>
	</tr>"; 

			while ($row = mysql_fetch_array($resqn)) {
			echo '<tr><td class="tab1_col3"><center> '.htmlspecialchars("$row[parent_cat] - $row[name]").'</center></td>';
	
		?> 
		<td class="tab1_col3">
<?php  echo "<a href=\"$site_config[SITEURL]/rss.php?cat=".$row['id']."\">$site_config[SITEURL]/rss.php?cat=".$row['id']." </a><br/><br/></td>
	</tr>"; 
		}?>
	



	</table>
	</form>
	<br /><br />
	
	<?php
	end_framec();
	stdfoot();
	die();
}

$cat = $_GET["cat"];
$dllink  = (int)$_GET["dllink"];

$passkey = $_GET["passkey"];
if (!get_row_count("users", "WHERE passkey=".sqlesc($passkey)))
	$passkey = "";


$where = "";
$wherea = array();

if ($cat) {
	$cats = implode(", ", array_unique(array_map("intval", explode(",", $cat))));
	$wherea[] = "category in ($cats)";
}

if (is_valid_id($_GET["user"])) {
	$wherea[] = "owner=$_GET[user]";
}

if ($wherea)
	$where = "WHERE ".implode(" AND ", $wherea);

$limit = "LIMIT 20";

// start the RSS feed output
header("Content-Type: application/xhtml+xml; charset=$site_config[CHARSET]"); 
echo("<?xml version=\"1.0\" encoding=\"$site_config[CHARSET]\"?>");
echo("<rss version=\"2.0\"><channel><generator>" . htmlspecialchars($site_config["SITENAME"]) . " RSS 2.0</generator><language>en</language>" . 
"<title>" . $site_config["SITENAME"] . "</title><description>" . htmlspecialchars($site_config["SITENAME"]) . " RSS Feed</description><link>" . $site_config["SITEURL"] . "</link><copyright>Copyright " . htmlspecialchars($site_config["SITENAME"]) . "</copyright><pubDate>".date("r")."</pubDate>"); 

$res = SQL_Query_exec("SELECT torrents.id, torrents.name, torrents.size, torrents.category, torrents.added, torrents.leechers, torrents.seeders, categories.parent_cat as cat_parent, categories.name AS cat_name FROM torrents LEFT JOIN categories ON category = categories.id $where ORDER BY added DESC $limit");

while ($row = mysql_fetch_array ($res)){ 
	list($id,$name,$size,$category,$added,$leechers,$seeders,$catname) = $row; 
	  date_default_timezone_set('Etc/GMT+3');
	if ($dllink) {
		if ($passkey)
			$link = "$site_config[SITEURL]/download.php?id=$id&amp;passkey=$passkey"; 
		else
			$link = "$site_config[SITEURL]/download.php?id=$id"; 
	} else {
		$link = $site_config["SITEURL"]."/torrents-details.php?id=$id&amp;hit=1"; 
	}

	$pubdate = date("r", sql_timestamp_to_unix_timestamp($added));


	echo("<item><title>" . htmlspecialchars($name) . "</title><guid>" . $link . "</guid><link>" . $link . "</link><pubDate>" . $pubdate . "</pubDate>	<category> " . $row["cat_parent"] . ": " . $row["cat_name"] . "</category><description>Categoria: " . $row["cat_parent"] . ": " . $row["cat_name"] . "  Tamanho: " . mksize($size) . "  Seeders: " . $seeders . " Leechers: " . $leechers . "</description><dataenvio> " . $added . "</dataenvio></item>"); 
} 


echo("</channel></rss>"); 