<?php
class POllsController extends AppController {
	var $uses = array();
	var $name = 'Polls';
	var $components = array('Cookie','Email','Twilio','Nexmomessage','Slooce','Plivo');
	function question_list(){
		$this->layout='admin_new_layout';
		app::import('Model','Question');
		$this->Question = new Question();
		$this->Question->recursive = 0;
		$this->set('questions', $this->paginate('Question', array('Question.user_id' => $this->Session->read('User.id'))));
	}
	   
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Poll', true));
			$this->redirect(array('action'=>'question_list'));
		}
	    app::import('Model','Question');
        $this->Question = new Question();
		if ($this->Question->delete($id)) {
			app::import('Model','Option');
			$this->Option = new Option();
			$this->Option->deleteAll(array('Option.question_id'=>$id));
			$this->Session->setFlash(__('Poll Deleted', true));
			$this->redirect(array('action'=>'question_list'));
		}
	}
		
	function index(){
		$this->layout='admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$this->set('users',$users);
		if(!empty($this->data)){
		   // pr($this->data);
		  // foreach($this->data['Option']['optiona'] as $option){
			// echo $option;
		  // }
		  // exit();
		  
		   //$this->data['Question']['code'] = strtoupper($this->data['Question']['code']);
			app::import('Model','Question');
			$this->Question = new Question();
			$this->data['Question']['user_id']=$user_id;
			$this->Question->save($this->data); 
			$question_id=$this->Question->id;
			$optionb=array('0'=>'A','1'=>'B','2'=>'C','3'=>'D');
			$automessage=array();
			foreach($this->data['Option']['autorsponder_message']as $automessage){
				$auto_message[] = $automessage;
			}
			$count = 0;
			$i = 0;
			foreach($this->data['Option']['optiona'] as $option){
				app::import('Model','Option');
				$this->Option = new Option();
				$this->data['Option']['question_id']=$question_id;
				
				$this->data['Option']['optiona']=$option;
				
				//$optionb[$count];
				
				$this->data['Option']['autorsponder_message']=$auto_message[$i];
				$this->data['Option']['optionb']=$optionb[$count];
				$this->Option->save($this->data); 
				$count++;
				$i++;
			}
			$this->Session->setFlash(__('The Poll has been saved', true));
			$this->redirect(array('action' => 'question_list'));
        	  
        }
	}
			  
	function send_question($id=null){
		$this->layout = 'popup';
		$this->set('id',$id);
		$user_id=$this->Session->read('User.id');
		app::import('Model','Group');
		$this->Group = new Group();
		$group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
		$this->set('Group',$group);
		app::import('Model','User');
		$this->User = new User();
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$credits = $users['User']['sms_balance'];
		$this->Session->write('User.sms_balance',$users['User']['sms_balance']);
		$this->Session->write('User.assigned_number',$users['User']['assigned_number']);
		$this->Session->write('User.active',$users['User']['active']);
		$this->Session->write('User.pay_activation_fees_active',$users['User']['pay_activation_fees_active']);
		app::import('Model','Option');
		$this->Option = new Option();
		$questionmessage = $this->Option->find('all',array('conditions' => array('Option.question_id'=>$id),'order'=>array('Option.id'=>'ASC')));
		$this->set('questionmessages',$questionmessage);
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$this->set('users',$users);
		if(!empty($this->data)){
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
			$users_arr = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.sms'=>1)));
			$rotate_number = $this->data['User']['rotate_number'];
			if((!empty($user_numbers)) || (!empty($users_arr))){	
				
				app::import('Model','ContactGroup');
				$this->ContactGroup = new ContactGroup();
				$Subscriber = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$this->data['Group']['id'],'ContactGroup.un_subscribers'=>0)));
				 //pr($Subscriber);
				if(empty($Subscriber)){
					$this->Session->setFlash(__('Add contacts to this group or select a different group.', true));
					$this->redirect(array('controller' =>'polls', 'action'=>'question_list'));
				}

                app::import('Model','Option');
				$this->Option = new Option();
				$questions = $this->Option->find('all',array('conditions' => array('Option.question_id'=>$this->data['Question']['question_id']),'order'=>array('Option.id'=>'ASC')));
				$options = '';
				foreach($questions as $question){
					$questions1 = $question['Question']['question'];
					$options .= $question['Option']['optionb'].". ".$question['Option']['optiona']."\n";
				}

				$totalsubscribers= count($Subscriber);
				$subscriberPhone = '';
                $sendthis="Reply A,B,C OR D.";			
				$optmsg=OPTMSG;
				$message1=$questions1."\n".$options."\n".$sendthis."\n".$optmsg;
				$length = strlen(utf8_decode(substr($message1,0,160)));                                                                 
				if (strlen($message1) != strlen(utf8_decode($message1))){
					 $contactcredits = ceil($length/70);
				}else{
					 $contactcredits = ceil($length/160);
				}
				if($credits < ($totalsubscribers * $contactcredits)){
					$this->Session->setFlash(__('You do not have enough credits to send a poll to this group.', true));
					$this->redirect(array('controller' =>'polls', 'action'=>'question_list'));
				} 

				app::import('Model','Question');
				$this->Question = new Question();
				$this->data['Question']['id']=$this->data['Question']['question_id'];
				$groupid = $this->data['Group']['id'];
				$this->data['Question']['group_id']=$groupid;
				$this->Question->save($this->data);
					 //print_r($Subscriber);
				$subscriberPhone1 = '';
				foreach($Subscriber as $Subscribers){
					$subscriberPhone1[$Subscribers['ContactGroup']['id']] = $Subscribers['Contact']['phone_number'];
				}			
				//pr($subscriberPhone1);
				$subscriberPhoneTotal = count($subscriberPhone1);
					/*	$this->data['User']['sms_balance']=$credits-$subscriberPhoneTotal;
						$this->data['User']['id']=$user_id;
						$this->User->save($this->data);*/
				if($subscriberPhoneTotal > 0){
					app::import('Model','GroupSmsBlast');
					$this->GroupSmsBlast = new GroupSmsBlast();
					$this->data['GroupSmsBlast']['user_id'] =$user_id;
					$this->data['GroupSmsBlast']['group_id'] =$groupid;
					$this->data['GroupSmsBlast']['totals'] =$subscriberPhoneTotal;
					$this->GroupSmsBlast->save($this->data);
					$groupblastid = $this->GroupSmsBlast->id;
					$this->Session->write('groupsmsid', $groupblastid);
					app::import('Model','Log');
					if(API_TYPE==0){
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
							$from_arr=array();
							if(!empty($users_arr)){					
								$from_arr[]=$users_arr['User']['assigned_number'];
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									$from_arr[]=$values['UserNumber']['number'];
								}
							}
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
									//$firstName = $Subscribers['Contact']['name'];
								$message = $message1;
								$to = $subscriberPhones;
								//$from = $users['User']['assigned_number'];
								$random_keys= array_rand($from_arr,1);
								$from=$from_arr[$random_keys];
								//$from = '2029993169';
								$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
								$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
								$response = $this->Twilio->sendsms($to,$from,$message);
									   //pr($response);
								$smsid=$response->ResponseXml->Message->Sid;
								$Status=$response->ResponseXml->RestException->Status;
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$smsid;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								//echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if($Status==400){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $response->ErrorMessage;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									//pr($groupContacts);
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
								//pr($this->data);
									$this->GroupSmsBlast->save($this->data);
								}
								$this->Log->save($this->data);
							}
						}else{
							$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
							if(!empty($usernumber)){
								if($usernumber['User']['sms']==1){
									$assigned_number=$usernumber['User']['assigned_number'];
								}else{
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
									if(!empty($user_numbers)){	
										$assigned_number=$user_numbers['UserNumber']['number'];
									}else{
										$assigned_number=$usernumber['User']['assigned_number'];
									}
								}
							}
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
								//$firstName = $Subscribers['Contact']['name'];
								$message = $message1;
								$to = $subscriberPhones;
								//$from = $users['User']['assigned_number'];
								$from = $assigned_number;
								//$from = '2029993169';
								$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
								$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
								$response = $this->Twilio->sendsms($to,$from,$message);
								   //pr($response);
								$smsid=$response->ResponseXml->Message->Sid;
								$Status=$response->ResponseXml->RestException->Status;
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$smsid;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								//echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if($Status==400){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $response->ErrorMessage;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									//pr($groupContacts);
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
									//pr($this->data);
									$this->GroupSmsBlast->save($this->data);
								}
								$this->Log->save($this->data);
							}
						}
					}else if(API_TYPE==2){
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
							$from_arr=array();
							if(!empty($users_arr)){					
								$from_arr[]=$users_arr['User']['assigned_number'];
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									$from_arr[]=$values['UserNumber']['number'];
								}
							}
							$k = 0;
							$sucesscredits = 0;
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
								//$firstName = $Subscribers['Contact']['name'];
								$message = $message1;
								$to = $subscriberPhones;
								//$from = $users['User']['assigned_number'];
								//$random_keys= array_rand($from_arr,1);
								$countnumber = count($from_arr);
								if($countnumber==$k){
									$k = 0;
								}
									
								$from=$from_arr[$k];
								$response = $this->Slooce->mt($users_arr['User']['api_url'],$users_arr['User']['partnerid'],$users_arr['User']['partnerpassword'],$to,$users_arr['User']['keyword'],$message);
								$message_id = '';
								$status = '';
								if(isset($response['id'])){
									if($response['result']=='ok'){
										$message_id = $response['id'];
									}
									$status = $response['result'];
								} 
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
									   //echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if($status!='ok'){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $status;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
									$this->GroupSmsBlast->save($this->data);
								}
							   if($message_id!=''){
								$sucesscredits = $sucesscredits + 1;
								$this->data['Log']['sms_status']= 'sent';
								}
								$this->Log->save($this->data);
								$k = $k + 1;	
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									$this->User->save($usercredit);
								}
								app::import('Model','GroupSmsBlast');
								$group_blast['GroupSmsBlast']['id'] =$groupblastid;
								$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
								$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
								$this->GroupSmsBlast->save($group_blast);
							}
							$usersms1 = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
							if($usersms1['User']['email_alert_credit_options']==0){
								if($usersms1['User']['sms_balance'] <= $usersms1['User']['low_sms_balances']){
									if($usersms1['User']['sms_credit_balance_email_alerts']==0){
										$username = $usersms1['User']['username'];
										$email = $usersms1['User']['email'];
										$phone = $usersms1['User']['assigned_number'];
										$date=date('Y-m-d H:i:s',time());
										$subject="Low SMS Credit Balance";	
										$sitename=str_replace(' ','',SITENAME);
										$this->Email->to = $email;	
										$this->Email->subject = $subject;
										$this->Email->from = $sitename;
										$this->Email->template = 'low_sms_credit_template';
										$this->Email->sendAs = 'html';
										$this->Email->Controller->set('username', $username);
										$this->Email->Controller->set('low_sms_balances', $usersms1['User']['low_sms_balances']);
										$this->Email->send();
										$this->User->id = $usersms1['User']['id'];
										$this->User->saveField('sms_credit_balance_email_alerts',1);
									}	
											
								}
							}
						}else{
							$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
							if(!empty($usernumber)){
								if($usernumber['User']['sms']==1){
									$assigned_number=$usernumber['User']['assigned_number'];
								}else{
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
									if(!empty($user_numbers)){	
										$assigned_number=$user_numbers['UserNumber']['number'];
									}else{
										$assigned_number=$usernumber['User']['assigned_number'];
									}
								}
							}
							$sucesscredits = 0;
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
								//$firstName = $Subscribers['Contact']['name'];
								$message = $message1;
								$to = $subscriberPhones;
								//$from = $users['User']['assigned_number'];
								$from = $assigned_number;
								$response = $this->Slooce->mt($usernumber['User']['api_url'],$usernumber['User']['partnerid'],$usernumber['User']['partnerpassword'],$to,$usernumber['User']['keyword'],$message);
								$message_id = '';
								$status = '';
								if(isset($response['id'])){
									if($response['result']=='ok'){
										$message_id = $response['id'];
									}
									$status = $response['result'];
								}  
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
									   //echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if($message_id!=''){
									$sucesscredits = $sucesscredits + 1;
									$this->data['Log']['sms_status']= 'sent';
								}	
								if($status!=0){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									//pr($groupContacts);
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
									//pr($this->data);
									$this->GroupSmsBlast->save($this->data);
								}
								$this->Log->save($this->data);
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									$this->User->save($usercredit);
								}
								app::import('Model','GroupSmsBlast');
								$group_blast['GroupSmsBlast']['id'] =$groupblastid;
								$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
								$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
								$this->GroupSmsBlast->save($group_blast);
							}
							$usersms1 = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
							if($usersms1['User']['email_alert_credit_options']==0){
								if($usersms1['User']['sms_balance'] <= $usersms1['User']['low_sms_balances']){
									if($usersms1['User']['sms_credit_balance_email_alerts']==0){
										$username = $usersms1['User']['username'];
										$email = $usersms1['User']['email'];
										$phone = $usersms1['User']['assigned_number'];
										$date=date('Y-m-d H:i:s',time());
										$subject="Low SMS Credit Balance";	
										$sitename=str_replace(' ','',SITENAME);
										$this->Email->to = $email;	
										$this->Email->subject = $subject;
										$this->Email->from = $sitename;
										$this->Email->template = 'low_sms_credit_template';
										$this->Email->sendAs = 'html';
										$this->Email->Controller->set('username', $username);
										$this->Email->Controller->set('low_sms_balances', $usersms1['User']['low_sms_balances']);
										$this->Email->send();
										$this->User->id = $usersms1['User']['id'];
										$this->User->saveField('sms_credit_balance_email_alerts',1);
									}		
								}
							}
						}
					}else if(API_TYPE==3){
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
							$from_arr=array();
							if(!empty($users_arr)){					
								$from_arr[]=$users_arr['User']['assigned_number'];
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									$from_arr[]=$values['UserNumber']['number'];
								}
							}
							$k = 0;
							$sucesscredits = 0;
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
								$message = $message1;
								$to = $subscriberPhones;
								$countnumber = count($from_arr);
								if($countnumber==$k){
									$k = 0;
									sleep(1);
								}
									
								$from=$from_arr[$k];
								//$from = '2029993169';
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								//sleep(1);
								$response = $this->Plivo->sendsms($to,$from,$message);
								$errortext = '';
								$message_id = '';
								if(isset($response['response']['error'])){
									$errortext = $response['response']['error'];
								}
								if(isset($response['response']['message_uuid'][0])){
									$message_id = $response['response']['message_uuid'][0];
								}
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
									   //echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if(isset($response['response']['error'])){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
									$this->GroupSmsBlast->save($this->data);
								}
							   if($message_id!=''){
								$sucesscredits = $sucesscredits + $contactcredits;
								$this->data['Log']['sms_status']= 'sent';
								}
								$this->Log->save($this->data);
								$k = $k + 1;	
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									$this->User->save($usercredit);
								}
								app::import('Model','GroupSmsBlast');
								$group_blast['GroupSmsBlast']['id'] =$groupblastid;
								$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
								$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
								$this->GroupSmsBlast->save($group_blast);
							}
							$usersms1 = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
							if($usersms1['User']['email_alert_credit_options']==0){
								if($usersms1['User']['sms_balance'] <= $usersms1['User']['low_sms_balances']){
									if($usersms1['User']['sms_credit_balance_email_alerts']==0){
										$username = $usersms1['User']['username'];
										$email = $usersms1['User']['email'];
										$phone = $usersms1['User']['assigned_number'];
										$date=date('Y-m-d H:i:s',time());
										$subject="Low SMS Credit Balance";	
										$sitename=str_replace(' ','',SITENAME);
										$this->Email->to = $email;	
										$this->Email->subject = $subject;
										$this->Email->from = $sitename;
										$this->Email->template = 'low_sms_credit_template';
										$this->Email->sendAs = 'html';
										$this->Email->Controller->set('username', $username);
										$this->Email->Controller->set('low_sms_balances', $usersms1['User']['low_sms_balances']);
										$this->Email->send();
										$this->User->id = $usersms1['User']['id'];
										$this->User->saveField('sms_credit_balance_email_alerts',1);
									}	
											
								}
							}
						}else{
							$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
							if(!empty($usernumber)){
								if($usernumber['User']['sms']==1){
									$assigned_number=$usernumber['User']['assigned_number'];
								}else{
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
									if(!empty($user_numbers)){	
										$assigned_number=$user_numbers['UserNumber']['number'];
									}else{
										$assigned_number=$usernumber['User']['assigned_number'];
									}
								}
							}
							$sucesscredits = 0;
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
								//$firstName = $Subscribers['Contact']['name'];
								$message = $message1;
								$to = $subscriberPhones;
								//$from = $users['User']['assigned_number'];
								$from = $assigned_number;
								//$from = '2029993169';
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								sleep(1);
								$response = $this->Plivo->sendsms($to,$from,$message);
								$errortext = '';
								$message_id = '';
								if(isset($response['response']['error'])){
									$errortext = $response['response']['error'];
								}
								if(isset($response['response']['message_uuid'][0])){
									$message_id = $response['response']['message_uuid'][0];
								}
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
									   //echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if($message_id!=''){
									$sucesscredits = $sucesscredits + $contactcredits;
									$this->data['Log']['sms_status']= 'sent';
								}	
								if(isset($response['response']['error'])){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									//pr($groupContacts);
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
									//pr($this->data);
									$this->GroupSmsBlast->save($this->data);
								}
								$this->Log->save($this->data);
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									$this->User->save($usercredit);
								}
								app::import('Model','GroupSmsBlast');
								$group_blast['GroupSmsBlast']['id'] =$groupblastid;
								$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
								$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
								$this->GroupSmsBlast->save($group_blast);
							}
							$usersms1 = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
							if($usersms1['User']['email_alert_credit_options']==0){
								if($usersms1['User']['sms_balance'] <= $usersms1['User']['low_sms_balances']){
									if($usersms1['User']['sms_credit_balance_email_alerts']==0){
										$username = $usersms1['User']['username'];
										$email = $usersms1['User']['email'];
										$phone = $usersms1['User']['assigned_number'];
										$date=date('Y-m-d H:i:s',time());
										$subject="Low SMS Credit Balance";	
										$sitename=str_replace(' ','',SITENAME);
										$this->Email->to = $email;	
										$this->Email->subject = $subject;
										$this->Email->from = $sitename;
										$this->Email->template = 'low_sms_credit_template';
										$this->Email->sendAs = 'html';
										$this->Email->Controller->set('username', $username);
										$this->Email->Controller->set('low_sms_balances', $usersms1['User']['low_sms_balances']);
										$this->Email->send();
										$this->User->id = $usersms1['User']['id'];
										$this->User->saveField('sms_credit_balance_email_alerts',1);
									}		
								}
							}
						}
					}else{
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
							$from_arr=array();
							if(!empty($users_arr)){					
								$from_arr[]=$users_arr['User']['assigned_number'];
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									$from_arr[]=$values['UserNumber']['number'];
								}
							}
							$k = 0;
							$sucesscredits = 0;
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
								//$firstName = $Subscribers['Contact']['name'];
								$message = $message1;
								$to = $subscriberPhones;
								//$from = $users['User']['assigned_number'];
								//$random_keys= array_rand($from_arr,1);
								$countnumber = count($from_arr);
								if($countnumber==$k){
									$k = 0;
									sleep(1);
								}
									
								$from=$from_arr[$k];
								//$from = '2029993169';
								$this->Nexmomessage->Key = NEXMO_KEY;
								$this->Nexmomessage->Secret = NEXMO_SECRET;
								//sleep(1);
								$response = $this->Nexmomessage->sendsms($to,$from,$message);
								   //pr($response);
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
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
									   //echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if($status!=0){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
									$this->GroupSmsBlast->save($this->data);
								}
							   if($message_id!=''){
								$sucesscredits = $sucesscredits + $contactcredits;
								$this->data['Log']['sms_status']= 'sent';
								}
								$this->Log->save($this->data);
								$k = $k + 1;	
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									$this->User->save($usercredit);
								}
								app::import('Model','GroupSmsBlast');
								$group_blast['GroupSmsBlast']['id'] =$groupblastid;
								$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
								$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
								$this->GroupSmsBlast->save($group_blast);
							}
							$usersms1 = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
							if($usersms1['User']['email_alert_credit_options']==0){
								if($usersms1['User']['sms_balance'] <= $usersms1['User']['low_sms_balances']){
									if($usersms1['User']['sms_credit_balance_email_alerts']==0){
										$username = $usersms1['User']['username'];
										$email = $usersms1['User']['email'];
										$phone = $usersms1['User']['assigned_number'];
										$date=date('Y-m-d H:i:s',time());
										$subject="Low SMS Credit Balance";	
										$sitename=str_replace(' ','',SITENAME);
										$this->Email->to = $email;	
										$this->Email->subject = $subject;
										$this->Email->from = $sitename;
										$this->Email->template = 'low_sms_credit_template';
										$this->Email->sendAs = 'html';
										$this->Email->Controller->set('username', $username);
										$this->Email->Controller->set('low_sms_balances', $usersms1['User']['low_sms_balances']);
										$this->Email->send();
										$this->User->id = $usersms1['User']['id'];
										$this->User->saveField('sms_credit_balance_email_alerts',1);
									}	
											
								}
							}
						}else{
							$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
							if(!empty($usernumber)){
								if($usernumber['User']['sms']==1){
									$assigned_number=$usernumber['User']['assigned_number'];
								}else{
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
									if(!empty($user_numbers)){	
										$assigned_number=$user_numbers['UserNumber']['number'];
									}else{
										$assigned_number=$usernumber['User']['assigned_number'];
									}
								}
							}
							$sucesscredits = 0;
							foreach($subscriberPhone1 as $contactgroupid=>$subscriberPhones){
								$this->Log = new Log();
								//$firstName = $Subscribers['Contact']['name'];
								$message = $message1;
								$to = $subscriberPhones;
								//$from = $users['User']['assigned_number'];
								$from = $assigned_number;
								//$from = '2029993169';
								$this->Nexmomessage->Key = NEXMO_KEY;
								$this->Nexmomessage->Secret = NEXMO_SECRET;
								sleep(1);
								$response = $this->Nexmomessage->sendsms($to,$from,$message);
								   //pr($response);
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
								$this->data['Log']['group_sms_id'] =$groupblastid;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =$groupid;				   
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
									   //echo $contactgroupid;
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['id']=$contactgroupid;
								$this->data['ContactGroup']['question_id']=$this->data['Question']['question_id'];
								$this->ContactGroup->save($this->data);
								$subscriberPhone[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
								if($message_id!=''){
									$sucesscredits = $sucesscredits + $contactcredits;
									$this->data['Log']['sms_status']= 'sent';
								}	
								if($status!=0){
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
									app::import('Model','GroupSmsBlast');
									$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
									//pr($groupContacts);
									$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
									//pr($this->data);
									$this->GroupSmsBlast->save($this->data);
								}
								$this->Log->save($this->data);
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									$this->User->save($usercredit);
								}
								app::import('Model','GroupSmsBlast');
								$group_blast['GroupSmsBlast']['id'] =$groupblastid;
								$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
								$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
								$this->GroupSmsBlast->save($group_blast);
							}
							$usersms1 = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
							if($usersms1['User']['email_alert_credit_options']==0){
								if($usersms1['User']['sms_balance'] <= $usersms1['User']['low_sms_balances']){
									if($usersms1['User']['sms_credit_balance_email_alerts']==0){
										$username = $usersms1['User']['username'];
										$email = $usersms1['User']['email'];
										$phone = $usersms1['User']['assigned_number'];
										$date=date('Y-m-d H:i:s',time());
										$subject="Low SMS Credit Balance";	
										$sitename=str_replace(' ','',SITENAME);
										$this->Email->to = $email;	
										$this->Email->subject = $subject;
										$this->Email->from = $sitename;
										$this->Email->template = 'low_sms_credit_template';
										$this->Email->sendAs = 'html';
										$this->Email->Controller->set('username', $username);
										$this->Email->Controller->set('low_sms_balances', $usersms1['User']['low_sms_balances']);
										$this->Email->send();
										$this->User->id = $usersms1['User']['id'];
										$this->User->saveField('sms_credit_balance_email_alerts',1);
									}		
								}
							}
						}
					}
					$this->Session->setFlash(__('The poll has been sent', true));
					$this->redirect(array('controller' =>'polls', 'action'=>'question_list'));
				}	
			}else{
				$this->Session->setFlash(__('You do not have any number with SMS capability', true));
			}
		}
	}	

	function polling_report($id=null) {
		$this->layout='admin_new_layout';
		app::import('Model','Question');
		$this->Question = new Question();
		$Subscriber1 = $this->Question->find('first',array('conditions' => array('Question.id'=>$id)));
				/*  echo "<pre>";
				 print_r($Subscriber);
				 echo "</pre>"; */
		$question11=$Subscriber1['Question']['question'];
		$this->set('questions',$question11);
		app::import('Model','AnswerSubscriber');
		$this->AnswerSubscriber = new AnswerSubscriber();
		$Subscriber = $this->AnswerSubscriber->find('all',array('conditions' => array('AnswerSubscriber.question_id'=>$id)));
		$Subscriber1 = $this->AnswerSubscriber->find('all',array('conditions' => array('AnswerSubscriber.question_id'=>$id)));
		$total = 	count($Subscriber);
		$this->Session->write('total', $total);
		//	pr($Subscriber1);
		$count = 1;
		foreach($Subscriber1 as $m_list){
			$day =$m_list['Option']['optionb'];
			// echo $month_list[$day];
			if(isset($month_list[$day])){
				// echo "test";
				$month_list[$day] =$count+1; 
				$count++;                 
            }else{
				$month_list[$day] = 1;
				//echo "test11";
            }
		}
		//pr($month_list);
		$mon_list = array();
		for($i=0;$i<4;$i++){						
			if($i == 0){
				$j = 'A';
			}
			if($i == 1){
				$j = 'B';
			}
			if($i == 2){
				$j = 'C';
			}
			if($i == 3){
				$j = 'D';
			}
			if(isset($month_list[$j]) && $month_list[$j]!='')
				$mon_list[$i]=$month_list[$j];
			else	
				$mon_list[$i]=0;
		}
		$caller_list=json_encode($mon_list);
		//pr($caller_list);
		$this->set('caller_list', $caller_list);
	}
	function edit($id=null) {
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		$this->set('id',$id);
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Poll', true));
			$this->redirect(array('action' => 'question_list'));
		}
		app::import('Model','Question');
		$this->Question = new Question();
		$questionedit = $this->Question->read(null, $id);
		$this->set('questionedits',$questionedit);
			//pr($this->data);
		if (!empty($this->data)) {
			$user_id=$this->Session->read('User.id');
			app::import('Model','Question');
			$this->Question = new Question();
			$questioncode = $this->Question->find('first',array('conditions'=>array('Question.id '=>$id,'Question.user_id '=>$user_id)));
			if(!empty($questioncode)){
				$this->data['Question']['user_id']=$user_id;
				$this->data['Question']['id']=$id;
				$this->Question->save($this->data);
				app::import('Model','Option');
				$this->Option = new Option();
				$Optionvalue = $this->Option->find('all',array('conditions'=>array('Option.question_id'=>$id),'order'=>array('Option.id'=>'ASC')));
				//pr($Optionvalue);
				$optionid=array();
				foreach($Optionvalue as $optionid){
					$option_id[] = $optionid['Option']['id'];
				}
				//pr($option_id);
				$optionb=array('0'=>'A','1'=>'B','2'=>'C','3'=>'D');
				$count = 0;
				$i = 0;
				// pr($this->data);
				foreach($this->data['Option']['autorsponder_message'] as $msg){
					$automsg[] = $msg;
					 
				}
					/*  pr($automsg);
					exit;  */
				foreach($this->data['Option']['optiona'] as $option){
					$this->data['Option']['autorsponder_message']=$automsg[$i];
					$this->data['Option']['id'] = $option_id[$i];
					$this->data['Option']['question_id']=$id;
					$this->data['Option']['optiona']=$option;
					//$optionb[$count];
					$this->data['Option']['optionb']=$optionb[$count];
					$this->Option->save($this->data); 
					$count++;
					$i++;
				}
					$this->Session->setFlash(__('The poll has been updated', true));
					$this->redirect(array('action' => 'question_list'));
        	}else{
				$this->Session->setFlash(__('The Poll could not be edited. Please, try again.', true));
				$this->redirect(array('action' => 'question_list'));
			
			}
		}
	}
	function check($id=null,$questionid=null){
		$this->autoRender = false;
		$user_id=$this->Session->read('User.id');
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		$Subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.group_id'=>$id)));
		if($Subscriber11['ContactGroup']['question_id']>0){
			echo "A poll was already sent to this group. Please select another group, otherwise the previous poll for this group will be deactivated.";
		}
	}
	
}
?>