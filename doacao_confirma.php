<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

require_once("backend/functionsprovante.php");
require_once("backend/configprovante.php");
dbconn(true);
loggedinonly();



stdhead("Confirmar Doação");
begin_framec("Confirmar Doação");

//*/===| For all parameters -> 1 = yes ; 0 = no |===/*//

$sujets = array('Escolher','Déposito Bancário','PagSeguro','Paypal');	# Possible topics for messages (for example add to take the first 5)
$choix_urgent = 1; # You can choose to enable or disable the "urgent", and the member may indicate that his email is urgent or not
$choix_nom = 1;  # Name required?
$votre_mail = 0; # View your email directly?
$contact_email = "doacaobrshares@gmail.com";


//*/===| Don't change anything below! |===/*//

if (isset($_POST['envoyer']) && $_POST['envoyer'] == 'ok')
    {
    $reponse = '<br/>';
    $mail = utf8_decode($_POST['mail']);
    $nom = utf8_decode($_POST['nom']);
	$nomecompleto = utf8_decode($_POST['nomecompleto']);
	$indentifica = utf8_decode($_POST['indentifica']);
	$datapag = utf8_decode($_POST['datapag']);
	$planoescolher = utf8_decode($_POST['planoescolher']);
    $sujet = utf8_decode($_POST['sujet']);
    $message = nl2br(utf8_decode($_POST['message']));
    $urgent = utf8_decode($_POST['urgent']);
    $ip = $_SERVER['REMOTE_ADDR'];           //função para pegar o ip do usuário
    if ($choix_nom == 1)
        {
        if (!empty($nom))
            {
            $Snom = 1;
        } elseif (empty($nom))
            {
            $Snom = 0;
        }
    } else
        {
        $Snom = 1;
    }

    if (!empty($nomecompleto) && !empty($nom) && !empty($mail) && !empty($indentifica) && !empty($planoescolher)  && !empty($datapag)  && $sujet != '' && $Snom == 1)
        {
        $entete = "MIME-Version: 1.0\r\n";
        $entete .= "Content-type: text/html; charset=utf-8\r\n";
        $entete .= "From: <$mail>\r\n";
        $entete .= "Reply-To: $mail\r\n";
        $email = '';

        if ($urgent == 1)
            $email .= '<div style=\'padding-top:3px\'><font size=2 color=red><b>Urgent message!</b></font></div>';

        if (empty($nom))
            $nom = '<font size=2 color=red><b>Not specified</b></font>';

        $email .= '<div style=\'padding-top:3px\'><font size=2>You received a message from your site <b>' . $site_config['SITENAME'] . '</b></font></div>
		<div style=\'padding-top:3px\'><font size=2>Reason: <b>' . $sujets[$sujet] . '</b></font></div>
		<div style=\'padding-top:3px\'><font size=2>Email: ' . $mail . '</font></div>';
        $email .= '<div style=\'padding-top:3px\'><font size=2>Sender: <b>' . $nom . '</b></font></div>
		<div style=\'padding-top:7px\'><font size=2>Message:</font></div>
		-------------------------------------------------------------------------<br />';
        $email .= $message;
        $email = stripslashes($email);
		$metoddoar = utf8_decode($_POST['sujet']);
		$emaildoacao="brshares2@gmail.com";
	    $metodos = utf8_decode('Método de pagamento');
	    $usuario = utf8_decode('Usuário');
	    $indentificar = utf8_decode('Identificação do pagamento');
	    $inforadd = utf8_decode('Informações adicionais');
	$body = <<<EOD

Nome: $nomecompleto
Email: $mail
Ip: $ip
$metodos: $metoddoar
$usuario: $nom
$indentificar: $indentifica
Data do pagamento: $datapag
Plano escolhido: $planoescolher

$inforadd : $message

EOD;

				sendmail($emaildoacao, "Comprovante BR", $body, "para: $site_config[SITEEMAIL]", "-f$site_config[SITEEMAIL]");
				$mailsent = 1;
		

        $reponse .= '<center><font size=2>Seu comprovante foi enviado com Sucesso!</font></center><br />';
		   $sender_id =  $CURUSER['id'];
		   $sender_user = $CURUSER['username'];
		    $added = get_date_time();
			
		SQL_Query_exec("INSERT INTO  donate (uid, username, added, plano, metudo, idkey ) VALUES($sender_id, '$sender_user', '$added', '$planoescolher', '$metoddoar', '$indentifica' )");
    } else {
        $reponse .= '<center><font size=2 color=red>Você deve preencher todos os campos obrigatórios!</font></center><br />';
    }
}
?>


