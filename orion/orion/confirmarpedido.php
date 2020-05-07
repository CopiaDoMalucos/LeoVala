<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
require ("backend/conexao.php");
dbconn();
loggedinonly();
$pdo = conectar();

if ($CURUSER["seedbonus"] >= 100){
$userid = $CURUSER["id"];
$cats = $_POST["category"];
$requestartist = $_POST["requestartist"];
$requesttitle = $_POST["requesttitle"];
$descr = $_POST["descr"];
$added = get_date_time();
$seedbonus = '100';

	$row_bonus=$pdo->prepare("UPDATE users SET seedbonus = seedbonus - :seedbonus  WHERE id= :uid");
	$row_bonus->bindParam(':uid', $userid);
	$row_bonus->bindParam(':seedbonus', $seedbonus);
    $row_bonus->execute();

	$addpedidotor=$pdo->prepare("INSERT INTO requests (hits, userid, cat, request, descr, added ) VALUES (0, :uid, :cat, :request, :descr, :added )");
	$addpedidotor->bindParam(':uid', $userid);
    $addpedidotor->bindParam(':cat', $cats);
    $addpedidotor->bindParam(':request', $requesttitle);	
    $addpedidotor->bindParam(':descr', $descr);	
    $addpedidotor->bindParam(':added', $added);		
    $addpedidotor->execute(); 
$id = $pdo->lastInsertId();



header("Refresh: 0; url=pedido_add.php");
}
else{
  show_error_msg("Erro!", 'Você não tem pontos suficiente para fazer o pedido!<br>


<br>Obrigado!.

<br><a href=pedido_add.php><b>Voltar</b></a>', 1);
}







?>
