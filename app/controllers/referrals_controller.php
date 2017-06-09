<?php
class ReferralsController extends AppController {
	var $name = 'Referrals';	
	function index() {
		$this->layout= 'admin_new_layout';
		$this->Referral->recursive = 0;
		$this->paginate = array('Referral' => array('order' => 'Referral.created DESC'));
		$this->set('referrals', $this->paginate('Referral', array('Referral.referred_by' =>$this->getLoggedInUserId(), 'Referral.account_activated' =>1)));
	}
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid referral', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('referral', $this->Referral->read(null, $id));
	}
	function admin_index(){
		$this->Referral->recursive = 0;
		$this->paginate = array('Referral' => array('group' => 'Referral.referred_by','limit' =>20));
		$referrals = $this->paginate('Referral', array('Referral.paid_status' =>0, 'Referral.account_activated' =>1,"Referral.created <" => date('Y-m-d',strtotime( "next saturday" ))));
		$temp = array();
		foreach($referrals as $referral){
			$record = $this->Referral->find('all', array('conditions' => array('Referral.referred_by' => $referral['Referral']['referred_by'],'Referral.paid_status' =>0, 'Referral.account_activated' =>1,"Referral.created <" => date('Y-m-d',strtotime( "next saturday" ))), 'fields' => "SUM(amount) as total"));
			$referral['RefferedBy']['totalAmt'] = $record['0']['0']['total'];
			$temp[] = $referral; 
		}
		$this->set('referrals', $temp);
	}
	function admin_mark($uid,$status){
		$this->Referral->updateAll(array('Referral.paid_status' => $status), array('Referral.referred_by' => $uid));
		$this->Session->setFlash(__('Marked as paid/unpaid successfully', true));
		$this->redirect(array('action' => 'index'));
	}
	function admin_details($uid){
		$this->layout = null;
		$records = $this->Referral->find('all', array('conditions' => array('Referral.referred_by' => $uid,'Referral.paid_status' =>0, 'Referral.account_activated' =>1)));
		$this->set('referrals', $records);
	}
}