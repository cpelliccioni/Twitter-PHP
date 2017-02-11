<?php

  include("class_lib.php");

  $consumer_key = <consumer_key>;
  $consumer_secret = <consumer_secret>;
  $consumer_auth_string = $consumer_key . ":" . $consumer_secret;

  $twt = new twitterClass;

  $twt->setAuthenticationString($consumer_auth_string);
  $auth_string = $twt->getAuthenticationString();
  $token = $twt->getToken($auth_string);

  $friends = $twt->getFriends($token, <user>);
  $json_friends = json_decode($friends);
  foreach($json_friends->users as $friend) {
    echo $show_wall->screen_name;
  }

 ?>
