<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functionsnew.php");
dbconn(true);
header('Content-Type: text/html; charset=utf-8');



loggedinonly();  
stdhead("Aviso");




	begin_framec("Aviso");
	echo "O BRShares não hospeda nenhum arquivo com direitos autorais de terceiros. O BRShares não se responsabiliza pelo conteúdo postado por seus usuários, ou qualquer outra ação feita por eles. Somos contra a pirataria. É EXPRESSAMENTE PROIBIDO obter qualquer tipo de vantagem financeira - direta ou indiretamente - envolvendo o BRShares. Não fazemos e não permitimos a venda de convites ou qualquer tipo de conteúdo indexado ao site. Você NÃO deve utilizar este site para vender ou distribuir qualquer material se você não tem os direitos legais para fazê-lo. Não é permitido o cadastro de usuários com menos de 18 anos. É sua responsabilidade aceitar estes termos. <br>
	Veja as <a href='rules.php'>•Regras do site</a>";
	echo "<center><a rel='license' href='http://creativecommons.org/licenses/by-nc-nd/2.5/br/'>
<img alt='Creative Commons License' style='border-width:0' src='http://creativecommons.org/images/public/somerights20.png'>
</a>
<br>Esta
<span xmlns:dc='http://purl.org/dc/elements/1.1/' href='http://purl.org/dc/dcmitype/InteractiveResource' rel='dc:type'>obra</span> está licenciada sob uma
<a rel='license' href='http://creativecommons.org/licenses/by-nc-nd/2.5/br/'>Licença Creative Commons</a>.</center>";
	
	end_framec();



stdfoot();
?>
