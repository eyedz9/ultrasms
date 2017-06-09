<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('Captcha','Cookie','Email','Twilio','Paginationclass','Qr','Checkout','Expresscheckout');

	
	function captcha_check($userCode){
		//print_r($this->params['form']['userCode']);
		//print_r($_SESSION);
		return $this->Captcha->check($userCode);
		
	}
	function payment(){
		$this->layout = 'default';
		// echo '<pre>';
		// print_r($_REQUEST);
		// echo '</pre>';
		$response = $this->Checkout->signup();
		/* echo '<pre>';
		print_r($response);
		echo '</pre>'; */
	
	}
	function returnurl(){
	
	ob_start();
	
	print_r($_REQUEST);
	
	
					$this->autoRender = false;
						
					$user_id=$this->Session->read('User.id');
					
					app::import('Model','Config');
					
	                $this->Config = new Config();
					
		            $configdata=$this->Config->find('first');
					
					app::import('Model','Package');
					
					$this->Package=new Package();
					
					//$_REQUEST['product_id']=2;
					
					//$_REQUEST['credit_card_processed']='Y';
					
					$Packageid = $this->Package->find('first',array('conditions' => array('Package.product_id'=>$_REQUEST['product_id'])));
					
					$balance = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
					
	
	if($_REQUEST['credit_card_processed']=='Y'){
	
	if($_REQUEST['product_id']==$configdata['Config']['2CO_account_activation_prod_ID']){
	    	  
			        $this->data['User']['id']=$user_id;
					
					$this->data['User']['sms_balance']=$configdata['Config']['free_sms'];
					
					$this->data['User']['voice_balance']=$configdata['Config']['free_voice'];
					
					$this->data['User']['active']=1;
					
					$this->User->save($this->data);
					
					//-------------------------------------------------------------------------------//
					//Activation mail function
					
					$sitename=str_replace(' ','',SITENAME);
					
					$this->Email->to = $balance['User']['email'];
					
					$this->Email->subject = 'Account has been activated';
					
					$this->Email->from = $sitename;
					
					$this->Email->template = 'membership'; // note no '.ctp'
					
					//Send as 'html', 'text' or 'both' (default is 'text')
					
					$this->Email->sendAs = 'html'; // because we like to send pretty mail
					
					//Set view variables as normal
					
					$this->set('data', $balance);
					
					//Do not pass any args to send()
					
					$this->Email->send();
					
					//------------------------------------------------------------------//
			  
			        $this->Session->setFlash(__('Thank you for activating your account with us.', true));  
			  
		          
	}else if(!empty($Packageid)){
				
				/*  echo "<pre>";
				print_r($Packageid);
				echo "</pre>"; */
				
	if($Packageid['Package']['type']=='text'){
				
				    $this->data['User']['id']=$user_id;
					
					$this->data['User']['sms_balance']=$balance['User']['sms_balance']+$Packageid['Package']['credit'];
					
					$this->data['User']['sms_credit_balance_email_alerts']=0;
					
					$this->User->save($this->data);
					
					$this->Session->setFlash(__('Thank you for your SMS credit package purchase!', true)); 
				
				
	}else if($Packageid['Package']['type']=='voice'){
				
				    $this->data['User']['id']=$user_id;
					
					$this->data['User']['voice_balance']=$balance['User']['voice_balance']+$Packageid['Package']['credit'];
					
					$this->data['User']['VM_credit_balance_email_alerts']=0;
					
					$this->User->save($this->data);
					
					$this->Session->setFlash(__('Thank you for your voice credit package purchase!', true));
				
				
				   }
				
				}
			}
			
			$out1 = ob_get_contents();
			ob_end_clean();
			$file = fopen("payment/payments".time().".txt", "w");
			fwrite($file, $out1); 
			fclose($file);
			
			
			//redirect to profile page
			 $this->redirect(array('controller' =>'users', 'action' => 'profile'));
				
		}		
				
	function checksale(){
		$this->autoRender = false;
		$response = $this->Checkout->checksale();
		/* echo '<pre>';
		print_r($response);
		echo '</pre>'; */
	}
		
	function captcha_image()
	{
		$this->Captcha->image();
		
	}
		
	function captcha_audio()
	{
		$this->Captcha->audio();
	}

	
	function home(){
		$this->set('title_for_layout', 'Home');
		$this->layout= 'home';
	}
	/*User Login*/
	function login(){ 
	$this->set('title_for_layout', 'Login');
		$this->layout = 'default';
		if(!empty($this->data)){
			$this->User->set($this->data);
			$this->User->validationSet = 'Userlogin';
			if ($this->User->validates()) 
			{
				$someone = $this->User->find('first', array('conditions' => array('username' =>$this->data['User']['usrname'])));
				
				if($someone['User']['register']==1){
				if(!empty($someone['User']['password']) && $someone['User']['password'] == md5($this->data['User']['passwrd']))
	            {
	                $this->Session->write('User', $someone['User']);
					if($someone['User']['active'] == 0){
						$this->redirect(array('controller' =>'users', 'action' => 'dashboard'));
					}else{
						$this->redirect(array('controller' =>'users', 'action' => 'profile'));
					}	
	            }

	            // Else, they supplied incorrect data:
	            else
	            {
	                $this->Session->setFlash('Username or Password Wrong'); 				
				}
				}else{
				
				$this->Session->setFlash('You must complete the register process before logging in.');
				
				}
				
				
			}else{
	                $this->Session->setFlash('Username or Password Wrong'); 				
			}
		}//this->data
	}
	
	function activation($id=null)
	{
	$this->set('id',$id);
	
	    app::import('Model','Config');
		
	    $this->Config = new Config();
		
		$configdata=$this->Config->find('first');
		
		$this->set('config',$configdata);
	}
	
	function thank_you(){
		//print_r($_POST);exit;
	}
	
	/*
	User frontend Logout
	*/
	function logout()
	{
		$this->Session->delete('User');
	
		$this->redirect('/');
	}
	
	function forgot_password()
	{
		$this->set('title_for_layout', 'Forgot Password');
		if(!empty($this->data))
		{
			if($this->data['User']['email'] !="")
			{
				 $someone=$this->User->find('first', array('conditions' => array('User.email' =>$this->data['User']['email'])));
				if(!empty($someone))
				{
					$email = $this->data['User']['email'];
					$id = $someone['User']['id'];
					$sitename=str_replace(' ','',SITENAME);
					$first_name = $someone['User']['first_name'];
					$subject=SITENAME." :: Forgot Password";	
					$comment= SITE_URL."/users/pwdreset/".$id."/".$someone['User']['password'];
					#$this->Email->delivery = 'debug';	
					$this->Email->to = $email;	
					$this->Email->subject = $subject;
					$this->Email->from = $sitename;
					$this->Email->template = 'forgot_password';
					$this->Email->sendAs = 'html';
					$this->Email->Controller->set('first_name', $first_name);
					$this->Email->Controller->set('comment', $comment);
					$this->Email->Controller->set('email', $email);
					$this->Email->send();
					$this->Session->setFlash('Your account information has been sent to your email id');
					
					$this->redirect(array('controller' =>'users', 'action'=>'login'));
					
				}
				else
				{
					$this->Session->setFlash('We do not have any accounts registered with that email.');
				}	
			}
			else
			{
				$this->User->invalidate('email', 'Please enter your Email id');
			}
		}
	}//fun
	
	
	
		function pwdreset($id, $code)
		{
			$someone=$this->User->find('first', array('conditions' => array('User.id' => $id, 'User.password' => $code)));
			//print_r($someone);
			if(!empty($someone))
			{
				/*****************************Random number**********************************************/
					function random_generator($digits)
					{
						srand ((double) microtime() * 10000000);
						//Array of alphabets
						$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q",
						"R","S","T","U","V","W","X","Y","Z");

						$random_generator="";// Initialize the string to store random numbers
						for($i=1;$i<$digits+1;$i++)
						{ // Loop the number of times of required digits
						if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
							// Add one random alphabet
							$rand_index = array_rand($input);
							$random_generator .=$input[$rand_index]; // One char is added

							}
							else
							{
								// Add one numeric digit between 1 and 10
								$random_generator .=rand(1,10); // one number is added
							} // end of if else

						} // end of for loop

						return $random_generator;
					} // end of function
				/******************************************************************************************/

					 	$random_number= random_generator(10);
						$username = $someone['User']['username'];
						$email = $someone['User']['email'];
						$first_name = $someone['User']['first_name'];
						 $sitename=str_replace(' ','',SITENAME);
						$subject=SITENAME." :: New Password";	
						#$this->Email->delivery = 'debug';	
						$this->Email->to = $email;	
						$this->Email->subject = $subject;
						$this->Email->from = $sitename;
						$this->Email->template = 'pwdreset';
						$this->Email->sendAs = 'html';
						$this->Email->Controller->set('first_name', $first_name);
						$this->Email->Controller->set('username', $username);
						$this->Email->Controller->set('password', $random_number);
						$this->Email->Controller->set('email', $email);
						$this->Email->send();
						
						$this->User->id = $id;
						$this->User->saveField('password', md5("$random_number"));
            			$this->Session->setFlash('Your Password information has been sent to your email id');
						
						$this->redirect(array('controller' =>'users', 'action'=>'login'));
			}
			else
			{
				$this->Session->setFlash('Invalid information');
				$this->redirect(array('controller' =>'users', 'action'=>'login'));
			}
		
		}//fun

	function add() {
	$this->set('title_for_layout', 'Register');
		if (!empty($this->data)) {
		function random_generator($digits)
					{
						srand ((double) microtime() * 10000000);
						//Array of alphabets
						$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q",
						"R","S","T","U","V","W","X","Y","Z");

						$random_generator="";// Initialize the string to store random numbers
						for($i=1;$i<$digits+1;$i++)
						{ // Loop the number of times of required digits
						if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
							// Add one random alphabet
							$rand_index = array_rand($input);
							$random_generator .=$input[$rand_index]; // One char is added

							}
							else
							{
								// Add one numeric digit between 1 and 10
								$random_generator .=rand(1,10); // one number is added
							} // end of if else

						} // end of for loop

						return $random_generator;
					} // end of function
		
			$this->User->create();
			$random_number= random_generator(4);
			$this->data['User']['password'] = md5($this->data['User']['passwrd']);
			$this->data['User']['email_alert_options'] = 1;
			$this->data['User']['email_alert_credit_options'] = 1;
			$this->data['User']['account_activated'] = $random_number;
			
				
			if ($this->User->save($this->data)) {
				
			          $sitename=str_replace(' ','',SITENAME);
				     
					
					$subject=SITENAME." :: Register Account";	
					$url= SITE_URL."/users/user_activate_account/".$random_number;
					#$this->Email->delivery = 'debug';	
					$this->Email->to = $this->data['User']['email'];	
					$this->Email->subject = $subject;
					$this->Email->from = $sitename;
					$this->Email->template = 'account_login';
					$this->Email->sendAs = 'html';
					$this->Email->Controller->set('username', $this->data['User']['username']);
					$this->Email->Controller->set('password', $this->data['User']['passwrd']);
					$this->Email->Controller->set('url', $url);
					$this->Email->Controller->set('email', $email);
					$this->Email->send();
					
				     $last_id = $this->User->getLastInsertId();
				
				    
				$referral = $this->Cookie->read('refArray');
				if(!empty($referral)){
					$this->loadModel('Referral');
					$refData = array('Referral' =>array('user_id' =>$last_id,'referred_by' =>$referral['id'], 'url' =>$referral['url'], 'amount' =>REFERRAL_AMOUNT));
					$this->Referral->save($refData);
					$this->Cookie->delete('refArray');
				}
				$this->Session->setFlash(__('You have been saved as a user.', true));
				$this->redirect(array('action' => 'success'));
			} 
		}
	}
	
	function user_activate_account($id=null){
	
	$this->autoRender=false;
	
	if(!empty($id)){
	
	app::import('Model','User');
	   
    $this->User = new User();
	
	$activate_user = $this->User->find('first', array('conditions' => array('User.account_activated' =>$id)));
	                    $sitename=str_replace(' ','',SITENAME);
							
						$subject= "New User Registered at ".SITENAME;	
						#$this->Email->delivery = 'debug';	
						$this->Email->to = SUPPORT_EMAIL;	
						$this->Email->subject = $subject;
						$this->Email->from = $sitename;
						$this->Email->template = 'new_user_registered';
						$this->Email->sendAs = 'html';
					     $this->Email->Controller->set('username', $activate_user['User']['username']);
						 $this->Email->Controller->set('firstname', $activate_user['User']['first_name']);
						 $this->Email->Controller->set('lastname', $activate_user['User']['last_name']);
						 
						 
						$this->Email->Controller->set('email', $activate_user['User']['email']);
						$this->Email->send();
						
						 $pay_activation_fee=PAY_ACTIVATION_FEES;
					
						if($pay_activation_fee==0){
						app::import('Model','User');
	   
                        $this->User = new User();
						
						$activationfees['id'] = $activate_user['User']['id'];
						$activationfees['active'] =1;
						$activationfees['sms_balance'] =FREE_SMS;
						$activationfees['voice_balance'] =FREE_VOICE;
						
						$this->User->save($activationfees);
						}
						
						
	                    $subject=SITENAME." :: Account Registered Successfully";	
						#$this->Email->delivery = 'debug';	
						$this->Email->to = $activate_user['User']['email'];	
						$this->Email->subject = $subject;
						$this->Email->from = $sitename;
						$this->Email->template = 'activate_sucessfully';
						$this->Email->sendAs = 'html';
					     $this->Email->Controller->set('username', $activate_user['User']['username']);
						$this->Email->Controller->set('email', $activate_user['User']['email']);
						$this->Email->send();
						
						$this->User->id = $activate_user['User']['id'];
						$this->User->saveField('register',1);
            			$this->Session->setFlash('Your account has been successfully registered');
						
						$this->redirect(array('controller' =>'users', 'action'=>'login'));
	
	
	}
	
	
	}
	
	function success(){
	}
	
	
	function dashboard(){
		$user_id = $this->getLoggedInUserId();
		$this->User->recursive = 0;
		$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' =>'active'));
		if($user['User']['active'] == 1){
			$this->redirect(array('controller' =>'users', 'action'=>'profile'));
		}
		
	}
	function profile() {
		$this->set('title_for_layout', 'Profile');
		$userId = $this->getLoggedInUserId();
		$this->loadModel('Log');
		 $activeid = $this->Session->read('User.active');
		if($activeid==1){
		
		$this->set('unreadTextMsg', $this->Log->find('count', array('conditions' => array('Log.user_id' =>$userId, 'Log.read' => 0, 'Log.route' => 'inbox', 'msg_type' => 'text'))));
		$this->set('unreadVoiceMsg', $this->Log->find('count', array('conditions' => array('Log.user_id' =>$userId, 'Log.read' => 0, 'Log.route' => 'inbox', 'msg_type' => 'voice'))));
		
		$this->set('inbox', $this->Log->find('count', array('conditions' => array('Log.user_id' =>$userId, 'Log.route' => 'inbox' ,'msg_type' => 'text'))));
		$this->set('outbox', $this->Log->find('count', array('conditions' => array('Log.user_id' =>$userId, 'Log.route' => 'outbox' ,'msg_type' => 'text'))));
		
				$this->set('vminbox', $this->Log->find('count', array('conditions' => array('Log.user_id' =>$userId, 'Log.route' => 'inbox' ,'msg_type' => 'voice'))));
				
				
			app::import('Model','ContactGroup');
			
		 $this->ContactGroup = new ContactGroup();
		 
		 $subscriber=$this->ContactGroup->find('count', array('conditions' => array('ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>1)));	
	
		
		 $unsubscriber=$this->ContactGroup->find('count', array('conditions' => array('ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>1,'ContactGroup.un_subscribers'=>1)));
		
	

		 $total=$subscriber-$unsubscriber;
		
		
		
       $percentage=$total/$subscriber*100;
				
		$this->set('percentage',$percentage);
		
	
			app::import('Model','MonthlyPackage');
		 
		    $this->MonthlyPackage = new MonthlyPackage();
		 
      $packageid = $this->Session->read('User.package');
	 
     $package=$this->MonthlyPackage->find('first', array('conditions' => array('MonthlyPackage.id' =>$packageid)));	

$this->set('packages',$package);	 
				
	}else{
	
	$this->Session->setFlash(__('Please Activate your Account', true));
	
	$this->redirect(array('action' => 'dashboard'));
	
	}
	
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}
	
	function affiliates() {
		$user_id = $this->getLoggedInUserId();
		$this->User->recursive = 0;
		$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' =>'paypal_email'));
		$this->set('user',$user);
	}
	
	function change_paypal_email(){
		$this->layout ='popup';
		if(!empty($this->data)){
			$this->User->set($this->data);
			$this->User->validationSet = 'ChangePaypalEmail';
			if ($this->User->validates()) 
			{
				$this->User->id = $this->getLoggedInUserId();
				$this->User->saveField('paypal_email', $this->data['User']['paypal_email']);
				$this->redirect(array('action' => 'affiliates'));
			}
		}
		$user_id = $this->getLoggedInUserId();
		$this->User->recursive = 0;
		$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' =>'paypal_email'));
		$this->set('user',$user);
	}
	function edit() {
		
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'profile'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$id = $this->getLoggedInUserId();
			$this->data = $this->User->read(null, $id);
		}
	}
	
	function change_password(){
	$this->set('title_for_layout', 'Change Password');
		if(!empty($this->data)){
			$this->User->set($this->data);
			$this->User->validationSet = 'ChangePassword';
			if ($this->User->validates()) 
			{
				$username = $this->getLoggedInUserName();
				$someone = $this->User->find('first', array('conditions' => array('username' =>$username)));
				if(!empty($someone['User']['password']) && $someone['User']['password'] == md5($this->data['User']['old_password']))
	            {
	                $this->User->id = $someone['User']['id'];
	                $this->User->saveField('password', md5($this->data['User']['new_password']));
					$this->Session->setFlash(__('Password changed', true));
					$this->redirect(array('controller' =>'users','action' => 'profile'));
	            }

	            // Else, they supplied incorrect data:
	            else
	            {
	                $this->Session->setFlash('Old Password is Wrong'); 				
				}
			}
		}//this->data
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function list_package($id=null){
	$this->set('id',$id);
		$this->loadModel('Package');
		$this->set('text_packages', $this->Package->find('all', array('conditions' => array('Package.status' =>1, 'Package.type' => 'text'))));
		$this->set('voice_packages', $this->Package->find('all', array('conditions' => array('Package.status' =>1, 'Package.type' => 'voice'))));
		$this->set('user', $this->User->find('first', array('conditions' => array('User.id' =>$this->Session->read('User.id')))));
	}
	
	function purchase_credit($id){
		$this->layout = 'popup';
		$this->loadModel('Package');
		$this->set('package', $this->Package->find('first' , array('conditions' => array('id' => $id))));
		
	}
	function purchase_credit_checkout($id){
		$this->layout = 'popup';
		$this->loadModel('Package');
		$this->set('package', $this->Package->find('first' , array('conditions' => array('id' => $id))));
		app::import('Model','Config');
	    $this->Config = new Config();
		$configdata=$this->Config->find('first');
		
		//print_r($configdata);
		
		$this->set('config',$configdata);
	}
	
	function account_credited(){
	}
	
	function listNumbers(){
		 
		 $response = $this->Twilio->listNumbers();	

		 $AvailablePhoneNumbers = $response->ResponseXml->AvailablePhoneNumbers->AvailablePhoneNumber;
		
		 
		 if(empty($AvailablePhoneNumbers)) {
			$this->Session->setFlash(__('We did not find any phone numbers by that search', true));
			//header("Location: ?msg=$err");
			//exit(0);
		}
		  $this->set('AvailablePhoneNumbers', $AvailablePhoneNumbers);
	}
	
	function admin_index() {
		$this->User->recursive = 0;
		$this->paginate = array(
									'User' => array(
									'order' => 'User.created DESC'
									)
								);
		$this->set('users', $this->paginate());
	}
	
	function admin_edit($id = null) {
		$this->set('title_for_layout', 'Edit User');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
	}
	
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function about(){
		$this->set('title_for_layout', 'About Us');
	}
	
	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}
	
	function admin_user_messages()
{	
	
	    $user = $this->User->find('list',array('fields'=>array('User.username')));
				
		$this->set('users',$user);
		
		
			if(isset($this->data['User']['date']) && !empty($this->data['User']['date'])){
					$call_date = explode('/', $this->data['User']['date']);
					if(count($call_date) == 3){
						$date_call=$call_date[2]."-".$call_date[0]."-".$call_date[1];
					}
				}else{
				$date_call=date("Y-m-d");
				}
			
				
			if($this->data['User']['date']!=''){
			
			
			$conditions=array();
			$this->Session->write('date', $this->data['User']['date']);
				$conditions = $this->array_push_assoc($conditions, 'DATE(Log.created)', "$date_call");
				
				app::import('Model','Log');
				$this->Log = new Log();
				
				$Messege=$this->Log->find('all',array('conditions'=> array('AND' => array($conditions)),'order'=>'Log.created'));
				
				$Messege15=$this->Log->find('all',array('conditions'=> array('AND' => array($conditions)),'order'=>'Log.created'));
				
				$total=$this->Log->find('count',array('conditions'=> array('AND' => array($conditions)),'order'=>'Log.created'));
				
				$this->Session->write('total', $total);
			
				}else if($this->data['User']['id']!=0){
				
				
				$user_id = $this->data['User']['id'];
				$conditions=array();
				
				$month=date('m');
				//contacts.created like '%$month%'"
				$conditions = $this->array_push_assoc($conditions, 'Log.user_id', "$user_id");
			
				$conditions = $this->array_push_assoc($conditions, 'MONTH(Log.created)', "$month");
				//print_r($conditions);
				
				app::import('Model','Log');
				$this->Log = new Log();
				
				$Messege=$this->Log->find('all',array('conditions'=> array('AND' => array($conditions)),'order'=>'Log.created'));
				
				$Messege15=$this->Log->find('all',array('conditions'=> array('AND' => array($conditions)),'order'=>'Log.created'));
				
				
				
				
				
				
				$total=$this->Log->find('count',array('conditions'=> array('AND' => array($conditions)),'order'=>'Log.created'));
				
				
				
				$this->Session->write('total', $total);
				
				}
				
				
				
				foreach($Messege15 as $m_list){
                       
					   //count records day wise from calllogs table for a month
                       $day = date("d",strtotime($m_list['Log']['created']));
                       if(isset($month_list[$day])){
                               $month_list[$day] = $month_list[$day] +1;                        
                       }else{
                               $month_list[$day] = 1;
                       }
					   
										  
					 
					   $number=$m_list['Log']['phone_number'];
					   
					  if(isset($number))
							$no_list[$number] = $no_list[$number] +1;       
					  else
							$no_list[$number]=1;

					}
				
					$mon_list = array();
					for($i=0;$i<31;$i++)
					{
						$j=$i+1;
						if(strlen($j)==1)
							$j='0'.$j;
						if(isset($month_list[$j]) && $month_list[$j]!='')
							$mon_list[$i]=$month_list[$j];
						else	
							$mon_list[$i]=0;
					}
					
					
					//pr($mon_list);
					$caller_list=json_encode($mon_list);
				
				    $this->set('caller_list', $caller_list);
					
				    $this->set('Messege', $Messege);
					
						
					
					
					
				

	}
	
	
	function admin_top_users(){
	
	      $perpg=5;  
			if(isset($_REQUEST['_pn']) && $_REQUEST['_pn'] >0 && $_REQUEST['_pn']!=''){
				$pageNumber = $_REQUEST['_pn'];    
				$perpg="".($perpg*($pageNumber-1)).",".$perpg;  
			}else{
				$pageNumber = 1;  
			}
			app::import('Model','Log');
		$this->Log = new Log();
	
		if(isset($this->data['date']['to']) && !empty($this->data['date']['from'])){
					$start_call_date = explode('/', $this->data['date']['from']);
					$end_call_date = explode('/', $this->data['date']['to']);
					if(count($start_call_date) == 3){
						$stardate=$start_call_date[2]."-".$start_call_date[0]."-".$start_call_date[1];
					}
					if(count($end_call_date) == 3){
					$enddate=$end_call_date[2]."-".$end_call_date[0]."-".$end_call_date[1];
					}
					
					$stardatenew = date('Y-m-d',strtotime($stardate ." -1 day"));
				
				    $enddatenew = date('Y-m-d',strtotime($enddate ." +1 day"));
					
					
				if($stardate==$enddate){
				

$usersCount = $this->Log->query ("SELECT p.id, p.first_name, p.last_name,p.email, p.assigned_number, COUNT( c.id ) as count FROM users p JOIN logs c ON c.user_id = p.id where  c.msg_type='text' and c.created like '%".$enddate."%' GROUP BY p.id ORDER BY COUNT( c.id ) DESC limit  ".$perpg."");	

}else{
$usersCount = $this->Log->query ("SELECT p.id, p.first_name, p.last_name,p.email, p.assigned_number, COUNT( c.id ) as count FROM users p JOIN logs c ON c.user_id = p.id where c.msg_type='text' and c.created between'".$stardate."' and '".$enddatenew."' GROUP BY p.id ORDER BY COUNT( c.id ) DESC limit  ".$perpg."");	


}				
					
				}else{
				
				
	  $currentDate = date('Y-m-d',mktime(0, 0, 0, date("m"), date("d"),date("Y")));
	  $usersCount = $this->Log->query ("SELECT p.id, p.first_name, p.last_name,p.email, p.assigned_number, COUNT( c.id ) as count FROM users p JOIN logs c ON c.user_id = p.id where c.created LIKE '".$currentDate."%' and c.msg_type='text' GROUP BY p.id ORDER BY COUNT( c.id ) DESC limit  ".$perpg.""); 
	  }
		
		
		
		
		 
		
	   // pr($usersCount);
		
		foreach($usersCount as $userCount){
			$first_name=$userCount['p']['first_name'];
			$total_no[$first_name]=$userCount[0]['count'];
		}
		$i=0;
		foreach($total_no as $key=>$value)
				{
				  $n_list[$i]=str_replace('"','\'',json_encode(array($key,(int)$value)));
			  	  $i++;
				}
     	 $number_list=str_replace('"','',json_encode(array_values($n_list)));
		$this->set('number_list', $number_list);
		$this->set('top_users', $usersCount);
	
	}
	
	function admin_non_users(){
	
	$perpg=5;  
			if(isset($_REQUEST['_pn']) && $_REQUEST['_pn'] >0 && $_REQUEST['_pn']!=''){
				$pageNumber = $_REQUEST['_pn'];    
				$perpg="".($perpg*($pageNumber-1)).",".$perpg;  
			}else{
				$pageNumber = 1;  
			}
			app::import('Model','Log');
		    $this->Log = new Log();
		
		if(isset($this->data['date']['to']) && !empty($this->data['date']['from'])){
					$start_call_date = explode('/', $this->data['date']['from']);
					$end_call_date = explode('/', $this->data['date']['to']);
					if(count($start_call_date) == 3){
						$stardate=$start_call_date[2]."-".$start_call_date[0]."-".$start_call_date[1];
					}
					if(count($end_call_date) == 3){
					$enddate=$end_call_date[2]."-".$end_call_date[0]."-".$end_call_date[1];
					}
					
					$stardatenew = date('Y-m-d',strtotime($stardate ." -1 day"));
				
				    $enddatenew = date('Y-m-d',strtotime($enddate ." +1 day"));
					
					if($stardate==$enddate){
					
					$logs =("select * from users where id not in(SELECT user_id from  logs WHERE  logs.created like '%".$stardate."%' group by logs.user_id)");	
					
					}else{
					
					$logs =("select * from users where id not in(SELECT user_id from  logs WHERE logs.created between '".$stardate."' and '".$enddatenew."' group by logs.user_id)");	
					
					}
				
				
				
				
		$data=$this->Log->query($logs);
		
		/* echo "<pre>";
		print_r($non_users);
		echo "</pre>"; */
				  $this->set('non_users',$data);
				}
	
	}
	
	function reports()
	{
	
		
		 $user=$this->Session->read('User');
//print_r($user);		 
		
		$perpg=5;  
			if(isset($_REQUEST['_pn']) && $_REQUEST['_pn'] >0 && $_REQUEST['_pn']!=''){
			
			
				$pageNumber = $_REQUEST['_pn'];    
				$perpg="".($perpg*($pageNumber-1)).",".$perpg;  
			}else{
				$pageNumber = 1;  
			}
				
			if(isset($this->data['User']['start']) && ! empty($this->data['User']['start'])){
					$call_date = explode('/', $this->data['User']['start']);
					if(count($call_date) == 3){
						$date_call_start=$call_date[2]."-".$call_date[0]."-".$call_date[1];
					}
					$date_call_end = explode('/', $this->data['User']['end']);
					if(count($date_call_end) == 3){
						$date_call_end=$date_call_end[2]."-".$date_call_end[0]."-".$date_call_end[1];
					}
					$date_call_start_actuall = $date_call_start;
	$date_call_end_actuall = $date_call_end;
					$date_call_start = date('Y-m-d',strtotime($date_call_start ." -1 day"));
				
				$date_call_end = date('Y-m-d',strtotime($date_call_end ." +1 day"));
				
				//$date_call_start_actuall = date('Y-m-d',strtotime($date_call_start ." +1 day"));
					
					//$date_call_end_actuall = date('Y-m-d',strtotime($date_call_end ." -1 day"));
				
			}elseif(isset($_REQUEST['_pn'])){
				$date_call_start = $this->Session->read('startdate');	
				$date_call_end =	$this->Session->read('enddate');
				$date_call_start_actuall =$this->Session->read('date_call_start_actuall');
				$date_call_end_actuall =$this->Session->read('date_call_end_actuall');
				}else{
						$date_call_start='';
						$date_call_end='';	
						
				}
				
				
				
			if($date_call_start_actuall!=''){
			
				
				app::import('Model','Log');
				$this->Log = new Log();
				$conditions = array();
				
				if($date_call_start_actuall == $date_call_end_actuall){
				
				$query1 = "SELECT * FROM logs  left join groups  on groups.id = logs.group_id WHERE logs.user_id = ".$user['id']." and msg_type='text' and  logs.created like '%".$date_call_start_actuall."%' order by logs.id desc limit ".$perpg."";
				$query = "SELECT * FROM logs  left join groups  on groups.id = logs.group_id WHERE logs.user_id = ".$user['id']." and msg_type='text' and  logs.created like '%".$date_call_start_actuall."%'";
				
				}else{
				
			 
				
				
				 $query1 = "SELECT * FROM logs  left join groups  on groups.id = logs.group_id WHERE logs.user_id = ".$user['id']." and msg_type='text' and  logs.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by logs.id desc limit ".$perpg."";
				
				$query = "SELECT * FROM logs  left join groups  on groups.id = logs.group_id WHERE logs.user_id = ".$user['id']." and msg_type='text' and  logs.created between '".$date_call_start_actuall."' and '".$date_call_end."'";
				
				
				
				
				}
				$Messege=$this->Log->query($query1);
		
			
				
				
				$Messege15=$this->Log->query($query);
			

				$total = 	count($Messege15);
				
			$this->Session->write('total', $total);	

			}else{
				
				 app::import('Model','Log');
				$this->Log = new Log();
				$month=date('m');
				
				
				$query1 = "SELECT * FROM logs  left join groups  on groups.id = logs.group_id WHERE logs.user_id = ".$user['id']." and msg_type='text' and  MONTH(logs.created) = '$month'";
				
				
				 $query = "SELECT * FROM logs  left join groups  on groups.id = logs.group_id WHERE logs.user_id = ".$user['id']." and  MONTH(logs.created) = '$month'  and msg_type='text' order by logs.id desc limit ".$perpg."";
				
				$Messege = $this->Log->query($query);
				$Messege15=$this->Log->query($query1);
				$total = 	count($Messege15);
			}	
			
				
				
				
				foreach($Messege15 as $m_list){
                       
				
                       $day = date("d",strtotime($m_list['logs']['created']));
                       if(isset($month_list[$day])){
                               $month_list[$day] = $month_list[$day] +1;                        
                       }else{
                               $month_list[$day] = 1;
                       }
					   
					 
					}
				
					$mon_list = array();
					for($i=0;$i<31;$i++)
					{
						$j=$i+1;
						if(strlen($j)==1)
							$j='0'.$j;
						if(isset($month_list[$j]) && $month_list[$j]!='')
							$mon_list[$i]=$month_list[$j];
						else	
							$mon_list[$i]=0;
					}
					
					
					
					$caller_list=json_encode($mon_list);
				
				    $this->set('caller_list', $caller_list);
					
				    $this->set('Messege', $Messege);
					
					
					
					$this->set('start',$date_call_start_actuall);
					$this->set('end',$date_call_end_actuall);
					
					$this->Paginationclass->intPageSize = 5;
   $this->Paginationclass->strFunctionName="showExtension";
   $this->Paginationclass->arrVariables = array(); 
   $this->Paginationclass->arrVariables[0] = $total; 
	$this->Paginationclass->setTotalRecords($total);
  
  $TotalPages = $this->Paginationclass->getTotalPages();   
    $strPagination = $this->Paginationclass->showPagination($pageNumber);				
				$this->Session->write('startdate',$date_call_start);	
				$this->Session->write('enddate',$date_call_end);
				$this->Session->write('date_call_start_actuall',$date_call_start_actuall);
				$this->Session->write('date_call_end_actuall',$date_call_end_actuall);
$this->set('TotalPages', $TotalPages);
$this->set('strPagination', $strPagination);
					
	 
	}
	
	function subscribers(){
	
	$user_id = $this->Session->read('User.id');	
	/*  app::import('Model','Group');
	$this->Group = new Group();
	$group = $this->Group->find('list',array('condition'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name'));
	 
	 
	 
				
		$this->set('groups',$group); */
		
		 
	
	app::import('Model','Contact');
		$this->Contact = new Contact();
		
		$perpg=5;  
			if(isset($_REQUEST['_pn']) && $_REQUEST['_pn'] >0 && $_REQUEST['_pn']!=''){
			
			
				$pageNumber = $_REQUEST['_pn'];    
				$perpg="".($perpg*($pageNumber-1)).",".$perpg;  
			}else{
				$pageNumber = 1;  
			}
				
			if(isset($this->data['User']['start']) && ! empty($this->data['User']['start'])){
					$call_date = explode('/', $this->data['User']['start']);
					if(count($call_date) == 3){
						$date_call_start=$call_date[2]."-".$call_date[0]."-".$call_date[1];
					}
					$date_call_end = explode('/', $this->data['User']['end']);
					if(count($date_call_end) == 3){
						$date_call_end=$date_call_end[2]."-".$date_call_end[0]."-".$date_call_end[1];
					}
					$date_call_start_actuall = $date_call_start;
	$date_call_end_actuall = $date_call_end;
					$date_call_start = date('Y-m-d',strtotime($date_call_start ." -1 day"));
				
				$date_call_end = date('Y-m-d',strtotime($date_call_end ." +1 day"));
				
				//$date_call_start_actuall = date('Y-m-d',strtotime($date_call_start ." +1 day"));
					
					//$date_call_end_actuall = date('Y-m-d',strtotime($date_call_end ." -1 day"));
				
			}elseif(isset($_REQUEST['_pn'])){
				$date_call_start = $this->Session->read('startdate');	
				$date_call_end =	$this->Session->read('enddate');
				$date_call_start_actuall =$this->Session->read('date_call_start_actuall');
				$date_call_end_actuall =$this->Session->read('date_call_end_actuall');
				}else{
						$date_call_start='';
						$date_call_end='';	
						
				}
				
				if($date_call_start_actuall!=''){
				
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				if($date_call_start_actuall == $date_call_end_actuall){
				
				  $query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created like '%".$date_call_start_actuall."%' order by contact_groups.created desc limit ".$perpg."";
				
				$query = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id  WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created like '%".$date_call_start_actuall."%'";
				
				}else{
				
				  $query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc limit ".$perpg."";
				
				$query = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id  WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."'";
				
				
				
				}
				
				$subscribers=$this->Contact->query($query1);
		
			
				
				
				$subscribers1=$this->Contact->query($query);
				
				
				$total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				
				
				/* $total = $this->Contact->query($query);
				
				$total = count($total);
				
				$this->Session->write('total', $total);	 */
				
				}
			
				
		       else{
				
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				     $query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc limit ".$perpg."";
				
				
				    $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month'";
				
					$subscribers=$this->Contact->query($query1);
		
			
				
				
				$subscribers1=$this->Contact->query($query);
				
				
				$total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				
				/* $total = $this->Contact->query($query);
				
				$total = count($total);
				
				$this->Session->write('total', $total);	 */
						
				}
			
		
		     
				
				
				
				/* $query12 = "SELECT * FROM contacts  WHERE contacts.user_id = ".$user_id." and  contacts.created like '%$month%'";
				$subscribers1 = $this->Contact->query($query12); */
				//$subscribers = $this->Contact->query($query1);
				
				//pr($subscribers);
	 
			foreach($subscribers1 as $m_list){
                       
				
                       $day = date("d",strtotime($m_list['contact_groups']['created']));
                       if(isset($month_list[$day])){
                               $month_list[$day] = $month_list[$day] +1;                        
                       }else{
                               $month_list[$day] = 1;
                       }
					   
					}
				
					$mon_list = array();
					for($i=0;$i<31;$i++)
					{
						$j=$i+1;
						if(strlen($j)==1)
							$j='0'.$j;
						if(isset($month_list[$j]) && $month_list[$j]!='')
							$mon_list[$i]=$month_list[$j];
						else	
							$mon_list[$i]=0;
					}
						$caller_list=json_encode($mon_list);
						
						
						//pr($caller_list);
				
				    $this->set('caller_list', $caller_list);
					
					$this->set('start',$date_call_start_actuall);
					$this->set('end',$date_call_end_actuall);
					  $this->set('subscribers', $subscribers);
					
					$this->Paginationclass->intPageSize = 5;
   $this->Paginationclass->strFunctionName="showExtension";
   $this->Paginationclass->arrVariables = array(); 
   $this->Paginationclass->arrVariables[0] = $total; 
	$this->Paginationclass->setTotalRecords($total);
  
  $TotalPages = $this->Paginationclass->getTotalPages();   
    $strPagination = $this->Paginationclass->showPagination($pageNumber);				
				$this->Session->write('startdate',$date_call_start);	
				$this->Session->write('enddate',$date_call_end);
				$this->Session->write('date_call_start_actuall',$date_call_start_actuall);
				$this->Session->write('date_call_end_actuall',$date_call_end_actuall);
$this->set('TotalPages', $TotalPages);
$this->set('strPagination', $strPagination);
				
				  
					
	
	}
	function unsubscribers() {
	
	
	$user_id = $this->Session->read('User.id');	
	/*  app::import('Model','Group');
	$this->Group = new Group();
	$group = $this->Group->find('list',array('condition'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name'));
	 
	 
	 
				
		$this->set('groups',$group); */
		
		 
	
	app::import('Model','Contact');
		$this->Contact = new Contact();
		
		$perpg=5;  
			if(isset($_REQUEST['_pn']) && $_REQUEST['_pn'] >0 && $_REQUEST['_pn']!=''){
			
			
				$pageNumber = $_REQUEST['_pn'];    
				$perpg="".($perpg*($pageNumber-1)).",".$perpg;  
			}else{
				$pageNumber = 1;  
			}
				
				if(isset($this->data['User']['start']) && ! empty($this->data['User']['start'])){
					$call_date = explode('/', $this->data['User']['start']);
					if(count($call_date) == 3){
						$date_call_start=$call_date[2]."-".$call_date[0]."-".$call_date[1];
					}
					$date_call_end = explode('/', $this->data['User']['end']);
					if(count($date_call_end) == 3){
						$date_call_end=$date_call_end[2]."-".$date_call_end[0]."-".$date_call_end[1];
					}
					$date_call_start_actuall = $date_call_start;
	$date_call_end_actuall = $date_call_end;
					$date_call_start = date('Y-m-d',strtotime($date_call_start ." -1 day"));
				
				$date_call_end = date('Y-m-d',strtotime($date_call_end ." +1 day"));
				
				//$date_call_start_actuall = date('Y-m-d',strtotime($date_call_start ." +1 day"));
					
					//$date_call_end_actuall = date('Y-m-d',strtotime($date_call_end ." -1 day"));
				
			}elseif(isset($_REQUEST['_pn'])){
				$date_call_start = $this->Session->read('startdate');	
				$date_call_end =	$this->Session->read('enddate');
				$date_call_start_actuall =$this->Session->read('date_call_start_actuall');
				$date_call_end_actuall =$this->Session->read('date_call_end_actuall');
				}else{
						$date_call_start='';
						$date_call_end='';	
						
				}
				
				if($date_call_start_actuall!=''){
				
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				if($date_call_start_actuall == $date_call_end_actuall){
				 
				
				 $query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created like '%".$date_call_start_actuall."%' order by contact_groups.created desc limit ".$perpg."";
				
				$query = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id  WHERE  contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created like '%".$date_call_start_actuall."%'";
				
				}else{
							$query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc limit ".$perpg."";
				
				$query = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id  WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."'";
				
				
				
				}
				
				$subscribers=$this->Contact->query($query1);
		
			
				
				
				$subscribers1=$this->Contact->query($query);
				
				
				 $total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				
				
				/* $total = $this->Contact->query($query);
				
				$total = count($total);
				
				$this->Session->write('total', $total);	 */
				
				}
			
				
		       else{
				
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				 $query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc limit ".$perpg."";
				
				
				    $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=1 and MONTH(contact_groups.created) = '$month'";
				
				  /*  $query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id WHERE contact_groups.un_subscribers=1 and contacts.user_id = ".$user_id." and  contact_groups.created like '%$month%' order by contact_groups.created desc limit ".$perpg."";
				
				
				
			echo 	$query = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id  WHERE contact_groups.un_subscribers=1 and  contacts.user_id = ".$user_id." and  contact_groups.created like '%$month%'"; */
				
					$subscribers=$this->Contact->query($query1);
		
			
				
				
				$subscribers1=$this->Contact->query($query);
				
				
				 $total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				
				/* $total = $this->Contact->query($query);
				
				$total = count($total);
				
				$this->Session->write('total', $total);	 */
						
				}
			
		
		     
				
				
				
				/* $query12 = "SELECT * FROM contacts  WHERE contacts.user_id = ".$user_id." and  contacts.created like '%$month%'";
				$subscribers1 = $this->Contact->query($query12); */
				//$subscribers = $this->Contact->query($query1);
				
				//pr($subscribers);
	 
			foreach($subscribers1 as $m_list){
                       
				
                       $day = date("d",strtotime($m_list['contact_groups']['created']));
                       if(isset($month_list[$day])){
                               $month_list[$day] = $month_list[$day] +1;                        
                       }else{
                               $month_list[$day] = 1;
                       }
					   
					}
				
					$mon_list = array();
					for($i=0;$i<31;$i++)
					{
						$j=$i+1;
						if(strlen($j)==1)
							$j='0'.$j;
						if(isset($month_list[$j]) && $month_list[$j]!='')
							$mon_list[$i]=$month_list[$j];
						else	
							$mon_list[$i]=0;
					}
						$caller_list=json_encode($mon_list);
						
						
						//pr($caller_list);
				
				    $this->set('caller_list', $caller_list);
					
					$this->set('start',$date_call_start_actuall);
					$this->set('end',$date_call_end_actuall);
					  $this->set('subscribers', $subscribers);
					
					$this->Paginationclass->intPageSize = 5;
   $this->Paginationclass->strFunctionName="showExtension";
   $this->Paginationclass->arrVariables = array(); 
   $this->Paginationclass->arrVariables[0] = $total; 
	$this->Paginationclass->setTotalRecords($total);
  
  $TotalPages = $this->Paginationclass->getTotalPages();   
    $strPagination = $this->Paginationclass->showPagination($pageNumber);				
				$this->Session->write('startdate',$date_call_start);	
				$this->Session->write('enddate',$date_call_end);
				$this->Session->write('date_call_start_actuall',$date_call_start_actuall);
				$this->Session->write('date_call_end_actuall',$date_call_end_actuall);
$this->set('TotalPages', $TotalPages);
$this->set('strPagination', $strPagination);
				
				  
					

	}
	
	function keywords(){
	 
	
		$user_id = $this->Session->read('User.id');


    app::import('Model','Group');
	$this->Group = new Group();
	$group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
	 
	 
	 
				
		$this->set('groups',$group);

	
		
		$perpg=5;  
			if(isset($_REQUEST['_pn']) && $_REQUEST['_pn'] >0 && $_REQUEST['_pn']!=''){
			
			
				$pageNumber = $_REQUEST['_pn'];    
				$perpg="".($perpg*($pageNumber-1)).",".$perpg;  
			}else{
				$pageNumber = 1;  
			}
		
				
			if(isset($this->data['User']['start']) && ! empty($this->data['User']['start'])){
					$call_date = explode('/', $this->data['User']['start']);
					if(count($call_date) == 3){
						$date_call_start=$call_date[2]."-".$call_date[0]."-".$call_date[1];
					}
					$date_call_end = explode('/', $this->data['User']['end']);
					if(count($date_call_end) == 3){
						$date_call_end=$date_call_end[2]."-".$date_call_end[0]."-".$date_call_end[1];
					}
					$date_call_start_actuall = $date_call_start;
	$date_call_end_actuall = $date_call_end;
					$date_call_start = date('Y-m-d',strtotime($date_call_start ." -1 day"));
				
				$date_call_end = date('Y-m-d',strtotime($date_call_end ." +1 day"));
				
				//$date_call_start_actuall = date('Y-m-d',strtotime($date_call_start ." +1 day"));
					
					//$date_call_end_actuall = date('Y-m-d',strtotime($date_call_end ." -1 day"));
				
			}elseif(isset($_REQUEST['_pn'])){
				 $date_call_start = $this->Session->read('startdate');	
				 $date_call_end =	$this->Session->read('enddate');
				 $this->data['Group']['id'] =	$this->Session->read('Groupid');
				 $this->data['Group']['groupsubscribers'] =	$this->Session->read('groupsubscribers');
				 $date_call_start_actuall =$this->Session->read('date_call_start_actuall');
				$date_call_end_actuall =$this->Session->read('date_call_end_actuall');
				 
				}else{
						$date_call_start='';
						$date_call_end='';	
						}
						
				if($date_call_start_actuall!='' && $this->data['Group']['id']!=0){
				
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				if($date_call_start_actuall == $date_call_end_actuall){
				
				
				
	$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and  contact_groups.created like '%".$date_call_start_actuall."%' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc limit ".$perpg."";
				
				
				
				 $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.created like '%".$date_call_start_actuall."%' and contact_groups.un_subscribers=0 and groups.id= ".$this->data['Group']['id']."";
				 
				 }else{
				 $query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc limit ".$perpg."";
				
				
				
				 $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE contact_groups.user_id = ".$user_id." and contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' and contact_groups.un_subscribers=0 and groups.id= ".$this->data['Group']['id']."";
				 
				 
				 
				 
				 }
				
				$subscribers=$this->Contact->query($query1);
		
		
			  // pr($subscribers);
				
				
				$subscribers1=$this->Contact->query($query);
				
				
				$total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				

            }else if($date_call_start_actuall!=''){
				
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				if($date_call_start_actuall == $date_call_end_actuall){
				
				$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and  contact_groups.un_subscribers=0 and contact_groups.created like '%".$date_call_start_actuall."%' order by contact_groups.created desc limit ".$perpg."";
				
				
				
				$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and contact_groups.created like '%".$date_call_start_actuall."%'";
				
				}else{
				
				$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and  contact_groups.un_subscribers=0 and contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc limit ".$perpg."";
				
				
				
				$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."'";
				
				
				
				}
				
				$subscribers=$this->Contact->query($query1);
		
			//pr($subscribers);
				
				
				$subscribers1=$this->Contact->query($query);
				
				
				$total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				
				
				
				
				
				}else if($this->data['Group']['id']!=0){
				$this->Session->write('Groupid', $this->data['Group']['id']);
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				if($this->data['Group']['groupsubscribers']!=''){
				
				 	$this->Session->write('groupsubscribers',$this->data['Group']['groupsubscribers']);
				   $query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']."  and  contact_groups.group_subscribers= '".$this->data['Group']['groupsubscribers']."' order by contact_groups.created desc limit ".$perpg."";
				
				
				   $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']." and contact_groups.group_subscribers='".$this->data['Group']['groupsubscribers']."'";
				
				}else{
				
				$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc limit ".$perpg."";
				
				
				
				 $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']."";
				}
				
				
				
				//$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE contacts.user_id = ".$user_id." and  contacts.created between '".$date_call_start."' and '".$date_call_end."'";
				
			
				
				$subscribers=$this->Contact->query($query1);
		
			//pr($subscribers);
				
				
				$subscribers1=$this->Contact->query($query);
				
				
				$total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				
				} else{
				$this->Session->write('Groupid', 0);
				$this->Session->write('groupsubscribers', '');
				 app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				 //$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contacts.user_id = ".$user_id." and contact_groups.created like '%$month%' limit ".$perpg."";
				 
				  $query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc limit ".$perpg."";
				
				
				
				// $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contacts.user_id = ".$user_id." and contact_groups.created like '%$month%'";
				 
				  $query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month'";
				
				$subscribers=$this->Contact->query($query1);
		
			//pr($subscribers);
				 
				
				$subscribers1=$this->Contact->query($query);
				
				
				$total = 	count($subscribers1);
				
			   $this->Session->write('total', $total);
				
				/* $total = $this->Contact->query($query);
				
				$total = count($total);
				
				$this->Session->write('total', $total);	 */
						
				}
			
		
		     
				
				
				
				/* $query12 = "SELECT * FROM contacts  WHERE contacts.user_id = ".$user_id." and  contacts.created like '%$month%'";
				$subscribers1 = $this->Contact->query($query12); */
				//$subscribers = $this->Contact->query($query1);
				
				//pr($subscribers1);
	 
			foreach($subscribers1 as $m_list){
                       
				
                       $day = date("d",strtotime($m_list['contact_groups']['created']));
                       if(isset($month_list[$day])){
                               $month_list[$day] = $month_list[$day] +1;                        
                       }else{
                               $month_list[$day] = 1;
                       }
					   
					}
				
					$mon_list = array();
					for($i=0;$i<31;$i++)
					{
						$j=$i+1;
						if(strlen($j)==1)
							$j='0'.$j;
						if(isset($month_list[$j]) && $month_list[$j]!='')
							$mon_list[$i]=$month_list[$j];
						else	
							$mon_list[$i]=0;
					}
						$caller_list=json_encode($mon_list);
						
						
						//pr($caller_list);
				
				    $this->set('caller_list', $caller_list);
					
					//$this->set('start',$date_call_start);
					$this->set('groupsubscribers',$this->data['Group']['groupsubscribers']);
					$this->set('start',$date_call_start_actuall);
					$this->set('end',$date_call_end_actuall);
					  $this->set('subscribers', $subscribers);
					
					$this->Paginationclass->intPageSize = 5;
   $this->Paginationclass->strFunctionName="showExtension";
   $this->Paginationclass->arrVariables = array(); 
   $this->Paginationclass->arrVariables[0] = $total; 
	$this->Paginationclass->setTotalRecords($total);
  
  $TotalPages = $this->Paginationclass->getTotalPages();   
    $strPagination = $this->Paginationclass->showPagination($pageNumber);				
				$this->Session->write('startdate',$date_call_start);	
				$this->Session->write('enddate',$date_call_end);
				
$this->set('TotalPages', $TotalPages);
$this->set('strPagination', $strPagination);
	
	
	}
	
	function checkkeyword($id=null){
	
	
	$this->autoRender = false;
	
	$user_id=$this->Session->read('User.id');
	
	 app::import('Model','ContactGroup');
		 $this->ContactGroup = new ContactGroup();
		 $Subscriber1 = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$id,'ContactGroup.group_subscribers <>'=>''),'fields'=>'ContactGroup.group_subscribers','group' => array('ContactGroup.group_subscribers')));
		 /*  echo "<pre>";
	    print_r($Subscriber1);
		echo "</pre>";  */
		
		
		
		
		
		
		echo 'Please select A keyword';
		echo '<br/>';
		
		echo '<select id="groupsubscribers" name="data[Group][groupsubscribers]"> ';
		echo "<option value=''>All</option>";
	
		foreach($Subscriber1 as $Subscriber){
		
		echo '<option value="'.$Subscriber['ContactGroup']['group_subscribers'].'">'.$Subscriber['ContactGroup']['group_subscribers'].'</option>';
		
		}
		echo '</select>';
		
		
		//$this->set('groupsubscribers',$Subscriber1);
		
	
	}
	
	function checksubscriber($id=null){
	
	
	$this->layout = null;
	
	$user_id=$this->Session->read('User.id');
	
	 app::import('Model','ContactGroup');
		 $this->ContactGroup = new ContactGroup();
		 $totalsubscriber = $this->ContactGroup->find('all',array('conditions' => array('ContactGroup.group_id'=>$id,'ContactGroup.un_subscribers'=>0,'ContactGroup.group_subscribers <>'=>''),'fields'=>'ContactGroup.group_subscribers,count(*) as total','group' => array('ContactGroup.group_subscribers')));
		 
		 
		   
		$this->set('totalsubscribers',$totalsubscriber);
		 
		 
		 
	}
	
	function setting(){
	
	$user_id = $this->Session->read('User.id');	
		/* echo "<pre>";
	print_r($this->data);
	echo "</pre>"; */
	
	
	if(!empty($this->data)){
	
	    app::import('Model','User');
	   
	    $this->User = new User();
		
		$this->data['User']['id']=$user_id;
		
		//$this->data['User']['sms_credit_balance_email_alerts']=0;
		
		//$this->data['User']['VM_credit_balance_email_alerts']=0;
		
		$this->User->save($this->data);
		
		 $this->Session->setFlash('Email alert settings have been saved');
		
		$this->redirect(array('controller' =>'users', 'action'=>'setting'));
		
	}else{

    $user = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
	
	$this->set('users',$user);


    }	
	
	
	
	}
	
	function antispampolicy(){
	
	}

	
	function qrcodes(){
	
	if(!empty($this->data)){
	
	$user_id = $this->Session->read('User.id');
	
	app::import('Model','Qrcod');
	   
    $this->Qrcod = new Qrcod();
	
	$data['user_id']=$user_id;
	
	$data['name']=$this->data['Code']['qrcode'];
	
	$this->Qrcod->save($data);
		
    $this->Session->setFlash('Web page URL QR code has been saved');
		
    $this->redirect(array('controller' =>'users', 'action'=>'qrcodeindex'));
	
	}
	
	
	
	}
	function qrcodeindex(){
	app::import('Model','Qrcod');
	   
    $this->Qrcod = new Qrcod();
	
	$user_id=$this->Session->read('User.id');	
		
		$this->paginate = array(
'conditions' => array('Qrcod.user_id' =>$user_id),'order' =>array('Qrcod.id' => 'asc'));
$data = $this->paginate('Qrcod');
$this->set('qrdata', $data);
	
	}
	
	function qrcodeview($id=null){
	
	$user_id=$this->Session->read('User.id');	
	
	app::import('Model','Qrcod');
	   
    $this->Qrcod = new Qrcod();
	
	 $codegenrate = $this->Qrcod->find('first',array('conditions' => array('Qrcod.user_id'=>$user_id,'Qrcod.id'=>$id)));
	 

	
	if(!empty($codegenrate)){
	
	
	 $test = $codegenrate['Qrcod']['name'];
	
		$p = 'qr/'.$test.'-large.png';
		
			$url = SITE_URL;
			
			 copy('test/test.png',$p);
	
	
				QRcode::png($test, 'qr/'.$test.'-large.png', 'L', 8, 2);
				
				$this->set('qrimage1',$url.'/qr/'.$test.'-large.png');
				
				 
				 }
	
	}
	function qrcodedelete($id=null){
	
	app::import('Model','Qrcod');
	   
    $this->Qrcod = new Qrcod();
	
	if ($this->Qrcod->delete($id)) {
	
	$this->Session->setFlash(__('QR code deleted', true));
	
	   $this->redirect(array('controller' =>'users', 'action'=>'qrcodeindex'));
	
	
	}
	}

  function terms_conditions(){
  
  }
  function privacy_policy(){
  
  }
  function faq(){
  
  }
	function order_confirm(){
		
		$amount = $_POST['amount'];
		$package_name = "package1";
		$user_id = $_POST['user_id'];
		
		app::import('Model','User');
	   
        $this->User = new User();
	
	    $userresponse = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		
		app::import('Model','MonthlyPackage');
	   
        $this->MonthlyPackage = new MonthlyPackage();
	
	    $monthlypackage = $this->MonthlyPackage->find('first',array('conditions' => array('MonthlyPackage.id'=>$userresponse['User']['package'])));
		
		
		$response = $this->Expresscheckout->CreateRecurringPayments($amount,$package_name);
		
		echo $nextdate= date("Y-m-d H:i:s",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")),time());
		//if($response['ACK']=='Success'){
		if($user_id!=''){
			$this->User->id = $_POST['user_id'];
			
			
			$this->data['User']['active'] = 1;
			
			$this->data['User']['next_renewal_dates'] =$nextdate;
			
			$this->data['User']['sms_balance'] =$monthlypackage['MonthlyPackage']['text_messages_credit'];
			
			$this->data['User']['voice_balance'] =$monthlypackage['MonthlyPackage']['voice_messages_credit'];
			
			echo "<pre>";
		print_r($this->data);
		echo "</pre>";
	 
	
			
			$this->User->save($this->data);
				die(); 
		
			//$this->Session->setFlash(__('Your account is activated.', true));
			//$this->redirect(array('action' => 'profile'));
		}
		
		
	}
	function paypalpayment($user_id){
	
	
	   app::import('Model','MonthlyPackage');
	   
       $this->MonthlyPackage = new MonthlyPackage();
	
	     $monthlydetails = $this->MonthlyPackage->find('all',array('conditions' => array('MonthlyPackage.status'=>1)));
	
	$this->set('monthlydetails',$monthlydetails);
	
	app::import('Model','Package');
	   
       $this->Package = new Package();
	
	  $Packagedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1)));
	$this->set('Packagedetails',$Packagedetails);
		if (!empty($this->data)) {
		
			$packageAmount = $this->data['User']['amount'];
			$user_id = $this->data['User']['id'];
			$packageName = $this->data['User']['package_name'];
			
			app::import('Model','User');
	   
            $this->User = new User();
			
			$userdetails['id'] = $this->data['User']['id'];
			
			$userdetails['package'] = $this->data['MonthlyPackage']['packageid'];
			$this->User->save($userdetails);
			
			$response = $this->Expresscheckout->sendrequest($packageAmount,$user_id,$packageName);	
		}
	}
	function review($user_id = null){
		$token = "";
		$this->set('user_id',$user_id);
		$packageName = "Sports";
		if (isset($_REQUEST['token']))
		{
			$token = $_REQUEST['token'];
		}
		if ( $token != "" )
		{
			$response = $this->Expresscheckout->GetShippingDetail($token,$packageName);
		/* 	echo "<pre>";
			print_r($response);
				echo "</pre>"; */
			$this->set('response',$response);
		}
		if (!empty($user_id)) {
			/* $this->User->id = $user_id;
			$this->data['User']['payment_paid'] = 1;
			$this->User->save($this->data);
			$this->set('user_id',$user_id); */
		}
	}
	function checkoutpayment(){
	
	    app::import('Model','MonthlyPackage');
	   
        $this->MonthlyPackage = new MonthlyPackage();
	
	    $monthlydetails = $this->MonthlyPackage->find('all',array('conditions' => array('MonthlyPackage.status'=>1)));
	
	    $this->set('monthlydetails',$monthlydetails);
	
	    app::import('Model','Config');
		
	    $this->Config = new Config();
		
		$config=$this->Config->find('first');
		
		$this->set('config', $config);
		
		app::import('Model','Package');
	   
       $this->Package = new Package();
	
	  $Packagedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1)));
	$this->set('Packagedetails',$Packagedetails);
	}
 
	
	
}