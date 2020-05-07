<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

	

<head>

	<title>by CRAZZY</title>

	

	<meta name="title"				content="Page de test by lateam" />

	<meta name="description"		content="bvlist" />

	<meta name="robots"				content="INDEX, FOLLOW" />

	<meta http-equiv="Content-Type"	content="text/html; charset=iso-8859-1" />

	<link type="text/css" rel="stylesheet" href="kiwicast.css" />


</head>



<body>



<h1>by lateam</h1>



<?php


include 'statcast.php';










$serveur	= '187.45.245.85';

$port		= 8000;

$adminpass	= 1m2a3l4u5c6o;



/* C'est là que ce fait tout le boulot ;) */

$tab_infos = shoutcast_stats($serveur, $port, $adminpass);



echo '<h2>Mise en forme rapide</h2>';

	

	echo '<div id="presentation">';

	

	if ($tab_infos)	// avons nous réussi à nous connecter au serveur ?

	{

		if ($tab_infos['http_code'] == '200')	// le serveur ne nous a-t-il pas jeté pour cause de mot de passe invalide ?

		{

			echo '<p>Serveur : <code>' . $serveur . ':' . $port . ' (<em>version ' . $tab_infos['version'] . '</em>)</code><br/>';

			echo 'Utilisateurs : <strong>' . $tab_infos['currentlisteners'] . '/' . $tab_infos['maxlisteners'] . '</strong> (max : <strong>' . $tab_infos['peaklisteners'] . '</strong>)</p>';



			if ($tab_infos['streamstatus'])		// le serveur diffuse-t-il ?

			{

				/*	Il se peut que certaines informations de flux soit encore présente même si le serveur ne diffuse pas

					mais il n'y a pas de réel interet à les afficher dans ce cas ^^

				*/

				

				echo '<h3>Diffusion en cours...</h3>';

				

				echo '<table class="serveur">';

					echo '<tr><th>Description :</th><td>' . $tab_infos['servertitle'] . '</td></tr>';

					echo '<tr><th>Adresse internet :</th><td><a href="' . $tab_infos['serverurl'] . '">' . $tab_infos['serverurl'] . '</a></td></tr>';

					echo '<tr><th>Titre en cours :</th><td>' . $tab_infos['songtitle'] . ' (<em>' . $tab_infos['bitrate'] . ' kbps ' . $tab_infos['content'] . '</em>)</td></tr>';

				echo '</table>';

			}

			else								// il ne diffuse pas, montrons le  ^^

				echo '<h3>Le serveur ne diffuse pas actuellement...</h3>';

				

			if (array_key_exists('morceau', $tab_infos))	// pouvons nous afficher un historique des morceaux ?

			{

				echo '<h3>Historique des titres :</h3>';

				

				echo '<table>';

				echo '<tr><th>Date de diffusion</th><th>Titre du morceau</th></tr>';



				foreach($tab_infos['morceau'] as $date => $titre)

					echo '<tr><td>' . date("d/m/Y H:i:s", $date) . '</td><td>' . $titre . '</td></tr>';



				echo '</table>';

			}

			

			

			if (array_key_exists('auditeur', $tab_infos))	// pouvons nous afficher la liste des auditeurs ?

			{

				echo '<h3>Connexions :</h3>';

				

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

			echo '<strong>Erreur</strong> : Le mot de passe est sans doute invalide, vérifier la variable adéquat.';

	}

	else

		echo '<strong>Erreur</strong> : la connexion au serveur <code>' . $serveur . '</code> sur le port <code>' . $port . '</code> à échoué... (serveur down ?)';

	

	echo '</div>';



/* Pour voir ce que retourne le tableau */

echo '<h2>Tableau complet <em>(debug)</em></h2>';



	echo '<div id="debug">';

	

	if ($tab_infos)

	{

		echo '<pre>';

		print_r($tab_infos);

		echo '</pre>';

	}

	

	echo '</div>';



?>



</body>

</html>
