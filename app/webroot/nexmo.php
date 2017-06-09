<?php 
include("NexmoMessage.php");
include("database.php");
class NexmoComponent 
{
    var $components = array('Cookie','Email','Session');
    var $controller;
 	// Nexmo REST API version 
	var	$ApiVersion = "";
	var $Key = '';
	var	$Secret = ''; 
	function __construct(){
		$this->getObject();
	}
    function startup( &$controller ) {
        $this->controller = &$controller;
    }
	function getObject(){		
		$this->Key =NEXMO_KEY;
		$this->Secret = NEXMO_SECRET;
		if($this->Key!=''){
			$this->client = new NexmoMessage($this->Key, $this->Secret);
		}	
	}
 
	function sendsms($tonumber,$from,$body){
		$this->getObject();
		if(strlen($tonumber) > 10){
			$tonumber=str_replace('+','',$tonumber);
			$to='+'.$tonumber;
		}else{
			$to='+'.$tonumber;
		}
        $response = $this->client->sendText($to,$from,$body);			   			
		return $response;
	} 
}
   
?>