<?php

class Reservations_m extends MY_Model {
    
    protected $_table_name = 'reservations';
    protected $_order_by = 'id DESC';
    public $rules_admin = array(
        'user_id' => array('field'=>'user_id', 'label'=>'lang:User', 'rules'=>'trim|required|intval'),
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property', 'rules'=>'trim|required|intval'),
        'date_from' => array('field'=>'date_from', 'label'=>'lang:From date', 'rules'=>'trim|required|callback__check_availability|xss_clean'),
        'date_to' => array('field'=>'date_to', 'label'=>'lang:To date', 'rules'=>'trim|required|xss_clean'),
        'total_price' => array('field'=>'total_price', 'label'=>'lang:Total price', 'rules'=>'trim|numeric|xss_clean'),
        'total_paid' => array('field'=>'total_paid', 'label'=>'lang:Total paid', 'rules'=>'trim|numeric|xss_clean'),
        'date_paid_advance' => array('field'=>'date_paid_advance', 'label'=>'lang:Date paid advance', 'rules'=>'trim|xss_clean'),
        'date_paid_total' => array('field'=>'date_paid_total', 'label'=>'lang:Date paid total', 'rules'=>'trim|xss_clean'),
        'currency_code' => array('field'=>"currency_code", 'label'=>'lang:Currency code', 'rules'=>'trim|required|xss_clean')
    );
    
    public $rules_admin_multiple = array(
        'user_id' => array('field'=>'user_id', 'label'=>'lang:User', 'rules'=>'trim|required|intval'),
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Property', 'rules'=>'trim|required|intval'),
        'dates' => array('field'=>'dates', 'label'=>'lang:Dates', 'rules'=>'trim|required'),
    );
    
    public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $page = new stdClass();
        $page->property_id = 0;
        $page->user_id = 0;
        $page->date_from = date('Y-m-d H:i:s');
        $page->date_to = date('Y-m-d H:i:s');
        $page->total_price = 0;
        $page->total_paid = 0;
        $page->date_paid_advance = '';
        $page->date_paid_total = '';
        $page->currency_code = 'NULL';
        $page->is_confirmed = '0';
        
