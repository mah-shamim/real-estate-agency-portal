<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Claim extends Admin_Controller
{
    
	public function __construct(){
		parent::__construct();
        
        $this->load->model('estate_m');
        $this->load->model('claim_m');
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    
    /*
     * page Index
     * 
     */
    public function index () {
        
        prepare_search_query_GET(array('message'), array('id', 'name', 'email','phone'));
        
        /* data */
        $this->data['all_claims']=$this->claim_m->get();
        /* end data */
        
        // Load view
        $this->data['subview'] = 'admin/claim/index';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    /*
     * page Edit claime
     * 
     */
    public function edit ($id=null) {
        if(!empty($id)) {
        /* data */
        $this->data['claim'] = $this->claim_m->get(trim($id));
        /* end data */
        } else {
            /* error */
            /* data */
            $this->data['claim'] = $this->claim_m->get_new();
            $id=null;
            /* end data */
        }
        
        // Set up the form
        // rules
        $rules = $this->claim_m->rules;
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
            
            $data = $this->claim_m->array_from_post(array('model', 'model_id', 'surname', 'agent_id', 'name', 
                                                         'phone', 'email', 'message', 'allow_contact', 'date_submit'));
            
            $insert_id='';
            $insert_id=$this->claim_m->save($data, $id);
              
            if(!empty($insert_id)) {
                $this->session->set_flashdata('message', 
                        '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');

                redirect('admin/claim/edit/'.$insert_id);
            }
            
        }
        
              // Load view
		$this->data['subview'] = 'admin/claim/edit';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function delete($id=null)
	{
        if(empty($id)) {
            $this->session->set_flashdata('error', 
                    lang_check('Id is empty'));
            redirect('admin/claim');
            exit();
        }
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/claim');
            exit();
        }
       
        $this->data['enquire'] = $this->claim_m->get($id);
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN')
        {
                redirect('admin/claim');
        }
       
		$this->claim_m->delete($id);
        redirect('admin/claim');
	}
    
    
}