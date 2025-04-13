<?php
class MY_Model extends CI_Model {
    
    protected $_table_name = '';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = '';
    public $rules = array();
    protected $_timestamps = FALSE;
    protected $_cache_temp = array();
    
	public function __construct(){
		parent::__construct();
	}
    
    public function get_primary_key()
    {
        return $this->_primary_key;
    }

    public function get_table_name()
    {
        return $this->_table_name;
    }
    
    public function array_from_post($fields)
    {
        $data = array();
        foreach($fields as $field)
        {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }
    
    public function get_all_rules()
    {
        if(isset($this->rules_lang))
            return array_merge($this->rules, $this->rules_lang);
            
        return $this->rules;
    }
    
    public function get_post_fields()
    {
        $post_fields = array();
        
        foreach($this->rules as $key=>$value)
        {
            $post_fields[] = $key;
        }
        
        return $post_fields;
    }
    
    public function array_from_rules($rules)
    {
        return $this->array_from_post($this->get_post_from_rules($rules));
    }
    
    public function get_post_from_rules($rules)
    {
        $post_fields = array();
        
        foreach($rules as $key=>$value)
        {
            $post_fields[] = $key;
        }
        
        return $post_fields;
    }
    
    public function max_order($parent_id = null)
    {
        //$this->db->select('MAX(`order`) as `order`', FALSE);
        
        
        if($parent_id == null)
        {
            // get max order
            $this->db->select('MAX(`order`) as `order`', FALSE);
        }
        else
        {
            // get max order
            $this->db->select('MAX(`order`) as `order`', FALSE);
            $this->db->where('parent_id', $parent_id);
            $this->db->or_where('id', $parent_id);
        }

        $query = $this->db->get($this->_table_name);
        
        if(is_object($query) && $query->num_rows() > 0)
        {
            $row = $query->row();
        }
        else
        {
            echo 'SQL problem in get max_order:';
            echo $this->db->last_query();
            exit();
        }
        
        return (int) $row->order;// + 1;
    }
    
    public function get_lang_post_fields($custom_rules = 'rules_lang')
    {
        $post_fields = array();
        
        if(isset($this->$custom_rules)){
            foreach($this->$custom_rules as $key=>$value)
            {
                $post_fields[] = $key;
            }
        }
        
        return $post_fields;
    }
    
    public function get($id = NULL, $single = FALSE)
    {
        if($id != NULL)
        {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_table_name.'.'.$this->_primary_key, $id);
            $method = 'row';
        }
        else if($single == TRUE)
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
        
        $query = $this->db->get($this->_table_name);
        if (is_object($query))
        {
            return $query->$method();
        }

        return array();
    }
    
    public function get_array($id = NULL, $single = FALSE)
    {
        if($id != NULL)
        {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_primary_key, $id);
            $method = 'row_array';
        }
        else if($single == TRUE)
        {
            $method = 'row_array';
        }
        else
        {
            $method = 'result_array';
        }
        
        if(!sw_count($this->db->ar_orderby))
        {
            $this->db->order_by($this->_order_by);
        }
        
        $query = $this->db->get($this->_table_name);
        if (is_object($query))
        {
            return $query->$method();
        }

        return array();
    }
    
    public function get_form_dropdown($column, $where = FALSE, $empty=TRUE, $show_id=FALSE)
    {
        $filter = $this->_primary_filter;
        
        if(!sw_count($this->db->ar_orderby))
        {
            $this->db->order_by($this->_order_by);
        }
        
        if($where)
            $this->db->where($where); 
            
        $query = $this->db->get($this->_table_name);
        
        if(!is_object($query))
        {
            $error = 'SQL problem in get_form_dropdown:'.'<br />';
            $error.= $this->db->last_query().'<br />';
            $error.= 'Posssible issue: <strong>Wrong database configuration</strong>'.'<br />';
            show_error($error);
        }
        
        $dbdata = $query->result_array();
        
        $results = array();
        if($empty)$results[''] = '';
        foreach($dbdata as $key=>$row){
            if(isset($row[$column]))
            {
                if(lang($row[$column]) != '')$row[$column] = lang($row[$column]);
                $results[$row[$this->_primary_key]] = $row[$column];
                
                if($show_id)
                {
                    $results[$row[$this->_primary_key]] = $row['id'].', '.$results[$row[$this->_primary_key]];
                }
                
            }
            
        }
        return $results;
    }
    
    public function get_by($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = "", $search = '')
    {
        $this->db->select('*');
        
        if(!empty($search))
        {
            $this->db->where("(address LIKE '%$search%' OR name_surname LIKE '%$search%')");
        }
        
        if($where !== NULL)
        {
            $this->db->where($where);
        }
        
        if($order_by !== NULL) $this->db->order_by($order_by);
        if($limit !== NULL) $this->db->limit($limit, $offset);

        return $this->get(NULL, $single);
    }
    
    public function get_array_by($where, $single = FALSE, $limit = NULL, $order_by = NULL)
    {
        if($order_by !== NULL) $this->db->order_by($order_by);
        if($limit !== NULL) $this->db->limit($limit);
        if($where !== NULL) $this->db->where($where);       
        return $this->get_array(NULL, $single);
    }
    
    public function save($data, $id = NULL)
    {
        // Set timestamps
        if($this->_timestamps == TRUE)
        {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
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
        
        return $id;
    }
    
    public function delete($id)
    {
        $filter = $this->_primary_filter;
        $id = $filter($id); 
        
        if(!$id)
        {
            return FALSE;
        }
        
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }
    
    public function total()
    {
        return $this->db->count_all($this->_table_name);
    }
    
    protected function cache_temp_load($var_name)
    {
        if(isset($this->_cache_temp[$var_name]))
        {
            return $this->_cache_temp[$var_name];
        }
        else
        {
            return FALSE;
        }
    }
    
    protected function cache_temp_save(&$var, $var_name)
    {
        $this->_cache_temp[$var_name] = $var;
    }
    
}