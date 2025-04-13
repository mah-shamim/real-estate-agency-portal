<?php

class Treefield_m extends MY_Model {
    
    protected $_primary_key = 'id';
    protected $_table_name = 'treefield';
    protected $_order_by = 'treefield.order, treefield.id';
    
    public $rules = array(
        'parent_id' => array('field'=>'parent_id', 'label'=>'lang:parent', 'rules'=>'trim|required|xss_clean'),
        'template' => array('field'=>'template', 'label'=>'lang:Template', 'rules'=>'trim|xss_clean'),
        'affilate_price' => array('field'=>'affilate_price', 'label'=>'lang:Affilate price', 'rules'=>'trim|numeric|xss_clean'),
        'repository_id' => array('field'=>'repository_id', 'label'=>'lang:repository_id', 'rules'=>'trim'),
        'order' => array('field'=>'order', 'label'=>'lang:Order', 'rules'=>'trim|numeric|xss_clean'),
        'font_icon_code' => array('field'=>'font_icon_code', 'label'=>'lang:Font icon code', 'rules'=>'trim|xss_clean')
    );
   
    public $rules_lang = array();

	public function __construct(){
		parent::__construct();
        
        $this->languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
  
        //Rules for languages
        foreach($this->languages as $key=>$value)
        {
            $this->rules_lang["value_$key"] = array('field'=>"value_$key", 'label'=>'lang:Value', 'rules'=>'trim|required|callback_values_correction|callback_values_dropdown_check|xss_clean');
            $this->rules_lang["title_$key"] = array('field'=>"title_$key", 'label'=>'lang:Page Title', 'rules'=>'trim|xss_clean');
            $this->rules_lang["path_title_$key"] = array('field'=>"path_title_$key", 'label'=>'lang:Custom path title', 'rules'=>'trim|xss_clean');
            $this->rules_lang["body_$key"] = array('field'=>"body_$key", 'label'=>'lang:Body', 'rules'=>'trim');
            $this->rules_lang["description_$key"] = array('field'=>"description_$key", 'label'=>'lang:Description', 'rules'=>'trim');
            $this->rules_lang["keywords_$key"] = array('field'=>"keywords_$key", 'label'=>'lang:Keywords', 'rules'=>'trim');
            $this->rules_lang["slug_$key"] = array('field'=>"slug_$key", 'label'=>'lang:URI slug', 'rules'=>'trim');
            $this->rules_lang["address_$key"] = array('field'=>"address_$key", 'label'=>'lang:Address', 'rules'=>'trim');
        
            for($i=1;$i<=6;$i++)
            {
                $this->rules_lang['adcode'.$i.'_'.$key] = array('field'=>'adcode'.$i.'_'.$key, 'label'=>lang_check('Ads code').' '.$i, 'rules'=>'trim');
            }
        }
	}

    public function get_new()
	{
        $option = new stdClass();
        $option->parent_id = 0;
        $option->template = 'treefield';
        $option->font_icon_code = '';
        
        //Add language parameters
        foreach($this->languages as $key=>$value)
        {
            $option->{"value_$key"} = '';
            $option->{"title_$key"} = '';
            $option->{"path_title_$key"} = '';
            $option->{"address_$key"} = '';
            $option->{"body_$key"} = '';
            $option->{"keywords_$key"} = '';
            $option->{"description_$key"} = '';
            $option->{"slug_$key"} = '';
            
            for($i=1;$i<=6;$i++)
            {
                //$option->{"adcode$i_$key"} = '';
                $option->{"adcode".$i."_".$key} = '';
            }
        }
        
        return $option;
	}
    
    public function get_max_level($key_option)
    {
        $this->db->select('MAX(`level`) as `level`', FALSE);
        $this->db->where('field_id', $key_option);
        $query = $this->db->get($this->_table_name);
        
        if(is_object($query) && $query->num_rows() > 0)
        {
            $row = $query->row();
        }
        else
        {
            echo 'SQL problem in get_max_level:';
            echo $this->db->last_query();
            exit();
        }
        
        return (int) $row->level;
    }
    
