<?php

   require_once('../backend/functionsajax.php');
ob_start();
dbconn();
global $CURUSER;
loggedinonly();


$retorno = array(); 


$char1 = 20; //cut length

$res = mysql_query("SELECT id,msg,subject FROM messages WHERE receiver='$CURUSER[id]' AND unread='yes' AND location IN ('in','both')  AND UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(added) < 30 ORDER BY id DESC LIMIT 5") 
       or $retorno['retorno'] = false;
	   
$num = mysql_num_rows($res);

if($num > 0){
	while($r = mysql_fetch_array($res)){
		$retorno['msgs'][]= array(
			'id' => $r['id'],
			'subject' => $r['subject'],
			'msg' => CutName(htmlspecialchars($r["msg"]), $char1),
			'image' => '<img width="10" height="10" src="../images/mensagem.png" /> alguns segundos atrás',
		);
	}
}

if(!isset($retorno['retorno'])){
	$retorno['retorno'] = true;
}

echo json_encode($retorno);
?>