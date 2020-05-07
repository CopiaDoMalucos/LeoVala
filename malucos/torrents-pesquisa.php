<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions_pes_torr.php");
dbconn();
error_reporting(0);
ini_set(“display_errors”, 0 );
//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["view_torrents"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

function sqlwildcardesc($x){
    return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}


$searchstr = trim($_GET["search"]);
$cleansearchstr = searchfield($searchstr);
$searchstr = str_replace('  ','',$searchstr);

$cleansearchstr = searchfield($searchstr);
$searchstr_lista = explode(' ',$searchstr);
$total_de_buscas = count($searchstr_lista)-1;



if (empty($cleansearchstr))
unset($cleansearchstr);



$thisurl = "torrents-pesquisa.php?";

$addparam = "";
$wherea = array();
$wherecatina = array();
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


//include dead

	
if ($_GET["incldead"] == 1) {

	$wherea[] = "seeders > '0'";
	$addparam .= "incldead=1&amp;";
	$thisurl .= "incldead=1&amp;";
}if ($_GET["incldead"] == 2){
	$wherea[] = "visible = 'no'";
	$addparam .= "incldead=2&amp;";
	$thisurl .= "incldead=2&amp;";
}
	
// Include freeleech
if ($_GET["freeleech"] == 1) {
	$addparam .= "freeleech=1&amp;";
	$thisurl .= "freeleech=1&amp;";
	$wherea[] = "freeleech = '0'";
} elseif ($_GET["freeleech"] == 2) {
	$addparam .= "freeleech=2&amp;";
	$thisurl .= "freeleech=2&amp;";
	$wherea[] = "freeleech = '1'";
}



//include external
if ($_GET["inclexternal"] == 1) {
	$addparam .= "inclexternal=1&amp;";
	$wherea[] = "external = 'no'";
}

if ($_GET["inclexternal"] == 2) {
	$addparam .= "inclexternal=2&amp;";
	$wherea[] = "external = 'yes'";
}








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
if(!isset($_COOKIE[$CURUSER["id"]])){
setcookie($CURUSER["id"],'true',time()+2);

	
}else{



  show_error_msg("Erro!", "<br>Aguarde alguns segundos para efetuar uma nova pesquisa.<br><br><a href='torrents-pesquisa.php?parent_cat=".$parent_catid."&search=".$searcht."&incldead=".$incldead."&freeleech=".$freeleecht."&termos=".$_GET["termos"]."&search_in=".$_GET["search_in"]."&" . $addparam."'><b>Voltar</b></a>", 1);
			

}

if ($_GET["termos"] == 'exata'&& $_GET["search_in"] == 'posts') {	

for($i = 0; $i <= $total_de_buscas; $i++){
	if($i == 0){
		$lista_consultar = "torrents.name REGEXP'[[:<:]]".$searchstr_lista[$i]."[[:>:]]'";

	}else{
		$lista_consultar .= "AND torrents.name REGEXP'[[:<:]]".$searchstr_lista[$i]."[[:>:]]'";

	}
}
		$wherea[]  = "torrents.name LIKE'%$searchstr%' AND $lista_consultar OR torrents.descr LIKE'%$searchstr%' AND $lista_consultar";
			$addparam .= "search=" . urlencode($searchstr) . "&amp;";
	$thisurl .= "search=".urlencode($searchstr)."&amp;";
}
	
if ($_GET["termos"] == 'exata'&& $_GET["search_in"] == 'titles') {		

for($i = 0; $i <= $total_de_buscas; $i++){
	if($i == 0){
		$lista_consultar = "torrents.name REGEXP'[[:<:]]".$searchstr_lista[$i]."[[:>:]]'";
	}else{
		$lista_consultar .= "AND torrents.name REGEXP'[[:<:]]".$searchstr_lista[$i]."[[:>:]]'";
	}
}
		$wherea[]  = "torrents.name LIKE'%$searchstr%' AND $lista_consultar ";
			$addparam .= "search=" . urlencode($searchstr) . "&amp;";
	$thisurl .= "search=".urlencode($searchstr)."&amp;";
}



if ($_GET["termos"] == 'qualquer'&& $_GET["search_in"] == 'posts') {	
	$wherea[] = "MATCH (torrents.name) AGAINST ('".mysql_real_escape_string($searchstr)."' IN BOOLEAN MODE)";
	$wherea[] = "MATCH (torrents.descr) AGAINST ('".mysql_real_escape_string($searchstr)."' IN BOOLEAN MODE)";
	$addparam .= "search=" . urlencode($searchstr) . "&amp;";
	$thisurl .= "search=".urlencode($searchstr)."&amp;";
}

if ($_GET["termos"] == 'qualquer'&& $_GET["search_in"] == 'titles') {	
	$wherea[] = "MATCH (torrents.name) AGAINST ('".mysql_real_escape_string($searchstr)."' IN BOOLEAN MODE)";
	$addparam .= "search=" . urlencode($searchstr) . "&amp;";
	$thisurl .= "search=".urlencode($searchstr)."&amp;";
}






	
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
	$where = "WHERE $where";

$parent_check = "";
if ($parent_cat){
	$parent_check = " AND categories.parent_cat=".sqlesc($parent_cat);
}


