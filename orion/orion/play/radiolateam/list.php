<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

	

<head>

	




	


</head>



<body>






<?php



include 'statcast.php';


$serveur	= '187.45.245.85';

$port		= 8000;

$adminpass	= your pass;





$tab_infos = shoutcast_stats($serveur, $port, $adminpass);




	

	echo '';

	

	if ($tab_infos)	// avons nous réussi à nous connecter au serveur ?

	{

		if ($tab_infos['http_code'] == '200')	// le serveur ne nous a-t-il pas jeté pour cause de mot de passe invalide ?

		{

			


			if ($tab_infos['streamstatus'])		// le serveur diffuse-t-il ?

			{

				/*	Il se peut que certaines informations de flux soit encore présente même si le serveur ne diffuse pas

					mais il n'y a pas de réel interet à les afficher dans ce cas ^^

				*/

				

				

			}

			else								// il ne diffuse pas, montrons le  ^^

				echo '<h3>O servidor não está a transmitir...</h3>';

				

			if (array_key_exists('morceau', $tab_infos))	// pouvons nous afficher un historique des morceaux ?

			{

				

				

				echo '';

				

				foreach($tab_infos['morceau'] as $date => $titre)

					echo ' 


							


									<FONT size="1pt"> <font color="white"><br />+' . $titre . '</font> </FONT>		


								


									
';



				echo '';

			}

			

			

			if (array_key_exists('auditeur', $tab_infos))	// pouvons nous afficher la liste des auditeurs ?

			{

				echo '<h3>Conexões :</h3>';

				

				echo '<table>';

				echo '<tr><th>ID</th><th>Hôte</th><th>User Agent</th><th>Durée (s)</th></tr>';

				

				/*	Dans cet exemple, la résolutions des IP en nom DNS est présente, pour plus de rapidité ou si elle est déja activé au niveau

					du serveur ShoutCast il est conseillé de transformer : "gethostbyaddr($valeur['hote'])" en "$valeur['hote']". */

				

				foreach($tab_infos['auditeur'] as $id => $valeur)

					echo '<tr><td>' . $id . '</td><td>' . gethostbyaddr($valeur['hote']) . '</td><td>' . $valeur['useragent'] . '</td><td>' . $valeur['temps'] . '</td></tr>';



				echo '</table>';

			}



		}

		else

			echo '<strong>Erro</strong> : A senha é inválida, sem dúvida, verifique a variável apropriada.';

	}

	else

		echo '<strong>Erro</strong> : conexão com o servidor <code>' . $serveur . '</code> na porta<code>' . $port . '</code> não ... (servidor em baixo?)';

	

	echo '</div>';







?>



</body>

</html>
