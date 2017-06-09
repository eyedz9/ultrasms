<?php

class GroupSmsBlast extends AppModel {

	var $name = 'GroupSmsBlast';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

var $hasMany = array(
      'Log' => array(
	  'className' => 'Log',
	  'foreignKey' => 'group_sms_id',
	  'conditions' => '',
	  'fields' => '',
	  'order' => ''
	  )
 );
 
 var $belongsTo = array(
 'Group' => array(
   'className' => 'Group',
   'foreignKey' => 'group_id',
   'conditions' => '',
   'fields' => '',
   'order' => ''
  
  )
  );

	


}
