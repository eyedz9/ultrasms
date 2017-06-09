<?php 
//session_start();
//vendor('phpcaptcha'.DS.'php-captcha.inc.php');\
App::import('Vendor','Nexmomessage',array('file'=>'nexmo/NexmoMessage.php'));
//$captcha=realpath(VENDORS . 'phpcaptcha').'/php-captcha.inc.php';
//include($captcha);

class NexmomessageComponent extends Object
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
		$this->Key = $this->Key;
		$this->Secret = $this->Secret;
		// Instantiate a new Nexmo Rest Client 
		if($this->Key!=''){
			$this->client = new NexmoMessage($this->Key, $this->Secret);
		}
	}
	function sendsms($tonumber,$from,$body){
		$this->getObject();
		//$vars['From']=$from;
		if(strlen($tonumber) > 10){
			$tonumber=str_replace('+','',$tonumber);
			 $to='+'.$tonumber;
		}else{
			$to='+'.$tonumber;
		}
		//$vars['Body']=$body;	
		//$vars['StatusCallback']=SITE_URL.'/twilios/callstatus';	
        $response = $this->client->sendText($to,$from,$body);
		//$this->smsmail($this->Session->read('User.id'));
		return $response;
	}

/*function smsmail($user_id=null){
		
	
	
	             app::import('Model','User');
		         $this->User = new User();
		         $usersmail = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));

				//echo  $usersmail['User']['low_sms_balances'];
	
	if($usersmail['User']['email_alert_credit_options']==0){
	
	
       if($usersmail['User']['sms_balance'] <= $usersmail['User']['low_sms_balances']){
	  
	   
	    if($usersmail['User']['sms_credit_balance_email_alerts']==0){
		 
			 $sitename=str_replace(' ','',SITENAME);
			              $username = $usersmail['User']['username'];
						$email = $usersmail['User']['email'];
						//echo $phone = $usersmail['User']['assigned_number'];
						 
					   
						$subject="Low SMS Credit Balance";	
						
						$this->Email->to = $email;	
						$this->Email->subject = $subject;
						$this->Email->from = $sitename;
						$this->Email->template = 'low_sms_credit_template';
						$this->Email->sendAs = 'html';
						
						$this->Email->Controller->set('username', $username);
					    $this->Email->Controller->set('low_sms_balances', $usersmail['User']['low_sms_balances']);
						
						$this->Email->send();
						
						$this->User->id = $usersmail['User']['id'];
						$this->User->saveField('sms_credit_balance_email_alerts',1);
					}	
						
			
		       }
			
			}
			
			
	
	}*/
	
 
    
    
}
?>