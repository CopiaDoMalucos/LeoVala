<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn();

loggedinonly();

function grupopedi($ts = 0)
{    

$month = floor($ts / 2629743); 	
 $ts = $ts - ($month*2629743); 
$weeks = floor($ts / 604800); 
 $ts = $ts - ($weeks*604800);                                                                                                                                                                                       
$days = floor($ts / 86400);
$ts = $ts - ($days*86400);
$hours = floor($ts / 3600 ); 
$ts = $ts - ($hours*3600);     
$mins = floor($ts / 60) % 60;

return sprintf( '%d mês(es), %d semana(s), %d dia(s), %d hora(s), %d minuto(s)',  $month, $weeks, $days, $hours, $mins);
}
function grupomes($ts = 0)
{    

$month = floor($ts / 2629743); 	

return sprintf( '%d mese(s)',  $month);
}


  
if ($CURUSER["downloaded"] > 0) {
    $ratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);

}else{
	$ratio = '0.00';
}

   $seedbonus = $CURUSER["seedbonus"];
   
     $verpedido = mysql_query("SELECT * FROM kitpedido WHERE  userid=" . $CURUSER["id"] ."");    
   
$exepedi=mysql_fetch_assoc($verpedido) ;


$iniciovip =  $exepedi['added'];

	
$datas2vip = date("Y-m-d H:i:s", utc_to_tz_time($iniciovip)); 

	
$datasemvip = date('Y-m-d H:i:s');    
$datasvip = date("Y-m-d H:i:s", utc_to_tz_time($datasemvip)); 


		
$data_inicialvip = $datas2vip;

$data_finalvip = $datasemvip;

$time_inicialvip = strtotime($data_inicialvip);

$time_finalvip = strtotime($data_finalvip);

$diferencavip = $time_finalvip - $time_inicialvip; // 19522800 segundos

$diasvip = (int)floor( $diferencavip / (60 * 60 * 24)); // 225 dias
if (mysql_num_rows($verpedido) == 0){
$validarvip = "verdade";
$diferencavip = 0; 
} 
if ($CURUSER["donator"] == "y" ){
 if(grupomes($diferencavip)  >= 3){
  $added12vip = 'Meta atingida';
 $added123vip = "<font color='Green'>$added12vip</font>";
   
  }else{
    $added12vip = 'Meta não atingida';
 $added123vip = "<font color='red'>$added12vip</font>";

  }	
$contavip = "3 meses";
}else{
 if(grupomes($diferencavip)  >= 6){
  $added12vip = 'Meta atingida';
 $added123vip = "<font color='Green'>$added12vip</font>";
   
  }else{
    $added12vip = 'Meta não atingida';
 $added123vip = "<font color='red'>$added12vip</font>";

  }	
$contavip = "6 meses";
}
 if(grupomes($diferenca)  >= 3){
  $added12 = 'Meta atingida';
 $added123 = "<font color='Green'>$added12</font>";
   
  }else{
    $added12 = 'Meta não atingida';
 $added123 = "<font color='red'>$added12</font>";

  }	
