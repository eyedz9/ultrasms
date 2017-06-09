<?php
class Referral extends AppModel {
	var $name = 'Referral';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'RefferedBy' => array(
			'className' => 'User',
			'foreignKey' => 'referred_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

	

}
