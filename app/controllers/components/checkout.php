<?php 
class CheckoutComponent extends Object{
    function startup( &$controller ) {
		$this->controller = &$controller;
    }
	function checksale(){
		$ch = curl_init("https://www.2checkout.com/api/sales/detail_sale?sale_id=1234567890");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "2Checkout PHP/0.1.0");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "pankaj.oditi:Test1234");
		$json_resp = curl_exec($ch);
		curl_close($ch);
		//decode to an associative array
		$array_resp = json_decode($json_resp, true);
		return $array_resp;
	}
	function signup(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, 'https://www.2checkout.com/checkout/spurchase');
		$content = curl_exec($ch);  
		$content = json_decode($content);
		echo '<pre>';
		print_r($content);
		echo '</pre>';
		echo $luckyUrl = $content->responseData->results[0]->unescapedUrl;
		exit();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.2checkout.com/checkout/purchase');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//start post
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"sid=1303908&product_id1=1&quantity1=1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//end post
		curl_setopt($ch, CURLOPT_USERAGENT, "2Checkout PHP/0.1.0");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "pankaj.oditi:Test1234");
		$json_resp = curl_exec($ch);
		curl_close($ch);
		//decode to an associative array
		$array_resp = json_decode($json_resp, true);
		echo '<pre>';
		print_r($array_resp);
		echo '</pre>';
		exit();
		return $array_resp;
	}
}
?>