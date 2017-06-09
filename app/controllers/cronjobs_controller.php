<?php
class CronjobsController extends AppController {
	var $components = array('Email','Twilio','Mms','Nexmomessage','Slooce','Plivo');
	var $uses  = array('User','Config','Log');
	var $userId = 0;
	function sendmessage(){
		$this->autoRender = false;
		$this->send_message();
		sleep(1);
		$this->send_single_message();
		sleep(1);
		$this->updateclicks(1);
	}

	function send_single_message(){
		$this->autoRender = false;
		app::import('Model','ScheduleMessageGroup');
		$this->ScheduleMessageGroup = new ScheduleMessageGroup();
		app::import('Model','ScheduleMessage');
		$this->ScheduleMessage = new ScheduleMessage();
		app::import('Model','Log');
		$this->Log = new Log();
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		app::import('Model','User');
		$this->User = new User();
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		app::import('Model','SingleScheduleMessage');
		$this->SingleScheduleMessage = new SingleScheduleMessage();
	        $schedule_arr = $this->ScheduleMessage->find('all', array('fields' => array('DISTINCT ScheduleMessage.user_id')));

		foreach($schedule_arr as $users){
			$Uid=$users['ScheduleMessage']['user_id'];
			$user_arr=$this->User->find('first', array('conditions' => array('User.id'=>$Uid)));
			$active = $user_arr['User']['active'];
			if($Uid!='' && $active == 1){
				$timezone=$user_arr['User']['timezone'];
				date_default_timezone_set($timezone);
				$date = date('Y-m-d H:i:s');
				$SingleScheduleMessage = $this->SingleScheduleMessage->find('all', array('conditions' => array('ScheduleMessage.sent'=>0,'ScheduleMessage.send_on <= '=>$date,'ScheduleMessage.user_id'=>$Uid)));
				if(API_TYPE==0){
					if(!empty($SingleScheduleMessage)){
						$k = 0;
						foreach($SingleScheduleMessage as $SingleScheduleMessages){   
							$contact_id = $SingleScheduleMessages['SingleScheduleMessage']['contact_id'];
							$user_id = $SingleScheduleMessages['ScheduleMessage']['user_id'];
							$message = $SingleScheduleMessages['ScheduleMessage']['message'];
							$systemmsg = $SingleScheduleMessages['ScheduleMessage']['systemmsg'];
							$mms_text = $SingleScheduleMessages['ScheduleMessage']['mms_text'].' '.$systemmsg;
							$rotate_number = $SingleScheduleMessages['ScheduleMessage']['rotate_number'];
                                                        $throttle = $SingleScheduleMessages['ScheduleMessage']['throttle'];
                                                        $alphasenderid = $SingleScheduleMessages['ScheduleMessage']['alphasender_input'];
							if(isset($contact_id)){
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.un_subscribers'=>0))); 
								$totalSubscriber = count($schedulemessages1);
								$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if($totalSubscriber > 0){
									if($rotate_number==1){
										$subscriberPhone1 = '';
										app::import('Model','UserNumber');
										$this->UserNumber = new UserNumber();
										$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
										$from_arr=array();
										if(!empty($users)){	
											if($users['User']['sms']==1){
												$from_arr[]=$users['User']['assigned_number'];
											}
										}
										if(!empty($user_numbers)){		
											foreach($user_numbers as $values){
												if($values['UserNumber']['sms']==1){
													$from_arr[]=$values['UserNumber']['number'];
												}
											}
										}
										$mms_arr=array();
										if(!empty($users)){		
											if($users['User']['mms']==1){
												$mms_arr[]=$users['User']['assigned_number'];
											} 
										}
										if(!empty($user_numbers)){	
											foreach($user_numbers as $values){
												if($values['UserNumber']['mms']==1){
													$mms_arr[]=$values['UserNumber']['number'];
												}				
											}
										}
										foreach($schedulemessages1 as $schedulemessages){
											if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
												$sms_balance=$users['User']['sms_balance'];
                                                $body = $message." ".$systemmsg;
												$spinbody = $this->process($body);
												$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
												if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){ 
													$contactcredits = 2; 
												}else{ 
													if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
														$contactcredits = ceil($length/70);
													}else{
														$contactcredits = ceil($length/160);
													}
												}
												if($sms_balance >= ($totalSubscriber * $contactcredits)){
													$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
													$to = $schedulemessages['Contact']['phone_number'];
													$space_pos = strpos($schedulemessages['Contact']['name'],' ');
													if($space_pos!=''){
														$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);	
													}else{
														$contact_name = $schedulemessages['Contact']['name'];	
													}
													if($SingleScheduleMessages['ScheduleMessage']['msg_type']==1){
														$message2 =str_replace('%%Name%%',$contact_name,$message);
													}else if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){
														$message_arr=explode(',',$message);	
														$message2=$message_arr;	
													}
													$body='';
													if($SingleScheduleMessages['ScheduleMessage']['msg_type']==1){
														//$random_keys= array_rand($from_arr,1);
														if ($throttle > 1){
														   $countnumber = count($from_arr);
														   if($countnumber==$k){
																$k = 0;
																sleep($throttle);
														   }
														}
                                                        $from=$from_arr[$k];
														//$from=$from_arr[$random_keys];
														$body = $message2." ".$systemmsg;
														$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
														$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
														$spinbody = $this->process($body);
														$response = $this->Twilio->sendsms($to,$from,$spinbody);
														$msg_type='text';
														$smsid=$response->ResponseXml->Message->Sid;
														$Status=$response->ResponseXml->RestException->Status;
													}else if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){
														//$random_keys= array_rand($mms_arr,1);
														if ($throttle > 1){
															$countnumber = count($mms_arr);
														   if($countnumber==$k){
																$k = 0;
																sleep($throttle);
														   }
														}
                                                        $from=$mms_arr[$k];
														//$from=$mms_arr[$random_keys];
														$body = $message2;
														$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
														$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
														$spinmms_text = $this->process($mms_text);
														$response = $this->Mms->sendmms($to,$from,$body,$spinmms_text);
														$msg_type='text';
														$smsid=$response->sid;
														if($smsid==''){
															$ErrorMessage = $response;
															$Status='400';
														}
													}
													$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
													$this->ScheduleMessage->id = $scheduleId;
													$this->data['ScheduleMessage']['sent'] = 1;
													$this->ScheduleMessage->save($this->data);
													$this->Log = new Log();
													$logArr_single['sms_id'] = (string)$smsid;
													$logArr_single['user_id'] = $users['User']['id'];
													$logArr_single['phone_number'] =$schedulemessages['Contact']['phone_number'];
													if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){
														$logArr_single['image_url'] = $message;
														$logArr_single['text_message'] = $spinmms_text;
													}else{
														$logArr_single['text_message'] = $spinbody;	
													}		
													$logArr_single['msg_type'] = $msg_type;
													$logArr_single['route'] = 'outbox';
													$logArr_single['sms_status']= '';
													$logArr_single['error_message']='';
					
													if($Status==400){
														$logArr_single['sms_status']= 'failed';
														if(isset($response->ErrorMessage)){
															$ErrorMessage = $response->ErrorMessage;
														}else{
															$ErrorMessage = $ErrorMessage;
														}	
														$logArr_single['error_message']=$ErrorMessage;
													}
													$this->Log->save($logArr_single);
                                                    $k = $k + 1;
												}

											}

										}
					
									}else{
										$subscriberPhone1 = '';
										if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){
											$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.mms'=>1)));
                                                                                if($alphasenderid != ''){
                                                                                   $assigned_number = $alphasenderid;
                                                                                }else{
											if(empty($usernumber)){
												$mmsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
												if(!empty($mmsnumber)){
													$assigned_number = $mmsnumber['UserNumber']['number'];
												}else{
													$assigned_number = $users['User']['assigned_number'];
												}
											}else{
												$assigned_number = $usernumber['User']['assigned_number'];
											}
                                                                                    }
										}else{
											$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));

