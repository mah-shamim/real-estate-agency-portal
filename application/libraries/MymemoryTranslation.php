<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MymemoryTranslation
{
    public $clientID; // Customer ID
    public $clientSecret; // Primary Account Key
    private $accessToken = NULL;
    private $validEmail = 'sandi@iwinter.com.hr';
    
    private $callsCount=0;

    public function __construct($params = array())
    {
        $cid = '';
        $secret = '';
        
        if(is_array($params))
        {
            if(isset($params['clientID']))
                $cid = $params['clientID'];
            
            if(isset($params['clientSecret']))
                $secret = $params['clientSecret'];
        }
        
        $this->clientID = $cid;
        $this->clientSecret = $secret;
    }

    public function translate($word, $from, $to)
    {   
        $CI =& get_instance();
        $CI->load->helper('text');
        
	    if(!function_exists('curl_version'))
            return '';
            
        $this->callsCount++;
        
        if(config_item('limit_curl_calls') !== FALSE)
        {
            if($this->callsCount > config_item('limit_curl_calls'))
            {
                return "";
            }
        }
        
        $word = strip_tags($word);
        $word = str_replace("&nbsp;"," ",$word);
        $word = preg_replace("/[[:blank:]]+/"," ",$word);
        $word = str_replace(" .",".",$word);
        $word = str_replace(". ",".",$word);
        $word = str_replace(".",". ",$word);
        
        $word = character_limiter($word, 400);
        
        $params = "q=".urlencode($word)."&langpair=".$from."|".$to;
        
        if(!empty($this->validEmail))
            $params .= "&de=".$this->validEmail;
        
        $json_url = "http://api.mymemory.translated.net/get?$params";
       
        // Initializing curl
        $ch = curl_init( $json_url );
        
        // Configuring curl options
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json')
        );
        
        $translatedStr = '';
        
        // Setting curl options
        curl_setopt_array( $ch, $options );
        
        // Getting results
        $json = curl_exec($ch); // Getting jSON result string
        
        $decoded_json = json_decode($json);
        
        if(!is_object($decoded_json))
            return '';
            
        if($decoded_json->responseStatus != '200')
            $translatedStr = 'ERROR: ';
        
        if($decoded_json->responseData->translatedText != '')
            $translatedStr = $decoded_json->responseData->translatedText;
        
        return $translatedStr;
    }

}

?>