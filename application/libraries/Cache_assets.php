<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

// Library for API: https://www.clickatell.com

class Cache_assets
{
    private static $instance = null;
    
    public $handle_array_js = [];
    protected $CI;
    public $settings_ci;
    protected $my_JSqueeze;
    
    public static function getInstance()
    {
        if(!self::$instance)
        {
          self::$instance = new Cache_assets();
        }
        return self::$instance;
    }
    
    /*
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }

        return self::$instances[$cls];
    }*/
    
    public function __construct($params = array()) {
        /* add libraries and model */
        $CI = &get_instance();
        $CI->load->library('session');
        $this->CI = $CI;
        $this->settings_ci = $CI->settings_m->get_fields();
    }
    
    public function sw_add_inline_script($handle, $js_inline = NULL, $compress_enabled=false, $cache_time_sec = 86400) {
        
        if(!isset($this->handle_array_js[$handle])) $this->handle_array_js[$handle] = '';
        if($this->CI->session->userdata('type')=='ADMIN')
            $cache_time_sec = 0;

        $handle_cache = FCPATH.'templates/'.$this->settings_ci['template'].'/assets/cache/'.$handle.'.js';
        if($js_inline === NULL) {
            if(file_exists($handle_cache) && filemtime($handle_cache) > time()-$cache_time_sec)
            {
            } else {
                file_put_contents(FCPATH.'templates/'.$this->settings_ci['template'].'/assets/cache/'.$handle.'.js', $this->handle_array_js[$handle]);
            }

            echo '<script src="assets/cache/'.$handle.'.js?v='.rand(000,999).'"></script>';
        } else {
            //Load view
            if(false && file_exists($handle_cache) && filemtime($handle_cache) > time()-$cache_time_sec)
            {
            } else {
                $output = $js_inline;
                $output = str_replace(array('<script>','</script>'), '', $output);
                if($compress_enabled) {
                    $output = $this->compress_js()->squeeze($output, true, false);
                }
                $this->handle_array_js[$handle] .= $output;
            }
        }
    }
    

    public function sw_add_script($handle, $original_file = NULL, $compress_enabled=true, $cache_time_sec = 86400) {
        
        if(!isset($this->handle_array_js[$handle])) $this->handle_array_js[$handle] = '';
        if($this->CI->session->userdata('type')=='ADMIN')
            $cache_time_sec = 0;
        $handle_cache = FCPATH.'templates/'.$this->settings_ci['template'].'/assets/cache/'.$handle.'.js';
        if($original_file === NULL) {
            if(file_exists($handle_cache) && filemtime($handle_cache) > time()-$cache_time_sec)
            {
            } else {
                file_put_contents(FCPATH.'templates/'.$this->settings_ci['template'].'/assets/cache/'.$handle.'.js', $this->handle_array_js[$handle]);
            }

            echo '<script src="assets/cache/'.$handle.'.js?v='.rand(000,999).'"></script>';
        } else {
            //Load view
            if(file_exists($handle_cache) && filemtime($handle_cache) > time()-$cache_time_sec)
            {
            } else {
                if(file_exists(FCPATH.'templates/'.$this->settings_ci['template'].'/'.$original_file))
                {
                    $output = $this->CI->parser->parse($this->settings_ci['template'].'/'.$original_file, $this->CI->data, TRUE);
                    $output = str_replace(array('<script>','</script>'), '', $output);
                    if($compress_enabled) {
                        $output = $this->compress_js()->squeeze($output, true, false);
                    }
                    $this->handle_array_js[$handle] .= $output;
                }
            }
        }
        
    }

    public function compress_js (){
	if ( !  ($this->my_JSqueeze instanceof JSqueeze ) ) {
            require_once APPPATH."helpers/min-js.php";
            $this->my_JSqueeze = new JSqueeze();
	}
	return $this->my_JSqueeze;
    }
    
    public function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set cURL to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    
}

?>