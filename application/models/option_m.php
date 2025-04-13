<?php

class Option_m extends MY_Model {
    
    protected $_table_name = 'option';
    protected $_order_by = 'option.order, option.id';
    
    public $rules = array(
        'parent_id' => array('field'=>'parent_id', 'label'=>'lang:parent', 'rules'=>'trim|required|xss_clean'),
        'type' => array('field'=>'type', 'label'=>'lang:Type', 'rules'=>'trim|required|xss_clean'),
        'visible' => array('field'=>'visible', 'label'=>'lang:Visible in table', 'rules'=>'trim|xss_clean'),
        'max_length' => array('field'=>'max_length', 'label'=>'lang:Max length', 'rules'=>'trim|is_natural|xss_clean'),
        'is_locked' => array('field'=>'is_locked', 'label'=>'lang:Locked', 'rules'=>'trim|xss_clean'),
        'is_frontend' => array('field'=>'is_frontend', 'label'=>'lang:Visible in frontend', 'rules'=>'trim|xss_clean'),
        'is_required' => array('field'=>'is_required', 'label'=>'lang:Required', 'rules'=>'trim|xss_clean|callback__validation_required'),
        'is_quickvisible' => array('field'=>'is_quickvisible', 'label'=>'lang:Visible in quick submission', 'rules'=>'trim|xss_clean'),
        'is_not_translatable' => array('field'=>'is_not_translatable', 'label'=>'lang:Not translatable', 'rules'=>'trim|xss_clean'),
        'columns_number' => array('field'=>'columns_number', 'label'=>'lang:Multiple column', 'rules'=>'trim|xss_clean'),
    );
    
    public $rules_lang = array();
   
    public $option_types = array('CATEGORY', 'CHECKBOX', 'INPUTBOX', 'TEXTAREA', 'DROPDOWN', 'TREE', 'UPLOAD', 'DECIMAL', 'INTEGER', 'HTMLTABLE', 'DROPDOWN_MULTIPLE');
    public $option_type_color = array('CATEGORY'=>'danger', 'CHECKBOX'=>'success', 'INPUTBOX'=>'success', 'DROPDOWN'=>'success', 
                                      'TEXTAREA'=>'success', 'TREE'=>'warning', 'UPLOAD'=>'info', 'DECIMAL'=>'success', 
                                      'INTEGER'=>'success', 'HTMLTABLE'=>'info', 'PEDIGREE'=>'info', 'DROPDOWN_MULTIPLE'=>'warning', 'DATETIME'=>'info','SCHEDULING_TABLE'=>'info');

