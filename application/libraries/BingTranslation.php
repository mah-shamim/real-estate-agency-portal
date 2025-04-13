<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class BingTranslation
{
    public $clientID; // Customer ID
    public $clientSecret; // Primary Account Key
    private $accessToken = NULL;

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

    /*
     * Get the access token.
     *
     * @param string $grantType    Grant type.
     * @param string $scopeUrl     Application Scope URL.
     * @param string $clientID     Application client ID.
     * @param string $clientSecret Application client ID.
     * @param string $authUrl      Oauth Url.
     *
     * @return string.
     */
    function getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl){
        try {
            //Initialize the Curl Session.
            $ch = curl_init();
            //Create the request Array.
            $paramArr = array (
                 'grant_type'    => $grantType,
                 'scope'         => $scopeUrl,
                 'client_id'     => $clientID,
                 'client_secret' => $clientSecret
            );

            //Create an Http Query.//
            //$paramArr = http_build_query($paramArr);
            $paramArr = http_build_query($paramArr, '', '&');   
            //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $authUrl);
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
            //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //Execute the  cURL session.
            $strResponse = curl_exec($ch);
            //Get the Error Code returned by Curl.
            $curlErrno = curl_errno($ch);
            if($curlErrno){
                $curlError = curl_error($ch);
                throw new Exception($curlError);
            }
            //Close the Curl Session.
            curl_close($ch);
            //Decode the returned JSON string.
            $objResponse = json_decode($strResponse);
            if(isset($objResponse->error))
            if ($objResponse->error){
                throw new Exception($objResponse->error_description);
            }
            
            return $objResponse->access_token;
        } catch (Exception $e) {
            echo "Exception-".$e->getMessage();
        }
    }
    
    /*
     * Create and execute the HTTP CURL request.
     *
     * @param string $url        HTTP Url.
     * @param string $authHeader Authorization Header string.
     * @param string $postData   Data to post.
     *
     * @return string.
     *
     */
    function curlRequest($url, $authHeader) {
        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt ($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        return $curlResponse;
    }

    public function translate($word, $from, $to)
    {
        //OAuth Url.
        $authUrl      = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
        //Application Scope Url
        $scopeUrl     = "http://api.microsofttranslator.com";
        //Application grant type
        $grantType    = "client_credentials";
        
        //Create the AccessTokenAuthentication object.
        //$authObj      = new AccessTokenAuthentication();
        //Get the Access token.
        if($this->accessToken == NULL)
            $this->accessToken  = $this->getTokens($grantType, $scopeUrl, $this->clientID, $this->clientSecret, $authUrl);
        //Create the authorization Header string.
        $authHeader = "Authorization: Bearer ". $this->accessToken;
        
        //Set the params.//
        $fromLanguage = $from;
        $toLanguage   = $to;
        $inputStr     = $word;
        $contentType  = 'text/plain';
        $category     = 'general';
    
        $params = "text=".urlencode($inputStr)."&to=".$toLanguage."&from=".$fromLanguage;
        $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";
    
        //Create the Translator Object.
        //$translatorObj = new HTTPTranslator();
    
        //Get the curlResponse.
        $curlResponse = $this->curlRequest($translateUrl, $authHeader);
        
        if(substr_count($curlResponse, 'TranslateApiException') > 0)
        {
            echo $curlResponse;
            exit();
        }
        
        $translatedStr = '';
        
        //Interprets a string of XML into an object.
        $xmlObj = simplexml_load_string($curlResponse);
        foreach((array)$xmlObj[0] as $val){
            $translatedStr = $val;
        }
        
        return $translatedStr;
    }

    public function translate2($word, $from, $tos)
    {
        //translates 1 word to several languages
        //$tos is array of languages to translate to
        //returns array of translations as $result['en']=>'Hello'

        $access_token = $this->get_access_token();

        $result[$from] = $word;

        foreach($tos as $to)
        {
            $url = 'http://api.microsofttranslator.com/V2/Http.svc/Translate?text=hello&from='.$from.'&to='.$to;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:bearer '.$access_token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
            $rsp = curl_exec($ch); 

            preg_match_all('/<string (.*?)>(.*?)<\/string>/s', $rsp, $matches);

            $result[$to] = $matches[2][0];
        }

        return $result;
    }
}

?>