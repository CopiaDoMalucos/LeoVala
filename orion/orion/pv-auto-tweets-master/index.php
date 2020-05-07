<?php

$consumerKey = '8i0iM7avfCNGROIglw6fOq5LD';
$consumerSecret = 'VZtfYuRPJQ5c117z2Sn7VZyt40WQYkQ67I0KkItdwov4zgM8lY';
$OAuthToken = '343555116-pSJxfGOMOgaSyMvs3acIWtJR6hzl1hB57S6sa2Vz';
$OAuthSecret = 'F1K40c3ACmjBknIHBCGIq3U89i9AFLPXxbpC6tFKcGt9h';



# API OAuth
require_once('twitteroauth.php');
$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $OAuthToken, $OAuthSecret);


# your code to retrieve data goes here, you can fetch your data from a rss feed or database

$tweet->post('statuses/update', array('status' => 'here the content of your tweet, you can add hashtags or links'));
?>
