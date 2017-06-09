<?php

class Question extends AppModel {

	var $name = 'Question';
	
	var $hasMany = array(
	  'Option' => array(
	  'className' => 'Option',
	  'foreignKey' => 'question_id',
	  'conditions' => '',
	  'fields' => '',
	  'order' => ''
	  )

 );

}

