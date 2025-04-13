<?php

class Claim_m extends MY_Model {
    
    protected $_table_name = 'claim';
    protected $_order_by = 'id ASC';
    
    public $rules = array(
        'model' => array('field'=>'model', 'label'=>'lang:Model', 'rules'=>'trim|required|xss_clean'),
        'model_id' => array('field'=>'model_id', 'label'=>'lang:Model_id', 'rules'=>'trim|required|xss_clean'),
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
        'model' => array('field'=>'model', 'label'=>'lang:Model', 'rules'=>'trim|required|xss_clean'),
        'model_id' => array('field'=>'model_id', 'label'=>'lang:Model_id', 'rules'=>'trim|required|xss_clean'),
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
        $report->model = '';
        $report->model_id = '';
        $report->surname = '';
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
    
    /* delete all */
    public function delete_all () {
        $this->db->empty_table($this->_table_name);
    }

}



