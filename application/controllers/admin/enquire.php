<?php

class Enquire extends Admin_Controller 
{

	public function __construct(){
		parent::__construct();
        
        $this->load->model('estate_m');
        $this->load->model('enquire_m');
        
        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    public function index($pagination_offset=0)
	{
        $this->load->library('pagination');
        
        //$properties_address
        
        prepare_search_query_GET(array('readed_to','message'), array('property_id', 'name_surname', 'mail','phone'));
        $this->data['enquires'] = $this->enquire_m->get_pagination();

        $config['base_url'] = site_url('admin/enquire/index');
        $config['uri_segment'] = 4;
        $config['total_rows'] = sw_count($this->data['enquires']);
        $config['per_page'] = 10;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        prepare_search_query_GET(array('readed_to','message'), array('property_id', 'name_surname', 'mail','phone'));
        $this->data['enquires'] = $this->enquire_m->get_pagination(array(), NULL, $config['per_page'], $pagination_offset);

       	$this->data['maskings'] = $this->masking_m->get();

        // Load view
		$this->data['subview'] = 'admin/enquire/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit($id = NULL)
	{
        
        $this->load->model('user_m');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);
        
        
	    // Fetch a user or set a new one
	    if($id)
        {
            $this->data['enquire'] = $this->enquire_m->get($id);

            if(sw_count($this->data['enquire']) == 0)
            {
                $this->data['errors'][] = 'Enquire could not be found';
                redirect('admin/enquire');
            }

            if($this->data['enquire']->fromdate == '0'){
                $this->data['enquire']->fromdate = '';
            }
            
            if($this->data['enquire']->todate == '0'){
                $this->data['enquire']->todate = '';
            }
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                if($this->estate_m->check_user_permission($this->data['enquire']->property_id, 
                                         $this->session->userdata('id')) > 0)
                {
                }
                else
                {
                    
                    redirect('admin/enquire');
                }
            }
        }
        else
        {
            $this->data['enquire'] = $this->enquire_m->get_new();
        }
        
        //$this->data['all_estates'] = $this->estate_m->get_form_dropdown('address', false, true, true);
        //$this->data['all_agents'] = $this->user_m->get_form_dropdown('username', false, true, true);
        
        // Fetch options and show title in dropdown
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        $this->data['estate_title'] = '';
        $this->data['estate_title'] = $this->option_m->get_property_value($this->data['content_language_id'], $this->data['enquire']->property_id, 10);
// Commented because use to much of memory
//        $this->load->model('option_m');
//        $this->data['options'] = $this->option_m->get_options($this->data['content_language_id']);
//        foreach($this->data['all_estates'] as $key_estate=>$address_estate)
//        {
//            if(!empty($this->data['options'][$key_estate][10]))
//            $this->data['all_estates'][$key_estate] = $address_estate.', '.$this->data['options'][$key_estate][10];
//        }
        
        // Set up the form
        $rules = $this->enquire_m->rules_admin;

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
            
            $data = $this->enquire_m->array_from_post(array('name_surname', 'mail', 'message', 
                                                         'address', 'message', 'phone', 'readed', 'fromdate', 'todate', 'property_id', 'agent_id'));
            
            if($id == NULL)
                $data['date'] = date('Y-m-d H:i:s');
            
            if($data['agent_id'] == '')
                unset($data['agent_id']);
            
            $this->enquire_m->save($data, $id);
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            redirect('admin/enquire');
        }
        
        // Load the view
		$this->data['subview'] = 'admin/enquire/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function reply($id)
	{
        $this->data['enquire'] = $this->enquire_m->get($id);

        if(sw_count($this->data['enquire']) == 0)
        {
            $this->data['errors'][] = 'Enquire could not be found';
            redirect('admin/enquire');
        }
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            if($this->estate_m->check_user_permission($this->data['enquire']->property_id, 
                                     $this->session->userdata('id')) > 0)
            {
            }
            else
            {
                redirect('admin/enquire');
            }
        }
        
