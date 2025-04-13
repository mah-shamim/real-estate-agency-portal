<?php

class Mapreport_m extends MY_Model {
    
    protected $_table_name = 'map_report';
    protected $_order_by = 'id_mapreport DESC';
    protected $_primary_key = 'id_mapreport';
    public $rules = array();
    
    public $years = array(''=>'');
    public $purposes = array(''=>'');
    public $types = array(''=>'');
    public $outcomes = array(''=>'');
    
	public function __construct(){
		parent::__construct();
                  
        //Years
        for($i=intval(date('Y'))-5;$i<=intval(date('Y'));$i++)
        {
            $this->years[$i] = $i;
        }
	}
    
    public function get_new()
	{
//        $item = new stdClass();
//        $item->invoice_num = '';
//        $item->date_paid = date('Y-m-d H:i:s');
//        $item->data_post = '';
//        
//        return $page;
	}
    
    public function get_pagination($limit, $offset)
    {
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get($this->_table_name);
        
        if(!is_object($query))
            exit($this->db->last_query());
        
        if ($query->num_rows() > 0)
            return $query->result();
            
        return array();
    }

}


