<?php

class Withdrawal_m extends MY_Model {
    
    protected $_table_name = 'withdrawal';
    protected $_order_by = 'id_withdrawal DESC';
    protected $_primary_key = 'id_withdrawal';

    public $rules = array(
        'amount' => array('field'=>'amount', 'label'=>'lang:Amount', 'rules'=>'trim|required|xss_clean|is_numeric|greater_than[4]|callback__amount_check'),
        'currency' => array('field'=>'currency', 'label'=>'lang:Currency code', 'rules'=>'trim|required|xss_clean'),
        'withdrawal_email' => array('field'=>'withdrawal_email', 'label'=>'lang:Withdrawal email', 'rules'=>'trim|required|xss_clean|valid_email')
    );
    
    public $rules_admin = array(
        'user_id' => array('field'=>'user_id', 'label'=>'lang:User', 'rules'=>'trim|required|xss_clean'),
        'completed' => array('field'=>'completed', 'label'=>'lang:Completed', 'rules'=>'trim|xss_clean'),
        'date_requested' => array('field'=>'date_requested', 'label'=>'lang:Date requested', 'rules'=>'trim|required|xss_clean'),
        'date_completed' => array('field'=>'date_completed', 'label'=>'lang:Date completed', 'rules'=>'trim|xss_clean'),
        'amount' => array('field'=>'amount', 'label'=>'lang:Amount', 'rules'=>'trim|required|xss_clean|is_numeric|greater_than[9]|callback__amount_check'),
        'currency' => array('field'=>'currency', 'label'=>'lang:Currency code', 'rules'=>'trim|required|xss_clean'),
        'withdrawal_email' => array('field'=>'withdrawal_email', 'label'=>'lang:Withdrawal email', 'rules'=>'trim|required|xss_clean|valid_email')
    );

    public function get_amounts($user_id)
    {
        $amounts = array();
        
        $this->db->where('date_from <=', date('Y-m-d H:i:s', time()-24*60*60)); 
        $this->db->where('total_paid >', 0);
        $this->db->where('saller_id', $user_id); 
        $query = $this->db->get('reservations');
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
           if($row->total_paid > 0)
           {
                if(!isset($amounts[$row->currency_code]))
                {
                    $amounts[$row->currency_code] = 0;
                }
                
                $amounts[$row->currency_code]+=
                    $row->total_paid-$row->total_paid*$row->booking_fee/100;
           }
           }
        }
        
        $this->db->where('user_id', $user_id); 

        $query = $this->db->get('withdrawal');
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                if(!isset($amounts[$row->currency]))
                {
                    $amounts[$row->currency] = 0;
                }
                
                $amounts[$row->currency]-=$row->amount;
           }
        } 

        return $amounts;
    }
    
    public function get_pending($user_id)
    {
        $amounts = array();
        
        $this->db->where('date_from >', date('Y-m-d H:i:s', time()-24*60*60)); 
        $this->db->where('total_paid >', 0);
        $this->db->where('saller_id', $user_id); 
        $query = $this->db->get('reservations');
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
           if($row->total_paid > 0)
           {
                if(!isset($amounts[$row->currency_code]))
                {
                    $amounts[$row->currency_code] = 0;
                }
                
                $amounts[$row->currency_code]+=
                    $row->total_paid-$row->total_paid*$row->booking_fee/100;
           }
           }
        }

        return $amounts;
    }
    
    public function delete($id)
    {      
        $withdrawal = $this->get($id);
        if($withdrawal->completed == 0)
        {
            parent::delete($id);
            return TRUE;
        }
        
        return FALSE;
    }
    
}



