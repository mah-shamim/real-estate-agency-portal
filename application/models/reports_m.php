<?php

class Reports_m extends MY_Model {
    
    protected $_table_name = 'reports';
    protected $_order_by = 'id ASC';
    
    public $rules = array(
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Estate', 'rules'=>'trim|required|xss_clean'),
        'agent_id' => array('field'=>'agent_id', 'label'=>'lang:Agent', 'rules'=>'trim|xss_clean'),
        'name' => array('field'=>'name', 'label'=>'lang:Name and surname', 'rules'=>'trim|required|xss_clean'),
        'surname' => array('field'=>'surname', 'label'=>'lang:Surname', 'rules'=>'trim|xss_clean'),
        'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|xss_clean'),
        'email' => array('field'=>'email', 'label'=>'lang:Mail', 'rules'=>'trim|required|xss_clean'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|xss_clean'),
        'repository_id' => array('field'=>'repository_id', 'label'=>'lang:repository_id', 'rules'=>'trim|xss_clean'),
        'allow_contact' => array('field'=>'allow_contact', 'label'=>'lang:I allow agent and affilities to contact me', 'rules'=>'trim|required'),
        'date_submit' => array('field'=>'date_submit', 'label'=>'lang:Submit Date', 'rules'=>'trim|required|xss_clean')
    );
    
    public $rules_agent = array(
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Estate', 'rules'=>'trim|required|xss_clean'),
        'agent_id' => array('field'=>'agent_id', 'label'=>'lang:Agent', 'rules'=>'trim|xss_clean'),
        'name' => array('field'=>'name', 'label'=>'lang:Name and surname', 'rules'=>'trim|required|xss_clean'),
        'surname' => array('field'=>'surname', 'label'=>'lang:Surname', 'rules'=>'trim|xss_clean'),
        'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|xss_clean'),
        'email' => array('field'=>'email', 'label'=>'lang:Mail', 'rules'=>'trim|required|xss_clean'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|xss_clean'),
        'repository_id' => array('field'=>'repository_id', 'label'=>'lang:repository_id', 'rules'=>'trim|xss_clean'),
        'allow_contact' => array('field'=>'allow_contact', 'label'=>'lang:I allow agent and affilities to contact me', 'rules'=>'trim|required'),
        'date_submit' => array('field'=>'date_submit', 'label'=>'lang:Submit Date', 'rules'=>'trim|xss_clean')
    );

    public function __construct(){
            parent::__construct();
    }
    
    public function get_new()
	{
        $report = new stdClass();
        $report->date_submit = date('Y-m-d H:i:s');
        $report->name = '';
        $report->phone = '';
        $report->email = '';
        $report->message = '';
        $report->allow_contact = '';
        $report->agent_id = NULL;
        $report->property_id = NULL;
        return $report;
	}
    
    public function get($id = NULL, $single = false)
    {
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->db->select($this->_table_name.'.*, property_user.user_id');
            $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        return parent::get($id,$single);
    }

    public function get_joined($where = null, $limit = null, $order_by = null, $offset = null)
    {
        $this->db->select($this->_table_name.'.*, user.username, property.address');
        $this->db->from($this->_table_name);
        $this->db->join('user', 'user.id = '.$this->_table_name.'.agent_id', 'left');
        $this->db->join('property', 'property.id = '.$this->_table_name.'.property_id');
        
        // fetch property details
//        $this->db->join('property_lang', 'property_lang.property_id = '.$this->_table_name.'.property_id');
//        $this->db->where('property_lang.language_id', 1);
        
        if(!empty($where))
        {
            $this->db->where($where);
        }
        
        if($limit != null || $offset != null)
            $this->db->limit($limit, $offset);
        
        if($order_by == null)
        {
            $this->db->order_by($this->_order_by);
        }
        else
        {
            $this->db->order_by($order_by);
        }
        
        $query = $this->db->get();
        
        if (is_object($query))
        {
            return $query->result();
        }
        
        return array();
    }
    
    /* delete all */
    public function delete_all () {
        $this->db->empty_table($this->_table_name);
    }

}



