<?php

class Invoice_m extends MY_Model {
    
    protected $_table_name = 'invoice';
    protected $_order_by = 'is_paid DESC, id_invoice DESC';
    protected $_primary_key = 'id_invoice';

    public $rules = array(
        'amount' => array('field'=>'amount', 'label'=>'lang:Amount', 'rules'=>'trim|required|xss_clean|is_numeric|greater_than[4]|callback__amount_check'),
        'currency' => array('field'=>'currency', 'label'=>'lang:Currency code', 'rules'=>'trim|required|xss_clean'),
        'withdrawal_email' => array('field'=>'withdrawal_email', 'label'=>'lang:Withdrawal email', 'rules'=>'trim|required|xss_clean|valid_email')
    );
    
    public $rules_admin = array(
        'is_paid' => array('field'=>'is_paid', 'label'=>'lang:Paid', 'rules'=>'trim'),
        'is_activated' => array('field'=>'is_activated', 'label'=>'lang:Activated', 'rules'=>'trim')
    );
    
}



