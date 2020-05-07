<?php
header('Content-Type: text/html; charset=utf-8');	   
	   function conectar(){
define('host','localhost');
define('user','root');
define('pass','1g2a3b4r5i6e7l##123');
try {
$conn = new PDO ( "mysql:host=".host.";dbname=malucos",user,pass);
$conn->exec("set names utf8");
}catch (PDOException  $e) {
	echo "Atencao: Ocorreu um problema".$e->getMessage();
}
return $conn;
}
?>