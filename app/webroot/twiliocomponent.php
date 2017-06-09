<?php 
include("database.php");
include("twilio.php");
	
class TwilioComponent
{
    var $controller;
	var	$ApiVersion = "";
	var	$client = '';
	var $AccountSid = '';
	var	$AuthToken = '';
	
	function __construct(){
		$this->getObject();
	}
    function startup( &$controller ) {
        $this->controller = &$controller;
	
    }
	function getObject(){	
	}
	
	function sendsms($tonumber,$from,$body){
		$AccountSid = TWILIO_ACCOUNTSID;
		$AuthToken = TWILIO_AUTH_TOKEN;
		$this->AccountSid =$AccountSid;
		$this->AuthToken = $AuthToken;
		$this->ApiVersion = "2010-04-01";
		if($this->AccountSid!=''){
			$this->client = new TwilioRestClient($this->AccountSid, $this->AuthToken);
		}
		$vars['From']=$from;
		if(strlen($tonumber) > 10){
			$tonumber=str_replace('+','',$tonumber);
			$to='+'.$tonumber;
		}else{
			$to='+'.$tonumber;
		}	
		$vars['To']=$to;
		$vars['Body']=$body;
		$response =  $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/Messages", "POST",$vars); 
		if($response->IsError) {
			return $response;
		}
	   return $response;
	}
}
?>