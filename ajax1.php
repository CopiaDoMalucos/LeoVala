<?php
  require_once("backend/functions.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar(); 


$id = $_POST["id"];

$res2 = $pdo->prepare("SELECT request, descr, cat FROM requests WHERE liberado != 'yes' AND id = :id"); 
$res2->bindParam(':id', $id );
$res2->execute(); 


$conttorrent = "SELECT count(*) FROM requests WHERE liberado != 'yes' AND id = :id"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->bindParam(':id', $id );
$conttorr->execute(); 
$count = $conttorr->fetchColumn() ; 
if ($count == 0){
	echo "Nada encontrado";
	}
else
{


 while ($row = $res2->fetch(PDO::FETCH_ASSOC)){ 

     echo "".htmlentities($row["descr"])."";

}
}

?>