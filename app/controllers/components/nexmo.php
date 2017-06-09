<?php 
//session_start();
//vendor('phpcaptcha'.DS.'php-captcha.inc.php');\
App::import('Vendor','Nexmo',array('file'=>'nexmo/NexmoAccount.php'));
//$captcha=realpath(VENDORS . 'phpcaptcha').'/php-captcha.inc.php';
//include($captcha);

class NexmoComponent extends Object{
    var $controller;
 	// Twilio REST API version 
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
			// Set our AccountSid and AuthToken 
			//$this->AccountSid = 'AC6caa9b833461d6a1a62586a4a23f4286';
			$this->Key = $this->Key;
			$this->Secret = $this->Secret;
			// Instantiate a new Twilio Rest Client 
			if($this->Key!=''){
				$this->client = new NexmoAccount($this->Key, $this->Secret);
			}
	}
    function listNumbers($api_key,$api_secret,$country_code,$pattern,$feature){
		$url = 'https://rest.nexmo.com/number/search';
		$fields = array('api_key' => $api_key,
		'api_secret' => $api_secret,
		'pattern' => $pattern,
		'country' => $country_code,
		'features' => $feature,
		'size' => 100
		);
		$url = $url . '?' . http_build_query($fields);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);
		$response=json_decode($result);
		return $response;
    }
	function assignthisnumber($country_code,$PhoneNumber,$api_key,$api_secret){
		$url = 'https://rest.nexmo.com/number/buy';
		$fields = array('api_key' => $api_key,
		'api_secret' => $api_secret,
		'country' => $country_code,
		'msisdn' => $PhoneNumber,
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$result = curl_exec($ch);
		$jsonresponse = json_decode($result);
		return $jsonresponse;
	}
	function updatenumber ($country_code,$PhoneNumber,$siteurl,$api_key,$api_secret){
		$moHttpUrl = $siteurl.'/nexmos/sms';
		$voiceCallbackType ='vxml';
		$voiceCallbackValue = $siteurl.'/nexmos/voiceCallbackValue?voicenumber='.$PhoneNumber;
		$voiceStatusCallback = $siteurl.'/nexmos/voiceStatusCallback';
		$url = 'https://rest.nexmo.com/number/update';
		$fields = array('api_key' => $api_key,
		'api_secret' => $api_secret,
		'msisdn' => $PhoneNumber,
		'country' => $country_code,
		'moHttpUrl' => $moHttpUrl,
		'voiceCallbackType' => $voiceCallbackType,
		'voiceCallbackValue' =>$voiceCallbackValue,
		'voiceStatusCallback' =>$voiceStatusCallback
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$result = curl_exec($ch);
		$json = json_decode($result);
		return $json;
	}
	
	function releasenumber($country_code,$PhoneNumber,$api_key,$api_secret){
		$url = 'https://rest.nexmo.com/number/cancel';
		$fields = array('api_key' => $api_key,
		'api_secret' => $api_secret,
		'country' => $country_code,
		'msisdn' => $PhoneNumber
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$result = curl_exec($ch);
		$jsonresponse = json_decode($result);
		return $jsonresponse;
	}
	function callNumberToPEOPLE($callnumber,$callerPhone,$group_id,$api_key,$api_secret,$log_id,$repeat,$language,$pause){
		$from=$callerPhone;
		$to=$callnumber;//"+8801926662284";
		$site_url = SITE_URL;
		//$forwardcall = $site_url.'/nexmos/peoplecallrecordscript?group_id='.$group_id.'';	
		//$forwardcall = $site_url.'/nexmos/peoplecallrecordscript?group_id='.$group_id.'repeat='.$repeat.'language='.$language.'pause='.$pause.'';	
		$forwardcall = $site_url.'/nexmos/peoplecallrecordscript/'.$group_id.'/'.$repeat.'/'.$language.'/'.$pause;
		$status_url = $site_url.'/nexmos/callStatus?log_id='.$log_id.'';		
		$error_url = $site_url.'/nexmos/error_url?log_id='.$log_id.'';		
		$url = 'https://rest.nexmo.com/call/json';
		$fields = array('api_key' => urlencode($api_key),
			'api_secret' => urlencode($api_secret),
			'from' => urlencode($from),
			'to' => urlencode($to),
			'answer_url' =>$forwardcall,
			'status_url' =>$status_url,
			'error_url' =>$error_url
		);
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$result = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result);
		return $json;
	}

}
?>