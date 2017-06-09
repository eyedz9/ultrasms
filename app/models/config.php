<?php
class Config extends AppModel {
	var $name = 'Config';
	var $validate = array(
		'registration_charge' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Enter Registration Charge',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'free_sms' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Enter Free Sms',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'free_voice' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Free Voice',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'referral_amount' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Enter Referral Amount',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
