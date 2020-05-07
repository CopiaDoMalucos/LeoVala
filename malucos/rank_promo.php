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

global $CURUSER;
$pdo = conectar();






$contuser = "SELECT count(*) FROM users WHERE status='confirmed'  ORDER BY uploader_rank DESC, added ASC LIMIT 20;  "; 
$contuser = $pdo->prepare($contuser); 
$contuser->execute(); 
$count = $contuser->fetchColumn() ;

$res2 = $pdo->prepare("SELECT id,username, uploader_rank FROM users WHERE status='confirmed' ORDER BY uploader_rank DESC, added ASC LIMIT 20;"); 
$res2->execute(); 	
	
		  $count = 0;
 	 $rank = $count;
	 
$contuserp = "SELECT count(*) FROM users WHERE status='confirmed' AND class != '100' AND class != '95' AND class != '85' AND class != '80' AND class != '75' ORDER BY uploader_rank DESC, added ASC LIMIT 20;  "; 
$contuserp = $pdo->prepare($contuserp); 
$contuserp->execute(); 
$countp = $contuserp->fetchColumn() ;

$res2p = $pdo->prepare("SELECT id,username, uploader_rank FROM users WHERE status='confirmed' AND class != '100' AND class != '95' AND class != '85' AND class != '80' AND class != '75' ORDER BY uploader_rank DESC, added ASC LIMIT 20;"); 
$res2p->execute(); 	
	
		  $countp = 0;
 	 $rankp = $countp;	 
	 
	 $res3 = $pdo->prepare("SELECT id,username, uploader_rank FROM users WHERE status='confirmed' ORDER BY uploader_rank ASC"); 
$res3->execute(); 	

$date_time1='2013-06-17 03:00:00'; 

	 $res4 = $pdo->prepare("SELECT id, name, added, owner FROM torrents WHERE added >='$date_time1'"); 
$res4->execute(); 	



$contuser4 = "SELECT count(*) FROM torrents WHERE added >='$date_time1' "; 
$contuser4 = $pdo->prepare($contuser4); 
$contuser4->execute(); 
$count4 = 0;
$count4 = $contuser4->fetchColumn() ;

stdhead("Pedido apagar");



	
?>	


<center>
[
<a href="rank_promo.php?acao=1">Top 20 geral</a>
|
<a href="rank_promo.php?acao=2">Top 20 [Exceto Staff]</a>
|
<a href="rank_promo.php?acao=3">Top 10 gráfico</a>
|
<a href="rank_promo.php?acao=4">Estatística lançamentos</a>
]

</center>


<?php


$pdo = conectar();

  $option = $_GET["id"];
