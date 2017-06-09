<?php

class User extends AppModel {

	var $name = 'User';
        var $recursive = -1;

	var $validate = array(

		'first_name' => array(

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter Your First Name',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

		'username' => array(

			'isUnique' => array(

				'rule' => array('isUnique'),

				'message' => 'This username has already been taken',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter Username',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

		'email' => array(

			'isUnique' => array(

				'rule' => array('isUnique'),

				'message' => 'This Email has already been taken',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'email' => array(

				'rule' => array('email'),

				'message' => 'Enter a valid Email Address',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter Email Address',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

		'passwrd' => array(

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter Password',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

		'confirm_password' => array(

			'confirm_password' => array(

				'rule' => array('confirm_password'),

				'message' => 'Password and confirm password are not the same',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter Confirm Password',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

		'userCode' => array(

			'checkCaptcha' => array(

				'rule' => array('checkCaptcha'),

				'message' => 'Enter Correct Code From Image',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter Code From Image',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),
		'phone' => array(

			'isUnique' => array(

				'rule' => array('isUnique'),

				'message' => 'This phone number already exists',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'phone' => array(

				'rule' => 'numeric',

				'message' => 'Enter a valid phone, all numeric digits',

				'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter phone',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

		

	);

	

	var $validateUserlogin = array(

		'usrname' => array(

				'notempty' => array(

					'rule' => array('notempty'),

					'message' => 'Enter username',

					//'allowEmpty' => false,

					//'required' => false,

					//'last' => false, // Stop validation after this rule

					//'on' => 'create', // Limit validation to 'create' or 'update' operations

				),

			),


		

		'passwrd' => array(

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter password',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

	

	);

	

	var $validateChangePaypalEmail = array(

		'paypal_email' => array(

				'email' => array(

					'rule' => array('email'),

					'message' => 'Enter valid Paypal Email',

					//'allowEmpty' => false,

					//'required' => false,

					//'last' => false, // Stop validation after this rule

					//'on' => 'create', // Limit validation to 'create' or 'update' operations

				),

				'notempty' => array(

					'rule' => array('notempty'),

					'message' => 'Enter Paypal Email',

					//'allowEmpty' => false,

					//'required' => false,

					//'last' => false, // Stop validation after this rule

					//'on' => 'create', // Limit validation to 'create' or 'update' operations

				),

			),

		

		

	

	);

	

	var $validateChangePassword = array(

		'old_password' => array(

				'notempty' => array(

					'rule' => array('notempty'),

					'message' => 'Enter old Passsword',

					//'allowEmpty' => false,

					//'required' => false,

					//'last' => false, // Stop validation after this rule

					//'on' => 'create', // Limit validation to 'create' or 'update' operations

				),

			),

		

		'new_password' => array(

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter New Password',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

		),

		'confirm_password' => array(

			'compare_password' => array(

				'rule' => array('compare_password'),

				'message' => 'New Password and Confirm Password should be same',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			'notempty' => array(

				'rule' => array('notempty'),

				'message' => 'Enter Confirm Password',

				//'allowEmpty' => false,

				//'required' => false,

				//'last' => false, // Stop validation after this rule

				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),

			

		),

	

	);

	

	function compare_password()

	{ //pr($this->data);exit;

	    if(trim($this->data['User']['new_password'])== trim($this->data['User']['confirm_password']))

			return true;

		else

			return false;

    }

	

	function confirm_password()

	{ 

	    if(trim($this->data['User']['passwrd'])== trim($this->data['User']['confirm_password']))

			return true;

		else

			return false;

    }

	

	function checkCaptcha(){

		$userCode = $this->data['User']['userCode'];

		if (!empty($_SESSION[CAPTCHA_SESSION_ID]) && $userCode == $_SESSION[CAPTCHA_SESSION_ID]) {

            // clear to prevent re-use

            unset($_SESSION[CAPTCHA_SESSION_ID]);

            

            return true;

        }

        else return false;

	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed



	var $hasMany = array(

		'Contact' => array(

			'className' => 'Contact',

			'foreignKey' => 'user_id',

			'dependent' => false,

			'conditions' => '',

			'fields' => '',

			'order' => '',

			'limit' => '',

			'offset' => '',

			'exclusive' => '',

			'finderQuery' => '',

			'counterQuery' => ''

		),

		'Log' => array(

			'className' => 'Log',

			'foreignKey' => 'user_id',

			'dependent' => false,

			'conditions' => '',

			'fields' => '',

			'order' => '',

			'limit' => '',

			'offset' => '',

			'exclusive' => '',

			'finderQuery' => '',

			'counterQuery' => ''

		),

		'UserNumber' => array(	
		'className' => 'UserNumber',	
		'foreignKey' => 'user_id',	
		'dependent' => false,		
		'conditions' => '',		
		'fields' => '',		
		'order' => '',	
		'limit' => '',		
		'offset' => '',		
		'exclusive' => '',	
		'finderQuery' => '',	
		'counterQuery' => ''
		),

		'Referral' => array(

			'className' => 'Referral',

			'foreignKey' => 'user_id',

			'dependent' => false,

			'conditions' => '',

			'fields' => '',

			'order' => '',

			'limit' => '',

			'offset' => '',

			'exclusive' => '',

			'finderQuery' => '',

			'counterQuery' => ''

		)

	);

var $belongsTo = array(

  'MonthlyPackage' => array(

   'className' => 'MonthlyPackage',

   'foreignKey' => 'package',

   'conditions' => '',

   'fields' => '',

   'order' => ''

)

  );

	

	function beforeUpdate(){

		 if (empty($this->data['User']['assigned_number']) || $this->data['Event']['assigned_number'] == 0) {

			 unset($this->data['User']['assigned_number']);

		 }

		 return true;

	

	}



}
