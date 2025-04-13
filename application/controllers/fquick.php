<?php

class Fquick extends Frontend_Controller
{

	public function __construct ()
	{
		parent::__construct();
        
        $this->load->model('packages_m');
	}
    
    public function index()
    {
        echo 'index';
    }
    
    public function events_qr_confirm($lang_code, $listing_id, $listing_hash)
    {        
        $this->load->library('session');
        $this->load->model('user_m');
        
        if($this->user_m->hash($listing_id) != $listing_hash)
        {
            exit(lang_check('Wrong link'));
        }
        
        $user_id = $this->session->userdata('id');
        
        if(empty($user_id))
        {
            // save to session and redirect to login with message
            $this->session->set_flashdata('message', 
            '<p class="alert alert-success validation">'.lang_check('Please login to confirm').'</p>');
            
            $this->session->set_userdata('listing_attend', $listing_id);
            
            redirect('frontend/login/'.$lang_code);
            
            exit(lang_check('Please login to confirm'));
        }
        
        // populate user_attend_listing
        $this->load->model('userattend_m');
        
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        $data = array();
        $data['user_id'] = $user_id;
        $data['listing_id'] = $listing_id;
        $data['date'] = date("Y-m-d H:i:s");
        $data['ip'] = $ip;
        
        $this->userattend_m->save($data, NULL);
        
        // redirect to myproperties with message
        $this->session->set_flashdata('message', 
        '<p class="alert alert-success validation">'.lang_check('Thanks on attending this event').'</p>');
        
        redirect('frontend/myproperties/'.$lang_code);
        
        exit();
    }
    
	public function submission()
	{
        $this->data['content_language_id'] = $this->data['lang_id'];

        if(config_db_item('enable_qs')  != 1) {
            show_404();
        }
        
        
        $this->load->model('treefield_m');
       
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Quick add listing');
        $this->data['page_title'] = lang_check('Quick add listing');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';


        $this->load->model('treefield_m');
        
        $current_language =  $this->language_m->get_name($this->data['lang_code']);
        if(empty($current_language))$current_language='';
        
        
        $repository_id = NULL;
        if(isset($_POST['repository_id']))
        {
            $repository_id = $this->data['repository_id'] = $_POST['repository_id'];
        }
        else
        {
            // Create new repository
            $repository_id = $this->repository_m->save(array('name'=>'estate_m', 'is_activated'=>0));
            $this->data['repository_id'] = $repository_id;

        }
        
	   
        /* Widgets functions */
        $this->data['print_menu'] = get_menu($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_lang_menu'] = get_lang_menu($this->language_m->get_array_by(array('is_frontend'=>1)), $this->data['lang_code']);
        /* End widget functions */
       
        // Get all options
        foreach($this->option_m->languages as $key=>$val){
            $this->data['options_lang'][$key] = $this->option_m->get_lang(NULL, FALSE, $key);
        }
        $this->data['options'] = $this->option_m->get_lang_array(NULL, FALSE, $this->data['content_language_id']);

        // Add rules for dynamic options
        $rules_dynamic = array();
        foreach($this->option_m->languages as $key_lang=>$val_lang){
            if(config_db_item('multilang_on_qs') == 0 && $this->language_m->get_default_id() != $key_lang)
            {
                continue;
            }
            
            foreach($this->data['options'] as $key_option=>$val_option){
                $rules_dynamic['option'.$val_option['id'].'_'.$key_lang] = 
                    array('field'=>'option'.$val_option['id'].'_'.$key_lang, 'label'=>$val_option['option'], 'rules'=>'trim');
                //if($id == NULL)$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
                //if(!isset($this->data['estate']))$this->data['estate']->{'option'.$val_option['id'].'_'.$key_lang} = '';
            }
            
            if(config_db_item('slug_enabled') === TRUE)
            {
                $rules_dynamic['slug_'.$key_lang] = 
                    array('field'=>'slug_'.$key_lang, 'label'=>'lang:URI slug', 'rules'=>'trim');
            }
        }
        
        //dump($rules_dynamic);
                
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
                
        // Set up the form
        $rules = $this->estate_m->rules;
        $rules['date']['rules'] = 'trim';
        
        if(!$this->user_m->loggedin())
            $rules['mail'] = array('field'=>'mail', 'label'=>'lang:Your email', 'rules'=>'trim|required|valid_email|callback__unique_mail');
        
        $rules['repository_id'] = array('field'=>'repository_id', 'label'=>'lang:Repository', 'rules'=>'trim|required|is_numeric');
        
        if(isset($not_logged) && sw_count($not_logged)>0) {
            if(config_item('captcha_disabled') === FALSE)
                $rules['captcha'] = array('field'=>'captcha', 'label'=>'lang:Captcha', 
                                          'rules'=>'trim|required|callback_captcha_check|xss_clean');

            if(config_item('recaptcha_site_key') !== FALSE)
                $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>'lang:Recaptcha', 
                                                        'rules'=>'trim|required|callback_captcha_check|xss_clean');
        }
        
        unset($rules['is_featured']);
        
        if(config_db_item('terms_link') !== FALSE)
        {
            $rules['option_agree_terms']['field'] = 'option_agree_terms';
            $rules['option_agree_terms']['label'] = 'lang:option_agree_terms';
            $rules['option_agree_terms']['rules'] = 'required';
        }
        
        /* minimum 1x images should be uploaded */ 
        if(config_item('required_images') !== FALSE) {
            $rules['repository_id']['rules'] = 'trim|callback_min_images';
        }
        
        $rules['gps']['rules'] = 'trim|xss_clean|callback_gps_check';
        $this->form_validation->set_rules(array_merge($rules, $rules_dynamic));

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('fquick/submission/'.$this->data['lang_code']);
                exit();
            }
            
