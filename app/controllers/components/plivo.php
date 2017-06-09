<?php 
App::import('Vendor','Plivo');

class PlivoComponent extends Object{
	var $controller;
	var	$AuthId = '';
	var	$AuthToken = '';
	
	function __construct(){
		$this->getObject();
	}
    function startup(Controller$controller ) {
        $this->controller = $controller;
	
    }
	function getObject(){
				$url = 'https://api.plivo.com';
				$auth_id = $this->AuthId;
			    $this->version = 'v1';
				$this->api = $url."/".$this->version."/Account/".$auth_id;
				$this->auth_id = $this->AuthId;
				$this->auth_token = $this->AuthToken;
				$this->ch = NULL;
	}
	function sendsms($to,$from,$body){
		$this->getObject();
		$vars['src']=$from;
		$vars['dst']=$to;
		$vars['text']=$body;
		$vars['type']='sms';
		$vars['url']=SITE_URL.'/plivos/callstatus';
		$vars['method']='GET';
		$response = $this->send_message($vars);		
	   	return $response;
	}
        
        public function get_recordings($callid) {
            $vars['call_uuid']=$callid;
            return $this->request('GET', '/Recording/', $vars);
        }
	
	function listNumbers($country_iso,$pattern,$services){
		$this->getObject();
		$vars['country_iso']=$country_iso;
		$vars['services']=$services;
		$vars['type']='any';
		$vars['pattern']=$pattern;
		$response = $this->search_phone_numbers($vars);	
	   	return $response;
	}
	
	public function application_number($numberid,$appid){
       $this->getObject();
		$vars['number']=$numberid;
		$vars['app_id']=$appid;
		$response = $this->link_application_number($vars);		
	   	return $response;
    }

     public function record($params = array()) {
        $this->getObject();
        $call_uuid = $this->pop($params, 'call_uuid');
        return $this->request('POST', '/Call/'.$call_uuid.'/Record/', $params);
    }
	
	public function link_application_number($params=array()) {
        $number = $this->pop($params, "number");
        return $this->request('POST', '/Number/'.$number.'/', $params);
    }
	public function callNumberToPEOPLE($callnumber,$callerPhone,$group_id,$log_id,$repeat,$language,$pause){
		$this->getObject();
		$answer_url = SITE_URL.'/plivos/peoplecallrecordscript/'.$group_id.'/'.$repeat.'/'.$language.'/'.$pause;
		$hangup_url = SITE_URL.'/plivos/hang_up/'.$group_id.'/'.$log_id;
		$params = array(
			'answer_url'=>$answer_url,
			'hangup_url'=>$hangup_url,
			'to'=>$callnumber,
			'from'=>$callerPhone,
			'answer_method'=>'GET',
			'hangup_method'=>'GET',
		);
        return $this->request('POST', '/Call/', $params);
    }
	public function create_application(){
		$this->getObject();
		$params = array(
			'answer_url'=>SITE_URL.'/plivos/voice',
			'app_name'=>SITENAME,
			'message_url'=>SITE_URL.'/plivos/sms',
                        'hangup_url'=>SITE_URL.'/plivos/hangup',
			'message_method'=>'GET',
                        'answer_method'=>'GET',
                        'hangup_method'=>'GET',
		);
        return $this->request('POST', '/Application/', $params);
    }
	
	private function pop($params, $key) {
        $val = $params[$key];
        if (!$val) {
            throw new PlivoError($key." parameter not found");
        }
        unset($params[$key]);
        return $val;
    }
	
	public function buy_phone_numbers($number){
		$this->getObject();
        $response = $this->buy_phone_number($number);		
		return $response;
    }
	
	public function send_message($params=array()) {
        return $this->request('POST', '/Message/', $params);
    }
	
	public function search_phone_numbers($params=array()){
        return $this->request('GET', '/PhoneNumber/', $params);
    }
	
	public function buy_phone_number($number) {
        return $this->request('POST', '/PhoneNumber/'.$number.'/');
    }
	public function delete_phone_number($number) {
		$this->getObject();
        $response = $this->delete_phone_number_plivio($number);		
		return $response;
    }
	
	public function delete_phone_number_plivio($number){
		return $this->request('DELETE', '/Number/'.$number.'/');
    }
	
	
	private function request($method, $path, $params=array()) {
        return $this->curl_request($method, $path, $params);
    }
	
	private function curl_request($method, $path, $params) {
        // construct full url
		$url = $this->api.rtrim($path, '/').'/';
		
		if (($method == "GET") || ($method == "DELETE")) {
		   $query = http_build_query($params, '', "&");
		   if (($query != NULL) && ($query != "")) {
				$url = $url."?".$query;
		   }
		}

		// initialize a new curl object            
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

		$headers = array(
		  'Authorization: Basic '. base64_encode($this->auth_id.":".$this->auth_token),
		  'Connection' => 'close'
		 );

		switch(strtoupper($method)) {
			case "GET":
				curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
				break;
			case "POST":
				curl_setopt($curl, CURLOPT_POST, TRUE);
				$json_params = json_encode($params);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $json_params);
				array_push($headers, "Content-Type:application/json");
				array_push($headers, 'Content-Length: '.strlen($json_params));
				break;
			case "DELETE":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
		 }
		
		curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);

		$options = array(
		   CURLOPT_URL => $url,
		   CURLOPT_USERAGENT => "PHPPlivo/Curl",
		   CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1,
		   CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		   CURLOPT_CUSTOMREQUEST => $method,
		   CURLOPT_TIMEOUT => 30,
		   CURLOPT_CONNECTTIMEOUT => 30,
		   CURLOPT_HEADER => FALSE,
		   CURLOPT_VERBOSE => FALSE,
		);

		curl_setopt_array($curl, $options);

		if(FALSE === ($result = curl_exec($curl))){
			$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);
			curl_close($curl);
			return array("status" => $responseCode, "response" => array("error" => $err));
		}
	   $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	   curl_close($curl);
	   $result2 = json_decode($result, TRUE);
	   return array("status" => $responseCode, "response" => $result2);       
    }
}
?>