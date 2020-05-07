<?php
  require_once("backend/functions.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar(); 


$id = $_POST["id"];
echo"$id";
$res2 = $pdo->prepare("SELECT id, request, descr, cat FROM requests WHERE cat = :id"); 
$res2->bindParam(':id', $id );
$res2->execute(); 


 while ($row = $res2->fetch(PDO::FETCH_ASSOC)){ 

     echo "<option value='".$row["id"]."'>".htmlentities($row["request"])."</option>\n";

}

?>