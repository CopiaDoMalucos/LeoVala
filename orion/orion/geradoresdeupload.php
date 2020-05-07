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
stdhead("Algumas Recomendações");
begin_framec("Algumas Recomendações");
echo"<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";

///print("<B>Observações Importantes:</B><br>");
print("1. Os torrents devem estar de acordo com as <a href=rules.php target=_BLANK><b>regras de lançamentos.</b></a> <BR>
2. Torrents repetidos, serão deletados sem aviso prévio.<BR>
3. Os torrents lançados deverão ser semeados por um período mínimo de 7 dias.<BR>
4. O método de envio de torrents é totalmente sistematizado. Qualquer dúvida, entre em contato com nossa equipe nos fóruns ou no ShoutBox.<BR><br></br><br></br>");
echo"</table>";

echo'<div align="justify" class="framecentro">';
echo'<table width="100%"  class="tab1" cellpadding="0" cellspacing="1" align="center">';
echo'<td align="center" colspan="5" class="tab1_cab1">Selecione uma categoria</td>';
echo'<tr><td class="ttable_head" align="center" >Anime / Filmes / Séries / Shows / Televisão </td><td class="ttable_head" align="center">Música</td><td class="ttable_head" align="center">Jogos / Aplicativos / Textos / Outros</td></tr>';
echo'<tr>';

echo'<td style="line-height: 160%; width: 33%; vertical-align: top;" class="tab1_col3">';
echo'<b>Vídeos</b>';
echo'<br><br>';
$cats = genrelist17();
foreach ($cats as $row)
echo"<a href='torrents-anime.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist1();
foreach ($cats as $row)
echo"<a href='torrents-filmes.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist8();
foreach ($cats as $row)
echo"<a href='torrents-series.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist7();
foreach ($cats as $row)
echo"<a href='torrents-shows.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist6();
foreach ($cats as $row)
echo"<a href='torrents-televisao.php?cat=" . $row["id"] . "'>Vídeos da Televisão</a><br>";
echo'</td>';

echo'<td style="line-height: 160%; width: 33%; vertical-align: top;" class="tab1_col3">';
echo'<b>Música</b>';
echo'<br><br>';
$cats = genrelist3();
foreach ($cats as $row)
echo"<a href='torrents-musicas.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
echo'</td>';

echo'<td style="line-height: 160%; width: 33%; vertical-align: top;" class="tab1_col3">';
echo'<b>Jogos</b>';
echo'<br><br>';
$cats = genrelist4();
foreach ($cats as $row)
echo"<a href='torrents-jogos.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
echo'<br>';
echo'<b>Aplicativos</b>';
echo'<br><br>';
$cats = genrelist2();
foreach ($cats as $row)
echo"<a href='torrents-aplicativos.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
echo'<br>';
echo'<b>Textos</b>';
echo'<br><br>';
$cats = genrelist11();
foreach ($cats as $row)
echo"<a href='torrents-apostila-cursos.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist9();
foreach ($cats as $row)
echo"<a href='torrents-livros-revist.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
echo'<br>';
echo'<b>Outros</b>';
echo'<br><br>';
$cats = genrelist12();
foreach ($cats as $row)
echo"<a href='torrents-video-aula.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist5();
foreach ($cats as $row)
echo"<a href='torrents-videoclipes.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist15();
foreach ($cats as $row)
echo"<a href='torrents-fotos-xxx.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist10();
foreach ($cats as $row)
echo"<a href='torrents-fotos.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";
$cats = genrelist16();
foreach ($cats as $row)
echo"<a href='torrents-upload.php?cat=" . $row["id"] . "'>".htmlspecialchars($row["name"])."</a><br>";

echo'</td>';


echo'</tr>';
echo'</table>';
echo'</div>';

end_framec();
stdfoot();
?>	
			

