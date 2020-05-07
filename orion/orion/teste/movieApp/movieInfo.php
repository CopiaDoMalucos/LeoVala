<?php

/**
 *	Define your API key below. To obtain one, visit 
 *	http://www.themoviedb.org/account/signup
 */

$api_key = 'f6682e3f0ca2d822b7d7eedb2d6d18f0';


// If the request was not issued by AJAX, or
// The search term is missing, exit:

if(!$_SERVER["HTTP_X_REQUESTED_WITH"] || !$_GET['term']){
	exit;
}

include 'tmdbAPI/TMDb.php';

$tmdb = new TMDb($api_key);


// Send a search API request to TMDb,
// and parse the returned JSON data:

$json = json_decode($tmdb->searchMovie($_GET['term']));

$response = array();

$i=0;
foreach($json as $movie){
	
	// Only movies existing in the IMDB catalog (and are not adult) are shown
	
	if(!$movie->imdb_id || $movie->adult) continue;
	if($i >= 8) break;
	
	
	// The jQuery autocomplete widget shows the label in the drop down,
	// and adds the value property to the text box.
	
	$response[$i]['value'] = $movie->name;
	$response[$i]['label'] = $movie->name . ' <small>(' . date('Y',strtotime($movie->released)).')</small>';
	$i++;
}

// Transforming the response as a JSON object:

echo json_encode($response);

?>