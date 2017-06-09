<?php
//**** Use Composer
//App::import('Vendor','stripe',array('file' => 'stripe/autoload.php')); 

//**** Manually load required dependent files
App::import('Vendor','stripe',array('file' => 'stripe/stripe/stripe-php/init.php'));
include('./accounts.php');
include('./alphacountrypermissions.php');

class UsersController extends AppController {
	var $name = 'Users';
	var $components = array('Captcha','Cookie','Email','Twilio','Paginationclass','Qr','Checkout','Expresscheckout','Nexmo','Plivo');

	function captcha_check($userCode){
		return $this->Captcha->check($userCode);
	}
	
	function recurringpayment(){
		$this->autoRender = false;
		if($_REQUEST['payment_status']=='Processed'){
	        app::import('Model','User');			
			$this->User=new User();
		    $reccuring_email = $this->User->find('first',array('conditions' => array('User.recurring_paypal_email'=>$_REQUEST['payer_email'])));
				if(!empty($reccuring_email)){
				app::import('Model','MonthlyPackage');	
				$this->MonthlyPackage=new MonthlyPackage();
				$monthlyPackages = $this->MonthlyPackage->find('first',array('conditions' => array('MonthlyPackage.id'=>$reccuring_email['User']['package'])));
				$this->data['User']['id']=$reccuring_email['User']['id'];
				$this->data['User']['sms_balance']=$reccuring_email['User']['sms_balance']+$monthlyPackages['MonthlyPackage']['text_messages_credit'];
				$this->data['User']['voice_balance']=$reccuring_email['User']['voice_balance']+$monthlyPackages['MonthlyPackage']['voice_messages_credit'];
				$this->data['User']['sms_credit_balance_email_alerts']=0;
				$this->data['User']['VM_credit_balance_email_alerts']=0;
				$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
				$this->data['User']['next_renewal_dates'] =$nextdate;	
				$this->User->save($this->data);
				app::import('Model','Invoice');
				$this->Invoice=new Invoice();		
				$invoice['user_id']=$reccuring_email['User']['id'];
				$invoice['txnid']=$_REQUEST['txn_id'];	
				$invoice['amount']=$monthlyPackages['MonthlyPackage']['amount'];
				$invoice['type']=0;	
				$invoice['created']=date("Y-m-d");	
				$this->Invoice->save($invoice);
					if($reccuring_email['User']['active']==1){
						app::import('Model','Referral');
						$this->Referral=new Referral();
						$referraldetails = $this->Referral->find('all',array('conditions' => array('Referral.referred_by'=>$reccuring_email['User']['id'])));
						$percentage=$monthlyPackages['MonthlyPackage']['amount']*RECURRING_REFERRAL_PERCENT/100;
			
						if(!empty($referraldetails)){
							foreach($referraldetails as $referraldetail){
								$referral['id']=$referraldetail['Referral']['id'];
								$referral['amount']=$percentage;
								$referral['paid_status']=0;
								$this->Referral->save($referral);
							}
						}
					}
				}
		} else if($_REQUEST['payment_status']=='Completed'){
			app::import('Model','User');		
			$this->User=new User();
			$reccuring_email = $this->User->find('first',array('conditions' => array('User.recurring_paypal_email'=>$_REQUEST['payer_email'])));
			if(!empty($reccuring_email)){
				app::import('Model','MonthlyPackage');	
				$this->MonthlyPackage=new MonthlyPackage();
				$monthlyPackages = $this->MonthlyPackage->find('first',array('conditions' => array('MonthlyPackage.id'=>$reccuring_email['User']['package'])));
				$this->data['User']['id']=$reccuring_email['User']['id'];	
				$this->data['User']['sms_balance']=$reccuring_email['User']['sms_balance']+$monthlyPackages['MonthlyPackage']['text_messages_credit'];	
				$this->data['User']['voice_balance']=$reccuring_email['User']['voice_balance']+$monthlyPackages['MonthlyPackage']['voice_messages_credit'];
				$this->data['User']['sms_credit_balance_email_alerts']=0;		
				$this->data['User']['VM_credit_balance_email_alerts']=0;
				$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
				$this->data['User']['next_renewal_dates'] =$nextdate;	
				$this->User->save($this->data);
				
				app::import('Model','Invoice');	
				$this->Invoice=new Invoice();	
				$invoice['user_id']=$reccuring_email['User']['id'];
				$invoice['txnid']=$_REQUEST['txn_id'];	
				$invoice['amount']=$monthlyPackages['MonthlyPackage']['amount'];
				$invoice['type']=0;	
				$invoice['created']=date("Y-m-d");	
				$this->Invoice->save($invoice);
				if($reccuring_email['User']['active']==1){
					app::import('Model','Referral');
					$this->Referral=new Referral();
					$referraldetails = $this->Referral->find('all',array('conditions' => array('Referral.referred_by'=>$reccuring_email['User']['id'])));
					$percentage=$monthlyPackages['MonthlyPackage']['amount']*RECURRING_REFERRAL_PERCENT/100;
					if(!empty($referraldetails)){
						foreach($referraldetails as $referraldetail){
							$referral['id']=$referraldetail['Referral']['id'];
							$referral['amount']=$percentage;
							$referral['paid_status']=0;
							$this->Referral->save($referral);
						}
					}
				}
			}
	
		}
		ob_start();
		print_r($_REQUEST);
		print_r($_POST);
		print_r($_GET);	
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("payment/recurringpayment".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);
	}
	function payment(){
		$this->layout = 'default';
		$response = $this->Checkout->signup();
	}
	function returnurl(){
		ob_start();
		print_r($_REQUEST);
		$this->autoRender = false;
		$user_id=$this->Session->read('User.id');
		//$user_id=$_REQUEST['merchant_order_id'];
		app::import('Model','Package');
		$this->Package=new Package();
		/*  $_REQUEST['product_id']=2;
		$_REQUEST['credit_card_processed']='Y';
		$_REQUEST['email']='pankaj.oditi@gmail.com';
		$_REQUEST['order_number']='123224423';  */
		$Packageid = $this->Package->find('first',array('conditions' => array('Package.product_id'=>$_REQUEST['product_id'])));
		$balance = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		app::import('Model','MonthlyPackage');				
		$this->MonthlyPackage=new MonthlyPackage();
		$monthlyPackageid = $this->MonthlyPackage->find('first',array('conditions' => array('MonthlyPackage.id'=>$balance['User']['package'])));
		app::import('Model','Config');	
		$this->Config = new Config();
		$configdetails=$this->Config->find('first');		               
		if($_REQUEST['credit_card_processed']=='Y'){
			if($_REQUEST['product_id']==$configdetails['Config']['2CO_account_activation_prod_ID']){
				$this->data['User']['id']=$this->Session->read('User.id');
				$this->data['User']['sms_balance']=$configdetails['Config']['free_sms'];
				$this->data['User']['voice_balance']=$configdetails['Config']['free_voice'];
				$this->data['User']['active']=1;	
				$this->User->save($this->data);
					
				app::import('Model','Referral');	
				$this->Referral = new Referral();
				$Referraldetails = $this->Referral->find('first',array('conditions' => array('Referral.user_id'=>$this->Session->read('User.id'))));
				if(!empty($Referraldetails)){
					$referral['id']=$Referraldetails['Referral']['id'];
					$referral['account_activated']=1;	
					$this->Referral->save($referral);	
				}	
				$balance1 = $this->User->find('first',array('conditions' => array('User.id'=>$this->Session->read('User.id'))));	//-------------------------------------------------------------------------------//
				//Activation mail function
				$sitename=str_replace(' ','',SITENAME);
				$this->Email->to = $balance1['User']['email'];
				$this->Email->subject = 'Account has been activated';
				$this->Email->from = $sitename;
				$this->Email->template = 'membership'; // note no '.ctp'
				//Send as 'html', 'text' or 'both' (default is 'text')
				$this->Email->sendAs = 'html'; // because we like to send pretty mail
				//Set view variables as normal
				$this->set('data', $balance1);
				//Do not pass any args to send()
				$this->Email->send();
				//------------------------------------------------------------------//
				$this->Session->setFlash(__('Thank you for activating your account with us.', true));  
			}else if($_REQUEST['product_id']==$monthlyPackageid['MonthlyPackage']['product_id']){
			        $this->data['User']['id']=$user_id;
					$this->data['User']['sms_balance']=$balance['User']['sms_balance']+$monthlyPackageid['MonthlyPackage']['text_messages_credit'];
					$this->data['User']['voice_balance']=$balance['User']['voice_balance']+$monthlyPackageid['MonthlyPackage']['voice_messages_credit'];
					$this->data['User']['sms_credit_balance_email_alerts']=0;
					$this->data['User']['recurring_checkout_email']=$_REQUEST['email'];
					$this->data['User']['VM_credit_balance_email_alerts']=0;
					$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
					$this->data['User']['next_renewal_dates'] =$nextdate;
					$this->User->save($this->data);
					
					app::import('Model','Invoice');
					$this->Invoice=new Invoice();
					$invoice['user_id']=$user_id;
					$invoice['txnid']=$_REQUEST['order_number'];
					$invoice['type']=1;
					$invoice['amount']=$monthlyPackageid['MonthlyPackage']['amount'];
					$invoice['created']=date("Y-m-d");
					$this->Invoice->save($invoice);
					
					app::import('Model','Referral');	
					$this->Referral = new Referral();
					$Referraldetails = $this->Referral->find('first',array('conditions' => array('Referral.user_id'=>$user_id)));
					if(!empty($Referraldetails)){
						$referral['id']=$Referraldetails['Referral']['id'];
						$referral['account_activated']=1;	
						$this->Referral->save($referral);
					}
			}else if(!empty($Packageid)){
				$user_id=$this->Session->read('User.id');
				app::import('Model','User');	
				$this->User=new User();
				$smsbalance = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
				if($Packageid['Package']['type']=='text'){
				    $this->data['User']['id']=$user_id;
					$this->data['User']['sms_balance']=$smsbalance['User']['sms_balance']+$Packageid['Package']['credit'];
					$this->data['User']['sms_credit_balance_email_alerts']=0;
					$this->User->save($this->data);
					
					app::import('Model','Invoice');
					$this->Invoice=new Invoice();
					$invoice['user_id']=$user_id;
					$invoice['txnid']=$_REQUEST['order_number'];
					$invoice['type']=1;
					$invoice['amount']=$Packageid['Package']['amount'];
					$invoice['created']=date("Y-m-d");
					$this->Invoice->save($invoice);
					$this->Session->setFlash(__('Thank you for your SMS credit package purchase!', true)); 
				}else if($Packageid['Package']['type']=='voice'){
				    $this->data['User']['id']=$user_id;
					$this->data['User']['voice_balance']=$smsbalance['User']['voice_balance']+$Packageid['Package']['credit'];
					$this->data['User']['VM_credit_balance_email_alerts']=0;
					$this->User->save($this->data);
					
					app::import('Model','Invoice');
					$this->Invoice=new Invoice();
					$invoice['user_id']=$user_id;
					$invoice['txnid']=$_REQUEST['order_number'];
					$invoice['type']=1;
					$invoice['amount']=$Packageid['Package']['amount'];
					$invoice['created']=date("Y-m-d");
					$this->Invoice->save($invoice);
					$this->Session->setFlash(__('Thank you for your voice credit package purchase!', true));
				}
			}
		}
			
		$out1 = ob_get_contents();
		ob_end_clean();
		$file = fopen("payment/payments".time().".txt", "w");
		fwrite($file, $out1); 
		fclose($file);	
	}	



    function paymentsucess(){
	
		$this->autoRender = false;
		if($_POST['message_type']=='RECURRING_INSTALLMENT_SUCCESS'){
			app::import('Model','User');		
			$this->User=new User();
		    $customeremail = $this->User->find('first',array('conditions' => array('User.recurring_checkout_email'=>$_POST['customer_email'])));
			if(!empty($customeremail)){
				app::import('Model','MonthlyPackage');	
				$this->MonthlyPackage=new MonthlyPackage();
				$monthlyPackages = $this->MonthlyPackage->find('first',array('conditions' => array('MonthlyPackage.id'=>$customeremail['User']['package'])));
				$this->data['User']['id']=$customeremail['User']['id'];	
				$this->data['User']['sms_balance']=$customeremail['User']['sms_balance']+$monthlyPackages['MonthlyPackage']['text_messages_credit'];	
				$this->data['User']['voice_balance']=$customeremail['User']['voice_balance']+$monthlyPackages['MonthlyPackage']['voice_messages_credit'];
				$this->data['User']['sms_credit_balance_email_alerts']=0;	
				$this->data['User']['VM_credit_balance_email_alerts']=0;
				$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
				$this->data['User']['next_renewal_dates'] =$nextdate;	
				$this->User->save($this->data);
				
				app::import('Model','Invoice');	
				$this->Invoice=new Invoice();	
				$invoice['user_id']=$customeremail['User']['id'];
				$invoice['txnid']=$_POST['sale_id'];	
				$invoice['amount']=$monthlyPackages['MonthlyPackage']['amount'];
				$invoice['type']=1;		
				$invoice['created']=date("Y-m-d");	
				$this->Invoice->save($invoice);
				if($customeremail['User']['active']==1){
					app::import('Model','Referral');
					$this->Referral=new Referral();
					$referraldetail = $this->Referral->find('all',array('conditions' => array('Referral.referred_by'=>$customeremail['User']['id'])));
					$percentage=$monthlyPackages['MonthlyPackage']['amount']*RECURRING_REFERRAL_PERCENT/100;
				
					if(!empty($referraldetail)){
						foreach($referraldetail as $referralnew){
							$referral['id']=$referralnew['Referral']['id'];
							$referral['amount']=$percentage;
							$referral['paid_status']=0;
							$this->Referral->save($referral);
						}
					}
				}
			}
		}else if($_POST['message_type']=='RECURRING_STOPPED'){	
		app::import('Model','User');		
		$this->User=new User();
		$customerdetails = $this->User->find('first',array('conditions' => array('User.recurring_checkout_email'=>$_POST['customer_email'])));
			if(!empty($customerdetails)){
				$this->data['User']['id']=$customerdetails['User']['id'];			
				$this->data['User']['active'] = 0;	
				$this->User->save($this->data);
			}	
		}
	}	
				
