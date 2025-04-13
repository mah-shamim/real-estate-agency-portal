<?php

class Promocode_m extends MY_Model {
    
    protected $_table_name = 'promocode';
    protected $_order_by = 'id ASC';
    
    public $all_rules = array(
        'code_name' => array('field'=>'code_name', 'label'=>'lang:Code Name', 'rules'=>'trim|required|xss_clean'),
        'value' => array('field'=>'value', 'label'=>'lang:Value', 'rules'=>'trim|required|xss_clean|numeric'),
        'quantity' => array('field'=>'quantity', 'label'=>'lang:Quantity', 'rules'=>'trim|required|xss_clean|numeric'),
        'usage' => array('field'=>'usage', 'label'=>'lang:Usage', 'rules'=>'trim|required|xss_clean|numeric'),
        'packages' => array('field'=>'packages', 'label'=>'lang:Packages', 'rules'=>'trim|required|xss_clean'),
        'date_start' => array('field'=>'date_start', 'label'=>'lang:Date Start', 'rules'=>'trim|required|xss_clean'),
        'date_end' => array('field'=>'date_end', 'label'=>'lang:Date End', 'rules'=>'trim|required|xss_clean|callback__check_date'),
        'used' => array('field'=>'used', 'label'=>'lang:Used', 'rules'=>'trim|required|xss_clean|numeric'),
        'promocode' => array('field'=>'promocode', 'label'=>'lang:Promocode', 'rules'=>'trim|required|callback__check_unique_code'),
        'date_created' => array('field'=>'date_created', 'label'=>'lang:Date Create', 'rules'=>'trim|xss_clean')
    );
    
    public $rules = array(
        'code_name' => array('field'=>'code_name', 'label'=>'lang:Code Name', 'rules'=>'trim|required|xss_clean'),
        'value' => array('field'=>'value', 'label'=>'lang:Value', 'rules'=>'trim|required|xss_clean|decimal'),
        'quantity' => array('field'=>'quantity', 'label'=>'lang:Quantity', 'rules'=>'trim|required|xss_clean|numeric'),
        'usage' => array('field'=>'usage', 'label'=>'lang:Usage', 'rules'=>'trim|required|xss_clean'),
        'packages' => array('field'=>'packages', 'label'=>'lang:Packages', 'rules'=>'required'),
        'date_start' => array('field'=>'date_start', 'label'=>'lang:Date Start', 'rules'=>'trim|required|xss_clean'),
        'date_end' => array('field'=>'date_end', 'label'=>'lang:Date End', 'rules'=>'trim|required|xss_clean|callback__check_date'),
        'promocode' => array('field'=>'promocode', 'label'=>'lang:Promocode', 'rules'=>'trim|required|callback__check_unique_code'),
        'date_created' => array('field'=>'date_created', 'label'=>'lang:Date Create', 'rules'=>'trim|xss_clean')
    );
    
    public $usage_array = array();
    public $packages_array = array();

    public function __construct(){
        parent::__construct();
            
        $this->usage_array = array(
            'unique' => lang_check('Unique'),
            'multiple' => lang_check('Multiple')
        );
        
        $this->load->model('packages_m');
        $packages = $this->packages_m->get();
        foreach ($packages as $package) {
            $this->packages_array [$package->id] = $package->id.'. '.$package->package_name;
        }
    }
    
    public function get_new()
    {
        $item = new stdClass();
        $item->code_name = '';
        $item->value = '';
        $item->quantity = '';
        $item->packages = [];
        $item->usage = '';
        $item->date_start = '';
        $item->date_end = '';
        $item->used = '';
        $item->promocode = NULL;
        $item->date_created = '';
        return $item;
    }
    
    public function get($id = NULL, $single = false)
    {
        return parent::get($id,$single);
    }
    
    /* delete all */
    public function delete_all () {
        $this->db->empty_table($this->_table_name);
    }
    
