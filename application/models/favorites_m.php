<?php

class Favorites_m extends MY_Model {
    
    protected $_table_name = 'favorites';
    protected $_order_by = 'id';
    public $rules_admin = array(
        'user_id' => array('field'=>'user_id', 'label'=>'lang:User', 'rules'=>'trim|required'),
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property', 'rules'=>'trim|required'),
        'lang_code' => array('field'=>'lang_code', 'label'=>'lang:Lang code', 'rules'=>'trim'),
    );
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        $item->date_saved = date('Y-m-d H:i:s');
        $item->user_id = NULL;
        $item->property_id = NULL;
        $item->lang_code = '';
        
        return $item;
	}
    
    public function get_new_array()
	{
        $item = array();
        $item['date_saved'] = date('Y-m-d H:i:s');
        $item['user_id'] = NULL;
        $item['property_id'] = NULL;
        $item['lang_code'] = '';
        
        return $item;
	}
    
    public function delete($id)
    {      
        parent::delete($id);
    }
    
    public function check_if_exists($user_id, $property_id)
    {
        $query = $this->db->get_where($this->_table_name, array('user_id'   => $user_id, 
                                                                'property_id'=>$property_id));
        return $query->num_rows();
    }
    
    public function get_joined($where = null, $limit = null, $order_by = null, $offset = null)
    {
        $this->db->select('favorites.*, user.username, property.address');
        $this->db->from($this->_table_name);
        $this->db->join('user', 'user.id = '.$this->_table_name.'.user_id');
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

}


