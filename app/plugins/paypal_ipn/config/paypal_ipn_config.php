<?php
/************
  * Use these settings to set defaults for the Paypal Helper class.
  * The PaypalHelper class will help you create paynow, subscribe, donate, or addtocart buttons for you.
  * 
  * All these options can be set on the fly as well within the helper
  */
  
class PaypalIpnConfig {

  /************
    * Each settings key coresponds to the Paypal API.  Review www.paypal.com for more. 
    */
  var $settings = array(
    'business' => 'live_email@paypal.com', //'live_email@paypal.com', //Your Paypal email account
    'server' => 'https://www.paypal.com', //Main paypal server.
      'notify_url' => 'http://www.ultrasmsscript.com/paypal_ipn/process', //'http://www.yoursite.com/paypal_ipn/process',
	'return' => 'http://www.ultrasmsscript.com/users/thank_you', //'http://www.yoursite.com/paypal_ipn/process',
	'cancel_return' => 'http://www.ultrasmsscript.com.com', //'http://www.yoursite.com/paypal_ipn/process', //Notify_url... set this to the process path of your paypal_ipn::instant_payment_notification::process action
    'currency_code' => 'USD', //Currency
	'cbt' => 'Continue >>', 
    'lc' => 'US', //Locality
    'item_name' => 'UltraSMSScript.com :: Membership', //Default item name.
   // 'amount' => '15.00' //Default item amount.
  );
  
  /***********
    * Test settings to test with using a sandbox paypal account.
    */
  var $testSettings = array(
    'business' => 'ashok_1305095475_biz@trigma.com', //'sandbox_email@paypal.com',
    'server' => 'https://www.sandbox.paypal.com',
    'notify_url' => 'http://www.ultrasmsscript.com/paypal_ipn/process', //'http://www.yoursite.com/paypal_ipn/process',
	'return' => 'http://www.ultrasmsscript.com/users/thank_you', //'http://www.yoursite.com/paypal_ipn/process',
	'cancel_return' => 'http://www.ultrasmsscript.com',
	'cbt' => 'Continue >>', 
    'currency_code' => 'USD',
    'lc' => 'US',
	'rm'=> "2",
    //'item_name' => 'Paypal_IPN',
    //'amount' => '15.00'
  );

}
?>
