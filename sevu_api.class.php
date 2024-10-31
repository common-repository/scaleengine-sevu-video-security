<?php

class SEVU_API_WP{
    
    private $SE_API_SECRET;
    private $SE_API_PUBLIC;
    private $SE_API_ADDRESS = 'https://api.scaleengine.net/stable/';
    private $SE_CDN;
    
    public $message = "";
    
    function __construct()
    {
        $this->SE_API_SECRET = get_option('sevu_api_secret');
        $this->SE_API_PUBLIC = get_option('sevu_api_public');
        $this->SE_API_ADDRESS = get_option('sevu_api_address');
        $this->SE_CDN = get_option('sevu_cdn');
    }
    
    /**
    * Requests a SEVU ticket to be created
    * @param Bool IP - Restrict ticket to an IP Addres
    * @param String video - path/to/video.mp4, * to allow viewing of any video
    * @param String app - name of the application
    * @param Int uses - # of times ticket may be used, 0 for unlimited uses
    * @param String expire - ISO 8601 or RFC 2822 formatted date, or as a relative time string ('+X units')
    *
    * @return String Ticket Key, false on failure
    */
    function request_ticket($ip, $video, $app, $uses, $expire = NULL) {
        $ip_addr = '0.0.0.0/0';
        if($ip)
        {
            $ip_addr = $_SERVER['REMOTE_ADDR'];
        }
        
        //randomly generated password string
        $pass = md5(rand(1000,9999).microtime(true).rand(1000,9999).'sevu_wp_plugin');
        $data = array(
            'command' => 'sevu.request',
            'pass' => $pass,
            'ip' => $ip_addr,
            'video' => $video,
            'app' => $app,
            'uses' => $uses,
        );
        if(!is_null($expire)) $data['expire'] = $expire;
        $result = $this->sevu_connect($data);
        
        $this->message = $result['message'];;
        
        if($result['status'] == 'success'){
            return $result['ticket'];
        }
        return false;    
    }
    
    /**
    * Connects to the SEVU API and returns the result
    */
    private function sevu_connect(&$data){        
        $time = new DateTime('now', new DateTimeZone('UTC'));
        $arrAPI = array( 
            'api_key' => $this->SE_API_PUBLIC,
            'timestamp' => $time->getTimestamp(),
            'cdn' => $this->SE_CDN,
        );
       
        $arrAPI = array_merge($arrAPI,$data);
        
        $request = json_encode($arrAPI);
        $sig = base64_encode(hash_hmac('sha256', $request, $this->SE_API_SECRET, true));
        $arrAPI['signature'] = $sig;
        $request = json_encode($arrAPI);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->SE_API_ADDRESS); // Set the URL
        curl_setopt($ch, CURLOPT_POST, true); // Perform a POST
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // If not set, curl prints output to the browser
        curl_setopt($ch, CURLOPT_HEADER, false); // If set, curl returns headers as part of the data stream
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('json' => $request)); //'Json' string or 'PHP' serialized return
        
        //If your PHP host does not have a proper SSL certificate bundle, you will need to turn off SSL Certificate Verification
        //This is dangerous, and should only be done temporarily until a proper certificate bundle can be installed
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Turns off verification of the SSL certificate.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Turns off verification of the SSL certificate.
        
        $response = curl_exec($ch); //Execute the API Call
        
        if (!$response) {
            die('Failed to connect to ScaleEngine API');
        }
        //print_r($response);
        $arrResponse = json_decode($response, true); //Decode the response as an associative array 
        //print_r($arrResponse);
        if ($arrResponse) {
            //Operation completed successfully 
            return $arrResponse;
        } else {
            //echo 'Unknown API Error';
            exit();
            die('Unknown API Error');
        }
    }
}