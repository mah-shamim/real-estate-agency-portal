<?php

class Frontuser_Controller extends MY_Controller 
{
	public function __construct(){
		parent::__construct();
        
        if(config_item('installed') == false)
        {
            redirect('configurator');
            exit();
        }
        
        if(ENVIRONMENT == 'development' || 
           md5($this->input->get('profiler'))=='b78ee15cb3ca6531667d47af5cdc61a1')
        {
            error_reporting(E_ALL | E_STRICT);
            //$this->output->enable_profiler(TRUE);
        }
        
        $this->data['listing_uri'] = config_item('listing_uri');
        if(empty($this->data['listing_uri']))$this->data['listing_uri'] = 'property';
        
        /* Load Helpers */
        $this->load->helper('text');    
        
        /* Load libraries */
        $this->load->library('parser');
        
        $this->load->library('form_validation');
        
        $this->load->library('session');
        $this->load->library('pagination');
        
        /* Load models */
        $this->load->model('language_m');
        $this->load->model('page_m');
        $this->load->model('file_m');
        $this->load->model('user_m');
        $this->load->model('repository_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('slideshow_m');
        
        $this->form_validation->set_error_delimiters('<p class="alert alert-error">', '</p>');
        
        $CI =& get_instance();
        $CI->form_languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
        
        
        // Fetch settings
        $this->load->model('settings_m');
        $this->data['settings'] = $this->settings_m->get_fields();
        
        foreach($this->data['settings'] as $key=>$value)
        {
            if($key == 'address')
            {
                $value = str_replace('"', '\\"', $value);
            }
            
            $this->data['settings_'.$key] = $value;
            
            $this->data['has_settings_'.$key] = array();
            if(!empty($value))
            {
                $this->data['has_settings_'.$key][] = array('count'=>'1');
            }
        }
        
        if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/config/'))
        {
            $this->config->add_config_path(FCPATH.'templates/'.$this->data['settings_template'].'/');
            $this->config->load('template_config');
        }
        
        /* [START] Fetch logo URL */
        $this->data['website_logo_url'] = 'assets/img/logo.png';
        if(isset($this->data['settings']['website_logo']))
        {
            if(is_numeric($this->data['settings']['website_logo']))
            {
                $files_logo = $this->file_m->get_by(array('repository_id' => $this->data['settings']['website_logo']), TRUE);
                if( is_object($files_logo) && file_exists(FCPATH.'files/thumbnail/'.$files_logo->filename))
                {
                    $this->data['website_logo_url'] = base_url('files/'.$files_logo->filename);
                }
            }
        }
        
        /* [END] Fetch logo URL */
                
        if(config_item('secondary_logo_support')){
            /* [START] Fetch logo secondary URL */
            $this->data['website_logo_secondary_url'] = 'assets/img/logo_secondary.png';
            if(isset($this->data['settings']['website_logo_secondary']))
            {
                if(is_numeric($this->data['settings']['website_logo_secondary']))
                {
                    $files_logo = $this->file_m->get_by(array('repository_id' => $this->data['settings']['website_logo_secondary']), TRUE);
                    if( is_object($files_logo) && file_exists(FCPATH.'files/thumbnail/'.$files_logo->filename))
                    {
                        $this->data['website_logo_secondary_url'] = base_url('files/'.$files_logo->filename);
                    }
                }
            }
            /* [END] Fetch logo secondary URL */
        }
        
        /* [START] Fetch favicon URL */
        $this->data['website_favicon_url'] = 'assets/img/favicon.png';
        if(isset($this->data['settings']['website_favicon']))
        {
            if(is_numeric($this->data['settings']['website_favicon']))
            {
                $files_logo = $this->file_m->get_by(array('repository_id' => $this->data['settings']['website_favicon']), TRUE);
                if( is_object($files_logo) && file_exists(FCPATH.'files/thumbnail/'.$files_logo->filename))
                {
                    $this->data['website_favicon_url'] = base_url('files/'.$files_logo->filename);
                }
            }
        }
        /* [END] Fetch favicon URL */
        
        // Extra JS features enabled
        $this->data['has_extra_js'] = array();
        if($this->uri->segment(2) == 'editproperty' ||
           $this->uri->segment(2) == 'myprofile' )
            $this->data['has_extra_js'][] = array('count'=>'1');
        
        // Get page data
        $this->data['lang_code'] = (string) $this->uri->segment(3);

        $this->data['pagination_offset'] = 0;
        
        if(empty($this->data['lang_code']))
        {
            $this->data['lang_code'] = $this->language_m->get_default();
        }
        
        $this->data['lang_id'] = $this->language_m->get_id($this->data['lang_code']);
        
        if(empty($this->data['lang_id']))
            show_404(current_url());

        $this->data['page_current_url'] = site_url($this->uri->uri_string());
        
        // Check if is it RTL
        $this->data['is_rtl'] = array();
        $lang_data = $this->language_m->get($this->data['lang_id']);
        $rtl_test = $this->input->get('test', TRUE);
        if($lang_data->is_rtl == 1 || $rtl_test == 'rtl')
        {
            $this->data['is_rtl'][]= array('count'=>'1');
        }
        
        // Fetch menu
        $this->temp_data['menu'] = $this->page_m->get_nested($this->data['lang_id']);
              
        if (!is_resource($CI->db->conn_id) && !is_object($CI->db->conn_id))
            show_error('Database conenction failed');
            
        // URL-s
        $this->data['ajax_load_url'] = site_url('frontend/ajax/'.$this->data['lang_code'].'/');
        
        // Load custom translations
        $this->config->set_item('language', $this->language_m->get_name($this->data['lang_code']));
        //$this->lang->load('frontend_base');
        
        if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/language/'.$this->language_m->get_name($this->data['lang_code'])))
        {
            $this->lang->load('frontend_template', '', FALSE, TRUE, FCPATH.'templates/'.$this->data['settings_template'].'/');
        }
        else
        {
            $this->config->set_item('language', 'english');
            $this->lang->load('frontend_template', '', FALSE, TRUE, FCPATH.'templates/'.$this->data['settings_template'].'/');
            //$this->config->set_item('language', $this->language_m->get_name($this->data['lang_code']));
        }
        
        if(!file_exists(APPPATH.'language/'.$this->language_m->get_name($this->data['lang_code']).'/form_validation_lang.php'))
        {
            $this->config->set_item('language', 'english');
        }
        
        // Define language for template
        $lang = $this->lang->get_array();
        foreach($lang as $key=>$row)
        {
            $this->data['lang_'.$key] = $row;
        }
        
        // Color definition for demo purposes
        $this->data['color'] = '';
        $this->data['color_path'] = '';
        $this->data['has_color'] = array();
        $this->data['has_color_picker'] = array();
        
        $color = $this->input->get_post('color', TRUE);
        $color_set_session = false;
        
        if(empty($color))
        {
            $color = $this->session->userdata('color');
        }
        else
        {
            $color_set_session = true;
        }

        if(config_db_item('color') !== FALSE && empty($color))
        {
            $color = config_db_item('color');
        }
        
        if($this->config->item('color_picker') !== FALSE)
        {
            if($this->config->item('color_picker') == TRUE)
            {
                $this->data['has_color_picker'][] = array('selected_color'=>$color);
            }
        }
        
        if( file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/assets/css/styles_'.$color.'.css') &&
            file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/assets/img/markers/'.$color) || $this->data['settings_template']=='selio')
        {
            $this->data['color'] = $color;
            $this->data['color_path'] = $color.'/';
            $this->data['has_color'][] = array('color'=>$color);
            
            if($color_set_session)
                $this->session->set_userdata('color', $color);
        }
        
        // homepage_url
        $this->data['homepage_url'] = site_url('');
        $this->data['homepage_url_lang'] = site_url($this->data['lang_code']);
        
        /* Check login */
        $this->data['is_logged_user'] = array();
        $this->data['is_logged_other'] = array();
        $this->data['not_logged'][] = array('count'=>'1');
        if($this->user_m->loggedin() == TRUE)
        {
            if($this->session->userdata('type') == 'USER')
            {
                $this->data['is_logged_user'][] = array('count'=>'1');
                $this->data['not_logged'] = array();
            }
            else
            {
                $this->data['is_logged_other'][] = array('count'=>'1');
                $this->data['not_logged'] = array();
            }
            
            $this->data['loged_user'] = $this->session->userdata('name_surname');
        }
        
        $this->data['logout_url'] = site_url('frontend/logout/'.$this->data['lang_code']);
        $this->data['login_url'] = site_url('admin/dashboard');

        $this->data['front_login_url'] = site_url('frontend/login/'.$this->data['lang_code']);
        $this->data['myproperties_url'] = site_url('frontend/myproperties/'.$this->data['lang_code']);
        $this->data['myprofile_url'] = site_url('frontend/myprofile/'.$this->data['lang_code']);
        $this->data['myreservations_url'] = site_url('frontend/myreservations/'.$this->data['lang_code']);
        $this->data['myresearch_url'] = site_url('fresearch/myresearch/'.$this->data['lang_code']);
        $this->data['api_private_url'] = site_url('privateapi');
        $this->data['myrates_url'] = site_url('frontend/myrates/'.$this->data['lang_code']);
        $this->data['myhistory_url'] = site_url('frontend/myhistory/'.$this->data['lang_code']);
        $this->data['myfavorites_url'] = site_url('ffavorites/myfavorites/'.$this->data['lang_code']);
        $this->data['mymessages_url'] = site_url('fmessages/mymessages/'.$this->data['lang_code']);
        
        $this->data['search_query'] = $this->input->get('search');
        
        $this->data['page_edit_url']='';
        $this->data['category_edit_url'] = '';
        
 /* edit link */
            // ADMIN
            if($this->session->userdata('type') == 'ADMIN' ){
                if($CI->uri->segment(1) == 'property') {
                    $this->data['page_edit_url']=  site_url('admin/estate/edit/'.$CI->uri->segment(2));
                } else if($CI->uri->segment(1) == 'showroom') {
                    $this->data['page_edit_url']=  site_url('admin/showroom/edit/'.$CI->uri->segment(2));
                }
                else if($CI->uri->segment(1) == 'profile') {
                    $this->data['page_edit_url'] = site_url('admin/user/edit/'.$CI->uri->segment(2)); 
                } else if(!empty( $this->temp_data['page'])&& $this->temp_data['page']->type == 'MODULE_NEWS_POST') {
                    $this->data['page_edit_url']=  site_url('admin/news/edit/'.$CI->uri->segment(2));
                } else if(!empty( $this->temp_data['page'])&& $this->temp_data['page']->type == 'ARTICLE') {
                    $this->data['page_edit_url']=  site_url('admin/page/edit/'.$this->temp_data['page']->id);
                } else {
                    if(!empty($this->temp_data['page'])&&$this->temp_data['page']->id==1&&!$CI->uri->segment(1)){
                        $this->data['page_edit_url'] = site_url('admin/page/edit/'.$this->temp_data['page']->id);
                    } else if(!empty( $this->temp_data['page'])) {
                        $this->data['page_edit_url'] = site_url('admin/page/edit/'.$this->temp_data['page']->id);

                        /* manager category */
                        if($this->temp_data['page']->template=='page_showroom'){
                            if(file_exists(APPPATH.'controllers/admin/showroom.php'))
                            $this->data['category_edit_url'] = site_url('admin/showroom');
                            
                        } elseif($this->temp_data['page']->template=='page_news') {
                            if(file_exists(APPPATH.'controllers/admin/news.php'))
                            $this->data['category_edit_url'] = site_url('admin/news');
                        } elseif($this->temp_data['page']->template=='page_expert') {
                            if(file_exists(APPPATH.'controllers/admin/expert.php'))
                                $this->data['category_edit_url'] = site_url('admin/expert');
                            
                        }
                    }
                } 
             // if AGENT   
            }elseif($this->session->userdata('type') == 'AGENT_ADMIN'){
                if($CI->uri->segment(1) == 'property'){
                    $this->data['estate'] = $this->estate_m->get_dynamic($id);
                    if($this->data['estate']->agent == $this->session->userdata('id')) 
                        $this->data['page_edit_url']=  site_url('admin/estate/edit/'.$CI->uri->segment(2));
                } else if($CI->uri->segment(1) == 'profile') {
                    if($this->session->userdata('id') == $CI->uri->segment(2))
                        $this->data['page_edit_url'] = site_url('admin/user/edit/'.$CI->uri->segment(2)); 
                }
             // if USER VISITOR   
            } elseif($this->session->userdata('type') == 'USER') {
                if($CI->uri->segment(1) == 'property') {
                    $this->data['estate'] = $this->estate_m->get_dynamic($CI->uri->segment(2));
                    if($this->data['estate']->agent == $this->session->userdata('id')) 
                        $this->data['page_edit_url'] = site_url('frontend/editproperty/'.$this->data['lang_code'].'/'.$CI->uri->segment(2));
                } else if($CI->uri->segment(1) == 'profile') {
                    if($this->session->userdata('id') == $CI->uri->segment(2))
                        $this->data['page_edit_url'] = site_url('frontend/myprofile/'.$this->data['lang_code']); 
                }
            }
        /* end edit link */
        
        // Get slideshow
        $rep_slideshow_images = $this->slideshow_m->get_repository_images();
        
        $this->data['slideshow_images'] = array();
        foreach($rep_slideshow_images as $key=>$file)
        {
            $slideshow_image = array();
            $slideshow_image['num'] = $key;
            $slideshow_image['url'] = base_url('files/'.$file->filename);
            $slideshow_image['first_active'] = '';
            if($key==0)$slideshow_image['first_active'] = 'active';
            
            $this->data['slideshow_images'][] = $slideshow_image;
        }
        // End Get slideshow
        
        /* [CAPTCHA Helper] */
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
                'img_width' => 100,
                'img_height' => 30,
                'expiration' => 7200
                );

            $this->data['captcha'] = create_captcha($vals);
            $this->data['captcha_hash'] = $captcha_hash;
        }
        /* [/CAPTCHA Helper] */
        
        if((string) $this->uri->segment(2) != 'treealerts' || 
            md5($this->uri->segment(4).config_item('encryption_key')) != $this->uri->segment(5))
        {
            $this->check_login();
        }
        
        $this->load_head_data();
        
		// Load stuff
        //$this->load->model('page_m');
        
		// Fetch navigation
		//$this->data['menu'] = $this->page_m->get_nested();
        //$this->data['news_archive_link'] = $this->page_m->get_archive_link();
		//$this->data['meta_title'] = config_item('site_name');
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
            
            if($this->session->userdata('type') == 'USER' || $this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT')
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
                
//                if($this->temp_data['page']->repository_id == $file->repository_id)
//                {
//                    $this->data['page_images'][] = $file;
//                }
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                $this->data['documents_'.$file->repository_id][] = $file;
//                if($this->temp_data['page']->repository_id == $file->repository_id)
//                {
//                    $this->data['page_documents'][] = $file;
//                }
            }
            
            $this->data['files_'.$file->repository_id][] = $file;

//            if($this->temp_data['page']->repository_id == $file->repository_id)
//            {
//                $this->data['page_files'][] = $file;
//            }
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
        $estates = $this->estate_m->get_by($where, FALSE, 100);
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
            
            if(sw_count(explode(',', $row->values)) > 0)
            {
                $options = '<option value="">'.$row->option.'</option>';
                $options_li = '';
                $radio_li = '';
                foreach(explode(',', $row->values) as $key2 => $val)
                {
                    $selected = '';
                    if($this->_get_purpose() == strtolower($val))$selected = 'selected';
                    $options.='<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
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
    
    public function generate_results_array(&$results_obj, &$results_array, &$options_name)
    {
        $this->load->model('favorites_m');
        $favorites_list = array();
        
        $_favorites_list = $this->favorites_m->get_by(array('user_id'=>$this->session->userdata('id')));
        foreach ($_favorites_list as $key => $value) {
            $favorites_list[$value->property_id] = true;
        }
        
        foreach($results_obj as $key=>$estate_arr)
        {
            $estate = array();
            $estate['id'] = $estate_arr->id;
            $estate['gps'] = $estate_arr->gps;
            $estate['address'] = $estate_arr->address;
            $estate['date'] = $estate_arr->date;
            $estate['repository_id'] = $estate_arr->repository_id;
            $estate['is_featured'] = $estate_arr->is_featured;
            $estate['counter_views'] = $estate_arr->counter_views;
            $estate['estate_data_id'] = $estate_arr->id;
            $estate['icons'] = array();
            $estate['is_favorite'] = FALSE;
            if(isset($favorites_list[$estate_arr->id]))
                $estate['is_favorite'] = TRUE;
            
            $json_obj = json_decode($estate_arr->json_object);
            
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
                        
                        if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
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
            if(!empty($estate['option_36']))
                $estate['custom_price'].=$this->data['options_prefix_36'].$estate['option_36'].$this->data['options_suffix_36'];
            if(!empty($estate['option_37']))
            {
                if(!empty($estate['custom_price']))
                    $estate['custom_price'].=' / ';
                $estate['custom_price'].=$this->data['options_prefix_37'].$estate['option_37'].$this->data['options_suffix_37'];
            }
                
            if(empty($estate['option_37']) && !empty($estate['option_56']))
            {
                if(!empty($estate['custom_price']))
                    $estate['custom_price'].=' / ';
                $estate['custom_price'].=$this->data['options_prefix_56'].$estate['option_56'].$this->data['options_suffix_56'];
            }
            // [END] custom price field
            
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
            
            // Url to preview
            if(isset($json_obj->field_10))
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_arr->id.'/'.$this->data['lang_code'].'/'.url_title_cro($json_obj->field_10));
            }
            else
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_arr->id.'/'.$this->data['lang_code']);
            }
            
            // Thumbnail
            if(!empty($estate_arr->image_filename))
            {
                $estate['thumbnail_url'] = base_url('files/thumbnail/'.$estate_arr->image_filename);
            }
            else
            {
                $estate['thumbnail_url'] = 'assets/img/no_image.jpg';
            }
            
            // [agent second image]
            if(isset($estate_arr->agent_rep_id))
            if(isset($this->data['images_'.$estate_arr->agent_rep_id]))
            {
                if(isset($this->data['images_'.$estate_arr['agent_rep_id']][1]))
                $estate['agent_sec_img_url'] = $this->data['images_'.$estate_arr['agent_rep_id']][1]->thumbnail_url;
            }
            
            $estate['has_agent_sec_img'] = array();
            if(isset($estate['agent_sec_img_url']))
                $estate['has_agent_sec_img'][] = array('count'=>'1');
            // [/agent second image]
            
            $results_array[] = $estate;
        }
    }
    
}