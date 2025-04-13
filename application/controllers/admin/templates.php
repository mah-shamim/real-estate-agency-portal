<?php

class Templates extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('customtemplates_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index($pagination_offset=0)
	{
	    $this->load->library('pagination');
        
        $listing_selected = array();
        $listing_selected['theme'] = $this->data['settings']['template'];
        
        $listing_selected['type'] = 'RIGHT';
        // Fetch all listings
        $this->data['listings'] = $this->customtemplates_m->get_by($listing_selected, FALSE, NULL, NULL, NULL);
        
        $listing_selected['type'] = 'LISTING';
        $this->data['listings_property'] = $this->customtemplates_m->get_by($listing_selected, FALSE, NULL, NULL, NULL);
        
        $listing_selected['type'] = 'RESULT_ITEM';
        $this->data['listings_item'] = $this->customtemplates_m->get_by($listing_selected, FALSE, NULL, NULL, NULL);
        
        // Load view
		$this->data['subview'] = 'admin/templates/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['listing'] = $this->customtemplates_m->get($id);
            sw_count($this->data['listing']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['listing'] = $this->customtemplates_m->get_new();
        }
        
        /* widget hint get lang from template for hint */
        $lang_name = $this->language_m->get_name($this->data['content_language_id']);
        $this->lang->load('frontend_template', $lang_name, FALSE, TRUE, FCPATH.'templates/'.$this->data['settings']["template"].'/');
        /* end widget hint get lang from template for hint */
    
        // Set up the form
        $rules = $this->customtemplates_m->rules_admin;
        $this->form_validation->set_rules($rules);
        
        $this->load->model('page_m');
        $this->widgets = $this->page_m->get_subtemplates('widgets');

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/templates/edit/'.$id);
                exit();
            }
            
            $data = $this->customtemplates_m->array_from_rules($rules);
            
            $id = $this->customtemplates_m->save($data, $id, TRUE);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('admin/templates/edit/'.$id);
            }
            else
            {
                //$this->output->enable_profiler(TRUE);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/templates/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit_listing($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['listing'] = $this->customtemplates_m->get($id);
            sw_count($this->data['listing']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['listing'] = $this->customtemplates_m->get_new();
        }
        
        // Set up the form
        $rules = $this->customtemplates_m->rules_admin;
        $this->form_validation->set_rules($rules);
        
        $this->load->model('page_m');
        $this->widgets = $this->page_m->get_listingtemplates('widgets');

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/templates/edit_listing/'.$id);
                exit();
            }
            
            $data = $this->customtemplates_m->array_from_rules($rules);
            
            $id = $this->customtemplates_m->save($data, $id, TRUE);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('admin/templates/edit_listing/'.$id);
            }
            else
            {
               // $this->output->enable_profiler(TRUE);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/templates/edit_listing';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit_item($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['listing'] = $this->customtemplates_m->get($id);
            sw_count($this->data['listing']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['listing'] = $this->customtemplates_m->get_new();
        }
        
        // Set up the form
        $rules = $this->customtemplates_m->rules_admin;
        $this->form_validation->set_rules($rules);
        
        $this->load->model('option_m');
        $this->fields = $this->option_m->get_field_list($this->data['content_language_id']);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/templates/edit_item/'.$id);
                exit();
            }
            
            $data = $this->customtemplates_m->array_from_rules($rules);
            
            $id = $this->customtemplates_m->save($data, $id, TRUE);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('admin/templates/edit_item/'.$id);
            }
            else
            {
                //$this->output->enable_profiler(TRUE);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/templates/edit_item';
        $this->load->view('admin/_layout_main', $this->data);
	}

    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/templates');
            exit();
        }
       
		$this->customtemplates_m->delete($id);
        redirect('admin/templates');
	}
    
    public function delete_listing($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/templates');
            exit();
        }
       
		$this->customtemplates_m->delete($id);
        redirect('admin/templates');
	}
    
    public function delete_item($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/templates');
            exit();
        }
       
		$this->customtemplates_m->delete($id);
        redirect('admin/templates');
	}
    
}