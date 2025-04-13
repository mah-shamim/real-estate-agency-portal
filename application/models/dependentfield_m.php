<?php

class Dependentfield_m extends MY_Model {
    
    protected $_table_name = 'dependent_field';
    protected $_primary_key = 'id_dependent_field';
    protected $_order_by = 'dependent_field.id_dependent_field';
    
    public $rules = array(
        'field_id' => array('field'=>'field_id', 'label'=>'lang:Dependent field', 'rules'=>'trim|required|xss_clean|callback__checkavailable'),
        'selected_index' => array('field'=>'selected_index', 'label'=>'lang:Selected index', 'rules'=>'trim|xss_clean'),
        'hidden_fields_list' => array('field'=>'visible', 'label'=>'lang:Hidden fields under selected', 'rules'=>'trim|xss_clean')
    );
    

	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        $item->field_id = '';
        $item->selected_index = '';
        $item->hidden_fields_list = '';
        
        return $item;
	}
    
    public function get_detailed($lang_id)
    {
        $this->db->join('option_lang', 'dependent_field.field_id = option_lang.option_id');
        $this->db->where('language_id', $lang_id); 
        
        
        return parent::get();
    }
    
    public function get_available_fields($lang_id, $empty=NULL)
    {
        $this->db->select('*');
        $this->db->from('option');
        $this->db->join('option_lang', 'option.id = option_lang.option_id');
        $this->db->where('language_id', $lang_id);
        
        if(config_db_item('dependent_treefield') === TRUE || TRUE)
        {
            $this->db->where_in('type', array('DROPDOWN', 'TREE'));
        }
        else
        {
            $this->db->where('type', 'DROPDOWN'); 
        }
        
        $this->db->where('is_frontend', 1); 
        $this->db->order_by('type, option'); 
        $query = $this->db->get();
        
        $available_fields = array();
        
        if($empty !== NULL)
        $available_fields[''] = $empty;
        
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $available_fields[$row->id] = "[$row->type] ".$row->option;
            }
        } 
        return $available_fields;
    }
    
    public function get_fields_under($order, $lang_id)
    {
        $this->db->select('*');
        $this->db->from('option');
        $this->db->join('option_lang', 'option.id = option_lang.option_id');
        $this->db->where('language_id', $lang_id); 
        if(is_numeric($order))
            $this->db->where('order >', $order); 
        $this->db->where('is_frontend', 1); 
        $this->db->order_by('order'); 
        $query = $this->db->get();
        
        $results_array = array();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $results_array[$row->id] = $row;
            }
        } 
        
        return $results_array;
    }
    
    public function get_field_values($lang_id, $field_id, $empty=NULL)
    {
        $limit = NULL;
        $offset = NULL;
        
        $query = $this->db->get_where('option_lang', array('language_id' => $lang_id, 'option_id' => $field_id), $limit, $offset);
        
        $values = array();
        
        if(!empty($empty))
            $values[''] = $empty;
        
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $val_exp = explode(',', $row->values);
            
            if(sw_count($val_exp) > 0)
            {
                foreach($val_exp as $key=>$value)
                {
                    $values[$key] = $value;
                }
            }
        } 
        
        return $values;
    }

    public function delete($id)
    {
        if($this->session->userdata('type') == 'ADMIN'){
            $this->_remove_cache();
            parent::delete($id);
        }
    }

    public function save($data, $id=NULL)
    {
        $this->_remove_cache();
        return parent::save($data, $id);
    }
    
    public function _remove_cache(){
        $this->load->model('settings_m');
        $template_name = $this->settings_m->get_field('template');
        $cache_file_name = FCPATH.'templates/'.$template_name.'/assets/cache/_generate_dependentfields.js';
        if(file_exists($cache_file_name))
            unlink($cache_file_name);
    }
    
}



