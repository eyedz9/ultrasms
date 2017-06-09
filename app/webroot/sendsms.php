#!/usr/local/bin/php -q
<?php
$server_root = str_replace('/sendsms.php','',$_SERVER['SCRIPT_FILENAME']);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
ini_set('display_startup_errors',0);
ini_set('display_errors',0);
include($server_root.'/database.php');
include($server_root.'/nexmo.php');
include($server_root.'/slooce.php');
include($server_root.'/twiliocomponent.php');
include($server_root.'/plivo.php');
include($server_root.'/class.email.php');
$email = email::parseSTDIN();
$config_settings = mysql_query("SELECT * FROM configs");  
$config_details = mysql_fetch_array($config_settings);

	if($config_details['api_type']==0){
		$obj = new TwilioComponent();
		$subject = $email->getSubject();
		$message = $email->getTextContent();
		$ticket_id = explode('-',$subject);
		//$str = mb_convert_case($message, MB_CASE_UPPER, "UTF-8");
		//$message_receive = explode('END',$str);
		//$message_change = mb_strtolower($message_receive[0]);
		$message_new =ucfirst($message);
		$ticketdetails = mysql_query("SELECT * FROM logs where ticket='".$ticket_id[1]."'");  
		$ticket_arr = mysql_fetch_array($ticketdetails);
		
		if(isset($ticket_arr['id'])){
			//$user_details = mysql_query("SELECT * FROM users where id='".$ticket_arr['user_id']."'");  
			//$users = mysql_fetch_array($user_details);
			//$from = $users['assigned_number']; 		
			//$response = $obj->sendsms($ticket_arr['phone_number'],$from,$message_new);

                        $length = strlen(utf8_decode(substr($message_new,0,1600))); 
			$credits = ceil($length/160);
			
			$user_sms_balance = mysql_query("SELECT sms_balance FROM users where id='".$ticket_arr['user_id']."'");  
			$usercredits = mysql_fetch_array($user_sms_balance);
			$usercreditbalance = $usercredits['sms_balance'] - $credits;

                        if($usercredits['sms_balance'] < $credits){
                           $message = "You do not have enough credits to respond to this email by SMS. You need ".$credits." credits to send this email to SMS message. Either send a shorter message or replenish your account with more credits.";
                           $response = $obj->sendsms($ticket_arr['email_to_sms_number'],$ticket_arr['email_to_sms_number'],$message);
                           exit;
                        }
			
			mysql_query("UPDATE users set sms_balance = '".$usercreditbalance."' where id='".$ticket_arr['user_id']."'"); 

                        $response = $obj->sendsms($ticket_arr['phone_number'],$ticket_arr['email_to_sms_number'],$message_new);
			$smsid=$response->ResponseXml->Message->Sid;
			if($smsid!=''){
				$status = 'delivered';
			}else{
				$status = 'failed';
                		$errortext=$response->ResponseXml->RestException->Message;
			}
			mysql_query("insert into logs(group_sms_id,sms_id,ticket,user_id,group_id,phone_number,text_message,image_url,voice_url,route,msg_type,sms_status,error_message,  created)values('0','".$smsid."','".$ticket_id[1]."',".$ticket_arr['user_id'].",'0','".$ticket_arr['phone_number']."','".$message_new."','','','outbox','text','".$status."','".$errortext."','".date('Y-m-d H:i:s')."')");	
			
			
			
		}
	}else if($config_details['api_type']==2){
		$obj = new SlooceComponent();
		$subject = $email->getSubject();
		$message = $email->getTextContent();
		$ticket_id = explode('-',$subject);
		$message_new =ucfirst($message);
		$ticketdetails = mysql_query("SELECT * FROM logs where ticket='".$ticket_id[1]."'");  
		$ticket_arr = mysql_fetch_array($ticketdetails);
		if(isset($ticket_arr['id'])){

            //$length = strlen(utf8_decode(substr($message_new,0,1600))); 
			//$credits = ceil($length/160);
			$credits = 1;
			
			$user_sms_balance = mysql_query("SELECT * FROM users where id='".$ticket_arr['user_id']."'");  
			$usercredits = mysql_fetch_array($user_sms_balance);
			$usercreditbalance = $usercredits['sms_balance'] - $credits;

            if($usercredits['sms_balance'] < $credits){
                $message = "You do not have enough credits to respond to this email by SMS. You need ".$credits." credits to send this email to SMS message.";
                           
				mysql_query("insert into logs(group_sms_id,sms_id,ticket,user_id,group_id,phone_number,text_message,image_url,voice_url,route,msg_type,sms_status,error_message,  created)values('0','','".$ticket_id[1]."',".$ticket_arr['user_id'].",'0','".$ticket_arr['email_to_sms_number']."','".$message."','','','inbox','text','received','','".date('Y-m-d H:i:s')."')");	
                           
				exit;
            }
			
			mysql_query("UPDATE users set sms_balance = '".$usercreditbalance."' where id='".$ticket_arr['user_id']."'"); 

            $response = $obj->mt($usercredits['api_url'],$usercredits['partnerid'],$usercredits['partnerpassword'],$ticket_arr['phone_number'],$usercredits['keyword'],$message_new);
			$message_id = '';
			$status = '';
			if(isset($response['id'])){
				if($response['result']=='ok'){
					$message_id = $response['id'];
				}
				$status = $response['result'];
			}
			if($status !='ok'){
				$status = 'failed';
			}else{
				$status = 'sent';
			}
			mysql_query("insert into logs(group_sms_id,sms_id,ticket,user_id,group_id,phone_number,text_message,image_url,voice_url,route,msg_type,sms_status,error_message,  created)values('0','".$message_id."','".$ticket_id[1]."',".$ticket_arr['user_id'].",'0','".$ticket_arr['phone_number']."','".$message_new."','','','outbox','text','".$status."','".$status."','".date('Y-m-d H:i:s')."')");	
			
				
		}
	}else if($config_details['api_type']==1){
		$obj = new NexmoComponent();
		$subject = $email->getSubject();
		$message = $email->getTextContent();
		$ticket_id = explode('-',$subject);
		//$str = mb_convert_case($message, MB_CASE_UPPER, "UTF-8");
		//$message_receive = explode('END',$str);
		//$message_change = mb_strtolower($message_receive[0]);
		$message_new =ucfirst($message);
		$ticketdetails = mysql_query("SELECT * FROM logs where ticket='".$ticket_id[1]."'");  
		$ticket_arr = mysql_fetch_array($ticketdetails);
		if(isset($ticket_arr['id'])){
			//$user_details = mysql_query("SELECT * FROM users where id='".$ticket_arr['user_id']."'");  
			//$users = mysql_fetch_array($user_details);
			//$from = $users['assigned_number'];  
			//$response = $obj->sendsms($ticket_arr['phone_number'],$from,$message_new);

                        $length = strlen(utf8_decode(substr($message_new,0,1600))); 
			$credits = ceil($length/160);
			
			$user_sms_balance = mysql_query("SELECT sms_balance FROM users where id='".$ticket_arr['user_id']."'");  
			$usercredits = mysql_fetch_array($user_sms_balance);
			$usercreditbalance = $usercredits['sms_balance'] - $credits;

                        if($usercredits['sms_balance'] < $credits){
                           $message = "You do not have enough credits to respond to this email by SMS. You need ".$credits." credits to send this email to SMS message. Either send a shorter message or replenish your account with more credits.";
                           
            	   
			mysql_query("insert into logs(group_sms_id,sms_id,ticket,user_id,group_id,phone_number,text_message,image_url,voice_url,route,msg_type,sms_status,error_message,  created)values('0','','".$ticket_id[1]."',".$ticket_arr['user_id'].",'0','".$ticket_arr['email_to_sms_number']."','".$message."','','','inbox','text','received','','".date('Y-m-d H:i:s')."')");	
                           
                           exit;
                        }
			
			mysql_query("UPDATE users set sms_balance = '".$usercreditbalance."' where id='".$ticket_arr['user_id']."'"); 

                        $response = $obj->sendsms($ticket_arr['phone_number'],$ticket_arr['email_to_sms_number'],$message_new);
			foreach($response->messages  as $doc){
				$message_id= $doc->messageid; 
				if($message_id!=''){
					$status= $doc->status;	  
					$message_id= $doc->messageid;	  
				}else{
					$status= $doc->status;
					$errortext= $doc->errortext; 
				}			  
			}
			if($status!=0){
				$status = 'failed';
			}else{
				$status = 'sent';
			}
			mysql_query("insert into logs(group_sms_id,sms_id,ticket,user_id,group_id,phone_number,text_message,image_url,voice_url,route,msg_type,sms_status,error_message,  created)values('0','".$message_id."','".$ticket_id[1]."',".$ticket_arr['user_id'].",'0','".$ticket_arr['phone_number']."','".$message_new."','','','outbox','text','".$status."','".$errortext."','".date('Y-m-d H:i:s')."')");	
			
				
		}
	}else if ($config_details['api_type']==3){
		$obj = new PlivoComponent();
		$subject = $email->getSubject();
		$message = $email->getTextContent();
		$ticket_id = explode('-',$subject);
		//$str = mb_convert_case($message, MB_CASE_UPPER, "UTF-8");
		//$message_receive = explode('END',$str);
		//$message_change = mb_strtolower($message_receive[0]);
		$message_new =ucfirst($message);
		$ticketdetails = mysql_query("SELECT * FROM logs where ticket='".$ticket_id[1]."'");  
		$ticket_arr = mysql_fetch_array($ticketdetails);
		if(isset($ticket_arr['id'])){
			//$user_details = mysql_query("SELECT * FROM users where id='".$ticket_arr['user_id']."'");  
			//$users = mysql_fetch_array($user_details);
			//$from = $users['assigned_number'];  
			//$response = $obj->sendsms($ticket_arr['phone_number'],$from,$message_new);

                        $length = strlen(utf8_decode(substr($message_new,0,1600))); 
			//$credits = ceil($length/160);
                        if (strlen($message_new) != strlen(utf8_decode($message_new))){
		            $credits = ceil($length/70);
                        }else{
                            $credits = ceil($length/160);
                        }
			
			$user_sms_balance = mysql_query("SELECT sms_balance FROM users where id='".$ticket_arr['user_id']."'");  
			$usercredits = mysql_fetch_array($user_sms_balance);
			$usercreditbalance = $usercredits['sms_balance'] - $credits;

                        if($usercredits['sms_balance'] < $credits){
                           $message = "You do not have enough credits to respond to this email by SMS. You need ".$credits." credits to send this email to SMS message. Either send a shorter message or replenish your account with more credits.";
                           
            	   
			mysql_query("insert into logs(group_sms_id,sms_id,ticket,user_id,group_id,phone_number,text_message,image_url,voice_url,route,msg_type,sms_status,error_message,  created)values('0','','".$ticket_id[1]."',".$ticket_arr['user_id'].",'0','".$ticket_arr['email_to_sms_number']."','".$message."','','','inbox','text','received','','".date('Y-m-d H:i:s')."')");	
                           
                           exit;
                        }
			
			mysql_query("UPDATE users set sms_balance = '".$usercreditbalance."' where id='".$ticket_arr['user_id']."'"); 

                        $response = $obj->sendsms($ticket_arr['phone_number'],$ticket_arr['email_to_sms_number'],$message_new);

                        $errortext = '';
			$message_id = '';
			if(isset($response['response']['error'])){
				$errortext = $response['response']['error'];
                                $status = 'failed';
			}
			if(isset($response['response']['message_uuid'][0])){
				$message_id = $response['response']['message_uuid'][0];
                                $status = 'sent';
			}
			
			mysql_query("insert into logs(group_sms_id,sms_id,ticket,user_id,group_id,phone_number,text_message,image_url,voice_url,route,msg_type,sms_status,error_message,  created)values('0','".$message_id."','".$ticket_id[1]."',".$ticket_arr['user_id'].",'0','".$ticket_arr['phone_number']."','".$message_new."','','','outbox','text','".$status."','".$errortext."','".date('Y-m-d H:i:s')."')");	
			
				
		}
}
	ob_start();
	echo "<pre>";
	print_r($email);
	print_r($ticketdetails);
	echo "</pre>";
	$out1 = ob_get_contents();
	ob_end_clean();
	$file = fopen($server_root."/debug/maildata".time().".txt", "w");
	fwrite($file, $out1); 
	fclose($file); 
	die();