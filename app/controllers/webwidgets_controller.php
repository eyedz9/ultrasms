<?php
class WebwidgetsController extends AppController {
	var $name  		= 'Webwidgets';
	var $layout		= 'default';
	var $components = array('Email','Twilio','Nexmomessage','Mms','Slooce','Plivo');
	var $uses  = array('Contact','Group','User');
	function index($file=null){
		$this->layout='admin_new_layout';
		$user_id=$this->Session->read('User.id');
		$groups=$this->Group->find('list',array('fields'=>array('Group.group_name'),'conditions'=>array('Group.user_id'=>$user_id)));
		$this->set('groups', $groups);
	}
	function subscribe(){
		$this->layout=null;
		$saved = false;
		$err = '';
		if (!empty($this->data)) {

                                             if(NUMVERIFY !=''){
                                                   $numbervalidation = $this->validateNumber($this->data['Contact']['phone_number']);
                                                   $errorcode = $numbervalidation['error']['code'];
                                                   $valid = $numbervalidation['valid'];
                                                   $linetype = $numbervalidation['line_type'];
                                                     
                                                   if($errorcode == ''){
                                                      if ($valid != 1){
                                                          $err = "The phone number you entered is not valid. Please provide a valid working phone number with country   code in the proper format. US format: 19999999999 UK format: 449999999999";
                                                          $this->set('err', $err);
		                                          $this->set('saved', $saved);
                                                      }else if (trim($linetype) != 'mobile'){
                                                          $err = "The line type of the number entered is ".$linetype.". You must provide a mobile number.";
                                                          $this->set('err', $err);
		                                          $this->set('saved', $saved);
                                                      }else {
                                                          $contact['carrier'] = $numbervalidation['carrier'];
                                                          $contact['location'] = $numbervalidation['location'];
                                                          $contact['phone_country'] = $numbervalidation['country_name'];
                                                          $contact['line_type'] = $numbervalidation['line_type'];
                                                      }
                                                   }else{
                                                      ob_start();
				                      print_r($numbervalidation['error']['info']);
		                                      $out1 = ob_get_contents();
		                                      ob_end_clean();
		                                      $file = fopen("debug/NumberVerifyAPI".time().".txt", "w");
		                                      fwrite($file, $out1); 
		                                      fclose($file);  

                                                   }
                                                }
                if ($err == ''){
			if(preg_match("/^[0-9]+$/", $this->data['Contact']['phone_number'])){
				if(API_TYPE==2){
					app::import('Model','Group');
					$this->Group = new Group();
					$group = $this->Group->find('first',array('conditions'=>array('Group.id'=>$this->data['Contact']['group_id'])));
					if(!empty($group)){
						$user_id=$group['Group']['user_id'];
						$users=$this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
						if(!empty($users)){
							$response = $this->Slooce->supported($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$this->data['Contact']['phone_number'],$users['User']['keyword']);
							if($response=='supported'){
								$sms_type=$group['Group']['sms_type'];
								$system_message=$group['Group']['system_message'];
								$auto_message=$group['Group']['auto_message'];
								$image_url=$group['Group']['image_url'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$group_id=$group['Group']['id'];
								$user_id=$group['Group']['user_id'];
								app::import('Model','Contact');
								$this->Contact = new Contact();
								$newsubscriber = $this->Contact->find('first',array('conditions' => array('Contact.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Contact']['phone_number']),'order' =>array('Contact.id' => 'desc')));
								if(empty($newsubscriber)){
									$this->Contact = new Contact();
									$contact['user_id']= $group['Group']['user_id'];
									$contact['name']=$this->data['Contact']['name'];
									$contact['phone_number']=$this->data['Contact']['phone_number'];	
                                                                        if(isset($this->data['Contact']['email'])){
                                                                           if($this->data['Contact']['email'] != ''){
                                                                              $contact['email']=$this->data['Contact']['email'];
                                                                           }
                                                                        }
                                                                        if(isset($this->data['Contact']['birthday'])){
                                                                           if($this->data['Contact']['birthday'] != ''){
						                              $contact['birthday']=$this->data['Contact']['birthday'];	
                                                                           }
                                                                        }	
                                                                        $contact['color']=$this->choosecolor();
									$this->Contact->save($contact);
									$contactArr = $this->Contact->getLastInsertId();
									//$contactArr = 0;
								}else{
									$contactArr = $newsubscriber['Contact']['id'];
								}
								
								if($contactArr!=''){
									app::import('Model','Group');
									$this->Group = new Group();
									$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id,'ContactGroup.user_id'=> $user_id)));
									$user_arr = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
									$timezone=$user_arr['User']['timezone'];
									date_default_timezone_set($timezone);
									app::import('Model','ContactGroup');
									if(empty($subscriber11)){
										$this->ContactGroup = new ContactGroup();
										$contactgroup['subscribed_by_sms'] = 2;
										$contactgroup['user_id'] = $user_id;
										$contactgroup['group_id'] = $group_id;
										$contactgroup['group_subscribers'] = $keywordname['Group']['keyword'];
										$contactgroup['contact_id'] = $contactArr;
										$date=date('Y-m-d H:i:s');
										$contactgroup['created'] = $date;
										$this->ContactGroup->save($contactgroup);
										if($user_arr['User']['email_alert_options']==0){
											if($user_arr['User']['email_alerts']==1){
												$username = $user_arr['User']['username'];
												$email = $user_arr['User']['email'];
												$subject="New Subscriber via Web Widget to ".$keywordname['Group']['group_name'];	
												$sitename=str_replace(' ','',SITENAME);
												$this->Email->to = $email;	
												$this->Email->subject = $subject;
												$this->Email->from = $sitename;
												$this->Email->template = 'new_subscriber_template';
												$this->Email->sendAs = 'html';
												$this->Email->Controller->set('username', $username);
												$this->Email->Controller->set('phoneno', $this->data['Contact']['phone_number']);
												$this->Email->Controller->set('groupname', $keywordname['Group']['group_name']);
												$this->Email->Controller->set('keyword', $keywordname['Group']['keyword']);
												$this->Email->Controller->set('datetime', $date);
												$this->Email->send();
											}
										}
										
										app::import('Model','Group');
										$this->Group = new Group();
										$groupsdata=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
										$groupArr['id'] = $group_id;
										$groupArr['totalsubscriber'] = $groupsdata['Group']['totalsubscriber']+1;
										$this->Group->save($groupArr);
										if($this->data['Contact']['autoresponse'] != ''){
											app::import('Model','User');
											$this->User = new User();
											$users =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
											if($users['User']['sms_balance'] > 0){
												$to = $this->data['Contact']['phone_number'];
												$body = $this->data['Contact']['autoresponse'];	
												$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$body);
												$this->Immediatelyresponder($user_id,$group_id,$to,$from);	
												//sleep(1);		
												if($user_arr['User']['capture_email_name']==0){		
													$capture_email_name=NAME_CAPTURE_MSG;	
													$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$capture_email_name);	
													$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));								
													if(!empty($someone_users)){		
														$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
														$user_credit['User']['id']=$user_id;				
														$this->User->save($user_credit); 				
													}							
												}							
												//sleep(1);	
												if($group['Group']['bithday_enable']==1){
													if($user_arr['User']['birthday_wishes']==0){	
														$birthday_wishes=BIRTHDAY_MSG;		
														$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$birthday_wishes);		
														$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
														if(!empty($someone_users)){				
															$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
															$user_credit['User']['id']=$user_id;						
															$this->User->save($user_credit); 				
														}									
													}
												}	
												if ($group['Group']['notify_signup']==1){
													$mobile = $group['Group']['mobile_number_input'];
													$groupname = $group['Group']['group_name'];
													$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
													$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$mobile,$users['User']['keyword'],$message);	
													$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
													if(!empty($someone_users)){
														$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
														$user_credit['User']['id']=$user_id;
														$this->User->save($user_credit); 
													}
												}												
											}
											$saved = true;
										}else{
											app::import('Model','User');
											$this->User = new User();
											$users =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
											if($users['User']['sms_balance'] > 0){
												$to = $this->data['Contact']['phone_number'];
												$message= $system_message.' '.$auto_message;
												$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$message);
												$this->Immediatelyresponder($user_id,$group_id,$to,$from);	
												//sleep(1);		
												if($user_arr['User']['capture_email_name']==0){		
													$capture_email_name=NAME_CAPTURE_MSG;	
													$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$capture_email_name);	
													$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));								
													if(!empty($someone_users)){		
														$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
														$user_credit['User']['id']=$user_id;				
														$this->User->save($user_credit); 				
													}							
												}							
												//sleep(1);	
												if($group['Group']['bithday_enable']==1){												
													if($user_arr['User']['birthday_wishes']==0){	
														$birthday_wishes=BIRTHDAY_MSG;		
														$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$birthday_wishes);		
														$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
														if(!empty($someone_users)){				
															$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
															$user_credit['User']['id']=$user_id;						
															$this->User->save($user_credit); 				
														}									
													}
												}	
												if ($group['Group']['notify_signup']==1){
													$mobile = $group['Group']['mobile_number_input'];
													$groupname = $group['Group']['group_name'];
													$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
													$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$mobile,$users['User']['keyword'],$message);	
						
						                            $someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));

                                                    if(!empty($someone_users)){
													   $user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
													   $user_credit['User']['id']=$user_id;
													   $this->User->save($user_credit); 
													}
                                                }		
											
											}
										$saved = true;
										}
									}else{
										app::import('Model','User');
										$this->User = new User();
										$users =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
										if($users['User']['sms_balance'] > 0){
											$to = $this->data['Contact']['phone_number'];
											$message= "You are already a subscriber with us";
											$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$message);
											$users_balance =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
											if(!empty($users_balance)){
												$credits = $users_balance['User']['sms_balance'];
												$userbalance['User']['sms_balance']=$credits-1;
												$userbalance['User']['id']=$group['Group']['user_id'];
												$this->User->save($userbalance); 
											}
											$saved = true;
										}
									}
								}
								$saved = true;
							}else{
								$err = "The specified phone number is not valid";
							}
						}else{
							$err = "User not found";
						}
					}else{
						$err = "Group not found";
					}
				}else{
					app::import('Model','Group');
					$this->Group = new Group();
					$group = $this->Group->find('first',array('conditions'=>array('Group.id'=>$this->data['Contact']['group_id'])));
					$sms_type=$group['Group']['sms_type'];
                                        $group_type=$group['Group']['group_type'];
					$system_message=$group['Group']['system_message'];
					$auto_message=$group['Group']['auto_message'];
					$image_url=$group['Group']['image_url'];
					app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					$group_id=$group['Group']['id'];
					$user_id=$group['Group']['user_id'];
					app::import('Model','Contact');
					$this->Contact = new Contact();
					$newsubscriber = $this->Contact->find('first',array('conditions' => array('Contact.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Contact']['phone_number']),'order' =>array('Contact.id' => 'desc')));
					if(empty($newsubscriber)){
						$this->Contact = new Contact();                                
						$contact['user_id']= $group['Group']['user_id'];
						$contact['name']=$this->data['Contact']['name'];
						$contact['phone_number']=$this->data['Contact']['phone_number'];	
                                                
                                                if(isset($this->data['Contact']['email'])){
                                                   if($this->data['Contact']['email'] != ''){
                                                      $contact['email']=$this->data['Contact']['email'];
                                                   }
                                                }
                                                if(isset($this->data['Contact']['birthday'])){
                                                   if($this->data['Contact']['birthday'] != ''){
						      $contact['birthday']=$this->data['Contact']['birthday'];	
                                                   }
                                                }
                                                $contact['color']=$this->choosecolor();
						$this->Contact->save($contact);
						$contactArr = $this->Contact->getLastInsertId();
					}else{
						$contactArr = $newsubscriber['Contact']['id'];
					}
					if($contactArr!=''){
						app::import('Model','Group');
						
						$this->Group = new Group();
						$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id,'ContactGroup.user_id'=> $user_id)));
						$user_arr = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
						$timezone=$user_arr['User']['timezone'];
						date_default_timezone_set($timezone);
						app::import('Model','ContactGroup');
						if(empty($subscriber11)){
							$this->ContactGroup = new ContactGroup();
							$contactgroup['subscribed_by_sms'] = 2;
							$contactgroup['user_id'] = $user_id;
							$contactgroup['group_id'] = $group_id;
							$contactgroup['group_subscribers'] = $keywordname['Group']['keyword'];
							$contactgroup['contact_id'] = $contactArr;
							$date=date('Y-m-d H:i:s');
							$contactgroup['created'] = $date;
							$this->ContactGroup->save($contactgroup);
							if($user_arr['User']['email_alert_options']==0){
								if($user_arr['User']['email_alerts']==1){
									$username = $user_arr['User']['username'];
									$email = $user_arr['User']['email'];
									$subject="New Subscriber via Web Widget to ".$keywordname['Group']['group_name'];	
									$sitename=str_replace(' ','',SITENAME);
									$this->Email->to = $email;	
									$this->Email->subject = $subject;
									$this->Email->from = $sitename;
									$this->Email->template = 'new_subscriber_template';
									$this->Email->sendAs = 'html';
									$this->Email->Controller->set('username', $username);
									$this->Email->Controller->set('phoneno', $this->data['Contact']['phone_number']);
									$this->Email->Controller->set('groupname', $keywordname['Group']['group_name']);
									$this->Email->Controller->set('keyword', $keywordname['Group']['keyword']);
									$this->Email->Controller->set('datetime', $date);
									$this->Email->send();
								}
							}
							app::import('Model','Group');
							$this->Group = new Group();
							$groupsdata=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
							$groupArr['id'] = $group_id;
							$groupArr['totalsubscriber'] = $groupsdata['Group']['totalsubscriber']+1;
							$this->Group->save($groupArr);
							if($this->data['Contact']['autoresponse'] != ''){
								app::import('Model','User');
								$this->User = new User();
								$users =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
								if(!empty($users)){
									if($users['User']['sms']==1){
										$assigned_number=$users['User']['assigned_number'];
									}else{
										app::import('Model','UserNumber');
										$this->UserNumber = new UserNumber();
										$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
										if(!empty($user_numbers)){	
											$assigned_number=$user_numbers['UserNumber']['number'];
										}else{
											$assigned_number=$users['User']['assigned_number'];
										}
									}
								}
								if($users['User']['sms_balance'] > 0){
									$to = $this->data['Contact']['phone_number'];
									$from = $assigned_number;
									$body1 = $this->data['Contact']['autoresponse'];	
									if($group_type==2){
										$address = $group['Group']['property_address'];
										$price = $group['Group']['property_price'];
										$bed = $group['Group']['property_bed'];
										$bath = $group['Group']['property_bath'];
										$description = $group['Group']['property_description'];
										$url = $group['Group']['property_url'];

										$message=$address."\n".$price."\nBed: ".$bed."\nBath: ".$bath."\n".$description."\n".$url."\n";
										$body=$message. $body1;
									}elseif ($group_type==3){
										$year = $group['Group']['vehicle_year'];
										$make = $group['Group']['vehicle_make'];
										$model = $group['Group']['vehicle_model'];
										$mileage = $group['Group']['vehicle_mileage'];
										$price = $group['Group']['vehicle_price'];
										$description = $group['Group']['vehicle_description'];
										$url = $group['Group']['vehicle_url'];

										$message=$year.' '.$make.' '.$model."\n".$mileage."\n".$price."\n".$description."\n".$url."\n";
										//$message=$message. $system_message.' '.$auto_message;
										$body=$message. $body1;
									}else{
										$body = $this->data['Contact']['autoresponse'];
                                    }
                                    $current_datetime=date("n/d/Y");
$body = str_replace('%%CURRENTDATE%%',$current_datetime,$body);
									if(API_TYPE==0){
										$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
										$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
										$response = $this->Twilio->sendsms($to,$from,$body);
										$this->Immediatelyresponder($user_id,$group_id,$to,$from);	
										sleep(1);		
										if($user_arr['User']['capture_email_name']==0){		
											$capture_email_name=NAME_CAPTURE_MSG;	
											$this->Twilio->sendsms($to,$from,$capture_email_name);		
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));								
											if(!empty($someone_users)){		
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;				
												$this->User->save($user_credit); 				
											}							
										}							
										sleep(1);
										if($group['Group']['bithday_enable']==1){										
											if($user_arr['User']['birthday_wishes']==0){	
												$birthday_wishes=BIRTHDAY_MSG;		
												$this->Twilio->sendsms($to,$from,$birthday_wishes);		
												$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
												if(!empty($someone_users)){				
													$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
													$user_credit['User']['id']=$user_id;						
													$this->User->save($user_credit); 				
												}									
											}
										}	

										if ($group['Group']['notify_signup']==1){
											$mobile = $group['Group']['mobile_number_input'];
											$groupname = $group['Group']['group_name'];
											$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
											$response = $this->Twilio->sendsms($mobile,$from,$message);
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
                                            if(!empty($someone_users)){
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;
												$this->User->save($user_credit); 
											}
										}									
									}else if(API_TYPE==3){
										$this->Plivo->AuthId =PLIVO_KEY;
										$this->Plivo->AuthToken =PLIVO_TOKEN;
										$response = $this->Plivo->sendsms($to,$from,$body);
										sleep(1);
										$this->Immediatelyresponder($user_id,$group_id,$to,$from);
										sleep(1);		
										if($user_arr['User']['capture_email_name']==0){		
											$capture_email_name=NAME_CAPTURE_MSG;	
											$this->Plivo->sendsms($to,$from,$capture_email_name);
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
											if(!empty($someone_users)){					
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;	
												$this->User->save($user_credit); 	
											}			
										}		
										sleep(1);
										if($group['Group']['bithday_enable']==1){
											if($user_arr['User']['birthday_wishes']==0){			
												$birthday_wishes=BIRTHDAY_MSG;		
												$this->Plivo->sendsms($to,$from,$birthday_wishes);		
												$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
												if(!empty($someone_users)){						
													$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;							
													$user_credit['User']['id']=$user_id;	
													$this->User->save($user_credit); 
												}
											}
										}
										if ($group['Group']['notify_signup']==1){
											$mobile = $group['Group']['mobile_number_input'];
											$groupname = $group['Group']['group_name'];
											$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
						                    $this->Plivo->sendsms($mobile,$from,$message);	
						                    $someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
                                            if(!empty($someone_users)){
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;
												$this->User->save($user_credit); 
											}
                                        }
									}else{		
										$this->Nexmomessage->Key = NEXMO_KEY;
										$this->Nexmomessage->Secret = NEXMO_SECRET;
										$response = $this->Nexmomessage->sendsms($to,$from,$body);
										sleep(1);
										$this->Immediatelyresponder($user_id,$group_id,$to,$from);
										sleep(1);		
										if($user_arr['User']['capture_email_name']==0){		
											$capture_email_name=NAME_CAPTURE_MSG;	
											$this->Nexmomessage->sendsms($to,$from,$capture_email_name);
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
											if(!empty($someone_users)){					
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;	
												$this->User->save($user_credit); 	
											}			
										}		
										sleep(1);
										if($group['Group']['bithday_enable']==1){
											if($user_arr['User']['birthday_wishes']==0){			
												$birthday_wishes=BIRTHDAY_MSG;		
												$this->Nexmomessage->sendsms($to,$from,$birthday_wishes);		
												$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
												if(!empty($someone_users)){						
													$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;							
													$user_credit['User']['id']=$user_id;	
													$this->User->save($user_credit); 
												}
											}
										}
										if ($group['Group']['notify_signup']==1){
											$mobile = $group['Group']['mobile_number_input'];
											$groupname = $group['Group']['group_name'];
											$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
						                    $this->Nexmomessage->sendsms($mobile,$from,$message);	
						                    $someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
                                            if(!empty($someone_users)){
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;
												$this->User->save($user_credit); 
											}
                                        }
									}
									$credits = $users['User']['sms_balance'];
                                    $length = strlen(utf8_decode(substr($body,0,1600)));
                                    if (strlen($body) != strlen(utf8_decode($body))){
		                                $dedcredits = ceil($length/70);
                                    }else{
										$dedcredits = ceil($length/160);
                                    }
									$this->data['User']['sms_balance']=$credits-$dedcredits;
									$this->data['User']['id']=$group['Group']['user_id'];
									$this->User->save($this->data);
									$saved = true;
                                    $this->smsmail($users['User']['id']);
								}					 
							}else{
								app::import('Model','User');
								$this->User = new User();		
								$users =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
								if($users['User']['sms_balance'] > 0){
									$to = $this->data['Contact']['phone_number'];
									$fromarr=array();
									if($group['Group']['sms_type']==2){
										$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$group['Group']['user_id'],'User.mms'=>1)));
										if(empty($usernumber)){
											app::import('Model','UserNumber');
											$this->UserNumber = new UserNumber();
											$mmsnumber = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$group['Group']['user_id'],'UserNumber.mms'=>1)));
											if(!empty($mmsnumber)){
												foreach($mmsnumber as $mmsnumbers){
													$fromarr[] = $mmsnumbers['UserNumber']['number'];
												}
											}else{
												$fromarr[] = $users['User']['assigned_number'];
											}
										}else{
											$fromarr[] = $usernumber['User']['assigned_number'];
										}
									}else{
										if(!empty($users)){
											if($users['User']['sms']==1){
												$fromarr[]=$users['User']['assigned_number'];
											}else{
												app::import('Model','UserNumber');
												$this->UserNumber = new UserNumber();
												$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
												if(!empty($user_numbers)){	
													$fromarr[]=$user_numbers['UserNumber']['number'];
												}else{
													$fromarr[]=$users['User']['assigned_number'];
												}
											}
										}
									}
									$random_keys= array_rand($fromarr,1);
									$from=$fromarr[$random_keys];
									//$message= $system_message.' '.$auto_message;
									if($group_type==2){
										$address = $group['Group']['property_address'];
										$price = $group['Group']['property_price'];
										$bed = $group['Group']['property_bed'];
										$bath = $group['Group']['property_bath'];
										$description = $group['Group']['property_description'];
										$url = $group['Group']['property_url'];

										$message=$address."\n".$price."\nBed: ".$bed."\nBath: ".$bath."\n".$description."\n".$url."\n";
										$message=$message. $system_message.' '.$auto_message;
									}elseif ($group_type==3){
										$year = $group['Group']['vehicle_year'];
										$make = $group['Group']['vehicle_make'];
										$model = $group['Group']['vehicle_model'];
										$mileage = $group['Group']['vehicle_mileage'];
										$price = $group['Group']['vehicle_price'];
										$description = $group['Group']['vehicle_description'];
										$url = $group['Group']['vehicle_url'];
										$message=$year.' '.$make.' '.$model."\n".$mileage."\n".$price."\n".$description."\n".$url."\n";
										$message=$message. $system_message.' '.$auto_message;

									}else{
										$message= $system_message.' '.$auto_message;
                                    }
                                    $current_datetime=date("n/d/Y");
