<?php
class TwiliosController extends AppController {
	var $uses = 'Users';
	var $components = array('Email','Twilio','Mms');
	function searchcountry() {
		$this->layout = 'popup';
	}	
	function searchbycode() {
		$this->layout = 'popup';
		$country=$_REQUEST['country'];
		$numbertype=$_REQUEST['numbertype'];
		$this->set('country',$country);
		$this->set('numbertype',$numbertype);
	}
	function buyNumber() {
		$this->layout = 'popup';
		$country=$_REQUEST['country'];
		$numbertype=$_REQUEST['numbertype'];
		$this->set('country',$country);
		$this->set('numbertype',$numbertype);
	}
	
	function areabuynumber() {
		$this->layout = 'popup';
		$country=$_REQUEST['country'];
		$numbertype=$_REQUEST['numbertype'];
		$this->set('country',$country);
		$this->set('numbertype',$numbertype);
	}
	function searchnumber() {
		$this->layout = 'popup';
		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
		$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
		$response = $this->Twilio->listNumbers();	
		$AvailablePhoneNumbers = $response->ResponseXml->AvailablePhoneNumbers->AvailablePhoneNumber;	  
		if(empty($AvailablePhoneNumbers)) {
			$this->Session->setFlash(__('We did not find any phone numbers by that search', true));			 
		}
		$this->set('AvailablePhoneNumbers', $AvailablePhoneNumbers);
	}	
	function assignthisnumber(){
		$this->autoRender=false;
		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
		$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
		$siteurl=SITE_URL;
		$user_id=$this->Session->read('User.id');
		if($user_id!=''){
			$response = $this->Twilio->assignthisnumber($_REQUEST['number'],$siteurl);
			if(isset($response->ResponseXml->IncomingPhoneNumber->Sid)){
				app::import('Model','UserNumber');
				$this->UserNumber = new UserNumber();			
				Controller::loadModel('User');
				$user_id=$this->Session->read('User.id');
				$someone = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
				if($someone['User']['assigned_number']==0){
					$this->User->id = $this->Session->read('User.id'); 
					$this->User->saveField('assigned_number', $_REQUEST['number']);
					$this->User->saveField('phone_sid', $response->ResponseXml->IncomingPhoneNumber->Sid);
					if(isset($_REQUEST['voice'])){
						$this->User->saveField('voice', $_REQUEST['voice']);
					}
					if(isset($_REQUEST['sms'])){
						$this->User->saveField('sms', $_REQUEST['sms']);
					}
					if(isset($_REQUEST['mms'])){
						$this->User->saveField('mms', $_REQUEST['mms']);
					}
					$this->User->saveField('api_type', API_TYPE);
					$this->User->saveField('number_limit_count',1);
				}else{
					$arr['UserNumber']['user_id']=$user_id;
					$arr['UserNumber']['number']=$_REQUEST['number'];
					$arr['UserNumber']['phone_sid']=$response->ResponseXml->IncomingPhoneNumber->Sid;
					if(isset($_REQUEST['voice'])){
						$arr['UserNumber']['voice']=$_REQUEST['voice'];
					}
					if(isset($_REQUEST['sms'])){
						$arr['UserNumber']['sms']=$_REQUEST['sms'];
					}
					if(isset($_REQUEST['mms'])){
						$arr['UserNumber']['mms']=$_REQUEST['mms'];
					}
					$arr['UserNumber']['api_type']=API_TYPE;
					$this->UserNumber->save($arr);
					
					if(!empty($someone)){
						$arr_number['User']['id']=$user_id;
						$arr_number['User']['number_limit_count']=$someone['User']['number_limit_count'] + 1;
						$this->User->save($arr_number);
					}
				}   
				$this->Session->setFlash(__('Number Assigned', true));
				echo 'success';
			}else{
				echo 'success';
				$error = $response->ErrorMessage;
				$this->Session->setFlash(__($error, true));
			}
		}else{
			echo 'success';
			$this->Session->setFlash(__('Invalid Id', true));
		}
	}
	function apibox() {
		$this->layout = 'popup';
		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;	
		$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;	
		Controller::loadModel('User');
		$userDetails = $this->getLoggedUserDetails();
		if($userDetails['User']['apikey']==''){
			$this->User->id = $this->Session->read('User.id'); 
			$apikey = 'INS'.rand(82342,23423456).$this->User->id;
			if($this->User->id != ''){
				$this->User->saveField('apikey', $apikey);
			}
		}else{
			$apikey = $userDetails['User']['apikey'];
		}
		$this->set('apiKey', $apikey);
	}
	function apicall($apikey,$sendto,$themsg){
		$this->autoRender=false;
		Controller::loadModel('User');
		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
		$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
		$someone = $this->User->find('first', array('conditions' => array('apikey' =>''.$apikey.'')));
		if(isset($someone['User']['id'])){
			if($someone['User']['sms_balance']<0){
					echo 'SMS Balance too low.';
					exit;
			}
			$to = $sendto;
			$from = $someone['User']['assigned_number'];	
			$decoded = base64_decode($themsg);
			$replacewith = array("+", "=");
			$replacers   = array("7PLUS7", "7EQUALS7");
			$themsg = str_replace($replacers, $replacewith, $decoded);
			$body = $themsg;			$response = $this->Twilio->sendsms($to,$from,$body);						
			//saving logs
			Controller::loadModel('Log');
			$this->Log->create();
			$this->data['Log']['user_id'] = $someone['User']['id']; 
			$this->data['Log']['phone_number'] = $to; 
			$this->data['Log']['text_message'] = $body; 
			$this->data['Log']['route'] = 'outbox'; 
			$this->Log->save($this->data);
			$this->User->id = $someone['User']['id']; 
			$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-1));
			echo 'sent';
		}else{
			echo 'Wrong API Key';
		}
	}
	function sendsms($id){
		$this->autoRender=false;
		$userDetails = $this->getLoggedUserDetails();
		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;	
		$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
		if($userDetails['User']['sms_balance'] > 0){
			$to = ($this->data['twilios']['phone_number']) ? $this->data['twilios']['phone_number'] : $this->data['twilios']['phone'];
			//$from = $userDetails['User']['assigned_number'];
			//$body = $this->data['twilios']['message'];
			$space_pos = strpos($this->data['twilios']['contact_name'],' ');
			if($space_pos!=''){
				$contact_name=substr($this->data['twilios']['contact_name'],0,$space_pos);
			}else{
				$contact_name = $this->data['twilios']['contact_name'];
			}
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
			$body =str_replace('%%Name%%',$contact_name,$this->data['twilios']['message']);
			$response = $this->Twilio->sendsms($to,$from,$body);	
			$smsid=$response->ResponseXml->Message->Sid;
			$Status=$response->ResponseXml->RestException->Status;
			Controller::loadModel('Log');
			$this->Log->create();
			$this->data['Log']['sms_id'] =$smsid; 
			$this->data['Log']['user_id'] = $this->Session->read('User.id'); 
			$this->data['Log']['phone_number'] = $to; 
			$this->data['Log']['text_message'] = $body; 
			$this->data['Log']['route'] = 'outbox'; 
			if($Status==400){
				$this->data['Log']['sms_status']= 'failed';
				$ErrorMessage = $response->ErrorMessage;
				$this->data['Log']['error_message']=$ErrorMessage;
			}
			$this->Log->save($this->data);
			if($response->IsError==1 || $response->HttpStatus==400){
				$this->Session->setFlash(__($response->ErrorMessage, true));
				if(!empty($id)){
					$this->redirect(array('controller' => 'groups'));
				}else{
					$this->redirect(array('controller' => 'contacts'));
				}
			}else if($smsid!=''){
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
		$this->autoRender=false;
		$_REQUEST['To'] = str_replace('+','',$_REQUEST['To']);
		$_REQUEST['From'] = str_replace('+','',$_REQUEST['From']);
		$phone = $_REQUEST['From'];
		$_REQUEST['Body'] = trim($_REQUEST['Body']);

		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
                $this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
		
		app::import('Model','User');
	        $this->User = new User();
		$someone = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));
	        }
		$user_id = $someone['User']['id'];
		$timezone = $someone['User']['timezone'];	
		date_default_timezone_set($timezone);

                $sms_balance = $someone['User']['sms_balance'];	
                $active = $someone['User']['active'];	
                //if ($active == 0 || ($sms_balance < 1 && strtoupper(trim($_REQUEST['Body']))!='STOP')){
                //    exit;
                //}

                if (($active == 0 || $sms_balance < 1) && strtoupper(trim($_REQUEST['Body']))!='STOP'){
                    exit;
                }
		
		$checkmsgpart = explode(':',$_REQUEST['Body']);
		$checkgroup = explode(' ',$checkmsgpart[0]);
				

		if(strtoupper($checkgroup[0])=='SEND'){
		
			$checkbroadcast=$this->User->find('first',array('conditions'=>array('User.id'=>$someone['User']['id'],'User.broadcast'=>''.trim($_REQUEST['From']).'')));
			if(!empty($checkbroadcast)){
			
				app::import('Model','Group');
				$this->Group = new Group();
				$groupbroadcast=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$checkgroup[1],array('Group.user_id'=>$someone['User']['id']))));
				$group_sms_id = 0;
				if(!empty($groupbroadcast)){
				
				        app::import('Model','ContactGroup');
	                                $this->ContactGroup = new ContactGroup();
					$contactlist=$this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.group_id'=>$groupbroadcast['Group']['id'],'ContactGroup.un_subscribers'=>0,'ContactGroup.user_id'=> $user_id)));
					$totalSubscriber = 0;
					if(!empty($contactlist)){
						$credits = 1;
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
							echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
							echo "<Response>
							<Sms>".$message."</Sms>
							</Response>";
						$this->smsmail($someone['User']['id']);
                                                $this->User->id = $someone['User']['id'];
						if($this->User->id!=''){
							$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-$credits));
						}
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
								$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
								$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
								$tonumber = $contactlists['Contact']['phone_number'];
								$fromnumber = $_REQUEST['To'];
								$bodymsg = $checkmsgpart[1];
								$response = $this->Twilio->sendsms($tonumber,$fromnumber,$bodymsg);
								$Sid = $response->ResponseXml->Message->Sid;
								$Status=$response->ResponseXml->RestException->Status;
                                                                $errortext=$response->ResponseXml->RestException->Message;
								app::import('Model','GroupSmsBlast');
								$this->GroupSmsBlast = new GroupSmsBlast();
								$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$group_sms_id)));
								
								if($Sid !=''){
									//$credits = $credits + 1;
                                                                          $credits = $credits + $contactcredits;
								}else if($Status==400){
									if(!empty($groupContacts)){
										app::import('Model','GroupSmsBlast');
										$this->GroupSmsBlast = new GroupSmsBlast();
										$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_sms_id;
										$GroupSmsBlast_arr['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
										$this->GroupSmsBlast->save($GroupSmsBlast_arr);
									}
								}
								$sms_id = '';
								if($Sid !=''){
									$sms_id  =$Sid;
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
                                                                $this->smsmail($someone['User']['id']);
								
						}
						
                        $message="Your SMS broadcast has been sent to ".$groupbroadcast['Group']['group_name'];
						echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						echo "<Response>
						<Sms>".$message."</Sms>
						</Response>";
                        $this->smsmail($someone['User']['id']);

                        //$credits = $credits + 1;
                        $this->User->id = $someone['User']['id'];
						if($this->User->id!=''){
							$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-$credits));
						}
						
					}
					exit;
				}
				
			}
		}
		if($someone['User']['birthday_wishes']==0){
			//$birthday_wishes = explode(':',$_REQUEST['Body']);
                        $birthday_wishes = $_REQUEST['Body'];

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
							$this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-2));
						}
						
						/*Controller::loadModel('Log');
						$this->Log->create();
						$this->data['Log']['user_id'] = $someone['User']['id']; 
						$this->data['Log']['phone_number'] = $_REQUEST['From']; 
						$this->data['Log']['text_message'] = $_REQUEST['Body'];
                                                $this->data['Log']['sms_status'] = 'received';
						$this->data['Log']['route'] = 'inbox'; 
						$this->Log->save($this->data);*/
						
						$message='Thanks for your response.';
						echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						echo "<Response>
						<Sms>".$message."</Sms>
						</Response>";
                                                $this->smsmail($someone['User']['id']);
				
						exit;
					}
				}
			
			}
		}

		if($someone['User']['capture_email_name']==0){
			$capture_email_name = explode(':',$_REQUEST['Body']);
			
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
							$this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-2));
						}
						
						/*Controller::loadModel('Log');
						$this->Log->create();
						$this->data['Log']['user_id'] = $someone['User']['id']; 
						$this->data['Log']['phone_number'] = $_REQUEST['From']; 
						$this->data['Log']['text_message'] = $_REQUEST['Body'];
                                                $this->data['Log']['sms_status'] = 'received';
						$this->data['Log']['route'] = 'inbox'; 
						$this->Log->save($this->data);*/
					
						$message='Thanks for your response.';
						echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						echo "<Response>
						<Sms>".$message."</Sms>
						</Response>";
						exit;
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
						
                                                $message=EMAIL_CAPTURE_MSG;

                                                $length = strlen(utf8_decode(substr($message,0,1600)));
                                                if (strlen($message) != strlen(utf8_decode($message))){
		                                    $credits = ceil($length/70) + 1;
                                                }else{
                                                    $credits = ceil($length/160) + 1;
                                                }
 
						if($this->User->id!=''){
							 //$this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-1));
                                                           $this->User->saveField('sms_balance', ($someoneuser['User']['sms_balance']-$credits));
						}
						
						/*Controller::loadModel('Log');
						$this->Log->create();
						$this->data['Log']['user_id'] = $someone['User']['id']; 
						$this->data['Log']['phone_number'] = $_REQUEST['From']; 
						$this->data['Log']['text_message'] = $_REQUEST['Body'];
                                                $this->data['Log']['sms_status'] = 'received';
						$this->data['Log']['route'] = 'inbox'; 
						$this->Log->save($this->data);*/
						
						echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						echo "<Response>
						<Sms>".$message."</Sms>
						</Response>";
						}
                                                $this->smsmail($someone['User']['id']);
						exit;
				}
			}
		}

       		
		app::import('Model','Group');
		$this->Group = new Group();
		$group=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$_REQUEST['Body'] ,array('Group.user_id'=>$someone['User']['id']))));
		
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
		$contestkeywords = $this->Contest->find('first',array('conditions'=>array('Contest.id '=>$contest_id,'Contest.keyword'=>$_REQUEST['Body'])));
		
		app::import('Model','Option');
	    $this->Option = new Option();
		$answers123=$this->Option->find('first',array('conditions'=>array('Option.question_id'=>$question_id,'Option.optionb'=>$_REQUEST['Body'])));
		
		app::import('Model','Smsloyalty');
	    $this->Smsloyalty = new Smsloyalty();
		$smsloyalty_arr=$this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.user_id'=>$user_id,'Smsloyalty.coupancode'=>strtoupper($_REQUEST['Body']))));
		
		$smsloyalty_status=$this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.user_id'=>$user_id,'Smsloyalty.codestatus'=>strtoupper($_REQUEST['Body']))));
		
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
						$credits = 2;
						$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_status['Smsloyalty']['checkstatus']);
						$msg=str_replace('%%STATUS%%',$loyaltyuser['SmsloyaltyUser']['count_trial'],$message);
                                                $statusmsg=str_replace('%%GOAL%%',$smsloyalty_status['Smsloyalty']['reachgoal'],$msg);
						header("content-type: text/xml");
						echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						echo "<Response>
						<Sms>".$statusmsg."</Sms>
						</Response>";
                                                $this->smsmail($someone['User']['id']);
					}
				}else{
					$credits = 2;
					$message="You are not eligible to participate since you are not subscribed to our opt-in list. Please text in ".$smsloyalty_status['Group']['keyword']." to be added to our opt-in list.";
					header("content-type: text/xml");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					echo "<Response>
					<Sms>".$message."</Sms>
					</Response>";
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
						$credits = 2;
						$message="Loyalty program ".$smsloyalty_arr['Smsloyalty']['program_name']." hasn't started yet. It begins on ".date('m/d/Y',strtotime($smsloyalty_arr['Smsloyalty']['startdate']))."";
						header("content-type: text/xml");
						echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						echo "<Response>
						<Sms>".$message."</Sms>
						</Response>";
                                                $this->smsmail($someone['User']['id']);
					}else if($smsloyalty_arr['Smsloyalty']['enddate'] < $current_date){
						$credits = 2;
						$message="Loyalty program ".$smsloyalty_arr['Smsloyalty']['program_name']." ended on ".date('m/d/Y',strtotime($smsloyalty_arr['Smsloyalty']['enddate']))."";
						header("content-type: text/xml");
						echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						echo "<Response>
						<Sms>".$message."</Sms>
						</Response>";
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
								$loyalty_user['SmsloyaltyUser']['keyword'] =$_REQUEST['Body'];
								$loyalty_user['SmsloyaltyUser']['count_trial'] =1;
								$loyalty_user['SmsloyaltyUser']['msg_date'] =$currentdate;
								$loyalty_user['SmsloyaltyUser']['created'] =date('Y-m-d H:i:s');
								if($smsloyalty_arr['Smsloyalty']['reachgoal']==1){
									$loyalty_user['SmsloyaltyUser']['is_winner'] =1;
									if($this->SmsloyaltyUser->save($loyalty_user)){
										if($smsloyalty_arr['Smsloyalty']['type']==1){
											$credits = 2;
											$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['reachedatgoal']);
											$msg=str_replace('%%STATUS%%',$count_trial,$message);
											$redeem = "Click link to redeem ".SITE_URL."/users/redeem/".$loyalty_user['SmsloyaltyUser']['unique_key']."";
											$sms = $msg.' '.$redeem;
											header("content-type: text/xml");
											echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
											echo "<Response>
											<Sms>".$sms."</Sms>
											</Response>";
                                            $this->smsmail($someone['User']['id']);
										}else if($smsloyalty_arr['Smsloyalty']['type']==2){
											$credits = 3;
											$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['reachedatgoal']);
											$msg=str_replace('%%STATUS%%',$count_trial,$message);
											$redeem = "Click link to redeem ".SITE_URL."/users/redeem/".$loyalty_user['SmsloyaltyUser']['unique_key']."";
											$sms = $msg.' '.$redeem;
											header("content-type: text/xml");
											echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
											echo "<Response>
												<Message>
													<Body>".$sms."</Body>
													<Media>".SITE_URL.'/mms/'.$smsloyalty_arr['Smsloyalty']['image']."</Media>
												</Message>
											</Response>";
                                            $this->smsmail($someone['User']['id']);
										}
										
									}
								}else{
                                    $this->SmsloyaltyUser->save($loyalty_user);
									$credits = 2;
									$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['addpoints']);
									$msg=str_replace('%%STATUS%%',1,$message);
									header("content-type: text/xml");
									echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
									echo "<Response>
									<Sms>".$msg."</Sms>
									</Response>";
                                    $this->smsmail($someone['User']['id']);
								}
                            }else{
								$credits = 2;
								$message="You have already redeemed your reward today.";
								header("content-type: text/xml");
								echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
								echo "<Response>
								<Sms>".$message."</Sms>
								</Response>";
								$this->smsmail($someone['User']['id']);
							}
							
					}else if($loyaltyuser['SmsloyaltyUser']['msg_date'] < $currentdate ){
							$count_trial = $loyaltyuser['SmsloyaltyUser']['count_trial']+1;
							$loyalty_user['SmsloyaltyUser']['id'] = $loyaltyuser['SmsloyaltyUser']['id'];
							$loyalty_user['SmsloyaltyUser']['user_id'] = $smsloyalty_arr['Smsloyalty']['user_id'];
							$loyalty_user['SmsloyaltyUser']['sms_loyalty_id'] = $smsloyalty_arr['Smsloyalty']['id'];
							$loyalty_user['SmsloyaltyUser']['contact_id'] =$contactgroupid['ContactGroup']['contact_id'];
							$loyalty_user['SmsloyaltyUser']['keyword'] =$_REQUEST['Body'];
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
											$credits = 2;
											$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['reachedatgoal']);
											$msg=str_replace('%%STATUS%%',$count_trial,$message);
											$redeem = "Click link to redeem ".SITE_URL."/users/redeem/".$loyaltyuser_list['SmsloyaltyUser']['unique_key']."";
											$sms = $msg.' '.$redeem;
											header("content-type: text/xml");
											echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
											echo "<Response>
											<Sms>".$sms."</Sms>
											</Response>";
                                                                                        $this->smsmail($someone['User']['id']);
										}else if($smsloyalty_arr['Smsloyalty']['type']==2){
											$credits = 3;
											$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['reachedatgoal']);
											$msg=str_replace('%%STATUS%%',$count_trial,$message);
											$redeem = "Click link to redeem ".SITE_URL."/users/redeem/".$loyaltyuser_list['SmsloyaltyUser']['unique_key']."";
											$sms = $msg.' '.$redeem;
											header("content-type: text/xml");
											echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
											echo "<Response>
												<Message>
													<Body>".$sms."</Body>
													<Media>".SITE_URL.'/mms/'.$smsloyalty_arr['Smsloyalty']['image']."</Media>
												</Message>
											</Response>";
                                            $this->smsmail($someone['User']['id']);
										}
										
									}
								}else{
									$credits = 2;
									$message=str_replace('%%Name%%',$contactgroupid['Contact']['name'],$smsloyalty_arr['Smsloyalty']['addpoints']);
									$msg=str_replace('%%STATUS%%',$count_trial,$message);
									header("content-type: text/xml");
									echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
									echo "<Response>
									<Sms>".$msg."</Sms>
									</Response>";
                                    $this->smsmail($someone['User']['id']);
								}
							}
						}else if($loyaltyuser['SmsloyaltyUser']['is_winner'] ==1){
							$credits = 2;
							$message="You have already reached the goal of ".$smsloyalty_arr['Smsloyalty']['reachgoal']." points. Please redeem your reward.";
							header("content-type: text/xml");
							echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
							echo "<Response>
							<Sms>".$message."</Sms>
							</Response>";
                            $this->smsmail($someone['User']['id']);
						}else{
							$credits = 2;
							header("content-type: text/xml");
							$message="You already punched your card today. Stop in tomorrow for the new punch code.";
							echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
							echo "<Response>
							<Sms>".$message."</Sms>
							</Response>";
                            $this->smsmail($someone['User']['id']);
						}
					}
					
				}else{
					$credits = 2;
					$message="You are not eligible to participate since you are not subscribed to our opt-in list. Please text in ".$smsloyalty_arr['Group']['keyword']." to be added to our opt-in list.";
					header("content-type: text/xml");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					echo "<Response>
					<Sms>".$message."</Sms>
					</Response>";
                    $this->smsmail($someone['User']['id']);
				}
				if($credits > 0){
					$update_user['User']['id'] = $someone['User']['id'];
					$update_user['User']['sms_balance'] = $someone['User']['sms_balance']-$credits;
					$this->User->save($update_user);
				}
			
			}
		}else if(strtoupper(trim($_REQUEST['Body']))=='START'){
			app::import('Model','User');
			$this->User = new User();
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
				$this->smsmail($someone['User']['id']);
				//$message='You have successfully un-subscribed from our list';
				/* $message='You have successfully been unsubscribed.';
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
				echo "<Response>
				<Sms>".$message."</Sms>
				</Response>";
				exit; */
			}
	    }else if(strtoupper(trim($_REQUEST['Body']))=='STOP'){
			app::import('Model','User');
			$this->User = new User();
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
				$this->smsmail($someone['User']['id']);
				/*$message='You have successfully been unsubscribed.';
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
				echo "<Response>
				<Sms>".$message."</Sms>
				</Response>";*/
				exit;
			}
		}else if(!empty($group)){
		    $keyword=$_REQUEST['Body'];
	        $sender_number=$_REQUEST['To'];
	        $this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
            $this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
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
$current_datetime=date("n/d/Y");
$message = str_replace('%%CURRENTDATE%%',$current_datetime,$message);
							if($sms_type == 1){
								$this->Twilio->sendsms($phone,$sender_number,$message);
								$this->Immediatelyresponder($user_id,$group_id,$phone,$sender_number);
							}else if($sms_type == 2){
								$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
								$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;			
								$message_arr=explode(',',$image_url);
								$this->Mms->sendmms($phone,$sender_number,$message_arr,$message);
								//sleep(2);	
								$this->Immediatelyresponder($user_id,$group_id,$phone,$sender_number);	
							}
							$curcredits = $someone['User']['sms_balance'];
                                                        $length = strlen(utf8_decode(substr($message,0,1600))); 
                                                        
                                                        if($sms_type == 1){
                                                           if (strlen($message) != strlen(utf8_decode($message))){
		                                               $credits = ceil($length/70) + 1;
                                                            }else{
                                                               $credits = ceil($length/160) + 1;
                                                            }
                                                        }else{
                                                            $credits = 3;
                                                        }
                                                        //$this->data['User']['sms_balance']=$credits-2;
                                                        $this->data['User']['sms_balance']=$curcredits-$credits;
							$this->data['User']['id']=$user_id;
							$this->User->save($this->data); 
							$this->smsmail($someone['User']['id']);


							if($someone['User']['capture_email_name']==0){
								$capture_email_name=NAME_CAPTURE_MSG;
								$response = $this->Twilio->sendsms($phone,$sender_number,$capture_email_name);
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
									$response = $this->Twilio->sendsms($phone,$sender_number,$birthday_wishes);
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
						           $response = $this->Twilio->sendsms($mobile,$sender_number,$message);
						
						           $someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));

                                                           if(!empty($someone_users)){
							      $user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
							      $user_credit['User']['id']=$user_id;
							      $this->User->save($user_credit); 
						           }
                                                       }
						}else{
							//$credits = $someone['User']['sms_balance'];
							//$this->data['User']['sms_balance']=$credits-2;
                                                        //$this->data['User']['id']=$user_id;
							//$this->User->save($this->data);  
							//$this->smsmail($someone['User']['id']);  
			 
							if($group_type==0){
								$message= $system_message.' '.$auto_message;
$current_datetime=date("n/d/Y");
$message = str_replace('%%CURRENTDATE%%',$current_datetime,$message);
								if($sms_type == 1){	
									$this->Twilio->sendsms($phone,$sender_number,$message);
									sleep(2);
									$this->Immediatelyresponder($user_id,$group_id,$phone,$sender_number);
								}else if($sms_type == 2){
									$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
									$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;	
									$message_arr=explode(',',$image_url);
									$this->Mms->sendmms($phone,$sender_number,$message_arr,$message);
									sleep(2);
									$this->Immediatelyresponder($user_id,$group_id,$phone,$sender_number);			
								}

                                                                $curcredits = $someone['User']['sms_balance'];
                                                                $length = strlen(utf8_decode(substr($message,0,1600))); 
                                                        
                                                                if($sms_type == 1){
                                                                   if (strlen($message) != strlen(utf8_decode($message))){
		                                                      $credits = ceil($length/70) + 1;
                                                                   }else{
                                                                      $credits = ceil($length/160) + 1;
                                                                   }
                                                                }else{
                                                                      $credits = 3;
                                                                }
                                                               //$this->data['User']['sms_balance']=$credits-2;
                                                                 $this->data['User']['sms_balance']=$curcredits-$credits;
                                                                 $this->data['User']['id']=$user_id;
							         $this->User->save($this->data);  
							         $this->smsmail($someone['User']['id']);  

                                                                 $name = $contactgroupid['Contact']['name'];
                                                                 $email = $contactgroupid['Contact']['email'];
                                                                 $bday = $contactgroupid['Contact']['birthday'];
								
								 if($someone['User']['capture_email_name']==0 && $name ==''){
									$capture_email_name=NAME_CAPTURE_MSG;
									$response = $this->Twilio->sendsms($phone,$sender_number,$capture_email_name);
									sleep(2);
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
										$response = $this->Twilio->sendsms($phone,$sender_number,$birthday_wishes);
										sleep(2);
								
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
                                                                }elseif ($group_type==3){
                                                                   $message='We have already sent you information on this vehicle.';
                                                                }else{
								   $message='You are already subscribed to this list.';
                                                                }
								     
                                                                echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
								echo "<Response>
								<Sms>".$message."</Sms>
								</Response>";

                                                                $user_credit['User']['sms_balance']=$someone['User']['sms_balance']-2;
								$user_credit['User']['id']=$user_id;
								$this->User->save($user_credit);
                                                                $this->smsmail($user_id);
								exit;
                                                       
							}
						}  
					} else {
					
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

						if($sms_type == 1){	
							$this->Twilio->sendsms($phone,$sender_number,$message);
							$this->Immediatelyresponder($user_id,$group_id,$phone,$sender_number);
						}else if($sms_type == 2){
							$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
							$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;				
							$message_arr=explode(',',$image_url);
							$this->Mms->sendmms($phone,$sender_number,$message_arr,$message);	
							$this->Immediatelyresponder($user_id,$group_id,$phone,$sender_number);			
						}	
						//$credits = $someone['User']['sms_balance'];
						//$this->data['User']['sms_balance']=$credits-2;
                                                $curcredits = $someone['User']['sms_balance'];
                                                $length = strlen(utf8_decode(substr($message,0,1600))); 
                                                        
                                                if($sms_type == 1){
                                                   if (strlen($message) != strlen(utf8_decode($message))){
		                                       $credits = ceil($length/70) + 1;
                                                   }else{
                                                       $credits = ceil($length/160) + 1;
                                                   }
                                                }else{
                                                   $credits = 3;
                                                }
                                                //$this->data['User']['sms_balance']=$credits-2;
                                                $this->data['User']['sms_balance']=$curcredits-$credits;
						$this->data['User']['id']=$user_id;
						$this->User->save($this->data); 
						$this->smsmail($someone['User']['id']);
						
						if($someone['User']['capture_email_name']==0){
							$capture_email_name=NAME_CAPTURE_MSG;
							$response = $this->Twilio->sendsms($phone,$sender_number,$capture_email_name);
							sleep(2);
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
								$response = $this->Twilio->sendsms($phone,$sender_number,$birthday_wishes);
								sleep(2);
						
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
						   $response = $this->Twilio->sendsms($mobile,$sender_number,$message);
						
						   $someone_users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));

                                                   if(!empty($someone_users)){
							$user_credit['User']['sms_balance']=$someone_users['User']['sms_balance']-1;
							$user_credit['User']['id']=$user_id;
							$this->User->save($user_credit); 
						   }
                                               }
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
					//pr($ansersubs);
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
							$this->data['User']['sms_balance']=$credits-2;
							$this->data['User']['id']=$user_id;
							$this->User->save($this->data); 
	 
							$this->smsmail($users['User']['id']);
							if($autorsponder_message!=''){
								$message=$autorsponder_message;
							}else{
								$message= $answers123['Question']['autoreply_message'];
							}
							//echo $message;
							echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
							echo "<Response>
							<Sms>".$message."</Sms>
							</Response>"; 
							exit;
						}else{
							app::import('Model','User');
							$this->User = new User();
							$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
							$credits = $users['User']['sms_balance'];
							$this->data['User']['sms_balance']=$credits-2;
							$this->data['User']['id']=$user_id;
							$this->User->save($this->data);
							$this->smsmail($users['User']['id']);
							$message= "You have already voted in this poll";
						   
							echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
							echo "<Response>
								<Sms>".$message."</Sms>
								</Response>"; 
							exit;
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
					$this->data['User']['sms_balance']=$credits-2;
					$this->data['User']['id']=$user_id;
					$this->User->save($this->data); 
		
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					echo "<Response>
					<Sms>".$contestkeywords['Contest']['system_message']."</Sms>
					</Response>"; 
					exit;
		
				}else{
					app::import('Model','User');
					$this->User = new User();
					$usersdetails = $this->User->find('first', array('conditions' => array('User.id'=>$contestkeywords['Contest']['user_id'])));		
					$credits = $usersdetails['User']['sms_balance'];
					$this->data['User']['sms_balance']=$credits-2;
					$this->data['User']['id']=$user_id;
					$this->User->save($this->data);
					$message1= "You have already entered into this contest";
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					echo "<Response>
					<Sms>".$message1."</Sms>
					</Response>"; 
					exit;
				}
			
                          
                          }else{
				if($someone['User']['sms_balance'] > 0){
					$this->User->id = $someone['User']['id'];
					if($this->User->id!=''){

                                           $length = strlen(utf8_decode(substr($_REQUEST['Body'],0,1600)));
                                           if (strlen($_REQUEST['Body']) != strlen(utf8_decode($_REQUEST['Body']))){
		                                   $credits = ceil($length/70);
                                           }else{
                                                   $credits = ceil($length/160);
                                           }
					   //$this->User->saveField('sms_balance', ($someone['User']['sms_balance']-1));
                                             $this->User->saveField('sms_balance', ($someone['User']['sms_balance']-$credits));
					}


					Controller::loadModel('Log');
					$this->Log->create();
					$this->data['Log']['user_id'] = $someone['User']['id']; 
					$this->data['Log']['phone_number'] = $_REQUEST['From']; 
                                        $this->data['Log']['name'] = $contactname; 
                                        $this->data['Log']['contact_id'] = $contact_id; 
                                        $this->data['Log']['email_to_sms_number'] = $_REQUEST['To'];
					$this->data['Log']['text_message'] = $_REQUEST['Body'];
                                        $this->data['Log']['created'] = date('Y-m-d H:i:s',time());
					if(isset($_REQUEST['SmsStatus'])){
						$this->data['Log']['sms_status'] = $_REQUEST['SmsStatus'];
					}
					if(isset($_REQUEST['MediaUrl0'])){
						$image_url='';
						for($i=0;$i<10;$i++){
							if($image_url!=''){
								if(isset($_REQUEST["MediaUrl".$i])){
									$image_url = $image_url.','.$_REQUEST["MediaUrl".$i];
								}
							}else{
								$image_url=$_REQUEST["MediaUrl".$i];
							}
						}
						$this->data['Log']['image_url'] =$image_url; 
					}			
					$this->data['Log']['inbox_type'] = 1; 
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
						$this->set('message',$_REQUEST['Body']);
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
					        $this->Email->Controller->set('body', $_REQUEST['Body']);
						$this->Email->send();
					}elseif ($someone['User']['incomingsms_emailalerts']==2){
						$this->Log = new Log();
						//$firstName = $Subscribers['Contact']['name'];
						$message = "Incoming SMS Alert From: " .$_REQUEST['From']." - ".$_REQUEST['Body'];
						$to = $username = $someone['User']['smsalerts_number'];
						$from = $_REQUEST['To']; 
						//$from = '2029993169';
						$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
						$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
						$response = $this->Twilio->sendsms($to,$from,$message);
						 //pr($response);
						$smsid=$response->ResponseXml->Message->Sid;
						$Status=$response->ResponseXml->RestException->Status;
                                                $this->data['Log']['contact_id'] = 0;
						$this->data['Log']['group_sms_id'] =0;
						$this->data['Log']['sms_id'] =$smsid;
						$this->data['Log']['user_id'] =$user_id;
						$this->data['Log']['group_id'] =0;				   
						$this->data['Log']['phone_number']=$to;
						$this->data['Log']['text_message']= $message;
						$this->data['Log']['route']= 'outbox';
						$this->data['Log']['sms_status']= '';
						$this->data['Log']['error_message']='';
						if($Status==400){
							$this->data['Log']['sms_status']= 'failed';
							$ErrorMessage = $response->ErrorMessage;
							$this->data['Log']['error_message']=$ErrorMessage;
						}
						$this->Log->save($this->data);
			
					}
				}
			}
		}
	}

	function voice(){
		ob_start();
		$this->autoRender=false;
		$_REQUEST['To'] = str_replace('+','',$_REQUEST['Called']);
		// $_REQUEST['From'] = str_replace('+1','',$_REQUEST['Caller']);
		$_REQUEST['From'] = str_replace('+','',$_REQUEST['Caller']);
		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
                $this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
		Controller::loadModel('User');
		$someone = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));
		}
		date_default_timezone_set($someone['User']['timezone']);
                $active = $someone['User']['active'];
		if($someone['User']['voice_balance'] > 0 && $active == 1){
			if($someone['User']['incomingcall_forward']==0 && $someone['User']['assign_callforward']==trim($_REQUEST['To'])){
			        header("content-type: text/xml");
				echo '<?xml version="1.0" encoding="UTF-8"?>';
				echo '<Response>';
				echo  '<Dial action="'.SITE_URL.'/twilios/voicerecord" record="false">'.$someone['User']['callforward_number'].'</Dial>';
				echo '</Response>';

			}else{
				$this->User->id = $someone['User']['id'];
				$this->User->saveField('voice_balance', ($someone['User']['voice_balance']-1));
				//saving logs
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
				Controller::loadModel('Log');
				$this->Log->create();
				$this->data['Log']['user_id'] = $someone['User']['id']; 
				$this->data['Log']['phone_number'] = $_REQUEST['From']; 
				$this->data['Log']['voice_url'] = $_REQUEST['CallSid']; 
				$this->data['Log']['route'] = 'inbox'; 
				$this->data['Log']['msg_type'] = 'voice'; 
				$this->Log->save($this->data);
				$out1 = ob_get_contents();
				ob_end_clean();
				$file = fopen("debug/twiliocall".time().".txt", "w");
				fwrite($file, $out1); 
				fclose($file); 

				if($someone['User']['welcome_msg_type']==1){
					$msg = "<Play>".SITE_URL.'/mp3/'.$someone['User']['mp3']."</Play>";
				}else{
					$msg = "<Say>".$someone['User']['defaultgreeting']."</Say>";
				}
				
				header("content-type: text/xml");
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
				echo "<Response>";
				echo  $msg;	  
				echo "<Record
							action='".SITE_URL."/twilios/voicerecording'
							method='POST'
							maxLength='40'
							finishOnKey='*'
							/>
						<Say>I did not receive a recording</Say>
				</Response>";
				exit;
			}
		}else{
			header("content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			echo "<Response>
				</Response>";
			exit;
		}
	}
	
	function voicerecord(){
		$this->autoRender=false;
		$_REQUEST['To'] = str_replace('+','',$_REQUEST['Called']);
		$_REQUEST['From'] = str_replace('+','',$_REQUEST['Caller']);
		Controller::loadModel('User');
		$someone = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
		//$someone = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
		if(empty($someone)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));
		}
		$this->Log->create();
		$this->data['Log']['user_id'] = $someone['User']['id']; 
		$this->data['Log']['phone_number'] = $_REQUEST['From'];
		if(isset($_REQUEST['RecordingUrl'])){
			$url_to_image = $_REQUEST['RecordingUrl'].'.mp3';
			$filename = basename($url_to_image);
			$this->data['Log']['voice_url'] = SITE_URL.'/audio/'.$filename;
			$this->savefile($url_to_image);			
		}
		$this->data['Log']['route'] = 'inbox'; 
		$this->data['Log']['msg_type'] = 'callforward'; 
                $this->data['Log']['call_duration'] = gmdate("H:i:s", $_REQUEST['DialCallDuration']); 
		if(isset($_REQUEST['DialCallDuration'])){
			$minute = gmdate("H:i:s", $_REQUEST['DialCallDuration']);
			$minute_arr = explode(':',$minute);
			$hour = 0;
			$mint = 0;
			$secnd = 0;
			if($minute_arr[0] !='00'){
				$hour = $minute_arr[0] * 60;
			}
			if($minute_arr[1]  > 0){
				$mint = $minute_arr[1];
			}
			if($minute_arr[2]  > 0){
				$secnd = 1;
			}
			$minutes = $hour+$mint+$secnd;
			
		}
		if($this->Log->save($this->data)){
			if($minutes > 0){
				$totalminutes = $minutes * 2;
				$voice_balance = $someone['User']['voice_balance'] - $totalminutes;
				$this->User->id = $someone['User']['id'];
				$this->User->saveField('voice_balance',$voice_balance);

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
		header("content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		echo "<Response>
		</Response>";
		exit;
	}
	
	function voicerecording(){
		ob_start();
		$this->autoRender=false;
		$_REQUEST['To'] = str_replace('+','',$_REQUEST['Called']);
		$_REQUEST['From'] = str_replace('+','',$_REQUEST['Caller']);
		pr($_REQUEST);	
		$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
                $this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
		Controller::loadModel('Log');
                $this->Log->recursive = -1;
		$someoneLog = $this->Log->find('first', array('conditions' => array('voice_url' =>''.trim($_REQUEST['CallSid']).'')));
		if(isset($someoneLog['Log']['id']) && isset($_REQUEST['RecordingUrl'])){
			$this->Log->id = $someoneLog['Log']['id'];
			$url_to_image = $_REQUEST['RecordingUrl'].'.mp3';
			$filename = basename($url_to_image);
			$this->Log->saveField('voice_url', SITE_URL.'/audio/'.$filename);
			//$this->Log->saveField('text_message', $_REQUEST['TranscriptionText']);
			$this->savefile($url_to_image);
		}
		if(isset($_REQUEST['TranscriptionText'])){
                  	Controller::loadModel('User');
			$someone = $this->User->find('first', array('conditions' => array('assigned_number' =>''.trim($_REQUEST['To']).'')));
			if(empty($someone)){
				app::import('Model','UserNumber');
				$this->UserNumber = new UserNumber();
				$someone=$this->UserNumber->find('first',array('conditions'=>array('UserNumber.number'=>''.trim($_REQUEST['To']).'')));

			}
			if(!empty($someone)){
			#pr($someone);die();
				$this->User->id = $someone['User']['id'];
				$email = $someone['User']['voicemailnotifymail'];
				#$email = 'simersd@gmail.com';
				$id = $someone['User']['id'];
				$first_name = $someone['User']['first_name'];
				$sitename=str_replace(' ','',SITENAME);
				$subject="New Voicemail: ".SITENAME."";	
				$comment= SITE_URL;	
				#$this->Email->delivery = 'debug';	
				$this->Email->to = $email;	
				$this->Email->subject = $subject;
				$this->Email->from = $sitename;
				$this->Email->template = 'newvoicemail';
				$this->Email->sendAs = 'html';
				$this->Email->Controller->set('first_name', $first_name);
				$this->Email->Controller->set('comment', $comment);
				$this->Email->Controller->set('email', $email);
				$this->Email->send();
			}
			/********** Mail function ends*/
		}
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/reocrdinghandle".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);  
		header("content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			echo "<Response>
				  <Say>
					Thanks, we received recording.
					</Say>
			</Response>";
		exit; 
	}
	
	function callstatus(){
		sleep(2);
                //ob_start();
		$this->autoRender=false;
		//print_r($_REQUEST);
		app::import('Model','Log');
		$this->Log = new Log();
                $this->Log->recursive = -1;
		//$someone = $this->Log->find('first', array('conditions' => array('Log.sms_id' =>$_REQUEST['MessageSid'])));
		$someone = $this->Log->find('first', array('conditions' => array('Log.sms_id' =>$_REQUEST['SmsSid'])));

                //$length = strlen(utf8_decode(substr($_REQUEST['Body'],0,1600))); 
		//$credits = ceil($length/160);
                $message = trim($someone['Log']['text_message']);
                $msgtype = substr($_REQUEST['SmsSid'],0,2);

                $length = strlen(utf8_decode(substr($message,0,1600))); 
                if ($msgtype == "SM"){
                   if (strlen($message) != strlen(utf8_decode($message))){
		       $credits = ceil($length/70);
                   }else{
                       $credits = ceil($length/160);
                   }
                }else{
                   $credits = 2;
                }

//print_r($credits);
		if(!empty($someone)){
			$logid=$someone['Log']['id'];
			$user_id=$someone['Log']['user_id'];
			$CurStatus=$someone['Log']['sms_status'];
			$this->data['Log']['id']=$logid;
			$Status = $_REQUEST['MessageStatus'];
			//if (trim($CurStatus)!='delivered' && trim($CurStatus)!='undelivered' && trim($CurStatus)!='failed'){
				$this->data['Log']['sms_status'] = $Status;
			//}else{
			//	$this->data['Log']['sms_status'] = $Status;
			//}
			app::import('Model','GroupSmsBlast');			
			$this->GroupSmsBlast = new GroupSmsBlast(); 
			$GroupSmsBlast = $this->GroupSmsBlast->find('first', array('conditions' => array('GroupSmsBlast.id' =>$someone['Log']['group_sms_id'])));
			if(trim($Status)=='sent'){
				app::import('Model','User');
				$this->User = new User();			   
				$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));				  
                                 
                                $remainingcredits = $usersms['User']['sms_balance']-$credits;

				if($usersms['User']['email_alert_credit_options']==0){
       				if($remainingcredits <= $usersms['User']['low_sms_balances']){
	    				if($usersms['User']['sms_credit_balance_email_alerts']==0){
							$username = $usersms['User']['username'];
							$email = $usersms['User']['email'];
							//echo $phone = $usersmail['User']['assigned_number'];
							$sitename=str_replace(' ','',SITENAME);
							$subject="Low SMS Credit Balance";	
							$this->Email->to = $email;	
							$this->Email->subject = $subject;
							$this->Email->from = $sitename;
							$this->Email->template = 'low_sms_credit_template';
							$this->Email->sendAs = 'html';
							$this->Email->Controller->set('username', $username);
							$this->Email->Controller->set('low_sms_balances', $usersms['User']['low_sms_balances']);
							$this->Email->send();
							$this->User->id = $usersms['User']['id'];
							$this->User->saveField('sms_credit_balance_email_alerts',1);
						}	
		      		}
				}
                                $this->data['User']['sms_balance']=$remainingcredits;
				$this->data['User']['id']=$usersms['User']['id'];	  
				$this->User->save($this->data);		
				//$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));	
				
                                if(!empty($GroupSmsBlast['GroupSmsBlast']['id'])){	
					$this->data['GroupSmsBlast']['total_successful_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_successful_messages']+1;
                                        //$this->data['GroupSmsBlast']['total_successful_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_successful_messages']+$credits;
					$this->data['GroupSmsBlast']['id'] = $GroupSmsBlast['GroupSmsBlast']['id']; 
					$this->GroupSmsBlast->save($this->data);
				}
			}elseif (trim($Status)=='undelivered' || trim($Status)=='failed') {
				if(!empty($GroupSmsBlast['GroupSmsBlast']['id'])){	
					$this->data['GroupSmsBlast']['total_successful_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_successful_messages']-1;		
//$this->data['GroupSmsBlast']['total_successful_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_successful_messages']-$credits;		
					$this->data['GroupSmsBlast']['total_failed_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_failed_messages']+1; 
//$this->data['GroupSmsBlast']['total_failed_messages'] = $GroupSmsBlast['GroupSmsBlast']['total_failed_messages']+$credits; 
					$this->data['GroupSmsBlast']['id'] = $GroupSmsBlast['GroupSmsBlast']['id']; 
					$this->GroupSmsBlast->save($this->data);
				}

                                if (trim($Status)=='failed') {
				  app::import('Model','User');
				  $this->User = new User();   
				  $usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));				  
  				  $this->data['User']['sms_balance']=$usersms['User']['sms_balance']+$credits;
				  $this->data['User']['id']=$usersms['User']['id'];	  
				  $this->User->save($this->data);
                                }

				if ($_REQUEST['ErrorCode']=='30001')
					$ErrorMessage='You tried to send too many messages too quickly and your message queue overflowed. Try sending your message again after 	waiting some time.';
				elseif ($_REQUEST['ErrorCode']=='30002')
					$ErrorMessage='Your account was suspended between the time of message send and delivery. Please contact Twilio.';
				elseif ($_REQUEST['ErrorCode']=='30003')
					$ErrorMessage='The destination handset you are trying to reach is switched off or otherwise unavailable.';
				elseif ($_REQUEST['ErrorCode']=='30004')
					$ErrorMessage='The destination number you are trying to reach is blocked from receiving this message (e.g. due to blacklisting).';
				elseif ($_REQUEST['ErrorCode']=='30005')
					$ErrorMessage='The destination number you are trying to reach is unknown and may no longer exist.';
				elseif ($_REQUEST['ErrorCode']=='30006')
					$ErrorMessage='The destination number is unable to receive this message. Potential reasons could include trying to reach a landline or, in the case of short codes, an unreachable carrier.';	
				elseif ($_REQUEST['ErrorCode']=='30007')
					$ErrorMessage='Your message content was flagged as going against carrier guidelines.';
				elseif ($_REQUEST['ErrorCode']=='30008')
					$ErrorMessage='Unknown error. In most cases, this is a result of trying to reach a landline number or unreachable carrier.';
				$this->data['Log']['error_message'] = $ErrorMessage;
			} 
			$this->Log->save($this->data);
		}
		//$out1 = ob_get_contents();
		//ob_end_clean();
		//$file = fopen("callstatusdebug/statushandle".time().".txt", "w");
		//fwrite($file, $out1); 
		//fclose($file);
	}
	
	function smsmail($user_id=null){
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
	}
	
	function peoplecallrecordscript($group_id=null,$loop=null,$language=null,$pause=null){
		$this->autoRender=false;
		app::import('Model','VoiceMessage');
		$this->VoiceMessage = new VoiceMessage();
		$VoiceMessage=$this->VoiceMessage->find('first',array('conditions' => array('VoiceMessage.group_id'=>$group_id)));
		$message_type  = $VoiceMessage['VoiceMessage']['message_type'];
		if($message_type == 1){  // audio url
			$audio_url  = SITE_URL.'/voice/'.$VoiceMessage['VoiceMessage']['audio']; // audio file url
			header("content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			echo "<Response>";
			echo "<Gather action='".SITE_URL."/twilios/do_not_call/".$group_id."' timeout='20' numDigits='5' finishOnKey='*'>";
            echo "<Pause length='".$pause."'/>";				
            echo "<Play loop='".$loop."'>".$audio_url."</Play>";
			echo "<Say language='".$language."'>Please press 1 for do not call.</Say>";
			echo "</Gather>";
			echo "</Response>";
			die; 
	
		}else{  // text to voice
			$msg = $VoiceMessage['VoiceMessage']['text_message'];
                   	header("content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			echo "<Response>";
			echo "<Gather action='".SITE_URL."/twilios/do_not_call/".$group_id."' timeout='20' numDigits='5' finishOnKey='*'>";
            echo "<Pause length='".$pause."'/>";		
            echo "<Say language='".$language."' loop='".$loop."'>".$msg."</Say>";
			echo "<Say language='".$language."'>Please press 1 for do not call and then press star.</Say>";
			echo "</Gather>";
			echo "</Response>";
			die; 
		}		
    }
	  
	function do_not_call($group_id=null){
		$this->autoRender=false;
		
		if($_REQUEST['Digits']==1){
			$phone  = str_replace('+','',$_REQUEST['Called']);
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
					echo "<Response><Say>You have successfully been un-subscribed and added to the do not call list</Say></Response>";
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
	  
	function sendcallStatus($lastinsertID=null){
		$this->autoRender=false;
			if($lastinsertID > 0){
				if(isset($_REQUEST['RecordingUrl'])){
				Controller::loadModel('Log');
				app::import('Model','Log');
				$this->Log = new Log();
                                $this->Log->recursive = -1;
				$someone = $this->Log->find('first', array('conditions' => array('Log.id' =>$lastinsertID)));
				if(!empty($someone)){
					app::import('Model','User');
					$this->User = new User();	   
					$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$someone['Log']['user_id'])));
					if(!empty($usersms)){			
						$arr['User']['voice_balance']=$usersms['User']['voice_balance']-1;
						$arr['User']['id']=$usersms['User']['id'];
						$this->User->save($arr);
						$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$someone['Log']['user_id'])));
						if($usersms['User']['email_alert_credit_options']==0){
							if($usersms['User']['voice_balance'] <= $usersms['User']['low_voice_balances']){
								if($usersms['User']['VM_credit_balance_email_alerts']==0){
									$username = $usersms['User']['username'];
									$email = $usersms['User']['email'];
									$sitename=str_replace(' ','',SITENAME);
									$subject="Low Voice Credit Balance";	
									$this->Email->to = $email;	
									$this->Email->subject = $subject;
									$this->Email->from = $sitename;
									$this->Email->template = 'low_voice_credit_template';
									$this->Email->sendAs = 'html';
									$this->Email->Controller->set('username', $username);
									$this->Email->Controller->set('low_voice_balances',$usersms['User']['low_voice_balances']);
									$this->Email->send();
									$this->User->id = $usersms['User']['id'];
									$this->User->saveField('VM_credit_balance_email_alerts',1);
								}
							}
						}
					}
				}
				$this->data['Log']['id'] = $lastinsertID; 
				$this->data['Log']['voice_url'] = $_REQUEST['RecordingUrl'];
				$this->data['Log']['sms_status'] = $_REQUEST['CallStatus'];
			}
			$this->Log->save($this->data);
		}
		ob_start();
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("debug/sendcallStatus".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);  
	}
	  
	function Immediatelyresponder($user_id=null,$group_id=null,$to=null,$from=null){
		$this->autoRender=false;
		app::import('Model','Responder');	
		$this->Responder = new Responder(); 
		app::import('Model','User');	
		$this->User = new User();
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
				if($users['User']['sms_balance'] > 0){
					$Responderid = $response['Responder']['id'];
					$group_id = $response['Responder']['group_id'];
					$sms_type = $response['Responder']['sms_type'];
					$image_url = $response['Responder']['image_url'];
					$message = str_replace('%%CURRENTDATE%%',$current_datetime,$response['Responder']['message']);
					$systemmsg = $response['Responder']['systemmsg'];
					$user_id = $response['Responder']['user_id'];
					$body = $message."\n".$systemmsg;
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
                                 

				}
			}
		}
	}
	function random_generator($digits){
	srand ((double) microtime() * 10000000);
    $input = array("A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z",
						"1","2","3","4","5","6","7","8","9");
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
	function savefile($url_to_image=null){
		$this->autoRender=false;
		$ch = curl_init($url_to_image);
		$my_save_dir = 'audio/';
		$filename = basename($url_to_image);
		$complete_save_loc = $my_save_dir . $filename;
		$fp = fopen($complete_save_loc, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
}
?>