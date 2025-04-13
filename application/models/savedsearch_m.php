<?php

class Savedsearch_m extends MY_Model {
    
    protected $_table_name = 'saved_search';
    protected $_order_by = 'id';
    public $rules_admin = array(
        'activated' => array('field'=>'activated', 'label'=>'lang:Activated', 'rules'=>'trim'),
        'user_id' => array('field'=>'user_id', 'label'=>'lang:User', 'rules'=>'trim|required'),
    );
    
    public $rules_treealerts = array(
        'delivery_frequency_h' => array('field'=>'delivery_frequency_h', 'label'=>'lang:Frequency of the delivery', 'rules'=>'trim|required')
    );
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        $item->date_last_informed = date('Y-m-d H:i:s');
        $item->date_created = date('Y-m-d H:i:s');
        $item->activated = 0;
        $item->user_id = 'NULL';
        $item->parameters = '';
        $item->delivery_frequency_h = '';
        $item->lang_code = '';
        
        return $item;
	}
    
    public function get_new_array()
	{
        $item = array();
        $item['date_last_informed'] = date('Y-m-d H:i:s');
        $item['date_created'] = date('Y-m-d H:i:s');
        $item['activated'] = 1;
        $item['user_id'] = 'NULL';
        $item['parameters'] = '';
        $item['lang_code'] = '';
        
        return $item;
	}
    
    public function delete($id)
    {      
        parent::delete($id);
    }
    
    public function get_by_search($search = array())
    {
        if(sw_count($search) == 0) return array();
        
        $this->db->select('user.*, '.$this->_table_name.'.parameters');
        $this->db->from($this->_table_name);
        $this->db->join('user', $this->_table_name.'.user_id = user.id');
        
        foreach($search as $val)
        {
            $this->db->or_where($this->_table_name.'.parameters LIKE', '%'.$val.'%');
        }
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }
    
    public function check_if_exists($user_id, $parameters, $lang_code='')
    {
        $query = $this->db->get_where($this->_table_name, array('user_id'    => $user_id, 
                                                                'parameters' => $parameters,
                                                                'lang_code'  => $lang_code  ));
        
        return $query->num_rows();
    }

}


