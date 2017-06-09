<?php
class DemoController extends AppController {
	public $name = 'Demo';
	public $components = array('Email','Plivo');
	public $uses = array();
	public $useModel = false;
    function index(){
		if($this->Session->check('User')){          
			$this->layout= 'admin_new_layout';
		}else{
			$this->redirect('/users/login');
		}
	}
}
?>
