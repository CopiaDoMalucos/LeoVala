<?php

############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
  
  require_once("backend/functions.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");

$pdo = conectar();

 	stdhead("Leilão de kit");
?>
<script type="text/javascript"  src="jquery.validate.min.js"></script>
<script type="text/javascript" type="text/javascript">
$(document).ready(function(){
 
 $('#addleilao').validate(
 {
  rules: {

	    estilo: {
      required: true,
    },
		tempo: {
      required: true,
    },
		tema: {
	  minlength: 10,
      required: true,
    },	
		linkavatar: {
	  minlength: 10,
      required: true,
    },	
			linksing: {
	  minlength: 10,
      required: true,
    },	
  },
  highlight: function(element) {
    $(element).closest('.control-group').removeClass('success').addClass('error');
  },
  success: function(element) {
    element
    .text('OK!').addClass('valid')
    .closest('.control-group').removeClass('error').addClass('success');
  }
 });
}); // end document.ready
</script>
<?php  
begin_framec("Leilão de kit");
print("<center>[  <a href='leilao_online.php'> Kits geral </a> | <a href='leilao_online.php?acao=1'> Kits sendo leiloados </a> | <a href='leilao_online.php?acao=2'> Kits finalizados </a> ]</center>");
print("<form name=addleilao id=addleilao method=post action=leilaoconfirmar.php><a name=addleilao id=addleilao></a>\n");
print("<CENTER><table class='tab1' cellpadding='0' cellspacing='1' align='center' >");
print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><B>Cadastro de kit</B></td></tr>");
print("<tr><td align=center colspan=2 class=tab1_col3><b>Regras</b><br><br>");
print("1 - Todos os kits leiloados, é obrigatóriamente seguir as regras de avatar e assinatura. Dúvidas vide regras de avatar e assinaturas nº3. Clique Aqui.
<br>
<br>
1.2 - O custo do lance é de 100 MS ptos. cada usuário só poderá dar 1 lance por kit leiloado.
<br>
<br>
1.3 - De total de MS ptos arrecadados 60% vai pro Designer e o restante fica pro site.
<br>
<br>
1.4 - O tempo de cada leilão é fornecido automáticamente após a postagem.");
print("<br>");
?>

<tr><td width="40%"  align="right"  class="tab1_col3"><b>Tipo: *</b></td>
<td  width="60%"  align="left"  class="tab1_col3"><select name="estilo" id="estilo">
<option value="">Escolher</option>
<option value="1">Estática</option>
<option value="2">Animada</option>
</select>

<?php print("</td></tr><br>\n");
?>

<tr><td width="40%"  align="right"  class="tab1_col3"><b>Tempo: *</b></td>
<td  width="60%"  align="left"  class="tab1_col3"><select name="tempo" id="tempo">
<option value="">Escolher</option>
<option value="12">12 Horas</option>
<option value="24">24 Horas</option>
<option value="36">36 Horas</option>
<option value="48">48 Horas</option>
</select>

<?php print("</td></tr><br>\n");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Tema: *</b></td><td width=20%  align=left  class=tab1_col3><input type=text size=20 maxlength=20 name=tema id=tema></td></tr>");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Link Avatar: *</b></td><td width=60%  align=left  class=tab1_col3><input type=text size=40  name=linkavatar id=linkavatar></td></tr>");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Link Sign: *</b></td><td width=60%  align=left  class=tab1_col3><input type=text size=40  name=linksing id=linksing></td></tr>");
print("<tr><td width=100% align=center colspan=2 class=tab1_col3 ><input type=submit value='Enviar Pedido' style='height: 22px'>\n");
print("</form>\n");
print("</table></CENTER>\n");
  
  end_framec();
  stdfoot();

  
  
  
  
?>