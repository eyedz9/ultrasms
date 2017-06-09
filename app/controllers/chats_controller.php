<?php
class ChatsController extends AppController {
	public $name = 'Chats';
	public $components = array('Email','Twilio','Nexmomessage','Slooce','Plivo');
	public $uses = array('User','UserNumber','Contact','Log','Group','GroupSmsBlast','ContactGroup','Smstemplate');
	public $useModel = false;
    
        function index(){
		if($this->Session->check('User')){
			$this->layout= 'admin_new_layout';
			$user_id = $this->Session->read('User.id');
			$this->Contact->bindModel(
			array('hasMany' => array('Log' => array(
				'className' => 'Log',			 
				'foreignKey' => 'contact_id',				
				'conditions' => array('Log.inbox_type'=>1,'Log.is_deleted'=>0),
				'limit' => 10,
				'fields' => array(),
				'order' => array('Log.created DESC'),
			))));
			$this->Group->bindModel(
			array('hasMany' => array('GroupSmsBlast' => array(
				'className' => 'GroupSmsBlast',			 
				'foreignKey' => 'group_id',				
				'conditions' => array('GroupSmsBlast.isdeleted'=>0,'GroupSmsBlast.responder'=>0),
				'fields' => array(),
				'limit' => 10,
				'order' => array('GroupSmsBlast.created DESC'),
			))));
			// unbind user
			$this->Contact->unbindModel(
				array('belongsTo' => array('User'))
			);		
			$response = $this->Contact->find('all',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'order'=>array('Contact.lastmsg DESC'),'limit'=>50));
			//$groups = $this->Group->find('all',array('conditions'=>array('Group.user_id'=>$user_id,'Group.active'=>1),'limit'=>10));
			//$response =  array_merge($contactArr, $groups);
			$firstcontact = $this->Contact->find('first',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'order'=>array('Contact.lastmsg DESC')));
			$numbers = array();
			$someone = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id,'User.sms'=>1)));
			
                        if(!empty($someone)){
                           $numbers[] =array('nickname'=>$someone['User']['first_name'],'number'=>$someone['User']['assigned_number'],'number_details'=>$someone['User']['assigned_number']);
                        }

			//if(!empty($someone)){
				$UserNumbers = $this->UserNumber->find('all',array('conditions'=>array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
				if(!empty($UserNumbers)){
					foreach($UserNumbers as $UserNumber){
						$numbers[] = array(
							'nickname'=>$UserNumber['UserNumber']['nickname'],
							'number'=>$UserNumber['UserNumber']['number'],
							'number_details'=>$UserNumber['UserNumber']['number'],
						);
					}
				}
			//}
			$this->set('firstcontact',$firstcontact);
			$this->set('contacts',$response);
			$this->set('numbers',$numbers);
		}else{
			$this->redirect('/users/login');
		}
	}
	function refresh($type=null){
		$this->layout= '';
		$user_id = $this->Session->read('User.id');
		// read limit from session
		$LoadContact = $this->Session->read('LoadContact');
		if(isset($type) && ($type == 1)){
			if(isset($LoadContact) && !empty($LoadContact)){
				$LoadContact = $LoadContact + 10;
			}else{				
				$LoadContact = 50;
			}
			$this->Contact->bindModel(
				array('hasMany' => array('Log' => array(
				'className' => 'Log',			 
				'foreignKey' => 'contact_id',				
				'conditions' => array('Log.inbox_type'=>1,'Log.is_deleted'=>0),
				'fields' => array(),
				'limit' => $LoadContact,
				'order' => array('Log.created DESC'),
			))));
			$this->Group->bindModel(
				array('hasMany' => array('GroupSmsBlast' => array(
				'className' => 'GroupSmsBlast',			 
				'foreignKey' => 'group_id',				
				'conditions' => array('GroupSmsBlast.isdeleted'=>0,'GroupSmsBlast.responder'=>0),
				'fields' => array(),
				'limit' => $LoadContact,
				'order' => array('GroupSmsBlast.created DESC'),
			))));
			// unbind user
			$this->Contact->unbindModel(
				array('belongsTo' => array('User'))
			);	
			$contactArr = $this->Contact->find('all',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'limit' => $LoadContact,'order'=>array('Contact.lastmsg DESC')));
			//$groups = $this->Group->find('all',array('conditions'=>array('Group.user_id'=>$user_id,'Group.active'=>1),'limit' => $LoadContact));
			$this->Session->write('LoadContact',$LoadContact);			
		}else{	
			$this->Session->delete('LoadContact');
			$this->Contact->bindModel(
				array('hasMany' => array('Log' => array(
				'className' => 'Log',			 
				'foreignKey' => 'contact_id',				
				'conditions' => array('Log.inbox_type'=>1,'Log.is_deleted'=>0),
				'fields' => array(),
				'limit' => 10,
				'order' => array('Log.created DESC'),
			))));
			$this->Group->bindModel(
				array('hasMany' => array('GroupSmsBlast' => array(
				'className' => 'GroupSmsBlast',			 
				'foreignKey' => 'group_id',				
				'conditions' => array('GroupSmsBlast.isdeleted'=>0,'GroupSmsBlast.responder'=>0),
				'fields' => array(),
				'limit' => 10,
				'order' => array('GroupSmsBlast.created DESC'),
			))));
			// unbind user
			$this->Contact->unbindModel(
				array('belongsTo' => array('User'))
			);	
			$contactArr = $this->Contact->find('all',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'limit' => 50,'order'=>array('Contact.lastmsg DESC')));
			//$groups = $this->Group->find('all',array('conditions'=>array('Group.user_id'=>$user_id,'Group.active'=>1),'limit' => 10));
		}
		//$response = array_merge($contactArr, $groups);
		$response = $contactArr;
		$this->set('contacts',$response);
	}
	function favorites($order=null){
		$this->layout= '';
		$user_id = $this->Session->read('User.id');
		$LoadContactfav = $this->Session->read('LoadContactfav');
		if(isset($LoadContactfav) && !empty($LoadContactfav)){
			$LoadContactfav = $LoadContactfav + 10;
		}else{				
			$LoadContactfav = 50;
		}
		$this->Contact->bindModel(
			array('hasMany' => array('Log' => array(
			'className' => 'Log',			 
			'foreignKey' => 'contact_id',				
			'conditions' => array('Log.inbox_type'=>1,'Log.is_deleted'=>0),
			'fields' => array(),
			'limit' => $LoadContactfav,
			'order' => array('Log.created DESC'),
		))));
		$this->Group->bindModel(
			array('hasMany' => array('GroupSmsBlast' => array(
			'className' => 'GroupSmsBlast',			 
			'foreignKey' => 'group_id',				
			'conditions' => array('GroupSmsBlast.isdeleted'=>0,'GroupSmsBlast.responder'=>0),
			'fields' => array(),
			'limit' => $LoadContactfav,
			'order' => array('GroupSmsBlast.created DESC'),
		))));
		// unbind user
		$this->Contact->unbindModel(
			array('belongsTo' => array('User'))
		);		
		if($order==0){
			$contactArr = $this->Contact->find('all',array('conditions'=>array('Contact.favorite'=>1,'Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'limit' => $LoadContactfav,'order'=>array('Contact.lastmsg DESC')));
			//$groups = $this->Group->find('all',array('conditions'=>array('Group.favorite'=>1,'Group.user_id'=>$user_id,'Group.active'=>1),'limit' => $LoadContactfav));
		}else if($order==1){
			$contactArr = $this->Contact->find('all',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'limit' => $LoadContactfav,'order'=>array('Contact.lastmsg DESC')));
			//$groups = $this->Group->find('all',array('conditions'=>array('Group.user_id'=>$user_id,'Group.active'=>1),'limit' => $LoadContactfav));
		}
		//$response = array_merge($contactArr, $groups);
		$response = $contactArr;
		$this->set('contacts',$response);
		$this->set('order',$order);
		$this->Session->write('LoadContactfav',$LoadContactfav);
	}
	function favorite($contact_id=null,$favorite=null){
		$this->autoRender = false;
 
		if($contact_id > 0){
			$contact_arr['Contact']['id'] = $contact_id;
			if($favorite==1){
				$contact_arr['Contact']['favorite'] = 0;
			}else{
				$contact_arr['Contact']['favorite'] = 1;
			}
			 $this->Contact->save($contact_arr);
		}
		 echo $contact_id;
	}
	function searchcontact(){
		$this->layout= null;
		$user_id = $this->Session->read('User.id');
		if($_POST['search'] !=''){
			$contactArr = $this->Contact->find('all', array(
				'conditions' => array(
					'OR' => array(
						array(
							'Contact.user_id'=>$user_id,
							'OR' => array(
								'Contact.name LIKE' => '%'.$_POST['search'].'%',
								'Contact.phone_number LIKE' => '%'.$_POST['search'].'%',
							)
						),
					)
				)
			));
			//$groups = $this->Group->find('all', array('conditions' => array('Group.user_id'=>$user_id,'Group.active'=>1,'Group.group_name LIKE' => '%'.$_POST['search'].'%')));
		}else{
			$contactArr = $this->Contact->find('all',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'order'=>array('Contact.lastmsg DESC')));
			//$groups = $this->Group->find('all',array('conditions'=>array('Group.user_id'=>$user_id,'Group.active'=>1)));
		}
		//$response =  array_merge($contactArr, $groups);
		$response =  $contactArr;
		$this->set('contacts',$response);
		$this->set('search',$_POST['search']);
	}
	function chat($type = null,$contactid=null){
		$user_id = $this->Session->read('User.id');
		if($user_id > 0){
			$this->layout= '';
			$contactArr = $this->Contact->find('first',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.id'=>$contactid)));				
			$this->set('contactArr',$contactArr);
			$this->set('type',$type);	
			//$contactgroups =$this->ContactGroup->find('all',array('conditions' => array('ContactGroup.user_id'=>$user_id,'ContactGroup.contact_id'=>$contactid)));
			//$this->set('contactgroups',$contactgroups);
			$this->Log->updateAll(array('Log.read' => 1),array('Log.contact_id' =>$contactid));
			$logs = $this->Log->find('all',array('conditions'=>array('Log.user_id'=>$user_id,'Log.contact_id'=>$contactid,'Log.inbox_type'=>1,'Log.is_deleted'=>0),'order' => array('Log.created DESC'),'limit' => 10));
			$this->set('logs',array_reverse($logs));
		}else{
			$this->autoRender = false;
			echo 0;
		}
	}
	function chatmsg($type = null,$contactid=null){
		$user_id = $this->Session->read('User.id');
		if($user_id > 0){
			$this->layout= '';
			$this->set('type',$type);
			$logs = $this->Log->find('all',array('conditions'=>array('Log.user_id'=>$user_id,'Log.contact_id'=>$contactid,'Log.inbox_type'=>1,'Log.is_deleted'=>0),'order' => array('Log.created DESC'),'limit' => 10));
			$this->set('logs',array_reverse($logs));
		}else{
			$this->autoRender = false;
			echo 0;
		}
	}
	function groupfavorite($group_id=null,$favorite=null){
		$this->autoRender = false;
		if($group_id > 0){
			$group_arr['Group']['id'] = $group_id;
			if($favorite==1){
				$group_arr['Group']['favorite'] = 0;
			}else{
				$group_arr['Group']['favorite'] = 1;
			}
			$this->Group->save($group_arr);
		}
		echo $group_id;
	}
	function favorites_mark(){
		$this->autoRender = false;
		$this->Session->write('User.favorites', 1);
		$this->redirect(array('action'=>'index'));
	}
	function favorites_unmark(){
		$this->autoRender = false;
		$this->Session->write('User.favorites', 0);
		$this->redirect(array('action'=>'index'));
	}
	function markunread($id=null){
		$this->autoRender = false;
		$log_arr['Log']['id'] = $id;
		$log_arr['Log']['read'] = 0;
		$this->Log->save($log_arr);
	}
	function markread($id=null){
		$this->autoRender = false;
		$log_arr['Log']['id'] = $id;
		$log_arr['Log']['read'] = 1;
		$this->Log->save($log_arr);
	}
	function messages(){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		$this->Contact->bindModel(
		array('hasMany' => array('Log' => array(
			'className' => 'Log',			 
			'foreignKey' => 'contact_id',				
			'conditions' => array('Log.inbox_type'=>1,'Log.is_deleted'=>0,'Log.read'=>0),
			'fields' => array(),
			'limit' => 10,
			'order' => array('Log.created DESC'),
		))));
		//$contactArr = $this->Contact->find('all',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0)));
		$contactArr = $this->Contact->find('all',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.un_subscribers'=>0),'order'=>array('Contact.lastmsg DESC'),'limit'=>50));
		foreach($contactArr as $data){
			if(count($data['Log']) > 0){
				$firstArray['id'] = $data['Contact']['id'];
				$firstArray['msgcount'] = count($data['Log']);
				$firstArray['notify'] = $data['Log'][0]['notify'];
				$firstArray['route'] = $data['Log'][0]['route'];
				if($data['Log'][0]['msg_type']=='text'){
					$firstArray['msg'] =ucfirst(substr($data['Log'][0]['text_message'],0,20)).'....';
					$firstArray['msgnotification'] =$data['Log'][0]['text_message'];
				}else{
					$firstArray['msg'] = 'New Voicemail....';
					$firstArray['msgnotification'] = 'New Voicemail';
				}
				$firstArray['msgid'] = $data['Log'][0]['id'];
				$firstArray['msgsound'] = $data['Log'][0]['msgsound'];
				$finaljson[] = $firstArray;
			}
		}
		echo $json_string = json_encode($finaljson);
	}
	function sendsms($type = null,$contact_id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		$status = 0;

		if(!empty($_POST)){
			if($_POST['message'] !=''){
				$userDetails = $this->User->find('first',array('conditions'=>array('User.assigned_number'=>$_POST['sendernumber'])));
				$from = '';
				if(!empty($userDetails)){
					if($userDetails['User']['sms']==1){
						$from=$userDetails['User']['assigned_number'];
					}else if($userDetails['User']['mms']==1){
						$from=$userDetails['User']['assigned_number'];
					}
				}else{
					app::import('Model','UserNumber');
					$this->UserNumber = new UserNumber();
					$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.number'=>$_POST['sendernumber'])));
					if(!empty($user_numbers)){
						if($user_numbers['UserNumber']['sms']==1){
							$from=$user_numbers['UserNumber']['number'];
						}else if($user_numbers['UserNumber']['mms']==1){
							$from=$user_numbers['UserNumber']['number'];
						}
					}
				}
				if($from !=''){
					$userDetails = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
					$contact = $this->Contact->find('first',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.id'=>$contact_id)));
					if(!empty($contact)){
						if($userDetails['User']['sms_balance'] > 0){
                                                        
                                                        Controller::loadModel('Log');
                                                        $this->Log->updateAll(array('Log.read' => 1),array('Log.contact_id' =>$contact_id));
							
                                                        if (strpos($_POST['message'], '[Name]') !== false) {
								$_POST['message'] =str_replace('[Name]',$contact['Contact']['name'],$_POST['message']);
							}
							if (strpos($_POST['message'], '[Email]') !== false) {
								$_POST['message'] =str_replace('[Email]',$contact['Contact']['email'],$_POST['message']);
							}
							if (strpos($_POST['message'], '[Phone]') !== false) {
								$_POST['message'] =str_replace('[Phone]',$contact['Contact']['phone_number'],$_POST['message']);
							}
							if (strpos($_POST['message'], '[Birthday]') !== false) {
								$_POST['message'] =str_replace('[Birthday]',$contact['Contact']['birthday'],$_POST['message']);
							}
							$body = $_POST['message'];
							if(API_TYPE==0){
								$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
								$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
								$response = $this->Twilio->sendsms($contact['Contact']['phone_number'],$from,$body);
								$smsid = '';
								if(isset($response->ResponseXml->Message->Sid)){
									$smsid=$response->ResponseXml->Message->Sid;
								}
								$responseStatus=$response->ResponseXml->RestException->Status;
								Controller::loadModel('Log');
								$this->Log->create();
								$log_arr['Log']['sms_id'] =$smsid; 
								$log_arr['Log']['contact_id'] =$contact['Contact']['id']; 
								$log_arr['Log']['user_id'] = $this->Session->read('User.id'); 
								$log_arr['Log']['phone_number'] = $contact['Contact']['phone_number'];
								$log_arr['Log']['text_message'] = $body; 
								$log_arr['Log']['route'] = 'outbox'; 
								$log_arr['Log']['inbox_type'] = 1;
								$log_arr['Log']['read'] = 1;
								if($responseStatus==400){
									$log_arr['Log']['sms_status']= 'failed';
									$ErrorMessage = $response->ErrorMessage;
									$log_arr['Log']['error_message']=$ErrorMessage;
								}
								if($this->Log->save($log_arr)){
									Controller::loadModel('Contact');
									$contact_arra_save['Contact']['id'] = $contact_id;
									$contact_arra_save['Contact']['lastmsg'] =date('Y-m-d H:i:s');
									$this->Contact->save($contact_arra_save);
									echo "1";
								}else{
									echo "1";
								}
							}else if(API_TYPE==2){
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
								$log_arr['Log']['sms_id'] =$message_id; 
								$log_arr['Log']['contact_id'] =$contact['Contact']['id']; 
								$log_arr['Log']['user_id'] = $this->Session->read('User.id'); 
								$log_arr['Log']['phone_number'] = $contact['Contact']['phone_number'];
								$log_arr['Log']['text_message'] = $body; 
								$log_arr['Log']['route'] = 'outbox'; 
								$log_arr['Log']['inbox_type'] = 1;
								$log_arr['Log']['read'] = 1;
								if($status !='ok'){
									$log_arr['Log']['sms_status']= 'failed';
									$log_arr['Log']['error_message']=$errortext;
								}else{
									$log_arr['Log']['sms_status']= 'sent';
								}
								if($this->Log->save($log_arr)){
									Controller::loadModel('Contact');
									$contact_arra_save['Contact']['id'] = $contact_id;
									$contact_arra_save['Contact']['lastmsg'] =date('Y-m-d H:i:s');
									if($this->Contact->save($contact_arra_save)){
										if($message_id!=''){
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
											}
										}
									}
									echo "1";
								}else{
									echo "1";
								}
							}else if(API_TYPE==3){
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								$response = $this->Plivo->sendsms($contact['Contact']['phone_number'],$from,$body);
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
								$log_arr['Log']['sms_id'] =$message_id; 
								$log_arr['Log']['contact_id'] =$contact['Contact']['id']; 
								$log_arr['Log']['user_id'] = $this->Session->read('User.id'); 
								$log_arr['Log']['phone_number'] = $contact['Contact']['phone_number'];
								$log_arr['Log']['text_message'] = $body; 
								$log_arr['Log']['route'] = 'outbox'; 
								$log_arr['Log']['inbox_type'] = 1;
								$log_arr['Log']['read'] = 1;
								if(isset($response['response']['error'])){
									$log_arr['Log']['sms_status']= 'failed';
									$log_arr['Log']['error_message']=$errortext;
								}else{
									$log_arr['Log']['sms_status']= 'sent';
								}
								if($this->Log->save($log_arr)){
									Controller::loadModel('Contact');
									$contact_arra_save['Contact']['id'] = $contact_id;
									$contact_arra_save['Contact']['lastmsg'] =date('Y-m-d H:i:s');
									if($this->Contact->save($contact_arra_save)){
										if($message_id!=''){
											Controller::loadModel('User');
											$this->User->id = $this->Session->read('User.id'); 
											if($this->User->id!=''){
												$length = strlen(utf8_decode(substr($body,0,1600)));
												if (strlen($body) != strlen(utf8_decode($body))){
													$credits = ceil($length/70);
												}else{
												        $credits = ceil($length/160);
												}
												$this->User->saveField('sms_balance', ($userDetails['User']['sms_balance']-$credits));
											}
										}
									}
									echo "1";
								}else{
									echo "1";
								}
							}else{
								$this->Nexmomessage->Key = NEXMO_KEY;
								$this->Nexmomessage->Secret = NEXMO_SECRET;
								$response = $this->Nexmomessage->sendsms($contact['Contact']['phone_number'],$from,$body);
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
								//saving logs
								Controller::loadModel('Log');
								$this->Log->create();
								$log_arr['Log']['sms_id'] =$message_id; 
								$log_arr['Log']['contact_id'] =$contact['Contact']['id']; 
								$log_arr['Log']['user_id'] = $this->Session->read('User.id'); 
								$log_arr['Log']['phone_number'] = $contact['Contact']['phone_number'];
								$log_arr['Log']['text_message'] = $body; 
								$log_arr['Log']['route'] = 'outbox'; 
								$log_arr['Log']['inbox_type'] = 1;
								$log_arr['Log']['read'] = 1;
								if($status!=0){
									$log_arr['Log']['sms_status']= 'failed';
									$log_arr['Log']['error_message']=$errortext;
								}else{
									$log_arr['Log']['sms_status']= 'sent';
								}
								if($this->Log->save($log_arr)){
									Controller::loadModel('Contact');
									$contact_arra_save['Contact']['id'] = $contact_id;
									$contact_arra_save['Contact']['lastmsg'] =date('Y-m-d H:i:s');
									if($this->Contact->save($contact_arra_save)){
										if($message_id!=''){
											Controller::loadModel('User');
											$this->User->id = $this->Session->read('User.id'); 
											if($this->User->id!=''){
												$length = strlen(utf8_decode(substr($body,0,1600)));
												if (strlen($body) != strlen(utf8_decode($body))){
													$credits = ceil($length/70);
												}else{
												        $credits = ceil($length/160);
												}
												$this->User->saveField('sms_balance', ($userDetails['User']['sms_balance']-$credits));
											}
										}
									}
									echo "1";
								}else{
									echo "1";
								}
							}
						}else{
							echo "4";
						}
					}else{
						echo "1";
					}
				}else{
					echo "You do not have any number with MMS capability.";
				}
			}else{
				echo "1";
			}
		}
	}
	function groupchat($type = null,$group_id=null){
		$user_id = $this->Session->read('User.id');
		if($user_id > 0){
			$this->layout= '';
			$groups = $this->Group->find('first',array('conditions'=>array('Group.user_id'=>$user_id,'Group.id'=>$group_id)));
			$this->set('groups',$groups);
			$this->set('type',$type);
			$this->GroupSmsBlast->bindModel(
				array('hasMany' => array('Log' => array(
				'className' => 'Log',			 
				'foreignKey' => 'group_sms_id',				
				'conditions' => array(),
				'fields' => array(),
				'order' => array('Log.created DESC'),
			))));
			$logs = $this->GroupSmsBlast->find('all',array('conditions'=>array('GroupSmsBlast.user_id'=>$user_id,'GroupSmsBlast.group_id'=>$group_id,'GroupSmsBlast.isdeleted'=>0,'GroupSmsBlast.responder'=>0),'limit' => 10,'order' => array('GroupSmsBlast.created asc')));
			$this->set('logs',$logs);
		}else{
			$this->autoRender = false;
			echo 0;
		}
	}
	function groupchatmsg($type = null,$group_id=null){
		$user_id = $this->Session->read('User.id');
		if($user_id > 0){
			$this->layout= '';
			$this->set('type',$type);
			$this->GroupSmsBlast->bindModel(
				array('hasMany' => array('Log' => array(
				'className' => 'Log',			 
				'foreignKey' => 'group_sms_id',				
				'conditions' => array(),
				'fields' => array(),
				'limit' => 10,
				'order' => array('Log.created DESC'),
			))));
			$logs = $this->GroupSmsBlast->find('all',array('conditions'=>array('GroupSmsBlast.user_id'=>$user_id,'GroupSmsBlast.group_id'=>$group_id,'GroupSmsBlast.isdeleted'=>0,'GroupSmsBlast.responder'=>0),'limit' => 10,'order' => array('GroupSmsBlast.created asc')));
			$this->set('logs',$logs);
		}else{
			$this->autoRender = false;
			echo 0;
		}
	}
	function sendsmsgroup($type = null,$group_id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		$status = 0;
		if(!empty($_POST)){
			if($_POST['message'] !=''){
				$userDetail = $this->User->find('first',array('conditions'=>array('User.assigned_number'=>$_POST['sendernumber'])));
				$from = '';
				if(!empty($userDetail)){
					if($userDetail['User']['sms']==1){
						$from=$userDetail['User']['assigned_number'];
					}
				}else{
					app::import('Model','UserNumber');
					$this->UserNumber = new UserNumber();
					$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1,'UserNumber.number'=>$_POST['sendernumber'])));
					if(!empty($user_numbers)){	
						$from=$user_numbers['UserNumber']['number'];
					}
				}
				$userDetails = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
				if($from !=''){
					$group = $this->Group->find('first',array('conditions'=>array('Group.user_id'=>$user_id,'Group.id'=>$group_id)));
					$contacts_list = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id,'ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
					if(!empty($contacts_list)){
						if(!empty($group)){
							$GroupSmsBlast_arr['GroupSmsBlast']['id'] = '';
							$GroupSmsBlast_arr['GroupSmsBlast']['user_id'] = $user_id;
							$GroupSmsBlast_arr['GroupSmsBlast']['group_id'] = $group_id;
							$GroupSmsBlast_arr['GroupSmsBlast']['totals'] = $group['Group']['totalsubscriber'];
							$GroupSmsBlast_arr['GroupSmsBlast']['isdeleted'] = 0;
							$GroupSmsBlast_arr['GroupSmsBlast']['msg'] = $_POST['message'];
							$GroupSmsBlast_arr['GroupSmsBlast']['created'] =date('Y-m-d H:i:s');
							if($this->GroupSmsBlast->save($GroupSmsBlast_arr)){
								$group_blast_id = $this->GroupSmsBlast->id;
								if($group_blast_id > 0){
									$contacts = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id,'ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
									if(!empty($contacts)){
										$totalsubscribers= count($contacts);
										if($userDetails['User']['sms_balance'] > $totalsubscribers){
											$group_sms_blast = $this->GroupSmsBlast->find('first',array('conditions'=>array('GroupSmsBlast.user_id'=>$user_id,'GroupSmsBlast.id'=>$group_blast_id)));
											foreach($contacts as $contact){
												$contactdetails = $this->Contact->find('first',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.id'=>$contact['Contact']['id'])));
												if (strpos($_POST['message'], '[Name]') !== false) {
													$_POST['message'] =str_replace('[Name]',$contact['Contact']['name'],$_POST['message']);
												}
												if (strpos($_POST['message'], '[Email]') !== false) {
													$_POST['message'] =str_replace('[Email]',$contact['Contact']['email'],$_POST['message']);
												}
												if (strpos($_POST['message'], '[Phone]') !== false) {
													$_POST['message'] =str_replace('[Phone]',$contact['Contact']['phone_number'],$_POST['message']);
												}
												if (strpos($_POST['message'], '[Birthday]') !== false) {
													$_POST['message'] =str_replace('[Birthday]',$contact['Contact']['birthday'],$_POST['message']);
												}
												$body =$_POST['message'];
												$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
												$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
												$response = $this->Twilio->sendsms($contact['Contact']['phone_number'],$from,$body);
												if(isset($response->ResponseXml->Message->Sid)){
													$smsid=$response->ResponseXml->Message->Sid;
												}
												$responseStatus=$response->ResponseXml->RestException->Status;
												Controller::loadModel('Log');
												$this->Log->create();
												$log_arr['Log']['sms_id'] =$smsid;
												$log_arr['Log']['group_sms_id'] =$group_blast_id; 
												$log_arr['Log']['group_id'] =$group_id; 
												$log_arr['Log']['contact_id'] =$contact['Contact']['id']; 
												$log_arr['Log']['user_id'] = $this->Session->read('User.id'); 
												$log_arr['Log']['phone_number'] = $contact['Contact']['phone_number'];
												$log_arr['Log']['text_message'] = $body; 
												$log_arr['Log']['route'] = 'outbox'; 
												$log_arr['Log']['inbox_type'] = 1;
												$log_arr['Log']['read'] = 1;
												if($responseStatus==400){
													$log_arr['Log']['sms_status']= 'failed';
													$ErrorMessage = $response->ErrorMessage;
													$log_arr['Log']['error_message']=$ErrorMessage;
												}else{
													$log_arr['Log']['sms_status']= 'sent';
												}
												if($this->Log->save($log_arr)){
													$contact_arra_save['Contact']['id'] =$contact['Contact']['id'];
													$contact_arra_save['Contact']['lastmsg'] =date('Y-m-d H:i:s');
													$this->Contact->save($contact_arra_save);
												}
												if($responseStatus==400){
													if($group_blast_id > 0){
														$GroupSmsBlast_arr['GroupSmsBlast']['id'] = $group_blast_id;
														$GroupSmsBlast_arr['GroupSmsBlast']['total_failed_messages'] = $group_sms_blast['GroupSmsBlast']['total_failed_messages'] + 1;
														$this->GroupSmsBlast->save($group_sms_blast);
													}
												}
											}
											echo "1";
										}else{
											echo "4";
										}
									}else{
										echo "3";
									}
								}else{
									echo "1";
								}
							}else{
								echo "1";
							}
						}else{
							echo "Please create a group before sending a message.";
						}
					}else{
						echo "3";
					}
				}else{
					echo "You do not have any number with SMS capability.";
				}
			}else{
				echo "1";
			}
		}
	}
	function shortlinks(){
		$this->layout='popup';
		app::import('Model','Shortlink');
		$this->Shortlink = new Shortlink();
		$user_id=$this->Session->read('User.id');	
		$this->paginate = array('conditions' => array('Shortlink.user_id' =>$user_id),'order' =>array('Shortlink.id' => 'asc'));
		$data = $this->paginate('Shortlink');
		$this->set('shortlink', $data);
	}
	function msgtemplates(){
		$this->layout='popup';
		$user_id=$this->Session->read('User.id');
		app::import('Model','Smstemplate');
		$this->Smstemplate = new Smstemplate();
		$Smstemplate = $this->Smstemplate->find('all',array('conditions' => array('Smstemplate.user_id'=> $user_id),'order' =>array('Smstemplate.id' => 'desc')));
		$this->set('Smstemplate',$Smstemplate);
	}
	function checktemplate($templateid=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		app::import('Model','Smstemplate');
		$this->Smstemplate = new Smstemplate();
		$checktemplatedata = $this->Smstemplate->find('first',array('conditions' => array('Smstemplate.id'=> $templateid,'Smstemplate.user_id'=>$user_id)));
		echo $checktemplatedata['Smstemplate']['message_template'];
	
	}
	function checkshortlink($id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		app::import('Model','Shortlink');
		$this->Shortlink = new Shortlink();
		$checktemplatedata = $this->Shortlink->find('first',array('conditions' => array('Shortlink.id'=> $id,'Shortlink.user_id'=>$user_id)));
		echo $checktemplatedata['Shortlink']['short_url'];
	
	}
	function deletemsg($log_id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		if($log_id > 0){
			$this->Log->updateAll(array('Log.is_deleted' => 1),array('Log.id' =>$log_id));
			echo "Message Deleted";
		}else{
			echo "Message Not Deleted";
		}
	}
	function deletegroup_thread($group_id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		if($group_id > 0){
			$this->GroupSmsBlast->updateAll(array('GroupSmsBlast.isdeleted' => 1),array('GroupSmsBlast.group_id' =>$group_id));
			$this->Log->updateAll(array('Log.is_deleted' => 1),array('Log.group_id' =>$group_id));
			echo "Thread Deleted";
		}else{
			echo "Contact Missing";
		}
	}
	function deletemsggroup($id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		if($id > 0){
			$this->GroupSmsBlast->updateAll(array('GroupSmsBlast.isdeleted' => 1),array('GroupSmsBlast.id' =>$id));
			$this->Log->updateAll(array('Log.is_deleted' => 1),array('Log.group_sms_id' =>$id));
			echo "Message Deleted";
		}else{
			echo "Message Deleted";
		}
	}
	function deletecontact_thread($contact_id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		if($contact_id > 0){
			$this->Log->updateAll(array('Log.is_deleted' => 1),array('Log.contact_id' =>$contact_id));
			echo "Message Deleted";
		}else{
			echo "Contact Missing";
		}
	}
	function retry($id=null){
		$this->autoRender = false;
		$user_id = $this->Session->read('User.id');
		$logs = $this->Log->find('first',array('conditions'=>array('Log.user_id'=>$user_id,'Log.id'=>$id)));
		if(!empty($logs)){
			$userDetails = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
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
			if($userDetails['User']['sms_balance'] > 0){
				$contact = $this->Contact->find('first',array('conditions'=>array('Contact.user_id'=>$user_id,'Contact.id'=>$logs['Log']['contact_id'])));
				if(API_TYPE==0){
					$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
					$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
					$response = $this->Twilio->sendsms($logs['Log']['phone_number'],$from,$logs['Log']['text_message']);
					$smsid = '';
					if(isset($response->ResponseXml->Message->Sid)){
						$smsid=$response->ResponseXml->Message->Sid;
					}
					$responseStatus=$response->ResponseXml->RestException->Status;
					Controller::loadModel('Log');

					$log_arr['Log']['id'] =$id;
					$log_arr['Log']['sms_id'] =$smsid;
					$log_arr['Log']['text_message'] = $logs['Log']['text_message']; 
					$log_arr['Log']['created'] = date('Y-m-d H:i:s'); 
					if($responseStatus==400){
						$log_arr['Log']['sms_status']= 'failed';
						$ErrorMessage = $response->ErrorMessage;
						$log_arr['Log']['error_message']=$ErrorMessage;
					}
					if(isset($response->ResponseXml->Message->Sid)){
						$length = strlen(utf8_decode(substr($logs['Log']['text_message'],0,1600))); 
						if (strlen($logs['Log']['text_message']) != strlen(utf8_decode($logs['Log']['text_message']))){
							$credits =ceil($length/70);
						}else{
							$credits =ceil($length/160);
						}
						//$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
						//if(!empty($usersbalance)){
							$usercredit['User']['id'] =$this->Session->read('User.id'); 
							$usercredit['User']['sms_balance'] = $userDetails['User']['sms_balance']-$credits; 
							$this->User->save($usercredit);
						//}
					}
					if($this->Log->save($log_arr)){
						echo 1;
					}else{
						echo 1;
					}
				}else if(API_TYPE==2){
					$response = $this->Slooce->mt($userDetails['User']['api_url'],$userDetails['User']['partnerid'],$userDetails['User']['partnerpassword'],$logs['Log']['phone_number'],$userDetails['User']['keyword'],$logs['Log']['text_message']);
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

					$log_arr['Log']['id'] =$id;
					$log_arr['Log']['sms_id'] =$message_id;
					$log_arr['Log']['text_message'] = $logs['Log']['text_message']; 
					$log_arr['Log']['created'] = date('Y-m-d H:i:s'); 
					if($status !='ok'){
						$log_arr['Log']['sms_status']= 'failed';
						$log_arr['Log']['error_message']=$status;
					}else{
						$log_arr['Log']['sms_status']= 'sent';
					}
					if($this->Log->save($log_arr)){
						if($message_id!=''){
							$length = strlen(utf8_decode(substr($logs['Log']['text_message'],0,1600))); 
							if (strlen($logs['Log']['text_message']) != strlen(utf8_decode($logs['Log']['text_message']))){
								$credits =ceil($length/70);
							}else{
								$credits =ceil($length/160);
							}
							//$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
							//if(!empty($usersbalance)){
								$usercredit['User']['id'] =$this->Session->read('User.id'); 
								$usercredit['User']['sms_balance'] = $userDetails['User']['sms_balance']-$credits; 
								$this->User->save($usercredit);
							//}
						}
						echo "1";
					}else{
						echo "1";
					}
				}else if(API_TYPE==3){
					$this->Plivo->AuthId =PLIVO_KEY;
					$this->Plivo->AuthToken =PLIVO_TOKEN;
					$response = $this->Plivo->sendsms($logs['Log']['phone_number'],$from,$logs['Log']['text_message']);
					$errortext = '';
					$message_id = '';
					if(isset($response['response']['error'])){
						$errortext = $response['response']['error'];
					}
					if(isset($response['response']['message_uuid'][0])){
						$message_id = $response['response']['message_uuid'][0];
					}
					Controller::loadModel('Log');
                                       
					$log_arr['Log']['id'] =$id;
					$log_arr['Log']['sms_id'] =$message_id;
                                   	$log_arr['Log']['text_message'] = $logs['Log']['text_message']; 
                                 	$log_arr['Log']['created'] = date('Y-m-d H:i:s'); 
					if(isset($response['response']['error'])){
						$log_arr['Log']['sms_status']= 'failed';
						$log_arr['Log']['error_message']=$errortext;
					}else{
                                                $log_arr['Log']['sms_status']= 'sent';
                                        }
					if(isset($response['response']['message_uuid'][0])){
						$length = strlen(utf8_decode(substr($logs['Log']['text_message'],0,1600))); 
						if (strlen($logs['Log']['text_message']) != strlen(utf8_decode($logs['Log']['text_message']))){
							$credits =ceil($length/70);
						}else{
							$credits =ceil($length/160);
						}
						//$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
						//if(!empty($usersbalance)){
							$usercredit['User']['id'] =$this->Session->read('User.id'); 
							$usercredit['User']['sms_balance'] = $userDetails['User']['sms_balance']-$credits; 
							$this->User->save($usercredit);
						//}
					}
					if($this->Log->save($log_arr)){
						echo 1;
					}else{
						echo 1;
					}
				}else{
					$this->Nexmomessage->Key = NEXMO_KEY;
					$this->Nexmomessage->Secret = NEXMO_SECRET;
					$response = $this->Nexmomessage->sendsms($logs['Log']['phone_number'],$from,$logs['Log']['text_message']);
					$message_id = '';
					$status = '';
					$errortext = '';
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
					Controller::loadModel('Log');
                                        $this->Log->create();
					$log_arr['Log']['id'] =$id;
					$log_arr['Log']['sms_id'] =$message_id;
					$log_arr['Log']['text_message'] = $logs['Log']['text_message']; 
					$log_arr['Log']['created'] = date('Y-m-d H:i:s'); 
					if($status!=0){
						$log_arr['Log']['sms_status']= 'failed';
						$log_arr['Log']['error_message']=$errortext;
					}
					if($message_id !=''){
						$length = strlen(utf8_decode(substr($logs['Log']['text_message'],0,1600))); 
						if (strlen($logs['Log']['text_message']) != strlen(utf8_decode($logs['Log']['text_message']))){
							$credits =ceil($length/70);
						}else{
							$credits =ceil($length/160);
						}
						//$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
						//if(!empty($usersbalance)){
							$usercredit['User']['id'] =$this->Session->read('User.id'); 
							$usercredit['User']['sms_balance'] = $userDetails['User']['sms_balance']-$credits; 
							$this->User->save($usercredit);
						//}
					}
					if($this->Log->save($log_arr)){
						echo 1;
					}else{
						echo 1;
					}
				}
			}
		}
	}
	// delete user from inbox 
	function deletecontact($id=null){
		$this->autoRender = false;
		$user_id=$this->Session->read('User.id');
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();		
		app::import('Model','Group');
		$this->Group = new Group();				
		$this->ContactGroup->bindModel(
			array('belongsTo' => array('Group' => array(
			'className' => 'Group',			 
			'foreignKey' => 'group_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));	
		$contactArr = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.contact_id'=>$id)));
		if(!empty($contactArr)){
			foreach($contactArr as $contacts){
				$ContactGroupId = $contacts['ContactGroup']['id'];
				$un_subscribers = $contacts['ContactGroup']['un_subscribers'];			
				if($un_subscribers==0){
					$GroupId = $contacts['Group']['id'];				
					$updateGroup['Group']['id'] = $GroupId;
					
					if($contacts['Group']['totalsubscriber'] < 0){
						$updateGroup['Group']['totalsubscriber'] = 0;
					}else{
						$updateGroup['Group']['totalsubscriber'] = $contacts['Group']['totalsubscriber'] - 1;
					}
					$this->Group->save($updateGroup);	
				}
				// delete contact from conatct table
				$this->Contact->delete($id);	

				// delete logs also from logs table
				$this->Log->query('delete from logs where contact_id='.$id.'');	
				
				// delete contact relation
				$this->ContactGroup->delete($ContactGroupId);
				// update group subscriber fields				
			}
		}else{
			// delete contact from conatct table
			$this->Contact->delete($id);	
		}
		//$this->Session->setFlash('Contact Deleted');			
		//$this->redirect(array('controller' =>'chats', 'action'=>'index'));
                echo "Contact Deleted";
	}
	function downloadhistory($contact_id=null){
		$this->autoRender = false;
		$user_id=$this->Session->read('User.id');
		ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
		$logs = $this->Log->find('all',array('conditions' => array('Log.user_id' =>$user_id,'Log.contact_id' =>$contact_id,'Log.msg_type' => 'text'),'fields' => array('Log.created','Log.phone_number','Log.text_message','Log.route','Log.msg_type','Log.sms_status','Log.email_to_sms_number'),'order' =>array('Log.id' => 'asc')));
		$filename = "logs".date("Y.m.d").".csv";
		$csv_file = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'"'); 
		$header_row = array("Contact Phone Number","Inbound Phone Number","Message","Route","Msg Type","Status","Created");
		fputcsv($csv_file,$header_row,',','"');
		foreach($logs as $result){
			// Array indexes correspond to the field names in your db table(s)
			$row = array($result['Log']['phone_number'],$result['Log']['email_to_sms_number'],$result['Log']['text_message'],$result['Log']['route'],$result['Log']['msg_type'],$result['Log']['sms_status'],$result['Log']['created']);
			fputcsv($csv_file,$row,',','"');
		}
		fclose($csv_file);
	}
	function msgsoundplay($log_id=null){
		$this->autoRender = false;
		if($log_id > 0){
			$this->Log->updateAll(array('Log.msgsound' => 1),array('Log.id' =>$log_id));
			echo "Message Deleted";
		}else{
			echo "Message Not Deleted";
		}
	}

       function credits(){
		$this->autoRender = false;
		$userSMSbalance = $this->getLoggedUserDetails();
                $smsbalance = $userSMSbalance['User']['sms_balance'];

                $firstArray['smsbalance'] = $smsbalance;
		$finaljson[] = $firstArray;
	
		echo $json_string = json_encode($finaljson);
	}
}
?>