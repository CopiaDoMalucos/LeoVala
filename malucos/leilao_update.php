<?php
 ############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################

  require_once("backend/functionsleilao.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar();
$id = $_POST["id"];

$select_row2 = $pdo->prepare("SELECT * FROM leilao where  id= :id"); 
$select_row2->bindParam(':id', $id);
$select_row2->execute(); 
$row_select2 = $select_row2->fetch(PDO::FETCH_ASSOC); 
 


 $data1= date('Y-m-d H:i:s',utc_to_tz_time_lei($row_select2["termina"]));
date_default_timezone_set('Etc/GMT+4');

$data21 = date('Y-m-d H:i:s'); 

if ($data1  >= $data21){
 if ($CURUSER["id"] !== $row_select2["userid"] ){
  if ($CURUSER["seedbonus"] >= 100){

$select_row = $pdo->prepare("SELECT * FROM leilao where  id= :id"); 
$select_row->bindParam(':id', $id);
$select_row->execute(); 
$row_select = $select_row->fetch(PDO::FETCH_ASSOC);  




$row  = "".$row_select['termina']."";
$date = new DateTime( $row );
$date->add( new DateInterval( 'PT10S' ) );
$upleilao=$date->format( 'Y-m-d H:i:s' );


$seedbonus= 100;
$update_bonus=$pdo->prepare("UPDATE users SET seedbonus= seedbonus - :seedbonus  where id= :id");
$update_bonus->bindParam(':seedbonus', $seedbonus);
$update_bonus->bindParam(':id', $CURUSER["id"]);
$update_bonus->execute();

$select_posts=$pdo->prepare("UPDATE leilao SET termina = :upleilao where id= :id");
$select_posts->bindParam(':upleilao', $upleilao);
$select_posts->bindParam(':id', $id);
$select_posts->execute();

$contuser4 = "SELECT count(*) FROM leilao_user where  kit_id= :id "; 
$contuser4 = $pdo->prepare($contuser4); 
$contuser4->bindParam(':id', $id);
$contuser4->execute(); 
$count4 = 0;
$count4 = $contuser4->fetchColumn() ;

  if ($count4 >= 1){
$quantidadeup = 1;
$up_leilao=$pdo->prepare("UPDATE leilao_user SET userid = :userid, quantidade = quantidade + :quantidade where kit_id= :id");
$up_leilao->bindParam(':id', $id);
$up_leilao->bindParam(':userid', $CURUSER["id"]);
$up_leilao->bindParam(':quantidade', $quantidadeup);		
$up_leilao->execute();
  }else{

$quantidade = 1;
$leilao_user=$pdo->prepare("INSERT INTO leilao_user (userid, kit_id, quantidade)VALUES (:userid, :kit_id, :quantidade )");
$leilao_user->bindParam(':userid', $CURUSER["id"]);
$leilao_user->bindParam(':kit_id', $id);
$leilao_user->bindParam(':quantidade', $quantidade);				  
$leilao_user->execute();
}
}
}
}

?>