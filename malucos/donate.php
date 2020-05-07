<?php
    
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
  
  require_once("backend/functions.php");
  dbconn(true);
  loggedinonly();
  
  stdhead("DONATE");

  begin_framec(T_("DONATE"));
  
  ?>
<center>
Para manter-mos o site com boa estrutura não é fácil ,temos um grande gasto com a manutenção do nosso servidor que vica hospedado em um dos melhores datacenter da europa .
<br><br>
Ajudando com sua doação , você garante nossa permanência no ar<br><br>

Faça sua parte ajude-nos a manter nosso site no ar e sempre com boa qualidade e estrutura. <br><br>Veja as opções para ser um usuário VIP:<br><br></center><table cellspacing="0" width="100%" border="1" cellpadding="0" align="center" id="tabela1">

<tbody><tr>
<td align="center" width="100%" border="1" colspan="4" class="ttable_head"><b>Doação Mensal</b></td>
</tr><tr>

<td width="20%" align="center" class="tab1_cab1">MALUCO VIP</td>
<td width="20%" align="center" class="tab1_cab1">MALUCO VIP BRONZE</td>
<td width="20%" align="center" class="tab1_cab1">MALUCOS VIP PRATA</td>
<td width="20%" align="center" class="tab1_cab1">MALUCOS VIP OURO</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 5 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 25 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 30 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 50 Ms-bônus</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 5 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 10 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 25 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 30 GB de upload</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">-</td>
<td width="20%" align="center" class="tab1_col3">+ 1 convite</td>
<td width="20%" align="center" class="tab1_col3">+ 3 convites</td>
<td width="20%" align="center" class="tab1_col3">+ 5 convites</td>
</tr>



<tr>
<td width="20%" align="center" class="tab1_col3"><b>R$ 5,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 10,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 20,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 50,00</b></td>
</tr>

</tbody></table><br><br><table cellspacing="0"  width="100%" border="1"  cellpadding="0" align="center" id="tabela1">

<tbody><tr>
<td align="center" colspan="4" class="ttable_head"><b>Doação Trimestral</b></td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_cab1">MALUCO VIP</td>
<td width="20%" align="center" class="tab1_cab1">MALUCO VIP BRONZE</td>
<td width="20%" align="center" class="tab1_cab1">MALUCOS VIP PRATA</td>
<td width="20%" align="center" class="tab1_cab1">MALUCOS VIP OURO</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 30 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 45 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 60 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 90 Ms-bônus</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 15 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 20 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 30 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 45 GB de upload</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 2 convite</td>
<td width="20%" align="center" class="tab1_col3">+ 4 convites</td>
<td width="20%" align="center" class="tab1_col3">+ 8 convites</td>
<td width="20%" align="center" class="tab1_col3">+ 12 convites</td>
</tr>



<tr>
<td width="20%" align="center" class="tab1_col3"><b>R$ 15,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 25,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 30,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 70,00</b></td>
</tr>

</tbody></table><br><br>

<table cellspacing="0" cellpadding="0"  width="100%" border="1"  align="center" id="tabela1">

<tbody><tr>
<td align="center" colspan="4" class="ttable_head"><b>Doação Semestral</b></td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_cab1">MALUCO VIP</td>
<td width="20%" align="center" class="tab1_cab1">MALUCO VIP BRONZE</td>
<td width="20%" align="center" class="tab1_cab1">MALUCOS VIP PRATA</td>
<td width="20%" align="center" class="tab1_cab1">MALUCOS VIP OURO</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 50 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 80 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 120 Ms-bônus</td>
<td width="20%" align="center" class="tab1_col3">+ 200 Ms-bônus</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 25 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 35 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 50 GB de upload</td>
<td width="20%" align="center" class="tab1_col3">+ 100 GB de upload</td>
</tr>

<tr>
<td width="20%" align="center" class="tab1_col3">+ 5 convites</td>
<td width="20%" align="center" class="tab1_col3">+ 10 convites</td>
<td width="20%" align="center" class="tab1_col3">+ 15 convites</td>
<td width="20%" align="center" class="tab1_col3">+ 25 convites</td>
</tr>



<tr>
<td width="20%" align="center" class="tab1_col3"><b>R$ 35,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 55,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 75,00</b></td>
<td width="20%" align="center" class="tab1_col3"><b>R$ 100,00</b></td>
</tr>

</tbody></table><br><br>

