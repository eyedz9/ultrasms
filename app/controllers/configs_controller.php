<?php
class ConfigsController extends AppController {
	var $name = 'Configs';
	function admin_index() {
		$this->Config->recursive = 0;
		$this->set('configs', $this->paginate());
		$configdetails=$this->Config->find('first', array('conditions' => array('Config.id' =>'1')));
		$this->set('configdetails', $configdetails);
	}
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid config', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('config', $this->Config->read(null, $id));
	}	
	function admin_add() {
		if (!empty($this->data)) {
			$this->Config->create();
			if ($this->Config->save($this->data)) {
				$this->Session->setFlash(__('The config has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The config could not be saved. Please, try again.', true));
			}
		}
	}
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid config', true));
			$this->redirect(array('action' => 'index'));
		}
	    app::import('Model','User');		
	    $this->User=new User();
		$api_type=$this->Config->find('first', array('conditions' => array('Config.id' => $id)));
		$someone=$this->User->find('count', array('conditions' => array('User.api_type' => $api_type['Config']['api_type'],'User.assigned_number != '=>0)));
		$this->set('apicount',$someone);
		$this->set('api_type',$api_type['Config']['api_type']);
		if (!empty($this->data)) {
		
			if($this->data['Config']['upload_logo']['name'] !=''){
				$image=str_replace(' ','_',time().$this->data['Config']['upload_logo']['name']);
				if(move_uploaded_file($this->data['Config']['upload_logo']['tmp_name'],"img/".$image)){
					$this->data['Config']['logo'] = $image;
				}				
			}
			if ($this->Config->save($this->data)) {
				$this->Session->setFlash(__('The config has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The config could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Config->read(null, $id);
		}
	}
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for config', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Config->delete($id)) {
			$this->Session->setFlash(__('Config deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Config was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}