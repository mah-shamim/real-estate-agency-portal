<?php

class Rates extends Frontuser_Controller
{

	public function __construct ()
	{
		parent::__construct();
	}

    public function index()
    {
	    $this->load->model('rates_m');
       
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('My rates and availability');
        $this->data['page_title'] = lang_check('My rates and availability');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');
        
        $property_selected = array();
//        if($property_id != 0)
//        {
//            $property_selected = array('rates.property_id'=>$property_id);
//        }

        // Fetch all listings
        $this->data['listings'] = $this->rates_m->get_by_check($property_selected);
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address', FALSE, TRUE, FALSE, FALSE);
        
        // Get templates
        $templatesDirectory = opendir(FCPATH.'templates/'.$this->data['settings_template'].'/components');
        // get each template
        $template_prefix = 'page_';
        while($tempFile = readdir($templatesDirectory)) {
            if ($tempFile != "." && $tempFile != ".." && strpos($tempFile, '.php') !== FALSE) {
                if(substr_count($tempFile, $template_prefix) == 0)
                {
                    $template_output = $this->parser->parse($this->data['settings_template'].'/components/'.$tempFile, $this->data, TRUE);
                    //$template_output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $template_output);
                    $this->data['template_'.substr($tempFile, 0, -4)] = $template_output;
                }
            }
        }
        

        $output = $this->parser->parse($this->data['settings_template'].'/myrates.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function make_withdrawal($lang_code_u = 'en')
    {
        $this->load->model('withdrawal_m');
        
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Make a withdrawal');
        $this->data['page_title'] = lang_check('Make a withdrawal');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');
        
        $this->data['withdrawal_amounts'] = $this->withdrawal_m->get_amounts($user_id);

        // Set up the form
        $rules = $this->withdrawal_m->rules;       
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('rates/index/'.$this->data['lang_code'].'#content');
                exit();
            }
            
            $data = $this->withdrawal_m->array_from_post(
                        $this->withdrawal_m->get_post_from_rules($rules));
            
            $data['user_id'] = $user_id;
            $data['date_requested'] = date('Y-m-d H:i:s');
            
            $id = $this->withdrawal_m->save($data);
            
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('You made withdraw request').'</p>');
            
            if(!empty($id))
            {
                // [START] Email alert to system admin

                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                if(!empty($this->data['settings_email']))
                {
                    $data['username'] = $this->session->userdata('username');
                    
                    $to_mail = $this->data['settings_email'];
                    $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
                    $this->email->to($to_mail);
                    $this->email->subject(lang_check('Message from real-estate web'));
                    $message = $this->load->view('email/withdrawal_request', array('data'=>$data), TRUE);
                    $this->email->message($message);
                    
                    if(ENVIRONMENT != 'development')
                    if ( ! $this->email->send())
                    {
                        log_message('error', 'Email alert to system admin');
                        $msg = $this->email->print_debugger();
                        log_message('error', $msg);
                    }
                }

                // [END] Email alert to system admin
                
                redirect('rates/payments/'.$this->data['lang_code'].'#content');
            }
            else
            {
                $this->output->enable_profiler(TRUE);
            }
        }
        
        // Get templates
        $templatesDirectory = opendir(FCPATH.'templates/'.$this->data['settings_template'].'/components');
        // get each template
        $template_prefix = 'page_';
        while($tempFile = readdir($templatesDirectory)) {
            if ($tempFile != "." && $tempFile != ".." && strpos($tempFile, '.php') !== FALSE) {
                if(substr_count($tempFile, $template_prefix) == 0)
                {
                    $template_output = $this->parser->parse($this->data['settings_template'].'/components/'.$tempFile, $this->data, TRUE);
                    //$template_output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $template_output);
                    $this->data['template_'.substr($tempFile, 0, -4)] = $template_output;
                }
            }
        }

