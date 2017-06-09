<?php
	$con = mysql_connect("localhost","DB_LOGIN","DB_PASSWORD");
	
        if (!$con){
	   die('Could not connect: ' . mysql_error());
	}
        
        mysql_select_db("DATABASE_NAME", $con); 


	//fetch data from configs	   
	  $configs = mysql_query("SELECT * FROM configs"); 
	  $configdata = mysql_fetch_array($configs); 
	
        //fetch data from paypals 
        //$paypals = mysql_query("SELECT * FROM paypals");
	//$paypalsdata = mysql_fetch_array($paypals); 
	//fetch data from database  table  

	$dbconfigs = mysql_query("SELECT * FROM dbconfigs");
	$dbconfigsdata = mysql_fetch_array($dbconfigs);
	

	define('SITEURL',$configdata['site_url']);

	define('NEXMO_KEY',$configdata['nexmo_key']);
	define('NEXMO_SECRET',$configdata['nexmo_secret']);

	define('TWILIO_ACCOUNTSID',$configdata['twilio_accountSid']);
	define('TWILIO_AUTH_TOKEN',$configdata['twilio_auth_token']);

        define('PLIVO_KEY',$configdata['plivo_key']);
	define('PLIVO_TOKEN',$configdata['plivo_token']);
        define('PLIVOAPP_ID',$configdata['plivoapp_id']);


	//define('PAYPAL_API_USERNAME',$paypalsdata['paypal_api_username']);
	//define('PAYPAL_API_PASSWORD',$paypalsdata['paypal_api_password']);
	//define('PAYPAL_API_SIGNATURE',$paypalsdata['paypal_api_signature']);

	define('DB_USERNAME',$dbconfigsdata['dbusername']);
	define('DB_NAME',$dbconfigsdata['dbname']);
	define('DB_PASSWORD',$dbconfigsdata['dbpassword']);
	  

?>