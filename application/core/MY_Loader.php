<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
   function __construct()
   {
      parent::__construct();

      $this->_ci_view_paths += array(FCPATH.'templates/'=>TRUE);
   }
   
	public function view($view, $vars = array(), $return = FALSE)
	{
        $admin_template = '';
        if(config_db_item('admin_template') !== FALSE)
            $admin_template = config_db_item('admin_template');
       
        if(strpos($view, 'admin/') === 0)
            $view = $admin_template.'/'.$view;
            
        if(isset($vars['subview']))
        {
            if(strpos($vars['subview'], 'admin/') === 0)
                $vars['subview'] = $admin_template.'/'.$vars['subview'];
        }
        
        return parent::view($view, $vars, $return);
	}

    function common_view($view, $vars = array(), $return = FALSE)
    {
        $view = 'common/'.$view;

        return parent::view($view, $vars, $return);
    }
    
}

?>