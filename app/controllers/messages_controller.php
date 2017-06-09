<?php
class MessagesController extends AppController {
    var $uses = array();
	var $name = 'Messages';
	var $components = array('Cookie','Email','Twilio','Mms','Nexmomessage','Facebook','Twitter','Slooce','Plivo');
    //var $layout="default";
	function send_message($id=null,$source=null){

		$this->layout= 'admin_new_layout';
		if($source=='mobile'){
  			$this->set('mobilepageid',$id);
		}
		else if($source=='contacts'){
			$this->set('contactid',$id);
		}else if($source=='groups'){
			$this->set('groupid',$id);
		}
		$this->set('source',$source);
		if(isset($_REQUEST['shortlink'])){
			if($_REQUEST['shortlink'] > 0){
				app::import('Model','Shortlink');
				$this->Shortlink = new Shortlink();
				$shortlinks = $this->Shortlink->find('first', array('conditions' => array('Shortlink.id'=>$_REQUEST['shortlink'])));
				if(!empty($shortlinks)){
					$this->set('short_url',$shortlinks['Shortlink']['short_url']);
				}
			}
		}
        $user_id=$this->Session->read('User.id');
		$this->Session->write('User.sms_balance'); 
		app::import('Model','Group');
		$this->Group = new Group();
		$Group = $this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
		$this->set('Group',$Group);
		app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		$contactnumber = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.user_id'=> $user_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.id','Group.group_name','Contact.phone_number'),'order' =>array('Group.group_name' => 'asc','Contact.name' => 'asc')));

		$this->set('contactnumber',$contactnumber);
		app::import('Model','Smstemplate');
        $this->Smstemplate = new Smstemplate();
		$Smstemplate = $this->Smstemplate->find('list',array('conditions'=>array('Smstemplate.user_id'=>$user_id),'fields'=>'Smstemplate.messagename','order' =>array('Smstemplate.messagename' => 'asc')));
		/* echo "<pre>";
		
		print_r($Smstemplate);
		echo "</pre>"; 
		 */
		$this->set('Smstemplate',$Smstemplate);
		app::import('Model','MobilePage');
        $this->MobilePage = new MobilePage();
		$mobilespage = $this->MobilePage->find('list',array('conditions'=>array('MobilePage.user_id'=>$user_id),'fields'=>'MobilePage.title','order' =>array('MobilePage.title' => 'asc')));
		$this->set('mobilespages', $mobilespage);
		app::import('Model','User');
		$this->User = new User();
                $this->User->recursive = -1;
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$credits = $users['User']['sms_balance'];
		//$usedcredit=$users['User']['credit_used'];
		//$totalCredit = $credits-$usedcredit;
		$this->Session->write('User.sms_balance',$users['User']['sms_balance']); 
		$this->Session->write('User.assigned_number',$users['User']['assigned_number']); 
		$this->Session->write('User.pay_activation_fees_active',$users['User']['pay_activation_fees_active']); 
		$this->Session->write('User.active',$users['User']['active']); 
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
			$user_numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
			$user_numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
			$users_arr = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
			$this->set('users',$users);
			if($this->data['Message']['msg_type']==1){
				if(($users_arr['User']['sms']!=1)&& (empty($user_numbers_sms))){
					$this->Session->setFlash(__('You do not have any number with SMS capability', true));
					$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
				}
		
			}else if($this->data['Message']['msg_type']==2){
				if(($users_arr['User']['mms']!=1)&& (empty($user_numbers_mms))){
					$this->Session->setFlash(__('You do not have any number with Mms capability', true));
					$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
				}
			}
			if($this->data['Message']['image'][0]['name']!=''){
				$counter=sizeof($this->data['Message']['image']);
				if($counter > 10){
					$this->Session->setFlash(__('You can not upload more than 10 images', true));
					$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
				}
			}
			if($this->data['Message']['image'][0]['name']!=''){
				 $image_arr='';
				 $fbpotsurl='';
				foreach($this->data['Message']['image'] as $value){
					$image=str_replace(' ','_',$value["name"]);	
					move_uploaded_file($value['tmp_name'],"mms/".$image);
					if($image_arr!=''){
						$image_arr = $image_arr.','.SITE_URL.'/mms/'.$image;
					}else{
						$image_arr =SITE_URL.'/mms/'.$image;
						$fbpotsurl =SITE_URL.'/mms/'.$image;
					}
				}
			}
			$rotate_number=$this->data['User']['rotate_number'];	
			$throttle=$this->data['User']['throttle'];	
                        $alphasenderid = '';
                        $alphasenderid = $this->data['Message']['alphasenderid_input'];

			if(isset($this->data['Message']['logout'])){
			  $this->Facebook->Appid = FACEBOOK_APPID;
			  $this->Facebook->AppSecret = FACEBOOK_APPSECRET;
			  $facekbookid = $this->Facebook->checkfblogin();
				if($facekbookid != 0){
					$this->Facebook->Appid = FACEBOOK_APPID;
					$this->Facebook->AppSecret = FACEBOOK_APPSECRET;
					if($this->data['Message']['msg_type']==2){
						if(!empty($this->data['Message']['msg'])){
							if($fbpotsurl!=''){
								$messagefb =str_replace('%%Name%%','',$this->data['Message']['msg']);
								$facekbookresponse = $this->Facebook->messagepost($messagefb,$facekbookid,$fbpotsurl);
							}
						}else{
							if($fbpotsurl!=''){
							$messagefb =str_replace('%%Name%%','',$this->data['Message']['msg']);
							$facekbookresponse = $this->Facebook->messagepost($messagefb,$facekbookid,$fbpotsurl);
							}
						}
					}else{
						if(!empty($this->data['Keyword']['message'])){
							$messagefb =str_replace('%%Name%%','',$this->data['Keyword']['message']);
							$facekbookresponse = $this->Facebook->messagepostold($messagefb,$facekbookid);
						}
					}
				}
			}
			app::import('Model','Group');
			$this->Group = new Group();
			$Groupname = $this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id)));
			if(empty($Groupname)){
				$this->Session->setFlash(__('Please create a group before sending a message.', true));
				$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
			} 
			if($this->data['User']['shedule']=='Select Time' || $this->data['User']['shedule']==''){
				if($this->data['pick']['id']==1){
					app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					//$Subscriber = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$this->data['Keyword']['id'],'ContactGroup.un_subscribers'=>0)));
					$Subscriber = $this->ContactGroup->find('count',array('conditions' => array('ContactGroup.group_id'=>$this->data['Keyword']['id'],'ContactGroup.un_subscribers'=>0)));
				}else{
					/*app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					$Subscriber = $this->ContactGroup->find('all',array('conditions' => array('Contact.phone_number'=>$this->data['Contact']['phone'],'ContactGroup.un_subscribers'=>0)));*/
					app::import('Model','Contact');
					$this->Contact = new Contact();
					$Subscriber = $this->Contact->find('all',array('conditions' => array('Contact.id'=>$this->data['Contact']['phone'])));
					//pr($Subscriber);
				}
				if($this->data['pick']['id']==1){
					//if(empty($Subscriber)){
                    if(empty($Subscriber) || $Subscriber == 0){
						$this->Session->setFlash(__('Add contacts to this group or select a different group.', true));
						$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
					} 
                    $totalsubscribers= $Subscriber;
				}else{
                    $totalsubscribers= count($Subscriber);
                }
				$subscriberPhone = '';
				$body = $this->data['Keyword']['message']." ".$this->data['Message']['systemmsg'];
				$spinbody = $this->process($body);
				$length = strlen(utf8_decode(substr($spinbody,0,1600))); 
				if($this->data['Message']['msg_type']==2){ 
					$contactcredits = 2; 
				}else{ 
					if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
						$contactcredits = ceil($length/70);
					}else{
						$contactcredits = ceil($length/160);
					}
				}
				if($credits < ($totalsubscribers * $contactcredits)){
					$this->Session->setFlash(__('You do not have enough credits to send a message to these contacts.', true));
					$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
				}

				if($this->data['pick']['id']==2){

					if(API_TYPE==0){
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
									$from_arr[]=$users_arr['User']['assigned_number'];
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
							if(!empty($users_arr)){	
								if($users_arr['User']['mms']==1){
									$mms_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['mms']==1){
										$mms_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							if($this->data['Message']['image'][0]['name']!=''){
								foreach($this->data['Message']['image'] as $value){
									$image=str_replace(' ','_',$value["name"]);	
									move_uploaded_file($value['tmp_name'],"mms/".$image);
								}
								
							}
							app::import('Model','Log');
							//foreach($this->data['Contact']['phone'] as $contacts){
							$k = 0;							
							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$body11="";
								$message='';
								if($this->data['Message']['image'][0]['name']!=''){
									foreach($this->data['Message']['image'] as $value){
										$image=str_replace(' ','_',$value["name"]);	
										$message[]=SITE_URL.'/mms/'.$image;
										if($body11==''){
											$body11=SITE_URL.'/mms/'.$image;
										}else{
											$body11=$body11.','.SITE_URL.'/mms/'.$image;
										}
									}
									$msg_type="mms";
									$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Message']['msg']);
									$mms_text = $message_replace.' '.$this->data['Message']['systemmsg'];
								}else{
									$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
									$message = $message_replace.' '.$this->data['Message']['systemmsg'];
									$body11=$message;
									$msg_type="sms";
								}
								if($this->data['Message']['pick_button']=='set'){
									$message[]=$this->data['Message']['pick_file'];
									$body11=$this->data['Message']['pick_file'];
									$msg_type="mms";
								}
								$to = $contacts['Contact']['phone_number'];
								$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
								$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
								$Status='';
								if($msg_type=="sms"){
									if ($throttle > 1){
									   $countnumber = count($from_arr);
										if($countnumber==$k){
											$k = 0;
											sleep($throttle);
										}
									}
									$from=$from_arr[$k];
									$message = $this->process($message);
									$response = $this->Twilio->sendsms($to,$from,$message);
									$type='text';
									$Status=$response->ResponseXml->RestException->Status;
									$smsid=$response->ResponseXml->Message->Sid;
									//$Status=$response->ResponseXml->RestException->Status;
									//$smsid=$response->ResponseXml->Message->Sid;
								}else if($msg_type=="mms"){
									//$random_keys= array_rand($mms_arr,1);
									//$from=$mms_arr[$random_keys];
									if ($throttle > 1){
										$countnumber = count($mms_arr);
										if($countnumber==$k){
											$k = 0;
											sleep($throttle);
										}
									}
                                    $from=$mms_arr[$k];
									$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
									$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
									$mms_text = $this->process($mms_text);
									$response = $this->Mms->sendmms($to,$from,$message,$mms_text);
									$type='text';
									$smsid=$response->sid;
									if(!isset($response->sid)){
										$ErrorMessage = $response;
										$Status='400';
									}
								}
								//$Status=$response->ResponseXml->Message->Status;
								$this->data['Log']['id'] ='';
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$smsid;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;
								$this->data['Log']['msg_type']=$type;
								$this->data['Log']['phone_number']=$to;
								if($msg_type=='mms'){
									$this->data['Log']['text_message']= $mms_text;
									$this->data['Log']['image_url']= $body11;
								}else{
									$this->data['Log']['text_message']= $message;
								}
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								if($Status==400){				
									$this->data['Log']['sms_status']= 'failed';
									if(isset($response->ErrorMessage)){
										$ErrorMessage = $response->ErrorMessage;
									}else{
										$ErrorMessage = $ErrorMessage;
									}
									$this->data['Log']['error_message']=$ErrorMessage;
								}
								$this->Log->save($this->data);
								$k = $k+1;
							}
						}else{
							if($this->data['Message']['msg_type']==2){
								$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.mms'=>1)));

                                                           if($alphasenderid != ''){
                                                              $assigned_number = $alphasenderid;
                                                           }else{
								if(!empty($usernumber)){
									$assigned_number = $usernumber['User']['assigned_number'];
								}else{
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$mmsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
									if(!empty($mmsnumber)){
										$assigned_number= $mmsnumber['UserNumber']['number'];
									}else{
										$assigned_number = $usernumber['User']['assigned_number'];
									}
                                                                 }
							    }
							}else{
								if(!empty($users_arr)){
                                                                   if($alphasenderid != ''){
                                                                      $assigned_number = $alphasenderid;
                                                                   }else{
									if($users_arr['User']['sms']==1){
										$assigned_number=$users_arr['User']['assigned_number'];
									}else{
										app::import('Model','UserNumber');
										$this->UserNumber = new UserNumber();
										$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
										if(!empty($user_numbers)){
											$assigned_number=$user_numbers['UserNumber']['number'];
										}else{
											$assigned_number=$users_arr['User']['assigned_number'];
										}
									}
                                                                    }
								}
							}
							app::import('Model','Log');
							//foreach($this->data['Contact']['phone'] as $contacts){
							if($this->data['Message']['image'][0]['name']!=''){
								foreach($this->data['Message']['image'] as $value){
									$image=str_replace(' ','_',$value["name"]);	
									move_uploaded_file($value['tmp_name'],"mms/".$image);
								}
							}
							$Status='';

							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$message='';
								$body11="";
								if($this->data['Message']['image'][0]['name']!=''){
									foreach($this->data['Message']['image'] as $value){
										$image=str_replace(' ','_',$value["name"]);	
										$message[]=SITE_URL.'/mms/'.$image;
										if($body11==''){
											$body11=SITE_URL.'/mms/'.$image;
										}else{
											$body11=$body11.','.SITE_URL.'/mms/'.$image;
										}
									}
									$msg_type="mms";					
									$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Message']['msg']);
									$mms_text = $message_replace.' '.$this->data['Message']['systemmsg'];
								}else{
									$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
									$message = $message_replace.' '.$this->data['Message']['systemmsg'];
									$body11=$message;
									$msg_type="sms";
								}
								if($this->data['Message']['pick_button']=='set'){
									$message[]=$this->data['Message']['pick_file'];
									$body11=$this->data['Message']['pick_file'];
									$msg_type="mms";
								}	
								//$to = $contacts;
								$to = $contacts['Contact']['phone_number'];
								$from=$assigned_number;
								//die();
							   // $from = $assigned_number;
								//$from = '2029993169';
								$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
								$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
								if ($throttle > 1) {
									sleep($throttle);
								}
                                if($msg_type=="sms"){
									$message = $this->process($message);
									$response = $this->Twilio->sendsms($to,$from,$message);
									$type='text';
									$Status=$response->ResponseXml->RestException->Status;
									$smsid=$response->ResponseXml->Message->Sid;
									$ErrorMessage = $response->ErrorMessage;	
								}else if($msg_type=="mms"){
									$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
									$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
									$mms_text = $this->process($mms_text);
									$response = $this->Mms->sendmms($to,$from,$message,$mms_text);
									$type='text';
									$smsid=$response->sid;
									if($smsid==''){
										$ErrorMessage = $response;
										$Status=400;
									}
								}
								$this->data['Log']['id'] ='';
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$smsid;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;
								$this->data['Log']['msg_type']=$type;
								$this->data['Log']['phone_number']=$to;
								if($msg_type=='mms'){
									$this->data['Log']['text_message']= $mms_text;
									$this->data['Log']['image_url']= $body11;
								}else{
									$this->data['Log']['text_message']= $message;
								}
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
							
								if($Status==400){				
									$this->data['Log']['sms_status']= 'failed';
									if(isset($response->ErrorMessage)){
										$ErrorMessage = $response->ErrorMessage;
									}else{
										$ErrorMessage = $ErrorMessage;
									}
									$this->data['Log']['error_message']=$ErrorMessage;
								}
								$this->Log->save($this->data);

							}
						}
					}else if(API_TYPE==2){
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
									$from_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['sms']==1){
										$from_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							app::import('Model','Log');
							//foreach($this->data['Contact']['phone'] as $contacts){
							$k = 0;
							$sucesscredits = 0;
							$credits = 0;
							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$message_replace = str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
								//$to = $contacts;
								$to = $contacts['Contact']['phone_number'];
								$message =  $message_replace.' '.$this->data['Message']['systemmsg'];
								//$from = $users['User']['assigned_number'];
								$countnumber = count($from_arr);
								if($countnumber==$k){
									$k = 0;
								}
								$from=$from_arr[$k];
								$message = $this->process($message);
								$response = $this->Slooce->mt($users_arr['User']['api_url'],$users_arr['User']['partnerid'],$users_arr['User']['partnerpassword'],$to,$users_arr['User']['keyword'],$message);
								$message_id = '';
								$status = '';
								if(isset($response['id'])){
									if($response['result']=='ok'){
										$message_id = $response['id'];
									}
									$status = $response['result'];
								}	
								$this->data['Log']['id'] ='';
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
									$this->data['Log']['error_message']=$status;
								}
								if($message_id!=''){
									$sucesscredits = $sucesscredits + 1;
									$length = strlen(utf8_decode(substr($message,0,1600))); 
									$credits = $credits + ceil($length/160);
									$this->data['Log']['sms_status']= 'sent';
								}
								$this->Log->save($this->data);
								$k = $k+1;
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits; 
									$this->User->save($usercredit);
								}
							}
							$this->smsmail($user_id);
						}else{
							app::import('Model','Log');
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
							$sucesscredits = 0;
							$credits = 0;
							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$message_replace = str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
								$message = $message_replace.' '.$this->data['Message']['systemmsg'];
								//$to = $contacts;
								$to = $contacts['Contact']['phone_number'];
								$from = $assigned_number;
								$message = $this->process($message);
								$response = $this->Slooce->mt($users_arr['User']['api_url'],$users_arr['User']['partnerid'],$users_arr['User']['partnerpassword'],$to,$users_arr['User']['keyword'],$message);
								$message_id = '';
								$status = '';
								if(isset($response['id'])){
									if($response['result']=='ok'){
										$message_id = $response['id'];
									}
									$status = $response['result'];
								}
								$this->data['Log']['id'] ='';
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;
								//$this->data['Log']['phone_number']=$contacts;
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								if($message_id!=''){
									$sucesscredits = $sucesscredits + 1;
									$length = strlen(utf8_decode(substr($message,0,1600))); 
									$credits = $credits + ceil($length/160);
									$this->data['Log']['sms_status']= 'sent';
									//$this->smsmail($user_id);
								}
								if($status!=0){				
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
								}
								$this->Log->save($this->data);
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id;
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
									$this->User->save($usercredit);
								}
							}
							$this->smsmail($user_id);
						  
						}
					}else if(API_TYPE==3){

						if($rotate_number==1){

							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
									$from_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['sms']==1){
										$from_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							app::import('Model','Log');
							$k = 0;
							$sucesscredits = 0;
							$credits = 0;
							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$message_replace = str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
								//$to = $contacts;
								$to = $contacts['Contact']['phone_number'];
								$message =  $message_replace.' '.$this->data['Message']['systemmsg'];
								//$from = $users['User']['assigned_number'];
								$countnumber = count($from_arr);
								if($countnumber==$k){
									$k = 0;
									sleep($throttle);
								}
								// $random_keys= array_rand($from_arr,1);
								$from=$from_arr[$k];
								//$from = '2029993169';
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								$message = $this->process($message);					
								$response = $this->Plivo->sendsms($to,$from,$message);
								$errortext = '';
								$message_id = '';
								if(isset($response['response']['error'])){
									$errortext = $response['response']['error'];
								}
								if(isset($response['response']['message_uuid'][0])){
									$message_id = $response['response']['message_uuid'][0];
								}
								$this->data['Log']['id'] ='';
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;
								//$this->data['Log']['phone_number']=$contacts;
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
									$sucesscredits = $sucesscredits + 1;
									$length = strlen(utf8_decode(substr($message,0,1600))); 
									//$credits = $credits + ceil($length/160);
									if (strlen($message) != strlen(utf8_decode($message))){
										$credits = $credits + ceil($length/70);
									}else{
										$credits = $credits + ceil($length/160);
									}
									$this->data['Log']['sms_status']= 'sent';
								}
								$this->Log->save($this->data);
								$k = $k+1;

							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									//$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									//$length = strlen(utf8_decode(substr($message,0,1600))); 
									//$credits = ceil($length/160);
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits; 
									$this->User->save($usercredit);
								}
							}
							$this->smsmail($user_id);

						}else{
							app::import('Model','Log');
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
							$sucesscredits = 0;
							$credits = 0;


							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$message_replace = str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
								$message = $message_replace.' '.$this->data['Message']['systemmsg'];
								//$to = $contacts;
								$to = $contacts['Contact']['phone_number'];
								$from = $assigned_number;
								//$from = '2029993169';
								$this->Plivo->AuthId =PLIVO_KEY;
								$this->Plivo->AuthToken =PLIVO_TOKEN;
								sleep($throttle);
								$message = $this->process($message);
								$response = $this->Plivo->sendsms($to,$from,$message);
								$errortext = '';
								$message_id = '';
								if(isset($response['response']['error'])){
									$errortext = $response['response']['error'];
								}
								if(isset($response['response']['message_uuid'][0])){
									$message_id = $response['response']['message_uuid'][0];
								}	
								$this->data['Log']['id'] ='';
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;
								//$this->data['Log']['phone_number']=$contacts;
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								if($message_id!=''){
									$sucesscredits = $sucesscredits + 1;
									$length = strlen(utf8_decode(substr($message,0,1600))); 
									if (strlen($message) != strlen(utf8_decode($message))){
										$credits = $credits + ceil($length/70);
									}else{
										$credits = $credits + ceil($length/160);
									}
									$this->data['Log']['sms_status']= 'sent';
								}
								if(isset($response['response']['error'])){		
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
								}
								$this->Log->save($this->data);
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
									$this->User->save($usercredit);
								}
							}
							$this->smsmail($user_id);
						}
					}else{

						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
									$from_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['sms']==1){
										$from_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							app::import('Model','Log');
							//foreach($this->data['Contact']['phone'] as $contacts){
							$k = 0;
							$sucesscredits = 0;
							$credits = 0;
							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$message_replace = str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
								//$to = $contacts;
								$to = $contacts['Contact']['phone_number'];
								$message =  $message_replace.' '.$this->data['Message']['systemmsg'];
								//$from = $users['User']['assigned_number'];
								$countnumber = count($from_arr);
								if($countnumber==$k){
									$k = 0;
									sleep($throttle);
								}
								// $random_keys= array_rand($from_arr,1);
								$from=$from_arr[$k];
								//$from = '2029993169';
								$this->Nexmomessage->Key = NEXMO_KEY;
								$this->Nexmomessage->Secret = NEXMO_SECRET;
								$message = $this->process($message);					
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
								$this->data['Log']['id'] ='';
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;
								//$this->data['Log']['phone_number']=$contacts;
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								if($status!=0){				
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
								}
								if($message_id!=''){
									$sucesscredits = $sucesscredits + 1;
									$length = strlen(utf8_decode(substr($message,0,1600))); 
									//$credits = $credits + ceil($length/160);
									if (strlen($message) != strlen(utf8_decode($message))){
										$credits = $credits + ceil($length/70);
									}else{
										$credits = $credits + ceil($length/160);
									}
									$this->data['Log']['sms_status']= 'sent';
								}
								$this->Log->save($this->data);
								$k = $k+1;
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									//$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$sucesscredits; 
									//$length = strlen(utf8_decode(substr($message,0,1600))); 
									//$credits = ceil($length/160);
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits; 
									$this->User->save($usercredit);
								}
							}
							$this->smsmail($user_id);
						}else{
							app::import('Model','Log');
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

							$sucesscredits = 0;
							$credits = 0;
							foreach($Subscriber as $contacts){
								$this->Log = new Log();
								$space_pos = strpos($contacts['Contact']['name'],' ');
								if($space_pos!=''){
									$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
								}else{
									$contact_name = $contacts['Contact']['name'];
								}
								$message_replace = str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
								$message = $message_replace.' '.$this->data['Message']['systemmsg'];
								//$to = $contacts;
								$to = $contacts['Contact']['phone_number'];
								$from = $assigned_number;
								//$from = '2029993169';
								$this->Nexmomessage->Key = NEXMO_KEY;
								$this->Nexmomessage->Secret = NEXMO_SECRET;
								sleep($throttle);
								$message = $this->process($message);
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
								$this->data['Log']['id'] ='';
								$this->data['Log']['group_sms_id'] =0;
								$this->data['Log']['sms_id'] =$message_id;
								$this->data['Log']['user_id'] =$user_id;
								$this->data['Log']['group_id'] =0;
								//$this->data['Log']['phone_number']=$contacts;
								$this->data['Log']['phone_number']=$to;
								$this->data['Log']['text_message']= $message;
								$this->data['Log']['route']= 'outbox';
								$this->data['Log']['sms_status']= '';
								$this->data['Log']['error_message']='';
								if($message_id!=''){
									$sucesscredits = $sucesscredits + 1;
									$length = strlen(utf8_decode(substr($message,0,1600))); 
									//$credits = $credits + ceil($length/160);
									if (strlen($message) != strlen(utf8_decode($message))){
										$credits = $credits + ceil($length/70);
									}else{
										$credits = $credits + ceil($length/160);
									}
									$this->data['Log']['sms_status']= 'sent';
								}
								if($status!=0){				
									$this->data['Log']['sms_status']= 'failed';
									$ErrorMessage = $errortext;
									$this->data['Log']['error_message']=$ErrorMessage;
								}
								$this->Log->save($this->data);
							}
							if($sucesscredits > 0){
								$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usersbalance)){
									$usercredit['User']['id'] =$user_id; 
									$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
									$this->User->save($usercredit);
								}
							}
							$this->smsmail($user_id);
						  
						}
					}
				}else{ 
					if(API_TYPE==0){ 
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
									$from_arr[]=$users_arr['User']['assigned_number'];
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
							if(!empty($users_arr)){	
								if($users_arr['User']['mms']==1){
								$mms_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['mms']==1){
										$mms_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								$total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									 //$total=count($subscriberPhone1);
									$this->data['GroupSmsBlast']['user_id'] =$user_id;
									$this->data['GroupSmsBlast']['group_id'] =$group_id;
									$this->data['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($this->data);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									$Status='';
									$k=0;
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
												$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
												$message='';
												$body11="";
												$space_pos = strpos($groupContact['Contact']['name'],' ');
												if($space_pos!=''){
													$contact_name=substr($groupContact['Contact']['name'],0,$space_pos);
												}else{
													$contact_name = $groupContact['Contact']['name'];
												}
												if($this->data['Message']['image'][0]['name']!=''){
													foreach($this->data['Message']['image'] as $value){
														$image=str_replace(' ','_',$value["name"]);	
														$message[]=SITE_URL.'/mms/'.$image;
														if($body11==''){
															$body11=SITE_URL.'/mms/'.$image;
														}else{
															$body11=$body11.','.SITE_URL.'/mms/'.$image;
														}
													}
													$msg_type="mms";
													$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Message']['msg']);
													$mms_text = $message_replace.' '.$this->data['Message']['systemmsg'];
												}else{
													$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
													$message = $message_replace.' '.$this->data['Message']['systemmsg'];
													$body11=$message;
													$msg_type="sms";
												}
												if($this->data['Message']['pick_button']=='set'){
													$message[]=$this->data['Message']['pick_file'];
													$body11=$this->data['Message']['pick_file'];
													$msg_type="mms";
												}		
												$to = $groupContact['Contact']['phone_number'];
												//$from = $users['User']['assigned_number'];
												//$from = '2029993169';
												$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
												$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
												$Status='';

											if($msg_type=="sms"){
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
												$message = $this->process($message);
												$response = $this->Twilio->sendsms($to,$from,$message);
												$type='text';
												$Status=$response->ResponseXml->RestException->Status;
												$smsid=$response->ResponseXml->Message->Sid;
											}else if($msg_type=="mms"){
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
												$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
												$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
												$mms_text = $this->process($mms_text);
												$response = $this->Mms->sendmms($to,$from,$message,$mms_text);
												$type='text';
												$smsid=$response->sid;
												//die();
												if($smsid==''){
													$ErrorMessage = $response;
													$Status='400';
												}
											}
										  // pr($response);
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$smsid;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											if($msg_type=='mms'){
												$this->data['Log']['text_message']= $mms_text;
												$this->data['Log']['image_url']= $body11;
											}else{
												$this->data['Log']['text_message']= $message;
											}
											$this->data['Log']['msg_type']=$type;
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($Status==400){
												$this->data['Log']['sms_status']= 'failed';
												if(isset($response->ErrorMessage)){
													$ErrorMessage = $response->ErrorMessage;
												}else{
													$ErrorMessage = $ErrorMessage;
												}
												$this->data['Log']['error_message']=$ErrorMessage;
												app::import('Model','GroupSmsBlast');
												// $this->GroupSmsBlast = new GroupSmsBlast();
												$this->data['GroupSmsBlast']['id'] =$contactArr;
												//$contactArr
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												//pr($groupContacts);
												$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												//pr($this->data);
												$this->GroupSmsBlast->save($this->data);
											}
											$this->Log->save($this->data);
											$k = $k + 1;
										}
									}
								}
							}
						}else{
							$assigned_number='';
							if($this->data['Message']['msg_type']==2){
								$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
                                                                if($alphasenderid != ''){
                                                                  $assigned_number = $alphasenderid;
                                                                }else{
								   if($usernumber['User']['mms']==1){
									$assigned_number = $usernumber['User']['assigned_number'];
								   }else{
									app::import('Model','UserNumber');
									$this->UserNumber = new UserNumber();
									$mmsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1)));
									if(!empty($mmsnumber)){
										$assigned_number = $mmsnumber['UserNumber']['number'];
									}else{
										$assigned_number = $usernumber['User']['assigned_number'];
									}
								   }
                                                                }
							}else{
							   $usernumbers = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
								if(!empty($usernumbers)){
                                                                   if($alphasenderid != ''){
                                                                      $assigned_number = $alphasenderid;
                                                                   }else{
									if($usernumbers['User']['sms']==1){
										$assigned_number=$usernumbers['User']['assigned_number'];
									}else{
										app::import('Model','UserNumber');
										$this->UserNumber = new UserNumber();
										$user_numbers = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
										if(!empty($user_numbers)){	
											$assigned_number=$user_numbers['UserNumber']['number'];
										}else{
											$assigned_number=$usernumbers['User']['assigned_number'];
										}
									}
                                                                    }
								}
							}
							$contactArr='';
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								 $total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									$GroupSmsBlast['GroupSmsBlast']['user_id'] =$user_id;
									$GroupSmsBlast['GroupSmsBlast']['group_id'] =$group_id;
									$GroupSmsBlast['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($GroupSmsBlast);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
											$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
											$message='';
											$body11="";
											$space_pos = strpos($groupContact['Contact']['name'],' ');
											if($space_pos!=''){
												$contact_name=substr($groupContact['Contact']['name'],0,$space_pos);
											}
											else{
												$contact_name = $groupContact['Contact']['name'];
											}
											if($this->data['Message']['image'][0]['name']!=''){
												foreach($this->data['Message']['image'] as $value){
													$image=str_replace(' ','_',$value["name"]);	
													$message[]=SITE_URL.'/mms/'.$image;
													if($body11==''){
														$body11=SITE_URL.'/mms/'.$image;
													}else{
														$body11=$body11.','.SITE_URL.'/mms/'.$image;
													}
												}
												$msg_type="mms";
												$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Message']['msg']);
												$mms_text = $message_replace.' '.$this->data['Message']['systemmsg'];
											}else{
												$message_replace =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
												$message = $message_replace.' '.$this->data['Message']['systemmsg'];
												$body11=$message;
												$msg_type="sms";
											}
											if($this->data['Message']['pick_button']=='set'){
												$message[]=$this->data['Message']['pick_file'];
												$body11=$this->data['Message']['pick_file'];
												$msg_type="mms";
											}
											$to = $groupContact['Contact']['phone_number'];
											// $random_keys= array_rand($assigned_number,1);
											// $from=$assigned_number[$random_keys];
											$from = $assigned_number;
											//$from = '2029993169';
											$Status='';
											if ($throttle > 1) {
												sleep($throttle);
                                            }
											if($msg_type=="sms"){
												$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
												$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
												$spinmessage = $this->process($message);
												$response = $this->Twilio->sendsms($to,$from,$spinmessage);
												$type='text';
												$Status=$response->ResponseXml->RestException->Status;
												$smsid=$response->ResponseXml->Message->Sid;
											}else if($msg_type=="mms"){
												$this->Mms->AccountSid = TWILIO_ACCOUNTSID;
												$this->Mms->AuthToken = TWILIO_AUTH_TOKEN;
												$mms_text = $this->process($mms_text);
												$response = $this->Mms->sendmms($to,$from,$message,$mms_text);
												$type='text';
												$smsid=$response->sid;
												//die();
												if($smsid==''){
													$ErrorMessage = $response;
													$Status=400;
												}
											}
											  // pr($response);
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$smsid;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											if($msg_type=='mms'){
												$this->data['Log']['text_message']= $mms_text;
												$this->data['Log']['image_url']= $body11;
											}else{
												$this->data['Log']['text_message']= $spinmessage;
											}
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['msg_type']=$type;
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($Status==400){
												$this->data['Log']['sms_status']= 'failed';
												if(isset($response->ErrorMessage)){
													$ErrorMessage = $response->ErrorMessage;
												}else{
													$ErrorMessage = $ErrorMessage;
												}
												$this->data['Log']['error_message']=$ErrorMessage;
												app::import('Model','GroupSmsBlast');
												$smsblast['GroupSmsBlast']['id'] =$contactArr;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												$smsblast['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												$this->GroupSmsBlast->save($smsblast);
											}
											$this->Log->save($this->data);
										
										}
									}
								}
							}
						}
					}else if(API_TYPE ==2){
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));			
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
								$from_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['sms']==1){
										$from_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								 $total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									//$total=count($subscriberPhone1);
									$this->data['GroupSmsBlast']['user_id'] =$user_id;
									$this->data['GroupSmsBlast']['group_id'] =$group_id;
									$this->data['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($this->data);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									$k = 0;
									$sucesscredits = 0;
									$credits = 0;
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
											$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
											$space_pos = strpos($contacts['Contact']['name'],' ');
											if($space_pos!=''){
												$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
											}else{
												$contact_name = $contacts['Contact']['name'];
											}					
											$message =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
											$body = $message.' '.$this->data['Message']['systemmsg'];
											$to = $groupContact['Contact']['phone_number'];
											//$from = $users['User']['assigned_number'];
											$countnumber = count($from_arr);
											if($countnumber==$k){
												$k = 0;
											}
											$from=$from_arr[$k];
											$body = $this->process($body);
											$response = $this->Slooce->mt($users_arr['User']['api_url'],$users_arr['User']['partnerid'],$users_arr['User']['partnerpassword'],$to,$users_arr['User']['keyword'],$body);
											$message_id = '';
											$status = '';
											if(isset($response['id'])){
												if($response['result']=='ok'){
													$message_id = $response['id'];
												}
												$status = $response['result'];
											}
										
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$message_id;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											$this->data['Log']['text_message']= $body;
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($message_id!=''){
												$sucesscredits = $sucesscredits + 1;
												$length = strlen(utf8_decode(substr($body,0,1600))); 
												$credits = $credits + ceil($length/160);
												$this->data['Log']['sms_status']= 'sent';
												//$this->smsmail($user_id);

											}else if($status!='ok'){
												$this->data['Log']['sms_status']= 'failed';
												$this->data['Log']['error_message']=$status;
												app::import('Model','GroupSmsBlast');
												$this->data['GroupSmsBlast']['id'] =$contactArr;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												$this->GroupSmsBlast->save($this->data);
											}
												$this->Log->save($this->data);

										}
										$k = $k+1;
									}
									if($sucesscredits > 0){
										$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id; 
											$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$contactArr;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
										$this->GroupSmsBlast->save($group_blast);
									}
										$this->smsmail($user_id);
						 
								}
						
							}
						}else{
							$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.sms'=>1)));
							if(!empty($usernumber)){
								$from = $usernumber['User']['assigned_number'];
							}else{
								app::import('Model','UserNumber');
								$this->UserNumber = new UserNumber();
								$smsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
							if(!empty($smsnumber)){
								$from= $smsnumber['UserNumber']['number'];
							}else{
								$from = $usernumber['User']['assigned_number'];
							}

							}
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								$total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									$this->data['GroupSmsBlast']['user_id'] =$user_id;
									$this->data['GroupSmsBlast']['group_id'] =$group_id;
									$this->data['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($this->data);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									$sucesscredits = 0;
									$credits = 0;
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
											$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
											$space_pos = strpos($groupContact['Contact']['name'],' ');
											if($space_pos!=''){
												$contact_name=substr($groupContact['Contact']['name'],0,$space_pos);
											}else{
												$contact_name = $groupContact['Contact']['name'];
											}
											$message =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
											$body = $message.' '.$this->data['Message']['systemmsg'];
											$to = $groupContact['Contact']['phone_number'];
											$body = $this->process($body);
											$response = $this->Slooce->mt($usernumber['User']['api_url'],$usernumber['User']['partnerid'],$usernumber['User']['partnerpassword'],$to,$usernumber['User']['keyword'],$body);
											$message_id = '';
											$status = '';
											if(isset($response['id'])){
												if($response['result']=='ok'){
													$message_id = $response['id'];
												}
												$status = $response['result'];
											}	
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$message_id;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											$this->data['Log']['text_message']= $body;
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($message_id!=''){
												$sucesscredits = $sucesscredits + 1;
												$length = strlen(utf8_decode(substr($body,0,1600))); 
												$credits = $credits + ceil($length/160);
												$this->data['Log']['sms_status']= 'sent';
												//$this->smsmail($user_id);
											}else if($status!='ok'){
												$this->data['Log']['sms_status']= 'failed';
												$ErrorMessage = $status;
												$this->data['Log']['error_message']=$ErrorMessage;
												app::import('Model','GroupSmsBlast');
												$arrdata['GroupSmsBlast']['id'] =$contactArr;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												//pr($groupContacts);
												$arrdata['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												//pr($this->data);
												$this->GroupSmsBlast->save($arrdata);
											}
											$this->Log->save($this->data);
										}
									}
									if($sucesscredits > 0){
										$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id;
											$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$contactArr;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
										$this->GroupSmsBlast->save($group_blast);
									}
									$this->smsmail($user_id);
								}
							}
						}
					}else if(API_TYPE==3){
	
					if($rotate_number==1){

							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));			
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
								$from_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['sms']==1){
										$from_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								 $total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									//$total=count($subscriberPhone1);
									$this->data['GroupSmsBlast']['user_id'] =$user_id;
									$this->data['GroupSmsBlast']['group_id'] =$group_id;
									$this->data['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($this->data);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									$k = 0;
									$sucesscredits = 0;
									$credits = 0;
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
											$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
											$space_pos = strpos($contacts['Contact']['name'],' ');
											if($space_pos!=''){
												$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
											}else{
												$contact_name = $contacts['Contact']['name'];
											}					
											$message =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
											$body = $message.' '.$this->data['Message']['systemmsg'];
											$to = $groupContact['Contact']['phone_number'];
											//$from = $users['User']['assigned_number'];
											$countnumber = count($from_arr);
											if($countnumber==$k){
												$k = 0;
												sleep($throttle);
											}
											//$random_keys= array_rand($from_arr,1);
											$from=$from_arr[$k];
											//$from = '2029993169';
											$this->Plivo->AuthId =PLIVO_KEY;
											$this->Plivo->AuthToken =PLIVO_TOKEN;
											$body = $this->process($body);
											$response = $this->Plivo->sendsms($to,$from,$body);
											$errortext = '';
											$message_id = '';
											if(isset($response['response']['error'])){
												$errortext = $response['response']['error'];
											}
											if(isset($response['response']['message_uuid'][0])){
												$message_id = $response['response']['message_uuid'][0];
											}
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$message_id;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											$this->data['Log']['text_message']= $body;
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($message_id!=''){
												$sucesscredits = $sucesscredits + 1;
												$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = $credits + ceil($length/160);
												if (strlen($body) != strlen(utf8_decode($body))){
													$credits = $credits + ceil($length/70);
												}else{
												   $credits = $credits + ceil($length/160);
												}
												$this->data['Log']['sms_status']= 'sent';
												//$this->smsmail($user_id);
											}else if(isset($response['response']['error'])){
												$this->data['Log']['sms_status']= 'failed';
												$ErrorMessage = $errortext;
												$this->data['Log']['error_message']=$ErrorMessage;
												app::import('Model','GroupSmsBlast');
												$this->data['GroupSmsBlast']['id'] =$contactArr;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												$this->GroupSmsBlast->save($this->data);
											}
											$this->Log->save($this->data);
										}
										$k = $k+1;
									}

									if($sucesscredits > 0){
										$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id;
											$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$contactArr;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
										$this->GroupSmsBlast->save($group_blast);
									}
									$this->smsmail($user_id);
								}
							}
						}else{

							$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.sms'=>1)));
                                                     if($alphasenderid != ''){
                                                         $from = $alphasenderid;
                                                     }else{
							if(!empty($usernumber)){
                                                           $from = $usernumber['User']['assigned_number'];
							}else{
								app::import('Model','UserNumber');
								$this->UserNumber = new UserNumber();
								$smsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
								if(!empty($smsnumber)){
									$from= $smsnumber['UserNumber']['number'];
								}else{
									$from = $usernumber['User']['assigned_number'];
								}
							}
                                                      }
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								$total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									$this->data['GroupSmsBlast']['user_id'] =$user_id;
									$this->data['GroupSmsBlast']['group_id'] =$group_id;
									$this->data['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($this->data);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									$sucesscredits = 0;
									$credits = 0;
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
											$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
											$space_pos = strpos($groupContact['Contact']['name'],' ');
											if($space_pos!=''){
												$contact_name=substr($groupContact['Contact']['name'],0,$space_pos);
											}else{
												$contact_name = $groupContact['Contact']['name'];
											}
											$message =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
											$body = $message.' '.$this->data['Message']['systemmsg'];
											$to = $groupContact['Contact']['phone_number'];
											//$from = $users['User']['assigned_number'];
											//$from = '2029993169';
											$this->Plivo->AuthId =PLIVO_KEY;
											$this->Plivo->AuthToken =PLIVO_TOKEN;
											sleep($throttle);
											$body = $this->process($body);
											$response = $this->Plivo->sendsms($to,$from,$body);
											$errortext = '';
											$message_id = '';
											if(isset($response['response']['error'])){
												$errortext = $response['response']['error'];
											}
											if(isset($response['response']['message_uuid'][0])){
												$message_id = $response['response']['message_uuid'][0];
											}	
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$message_id;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											$this->data['Log']['text_message']= $body;
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($message_id!=''){
												$sucesscredits = $sucesscredits + 1;
												$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = $credits + ceil($length/160);
												if (strlen($body) != strlen(utf8_decode($body))){
													$credits = $credits + ceil($length/70);
												}else{
												   $credits = $credits + ceil($length/160);
												}
												$this->data['Log']['sms_status']= 'sent';
												//$this->smsmail($user_id);
											}else if(isset($response['response']['error'])){
												$this->data['Log']['sms_status']= 'failed';
												$ErrorMessage = $errortext;
												$this->data['Log']['error_message']=$ErrorMessage;
												app::import('Model','GroupSmsBlast');
												$arrdata['GroupSmsBlast']['id'] =$contactArr;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												//pr($groupContacts);
												$arrdata['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												//pr($this->data);
												$this->GroupSmsBlast->save($arrdata);
											}
											$this->Log->save($this->data);
										}
									}
									if($sucesscredits > 0){
										$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id;
											$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$contactArr;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
										$this->GroupSmsBlast->save($group_blast);
									}
									$this->smsmail($user_id);
								}
							}
						}
					}else{
						if($rotate_number==1){
							app::import('Model','UserNumber');
							$this->UserNumber = new UserNumber();
							$user_numbers = $this->UserNumber->find('all', array('conditions' => array('UserNumber.user_id'=>$user_id)));			
							$from_arr=array();
							if(!empty($users_arr)){	
								if($users_arr['User']['sms']==1){
								$from_arr[]=$users_arr['User']['assigned_number'];
								}
							}
							if(!empty($user_numbers)){	
								foreach($user_numbers as $values){	
									if($values['UserNumber']['sms']==1){
										$from_arr[]=$values['UserNumber']['number'];
									}				
								}
							}
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								 $total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									//$total=count($subscriberPhone1);
									$this->data['GroupSmsBlast']['user_id'] =$user_id;
									$this->data['GroupSmsBlast']['group_id'] =$group_id;
									$this->data['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($this->data);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									$k = 0;
									$sucesscredits = 0;
									$credits = 0;
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
											$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
											$space_pos = strpos($contacts['Contact']['name'],' ');
											if($space_pos!=''){
												$contact_name=substr($contacts['Contact']['name'],0,$space_pos);
											}else{
												$contact_name = $contacts['Contact']['name'];
											}					
											$message =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
											$body = $message.' '.$this->data['Message']['systemmsg'];
											$to = $groupContact['Contact']['phone_number'];
											//$from = $users['User']['assigned_number'];
											$countnumber = count($from_arr);
											if($countnumber==$k){
												$k = 0;
												sleep($throttle);
											}
											//$random_keys= array_rand($from_arr,1);
											$from=$from_arr[$k];
											//$from = '2029993169';
											$this->Nexmomessage->Key = NEXMO_KEY;
											$this->Nexmomessage->Secret = NEXMO_SECRET;
											$body = $this->process($body);
											$response = $this->Nexmomessage->sendsms($to,$from,$body);
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
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$message_id;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											$this->data['Log']['text_message']= $body;
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($message_id!=''){
												$sucesscredits = $sucesscredits + 1;
												$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = $credits + ceil($length/160);
												if (strlen($body) != strlen(utf8_decode($body))){
													$credits = $credits + ceil($length/70);
												}else{
												   $credits = $credits + ceil($length/160);
												}
												$this->data['Log']['sms_status']= 'sent';
												//$this->smsmail($user_id);
											}else if($status!=0){
												$this->data['Log']['sms_status']= 'failed';
												$ErrorMessage = $errortext;
												$this->data['Log']['error_message']=$ErrorMessage;
												app::import('Model','GroupSmsBlast');
												$this->data['GroupSmsBlast']['id'] =$contactArr;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												$this->data['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												$this->GroupSmsBlast->save($this->data);
											}
											$this->Log->save($this->data);
										}
										$k = $k+1;
									}
									if($sucesscredits > 0){
										$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id;
											$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$contactArr;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
										$this->GroupSmsBlast->save($group_blast);
									}
									$this->smsmail($user_id);
								}
							}
						}else{
							$usernumber = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.sms'=>1)));
			                             if($alphasenderid != ''){
                                                         $from = $alphasenderid;
                                                     }else{				

                                                        if(!empty($usernumber)){
								$from = $usernumber['User']['assigned_number'];
							}else{
								app::import('Model','UserNumber');
								$this->UserNumber = new UserNumber();
								$smsnumber = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1)));
								if(!empty($smsnumber)){
									$from= $smsnumber['UserNumber']['number'];
								}else{
									$from = $usernumber['User']['assigned_number'];
								}
							}
                                                      }
							foreach($this->data['Keyword']['id'] as $groupIds){
								$group_id = $groupIds;
								$message = $this->data['Keyword']['message'];
								app::import('Model','ContactGroup');
								$this->ContactGroup = new ContactGroup();
								//$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0)));
								$groupContacts = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$group_id,'ContactGroup.un_subscribers'=>0),'fields' => array('Contact.name','Contact.phone_number')));
								//pr($groupContacts);
								$total11=count($groupContacts);
								if($total11 > 0){
									app::import('Model','GroupSmsBlast');
									$this->GroupSmsBlast = new GroupSmsBlast();
									$this->data['GroupSmsBlast']['user_id'] =$user_id;
									$this->data['GroupSmsBlast']['group_id'] =$group_id;
									$this->data['GroupSmsBlast']['totals'] =$total11;
									$this->GroupSmsBlast->save($this->data);
									$contactArr = $this->GroupSmsBlast->id;
									$this->Session->write('groupsmsid', $contactArr);
									app::import('Model','Log');
									$sucesscredits = 0;
									$credits = 0;
									foreach($groupContacts as $groupContact){
										$this->Log = new Log();
										if(!isset($phone[$groupContact['Contact']['phone_number']])){
											$phone[$groupContact['Contact']['phone_number']] = $groupContact['Contact']['phone_number'];
											$space_pos = strpos($groupContact['Contact']['name'],' ');
											if($space_pos!=''){
												$contact_name=substr($groupContact['Contact']['name'],0,$space_pos);
											}else{
												$contact_name = $groupContact['Contact']['name'];
											}
											$message =str_replace('%%Name%%',$contact_name,$this->data['Keyword']['message']);
											$body = $message.' '.$this->data['Message']['systemmsg'];
											$to = $groupContact['Contact']['phone_number'];
											//$from = $users['User']['assigned_number'];
											//$from = '2029993169';
											$this->Nexmomessage->Key = NEXMO_KEY;
											$this->Nexmomessage->Secret = NEXMO_SECRET;
											sleep($throttle);
											$body = $this->process($body);
											$response = $this->Nexmomessage->sendsms($to,$from,$body);
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
											$this->data['Log']['id'] ='';
											$this->data['Log']['group_sms_id'] =$contactArr;
											$this->data['Log']['sms_id'] =$message_id;
											$this->data['Log']['user_id'] =$user_id;
											$this->data['Log']['group_id'] =$group_id;
											$this->data['Log']['phone_number']=$groupContact['Contact']['phone_number'];
											$this->data['Log']['text_message']= $body;
											$this->data['Log']['route']= 'outbox';
											$this->data['Log']['sms_status']= '';
											$this->data['Log']['error_message']='';
											if($message_id!=''){
												$sucesscredits = $sucesscredits + 1;
												$length = strlen(utf8_decode(substr($body,0,1600))); 
												//$credits = $credits + ceil($length/160);
												if (strlen($body) != strlen(utf8_decode($body))){
													$credits = $credits + ceil($length/70);
												}else{
												   $credits = $credits + ceil($length/160);
												}
												$this->data['Log']['sms_status']= 'sent';
												//$this->smsmail($user_id);
											}else if($status!=0){
												$this->data['Log']['sms_status']= 'failed';
												$ErrorMessage = $errortext;
												$this->data['Log']['error_message']=$ErrorMessage;
												app::import('Model','GroupSmsBlast');
												$arrdata['GroupSmsBlast']['id'] =$contactArr;
												$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
												//pr($groupContacts);
												$arrdata['GroupSmsBlast']['total_failed_messages']=$groupContacts['GroupSmsBlast']['total_failed_messages']+1;
												//pr($this->data);
												$this->GroupSmsBlast->save($arrdata);
											}
											$this->Log->save($this->data);
										}
									}
									if($sucesscredits > 0){
										$usersbalance = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
										if(!empty($usersbalance)){
											$usercredit['User']['id'] =$user_id;
											$usercredit['User']['sms_balance'] = $usersbalance['User']['sms_balance']-$credits;
											$this->User->save($usercredit);
										}
										app::import('Model','GroupSmsBlast');
										$group_blast['GroupSmsBlast']['id'] =$contactArr;
										$groupContacts = $this->GroupSmsBlast->find('first',array('conditions' => array('GroupSmsBlast.id'=>$contactArr)));
										$group_blast['GroupSmsBlast']['total_successful_messages']=$groupContacts['GroupSmsBlast']['total_successful_messages']+$sucesscredits;
										$this->GroupSmsBlast->save($group_blast);
									}
									$this->smsmail($user_id);
						 
								}
						
							}
						
						}
					}
				}
				$this->Session->setFlash(__('Bulk SMS message has been sent', true));
				//$this->redirect(array('controller' =>'logs', 'action'=>'sentstatistics/'.$contactArr));
				$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
			}else{
				if($this->data['pick']['id']==1){
					app::import('Model','ContactGroup');
					$this->ContactGroup = new ContactGroup();
					$Subscriber = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$this->data['Keyword']['id'])));
					if(empty($Subscriber)){
						$this->Session->setFlash(__('Add contacts to this group or select a different group.', true));
						$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
					} 
					$totalsubscribers= count($Subscriber);
					$body = $this->data['Keyword']['message']." ".$this->data['Message']['systemmsg'];
					$spinbody = $this->process($body);
					$length = strlen(utf8_decode(substr($spinbody,0,1600))); 

					if($this->data['Message']['msg_type']==2){ 
					   $contactcredits = 2; 
					}else{
						if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
						   $contactcredits = ceil($length/70);
						}else{
						   $contactcredits = ceil($length/160);
						}
					}
					if($credits < ($totalsubscribers * $contactcredits)){
						$this->Session->setFlash(__('You do not have enough credits to schedule a message to this group.', true));
						$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
					}
					foreach($Subscriber as $Subscribers){
						app::import('Model','ScheduleMessage');
						$this->ScheduleMessage = new ScheduleMessage();
						$this->data['ScheduleMessage']['user_id'] =$user_id;
						$this->data['ScheduleMessage']['send_on'] =date('Y-m-d H:i:s',strtotime($this->data['User']['shedule']));
						if($this->data['Message']['image'][0]['name']!=''){
							foreach($this->data['Message']['image'] as $value){
								$image=str_replace(' ','_',$value["name"]);	
								move_uploaded_file($value['tmp_name'],"mms/".$image);
							}
							$this->data['ScheduleMessage']['message']=$image_arr;
						}else{		
							$this->data['ScheduleMessage']['message']=$this->data['Keyword']['message'];
						}
						if($this->data['User']['rotate_number']==''){
							$this->data['ScheduleMessage']['rotate_number']=0;
						}else{
							$this->data['ScheduleMessage']['rotate_number']=$this->data['User']['rotate_number'];
						}
						if(isset($this->data['Message']['msg_type'])){
							$this->data['ScheduleMessage']['msg_type']=$this->data['Message']['msg_type'];
						}else{
							$this->data['ScheduleMessage']['msg_type']=1;
						}
						if(isset($this->data['Message']['msg'])){
							$this->data['ScheduleMessage']['mms_text']=$this->data['Message']['msg'];
						}else{
							$this->data['ScheduleMessage']['mms_text']='';
						}
                                                if(isset($this->data['User']['throttle'])){
							$this->data['ScheduleMessage']['throttle']=$this->data['User']['throttle'];
						}else{
							$this->data['ScheduleMessage']['throttle']=1;
						}

                                                if($this->data['Message']['alphasenderid']==''){
							$this->data['ScheduleMessage']['alphasender']=0;
						}else{
							$this->data['ScheduleMessage']['alphasender']=$this->data['Message']['alphasenderid'];
						}

                                                if(isset($this->data['Message']['alphasenderid_input'])){
							$this->data['ScheduleMessage']['alphasender_input']=$this->data['Message']['alphasenderid_input'];
						}else{
							$this->data['ScheduleMessage']['alphasender_input']='';
						}

						$this->data['ScheduleMessage']['systemmsg']=$this->data['Message']['systemmsg'];
						$this->data['ScheduleMessage']['pick_file']=$this->data['Message']['pick_file'];
						$this->ScheduleMessage->save($this->data);
						$scheduleMessageid=$this->ScheduleMessage->id;
						foreach($this->data['Keyword']['id'] as $groupIds){
							$group_id = $groupIds;
							app::import('Model','ScheduleMessageGroup');
							$this->ScheduleMessageGroup = new ScheduleMessageGroup();
							$this->data['ScheduleMessageGroup']['group_id']=$group_id;
							$this->data['ScheduleMessageGroup']['schedule_sms_id']=$scheduleMessageid;
							$this->ScheduleMessageGroup->save($this->data);
						}
						$this->Session->setFlash(__('Group SMS message has been scheduled', true));
						$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));

					}
				}else{
					app::import('Model','Contact');
					$this->Contact = new Contact();
					$Subscribercontact = $this->Contact->find('all',array('conditions' => array('Contact.id'=>$this->data['Contact']['phone'])));
					$totalsubscribers= count($Subscribercontact);

                                        $body = $this->data['Keyword']['message']." ".$this->data['Message']['systemmsg'];
                                        $spinbody = $this->process($body);
                                        $length = strlen(utf8_decode(substr($spinbody,0,1600))); 

                                        if($this->data['Message']['msg_type']==2){ 
                                           $contactcredits = 2; 
                                        }else{ 
                                                                           
                                            if (strlen($spinbody) != strlen(utf8_decode($spinbody))){
                                               $contactcredits = ceil($length/70);
                                            }else{
                                               $contactcredits = ceil($length/160);
                                            }
                                        }

					if($credits < ($totalsubscribers * $contactcredits)){
						$this->Session->setFlash(__('You do not have enough credits to schedule a message to these contacts.', true));
						$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
					}
					if(!empty($Subscribercontact)){
						app::import('Model','ScheduleMessage');
						$this->ScheduleMessage = new ScheduleMessage();
						$this->data['ScheduleMessage']['user_id'] =$user_id;
						$this->data['ScheduleMessage']['send_on'] =date('Y-m-d H:i:s',strtotime($this->data['User']['shedule']));
						if($this->data['Message']['image'][0]['name']!=''){
							foreach($this->data['Message']['image'] as $value){
								$image=str_replace(' ','_',$value["name"]);	
								move_uploaded_file($value['tmp_name'],"mms/".$image);
							}				 
							$this->data['ScheduleMessage']['message']=$image_arr;;

						}else{		
							$this->data['ScheduleMessage']['message']=$this->data['Keyword']['message'];

						}
						if($this->data['User']['rotate_number']==''){
							$this->data['ScheduleMessage']['rotate_number']=0;
						}else{
							$this->data['ScheduleMessage']['rotate_number']=$this->data['User']['rotate_number'];
						}
						if(isset($this->data['Message']['msg_type'])){
							$this->data['ScheduleMessage']['msg_type']=$this->data['Message']['msg_type'];
						}else{
							$this->data['ScheduleMessage']['msg_type']=1;
						}
						if(isset($this->data['Message']['msg'])){
							$this->data['ScheduleMessage']['mms_text']=$this->data['Message']['msg'];
						}else{
							$this->data['ScheduleMessage']['mms_text']='';
						}
                                                if(isset($this->data['User']['throttle'])){
							$this->data['ScheduleMessage']['throttle']=$this->data['User']['throttle'];
						}else{
							$this->data['ScheduleMessage']['throttle']=1;
						}

                                                if($this->data['Message']['alphasenderid']==''){
							$this->data['ScheduleMessage']['alphasender']=0;
						}else{
							$this->data['ScheduleMessage']['alphasender']=$this->data['Message']['alphasenderid'];
						}

                                                if(isset($this->data['Message']['alphasenderid_input'])){
							$this->data['ScheduleMessage']['alphasender_input']=$this->data['Message']['alphasenderid_input'];
						}else{
							$this->data['ScheduleMessage']['alphasender_input']='';
						}

						//$this->data['ScheduleMessage']['mms_text']=$this->data['Message']['msg'];
						$this->data['ScheduleMessage']['pick_file']=$this->data['Message']['pick_file'];
						$this->data['ScheduleMessage']['systemmsg']=$this->data['Message']['systemmsg'];
						if($this->ScheduleMessage->save($this->data));
							$scheduleMessageid=$this->ScheduleMessage->id;
						foreach($Subscribercontact as $Subscribercontacts){
							$contact_id = $Subscribercontacts['Contact']['id'];
							app::import('Model','SingleScheduleMessage');
							$this->SingleScheduleMessage = new SingleScheduleMessage();
							$this->data['SingleScheduleMessage']['contact_id']=$contact_id;
							$this->data['SingleScheduleMessage']['schedule_sms_id']=$scheduleMessageid;
							$this->SingleScheduleMessage->save($this->data);
						}
						$this->Session->setFlash(__('Contacts SMS message has been scheduled', true));
						$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
					}
				}
			}
			/* app::import('Model','SubscriberGroup');
			$this->SubscriberGroup = new SubscriberGroup();
			$Sub = $this->SubscriberGroup->find('all');
			pr($Sub);
			$this->set('Subs',$Sub);
			 */
		/*} else{
		
		
		} */
			
		
		}	


			
	}
		 
	function schedule_message($field=null,$short=null){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','User');
		$this->User = new User();
                $this->User->recursive = -1;
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.mms'=>1)));
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();		  
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);		
		$this->set('users',$users);
		if(!empty($field)){
			if(!isset($short)){
				$short=='asc';
			}
			$order=array();
			if($field){
				$order = array($field.' '.$short);
			}
			app::import('Model','ScheduleMessageGroup');
			$this->ScheduleMessageGroup = new ScheduleMessageGroup();
			$this->paginate = array('conditions' => array('ScheduleMessage.user_id' =>$user_id, 'ScheduleMessage.sent' => 0),'order' => $order);
			$ScheduleMessage1 = $this->paginate('ScheduleMessageGroup');
			//$ScheduleMessage1 = $this->ScheduleMessageGroup->find('all', array('conditions' => array('ScheduleMessage.user_id'=>$user_id,'ScheduleMessage.sent'=>0),'order' => $order,'limit' => 15));

			//print_r($ScheduleMessage1);
			$this->set('ScheduleMessage',$ScheduleMessage1);

			if($short=='asc'){
			$short = 'desc';
			}else{
			$short = 'asc';
			}
			$this->set('sort',$short);
		}else{
			app::import('Model','ScheduleMessageGroup');
			$this->ScheduleMessageGroup = new ScheduleMessageGroup();
			$this->paginate = array('conditions' => array('ScheduleMessage.user_id' =>$user_id, 'ScheduleMessage.sent' => 0),'order' =>array('ScheduleMessage.id' => 'desc'));
			$ScheduleMessage = $this->paginate('ScheduleMessageGroup');
			//$ScheduleMessage = $this->ScheduleMessageGroup->find('all', array('conditions' => array('ScheduleMessage.user_id'=>$user_id,'ScheduleMessage.sent'=>0),'order' => array('ScheduleMessage.id DESC'),'limit' => 20));
			$this->set('ScheduleMessage',$ScheduleMessage);
		}
	}
	function delete($id = null) { 
		app::import('Model','ScheduleMessage');
		$this->ScheduleMessage = new ScheduleMessage();
  		//$this->layout="default";
		$this->ScheduleMessage->delete($id);
		if ($this->ScheduleMessage->delete($id)) {
			$this->Session->setFlash(__('Scheduled Message Deleted', true));
			$this->redirect(array('action'=>'schedule_message'));
		}
		$this->Session->setFlash(__('Scheduled Message Deleted', true));
		$this->redirect(array('action' => 'schedule_message'));
	}
	
	function edit($id=null){
		$this->layout='admin_new_layout';
		$this->set('id',$id);
		$user_id=$this->Session->read('User.id');
		app::import('Model','MobilePage');
        $this->MobilePage = new MobilePage();
		$mobilespage = $this->MobilePage->find('list',array('conditions'=>array('MobilePage.user_id'=>$user_id),'fields'=>'MobilePage.title','order' =>array('MobilePage.title' => 'asc')));
		$this->set('mobilespages', $mobilespage);
		if(!empty($this->data)){
			if($this->data['Message']['new_image'][0]['name']!=''){
				$counter=sizeof($this->data['Message']['new_image']);
				if($counter > 10){
					$this->Session->setFlash(__('Please upload 10 images or less', true));
					$this->redirect(array('controller' =>'messages', 'action'=>'edit/'.$id));
				}
			}
			app::import('Model','ScheduleMessage');
			$this->ScheduleMessage = new ScheduleMessage();
			$this->data['ScheduleMessage']['id'] =$id;
			$this->data['ScheduleMessage']['user_id'] =$user_id;
			$this->data['ScheduleMessage']['send_on'] =date('Y-m-d H:i:s',strtotime($this->data['User']['shedule']));
			if($this->data['Message']['msg_type']==2){
				$image_arr='';
				if($this->data['Message']['new_image'][0]['name']!=''){
					foreach($this->data['Message']['new_image'] as $value){
					$image=str_replace(' ','_',$value["name"]);	
					move_uploaded_file($value['tmp_name'],"mms/".$image);
					if($image_arr!=''){
					$image_arr = $image_arr.','.SITE_URL.'/mms/'.$image;
					}else{
					$image_arr =SITE_URL.'/mms/'.$image;
					}
								
					}
					$this->data['ScheduleMessage']['message']=$image_arr;
				}else{
					$this->data['ScheduleMessage']['message']=$this->data['Message']['image'];
				}
			}else if($this->data['Message']['msg_type']==1){
				$this->data['ScheduleMessage']['message']=$this->data['Keyword']['message'];
			}else{
				$this->data['ScheduleMessage']['message']=$this->data['Keyword']['message'];
			}
			if($this->data['User']['rotate_number']==''){
				$this->data['ScheduleMessage']['rotate_number']=0;
			}else{
				$this->data['ScheduleMessage']['rotate_number']=$this->data['User']['rotate_number'];
			}		
			if(isset($this->data['Message']['msg_type'])){
				$this->data['ScheduleMessage']['msg_type']=$this->data['Message']['msg_type'];
			}
			if(isset($this->data['Message']['mms_text'])){
				$this->data['ScheduleMessage']['mms_text']=$this->data['Message']['mms_text'];
			}
			if($this->data['Message']['pick_file']!=''){
				$this->data['ScheduleMessage']['pick_file']=$this->data['Message']['pick_file'];
			}else{
				$this->data['ScheduleMessage']['pick_file']=$this->data['Message']['pick_old'];
			}	
                        if(isset($this->data['User']['throttle'])){
			   $this->data['ScheduleMessage']['throttle']=$this->data['User']['throttle'];
			}else{
			   $this->data['ScheduleMessage']['throttle']=1;
			}	
			$this->ScheduleMessage->save($this->data);
			$scheduleMessageid=$this->ScheduleMessage->id;
			app::import('Model','ScheduleMessageGroup');
			$this->ScheduleMessageGroup = new ScheduleMessageGroup();
			$this->ScheduleMessageGroup->deleteAll(array('ScheduleMessageGroup.schedule_sms_id'=>$id));
			foreach($this->data['Keyword']['id'] as $groupIds){
				$group_id = $groupIds;
				app::import('Model','ScheduleMessageGroup');
				$this->ScheduleMessageGroup = new ScheduleMessageGroup();
				app::import('Model','ScheduleMessageGroup');
				$this->ScheduleMessageGroup = new ScheduleMessageGroup();
				$this->data['ScheduleMessageGroup']['schedule_sms_id']=$id;
				$this->data['ScheduleMessageGroup']['group_id']=$group_id;
				$this->ScheduleMessageGroup->save($this->data);
			}
			$this->Session->setFlash(__('Scheduled Message Updated', true));
			$this->redirect(array('action' => 'schedule_message'));
		}else{
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			app::import('Model','User');
			$this->User = new User();
                        $this->User->recursive = -1;
			$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id))); 
			$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
			$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
			$this->set('numbers_mms',$numbers_mms);
			$this->set('numbers_sms',$numbers_sms);
			$this->set('users',$users);
			app::import('Model','Group');
			$this->Group = new Group();
			$Group = $this->Group->find('all',array('conditions' => array('Group.user_id'=> $user_id),'order' =>array('Group.group_name' => 'asc')));
			$this->set('Group',$Group);
			app::import('Model','ScheduleMessage');
		    $this->ScheduleMessage = new ScheduleMessage();
			$ScheduleMessage = $this->ScheduleMessage->read(null, $id);
			$this->set('ScheduleMessage',$ScheduleMessage);
			app::import('Model','ScheduleMessageGroup');
			$this->ScheduleMessageGroup = new ScheduleMessageGroup();
			$message = $this->ScheduleMessageGroup->find('all',array('conditions' => array('ScheduleMessage.user_id'=> $user_id,'ScheduleMessage.id'=> $id)));
			$this->set('message',$message);
			foreach($message as $message){
				$groupid[$message['Group']['id']] = $message['Group']['group_name'];
			}
			//print_r($groupid);
			$this->set('groupid', $groupid);
			$user_id=$this->Session->read('User.id');
			app::import('Model','Smstemplate');
			$this->Smstemplate = new Smstemplate();
			$Smstemplate = $this->Smstemplate->find('list',array('conditions'=>array('Smstemplate.user_id'=>$user_id),'fields'=>'Smstemplate.messagename','order' =>array('Smstemplate.messagename' => 'asc')));
			$this->set('Smstemplate',$Smstemplate);
		}

	}
   
	function smstemplate(){
		//pr($this->data);
		$this->layout='admin_new_layout';
		$user_id=$this->Session->read('User.id');
		if (!empty($this->data)){
			app::import('Model','Smstemplate');
			$this->Smstemplate = new Smstemplate();
			$this->data['Smstemplate']['user_id']=$user_id;
			$this->data['Smstemplate']['messagename']=$this->data['Message']['messagename'];
			$this->data['Smstemplate']['message_template']=$this->data['Message']['message_template'];
			$this->data['Smstemplate']['created']=date('Y-m-d H:i:s',time());
			$this->Smstemplate->save($this->data);
			$this->Session->setFlash(__('Message has been saved', true));
			$this->redirect(array('action' => 'smstemplate'));

		} 
	}
   
	function template_message(){
		$this->layout='admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','Smstemplate');
		$this->Smstemplate = new Smstemplate();
		$Smstemplate = $this->Smstemplate->find('all',array('conditions' => array('Smstemplate.user_id'=> $user_id),'order' =>array('Smstemplate.id' => 'desc')));
		 $this->set('Smstemplate',$Smstemplate);
	} 
   
	function template_delete($id=null){
		app::import('Model','Smstemplate');
        $this->Smstemplate = new Smstemplate();
		if ($this->Smstemplate->delete($id)) {
			$this->Session->setFlash(__('Template Message Deleted', true));
			$this->redirect(array('action'=>'template_message'));
		}
		$this->Session->setFlash(__('Template Message Deleted', true));
		$this->redirect(array('action' => 'template_message'));
	}
	function checktemplatedata($templateid=null){
		$this->autoRender = false;
		$user_id=$this->Session->read('User.id');
		app::import('Model','Smstemplate');
		$this->Smstemplate = new Smstemplate();
		$checktemplatedata = $this->Smstemplate->find('first',array('conditions' => array('Smstemplate.id'=> $templateid,'Smstemplate.user_id'=>$user_id)));
		echo $checktemplatedata['Smstemplate']['message_template'];
		  //$this->set('checktemplatedata',$checktemplatedata);
	
	}
	
	function edit_smstemplate($id=null){
		$this->layout='admin_new_layout';
		$this->set('id',$id);
		$user_id=$this->Session->read('User.id');
		if (!empty($this->data)){
			app::import('Model','Smstemplate');
			$this->Smstemplate = new Smstemplate();
			$this->data['Smstemplate']['id']=$id;
			$this->data['Smstemplate']['user_id']=$user_id;
			$this->data['Smstemplate']['messagename']=$this->data['Smstemplate']['messagename'];
			$this->data['Smstemplate']['message_template']=$this->data['Smstemplate']['message_template'];
			$this->Smstemplate->save($this->data);
			$this->Session->setFlash(__('Template message has been updated', true));
			$this->redirect(array('action' => 'template_message'));
		}else{
			app::import('Model','Smstemplate');
			$this->Smstemplate = new Smstemplate();
			$edittemplate = $this->Smstemplate->find('first',array('conditions' => array('Smstemplate.id'=> $id,'Smstemplate.user_id'=>$user_id)));
			$this->set('edittemplate',$edittemplate);
		}
	}
	
	function mobile_pages($id){
		$this->autoRender = false;
		if(!empty($id)){
			$siteurl=SITE_URL;
			$base64 = base64_encode($id);
			$changeid = str_replace('=' ,'', $base64);
			$pageid= str_replace('+' ,'', $changeid );
			echo  $siteurl.'/lp.php?id='.$pageid;
		}
	}
	
	function editmobile_pages($id){
		$this->autoRender = false;
		if(!empty($id)){
			$siteurl=SITE_URL;
			//$pageid=base64_encode($id);
			$base64 = base64_encode($id);
			$changeid = str_replace('=' ,'', $base64);
			$pageid= str_replace('+' ,'', $changeid );
			echo  $siteurl.'/lp.php?id='.$pageid;
		
		}
	}
	function singlemessages($field=null,$short=null){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','User');
		$this->User = new User();
                $this->User->recursive = -1;
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.mms'=>1)));
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();		  
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);		
		$this->set('users',$users);
		if(!empty($field)){
			if(!isset($short)){
				$short=='asc';
			}
			$order=array();
			if($field){
				$order = array($field.' '.$short);
			}
			app::import('Model','SingleScheduleMessage');
			$this->SingleScheduleMessage = new SingleScheduleMessage();
			$this->paginate = array('conditions' => array('ScheduleMessage.user_id' =>$user_id, 'ScheduleMessage.sent' => 0),'order' => $order);
			$ScheduleMessage1 = $this->paginate('SingleScheduleMessage');
			//$ScheduleMessage1 = $this->SingleScheduleMessage->find('all', array('conditions' => array('ScheduleMessage.user_id'=>$user_id,'ScheduleMessage.sent'=>0),'order' => $order,'limit' => 15));
			//print_r($ScheduleMessage1);
			$this->set('ScheduleMessage',$ScheduleMessage1);
			if($short=='asc'){
				$short = 'desc';
			}else{
				$short = 'asc';
			}
			$this->set('sort',$short);
		}else{
			app::import('Model','SingleScheduleMessage');
			$this->SingleScheduleMessage = new SingleScheduleMessage();
			$this->paginate = array('conditions' => array('ScheduleMessage.user_id' =>$user_id, 'ScheduleMessage.sent' => 0),'order' =>array('ScheduleMessage.id' => 'desc'));
			$ScheduleMessage = $this->paginate('SingleScheduleMessage');
			$this->set('ScheduleMessage',$ScheduleMessage);
		}
		 
	}
	function singlesmsdelete($id = null){ 
		app::import('Model','SingleScheduleMessage');
		$this->SingleScheduleMessage = new SingleScheduleMessage();
  		//$this->layout="default";
		$this->SingleScheduleMessage->delete($id);
		if ($this->SingleScheduleMessage->delete($id)) {
			$this->Session->setFlash(__('Single Scheduled Message Deleted', true));
			$this->redirect(array('action'=>'singlemessages'));
		}
		$this->Session->setFlash(__('Single Scheduled Message Deleted', true));
		$this->redirect(array('action' => 'singlemessages'));

	}
	function edit_singlemessage($id=null){
		$this->layout='admin_new_layout';
		$this->set('id',$id);
		$user_id=$this->Session->read('User.id');
		app::import('Model','MobilePage');
		$this->MobilePage = new MobilePage();
		$mobilespage = $this->MobilePage->find('list',array('conditions'=>array('MobilePage.user_id'=>$user_id),'fields'=>'MobilePage.title','order' =>array('MobilePage.title' => 'asc')));
		$this->set('mobilespages', $mobilespage);
		if(!empty($this->data)){	
			if($this->data['Message']['new_image'][0]['name']!=''){
				$counter=sizeof($this->data['Message']['new_image']);
				if($counter > 10){
				$this->Session->setFlash(__('You can not upload more than 10 images', true));
				$this->redirect(array('controller' =>'messages', 'action'=>'edit_singlemessage/'.$this->data['Message']['id']));

				}
			}
			app::import('Model','ScheduleMessage');
			$this->ScheduleMessage = new ScheduleMessage();
			$this->data['ScheduleMessage']['id'] =$this->data['Message']['id'];
			$this->data['ScheduleMessage']['user_id'] =$user_id;
			$this->data['ScheduleMessage']['send_on'] =date('Y-m-d H:i:s',strtotime($this->data['User']['shedule']));
			if($this->data['Message']['msg_type']==2){
				$image_arr='';
				if($this->data['Message']['new_image'][0]['name']!=''){
					foreach($this->data['Message']['new_image'] as $value){
						$image=str_replace(' ','_',$value["name"]);	
						move_uploaded_file($value['tmp_name'],"mms/".$image);
					if($image_arr!=''){
						$image_arr = $image_arr.','.SITE_URL.'/mms/'.$image;
					}else{
						$image_arr =SITE_URL.'/mms/'.$image;
					
					}
					}
					$this->data['ScheduleMessage']['message']=$image_arr;
		
				}else{
					$this->data['ScheduleMessage']['message']=$this->data['Message']['image'];
				}
			}else if($this->data['Message']['msg_type']==1){
				$this->data['ScheduleMessage']['message']=$this->data['Keyword']['message'];
			}else{
				$this->data['ScheduleMessage']['message']=$this->data['Keyword']['message'];
			}
			if($this->data['User']['rotate_number']==''){
				$this->data['ScheduleMessage']['rotate_number']=0;
			}else{
				$this->data['ScheduleMessage']['rotate_number']=$this->data['User']['rotate_number'];
			}	
			if(isset($this->data['Message']['msg_type'])){
				$this->data['ScheduleMessage']['msg_type']=$this->data['Message']['msg_type'];
			}
			if(isset($this->data['Message']['mms_text'])){
				$this->data['ScheduleMessage']['mms_text']=$this->data['Message']['mms_text'];
			}
                        if(isset($this->data['User']['throttle'])){
			   $this->data['ScheduleMessage']['throttle']=$this->data['User']['throttle'];
			}else{
			   $this->data['ScheduleMessage']['throttle']=1;
			}	
			$this->data['ScheduleMessage']['pick_file']=$this->data['Message']['pick_file'];
			$this->ScheduleMessage->save($this->data);
			$scheduleMessageid=$this->ScheduleMessage->id;
			app::import('Model','SingleScheduleMessage');
			$this->SingleScheduleMessage = new SingleScheduleMessage();
			/*$this->SingleScheduleMessage->deleteAll(array('SingleScheduleMessage.schedule_sms_id'=>$id));
			foreach($this->data['Keyword']['id'] as $contact_id){
				$contact_id = $contact_id;
				app::import('Model','SingleScheduleMessage');
				$this->SingleScheduleMessage = new SingleScheduleMessage();
				$this->data['SingleScheduleMessage']['schedule_sms_id']=$id;
				$this->data['SingleScheduleMessage']['contact_id']=$contact_id;
				$this->SingleScheduleMessage->save($this->data);
			}*/
			$this->Session->setFlash(__('Scheduled Message Updated', true));
			$this->redirect(array('action' => 'singlemessages'));
		}else{
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			app::import('Model','User');
			$this->User = new User();
                        $this->User->recursive = -1;
			$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id))); 
			$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
			$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
			$this->set('numbers_mms',$numbers_mms);
			$this->set('numbers_sms',$numbers_sms);
			$this->set('users',$users);
			$Subscribercountfind = $this->ContactGroup->find('all',array('conditions'=>array('ContactGroup.user_id' =>$user_id)));
			/* echo"<pre>";
			print_r($Subscribercountfind);
			echo"</pre>"; */
			//$ContactGroup = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.user_id'=> $user_id),'order' =>array('ContactGroup.id' => 'asc')));
			$this->set('contacts',$Subscribercountfind);
			app::import('Model','ScheduleMessage');
			$this->ScheduleMessage = new ScheduleMessage();
			$ScheduleMessage = $this->ScheduleMessage->read(null, $id);
			$this->set('ScheduleMessage',$ScheduleMessage);
			app::import('Model','SingleScheduleMessage');
			$this->SingleScheduleMessage = new SingleScheduleMessage();
			$message = $this->SingleScheduleMessage->find('all',array('conditions' => array('ScheduleMessage.user_id'=> $user_id,'ScheduleMessage.id'=> $id)));
			$this->set('message',$message);
			foreach($message as $message){
				$contactid[$message['Contact']['id']] = $message['SingleScheduleMessage']['contact_id'];
			}
			//print_r($contactid);
			$this->set('contactid', $contactid);
			$user_id=$this->Session->read('User.id');
			app::import('Model','Smstemplate');
			$this->Smstemplate = new Smstemplate();
			$Smstemplate = $this->Smstemplate->find('list',array('conditions'=>array('Smstemplate.user_id'=>$user_id),'fields'=>'Smstemplate.messagename','order' =>array('Smstemplate.messagename' => 'asc')));
			$this->set('Smstemplate',$Smstemplate);
		}

	}
   
	function send_message_twitter(){
		$this->autoRender = false;
		$this->Twitter->ConsumerKey = TWITTER_CONSUMER_KEY;
		$this->Twitter->ConsumerSecret = TWITTER_CONSUMER_SECRET;
		$request_token = $this->Twitter->login();
		if(isset($request_token['oauth_token'])){
		  $token=array('oauth_token'=>$request_token['oauth_token'],'oauth_token_secret'=>$request_token['oauth_token_secret']);
		  $this->Session->write('Token', $token);
		  $response = $this->Twitter->getAuthorizeURLlogin($request_token['oauth_token']);
		  if(!empty($response)){
		  $this->redirect($response);
		  }
		}else{
			$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
		}
   
	}
	function responsetwitter(){
		$this->autoRender = false;
		$twittersession = $this->Session->read('Token');
		$ConsumerKey = TWITTER_CONSUMER_KEY;
		$ConsumerSecret = TWITTER_CONSUMER_SECRET;
		$oauth_token = $twittersession['oauth_token'];
		$oauth_token_secret = $twittersession['oauth_token_secret'];
		$response = $this->Twitter->twitter_login_details($ConsumerKey,$ConsumerSecret,$oauth_token,$oauth_token_secret,$_REQUEST['oauth_verifier']);
		if(isset($response['user_id'])){
			$this->Session->write('TwitterSuccess',$response);
		}
		$this->Session->setFlash(__('Your twitter account login process is complete.', true));
		$this->redirect(array('controller' =>'messages', 'action'=>'send_message'));
	}
   
	function posttweet($message){
        $this->autoRender = false;
   		$twittersession = $this->Session->read('TwitterSuccess');
   		$ConsumerKey = TWITTER_CONSUMER_KEY;
		$ConsumerSecret = TWITTER_CONSUMER_SECRET;
		$oauth_token = $twittersession['oauth_token'];
		$oauth_token_secret = $twittersession['oauth_token_secret'];
		$response = $this->Twitter->postmessage($ConsumerKey,$ConsumerSecret,$oauth_token,$oauth_token_secret,$message);
	}
	function smsmail($user_id=null){
		$this->autoRender=false;
		app::import('Model','User');
		$this->User = new User();
                $this->User->recursive = -1;
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
    function process($text){
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    function replace($text) {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }

	function spinhelp(){
		$this->layout ='popup';
	}
	function nongsm() {
	 $this->layout ='popup';
	}
	function copygroupschedule($schedule_id=null,$weekly=null){
		app::import('Model','ScheduleMessageGroup');
		$this->ScheduleMessageGroup = new ScheduleMessageGroup();
		$groupschedule = $this->ScheduleMessageGroup->find('first',array('conditions' => array('ScheduleMessageGroup.schedule_sms_id'=>$schedule_id)));
		app::import('Model','ScheduleMessage');
		$this->ScheduleMessage = new ScheduleMessage();
		$groupschedulemsg = $this->ScheduleMessage->find('first',array('conditions' => array('ScheduleMessage.id'=>$schedule_id)));
		$user_id=$this->Session->read('User.id');
		$this->data['ScheduleMessage']['user_id'] =$user_id;
            if ($weekly == 1){
                $send_on = strtotime(date("Y-m-d H:i:s", strtotime($groupschedulemsg['ScheduleMessage']['send_on'])) . " +1 week");
                $this->data['ScheduleMessage']['send_on'] = date("Y-m-d H:i:s", $send_on);
            }else{
                $send_on = strtotime(date("Y-m-d H:i:s", strtotime($groupschedulemsg['ScheduleMessage']['send_on'])) . " +1 month");
                $this->data['ScheduleMessage']['send_on'] = date("Y-m-d H:i:s", $send_on);
            }
			$this->data['ScheduleMessage']['send_on'] = date("Y-m-d H:i:s", $send_on);
			$this->data['ScheduleMessage']['message']  = $groupschedulemsg['ScheduleMessage']['message'];
			$this->data['ScheduleMessage']['systemmsg'] = $groupschedulemsg['ScheduleMessage']['systemmsg'];
			$this->data['ScheduleMessage']['sent'] = $groupschedulemsg['ScheduleMessage']['sent'];
			$this->data['ScheduleMessage']['rotate_number'] = $groupschedulemsg['ScheduleMessage']['rotate_number'];
			$this->data['ScheduleMessage']['msg_type'] = $groupschedulemsg['ScheduleMessage']['msg_type'];
			$this->data['ScheduleMessage']['mms_text'] = $groupschedulemsg['ScheduleMessage']['mms_text'];
			$this->data['ScheduleMessage']['pick_file'] = $groupschedulemsg['ScheduleMessage']['pick_file'];
			$this->ScheduleMessage->save($this->data);
			$scheduleMessageid=$this->ScheduleMessage->id;
			app::import('Model','ScheduleMessageGroup');
			$this->ScheduleMessageGroup = new ScheduleMessageGroup();
			$this->data['ScheduleMessageGroup']['group_id']=$groupschedule['ScheduleMessageGroup']['group_id'];
			$this->data['ScheduleMessageGroup']['schedule_sms_id']=$scheduleMessageid;
			$this->ScheduleMessageGroup->save($this->data);
			$this->Session->setFlash(__('Group SMS scheduled message has been copied', true));
			$this->redirect(array('controller' =>'messages', 'action'=>'schedule_message'));
	}

	function copycontactschedule($schedule_id=null,$weekly=null){
		app::import('Model','SingleScheduleMessage');
		$this->SingleScheduleMessage = new SingleScheduleMessage();
		$singleschedule = $this->SingleScheduleMessage->find('first',array('conditions' => array('SingleScheduleMessage.schedule_sms_id'=>$schedule_id)));
		app::import('Model','ScheduleMessage');
		$this->ScheduleMessage = new ScheduleMessage();
		$singleschedulemsg = $this->ScheduleMessage->find('first',array('conditions' => array('ScheduleMessage.id'=>$schedule_id)));
        $user_id=$this->Session->read('User.id');
        $this->data['ScheduleMessage']['user_id'] =$user_id;
        if ($weekly == 1){
			$send_on = strtotime(date("Y-m-d H:i:s", strtotime($singleschedulemsg['ScheduleMessage']['send_on'])) . " +1 week");
			$this->data['ScheduleMessage']['send_on'] = date("Y-m-d H:i:s", $send_on);
        }else{
			$send_on = strtotime(date("Y-m-d H:i:s", strtotime($singleschedulemsg['ScheduleMessage']['send_on'])) . " +1 month");
			$this->data['ScheduleMessage']['send_on'] = date("Y-m-d H:i:s", $send_on);
		}
		$this->data['ScheduleMessage']['send_on'] = date("Y-m-d H:i:s", $send_on);
		$this->data['ScheduleMessage']['message']  = $singleschedulemsg['ScheduleMessage']['message'];
		$this->data['ScheduleMessage']['systemmsg'] = $singleschedulemsg['ScheduleMessage']['systemmsg'];
		$this->data['ScheduleMessage']['sent'] = $singleschedulemsg['ScheduleMessage']['sent'];
		$this->data['ScheduleMessage']['rotate_number'] = $singleschedulemsg['ScheduleMessage']['rotate_number'];
		$this->data['ScheduleMessage']['msg_type'] = $singleschedulemsg['ScheduleMessage']['msg_type'];
		$this->data['ScheduleMessage']['mms_text'] = $singleschedulemsg['ScheduleMessage']['mms_text'];
		$this->data['ScheduleMessage']['pick_file'] = $singleschedulemsg['ScheduleMessage']['pick_file'];
		$this->ScheduleMessage->save($this->data);
		$scheduleMessageid=$this->ScheduleMessage->id;
		$this->data['SingleScheduleMessage']['contact_id']=$singleschedule['SingleScheduleMessage']['contact_id'];
		$this->data['SingleScheduleMessage']['schedule_sms_id']=$scheduleMessageid;
		$this->SingleScheduleMessage->save($this->data);
	    $this->Session->setFlash(__('Contact SMS scheduled message has been copied', true));
	    $this->redirect(array('controller' =>'messages', 'action'=>'singlemessages'));

	}
}