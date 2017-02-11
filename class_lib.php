<?php

  class twitterClass {

    public $auth_string;
    public $access_token;
    public $url = "https://api.twitter.com";

    function setAuthenticationString($secret_code) {
      //Set Twitter authentication string for Basic Authentication
      $this->auth_string = $secret_code;
    }

    function getAuthenticationString() {
      //Get Twitter authentication string for Basic Authentication
      return $this->auth_string;
    }

    function getToken($secret_code) {
      //Send POST request to obtain an access token
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->url . "/oauth2/token");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_USERPWD, $this->auth_string);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $token_response = curl_exec($ch);
      curl_close ($ch);

      $json_token = json_decode($token_response);
      $this->access_token = $json_token->access_token;

      return $this->access_token;
    }

    function authWithBearerToken($token, $path) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->url . $path);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $json_response = curl_exec($ch);
      curl_close ($ch);

      return $json_response;
    }

    function getFriends($token, $key) {
      $path = "/1.1/friends/list.json?cursor=-1&screen_name=" . $key . "&count=200";
      $json_response = $this->authWithBearerToken($token, $path);

      return $json_response;
    }

    function getTweets($token, $key) {
      $path = "/1.1/search/tweets.json?q=%23" . $key . "&result_type=mixed&count=1000";
      $json_response = $this->authWithBearerToken($token, $path);

      return $json_response;
    }

    function getUsersWall($token, $key) {
      $path = "/1.1/users/show.json?screen_name=" . $key;
      $json_response = $this->authWithBearerToken($token, $path);

      return $json_response;
    }

  }

 ?>
