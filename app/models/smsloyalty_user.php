<?php

class SmsloyaltyUser extends AppModel {

	var $name = 'SmsloyaltyUser';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

var $belongsTo = array(
  'User' => array(
   'className' => 'User',
   'foreignKey' => 'user_id',
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
  ),
  'Smsloyalty' => array(
   'className' => 'Smsloyalty',
   'foreignKey' => 'sms_loyalty_id',
   'conditions' => '',
   'fields' => '',
   'order' => ''
  )
 );

	


}