$message = str_replace('%%CURRENTDATE%%',$current_datetime,$message);
									if(API_TYPE==0){
										if($sms_type == 1){	
											$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
											$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
											$this->Twilio->sendsms($to,$from,$message);
											$this->Immediatelyresponder($user_id,$group_id,$to,$from);
										}else if($sms_type == 2){
											$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
											$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;				
											$message_arr=explode(',',$image_url);
											$this->Mms->sendmms($to,$from,$message_arr,$message);
											$this->Immediatelyresponder($user_id,$group_id,$to,$from);
										}
										$credits = $users['User']['sms_balance'];
										$length = strlen(utf8_decode(substr($message,0,1600)));
										if (strlen($message) != strlen(utf8_decode($message))){
											$dedcredits = ceil($length/70);
										}else{
										   $dedcredits = ceil($length/160);
										}
										$this->data['User']['sms_balance']=$credits-$dedcredits;
										$this->data['User']['id']=$user_id;
										$this->User->save($this->data);    
										$saved = true;	
										sleep(1);		
										if($users['User']['capture_email_name']==0){	
											$capture_email_name=NAME_CAPTURE_MSG;							
											$this->Twilio->sendsms($to,$from,$capture_email_name);					
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
											if(!empty($someone_users)){						
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;			
												$this->User->save($user_credit); 			
											}								
										}				
										sleep(1);
										if($group['Group']['bithday_enable']==1){	
											if($users['User']['birthday_wishes']==0){		
												$birthday_wishes=BIRTHDAY_MSG;
												$this->Twilio->sendsms($to,$from,$birthday_wishes);	
												$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
												if(!empty($someone_users)){				
													$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
													$user_credit['User']['id']=$user_id;			
													$this->User->save($user_credit); 			
												}								
											}
										}

										if ($group['Group']['notify_signup']==1){
											$mobile = $group['Group']['mobile_number_input'];
											$groupname = $group['Group']['group_name'];
											$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
											$response = $this->Twilio->sendsms($mobile,$from,$message);
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
											if(!empty($someone_users)){
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;
												$this->User->save($user_credit); 
											}
										}	
									}else if(API_TYPE==3){
										$this->Plivo->AuthId =PLIVO_KEY;
										$this->Plivo->AuthToken =PLIVO_TOKEN;
										$response = $this->Plivo->sendsms($to,$from,$message);
										$errortext = '';
										$message_id = '';
										if(isset($response['response']['error'])){
											$errortext = $response['response']['error'];
										}
										if(isset($response['response']['message_uuid'][0])){
											$message_id = $response['response']['message_uuid'][0];
										}
										if($message_id !=''){
											$this->Immediatelyresponder($user_id,$group_id,$to,$from);
										}	
										$users_balance =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
										sleep(1);	
										//$creditdeduct = 1;
										$length = strlen(utf8_decode(substr($message,0,1600)));
										if (strlen($message) != strlen(utf8_decode($message))){
											$creditdeduct = ceil($length/70);
										}else{
										   $creditdeduct = ceil($length/160);
										}
										if($users_balance['User']['capture_email_name']==0){		
											$capture_email_name=NAME_CAPTURE_MSG;		
											$this->Plivo->sendsms($to,$from,$capture_email_name);
											$creditdeduct = $creditdeduct + 1;
										}								
										sleep(1);
										if($group['Group']['bithday_enable']==1){											
											if($users_balance['User']['birthday_wishes']==0){		
												$birthday_wishes=BIRTHDAY_MSG;	
												$this->Plivo->sendsms($to,$from,$birthday_wishes);		
												$creditdeduct = $creditdeduct + 1;		
											}
										}
										
										if(!empty($users_balance)){
											$credits = $users_balance['User']['sms_balance'];
											$userbalance['User']['sms_balance']=$credits-$creditdeduct;
											$userbalance['User']['id']=$user_id;
											$this->User->save($userbalance);  
										}					 
										$saved = true;
										if ($group['Group']['notify_signup']==1){
											$mobile = $group['Group']['mobile_number_input'];
											$groupname = $group['Group']['group_name'];
											$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
											$this->Plivo->sendsms($mobile,$from,$message);
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
                                            if(!empty($someone_users)){
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;
												$this->User->save($user_credit); 
						                    }
										}	
										$this->smsmail($users['User']['id']);
									}else{	
										$this->Nexmomessage->Key = NEXMO_KEY;
										$this->Nexmomessage->Secret = NEXMO_SECRET;
										$response = $this->Nexmomessage->sendsms($to,$from,$message);
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
										if(isset($status)){
											if($status==0){
												sleep(1);
												$this->Immediatelyresponder($user_id,$group_id,$to,$from);
											}
										}	
										$users_balance =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
										sleep(1);	
										//$creditdeduct = 1;
										$length = strlen(utf8_decode(substr($message,0,1600)));
										if (strlen($message) != strlen(utf8_decode($message))){
											$creditdeduct = ceil($length/70);
										}else{
										   $creditdeduct = ceil($length/160);
										}
										if($users_balance['User']['capture_email_name']==0){		
											$capture_email_name=NAME_CAPTURE_MSG;		
											$this->Nexmomessage->sendsms($to,$from,$capture_email_name);
											$creditdeduct = $creditdeduct + 1;
										}								
										sleep(1);
										if($group['Group']['bithday_enable']==1){											
											if($users_balance['User']['birthday_wishes']==0){		
												$birthday_wishes=BIRTHDAY_MSG;	
												$this->Nexmomessage->sendsms($to,$from,$birthday_wishes);		
												$creditdeduct = $creditdeduct + 1;		
											}
										}
										
										if(!empty($users_balance)){
											$credits = $users_balance['User']['sms_balance'];
											$userbalance['User']['sms_balance']=$credits-$creditdeduct;
											$userbalance['User']['id']=$user_id;
											$this->User->save($userbalance);  
										}					 
										$saved = true;
										if ($group['Group']['notify_signup']==1){
											$mobile = $group['Group']['mobile_number_input'];
											$groupname = $group['Group']['group_name'];
											$message = "New Subscriber Alert: ".$to." has joined group ".$groupname;
											$this->Nexmomessage->sendsms($mobile,$from,$message);
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
                                            if(!empty($someone_users)){
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;
												$this->User->save($user_credit); 
						                    }
										}	
										$this->smsmail($users['User']['id']);
									}
								} 
							}
						}else{
							app::import('Model','User');
							$this->User = new User();
							$users =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
							if(!empty($users)){
								if($users['User']['sms']==1){
									$assigned_number=$users['User']['assigned_number'];
								}else{
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();	
									$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
									if(!empty($user_numbers)){	
										$assigned_number=$user_numbers['UserNumber']['number'];
									}else{
										$assigned_number=$users['User']['assigned_number'];
									}
								}
							}
							if($users['User']['sms_balance'] > 0){
								$to = $this->data['Contact']['phone_number'];
								$from = $assigned_number;
								//$message= "You are already a subscriber with us";
								if($group_type==2){
									$message='We have already sent you information on this property.';
								}elseif ($group_type==3){
									$message='We have already sent you information on this vehicle.';
								}else{
									$message='You are already subscribed to this list.';
								}
								if(API_TYPE==0){
									$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
									$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
									$response = $this->Twilio->sendsms($to,$from,$message);
								}else if(API_TYPE==2){
									$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$message);
								}else if(API_TYPE==3){
									$this->Plivo->AuthId =PLIVO_KEY;
									$this->Plivo->AuthToken =PLIVO_TOKEN;
									$response = $this->Plivo->sendsms($to,$from,$message);									
								}else{	
									$this->Nexmomessage->Key = NEXMO_KEY;
									$this->Nexmomessage->Secret = NEXMO_SECRET;
									$response = $this->Nexmomessage->sendsms($to,$from,$message);
									$this->smsmail($users['User']['id']);
								}
								$users_balance =$this->User->find('first',array('conditions' => array('User.id'=>$group['Group']['user_id'])));
								if(!empty($users_balance)){
									$credits = $users_balance['User']['sms_balance'];
									$userbalance['User']['sms_balance']=$credits-1;
									$userbalance['User']['id']=$group['Group']['user_id'];
									$this->User->save($userbalance); 
								}
								$saved = true;
							}
						}
					}
					$saved = true;
				}				
			}else{
				$err = "'".$this->data['Contact']['phone_number']."' is not a valid phone number ";
		        }
                    }
       		} 

		$this->set('err', $err);
		$this->set('saved', $saved);
		$this->render('/webwidgets/subscribe');
	}
	function facebookwidget($group_id=NULL){
		$this->autoRender = false;
		Configure:: write('debug', 0);
		$this->layout = false;
		$font_family=array('A'=>'Arial','H'=>'Helvetica','T'=>'Times New Roman','V'=>'Verdana');
		$font_weight=array('N'=>'Normal','B'=>'Bold','I'=>'Italic','BI'=>'Bold+Italic');
		$user_id=$this->Session->read('User.id');
		$this->set('user_id',$user_id);
		$this->set('group_id',$group_id);
		$this->set('font_family',$font_family);
		$this->set('font_weight',$font_weight);
		$this->set('data',$_REQUEST);
		$this->render('facebookwidget');
	}
	function subscribefb($user_id=NULL,$group_id=NULL){
		echo '<pre>';
		echo 'user: '.$user_id.' group '.$group_id;
		$group = $this->Group->find('first',array('conditions'=>array('id'=>$group_id)));
		$this->data['Contact']['user_id'] = $user_id;
		$this->data['Contact']['msg_user_id'] = $user_id;
		$this->data['Contact']['contact_first_name'] = $_REQUEST['firstName'];
		$this->data['Contact']['contact_last_name'] = $_REQUEST['lastName'];
		$this->data['Contact']['phone'] = $_REQUEST['phonenumber'];
		$this->data['Contact']['source_id'] = 2;
		if ($this->Contact->save($this->data)) {
			$this->data['GroupsContact']['contact_id'] = $this->Contact->getLastInsertId();
			$this->data['GroupsContact']['group_id'] = $group_id;
			if($_REQUEST['autoresponder'] != ''){
				$this->loadModel('Config');
				$config = $this->Config->find('first');
				$to = $this->data['Contact']['phone'];
				$from = $config['Config']['sms_sender_phone'];
				$body = $_REQUEST['autoresponder'];
				//$response = $this->Twilio->sendsms($to,$from,$body);
			}
			if ($this->GroupsContact->save($this->data)) {	
				$saved = true;
			}
		}
		die;
	}
    function upload(){
		$this->layout=null;
		$filename=$_FILES['data']['name']['image'];
		$type=$_FILES['data']['type']['image'];
		$tmp_name=$_FILES['data']['tmp_name']['image'];
		if(!empty($filename)){
			$time=time();
			move_uploaded_file($tmp_name,"uploadimages/".$time.$filename);
			$siteurl=SITE_URL;
			$imagepath=$siteurl.'/uploadimages/'.$time.$filename;
			$this->set('imagename',$imagepath);
		}
	}
	function smsmail($user_id=null){
		$this->autoRender=false;
		app::import('Model','User');
		$this->User = new User();
		$usersmail = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
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
	}	
	function Immediatelyresponder($user_id=null,$group_id=null,$to=null,$from=null){
		$this->autoRender=false;
		app::import('Model','Responder');
		$this->Responder = new Responder();
		$response=$this->Responder->find('first',array('conditions'=>array('Responder.user_id'=>$user_id,'Responder.group_id'=>$group_id,'Responder.days'=>0)));
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
	        if($response['Responder']['sms_type']==2){
				if($users['User']['mms']==1){
					$assigned_number = $users['User']['assigned_number'];
				}else{
					app::import('Model','UserNumber');
					$this->UserNumber = new UserNumber();
					$mmsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
					if(!empty($mmsnumber)){
						$assigned_number = $mmsnumber['UserNumber']['number'];
					}else{
						$assigned_number = $users['User']['assigned_number'];
					}
				}
			}else{
	  			if(!empty($users)){
					if($users['User']['sms']==1){
						$assigned_number=$users['User']['assigned_number'];
					}else{
						app::import('Model','UserNumber');
						$this->UserNumber = new UserNumber();
						$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
						if(!empty($user_numbers)){	
							$assigned_number=$user_numbers['UserNumber']['number'];
						}else{
							$assigned_number=$users['User']['assigned_number'];
						}
					}
				}
			}
			if($assigned_number!=''){
				if(!empty($response)){
					$current_datetime=date("n/d/Y");
	                if($users['User']['sms_balance'] > 0)
						$Responderid = $response['Responder']['id'];
						$group_id = $response['Responder']['group_id'];
						$sms_type = $response['Responder']['sms_type'];
						$image_url = $response['Responder']['image_url'];
						$message = str_replace('%%CURRENTDATE%%',$current_datetime,$response['Responder']['message']);
						$systemmsg = $response['Responder']['systemmsg'];
						$user_id = $response['Responder']['user_id'];
						$body = $message." ".$systemmsg;
					if(API_TYPE==0){
						if($sms_type  == 1){
							$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
							$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
							$response = $this->Twilio->sendsms($to,$assigned_number,$body);
							$smsid=$response->ResponseXml->Message->Sid;
							$Status=$response->ResponseXml->RestException->Status;
                                                        $credits = 1;
						}else if($sms_type  == 2){
							$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
							$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;						
							$message_arr=explode(',',$image_url);
							$response = $this->Mms->sendmms($to,$assigned_number,$message_arr,$body);
							$smsid=$response->sid;
                            $credits = 2;
							if($smsid==''){
								$ErrorMessage = $response;
								$Status=400;
							}
						}
                        $usersave['User']['id']=$user_id;
						$usersave['User']['sms_balance']=$users['User']['sms_balance']-$credits;
						$this->User->save($usersave);

					}else if(API_TYPE==2){
						$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$body);
						$message_id = '';
						$status = '';
						if(isset($response['id'])){
							if($response['result']=='ok'){
								$message_id = $response['id'];
							}
							$status = $response['result'];
						}
						if($message_id!=''){
							$usersave['User']['id']=$user_id;
							$usersave['User']['sms_balance']=$users['User']['sms_balance']-1;
							$this->User->save($usersave);
						}
					}else if(API_TYPE==3){
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$response = $this->Plivo->sendsms($to,$assigned_number,$body);
						$errortext = '';
						$message_id = '';
						if(isset($response['response']['error'])){
							$errortext = $response['response']['error'];
						}
						if(isset($response['response']['message_uuid'][0])){
							$message_id = $response['response']['message_uuid'][0];
						}
						if($message_id!=''){
							$usersave['User']['id']=$user_id;
							$usersave['User']['sms_balance']=$users['User']['sms_balance']-1;
							$this->User->save($usersave);
						}
					}else{
						$this->Nexmomessage->Key = NEXMO_KEY;
						$this->Nexmomessage->Secret = NEXMO_SECRET;
						$response = $this->Nexmomessage->sendsms($to,$assigned_number,$body);
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
						if($message_id!=''){
							$usersave['User']['id']=$user_id;
							$usersave['User']['sms_balance']=$users['User']['sms_balance']-1;
							$this->User->save($usersave);
						}
					}
				}
			}
		}
	}