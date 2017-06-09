<?php

class ContestSubscriber extends AppModel {

	var $name = 'ContestSubscriber';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(

  'Contest' => array(

   'className' => 'Contest',

   'foreignKey' => 'contest_id',

   'conditions' => '',

   'fields' => '',

   'order' => ''

  )

  

 );


}
