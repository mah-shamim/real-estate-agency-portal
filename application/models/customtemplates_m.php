<?php

class Customtemplates_m extends MY_Model {
    
    protected $_table_name = 'custom_templates';
    protected $_order_by = 'id';
    public $rules_admin = array(
        'theme' => array('field'=>'theme', 'label'=>'lang:Theme', 'rules'=>'trim|required'),
        'template_name' => array('field'=>'template_name', 'label'=>'lang:Template name', 'rules'=>'trim|required'),
        'type' => array('field'=>'type', 'label'=>'lang:Type', 'rules'=>'trim|required'),
        'widgets_order' => array('field'=>'widgets_order', 'label'=>'lang:Please drag some widgets', 'rules'=>'trim|required'),
    );
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        $item->theme = '';
        $item->template_name = '';
        $item->type = 'RIGHT';
        $item->widgets_order = '';
        
        return $item;
	}
    
    public function delete($id)
    {
        parent::delete($id);
    }

}