if  ($validarvip == "verdade"){
$added123vip = "<font color='Green'>Meta atingida</font>";
}

     $rei = mysql_query("SELECT *  FROM users WHERE id=" . $CURUSER["id"] ."") or sqlerr();
    while ($arr2 = mysql_fetch_assoc($rei)) {
$inicio =  $arr2['added'];

	
$datas2 = date("Y-m-d H:i:s", utc_to_tz_time($inicio)); 

				
$datasem = date('Y-m-d H:i:s');    
$datas = date("Y-m-d H:i:s", utc_to_tz_time($datasem)); 



$data_inicial = $datas2;

$data_final = $datas;

$time_inicial = strtotime($data_inicial);

$time_final = strtotime($data_final);

$diferenca = $time_final - $time_inicial; // 19522800 segundos

$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
           if ($arr2["warned"] == "no")
                $warned22 = "Não";
            else
                $warned22 = "Sim";
   

 }

  if(number_format("$ratio",2)>= '1.00'){
  $ratio12 = 'Meta atingida';
 $ratio1 = "<font color='Green'>$ratio12</font>";
   
  }else{
    $ratio12 = 'Meta não atingida';
 $ratio1 = "<font color='red'>$ratio12</font>";

  }	

 if(grupomes($diferenca)  >= 3){
  $added12 = 'Meta atingida';
 $added123 = "<font color='Green'>$added12</font>";
   
  }else{
    $added12 = 'Meta não atingida';
 $added123 = "<font color='red'>$added12</font>";

  }	
 
 if($seedbonus >= '200'){
  $seedbonusr = 'Meta atingida';
 $seedbonusr1 = "<font color='Green'>$seedbonusr</font>";
   
  }else{
    $seedbonusr = 'Meta não atingida';
 $seedbonusr1 = "<font color='red'>$seedbonusr</font>";

  }	
 //$numtorrents
 		$res4 = SQL_Query_exec("SELECT COUNT(*) FROM forum_posts WHERE userid=" . $CURUSER["id"] ."") or forumsqlerr();
		$arr33 = mysql_fetch_row($res4);
		$forumposts = $arr33[0];
  if($forumposts >= '5'){
  $forumpostsr = 'Meta atingida';
 $forumpostsr1 = "<font color='Green'>$forumpostsr</font>";
   
  }else{
    $forumpostsr = 'Meta não atingida';
 $forumpostsr1 = "<font color='red'>$forumpostsr</font>";

  }	
  if ($added12vip == "Meta atingida" || $validarvip == "verdade" ){
$validadovip = "verdade" ;

}
 



$requesttitle = $_POST["requesttitle"];
$screens1 = $_POST["screens1"];
$screens2 = $_POST["screens2"];
$screens3 = $_POST["screens3"];
$descr = $_POST["descr"];
$cats = $_POST["category"];
$txt_outro = $_POST["txt_outro"];

$userid = sqlesc($userid);
$screens1 = sqlesc($screens1);
$screens2 = sqlesc($screens2);
$screens3 = sqlesc($screens3);
$descr = sqlesc($descr);
$cat = sqlesc($cats);
$txt_outro = sqlesc($txt_outro);

if (!$cats){
  show_error_msg("Erro", "Selecione um tipo de imagem");
}
if (!$requesttitle){
  show_error_msg("Erro", "Por favor é obrigatorio preencher a o campo Tema!!!");
}
if (!$screens1){
  show_error_msg("Erro", "Por favor é obrigatorio preencher a o campo screens1!!!");
}
if (!$screens2){
  show_error_msg("Erro", "Por favor é obrigatorio preencher a o campo screens2!!!");
}

if (!$descr){
  show_error_msg("Erro", "Por favor é obrigatorio preencher a o campo descrição!!!");
}
 if ($forumpostsr == "Meta atingida" && $seedbonusr == "Meta atingida" && $added12 == "Meta atingida"  && $validadovip == "verdade" && $ratio12 == "Meta atingida"){ 

$bonus_kit = '200';
 mysql_query("INSERT INTO kitpedido (userid, cat, screens1, screens2, screens3, descr, bonus_kit, added, kusername, estilo) VALUES($CURUSER[id], $cat, $screens1, $screens2, $screens3, $descr, $bonus_kit, '" . get_date_time() . "', '$CURUSER[username]', $txt_outro)") or die(mysql_error());
  SQL_Query_exec("UPDATE `users` SET `seedbonus` = `seedbonus`- '$bonus_kit' WHERE `id` = '" . $CURUSER["id"] . "'") or exit(mysql_error());
	stdhead("Gestão de kits");
	begin_framec("Gestão de kits");	

			print("<center>Seu pedido foi realizado com sucesso!!!!<br>[<a href='pedirkit.php'>Voltar</a>]</center>");

	end_framec();
	stdfoot();
	die();
}else{
	stdhead("Gestão de kits");
	begin_framec("Gestão de kits");	
	print("<center>Desculpe mais você não pode pedir kit no momento!!!!<br>[<a href='pedirkit.php'>Voltar</a>]</center>");
	end_framec();
	stdfoot();
	}

?>
