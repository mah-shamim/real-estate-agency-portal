<?php

class Dashboard extends Admin_Controller {
	
    public function __construct(){
		parent::__construct();
        $this->load->model('page_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
        
        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    public function index() 
    {
        $this->data['pages_nested'] = $this->page_m->get_nested($this->data['content_language_id']);
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        //$this->data['estates'] = $this->estate_m->get_last();
        //$this->data['estates_all'] = $this->estate_m->get_join();
        //$this->data['options'] = $this->option_m->get_options($this->data['content_language_id']);
        
        // Fetch settings
        $this->load->model('settings_m');
        $this->data['settings'] = $this->settings_m->get_fields();
        $this->data['settings_template']=$this->data['settings']['template'];
        
        $where = array();
        $search_array = array();
        $where['language_id']  = $this->data['content_language_id'];
        
        // [AGENT_COUNTY_AFFILIATE]
        if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
        {
            $this->load->model('affilatepackages_m');
            $user_id = $this->session->userdata('id');

            $related_tree_paths = $this->affilatepackages_m->get_user_packages($user_id, $this->data['content_language_id']);
            
            $gen_where = array();
            if(sw_count($related_tree_paths) > 0)
            {
                foreach($related_tree_paths as $row_t)
                {
                    $gen_where[] = 'json_object LIKE \'%'.$row_t->value_path.'%\'';
                }
                
//                $where = 'language_id = '.$this->data['content_language_id'].' AND status != "HOLD_ADMIN" AND ('.
//                         implode(' OR ', $gen_where).
//                         ')';
                
                $where['(status IS NULL OR status != "CONTRACT")'] = NULL;
                $where['(status IS NULL OR status != "HOLD_ADMIN")'] = NULL;
                $where['('.implode(' OR ', $gen_where).')'] = NULL;
            }
            else
            {
                $where['user_id'] = $this->session->userdata('id');
                $where['(status IS NULL OR status != "CONTRACT")'] = NULL;
                $where['(status IS NULL OR status != "HOLD_ADMIN")'] = NULL;
            }
        }
        // [/AGENT_COUNTY_AFFILIATE]
        
        $this->data['estates'] = $this->estate_m->get_by($where, false, 5, 'property.id DESC', NULL, array(), NULL, TRUE);
        
        $this->data['estates_all'] = $this->estate_m->get_by($where, false, 100, 'property.id DESC', NULL, array(), NULL, TRUE);
        
    	$this->data['subview'] = 'admin/dashboard/index';
    	$this->load->view('admin/_layout_main', $this->data);
    }
    
    public function search() 
    {
        //$this->data['estates'] = $this->estate_m->get_search($this->input->post('search'));
        //$this->data['options'] = $this->option_m->get_options($this->data['content_language_id']);
        
        $where = array();
        $search_array = array();
        $search_array['search_option_smart'] = $this->input->post('search');        
        $where['language_id']  = $this->data['content_language_id'];

        $this->data['estates'] = $this->estate_m->get_by($where, false, 100, 'property.id DESC', NULL, $search_array, NULL, TRUE);

    	$this->data['subview'] = 'admin/dashboard/search';
    	$this->load->view('admin/_layout_main', $this->data);
    }
    
    public function modal() {
    	$this->load->view('admin/_layout_modal', $this->data);
    }
    
}