<?php
require_once 'backend/functions.php';


$action = $_POST['action'];

switch($action){
	case "pesquisar":
		$string = (string) "";
		$search = sqlesc('%'.str_replace(" ", "%", $_POST['torrent']).'%');
		$exe_search = mysql_query("SELECT id,name FROM torrents WHERE name LIKE {$search} AND safe='yes';");
		while($row = mysql_fetch_array($exe_search)){
			$string .= "<a href='torrents-details.php?id={$row['id']}'>{$row['name']}</a><br />";
		}
		if(strlen($string) > 0)
			echo "<span style='color: #FFFF00;'>Torrents Semelhantes Encontrados:</span> <br />{$string}";
		else
			echo "Não foi encontrado nenhum torrent com nome semelhante ao seu!<br />";
		break;
	default:
	break;
}

?>