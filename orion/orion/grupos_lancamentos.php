<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functionsgrupos.php");
dbconn();


 $id = (int) $_GET['id'];
  $res123 = SQL_Query_exec("SELECT teams.id, teams.name, teams.image, teams.image1, teams.image2, teams.info, teams.owner, users.username FROM teams LEFT JOIN users ON teams.owner = users.id WHERE users.enabled = 'yes' AND users.status = 'confirmed' AND teams.id = '$id'");

 $row123 = mysql_fetch_array($res123);

 if (mysql_num_rows($res123) == 0)
     show_error_msg("Error", "Essa equipe não existe.", 1);
	 
//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["view_torrents"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

function sqlwildcardesc($x){
    return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}

//GET SEARCH STRING
$searchstr = trim($_GET["search"]);
$cleansearchstr = searchfield($searchstr);
if (empty($cleansearchstr))
unset($cleansearchstr);

$thisurl = "grupos_lancamentos.php?";

$addparam = "";
$wherea = array();
$wherecatina = array();
$wherea[] = "banned = 'no'";
$wherea[] = "safe = 'yes'";


$wherecatina = array();
$wherecatin = "";
$res = SQL_Query_exec("SELECT id FROM categories");
while($row = mysql_fetch_assoc($res)){
    if ($_GET["c$row[id]"]) {
        $wherecatina[] = $row[id];
        $addparam .= "c$row[id]=1&amp;";
        $addparam .= "c$row[id]=1&amp;";
        $thisurl .= "c$row[id]=1&amp;";
    }
    $wherecatin = implode(", ", $wherecatina);
}
if ($wherecatin)
    $wherea[] = "category IN ($wherecatin)";






//cat
if ($_GET["cat"]) { 
        $wherea[] = "category = " . sqlesc($_GET["cat"]);
		$wherecatina[] = sqlesc($_GET["cat"]);
        $addparam .= "cat=" . urlencode($_GET["cat"]) . "&amp;";
	$thisurl .= "cat=".urlencode($_GET["cat"])."&amp;";
}

//language
if ($_GET["lang"]) {
    $wherea[] = "torrentlang = " . sqlesc($_GET["lang"]);
    $addparam .= "lang=" . urlencode($_GET["lang"]) . "&amp;";
    $thisurl .= "lang=".urlencode($_GET["lang"])."&amp;";
}

//parent cat
if ($_GET["parent_cat"]) {
	$addparam .= "parent_cat=" . urlencode($_GET["parent_cat"]) . "&amp;";
	$thisurl .= "parent_cat=".urlencode($_GET["parent_cat"])."&amp;";
}

$parent_cat = $_GET["parent_cat"];

$wherebase = $wherea;

if (isset($cleansearchstr)) {
	$wherea[] = "MATCH (torrents.name) AGAINST ('".mysql_real_escape_string($searchstr)."' IN BOOLEAN MODE)";

	$addparam .= "search=" . urlencode($searchstr) . "&amp;";
	$thisurl .= "search=".urlencode($searchstr)."&amp;";
}

//order by
if ($_GET['sort'] && $_GET['order']) {
	$column = '';
	$ascdesc = '';
	switch($_GET['sort']) {
		case 'id': $column = "id"; break;
		case 'name': $column = "name"; break;
		case 'comments': $column = "comments"; break;
		case 'size': $column = "size"; break;
		case 'completed': $column = "times_completed"; break;
		case 'seeders': $column = "seeders"; break;
		case 'leechers': $column = "leechers"; break;
		case 'category': $column = "category"; break;
		default: $column = "id"; break;
	}

	switch($_GET['order']) {
		case 'asc': $ascdesc = "ASC"; break;
		case 'desc': $ascdesc = "DESC"; break;
		default: $ascdesc = "DESC"; break;
	}
} else {
	$_GET["sort"] = "id";
	$_GET["order"] = "desc";
	$column = "id";
	$ascdesc = "DESC";
}

	$orderby = "ORDER BY torrents." . $column . " " . $ascdesc;
	$pagerlink = "sort=" . $_GET['sort'] . "&amp;order=" . $_GET['order'] . "&amp;";

if (is_valid_id($_GET["page"]))
	$thisurl .= "page=$_GET[page]&amp;";


$where = implode(" AND ", $wherea);

if ($where != "")
	$where = " $where";

$parent_check = "";
if ($parent_cat){
	$parent_check = " AND categories.parent_cat=".sqlesc($parent_cat);
}


//GET NUMBER FOUND FOR PAGER
$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents  WHERE owner IN (SELECT id FROM users WHERE team = ".$row123['id'].") AND $where $parent_check");
$row = mysql_fetch_array($res);
$count = $row[0];


if (!$count && isset($cleansearchstr)) {
	$wherea = $wherebase;
	$searcha = explode(" ", $cleansearchstr);
	$sc = 0;
	foreach ($searcha as $searchss) {
		if (strlen($searchss) <= 1)
		continue;
		$sc++;
		if ($sc > 5)
		break;
		$ssa = array();
		foreach (array("torrents.name") as $sss)
		$ssa[] = "$sss LIKE '%" . sqlwildcardesc($searchss) . "%'";
		$wherea[] = "(" . implode(" OR ", $ssa) . ")";
	}
	if ($sc) {
		$where = implode(" AND ", $wherea);
		if ($where != "")
		$where = "WHERE $where";
		$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE $where $parent_check");
		$row = mysql_fetch_array($res);
		$count = $row[0];
	}
}