if ($_GET['acao'] == "") {   
$stmt = $pdo->prepare("SELECT id, userid, avatar, sing, added, termina, tema, estilo FROM leilao  WHERE  id= ?"); 
$stmt->bindParam(1,$option);
$stmt->execute(); 
$x2 = $stmt->fetch(PDO::FETCH_ASSOC); 
?>
<br>
<br>
<center>
<script src="countdown.js" type="text/javascript"></script>
<script type="application/javascript">

var myCountdownTest = new Countdown({

	
								 year: <?php echo date("Y", utc_to_tz_time($x2['termina'])); ?>,
									month	: <?php echo date("m", utc_to_tz_time($x2['termina'])); ?>, 
									day		: <?php echo date("d", utc_to_tz_time($x2['termina'])); ?>,
									hour	: <?php echo date("h", utc_to_tz_time($x2['termina'])); ?>,
                                    minute	: <?php echo date("i", utc_to_tz_time($x2['termina'])); ?>, 
                                    second	: <?php echo date("s", utc_to_tz_time($x2['termina'])); ?>,	 // < - no comma on last item!!
									width	: 300, 
									height	: 40

									});


   
</script>
</center>

<?
 echo"<br><br><TABLE class='tab1'  cellpadding='0' cellspacing='1'>";
  echo"<tr><td    class=tab1_col3 width=1>";
echo"<br></br>
<center><font color=green><font size=4>Promoção Maluca Malucos-Share</font></font></center>
<br>
<br>
<br>
<center><b><font size=3>
Sempre fazendo o melhor por você e para você, Malucos-share traz mais uma super promoção, isso mesmo um promoção nova e bem maluca coisa assim você só vê aqui.
<br>
<br>
Durante sete dias todos os lançamentos apareceram em um ranking e estarão participando da brincadeira todos que lançarem o ranking está na página principal e repito todos poderão participar e acompanhar o andamento do ranking, e na página de informações da promoção vocês terão todas as informação detalhadas dos lançamentos e ainda acompanhar em tempo real, mostrando o desempenho de cada um.
Toda a contabilização será automática, portanto somente após a liberação dos torrents os pontos serão computados no ranking.
E mais durante a promoção os seis usuários melhores colocados no ranking terão em seus lançamentos na index destacados com uma coroa, serão destacados os com seis tipos de coroas os seis melhores colocados no ranking.
</center></b></font>
<br>
<br>
<center><b><font color=red><font size=4>Mecânica dos pontos:</font></font></b></center>
<br>
<br>
<center><b><font size=3>
Torrents igual ou acima 1GB lançados e 8 completos =4 pontos
<br>
Torrents igual ou acima 2GB lançados e 6 completos = 6 pontos
<br>
Torrents igual ou acima 4GB lançados e 4 completos = 8 pontos
</font></b></center>
<br>
<br>
<center><b><font size=3>mais chega de falar e vamos ao que interessa os prêmios EBAAAAA.</center></b></font>
<br>
<br>
<center><b><font color=red><font size=4>Prêmios:</font></font></b></center>
<br>
<br>
<center><b><font size=3>
01 Pen Drive 8GB Sandisk Cruzer Blade, para o usuário com maior número pontos;
<br>
01 Pen Drive 8GB Sandisk Cruzer Blade, que será sorteado entre os participantes do TOP 5;
<br>
100 GB de upload e 20 convites distribuídos da seguinte forma:
</font></b></center>
<br>
<br>
<center><b><font color=red><font size=4>Distribuição dos prêmios:</font></font></b></center>
<br>
<br>
<center><b><font size=4>
1º Colocado com maior número de pontos (não sendo da staff) leva 01 Pen Drive 8GB Sandisk Cruzer Blade;
<br>
2º  01 Pen Drive 8 GB Scandisk Cruzer Blade sorteado entre os 5 melhores do ranking excluindo o primeiro colocado;
<br>
2º Colocado 50 GB de upload e mais 10 convites
<br>
3º Colocado 25 GB de upload e mais 5 convites
<br>
4º Colocado 20 GB de upload e mais 3 convites
<br>
5º Colocado 15 GB de upload e mais 2 convites
</font></b></center>
<br>
<br>
<center><b><font color=red><font size=4>Regras:</font></font></b></center>
<br>
<br>
<center><b><font size=3>
- Geral concorrerão às premiações todos os usuários exceto STAFF (Liberadores/Moderadores/Admins);<br>
O primeiro lugar do ranking leva 01 Pen Drive, esse será enviado sem nenhum custo ao ganhador em qualquer lugar do mundo;<br>
Os participantes que estiverem no top 05 do ranking, excluindo-se o primeiro colocado concorrerão a um Pen Drive, ao qual será usado para sorteio o site www.sorteador.com.br registrando com imagens o número sorteado;<br>
- Os membros do Staff não poderão concorrer à premiação (exceto uploaders) não estando, no entanto, nenhum deles inseto de participar da promoção;<br>
- Os envios dos Pen Drives serão através dos Correios e enviado para qualquer mundo e o ganhador devera informar o recebimento e caberá ao ganhador informar os dados para envio através de MP (Mensagem Privada).<br>
- A entrega dos GB de uploads e dos convites serão realizadas em até 7 dias após o final da promoção;<br> 
- Em nenhuma hipótese o ganhador poderá solicitar o dinheiro ao invés do prêmio;<br>
- Toda informação referente à promoção deverá ser colocada em fórum aberto para total transparência da mesma;<br>
- Não serão considerados os lançamentos de e-books, epub, revistas e demais torrents do gênero ficando justo a todos, salvo casos em que sejam acima de 1 Gb; <br>
- Havendo empate na soma dos pontos será usado como critério de desempate o usuário com maior ratio;<br>
- As regras podem ser alteradas sem prévio aviso;<br>
- O ganhador só poderá ganhar apenas uma vez, caso a mesma pessoa ganha 2x será passado ao próximo;<br>
- Usuários advertidos serão desclassificados da promoção durante a vigência da ADV<br>
- Período de vigência de 16-06-2013 até 30-06-2013, ou seja, uma semana.<br>
</font></b></center>
<br></br>";
   echo"</td></tr>";
 echo"</TABLE>";

}
if ($_GET['acao'] == "1") {    
echo"<TABLE class='tab1'  cellpadding='0' cellspacing='1' align='center'><td class='tab1_cab1'  width='50%'  colspan='5' align='center'> Rank </td>";
print("<tr><td class=ttable_headp  width=1 align=left>Posição</td><td class=ttable_headp align=center>Usuário</td><td 
class=ttable_headp align=center >Pontos</td><td 
class=ttable_headp align=center >Status</td></tr>");


while ($row = $res2->fetch(PDO::FETCH_ASSOC)) 

{

++$num;


 
 $upload = $upload +  $row['uploader_rank'];

 $perc1 = $row['uploader_rank'];
 
 if ( $perc1 == 0) 
{
$perc = 0 ;
}else{
$perc= $perc1*100/$upload;
}


if ($perc<= 1) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 20) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 30) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 40) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 50) { 
$pic = "images/loadbarred.gif"; $width = $perc; }
elseif ($perc<= 60) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
elseif ($perc<= 70) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
elseif ($perc<= 80) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
elseif ($perc<= 90) {
$pic = "images/loadbaryellow.gif"; $width = $perc;  } 
else { 
$pic = "images/loadbargreen.gif "; $width = "100"; }

 echo"<tr><td  align=center  class=tab1_col3 width=1><b>".$num."</b></td><td width=1  align=center class=tab1_col3  ><a href='account-details.php?id=".$row['id']."'>".$row['username']." </a></td><td   align=center class=tab1_col3 width=1  >".$row['uploader_rank']."</td><td class=tab1_col3  src=images/loadbarbg.gif  ><img height=15 width=$width% src=\"$pic\" alt='($donatein)%'><br><font size='1'><center>".number_format($perc)."%</center></font></td></tr>";
}
 echo"</TABLE>";	
} 
?>