    public function get_affilate_available()
    {
        $this->db->select($this->_table_name.'.*, affilate_packages.*, '.$this->_table_name.'_lang.value_path');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $this->db->join('affilate_packages', $this->_table_name.'.id = affilate_packages.treefield_id', 'left');
        $this->db->where($this->_table_name.'.notifications_sent', 0);
        $this->db->where($this->_table_name.'.affilate_price >', 0);
        $this->db->where('(affilate_packages.date_expire < \''.date('Y-m-d H:i:s').'\' OR affilate_packages.date_expire is NULL)');
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }
    
    public function get_by_in($where_in, $lang_id)
    {
        if(sw_sw_count($where_in) == 0)
            return array();
        
        $this->db->select($this->_table_name.'.id, value, value_path, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where_in($this->_table_name.'.id',$where_in);
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }
    
    public function get_level_values ($lang_id, $field_id, $parent_id=0, $level=0, $not_selected = NULL)
    {
        $this->db->select($this->_table_name.'.id, value, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('field_id', $field_id);
        $this->db->where('language_id', $lang_id);
        $this->db->where('level', $level);
        $this->db->where('parent_id', $parent_id);
        $this->db->order_by('treefield.order, '.$this->_table_name.'_lang.value');
        $query = $this->db->get();
        $options = $query->result();
        
        $array = array('' => $not_selected);
        if($not_selected == NULL)
        {
            $lang_not_selected = lang_check('treefield_'.$field_id.'_'.$level);
            if($lang_not_selected == 'treefield_'.$field_id.'_'.$level)
                $lang_not_selected = lang_check('Not selected');
            $array = array('' => $lang_not_selected);
        }
        
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $array[$option->id] = $option->value;
            }
        }
        
//        if(sw_count($array) == 1)
//        {
//            $array = array('' => lang_check('No values found'));
//        }
        
        return $array;        
    }