        return $page;
	}
    
    public function is_booked($property_id, $date_from, $date_to, $except_id = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('property_id', $property_id);
        $this->db->where('is_confirmed', '1');
        
        if(is_numeric($except_id))
        {
            $this->db->where('id !=', $except_id);
        }
        
        // Check dates availability
        $this->db->where('date_from <', $date_to);
        $this->db->where('date_to >', $date_from);
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }
    
    public function days_between($date_from, $date_to)
    {
        return ceil(abs(strtotime($date_to) - strtotime($date_from)) / 86400);
    }
    
    public function week_day_index($date)
    {
        if(jddayofweek(unixtojd(strtotime($date))) == 0)
            return 7;
        
        return jddayofweek(unixtojd(strtotime($date)));
    }
    
    public function get_available_properties($date_from, $date_to)
    {
//        echo $date_from.' - '.$date_to;
//        exit();
        
        $available_properties = array();
        $available_properties_from = array();
        $available_properties_to = array();
        
        $this->db->select('*');
        $this->db->from('rates');
        $this->db->where('date_from <', $date_from);
        $query_rates = $this->db->get();

        if ($query_rates->num_rows() > 0)
        {
           foreach ($query_rates->result() as $row)
           {
                $available_properties_from[$row->property_id] = $row->date_to;
           }
        }
        
        $this->db->select('*');
        $this->db->from('rates');
        $this->db->where('date_to >', $date_to);
        $query_rates = $this->db->get();

        if ($query_rates->num_rows() > 0)
        {
           foreach ($query_rates->result() as $row)
           {
                if(isset($available_properties_from[$row->property_id]))
                {
                    if((date("Y-m-d", strtotime($available_properties_from[$row->property_id])) == 
                       date("Y-m-d", strtotime($row->date_from))) ||
                       ($available_properties_from[$row->property_id] == $row->date_to) ||
                       strtotime($date_from) > strtotime($row->date_from)
                       )
                    {
                        $available_properties_to[$row->property_id] = $row->property_id;
                    }
                }
                
           }
        }
        
        $available_properties = $available_properties_to;
        
        $this->db->select('*');
        $this->db->from('reservations');
        $this->db->where('date_from <', $date_to);
        $this->db->where('date_to >', $date_from);
        $this->db->where('is_confirmed', '1');
        $query_reservations = $this->db->get();
        
        if ($query_reservations->num_rows() > 0)
        {
           foreach ($query_reservations->result() as $row)
           {
                if(isset($available_properties[$row->property_id]))
                    unset($available_properties[$row->property_id]);
           }
        }
        
        return $available_properties;
    }
    
    public function get_available_dates($property_id, $show_changeover = TRUE)
    {
        $available_dates = array();
        
        $this->db->select('*');
        $this->db->from('rates');
        $this->db->where('property_id', $property_id);
        $this->db->where('date_to >', date("Y-m-d H:i:s"));
        $this->db->order_by('date_from'); 
        
        $query_rates = $this->db->get();
        if ($query_rates->num_rows() > 0)
        {
           foreach ($query_rates->result() as $row)
           {
                /* [get days] */
                $days_between = $this->days_between($row->date_from, $row->date_to);
                
                $days = array();
                //for($i=0; $i <= $days_between;  $i++)
                for($i=0; $i < $days_between;  $i++)
                {
                    $row_time = strtotime($row->date_from." + $i day");
                    $row_time_00 = strtotime(date("Y-m-d", strtotime($row->date_from." + $i day")));
                    if($row_time > time())
                    {
                        if($show_changeover && !empty($row->changeover_day))
                        {
                            if($row->changeover_day == $this->week_day_index($row->date_from." + $i day"))
                            {
                                $available_dates[date("Y-m-d", $row_time)] = $row_time_00;
                            }
                        }
                        else
                        {
                            $available_dates[date("Y-m-d", $row_time)] = $row_time_00;
                        }
                    }
                }
                /* [/get days] */
           }
        }

        // Remove booked days
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('property_id', $property_id);
        $this->db->where('is_confirmed', '1');
        $this->db->where('date_to >', date("Y-m-d H:i:s"));
        $this->db->order_by('date_from'); 
         
        $query_booked = $this->db->get();
        if ($query_booked->num_rows() > 0)
        {
           foreach ($query_booked->result() as $row_booked)
           {
                //echo $row_booked->date_from.' | '.$row_booked->date_to.'<br />';
            
                /* [get days] */
                $days_between = $this->days_between($row_booked->date_from, $row_booked->date_to);
                
                $days = array();
                for($i=0; $i < $days_between;  $i++)
                {
                    $row_time = strtotime($row_booked->date_from." + $i day");
                    
                    //echo $row_booked->date_from." + $i day".'<br />';
                    //echo 'UNSET: '.date("Y-m-d", $row_time).'<br />';
                    
                    if(isset($available_dates[date("Y-m-d", $row_time)]))
                        unset($available_dates[date("Y-m-d", $row_time)]);
                }
                /* [/get days] */
           }
        }
        
        //print_r($available_dates);
        
        return $available_dates;
    }
    
    public function min_stay($property_id, $date_from, $date_to)
    {
        $days_between = $this->days_between($date_from, $date_to);
        
        $this->db->select('*');
        $this->db->from('rates');
        $this->db->where('property_id', $property_id);
        
        // get dates
        $this->db->where('date_from <', $date_from);
        $this->db->where('date_to >', $date_from);
        
        $query_rates = $this->db->get();
        if ($query_rates->num_rows() > 0)
        {
           $row = $query_rates->row();
        
           if($row->min_stay <= $days_between)
           {
                if(empty($row->changeover_day))
                {
                    return TRUE;
                }
                else if($days_between % $row->min_stay == 0)
                {
                    return TRUE;
                }
           }
           
           return FALSE;
        } 

        return NULL;
    }
    
    public function changeover_day($property_id, $date_from)
    {
        $this->db->select('*');
        $this->db->from('rates');
        $this->db->where('property_id', $property_id);
        
        // get dates
        $this->db->where('date_from <', $date_from);
        $this->db->where('date_to >', $date_from);
        
        $query_rates = $this->db->get();
        if ($query_rates->num_rows() > 0)
        {
           $row = $query_rates->row();
           
           //echo 'changeover_day: '.$row->changeover_day.'<br />';
           //echo 'week_day_index: '.$this->week_day_index($date_from).'<br />';
           
           if(empty($row->changeover_day))
                return TRUE;
            
           if($row->changeover_day == $this->week_day_index($date_from))
                return TRUE;
                
           return FALSE;
        } 

        return NULL;
    }

   
    public function calculate_price($property_id, $date_from, $date_to, $currency = 'USD')
    {
        /* [get rates] */
        $this->db->select('*');
        $this->db->from('rates');
        $this->db->join('rates_lang', 'rates.id = rates_lang.rates_id');
        $this->db->where('property_id', $property_id);
        $this->db->where('currency_code', $currency);
        
        // get dates
        $this->db->where('date_from <=', $date_to);
        $this->db->where('date_to >=', $date_from);
        
        $query_rates = $this->db->get();
        $results_rates = $query_rates->result();
        /* [/get rates] */
        
        /* [get days] */
        $days_between = $this->days_between($date_from, $date_to);
        
        $days = array();
        for($i=0; $i < $days_between;  $i++)
        {
            $days[] = date('Y-m-d',strtotime($date_from." + $i day"));
        }
        /* [/get days] */

        /* [get day prices] */
        $days_prices = array();
        foreach($days as $key=>$day)
        {
            foreach($results_rates as $rate)
            {
                if(date('Y-m-d',strtotime($rate->date_from))<=$day && 
                   date('Y-m-d',strtotime($rate->date_to)) >=$day)
                {
                    if($days_between>29 && !empty($rate->rate_monthly))
                    {
                        $days_prices[$key] = $rate->rate_monthly / 30;
                    }
                    elseif($days_between>6 && !empty($rate->rate_weekly))
                    {
                        $days_prices[$key] = $rate->rate_weekly / 7;
                    }
                    elseif(!empty($rate->rate_nightly))
                    {
                        $days_prices[$key] = $rate->rate_nightly;
                    }
                    else
                    {
                        $days_prices[$key] = $rate->rate_weekly / 7;
                    }

                    break;
                }
            }
        }
        /* [/get day prices] */
        
        /* [price calculation] */
        if(sw_count($days_prices) == $days_between)
        {
            return array_sum($days_prices);
        }
        /* [/price calculation] */
        
        return FALSE;
    }
    
    public function delete($id)
    {      
        $reservation = $this->get($id);
        if($reservation->total_paid > 0)
        {
            return FALSE;
        }
        
        parent::delete($id);
        
        return TRUE;
    }
    
    public function check_user_permission($reservation_id, $user_id)
    {
        $reservation = $this->get($reservation_id);
        
        if(isset($reservation->property_id))
            $property_id = $reservation->property_id;
        else
            return 0;
        
        $this->db->where('property_id', $property_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('property_user');
        return $query->num_rows();
    }
    
    public function get_by_check($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = "", $seller=false)
    {
        $select = $this->_table_name.'.*, property_user.user_id as p_user_id, user.username as buyer_username';

        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
        $this->db->join('user', $this->_table_name.'.saller_id = user.id', 'left');
        
        if($seller == true)
        {
            $this->db->where($this->_table_name.'.saller_id', $this->session->userdata('id'));
        }
        else if($this->session->userdata('type') != 'ADMIN')
        {
            $this->db->where('property_user.user_id', $this->session->userdata('id'));
        }
        
        if($where !== NULL) $this->db->where($where);
        if($order_by !== NULL) $this->db->order_by($order_by);
        if($limit !== NULL) $this->db->limit($limit, $offset);
        
        if(!empty($search))
        {
            //$this->db->where("(address LIKE '%$search%' OR name_surname LIKE '%$search%')");
        }
          
        $query = $this->db->get();
        
        // echo $this->db->last_query();

        return $query->result();
    }
    
    public function save($data, $id = NULL)
    {
        // Correct times to 12:00
        if(isset($data['date_from']))
        {
            $data['date_from'] = date('Y-m-d 12:00:00', strtotime($data['date_from']));
        }
        
        if(isset($data['date_to']))
        {
            $data['date_to'] = date('Y-m-d 12:00:00', strtotime($data['date_to']));
        }
        
        // Insert saller_id
        if(!empty($data['property_id']) && $id == NULL)
        {
            // Fetch user_id related to property_id
            $this->load->model('estate_m');
            $data['saller_id'] = $this->estate_m->get_user_id($data['property_id']);
        }
        
        // Insert booking_fee percentage
        if($id == NULL)
        {
            $this->load->model('settings_m');
            $settings = $this->settings_m->get_fields();
            if(!empty($settings['booking_fee']))
            {
                $data['booking_fee'] = intval($settings['booking_fee']);
            }
        }
        
        return parent::save($data, $id);
    }
    
    public function add_multiple($data)
    {
//        var_dump($data);
//        array(3) { ["user_id"]=> int(8) ["property_id"]=> int(30) ["dates"]=> string(70) "2015-10-09 2015-10-10 2015-10-11 2015-10-12 2015-10-13 2015-10-14" } 
        
        $data_i = array();
        
        if(empty($data['property_id']))
            exit('Wrong request!');

        // Fetch user_id related to property_id
        $this->load->model('estate_m');
        $data_i['saller_id'] = $this->estate_m->get_user_id($data['property_id']);
        
        $property_id = $data_i['property_id'] = $data['property_id'];
        $user_id = $data_i['user_id'] = $data['user_id'];
        $data_i['is_confirmed'] = 1;
        $data_i['currency_code'] = 'USD';
        $data_i['total_price'] = 0;
        $data_i['date_paid_advance'] = 0;
        $data_i['date_paid_total'] = 0;
        $data_i['total_paid'] = 0;
        $data_i['booking_fee'] = 0;
        
        //Load property reservations from yesterday, last todate
        $existing_res = array();
        $existing_dates = array();
        $last_to_date = date('Y-m-d');
        $query = $this->db->get_where('reservations', array('property_id' => $property_id, 'date_to >' => date('Y-m-d 00:00:00')));
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $existing_res[date('Y-m-d', strtotime($row->date_from))] = 
                              date('Y-m-d', strtotime($row->date_to));
                                
                if(strtotime($row->date_to) > strtotime($last_to_date))
                    $last_to_date = date('Y-m-d', strtotime($row->date_to));
                    
                /* [get days] */
                $days_between = $this->days_between($row->date_from, $row->date_to);
                
                $days = array();
                for($i=0; $i < $days_between;  $i++)
                {
                    $row_time = strtotime($row->date_from." + $i day");
                    $row_time_00 = date("Y-m-d", $row_time);
                    $existing_dates[$row_time_00] = strtotime($row_time_00);
                }
                /* [/get days] */
            }
        }
        
