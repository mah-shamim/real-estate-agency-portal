<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

if(!function_exists('session_status')){
    exit('PHP version 5.4 is required for this version of Google API');
}

class GTranslation
{
    public $clientID; // Customer ID
    public $clientSecret; // Primary Account Key
    
    // Your commercial google translate server API key
    private $apiKey = '';

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
 
    function translate_api($word, $from, $to)
    {
        $CI =& get_instance();
        $CI->load->library('MY_Composer');

        $tr = new Stichoza\GoogleTranslate\TranslateClient($from, $to);

        return $tr->translate($word);
    }
    
    function translate_commercial_api($word, $from, $to)
    {
        $url = 'https://www.googleapis.com/language/translate/v2?key=' . $this->apiKey . '&q=' . rawurlencode($word) . '&source='.$from.'&target='.$to;
    
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);                 
        $responseDecoded = json_decode($response, true);
        curl_close($handle);

        if(isset($responseDecoded['data']['translations'][0]['translatedText']))
            return $responseDecoded['data']['translations'][0]['translatedText'];
            
        return '';
    }
    
    public function translate($word, $from, $to)
    {
        $CI =& get_instance();
        $CI->load->helper('text');
        
        $word = strip_tags($word);
        $word = str_replace("&nbsp;"," ",$word); // change HTML space with char space
        $word = preg_replace("/[[:blank:]]+/"," ",$word); // replace multiple spaces with one
        $word = str_replace(" .",".",$word);
        $word = str_replace(". ",".",$word);
        $word = str_replace(".",". ",$word);
        
        $word = character_limiter($word, 400);
        
	    if(!function_exists('curl_version'))
            return '';
            
        if(!empty($this->apiKey))
        {
            return $this->translate_commercial_api($word, $from, $to);
        }
        
        return $this->translate_api($word, $from, $to);
    }

}

?>