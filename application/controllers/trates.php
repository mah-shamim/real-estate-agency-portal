<?php

class Trates extends Frontuser_Controller
{

	public function __construct ()
	{
		parent::__construct();
	}

    public function index()
    {
	    $this->load->model('trates_m');
       
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
        $this->data['listings'] = $this->trates_m->get_by_check($property_selected);
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/tmyrates.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function rate_edit()
	{
	   $this->load->model('trates_m');
	   $listing_id = $this->uri->segment(4);
       $id = $listing_id;
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        if(empty($id))$id=null;
        
	    if($id)
        {
            $this->data['rate'] = $this->data['rate'] = $this->trates_m->get($id);
            sw_count($this->data['rate']) || $this->data['errors'][] = 'Could not be found';
            
            if(!isset($this->data['rate']->property_id))
                redirect('trates/index');
            
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
            $this->data['rate'] = $this->trates_m->get_new();
        }
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Edit rate');
        $this->data['page_title'] = lang_check('Edit rate');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');
        $lang_id = $this->language_m->get_default_id();
        $property_id = $this->data['rate']->property_id;

        //Check if user have permision
        $num_found = $this->estate_m->check_user_permission($this->data['rate']->property_id, $this->session->userdata('id'));
        if($num_found=0)
        {
            redirect('trates/index/'.$this->data['lang_code'].'#content');
            exit();  
        }
        
		// Pages for dropdown
        $where_users = array();
        $empty_users = FALSE;
        $where_users = array('id'=>$this->session->userdata('id'));
        
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address', FALSE, TRUE, TRUE);
        $this->data['users'] = $this->user_m->get_form_dropdown('username', $where_users, $empty_users);
        $this->data['t_rows'] = $this->trates_m->get_property_rows($property_id, $lang_id);

        // [Complicated way, ID, Property name, address]
        if(config_item('address_to_title') === TRUE)
        {
            $this->load->model('option_m');
            $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(10), array_flip($this->data['properties']));
            
            foreach($this->data['properties'] as $key=>$val)
            {
                if(!empty($key))
                {
                    if(isset($this->data['options'][$key][10]))
                    {
                        $this->data['properties'][$key] = $val.', '.$this->data['options'][$key][10];
                    }
                    else
                    {
                        $this->data['properties'][$key] = $val;
                    }
                }
            }     
        }
        // [/Complicated way, ID, Property name, address]

        $prefs = array();
        $prefs['template'] = '
           {table_open}<table border="0" class="av_calender" cellpadding="0" cellspacing="0">{/table_open}
           {heading_row_start}<tr>{/heading_row_start}
           {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
           {heading_title_cell}<th colspan="{colspan}"><span>{heading}</span></th>{/heading_title_cell}
           {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
        
           {heading_row_end}</tr>{/heading_row_end}
        
           {week_row_start}<tr>{/week_row_start}
           {week_day_cell}<td><span>{week_day}</span></td>{/week_day_cell}
           {week_row_end}</tr>{/week_row_end}
        
           {cal_row_start}<tr>{/cal_row_start}
           {cal_cell_start}<td>{/cal_cell_start}
        
           {cal_cell_content}<a {content} href="#form">{day}</a>{/cal_cell_content}
           {cal_cell_content_today}<a {content} style="background: red; color:white;" href="#form">{day}</a>{/cal_cell_content_today}
        
           {cal_cell_no_content}<span class="disabled">{day}</span>{/cal_cell_no_content}
           {cal_cell_no_content_today}<div class="highlight disabled">{day}</div>{/cal_cell_no_content_today}
        
           {cal_cell_blank}<span>&nbsp;</span>{/cal_cell_blank}
        
           {cal_cell_end}</td>{/cal_cell_end}
           {cal_row_end}</tr>{/cal_row_end}
        
           {table_close}</table>{/table_close}
        ';
        
        $this->load->library('calendar', $prefs);
        $this->data['months_availability'] = array();
        $cal_data = array();

        for($i=0;$i < 12; $i++)
        {
            $next_month_time = strtotime("+$i month", strtotime(date("F") . "1"));
            
            $start_time = $next_month_time;
            $end_time = strtotime("+1 month", $start_time);
            for($j=$start_time; $j<$end_time; $j+=86400)
            {
               $cal_data[date("m", $j)][date("j", $j)] = 'class="available_x selectable" ref="'.date("Y-m-d", $j).'" ref_to="'.date("Y-m-d", strtotime(date("Y-m-d", $j).' +7 day')).'"';
            }
            
            if(!isset($cal_data[date("m", $next_month_time)]))
                $cal_data[date("m", $next_month_time)] = array();

            $this->data['months_availability'][date("Y-m", $next_month_time)] = $this->calendar->generate(date("Y", $next_month_time), date("m", $next_month_time), $cal_data[date("m", $next_month_time)]);
        }
        
        // Set up the form
        $rules = $this->trates_m->rules;
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('trates/index/'.$this->data['lang_code'].'#content');
                exit();
            }
            
            $data = $this->trates_m->array_from_post(array('property_id', 'dates', 'rates', 'table_row_index'));
            
            $table_row_index = NULL;
            if(!empty($data['table_row_index']))
            {
                $table_row_index = $data['table_row_index'];
            }
            
            $id_inserted = $this->trates_m->save($data, $id);


            if(!empty($id_inserted))
            {
                $this->session->set_flashdata('message', 
                        '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');
                redirect('trates/rate_edit/'.$this->data['lang_code'].'/'.$id_inserted.'/');
            }
            else
            {
                $this->session->set_flashdata('error', lang_check('Nothing inserted, dates already exists'));
                redirect('trates/rate_edit/'.$this->data['lang_code'].'/');
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/teditrate.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function rate_delete()
	{
	   $this->load->model('trates_m');
	   $listing_id = $this->uri->segment(4);
       
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('trates/index/'.$this->data['lang_code'].'#content');
            exit();
        }
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN')
        {
            $rate = $this->trates_m->get($listing_id);
            
            if(!isset($rate->property_id))
                redirect('trates/index/'.$this->data['lang_code'].'#content');
            
            $num_found = $this->estate_m->check_user_permission($rate->property_id, $this->session->userdata('id'));
            
            if($num_found == 0)
                redirect('trates/index/'.$this->data['lang_code'].'#content');
        }
       
		$this->trates_m->delete($listing_id);
        redirect('trates/index/'.$this->data['lang_code'].'#content');
    }

    public function _check_exists($str)
    {   
        $id = $this->uri->segment(4);
        $table_row_index = $this->input->post('table_row_index');
        $property_id = $this->input->post('property_id');
  
        $is_defined = $this->trates_m->is_defined($property_id, $table_row_index, $id);
        
        if(sw_count($is_defined) > 0)
        {
            $this->form_validation->set_message('_check_exists', lang_check('Dates already defined'));
            return FALSE;
        }

        return TRUE;
    }
}