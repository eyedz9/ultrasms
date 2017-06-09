<?php 
//session_start();
//vendor('phpcaptcha'.DS.'php-captcha.inc.php');\
App::import('Vendor','Twitter',array('file'=>'twitteroauth/twitteroauth.php'));
//$captcha=realpath(VENDORS . 'phpcaptcha').'/php-captcha.inc.php';
//include($captcha);
class TwitterComponent extends Object{
    var $controller;
 		// Twilio REST API version 
	var	$ApiVersion = "";
	var	$client = '';
	var $ConsumerKey = '';
	var	$ConsumerSecret = ''; 
	var	$AccessToken = ''; 
	var	$AccessTokenSecret = ''; 
	function __construct(){
		$this->getObject();
	}
    function startup( &$controller ) {
        $this->controller = &$controller;
    }
	function getObject(){		
		$this->ConsumerKey= $this->ConsumerKey;
		$this->ConsumerSecret = $this->ConsumerSecret;
		if($this->ConsumerKey!=''){
			$this->twitter =new TwitterOAuth($this->ConsumerKey, $this->ConsumerSecret);
		}
	}
	function login(){
		$this->getObject();
		$url = SITE_URL.'/messages/responsetwitter';
		$request_token = $this->twitter->getRequestToken($url);
        return $request_token;
	}
	function getAuthorizeURLlogin($token){
	    $this->getObject();
		$url = $this->twitter->getAuthorizeURL($token);
        return $url;
	}
	function twitter_login_details($ConsumerKey,$ConsumerSecret,$oauth_token,$oauth_token_secret,$oauth_verifier){
	   $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$oauth_token,$oauth_token_secret);
       $access_token = $connection->getAccessToken($oauth_verifier);
		return $access_token;
	}
	function postmessage($ConsumerKey,$ConsumerSecret,$oauth_token,$oauth_token_secret,$message){
		$connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$oauth_token,$oauth_token_secret);
		$response = $connection->post('statuses/update', array('status' => $message));
		return $response;
	}
}
	
 