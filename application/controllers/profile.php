<?php

class Profile extends Frontend_Controller
{

	public function __construct ()
	{
		parent::__construct();
	}
    
    public function _remap($method)
    {
        $param_offset = 2;
        // Default to index
        if (is_numeric($method) || !method_exists($this, $method))
        {
            // We need one more param
            $param_offset = 1;
            $method = 'index';
        }
        
        // Since all we get is $method, load up everything else in the URI
        $params = array_slice($this->uri->rsegment_array(), $param_offset);
        // Call the determined method with all params
        call_user_func_array(array($this, $method), $params);
    }
    
    private function _get_purpose()
    {
        if(isset($this->data['is_purpose_sale'][0]['count']))
        {
            return lang('Sale');
        }
        
        if(isset($this->data['is_purpose_rent'][0]['count']))
        {
            return lang('Rent');
        }
        
        return lang('Sale');
    }
    
    public function ajax($lang_code, $user_id, $pagination_offset = 0)
    {
        // Prevent direct access for google and simmilar
        if(!isset($_POST['page_num']))
            redirect('');
        
        /* [Get agent estates] */
        
        $where = array();
        $where['language_id'] = $this->data['lang_id'];
        $where['is_activated'] = 1;
        
        if(isset($this->data['settings_listing_expiry_days']))
        {
            if(is_numeric($this->data['settings_listing_expiry_days']) && $this->data['settings_listing_expiry_days'] > 0)
            {
                 $where['property.date_modified >']  = date("Y-m-d H:i:s" , time()-$this->data['settings_listing_expiry_days']*86400);
            }
        }
        
        
        $search_array = array();
        
        /* Fetch options names */
        $options_name = $this->option_m->get_lang(NULL, FALSE, $this->data['lang_id']);
        $option_categories = array();
        foreach($options_name as $key=>$row)
        {
            $this->data['options_name_'.$row->option_id] = $row->option;
            $this->data['options_suffix_'.$row->option_id] = $row->suffix;
            $this->data['options_prefix_'.$row->option_id] = $row->prefix;
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_name'] = $row->option;
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_type'] = $row->type;
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_suffix'] = $row->suffix;
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_prefix'] = $row->prefix;
            
            $this->data['category_options_'.$row->parent_id][$row->option_id]['is_checkbox'] = array();
            $this->data['category_options_'.$row->parent_id][$row->option_id]['is_dropdown'] = array();
            $this->data['category_options_'.$row->parent_id][$row->option_id]['is_text'] = array();
            
            $option_categories[$row->option_id] = $row->parent_id;
        }
        /* End fetch options names */
        
        /* [Pagination configuration] */ 
        $config_2['base_url'] = site_url('profile/ajax/'.$this->data['lang_code'].'/'.$user_id);
        $config_2['per_page'] = 9;
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
        /* [/Pagination configuration] */ 
        
        if(config_db_item('per_page_profile') !== FALSE)
            $config_2['per_page'] = config_db_item('per_page_profile');
            
        // [Detect if user access via this agent link directly]
        $search_agent_id = $user_id;
        if(config_db_item('agent_profile_direct') === TRUE)
        {
            $search_agent_id = NULL;
        }
        // [/Detect if user access via this agent link directly]
        
        $this->data['agent_estates_total'] = $this->estate_m->count_get_by($where, FALSE, NULL, NULL, NULL, $search_array, NULL,  $search_agent_id);

        $config_2['total_rows'] = $this->data['agent_estates_total'];
        
        
        $estates = $this->estate_m->get_by($where, FALSE, $config_2['per_page'], NULL, $pagination_offset, array(), NULL,  $search_agent_id);
        $this->data['agent_estates'] = array();

        $this->generate_results_array($estates, $this->data['agent_estates'], $options_name); 

        $pagination_2 = new CI_Pagination($config_2);
        $this->data['pagination_links_agent'] = $pagination_2->create_links();
        /* [/Get agent estates] */
        
        $output = $this->parser->parse($this->data['settings_template'].'/results_profile.php', $this->data, TRUE);
        $output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
        
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        echo json_encode(array('print' => $output, 'lang_id'=>$this->data['lang_id'], 'total_rows'=>$config_2['total_rows']));
        exit();
    }