        $user_agent = $this->user_m->get($this->session->userdata('id'));

        if(empty($user_agent->mail))
        {
            $this->session->set_flashdata('error', 
                    lang_check('Your email is missing'));
            redirect('admin/enquire/edit/'.$id);
        }

        // Fetch options and show title in dropdown
        $this->data['content_language_id'] = $this->language_m->get_content_lang();

        // Set up the form
        $rules = $this->enquire_m->rules_reply;

        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/enquire/edit/'.$id);
            }
            
            $data = $this->enquire_m->array_from_post(array('last_reply'));
            $data['readed'] = 1;
            
            // [Send message]
            
            $this->load->library('email');
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($user_agent->mail, lang_check('Reply on property inquiry'));
            $this->email->to($this->data['enquire']->mail);
            $this->email->subject(lang_check('Reply on property inquiry'));
            
            $data_m = array();
            $data_m['subject'] = lang_check('Reply on property inquiry');
            $data_m['name_surname'] = $this->session->userdata('name_surname');
            $data_m['link'] = '<a href="'.site_url('property/'.$this->data['enquire']->property_id).'">'.lang_check('Property link').'</a>';
            $data_m['message'] = $data['last_reply'];
            
            $message = $this->load->view('email/quick_reply', array('data'=>$data_m), TRUE);
            $this->email->message($message);
            
            if ( ! $this->email->send())
            {
                echo $this->email->print_debugger();
                exit();
            }
            
            // [/Send message]

            $this->session->set_flashdata('message', 
                    lang_check('Message sent'));
            
            $this->enquire_m->save($data, $id);
            
            redirect('admin/enquire/edit/'.$id);
        }
        
        $this->session->set_flashdata('error', 
                lang_check('Message sending failed'));
        redirect('admin/enquire/edit/'.$id);
	}
    
    public function delete($id=NULL,$redirect = TRUE)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/enquire');
            exit();
        }
       
        $this->data['enquire'] = $this->enquire_m->get($id);
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            if($this->estate_m->check_user_permission($this->data['enquire']->property_id, 
                                     $this->session->userdata('id')) > 0)
            {
            }
            else
            {
                redirect('admin/enquire');
            }
        }
       
		$this->enquire_m->delete($id);
                
        if(!$redirect)return;        
        redirect('admin/enquire');
	}
    
    public function delete_masking($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/enquire');
            exit();
        }
       
        $this->data['enquire'] = $this->masking_m->get($id);
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            if($this->estate_m->check_user_permission($this->data['enquire']->property_id, 
                                     $this->session->userdata('id')) > 0)
            {
            }
            else
            {
                redirect('admin/enquire');
            }
        }
       
		$this->masking_m->delete($id);
        redirect('admin/enquire');
	}
        
        
    public function delete_multiple(){
        if(isset($_POST["delete_multiple"]) && !empty($_POST["delete_multiple"]))
            foreach($_POST["delete_multiple"] as $id)
            {
                if(is_numeric($id))
                    $this->delete($id, FALSE);
            }
        
        redirect('admin/enquire');
    }    
    
        public function messenger()
    {
        
        if(!file_exists(APPPATH.'libraries/Messenger.php') || config_item('private_messages_enabled') == FALSE) {
            redirect('fmessages');
            exit();
        }
        
        $this->load->model('user_m');
        $this->load->library('messenger');
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Messenger');
        $this->data['page_title'] = lang_check('Messenger');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $this->data['user'] = $this->user_m->get_array($this->session->userdata('id'));
        $this->data['lang_code'] = $this->language_m->get_default();
        
        $this->data['content_language_id'] = $this->data['lang_id'];
        $this->data['user_id']  = $this->session->userdata('id');

        /* remove dialog */
        $del_dialog='';
        if(isset($_GET['del_dialog']) &&  !empty($_GET['del_dialog'])
                && isset($_GET['property_id']) &&  !empty($_GET['property_id']))  {  
            $del_dialog = trim($_GET['del_dialog']);  
            $property_id = trim($_GET['property_id']);  
            
            $this->enquire_m->remove_dialog($del_dialog,$property_id);
            redirect('admin/enquire/messenger');
            exit();
        }
        
        
        $this->data['all_dialogs'] = array();
        $this->data['dialog'] = array();
        $this->data['speakers'] = array();  
        $this->data['sel'] = '';  
        $this->data['latest_id']='';
                
        $switch_messenger = false;
        
        $sel='';
        $property_id='';
        if(isset($_GET['sel']) &&  !empty($_GET['sel']) && $_GET['sel'] != $this->data['user_id']
            && isset($_GET['property_id']) &&  !empty($_GET['property_id'])    
            ) {
            $this->data['sel'] = $sel = trim($_GET['sel']);  
            $this->data['property_id'] = $property_id = trim($_GET['property_id']);  
        }
        
        // check if user exists and accept dialog
        if(!empty($sel))
            if($this->user_m->get($sel)) {
                $switch_messenger = true;
            }
        
        // Dialogs
        if($switch_messenger && !empty($sel)) { 
            $_dialog = $this->messenger->get_dialog($sel, $property_id);
            $this->data['dialog'] = $this->messenger->_generate_diolog($_dialog, $this->data['lang_code'], TRUE);
            $this->data['speakers'] = $this->messenger->_generate_speakers($sel, $this->data['lang_code'], TRUE);
            if(!empty($this->data['dialog']))
                $this->data['latest_id'] = $this->data['dialog'][end(array_keys($this->data['dialog']))]->id;  
            
            /* Fetch estate data */
            $this->load->model('estate_m');
            $estate_data = $this->estate_m->get_array($property_id, TRUE, array('language_id'=>$this->data['lang_id']));
            foreach($estate_data as $key=>$val)
            {
                $this->data['estate_data_'.$key] = $val;
            }

            $json_obj = json_decode($estate_data['json_object']);

            $categories_hidden_preview = array();

            if(!empty($json_obj))
            foreach($json_obj as $key_json=>$val)
            {
                $j_parts = explode('_',$key_json);
                $key = $j_parts[1];

                if($val != '')
                {
                    if(substr($val, -2) == ' -')$val=substr($val, 0, -2);
                    $this->data['estate_data_option_'.$key] = $val;

                    // Set Category data
                    if(isset($option_categories[$key]) && empty($options[$estate_data['id']][$option_categories[$key]]))
                    {
                        $this->data['category_options_'.$option_categories[$key]][$key]['option_value'] = $val;

    //                    if(!empty($options[$estate_data['id']][$option_categories[$key]]))
    //                    {
    //                        print_r($option_categories[$key]);
    //                        echo $options[$estate_data['id']][$option_categories[$key]];
    //                        echo '<br />';
    //                    }

                        if(!empty($val) && !isset($categories_hidden_preview[$option_categories[$key]]))
                            $this->data['category_options_count_'.$option_categories[$key]]++;

                        if($this->data['category_options_'.$option_categories[$key]][$key]['option_type'] == 'CHECKBOX')
                        {
                            //you can define this via cms_config.php, $config['show_not_available_amenities'] = TRUE;
                            if(config_item('show_not_available_amenities') !== FALSE)
                            {
                                $this->data['category_options_'.$option_categories[$key]][$key]['is_checkbox'][] = array('true'=>'true');
                            }
                            else
                            {
                                if($val == 'true')
                                    $this->data['category_options_'.$option_categories[$key]][$key]['is_checkbox'][] = array('true'=>'true');
                            }

                        }
                        elseif($this->data['category_options_'.$option_categories[$key]][$key]['option_type'] == 'DROPDOWN' || 
                               $this->data['category_options_'.$option_categories[$key]][$key]['option_type'] == 'DROPDOWN_MULTIPLE')
                        {
                            $this->data['category_options_'.$option_categories[$key]][$key]['is_dropdown'][] = array('true'=>'true');
                        }
                        elseif($this->data['category_options_'.$option_categories[$key]][$key]['option_type'] == 'UPLOAD')
                        {
                            $this->data['category_options_'.$option_categories[$key]][$key]['is_upload'][] = array('true'=>'true');
                        }
                        elseif($this->data['category_options_'.$option_categories[$key]][$key]['option_type'] == 'TREE')
                        {
                            $this->data['category_options_'.$option_categories[$key]][$key]['is_tree'][] = array('true'=>'true');
                        }
                        elseif($this->data['category_options_'.$option_categories[$key]][$key]['option_type'] == 'CATEGORY')
                        {
                            if($val == 'true') // hidden
                            {
                                $categories_hidden_preview[$key] = true;
                            }
                        }
                        elseif($this->data['category_options_'.$option_categories[$key]][$key]['option_type'] == 'PEDIGREE')
                        {
                            $this->data['category_options_'.$option_categories[$key]][$key]['is_pedigree'][] = array('true'=>'true');
                        }
                        else
                        {
                            $this->data['category_options_'.$option_categories[$key]][$key]['is_text'][] = array('true'=>'true');
                        }

                        $this->data['category_options_'.$option_categories[$key]][$key]['option_id'] = $key;

                        /* icon */
                        $this->data['category_options_'.$option_categories[$key]][$key]['icon']='';

                        if(!empty($this->data['options_obj_'.$key]->image_filename))
                        {
                            $this->data['category_options_'.$option_categories[$key]][$key]['icon']=
                            '<img src="'.base_url('files/'.$this->data['options_obj_'.$key]->image_filename).'" alt="'.$val.'"/>';
                        }
                        else if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                       '/assets/img/icons/option_id/'.$key.'.png'))
                        {
                            $this->data['category_options_'.$option_categories[$key]][$key]['icon']=
                            '<img src="assets/img/icons/option_id/'.$key.'.png" alt="'.$val.'"/>';
                        }
                    }
                }
            }

            if(!isset($this->data['estate_data_option_10']))$this->data['estate_data_option_10'] = '';
            $url_title = url_title_cro($this->data['estate_data_option_10']);
            if(empty($url_title))$url_title='title_undefined';
            $this->data['estate_data_printurl'] = 
                site_url_q($this->data['listing_uri'].'/'.$estate_data['id'].'/'.$this->data['lang_code'].'/'.$url_title, 'v=print');

            $this->data['estate_data_icon'] = 'assets/img/markers/'.$this->data['color_path'].'marker_blue.png';
            if(isset($this->data['estate_data_option_6']))
            {
                if($this->data['estate_data_option_6'] != '' && $this->data['estate_data_option_6'] != 'empty')
                {
                    if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                   '/assets/img/markers/'.$this->data['color_path'].$this->data['estate_data_option_6'].'.png'))
                    $this->data['estate_data_icon'] = 'assets/img/markers/'.$this->data['color_path'].$this->data['estate_data_option_6'].'.png';

                    if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                       '/assets/img/markers/'.$this->data['color_path'].'selected/'.$this->data['estate_data_option_6'].'.png'))
                    $this->data['estate_data_icon'] = 'assets/img/markers/'.$this->data['color_path'].'selected/'.$this->data['estate_data_option_6'].'.png';
                }
            }

            /* End Fetch estate data */ 
            
        } else {
            $_all_dialogs = $this->messenger->get_dialogs();
            //echo $this->db->last_query();
            $this->data['all_dialogs'] = $this->messenger->_generate_dialogs($_all_dialogs, $this->data['lang_code']);
        }
        
        $this->data['switch_messenger'] = $switch_messenger;
     
        // Load view
		$this->data['subview'] = 'admin/enquire/messenger';
        $this->load->view('admin/_layout_main', $this->data);
    }


}