//        echo '<pre>';
//        var_dump($existing_dates);
//        echo '</pre>';
        
        $dates_reserved_iso = array();
        $dates_ranges_iso = array();
        $dates_import = explode("\n", $data['dates']);
        
        // clear already reserved dates
        foreach($dates_import as $key=>$date_curr) {
            if(isset($existing_dates[$date_curr]))
                unset($dates_import[$key]);
        }

        foreach($dates_import as $date_curr) {
            $date_curr = trim($date_curr);
            if(empty($date_curr))continue;
            
            $iso_date       = $date_curr;
            $row_time_00 = date("Y-m-d", strtotime($iso_date));
            $dates_reserved_iso[$row_time_00] = strtotime($row_time_00);
            
            $last_added_date_from = end(array_keys($dates_ranges_iso));

            if($last_added_date_from === NULL)
            {
                $last_added_date_from = $row_time_00;
                $dates_ranges_iso[$last_added_date_from] = date("Y-m-d", strtotime($row_time_00." + 1 day"));
            }
            else if($dates_ranges_iso[$last_added_date_from] == $row_time_00)
            {
                $dates_ranges_iso[$last_added_date_from] = date("Y-m-d", strtotime($row_time_00." + 1 day"));
            }
            else
            {
                $last_added_date_from = $row_time_00;
                $dates_ranges_iso[$last_added_date_from] = date("Y-m-d", strtotime($row_time_00." + 1 day"));
            }
        }
        
//        echo '<pre>';
//        var_dump($dates_ranges_iso);
//        echo '</pre>';
        
        $data_batch = array();
        $imported_counter = 0;
        if(!empty($user_id))
        foreach($dates_ranges_iso as $from_date=>$to_date)
        {
//            if(isset($existing_res[$from_date]))
//                continue;
            
            $date_prepare = $data_i;
            $date_prepare['date_from'] = date('Y-m-d 12:00:00', strtotime($from_date));
            $date_prepare['date_to'] = date('Y-m-d 12:00:00', strtotime($to_date));

            $data_batch[] = $date_prepare;

            $imported_counter++;
        }
        
//        echo '<pre>';
//        var_dump($data_batch);
//        echo '</pre>';
        
        // Import all rates for property
        if(sw_count($data_batch) > 0)
            $this->db->insert_batch('reservations', $data_batch);
        
        return $imported_counter;
    }

}


