<?php 
class Kiosks extends AppModel {

	var $name = 'Kiosks';
	var $belongsTo = array(	 
	'Smsloyalty' => array(	
	'className' => 'Smsloyalty',	
	'foreignKey' => 'loyalty_id',	
	'conditions' => '',	
	'fields' => '',	  
	'order' => ''	
	),'User' => array(	
	'className' => 'User',	
	'foreignKey' => 'user_id',	
	'conditions' => '',	
	'fields' => '',	  
	'order' => ''	
	));
}

 ?>