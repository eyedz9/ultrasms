<?php
class ContestsController extends AppController {
	var $name ='Contests';
	var $components = array('Cookie','Email','Twilio','Qr','Nexmomessage','Slooce','Plivo');
	function index(){
		$this->layout= 'admin_new_layout';
		$this->Contest->recursive = 0;
		$user_id=$this->Session->read('User.id');	
		$this->paginate = array('conditions' => array('Contest.user_id' =>$user_id),'order' =>array('Contest.id' => 'asc'));
		$data = $this->paginate('Contest');
		$this->set('contests', $data);
	}
	function add(){
		$this->layout= 'admin_new_layout';	
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
		
		   app::import('Model','Group');
		   $this->Group = new Group();
			
		   app::import('Model','Contest');
		   $this->Contest = new Contest();
				
	           app::import('Model','Smsloyalty');
		   $this->Smsloyalty = new Smsloyalty();
			

			$keywords = $this->Group->find('first',array('conditions'=>array('Group.keyword '=>$this->data['Contest']['keyword'],'Group.user_id '=>$user_id)));
			$contestkeyword = $this->Contest->find('first',array('conditions'=>array('Contest.keyword '=>$this->data['Contest']['keyword'],'Contest.user_id'=>$user_id)));

				if(!empty($contestkeyword)){ 
     					$this->Session->setFlash(__('Keyword is already registered for a contest. Please choose another keyword.', true));
     					$this->redirect(array('controller' =>'contests', 'action'=>'add')); 
				}
                   
				$loyaltykeyword = $this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.codestatus '=>$this->data['Contest']['keyword'],'Smsloyalty.user_id'=>$user_id)));

				if(!empty($loyaltykeyword)){ 
     					$this->Session->setFlash(__('Keyword is already registered for another loyalty program. Please choose another keyword.', true));
     					$this->redirect(array('controller' =>'contests', 'action'=>'add')); 
				}
						
			
			
				if(empty($keywords)){ 
					$this->data['Contest']['active'] = 1;	
					$this->data['Contest']['user_id']=$user_id;
					$this->Contest->save($this->data);
					$this->Session->setFlash(__('The SMS contest has been saved', true)); 
					$this->redirect(array('action' => 'index'));
				 }else {					$this->Session->setFlash(__('Keyword is already registered. Please choose another keyword.', true)); 	
				}	
					
	}
	}
	function edit($id = null) {
		$this->layout= 'admin_new_layout';
      //$this->layout="default";
		//$this->checkUserSession();
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)){
		$user_id=$this->Session->read('User.id');
			app::import('Model','Group');
			$this->Group = new Group();
			//$findkeyword=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$this->data['Contest']['keyword'])));
		
		
			app::import('Model','Contest');
		   $this->Contest = new Contest();
				
	           app::import('Model','Smsloyalty');
		   $this->Smsloyalty = new Smsloyalty();
			
		
			$findkeyword = $this->Group->find('first',array('conditions'=>array('Group.keyword '=>$this->data['Contest']['keyword'],'Group.user_id '=>$user_id)));
			$contestkeyword = $this->Contest->find('first',array('conditions'=>array('Contest.keyword '=>$this->data['Contest']['keyword'],'Contest.user_id' =>$user_id,'Contest.group_name !=' =>$this->data['Contest']['group_name'])));

				if(!empty($contestkeyword)){ 
     					$this->Session->setFlash(__('Keyword is already registered for a contest. Please choose another keyword.', true));
     					$this->redirect(array('controller' =>'contests', 'action'=>'edit/'.$id)); 
				}
                   
				$loyaltykeyword = $this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.codestatus '=>$this->data['Contest']['keyword'],'Smsloyalty.user_id'=>$user_id)));

				if(!empty($loyaltykeyword)){ 
     					$this->Session->setFlash(__('Keyword is already registered for another loyalty program. Please choose another keyword.', true));
     					$this->redirect(array('controller' =>'contests', 'action'=>'edit/'.$id));  
				}
			
			
			
			if(empty($findkeyword)){
					//$user_id=$this->Session->read('User.id');
					$this->Session->write('User.active',$this->Session->read('User.active'));
					$this->Session->write('sms_balance',$this->Session->read('User.sms_balance'));
					$this->Session->write('User.assigned_number',$this->Session->read('User.assigned_number'));
					//$contests= $this->Contest->find('first', array('conditions'=>array('Contest.keyword'=>$this->data['Contest']['keyword'],'Contest.user_id !='=>$user_id)));
				if(empty($contestkeyword )){ 
					$this->set('contest', $contests);
					//$contest['Contest']['keyword'];			
					//if($contestkeyword['Contest']['keyword'] == $this->data['Contest']['keyword']){
					//$this->data['Contest']['keyword'] = $this->data['Contest']['keyword'];
					$this->data['Contest']['id'] = $id;
//				}   					
				if ($this->Contest->save($this->data)) {
						$this->Session->setFlash(__('The SMS contest has been updated', true));
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The SMS contest could not be updated. Please, try again.', true));
					}
				}
			}else{
				$this->Session->setFlash(__('Keyword is already registered. Please choose another keyword.', true)); 
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Contest->read(null, $id);
		}
	}
	function delete($id = null) { 
		if ($this->Contest->delete($id)) {
			$this->Session->setFlash(__('SMS contest has been deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('SMS contest has been deleted', true));		$this->redirect(array('action' => 'index'));
	}
	function sendcontest($id = null) { 
		$this->set('id',$id);
		$this->layout = 'popup';
		$user_id=$this->Session->read('User.id');
		$this->Session->write('User.active',$this->Session->read('User.active'));
		$this->Session->write('sms_balance',$this->Session->read('User.sms_balance'));
		$this->Session->write('User.assigned_number',$this->Session->read('User.assigned_number'));
		app::import('Model','Group');
		$this->Group = new Group();
		$group =$this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
		$this->set('Group',$group);
		app::import('Model','Contest');
		$this->Contest = new Contest();
		$contestkeyworddata = $this->Contest->find('first',array('conditions'=>array('Contest.id '=>$id,'Contest.user_id'=>$user_id)));		$this->set('contestkeyworddata',$contestkeyworddata);
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$this->set('users',$users);
	}
	
	function send_message(){
		if(!empty($this->data)){
			$rotate_number = $this->data['User']['rotate_number'];
			$user_id=$this->Session->read('User.id');
			app::import('Model','User');
			$this->User = new User();
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
			$users_arr = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.sms'=>1)));
			if((!empty($user_numbers)) || (!empty($users_arr))){
				$this->Session->write('User.active',$this->Session->read('User.active'));
				$this->Session->write('sms_balance',$this->Session->read('User.sms_balance'));
				$this->Session->write('User.assigned_number',$this->Session->read('User.assigned_number'));
				app::import('Model','Contest');
				$this->Contest = new Contest();
				$contestkeywords = $this->Contest->find('first',array('conditions'=>array('Contest.id '=>$this->data['Contest']['id'],'Contest.user_id'=>$user_id)));
				app::import('Model','User');
				$this->User = new User();
				$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
				$credits = $users['User']['sms_balance'];
				app::import('Model','ContactGroup');
				$this->ContactGroup = new ContactGroup();
				$Subscriber = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$this->data['Group']['id'],'ContactGroup.un_subscribers'=>0)));
				if(empty($Subscriber)){
						$this->Session->setFlash(__('Add contacts to this group or select a different group.', true));
						$this->redirect(array('controller' =>'contests', 'action'=>'index'));
				}
				$totalsubscribers= count($Subscriber);
				$message1 = $this->data['Contest']['message'];
				$sendthis=" Reply ".$contestkeywords['Contest']['keyword'];	
				$optmsg=OPTMSG;
				$message2=$message1."\n".$sendthis."\n".$optmsg;

				$length = strlen(utf8_decode(substr($message2,0,160)));                                                                 
				if (strlen($message2) != strlen(utf8_decode($message2))){
				   $contactcredits = ceil($length/70);
				}else{
				   $contactcredits = ceil($length/160);
				}
				if($credits < ($totalsubscribers * $contactcredits)){
					$this->Session->setFlash(__('You do not have enough credits to send a contest to this group.', true));
					$this->redirect(array('controller' =>'contests', 'action'=>'index'));				} 
				$subscriberPhone1 = '';
				foreach($Subscriber as $Subscribers){
					$subscriberPhone1[$Subscribers['Contact']['phone_number']] = $Subscribers['Contact']['phone_number'];
				}
				foreach($this->data['Group']['id'] as $groupIds){
					$group_id = $groupIds;
					app::import('Model','ContactGroup');		
					$this->ContactGroup = new ContactGroup();	
					$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
					$subscriberPhone2 = '';
					$subscriberPhone3 = '';
						foreach($groupContacts as $Subscribers1){
							$subscriberPhone2[$Subscribers1['ContactGroup']['id']] = $Subscribers1['Contact']['phone_number'];
							$space_pos = strpos($Subscribers1['Contact']['name'],' ');
							if($space_pos!=''){	
								$subscriberPhone3[]=substr($Subscribers1['Contact']['name'],0,$space_pos);
							}else{		
								$subscriberPhone3[] = $Subscribers1['Contact']['name'];
							}
						}
					$subscriberPhoneTotal = count($subscriberPhone2);	
					if($subscriberPhoneTotal > 0){
							app::import('Model','GroupSmsBlast');
							$this->GroupSmsBlast = new GroupSmsBlast();
							$this->data['GroupSmsBlast']['user_id'] =$user_id;
							$this->data['GroupSmsBlast']['group_id'] =$group_id;
							$this->data['GroupSmsBlast']['totals'] =$subscriberPhoneTotal;
							$this->GroupSmsBlast->save($this->data);
							$groupblastid = $this->GroupSmsBlast->id;
							$this->Session->write('groupsmsid', $groupblastid);
							app::import('Model','Log');	
						if(API_TYPE==0)	{	
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
								$i=0;
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){										
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;
										$to = $subscriberPhones;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);
										$random_keys= array_rand($from_arr,1);
										$from=$from_arr[$random_keys];
										$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
										$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
										$response = $this->Twilio->sendsms($to,$from,$message);
										$smsid=$response->ResponseXml->Message->Sid;
										$Status=$response->ResponseXml->RestException->Status;
										$this->data['Log']['group_sms_id'] =$groupblastid;
										$this->data['Log']['sms_id'] =$smsid;
										$this->data['Log']['user_id'] =$user_id;
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($Status==400){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $response->ErrorMessage;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
										$this->GroupSmsBlast->save($this->data);
									}
									$this->Log->save($this->data); 
									$i++;
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
										}									}
								}
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){		
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;
										$to = $subscriberPhones;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);
										$from = $assigned_number;
										$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
										$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
										$response = $this->Twilio->sendsms($to,$from,$message);
										$smsid=$response->ResponseXml->Message->Sid;
										$Status=$response->ResponseXml->RestException->Status;
										$this->data['Log']['group_sms_id'] =$groupblastid;
										$this->data['Log']['sms_id'] =$smsid;
										$this->data['Log']['user_id'] =$user_id;
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($Status==400){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $response->ErrorMessage;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
										$this->GroupSmsBlast->save($this->data);
									}
									$this->Log->save($this->data); 
									$i++;
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
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;	
										$to = $subscriberPhones;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);
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
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($message_id!=''){
										$sucesscredits = $sucesscredits + 1;
										$this->data['Log']['sms_status']= 'sent';
									}else if($status!='ok'){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $status;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
										$this->GroupSmsBlast->save($this->data);
									}
									$this->Log->save($this->data);
									$k = $k +1;
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
								app::import('Model','User');
								$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
								if($usersms['User']['email_alert_credit_options']==0){
									if($usersms['User']['sms_balance'] <= $usersms['User']['low_sms_balances']){
										if($usersms['User']['sms_credit_balance_email_alerts']==0){
											$username = $usersms['User']['username'];
											$email = $usersms['User']['email'];
											$phone = $usersms['User']['assigned_number'];
											$subject="Low SMS Credit Balance";	
											$sitename=str_replace(' ','',SITENAME);
											$this->Email->to = $email;	
											$this->Email->subject = $subject;
											$this->Email->from = $sitename;
											$this->Email->template = 'low_sms_credit_template';
											$this->Email->sendAs = 'html';
											$this->Email->Controller->set('username', $username);
											$this->Email->Controller->set('low_sms_balances', $usersms['User']['low_sms_balances']);
											$this->Email->send();
											$this->User->id = $usersms['User']['id'];
											$this->User->saveField('sms_credit_balance_email_alerts',1);												}
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
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;
										$to = $subscriberPhones;
										$from = $assigned_number;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);
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
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($message_id!=''){
										$sucesscredits = $sucesscredits + 1;
										$this->data['Log']['sms_status']= 'sent';
									}else if($status!='ok'){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $status;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');	
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
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
								app::import('Model','User');
								$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
								if($usersms['User']['email_alert_credit_options']==0){
									if($usersms['User']['sms_balance'] <= $usersms['User']['low_sms_balances']){
										if($usersms['User']['sms_credit_balance_email_alerts']==0){
											$username = $usersms['User']['username'];
											$email = $usersms['User']['email'];
											$phone = $usersms['User']['assigned_number'];
											$subject="Low SMS Credit Balance";	
											$sitename=str_replace(' ','',SITENAME);
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
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;	
										$to = $subscriberPhones;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);
										$countnumber = count($from_arr);
										if($countnumber==$k){
											$k = 0;
											sleep(1);
										}
										$from=$from_arr[$k];
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
										$this->data['Log']['group_sms_id'] =$groupblastid;
										$this->data['Log']['sms_id'] =$message_id;
										$this->data['Log']['user_id'] =$user_id;
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($message_id!=''){
										$sucesscredits = $sucesscredits + $contactcredits;
										$this->data['Log']['sms_status']= 'sent';
									}else if(isset($response['response']['error'])){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $errortext;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
										$this->GroupSmsBlast->save($this->data);
									}
									$this->Log->save($this->data);
									$k = $k +1;
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
								app::import('Model','User');
								$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
								if($usersms['User']['email_alert_credit_options']==0){
									if($usersms['User']['sms_balance'] <= $usersms['User']['low_sms_balances']){
										if($usersms['User']['sms_credit_balance_email_alerts']==0){
											$username = $usersms['User']['username'];
											$email = $usersms['User']['email'];
											$phone = $usersms['User']['assigned_number'];
											$subject="Low SMS Credit Balance";	
											$sitename=str_replace(' ','',SITENAME);
											$this->Email->to = $email;	
											$this->Email->subject = $subject;
											$this->Email->from = $sitename;
											$this->Email->template = 'low_sms_credit_template';
											$this->Email->sendAs = 'html';
											$this->Email->Controller->set('username', $username);
											$this->Email->Controller->set('low_sms_balances', $usersms['User']['low_sms_balances']);
											$this->Email->send();
											$this->User->id = $usersms['User']['id'];
											$this->User->saveField('sms_credit_balance_email_alerts',1);												}
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
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;
										$to = $subscriberPhones;
										$from = $assigned_number;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);					
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
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($message_id!=''){
										$sucesscredits = $sucesscredits + $contactcredits;
										$this->data['Log']['sms_status']= 'sent';
									}else if(isset($response['response']['error'])){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $errortext;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');	
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
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
								app::import('Model','User');
								$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
								if($usersms['User']['email_alert_credit_options']==0){
									if($usersms['User']['sms_balance'] <= $usersms['User']['low_sms_balances']){
										if($usersms['User']['sms_credit_balance_email_alerts']==0){
											$username = $usersms['User']['username'];
											$email = $usersms['User']['email'];
											$phone = $usersms['User']['assigned_number'];
											$subject="Low SMS Credit Balance";	
											$sitename=str_replace(' ','',SITENAME);
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
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;	
										$to = $subscriberPhones;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);
										$countnumber = count($from_arr);
										if($countnumber==$k){
											$k = 0;
											sleep(1);
										}
										$from=$from_arr[$k];
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
										$this->data['Log']['group_sms_id'] =$groupblastid;
										$this->data['Log']['sms_id'] =$message_id;
										$this->data['Log']['user_id'] =$user_id;
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($message_id!=''){
										$sucesscredits = $sucesscredits + $contactcredits;
										/*$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));	
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id; 
											$usercredit['User']['sms_balance'] =$usersbalance['User']['sms_balance']-1; 
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$groupblastid;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
										$this->GroupSmsBlast->save($group_blast);*/
										$this->data['Log']['sms_status']= 'sent';
									}else if($status!=0){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $errortext;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
										$this->GroupSmsBlast->save($this->data);
									}
									$this->Log->save($this->data);
									$k = $k +1;
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
								app::import('Model','User');
								$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
								if($usersms['User']['email_alert_credit_options']==0){
									if($usersms['User']['sms_balance'] <= $usersms['User']['low_sms_balances']){
										if($usersms['User']['sms_credit_balance_email_alerts']==0){
											$username = $usersms['User']['username'];
											$email = $usersms['User']['email'];
											$phone = $usersms['User']['assigned_number'];
											$subject="Low SMS Credit Balance";	
											$sitename=str_replace(' ','',SITENAME);
											$this->Email->to = $email;	
											$this->Email->subject = $subject;
											$this->Email->from = $sitename;
											$this->Email->template = 'low_sms_credit_template';
											$this->Email->sendAs = 'html';
											$this->Email->Controller->set('username', $username);
											$this->Email->Controller->set('low_sms_balances', $usersms['User']['low_sms_balances']);
											$this->Email->send();
											$this->User->id = $usersms['User']['id'];
											$this->User->saveField('sms_credit_balance_email_alerts',1);												}
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
								foreach($subscriberPhone2 as $contactgroupid=>$subscriberPhones){
									if(!isset($phone[$subscriberPhones])){
										$this->Log = new Log();
										$phone[$subscriberPhones] = $subscriberPhones;
										$to = $subscriberPhones;
										$from = $assigned_number;
										$contact_name=$subscriberPhone3[$i];
										$message=str_replace('%%Name%%',$contact_name,$message2);					
										$this->Nexmomessage->Key = NEXMO_KEY;
										$this->Nexmomessage->Secret = NEXMO_SECRET;
										sleep(1);
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
										$this->data['Log']['group_sms_id'] =$groupblastid;
										$this->data['Log']['sms_id'] =$message_id;
										$this->data['Log']['user_id'] =$user_id;
										$this->data['Log']['group_id'] =$group_id;				   
										$this->data['Log']['phone_number']=$to;
										$this->data['Log']['text_message']= $message;
										$this->data['Log']['route']= 'outbox';
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['id']=$contactgroupid;
										$this->data['ContactGroup']['contest_id']=$this->data['Contest']['id'];
										$this->ContactGroup->save($this->data);
									}
									$this->data['Log']['sms_status']= '';
									$this->data['Log']['error_message']='';
									if($message_id!=''){
									$sucesscredits = $sucesscredits + $contactcredits;
											/*$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));	
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id; 
											$usercredit['User']['sms_balance'] =$usersbalance['User']['sms_balance']-1; 
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$groupblastid;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+1;
										$this->GroupSmsBlast->save($group_blast);*/
										$this->data['Log']['sms_status']= 'sent';
									}else if($status!=0){
										$this->data['Log']['sms_status']= 'failed';
										$ErrorMessage = $errortext;
										$this->data['Log']['error_message']=$ErrorMessage;
										app::import('Model','GroupSmsBlast');	
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$groupblastid)));
										$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
										$this->GroupSmsBlast->save($this->data);
									}
									$this->Log->save($this->data);
									/*if($message_id!=''){
										app::import('Model','User');
										$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
										if($usersms['User']['email_alert_credit_options']==0){
											if($usersms['User']['sms_balance'] <= $usersms['User']['low_sms_balances']){
												if($usersms['User']['sms_credit_balance_email_alerts']==0){
													$username = $usersms['User']['username'];
													$email = $usersms['User']['email'];
													$phone = $usersms['User']['assigned_number'];
													$subject="Low SMS Credit Balance";	
													$sitename=str_replace(' ','',SITENAME);
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
									}*/
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
								app::import('Model','User');
								$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
								if($usersms['User']['email_alert_credit_options']==0){
									if($usersms['User']['sms_balance'] <= $usersms['User']['low_sms_balances']){
										if($usersms['User']['sms_credit_balance_email_alerts']==0){
											$username = $usersms['User']['username'];
											$email = $usersms['User']['email'];
											$phone = $usersms['User']['assigned_number'];
											$subject="Low SMS Credit Balance";	
											$sitename=str_replace(' ','',SITENAME);
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
							}
						}
						$this->Session->setFlash(__('SMS contest has been sent', true));
						$this->redirect(array('controller' =>'contests', 'action'=>'index'));
					}
				}
			}else{
				$this->Session->setFlash(__('You do not have any number with SMS capability', true));
				$this->redirect(array('controller' =>'contests', 'action'=>'index'));
			}
		}
	}
	function contest_winner($id=null,$contest_id=null){
		$this->layout = 'popup';
		$user_id=$this->Session->read('User.id');
		$this->Session->write('User.active',$this->Session->read('User.active'));
		$this->Session->write('sms_balance',$this->Session->read('User.sms_balance'));
		$this->Session->write('User.assigned_number',$this->Session->read('User.assigned_number'));
		$this->set('id',$id);
		app::import('Model','ContestSubscriber');
		$this->ContestSubscriber = new ContestSubscriber();
		$phone_number = $this->ContestSubscriber->find('first',array('conditions' => array('ContestSubscriber.contest_id'=>$id,		),'order' => 'rand()',));
		$this->set('phoneno',$phone_number);
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$this->set('users',$users);
		if(!empty($this->data)){
			app::import('Model','User');
			$this->User = new User();
			$assign_number = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
			$to=$this->data['ContestSubscriber']['phoneno'];
			$from=$assign_number['User']['assigned_number'];
			$message=$this->data['ContestSubscriber']['message'];
			if(API_TYPE==0){
				$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
				$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
				$response = $this->Twilio->sendsms($to,$from,$message);
				$smsid=$response->ResponseXml->Message->Sid;
				if($smsid!=''){
					$this->Session->setFlash(__('Message sent', true));	  
				}else{
					$this->Session->setFlash(__('Message not sent', true));
				}
			}else{
				$this->Nexmomessage->Key = NEXMO_KEY;
				$this->Nexmomessage->Secret = NEXMO_SECRET;
				sleep(1);				
				$response = $this->Nexmomessage->sendsms($to,$from,$message);				foreach($response->messages  as $doc){
					$message_id= $doc->messageid;
					if($message_id!=''){
						$status= $doc->status;
						$message_id= $doc->messageid;
					}else{
					 $status= $doc->status;
					 $errortext= $doc->errortext;
					} 				}
				if($message_id!=''){
					  $smsbalance['sms_balance']=$assign_number['User']['sms_balance']-1;
					  $smsbalance['id']=$assign_number['User']['id'];
					  $this->User->save($smsbalance);
					  $this->Session->setFlash(__('Message sent', true));
					if($assign_number['User']['email_alert_credit_options']==0){
						if($assign_number['User']['sms_balance'] <= $assign_number['User']['low_sms_balances']){
							if($assign_number['User']['sms_credit_balance_email_alerts']==0){
								$username = $assign_number['User']['username'];
								$email = $assign_number['User']['email'];
								$phone = $assign_number['User']['assigned_number'];
								$subject="Low SMS Credit Balance";	
								$sitename=str_replace(' ','',SITENAME);
								$this->Email->to = $email;	
								$this->Email->subject = $subject;
								$this->Email->from = $sitename;
								$this->Email->template = 'low_sms_credit_template';
								$this->Email->sendAs = 'html';
								$this->Email->Controller->set('username', $username);
								$this->Email->Controller->set('low_sms_balances', $assign_number['User']['low_sms_balances']);
								$this->Email->send();
								$this->User->id = $assign_number['User']['id'];
								$this->User->saveField('sms_credit_balance_email_alerts',1);
							}
						}
					}
					$this->Session->setFlash(__('Message sent', true));	
				}else{
					$this->Session->setFlash(__('Message not sent', true));
				}
			}
			app::import('Model','ContestSubscriber');
			$this->ContestSubscriber=new ContestSubscriber();
			$condition =array('ContestSubscriber.contest_id'=>$contest_id);
			$this->ContestSubscriber->deleteAll($condition,false);
			app::import('Model','Contest');
			$this->Contest=new Contest();
			$this->data['Contest']['id']=$contest_id;
			$this->data['Contest']['totalsubscriber']=0;
			$this->data['Contest']['winning_phone_number']=$this->data['ContestSubscriber']['phoneno'];
			$this->Contest->save($this->data);
			$this->redirect(array('controller' =>'contests', 'action'=>'index'));
		}
	}
}
?>