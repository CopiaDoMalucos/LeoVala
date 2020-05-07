<?php
  require_once("backend/functions1.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar(); 


$id = $_POST["id"];

$res2 = $pdo->prepare("SELECT quantidade FROM leilao_user WHERE  kit_id = :id"); 
$res2->bindParam(':id', $id );
$res2->execute(); 





$conttorrent = "SELECT count(*) FROM leilao_user WHERE kit_id = :id"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->bindParam(':id', $id );
$conttorr->execute(); 
$count = $conttorr->fetchColumn() ; 
if ($count == 0){
	echo "0";
	}
else
{


 while ($row = $res2->fetch(PDO::FETCH_ASSOC)){ 

$texto1= "".$row["quantidade"]."";
$texto2= $texto1 * 100;
echo " Pontos ".htmlentities($texto2).""; 

}
}

?>