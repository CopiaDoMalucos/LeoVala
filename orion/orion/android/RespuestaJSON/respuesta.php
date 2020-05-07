<?php
function mksize ($s, $precision = 2) {
	$suf = array("B", "kB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

	for ($i = 1, $x = 0; $i <= count($suf); $i++, $x++) {
		if ($s < pow(1024, $i) || $i == count($suf)) // Change 1024 to 1000 if you want 0.98GB instead of 1,0000MB
			return number_format($s/pow(1024, $x), $precision)." ".$suf[$x];
	}
}
//Generar Respuesta JSON con PHP y MySQL

	//Se genera la Conexion a la base de datos MysQL
 
        $host = "localhost";//host
        $usuario = "root";//usuarioBD
        $pass = "1c2a3u4a5a6mor";//Contraseña
        $bd = "malucos";//Base deDatos
		
		$servidor = mysql_connect($host, $usuario, $pass);
		
		//1_Se elige el formato de datos para lla conexion UTF8
	 	mysql_set_charset("utf8", $servidor);
		$conexion = mysql_select_db($bd, $servidor);
		$id = json_encode($_GET["id"]);
			//Se prepara la peticion

			//2_Se establece la consulta a la BD
			$consulta = "SELECT * FROM users WHERE username=$id ";
			$sql = mysql_query($consulta);
	 
			if ( ! $sql ) {
				echo "La conexion no se logró".mysql_error();
				die;
			}	
			
			//3_Se declara un arreglo
			$datos= array();
			
						//SE genera el archivo JSON
			while ($obj = mysql_fetch_object($sql)) {
				$datos[] = array('username' => $obj->username,
							   'uploaded' => mksize($obj->uploaded),
							   'downloaded' => mksize($obj->downloaded),
					);
			}
			
			echo '' . json_encode($datos) . '';
			
			mysql_close($servidor);//Se cierra la conexion
			
			//Se declara que esta es una aplicacion que genera un JSON
			header('Content-type: application/json');
			//Se abre el acceso a las conexiones que requieran de esta aplicacion
			header("Access-Control-Allow-Origin: *");


?>