<?php

class Rates_m extends MY_Model {
    
    protected $_table_name = 'rates';
    protected $_order_by = 'id DESC';
    public $rules = array(
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property', 'rules'=>'trim|required|intval'),
        'language_id' => array('field'=>'language_id', 'label'=>'lang:Language', 'rules'=>'trim|intval'),
        'date_from' => array('field'=>'date_from', 'label'=>'lang:From date', 'rules'=>'trim|required|callback__check_exists|xss_clean'),
        'date_to' => array('field'=>'date_to', 'label'=>'lang:To date', 'rules'=>'trim|required|xss_clean'),
        'min_stay' => array('field'=>'min_stay', 'label'=>'lang:Min stay', 'rules'=>'trim|intval|xss_clean'),
        'changeover_day' => array('field'=>'changeover_day', 'label'=>'lang:Changeover day', 'rules'=>'trim|intval|xss_clean'),
   );
   
   public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
        
        $this->languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
                                  
        //Rules for languages
        foreach($this->languages as $key=>$value)
        {
            $this->rules_lang["rate_nightly_$key"] = array('field'=>"rate_nightly_$key", 'label'=>'lang:Rate nightly', 'rules'=>'trim|xss_clean');
            $this->rules_lang["rate_weekly_$key"] = array('field'=>"rate_weekly_$key", 'label'=>'lang:Rate weekly', 'rules'=>'trim|required|xss_clean');
            $this->rules_lang["rate_monthly_$key"] = array('field'=>"rate_monthly_$key", 'label'=>'lang:Rate monthly', 'rules'=>'trim|xss_clean');
            $this->rules_lang["currency_code_$key"] = array('field'=>"currency_code_$key", 'label'=>'lang:Currency code', 'rules'=>'trim|required|xss_clean');            
        }
	}

    public function get_new()
	{
        $page = new stdClass();
        $page->property_id = 0;
        $page->language_id = 0;
        $page->date_from = date('Y-m-d 12:00:00');
        $page->date_to = date('Y-m-d 12:00:00');
        $page->min_stay = 7;
        $page->changeover_day = 6;
        
        //Add language parameters
        foreach($this->languages as $key=>$value)
        {
            $page->{"rate_nightly_$key"} = '';
            $page->{"rate_weekly_$key"} = '';
            $page->{"rate_monthly_$key"} = '';
            $page->{"currency_code_$key"} = '';
        }
        
        return $page;
	}

    public function get_by_check($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = "")
    {
        $this->db->select($this->_table_name.'.*, property_user.user_id as p_user_id');
        $this->db->from($this->_table_name);
        $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
        
        
        if($this->session->userdata('type') != 'ADMIN')
        {
            $this->db->where('property_user.user_id', $this->session->userdata('id'));
        }
        
        $this->db->order_by($this->_order_by);
        
        if($where !== NULL) $this->db->where($where);
        if($order_by !== NULL) $this->db->order_by($order_by);
        if($limit !== NULL) $this->db->limit($limit, $offset);
        
        if(!empty($search))
        {
            //$this->db->where("(address LIKE '%$search%' OR name_surname LIKE '%$search%')");
        }
          
        $query = $this->db->get();
        
        //echo $this->db->last_query();

        return $query->result();
    }
    
    public function get_lang($id = NULL, $single = FALSE, $lang_id=1, $where = null, $limit = null, $offset = "", $order_by=NULL, $search = '')
    {
        if($id != NULL)
        {
            $result = $this->get($id);
            
            if(empty($result))
                return array();
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('rates_id', $id);
            $lang_result = $this->db->get()->result_array();
            foreach ($lang_result as $row)
            {
                foreach ($row as $key=>$val)
                {
                    $result->{$key.'_'.$row['language_id']} = $val;
                }
            }
            
            foreach($this->languages as $key_lang=>$val_lang)
            {
                foreach($this->rules_lang as $r_key=>$r_val)
                {
                    if(!isset($result->{$r_key}))
                    {
                        $result->{$r_key} = '';
                    }
                }
            }
            
            return $result;
        }
        
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.rates_id');
        $this->db->where('language_id', $lang_id);
        
        if($where != null)
            $this->db->where($where);
            
        if(!empty($search))
        {
            //$this->db->where("(title LIKE '%$search%' OR keywords LIKE '%$search%' OR address LIKE '%$search%')");
        }
        
        if($limit != null)
            $this->db->limit($limit, $offset);
            
        
        if($single == TRUE)
        {
            $method = 'row';
        }
        else
        {
            $method = 'result';
        }
        
        
        if($order_by == NULL)
        {
            if(!sw_count($this->db->ar_orderby))
            {
                $this->db->order_by($this->_order_by);
            }
        }
        else
        {
            $this->db->order_by($order_by);
        }

        
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function save_with_lang($data, $data_lang, $id = NULL)
    {
        // Set timestamps
        if($this->_timestamps == TRUE)
        {
            $now = date('Y-m-d H:i:s');
            $id || $data['date_from'] = $now;
            $id || $data['date_to'] = $now;
        }
        
        //Correct times to 12:00
        if(isset($data['date_from']))
        {
            $data['date_from'] = date('Y-m-d 12:00:00', strtotime($data['date_from']));
        }
        if(isset($data['date_to']))
        {
            $data['date_to'] = date('Y-m-d 12:00:00', strtotime($data['date_to']));
        }

        // Insert
        if($id === NULL)
        {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else
        {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }
        
        // Save lang data
        $this->db->delete($this->_table_name.'_lang', array('rates_id' => $id));
        
        foreach($this->languages as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['language_id'] = $lang_key;
                $curr_data_lang['rates_id'] = $id;
                
                foreach($data_lang as $data_key=>$data_val)
                {
                    $pos = strrpos($data_key, "_");
                    if(substr($data_key,$pos+1) == $lang_key)
                    {
                        $curr_data_lang[substr($data_key,0,$pos)] = $data_val;
                    }
                }

                $this->db->set($curr_data_lang);
                $this->db->insert($this->_table_name.'_lang');
            }
        }

        return $id;
    }
    
    public function is_defined($property_id, $date_from, $date_to, $except_id = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('property_id', $property_id);
        
        if(is_numeric($except_id))
        {
            $this->db->where('id !=', $except_id);
        }
        
        // Check dates availability
        $this->db->where('date_from <', $date_to);
        $this->db->where('date_to >', $date_from);
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }
    
    public function delete($id)
    {
        $this->db->delete('rates_lang', array('rates_id' => $id));         
        parent::delete($id);
    }
    
    public function save($data, $id = NULL)
    {
//TODO: Some code (not tested) to correct times to 12:00
//        if(isset($data['date_from']))
//        {
//            $data['date_from'] = date('Y-m-d 12:00:00', strtotime($data['date_from']));
//        }
//        
//        if(isset($data['date_to']))
//        {
//            $data['date_to'] = date('Y-m-d 12:00:00', strtotime($data['date_to']));
//        }
        
        return parent::save($data, $id);
    }

}


