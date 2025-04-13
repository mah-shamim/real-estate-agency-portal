<?php

class Savesearch extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('savedsearch_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index($user_id = 0, $pagination_offset=0)
	{
	    $this->load->library('pagination');
        
        $listing_selected = array();
        if($user_id != 0)
        {
            $listing_selected['user_id'] = $user_id;
        }
        
        // Fetch all listings
        $this->data['users'] = $this->user_m->get_form_dropdown('username');
        $this->data['listings'] = $this->savedsearch_m->get_by($listing_selected);

        $config['base_url'] = site_url('admin/savedsearch/index/'.$user_id.'/');
        $config['uri_segment'] = 4;
        $config['total_rows'] = sw_count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['listings'] = $this->savedsearch_m->get_by($listing_selected, FALSE, $config['per_page'], NULL, $pagination_offset);

        // Load view
		$this->data['subview'] = 'admin/savesearch/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['listing'] = $this->savedsearch_m->get($id);
            sw_count($this->data['listing']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['listing'] = $this->savedsearch_m->get_new();
        }
        
		// Pages for dropdown
        $this->data['users'] = $this->user_m->get_form_dropdown('username');       

        // Set up the form
        $rules = $this->savedsearch_m->rules_admin;
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/savesearch/edit/'.$id);
                exit();
            }
            
            $data = $this->savedsearch_m->array_from_post(array('activated', 'user_id', 'delivery_frequency_h'));
            
            $id = $this->savedsearch_m->save($data, $id, TRUE);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('admin/savesearch/index/');
            }
            else
            {
                //$this->output->enable_profiler(TRUE);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/savesearch/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}

    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/savesearch');
            exit();
        }
       
		$this->savedsearch_m->delete($id);
        redirect('admin/savesearch');
	}
    
//    public function _check_availability($str)
//    {   
//        $id = $this->uri->segment(4);
//        $date_from = $this->input->post('date_from');
//        $date_to = $this->input->post('date_to');
//        $property_id = $this->input->post('property_id');
//        $currency_code = $this->input->post('currency_code');
//  
//        if($booking_price  === FALSE)
//        {
//            $this->form_validation->set_message('_check_availability', lang_check('No rates defined for selected dates and currency'));
//            return FALSE;
//        }
//
//        return TRUE;
//    }
    
}