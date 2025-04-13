<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Litecache {

    var $cache_time_sec = 3000;

    var $string_uri = NULL;
    var $string_post = NULL;
    var $string_get = NULL;
    var $strign_total = NULL;
    var $cache_file = NULL;    
    var $cache_file_prefix = '';    
    var $logged=FALSE;
    
    public function __construct($params = array())
    {
        $this->CI = &get_instance();
        
        $this->string_uri = $this->CI->uri->uri_string();
        $this->string_post = implode("|",array_flatten($_POST));
        $this->string_get = implode("|",$_GET);
        $this->strign_total = $this->string_uri."|".$this->string_post."|".$this->string_get;
        $this->cache_file_prefix = substr(md5($_SERVER['HTTP_HOST']),0,5) ;
        $this->cache_file = $this->cache_file_prefix.md5($this->strign_total);
        
        $this->CI->load->library('session');
        $this->logged = $this->CI->session->userdata('type');
        
        if($this->logged !== FALSE)
        {
        	$this->CI->load->model('user_m');
        	$this->logged = $this->CI->user_m->loggedin();
        }
        
        if(empty($this->logged) && $this->CI->session->userdata('property_compare') != "" &&
           $this->CI->uri->segment(1) == 'property')
        {
            $this->logged = "COOKIE";
        }
        
    }
    
    public function load_cache()
    {
        if(sw_count($_POST) > 0 || sw_count($_GET) > 0 || $this->logged !== FALSE)
        {
            return true;
        }
        
        if(file_exists(APPPATH.'cache/'.$this->cache_file.'.cache'))
        {
            // if file is to old, then remove it
            if(filemtime(APPPATH.'cache/'.$this->cache_file.'.cache') < time()-$this->cache_time_sec)
            {
                unlink(APPPATH.'cache/'.$this->cache_file.'.cache');
            }
            else
            {
                // load from cache
                readfile(APPPATH.'cache/'.$this->cache_file.'.cache');
                exit();
            }
        }
    }
    
    public function save_cache($page_html)
    {
        if(!is_writable(APPPATH.'cache/'))
        {
            exit('Cache folder not writable!');
        }

        if(sw_count($_POST) > 0 || sw_count($_GET) > 0 || $this->logged !== FALSE)
            return true;

        if(config_item('litecache_lazyload_enabled') === TRUE)
        {
            $page_html = filter_lazyload($page_html);
        }
        
        if(config_item('litecache_compress_html_enabled') === TRUE)
        {
        $page_html = $this->compress_html($page_html);
        }
        
        $fp = fopen(APPPATH.'cache/'.$this->cache_file.'.cache', 'w');
        fwrite($fp, $page_html);
        fclose($fp);
    }
    

    function compress_html($buffer) {
       //return $buffer;
        $buffer = str_replace(array("\r\n", "\r", "\n"), '', $buffer);
        $buffer = str_replace(array("\t","	","  "), ' ', $buffer);
        $buffer = str_replace(array("\t","	","  "), ' ', $buffer);
        $buffer = str_replace(array("\t","	","  "), ' ', $buffer);
        $buffer = str_replace(array("\t","	","  "), ' ', $buffer);
        $buffer = str_replace(array("\t","	","  "), ' ', $buffer);

        return $buffer;
    }

}

?>