<form name="form1" method="post" action="">
<p><strong><?php echo $reponse;?></strong></p>
<br><table width="100%" border="1" cellpadding="0" cellspacing="0" >
	<div class="tab1_cab1" align="center">Confirmar Doação</font></div>
	<tr><td width="50%"  align="right"  class="tab1_col3"  ><label>Nome Completo: <font color='red'>*</font></td><td width="50%"  align="left" class="tab1_col3"  ><input type="text" class="buttonInput" name="nomecompleto" value=""></label></td></tr>
		<tr><td width="50%"  align="right"  class="tab1_col3"  ><label>Email: <font color='red'>*</font></td><td width="50%"  align="left" class="tab1_col3"  ><input type="text" class="buttonInput" name="mail" value="<?php print("" . $CURUSER["email"] . "\n");?>"></label></td></tr>
			<tr><td width="50%"  align="right"  class="tab1_col3"  ><label>Usuário: <font color='red'>*</font></td><td width="50%"  align="left" class="tab1_col3"  ><input type="text" class="buttonInput" name="nom" value="<?php print("" . $CURUSER["username"] . "\n");?>"></label></td></tr>
			<tr><td width="50%"  align="right"  class="tab1_col3"  >Método de pagamento: <font color='red'>*</font></td><td width="50%"  align="left" class="tab1_col3"  ><select name="sujet">
		<?php for ($i = 0; $i < count($sujets); $i++) {
			echo '<option value="' . $i . '">' . $sujets[$i] . '</option>';
		} ?>
		</select>
		</td></tr>
			<tr><td width="50%"  align="right"  class="tab1_col3"  ><label>Identificação do pagamento: <font color='red'>*</font></td><td width="50%"  align="left" class="tab1_col3"  ><input type="text" class="buttonInput" name="indentifica" value=""></label><br><label>Em caso de pagamento via Pagseguro, informar a ID de transação (Autenticação) Pagseguro
Em caso de pagamento via depósito em Conta Corrente, informar a Agência e Número do Documento</label></td></tr>
	<tr><td width="50%"  align="right"  class="tab1_col3"  ><label>Data do pagamento: <font color='red'>*</font></td><td width="50%"  align="left" class="tab1_col3"  ><input type="text" class="buttonInput" name="datapag" value=""></label></td></tr>
		<tr><td width="50%"  align="right"  class="tab1_col3"  ><label>Plano escolhido: <font color='red'>*</font></td><td width="50%"  align="left" class="tab1_col3"  ><input type="text" class="buttonInput" name="planoescolher" value=""></label></td></tr>

		<?php
		if ($choix_urgent == 1) {
			echo '<tr><td width=50%  align=right  class=tab1_col3  >Sua mensagem é urgente</td><td width=50%  align=left class=tab1_col3  >';
			echo '<label><input type="radio" name="urgent" value="1"> ' . T_('YES') . '</label> &nbsp;&nbsp;';
			echo '<label><input type="radio" name="urgent" value="0" checked> ' . T_('NO') . '</label>&nbsp;&nbsp;';
			echo '<font color=\'red\'>Obrigado por não abusar da emergência</font>
		</td></tr>';
		}
		?>
		<tr><td width="50%"  align="right"  class="tab1_col3">Informações adicionais<br>(opcional): </td><td width="50%"  align="left" class="tab1_col3" ><textarea name="message" cols="55" rows="5"></textarea></td></tr>
		<tr><td colspan='2' class="tab1_col3" align='center'>
			<input class="bottom" type="hidden" name="envoyer" value="ok">
			<input class="bottom" type="submit" name="Submit" value="Enviar Confirmação" />
			<input class="bottom" type="reset" name="Submit2" value="Anular" />
		</td></tr>
	</table>

</form>
<?php
end_framec();
stdfoot();
?>