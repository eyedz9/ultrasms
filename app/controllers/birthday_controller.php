<?php
class BirthdayController extends AppController {
	var $name = 'Birthday';
	var $components = array('Email','Twilio','Qr','Qrsms');
    //var $layout="default";
	function index(){
		$this->layout= 'admin_new_layout';
		$this->Birthday->recursive = 0;
		$user_id=$this->Session->read('User.id'); 
		app::import('Model','User');
		$this->User = new User();
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id,'User.mms'=>1)));
		app::import('Model','UserNumber');
		$this->UserNumber = new UserNumber();		  
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);		
		$this->set('users',$users);
		app::import('Model','Group');
		$this->Group = new Group();
		$this->paginate = array('conditions' =>array('Birthday.user_id' =>$user_id),'order' => array('Group.group_name' => 'ASC','Birthday.days' => 'ASC'),'paramType' => 'querystring');
		$data = $this->paginate('Birthday');
		$this->set('birthdays', $data);
	}
	function add(){
		$this->layout= 'admin_new_layout';
		$user_id=$this->Session->read('User.id');
		app::import('Model','Birthday');
		$this->Birthday = new Birthday();
		$Birthdaylist = $this->Birthday->find('all',array('conditions'=>array('Birthday.user_id'=>$user_id),'order' =>array('Birthday.name' => 'asc')));
		$group_arr_id = array();
		if(!empty($Birthdaylist)){
			foreach($Birthdaylist as $birthlist){
				$group_arr_id[] = $birthlist['Birthday']['group_id'];
			}
		
		}
		app::import('Model','Group');
		$this->Group = new Group();
		
		$Group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id,'Not'=>array("Group.id" =>$group_arr_id)),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
		$this->set('Group',$Group);
		
		app::import('Model','Smstemplate');
        $this->Smstemplate = new Smstemplate();
		$Smstemplate = $this->Smstemplate->find('list',array('conditions'=>array('Smstemplate.user_id'=>$user_id),'fields'=>'Smstemplate.messagename','order' =>array('Smstemplate.messagename' => 'asc')));
		$this->set('Smstemplate',$Smstemplate);
		app::import('Model','MobilePage');
        $this->MobilePage = new MobilePage();
		$mobilespage = $this->MobilePage->find('list',array('conditions'=>array('MobilePage.user_id'=>$user_id),'fields'=>'MobilePage.title','order' =>array('MobilePage.title' => 'asc')));
		$this->set('mobilespages', $mobilespage);
		app::import('Model','Group');
		$this->UserNumber = new UserNumber();
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$this->set('users',$users);
		if(!empty($this->data)){
		    app::import('Model','Birthday');
	        $this->Birthday = new Birthday();
			$this->data['Birthday']['user_id']=$user_id;
			$this->data['Birthday']['created']=date('Y-m-d h:i:s');
			$this->data['Birthday']['group_id']=$this->data['Birthday']['group_id'];
			$this->data['Birthday']['name']=$this->data['Birthday']['name'];
			if(!empty($this->data['Birthday']['msg_type'])){
				$this->data['Birthday']['sms_type']=$this->data['Birthday']['msg_type'];
			}else{
				$this->data['Birthday']['sms_type']=1;
			}
			if($this->data['Birthday']['message']!=''){
				$this->data['Birthday']['message']=$this->data['Birthday']['message'];
			}if($this->data['Birthday']['message1']!=''){
				$this->data['Birthday']['message']=$this->data['Birthday']['message1'];
			}
			$this->data['Birthday']['systemmsg']=$this->data['Birthday']['systemmsg'];
			$this->data['Birthday']['days']=$this->data['Birthday']['days'];
			if($this->data['Message']['pick_file']!=''){
				$this->data['Birthday']['image_url']=$this->data['Message']['pick_file'];
			}else if($this->data['Message']['image'][0]['name']!=''){
				$image_arr='';
				foreach($this->data['Message']['image'] as $value){
					$image=str_replace(' ','_',mt_rand().$value["name"]);	
					move_uploaded_file($value['tmp_name'],"mms/".$image);
					if($image_arr!=''){
						$image_arr = $image_arr.','.SITE_URL.'/mms/'.$image;
					}else{
						$image_arr =SITE_URL.'/mms/'.$image;					}
				}
				$this->data['Birthday']['image_url']=$image_arr;
			}
			$this->Birthday->save($this->data);
			$this->Session->setFlash(__('The Birthday SMS wish has been saved', true)); 
			$this->redirect(array('action' => 'index'));
        }
	}

	
	function delete($id = null,$type = null) { 
	    $this->autoRender = false;
  		app::import('Model','Birthday');	
	    $this->Birthday = new Birthday();
		if ($this->Birthday->delete($id)) {
			if($type == 1){
				$this->redirect(array('action' => 'mms'));
				$this->Session->setFlash(__('Birthday SMS wish has been deleted', true));
			}else if($type == 0){
				$this->redirect(array('action' => 'index'));
				$this->Session->setFlash(__('Birthday SMS wish has been deleted', true));
			}	
		}else{
			$this->Session->setFlash(__('Birthday SMS wish not deleted', true));
		}
		$this->redirect(array('action' => 'index'));
	}
	function edit($id = null) {
		$this->layout= 'admin_new_layout';
		$this->set('id',$id);
		$user_id=$this->Session->read('User.id');
		app::import('Model','Group');
		$this->UserNumber = new UserNumber();
		$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
		$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
		$this->set('numbers_mms',$numbers_mms);
		$this->set('numbers_sms',$numbers_sms);
		$users = $this->User->find('first', array('conditions' => array('User.id'=>$user_id)));
		$birthday_arr = $this->Birthday->find('first', array('conditions' => array('Birthday.id'=>$id)));
		$this->set('users',$users);
		$this->set('birthday',$birthday_arr);
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Birthday', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->data['Birthday']['id']=$this->data['Birthday']['id'];
			$this->data['Birthday']['group_id']=$this->data['Birthday']['group_id'];
			$this->data['Birthday']['name']=$this->data['Birthday']['name'];
			if(!empty($this->data['Birthday']['msg_type'])){
				$this->data['Birthday']['sms_type']=$this->data['Birthday']['msg_type'];
			}else{
				$this->data['Birthday']['sms_type']=1;
			}
			if($this->data['Birthday']['msg_type']==1){
				$this->data['Birthday']['message']=$this->data['Birthday']['message'];
			}	
			if($this->data['Birthday']['msg_type']==2){
				$this->data['Birthday']['message']=$this->data['Birthday']['message1'];
			}
			$this->data['Birthday']['systemmsg']=$this->data['Birthday']['systemmsg'];
			$this->data['Birthday']['days']=$this->data['Birthday']['days'];
			if($this->data['Birthday']['check_img_validation']==0){
				if($this->data['Message']['pick_file']!=''){
					$this->data['Birthday']['image_url']=$this->data['Message']['pick_file'];
				}else{
					$this->data['Birthday']['image_url']=$this->data['Message']['pick_file_old'];
				}
			}else{		
				if($this->data['Message']['image'][0]['name']!=''){			
					$image_arr='';
					foreach($this->data['Message']['image'] as $value){
						$image=str_replace(' ','_',mt_rand().$value["name"]);	
						move_uploaded_file($value['tmp_name'],"mms/".$image);
						if($image_arr!=''){
							$image_arr = $image_arr.','.SITE_URL.'/mms/'.$image;
						}else{
							$image_arr =SITE_URL.'/mms/'.$image;
						}
					}
					$this->data['Birthday']['image_url']=$image_arr;
					}else{
						$this->data['Birthday']['image_url']=$this->data['Message']['mms_image'];
					}
			}
			if ($this->Birthday->save($this->data)) {
				$this->Session->setFlash(__('The Birthday SMS wish has been edited', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Birthday SMS wish could not be edited. Please, try again.', true));
			}
		}
		app::import('Model','Smstemplate');
        $this->Smstemplate = new Smstemplate();
		$Smstemplate = $this->Smstemplate->find('list',array('conditions'=>array('Smstemplate.user_id'=>$user_id),'fields'=>'Smstemplate.messagename','order' =>array('Smstemplate.messagename' => 'asc')));
		$this->set('Smstemplate',$Smstemplate);
		app::import('Model','MobilePage');
        $this->MobilePage = new MobilePage();
		$mobilespage = $this->MobilePage->find('list',array('conditions'=>array('MobilePage.user_id'=>$user_id),'fields'=>'MobilePage.title','order' =>array('MobilePage.title' => 'asc')));
		$this->set('mobilespages', $mobilespage);
		if (empty($this->data)) {
			$this->data = $this->Birthday->read(null, $id);
		}
		app::import('Model','Birthday');
		$this->Birthday = new Birthday();
		$Birthdaylist = $this->Birthday->find('all',array('conditions'=>array('Birthday.user_id'=>$user_id,'Birthday.id !='=>$id),'order' =>array('Birthday.name' => 'asc')));
		$group_arr_id = array();
		if(!empty($Birthdaylist)){
			foreach($Birthdaylist as $birthlist){
				$group_arr_id[] = $birthlist['Birthday']['group_id'];
			}
		
		}
	  	app::import('Model','Group');
		$this->Group = new Group();
		$Group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id,'Not'=>array("Group.id" =>$group_arr_id)),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
		$this->set('Group',$Group);
	}
	
}
?>