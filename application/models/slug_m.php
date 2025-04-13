<?php

class Slug_m extends MY_Model {
    
    protected $_table_name = 'slugs';
    protected $_order_by = 'id DESC';
    public $rules = array(
    );
    public $rules_admin = array(
        'slug' => array('field'=>'slug', 'label'=>'lang:URI slug', 'rules'=>'trim|required|callback__unique_slug|callback__check_slug|xss_clean'),
        'real_url' => array('field'=>'real_url', 'label'=>'lang:Real URL', 'rules'=>'trim|required|xss_clean')
    );
    
    public $cache_slugs = array();
    public $cache_slugs_lang_id = array();

	public function __construct(){
		parent::__construct();
        
        // Cache slugs
        $this->cache_slugs = array();
        $this->cache_slugs_lang_id = array();
        
        $query = $this->db->get($this->_table_name);
        
        //TODO: Memory usage should be reduced on large number of properties (10k properties = 20MB usage reduction)
        //$query = $this->db->get_where($this->_table_name, array('model_name !=' => 'estate_m'));
        
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
              $this->cache_slugs[$row->slug] = $row;
              $this->cache_slugs_lang_id[$row->model_name.'_'.$row->model_id.'_'.$row->model_lang_code] = $row;
           }
        } 
	}
    
    public function get_new()
	{
        $item = new stdClass();
        $item->slug = '';
        $item->real_url = '';
        return $item;
	}
    
    // Get cached slug or return false
    public function get_slug($slug)
    {
        if(isset($this->cache_slugs_lang_id[$slug]))
        {
            return $this->cache_slugs_lang_id[$slug];
        }
        else if(isset($this->cache_slugs[$slug]))
        {
            return $this->cache_slugs[$slug];
        }
        else
        {
            return FALSE;
        }
    }
    
    public function save_slug($model_name, $id, &$data_lang, $data=array())
    {
        $this->db->delete($this->_table_name, array('model_name'=>$model_name,
                                                    'model_id'=>$id)); 
        foreach($data_lang as $data_key=>$data_val)
        {
            $pos = strrpos($data_key, "_");
            if(substr($data_key,0,$pos) == 'slug')
            {
                $lang_id = substr($data_key, $pos+1);
                
                $lang_code = '';
                if(!empty($this->language_m->db_languages_id[$lang_id]))
                    $lang_code = $this->language_m->db_languages_id[$lang_id];
                
                $data_val = url_title_cro($data_val, 'dash');
                
                // If slug field empty
                if($model_name == 'page_m')
                {
                    if(empty($data_val) && !empty($data_lang['title_'.$lang_id]))$data_val = url_title_cro($data_lang['title_'.$lang_id], 'dash');
                }
                else if($model_name == 'treefield_m')
                {
                    if(empty($data_val) && !empty($data_lang['title_'.$lang_id]))$data_val = url_title_cro($data_lang['title_'.$lang_id], 'dash');
                    if(empty($data_val) && !empty($data_lang['value_'.$lang_id]))$data_val = url_title_cro($data_lang['value_'.$lang_id], 'dash');
                }
                else if($model_name == 'estate_m')
                {
                    // 10 is #id of property name field
                    if(empty($data_val) && !empty($data_lang['option10_'.$lang_id]))
                    {
                        $data_val = url_title_cro($data_lang['option10_'.$lang_id], 'dash');
                        
                        // Add lang suffix example
                        //$data_val = $data_val.'-'.$lang_code;
                    }
                }

                //Check if data_val exists and generate new version
                $gen_suffix = 1;
                do{
                    $query = $this->db->get_where($this->_table_name, array('slug'=>$data_val));
                    $num_rows = $query->num_rows();
                    if ($num_rows > 0)
                    {
                        $data_val.=$gen_suffix;
                    }
                }while($num_rows>0 && $gen_suffix<50);
                
                $data = array(
                   'model_name' => $model_name,
                   'model_id' => $id,
                   'model_lang_id' => $lang_id,
                   'model_lang_code' => $lang_code,
                   'slug' => $data_val
                );
                
                $data_lang[$data_key] = $data_val;
                
                if(!empty($data_val))
                    $this->db->insert($this->_table_name, $data); 
            }
        }

    }
    
}



