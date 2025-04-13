<?php

class Language_m extends MY_Model {
    
    protected $_table_name = 'language';
    protected $_order_by = 'is_default DESC, id';

    public $rules_admin = array(
        'code' => array('field'=>'code', 'label'=>'lang:Code', 'rules'=>'trim|required|xss_clean|alpha|strtolower|max_length[4]'),
        'facebook_lang_code' => array('field'=>'facebook_lang_code', 'label'=>'lang:Facebook lang code', 'rules'=>'trim|xss_clean'),
        'language' => array('field'=>'language', 'label'=>'lang:Language', 'rules'=>'trim|required|xss_clean|alpha|strtolower'),
        'is_default' => array('field'=>'is_default', 'label'=>'lang:Default', 'rules'=>'trim'),
        'is_frontend' => array('field'=>'is_frontend', 'label'=>'lang:Default', 'rules'=>'trim'),
        //'is_hidden_submission' => array('field'=>'is_hidden_submission', 'label'=>'lang:Hidden on submission', 'rules'=>'trim'),
        'is_rtl' => array('field'=>'is_rtl', 'label'=>'lang:Default', 'rules'=>'trim'),
        'currency_default' => array('field'=>'currency_default', 'label'=>'lang:Currency', 'rules'=>'trim'),
        'domain' => array('field'=>'domain', 'label'=>'lang:Custom domain', 'rules'=>'trim')
    );

    public $backend_languages = array('hr'=>'Croatian', 'en'=>'English');
    public $db_languages_code = array();
    public $db_languages_code_obj = array();
    public $db_languages_id = array();
    
	public function __construct(){
		parent::__construct();
        
        $this->backend_languages = array();
        
        $langDirectory = opendir(APPPATH.'language');
        // get each lang
        while($langName = readdir($langDirectory)) {
            if ($langName != "." && $langName != "..") {
                $this->backend_languages[$langName] = lang($langName)==''?$langName:lang($langName);
            }
        }
        
        $langs = $this->get();
        foreach($langs as $row)
        {
            $this->db_languages_id[$row->id] = $row->code;
            $this->db_languages_code[$row->code] = $row->id;
            $this->db_languages_code_obj[$row->code] = $row;
        }
	}
    
    public function get_new()
	{
        $language = new stdClass();
        $language->code = '';
        $language->language = '';
        $language->is_default = 0;
        $language->is_frontend = 1;
        $language->facebook_lang_code = '';
        //$language->is_hidden_submission = 0;
        $language->is_rtl = 0;
        $language->currency_default = 'USD';
        return $language;
	}
    
    public function get_content_lang()
    {
        $query = $this->db->get_where($this->_table_name, array('language' => $this->config->item('language')), 1);
        
        if ($query->num_rows() > 0)
        {
            return $query->row()->id;
        }
        else
        {
            $query = $this->db->get_where($this->_table_name, array('is_default' => 1), 1);
            if ($query->num_rows() > 0)
                return $query->row()->id;
            else 
                return NULL;
        }

        return 2;
    }
    
    public function get_default()
    {
        if(($lang_default_code = $this->cache_temp_load('lang_default_code')) === FALSE)
        {
            $query = $this->db->get_where($this->_table_name, array('is_default' => 1, 'is_frontend'=>1), 1);
            if($query && $query->num_rows()>0)
            {
                $lang_default_code = $query->row()->code;
            }
            else
            {
                $query = $this->db->get_where($this->_table_name, array('is_default'=>1), 1);
                if($query && $query->num_rows()>0)
                {
                    $lang_default_code = $query->row()->code;
                }
                else
                {
                    $query = $this->db->get_where($this->_table_name, array('is_frontend'=>1), 1);
                    if($query && $query->num_rows()>0)
                    {
                        $lang_default_code = $query->row()->code;
                    }
                }
            }
            
            if(empty($lang_default_code))$lang_default_code = 'en';
            
            $this->cache_temp_save($lang_default_code, 'lang_default_code');
        }

        return $lang_default_code;
    }
        
