<?php 
class SlooceComponent
{
    var $controller;
	var	$Slooce = "";
	function __construct(){
		//$this->getObject();
	}
    function startup( &$controller ) {
        $this->controller = &$controller;
    }
	function mt($apiurl,$partnerid,$partnerpassword,$phonenumber,$keyword,$msg){
                $msgcheck = str_replace('&','&amp;',$msg);
		$msgcheck = str_replace('<','&lt;',$msgcheck);
		$msgcheck = str_replace('>','&gt;',$msgcheck);
		$msgcheck = str_replace('"','&quot;',$msgcheck);
		$body ="<message><partnerpassword>".$partnerpassword."</partnerpassword><content>".$msgcheck."</content> </message>";
		$url = "".$apiurl."".$partnerid."/".$phonenumber."/".$keyword."/messages/mt";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$xml=simplexml_load_string($output);
		$response = $this->convertXmlObjToArr($xml);
		return $response;
	}
	function convertXmlObjToArr($obj){
		$arr=array();
		$children = $obj->attributes();
		foreach ($children as $attributeName => $attributeValue)
		{
			$attribName = strtolower(trim((string)$attributeName));
			$attribVal = trim((string)$attributeValue);
			$arr[$attribName] = $attribVal;
		}
		return $arr;
	} 

}
?>