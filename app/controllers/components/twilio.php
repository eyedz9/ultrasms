<?php 
//session_start();
//vendor('phpcaptcha'.DS.'php-captcha.inc.php');\
App::import('Vendor','Twilio',array('file'=>'twilio/twilio.php'));
//$captcha=realpath(VENDORS . 'phpcaptcha').'/php-captcha.inc.php';
//include($captcha);

class TwilioComponent extends Object{
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
			// Set our AccountSid and AuthToken 
			//$this->AccountSid = 'AC6caa9b833461d6a1a62586a4a23f4286';
			$this->AccountSid = $this->AccountSid;
			$this->AuthToken = $this->AuthToken;
			// $this->AccountSid = 'ACfd89698e0ab9d7e8a0bd607e3102783c';
			// $this->AuthToken = '4060d70915f02a0df093086c24677225';
			// $this->AuthToken = 'f17115cfea8870b8caebf6825ed5c2fa';
			$this->ApiVersion = "2010-04-01";
			// Instantiate a new Twilio Rest Client 
			if($this->AccountSid!=''){
				$this->client = new TwilioRestClient($this->AccountSid, $this->AuthToken);
			}
	}
    function listNumbers(){
		$this->getObject();
		/* if($_REQUEST['zip']=='zip'){
		
		$SearchParams['InPostalCode'] = !empty($_REQUEST['zip'])? trim($_REQUEST['zip']) : '';
		
		}else if($_REQUEST['area']=='area'){
		$SearchParams['AreaCode'] = !empty($_REQUEST['area'])? trim($_REQUEST['area']) : '';
		
		}  */
		$SearchParams1['AreaCode'] = !empty($_REQUEST['area'])? trim($_REQUEST['area']) : '';
		$SearchParams1['Contains'] = !empty($_REQUEST['Contains'])? trim($_REQUEST['Contains']) : '';
		$SearchParams1['InPostalCode'] = !empty($_REQUEST['zip'])? trim($_REQUEST['zip']) : '';
		$country = !empty($_REQUEST['country'])? trim($_REQUEST['country']) : '';
			if($SearchParams1['AreaCode']=='' && $country!='' && $SearchParams1['InPostalCode']!=''){
		//echo 'test';
			$zipcode = !empty($_REQUEST['zip'])? trim($_REQUEST['zip']) : '';
			//$SearchParams['InPostalCode'] =str_replace(' ','',$zipcode);
			//$SearchParams['InPostalCode'] =urlencode($zipcode);
			$SearchParams['InPostalCode'] = !empty($_REQUEST['zip'])? trim($_REQUEST['zip']) : '';
			//$SearchParams['VoiceEnabled'] = $_REQUEST['Voice'];
			//$SearchParams['MmsEnabled'] = $_REQUEST['MMS'];
			//$SearchParams['SmsEnabled'] = $_REQUEST['SMS'];
			$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/AvailablePhoneNumbers/".$country."/".$_REQUEST['numbertype']."/Capabilities/".$_REQUEST['SMS']."/".$_REQUEST['MMS']."/".$_REQUEST['Voice']."","GET",$SearchParams);
			return $response;
			/*  echo "<pre>"; 
                print_r($response);						
			echo "</pre>";    */		  
			}else if($SearchParams1['AreaCode']!='' && $SearchParams1['Contains']==''  && $country!='' && $SearchParams1['InPostalCode']==''){
			//echo 'test1';
			//echo 'test1';
			$SearchParams['AreaCode'] = !empty($_REQUEST['area'])? trim($_REQUEST['area']) : '';
			//$SearchParams['VoiceEnabled'] = $_REQUEST['Voice'];
			//$SearchParams['MmsEnabled'] = $_REQUEST['MMS'];
			//$SearchParams['SmsEnabled'] = $_REQUEST['SMS'];
			/* echo '<pre>';
			print_r($SearchParams);
			print_r($_REQUEST['numbertype']);
			echo '</pre>'; */
			$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/AvailablePhoneNumbers/".$country."/".$_REQUEST['numbertype']."","GET",$SearchParams);
			return $response; 
			/* 	 echo "<pre>"; 		
				 print_r($response);
				echo "</pre>";  */
						
			}else if($SearchParams1['AreaCode']!='' && $SearchParams1['Contains']!='' && $country!='' && $SearchParams1['InPostalCode']==''){
				//echo 'test2';
				$SearchParams['AreaCode'] = !empty($_REQUEST['area'])? trim($_REQUEST['area']) : '';
				$SearchParams['Contains'] = !empty($_REQUEST['Contains'])? trim($_REQUEST['Contains']) : '';
				//$SearchParams['VoiceEnabled'] = $_REQUEST['Voice'];
				//$SearchParams['MmsEnabled'] = $_REQUEST['MMS'];
				//$SearchParams['SmsEnabled'] = $_REQUEST['SMS'];
				$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/AvailablePhoneNumbers/".$country."/".$_REQUEST['numbertype']."","GET",$SearchParams);
				return $response;	
			}else if($SearchParams1['AreaCode']=='' && $SearchParams1['Contains']!='' && $country!='' && $SearchParams1['InPostalCode']==''){
				$SearchParams['Contains'] = !empty($_REQUEST['Contains'])? trim($_REQUEST['Contains']) : '';
				//$SearchParams['VoiceEnabled'] = $_REQUEST['Voice'];
				//$SearchParams['MmsEnabled'] = $_REQUEST['MMS'];
				//$SearchParams['SmsEnabled'] = $_REQUEST['SMS'];
				$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/AvailablePhoneNumbers/".$country."/".$_REQUEST['numbertype']."","GET", $SearchParams);
				return $response;
			}else if($SearchParams1['AreaCode']=='' && $country!='' && $SearchParams1['InPostalCode']==''){
				//$SearchParams['VoiceEnabled'] = $_REQUEST['Voice'];
				//$SearchParams['MmsEnabled'] = $_REQUEST['MMS'];
				//$SearchParams['SmsEnabled'] = $_REQUEST['SMS'];
				//echo 'test3';
				$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/AvailablePhoneNumbers/".$country."/".$_REQUEST['numbertype']."", "GET");
				return $response;		  		  
			}else if($country!='US' || $country!='CA'){
				//echo 'test4';
				//$SearchParams['VoiceEnabled'] = $_REQUEST['Voice'];
				//$SearchParams['MmsEnabled'] = $_REQUEST['MMS'];
				//$SearchParams['SmsEnabled'] = $_REQUEST['SMS'];
				$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/AvailablePhoneNumbers/".$country."/".$_REQUEST['numbertype']."","GET");
				return $response;
			
			}
			//$SearchParams['InPostalCode'] = !empty($_REQUEST['area'])? trim($_REQUEST['area']) : '';
			// $SearchParams['NearNumber'] = !empty($_REQUEST['area'])? trim($_REQUEST['area']) : ''; 
			// $SearchParams['Contains'] = !empty($_REQUEST['area'])? trim($_REQUEST['area']) : '' ;
				//$SearchParams['InPostalCode'] = '412';
			/* Initiate US Local PhoneNumber search with $SearchParams list */
				//return $response;
    }
	function assignthisnumber($numbertoassign,$siteurl){
		$this->getObject();
		$PhoneNumber = $numbertoassign;	
			$vars['PhoneNumber']=$PhoneNumber;	
			$vars['VoiceUrl']=$siteurl.'/twilios/voice';
			$vars['SmsUrl']=$siteurl.'/twilios/sms';
		/* Purchase the selected PhoneNumber */
		$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/IncomingPhoneNumbers","POST",$vars);
		// pr($response);
		// die('buyed from twilio');
		if($response->IsError) {
			//echo "Error purchasing number: $response->ErrorMessage";
			return $response;
		}
		return $response;
	}
	function sendsms($tonumber,$from,$body){
		$this->getObject();
		$vars['From']=$from;
			if(strlen($tonumber) > 10){
				$tonumber=str_replace('+','',$tonumber);
				$to='+'.$tonumber;
			}else{
				$to='+'.$tonumber;
			}
			$vars['To']=$to;
			$vars['Body']=$body;	
			$vars['StatusCallback']=SITE_URL.'/twilios/callstatus';	
			// echo $this->AccountSid;
			// echo $this->AuthToken;
			$response =  $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/Messages", "POST",$vars);
			//$response =  $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/SMS/Messages", "POST",$vars);
			//$response =  $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/SMS/Messages", "POST",$vars);
		   if($response->IsError) {
				//echo "Error purchasing number: $response->ErrorMessage";
				return $response;
				//print $response->ErrorMessage;
			}
		   return $response;
	}
	function singlemms($tonumber,$from,$body,$mms_text){
		$this->getObject();
		$vars['From']=$from;
		if(strlen($tonumber) > 10){
			$tonumber=str_replace('+','',$tonumber);
			$to='+'.$tonumber;
		}else{
			$to='+1'.$tonumber;
		}	 
		if($mms_text!=''){
			$vars['Body']=$mms_text;
		}
		$vars['To']=$to;
		$vars['StatusCallback']=SITE_URL.'/twilios/callstatus';	
		$vars['MediaUrl']=$body;
		$response =  $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/Messages", "POST",$vars);
		//$response =  $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/SMS/Messages", "POST",$vars);
		//$response =  $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/SMS/Messages", "POST",$vars);
		if($response->IsError) {
			//echo "Error purchasing number: $response->ErrorMessage";
			return $response;
			//print $response->ErrorMessage;
		}  
		return $response;
	}
	function callNumberToPEOPLE($callnumber,$callerPhone,$group_id,$lastinsertID,$repeat,$language,$pause){
		$this->getObject();
		$vars['Caller']=$callerPhone;
		$vars['Called']=$callnumber;
		$vars['Method']='POST';
		$vars['IfMachine']='Continue';
		//$vars['IfMachine']='Hangup';
		$vars['Record']='true';
		$site_url = SITE_URL;
		$vars['StatusCallback']=$site_url.'/twilios/sendcallStatus/'.$lastinsertID;
		$vars['StatusCallbackMethod']='POST';
		//$vars['Url']= $site_url.'/twilios/peoplecallrecordscript/'.$group_id;
        $vars['Url']= $site_url.'/twilios/peoplecallrecordscript/'.$group_id.'/'.$repeat.'/'.$language.'/'.$pause;
				//HTTP/1.1
		$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/Calls", "POST",$vars);	
		return $response; 
	}
			
	function releasenumber($phone_sid){
		$this->getObject();
        $response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/IncomingPhoneNumbers/".$phone_sid."","DELETE");
            //return $arrReturn;
		return $response;
    }
	
 	function getstatus($smsid=null){
		$this->getObject();
		$response = $this->client->request("/".$this->ApiVersion."/Accounts/".$this->AccountSid."/Messages/".$smsid."");
		return $response;
	
	}
    
    
}
?>