	public function __construct(){
		parent::__construct();
                    
        $this->columns_number = array(''=>lang_check('Select columns'), 
                                    '1'=>1,
                                    '2'=>2,
                                    '3'=>3,
                                    );
        
        $this->load->model('language_m');
        $this->languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
        
        $this->option_types = array('CATEGORY'=>lang('CATEGORY'), 'CHECKBOX'=>lang('CHECKBOX'), 'INPUTBOX'=>lang('INPUTBOX'), 'HTMLTABLE' => lang_check('HTMLTABLE'),
                                    'DROPDOWN'=>lang('DROPDOWN'), 'TEXTAREA'=>lang('TEXTAREA'), 'TREE'=>lang_check('TREE'), 'UPLOAD'=>lang_check('UPLOAD'), 
                                    'DECIMAL'=>lang_check('DECIMAL'), 'INTEGER'=>lang_check('INTEGER'), 'PEDIGREE'=>lang_check('PEDIGREE'), 'DROPDOWN_MULTIPLE'=>lang_check('DROPDOWN_MULTIPLE'), 'DATETIME'=>  lang_check('DATETIME'),
                                    'SCHEDULING_TABLE'=>  lang_check('SCHEDULING TABLE'));
        
        if(config_item('tree_field_enabled') === FALSE)
        {
            unset($this->option_types['TREE']);
        }
        
        if(config_item('enable_numeric_input') === FALSE)
        {
            unset($this->option_types['DECIMAL'], $this->option_types['INTEGER']);
        }
        
        if(config_item('enable_table_input') === FALSE)
        {
            unset($this->option_types['HTMLTABLE']);
        }
        
        if(config_item('enable_pedigree_input') === FALSE)
        {
            unset($this->option_types['PEDIGREE']);
        }
        
        if(config_item('field_dropdown_multiple_enabled') === FALSE)
        {
            unset($this->option_types['DROPDOWN_MULTIPLE']);
        }

        if(config_item('field_datetime_enabled') === FALSE)
        {
            unset($this->option_types['DATETIME']);

        }

        if(config_item('enable_field_scheduling_table') === FALSE)
        {
            unset($this->option_types['SCHEDULING_TABLE']);

        }
        
        //Rules for languages
        foreach($this->languages as $key=>$value)
        {
            $this->rules_lang["values_$key"] = array('field'=>"values_$key", 'label'=>'lang:Values', 'rules'=>'trim|callback_values_correction|callback_values_dropdown_check|xss_clean');
            $this->rules_lang["suffix_$key"] = array('field'=>"suffix_$key", 'label'=>'lang:Suffix', 'rules'=>'trim|xss_clean');
            $this->rules_lang["prefix_$key"] = array('field'=>"prefix_$key", 'label'=>'lang:Prefix', 'rules'=>'trim|xss_clean');
            $this->rules_lang["option_$key"] = array('field'=>"option_$key", 'label'=>'lang:Option name', 'rules'=>'trim|required|required|xss_clean');
            $this->rules_lang["hint_$key"] = array('field'=>"hint_$key", 'label'=>'lang:Hint', 'rules'=>'trim|xss_clean');
        }
	}

    public function get_new()
	{
        $option = new stdClass();
        $option->parent_id = 0;
        $option->type = 'checkbox';
        $option->visible = false;
        $option->is_locked = 0;
        $option->is_frontend = 1;
        $option->is_required = 0;
        $option->is_quickvisible = 0;
        $option->is_not_translatable = 0;
        $option->max_length = NULL;
        $option->columns_number = NULL;
        
        //Add language parameters
        foreach($this->languages as $key=>$value)
        {
            $option->{"values_$key"} = '';
            $option->{"option_$key"} = '';
            $option->{"suffix_$key"} = '';
            $option->{"prefix_$key"} = '';
            $option->{"hint_$key"} = '';
        }
        
        return $option;
	}

    public function get_no_parents($lang_id = 2, $curr_id = NULL)
	{
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.id, option');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.option_id');
        $this->db->where('parent_id', 0);
        if(isset($curr_id))
            $this->db->where('id !=', $curr_id);
        $this->db->where('type', 'category');
        $this->db->where('language_id', $lang_id);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $options = $query->result();

