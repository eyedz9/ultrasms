<?php

class ScheduleMessageGroup extends AppModel {

	var $name = 'ScheduleMessageGroup';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
var $belongsTo = array(
	  'ScheduleMessage' => array(
	  'className' => 'ScheduleMessage',
	  'foreignKey' => 'schedule_sms_id',
	  'conditions' => '',
	  'fields' => '',
	  'order' => ''
	  ),
	  'Group' => array(
	  'className' => 'Group',
	  'foreignKey' => 'group_id',
	  'conditions' => '',
	  'fields' => '',
	  'order' => ''
	  )
 );



	


}
