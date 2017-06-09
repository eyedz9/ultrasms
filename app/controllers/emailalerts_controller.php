<?php
class EmailalertsController extends AppController {
	var $components = array('Email','Twilio');
	var $uses  = array('User','Config','Log');
	var $userId = 0;
	function sendemail(){
	 $this->autoRender = false;
	 	ob_start();
	    app::import('Model','ContactGroup');
		$this->ContactGroup = new ContactGroup();
		app::import('Model','User');
		$this->User = new User();
		$allusers= $this->User->find('all');
		foreach($allusers as $alluser){
			$timezone=$alluser['User']['timezone'];		
			date_default_timezone_set($timezone);
			$date = date('Y-m-d');
			$emailalerts = $this->ContactGroup->find('all', array('conditions' => array('ContactGroup.user_id'=>$alluser['User']['id'],'ContactGroup.subscribed_by_sms'=>array(1,2),'ContactGroup.un_subscribers'=>0,'ContactGroup.created like'=>'%'.$date.'%'),'order' =>array('ContactGroup.group_id' => 'desc'))); 
			$this->set('emailalerts',$emailalerts);
			if(!empty($emailalerts)){
				foreach($emailalerts as $emailalert){
					$group_id=$emailalert['ContactGroup']['group_id'];
					$Subscribercount1[$group_id] = $this->ContactGroup->find('count',array('conditions'=>array('ContactGroup.group_id' =>$group_id,'ContactGroup.un_subscribers'=>0,'ContactGroup.subscribed_by_sms'=>array(1,2),'ContactGroup.created like'=>'%'.$date.'%')));
				}
				$this->set('Subscribercounts',$Subscribercount1);
				$user_id=$alluser['User']['id'];
				$email_alerts=$alluser['User']['email_alerts'];
				$email_alert_options=$alluser['User']['email_alert_options'];
				if($email_alerts==2 && $email_alert_options==0){					$username = $alluser['User']['username'];					$email = $alluser['User']['email'];					$sitename=str_replace(' ','',SITENAME);					$subject=" New Subscriber Daily Summary Report";	
					$this->Email->to = $email;						$this->Email->subject = $subject;
					$this->Email->from = $sitename;
					$this->Email->template = 'daily_summary_template';
					$this->Email->sendAs = 'html';
					$this->Email->Controller->set('username', $username);
					$this->Email->send();
		        }
		    }
        }		
	}
}