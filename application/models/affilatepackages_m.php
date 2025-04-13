<?php

class Affilatepackages_m extends MY_Model {
    
    protected $_table_name = 'affilate_packages';
    protected $_order_by = 'id_affilate_packages';
    public $rules_admin = array(
    );
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        //$item->date_created = date('Y-m-d H:i:s');
        //$item->date_modified = NULL;

        return $item;
	}
    
    public function get_user_treefield($user_id, $treefield_id)
    {
        $item = $this->get_by(array('user_id'=>$user_id, 'treefield_id'=>$treefield_id), TRUE);
        
        return $item;
    }
    
    public function get_users_affilate()
    {
        $listings = $this->get_by(array('date_expire >'=>date('Y-m-d H:i:s')));
        
        $results = array();
        foreach($listings as $listing)
        {
            $results[$listing->treefield_id][$listing->user_id] = $listing;
        }
        
        return $results;
    }
    
    public function get_user_packages($user_id, $default_lang_id)
    {
        $this->db->select('treefield_lang.*');
        $this->db->from($this->_table_name);
        $this->db->join('treefield_lang', $this->_table_name.'.treefield_id = treefield_lang.treefield_id');
        $this->db->where('affilate_packages.user_id', $user_id);
        $this->db->where('treefield_lang.language_id', $default_lang_id);
        $this->db->where($this->_table_name.'.date_expire >', date('Y-m-d H:i:s'));
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
           $results = $query->result();
        
           return $results;
        } 
    }
    
    public function check_user_affilate($user_id, $property_id, $language_id)
    {
        // Get treefield id
        $this->db->select('value');
        $this->db->from('property_value');
        $this->db->where('property_value.option_id', 64);
        $this->db->where('property_value.property_id', $property_id);
        $this->db->where('property_value.language_id', $language_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $treefield_value = $row->value;
            
            if(substr($treefield_value, -2) == ' -')
                $treefield_value = substr($treefield_value, 0, -2);

            $this->db->select('*');
            $this->db->from('treefield_lang');
            $this->db->where('treefield_lang.value_path LIKE', '%'.$treefield_value.'%');
            $this->db->where('treefield_lang.language_id', $language_id);
            $query = $this->db->get();
            
            if ($query->num_rows() > 0)
            {
                $row = $query->row();
                $treefield_id = $row->treefield_id;

                // Check if user is affilate 
                
                $user = $this->get_user_treefield($user_id, $treefield_id);
                if(!empty($user))
                    return true;
            }
        } 

        return false;
    }
    
    public function get_related_affilate($default_lang_id, $county_affiliate_values)
    {
        if(substr($county_affiliate_values, -2) == ' -')
            $county_affiliate_values = substr($county_affiliate_values, 0, -2);
        
        $this->db->select('user.*');
        $this->db->from($this->_table_name);
        $this->db->join('treefield_lang', $this->_table_name.'.treefield_id = treefield_lang.treefield_id');
        $this->db->join('user', $this->_table_name.'.user_id = user.id');
        $this->db->where('treefield_lang.language_id', $default_lang_id);
        $this->db->where('treefield_lang.value_path LIKE', $county_affiliate_values.'%');
        $this->db->where($this->_table_name.'.date_expire >', date('Y-m-d H:i:s'));
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
        
           return $row;
        } 
        
        //$sql = $this->db->last_query();
        //exit($sql);
        
        return NULL;
    }
    
    public function delete($id)
    {      
        parent::delete($id);
    }

}


