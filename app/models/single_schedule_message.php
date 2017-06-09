<?php

class SingleScheduleMessage extends AppModel {

	var $name = 'SingleScheduleMessage';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

 
 var $belongsTo = array(
	  'ScheduleMessage' => array(
	  'className' => 'ScheduleMessage',
	  'foreignKey' => 'schedule_sms_id',
	  'conditions' => '',
	  'fields' => '',
	  'order' => ''
	  ),
	  'Contact' => array(
	  'className' => 'Contact',
	  'foreignKey' => 'contact_id',
	  'conditions' => '',
	  'fields' => '',
	  'order' => ''
	  )
 );



	


}
