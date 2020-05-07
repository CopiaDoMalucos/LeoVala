<?php
 ############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################

  require_once("backend/functionsleilao.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar();

  $option = $_GET["id"];
stdhead("Leilão de kit");

$stmt = $pdo->prepare("SELECT id, userid, avatar, sing, added, termina, tema, estilo FROM leilao  WHERE  id= ?"); 
$stmt->bindParam(1,$option);
$stmt->execute(); 
$x2 = $stmt->fetch(PDO::FETCH_ASSOC); 
if (!$x2)
	show_error_msg("ERROR", 'Kit não encontrado.', 1);

$r = $pdo->prepare("SELECT * FROM users WHERE id=".$x2["userid"].""); 
$r->execute(); 
$user = $r->fetch(PDO::FETCH_ASSOC); 

begin_framec("Leilão de kit");

print("<center>[ <a href='leilao.php'> Leiloar um kit </a> |  <a href='leilao_online.php'> Kits geral </a> | <a href='leilao_online.php?acao=1'> Kits sendo leiloados </a> | <a href='leilao_online.php?acao=2'> Kits finalizados </a> ]</center>");
print("<br>");
print("<center><b>Observação Importante</B>:<br>
1. O custo do lance é de <b>100 MS ptos</b>.<br>

</center>");
print("<br>");



print"<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><b>Kits sendo leiloado (<a href='account-details.php?id=".$user["id"]."'>".$user["username"]."</a>)</b></tr>");


			$char1 = 30; //cut length 
			$smallname = htmlspecialchars(CutName($x2["tema"], $char1));


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>TESTE</title>

<script type="text/javascript">

		var YY = <?php echo date("Y", utc_to_tz_time_lei($x2['termina'])); ?>;
	var MM = <?php echo date("m", utc_to_tz_time_lei($x2['termina'])); ?>;
	var DD = <?php echo date("d", utc_to_tz_time_lei($x2['termina'])); ?>;
	var HH = <?php echo date("H", utc_to_tz_time_lei($x2['termina'])); ?>;
	var MI = <?php echo date("i", utc_to_tz_time_lei($x2['termina'])); ?>;
	var SS = <?php echo date("s", utc_to_tz_time_lei($x2['termina'])); ?>;
	          var chave_id =   <?php echo $x2['id']; ?>;

		function somaContador() {

	<?php 
 if ($CURUSER["id"] !== $x2["userid"] ){
	if ($CURUSER["seedbonus"] >= 100){?>
		SS += 10;
		atualizaContador();
	<?php }else{?>
	SS += 0;
	<?php }
	}
	?>
	
          $("#descped").load("leilao_update.php",{id:chave_id})


	}
	function atualizaContador() {  


  $.post("data.php",function(data){

eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(c/a))+String.fromCharCode(c%a+161)};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\[\xa1-\xff]+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp(e(c),'g'),k[c])}}return p}('£ ¡=ª;£ §=¥ ¤(¡.¢("/")[0],¡.¢("/")[1]-1,¡.¢("/")[2],¡.¢("/")[3],¡.¢("/")[4],¡.¢("/")[5]);£ ¦=¥ ¤(¨,©-1,±,«,¯,®);£ ¬=­((¦-§)/°);',17,17,'dataatual|split|var|Date|new|futuro|hoje|YY|MM|data|HH|ss|parseInt|SS|MI|1000|DD'.split('|'),0,{}))

		

 		
	eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(c/a))+String.fromCharCode(c%a+161)};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\[\xa1-\xff]+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp(e(c),'g'),k[c])}}return p}('§ ¢=©(£/¨);§ ¡=©(¢/¨);§ ¤=©(¡/ª);£=£-(¢*¨);¢=¢-(¡*¨);¡=¡-(¤*ª);§ ¦=\'\';¦+=(¤&&¤>1)?¤+\' ­ + \':(¤==1?\'1 « + \':\'\');¦+=(¡&&¡>=¥)?¡+\':\':((¡<¥&&¡>0)?\'0\'+¡+\':\':\'\');¦+=(¢&&¢>=¥)?¢+\':\':((¢<¥&&¢>0)?\'0\'+¢+\':\':\'\');¦+=(£&&£>=¥)?£:(£<¥?\'0\'+£:\':¬\');',13,13,'hh|mm|ss|dd|10|faltam|var|60|parseInt|24|DIA|00|DIAS'.split('|'),0,{}))

	
	
	
		if (dd+hh+mm+ss > 0) {
		
		
					document.getElementById('contador').innerHTML = faltam;    



          $("#descped").load("leilao_lance.php",{id:chave_id})
		 



          $("#l_quantidade1").load("leilao_quant1.php",{id:chave_id})
		 
    $("#l_bonus").load("leilao_bonus.php",{id:chave_id})

          $("#l_quantidade2").load("leilao_quant2.php",{id:chave_id})
		 

			setTimeout(atualizaContador,1000);  
		} else {
          $("#l_quantidade1").load("leilao_quant1.php",{id:chave_id})
		 
    $("#l_bonus").load("leilao_bonus.php",{id:chave_id})

          $("#l_quantidade2").load("leilao_quant2.php",{id:chave_id})
			document.getElementById('contador').innerHTML = 'FINALIZADO'; 

          $("#descped").load("leilao_lance.php",{id:chave_id})

			setTimeout(atualizaContador,1000);  
		}
		
		
		
		

})
	}
	
