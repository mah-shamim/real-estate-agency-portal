<?php

class Token_m extends MY_Model {
    
    protected $_table_name = 'token_api';
    protected $_order_by = 'id';
    
    public function get_token($POST)
    {
        $this->db->select('user_id');
        $this->db->from($this->_table_name);
        $this->db->where('token', $POST['token']);
        $query = $this->db->get();
        
        if (is_object($query))
        {
            $row = $query->row();
            return $row;
        }
        
        return false;
    }
    
}



