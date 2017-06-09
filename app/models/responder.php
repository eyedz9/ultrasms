<?php

class Responder extends AppModel {

	var $name = 'Responder';

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

