<?php

class Lead_m extends MY_Model {
    
    protected $_table_name = 'leads';
    protected $_order_by = 'id';
    public $rules = array(
    );
    
    public $rules_lang = array();
    
    public $rules_lang_news = array();
   
	public function __construct(){
		parent::__construct();
        
        $this->languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
	}

    public function get_new()
	{
        $lead = new stdClass();
        $lead->lead_id  = NULL;
        $lead->created_time  = date('Y-m-d H:i:s');
        $lead->retailer_item_id  = NULL;
        $lead->email  = NULL;
        $lead->json  = NULL;
        
        return $lead;
	}

}


