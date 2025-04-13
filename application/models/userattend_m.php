<?php

class Userattend_m extends MY_Model {
    
    protected $_table_name = 'user_attend_listing';
    protected $_order_by = 'id';
    public $rules_admin = array();
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}
    
    public function delete($id)
    {
        parent::delete($id);
    }
    
    public function check_notattend($listing_id, $user_id)
    {
        if(empty($user_id))return true;
        
        $this->db->where('user_id', $user_id); 
        $this->db->where('listing_id', $listing_id); 
        $this->db->from($this->_table_name);
        $query = $this->db->get();
        
        if($query->num_rows() == 0)
            return true;
            
        return false;
    }

    public function get_joined($where = null, $limit = null, $order_by = null, $offset = null)
    {
        $this->db->select('user_attend_listing.*, user.username, property.address, property_lang.*');
        $this->db->from($this->_table_name);
        $this->db->join('user', 'user.id = '.$this->_table_name.'.user_id');
        $this->db->join('property', 'property.id = '.$this->_table_name.'.listing_id');
        $this->db->join('property_lang', 'property.id = property_lang.property_id');
        
        
        // fetch property details
//        $this->db->join('property_lang', 'property_lang.property_id = '.$this->_table_name.'.property_id');
        //$this->db->where('property_lang.language_id', 1);
        
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


