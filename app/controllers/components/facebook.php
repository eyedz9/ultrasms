<?php 
//session_start();
//vendor('phpcaptcha'.DS.'php-captcha.inc.php');\
App::import('Vendor','Facebook',array('file'=>'facebook/facebook.php'));
//$captcha=realpath(VENDORS . 'phpcaptcha').'/php-captcha.inc.php';
//include($captcha);
class FacebookComponent extends Object{
    var $controller;
 	// Twilio REST API version 
	var	$ApiVersion = "";
	var	$client = '';
	var $Appid = '';
	var	$AppSecret = ''; 
	function __construct(){
		$this->getObject();
	}
    function startup( &$controller ) {
        $this->controller = &$controller;
    }
	function getObject(){		
			// Set our AccountSid and AuthToken 
			//$this->AccountSid = 'AC6caa9b833461d6a1a62586a4a23f4286';
			  $this->Appid = $this->Appid;
			  $this->AppSecret = $this->AppSecret;
			  $fileUpload=true;
			  $cookie=true;
			// $this->AccountSid = 'ACfd89698e0ab9d7e8a0bd607e3102783c';
			// $this->AuthToken = '4060d70915f02a0df093086c24677225';
			// $this->AuthToken = 'f17115cfea8870b8caebf6825ed5c2fa';
			// Instantiate a new Twilio Rest Client 
			if($this->Appid!=''){
				$this->facebook = new Facebook(array('appId'  => $this->Appid,'secret' =>$this->AppSecret,'fileUpload' => true,'cookie' => true));
			}
	}
    function checkfblogin(){
		$this->getObject();
		$fbuser = $this->facebook->getUser();
		return $fbuser;
	}
	function messagepost($messagefb,$facekbookid,$url){
	   $this->getObject();
	   $post_url = '/'.$facekbookid.'/feed';
	   $msg_body = array('message' => $messagefb,'picture'=>$url);
	   $status = $this->facebook->api($post_url, "post",  $msg_body);
	   return $status;
	}
   function messagepostold($messagefb,$facekbookid){
	   $this->getObject();
	   $post_url = '/'.$facekbookid.'/feed';
	   $msg_body = array('message' => $messagefb);
	   $status = $this->facebook->api($post_url, "post",  $msg_body);
	   return $status;
	}
}
	
 