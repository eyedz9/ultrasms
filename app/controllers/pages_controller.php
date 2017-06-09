<?php
class PagesController extends AppController {
	var $name = 'Pages';
	var $components = array('Cookie','Email');
    var $layout="default";
	var $uses = array();

	function pricingplans(){
		$this->layout= 'default';	

                $country=COUNTRY;
	
		app::import('Model','MonthlyPackage');
		$this->MonthlyPackage = new MonthlyPackage();
	        //$monthlydetails = $this->MonthlyPackage->find('all',array('conditions' => array('MonthlyPackage.status'=>1),'order' =>array('MonthlyPackage.amount' => 'asc')));
$monthlydetails = $this->MonthlyPackage->find('all',array('conditions' => array('MonthlyPackage.status'=>1,'MonthlyPackage.user_country'=>''.trim($country).''),'order' =>array('MonthlyPackage.amount' => 'asc')));
		$this->set('monthlydetails',$monthlydetails);

		app::import('Model','Package');
		$this->Package = new Package();
		//$Packagedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'text'),'order' =>array('Package.amount' => 'asc')));
$Packagedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'text','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
		$this->set('Packagedetails',$Packagedetails);
		//$Packagevoicedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'voice'),'order' =>array('Package.amount' => 'asc')));
$Packagevoicedetails = $this->Package->find('all',array('conditions' => array('Package.status'=>1,'Package.type'=>'voice','Package.user_country'=>''.trim($country).''),'order' =>array('Package.amount' => 'asc')));
		$this->set('Packagevoicedetails',$Packagevoicedetails);
	}
	
	function page2(){
	}
	function page3(){
	}   
	function page4(){
	} 
	function features(){
		//$this->layout= 'admin_new_layout';	
	}
        function industries(){
	}
}?>