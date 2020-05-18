<?php
header('Content-Type: text/html; charset=utf-8');	   
	   function conectar(){
define('host','localhost');
define('user','fastshr_bd');
define('pass','ariglr231282');
try {
$conn = new PDO ( "mysql:host=".host.";dbname=fastshr_bd",user,pass);
$conn->exec("set names utf8");
}catch (PDOException  $e) {
	echo "Atencao: Ocorreu um problema".$e->getMessage();
}
return $conn;
}
?>
