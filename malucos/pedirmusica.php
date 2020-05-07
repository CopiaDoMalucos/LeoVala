<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/smilies.php");
require_once("backend/functions.php");
dbconn(false);
loggedinonly();

$musica = $_POST['musica'];
$artista = $_POST['artista'];
$recado = $_POST['recado'];
$ouvinte = $CURUSER['username'];
$page = (string) $_GET["page"];
$action = (string) $_GET["action"];

?>
    <fieldset><legend><?php echo T_("PEDIR_MUSICA"); ?></legend>
                        <form name="pedirmusica" method="post" action="?page=pedirmusica&action=pedir" />
                        	<table border="0">
                            	<tr>
                                	<td><?php echo T_("PEDIR_MUSICA_NOME"); ?>: </td>
                                    <td><input type="text" size="60" maxlength="30" name="musica" /></td>
                                </tr>
                                <tr>
                                	<td><?php echo T_("PEDIR_MUSICA_ARTISTA"); ?>: </td>
                                    <td><input type="text" size="40" name="artista" /></td>
                                </tr>
								      <tr>
                                	<td>Recado: </td>
                                    <td><input type="text" size="40" name="recado" /></td>
                                </tr>
                                <tr>
                                	<td colspan="2">
                                    	<input type="submit" value="<?php echo T_("TESTE_PORTA_ENVIAR"); ?>" />
                                    </td>
                                </tr>
                            </table>

                        </form>
    <?php
	if ($action){
			if(!$artista or !$musica){
			print("" .T_("PEDIR_MUSICA_ARTISTA_CAMPOS"). "!");
	}else{
	$pedidolink = '<table cellspacing="0" cellpadding="3" border="0" align="left"><tbody><tr><td align="center"><img border="0" style="max-width:600px; expression(this.width &gt; 600 ? 600: true);" src="http://www.malucos-share.org/images/anuncio.png"></td></tr></tbody></table><table cellspacing="0" cellpadding="3" border="0" align="left"><tbody><tr><td bgcolor="#2e2e2e" align="center" class="shoutbox_shoutbtn" colspan="2"><span style="font-size:9pt"><span style="font-family:georgia"><b> Novo Pedido Da Radio</b></span></span></td></tr><tr><td align="right" style="font-size:8pt"><b>Música:</b> '.$musica.'</td><td align="center" style="font-size:8pt"><b>Artista:</b> '.$artista.'</td></tr><tr><td align="left" style="font-size:8pt"><b>Pedido por:</b><span style="color:#FFFFFF;"> <a href="'.$site_config['SITEURL'].'/account-details.php?id='.$CURUSER['id'].'" target="_parent"><b>' .$CURUSER['username']. '</b></a></span></td><td align="center" style="font-size:8pt"><b>Recado:</b> ' .$recado. '.</td></tr></tbody></table>';


	mysql_query("INSERT INTO shoutbox (msgid, user, message, date, userid) VALUES('', 'System', '$pedidolink', now(), '0')");

		
		
		
		
		
		print("<b><center>Seu pedido foi realizado com sucesso!!!!<br><a href='javascript:window.close();'>Fechar</a></center></b>");


die;

			}
	}

?>