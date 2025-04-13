<?php

class Payments_m extends MY_Model {
    
    protected $_table_name = 'payments';
    protected $_order_by = 'id';
    public $rules = array();
    
    public $currencies = array(
                               'AUD'=>'AUD - Australian Dollar',
                               'BRL'=>'BRL - Brazilian Real',
                               'CAD'=>'CAD - Canadian Dollar',
                               'CZK'=>'CZK - Czech Koruna',
                               'DKK'=>'DKK - Danish Krone ',
                               'EUR'=>'EUR - Euro', 
                               'HKD'=>'HKD - Hong Kong Dollar',
                               'HUF'=>'HUF - Hungarian Forint',
                               'ILS'=>'ILS - Israeli New Sheqel',
                               'JPY'=>'JPY - Japanese Yen',
                               'MYR'=>'MYR - Malaysian Ringgit',
                               'MXN'=>'MXN - Mexican Peso',
                               'NOK'=>'NOK - Norwegian Krone', 
                               'NZD'=>'NZD - New Zealand Dollar',
                               'PHP'=>'PHP - Philippine Peso',
                               'PLN'=>'PLN - Polish Zloty',
                               'GBP'=>'GBP - Pound Sterling',
                               'RUB'=>'RUB - Russian Ruble',
                               'SGD'=>'SGD - Singapore Dollar',
                               'SEK'=>'SEK - Swedish Krona ',
                               'CHF'=>'CHF - Swiss Franc',
                               'TWD'=>'TWD - Taiwan New Dollar',
                               'THB'=>'THB - Thai Baht',
                               'TRY'=>'TRY - Turkish Lira', 
                               'USD'=>'USD - U.S. Dollar'
                               );
    
    public function get_new()
	{
        $item = new stdClass();
        $item->invoice_num = '';
        $item->date_paid = date('Y-m-d H:i:s');
        $item->data_post = '';
        
        return $page;
	}
    
    public function get_by_check($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = "")
    {
        $this->db->from($this->_table_name);
        
        if($where !== NULL) $this->db->where($where);
        if($order_by !== NULL) $this->db->order_by($order_by);
        if($limit !== NULL) $this->db->limit($limit, $offset);

        $query = $this->db->get();
        //echo $this->db->last_query();
        
        $results = $query->result();
        
        if($this->session->userdata('type') != 'ADMIN')
        {
            $this->load->model('reservations_m');
            
            foreach($results as $key=>$row){
                $reservation_id = substr($row->invoice_num, 0, strpos($row->invoice_num,'_'));
                $found_num = $this->reservations_m->check_user_permission($reservation_id, $this->session->userdata('id'));
                
                if($found_num == 0)
                    unset($results[$key]);
            }
        }

        return $results;
    }

}