        // Return key => value pair array
        $array = array(0 => lang('No parent'));
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $array[$option->id] = $option->option;
            }
        }
        
        return $array;
	}
    
    public function get_visible($lang_id=1)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.option_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where('visible', '1');
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    //ALTER TABLE `property_lang` ADD `field_40_int` INT NULL ;
    public function numeric_field_range($id, $type='INTEGER')
    {
        $true_false = false;
        
        if($type=='DATETIME') {
            if($this->session->userdata('type') == 'ADMIN')
            {
                $true_false = $this->db->simple_query("ALTER TABLE `property_lang` ADD `field_".$id."_int` datetime NULL ;");
            }

            $property_lang = $this->estate_m->get_table_name().'_lang';
            $property_value = $this->estate_m->get_table_name().'_value';

            $count_rows =  $this->db->count_all_results($property_lang);
            if($count_rows<= 20000){
                $this->db->query("UPDATE `".$property_lang."` 
                    INNER JOIN `".$property_value."`
                    ON (`".$property_value."`.`property_id` = `".$property_lang."`.`property_id` and `".$property_value."`.`language_id` = `".$property_lang."`.`language_id` )
                    SET `".$property_lang."`.`field_".$id."_int`= `".$property_value."`.`value`
                    WHERE `".$property_value."`.`option_id` = ".$id."
                ");
            }
        }
        else {
            if($this->session->userdata('type') == 'ADMIN')
            {
                $true_false = $this->db->simple_query("ALTER TABLE `property_lang` ADD `field_".$id."_int` INT NULL ;");
            }

            $property_lang = $this->estate_m->get_table_name().'_lang';
            $property_value = $this->estate_m->get_table_name().'_value';

            $count_rows =  $this->db->count_all_results($property_lang);
            if($count_rows<= 20000){
                $this->db->query("UPDATE `".$property_lang."` 
                    INNER JOIN `".$property_value."`
                    ON (`".$property_value."`.`property_id` = `".$property_lang."`.`property_id` and `".$property_value."`.`language_id` = `".$property_lang."`.`language_id` )
                    SET `".$property_lang."`.`field_".$id."_int`= `".$property_value."`.`value`
                    WHERE `".$property_value."`.`option_id` = ".$id." and `".$property_value."` .`value`  REGEXP '^[[:digit:]]+$'
                ");
            }
        }
        return $true_false;
    }
    
    public function get_options($lang_id=1, $option_id = array(), $property_id = array(), $estate_db_results = NULL)
    {
        $this->db->where('language_id', $lang_id);
        
        if(sw_count($option_id) > 0)
        {
            $this->db->where_in('option_id', $option_id);
        }
        
        if(sw_count($property_id) > 0)
        {
            $this->db->where_in('property_id', $property_id);
        }
        
        if($estate_db_results !== NULL && sw_count($estate_db_results) == 0)
        {
            $this->db->get('property_value');
            return array();
        }
        
        if(isset($estate_db_results[0]))
        {
            $where_in = array();
            if(is_object($estate_db_results[0]))
            {
                foreach($estate_db_results as $key=>$listing)
                {
                    $where_in[] = $listing->id;
                }
            }
            else
            {
                foreach($estate_db_results as $key=>$listing)
                {
                    $where_in[] = $listing['id'];
                }
            }
            
            $this->db->where_in('property_id', $where_in);
        }
        
        $query = $this->db->get('property_value');
        
        $data = array();
        foreach($query->result() as $key=>$option)
        {
            $data[$option->property_id][$option->option_id] = $option->value;
        }

        return $data;
    }
    
    public function get_property_value($language_id, $property_id, $option_id)
    {
        $query = $this->db->get_where('property_value', array('language_id'=>$language_id,
                                                                        'property_id'=>$property_id,
                                                                        'option_id'=>$option_id), 1);
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
           return $row->value;
        }                                          
                                                                        
        return NULL;                                                             
    }
    
    public function get_lang($id = NULL, $single = FALSE, $lang_id=1)
    {
        if($id != NULL)
        {
            $result = $this->get($id);
            
            if(empty($result)) return false;
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('option_id', $id);
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
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.option_id');
        $this->db->where('language_id', $lang_id);
        
        if($single == TRUE)
        {
            $method = 'row';
        }
        else
        {
            $method = 'result';
        }
        
        if(!sw_count($this->db->ar_orderby))
        {
            $this->db->order_by($this->_order_by);
        }
        
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function get_typeahead($q, $limit=8, $option_ids=array(5,7,40), $lang_id=1)
    {
        $results = array();
        
        //Generate query
        $this->db->distinct();
        $this->db->select('value');
        $this->db->from('property_value');
        $this->db->where('language_id', $lang_id);
        $this->db->where_in('option_id', $option_ids);
        $this->db->like('value', $q);
        $this->db->order_by('value');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        $q_result = $query->result();
        
        // Generate results
        foreach($q_result as $key=>$row)
        {
            $results[] = $row->value;
        }
        
        // for treefield
        if(config_db_item('tree_field_enabled') === TRUE)
        {
            //Generate query
            $this->db->distinct();
            $this->db->select('value');
            $this->db->from('treefield_lang');
            $this->db->where('language_id', $lang_id);
            $this->db->like('value', $q);
            $this->db->order_by('value');
            $this->db->limit($limit);
            
            $query = $this->db->get();
            $q_result = $query->result();
            
            // Generate results
            foreach($q_result as $key=>$row)
            {
                $results[] = $row->value;
            }
            
            $results = array_unique($results);
        }
        
        if(config_db_item('additional_typeahead_address') === TRUE)
        {
            //Generate query
            $this->db->distinct();
            $this->db->select('address');
            $this->db->from('property');
            $this->db->like('address', $q);
            $this->db->order_by('address');
            $this->db->limit($limit);
            
            $query = $this->db->get();
            $q_result = $query->result();
            
            // Generate results
            foreach($q_result as $key=>$row)
            {
                $results[] = $row->address;
            }
            
            $results = array_unique($results);
        }
        
        return $results;
    }
    
    public function get_lang_array($id = NULL, $single = FALSE, $lang_id=1)
    {
        if($id != NULL)
        {
            $result = $this->get($id);
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('option_id', $id);
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
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.option_id');
        $this->db->where('language_id', $lang_id);
        
        if($single == TRUE)
        {
            $method = 'row';
        }
        else
        {
            $method = 'result';
        }
        
        if(!sw_count($this->db->ar_orderby))
        {
            $this->db->order_by($this->_order_by);
        }
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function save_with_lang($data, $data_lang, $id = NULL)
    {
        // Set timestamps
        if($this->_timestamps == TRUE)
        {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }
        
        // [Save first/second image in repository]
        $curr_item = $this->get($id);
        $repository_id = NULL;
        if(is_object($curr_item))
        {
            $repository_id = $curr_item->repository_id;
        }
        
        $data['image_gallery'] = NULL;
        $data['image_filename'] = NULL;
        if(!empty($repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$repository_id));
            
            $image_repository = array();
            $data['image_gallery'] = '';
            foreach($files as $key_f=>$file_row)
            {
                if(is_object($file_row))
                {
                    if(file_exists(FCPATH.'files/thumbnail/'.$file_row->filename))
                    {
                        if(empty($data['image_filename']))
                        {
                            $data['image_filename'] = $file_row->filename;
                        }
                        
                        $data['image_gallery'].=$file_row->filename.',';
                    }
                }
            }
        }
        // [/Save first/second image in repository]

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
        $this->db->delete($this->_table_name.'_lang', array('option_id' => $id));
        
        foreach($this->languages as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['language_id'] = $lang_key;
                $curr_data_lang['option_id'] = $id;
                
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
    
	public function get_nested ($lang_id = 2)
	{
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.option_id');
        $this->db->where('language_id', $lang_id);
        $this->db->order_by($this->_order_by);
		$pages = $this->db->get()->result_array();
        
        
		$array = array();
		foreach ($pages as $page) {
            if(!isset($this->option_types[$page['type']]))continue;
          
            $page['color'] = $this->option_type_color[$page['type']];
            $page['type'] = $this->option_types[$page['type']];
          
			if (! $page['parent_id']) {
				// This page has no parent
				$array[$page['id']]['parent'] = $page;
			}
			else {
				// This is a child page
				$array[$page['parent_id']]['children'][] = $page;
			}
		}
        
		return $array;
	}
    
    public $associative_array = NULL;
    public function get_field_list($lang_id)
    {
        if(is_array($this->associative_array))
            return $this->associative_array;
        
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.option_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where('type !=', 'CATEGORY');
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $results = $query->result();
        
        $associative_array = array();
        foreach($results as $row)
        {
            $associative_array[$row->id] = $row;
        }
        
        $this->associative_array = $associative_array;
        
        return $associative_array;     
    }
    
	public function save_order ($options)
	{
		if (is_array($options)) {
			foreach ($options as $order => $option) {
				if ($option['item_id'] != '' && $option['item_id'] != $option['parent_id']) {
					$data = array('parent_id' => (int) $option['parent_id'], 'order' => $order);
					$this->db->set($data)->where($this->_primary_key, $option['item_id'])->update($this->_table_name);
				}
			}
		}
	}
    
    public function save_repository($repository_id, $field_id, $property_id, $lang_id, $id)
    {
        $data = array();
        $data['value'] = $repository_id;
        $data['value_num'] = $repository_id;
        
        if(empty($id))
        {
            // Insert
            $data['language_id'] = $lang_id;
            $data['property_id'] = $property_id;
            $data['option_id'] = $field_id;

            $this->db->set($data);
            $this->db->insert('property_value');
            
            $id = $this->db->insert_id();
        }
        else
        {
            //Update
            $this->db->set($data);
            $this->db->where('id', $id);
            $this->db->update('property_value');
        }
        
        return $id;
    }
    
    public function get_property_value_by($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = "", $search = '')
    {
        $this->_table_name = 'property_value';
        $this->_order_by = 'id';
        
        $ret_parent = parent::get_by($where, $single, $limit, $order_by, $offset, $search);
        
        $this->_table_name = 'option';
        $this->_order_by = 'option.order, option.id';
        
        return $ret_parent;
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
                foreach($val_exp as $value)
                {
                    $values[$value] = $value;
                }
            }
        } 
        
        return $values;
    }
    
    public function get_fields($lang_id = 1)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.option_id');
        $this->db->where('language_id', $lang_id);
        $this->db->order_by($this->_order_by);
		$fields = $this->db->get()->result();
        
        return $fields;
    }
    
    public $fields_cache = array();
    public function get_field_data($field_id, $lang_id = 1)
    {
        if(isset($this->fields_cache[$lang_id]) && sw_count($this->fields_cache[$lang_id]) > 0)
        {
            if(isset($this->fields_cache[$lang_id][$field_id]))
                return $this->fields_cache[$lang_id][$field_id];
        }
        else
        {
            $fields = $this->get_fields($lang_id);
            //dump($fields);
            
            foreach($fields as $field)
            {
                $this->fields_cache[$lang_id][$field->id] = $field;
            }
            
            if(isset($this->fields_cache[$lang_id][$field_id]))
                return $this->fields_cache[$lang_id][$field_id];
        }
        
        return '';
    }
    
    public function check_deletable($id)
    {
        $where = "( parent_id=$id OR id=$id ) AND ( is_locked=1 OR is_hardlocked=1 )";
        $this->db->where($where);
        $this->db->from($this->_table_name);
        
        return ($this->db->count_all_results() == 0);
    }
    
    public function delete($id)
    {
        //check for deletable
        if($this->check_deletable($id))
        {
            //Get all childs
            $childs = $this->get_by(array('parent_id'=>$id));
            
            if(sw_count($childs) > 0)
            {
                foreach($childs as $key=>$child)
                {
                    //remove all values
                    $this->db->delete('property_value', array('option_id' => $child->id));
                    
                    // remove all childs translations
                    $this->db->delete('option_lang', array('option_id' => $child->id)); 
                }
                
                //Remove childs
                $this->db->delete($this->_table_name, array('parent_id'=>$id)); 
                
                // Delete also from depending
                $this->db->delete('dependent_field', array('field_id' => $id));
                
            }
            
            // Delete also from depending
            $this->db->delete('dependent_field', array('field_id' => $id));
            
            //remove all values from current
            $this->db->delete('property_value', array('option_id' => $id));
            
            // Delete also treefields if exists
            $this->load->model('treefield_m');
            $this->treefield_m->delete($id);
            
            //Remove current option
            $this->db->delete('option_lang', array('option_id' => $id)); 
            parent::delete($id);
            

        }
    }
    

}



