<?php

class Conversions_m extends MY_Model {
    
    protected $_table_name = 'conversions';
    protected $_order_by = 'currency_code ASC';

    public $rules_admin = array(
        'currency_code' => array('field'=>'currency_code', 'label'=>'lang:Currency code', 'rules'=>'trim|required|xss_clean|alpha|strtoupper|max_length[5]'),
        'currency_symbol' => array('field'=>'currency_symbol', 'label'=>'lang:Currency symbol', 'rules'=>'trim|xss_clean|max_length[5]'),
        'conversion_index' => array('field'=>'conversion_index', 'label'=>'lang:Conversion index', 'rules'=>'trim|required|xss_clean|numeric'),
    );
    
	public function __construct(){
		parent::__construct();
	}
    
    public function get_new()
	{
        $item = new stdClass();
        $item->conversion_index = 1;
        $item->currency_code = '';
        $item->currency_symbol = '';
        return $item;
	}
    
    public function get_conversions_table()
    {
        $query = $this->db->get($this->_table_name);
        
        $conversions_table = array();
        
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                $conversions_table['code'][$row->currency_code] = $row;
                $conversions_table['symbol'][$row->currency_symbol] = $row;
           }
        }
        
        return $conversions_table;
    }

    public function delete($id)
    {
//        $this->db->where('language_id', $id);
//        $this->db->delete('page_lang');

        return parent::delete($id);
    }

}



