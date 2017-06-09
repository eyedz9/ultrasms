<?php
class Smsloyalty extends AppModel {	
	var $name = 'Smsloyalty';
	var $belongsTo = array(	 
	'Group' => array(	
	'className' => 'Group',	
	'foreignKey' => 'group_id',	
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

