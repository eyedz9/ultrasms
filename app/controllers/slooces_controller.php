<?php
class SloocesController extends AppController {
	var $name = 'Users';
	var $components = array('Email','Slooce','RequestHandler');
	function sendsms($id=null){
		$this->autoRender=false;
		$userDetails = $this->getLoggedUserDetails();
		if($userDetails['User']['sms_balance'] > 0){
		   $to = ($this->data['slooces']['phone_number']) ? $this->data['slooces']['phone_number'] : $this->data['slooces']['phone'];
			$body = $this->data['slooces']['message'];
			$response = $this->Slooce->mt($userDetails['User']['api_url'],$userDetails['User']['partnerid'],$userDetails['User']['partnerpassword'],$to,$userDetails['User']['keyword'],$body);
			$message_id = '';
			$status = '';
			if(isset($response['id'])){
				if($response['result']=='ok'){
					$message_id = $response['id'];
				}
				$status = $response['result'];
			}
			//saving logs
			Controller::loadModel('Log');
			$this->Log->create();
			$this->data['Log']['sms_id'] =$message_id; 
			$this->data['Log']['user_id'] = $this->Session->read('User.id'); 
			$this->data['Log']['phone_number'] = $to; 
			$this->data['Log']['text_message'] = $body; 
			$this->data['Log']['route'] = 'outbox'; 
			if($status!='ok'){
				$this->data['Log']['sms_status']= 'failed';	
				$this->data['Log']['error_message']=$status;
			}else{
				$this->data['Log']['sms_status']= 'sent';
			}
			$this->Log->save($this->data);
			if($status !='ok'){
				$this->Session->setFlash(__('An unknown error occurred', true));
			if(!empty($id)){
				$this->redirect(array('controller' => 'groups'));
			}else{
				$this->redirect(array('controller' => 'contacts'));
			}
			}else if($message_id!=''){
			Controller::loadModel('User');
			$this->User->id = $this->Session->read('User.id'); 
				if($this->User->id!=''){
					$this->User->saveField('sms_balance', ($userDetails['User']['sms_balance']-1));
					app::import('Model','User');
					$this->User = new User();
					$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$userDetails['User']['id'])));
					if($usersbalance['User']['email_alert_credit_options']==0){
						if($usersbalance['User']['sms_balance'] <= $usersbalance['User']['low_sms_balances']){
							if($usersbalance['User']['sms_credit_balance_email_alerts']==0){
								$username = $usersbalance['User']['username'];
								$email = $usersbalance['User']['email'];
								//echo $phone = $usersmail['User']['assigned_number'];
								$subject="Low SMS Credit Balance";	
								$sitename=str_replace(' ','',SITENAME);
								$this->Email->to = $email;	
								$this->Email->subject = $subject;
								$this->Email->from = $sitename;
								$this->Email->template = 'low_sms_credit_template';
								$this->Email->sendAs = 'html';
								$this->Email->Controller->set('username', $username);
								$this->Email->Controller->set('low_sms_balances', $usersbalance['User']['low_sms_balances']);
								$this->Email->send();
								$this->User->id = $usersbalance['User']['id'];
								$this->User->saveField('sms_credit_balance_email_alerts',1);
							}	
						}
					}
				}
				$this->Session->setFlash(__('SMS message sent', true));
				if(!empty($id)){
					$this->redirect(array('controller' => 'groups'));
				}else{
					$this->redirect(array('controller' => 'contacts'));
				}
			}
		}else{
			$this->Session->setFlash(__('SMS Balance too low.', true));
		}
	}
	function sms(){
	sleep(1);
		$this->autoRender=false;
        $headers = apache_request_headers();
        $xml = file_get_contents('php://input');
        $xmlData = simplexml_load_string($xml);
		$user =$xmlData->user;
		$keyword =$xmlData->keyword;
		$content =$xmlData->content;
		$command =$xmlData->command;
		
		
		
			app::import('Model','User');
			$this->User = new User();
			$someone = $this->User->find('first', array('conditions' => array('User.keyword'=>''.trim($keyword).'')));
			
                        $sms_balance = $someone['User']['sms_balance'];	
                        $active = $someone['User']['active'];	
                        //if ($active == 0 || ($sms_balance < 1 && strtoupper(trim($command))!='Q' && strtoupper(trim($command))!='H')){
                        //   exit;
                        //}

                        if (($active == 0 || $sms_balance < 1) && (strtoupper(trim($command))!='Q' && strtoupper(trim($command))!='H')){
                           exit;
                        }

				$_REQUEST['text'] =trim($content);
				$_REQUEST['From'] =trim($user);
				$phone = $_REQUEST['From'];
				$user_id = $someone['User']['id'];
				app::import('Model','Group');
				$this->Group = new Group();
				
				if (($content != '') || ($command != '')) {
				  $group=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$_REQUEST['text'] ,array('Group.user_id'=>$someone['User']['id']))));
				 }else{
				$group=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>''.trim($keyword).'' ,array('Group.user_id'=>$someone['User']['id']))));
				}
				
				app::import('Model','ContactGroup');
				$this->ContactGroup = new ContactGroup();
				$contact=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.question_id >'=>0,'ContactGroup.user_id'=> $user_id),'order' =>array('ContactGroup.id' => 'desc')));
				$question_id=$contact['ContactGroup']['question_id'];
				app::import('Model','ContactGroup');
				$this->ContactGroup = new ContactGroup();
				$contestid=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.contest_id >'=>0,'ContactGroup.user_id'=> $user_id),'order' =>array('ContactGroup.id' => 'desc')));
				$contest_id=$contestid['ContactGroup']['contest_id'];

				$contactname_arr=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.user_id'=> $user_id),'order' =>array('ContactGroup.id' => 'desc')));
				$contactname = '';
				$contact_id = 0;
				if(!empty($contactname_arr)){
					$contactname=$contactname_arr['Contact']['name'];
					$contact_id=$contactname_arr['Contact']['id'];
				}
				app::import('Model','Contest');
				$this->Contest = new Contest();
				$contestkeywords = $this->Contest->find('first',array('conditions'=>array('Contest.id '=>$contest_id,'Contest.keyword'=>$_REQUEST['text'])));			
				app::import('Model','Option');
				$this->Option = new Option();
				$answers123=$this->Option->find('first',array('conditions'=>array('Option.question_id'=>$question_id,'Option.optionb'=>$_REQUEST['text'])));
				app::import('Model','Smsloyalty');
				$this->Smsloyalty = new Smsloyalty();
				$smsloyalty_arr=$this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.user_id'=>$user_id,'Smsloyalty.coupancode'=>strtoupper($_REQUEST['text']))));
				$timezone = $someone['User']['timezone'];			
				date_default_timezone_set($timezone);
				$checkmsgpart = explode(':',$_REQUEST['text']);
				$checkgroup = explode(' ',$checkmsgpart[0]);
				if(strtoupper($checkgroup[0])=='SEND'){
					$checkbroadcast=$this->User->find('first',array('conditions'=>array('User.id'=>$someone['User']['id'],'User.broadcast'=>''.trim($_REQUEST['From']).'')));
					if(!empty($checkbroadcast)){
						app::import('Model','Group');
						$this->Group = new Group();
						$groupbroadcast=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$checkgroup[1],array('Group.user_id'=>$someone['User']['id']))));
						$group_sms_id = 0;
						if(!empty($groupbroadcast)){
							
							$contactlist=$this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.group_id'=>$groupbroadcast['Group']['id'],'ContactGroup.un_subscribers'=>0,'ContactGroup.user_id'=> $user_id)));
							if(!empty($contactlist)){
								$credits = 0;
								$faildsms = 0;
								$totalSubscriber = count($contactlist);
								$sms_balance = $checkbroadcast['User']['sms_balance'];	
								if($sms_balance < $totalSubscriber){
									$message = "You do not have enough credits to broadcast this message to ".$groupbroadcast['Group']['group_name'];
									$response = $this->Slooce->mt($checkbroadcast['User']['api_url'],$checkbroadcast['User']['partnerid'],$checkbroadcast['User']['partnerpassword'],$_REQUEST['From'],$checkbroadcast['User']['keyword'],$message);
									$this->User->id = $someone['User']['id'];
									if($this->User->id!=''){
										$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-1));
									}
									$this->smsmail($someone['User']['id']);
									exit;
								}
								app::import('Model','GroupSmsBlast');
								$this->GroupSmsBlast = new GroupSmsBlast();
								$group_blast['GroupSmsBlast']['user_id'] =$someone['User']['id'];
								$group_blast['GroupSmsBlast']['group_id'] =$groupbroadcast['Group']['id'];
								$group_blast['GroupSmsBlast']['responder'] =1;
								$group_blast['GroupSmsBlast']['totals'] =$totalSubscriber;
								$this->GroupSmsBlast->save($group_blast);
								$group_sms_id = $this->GroupSmsBlast->id;
								foreach($contactlist as $contactlists){
										$tonumber = $contactlists['Contact']['phone_number'];
										$fromnumber = $_REQUEST['To'];
										$bodymsg = $checkmsgpart[1];
										$response = $this->Slooce->mt($checkbroadcast['User']['api_url'],$checkbroadcast['User']['partnerid'],$checkbroadcast['User']['partnerpassword'],$tonumber,$checkbroadcast['User']['keyword'],$bodymsg);
										$message_id = '';
										$status = '';
										if(isset($response['id'])){
											if($response['result']=='ok'){
												$message_id = $response['id'];
											}
											$status = $response['result'];
										}
										app::import('Model','GroupSmsBlast');
										$this->GroupSmsBlast = new GroupSmsBlast();
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
										if($message_id !=''){
											$credits = $credits + 1;
											if(!empty($groupContacts)){
												app::import('Model','GroupSmsBlast');
												$this->GroupSmsBlast = new GroupSmsBlast();
												$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
												$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
												$this->GroupSmsBlast->save($GroupSmsBlast_arr);
											}
										}else if($status !='ok'){
											if(!empty($groupContacts)){
												app::import('Model','GroupSmsBlast');
												$this->GroupSmsBlast = new GroupSmsBlast();
												$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
												$GroupSmsBlast_arr['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												$this->GroupSmsBlast->save($GroupSmsBlast_arr);
											}
										}
										
										$sms_id = '';
										if($message_id !=''){
											$sms_id  =$message_id;
											$sms_status = 'sent';
										}else{
											$sms_status = 'failed';
										}
										Controller::loadModel('Log');
										$this->Log->create();
										$log_ar['Log']['sms_id'] = $sms_id; 
										$log_ar['Log']['user_id'] = $someone['User']['id']; 
										$log_ar['Log']['group_id'] =$groupbroadcast['Group']['id'];
										$log_ar['Log']['group_sms_id'] = $group_sms_id; 
										$log_ar['Log']['phone_number'] =$tonumber; 
										$log_ar['Log']['text_message'] =$bodymsg;
										$log_ar['Log']['sms_status'] = $sms_status;
										$log_ar['Log']['error_message'] = $status;
										$log_ar['Log']['route'] = 'outbox'; 
										$this->Log->save($log_ar);
								}
								

								$message="Your SMS broadcast has been sent to ".$groupbroadcast['Group']['group_name'];
								$response = $this->Slooce->mt($checkbroadcast['User']['api_url'],$checkbroadcast['User']['partnerid'],$checkbroadcast['User']['partnerpassword'],$_REQUEST['From'],$checkbroadcast['User']['keyword'],$message);
								$credits = $credits + 1;
								$this->User->id = $someone['User']['id'];
								if($this->User->id!=''){
									$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-$credits));
								}
								$this->smsmail($someone['User']['id']);
							}
						}

					}
				}	
				if($someone['User']['birthday_wishes']==0){
					//$birthday_wishes = explode(':',$_REQUEST['text']);
                                       $birthday_wishes = $_REQUEST['text'];

                                       $tempDate = explode('-', $birthday_wishes);
                                       if (checkdate($tempDate[1], $tempDate[2], $tempDate[0])) {//checkdate(month, day, year)
                                          $bday = 1;
                                       } else {
                                          $bday = 0;
                                       }

			              //if((strtoupper($birthday_wishes[0])=='BIRTHDAY') || (strtoupper($birthday_wishes[0])=='BIRTH')){
                                      if($bday==1){
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$contact=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.user_id'=> $user_id),'order' =>array('ContactGroup.id' => 'desc')));
						if(!empty($contact)){
							app::import('Model','Contact');
							$this->Contact = new Contact();
							$cont['Contact']['id'] = $contact['Contact']['id'];
							//$cont['Contact']['birthday'] = trim($birthday_wishes[1]);
                                                        $cont['Contact']['birthday'] = trim($birthday_wishes);
							$this->Contact->save($cont);
							$someoneuser = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
							
							if($someoneuser['User']['sms_balance'] > 0){
								app::import('Model','User');
								$this->User = new User();
								$this->User->id = $someone['User']['id'];
								if($this->User->id!=''){
									$this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-1));
								}
								
								/*Controller::loadModel('Log');
								$this->Log->create();
								$this->data['Log']['user_id'] = $someone['User']['id']; 
								$this->data['Log']['phone_number'] = $_REQUEST['From']; 
								$this->data['Log']['text_message'] = $_REQUEST['text'];
								$this->data['Log']['sms_status'] = 'received';
								$this->data['Log']['route'] = 'inbox'; 
								$this->Log->save($this->data);*/
								$message='Thanks for your response.';
								$response = $this->Slooce->mt($someoneuser['User']['api_url'],$someoneuser['User']['partnerid'],$someoneuser['User']['partnerpassword'],$phone,$someoneuser['User']['keyword'],$message);
								$this->smsmail($someone['User']['id']);
							}
						}
					}
				}
				if($someone['User']['capture_email_name']==0){
					$capture_email_name = explode(':',$_REQUEST['text']);
					if((strtoupper($capture_email_name[0])=='EMAIL')){
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$contact=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.user_id'=> $user_id),'order' =>array('ContactGroup.id' => 'desc')));
						if(!empty($contact)){
							app::import('Model','Contact');
							$this->Contact = new Contact();
							$cont['Contact']['id'] = $contact['Contact']['id'];
							$cont['Contact']['email'] = $capture_email_name[1];
							$this->Contact->save($cont);
							$someoneuser = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
							if($someoneuser['User']['sms_balance'] > 0){
								app::import('Model','User');
								$this->User = new User();
								$this->User->id = $someone['User']['id'];
								if($this->User->id!=''){
									$this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-1));
								}
								
								/*Controller::loadModel('Log');
								$this->Log->create();
								$this->data['Log']['user_id'] = $someone['User']['id']; 
								$this->data['Log']['phone_number'] = $_REQUEST['From']; 
								$this->data['Log']['text_message'] = $_REQUEST['text'];
								$this->data['Log']['sms_status'] = 'received';
								$this->data['Log']['route'] = 'inbox'; 
								$this->Log->save($this->data);*/
							
								$message='Thanks for your response.';
								$response = $this->Slooce->mt($someoneuser['User']['api_url'],$someoneuser['User']['partnerid'],$someoneuser['User']['partnerpassword'],$phone,$someoneuser['User']['keyword'],$message);
								$this->smsmail($someone['User']['id']);
							}
						}
					
					}else if((strtoupper($capture_email_name[0])=='NAME')){
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$contact=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.user_id'=> $user_id),'order' =>array('ContactGroup.id' => 'desc')));
						if(!empty($contact)){
							app::import('Model','Contact');
							$this->Contact = new Contact();
							$cont['Contact']['id'] = $contact['Contact']['id'];
							$cont['Contact']['name'] = $capture_email_name[1];
							$this->Contact->save($cont);
							$someoneuser = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
							if($someoneuser['User']['sms_balance'] > 0){
								app::import('Model','User');
								$this->User = new User();
								$this->User->id = $someone['User']['id'];
								if($this->User->id!=''){
									$this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-1));
								}
								
								/*Controller::loadModel('Log');
								$this->Log->create();
								$this->data['Log']['user_id'] = $someone['User']['id']; 
								$this->data['Log']['phone_number'] = $_REQUEST['From']; 
								$this->data['Log']['text_message'] = $_REQUEST['text'];
														$this->data['Log']['sms_status'] = 'received';
								$this->data['Log']['route'] = 'inbox'; 
								$this->Log->save($this->data);*/
								
								$message=EMAIL_CAPTURE_MSG;
								$response = $this->Slooce->mt($someoneuser['User']['api_url'],$someoneuser['User']['partnerid'],$someoneuser['User']['partnerpassword'],$phone,$someoneuser['User']['keyword'],$message);
								$this->smsmail($someone['User']['id']);
							}
						}
					}
				}
				$smsloyalty_status=$this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.user_id'=>$user_id,'Smsloyalty.codestatus'=>strtoupper($_REQUEST['text']))));
				if(!empty($smsloyalty_status)){
					if($someone['User']['sms_balance'] > 0){
						$credits = 0;
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$contactgroupid=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.group_id'=>$smsloyalty_status['Smsloyalty']['group_id'],'ContactGroup.user_id'=> $user_id)));
						if(!empty($contactgroupid)){
							app::import('Model','SmsloyaltyUser');
							$this->SmsloyaltyUser = new SmsloyaltyUser();
							$loyaltyuser=$this->SmsloyaltyUser->find('first',array('conditions'=>array('SmsloyaltyUser.contact_id'=>$contactgroupid['ContactGroup']['contact_id'],'SmsloyaltyUser.sms_loyalty_id'=>$smsloyalty_status['Smsloyalty']['id']),'order' =>array('SmsloyaltyUser.created' => 'desc')));
							if(!empty($loyaltyuser)){
								$credits = 1;
								$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_status['Smsloyalty']['checkstatus']);
								$msg=str_replace('%%STATUS%%',$loyaltyuser['SmsloyaltyUser']['count_trial'],$message);
								$statusmsg=str_replace('%%GOAL%%',$smsloyalty_status['Smsloyalty']['reachgoal'],$msg);
                                $response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$statusmsg);	
                                $this->smsmail($someone['User']['id']);							
							}
						}else{
							$credits = 1;
							$message="You are not eligible to participate since you are not subscribed to our opt-in list. Please text in ".$smsloyalty_status['Group']['keyword']." to be added to our opt-in list.";	
							$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);	
							$this->smsmail($someone['User']['id']);
						}
						if($credits > 0){
							$update_user['User']['id'] = $someone['User']['id'];
							$update_user['User']['sms_balance'] = $someone['User']['sms_balance']-$credits;
							$this->User->save($update_user);
						}
					}
				}else if(!empty($smsloyalty_arr)){
					if($someone['User']['sms_balance'] > 0){
						$credits = 0;
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$contactgroupid=$this->ContactGroup->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.group_id'=>$smsloyalty_arr['Smsloyalty']['group_id'],'ContactGroup.user_id'=> $user_id)));
						if(!empty($contactgroupid)){
							$current_date = date('Y-m-d');
							if($smsloyalty_arr['Smsloyalty']['startdate'] > $current_date){
								$credits = 1;
								$message="Loyalty program ".$smsloyalty_arr['Smsloyalty']['program_name']." hasn't started yet. It begins on ".date('m/d/Y',strtotime($smsloyalty_arr['Smsloyalty']['startdate']))."";
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
							}else if($smsloyalty_arr['Smsloyalty']['enddate'] < $current_date){
								$credits = 1;
								$message="Loyalty program ".$smsloyalty_arr['Smsloyalty']['program_name']." ended on ".date('m/d/Y',strtotime($smsloyalty_arr['Smsloyalty']['enddate']))."";
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
								$this->smsmail($someone['User']['id']);
								
							}else{
								$currentdate = date('Y-m-d');
								app::import('Model','SmsloyaltyUser');
								$this->SmsloyaltyUser = new SmsloyaltyUser();
								$loyaltyuser=$this->SmsloyaltyUser->find('first',array('conditions'=>array('SmsloyaltyUser.contact_id'=>$contactgroupid['ContactGroup']['contact_id'],'SmsloyaltyUser.sms_loyalty_id'=>$smsloyalty_arr['Smsloyalty']['id'],'SmsloyaltyUser.redemptions'=>0),'order' =>array('SmsloyaltyUser.msg_date' => 'desc'))); 
								if(empty($loyaltyuser)){
									$loyalty_user['SmsloyaltyUser']['id'] = '';
									$loyalty_user['SmsloyaltyUser']['unique_key'] = $this->random_generator(10);
									$loyalty_user['SmsloyaltyUser']['user_id'] = $smsloyalty_arr['Smsloyalty']['user_id'];
									$loyalty_user['SmsloyaltyUser']['sms_loyalty_id'] = $smsloyalty_arr['Smsloyalty']['id'];
									$loyalty_user['SmsloyaltyUser']['contact_id'] =$contactgroupid['ContactGroup']['contact_id'];
									$loyalty_user['SmsloyaltyUser']['keyword'] =$_REQUEST['text'];
									$loyalty_user['SmsloyaltyUser']['count_trial'] =1;
									$loyalty_user['SmsloyaltyUser']['msg_date'] =$currentdate;
									$loyalty_user['SmsloyaltyUser']['created'] =date('Y-m-d H:i:s');
									if($this->SmsloyaltyUser->save($loyalty_user)){
										$credits = 1;
										$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['addpoints']);
										$msg=str_replace('%%STATUS%%',1,$message);
										$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$msg);	
										$this->smsmail($someone['User']['id']);
									}
								}else if($loyaltyuser['SmsloyaltyUser']['msg_date'] < $currentdate){
									$count_trial = $loyaltyuser['SmsloyaltyUser']['count_trial']+1;
									$loyalty_user['SmsloyaltyUser']['id'] = $loyaltyuser['SmsloyaltyUser']['id'];
									$loyalty_user['SmsloyaltyUser']['user_id'] = $smsloyalty_arr['Smsloyalty']['user_id'];
									$loyalty_user['SmsloyaltyUser']['sms_loyalty_id'] = $smsloyalty_arr['Smsloyalty']['id'];
									$loyalty_user['SmsloyaltyUser']['contact_id'] =$contactgroupid['ContactGroup']['contact_id'];
									$loyalty_user['SmsloyaltyUser']['keyword'] =$_REQUEST['text'];
									$loyalty_user['SmsloyaltyUser']['count_trial'] =$count_trial;
									$loyalty_user['SmsloyaltyUser']['msg_date'] =$currentdate;
									$loyalty_user['SmsloyaltyUser']['created'] =date('Y-m-d H:i:s');
									if($this->SmsloyaltyUser->save($loyalty_user)){
										$loyaltyuser_list=$this->SmsloyaltyUser->find('first',array('conditions'=>array('SmsloyaltyUser.contact_id'=>$contactgroupid['ContactGroup']['contact_id'],'SmsloyaltyUser.sms_loyalty_id'=>$smsloyalty_arr['Smsloyalty']['id'],'SmsloyaltyUser.redemptions'=>0),'order' =>array('SmsloyaltyUser.msg_date' => 'desc')));
										
										if($loyaltyuser_list['SmsloyaltyUser']['count_trial']==$smsloyalty_arr['Smsloyalty']['reachgoal']){
											$loyalty_user_arr['SmsloyaltyUser']['id'] = $loyaltyuser['SmsloyaltyUser']['id'];
											$loyalty_user_arr['SmsloyaltyUser']['is_winner'] =1;
											if($this->SmsloyaltyUser->save($loyalty_user_arr)){
												if($smsloyalty_arr['Smsloyalty']['type']==1){
													$credits = 1;
													$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['reachedatgoal']);
													$msg=str_replace('%%STATUS%%',$count_trial,$message);
													$redeem = "Click link to redeem ".SITE_URL."/users/redeem/".$loyaltyuser_list['SmsloyaltyUser']['unique_key']."";
													$sms = $msg.' '.$redeem;
													$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$sms);	
													$this->smsmail($someone['User']['id']);													
												}
											}
										}else{
											$credits = 1;
											$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['addpoints']);
											$msg=str_replace('%%STATUS%%',$count_trial,$message);
											$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$msg);	
											$this->smsmail($someone['User']['id']);
										}
									}
								}else if($loyaltyuser['SmsloyaltyUser']['is_winner'] ==1){
									$credits = 1;
									$message="You have already reached the goal of ".$smsloyalty_arr['Smsloyalty']['reachgoal']." points.";
									$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
									$this->smsmail($someone['User']['id']);
								}else{
									$credits = 1;
									$message="You already punched your card today. Stop in tomorrow for the new punch code.";
									$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);	
									$this->smsmail($someone['User']['id']);
								}
							}
							
						}else{
							$credits = 1;
							$message="You are not eligible to participate since you are not subscribed to our opt-in list. Please text in ".$smsloyalty_arr['Group']['keyword']." to be added to our opt-in list.";
							$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
							$this->smsmail($someone['User']['id']);
								
						}
						if($credits > 0){
							$update_user['User']['id'] = $someone['User']['id'];
							$update_user['User']['sms_balance'] = $someone['User']['sms_balance']-$credits;
							$this->User->save($update_user);
						}
					}
						
						
				}else if(strtoupper(trim($command))=='H'){
				
					app::import('Model','User');
					$this->User = new User();
					$user_id = $someone['User']['id'];
					$sms_balance = $someone['User']['sms_balance'];
					$update_user['User']['id']=$user_id;
					$update_user['User']['sms_balance']=$sms_balance-1;
					$this->User->save($update_user);
					$companyname = $someone['User']['company_name'];
					$userphone = $this->format_phone($someone['User']['phone']);
					if(!empty($companyname)){
					   $message=$companyname.": News and promotions. For help call ".$userphone.". Ongoing msgs. Text STOP to cancel. Msg&Data Rates May Apply.";
					}else{
					   $message="News and promotions. For help call ".$userphone.". Ongoing msgs. Text STOP to cancel. Msg&Data Rates May Apply.";
					}
					$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
					$this->smsmail($someone['User']['id']);
				}
				
				if(strtoupper(trim($_REQUEST['text']))=='START'){
					app::import('Model','User');
					$this->User = new User();
					$user_id = $someone['User']['id'];
					$sms_balance = $someone['User']['sms_balance'];
					app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					$contactsstart=$this->ContactGroup->find('all',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.un_subscribers'=>1,'ContactGroup.user_id'=> $user_id)));
					if(!empty($contactsstart)){
						foreach($contactsstart as $contact){
							if($someone['User']['active']==1 || $someone['User']['sms_balance'] > 0){
								app::import('Model','Contact');
								$this->Contact = new Contact();
								$contact_id = $contact['Contact']['id'];
								$id = $contact['ContactGroup']['id'];
								$this->data['ContactGroup']['id']=$id;
								$this->data['ContactGroup']['contact_id']=$contact_id;
								$this->data['ContactGroup']['un_subscribers']=0;
								$this->data['ContactGroup']['created'] = date('Y-m-d H:i:s',time());
								if($this->ContactGroup->save($this->data)){
									$contact_arr['Contact']['id'] =$contact_id; 
									$contact_arr['Contact']['un_subscribers'] =0; 
									if($this->Contact->save($contact_arr)){
										app::import('Model','Group');
										$this->Group = new Group();
										$this->data['Group']['id']=$contact['Group']['id'];		
										$this->data['Group']['totalsubscriber']=$contact['Group']['totalsubscriber']+1;
										$this->Group->save($this->data);
									}
								}
							}
						}
						app::import('Model','User');
						$this->User = new User();
						$savedata['User']['id']=$user_id;
						$savedata['User']['sms_balance']=$sms_balance-1;
						$this->User->save($savedata);
						$message='You have successfully been re-subscribed. Text STOP to cancel. Msg&Data Rates May Apply.';
						$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
						$this->smsmail($someone['User']['id']);
					}
				}else if(strtoupper(trim($command))=='Q'){
				
					app::import('Model','User');
					$this->User = new User();
					$user_id = $someone['User']['id'];
					$sms_balance = $someone['User']['sms_balance'];
					app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					$companyname = $someone['User']['company_name'];
					$contacts=$this->ContactGroup->find('all',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.un_subscribers'=>0,'ContactGroup.user_id'=> $user_id)));
					if(!empty($contacts)){
						foreach($contacts as $contact){
							if($someone['User']['active']==1 || $someone['User']['sms_balance'] > 0){
								app::import('Model','Contact');
								$this->Contact = new Contact();
								$contact_id = $contact['Contact']['id'];
								$id = $contact['ContactGroup']['id'];
								$this->data['ContactGroup']['id']=$id;
								$this->data['ContactGroup']['contact_id']=$contact_id;
								$this->data['ContactGroup']['un_subscribers']=1;
								$this->data['ContactGroup']['created'] = date('Y-m-d H:i:s',time());
								if($this->ContactGroup->save($this->data)){
									$contact_arr['Contact']['id'] =$contact_id; 
									$contact_arr['Contact']['un_subscribers'] =1; 
									if($this->Contact->save($contact_arr)){
										app::import('Model','Group');
										$this->Group = new Group();	
										$this->data['Group']['id']=$contact['Group']['id'];
										$this->data['Group']['totalsubscriber']=$contact['Group']['totalsubscriber']-1;
										$this->Group->save($this->data);
									}
								}
							}
						}
						app::import('Model','User');
						$this->User = new User();
						$this->data['User']['id']=$user_id;
						$this->data['User']['sms_balance']=$sms_balance-1;
						$this->User->save($this->data);
						$userphone = $this->format_phone($someone['User']['phone']);
						if(!empty($companyname)){
					           $message=$companyname.": You have opted out successfully. For help call ".$userphone.". No more messages will be sent";
					        }else{
					           $message="You have opted out successfully. For help call ".$userphone.". No more messages will be sent";
					        }
						$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
						$this->smsmail($someone['User']['id']);
						exit;
					}
				}else if(!empty($group)){
					$keyword=$_REQUEST['text'];
					$group_id = $group['Group']['id'];
					$group_name = $group['Group']['group_name'];
					$totalsubscriber = $group['Group']['totalsubscriber'];
					$sms_type = $group['Group']['sms_type'];	  
					$system_message = $group['Group']['system_message'];
					$auto_message = $group['Group']['auto_message'];
					$image_url = $group['Group']['image_url'];
					$group_type = $group['Group']['group_type'];
					app::import('Model','Contact');
					$this->Contact = new Contact();
					$contact=$this->Contact->find('first',array('conditions'=>array('Contact.phone_number'=>$phone,'Contact.user_id'=> $user_id)));
					if($someone['User']['sms_balance'] > 0){
						if(empty($contact)){
                                                        if(NUMVERIFY !=''){
                                                            $numbervalidation = $this->validateNumber($phone);
                                                            $errorcode = $numbervalidation['error']['code'];
                                                     
                                                            if($errorcode == ''){
                                                               $this->data['Contact']['carrier'] = $numbervalidation['carrier'];
                                                               $this->data['Contact']['location'] = $numbervalidation['location'];
                                                               $this->data['Contact']['phone_country'] = $numbervalidation['country_name'];
                                                               $this->data['Contact']['line_type'] = $numbervalidation['line_type'];
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
							$this->data['Contact']['phone_number'] = $phone;
							$this->data['Contact']['user_id'] = $user_id;
							$this->data['Contact']['created'] = date('Y-m-d H:i:s',time());
                                                        $this->data['Contact']['color']=$this->choosecolor();
							$this->Contact->save($this->data);
							$contact_id = $this->Contact->id;
						}else{
							$contact_id = $contact['Contact']['id'];
						}
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$contactgroupid=$this->ContactGroup->find('first',array('conditions'=>array('ContactGroup.contact_id'=>$contact_id,'ContactGroup.group_id'=>$group_id,'ContactGroup.user_id'=> $user_id)));
						if(!empty($contactgroupid)){
							if($contactgroupid['ContactGroup']['un_subscribers']==1){	
								$this->data['ContactGroup']['id'] = $contactgroupid['ContactGroup']['id'];
								$this->data['ContactGroup']['un_subscribers'] = 0;
								$this->data['ContactGroup']['subscribed_by_sms'] = 1;
								$this->data['ContactGroup']['created'] = date('Y-m-d H:i:s',time());
								$this->ContactGroup->save($this->data);

                                                                app::import('Model','Contact');
                                                                $this->Contact = new Contact();

                                                                $contact_arr['Contact']['id'] =$contact_id; 
                                                                $contact_arr['Contact']['un_subscribers'] =0; 
                                                                $this->Contact->save($contact_arr);

								if($someone['User']['email_alert_options']==0){
									if($someone['User']['email_alerts']==1){
										$username = $someone['User']['username'];
										$email = $someone['User']['email'];
										$date=date('Y-m-d H:i:s',time());
										$subject="New Subscriber to ".$group_name;	
										$sitename=str_replace(' ','',SITENAME);
										$this->Email->to = $email;	
										$this->Email->subject = $subject;
										$this->Email->from = $sitename;
										$this->Email->template = 'new_subscriber_template';
										$this->Email->sendAs = 'html';
										$this->Email->Controller->set('username', $username);
										$this->Email->Controller->set('phoneno', $phone);
										$this->Email->Controller->set('groupname', $group_name);
										$this->Email->Controller->set('keyword', $keyword);
										$this->Email->Controller->set('datetime', $date);
										$this->Email->send();
									}
								}
								app::import('Model','Group');
								$this->Group = new Group();
								$this->data['Group']['id']=$contactgroupid['Group']['id'];
								$this->data['Group']['totalsubscriber']=$contactgroupid['Group']['totalsubscriber']+1;
								$this->Group->save($this->data);
								$message= $system_message.' '.$auto_message;
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
								$this->Immediatelyresponder($user_id,$group_id,$phone);
								if(!empty($someone['User']['id'])){
									$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
									if(!empty($users_sms_balance)){
										$credits = $users_sms_balance['User']['sms_balance'];
										$user_balance['User']['sms_balance']=$credits-1;
										$user_balance['User']['id']=$someone['User']['id'];
										$this->User->save($user_balance);
									}
								}
								$this->smsmail($someone['User']['id']);
								
								if($someone['User']['capture_email_name']==0){
									$capture_email_name=NAME_CAPTURE_MSG;
									$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$capture_email_name);
									$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
									if(!empty($someone_users)){
										$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
										$user_credit['User']['id']=$user_id;
										$this->User->save($user_credit); 
									}
								}
								if($group['Group']['bithday_enable']==1){		
									if($someone['User']['birthday_wishes']==0){
										$birthday_wishes=BIRTHDAY_MSG;
										$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$birthday_wishes);
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
                                                                   $message = "New Subscriber Alert: ".$phone." has joined group ".$groupname;

                                                                   $response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$mobile,$someone['User']['keyword'],$message);
						
						                   $someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));

                                                                   if(!empty($someone_users)){
							              $user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
							              $user_credit['User']['id']=$user_id;
							              $this->User->save($user_credit); 
						                   }
                                                               }
								
							}else{
								if(!empty($someone['User']['id'])){
									$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
									if(!empty($users_sms_balance)){
										$user_balance['User']['sms_balance']=$users_sms_balance['User']['sms_balance']-1;
										$user_balance['User']['id']=$someone['User']['id'];
										$this->User->save($user_balance);
									}
								}
								$this->smsmail($someone['User']['id']);  
								if($group_type==0){
									$message= $system_message.' '.$auto_message;
									$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
									$this->Immediatelyresponder($user_id,$group_id,$phone);
									$this->smsmail($someone['User']['id']); 

                                                                        $name = $contactgroupid['Contact']['name'];
                                                                        $email = $contactgroupid['Contact']['email'];
                                                                        $bday = $contactgroupid['Contact']['birthday'];
									
									
									if($someone['User']['capture_email_name']==0 && $name ==''){
										$capture_email_name=NAME_CAPTURE_MSG;
										$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$capture_email_name);
										$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
										if(!empty($someone_users)){
											$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
											$user_credit['User']['id']=$user_id;
											$this->User->save($user_credit); 
										}
									}
									if($group['Group']['bithday_enable']==1 && $bday == '0000-00-00'){	
										if($someone['User']['birthday_wishes']==0){
											$birthday_wishes=BIRTHDAY_MSG;
											$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$birthday_wishes);
											$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
											if(!empty($someone_users)){
												$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
												$user_credit['User']['id']=$user_id;
												$this->User->save($user_credit); 
											}
										
										}
									}
									
								}else{
								   /*$message='You are already subscribed to this list.';
								   $response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
								   $this->smsmail($someone['User']['id']); */
								}
							}  
						}else{
							app::import('Model','ContactGroup');
							$this->ContactGroup = new ContactGroup();
							$this->data['ContactGroup']['user_id'] = $user_id;;
							$this->data['ContactGroup']['contact_id'] = $contact_id;
							$this->data['ContactGroup']['group_id'] = $group_id;
							$this->data['ContactGroup']['group_subscribers'] = $keyword;
							$this->data['ContactGroup']['subscribed_by_sms'] = 1;
							$this->data['ContactGroup']['created'] = date('Y-m-d H:i:s',time());
							$this->ContactGroup->save($this->data);
							if($someone['User']['email_alert_options']==0){
								if($someone['User']['email_alerts']==1){
									$username = $someone['User']['username'];
									$email = $someone['User']['email'];
									$date=date('Y-m-d H:i:s',time());
									$subject="New Subscriber to ".$group_name;	
									$sitename=str_replace(' ','',SITENAME);
									$this->Email->to = $email;	
									$this->Email->subject = $subject;
									$this->Email->from = $sitename;
									$this->Email->template = 'new_subscriber_template';
									$this->Email->sendAs = 'html';
									$this->Email->Controller->set('username', $username);
									$this->Email->Controller->set('phoneno', $phone);
									$this->Email->Controller->set('groupname', $group_name);
									$this->Email->Controller->set('keyword', $keyword);
									$this->Email->Controller->set('datetime', $date);
									$this->Email->send();
								}
							}
							app::import('Model','Group');
							$this->Group = new Group();
							$this->data['Group']['id']=$group_id;
							$this->data['Group']['totalsubscriber']=$totalsubscriber+1;
							$this->Group->save($this->data);
							$message= $system_message.' '.$auto_message;
							if($sms_type == 1){	
								$message= $system_message.' '.$auto_message;
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
								$this->Immediatelyresponder($user_id,$group_id,$phone,$fromnumber);
							}	
							if(!empty($someone['User']['id'])){
								$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
								$credits = $users_sms_balance['User']['sms_balance'];
								if(!empty($users_sms_balance)){
									$this->data['User']['sms_balance']=$credits-1;
									$this->data['User']['id']=$someone['User']['id'];
									$this->User->save($this->data);
								}
							}	
							if($someone['User']['capture_email_name']==0){
								$capture_email_name=NAME_CAPTURE_MSG;
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$capture_email_name);
								$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
								$credits = $users_sms_balance['User']['sms_balance'];
								if(!empty($users_sms_balance)){
									$this->data['User']['sms_balance']=$credits-1;
									$this->data['User']['id']=$someone['User']['id'];
									$this->User->save($this->data);
								}
							}
							if($group['Group']['bithday_enable']==1){	
								if($someone['User']['birthday_wishes']==0){
									$birthday_wishes=BIRTHDAY_MSG;
									$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$birthday_wishes);
									
									$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
									$credits = $users_sms_balance['User']['sms_balance'];
									if(!empty($users_sms_balance)){
										$this->data['User']['sms_balance']=$credits-1;
										$this->data['User']['id']=$someone['User']['id'];
										$this->User->save($this->data);
									}
								}
							}	

                                                        if ($group['Group']['notify_signup']==1){
                                                                   $mobile = $group['Group']['mobile_number_input'];
                                                                   $groupname = $group['Group']['group_name'];
                                                                   $message = "New Subscriber Alert: ".$phone." has joined group ".$groupname;

                                                                   $response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$mobile,$someone['User']['keyword'],$message);
						
						                   $someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));

                                                                   if(!empty($someone_users)){
							              $user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
							              $user_credit['User']['id']=$user_id;
							              $this->User->save($user_credit); 
						                   }
                                                         }						
							$this->smsmail($someone['User']['id']);
						}
					}
				}elseif(!empty($answers123)){
					$answers_id=$answers123['Option']['id'];
					$autorsponder_message=$answers123['Option']['autorsponder_message'];
					if(!empty($contact)){
						$contact_id = $contact['Contact']['id'];
						app::import('Model','AnswerSubscriber');
						$this->AnswerSubscriber = new AnswerSubscriber();
						$ansersubs=$this->AnswerSubscriber->find('first',array('conditions'=>array('AnswerSubscriber.contact_id'=>$contact_id,'AnswerSubscriber.question_id'=>$question_id)));
						$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
						if($usersbalance['User']['sms_balance'] > 0){
							if(empty($ansersubs)){
								if($answers_id != ''){
									$this->Option->id = $answers_id;
									$this->data['Option']['count'] = $answers123['Option']['count']+1;
									$this->Option->save($this->data);
								}
								$this->data['AnswerSubscriber']['answer_id']=$answers_id;
								$this->data['AnswerSubscriber']['question_id']=$question_id;
								$this->data['AnswerSubscriber']['contact_id']=$contact_id;
								$this->data['AnswerSubscriber']['created']=date('Y-m-d H:i:s',time());
								$this->AnswerSubscriber->save($this->data);
								app::import('Model','User');
								$this->User = new User();
								$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								$credits = $users['User']['sms_balance'];
								$this->data['User']['sms_balance']=$credits-1;
								$this->data['User']['id']=$user_id;
								$this->User->save($this->data); 
								//$this->smsmail($users['User']['id']);
								if($autorsponder_message!=''){
									$message=$autorsponder_message;
								}else{
									$message= $answers123['Question']['autoreply_message'];
								}
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
							}else{
								app::import('Model','User');
								$this->User = new User();
								$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								$credits = $users['User']['sms_balance'];
								$this->data['User']['sms_balance']=$credits-1;
								$this->data['User']['id']=$user_id;
								$this->User->save($this->data);
								$message= "You have already voted in this poll";
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message);
								$this->smsmail($users['User']['id']); 
							}
						}
					}
				}else if(!empty($contestkeywords)){
					app::import('Model','ContestSubscriber');
					$this->ContestSubscriber = new ContestSubscriber();
					$contestdetails=$this->ContestSubscriber->find('first',array('conditions'=>array('ContestSubscriber.phone_number'=>$phone,'ContestSubscriber.contest_id'=>$contest_id)));
					if(empty($contestdetails)){
						$this->data['ContestSubscriber']['user_id'] = $contestkeywords['Contest']['user_id']; 
						$this->data['ContestSubscriber']['contest_id'] = $contestkeywords['Contest']['id']; 
						$this->data['ContestSubscriber']['phone_number'] = $phone; 
						$this->ContestSubscriber->save($this->data);
						app::import('Model','Contest');
						$this->Contest = new Contest();
						$totalsubscriberdata=$this->Contest->find('first',array('conditions'=>array('Contest.id'=>$contest_id)));
						$Contestdata['id']=$contest_id;
						$Contestdata['totalsubscriber']=$totalsubscriberdata['Contest']['totalsubscriber']+1;
						$this->Contest->save($Contestdata);
						app::import('Model','User');
						$this->User = new User();
						$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$contestkeywords['Contest']['user_id'])));
						$credits = $usersbalance['User']['sms_balance'];
						$this->data['User']['sms_balance']=$credits-1;
						$this->data['User']['id']=$user_id;
						$this->User->save($this->data); 
						$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$contestkeywords['Contest']['system_message']);
						$this->smsmail($user_id);
					}else{
						app::import('Model','User');
						$this->User = new User();
						$usersdetails = $this->User->find('first', array('conditions' => array('User.id'=>$contestkeywords['Contest']['user_id'])));
						$credits = $usersdetails['User']['sms_balance'];
						$this->data['User']['sms_balance']=$credits-1;
						$this->data['User']['id']=$user_id;
						$this->User->save($this->data);
						$message1= "You have already entered into this contest";
						$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$phone,$someone['User']['keyword'],$message1);
						$this->smsmail($user_id);
					}
				}else{
					Controller::loadModel('Log');
					$this->Log->create();
					$log['Log']['user_id'] = $someone['User']['id']; 
					$log['Log']['phone_number'] = trim($user); 
					$log['Log']['name'] = $contactname;
					$log['Log']['contact_id'] = $contact_id;
					$log['Log']['inbox_type'] = 1;
					$log['Log']['text_message'] = trim($content); 
					$log['Log']['created'] = date('Y-m-d H:i:s',time());
					$log['Log']['sms_status'] = 'received'; 
					$log['Log']['route'] = 'inbox';
					if($someone['User']['email_to_sms']==0){
						$random_generator = $this->random_generator(15);
						$this->Email->to =$someone['User']['email'];
						$subject='Incoming SMS to Email Notice-'.$random_generator;
						$this->Email->subject = $subject;
						$this->Email->from = SUPPORT_EMAIL;
						$this->Email->template = 'sendemail';
						$this->Email->sendAs = 'html';
						$this->set('phone',$_REQUEST['From']);
                                                $this->set('name',$contactname);
						$this->set('message',$_REQUEST['text']);
						$this->set('api_type',API_TYPE);
						$this->Email->send();
						$log['Log']['ticket'] =$random_generator;
					}
					
					$this->Log->save($log);
					if($contact_id > 0){
						app::import('Model','Contact');
						$this->Contact = new Contact();
						$contact_arra_save['Contact']['id'] =$contact_id;
						$contact_arra_save['Contact']['lastmsg'] =date('Y-m-d H:i:s');
						$this->Contact->save($contact_arra_save);
					}
					if($someone['User']['sms_balance'] > 0){
						if($someone['User']['incomingsms_alerts']==0){
							if($someone['User']['incomingsms_emailalerts']==1){
								$username = $someone['User']['username'];
								$email = $someone['User']['email'];
								$from = $_REQUEST['From']; 
								$date=date('Y-m-d H:i:s',time());
								$sitename=str_replace(' ','',SITENAME);
								$subject="New Incoming SMS To Your Account At ".SITENAME;
								$this->Email->to = $email;	
								$this->Email->subject = $subject;
								$this->Email->from = $sitename;
								$this->Email->template = 'incoming_sms_email_alert';
								$this->Email->sendAs = 'html';
								$this->Email->Controller->set('username', $username);
								$this->Email->Controller->set('from', $from);
                                                                $this->Email->Controller->set('name', $contactname);
								$this->Email->Controller->set('body', $_REQUEST['text']);
								$this->Email->send();
							}elseif ($someone['User']['incomingsms_emailalerts']==2){
								$this->User->id = $someone['User']['id'];
								if($this->User->id!=''){
									$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-1));
								}
								$this->Log = new Log();
								//$message = SITENAME." Incoming SMS Alert: " .$_REQUEST['text'];
								$message = "Incoming SMS Alert From: " .$_REQUEST['From']." - ".$_REQUEST['text'];
								$to = $someone['User']['smsalerts_number'];
								$from = $_REQUEST['To']; 
								$response = $this->Slooce->mt($someone['User']['api_url'],$someone['User']['partnerid'],$someone['User']['partnerpassword'],$to,$someone['User']['keyword'],$message);
								$message_id = '';
								$status = '';
								if(isset($response['id'])){
									if($response['result']=='ok'){
										$message_id = $response['id'];
									}
									$status = $response['result'];
								}
                                                                $this->data['Log']['contact_id'] = 0;
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								if($status!='ok'){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $status;
									$this->data['Log']['error_message']=$ErrorMessage;
								}
								if($message_id!=''){
									$this->data['Log']['sms_status']= 'sent';
								}
								$this->Log->save($this->data);
							}
						}
					}
				}
		
		
		/*echo "200";
		ob_start();
		echo "\nheaders:";
		print_r($headers);
		echo "\n_SERVER:";
		print_r($_SERVER);
		echo "\nxml:";
		print_r($xml);
		echo "\n\nxmlData:";
		print_r($xmlData);
		echo "\n_REQUEST:";
		print_r($_REQUEST);
		echo "\n_POST:";
		print_r($_POST);
		echo "\n_GET:";
		print_r($_GET);
		echo "\n_command:";
		print_r($command);
		print_r($group);
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/Sloocessms".time().".txt", "w");
		fwrite($file, $out1);
		fclose($file);*/
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
	function Immediatelyresponder($user_id=null,$group_id=null,$to=null){
		$this->autoRender=false;
		app::import('Model','Responder');
		$this->Responder = new Responder();
		app::import('Model','User');
		$this->User = new User();
		$response=$this->Responder->find('first',array('conditions'=>array('Responder.user_id'=>$user_id,'Responder.group_id'=>$group_id,'Responder.days'=>0)));
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
		if(!empty($response)){
	        if($users['User']['sms_balance'] > 0){
	  			$Responderid = $response['Responder']['id'];
			    $group_id = $response['Responder']['group_id'];
				$sms_type = $response['Responder']['sms_type'];
				$image_url = $response['Responder']['image_url'];
				$message = $response['Responder']['message'];
				$systemmsg = $response['Responder']['systemmsg'];
				$user_id = $response['Responder']['user_id'];
				$body = $message." ".$systemmsg;
				$response = $this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$to,$users['User']['keyword'],$body);
				$message_id = '';
				$status = '';
				if(isset($response['id'])){
					if($response['result']=='ok'){
						$message_id = $response['id'];
					}
					$status = $response['result'];
				}
				/*foreach($response->messages  as $doc){
					$message_id= $doc->messageid;
					if($message_id!=''){
						$status= $doc->status;
						$message_id= $doc->messageid;
					}else{
						$status= $doc->status;	
						$errortext= $doc->errortext;
					}
				}*/
				if($message_id!=''){	
					app::import('Model','User');
	                $this->User = new User();
					$users_sms = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
					$usersave['User']['id']=$user_id;
					$usersave['User']['sms_balance']=$users_sms['User']['sms_balance']-1;
					$this->User->save($usersave);
				}
	        }
		}
	}
	function random_generator($digits){
		srand ((double) microtime() * 10000000);
		$input = array("A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
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
	
	function format_phone($phone)
	{
    	$phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
    elseif(strlen($phone) == 11)
        return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3-$4", $phone);
    else
        return $phone;
}
}