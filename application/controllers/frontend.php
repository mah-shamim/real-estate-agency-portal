<?php

class Frontend extends Frontend_Controller
{

	public function __construct ()
	{
		parent::__construct();
                
	}
    
    private function _get_purpose()
    {
        
        if(isset($this->select_tab_by_title))
        if($this->select_tab_by_title != '')
        {
            $this->data['purpose_defined'] = $this->select_tab_by_title;
            return $this->select_tab_by_title;
        }
        
        if(isset($this->data['is_purpose_sale'][0]['count']))
        {
            $this->data['purpose_defined'] = lang('Sale');
            return lang('Sale');
        }
        
        if(isset($this->data['is_purpose_rent'][0]['count']))
        {
            $this->data['purpose_defined'] = lang('Rent');
            return lang('Rent');
        }
        
        if(config_item('all_results_default') === TRUE)
        {
            $this->data['purpose_defined'] = '';
            return '';
        }
        
        $this->data['purpose_defined'] = lang('Sale');
        return lang('Sale');
    }
    
    /*
     *  Function return page_offline_message if page_offline checked and message no empty
     * 
     */
    private function _page_offline()
    {
        if(isset($this->data['settings_page_offline']) && $this->data['settings_page_offline'] == '1'
            && isset($this->data['settings_page_offline_message']) && !empty($this->data['settings_page_offline_message'])    
            ) {
            
            if($this->session->userdata('type') and $this->session->userdata('type')== 'ADMIN') {
                
            }
            else {
                show_error($this->data['settings_page_offline_message'], 500, lang_check('Message'));
            }
            
        }

    }
    
    public function maskingsubmit()
    {
        $this->load->model('masking_m');
        
        //Validation
        $rules = $this->masking_m->rules_admin;
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->masking_m->array_from_post(array('visitor_type', 'name', 'phone', 
                                                            'email', 'allow_contact', 'agent_id', 'property_id'));
            
            // Save to database
            $data['date_submit'] = date('Y-m-d H:i:s');
            $this->masking_m->save($data);
            
            // Save to session
            $this->load->library('session');
            $data_sess = $data;
            $data_sess['contacted_agents'] = $this->session->userdata('contacted_agents');
            $data_sess['contacted_agents'][] = $data_sess['agent_id'];
            $this->session->set_userdata($data_sess);
            
            // Fetch agent/user email
            $agent = $this->user_m->get($data['agent_id']);
            
            if(!empty($agent->mail))
            {
                // Send email to agent/user
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
                $this->email->to($agent->mail);
                
                $this->email->subject(lang_check('Masking submission from real-estate web'));
                
                $message = $this->load->view('email/masking_submission', array('data'=>$data), TRUE);
                
                $this->email->message($message);
                if ( ! $this->email->send())
                {
                    $this->session->set_flashdata('email_sent', 'email_sent_false');
                    //echo 'problem sending email';
                }
                else
                {
                    $this->session->set_flashdata('email_sent', 'email_sent_true');
                    //echo 'successfully';
                }
            }
        
            echo 'successfully';
        }
        else
        {
            echo validation_errors();
        }

