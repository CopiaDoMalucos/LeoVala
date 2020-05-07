<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
  
  require_once("backend/functions.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar();


$requestid = (int)$_GET["id"];
$userid = (int)$CURUSER["id"];



$stmt = $pdo->prepare("SELECT * FROM addedrequests WHERE requestid = :requestid and userid = :userid "); 
$stmt->bindParam(':userid', $userid);
$stmt->bindParam(':requestid', $requestid);
$stmt->execute(); 
$voted = $stmt->fetchColumn() ; 

$stmt1 = $pdo->prepare("SELECT userid FROM requests WHERE id = :id "); 
$stmt1->bindParam(':id', $requestid);
$stmt1->execute(); 
$row = $stmt1->fetch(PDO::FETCH_ASSOC);  

if ($CURUSER["id"] == $row["userid"]) {
echo"";
  show_error_msg("Erro!", "<center><p>Você não pode votar no seu pedido.</p><p><br><a href=pedidos_torrents.php><b>Voltar</b></a></p></center>", 1);

}
if ($voted) {
  show_error_msg("Erro!", "<center><p>Você não pode votar duas vezes no mesmo pedido.</p><p><br><a href=pedidos_torrents.php><b>Voltar</b></a></p></center>", 1);
}else
 {
 stdhead("Voto confirmar");

begin_frame("Voto confirmar");
	$stmtup=$pdo->prepare("UPDATE requests SET hits = hits + 1 WHERE id=:id");
    $stmtup->bindParam(':id', $requestid);
    $stmtup->execute();

	$addpedidotor=$pdo->prepare("INSERT INTO addedrequests (id, requestid, userid ) VALUES (0, :requestid, :userid )");
	$addpedidotor->bindParam(':requestid', $requestid);
    $addpedidotor->bindParam(':userid', $userid);
    $addpedidotor->execute(); 


print("<br><center><p>Voto computado! Quando o pedido for atendido, você será avisado.</p><br><p><a href=pedidos_torrents.php><b>Continuar</b></a></p></center><br><br>");

}

end_frame();

stdfoot();
?>
