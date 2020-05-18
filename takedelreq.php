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

global $CURUSER;
$pdo = conectar();

	
stdhead("Pedido apagar");
begin_frame("Pedido apagar");

if ($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Moderador"){
if (empty($_POST["delreq"])){
print("<CENTER>Você deve selecionar pelo menos um pedido de exclusão.</CENTER>");
end_frame();
stdfoot();
die;
}
$delprepare = "DELETE FROM requests WHERE id IN (" . implode(", ", $_POST[delreq]) . ")"; 
$del1 = $pdo->prepare($delprepare); 
$del1->execute(); 
$delprepare2 = "DELETE FROM addedrequests WHERE requestid IN (" . implode(", ", $_POST[delreq]) . ")"; 
$del2 = $pdo->prepare($delprepare2); 
$del2->execute(); 
print("<CENTER>Pedido excluídos.</CENTER>");

echo "<BR><BR>";
} else {
foreach ($_POST[delreq] as $del_req){
$delete_ok = checkRequestOwnership($CURUSER[id],$del_req);
if ($delete_ok){
$delprepare = "DELETE FROM requests WHERE id IN ($del_req)"; 
$del1 = $pdo->prepare($delprepare); 
$del1->execute(); 
$delprepare2 = "DELETE FROM addedrequests WHERE requestid IN ($del_req)"; 
$del2 = $pdo->prepare($delprepare2); 
$del2->execute(); 
print("<CENTER>pedir ID $del_req excluídos</CENTER>");
} else {
print("<CENTER>Sem permissão para excluir o pedido id $del_req</CENTER>");
}
}
}

end_frame();
stdfoot();



function checkRequestOwnership ($user, $delete_req){
$conttorrent = "SELECT * FROM requests WHERE userid=$user AND id = $delete_req"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->execute(); 
$num = $conttorr->fetchColumn() ; 

if ($num > 0)
return(true);
else
return(false);
}


?>
