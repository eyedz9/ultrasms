<?php

class Birthday extends AppModel {

	var $name = 'Birthday';

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