//GET NUMBER FOUND FOR PAGER
$res = mysql_query("SELECT COUNT(*) FROM torrents $where $parent_check");
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
		$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents $where $parent_check");
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
	list($pagertop, $pagerbottom, $limit) = pager(20, $count, "torrents-pesquisa.php?&parent_cat=".$parent_catid."&search=".$searcht."&incldead=".$incldead."&freeleech=".$freeleecht."&termos=".$_GET["termos"]."&search_in=".$_GET["search_in"]."&" . $addparam);
	$query = "SELECT torrents.id,  torrents.category, torrents.leechers, torrents.banned, torrents.comments, torrents.seeders, torrents.name,  torrents.size, torrents.added,  torrents.filename, torrents.owner, torrents.freeleech, torrents.screens1, torrents.filmeresolucao, torrents.filmeresolucalt, torrents.filme3d,  torrents.safe, torrents.apliversao, filmeaudio.name AS  filmeaudio_name , filmequalidade.name AS filmequalidade_name, filmeextensao.name AS  filmeextensao_name,  filmeano.name AS  filmeano_name,  jogosgenero.name AS  jogosgenero_name, apliformarq.name AS  apliformarq_name, aplicrack.name AS  aplicrack_name, revistatensao.name AS revistatensao_name, musicatensao.name AS  musicatensao_name, musicaqualidade.name AS  musicaqualidade_name, users.freeleechuser AS freeleechuser,  categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN filmeaudio ON torrents.filmeaudio =  filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filmeextensao ON torrents. filmeextensao =  filmeextensao.id LEFT JOIN filmeano ON torrents. filmeano =  filmeano.id LEFT JOIN jogosgenero ON torrents.jogosgenero =  jogosgenero.id  LEFT JOIN apliformarq ON torrents.apliformarq =  apliformarq.id LEFT JOIN aplicrack ON torrents.aplicrack =  aplicrack.id LEFT JOIN revistatensao ON torrents.revistatensao =  revistatensao.id LEFT JOIN musicatensao ON torrents.musicatensao =  musicatensao.id LEFT JOIN musicaqualidade ON torrents.musicaqualidade =  musicaqualidade.id $where $parent_check $orderby $limit";
	$res = SQL_Query_exec($query);

	}else{
		unset($res);
}

if (isset($cleansearchstr))
	stdhead(T_("SEARCH_RESULTS_FOR")." \"" . htmlspecialchars($searchstr) . "\"");
else
	stdhead(T_("BROWSE_TORRENTS"));

begin_framec(T_("SEARCH_TORRENTS"));


?>

<div id="content">
<center>
[
<a href="pesquisa_avan.php">Pesquisa avançada </a>
]
</center>
<center>
<form method="get" action="torrents-pesquisa.php">
<table border="0" align="center">
<tr align='right'>
<?php
$i = 0;
$cats = SQL_Query_exec("SELECT * FROM categories ORDER BY parent_cat, name");
while ($cat = mysql_fetch_assoc($cats)) {
    $catsperrow = 5;
    print(($i && $i % $catsperrow == 0) ? "</tr><tr align='right'>" : "");

    $i++;                                                                                                                                                                                                                                                                                                                 
}
echo "</tr></table>";
?>
	
<table  width='100%' class='tab1' cellpadding='0' cellspacing='1' align='center' ><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Pesquisa Avançada de Torrents</td></tr><tr><td colspan="3" class="tab1_col3"><center><b>Pesquisar por: </b> <input type="text" name="search" size="50" maxlength="100" value="<?php echo  stripslashes(htmlspecialchars($searchstr)) ?>" /></center></td></tr><tr><td width="40%" class="tab1_col3">Pequisar na(s) categoria(s):<br> 

 <select name="cat">
	<option value="0">Todos</option>
	<?php


	$cats = genrelist();
	$catdropdown = "";
	foreach ($cats as $cat) {
		$catdropdown .= "<option value=\"" . $cat["id"] . "\"";
		if ($cat["id"] == $_GET["cat"])
			$catdropdown .= " selected=\"selected\"";
		$catdropdown .= ">" . htmlspecialchars($cat["parent_cat"]) . ": " . htmlspecialchars($cat["name"]) . "</option>\n";
	}	
	?>
	<?php echo  $catdropdown ?>
	</select>  
	
	<br><input type="checkbox" value="1" name="incldead"<?php if ($_GET["incldead"] == 1) { echo "checked"; } ?>> Apenas torrents com seeders.
<br><input type="checkbox" value="2" name="freeleech"<?php if ($_GET["freeleech"] == 2) { echo "checked"; } ?>> Apenas torrents <font color="green">Free Leech</font>.<br>
</td><td class="tab1_col3"><b>Pesquisar por</b><br><input type="radio" checked="checked" value="qualquer" id="qualquer" name="termos" <?php if ($_GET["termos"] == 'qualquer') { echo "checked"; } ?> ><label for="qualquer">Qualquer palavra</label><br><input type="radio" value="exata" id="exata" name="termos"<?php if ($_GET["termos"] == 'exata') { echo "checked"; } ?>><label for="exata">Busca exata</label></td><td class="tab1_col3"><b>Procurar em</b><br><input type="radio" checked="checked" value="titles" id="search_in_titles" class="radiobutton" name="search_in" <?php if ($_GET["search_in"] == 'titles') { echo "checked"; } ?>><label for="search_in_titles">Apenas no título</label><br><input type="radio" value="posts" id="search_in_posts" class="radiobutton" name="search_in" <?php if ($_GET["search_in"] == 'posts') { echo "checked"; } ?>><label for="search_in_posts">Título e Descrição</label><br><br></td></tr><tr><td align="center" colspan="3" class="tab1_col3"><input type="submit" style="width: 150px; height:30px;" value="Pesquisar!"></td></tr></tbody></table>
	<br />
	</form>
    </center>
    
<?php

if ($count) {

	torrenttable($res);
	print($pagerbottom);
}else {
    
     print("<div class='f-border'>");
     print("<div class='f-cat' width='100%'>".T_("NOTHING_FOUND")."</div>");
     print("<div>");
     print T_("NO_RESULTS");
     print("</div>");
     print("</div>");
     
}


end_framec();
stdfoot();

?>