    public function index()
    {
        $lang_code = (string) $this->uri->segment(3);
        $user_id = (string) $this->uri->segment(2);
        
        $lang_id = $this->data['lang_id'];
        
        $option_sum = '';
        
        /* Fetch estate data */
        
        $this->data['user_id'] = $user_id;
        
        /* Fetch agent */
        
        $agent = $this->user_m->get_array($this->data['user_id']);
        
        if(sw_count($agent))
        {
            $this->data['agent_name_surname'] = $agent['name_surname'];
            $this->data['agent_phone'] = $agent['phone'];
            $this->data['agent_mail'] = $agent['mail'];
            $this->data['agent_address'] = $agent['address'];
            $this->data['agent_id'] = $agent['id'];
            $this->data['agent_name_title'] = url_title_cro($agent['name_surname']);
            $this->data['agent_url'] = slug_url('profile/'.$agent['id'].'/'.$this->data['lang_code'].'/'.$this->data['agent_name_title']);
        
            $this->data['page_navigation_title'] = $this->data['agent_name_surname'];
            $this->data['page_title'] = $this->data['agent_name_surname'];
            $this->data['page_body']  = $agent['description'];
            $this->data['page_description'] = character_limiter(strip_tags($agent['description']), 160);
            
            $this->data['agent_profile'] = $agent;
        }
        else
        {
            show_404(current_url());
        }
        
        $this->data['has_agent'] = array();
        if(sw_count($agent))
            $this->data['has_agent'][] = array('count'=>sw_count($agent));
            
        // [Detect if user access via this agent link directly]
        if(config_db_item('agent_profile_direct') === TRUE)
        {
            $agent_direct = $this->session->userdata('agent_direct');
            $last_activity = $this->session->userdata('last_activity');

            if(empty($agent_direct) || TRUE)
            {
                $this->session->set_userdata('agent_direct', $agent);
                $agent_direct = $agent;
            }
        }
        // [/Detect if user access via this agent link directly]
        
        /* Fetch options names */
        $options_name = $this->option_m->get_lang(NULL, FALSE, $lang_id);
        $option_categories = array();
        foreach($options_name as $key=>$row)
        {
            $this->data['options_name_'.$row->option_id] = $row->option;
            $this->data['options_suffix_'.$row->option_id] = $row->suffix;
            $this->data['options_prefix_'.$row->option_id] = $row->prefix;
            $this->data['options_obj_'.$row->option_id] = $row;
            $this->data['options_values_arr_'.$row->option_id] = explode(',',$row->values);
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_name'] = $row->option;
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_type'] = $row->type;
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_suffix'] = $row->suffix;
            $this->data['category_options_'.$row->parent_id][$row->option_id]['option_prefix'] = $row->prefix;
            
            $this->data['category_options_'.$row->parent_id][$row->option_id]['is_checkbox'] = array();
            $this->data['category_options_'.$row->parent_id][$row->option_id]['is_dropdown'] = array();
            $this->data['category_options_'.$row->parent_id][$row->option_id]['is_text'] = array();
            
            $option_categories[$row->option_id] = $row->parent_id;
        }
        /* End fetch options names */
        
        $where_in = array($this->temp_data['page']->repository_id);
        
        if(!empty($agent['repository_id']))
            $where_in[] = $agent['repository_id'];
        
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
        foreach($files as $key=>$file)
        {
            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
            $file->url = base_url('files/'.$file->filename);
            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
            {
                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                $this->data['images_'.$file->repository_id][] = $file;
                
                if($agent['repository_id'] == $file->repository_id)
                {
                    $this->data['page_images'][] = $file;
                }
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                $this->data['documents_'.$file->repository_id][] = $file;
                if($agent['repository_id'] == $file->repository_id)
                {
                    $this->data['page_documents'][] = $file;
                }
            }
        }
        /* Fetch estate data end */
        
        // Thumbnail agent
        if(sw_count($agent) && !empty($agent['image_user_filename']) && file_exists(FCPATH.'files/thumbnail/'.$agent['image_user_filename']))
        {
            $this->data['agent_image_url'] = base_url('files/thumbnail/'.$agent['image_user_filename']);
        }
        else
        {
            $this->data['agent_image_url'] = 'assets/img/user-agent.png';
        }
        
        if(sw_count($agent) && !empty($agent['image_agency_filename']))
        {
            $this->data['agency_image_url'] = base_url('files/thumbnail/'.$agent['image_agency_filename']);
        }

        /* Get all estates data */
        $where = array();
        $where['is_activated'] = 1;
        $where['language_id']  = $lang_id;
        
        if(isset($this->data['settings_listing_expiry_days']))
        {
            if(is_numeric($this->data['settings_listing_expiry_days']) && $this->data['settings_listing_expiry_days'] > 0)
            {
                 $where['property.date_modified >']  = date("Y-m-d H:i:s" , time()-$this->data['settings_listing_expiry_days']*86400);
            }
        }
        
        /* [Get all estates data] */
        $order = 'property.id DESC';
        $search_array = array();
        
        $this->data['all_estates'] = array();
                            
        $featured_order = '';
        if(config_db_item('featured_on_top_disabled') == FALSE ){
            $featured_order = 'property.is_featured DESC, ';
        }
        
        $results_obj = $this->estate_m->get_by($where, false, 100, $featured_order.$order, 
                                               0, $search_array);
        $this->generate_results_array($results_obj, $this->data['all_estates'], $options_name); 
        $this->data['all_estates_center'] = calculateCenter($this->data['all_estates']);
        
        $this->data['has_no_all_estates'] = array();
        if(sw_count($this->data['all_estates']) == 0)
        {
            $this->data['has_no_all_estates'][] = array('count'=>sw_count($this->data['all_estates']));
        }
        /* [/Get all estates data] */

        /* [Get agent estates] */
        $pagination_offset = 0;
        
        /* Pagination configuration */ 
        $config_2['base_url'] = site_url('profile/ajax/'.$this->data['lang_code'].'/'.$agent['id']);
        $config_2['per_page'] = 9;
        $config_2['uri_segment'] = 6;
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
        
        if(config_db_item('per_page_profile') !== FALSE)
            $config_2['per_page'] = config_db_item('per_page_profile');
            
        // [Detect if user access via this agent link directly]
        $search_agent_id = $agent['id'];
        if(config_db_item('agent_profile_direct') === TRUE)
        {
            $search_agent_id = NULL;
        }
        // [/Detect if user access via this agent link directly]
        
        $this->data['agent_estates_total'] = $this->estate_m->count_get_by($where, FALSE, NULL, NULL, NULL, $search_array, NULL,  $search_agent_id);

        $config_2['total_rows'] = $this->data['agent_estates_total'];
        
        
        $estates = $this->estate_m->get_by($where, FALSE, $config_2['per_page'], NULL, $pagination_offset, array(), NULL,  $search_agent_id);
        $this->data['agent_estates'] = array();
        
        $this->generate_results_array($estates, $this->data['agent_estates'], $options_name); 

        $pagination_2 = new CI_Pagination($config_2);
        $this->data['pagination_links_agent'] = $pagination_2->create_links();
        /* [/Get agent estates] */
        
        // Get slideshow
//        $files = $this->file_m->get();
//        $rep_file_count = array();
//        $this->data['slideshow_property_images'] = array();
//        $num=0;
//        foreach($files as $key=>$file)
//        {
//            if($agent['repository_id'] == $file->repository_id)
//            {
//                $slideshow_image = array();
//                $slideshow_image['num'] = $num;
//                $slideshow_image['url'] = base_url('files/'.$file->filename);
//                $slideshow_image['first_active'] = '';
//                if($num==0)$slideshow_image['first_active'] = 'active';
//                
//                $this->data['slideshow_property_images'][] = $slideshow_image;
//                $num++;
//            }
//        }
        // End Get slideshow
        
        /* [Get last n properties] */
        $last_n = 4;
        if(config_item('last_estates_limit'))
            $last_n = config_item('last_estates_limit');
        
        $last_n_estates = $this->estate_m->get_by(array('is_activated' => 1, 'is_visible'=>1, 'language_id'=>$lang_id), FALSE, $last_n, 'id DESC');
        
        $this->data['last_estates_num'] = $last_n;
        $this->data['last_estates'] = array();
        $this->generate_results_array($last_n_estates, $this->data['last_estates'], $options_name); 
        /* [/Get last n properties] */
        
        /* Helpers */
        $this->data['year'] = date('Y');
        /* End helpers */
        
        /* Widgets functions */
        $this->data['print_menu'] = get_menu($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_lang_menu'] = get_lang_menu($this->language_m->get_array_by(array('is_frontend'=>1)), $this->data['lang_code']);
        $this->data['page_template'] = $this->temp_data['page']->template;
        /* End widget functions */

        /* Validation for contact */
        $rules = array(
            'firstname' => array('field'=>'firstname', 'label'=>'lang:FirstLast', 'rules'=>'trim|required|xss_clean'),
            'email' => array('field'=>'email', 'label'=>'lang:Email', 'rules'=>'trim|required|valid_email|xss_clean'),
            'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|required|xss_clean'),
            'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim|required|xss_clean'),
            'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|required|xss_clean')
        );
        
        if(config_item('captcha_disabled') === FALSE)
            $rules['captcha'] = array('field'=>'captcha', 'label'=>'lang:Captcha', 'rules'=>'trim|required|callback_captcha_check|xss_clean');
        
        if(config_item('recaptcha_site_key') !== FALSE)
                $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>'lang:Recaptcha', 
                                                        'rules'=>'trim|required|callback_captcha_check|xss_clean');
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data_t = $this->page_m->array_from_post(array('firstname', 'email', 'phone', 'message', 'address'));
            
            // Save enquire to database
            $this->load->model('enquire_m');
            $data = array();
            $data['name_surname'] = $data_t['firstname'];
            $data['phone'] = $data_t['phone'];
            $data['mail'] = $data_t['email'];
            $data['message'] = $data_t['message'];
            $data['address'] = $data_t['address'];
            $data['agent_id'] = $agent['id'];
            $data['readed'] = 0;
            $data['property_id'] = NULL;
            $data['date'] = date('Y-m-d H:i:s');
            $this->enquire_m->save($data);
            
            $this->session->set_flashdata('email_sent', 'email_sent_true');
            
            // Send email
            $this->load->library('email');
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $to_mail = '';
            
            if(sw_count($agent))$to_mail = $agent['mail'];
            
            if(empty($to_mail))$to_mail = $this->data['settings_email'];
            
            $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
            $this->email->to($to_mail);
            
            $this->email->subject(lang_check('Message from real-estate web'));
            
            $message='';
            foreach($data as $key=>$value){
            	$message.="$key:\n$value\n";
            }
            
            $message = $this->load->view('email/profile_message', array('data'=>$data), TRUE);
            
            $this->email->message($message);
            
            if(ENVIRONMENT != 'development')
            if ( ! $this->email->send())
            {
                $this->session->set_flashdata('email_sent', 'email_sent_false');
            }
            else
            {
                $this->session->set_flashdata('email_sent', 'email_sent_true');
            }
            
            redirect($this->uri->uri_string());
        }
        
        $this->data['validation_errors'] = validation_errors();
        
        $this->data['form_sent_message'] = '';
        if($this->session->flashdata('email_sent'))
        {
            if($this->session->flashdata('email_sent') == 'email_sent_true')
            {
                $this->data['form_sent_message'] = '<p class="alert alert-success">'.lang_check('message_sent_successfully').'</p>';
                
//                $this->data['form_sent_message'].=' <script type="text/javascript">
//                                                    /* <![CDATA[ */
//                                                    var google_conversion_id = 973185194;
//                                                    var google_conversion_language = "en";
//                                                    var google_conversion_format = "3";
//                                                    var google_conversion_color = "ffffff";
//                                                    var google_conversion_label = "7RR9CJ6C6AcQqsGG0AM";
//                                                    var google_remarketing_only = false;
//                                                    /* ]]> */
//                                                    </script>
//                                                    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
//                                                    </script>
//                                                    <noscript>
//                                                    <div style="display:inline;">
//                                                    <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/973185194/?label=7RR9CJ6C6AcQqsGG0AM&amp;guid=ON&amp;script=0"/>
//                                                    </div>
//                                                    </noscript>';
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
        $this->data['form_error_address'] = form_error('address')==''?'':'error';
        $this->data['form_error_captcha'] = form_error('captcha')==''?'':'error';
        
        // Form values
        $this->data['form_value_firstname'] = set_value('firstname', '');
        $this->data['form_value_email'] = set_value('email', '');
        $this->data['form_value_phone'] = set_value('phone', '');
        $this->data['form_value_message'] = set_value('message', '');
        $this->data['form_value_address'] = set_value('address', '');

        /* End validation for contact */
        
        /* Fetch options data */
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
                        $options.='<option value="'.$val.'" '.$selected.'>'.lang_check('Any').'</option>';
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
        /* {MOULE_ADS} */
        $this->load->model('ads_m');
        $this->data['ads'] = array();
        
        foreach($this->ads_m->ads_types as $type_key=>$type_name)
        {
            $ads_by_type = $this->ads_m->get_by(array('type'=>$type_key, 'is_activated'=>1));
            
            $num_ads = sw_count($ads_by_type);

            $this->data['has_ads_'.$type_name] = array();
            if(isset($ads_by_type[0]))
            if($num_ads > 0)
            {
                $rand_ad_key = rand(0, $num_ads-1);
                
                if(isset($ads_by_type[$rand_ad_key]))
                {
                    $rand_image=0;
                    if($ads_by_type[$rand_ad_key]->is_random)
                        $rand_image = rand(0, sw_count($this->data['images_'.$ads_by_type[$rand_ad_key]->repository_id])-1);
                    
                    $this->data['random_ads_'.$type_name.'_link'] = $ads_by_type[$rand_ad_key]->link;
                    $this->data['random_ads_'.$type_name.'_repository'] = $ads_by_type[$rand_ad_key]->repository_id;
                    $this->data['random_ads_'.$type_name.'_image'] = $this->data['images_'.$ads_by_type[$rand_ad_key]->repository_id][$rand_image]->url;
                    $this->data['has_ads_'.$type_name][] = array('count' => $num_ads);
                }
            }
        }
        /* {/MOULE_ADS} */
        
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
        
        $output = $this->parser->parse($this->data['settings_template'].'/profile.php', $this->data, TRUE);
        $output =  str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
        
        if(config_item('litecache_enabled') === TRUE)
        {
            $this->litecache->save_cache($output);
        }
        
        echo $output;
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

}