        exit();
    }
    
    public function reportsubmit()
    {
        $this->load->model('reports_m');
        
        
         /* [CAPTCHA Helper] */
        if(config_item('recaptcha_site_key') !== FALSE)
        {
            $this->config->set_item('captcha_disabled', TRUE);
        }
        
        if(config_item('captcha_disabled') === FALSE)
        {
            $this->load->helper('captcha');
            $captcha_hash = substr(md5(rand(0, 999).time()), 0, 5);
            $captcha_hash_old = $this->session->userdata('captcha_hash');
            if(isset($_POST['captcha_hash']))
                $captcha_hash_old = $_POST['captcha_hash'];
            
            $this->data['captcha_hash_old'] = $captcha_hash_old;
            $this->session->set_userdata('captcha_hash', $captcha_hash);

            $vals = array(
                'word' => substr(md5($captcha_hash.config_item('encryption_key')), 0, 5),
                'img_path' => FCPATH.'files/captcha/',
                'img_url' => base_url('files/captcha').'/',
                'font_path' => FCPATH.'admin-assets/font/verdana.ttf',
                'img_width' => 120,
                'img_height' => 35,
                'expiration' => 7200
                );

            $this->data['captcha'] = create_captcha($vals);
            $this->data['captcha_hash'] = $captcha_hash;
        }
        /* [/CAPTCHA Helper] */
        
        //Validation
        $rules = $this->reports_m->rules_agent;
        
        if($this->data['settings_template'] == 'local'){
            if($this->config->item('captcha_disabled') === FALSE)
                $rules['captcha'] = array('field'=>'captcha', 'label'=>'lang:Captcha', 
                                          'rules'=>'trim|required|callback_captcha_check|xss_clean');

            if($this->config->item('recaptcha_site_key') !== FALSE)
                $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>'lang:Recaptcha', 
                                                        'rules'=>'trim|required|callback_captcha_check|xss_clean');
        }
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->reports_m->array_from_post(array('property_id', 'agent_id', 'name', 'surname','repository_id',
                                                         'phone', 'email', 'message', 'allow_contact', 'date_submit'));
            
            // Save to database
            $data['date_submit'] = date('Y-m-d H:i:s');
            
            if(empty($data['agent_id']))
                unset($data['agent_id']);
            
            if(!$data['repository_id'])
                unset($data['repository_id']);
                
            $this->reports_m->save($data);
            // Save to session
            $this->load->library('session');
            $data_sess = $data;
            $data_sess['reported'] = $this->session->userdata('reported');
            $data_sess['reported'][] = $data_sess['property_id'];
            $this->session->set_userdata($data_sess);
            
            // Send mail
            if(!empty($this->data['settings_email']))
            {
                // Send email to agent/user
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
                $this->email->to($this->data['settings_email']);
                
                $this->email->subject(lang_check('Reporte from real-estate web'));
                
                if(isset($data['repository_id'])) {
                    $file_rep = $this->file_m->get_by(array('repository_id'=>$data['repository_id']));
                    if(sw_count($file_rep)) {
                        foreach($file_rep as $k => $file_r)
                        {
                            $data['attached_file_'.($k+1)]= '<a href="'.base_url('files/'.$file_r->filename).'">'.base_url('files/'.$file_r->filename).'</a>';
                        }
                    }
                    unset($data['repository_id']);
                }
                 
                $message = $this->load->view('email/report_submission', array('data'=>$data), TRUE);
                
                $this->email->message($message);
                if ( ! $this->email->send())
                {
                    $this->session->set_flashdata('email_sent', 'email_sent_false');
                    //echo 'problem sending email';
                }
                else
                {
                    $this->session->set_flashdata('email_sent', 'email_sent_true');
                    //echo 'successfully';
                }
            }
            
            echo 'successfully';
        }
        else
        {
            echo validation_errors();
        }

        exit();
    }
    
    private function check_login()
    {        
        $this->load->library('session');
        $this->load->model('user_m');
        
        // Login check
        if($this->user_m->loggedin() == FALSE)
        {
            redirect('frontend/login/'.$this->data['lang_code']);
        }
        else
        {
    	    $dashboard = 'admin/dashboard';
            
            if($this->session->userdata('type') == 'USER')
            {
                // LOGIN USER, OK
            }
            else
            {
                redirect($dashboard);
            }
        }
    }
      
    private function load_head_data()
    {
        /* Helpers */
        $this->data['year'] = date('Y');
        /* End helpers */
                
        /* Widgets functions */
        $this->data['print_menu'] = get_menu($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_menu_realia'] = get_menu_realia($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_lang_menu'] = get_lang_menu($this->language_m->get_array_by(array('is_frontend'=>1)), $this->data['lang_code']);
        /* End widget functions */
        
        $this->load->model('ads_m');
        $ads_act = $this->ads_m->get_by(array('is_activated'=>1));
        foreach($ads_act as $row)
        {
            $where_in[] = $row->repository_id;
        }

        // Fetch all files by repository_id
        $files = $this->file_m->get_where_in($where_in);
        $rep_file_count = array();
        $this->data['page_documents'] = array();
        $this->data['page_images'] = array();
        $this->data['page_files'] = array();
        foreach($files as $key=>$file)
        {
            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
            $file->url = base_url('files/'.$file->filename);

            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
            {
                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                $this->data['images_'.$file->repository_id][] = $file;
                
                if($this->temp_data['page']->repository_id == $file->repository_id)
                {
                    $this->data['page_images'][] = $file;
                }
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                $this->data['documents_'.$file->repository_id][] = $file;
                if($this->temp_data['page']->repository_id == $file->repository_id)
                {
                    $this->data['page_documents'][] = $file;
                }
            }
            
            $this->data['files_'.$file->repository_id][] = $file;

            if($this->temp_data['page']->repository_id == $file->repository_id)
            {
                $this->data['page_files'][] = $file;
            }
        }

        $where = array();
        $where['is_activated'] = 1;
        $where['language_id']  = $this->data['lang_id'];
        
        if(isset($this->data['settings_listing_expiry_days']))
        {
            if(is_numeric($this->data['settings_listing_expiry_days']) && $this->data['settings_listing_expiry_days'] > 0)
            {
                 $where['property.date_modified >']  = date("Y-m-d H:i:s" , time()-$this->data['settings_listing_expiry_days']*86400);
            }
        }

        /* Get all estates data */
        $limit_markers = 100;
        if(config_db_item('limit_markers') !== FALSE && is_numeric(config_db_item('limit_markers'))){
            $limit_markers = config_db_item('limit_markers');
        }
        
        $estates = $this->estate_m->get_by($where, FALSE, $limit_markers);
        //$options = $this->option_m->get_options($this->data['lang_id']);
        
        $this->data['all_estates'] = array();
        foreach($estates as $key=>$estate_obj)
        {
            $estate = array();
            $estate['id'] = $estate_obj->id;
            $estate['gps'] = $estate_obj->gps;
            $estate['address'] = htmlentities($estate_obj->address);
            $estate['date'] = $estate_obj->date;
            $estate['is_featured'] = $estate_obj->is_featured;
            
            // All estate options
            if(isset($options[$estate_obj->id]))
            foreach($options[$estate_obj->id] as $key1=>$row1)
            {                
                if(substr($row1, -2) == ' -')$row1=substr($row1, 0, -2);
                $estate['option_'.$key1] = $row1;
                $estate['option_chlimit_'.$key1] = character_limiter(strip_tags($row1), 80);
            }
            
            // Url to preview
            if(isset($options[$estate_obj->id][10]))
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$this->data['lang_code'].'/'.url_title_cro($options[$estate_obj->id][10]));
            }
            else
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$this->data['lang_code']);
            }

            // Thumbnail
            if(isset($this->data['images_'.$estate_obj->repository_id]))
            {
                $estate['thumbnail_url'] = $this->data['images_'.$estate_obj->repository_id][0]->thumbnail_url;
            }
            else
            {
                $estate['thumbnail_url'] = 'assets/img/no_image.jpg';
            }
            
            $estate['icon'] = 'assets/img/markers/'.$this->data['color_path'].'marker_blue.png';
            if(isset($estate['option_6']))
            {
                if($estate['option_6'] != '' && $estate['option_6'] != 'empty')
                {
                    if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                   '/assets/img/markers/'.$this->data['color_path'].$estate['option_6'].'.png'))
                    $estate['icon'] = 'assets/img/markers/'.$this->data['color_path'].$estate['option_6'].'.png';
                }
            }

            $this->data['all_estates'][] = $estate;
        }
        
        $this->data['all_estates_center'] = calculateCenter($estates);
        
        /* End get all estates data */
        
        $options_name = $this->option_m->get_lang(NULL, FALSE, $this->data['lang_id']);
        
        $this->data['options_name'] = array();
        $this->data['options_suffix'] = array();
        foreach($options_name as $key=>$row)
        {
            $this->data['options_name_'.$row->option_id] = $row->option;
            $this->data['options_suffix_'.$row->option_id] = $row->suffix;
            $this->data['options_prefix_'.$row->option_id] = $row->prefix;
            $this->data['options_values_'.$row->option_id] = '';
            $this->data['options_values_li_'.$row->option_id] = '';
            $this->data['options_values_arr_'.$row->option_id] = array();
            $this->data['options_values_radio_'.$row->option_id] = '';
            $this->data['options_obj_'.$row->option_id] = $row;
            
            if(sw_count(explode(',', $row->values)) > 0)
            {
                $options = '<option value="">'.$row->option.'</option>';
                $options_li = '';
                $radio_li = '';
                foreach(explode(',', $row->values) as $key2 => $val)
                {
                    $selected = '';
                    if($this->_get_purpose() == strtolower($val))$selected = 'selected';
                    if(empty($val))
                    {
                        $options='<option value="'.$val.'" '.$selected.'>'.lang_check('Any').'</option>';
                    }
                    else
                    {
                        $options.='<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                    }
                    
                    $this->data['options_values_arr_'.$row->option_id][] = $val;
                    
                    $active = '';
                    if($this->_get_purpose() == strtolower($val))$active = 'active';
                    $options_li.= '<li class="'.$active.' cat_'.$key2.'"><a href="#">'.$val.'</a></li>';
                    
                    $checked = '';
                    if($this->_get_purpose() == strtolower($val))$checked = 'checked';
                    $radio_li.='<label class="checkbox">
                                <input type="radio" rel="'.$val.'" name="search_option_'.$row->option_id.'" value="'.$key2.'" '.$checked.'> '.$val.'
                                </label>';
                }
                $this->data['options_values_'.$row->option_id] = $options;
                $this->data['options_values_li_'.$row->option_id] = $options_li;
                $this->data['options_values_radio_'.$row->option_id] = $radio_li;
            }
        }
    }
    
    public function myvisits()
    {
        $this->check_login();
        $this->load_head_data();
        
         if(file_exists(APPPATH.'controllers/admin/visits.php')) {
            $this->session->set_flashdata('error', 
                    lang_check('Visits module not install'));
            redirect('frontend');
            exit();
         }
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('My submited visits');
        $this->data['page_title'] = lang_check('My submited visits');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $this->load->model('visits_m');
	    // Fetch all estates
        $this->data['estates'] = $this->estate_m->get_join();
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
        
        // For compatibility with old template versions
        $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array(), $this->data['estates']);
        
        // filter for show only confirmed
        
        $this->data['visits'] = $this->visits_m->get_myout(NULL,0, array('date_visit >'=> date('Y-m-d H:i:s'),'date_confirmed !='=> 'NULL'));
        
        
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

        $output = $this->parser->parse($this->data['settings_template'].'/myvisits.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function myvisits_inbox()
    {
        $this->check_login();
        $this->load_head_data();
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('My submited visits');
        $this->data['page_title'] = lang_check('My submited visits');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $this->load->model('visits_m');
	    // Fetch all estates
        $this->data['estates'] = $this->estate_m->get_join();
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
        
        // For compatibility with old template versions
        $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array(), $this->data['estates']);
        
        $this->data['visits'] = $this->visits_m->get(NULL,0, array('date_visit >'=> date('Y-m-d H:i:s')));
        
        
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

        $output = $this->parser->parse($this->data['settings_template'].'/myvisits_inbox.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function edit_visit()
    {
        
        $this->check_login();
        $this->load_head_data();
        $this->load->model('visits_m');
        $this->load->model('estate_m');
        $this->load->model('user_m');
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Edit visit');
        $this->data['page_title'] = lang_check('Edit visit');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
	    // Fetch all estates
        $this->data['estates'] = $this->estate_m->get_join();
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
        
        $estates = $this->estate_m->get_by(array());
        $this->data['estates_list'] = array();
        foreach($estates as $key=>$estate)
        {
            $this->data['estates_list'][$estate->id] = $estate->id.'. '.$this->estate_m->get_field_from_listing($estate, 10);
        }
        
        $users_list = $this->user_m->get_by(array());
        $this->data['users_list'] = array();
        foreach($users_list as $key=>$user)
        {
            $this->data['users_list'][$user->id] = $user->name_surname;
        }
        
        // Fetch a user or set a new one
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
        
        if($id)
        {
            $this->data['listing'] = $this->visits_m->get($id);
            
            if(sw_count($this->data['listing']) == 0)
            {
                $this->session->set_flashdata('error', 
                        lang_check('Visit could not be found'));
                redirect('frontend/myvisits');
                exit();
            }

            if(!empty($this->data['listing']->date_confirmed))
                $this->data['listing']->confirmed = '1';
            else
                $this->data['listing']->confirmed = '';
                
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                if($this->estate_m->check_user_permission($this->data['listing']->property_id, 
                                         $this->session->userdata('id')) > 0)
                {
                }
                else
                {
                    
                    redirect('frontend/myvisits');
                }
            }
        }
        else
        {
            /*$this->data['listing'] = $this->visits_m->get_new();
            $this->data['listing']->confirmed = '';*/
            
            $this->session->set_flashdata('error', 
                    lang_check('You can only edit'));
            redirect('frontend/myvisits');
            exit();
        }
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        // Set up the form
        $rules = $this->visits_m->rules_admin;

        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Visits editing disabled in demo'));
                redirect('frontend/edit_visit/'.$this->data['lang_code'].'/'.$id);
                exit();
            }
            
            $data = $this->visits_m->array_from_post(array('property_id', 'message', 
                                                         'client_id','confirmed'));
            
            if($id == NULL)
                $data['date_created'] = date('Y-m-d H:i:s');
            
            if($data['confirmed'] == '1')
                $data['date_confirmed'] = date('Y-m-d H:i:s');
            
            unset($data['confirmed']);
            
            $insert_id=$this->visits_m->save($data, $id);
            
            if(empty($insert_id)) {
                echo 'QUERY: '.$this->db->last_query();
                echo '<br />';
                echo 'ERROR: '.$this->db->_error_message();
                exit();
            }
            
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');
            //redirect('admin/visits');
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

        $output = $this->parser->parse($this->data['settings_template'].'/edit_visit.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
        
    public function cancel_visit()
    {
        $this->check_login();
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Editing disabled in demo'));
            redirect('frontend/myvisits/'.$this->data['lang_code']);
            exit();
        }
        
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
         $this->load->model('visits_m');
        // Fetch a page or set a new one
        if($id)
        {
            $this->visits_m->cancel($id);
        }           

        redirect('frontend/myvisits_inbox/'.$this->data['lang_code']);
    }
    
    public function myproperties()
    {
        $this->check_login();
        $this->load_head_data();
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Myproperties');
        $this->data['page_title'] = lang_check('Myproperties');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        
	    // Fetch all estates
        $this->data['estates'] = $this->estate_m->get_join();
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
        
        // For compatibility with old template versions
        $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array(), $this->data['estates']);
        
        // Fetch packages
        $this->load->model('packages_m');
        
        $this->data['packages'] = $this->packages_m->get();
        $this->data['packages_days'] = $this->packages_m->get_form_dropdown('package_days');
        $this->data['packages_listings'] = $this->packages_m->get_form_dropdown('num_listing_limit');
        $this->data['packages_price'] = $this->packages_m->get_form_dropdown('package_price');
        $this->data['curr_listings'] = $this->packages_m->get_curr_listings();
        
        // Fetch attended events
        if(config_db_item('events_qr_confirmation') === TRUE)
        {
            $this->load->model('userattend_m');
            
            $user_id = $this->session->userdata('id');
    
            $this->data['attend_events'] = $this->userattend_m->get_joined(array('user_attend_listing.user_id'=>$user_id,
                                        'property_lang.language_id'=>$this->data['lang_id']));
        }
        
        
        if(isset($_POST['promocode'])) {
            $this->load->model('promocode_m');
            $promocode_item = $this->promocode_m->get_promoode($_POST['promocode']);
            if($promocode_item) {
                $error = '';
                if($promocode_item->used >= $promocode_item->quantity)
                    $error = lang_check('Voucher aready have limit');
                elseif(date('Y-m-d H:i:s') < $promocode_item->date_start)
                    $error = lang_check('Voucher is avaible will be at').' '.$promocode_item->date_start;
                elseif(date('Y-m-d H:i:s') > $promocode_item->date_end)
                    $error = lang_check('Voucher was expired at').' '.$promocode_item->date_end;
                
                if(empty($error)){
                   
                    // check if code not used
                    if(!$this->promocode_m->get_user_promocode_exist($this->session->userdata('id'), $promocode_item->id)) {
                        if($this->promocode_m->add_user_promocode($this->session->userdata('id'), $promocode_item->id)) {
                            /* key missing */
                            $this->session->set_flashdata('promocode_message', 
                                    lang_check('Voucher is added'));
                        } else {
                            /* key missing */
                            $this->session->set_flashdata('promocode_error', 
                                    lang_check('Voucher not added, please contact with administrator for check this'));
                        }
                    } else {
                        /* key activated */
                        $this->session->set_flashdata('promocode_error', 
                            lang_check('Voucher already activated'));
                    }
                } else {
                    /* key activated */
                    $this->session->set_flashdata('promocode_error', 
                        $error);
                }
            } else {
                /* key missing */
                $this->session->set_flashdata('promocode_error', 
                        lang_check('Voucher is not available'));
            }
            redirect('frontend/myproperties/'.$this->data['lang_code'].'/');
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

        $output = $this->parser->parse($this->data['settings_template'].'/myproperties.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function myreservations()
    {
        $this->check_login();
        $this->load_head_data();
        
        $this->load->model('reservations_m');
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Myreservations');
        $this->data['page_title'] = lang_check('Myreservations');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        
	    // Fetch all estates
        $this->data['estates'] = $this->reservations_m->get_by(array('user_id' => $this->session->userdata('id'), 'date_to >'=>date("Y-m-d")));
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
        
        $estates_to_fetch = array();
        foreach($this->data['estates'] as $row)
        {
            $estates_to_fetch[]['id']=$row->property_id;
        }
        
        // For compatibility with old template versions
        $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array(), $estates_to_fetch);                
        
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

        $output = $this->parser->parse($this->data['settings_template'].'/myreservations.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function myhistory()
    {
        $this->check_login();
        $this->load_head_data();
        
        $this->load->model('historyads_m');
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('My history');
        $this->data['page_title'] = lang_check('My history');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        // Fetch all estates
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
        
        $this->data['historyads'] = $this->historyads_m->get_by_check([], FALSE, 30);
        
        $estates_to_fetch = array();
        
        $where_in = [];
        foreach ($this->data['historyads'] as $key => $value) {
           $where_in[]= $value->listing_id;
        }
        
        $where = array();
        $where['language_id']  = $this->data['lang_id'];
        $this->data['properties'] = [];
        
        $estates = $this->estate_m->get_by([],FALSE, NULL, NULL, NULL, array(), $where_in);
        foreach($estates as $key=>$estate)
        {
            $this->data['properties'][$estate->id] = $estate->address;
        }
        
        // For compatibility with old template versions
        $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array(), $estates_to_fetch);                
        
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

        $output = $this->parser->parse($this->data['settings_template'].'/myhistory.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function myrates()
    {
        $this->check_login();
        $this->load_head_data();
        
        $this->load->model('rates_m');
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Myrates');
        $this->data['page_title'] = lang_check('Myrates');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        
	    // Fetch all rates
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address');
        $this->data['rates'] = $this->rates_m->get_by_check(array());
        
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
    
    public function editrate()
    {
        $this->check_login();
        $this->load_head_data();
        
        $this->load->model('rates_m');
        $this->load->model('showroom_m');
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Editrate');
        $this->data['page_title'] = lang_check('Editrate');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
                        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['rate'] = $this->rates_m->get_lang($id, FALSE, $this->data['content_language_id']);
            sw_count($this->data['rate']) || $this->data['errors'][] = 'Could not be found';
            
            if(!isset($this->data['rate']->property_id))
                redirect('frontend/myrates/'.$this->data['lang_code']);
            
            //Check if user have permissions
            $num_found = $this->estate_m->check_user_permission($this->data['rate']->property_id, $this->session->userdata('id'));
            
            if($num_found == 0)
                redirect('frontend/myrates/'.$this->data['lang_code']);
        }
        else
        {
            $this->data['rate'] = $this->rates_m->get_new();
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
                redirect('frontend/editrate/'.$this->data['lang_code'].'/'.$id);
                exit();
            }
            
            $data = $this->rates_m->array_from_post(array('date_from', 'date_to', 'min_stay', 'changeover_day', 'property_id'));
            
            $data_lang = $this->rates_m->array_from_post($this->rates_m->get_lang_post_fields());
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN')
            {
                $num_found = $this->estate_m->check_user_permission($data['property_id'], $this->session->userdata('id'));
                
                if($num_found == 0)
                    exit(lang_check('Access not allowed'));
            }
            
            $id = $this->rates_m->save_with_lang($data, $data_lang, $id);
            
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('frontend/editrate/'.$this->data['lang_code'].'/'.$id);
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
    
    public function deleterate()
    {
        $this->check_login();
        $this->load->model('rates_m');
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('frontend/myrates/'.$this->data['lang_code']);
            exit();
        }
        
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {

            $rate = $this->rates_m->get($id);
            
            if(!isset($rate->property_id))
                redirect('frontend/myrates/'.$this->data['lang_code']);
            
            $num_found = $this->estate_m->check_user_permission($rate->property_id, $this->session->userdata('id'));
            
            if($num_found == 0)
                redirect('frontend/myrates/'.$this->data['lang_code']);
           
    		$this->rates_m->delete($id);
        }           

        redirect('frontend/myrates/'.$this->data['lang_code']);
    }
    
    public function deletereservation()
    {
        $this->check_login();
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('frontend/myreservations/'.$this->data['lang_code']);
            exit();
        }
        
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->load->model('reservations_m');
            $reservation = $this->reservations_m->get($id);
            
            if(!empty($reservation))
            {
                //Check if user have permissions
                if($reservation->user_id == $this->session->userdata('id') &&
                   $reservation->is_confirmed == 0 )
                {
                    $this->reservations_m->delete($id);
                }
            }
        }           

        redirect('frontend/myreservations/'.$this->data['lang_code']);
    }
    
    public function viewreservation()
    {
        $this->load->model('reservations_m');
        
        $this->check_login();
        $this->load_head_data();
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {
    	    // Fetch all estates
            $this->data['reservation'] = $this->reservations_m->get_array_by(array('user_id' => $this->session->userdata('id'), 'id'=>$id), TRUE);
            $this->data['languages'] = $this->language_m->get_form_dropdown('language');
            $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
            
            // Fetch options only for one property
            $property_id = $this->data['reservation']['property_id'];
            $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array($property_id));
            
            // Main page data
            $this->data['page_navigation_title'] = lang_check('Reservation').' :: '.date('Y-m-d', strtotime($this->data['reservation']['date_from'])).' - '.date('Y-m-d', strtotime($this->data['reservation']['date_to']));
            $this->data['page_title'] = lang_check('Reservation').' :: '.date('Y-m-d', strtotime($this->data['reservation']['date_from'])).' - '.date('Y-m-d', strtotime($this->data['reservation']['date_to']));
            $this->data['page_body']  = '';
            $this->data['page_description']  = '';
            $this->data['page_keywords']  = '';
        }
        else
        {
            redirect('frontend/myreservations/'.$this->data['lang_code']);
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

        $output = $this->parser->parse($this->data['settings_template'].'/viewreservation.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function listproperty()
    {
        $this->check_login();
        $this->load_head_data();
        
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
        
        $output = $this->parser->parse($this->data['settings_template'].'/myproperties.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function deleteproperty()
    {
        
        $this->check_login();
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
            exit();
        }
        
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['estate'] = $this->estate_m->get_dynamic_array($id);
            
            if(sw_count($this->data['estate']) > 0)
            {
                //Check if user have permissions
                if($this->data['estate']['agent'] == $this->session->userdata('id'))
                {
                    $this->estate_m->delete($id);
                }
            }
        }           

        redirect('frontend/myproperties/'.$this->data['lang_code']);
    }
    
    public function pause_property()
    {
        
        $this->check_login();
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
            exit();
        }
        
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['estate'] = $this->estate_m->get_dynamic_array($id);
            
            if(sw_count($this->data['estate']) > 0)
            {
                //Check if user have permissions
                if($this->data['estate']['agent'] == $this->session->userdata('id'))
                {
                    $this->estate_m->change_visible_properties($id, 0);
                }
            }
        }           

        redirect('frontend/myproperties/'.$this->data['lang_code']);
    }
    
    public function unpause_property()
    {
        
        $this->check_login();
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
            exit();
        }
        
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['estate'] = $this->estate_m->get_dynamic_array($id);
            
            if(sw_count($this->data['estate']) > 0)
            {
                //Check if user have permissions
                if($this->data['estate']['agent'] == $this->session->userdata('id'))
                {
                    $this->estate_m->change_visible_properties($id, 1);
                }
            }
        }           

        redirect('frontend/myproperties/'.$this->data['lang_code']);
    }
    
    public function report_deleteproperty()
    {
        $this->check_login();
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
            exit();
        }
        
        if(empty($_POST['outcome']) || $_POST['outcome'] == 'SOLD' && empty($_POST['price']))
        {
            $this->session->set_flashdata('error', 
                    lang_check('Please define reason or price to remove'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
        }

        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['estate'] = $this->estate_m->get_dynamic_array($id);

            if(sw_count($this->data['estate']) > 0)
            {
                //Check if user have permissions
                if($this->data['estate']['agent'] == $this->session->userdata('id'))
                {
                    // Define language
                    $save_lang_id = NULL;
                    if(isset($this->language_m->db_languages_id[1]))
                    {
                        $save_lang_id = 1;
                    }
                    else
                    {
                        $save_lang_id = $this->language_m->get_default_id();
                    }
                    
                    //Save data
                    $data_report = array();
                    $data_report['lat'] = $this->data['estate']['lat'];
                    $data_report['lng'] = $this->data['estate']['lng'];
                    $data_report['address'] = $this->data['estate']['address'];
                    $data_report['outcome'] = $this->input->post('outcome');
                    $data_report['price'] = $this->input->post('price');
                    $data_report['area'] = $this->data['estate']['option57_'.$save_lang_id];
                    $data_report['date_removed'] = date('Y-m-d H:i:s');
                    $data_report['date_submited'] = $this->data['estate']['date'];
                    $data_report['purpose'] = $this->data['estate']['option4_'.$save_lang_id];
                    $data_report['type'] = $this->data['estate']['option2_'.$save_lang_id];
                    $data_report['property_json'] = json_encode($this->data['estate']);
                    $data_report['user_id_agent'] = $this->data['estate']['agent'];
                    $data_report['user_id_remover'] = $this->session->userdata('id');
                    $data_report['id_old'] = $this->data['estate']['id'];
                    
                    $this->load->model('mapreport_m');
                    $this->mapreport_m->save($data_report, NULL);
                    
                    //Delete
                    $this->estate_m->delete($id);
                }
            }
        }           

        redirect('frontend/myproperties/'.$this->data['lang_code']);
    }
    
    public function myprofile()
    {
        $this->check_login();
        $this->load_head_data();

        $this->data['content_language_id'] = $this->data['lang_id'];
        
        if($this->session->userdata('type') == 'USER')
        {
            // Load user data
            $this->data['user_data'] = $this->user_m->get_array($this->session->userdata('id'));
            
            $id = $this->data['user_data']['id'];
            //print_r($this->data['user_data']);
            
            // [Custom fields]
            custom_fields_load($this->data, $this->data['user_data']['custom_fields']);
            // [/Custom fields]
        }
        else
        {
             redirect('');
        }
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Myprofile');
        $this->data['page_title'] = lang_check('Myprofile');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';

        // Fetch all files by repository_id
        if(isset($this->data['user_data']['repository_id']))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$this->data['user_data']['repository_id']));
                foreach($files as $key=>$file)
                {
                    $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
                    $file->zoom_enabled = false;
                    $file->download_url = base_url('files/'.$file->filename);
                    $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));

                    if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
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
        
        $password_attr = '';
        if(!empty($this->data['user_data']) && isset($this->data['user_data']['is_password_locked']) && $this->data['user_data']['is_password_locked'] == 1) {
            $password_attr = 'readonly';
        }
        
        $this->data['password_attr'] = $password_attr;
        
        // Set up the form
        $rules = $this->user_m->rules_admin;
        unset($rules['type'], $rules['language']);
        
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('frontend/myprofile/'.$this->data['lang_code'].'/');
                exit();
            }
            
            $data = $this->user_m->array_from_post(array('name_surname', 'mail', 'password', 'username',
                                                         'address', 'description', 'mail', 'phone','phone2', 
                                                         'facebook_id', 'research_sms_notifications'));
            
            if($data['password'] == '')
            {
                unset($data['password']);
            }
            else
            {
                $data['password'] = $this->user_m->hash($data['password']);
            }
            
            $message_mail = '';
            if($this->data['user_data']['mail'] != $data['mail'] && config_db_item('email_activation_enabled') === TRUE)
            {
                $data['mail_verified'] = 0;
                // [START] Activation email
                
                if(ENVIRONMENT != 'development')
                if(!empty($data['mail']))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
                    $this->email->to($data['mail']);
                    
                    $this->email->subject(lang_check('Activate your account'));
                    
                    $new_hash = substr($this->user_m->hash($data['mail'].$this->data['user_data']['id']), 0, 5);
                    
                    $data_m = array();
                    $data_m['name_surname'] = $data['name_surname'];
                    $data_m['username'] = $data['username'];
                    $data_m['activation_link'] = '<a href="'.site_url('admin/user/verifyemail/'.$this->data['user_data']['id'].'/'.$new_hash).'">'.lang_check('Activate your account').'</a>';
                    $data_m['login_link'] = '<a href="'.site_url('frontend/login/').'?username='.$this->data['user_data']['username'].'#content">'.lang_check('login_link').'</a>';
                    
                    $message = $this->load->view('email/email_activation', array('data'=>$data_m), TRUE);
                    
                    $this->email->message($message);
                    if ( ! $this->email->send())
                    {
                        $message_mail = ', '.lang_check('Problem sending email to user');
                    }
                }
                // [END] Activation email
            }
            
            if($this->data['user_data']['phone'] != $data['phone'] && !empty($data['phone']) &&
               (config_db_item('clickatell_api_id') != FALSE || config_db_item('clickatell_api_key') != FALSE) && config_db_item('phone_verification_enabled') === TRUE &&
               file_exists(APPPATH.'libraries/Clickatellapi.php'))
            {
                $data['phone_verified'] = 0;
                
                //Send SMS for phone verification
                $new_hash = substr($this->user_m->hash($data['phone'].$this->data['user_data']['id']), 0, 5);
                
                $message='';
                $message.=lang_check('Your code').": \n";
                $message.=$new_hash."\n";
                $message.=lang_check('Verification link').": \n";
                $message.=site_url('admin/user/verifyphone/'.$this->data['user_data']['id'].'/'.$new_hash);
                
                $this->load->library('clickatellapi');
                $return_sms = $this->clickatellapi->send_sms($message, $data['phone']);
                
                if(substr_count($return_sms, 'successnmessage') == 0)
                {
                    $this->session->set_flashdata('error', $return_sms);
                }
            }
            
            // [Custom fields]
            custom_fields_save($data, 'custom_fields_code');
            // [/Custom fields]
            
            $this->user_m->save($data, $id);

            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').$message_mail.'</p>');
            
            redirect('frontend/myprofile/'.$this->data['lang_code'].'/');
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

        $output = $this->parser->parse($this->data['settings_template'].'/myprofile.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function editproperty()
    {
        $this->check_login();
        $this->load_head_data();

        $this->data['content_language_id'] = $this->data['lang_id'];
        $id = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
        }
        
        // If limit reached, error/warning!
        $this->load->model('packages_m');
        $this->load->model('treefield_m');
        $user = $this->user_m->get($this->session->userdata('id'));
        
        $package_mode=false;$package=NULL;
        if(file_exists(APPPATH.'controllers/admin/packages.php'))
        if($user->package_id > 0)
        {
            $package_mode=true;
            $package = $this->packages_m->get($user->package_id);
            $listing_num = $this->packages_m->get_curr_listings(array('user_id'=>$user->id));

            if(config_item('enable_num_amenities_listing') == true)
                $this->data['package_num_amenities_limit'] = $package->num_amenities_limit;
            
            /* 0 listings */
            if(empty($listing_num) && $package->num_listing_limit ==0&& !$id)
            {
                $this->session->set_flashdata('error', 
                        lang_check('Num listings max. reached for your package'));
                redirect('frontend/myproperties/'.$this->data['lang_code'].'#content');
                exit();
                
            }elseif(isset($listing_num[$user->id]))
            {
                if($listing_num[$user->id] >= $package->num_listing_limit && !$id)
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Num listings max. reached for your package'));
                    redirect('frontend/myproperties/'.$this->data['lang_code'].'#content');
                    exit();
                }
                else if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Date for your package expired, please extend'));
                    redirect('frontend/myproperties/'.$this->data['lang_code'].'#content');
                    exit();
                }
            }else if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
            {
                $this->session->set_flashdata('error', 
                        lang_check('Date for your package expired, please extend'));
                redirect('frontend/myproperties/'.$this->data['lang_code'].'#content');
                exit();
            }
        } else if($this->session->userdata('type') != 'ADMIN') {
            $this->session->set_flashdata('error', 
                                            lang_check('Please activate one package, for add new listing'));
            redirect('frontend/myproperties/'.$this->data['lang_code'].'#content');
            exit();
        }
        
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['estate'] = $this->estate_m->get_dynamic_array($id);
            
            if(sw_count($this->data['estate']) == 0)
            {
                $this->data['errors'][] = lang_check('Estate could not be found');
                redirect('frontend/myproperties/'.$this->data['lang_code'], 'refresh');
            }
            
            //Check if user have permissions
            if($this->data['estate']['agent'] == $this->session->userdata('id'))
            {
            
            }
            else
            {
                redirect('frontend/myproperties/'.$this->data['lang_code'], 'refresh');
                exit();                
            }
            
            //var_dump($this->data['estate']);
            
            // Fetch file repository
            $repository_id = $this->data['estate']['repository_id'];
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('name'=>'estate_m'));
                
                // Update page with new repository_id
                $this->estate_m->save(array('repository_id'=>$repository_id), $this->data['estate']['id']);
            }
            
            
                        
            if(config_item('plan_gallery_enabled') == TRUE){
                $planimages_repository_id = $this->data['estate']['planimages_repository_id'];
                if(empty($planimages_repository_id))
                {
                    // Create repository
                    $planimages_repository_id = $this->repository_m->save(array('name'=>'estate_m'));

                    // Update page with new repository_id
                    $this->estate_m->save(array('planimages_repository_id'=>$planimages_repository_id), $this->data['estate']['id']);
                    $this->data['estate']['planimages_repository_id'] = $planimages_repository_id;
                }
            }
        }
        else
        {            
            // Load estate data
            $this->data['estate'] = $this->estate_m->get_new_array();
        }
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Editproperty');
        $this->data['page_title'] = lang_check('Editproperty');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';

		// Pages for dropdown
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        
        // Get available agents
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'AGENT'));
        
        // Get all options
        foreach($this->option_m->languages as $key=>$val){
            $this->data['options_lang'][$key] = $this->option_m->get_lang(NULL, FALSE, $key);
        }
        $this->data['options'] = $this->option_m->get_lang_array(NULL, FALSE, $this->data['content_language_id']);
        
        /*
        // Get all options
        foreach($this->option_m->languages as $key=>$val){
            foreach($this->option_m->get_lang(NULL, FALSE, $key) as $k=>$v)
                $this->data['options_lang'][$key][$v->option_id] = $v;
        }
        
        $this->data['options'] = [];
        foreach($this->option_m->get_lang_array(NULL, FALSE, $this->data['content_language_id']) as $k=>$v)
            $this->data['options'][$v['option_id']] = $v;
        */
        $options_data = array();
        foreach($this->option_m->get() as $key=>$val)
        {
            $options_data[$val->id][$val->type] = 'true';
        }
        
        // Add rules for dynamic options
        $rules_dynamic = array();
        foreach($this->option_m->languages as $key_lang=>$val_lang){
            foreach($this->data['options'] as $key_option=>$val_option){
                $rules_dynamic['option'.$val_option['id'].'_'.$key_lang] = 
                    array('field'=>'option'.$val_option['id'].'_'.$key_lang, 'label'=>$val_option['option'], 'rules'=>'trim');
                //if($id == NULL)$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
                if(!isset($this->data['estate']))$this->data['estate']->{'option'.$val_option['id'].'_'.$key_lang} = '';
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

                if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
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
        // Fetch all files by planimages_repository_id
        if(isset($planimages_repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$planimages_repository_id));
                foreach($files as $key=>$file)
                {
                    $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
                    $file->zoom_enabled = false;
                    $file->download_url = base_url('files/'.$file->filename);
                    $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));
                    if(file_exists(FCPATH.'files/'.$file->filename))
                    {
                        $file->thumbnail_url = base_url('files/'.$file->filename);
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
        $rules['date']['rules'] = 'trim';
        unset($rules['is_featured']);
        
        if(config_db_item('terms_link') !== FALSE)
        {
            $rules['option_agree_terms']['field'] = 'option_agree_terms';
            $rules['option_agree_terms']['label'] = 'lang:option_agree_terms';
            $rules['option_agree_terms']['rules'] = 'required';
        }
        
        if(isset($_POST['repository_id']))
            $rules['repository_id'] = array('field'=>'repository_id', 'label'=>'lang:Repository', 'rules'=>'trim');
        
        /* minimum 1x images should be uploaded */ 
        if(config_item('enable_num_images_listing') == TRUE) {
            $rules['repository_id'] = array('field'=>'repository_id', 'label'=>'lang:Repository', 'rules'=>'trim|callback_limit_images_packages');
        }
        

        $this->form_validation->set_rules(array_merge($rules, $rules_dynamic));

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('frontend/editproperty/'.$this->data['lang_code'].'/'.$id);
                exit();
            }
            
            if(isset($_POST['repository_id']))
                $data = $this->estate_m->array_from_post(array('gps', 'address', 'is_visible','repository_id'));
            else {
                $data = $this->estate_m->array_from_post(array('gps', 'address', 'is_visible'));
            }
            
            $dynamic_data = $this->estate_m->array_from_post(array_keys($rules_dynamic));

            $listing_resubmited = FALSE;

            if($id == NULL)
            {
                $data['is_activated'] = 1;
                
                $data['date'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');
                
                // [removed_reports_enabled]
                if(config_item('removed_reports_enabled') === TRUE && config_db_item('address_not_required') === FALSE)
                {
                    // If property with same address already exists and is not expired, return error message
                    $gps = explode(', ', $data['gps']);
                    $lat = floatval($gps[0]);
                    $lng = floatval($gps[1]);
                    $listings_similar = $this->estate_m->get_similar($data['address'], $lat, $lng, array());
                    if($listings_similar !== NULL)
                    {
                        $this->session->set_flashdata('message', 
                                '<p class="alert alert-error">'.lang_check('Property already exists').'</p>');
                        redirect('frontend/myproperties/'.$this->data['lang_code']);
                    }
                    
                    // If property with same address already exists and is expired, remove old, create new and notify old user
                    $listings_similar = $this->estate_m->get_similar_expired($data['address'], $lat, $lng, array());
                    if($listings_similar !== NULL)
                    {
                        foreach($listings_similar as $key=>$listing)
                        {
                            // notify users
                            
                            $this->load->library('email');
                            $config_mail['mailtype'] = 'html';
                            $this->email->initialize($config_mail);
                            $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
                            $this->email->to($listing->mail);
                            
                            $this->email->subject(lang_check('The property is now listed by someone else'));
                            
                            $data_mail = array();
                            $data_mail['title'] = lang_check('The property is now listed by someone else');
                            $data_mail['property'] = (array)$listing;
                            
                            $message = $this->load->view('email/user_property_taken', $data_mail, TRUE);
                            
                            //echo $listing->mail;
                            //echo $message;
                            //exit();
                            $this->email->message($message);
                            $this->email->send();
                            
                            // remove old
                            $this->estate_m->delete($listing->id);
                        }
                    }
                    
                    // If property is recently removed and price is reduced
                    $this->load->model('removedlistings_m');
                    $listings_removed = $this->removedlistings_m->get_similar($data['address'], $lat, $lng, array());
                    
                    if($listings_removed !== NULL)
                    if(get_numeric_val($dynamic_data['option36_1']) > 0 && 
                       get_numeric_val($dynamic_data['option36_1']) <= $listings_removed[0]->price_0*0.9 )
                    {
                        // send to affilate - follow standard procedure
                        $listing_resubmited = TRUE;
                    }
                    else
                    { // Else if property is recently removed and price is not reduced
                        
                        // send to affilate, but not email users
                        $data['status'] = 'RESUBMIT';
                        $data['date_status'] = date('Y-m-d H:i:s');
                        $listing_resubmited = TRUE;
                    }
                }
                // [/removed_reports_enabled]
                
            }
            elseif(isset($this->data['estate']['is_activated']))
            {
                $data['is_activated'] = $this->data['estate']['is_activated'];
            }
            
            if( $id == NULL || 
                ( config_item('reactivation_enabled') === TRUE && !empty($this->data['settings_listing_expiry_days']) && 
                  strtotime($this->data['estate']['date_modified'])+($this->data['settings_listing_expiry_days'])*86400 < time()
                )
              )
            {
                $data['is_activated'] = 0;
                $data['sent_to_affiliate'] = 0;
            }
                
            if($package_mode === TRUE && isset($package->auto_activation))
            {
                if($package->auto_activation)
                {
                    $data['is_activated'] = 1;
                    $data['is_visible'] = 1;
                }
            }
            
            $save_as_draft = $this->input->post('save_as_draft');
            if($save_as_draft == "true")
            {
                $data['is_activated'] = 0;
                $data['sent_to_affiliate'] = 0;
            }
            
            
            $data['search_values'] = $data['address'];
            foreach($dynamic_data as $key=>$val)
            {
                $pos = strpos($key, '_');
                $option_id = substr($key, 6, $pos-6);
                
                if(!isset($options_data[$option_id]['TEXTAREA'])){
                    $data['search_values'].=' '.$val;
                }
                
                // TODO: test check, values for each language for selected checkbox
                if(isset($options_data[$option_id]['CHECKBOX'])){
                    if($options_data[$option_id]['CHECKBOX'] == 'true')
                    {
                        foreach($this->option_m->languages as $key_lang=>$val_lang){
                            foreach($this->data['options'] as $key_option=>$val_option){
                                if($val_option['id'] == $option_id)
                                {
                                    $data['search_values'].=' '.$val_option['option'];
                                }
                            }
                        }
                    }
                }
            }
            
            $email_affilate = false;
            
            if(!empty($id))
            {
                /*
                If listing is modified after (7 days before expire) then expire date will be extended to next n days
                */
                if( empty($this->data['settings_listing_expiry_days']) ||
                    strtotime($this->data['estate']['date_modified'])+($this->data['settings_listing_expiry_days']-7)*86400 < time() 
                )
                {
                    $data['date_modified'] = date('Y-m-d H:i:s');
                    
                    // repost property
                    $data['date_repost'] = date('Y-m-d H:i:s');
                }

                if($this->data['estate']['status'] == 'DECLINE')
                {
                    $data['status'] = NULL;
                    $data['date_status'] = NULL;
                }

                // renew property if 48h elapsed, move to top of listings
                if(!empty($this->data['estate']['date_renew']))
                if(time()-2*86400 > strtotime($this->data['estate']['date_renew']))
                {
                    $data['date_renew'] = date('Y-m-d H:i:s');
                    // Alert only if price is reduced
                    $price_is_reduced = TRUE;
                    $price_is_valid=TRUE;
                    // For each language 
                    foreach($this->option_m->languages as $key_lang=>$val_lang){
                        // Check if price is reduced
                        if( empty($this->data['estate']['option36_'.$key_lang]) || 
                            empty($dynamic_data['option36_'.$key_lang]) ||
                            get_numeric_val($this->data['estate']['option36_'.$key_lang])*0.9 < // old lower then
                            get_numeric_val($dynamic_data['option36_'.$key_lang]) // new
                        )
                        {
                            $price_is_reduced = FALSE;
                            break;
                        } 
                    }
                    
                    if($price_is_reduced === TRUE && !isset($_GET['date_alert']))
                    {
                        $data['status'] = 'REDUCED_PRICE';
                        $data['date_status'] = date('Y-m-d H:i:s');
                        // change time to review only 24h, not 48h
                        $email_affilate = true;
                        // First go to county affilate, not directly to emails
                        //$data['date_alert'] = date('Y-m-d H:i:s');
                    }
                        
                }
                
            }
            $insert_id = $this->estate_m->save($data, $id);
            
            if($this->session->userdata('type') != 'ADMIN')
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
            
            if(is_numeric($insert_id))
            {
                $update_data = array();
                $update_data['search_values'] = 'id: '.$id.$data['search_values'];
                
                $this->estate_m->save($update_data, $insert_id);
            }
            
            //if(isset($this->data['settings_email_alert']) && ENVIRONMENT != 'development')
            if( ( (isset($data['is_activated']) && $data['is_activated'] == 0) ||
                  (isset($this->data['estate']['is_activated']) && $this->data['estate']['is_activated'] == 0) ||
                  $email_affilate === TRUE
                )
                && $this->data['settings_email_alert'] == 1
              )
            {
                /*
                
                County affiliate: Receives notification email letting him know of new submission.
                
                */
                
                $email_activator = $this->data['settings_email'];
                $default_language_id = $this->language_m->get_content_lang();

                // Check if there is some active affiliate for specific county
                if(config_db_item('enable_county_affiliate_roles') === TRUE)
                {
                    $default_language_id = $this->language_m->get_content_lang();
                    if(isset($dynamic_data['option64_'.$default_language_id]))
                    {
                        $county_affiliate_values = $dynamic_data['option64_'.$default_language_id];

                        // get county affiliate user
                        $this->load->model('affilatepackages_m');
                        $affiliate_user = $this->affilatepackages_m->get_related_affilate($default_language_id, $county_affiliate_values);

//                        $affiliate_user = $this->user_m->get_by(array('county_affiliate_values LIKE' => $county_affiliate_values.'%',
//                                                                      'type' => 'AGENT_COUNTY_AFFILIATE',
//                                                                      'package_last_payment >' => date('Y-m-d H:i:s'),
//                                                                      'package_id >' => 0
//                                                                ), true, 1, 'package_last_payment DESC');

                        // set affiliate email
                        if(!empty($affiliate_user->mail))
                        {
                            $email_activator = $affiliate_user->mail;
                            
                            $update_data = array();
                            $update_data['sent_to_affiliate'] = 1;
                            $update_data['affilate_id'] = $affiliate_user->id;
                            $this->estate_m->save($update_data, $insert_id);
                        }
                    }
                }

                // Send email alert to contact address
                $this->load->library('email');
                
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings_noreply'], lang_check('Web page not-activated property'));
                $this->email->to($email_activator);


                $subject = lang_check('New not-activated property from user');
								
                if(isset($data['is_activated']) && $data['is_activated'] == 1)
                         $subject = lang_check('New activated property from user');
									 
                if($listing_resubmited === TRUE)
                {
                    $subject = lang_check('New not-activated property from user, RESUBMITED!');
								
                    if(isset($data['is_activated']) && $data['is_activated'] == 1)
                        $subject = lang_check('New activated property from user, RESUBMITED');
                 }
                
                $this->email->subject($subject);
                
                $data_m = array();
                $data_m['subject'] = $subject;
                $data_m['name_surname'] = $this->session->userdata('username');
                $data_m['link'] = '<a href="'.site_url('admin/estate/edit/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
                $message = $this->load->view('email/waiting_for_activation', array('data'=>$data_m), TRUE);

                $this->email->message($message);
                if ( ! $this->email->send())
                {
                    $this->session->set_flashdata('email_sent', 'email_sent_false');
                }
                else
                {
                    $this->session->set_flashdata('email_sent', 'email_sent_true');
                }
            }

            if(ENVIRONMENT != 'development')
            if(isset($data['is_activated']) && $data['is_activated'] == 0 && !empty($user->mail))
            {
                
                // Send email alert to contact address
                $this->load->library('email');
                
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings_noreply'], lang_check('Web page not-activated property'));
                $this->email->to($user->mail);
                $this->email->subject(lang_check('Web page not-activated property'));
                
                $data_m = array();
                $data_m['subject'] = lang_check('New not-activated property from user');
                $data_m['name_surname'] = $this->session->userdata('username');
                $data_m['link'] = '<a href="'. slug_url('frontend/'.$this->data['listing_uri'].'/'.$this->data['lang_code'].'/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
                $message = $this->load->view('email/waiting_for_activation_user', array('data'=>$data_m), TRUE);
                
                $this->email->message($message);
                $this->email->send();
            }
            
            if(isset($repository_id) && isset($this->data['files'][$repository_id]) && sw_count($this->data['files'][$repository_id]) > 0)
            {
                if(empty($data['is_activated']) || $data['is_activated'] == 0)
                {
                    $this->session->set_flashdata('message', 
                            '<p class="alert alert-success">'.lang_check('Property saved, waiting for approve').'</p>');
                }
                else
                {
                    $this->session->set_flashdata('message', 
                            '<p class="alert alert-success">'.lang_check('Property saved').'</p>');
                }
                
                redirect('frontend/myproperties/'.$this->data['lang_code']);
            }
            else
            {
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success"> <a href="#page-files-'.$insert_id.'">'.lang_check('Changes saved').', '.lang_check('please add some images now').'</a></p>');
                
                redirect('frontend/editproperty/'.$this->data['lang_code'].'/'.$insert_id);
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/editproperty.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function logout()
    {
        $this->user_m->logout();
        
        if($this->uri->segment(4) != 'no_redirect')
        {
            redirect($this->data['lang_code']);
        }
        else
        {
            exit('Logout without redirection');
        }
    }
    
    public function login()
    {
        if($this->user_m->loggedin() == TRUE)
        {
    	    $dashboard = 'admin/dashboard';
            
            if($this->session->userdata('type') == 'USER')
            {
                if(config_item('enable_restricted_mode') === TRUE)
                {
                    redirect($this->data['lang_code']);
                }
                else
                {
                    redirect('frontend/myproperties/'.$this->data['lang_code']);
                }
            }
            else
            {
                if(config_item('enable_restricted_mode') === TRUE)
                {
                    redirect($this->data['lang_code']);
                }
                else
                {
                    redirect($dashboard);
                }
            }
        }
        
        $this->load_head_data();
        
        $current_language =  $this->language_m->get_name($this->data['lang_code']);
        if(empty($current_language))$current_language='';
        
        
        if($this->config->item('facebook_api_version') == '2.4' || floatval($this->config->item('facebook_api_version')) >= 2.4
              || version_compare($this->config->item('facebook_api_version'), 2.4, '>') 
            )
        {
            $user_facebook = FALSE;
            if($this->config->item('appId') != '')
            {   
        		$this->load->library('facebook/Facebook'); // Automatically picks appId and secret from config
        		$user_facebook = $this->facebook->getUser();
            }

            if ($user_facebook !== FALSE) {
                try {
                    $data['user_profile'] = $user_facebook;
                    
                    if(!isset($data['user_profile']['email']))
                    {
                        $this->session->set_flashdata('error', 
                                lang_check('Email permissions on Facebook is required to login'));
                        $this->facebook->destroySession();
                        redirect('frontend/login/'.$this->data['lang_code']); 
                        exit();
                    }
    
                    // Register and login with Facebook if Facebook ID didn't exists'
                    $user_face = $this->user_m->get_by(array('password'=>$this->user_m->hash($data['user_profile']['id']), 
                                                             'username'=>$data['user_profile']['email']), true);
                    
                    if(sw_count($user_face) == 0)
                    {
                        // Check if email already exists
                        if($this->user_m->if_exists($data['user_profile']['email']) === TRUE)
                        {
                            $this->facebook->destroySession();
                            
                            $this->session->set_flashdata('error', 
                                    lang_check('Email already exists in database, please contact administrator or reset password'));
                            
                            redirect('frontend/login/'.$this->data['lang_code']); 
                            exit();
                        }
                        
                        // Register user
                        $data_f['username'] = $data['user_profile']['email'];
                        $data_f['mail'] = $data['user_profile']['email'];
                        $data_f['password'] = $this->user_m->hash($data['user_profile']['id']);
                        $data_f['facebook_id'] = $data['user_profile']['id'];
                        $data_f['type'] = 'USER';
                        $data_f['name_surname'] = $data['user_profile']['name'];
                        $data_f['activated'] = '1';
                        $data_f['description'] = '';
                        $data_f['language'] = $current_language;
                        $data_f['registration_date'] = date('Y-m-d H:i:s');
                        $data_f['mail_verified'] = 0;
                        $data_f['phone_verified'] = 0;               
                        $data_f['is_password_locked'] = 1;               
                        
                        if($this->config->item('def_package') !== FALSE)
                        {
                            $data_f['package_id'] = $this->config->item('def_package');
                            
                            $this->load->model('packages_m');
                            $package = $this->packages_m->get($data_f['package_id']);
                            
                            if(is_object($package))
                            {
                                $days_extend = $package->package_days;
                            
                                if($days_extend > 0)
                                    $data_f['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
                            }
                        }                            
                        
                        $data_r['user_id'] = $this->user_m->save($data_f, NULL);
                        
                        if(empty($data_r['user_id']))
                        {
                            echo $this->db->last_query().'<br />';
                            exit ('Facebook login failed, user not added to database');
                        }
                        
                        $facebook_id = $user_facebook['id'];
                        if(!empty($facebook_id)){
                            $this->load->model('repository_m');
                            $this->load->model('file_m');
                            $this->load->library('uploadHandler', array('initialize'=>FALSE));

                            $user_data = $this->user_m->get($data_r['user_id']);
                            // Fetch file repository
                            $repository_id = $user_data->repository_id;
                            if(empty($repository_id))
                            {
                                // Create repository
                                $repository_id = $this->repository_m->save(array('name'=>'user_m'));
                                // Update with new repository_id
                                $this->user_m->save(array('repository_id'=>$repository_id), $user_data->id);
                            }

                            $url = 'https://graph.facebook.com/'.$facebook_id.'/picture?width=1360&redirect=false';
                            
                            $handle   = curl_init($url);
                            curl_setopt($handle, CURLOPT_HEADER, false);
                            curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
                            curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
                            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 3);
                            $connectable = curl_exec($handle);
                            ##print $connectable;
                            curl_close($handle);
                            
                            if(!empty($connectable)){
                                $facebook_i_data = json_decode($connectable);

                                if(!empty($facebook_i_data) && isset($facebook_i_data->data) && isset($facebook_i_data->data->url)){
                                    $user_avatar = $facebook_i_data->data->url;
                                    $handle   = curl_init($user_avatar);
                                    curl_setopt($handle, CURLOPT_HEADER, false);
                                    curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
                                    curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
                                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
                                    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 3);
                                    $file = curl_exec($handle);
                                    ##print $connectable;
                                    curl_close($handle);

                                    $new_file_name=time().rand(000, 999).'.jpg';
                                    file_put_contents(FCPATH.'/files/'.$new_file_name, $file);
                                    /* create thumbnail */
                                    $this->uploadhandler->regenerate_versions($new_file_name);
                                    /* end create thumbnail */
                                    $file_name = $new_file_name;
                                    $file_id = $this->file_m->save(array(
                                    'repository_id' => $repository_id,
                                    'order' => 0,
                                    'filename' => $file_name,
                                    )); 
                                } 
                            }
                        }
                    } 
                    
                    // Login with facebook :: AUTO
                    if($this->user_m->login($data['user_profile']['email'], $data['user_profile']['id']) == TRUE)
                    {
                        redirect('frontend/myproperties/'.$this->data['lang_code']);
                        exit();
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 
                                lang_check('That email/password combination does not exists'));
                        redirect('frontend/login/'.$this->data['lang_code']); 
                        exit();
                    }
    
                } catch (FacebookApiException $e) {
                    $user = null;
                    echo 'facebook loading error';
                }
            }
            else if($this->config->item('appId') != ''){
                $this->facebook->logout();
            }
    
            $this->data['login_url_facebook'] = '';
            
            if ($user_facebook) {
                //echo 'logout';
                //exit();
                
                //$data['logout_url'] = site_url('facebookdemo/logout'); // Logs off application
                // OR 
                // Logs off FB!
                // $data['logout_url'] = $this->facebook->getLogoutUrl();
    
            } else if($this->config->item('appId') != ''){
                $this->data['login_url_facebook'] = $this->facebook->login_url();
            }
            
        }
        else
        {
            $user_facebook = FALSE;
            if($this->config->item('appId') != '')
            {
        		$this->load->library('facebook'); // Automatically picks appId and secret from config
        		$user_facebook = $this->facebook->getUser();
            }   
            
            if ($user_facebook) {
                try {
                    $data['user_profile'] = $this->facebook->api('/me');
                    
                    if(!isset($data['user_profile']['email']))
                    {
                        $this->session->set_flashdata('error', 
                                lang_check('Email permissions on Facebook is required to login'));
                        $this->facebook->destroySession();
                        redirect('frontend/login/'.$this->data['lang_code']); 
                        exit();
                    }
    
                    // Register and login with Facebook if Facebook ID didn't exists'
                    $user_face = $this->user_m->get_by(array('password'=>$this->user_m->hash($data['user_profile']['id']), 
                                                             'username'=>$data['user_profile']['email']), true);
                    
                    if(sw_count($user_face) == 0)
                    {
                        // Check if email already exists
                        if($this->user_m->if_exists($data['user_profile']['email']) === TRUE)
                        {
                            exit('Email already exists in database, please contact administrator or reset password');
                        }
                        
                        // Register user
                        $data_f['username'] = $data['user_profile']['email'];
                        $data_f['mail'] = $data['user_profile']['email'];
                        $data_f['password'] = $this->user_m->hash($data['user_profile']['id']);
                        $data_f['facebook_id'] = $data['user_profile']['link'];
                        $data_f['type'] = 'USER';
                        $data_f['name_surname'] = $data['user_profile']['name'];
                        $data_f['activated'] = '1';
                        $data_f['description'] = '';
                        $data_f['language'] = $current_language;
                        $data_f['registration_date'] = date('Y-m-d H:i:s');
                        $data_f['mail_verified'] = 0;
                        $data_f['phone_verified'] = 0;  
                        $data_f['is_password_locked'] = 1;  
                        
                        if($this->config->item('def_package') !== FALSE)
                        {
                            $data_f['package_id'] = $this->config->item('def_package');
                            
                            $this->load->model('packages_m');
                            $package = $this->packages_m->get($data_f['package_id']);
                            
                            if(is_object($package))
                            {
                                $days_extend = $package->package_days;
                            
                                if($days_extend > 0)
                                    $data_f['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
                            }
                        }      
                        
                        $data_r['user_id'] = $this->user_m->save($data_f, NULL);
                    } 
                    
                    // Login with facebook :: AUTO
                    if($this->user_m->login($data['user_profile']['email'], $data['user_profile']['id']) == TRUE)
                    {
                        
                        if(!empty($data_r['user_id']) && 
                            config_item('registration_interest_enabled') === TRUE && 
                            config_item('tree_field_enabled') === TRUE)
                        {
                            $user_id = $data_r['user_id'];
                            
                            redirect('fresearch/treealerts/'.$this->data['lang_code'].'/'.$user_id.'/'.md5($user_id.config_item('encryption_key')));
                        }
                        
                        redirect('frontend/myproperties/'.$this->data['lang_code']);
                        exit();
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 
                                lang_check('That email/password combination does not exists'));
                        redirect('frontend/login/'.$this->data['lang_code']); 
                        exit();
                    }
    
                } catch (FacebookApiException $e) {
                    $user = null;
                    echo 'facebook loading error';
                }
            }else if($this->config->item('appId') != ''){
                $this->facebook->destroySession();
            }
    
            $this->data['login_url_facebook'] = '';
            
            if ($user_facebook) {
                //echo 'logout';
                //exit();
                
                //$data['logout_url'] = site_url('facebookdemo/logout'); // Logs off application
                // OR 
                // Logs off FB!
                // $data['logout_url'] = $this->facebook->getLogoutUrl();
    
            } else if($this->config->item('appId') != ''){
                $this->data['login_url_facebook'] = $this->facebook->getLoginUrl(array(
                    'redirect_uri' => site_url('frontend/login/'.$this->data['lang_code']), 
                    'scope' => array("email") // permissions here
                ));
            }
        }
        
        if(file_exists(APPPATH.'controllers/admin/packages.php'))
        {
            $this->load->model('packages_m');
            $this->data['packages'] = $this->packages_m->get();
            $this->data['packages_days'] = $this->packages_m->get_form_dropdown('package_days');
            $this->data['packages_listings'] = $this->packages_m->get_form_dropdown('num_listing_limit');
            $this->data['packages_price'] = $this->packages_m->get_form_dropdown('package_price');
        }

        // Main page data
        $this->data['page_navigation_title'] = lang_check('Login');
        $this->data['page_title'] = lang_check('Login');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['is_registration'] = false;
        $this->data['is_login'] = false;

        // Set up the form for register
        if(isset($_POST['password_confirm']) || $this->session->flashdata('error_registration') != '')
        {
            if(config_db_item('property_subm_disabled')==TRUE):
                exit('Registration disabled');
            endif;
            $this->data['is_registration'] = true;
            
            $rules = $this->user_m->rules_admin;
            $rules['name_surname']['label'] = 'lang:FirstLast';
            $rules['password']['rules'] .= '|required';
            $rules['type']['rules'] = 'trim';
            $rules['language']['rules'] = 'trim';
            $rules['mail']['label'] = 'lang:Email';
            $rules['mail']['rules'] .= '|valid_email';
            
            if(config_db_item('register_reduced') === TRUE)
            {
                $rules['name_surname']['rules'] = 'trim|xss_clean';
                $rules['username']['rules'] = 'trim|xss_clean';
                
                $e_mail = $this->input->post('mail');
                if(!empty($e_mail))
                {
                    if(empty($_POST['username']))
                        $_POST['username'] = $e_mail;
                    if(empty($_POST['name_surname']))
                        $_POST['name_surname'] = $e_mail;
                }
            }
            
            if(config_item('captcha_disabled') === FALSE)
                $rules['captcha'] = array('field'=>'captcha', 'label'=>'lang:Captcha', 
                                          'rules'=>'trim|required|callback_captcha_check|xss_clean');
                                          
            if(config_item('recaptcha_site_key') !== FALSE)
                $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>'lang:Recaptcha', 
                                                        'rules'=>'trim|required|callback_captcha_check|xss_clean');
            
            $this->form_validation->set_rules($rules);

            // Process the form
            if($this->form_validation->run() == TRUE)
            {
                if($this->config->item('app_type') == 'demo')
                {
                    $this->session->set_flashdata('error_registration', 
                            lang_check('Data editing disabled in demo'));
                    redirect('frontend/login/'.$this->data['lang_code']);
                    exit();
                }
                
                $data = $this->user_m->array_from_post(array('name_surname', 'mail', 'password', 'username',
                                                             'address', 'description', 'mail', 'phone','phone2', 'type', 'language', 'activated', 'type'));
                if($data['password'] == '')
                {
                    unset($data['password']);
                }
                else
                {
                    $data['password'] = $this->user_m->hash($data['password']);
                }

                if($data['type'] == 'AGENT' && config_db_item('dropdown_register_enabled') === TRUE)
                {
                    $data['type'] = 'AGENT';
                }
                else
                {
                    $data['type'] = 'USER';
                }
                
                $data['activated'] = '1';
                if(config_db_item('email_activation_enabled') === TRUE)
                    $data['activated'] = '0';
                
                $data['description'] = '';
                $data['language'] = $current_language;
                $data['registration_date'] = date('Y-m-d H:i:s');
                $data['mail_verified'] = 0;
                $data['phone_verified'] = 0;
                
                if(empty($data['phone']))$data['phone'] = '';
                if(empty($data['phone2']))$data['phone2'] = '';
                if(empty($data['address']))$data['address'] = '';

                if($this->config->item('def_package') !== FALSE && $data['type'] == 'USER')
                {
                    $data['package_id'] = $this->config->item('def_package');
                    
                    $this->load->model('packages_m');
                    $package = $this->packages_m->get($data['package_id']);
                    
                    if(is_object($package))
                    {
                        $days_extend = $package->package_days;
                    
                        if($days_extend > 0)
                            $data['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
                    }
                }
                
                if($this->config->item('def_package_agent') !== FALSE && $data['type'] == 'AGENT')
                {
                    $data['package_id'] = $this->config->item('def_package_agent');
                    
                    $this->load->model('packages_m');
                    $package = $this->packages_m->get($data['package_id']);
                    
                    if(is_object($package))
                    {
                        $days_extend = $package->package_days;
                    
                        if($days_extend > 0)
                            $data['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
                    }
                }
                
                $user_id = $this->user_m->save($data, NULL);
                
                $message_mail = '';

                if(!empty($data['mail']) && config_db_item('email_activation_enabled') === TRUE)
                {
                    $data['mail_verified'] = 0;
                    // [START] Activation email
                    
                    //if(ENVIRONMENT != 'development')
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
                    $this->email->to($data['mail']);
                    
                    $this->email->subject(lang_check('Activate your account'));
                    
                    $new_hash = substr($this->user_m->hash($data['mail'].$user_id), 0, 5);
                    
                    $data_m = array();
                    $data_m['name_surname'] = $data['name_surname'];
                    $data_m['username'] = $data['username'];
                    $data_m['activation_link'] = '<a href="'.site_url('admin/user/verifyemail/'.$user_id.'/'.$new_hash).'">'.lang_check('Activate your account').'</a>';
                    $data_m['login_link'] = '<a href="'.site_url('frontend/login/').'?username='.$data['username'].'#content">'.lang_check('login_link').'</a>';
                    
                    $message = $this->load->view('email/email_activation', array('data'=>$data_m), TRUE);
                    
                    $this->email->message($message);
                    if ( ! $this->email->send())
                    {
                        $message_mail = ', '.lang_check('Problem sending email to user');
                    }
                    // [END] Activation email
                }

                $submit = true;
                if(!empty($data['phone']) && !empty($user_id) &&
                   (config_db_item('clickatell_api_id') != FALSE || config_db_item('clickatell_api_key') != FALSE) && config_db_item('phone_verification_enabled') === TRUE &&
                   file_exists(APPPATH.'libraries/Clickatellapi.php'))
                {
                    $data['phone_verified'] = 0;
                    
                    //Send SMS for phone verification
                    $new_hash = substr($this->user_m->hash($data['phone'].$user_id), 0, 5);
                    
                    $message='';
                    $message.=lang_check('Your code').": \n";
                    $message.=$new_hash."\n";
                    $message.=lang_check('Verification link').": \n";
                    $message.=site_url('admin/user/verifyphone/'.$user_id.'/'.$new_hash);
                    
                    $this->load->library('clickatellapi');
                    $return_sms = $this->clickatellapi->send_sms($message, $data['phone']);
                    
                    if(substr_count($return_sms, 'successnmessage') == 0)
                    {
                        // nginx causing error 502
                        $return_sms = json_decode($return_sms);
                        $this->user_m->delete($user_id);

                        $this->_clickatellapi_error = $return_sms->message;
                        $rules = array();
                        $rules['phone'] = array('field'=>'phone', 'label'=>'lang:Phone', 
                                        'rules'=>'callback__phone_clickatellapi_check');
            
                        $this->form_validation->set_rules($rules);
                        $this->form_validation->run();
                        $submit = false;                        
                    }
                }
                
                if($submit) {
                
                    if(config_db_item('email_activation_enabled') === TRUE)
                    {
                        $this->session->set_flashdata('error_registration', 
                            lang_check('Thanks on registration, please check and activate your email to login').$message_mail);
                    
                    } else if(!empty($data['phone']) && !empty($user_id) &&
                        (config_db_item('clickatell_api_id') != FALSE || config_db_item('clickatell_api_key') != FALSE) && config_db_item('phone_verification_enabled') === TRUE &&
                        file_exists(APPPATH.'libraries/Clickatellapi.php'))
                    {
                        $this->session->set_flashdata('error_registration', 
                                lang_check('Thanks on registration, we sent code for activation on your phone number, please check and activate').' '.$data['phone']);
                    }
                    else
                    {
                        $this->session->set_flashdata('error_registration', 
                                lang_check('Thanks on registration, you can login now').$message_mail);
                    }

                    if(!empty($user_id) && 
                        config_item('registration_interest_enabled') === TRUE && 
                        config_item('tree_field_enabled') === TRUE)
                    {
                        redirect('fresearch/treealerts/'.$this->data['lang_code'].'/'.$user_id.'/'.md5($user_id.config_item('encryption_key')));
                    }


                    redirect('frontend/login/'.$this->data['lang_code'], 'refresh');
                }
            }
        }
        else
        {
            $this->data['is_login'] = true;
            
    	    $dashboard = 'admin/dashboard';
                       
            // Set form
            $rules = $this->user_m->rules;
            $this->form_validation->set_rules($rules);
            
            // Process form
            if($this->form_validation->run() == TRUE)
            {
                // We can login and redirect
                if($this->user_m->login() == TRUE)
                {
                    
                    $listing_attend = $this->session->userdata('listing_attend');
                    
                    if(!empty($listing_attend))
                    {
                        // populate user_attend_listing
                        $this->load->model('userattend_m');
                        
                        $ip = $_SERVER['REMOTE_ADDR'];
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                            $ip = $_SERVER['HTTP_CLIENT_IP'];
                        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        }
                        
                        $data = array();
                        $data['user_id'] = $this->user_m->user_session_data['id'];
                        $data['listing_id'] = $listing_attend;
                        $data['date'] = date("Y-m-d H:i:s");
                        $data['ip'] = $ip;
                        
                        $this->userattend_m->save($data, NULL);
                        
                        $this->session->set_flashdata('message', 
                        '<p class="alert alert-success validation">'.lang_check('Thanks on attending this event').'</p>');
                    }
                    
                    if(file_exists(APPPATH.'controllers/admin/booking.php') && 
                       config_item('reservations_disabled') === FALSE &&
                       config_item('user_login_to_reservations') === TRUE)
                    {
                        redirect('frontend/myreservations/'.$this->data['lang_code']);
                    }
                    
                    redirect('frontend/myproperties/'.$this->data['lang_code']);
                }
                else
                {
                    if($this->user_m->is_note_activated() == TRUE)
                    {
                        $this->session->set_flashdata('error', 
                                lang_check('Account is not activated, please check your email'));
                        redirect('frontend/login/'.$this->data['lang_code']);                
                    } else{
                        $this->session->set_flashdata('error', 
                                lang_check('That email/password combination does not exists'));
                        redirect('frontend/login/'.$this->data['lang_code']);                
                    }
                }
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/login.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function do_purchase()
    {
        $this->load->model('reservations_m');
        
        $this->check_login();
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $id = NULL;
        $price_pay = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
            $price_pay = $this->uri->segment(5);
        }

	    // Fetch a page or set a new one
	    if(!empty($id) && !empty($price_pay))
        {
    	    // Fetch all estates
            $this->data['reservation'] = $this->reservations_m->get_array_by(array('user_id' => $this->session->userdata('id'), 'id'=>$id), TRUE);
            $this->data['languages'] = $this->language_m->get_form_dropdown('language');
            $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'USER'));
            
            // Fetch options only for one property
            $property_id = $this->data['reservation']['property_id'];
            $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array($property_id));
            
            // Main page data
            $this->data['page_navigation_title'] = lang_check('Reservation').' :: '.date('Y-m-d', strtotime($this->data['reservation']['date_from'])).' - '.date('Y-m-d', strtotime($this->data['reservation']['date_to']));
            $this->data['page_title'] = lang_check('Reservation').' :: '.date('Y-m-d', strtotime($this->data['reservation']['date_from'])).' - '.date('Y-m-d', strtotime($this->data['reservation']['date_to']));
            $this->data['page_body']  = '';
            $this->data['page_description']  = '';
            $this->data['page_keywords']  = '';

            /* [Payment configuration] */
            
    		$config['business'] 			= $this->data['settings_paypal_email'];
    		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
    		$config['return'] 				= site_url('frontend/myreservations/'.$this->data['lang_code']);
    		$config['cancel_return'] 		= site_url('frontend/cancel_payment/'.$this->data['lang_code']);
    		$config['notify_url'] 			= site_url('frontend/notify_payment/'.$this->data['lang_code']); //IPN Post
    		$config['production'] 			= (ENVIRONMENT == 'production'); //Its false by default and will use sandbox
    		//$config['discount_rate_cart'] 	= 0; //This means 20% discount
    		$config["invoice"]				= $this->data['reservation']['id'].'_RES_'.$price_pay;//.rand(1,10000); //The invoice id
            $config["currency_code"]        = $this->data['reservation']['currency_code'];
            
            if(empty($config['business']))
            {
                echo lang_check('PayPal email address missing');
                exit();
            }
            
    		$this->load->library('paypal', $config);
    		
    		#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
    		
    		$this->paypal->add('Reservation #'.$id, $price_pay, 1); //First item
    		//$this->paypal->add('Pants',1.99, 1); 	  //Second item
    		//$this->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
    		
    		$this->paypal->pay(); //Proccess the payment
            
            /* [/Payment configuration] */
        }
        else
        {
            redirect('frontend/myreservations/'.$this->data['lang_code']);
        }
    }
    
    public function do_package_activate(){
        $this->check_login();
        $this->load->model('packages_m');
        
        $id = NULL;
        if($this->uri->segment(4) != '' && config_item('def_package') == $this->uri->segment(4))
        {
            $id = $this->uri->segment(4);
        } 
        $this->data['package'] = array();
        
        if($id !== NULL)
            $this->data['package'] = $this->packages_m->get_array($id);
        
        if(!empty($this->data['package']))
        {
            $data_u = array();
            $data_u['package_id'] = $this->data['package']['id'];
            $days_extend = $this->data['package']['package_days'];
            
            if($days_extend > 0)
                $data_u['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
           
           $this->user_m->save($data_u, $this->session->userdata('id'));
        } 
        
        redirect('frontend/myproperties/' . $this->data['lang_code']);
    }
    
    public function do_purchase_package()
    {
        $this->load->model('packages_m');
        
        $this->check_login();
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $id = NULL;
        $price_pay = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
            $price_pay = $this->uri->segment(5);
        }
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));

	    // Fetch a page or set a new one
	    if(!empty($id) && !empty($price_pay))
        {
    	    // Fetch all estates
            $this->data['package'] = $this->packages_m->get_array($id);
            $this->data['languages'] = $this->language_m->get_form_dropdown('language');

            /* [Payment configuration] */
            
    		$config['business'] 			= $this->data['settings_paypal_email'];
    		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
    		$config['return'] 				= site_url('frontend/myproperties/'.$this->data['lang_code']);
    		$config['cancel_return'] 		= site_url('frontend/cancel_payment/'.$this->data['lang_code']);
    		$config['notify_url'] 			= site_url('frontend/notify_payment/'.$this->data['lang_code']); //IPN Post
    		$config['production'] 			= (ENVIRONMENT == 'production'); //Its false by default and will use sandbox
    		//$config['discount_rate_cart'] 	= 0; //This means 20% discount
    		$config["invoice"]				= $this->data['package']['id'].'_PAC_'.$this->data['user']['id'].'_'.$price_pay.'_'.date('w');//.rand(1,10000); //The invoice id
            $config["currency_code"]        = $this->data['package']['currency_code'];
            
            if(empty($config['business']))
            {
                echo lang_check('PayPal email address missing');
                exit();
            }

    		$this->load->library('paypal', $config);
    		
    		#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
    		
    		$this->paypal->add('Package '.$this->data['package']['package_name'].'', $price_pay, 1); //First item
    		//$this->paypal->add('Pants',1.99, 1); 	  //Second item
    		//$this->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
    		
    		$this->paypal->pay(); //Proccess the payment
            
            /* [/Payment configuration] */
        }
        else
        {
            $this->session->set_flashdata('error_package', lang_check('Something goes wrong... contact admin please.'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
        }
    }
    
    public function do_purchase_activation()
    {
        $this->check_login();
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $id = NULL;
        $price_pay = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
            $price_pay = $this->uri->segment(5);
        }
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));

	    // Fetch a page or set a new one
	    if(!empty($id) && !empty($price_pay))
        {
    	    // Fetch all estates
            $this->data['languages'] = $this->language_m->get_form_dropdown('language');

            /* [Payment configuration] */
            
    		$config['business'] 			= $this->data['settings_paypal_email'];
    		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
    		$config['return'] 				= site_url('frontend/myproperties/'.$this->data['lang_code']);
    		$config['cancel_return'] 		= site_url('frontend/cancel_payment/'.$this->data['lang_code']);
    		$config['notify_url'] 			= site_url('frontend/notify_payment/'.$this->data['lang_code']); //IPN Post
    		$config['production'] 			= (ENVIRONMENT == 'production'); //Its false by default and will use sandbox
    		//$config['discount_rate_cart'] 	= 0; //This means 20% discount
    		$config["invoice"]				= $id.'_ACT_'.$this->data['user']['id'].'_'.$price_pay.'_'.date('w');//.rand(1,10000); //The invoice id
            
            if(isset($this->data['settings_default_currency']))
            {
                $config["currency_code"] = $this->data['settings_default_currency'];
            }
            else
            {
                $config["currency_code"] = 'USD';
            }

            if(empty($config['business']))
            {
                echo lang_check('PayPal email address missing');
                exit();
            }

    		$this->load->library('paypal', $config);
    		
    		#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
    		
    		$this->paypal->add('Activation property #'.$id.'', $price_pay, 1); //First item
    		//$this->paypal->add('Pants',1.99, 1); 	  //Second item
    		//$this->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
    		
    		$this->paypal->pay(); //Proccess the payment
            
            /* [/Payment configuration] */
        }
        else
        {
            $this->session->set_flashdata('error_package', lang_check('Something goes wrong... contact admin please.'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
        }
    }

    public function do_purchase_featured()
    {
        $this->check_login();
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $id = NULL;
        $price_pay = NULL;
        if($this->uri->segment(4) != '')
        {
            $id = $this->uri->segment(4);
            $price_pay = $this->uri->segment(5);
        }
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));

	    // Fetch a page or set a new one
	    if(!empty($id) && !empty($price_pay))
        {
    	    // Fetch all estates
            $this->data['languages'] = $this->language_m->get_form_dropdown('language');

            /* [Payment configuration] */
            
    		$config['business'] 			= $this->data['settings_paypal_email'];
    		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
    		$config['return'] 				= site_url('frontend/myproperties/'.$this->data['lang_code']);
    		$config['cancel_return'] 		= site_url('frontend/cancel_payment/'.$this->data['lang_code']);
    		$config['notify_url'] 			= site_url('frontend/notify_payment/'.$this->data['lang_code']); //IPN Post
    		$config['production'] 			= (ENVIRONMENT == 'production'); //Its false by default and will use sandbox
    		//$config['discount_rate_cart'] 	= 0; //This means 20% discount
    		$config["invoice"]				= $id.'_FEA_'.$this->data['user']['id'].'_'.$price_pay.'_'.date('w');//.rand(1,10000); //The invoice id
            
            if(isset($this->data['settings_default_currency']))
            {
                $config["currency_code"] = $this->data['settings_default_currency'];
            }
            else
            {
                $config["currency_code"] = 'USD';
            }

            if(empty($config['business']))
            {
                echo lang_check('PayPal email address missing');
                exit();
            }

    		$this->load->library('paypal', $config);
    		
    		#$this->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
    		
    		$this->paypal->add('Featured property #'.$id.'', $price_pay, 1); //First item
    		//$this->paypal->add('Pants',1.99, 1); 	  //Second item
    		//$this->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
    		
    		$this->paypal->pay(); //Proccess the payment
            
            /* [/Payment configuration] */
        }
        else
        {
            $this->session->set_flashdata('error_package', lang_check('Something goes wrong... contact admin please.'));
            redirect('frontend/myproperties/'.$this->data['lang_code']);
        }
    }

    public function notify_payment()
    {
//        Array
//        (
//            [mc_gross] => 2.99
//            [invoice] => 12373469
//            [protection_eligibility] => Eligible
//            [address_status] => confirmed
//            [item_number1] => 
//            [payer_id] => ER2LXCHKVY38Q
//            [tax] => 0.00
//            [address_street] => 1 Main St
//            [payment_date] => 12:56:41 Jun 03, 2014 PDT
//            [payment_status] => Completed
//            [charset] => windows-1252
//            [address_zip] => 95131
//            [mc_shipping] => 0.00
//            [mc_handling] => 0.00
//            [first_name] => Info
//            [mc_fee] => 0.39
//            [address_country_code] => US
//            [address_name] => Info Winter
//            [notify_version] => 3.8
//            [custom] => 
//            [payer_status] => verified
//            [business] => sandi@iwinter.com.hr
//            [address_country] => United States
//            [num_cart_items] => 1
//            [mc_handling1] => 0.00
//            [address_city] => San Jose
//            [verify_sign] => AI36sk2Aln3iC.t.mla1wMizPRcQA8RKkhVgDKLdhbV.2hZelzrKnqbO
//            [payer_email] => info@iwinter.com.hr
//            [mc_shipping1] => 0.00
//            [tax1] => 0.00
//            [txn_id] => 5NJ254081K680701C
//            [payment_type] => instant
//            [last_name] => Winter
//            [address_state] => CA
//            [item_name1] => T-shirt
//            [receiver_email] => sandi@iwinter.com.hr
//            [payment_fee] => 0.39
//            [quantity1] => 1
//            [receiver_id] => S63XQYGHM4X8N
//            [txn_type] => cart
//            [mc_gross_1] => 2.99
//            [mc_currency] => USD
//            [residence_country] => US
//            [test_ipn] => 1
//            [transaction_subject] => 
//            [payment_gross] => 2.99
//            [ipn_track_id] => 9d77baf4e8f10
//        )
        
        $this->load->model('reservations_m');
        
        $received_post = $this->input->post();
        
        if(!isset($received_post['invoice']))
            exit('POST data missing');
        
        $data = array();
        $data['invoice_num'] = $received_post['invoice'];
        $data['date_paid'] = date('Y-m-d H:i:s');
        $data['data_post'] = serialize($received_post);
        $data['payer_id'] = $received_post['payer_id'];
        $data['txn_id'] = $received_post['txn_id'];
        $data['paid'] = $received_post['mc_gross'];
        $data['currency_code'] = $received_post['mc_currency'];
        $data['payer_email'] = $received_post['payer_email'];
        
        // for reservation
        $inv_ex = explode('_', $data['invoice_num']);
        if($inv_ex[1] == 'RES'){
            if(is_numeric($inv_ex[0]))
                $data['reservation_id'] = $inv_ex[0];
        }
        
        $this->load->model('payments_m');
        $this->payments_m->save($data);
        
        // update reservation

        if($inv_ex[1] == 'RES'){
            $table_id = $inv_ex[0];
            
            // Set reservations paid
            $reservation = $this->reservations_m->get_array_by(array('id'=>$table_id), TRUE);
            
            $data_r = array();
            
            if(empty($reservation['total_paid']))
                $reservation['total_paid'] = 0;
    
            $data_r['total_paid'] = $reservation['total_paid'] + $data['paid'];
            
            if($data_r['total_paid'] >= $reservation['total_price'])
            {
                $data_r['date_paid_total'] = date('Y-m-d H:i:s');
            }
            else
            {
                $data_r['date_paid_advance'] = date('Y-m-d H:i:s');
            }
            
            $data_r['is_confirmed'] = '1';
            
            $this->reservations_m->save($data_r, $table_id);
        }
        else if($inv_ex[1] == 'PAC')
        {
            $table_id = $inv_ex[2];
            $package_id = $inv_ex[0];
            
            // check if extend or buy
            $user = $this->user_m->get($table_id);
            $from_time = time();
            if(strtotime($user->package_last_payment) > $from_time)
                $from_time = strtotime($user->package_last_payment);
            
            $this->load->model('packages_m');
            $package = $this->packages_m->get($package_id);
            $days_extend = $package->package_days;
            
            // Set package paid
            $data_r = array();
            $data_r['package_last_payment'] = date('Y-m-d H:i:s', $from_time + 86400*intval($days_extend));
            $data_r['package_id'] = $package_id;
            
            $this->user_m->save($data_r, $table_id);
        }
        else if($inv_ex[1] == 'AFF')
        {
            $this->load->model('affilatepackages_m');
            
            $table_id = $inv_ex[2];
            $treefield_id = $inv_ex[0];
            
            // check if extend or buy
            $user = $this->user_m->get($table_id);
            
            $this->load->model('treefield_m');
            $treefield = $this->treefield_m->get($treefield_id);
            $days_extend = intval(($data['paid'] / $treefield->affilate_price)*30);
            
            $from_time = time();
            if(strtotime($user->package_last_payment) > $from_time)
                $from_time = strtotime($user->package_last_payment);
            
            // Reset notifications
            $data_r = array();
            $data_r['notifications_sent'] = 0;
            $this->affilatepackages_m->save($data_r, $treefield_id);
            
            // Set affilate package paid
            $aff_package = $this->affilatepackages_m->get_user_treefield($user->id, $treefield_id);
            
            $data_r = array();
            $data_r['user_id'] = $user->id;
            $data_r['treefield_id'] = $treefield_id;
            $data_r['date_last_payment'] = date('Y-m-d H:i:s');
            $data_r['date_expire'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
            $data_r['paid_total'] = $data['paid'];
            $data_r['currency'] = $data['currency_code'];
            $aff_id = NULL;
            
            if(!empty($aff_package))
            {
                // produljenje
                $data_r['date_expire'] = date('Y-m-d H:i:s', strtotime($aff_package->date_expire) + 86400*intval($days_extend)); 
                $data_r['paid_total'] = $data['paid'] + $aff_package->paid_total;
                $aff_id = $aff_package->id;
            }
            
            $this->affilatepackages_m->save($data_r, $aff_id);
            
            // Set package paid
            $data_r = array();
            $data_r['package_last_payment'] = NULL;
            $data_r['package_id'] = NULL;
            $this->user_m->save($data_r, $table_id);
        }
        else if($inv_ex[1] == 'ACT')
        {
            $table_id = $inv_ex[2];
            $property_id = $inv_ex[0];
            
            // check if extend or buy
            $this->load->model('estate_m');
            $estate = $this->estate_m->get($property_id);
            
            // Set package paid
            $data_r = array();
            $data_r['is_activated'] = '1';
            $data_r['activation_paid_date'] = date('Y-m-d H:i:s');
            
            $this->estate_m->save($data_r, $property_id);
        }
        else if($inv_ex[1] == 'FEA')
        {
            $table_id = $inv_ex[2];
            $property_id = $inv_ex[0];
            
            // check if extend or buy
            $this->load->model('estate_m');
            $estate = $this->estate_m->get($property_id);
            
            // Set package paid
            $data_r = array();
            $data_r['is_featured'] = '1';
            $data_r['featured_paid_date'] = date('Y-m-d H:i:s');
            
            $this->estate_m->save($data_r, $property_id);
        }
        
        exit();
    }
    
    public function cancel_payment()
    {
        $this->session->set_flashdata('error', 
                lang_check('Payment canceled'));
        redirect('frontend/myreservations/'.$this->data['lang_code']);    
    }
    
    public function login_book()
    {
        if($this->user_m->loggedin() == TRUE)
        {
    	    $dashboard = 'admin/dashboard';
            
            if($this->session->userdata('type') == 'USER')
            {
                redirect('frontend/myproperties/'.$this->data['lang_code']);
            }
            else
            {
                redirect($dashboard);
            }
        }
        
        $data_r = unserialize($this->session->flashdata('data_r'));
        
        if(!isset($data_r['date_from']))
            redirect('');
        
        $this->session->keep_flashdata('data_r');
        
        $this->data['reservation'] = $data_r;
        
        $data_contact_r = unserialize($this->session->flashdata('data_contact_r'));
        $this->session->keep_flashdata('data_contact_r');
        $this->data['data_contact_r'] = $data_contact_r;
        
        $this->load_head_data();
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        
        // Fetch options only for one property
        $property_id = $this->data['reservation']['property_id'];
        $this->data['options'] = $this->option_m->get_options($this->data['content_language_id'], array(), array($property_id));
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Register and book online');
        $this->data['page_title'] = lang_check('Register and book online');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';

        // Set up the form for register and book online
        
        $rules = $this->user_m->rules_admin;
        $rules['name_surname']['label'] = 'lang:FirstLast';
        $rules['password']['rules'] .= '|required';
        $rules['type']['rules'] = 'trim';
        $rules['language']['rules'] = 'trim';
        $rules['mail']['label'] = 'lang:Email';
        $rules['mail']['rules'] .= '|valid_email|is_unique[user.mail]';
        
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error_registration', 
                        lang_check('Data editing disabled in demo'));
                redirect('frontend/login/'.$this->data['lang_code']);
                exit();
            }
            
            // Register and book
            
            $data = $this->user_m->array_from_post(array('name_surname', 'mail', 'password', 'username',
                                                         'address', 'description', 'mail', 'phone', 'type', 'language', 'activated'));
            if($data['password'] == '')
            {
                unset($data['password']);
            }
            else
            {
                $data['password'] = $this->user_m->hash($data['password']);
            }
            
            $data['type'] = 'USER';
            $data['activated'] = '1';
            $data['description'] = '';
            $data['language'] = '';
            $data['registration_date'] = date('Y-m-d H:i:s');
            
            if($this->config->item('def_package') !== FALSE)
                $data['package_id'] = $this->config->item('def_package');
            
            $data_r['user_id'] = $this->user_m->save($data, NULL);
            
            // save reservation
            $this->load->model('reservations_m');
            $reservation_id = $this->reservations_m->save($data_r, NULL);

            // auto login
            if($this->user_m->login() == TRUE)
            {
                // view reservation
                $this->session->set_flashdata('error_registration', 
                        lang_check('Thanks on registration, you can book now'));
                
                redirect('frontend/viewreservation/'.$this->data['lang_code'].'/'.$reservation_id, 'refresh');
            }
            else
            {
                $this->session->set_flashdata('error', 
                        lang_check('That email/password combination does not exists'));
                redirect('frontend/login/'.$this->data['lang_code']); 
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/login_book.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }

    private function _custom_search_filtering(&$res_array, $options, $post_option)
    {
        foreach($res_array as $key=>$row)
        {
            foreach($post_option as $key1=>$val1)
            {
                if(is_numeric($val1) && $key1 != 'smart')
                {
                    $option_num = $key1;

                    if(strrpos($option_num, 'from') > 0)
                    {
                        $option_num = substr($option_num,0,-5);
                        
                        // For rentable
                        if($option_num == 36 && isset($this->data['is_purpose_rent'][0]['count']))
                            $option_num++;
                        
                        if(!isset($this->data['is_purpose_rent'][0]['count']) &&
                                !isset($this->data['is_purpose_sale'][0]['count']))
                        {
                            if( ($options[$row['id']][$option_num] < $val1 || empty($options[$row['id']][$option_num])) && 
                                ($options[$row['id']][$option_num+1] < $val1 || empty($options[$row['id']][$option_num+1]))  )
                            {
                                unset($res_array[$key]);
                            }
                        }
                        else if(!isset($options[$row['id']][$option_num]))
                        {
                            unset($res_array[$key]);
                        }
                        else if($options[$row['id']][$option_num] < $val1)
                        {
                            unset($res_array[$key]);
                        }
                    }
                    else if(strrpos($option_num, 'to') > 0)
                    {
                        $option_num = substr($option_num,0,-3);
                        
                        // For rentable
                        if($option_num == 36 && isset($this->data['is_purpose_rent'][0]['count']))
                            $option_num++;
                        
                        if(!isset($this->data['is_purpose_rent'][0]['count']) &&
                                !isset($this->data['is_purpose_sale'][0]['count']))
                        {
                            if(!isset($options[$row['id']][$option_num]))
                            {
                                unset($res_array[$key]);
                            }
                            else
                            {
//                                echo $val1."\r\n";
//                                echo $options[$row['id']][$option_num]."\r\n";
//                                echo $options[$row['id']][$option_num+1]."\r\n";
                                
                                if( ($options[$row['id']][$option_num] > $val1 || empty($options[$row['id']][$option_num])) && 
                                    ($options[$row['id']][$option_num+1] > $val1 || empty($options[$row['id']][$option_num+1]) || $row['id'] != 36 )  )
                                {
                                    unset($res_array[$key]);
//                                    echo "unset\r\n";
                                }
                            }
                        }
                        else if(!isset($options[$row['id']][$option_num]) || empty($options[$row['id']][$option_num]))
                        {
                            unset($res_array[$key]);
                        }
                        else if($options[$row['id']][$option_num] > $val1)
                        {
                            unset($res_array[$key]);
                        }
                    }
                    else
                    {
                        if(!isset($options[$row['id']][$option_num]))
                        {
                            unset($res_array[$key]);
                        }
                        else if($options[$row['id']][$option_num] != $val1)
                        {
                            unset($res_array[$key]);
                        }
                    }
                }
            }
        }
    }
    
    public function typeahead ()
    {
        $q = $this->input->post('q');
        $limit = $this->input->post('limit');
        $option_id = (string) $this->uri->segment(5);
        $option_ids = array(5,7,40);
        $language_id = $this->data['lang_id'];
        
        if(is_array(config_item('additional_typeahead_ids')))
        {
            $option_ids = array_merge($option_ids, config_item('additional_typeahead_ids'));
        }
        
        if($option_id != 'smart')
        {
            $option_ids = array(intval($option_id));
        }
        
        if($limit == '')
        {
            $limit = 8;
        }
        
        if(empty($q))
        {
            echo json_encode(array());
            exit();
        }

        $results = $this->option_m->get_typeahead($q, $limit, $option_ids, $language_id);
        
        $this->output->enable_profiler(FALSE);
        echo json_encode($results);
        //echo '["Electric Light Orchestra", "Elvis Costello", "Eric Clapton"]';
        //exit();
    }

    public function ajax ($page_id)
    {
        // Prevent direct access for google and simmilar
        if(!isset($_POST['page_num']))
            redirect('');

        $this->load->model('treefield_m');
        $lang_id = $this->data['lang_id'];        
        
        // Fetch post values
        $address = $this->input->post('address');
        $order = $this->input->post('order');
        $view = $this->input->post('view');

        $post_option = array();
        $post_option_sum = ' ';
        foreach($_POST as $key=>$val)
        {
            $tmp_post = $this->input->post($key);
            if(!empty($tmp_post) && strrpos($key, 'tion_') > 0){
                $post_option[$key] = $tmp_post;
                if(is_string($val) || is_int($val))
                    $post_option_sum.=$tmp_post.' ';
            }
            
            if(is_array($tmp_post))
            {
                $category_num = substr($key, strrpos($key, 'gory_')+5);
                
                foreach($tmp_post as $key=>$val)
                {
                    $post_option['0'.$category_num.'9999'.$key] = $val;
                    $post_option_sum.=$val.' ';
                }
            }
        }
        // End fetch post values     

        /* Define order */
        if(empty($order))$order='id DESC';

        $this->data['order_dateASC_selected'] = '';
        if($order=='id ASC')
            $this->data['order_dateASC_selected'] = 'selected';
            
        $this->data['order_dateDESC_selected'] = '';
        if($order=='id DESC')
            $this->data['order_dateDESC_selected'] = 'selected';
            
        $this->data['order_priceASC_selected'] = '';
        if($order=='price ASC')
            $this->data['order_priceASC_selected'] = 'selected';
            
        $this->data['order_priceDESC_selected'] = '';
        if($order=='price DESC')
            $this->data['order_priceDESC_selected'] = 'selected';

        $this->data['order_livingarea_selected'] = '';
        if($order=='livingArea')
            $this->data['order_livingarea_selected'] = 'selected';
            
        $this->data['order_viewsASC_selected'] = '';
        if($order=='counter_views ASC')
            $this->data['order_viewsASC_selected'] = 'selected';
            
        $this->data['order_viewsDESC_selected'] = '';
        if($order=='counter_views DESC')
            $this->data['order_viewsDESC_selected'] = 'selected';
        /* End define order */
        
        /* Define view */
        if(empty($view))$view='grid';
        
        $this->data['view_grid_selected'] = '';
        $this->data['has_view_grid'] = array();
        if($view=='grid')
        {
            $this->data['view_grid_selected'] = 'active';
            $this->data['has_view_grid'][] = array('view' => 'grid');
        }
        
        $this->data['view_list_selected'] = '';
        $this->data['has_view_list'] = array();
        if($view=='list')
        {
            $this->data['view_list_selected'] = 'active';
            $this->data['has_view_list'][] = array('view' => 'list');
        }
        /* End define view */  
        
        /* Define purpose */
        $this->data['is_purpose_rent'] = array();
        $this->data['is_purpose_sale'] = array();

        if(strpos($post_option_sum, lang_check('Rent')) !== FALSE)
        {
            $this->data['is_purpose_rent'][] = array('count'=>'1');
            $order = str_replace("price", "field_37_int", $order);
        }
        if(strpos($post_option_sum, lang_check('Sale')) !== FALSE)
        {
            $this->data['is_purpose_sale'][] = array('count'=>'1');
        }
        
        $order = str_replace("price", "field_36_int", $order);
        
        // Special situation for properties available for rent
        if(sw_count($this->data['is_purpose_rent']) > 0)
        {
            if(isset($post_option['v_search_option_36_from']))
                $post_option['v_search_option_37_from'] = $post_option['v_search_option_36_from'];
            if(isset($post_option['v_search_option_36_to']))
                $post_option['v_search_option_37_to'] = $post_option['v_search_option_36_to'];
            unset($post_option['v_search_option_36_from'], $post_option['v_search_option_36_to']);
        }
        
        /* End define purpose */
        
        /* Pagination configuration */ 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '<ul>';
        
        $config['base_url'] = $this->data['ajax_load_url'];
        $config['total_rows'] = 200;
        $config['per_page'] = config_item('per_page');
        $config['uri_segment'] = 5;
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        /* End Pagination */
        
        //$options = $this->option_m->get_options($lang_id);
        $options_name = $this->option_m->get_lang(NULL, FALSE, $lang_id);
        
        /* [Booking check availability] */
        $available_properties = NULL;
        if(file_exists(APPPATH.'controllers/admin/booking.php'))
        {
            $this->load->model('reservations_m');
            
            $booking_date_from = $this->input->post('v_booking_date_from');
            $booking_date_to = $this->input->post('v_booking_date_to');
            
            if(!empty($booking_date_from) && !empty($booking_date_to))
            {
                $available_properties = $this->reservations_m->get_available_properties($booking_date_from, $booking_date_to);
            }
        }
        /* [/Booking check availability] */

        /* Search */
        $offset_properties = $this->data['pagination_offset'];
        
        $search_array = $post_option;
        
        $where = array();
        $where['is_activated'] = 1;
        $where['is_visible'] = 1;
        $where['language_id']  = $lang_id;
        
        if(isset($this->data['settings_listing_expiry_days']))
        {
            if(is_numeric($this->data['settings_listing_expiry_days']) && $this->data['settings_listing_expiry_days'] > 0)
            {
                 $where['property.date_modified >']  = date("Y-m-d H:i:s" , time()-$this->data['settings_listing_expiry_days']*86400);
            }
        }

        $data = array();
        
        $this->data['options_name'] = array();
        $this->data['options_suffix'] = array();
        foreach($options_name as $key=>$row)
        {
            $this->data['options_name_'.$row->option_id] = $row->option;
            $this->data['options_suffix_'.$row->option_id] = $row->suffix;
            $this->data['options_prefix_'.$row->option_id] = $row->prefix;
            $this->data['options_obj_'.$row->option_id] = $row;
            $this->data['options_values_arr_'.$row->option_id] = explode(',',$row->values);
        }
        
        // Fetch all files by repository_id
        $files = array();
        
        /* End fetch files */

        /* Get estates for map data */
        $limit_markers = 100;
        if(config_db_item('limit_markers') !== FALSE && is_numeric(config_db_item('limit_markers'))){
            $limit_markers = config_db_item('limit_markers');
        }
        
        $featured_order = '';
        if(config_db_item('featured_on_top_disabled') == FALSE ){
            $featured_order = 'property.is_featured DESC, ';
        }
        
        $results_obj = $this->estate_m->get_by($where, false, $limit_markers, $featured_order.$order, 
                                               NULL, $search_array, $available_properties);
        
        //echo $this->db->last_query();
        
        $this->data['has_no_results'] = array();
        if(sw_count($results_obj) == 0)
            $this->data['has_no_results'][] = array('count'=>sw_count($results_obj));
        
        /* Get all estates data for json */
        $this->data['results_json'] = array();
        foreach($results_obj as $key=>$estate_obj)
        {
            $estate = array();
            $estate['id'] = $estate_obj->id;
            $estate['gps'] = $estate_obj->gps;
            $estate['address'] = $estate_obj->address;
            $estate['date'] = $estate_obj->date;
            $estate['repository_id'] = $estate_obj->repository_id;
            $estate['is_featured'] = $estate_obj->is_featured;
            
            $json_obj = json_decode($estate_obj->json_object);
            foreach($options_name as $key2=>$row2)
            {
                $key1 = $row2->option_id;
                $estate['has_option_'.$key1] = array();
                
                if(isset($json_obj->{"field_$key1"}))
                {
                    $row1 = $json_obj->{"field_$key1"};
                    if(substr($row1, -2) == ' -')$row1=substr($row1, 0, -2);
                    $estate['option_'.$key1] = $row1;
                    $estate['option_chlimit_'.$key1] = character_limiter(strip_tags($row1), 80);
                    $estate['option_icon_'.$key1] = '';

                    if(!empty($row1))
                    {
                        $estate['has_option_'.$key1][] = array('count'=>sw_count($row1));
                        
                        if($this->data['options_obj_'.$key1]->type == 'CHECKBOX' || $this->data['options_obj_'.$key1]->type == 'INPUTBOX')
                        if(!empty($this->data['options_obj_'.$key1]->image_filename))
                        {
                            $estate['option_icon_'.$key1] = '<img class="results-icon" src="'.base_url('files/'.$this->data['options_obj_'.$key1]->image_filename).'" alt="'.$row1.'"/>';;
                            $estate['icons'][]['icon']= $estate['option_icon_'.$key1];
                        }
                        elseif(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                            '/assets/img/icons/option_id/'.$key1.'.png'))
                        {
                            $estate['option_icon_'.$key1] = '<img class="results-icon" src="assets/img/icons/option_id/'.$key1.'.png" alt="'.$row1.'"/>';;
                            $estate['icons'][]['icon']= $estate['option_icon_'.$key1];
                        }
                    }
                }
            }
            
            // [START] custom price field
            $estate['custom_price'] = '';
            if(!empty($estate_obj->field_36))
                $estate['custom_price'].=$this->data['options_prefix_36'].$estate_obj->field_36.$this->data['options_suffix_36'];
            if(!empty($estate_obj->field_37))
            {
                if(!empty($estate['custom_price']))
                    $estate['custom_price'].=' / ';
                $estate['custom_price'].=$this->data['options_prefix_37'].$estate_obj->field_37.$this->data['options_suffix_37'];
            }
                
            if(empty($estate_obj->field_37) && !empty($estate_obj->field_56))
            {
                if(!empty($estate['custom_price']))
                    $estate['custom_price'].=' / ';
                $estate['custom_price'].=$this->data['options_prefix_56'].$estate_obj->field_56.$this->data['options_suffix_56'];
            }
            // [END] custom price field

            $estate['icon'] = 'assets/img/markers/'.$this->data['color_path'].'marker_blue.png';
            if(isset($json_obj->field_6))
            {
                if($json_obj->field_6 != '' && $json_obj->field_6 != 'empty')
                {
                    // if uploaded
                    $uloaded_set = false;
                    if(!empty($this->data['options_obj_6']->image_gallery))
                    {
                        $gallery_images = explode(',', $this->data['options_obj_6']->image_gallery);
                        $value_index = array_search($json_obj->field_6, $this->data['options_values_arr_6']);
                        if(isset($gallery_images[$value_index]) && !empty($gallery_images[$value_index]))
                        {
                            $uloaded_set=true;
                            $estate['icon'] = base_url('files/'.$gallery_images[$value_index]);
                        }
                    }
                    
                    if(!$uloaded_set)
                    if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                   '/assets/img/markers/'.$this->data['color_path'].$json_obj->field_6.'.png'))
                    {
                        $estate['icon'] = 'assets/img/markers/'.$this->data['color_path'].$json_obj->field_6.'.png';
                    }
                    elseif (file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                   '/assets/img/markers/'.$estate['option_6'].'.png'))
                    {
                        $estate['icon'] = 'assets/img/markers/'.$estate['option_6'].'.png';
                    }
                }
            }
            
            // [fetch marker by type uploaded image]
            
            // Check if images are uploaded
            if(config_db_item('field_file_upload_enabled') === TRUE && !empty($this->data['options_obj_2']->image_gallery))
            {
                // Get selected type index
                $image_index = array_search($estate['option_2'], $this->data['options_values_arr_2']);
                
                // Explode images
                $images = explode(',', $this->data['options_obj_2']->image_gallery);

                // set if image exists
                if(!empty($images[$image_index]))
                {
                    $image_filename = 'files/'.$images[$image_index];
                    
                    if(file_exists(FCPATH.$image_filename))
                        $estate['icon'] = base_url($image_filename);
                }
            }

            // [/fetch marker by type uploaded image]
            
            // Url to preview
            if(isset($json_obj->field_10))
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$this->data['lang_code'].'/'.url_title_cro($json_obj->field_10));
            }
            else
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$this->data['lang_code']);
            }
            
            // Thumbnail
            if(isset($estate_obj->image_filename) and file_exists(FCPATH.'files/thumbnail/'.$estate_obj->image_filename))
            {
                $estate['thumbnail_url'] = base_url('files/thumbnail/'.$estate_obj->image_filename);
            }
            else
            {
                $estate['thumbnail_url'] = base_url('templates/'.$this->data['settings_template']).'/assets/img/no_image.jpg';
            }
            
            $estate_obj_gen = new StdClass;
            $estate_obj_options = new StdClass;
            $estate_obj_options->icon = base_url('templates/'.$this->data['settings_template']).'/'.$estate['icon'];
            
            if(strpos($estate['icon'], 'files') !== FALSE)
            {
                $estate_obj_options->icon = $estate['icon'];
            }
            
            if(!empty($json_obj->field_6))
            {
                $estate_obj_options->cssclass = $json_obj->field_6;
            }
            else
            {
                $estate_obj_options->cssclass = 'not-defined';
            }

            $gps_coo = explode(', ', $estate_obj->gps);
            if(sw_count($gps_coo) == 2)$estate_obj_gen->latLng = array(floatval($gps_coo[0]), floatval($gps_coo[1]));
            $estate_obj_gen->options = $estate_obj_options;
            if(!isset($estate['option_2']))$estate['option_2'] = '{option_2}';
            if(!isset($estate['option_4']))$estate['option_4'] = '{option_4}';
            
            $estate_obj_gen->data = _generate_popup(array_merge($estate, $this->data));
            
            if(empty($estate_obj_gen->data))
            {
                if($this->data['settings_template'] != 'realia')
                $estate_obj_gen->data = "<img style=\"width: 150px; height: 100px;\" src=\"".$estate['thumbnail_url']."\" /><br />".
                                    $estate_obj->address."<br />".$estate['option_2']."<br /><span class=\"label label-info\">&nbsp;&nbsp;".$estate['option_4']."&nbsp;&nbsp;</span>".
                                    "<br /><a href=\"".$estate['url']."\">".lang('Details')."</a>";
                if($this->data['settings_template'] == 'realia')
                $estate_obj_gen->data_realia = "<div class=\"image\"><img style=\"width: 100px; height: 74px;\" src=\"".$estate['thumbnail_url']."\" alt=\"\"></div><div class=\"title\"><a href=\"".$estate['url']."\">".$estate['option_10']."</a></div><div class=\"area\"><span class=\"key\">".$this->data['options_name_3'].":</span><span class=\"value\">".$estate['option_3'].$this->data['options_suffix_3']."</span></div>".$estate['option_2']."&nbsp;&nbsp;<span class=\"label label-info\">&nbsp;&nbsp;".$estate['option_4']."&nbsp;&nbsp;</span><div class=\"price\">".
                                           (!empty($estate['option_36'])?$this->data['options_prefix_36']." ".$estate['option_36']." ".$this->data['options_suffix_36']:'').(!empty($estate['option_37'])?$this->data['options_prefix_36']." ".$estate['option_37']." ".$this->data['options_suffix_36']:'').
                                           "</div><div class=\"link\"><a href=\"".$estate['url']."\">".lang('Details')."</a></div>";
            }

            $estate_obj_gen->adr = $estate_obj->address;
            
            $this->data['results_json'][] = $estate_obj_gen;
        }
        
        $results_center = calculateCenterArray($results_obj);

        /* Get all estates data */
        $config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.is_featured DESC, '.$order, 
                                               NULL, $search_array, $available_properties);
        
        /* Pagination in query */
        $this->data['total_rows'] = $config['total_rows'];
        
        $featured_order = '';
        if(config_db_item('featured_on_top_disabled') == FALSE ){
            $featured_order = 'property.is_featured DESC, ';
        }
        
        $estates = $this->estate_m->get_by($where, false, $config['per_page'], $featured_order.$order, 
                                               $offset_properties, $search_array, $available_properties);
        $query = $this->db->last_query();
        $this->data['results'] = array();
        $this->generate_results_array($estates, $this->data['results'], $options_name); 
        
        /* Pagination load */ 
        $this->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->pagination->create_links();
        /* End Pagination */
        
        $output = $this->parser->parse($this->data['settings_template'].'/results.php', $this->data, TRUE);
        $output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
        if(function_exists('mb_convert_encoding'))
            $output = mb_convert_encoding($output, "UTF-8", "auto");
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        echo json_encode(array('results'=>$this->data['results_json'], 'results_center'=>$results_center, 'print' => $output, 'order'=>$order, 'lang_id'=>$lang_id, 'total_rows'=>$config['total_rows']),JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    
	public function index ()
	{
        $lang_id = $this->data['lang_id'];
        
        $this->_page_offline();
        // Fetch all files by repository_id
        
        $this->data['page_documents'] = array();
        $this->data['page_images'] = array();
        $this->data['page_files'] = array();
        
        $where_in = array($this->temp_data['page']->repository_id);
        
        $this->load->model('ads_m');
        $ads_act = $this->ads_m->get_by(array('is_activated'=>1));
        foreach($ads_act as $row)
        {
            $where_in[] = $row->repository_id;
        }

        // Fetch all files by repository_id
        $files = $this->file_m->get_where_in($where_in);
        $rep_file_count = array();
        $this->data['page_documents'] = array();
        $this->data['page_images'] = array();
        $this->data['page_files'] = array();
        foreach($files as $key=>$file)
        {
            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
            $file->url = base_url('files/'.$file->filename);

            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
            {
                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                $this->data['images_'.$file->repository_id][] = $file;
                
                if($this->temp_data['page']->repository_id == $file->repository_id)
                {
                    $this->data['page_images'][] = $file;
                }
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                $this->data['documents_'.$file->repository_id][] = $file;
                if($this->temp_data['page']->repository_id == $file->repository_id)
                {
                    $this->data['page_documents'][] = $file;
                }
            }
            
            $this->data['files_'.$file->repository_id][] = $file;

            if($this->temp_data['page']->repository_id == $file->repository_id)
            {
                $this->data['page_files'][] = $file;
            }
        }
        
        // Has attributes
        $this->data['has_page_documents'] = array();
        if(sw_count($this->data['page_documents']))
            $this->data['has_page_documents'][] = array('count'=>sw_count($this->data['page_documents']));
        
        $this->data['has_page_images'] = array();
        if(sw_count($this->data['page_images']))
            $this->data['has_page_images'][] = array('count'=>sw_count($this->data['page_images']));
            
        $this->data['has_page_files'] = array();
        if(sw_count($this->data['page_files']))
            $this->data['has_page_files'][] = array('count'=>sw_count($this->data['page_files']));
        /* End fetch files */

        /* Helpers */
        $this->data['year'] = date('Y');
        /* End helpers */

        /* Widgets functions */
        $this->data['print_menu'] = get_menu($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_menu_realia'] = get_menu_realia($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_lang_menu'] = get_lang_menu($this->language_m->get_array_by(array('is_frontend'=>1)), $this->data['lang_code']);
        $this->data['page_template'] = $this->temp_data['page']->template;
        /* End widget functions */

        // [JSON_SEARCH]
        // Example: ?search={"search_option_smart": "zagreb"}
        $search_json = NULL;
        if(isset($_GET['search']))$search_json = json_decode($this->input->get_post('search', TRUE));
        
        if($search_json !== FALSE && $search_json !== NULL)
        {
            $post_option = array();
            $post_option_sum = ' ';
            
            if(is_array($search_json) || is_object($search_json))
            {
                foreach($search_json as $key=>$val)
                {
                    //$tmp_post = htmlentities($val);
                    $tmp_post = sw_xss_clean($val);
                    if(!empty($tmp_post) && strrpos($key, 'tion_') > 0 && !is_array($tmp_post)){
                        $post_option[substr($key, strrpos($key, 'tion_')+5)] = $tmp_post;
                        $post_option_sum.=$tmp_post.' ';
                    }
                    
                    if(is_array($tmp_post))
                    {
                        $category_num = substr($key, strrpos($key, 'gory_')+5);
                        
                        foreach($tmp_post as $k=>$v)
                        {
                            $post_option[substr($key, strrpos($key, 'tion_')+5)][] = $v;
                            //$post_option['0'.$category_num.'9999'.$key] = $val;
                            $post_option_sum.=$v.' ';
                        }
                    }
                }
            }
            else
            {
            }


            $this->data['search_query'] = '';
            if(!empty($post_option['smart']))
                $this->data['search_query'] = $post_option['smart'];
                   
            $this->data['search_option_location'] = '';
            if(!empty($post_option['location']))
                $this->data['search_option_location'] = $post_option['location'];
        }

        $this->g_post_option = &$post_option;

        // [/JSON_SEARCH]

        /* Define purpose */
        $purpose = '';
        $this->data['purpose_rent_active'] = '';
        $this->data['purpose_sale_active'] = '';
        
        $this->data['is_purpose_rent'] = array();
        $this->data['is_purpose_sale'] = array();
        
        if(strpos($this->temp_data['page']->template, 'rent') !== FALSE)
        {
            $purpose = 'rent';
            $this->data['purpose_rent_active'] = 'active';
            $this->data['is_purpose_rent'][] = array('count'=>'1');
        }
        else if(strpos($this->temp_data['page']->template, 'sale') !== FALSE ||
                (strpos($this->temp_data['page']->template, 'home') !== FALSE && config_item('all_results_default') !== TRUE))
        {
            $purpose = 'sale';
            $this->data['purpose_sale_active'] = 'active';
            $this->data['is_purpose_sale'][] = array('count'=>'1');
        }
        
        $where = array();
        $where['is_activated'] = 1;
        $where['is_visible'] = 1;
        $where['language_id']  = $lang_id;
        
        if(isset($this->data['settings_listing_expiry_days']))
        {
            if(is_numeric($this->data['settings_listing_expiry_days']) && $this->data['settings_listing_expiry_days'] > 0)
            {
                 $where['property.date_modified >']  = date("Y-m-d H:i:s" , time()-$this->data['settings_listing_expiry_days']*86400);
            }
        }
        

        //$options = $this->option_m->get_options($this->data['lang_id']);
        $options_name = $this->option_m->get_lang(NULL, FALSE, $this->data['lang_id']);
        
        $this->data['search_purpose'] = NULL;

        /* Check for tab/purpose select */
        foreach($options_name as $key=>$row)
        {
            $this->data['options_val_'.$row->option_id] = $row->values;
        }
        
        $this->select_tab_by_title = '';
        if(isset($this->data['options_val_4']) && !isset($_GET['search']))
        {
            if(!empty($this->data['page_title']))
            if(strpos(strtolower($this->data['options_val_4']), strtolower($this->data['page_title'])) !== false)
            {
                $this->select_tab_by_title = strtolower($this->data['page_title']);
            }
        }
        
        if(!empty($this->data['search_query']))
            $this->data['page_title'].=' - '.$this->data['search_query'];

        // If no selection, then select first
        if(isset($this->data['options_val_4']))
        //if(strpos(strtolower(' '.$this->data['options_val_4']), strtolower($this->_get_purpose()))  === false)
        if($this->select_tab_by_title == '' && config_item('all_results_default') !== TRUE)
        {
            $vals = explode(',', $this->data['options_val_4']);
            if(sw_count($vals)>0)
            $this->select_tab_by_title = strtolower($vals[0]);
        }

        /* End check for tab/purpose select */
        
        /* check for type select */
        
        if(!empty($this->data['page_title']) && isset($this->data['options_val_2']) && !isset($_GET['search'])){
            if(empty(search_value(2)) && strpos(strtolower($this->data['options_val_2']), strtolower($this->data['page_title'])) !== false)
            {
                $options_val_2_array_lower = explode(',',strtolower($this->data['options_val_2']));
                if(in_array(strtolower($this->data['page_title']), $options_val_2_array_lower)) {

                    if(empty($search_json))
                        $search_json = new stdClass ();

                    $search_json->v_search_option_2 = $this->data['page_title'];
                }

            }
            if(isset($this->data['options_val_4']) && empty(search_value(4)) && strpos(strtolower($this->data['options_val_4']), strtolower($this->data['page_title'])) !== false)
            {
                
                $options_val_4_array_lower = explode(',',strtolower($this->data['options_val_4']));
                if(in_array(strtolower($this->data['page_title']), $options_val_4_array_lower)) {

                    if(empty($search_json))
                        $search_json = new stdClass ();

                    $search_json->v_search_option_4 = $this->data['page_title'];
                }
            }
        }
        /* End check for type select */
        
        /* [GeoPlugin] */
        $this->data['geodata'] = NULL;
        if(file_exists(APPPATH.'libraries/Geoplugin.php') && config_db_item('geoplugin_enabled') === TRUE)
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            
            //$ip = '93.139.139.12';
            
            if(strlen($ip) > 5){
                $this->load->library('geoplugin');
                $this->geoplugin->locate($ip);
                
                if(!empty($this->geoplugin->city))
                {
                    $this->data['geodata'] = $this->geoplugin->city;
                }
                else if(!empty($this->geoplugin->region))
                {
                    $this->data['geodata'] = $this->geoplugin->region;
                }
                else if(!empty($this->geoplugin->countryName))
                {
                    $this->data['geodata'] = $this->geoplugin->countryName;
                }
    
                if(empty($this->data['search_query']) && !isset($_GET['search']) && !empty($this->data['geodata']))
                    $this->data['search_query'] = $this->data['geodata'];
            }
            
        }
        /* [/GeoPlugin] */

        $this->data['options_name'] = array();
        $this->data['options_suffix'] = array();
        foreach($options_name as $key=>$row)
        {
            $this->data['options_name_'.$row->option_id] = $row->option;
            $this->data['options_suffix_'.$row->option_id] = $row->suffix;
            $this->data['options_prefix_'.$row->option_id] = $row->prefix;
            $this->data['options_values_'.$row->option_id] = '';
            $this->data['options_values_li_'.$row->option_id] = '';
            $this->data['options_values_arr_'.$row->option_id] = array();
            $this->data['options_values_radio_'.$row->option_id] = '';
            $this->data['options_obj_'.$row->option_id] = $row;
            
            if(sw_count(explode(',', $row->values)) > 0)
            {
                $options_h = '<option value="">'.$row->option.'</option>';
                $options_li = '';
                $radio_li = '';
                $_s_value = strtolower(search_value($row->option_id));
                foreach(explode(',', $row->values) as $key2 => $val)
                {
                    $o_selected = '';
                    if(!empty($_s_value) && $_s_value == strtolower($val))
                    {
                        $o_selected = 'selected="selected"';
                    }
                    
                    $selected = '';
                    if($this->_get_purpose() == strtolower($val))$selected = 'selected="selected"';
                    if($o_selected == 'selected="selected"')$selected = 'selected="selected"';
                    
                    if(empty($val))
                    {
                        $options_h='<option value="'.$val.'" '.$selected.'>'.lang_check('Any').'</option>';
                    }
                    else
                    {
                        $options_h.='<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                    }
                    $this->data['options_values_arr_'.$row->option_id][] = $val;
                    
                    $active = '';
                    if(!empty($val)){
                        if($this->_get_purpose() == strtolower($val))$active = 'active';
                        if($o_selected == 'selected="selected"')$active = 'active';
                        $options_li.= '<li class="'.$active.' cat_'.$key2.'"><a href="#">'.$val.'</a></li>';
                    }
                    
                    $checked = '';
                    if($this->_get_purpose() == strtolower($val))$checked = 'checked';
                    if($o_selected == 'selected="selected"')$checked = 'checked';
                    
                    $radio_li.='<label class="checkbox">
                                <input type="radio" rel="'.$val.'" name="search_option_'.$row->option_id.'" value="'.$key2.'" '.$checked.'> '.$val.'
                                </label>';
                }
                $this->data['options_values_'.$row->option_id] = $options_h;
                $this->data['options_values_li_'.$row->option_id] = $options_li;
                $this->data['options_values_radio_'.$row->option_id] = $radio_li;
            }
        }


        /* [Get last n properties] */
        $last_n = 4;
        if(config_item('last_estates_limit'))
            $last_n = config_item('last_estates_limit');
        
        $last_n_estates = $this->estate_m->get_by(array('is_activated' => 1, 'is_visible'=>1, 'language_id'=>$lang_id), FALSE, $last_n, 'id DESC');
        
        $this->data['last_estates_num'] = $last_n;
        $this->data['last_estates'] = array();
        $this->generate_results_array($last_n_estates, $this->data['last_estates'], $options_name); 
        /* [/Get last n properties] */
        
        /* [Offset] */
        
        if(isset($search_json->page_num))
        {
            $this->data['pagination_offset'] = $search_json->page_num;
            $config['cur_page'] = $search_json->page_num;
        }
        
        /* [/Offset] */
        
        /* [Define order] */
        
        if(isset($search_json->order))
        {
            $order=$search_json->order;
        }
        
        if(empty($order))$order='id DESC';

        $this->data['order_dateASC_selected'] = '';
        if($order=='id ASC')
            $this->data['order_dateASC_selected'] = 'selected';
            
        $this->data['order_dateDESC_selected'] = '';
        if($order=='id DESC')
            $this->data['order_dateDESC_selected'] = 'selected';
            
        $this->data['order_priceASC_selected'] = '';
        if($order=='price ASC')
            $this->data['order_priceASC_selected'] = 'selected';
            
        $this->data['order_priceDESC_selected'] = '';
        if($order=='price DESC')
            $this->data['order_priceDESC_selected'] = 'selected';

        $this->data['order_livingarea_selected'] = '';
        if($order=='livingArea')
            $this->data['order_livingarea_selected'] = 'selected';
        
        $this->data['order_viewsASC_selected'] = '';
        if($order=='counter_views ASC')
            $this->data['order_viewsASC_selected'] = 'selected';
            
        $this->data['order_viewsDESC_selected'] = '';
        if($order=='counter_views DESC')
            $this->data['order_viewsDESC_selected'] = 'selected';
            
        if(sw_count($this->data['is_purpose_rent']) > 0)
        {
            $order = str_replace("price", "field_37_int", $order);
        }
        
        $order = str_replace("price", "field_36_int", $order);
        /* [/Define order] */
        
        /* Pagination configuration */ 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['base_url'] = $this->data['ajax_load_url'];
        $config['total_rows'] = 200;
        $config['per_page'] = config_item('per_page');
        $config['uri_segment'] = 5;
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        /* End Pagination */

        /* [Search configuration] */
        $offset_properties = $this->data['pagination_offset'];
        $search_array = $search_json;
     
        if(!empty($this->data['search_query']))
        {
            if($search_array === NULL)$search_array  = new stdClass();
            
            $search_array->search_option_smart = 
                                $this->data['search_query'];
        }

        $lang_purpose = lang_check(ucfirst($purpose));
        if($this->select_tab_by_title != '' && $search_array===NULL)
        {
            $lang_purpose = $this->select_tab_by_title;

            if($lang_purpose=='rent')
            {
                $this->data['purpose_rent_active'] = 'active';
                $this->data['is_purpose_rent'] = array();
                $this->data['is_purpose_rent'][] = array('count'=>'1');
                
                $this->data['purpose_sale_active'] = '';
                $this->data['is_purpose_sale'] = array();
            }
        }
                
        if(!empty($lang_purpose) && $search_array===NULL)
        {
            $search_array['v_search_option_4'] = $lang_purpose;
        }
        if(!empty($address)){
            $where['property.address']  = $address;
        }
        /* [/Search configuration] */

        /* [Get paginated results] */
        $config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.is_featured DESC, '.$order, 
                                               NULL, $search_array);
        
        $this->data['total_rows'] = $config['total_rows'];
            
        $featured_order = '';
        if(config_db_item('featured_on_top_disabled') == FALSE ){
            $featured_order = 'property.is_featured DESC, ';
        }
        
        $results_obj = $this->estate_m->get_by($where, false, $config['per_page'], $featured_order.$order, 
                                               $offset_properties, $search_array);

        $this->data['has_no_results'] = array();
        if(sw_count($results_obj) == 0)
            $this->data['has_no_results'][] = array('count'=>sw_count($results_obj));
        
        
        $this->data['results'] = array();
        $this->generate_results_array($results_obj, $this->data['results'], $options_name); 

        $this->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->pagination->create_links();
        /* [/Get paginated results] */
        
        $limit_markers = 100;
        if(config_db_item('limit_markers') !== FALSE && is_numeric(config_db_item('limit_markers'))){
            $limit_markers = config_db_item('limit_markers');
        }
        
        $featured_order = '';
        if(config_db_item('featured_on_top_disabled') == FALSE ){
            $featured_order = 'property.is_featured DESC, ';
        }
        
        /* [Get all estates data] */
        $this->data['all_estates'] = array();
        $results_obj = $this->estate_m->get_by($where, false, $limit_markers, $featured_order.$order, 
                                               0, $search_array);
        $this->generate_results_array($results_obj, $this->data['all_estates'], $options_name); 
        $this->data['all_estates_center'] = calculateCenter($this->data['all_estates']);
        
        $this->data['has_no_all_estates'] = array();
        if(sw_count($this->data['all_estates']) == 0)
        {
            $this->data['has_no_all_estates'][] = array('count'=>sw_count($this->data['all_estates']));
        }
        /* [/Get all estates data] */
        
        /* [Get featured estates data] */
        $this->data['featured_properties'] = array();
        $this->data['featured_estates'] = $this->estate_m->get_by(array_merge($where, array('is_featured'=>1)), false, 20);
        $this->generate_results_array($this->data['featured_estates'], $this->data['featured_properties'], $options_name);      
        /* [/Get featured estates data] */
        
        
        /* [Fetch all agents] */
        
        $search_agent = $this->input->get('search-agent', TRUE);
        
        $user_enabled= '';
        if(config_db_item('agents_page_user_enable') !== FALSE) {
            $user_enabled= ' or type LIKE \'USER%\' ';
        }
                
        $all_agents = $this->user_m->get_counted('type LIKE \'AGENT%\' '.$user_enabled.' and activated=1', FALSE, NULL, 'properties_count DESC, user_id', '', $search_agent);
        /* [Fetch agent listings num] */
        $this->load->model('packages_m');
        $listings_count = $this->packages_m->get_curr_listings();
        /* [/Fetch agent listings num] */        
        
        $this->data['all_agents'] = array();
        foreach($all_agents as $key=>$agent_obj)
        {
            $agent = array();
            $agent['name_surname'] = $agent_obj->name_surname;
            $agent['phone'] = $agent_obj->phone;
            $agent['mail'] = $agent_obj->mail;
            $agent['address'] = $agent_obj->address;
            
            $agent['agent_name_title'] = url_title_cro($agent_obj->name_surname);
            $agent['agent_url'] = slug_url('profile/'.$agent_obj->id.'/'.$this->data['lang_code'].'/'.$agent['agent_name_title']);
            
            if(isset($listings_count[$agent_obj->id]))
                $agent['total_listings_num'] = $listings_count[$agent_obj->id];
            else
                $agent['total_listings_num'] = '0';
            
            if(isset($agent_obj->image_user_filename) and file_exists(FCPATH.'files/thumbnail/'.$agent_obj->image_user_filename))
            {
                $agent['image_url'] =  base_url('files/thumbnail/'.$agent_obj->image_user_filename);
            }
            else
            {
                //$agent['image_url'] = 'assets/img/no_image.jpg';
                $agent['image_url'] = 'assets/img/user-agent.png';
            }
            
            // [agent second image]
            if(isset($agent_obj->image_agency_filename) and file_exists(FCPATH.'files/thumbnail/'.$agent_obj->image_agency_filename))
            {
                $agent['image_sec_url'] = base_url('files/thumbnail/'.$agent_obj->image_agency_filename);
            }
            // [/agent second image]
            
            $this->data['all_agents'][] = $agent;
        }
        
        $this->data['has_agents'] = array();
        if(sw_count($all_agents))
            $this->data['has_agents'][] = array('count'=>sw_count($all_agents));
            
         /* [/Fetch all agents] */
        
        /* [Fetch paginated agents] */
        
        $offset = $this->uri->segment(4);
        if(empty($offset))$offset = 0;
        
        $agent_per_page = config_item('per_page_agents');
        if(empty($agent_per_page))
            $agent_per_page = 32;
        
        $user_enabled= '';
        if(config_db_item('agents_page_user_enable') !== FALSE) {
            $user_enabled= ' or type LIKE \'USER%\' ';
        }
        
        $paginated_agents = $this->user_m->get_counted('(type LIKE \'AGENT%\' '.$user_enabled.') AND activated=1 AND NOT property_id IS NULL', FALSE, $agent_per_page, 'properties_count DESC, user_id', $offset, $search_agent);

        $this->data['paginated_agents'] = array();
        foreach($paginated_agents as $key=>$agent_obj)
        {
            $agent = array();
            $agent['name_surname'] = $agent_obj->name_surname;
            $agent['phone'] = $agent_obj->phone;
            $agent['mail'] = $agent_obj->mail;
            $agent['address'] = $agent_obj->address;
            $agent['description'] = $agent_obj->description;
            
            $agent['agent_name_title'] = url_title_cro($agent_obj->name_surname);
            $agent['agent_url'] = slug_url('profile/'.$agent_obj->id.'/'.$this->data['lang_code'].'/'.$agent['agent_name_title']);
            
            $agent['total_listings_num'] = $agent_obj->properties_count;
            
            if(isset($agent_obj->image_user_filename) and file_exists(FCPATH.'files/thumbnail/'.$agent_obj->image_user_filename))
            {
                $agent['image_url'] =  base_url('files/thumbnail/'.$agent_obj->image_user_filename);
            }
            else
            {
                $agent['image_url'] = 'assets/img/user-agent.png';
            }
            
            $agent['agent_profile'] = (array) $agent_obj;
            
            $this->data['paginated_agents'][] = $agent;
        }

        /* Pagination configuration */ 
        $config_2['base_url'] = site_url($this->data['lang_code'].'/'.$this->data['page_id'].'/'.$this->uri->segment(3).'/');
        //$config_2['first_url'] = site_url($this->uri->uri_string());
        $config_2['total_rows'] = sw_count($this->data['all_agents']);
        $config_2['per_page'] = $agent_per_page;
        $config_2['uri_segment'] = 4;
    	$config_2['num_tag_open'] = '<li>';
    	$config_2['num_tag_close'] = '</li>';
        $config_2['full_tag_open'] = '<ul class="pagination">';
        $config_2['full_tag_close'] = '</ul>';
        $config_2['cur_tag_open'] = '<li class="active"><span>';
        $config_2['cur_tag_close'] = '</span></li>';
    	$config_2['next_tag_open'] = '<li>';
    	$config_2['next_tag_close'] = '</li>';
    	$config_2['prev_tag_open'] = '<li>';
    	$config_2['prev_tag_close'] = '</li>';
        /* End Pagination */

        //$this->pagination->initialize($config_2);
        $pagination_3 = new MY_Pagination($config_2);
        //$pagination_2->initialize($config_2);
        $this->data['agents_pagination'] = $pagination_3->create_links();
        
        
        /* [/Fetch paginated agents] */
        
        /* Validation for contact */
        $rules = array(
            'firstname' => array('field'=>'firstname', 'label'=>'lang:FirstLast', 'rules'=>'trim|required|xss_clean'),
            'email' => array('field'=>'email', 'label'=>'lang:Email', 'rules'=>'trim|required|xss_clean'),
            'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|xss_clean'),
            'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|required|xss_clean')
       );
       
       if(config_item('captcha_disabled') === FALSE)
            $rules['captcha'] = array('field'=>'captcha', 'label'=>'lang:Captcha', 
                                                    'rules'=>'trim|required|callback_captcha_check|xss_clean');
       
        if(config_item('recaptcha_site_key') !== FALSE)
            $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>'lang:Recaptcha', 
                                                    'rules'=>'trim|required|callback_captcha_check|xss_clean');
       
       if(isset($_POST['question']))
       {
            unset($rules['message']);
            $rules['question'] = array('field'=>'question', 'label'=>'lang:Question', 'rules'=>'trim|required|xss_clean');
       }
       
       $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->page_m->array_from_post(array('firstname', 'email', 'phone', 'message'));

            // Send email
            $this->load->library('email');
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
            $this->email->to($this->data['settings_email']);
            
            $this->email->subject(lang_check('Message from real-estate web'));
            
            if(isset($_POST['question']))
            {
                $this->load->model('qa_m');
                
                $data_t = $this->page_m->array_from_post(array('firstname', 'email', 'phone', 'question'));
                
                $data = array();
                $data['is_readed'] = 0;
                $data['date'] = date('Y-m-d H:i:s');
                $data['type'] = 'QUESTION';
                $data['answer_user_id'] = 0;
                $data['parent_id'] = 0;
                
                $data_lang = array();
                $data_lang['question_'.$lang_id] = $data_t['question'];
                
                $id = $this->qa_m->save_with_lang($data, $data_lang, NULL);
                $this->email->subject(lang_check('Expert question from real-estate web'));
    
                $data['name_surname'] = $data_t['firstname'];
                $data['phone'] = $data_t['phone'];
                $data['mail'] = $data_t['email'];
            }
            
            unset($_POST['captcha'], $_POST['captcha_hash']);
            
            $message='';
            foreach($_POST as $key=>$value){
            	$message.="$key:\n$value\n";
            }
                        
            $message = $this->load->view('email/contact_message', array('data'=>$data), TRUE);
            
            $this->email->message($message);
            if ( ! $this->email->send())
            {
                $this->session->set_flashdata('email_sent', 'email_sent_false');
            }
            else
            {
                $this->session->set_flashdata('email_sent', 'email_sent_true');
            }
            
