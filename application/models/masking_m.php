<?php

class Masking_m extends MY_Model {
    
    protected $_table_name = 'masking';
    protected $_order_by = 'id';
    public $rules_admin = array(
        'visitor_type' => array('field'=>'visitor_type', 'label'=>'lang:YouAre', 'rules'=>'trim|required'),
        'name' => array('field'=>'name', 'label'=>'lang:YourName', 'rules'=>'trim|required'),
        'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|required'),
        'email' => array('field'=>'email', 'label'=>'lang:Email', 'rules'=>'trim|required|valid_email'),
        'allow_contact' => array('field'=>'allow_contact', 'label'=>'lang:I allow agent and affilities to contact me', 'rules'=>'trim|required'),
        'agent_id' => array('field'=>'agent_id', 'label'=>'lang:Agent', 'rules'=>'trim|required'),
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property', 'rules'=>'trim|required')
    );
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        $item->date_submit = date('Y-m-d H:i:s');
        $item->visitor_type = '';
        $item->phone = '';
        $item->email = 0;
        $item->allow_contact = false;
        $item->agent_id = 'NULL';
        $item->property_id = 'NULL';
        
        return $item;
	}
    
    public function get($id = NULL, $single = FALSE)
    {
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->db->select($this->_table_name.'.*, property_user.user_id');
            $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        return parent::get($id, $single);
    }
    
    public function delete($id)
    {      
        parent::delete($id);
    }

}


