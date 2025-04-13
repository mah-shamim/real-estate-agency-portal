<?php

class Qa_m extends MY_Model {
    
    protected $_table_name = 'qa';
    protected $_order_by = 'is_readed, order, id';
    public $rules = array(
        'parent_id' => array('field'=>'parent_id', 'label'=>'lang:Category', 'rules'=>'trim|intval'),
        'language_id' => array('field'=>'language_id', 'label'=>'lang:Language', 'rules'=>'trim|intval'),
        'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim|xss_clean'),
        'contact_email' => array('field'=>'contact_email', 'label'=>'lang:Contact Email', 'rules'=>'trim|xss_clean'),
   );
   
   public $rules_category = array(
        'parent_id' => array('field'=>'parent_id', 'label'=>'lang:Parent', 'rules'=>'trim|intval'),
        'language_id' => array('field'=>'language_id', 'label'=>'lang:Language', 'rules'=>'trim|intval'),
        'is_readed' => array('field'=>'language_id', 'label'=>'lang:Readed', 'rules'=>'trim')
   );
   
   public $rules_lang = array();
   
   public $rules_lang_categories = array();
   
	public function __construct(){
		parent::__construct();
        
        $this->languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
                                  
        //Rules for languages
        foreach($this->languages as $key=>$value)
        {
            $this->rules_lang["question_$key"] = array('field'=>"question_$key", 'label'=>'lang:Question', 'rules'=>'trim|required');
            $this->rules_lang["answer_$key"] = array('field'=>"answer_$key", 'label'=>'lang:Answer', 'rules'=>'trim');
            $this->rules_lang["keywords_$key"] = array('field'=>"keywords_$key", 'label'=>'lang:Keywords', 'rules'=>'trim');
            
            $this->rules_lang_categories["question_$key"] = array('field'=>"question_$key", 'label'=>'lang:Title', 'rules'=>'trim|required|xss_clean');
        }
	}

    public function get_new()
	{
        $page = new stdClass();
        $page->parent_id = 0;
        $page->language_id = 0;
        $page->is_readed = 0;
        $page->type = 'CATEGORY';
        $page->date = date('Y-m-d H:i:s');
        $page->date_publish = date('Y-m-d H:i:s');
        $page->answer_user_id = NULL;
        
        //Add language parameters
        foreach($this->languages as $key=>$value)
        {
            $page->{"question_$key"} = '';
            $page->{"answer_$key"} = '';
            $page->{"keywords_$key"} = '';
        }
        
        return $page;
	}
    
	public function save_order ($pages)
	{
		if (sw_count($pages)) {
			foreach ($pages as $order => $page) {
				if ($page['item_id'] != '') {
					$data = array('parent_id' => (int) $page['parent_id'], 'order' => $order);
					$this->db->set($data)->where($this->_primary_key, $page['item_id'])->update($this->_table_name);
				}
			}
		}
	}
    
    public function get_lang($id = NULL, $single = FALSE, $lang_id=1, $where = null, $limit = null, $offset = "", $order_by=NULL, $search = '')
    {
        if($id != NULL)
        {
            $result = $this->get($id);
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('qa_id', $id);
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
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.qa_id');
        $this->db->where('language_id', $lang_id);
        
        if($where != null)
            $this->db->where($where);
            
        if(!empty($search))
        {
            $this->db->where("(question LIKE '%$search%' OR keywords LIKE '%$search%' OR answer LIKE '%$search%')");
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
            $id || $data['date'] = $now;
            $data['date_modified'] = $now;
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
        $this->db->delete($this->_table_name.'_lang', array('qa_id' => $id));
        
        foreach($this->languages as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['language_id'] = $lang_key;
                $curr_data_lang['qa_id'] = $id;
                
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
    
    public function get_no_parents($lang_id=1)
	{
        // Fetch pages without parents
        $this->db->select('*');
        $this->db->where('parent_id', 0);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.qa_id');
        //$this->db->not_like($this->_table_name.'.type', 'MODULE_', 'after');
        $this->db->where('language_id', $lang_id);
        $pages = parent::get();
        
        // Return key => value pair array
        $array = array(0 => lang('No parent'));
        if(sw_count($pages))
        {
            foreach($pages as $page)
            {
                $array[$page->id] = $page->question;
            }
        }
        
        return $array;
	}
    
    public function get_no_parents_expert($lang_id=1, $not_selected = 'Not expert')
	{
        // Fetch pages without parents
        $this->db->select('*');
        //$this->db->where('parent_id', 0);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.qa_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where($this->_table_name.'.type =', 'CATEGORY');
        //$this->db->not_like($this->_table_name.'.type', 'MODULE_', 'after');
        $this->db->order_by('order, id');
        
        $pages = parent::get();
        
        // Return key => value pair array
        $array = array(0 => lang_check($not_selected));
        if(sw_count($pages))
        {
            foreach($pages as $page)
            {
                if($page->parent_id == 0)
                {
                    $array[$page->id] = $page->question;
                }
                else
                {
                    $array[$page->id] = '&nbsp;|-'.$page->question;
                }
            }
        }
        
        return $array;
	}
    
    public function get_contained_expert_category($page_id, $type = 'CATEGORY')
    {
        $this->db->select('*');
        $this->db->where('parent_id', $page_id);
        $this->db->where($this->_table_name.'.type', $type);
        
        return parent::get(NULL, TRUE);
    }
    
    public function get_no_parents_expert_category($lang_id=1)
	{
        // Fetch pages without parents
        $this->db->select('*');
        //$this->db->where('parent_id', 0);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.qa_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where($this->_table_name.'.type', 'CATEGORY');
        $this->db->order_by('order, id');
        
        $pages = parent::get();
        
        // Return key => value pair array
        $array = array(0 => lang_check('No category'));
        if(sw_count($pages))
        {
            foreach($pages as $page)
            {
                if($page->parent_id == 0)
                {
                    $array[$page->id] = $page->question;
                }
                else
                {
                    $array[$page->id] = '|- '.$page->question;
                }
            }
        }
        
        return $array;
	}
    
    public function get_sitemap()
	{
        // Fetch pages without parents
        $this->db->select('*');
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $pages = parent::get();
                
        return $pages;
	}

	public function get_nested_tree ($lang_id=2)
	{
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.qa_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where('type', 'CATEGORY');
        //$this->db->not_like('type', 'MODULE_', 'after');
        $this->db->order_by($this->_order_by);
		$pages = $this->db->get()->result_array();
		
		$array = array();
        $tmp_arr = array();
		foreach ($pages as $page) {
		   $array[$page['parent_id']][$page['id']] = $page;
		}
		return $array;
	}
    
	public function get_nested_showrooms_categories ($lang_id=2)
	{
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.qa_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where('type', 'CATEGORY');
        $this->db->order_by($this->_order_by);
		$pages = $this->db->get()->result_array();
		
		$array = array();
		foreach ($pages as $page) {         
			if (! $page['parent_id']) {
				// This page has no parent
				$array[$page['id']] = $page;
			}
			else {
				// This is a child page
				//$array[$page['parent_id']]['children'][] = $page;
                $array[$page['id']] = $page;
			}
		}
		return $array;
	}
    
    public function delete($id)
    {
        $this->db->delete('qa_lang', array('qa_id' => $id)); 
        
        parent::delete($id);
        
		// Reset parent ID for its children
		$this->db->set(array(
			'parent_id' => 0
		))->where('parent_id', $id)->update($this->_table_name);
    }

}


