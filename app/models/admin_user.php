<?php
class AdminUser extends AppModel {

	var $name = 'AdminUser';
	
	function beforeSave()
		{
		if(isset($this->data['AdminUser']['password']))
			{
				$this->data['AdminUser']['password']=md5($this->data['AdminUser']['password']);
			}
	 /*if($this->hasAndBelongsToMany != array()) {
				$this->correctHABTMdata();
			}*/	
		return true;
		}

}
?>