</script>

</head>
<body onload="atualizaContador()">
<td width="25%" valign="top" align="center">                <div style="position:relative; width:200px; height:330px;">
                
                <div style=" width:50px; right:-25px; position: absolute; top: 80px;">
                	                
                	                                    </div>
                
                
                <table width="200" cellspacing="0" cellpadding="0" border="0" align="center">
                  <tbody><tr>
                    <td valign="bottom" height="51" align="center" id="LBox1_1660" style="background-image: url(&quot;images/layout_10.png&quot;); background-repeat: no-repeat; background-position: center center; color: rgb(0, 0, 0); font-size: 12px;">
                      <strong> <?php echo $smallname ?></strong>
                    </td>
                  </tr>
                  <tr>
                    <td height="220" align="center" id="LBox2_1660" style="background-image: url(&quot;images/layout_16.png&quot;); background-repeat: no-repeat; background-position: center top;">
                    
                        <table width="200" cellspacing="0" cellpadding="0" border="0">
                          <tbody><tr>
                            <td height="133" align="center" style="background-image:url('<?php echo $x2['avatar']; ?>'); background-repeat:no-repeat; background-position:center; padding-right:1px; cursor:pointer;">
                            
                                <table width="222" cellspacing="0" cellpadding="0" border="0" id="BoxInicio_1660" style="background-image: url(&quot;images/cronometro_fundo.png&quot;); background-repeat: no-repeat; background-position: center center;">
                                  <tbody><tr>
                                    <td height="69" align="center" style="color:#FFF;">
                                        <span style="font-size:12px;">Faltam:</span>
                                        <br>
                                        <span id="contador"><strong></strong></span>
                                        <br>
                                        <span style="font-size:12px;"></span><br>
                                    </td>
                                  </tr>
                                </tbody></table>
                            
                            </td>
                          </tr> 
                          <tr>
                            <td valign="bottom" align="center" style="font-size:30px; color:#b01f05;"><strong><em id="l_bonus"></em></strong></td>
                          </tr>
                          <tr>
                            <td height="25" align="center" id="descped" style="font-size:12px; color:#1b1b1b;"></td>
                          </tr>
                        </tbody></table>
                    
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" align="center">
                    
                        <table width="224" cellspacing="0" cellpadding="0" border="0" style="padding-left:5px;">
                          <tbody><tr>
                            <td width="155" valign="top" height="63" align="right" id="LBox3_1660" style="background-image: url(&quot;images/layout_24.png&quot;); background-repeat: no-repeat; background-position: right bottom;">
                            
                                <table width="100" cellspacing="0" cellpadding="0" border="0" style="padding-top:9px;">
                                  <tbody><tr style="font-size:38px; color:#FFF;">
                                    <td width="49" height="50" align="center"><strong id="l_quantidade1"></strong></td>
                                    <td width="51" align="center"><strong id="l_quantidade2"></strong></td>
                                  </tr>
                                </tbody></table>
                                
                            </td>
                            <td valign="top" id="L_Botao_Box_1660">
                                <div id="L_BotaoA_1660">
                                  <a  id="somaContador"  style="cursor:pointer"  onclick="javascript:somaContador();"><img border="0" style="float:left; cursor:pointer;" src="images/layout_25.png" ></a>
                                </div>
                                <div style="display:none;" id="L_BotaoB_1660">
                                    <img border="0" style="float:left;" src="images/layout_25.png" id="LBox5_1660">
                                </div>
                            </td>
                          </tr>
                        </tbody></table>
                    
                    </td>
                  </tr>
                </tbody></table>
                </div>
                </td>
</body>
</html>

<?php





print"</table>";




end_framec();
begin_framec("Assinatura");	
if ($x2["estilo"] == 1)
{
 $usersignature = $x2["sing"];
echo"<center><img border='0'  src='".$usersignature."' ></center>";	
}
if ($x2["estilo"] == 2)
{
 $usersignature1 = $x2["sing"];
$user1 = "$usersignature1";

 $usersing = "[flash 450,250]".$user1."[/flash]";

 $usersignature = stripslashes(format_comment($usersing));
echo"<center> $usersignature</center>";	


 
 
 
}	

end_framec();

stdfoot();
?>