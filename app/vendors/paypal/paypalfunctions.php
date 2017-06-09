<?php
include('./database.php');
	/********************************************

	PayPal API Module

	 

	Defines all the global variables and the wrapper functions 

	

	********************************************/

	class Paypalrequest{

	

	

	



	/* An express checkout transaction starts with a token, that

	   identifies to PayPal your transaction

	   In this example, when the script sees a token, the script

	   knows that the buyer has already authorized payment through

	   paypal.  If no token was found, the action is to send the buyer

	   to PayPal to first authorize payment

	   */



	/*   

	'-------------------------------------------------------------------------------------------------------------------------------------------

	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.

	' Inputs:  

	'		paymentAmount:  	Total value of the shopping cart

	'		currencyCodeType: 	Currency code value the PayPal API

	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization

	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal

	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal

	'--------------------------------------------------------------------------------------------------------------------------------------------	

	*/

	



	var $PROXY_HOST = '127.0.0.1';

	var $PROXY_PORT = '808';



	var  $SandboxFlag = false;



	//'------------------------------------

	//' PayPal API Credentials

	//' Replace <API_USERNAME> with your API Username

	//' Replace <API_PASSWORD> with your API Password

	//' Replace <API_SIGNATURE> with your Signature

	//'------------------------------------

	/* var  $API_UserName="ranjit.oditi-facilitator_api1.gmail.com";

	var $API_Password="1366642630";

	var $API_Signature="Aqjb8iKIGpVI.ujzzaa7tjAHeUu8AlHi56AcuoyLfSr9yqyjztUkTSCB";*/
	
	var  $API_UserName=PAYPAL_API_USERNAME;

	var $API_Password=PAYPAL_API_PASSWORD;

	var $API_Signature=PAYPAL_API_SIGNATURE;
	

	
	




	// BN Code 	is only applicable for partners

	var  $sBNCode = "PP-ECWizard";

	

	

	/*	

	' Define the PayPal Redirect URLs.  

	' 	This is the URL that the buyer is first sent to do authorize payment with their paypal account

	' 	change the URL depending if you are testing on the sandbox or the live PayPal site

	'

	' For the sandbox, the URL is       https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=

	' For the live site, the URL is        https://www.paypal.com/webscr&cmd=_express-checkout&token=

	*/

	

	var $USE_PROXY = false;

	var $version="64";

	var $API_Endpoint;

	var $PAYPAL_URL;

  public function __construct(){

	$this->PROXY_HOST = '127.0.0.1';

	$this->PROXY_PORT = '808';



	$this->SandboxFlag = false;

	/* $this->API_UserName="ranjit.oditi-facilitator_api1.gmail.com";

	$this->API_Password="1366642630";

	$this->API_Signature="Aqjb8iKIGpVI.ujzzaa7tjAHeUu8AlHi56AcuoyLfSr9yqyjztUkTSCB"; */
	
	 $this->API_UserName=PAYPAL_API_USERNAME;

	$this->API_Password=PAYPAL_API_PASSWORD;

	$this->API_Signature=PAYPAL_API_SIGNATURE;
	
	
	



	// BN Code 	is only applicable for partners

	$this->sBNCode = "PP-ECWizard";

	

	if ($this->SandboxFlag == true) 

	{

		$this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";

		$this->PAYPAL_URL ="https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";

	}

	else

	{

		$this->API_Endpoint = "https://api-3t.paypal.com/nvp";

		$this->PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";

	}



	$this->USE_PROXY = false;

	$this->version="64";



	if (session_id() == "") 

		session_start();

		

    

  }

	



	public function CallShortcutExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL,$packageName) 

	{

	

	

//------------------------------------------------------------------------------------------------------------------------------------

		// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation



		$nvpstr="&AMT=". $paymentAmount;
				
		$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
		

	       $currencycode=PAYMENT_CURRENCY_CODE;
			
	       if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD')
              
              $symbol="$";
              else if($currencycode=='JPY')
              $symbol="¥";
	      else if($currencycode=='EUR')
              $symbol="€";
              else if($currencycode=='GBP')
              $symbol="£";
              else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK')
              $symbol="kr";
              else if($currencycode=='CHF')
              $symbol="CHF";
              else if($currencycode=='BRL')
              $symbol="R$";
              else if($currencycode=='PHP')
              $symbol="₱";
              else if($currencycode=='ILS')
              $symbol="₪";

		$nvpstr = $nvpstr . "&BILLINGAGREEMENTDESCRIPTION=".urlencode("".$packageName." Recurring Payment(".$symbol."".$paymentAmount." monthly)");

		$nvpstr = $nvpstr . "&BILLINGTYPE=RecurringPayments";

		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;

		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;

		$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCodeType;
		
		//$nvpstr = $nvpstr . "&SOLUTIONTYPE=Sole";
		
		//$nvpstr = $nvpstr . "&LANDINGPAGE=Billing";

		

		

		//echo $nvpstr;

		

	//exit();

		

		$_SESSION["currencyCodeType"] = $currencyCodeType;	  

		$_SESSION["PaymentType"] = $paymentType;



		//'--------------------------------------------------------------------------------------------------------------- 

		//' Make the API call to PayPal

		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  

		//' If an error occured, show the resulting errors

		//'---------------------------------------------------------------------------------------------------------------

	

		$resArray = $this->hash_call("SetExpressCheckout", $nvpstr);

		

		$ack = strtoupper($resArray["ACK"]);

		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")

		{

			$token = urldecode($resArray["TOKEN"]);

			$_SESSION['TOKEN']=$token;

		}



	    return $resArray;

	}



	/*   

	'-------------------------------------------------------------------------------------------------------------------------------------------

	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.

	' Inputs:  

	'		paymentAmount:  	Total value of the shopping cart

	'		currencyCodeType: 	Currency code value the PayPal API

	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization

	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal

	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal

	'		shipToName:		the Ship to name entered on the merchant's site

	'		shipToStreet:		the Ship to Street entered on the merchant's site

	'		shipToCity:			the Ship to City entered on the merchant's site

	'		shipToState:		the Ship to State entered on the merchant's site

	'		shipToCountryCode:	the Code for Ship to Country entered on the merchant's site

	'		shipToZip:			the Ship to ZipCode entered on the merchant's site

	'		shipToStreet2:		the Ship to Street2 entered on the merchant's site

	'		phoneNum:			the phoneNum  entered on the merchant's site

	'--------------------------------------------------------------------------------------------------------------------------------------------	

	*/

	public function CallMarkExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, 

									  $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState,

									  $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum

									) 

	{

		//------------------------------------------------------------------------------------------------------------------------------------

		// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation

		

		$nvpstr="&PAYMENTREQUEST_0_AMT=". $paymentAmount;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;

		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;

		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;

		$nvpstr = $nvpstr . "&ADDROVERRIDE=1";

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTONAME=" . $shipToName;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET=" . $shipToStreet;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET2=" . $shipToStreet2;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCITY=" . $shipToCity;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTATE=" . $shipToState;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=" . $shipToCountryCode;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOZIP=" . $shipToZip;

		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOPHONENUM=" . $phoneNum;

		

		$_SESSION["currencyCodeType"] = $currencyCodeType;	  

		$_SESSION["PaymentType"] = $paymentType;



		//'--------------------------------------------------------------------------------------------------------------- 

		//' Make the API call to PayPal

		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  

		//' If an error occured, show the resulting errors

		//'---------------------------------------------------------------------------------------------------------------

	    $resArray=$this->hash_call("SetExpressCheckout", $nvpstr);

		$ack = strtoupper($resArray["ACK"]);

		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")

		{

			$token = urldecode($resArray["TOKEN"]);

			$_SESSION['TOKEN']=$token;

		}

		   

	    return $resArray;

	}

	

	/*

	'-------------------------------------------------------------------------------------------

	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.

	'

	' Inputs:  

	'		None

	' Returns: 

	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.

	'-------------------------------------------------------------------------------------------

	*/

	public function GetShippingDetails( $token,$packageName )

	{
	

		//'--------------------------------------------------------------

		//' At this point, the buyer has completed authorizing the payment

		//' at PayPal.  The function will call PayPal to obtain the details

		//' of the authorization, incuding any shipping information of the

		//' buyer.  Remember, the authorization is not a completed transaction

		//' at this state - the buyer still needs an additional step to finalize

		//' the transaction

		//'--------------------------------------------------------------

	   

	    //'---------------------------------------------------------------------------

		//' Build a second API request to PayPal, using the token as the

		//'  ID to get the details on the payment authorization

		//'---------------------------------------------------------------------------

	    $nvpstr="&TOKEN=" . $token;



		//'---------------------------------------------------------------------------

		//' Make the API call and store the results in an array.  

		//'	If the call was a success, show the authorization details, aGetExpressnd provide

		//' 	an action to complete the payment.  

		//'	If failed, show the error

		//'---------------------------------------------------------------------------

	    $resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr);
		
		

	    $ack = strtoupper($resArray["ACK"]);

		$resArray['package_name'] = $packageName;

		if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")

		{	

			$_SESSION['payer_id'] =	$resArray['PAYERID'];

			$_SESSION['email'] =	$resArray['EMAIL'];

			$_SESSION['firstName'] = $resArray["FIRSTNAME"]; 

			$_SESSION['lastName'] = $resArray["LASTNAME"]; 

			$_SESSION['shipToName'] = $resArray["SHIPTONAME"]; 

			$_SESSION['shipToStreet'] = $resArray["SHIPTOSTREET"]; 

			$_SESSION['shipToCity'] = $resArray["SHIPTOCITY"];

			$_SESSION['shipToState'] = $resArray["SHIPTOSTATE"];

			$_SESSION['shipToZip'] = $resArray["SHIPTOZIP"];

			$_SESSION['shipToCountry'] = $resArray["SHIPTOCOUNTRYCODE"];

		} 

		return $resArray;

	}

	

	/*

	'-------------------------------------------------------------------------------------------------------------------------------------------

	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.

	'

	' Inputs:  

	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.

	' Returns: 

	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.

	'--------------------------------------------------------------------------------------------------------------------------------------------	

	*/

	public function ConfirmPayment( $FinalPaymentAmt )

	{

		/* Gather the information to make the final call to

		   finalize the PayPal payment.  The variable nvpstr

		   holds the name value pairs

		   */

		



		//Format the other parameters that were stored in the session from the previous calls	

		$token 		= urlencode($_SESSION['TOKEN']);

		$paymentType 		= urlencode($_SESSION['PaymentType']);

		$currencyCodeType 	= urlencode($_SESSION['currencyCodeType']);

		$payerID 		= urlencode($_SESSION['payer_id']);



		$serverName 		= urlencode($_SERVER['SERVER_NAME']);



		$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTACTION=' . $paymentType . '&AMT=' . $FinalPaymentAmt;

		$nvpstr .= '&CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName; 



		 /* Make the call to PayPal to finalize payment

		    If an error occured, show the resulting errors

		    */
		    
		     /* echo"<pre>";
  
print_r($nvpstr);	  
	  

	  echo"</pre>";	
exit; */

		$resArray=$this->hash_call("DoExpressCheckoutPayment",$nvpstr);



		$_SESSION['billing_agreemenet_id']	= $resArray["BILLINGAGREEMENTID"];



		/* Display the API response back to the browser.

		   If the response from PayPal was a success, display the response parameters'

		   If the response was an error, display the errors received using APIError.php.

		   */

		$ack = strtoupper($resArray["ACK"]);

 

		return $resArray;

	}

	

	public function CreateRecurringPaymentsProfile($amount,$package_name,$profilestartdate)

	{
//$resArray = $this->ConfirmPayment($amount);
//echo $profilestartdate;
		//'--------------------------------------------------------------

		//' At this point, the buyer has completed authorizing the payment

		//' at PayPal.  The function will call PayPal to obtain the details

		//' of the authorization, incuding any shipping information of the

		//' buyer.  Remember, the authorization is not a completed transaction

		//' at this state - the buyer still needs an additional step to finalize

		//' the transaction

		//'--------------------------------------------------------------

		

	

		 $token 		= urlencode($_SESSION['TOKEN']);
		

	    $email 		= urlencode($_SESSION['email']);

		$shipToName		= urlencode($_SESSION['shipToName']);

		$shipToStreet		= urlencode($_SESSION['shipToStreet']);

		$shipToCity		= urlencode($_SESSION['shipToCity']);

		$shipToState		= urlencode($_SESSION['shipToState']);

		$shipToZip		= urlencode($_SESSION['shipToZip']);

		$shipToCountry	= urlencode($_SESSION['shipToCountry']);

		

	    //'---------------------------------------------------------------------------

		//' Build a second API request to PayPal, using the token as the

		//'  ID to get the details on the payment authorization

		//'---------------------------------------------------------------------------

		$nvpstr="&TOKEN=".$token;

	    $nvpstr.="&EMAIL=".$email;

		$nvpstr.="&SHIPTONAME=".$shipToName;

		$nvpstr.="&SHIPTOSTREET=".$shipToStreet;

		$nvpstr.="&SHIPTOCITY=".$shipToCity;

		$nvpstr.="&SHIPTOSTATE=".$shipToState;

		$nvpstr.="&SHIPTOZIP=".$shipToZip;

		$nvpstr.="&SHIPTOCOUNTRY=".$shipToCountry;
		
		 /*if($profilestartdate >0){
				
		 $profilestartdate=urlencode(date('Y-m-d H:i:s', strtotime("$profilestartdate hours")));
		
		}else{
		
		 $profilestartdate=urlencode(date('Y-m-d H:i:s',time()));
		
		} */
		
				
		
		If($profilestartdate = 0){
 		  $profilestartdate=urlencode(date('Y-m-d H:i:s',time()));
		}
		Else If($profilestartdate > 0){
		  $profilestartdate=urlencode(date('Y-m-d H:i:s', strtotime("+$profilestartdate hours")));
		}
		Else{
		  $profilestartdate=urlencode(date('Y-m-d H:i:s', strtotime("$profilestartdate hours")));
		}
	
		//$profilestartdate=urlencode(date('Y-m-d H:i:s',time()));

		//$nvpstr.="&PROFILESTARTDATE=".urlencode(date('Y-m-d H:i:s',time()));
		$nvpstr.="&PROFILESTARTDATE=".$profilestartdate;

$currencycode=PAYMENT_CURRENCY_CODE;
			
	       if($currencycode=='MXN' || $currencycode=='USD' || $currencycode=='AUD' || $currencycode=='CAD' || $currencycode=='HKD' || $currencycode=='NZD' || $currencycode=='SGD')
              
              $symbol="$";
              else if($currencycode=='JPY')
              $symbol="¥";
	      else if($currencycode=='EUR')
              $symbol="€";
              else if($currencycode=='GBP')
              $symbol="£";
              else if($currencycode=='DKK' || $currencycode=='NOK' || $currencycode=='SEK')
              $symbol="kr";
              else if($currencycode=='CHF')
              $symbol="CHF";
              else if($currencycode=='BRL')
              $symbol="R$";
              else if($currencycode=='PHP')
              $symbol="₱";
              else if($currencycode=='ILS')
              $symbol="₪";
		
		$nvpstr.="&DESC=".urlencode("".$package_name." Recurring Payment(".$symbol."".str_replace(".00","",$amount)." monthly)");

		$nvpstr.="&BILLINGPERIOD=Month";

		$nvpstr.="&BILLINGFREQUENCY=1";

		$nvpstr.="&AMT=".$amount;

		$nvpstr.="&CURRENCYCODE=".PAYMENT_CURRENCY_CODE;

		$nvpstr.="&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];

		

		//echo $nvpstr;

				$nvpstrnew="&TOKEN=".$token;

	    $nvpstrnew.="&EMAIL=".$email;

		$nvpstrnew.="&SHIPTONAME=".$shipToName;

		$nvpstrnew.="&SHIPTOSTREET=".$shipToStreet;

		$nvpstrnew.="&SHIPTOCITY=".$shipToCity;

		$nvpstrnew.="&SHIPTOSTATE=".$shipToState;

		$nvpstrnew.="&SHIPTOZIP=".$shipToZip;

		$nvpstrnew.="&SHIPTOCOUNTRY=".$shipToCountry;

		//$nvpstr.="&PROFILESTARTDATE=".urlencode(date('Y-m-d H:i:s',time()));
		
		//$nvpstrnew.="&STARTDATE=".urlencode(date('Y-m-d H:i:s', strtotime("-1 day")));
		$nvpstrnew.="&STARTDATE=".$profilestartdate;
		
		$nvpstrnew.="&DESC=".urlencode("".$package_name." Recurring Payment(".$symbol."".str_replace(".00","",$amount)." monthly)");

		$nvpstrnew.="&BILLINGPERIOD=Month";

		$nvpstrnew.="&BILLINGFREQUENCY=1";

		$nvpstrnew.="&AMT=".$amount;

		$nvpstrnew.="&CURRENCYCODE=".PAYMENT_CURRENCY_CODE;

		$nvpstrnew.="&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];


		

		//'---------------------------------------------------------------------------

		//' Make the API call and store the results in an array.  

		//'	If the call was a success, show the authorization details, and provide

		//' 	an action to complete the payment.  

		//'	If failed, show the error

		//'---------------------------------------------------------------------------

		

		//$resArray1=$this->hash_call("GetTransactionDetails",$nvpstr);
		
		
		
		//$resArray=$this->hash_call("CreateRecurringPaymentsProfile",$nvpstr);
		/*$resArraynew=$this->hash_call("TransactionSearch",$nvpstrnew);
		
		if(!empty($resArraynew['L_TRANSACTIONID0'])){
		
		$nvpstr.="&PROFILESTARTDATE=".urlencode($resArraynew["L_TIMESTAMP0"]);
		}
		else{
		
		$nvpstr.="&PROFILESTARTDATE=".urlencode(date('Y-m-d H:i:s',time()));
		}
		
		$resArray=$this->hash_call("CreateRecurringPaymentsProfile",$nvpstr);
	
	  	  	
	  echo"<pre>";
 
	  
print_r($nvpstrnew);
print_r($nvpstr);	 
print_r($resArraynew);	  
print_r($resArray);	  

	  echo"</pre>";	
exit;*/
		
		
		
		$resArray=$this->hash_call("CreateRecurringPaymentsProfile",$nvpstr);
		//$resArraynew=$this->hash_call("TransactionSearch",$nvpstrnew);
	

		$ack = strtoupper($resArray["ACK"]);

		//return $resArraynew;
		return $resArray;

	}

	/*

	'-------------------------------------------------------------------------------------------------------------------------------------------

	' Purpose: 	This function makes a DoDirectPayment API call

	'

	' Inputs:  

	'		paymentType:		paymentType has to be one of the following values: Sale or Order or Authorization

	'		paymentAmount:  	total value of the shopping cart

	'		currencyCode:	 	currency code value the PayPal API

	'		firstName:			first name as it appears on credit card

	'		lastName:			last name as it appears on credit card

	'		street:				buyer's street address line as it appears on credit card

	'		city:				buyer's city

	'		state:				buyer's state

	'		countryCode:		buyer's country code

	'		zip:				buyer's zip

	'		creditCardType:		buyer's credit card type (i.e. Visa, MasterCard ... )

	'		creditCardNumber:	buyers credit card number without any spaces, dashes or any other characters

	'		expDate:			credit card expiration date

	'		cvv2:				Card Verification Value 

	'		

	'-------------------------------------------------------------------------------------------

	'		

	' Returns: 

	'		The NVP Collection object of the DoDirectPayment Call Response.

	'--------------------------------------------------------------------------------------------------------------------------------------------	

	*/





	public function DirectPayment( $paymentType, $paymentAmount, $creditCardType, $creditCardNumber,

							$expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, 

							$countryCode, $currencyCode )

	{

		//Construct the parameter string that describes DoDirectPayment

		$nvpstr = "&AMT=" . $paymentAmount;

		$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCode;

		$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;

		$nvpstr = $nvpstr . "&CREDITCARDTYPE=" . $creditCardType;

		$nvpstr = $nvpstr . "&ACCT=" . $creditCardNumber;

		$nvpstr = $nvpstr . "&EXPDATE=" . $expDate;

		$nvpstr = $nvpstr . "&CVV2=" . $cvv2;

		$nvpstr = $nvpstr . "&FIRSTNAME=" . $firstName;

		$nvpstr = $nvpstr . "&LASTNAME=" . $lastName;

		$nvpstr = $nvpstr . "&STREET=" . $street;

		$nvpstr = $nvpstr . "&CITY=" . $city;

		$nvpstr = $nvpstr . "&STATE=" . $state;

		$nvpstr = $nvpstr . "&COUNTRYCODE=" . $countryCode;

		$nvpstr = $nvpstr . "&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];



		$resArray=$this->hash_call("DoDirectPayment", $nvpstr);



		return $resArray;

	}





	/**

	  '-------------------------------------------------------------------------------------------------------------------------------------------

	  * hash_call: Function to perform the API call to PayPal using API signature

	  * @methodName is name of API  method.

	  * @nvpStr is nvp string.

	  * returns an associtive array containing the response from the server.

	  '-------------------------------------------------------------------------------------------------------------------------------------------

	*/

	public function hash_call($methodName,$nvpStr)

	{

		//declaring of global variables

		global $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature;

		global $USE_PROXY, $PROXY_HOST, $PROXY_PORT;

		global $gv_ApiErrorURL;

		global $sBNCode;



		//setting the curl parameters.

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$this->API_Endpoint);

		curl_setopt($ch, CURLOPT_VERBOSE, 1);



		//turning off the server and peer verification(TrustManager Concept).

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);



		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		curl_setopt($ch, CURLOPT_POST, 1);

		

	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.

	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 

		if($this->USE_PROXY)

			curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST. ":" . $this->PROXY_PORT); 



		//NVPRequest for submitting to server

		$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->version) . "&PWD=" . urlencode($this->API_Password) . "&USER=" . urlencode($this->API_UserName) . "&SIGNATURE=" . urlencode($this->API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($this->sBNCode);



		//var_dump($nvpreq);

		//setting the nvpreq as POST FIELD to curl

		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);



		//getting response from server

		$this->response = curl_exec($ch);



		//convrting NVPResponse to an Associative Array

		$nvpResArray=$this->deformatNVP($this->response);

		$this->nvpReqArray=$this->deformatNVP($nvpreq);

		$_SESSION['nvpReqArray']=$this->nvpReqArray;



		if (curl_errno($ch)) 

		{

			// moving to display page to display curl errors

			  $_SESSION['curl_error_no']=curl_errno($ch) ;

			  $_SESSION['curl_error_msg']=curl_error($ch);



			  //Execute the Error handling module to display errors. 

		} 

		else 

		{

			 //closing the curl

		  	curl_close($ch);

		}



		return $nvpResArray;

	}



	/* '----------------------------------------------------------------------------------

	 Purpose: Redirects to PayPal.com site.

	 Inputs:  NVP string.

	 Returns: 

	----------------------------------------------------------------------------------

	*/

	public function RedirectToPayPal ( $token )

	{

		global $PAYPAL_URL;

		

		// Redirect to paypal.com here

		$payPalURL = $this->PAYPAL_URL . $token;

		?>

		<script>

			window.location="<?php echo $payPalURL ?>";

		</script>

		<?

		header("Location: ".$payPalURL);

	}



	

	/*'----------------------------------------------------------------------------------

	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.

	  * It is usefull to search for a particular key and displaying arrays.

	  * @nvpstr is NVPString.

	  * @nvpArray is Associative Array.

	   ----------------------------------------------------------------------------------

	  */

	public function deformatNVP($nvpstr)

	{

		$intial=0;

	 	$nvpArray = array();



		while(strlen($nvpstr))

		{

			//postion of Key

			$keypos= strpos($nvpstr,'=');

			//position of value

			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);



			/*getting the Key and Value values and storing in a Associative Array*/

			$keyval=substr($nvpstr,$intial,$keypos);

			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);

			//decoding the respose

			$nvpArray[urldecode($keyval)] =urldecode( $valval);

			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));

	     }

		return $nvpArray;

	}

	}