                                                                                if($alphasenderid != ''){
                                                                                   $assigned_number = $alphasenderid;
                                                                                }else{
											if(!empty($usernumber)){
												if($usernumber['User']['sms']==1){
												$assigned_number=$usernumber['User']['assigned_number'];
												}else{
												$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
													if(!empty($user_numbers)){	
														$assigned_number=$user_numbers['UserNumber']['number'];
													}else{
														$assigned_number=$usernumber['User']['assigned_number'];
													}
												}
											}
                                                                                    }
										}
										$body='';
										foreach($schedulemessages1 as $schedulemessages){
											if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													$body = $message." ".$systemmsg;
													$spinbody = $this->process($body);
													$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
													if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){ 
														$contactcredits = 2; 
													}else{ 
														if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
															$contactcredits = ceil($length/70);
														}else{
															$contactcredits = ceil($length/160);
														}
													}
													if($sms_balance >= ($totalSubscriber * $contactcredits)){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														$from = $assigned_number;
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
														if($SingleScheduleMessages['ScheduleMessage']['msg_type']==1){
															$message2 =str_replace('%%Name%%',$contact_name,$message);
														}else if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){
															$message_arr=explode(',',$message);
															$message2=$message_arr;
														}
														$body = $message2;
														$smsid='';
														if ($throttle > 1) {
															sleep($throttle);
                                                        }
														if($SingleScheduleMessages['ScheduleMessage']['msg_type']==1){
															$body = $message2." ".$systemmsg;
															$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
															$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
															$spinbody = $this->process($body);
															$response = $this->Twilio->sendsms($to,$from,$spinbody);
															$msg_type='text';
															$smsid=$response->ResponseXml->Message->Sid;
															$Status=$response->ResponseXml->RestException->Status;
						
														}else if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){
															$body = $message2;
															$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
															$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
															$spinmms_text = $this->process($mms_text);
															$response = $this->Mms->sendmms($to,$from,$body,$spinmms_text);
															$msg_type='text';
															$smsid=$response->sid;
															if($smsid==''){
																$ErrorMessage = $response;
																$Status='400';
															}
						
														}
														$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
														$this->ScheduleMessage->id = $scheduleId;
														$this->data['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($this->data);
														$this->Log = new Log();
														$logArr_single['sms_id'] = (string)$smsid;
														$logArr_single['user_id'] = $users['User']['id'];
														$logArr_single['phone_number'] =$schedulemessages['Contact']['phone_number'];
														if($SingleScheduleMessages['ScheduleMessage']['msg_type']==2){
															$logArr_single['image_url'] = $message;
															$logArr_single['text_message'] = $spinmms_text;
														}else{
															$logArr_single['text_message'] = $spinbody;
														}
														$logArr_single['msg_type'] = $msg_type;
														$logArr_single['route'] = 'outbox';
														$logArr_single['sms_status']= '';														$logArr_single['error_message']='';
														if($Status==400){
														$logArr_single['sms_status']= 'failed';													
															if(isset($response->ErrorMessage)){
																$ErrorMessage = $response->ErrorMessage;
															}else{
																$ErrorMessage = $ErrorMessage;
															}	
														$logArr_single['error_message']=$ErrorMessage;

														}
													$this->Log->save($logArr_single);
												}
											}
										}
									}
								}
							}
						}
					}
				}else if(API_TYPE==2){
					if(!empty($SingleScheduleMessage)){
						foreach($SingleScheduleMessage as $SingleScheduleMessages){
							$contact_id = $SingleScheduleMessages['SingleScheduleMessage']['contact_id'];
							$user_id = $SingleScheduleMessages['ScheduleMessage']['user_id'];
							$message = $SingleScheduleMessages['ScheduleMessage']['message'];
							$systemmsg = $SingleScheduleMessages['ScheduleMessage']['systemmsg'];
							$rotate_number = $SingleScheduleMessages['ScheduleMessage']['rotate_number'];
							if($rotate_number==1){
								if(isset($contact_id)){
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.un_subscribers'=>0))); 
									$totalSubscriber = count($schedulemessages1);
									$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
									$from_arr=array();
									if(!empty($users)){	
										if($users['User']['sms']==1){
											$from_arr[]=$users['User']['assigned_number'];
										}
									}
									if(!empty($user_numbers)){	
										foreach($user_numbers as $values){	
											if($values['UserNumber']['sms']==1){
												$from_arr[]=$values['UserNumber']['number'];
											}				
										}
									}
									if($totalSubscriber > 0){
										$k = 0;
										$sucesscredits = 0;
										$credits = 0;
										foreach($schedulemessages1 as $schedulemessages){
											if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
												$sms_balance=$users['User']['sms_balance'];
												if($sms_balance > $totalSubscriber){
													$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
													$to = $schedulemessages['Contact']['phone_number'];
													$countnumber = count($from_arr);
													if($countnumber==$k){
														$k = 0;
													}
													$from = $from_arr[$k];
													$space_pos = strpos($schedulemessages['Contact']['name'],' ');
													if($space_pos!=''){	
														$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $schedulemessages['Contact']['name'];
													}
													$message2 =str_replace('%%Name%%',$contact_name,$message);
													$body = $message2." ".$systemmsg;
													
													$spinbody = $this->process($body);
													$response = $this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$spinbody);
													$message_id = '';
													$status = '';
													if(isset($response['id'])){
														if($response['result']=='ok'){
															$message_id = $response['id'];
														}
														$status = $response['result'];
													}
													if(!empty($SingleScheduleMessages['ScheduleMessage']['id'])){
														$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
														$ScheduleMessage_save_arr['ScheduleMessage']['id'] = $scheduleId;
														$ScheduleMessage_save_arr['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($ScheduleMessage_save_arr);
													}
													$this->Log = new Log();
													$logArr['sms_id'] = $message_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
													$logArr['text_message'] = $spinbody;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if($status!='ok'){
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $status;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$sucesscredits = $sucesscredits + 1;
														$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
														$credits = $credits + ceil($length/160);
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
												}
											}
											$k = $k + 1;
										}
										if($sucesscredits > 0){
											app::import('Model','User');
											$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
											if(!empty($usersms)){
												$user_credit['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$user_credit['User']['id']=$usersms['User']['id'];
												$this->User->save($user_credit);
											}
										}
										app::import('Model','User');
										$this->User = new User();
										$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
										if($users['User']['email_alert_credit_options']==0){
											if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
												if($users['User']['sms_credit_balance_email_alerts']==0){
													$username = $users['User']['username'];
													$email = $users['User']['email'];
													$phone = $users['User']['assigned_number'];
													$subject="Low SMS Credit Balance";	
													$sitename=str_replace(' ','',SITENAME);
													$this->Email->to = $email;	
													$this->Email->subject = $subject;
													$this->Email->from = $sitename;
													$this->Email->template = 'low_sms_credit_template';
													$this->Email->sendAs = 'html';
													$this->Email->Controller->set('username', $username);
													$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
													$this->Email->send();
													$this->User->id = $users['User']['id'];
													$this->User->saveField('sms_credit_balance_email_alerts',1);
												}	
											}
										}
									}
								}
							}else{
									if(isset($contact_id)){
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.un_subscribers'=>0))); 
										$totalSubscriber = count($schedulemessages1);
										$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										if(!empty($users)){
											if($users['User']['sms']==1){
												$assigned_number=$users['User']['assigned_number'];
											}else{
												$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
												if(!empty($user_numbers)){	
													$assigned_number=$user_numbers['UserNumber']['number'];
												}else{
													$assigned_number=$users['User']['assigned_number'];
												}
											}
										}
										if($totalSubscriber > 0){
											$sucesscredits = 0;
											$credits = 0;
											foreach($schedulemessages1 as $schedulemessages){
												if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													if($sms_balance > $totalSubscriber){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														$from = $assigned_number;
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){	
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
														$message2 =str_replace('%%Name%%',$contact_name,$message);
														$body = $message2." ".$systemmsg;
														$spinbody = $this->process($body);
														$response = $this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$spinbody);
														$message_id = '';
														$status = '';
														if(isset($response['id'])){
															if($response['result']=='ok'){
																$message_id = $response['id'];
															}
															$status = $response['result'];
														}
														if(!empty($SingleScheduleMessages['ScheduleMessage']['id'])){
															$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
															$ScheduleMessage_arra['ScheduleMessage']['id'] = $scheduleId;
															$ScheduleMessage_arra['ScheduleMessage']['sent'] = 1;
															$this->ScheduleMessage->save($ScheduleMessage_arra);
														}
														$this->Log = new Log();
														$logArr['sms_id'] = $message_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
														$logArr['text_message'] = $spinbody;
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if($status!='ok'){
															$logArr['sms_status']= 'failed';
															$ErrorMessage = $status;
															$logArr['error_message']=$ErrorMessage;
														}
														if($message_id!=''){
															$sucesscredits = $sucesscredits + 1;
															$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
															$credits = $credits + ceil($length/160);
															$logArr['sms_status']= 'sent';
														}
														$this->Log->save($logArr);
													}
												}
											}
											if($sucesscredits > 0){
												app::import('Model','User');
												$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
												if(!empty($usersms)){
													$user_update['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
													$user_update['User']['id']=$usersms['User']['id'];
													$this->User->save($user_update);
												}
											}
											app::import('Model','User');
											$this->User = new User();
											$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
											if($users['User']['email_alert_credit_options']==0){
												if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
													if($users['User']['sms_credit_balance_email_alerts']==0){
														$username = $users['User']['username'];
														$email = $users['User']['email'];
														$phone = $users['User']['assigned_number'];
														$subject="Low SMS Credit Balance";	
														$sitename=str_replace(' ','',SITENAME);
														$this->Email->to = $email;	
														$this->Email->subject = $subject;
														$this->Email->from = $sitename;
														$this->Email->template = 'low_sms_credit_template';
														$this->Email->sendAs = 'html';
														$this->Email->Controller->set('username', $username);
														$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
														$this->Email->send();
														$this->User->id = $users['User']['id'];
														$this->User->saveField('sms_credit_balance_email_alerts',1);
													}	
												}
											}
										}
									}
								}
							}	
						}
				}else if(API_TYPE==3){
					if(!empty($SingleScheduleMessage)){
						foreach($SingleScheduleMessage as $SingleScheduleMessages){
							$contact_id = $SingleScheduleMessages['SingleScheduleMessage']['contact_id'];
							$user_id = $SingleScheduleMessages['ScheduleMessage']['user_id'];
							$message = $SingleScheduleMessages['ScheduleMessage']['message'];
							$systemmsg = $SingleScheduleMessages['ScheduleMessage']['systemmsg'];
							$rotate_number = $SingleScheduleMessages['ScheduleMessage']['rotate_number'];
                                                        $throttle = $SingleScheduleMessages['ScheduleMessage']['throttle'];
                                                        $alphasenderid = $SingleScheduleMessages['ScheduleMessage']['alphasender_input'];
							if($rotate_number==1){
								if(isset($contact_id)){
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.un_subscribers'=>0))); 
									$totalSubscriber = count($schedulemessages1);
									$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
									$from_arr=array();
									if(!empty($users)){	
										if($users['User']['sms']==1){
											$from_arr[]=$users['User']['assigned_number'];
										}
									}
									if(!empty($user_numbers)){	
										foreach($user_numbers as $values){	
											if($values['UserNumber']['sms']==1){
												$from_arr[]=$values['UserNumber']['number'];
											}				
										}
									}
									if($totalSubscriber > 0){
										$k = 0;
										$sucesscredits = 0;
										$credits = 0;
										foreach($schedulemessages1 as $schedulemessages){
											if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
												$sms_balance=$users['User']['sms_balance'];
												$body = $message." ".$systemmsg;
												$spinbody = $this->process($body);    
												$length = strlen(utf8_decode(substr($spinbody,0,1600)));
												if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
													$contactcredits = ceil($length/70);
												}else{
													$contactcredits = ceil($length/160);
												}
												if($sms_balance >= ($totalSubscriber * $contactcredits)){
													$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
													$to = $schedulemessages['Contact']['phone_number'];
													$countnumber = count($from_arr);
													if($countnumber==$k){
														$k = 0;
														sleep($throttle);
													}
													$from = $from_arr[$k];
													$space_pos = strpos($schedulemessages['Contact']['name'],' ');
													if($space_pos!=''){	
														$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $schedulemessages['Contact']['name'];
													}
													$message2 =str_replace('%%Name%%',$contact_name,$message);
													$body = $message2." ".$systemmsg;
													$this->Plivo->AuthId =PLIVO_KEY;
													$this->Plivo->AuthToken =PLIVO_TOKEN;
                                                    $spinbody = $this->process($body);
													$response = $this->Plivo->sendsms($to,$from,$spinbody);
													$errortext = '';
													$message_id = '';
													if(isset($response['response']['error'])){
														$errortext = $response['response']['error'];
													}
													if(isset($response['response']['message_uuid'][0])){
														$message_id = $response['response']['message_uuid'][0];
													}
				
													if(!empty($SingleScheduleMessages['ScheduleMessage']['id'])){
														$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
														$ScheduleMessage_save_arr['ScheduleMessage']['id'] = $scheduleId;
														$ScheduleMessage_save_arr['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($ScheduleMessage_save_arr);
													}
													$this->Log = new Log();
													$logArr['sms_id'] = $message_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
													$logArr['text_message'] = $spinbody;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if(isset($response['response']['error'])){
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $errortext;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$sucesscredits = $sucesscredits + 1;
														$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
														//$credits = $credits + ceil($length/160);
														if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
															$credits = $credits + ceil($length/70);
														}else{
															$credits = $credits + ceil($length/160);
														}
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
												}
											}
											$k = $k + 1;
										}
										if($sucesscredits > 0){
											app::import('Model','User');
											$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
											if(!empty($usersms)){
												$user_credit['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$user_credit['User']['id']=$usersms['User']['id'];
												$this->User->save($user_credit);
											}
										}
										app::import('Model','User');
										$this->User = new User();
										$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
										if($users['User']['email_alert_credit_options']==0){
											if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
												if($users['User']['sms_credit_balance_email_alerts']==0){
													$username = $users['User']['username'];
													$email = $users['User']['email'];
													$phone = $users['User']['assigned_number'];
													$subject="Low SMS Credit Balance";	
													$sitename=str_replace(' ','',SITENAME);
													$this->Email->to = $email;	
													$this->Email->subject = $subject;
													$this->Email->from = $sitename;
													$this->Email->template = 'low_sms_credit_template';
													$this->Email->sendAs = 'html';
													$this->Email->Controller->set('username', $username);
													$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
													$this->Email->send();
													$this->User->id = $users['User']['id'];
													$this->User->saveField('sms_credit_balance_email_alerts',1);
												}	
											}
										}
									}
								}
							}else{
								if(isset($contact_id)){
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.un_subscribers'=>0))); 
									$totalSubscriber = count($schedulemessages1);
									$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
									if(!empty($users)){
                                                                            if($alphasenderid != ''){
                                                                                   $assigned_number = $alphasenderid;
                                                                            }else{
										if($users['User']['sms']==1){
											$assigned_number=$users['User']['assigned_number'];
										}else{
											$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
											if(!empty($user_numbers)){	
												$assigned_number=$user_numbers['UserNumber']['number'];
											}else{
												$assigned_number=$users['User']['assigned_number'];
											}
										}
                                                                             }
									}
									if($totalSubscriber > 0){
										$sucesscredits = 0;
										$credits = 0;
										foreach($schedulemessages1 as $schedulemessages){
											if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
												$sms_balance=$users['User']['sms_balance'];
												$body = $message." ".$systemmsg;
												$spinbody = $this->process($body); 
												$length = strlen(utf8_decode(substr($spinbody,0,1600)));
												if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
													$contactcredits = ceil($length/70);
												}else{
													$contactcredits = ceil($length/160);
												}
												if($sms_balance >= ($totalSubscriber * $contactcredits)){
													$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
													$to = $schedulemessages['Contact']['phone_number'];
													$from = $assigned_number;
													$space_pos = strpos($schedulemessages['Contact']['name'],' ');
													if($space_pos!=''){	
														$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $schedulemessages['Contact']['name'];
													}
													$message2 =str_replace('%%Name%%',$contact_name,$message);
													$body = $message2." ".$systemmsg;
													$this->Plivo->AuthId =PLIVO_KEY;
													$this->Plivo->AuthToken =PLIVO_TOKEN;
													sleep($throttle);
													$spinbody = $this->process($body);
													$response = $this->Plivo->sendsms($to,$from,$spinbody);
													$errortext = '';
													$message_id = '';
													if(isset($response['response']['error'])){
														$errortext = $response['response']['error'];
													}
													if(isset($response['response']['message_uuid'][0])){
														$message_id = $response['response']['message_uuid'][0];
													}
													if(!empty($SingleScheduleMessages['ScheduleMessage']['id'])){
														$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
														$ScheduleMessage_arra['ScheduleMessage']['id'] = $scheduleId;
														$ScheduleMessage_arra['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($ScheduleMessage_arra);
													}
													$this->Log = new Log();
													$logArr['sms_id'] = $message_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
													$logArr['text_message'] = $spinbody;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if(isset($response['response']['error'])){
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $errortext;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$sucesscredits = $sucesscredits + 1;
														$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
                                                        if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
															$credits = $credits + ceil($length/70);
														}else{
															$credits = $credits + ceil($length/160);
														}
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
												}
											}
										}
										if($sucesscredits > 0){
											app::import('Model','User');
											$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
											if(!empty($usersms)){
												$user_update['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$user_update['User']['id']=$usersms['User']['id'];
												$this->User->save($user_update);
											}
										}
										app::import('Model','User');
										$this->User = new User();
										$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
										if($users['User']['email_alert_credit_options']==0){
											if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
												if($users['User']['sms_credit_balance_email_alerts']==0){
													$username = $users['User']['username'];
													$email = $users['User']['email'];
													$phone = $users['User']['assigned_number'];
													$subject="Low SMS Credit Balance";	
													$sitename=str_replace(' ','',SITENAME);
													$this->Email->to = $email;	
													$this->Email->subject = $subject;
													$this->Email->from = $sitename;
													$this->Email->template = 'low_sms_credit_template';
													$this->Email->sendAs = 'html';
													$this->Email->Controller->set('username', $username);
													$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
													$this->Email->send();
													$this->User->id = $users['User']['id'];
													$this->User->saveField('sms_credit_balance_email_alerts',1);
												}	
											}
										}
									}
								}
							}
						}	
					}
				}else{	
					if(!empty($SingleScheduleMessage)){
						foreach($SingleScheduleMessage as $SingleScheduleMessages){
							$contact_id = $SingleScheduleMessages['SingleScheduleMessage']['contact_id'];
							$user_id = $SingleScheduleMessages['ScheduleMessage']['user_id'];
							$message = $SingleScheduleMessages['ScheduleMessage']['message'];
							$systemmsg = $SingleScheduleMessages['ScheduleMessage']['systemmsg'];
							$rotate_number = $SingleScheduleMessages['ScheduleMessage']['rotate_number'];
                                                        $throttle = $SingleScheduleMessages['ScheduleMessage']['throttle'];
                                                        $alphasenderid = $SingleScheduleMessages['ScheduleMessage']['alphasender_input'];
							if($rotate_number==1){
								if(isset($contact_id)){
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.un_subscribers'=>0))); 
									$totalSubscriber = count($schedulemessages1);
									$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
									$from_arr=array();
									if(!empty($users)){	
										if($users['User']['sms']==1){
											$from_arr[]=$users['User']['assigned_number'];
										}
									}
									if(!empty($user_numbers)){	
										foreach($user_numbers as $values){	
											if($values['UserNumber']['sms']==1){
												$from_arr[]=$values['UserNumber']['number'];
											}				
										}
									}
									if($totalSubscriber > 0){
										$k = 0;
										$sucesscredits = 0;
										$credits = 0;
										foreach($schedulemessages1 as $schedulemessages){
											if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
												$sms_balance=$users['User']['sms_balance'];
												$body = $message." ".$systemmsg;
												$spinbody = $this->process($body);    
												$length = strlen(utf8_decode(substr($spinbody,0,1600)));
												if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
													$contactcredits = ceil($length/70);
												}else{
													$contactcredits = ceil($length/160);
												}
												if($sms_balance >= ($totalSubscriber * $contactcredits)){
													$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
													$to = $schedulemessages['Contact']['phone_number'];
													$countnumber = count($from_arr);
													if($countnumber==$k){
														$k = 0;
														sleep($throttle);
													}
													$from = $from_arr[$k];
													$space_pos = strpos($schedulemessages['Contact']['name'],' ');
													if($space_pos!=''){	
														$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $schedulemessages['Contact']['name'];
													}
													$message2 =str_replace('%%Name%%',$contact_name,$message);
													$body = $message2." ".$systemmsg;
													$this->Nexmomessage->Key = NEXMO_KEY;  
													$this->Nexmomessage->Secret = NEXMO_SECRET;
                                                    $spinbody = $this->process($body);
													$response = $this->Nexmomessage->sendsms($to,$from,$spinbody);
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
				
													if(!empty($SingleScheduleMessages['ScheduleMessage']['id'])){
														$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
														$ScheduleMessage_save_arr['ScheduleMessage']['id'] = $scheduleId;
														$ScheduleMessage_save_arr['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($ScheduleMessage_save_arr);
													}
													$this->Log = new Log();
													$logArr['sms_id'] = $message_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
													$logArr['text_message'] = $spinbody;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if($status!=0){
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $errortext;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$sucesscredits = $sucesscredits + 1;
														$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
														//$credits = $credits + ceil($length/160);
														if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
															$credits = $credits + ceil($length/70);
														}else{
															$credits = $credits + ceil($length/160);
														}
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
												}
											}
											$k = $k + 1;
										}
										if($sucesscredits > 0){
											app::import('Model','User');
											$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
											if(!empty($usersms)){
												//$user_credit['User']['sms_balance']=$usersms['User']['sms_balance']-$sucesscredits;
												//$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = ceil($length/160);
												$user_credit['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$user_credit['User']['id']=$usersms['User']['id'];
												$this->User->save($user_credit);
											}
										}
										app::import('Model','User');
										$this->User = new User();
										$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
										if($users['User']['email_alert_credit_options']==0){
											if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
												if($users['User']['sms_credit_balance_email_alerts']==0){
													$username = $users['User']['username'];
													$email = $users['User']['email'];
													$phone = $users['User']['assigned_number'];
													$subject="Low SMS Credit Balance";	
													$sitename=str_replace(' ','',SITENAME);
													$this->Email->to = $email;	
													$this->Email->subject = $subject;
													$this->Email->from = $sitename;
													$this->Email->template = 'low_sms_credit_template';
													$this->Email->sendAs = 'html';
													$this->Email->Controller->set('username', $username);
													$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
													$this->Email->send();
													$this->User->id = $users['User']['id'];
													$this->User->saveField('sms_credit_balance_email_alerts',1);
												}	
											}
										}
									}
								}
							}else{
								if(isset($contact_id)){
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.un_subscribers'=>0))); 
									$totalSubscriber = count($schedulemessages1);
									$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
									if(!empty($users)){
                                                                           if($alphasenderid != ''){
                                                                              $assigned_number = $alphasenderid;
                                                                           }else{
										if($users['User']['sms']==1){
											$assigned_number=$users['User']['assigned_number'];
										}else{
											$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
											if(!empty($user_numbers)){	
												$assigned_number=$user_numbers['UserNumber']['number'];
											}else{
												$assigned_number=$users['User']['assigned_number'];
											}
										}
                                                                             }
									}
									if($totalSubscriber > 0){
										$sucesscredits = 0;
										$credits = 0;
										foreach($schedulemessages1 as $schedulemessages){
											if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
												$sms_balance=$users['User']['sms_balance'];
												$body = $message." ".$systemmsg;
												$spinbody = $this->process($body); 
												$length = strlen(utf8_decode(substr($spinbody,0,1600)));
												if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
													$contactcredits = ceil($length/70);
												}else{
													$contactcredits = ceil($length/160);
												}
												if($sms_balance >= ($totalSubscriber * $contactcredits)){
													$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
													$to = $schedulemessages['Contact']['phone_number'];
													$from = $assigned_number;
													$space_pos = strpos($schedulemessages['Contact']['name'],' ');
													if($space_pos!=''){	
														$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $schedulemessages['Contact']['name'];
													}
													$message2 =str_replace('%%Name%%',$contact_name,$message);
													$body = $message2." ".$systemmsg;
													$this->Nexmomessage->Key = NEXMO_KEY;
													$this->Nexmomessage->Secret = NEXMO_SECRET;
													sleep($throttle);
													$spinbody = $this->process($body);
													$response = $this->Nexmomessage->sendsms($to,$from,$spinbody);
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
													if(!empty($SingleScheduleMessages['ScheduleMessage']['id'])){
														$scheduleId = $SingleScheduleMessages['ScheduleMessage']['id'];
														$ScheduleMessage_arra['ScheduleMessage']['id'] = $scheduleId;
														$ScheduleMessage_arra['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($ScheduleMessage_arra);
													}
													$this->Log = new Log();
													$logArr['sms_id'] = $message_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
													$logArr['text_message'] = $spinbody;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if($status!=0){
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $errortext;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$sucesscredits = $sucesscredits + 1;
														$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
                                                        if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
															$credits = $credits + ceil($length/70);
														}else{
															$credits = $credits + ceil($length/160);
														}
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
												}
											}
										}
										if($sucesscredits > 0){
											app::import('Model','User');
											$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
											if(!empty($usersms)){
												$user_update['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$user_update['User']['id']=$usersms['User']['id'];
												$this->User->save($user_update);
											}
										}
										app::import('Model','User');
										$this->User = new User();
										$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
										if($users['User']['email_alert_credit_options']==0){
											if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
												if($users['User']['sms_credit_balance_email_alerts']==0){
													$username = $users['User']['username'];
													$email = $users['User']['email'];
													$phone = $users['User']['assigned_number'];
													$subject="Low SMS Credit Balance";	
													$sitename=str_replace(' ','',SITENAME);
													$this->Email->to = $email;	
													$this->Email->subject = $subject;
													$this->Email->from = $sitename;
													$this->Email->template = 'low_sms_credit_template';
													$this->Email->sendAs = 'html';
													$this->Email->Controller->set('username', $username);
													$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
													$this->Email->send();
													$this->User->id = $users['User']['id'];
													$this->User->saveField('sms_credit_balance_email_alerts',1);
												}	
											}
										}
									}
								}
							}
						}	
					}
				}
			}
		}			
	}

	function send_message(){
		$this->autoRender = false;
		ob_start();
		app::import('Model','ScheduleMessageGroup');
		$this->ScheduleMessageGroup = new ScheduleMessageGroup();
		app::import('Model','ScheduleMessage');
		$this->ScheduleMessage = new ScheduleMessage();
		app::import('Model','Log');
		$this->Log = new Log();
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		app::import('Model','User');
		$this->User = new User();
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		app::import('Model','SingleScheduleMessage');
		$this->SingleScheduleMessage = new SingleScheduleMessage();
		$schedule_arr = $this->ScheduleMessage->find('all', array('fields' => array('DISTINCT ScheduleMessage.user_id')));
       
		foreach($schedule_arr as $users){
			$Uid=$users['ScheduleMessage']['user_id'];
            $user_arr=$this->User->find('first', array('conditions' => array('User.id'=>$Uid)));
            $active = $user_arr['User']['active'];
			if($Uid!='' && $active == 1){
				$timezone=$user_arr['User']['timezone'];
				date_default_timezone_set($timezone);
				$date = date('Y-m-d H:i:s');
				//echo "<br>";
				$schedulemessages = $this->ScheduleMessageGroup->find('all', array('conditions' => array('ScheduleMessage.sent'=>0,'ScheduleMessage.send_on <= '=>$date,'ScheduleMessage.user_id'=>$Uid)));
				if(!empty($schedulemessages)){
					foreach($schedulemessages as $schedulemessage){
						$count = 0;
						$group_id = $schedulemessage['Group']['id'];
						$message = $schedulemessage['ScheduleMessage']['message'];
						$systemmsg = $schedulemessage['ScheduleMessage']['systemmsg'];
						$mms_text = $schedulemessage['ScheduleMessage']['mms_text'].' '.$systemmsg;
						$sent_on = $schedulemessage['ScheduleMessage']['send_on'];
						$user_id = $schedulemessage['ScheduleMessage']['user_id'];
						$rotate = $schedulemessage['ScheduleMessage']['rotate_number'];
                                                $throttle = $schedulemessage['ScheduleMessage']['throttle'];
                                                $alphasenderid = $schedulemessage['ScheduleMessage']['alphasender_input'];

						if(isset($group_id)){
							$schedulemessages1 = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.group_id'=>$group_id ,'ContactGroup.un_subscribers'=>0)));
							if(!empty($schedulemessages1)){
								$totalSubscriber = count($schedulemessages1);
								if($totalSubscriber > 0){
									$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
									if(API_TYPE==0){
										if($rotate==1){
											if($users['User']['sms_balance'] > $totalSubscriber){	
												$subscriberPhone1 = '';
												app::import('Model','GroupSmsBlast');
												$this->GroupSmsBlast = new GroupSmsBlast();
												$this->data['GroupSmsBlast']['user_id'] =$user_id;
												$this->data['GroupSmsBlast']['group_id'] =$group_id;
												$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
												$this->GroupSmsBlast->save($this->data);
												$group_sms_id = $this->GroupSmsBlast->id;
												$k = 0;
												foreach($schedulemessages1 as $schedulemessages){
													if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
														$sms_balance=$users['User']['sms_balance'];
														$body = $message." ".$systemmsg;
														$spinbody = $this->process($body);  
														$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
														if($schedulemessage['ScheduleMessage']['msg_type']==2){ 
															$contactcredits = 2; 
														}else{ 
															if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
																$contactcredits = ceil($length/70);
															}else{
																$contactcredits = ceil($length/160);
															}
														}
														if($sms_balance >= ($totalSubscriber * $contactcredits)){
															$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
															$to = $schedulemessages['Contact']['phone_number'];
															app::import('Model','UserNumber');
															$this->UserNumber = new UserNumber();
															$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
															$from_arr=array();
															if(!empty($users)){	
																if($users['User']['sms']==1){
																	$from_arr[]=$users['User']['assigned_number'];
																}
															}
															if(!empty($user_numbers)){	
																foreach($user_numbers as $values){	
																	if($values['UserNumber']['sms']==1){
																		$from_arr[]=$values['UserNumber']['number'];
																	}				
																}
															}
															$mms_arr=array();
															if(!empty($users)){	
																if($users['User']['mms']==1){
																	$mms_arr[]=$users['User']['assigned_number'];
																}
															}
															if(!empty($user_numbers)){	
																foreach($user_numbers as $values){	
																	if($values['UserNumber']['mms']==1){
																		$mms_arr[]=$values['UserNumber']['number'];
																	}				
																}
															}
															$space_pos = strpos($schedulemessages['Contact']['name'],' ');
															if($space_pos!=''){
																$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
															}else{
																$contact_name = $schedulemessages['Contact']['name'];
															}
															if($schedulemessage['ScheduleMessage']['msg_type']==1){
																$message2 =str_replace('%%Name%%',$contact_name,$message);
															}else if($schedulemessage['ScheduleMessage']['msg_type']==2){
																$message_arr=explode(',',$message);
																$message2=$message_arr;
															}
															$Status=''; 
															$smsid=''; 
															$body=''; 
															if($schedulemessage['ScheduleMessage']['msg_type']==1){
																//$random_keys= array_rand($from_arr,1);
																if ($throttle > 1){
																	$countnumber = count($from_arr);
																	if($countnumber==$k){
																		$k = 0;
																		sleep($throttle);
																	}
																}
                                                                                        $from=$from_arr[$k];
																//$from=$from_arr[$random_keys];
																$body = $message2." ".$systemmsg;
																$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
																$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
																$spinbody = $this->process($body);															
																$response = $this->Twilio->sendsms($to,$from,$spinbody);
																$msg_type='text';
																$smsid=$response->ResponseXml->Message->Sid;
																$Status=$response->ResponseXml->RestException->Status;
															}else if($schedulemessage['ScheduleMessage']['msg_type']==2){
																if ($throttle > 1){
																	$countnumber = count($mms_arr);
																	if($countnumber==$k){
																		$k = 0;
																		sleep($throttle);
																	}
																}
                                                                $from=$mms_arr[$k];
																//$from=$mms_arr[$random_keys];
																$body = $message2;
																$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
																$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
																$spinmms_text = $this->process($mms_text);															
																$response = $this->Mms->sendmms($to,$from,$body,$spinmms_text);
																$msg_type='text';
																$smsid=$response->sid;
																if($smsid==''){
																	$ErrorMessage = $response;
																	$Status=400;
																}
															}
															$scheduleId = $schedulemessage['ScheduleMessage']['id'];
															$this->ScheduleMessage->id = $scheduleId;
															$this->data['ScheduleMessage']['sent'] = 1;
															$this->ScheduleMessage->save($this->data);
															$this->Log = new Log();
															$logArr['group_sms_id'] = $group_sms_id;
															$logArr['sms_id'] = $smsid;
															$logArr['group_id'] = $group_id;
															$logArr['user_id'] = $users['User']['id'];
															$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
															if($schedulemessage['ScheduleMessage']['msg_type']==2){
																$logArr['image_url'] = $message;
																$logArr['text_message'] = $spinmms_text;
															}else{
																$logArr['text_message'] = $spinbody;
															}
															$logArr['msg_type'] = $msg_type;
															$logArr['route'] = 'outbox';
															$logArr['sms_status']= '';
															$logArr['error_message']='';
															if($Status==400){
																$logArr['sms_status']= 'failed';
																if(isset($response->ErrorMessage)){
																	$ErrorMessage = $response->ErrorMessage;
																}else{
																	$ErrorMessage = $ErrorMessage;
																}
																$logArr['error_message']=$ErrorMessage;
															}
															$this->Log->save($logArr);
															if($Status==400){
																$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
																$GroupSmsBlast['GroupSmsBlast']['id'] = $group_sms_id;
																$GroupSmsBlast['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
																$this->GroupSmsBlast->save($GroupSmsBlast);
															}

														}
													}
													$k = $k+1;
												}
											}
										}else{
											if($users['User']['sms_balance'] > $totalSubscriber){	
												$subscriberPhone1 = '';
												app::import('Model','GroupSmsBlast');
												$this->GroupSmsBlast = new GroupSmsBlast();
												$this->data['GroupSmsBlast']['user_id'] =$user_id;
												$this->data['GroupSmsBlast']['group_id'] =$group_id;
												$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
												$this->GroupSmsBlast->save($this->data);
												$group_sms_id = $this->GroupSmsBlast->id;
												if($schedulemessage['ScheduleMessage']['msg_type']==2){
													$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.mms'=>1)));
                                                                                                   if($alphasenderid != ''){
                                                                                                      $assigned_number = $alphasenderid;
                                                                                                   }else{
													if(empty($usernumber)){
														$mmsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
														if(!empty($mmsnumber)){
															$assigned_number = $mmsnumber['UserNumber']['number'];
														}else{
															$assigned_number = $users['User']['assigned_number'];
														}
													}else{
														$assigned_number = $usernumber['User']['assigned_number'];
													}
                                                                                                     }
												}else{
													$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
                                                                                                   if($alphasenderid != ''){
                                                                                                      $assigned_number = $alphasenderid;
                                                                                                   }else{
													if(!empty($usernumber)){
														if($usernumber['User']['sms']==1){
															$assigned_number=$usernumber['User']['assigned_number'];
														}else{
															$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
															if(!empty($user_numbers)){	
																$assigned_number=$user_numbers['UserNumber']['number'];
															}else{
																$assigned_number=$usernumber['User']['assigned_number'];
															}
														}
													}
                                                                                                    }
												}
												foreach($schedulemessages1 as $schedulemessages){
													if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
														$sms_balance=$users['User']['sms_balance'];
														$count++;
														$body = $message." ".$systemmsg;
														$spinbody = $this->process($body);  
														$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
														if($schedulemessage['ScheduleMessage']['msg_type']==2){ 
															$contactcredits = 2; 
														}else{ 
															if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
																$contactcredits = ceil($length/70);
															}else{
																$contactcredits = ceil($length/160);
															}
														}
														if($sms_balance >= ($totalSubscriber * $contactcredits)){
															$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
															$to = $schedulemessages['Contact']['phone_number'];
															$from = $assigned_number;
															$space_pos = strpos($schedulemessages['Contact']['name'],' ');
															if($space_pos!=''){
																$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
															}else{
																$contact_name = $schedulemessages['Contact']['name'];
															
															}
															if($schedulemessage['ScheduleMessage']['msg_type']==1){
																$message2 =str_replace('%%Name%%',$contact_name,$message);
															}else if($schedulemessage['ScheduleMessage']['msg_type']==2){
																$message_arr=explode(',',$message);
																$message2=$message_arr;
															}
															$body='';
															$msg_type='';
															$Status='';
															if ($throttle > 1) {
																sleep($throttle);
                                                            }
															if($schedulemessage['ScheduleMessage']['msg_type']==1){
																$body = $message2." ".$systemmsg;
																$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
																$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
																$spinbody = $this->process($body);												
																$response = $this->Twilio->sendsms($to,$from,$spinbody);
																$msg_type='text';
																$smsid=$response->ResponseXml->Message->Sid;
																$Status=$response->ResponseXml->RestException->Status;
															}else if($schedulemessage['ScheduleMessage']['msg_type']==2){
																$body = $message2;
																$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
																$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
																$spinmms_text = $this->process($mms_text);
																$response = $this->Mms->sendmms($to,$from,$body,$spinmms_text);
																$msg_type='text';
																$smsid=$response->sid;
																if($smsid==''){
																	$ErrorMessage = $response;
																	$Status=400;
																}
															}
															//save data in Schedule Message table
															$scheduleId = $schedulemessage['ScheduleMessage']['id'];
															$ScheduleMessage_arr['ScheduleMessage']['id'] = $scheduleId;
															$ScheduleMessage_arr['ScheduleMessage']['sent'] = 1;
															$this->ScheduleMessage->save($ScheduleMessage_arr);
															$this->Log = new Log();
															$logArr['group_sms_id'] = $group_sms_id;
															$logArr['sms_id'] = $smsid;
															$logArr['group_id'] = $group_id;
															$logArr['user_id'] = $users['User']['id'];
															$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
															if($schedulemessage['ScheduleMessage']['msg_type']==2){
																$logArr['image_url'] = $message;
																$logArr['text_message'] = $spinmms_text;
															}else{
																$logArr['text_message'] = $spinbody;
															}
															$logArr['msg_type'] = $msg_type;
															$logArr['route'] = 'outbox';
															$logArr['sms_status']= '';
															$logArr['error_message']='';
															if($Status==400){
																$logArr['sms_status']= 'failed';
																if(isset($response->ErrorMessage)){
																	$ErrorMessage = $response->ErrorMessage;
																}else{
																	$ErrorMessage = $ErrorMessage;
																}
																$logArr['error_message']=$ErrorMessage;
															}
															$this->Log->save($logArr);
															if($Status==400){
																$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
																$GroupSmsBlast['GroupSmsBlast']['id'] = $group_sms_id;
																$GroupSmsBlast['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
																$this->GroupSmsBlast->save($GroupSmsBlast);
															}
														}
													}
												}
											}
										}
									}else if(API_TYPE==2){
										if($rotate==1){
											$subscriberPhone1 = '';
											app::import('Model','GroupSmsBlast');
											$this->GroupSmsBlast = new GroupSmsBlast();
											$this->data['GroupSmsBlast']['user_id'] =$user_id;
											$this->data['GroupSmsBlast']['group_id'] =$group_id;
											$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
											$this->GroupSmsBlast->save($this->data);
											$group_sms_id = $this->GroupSmsBlast->id;
											$k = 0;	
											$sucesscredits = 0;
											$credits = 0;
											foreach($schedulemessages1 as $schedulemessages){
												if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													$count++;
													if($sms_balance > $totalSubscriber){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														app::import('Model','UserNumber');
														$this->UserNumber = new UserNumber();
														$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
														$from_arr=array();
														if(!empty($users)){	
															if($users['User']['sms']==1){
																$from_arr[]=$users['User']['assigned_number'];
															}
														}
														if(!empty($user_numbers)){	
															foreach($user_numbers as $values){	
																if($values['UserNumber']['sms']==1){
																	$from_arr[]=$values['UserNumber']['number'];
																}				
															}
														}
														$countnumber = count($from_arr);	
														if($countnumber==$k){
															$k = 0;
														}
														$from = $from_arr[$k];
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
														$message2 =str_replace('%%Name%%',$contact_name,$message);
														$body = $message2." ".$systemmsg;
														$spinbody = $this->process($body);
														$response = $this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$schedulemessages['Contact']['phone_number'],$users['User']['keyword'],$spinbody);
														$message_id = '';
														$status = '';
														if(isset($response['id'])){
															if($response['result']=='ok'){
																$message_id = $response['id'];
															}
															$status = $response['result'];
														}	
														//save data in log table
														//save data in Schedule Message table
														$scheduleId = $schedulemessage['ScheduleMessage']['id'];
														$this->ScheduleMessage->id = $scheduleId;
														$this->data['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($this->data);
														$this->Log = new Log();
														pr($contactArr);
														$logArr['group_sms_id'] = $group_sms_id;
														$logArr['sms_id'] = $message_id;
														$logArr['group_id'] = $group_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
														$logArr['text_message'] = $spinbody;
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if($status!='ok'){		
															$logArr['sms_status']= 'failed';
															$ErrorMessage = $status;
															$logArr['error_message']=$ErrorMessage;
														}else{
															$logArr['sms_status']= 'sent';
														}
				
														$this->Log->save($logArr);
														if($message_id!=''){
															$sucesscredits = $sucesscredits + 1;
															$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
															$credits = $credits + ceil($length/160);
														} 
														if($status!='ok'){
															$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
															$this->GroupSmsBlast->id = $group_sms_id;
															$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
															$this->GroupSmsBlast->save($this->data);
														}
													}
												}
												$k = $k + 1;
											}
											if($sucesscredits > 0){
												app::import('Model','GroupSmsBlast');
												$group_blast['GroupSmsBlast']['id'] =$group_sms_id;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
												$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
												$this->GroupSmsBlast->save($group_blast);
												app::import('Model','User');
												$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
												//$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$sucesscredits;
												//$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = ceil($length/160);
												$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$this->data['User']['id']=$usersms['User']['id'];
												$this->User->save($this->data);
											}
											app::import('Model','User');
											$this->User = new User();
											$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
											if($users['User']['email_alert_credit_options']==0){
												if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
													if($users['User']['sms_credit_balance_email_alerts']==0){
														$username = $users['User']['username'];
														$email = $users['User']['email'];
														$phone = $users['User']['assigned_number'];
														$subject="Low SMS Credit Balance";	
														$sitename=str_replace(' ','',SITENAME);
														$this->Email->to = $email;	
														$this->Email->subject = $subject;
														$this->Email->from = $sitename;
														$this->Email->template = 'low_sms_credit_template';
														$this->Email->sendAs = 'html';
														$this->Email->Controller->set('username', $username);
														$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
														$this->Email->send();
														$this->User->id = $users['User']['id'];
														$this->User->saveField('sms_credit_balance_email_alerts',1);
													}	
												}
											}
										}else{
											$subscriberPhone1 = '';
											app::import('Model','GroupSmsBlast');
											$this->GroupSmsBlast = new GroupSmsBlast();
											$this->data['GroupSmsBlast']['user_id'] =$user_id;
											$this->data['GroupSmsBlast']['group_id'] =$group_id;
											$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
											$this->GroupSmsBlast->save($this->data);
											$group_sms_id = $this->GroupSmsBlast->id;
											$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
											if(!empty($usernumber)){
												if($usernumber['User']['sms']==1){
													$assigned_number=$usernumber['User']['assigned_number'];
												}else{
													$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
													if(!empty($user_numbers)){	
														$assigned_number=$user_numbers['UserNumber']['number'];
													}else{
														$assigned_number=$usernumber['User']['assigned_number'];
													}
												}
											}
											$sucesscredits = 0;
											$credits = 0;
											foreach($schedulemessages1 as $schedulemessages){
												if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													$count++;
													if($sms_balance > $totalSubscriber){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														$from = $assigned_number;
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
					
														$message2 =str_replace('%%Name%%',$contact_name,$message);
														$body = $message2." ".$systemmsg;
														$spinbody = $this->process($body);
														$response = $this->Slooce->mt($usernumber['User']['api_url'],$usernumber['User']['partnerid'],$usernumber['User']['partnerpassword'],$schedulemessages['Contact']['phone_number'],$usernumber['User']['keyword'],$spinbody);
														$message_id = '';
														$status = '';
														if(isset($response['id'])){
															if($response['result']=='ok'){
																$message_id = $response['id'];
															}
															$status = $response['result'];
														}
														//save data in log table
														//save data in Schedule Message table
														$scheduleId = $schedulemessage['ScheduleMessage']['id'];
														$this->ScheduleMessage->id = $scheduleId;
														$this->data['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($this->data);
														$this->Log = new Log();
														pr($contactArr);
														$logArr['group_sms_id'] = $group_sms_id;
														$logArr['sms_id'] = $message_id;
														$logArr['group_id'] = $group_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
														$logArr['text_message'] = $spinbody;
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if($status!='ok'){	
															$logArr['sms_status']= 'failed';
															$ErrorMessage = $status;
															$logArr['error_message']=$ErrorMessage;
														}else{
															$logArr['sms_status']= 'sent';
														}
														$this->Log->save($logArr);
														if($message_id!=''){
															$sucesscredits = $sucesscredits + 1;
															$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
															$credits = $credits + ceil($length/160);
														} 
														if($status!='ok'){
															$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
															$this->GroupSmsBlast->id = $group_sms_id;
															$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
															$this->GroupSmsBlast->save($this->data);
														}
													}
												}
											}
											if($sucesscredits > 0){
												app::import('Model','GroupSmsBlast');
												$group_blast['GroupSmsBlast']['id'] =$group_sms_id;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
												$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
												$this->GroupSmsBlast->save($group_blast);
												app::import('Model','User');
												$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
												//$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$sucesscredits;
												//$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = ceil($length/160);
												$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$this->data['User']['id']=$usersms['User']['id'];
												$this->User->save($this->data);
											}
											app::import('Model','User');
											$this->User = new User();
											$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
											if($users['User']['email_alert_credit_options']==0){
												if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
													if($users['User']['sms_credit_balance_email_alerts']==0){
														$username = $users['User']['username'];
														$email = $users['User']['email'];
														$phone = $users['User']['assigned_number'];
														$subject="Low SMS Credit Balance";	
														$sitename=str_replace(' ','',SITENAME);
														$this->Email->to = $email;	
														$this->Email->subject = $subject;
														$this->Email->from = $sitename;
														$this->Email->template = 'low_sms_credit_template';
														$this->Email->sendAs = 'html';
														$this->Email->Controller->set('username', $username);
														$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
														$this->Email->send();
														$this->User->id = $users['User']['id'];
														$this->User->saveField('sms_credit_balance_email_alerts',1);
													}	
												}
											}
										}
									}else if(API_TYPE==3){
										if($rotate==1){
											$subscriberPhone1 = '';
											app::import('Model','GroupSmsBlast');
											$this->GroupSmsBlast = new GroupSmsBlast();
											$this->data['GroupSmsBlast']['user_id'] =$user_id;
											$this->data['GroupSmsBlast']['group_id'] =$group_id;
											$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
											$this->GroupSmsBlast->save($this->data);
											$group_sms_id = $this->GroupSmsBlast->id;
											$k = 0;	
											$sucesscredits = 0;
											$credits = 0;
											foreach($schedulemessages1 as $schedulemessages){
												if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													$count++;
													$body = $message." ".$systemmsg;
													$spinbody = $this->process($body);  
													$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
													if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
														$contactcredits = ceil($length/70);
													}else{
														$contactcredits = ceil($length/160);
													}
													if($sms_balance >= ($totalSubscriber * $contactcredits)){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														app::import('Model','UserNumber');
														$this->UserNumber = new UserNumber();
														$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
														$from_arr=array();
														if(!empty($users)){	
															if($users['User']['sms']==1){
																$from_arr[]=$users['User']['assigned_number'];
															}
														}
														if(!empty($user_numbers)){	
															foreach($user_numbers as $values){	
																if($values['UserNumber']['sms']==1){
																	$from_arr[]=$values['UserNumber']['number'];
																}				
															}
														}
														$countnumber = count($from_arr);	
														if($countnumber==$k){
															$k = 0;
															sleep($throttle);
														}
														$from = $from_arr[$k];
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
														$message2 =str_replace('%%Name%%',$contact_name,$message);
														$body = $message2." ".$systemmsg;
														$this->Plivo->AuthId =PLIVO_KEY;
														$this->Plivo->AuthToken =PLIVO_TOKEN;
														$spinbody = $this->process($body);
														$response = $this->Plivo->sendsms($to,$from,$spinbody);
														$errortext = '';
														$message_id = '';
														if(isset($response['response']['error'])){
															$errortext = $response['response']['error'];
														}
														if(isset($response['response']['message_uuid'][0])){
															$message_id = $response['response']['message_uuid'][0];
														}
														//save data in log table
														//save data in Schedule Message table
														$scheduleId = $schedulemessage['ScheduleMessage']['id'];
														$this->ScheduleMessage->id = $scheduleId;
														$this->data['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($this->data);
														$this->Log = new Log();
														pr($contactArr);
														$logArr['group_sms_id'] = $group_sms_id;
														$logArr['sms_id'] = $message_id;
														$logArr['group_id'] = $group_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
														$logArr['text_message'] = $spinbody;
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if(isset($response['response']['error'])){
															$logArr['sms_status']= 'failed';
															$ErrorMessage = $errortext;
															$logArr['error_message']=$ErrorMessage;
														}else{
															$logArr['sms_status']= 'sent';
														}
				
														$this->Log->save($logArr);
														if($message_id!=''){
															$sucesscredits = $sucesscredits + 1;
															$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
															//$credits = $credits + ceil($length/160);
															if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
																$credits = $credits + ceil($length/70);
															}else{
																$credits = $credits + ceil($length/160);
															}
														} 
														if(isset($response['response']['error'])){
															$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
															$this->GroupSmsBlast->id = $group_sms_id;
															$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
															$this->GroupSmsBlast->save($this->data);
														}
													}
												}
												$k = $k + 1;
											}
											if($sucesscredits > 0){
												app::import('Model','GroupSmsBlast');
												$group_blast['GroupSmsBlast']['id'] =$group_sms_id;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
												$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
												$this->GroupSmsBlast->save($group_blast);
												app::import('Model','User');
												$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
												$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$this->data['User']['id']=$usersms['User']['id'];
												$this->User->save($this->data);
											}
											app::import('Model','User');
											$this->User = new User();
											$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
											if($users['User']['email_alert_credit_options']==0){
												if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
													if($users['User']['sms_credit_balance_email_alerts']==0){
														$username = $users['User']['username'];
														$email = $users['User']['email'];
														$phone = $users['User']['assigned_number'];
														$subject="Low SMS Credit Balance";	
														$sitename=str_replace(' ','',SITENAME);
														$this->Email->to = $email;	
														$this->Email->subject = $subject;
														$this->Email->from = $sitename;
														$this->Email->template = 'low_sms_credit_template';
														$this->Email->sendAs = 'html';
														$this->Email->Controller->set('username', $username);
														$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
														$this->Email->send();
														$this->User->id = $users['User']['id'];
														$this->User->saveField('sms_credit_balance_email_alerts',1);
													}	
												}
											}
										}else{
											$subscriberPhone1 = '';
											app::import('Model','GroupSmsBlast');
											$this->GroupSmsBlast = new GroupSmsBlast();
											$this->data['GroupSmsBlast']['user_id'] =$user_id;
											$this->data['GroupSmsBlast']['group_id'] =$group_id;
											$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
											$this->GroupSmsBlast->save($this->data);
											$group_sms_id = $this->GroupSmsBlast->id;
											$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
                                                                                    if($alphasenderid != ''){
                                                                                        $assigned_number = $alphasenderid;
                                                                                    }else{
											if(!empty($usernumber)){
												if($usernumber['User']['sms']==1){
													$assigned_number=$usernumber['User']['assigned_number'];
												}else{
													$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
													if(!empty($user_numbers)){	
														$assigned_number=$user_numbers['UserNumber']['number'];
													}else{
														$assigned_number=$usernumber['User']['assigned_number'];
													}
												}
                                                                                            }
											}
											$sucesscredits = 0;
											$credits = 0;
											foreach($schedulemessages1 as $schedulemessages){
												if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													$count++;
													$body = $message." ".$systemmsg;
													$spinbody = $this->process($body); 
													$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
													if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
														$contactcredits = ceil($length/70);
													}else{
														$contactcredits = ceil($length/160);
													}
													if($sms_balance >= ($totalSubscriber * $contactcredits)){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														$from = $assigned_number;
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
					
														$message2 =str_replace('%%Name%%',$contact_name,$message);
														$body = $message2." ".$systemmsg;
														$this->Plivo->AuthId =PLIVO_KEY;
														$this->Plivo->AuthToken =PLIVO_TOKEN;
														sleep($throttle);
														$spinbody = $this->process($body);
														$response = $this->Plivo->sendsms($to,$from,$spinbody);
														$errortext = '';
														$message_id = '';
														if(isset($response['response']['error'])){
															$errortext = $response['response']['error'];
														}
														if(isset($response['response']['message_uuid'][0])){
															$message_id = $response['response']['message_uuid'][0];
														}
														//save data in log table
														//save data in Schedule Message table
														$scheduleId = $schedulemessage['ScheduleMessage']['id'];
														$this->ScheduleMessage->id = $scheduleId;
														$this->data['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($this->data);
														$this->Log = new Log();
														pr($contactArr);
														$logArr['group_sms_id'] = $group_sms_id;
														$logArr['sms_id'] = $message_id;
														$logArr['group_id'] = $group_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
														$logArr['text_message'] = $spinbody;
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if(isset($response['response']['error'])){
															$logArr['sms_status']= 'failed';
															$ErrorMessage = $errortext;
															$logArr['error_message']=$ErrorMessage;
														}else{
															$logArr['sms_status']= 'sent';
														}
														$this->Log->save($logArr);
														if($message_id!=''){
															$sucesscredits = $sucesscredits + 1;
															$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
															if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
																$credits = $credits + ceil($length/70);
															}else{
																$credits = $credits + ceil($length/160);
															}
														} 
														if(isset($response['response']['error'])){
															$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
															$this->GroupSmsBlast->id = $group_sms_id;
															$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
															$this->GroupSmsBlast->save($this->data);
														}
													}
												}
											}
											if($sucesscredits > 0){
												app::import('Model','GroupSmsBlast');
												$group_blast['GroupSmsBlast']['id'] =$group_sms_id;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
												$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
												$this->GroupSmsBlast->save($group_blast);
												app::import('Model','User');
												$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
												$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$this->data['User']['id']=$usersms['User']['id'];
												$this->User->save($this->data);
											}
											app::import('Model','User');
											$this->User = new User();
											$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
											if($users['User']['email_alert_credit_options']==0){
												if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
													if($users['User']['sms_credit_balance_email_alerts']==0){
														$username = $users['User']['username'];
														$email = $users['User']['email'];
														$phone = $users['User']['assigned_number'];
														$subject="Low SMS Credit Balance";	
														$sitename=str_replace(' ','',SITENAME);
														$this->Email->to = $email;	
														$this->Email->subject = $subject;
														$this->Email->from = $sitename;
														$this->Email->template = 'low_sms_credit_template';
														$this->Email->sendAs = 'html';
														$this->Email->Controller->set('username', $username);
														$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
														$this->Email->send();
														$this->User->id = $users['User']['id'];
														$this->User->saveField('sms_credit_balance_email_alerts',1);
													}	
												}
											}
										}
									}else{
										if($rotate==1){
											$subscriberPhone1 = '';
											app::import('Model','GroupSmsBlast');
											$this->GroupSmsBlast = new GroupSmsBlast();
											$this->data['GroupSmsBlast']['user_id'] =$user_id;
											$this->data['GroupSmsBlast']['group_id'] =$group_id;
											$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
											$this->GroupSmsBlast->save($this->data);
											$group_sms_id = $this->GroupSmsBlast->id;
											$k = 0;	
											$sucesscredits = 0;
											$credits = 0;
											foreach($schedulemessages1 as $schedulemessages){
												if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													$count++;
													$body = $message." ".$systemmsg;
													$spinbody = $this->process($body);  
													$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
													if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
														$contactcredits = ceil($length/70);
													}else{
														$contactcredits = ceil($length/160);
													}
													if($sms_balance >= ($totalSubscriber * $contactcredits)){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														app::import('Model','UserNumber');
														$this->UserNumber = new UserNumber();
														$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
														$from_arr=array();
														if(!empty($users)){	
															if($users['User']['sms']==1){
																$from_arr[]=$users['User']['assigned_number'];
															}
														}
														if(!empty($user_numbers)){	
															foreach($user_numbers as $values){	
																if($values['UserNumber']['sms']==1){
																	$from_arr[]=$values['UserNumber']['number'];
																}				
															}
														}
														$countnumber = count($from_arr);	
														if($countnumber==$k){
															$k = 0;
															sleep($throttle);
														}
														$from = $from_arr[$k];
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
														$message2 =str_replace('%%Name%%',$contact_name,$message);
														$body = $message2." ".$systemmsg;
														$this->Nexmomessage->Key = NEXMO_KEY;
														$this->Nexmomessage->Secret = NEXMO_SECRET;
														$spinbody = $this->process($body);
														$response = $this->Nexmomessage->sendsms($to,$from,$spinbody);
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
														//save data in log table
														//save data in Schedule Message table
														$scheduleId = $schedulemessage['ScheduleMessage']['id'];
														$this->ScheduleMessage->id = $scheduleId;
														$this->data['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($this->data);
														$this->Log = new Log();
														pr($contactArr);
														$logArr['group_sms_id'] = $group_sms_id;
														$logArr['sms_id'] = $message_id;
														$logArr['group_id'] = $group_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
														$logArr['text_message'] = $spinbody;
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if($status!=0){
															$logArr['sms_status']= 'failed';
															$ErrorMessage = $errortext;
															$logArr['error_message']=$ErrorMessage;
														}else{
															$logArr['sms_status']= 'sent';
														}
				
														$this->Log->save($logArr);
														if($message_id!=''){
															$sucesscredits = $sucesscredits + 1;
															$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
															//$credits = $credits + ceil($length/160);
															if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
																$credits = $credits + ceil($length/70);
															}else{
																$credits = $credits + ceil($length/160);
															}
														} 
														if($status!=0){
															$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
															$this->GroupSmsBlast->id = $group_sms_id;
															$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
															$this->GroupSmsBlast->save($this->data);
														}
													}
												}
												$k = $k + 1;
											}
											if($sucesscredits > 0){
												app::import('Model','GroupSmsBlast');
												$group_blast['GroupSmsBlast']['id'] =$group_sms_id;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
												$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
												$this->GroupSmsBlast->save($group_blast);
												app::import('Model','User');
												$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
												//$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$sucesscredits;
												//$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = ceil($length/160);
												$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$this->data['User']['id']=$usersms['User']['id'];
												$this->User->save($this->data);
											}
											app::import('Model','User');
											$this->User = new User();
											$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
											if($users['User']['email_alert_credit_options']==0){
												if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
													if($users['User']['sms_credit_balance_email_alerts']==0){
														$username = $users['User']['username'];
														$email = $users['User']['email'];
														$phone = $users['User']['assigned_number'];
														$subject="Low SMS Credit Balance";	
														$sitename=str_replace(' ','',SITENAME);
														$this->Email->to = $email;	
														$this->Email->subject = $subject;
														$this->Email->from = $sitename;
														$this->Email->template = 'low_sms_credit_template';
														$this->Email->sendAs = 'html';
														$this->Email->Controller->set('username', $username);
														$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
														$this->Email->send();
														$this->User->id = $users['User']['id'];
														$this->User->saveField('sms_credit_balance_email_alerts',1);
													}	
												}
											}
										}else{
											$subscriberPhone1 = '';
											app::import('Model','GroupSmsBlast');
											$this->GroupSmsBlast = new GroupSmsBlast();
											$this->data['GroupSmsBlast']['user_id'] =$user_id;
											$this->data['GroupSmsBlast']['group_id'] =$group_id;
											$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
											$this->GroupSmsBlast->save($this->data);
											$group_sms_id = $this->GroupSmsBlast->id;
											$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));

                                                                                    if($alphasenderid != ''){
                                                                                       $assigned_number = $alphasenderid;
                                                                                    }else{
											if(!empty($usernumber)){
												if($usernumber['User']['sms']==1){
													$assigned_number=$usernumber['User']['assigned_number'];
												}else{
													$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
													if(!empty($user_numbers)){	
														$assigned_number=$user_numbers['UserNumber']['number'];
													}else{
														$assigned_number=$usernumber['User']['assigned_number'];
													}
												}
                                                                                            }
											}
											$sucesscredits = 0;
											$credits = 0;
											foreach($schedulemessages1 as $schedulemessages){
												if(!isset($subscriberPhone1[$schedulemessages['Contact']['phone_number']])){
													$sms_balance=$users['User']['sms_balance'];
													$count++;
													$body = $message." ".$systemmsg;
													$spinbody = $this->process($body); 
													$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
													if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
														$contactcredits = ceil($length/70);
													}else{
														$contactcredits = ceil($length/160);
													}
													if($sms_balance >= ($totalSubscriber * $contactcredits)){
														$subscriberPhone1[$schedulemessages['Contact']['phone_number']] = $schedulemessages['Contact']['phone_number'];
														$to = $schedulemessages['Contact']['phone_number'];
														$from = $assigned_number;
														$space_pos = strpos($schedulemessages['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($schedulemessages['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $schedulemessages['Contact']['name'];
														}
					
														$message2 =str_replace('%%Name%%',$contact_name,$message);
														$body = $message2." ".$systemmsg;
														$this->Nexmomessage->Key = NEXMO_KEY;
														$this->Nexmomessage->Secret = NEXMO_SECRET;
														sleep($throttle);
														$spinbody = $this->process($body);
														$response = $this->Nexmomessage->sendsms($to,$from,$spinbody);
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
														//save data in log table
														//save data in Schedule Message table
														$scheduleId = $schedulemessage['ScheduleMessage']['id'];
														$this->ScheduleMessage->id = $scheduleId;
														$this->data['ScheduleMessage']['sent'] = 1;
														$this->ScheduleMessage->save($this->data);
														$this->Log = new Log();
														pr($contactArr);
														$logArr['group_sms_id'] = $group_sms_id;
														$logArr['sms_id'] = $message_id;
														$logArr['group_id'] = $group_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$schedulemessages['Contact']['phone_number'];
														$logArr['text_message'] = $spinbody;
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if($status!=0){
															$logArr['sms_status']= 'failed';
															$ErrorMessage = $errortext;
															$logArr['error_message']=$ErrorMessage;
														}else{
															$logArr['sms_status']= 'sent';
														}
														$this->Log->save($logArr);
														if($message_id!=''){
															$sucesscredits = $sucesscredits + 1;
															$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
															if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
																$credits = $credits + ceil($length/70);
															}else{
																$credits = $credits + ceil($length/160);
															}
														} 
														if($status!=0){
															$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
															$this->GroupSmsBlast->id = $group_sms_id;
															$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
															$this->GroupSmsBlast->save($this->data);
														}
													}
												}
											}
											if($sucesscredits > 0){
												app::import('Model','GroupSmsBlast');
												$group_blast['GroupSmsBlast']['id'] =$group_sms_id;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
												$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
												$this->GroupSmsBlast->save($group_blast);
												app::import('Model','User');
												$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
												$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
												$this->data['User']['id']=$usersms['User']['id'];
												$this->User->save($this->data);
											}
											app::import('Model','User');
											$this->User = new User();
											$users= $this->User->find('first', array('conditions' => array('User.id'=>$usersms['User']['id'])));
											if($users['User']['email_alert_credit_options']==0){
												if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
													if($users['User']['sms_credit_balance_email_alerts']==0){
														$username = $users['User']['username'];
														$email = $users['User']['email'];
														$phone = $users['User']['assigned_number'];
														$subject="Low SMS Credit Balance";	
														$sitename=str_replace(' ','',SITENAME);
														$this->Email->to = $email;	
														$this->Email->subject = $subject;
														$this->Email->from = $sitename;
														$this->Email->template = 'low_sms_credit_template';
														$this->Email->sendAs = 'html';
														$this->Email->Controller->set('username', $username);
														$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
														$this->Email->send();
														$this->User->id = $users['User']['id'];
														$this->User->saveField('sms_credit_balance_email_alerts',1);
													}	
												}
											}
										}
									}
								}
							}
						}
					}
				}	
			}
		}	 
	}

	function sendresponder(){
		$this->autoRender = false;
		app::import('Model','Responder');		
		$this->Responder = new Responder();
		app::import('Model','Log');
		$this->Log = new Log();
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		app::import('Model','User');
		$this->User = new User();
		//$responsedata = $this->Responder->find('all');
		$responsedata = $this->Responder->find('all',array('conditions' => array('Responder.days >'=>0)));
		if(!empty($responsedata)){
			$current_datetime=date("n/d/Y");
			foreach($responsedata as $response){
			    $Responderid = $response['Responder']['id']; 
			    $group_id = $response['Responder']['group_id'];
				$sms_type = $response['Responder']['sms_type'];
				$image_url = $response['Responder']['image_url'];
				$message = str_replace('%%CURRENTDATE%%',$current_datetime,$response['Responder']['message']);
				$systemmsg = $response['Responder']['systemmsg'];
				$user_id = $response['Responder']['user_id'];
				$responderdays = $response['Responder']['days'];
				$user_arr=$this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
				$timezone=$user_arr['User']['timezone'];
				$active=$user_arr['User']['active'];
				date_default_timezone_set($timezone);
				if($response['Responder']['sms_type']==2){
					if($user_arr['User']['mms']==1){
						$assigned_number = $user_arr['User']['assigned_number'];
					}else{
						app::import('Model','UserNumber');
						$this->UserNumber = new UserNumber();
						$mmsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
						if(!empty($mmsnumber)){
							$assigned_number = $mmsnumber['UserNumber']['number'];
						}else{
							$assigned_number = $user_arr['User']['assigned_number'];
						}
					}
				}else{
					if(!empty($user_arr)){
						if($user_arr['User']['sms']==1){
							$assigned_number=$user_arr['User']['assigned_number'];
						}else{
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
							if(!empty($user_numbers)){	
								$assigned_number=$user_numbers['UserNumber']['number'];
							}else{
								$assigned_number=$user_arr['User']['assigned_number'];
							}
						}
					}
				}
				if($assigned_number!='' && $active == 1){
					if(isset($group_id)){
						$tomorrow1 = mktime(0,0,0,date("m"),date("d")-$responderdays,date("Y"));
						//$group_id.'-'.
						$last_date=date("Y-m-d", $tomorrow1);


						//$contacts = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms !='=>0,'ContactGroup.created LIKE '=>$last_date.'%')));

						$contacts = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms !='=>0,'DATE(ContactGroup.created)'=>$last_date)));


						if(!empty($contacts)){
							$totalSubscriber = count($contacts);

							if($totalSubscriber > 0){
								$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								$smsbalance = $users['User']['sms_balance'];
								$body = $message." ".$systemmsg;
								$length = strlen(utf8_decode(substr($body,0,160))); 
								if($sms_type==2){ 
									$contactcredits = 2; 
								}else{ 
									if (strlen($body ) != strlen(utf8_decode($body ))){
										$contactcredits = ceil($length/70);
									}else{
										$contactcredits = ceil($length/160);
									}
								}
								if($smsbalance >= ($totalSubscriber * $contactcredits)){
									if(API_TYPE==0){
										$group_sms_id='';
										foreach ($contacts as $contact){
											//$reg_date=$contact['ContactGroup']['created'];
											//$date2 = date('Y-m-d H:i:s');
											//$seconds = strtotime($date2) - strtotime($reg_date);
											//$days = floor($seconds / 86400);
											//if($days == $responderdays){
												if($group_sms_id==''){
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->data['GroupSmsBlast']['user_id'] =$user_id;
													$this->data['GroupSmsBlast']['group_id'] =$group_id;
													$this->data['GroupSmsBlast']['responder'] =1;
													$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
													$this->GroupSmsBlast->save($this->data);
													$group_sms_id = $this->GroupSmsBlast->id;
												}
												$to = $contact['Contact']['phone_number'];
												$from = $assigned_number;
												$body = $message."\n".$systemmsg;
												$Status='';
												if($sms_type  == 1){
													$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
													$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
													$response = $this->Twilio->sendsms($to,$from,$body);
													$smsid=$response->ResponseXml->Message->Sid;
													$Status=$response->ResponseXml->RestException->Status;
												}else if($sms_type  == 2){
													$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
													$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;						
													$message_arr=explode(',',$image_url);
													$response = $this->Mms->sendmms($to,$from,$message_arr,$body);
													$smsid=$response->sid;
													if($smsid==''){
														$ErrorMessage = $response;
														$Status='400';
													}
												}
												$logArr['id'] ='';
												$logArr['group_sms_id'] =$group_sms_id;
												$logArr['sms_id'] = $smsid;
												$logArr['group_id'] = $group_id;
												$logArr['user_id'] = $users['User']['id'];
												$logArr['phone_number'] =$contact['Contact']['phone_number'];
												if($sms_type==2){
													$logArr['text_message'] = $body;
													$logArr['image_url'] = $image_url;	
												}else{
													$logArr['text_message'] = $body;
												}
												$logArr['route'] = 'outbox';
												$logArr['sms_status']= '';
												$logArr['error_message']='';
												if($Status==400){
													$logArr['sms_status']= 'failed';
													if(isset($response->ErrorMessage)){
														$ErrorMessage = $response->ErrorMessage;
													}else{			
														$ErrorMessage = $ErrorMessage;
													}
													$logArr['error_message']=$ErrorMessage;
												}
												$this->Log->save($logArr);
												if($Status==400){
													$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->GroupSmsBlast->id = $group_sms_id;
													$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
													$this->GroupSmsBlast->save($this->data);
												}
											//}
										}
									}else if(API_TYPE==2){
										$group_sms_id='';
										foreach ($contacts as $contact){
											//$reg_date=$contact['ContactGroup']['created'];
											//$date2 = date('Y-m-d h:i:s');
											//$seconds = strtotime($date2) - strtotime($reg_date);
											//$days = floor($seconds / 86400);
											//if($days == $responderdays){
												if($group_sms_id==''){
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->data['GroupSmsBlast']['user_id'] =$user_id;
													$this->data['GroupSmsBlast']['group_id'] =$group_id;
													$this->data['GroupSmsBlast']['responder'] =1;
													$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
													$this->GroupSmsBlast->save($this->data);
													$group_sms_id = $this->GroupSmsBlast->id;
												}
												$to = $contact['Contact']['phone_number'];
												$from = $assigned_number;
												$body = $message."\n".$systemmsg;
												$response = $this->Slooce->mt($user_arr['User']['api_url'],$user_arr['User']['partnerid'],$user_arr['User']['partnerpassword'],$to,$user_arr['User']['keyword'],$body);
												$message_id = '';
												$status = '';
												if(isset($response['id'])){
													if($response['result']=='ok'){
														$message_id = $response['id'];
													}
													$status = $response['result'];
												}	
												$logArr['id'] ='';
												$logArr['group_sms_id'] =$group_sms_id;
												$logArr['sms_id'] = $message_id;
												$logArr['group_id'] = $group_id;
												$logArr['user_id'] = $users['User']['id'];
												$logArr['phone_number'] =$contact['Contact']['phone_number'];
												$logArr['text_message'] = $body;
												$logArr['route'] = 'outbox';
												$logArr['sms_status']= '';
												$logArr['error_message']='';
												if($status!='ok'){	
													$logArr['sms_status']= 'failed';
													$ErrorMessage = $status;
													$logArr['error_message']=$ErrorMessage;
												}
												if($message_id!=''){
													$logArr['sms_status']= 'sent';
												}
												$this->Log->save($logArr);
												if($message_id!=''){
													app::import('Model','User');
													$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
													if(!empty($usersms)){
														$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-1;
														$this->data['User']['id']=$usersms['User']['id'];
														$this->User->save($this->data);
													}
													$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
													if(!empty($groupContacts)){
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
														$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
														$this->GroupSmsBlast->save($GroupSmsBlast_arr);
													}
													app::import('Model','User');
													$this->User = new User();
													$users= $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
													if($users['User']['email_alert_credit_options']==0){
														if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
															if($users['User']['sms_credit_balance_email_alerts']==0){
																$username = $users['User']['username'];
																$email = $users['User']['email'];
																$phone = $users['User']['assigned_number'];
																$subject="Low SMS Credit Balance";	
																$sitename=str_replace(' ','',SITENAME);
																$this->Email->to = $email;	
																$this->Email->subject = $subject;
																$this->Email->from = $sitename;
																$this->Email->template = 'low_sms_credit_template';
																$this->Email->sendAs = 'html';
																$this->Email->Controller->set('username', $username);
																$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
																$this->Email->send();
																$this->User->id = $users['User']['id'];
																$this->User->saveField('sms_credit_balance_email_alerts',1);
															}	
														}
													}
												}
												if($status!='ok'){	
													$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->GroupSmsBlast->id = $group_sms_id;
													$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
													$this->GroupSmsBlast->save($this->data);
												}
											//}
										}
									}else if(API_TYPE==3){
										$group_sms_id='';
										foreach ($contacts as $contact){
											//$reg_date=$contact['ContactGroup']['created'];
											//$date2 = date('Y-m-d h:i:s');
											//$seconds = strtotime($date2) - strtotime($reg_date);
											//$days = ceil($seconds / 86400);

											//if($days == $responderdays){
												if($group_sms_id==''){
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->data['GroupSmsBlast']['user_id'] =$user_id;
													$this->data['GroupSmsBlast']['group_id'] =$group_id;
													$this->data['GroupSmsBlast']['responder'] =1;
													$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
													$this->GroupSmsBlast->save($this->data);
													$group_sms_id = $this->GroupSmsBlast->id;
												}
												$to = $contact['Contact']['phone_number'];
												$from = $assigned_number;
												$body = $message."\n".$systemmsg;
												$this->Plivo->AuthId =PLIVO_KEY;
												$this->Plivo->AuthToken =PLIVO_TOKEN;
												sleep(1);
												//$body = $this->process($body);
												$response = $this->Plivo->sendsms($to,$from,$body);
												$errortext = '';
												$message_id = '';
												if(isset($response['response']['error'])){
													$errortext = $response['response']['error'];
												}
												if(isset($response['response']['message_uuid'][0])){
													$message_id = $response['response']['message_uuid'][0];
												}
												$logArr['id'] ='';
												$logArr['group_sms_id'] =$group_sms_id;
												$logArr['sms_id'] = $message_id;
												$logArr['group_id'] = $group_id;
												$logArr['user_id'] = $users['User']['id'];
												$logArr['phone_number'] =$contact['Contact']['phone_number'];
												$logArr['text_message'] = $body;
												$logArr['route'] = 'outbox';
												$logArr['sms_status']= '';
												$logArr['error_message']='';
												if(isset($response['response']['error'])){
													$logArr['sms_status']= 'failed';
													$ErrorMessage = $errortext;
													$logArr['error_message']=$ErrorMessage;
												}
												if($message_id!=''){
													$logArr['sms_status']= 'sent';
												}
												$this->Log->save($logArr);
												if($message_id!=''){
													app::import('Model','User');
													$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
													if(!empty($usersms)){
                                                        $length = strlen(utf8_decode(substr($body,0,160))); 
														if (strlen($body) != strlen(utf8_decode($body))){
															$credits = ceil($length/70);
														}else{
															$credits = ceil($length/160);
														}
														$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
														$this->data['User']['id']=$usersms['User']['id'];
														$this->User->save($this->data);
													}
													$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
													if(!empty($groupContacts)){
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
														$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
														$this->GroupSmsBlast->save($GroupSmsBlast_arr);
													}
													app::import('Model','User');
													$this->User = new User();
													$users= $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
													if($users['User']['email_alert_credit_options']==0){
														if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
															if($users['User']['sms_credit_balance_email_alerts']==0){
																$username = $users['User']['username'];
																$email = $users['User']['email'];
																$phone = $users['User']['assigned_number'];
																$subject="Low SMS Credit Balance";	
																$sitename=str_replace(' ','',SITENAME);
																$this->Email->to = $email;	
																$this->Email->subject = $subject;
																$this->Email->from = $sitename;
																$this->Email->template = 'low_sms_credit_template';
																$this->Email->sendAs = 'html';
																$this->Email->Controller->set('username', $username);
																$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
																$this->Email->send();
																$this->User->id = $users['User']['id'];
																$this->User->saveField('sms_credit_balance_email_alerts',1);
															}	
														}
													}
												}
												if(isset($response['response']['error'])){
													$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->GroupSmsBlast->id = $group_sms_id;
													$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
													$this->GroupSmsBlast->save($this->data);
												}
											//}
										}
									}else{
										$group_sms_id='';
										foreach ($contacts as $contact){
											//$reg_date=$contact['ContactGroup']['created'];
											//$date2 = date('Y-m-d h:i:s');
											//$seconds = strtotime($date2) - strtotime($reg_date);
											//$days = floor($seconds / 86400);
											//if($days == $responderdays){
												if($group_sms_id==''){
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->data['GroupSmsBlast']['user_id'] =$user_id;
													$this->data['GroupSmsBlast']['group_id'] =$group_id;
													$this->data['GroupSmsBlast']['responder'] =1;
													$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
													$this->GroupSmsBlast->save($this->data);
													$group_sms_id = $this->GroupSmsBlast->id;
												}
												$to = $contact['Contact']['phone_number'];
												$from = $assigned_number;
												$body = $message."\n".$systemmsg;
												$this->Nexmomessage->Key = NEXMO_KEY;
												$this->Nexmomessage->Secret = NEXMO_SECRET;
												sleep(1);
												//$body = $this->process($body);
												$responsemsg = $this->Nexmomessage->sendsms($to,$from,$body);
												foreach($responsemsg->messages  as $doc){
													$message_id= $doc->messageid;
													if($message_id!=''){
														$status= $doc->status;
														$message_id= $doc->messageid;
													}else{
														$status= $doc->status;
														$errortext= $doc->errortext;
													}
												}
												$logArr['id'] ='';
												$logArr['group_sms_id'] =$group_sms_id;
												$logArr['sms_id'] = $message_id;
												$logArr['group_id'] = $group_id;
												$logArr['user_id'] = $users['User']['id'];
												$logArr['phone_number'] =$contact['Contact']['phone_number'];
												$logArr['text_message'] = $body;
												$logArr['route'] = 'outbox';
												$logArr['sms_status']= '';
												$logArr['error_message']='';
												if($status!=0){
													$logArr['sms_status']= 'failed';
													$ErrorMessage = $errortext;
													$logArr['error_message']=$ErrorMessage;
												}
												if($message_id!=''){
													$logArr['sms_status']= 'sent';
												}
												$this->Log->save($logArr);
												if($message_id!=''){
													app::import('Model','User');
													$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
													if(!empty($usersms)){
                                                        $length = strlen(utf8_decode(substr($body,0,160))); 
														if (strlen($body) != strlen(utf8_decode($body))){
															$credits = ceil($length/70);
														}else{
															$credits = ceil($length/160);
														}
														$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
														$this->data['User']['id']=$usersms['User']['id'];
														$this->User->save($this->data);
													}
													$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
													if(!empty($groupContacts)){
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
														$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
														$this->GroupSmsBlast->save($GroupSmsBlast_arr);
													}
													app::import('Model','User');
													$this->User = new User();
													$users= $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
													if($users['User']['email_alert_credit_options']==0){
														if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
															if($users['User']['sms_credit_balance_email_alerts']==0){
																$username = $users['User']['username'];
																$email = $users['User']['email'];
																$phone = $users['User']['assigned_number'];
																$subject="Low SMS Credit Balance";	
																$sitename=str_replace(' ','',SITENAME);
																$this->Email->to = $email;	
																$this->Email->subject = $subject;
																$this->Email->from = $sitename;
																$this->Email->template = 'low_sms_credit_template';
																$this->Email->sendAs = 'html';
																$this->Email->Controller->set('username', $username);
																$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
																$this->Email->send();
																$this->User->id = $users['User']['id'];
																$this->User->saveField('sms_credit_balance_email_alerts',1);
															}	
														}
													}
												}
												if($status!=0){
													$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
													app::import('Model','GroupSmsBlast');
													$this->GroupSmsBlast = new GroupSmsBlast();
													$this->GroupSmsBlast->id = $group_sms_id;
													$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
													$this->GroupSmsBlast->save($this->data);
												}
											//}
										}
									}
								}
							}
						}
					}
				}
			} 
		}
	}
	
	function sendbirthday(){
		$this->autoRender = false;
		app::import('Model','Birthday');		
		$this->Contact = new Contact();
		app::import('Model','Contact');		
		$this->Birthday = new Birthday();
		app::import('Model','Log');
		$this->Log = new Log();
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		app::import('Model','User');
		$this->User = new User();
		$birthdaydata = $this->Birthday->find('all');
		if(!empty($birthdaydata)){
			foreach($birthdaydata as $birthday){
				$Responderid = $birthday['Birthday']['id']; 
				$group_id = $birthday['Birthday']['group_id'];
				$sms_type = $birthday['Birthday']['sms_type'];
				$image_url = $birthday['Birthday']['image_url'];
				$message = $birthday['Birthday']['message'];
				$systemmsg = $birthday['Birthday']['systemmsg'];
				$user_id = $birthday['Birthday']['user_id'];
				$responderdays = $birthday['Birthday']['days'];
				$user_arr=$this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
				$timezone=$user_arr['User']['timezone'];
                                $active=$user_arr['User']['active'];
				date_default_timezone_set($timezone);
				if($birthday['Birthday']['sms_type']==2){
					if($user_arr['User']['mms']==1){
						$assigned_number = $user_arr['User']['assigned_number'];
					}else{
						app::import('Model','UserNumber');
						$this->UserNumber = new UserNumber();
						$mmsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
						if(!empty($mmsnumber)){
							$assigned_number = $mmsnumber['UserNumber']['number'];
						}else{
							$assigned_number = $user_arr['User']['assigned_number'];
						}
					}
				}else{
					if(!empty($user_arr)){
						if($user_arr['User']['sms']==1){
							$assigned_number=$user_arr['User']['assigned_number'];
						}else{
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
							if(!empty($user_numbers)){	
								$assigned_number=$user_numbers['UserNumber']['number'];
							}else{
								$assigned_number=$user_arr['User']['assigned_number'];
							}
						}
					}
				}
				if($assigned_number !='' && $active == 1){
					if(isset($group_id)){
						$tomorrow1 = mktime(0,0,0,date("m"),date("d")+$responderdays,date("Y"));
						$birthday_month=date("m", $tomorrow1);
						$birthday_date=date("d", $tomorrow1);
						//$contacts_group = $this->ContactGroup->find('all', array('conditions' => array('Group.bithday_enable'=>1,'ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms != '=>0,'MONTH(Contact.birthday)'=>$birthday_month)));

                                                $contacts_group = $this->ContactGroup->find('all', array('conditions' => array('Group.bithday_enable'=>1,'ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0,'MONTH(Contact.birthday)'=>$birthday_month)));

						if(!empty($contacts_group)){
						
							foreach($contacts_group as $contacts_groups){
								$contacts = $this->Contact->find('all', array('conditions' => array('Contact.id'=>$contacts_groups['ContactGroup']['contact_id'],'MONTH(Contact.birthday)'=>$birthday_month,'DAY(Contact.birthday)'=>$birthday_date)));
								if(!empty($contacts)){
									$totalSubscriber = count($contacts);
									if($totalSubscriber > 0){
										$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										$smsbalance = $users['User']['sms_balance'];
										$body = $message." ".$systemmsg;
										$length = strlen(utf8_decode(substr($body,0,160))); 
										if($sms_type==2){ 
											$contactcredits = 2; 
										}else{
											if (strlen($body ) != strlen(utf8_decode($body ))){
												$contactcredits = ceil($length/70);
											}else{
												$contactcredits = ceil($length/160);
											}
										}
										if($smsbalance >= ($totalSubscriber * $contactcredits)){
											if(API_TYPE==0){
												$group_sms_id='';
												foreach ($contacts as $contact){
														if($group_sms_id==''){
															app::import('Model','GroupSmsBlast');
															$this->GroupSmsBlast = new GroupSmsBlast();
															$this->data['GroupSmsBlast']['user_id'] =$user_id;
															$this->data['GroupSmsBlast']['group_id'] =$group_id;
															$this->data['GroupSmsBlast']['responder'] =1;
															$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
															$this->GroupSmsBlast->save($this->data);
															$group_sms_id = $this->GroupSmsBlast->id;
														}
														$to = $contact['Contact']['phone_number'];
														$space_pos = strpos($contact['Contact']['name'],' ');
														if($space_pos!=''){
															$contact_name=substr($contact['Contact']['name'],0,$space_pos);
														}else{
															$contact_name = $contact['Contact']['name'];
														}
														$newmessage =str_replace('%%Name%%',$contact_name,$message);
														$from = $assigned_number;
														$body = $newmessage."\n".$systemmsg;
														$Status='';
														if($sms_type  == 1){
															$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
															$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
															//$body = $this->process($body);
															$response = $this->Twilio->sendsms($to,$from,$body);
															$smsid=$response->ResponseXml->Message->Sid;
															$Status=$response->ResponseXml->RestException->Status;
														}else if($sms_type  == 2){
															$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
															$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;						
															$message_arr=explode(',',$image_url);
															//$body = $this->process($body);
															$response = $this->Mms->sendmms($to,$from,$message_arr,$body);
															$smsid=$response->sid;
															if($smsid==''){
																$ErrorMessage = $response;
																$Status='400';
															}
														}
														$logArr['id'] ='';
														$logArr['group_sms_id'] =$group_sms_id;
														$logArr['sms_id'] = $smsid;
														$logArr['group_id'] = $group_id;
														$logArr['user_id'] = $users['User']['id'];
														$logArr['phone_number'] =$contact['Contact']['phone_number'];
														if($sms_type==2){
															$logArr['text_message'] = $body;
															$logArr['image_url'] = $image_url;	
														}else{
															$logArr['text_message'] = $body;
														}
														$logArr['route'] = 'outbox';
														$logArr['sms_status']= '';
														$logArr['error_message']='';
														if($Status==400){
															$logArr['sms_status']= 'failed';
															if(isset($response->ErrorMessage)){
																$ErrorMessage = $response->ErrorMessage;
															}else{			
																$ErrorMessage = $ErrorMessage;
															}
															$logArr['error_message']=$ErrorMessage;
														}
														$this->Log->save($logArr);
														if($Status==400){
															$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
															app::import('Model','GroupSmsBlast');
															$this->GroupSmsBlast = new GroupSmsBlast();
															$this->GroupSmsBlast->id = $group_sms_id;
															$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
															$this->GroupSmsBlast->save($this->data);
														}
												}
											}else if(API_TYPE==2){
												$group_sms_id='';
												foreach ($contacts as $contact){
													if($group_sms_id==''){
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$this->data['GroupSmsBlast']['user_id'] =$user_id;
														$this->data['GroupSmsBlast']['group_id'] =$group_id;
														$this->data['GroupSmsBlast']['responder'] =1;
														$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
														$this->GroupSmsBlast->save($this->data);
														$group_sms_id = $this->GroupSmsBlast->id;
													}
													$to = $contact['Contact']['phone_number'];
													$from = $assigned_number;
													$space_pos = strpos($contact['Contact']['name'],' ');
													if($space_pos!=''){
														$contact_name=substr($contact['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $contact['Contact']['name'];
													}
													$newmessage =str_replace('%%Name%%',$contact_name,$message);
													$body = $newmessage."\n".$systemmsg;
													$response = $this->Slooce->mt($user_arr['User']['api_url'],$user_arr['User']['partnerid'],$user_arr['User']['partnerpassword'],$to,$user_arr['User']['keyword'],$body);
													$message_id = '';
													$status = '';
													if(isset($response['id'])){
														if($response['result']=='ok'){
															$message_id = $response['id'];
														}
														$status = $response['result'];
													}
													$logArr['id'] ='';
													$logArr['group_sms_id'] =$group_sms_id;
													$logArr['sms_id'] = $message_id;
													$logArr['group_id'] = $group_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$contact['Contact']['phone_number'];
													$logArr['text_message'] = $body;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if($status!='ok'){	
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $status;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
													if($message_id!=''){
														app::import('Model','User');
														$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
														if(!empty($usersms)){
															$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-1;
															$this->data['User']['id']=$usersms['User']['id'];
															$this->User->save($this->data);
														}
														$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
														if(!empty($groupContacts)){
															app::import('Model','GroupSmsBlast');
															$this->GroupSmsBlast = new GroupSmsBlast();
															$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
															$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
															$this->GroupSmsBlast->save($GroupSmsBlast_arr);
														}
														app::import('Model','User');
														$this->User = new User();
														$users= $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
														if($users['User']['email_alert_credit_options']==0){
															if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
																if($users['User']['sms_credit_balance_email_alerts']==0){
																	$username = $users['User']['username'];
																	$email = $users['User']['email'];
																	$phone = $users['User']['assigned_number'];
																	$subject="Low SMS Credit Balance";	
																	$sitename=str_replace(' ','',SITENAME);
																	$this->Email->to = $email;	
																	$this->Email->subject = $subject;
																	$this->Email->from = $sitename;
																	$this->Email->template = 'low_sms_credit_template';
																	$this->Email->sendAs = 'html';
																	$this->Email->Controller->set('username', $username);
																	$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
																	$this->Email->send();
																	$this->User->id = $users['User']['id'];
																	$this->User->saveField('sms_credit_balance_email_alerts',1);
																}	
															}
														}
													}
													if($status!='ok'){	
														$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$this->GroupSmsBlast->id = $group_sms_id;
														$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
														$this->GroupSmsBlast->save($this->data);
													}
												}
											}else if(API_TYPE==3){
												$group_sms_id='';
												foreach ($contacts as $contact){
													if($group_sms_id==''){
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$this->data['GroupSmsBlast']['user_id'] =$user_id;
														$this->data['GroupSmsBlast']['group_id'] =$group_id;
														$this->data['GroupSmsBlast']['responder'] =1;
														$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
														$this->GroupSmsBlast->save($this->data);
														$group_sms_id = $this->GroupSmsBlast->id;
													}
													$to = $contact['Contact']['phone_number'];
													$from = $assigned_number;
													$space_pos = strpos($contact['Contact']['name'],' ');
													if($space_pos!=''){
														$contact_name=substr($contact['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $contact['Contact']['name'];
													}
													$newmessage =str_replace('%%Name%%',$contact_name,$message);
													$body = $newmessage."\n".$systemmsg;
													$this->Plivo->AuthId =PLIVO_KEY;
													$this->Plivo->AuthToken =PLIVO_TOKEN;
													sleep(1);
													//$body = $this->process($body);
													$response = $this->Plivo->sendsms($to,$from,$body);
													$errortext = '';
													$message_id = '';
													if(isset($response['response']['error'])){
														$errortext = $response['response']['error'];
													}
													if(isset($response['response']['message_uuid'][0])){
														$message_id = $response['response']['message_uuid'][0];
													}
													$logArr['id'] ='';
													$logArr['group_sms_id'] =$group_sms_id;
													$logArr['sms_id'] = $message_id;
													$logArr['group_id'] = $group_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$contact['Contact']['phone_number'];
													$logArr['text_message'] = $body;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if(isset($response['response']['error'])){
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $errortext;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
													if($message_id!=''){
														app::import('Model','User');
														$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
														if(!empty($usersms)){
                                                            $length = strlen(utf8_decode(substr($body,0,160))); 
															if (strlen($body) != strlen(utf8_decode($body))){
		                                                        $credits = ceil($length/70);
                                                            }else{
                                                                $credits = ceil($length/160);
															}
															//$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-1;
                                                            $this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
															$this->data['User']['id']=$usersms['User']['id'];
															$this->User->save($this->data);
														}
														$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
														if(!empty($groupContacts)){
															app::import('Model','GroupSmsBlast');
															$this->GroupSmsBlast = new GroupSmsBlast();
															$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
															$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
															$this->GroupSmsBlast->save($GroupSmsBlast_arr);
														}
														app::import('Model','User');
														$this->User = new User();
														$users= $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
														if($users['User']['email_alert_credit_options']==0){
															if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
																if($users['User']['sms_credit_balance_email_alerts']==0){
																	$username = $users['User']['username'];
																	$email = $users['User']['email'];
																	$phone = $users['User']['assigned_number'];
																	$subject="Low SMS Credit Balance";	
																	$sitename=str_replace(' ','',SITENAME);
																	$this->Email->to = $email;	
																	$this->Email->subject = $subject;
																	$this->Email->from = $sitename;
																	$this->Email->template = 'low_sms_credit_template';
																	$this->Email->sendAs = 'html';
																	$this->Email->Controller->set('username', $username);
																	$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
																	$this->Email->send();
																	$this->User->id = $users['User']['id'];
																	$this->User->saveField('sms_credit_balance_email_alerts',1);
																}	
															}
														}
													}
													if(isset($response['response']['error'])){
														$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$this->GroupSmsBlast->id = $group_sms_id;
														$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
														$this->GroupSmsBlast->save($this->data);
													}
												}
											}else{
												$group_sms_id='';
												foreach ($contacts as $contact){
													if($group_sms_id==''){
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$this->data['GroupSmsBlast']['user_id'] =$user_id;
														$this->data['GroupSmsBlast']['group_id'] =$group_id;
														$this->data['GroupSmsBlast']['responder'] =1;
														$this->data['GroupSmsBlast']['totals'] =$totalSubscriber;
														$this->GroupSmsBlast->save($this->data);
														$group_sms_id = $this->GroupSmsBlast->id;
													}
													$to = $contact['Contact']['phone_number'];
													$from = $assigned_number;
													$space_pos = strpos($contact['Contact']['name'],' ');
													if($space_pos!=''){
														$contact_name=substr($contact['Contact']['name'],0,$space_pos);
													}else{
														$contact_name = $contact['Contact']['name'];
													}
													$newmessage =str_replace('%%Name%%',$contact_name,$message);
													$body = $newmessage."\n".$systemmsg;
													$this->Nexmomessage->Key = NEXMO_KEY;
													$this->Nexmomessage->Secret = NEXMO_SECRET;
													sleep(1);
													//$body = $this->process($body);
													$responsemsg = $this->Nexmomessage->sendsms($to,$from,$body);
													foreach($responsemsg->messages  as $doc){
														$message_id= $doc->messageid;
														if($message_id!=''){
															$status= $doc->status;
															$message_id= $doc->messageid;
														}else{
															$status= $doc->status;
															$errortext= $doc->errortext;
														}
													}
													$logArr['id'] ='';
													$logArr['group_sms_id'] =$group_sms_id;
													$logArr['sms_id'] = $message_id;
													$logArr['group_id'] = $group_id;
													$logArr['user_id'] = $users['User']['id'];
													$logArr['phone_number'] =$contact['Contact']['phone_number'];
													$logArr['text_message'] = $body;
													$logArr['route'] = 'outbox';
													$logArr['sms_status']= '';
													$logArr['error_message']='';
													if($status!=0){
														$logArr['sms_status']= 'failed';
														$ErrorMessage = $errortext;
														$logArr['error_message']=$ErrorMessage;
													}
													if($message_id!=''){
														$logArr['sms_status']= 'sent';
													}
													$this->Log->save($logArr);
													if($message_id!=''){
														app::import('Model','User');
														$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
														if(!empty($usersms)){
                                                            $length = strlen(utf8_decode(substr($body,0,160))); 
															if (strlen($body) != strlen(utf8_decode($body))){
		                                                        $credits = ceil($length/70);
                                                            }else{
                                                                $credits = ceil($length/160);
															}
															//$this->data['User']['sms_balance']=$usersms['User']['sms_balance']-1;
                                                            $this->data['User']['sms_balance']=$usersms['User']['sms_balance']-$credits;
															$this->data['User']['id']=$usersms['User']['id'];
															$this->User->save($this->data);
														}
														$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
														if(!empty($groupContacts)){
															app::import('Model','GroupSmsBlast');
															$this->GroupSmsBlast = new GroupSmsBlast();
															$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
															$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
															$this->GroupSmsBlast->save($GroupSmsBlast_arr);
														}
														app::import('Model','User');
														$this->User = new User();
														$users= $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
														if($users['User']['email_alert_credit_options']==0){
															if($users['User']['sms_balance'] <= $users['User']['low_sms_balances']){
																if($users['User']['sms_credit_balance_email_alerts']==0){
																	$username = $users['User']['username'];
																	$email = $users['User']['email'];
																	$phone = $users['User']['assigned_number'];
																	$subject="Low SMS Credit Balance";	
																	$sitename=str_replace(' ','',SITENAME);
																	$this->Email->to = $email;	
																	$this->Email->subject = $subject;
																	$this->Email->from = $sitename;
																	$this->Email->template = 'low_sms_credit_template';
																	$this->Email->sendAs = 'html';
																	$this->Email->Controller->set('username', $username);
																	$this->Email->Controller->set('low_sms_balances', $users['User']['low_sms_balances']);
																	$this->Email->send();
																	$this->User->id = $users['User']['id'];
																	$this->User->saveField('sms_credit_balance_email_alerts',1);
																}	
															}
														}
													}
													if($status!=0){
														$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
														app::import('Model','GroupSmsBlast');
														$this->GroupSmsBlast = new GroupSmsBlast();
														$this->GroupSmsBlast->id = $group_sms_id;
														$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
														$this->GroupSmsBlast->save($this->data);
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}	
	}
	function updateclicks($cronjob=0){
		$this->autoRender = false;
		app::import('Model','Shortlink');
		$this->Shortlink = new Shortlink();
		$Shortlinkdetails = $this->Shortlink->find('all',array('order' =>array('Shortlink.url' => 'asc')));
		if(!empty($Shortlinkdetails)){
			if(BITLY_ACCESS_TOKEN !=''){
				foreach($Shortlinkdetails as $Shortlinkdetail){
				$access_token =BITLY_ACCESS_TOKEN;
				$url = 'https://api-ssl.bitly.com/v3/link/clicks?access_token='.$access_token.'&link='.$Shortlinkdetail['Shortlink']['short_url'].'';
				$result =  file_get_contents($url);
				$jsonresponse = json_decode($result);
				$status_code = $jsonresponse->status_code;
				$link_clicks = $jsonresponse->data->link_clicks;	
					if($status_code==200){
						$data['Shortlink']['id']=$Shortlinkdetail['Shortlink']['id'];
						$data['Shortlink']['clicks']=$link_clicks;
						$this->Shortlink->save($data);
					}
				}
			}
		}
                		
		if ($cronjob==0){
                     $this->Session->setFlash('Clicks have been refreshed');
		     $this->redirect(array('controller' =>'users', 'action'=>'shortlinks'));
		}
	}

    function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
	
	function loyaltycron(){
		$this->autoRender = false;
		app::import('Model','Smsloyalty');
		$this->Smsloyalty = new Smsloyalty();
		$loyalty_arr= $this->Smsloyalty->find('all');
		if(!empty($loyalty_arr)){
			foreach($loyalty_arr as $loyalty){
				$couponcode = $this->couponcode(6);
				$loyalty_update['Smsloyalty']['id'] = $loyalty['Smsloyalty']['id'];
				$loyalty_update['Smsloyalty']['coupancode'] =$couponcode;
				if($this->Smsloyalty->save($loyalty_update)){
					if($loyalty['Smsloyalty']['notify_punch_code']==1){
						if($loyalty['Smsloyalty']['my_email_address']==1){
							$username = $loyalty['User']['username'];
                                                        $program_name = $loyalty['Smsloyalty']['program_name'];
							$email = $loyalty['User']['email'];
							$subject="New Daily Punch Code";	
							$sitename=str_replace(' ','',SITENAME);
							$this->Email->to = $email;	
							$this->Email->subject = $subject;
							$this->Email->from = $sitename;
							$this->Email->template = 'new_coupon_code';
							$this->Email->sendAs = 'html';
							$this->Email->Controller->set('username', $username);
							$this->Email->Controller->set('couponcode', $couponcode);
                            $this->Email->Controller->set('programname', $program_name);
							$this->Email->send();
						}
						if($loyalty['Smsloyalty']['email_address']==1){
							$username = $loyalty['User']['username'];
                            $program_name = $loyalty['Smsloyalty']['program_name'];
							$email = $loyalty['Smsloyalty']['email_address_input'];
							$subject="New Daily Punch Code";	
							$sitename=str_replace(' ','',SITENAME);
							$this->Email->to = $email;	
							$this->Email->subject = $subject;
							$this->Email->from = $sitename;
							$this->Email->template = 'new_coupon_code';
							$this->Email->sendAs = 'html';
							$this->Email->Controller->set('username', $username);
							$this->Email->Controller->set('couponcode', $couponcode);
                            $this->Email->Controller->set('programname', $program_name);
							$this->Email->send();
						}
						if($loyalty['Smsloyalty']['mobile_number']==1){
							$to = $loyalty['Smsloyalty']['mobile_number_input'];
							$from = $loyalty['User']['assigned_number'];
                            $program_name = $loyalty['Smsloyalty']['program_name'];
							$body = 'Here is the new daily Punch Code: '.$couponcode.' for loyalty program '.$program_name;
							$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$loyalty['Smsloyalty']['user_id'])));
							if(!empty($usersms)){
								$this->User->id = $loyalty['Smsloyalty']['user_id'];
								$sms_balance = $usersms['User']['sms_balance'] - 1;
								$this->User->saveField('sms_balance',$sms_balance);
							}
							if(API_TYPE==0){
								$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
								$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
								$this->Twilio->sendsms($to,$from,$body);
							}else if(API_TYPE==2){
								$response = $this->Slooce->mt($loyalty['User']['api_url'],$loyalty['User']['partnerid'],$loyalty['User']['partnerpassword'],$to,$loyalty['User']['keyword'],$body);
							}else if(API_TYPE==3){
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								$this->Plivo->sendsms($to,$from,$body);
							}else{
								$this->Nexmomessage->Key = NEXMO_KEY;
								$this->Nexmomessage->Secret = NEXMO_SECRET;
								sleep(1);
								$this->Nexmomessage->sendsms($to,$from,$body);
							}
						}
					}
				}
			}
		}
		
	}
	function couponcode($digits){
		$this->autoRender = false;
		srand ((double) microtime() * 10000000);
		$input = array("A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
		//$input = array("0","1","2","3","4","5","6","7","8","9");
		$random_generator="";// Initialize the string to store random numbers
		for($i=1;$i<$digits+1;$i++)	{ 
			// Loop the number of times of required digits

			if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
				$rand_index = array_rand($input);
				$random_generator .=$input[$rand_index]; // One char is added
			}else{
					$random_generator .=rand(1,7); // one number is added
			}
		} // end of for loop
		return $random_generator;
	} // end of function
}
?>