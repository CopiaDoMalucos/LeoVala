<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
require ("backend/conexao.php");
dbconn();
loggedinonly();
$pdo = conectar();

$tempo = $_POST["tempo"];
$estilo = $_POST["estilo"];
$added = get_date_time();

if ($tempo == '12' || $tempo == '24' || $tempo == '36' || $tempo == '48' )
{
$ab = 'verdadeiro';
}else{
$ab = 'falso';
}
if ($ab == 'falso'){

  show_error_msg("Erro!", '
<br>Ops valor do tempo inválido.
<br><a href=leilao.php><b>Voltar</b></a>', 1);
}
if ($estilo == '1' || $estilo == '2'){

$abx = 'verdadeiro';
}else
{
$abx = 'falso';
}
if ($abx == 'falso'){
  show_error_msg("Erro!", '
<br>Ops tipo escolhido inválido.
<br><a href=leilao.php><b>Voltar</b></a>', 1);
}


if ($tempo == 12){
$termina = gmdate("Y-m-d H:i:s", gmtime() + (46800));
}
if ($tempo == 24){
$termina = gmdate("Y-m-d H:i:s", gmtime() + (90000));
}
if ($tempo == 36){
$termina = gmdate("Y-m-d H:i:s", gmtime() + (133200));
}
if ($tempo == 48){
$termina = gmdate("Y-m-d H:i:s", gmtime() + (176400));
}

	$addleilao=$pdo->prepare("INSERT INTO leilao (userid, avatar, sing, added, termina, tema, estilo ) VALUES (:uid, :avat, :sin, :added, :term, :tem, :est )");
	$addleilao->bindParam(':uid', $CURUSER["id"]);
    $addleilao->bindParam(':avat', $_POST["linkavatar"]);
    $addleilao->bindParam(':sin', $_POST["linksing"]);	
    $addleilao->bindParam(':added', $added);	
    $addleilao->bindParam(':term', $termina);
    $addleilao->bindParam(':tem', $_POST["tema"]);	
    $addleilao->bindParam(':est', $_POST["estilo"]);	
    $addleilao->execute(); 

  show_error_msg("Sucesso!", '
<br>Obrigado!.

<br><a href=leilao.php><b>Voltar</b></a>', 1);


?>