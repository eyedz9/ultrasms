<?php 
//session_start();
//vendor('phpcaptcha'.DS.'php-captcha.inc.php');\
App::import('Vendor','Mms',array('file'=>'services/Twilio.php'));
//$captcha=realpath(VENDORS . 'phpcaptcha').'/php-captcha.inc.php';
//include($captcha);
class MmsComponent extends Object{
    var $controller;
 		// Twilio REST API version 
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
		$this->AccountSid = $this->AccountSid;
		$this->AuthToken = $this->AuthToken;
		$this->ApiVersion = "2010-04-01";
		if($this->AccountSid!=''){
			$this->client = new Services_Twilio($this->AccountSid, $this->AuthToken);
		}
	}
	function sendmms($tonumber,$from,$body,$mms_text){
		$this->getObject();
		$From=$from;
		if(strlen($tonumber) > 10){
			$tonumber=str_replace('+','',$tonumber);
			$to='+'.$tonumber;		}else{			$to='+1'.$tonumber;
		}
		$media='';
		foreach($body as $key=>$value){
				$media[]=$value;
		}
		$Body='';
		if($mms_text!=''){
			$Body=$mms_text;
		}
		$StatusCallback = SITE_URL.'/twilios/callstatus';	
		$response='';
		try {
			$response = $this->client->account->messages->sendMessage($From,$to,$Body,$media,array('StatusCallback' =>$StatusCallback ));
		} catch (Exception $e) {
		$response=$e->getMessage();
	//$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
		}
		   return $response;
	}
}
?>