<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
	var $helpers = array('Html', 'Form','Session','Form2','PaypalIpn.Paypal', 'Javascript','Validation');
	var $components = array('Cookie','Email','Session');
	

	function beforeFilter(){
		if(!empty($_GET['ref'])){
			$refArray = array();
			$refArray['id'] = $_GET['ref']; 
			$refArray['url'] = @$trace_referer['host']; 
			$trace_referer=parse_url(@$_SERVER['HTTP_REFERER']);
			$this->loadModel('User');
			$record = $this->User->find('count', array('conditions' => array('User.id' => $_GET['ref'])));
			if($record > 0){
				$this->Cookie->time = '7 Days';
				$this->Cookie->write('refArray',$refArray);
			}


		}else if(!empty($_GET['recurring_ref'])){
			$refArraynew = array();
			$refArraynew['id'] = $_GET['recurring_ref']; 
			$refArraynew['url'] = @$trace_referer['host']; 
			$trace_referer=parse_url(@$_SERVER['HTTP_REFERER']);
			$this->loadModel('User');
			$record = $this->User->find('count', array('conditions' => array('User.id' => $_GET['recurring_ref'])));
			if($record > 0){
				$this->Cookie->time = '7 Days';
				$this->Cookie->write('recurringrefArray',$refArraynew);
			}
		}
		
		if(($this->params['controller'] == 'admin_users' && $this->params['action'] != 'login') || isset($this->params['admin']))
		{	
			$this->layout = 'admin';
			if(!$this->Session->check('AdminUser'))
			{
				$this->Session->setFlash('You need to login first'); 				
				$this->redirect('/admin_users/login');
			}
		}else if(!($this->params['controller'] == 'twilios' || $this->params['controller'] == 'nexmos' || $this->params['controller'] == 'plivos' || $this->params['controller'] == 'cronjobs' || $this->params['action'] == 'sendmessage' ||  $this->params['controller'] == 'emailalerts' || $this->params['action'] == 'sendemail' || $this->params['controller'] == 'instant_payment_notifications' || $this->params['action'] == 'home'|| $this->params['action'] == 'captcha_image'||$this->params['action'] == 'success' || $this->params['action'] == 'forgot_password' || $this->params['action'] == 'sms' || $this->params['action'] == 'voice' || $this->params['action'] == 'pwdreset' ||$this->params['action'] == 'login' ||$this->params['action'] == 'about' ||$this->params['action'] == 'terms_conditions'||$this->params['action'] == 'privacy_policy'||$this->params['action'] == 'faq' || $this->params['action'] == 'antispampolicy' ||$this->params['action'] == 'paymentsucess' || $this->params['action'] == 'recurringpayment' || $this->params['action'] == 'returnurl' ||$this->params['action'] == 'user_activate_account'|| $this->params['action'] == 'add' || $this->params['action'] == 'qrcodes'|| $this->params['controller'] == 'pages' ||  $this->params['action'] == 'peoplecallrecordscript' || ($this->params['controller'] == 'webwidgets' && $this->params['action'] == 'subscribe') || ($this->params['controller'] == 'users' && $this->params['action'] == 'updateurl' || $this->params['action'] == 'redeem' || $this->params['action'] == 'autologin') || ($this->params['controller'] == 'kiosks' && $this->params['action'] == 'view' || $this->params['action'] == 'joins' || $this->params['action'] == 'checkpoints' || $this->params['action'] == 'punchcard' || $this->params['action'] == 'success' || $this->params['action'] == 'getnotification')) && !$this->Session->check('User')){
			$this->redirect('/users/login');
		}

                mysql_query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

                $this->defineConstatnt();
		//$this->disableCache();
		$this->loadModel('User');
		$someone = $this->User->find('first',array('conditions' => array('User.id' => $this->Session->read('User.id'))) );
		if(!empty($someone)){
			$timezone = $someone['User']['timezone'];		
			date_default_timezone_set($timezone);
		}

                if ($this->params['controller'] == 'birthday' && $someone['User']['birthdaywishes']==0) {
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the birthday SMS wishes module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if ($this->params['controller'] == 'responders' && $someone['User']['autoresponders']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the autoresponders module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if (($this->params['controller'] == 'contacts' && $this->params['action'] == 'upload') && $someone['User']['importcontacts']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the import contacts module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if (($this->params['controller'] == 'users' && $this->params['action'] == 'shortlinks') && $someone['User']['shortlinks']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the short links module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if (($this->params['controller'] == 'groups' && ($this->params['action'] == 'broadcast_list' || $this->params['action'] == 'voicebroadcasting')) && $someone['User']['voicebroadcast']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the voice broadcast module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if ($this->params['controller'] == 'polls' and $someone['User']['polls']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the polls module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if ($this->params['controller'] == 'contests' and $someone['User']['contests']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the contests module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if ($this->params['controller'] == 'smsloyalty' and $someone['User']['loyaltyprograms']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the loyalty programs module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if ($this->params['controller'] == 'kiosks' and $this->params['action'] != 'joins' and $this->params['action'] != 'success' and $this->params['action'] != 'checkpoints' and $this->params['action'] != 'view' and $this->params['action'] != 'punchcard' and $someone['User']['kioskbuilder']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the kiosks module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if ($this->params['controller'] == 'mobile_pages' and $someone['User']['mobilepagebuilder']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the mobile splash page builder module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }else if ($this->params['controller'] == 'webwidgets' and $this->params['action'] != 'subscribe' and $someone['User']['webwidgets']==0){
                    $this->layout= 'admin_new_layout';                   
                    $this->Session->setFlash('You do not have access to the web sign-up widgets module'); 		
                    $this->redirect(array('controller' =>'users', 'action' => 'profile'));	
                }
		$this->getuserdetails();
	}	
	function beforeRender(){
		$this->loadModel('User');
		if($this->isLoggedIn()){
			$this->set('loggedUser', $this->getLoggedUserDetails());
			 $this->set('statistic', $this->statistic());
		}
                $this->_setErrorLayout();
	}

        function _setErrorLayout() {
             if($this->name == 'CakeError') {
                if($this->isLoggedIn()){
                  $this->layout = 'login_layout';
                }else{
                  $this->layout = '';
                }
             }
        }

	function getLoggedUserDetails(){
		$this->loadModel('User');
		return $this->User->find('first',array('conditions' => array('User.id' => $this->Session->read('User.id'))) );
	}

	function isLoggedIn(){
		if($this->Session->read('User.id'))
			return true;
		else
			return false;
	} 

	function getLoggedInUserId(){
		return $this->Session->read('User.id');
	}

	function getLoggedInUserName(){
		return $this->Session->read('User.username');
	}

	function defineConstatnt(){
		$this->loadModel('Config');
		$this->loadModel('Stripe');
         
		$configs = $this->Config->find('first');
		unset($configs['Config']['id']);
		foreach($configs['Config'] as $key => $value){
			define(strtoupper($key), $value);
		}
		$stripes = $this->Stripe->find('first');
		unset($stripes['Stripe']['id']);
		foreach($stripes['Stripe'] as $key => $value){
			define(strtoupper($key), $value);
		}
	}


        function getRealUserIp(){
            switch(true){
               case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
               case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
               case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
               default : return $_SERVER['REMOTE_ADDR'];
            }
        }

	function getuserdetails(){
		$this->loadModel('Log');
		//$this->loadModel('ContactGroup');
		$user_id = $this->Session->read('User.id');
		if(isset($user_id)){
			$month=date('m');
                        //$this->Log->recursive = -1;		
			$logs_inboxdata =  $this->Log->find('all', array('conditions' => array('Log.user_id' =>$user_id, 'Log.route' => 'inbox' ,'msg_type' => 'text', 'read' => '0'),'limit' => 10,'recursive' => -1,'order' => array('Log.created DESC')));
			$this->set('logs_inboxdetails',$logs_inboxdata);
			$logs_inboxvoicedata =  $this->Log->find('all', array('conditions' => array('Log.user_id' =>$user_id, 'Log.route' => 'inbox' ,'msg_type' => 'voice', 'read' => '0'),'limit' => 10,'recursive' => -1,'order' => array('Log.created DESC')));
			$this->set('logs_inboxvoicedetails',$logs_inboxvoicedata);
			$this->set('unreadTextMsg', $this->Log->find('count', array('conditions' => array('Log.user_id' =>$user_id, 'Log.read' => 0, 'Log.route' => 'inbox', 'msg_type' => 'text'),'recursive' => -1)));
			$this->set('unreadVoiceMsg', $this->Log->find('count', array('conditions' => array('Log.user_id' =>$user_id, 'Log.read' => 0, 'Log.route' => 'inbox', 'msg_type' => 'voice'),'recursive' => -1)));
		}
	}
	function afterPaypalNotification($txnId){
		//Here is where you can implement code to apply the transaction to your app.
		//for example, you could now mark an order as paid, a subscription, or give the user premium access.
		//retrieve the transaction using the txnId passed and apply whatever logic your site needs.
		$transaction = ClassRegistry::init('PaypalIpn.InstantPaymentNotification')->find('first',array('conditions' =>array('id' =>$txnId)));
		$this->log($transaction['InstantPaymentNotification']['id'], 'paypal');
		//Tip: be sure to check the payment_status is complete because failure 
		//     are also saved to your database for review.
		if($transaction['InstantPaymentNotification']['payment_status'] == 'Completed'){
		  //Yay!  We have monies!
		  $this->log('completed', 'paypal');
		  $this->loadModel('User');
		  $this->User->id = $transaction['InstantPaymentNotification']['custom'];
		  $this->User->saveField('active', 1);
		  $this->User->saveField('sms_balance', FREE_SMS);
		  $this->User->saveField('voice_balance', FREE_VOICE);
		  $this->loadModel('Referral');
		  $this->Referral->updateAll(array('Referral.account_activated' =>1), array('Referral.user_id' =>$transaction['InstantPaymentNotification']['custom']));
		  $this->sendActivationEmail($transaction['InstantPaymentNotification']['custom']);
		  #$this->redirect(array('controller' => 'users', 'action'=> 'profile'));
		  
		}else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Pending'){
                       $this->sendPayPalActivationPendingEmail($transaction['InstantPaymentNotification']['custom'],$transaction['InstantPaymentNotification']['payment_status'],$transaction['InstantPaymentNotification']['pending_reason']);
                
                }else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Failed'){
                       $this->sendPayPalActivationFailingEmail($transaction['InstantPaymentNotification']['custom']);
		}else{
			//Oh no, better look at this transaction to determine what to do; like email a decline letter.
			$this->log('not completed', 'paypal');
		}
		
	}
	function sendActivationEmail($uid){
		$this->loadModel('User');
		$UserData = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
		#$this->Email->delivery = 'debug';
		$sitename=str_replace(' ','',SITENAME);
		$this->Email->to = $UserData['User']['email'];
		$this->Email->subject = 'Account has been activated';
		$this->Email->from = $sitename;
		$this->Email->template = 'membership'; // note no '.ctp'
		//Send as 'html', 'text' or 'both' (default is 'text')
		$this->Email->sendAs = 'html'; // because we like to send pretty mail
		//Set view variables as normal
		$this->set('data', $UserData);
		//Do not pass any args to send()
		$this->Email->send();
		
	}
  

	function afterPaypalNotificationCredit($txnId, $package_id){
		$transaction = ClassRegistry::init('PaypalIpn.InstantPaymentNotification')->find('first',array('conditions' =>array('id' =>$txnId)));
		$this->log($transaction['InstantPaymentNotification']['id'], 'paypal');
		//Tip: be sure to check the payment_status is complete because failure 
		//     are also saved to your database for review.

                $this->loadModel('Package');
		$package = $this->Package->find('first', array('conditions' => array('Package.id' =>$package_id)));
                $package_name = $package['Package']['name'];

		if($transaction['InstantPaymentNotification']['payment_status'] == 'Completed'){
			//Yay!  We have monies!
			$this->log('creditcompleted', 'paypal');
			$this->log($package_id, 'paypal');
			$this->log($txnId, 'paypal');
			//$this->loadModel('Package');
			//$package = $this->Package->find('first', array('conditions' => array('Package.id' =>$package_id)));
			  
			  
			$this->loadModel('User');
			$someone = $this->User->find('first', array('conditions' => array('User.id' =>$transaction['InstantPaymentNotification']['custom'])));
			$this->User->id = $transaction['InstantPaymentNotification']['custom'];
			$this->User->saveField('active', 1);
			if($package['Package']['type'] == 'text'){
				$sms_balance = $someone['User']['sms_balance']+ $package['Package']['credit'];
				$this->User->saveField('sms_balance', $sms_balance);
				$this->User->saveField('sms_credit_balance_email_alerts',0);
				app::import('Model','Invoice');
				
				$this->Invoice=new Invoice();
				
				$invoice['user_id']=$transaction['InstantPaymentNotification']['custom'];
				
				$invoice['txnid']=$transaction['InstantPaymentNotification']['txn_id'];
				
				$invoice['type']=0;
                                
                                $invoice['package_name']=$package_name;
				
				$invoice['amount']=$package['Package']['amount'];
				
				$invoice['created']=date("Y-m-d");
				
				$this->Invoice->save($invoice);
			}elseif($package['Package']['type'] == 'voice'){
				$voice_balance = intval($someone['User']['voice_balance']) + intval($package['Package']['credit']);
				$this->User->saveField('voice_balance', $voice_balance);
				$this->User->saveField('VM_credit_balance_email_alerts',0);
				app::import('Model','Invoice');
				$this->Invoice=new Invoice();
				
				$invoice['user_id']=$transaction['InstantPaymentNotification']['custom'];
				
				$invoice['txnid']=$transaction['InstantPaymentNotification']['txn_id'];
				
				$invoice['type']=0;
				
				$invoice['amount']=$package['Package']['amount'];

                                $invoice['package_name']=$package_name;
				
				$invoice['created']=date("Y-m-d");
				
				$this->Invoice->save($invoice);
			}
                }else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Pending'){
                       $this->sendPayPalPendingEmail($transaction['InstantPaymentNotification']['custom'],$package_name,$transaction['InstantPaymentNotification']['payment_status'],$transaction['InstantPaymentNotification']['pending_reason']);
                
                }else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Failed'){
                       $this->sendPayPalFailingEmail($transaction['InstantPaymentNotification']['custom'],$package_name);
		}else{
			//Oh no, better look at this transaction to determine what to do; like email a decline letter.
			$this->log('not completed', 'paypal');
		}
	}
	function statistic(){
		//$this->loadModel('Referral');
		app::import('Model','Referral');
		$this->Referral=new Referral();
						
		$referredUser = $this->Referral->find('count', array('conditions' => array('Referral.referred_by' =>$this->Session->read('User.id'), 'Referral.account_activated' =>1)));
		
		$referredpaid = $this->Referral->find('count', array('conditions' => array('Referral.referred_by' =>$this->Session->read('User.id'), 'Referral.paid_status' =>1)));
		
		$record = $this->Referral->find('all', array('conditions' => array('Referral.referred_by' =>$this->Session->read('User.id'), 'Referral.account_activated' =>1, 'Referral.paid_status' =>1), 'fields' => "SUM(amount) as total"));
		$overAllCredit = $record['0']['0']['total'];
		
		$invoice = $this->Referral->find('all', array('conditions' => array('Referral.referred_by' =>$this->Session->read('User.id'), 'Referral.account_activated' =>1, 'Referral.paid_status' =>0,"Referral.created <" => date('Y-m-d',strtotime( "next saturday" ))), 'fields' => "SUM(amount) as commission"));
		return array('referredUser' =>$referredUser, 'referredpaid' =>$referredpaid,'overAllCredit' => number_format($overAllCredit, 2), 'unPaidCommision' => number_format($invoice['0']['0']['commission'],2));
	}



function afterPaypalNotificationSubscribe($txnId, $package_id){
		$transaction = ClassRegistry::init('PaypalIpn.InstantPaymentNotification')->find('first',array('conditions' =>array('id' =>$txnId)));
		$this->log($transaction['InstantPaymentNotification']['id'], 'paypal');
		//Tip: be sure to check the payment_status is complete because failure 
		//     are also saved to your database for review.

                 $this->loadModel('MonthlyPackage');
                 $monthlypackage = $this->MonthlyPackage->find('first', array('conditions' => array('MonthlyPackage.id' =>$package_id)));
		$package_name = $monthlypackage['MonthlyPackage']['package_name'];

		if($transaction['InstantPaymentNotification']['payment_status'] == 'Completed'){
			//Yay!  We have monies!
			$this->log('creditcompleted', 'paypal');
			$this->log($package_id, 'paypal');
			$this->log($txnId, 'paypal');
									  
			$this->loadModel('User');
			$someone = $this->User->find('first', array('conditions' => array('User.id' =>$transaction['InstantPaymentNotification']['custom'])));

			$firstsubpayment = $this->User->find('first',array('conditions' => array('User.recurring_paypal_email'=>$transaction['InstantPaymentNotification']['payer_email'])));    	
			$this->User->id = $transaction['InstantPaymentNotification']['custom'];
                        $recurring_email = $transaction['InstantPaymentNotification']['payer_email'];
	                $nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));

                        $this->data['User']['active'] = 1;
                        $this->data['User']['package'] = $package_id;
                        $this->data['User']['next_renewal_dates'] =$nextdate;
			$this->data['User']['recurring_paypal_email'] =$recurring_email;
			$this->data['User']['sms_balance'] =$someone['User']['sms_balance']+$monthlypackage['MonthlyPackage']['text_messages_credit'];
			$this->data['User']['voice_balance'] =$someone['User']['voice_balance']+$monthlypackage['MonthlyPackage']['voice_messages_credit'];
			$this->data['User']['sms_credit_balance_email_alerts']=0;	
			$this->data['User']['VM_credit_balance_email_alerts']=0;
			$this->User->save($this->data);

			app::import('Model','Referral');	
			$this->Referral = new Referral();

                        if(!empty($firstsubpayment)){
                            if($firstsubpayment['User']['active']==1){
				$referraldetails = $this->Referral->find('all',array('conditions' => array('Referral.referred_by'=>$firstsubpayment['User']['id'])));
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
                        }else{

			$Referraldetails = $this->Referral->find('first',array('conditions' =>  array('Referral.user_id'=>$transaction['InstantPaymentNotification']['custom'])));
				 if(!empty($Referraldetails)){
					 $referral['id']=$Referraldetails['Referral']['id'];
					 $referral['account_activated']=1;	
					 $this->Referral->save($referral);
				}
                        }
			app::import('Model','Invoice');		
			$this->Invoice=new Invoice();	
			$invoice['user_id']=$transaction['InstantPaymentNotification']['custom'];	
			$invoice['amount']=$monthlypackage['MonthlyPackage']['amount'];
			$invoice['txnid']=$transaction['InstantPaymentNotification']['txn_id'];
			$invoice['type']=0;	
                        $invoice['package_name']=$package_name;
			$invoice['created']=date("Y-m-d");	
			$this->Invoice->save($invoice);

			$this->Session->write('User.active',1);
			$this->Session->write('User.package',$package_id);
			//$this->Session->setFlash(__( $package_name. '  package is activated.', true));
		}else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Pending'){
				   $this->sendPayPalPendingEmail($transaction['InstantPaymentNotification']['custom'],$package_name,$transaction['InstantPaymentNotification']['payment_status'],$transaction['InstantPaymentNotification']['pending_reason']);
			
		}else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Failed'){
				   $this->sendPayPalFailingEmail($transaction['InstantPaymentNotification']['custom'],$package_name);

		}else if ($transaction['InstantPaymentNotification']['txn_type']== 'subscr_cancel'){
				$this->loadModel('User');
				$someone = $this->User->find('first', array('conditions' => array('User.id'  =>$transaction['InstantPaymentNotification']['custom'])));
				$this->User->id = $transaction['InstantPaymentNotification']['custom'];
				$this->data['User']['active'] = 0;
				$this->data['User']['package'] = 0;
				$this->data['User']['next_renewal_dates'] ='';
				$this->data['User']['sms_credit_balance_email_alerts']=0;	
				$this->data['User']['VM_credit_balance_email_alerts']=0;
				$this->User->save($this->data);
				$this->sendSubscriptionCancelEmail($transaction['InstantPaymentNotification']['custom']);
		}else{
				//Oh no, better look at this transaction to determine what to do; like email a decline letter.
				$this->log('not completed', 'paypal');
		}
	}

	function afterPaypalNotificationSubscribenumbers($txnId, $package_id){
		$transaction = ClassRegistry::init('PaypalIpn.InstantPaymentNotification')->find('first',array('conditions' =>array('id' =>$txnId)));
		$this->log($transaction['InstantPaymentNotification']['id'], 'paypal');
		//Tip: be sure to check the payment_status is complete because failure 
		// are also saved to your database for review.

		$this->loadModel('MonthlyNumberPackage');
		$monthlynumberpackage = $this->MonthlyNumberPackage->find('first', array('conditions' => array('MonthlyNumberPackage.id' =>$package_id)));
		$package_name = $monthlynumberpackage['MonthlyNumberPackage']['package_name'];

		if($transaction['InstantPaymentNotification']['payment_status'] == 'Completed'){
			//Yay!  We have monies!
			$this->log('creditcompleted', 'paypal');
			$this->log($package_id, 'paypal');
			$this->log($txnId, 'paypal');
			$this->loadModel('User');
			$someone = $this->User->find('first', array('conditions' => array('User.id' =>$transaction['InstantPaymentNotification']['custom'])));
                        $number_limit_set = $someone['User']['number_limit_set'];
                        
                        $this->User->id = $transaction['InstantPaymentNotification']['custom'];
			$nextdate= date("Y-m-d",mktime(0, 0, 0, date("m")+1 , date("d"), date("Y")));
                        $this->data['User']['number_next_renewal_dates'] =$nextdate;
			$this->data['User']['id'] =$transaction['InstantPaymentNotification']['custom'];
                        $this->data['User']['active'] = 1;

                        if ($number_limit_set == 0){
			   
			   $this->data['User']['number_limit'] = $someone['User']['number_limit'] + $monthlynumberpackage['MonthlyNumberPackage']['total_secondary_numbers'];
			   $this->data['User']['number_package'] = $package_id;
                           $this->data['User']['number_limit_set'] = 1;

			}
                        $this->User->save($this->data);

			app::import('Model','Invoice');		
			$this->Invoice=new Invoice();	
			$invoice['user_id']=$transaction['InstantPaymentNotification']['custom'];	
			$invoice['amount']=$monthlynumberpackage['MonthlyNumberPackage']['amount'];
			$invoice['txnid']=$transaction['InstantPaymentNotification']['txn_id'];
			$invoice['type']=0;	
                        $invoice['package_name']=$package_name;
			$invoice['created']=date("Y-m-d");	
			$this->Invoice->save($invoice);
			$this->Session->write('User.number_package',$package_id);
		}else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Pending'){
				$this->sendPayPalPendingEmail($transaction['InstantPaymentNotification']['custom'],$package_name,$transaction['InstantPaymentNotification']['payment_status'],$transaction['InstantPaymentNotification']['pending_reason']);
		}else if ($transaction['InstantPaymentNotification']['payment_status'] == 'Failed'){
				$this->sendPayPalFailingEmail($transaction['InstantPaymentNotification']['custom'],$package_name);
		}else if ($transaction['InstantPaymentNotification']['txn_type']== 'subscr_cancel'){
				$this->loadModel('User');
				$someone = $this->User->find('first', array('conditions' => array('User.id'  =>$transaction['InstantPaymentNotification']['custom'])));
				$this->User->id = $transaction['InstantPaymentNotification']['custom'];
				$this->data['User']['number_limit'] = $someone['User']['number_limit'] - $monthlynumberpackage['MonthlyNumberPackage']['total_secondary_numbers'];
				$this->data['User']['number_package'] = 0;
				$this->data['User']['number_next_renewal_dates'] ='';
                                $this->data['User']['number_limit_set'] = 0;
                                $this->data['User']['active'] = 0;
				$this->User->save($this->data);
				$this->sendSubscriptionNumberCancelEmail($transaction['InstantPaymentNotification']['custom']);
		}else{
				//Oh no, better look at this transaction to determine what to do; like email a decline letter.
				$this->log('not completed', 'paypal');
		}
	}

	function sendSubscriptionCancelEmail($uid){
		$this->loadModel('User');
		$UserData = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
		$sitename=str_replace(' ','',SITENAME);		
		$subject="Your PayPal subscription with ".SITENAME." has been canceled";
		$this->Email->subject = $subject;
		$this->Email->from = $sitename;
		$this->Email->to = $UserData['User']['email'];
		$this->Email->template = 'paypal_subscription_cancel'; 
		$this->Email->sendAs = 'html'; 
		$this->set('data', $UserData);
   		$this->Email->send();
	}

        function sendSubscriptionNumberCancelEmail($uid){
		$this->loadModel('User');
		$UserData = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
		$sitename=str_replace(' ','',SITENAME);		
		$subject="Your PayPal subscription with ".SITENAME." has been canceled";
		$this->Email->subject = $subject;
		$this->Email->from = $sitename;
		$this->Email->to = $UserData['User']['email'];
		$this->Email->template = 'paypal_number_subscription_cancel'; 
		$this->Email->sendAs = 'html'; 
		$this->set('data', $UserData);
   		$this->Email->send();
	}

	function sendPayPalPendingEmail($uid,$packagename,$status,$reason){
		$this->loadModel('User');
		$UserData = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
		$sitename=str_replace(' ','',SITENAME);		
		$subject="Your PayPal payment with ".SITENAME." is Pending";
		$this->Email->subject = $subject;
		$this->Email->from = $sitename;
		$this->Email->to = $UserData['User']['email'];
		$this->Email->template = 'paypal_status_pending'; 
		$this->Email->sendAs = 'html'; 
		$first_name = $UserData['User']['first_name'];
		$this->Email->Controller->set('firstname', $first_name);
		$this->Email->Controller->set('packagename', $packagename);
		$this->Email->Controller->set('status', $status);
		$this->Email->Controller->set('reason', $reason);
		$this->Email->send();
	}

	function sendPayPalFailingEmail($uid,$packagename){
		$this->loadModel('User');
		$UserData = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
		$sitename=str_replace(' ','',SITENAME);		
		$subject="Your PayPal payment with ".SITENAME." has failed";
		$this->Email->subject = $subject;
		$this->Email->from = $sitename;
		$this->Email->to = $UserData['User']['email'];
		$this->Email->template = 'paypal_status_failed'; 
		$this->Email->sendAs = 'html'; 
		$first_name = $UserData['User']['first_name'];
		$this->Email->Controller->set('firstname', $first_name);
		$this->Email->Controller->set('packagename', $packagename);
		$this->Email->send();
		
	}

        function sendPayPalActivationPendingEmail($uid,$status,$reason){
		$this->loadModel('User');
		$UserData = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
		$sitename=str_replace(' ','',SITENAME);		
		$subject="Your PayPal payment with ".SITENAME." is Pending";
		$this->Email->subject = $subject;
		$this->Email->from = $sitename;
		$this->Email->to = $UserData['User']['email'];
		$this->Email->template = 'paypal_activation_status_pending'; 
		$this->Email->sendAs = 'html'; 
		$first_name = $UserData['User']['first_name'];
		$this->Email->Controller->set('firstname', $first_name);
		$this->Email->Controller->set('status', $status);
		$this->Email->Controller->set('reason', $reason);
		$this->Email->send();
	}

	function sendPayPalActivationFailingEmail($uid){
		$this->loadModel('User');
		$UserData = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
		$sitename=str_replace(' ','',SITENAME);		
		$subject="Your PayPal payment with ".SITENAME." has failed";
		$this->Email->subject = $subject;
		$this->Email->from = $sitename;
		$this->Email->to = $UserData['User']['email'];
		$this->Email->template = 'paypal_activation_status_failed'; 
		$this->Email->sendAs = 'html'; 
		$first_name = $UserData['User']['first_name'];
		$this->Email->Controller->set('firstname', $first_name);
		$this->Email->send();
		
	}
	function choosecolor(){
		$this->autoRender = false;
		$color = array('#1abc9c','#3498db','#9b59b6','#34495e','#16a085','#f1c40f','#27ae60','#e67e22','#f39c12','#d35400','#2980b9','#8e44ad','#2c3e50','#e74c3c','#a71065','#c0392b','#ff6a28','#7f8c8d','#0a8351','#D91E18','#32c5d2','#16565c','#d0006c');
		$k = array_rand($color);
		$firstcolor = $color[$k];
		return $firstcolor;
	}
	function validateNumber($phone_number){
        $this->autoRender = false;
        // set API Access Key
        $access_key = NUMVERIFY;
        // set email address
        //$phone_number = '14158586273';
        $url = NUMVERIFYURL.'?access_key='.$access_key.'&number='.$phone_number;
        // Initialize CURL:
        //$ch = curl_init('http://apilayer.net/api/validate?access_key='.$access_key.'&number='.$phone_number.'');  
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);
        // Decode JSON response:
        $validationResult = json_decode($json, true);
        return $validationResult;
        //print_r($validationResult['valid']);
        //print_r($validationResult['carrier']);
        //print_r($validationResult['line_type']);
        //print_r($validationResult['location']);

	}


}//class