<a name="metodos"></a>
<table cellspacing="1" cellpadding="0"  width="100%" align="center" id="tabela1">
<tbody><tr><td align="center" class="tab1_cab1">
Métodos de Doação
</td></tr><tr><td style="line-height: 2.2em;" class="tab1_col3">
<font size="2" color="#006699"><b>Banco - Depósito em Conta Corrente / Transferência</b></font><br><br>
		<font size="2">
		Banco: <b>Bradesco</b><br>
		Agência: <b>0485</b><br>
		Conta Corrente: <b>513-4</b><br>
		Favorecido: <b>Jeanete</b><br>
		</font><br><hr>


<font size="2" color="#006699"><b>Paypal</b></font><br><br>


<b>
» Cartões de crédito<br>
» Créditos Paypal
</b><br><br>

<small>Clique na imagem abaixo para fazer sua doação!</small><br>





  <form action="<?php echo $site_config['SITEURL']; ?>/paypal.php" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="business" value="donativo@malucos-share.org">
  <input type="hidden" name="item_name" value="Donation From (<?php echo ($CURUSER["username"]); ?>)">
  <input type="hidden" name="userid" value="<?php echo ($CURUSER["id"]); ?>">
  <input type="hidden" name="no_shipping" value="0">
  <input type="hidden" name="no_note" value="1">
  <input type="hidden" name="tax" value="0">
  <input type="hidden" name="lc" value="GB">
  <input type="hidden" name="bn" value="PP-DonationsBF">
<select name="amount" size="1">
<option value="5.00">R$ 5,00</option>
<option value="10.00">R$ 10,00</option>
<option value="15.00">R$ 15,00</option>
<option value="20.00">R$ 20,00</option>
<option value="25.00">R$ 25,00</option>
<option value="30.00">R$ 30,00</option>
<option value="35.00">R$ 35,00</option>
<option value="50.00">R$ 50,00</option>
<option value="55.00">R$ 55,00</option>
<option value="70.00">R$ 70,00</option>
<option value="75.00">R$ 75,00</option>
<option value="100.00">R$ 100,00</option>
</select><br>
<BR>


<font color="#ff9900"><input type="image" border="0" alt="PayPal - A maneira mais fácil e segura de efetuar pagamentos online!" name="submit" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif">
</form>

<hr>
<br>


<font size="2" color="#006699"><b>UOL Pagseguro</b></font><br><br>

<b>
» Débito em Conta Corrente<br>
» Boleto bancário
</b><br><br>
<small>Clique na imagem abaixo para usar o UOL Pagseguro</small><br>



<form method="post" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml" target="pagseguro">
<input type="hidden" value="donativo@malucos-share.org" name="email_cobranca">
<input type="hidden" value="CP" name="tipo">
<input type="hidden" value="BRL" name="moeda">
<input type="hidden" value="MS" name="item_id_1">
<input type="hidden" value="malucos-share.org" name="item_descr_1">
<input type="hidden" value="1" name="item_quant_1">
<select name="item_valor_1">
<option value="2000">Escolher</option>
<option value="5.00">R$ 5,00</option>
<option value="10.00">R$ 10,00</option>
<option value="15.00">R$ 15,00</option>
<option value="20.00">R$ 20,00</option>
<option value="25.00">R$ 25,00</option>
<option value="30.00">R$ 30,00</option>
<option value="35.00">R$ 35,00</option>
<option value="50.00">R$ 50,00</option>
<option value="55.00">R$ 55,00</option>
<option value="70.00">R$ 70,00</option>
<option value="75.00">R$ 75,00</option>
<option value="100.00">R$ 100,00</option>
</select><br>
<input type="hidden" value="000" name="item_frete_1">
<input type="image" alt="Pague com PagSeguro - é rápido, grátis e seguro!" name="submit" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/pagamentos/84x35-pagar-azul.gif">
</form>
</td></tr></tbody></table><br>


<table cellspacing="1" cellpadding="0"  width="100%" align="center" id="tabela1">
<tbody><tr><td align="center" class="tab1_cab1">
Confirmação de doação
</td></tr><tr><td align="center" class="tab1_col3">
<font size="3" color="#006699"><b>ATENÇÃO!!!</b></font>
<br>
A sua doação só será reconhecida após o envio do formulário de confirmação.<br><br>

<a href="doacao_confirma.php"><b><font size="2" color="#FF0000">Clique aqui para confirmar a doação!</font></b></a>
<br><br><br>
Aguarde a confirmação da doação. Isso deve levar em torno de 3 dias úteis. Caso o prazo termine, envie um email para donativo@malucos-share.org informando o atraso.
</td></tr></tbody></table><br>

				<div class="clr"></div>



	

 <?php 
  end_framec();
  stdfoot();
  
?>