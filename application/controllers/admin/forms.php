<?php

class Forms extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('forms_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index()
	{
	    // part of estate
	}
    
    public function edit($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['listing'] = $this->forms_m->get($id);
            sw_count($this->data['listing']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['listing'] = $this->forms_m->get_new();
        }
        
        // Set up the form
        $rules = $this->forms_m->rules_admin;
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
                redirect('admin/forms/edit/'.$id);
                exit();
            }
            
            $data = $this->forms_m->array_from_rules($rules);
            
            $id = $this->forms_m->save($data, $id, TRUE);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('admin/forms/edit/'.$id);
            }
            else
            {
                //$this->output->enable_profiler(TRUE);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/forms/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}

    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/estate/forms');
            exit();
        }
        
        $locked = config_item('locked_forms');
        if(is_array($locked))
        {
            if(in_array($id, $locked))
            {
                $this->session->set_flashdata('error', 
                        lang_check('Removing disabled by configuration'));
                redirect('admin/estate/forms');
                exit();
            }
        }
       
		$this->forms_m->delete($id);
        redirect('admin/estate/forms');
	}
    
}