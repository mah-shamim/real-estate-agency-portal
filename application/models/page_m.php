<?php

class Page_m extends MY_Model {
    
    protected $_table_name = 'page';
    protected $_order_by = 'order, id';
    public $rules = array(
        'parent_id' => array('field'=>'parent_id', 'label'=>'lang:Parent', 'rules'=>'trim|intval|callback_parent_check'),
        'language_id' => array('field'=>'language_id', 'label'=>'lang:Language', 'rules'=>'trim|intval'),
        'template' => array('field'=>'template', 'label'=>'lang:Template', 'rules'=>'trim|required|xss_clean'),
        'template_header' => array('field'=>'template_header', 'label'=>'lang:Template header', 'rules'=>'trim|xss_clean'),
        'template_footer' => array('field'=>'template_footer', 'label'=>'lang:Template footer', 'rules'=>'trim|xss_clean'),
        'is_visible' => array('field'=>'is_visible', 'label'=>'lang:Visible in menu', 'rules'=>'trim|xss_clean'),
        'is_private' => array('field'=>'is_private', 'label'=>'lang:Visible for logged users', 'rules'=>'trim|xss_clean')
        //'title' => array('field'=>'title', 'label'=>'lang:Title', 'rules'=>'trim|required|max_length[100]|xss_clean'),
        //'navigation_title' => array('field'=>'navigation_title', 'label'=>'lang:Navigation title', 'rules'=>'trim|required|max_length[100]|xss_clean'),
        //'slug' => array('field'=>'slug', 'label'=>'lang:Slug', 'rules'=>'trim|required|max_length[100]|url_title|callback__unique_slug|xss_clean'),
        //'body' => array('field'=>'body', 'label'=>'lang:Body', 'rules'=>'trim|required'),
    );
    
    public $rules_news = array(
        'parent_id' => array('field'=>'parent_id', 'label'=>'lang:Parent', 'rules'=>'trim|intval'),
        'language_id' => array('field'=>'language_id', 'label'=>'lang:Language', 'rules'=>'trim|intval')
    );
    
    public $rules_lang = array();
    
    public $rules_lang_news = array();
   
	public function __construct(){
		parent::__construct();
        
        $this->languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
                                  
        //Rules for languages
        foreach($this->languages as $key=>$value)
        {
            $this->rules_lang["title_$key"] = array('field'=>"title_$key", 'label'=>'lang:Title', 'rules'=>'trim|required|xss_clean');
            $this->rules_lang["navigation_title_$key"] = array('field'=>"navigation_title_$key", 'label'=>'lang:Navigation title', 'rules'=>'trim|required|xss_clean');
            $this->rules_lang["body_$key"] = array('field'=>"body_$key", 'label'=>'lang:Body', 'rules'=>'trim');
            $this->rules_lang["description_$key"] = array('field'=>"description_$key", 'label'=>'lang:Description', 'rules'=>'trim');
            $this->rules_lang["keywords_$key"] = array('field'=>"keywords_$key", 'label'=>'lang:Keywords', 'rules'=>'trim');
            
            if(config_db_item('slug_enabled') === TRUE)
            {
                $this->rules_lang["slug_$key"] = array('field'=>"slug_$key", 'label'=>'lang:URI slug', 'rules'=>'trim');
            }
            
            $this->rules_lang_news["title_$key"] = array('field'=>"title_$key", 'label'=>'lang:Title', 'rules'=>'trim|required|xss_clean');
        }
	}

