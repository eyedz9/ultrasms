<?php
class MobilePagesController extends AppController {
	var $name = 'MobilePages';
	var $components = array('Cookie','Email','Qr');
	var $helpers = array('Javascript', 'Time', 'Form', 'Html', 'Session', 'Fck');
	var $layout="default";
	function pagedetails(){
		if($this->Session->check('User')){
			$this->layout='admin_new_layout';
			$user_id=$this->Session->read('User.id');	
			$phoneno = $this->MobilePage->find('all',array('conditions' => array('MobilePage.user_id' =>$user_id),'order' =>array('MobilePage.id' => 'asc')));
			$this->set('mobilespages', $phoneno);
		}else{
			$this->redirect('/users/login');
		}

	}
	function upload(){
		$this->layout=null;
		$filename=$_FILES['data']['name']['image'];
		$type=$_FILES['data']['type']['image'];
		$tmp_name=$_FILES['data']['tmp_name']['image'];
		if(!empty($filename)){
			$time=time();
			move_uploaded_file($tmp_name,"uploadimages/".$time.$filename);
			//$this->Session->setFlash(__('The File has been uploaded', true));
			$siteurl=SITE_URL;
			$imagepath=$siteurl.'/uploadimages/'.$time.$filename;
			$this->set('imagename',$imagepath);
		}
	}
	function add(){
		if($this->Session->check('User')){
			$this->layout='admin_new_layout';
			$user_id=$this->Session->read('User.id');
			if(!empty($this->data)){
				$countpage = $this->MobilePage->find('count',array('conditions' => array('MobilePage.user_id' =>$user_id)));
				//echo $countpage;
				$pagelimit=MOBILE_PAGE_LIMIT;
				if($countpage < $pagelimit){
					app::import('Model','MobilePage');
					$this->MobilePage = new MobilePage();
					$filename=$this->data['MobilePage']['header_logo']['name'];
					$type=$this->data['MobilePage']['header_logo']['type'];
					$tmp_name=$this->data['MobilePage']['header_logo']['tmp_name'];
					$time = time();
					if(!empty($filename)){
						move_uploaded_file($tmp_name,"pages/".$time.$filename);
						$this->data['MobilePage']['header_logo']=$time.$filename;
					}else{
						$this->data['MobilePage']['header_logo']='';
					}
					$this->data['MobilePage']['user_id']=$user_id;
					$this->data['MobilePage']['title']=$this->data['MobilePage']['title'];
					$this->data['MobilePage']['description']=$this->data['MobilePage']['description'];
					$this->data['MobilePage']['header_color']=$this->data['MobilePage']['header_color'];
					$this->data['MobilePage']['headerfont_color']=$this->data['MobilePage']['headerfont_color'];
					$this->data['MobilePage']['bodybg_color']=$this->data['MobilePage']['bodybg_color'];
					$this->data['MobilePage']['footertext_color']=$this->data['MobilePage']['footertext_color'];
					$this->data['MobilePage']['footertext']=$this->data['MobilePage']['footertext'];
					$this->data['MobilePage']['footer_color']=$this->data['MobilePage']['footer_color'];
					$this->data['MobilePage']['map_url']=$this->data['MobilePage']['mapurl'];
					$this->MobilePage->save($this->data);
					$this->Session->setFlash(__('The MobilePage has been saved', true));
					$this->redirect(array('action' => 'pagedetails'));
				}else{
					$this->Session->setFlash(__('You are already at the limit to create a page', true));
					$this->redirect(array('action' => 'pagedetails'));
				}
			}
		}else{
			$this->redirect('/users/login');
		}
	}
	function edit($id = null) {
		if($this->Session->check('User')){
			$this->layout='admin_new_layout';
			app::import('Model','MobilePage');
			$this->MobilePage = new MobilePage();
			$user_id=$this->Session->read('User.id');	
			$page = $this->MobilePage->find('first',array('conditions' => array('MobilePage.id' =>$id,'MobilePage.user_id' =>$user_id),'order' =>array('MobilePage.id' => 'asc')));
			$this->set('pagedetails', $page);
			if (!empty($this->data)) {
				$time = time();
				$pagedetails = $this->MobilePage->find('first',array('conditions' => array('MobilePage.id' =>$this->data['MobilePage']['id'],'MobilePage.user_id' =>$user_id),'order' =>array('MobilePage.id' => 'asc')));
				$filename=$this->data['MobilePage']['header_logo']['name'];
				$type=$this->data['MobilePage']['header_logo']['type'];
				$tmp_name=$this->data['MobilePage']['header_logo']['tmp_name'];
				if(!empty($filename)){
					move_uploaded_file($tmp_name,"pages/".$time.$filename);
					$this->data['MobilePage']['header_logo']=$time.$filename;
				}else{
					$this->data['MobilePage']['header_logo']='';
				}
				$this->data['MobilePage']['id']=$this->data['MobilePage']['id'];
				$this->data['MobilePage']['user_id']=$user_id;
				$this->data['MobilePage']['title']=$this->data['MobilePage']['title'];
				$this->data['MobilePage']['description']=$this->data['MobilePage']['description'];
				$this->data['MobilePage']['header_color']=$this->data['MobilePage']['header_color'];
				$this->data['MobilePage']['headerfont_color']=$this->data['MobilePage']['headerfont_color'];
				$this->data['MobilePage']['bodybg_color']=$this->data['MobilePage']['bodybg_color'];
				$this->data['MobilePage']['footertext_color']=$this->data['MobilePage']['footertext_color'];
				$this->data['MobilePage']['footertext']=$this->data['MobilePage']['footertext'];
				$this->data['MobilePage']['footer_color']=$this->data['MobilePage']['footer_color'];
				$this->data['MobilePage']['map_url']=$this->data['MobilePage']['mapurl'];
				$this->MobilePage->save($this->data);
				$this->Session->setFlash(__('The MobilePage has been edited', true));
				$this->redirect(array('action' => 'pagedetails'));	
			}
		}else{
			$this->redirect('/users/login');
		}
	}
	function delete($id = null) { 
		app::import('Model','MobilePage');
		$this->MobilePage = new MobilePage();
		if ($this->MobilePage->delete($id)) {
			$this->Session->setFlash(__('MobilePage deleted', true));
			$this->redirect(array('action'=>'pagedetails'));
		}
	}
	function view($id){
		if($this->Session->check('User')){
			app::import('Model','Qrcod');
			$this->Qrcod = new Qrcod();
			app::import('Model','MobilePage');
			$this->MobilePage = new MobilePage();
			$user_id=$this->Session->read('User.id');	
			$viewpage = $this->MobilePage->find('first',array('conditions' => array('MobilePage.id' =>$id,'MobilePage.user_id' =>$user_id),'order' =>array('MobilePage.id' => 'asc')));
			$this->set('pageview', $viewpage);
		}else{
			$this->redirect('/users/login');
		}
	}
	function qrcodeview($id=null){
		if($this->Session->check('User')){
			$this->layout='admin_new_layout';
			$user_id=$this->Session->read('User.id');	
			app::import('Model','MobilePage');
			$this->MobilePage = new MobilePage();
			$viewpage = $this->MobilePage->find('first',array('conditions' => array('MobilePage.id' =>$id,'MobilePage.user_id' =>$user_id)));
			if(!empty($viewpage)){			$base64 = base64_encode($viewpage['MobilePage']['id']);
				$changeid = str_replace('=' ,'', $base64);
				$pageid= str_replace('+' ,'', $changeid );
				$url = SITE_URL;
				$test = $pageid;
				$page = $url.'/lp.php?id='.$pageid;
				$p = 'pagesqr/'.$test.'-large.png';
				copy('test/test.png',$p);
				QRcode::png($page, 'pagesqr/'.$test.'-large.png', 'L', 8, 2);			$this->set('qrimage1',$url.'/pagesqr/'.$test.'-large.png');
			}
		}else{
			$this->redirect('/users/login');
		}
	}
}