    public function get_no_parents($lang_id = 2, $field_id=0, $current_id = NULL)
	{
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.id, value, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('field_id', $field_id);
        $this->db->where('language_id', $lang_id);
        if($current_id != NULL)$this->db->where($this->_table_name.'.id !=', $current_id);
        //$this->db->order_by($this->_order_by);
        $this->db->order_by($this->_table_name.'_lang.value');
        $query = $this->db->get();
        $options = $query->result();

        // Return key => value pair array
        $array = array('0' => lang('No parent'));
        
        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->parent_id][$option->id] = $option;
            }
        }
        
        $this->generate_tree_recursive(0, $t_array, $array, 0);
        return $array;
	}
    
    private function generate_tree_recursive($parent_id, $t_array, &$array, $level)
    {
        if(isset($t_array[$parent_id]))
        foreach($t_array[$parent_id] as $key=>$option)
        {
            $level_gen = str_pad('', $level*12, '&nbsp;');

            $array[$key] = $level_gen.'|-'.$option->value;
            
            if(isset($t_array[$key]))
                $this->generate_tree_recursive($key, $t_array, $array, $level+1);
        }
    }
    
    public function get_table_tree($lang_id = 2, $field_id=0, $current_id = NULL, $return_print=true, $custom_order='_lang.value', $custom_fields='', $where=NULL)
	{
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.id, value, level, parent_id, template, body, affilate_price'.$custom_fields);
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('field_id', $field_id);
        $this->db->where('language_id', $lang_id);
        //if($current_id != NULL)$this->db->where($this->_table_name.'.id !=', $current_id);
        //$this->db->order_by($this->_order_by);
        
        if($where != NULL)
            $this->db->where($where);

        $this->db->order_by($this->_table_name.$custom_order);
        
        $query = $this->db->get();
        
        if($query==FALSE)
            return false;
        
        $options = $query->result();

        // Return key => value pair array
        $array = array();
        
        if(sw_count($where) > 0 && sw_count($options))
        {
            return $options;
        }

        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->parent_id][$option->id] = $option;
            }
        }
        
        if(!$return_print)
        {
            return $t_array;
        }
        
        $this->_generate_table_tree_recursive(0, $t_array, $array, 0);
        return $array;
	}
    
    private function _generate_table_tree_recursive($parent_id, $t_array, &$array, $level)
    {
        if(isset($t_array[$parent_id]))
        foreach($t_array[$parent_id] as $key=>$option)
        {
            $level_gen = str_pad('', $level*12, '&nbsp;');
            
            $option->visual = $level_gen.'|-';
            $array[$key] = $option;
            
            if(isset($t_array[$key]))
                $this->_generate_table_tree_recursive($key, $t_array, $array, $level+1);
        }
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
    
    public function get_options($lang_id=1, $option_id = array(), $property_id = array())
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
        
        $query = $this->db->get('property_value');
        
        $data = array();
        foreach($query->result() as $key=>$option)
        {
            $data[$option->property_id][$option->option_id] = $option->value;
        }

        return $data;
    }
    
    public function get_lang($id = NULL, $single = FALSE, $lang_id=1)
    {
        if($id != NULL)
        {
            $result = $this->get($id);
            
            if(empty($result)) return '';
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('treefield_id', $id);
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
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
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
    
    public function get_typeahead($q, $limit=8, $treefield_ids=array(5,7,40), $lang_id=1)
    {
        $results = array();
        
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
    
    public function save_with_lang($data, $data_lang, $field_id, $treefield_id = NULL)
    {
        // Set timestamps
        if($this->_timestamps == TRUE)
        {
            $now = date('Y-m-d H:i:s');
            $treefield_id || $data['created'] = $now;
            $data['modified'] = $now;
        }
        
        $data['field_id'] = $field_id;
        
        if(empty($data['level']) && !empty($data['parent_id']))
        {
            $parent_treefield = $this->get($data['parent_id']);
            $data['level'] = $parent_treefield->level + 1;
        }
        
        // Insert
        if($treefield_id === NULL)
        {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $treefield_id = $this->db->insert_id();
        }
        // Update
        else
        {
            $filter = $this->_primary_filter;
            $treefield_id = $filter($treefield_id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $treefield_id);
            $this->db->update($this->_table_name);
        }
        
        // Save lang data
        $this->db->delete($this->_table_name.'_lang', array('treefield_id' => $treefield_id));
        if(!function_exists('recursion_save')){
        function recursion_save ($path, $tree_listings, $parent_lvl, $id, &$ariesInfo){
            if (isset($tree_listings[$id]) && sw_count($tree_listings[$id]) > 0){
                foreach ($tree_listings[$id] as $key => $_child) {
                    $options = $tree_listings[$_child->parent_id][$_child->id];

                    $_parent_lvl = $parent_lvl+1;
                    $_current_path = $path.' - '.$options->value;

                    $treefield = array();

                    $treefield['id'] = $options->id;
                    $treefield['level'] = $_parent_lvl;
                    
                    $CI=&get_instance();
                    $CI->load->model('treefield_m');
                    $treefield['title'] = $options->value;
                    $treefield['path'] = $_current_path;
                    $ariesInfo[]=$treefield;
                    
                    if (isset($tree_listings[$_child->id]) && sw_count($tree_listings[$_child->id]) > 0){    
                        recursion_save($_current_path, $tree_listings, $_parent_lvl, $_child->id, $ariesInfo);
                    }
                }
            }
        };
        }
        foreach($this->languages as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['language_id'] = $lang_key;
                $curr_data_lang['treefield_id'] = $treefield_id;

                foreach($data_lang as $data_key=>$data_val)
                {
                    $pos = strrpos($data_key, "_");
                    if((int)substr($data_key,$pos+1) == (int)$lang_key)
                    {
                        $curr_data_lang[substr($data_key,0,$pos)] = $data_val;
                        if(substr($data_key,0,$pos) == 'value')
                        {
                            $curr_data_lang['value_path'] = $this->get_path($field_id, $data['parent_id'], $lang_key).$data_val;
                            
                            /* updated childs */
                            $tree_listings = $this->get_table_tree($lang_key, $field_id, NULL, FALSE, '.order', ', value_path');
                            if(!empty($tree_listings[$treefield_id]) && sw_count($tree_listings[$treefield_id]) > 0){
                                $result_count = array();
                                $parent_path =  $curr_data_lang['value_path'];
                                $parent_lvl =  $data['level'];
                                $ariesInfo = array();
                                foreach ($tree_listings[$treefield_id] as $val) {
                                    $options = $val;
                                    $treefield = array();

                                    $current_lvl = $parent_lvl+1;
                                    $current_path = $parent_path.' - '.$options->value;

                                    $treefield['id'] = $val->id;
                                    $treefield['title'] = $options->value;
                                    $treefield['path'] = $current_path;
                                    $treefield['level'] = $current_lvl;
                                    $ariesInfo[]=$treefield;
                                    
                                    if(isset($tree_listings[$val->id]) && sw_count($tree_listings[$val->id]) > 0){
                                        recursion_save($current_path, $tree_listings, $current_lvl, $val->id, $ariesInfo);
                                    }     
                                }
                                
                                /* update path */
                                foreach ($ariesInfo as $key => $value) {
                                    $data_update = array(
                                        'value_path'  => $value['path'],
                                    );

                                    $this->db->where('language_id', $lang_key);
                                    $this->db->where('treefield_id', $value['id']);
                                    $this->db->update($this->_table_name.'_lang', $data_update);
                                }

                            }
                            /* end updated childs */
                            
                        }
                    }
                }
                
                /* start updated childs */
                if(isset($ariesInfo) && !empty($ariesInfo)){
                    foreach ($ariesInfo as $key => $value) {
                        $data_update = array(
                            'level' => $value['level'],
                        );

                        $this->db->where('id', $value['id']);
                        $this->db->update($this->_table_name, $data_update);
                    }
                }
                /* end updated childs */
                
                $this->db->set($curr_data_lang);
                $this->db->insert($this->_table_name.'_lang');
            }
        }
        
        
        if(!empty($treefield_id)) {
            // [Save first image in repository]
           
            $repository_id = NULL;
            
            $field=$this->treefield_m->get_by(array('id'=>$treefield_id));
            $field=$field[0];
            if(!empty($field))       
                $repository_id = $field->repository_id;   
            $data= array();
            $data['image_filename'] = NULL;
            if(!empty($repository_id))
            {
                $files = $this->file_m->get_by(array('repository_id'=>$repository_id));

                $image_repository = array();
                foreach($files as $key_f=>$file_row)
                {
                    if(is_object($file_row))
                    if(file_exists(FCPATH.'files/thumbnail/'.$file_row->filename))
                    {
                        if(empty($data['image_filename']))
                            $data['image_filename'] = $file_row->filename;

                    }
                }

                $this->save($data, $treefield_id);
            }
            // [/Save first image in repository]
        }
        
        return $treefield_id;
    }
    
    public function id_by_path($field_id, $lang_id, $path)
    {
        $this->db->select('treefield_id, field_id, language_id, value_path');
        $this->db->from($this->_table_name.'_lang');
        $this->db->join($this->_table_name, $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('field_id', $field_id);
        $this->db->where('value_path', $path);
        $this->db->where('language_id', $lang_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
           return $row->treefield_id;
        }
        
        return NULL;
    }

    public function get_path_list()
    {
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.id, value, language_id, value_path, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        //$this->db->where('language_id', $lang_id);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $options = $query->result();

        // Return key => value pair array
        $array = array();

        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->id][$option->language_id] = $option;
            }
        }

        return $t_array;
    }
    
    public function get_path($field_id, $treefield_id, $lang_id)
    {
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.id, value, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('field_id', $field_id);
        $this->db->where('language_id', $lang_id);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $options = $query->result();

        // Return key => value pair array
        $array = array();

        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->id] = $option;
            }
        }
        
        if(!isset($t_array[$treefield_id]))
            return '';
        
        $tree_parent_id = $t_array[$treefield_id]->parent_id;
        $generated_path = $t_array[$treefield_id]->value.' - ';

        while(!empty($t_array[$tree_parent_id]))
        {
            $option = $t_array[$tree_parent_id];
            $generated_path = $option->value.' - '.$generated_path;
            $tree_parent_id = $option->parent_id;
        }

        return $generated_path;
    }
    
    public function regenerate_fields()
    {
        $this->db->select($this->_table_name.'.id, value, level, field_id, parent_id, language_id as lang_id, '.$this->_table_name.'_lang.id as tree_lang_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.treefield_id');
        $query = $this->db->get();
        
        echo  $this->db->last_query().'<br />';
        $fields = $query->result();
        
        $data = array();
        foreach($fields as $key=>$row)
        {
            $data_t = array();
            $data_t['value_path'] = $this->get_path($row->field_id, $row->parent_id, $row->lang_id).$row->value;
            $data_t['id'] = $row->tree_lang_id;
            $data[] = $data_t;
            
            //$this->db->where('id', $row->tree_lang_id);
            //$this->db->update($this->_table_name.'_lang', $data); 
        }
        
        $this->db->update_batch($this->_table_name.'_lang', $data, 'value_path'); 
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
    
	public function save_order ($options)
	{
		if (is_array($options)) {
			foreach ($options as $order => $option) {
				if ($option['item_id'] != '') {
					$data = array('parent_id' => (int) $option['parent_id'], 'order' => $order);
					$this->db->set($data)->where($this->_primary_key, $option['item_id'])->update($this->_table_name);
				}
			}
		}
	}
    
    public function check_deletable($id)
    {
        $where = "( parent_id=$id OR id=$id )";
        $this->db->where($where);
        $this->db->from($this->_table_name);
        
        return ($this->db->count_all_results() == 0);
    }
    
    public function delete($field_id)
    {
        //Get all childs
        $childs = array();
        $this->_get_all_childs($field_id, 0, $childs);
        
        //Delete childs
        $this->db->where_in('treefield_id', $childs);
        $this->db->delete('treefield_lang'); 
        $this->db->where_in('id', $childs);
        $this->db->delete('treefield'); 
    }
        
    public function get_all_childs($field_id, $treefield_id, &$childs)
    {
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.id, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->where('field_id', $field_id);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $options = $query->result();

        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->parent_id][$option->id] = $option;
            }
        }
        
        $this->_get_all_childs_recursive($treefield_id, $t_array, $childs);
    }
    
    private function _get_all_childs($field_id, $treefield_id, &$childs)
    {
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.id, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->where('field_id', $field_id);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $options = $query->result();

        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->parent_id][$option->id] = $option;
            }
        }
        
        $this->_get_all_childs_recursive($treefield_id, $t_array, $childs);
    }
    
    private function _get_all_childs_recursive($parent_id, $t_array, &$array)
    {
        if(isset($t_array[$parent_id]))
        foreach($t_array[$parent_id] as $key=>$option)
        {
            $array[$key] = $key;
            
            if(isset($t_array[$key]))
                $this->_get_all_childs_recursive($key, $t_array, $array);
        }
    }
    
    public function delete_value($field_id, $treefield_id, $only_childs = FALSE)
    {
            //Get all childs
            $childs = array();
            $this->_get_all_childs($field_id, $treefield_id, $childs);
            
            //Delete childs
            $this->db->where_in('treefield_id', $childs);
            $this->db->delete('treefield_lang'); 
            $this->db->where_in('id', $childs);
            $this->db->delete('treefield'); 
            
            //Delete current
            if(!$only_childs){
                $this->db->delete('treefield_lang', array('treefield_id' => $treefield_id)); 
                $this->db->delete('treefield', array('id' => $treefield_id)); 
            }
    }

    /*
    * return id
    */
    public function generated_id_by_path($path, $field_id, $lang_id = 1) {
        $tree_id = NULL; 
        $path = trim($path, ' - ');
        $values_arr = explode(' - ', $path);
        $tree_id = $this->id_by_path($field_id, $lang_id, $path);

        $current_path = '';
        $current_tree_id = 0;
        if(!$tree_id)
            foreach ($values_arr as $level => $value) {
                if(!empty($current_path))
                    $current_path .= ' - ';

                $current_path .= $value;

                if(empty($value)) break;

                $tree_id = $this->id_by_path($field_id, $lang_id, $current_path);

                if(empty($tree_id)) {
                    $data = array();
                    $data['parent_id'] = $current_tree_id;
                    $data['order']=0;
                    $data['font_icon_code']='';
                    $data['level']=$level;
                    $data['template']='treefield_treefield';

                    $data_lang= array();
                    foreach($this->language_m->db_languages_code_obj as $lang_obj)
                        {
                            $data_lang['title_'.$lang_obj->id] =  '';
                            $data_lang['value_'.$lang_obj->id] =  $value;
                            $data_lang['path_title_'.$lang_obj->id] = $current_path;
                            $data_lang['body_'.$lang_obj->id] = '';
                        }

                    $tree_id = $current_tree_id = $this->save_with_lang($data, $data_lang, $field_id);
                } else {
                    $current_tree_id = $tree_id;
                }
            }

        return $tree_id;
    }
    
}



