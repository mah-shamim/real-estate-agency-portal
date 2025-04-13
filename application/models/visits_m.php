<?php

class Visits_m extends MY_Model {
    
    protected $_table_name = 'visits';
    protected $_order_by = 'id DESC ';
    
    public $rules_admin = array(
        'date_visit' => array('field'=>'date_visit', 'label'=>'lang:Date visit', 'rules'=>'trim'),
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property id', 'rules'=>'trim|required'),
        'client_id' => array('field'=>'client_id', 'label'=>'lang:Client', 'rules'=>'trim|required'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim'),
    );
    
    public $rules_client = array(
        'date_visit' => array('field'=>'date_visit', 'label'=>'lang:Date visit', 'rules'=>'trim|required|callback_date_valid'),
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property id', 'rules'=>'trim|required'),
        'client_id' => array('field'=>'client_id', 'label'=>'lang:Client', 'rules'=>'trim|required'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim'),
    );
    
    
    public function date_valid($date){
        if((bool)strtotime($date)) {
            return true;
        }
        
        $this->form_validation->set_message('date_valid', 'The Date field must date');
        return false;
    }
    
    public function __construct(){
            parent::__construct();
    }
    
    public function get_new()
	{
        $obj = new stdClass();
        $obj->id = '';
        $obj->date_visit = '';
        $obj->date_created = date('Y-m-d H:i:s');
        $obj->date_confirmed = '';
        $obj->date_canceled = '';
        $obj->property_id = '';
        $obj->message = '';
        $obj->client_id = '';
        return $obj;
    }
    
    public function get($id = NULL, $single = FALSE, $where= array()) {
        
        $this->db->select($this->_table_name.'.*, property_user.user_id, property.address as p_address, user.name_surname as client_name_surname');
        
        
        $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
        $this->db->join('user', $this->_table_name.'.client_id = user.id', 'left');
        
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        $this->db->join('property', $this->_table_name.'.property_id = property.id', 'left');
        
        $this->db->where_not_in($this->_table_name.'.client_id', $this->session->userdata('id'));
        
        if(!empty($where))
            $this->db->where($where);
                
        return parent::get($id, $single);
    }
    
    public function get_pagin($limit = NULL, $offset = 0, $where = NULL ) {
        if(!empty($where))
            $this->db->where($where);
        if($limit !== NULL)
            $this->db->limit($limit, $offset);
                
        return $this->get(NULL, FALSE, $where);
    }
    
    public function get_myout($limit = NULL, $offset = 0, $where = array()) {
        
        $this->db->select($this->_table_name.'.*, property_user.user_id, property.address as p_address, user.name_surname as client_name_surname');
        
        $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
        $this->db->join('user', $this->_table_name.'.client_id = user.id', 'left');
        
        $this->db->where($this->_table_name.'.client_id', $this->session->userdata('id'));
        
        $this->db->join('property', $this->_table_name.'.property_id = property.id', 'left');
        
        if(!empty($where))
            $this->db->where($where);
                
        if($limit !== NULL)
            $this->db->limit($limit, $offset);
        
        return parent::get(NULL, FALSE);
    }
    
    public function cancel($id)
    {
        $data = array();
        $data ['date_canceled'] = date('Y-m-d H:i:s');
        
        return parent::save($data, $id);
    }

}



