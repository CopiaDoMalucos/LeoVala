<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions_pes_torr.php");
$_POST['cat'] = json_decode($_POST['cat']);
dbconn();

//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["view_torrents"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

function sqlwildcardesc($x){
    return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}


$searchstr = trim($_POST["search"]);
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

$wherecatin = "";
$res = SQL_Query_exec("SELECT id FROM categories");
while($row = mysql_fetch_assoc($res)){
    if ($_POST["c$row[id]"]) {
        $wherecatina[] = $row[id];
    }
    $wherecatin = implode(", ", $wherecatina);
}
if ($wherecatin)
    $wherea[] = "category IN ($wherecatin)";

	
	
	
	
	
	

//include dead

	
if ($_POST["incldead"] == 1) {
	$wherea[] = "seeders > '0'";
    $incldead .= "incldead=1&amp;";

}if ($_POST["incldead"] == 2){
	$wherea[] = "visible = 'no'";
	    $incldead .= "incldead=2&amp;";
}
	
// Include freeleech
if ($_POST["freeleech"] == 1) {
	$wherea[] = "freeleech = '0'";
	   $freeleech .= "freeleech=2&amp;";
} elseif ($_POST["freeleech"] == 2) {
	$wherea[] = "freeleech = '1'";
	   $freeleech .= "freeleech=2&amp;";
}
//cat

if(isset($_POST["cat"]) and !empty($_POST['cat'])) {
 $consulta = 'category IN (';
  $i = 0; 
  foreach($_POST["cat"] as $key){
  $consulta .= ($i != 0 ? ',' : '')."'".$key."'";
  $wherecatina[] = sqlesc($key);
 $addparam .=  "cat=" . urlencode($key) . "&amp;";
  $i++;
  }
  $consulta .= ')';
 $wherea[] = $consulta;
}


$wherebase = $wherea;

if (isset($cleansearchstr)) {


if ($_POST["termos"] == 'exata'&& $_POST["search_in"] == 'posts') {	

for($i = 0; $i <= $total_de_buscas; $i++){
	if($i == 0){
		$lista_consultar = "torrents.name REGEXP'[#\-]".$searchstr_lista[$i]."[#\-]'";

	}else{
		$lista_consultar .= "AND torrents.name REGEXP'[#\-]".$searchstr_lista[$i]."[#\-]'";

	}
}
		$wherea[]  = "torrents.name LIKE'%$searchstr%' AND $lista_consultar OR torrents.descr LIKE'%$searchstr%' AND $lista_consultar";
	$termos .= "termos=" . $_POST["termos"] . "&amp;";
	$search_in .= "search_in=".$_POST["search_in"]."&amp;";
}
	
if ($_POST["termos"] == 'exata'&& $_POST["search_in"] == 'titles') {		

for($i = 0; $i <= $total_de_buscas; $i++){
	if($i == 0){
		$lista_consultar = "torrents.name REGEXP'[#\-]".$searchstr_lista[$i]."[#\-]'";
	}else{
		$lista_consultar .= "AND torrents.name REGEXP'[#\-]".$searchstr_lista[$i]."[#\-]'";
	}
}
		$wherea[]  = "torrents.name LIKE'%$searchstr%' AND $lista_consultar ";
			$termos .= "termos=" . $_POST["termos"] . "&amp;";
	        $search_in .= "search_in=".$_POST["search_in"]."&amp;";
}



if ($_POST["termos"] == 'qualquer'&& $_POST["search_in"] == 'posts') {	
	$wherea[] = "MATCH (torrents.name) AGAINST ('".mysql_real_escape_string($searchstr)."' IN BOOLEAN MODE)";
	$wherea[] = "MATCH (torrents.descr) AGAINST ('".mysql_real_escape_string($searchstr)."' IN BOOLEAN MODE)";
		$termos .= "termos=" . $_POST["termos"] . "&amp;";
	$search_in .= "search_in=".$_POST["search_in"]."&amp;";

}

if ($_POST["termos"] == 'qualquer'&& $_POST["search_in"] == 'titles') {	
	$wherea[] = "MATCH (torrents.name) AGAINST ('".mysql_real_escape_string($searchstr)."' IN BOOLEAN MODE)";
		$termos .= "termos=" . $_POST["termos"] . "&amp;";
	$search_in .= "search_in=".$_POST["search_in"]."&amp;";

}

	}	



