<?php

class Packages extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('showroom_m');
        $this->load->model('rates_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        $this->load->model('packages_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index()
	{
        // Fetch all packages
        $this->data['packages'] = $this->packages_m->get();

        // Load view
		$this->data['subview'] = 'admin/packages/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function affilatepackage()
    {
        $this->load->model('treefield_m');
        $this->load->model('affilatepackages_m'); 

        $field_id = 64;
        $treefield_id = NULL;
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        
        $this->data['listings'] = $this->treefield_m->get_table_tree(
                                                            $this->data['content_language_id'], $field_id,
                                                            $treefield_id);
        
        $this->data['currency'] = $this->data['settings']['default_currency'];
        
        $this->data['affilate_users'] = $this->affilatepackages_m->get_users_affilate();
        
        // Load view
		$this->data['subview'] = 'admin/packages/affilatepackage';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function mypackage()
	{
        // Fetch all packages
        $this->data['packages'] = $this->packages_m->get();
        $this->data['packages_days'] = $this->packages_m->get_form_dropdown('package_days');
        $this->data['packages_listings'] = $this->packages_m->get_form_dropdown('num_listing_limit');
        $this->data['packages_price'] = $this->packages_m->get_form_dropdown('package_price');
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));

        // Load view
		$this->data['subview'] = 'admin/packages/mypackage';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function do_purchase_affilate($id = NULL, $price_pay = NULL)
    {
        $this->load->model('treefield_m');
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        $this->data['lang_code'] = $this->language_m->get_default();
        
	    // Fetch a page or set a new one
	    if(!empty($id) && !empty($price_pay))
        {
    	    // Fetch all estates
            $this->data['treefield'] = $this->treefield_m->get_lang($id, FALSE, $this->data['content_language_id']);

            /* [Payment configuration] */
            
    		$config['business'] 			= $this->data['settings']['paypal_email'];
    		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
    		$config['return'] 				= site_url('admin/packages/affilatepackage');
    		$config['cancel_return'] 		= site_url('admin/packages/cancel_payment');
    		$config['notify_url'] 			= site_url('frontend/notify_payment/'.$this->data['lang_code']); //IPN Post
    		$config['production'] 			= (ENVIRONMENT == 'production'); //Its false by default and will use sandbox
    		//$config['discount_rate_cart'] 	= 0; //This means 20% discount
    		$config["invoice"]				= $id.'_AFF_'.$this->data['user']['id'].'_'.$price_pay.'_'.date('w');//.rand(1,10000); //The invoice id
            $config["currency_code"]        = $this->data['settings']['default_currency'];
            
            if(empty($config['business']))
            {
                echo lang_check('PayPal email address missing');
                exit();
            }
            
            if(config_item('auto_paypal_payment') === TRUE)
            {
                $url = $config['notify_url'];
                
                $params = array();
                $params['invoice'] = $config["invoice"];
                $params['payer_id'] = substr(md5(time().rand(1,10000)), 0, 5);
                $params['txn_id'] =  md5(time().rand(1,10000));
                $params['mc_gross'] = $price_pay;
                $params['mc_currency'] = $config["currency_code"];
                $params['payer_email'] = $this->data['user']['mail'];
                
                $output = postCURL($url, $params);
                
                if(empty($output))
                    redirect('admin/packages/affilatepackage');
                    
                exit($output);
            }

    		$this->load->library('paypal', $config);
    		
    		#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
    		
    		$this->paypal->add('Affilate #'.$id.'', $price_pay, 1); //First item
    		//$this->paypal->add('Pants',1.99, 1); 	  //Second item
    		//$this->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
    		
    		$this->paypal->pay(); //Proccess the payment
            
            /* [/Payment configuration] */
        }
        else
        {
            $this->session->set_flashdata('error_package', lang_check('Something goes wrong... contact admin please.'));
            redirect('admin/packages/mypackage');
        }
    }
    
    public function do_purchase_package($id = NULL, $price_pay = NULL)
    {
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        $this->data['lang_code'] = $this->language_m->get_default();
        
	    // Fetch a page or set a new one
	    if(!empty($id) && !empty($price_pay))
        {
    	    // Fetch all estates
            $this->data['package'] = $this->packages_m->get_array($id);
            $this->data['languages'] = $this->language_m->get_form_dropdown('language');

            /* [Payment configuration] */
            
    		$config['business'] 			= $this->data['settings']['paypal_email'];
    		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
    		$config['return'] 				= site_url('admin/packages/mypackage');
    		$config['cancel_return'] 		= site_url('admin/packages/cancel_payment');
    		$config['notify_url'] 			= site_url('frontend/notify_payment/'.$this->data['lang_code']); //IPN Post
    		$config['production'] 			= (ENVIRONMENT == 'production'); //Its false by default and will use sandbox
    		//$config['discount_rate_cart'] 	= 0; //This means 20% discount
    		$config["invoice"]				= $this->data['package']['id'].'_PAC_'.$this->data['user']['id'].'_'.$price_pay.'_'.date('w');//.rand(1,10000); //The invoice id
            $config["currency_code"]        = $this->data['package']['currency_code'];
            
            if(empty($config['business']))
            {
                echo lang_check('PayPal email address missing');
                exit();
            }
            
            if(config_item('auto_paypal_payment') === TRUE)
            {
                $url = $config['notify_url'];
                
                $params = array();
                $params['invoice'] = $config["invoice"];
                $params['payer_id'] = substr(md5(time().rand(1,10000)), 0, 5);
                $params['txn_id'] =  md5(time().rand(1,10000));
                $params['mc_gross'] = $price_pay;
                $params['mc_currency'] = $config["currency_code"];
                $params['payer_email'] = $this->data['user']['mail'];
                
                $output = postCURL($url, $params);
                
                if(empty($output))
                    redirect('admin/packages/mypackage');
                    
                exit($output);
            }

    		$this->load->library('paypal', $config);
    		
    		#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
    		
    		$this->paypal->add('Package '.$this->data['package']['package_name'].'', $price_pay, 1); //First item
    		//$this->paypal->add('Pants',1.99, 1); 	  //Second item
    		//$this->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
    		
    		$this->paypal->pay(); //Proccess the payment
            
            /* [/Payment configuration] */
        }
        else
        {
            $this->session->set_flashdata('error_package', lang_check('Something goes wrong... contact admin please.'));
            redirect('admin/packages/mypackage');
        }
    }
    
    public function cancel_payment()
    {
        $this->session->set_flashdata('error', 
                lang_check('Payment canceled'));
        redirect('admin/packages/mypackage');    
    }
    
    public function edit($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['package'] = $this->packages_m->get($id);
            sw_count($this->data['package']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['package'] = $this->packages_m->get_new();
        }
        
		// Currencies for dropdown
        $this->load->model('payments_m');
        $this->data['currencies'] = $this->payments_m->currencies;
        
        // Set up the form
        $rules = $this->packages_m->rules_admin;
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/packages/edit/'.$id);
                exit();
            }
            
            $data = $this->packages_m->array_from_post(array('package_name', 'num_listing_limit', 'num_images_limit', 'num_amenities_limit', 'package_price', 
                                                             'package_days', 'currency_code', 'show_private_listings', 'user_type', 'auto_activation', 'num_featured_limit'));
            
            $data['date_modified'] = date('Y-m-d H:i:s');
            
            if(empty($id))
                $data['date_created'] = date('Y-m-d H:i:s');
            
            $id = $this->packages_m->save($data, $id);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('admin/packages');
            }
            else
            {
                //$this->output->enable_profiler(TRUE);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/packages/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function payments($user_id = 0, $pagination_offset=0)
    {
	    $this->load->library('pagination');
        $this->load->model('payments_m');
        
        $reservation_selected = array('invoice_num LIKE'=>'%_PAC_%');
        if($user_id != 0)
        {
            $reservation_selected = array('invoice_num LIKE'=>$user_id.'_PAC_%');
        }
        
        // Fetch all pages
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address');
        $this->data['payments'] = $this->payments_m->get_by($reservation_selected);
        
        $config['base_url'] = site_url('admin/packages/payments/'.$user_id.'/');
        $config['uri_segment'] = 5;
        $config['total_rows'] = sw_count($this->data['payments']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';

        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['payments'] = $this->payments_m->get_by($reservation_selected, FALSE, $config['per_page'], NULL, $pagination_offset);
        
        // Load view
		$this->data['subview'] = 'admin/packages/payments';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function view_payment($id = NULL)
	{
        $this->load->model('payments_m');
        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['payment'] = $this->payments_m->get_by(array('id'=>$id), TRUE);
            sw_count($this->data['payment']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            redirect('admin/packages/payments/');
        }
                
        // Load the view
		$this->data['subview'] = 'admin/packages/view_payment';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function users()
    {
        // Fetch all users
        $this->data['users'] = $this->user_m->get_by(array('package_id >'=>0));
        $this->data['packages'] = $this->packages_m->get_form_dropdown('package_name');
        
        $this->data['packages_days'] = $this->packages_m->get_form_dropdown('package_days');
        $this->data['packages_listings'] = $this->packages_m->get_form_dropdown('num_listing_limit');
        $this->data['packages_price'] = $this->packages_m->get_form_dropdown('package_price');
        $this->data['curr_listings'] = $this->packages_m->get_curr_listings();

        // Load view
		$this->data['subview'] = 'admin/packages/users';
        $this->load->view('admin/_layout_main', $this->data);
    }

    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/packages');
            exit();
        }
       
		$this->packages_m->delete($id);
        redirect('admin/packages');
	}
    
    public function _check_availability($str)
    {   
        $id = $this->uri->segment(4);
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $property_id = $this->input->post('property_id');
        $currency_code = $this->input->post('currency_code');
  
        // check 'from' before 'to', 'from' after 'now'
        if(strtotime($date_from) < time() || strtotime($date_to) < strtotime($date_from))
        {
            $this->form_validation->set_message('_check_availability', lang_check('Please correct dates'));
            return FALSE;
        }

        $is_booked = $this->reservations_m->is_booked($property_id, $date_from, $date_to, $id);
        
        if(sw_count($is_booked) > 0)
        {
            $this->form_validation->set_message('_check_availability', lang_check('Dates already booked'));
            return FALSE;
        }
        
        $changeover_day = $this->reservations_m->changeover_day($property_id, $date_from);
        if($changeover_day  === FALSE)
        {
            $this->form_validation->set_message('_check_availability', lang_check('Changeover day condition is not met'));
            return FALSE;
        }
        
        $min_stay = $this->reservations_m->min_stay($property_id, $date_from, $date_to);
        
        if($min_stay  === FALSE)
        {
            $this->form_validation->set_message('_check_availability', lang_check('Min. stay condition is not met'));
            return FALSE;
        }
        
        $booking_price = $this->reservations_m->calculate_price($property_id, $date_from, $date_to, $currency_code);

        if($booking_price  === FALSE)
        {
            $this->form_validation->set_message('_check_availability', lang_check('No rates defined for selected dates and currency'));
            return FALSE;
        }

        return TRUE;
    }
    
        
    public function do_package_activate($lang_code='en', $package_id=NULL){
        $this->load->model('packages_m');
        
        $id = NULL;
        if($package_id !== NULL && config_item('def_package') == $package_id)
        {
            $id = $package_id;
        } 
        $this->data['package'] = array();
        
        if($id !== NULL)
            $this->data['package'] = $this->packages_m->get_array($id);
        if(!empty($this->data['package']))
        {
            $data_u = array();
            $data_u['package_id'] = $this->data['package']['id'];
            $days_extend = $this->data['package']['package_days'];
            
            if($days_extend > 0)
                $data_u['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
           
           $this->user_m->save($data_u, $this->session->userdata('id'));
        } 
        
        redirect('admin/packages/mypackage/');
    }
    
    
}