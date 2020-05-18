<?php


function connect_cast($host, $port, $password)

{

	$fp = @fsockopen($host, $port, $errno, $errstr, 3);

	

	if(!$fp)

		return false;



	$req = 'GET /' . ($password ? ('admin.cgi?pass=' . $password . '&mode=viewxml') : '');

	

	fputs($fp, $req . " HTTP/1.0\r\nUser-Agent: Kiwi XML Getter (Mozilla Compatible)\r\n\r\n");

	

	$page = '';

	while(!feof($fp))

		$page .= fread($fp, 1000);

	

	fclose($fp);

	return $page;

}



/* Récuperation des données depuis le fichier XML */

function admin_cast($tab_pattern, $tab_source, $multi = false)

{

	foreach($tab_pattern as $key)

	{

		preg_match_all('`<' . $key . '>([^<]*)<`i', $tab_source, $tabt);

		$stats[$key] = $multi ? $tabt[1] : $tabt[1][0];

	}

	return $stats;

}



/* Récupération des données depuis la page d'acceuil du flux */

function info_cast($tab_pattern, $tab_source)

{

	foreach($tab_pattern as $key => $value)

	{

		if (preg_match('`' . $value . '`i', $tab_source, $tabt))

			$stats[$key] = $tabt[1];

	}

	return $stats;

}



/* Fonction générale ;) */

function shoutcast_stats($host, $port, $password = false)

{

	$page = connect_cast($host, $port, $password);		// récupération éventuelle du contenu XML

	

	if (!$page)

		return false;



	preg_match('`^(HTTP/1\.0|ICY) ([[:digit:]]{3})`', $page, $tab_code);

	

	if ($tab_code[2] != '200')

	{

		$stats['http_code'] = $tab_code[2];

		return $stats;

	}

	

	if ($password)

	{	

		$liste_simple = array(								// définition des balises simple à récupérer

			'version',

			'servertitle',

			'serverurl',

			'streamstatus',

			'currentlisteners',

			'maxlisteners',

			'peaklisteners',

			'songtitle',

			'servergenre',

			'content',

			'bitrate'

		);

		

		$liste_multiple = array(							// définition des balises potentiellement multiples

			'hostname',

			'useragent',

			'connecttime',

			'pointer',

			'title',

			'playedat'

		);

		

		$stats = admin_cast($liste_simple, $page);					// création d'un tableau correspondant à chaque balise

		$stat_multiple = admin_cast($liste_multiple, $page, true);	// création du tableau pour les balises multiples

			

			foreach($stat_multiple['pointer'] as $key => $value)		// réorganisation des données sur les clients

			{															// avec pour index l'identifiant du client

				$stats['auditeur'][$value]['hote']		= $stat_multiple['hostname'][$key];

				$stats['auditeur'][$value]['useragent']	= $stat_multiple['useragent'][$key];

				$stats['auditeur'][$value]['temps']		= $stat_multiple['connecttime'][$key];

			}

	

			foreach($stat_multiple['playedat'] as $key => $value)				// réorganisation des titres joués avec pour index

				$stats['morceau'][$value]	= $stat_multiple['title'][$key];	// la date de lecture */

	}

	else

	{

			$bazard_shoutcast = ': </font></td><td><font class=default><b>';

			$infos_basic_pattern = array(

			'version'			=> 'SHOUTcast Server Version ([1-9\.]+)',

			'servertitle'		=> 'Stream Title' . $bazard_shoutcast . '([^<]*)<',

			'serverurl'			=> 'Stream URL' . $bazard_shoutcast . '<a href="[^"]*">([^<]*)<',

			'streamstatus'		=> 'Server Status' . $bazard_shoutcast . 'Server is currently (up|down)',

			'currentlisteners'	=> 'Stream is up at [[:digit:]]+ kbps with <B>([[:digit:]]+) of',

			'maxlisteners'		=> 'Stream is up at [[:digit:]]+ kbps with <B>[[:digit:]]+ of ([[:digit:]]+)',

			'peaklisteners'		=> 'Listener Peak' . $bazard_shoutcast . '([[:digit:]]+)',

			'songtitle'			=> 'Current Song' . $bazard_shoutcast . '([^<]*)<',

			'servergenre'		=> 'Stream Genre' . $bazard_shoutcast . '([^<]*)<',

			'content'			=> 'Content Type' . $bazard_shoutcast . '([^<]*)<',

			'bitrate'			=> 'Stream is up at ([[:digit:]]+) kbps'

		);

		

		/* Restons compatible ;) */

		$stats = info_cast($infos_basic_pattern, $page);

		if ($stats['streamstatus'] != 'up')

			$stats['streamstatus'] = $stats['currentlisteners'] = $stats['maxlisteners'] = $stats['peaklisteners'] = 0;

		else

			$stats['streamstatus'] = 1;

	}

			

	$stats['http_code'] = $tab_code[2];

	return $stats;

}

?>
