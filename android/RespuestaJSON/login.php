<?php
header('Content-type: application/json');


$server = "localhost";
$username = "root";
$password = "1c2a3u4a5a6mor";
$database = "malucos";

$con = mysql_connect($server, $username, $password) or die ("Could not connect: " . mysql_error());
mysql_select_db($database, $con);



if(isset($_POST['username'],$_POST['password'])){
$email=$_POST['username']; $password=md5($_POST['password']);
$query=mysql_query("SELECT * FROM users  WHERE username='".$email."' AND password='".$password."'");
if(mysql_num_rows($query)>0){
$json=true;
echo json_encode($json);
}else{

$json=false;
echo json_encode($json);
}
}

	//Se declara que esta es una aplicacion que genera un JSON
			header('Content-type: application/json');
			//Se abre el acceso a las conexiones que requieran de esta aplicacion
			header("Access-Control-Allow-Origin: *");


?>