            $data = $this->estate_m->array_from_post(array('gps', 'address', 'mail', 'repository_id'));
            $dynamic_data = $this->estate_m->array_from_post(array_keys($rules_dynamic));
            
            $data['is_activated'] = 0;
            $data['date'] = date('Y-m-d H:i:s');
            $data['date_modified'] = date('Y-m-d H:i:s');
            
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
            

            // If not logged in then add user
            if($this->user_m->loggedin())
            {
                $user = $this->user_m->get($this->session->userdata('id'));
                
                $username = $this->session->userdata('username');
                $user_id = $this->session->userdata('id');
                $user_email = $user->mail;
            }
            else
            {
                // add user
                $data_user = array();
                $data_user['username'] = $data_user['name_surname'] = $username = $data_user['mail'] = $data['mail'];
                $password = substr(md5(time().'fsdgfasd'), 0, 7);
                $data_user['password'] = $this->user_m->hash($password);
                $data_user['type'] = 'USER';

                
                $data_user['activated'] = '1';
                if(config_db_item('email_activation_enabled') === TRUE)
                    $data_user['activated'] = '0';
                
                $data_user['description'] = '';
                $data_user['language'] = $current_language;
                $data_user['registration_date'] = date('Y-m-d H:i:s');
                $data_user['mail_verified'] = 0;
                $data_user['phone_verified'] = 0;
                
                if($this->config->item('def_package') !== FALSE && $data_user['type'] == 'USER')
                {
                    $data_user['package_id'] = $this->config->item('def_package');
                    
                    $this->load->model('packages_m');
                    $package = $this->packages_m->get($data_user['package_id']);
                    
                    if(is_object($package))
                    {
                        $days_extend = $package->package_days;
                    
                        if($days_extend > 0)
                            $data_user['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
                    }
                }
                
                $user_id = $this->user_m->save($data_user, NULL);
                
                $message_mail = '';
                if($data_user['mail'] && config_db_item('email_activation_enabled') === TRUE)
                {
                    $data_user['mail_verified'] = 0;
                    // [START] Activation email

                    if(!empty($data['mail']))
                    {
                        $this->load->library('email');
                        $config_mail['mailtype'] = 'html';
                        $this->email->initialize($config_mail);
                        $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
                        $this->email->to($data_user['mail']);

                        $this->email->subject(lang_check('Activate your account'));

                        $new_hash = substr($this->user_m->hash($data['mail'].$user_id), 0, 5);

                        $data_m = array();
                        $data_m['name_surname'] = $data_user['name_surname'];
                        $data_m['username'] = $data_user['username'];
                        $data_m['activation_link'] = '<a href="'.site_url('admin/user/verifyemail/'.$user_id.'/'.$new_hash).'">'.lang_check('Activate your account').'</a>';
                        $data_m['login_link'] = '<a href="'.site_url('frontend/login/').'?username='.$data_user['username'].'#content">'.lang_check('login_link').'</a>';

                        $message = $this->load->view('email/email_activation', array('data'=>$data_m), TRUE);

                        $this->email->message($message);
                        if ( ! $this->email->send())
                        {
                            $message_mail = ', '.lang_check('Problem sending email to user');
                        }
                    }
                    // [END] Activation email
                }
                
                $user_email = $data['mail'];
                
            }
            
            $dynamic_data['agent'] = $user_id;

            unset($data['mail']);
            $insert_id = $this->estate_m->save($data, NULL);
            //echo $this->db->last_query();

            $this->estate_m->save_dynamic($dynamic_data, $insert_id);
            
            $email_activator = config_db_item('email');

            // Send email alert to contact address
            $this->load->library('email');
            
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings_noreply'], lang_check('Web page not-activated property'));
            $this->email->to($email_activator);

            $subject = lang_check('New not-activated property from user');
            
            $this->email->subject($subject);
            
            $data_m = array();
            $data_m['subject'] = $subject;
            $data_m['username'] = $data_user['username'];
            $data_m['You listing ID Number is'] = $insert_id;
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
            
            if(ENVIRONMENT != 'development')
            if(isset($data['is_activated']) && $data['is_activated'] == 0 && !empty($user_email))
            {
                
                // Send email alert to contact address
                $this->load->library('email');
                
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings_noreply'], lang_check('Web page not-activated property'));
                $this->email->to($user_email);
                $this->email->subject(lang_check('Web page not-activated property'));
                
                $data_m = array();
                $data_m['subject'] = lang_check('New not-activated property');
                $data_m['username'] = $data_user['username'];
                $data_m['password'] = $password;
                $data_m['link'] = '<a href="'.site_url('frontend/editproperty/'.$this->data['lang_code'].'/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
                $message = $this->load->view('email/waiting_for_activation_user', array('data'=>$data_m), TRUE);
                
                $this->email->message($message);
                
                $this->email->send();
            } else if(!empty($user_email)) {
                // Send email alert to contact address
                $this->load->library('email');
                
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings_noreply'], lang_check('Web page activated property'));
                $this->email->to($user_email);
                $this->email->subject(lang_check('Web page activated property'));
                
                $data_m = array();
                $data_m['subject'] = lang_check('New activated property');
                $data_m['username'] = $data_user['username'];
                $data_m['password'] = $password;
                $data_m['link'] = '<a href="'.site_url('frontend/editproperty/'.$this->data['lang_code'].'/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
                $message = $this->load->view('email/waiting_for_activation_user', array('data'=>$data_m), TRUE);
                
                $this->email->message($message);
                
                $this->email->send();
            }
            
            if(empty($data['is_activated']) || $data['is_activated'] == 0)
            {
                $this->session->set_flashdata('message', 
                        '<p class="alert alert-success">'.lang_check('Thanks on submission, waiting for approve, please check your email').'</p>');
            }
            else
            {
                $this->session->set_flashdata('message', 
                        '<p class="alert alert-success">'.lang_check('Thanks on submission, please check your email').'</p>');
            }
            
            redirect('fquick/submission/'.$this->data['lang_code']);
            
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/quicklisting.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
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

    	public function gps_check($str)
	{
        $gps_coor = explode(', ', $str);
        
        if(empty($str) && config_db_item('address_not_required') === TRUE)
            return TRUE;
        
        if(empty($str))
        {
        	$this->form_validation->set_message('gps_check', lang_check('Address not found, drag and drop to select location'));
        	return FALSE;
        }
        
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
        

        public function min_images($str){

            $this->load->model('file_m');


            $files = $this->file_m->get_by(array('repository_id' => $str));

            if(sw_count($files) == 0)
            {
                $this->form_validation->set_message('min_images', lang_check('Min one image should be exists, please upload some images'));
                return FALSE;
            }

            return TRUE;
        }  
    
}