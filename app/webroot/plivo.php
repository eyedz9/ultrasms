<?php 
include("database.php");

class PlivoComponent {
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
                $this->version = 'v1';
                $auth_id = PLIVO_KEY;
		$this->auth_id = PLIVO_KEY;
		$this->auth_token = PLIVO_TOKEN;
                $this->api = $url."/".$this->version."/Account/".$auth_id;
            	$this->ch = NULL;
	}
	function sendsms($to,$from,$body){
		$this->getObject();
		$vars['src']=$from;
		$vars['dst']=$to;
		$vars['text']=$body;
		$vars['type']='sms';
                $vars['url']=SITEURL.'/plivos/callstatus';
                $vars['method']='GET';
		$response = $this->send_message($vars);		
	   	return $response;
	}

	
	public function send_message($params=array()) {
        return $this->request('POST', '/Message/', $params);
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

/*echo "<pre>";
print_r($encoded);
print_r($result);
print_r($url);
print_r($json_params);
print_r($responseCode);
die();*/
 
           $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
           curl_close($curl);
           $result2 = json_decode($result, TRUE);
           return array("status" => $responseCode, "response" => $result2);       

           


    }
}
?>