//order by
if ($_POST['sort'] && $_POST['order']) {
	$column = '';
	$ascdesc = '';
	switch($_POST['sort']) {
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

	switch($_POST['order']) {
		case 'asc': $ascdesc = "ASC"; break;
		case 'desc': $ascdesc = "DESC"; break;
		default: $ascdesc = "DESC"; break;
	}
} else {
	$_POST["sort"] = "id";
	$_POST["order"] = "desc";
	$column = "id";
	$ascdesc = "DESC";
}

	$orderby = "ORDER BY torrents." . $column . " " . $ascdesc;


$where = implode(" AND ", $wherea);

if ($where != "")
	$where = "WHERE $where";



//GET NUMBER FOUND FOR PAGER
$res = mysql_query("SELECT COUNT(*) FROM torrents $where");
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






$offset = is_numeric($_POST['offset']) ? $_POST['offset'] : die();
$postnumbers = is_numeric($_POST['number']) ? $_POST['number'] : die();

$res = mysql_query("SELECT torrents.id,  torrents.category, torrents.leechers, torrents.banned, torrents.comments, torrents.seeders, torrents.name,  torrents.size, torrents.added,  torrents.filename, torrents.owner, torrents.freeleech, torrents.screens1, torrents.filmeresolucao, torrents.filmeresolucalt, torrents.filme3d,  torrents.safe, torrents.apliversao, filmeaudio.name AS  filmeaudio_name , filmequalidade.name AS filmequalidade_name, filmeextensao.name AS  filmeextensao_name,  filmeano.name AS  filmeano_name,  jogosgenero.name AS  jogosgenero_name, apliformarq.name AS  apliformarq_name, aplicrack.name AS  aplicrack_name, revistatensao.name AS revistatensao_name, musicatensao.name AS  musicatensao_name, musicaqualidade.name AS  musicaqualidade_name, users.freeleechuser AS freeleechuser,  categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN filmeaudio ON torrents.filmeaudio =  filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filmeextensao ON torrents. filmeextensao =  filmeextensao.id LEFT JOIN filmeano ON torrents. filmeano =  filmeano.id LEFT JOIN jogosgenero ON torrents.jogosgenero =  jogosgenero.id  LEFT JOIN apliformarq ON torrents.apliformarq =  apliformarq.id LEFT JOIN aplicrack ON torrents.aplicrack =  aplicrack.id LEFT JOIN revistatensao ON torrents.revistatensao =  revistatensao.id LEFT JOIN musicatensao ON torrents.musicatensao =  musicatensao.id LEFT JOIN musicaqualidade ON torrents.musicaqualidade =  musicaqualidade.id $where $orderby LIMIT ".$postnumbers." OFFSET ".$offset."");



	

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
          if (isset($_POST["sort"]))
                           $sort=htmlentities(urldecode($_POST["sort"]));
                  else
                          $sort="id";

                  if (isset($_POST["order"]))
                          $order=htmlentities(urldecode($_POST["order"]));
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
		 $idteam =  $_POST['cat'];
		 $parent_catid = $_REQUEST['parent_cat'];
		 $parent_cat =  $_POST['parent_cat'];
		 $freeleech =  $_REQUEST['freeleech'];


	while ($row = mysql_fetch_assoc($res)) {
		$id = $row["id"];

		print("<li style='' class='listlist'>");
 
	$x = 1;

	foreach ($cols as $col) {
		switch ($col) {
			case 'category':
				print("<div style='width: 50px;' class='divlista'><div style='width: 50px;'>");
				if (!empty($row["cat_name"])) {
					print("<a href=\"torrents-pesquisa.php?search=".$searchstr."&cat%5B%5D=" . $row["category"] . "&".$incldead."".$freeleech."".$termos."".$search_in."&sort=0&order=".($sort=="size" && $order=="desc"?"asc":"desc")."\">");
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

              			     print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img src=images/conteudoo.png width=150 height=150 ></td><td><div align=left>Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");    
				 }
            ///filmes		 
	           elseif ($row["category"] == 2 || $row["category"] == 3 || $row["category"] == 4 || $row["category"] == 5 || $row["category"] == 6 || $row["category"] == 7 || $row["category"] == 23 || $row["category"] == 24 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 36 || $row["category"] == 37 || $row["category"] == 39 || $row["category"] == 40 || $row["category"] == 41 || $row["category"] == 42 || $row["category"] == 49 || $row["category"] == 95 || $row["category"] == 96 || $row["category"] == 97 || $row["category"] == 98 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 101 || $row["category"] == 103 || $row["category"] == 110 || $row["category"] == 118 || $row["category"] == 114 || $row["category"] == 117 || $row["category"] == 120 || $row["category"] == 124 || $row["category"] == 112) 
	              {			  
			     print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Audio: &lt;B&gt;" .$row["filmeaudio_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["filmequalidade_name"] . "&lt;/B&gt;&lt;br&gt;Extensão: &lt;B&gt;" .$row["filmeextensao_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Resolução: &lt;B&gt;" . $row["filmeresolucao"] . "X" . $row["filmeresolucalt"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                        }	
						///Cursos
				        elseif ($row["category"] == 9 || $row["category"] == 109 || $row["category"] == 113 ) 
	              {			
                         print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");				  
                        }	
										///Cursos videos
				        elseif ($row["category"] == 111 ) 
	              {			
                         print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");				  
                        }	
						///revista xx
						elseif ( $row["category"] == 104) 
	              {			
                         print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img src=images/conteudoo.png width=150 height=150 ></td><td><div align=left>Extensão: &lt;B&gt;" .$row["revistatensao_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");				  
                        }
				 			///jogos
				       elseif ($row["category"] == 10 || $row["category"] == 11 || $row["category"] == 12 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 15 || $row["category"] == 16 || $row["category"] == 43 || $row["category"] == 44 ||   $row["category"] == 120  || $row["category"] == 121  || $row["category"] == 105)    
	              {			
                        print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Plataforma: &lt;B&gt;" .$row["cat_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Genero: &lt;B&gt;" .$row["jogosgenero_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                        }	
						 			///aplicativos
				      elseif ($row["category"] == 18 || $row["category"] == 20 || $row["category"] == 94 || $row["category"] == 115 || $row["category"] == 122 || $row["category"] == 123 ) 
	              {			  
                        print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extenção: &lt;B&gt;" .$row["apliformarq_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;Crack: &lt;B&gt;" .$row["aplicrack_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                        }	
							 			///músicas
				      elseif ($row["category"] == 51 || $row["category"] == 52 || $row["category"] == 82 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 57 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 61 || $row["category"] == 62 || $row["category"] == 64 || $row["category"] == 65 || $row["category"] == 66 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89 || $row["category"] == 90 || $row["category"] == 91 ) 
	              {			  
                  print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Extenção: &lt;B&gt;" .$row["musicatensao_name"] . "&lt;/B&gt;&lt;br&gt;Qualidade: &lt;B&gt;" .$row["musicaqualidade_name"] . "&lt;/B&gt;&lt;br&gt;Ano: &lt;B&gt;" .$row["filmeano_name"] . "&lt;/B&gt;&lt;br&gt;&lt;hr&gt;Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");
                   	   }		
								    
					elseif ($row["category"] == 108 ) 
	              {			  
				  print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");	
                        }
				 else{
             	 print("<div style='text-align: left; min-width: 200px; width: 732px;' class='divlista'>".(count($expandrows)?"":"")."   <a style=\"\" title=\"<table width=100%  cellspacing=0 cellpadding=5 align=center><tr valign=top><td align=center><img border=0 width=150 height=150 src=$balon></td><td><div align=left>Lançado por &lt;B&gt;" . $row["username"] . "&lt;/B&gt;&lt;br&gt;Lançado em &lt;B&gt;" . date("d/m/y", utc_to_tz_time($row['added']))." às ". date("H:i:s", utc_to_tz_time($row['added'])) . "&lt;/B&gt;\" href=\"torrents-details.php?id=$id\">$dispname</a></div>");	
}					  
								  
			
			break;
			case 'dl':
				print("<div style='width: 60px;' class='divlista'><div style='width: 60px;'><a href=\"download.php?id=$id&amp;name=" . rawurlencode($row["filename"]) . "\"><img src='" . $site_config['SITEURL'] . "/images/icon_download.gif' border='0' alt=\"Download .torrent\" /></a></div></div>");
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











?>