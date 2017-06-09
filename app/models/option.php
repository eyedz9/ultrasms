<?php

class Option extends AppModel {

	var $name = 'Option';
	
	
	var $belongsTo = array(
	  'Question' => array(
	  'className' => 'Question',
	  'foreignKey' => 'question_id',
	  'conditions' => '',
	  'fields' => '',
	  'order' => ''
	  )

 );




}

