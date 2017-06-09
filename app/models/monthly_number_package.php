<?php



class MonthlyNumberPackage extends AppModel {

	var $name = 'MonthlyNumberPackage';

        var $validate = array(
		'package_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Enter a Package Name',
			),
		),
		'amount' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Enter Amount for this Package',
			),
		),
		'total_secondary_numbers' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Enter Total Secondary Numbers',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		
	);

}
