<?php

class Weathercacher_m extends MY_Model {
    
    protected $_table_name = 'weather_cacher';
    protected $_order_by = 'id';
    
    /*
     * Save cache
     * @param property_id int id property
     * @param value mix data for cache
     * @param expire_hours int hours for life cache
     * 
     * return false or id cache 
     */
    public function cache($property_id=NULL, $lang_id=1 , $value=NULL, $weather_api=NULL, $expire_hours = 4)
    {
        if(empty($property_id) || empty($weather_api))
            return false;
        
        $update = false;
        $id=false;

        $cache = $this->db->get_where($this->_table_name, array('property_id'=>$property_id,'lang_id'=>$lang_id,'weather_api' => $weather_api), 1 , $this->_order_by);

        if($cache && $cache->num_rows()>0){
            $update = true;
            $id=$cache->row_array(1)['id'];
        }
        
        //Save new
        if(!$update)
        {
            $data = array(
               'property_id' => $property_id,
               'expire_date' => date('Y-m-d H:i:s', time()+$expire_hours*60*60),
               'value' => trim($value),
               'lang_id' => $lang_id,
               'weather_api' => $weather_api
            );
            
            $this->db->insert($this->_table_name, $data); 
            
            return $this->db->insert_id();
        }
        // Update
        if($update) {
            $data = array(
               'expire_date' => date('Y-m-d H:i:s', time()+$expire_hours*60*60),
               'value' =>trim($value)
            );

            $this->db->where(array('property_id'=>$property_id,'lang_id'=>$lang_id,'weather_api' => $weather_api));
            $this->db->update($this->_table_name, $data);
            
            return $id;
        }
        
        return false;
    }
    
    /*
     * Check expire status
     * @param property_id int property id
     * 
     * return true if expire_date > time now
     *        false if expire_date < time now
     */
    public function check_expire_status($property_id=NULL, $weather_api=NULL, $lang_id=1) {
        if(empty($property_id) || empty($weather_api))
            return false;
        
        $cache = $this->db->get_where($this->_table_name, array('property_id'=>$property_id,'weather_api' => $weather_api, 'expire_date >'=>date('Y-m-d H:i:s'),'lang_id'=>$lang_id), 1 , $this->_order_by);
        if($cache && $cache->num_rows()>0)
            return true;
        
        return false;
    }
    
    public function get_cache($property_id=NULL, $weather_api=null, $lang_id=1) {
        if(empty($property_id) || empty($weather_api))
           return false;
        
        $cache = $this->db->get_where($this->_table_name, array('property_id'=>$property_id,'weather_api' => $weather_api,'lang_id'=>$lang_id), 1 , $this->_order_by);
         
        if($cache && $cache->num_rows()>0){
            return($cache->row_array(1)['value']);
        }
         
        return false;
    }
    
    /* remove all cache
     * 
     */
    public function clear_cache(){
        
        if($this->db->empty_table($this->_table_name))
            return true;
        else
            return false;
    }
    
}



