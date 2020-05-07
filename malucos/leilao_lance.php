<?php
  require_once("backend/functions1.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar(); 


$id = $_POST["id"];

$res2 = $pdo->prepare("SELECT userid, kit_id FROM leilao_user WHERE  kit_id = :id"); 
$res2->bindParam(':id', $id );
$res2->execute(); 
$row = $res2->fetch(PDO::FETCH_ASSOC);

$select_row = $pdo->prepare("SELECT * FROM users WHERE id = :username"); 
$select_row->bindParam(':username', $row["userid"]);
$select_row->execute(); 


$conttorrent = "SELECT count(*) FROM leilao_user WHERE kit_id = :id"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->bindParam(':id', $id );
$conttorr->execute(); 
$count = $conttorr->fetchColumn() ; 
if ($count == 0){
	echo "Aguardando lance inicial";
	}
else
{


 while ($row_select = $select_row->fetch(PDO::FETCH_ASSOC)){ 

     echo "<a href='account-details.php?id=$row_select[id]' title=''><font size='3'>".htmlentities($row_select["username"])."</font></a>";

}
}

?>