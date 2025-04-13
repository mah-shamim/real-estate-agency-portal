<?php

class Removedlistings_m extends MY_Model {
    
    protected $_table_name = 'removed_listings';
    protected $_order_by = 'id DESC';

	public function __construct(){
		parent::__construct();

    }
    
    public function is_new_listings($address, $lat, $lng)
    {
        if($this->get_similar($address, $lat, $lng) === NULL)
            return TRUE;
        
        return FALSE;
    }
    
    public function get_similar($address, $lat, $lng, $other=array())
    {
        // delete old removed listings
        $this->db->delete($this->_table_name, array('expire_date <' => date('Y-m-d H:i:s'))); 

        // get similar
        $this->db->select($this->_table_name.'.*');
        $this->db->from($this->_table_name);        
        $this->db->where('address LIKE', "%$address%");
        $this->db->where('expire_date >', date('Y-m-d H:i:s'));
        $this->db->or_where("(lat = $lat AND lng = $lng)");
        $this->db->order_by($this->_table_name.'.id DESC');
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
           return $query->result();
        }
        
        return NULL;
    }


}



