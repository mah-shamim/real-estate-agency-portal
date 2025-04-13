<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session
{
	function sess_update ()
	{
		// Listen to HTTP_X_REQUESTED_WITH
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
			// This is NOT an ajax call
			parent::sess_update();
		}
	}
    
    // nginx fix
	function _flashdata_mark1()
    {
            $userdata = $this->all_userdata();
            $newUserData = array();
            $userDataToUnset = array();
            foreach ($userdata as $name => $value)
            {
                    $parts = explode(':new:', $name);
                    if (is_array($parts) && sw_count($parts) === 2)
                    {
                            $new_name = $this->flashdata_key.':old:'.$parts[1];
                            $newUserData[$new_name] = $value;
                            $userDataToUnset[$name] = '';
                            //if we call these for each flash key we end up with a TON of set-cookie headers
                            //this is dumb, and causes problems with loadbalancers etc.
                            //$this->set_userdata($new_name, $value);
                            //$this->unset_userdata($name);
                    }
            }

            if ( sw_count($newUserData) > 0 ) {
              $this->set_userdata($newUserData);
              $this->unset_userdata($userDataToUnset);
              
              //echo 'T1';
              //var_dump($newUserData);
            }
    }
        
        
}