    public function get_packages ($str, $output = FALSE) {
        $packages = [];
        $str = explode(',', $str);
        foreach ($str as $id) {
            if(empty($id)) continue;
            if(isset($this->packages_array [$id]))
                $packages[$id] = $this->packages_array [$id];
        }
        
        if($output){
            return implode(', ', $packages);
        } else {
            return $packages;
        }
    }
    
    public function get_promoode ($promocode = '') {
        
        if(empty($promocode)) return FALSE;
        
        $this->db->where('promocode', $promocode);
        $query = $this->db->get($this->_table_name);
        if ($query->num_rows() > 0)
        {
           return $query->row();
        }
        
        return FALSE;
        
    }
    
        
    public function get_user_promocodes ($id = NULL) {
        
        if(empty($id)) return FALSE;
        
        $packages = [];
        $this->load->model('user_m');
        
        $user = $this->user_m->get($id);
        
        if(empty($user)) return FALSE;
        
        if(!isset($user->promocode_added)) return FALSE;

        $str = explode(',', $user->promocode_added);
        foreach ($str as $id) {
            if(empty($id)) continue;
            $promo = $this->get($id);
            if($promo)
                $packages[$promo->id] = $promo;
        }
        
        return $packages;
    }
        
    public function get_user_promocodes_activated ($id = NULL) {
        
        if(empty($id)) return FALSE;
        
        $packages = [];
        $this->load->model('user_m');
        
        $user = $this->user_m->get($id);

        if(empty($user)) return FALSE;
        
        if(isset($user->promocode_activated)) {
            $str = explode(',', $user->promocode_activated);
            foreach ($str as $id) {
                if(empty($id)) continue;
                $promo = $this->get($id);
                if($promo)
                    $packages[$promo->id] = $promo;
            }
        }

        return $packages;
    }
        
    public function get_user_promocode_exist ($user_id = NULL , $code_id = NULL) {
        
        if(empty($user_id) || empty($code_id)) return FALSE;
        
        $packages = [];
        $this->load->model('user_m');
        
        $user = $this->user_m->get($user_id);
        
        if(empty($user)) return FALSE;

        if(!isset($user->promocode_added)) return FALSE;

        $pacs = explode(',', $user->promocode_activated.$user->promocode_added);
        if(in_array($code_id, $pacs)!== FALSE){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function add_user_promocode($user_id = NULL , $code_id = NULL) {
        
        if(empty($user_id) || empty($code_id)) return FALSE;
        
        $packages = [];
        $this->load->model('user_m');
        
        $user = $this->user_m->get($user_id);
        $promo = $this->get($code_id);
        
        if(empty($user) || empty($promo)) return FALSE;
        
        if($promo->used >= $promo->quantity) return FALSE;
        
        if(!isset($user->promocode_added)) return FALSE;

        $user->promocode_added .= ','.$code_id;
        $dat_user['promocode_added'] = [];
        $dat_user['promocode_added'] = $user->promocode_added;
        $this->user_m->save($dat_user, $user_id);
        
        $dat_promo = [];
        $dat_promo['used'] = $promo->used + 1;
        $this->save($dat_promo, $code_id);
        return TRUE;
    }
    
            
    public function get_user_discount_by_pac ($id = NULL) {
        
        if(empty($id)) return FALSE;
        
        $packages = [];
        $this->load->model('user_m');
        
        $user = $this->user_m->get($id);
        
        if(empty($user)) return FALSE;
        
        $packages_sales = [];
        
        foreach ($this->packages_array as $key => $value) {
            $packages_sales[$key] = 0;
        }

        if(!isset($user->promocode_added)) return FALSE;

        $user_pacs = explode(',', $user->promocode_added);
        foreach ($user_pacs as $user_pac) {
            if(empty($user_pac)) continue;
            $promo = $this->get($user_pac);
            $promo_pacs_str = explode(',', $promo->packages);
            foreach ($packages_sales as $packages_sale_id => $packages_sale_val) {
                if(in_array($packages_sale_id,$promo_pacs_str) !==FALSE){
                    $packages_sales[$packages_sale_id] = $promo->value;
                }
            }
        }
        
        return $packages_sales;
        
    }
    
}



