<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();
loggedinonly();

$id = 0 + $_GET["id"];

if ($id == 0){
$id = $CURUSER["id"];
}
$validar =  $CURUSER["id"];
if ( $validar != $id )
 show_error_msg("Ops", "Acceso restrito");

stdhead("Convites");
	  $temconvites = ($CURUSER["invites"]);


$action = $_REQUEST["action"];
$do = $_REQUEST["do"];

if (!$action){
	begin_framec("Convites Disponíveis ".$temconvites."");

	



$type = unesc($_GET["type"]);
$invite = $_GET["invite"];
$SITENAME = $site_config['SITENAME'];
stdhead("Invite");


 
$res = mysql_query("SELECT invites FROM users WHERE id = $id");
$inv = mysql_fetch_assoc($res);

if ($inv["invites"] != 1){
$_s = "s";
} else {
$_s = "";
}


if ($type == 'del'){
$ret = mysql_query("SELECT * FROM invites WHERE invite = '$invite'") ;
$num = mysql_fetch_assoc($ret);
if ($num[inviter]==$id){
mysql_query("DELETE FROM invites WHERE invite = '$invite'");


mysql_query("UPDATE users SET invites = ($CURUSER[invites]+1) WHERE id = $CURUSER[id]");

show_error_msg("Sucesso", "Código do convite apagado com sucesso.  <br><a href=convites.php><font color=#FF0000><CENTER><b>[Voltar]</b></CENTER></font></a>", 1);

} else
print("Você não tem permissão para excluir este convite, ou este convite não existe");
}
else {


$rel = mysql_query("SELECT COUNT(*) FROM users WHERE invited_by = $id AND status = 'pending'");
$arro = mysql_fetch_row($rel);
$number = $arro[0];


$ret = mysql_query("SELECT id, username, email, uploaded, downloaded, status, warned, enabled, donated, email, added FROM users WHERE invited_by = $id AND status = 'pending' ORDER BY added DESC") ;
$num = mysql_num_rows($ret); 

echo "<center><table width=100% border=0 align=center><tr><td></td><br>";


if ($_GET['instructions'])
{         
        echo "<p align=center>[&nbsp;<a class=altlink href='".$_SERVER["PHP_SELF"]."'>Fechar</a>&nbsp;]</p></tr>\n";
        echo "<tr><td class=clearalt6><div align=left>\n
        <b><center>Instruções</center></b><br><br>\n
          
		  
		<td align='left'>
                  <center><h2><span style='color:#FF0000'><font face=verdana>Instruções</spam></font></h2></center><br><br>
                  <p></p><center><h3><b><font face=verdana>Sistema de Convites do BRShares:</b></h3></center><p></p>

                  Abaixo está a lista e estado atual dos seus convidados (se houver). 
                  Para convidar um novo membro para o BRShares, clique primeiro no botão <input type='submit' value='Gerar Código do Convite' class='shoutbox_shoutbtn'> 
                    após isso uma nova página é carregada.<br><br>

                    <p></p><center><h3><b>Gerar código do convite (X)</b></h3></center><p></p>
                    (X significa a quantidade de convites que você tem). Abaixo você verá o botão: <input type='submit' value='Gerar Código' class='shoutbox_shoutbtn'>
                    clique nele e um novo código de convite será gerado.<br>

                    <br><br>

                    <p></p><center><h3><b>IMPORTANTE!</b></h3></center><p></p>

                    Copie o código exatamente como aparece e envie para a pessoa que você deseja convidar para o BRShares, juntamente com o endereço principal do site: 
                    http://www.brshares.com/ para ele se registrar. Após o cadastro o seu convidado deve ser confirmado por você. Ele não será capaz de acessar o BRShares
                    até que você o confirme como seu convidado! Para confirmar seu convidado(s), basta marcar a caixa em confirmar (ou várias caixas se você convidou mais 
                    de uma pessoa), e clique no botão <input type='submit' value='Confirmar Convidado(s)' class='shoutbox_shoutbtn'> só depois disso seu convidado será capaz de logar.<br>
					Caso o seu convidado não se registre em 7 (sete) dias o código de convite será deletado automaticamente pelo sistema.
                      <br><br>

                      <p></p><center><h3><b>SEJA RESPONSÁVEL!</b></h3></center><p></p>
                      Convide pessoas que você conheça e confie, só assim teremos um site cada vez melhor.
                      <br><br>
                      <p></p><center><h3><b>DÚVIDAS OU PROBLEMAS!</b></h3></center><p></p>
                      Qualquer dúvida ou dificuldade procure a Administração.
                      Equipe BRShares, sempre fazendo o melhor por você!!!</font>
                      </td>
		  
		  
        </div></td></tr></table><br><br>\n";
}
else
{
echo "<p align=center>[&nbsp;<a class=altlink href='".$_SERVER["PHP_SELF"]."?instructions=1'>Instruções</a>&nbsp;]</p></td></tr>\n";
}       

$rul = mysql_query("SELECT COUNT(*) FROM invites WHERE inviter = $id AND simounao='no'");
$arre = mysql_fetch_row($rul);
$number1 = $arre[0];

$rer = mysql_query("SELECT inviteid, invite, time_invited, email  FROM invites WHERE inviter = $id AND confirmed='no' AND simounao='no'");
$num1 = mysql_num_rows($rer);
begin_framec("Estado atual de códigos criados <b>".($number1)."</b>");
////

print("<tr class=tableb><td colspan=6><center><a href=\"convites.php?action=gerar\">Gerar Código </a></center></tr>");
print("<table border=1 width=100% cellspacing=0 cellpadding=5><tr class=tabletitle></tr>");
print("<tr class=ttable_head><td class=ttable_head><b>Código</b></td><td class=ttable_head><b>E-mail</b></td><td class=ttable_head><b>Data de criação</b></td><td class=ttable_head><b>Deletar código</b></td>");
if(!$num1){
print("<tr class=tableb><td colspan=6>Não há códigos de convite criados no momento.</tr>");
print("<tr class=tableb><td colspan=6><center><a href=\"convites.php?action=gerar\">Gerar Código </a></center></tr>");

} else {
 

for ($i = 0; $i < $num1; ++$i)
{
$arr1 = mysql_fetch_assoc($rer);

print("<tr class=tableb><td>$arr1[invite]</td><td>$arr1[email]</td><td>". date("d-m-Y H:i:s", utc_to_tz_time($arr1["time_invited"])) . "</td>");
// print("<td><input type=\"checkbox\" name=\"conusr[]\" value=\"" . $arr[id] . "\" /></td></tr>");
print ("<td ><a href=\"convites.php?invite=$arr1[invite]&type=del\">Deletar código</a></td></tr>");

}
print("<tr class=tableb><td colspan=6><center><a href=\"convites.php?action=gerar\">Gerar Código </a></center></tr>");
}



print("</table><BR>");
end_framec();
begin_framec("Estado atual dos convidados ".($number)."");
print("<form method=post action=takeconfirm.php?id=$id><table cellspacing=0 cellpadding=5 border=1 align=center width=100%>".
"<tr class=tabletitle></tr>");

if(!$num){
print("<tr class=tableb><td colspan=7>Não há ainda convidados pendentes.</tr>");
} else {


print("<tr class=ttable_head><td class=ttable_head><b>Usuário</b></td><td class=ttable_head><b>E-mail</b></td><td class=ttable_head><b>Semeado</b></td><td class=ttable_head><b>Baixado</b></td><td class=ttable_head><b>Ratio</b></td><td class=ttable_head><b>Status</b></td>");
if ($CURUSER[id] == $id )
print("<td align=center class=ttable_head><b>Confirm</b></td>");

print("</tr>");
for ($i = 0; $i < $num; ++$i)
{        
  //=======change colors
                if($count2 == 0)
{
$count2 = $count2+1;
$class = "clearalt7";
}
else
{
$count2 = 0;
$class = "clearalt6";
}
                //=======end
$arr = mysql_fetch_assoc($ret);
if ($arr[status] == 'pending')
$user = "<td align=left class=$class><a class=altlink href=account-details.php?id=$arr[id]>$arr[username]</a></td>";
else
$user = "<td align=left class=$class><a class=altlink href=account-details.php?id=$arr[id]>$arr[username]</a>" .($arr["warned"] == "yes" ? "&nbsp;<img src=pic/warned.gif border=0 alt='Warned'>" : "")."&nbsp;" .($arr["enabled"] == "no" ? "&nbsp;<img src=pic/disabled.gif border=0 alt='Disabled'>" : "")."&nbsp;" .($arr["donated"] == "yes" ? "<img src=pic/star.gif border=0 alt='Donated'>" : "")."</td>";

if ($arr["downloaded"] > 0) {
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
} else {
if ($arr["uploaded"] > 0) {
$ratio = "Inf.";
}
else {
$ratio = "---";
}
}
if ($arr["status"] == 'confirmed')
$status = "<a class=altlink href=account-details.php?id=$arr[id]><font color=#1f7309>Confirmado</font></a>";
else
$status = "<a class=altlink href=account-details.php?id=$arr[id]><font color=#ca0226>Pendente</font></a>";

print("<tr  class=$class>$user<td class=$class>$arr[email]</td><td class=$class>" . mksize($arr[uploaded]) . "</td><td class=$class>" . mksize($arr[downloaded]) . "</td><td class=$class>$ratio</td><td class=$class>$status</td>");
if ($CURUSER[id] == $id ){
print("<td align=center class=$class>");
if ($arr[status] == 'pending')
print("<input type=\"checkbox\" name=\"conusr[]\" value=\"" . $arr[id] . "\" />");
print("</td>");
}

print("</tr>");
}
}
if ($CURUSER[id] == $id ){
print("<input type=hidden name=email value=$arr[email]>");
print("<tr class=tableb><td colspan=7 align=right><input class=button type=submit value='Confirma Usuários' style='height: 20px'></form></td></tr>");
}
print("</table><br>");
////
end_framec();

}

stdfoot();

die;





}