//            echo $this->email->print_debugger();
//            exit();

            redirect($this->uri->uri_string());
        }
        
        $this->data['validation_errors'] = validation_errors();

        $this->data['form_sent_message'] = '';
        if($this->session->flashdata('email_sent'))
        {
            if($this->session->flashdata('email_sent') == 'email_sent_true')
            {
                $this->data['form_sent_message'] = '<p class="alert alert-success">'.lang_check('message_sent_successfully').'</p>';
            }
            else
            {
                $this->data['form_sent_message'] = '<p class="alert alert-error">'.lang_check('message_sent_error').'</p>';
            }  
        }
        
        // Form errors
        $this->data['form_error_firstname'] = form_error('firstname')==''?'':'error';
        $this->data['form_error_email'] = form_error('email')==''?'':'error';
        $this->data['form_error_phone'] = form_error('phone')==''?'':'error';
        $this->data['form_error_message'] = form_error('message')==''?'':'error';
        $this->data['form_error_question'] = form_error('question')==''?'':'error';
        $this->data['form_error_captcha'] = form_error('captcha')==''?'':'error';
        
        // Form values
        $this->data['form_value_firstname'] = set_value('firstname', '');
        $this->data['form_value_email'] = set_value('email', '');
        $this->data['form_value_phone'] = set_value('phone', '');
        $this->data['form_value_message'] = set_value('message', '');
        $this->data['form_value_question'] = set_value('question', '');
        
        /* End validation for contact */
        
        $page_id = $this->data['page_id'];
        
        /* {PAGE SLIDESHOW} */
        if(config_db_item('page_slideshow_enabled') == TRUE && sw_count($this->data['page_images']) > 0)
        {
            $rep_file_count = array();
            $this->data['slideshow_images'] = array();
            $num=0;
    
            foreach($this->data['page_images']  as $key=>$file)
            {
                if($this->temp_data['page']->repository_id == $file->repository_id)
                {
                    $slideshow_image = array();
                    $slideshow_image['num'] = $num;
                    $slideshow_image['url'] = str_replace(' ', '%20', base_url('files/'.$file->filename));
                    $slideshow_image['first_active'] = '';
                    if($num==0)$slideshow_image['first_active'] = 'active';
                    
                    $this->data['slideshow_images'][] = $slideshow_image;
                    $num++;
                }
            }
        }
         /* {/PAGE SLIDESHOW} */
        
        /* {ARTICLES} */
        // Fetch all pages
        $this->data['news_articles'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, array('parent_id' => $page_id, 'type'=>'ARTICLE'), null, '', 'date_publish DESC');
        
        $this->data['news_articles_157'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, array('parent_id' => 157, 'type'=>'ARTICLE'), null, '', 'date_publish DESC');
        /* {/ARTICLES} */
        
        /* {MODULE_NEWS} */
        
        $category_id = 0;
        
        // Check for contained category/parent_id
        $news_category = $this->page_m->get_contained_news_category($page_id);
        $cat_merge = array('date_publish <'=>date('Y-m-d H:i:s'));
        if(sw_count($news_category)>0)
        {
            $cat_merge = array('parent_id' => $news_category->id);
            $category_id = $news_category->id;
        }
        
        $category_id_get = $this->input->get('cat', TRUE);
        if(!empty($category_id_get))
        {
            $cat_merge = array('parent_id' => $category_id_get);
            $category_id = $category_id_get;
        }
        
        $pagination_offset=0;
        
        // Fetch all pages
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['categories'] = $this->page_m->get_no_parents_news_category($lang_id);
        $this->data['news_module_all'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, array_merge($cat_merge, array('type'=>'MODULE_NEWS_POST')), null, '', 'date_publish DESC');
        
        /* Pagination configuration */ 
        $config_2['base_url'] = site_url('news/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id'].'/'.$category_id.'/');
        //$config_2['first_url'] = site_url($this->uri->uri_string());
        $config_2['total_rows'] = sw_count($this->data['news_module_all']);
        $config_2['per_page'] = config_item('per_page');
        $config_2['uri_segment'] = 5;
    	$config_2['num_tag_open'] = '<li>';
    	$config_2['num_tag_close'] = '</li>';
        $config_2['full_tag_open'] = '<ul class="pagination">';
        $config_2['full_tag_close'] = '</ul>';
        $config_2['cur_tag_open'] = '<li class="active"><span>';
        $config_2['cur_tag_close'] = '</span></li>';
    	$config_2['next_tag_open'] = '<li>';
    	$config_2['next_tag_close'] = '</li>';
    	$config_2['prev_tag_open'] = '<li>';
    	$config_2['prev_tag_close'] = '</li>';
        /* End Pagination */

        //$this->pagination->initialize($config_2);
        $pagination_2 = new CI_Pagination($config_2);
        //$pagination_2->initialize($config_2);
        $this->data['news_pagination'] = $pagination_2->create_links();
        
        $this->data['news_module_all'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, 
                                                          array_merge($cat_merge, array('type'=>'MODULE_NEWS_POST')), 
                                                          $config_2['per_page'], $pagination_offset, 'date_publish DESC');
        
        if(file_exists(APPPATH.'controllers/admin/news.php'))
        {
            $this->data['news_module_latest_5'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, 
                                                              array_merge($cat_merge, array('type'=>'MODULE_NEWS_POST')), 
                                                              5, 0, 'date_publish DESC');
        }
        else
        {
            $this->data['news_module_latest_5'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, 
                                                              array('type'=>'ARTICLE'), 
                                                              5, 0, 'date DESC');
        }

        
        /* {/MODULE_NEWS} */
        
        /* {MODULE_ADS} */
        $this->load->model('ads_m');
        $this->data['ads'] = array();
        
        foreach($this->ads_m->ads_types as $type_key=>$type_name)
        {
            $ads_by_type = $this->ads_m->get_by(array('type'=>$type_key));
            
            $num_ads = sw_count($ads_by_type);

            $this->data['has_ads_'.$type_name] = array();
            if(isset($ads_by_type[0]))
            if($num_ads > 0 && $ads_by_type[0]->is_activated)
            {
                $rand_ad_key=0;
                if($ads_by_type[0]->is_random)
                    $rand_ad_key = rand(0, $num_ads-1);
                
                if(isset($ads_by_type[$rand_ad_key]) && isset($this->data['images_'.$ads_by_type[$rand_ad_key]->repository_id]))
                {
                    $rand_image = rand(0, sw_count($this->data['images_'.$ads_by_type[$rand_ad_key]->repository_id])-1);
                    
                    $this->data['random_ads_'.$type_name.'_link'] = $ads_by_type[$rand_ad_key]->link;
                    $this->data['random_ads_'.$type_name.'_repository'] = $ads_by_type[$rand_ad_key]->repository_id;
                    $this->data['random_ads_'.$type_name.'_image'] = $this->data['images_'.$ads_by_type[$rand_ad_key]->repository_id][$rand_image]->url;
                    $this->data['has_ads_'.$type_name][] = array('count' => $num_ads);
                }
            }
        }
        /* {/MODULE_ADS} */
        
        /* {MODULE_SHOWROOM} */
        
        $this->load->model('showroom_m');
        
        $category_id = 0;
        
        // Check for contained category/parent_id
        $showroom_category = $this->showroom_m->get_contained_showroom_category($page_id);
        $cat_merge = array();
        if(sw_count($showroom_category)>0)
        {
            $cat_merge = array('parent_id' => $showroom_category->id);
            $category_id = $showroom_category->id;
        }
        
        $category_id_get = $this->input->get('cat', TRUE);
        if(!empty($category_id_get))
        {
            $cat_merge = array('parent_id' => $category_id_get);
            $category_id = $category_id_get;
        }
        
        $pagination_offset=0;
        
        // Fetch all pages
        $this->data['categories_showroom'] = $this->showroom_m->get_no_parents_showrooms_category($lang_id);
        $this->data['showroom_module_all'] = $this->showroom_m->get_lang(NULL, FALSE, $lang_id, array_merge($cat_merge, array('type'=>'COMPANY')), null, '', 'date_publish DESC');
        
        /* Pagination configuration */ 
        $config_2['base_url'] = site_url('showroom/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id'].'/'.$category_id.'/');
        //$config_2['first_url'] = site_url($this->uri->uri_string());
        $config_2['total_rows'] = sw_count($this->data['showroom_module_all']);
        $config_2['per_page'] = config_item('per_page');
        $config_2['uri_segment'] = 5;
    	$config_2['num_tag_open'] = '<li>';
    	$config_2['num_tag_close'] = '</li>';
        $config_2['full_tag_open'] = '<ul class="pagination">';
        $config_2['full_tag_close'] = '</ul>';
        $config_2['cur_tag_open'] = '<li class="active"><span>';
        $config_2['cur_tag_close'] = '</span></li>';
    	$config_2['next_tag_open'] = '<li>';
    	$config_2['next_tag_close'] = '</li>';
    	$config_2['prev_tag_open'] = '<li>';
    	$config_2['prev_tag_close'] = '</li>';
        /* End Pagination */

        //$this->pagination->initialize($config_2);
        $pagination_2 = new CI_Pagination($config_2);
        //$pagination_2->initialize($config_2);
        $this->data['showroom_pagination'] = $pagination_2->create_links();
        
        $this->data['showroom_module_all'] = $this->showroom_m->get_lang(NULL, FALSE, $lang_id, 
                                                          array_merge($cat_merge, array('type'=>'COMPANY')), 
                                                          $config_2['per_page'], $pagination_offset, 'date_publish DESC');
        
        $this->data['showroom_module_latest_5'] = $this->showroom_m->get_lang(NULL, FALSE, $lang_id, 
                                                          array_merge($cat_merge, array('type'=>'COMPANY')), 
                                                          5, 0, 'date_publish DESC');
        
        /* {/MODULE_SHOWROOM} */
        
        /* {MODULE_Q&A} */
        
        $this->load->model('qa_m');
        
        $category_id = 0;
        
        // Check for contained category/parent_id
        $expert_category = $this->qa_m->get_contained_expert_category($page_id);
        $cat_merge = array();
        if(sw_count($expert_category)>0)
        {
            $cat_merge = array('parent_id' => $expert_category->id);
            $category_id = $expert_category->id;
        }
        
        $category_id_get = $this->input->get('cat', TRUE);
        if(!empty($category_id_get))
        {
            $cat_merge = array('parent_id' => $category_id_get);
            $category_id = $category_id_get;
        }
        
        $pagination_offset=0;
                
        // Fetch all pages
        $this->data['categories_expert'] = $this->qa_m->get_no_parents_expert_category($lang_id);
        $this->data['expert_module_all'] = $this->qa_m->get_lang(NULL, FALSE, $lang_id, array_merge($cat_merge, array('type'=>'QUESTION', 'is_readed'=>1)), null, '', 'date_publish DESC');
        
        /* Pagination configuration */ 
        $config_2['base_url'] = site_url('expert/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id'].'/'.$category_id.'/');
        //$config_2['first_url'] = site_url($this->uri->uri_string());
        $config_2['total_rows'] = sw_count($this->data['expert_module_all']);
        $config_2['per_page'] = config_item('per_page');
        $config_2['uri_segment'] = 5;
    	$config_2['num_tag_open'] = '<li>';
    	$config_2['num_tag_close'] = '</li>';
        $config_2['full_tag_open'] = '<ul>';
        $config_2['full_tag_close'] = '</ul>';
        $config_2['cur_tag_open'] = '<li class="active"><span>';
        $config_2['cur_tag_close'] = '</span></li>';
    	$config_2['next_tag_open'] = '<li>';
    	$config_2['next_tag_close'] = '</li>';
    	$config_2['prev_tag_open'] = '<li>';
    	$config_2['prev_tag_close'] = '</li>';
        /* End Pagination */

        //$this->pagination->initialize($config_2);
        $pagination_2 = new CI_Pagination($config_2);
        //$pagination_2->initialize($config_2);
        $this->data['expert_pagination'] = $pagination_2->create_links();
        
        $this->data['expert_module_all'] = $this->qa_m->get_lang(NULL, FALSE, $lang_id, 
                                                          array_merge($cat_merge, array('type'=>'QUESTION', 'is_readed'=>1)), 
                                                          $config_2['per_page'], $pagination_offset, 'date_publish DESC');
        
        $this->data['expert_module_latest_5'] = $this->qa_m->get_lang(NULL, FALSE, $lang_id, 
                                                          array_merge($cat_merge, array('type'=>'QUESTION', 'is_readed'=>1)), 
                                                          5, 0, 'date_publish DESC');
        
        // Fetch all experts
        $all_experts = $this->user_m->get_by(array('qa_id !='=>0, 'type !=' => 'USER'));
        
        $this->data['all_experts'] = array();
        foreach($all_experts as $key=>$expert_obj)
        {
            $agent = array();
            $agent['name_surname'] = $expert_obj->name_surname;
            $agent['phone'] = $expert_obj->phone;
            $agent['mail'] = $expert_obj->mail;
            $agent['address'] = $expert_obj->address;
            
            if(isset($expert_obj->image_user_filename))
            {
                $agent['image_url'] =  base_url('files/thumbnail/'.$expert_obj->image_user_filename);
            }
            else
            {
                $agent['image_url'] = 'assets/img/user-agent.png';
            }


            $this->data['all_experts'][$expert_obj->id] = $agent;
        }
        
        /* {/MODULE_Q&A} */

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
        
        // custom template defined
        if(substr($this->temp_data['page']->template, 0, 7) == 'custom_')
        {
            $this->load->model('customtemplates_m');
            $template_id = substr($this->temp_data['page']->template, 7);
            
            $this->temp_data['page']->template = 'custom_template';
            
            $this->data['template_data'] = $this->customtemplates_m->get($template_id);
            $this->data['widgets_order'] = json_decode($this->data['template_data']->widgets_order);

            if($this->data['template_data']->theme != $this->data['settings_template'] ) {
                show_error('<a href="'.site_url('admin/page/edit/'.$this->data['page_id']).'" style="text-decoration: none;">'.sprintf(lang_check('Template %1$s doesn\'t exists, please select different template for page %2$s'), 'custom_'.$template_id, $this->data['page_title']).'</a>', 500, lang_check('Missing tempalte'));
                $this->temp_data['page']->template = 'page_homepage-filters'; 
            }

        }
        
        if(isset($this->data['widget_preview']) && $this->data['widget_preview']) {
            $this->temp_data['page']->template = 'inc_widget_preview';
            $this->data['widget_preview_file'] = $this->uri->segment(3);
        }
        
        $output = $this->parser->parse($this->data['settings_template'].'/'.$this->temp_data['page']->template.'.php', $this->data, TRUE);
        $output =  str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
        
        if(config_item('litecache_enabled') === TRUE)
        {
            $this->litecache->save_cache($output);
        }
        
        echo $output;
	}

	public function gps_check($str)
	{
        $gps_coor = explode(', ', $str);
        
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
    
	public function captcha_check($str)
	{
        if(config_item('recaptcha_site_key') !== FALSE)
        {
            if(valid_recaptcha() === TRUE)
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('captcha_check', lang_check('Robot verification failed'));
                return FALSE;
            }
        }
       
		if ($str != substr(md5($this->data['captcha_hash_old'].config_item('encryption_key')), 0, 5))
		{
			$this->form_validation->set_message('captcha_check', lang_check('Wrong captcha'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
    
        
    public function _unique_username($str)
    {
        // Do NOT validate if username alredy exists
        // UNLESS it's the username for the current user
        $id = $this->session->userdata('id');
        $this->db->where('username', $this->input->post('username'));
        !$id || $this->db->where('id !=', $id);
        
        $user = $this->user_m->get();
        
        if(sw_count($user))
        {
            $this->form_validation->set_message('_unique_username', '%s '.lang('should be unique'));
            return FALSE;
        }

        return TRUE;
    }
    
    public function _unique_mail($str)
    {
        // Do NOT validate if mail alredy exists
        // UNLESS it's the mail for the current user
        
        $id = $this->session->userdata('id');
        $this->db->where('mail', $this->input->post('mail'));
        !$id || $this->db->where('id !=', $id);
        
        $user = $this->user_m->get();
        
        if(sw_count($user))
        {
            $this->form_validation->set_message('_unique_mail', '%s '.lang('should be unique'));
            return FALSE;
        }
        
        return TRUE;
    }
    
    function _phone_clickatellapi_check($str)
    {
        $this->form_validation->set_message('_phone_clickatellapi_check', lang_check('Phone:').' '.$this->_clickatellapi_error);
        return FALSE;
    }

    public function limit_images_packages($str){

        $this->load->model('file_m');
        $this->load->model('user_m');
        $this->load->model('packages_m');

        $user = $this->user_m->get($this->session->userdata('id'));
        if($user->package_id > 0)
        {
            $package = $this->packages_m->get($user->package_id);
            
            if(is_numeric($package->num_images_limit))
            {
                $num_limitation = intval($package->num_images_limit);
            }
        }   
        $files = $this->file_m->get_by(array('repository_id' => $str));

        if(sw_count($files) > $num_limitation)
        {
            $this->form_validation->set_message('limit_images_packages', lang_check('Max images of your packages'));
            return FALSE;
        }

        return TRUE;
    }  
}