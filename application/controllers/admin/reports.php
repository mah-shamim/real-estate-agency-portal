<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reports extends Admin_Controller
{
    
	public function __construct(){
		parent::__construct();
        
        $this->load->model('estate_m');
        $this->load->model('reports_m');
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    
    /*
     * page Index
     * 
     */
    public function index () {
        
        prepare_search_query_GET(array('message'), array('id', 'name', 'email','phone'));
        
        /* data */
        $this->data['all_reports']=$this->reports_m->get_joined();
        //$this->data['all_users']=$this->user_m->get_form_dropdown('name_surname');
        //$this->data['all_estates']=$this->estate_m->get_form_dropdown('address');
        /* end data */
        
        // Load view
            $this->data['subview'] = 'admin/reports/index';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    /*
     * page Edit reporte
     * 
     */
    public function edit ($id=null) {
        if(!empty($id)) {
        /* data */
        $this->data['report'] = $this->reports_m->get(trim($id));
        /* end data */
        } else {
            /* error */
            /* data */
            $this->data['report'] = $this->reports_m->get_new();
            $id=null;
            /* end data */
        }
        
        /* data */
        $this->data['all_reports']=$this->reports_m->get_joined();
        //$this->data['all_users']=$this->user_m->get_form_dropdown('name_surname',false,false);
        //$this->data['all_estates']=$this->estate_m->get_form_dropdown('address',false,false);
        /* end data */
        
        // Set up the form
        // rules
        $rules = $this->reports_m->rules;
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/enquire/edit/'.$id);
                exit();
            }
            
            $data = $this->reports_m->array_from_post(array('property_id', 'surname', 'agent_id', 'name', 
                                                         'phone', 'email', 'message', 'allow_contact', 'date_submit'));
            
            $insert_id='';
            $insert_id=$this->reports_m->save($data, $id);
              
            if(!empty($insert_id)) {
                $this->session->set_flashdata('message', 
                        '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');

                redirect('admin/reports/edit/'.$insert_id);
            }
            
        }
        
              // Load view
		$this->data['subview'] = 'admin/reports/edit';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function delete($id=null)
	{
        if(empty($id)) {
            $this->session->set_flashdata('error', 
                    lang_check('Id is empty'));
            redirect('admin/reports');
            exit();
        }
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/reports');
            exit();
        }
       
        $this->data['enquire'] = $this->reports_m->get($id);
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN')
        {
                redirect('admin/reports');
        }
       
		$this->reports_m->delete($id);
        redirect('admin/reports');
	}
    
    
}