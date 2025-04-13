<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ghelper {

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
    }
    
    /**
    * Reads an URL to a string
    * @param string $url The URL to read from
    * @return string The URL content
    */
    private function getURL($url){
     	$ch = curl_init();
        
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2');
    	curl_setopt($ch, CURLOPT_URL, $url);
    	$tmp = curl_exec($ch);
    	curl_close($ch);
    	if ($tmp != false){
    	 	return $tmp;
    	}
    }
    
    
    
	/**
	* Get Latitude/Longitude/Altitude based on an address
	* @param string $address The address for converting into coordinates
	* @return array An array containing Latitude/Longitude/Altitude data
	*/
    	public function getCoordinates($address){

        $api_key = '';
        
        $key = '';
        if( config_db_item('maps_api_key')) {
            $maps_api_key = config_db_item('maps_api_key');;
            $api_key = $maps_api_key;
        }
        
        $results = array();
        
        if(config_db_item('map_version') != 'open_street' && empty($api_key))
            return $results;

        $address = str_replace(' ','+',$address);
        
        if(function_exists('mb_strtolower'))
            $address = mb_strtolower($address);
        
        if(config_db_item('map_version') =='open_street'){
            $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . $address;
        } else {
            $url = 'https://maps.google.com/maps/api/geocode/json?address=' . $address.'&key='.$api_key;
        }
        
                
        $this->CI->load->model('cacher_m');
        $loaded_value = $this->CI->cacher_m->load($address);
       // $loaded_value = false;
        if($loaded_value === FALSE)
        {
            $data = $this->getURL($url);
        }
        else
        {
            $data = $loaded_value;
        }
		if ($data){
			$resp = json_decode($data, true);
            
        //check cache if changed map api
        if($loaded_value !== FALSE)
        {
            if(config_db_item('map_version') =='open_street'){
                if(isset($resp['status'])) {
                    $data = $this->getURL($url);
                    if ($data){
			$resp = json_decode($data, true);
                        $this->CI->db->set('value', $data);
                        $this->CI->db->where('index_real', $address);
                        $this->CI->db->update($this->CI->cacher_m->get_table_name());
                    }
                }
            } else {
                if(!isset($resp['status'])) {
                    $data = $this->getURL($url);
                    if ($data){
			$resp = json_decode($data, true);
                        $this->CI->db->set('value', $data);
                        $this->CI->db->where('index_real', $address);
                        $this->CI->db->update($this->CI->cacher_m->get_table_name());
                    }
                }
            }
        }           
          if(config_db_item('map_version') =='open_street'){
              if(!empty($resp) && isset($resp[0]) && isset($resp[0]['lat']) && isset($resp[0]['lon'])) {
                if($loaded_value === FALSE)
                {
                    $this->CI->cacher_m->cache($address, $data);
                }
                
                return array('lat' => $resp[0]['lat'], 'lng' => $resp[0]['lon'], 'alt' => 0);
              }
              
              
          } else {
            if(isset($resp['status']))
			if($resp['status'] == 'OK'){
                if($loaded_value === FALSE)
                {
                    $this->CI->cacher_m->cache($address, $data);
                }
             
			 	//all is ok
			 	$lat = $resp['results'][0]['geometry']['location']['lat'];
                $lng = $resp['results'][0]['geometry']['location']['lng'];
			 	if (!empty($lat) && !empty($lng)){
			 	   return array('lat' => $lat, 'lng' => $lng, 'alt' => 0);
				}
			}
		}
            }  
                
		//return default data
		return array('lat' => 0, 'lng' => 0, 'alt' => 0);
	}
    
    // Modified from:
    // http://www.sitepoint.com/forums/showthread.php?656315-adding-distance-gps-coordinates-get-bounding-box
    /**
    * bearing is 0 = north, 180 = south, 90 = east, 270 = west
    *
    */
    function getDueCoords($latitude, $longitude, $bearing, $distance, $distance_unit = "km", $return_as_array = FALSE) {
    
        if ($distance_unit == "m") {
          // Distance is in miles.
        	  $radius = 3963.1676;
        }
        else {
          // distance is in km.
          $radius = 6378.1;
        }
        
        //	New latitude in degrees.
        $new_latitude = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad($bearing))));
        		
        //	New longitude in degrees.
        $new_longitude = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($new_latitude))));
        
        if ($return_as_array) {
          //  Assign new latitude and longitude to an array to be returned to the caller.
          $coord = array();
          $coord['lat'] = $new_latitude;
          $coord['lng'] = $new_longitude;
        }
        else {
          $coord = $new_latitude . ", " . $new_longitude;
        }
        
        return $coord;
    
    }	
    
}

?>