<?php
############################################################
#######                                             ########
#######                                             ########
#######           Malucos-share.net 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functionslancados.php");
dbconn();
loggedinonly();
 
 
$id = (int)$_GET["id"];
$r = @mysql_query("SELECT * FROM users WHERE id=$id");
$user = mysql_fetch_array($r);


if ($user["ver_lancados"] == "yes" || $CURUSER['id'] == $user['id'] || $CURUSER["level"]=="Administrador"  || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador") {



if (!is_valid_id($id))
  show_error_msg(T_("NO_SHOW_DETAILS"), "Bad ID.",1);
 
 
//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["view_torrents"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

//get http vars
$addparam = "";
$wherea = array();

$thisurl = "lancados.php?";
$wherea = array();
$wherea[] = "owner = '$id'";
// Mod Hide XXX
if($CURUSER["ver_xxx"]=='no'){
	$exe_xxx = mysql_query("SELECT id FROM categories WHERE parent_cat = 'Adulto'");
	while($xxx = mysql_fetch_array($exe_xxx)){
		$wherea[] = "category != '".$xxx["id"]."'";
	}
}
//Mod Hide XXX

$thisurl = "lancados.php?";
if ($_GET["cat"]) {
	$wherea[] = "category = " . sqlesc($_GET["cat"]);
	$addparam .= "cat=" . urlencode($_GET["cat"]) . "&amp;";
	$thisurl .= "cat=".urlencode($_GET["cat"])."&amp;";
}

if ($_GET["parent_cat"]) {
	$addparam .= "parent_cat=" . urlencode($_GET["parent_cat"]) . "&amp;";
	$thisurl .= "parent_cat=".urlencode($_GET["parent_cat"])."&amp;";
	$wherea[] = "categories.parent_cat=".sqlesc($_GET["parent_cat"]);
}

$parent_cat = $_GET["parent_cat"];
$category = (int) $_GET["cat"];

$where = implode(" AND ", $wherea);
$wherecatina = array();
$wherecatin = "";
$res = SQL_Query_exec("SELECT id FROM categories");
while($row = mysql_fetch_array($res)){
    if ($_GET["c$row[id]"]) {
        $wherecatina[] = $row["id"];
        $addparam .= "c$row[id]=1&amp;";
        $thisurl .= "c$row[id]=1&amp;";
    }
    $wherecatin = implode(", ", $wherecatina);
}

if ($wherecatin)
	$where .= ($where ? " AND " : "") . "category IN(" . $wherecatin . ")";

if ($where != "")
	$where = "WHERE $where";


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


//Get Total For Pager
$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents LEFT JOIN categories ON category = categories.id $where");
$row = mysql_fetch_row($res);
$count = $row[0];
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

//get sql info
if ($count) {
	list($pagertop, $pagerbottom, $limit) = pager(20, $count, "lancados.php?id=$id&" . $addparam);
	$query = "SELECT torrents.id,  torrents.category, torrents.leechers, torrents.banned, torrents.comments, torrents.seeders, torrents.name,  torrents.size, torrents.added,  torrents.filename, torrents.owner, torrents.freeleech, torrents.screens1, torrents.filmeresolucao, torrents.filmeresolucalt, torrents.filme3d,  torrents.safe, torrents.apliversao, filmeaudio.name AS  filmeaudio_name , filmequalidade.name AS filmequalidade_name, filmeextensao.name AS  filmeextensao_name,  filmeano.name AS  filmeano_name,  jogosgenero.name AS  jogosgenero_name, apliformarq.name AS  apliformarq_name, aplicrack.name AS  aplicrack_name, revistatensao.name AS revistatensao_name, musicatensao.name AS  musicatensao_name, musicaqualidade.name AS  musicaqualidade_name, users.freeleechuser AS freeleechuser,  categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN filmeaudio ON torrents.filmeaudio =  filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filmeextensao ON torrents. filmeextensao =  filmeextensao.id LEFT JOIN filmeano ON torrents. filmeano =  filmeano.id LEFT JOIN jogosgenero ON torrents.jogosgenero =  jogosgenero.id  LEFT JOIN apliformarq ON torrents.apliformarq =  apliformarq.id LEFT JOIN aplicrack ON torrents.aplicrack =  aplicrack.id LEFT JOIN revistatensao ON torrents.revistatensao =  revistatensao.id LEFT JOIN musicatensao ON torrents.musicatensao =  musicatensao.id LEFT JOIN musicaqualidade ON torrents.musicaqualidade =  musicaqualidade.id  $where $orderby $limit";
	$res = SQL_Query_exec($query);
}else{
	unset($res);
}



stdhead("Torrents lançados");
		begin_framec("Torrents lançados");

echo"<BR>";
 

if (is_valid_id($_GET["page"]))
	$thisurl .= "page=$_GET[page]&amp;";

echo "</center>";//some spacing



if ($count) {
	print($pagerbottom);
	echo"<BR>";
	torrenttable($res);
	echo"<BR>";
	print($pagerbottom);
	echo"<BR>";
}else {
	
     print("<div class='f-border'>");
     print("<div class='f-cat' width='100%'>".T_("NOTHING_FOUND")."</div>");
     print("<div>");
     print T_("NO_UPLOADS");
     print("</div>");
     print("</div>");
    
}
}
else
{
	show_error_msg(T_("ERROR"), "Este usuário nao quer mostrar seu torrents lançados", 1);
}

end_framec();
stdfoot();
?>