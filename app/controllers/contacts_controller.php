<?php
class ContactsController extends AppController {

	var $name = 'Contacts';	
	var $components = array('Cookie','Email','Slooce');
	function index($field=null,$short=null) {
		$this->layout= 'admin_new_layout';
		$this->Contact->recursive = 0;
		$user_id= $this->Session->read('User.id');
		/*********************************************/
		app::import('Model','Group');
		$this->Group = new Group();
		$group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
		$this->set('Group',$group);
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$this->set('users',$users);

		if(!empty($field)){
			if(!isset($short)){
				$short='desc';
			}
			$order=array();
			if($field){
				$order = array($field.' '.$short);
			}
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			}else{
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$user_id= $this->Session->read('User.id');		
			$this->paginate = array('conditions' => array('ContactGroup.user_id'=>$user_id),'order' =>array('ContactGroup.created' => 'desc'));
			$data = $this->paginate('ContactGroup');
			$this->set('contacts', $data);
			$Subscribercountfind = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id' =>$user_id)));
			$this->Session->write('contacts', $Subscribercountfind);
		}
		$Subscribercount = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id' =>$user_id,'ContactGroup.un_subscribers'=>0)));
		$this->set('Subscribercount',$Subscribercount);
                
		$totsubscribercount = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id' =>$user_id,'ContactGroup.un_subscribers'=>0)));
		$this->set('totsubscribercount',$totsubscribercount );

		$unsubscribercount = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id' =>$user_id,'ContactGroup.un_subscribers'=>1)));
		$this->set('unsubscribercount',$unsubscribercount );

		$Subscribercountsms = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id' =>$user_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms'=>1)));
		$this->set('Subscribercountsms',$Subscribercountsms );

		$Subscribercountwidget = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id'=>$user_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms'=>2)));
		$this->set('Subscribercountwidget',$Subscribercountwidget );

		$Subscribercountkiosk = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id'=>$user_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms'=>3)));
		$this->set('Subscribercountkiosk',$Subscribercountkiosk );

		$Subscribercountimport = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id'=>$user_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms'=>0)));
		$this->set('Subscribercountimport',$Subscribercountimport );

		$user_id= $this->Session->read('User.id');
		app::import('Model','ContactGroup');	
		$this->ContactGroup = new ContactGroup();
		if($this->data['Contact']['phone']==1){
			$conditions = array('OR' => array(array(array('Contact.name like' => $this->data['Contact']['name'].'%'),array('ContactGroup.user_id' => $user_id))));
			$conditionscount = array('OR' => array(array(array('ContactGroup.un_subscribers' =>'0'),array('Contact.name like' => $this->data['Contact']['name'].'%'),array('ContactGroup.user_id' => $user_id))));	
			$this->set('contacts','');
			$this->set('Subscribercount',0);
		}elseif( $this->data['Contact']['phone']==2){
			$conditions = array('OR' => array(array(array('Contact.phone_number' =>$this->data['Contact']['name']),array('ContactGroup.user_id' => $user_id))));
			$conditionscount = array('OR' => array(array(array('ContactGroup.un_subscribers' =>'0'),array('Contact.phone_number' =>$this->data['Contact']['name']),array('ContactGroup.user_id' => $user_id))));	
			$this->set('contacts','');	
			$this->set('Subscribercount',0);
		} 
		/***********************************************************************/
		if($this->data['Group']['id']!=0 && $this->data['Contact']['source']!=4){
			$conditions = array('OR' => array(array(array('ContactGroup.group_id' => $this->data['Group']['id']),array('ContactGroup.subscribed_by_sms' => $this->data['Contact']['source']),array('ContactGroup.user_id' => $user_id))));
			
			$conditionscount = array('OR' => array(array(array('ContactGroup.un_subscribers' =>'0'),array('ContactGroup.group_id' => $this->data['Group']['id']),array('ContactGroup.subscribed_by_sms' => $this->data['Contact']['source']),array('ContactGroup.user_id' => $user_id))));	
			$this->set('contacts','');
			$this->set('Subscribercount',0);
		}else if ($this->data['Contact']['source']!=4){
			$conditions = array('OR' => array(array(array('ContactGroup.subscribed_by_sms' => $this->data['Contact']['source']),array('ContactGroup.user_id' => $user_id))));	
			$conditionscount = array('OR' => array(array(array('ContactGroup.un_subscribers' =>'0'),array('ContactGroup.subscribed_by_sms' => $this->data['Contact']['source']),array('ContactGroup.user_id' => $user_id))));
			$this->set('contacts','');		
			$this->set('Subscribercount',0);
		}else if($this->data['Group']['id']!=0){
			$conditions = array('OR' => array(array(array('ContactGroup.group_id' => $this->data['Group']['id']),array('ContactGroup.user_id' => $user_id))));	
			$conditionscount = array('OR' => array(array(array('ContactGroup.un_subscribers' =>'0'),array('ContactGroup.group_id' => $this->data['Group']['id']),array('ContactGroup.user_id' => $user_id))));
			$this->set('contacts','');	
			$this->set('Subscribercount',0);
		}
		$Subscriber11 = $this->ContactGroup->find('all',array('conditions'=>$conditions));
		$Subscribercount = $this->ContactGroup->find('count',array('conditions'=>$conditionscount));
		if(!empty($this->data)){
			$this->set('contacts',$Subscriber11);
			$this->set('Subscribercount',$Subscribercount);
			$this->Session->write('contacts', $Subscriber11);
		}else if(!empty($order)){
			$this->paginate = array('conditions' => array('ContactGroup.user_id' =>$user_id),'order' =>$order);	
			$data = $this->paginate('ContactGroup');	
			$this->set('contacts', $data);
			
			$Subscribercount = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id' =>$user_id,'ContactGroup.un_subscribers'=>0)));	
			$this->set('Subscribercount',$Subscribercount);
			if($short=='asc'){
			$short = 'desc';
			}else{
				$short = 'asc';
			}
			$this->set('sort',$short);
		}else{
			$this->paginate = array('conditions' => array('ContactGroup.user_id'=>$user_id),'order' =>array('ContactGroup.created' => 'desc'));	
			$data = $this->paginate('ContactGroup');
			$this->set('contacts', $data);
			$Subscribercount = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.user_id'=>$user_id,'ContactGroup.un_subscribers'=>0)));		
			$this->set('Subscribercount',$Subscribercount);	
			$this->Session->write('contacts', $Subscriber11);
		}

	}
	function add($source = null) {	
		$this->layout = 'popup';	
		$user_id=$this->Session->read('User.id');	
		app::import('Model','Group');
		$this->Group = new Group();	
		$contacts_members1 =$this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
		$this->set('groupname',$contacts_members1);
		/*********/
		$phoneno1 =$this->data['Contact']['phone_number'];
		$phone = str_replace('+','',$phoneno1);		
		$phone_number= str_replace('-','',$phone);
		$this->set('source',$source);	
		/********/	
		if (!empty($this->data)) {
			if(API_TYPE==2){
				$users=$this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
				if(!empty($users)){
					$response = $this->Slooce->supported($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$phone_number,$users['User']['keyword']);
					if($response=='supported'){
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$newsubscriber = $this->ContactGroup->find('first',array('conditions' => array('Contact.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Contact']['phone_number'])));
						if(empty($newsubscriber)){
							$contact['user_id']= $this->Session->read('User.id');
							$contact['name']=$this->data['Contact']['name'];
							$contact['phone_number']=$phone_number;
							$contact['color']=$this->choosecolor();
							$contact['email']=$this->data['Contact']['email'];
							$contact['birthday']=$this->data['Contact']['birthday'];
							$this->Contact->save($contact);
							$contactArr = $this->Contact->getLastInsertId();
							//$contactArr = 1;
						}else{
							$contactArr = $newsubscriber['Contact']['id'];
						}		
						if($contactArr!=''){
							foreach($this->data['Group']['id'] as $groupIds){		
							$group_id = $groupIds;		
							app::import('Model','Group');	
							$this->Group = new Group();		
							$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));	
							app::import('Model','ContactGroup');	
							$this->ContactGroup = new ContactGroup();
								$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
								if(empty($subscriber11)){
									$message= $keywordname['Group']['system_message'].' '.$keywordname['Group']['auto_message'];
									$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$phone_number,$users['User']['keyword'],$message);
									app::import('Model','ContactGroup');			
									$this->ContactGroup = new ContactGroup();	
									$contactgroup['user_id'] = $this->Session->read('User.id');		
									$contactgroup['group_id'] = $group_id;			
									$contactgroup['group_subscribers'] = $keywordname['Group']['keyword'];				
									$contactgroup['contact_id'] = $contactArr;			
									$this->ContactGroup->save($contactgroup);			
									app::import('Model','Group');				
									$this->Group = new Group();		
									$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
									$groupArr['id'] = $groups['Group']['id'];
									$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
									$this->Group->save($groupArr);
									
									$update_user['User']['id'] = $users['User']['id'];
									$update_user['User']['sms_balance'] = $users['User']['sms_balance']-1;
									$this->User->save($update_user);
									$this->Session->setFlash(__('Contact has been saved', true));
								}else{
									$this->Session->setFlash(__('This contact already exists', true));
								}
							}
						}else{
							$this->Session->setFlash(__('The contact could not be saved. Please, try again.', true));
						}
						$this->redirect(array('action' => 'index'));
					}else{
						$this->Session->setFlash(__('The specified phone number is not valid', true));
					}
				}else{
					$this->Session->setFlash(__('User not found', true));
				}
				if ($this->data['Contact']['source']==1){
					$this->redirect(array('controller' =>'groups', 'action' => 'index'));
				}else{
					$this->redirect(array('action' => 'index'));
				}
			}else{
				app::import('Model','ContactGroup');
				$this->ContactGroup = new ContactGroup();
				$newsubscriber = $this->ContactGroup->find('first',array('conditions' => array('Contact.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Contact']['phone_number'])));
				if(empty($newsubscriber)){
					$contact['user_id']= $this->Session->read('User.id');
					$contact['name']=$this->data['Contact']['name'];
					$contact['phone_number']=$phone_number;
					$contact['color']=$this->choosecolor();
					$contact['email']=$this->data['Contact']['email'];
					$contact['birthday']=$this->data['Contact']['birthday'];
					$this->Contact->save($contact);
					$contactArr = $this->Contact->getLastInsertId();
					}else{
					$contactArr = $newsubscriber['Contact']['id'];
				}		
				if($contactArr!=''){
					foreach($this->data['Group']['id'] as $groupIds){		
					$group_id = $groupIds;		
					app::import('Model','Group');	
					$this->Group = new Group();		
					$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));	
					app::import('Model','ContactGroup');	
					$this->ContactGroup = new ContactGroup();
						$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
						if(empty($subscriber11)){
							app::import('Model','ContactGroup');			
							$this->ContactGroup = new ContactGroup();	
							$contactgroup['user_id'] = $this->Session->read('User.id');		
							$contactgroup['group_id'] = $group_id;			
							$contactgroup['group_subscribers'] = $keywordname['Group']['keyword'];				
							$contactgroup['contact_id'] = $contactArr;			
							$this->ContactGroup->save($contactgroup);			
							app::import('Model','Group');				
							$this->Group = new Group();		
							$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
							$groupArr['id'] = $groups['Group']['id'];
							$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
							$this->Group->save($groupArr);
							$this->Session->setFlash(__('Contact has been saved', true));
						}else{
							$this->Session->setFlash(__('This contact already exists', true));
						}
					}
				}else{
					$this->Session->setFlash(__('The contact could not be saved. Please, try again.', true));
				}
				if ($this->data['Contact']['source']==1){
					$this->redirect(array('controller' =>'groups', 'action' => 'index'));
                                }else if($this->data['Contact']['source']==2){
					$this->redirect(array('controller' =>'chats', 'action' => 'index'));				
                                }else{
					$this->redirect(array('action' => 'index'));
				}
			}
		}
	}
	function edit($id = null,$source=null) {
		$this->layout = 'popup';
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid contact', true));
			$this->redirect(array('action' => 'index'));
		}
		$user_id=$this->Session->read('User.id');
		app::import('Model','Group');
		$this->Group = new Group();
		$contacts_groupname =$this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
		$this->set('groupsnames',$contacts_groupname);
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		$message = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.user_id'=> $user_id,'Contact.id'=> $id)));
		foreach($message as $message){	
			$groupid[$message['Group']['id']] = $message['Group']['group_name'];
		}
		$this->set('groupid', $groupid);
		$this->set('email', $message['Contact']['email']);
		$this->set('birthday', $message['Contact']['birthday']);
		if (!empty($this->data)) {
			if(API_TYPE==2){
				$users=$this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
				$newsubscriber_details = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Contact']['phone_number'])));
				if(!empty($users)){
					if(empty($newsubscriber_details)){
						$response = $this->Slooce->supported($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$this->data['Contact']['phone_number'],$users['User']['keyword']);
					}else{
						$response='supported';
					}
					if($response=='supported'){
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$newsubscriber1 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Contact']['phone_number'])));
						if(empty($newsubscriber1)){
							$phoneno1=$this->data['Contact']['phone_number'];
							$phone  = str_replace('+','',$phoneno1);
							$phone_number  = str_replace('-','',$phone);
							$contact['user_id'] = $this->Session->read('User.id');	
							$contact['name']=$this->data['Contact']['name'];	
							$contact['phone_number']=$phone_number;		
							$contact['email']=$this->data['Contact']['email'];
							$contact['birthday']=$this->data['Contact']['birthday'];	
							$contact['color']=$this->choosecolor();	
							$contact['id']=$id;	
							$contact['created']=date('Y-m-d H:i:s');							
							$this->Contact->save($contact);		
							$contactArr=$this->Contact->id;		
						}else{			
							$phoneno1=$this->data['Contact']['phone_number'];
							$phone  = str_replace('+','',$phoneno1);
							$phone_number  = str_replace('-','',$phone);	
							$contact['name']=$this->data['Contact']['name'];	
							$contact['phone_number']=$phone_number;		
							$contact['email']=$this->data['Contact']['email'];
							$contact['birthday']=$this->data['Contact']['birthday'];	
							$contact['color']=$this->choosecolor();	
							$contact['id']=$id;
							$contact['created']=date('Y-m-d H:i:s');							
							$this->Contact->save($contact);		
							$contactArr = $newsubscriber1['Contact']['id'];	
						}		
						if($contactArr!=''){
							app::import('Model','ContactGroup');		
							$this->ContactGroup = new ContactGroup();
							$subscriber12 = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.contact_id'=> $contactArr)));
							foreach($subscriber12 as $groupId){		
							app::import('Model','Group');		
							$this->Group = new Group();			
							$keyword=$this->Group->find('first',array('conditions' => array('Group.id'=>$groupId['ContactGroup']['group_id'])));
								app::import('Model','Group');
								$this->Group = new Group();
								$groupArr1['id'] = $keyword['Group']['id'];
								$groupArr1['totalsubscriber'] = $keyword['Group']['totalsubscriber']-1;
								$this->Group->save($groupArr1); 
							}	
							app::import('Model','ContactGroup');
							$this->ContactGroup = new ContactGroup();
							$contactsms = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr)));
							$this->ContactGroup->deleteAll(array('ContactGroup.contact_id' => $contactArr));
							foreach($this->data['Group']['id'] as $groupIds){
								$group_id = $groupIds;
								app::import('Model','Group');		
								$this->Group = new Group();		
								$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));	
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
								if(empty($subscriber11)){
									$message= $keywordname['Group']['system_message'].' '.$keywordname['Group']['auto_message'];
									if($newsubscriber_details['Contact']['phone_number'] != $this->data['Contact']['phone_number']){
										$this->Slooce->mt($users['User']['api_url'],$users['User']['partnerid'],$users['User']['partnerpassword'],$phone_number,$users['User']['keyword'],$message);
										$update_user['User']['id'] = $users['User']['id'];
										$update_user['User']['sms_balance'] = $users['User']['sms_balance']-1;
										$this->User->save($update_user);
									}
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$this->data['ContactGroup']['user_id'] = $this->Session->read('User.id');
									$this->data['ContactGroup']['group_id'] = $group_id;
									$this->data['ContactGroup']['group_subscribers'] = $keywordname['Group']['keyword'];
									$this->data['ContactGroup']['contact_id'] = $contactArr;
									$this->data['ContactGroup']['subscribed_by_sms'] = $contactsms['ContactGroup']['subscribed_by_sms'];
									$this->data['ContactGroup']['do_not_call'] = $contactsms['ContactGroup']['do_not_call'];
									$this->data['ContactGroup']['created'] = $contactsms['ContactGroup']['created'];
									$this->ContactGroup->save($this->data);
									app::import('Model','Group');		
									$this->Group = new Group();
									$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));	
									$groupArr['id'] = $groups['Group']['id'];
									$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
									$this->Group->save($groupArr);
									$this->Session->setFlash(__('Contacts have been saved', true));
								}
							}
						}
						if ($source == 1){
							$this->redirect(array('controller' => 'groups','action' => 'index'));
						}else{
							$this->redirect(array('action'=>'index')); 
						}
					}else{
						$this->Session->setFlash(__('The specified phone number is not valid', true));
					}
				}else{
					$this->Session->setFlash(__('User not found', true));
				}
				if ($source == 1){
					$this->redirect(array('controller' => 'groups','action' => 'index'));
				}else{
					$this->redirect(array('action'=>'index')); 
				}
			}else{
				app::import('Model','ContactGroup');
				$this->ContactGroup = new ContactGroup();
				$newsubscriber1 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Contact']['phone_number'])));
				if(empty($newsubscriber1)){
					$phoneno1=$this->data['Contact']['phone_number'];
					$phone  = str_replace('+','',$phoneno1);
					$phone_number  = str_replace('-','',$phone);
					$contact['user_id'] = $this->Session->read('User.id');	
					$contact['name']=$this->data['Contact']['name'];	
					$contact['phone_number']=$phone_number;		
					$contact['email']=$this->data['Contact']['email'];
					$contact['birthday']=$this->data['Contact']['birthday'];	
					$contact['color']=$this->choosecolor();
					$contact['id']=$id;
					$contact['created']=date('Y-m-d H:i:s');					
					$this->Contact->save($contact);		
					$contactArr=$this->Contact->id;		
				}else{			
					$phoneno1=$this->data['Contact']['phone_number'];
					$phone  = str_replace('+','',$phoneno1);
					$phone_number  = str_replace('-','',$phone);	
					$contact['name']=$this->data['Contact']['name'];	
					$contact['phone_number']=$phone_number;		
					$contact['email']=$this->data['Contact']['email'];
					$contact['birthday']=$this->data['Contact']['birthday'];	
					$contact['color']=$this->choosecolor();
					$contact['id']=$id;			
					$contact['created']=date('Y-m-d H:i:s');			
					$this->Contact->save($contact);		
					$contactArr = $newsubscriber1['Contact']['id'];	
				}		
				if($contactArr!=''){
					app::import('Model','ContactGroup');		
					$this->ContactGroup = new ContactGroup();
					$subscriber12 = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.contact_id'=> $contactArr)));
					foreach($subscriber12 as $groupId){		
						app::import('Model','Group');		
						$this->Group = new Group();			
						$keyword=$this->Group->find('first',array('conditions' => array('Group.id'=>$groupId['ContactGroup']['group_id'])));
						app::import('Model','Group');
						$this->Group = new Group();
						$groupArr1['id'] = $keyword['Group']['id'];
						$groupArr1['totalsubscriber'] = $keyword['Group']['totalsubscriber']-1;
						$this->Group->save($groupArr1); 
					}	
					app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					$contactsms = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr)));
					$this->ContactGroup->deleteAll(array('ContactGroup.contact_id' => $contactArr));
					foreach($this->data['Group']['id'] as $groupIds){
						$group_id = $groupIds;
						app::import('Model','Group');		
						$this->Group = new Group();		
						$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));	
						app::import('Model','ContactGroup');
						$this->ContactGroup = new ContactGroup();
						$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
						if(empty($subscriber11)){
							app::import('Model','ContactGroup');
							$this->ContactGroup = new ContactGroup();
							$this->data['ContactGroup']['user_id'] = $this->Session->read('User.id');
							$this->data['ContactGroup']['group_id'] = $group_id;
							$this->data['ContactGroup']['group_subscribers'] = $keywordname['Group']['keyword'];
							$this->data['ContactGroup']['contact_id'] = $contactArr;
							$this->data['ContactGroup']['subscribed_by_sms'] = $contactsms['ContactGroup']['subscribed_by_sms'];
							$this->data['ContactGroup']['do_not_call'] = $contactsms['ContactGroup']['do_not_call'];
							$this->data['ContactGroup']['created'] = $contactsms['ContactGroup']['created'];
							$this->ContactGroup->save($this->data);
							app::import('Model','Group');		
							$this->Group = new Group();
							$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));	
							$groupArr['id'] = $groups['Group']['id'];
							$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
							$this->Group->save($groupArr);
							$this->Session->setFlash(__('Contacts have been saved', true));
						}
					}
				}
				if ($source == 1){
					$this->redirect(array('controller' => 'groups','action' => 'index'));
				}else{
					$this->redirect(array('action'=>'index')); 
				} 
			}			
		}	
		if (empty($this->data)) {
			$this->data = $this->Contact->read(null, $id);
		}
	}
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for contact', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Contact->delete($id)) {
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$contacts_members =$this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$id)));
			foreach($contacts_members as $contacts_member){
				$group_id=$contacts_member['ContactGroup']['group_id'];
				$un_subscribers=$contacts_member['ContactGroup']['un_subscribers'];
				if($un_subscribers==0){
					app::import('Model','Group');
					$this->Group = new Group();
					$Group =$this->Group->find('first', array('conditions' => array('Group.id'=>$group_id)));
					$this->data['Group']['id'] = $group_id;
					$this->data['Group']['totalsubscriber'] = $Group['Group']['totalsubscriber']-1;
					$this->Group->save($this->data);
				}
			}	
			$this->ContactGroup->deleteAll(array('ContactGroup.contact_id' => $id));
			$this->Session->setFlash(__('Contact deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Contact was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}	
	function send_sms($id=null){	
		$this->layout = 'popup';
		$this->set('phoneno',$id);
		if(!empty($this->data)){
			$this->Contact->set($this->data);
			$this->Contact->validationSet = 'sendMsg';
			if ($this->Contact->validates()){
				$this->redirect(array('action' => 'index'));
			}			
		}
		$userDetails = $this->getLoggedUserDetails();
		$this->Session->write('User.sms_balance',$userDetails['User']['sms_balance']);
		$this->Session->write('User.assigned_number',$userDetails['User']['assigned_number']); 
		$this->Session->write('User.active',$userDetails['User']['active']); 		 
		if($userDetails['User']['assigned_number']!=0){
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$user_id=$this->Session->read('User.id');
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('Contact.name' => 'asc')));
			$contactnames[0]='';	
			foreach($contactsvars  as $contactsvar){
				if($contactsvar['Contact']['name']!=''){
					$contactnames[$contactsvar['Contact']['phone_number']]=$contactsvar['Contact']['name'];
					$contact_name=$contactsvar['Contact']['name'];
				}
			}
			$this->set('contacts',$contactnames);	
			$this->set('contact_name',$contact_name);
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
			$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
			$this->set('numbers_mms',$numbers_mms);
			$this->set('numbers_sms',$numbers_sms);
			$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
			$this->set('users',$users);
			$this->set('nonactiveuser',0);
		}else{
			$this->set('nonactiveuser',1);
		}
	}
	function nexmo_send_sms($id=null){
		$this->layout = 'popup';
		$this->set('phoneno',$id);
		if(!empty($this->data)){
			$this->Contact->set($this->data);
			$this->Contact->validationSet = 'sendMsg';
			if ($this->Contact->validates()){	
			$this->redirect(array('action' => 'index'));
			}	
		}
		$userDetails = $this->getLoggedUserDetails();
		$this->Session->write('User.sms_balance',$userDetails['User']['sms_balance']);
		$this->Session->write('User.assigned_number',$userDetails['User']['assigned_number']); 
		$this->Session->write('User.active',$userDetails['User']['active']); 		 
		if($userDetails['User']['assigned_number']!=0){
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$user_id=$this->Session->read('User.id');
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('Contact.name' => 'asc')));
			$contactnames[0]='';
			foreach($contactsvars  as $contactsvar){
				if($contactsvar['Contact']['name']!=''){
					$contactnames[$contactsvar['Contact']['phone_number']]=$contactsvar['Contact']['name'];
				}
			}
			$this->set('contacts',$contactnames);
			$this->set('nonactiveuser',0);
		}else{
			$this->set('nonactiveuser',1);
		}
	}
	function plivo_send_sms($id=null){
		$this->layout = 'popup';
		$this->set('phoneno',$id);
		if(!empty($this->data)){
			$this->Contact->set($this->data);
			$this->Contact->validationSet = 'sendMsg';
			if ($this->Contact->validates()){	
			$this->redirect(array('action' => 'index'));
			}	
		}
		$userDetails = $this->getLoggedUserDetails();
		$this->Session->write('User.sms_balance',$userDetails['User']['sms_balance']);
		$this->Session->write('User.assigned_number',$userDetails['User']['assigned_number']); 
		$this->Session->write('User.active',$userDetails['User']['active']); 		 
		if($userDetails['User']['assigned_number']!=0){
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$user_id=$this->Session->read('User.id');
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('Contact.name' => 'asc')));
			$contactnames[0]='';
			foreach($contactsvars  as $contactsvar){
				if($contactsvar['Contact']['name']!=''){
					$contactnames[$contactsvar['Contact']['phone_number']]=$contactsvar['Contact']['name'];
				}
			}
			$this->set('contacts',$contactnames);
			$this->set('nonactiveuser',0);
		}else{
			$this->set('nonactiveuser',1);
		}
	}
	function slooce_send_sms($id=null){
		$this->layout = 'popup';
		$this->set('phoneno',$id);
		if(!empty($this->data)){
			$this->Contact->set($this->data);
			$this->Contact->validationSet = 'sendMsg';
			if ($this->Contact->validates()){	
				$this->redirect(array('action' => 'index'));
			}	
		}
		$userDetails = $this->getLoggedUserDetails();
		$this->Session->write('User.sms_balance',$userDetails['User']['sms_balance']);
		$this->Session->write('User.assigned_number',$userDetails['User']['assigned_number']); 
		$this->Session->write('User.active',$userDetails['User']['active']); 		 
		if($userDetails['User']['assigned_number']!=0){
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$user_id=$this->Session->read('User.id');
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('Contact.name' => 'asc')));
			$contactnames[0]='';
			foreach($contactsvars  as $contactsvar){
				if($contactsvar['Contact']['name']!=''){
					$contactnames[$contactsvar['Contact']['phone_number']]=$contactsvar['Contact']['name'];
				}
			}
			$this->set('contacts',$contactnames);
			$this->set('nonactiveuser',0);
		}else{
			$this->set('nonactiveuser',1);
		}
	}
	function show_next(){
		//$this->checkUserSession();	
		$this->layout= 'admin_new_layout';
		$filename=$this->data['contact']['name'];
		$type=$this->data['contact']['type'];
		$tmp_name=$this->data['contact']['tmp_name'];
		$name=$this->data['contact']['tmp_name'];
		$handle = fopen($name, 'r');
		$header = fgetcsv($handle);		
		$ext = substr(strrchr($this->data['contact']['name'],'.'),1);
		if($ext == 'csv'){
		}else{
			$this->Session->setFlash(__('Please Use Only CSV File', true));
			$this->redirect(array('controller' => 'contacts','action' => 'upload'));
		}
		move_uploaded_file($tmp_name,"csvfile/".time().$filename);
		app::import('Model','User');
		$this->User = new User();
		$user_id=$this->Session->read('User.id');
		$this->data['User']['id'] =  $user_id;
		$this->data['User']['file_name'] = time().$filename;   
		$this->User->save($this->data);
		fclose($handle);
		if($this->data['contact']['header']==0){
			$this->redirect(array('controller' => 'contacts','action' => 'importcontact'));
		}
		$this->set('header',$header);
		$user_id=$this->Session->read('User.id');
        app::import('Model','Group');
		$this->Group = new Group();
		$Group = $this->Group->find('list',array('fields' => array('Group.group_name'),'conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.id' => 'asc')));
		$this->set('Group',$Group);	
	}
	function importcontact(){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
        app::import('Model','Group');
		$this->Group = new Group();
		$Group = $this->Group->find('list',array('fields' => array('Group.group_name'),'conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
		$this->set('Group',$Group);
		app::import('Model','User');
		$this->User = new User();
		$files = $this->User->find('first',array('conditions' => array('User.id'=> $user_id)));
		$file_path = $files['User']['file_name'];
	    $path =  'csvfile/'.$file_path;
	    $handle = fopen($path, "r");
		if(!empty($this->data)){
			if(API_TYPE==2){
				while (($row = fgetcsv($handle)) !== FALSE) {
					app::import('Model','Contact');
					$this->Contact = new Contact();	
					$Contact['user_id'] = $user_id;
					$var=$row[0];
					if($var >0){
						$Contact['name'] ='';
						$Contact['phone_number'] = $row[0];
						$Contact['color'] = $this->choosecolor();
					}else{
						if(empty($var)){
							$Contact['name'] ='';
						}else{
							$Contact['name'] = $row[0];
						}
						$phone  = str_replace('+','',$row[1]);
						$row[1] = str_replace('-','',$phone);
						$Contact['phone_number'] = $row[1];
						$Contact['color'] = $this->choosecolor();
					}
					$firstSubscriber = $this->Contact->find('first',array('conditions' => array('Contact.user_id'=> $user_id,'Contact.phone_number'=>$Contact['phone_number'])));
					if(empty($firstSubscriber)){
						$response = $this->Slooce->supported($files['User']['api_url'],$files['User']['partnerid'],$files['User']['partnerpassword'],$Contact['phone_number'],$files['User']['keyword']);
					}else{
						$response='supported';
					}
					if($response=='supported'){
						if(empty($firstSubscriber)){
							$this->Contact->save($Contact);
							$contactArr = $this->Contact->getLastInsertId();
							//$contactArr = 0;
							$phone_number = $Contact['phone_number'];
						}else{	
							$contactArr = $firstSubscriber['Contact']['id'];
							$phone_number = $firstSubscriber['Contact']['phone_number'];
						}
						if($Contact['phone_number']!=''){
							foreach($this->data['Group']['id'] as $group_id){
								app::import('Model','Group');	
								$this->Group = new Group();
								$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$unsubscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.un_subscribers'=>1)));
								if(empty($unsubscriber)){
									$subscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
									if(empty($subscriber)){
										$message= $keywordname['Group']['system_message'].' '.$keywordname['Group']['auto_message'];
										//if($phone_number != $Contact['phone_number']){
											$this->Slooce->mt($files['User']['api_url'],$files['User']['partnerid'],$files['User']['partnerpassword'],$Contact['phone_number'],$files['User']['keyword'],$message);
											
										$update_user['User']['id'] = $users['User']['id'];
										$update_user['User']['sms_balance'] = $users['User']['sms_balance']-1;
										$this->User->save($update_user);
										//}
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['user_id'] = $user_id;
										$this->data['ContactGroup']['group_id'] = $group_id;
										$this->data['ContactGroup']['group_subscribers'] = $keywordname['Group']['keyword'];
										$this->data['ContactGroup']['contact_id'] = $contactArr;
										$this->ContactGroup->save($this->data);
										app::import('Model','Group');
										$this->Group = new Group();
										$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
										$groupArr['id'] = $groups['Group']['id'];
										$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
										$this->Group->save($groupArr);
										$this->Session->setFlash(__('Contacts have been uploaded', true));
									}else{
										$this->Session->setFlash(__('This contact already exists for this group', true));
									}
								}
							}
						}
					}else{
						$this->Session->setFlash(__('The specified phone number is not valid', true));
					}
				}
			}else{
				while (($row = fgetcsv($handle)) !== FALSE) {
					app::import('Model','Contact');
					$this->Contact = new Contact();	
					$Contact['user_id'] = $user_id;
					$var=$row[0];
					if($var >0){
						$Contact['name'] ='';
						$Contact['phone_number'] = $row[0];
						$Contact['color'] = $this->choosecolor();
					}else{
						if(empty($var)){
							$Contact['name'] ='';
						}else{
							$Contact['name'] = $row[0];
						}
						$phone  = str_replace('+','',$row[1]);
						$row[1] = str_replace('-','',$phone);
						$Contact['phone_number'] = $row[1];
						$Contact['color'] = $this->choosecolor();
					}
					$firstSubscriber = $this->Contact->find('first',array('conditions' => array('Contact.user_id'=> $user_id,'Contact.phone_number'=>$Contact['phone_number'])));
					if(empty($firstSubscriber)){
						$this->Contact->save($Contact);
						$contactArr = $this->Contact->getLastInsertId();
					}else{	
						$contactArr = $firstSubscriber['Contact']['id'];
					}
					if($Contact['phone_number']!=''){
						foreach($this->data['Group']['id'] as $group_id){
							app::import('Model','Group');	
							$this->Group = new Group();
							$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
							app::import('Model','ContactGroup');
							$this->ContactGroup = new ContactGroup();
							$unsubscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.un_subscribers'=>1)));
							if(empty($unsubscriber)){
								$subscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
								if(empty($subscriber)){
									app::import('Model','ContactGroup');
									$this->ContactGroup = new ContactGroup();
									$this->data['ContactGroup']['user_id'] = $user_id;
									$this->data['ContactGroup']['group_id'] = $group_id;
									$this->data['ContactGroup']['group_subscribers'] = $keywordname['Group']['keyword'];
									$this->data['ContactGroup']['contact_id'] = $contactArr;
									$this->ContactGroup->save($this->data);
									app::import('Model','Group');
									$this->Group = new Group();
									$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
									$groupArr['id'] = $groups['Group']['id'];
									$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
									$this->Group->save($groupArr);
									$this->Session->setFlash(__('Contacts have been uploaded', true));
								}else{
									$this->Session->setFlash(__('This contact already exists for this group', true));
								}
							}
						}
					}
				}
			}
			
			$sitename=str_replace(' ','',SITENAME);
			$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
			$username = $users["User"]["username"];	
			$first_name = $users["User"]["first_name"];	
			$last_name = $users["User"]["last_name"];	
			$email = $users["User"]["email"];	
			$phone = $users["User"]["phone"];	
			$subject="Import Contacts Alert - ".SITENAME;	
			$this->Email->to = SUPPORT_EMAIL;
			$this->Email->subject = $subject;
			$this->Email->from = $sitename;
			$this->Email->template = 'import_contacts_alert';
			$this->Email->sendAs = 'html';
			$this->Email->Controller->set('username', $username);
			$this->Email->Controller->set('firstname', $first_name);
			$this->Email->Controller->set('lastname', $last_name);
			$this->Email->Controller->set('email', $email);
			$this->Email->Controller->set('phone', $phone);
			$this->Email->send();
			$this->redirect(array('controller' => 'contacts','action' => 'index'));
		}
	}
	function upload(){
		$this->layout= 'admin_new_layout';
		//$this->checkUserSession();
		//$this->layout="default";
	}
	function check_csvdata(){
		$user_id=$this->Session->read('User.id');
		app::import('Model','User');
		$this->User = new User();
		$files = $this->User->find('first',array('conditions' => array('User.id'=> $user_id)));	
		$file_path = $files['User']['file_name'];
		$path =  'csvfile/'.$file_path;
		$handle = fopen($path, "r");
		$header = fgetcsv($handle);
		if($this->data['Group']['id'] == '' && $this->data['Contact']['name'] == '' && $this->data['Contact']['name'] == ''){
			$this->Session->setFlash(__('Contact are  not uploaded', true));
			$this->redirect(array('controller' => 'contacts','action' => 'index'));
		}
		if(!empty($this->data)) {
			if(API_TYPE==2){
				while (($row = fgetcsv($handle)) !== FALSE) {
					app::import('Model','Contact');
					$this->Contact = new Contact();	
					$Contact['user_id'] = $user_id;
					$var=$row[0];
					if($var >0){
						$Contact['name'] ='';
						$Contact['phone_number'] = $row[0];
						$Contact['color'] = $this->choosecolor();
					}else{
						if(empty($var)){
							$Contact['name'] ='';
						}else{
							$Contact['name'] = $row[0];
						}
						$phone  = str_replace('+','',$row[1]);
						$row[1] = str_replace('-','',$phone);
						$Contact['phone_number'] = $row[1];
						$Contact['color'] = $this->choosecolor();
					}
					$firstSubscriber = $this->Contact->find('first',array('conditions' => array('Contact.user_id'=> $user_id,'Contact.phone_number'=>$Contact['phone_number'])));
					if(empty($firstSubscriber)){
						$response = $this->Slooce->supported($files['User']['api_url'],$files['User']['partnerid'],$files['User']['partnerpassword'],$Contact['phone_number'],$files['User']['keyword']);
					}else{
						$response='supported';
					}
					if($response=='supported'){
						if(empty($firstSubscriber)){
							$this->Contact->save($Contact);
							$contactArr = $this->Contact->getLastInsertId();
							$phone_number = $Contact['phone_number'];
						}else{	
							$contactArr = $firstSubscriber['Contact']['id'];
							$phone_number = $firstSubscriber['Contact']['phone_number'];
						}
						if($Contact['phone_number']!=''){
							foreach($this->data['Group']['id'] as $group_id){
								app::import('Model','Group');	
								$this->Group = new Group();
								$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$unsubscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.un_subscribers'=>1)));
								if(empty($unsubscriber)){
									$subscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
									if(empty($subscriber)){
										$message= $keywordname['Group']['system_message'].' '.$keywordname['Group']['auto_message'];
										if($phone_number != $Contact['phone_number']){
											$this->Slooce->mt($files['User']['api_url'],$files['User']['partnerid'],$files['User']['partnerpassword'],$Contact['phone_number'],$files['User']['keyword'],$message);
										}
										app::import('Model','ContactGroup');
										$this->ContactGroup = new ContactGroup();
										$this->data['ContactGroup']['user_id'] = $user_id;
										$this->data['ContactGroup']['group_id'] = $group_id;
										$this->data['ContactGroup']['group_subscribers'] = $keywordname['Group']['keyword'];
										$this->data['ContactGroup']['contact_id'] = $contactArr;
										$this->ContactGroup->save($this->data);
										app::import('Model','Group');
										$this->Group = new Group();
										$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
										$groupArr['id'] = $groups['Group']['id'];
										$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
										$this->Group->save($groupArr);
										$this->Session->setFlash(__('Contacts have been uploaded', true));
									}else{
										$this->Session->setFlash(__('This contact already exists for this group', true));
									}
								}
							}
						}
					}else{
						$this->Session->setFlash(__('The specified phone number is not valid', true));
					}
				}
			}else{
				while (($row = fgetcsv($handle)) !== FALSE) {
					app::import('Model','Contact');
					$this->Contact = new Contact();	
					$Contact['user_id'] = $user_id;
					$var=$row[0];
					if(empty($var)){
						$Contact['name']=' ';
					}else{
						$Contact['name'] = $row[$this->data['Contact']['name']];
					}	
					$phoneno=$row[$this->data['Contact']['phone']];
					$phone  = str_replace('+','',$phoneno);
					$phone_number = str_replace('-','',$phone);
					$Contact['phone_number'] = $phone_number;
					$Contact['color'] = $this->choosecolor();
					$firstSubscriber = $this->Contact->find('first',array('conditions' => array('Contact.user_id'=> $user_id,'Contact.phone_number'=>$Contact['phone_number'])));
					if(empty($firstSubscriber)){
						$this->Contact->save($Contact);
						$contactArr = $this->Contact->getLastInsertId();
					}else{
						$contactArr = $firstSubscriber['Contact']['id'];
					}
					if($Contact['phone_number']!=''){
						foreach($this->data['Group']['id'] as $group_id){
							app::import('Model','Group');
							$this->Group = new Group();		
							$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
							app::import('Model','ContactGroup');
							$this->ContactGroup = new ContactGroup();
							$subscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$group_id)));
							if(empty($subscriber)){
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								$this->data['ContactGroup']['user_id'] = $user_id;
								$this->data['ContactGroup']['group_id'] = $group_id;
								$this->data['ContactGroup']['group_subscribers'] = $keywordname['Group']['keyword'];
								$this->data['ContactGroup']['contact_id'] = $contactArr;
								$this->ContactGroup->save($this->data);
								app::import('Model','Group');
								$this->Group = new Group();
								$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
								$groupArr['id'] = $groups['Group']['id'];
								$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
								$this->Group->save($groupArr);
								$this->Session->setFlash(__('Contacts have been uploaded', true));
							}else{
								$this->Session->setFlash(__('This contact already exists for this group', true));
							}
						
						}
					}		
				}
			}
			$this->redirect(array('controller' => 'contacts','action' => 'index'));
		}
	}
	function export(){
		$this->autoRender = false;
		$contacts = $this->Session->read('contacts');
		if(empty($contacts)){
			$user_id=$this->Session->read('User.id');
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$contacts = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('ContactGroup.created' => 'desc')));
		}
		$filename = "contacts".date("Y.m.d").".csv";
		$csv_file = fopen('php://output', 'w');
		header('Content-type: application/csv');
		//header('Content-Type: text/html');
		header('Content-Disposition: attachment; filename="'.$filename.'"'); 
		$header_row = array("Subscriber Name","Email","Birthday","Phone","Carrier","Location","Phone Country","Line Type","Group Name", "Sub", "Source");
		fputcsv($csv_file,$header_row,',','"');
		foreach($contacts as $result){
			if($result['ContactGroup']['un_subscribers']==0){
				$type="Subscriber";
			}else{
				$type="Un-subscriber";
			}
			if($result['ContactGroup']['subscribed_by_sms']==0){
				$subscribed_by_sms="Import";
			}else if($result['ContactGroup']['subscribed_by_sms']==1){
				$subscribed_by_sms="SMS";
					}else if($result['ContactGroup']['subscribed_by_sms']==3){
				$subscribed_by_sms="Kiosk";
			}else{		
			$subscribed_by_sms="Widget";
			}
			$row = array(
			$result['Contact']['name'],
			$result['Contact']['email'],
			$result['Contact']['birthday'],
			$result['Contact']['phone_number'],
			$result['Contact']['carrier'],
			$result['Contact']['location'],
			$result['Contact']['phone_country'],
			$result['Contact']['line_type'],
			$result['Group']['group_name'],
			$type,
			$subscribed_by_sms);	
			fputcsv($csv_file,$row,',','"');
		}
		fclose($csv_file);
	}
	
	function stoppartner($group_id,$contact_id){
		$this->autoRender = false;
		$user_id=$this->Session->read('User.id');
		app::import('Model','User');
		$this->User = new User();
		$user = $this->User->find('first',array('conditions' => array('User.id'=> $user_id)));	
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		$subscriber = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contact_id,'ContactGroup.group_id'=>$group_id)));
		if(!empty($subscriber)){
			$this->data['ContactGroup']['id']=$subscriber['ContactGroup']['id'];
			$this->data['ContactGroup']['un_subscribers']=1;
			if($this->ContactGroup->save($this->data)){
				$response = $this->Slooce->stoppartener($user['User']['api_url'],$user['User']['partnerid'],$user['User']['partnerpassword'],$subscriber['Contact']['phone_number'],$user['User']['keyword']);
				if($response=='ok'){
					$companyname = $user['User']['company_name'];
					$this->data['User']['id']=$user_id;
					$this->data['User']['sms_balance']=$user['User']['sms_balance']-1;
					$this->User->save($this->data);
					app::import('Model','Group');
					$this->Group = new Group();
					$this->data['Group']['id']=$group_id;
					$this->data['Group']['totalsubscriber']=$subscriber ['Group']['totalsubscriber']-1;
					$this->Group->save($this->data);
					$userphone = $this->format_phone($user['User']['phone']);
					if(!empty($companyname)){
						$message=$companyname.": You have opted out successfully. For help call ".$userphone.". No more messages will be sent";
					}else{
						$message="You have opted out successfully. For help call ".$userphone.". No more messages will be sent";
					}
					$response = $this->Slooce->mt($user['User']['api_url'],$user['User']['partnerid'],$user['User']['partnerpassword'],$subscriber['Contact']['phone_number'],$user['User']['keyword'],$message);
					$this->smsmail($user['User']['id']);
					$this->Session->setFlash(__('Contact has been unsubscribed.', true));
				}else{
				   $this->Session->setFlash(__('Contact was not unsubscribed. Please try again.', true));
				}
			}else{
				$this->Session->setFlash(__('Contact was not unsubscribed. Please try again.', true));
			}
		}else{
			$this->Session->setFlash(__('Contact is not found.', true));
		}
		$this->redirect(array('controller' => 'contacts','action' => 'index'));
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
	
	function format_phone($phone){
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
	function unsubscribers(){
		$this->layout= 'admin_new_layout';
		$this->Contact->recursive = 0;
		$user_id=$this->Session->read('User.id');
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		$this->paginate = array('conditions' => array('ContactGroup.user_id'=>$user_id,'ContactGroup.un_subscribers'=>1),'order' =>array('ContactGroup.created' => 'desc'));
		$data = $this->paginate('ContactGroup');
		$this->set('contacts', $data);
		$Subscribercountfind = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id' =>$user_id)));
		$this->Session->write('contacts', $Subscribercountfind);
	}

	function exportunsubs(){
		$this->autoRender = false;
		$user_id=$this->Session->read('User.id');
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		$contacts = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id,'ContactGroup.un_subscribers'=>1),'order' =>array('ContactGroup.created' => 'desc')));
		$filename = "unsubscribers".date("Y.m.d").".csv";
		$csv_file = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'"'); 
		$header_row = array("Name","Number","Group","Source","Unsubscribed Date");
		fputcsv($csv_file,$header_row,',','"');
		foreach($contacts as $result){
			if($result['ContactGroup']['subscribed_by_sms']==0){
				$subscribed_by_sms="Import";
			}else if($result['ContactGroup']['subscribed_by_sms']==1){
				$subscribed_by_sms="SMS";
			}else if($result['ContactGroup']['subscribed_by_sms']==3){
				$subscribed_by_sms="Kiosk";
			}else{		
				$subscribed_by_sms="Widget";
			}
			$row = array(
			$result['Contact']['name'],
			$result['Contact']['phone_number'],
			$result['Group']['group_name'],
			$subscribed_by_sms,
			$result['ContactGroup']['created']);	
			fputcsv($csv_file,$row,',','"');
		}
		fclose($csv_file);
	}
}	