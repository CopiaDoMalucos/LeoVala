<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################


// incluir a lib fo facebook
require_once("backend/functions.php");
dbconn();
require 'src/facebook.php';

// Cria a instancia da aplicacao, informando o appid e o secret
$facebook = new Facebook(array(
  'appId'  => '1652293441668671',
  'secret' => 'ffdc3d919d0ed17b8ec503ebdc7861e4',
));

// parametrizar o id da fanpage
$ID_FANPAGE = '282069755171491';

// obtem o id do usuario
$user_id = $facebook->getUser();

if ($user_id) { // usuario logado
  // solicitar permissao
	try {
		$permissions = $facebook->api("/me/permissions");
		// tratar permissoes
                $permissions_granted = array();
                foreach($permissions['data'] as $perm) {
                        if($perm['status'] == 'granted') {
                                array_push($permissions_granted, $perm['permission']);
                        }
                }

                if(!in_array('manage_pages', $permissions_granted)
                        || !in_array('publish_pages', $permissions_granted)
                ) {
                        header("Location: " . $facebook->getLoginUrl(array("scope" => "manage_pages, publish_pages")));
                        exit;
                }


		// obtendo token da fan page baseado no ID
		$fanpage_token = null;
	        $accounts = $facebook->api('/me/accounts', 'GET');
	        foreach($accounts['data'] as $account) {
	                if($account['id'] == $ID_FANPAGE){
	                        $fanpage_token = $account['access_token'];
	                }
	        }
$semmod = mysql_query("SELECT * FROM torrents WHERE adota = '1' AND category != '106' AND category != '104' AND category != '47' ORDER BY torrents.added DESC") or sqlerr();	

$ressemmod = mysql_fetch_array($semmod);

$torrentname = $ressemmod["name"];
$torrentscreem = $ressemmod["screens1"];
$id = $ressemmod["id"];			
		if($fanpage_token) {
			// conseguimos obter o token da fan page
			// publicar

			// dados para envio da publicacao da foto
			$feed_data = array(				
				"message" => "".$torrentname." http://www.brshares.com/torrents-details.php?id=".$id."",
				"url" => "".$torrentscreem."", 
				"privacy" => "{'value':'SELF'}",
				"access_token" => $fanpage_token,
			);

			// publica foto na timeline
			$dados = $facebook->api("/$ID_FANPAGE/photos", 'post', $feed_data);
			echo "Publicado na fan page com sucesso";
			mysql_query("UPDATE torrents SET adota='2' WHERE id = ".$ressemmod["id"]."") or die(mysql_error());
		}


        } catch (FacebookApiException $e) {
                var_dump($e);
                $user = null;
					mysql_query("UPDATE torrents SET adota='2' WHERE id = ".$ressemmod["id"]."") or die(mysql_error());
        }
} else {
        // usuario nao logado, solicitar autenticacao
        $loginUrl = $facebook->getLoginUrl();
	header("Location: $loginUrl");
}
?>