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

stdhead("Leilão de kit");

begin_framec("Leilão de kit");
print("<center>[ <a href='leilao.php'> Leiloar um kit </a> |  <a href='leilao_online.php'> Kits geral </a> | <a href='leilao_online.php?acao=1'> Kits sendo leiloados </a> | <a href='leilao_online.php?acao=2'> Kits finalizados </a> ]</center>");
print("<br>");
print("<br>");

$colunas="2"; //quantidade de colunas
$cont="1"; //contador

print"<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><b>Kits sendo leiloados</b></tr>");
 $data2 = date("Y-m-d H:i:s", $inicio);  
 $termina = gmdate("Y-m-d H:i:s", gmtime() );

   $whereus=array();

  if ($_GET['acao'] == 1 ){
         $whereus[] = "WHERE date_format(termina,'%Y-%m-%d %H:%i:%s')>='$termina'";

  }

  if ($_GET['acao'] == 2 ){
           $whereus[] = "WHERE date_format(termina,'%Y-%m-%d %H:%i:%s')<='$termina'";
  
  }
      $whereg = implode("", $whereus);
  

$conttorrent = "SELECT count(*) FROM leilao ". $whereg .""; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->execute(); 
$rowconttor = $conttorr->fetchColumn() ; 

list($pagertop, $pagerbottom, $limit) = pager(10, $rowconttor, "leilao_online.php?acao=".$_GET['acao']."&amp;");

echo $pagertop;

$sql = $pdo->query("SELECT id, userid, avatar, sing, added, termina, tema, estilo FROM leilao  ". $whereg ." ORDER BY  termina DESC $limit");
while ($x = $sql->fetch(PDO::FETCH_ASSOC)) {
$r = $pdo->prepare("SELECT * FROM users WHERE id=".$x["userid"].""); 
$r->execute(); 
$user = $r->fetch(PDO::FETCH_ASSOC); 



			$char1 = 30; //cut length 
			$smallname = htmlspecialchars(CutName($x["tema"], $char1));

//se o cont for igual a 1 ele começa a linha da tabela
if($cont==1){
print"<tr align=center colspan=2 class=tab1_col3>";
}
print"<td  >";

if ($x["estilo"] == 1)
{
$estilo = 'Estática';
}
if ($x["estilo"] == 2)
{
$estilo = 'Animada';
}
 $data1= date('Y-m-d H:i:s',utc_to_tz_time_lei($x["termina"]));

date_default_timezone_set('Etc/GMT+4');

$data21 = date('Y-m-d H:i:s'); 

   
if ($data1  >= $data21){
$aberto= '/images/layout_28.png';
$aberto1= '/images/layout_30.png';
$encerrado= 'Encerra em';
}else{
$aberto= '/images/layout_27.png';
$aberto1= '/images/layout_29.png';
$encerrado= 'Encerrado  em';
}
?>
<br>
       <div style="position:relative; width:200px; height:330px;">
                
                <div style=" width:50px; right:-25px; position: absolute; top: 80px;">
                	                
                	                                    </div>
                
                
                <table width="200" cellspacing="0" cellpadding="0" border="0" align="center">
				  <td style="position:relative; width:200px;"colspan="0" class="tab1_cab1"><b>Leiloado por (<a href="account-details.php?id=<?php echo $user['id']?>"><?php echo $user['username']?></a>)</b></td>
                  <tbody>
				
				  
				  <tr>
				  
                    <td valign="bottom" height="51" align="center" id="LBox1_1660" style="background-image: url(&quot;images/layout_10.png&quot;); background-repeat: no-repeat; background-position: center center; color: rgb(0, 0, 0); font-size: 12px;">
                      <strong>Tema: <?php echo $smallname ?><br>Tipo: <?php echo $estilo ?><br></strong>
					  
                    </td>
                  </tr>
                  <tr>
                    <td height="220" align="center" id="LBox2_1660" style="background-image: url(&quot;images/layout_16.png&quot;); background-repeat: no-repeat; background-position: center top;">
                    
                        <table width="200" cellspacing="0" cellpadding="0" border="0">
                          <tbody><tr>

                             <td height="133" align="center" onclick="window.location = 'leilao_detalhes.php?id=<?php echo $x['id']?>';" style="background-image:url('<?php echo $x['avatar']; ?>'); background-repeat:no-repeat; background-position:center; padding-right:1px; cursor:pointer;">
                            
                                <table width="222" cellspacing="0" cellpadding="0" border="0" id="BoxInicio_1660" style="background-image: url(&quot;images/cronometro_fundo.png&quot;); background-repeat: no-repeat; background-position: center center;">
                                  <tbody><tr>
                                    <td height="69" align="center" style="color:#FFF;">
									
                                        <span style="font-size:12px;"><?php echo $encerrado ?>:</span>
                                        <br>
                                        <span id="contador"><strong><?php echo date("d/m/y", utc_to_tz_time($x['termina']))." às ". date("H:i:s", utc_to_tz_time($x['termina']))?></strong></span>
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
                            <td width="155" valign="top" height="63" align="right" id="LBox3_1660" style="background-image: url(&quot;<?php echo $aberto1 ;?>&quot;); background-repeat: no-repeat; background-position: right bottom;">
                            
                                <table width="100" cellspacing="0" cellpadding="0" border="0" style="padding-top:9px;">
                                  <tbody><tr style="font-size:38px; color:#FFF;">
                                    <td width="49" height="50" align="center"><strong id="l_quantidade1"></strong></td>
                                    <td width="51" align="center"><strong id="l_quantidade2"></strong></td>
                                  </tr>
                                </tbody></table>
                                
                            </td>
                            <td valign="top" id="L_Botao_Box_1660">
                                <div id="L_BotaoA_1660">
                                 <a style="cursor:pointer" href='leilao_detalhes.php?id=<?php echo $x['id']?>' title=''><img border="0" style="float:left; cursor:pointer;" src="<?php echo $aberto ?>" ></a>
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

<br><br><br>
<?php
print"</td>";



//se o cont for igual o número de colunas ele fecha a linha da tabela
if($cont==$colunas){
print"</tr>";
$cont=0;
}
$cont=$cont+1; //acrescenta valor ao cont
}

//se o valor final de cont for diferente do numero de colunas ele fechará a tabela
if(!$cont==$colunas){
print"</tr></table>";
} else {
print"</table>";
}
echo $pagertop;
end_framec();
	


stdfoot();
?>