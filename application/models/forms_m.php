<?php

class Forms_m extends MY_Model {
    
    protected $_table_name = 'forms_search';
    protected $_order_by = 'id';
    public $rules_admin = array(
        'theme' => array('field'=>'theme', 'label'=>'lang:Theme', 'rules'=>'trim|required'),
        'form_name' => array('field'=>'form_name', 'label'=>'lang:Form name', 'rules'=>'trim|required'),
        'type' => array('field'=>'type', 'label'=>'lang:Type', 'rules'=>'trim'),
        'fields_order_primary' => array('field'=>'fields_order_primary', 'label'=>'lang:Fields order primary', 'rules'=>'trim|required'),
        'fields_order_secondary' => array('field'=>'fields_order_secondary', 'label'=>'lang:Fields order secondary', 'rules'=>'trim'),
    );
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        $item->theme = '';
        $item->form_name = '';
        $item->type = 'RIGHT';
        $item->fields_order_primary = '';
        $item->fields_order_secondary = '';
        
        return $item;
	}
    
    public function delete($id)
    {
        parent::delete($id);
    }

}