    public function get_default_id()
    {
        if(($lang_default_id = $this->cache_temp_load('lang_default_id')) === FALSE)
        {
            $query = $this->db->get_where($this->_table_name, array('is_default' => 1, 'is_frontend'=>1), 1);
            if($query && $query->num_rows()>0)
            {
                $lang_default_id = $query->row()->id;
            }
            else
            {
                $query = $this->db->get_where($this->_table_name, array('is_frontend'=>1), 1);
                if($query && $query->num_rows()>0)
                {
                    return $query->row()->id;
                }
                
                $query = $this->db->get_where($this->_table_name, array('is_default'=>1), 1);
                if($query && $query->num_rows()>0)
                {
                    return $query->row()->id;
                }
                else
                {
                    $query = $this->db->get_where($this->_table_name, NULL, 1);
                    if($query && $query->num_rows()>0)
                    {
                        return $query->row()->id;
                    }
                }
            }
            if(!isset($lang_default_id))$lang_default_id = FALSE;
            
            $this->cache_temp_save($lang_default_id, 'lang_default_id');
        }
        
        return $lang_default_id;
    }
    
    public function count_visible($ignore_id = FALSE)
    {
        $query = $this->db->get_where($this->_table_name, array('is_frontend' => 1, 'id !='=>$ignore_id));
        return sw_count($query->result());
    }
    
    public function get_id($code)
    {
        if(isset($this->db_languages_code[$code]))
        {
            return $this->db_languages_code[$code];
        }
        
        $query = $this->db->get_where($this->_table_name, array('code' => $code), 1);
        
        if($query && $query->num_rows()>0)
            return $query->row()->id;
    }
    
    public function get_code($id)
    {
        if(isset($this->db_languages_id[$id]))
        {
            return $this->db_languages_id[$id];
        }
        
        $query = $this->db->get_where($this->_table_name, array('id' => $id), 1);
        return $query->row()->code;
    }
    
    public function get_name($code)
    {
        if(is_numeric($code))
        {
            if(isset($this->db_languages_id[$code]))
            {
                return $this->db_languages_code_obj[$this->db_languages_id[$code]]->language;
            }
            
            $query = $this->db->get_where($this->_table_name, array('id' => $code), 1);
        }
        else
        {
            if(isset($this->db_languages_code_obj[$code]))
            {
                return $this->db_languages_code_obj[$code]->language;
            }
            
            $query = $this->db->get_where($this->_table_name, array('code' => $code), 1);
        }
        
        if($query->num_rows() > 0)
            return $query->row()->language;
            
        return NULL;
    }
    
//    public function get($id = NULL, $single = FALSE)
//    {
//        if($id == NULL)
//        {
//            if(($language_get = $this->cache_temp_load('language_get')) === FALSE)
//            {
//                $language_get = parent::get($id, $single);
//                
//                $this->cache_temp_save($language_get, 'language_get');
//            }
//        }
//        else
//        {
//            $language_get = parent::get($id, $single);
//        }
//
//
//        return $language_get;
//    }

    
    public function get_form_dropdown($column, $where = FALSE, $empty=TRUE, $show_id=FALSE)
    {
        if(($get_form_dropdown = $this->cache_temp_load("get_form_dropdown_$column".intval($empty))) === FALSE)
        {
            $get_form_dropdown = parent::get_form_dropdown($column, $where, $empty, $show_id);
            $this->cache_temp_save($get_form_dropdown, "get_form_dropdown_$column".intval($empty));
        }
        
        return $get_form_dropdown;
    }
    
    public function save($data, $id = NULL)
    {
        if($data['is_default'] == '1')
        {
            $this->db->set(array('is_default'=>'0'));
            $this->db->update($this->_table_name);
        }
        
        return parent::save($data, $id);
    }
    
    public function delete($id)
    {
        $this->db->where('language_id', $id);
        $this->db->delete('page_lang');
        
        $this->db->where('language_id', $id);
        $this->db->delete('property_value');
        
        $this->db->where('language_id', $id);
        $this->db->delete('property_lang');
        
        $this->db->where('language_id', $id);
        $this->db->delete('option_lang');
        
        $this->db->where('language_id', $id);
        $this->db->delete('showroom_lang');
    
        $this->db->where('language_id', $id);
        $this->db->delete('qa_lang');
        
        $this->db->where('language_id', $id);
        $this->db->delete('rates_lang');
        
        return parent::delete($id);
    }

}



