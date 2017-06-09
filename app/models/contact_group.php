<?php



class ContactGroup extends AppModel {



	var $name = 'ContactGroup';


	//The Associations below have been created with all possible keys, those that are not needed can be removed



var $belongsTo = array(

  'Group' => array(

   'className' => 'Group',

   'foreignKey' => 'group_id',

   'conditions' => '',

   'fields' => '',

   'order' => ''

  ),

  ' Contact' => array(

   'className' => 'Contact',

   'foreignKey' => 'contact_id',

   'conditions' => '',

   'fields' => '',

   'order' => ''

  )

 );



	





}

