<?php

class Tcalendar extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('showroom_m');
        $this->load->model('trates_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index($property_id = 0, $pagination_offset=0)
	{
        exit('Not implemented');
	}
    
    public function available($property_id = 0, $pagination_offset=0)
	{
	    $this->load->library('pagination');
        
        $property_selected = array();
        if($property_id != 0)
        {
            $property_selected = array('trates.property_id'=>$property_id);
        }
        
        // Fetch all pages
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address');
        $this->data['rates'] = $this->trates_m->get_by_check($property_selected);
        $this->data['lang_id'] = $this->language_m->get_default_id();
        
        $config['base_url'] = site_url('admin/tcalendar/available/'.$property_id.'/');
        $config['uri_segment'] = 5;
        $config['total_rows'] = sw_count($this->data['rates']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['rates'] = $this->trates_m->get_by_check($property_selected, FALSE, $config['per_page'], NULL, $pagination_offset);
        
        // Load view
		$this->data['subview'] = 'admin/tcalendar/available';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit_rate($id = NULL, $row_index = NULL)
	{
	    if($id)
        {
            $this->data['rate'] = $this->data['rate'] = $this->trates_m->get($id);
            sw_count($this->data['rate']) || $this->data['errors'][] = 'Could not be found';
            
            if(!isset($this->data['rate']->property_id))
                redirect('admin/tcalendar/available');
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN')
            {
                $num_found = $this->estate_m->check_user_permission($this->data['rate']->property_id, $this->session->userdata('id'));
                
                if($num_found == 0)
                    redirect('admin/tcalendar/available');
            }
        }
        else
        {
            $this->data['rate'] = $this->trates_m->get_new();
        }
        
        $lang_id = $this->language_m->get_default_id();
        $property_id = $this->data['rate']->property_id;

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
               $cal_data[date("m", $j)][date("j", $j)] = 'class="available selectable" ref="'.date("Y-m-d", $j).'" ref_to="'.date("Y-m-d", strtotime(date("Y-m-d", $j).' +7 day')).'"';
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
                redirect('admin/tcalendar/available');
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
                        '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
                redirect('admin/tcalendar/edit_rate/'.$id_inserted.'/');
            }
            else
            {
                $this->session->set_flashdata('error', lang_check('Nothing inserted, dates already exists'));
                redirect('admin/tcalendar/edit_rate');
            }
        }

        // Load the view
		$this->data['subview'] = 'admin/tcalendar/edit_rate';
        $this->load->view('admin/_layout_main', $this->data);
	}


    public function delete_rate($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/tcalendar/available');
            exit();
        }
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN')
        {
            $rate = $this->trates_m->get($id);
            
            if(!isset($rate->property_id))
                redirect('admin/tcalendar/available');
            
            $num_found = $this->estate_m->check_user_permission($rate->property_id, $this->session->userdata('id'));
            
            if($num_found == 0)
                redirect('admin/tcalendar/available');
        }
       
		$this->trates_m->delete($id);
        redirect('admin/tcalendar/available');
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