<?php

class Estate extends Admin_Controller
{
    
	public function __construct()
    {
		parent::__construct();
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('file_m');
        
        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        $this->data['listing_uri'] = config_item('listing_uri');
            if(empty($this->data['listing_uri']))$this->data['listing_uri'] = 'property';
	}
    
    public function index($pagination_offset=0)
	{
	    $this->load->library('pagination');
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
        
        $type_field = 'field_2';
        if($this->data['settings']['template'] == 'local' || $this->data['settings']['template'] == 'selio')
            $type_field = 'json_object';

        prepare_search_query_GET(array($type_field, 'field_4'), array('property.id', 'address', 'search_values'));
        
        // Fetch all estates
        $this->data['total_rows']=$config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.id DESC', 
                                                              NULL, $search_array, NULL, TRUE);
        
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);

        $config['base_url'] = site_url('admin/estate/index');
        $config['uri_segment'] = 4;
        //$config['total_rows'] = sw_count($this->data['estates']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['additional_query_string'] = regenerate_query_string();
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        /* user type = ADMIN, search by AGENT/User name enabled */
        if($this->session->userdata('type') == 'ADMIN'){
            prepare_search_query_GET(array($type_field, 'field_4', 'name_surname'), array('property.id', 'property.address', 'search_values'));

            if($this->input->get_post('name_surname')) {
                $this->db->select('user.name_surname');
                $this->db->join('property_user', $this->estate_m->get_table_name().'.id = property_user.property_id', 'left');
                $this->db->join('user', 'property_user.user_id = user.id', 'left');
            }
        } else {
            prepare_search_query_GET(array($type_field, 'field_4'), array('property.id', 'address', 'search_values'));
        }
        
        $order_by = 'property.id DESC';
        if($this->input->get('order_by'))
            $order_by = $this->input->get('order_by');
        
        $this->data['estates'] = $this->estate_m->get_by($where, false, $config['per_page'], $order_by, 
                                               $pagination_offset, $search_array, NULL, TRUE);
        
        // Load view
		$this->data['subview'] = 'admin/estate/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
        
    public function word_filtering($pagination_offset=0) {
        $this->load->library('pagination');
        $where = array();
        $search_array = array();
        
        $where['language_id']  = $this->data['content_language_id'];
        
        if(empty($this->data['settings']['word_filtering'])) {
            redirect('admin/estate/');
        }
        
        $word_filtering=$this->data['settings']['word_filtering'];

       
        
        $val = explode(',', $word_filtering);
        if(is_array($val) && !empty($val)){
        $query ='';
        foreach ($val as $key => $val_parse) {
        $val_parse = trim($val_parse);
        if(empty($val_parse)) continue;

        if(empty($query)) 
                $query.='(';
        else
                $query.=' OR ';

        $query .="(json_object LIKE '%$val_parse%')";
        }
        if(!empty($query)){
                $query .=')';
                $this->db->where($query);
            }
        }
        prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        
        // Fetch all estates
        $config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.id DESC', 
                                                              NULL, $search_array, NULL, TRUE);
        
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);

        $config['base_url'] = site_url('admin/estate/word_filtering');
        $config['uri_segment'] = 4;
        //$config['total_rows'] = sw_count($this->data['estates']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['additional_query_string'] = regenerate_query_string();
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        /* user type = ADMIN, search by AGENT/User name enabled */
        if($this->session->userdata('type') == 'ADMIN'){
            prepare_search_query_GET(array('field_2', 'field_4', 'name_surname'), array('property.id', 'property.address', 'search_values'));

            if($this->input->get_post('name_surname')) {
                $this->db->select('user.name_surname');
                $this->db->join('property_user', $this->estate_m->get_table_name().'.id = property_user.property_id', 'left');
                $this->db->join('user', 'property_user.user_id = user.id', 'left');
            }
        } else {
            prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        }
        
        
        $val = explode(',', $word_filtering);
        if(is_array($val) && !empty($val)){
        $query ='';
        foreach ($val as $key => $val_parse) {
        $val_parse = trim($val_parse);
        if(empty($val_parse)) continue;

        if(empty($query)) 
                $query.='(';
        else
                $query.=' OR ';

        $query .="(json_object LIKE '%$val_parse%')";
        }
        if(!empty($query)){
                $query .=')';
                $this->db->where($query);
            }
        }
        
        $this->data['estates'] = $this->estate_m->get_by($where, false, $config['per_page'], 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        
        // Load view
        $this->data['subview'] = 'admin/estate/word_filtering';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function contracted($pagination_offset=0)
	{
	    $this->load->library('pagination');
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
                
                $where['(status = "CONTRACT")'] = NULL;
                $where['('.implode(' OR ', $gen_where).')'] = NULL;
            }
            else
            {
                $where['user_id'] = $this->session->userdata('id');
                $where['(status = "CONTRACT")'] = NULL;
            }
        }
        // [/AGENT_COUNTY_AFFILIATE]

        prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        
        // Fetch all estates
        $config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.id DESC', 
                                                              NULL, $search_array, NULL, TRUE);
        
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);

        $config['base_url'] = site_url('admin/estate/contracted');
        $config['uri_segment'] = 4;
        //$config['total_rows'] = sw_count($this->data['estates']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['additional_query_string'] = regenerate_query_string();
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        
        $this->data['estates'] = $this->estate_m->get_by($where, false, $config['per_page'], 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);

        // Load view
		$this->data['subview'] = 'admin/estate/contracted';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function statuses($pagination_offset=0)
	{
	    $this->load->library('pagination');
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
                
                $where['('.implode(' OR ', $gen_where).')'] = NULL;
            }
            else
            {
                $where['user_id'] = $this->session->userdata('id');
            }
        }
        // [/AGENT_COUNTY_AFFILIATE]

        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);
        
        
        $where['(status = "" OR status is NULL OR status = "REDUCED_PRICE" OR status = "RESUBMIT")'] = NULL;
        $this->data['estates_pending'] = $this->estate_m->get_by($where, false, NULL, 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        unset($where['(status = "" OR status is NULL OR status = "REDUCED_PRICE" OR status = "RESUBMIT")']);
        
        
        $where['(status = "HOLD" OR status="HOLD_REDUCED" OR status="HOLD_RESUBMIT")'] = NULL;
        $this->data['estates_hold'] = $this->estate_m->get_by($where, false, NULL, 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        unset($where['(status = "HOLD" OR status="HOLD_REDUCED" OR status="HOLD_RESUBMIT")']);
                                               
        $where['(status = "CONTRACT")'] = NULL;
        $this->data['estates_contracted'] = $this->estate_m->get_by($where, false, NULL, 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        
        
        
        // Load view
		$this->data['subview'] = 'admin/estate/statuses';
        $this->load->view('admin/_layout_main', $this->data);
	}
       
    public function edit($id = NULL)
	{
        // If limit reached, error/warning!
        $this->load->model('packages_m');
        $this->load->model('treefield_m');
        $this->load->model('language_m');
        $this->load->model('user_m');
        $lang_code = $this->language_m->get_default();
        $related_users = $this->user_m->get_by(array('agency_id'=>$this->session->userdata('id')));
        $this->data['related_users_id'] = array();
        $this->data['related_users_dropdown'] = array();
        if($related_users) foreach( $related_users as $user) {
            $this->data['related_users_id'][$user->id] = TRUE;
            $this->data['related_users_dropdown'][$user->id] = $user->id.', '.$user->name_surname;
        }
        
        $user = $this->user_m->get($this->session->userdata('id'));
        
        if(file_exists(APPPATH.'controllers/admin/packages.php'))
        if($user->package_id > 0 && $this->session->userdata('type') == 'AGENT')
        {
            $package = $this->packages_m->get($user->package_id);
            $listing_num = $this->packages_m->get_curr_listings(array('user_id'=>$user->id));
            
            if(config_item('enable_num_amenities_listing') == true)
                $this->data['package_num_amenities_limit'] = $package->num_amenities_limit;
            
            if(isset($listing_num[$user->id]))
            {
                if($listing_num[$user->id] >= $package->num_listing_limit && !$id)
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Num listings max. reached for your package'));
                    redirect('admin/estate');
                    exit();
                }
                else if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Date for your package expired, please extend'));
                    redirect('admin/estate');
                    exit();
                }
            }
        } else if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN') {
            $this->session->set_flashdata('error', 
                                            lang_check('Please activate one package, for add new listing'));
            redirect('admin/estate');
            exit();
        }
       
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['estate'] = $this->estate_m->get_dynamic($id);
            
            if(sw_count($this->data['estate']) == 0)
            {
                $this->data['errors'][] = 'Estate could not be found';
                redirect('admin/estate');
            }
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                if($this->data['estate']->agent == $this->session->userdata('id') || isset($this->data['related_users_id'][$this->data['estate']->agent]))
                {
                    
                }
                else if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
                {
                    // [AGENT_COUNTY_AFFILIATE]
                    
                    $user = $this->user_m->get($this->session->userdata('id'));
                    if(strpos($this->data['estate']->search_values, $user->county_affiliate_values) == FALSE)
                    {
                        redirect('admin/estate');
                    }
            
                    // [/AGENT_COUNTY_AFFILIATE]
                }
                else
                {
                    redirect('admin/estate');
                }
            }
            
            // Fetch file repository
            $repository_id = $this->data['estate']->repository_id;
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('name'=>'estate_m'));
                
                // Update page with new repository_id
                $this->estate_m->save(array('repository_id'=>$repository_id), $this->data['estate']->id);
                $this->data['estate']->repository_id = $repository_id;
            }
            
            
            if(config_item('plan_gallery_enabled') == TRUE){
                $planimages_repository_id = $this->data['estate']->planimages_repository_id;
                if(empty($planimages_repository_id))
                {
                    // Create repository
                    $planimages_repository_id = $this->repository_m->save(array('name'=>'estate_m'));

                    // Update page with new repository_id
                    $this->estate_m->save(array('planimages_repository_id'=>$planimages_repository_id), $this->data['estate']->id);
                    $this->data['estate']->planimages_repository_id = $planimages_repository_id;
                }
            }
        }
        else
        {
            $this->data['estate'] = $this->estate_m->get_new();
        }
        
		// Pages for dropdown
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        
        // Get available agents
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'AGENT'));
        
        $this->data['available_agent'][''] = lang_check('Current user');
        
        // Get all options
        foreach($this->option_m->languages as $key=>$val){
            $this->data['options_lang'][$key] = $this->option_m->get_lang(NULL, FALSE, $key);
        }
        $this->data['options'] = $this->option_m->get_lang(NULL, FALSE, $this->data['content_language_id']);
        
        // Id's for key adjustments 
        // TODO: better solution needed, this is just hotfix
        $options = $this->data['options'];
        $this->data['options'] = array();
        foreach($options as $option_key=>$option_row)
        {
            $this->data['options'][$option_row->option_id] = $option_row;
        }
        
        // For other langs
        foreach($this->option_m->languages as $key=>$val){
            $options_key = $this->data['options_lang'][$key];
            $this->data['options_lang'][$key] = array();
            foreach($options_key as $option_key=>$option_row)
            {
                $this->data['options_lang'][$key][$option_row->option_id] = $option_row;
            }
        }
        // End id's for key adjustments
        
        
        $options_data = array();
        foreach($this->option_m->get() as $key=>$val)
        {
            $options_data[$val->id][$val->type] = 'true';
        }
        
        // Add rules for dynamic options
        $rules_dynamic = array();
        foreach($this->option_m->languages as $key_lang=>$val_lang){
            foreach($this->data['options'] as $key_option=>$val_option){
                $rules_dynamic['option'.$val_option->id.'_'.$key_lang] = 
                    array('field'=>'option'.$val_option->id.'_'.$key_lang, 'label'=>$val_option->option, 'rules'=>'trim');
                //if($id == NULL)$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
                if(!isset($this->data['estate']))$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
            }
            
            if(config_db_item('slug_enabled') === TRUE)
            {
                $rules_dynamic['slug_'.$key_lang] = 
                    array('field'=>'slug_'.$key_lang, 'label'=>'lang:URI slug', 'rules'=>'trim');
            }
        }
        
        // Fetch all files by repository_id
        if(isset($repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$repository_id));
            foreach($files as $key=>$file)
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
                $file->zoom_enabled = false;
                $file->download_url = base_url('files/'.$file->filename);
                $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));
    
                if(file_exists(FCPATH.'/files/thumbnail/'.$file->filename))
                {
                    $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                    $file->zoom_enabled = true;
                }
                else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
                {
                    $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                }
                
                $this->data['files'][$file->repository_id][] = $file;
            }
        }
        
        // Fetch all files by repository_id
        if(isset($planimages_repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$planimages_repository_id));
            foreach($files as $key=>$file)
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
                $file->zoom_enabled = false;
                $file->download_url = base_url('files/'.$file->filename);
                $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));
    
                if(file_exists(FCPATH.'/files/thumbnail/'.$file->filename))
                {
                    $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                    $file->zoom_enabled = true;
                }
                else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
                {
                    $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                }
                
                $this->data['files'][$file->repository_id][] = $file;
            }
        }
        
        // Set up the form
        $rules = $this->estate_m->rules;
        
        /* minimum 1x images should be uploaded */ 
        if(config_item('required_images') !== FALSE) {
            $rules['repository_id'] = array('field'=>'repository_id', 'label'=>'lang:repository_id', 'rules'=>'trim|callback_min_images');;
        }
        $this->form_validation->set_rules(array_merge($rules, $rules_dynamic));
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang_check('Data editing disabled in demo'));
                redirect('admin/estate/edit/'.$id);
                exit();
            }
            
            $data = $this->estate_m->array_from_post(array('gps', 'date', 'date_modified', 'address', 'is_featured', 'is_activated', 'is_visible', 'id_transitions'));
            $dynamic_data = $this->estate_m->array_from_post(array_keys($rules_dynamic));
            
            // AGENT_LIMITED don't have permission to change this fields...
            if($this->session->userdata('type') == 'AGENT_LIMITED')
            {
                unset($data['is_activated'],
                      $data['is_featured']
                );
                
                $data['is_activated'] = 0;
            }
            
            if(empty($data['id_transitions']))
            {
                $data['id_transitions'] = NULL;
            }
            
            $data['search_values'] = $data['address'];
            foreach($dynamic_data as $key=>$val)
            {
                $pos = strpos($key, '_');
                $option_id = substr($key, 6, $pos-6);
                $language_id = substr($key, $pos+1);
                
                if(!isset($options_data[$option_id]['TEXTAREA']) && !isset($options_data[$option_id]['CHECKBOX'])){
                    $data['search_values'].=' '.$val;
                }
                
                // TODO: test check, values for each language for selected checkbox
                if(isset($options_data[$option_id]['CHECKBOX'])){
                    if($val == 'true')
                    {
                        foreach($this->option_m->languages as $key_lang=>$val_lang){
                            foreach($this->data['options_lang'][$key_lang] as $key_option=>$val_option){
                                if($val_option->id == $option_id && $language_id == $key_lang)
                                {
                                    $data['search_values'].=' true'.$val_option->option;
                                }
                            }
                        }
                    }
                }
                
                if(isset($options_data[$option_id]['DATETIME']) && !empty($val)){
                    
                    if((bool)strtotime($val)) {
                        /*echo $key.'='.$val.'<br>';*/
                        $timestamp = strtotime($val);
                        $dynamic_data[$key] = date('Y-m-d H:i:s', $timestamp);
                    }
                }
            }
            
            if(isset($data['date_modified']) && empty($data['date_modified']))
            {
                $data['date_modified'] = date('Y-m-d H:i:s');
            }
            
            if($this->session->userdata('type') != 'ADMIN')
            {
                // only admin can manually change modify date
                unset($data['date_modified']);
            }
            
            if($id === NULL)
            {
                $data['date_modified'] = date('Y-m-d H:i:s');
            }
            elseif($this->data['estate']->agent != $this->session->userdata('id'))
            {
                // If admin/agent/... modify property from other user, date_modified is not changed
            }
            else
            {
                $data['date_modified'] = date('Y-m-d H:i:s');
            }
            
            /* [Auto move gps coordinates few meters away if same exists in database] */
            $estate_same_coordinates = $this->estate_m->get_by(array('gps'=>$data['gps']), TRUE);

            if(is_object($estate_same_coordinates) && !empty($estate_same_coordinates))
            {
                $same_gps = explode(', ', $estate_same_coordinates->gps);
                // $same_gps[0] && $same_gps[1] available
                $rand_lat = rand(1, 9);
                $rand_lan = rand(1, 9);
                
                $data['gps'] = ($same_gps[0]+0.00001*$rand_lat).', '.($same_gps[1]+0.00001*$rand_lan);
            }
            /* [/Auto move gps coordinates few meters away if same exists in database] */
            
            $insert_id = $this->estate_m->save($data, $id);
            
            if(empty($insert_id))
            {
                echo '$insert_id is empty, ERROR IN QUERY: <br />';
                echo $this->db->last_query().'<br />';
                echo $this->db->_error_message();
                exit();
            }

            // add insert to search_values
            if(is_numeric($insert_id))
            {
                $update_data = array();
                $update_data['search_values'] = 'id: '.$id.$data['search_values'];
                
                $this->estate_m->save($update_data, $insert_id);
            }
            
            if( $this->session->userdata('type') != 'ADMIN' && 
                $this->session->userdata('type') != 'AGENT_ADMIN' && 
                $this->session->userdata('type') != 'AGENT_COUNTY_AFFILIATE' && !isset($this->data['related_users_id'][$this->data['estate']->agent]))
            {
                $data['agent'] = $this->session->userdata('id');
            }
            else
            {
                $data['agent'] = $this->input->post('agent');
            }           
            
            // Save dynamic options
            $dynamic_data['agent'] = $data['agent'];
            $this->estate_m->save_dynamic($dynamic_data, $insert_id);

            $this->load->library('sitemap');
            $this->sitemap->generate_sitemap();
            
            /* [Email sending] */
            
            if(ENVIRONMENT != 'development')
            if( $this->session->userdata('type') == 'AGENT_LIMITED' && 
                $this->data['settings']['email_alert'] == 1 )
            {
                // Send email alert to contact address
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings']['noreply'], lang_check('Web page not-activated property'));
                $this->email->to($this->data['settings']['email']);
                $this->email->subject(lang_check('Web page not-activated property'));
                
                $data_m = array();
                $data_m['subject'] = lang_check('New not-activated property from user');
                $data_m['name_surname'] = $this->session->userdata('username');
                $data_m['link'] = '<a href="'.site_url('admin/estate/edit/'.$insert_id.'/'.$lang_code).'">'.lang_check('Property edit link').'</a>';
                $message = $this->load->view('email/waiting_for_activation', array('data'=>$data_m), TRUE);
                
                $this->email->message($message);
                $this->email->send();
            }
            if(ENVIRONMENT != 'development' && sw_count($this->data['estate']))
            if(isset($data['is_activated']) && $data['is_activated'] == 1 && $this->data['estate']->is_activated == 0)
            {
                // get user details
                $user = $this->user_m->get($this->data['estate']->agent);
                
                if(isset($user->type) && $user->type == 'USER')
                {
                    // Send email alert to contact address
                    $this->load->library('email');
                    
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    
                    $this->email->from($this->data['settings']['noreply'], lang_check('Web page activated property'));
                    $this->email->to($user->mail);
                    $this->email->subject(lang_check('Web page activated property'));
                    
                    $data_m = array();
                    $data_m['subject'] = lang_check('New activated property');
                    $data_m['link'] = '<a href="'. slug_url($this->data['listing_uri'].'/'.$insert_id.'/'.$lang_code).'">'.lang_check('Property preview link').'</a>';
                    $message = $this->load->view('email/waiting_for_activation_user', array('data'=>$data_m), TRUE);
                    
                    $this->email->message($message);
                    $this->email->send();
                }
            }
            
            /* [/Email sending] */
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            redirect('admin/estate/edit/'.$insert_id);
        }
        
        // Load the view
		$this->data['subview'] = 'admin/estate/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function status($id=NULL, $status=NULL, $redirect_uri = '')
    {
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('admin/estate/');
            exit();
        }
        
        $this->data['estate'] = $this->estate_m->get_dynamic($id);
        
        if(sw_count($this->data['estate']) == 0)
        {
            $this->data['errors'][] = 'Estate could not be found';
            redirect('admin/estate/');
        }
        
        if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
        {
            // [AGENT_COUNTY_AFFILIATE]
            $this->load->model('affilatepackages_m');
            $user_id = $this->session->userdata('id');
            $language_id = $this->data['content_language_id'];
            
            //get_related_affilate($default_lang_id, $county_affiliate_values)
            if(!$this->affilatepackages_m->check_user_affilate($user_id, $id, $language_id))
            {
                redirect('admin/estate');
            }
    
            // [/AGENT_COUNTY_AFFILIATE]
        }
        else if($this->session->userdata('type') != 'ADMIN')
        {
            redirect('admin/estate');
        }
        
        $update_data = array();
        $update_data['status']      = $status;
        $update_data['date_status'] = date('Y-m-d H:i:s');
        
        if($status == 'APPROVE' || $status == 'APPROVE_REDUCED')
        {
            $update_data['is_activated']    = 1;
            $update_data['date_repost']     = date('Y-m-d H:i:s');
            $update_data['date_modified']   = date('Y-m-d H:i:s');
            $update_data['date_alert']      = date('Y-m-d H:i:s');
        }
        elseif($status == 'APPROVE_RESUBMIT')
        {
            $update_data['is_activated']    = 1;
            $update_data['date_repost']     = date('Y-m-d H:i:s');
            $update_data['date_modified']   = date('Y-m-d H:i:s');
        }
        else
        {
            $update_data['is_activated']    = 0;
        }
        
        $this->estate_m->save($update_data, $id);
        
        $insert_id = $id;
        
        // get user details
        $user = $this->user_m->get($this->data['estate']->agent);
        
        if($status == 'DECLINE')
        {
            /*
                Declined
                
                *User receives generic email with the reasons why most postings get declined.
            */
            $lang_code = $this->language_m->get_default();
            
            // Send message to user
            $this->load->library('email');
            
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings']['noreply'], lang_check('Your property declined!'));
            $this->email->to($user->mail);
            $this->email->subject(lang_check('Your property declined!'));
            
            $data_m = array();
            $data_m['subject'] = lang_check('Your property declined!');
            $data_m['link'] = '<a href="'.site_url('frontend/editproperty/'.$lang_code.'/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
            $message = $this->load->view('email/declined_property', array('data'=>$data_m), TRUE);
            
            $this->email->message($message);
            if ( ! $this->email->send())
            {
                exit('Sending email error, to:'.$user->mail."\n".$this->email->print_debugger());
            }
        }
        elseif($status == 'HOLD' || $status == 'HOLD_REDUCED' || $status == 'HOLD_RESUBMIT')
        {
            /*
                Hold
                
                *User receives notification that someone from our team has interest in his property.
            */
            
            $lang_code = $this->language_m->get_default();
            
            // Send message to user
            $this->load->library('email');
            
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings']['noreply'], lang_check('Your property on hold!'));
            $this->email->to($user->mail);
            $this->email->subject(lang_check('Your property on hold!'));
            
            $data_m = array();
            $data_m['subject'] = lang_check('Your property on hold!');
            $message = $this->load->view('email/hold_property', array('data'=>$data_m), TRUE);

            $this->email->message($message);
            if ( ! $this->email->send())
            {
                exit('Sending email error, to:'.$user->mail."\n".$this->email->print_debugger());
            }
        }
        elseif($status == 'CONTRACT')
        {
            /*
                Contract
                
                *User: Receives notification letting him know that our team member has decided to contract his property.
            */

            $lang_code = $this->language_m->get_default();
            
            // Send message to user
            $this->load->library('email');
            
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings']['noreply'], lang_check('Your property purchased!'));
            $this->email->to($user->mail);
            $this->email->subject(lang_check('Your property purchased!'));
            
            $data_m = array();
            $data_m['subject'] = lang_check('Your property purchased!');
            $message = $this->load->view('email/contract_property', array('data'=>$data_m), TRUE);

            $this->email->message($message);
            if ( ! $this->email->send())
            {
                exit('Sending email error, to:'.$user->mail."\n".$this->email->print_debugger());
            }
        }
        elseif($status == 'APPROVE' || $status == 'APPROVE_REDUCED')
        {
            /*
                Property approved
                
                *User receives notification letting him know his property has been posted on the website.
            */

            if($user->type == 'USER')
            {
                // Send email alert to contact address
                $this->load->library('email');
                
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings']['noreply'], lang_check('Web page activated property'));
                $this->email->to($user->mail);
                $this->email->subject(lang_check('Web page activated property'));
                
                $data_m = array();
                $data_m['subject'] = lang_check('New activated property');
                $data_m['link'] = '<a href="'. slug_url($this->data['listing_uri'].'/'.$insert_id.'/').'">'.lang_check('Property preview link').'</a>';
                $message = $this->load->view('email/waiting_for_activation_user', array('data'=>$data_m), TRUE);
                
                $this->email->message($message);
                if ( ! $this->email->send())
                {
                    exit('Sending email error, to:'.$user->mail."\n".$this->email->print_debugger());
                }
            }
        }
        
        redirect('admin/estate/'.$redirect_uri);
    }
    
    public function delete($id, $redirect = TRUE)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('admin/estate');
            exit();
        }
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->data['estate'] = $this->estate_m->get_dynamic($id);
            
            if(sw_count($this->data['estate']) > 0)
            {
                if($this->data['estate']->agent == $this->session->userdata('id'))
                {
                    
                }
                else if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
                {
                    // [AGENT_COUNTY_AFFILIATE]
                    $this->load->model('affilatepackages_m');
                    $user_id = $this->session->userdata('id');
                    $language_id = $this->data['content_language_id'];
                    
                    //get_related_affilate($default_lang_id, $county_affiliate_values)
                    if(!$this->affilatepackages_m->check_user_affilate($user_id, $id, $language_id))
                    {
                        redirect('admin/estate');
                    }
            
                    // [/AGENT_COUNTY_AFFILIATE]
                }
                else
                {
                    if(!$redirect)return;
                    redirect('admin/estate');
                }
            }
        }
       
		$this->estate_m->delete($id);
        
        if(!$redirect)return;
        redirect('admin/estate');
	}
    
    public function delete_multiple()
    {
        // var_dump($_POST);
        // array(1) { ["delete_multiple"]=> array(3) { [0]=> string(2) "31" [1]=> string(2) "30" [2]=> string(2) "29" } } 
        if(isset($_POST["delete_multiple"]) && !empty($_POST["delete_multiple"]))
            foreach($_POST["delete_multiple"] as $property_id)
            {
                if(is_numeric($property_id))
                    $this->delete($property_id, FALSE);
            }
        
        redirect('admin/estate');
    }
    
    public function removed()
    {
        $this->load->model('removedlistings_m');
        $this->data['listings'] = $this->removedlistings_m->get();
        
        // Load view
		$this->data['subview'] = 'admin/estate/removed';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function options()
	{
        // Fetch all estates
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['options_no_parents'] = $this->option_m->get_no_parents($this->data['content_language_id']);
        $this->data['options'] = $this->option_m->get_lang(NULL, FALSE, $this->data['content_language_id']);
        $this->data['options_nested'] = $this->option_m->get_nested($this->data['content_language_id']);
        
        //var_dump($this->data['options_nested']);
        
        // Load view
		$this->data['subview'] = 'admin/estate/options';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function numeric_field_range($id=NULL, $type='INTEGER')
    {
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('admin/estate/edit_option/'.$id);
            exit();
        }

        if($this->option_m->numeric_field_range($id,$type) === TRUE)
        {
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
        }
        else
        {
            $this->session->set_flashdata('message', 
                    '<p class="label label-danger validation">'.lang_check('Query failed, probably permissions on db missing').'</p>');
        }

        redirect('admin/estate/edit_option/'.$id);
    }
    
    public function dependent_fields()
	{
        
        $this->load->model('dependentfield_m');
        
        $this->data['listings'] = $this->dependentfield_m->get_detailed($this->data['content_language_id']);
        
        // Load view
		$this->data['subview'] = 'admin/estate/dependent_fields';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function edit_dependent_field($id = NULL)
    {
        $this->load->model('dependentfield_m');

        // Fetch a user or set a new one
	    if($id)
        {
            $this->data['item'] = $this->dependentfield_m->get($id);

            if(sw_count($this->data['item']) == 0)
            {
                $this->data['errors'][] = lang_check('Could not be found');
                redirect('admin/estate/dependent_fields');
            }
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN')
            {
                redirect('admin/estate/dependent_fields');
            }

        }
        else
        {
            $this->data['item'] = $this->dependentfield_m->get_new();
        }
        
        if(!empty($this->data['item']->field_id))
        {
            $field_data = $this->option_m->get($this->data['item']->field_id);
        } else {
            $field_data = '';
        }
        
        // Load additional resources
        $this->data['available_fields'] = $this->dependentfield_m->get_available_fields($this->data['content_language_id']);
        $this->data['available_indexes'] = $this->dependentfield_m->get_field_values($this->data['content_language_id'], $this->data['item']->field_id, lang_check('Not selected'));
        
        $this->data['fields_under_selected'] = array();
        if(!empty($field_data))
            $this->data['fields_under_selected'] = $this->dependentfield_m->get_fields_under($field_data->order, $this->data['content_language_id']);
        
        // Fetch hidden fields
        if(!empty($this->data['item']->hidden_fields_list))
        {
            foreach(explode(',', $this->data['item']->hidden_fields_list) as $f_id)
            {
                $this->data['item']->{'field_'.$f_id} = '1';
            }
        }
        
        // Form configuration
        $rules = $this->dependentfield_m->rules;
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang_check('Data editing disabled in demo'));
                redirect('admin/estate/edit_dependent_field/'.$id);
                exit();
            }
            
            $data = $this->dependentfield_m->array_from_rules($rules);
            
            // [Hidden fields]
            $hidden_fields_list = array();
            foreach($_POST as $key=>$val)
            {
                $exp = explode('_', $key);
                if(sw_count($exp) == 2)
                {
                    if($exp[0] == 'field' && is_numeric($exp[1]))
                    {
                        $hidden_fields_list[] = $exp[1];
                    }
                }
                
            }
            $data['hidden_fields_list'] = implode(',', $hidden_fields_list);
            
            // [/Hidden fields]
            
            $id = $this->dependentfield_m->save($data, $id);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            //if(empty($id))
            //    $this->output->enable_profiler(TRUE);

            redirect('admin/estate/edit_dependent_field/'.$id);
        }
        
        // Load view
		$this->data['subview'] = 'admin/estate/edit_dependent_field';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function delete_dependent_field($id)
    {
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('admin/estate/dependent_fields');
            exit();
        }
        
        $this->load->model('dependentfield_m');
        $this->dependentfield_m->delete($id);
		
        redirect('admin/estate/dependent_fields');
    }
    
    public function edit_option($id = NULL)
	{
	    // Fetch a record or set a new one
	    if($id)
        {
            $this->data['option'] = $this->option_m->get_lang($id, FALSE, $this->data['content_language_id']);
            sw_count($this->data['option']) || $this->data['errors'][] = 'Could not be found';
            
            // Fetch file repository
            $repository_id = $this->data['option']->repository_id;
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('name'=>'option_m'));
                
                // Update page with new repository_id
                $this->option_m->save(array('repository_id'=>$repository_id), $this->data['option']->id);
            }
        }
        else
        {
            $this->data['option'] = $this->option_m->get_new();
        }
        
        // Fetch all files by repository_id
        $this->data['files'] = array();
        if(!empty($repository_id))
        {
            $files = $this->file_m->get_where_in(array($repository_id));
            foreach($files as $key=>$file)
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
                $file->zoom_enabled = false;
                $file->download_url = base_url('files/'.$file->filename);
                $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));
    
                if(file_exists(FCPATH.'/files/thumbnail/'.$file->filename))
                {
                    $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                    $file->zoom_enabled = true;
                }
                else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
                {
                    $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                }
                
                $this->data['files'][$file->repository_id][] = $file;
            }
        }

        
		// Options for dropdown
        $this->data['options_no_parents'] = $this->option_m->get_no_parents($this->data['content_language_id'], $id);
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');

        // Set up the form
        $rules = $this->option_m->get_all_rules();
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang_check('Data editing disabled in demo'));
                redirect('admin/estate/edit_option/'.$id);
                exit();
            }
            
            $data = $this->option_m->array_from_post($this->option_m->get_post_fields());
            
            if(empty($data['max_length']))
                $data['max_length'] = NULL;
            
            if($id == NULL)
            {
                //get max order in parent id and set
                $parent_id = $this->input->post('parent_id');
                $data['order'] = $this->option_m->max_order($parent_id);
            }
            
            $data_lang = $this->option_m->array_from_post($this->option_m->get_lang_post_fields());
            
            if(empty($data['columns_number']))$data['columns_number'] = NULL;
            
            $id = $this->option_m->save_with_lang($data, $data_lang, $id);
            
            //$this->output->enable_profiler(TRUE);
            //redirect('admin/estate/options');
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            redirect('admin/estate/edit_option/'.$id);
        }
        
        // Load the view
		$this->data['subview'] = 'admin/estate/edit_option';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function update_ajax($filename = NULL)
    {
        // Save order from ajax call
        if(isset($_POST['sortable']) && $this->config->item('app_type') != 'demo')
        {
            $this->option_m->save_order($_POST['sortable']);
        }
        
        $data = array();
        $length = strlen(json_encode($data));
        header('Content-Type: application/json; charset=utf8');
        header('Content-Length: '.$length);
        echo json_encode($data);
        
        exit();
    }
    
    public function delete_option($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('admin/estate/options');
            exit();
        }
        
        if($this->option_m->check_deletable($id))
        {
            $this->option_m->delete($id);
        }
        else
        {
            $this->session->set_flashdata('error', 
                    lang_check('Delete disabled, child or element locked/hardlocked! But you can change or unlock it.'));
        }
		
        redirect('admin/estate/options');
	}
    
    public function forms($pagination_offset=0)
	{
	    $this->load->model('forms_m');
	    $this->load->library('pagination');
        
        $listing_selected = array();
        $listing_selected['theme'] = $this->data['settings']['template'];

        if(config_db_item('loaded_template_config') !== FALSE)
            $listing_selected['theme'] = config_db_item('loaded_template_config');
        
        // Fetch all listings
        $this->data['listings'] = $this->forms_m->get_by($listing_selected);
        
        $user_id = $this->session->userdata('id');
        
        $config['base_url'] = site_url('admin/savedsearch/index/'.$user_id.'/');
        $config['uri_segment'] = 4;
        $config['total_rows'] = sw_count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['listings'] = $this->forms_m->get_by($listing_selected, FALSE, $config['per_page'], NULL, $pagination_offset);

        // Load view
		$this->data['subview'] = 'admin/forms/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function values_correction(&$str)
    {
        $str = str_replace(', ', ',', $str);
        
        return TRUE;
    }
    
    public function values_dropdown_check($str)
    {
        static $already_set = false;
        $comma_count = -1;
        
        if($already_set == true)
            return TRUE;
        
        foreach($this->option_m->languages as $key=>$value)
        {
            $values_post = $this->input->post("values_$key");
            
            $comma_cur_count = substr_count($values_post, ',');
            
            if($comma_count == -1)$comma_count = $comma_cur_count;
            
            if($comma_count != $comma_cur_count)
            {
                $this->form_validation->set_message('values_dropdown_check', lang_check('Values number must be same in all languages'));
                $already_set = true;
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
	public function gps_check($str)
	{
            
        if(strpos($str, ', '))
            $gps_coor = explode(', ', $str);
        else
            $gps_coor = explode(',', $str);
        
        if(empty($str) && config_db_item('address_not_required') === TRUE)
            return TRUE;
        
        if(sw_count($gps_coor) != 2)
        {
        	$this->form_validation->set_message('gps_check', lang_check('Please check GPS coordinates'));
        	return FALSE;
        }
        
        if(!is_numeric($gps_coor[0]) || !is_numeric($gps_coor[1]))
        {
        	$this->form_validation->set_message('gps_check', lang_check('Please check GPS coordinates'));
        	return FALSE;
        }
        
        if($gps_coor[0] < -90 || $gps_coor[0] > 90 || $gps_coor[1] < -180 || $gps_coor[1] > 180)
        {
        	$this->form_validation->set_message('gps_check', lang_check('Please check GPS coordinates'));
        	return FALSE;
        }
        
        return TRUE;
	}
    
    public function json2field($field_id)
    {
        $query = $this->db->get('property_lang');

        if (!$this->db->field_exists("field_$field_id", 'property_lang'))
        {
            exit("field_$field_id doesn't exists");
        } 

        $data = array();

        foreach ($query->result() as $row)
        {
            $json_obj = json_decode($row->json_object);
            
            if(isset($json_obj->{"field_$field_id"}))
            {
                $data[] = array(
                  'l_id' => (int) $row->l_id,
                  "field_$field_id" => (string) $json_obj->{"field_$field_id"}
                );
            }
            
            //if(sw_count($data) > 3)break;
        }

        $this->db->update_batch('property_lang', $data, 'l_id'); 
        
        exit('update successfuly: '.sw_count($data));
    }
    
    
    public function generic_description($limit = 50, $lang_id = 1)
    {
        
        if($this->session->userdata('type') != 'ADMIN')
            exit('Permission denied');

        // Fetch all listings
        $estates = $this->estate_m->get_by(array('is_activated' => 1, 'language_id'=>$lang_id), FALSE, $limit, 'id DESC');
        
        foreach($estates as $key=>$estate)
        {
            $json_obj = json_decode($estate->json_object);
            
            // Generate new description
            $gen_description = '';
            
            if(isset($json_obj->{"field_10"}) && !empty($json_obj->{"field_10"}))
            {
                $gen_description.= $json_obj->{"field_10"}.' ';
            }
            
            if(isset($json_obj->{"field_4"}) && !empty($json_obj->{"field_4"}))
            {
                $gen_description.= 'for '.$json_obj->{"field_4"}.' ';
            }
            
            $gen_description.= 'from '.$estate->address.'.<br />';
            
            if(isset($json_obj->{"field_36"}) && !empty($json_obj->{"field_36"}))
            {
                $gen_description.= 'Sale price is '.$json_obj->{"field_36"}.'. ';
            }
            
            if(isset($json_obj->{"field_37"}) && !empty($json_obj->{"field_37"}))
            {
                $gen_description.= 'Rent price is '.$json_obj->{"field_37"}.'. ';
            }
            
            // Save new description to property_value in ID# 8 and 17
            
            $data = array('value' => $gen_description);
            $this->db->where('property_id', $estate->id);
            $this->db->where('language_id', $lang_id);
            $this->db->where('option_id', 8);
            $this->db->update('property_value', $data); 
            
            $data = array('value' => $gen_description);
            $this->db->where('property_id', $estate->id);
            $this->db->where('language_id', $lang_id);
            $this->db->where('option_id', 17);
            $this->db->update('property_value', $data); 

            // Save new description to property_lang, json_object in ID# 8 and 17
            
            $json_obj->{"field_8"} = $gen_description;
            $json_obj->{"field_17"} = $gen_description;
            
            $data = array('json_object' => json_encode($json_obj));
            $this->db->where('property_id', $estate->id);
            $this->db->where('language_id', $lang_id);
            $this->db->update('property_lang', $data); 
            
            echo "Completed for #ID: ".$estate->id."<br />";
        }
        
        echo "Method completed<br />";
    }
    
	public function featured_limitation_check($str)
	{
        if($str=='1')
        {
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                // Get exception property_id
                $exception_property_id = NULL;
                if(isset($this->data['estate']))
                    $exception_property_id = $this->data['estate']->id;
                
                $this->load->model('user_m');
                $user = $this->user_m->get($this->session->userdata('id'));
                if(empty($user->package_id)) return TRUE;
                
                if($this->packages_m->get_available_featured(NULL, $exception_property_id) == 0)
                {
                	$this->form_validation->set_message('featured_limitation_check', 
                                                        lang_check('Featured limitation reached in your package!'));
                	return FALSE;
                }
            }
        }
        
        return TRUE;
	}
    
        public function import_csv() {
            $this->load->library('Importcsv');
            /* feature */
            $xml='file.csv';
            $config['allowed_types'] = 'csv|xml|txt|text';
            $config['allowed_types'] = '*'; // test
            $config['upload_path'] = './files/';
            $config['overwrite'] = TRUE;
            $this->data['message'] = '';
            
            $this->load->library('upload', $config);
            $this->data['skipped']=0;
            
            $this->form_validation->set_rules('csv_url', "lang: CSV Url",'trim');
            
            if($this->form_validation->run()== TRUE) {
                if($this->config->item('app_type') == 'demo')
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Data editing disabled in demo'));
                    redirect('admin/estate');
                    exit();
                }
                
                $google_gps = false;
                if($this->input->post('google_gps') && $this->input->post('google_gps')==1){
                   $google_gps = true;
                } 
                
                    if($this->input->post('csv_url')) {
                        if(preg_match('/\.(csv|xml|txt|text)$/i', $this->input->post('csv_url'))){
                        // Load csv file for import
                        $xmlurl = trim($this->input->post('csv_url'));
                        /*$imports=$this->importcsv->start_import($xmlurl);*/
                        
                        if($this->input->post('overwrite_existing') && $this->input->post('overwrite_existing')==1){
                            $imports=$this->importcsv->start_import($xmlurl, true, $this->input->post('max_images'), $google_gps, $this->input->post('limit_listings'));
                        } else {
                            $imports=$this->importcsv->start_import($xmlurl, false, $this->input->post('max_images'), $google_gps, $this->input->post('limit_listings'));
                        }
                        
                        $this->data['imports']= $imports['info'];
                        $this->data['skipped']= $imports['count_skip'];
                        
                        } else {
                            $this->data['error'] = 'Set current csv link';
                        }
                    } else if($this->upload->do_upload('userfile_csv')){
                        $this->upload->do_upload('userfile_xml');
                        $upload_data = $this->upload->data();
                        $file_path = $upload_data['full_path'];

                        // Load csv file for import
                        $xmlurl = $file_path;
                        
                        if($this->input->post('overwrite_existing') && $this->input->post('overwrite_existing')==1){
                            $imports=$this->importcsv->start_import($xmlurl, true, $this->input->post('max_images'), $google_gps, $this->input->post('limit_listings'));
                        } else {
                            $imports=$this->importcsv->start_import($xmlurl,false, $this->input->post('max_images'), $google_gps, $this->input->post('limit_listings'));
                        }
                        
                        $this->data['imports']= $imports['info'];
                        $this->data['skipped']= $imports['count_skip'];
                        if(isset($imports['message']))
                            $this->data['message']= $imports['message'];
                        
                                
                    } else {
                        /* error */
                        $this->data['error'] = $this->upload->display_errors('', '');
                    }
            }
                
        // Load view
        $this->data['subview'] = 'admin/estate/import_csv';
        $this->load->view('admin/_layout_main', $this->data);
        }
        
        function export_csv ($limit_properties=NULL, $offset_properties=0){
            $this->load->library('importcsv');
            $this->load->helper('download');
            
            $date = date('Y-m-d H:i:s');
            force_download('export_'.$date.'.csv', $this->importcsv->get_csv($limit_properties, $offset_properties));
            
        }
        
        /*
         * 
         *  Import places from https://developers.google.com/places/web-service/
         * 
         */
        
        function import_google_places () {
            
            if(config_item('import_google_places')!== TRUE || !file_exists(APPPATH.'libraries/Import_google_places.php')) {
                redirect('admin/estate');
            }
            
            $this->load->library('import_google_places');
            $this->load->model('estate_m');
            $this->load->model('treefield_m');
            
            /* add libraries and model */
            $this->CI = &get_instance();
            $this->CI->load->model('estate_m');
            $this->CI->load->model('file_m');
            $this->CI->load->model('language_m');
            $this->CI->load->model('repository_m');
            $this->CI->load->library('uploadHandler', array('initialize'=>FALSE));
            $this->CI->load->library('ghelper');
            $this->CI->load->model('option_m',2);
            $lang_id =  $this->language_m->get_default_id();
            
            $this->data['category_list'] = $this->option_m->get_field_values($lang_id, 2);
            $this->data['marker_list'] = $this->option_m->get_field_values($lang_id, 6);
            $this->data['message']='';
            
            $this->data['gps'] = '';
            if(isset($this->data['settings']['gps']) && !empty($this->data['settings']['gps']))
                $this->data['gps'] = $this->data['settings']['gps'];
            
            // from https://developers.google.com/places/supported_types#table1
            $types = array('accounting','airport','accounting','airport','amusement_park','aquarium','art_gallery','atm','bakery','bank',
                            'bar','beauty_salon','bicycle_store','book_store','bowling_alley','bus_station','cafe','campground',
                            'car_dealer','car_rental','car_repair','car_wash','casino','cemetery','church','city_hall','clothing_store','convenience_store',
                            'courthouse','dentist','department_store','doctor','electrician','electronics_store','embassy','fire_station',
                            'florist','food','funeral_home','furniture_store','gas_station','gas_station','general_contractor','grocery_or_supermarket',                
                            'gym','hair_care','hardware_store','health','hindu_temple','home_goods_store','hospital','insurance_agency','jewelry_store',
                            'laundry','lawyer','library','liquor_store','local_government_office','locksmith','lodging','meal_delivery','meal_takeaway',
                            'mosque','movie_rental','movie_theater','moving_company','museum','night_club','painter','park','parking',
                            'pet_store','pharmacy','physiotherapist','place_of_worship','plumber','police','post_office','real_estate_agency',
                            'restaurant','roofing_contractor','rv_park','school','shoe_store','shopping_mall','spa','stadium','storage','store',
                            'subway_station','synagogue','taxi_stand','train_station','transit_station','travel_agency','university','veterinary_care',
                            'zoo'
                        );
            
            $this->data['types_list']=  array_combine($types,$types);
            
            $langs_api = array(
                    "ar"=> lang_check("Arabic"),
                    "bg"=> lang_check("Bulgarian"),
                    "bn"=> lang_check("Bengali"),
                    "ca"=> lang_check("Catalan"),
                    "cs"=> lang_check("Czech"),
                    "da"=> lang_check("Danish"),
                    "de"=> lang_check("German"),
                    "el"=> lang_check("Greek"),
                    "en"=> lang_check("English"),
                    "en-AU"=> lang_check("English (Australian)"),
                    "en-GB"=> lang_check("English (Great Britain)"),
                    "es"=> lang_check("Spanish"),
                    "eu"=> lang_check("Basque"),
                    "fa"=> lang_check("Farsi"),
                    "fi"=> lang_check("Finnish"),
                    "fil"=>lang_check("Filipino"),
                    "fr"=> lang_check("French"),
                    "gl"=> lang_check("Galician"),
                    "gu"=> lang_check("Gujarati"),
                    "hi"=> lang_check("Hindi"),
                    "hr"=> lang_check("Croatian"),
                    "hu"=> lang_check("Hungarian"),
                    "id"=> lang_check("Indonesian"),
                    "it"=> lang_check("Italian"),
                    "iw"=> lang_check("Hebrew"),
                    "ja"=> lang_check("Japanese"),
                    "kn"=> lang_check("Kannada"),
                    "ko"=> lang_check("Korean"),
                    "lt"=> lang_check("Lithuanian"),
                    "lv"=> lang_check("Latvian"),
                    "ml"=> lang_check("Malayalam"),
                    "mr"=> lang_check("Marathi"),
                    "nl"=> lang_check("Dutch"),
                    "no"=> lang_check("Norwegian"),
                    "pl"=> lang_check("Polish"),
                    "pt"=> lang_check("Portuguese"),
                    "pt-BR"=> lang_check("Portuguese (Brazil)"),
                    "pt-PT"=> lang_check("Portuguese (Portugal)"),
                    "ro"=> lang_check("Romanian"),
                    "ru"=> lang_check("Russian"),
                    "sk"=> lang_check("Slovak"),
                    "sl"=> lang_check("Slovenian"),
                    "sr"=> lang_check("Serbian"),
                    "sv"=> lang_check("Swedish"),
                    "ta"=> lang_check("Tamil"),
                    "te"=> lang_check("Telugu"),
                    "th"=> lang_check("Thai"),
                    "tl"=> lang_check("Tagalog"),
                    "tr"=> lang_check("Turkish"),
                    "uk"=> lang_check("Ukrainian"),
                    "vi"=> lang_check("Vietnamese"),
                    "zh-CN"=> lang_check("Chinese (Simplified)"),
                    "zh-TW"=> lang_check("Chinese (Traditional)"),
            );
            $this->data['langs_api']=  $langs_api;
            $this->data['lang_code'] = $this->language_m->get_default();
            
            $form_import = $this->input->post('form_import');
            
            $this->data['gps_google']='';
            $this->data['preview_data']=array();
            $this->data['imported']='';
            $this->data['marker_category']='';
            $this->data['marker_category_string']='';
            
            // $form_import==1 - import form, else preview
            if($form_import==1) {
                $this->form_validation->set_rules('gps_google', "lang: Gps google",'trim|required');
                $this->form_validation->set_rules('radius', "lang: Radius",'trim|required');
                $this->form_validation->set_rules('type', "lang: Type",'trim|required');
                $this->form_validation->set_rules('name', "lang: Name",'trim');
                
            } else {
                $this->form_validation->set_rules('gps_google', "lang: Gps google",'trim|required');
                $this->form_validation->set_rules('radius', "lang: Radius",'trim|required');
                $this->form_validation->set_rules('type', "lang: Type",'trim|required');
                $this->form_validation->set_rules('name', "lang: Name",'trim');
            }
            
            
            
            if($this->form_validation->run()== TRUE) {
                if($this->config->item('app_type') == 'demo')
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Data editing disabled in demo'));
                    redirect('admin/estate');
                    exit();
                }
                // time limit increase
                set_time_limit(9999999);
                
                $gps_google= $this->input->post('gps_google');
                $gps_google= str_replace(' ', '', $gps_google);

                $this->data['gps_google'] = $this->input->post('gps_google');
                $this->data['radius'] = $this->input->post('radius');
                $this->data['type'] = $this->input->post('type');
                $this->data['name'] = $this->input->post('name');
                $this->data['lang_api'] = $this->input->post('lang_api');
                $this->data['geocode_api'] = $this->input->post('geocode_api');
                $this->data['place_in_detail'] = $this->input->post('place_in_detail');
                
                $category_tree = $this->input->post('option79_1'); 
                $cache_results = $this->input->post('cache_results');
                if($cache_results)
                    $this->import_google_places->caching_results = unserialize(base64_decode($cache_results));
                
                $geocode = false;
                if($form_import==1) {
                   // import start
                   $add_multiple = $this->input->post('add_multiple');
                   $category = $this->input->post('type_db');
                   $marker_category = $this->input->post('marker_category');
                   if($this->data['geocode_api'] == 1)
                       $geocode = true;
                   
                    $preview_data=$this->import_google_places->import($this->data['gps_google'],$this->data['radius'],$this->data['type'], $this->data['name'], false, $this->input->post('lang_api'), $add_multiple, $category, $marker_category, $this->input->post('max_images'),$geocode, $this->data['place_in_detail'], $category_tree);
                    $this->data['imported']=true;
                    $this->data['preview_data']=$preview_data['preview_data'];
                    if(isset($preview_data['message']))
                        $this->data['message']=$preview_data['message'];

                } else {
                    // preview import start
                    $preview_data=$this->import_google_places->import($this->data['gps_google'],$this->data['radius'],$this->data['type'], $this->data['name'], true, $this->input->post('lang_api'));
                    $this->data['preview_data']=$preview_data['preview_data'];
                    
                    $this->data['output_log']=$this->import_google_places->google_output;
                    $this->data['cache_results'] = base64_encode(serialize($this->import_google_places->caching_results));
                    
                    if(isset($preview_data['message']))
                        $this->data['message']=$preview_data['message'];
                    
                    // if 0 results
                    if(empty($preview_data)) {
                        $this->data['error']=lang_check('0 results for import, please check more info in Developer log (at bottom of page)');
                    }
                }
            }
            
            // Load view
            $this->data['subview'] = 'admin/estate/import_google_places';
            $this->load->view('admin/_layout_main', $this->data);
        }
        
        function import_xml2u() {
            if(!file_exists(APPPATH.'libraries/Xml2u.php') || $this->session->userdata('type')!='ADMIN') {
                exit('XML2U modul is not installed');
            }
            
            $this->data['message']='';
            $this->load->library('xml2u');
            
            $lang_id =  $this->language_m->get_default_id();
            $this->form_validation->set_rules('xml_url', "lang: XML Url", 'trim|required');
            $this->form_validation->set_rules('xml_offset', "lang: Offset", 'trim|numeric');
            $this->form_validation->set_rules('xml_limit', "lang: Limit", 'trim|numeric');
            
            
            if($this->form_validation->run() == TRUE) {
                
                if($this->config->item('app_type') == 'demo')
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Data editing disabled in demo'));
                    redirect('admin/estate');
                    exit();
                }
                
                $url = $this->input->post('xml_url'); 
                
                $overwrite_existing = false;
                if($this->input->post('overwrite_existing') && $this->input->post('overwrite_existing')==1){
                   $overwrite_existing = true;
                } 
                
                $activated = false;
                if($this->input->post('activated') && $this->input->post('activated')==1){
                   $activated = true;
                } 
                
                $google_gps = false;
                if($this->input->post('google_gps') && $this->input->post('google_gps')==1){
                   $google_gps = true;
                } 
                
                $offset= false;
                if($this->input->post('xml_offset') && !empty($this->input->post('xml_offset'))){
                   $offset = $this->input->post('xml_offset');
                } 
                
                $limit = false;
                if($this->input->post('xml_limit') && !empty($this->input->post('xml_limit'))){
                   $limit = $this->input->post('xml_limit');
                } 
                
                $result_import = $this->xml2u->import($url, $overwrite_existing, $activated, $google_gps, $this->input->post('max_images'), $this->input->post('user_id'), $offset, $limit);
                $this->data['imports']= $result_import['info'];
                $this->data['skipped']= $result_import['count_skip'];
                $this->data['count_exists_overwrite']= $result_import['count_exists_overwrite'];
                $this->data['count_exists']= $result_import['count_exists'];
                
                if(isset($result_import['message']))
                    $this->data['message']= $result_import['message'];
                
            }
            
            // Load view
            $this->data['subview'] = 'admin/estate/import_xml2u';
            $this->load->view('admin/_layout_main', $this->data);
            
        }
        
        
          /*
         * 
         *  Import places from https://developers.google.com/places/web-service/
         * 
         */
        
        function import_foursquare () {
            
            if(config_item('import_foursquare')!== TRUE || !file_exists(APPPATH.'libraries/Import_foursquare.php')) {
                redirect('admin/estate');
            }
            
            $this->load->library('import_foursquare');
            $this->load->model('estate_m');
            /* add libraries and model */
            
            $lang_id =  $this->language_m->get_default_id();
            
            $this->data['category_list'] = $this->option_m->get_field_values($lang_id, 2);
            $this->data['marker_list'] = $this->option_m->get_field_values($lang_id, 6);
            
            $this->data['gps'] = '';
            if(isset($this->data['settings']['gps']) && !empty($this->data['settings']['gps']))
                $this->data['gps'] = $this->data['settings']['gps'];
            
            // from https://developer.foursquare.com/docs/venues/explore
            $types = array('food','drinks','coffee','arts','outdoors','sights','trending','specials','nextVenues','topPicks'
                        );
            
            
            /* GENERATE ARRAY WITH CATEGORY 
             *  output example:
             *   array (
             *     'Arts & Entertainment' => '4d4b7104d754a06370d81259',
             *     '-Amphitheater' => '56aa371be4b08b9a8d5734db',
             *     '-Aquarium' => '4fceea171983d5d06c3e9823',
             *     '-Arcade' => '4bf58dd8d48988d1e1931735',
             *    ...
             *  );
             *              
             */
            $generate_categories_list = function(&$results=NULL, $categories=NULL, $level=1) use (&$generate_categories_list) {
                $level_gen = str_pad('', $level*3, ' - ');
                foreach ($categories as $key => $value) {
                    $results[$level_gen.$value->name] = $value->id;
                    if(isset($value->categories) && !empty($value->categories)) {
                        $generate_categories_list($results, $value->categories, $level+1);
                    }
                }
            };
            $url = 'https://api.foursquare.com/v2/venues/categories?oauth_token=G0EZ51NXVYDAXGHHYVKLCKJX5ZF4LPA4TIKLPQ3U1MSFXT33&v=20170627';
            $categories_list = array();
            @$json = file_get_contents($url);
            $json= json_decode($json);
            $response = $json->response;
            $categories = $response->categories;
            $generate_categories_list($categories_list,$categories);
            /*
            dump($categories_list);
            exit();
            */
            
            $this->data['types_list']=  array_flip($categories_list);
            //$this->data['types_list']=  array_combine($types,$types);
            
            $form_import = $this->input->post('form_import');
            
            $this->data['gps_google']='';
            $this->data['preview_data']=array();
            $this->data['imported']='';
            $this->data['marker_category']='';
            $this->data['marker_category_string']='';
            $this->data['message']='';
            $this->data['message_successful']='';
            $this->data['type'] ='';
            
            // $form_import==1 - import form, else preview
            if($form_import==1) {
                $this->form_validation->set_rules('gps_google', "lang: Gps google",'trim|required');
                $this->form_validation->set_rules('radius', "lang: Radius",'trim|required');
                //$this->form_validation->set_rules('type', "lang: Type",'trim|required');
                //$this->form_validation->set_rules('name', "lang: Name",'trim');
                
            } else {
                $this->form_validation->set_rules('gps_google', "lang: Gps google",'trim|required');
                $this->form_validation->set_rules('radius', "lang: Radius",'trim|required');
                //$this->form_validation->set_rules('type', "lang: Type",'trim|required');
               // $this->form_validation->set_rules('name', "lang: Name",'trim');
            
            }
            
            if($this->form_validation->run()== TRUE) {
                
                if($this->config->item('app_type') == 'demo')
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Data editing disabled in demo'));
                    redirect('admin/estate');
                    exit();
                }
                
                $gps_google= $this->input->post('gps_google');
                $gps_google= str_replace(' ', '', $gps_google);

                $this->data['gps_google'] = $this->input->post('gps_google');
                $this->data['radius'] = $this->input->post('radius');
                $this->data['type'] = $this->input->post('type');
                if(is_array($this->data['type']))
                    $this->data['type'] = implode(',', $this->data['type']);
                
                $this->data['name'] = $this->input->post('name');

                if($form_import==1) {
                   // import start
                   $add_multiple = $this->input->post('add_multiple');
                   $category = $this->input->post('type_db');
                   $marker_category = $this->input->post('marker_category');

                    $preview_data=$this->import_foursquare->import($this->data['gps_google'],$this->data['radius'],$this->data['type'], $this->data['name'], false, $add_multiple, $category, $marker_category, $this->input->post('max_images'));
                    $this->data['imported']=true;
                    $this->data['preview_data']=$preview_data['data'];
                    
                    if(isset($preview_data['message']))
                        $this->data['message']=$preview_data['message'];
                    else
                        $this->data['message_successful'] = lang_check ('Import is successful');
                    
                } else {
                    // preview import start
                    $preview_data=$this->import_foursquare->import($this->data['gps_google'],$this->data['radius'],$this->data['type'], $this->data['name'], true);
                    $this->data['preview_data']=$preview_data['data'];
                    
                    if(isset($preview_data['message']))
                        $this->data['message']=$preview_data['message'];

                    // if 0 results
                    if(empty($preview_data['data'])) {
                        $this->data['error']=lang_check('0 results for import');
                    }
                    
                }
            }
            
            // Load view
            $this->data['subview'] = 'admin/estate/import_foursquare';
            $this->load->view('admin/_layout_main', $this->data);
        }
        
        function import_eventful(){
            
            if(!file_exists(APPPATH.'libraries/Eventful.php') || $this->session->userdata('type')!='ADMIN') {
                exit('Eventful modul is not installed');
            }
            
            $allowed_execution_time  = ini_get('max_execution_time')-180;
            $this->load->library('eventful', array('allowed_execution_time'=>$allowed_execution_time));
            
            $this->load->model('treefield_m');
            
            $this->data['event_categories']=$this->eventful->get_categories();
             
            $this->data['message']='';
            
            $lang_id =  $this->language_m->get_default_id();
            $this->form_validation->set_rules('option79_1', "lang: Category into import", 'trim|required');
            $this->form_validation->set_rules('event_category', "lang: Eventful categories", 'trim|required');
            $this->form_validation->set_rules('eventful_limit_page', "lang: Eventful limit page", 'trim|required');
            $this->form_validation->set_rules('eventful_offset_page', "lang: Eventful offset page", 'trim|required');
            
            
            if($this->form_validation->run()) {
                
                if($this->config->item('app_type') == 'demo')
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Data editing disabled in demo'));
                    redirect('admin/estate');
                    exit();
                }
                
                $category = $this->input->post('option79_1'); 
                $event_category = $this->input->post('event_category'); 
                
                $overwrite_existing = false;
                if($this->input->post('overwrite_existing') && $this->input->post('overwrite_existing')==1){
                   $overwrite_existing = true;
                } 
                
                /*
                $activated = false;
                if($this->input->post('activated') && $this->input->post('activated')==1){
                   $activated = true;
                } */
                $result_import = $this->eventful->start_import($overwrite_existing, $event_category, $category, TRUE, $this->input->post('eventful_limit_page'), $this->input->post('eventful_offset_page'), $this->input->post('max_images'));
                $this->data['imports']= $result_import['info'];
                $this->data['skipped']= $result_import['count_skip'];
                $this->data['count_exists_overwrite']= $result_import['count_exists_overwrite'];
                $this->data['count_exists']= $result_import['count_exists'];
                
                if(isset($result_import['message']))
                    $this->data['message']= $result_import['message'];
                
            }
            
            // Load view
            $this->data['subview'] = 'admin/estate/import_eventful';
            $this->load->view('admin/_layout_main', $this->data);
        }
        
    public function clone_listing($listing_id = NULL) 
	{
        // If limit reached, error/warning!
        $this->load->model('packages_m');
        $this->load->model('treefield_m');
        
        $user = $this->user_m->get($this->session->userdata('id'));
        if(empty($listing_id)){
            $this->session->set_flashdata('error', 
                    lang_check('Clone not create, listing missing'));
            redirect('admin/estate');
            exit();
        }
        /* fetch data */
        $estate_data = $this->estate_m->get_dynamic($listing_id);
        if(empty($estate_data)){
            $this->session->set_flashdata('error', 
                    lang_check('Clone not create, listing missing'));
            redirect('admin/estate');
            exit();
        }
        
        $_POST = (array) $estate_data;
        $this->data['estate'] = $this->estate_m->get_new();
        $id = NULL;
        /* end fetch data */
        
        if(file_exists(APPPATH.'controllers/admin/packages.php'))
        if($user->package_id > 0 && $this->session->userdata('type') == 'AGENT')
        {
            $package = $this->packages_m->get($user->package_id);
            $listing_num = $this->packages_m->get_curr_listings(array('user_id'=>$user->id));
            
            if(config_item('enable_num_amenities_listing') == true)
                $this->data['package_num_amenities_limit'] = $package->num_amenities_limit;
            
            if(isset($listing_num[$user->id]))
            {
                if($listing_num[$user->id] >= $package->num_listing_limit && !$id)
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Num listings max. reached for your package'));
                    redirect('admin/estate');
                    exit();
                }
                else if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Date for your package expired, please extend'));
                    redirect('admin/estate');
                    exit();
                }
            }
        }
       
        // Pages for dropdown
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        
        // Get available agents
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'AGENT'));
        
        $this->data['available_agent'][''] = lang_check('Current user');
        
        // Get all options
        foreach($this->option_m->languages as $key=>$val){
            $this->data['options_lang'][$key] = $this->option_m->get_lang(NULL, FALSE, $key);
        }
        $this->data['options'] = $this->option_m->get_lang(NULL, FALSE, $this->data['content_language_id']);
        
        // Id's for key adjustments 
        // TODO: better solution needed, this is just hotfix
        $options = $this->data['options'];
        $this->data['options'] = array();
        foreach($options as $option_key=>$option_row)
        {
            $this->data['options'][$option_row->option_id] = $option_row;
        }
        
        // For other langs
        foreach($this->option_m->languages as $key=>$val){
            $options_key = $this->data['options_lang'][$key];
            $this->data['options_lang'][$key] = array();
            foreach($options_key as $option_key=>$option_row)
            {
                $this->data['options_lang'][$key][$option_row->option_id] = $option_row;
            }
        }
        // End id's for key adjustments
        
        $options_data = array();
        foreach($this->option_m->get() as $key=>$val)
        {
            $options_data[$val->id][$val->type] = 'true';
        }
        
        // Add rules for dynamic options
        $rules_dynamic = array();
        foreach($this->option_m->languages as $key_lang=>$val_lang){
            foreach($this->data['options'] as $key_option=>$val_option){
                $rules_dynamic['option'.$val_option->id.'_'.$key_lang] = 
                    array('field'=>'option'.$val_option->id.'_'.$key_lang, 'label'=>$val_option->option, 'rules'=>'trim');
                //if($id == NULL)$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
                if(!isset($this->data['estate']))$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
            }
            
            if(config_db_item('slug_enabled') === TRUE)
            {
                $rules_dynamic['slug_'.$key_lang] = 
                    array('field'=>'slug_'.$key_lang, 'label'=>'lang:URI slug', 'rules'=>'trim');
            }
        }
        
        // Set up the form
        $rules = $this->estate_m->rules;
        $this->form_validation->set_rules(array_merge($rules, $rules_dynamic));
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang_check('Data editing disabled in demo'));
                redirect('admin/estate/edit/'.$id);
                exit();
            }
            
            $data = $this->estate_m->array_from_post(array('gps', 'date', 'date_modified', 'address', 'is_featured', 'is_activated', 'id_transitions'));
            $dynamic_data = $this->estate_m->array_from_post(array_keys($rules_dynamic));
            
            // AGENT_LIMITED don't have permission to change this fields...
            if($this->session->userdata('type') == 'AGENT_LIMITED')
            {
                unset($data['is_activated'],
                      $data['is_featured']
                );
                
                $data['is_activated'] = 0;
            }
            
            if(empty($data['id_transitions']))
            {
                $data['id_transitions'] = NULL;
            }
            
            $data['search_values'] = $data['address'];
            foreach($dynamic_data as $key=>$val)
            {
                $pos = strpos($key, '_');
                $option_id = substr($key, 6, $pos-6);
                $language_id = substr($key, $pos+1);
                
                if(!isset($options_data[$option_id]['TEXTAREA']) && !isset($options_data[$option_id]['CHECKBOX'])){
                    $data['search_values'].=' '.$val;
                }
                
                /* create new repository_id for UPLOAD fields */
                if(isset($options_data[$option_id]['UPLOAD'])){
                // Fetch file repository
                    $repository_id = '';
                    if(empty($repository_id))
                    {
                        // Create repository
                        $repository_id = $this->repository_m->save(array('name'=>'option_m_val'));
                    }
                    
                   $dynamic_data[$key] = $repository_id;
                }
                
                // TODO: test check, values for each language for selected checkbox
                if(isset($options_data[$option_id]['CHECKBOX'])){
                    if($val == 'true')
                    {
                        foreach($this->option_m->languages as $key_lang=>$val_lang){
                            foreach($this->data['options_lang'][$key_lang] as $key_option=>$val_option){
                                if($val_option->id == $option_id && $language_id == $key_lang)
                                {
                                    $data['search_values'].=' true'.$val_option->option;
                                }
                            }
                        }
                    }
                }
                
                if(isset($options_data[$option_id]['DATETIME']) && !empty($val)){
                    
                    if((bool)strtotime($val)) {
                        /*echo $key.'='.$val.'<br>';*/
                        $timestamp = strtotime($val);
                        $dynamic_data[$key] = date('Y-m-d H:i:s', $timestamp);;
                    }
                }
            }
            
            if($this->session->userdata('type') != 'ADMIN')
            {
                // only admin can manually change modify date
                unset($data['date_modified']);
            }
            
            if($id === NULL)
            {
                $data['date_modified'] = date('Y-m-d H:i:s');
            }
            elseif($this->data['estate']->agent != $this->session->userdata('id'))
            {
                // If admin/agent/... modify property from other user, date_modified is not changed
            }
            else
            {
                $data['date_modified'] = date('Y-m-d H:i:s');
            }
            
            /* [Auto move gps coordinates few meters away if same exists in database] */
            $estate_same_coordinates = $this->estate_m->get_by(array('gps'=>$data['gps']), TRUE);

            if(is_object($estate_same_coordinates) && !empty($estate_same_coordinates))
            {
                $same_gps = explode(', ', $estate_same_coordinates->gps);
                // $same_gps[0] && $same_gps[1] available
                $rand_lat = rand(1, 9);
                $rand_lan = rand(1, 9);
                
                $data['gps'] = ($same_gps[0]+0.0001*$rand_lat).', '.($same_gps[1]+0.0001*$rand_lan);
            }
            /* [/Auto move gps coordinates few meters away if same exists in database] */
            
            $insert_id = $this->estate_m->save($data, $id);
            
            // add insert to search_values
            if(is_numeric($insert_id))
            {
                $update_data = array();
                $update_data['search_values'] = 'id: '.$id.$data['search_values'];
                
                $this->estate_m->save($update_data, $insert_id);
            }
            
            if( $this->session->userdata('type') != 'ADMIN' && 
                $this->session->userdata('type') != 'AGENT_ADMIN' && 
                $this->session->userdata('type') != 'AGENT_COUNTY_AFFILIATE')
            {
                $data['agent'] = $this->session->userdata('id');
            }
            else
            {
                $data['agent'] = $this->input->post('agent');
            }           
            
            // Save dynamic options
            $dynamic_data['agent'] = $data['agent'];
            $this->estate_m->save_dynamic($dynamic_data, $insert_id);

            $this->load->library('sitemap');
            $this->sitemap->generate_sitemap();
            
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Clone created').'</p>');
            
            redirect('admin/estate/edit/'.$insert_id);
        }
        
        // Load the view
            $this->data['subview'] = 'admin/estate/edit';
        $this->load->view('admin/_layout_main', $this->data);
    
        }

        public function _checkavailable($field_id)
        {
            $total_count = $this->db->count_all_results('dependent_field');
            if($total_count == 0)
            {
                return TRUE;
            }
            
            $this->db->where('field_id', $field_id);
            $query = $this->db->get('dependent_field');
            
            if ($query->num_rows() > 0)
            {
                return TRUE;
            }
    
            $this->form_validation->set_message('_checkavailable', lang_check('Dependent field support only one field, please use same field or remove old dependents field then create new'));
            return FALSE;
        }

        
    function _validation_required ($str) {
        if($str == 1 && !$this->input->post('is_frontend'))
        {
            $this->form_validation->set_message('_validation_required', lang_check('Field can\'t be hidden and required in same time'));
            return FALSE;
        }
        
        return TRUE;
        
    }
    
    public function min_images($str){

            $this->load->model('file_m');
            if(empty($str)) return TRUE;
            $files = $this->file_m->get_by(array('repository_id' => $str));
            if(sw_count($files) == 0)
            {
                $this->form_validation->set_message('min_images', lang_check('Min one image should be exists, please upload some images'));
                return FALSE;
            }

            return TRUE;
    }  
    
}