//Sort by
if ($addparam != "") { 
	if ($pagerlink != "") {
		if ($addparam{strlen($addparam)-1} != ";") { // & = &amp;
			$addparam = $addparam . "&amp;" . $pagerlink;
		} else {
			$addparam = $addparam . $pagerlink;
		}
	}
} else {
	$addparam = $pagerlink;
}



if ($count) {

	//SEARCH QUERIES! 
	list($pagertop, $pagerbottom, $limit) = pager(10, $count, "grupos_lancamentos.php?id=$id&" . $addparam);
    $query = "SELECT torrents.id,  torrents.category, torrents.leechers, torrents.banned, torrents.comments, torrents.seeders, torrents.name,  torrents.size, torrents.added,  torrents.filename, torrents.owner, torrents.freeleech, torrents.screens1, torrents.filmeresolucao, torrents.filmeresolucalt, torrents.filme3d,  torrents.safe, torrents.apliversao, filmeaudio.name AS  filmeaudio_name , filmequalidade.name AS filmequalidade_name, filmeextensao.name AS  filmeextensao_name,  filmeano.name AS  filmeano_name,  jogosgenero.name AS  jogosgenero_name, apliformarq.name AS  apliformarq_name, aplicrack.name AS  aplicrack_name, revistatensao.name AS revistatensao_name, musicatensao.name AS  musicatensao_name, musicaqualidade.name AS  musicaqualidade_name, users.freeleechuser AS freeleechuser,  categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN filmeaudio ON torrents.filmeaudio =  filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filmeextensao ON torrents. filmeextensao =  filmeextensao.id LEFT JOIN filmeano ON torrents. filmeano =  filmeano.id LEFT JOIN jogosgenero ON torrents.jogosgenero =  jogosgenero.id  LEFT JOIN apliformarq ON torrents.apliformarq =  apliformarq.id LEFT JOIN aplicrack ON torrents.aplicrack =  aplicrack.id LEFT JOIN revistatensao ON torrents.revistatensao =  revistatensao.id LEFT JOIN musicatensao ON torrents.musicatensao =  musicatensao.id LEFT JOIN musicaqualidade ON torrents.musicaqualidade =  musicaqualidade.id  WHERE owner IN (SELECT id FROM users WHERE team = ".$row123['id'].") AND safe='yes' AND $where  $parent_check $orderby $limit";

	$res = SQL_Query_exec($query);

	}else{
		unset($res);
}

if (isset($cleansearchstr))
	stdhead(T_("SEARCH_RESULTS_FOR")." \"" . htmlspecialchars($searchstr) . "\"");
else
	stdhead('LANÇAMENTOS DO GRUPO  - ' . $row123['name']);

begin_framec('LANÇAMENTOS DO GRUPO  - ' . $row123['name']);


?>

<?php
$i = 0;
$cats = SQL_Query_exec("SELECT * FROM categories ORDER BY parent_cat, name");
while ($cat = mysql_fetch_assoc($cats)) {
    $catsperrow = 5;
    print(($i && $i % $catsperrow == 0) ? "" : "");

    $i++;                                                                                                                                                                                                                                                                                                                 
}


//if we are browsing, display all subcats that are in same cat
if ($parent_cat){
	echo "<br /><br /><b>You are in:</b> <a href='torrents.php?parent_cat=$parent_cat'>$parent_cat</a><br /><b>Sub Categories:</b> ";
	$subcatsquery = SQL_Query_exec("SELECT id, name, parent_cat FROM categories WHERE parent_cat='$parent_cat' ORDER BY name");
	while($subcatsrow = mysql_fetch_assoc($subcatsquery)){
		$name = $subcatsrow['name'];
		echo " - <a href='torrents.php?cat=$subcatsrow[id]'>$name</a>";
	}
}	

echo "<br /><br />";//some spacing

?>   

	<?php


	$cats = genrelist();
	$catdropdown = "";
	foreach ($cats as $cat) {
		$catdropdown .= "<option value=\"" . $cat["id"] . "\"";
		if ($cat["id"] == $_GET["cat"])
			$catdropdown .= " selected=\"selected\"";
		$catdropdown .= ">" . htmlspecialchars($cat["parent_cat"]) . ": " . htmlspecialchars($cat["name"]) . "</option>\n";
	}	

if ($count) {

	print($pagerbottom);
		?>
 <table width="100%">
 <tr>
     <td valign="top" align="right">
     <form id='sort' action=''>
     <b>Categorias:</b>
     <select name="cat" onchange="window.location='grupos_lancamentos.php?id=<?php echo $row123['id'] ?>&cat='+this.options[this.selectedIndex].value">
     <option value="">Todas as Categorias</option>
     <?php foreach ( genrelist() as $category ): ?>
        <option value="<?php echo $category["id"]; ?>" <?php echo ($_GET['cat'] == $category["id"] ? " selected='selected'" : ""); ?>><?php echo $category["parent_cat"] . ' > ' . $category["name"]; ?></option>
     <?php endforeach; ?>
     </select>   
     </form> 
     </td>
 </tr>
 </table>
<?php
	torrenttable($res);
	echo"<br>";
	print($pagerbottom);
}else {
    
     print("<div class='f-border'>");
     print("<div class='f-cat' width='100%'>".T_("NOTHING_FOUND")."</div>");
     print("<div>");
     print T_("NO_RESULTS");
     print("</div>");
     print("</div>");
     
}

if ($CURUSER)
	SQL_Query_exec("UPDATE users SET last_browse=".gmtime()." WHERE id=$CURUSER[id]");


end_framec();
stdfoot();

?>
