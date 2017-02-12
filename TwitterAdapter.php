<?php

class TwitterAdapter {
	
	const BASE_URL = "https://api.twitter.com";
	const OAUTH2_TOKEN_URL = "oauth2/token";
	const API_VERSION = "1.1";
	const FRIENDS_URL = "friends/list.json?cursor=-1&screen_name=%s&count=200";
	const TWEETS_URL = "search/tweets.json?q=%23%s&result_type=mixed&count=1000";
	const USERS_WALL_URL = "users/show.json?screen_name=%s";
	
    private $auth_string;
    private $access_token;

	public function __construct($auth_string) {
		$this->auth_string = $auth_string;
	}
	
	public function getAccessToken() {
		return $this->access_token;
	}
	
    public function setAuthenticationString($secret_code) {
      //Set Twitter authentication string for Basic Authentication
      $this->auth_string = $secret_code;
	  return $this;
    }

    public function getAuthenticationString() {
      //Get Twitter authentication string for Basic Authentication
      return $this->auth_string;
    }

    public function generateToken() {
      //Send POST request to obtain an access token
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, sprintf("%s/%s", self::BASE_URL, self::OAUTH2_TOKEN_URL));
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_USERPWD, $this->auth_string);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $token_response = curl_exec($ch);
      curl_close ($ch);
      $json_token = json_decode($token_response);
	  if ($json_token == null) {
		  throw new Exception("Unable to generate token, credentials error");
	  }
      $this->access_token = $json_token->access_token;
    }

    public function authWithBearerToken($path) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, sprintf("%s/%s", self::BASE_URL, $path));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->access_token));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      $json_response = curl_exec($ch);
      curl_close ($ch);

      return $json_response;
    }

    public function getFriends($key = null) {
	  $path = sprintf("%s/%s", self::API_VERSION, sprintf(self::FRIENDS_URL, $key));
      return $this->authWithBearerToken($path);
    }

    public function getTweets($key) {
	  $path = sprintf("%s/%s", self::API_VERSION, sprintf(self::TWEETS_URL, $key));
      $json_response = $this->authWithBearerToken($path);
      return $json_response;
    }

    public function getUsersWall($key) {
	  $path = sprintf("%s/%s", self::API_VERSION, sprintf(self::USERS_WALL_URL, $key));
      $json_response = $this->authWithBearerToken($path);
      return $json_response;
    }

  }

 ?>
