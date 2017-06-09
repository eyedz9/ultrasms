<?php
class PlivosController extends AppController {
	var $uses = 'Users';
	var $components = array('Email','Plivo');
	function searchcountry() {
		$this->layout = 'popup';
		if(PLIVOAPP_ID==''){
			$this->Plivo->AuthId =PLIVO_KEY;
			$this->Plivo->AuthToken =PLIVO_TOKEN;
			$response = $this->Plivo->create_application();
			if(isset($response['response']['app_id'])){
				app::import('Model','Config');		
				$this->Config=new Config();
				$config['Config']['id'] = 1;
				$config['Config']['plivoapp_id'] = $response['response']['app_id'];
				$this->Config->save($config);
			}
		}
	}
	function pattrenbuynumber() {
		$this->layout = 'popup';
		$country=$_REQUEST['country'];
		$services=$_REQUEST['services'];
		$this->set('services',$services);
		$this->set('country',$country);
	}
	function searchnumber() {
		$this->layout = 'popup';
		$this->Plivo->AuthId =PLIVO_KEY;
		$this->Plivo->AuthToken =PLIVO_TOKEN;
		$country_code=$_REQUEST['country'];
		$services=$_REQUEST['services'];
		$pattern=$_REQUEST['pattren'];
		$response = $this->Plivo->listNumbers($country_code,$pattern,$services);
		if($response['response']['meta']['total_count'] == 0) {
			$this->Session->setFlash(__('We did not find any phone numbers by that search', true));			 
		}else{
			$this->set('AvailablePhoneNumbers', $response['response']['objects']);
			$this->set('country_code', $country_code);
		}
	}
	function assignthisnumber() {
		$this->autoRender=false;
		$this->Plivo->AuthId =PLIVO_KEY;
		$this->Plivo->AuthToken =PLIVO_TOKEN;
		$country_code=$_REQUEST['country'];
		$PhoneNumber=$_REQUEST['number'];
		$siteurl=SITE_URL;
		$buy_phone_response = $this->Plivo->buy_phone_numbers($PhoneNumber);
		$sucess_response = $buy_phone_response['status'];
		if($sucess_response==201){
			$appid=PLIVOAPP_ID;
			$link_application_response = $this->Plivo->application_number($PhoneNumber,$appid);
			Controller::loadModel('User');
			$user_id=$this->Session->read('User.id');
			$someone = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
				if($someone['User']['assigned_number']==0){
					$this->User->id = $this->Session->read('User.id'); 
					$this->User->saveField('assigned_number', $_REQUEST['number']);
					$this->User->saveField('country_code', $_REQUEST['country']);
					$this->User->saveField('voice', $_REQUEST['voice']);
					$this->User->saveField('sms', $_REQUEST['sms']);
					$this->User->saveField('api_type', API_TYPE);
					$this->User->saveField('number_limit_count',1);
					echo 'sucess';
					$this->Session->setFlash(__('Number Assigned', true));
				}else{
					app::import('Model','UserNumber');
					$this->UserNumber = new UserNumber();
					$arr['UserNumber']['user_id']=$user_id;
					$arr['UserNumber']['number']=$_REQUEST['number'];
					$arr['UserNumber']['country_code']=$_REQUEST['country'];
					$arr['UserNumber']['sms']=$_REQUEST['sms'];
					$arr['UserNumber']['voice']=$_REQUEST['voice'];
					$arr['UserNumber']['api_type']=API_TYPE;
					$this->UserNumber->save($arr);
					if(!empty($someone)){
						$arr_number['User']['id']=$user_id;
						$arr_number['User']['number_limit_count']=$someone['User']['number_limit_count'] + 1;
						$this->User->save($arr_number);
					}
					echo 'sucess';
					$this->Session->setFlash(__('Number Assigned', true));
				}
				
		}else{
			echo 'error';
			$errorcode = $buy_phone_response['response']['error'];
			$this->Session->setFlash(__($errorcode, true));
		}
	}
	function sendsms($id=null){
		$this->autoRender=false;
		$userDetails = $this->getLoggedUserDetails();
		$this->Plivo->AuthId =PLIVO_KEY;
		$this->Plivo->AuthToken =PLIVO_TOKEN;
		if($userDetails['User']['sms_balance'] > 0){
		   $to = ($this->data['Plivo']['phone_number']) ? $this->data['Plivo']['phone_number'] : $this->data['Plivo']['phone'];
		  	if(!empty($userDetails)){
				if($userDetails['User']['sms']==1){
					$from=$userDetails['User']['assigned_number'];
				}else{
					app::import('Model','UserNumber');
		            $this->UserNumber = new UserNumber();
					$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$userDetails['User']['id'],'UserNumber.sms'=>1)));
					if(!empty($user_numbers)){	
						$from=$user_numbers['UserNumber']['number'];
			        }else{
						$from=$userDetails['User']['assigned_number'];
					}
			    }
			}
			$body = $this->data['Plivo']['message'];
			$response = $this->Plivo->sendsms($to,$from,$body);
			$errortext = '';
			$message_id = '';
			if(isset($response['response']['error'])){
				$errortext = $response['response']['error'];
			}
			if(isset($response['response']['message_uuid'][0])){
				$message_id = $response['response']['message_uuid'][0];
			}
			
			//saving logs
			Controller::loadModel('Log');
			$this->Log->create();
			$this->data['Log']['sms_id'] =$message_id; 
			$this->data['Log']['user_id'] = $this->Session->read('User.id'); 
			$this->data['Log']['phone_number'] = $to; 
			$this->data['Log']['text_message'] = $body; 
			$this->data['Log']['route'] = 'outbox'; 
			if(isset($response['response']['error'])){
				$this->data['Log']['sms_status']= 'failed';
				$this->data['Log']['error_message']=$errortext;
			}else{
				$this->data['Log']['sms_status']= 'sent';
			}
			$this->Log->save($this->data);
			if(isset($response['response']['error'])){
				$this->Session->setFlash(__($errortext, true));
			if(!empty($id)){
				$this->redirect(array('controller' => 'groups'));
			}else{
				$this->redirect(array('controller' => 'contacts'));
			}
			}else if($message_id!=''){
			Controller::loadModel('User');
			$this->User->id = $this->Session->read('User.id'); 
				if($this->User->id!=''){
					$length = strlen(utf8_decode(substr($body,0,160)));
					if (strlen($body) != strlen(utf8_decode($body))){
						$credits = ceil($length/70);
					}else{
					   $credits = ceil($length/160);
					}
                    $this->User->saveField('sms_balance', ($userDetails['User']['sms_balance']-$credits));
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
	
	        ob_start();
		/*print_r($_REQUEST);
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/plivosms".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);*/
		$this->autoRender=false;
		$_REQUEST['To'] = str_replace('+','',$_REQUEST['To']);
		$_REQUEST['From'] = str_replace('+','',$_REQUEST['From']);
		$fromnumber = str_replace('+','',$_REQUEST['To']);
		$phone = $_REQUEST['From'];
		$_REQUEST['text'] = trim($_REQUEST['Text']);
		app::import('Model','User');
	        $this->User = new User();
		$someone = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));
		}
		$sms_balance = $someone['User']['sms_balance'];	
		$active = $someone['User']['active'];
		if (($active == 0 || $sms_balance < 1) && strtoupper(trim($_REQUEST['text']))!='STOP'){
			exit;
		}
		$user_id = $someone['User']['id'];
		app::import('Model','Group');
		$this->Group = new Group();
		$group=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$_REQUEST['text'] ,array('Group.user_id'=>$someone['User']['id']))));

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
						$bodymsg = $checkmsgpart[1];
						$length = strlen(utf8_decode(substr($bodymsg,0,1600)));                                                                 
						if (strlen($bodymsg) != strlen(utf8_decode($bodymsg))){
							$contactcredits = ceil($length/70);
						}else{
							$contactcredits = ceil($length/160);
						}
						if($sms_balance < ($totalSubscriber * $contactcredits)){
							$message = "You do not have enough credits to broadcast this message to ".$groupbroadcast['Group']['group_name'];
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							$response = $this->Plivo->sendsms($_REQUEST['From'],$_REQUEST['To'],$message);
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
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							$tonumber = $contactlists['Contact']['phone_number'];
							$fromnumber = $_REQUEST['To'];
							$bodymsg = $checkmsgpart[1];
							sleep(1);
							$response = $this->Plivo->sendsms($tonumber,$fromnumber,$bodymsg);
							$errortext = '';
							$message_id = '';
							if(isset($response['response']['error'])){
								$errortext = $response['response']['error'];
							}
							if(isset($response['response']['message_uuid'][0])){
								$message_id = $response['response']['message_uuid'][0];
							}
							app::import('Model','GroupSmsBlast');
							$this->GroupSmsBlast = new GroupSmsBlast();
							$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
							if($message_id !=''){
								$credits = $credits + $contactcredits;
								if(!empty($groupContacts)){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
									$GroupSmsBlast_arr['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
									$this->GroupSmsBlast->save($GroupSmsBlast_arr);
								}
							}else if(isset($response['response']['error'])){
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
							$log_ar['Log']['error_message'] = $errortext;
							$log_ar['Log']['route'] = 'outbox'; 
							$this->Log->save($log_ar);
						}
						

                        $message="Your SMS broadcast has been sent to ".$groupbroadcast['Group']['group_name'];
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						sleep(1);
						$response = $this->Plivo->sendsms($_REQUEST['From'],$_REQUEST['To'],$message);
                        $credits = $credits + 1;
                        $this->User->id = $someone['User']['id'];
						if($this->User->id!=''){
							$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-$credits));
						}
						$this->smsmail($someone['User']['id']);
					}
					exit;
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
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$message);	
						$this->smsmail($someone['User']['id']);
						exit;
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
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$message);	
						$this->smsmail($someone['User']['id']);
						exit;
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
                        $message=EMAIL_CAPTURE_MSG;
						$length = strlen(utf8_decode(substr($message,0,1600)));
						if (strlen($message) != strlen(utf8_decode($message))){
							$credits = ceil($length/70);
						}else{
							 $credits = ceil($length/160);
						}
						if($this->User->id!=''){
                            $this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-$credits));
						}
						/*Controller::loadModel('Log');
						$this->Log->create();
						$this->data['Log']['user_id'] = $someone['User']['id']; 
						$this->data['Log']['phone_number'] = $_REQUEST['From']; 
						$this->data['Log']['text_message'] = $_REQUEST['text'];
                        $this->data['Log']['sms_status'] = 'received';
						$this->data['Log']['route'] = 'inbox'; 
						$this->Log->save($this->data);*/
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$message);	
						$this->smsmail($someone['User']['id']);
						exit;
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
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$statusmsg);	
                        $this->smsmail($someone['User']['id']);
					}
				}else{
					$credits = 1;
					$message="You are not eligible to participate since you are not subscribed to our opt-in list. Please text in ".$smsloyalty_status['Group']['keyword']." to be added to our opt-in list.";
					$this->Plivo->AuthId =PLIVO_KEY;
					$this->Plivo->AuthToken =PLIVO_TOKEN;
					$this->Plivo->sendsms($phone,$fromnumber,$message);	
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
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$message);	
                        $this->smsmail($someone['User']['id']);
					}else if($smsloyalty_arr['Smsloyalty']['enddate'] < $current_date){
						$credits = 1;
						$message="Loyalty program ".$smsloyalty_arr['Smsloyalty']['program_name']." ended on ".date('m/d/Y',strtotime($smsloyalty_arr['Smsloyalty']['enddate']))."";
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$message);
                        $this->smsmail($someone['User']['id']);
						
					}else{
						$currentdate = date('Y-m-d');
						app::import('Model','SmsloyaltyUser');
						$this->SmsloyaltyUser = new SmsloyaltyUser();
						$loyaltyuser=$this->SmsloyaltyUser->find('first',array('conditions'=>array('SmsloyaltyUser.contact_id'=>$contactgroupid['ContactGroup']['contact_id'],'SmsloyaltyUser.sms_loyalty_id'=>$smsloyalty_arr['Smsloyalty']['id'],'SmsloyaltyUser.redemptions'=>0),'order' =>array('SmsloyaltyUser.msg_date' => 'desc'))); 
						if(empty($loyaltyuser)){
							$loyaltyuserredeem=$this->SmsloyaltyUser->find('first',array('conditions'=>array('SmsloyaltyUser.contact_id'=>$contactgroupid['ContactGroup']['contact_id'],'SmsloyaltyUser.sms_loyalty_id'=>$smsloyalty_arr['Smsloyalty']['id'],'SmsloyaltyUser.redemptions'=>1,'SmsloyaltyUser.msg_date'=>$currentdate),'order' =>array('SmsloyaltyUser.msg_date' => 'desc'))); 
                            if(empty($loyaltyuserredeem)){
								$loyalty_user['SmsloyaltyUser']['id'] = '';
								$loyalty_user['SmsloyaltyUser']['unique_key'] = $this->random_generator(10);
								$loyalty_user['SmsloyaltyUser']['user_id'] = $smsloyalty_arr['Smsloyalty']['user_id'];
								$loyalty_user['SmsloyaltyUser']['sms_loyalty_id'] = $smsloyalty_arr['Smsloyalty']['id'];
								$loyalty_user['SmsloyaltyUser']['contact_id'] =$contactgroupid['ContactGroup']['contact_id'];
								$loyalty_user['SmsloyaltyUser']['keyword'] =$_REQUEST['text'];
								$loyalty_user['SmsloyaltyUser']['count_trial'] =1;
								$loyalty_user['SmsloyaltyUser']['msg_date'] =$currentdate;
								$loyalty_user['SmsloyaltyUser']['created'] =date('Y-m-d H:i:s');
								if($smsloyalty_arr['Smsloyalty']['reachgoal']==1){
									$loyalty_user['SmsloyaltyUser']['is_winner'] =1;
									if($this->SmsloyaltyUser->save($loyalty_user)){
										if($smsloyalty_arr['Smsloyalty']['type']==1){
											$credits = 1;
											$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['reachedatgoal']);
											$msg=str_replace('%%STATUS%%',$count_trial,$message);
											$redeem = "Click link to redeem ".SITE_URL."/users/redeem/".$loyalty_user['SmsloyaltyUser']['unique_key']."";
											$sms = $msg.' '.$redeem;
											$this->Plivo->AuthId =PLIVO_KEY;
											$this->Plivo->AuthToken =PLIVO_TOKEN;
											$this->Plivo->sendsms($phone,$fromnumber,$sms);	 
                                            $this->smsmail($someone['User']['id']);
										}
									}
								}else{
                                    $this->SmsloyaltyUser->save($loyalty_user);
									$credits = 1;
									$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['addpoints']);
									$msg=str_replace('%%STATUS%%',1,$message);
									$this->Plivo->AuthId =PLIVO_KEY;
									$this->Plivo->AuthToken =PLIVO_TOKEN;
								    $this->Plivo->sendsms($phone,$fromnumber,$msg);	
                                    $this->smsmail($someone['User']['id']);
								}
                            }else{
                                $credits = 1;
								$message="You have already redeemed your reward today.";
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
					            $this->Plivo->sendsms($phone,$fromnumber,$message);	
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
											$this->Plivo->AuthId =PLIVO_KEY;
											$this->Plivo->AuthToken =PLIVO_TOKEN;
											$this->Plivo->sendsms($phone,$fromnumber,$sms);	
                                            $this->smsmail($someone['User']['id']);
										}
									}
								}else{
									$credits = 1;
									$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['addpoints']);
									$msg=str_replace('%%STATUS%%',$count_trial,$message);
									$this->Plivo->AuthId =PLIVO_KEY;
									$this->Plivo->AuthToken =PLIVO_TOKEN;
									$this->Plivo->sendsms($phone,$fromnumber,$msg);	
                                    $this->smsmail($someone['User']['id']);
								}
							}
						}else if($loyaltyuser['SmsloyaltyUser']['is_winner'] ==1){
							$credits = 1;
							$message="You have already reached the goal of ".$smsloyalty_arr['Smsloyalty']['reachgoal']." points.";
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							$this->Plivo->sendsms($phone,$fromnumber,$message);	
                            $this->smsmail($someone['User']['id']);

						}else{
							$credits = 1;
							$message="You already punched your card today. Stop in tomorrow for the new punch code.";
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							$this->Plivo->sendsms($phone,$fromnumber,$message);	
                            $this->smsmail($someone['User']['id']);
						}
					}
				}else{
					$credits = 1;
					$message="You are not eligible to participate since you are not subscribed to our opt-in list. Please text in ".$smsloyalty_arr['Group']['keyword']." to be added to our opt-in list.";
					$this->Plivo->AuthId =PLIVO_KEY;
					$this->Plivo->AuthToken =PLIVO_TOKEN;
					$this->Plivo->sendsms($phone,$fromnumber,$message);	
                    $this->smsmail($someone['User']['id']);
				}
				if($credits > 0){
					$update_user['User']['id'] = $someone['User']['id'];
					$update_user['User']['sms_balance'] = $someone['User']['sms_balance']-$credits;
					$this->User->save($update_user);
				}
			}
		}else if(strtoupper(trim($_REQUEST['text']))=='HELP'){
			app::import('Model','User');
			$this->User = new User();
			$user_id = $someone['User']['id'];
			$sms_balance = $someone['User']['sms_balance'];
			$this->data['User']['id']=$user_id;
			$this->data['User']['sms_balance']=$sms_balance-1;
			$this->User->save($this->data);
            $companyname = $someone['User']['company_name'];
            if(!empty($companyname)){
				$message="You have signed up to receive promotional messages from ".$companyname.". Text STOP to cancel. Msg&Data Rates May Apply.";
            }else{
			   $message="Text STOP to cancel. Msg&Data Rates May Apply.";
            }
			$this->Plivo->AuthId =PLIVO_KEY;
			$this->Plivo->AuthToken =PLIVO_TOKEN;
         	$this->Plivo->sendsms($phone,$fromnumber,$message);	
			$this->smsmail($someone['User']['id']);
            exit;
		}
		if(strtoupper(trim($_REQUEST['text']))=='START'){
			app::import('Model','User');
			$this->User = new User();
			//$someone1 = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
			$user_id = $someone['User']['id'];
			$sms_balance = $someone['User']['sms_balance'];
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$contactsstart=$this->ContactGroup->find('all',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.un_subscribers'=>1,'ContactGroup.user_id'=> $user_id)));
			if(!empty($contactsstart)){
				foreach($contactsstart as $contact){
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
				app::import('Model','User');
				$this->User = new User();
				$this->data['User']['id']=$user_id;
				$this->data['User']['sms_balance']=$sms_balance-1;
				$this->User->save($this->data);
				$message='You have successfully been re-subscribed. Text STOP to cancel. Msg&Data Rates May Apply.';
				$this->Plivo->AuthId =PLIVO_KEY;
				$this->Plivo->AuthToken =PLIVO_TOKEN;
				$this->Plivo->sendsms($phone,$fromnumber,$message);
				$this->smsmail($someone['User']['id']);
                exit;
			}
		}else if(strtoupper(trim($_REQUEST['text']))=='STOP'){
			app::import('Model','User');
			$this->User = new User();
			//$someone = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
			$user_id = $someone['User']['id'];
			$sms_balance = $someone['User']['sms_balance'];
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$contacts=$this->ContactGroup->find('all',array('conditions'=>array('Contact.phone_number'=>$phone,'ContactGroup.un_subscribers'=>0,'ContactGroup.user_id'=> $user_id)));
			if(!empty($contacts)){
				foreach($contacts as $contact){
					//if($someone['User']['active']==1 || $someone['User']['sms_balance'] > 0){
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
					//}
				}
				app::import('Model','User');
				$this->User = new User();
				$this->data['User']['id']=$user_id;
				$this->data['User']['sms_balance']=$sms_balance-1;
				$this->User->save($this->data);
				$message='You have successfully been unsubscribed. Reply START to get added back to our list. Msg&Data Rates May Apply.';
				$this->Plivo->AuthId =PLIVO_KEY;
				$this->Plivo->AuthToken =PLIVO_TOKEN;
				$this->Plivo->sendsms($phone,$fromnumber,$message);
				$this->smsmail($someone['User']['id']);
                exit;
			}
		}else if(!empty($group)){
			$keyword=$_REQUEST['text'];
			//$to=$_REQUEST['To'];
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
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
                                                $current_datetime=date("n/d/Y");
                                                $message = str_replace('%%CURRENTDATE%%',$current_datetime,$message);
						$this->Plivo->sendsms($phone,$fromnumber,$message);
						sleep(2);
						$this->Immediatelyresponder($user_id,$group_id,$phone,$fromnumber);
						if(!empty($someone['User']['id'])){
							$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
							if(!empty($users_sms_balance)){
								$curcredits = $users_sms_balance['User']['sms_balance'];
								$length = strlen(utf8_decode(substr($message,0,1600)));
								if (strlen($message) != strlen(utf8_decode($message))){
									$credits = ceil($length/70);
								}else{
									$credits = ceil($length/160);
								}
                                $user_balance['User']['sms_balance']=$curcredits- $credits;
								$user_balance['User']['id']=$someone['User']['id'];
								$this->User->save($user_balance);
							}
						}
						$this->smsmail($someone['User']['id']);
						if($someone['User']['capture_email_name']==0){
							$capture_email_name=NAME_CAPTURE_MSG;
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							$this->Plivo->sendsms($phone,$fromnumber,$capture_email_name);
							$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
							$length = strlen(utf8_decode(substr($capture_email_name,0,1600)));
							if (strlen($capture_email_name) != strlen(utf8_decode($capture_email_name))){
								$credits = ceil($length/70);
							}else{
								$credits = ceil($length/160);
							}
							if(!empty($someone_users)){
                                $user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-$credits;
								$user_credit['User']['id']=$user_id;
								$this->User->save($user_credit); 
							}
						}
						if($group['Group']['bithday_enable']==1){	
							if($someone['User']['birthday_wishes']==0){
								$birthday_wishes=BIRTHDAY_MSG;
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								$this->Plivo->sendsms($phone,$fromnumber,$birthday_wishes);
								$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
                                $length = strlen(utf8_decode(substr($birthday_wishes,0,1600)));
                                if (strlen($birthday_wishes) != strlen(utf8_decode($birthday_wishes))){
		                            $credits = ceil($length/70);
                                }else{
                                    $credits = ceil($length/160);
                                }
								if(!empty($someone_users)){
                                    $user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-$credits;
									$user_credit['User']['id']=$user_id;
									$this->User->save($user_credit); 
								}
							}
						}
                        if ($group['Group']['notify_signup']==1){
							$mobile = $group['Group']['mobile_number_input'];
							$groupname = $group['Group']['group_name'];
							$message = "New Subscriber Alert: ".$phone." has joined group ".$groupname;
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
                            $this->Plivo->sendsms($mobile,$fromnumber,$message);
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
							/*if(!empty($users_sms_balance)){
								$user_balance['User']['sms_balance']=$users_sms_balance['User']['sms_balance']-1;
								$user_balance['User']['id']=$someone['User']['id'];
								$this->User->save($user_balance);
							}*/
						}
						$this->smsmail($someone['User']['id']);  
						if($group_type==0){
							$message= $system_message.' '.$auto_message;
                                                        $current_datetime=date("n/d/Y");
                                                        $message = str_replace('%%CURRENTDATE%%',$current_datetime,$message);
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							$this->Plivo->sendsms($phone,$fromnumber,$message);
							sleep(2);
							$curcredits = $users_sms_balance['User']['sms_balance'];
							$length = strlen(utf8_decode(substr($message,0,1600)));
							if (strlen($message) != strlen(utf8_decode($message))){
								$credits = ceil($length/70);
							}else{
								$credits = ceil($length/160);
							}
							$user_balance['User']['sms_balance']=$curcredits- $credits;
							$user_balance['User']['id']=$someone['User']['id'];
					        $this->User->save($user_balance);
							$this->Immediatelyresponder($user_id,$group_id,$phone,$fromnumber);
							$this->smsmail($someone['User']['id']); 
							$name = $contactgroupid['Contact']['name'];
							$email = $contactgroupid['Contact']['email'];
							$bday = $contactgroupid['Contact']['birthday'];

							if($someone['User']['capture_email_name']==0 && $name ==''){
								$capture_email_name=NAME_CAPTURE_MSG;
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								sleep(1);
								$this->Plivo->sendsms($phone,$fromnumber,$capture_email_name);
								$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
                                $length = strlen(utf8_decode(substr($capture_email_name,0,1600)));
                                if (strlen($capture_email_name) != strlen(utf8_decode($capture_email_name))){
		                            $credits = ceil($length/70);
                                }else{
                                    $credits = ceil($length/160);
                                }
								if(!empty($someone_users)){
                                    $user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-$credits;
									$user_credit['User']['id']=$user_id;
									$this->User->save($user_credit); 
								}
							}
							if($group['Group']['bithday_enable']==1 && $bday == '0000-00-00'){
								if($someone['User']['birthday_wishes']==0){
									$birthday_wishes=BIRTHDAY_MSG;
									$this->Plivo->AuthId =PLIVO_KEY;
									$this->Plivo->AuthToken =PLIVO_TOKEN;
									sleep(1);
									$this->Plivo->sendsms($phone,$fromnumber,$birthday_wishes);
									$someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
									$length = strlen(utf8_decode(substr($birthday_wishes,0,1600)));
									if (strlen($birthday_wishes) != strlen(utf8_decode($birthday_wishes))){
										$credits = ceil($length/70);
									}else{
										$credits = ceil($length/160);
									}
									if(!empty($someone_users)){
										$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-$credits;
										$user_credit['User']['id']=$user_id;
										$this->User->save($user_credit); 
									}
								}
							}
						}else{
						   if($group_type==2){
                                $message='We have already sent you information on this property.';
                            }else if ($group_type==3){
								$message='We have already sent you information on this vehicle.';
                            }else{
								$message='You are already subscribed to this list.';
                            }
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							$this->Plivo->sendsms($phone,$fromnumber,$message);
                            $user_credit['User']['sms_balance']=$someone['User']['sms_balance']-1;
							$user_credit['User']['id']=$user_id;
					        $this->User->save($user_credit);
							$this->smsmail($someone['User']['id']); 
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
					if($group_type==2){
						$address = $group['Group']['property_address'];
						$price = $group['Group']['property_price'];
						$bed = $group['Group']['property_bed'];
						$bath = $group['Group']['property_bath'];
						$description = $group['Group']['property_description'];
						$url = $group['Group']['property_url'];
						$message=$address."\n".$price."\nBed: ".$bed."\nBath: ".$bath."\n".$description."\n".$url."\n";
						$message=$message. $system_message.' '.$auto_message;
                    }else if ($group_type==3){
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
					if($sms_type == 1){	
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
$current_datetime=date("n/d/Y");
$message = str_replace('%%CURRENTDATE%%',$current_datetime,$message);
						$this->Plivo->sendsms($phone,$fromnumber,$message);
						sleep(2);
						$this->Immediatelyresponder($user_id,$group_id,$phone,$fromnumber);
					}	
					if(!empty($someone['User']['id'])){
						$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
						$curcredits = $users_sms_balance['User']['sms_balance'];
						$length = strlen(utf8_decode(substr($message,0,1600)));
						if(!empty($users_sms_balance)){
							//$this->data['User']['sms_balance']=$credits-1;
							if (strlen($message) != strlen(utf8_decode($message))){
								$credits = ceil($length/70);
							}else{
								$credits = ceil($length/160);
							}
							$this->data['User']['sms_balance']=$curcredits- $credits;
							$this->data['User']['id']=$someone['User']['id'];
							$this->User->save($this->data);
						 }
					}	
					if($someone['User']['capture_email_name']==0){
						$capture_email_name=NAME_CAPTURE_MSG;
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						sleep(1);
						$this->Plivo->sendsms($phone,$fromnumber,$capture_email_name);
						$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
						$curcredits = $users_sms_balance['User']['sms_balance'];

						$length = strlen(utf8_decode(substr($capture_email_name,0,1600)));
						if (strlen($capture_email_name) != strlen(utf8_decode($capture_email_name))){
							$credits = ceil($length/70);
						}else{
							$credits = ceil($length/160);
						}
						if(!empty($users_sms_balance)){
							$this->data['User']['sms_balance']=$curcredits-$credits;
							$this->data['User']['id']=$someone['User']['id'];
							$this->User->save($this->data);
						}
					}
					if($group['Group']['bithday_enable']==1){
						if($someone['User']['birthday_wishes']==0){
							$birthday_wishes=BIRTHDAY_MSG;
							$this->Plivo->AuthId =PLIVO_KEY;
							$this->Plivo->AuthToken =PLIVO_TOKEN;
							sleep(1);
							$this->Plivo->sendsms($phone,$fromnumber,$birthday_wishes);
							$users_sms_balance = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
							$curcredits = $users_sms_balance['User']['sms_balance'];
                            $length = strlen(utf8_decode(substr($birthday_wishes,0,1600)));
							if (strlen($birthday_wishes) != strlen(utf8_decode($birthday_wishes))){
								$credits = ceil($length/70);
							}else{
								$credits = ceil($length/160);
							}
							if(!empty($users_sms_balance)){
								$this->data['User']['sms_balance']=$curcredits-$credits;
								$this->data['User']['id']=$someone['User']['id'];
								$this->User->save($this->data);
							}
						}
					}	
                    if ($group['Group']['notify_signup']==1){
						$mobile = $group['Group']['mobile_number_input'];
						$groupname = $group['Group']['group_name'];
						$message = "New Subscriber Alert: ".$phone." has joined group ".$groupname;
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($mobile,$fromnumber,$message);
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
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$message);
					}else{
						app::import('Model','User');
						$this->User = new User();
						$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
						$credits = $users['User']['sms_balance'];
						$this->data['User']['sms_balance']=$credits-1;
						$this->data['User']['id']=$user_id;
						$this->User->save($this->data);
						//$this->smsmail($users['User']['id']);
						$message= "You have already voted in this poll";
						$this->Plivo->AuthId =PLIVO_KEY;
						$this->Plivo->AuthToken =PLIVO_TOKEN;
						$this->Plivo->sendsms($phone,$fromnumber,$message);
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
				$this->Plivo->AuthId =PLIVO_KEY;
				$this->Plivo->AuthToken =PLIVO_TOKEN;
				$this->Plivo->sendsms($phone,$fromnumber,$contestkeywords['Contest']['system_message']);
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
				$this->Plivo->AuthId =PLIVO_KEY;
				$this->Plivo->AuthToken =PLIVO_TOKEN;
				$this->Plivo->sendsms($phone,$fromnumber,$message1);
				$this->smsmail($user_id);
			}
		}else{
			//saving logs
			Controller::loadModel('Log');
			$this->Log->create();
			$this->data['Log']['user_id'] = $someone['User']['id']; 
			$this->data['Log']['phone_number'] = $_REQUEST['From']; 
			$this->data['Log']['name'] = $contactname;
			$this->data['Log']['contact_id'] = $contact_id;
			$this->data['Log']['inbox_type'] = 1;
			$this->data['Log']['email_to_sms_number'] = $_REQUEST['To'];
			$this->data['Log']['text_message'] = $_REQUEST['text']; 
            $this->data['Log']['created'] = date('Y-m-d H:i:s',time());
			$this->data['Log']['sms_status'] = 'received'; 
			$this->data['Log']['route'] = 'inbox'; 
			pr($this->data['Log']);
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
				$this->Email->send();
				$this->data['Log']['ticket'] =$random_generator;
			}
			$this->Log->save($this->data);
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
						$to = $username = $someone['User']['smsalerts_number'];
						$from = $_REQUEST['To']; 
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
						if(isset($response['response']['error'])){
							$this->data['Log']['sms_status']= 'failed';
							$ErrorMessage = $errortext;
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
	function voice(){
		$this->autoRender=false;
		$_REQUEST['To'] = str_replace('+','',$_REQUEST['To']);
		$_REQUEST['From'] = str_replace('+1','',$_REQUEST['From']);
		Controller::loadModel('User');
		$someone = $this->User->find('first', array('conditions' => array('User.assigned_number' =>''.trim($_REQUEST['To']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));
		}
		date_default_timezone_set($someone['User']['timezone']);
		$active = $someone['User']['active'];
		if($someone['User']['voice_balance'] > 0 && $active == 1){
			if($someone['User']['incomingcall_forward']==0 && $someone['User']['assign_callforward']==trim($_REQUEST['To'])){
				Controller::loadModel('Log');
				$this->Log->create();
				$this->data['Log']['user_id'] = $someone['User']['id']; 
				$this->data['Log']['phone_number'] = $_REQUEST['From']; 
				//$this->data['Log']['voice_url'] = $_REQUEST['CallUUID'];
                                $this->data['Log']['sms_id'] = $_REQUEST['CallUUID'];
				$this->data['Log']['route'] = 'inbox'; 
				$this->data['Log']['msg_type'] = 'callforward'; 
                                ob_start();
				echo "<pre>";
				print_r($_REQUEST);
				echo "</pre>";
				$out1 = ob_get_contents();
				ob_end_clean();
				$file = fopen("debug/plivocallforward".time().".txt", "w");
				fwrite($file, $out1); 
				fclose($file); 
				$this->Log->save($this->data);
				header("content-type: text/xml");
				echo "<Response>";
				echo "<Dial callerId ='".$_REQUEST['From']."' timeLimit='3600'>";
				echo "<Number>".$someone['User']['callforward_number']."</Number>";
				echo "</Dial>";
				echo "</Response>";
				exit;
				
			}else{
											
				if($someone['User']['welcome_msg_type']==1){
					$msg = '<Play>'.SITE_URL.'/mp3/'.$someone['User']['mp3'].'</Play>';
				}else{
					$msg = '<Speak>'.$someone['User']['defaultgreeting'].'</Speak>';
				} 
				header("content-type: text/xml");
				echo '<Response>';
				echo $msg;
				//echo "<Record action='".SITE_URL."/plivos/voicerecording/".$log_id."' maxLength='60' finishOnKey='*' method='GET'/>";
                                echo '<Record method="GET" maxLength="60" finishOnKey="*" />';
				//echo '<Speak>Recording not received.</Speak>';
				echo '</Response>';
				//exit;
                                $this->Plivo->AuthId =PLIVO_KEY;
		                $this->Plivo->AuthToken =PLIVO_TOKEN;
		                $vars['call_uuid']=$_REQUEST['CallUUID'];
                                $vars['time_limit']="120";
		                $response = $this->Plivo->record($vars);
                                $url = $response['response']['url'];
                               

                                Controller::loadModel('Log');
				$this->Log->create();
				$this->data['Log']['user_id'] = $someone['User']['id']; 
				$this->data['Log']['phone_number'] = $_REQUEST['From']; 
				//$this->data['Log']['voice_url'] = $_REQUEST['CallUUID']; 
                                $this->data['Log']['sms_id'] = $_REQUEST['CallUUID'];
                                $this->data['Log']['voice_url'] = $url;
				$this->data['Log']['route'] = 'inbox'; 
				$this->data['Log']['msg_type'] = 'voice'; 
				$this->Log->save($this->data);
				$log_id = $this->Log->id;
                                 

                                ob_start();
				echo "<pre>";
				print_r($_REQUEST);
				echo "</pre>";
				$out1 = ob_get_contents();
				ob_end_clean();
				$file = fopen("debug/plivovoicemail".time().".txt", "w");
				fwrite($file, $out1); 
				fclose($file); 
			}
		}else{
			header("content-type: text/xml");
			echo "<Response>";
			echo "<Speak>Thanks for calling.</Speak>";
			echo "</Response>";
			exit;
		}
	}
	function voicerecording($log_id=null){
		$this->autoRender=false;
		Controller::loadModel('User');
		$someone = $this->User->find('first', array('conditions' => array('User.assigned_number' =>''.trim($_REQUEST['To']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));
		}
		date_default_timezone_set($someone['User']['timezone']);
                Controller::loadModel('Log');
                $this->Log->recursive = -1;
		$logs = $this->Log->find('first', array('conditions' => array('Log.id' =>$log_id)));
		if(!empty($logs)){
			Controller::loadModel('Log');
			$this->data['Log']['id'] =$logs['Log']['id'];
			$this->data['Log']['voice_url'] =$_REQUEST['RecordUrl'];
			$this->Log->save($this->data);
		}
		header("content-type: text/xml");
		echo '<Response>';
		echo '<Speak>Thanks for calling.</Speak>';
		echo '</Response>';
		ob_start();
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/plivovoicerecording".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file); 
                
		
	}
	function hangup($log_id=null){
		$this->autoRender=false;
		Controller::loadModel('User');
		$someone = $this->User->find('first', array('conditions' => array('User.assigned_number' =>''.trim($_REQUEST['To']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));
		}
		date_default_timezone_set($someone['User']['timezone']);
                Controller::loadModel('Log');
                $this->Log->recursive = -1;
		//$logs = $this->Log->find('first', array('conditions' => array('Log.voice_url' =>$_REQUEST['CallUUID'])));
                $logs = $this->Log->find('first', array('conditions' => array('Log.sms_id' =>$_REQUEST['CallUUID'])));
		if(!empty($logs)){
			if($someone['User']['voice_balance'] > 0){
				if(isset($_REQUEST['Duration'])){
					$minutes = ceil($_REQUEST['Duration']/60);
                                        $msg_type = $logs['Log']['msg_type'];
                                        if ($msg_type == 'callforward'){
                                           $minutes = $minutes*2;
                                        }
					if($minutes > 0){
						Controller::loadModel('Log');
						$this->data['Log']['id'] =$logs['Log']['id'];
						$this->data['Log']['sms_status'] =$_REQUEST['CallStatus'];
						$this->data['Log']['call_duration'] = gmdate("H:i:s", $_REQUEST['Duration']);
						$this->Log->save($this->data);
						$this->User->id = $someone['User']['id'];
						$this->User->saveField('voice_balance', ($someone['User']['voice_balance']-$minutes));

                                               
						Controller::loadModel('User');
						$usersvoicebalance = $this->User->find('first', array('conditions' => array('User.id'=>$someone['User']['id'])));	
						if($usersvoicebalance['User']['email_alert_credit_options']==0){
							if($usersvoicebalance['User']['voice_balance'] <= $usersvoicebalance['User']['low_voice_balances']){
								if($usersvoicebalance['User']['VM_credit_balance_email_alerts']==0){
									$username = $usersvoicebalance['User']['username'];
									$email = $usersvoicebalance['User']['email'];
									$sitename=str_replace(' ','',SITENAME);
									$subject="Low Voice Credit Balance";	
									$this->Email->to = $email;	
									$this->Email->subject = $subject;
									$this->Email->from = $sitename;
									$this->Email->template = 'low_voice_credit_template';
									$this->Email->sendAs = 'html';
									$this->Email->Controller->set('username', $username);
									$this->Email->Controller->set('low_voice_balances',$usersvoicebalance['User']['low_voice_balances']);
									$this->Email->send();
									$this->User->id = $usersvoicebalance['User']['id'];
									$this->User->saveField('VM_credit_balance_email_alerts',1);
								}
							}
						}
					}
				}
			}
		}
		/*header("content-type: text/xml");
		echo "<Response>";
		echo "</Response>";*/
		ob_start();
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/hangup".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file); 
	}
	function hang_up($group_id=null,$log_id=null){
		$this->autoRender=false;
		Controller::loadModel('User');
		$someone = $this->User->find('first', array('conditions' => array('User.assigned_number' =>''.trim($_REQUEST['From']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['From']).'')));
		}
		date_default_timezone_set($someone['User']['timezone']);

		Controller::loadModel('Log');
	        $this->data['Log']['id'] = $log_id;
		$this->data['Log']['sms_status'] =$_REQUEST['CallStatus'];
		$this->data['Log']['voice_url'] =$_REQUEST['CallUUID'];
                $this->data['Log']['call_duration'] = gmdate("H:i:s", $_REQUEST['Duration']);
		$this->Log->save($this->data);

		if($someone['User']['voice_balance'] > 0){
			if(isset($_REQUEST['Duration'])){
				$minutes = ceil($_REQUEST['Duration']/60);
				if($minutes > 0){
					//Controller::loadModel('Log');
					//$this->data['Log']['id'] = $log_id;
					//$this->data['Log']['sms_status'] =$_REQUEST['CallStatus'];
					//$this->data['Log']['voice_url'] =$_REQUEST['CallUUID'];
 					//$this->data['Log']['call_duration'] = gmdate("H:i:s", $_REQUEST['Duration']);
					//$this->Log->save($this->data);
					$this->User->id = $someone['User']['id'];
					$this->User->saveField('voice_balance', ($someone['User']['voice_balance']-$minutes));

					Controller::loadModel('User');
					$usersvoicebalance = $this->User->find('first', array('conditions' => array('User.id'=>$someone['User']['id'])));	
					if($usersvoicebalance['User']['email_alert_credit_options']==0){
						if($usersvoicebalance['User']['voice_balance'] <= $usersvoicebalance['User']['low_voice_balances']){
							if($usersvoicebalance['User']['VM_credit_balance_email_alerts']==0){
								$username = $usersvoicebalance['User']['username'];
								$email = $usersvoicebalance['User']['email'];
								$sitename=str_replace(' ','',SITENAME);
								$subject="Low Voice Credit Balance";	
								$this->Email->to = $email;	
								$this->Email->subject = $subject;
								$this->Email->from = $sitename;
								$this->Email->template = 'low_voice_credit_template';
								$this->Email->sendAs = 'html';
								$this->Email->Controller->set('username', $username);
								$this->Email->Controller->set('low_voice_balances',$usersvoicebalance['User']['low_voice_balances']);
								$this->Email->send();
								$this->User->id = $usersvoicebalance['User']['id'];
								$this->User->saveField('VM_credit_balance_email_alerts',1);
							}
						}
					}
				}
			}
		}
		/*header("content-type: text/xml");
		echo "<Response>";
		echo "</Response>";*/
		ob_start();
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/hang_up".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file); 
	}
	function peoplecallrecordscript($group_id=null,$repeat=null,$language=null,$pause=null){
		$this->autoRender=false;
		ob_start();
		echo "<pre>";
		print_r($_REQUEST);
		print_r($_FILES);
		echo "</pre>";
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/peoplecallrecordscript".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);
		app::import('Model','VoiceMessage');
		$this->VoiceMessage = new VoiceMessage();
		$VoiceMessage=$this->VoiceMessage->find('first',array('conditions' => array('VoiceMessage.group_id'=>$group_id)));
		$message_type  = $VoiceMessage['VoiceMessage']['message_type'];
		if($message_type == 1){  // audio url
			$audio = SITE_URL.'/voice/'.$VoiceMessage['VoiceMessage']['audio'];
			header("content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			echo "<Response>";
			echo "<Wait length='".$pause."'/>";				
			echo "<Play loop='".$repeat."'>".$audio."</Play>";
                        echo "<GetDigits action='".SITE_URL."/plivos/do_not_call/".$group_id."' method='GET'>";
                        echo "<Speak>Please press 1 to be added to the do not call list.</Speak>";
                        echo "</GetDigits>";
			echo "</Response>";
		}else{  
			$msg = $VoiceMessage['VoiceMessage']['text_message'];
			header("content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			echo "<Response>";
                        echo "<Wait length='".$pause."'/>";				
			echo "<Speak language='".$language."' loop='".$repeat."'>".$msg."</Speak>";
                        echo "<GetDigits action='".SITE_URL."/plivos/do_not_call/".$group_id."' method='GET'>";
                        echo "<Speak>Please press 1 to be added to the do not call list.</Speak>";
                        echo "</GetDigits>";
			echo "</Response>";
 		}
         }

         function do_not_call($group_id=null){
		$this->autoRender=false;
		ob_start();
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/donotcall".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);
		if($_REQUEST['Digits']==1){
			$phone  = str_replace('+','',$_REQUEST['To']);
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			app::import('Model','Contact');
			$this->Contact = new Contact();
			//$Subscriber = $this->Contact->find('first',array('conditions' => array('Contact.phone_number'=>$phone)));
			$contactgroup = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.group_id'=>$group_id)));
			$user_id = $contactgroup['ContactGroup']['user_id'];
			
			$Subscriber = $this->Contact->find('first', array('conditions' => array('phone_number' =>$phone,'Contact.user_id'=> $user_id)));
			if(!empty($Subscriber)){
				$Subscribergroup = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=>$Subscriber['Contact']['id'],'ContactGroup.group_id'=>$group_id)));
				if(!empty($Subscribergroup)){
					$arr['ContactGroup']['id']=$Subscribergroup['ContactGroup']['id'];
					$arr['ContactGroup']['do_not_call']=1;
					$this->ContactGroup->save($arr);
					
					header("content-type: text/xml");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					echo "<Response><Speak>You have successfully been un-subscribed and added to the do not call list. Thank you.</Speak></Response>";
					exit; 
				}else{
					header("content-type: text/xml");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";		
					echo "<Response></Response>";	
					exit; 
				}
			}else{
				header("content-type: text/xml");
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";		
				echo "<Response></Response>";	
				exit; 
			}
		}else{
			header("content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";	
			echo "<Response></Response>";	
			exit; 
		}
	}

	function callstatus(){
        sleep(2);		
        $this->autoRender=false;
		/*ob_start();
		echo "<pre>";
		print_r($_REQUEST);
		print_r($_FILES);
		echo "</pre>";*/

		app::import('Model','Log');
		$this->Log = new Log();
                $this->Log->recursive = -1;
		$someone = $this->Log->find('first', array('conditions' => array('Log.sms_id' =>$_REQUEST['ParentMessageUUID'])));
		$message = trim($someone['Log']['text_message']);
		$length = strlen(utf8_decode(substr($message,0,1600))); 
		if (strlen($message) != strlen(utf8_decode($message))){
			$credits = ceil($length/70);
		}else{
			$credits = ceil($length/160);
		}
               
		if(!empty($someone)){
			$logid=$someone['Log']['id'];
			$user_id=$someone['Log']['user_id'];
			$CurStatus=$someone['Log']['sms_status'];
			$this->data['Log']['id']=$logid;
			$Status = $_REQUEST['Status'];
			//$this->data['Log']['sms_status'] = $Status;
		
			app::import('Model','GroupSmsBlast');			
			$this->GroupSmsBlast = new GroupSmsBlast(); 
			$GroupSmsBlast = $this->GroupSmsBlast->find('first', array('conditions' => array('GroupSmsBlast.id' =>$someone['Log']['group_sms_id'])));
			
                if (trim($Status)=='undelivered' || trim($Status)=='failed' || trim($Status)=='rejected') {
					if(!empty($GroupSmsBlast['GroupSmsBlast']['id'])){	
						$this->data['GroupSmsBlast']['total_successful_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_successful_messages']-1;		
						$this->data['GroupSmsBlast']['total_failed_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_failed_messages']+1; 
						$this->data['GroupSmsBlast']['id'] = $GroupSmsBlast['GroupSmsBlast']['id']; 
						$this->GroupSmsBlast->save($this->data);
					}
					
                                 if (trim($Status)=='failed' || trim($Status)=='rejected'){
                                        app::import('Model','User');
					$this->User = new User();   
					$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));				  
					$this->data['User']['sms_balance']=$usersms['User']['sms_balance']+$credits;
					$this->data['User']['id']=$usersms['User']['id'];	  
					$this->User->save($this->data);
                                 }
					
                                        $this->data['Log']['error_message'] = "The destination handset you are trying to reach is either a landline number, unknown or may no longer exist, blocked from receiving this message, or an unreachable carrier"; 
					$this->data['Log']['sms_status'] = $Status; 
				}elseif (trim($Status)=='delivered') {
					$this->data['Log']['sms_status'] = $Status; 
			}
			$this->Log->save($this->data);
		}
		
		/*$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/callstatus".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);*/
	}
	function Immediatelyresponder($user_id=null,$group_id=null,$to=null,$from=null){
		$this->autoRender=false;
		app::import('Model','Responder');
		$this->Responder = new Responder();
		app::import('Model','User');
		$this->User = new User();
		$response=$this->Responder->find('first',array('conditions'=>array('Responder.user_id'=>$user_id,'Responder.group_id'=>$group_id,'Responder.days'=>0)));
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
		if(!empty($response)){
			$current_datetime=date("n/d/Y");
	        if($users['User']['sms_balance'] > 0){
	  			$Responderid = $response['Responder']['id'];
			    $group_id = $response['Responder']['group_id'];
				$sms_type = $response['Responder']['sms_type'];
				$image_url = $response['Responder']['image_url'];
				$message = str_replace('%%CURRENTDATE%%',$current_datetime,$response['Responder']['message']);
				$systemmsg = $response['Responder']['systemmsg'];
				$user_id = $response['Responder']['user_id'];
				$body = $message." ".$systemmsg;
				$this->Plivo->AuthId =PLIVO_KEY;
				$this->Plivo->AuthToken =PLIVO_TOKEN;
				$response = $this->Plivo->sendsms($to,$from,$body);
				$errortext = '';
				$message_id = '';
				if(isset($response['response']['error'])){
					$errortext = $response['response']['error'];
				}
				if(isset($response['response']['message_uuid'][0])){
					$message_id = $response['response']['message_uuid'][0];
				}
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
}