<?php
class SmsloyaltyController extends AppController {

	var $name = 'Smsloyalty';	
	function index(){
		$this->layout='admin_new_layout';
		$user_id=$this->Session->read('User.id');
		if($user_id > 0){
			app::import('Model','Smsloyalty');
			$this->Smsloyalty = new Smsloyalty();
			$this->Smsloyalty->recursive = 0;
			$this->paginate = array('conditions' => array('Smsloyalty.user_id' =>$user_id),'order' =>array('Smsloyalty.id' => 'asc'));
			$loyalty = $this->paginate('Smsloyalty');
			$this->set('loyaltys', $loyalty);
		}else{
			$this->Session->setFlash('You need to login first'); 				
			$this->redirect(array('controller' =>'users', 'action'=>'login'));
		}
	}
	function participants($id=null){
		$this->layout = 'popup';
		$user_id=$this->Session->read('User.id');
		if($user_id > 0){
			app::import('Model','SmsloyaltyUser');
			$this->SmsloyaltyUser = new SmsloyaltyUser();
			$this->SmsloyaltyUser->recursive = 0;
			$this->paginate = array('conditions' => array('SmsloyaltyUser.sms_loyalty_id' =>$id,'SmsloyaltyUser.user_id' =>$user_id),'order' =>array('SmsloyaltyUser.id' => 'asc'));
			$participant = $this->paginate('SmsloyaltyUser');
			$this->set('participant', $participant);
		}else{
			$this->Session->setFlash('You need to login first'); 				
			$this->redirect(array('controller' =>'users', 'action'=>'login'));
		}
	}
	function add(){
		$this->layout='admin_new_layout';
		$user_id=$this->Session->read('User.id');
		if($user_id > 0){

                        app::import('Model','Smsloyalty');
		        $this->Smsloyalty = new Smsloyalty();
		        $loyaltylist = $this->Smsloyalty->find('all',array('conditions'=>array('Smsloyalty.user_id'=>$user_id),'order' =>array('Smsloyalty.program_name' => 'asc')));
		        $group_arr_id = array();
		        if(!empty($loyaltylist)){
			     foreach($loyaltylist as $loylist){
				$group_arr_id[] = $loylist['Smsloyalty']['group_id'];
			     }
		
		        }

			app::import('Model','Group');
			$this->Group = new Group();
			//$Group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
                        $Group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id,'Not'=>array("Group.id" =>$group_arr_id)),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
			$this->set('groups',$Group);
			srand ((double) microtime() * 10000000);
			$input = array("A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
			$random_generator="";// Initialize the string to store random numbers
			for($i=1;$i<6+1;$i++)	{ 
				// Loop the number of times of required digits
				if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
					$rand_index = array_rand($input);
					$random_generator .=$input[$rand_index]; // One char is added
				}else{
						$random_generator .=rand(1,7); // one number is added
				}
			}
			$this->set('coupancode',$random_generator);
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber(); 
			$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
			$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
			$this->set('numbers_mms',$numbers_mms);
			$this->set('numbers_sms',$numbers_sms);
			app::import('Model','User');
			$this->User = new User();
			$users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
			$this->set('users',$users);
			if(!empty($this->data)){
		        $groupname=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$this->data['Smsloyalty']['codestatus'],'Group.user_id'=>$user_id)));
				if(!empty($groupname)){
     				$this->Session->setFlash(__('Keyword is already registered for a group. Please choose another status keyword.', true));
     				$this->redirect(array('controller' =>'smsloyalty', 'action'=>'add'));  
				}
				app::import('Model','Contest');
				$this->Contest = new Contest();
				$contestkeyword = $this->Contest->find('first',array('conditions'=>array('Contest.keyword '=>$this->data['Smsloyalty']['codestatus'],'Contest.user_id'=>$user_id)));
				if(!empty($contestkeyword)){ 
     				$this->Session->setFlash(__('Keyword is already registered for a contest. Please choose another status keyword.', true));
     				$this->redirect(array('controller' =>'smsloyalty', 'action'=>'add')); 
				}
				$loyaltykeyword = $this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.codestatus '=>$this->data['Smsloyalty']['codestatus'],'Smsloyalty.user_id'=>$user_id)));
				if(!empty($loyaltykeyword)){ 
     				$this->Session->setFlash(__('Keyword is already registered for another loyalty program. Please choose another status keyword.', true));
     				$this->redirect(array('controller' =>'smsloyalty', 'action'=>'add')); 
				}
				if(isset($this->data['Smsloyalty']['image']['name'])){
					if($this->data['Smsloyalty']['image']['name']!=''){
						$image=str_replace(' ','_',time().$this->data['Smsloyalty']['image']['name']);
						move_uploaded_file($this->data['Smsloyalty']['image']['tmp_name'],"mms/".$image);	
						$image_arr = $image;						
					}
				}else{
					$image_arr = '';
				}
				$loyalty['Smsloyalty']['id'] = '';
				$loyalty['Smsloyalty']['user_id'] = $user_id;
				$loyalty['Smsloyalty']['program_name'] = $this->data['Smsloyalty']['program_name'];
				$loyalty['Smsloyalty']['group_id'] = $this->data['Smsloyalty']['group_id'];
				$loyalty['Smsloyalty']['startdate'] = date('Y-m-d',strtotime($this->data['Smsloyalty']['start']));
				$loyalty['Smsloyalty']['enddate'] = date('Y-m-d',strtotime($this->data['Smsloyalty']['end']));
				$loyalty['Smsloyalty']['reachgoal'] = $this->data['Smsloyalty']['reachgoal'];
				$loyalty['Smsloyalty']['addpoints'] = $this->data['Smsloyalty']['addpoints'];
				$loyalty['Smsloyalty']['reachedatgoal'] = $this->data['Smsloyalty']['reachedatgoal'];
				$loyalty['Smsloyalty']['coupancode'] = $this->data['Smsloyalty']['coupancode'];
				$loyalty['Smsloyalty']['codestatus'] = $this->data['Smsloyalty']['codestatus'];
				$loyalty['Smsloyalty']['checkstatus'] = $this->data['Smsloyalty']['checkstatus'];
				if(isset($this->data['Smsloyalty']['type'])){
					$loyalty['Smsloyalty']['type'] = $this->data['Smsloyalty']['type'];
					$loyalty['Smsloyalty']['image'] = $image_arr;
				}else{
					$loyalty['Smsloyalty']['type'] = 1;
				}
				if(isset($this->data['Smsloyalty']['notify_punch_code'])){
					$loyalty['Smsloyalty']['notify_punch_code'] = 1;
				}else{
					$loyalty['Smsloyalty']['notify_punch_code'] = 0;
				}
				if(isset($this->data['Smsloyalty']['my_email_address'])){
					$loyalty['Smsloyalty']['my_email_address'] = 1;
				}else{
					$loyalty['Smsloyalty']['my_email_address'] = 0;
				}
				if(isset($this->data['Smsloyalty']['email_address'])){
					$loyalty['Smsloyalty']['email_address'] = 1;
					$loyalty['Smsloyalty']['email_address_input'] = $this->data['Smsloyalty']['email_address_input'];
				}else{
					$loyalty['Smsloyalty']['email_address'] = 0;
					$loyalty['Smsloyalty']['email_address_input'] = '';
				}
				if(isset($this->data['Smsloyalty']['mobile_number'])){
					$loyalty['Smsloyalty']['mobile_number'] = 1;
					$loyalty['Smsloyalty']['mobile_number_input'] = $this->data['Smsloyalty']['mobile_number_input'];
				}else{
					$loyalty['Smsloyalty']['mobile_number'] = 0;
					$loyalty['Smsloyalty']['mobile_number_input'] = '';
				}
				if($this->Smsloyalty->save($loyalty)){
					$this->Session->setFlash('Loyalty program has been saved.'); 				
					$this->redirect(array('controller' =>'smsloyalty', 'action'=>'index'));
				}else{
					$this->Session->setFlash('Loyalty program has not been saved.please try again.'); 	
				}
			}
		}else{
			$this->Session->setFlash('You need to login first'); 				
			$this->redirect(array('controller' =>'users', 'action'=>'login'));
		}

	}
	function edit($id=null){
		$this->layout='admin_new_layout';
		$user_id=$this->Session->read('User.id');
		if($user_id > 0){
			app::import('Model','Group');
			$this->Group = new Group();
			$Group = $this->Group->find('list',array('conditions'=>array('Group.user_id'=>$user_id),'fields'=>'Group.group_name','order' =>array('Group.group_name' => 'asc')));
			$this->set('groups',$Group);
			$this->set('id',$id);
			app::import('Model','Smsloyalty');
			$this->Smsloyalty = new Smsloyalty();
			$loyalty_arr= $this->Smsloyalty->find('first', array('conditions'=>array('Smsloyalty.id'=>$id)));
			$this->set('loyalty',$loyalty_arr);
			app::import('Model','UserNumber');
			$this->UserNumber = new UserNumber(); 
			$numbers_sms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.sms'=>1))); 
			$numbers_mms = $this->UserNumber->find('first', array('conditions' => array('UserNumber.user_id'=>$user_id,'UserNumber.mms'=>1))); 
			$this->set('numbers_mms',$numbers_mms);
			$this->set('numbers_sms',$numbers_sms);
			app::import('Model','User');
			$this->User = new User();
			$users = $this->User->find('first', array('conditions' => array('User.id' =>$user_id)));
			$this->set('users',$users);
			if(!empty($this->data)){
				$groupname=$this->Group->find('first',array('conditions'=>array('Group.keyword'=>$this->data['Smsloyalty']['codestatus'],'Group.user_id'=>$user_id)));
    			if(!empty($groupname)){
     				$this->Session->setFlash(__('Keyword is already registered for a group. Please choose another status keyword.', true));
     				$this->redirect(array('controller' =>'smsloyalty', 'action'=>'edit/'.$id));  
				}
				app::import('Model','Contest');
				$this->Contest = new Contest();
				$contestkeyword = $this->Contest->find('first',array('conditions'=>array('Contest.keyword '=>$this->data['Smsloyalty']		['codestatus'],'Contest.user_id'=>$user_id)));
				if(!empty($contestkeyword)){ 
     				$this->Session->setFlash(__('Keyword is already registered for a contest. Please choose another status keyword.', true));
     				$this->redirect(array('controller' =>'smsloyalty', 'action'=>'edit/'.$id)); 
				}
				$loyaltykeyword = $this->Smsloyalty->find('first',array('conditions'=>array('Smsloyalty.codestatus '=>$this->data['Smsloyalty']['codestatus'],'Smsloyalty.user_id'=>$user_id,'Smsloyalty.coupancode !='=>$this->data['Smsloyalty']['coupancode'])));

				if(!empty($loyaltykeyword)){ 
     				$this->Session->setFlash(__('Keyword is already registered for another loyalty program. Please choose another status keyword.', true));
     				$this->redirect(array('controller' =>'smsloyalty', 'action'=>'edit/'.$id)); 
				}
				$loyalty_arr= $this->Smsloyalty->find('first', array('conditions'=>array('Smsloyalty.id'=>$this->data['Smsloyalty']['id'])));
                $image_arr = $loyalty_arr['Smsloyalty']['image'];
				if(isset($this->data['Smsloyalty']['image']['name']) || $image_arr == ''){
					if($this->data['Smsloyalty']['image']['name']!=''){
						$image=str_replace(' ','_',time().$this->data['Smsloyalty']['image']['name']);
						move_uploaded_file($this->data['Smsloyalty']['image']['tmp_name'],"mms/".$image);	
						$image_arr = $image;						
					}
				 }else{
					$image_arr = $loyalty_arr['Smsloyalty']['image'];
				}
				$loyalty['Smsloyalty']['id'] =$this->data['Smsloyalty']['id'];
				$loyalty['Smsloyalty']['user_id'] = $user_id;
				$loyalty['Smsloyalty']['program_name'] = $this->data['Smsloyalty']['program_name'];
				$loyalty['Smsloyalty']['group_id'] = $this->data['Smsloyalty']['group_id'];
				$loyalty['Smsloyalty']['startdate'] = date('Y-m-d',strtotime($this->data['Smsloyalty']['start']));
				$loyalty['Smsloyalty']['enddate'] = date('Y-m-d',strtotime($this->data['Smsloyalty']['end']));
				$loyalty['Smsloyalty']['reachgoal'] = $this->data['Smsloyalty']['reachgoal'];
				$loyalty['Smsloyalty']['addpoints'] = $this->data['Smsloyalty']['addpoints'];
				$loyalty['Smsloyalty']['reachedatgoal'] = $this->data['Smsloyalty']['reachedatgoal'];
				$loyalty['Smsloyalty']['checkstatus'] = $this->data['Smsloyalty']['checkstatus'];
				$loyalty['Smsloyalty']['codestatus'] = $this->data['Smsloyalty']['codestatus'];
				if(isset($this->data['Smsloyalty']['type'])){
					$loyalty['Smsloyalty']['type'] = $this->data['Smsloyalty']['type'];
					$loyalty['Smsloyalty']['image'] = $image_arr;
				}else{
					$loyalty['Smsloyalty']['type'] = 1;
				}
				if(isset($this->data['Smsloyalty']['notify_punch_code'])){
					$loyalty['Smsloyalty']['notify_punch_code'] = 1;
				}else{
					$loyalty['Smsloyalty']['notify_punch_code'] = 0;
				}
				if(isset($this->data['Smsloyalty']['my_email_address'])){
					$loyalty['Smsloyalty']['my_email_address'] = 1;
				}else{
					$loyalty['Smsloyalty']['my_email_address'] = 0;
				}
				if(isset($this->data['Smsloyalty']['email_address'])){
					$loyalty['Smsloyalty']['email_address'] = 1;
					$loyalty['Smsloyalty']['email_address_input'] = $this->data['Smsloyalty']['email_address_input'];
				}else{
					$loyalty['Smsloyalty']['email_address'] = 0;
				}
				if(isset($this->data['Smsloyalty']['mobile_number'])){
					$loyalty['Smsloyalty']['mobile_number'] = 1;
					$loyalty['Smsloyalty']['mobile_number_input'] = $this->data['Smsloyalty']['mobile_number_input'];
				}else{
					$loyalty['Smsloyalty']['mobile_number'] = 0;
				}
				if($this->Smsloyalty->save($loyalty)){
					$this->Session->setFlash('Loyalty program has been updated.'); 				
					$this->redirect(array('controller' =>'smsloyalty', 'action'=>'index'));
				}else{
					$this->Session->setFlash('Loyalty program has not been updated.please try again.'); 	
				}
			}
		}else{
			$this->Session->setFlash('You need to login first'); 				
			$this->redirect(array('controller' =>'users', 'action'=>'login'));
		}

	}
	function view($id = null) {
		$this->layout='admin_new_layout';
		if($id > 0){
			if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Loyalty program', true));
			$this->redirect(array('action' => 'index'));
			}
			app::import('Model','Smsloyalty');
			$this->Smsloyalty = new Smsloyalty();
			$loyalty_arr= $this->Smsloyalty->find('first', array('conditions'=>array('Smsloyalty.id'=>$id)));
			$this->set('loyalty',$loyalty_arr);
		}else{
			$this->Session->setFlash('Please select loyalty program'); 				
			$this->redirect(array('controller' =>'smsloyalty', 'action'=>'index'));
		}
	}
	function coupancode($digits){
		$this->autoRender = false;
		srand ((double) microtime() * 10000000);
		$input = array("A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
		//$input = array("0","1","2","3","4","5","6","7","8","9");
		$random_generator="";// Initialize the string to store random numbers
		for($i=1;$i<$digits+1;$i++)	{ 
			// Loop the number of times of required digits

			if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
				$rand_index = array_rand($input);
				$random_generator .=$input[$rand_index]; // One char is added
			}else{
					$random_generator .=rand(1,7); // one number is added
			}
		} // end of for loop
		return $random_generator;
	} // end of function
	
	function delete($id = null){
		$this->autoRender = false;
		if($id > 0){
			if ($this->Smsloyalty->delete($id)) {
				$this->Session->setFlash(__('Loyalty program deleted', true));
			}else{
				$this->Session->setFlash(__('Loyalty program not deleted', true));
			}
			$this->redirect(array('controller' =>'smsloyalty', 'action'=>'index'));
		}else{
			$this->Session->setFlash('Please select loyalty program'); 				
			$this->redirect(array('controller' =>'smsloyalty', 'action'=>'index'));
		}
	}
}	