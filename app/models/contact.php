<?php
class Contact extends AppModel {
	var $name = 'Contact';
	var $validate = array(
		
		'phone_number' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Enter Phone Number',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	var $validatesendMsg = array(
		'message' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Enter Message',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		
		
	
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