if ($action=="gerar"){

if ($do=="convites"){

$id = 0 + $_GET["id"];
$email = $_POST["email"];
if ($id == 0){
$id = $CURUSER["id"];
}

$id = $CURUSER["id"];

if (!validemail($email))
		show_error_msg("Isso não se parece com um email válido.");
		
		//check email isnt banned
			$maildomain = (substr($email, strpos($email, "@") + 1));
			$a = (@mysql_fetch_row(@SQL_Query_exec("select count(*) from email_bans where mail_domain='$email'")));
			if ($a[0] != 0)
               show_error_msg("".T_("ERRO")."", "Esse email já foi usado para cadastro, e se encontra com restrição, por favor escolha outro", 1);

			$a = (@mysql_fetch_row(@SQL_Query_exec("select count(*) from email_bans where mail_domain='$maildomain'")));
			if ($a[0] != 0)
               show_error_msg("".T_("ERRO")."", "Esse email já foi usado para cadastro e se encontra com restrição , por favor escolha outro", 1);
			 
		  // check if email addy is already in use
		  $a = (@mysql_fetch_row(@SQL_Query_exec("select count(*) from users where email='$email'")));
		  if ($a[0] != 0)
			 show_error_msg("".T_("ERRO")."", "O email ".$email." já foi usado , por favor escolha outro", 1);

$re = mysql_query("SELECT invites FROM users WHERE id = $id");
$tes = mysql_fetch_assoc($re);

if ($tes[invites] <= 0)
 show_error_msg("".T_("ERRO")."", "Você não possui convites para geração de código", 1);

 		$a = (@mysql_fetch_row(@SQL_Query_exec("select count(*) from invites where email='$email'")));
			if ($a[0] != 0)
                show_error_msg("".T_("ERRO")."", "Já existe um código gerado para o email ".$email."", 1);
			



$ret = mysql_query("SELECT username FROM users WHERE id = $id");
$arr = mysql_fetch_assoc($ret);


$hash = md5(mt_rand(1,1000000));

mysql_query("INSERT INTO invites (inviter, invite, time_invited, email) VALUES ('$id', '$hash', '" . get_date_time() . "', '$email')");
mysql_query("UPDATE users SET invites = invites - 1 WHERE id = $id") or sqlerr(__FILE__, __LINE__);



$body = <<<EOD
O {$CURUSER["username"]} ,membro da comunidade BRShares esta convidando você, para participar do site. 

use o código do convite abaixo para se registrar no site, 

{$hash}

Endereço do email 

{$email} 

* Importante, o seu cadastro só será posssível usando o código deste email. 

Para cadastrar você deve clique link abaixo e preencha todos os campos, após preencher será enviado um email para confirmação da conta.


$site_config[SITEURL]/account-signup.php?convite=$hash

BRShares.com

EOD;
	    $body1 = utf8_decode($body);
				sendmail($email, "Convite BR", $body1, "para: $site_config[SITEEMAIL]", "-f$site_config[SITEEMAIL]");
				$mailsent = 1;


 show_error_msg("Sucesso", "Parabéns, o convite foi gerado com sucesso, para o cadastro é necessário que passe para seu amigo o código gerado abaixo.
 Foi enviado também uma cópia do código para o email informado ".$email.".
 Em alguns minutos seu convidado deverá receber o email com as instruções e o código do convite, caso não chegue passe o código abaixo.<br>
  <br>
  ".$hash." 
  <br></br>
  O mesmo é necessário para realizar o cadastro no site. <br><a href=convites.php><font color=#FF0000><CENTER><b>[Voltar]</b></CENTER></font></a>", 1);

		end_framec();
		stdfoot();
		die();
	}//do

	begin_framec("Gerar Código");
if (!$site_config['INVITEONLY']) {
begin_framec("Erro!");
echo "<BR><BR><B><Center><font color=red>A Geração de convites está desabilitada, por favor diga aos seus amigos para usar o link de registo <font size=2>(www.brshares.com/account-signup.php) </font>, para se registrar e assim ajudar no fortalecimento da nossa comunidade maluca :) .</B></CENTER></font><BR><BR>";
end_framec();
stdfoot();
exit;
}
	?>

		<form method="post" action="convites.php?action=gerar">
	<input type="hidden" name="do" value="convites" />
    <div >
	
	    <table border="0" cellspacing="0" cellpadding="0"  align="center" width="100%"  cellpadding="10">

	<tr>
	<td colspan="2" class="ttable_head">
	<b>
	<h4>Gerar código do convite (Você tem <?php echo $temconvites ?> convite)</h4>
	</b>
	</td>
	</tr>
	<tr>
	<td align="left" class="ttable_col1" colspan="2">
	<font color="red">
	<i>* Campo Obrigatário, somente este email poderá se cadastrar com o convite gerado.</i>
	</font>
	</td>
	</tr>
	<tr>
	<td class="ttable_col1">
	<b>Digite o email da pessoa que você quer convidar</b>
	</td>
	<td class="ttable_col1">
	<input size="35" type="email" name="email"  value="">
	</td></tr><tr class="ttable_col1">
	<td align="center" colspan="2">
        <input type="reset" value="<?php echo T_("REVERT"); ?>" />
        <input type="submit" value="Gerar código" />
	</td>
	</tr> 

	</table>
	  <br>
    </div>
	</form>
	
	
	
	
	
	<?php
	
	end_framec();
}


stdfoot();
?>