<?php
if ($_GET['acao'] == "3") {    
	
 echo"<br><br><TABLE class='tab1'  cellpadding='0' cellspacing='1' align='center'>";
 
 ?>

<html>
  <head>
   <script type="text/javascript" src="<?php echo  $site_config["SITEURL"]; ?>/scripts/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
     <?php while ($row3 = $res3->fetch(PDO::FETCH_ASSOC)) 

{ ?>
		   ['<?php echo $row3["username"]; ?>',  <?php echo $row3["uploader_rank"]; ?>],
     <?php 

	 
}?>
        ]);

        var options = {
          title: 'Atividades '
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
  </body>
</html>

<?php
  echo"</TABLE>";
 
} 
?>




<?php
if ($_GET['acao'] == "2") {    
echo"<TABLE class='tab1'  cellpadding='0' cellspacing='1' align='center'><td class='tab1_cab1'  width='50%'  colspan='5' align='center'>  Top 20 [Exceto Staff] </td>";
print("<tr><td class=ttable_head width=1 align=left>Posição</td><td class=ttable_head align=center>Usuário</td><td 
class=ttable_head align=center >Pontos</td><td 
class=ttable_head align=center >Status</td></tr>");

while ($rowp = $res2p->fetch(PDO::FETCH_ASSOC)) 
{
++$nump;


 
 $uploadp = $uploadp +  $rowp['uploader_rank'];

 $perc1p = $rowp['uploader_rank'];
 




 if ( $perc1p == 0) 
{
$percp = 0 ;
}else{
$percp= $perc1p*100/$uploadp;
}
 
if ($percp<= 1) { 
$picp = "images/loadbarred.gif"; $widthp = $percp; }
elseif ($percp<= 20) { 
$picp = "images/loadbarred.gif"; $widthp = $percp; }
elseif ($percp<= 30) { 
$picp = "images/loadbarred.gif"; $widthp = $percp; }
elseif ($percp<= 40) { 
$picp = "images/loadbarred.gif"; $widthp = $percp; }
elseif ($percp<= 50) { 
$picp = "images/loadbarred.gif"; $widthp = $percp; }
elseif ($percp<= 60) {
$picp = "images/loadbaryellow.gif"; $widthp = $percp;  } 
elseif ($percp<= 70) {
$picp = "images/loadbaryellow.gif"; $widthp = $percp;  } 
elseif ($percp<= 80) {
$pic = "images/loadbaryellow.gif"; $widthp = $percp;  } 
elseif ($percp<= 90) {
$picp = "images/loadbaryellow.gif"; $widthp = $percp;  } 
else { 
$picp = "images/loadbargreen.gif "; $widthp = "100"; }
 echo"<tr><td  align=center  class=tab1_col3 width=1><b>".$nump."</b></td><td width=1  align=center class=tab1_col3  ><a href='account-details.php?id=".$rowp['id']."'>".$rowp['username']." </a></td><td   align=center class=tab1_col3 width=1  >".$rowp['uploader_rank']."</td><td class=tab1_col3  src=images/loadbarbg.gif  ><img height=15 width=$widthp% src=\"$picp\" alt='($donateinp)%'><br><font size='1'><center>".number_format($percp)."%</center></font></td></tr>";
}
 echo"</TABLE>";
 }
?>















<?php
if ($_GET['acao'] == "4") {    
	
 echo"<br><br><TABLE class='tab1'  cellpadding='0' cellspacing='1' align='center'>";
 
 ?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />

  <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">
    google.load('visualization', '1', {packages: ['motionchart']});

    function drawVisualization() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Resultado');
      data.addColumn('date', 'Data');
      data.addColumn('number', 'Up');
      data.addColumn('number', 'Lançados');
      data.addColumn('string', 'Posição');
      data.addRows([
	  

	       <?php while ($row4 = $res4->fetch(PDO::FETCH_ASSOC)) 

{
	 $res5 = $pdo->prepare("SELECT id, username FROM users WHERE id =".$row4['owner'].""); 
$res5->execute(); 	
$row5 = $res5->fetch(PDO::FETCH_ASSOC);
$numtorrents = get_row_count("torrents", "WHERE owner = ".$row4['owner']."");
 ?>
        ['<?php echo $row5 ["username"]; ?>', new Date(<?php echo date("y", utc_to_tz_time($row4['added'])); ?>,<?php echo date("m", utc_to_tz_time($row4['added'])); ?>,<?php echo date("d", utc_to_tz_time($row4['added'])); ?>), <?php echo $count4; ?>, <?php echo $numtorrents; ?>, 'East'],
	       <?php 
}		   ?>
      ]);
    
      var motionchart = new google.visualization.MotionChart(
          document.getElementById('visualization'));
      motionchart.draw(data, {'width': '100%', 'height': 400});
    }
    

    google.setOnLoadCallback(drawVisualization);
  </script>
</head>
<body style="font-family: Arial;border: 0 none;">
<div id="visualization" style="width: 100%; height: 400px;"></div>
</body>
</html>

<?php
  echo"</TABLE>";
 
} 
?>
<?php
stdfoot();


?>