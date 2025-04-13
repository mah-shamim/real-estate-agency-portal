<?php

class Cacher_m extends MY_Model {
    
    protected $_table_name = 'cacher';
    protected $_order_by = 'id';
    
    private $_cache_ram = array();

    public function cache($index, $value, $expire_days = 3000)
    {
        //hash and check if exists
        $index_hash = md5(strtolower($index));
        
        $update = false;
        $this->db->where('index_hash', $index_hash);
        $q = $this->db->get($this->_table_name);
        if ( $q->num_rows() > 0 )
            $update = true;
        
        //Save
        if(!$update)
        {
            $data = array(
               'index_hash' => $index_hash,
               'expire_date' => date('Y-m-d H:i:s', time()+$expire_days*24*60*60),
               'index_real' => $index,
               'value' => str_replace("'", "\'", serialize($value))
            );
            
            $this->db->insert($this->_table_name, $data); 
        }
        
        return !$update;
    }

    public function cache_update($index, $value, $expire_days = 3000)
    {
        //hash and check if exists
        $index_hash = md5(strtolower($index));
        
        $update = false;
        $this->db->where('index_hash', $index_hash);
        $q = $this->db->get($this->_table_name);
        if ( $q->num_rows() > 0 )
            $update = true;
        
        //Save
        if(!$update)
        {
            $data = array(
               'index_hash' => $index_hash,
               'expire_date' => date('Y-m-d H:i:s', time()+$expire_days*24*60*60),
               'index_real' => $index,
               'value' => serialize($value)
            );
            
            $this->db->insert($this->_table_name, $data); 
        }
        
        // Update
        if($update) {
            $data = array(
               'expire_date' => date('Y-m-d H:i:s', time()+$expire_days*24*60*60),
               'value' =>serialize($value)
            );

            $this->db->where(array('index_real'=>$index));
            $this->db->update($this->_table_name, $data);
            
        }
        
        return !$update;
    }
    
    public function load($index)
    {
        $index_hash = md5(strtolower($index));

        // If exist in ram, auto load by ram
        if(isset($this->_cache_ram[$index_hash]))
            return $this->_cache_ram[$index_hash];
        
        // Check if exists
        $this->db->where('index_hash', $index_hash);
        $query = $this->db->get($this->_table_name);
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
           $real_data = @unserialize($row->value);

           if(strtotime($row->expire_date) > time() && $real_data !== FALSE)
           {
                $this->_cache_ram[$index_hash] = $real_data;
                return $real_data;
           }
           else
           {
                $this->db->where('expire_date >', date('Y-m-d H:i:s', time()));
                $this->db->delete($this->_table_name);
           }
        } 

        return FALSE;
    }


}



