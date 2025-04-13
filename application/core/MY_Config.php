<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Config extends CI_Config{

    
    	function __construct()
	{
            $this->config =& get_config();
            // Fix base_url if base_url defined
            if ($this->config['base_url'] != '')
            {
                // If HTTP_HOST have www , but base_url without www 
                if(strripos($_SERVER['HTTP_HOST'], 'www.') === 0 && strripos($this->config['base_url'], '://www.') == FALSE) {
                    $parse_url = parse_url($this->config['base_url']);
                    $base_url = str_replace($parse_url['scheme'].'://', $parse_url['scheme'].'://www.', $this->config['base_url']);
                } 
                // If HTTP_HOST without www , but base_url have www 
                else if(strripos($_SERVER['HTTP_HOST'], 'www.') !== 0 && strripos($this->config['base_url'], '://www.') !== FALSE) {
                    $base_url = str_replace('://www.', '://', $this->config['base_url']);
                } else {
                    $base_url = $this->config['base_url'];
                }
                $this->set_item('base_url', $base_url);
            }
            
            parent::__construct();
	}

    
	/**
	 * List of paths to search when trying to load a config file
	 *
	 * @var array
	 */
	var $_config_paths = array(APPPATH);

	public function add_config_path($path)
	{
		$this->_config_paths = array_merge(array($path), $this->_config_paths);
    }
    
}