    public function get_new()
	{
        $page = new stdClass();
        $page->parent_id = 0;
        $page->language_id = 0;
        $page->is_visible = 1;
        $page->is_private = 0;
        $page->type = 'PAGE';
        $page->template = 'page';
        $page->date = date('Y-m-d H:i:s');
        $page->date_publish = date('Y-m-d H:i:s');
        $page->template_header = '';
        $page->template_footer = '';
        
        //Add language parameters
        foreach($this->languages as $key=>$value)
        {
            $page->{"title_$key"} = '';
            $page->{"navigation_title_$key"} = '';
            //$page->{"slug_$key"} = '';
            $page->{"body_$key"} = '';
            $page->{"keywords_$key"} = '';
            $page->{"description_$key"} = '';
            $page->{"slug_$key"} = '';
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
    
	public function get_with_parent ($id = NULL, $single = FALSE)
	{
		$this->db->select('page.*, p.slug as parent_slug, p.title as parent_title');
		$this->db->join('page as p', 'page.parent_id=p.id', 'left');
		return parent::get($id, $single);
	}
    
    public function get_templates($template_prefix)
    {
        $CI =& get_instance();
        
        $templates = array();
        
        $templatesDirectory = opendir(FCPATH.'templates/'.$CI->app_settings['template']);
        // get each template
        while($tempFile = readdir($templatesDirectory)) {
            if ($tempFile != "." && $tempFile != "..") {
                if(substr_count($tempFile, $template_prefix) > 0)
                {
                    $templates[substr($tempFile,0,-4)] = lang_check(ucfirst(substr($tempFile, strlen($template_prefix), -4)));
                }

            }
        }
        
        return $templates;
    }
    
    public function get_subtemplates($subfolder = 'headers')
    {
        $CI =& get_instance();
        $templates = array();
        
        if(file_exists(FCPATH.'templates/'.$CI->app_settings['template'].'/'.$subfolder.'/'))
        {
            $templates[''] = lang_check('Default');
            
            $templatesDirectory = opendir(FCPATH.'templates/'.$CI->app_settings['template'].'/'.$subfolder.'/');
            // get each template
            while($tempFile = readdir($templatesDirectory)) {
                if ($tempFile != "." && $tempFile != "..") {
                    $templates[substr($tempFile,0,-4)] = lang_check(ucfirst(str_replace('_', ' ', substr($tempFile, 0, -4))));
                }
            }
        }
        
        return $templates;
    }
    
    public function get_listingtemplates($subfolder = 'widgets')
    {
        $CI =& get_instance();
        $templates = array();
        
        if(file_exists(FCPATH.'templates/'.$CI->app_settings['template'].'/'.$subfolder.'/'))
        {
            $templates[''] = lang_check('Default');
            
            $templatesDirectory = opendir(FCPATH.'templates/'.$CI->app_settings['template'].'/'.$subfolder.'/');
            // get each template
            while($tempFile = readdir($templatesDirectory)) {
                if ($tempFile != "." && $tempFile != ".." && substr($tempFile,0,9) == 'property_') {
                    
                    $templates[substr($tempFile,9,-4)] = lang_check(ucfirst(str_replace('_', ' ', substr($tempFile, 9, -4))));
                }
            }
        }
        
        return $templates;
    }
    
    public function get_lang($id = NULL, $single = FALSE, $lang_id=1, $where = null, $limit = null, $offset = "", $order_by=NULL, $search=NULL)
    {
        if($id != NULL)
        {
            $result = $this->get($id);
            
            $this->db->select('*');
            $this->db->from($this->_table_name.'_lang');
            $this->db->where('page_id', $id);
            $lang_result = $this->db->get()->result_array();
            
            if(sw_count($lang_result) == 0)
                return NULL;
            
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
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->where('language_id', $lang_id);
        
        if($where != null)
            $this->db->where($where);
            
        if(!empty($search))
        {
            $this->db->where("(title LIKE '%$search%' OR keywords LIKE '%$search%' OR body LIKE '%$search%' OR description LIKE '%$search%')");
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

        if(!empty($repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$repository_id));
            
            $image_repository = array();
            foreach($files as $key_f=>$file_row)
            {
                if(is_object($file_row))
                {
                    if(file_exists(FCPATH.'files/thumbnail/'.$file_row->filename))
                    {
                        if(empty($data['image_filename']))
                        {
                            $data['image_filename'] = $file_row->filename;
                            break;
                        }
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
        $this->db->delete($this->_table_name.'_lang', array('page_id' => $id));
        
        foreach($this->languages as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['language_id'] = $lang_key;
                $curr_data_lang['page_id'] = $id;
                
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
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->not_like($this->_table_name.'.type', 'MODULE_', 'after');
        $this->db->where('language_id', $lang_id);
        $pages = parent::get();
        
        // Return key => value pair array
        $array = array(0 => lang('No parent'));
        if(sw_count($pages))
        {
            foreach($pages as $page)
            {
                $array[$page->id] = $page->title;
            }
        }
        
        return $array;
	}
    
    public function get_no_parents_news($lang_id=1, $not_selected = 'No container', $current_id=NULL)
	{
        // Fetch pages without parents
        $this->db->select('*');
        //$this->db->where('parent_id', 0);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where($this->_table_name.'.type !=', 'ARTICLE');
        if($current_id != NULL)$this->db->where($this->_table_name.'.id !=', $current_id);
        $this->db->not_like($this->_table_name.'.type', 'MODULE_', 'after');
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
                    $array[$page->id] = $page->title;
                }
                else
                {
                    $array[$page->id] = '&nbsp;|-'.$page->title;
                }
            }
        }
        
        return $array;
	}
    
    public function get_contained_news_category($page_id, $type = 'MODULE_NEWS_CATEGORY')
    {
        $this->db->select('*');
        $this->db->where('parent_id', $page_id);
        $this->db->where($this->_table_name.'.type', $type);
        
        return parent::get(NULL, TRUE);
    }
    
    public function get_no_parents_news_category($lang_id=1)
	{
        // Fetch pages without parents
        $this->db->select('*');
        //$this->db->where('parent_id', 0);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where($this->_table_name.'.type', 'MODULE_NEWS_CATEGORY');
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
                    $array[$page->id] = $page->title;
                }
                else
                {
                    $array[$page->id] = ''.$page->title;
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
        $this->db->where('is_visible', 1);
        $this->db->where('is_private', 0);
        $pages = parent::get();
                
        return $pages;
	}
    
    public function get_first ()
    {
        if(($page = $this->cache_temp_load('page_get_first')) === FALSE)
        {
            $page = '';
            
            $this->db->select('*');
            $this->db->from($this->_table_name);
            $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
            $this->db->where('template !=', '');
            $this->db->where('type', 'PAGE');
            $this->db->order_by($this->_order_by);
            $this->db->limit(1);
            
    		$pages = $this->db->get()->result();
            
            if(sw_count($pages) > 0)
            {
                $page = $pages[0];
            }
            
            $this->cache_temp_save($page, 'page_get_first');
        }
        
        return $page;
    }
    
    public function get_id_by_name ($page_id)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->order_by($this->_order_by);
		$pages = $this->db->get()->result_array();
        
		foreach ($pages as $page) {
		  if(url_title_cro($page['title'], '-', TRUE) == $page_id)  
          {
            return $page['id'];
          }
		}
        
        return $page_id;
    }    
    
	public function get_nested ($lang_id=2)
	{
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->where('language_id', $lang_id);
        $this->db->not_like('type', 'MODULE_', 'after');
        $this->db->where('type !=', 'ARTICLE');
        $this->db->order_by($this->_order_by);
		$query = $this->db->get();
        $pages = $query->result_array();
        
        $array = array();
        $tmp_arr = array();
		foreach ($pages as $page) {
			if (! $page['parent_id']) {
				// This page has no parent
				$array[$page['id']] = $page;
			}
			else {
                            // This is a child page
                            // show only 2x lvl
                            if(isset( $array[$page['parent_id']]))
                                $array[$page['parent_id']]['children'][] = $page;
			}
		}
		return $array;
	}
    
	public function get_nested_tree ($lang_id=2)
	{
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->where('language_id', $lang_id);
        $this->db->not_like('type', 'MODULE_', 'after');
        $this->db->order_by($this->_order_by);
		$pages = $this->db->get()->result_array();
		
		$array = array();
        $tmp_arr = array();
		foreach ($pages as $page) {
		   $array[$page['parent_id']][$page['id']] = $page;
		}
		return $array;
	}
    
	public function get_nested_news_categories ($lang_id=2)
	{
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.id = '.$this->_table_name.'_lang.page_id');
        $this->db->where('language_id', $lang_id);
        $this->db->where('type', 'MODULE_NEWS_CATEGORY');
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
        $this->db->delete('slugs', array('model_name'=>'page_m',
                                         'model_id'=>$id)); 
        
        $this->db->delete('page_lang', array('page_id' => $id)); 
        
        // Remove repository
        $page_data = $this->get($id, TRUE);
        if(sw_count($page_data))
        {
            $this->repository_m->delete($page_data->repository_id);
        }
        
        parent::delete($id);
        
		// Reset parent ID for its children
		$this->db->set(array(
			'parent_id' => 0
		))->where('parent_id', $id)->update($this->_table_name);
    }

}


