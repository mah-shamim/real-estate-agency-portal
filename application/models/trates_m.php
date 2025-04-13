<?php

/*

CREATE TABLE IF NOT EXISTS `trates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `date_from` DATETIME NOT NULL,
  `date_to` DATETIME NOT NULL,
  `min_stay` INT(11) NULL DEFAULT 1,
  `changeover_day` INT(11) NULL DEFAULT 6,
  `table_row_index` INT(11) NULL DEFAULT NULL,
  `code_dates` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

*/

class Trates_m extends MY_Model {
    
    protected $_table_name = 'trates';
    protected $_order_by = 'id DESC';
    public $rules = array(
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property', 'rules'=>'trim|required|intval'),
        'language_id' => array('field'=>'language_id', 'label'=>'lang:Language', 'rules'=>'trim|intval'),
        'min_stay' => array('field'=>'min_stay', 'label'=>'lang:Min stay', 'rules'=>'trim|intval|xss_clean'),
        'changeover_day' => array('field'=>'changeover_day', 'label'=>'lang:Changeover day', 'rules'=>'trim|intval|xss_clean'),
        'table_row_index' => array('field'=>'table_row_index', 'label'=>'lang:Table row', 'rules'=>'trim|intval|callback__check_exists|xss_clean'),
        'dates' => array('field'=>'dates', 'label'=>'lang:Dates', 'rules'=>'trim|xss_clean')
   );
   
   public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $page = new stdClass();
        $page->property_id = 0;
        $page->language_id = 0;
        $page->min_stay = 7;
        $page->changeover_day = 6;
        $page->table_row_index = 0;
        $page->dates = '';
        $page->rates = '';
        $page->date_from = '';
        $page->date_to = '';
        
        return $page;
	}
    
    public function get_property_rows($property_id, $lang_id)
    {
        $rows = array();

        if(empty($property_id))
        {
            $rows[''] = lang_check('Please select property and save');
        }
        else
        {
            $this->db->select('*');
            $this->db->from('property_value');
            $this->db->where('property_value.option_id', 76);
            $this->db->where('property_value.property_id', $property_id);
            $this->db->where('property_value.language_id', $lang_id);
            $this->db->limit(1);
            $query = $this->db->get();
            $res = $query->row();

            $parsed = parseTable($res->value);
            if(is_array($parsed))
            {
                foreach($parsed as $key=>$row)
                {
                    $rows[$key+1] = $row[0];
                }
            }
            else
            {
                $rows[''] = lang_check('Table is not populated');
            }
        }
            
        
        return $rows;
    }
    
    public function get_dates($property_id, $table_row_index)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('property_id', $property_id);
        $this->db->where('table_row_index', $table_row_index);
        $this->db->limit(1);
        
        $query = $this->db->get();
        $row = $query->row();
        
        if(!empty($row->dates))
            return $row->dates;
            
        return NULL;
    }

    public function get_rates($property_id, $table_row_index)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('property_id', $property_id);
        $this->db->where('table_row_index', $table_row_index);
        $this->db->limit(1);
        
        $query = $this->db->get();
        $row = $query->row();
        
        if(!empty($row->rates))
            return $row->rates;
            
        return NULL;
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


    
    public function is_defined($property_id, $table_row_index, $except_id = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('property_id', $property_id);
        $this->db->where('table_row_index', $table_row_index);
        
        if(is_numeric($except_id))
        {
            $this->db->where('id !=', $except_id);
        }
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }
    
    public function delete($id)
    {      
        parent::delete($id);
    }
    
    public function save($data, $id = NULL)
    {
        //$this->db->delete('trates', array('id' => $id, 'table_row_index' => $table_row_index));   
        
        return parent::save($data, $id);
    }

}


