<?php
class PackagesController extends AppController {
	var $name = 'Packages';
	var $uses=array('Package','MonthlyPackage','MonthlyNumberPackage');
	function admin_index() {
		$this->Package->recursive = 0;
		app::import('Model','Package');
		$this->Package = new Package();
		$this->set('packages', $this->paginate('Package'));
	}
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid package', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('package', $this->Package->read(null, $id));
	}
	function admin_add() {
		if (!empty($this->data)) {
			$this->Package->create();
			if ($this->Package->save($this->data)) {
				$this->Session->setFlash(__('The package has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The package could not be saved. Please, try again.', true));
			}
		}
	}
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid package', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Package->save($this->data)) {
				$this->Session->setFlash(__('The package has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The package could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Package->read(null, $id);
		}
	}
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for package', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Package->delete($id)) {
			$this->Session->setFlash(__('Package deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Package was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	function admin_monthlypackage() {
		$this->MonthlyPackage->recursive = 0;
		app::import('Model','MonthlyPackage');
		$this->MonthlyPackage = new MonthlyPackage();
		$this->set('packagesdata', $this->paginate('MonthlyPackage'));
	}
	function admin_addmonthlypackage() {
		if (!empty($this->data)) {
			app::import('Model','MonthlyPackage');
			$this->MonthlyPackage = new MonthlyPackage();
			$this->MonthlyPackage->create();
			app::import('Model','MonthlyPackage');
		    $this->MonthlyPackage = new MonthlyPackage();
			if(!empty($this->data['Package']['product_id'])){
				$this->data['MonthlyPackage']['product_id']=$this->data['Package']['product_id'];
			}else{
				$this->data['MonthlyPackage']['product_id']=' ';
			}
			$this->data['MonthlyPackage']['package_name']=$this->data['Package']['package_name'];
			$this->data['MonthlyPackage']['amount']=$this->data['Package']['amount'];
			$this->data['MonthlyPackage']['text_messages_credit']=$this->data['Package']['text_messages_credit'];
			$this->data['MonthlyPackage']['voice_messages_credit']=$this->data['Package']['voice_messages_credit'];
			$this->data['MonthlyPackage']['status']=$this->data['Package']['status'];
                        $this->data['MonthlyPackage']['user_country']=$this->data['Package']['user_country'];
			if ($this->MonthlyPackage->save($this->data)) {
				$this->Session->setFlash(__('The monthly package has been saved', true));
				$this->redirect(array('action' => 'monthlypackage'));
			} else {
				$this->Session->setFlash(__('The monthly package could not be saved. Please, try again.', true));
			}
		}
	}
	function admin_editmonthlypackage($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid monthly package', true));
			$this->redirect(array('action' => 'monthlypackage'));
		}
		if (!empty($this->data)) {
			if ($this->MonthlyPackage->save($this->data)) {
				$this->Session->setFlash(__('The monthly package has been saved', true));
				$this->redirect(array('action' => 'monthlypackage'));
			} else {
				$this->Session->setFlash(__('The monthly package could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MonthlyPackage->read(null, $id);
		}
	}
	function admin_monthlydelete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for monthly package', true));
			$this->redirect(array('action'=>'monthlypackage'));
		}
		if ($this->MonthlyPackage->delete($id)) {
			$this->Session->setFlash(__('Monthly package deleted', true));
			$this->redirect(array('action'=>'monthlypackage'));
		}
		$this->Session->setFlash(__('Monthly package was not deleted', true));
		$this->redirect(array('action' => 'monthlypackage'));
	}
	function admin_monthlynumberpackage() {
		$this->MonthlyNumberPackage->recursive = 0;
		app::import('Model','MonthlyNumberPackage');
		$this->MonthlyNumberPackage = new MonthlyNumberPackage();
		$this->set('packagesdata', $this->paginate('MonthlyNumberPackage'));
	}
	function admin_addmonthlynumberpackage() {
		if (!empty($this->data)) {
			app::import('Model','MonthlyNumberPackage');
			$this->MonthlyNumberPackage = new MonthlyNumberPackage();
			$this->MonthlyNumberPackage->create();
			app::import('Model','MonthlyNumberPackage');
		        $this->MonthlyNumberPackage = new MonthlyNumberPackage();

                        if(!empty($this->data['Package']['plan'])){
				$this->data['MonthlyNumberPackage']['plan']=$this->data['Package']['plan'];
			}else{
				$this->data['MonthlyNumberPackage']['plan']=' ';
			}

			$this->data['MonthlyNumberPackage']['package_name']=$this->data['Package']['package_name'];
			$this->data['MonthlyNumberPackage']['amount']=$this->data['Package']['amount'];
			$this->data['MonthlyNumberPackage']['total_secondary_numbers']=$this->data['Package']['total_secondary_numbers'];
			$this->data['MonthlyNumberPackage']['status']=$this->data['Package']['status'];
			$this->data['MonthlyNumberPackage']['country']=$this->data['Package']['user_country'];
			//$this->data['MonthlyNumberPackage']['created']=date('Y-m-d H:i:s');
			if ($this->MonthlyNumberPackage->save($this->data)) {
				$this->Session->setFlash(__('The monthly number package has been saved', true));
				$this->redirect(array('action' => 'monthlynumberpackage'));
			} else {
				$this->Session->setFlash(__('The monthly number package could not be saved. Please, try again.', true));
			}
		}
	}
	function admin_editmonthlynumberpackage($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid monthly number package', true));
			$this->redirect(array('action' => 'monthlynumberpackage'));
		}
		if (!empty($this->data)) {
			if ($this->MonthlyNumberPackage->save($this->data)) {
				$this->Session->setFlash(__('The monthly number package has been saved', true));
				$this->redirect(array('action' => 'monthlynumberpackage'));
			} else {
				$this->Session->setFlash(__('The monthly number package could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MonthlyNumberPackage->read(null, $id);
		}
	}
	function admin_monthlynumberdelete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for monthly package', true));
			$this->redirect(array('action'=>'monthlynumberpackage'));
		}
		if ($this->MonthlyNumberPackage->delete($id)) {
			$this->Session->setFlash(__('Monthly number package deleted', true));
			$this->redirect(array('action'=>'monthlynumberpackage'));
		}
		$this->Session->setFlash(__('Monthly number package was not deleted', true));
		$this->redirect(array('action' => 'monthlynumberpackage'));
	}
}?>