	function checksale(){
		$this->autoRender = false;
		$response = $this->Checkout->checksale();
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
	function autologin($user_id,$password){ 
		$this->autoRender = false;
                $user_id = base64_decode($user_id);
                $password = base64_decode($password);
		$someone = $this->User->find('first', array('conditions' => array('User.id' =>$user_id,'User.password' =>$password)));
		if(!empty($someone)){
  			$this->Session->write('User', $someone['User']);
			$pay_activation_fee=PAY_ACTIVATION_FEES;
			if($someone['User']['active'] == 0 && $pay_activation_fee == 1){
				$this->redirect(array('controller' =>'users', 'action' => 'dashboard'));
			}else{
				$this->redirect(array('controller' =>'users', 'action' => 'profile'));
			}
		}
	}
	/*User Login*/
	function login(){ 
		$this->set('title_for_layout', 'Login');
		//$this->layout = 'default';
		$this->layout = 'login_layout';
		if(!empty($this->data)){
			$this->User->set($this->data);
			$this->User->validationSet = 'Userlogin';
			if ($this->User->validates()){
				$someone = $this->User->find('first', array('conditions' => array('username' =>$this->data['User']['usrname'])));
				if($someone['User']['register']==1){
					if(!empty($someone['User']['password']) && $someone['User']['password'] == md5($this->data['User']['passwrd'])){
						$this->Session->write('User', $someone['User']);
						
						$pay_activation_fee=PAY_ACTIVATION_FEES;
						
						if($someone['User']['active'] == 0 && $pay_activation_fee == 1){
							$this->redirect(array('controller' =>'users', 'action' => 'dashboard'));
						}else{
							$this->redirect(array('controller' =>'users', 'action' => 'profile'));
						}	
					}else{
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
	
	function activation($id=null){
		$this->layout= 'admin_new_layout';
		$this->set('id',$id);
		if(isset($_POST['stripeToken'])){
			if($_POST['stripeToken'] !=''){
				$amount = REGISTRATION_CHARGE * 100;
				$desc = SITENAME.' Membership Activation';
				$setApiKey = SECRET_KEY;
				$currency = PAYMENT_CURRENCY_CODE;
				\Stripe\Stripe::setApiKey(SECRET_KEY);
				try {
					$charge = \Stripe\Charge::create(array(
						"amount" => REGISTRATION_CHARGE * 100, 
						"currency" => $currency,
						"description" => $desc,
						"source"=>$_POST['stripeToken']
						)
					);
					if(isset($charge->id)){
						$this->data['User']['id']=$this->Session->read('User.id');
						$this->data['User']['sms_balance']=FREE_SMS;
						$this->data['User']['voice_balance']=FREE_VOICE;
						$this->data['User']['active']=1;	
						$this->User->save($this->data);
						app::import('Model','Referral');	
						$this->Referral = new Referral();
						$Referraldetails = $this->Referral->find('first',array('conditions' => array('Referral.user_id'=>$this->Session->read('User.id'))));
						if(!empty($Referraldetails)){
							$referral['id']=$Referraldetails['Referral']['id'];
							$referral['account_activated']=1;	
							$this->Referral->save($referral);	
						}	
						$balance1 = $this->User->find('first',array('conditions' => array('User.id'=>$this->Session->read('User.id'))));	//-------------------------------------------------------------------------------//
						//Activation mail function
						$sitename=str_replace(' ','',SITENAME);
						$this->Email->to = $balance1['User']['email'];
						$this->Email->subject = 'Account has been activated';
						$this->Email->from = $sitename;
						$this->Email->template = 'membership'; // note no '.ctp'
						//Send as 'html', 'text' or 'both' (default is 'text')
						$this->Email->sendAs = 'html'; // because we like to send pretty mail
						//Set view variables as normal
						$this->set('data', $balance1);
						//Do not pass any args to send()
						$this->Email->send();
						//------------------------------------------------------------------//
						$this->Session->setFlash(__('Thank you for activating your account with us', true)); 
					}
					$this->redirect(array('controller' =>'users', 'action'=>'profile'));
				} catch (\Stripe\Error\Card $e) {
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));
				} catch (\Stripe\Error\RateLimit $e) {
                                       // Too many requests made to the API too quickly
                                        $this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));
                                } catch (\Stripe\Error\InvalidRequest $e) {
                                       // Invalid parameters were supplied to Stripe's API
                                        $this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));
                                } catch (\Stripe\Error\Authentication $e) {
                                       // Authentication with Stripe's API failed
                                       // (maybe you changed API keys recently)
                                        $this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));
                                } catch (\Stripe\Error\ApiConnection $e) {
                                       // Network communication with Stripe failed
                                        $this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));
                                } catch (\Stripe\Error\Base $e) {
                                       // Display a very generic error to the user, and maybe send
                                       // yourself an email
                                        $this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));
                                } catch (Exception $e) {
                                        $this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));

                                }

			}else{
				$this->Session->setFlash('Please try again');
				$this->redirect(array('controller' =>'users', 'action'=>'activation/'.$id));
			}
		}
	}
	
	function thank_you(){
		$this->layout= 'admin_new_layout';
		//print_r($_POST);exit;
	}
	
	/*
	User frontend Logout
	*/
	function logout()
	{
		$this->Session->delete('User');
		$this->Session->delete('Token');
		$this->Session->delete('TwitterSuccess');
		if (LOGOUT_URL !=''){
                   $this->redirect(LOGOUT_URL);
                }else{
                   $this->redirect('/');
                }
	}
	
	function forgot_password(){
		$this->set('title_for_layout', 'Forgot Password');
		$this->layout = 'login_layout';
		if(!empty($this->data)){
			if($this->data['User']['email'] !=""){
				$someone=$this->User->find('first', array('conditions' => array('User.email' =>$this->data['User']['email'])));
				if(!empty($someone)){
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
					$this->Session->setFlash('Please check your email for instructions to reset your password');
				}else{
					$this->Session->setFlash('We do not have any accounts registered with that email.');
				}	
			}else{
				$this->User->invalidate('email', 'Please enter your Email id');
			}
		}
	}//fun
	
	function pwdreset($id, $code){
			$someone=$this->User->find('first', array('conditions' => array('User.id' => $id, 'User.password' => $code)));
			//print_r($someone);
			if(!empty($someone)){
				/*****************************Random number**********************************************/
				function random_generator($digits){
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
				$this->Session->setFlash('Your new password has now been sent to your email. Use this password to login');
				
				$this->redirect(array('controller' =>'users', 'action'=>'login'));
			}else{
				$this->Session->setFlash('Invalid information');
				$this->redirect(array('controller' =>'users', 'action'=>'login'));
			}
		}

	function add() {
		$this->set('title_for_layout', 'Register');
		 $this->layout = 'login_layout';


		if (!empty($this->data)) {

                    $accounts = $this->User->find('count',array('conditions' => array('User.active'=>1)));

                    if ($accounts >= NUMACCOUNTS){

                        $sitename=str_replace(' ','',SITENAME);
			$subject=SITENAME." :: Account Limit Reached";	
			$this->Email->to = SUPPORT_EMAIL;	
			$this->Email->subject = $subject;
			$this->Email->from = $sitename;
			$this->Email->template = 'account_limit_notice';
			$this->Email->sendAs = 'html';
			$this->Email->Controller->set('username', $this->data['User']['username']);
			$this->Email->Controller->set('email', $this->data['User']['email']);
                        $this->Email->Controller->set('firstname', $this->data['User']['first_name']);
                        $this->Email->Controller->set('lastname', $this->data['User']['last_name']);
                        $this->Email->Controller->set('phone', $this->data['User']['phone']);
			$this->Email->send();

                        $this->Session->setFlash('System has reached maximum number of active accounts. Please contact us to increase capacity.');
                        $this->redirect(array('controller' =>'users', 'action'=>'add'));
                    }

			function random_generator($digits){
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

                        $phone = $this->data['User']['phone'];
                        
                        if(NUMVERIFY !=''){
                           $numbervalidation = $this->validateNumber($phone);
                           $errorcode = $numbervalidation['error']['code'];
                           $valid = $numbervalidation['valid'];
                                                     
                           if($errorcode == ''){
                              if ($valid != 1){
                                  $this->Session->setFlash('The phone number you entered is not valid. Please provide a valid working phone number with country code in the proper format. US format: 19999999999 UK format: 449999999999');
                                  $this->redirect(array('controller' =>'users', 'action'=>'add'));
                              }
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
                       		
			$this->User->create();

                        $country = $this->data['User']['user_country'];
                        
                        if ($country == "Australia"){
                           $this->data['User']['alphasender'] = AUSTRALIA;
                        }else if ($country == "Austria"){
                           $this->data['User']['alphasender'] = AUSTRIA;
                        }else if ($country == "Belgium"){
                           $this->data['User']['alphasender'] = BELGIUM;
                        }else if ($country == "Canada"){
                           $this->data['User']['alphasender'] = CANADA;
                        }else if ($country == "Denmark"){
                           $this->data['User']['alphasender'] = DENMARK;
                        }else if ($country == "Estonia"){
                           $this->data['User']['alphasender'] = ESTONIA;
                        }else if ($country == "Finland"){
                           $this->data['User']['alphasender'] = FINLAND;
                        }else if ($country == "France"){
                           $this->data['User']['alphasender'] = FRANCE;
                        }else if ($country == "Germany"){
                           $this->data['User']['alphasender'] = GERMANY;
                        }else if ($country == "Hong Kong"){
                           $this->data['User']['alphasender'] = HONGKONG;
                        }else if ($country == "Hungary"){
                           $this->data['User']['alphasender'] = HUNGARY;
                        }else if ($country == "Ireland"){
                           $this->data['User']['alphasender'] = IRELAND;
                        }else if ($country == "Israel"){
                           $this->data['User']['alphasender'] = ISRAEL;
                        }else if ($country == "Lithuania"){
                           $this->data['User']['alphasender'] = LITHUANIA;
                        }else if ($country == "Mexico"){
                           $this->data['User']['alphasender'] = MEXICO;
                        }else if ($country == "Netherlands"){
                           $this->data['User']['alphasender'] = NETHERLANDS;
                        }else if ($country == "Norway"){
                           $this->data['User']['alphasender'] = NORWAY;
                        }else if ($country == "Poland"){
                           $this->data['User']['alphasender'] = POLAND;
                        }else if ($country == "Puerto Rico"){
                           $this->data['User']['alphasender'] = PUERTORICO;
                        }else if ($country == "Spain"){
                           $this->data['User']['alphasender'] = SPAIN;
                        }else if ($country == "Sweden"){
                           $this->data['User']['alphasender'] = SWEDEN;
                        }else if ($country == "Switzerland"){
                           $this->data['User']['alphasender'] = SWITZERLAND;
                        }else if ($country == "United Kingdom"){
                           $this->data['User']['alphasender'] = UNITEDKINGDOM;
                        }else if ($country == "United States"){
                           $this->data['User']['alphasender'] = UNITEDSTATES;
                        }

			$random_number= random_generator(4);
			$this->data['User']['password'] = md5($this->data['User']['passwrd']);
			$this->data['User']['email_alert_options'] = 1;
			$this->data['User']['email_alert_credit_options'] = 1;
			$this->data['User']['api_type'] = API_TYPE;
			$this->data['User']['account_activated'] = $random_number;
                        $userIP = $this->getRealUserIp();
                        $this->data['User']['IP_address'] = $userIP;
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
				//$recurringrefArray = $this->Cookie->read('recurringrefArray');
				//print_r($referral);
				//print_r($recurringrefArray);
				if(!empty($referral)){
					$this->loadModel('Referral');
					$refData = array('Referral' =>array('user_id' =>$last_id,'referred_by' =>$referral['id'], 'url' =>$referral['url'],'type' =>0 ,'amount' =>REFERRAL_AMOUNT));
					$this->Referral->save($refData);
					$this->Cookie->delete('refArray');
					
				}/*else if(!empty($recurringrefArray)){
				
				    app::import('Model','Referral');
	   
                    $this->Referral = new Referral();
					
					app::import('Model','User');
	   
                    $this->User = new User();
	
	                $userdetails = $this->User->find('first', array('conditions' => array('User.id' =>$recurringrefArray['id'])));
					
					/* echo "<pre>";
					print_r($userdetails);
					echo "</pre>"; */
					/*if($userdetails['User']['active']==1){
					$this->loadModel('MonthlyPackage');
					
		            $monthlydetails=$this->MonthlyPackage->find('first' , array('conditions' => array('MonthlyPackage.id' => $userdetails['User']['package'])));
					
					/* echo "<pre>";
					print_r($monthlydetails);
					echo "</pre>"; */
					
					 /*$percentage=$monthlydetails['MonthlyPackage']['amount']*RECURRING_REFERRAL_PERCENT/100;
					
					$refData = array('Referral' =>array('user_id' =>$last_id,'referred_by' =>$recurringrefArray['id'], 'url' =>$recurringrefArray['url'],'type' =>1, 'amount' =>$percentage));
					$this->Referral->save($refData);
					$this->Cookie->delete('recurringrefArray');
					
					}
				}*/
				
				$this->Session->setFlash(__('You have been saved as a user.', true));
				$this->redirect(array('action' => 'success'));
			} 
		}
	}
	
	function user_activate_account($id=null,$admin=0){
		$this->autoRender=false;
		if(!empty($id)){
			app::import('Model','User');
			$this->User = new User();
			$activate_user = $this->User->find('first', array('conditions' => array('User.account_activated' =>$id)));

                        $accounts = $this->User->find('count',array('conditions' => array('User.active'=>1)));

                        if ($accounts >= NUMACCOUNTS){

                           $sitename=str_replace(' ','',SITENAME);
			   $subject=SITENAME." :: Account Limit Reached";	
			   $this->Email->to = SUPPORT_EMAIL;	
			   $this->Email->subject = $subject;
			   $this->Email->from = $sitename;
			   $this->Email->template = 'account_limit_notice';
			   $this->Email->sendAs = 'html';
			   $this->Email->Controller->set('username', $activate_user['User']['username']);
			   $this->Email->Controller->set('firstname', $activate_user['User']['first_name']);
			   $this->Email->Controller->set('lastname', $activate_user['User']['last_name']);
			   $this->Email->Controller->set('email', $activate_user['User']['email']);
                           $this->Email->Controller->set('phone', $activate_user['User']['phone']);
			   $this->Email->send();

                           $this->Session->setFlash('System has reached maximum number of active accounts, therefore your account could not be activated. Please contact us to increase capacity.');
                           $this->redirect(array('controller' =>'users', 'action'=>'login'));
                    }

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
			if($pay_activation_fee==2){
				app::import('Model','User');
                $this->User = new User();
				$activationfees['id'] = $activate_user['User']['id'];
				$activationfees['active'] =1;
				$activationfees['sms_balance'] =FREE_SMS;
				$activationfees['voice_balance'] =FREE_VOICE;
				$activationfees['pay_activation_fees_active'] =PAY_ACTIVATION_FEES;
				$this->User->save($activationfees);
			}else{
				$activationfeesdetails['id'] = $activate_user['User']['id'];
				$activationfeesdetails['pay_activation_fees_active'] =PAY_ACTIVATION_FEES;
				$this->User->save($activationfeesdetails);		
			}
						
			$recurringrefArray = $this->Cookie->read('recurringrefArray');	
			if(!empty($recurringrefArray)){
				app::import('Model','Referral');
				$this->Referral = new Referral();
				app::import('Model','User');
				$this->User = new User();
	
	            $userdetails = $this->User->find('first', array('conditions' => array('User.id' =>$recurringrefArray['id'])));
		
				if($userdetails['User']['active']==1){
					$this->loadModel('MonthlyPackage');
		            $monthlydetails=$this->MonthlyPackage->find('first' , array('conditions' => array('MonthlyPackage.id' => $userdetails['User']['package'])));
					$percentage=$monthlydetails['MonthlyPackage']['amount']*RECURRING_REFERRAL_PERCENT/100;
					$refData = array('Referral' =>array('user_id' =>$activate_user['User']['id'],'referred_by' =>$recurringrefArray['id'], 'url' =>$recurringrefArray['url'],'type' =>1, 'amount' =>$percentage));
					$this->Referral->save($refData);
					$this->Cookie->delete('recurringrefArray');
					
				}
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
			if ($admin==0){
				$this->redirect(array('controller' =>'users', 'action'=>'login'));
			}
		}
	}
	
	function success(){
		$this->layout= 'login_layout';	
	}
	
	
	function dashboard(){
		$this->layout= 'admin_new_layout';	
		$user_id = $this->getLoggedInUserId();
		$this->User->recursive = 0;
		$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'fields' =>'active'));
		/*if($user['User']['active'] == 1){
			$this->redirect(array('controller' =>'users', 'action'=>'profile'));
		}*/

		$pay_activation_fee=PAY_ACTIVATION_FEES;
						
		if($user['User']['active'] == 1 || ($pay_activation_fee == 2 && $user['User']['active'] == 0)){
			$this->redirect(array('controller' =>'users', 'action' => 'profile'));
		}

	}
	function purchasenumber() {
		$this->layout= 'popup';
	}
	function profile() {
		$this->set('title_for_layout', 'Profile');
		$this->layout= 'admin_new_layout';
		//$this->layout = 'login_layout';
		$userId = $this->getLoggedInUserId();
		$userActive = $this->getLoggedUserDetails();
		$activeid = $userActive['User']['active'];
		$this->loadModel('Log');
		//$activeid = $this->Session->read('User.active');
		$user = $this->Session->read('User');
		if($activeid==1){
			$month = date('m');
			$year = date('Y');
                        $lastyear = date('Y', strtotime('-1 year'));

                        $this->Log->recursive = -1;
 
			$inbox =  $this->Log->find('all', array('conditions' => array('MONTH(Log.created)' =>$month,'YEAR(Log.created)' =>$year,'Log.user_id' =>$userId, 'Log.route' => 'inbox' ,'msg_type' => 'text'),'fields' => array('Log.created')));
			$this->set('inbox',$inbox);
			$outbox =  $this->Log->find('all', array('conditions' => array('MONTH(Log.created)' =>$month,'YEAR(Log.created)' =>$year,'Log.user_id' =>$userId, 'Log.route' => 'outbox' ,'msg_type' => 'text'),'fields' => array('Log.created')));
			$this->set('outbox',$outbox);
			$vminbox =  $this->Log->find('all', array('conditions' => array('MONTH(Log.created)' =>$month,'YEAR(Log.created)' =>$year,'Log.user_id' =>$userId, 'Log.route' => 'inbox' ,'msg_type' => 'broadcast'),'fields' => array('Log.created')));
			$this->set('vminbox',$vminbox);
			
			//Overall
			app::import('Model','ContactGroup');
			$this->ContactGroup = new ContactGroup();
			
			$subscriber=$this->ContactGroup->find('count', array('conditions' => array('ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3))));	
			$unsubscriber=$this->ContactGroup->find('count', array('conditions' => array('ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3),'ContactGroup.un_subscribers'=>1)));
			
                        if ($subscriber !=0){
			    $total=$subscriber-$unsubscriber;
			    $percentage=$total/$subscriber*100;
			    $this->set('percentage',$percentage);
			}

                        //Yearly
			$yearlysubscriber=$this->ContactGroup->find('count', array('conditions' => array('YEAR(ContactGroup.created)' =>$year,'ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3))));	
			$yearlyunsubscriber=$this->ContactGroup->find('count', array('conditions' => array('YEAR(ContactGroup.created)' =>$year,'ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3),'ContactGroup.un_subscribers'=>1)));
			                        
			if($yearlysubscriber !=0){
			    $yearlytotal=$yearlysubscriber-$yearlyunsubscriber;
			    $yearlypercentage = $yearlytotal/$yearlysubscriber*100;
			    $this->set('yearlypercentage',$yearlypercentage);
			}

			//monthly
			$monthlysubscriber=$this->ContactGroup->find('count', array('conditions' => array('MONTH(ContactGroup.created)' =>$month,'YEAR(ContactGroup.created)' =>$year,'ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3))));	
			$monthlyunsubscriber=$this->ContactGroup->find('count', array('conditions' => array('MONTH(ContactGroup.created)' =>$month,'YEAR(ContactGroup.created)' =>$year,'ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3),'ContactGroup.un_subscribers'=>1)));
			                        
			if($monthlysubscriber !=0){
			    $monthlytotal=$monthlysubscriber-$monthlyunsubscriber;
			    $monthlypercentage = $monthlytotal/$monthlysubscriber*100;
			    $this->set('monthlypercentage',$monthlypercentage);
			}
			
                        //weekly
                        $week = date('W');
			$current_date =date('Y-m-d');
			$one_week_date =date('Y-m-d',strtotime('-7 days'));
			$weeklysubscriber=$this->ContactGroup->find('count', array('conditions' => array('DATE(ContactGroup.created) >=' =>$one_week_date,'DATE(ContactGroup.created) <=' =>$current_date,'ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3))));	
			$weeklyunsubscriber=$this->ContactGroup->find('count', array('conditions' => array('DATE(ContactGroup.created) >=' =>$one_week_date,'DATE(ContactGroup.created) <=' =>$current_date,'ContactGroup.user_id' =>$userId,'ContactGroup.subscribed_by_sms' =>array(1,2,3),'ContactGroup.un_subscribers'=>1)));

			                        
			if ($weeklysubscriber !=0){
			    $weeklytotal=$weeklysubscriber-$weeklyunsubscriber;
			    $weeklypercentage = $weeklytotal/$weeklysubscriber*100;
			    $this->set('weeklypercentage',$weeklypercentage);
			}
			
			app::import('Model','MonthlyPackage');
			$this->MonthlyPackage = new MonthlyPackage();
			$someone = $this->User->find('first', array('conditions' => array('User.id' =>$userId)));
			$packageid = $someone['User']['package'];
			$package=$this->MonthlyPackage->find('first', array('conditions' => array('MonthlyPackage.id' =>$packageid)));		
			$this->set('packages',$package);

                        app::import('Model','MonthlyNumberPackage');
			$this->MonthlyNumberPackage = new MonthlyNumberPackage();
			$numberpackageid = $someone['User']['number_package'];
			$numberpackage=$this->MonthlyNumberPackage->find('first', array('conditions' => array('MonthlyNumberPackage.id' =>$numberpackageid)));		
			$this->set('numberpackages',$numberpackage);

			app::import('Model','Contact');
			$this->Contact = new Contact();
			
			$query = "SELECT contact_groups.created, groups.keyword, contact_groups.subscribed_by_sms FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$userId." and contact_groups.un_subscribers=0 and contact_groups.subscribed_by_sms !=0 and YEAR(contact_groups.created) = '$year'";	
			$subscribers=$this->Contact->query($query);
			$this->set('subscribers',$subscribers);

                        $query = "SELECT contact_groups.created, groups.keyword, contact_groups.subscribed_by_sms FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$userId." and contact_groups.un_subscribers=0 and contact_groups.subscribed_by_sms !=0 and YEAR(contact_groups.created) = '$lastyear'";	
			$subscriberslastyear=$this->Contact->query($query);
			$this->set('subscriberslastyear',$subscriberslastyear);

			$query_un = "SELECT contact_groups.created, groups.keyword FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$userId." and contact_groups.un_subscribers=1 and contact_groups.subscribed_by_sms !=0 and YEAR(contact_groups.created) = '$year'";	
			$un_subscribers=$this->Contact->query($query_un);
			$this->set('un_subscribers',$un_subscribers);

                        $query_un = "SELECT contact_groups.created, groups.keyword FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$userId." and contact_groups.un_subscribers=1 and contact_groups.subscribed_by_sms !=0 and YEAR(contact_groups.created) = '$lastyear'";	
			$un_subscriberslastyear=$this->Contact->query($query_un);
			$this->set('un_subscriberslastyear',$un_subscriberslastyear);

			//Invoice list
			app::import('Model','Invoice');
			$this->Invoice = new Invoice();
			$invoicedetil = $this->Invoice->find('all',array('conditions' => array('Invoice.user_id'=>$userId),'order' =>array('Invoice.id' => 'desc'),'limit' =>5));
			$this->set('invoicedetils',$invoicedetil);
			//Referrals list
			app::import('Model','Referral');
			$this->Referral = new Referral();
			$referrals = $this->Referral->find('all',array('conditions' => array('Referral.referred_by'=>$this->getLoggedInUserId(),'Referral.account_activated'=>1),'order' =>array('Referral.id' => 'desc'),'limit' =>5));
			$this->set('referrals',$referrals);

			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			if(API_TYPE==1){
				$numbers = $this->UserNumber->find('all',array('conditions' => array('UserNumber.user_id'=>$userId,'UserNumber.api_type'=>1),'order' =>array('UserNumber.id' => 'desc'),'limit' =>5));
			}elseif(API_TYPE==0){
				$numbers = $this->UserNumber->find('all',array('conditions' => array('UserNumber.user_id'=>$userId,'UserNumber.api_type'=>0),'order' =>array('UserNumber.id' => 'desc'),'limit' =>5));
			}elseif(API_TYPE==3){
				$numbers = $this->UserNumber->find('all',array('conditions' => array('UserNumber.user_id'=>$userId,'UserNumber.api_type'=>3),'order' =>array('UserNumber.id' => 'desc'),'limit' =>5));
			}
			$this->set('numbers',$numbers);

			app::import('Model','Group');
			$this->Group = new Group();
			$keywords =  $this->Group->find('all', array('conditions' => array('Group.user_id' =>$userId),'fields' => array('Group.keyword')));
			$this->set('keywords',$keywords);

                        $query = "SELECT  DISTINCT contacts.carrier, count( * ) 'count' FROM contacts, contact_groups WHERE contact_groups.contact_id = contacts.id 
                        AND contact_groups.user_id = ".$userId." AND contact_groups.un_subscribers=0 AND contact_groups.subscribed_by_sms !=0 AND contacts.carrier !='' GROUP BY contacts.carrier";	
			$carriers=$this->Contact->query($query);
			$this->set('carriers',$carriers);

$query = "SELECT groups.keyword, count(*) 'count'  FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$userId." and contact_groups.un_subscribers=0 and contact_groups.subscribed_by_sms !=0 group by groups.keyword";	
			$keywordcounts=$this->Contact->query($query);
			$this->set('keywordcounts',$keywordcounts);

$query = "SELECT  DISTINCT contact_groups.subscribed_by_sms 'source', count( * ) 'count' FROM contacts, contact_groups WHERE contact_groups.contact_id = contacts.id 
                        AND contact_groups.user_id = ".$userId." AND contact_groups.un_subscribers=0 AND contact_groups.subscribed_by_sms !=0 GROUP BY contact_groups.subscribed_by_sms";	
			$sourcecounts=$this->Contact->query($query);
			$this->set('sourcecounts',$sourcecounts);

                        
		}else{
			$this->Session->setFlash(__('Your account is not active. Please activate your account', true));
			$pay_activation_fee=PAY_ACTIVATION_FEES;
			if($pay_activation_fee == 1){
				$this->redirect(array('action' => 'dashboard'));
			}
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
		$this->layout= 'admin_new_layout';
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
	function edit(){       
		$this->layout= 'admin_new_layout';
		if (!empty($this->data)) {
			$user_arr['User']['id']=$this->data['User']['id'];
			$user_arr['User']['first_name']=$this->data['User']['first_name'];
			$user_arr['User']['last_name']=$this->data['User']['last_name'];
			$user_arr['User']['email']=$this->data['User']['email'];
            $user_arr['User']['phone']=$this->data['User']['phone'];
            $user_arr['User']['company_name']=$this->data['User']['company_name'];
			$user_arr['User']['paypal_email']=$this->data['User']['paypal_email'];
			$user_arr['User']['voicemailnotifymail']=$this->data['User']['voicemailnotifymail'];
			$user_arr['User']['welcome_msg_type']=$this->data['User']['welcome_msg_type'];
			$user_arr['User']['defaultgreeting']=$this->data['User']['defaultgreeting'];
			$user_arr['User']['timezone']=$this->data['User']['timezone'];
                        $user_arr['User']['user_country']=$this->data['User']['user_country'];
			if(isset($this->data['User']['mp3']['name'])){
				if($this->data['User']['mp3']['name']!=''){
					$temp_name = $this->data['User']['mp3']['tmp_name'];
					$name1 = str_replace(" ","_",$this->data['User']['mp3']['name']);
					$name2 = str_replace("&","_",$name1);
					move_uploaded_file($temp_name,"mp3/".time().$name2);
					$user_arr['User']['mp3'] = time().$name2;
				}
			}
			if ($this->User->save($user_arr)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'edit'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$id = $this->getLoggedInUserId();
			$this->data = $this->User->read(null, $id);
			$user_arr = $this->User->read(null, $id);
			$this->set('user_arr',$user_arr);
		}
	}
	function change_password(){
		$this->layout= 'admin_new_layout';	
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
		$this->layout= 'admin_new_layout';
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
	function purchase_credit_stripe($id=null){
		$this->layout = 'popup';
		$this->loadModel('Package');
		$this->set('package', $this->Package->find('first' , array('conditions' => array('id' => $id))));
		if(isset($_POST['stripeToken'])){
			if($_POST['stripeToken'] !=''){
				$package = $this->Package->find('first' , array('conditions' => array('id' => $id)));
				$desc = $package['Package']['name'];
				$setApiKey = SECRET_KEY;
				$currency = PAYMENT_CURRENCY_CODE;
				\Stripe\Stripe::setApiKey(SECRET_KEY);
				try {
					$charge = \Stripe\Charge::create(array(
						"amount" => $package['Package']['amount'] * 100, 
						"currency" => $currency,
						"description" => $desc,
						"source"=>$_POST['stripeToken']
						)
					);
					if(isset($charge->id)){
						$user = $this->User->find('first' , array('conditions' => array('User.id' =>$this->Session->read('User.id'))));
						if(!empty($user)){
							$user_arr['User']['id']=$this->Session->read('User.id');
							$user_arr['User']['active'] = 1;
							if($package['Package']['type']=='text'){
								$user_arr['User']['sms_balance']=$user['User']['sms_balance'] + $package['Package']['credit'];
								$user_arr['User']['sms_credit_balance_email_alerts'] = 0;
							}
							if($package['Package']['type']=='voice'){
								$user_arr['User']['voice_balance']=$user['User']['voice_balance'] + $package['Package']['credit'];
								$user_arr['User']['VM_credit_balance_email_alerts'] = 0;
							}
							$this->User->save($user_arr);
							app::import('Model','Invoice');
							$this->Invoice=new Invoice();
							$invoice['user_id']=$this->Session->read('User.id');
							$invoice['txnid']=$charge->id;
							$invoice['type']=1;
							$invoice['amount']=$package['Package']['amount'];
							$invoice['package_name']=$package['Package']['name'];
							$invoice['created']=date("Y-m-d");
							$this->Invoice->save($invoice);
						}
					}
					$this->Session->setFlash('Thank you for your purchase! Credits updated successfully');
					$this->redirect(array('controller' =>'users', 'action'=>'profile'));
				} catch (\Stripe\Error\Card $e) {
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\RateLimit $e) {
				   // Too many requests made to the API too quickly
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\InvalidRequest $e) {
					// Invalid parameters were supplied to Stripe's API
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\Authentication $e) {
				   // Authentication with Stripe's API failed
				   // (maybe you changed API keys recently)
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\ApiConnection $e) {
				   // Network communication with Stripe failed
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\Base $e) {
				   // Display a very generic error to the user, and maybe send
				   // yourself an email
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (Exception $e) {
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				}
			}else{
				$this->Session->setFlash('Please try again');
				$this->redirect(array('controller' =>'users', 'action'=>'activation'));
			}
		}
	}
    function purchase_subscription($id){
		$this->layout = 'popup';
		$this->loadModel('MonthlyPackage');
		$this->set('monthlypackage', $this->MonthlyPackage->find('first' , array('conditions' => array('id' => $id))));
	}
	
    function purchase_subscription_stripe($id){
		$this->layout = 'popup';
		$this->loadModel('MonthlyPackage');
		$this->set('monthlypackage', $this->MonthlyPackage->find('first' , array('conditions' => array('id' => $id))));
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$this->Session->read('User.id'))));
		$this->set('users',$users);
		if(isset($_POST['stripeToken'])){
			if($_POST['stripeToken'] !=''){
				$monthlypackage = $this->MonthlyPackage->find('first' , array('conditions' => array('id' => $id)));
				$desc = $monthlypackage['MonthlyPackage']['package_name'];
				$setApiKey = SECRET_KEY;
				$currency = PAYMENT_CURRENCY_CODE;
				$user = $this->User->find('first' , array('conditions' => array('User.id' =>$this->Session->read('User.id'))));
				$username = $user['User']['username'];
				$firstname = $user['User']['first_name'];
				$lastname = $user['User']['last_name'];
				$stripecustomerid = $user['User']['stripe_customer_id'];
				$monthly_stripe_subscription_id = '';
				\Stripe\Stripe::setApiKey(SECRET_KEY);
				try {
					if ($stripecustomerid !=''){
						$customer = \Stripe\Subscription::create(array(
							"customer" => $stripecustomerid,
							"plan" => $monthlypackage['MonthlyPackage']['product_id']
						));
						if(isset($customer->id)){
							$monthly_stripe_subscription_id = $customer->id;
						}
					}else{
						$customer = \Stripe\Customer::create(array(
							"plan" => $monthlypackage['MonthlyPackage']['product_id'], 
							"source"=>$_POST['stripeToken'],
							"email"=>$this->Session->read('User.email'),
							"metadata" => array("username" =>$username, "firstname"=>$firstname, "lastname"=>$lastname)
							)
						);
						$stripecustomerid = $customer->id;
						if(isset($customer->subscriptions->data[0]->id)){
							$monthly_stripe_subscription_id = $customer->subscriptions->data[0]->id;
						}
					}
					if(isset($customer->id)){
						if(!empty($user)){
							$user_arr['User']['id']=$this->Session->read('User.id');
							$user_arr['User']['stripe_customer_id']=$stripecustomerid;
							$user_arr['User']['monthly_stripe_subscription_id']=$monthly_stripe_subscription_id;
							//$user_arr['User']['sms_balance']=$user['User']['sms_balance'] + $monthlypackage['MonthlyPackage']['text_messages_credit'];
							//$user_arr['User']['voice_balance']=$user['User']['voice_balance'] + $monthlypackage['MonthlyPackage']['voice_messages_credit'];
							$user_arr['User']['sms_credit_balance_email_alerts']=0;
							$user_arr['User']['VM_credit_balance_email_alerts']=0;
							$user_arr['User']['package']=$monthlypackage['MonthlyPackage']['id'];
							$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
							$user_arr['User']['next_renewal_dates'] =$nextdate;
                            $user_arr['User']['active']=1;
							$this->User->save($user_arr);
							app::import('Model','Referral');	
							$this->Referral = new Referral();
							$Referraldetails = $this->Referral->find('first',array('conditions' =>  array('Referral.user_id'=>$this->Session->read('User.id'))));
							if(!empty($Referraldetails)){
								$referral['id']=$Referraldetails['Referral']['id'];
								$referral['account_activated']=1;	
								$this->Referral->save($referral);
							}
						}
					}
					$this->Session->setFlash('Thanks for your purchase! Please allow 10-20 seconds for your account to be updated as we have to wait for confirmation from the credit card processor.');
					$this->redirect(array('controller' =>'users', 'action'=>'profile'));
				} catch (\Stripe\Error\Card $e) {
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\RateLimit $e) {
				   // Too many requests made to the API too quickly
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\InvalidRequest $e) {
				   // Invalid parameters were supplied to Stripe's API
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\Authentication $e) {
					// Authentication with Stripe's API failed
					// (maybe you changed API keys recently)
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\ApiConnection $e) {
					// Network communication with Stripe failed
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (\Stripe\Error\Base $e) {
					// Display a very generic error to the user, and maybe send
					// yourself an email
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				} catch (Exception $e) {
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripepayment'));
				}
			}else{
				$this->Session->setFlash('Please try again');
				$this->redirect(array('controller' =>'users', 'action'=>'activation'));
			}
		}
	}
	function purchase_subscription_stripe_numbers($id){
		$this->layout = 'popup';
		$this->loadModel('MonthlyNumberPackage');
		$this->set('monthlynumberpackage', $this->MonthlyNumberPackage->find('first' , array('conditions' => array('id' => $id))));
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$this->Session->read('User.id'))));
		$this->set('users',$users);
		if(isset($_POST['stripeToken'])){
			if($_POST['stripeToken'] !=''){
				$monthlypackage = $this->MonthlyNumberPackage->find('first' , array('conditions' => array('id' => $id)));
				$user = $this->User->find('first' , array('conditions' => array('User.id' =>$this->Session->read('User.id'))));
				$username = $user['User']['username'];
				$firstname = $user['User']['first_name'];
				$lastname = $user['User']['last_name'];
				$stripecustomerid = $user['User']['stripe_customer_id'];
				$monthly_number_subscription_id = '';
				\Stripe\Stripe::setApiKey(SECRET_KEY);
				try {
					if ($stripecustomerid != ''){
						$customer = \Stripe\Subscription::create(array(
							"customer" => $stripecustomerid,
							"plan" => $monthlypackage['MonthlyNumberPackage']['plan']
						));
						if(isset($customer->id)){
							$monthly_number_subscription_id = $customer->id;
						}
					}else{
						$customer = \Stripe\Customer::create(array(
							"plan" => $monthlypackage['MonthlyNumberPackage']['plan'], 
							"source"=>$_POST['stripeToken'],
							"email"=>$this->Session->read('User.email'),
							"metadata" => array("username" =>$username, "firstname"=>$firstname, "lastname"=>$lastname)
							)
						);
						$stripecustomerid = $customer->id;
						if(isset($customer->subscriptions->data[0]->id)){
							$monthly_number_subscription_id = $customer->subscriptions->data[0]->id;
						}
					}
					if(isset($customer->id)){
						if(!empty($user)){
							$user_arr['User']['id']=$this->Session->read('User.id');
							$user_arr['User']['stripe_customer_id']=$stripecustomerid;
							$user_arr['User']['monthly_number_subscription_id']=$monthly_number_subscription_id;
							$user_arr['User']['number_package']=$monthlypackage['MonthlyNumberPackage']['id'];
							$user_arr['User']['number_limit']=$user['User']['number_limit'] + $monthlypackage['MonthlyNumberPackage']['total_secondary_numbers'];
							$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
							$user_arr['User']['number_next_renewal_dates'] =$nextdate;
							$user_arr['User']['active']=1;
							$this->User->save($user_arr);
						}
					}
					$this->Session->setFlash('Thanks for your purchase! Your account was updated successfully');
					$this->redirect(array('controller' =>'users', 'action'=>'profile'));
				} catch (\Stripe\Error\Card $e) {
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripenumbers'));
				} catch (\Stripe\Error\RateLimit $e) {
				   // Too many requests made to the API too quickly
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripenumbers'));
				} catch (\Stripe\Error\InvalidRequest $e) {
				   // Invalid parameters were supplied to Stripe's API
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripenumbers'));
				} catch (\Stripe\Error\Authentication $e) {
				   // Authentication with Stripe's API failed
				   // (maybe you changed API keys recently)
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripenumbers'));
				} catch (\Stripe\Error\ApiConnection $e) {
				   // Network communication with Stripe failed
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripenumbers'));
				} catch (\Stripe\Error\Base $e) {
				   // Display a very generic error to the user, and maybe send
				   // yourself an email
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripenumbers'));
				} catch (Exception $e) {
					$this->Session->setFlash($e->getMessage());
					$this->redirect(array('controller' =>'users', 'action'=>'stripenumbers'));
				}
			}else{
				$this->Session->setFlash('Please try again');
				$this->redirect(array('controller' =>'users', 'action'=>'profile'));
			}
		}
	}
	function purchase_subscription_numbers($id){
		$this->layout = 'popup';
		$this->loadModel('MonthlyNumberPackage');
		$this->set('monthlynumberpackage', $this->MonthlyNumberPackage->find('first' , array('conditions' => array('id' => $id))));
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$this->Session->read('User.id'))));
		$this->set('users',$users);
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
	
	
	function checkout_credit($id){
		$this->layout = 'popup';
		$this->loadModel('MonthlyPackage');
		$this->set('monthlydetail', $this->MonthlyPackage->find('first' , array('conditions' => array('id' => $id))));
		app::import('Model','Config');
	    $this->Config = new Config();
		$configdata=$this->Config->find('first');
		$this->set('config',$configdata);
	}
	
	function paypal_credit($id){
		$this->layout = 'popup';
		$this->loadModel('MonthlyPackage');
		$this->set('monthlydetail', $this->MonthlyPackage->find('first' , array('conditions' => array('id' => $id))));
	}
	
	function account_credited(){
		$this->layout= 'admin_new_layout';
	}
	
	function listNumbers(){
		$response = $this->Twilio->listNumbers();	
		$AvailablePhoneNumbers = $response->ResponseXml->AvailablePhoneNumbers->AvailablePhoneNumber;

		if(empty($AvailablePhoneNumbers)) {
			$this->Session->setFlash(__('We did not find any phone numbers by that search', true));
		}
		$this->set('AvailablePhoneNumbers', $AvailablePhoneNumbers);
	}
	
	function admin_index() {
		$this->User->recursive = 0;
		$conditions['AND'] = array();		
		if($this->data['Users']['phone']==1){
			$cond = array('User.username like' => $this->data['users']['name'].'%');      
			array_push($conditions['AND'], $cond);	
		}else if ($this->data['Users']['phone']==2) {
			$cond1 = array('User.first_name like' => $this->data['users']['name'].'%');
			array_push($conditions['AND'], $cond1);		
		}else if ($this->data['Users']['phone']==3) {
			$cond2 = array('User.last_name like' => $this->data['users']['name'].'%');
			array_push($conditions['AND'], $cond2);	
		}else if ($this->data['Users']['phone']==4) {
			$cond3 = array('User.email like' => $this->data['users']['name'].'%');
			array_push($conditions['AND'], $cond3);
		}else if ($this->data['Users']['phone']==5) {
			$cond4 = array('User.assigned_number like' => $this->data['users']['name'].'%');
			array_push($conditions['AND'], $cond4);	
		}else if ($this->data['Users']['phone']==7) {
			$cond7 = array('User.IP_address like' => $this->data['users']['name'].'%');
			array_push($conditions['AND'], $cond7);	
		}
		if($this->data['Users']['api_type']!=""){
			//$cond5 = array('User.api_type like' => $this->data['Users']['api_type']);
			$cond5 = array('User.api_type =' => API_TYPE);
			array_push($conditions['AND'], $cond5);
		}else{
			//$default = array('User.api_type like' => $this->data['Users']['api_type'].'%');
			$default = array('User.api_type =' => API_TYPE);
			array_push($conditions['AND'], $default);
		}

		$this->paginate = array('conditions' =>$conditions);
		$data = $this->paginate('User');
		$this->set('users', $data);
	}
	
	function admin_add() {
		$this->set('title_for_layout', 'Add User');
		if (!empty($this->data)) {

                    $accounts = $this->User->find('count',array('conditions' => array('User.active'=>1)));

                    if ($accounts >= NUMACCOUNTS){

                        /*$sitename=str_replace(' ','',SITENAME);
			$subject=SITENAME." :: Account Limit Reached";	
			$this->Email->to = SUPPORT_EMAIL;	
			$this->Email->subject = $subject;
			$this->Email->from = $sitename;
			$this->Email->template = 'account_limit_notice';
			$this->Email->sendAs = 'html';
			$this->Email->Controller->set('username', $this->data['User']['username']);
			$this->Email->Controller->set('email', $this->data['User']['email']);
                        $this->Email->Controller->set('firstname', $this->data['User']['first_name']);
                        $this->Email->Controller->set('lastname', $this->data['User']['last_name']);
                        $this->Email->Controller->set('phone', $this->data['User']['phone']);
			$this->Email->send();*/

                        $this->Session->setFlash('System has reached maximum number of active accounts. Please contact sales@ultrasmsscript.com to upgrade your level to increase capacity.');
                        $this->redirect(array('controller' =>'users', 'action'=>'admin_add'));
                    }

		$activate_user = $this->User->find('first', array('conditions' => array('User.id' =>$this->data['User']['email'])));
		if(empty($activate_user)){
				$this->data['User']['register'] = 1;
				$this->data['User']['password'] = md5($this->data['User']['passwrd']);
                                if (API_TYPE !=2 ) {				
                                   $this->data['User']['assigned_number'] = 0;
                                }
				$this->data['User']['sms_balance'] =FREE_SMS;
				$this->data['User']['voice_balance'] = FREE_VOICE;
				$this->data['User']['api_type'] = API_TYPE;

				if ($this->User->save($this->data)) {
					$this->Session->setFlash(__('The user has been saved', true));
					$this->redirect(array('action' => 'index'));
				}else{
					$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
				}
		}else{
			$this->Session->setFlash(__('The User already exists. Please, try again.', true));
		}
		
		}
		
	}	
	
	function admin_edit($id = null,$password=null) {
		$this->set('title_for_layout', 'Edit User');
          
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'index'));
		}

         
     	        if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				if($this->data['User']['active']==1){
					app::import('Model','User');
					$this->User = new User();
					$activate_user = $this->User->find('first', array('conditions' => array('User.id' =>$this->data['User']['id'])));
					if ($activate_user['User']['active']==0){
						$this->user_activate_account($activate_user['User']['account_activated'],1);
					}
			 
					app::import('Model','Referral');	
					$this->Referral = new Referral();
					$Referraldetails = $this->Referral->find('first',array('conditions' => array('Referral.user_id'=>$this->data['User']['id'])));
					if($Referraldetails['Referral']['account_activated']==0){
						$referral['id']=$Referraldetails['Referral']['id'];
						$referral['account_activated']=1;	
						$this->Referral->save($referral);
					}
				}else if($this->data['User']['active']==0){
					app::import('Model','Referral');	
					$this->Referral = new Referral();
					$Referraldetails = $this->Referral->find('first',array('conditions' => array('Referral.user_id'=>$this->data['User']['id'])));
					if($Referraldetails['Referral']['account_activated']==1){
						$referral['id']=$Referraldetails['Referral']['id'];
						$referral['account_activated']=0;	
						$this->Referral->save($referral);
					}
			 
				}
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		    }
                
		if (empty($this->data)) {
                    $password = base64_decode($password);
                    app::import('Model','User');
         	    $this->User = new User();
		    $someone = $this->User->find('first', array('conditions' => array('User.id' =>$id,'User.password' =>$password)));
		    if(!empty($someone)){
			$this->data = $this->User->read(null, $id);
		    }else{
                        $this->Session->setFlash(__('The user could not be found. Please, try again.', true));
                        $this->redirect(array('action' => 'index'));
                    }
                }
	}
	
	function admin_delete($id = null,$password=null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
                $id = base64_decode($id);
                $password = base64_decode($password);
                app::import('Model','User');
         	$this->User = new User();
		$someone = $this->User->find('first', array('conditions' => array('User.id' =>$id,'User.password' =>$password)));
		if(!empty($someone)){

		   if ($this->User->delete($id)) {
			app::import('Model','Referral');	
			$this->Referral = new Referral();
			$Referraldetails = $this->Referral->find('first',array('conditions' => array('Referral.user_id'=>$id)));
			if(!empty($Referraldetails)){
				$referral['id']=$Referraldetails['Referral']['id'];
				$referral['account_activated']=0;	
				$this->Referral->save($referral);
			}
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		   }
		   $this->Session->setFlash(__('User was not deleted', true));
		   $this->redirect(array('action' => 'index'));
               }
	}
	
	function about(){
		$this->set('title_for_layout', 'About Us');
	}
	
	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}
	function admin_user_messages(){	
	    $user = $this->User->find('all');	
		$this->set('users',$user);
		if($_REQUEST['all']){	
			$this->Session->write('user_session_id','');
			$this->Session->write('date','');
			$conditions=array();	   
			$conditions = $this->array_push_assoc($conditions, 'msg_type', "text");		
			$this->Session->write('session_cond', $conditions);
	    }	
		$userid=$this->Session->read('user_session_id');
			  
		$date=$this->Session->read('date');
		if($date == $this->data['User']['date'] && $id == $this->data['User']['id']){	
			if(!empty($userid)){
				$this->data['User']['id'] = $userid;
			}
			if(!empty($date)){
				$this->data['User']['date'] = $date;
            }
			
		}
			
		if(isset($this->data['User']['date']) && !empty($this->data['User']['date'])){
				$call_date = explode('/', $this->data['User']['date']);
				if(count($call_date) == 3){
					$date_call=$call_date[2]."-".$call_date[0]."-".$call_date[1];
				}
		}else{
			$date_call=date("Y-m-d");
		}
					
		if(($this->data['User']['date']!='') && ($this->data['User']['id']==0)){
			$conditions=array();
			$this->Session->write('date', $this->data['User']['date']);
			$this->Session->write('user_session_id','');
			$conditions = $this->array_push_assoc($conditions, 'DATE(Log.created)', "$date_call");
			$conditions = $this->array_push_assoc($conditions, 'msg_type', "text");	
			$this->Session->write('session_cond', $conditions);
		}else if($this->data['User']['id']!=0 && $this->data['User']['date']==''){
			$user_id = $this->data['User']['id'];
			$this->Session->write('user_session_id', $user_id);
			$this->Session->write('date', '');
			$conditions=array();
			$month=date('m');
			//contacts.created like '%$month%'"
			$conditions = $this->array_push_assoc($conditions, 'Log.user_id', "$user_id");
			$conditions = $this->array_push_assoc($conditions, 'MONTH(Log.created)', "$month");
			$conditions = $this->array_push_assoc($conditions, 'msg_type', "text");
			$this->Session->write('session_cond', $conditions);
		}else if(($this->data['User']['id']!=0) && ($this->data['User']['date']!='')){
			$user_id = $this->data['User']['id'];
			$this->Session->write('user_session_id', $user_id);
			$this->Session->write('date', $this->data['User']['date']);
			$conditions=array();
			$conditions = $this->array_push_assoc($conditions, 'Log.user_id', "$user_id");
			$conditions = $this->array_push_assoc($conditions, 'DATE(Log.created)', "$date_call");
			$conditions = $this->array_push_assoc($conditions, 'msg_type', "text");
			$this->Session->write('session_cond', $conditions);
		}	
		$sconditions=$this->Session->read('session_cond');
		app::import('Model','Log');
		$this->Log = new Log();
$this->Log->recursive = -1;
		$this->paginate = array('conditions'=>$sconditions,'order' =>array('Log.created' => 'desc'));
		$Messege=$this->paginate('Log');
		$Messege15=$this->Log->find('all',array('conditions'=> array('AND' => array($sconditions)),'order'=>'Log.created'));
		$total=$this->Log->find('count',array('conditions'=> array('AND' => array($sconditions)),'order'=>'Log.created'));							
		$this->Session->write('total', $total);
			
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
		for($i=0;$i<31;$i++){
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
	}
	
	function admin_top_users(){
	    $perpg=5;  
		if(isset($_REQUEST['_pn']) && $_REQUEST['_pn'] >0 && $_REQUEST['_pn']!=''){
			$pageNumber = $_REQUEST['_pn'];    
			$perpg="".($perpg*($pageNumber-1)).",".$perpg;  
		}else{
			$pageNumber = 1;  
		}
			
		if($_REQUEST['all']){
			$this->Session->write('todatetop', '');	
		    $this->Session->write('fromdatetop', '');
		}
		app::import('Model','Log');
		$this->Log = new Log();
		if(isset($this->data['date']['to']) && !empty($this->data['date']['from'])){
		
				$this->Session->write('todatetop', $this->data['date']['to']);
				$this->Session->write('fromdatetop', $this->data['date']['from']);
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
		foreach($usersCount as $userCount){
			$first_name=$userCount['p']['first_name'];
			$total_no[$first_name]=$userCount[0]['count'];
		}
		$i=0;
		foreach($total_no as $key=>$value){
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
		if($_REQUEST['all']){
			$this->Session->write('todatenon', '');	
		    $this->Session->write('fromdatenon', '');
		}
		app::import('Model','Log');
		$this->Log = new Log();
		
		if(isset($this->data['date']['to']) && !empty($this->data['date']['from'])){
			$this->Session->write('todatenon', $this->data['date']['to']);
			$this->Session->write('fromdatenon', $this->data['date']['from']);
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
			$this->set('non_users',$data);
		}
	
	}
	
	function reports(){
		$this->layout= 'admin_new_layout';
		$user=$this->Session->read('User');
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
		for($i=0;$i<31;$i++){
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
		$this->layout= 'admin_new_layout';
		$user_id = $this->Session->read('User.id');	
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
				
				$querynolimit = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created like '%".$date_call_start_actuall."%' order by contact_groups.created desc";
				
			}else{
				
				$query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc limit ".$perpg."";
				
				$query = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id  WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."'";
				
				$querynolimit= "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc";
				
			}	
			$subscribers=$this->Contact->query($query1);	
			$subscribersnolimit=$this->Contact->query($querynolimit);
			$this->Session->write('subscribers', $subscribersnolimit);
			$subscribers1=$this->Contact->query($query);
			$total = 	count($subscribers1);
			$this->Session->write('total', $total);
		}else{
				
			app::import('Model','Contact');
			$this->Contact = new Contact();
			$month=date('m');
				
			$query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc limit ".$perpg."";
					 
			$querynolimit = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id WHERE contact_groups.un_subscribers=0 and contact_groups.user_id = ".$user_id." and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc";
		
			$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month'";
				
			$subscribers=$this->Contact->query($query1);
			$subscribersnolimit=$this->Contact->query($querynolimit);
			$this->Session->write('subscribers', $subscribersnolimit);
			$subscribers1=$this->Contact->query($query);
			$total = 	count($subscribers1);
			$this->Session->write('total', $total);			
		}
			
	 
		foreach($subscribers1 as $m_list){   
            $day = date("d",strtotime($m_list['contact_groups']['created']));
			if(isset($month_list[$day])){
				$month_list[$day] = $month_list[$day] +1;                        
			}else{
				$month_list[$day] = 1;
			}		   
		}
				
		$mon_list = array();
		for($i=0;$i<31;$i++){
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
		$this->layout= 'admin_new_layout';
		$user_id = $this->Session->read('User.id');	
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
			
				$querynolimit = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created like '%".$date_call_start_actuall."%' order by contact_groups.created desc";
			
			}else{
				$query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc limit ".$perpg."";
			
				$query = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id  WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."'";
			
				$querynolimit = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id   WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc";
			}
			$subscribers=$this->Contact->query($query1);
			$unsubscribersnolimit=$this->Contact->query($querynolimit);
			$this->Session->write('unsubscribers', $unsubscribersnolimit);
			$subscribers1=$this->Contact->query($query);
			$total = 	count($subscribers1);
			$this->Session->write('total', $total);	
		}else{
			app::import('Model','Contact');
			$this->Contact = new Contact();
			$month=date('m');
			$query1 = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc limit ".$perpg."";
				 
			$querynolimit = "SELECT * FROM contact_groups left join contacts on contacts.id=contact_groups.contact_id left join groups on groups.id= contact_groups.group_id WHERE contact_groups.un_subscribers=1 and contact_groups.user_id = ".$user_id." and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc";
				
			$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=1 and MONTH(contact_groups.created) = '$month'";
			
			$subscribers=$this->Contact->query($query1);
			$unsubscribersnolimit=$this->Contact->query($querynolimit);
			$this->Session->write('unsubscribers', $unsubscribersnolimit);;
			$subscribers1=$this->Contact->query($query);
			$total = 	count($subscribers1);	
			$this->Session->write('total', $total);

		}
	
	 
		foreach($subscribers1 as $m_list){
		   $day = date("d",strtotime($m_list['contact_groups']['created']));
		   if(isset($month_list[$day])){
				$month_list[$day] = $month_list[$day] +1;                        
		   }else{
				$month_list[$day] = 1;
		   }		   
		}
				
		$mon_list = array();
		for($i=0;$i<31;$i++){
			$j=$i+1;
			if(strlen($j)==1)
				$j='0'.$j;
			if(isset($month_list[$j]) && $month_list[$j]!=''){
				$mon_list[$i]=$month_list[$j];
			}else{	
				$mon_list[$i]=0;
			}
		}
		$caller_list=json_encode($mon_list);			
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
		$this->layout= 'admin_new_layout';
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
		
				$keywordnolimit = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and  contact_groups.created like '%".$date_call_start_actuall."%' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc";
					
				$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.created like '%".$date_call_start_actuall."%' and contact_groups.un_subscribers=0 and groups.id= ".$this->data['Group']['id']."";
					 
			}else{
				$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc limit ".$perpg."";
						
				 $keywordnolimit = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and  contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc";
					
				$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE contact_groups.user_id = ".$user_id." and contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' and contact_groups.un_subscribers=0 and groups.id= ".$this->data['Group']['id']."";
				 
			}	
			$subscribers=$this->Contact->query($query1);	
			$keywords=$this->Contact->query($keywordnolimit);	
			$this->Session->write('keyword', $keywords);
			$subscribers1=$this->Contact->query($query);	
			$total = 	count($subscribers1);	
			$this->Session->write('total', $total);
        }else if($date_call_start_actuall!=''){
				app::import('Model','Contact');
				$this->Contact = new Contact();
				$month=date('m');
				
				if($date_call_start_actuall == $date_call_end_actuall){
					$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and  contact_groups.un_subscribers=0 and contact_groups.created like '%".$date_call_start_actuall."%' order by contact_groups.created desc limit ".$perpg."";
				
					$keywordnolimit = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and  contact_groups.un_subscribers=0 and contact_groups.created like '%".$date_call_start_actuall."%' order by contact_groups.created desc";
				
					$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and contact_groups.created like '%".$date_call_start_actuall."%'";
				
				}else{
				
					$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and  contact_groups.un_subscribers=0 and contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc limit ".$perpg."";
				
					$keywordnolimit = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and  contact_groups.un_subscribers=0 and contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."' order by contact_groups.created desc";
				
					$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and contact_groups.created between '".$date_call_start_actuall."' and '".$date_call_end."'";
				}
				
				$subscribers=$this->Contact->query($query1);
			    $keywords=$this->Contact->query($keywordnolimit);
				$this->Session->write('keyword', $keywords);
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
				   
				    $keywordnolimit = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']."  and  contact_groups.group_subscribers= '".$this->data['Group']['groupsubscribers']."' order by contact_groups.created desc";
				
					$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']." and contact_groups.group_subscribers='".$this->data['Group']['groupsubscribers']."'";
				
				}else{
				
					$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc limit ".$perpg."";
				
					$keywordnolimit = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']." order by contact_groups.created desc";
				
					$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id   WHERE  contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' and  groups.id= ".$this->data['Group']['id']."";
				}
				$subscribers=$this->Contact->query($query1);
			    $keywords=$this->Contact->query($keywordnolimit);
				$this->Session->write('keyword', $keywords);
				$subscribers1=$this->Contact->query($query);
				$total = 	count($subscribers1);
				$this->Session->write('total', $total);
				
		} else{
			$this->Session->write('Groupid', 0);
			$this->Session->write('groupsubscribers', '');
			app::import('Model','Contact');
			$this->Contact = new Contact();
			$month=date('m');

			$query1 = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc limit ".$perpg."";
				  
			$keywordnolimit = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month' order by contact_groups.created desc";

			$query = "SELECT * FROM contact_groups left join groups on groups.id=contact_groups.group_id left join contacts on contacts.id= contact_groups.contact_id  WHERE contact_groups.user_id = ".$user_id." and contact_groups.un_subscribers=0 and MONTH(contact_groups.created) = '$month'";
				
			$subscribers=$this->Contact->query($query1);
			$keywords=$this->Contact->query($keywordnolimit);
			$this->Session->write('keyword', $keywords);
			$subscribers1=$this->Contact->query($query);	
			$total = 	count($subscribers1);
			$this->Session->write('total', $total);		
		}
			
		foreach($subscribers1 as $m_list){
		   $day = date("d",strtotime($m_list['contact_groups']['created']));
		   if(isset($month_list[$day])){
				$month_list[$day] = $month_list[$day] +1;                        
		   }else{
				$month_list[$day] = 1;
		   }
					   
		}
				
		$mon_list = array();
		for($i=0;$i<31;$i++){
			$j=$i+1;
			if(strlen($j)==1)
				$j='0'.$j;
			if(isset($month_list[$j]) && $month_list[$j]!=''){
				$mon_list[$i]=$month_list[$j];
			}else{
				$mon_list[$i]=0;
			}
			
		}
		$caller_list=json_encode($mon_list);
		$this->set('caller_list', $caller_list);
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
		echo 'Please select A keyword';
		echo '<br/>';
		echo '<select id="groupsubscribers" class="form-control" name="data[Group][groupsubscribers]"> ';
		echo "<option value=''>All</option>";
		foreach($Subscriber1 as $Subscriber){
			echo '<option value="'.$Subscriber['ContactGroup']['group_subscribers'].'">'.$Subscriber['ContactGroup']['group_subscribers'].'</option>';
		}
		echo '</select>';
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
		$this->layout='admin_new_layout';
		$user_id = $this->Session->read('User.id');	
		if(!empty($this->data)){
			app::import('Model','User');
			$this->User = new User();
			$this->data['User']['id']=$user_id;
			$this->User->save($this->data);
			$this->Session->setFlash('Settings have been saved');
			$this->redirect(array('controller' =>'users', 'action'=>'setting'));
		}else{
			$user = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
			$this->set('users',$user);
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$UserNumber = $this->UserNumber->find('all',array('conditions'=>array('UserNumber.user_id'=>$user_id,'UserNumber.voice'=>1),'fields'=>'UserNumber.number','order' =>array('UserNumber.number' => 'asc')));
			$this->set('UserNumber',$UserNumber);
		}
	}
	function antispampolicy(){
	}
	
	function shortlinkadd(){
		$this->layout='admin_new_layout';
		if(!empty($this->data)){
			$user_id = $this->Session->read('User.id');
			app::import('Model','Shortlink');   
			$this->Shortlink = new Shortlink();
			
			$urldata = 'http://api.bitly.com/v3/shorten';
			$fields = array('login' =>BITLY_USERNAME,
			'apiKey' => BITLY_API_KEY,
			'longUrl' => $this->data['Shortlink']['url'],
			);
			$ch = curl_init($urldata);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			$result = curl_exec($ch);
			$jsonresponse = json_decode($result);
			$status_code = $jsonresponse->status_code;
			$short_url = $jsonresponse->data->url;
			if($status_code==200){
				$this->data['Shortlink']['id']='';
				$this->data['Shortlink']['user_id']=$user_id;
				$this->data['Shortlink']['shortname']=$this->data['Shortlink']['name'];
				$this->data['Shortlink']['url']=$this->data['Shortlink']['url'];
				$this->data['Shortlink']['clicks']=0;
				$this->data['Shortlink']['short_url']=$short_url;
				$this->data['Shortlink']['created']=date('y-m-d H:i:s');
				app::import('Model','Shortlink');   
				$this->Shortlink = new Shortlink();
				$this->Shortlink->save($this->data);
				
				$this->Session->setFlash('Short Link has been saved');
				$this->redirect(array('controller' =>'users', 'action'=>'shortlinks'));
			}else{
				$this->Session->setFlash('Short Link not saved');
			}
		}
	}
	function shortlinks(){
		$this->layout='admin_new_layout';
		app::import('Model','Shortlink');
		$this->Shortlink = new Shortlink();
		$user_id=$this->Session->read('User.id');	
		$this->paginate = array('conditions' => array('Shortlink.user_id' =>$user_id),'order' =>array('Shortlink.id' => 'asc'));
		$data = $this->paginate('Shortlink');
		$this->set('shortlink', $data);
	}
	
	function qrcodes(){
		$this->layout= 'admin_new_layout';
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
		$this->layout= 'admin_new_layout';
		app::import('Model','Qrcod');
		$this->Qrcod = new Qrcod();
		$user_id=$this->Session->read('User.id');	
		$this->paginate = array('conditions' => array('Qrcod.user_id' =>$user_id),'order' =>array('Qrcod.id' => 'asc'));
		$data = $this->paginate('Qrcod');
		$this->set('qrdata', $data);
	}
	
	function qrcodeview($id=null){
		$this->layout= 'admin_new_layout';
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
	function shortlinkdelete($id=null){
		app::import('Model','Shortlink');
		$this->Shortlink = new Shortlink();
		if ($this->Shortlink->delete($id)) {
			$this->Session->setFlash(__('Short link deleted', true));
			$this->redirect(array('controller' =>'users', 'action'=>'shortlinks'));
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
		//$package_name = "package2";
		$package_name = $_POST['package_name'];
		$user_id = $_POST['user_id'];
        $recurring_email = $_POST['recurring_email'];
		app::import('Model','User');
        $this->User = new User();
	    $userresponse = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		app::import('Model','MonthlyPackage');
        $this->MonthlyPackage = new MonthlyPackage();
	    $monthlypackage = $this->MonthlyPackage->find('first',array('conditions' => array('MonthlyPackage.id'=>$userresponse['User']['package'])));
		$profilestartdate=PROFILESTARTDATE;
		$response = $this->Expresscheckout->CreateRecurringPayments($amount,$package_name,$profilestartdate);
		$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));
		if(!empty($response['PROFILEID'])){
			if($response['ACK']=='Success'){
				$this->User->id = $_POST['user_id'];
				$this->data['User']['active'] = 1;
				$this->data['User']['next_renewal_dates'] =$nextdate;
				$this->data['User']['recurring_paypal_email'] =$recurring_email;
				$this->data['User']['sms_balance'] =$userresponse['User']['sms_balance']+$monthlypackage['MonthlyPackage']['text_messages_credit'];
				$this->data['User']['voice_balance'] =$userresponse['User']['voice_balance']+$monthlypackage['MonthlyPackage']['voice_messages_credit'];
				$this->data['User']['sms_credit_balance_email_alerts']=0;	
				$this->data['User']['VM_credit_balance_email_alerts']=0;
				$this->User->save($this->data);
				app::import('Model','Referral');	
				$this->Referral = new Referral();
				$Referraldetails = $this->Referral->find('first',array('conditions' => array('Referral.user_id'=>$_POST['user_id'])));
				if(!empty($Referraldetails)){
					$referral['id']=$Referraldetails['Referral']['id'];
					$referral['account_activated']=1;	
					$this->Referral->save($referral);
				}
				app::import('Model','Invoice');		
				$this->Invoice=new Invoice();	
				$invoice['user_id']=$user_id;	
				$invoice['amount']=$monthlypackage['MonthlyPackage']['amount'];
				$invoice['txnid']=$response['PROFILEID'];
				$invoice['type']=0;	
				$invoice['created']=date("Y-m-d");	
				$this->Invoice->save($invoice);
				$this->Session->write('User.active',1);
				$this->Session->write('User.package',$userresponse['User']['package']);
				$this->Session->setFlash(__( $package_name. '  package is activated.', true));
			}
		}else{
			$this->Session->setFlash(__('Payment is not processed, Try again', true));
		}
		$this->redirect(array('controller' =>'users', 'action'=>'profile'));
	}
	function paypalpayment($user_id=null){
		$this->layout= 'admin_new_layout';	
		app::import('Model','User');
		$this->User = new User();
		$user_id=$this->Session->read('User.id');
		$userdetails = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		$country = $userdetails['User']['user_country'];

		app::import('Model','MonthlyPackage');
		$this->MonthlyPackage = new MonthlyPackage();
	        $monthlydetails = $this->MonthlyPackage->find('all',array('conditions' => array('MonthlyPackage.status'=>1,'MonthlyPackage.user_country'=>''.trim($country).''),'order' =>array('MonthlyPackage.amount' => 'asc')));
		$this->set('monthlydetails',$monthlydetails);
		app::import('Model','Package');
		$this->Package = new Package();
		$Packagedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'text','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
		$this->set('Packagedetails',$Packagedetails);
		$Packagevoicedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'voice','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
	
		$this->set('Packagevoicedetails',$Packagevoicedetails);
		if (!empty($this->data)) {
			$packageAmount = $this->data['User']['amount'];
			$user_id = $this->data['User']['id'];
			$packageName = $this->data['User']['package_name'];
			app::import('Model','User');
            $this->User = new User();
			$userdetails_arr['id'] = $this->data['User']['id'];
			$userdetails_arr['package'] = $this->data['MonthlyPackage']['packageid'];
			$this->User->save($userdetails_arr);
			$response = $this->Expresscheckout->sendrequest($packageAmount,$user_id,$packageName);	
		}
	}
	function stripepayment($user_id=null){
		$this->layout= 'admin_new_layout';	
		app::import('Model','User');
		$this->User = new User();
		$user_id=$this->Session->read('User.id');
		$userdetails = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		$country = $userdetails['User']['user_country'];
		app::import('Model','MonthlyPackage');
		$this->MonthlyPackage = new MonthlyPackage();
	    $monthlydetails = $this->MonthlyPackage->find('all',array('conditions' => array('MonthlyPackage.status'=>1,'MonthlyPackage.user_country'=>''.trim($country).''),'order' =>array('MonthlyPackage.amount' => 'asc')));
		$this->set('monthlydetails',$monthlydetails);
		app::import('Model','Package');
		$this->Package = new Package();
		$Packagedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'text','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
		$this->set('Packagedetails',$Packagedetails);
		$Packagevoicedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'voice','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
		$this->set('Packagevoicedetails',$Packagevoicedetails);
	}
	function paypalnumbers($user_id=null){
		$this->layout= 'admin_new_layout';	
		app::import('Model','User');
		$this->User = new User();
		$user_id=$this->Session->read('User.id');
		$userdetails = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		$country = $userdetails['User']['user_country'];
		app::import('Model','MonthlyNumberPackage');
		$this->MonthlyNumberPackage = new MonthlyNumberPackage();
		$monthlydetails = $this->MonthlyNumberPackage->find('all',array('conditions' => array('MonthlyNumberPackage.status'=>1,'MonthlyNumberPackage.country'=>''.trim($country).''),'order' =>array('MonthlyNumberPackage.amount' => 'asc')));
		$this->set('monthlydetails',$monthlydetails);
	}
	function stripenumbers($user_id=null){
		$this->layout= 'admin_new_layout';	
		app::import('Model','User');
		$this->User = new User();
		$user_id=$this->Session->read('User.id');
		$userdetails = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		$country = $userdetails['User']['user_country'];
		app::import('Model','MonthlyNumberPackage');
		$this->MonthlyNumberPackage = new MonthlyNumberPackage();
		$monthlydetails = $this->MonthlyNumberPackage->find('all',array('conditions' => array('MonthlyNumberPackage.status'=>1,'MonthlyNumberPackage.country'=>''.trim($country).''),'order' =>array('MonthlyNumberPackage.amount' => 'asc')));
		$this->set('monthlydetails',$monthlydetails);
	}
	function review($user_id = null){
		$this->layout= 'admin_new_layout';
		$token = "";
		$this->set('user_id',$user_id);
		app::import('Model','User');
        $this->User = new User();
	    $userdetails = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
		app::import('Model','MonthlyPackage');
        $this->MonthlyPackage = new MonthlyPackage();
	    $monthlypackage = $this->MonthlyPackage->find('first',array('conditions' => array('MonthlyPackage.id'=>$userdetails['User']['package'])));
		$monthlypackage['MonthlyPackage']['package_name'];
		$packageName = $monthlypackage['MonthlyPackage']['package_name'];
		if (isset($_REQUEST['token'])){
			$token = $_REQUEST['token'];
		}
		if ( $token != "" ){
			$response = $this->Expresscheckout->GetShippingDetail($token,$packageName);
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
		$this->layout= 'admin_new_layout';	

                app::import('Model','User');
                $this->User = new User();
                $user_id=$this->Session->read('User.id');
	        $userdetails = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));
                $country = $userdetails['User']['user_country'];

	        app::import('Model','MonthlyPackage');
                $this->MonthlyPackage = new MonthlyPackage();
	        $monthlydetails = $this->MonthlyPackage->find('all',array('conditions' => array('MonthlyPackage.status'=>1,'MonthlyPackage.user_country'=>''.trim($country).''),'order' =>array('MonthlyPackage.amount' => 'asc')));
	        $this->set('monthlydetails',$monthlydetails);

	        app::import('Model','Config');
	        $this->Config = new Config();
		$config=$this->Config->find('first');
		$this->set('config', $config);
		
                app::import('Model','Package');
		$this->Package = new Package();
		$Packagedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'text','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
		$Packagevoicedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'voice','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
		$this->set('Packagedetails',$Packagedetails);
		$this->set('Packagevoicedetails',$Packagevoicedetails);
	}
 
	function invoices(){
		$this->layout ='popup';
		$user_id=$this->Session->read('User.id');
		app::import('Model','Invoice');
        $this->Invoice = new Invoice();
	    $invoicedetil = $this->Invoice->find('all',array('conditions' => array('Invoice.user_id'=>$user_id),'order' =>array('Invoice.id' => 'desc'),'limit' =>5));
		$this->set('invoicedetils',$invoicedetil);
	
	}
	function viewallreceipt(){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','Invoice');
		$this->Invoice = new Invoice();
		$this->paginate = array('conditions' => array('Invoice.user_id' =>$user_id),'order' =>array('Invoice.id' => 'desc'));
		$data = $this->paginate('Invoice');
		$this->set('invoicedetils', $data);
	}
	
	function subscriberexport(){
		$this->autoRender = false;
		ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
		$subscriber=$this->Session->read('subscribers');
		$filename = "subscriber".date("Y.m.d").".csv";


		$csv_file = fopen('php://output', 'w');
	 
		header('Content-type: application/csv');
		header('Content-Type: text/html');
		header('Content-Disposition: attachment; filename="'.$filename.'"'); 
		$header_row = array("Subscriber Name","Group Name", "Phone", "Source", "Subscribed Date");
		fputcsv($csv_file,$header_row,',','"');
		foreach($subscriber as $result){
			if($result['contact_groups']['subscribed_by_sms']==0){
				$type = 'Import';
			}else if($result['contact_groups']['subscribed_by_sms']==1){
				$type = 'SMS';
			}else{
				$type = 'Widget';
			}
			// Array indexes correspond to the field names in your db table(s)
			$row = array(
			$result['contacts']['name'],
			$result['groups']['group_name'],
			$result['contacts']['phone_number'],
			$type,
			$result['contact_groups']['created']
			);
			fputcsv($csv_file,$row,',','"');
		}
		fclose($csv_file);
	}
	
	function unsubscriberexport(){
		$this->autoRender = false;
		ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
		$unsubscriber=$this->Session->read('unsubscribers');
		$filename = "Un-subscriber".date("Y.m.d").".csv";
		$csv_file = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Type: text/html');
		header('Content-Disposition: attachment; filename="'.$filename.'"'); 
		$header_row = array("Subscriber Name","Group Name", "Phone", "Type", "Subscribed Date");
		fputcsv($csv_file,$header_row,',','"');
		foreach($unsubscriber as $result){
			if($result['contact_groups']['subscribed_by_sms']==0){
			 $type = 'IMP';
			}else{
			 $type = 'SMS';
			}
			// Array indexes correspond to the field names in your db table(s)
			$row = array(
			$result['contacts']['name'],
			$result['groups']['group_name'],
			$result['contacts']['phone_number'],
			$type,
			$result['contact_groups']['created']
			);
			fputcsv($csv_file,$row,',','"');
		}
		fclose($csv_file);
	}
	
	
	function keywordexport(){
		$this->autoRender = false;
		ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
		$keyword=$this->Session->read('keyword');
		$filename = "KeywordReport".date("Y.m.d").".csv";
		$csv_file = fopen('php://output', 'w'); 
		header('Content-type: application/csv');
		header('Content-Type: text/html');
		header('Content-Disposition: attachment; filename="'.$filename.'"'); 
		$header_row = array("Subscriber Name","Group Name", "Keyword","Phone", "Subscribed Date");
		fputcsv($csv_file,$header_row,',','"');
		foreach($keyword as $result){
			// Array indexes correspond to the field names in your db table(s)
			$row = array(
			$result['contacts']['name'],
			$result['groups']['group_name'],
			$result['groups']['keyword'],
			$result['contacts']['phone_number'],
			$result['contact_groups']['created']
			);
			fputcsv($csv_file,$row,',','"');
		}
		fclose($csv_file);
	
	}
	
	function admin_number_release($id=null){
		$this->autoRender = false;
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		$user_details = $this->UserNumber->find('first',array('conditions' => array('UserNumber.id'=>$id)));
		if(!empty($user_details)){
			if(API_TYPE==0){
				if($user_details['UserNumber']['phone_sid']!=''){
					$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
					$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
					$this->Twilio->releasenumber($user_details['UserNumber']['phone_sid']);
				}
			}else if(API_TYPE==3){
				$this->Plivo->AuthId =PLIVO_KEY;
				$this->Plivo->AuthToken =PLIVO_TOKEN;
				$this->Plivo->delete_phone_number($user_details['UserNumber']['number']);
			}else{
				$api_key= NEXMO_KEY;
				$api_secret= NEXMO_SECRET;
				$this->Nexmo->releasenumber($user_details['UserNumber']['country_code'],$user_details['UserNumber']['number'],$api_key,$api_secret);
			}
			if(!empty($user_details)){
				$this->data['User']['id']=$user_details['User']['id'];
				$this->data['User']['number_limit_count']=$user_details['User']['number_limit_count']-1;
				$this->User->save($this->data);
			}
			$this->UserNumber->delete($id);
			$this->Session->setFlash(__('User number deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User number not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_number_release_user($id=null){
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		$user_details = $this->User->find('first',array('conditions' => array('User.id'=>$id)));
		if(!empty($user_details)){
			if(API_TYPE==0){
				if($user_details['User']['phone_sid']!=''){
					$this->Twilio->AccountSid = TWILIO_ACCOUNTSID;
					$this->Twilio->AuthToken = TWILIO_AUTH_TOKEN;
					$this->Twilio->releasenumber($user_details['User']['phone_sid']);
				}
			}else if(API_TYPE==3){
				$this->Plivo->AuthId =PLIVO_KEY;
				$this->Plivo->AuthToken =PLIVO_TOKEN;
				$this->Plivo->delete_phone_number($user_details['User']['assigned_number']);
			}else{
				$api_key= NEXMO_KEY;
				$api_secret= NEXMO_SECRET;
				$this->Nexmo->releasenumber($user_details['User']['country_code'],$user_details['User']['assigned_number'],$api_key,$api_secret);
			}
			$this->data['User']['id']=$id;
			$this->data['User']['assigned_number']=0;
			$this->data['User']['number_limit_count']=$user_details['User']['number_limit_count']-1;
			$this->data['User']['api_type']=API_TYPE;
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber();
			$user_numbers = $this->UserNumber->find('first',array('conditions' => array('UserNumber.user_id'=>$id)));
			if(!empty($user_numbers)){
				$this->data['User']['phone_sid']=$user_numbers['UserNumber']['phone_sid'];
				$this->data['User']['assigned_number']=$user_numbers['UserNumber']['number'];
				$this->data['User']['country_code']=$user_numbers['UserNumber']['country_code'];
				$this->data['User']['sms']=$user_numbers['UserNumber']['sms'];
				$this->data['User']['mms']=$user_numbers['UserNumber']['mms'];
				$this->data['User']['voice']=$user_numbers['UserNumber']['voice'];
				$this->UserNumber->delete($user_numbers['UserNumber']['id']);
			}

			$this->User->save($this->data);
			$this->Session->setFlash(__('User number deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	
		$this->Session->setFlash(__('User number not deleted', true));
		$this->redirect(array('action' => 'index'));
	}


        function admin_contactdelete($id = null) {
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
			foreach($contacts_members as $contacts_member){
				$group_id=$contacts_member['ContactGroup']['group_id'];
				$un_subscribers=$contacts_member['ContactGroup']['un_subscribers'];
				if($un_subscribers==1){
					app::import('Model','Group');
					$this->Group = new Group();
					$Group =$this->Group->find('first', array('conditions' => array('Group.id'=>$group_id)));
					$this->data['Group']['id'] = $group_id;
					$this->data['Group']['totalsubscriber'] = $Group['Group']['totalsubscriber'];	
					$this->Group->save($this->data);
				}else{
				
					app::import('Model','Group');
					$this->Group = new Group();
					$Group =$this->Group->find('first', array('conditions' => array('Group.id'=>$group_id)));
					$this->data['Group']['id'] = $group_id;
					$this->data['Group']['totalsubscriber'] = $Group['Group']['totalsubscriber']-1;
					$this->Group->save($this->data);	
					}
			}			$this->ContactGroup->deleteAll(array('ContactGroup.contact_id' => $id));
			$this->Session->setFlash(__('Contact deleted', true));
			
			$this->redirect(array('action'=>'index'));
			
		}
		$this->Session->setFlash(__('Contact was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}	
	/*****************************************************************nexmo number list **********************************************************/
	
	function numberlist_nexmo(){
		$this->layout ='popup';
		$user_id=$this->Session->read('User.id');
		app::import('Model','UserNumber');
        $this->UserNumber = new UserNumber();
	    $nexmodetail = $this->UserNumber->find('all',array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.api_type'=>1),'order' =>array('UserNumber.id' => 'desc'),'limit' =>5));			
		$this->set('nexmodetails',$nexmodetail);
	}
	
	function viewallnumber_nexmo(){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','UserNumber');
        $this->UserNumber = new UserNumber();		
		$this->paginate = array('conditions' => array('UserNumber.user_id' =>$user_id,'UserNumber.api_type'=>1),'order' =>array('UserNumber.id' => 'desc'));
		$nexmo_data = $this->paginate('UserNumber');
		$this->set('nexmoall_data',$nexmo_data);
	}
	function numberlist_plivo(){
		$this->layout ='popup';
		$user_id=$this->Session->read('User.id');
		app::import('Model','UserNumber');
        $this->UserNumber = new UserNumber();
	    $plivodetail = $this->UserNumber->find('all',array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.api_type'=>3),'order' =>array('UserNumber.id' => 'desc'),'limit' =>5));			
		$this->set('plivodetails',$plivodetail);
	}
	
	function viewallnumber_plivo(){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','UserNumber');
        $this->UserNumber = new UserNumber();		
		$this->paginate = array('conditions' => array('UserNumber.user_id' =>$user_id,'UserNumber.api_type'=>3),'order' =>array('UserNumber.id' => 'desc'));
		$plivo_data = $this->paginate('UserNumber');
		$this->set('plivoall_data',$plivo_data);
	}
	/*****************************************************************twillio number list **********************************************************/
	function numberlist_twillio(){
		$this->layout ='popup';
		$user_id=$this->Session->read('User.id');
		app::import('Model','UserNumber');
        $this->UserNumber = new UserNumber();
	    $twilliodetail = $this->UserNumber->find('all',array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.api_type'=>0),'order' =>array('UserNumber.id' => 'desc'),'limit' =>5));		
		$this->set('twilliodetails',$twilliodetail);
	}
	
	function viewallnumber_twillio(){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','UserNumber');
        $this->UserNumber = new UserNumber();		
		$this->paginate = array('conditions' => array('UserNumber.user_id' =>$user_id,'UserNumber.api_type'=>0),'order' =>array('UserNumber.id' => 'desc'));
		$twilliall_data = $this->paginate('UserNumber');
		$this->set('twilli_data', $twilliall_data);
	}
	
	function admin_allnumbers($user_id=null,$password=null){
		$this->layout ='popup';
                $user_id = base64_decode($user_id);
                $password = base64_decode($password);
                app::import('Model','User');
         	$this->User = new User();
		$someone = $this->User->find('first', array('conditions' => array('User.id' =>$user_id,'User.password' =>$password)));
		if(!empty($someone)){
		   app::import('Model','UserNumber');
		   $this->UserNumber = new UserNumber();
		   app::import('Model','User');	 
		   $this->User = new User();	
		   $numbers = $this->UserNumber->find('all',array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.api_type'=>API_TYPE),'order' =>array('UserNumber.id' => 'desc')));
		   $usernumbers = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));	  
		   $this->set('numbers', $numbers);
		   $this->set('usernumbers', $usernumbers);
                }else{
                   $this->Session->setFlash(__('Numbers for this user could not be found. Please, try again.', true));
                   $this->redirect(array('action' => 'index'));

                }
	}

        function admin_userpermissions($user_id=null,$submit=null,$password=null){
		$this->layout ='popup';
                
		
                if(!empty($this->data) || $submit==1){
		   app::import('Model','User');
		   $this->User = new User();
                   $user_id = base64_decode($user_id);
                   $this->data['User']['id']=$user_id;
           
                   if($this->data['User']['autoresponders']==''){
			$this->data['User']['autoresponders']=0;
		   }else{
			$this->data['User']['autoresponders']=$this->data['User']['autoresponders'];
		   }
                   if($this->data['User']['importcontacts']==''){
			$this->data['User']['importcontacts']=0;
		   }else{
			$this->data['User']['importcontacts']=$this->data['User']['importcontacts'];
		   }
                   if($this->data['User']['shortlinks']==''){
			$this->data['User']['shortlinks']=0;
		   }else{
			$this->data['User']['shortlinks']=$this->data['User']['shortlinks'];
		   }
                   if($this->data['User']['voicebroadcast']==''){
			$this->data['User']['voicebroadcast']=0;
		   }else{
			$this->data['User']['voicebroadcast']=$this->data['User']['voicebroadcast'];
		   }
                   if($this->data['User']['polls']==''){
			$this->data['User']['polls']=0;
		   }else{
			$this->data['User']['polls']=$this->data['User']['polls'];
		   }
                   if($this->data['User']['contests']==''){
			$this->data['User']['contests']=0;
		   }else{
			$this->data['User']['contests']=$this->data['User']['contests'];
		   }
                   if($this->data['User']['loyaltyprograms']==''){
			$this->data['User']['loyaltyprograms']=0;
		   }else{
			$this->data['User']['loyaltyprograms']=$this->data['User']['loyaltyprograms'];
		   }
                   if($this->data['User']['kioskbuilder']==''){
			$this->data['User']['kioskbuilder']=0;
		   }else{
			$this->data['User']['kioskbuilder']=$this->data['User']['kioskbuilder'];
		   }
                   if($this->data['User']['birthdaywishes']==''){
			$this->data['User']['birthdaywishes']=0;
		   }else{
			$this->data['User']['birthdaywishes']=$this->data['User']['birthdaywishes'];
		   }
                   if($this->data['User']['mobilepagebuilder']==''){
			$this->data['User']['mobilepagebuilder']=0;
		   }else{
			$this->data['User']['mobilepagebuilder']=$this->data['User']['mobilepagebuilder'];
		   }
                   if($this->data['User']['webwidgets']==''){
			$this->data['User']['webwidgets']=0;
		   }else{
			$this->data['User']['webwidgets']=$this->data['User']['webwidgets'];
		   }

		   $this->User->save($this->data);
		   $this->Session->setFlash('User permissions have been saved');
                   $this->redirect(array('action'=>'index'));
		}else{
                   app::import('Model','User');	 
		   $this->User = new User();	
                   $this->set('id', $user_id);
                   $user_id = base64_decode($user_id);
                   $password = base64_decode($password);
		   $user = $this->User->find('first',array('conditions' => array('User.id'=>$user_id,'User.password' =>$password)));	
                   if(!empty($user)){  
		      $this->set('userpermissions', $user);
                   }else{
                      $this->Session->setFlash(__('User permissions for this user could not be found. Please, try again.', true));
                      $this->redirect(array('action' => 'index'));
                   }
                   

                }
	}

        function admin_usercontacts($user_id=null,$password=null){
		$this->layout ='popup';
                $user_id = base64_decode($user_id);
                $password = base64_decode($password);
                app::import('Model','User');
         	$this->User = new User();
		$someone = $this->User->find('first', array('conditions' => array('User.id' =>$user_id,'User.password' =>$password)));
		if(!empty($someone)){

		   app::import('Model','ContactGroup');
		   $this->ContactGroup = new ContactGroup();
	           $this->paginate = array('conditions' => array('ContactGroup.user_id'=>$user_id),'order' =>array('ContactGroup.created' => 'desc'));			 
                   $data = $this->paginate('ContactGroup');
		   $this->set('contacts', $data);
                   $user = $this->User->find('first',array('conditions' => array('User.id'=>$user_id)));	  
		   $this->set('users', $user);
                }else{
                   $this->Session->setFlash(__('Contacts for this user could not be found. Please, try again.', true));
                   $this->redirect(array('action' => 'index'));
                }
	}
	
	function admin_resend_email($id=null){
	    $this->autoRender = false;
		if($id > 0){
			function random_generator($digits){
				srand ((double) microtime() * 10000000);
				//Array of alphabets
				$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q",
				"R","S","T","U","V","W","X","Y","Z");
				$random_generator="";// Initialize the string to store random numbers
				for($i=1;$i<$digits+1;$i++){ // Loop the number of times of required digits
					if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
						// Add one random alphabet
						$rand_index = array_rand($input);
						$random_generator .=$input[$rand_index]; // One char is added
					}else{
						// Add one numeric digit between 1 and 10
						$random_generator .=rand(1,10); // one number is added
					} // end of if else
				} // end of for loop
				return $random_generator;
			} // end of function
			$random_number = random_generator(4);
			$data=$this->User->find('first',array('conditions' => array('User.id'=>$id)));
			$email=$data['User']['email'];
			$username=$data['User']['username'];
			$this->data['User']['id'] = $id;
			$this->data['User']['account_activated'] = $random_number;
			if($this->User->save($this->data)){
				$subject=SITENAME." Activate Account";	
				$url= SITE_URL."/users/user_activate_account/".$random_number;
				#$this->Email->delivery = 'debug';	
				$this->Email->to =$email;	
				$this->Email->subject = $subject;
				$this->Email->from = $sitename;
				$this->Email->template = 'account_login_resend';
				$this->Email->sendAs = 'html';
				$this->Email->Controller->set('username', $username);
				//$this->Email->Controller->set('password', $this->data['User']['passwrd']);
				$this->Email->Controller->set('url', $url);
				$this->Email->Controller->set('email', $email);
				$this->Email->send();
				$this->Session->setFlash(__('Account activation email has been re-sent', true));
			}else{
				$this->Session->setFlash(__('Please try again', true));
			}
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function redeem($unique_key=null,$code=null){
		$this->layout = null;		
		if($unique_key !=''){
			app::import('Model','SmsloyaltyUser');
			$this->SmsloyaltyUser = new SmsloyaltyUser();
			$loyaltyuser=$this->SmsloyaltyUser->find('first',array('conditions'=>array('SmsloyaltyUser.unique_key'=>$unique_key)));
			$this->set('loyaltyuser',$loyaltyuser);
			if(!empty($loyaltyuser)){
				if($loyalty_arr['SmsloyaltyUser']['redemptions']==0){
					$loyalty_arr['SmsloyaltyUser']['id'] = $loyaltyuser['SmsloyaltyUser']['id'];
					$loyalty_arr['SmsloyaltyUser']['redemptions'] = 1;
					$this->SmsloyaltyUser->save($loyalty_arr);
					if($code !=''){
						app::import('Model','Kiosks');
						$this->Kiosks = new Kiosks();
						$kiosks = $this->Kiosks->find('first',array('conditions'=>array('Kiosks.unique_id'=>$code),'order' =>array('Kiosks.id' => 'asc')));
						$this->set('code',$code);
						$this->set('kiosks',$kiosks);
					}
				}
			}else{
				$this->set('notfound',1);
				$this->Session->setFlash(__('Redeem code not found. Please make sure you are not editing the redeem code.', true));
				//$this->redirect(array('action' => 'login'));
			}
		}else{
			$this->set('empty',1);
			$this->Session->setFlash(__('Redeem code is empty. Please make sure you are not editing the redeem code.', true));
			//$this->redirect(array('action' => 'login'));
			
		}
	}
	function cancel_monthly_subscription(){
		$this->autoRender=false;
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$this->Session->read('User.id'))));
		if(!empty($users)){
			\Stripe\Stripe::setApiKey(SECRET_KEY);
			try{
				$subscription = \Stripe\Subscription::retrieve($users['User']['monthly_stripe_subscription_id']);
				$subscription->cancel(array('at_period_end' => true));
				$this->Session->setFlash(__('Your monthly credit subscription will be canceled at the end of your current billing period and remain active until then.', true));
			} catch (\Stripe\Error\Card $e) {
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\RateLimit $e) {
			   // Too many requests made to the API too quickly
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\InvalidRequest $e) {
			   // Invalid parameters were supplied to Stripe's API
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Authentication $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\ApiConnection $e) {
				// Network communication with Stripe failed
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Base $e) {
				// Display a very generic error to the user, and maybe send
				// yourself an email
				$this->Session->setFlash($e->getMessage());
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}else{
			$this->Session->setFlash(__('User not found.please try again', true));
		}
		$this->redirect(array('controller' =>'users', 'action'=>'profile'));
	}
	function cancel_monthly_numbers_subscription(){
		$this->autoRender=false;
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$this->Session->read('User.id'))));
		if(!empty($users)){
			\Stripe\Stripe::setApiKey(SECRET_KEY);
			try{
				$subscription = \Stripe\Subscription::retrieve($users['User']['monthly_number_subscription_id']);
				$subscription->cancel(array('at_period_end' => true));
				$this->Session->setFlash(__('Your monthly numbers subscription will be canceled at the end of your current billing period and remain active until then.', true));
			} catch (\Stripe\Error\Card $e) {
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\RateLimit $e) {
			   // Too many requests made to the API too quickly
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\InvalidRequest $e) {
			   // Invalid parameters were supplied to Stripe's API
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Authentication $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\ApiConnection $e) {
				// Network communication with Stripe failed
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Base $e) {
				// Display a very generic error to the user, and maybe send
				// yourself an email
				$this->Session->setFlash($e->getMessage());
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}else{
			$this->Session->setFlash(__('User not found.please try again', true));
		}
		$this->redirect(array('controller' =>'users', 'action'=>'profile'));
	}
	function upgrade_monthly_numbers_subscription($package_id=null){
		$this->autoRender=false;
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$this->Session->read('User.id'))));
		if(!empty($users)){
			$this->loadModel('MonthlyNumberPackage');
			$monthlypackage = $this->MonthlyNumberPackage->find('first' , array('conditions' => array('MonthlyNumberPackage.id' => $package_id)));
			$oldmonthlypackage = $this->MonthlyNumberPackage->find('first' , array('conditions' => array('MonthlyNumberPackage.id' =>$users['User']['number_package'])));
			\Stripe\Stripe::setApiKey(SECRET_KEY);
			try{
				if ($package_id == $users['User']['number_package']){
					$this->Session->setFlash(__('You are currently subscribed to this plan. Please choose a different plan you want to upgrade/downgrade.', true));
				}else {
					$subscription = \Stripe\Subscription::retrieve($users['User']['monthly_number_subscription_id']);
					$subscription->plan = $monthlypackage['MonthlyNumberPackage']['plan'];
					$subscription->save();
					if(isset($subscription->id)){
						$user_arr['User']['id']=$this->Session->read('User.id');
						$user_arr['User']['monthly_number_subscription_id']=$subscription->id;
						$user_arr['User']['number_package']=$monthlypackage['MonthlyNumberPackage']['id'];
						$user_arr['User']['number_limit']=$users['User']['number_limit'] + $monthlypackage['MonthlyNumberPackage']['total_secondary_numbers'] - $oldmonthlypackage['MonthlyNumberPackage']['total_secondary_numbers'];
                                                
						$this->User->save($user_arr);
					}
					$this->Session->setFlash(__('Your monthly numbers plan was successfully updated. You will be charged a prorated amount on your next invoice.', true));
				}
			} catch (\Stripe\Error\Card $e) {
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\RateLimit $e) {
			   // Too many requests made to the API too quickly
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\InvalidRequest $e) {
			   // Invalid parameters were supplied to Stripe's API
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Authentication $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\ApiConnection $e) {
				// Network communication with Stripe failed
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Base $e) {
				// Display a very generic error to the user, and maybe send
				// yourself an email
				$this->Session->setFlash($e->getMessage());
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}else{
			$this->Session->setFlash(__('User not found.please try again', true));
		}
		$this->redirect(array('controller' =>'users', 'action'=>'profile'));
	}
	function upgrade_monthly_subscription($package_id=null){
		$this->autoRender=false;
		$users = $this->User->find('first',array('conditions'=>array('User.id'=>$this->Session->read('User.id'))));
		if(!empty($users)){
			$this->loadModel('MonthlyPackage');
			$monthlypackage = $this->MonthlyPackage->find('first' , array('conditions' => array('MonthlyPackage.id' => $package_id)));
			$oldmonthlypackage = $this->MonthlyPackage->find('first' , array('conditions' => array('MonthlyPackage.id' =>$users['User']['package'])));
			\Stripe\Stripe::setApiKey(SECRET_KEY);
			try{
				if ($package_id == $users['User']['package']){
					$this->Session->setFlash(__('You are currently subscribed to this plan. Please choose a different plan you want to upgrade/downgrade.', true));
				}else {
					$subscription = \Stripe\Subscription::retrieve($users['User']['monthly_stripe_subscription_id']);
					$subscription->plan = $monthlypackage['MonthlyPackage']['product_id'];
					$subscription->save();
					if(isset($subscription->id)){
						$user_arr['User']['id']=$this->Session->read('User.id');
						$user_arr['User']['monthly_stripe_subscription_id']=$subscription->id;
						$user_arr['User']['sms_balance']=$users['User']['sms_balance'] + $monthlypackage['MonthlyPackage']['text_messages_credit'] - $oldmonthlypackage['MonthlyPackage']['text_messages_credit'];
						$user_arr['User']['voice_balance']=$users['User']['voice_balance'] + $monthlypackage['MonthlyPackage']['voice_messages_credit'] - $oldmonthlypackage['MonthlyPackage']['voice_messages_credit'];
						$user_arr['User']['sms_credit_balance_email_alerts']=0;
						$user_arr['User']['VM_credit_balance_email_alerts']=0;
						$user_arr['User']['package']=$monthlypackage['MonthlyPackage']['id'];
						//$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
						//$user_arr['User']['next_renewal_dates'] =$nextdate;
						$this->User->save($user_arr);
					}
					$this->Session->setFlash(__('Your monthly plan was successfully updated. You will be charged a prorated amount on your next invoice.', true));
				}
			} catch (\Stripe\Error\Card $e) {
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\RateLimit $e) {
			   // Too many requests made to the API too quickly
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\InvalidRequest $e) {
			   // Invalid parameters were supplied to Stripe's API
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Authentication $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\ApiConnection $e) {
				// Network communication with Stripe failed
				$this->Session->setFlash($e->getMessage());
			} catch (\Stripe\Error\Base $e) {
				// Display a very generic error to the user, and maybe send
				// yourself an email
				$this->Session->setFlash($e->getMessage());
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}else{
			$this->Session->setFlash(__('User not found.please try again', true));
		}
		$this->redirect(array('controller' =>'users', 'action'=>'profile'));
	}
	function getnotification(){
		$this->autoRender=false;
        $out = @file_get_contents('php://input');
        $event_json = json_decode('['.$out.']');   
        $jsonObject = $event_json[0];
        echo $evt_id=$jsonObject->id;
        $type = $jsonObject->type;   
        $subscription_id=$jsonObject->data->object->id;
        $customer_id=$jsonObject->data->object->customer;
		if(($type=='customer.subscription.deleted') && ($customer_id !='')){
			$monthlysubscription = $this->User->find('first',array('conditions'=>array('User.stripe_customer_id'=>$customer_id,'User.monthly_stripe_subscription_id'=>$subscription_id)));
			$monthly_number_subscription = $this->User->find('first',array('conditions'=>array('User.stripe_customer_id'=>$customer_id,'User.monthly_number_subscription_id'=>$subscription_id)));
			if(!empty($monthlysubscription)){
				$this->loadModel('MonthlyPackage');
				$monthlypackage = $this->MonthlyPackage->find('first', array('conditions' => array('MonthlyPackage.id' =>$monthlysubscription['User']['package'])));
				$user_arr['User']['id'] = $monthlysubscription['User']['id'];
				$user_arr['User']['active'] = 0;
				$user_arr['User']['package'] = 0;
				$user_arr['User']['next_renewal_dates'] ='';
				$user_arr['User']['monthly_stripe_subscription_id'] ='';
				$user_arr['User']['sms_credit_balance_email_alerts']=0;	
				$user_arr['User']['VM_credit_balance_email_alerts']=0;
				if($this->User->save($user_arr)){
					$sitename=str_replace(' ','',SITENAME);		
					$subject="Your Monthly Credit Subscription with ".SITENAME." has been canceled";
					$this->Email->subject = $subject;
					$this->Email->from = $sitename;
					$this->Email->to = $monthlysubscription['User']['email'];
					$this->Email->template = 'stripe_subscription_cancel'; 
					$this->Email->sendAs = 'html'; 
					$this->set('data', $monthlysubscription);
					$this->Email->send();
				}
			}else if(!empty($monthly_number_subscription)){
				$this->loadModel('MonthlyNumberPackage');
				$monthlynumberpackage = $this->MonthlyNumberPackage->find('first', array('conditions' => array('MonthlyNumberPackage.id' =>$monthly_number_subscription['User']['number_package'])));
				$user_arr['User']['id'] = $monthly_number_subscription['User']['id'];
				$user_arr['User']['number_limit'] = $monthly_number_subscription['User']['number_limit'] - $monthlynumberpackage['MonthlyNumberPackage']['total_secondary_numbers'];
				$user_arr['User']['number_package'] = 0;
				$user_arr['User']['number_next_renewal_dates'] ='';
				$user_arr['User']['monthly_number_subscription_id'] ='';
				$user_arr['User']['number_limit_set'] = 0;
				$user_arr['User']['active'] = 0;
				if($this->User->save($user_arr)){
					$sitename=str_replace(' ','',SITENAME);		
					$subject="Your Monthly Numbers Subscription with ".SITENAME." has been canceled";
					$this->Email->subject = $subject;
					$this->Email->from = $sitename;
					$this->Email->to = $monthly_number_subscription['User']['email'];
					$this->Email->template = 'stripe_number_subscription_cancel'; 
					$this->Email->sendAs = 'html'; 
					$this->set('data', $monthly_number_subscription);
					$this->Email->send();
				}
			}
		}else if(($type=='invoice.payment_succeeded') && ($customer_id !='')){
                        $subscription_id=$jsonObject->data->object->lines->data[0]->id;
                        $amount = $jsonObject->data->object->lines->data[0]->plan->amount / 100;
			$monthlysubscription = $this->User->find('first',array('conditions'=>array('User.stripe_customer_id'=>$customer_id,'User.monthly_stripe_subscription_id'=>$subscription_id)));
			$monthly_number_subscription = $this->User->find('first',array('conditions'=>array('User.stripe_customer_id'=>$customer_id,'User.monthly_number_subscription_id'=>$subscription_id)));
			if(!empty($monthlysubscription)){
				$this->loadModel('MonthlyPackage');
				$monthlypackage = $this->MonthlyPackage->find('first', array('conditions' => array('MonthlyPackage.id' =>$monthlysubscription['User']['package'])));
				$user_arr['User']['id'] = $monthlysubscription['User']['id'];
				$user_arr['User']['active'] = 1;
                                $nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
 				$user_arr['User']['next_renewal_dates'] =$nextdate;

$user_arr['User']['sms_balance']=$monthlysubscription['User']['sms_balance'] + $monthlypackage['MonthlyPackage']['text_messages_credit'];
$user_arr['User']['voice_balance']=$monthlysubscription['User']['voice_balance'] + $monthlypackage['MonthlyPackage']['voice_messages_credit'];
				$user_arr['User']['sms_credit_balance_email_alerts']=0;
				$user_arr['User']['VM_credit_balance_email_alerts']=0;

				if($this->User->save($user_arr)){
					app::import('Model','Invoice');
					$this->Invoice=new Invoice();
					$invoice['id']='';
					$invoice['user_id']=$monthlysubscription['User']['id'];
					$invoice['txnid']=$subscription_id;
					$invoice['type']=2;
					$invoice['package_name']=$monthlypackage['MonthlyPackage']['package_name'];
					//$invoice['amount']=$monthlypackage['MonthlyPackage']['amount'];
                                        $invoice['amount']=$amount;
					$invoice['created']=date("Y-m-d");
					$this->Invoice->save($invoice);

                                        app::import('Model','Referral');	
                                        $this->Referral = new Referral();

                                        if($monthlysubscription['User']['active']==1){
	                                    $referraldetails = $this->Referral->find('all',array('conditions' => array('Referral.referred_by'=>$monthlysubscription['User']['id'])));
	                                    $percentage=$monthlypackage['MonthlyPackage']['amount']*RECURRING_REFERRAL_PERCENT/100;
	
		                            if(!empty($referraldetails)){
			                         foreach($referraldetails as $referraldetail){
				                     $referral['id']=$referraldetail['Referral']['id'];
				                     $referral['amount']=$percentage;
				                     $referral['paid_status']=0;
				                     $this->Referral->save($referral);
			                         }
		                            }
                                        }
				}
			}else if(!empty($monthly_number_subscription)){
				$this->loadModel('MonthlyNumberPackage');
				$monthlynumberpackage = $this->MonthlyNumberPackage->find('first', array('conditions' => array('MonthlyNumberPackage.id' =>$monthly_number_subscription['User']['number_package'])));
				$user_arr['User']['id'] = $monthly_number_subscription['User']['id'];
				$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));				
				$user_arr['User']['number_next_renewal_dates'] =$nextdate;
				if($this->User->save($user_arr)){
					app::import('Model','Invoice');
					$this->Invoice=new Invoice();
					$invoice['id']='';
					$invoice['user_id']=$monthly_number_subscription['User']['id'];
					$invoice['txnid']=$subscription_id;
					$invoice['type']=2;
					$invoice['package_name']=$monthlynumberpackage['MonthlyNumberPackage']['package_name'];
					//$invoice['amount']=$monthlynumberpackage['MonthlyNumberPackage']['amount'];
                                        $invoice['amount']=$amount;
					$invoice['created']=date("Y-m-d");
					$this->Invoice->save($invoice);
				}
			}
		}else if(($type=='invoice.payment_failed') && ($customer_id !='')){
                        $subscription_id=$jsonObject->data->object->lines->data[0]->id;
			$monthlysubscription = $this->User->find('first',array('conditions'=>array('User.stripe_customer_id'=>$customer_id,'User.monthly_stripe_subscription_id'=>$subscription_id)));
			$monthly_number_subscription = $this->User->find('first',array('conditions'=>array('User.stripe_customer_id'=>$customer_id,'User.monthly_number_subscription_id'=>$subscription_id)));

                        if(!empty($monthlysubscription)){
                            $this->loadModel('MonthlyPackage');
                            $to = $monthlysubscription['User']['email'];
                            $first_name = $monthlysubscription['User']['first_name'];
                            $monthlypackage = $this->MonthlyPackage->find('first', array('conditions' => array('MonthlyPackage.id' =>$monthlysubscription['User']['package'])));

                            $packagename = $monthlypackage['MonthlyPackage']['package_name'];

                        }else if(!empty($monthly_number_subscription)){
                            $this->loadModel('MonthlyNumberPackage');
                            $to = $monthly_number_subscription['User']['email'];
                            $first_name = $monthly_number_subscription['User']['first_name'];
                            $monthlynumberpackage = $this->MonthlyNumberPackage->find('first', array('conditions' => array('MonthlyNumberPackage.id' =>$monthly_number_subscription['User']['number_package'])));

                            $packagename = $monthlynumberpackage['MonthlyNumberPackage']['package_name'];
                        }

                        $sitename=str_replace(' ','',SITENAME);		
			$subject="Your monthly credit card payment with ".SITENAME." has failed";
			$this->Email->subject = $subject;
			$this->Email->from = $sitename;
			$this->Email->to = $to;
			$this->Email->template = 'stripe_status_failed'; 
			$this->Email->sendAs = 'html'; 
			$this->Email->Controller->set('firstname', $first_name);
                        $this->Email->Controller->set('packagename', $packagename);
			$this->Email->send();
               }
        echo "200";
        ob_start();
        //$out = @file_get_contents('php://input');
        //$event_json = json_decode('['.$out.']');
        print_r('<pre>');
        //print_r($_REQUEST);
        //print_r($_POST);
        print_r($out);
        print_r($event_json);
        print_r('</pre>');
        $out1 = ob_get_contents();
        ob_end_clean();
        $file = fopen("debug/getnotification".time().".txt", "w");
        fwrite($file, $out1);
        fclose($file);   
    }
}