<?php
  require_once("backend/functions.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar(); 


$id = $_POST["id"];

$res2 = $pdo->prepare("SELECT id, request, descr, cat FROM requests WHERE liberado != 'yes' AND cat = :id"); 
$res2->bindParam(':id', $id );
$res2->execute(); 


$conttorrent = "SELECT count(*) FROM requests WHERE liberado != 'yes' AND cat = :id"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->bindParam(':id', $id );
$conttorr->execute(); 
$count = $conttorr->fetchColumn() ; 

if ($count == 0){
	echo "<option value='0'>Nada encontrado</option>";
	}
else
{

 while ($row = $res2->fetch(PDO::FETCH_ASSOC)){ 

     echo "<option value='".$row["id"]."'>".htmlentities($row["request"])."</option>\n";
		
}
 echo "<option value=''></option>\n";
}
?>