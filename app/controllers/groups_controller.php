<?php
class GroupsController extends AppController {
	var $name ='Groups';
	var $components = array('Email','Twilio','Qr','Qrsms','Nexmo','Slooce','Plivo');
    //var $layout="default";
	function index(){

		$this->layout= 'admin_new_layout';
		$this->Group->recursive = 0;
		$user_id=$this->Session->read('User.id');
		app::import('Model','User');
		$this->User = new User();
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.mms'=>1)));
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();		  
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);		
		$this->set('users',$users);
		$this->paginate = array(
		'conditions' => array('Group.user_id' =>$user_id),'order' =>array('Group.id' => 'asc')
		);
		$data = $this->paginate('Group');
		$this->set('groups', $data);

		
	}
	
	
	
	function add(){
		$this->layout= 'admin_new_layout';
		
		$user_id=$this->Session->read('User.id');
		$this->set('user_id',$user_id);
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		app::import('Model','User');
		$this->User = new User();
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id))); 
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$this->set('users',$users);
		if(!empty($this->data)){
		/*  echo'<pre>';
		print_r($this->data);
		die();  */ 
		   app::import('Model','Contest');
		   $this->Contest = new Contest();
				
	           app::import('Model','Smsloyalty');
		   $this->Smsloyalty = new Smsloyalty();
			
		   if(API_TYPE !=2){
			$keywords = $this->Group->find('first',array('conditions'=>array('Group.keyword '=>$this->data['Group']['keyword'],'Group.user_id '=>$user_id)));
		   }else{
			
			$keywords = $this->Group->find('first',array('conditions'=>array('Group.keyword '=>$this->data['Group']['keyword'])));
						
		   }
			
			$contestkeyword = $this->Contest->find('first',array('conditions'=>array('Contest.keyword '=>$this->data['Group']['keyword'],'Contest.user_id'=>$user_id)));

				if(!empty($contestkeyword)){ 
     					$this->Session->setFlash(__('Keyword is already registered for a contest. Please choose another keyword.', true));
     					$this->redirect(array('controller' =>'groups', 'action'=>'add')); 
				}
                   
				$loyaltykeyword = $this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.codestatus '=>$this->data['Group']['keyword'],'Smsloyalty.user_id'=>$user_id)));

				if(!empty($loyaltykeyword)){ 
     					$this->Session->setFlash(__('Keyword is already registered for another loyalty program. Please choose another keyword.', true));
     					$this->redirect(array('controller' =>'groups', 'action'=>'add')); 
				}
				
			if(empty($keywords)){ 
				$msgtype=$this->data['Group']['msg_type'];
				/* if($msgtype==0){
					$this->data['Group']['text_message']=$this->data['Group']['text_message'];
					$this->data['Group']['audio_name']= '';
					$this->data['Group']['message_type']=0;
				}
				else if($msgtype==1 && isset($this->data['Group']['mp3']['name'])){
					$name = $this->data['Group']['mp3']['name'];
				
					$allowedExts = array("mp3","wav");
					$temp = explode(".", $this->data['Group']['mp3']['name']);
					$extension = end($temp);

					if ((($this->data['Group']['mp3']['type'] == "audio/mp3")
					|| ($this->data['Group']['mp3']['type'] == "audio/wav"))
					&& in_array($extension, $allowedExts)){
				
						$filename= str_replace(' ','_',time().$name);
							
						move_uploaded_file($this->data['Group']['mp3']['tmp_name'],"mp3/" . $filename);
						
						$this->data['Group']['audio_name']=$filename;
						$this->data['Group']['text_message']= '';
						$this->data['Group']['message_type']=1;
						}
						else{
							$this->Session->setFlash(__ ('Please select the audio like mp3 and wav files.',true));
						}
						
					
				} */
				
				
				
				
				//$group['Group']['user_id']=$user_id;
				
				$group['Group']['user_id']=$this->data['Group']['user_id'];
				$group['Group']['group_type']=$this->data['Group']['group_type'];
				$group['Group']['group_name']=$this->data['Group']['group_name'];
				$group['Group']['keyword']=$this->data['Group']['keyword'];
				if($this->data['Group']['msg']!=''){
					$group['Group']['system_message']=$this->data['Group']['msg'];
				}
				if($this->data['Group']['msg2']!=''){
					$group['Group']['system_message']=$this->data['Group']['msg2'];
				}
				if($this->data['Group']['msg1']!=''){
					$group['Group']['system_message']=$this->data['Group']['msg1'];
				}
				$group['Group']['auto_message']=$this->data['Group']['auto_message'];
				if(!empty($this->data['Message']['msg_type'])){
					$group['Group']['sms_type']=$this->data['Message']['msg_type'];
				}else{
					$group['Group']['sms_type']=1;
				}
				if(!empty($this->data['Message']['bithday_enable'])){
					$group['Group']['bithday_enable']=$this->data['Message']['bithday_enable'];
				}else{
					$group['Group']['bithday_enable']=0;
				}
				if($this->data['Group']['image'][0]['name']!=''){
					$image_arr='';
					foreach($this->data['Group']['image'] as $value){
						$image=str_replace(' ','_',mt_rand().$value["name"]);	
						move_uploaded_file($value['tmp_name'],"mms/".$image);
						if($image_arr!=''){
							$image_arr = $image_arr.','.SITE_URL.'/mms/'.$image;
						}else{
							$image_arr =SITE_URL.'/mms/'.$image;
						}
					}
					$group['Group']['image_url']=$image_arr;
				}
				$group['Group']['active'] = 1;

                                if(isset($this->data['Group']['notify_signup'])){
					$group['Group']['notify_signup'] = 1;
				}else{
					$group['Group']['notify_signup'] = 0;
				}

                                $group['Group']['mobile_number_input'] = $this->data['Group']['mobile_number_input'];

                                $group_type = $this->data['Group']['group_type'];
                                if ($group_type == '2'){
                                    $group['Group']['property_address'] = $this->data['Group']['propertyaddress'];
                                    $group['Group']['property_price'] = $this->data['Group']['propertyprice'];
                                    $group['Group']['property_bed'] = $this->data['Group']['property_bed'];
                                    $group['Group']['property_bath'] = $this->data['Group']['property_bath'];
                                    $group['Group']['property_description'] = $this->data['Group']['propertydesc'];
                                    $group['Group']['property_url'] = $this->data['Group']['propertyurl'];
                                
                                }elseif ($group_type == '3'){
                                    $group['Group']['vehicle_year'] = $this->data['Group']['vehicleyear'];
                                    $group['Group']['vehicle_make'] = $this->data['Group']['vehiclemake'];
                                    $group['Group']['vehicle_model'] = $this->data['Group']['vehiclemodel'];
                                    $group['Group']['vehicle_mileage'] = $this->data['Group']['vehiclemileage'];
                                    $group['Group']['vehicle_price'] = $this->data['Group']['vehicleprice'];
                                    $group['Group']['vehicle_description'] = $this->data['Group']['vehicledesc'];
                                    $group['Group']['vehicle_url'] = $this->data['Group']['vehicleurl'];

                                }
				
				if($this->Group->save($group)){					
				   $this->Session->setFlash(__('The group has been saved', true)); 
				   $this->redirect(array('action' => 'index'));
				}
			  //$this->redirect(array('controller' =>'users', 'action'=>'dashboard'));
			}else {
				$this->Session->setFlash(__('Keyword is already registered. please choose another keyword.', true)); 
			}
		}
	
	}
	
	
	
	function delete($id = null , $message_type = null) { 
  		//$this->layout="default";
		if ($this->Group->delete($id)) {
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$this->ContactGroup->deleteAll(array('ContactGroup.group_id'=>$id));
			app::import('Model','Birthday');
			$this->Birthday = new Birthday();			
			$this->Birthday ->deleteAll(array('Birthday.group_id'=>$id));
			app::import('Model','VoiceMessage');
			$this->VoiceMessage = new VoiceMessage();		
			$this->VoiceMessage ->deleteAll(array('VoiceMessage.group_id'=>$id));
			app::import('Model','Responder');
			$this->Responder = new Responder();		
			$this->Responder ->deleteAll(array('Responder.group_id'=>$id));

                        app::import('Model','Smsloyalty');
		        $this->Smsloyalty = new Smsloyalty();
                        $this->Smsloyalty ->deleteAll(array('Smsloyalty.group_id'=>$id));

			$this->Session->setFlash(__('Group deleted', true));
			if($message_type == 1){
			$this->redirect(array('action'=>'mms_group'));
			}else if($message_type == 0){
			$this->redirect(array('action'=>'index'));
			}
		}
		$this->Session->setFlash(__('Group  deleted', true));
		$this->redirect(array('action' => 'index'));

	}
	
	
	function edit($id = null) {
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		$this->set('id',$id);
		$this->set('user_id',$user_id);
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		app::import('Model','User');
		$this->User = new User();
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id))); 
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$this->set('users',$users);
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid group', true));
			$this->redirect(array('action' => 'index'));
		}
		$group_arr= $this->Group->find('first', array('conditions'=>array('Group.id'=>$id)));
		$this->set('group',$group_arr);
		if (!empty($this->data)) {
			$group= $this->Group->find('first', array('conditions'=>array('Group.id'=>$id)));
			$this->set('group', $group);
			$group['Group']['keyword'];		
				if($group['Group']['keyword'] == $this->data['Group']['keyword']){
					$this->data['Group']['keyword'] = $this->data['Group']['keyword'];
				}  
				/* message type option update data */
				$msgtype=$this->data['Group']['msg_type'];
				if($msgtype==0){
					$this->data['Group']['text_message']=$this->data['Group']['text_message'];
					$this->data['Group']['audio_name']= '';
					$this->data['Group']['message_type']=0;
				}else if($msgtype==1){
					$name = $this->data['Group']['mp3']['name'];
					$allowedExts = array("mp3","wav");
					$temp = explode(".", $this->data['Group']['mp3']['name']);
					$extension = end($temp);
					if ((($this->data['Group']['mp3']['type'] == "audio/mp3")
					|| ($this->data['Group']['mp3']['type'] == "audio/wav")
					|| ($this->data['Group']['mp3']['type'] == "audio/mpeg"))
					&& in_array($extension, $allowedExts)){
						$filename= str_replace(' ','_',time().$name);
						move_uploaded_file($this->data['Group']['mp3']['tmp_name'],"mp3/" . $filename);
						$this->data['Group']['audio_name']=$filename;
						$this->data['Group']['text_message']= '';
						$this->data['Group']['message_type']=1;
					}else{
						$this->Session->setFlash(__ ('Please select the audio like mp3 and wav files.',true));
					}
				}  
           	$group['Group']['id']=$this->data['Group']['id'];
           	$group['Group']['user_id']=$this->data['Group']['user_id'];
			$group['Group']['group_type']=$this->data['Group']['group_type'];
			$group['Group']['group_name']=$this->data['Group']['group_name'];
			$group['Group']['keyword']=$this->data['Group']['keyword'];
			if($this->data['Message']['msg_type']==1){
				$group['Group']['system_message']=$this->data['Group']['msg2'];
			}else if($this->data['Message']['msg_type']==2){
				$group['Group']['system_message']=$this->data['Group']['msg1'];
			}else{
				$group['Group']['system_message']=$this->data['Group']['msg2'];
			}
			$group['Group']['auto_message']=$this->data['Group']['auto_message'];
			if(!empty($this->data['Message']['msg_type'])){
				$group['Group']['sms_type']=$this->data['Message']['msg_type'];
			}else{
				$group['Group']['sms_type']=1;
			}
			if(!empty($this->data['Message']['bithday_enable'])){
				$group['Group']['bithday_enable']=$this->data['Message']['bithday_enable'];
			}else{
				$group['Group']['bithday_enable']=0;
			}
			if($this->data['Group']['image'][0]['name']!=''){			
				$image_arr='';		 		  
				foreach($this->data['Group']['image'] as $value){
					$image=str_replace(' ','_',mt_rand().$value["name"]);	
					move_uploaded_file($value['tmp_name'],"mms/".$image);
					if($image_arr!=''){
						$image_arr = $image_arr.','.SITE_URL.'/mms/'.$image;
					}else{
						$image_arr =SITE_URL.'/mms/'.$image;
					}
				}
				$group['Group']['image_url']=$image_arr;
			}else{
				$group['Group']['image_url']=$this->data['Message']['image_url_old'];
			}
			$group['Group']['active'] = 1;
                        if(isset($this->data['Group']['notify_signup'])){
				$group['Group']['notify_signup'] = 1;
			}else{
				$group['Group']['notify_signup'] = 0;
			}

                        $group['Group']['mobile_number_input'] = $this->data['Group']['mobile_number_input'];

                        $group_type = $this->data['Group']['group_type'];
                        if ($group_type == '2'){
                              $group['Group']['property_address'] = $this->data['Group']['propertyaddress'];
                              $group['Group']['property_price'] = $this->data['Group']['propertyprice'];
                              $group['Group']['property_bed'] = $this->data['Group']['property_bed'];
                              $group['Group']['property_bath'] = $this->data['Group']['property_bath'];
                              $group['Group']['property_description'] = $this->data['Group']['propertydesc'];
                              $group['Group']['property_url'] = $this->data['Group']['propertyurl'];
                                
                         }elseif ($group_type == '3'){
                              $group['Group']['vehicle_year'] = $this->data['Group']['vehicleyear'];
                              $group['Group']['vehicle_make'] = $this->data['Group']['vehiclemake'];
                              $group['Group']['vehicle_model'] = $this->data['Group']['vehiclemodel'];
                              $group['Group']['vehicle_mileage'] = $this->data['Group']['vehiclemileage'];
                              $group['Group']['vehicle_price'] = $this->data['Group']['vehicleprice'];
                              $group['Group']['vehicle_description'] = $this->data['Group']['vehicledesc'];
                              $group['Group']['vehicle_url'] = $this->data['Group']['vehicleurl'];

                        }

			if ($this->Group->save($group)) {
				$this->Session->setFlash(__('The Group has been edited', true));
				$this->redirect(array('action' => 'index'));
			}else {
				$this->Session->setFlash(__('The Group could not be edited. Please, try again.', true));
			}
			//pr($this->data);
		}
		if (empty($this->data)) {
			$this->data = $this->Group->read(null, $id);
		}
	}
	
	function view($id=null){
				$this->layout= 'admin_new_layout';
		app::import('Model','Group');
		$this->Group = new Group();
		$user_id=$this->Session->read('User.id');
		app::import('Model','User');
		$this->User = new User();
		$phoneno = $this->User->find('first',array('conditions'=>array('User.id '=>$user_id)));
		$phone_number=$phoneno['User']['assigned_number'];
		$url = SITE_URL;
		$groupname = $this->Group->find('first',array('conditions'=>array('Group.id '=>$id,'Group.user_id '=>$user_id)));
		$keyword=$groupname['Group']['keyword'];
		$this->Qrsms->sms($phone_number,$keyword);
		$name = $id;
		$this->Qrsms->draw(150, "qr/".$name);
		$this->set('qrimage',$url.'/qr/'.$name.'.png');
	}
	
	function qrcodes($groupname=null,$keyword=null,$id=null){
			$this->layout= 'admin_new_layout';
		$this->set('id',$id);
		$this->set('keyword',$keyword);
		$this->set('groupname',$groupname);
		if(!empty($this->data)){
			$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
			$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
			app::import('Model','Group');
			$this->Group = new Group();
			$groupkeyword=$this->Group->find('first',array('conditions' => array('Group.id'=>$id)));
			$user_id=$groupkeyword['Group']['user_id'];
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('Contact.phone_number'=>$this->data['Contact']['phoneno'],'ContactGroup.user_id'=>$user_id)));
			if(empty($subscriber11)){
				$contact['user_id']= $user_id;
				$contact['name']=$this->data['Contact']['name'];
				$contact['phone_number']=$this->data['Contact']['phoneno'];
				app::import('Model','Contact');
				$this->Contact = new Contact();
				$this->Contact->save($contact);
				$contactArr = $this->Contact->getLastInsertId();
			}else{
				$contactArr = $subscriber11['Contact']['id'];
			}
			if(isset($id)){
				app::import('Model','Group');
				$this->Group = new Group();
				$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$id)));
				//print_r($keywordname);
				app::import('Model','ContactGroup');
				$this->ContactGroup = new ContactGroup();
				$subscriber11 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.contact_id'=> $contactArr,'ContactGroup.group_id'=>$id)));
				if(empty($subscriber11)){
					app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					$this->data['ContactGroup']['user_id'] = $user_id;
					$this->data['ContactGroup']['group_id'] = $id;
					$this->data['ContactGroup']['group_subscribers'] = $keywordname['Group']['keyword'];
					$this->data['ContactGroup']['contact_id'] = $contactArr;
					$this->ContactGroup->save($this->data);
					app::import('Model','Group');
					$this->Group = new Group();
					$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$id)));
						//pr($groups);
					$groupArr['id'] = $groups['Group']['id'];
					$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
						/* print_r($groupArr);
						die();  */ 
					$this->Group->save($groupArr);
					$this->Session->setFlash(__('Contacts have been saved', true));
					app::import('Model','User');
					$this->User = new User();
					$someone = $this->User->find('first',array('conditions' => array('User.id'=> $user_id)));
					if($someone['User']['email_alert_options']==0){
						if($someone['User']['email_alerts']==1){
							$username = $someone['User']['username'];
							$email = $someone['User']['email'];
							$date=date('Y-m-d H:i:s',time());
							$subject="New Subscriber to ".$group_name;
							$site_name=str_replace(" ","",SITENAME);						
							$this->Email->to = $email;	
							$this->Email->subject = $subject;
							$this->Email->from = $site_name;
							$this->Email->template = 'new_subscriber_template';
							$this->Email->sendAs = 'html';
							$this->Email->Controller->set('username', $username);
							$this->Email->Controller->set('phoneno', $this->data['Contact']['phoneno']);
							$this->Email->Controller->set('groupname', $groupname);
							$this->Email->Controller->set('keyword', $keyword);
							$this->Email->Controller->set('datetime', $date);
							$this->Email->send();
						}
					}
					$user['sms_balance']=$someone['User']['sms_balance']-1;
					$user['id']=$user_id;
					$this->User->save($user);
					$to = $this->data['Contact']['phoneno'];
					$from = $someone['User']['assigned_number'];
					$message=$groupkeyword['Group']['system_message']. ' ' .$groupkeyword['Group']['auto_message'];
					$response = $this->Twilio->sendsms($to,$from,$message);
				}else{
					$this->Session->setFlash(__('This contact already exists other groups', true));
				}					   
			}

		}
	
    }			 
	function contactlist($id){
		$this->layout = null;
		app::import('Model','ContactGroup');
        $this->ContactGroup = new ContactGroup();
		$this->paginate = array('conditions' => array('ContactGroup.group_id' =>$id,'ContactGroup.un_subscribers'=>0),'order' =>array('Contact.name' => 'asc'));
		$data = $this->paginate('ContactGroup');
		$this->set('groups', $data);
		$this->set('id', $id);
	    /* $groupcontact=$this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$id,'ContactGroup.un_subscribers'=>0),'order' =>array('Contact.name' => 'asc')));
		$this->set('groups',$groupcontact); */
		app::import('Model','Group');
        $this->Group = new Group();
		$group=$this->Group->find('first',array('conditions' => array('Group.id'=>$id)));
		$this->set('groupcount',$group);
	}
	function deletecontact($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for contact', true));
			$this->redirect(array('action'=>'index'));
		}
		app::import('Model','Contact');
        $this->Contact = new Contact();
		if ($this->Contact->delete($id)) {
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$contacts_members =$this->ContactGroup->find('all', array('conditions' => array('ContactGroup.contact_id'=>$id)));
			//pr($contacts_member);
			foreach($contacts_members as $contacts_member){
				$group_id=$contacts_member['ContactGroup']['group_id'];
				app::import('Model','Group');
				$this->Group = new Group();
				$Group =$this->Group->find('first', array('conditions' => array('Group.id'=>$group_id)));
				$this->data['Group']['id'] = $group_id;
				$this->data['Group']['totalsubscriber'] = $Group['Group']['totalsubscriber']-1;
				$this->Group->save($this->data);
			}
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			//pr($Groupname);
			$this->ContactGroup->deleteAll(array('ContactGroup.contact_id' => $id));
			$this->Session->setFlash(__('Contact deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Contact was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	function editcontact($id = null) {
		$this->layout = 'popup';
		$this->set('id', $id);
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid contact', true));
			$this->redirect(array('action' => 'index'));
		}
		$user_id=$this->Session->read('User.id');
		app::import('Model','Group');
		$this->Group = new Group();
		$contacts_groupname =$this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
		//print_r($contacts_members1);
		$this->set('groupsnames',$contacts_groupname);
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		$message = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.user_id'=> $user_id,'Contact.id'=> $id)));
		foreach($message as $message){
			$groupid[$message['Group']['id']] = $message['Group']['group_name'];
		}
		//print_r($groupid);
		$this->set('groupid', $groupid);
                $this->set('email', $message['Contact']['email']);
                $this->set('birthday', $message['Contact']['birthday']);
		if (!empty($this->data)) {
		 /* echo "<pre>";
				print_r($this->data);
				 echo "</pre>";  */
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$newsubscriber1 = $this->ContactGroup->find('first',array('conditions' => array('ContactGroup.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Group']['phone_number'])));
				  /*  echo "<pre>";
				print_r($newsubscriber1);
				 echo "</pre>";
				die();
				 */
			if(empty($newsubscriber1)){
				app::import('Model','Contact');
				$this->Contact = new Contact();
				$phoneno1=$this->data['Group']['phone_number'];
				$phone  = str_replace('+','',$phoneno1);
				$phone_number  = str_replace('-','',$phone);
				$contact['user_id'] = $this->Session->read('User.id');
				$contact['name']=$this->data['Group']['name'];
				$contact['phone_number']=$phone_number;
                                $contact['email']=$this->data['Contact']['email'];
                                $contact['birthday']=$this->data['Contact']['birthday'];	
				$contact['id']=$id;
				$this->Contact->save($contact);
				$contactArr=$this->Contact->id;
				//$contactArr = $this->Contact->getLastInsertId();
			}else{
				app::import('Model','Contact');
				$this->Contact = new Contact();
				$phoneno1=$this->data['Group']['phone_number'];
				$phone  = str_replace('+','',$phoneno1);
				$phone_number  = str_replace('-','',$phone);
				$contact1['name']=$this->data['Group']['name'];
				$contact1['phone_number']=$phone_number;
                                $contact['email']=$this->data['Contact']['email'];
                                $contact['birthday']=$this->data['Contact']['birthday'];	
				$contact1['id']=$id;
				$this->Contact->save($contact1);
				$contactArr = $this->Contact->id;
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
					//print_r($keywordname);
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
						$this->ContactGroup->save($this->data);
						app::import('Model','Group');
						$this->Group = new Group();
						$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
						//pr($groups);
						$groupArr['id'] = $groups['Group']['id'];
						$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
						/* print_r($groupArr);
						die();  */ 
						$this->Group->save($groupArr);
						$this->Session->setFlash(__('Contact has been saved', true));
						
					}
				}
			}
			$this->redirect(array('action'=>'index'));        
				/* echo "<pre>";
				print_r($subscriber12);
				echo "<pre>"; */
		}else{
			app::import('Model','Contact');
			$this->Contact = new Contact();
			$newsubscriber1 = $this->Contact->find('first',array('conditions' => array('Contact.id'=>$id)));
			//print_r($newsubscriber1);
			$this->set('newsubscriber1',$newsubscriber1);
		}
	}
	function send_sms($id=null){
		$this->layout = 'popup';
		$this->set('phoneno',$id);
		if(!empty($this->data)){
			app::import('Model','Contact');
			$this->Contact = new Contact();
			$this->Contact->set($this->data);
			$this->Contact->validationSet = 'sendMsg';
			if ($this->Contact->validates()) {
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
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id)));
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('Contact.name' => 'asc')));
			$contactnames[0]='';
			foreach($contactsvars  as $contactsvar){
				/* echo"<pre>";
				print_r($contactsvar);
				echo"</pre>"; */
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
			//$contacts =$this->Contact->find('list', array('conditions' => array('Contact.user_id' =>$this->Session->read('User.id')), 'fields' => array('phone_number','name')));
			//$this->set(compact('contacts'));
			$this->set('nonactiveuser',0);
		}else{
			$this->set('nonactiveuser',1);
		}
		//$this->redirect(array('action' => 'index'));
	}
	function nexmo_send_sms($id=null){
		$this->layout = 'popup';
		$this->set('phoneno',$id);
		if(!empty($this->data)){
			app::import('Model','Contact');
			$this->Contact = new Contact();
			$this->Contact->set($this->data);
			$this->Contact->validationSet = 'sendMsg';
			if ($this->Contact->validates()) 
			{
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
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id)));
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('Contact.name' => 'asc')));
			$contactnames[0]='';
			foreach($contactsvars  as $contactsvar){
				/* echo"<pre>";
				print_r($contactsvar);
				echo"</pre>"; */
				if($contactsvar['Contact']['name']!=''){
					$contactnames[$contactsvar['Contact']['phone_number']]=$contactsvar['Contact']['name'];
				}
			}
			$this->set('contacts',$contactnames);
			//$contacts =$this->Contact->find('list', array('conditions' => array('Contact.user_id' =>$this->Session->read('User.id')), 'fields' => array('phone_number','name')));
			//$this->set(compact('contacts'));
			$this->set('nonactiveuser',0);
		}else{
			$this->set('nonactiveuser',1);
		}
		//$this->redirect(array('action' => 'index'));
	}
	
	function plivo_send_sms($id=null){
		$this->layout = 'popup';
		$this->set('phoneno',$id);
		if(!empty($this->data)){
			app::import('Model','Contact');
			$this->Contact = new Contact();
			$this->Contact->set($this->data);
			$this->Contact->validationSet = 'sendMsg';
			if ($this->Contact->validates()) 
			{
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
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id)));
			$contactsvars = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id'=>$user_id),'order' =>array('Contact.name' => 'asc')));
			$contactnames[0]='';
			foreach($contactsvars  as $contactsvar){
				/* echo"<pre>";
				print_r($contactsvar);
				echo"</pre>"; */
				if($contactsvar['Contact']['name']!=''){
					$contactnames[$contactsvar['Contact']['phone_number']]=$contactsvar['Contact']['name'];
				}
			}
			$this->set('contacts',$contactnames);
			//$contacts =$this->Contact->find('list', array('conditions' => array('Contact.user_id' =>$this->Session->read('User.id')), 'fields' => array('phone_number','name')));
			//$this->set(compact('contacts'));
			$this->set('nonactiveuser',0);
		}else{
			$this->set('nonactiveuser',1);
		}
		//$this->redirect(array('action' => 'index'));
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

	function addcontact() {
		$this->layout = 'popup';
		$user_id=$this->Session->read('User.id');
		app::import('Model','Group');
		$this->Group = new Group();
		$contacts_members1 =$this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
		//print_r($contacts_members1);
		$this->set('groupname',$contacts_members1);
		if (!empty($this->data)) {
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$newsubscriber = $this->ContactGroup->find('first',array('conditions' => array('Contact.user_id'=>$user_id,'Contact.phone_number'=>$this->data['Group']['phone_number'])));
			if(empty($newsubscriber)){
				app::import('Model','Contact');
				$this->Contact = new Contact();
				$contact['user_id']= $this->Session->read('User.id');
				$contact['name']=$this->data['Group']['name'];
				$contact['phone_number']=$this->data['Group']['phone_number'];
				$this->Contact->save($contact);
				$contactArr = $this->Contact->getLastInsertId();
				//$contactArr =  $contact['Contact']['id'];
			}else{
				$contactArr = $newsubscriber['Contact']['id'];
			}
			if($contactArr!=''){
				//print_r($this->data['Group']['id']);
				foreach($this->data['Group']['id'] as $groupIds){
					$group_id = $groupIds;
					app::import('Model','Group');
					$this->Group = new Group();
					$keywordname=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
					//print_r($keywordname);
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
						//print_r($contactgroup);
						app::import('Model','Group');
						$this->Group = new Group();
						$groups=$this->Group->find('first',array('conditions' => array('Group.id'=>$group_id)));
						//pr($groups);
						$groupArr['id'] = $groups['Group']['id'];
						$groupArr['totalsubscriber'] = $groups['Group']['totalsubscriber']+1;
						/* print_r($groupArr);
						die();  */ 
						$this->Group->save($groupArr);
						$this->Session->setFlash(__('Contacts have been saved', true));
					}else{
						$this->Session->setFlash(__('This contact already exists', true));
					}
				}
			}else{
				$this->Session->setFlash(__('The contact could not be saved. Please, try again.', true));
			}
			$this->redirect(array('controller' => 'groups','action' => 'index'));	
		}	
	}
	function voicebroadcasting($id=null,$group_id=null){
		$this->layout ='popup';
		app::import('Model','User');
		$this->User = new User();
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		$user_id = $this->Session->read('User.id');
		$users_number=$this->UserNumber->find('list',array('conditions'=>array('UserNumber.user_id'=>$user_id,'UserNumber.voice'=>1),'fields' => 'UserNumber.number'));
		$users=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id,'User.voice'=>1)));
		$this->set('usernumber',$users_number);
		$this->set('users',$users);
		$this->set('group_id',$group_id);
		$this->set('id',$id);
        $active = $users['User']['active'];
		if(!empty($this->data)){
			if ($active == 0){
			   $this->Session->setFlash(__('Your account is inactive. You need to activate your account to send voice broadcasts.', true));
			   $this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
			}
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			$Subscriber = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$this->data['Group']['id'],'ContactGroup.do_not_call'=>0,'ContactGroup.un_subscribers'=>0)));
			$group_id = $this->data['Group']['id'];
			$repeat = $this->data['voice']['repeat'];
			$language = $this->data['voice']['language'];
			$pause = $this->data['voice']['pause'];
			if(!empty($Subscriber)){
				$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
				$totalsubscribers= count($Subscriber);
				if($usersms['User']['voice_balance'] >= $totalsubscribers){
					if($usersms['User']['voice_balance'] > 0){
						if(API_TYPE==0){
							foreach($Subscriber as $contacts){
								$callnumber = $contacts['Contact']['phone_number'];
								$callerPhone = $this->data['Group']['number'];
								Controller::loadModel('Log');
								$logarr['Log']['id'] = '';
								$logarr['Log']['user_id'] = $user_id;
								$logarr['Log']['group_id'] = $contacts['Contact']['group_id']; 
								$logarr['Log']['phone_number'] = $callnumber; 
								$logarr['Log']['route'] = 'inbox'; 
								$logarr['Log']['msg_type'] = 'broadcast';
								$this->Log->save($logarr);
								$lastinsertID = $this->Log->getLastInsertId();
								$Sid='';
								if($lastinsertID > 0){
									$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
									$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
									$response = $this->Twilio->callNumberToPEOPLE($callnumber,$callerPhone,$group_id,$lastinsertID,$repeat,$language,$pause);
									$Sid = $response->ResponseXml->Call->Sid;
									if($Sid==''){
										$logarr['Log']['id'] = $lastinsertID;
										$logarr['Log']['sms_status'] = 'failed';
										$this->Log->save($logarr);
									}
								}
							}
						}else if(API_TYPE==3){
							foreach($Subscriber as $contacts){
								$callnumber = $contacts['Contact']['phone_number'];
								$callerPhone = $this->data['Group']['number'];
								Controller::loadModel('Log');
								$logarr['Log']['id'] = '';
								$logarr['Log']['user_id'] = $user_id;
								$logarr['Log']['group_id'] = $contacts['Contact']['group_id']; 
								$logarr['Log']['phone_number'] = $callnumber; 
								$logarr['Log']['route'] = 'inbox'; 
								$logarr['Log']['msg_type'] = 'broadcast';
								$this->Log->save($logarr);
								$lastinsertID = $this->Log->getLastInsertId();
								$Sid='';
								if($lastinsertID > 0){
									$this->Plivo->AuthId =PLIVO_KEY;
									$this->Plivo->AuthToken =PLIVO_TOKEN;
									$response = $this->Plivo->callNumberToPEOPLE($callnumber,$callerPhone,$group_id,$lastinsertID,$repeat,$language,$pause);
								}
						
								
							}
						}else{
							foreach($Subscriber as $contacts){
								$callnumber = $contacts['Contact']['phone_number'];
								$callerPhone = $this->data['Group']['number'];
								Controller::loadModel('Log');
								$logarr['Log']['id'] = '';
								$logarr['Log']['user_id'] = $user_id;
								$logarr['Log']['group_id'] = $contacts['Contact']['group_id']; 
								$logarr['Log']['phone_number'] = $callnumber; 
								$logarr['Log']['route'] = 'inbox'; 
								$logarr['Log']['msg_type'] = 'broadcast';
								$this->Log->save($logarr);
								$lastinsertID = $this->Log->getLastInsertId();
								$Sid='';
								if($lastinsertID > 0){
									$api_key=NEXMO_KEY;
									$api_secret=NEXMO_SECRET;
									$response = $this->Nexmo->callNumberToPEOPLE($callnumber,$callerPhone,$group_id,$api_key,$api_secret,$lastinsertID,$repeat,$language,$pause);
									$Sid = $response->{'call-id'};
								}
						
								if($Sid!=''){
									$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
									$this->data['User']['voice_balance']=$usersms['User']['voice_balance']-1;
									$this->data['User']['id']=$user_id;
									$this->User->save($this->data);
									//Check voice credit balance	
									$usersms = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
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
								}else{
									$logarr['Log']['id'] = $lastinsertID;
									$logarr['Log']['sms_status'] = 'failed';
									$this->Log->save($logarr);
								}
							}
						}
						$this->Session->setFlash(__('Broadcast message has been sent', true));
					}else{
						$this->Session->setFlash(__('Voice credit balance is too low. Please purchase more voice credits', true));
					}
				}else{
					$this->Session->setFlash(__('You do not have enough credits to send a broadcast to these contacts', true));
				}
			}else{
				$this->Session->setFlash(__('Broadcast message could not be sent. Please, try again', true));
			}
			$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
		}
	}

	function voicebroadcast(){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','Group');
		$this->Group = new Group();
		$group_list=$this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));	
		$this->set('groups',$group_list);
		if(!empty($this->data)){
			app::import('Model','VoiceMessage');
			$this->VoiceMessage = new VoiceMessage();
			$group_id=$this->data['Group']['id'];
			$voice_arr=$this->VoiceMessage->find('first',array('conditions'=>array('VoiceMessage.group_id'=>$group_id)));	
			if(empty($voice_arr)){
				$this->data['VoiceMessage']['user_id']=$user_id;
				$this->data['VoiceMessage']['group_id']=$this->data['Group']['id'];		
				$this->data['VoiceMessage']['text_message']=$this->data['Group']['text_message'];
				$this->data['VoiceMessage']['message_type']=$this->data['Group']['msg_type'];
				$this->data['VoiceMessage']['created']=date('Y-m-d h:i:s a');
				if($this->data['Group']['msg_type']==1){
					$filename = str_replace(' ','_',$this->data['Group']['mp3']['name']);
					$filename1 = str_replace('(','_',$filename);
					$filename2 = str_replace(')','_',$filename1);
					$filenewname= time().$filename2;
						if(move_uploaded_file($this->data['Group']['mp3']['tmp_name'],'voice/'.$filenewname)){
							$this->data['VoiceMessage']['audio']=$filenewname;
						}
				}
				if($this->VoiceMessage->save($this->data)){
					$this->Session->setFlash(__('Broadcast message saved.', true));
					$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
				}else{
					$this->Session->setFlash(__('Broadcast message not  saved.', true));
					$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
				}
			
			}else{
				$this->Session->setFlash(__('Broadcast message already assigned to this group.', true));
				$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
			}
		}
	}
	function broadcast_list(){
		$this->layout= 'admin_new_layout';
		app::import('Model','VoiceMessage');
		$this->VoiceMessage = new VoiceMessage();
		$user_id=$this->Session->read('User.id');	
		$this->paginate = array(
		'conditions' => array('VoiceMessage.user_id' =>$user_id),'order' =>array('VoiceMessage.id' => 'desc')
		);
		$data = $this->paginate('VoiceMessage');
		$this->set('vioce_broad', $data);
	}
	function edit_broadcast($id=null){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');	
		app::import('Model','VoiceMessage');
		$this->VoiceMessage = new VoiceMessage();
		app::import('Model','Group');
		$this->Group = new Group();
		//$group_list=$this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
		$group_ids=$this->Group->find('all',array('conditions'=>array('Group.user_id'=>$user_id),'order' =>array('Group.group_name' => 'asc')));
		//$this->set('group_lists',$group_list);
		$this->set('group_ids',$group_ids);
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'broadcast_list'));
		}
		$voice_arr= $this->VoiceMessage->find('first', array('conditions'=>array('VoiceMessage.id'=>$id)));
		$this->set('broad_cast',$voice_arr);
		if (!empty($this->data)) {	
			$voice_arr=$this->VoiceMessage->find('first',array('conditions'=>array('VoiceMessage.id'=>$this->data['VoiceMessage']['id'],'VoiceMessage.group_id'=>$this->data['VoiceMessage']['group_id'])));
			if(!empty($voice_arr)){
				app::import('Model','VoiceMessage');
				$this->VoiceMessage = new VoiceMessage();
				$this->data['VoiceMessage']['id']=$this->data['VoiceMessage']['id'];	
				$this->data['VoiceMessage']['user_id']=$user_id;
				$this->data['VoiceMessage']['group_id']=$this->data['VoiceMessage']['group_id'];
				$this->data['VoiceMessage']['text_message']=$this->data['VoiceMessage']['text_message'];
				$this->data['VoiceMessage']['message_type']=$this->data['VoiceMessage']['msg_type'];
				$this->data['VoiceMessage']['created']=date('Y-m-d h:i:s a');
				if($this->data['VoiceMessage']['msg_type']==1){
					$filename = str_replace(' ','_',$this->data['VoiceMessage']['mp3']['name']);
					$filename1 = str_replace('(','_',$filename);
					$filename2 = str_replace(')','_',$filename1);
					$filenewname= time().$filename2;
					if(move_uploaded_file($this->data['VoiceMessage']['mp3']['tmp_name'],'voice/'.$filenewname)){
						$this->data['VoiceMessage']['audio']=$filenewname;
					}
				}
				if($this->VoiceMessage->save($this->data)){
					$this->Session->setFlash(__('Broadcast message updated.', true));
					$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
				}else{
					$this->Session->setFlash(__('Broadcast message not  updated.', true));
					$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
				}
			}else{
				$this->Session->setFlash(__('Broadcast message already assigned to this group.', true));
				$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
			}
		}
		if(empty($this->data)){
			//echo 'test';
			$this->VoiceMessage->read(null,$id);
		}
	}
	function delete_broadcast($id=null){
		$this->autoRender=false;
		if(!$id){
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'broadcast_list'));
		}
		if($id!=''){
			app::import('Model','VoiceMessage');
			$this->VoiceMessage = new VoiceMessage();
			if($this->VoiceMessage->delete($id)){
				$this->Session->setFlash(__('Broadcast message deleted.', true));
				$this->redirect(array('controller' => 'groups','action' => 'broadcast_list'));
			}
	
		}
	}
	function do_not_call($id=null){
		$this->layout = 'popup';
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		app::import('Model','Group');
		$this->Group = new Group();
		$this->paginate = array('conditions' => array('ContactGroup.group_id' =>$id,'ContactGroup.do_not_call'=>1),'order' =>array('ContactGroup.id' => 'desc'));
		$data = $this->paginate('ContactGroup');
		$this->set('calls',$data);	
		$group_names=$this->Group->find('first',array('conditions'=>array('Group.id'=>$id)));
		$this->set('group_name', $group_names);	
		$totalUsers = $this->ContactGroup->find('count', array('conditions'=>array('ContactGroup.group_id' => $id)));
		$this->set('count_user',$totalUsers);	
			
	}

        
	
}
?>