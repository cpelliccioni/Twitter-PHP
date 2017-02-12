<?php

  include("TwitterAdapter.php");

  $consumer_key = "<consumerkey>";
  $consumer_secret = "<consumersecret>";
  $consumer_auth_string = $consumer_key . ":" . $consumer_secret;

  $twt = new TwitterAdapter($consumer_auth_string);  
  $twt->generateToken();
  $friends = $twt->getFriends("<nick>");
  $json_friends = json_decode($friends);
  foreach($json_friends->users as $friend) {
    echo $friend->screen_name.PHP_EOL;
  }

 ?>
