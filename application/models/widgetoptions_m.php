<?php

class Widgetoptions_m extends MY_Model {
    
    protected $_table_name = 'widget_options';
    protected $_order_by = 'id';
    public $rules = array(
        'parent_id' => array('field'=>'parent_id', 'label'=>'lang:Category', 'rules'=>'trim|intval|callback_parent_check'),
        'page_id' => array('field'=>'page_id', 'label'=>'lang: Page id', 'rules'=>'trim|intval'),
        'language_id' => array('field'=>'language_id', 'label'=>'lang:Language', 'rules'=>'trim|intval'),
        'template' => array('field'=>'template', 'label'=>'lang:Template', 'rules'=>'trim|required|xss_clean'),
        'widget_name' => array('field'=>'widget_name', 'label'=>'lang:Widget`s name', 'rules'=>'trim|required|xss_clean'),
        'option_name' => array('field'=>'option_name', 'label'=>'lang:Option`s name', 'rules'=>'trim|required|xss_clean'),
   );
   
    public $rules_lang = array();
   
    public function __construct(){
		parent::__construct();
        
        $this->languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
                                  
        //Rules for languages
        foreach($this->languages as $key=>$value)
        {
            $this->rules_lang["value_$key"] = array('field'=>"value_$key", 'label'=>'lang:Value', 'rules'=>'trim|xss_clean');
            
        }
    }

    public function get_new()
	{
        $page = new stdClass();
        $page->page_id = 0;
        $page->language_id = 0;
        $page->widget_name = '';
        $page->option_name = '';
        $page->template = '';
        
        //Add language parameters
        foreach($this->languages as $key=>$value)
        {
            $page->{"value_$key"} = '';
        }
        
        return $page;
    }
    
    public function get_widget_options_lang($widget_name = NULL, $template = NULL)
    {
        if($widget_name === NULL ) return false;
        
            $result = $this->get_widget_options($widget_name, $template);
            if(!$result) return false;
            
            $return = array();
            foreach($result as $val)
            {
                $return[$val->option_name.'_'.$val->page_id.'_'.$val->language_id] = $val->value;
            }
            
            return $return;
    }
    
    public function get_widget_options ($widget_name = NULL, $template = NULL)
    {

        $this->db->select($this->_table_name.'.*, '.$this->_table_name.'_lang.language_id, '.$this->_table_name.'_lang.widget_options_id, '.$this->_table_name.'_lang.value');

        $this->db->join($this->_table_name.'_lang',  $this->_table_name.'_lang.widget_options_id = '.$this->_table_name.'.id');

        $this->db->where('widget_name', $widget_name);
        $this->db->where('template', $template);
        $query= $this->db->get($this->_table_name);

        $result_count = array();

        if($query->num_rows() > 0 && $query){
             return$query->result();
        }
        
        return false;
    }
    
    public function get_option_page_lang ($option_name, $widget_name, $page_id, $template) {
        
        if($widget_name === NULL ) return false;
        
            $result = $this->get_option_page ($option_name, $widget_name, $page_id, $template);
            
            if(!$result) return false;
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('widget_options_id', $result->id);
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
    
    public function get_option_page ($option_name, $widget_name, $page_id, $template) {
        $this->db->select('*');

        $this->db->where('option_name', $option_name);
        $this->db->where('widget_name', $widget_name);
        $this->db->where('page_id', $page_id);
        $this->db->where('template', $template);
        $query= $this->db->get($this->_table_name);

        $result_count = array();

        if($query->num_rows() > 0 && $query){
             return $query->row();
        }
        
        return false;
    }
    
    

    public function get_lang($id = NULL, $single = FALSE, $lang_id=1, $where = null, $limit = null, $offset = "", $order_by=NULL, $search = '')
    {
        if($id != NULL)
        {
            $result = $this->get($id);
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('widget_options_id', $id);
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
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.showroom_id');
        $this->db->where('language_id', $lang_id);
        
        if($where != null)
            $this->db->where($where);
            
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
    
    public function save_option_with_lang($data, $data_lang, $option_name = NULL, $widget_name = NULL, $page_id = NULL, $template = NULL)
    {
        
        // Insert
        $id = $this->get_option_page ($option_name, $widget_name, $page_id, $template);

        if(empty($id))
        {
            
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
        $this->db->delete($this->_table_name.'_lang', array('widget_options_id' => $id));
        
        foreach($this->languages as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['language_id'] = $lang_key;
                $curr_data_lang['widget_options_id'] = $id;
                
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
    
    public function delete($id)
    {
        $this->db->delete('widget_options_lang', array('widget_options_id' => $id)); 
        
        
        parent::delete($id);
    }

}