        $output = $this->parser->parse($this->data['settings_template'].'/make_withdrawal.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function payments($lang_code_u = 'en', $pagination_offset=0)
    {
	    $this->load->model('reservations_m');
        $this->load->model('withdrawal_m');
       
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('My reservations and payments');
        $this->data['page_title'] = lang_check('My reservations and payments');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');
        
        $property_selected = array();
//        if($property_id != 0)
//        {
//            $property_selected = array('rates.property_id'=>$property_id);
//        }
        
        // Fetch all listings
        $this->data['listings'] = $this->reservations_m->get_by_check($property_selected, false, NULL, NULL, '', true);
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address', FALSE, TRUE, FALSE, FALSE);

        $config['base_url'] = site_url('rates/payments/'.$this->data['lang_code'].'/');
        $config['uri_segment'] = 4;
        $config['total_rows'] = sw_count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        
        $this->pagination->initialize($config);
        $this->data['pagination_links'] = $this->pagination->create_links();
        
        $this->data['listings'] = $this->reservations_m->get_by_check($property_selected, FALSE, $config['per_page'], NULL, $pagination_offset, true);
        
        $this->data['withdrawal_amounts'] = $this->withdrawal_m->get_amounts($user_id);
        $this->data['pending_amounts'] = $this->withdrawal_m->get_pending($user_id);
        $this->data['listings_withdrawal'] = $this->withdrawal_m->get_by(array('user_id'=>$user_id), FALSE, 5);
        
        // Get templates
        $templatesDirectory = opendir(FCPATH.'templates/'.$this->data['settings_template'].'/components');
        // get each template
        $template_prefix = 'page_';
        while($tempFile = readdir($templatesDirectory)) {
            if ($tempFile != "." && $tempFile != ".." && strpos($tempFile, '.php') !== FALSE) {
                if(substr_count($tempFile, $template_prefix) == 0)
                {
                    $template_output = $this->parser->parse($this->data['settings_template'].'/components/'.$tempFile, $this->data, TRUE);
                    //$template_output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $template_output);
                    $this->data['template_'.substr($tempFile, 0, -4)] = $template_output;
                }
            }
        }
        

        $output = $this->parser->parse($this->data['settings_template'].'/mypayments.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function rate_edit()
	{
	   $this->load->model('rates_m');
	   $listing_id = $this->uri->segment(4);
       $id = $listing_id;
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        if(empty($id))$id=null;
        
	    if($id)
        {
            $this->data['rate'] = $this->rates_m->get_lang($id, FALSE, $this->data['content_language_id']);
            sw_count($this->data['rate']) || $this->data['errors'][] = 'Could not be found';
            
            if(!isset($this->data['rate']->property_id))
                redirect('rates/index');
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN')
            {
                $num_found = $this->estate_m->check_user_permission($this->data['rate']->property_id, $this->session->userdata('id'));
                
                if($num_found == 0)
                    redirect('admin/booking/rates');
            }
        }
        else
        {
            $this->data['rate'] = $this->rates_m->get_new();
        }
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Edit rate');
        $this->data['page_title'] = lang_check('Edit rate');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');

        //Check if user have permision
        $num_found = $this->estate_m->check_user_permission($this->data['rate']->property_id, $this->session->userdata('id'));
        if($num_found=0)
        {
            redirect('rates/index/'.$this->data['lang_code'].'#content');
            exit();  
        }
        
		// Pages for dropdown
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        
        //Simple way to featch only address:        
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address');

        $this->load->model('payments_m');
        $this->data['currencies'] = $this->payments_m->currencies;
        
        $this->lang->load('calendar');
        $this->data['changeover_days'] = array(lang_check('Flexible'), 
                                               lang_check('cal_monday'),
                                               lang_check('cal_tuesday'),
                                               lang_check('cal_wednesday'),
                                               lang_check('cal_thursday'),
                                               lang_check('cal_friday'),
                                               lang_check('cal_saturday'),
                                               lang_check('cal_sunday'));
        
        // Set up the form
        $rules = $this->rates_m->get_all_rules();
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('rates/index/'.$this->data['lang_code'].'#content');
                exit();
            }
            
            $data = $this->rates_m->array_from_post(array('date_from', 'date_to', 'min_stay', 'changeover_day', 'property_id'));
            $data_lang = $this->rates_m->array_from_post($this->rates_m->get_lang_post_fields());
            
            $id = $this->rates_m->save_with_lang($data, $data_lang, $id);
            
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('rates/index/'.$this->data['lang_code'].'#content');
            }
            else
            {
                $this->output->enable_profiler(TRUE);
            }
        }

        // Get templates
        $templatesDirectory = opendir(FCPATH.'templates/'.$this->data['settings_template'].'/components');
        // get each template
        $template_prefix = 'page_';
        while($tempFile = readdir($templatesDirectory)) {
            if ($tempFile != "." && $tempFile != ".." && strpos($tempFile, '.php') !== FALSE) {
                if(substr_count($tempFile, $template_prefix) == 0)
                {
                    $template_output = $this->parser->parse($this->data['settings_template'].'/components/'.$tempFile, $this->data, TRUE);
                    //$template_output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $template_output);
                    $this->data['template_'.substr($tempFile, 0, -4)] = $template_output;
                }
            }
        }
        

        $output = $this->parser->parse($this->data['settings_template'].'/editrate.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function rate_delete()
	{
	   $this->load->model('rates_m');
	   $listing_id = $this->uri->segment(4);
       
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('rates/index/'.$this->data['lang_code'].'#content');
            exit();
        }
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN')
        {
            $rate = $this->rates_m->get($listing_id);
            
            if(!isset($rate->property_id))
                redirect('rates/index/'.$this->data['lang_code'].'#content');
            
            $num_found = $this->estate_m->check_user_permission($rate->property_id, $this->session->userdata('id'));
            
            if($num_found == 0)
                redirect('rates/index/'.$this->data['lang_code'].'#content');
        }
       
		$this->rates_m->delete($listing_id);
        redirect('rates/index/'.$this->data['lang_code'].'#content');
    }

    public function _check_exists($str)
    {   
        $id = $this->uri->segment(4);
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $property_id = $this->input->post('property_id');
  
        // check 'from' before 'to', 'from' after 'now'
        if(strtotime($date_from) < time() || strtotime($date_to) < strtotime($date_from))
        {
            $this->form_validation->set_message('_check_exists', lang_check('Please correct dates'));
            return FALSE;
        }

        $is_defined = $this->rates_m->is_defined($property_id, $date_from, $date_to, $id);
        
        if(sw_count($is_defined) > 0)
        {
            $this->form_validation->set_message('_check_exists', lang_check('Dates already defined'));
            return FALSE;
        }

        return TRUE;
    }
    
    public function _amount_check($str)
    {   
        $amount = $this->input->post('amount');
        $currency = $this->input->post('currency');

        if( isset($this->data['withdrawal_amounts'][$currency]) && 
            $this->data['withdrawal_amounts'][$currency] >= $amount)
        {
            return TRUE;
        }
        
        $this->form_validation->set_message('_amount_check', lang_check('Amount not available'));
        return FALSE;
    }
}