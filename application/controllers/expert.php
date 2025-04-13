<?php

class Expert extends Frontend_Controller
{

	public function __construct ()
	{
		parent::__construct();
        
        $this->load->model('showroom_m');
        $this->load->model('qa_m');
        $this->load->model('page_m');
	}
    
    public function _remap($method=NULL, $params = array())
    {
        if($method == 'ajax')
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->index();
        }
    }

    public function index()
    {
        $lang_code = (string) $this->uri->segment(3);
        $showroom_id = (string) $this->uri->segment(2);
        $showroom_slug = (string) $this->uri->segment(4);
        
        $lang_id = $this->data['lang_id'];
        
        $option_sum = '';
        
        /* Fetch showroom data */
        
        $this->data['showroom_id'] = $showroom_id;
        
        $showroom_data = $this->user_m->get_array($this->data['showroom_id'], TRUE);
        
        $this->data['expert_data'] = $showroom_data;
        
        /* End Fetch showroom data */ 
        $this->data['showroom_data_address'] = $showroom_data['address'];
        
        // Fetch all files by repository_id
        $rep_file_count = array();
        $this->data['page_documents'] = array();
        $this->data['page_images'] = array();
        $this->data['page_files'] = array();
        
        // Fetch all files by repository_id
        if(isset($this->temp_data['page']->repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$this->temp_data['page']->repository_id));
        foreach($files as $key=>$file)
        {
            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
            $file->url = base_url('files/'.$file->filename);
            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
            {

                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                $this->data['images_'.$file->repository_id][] = $file;
                
                if($showroom_data['repository_id'] == $file->repository_id)
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
        
        /* Fetch estate data end */
        
        if(!empty($showroom_id)){           
            $this->data['page_showroom_title'] = $showroom_data['name_surname'];
            $this->data['page_title'] = $showroom_data['name_surname'];
            $this->data['page_body']  = $showroom_data['description'];
            $this->data['page_description'] = character_limiter(strip_tags($showroom_data['description']), 160);
            $this->data['page_keywords']  = '';

            $this->data['showroom_image_url'] = 'assets/img/no_image.jpg';
            if(isset($this->data['page_images'][0]))
            {
                $this->data['showroom_image_url'] = $this->data['page_images'][0]->thumbnail_url;
                unset($this->data['page_images'][0]);
            }
        }
        else
        {
            show_404(current_url());
        }
        
        /* Get all estates data */
        $estates = $this->estate_m->get_by(array('is_activated' => 1));
        $options = $this->option_m->get_options($this->data['lang_id']);
        $options_name = $this->option_m->get_lang(NULL, FALSE, $this->data['lang_id']);
        
        $this->data['all_estates'] = array();
        $this->data['agent_estates'] = array();
        foreach($estates as $key=>$estate_obj)
        {
            $estate = array();
            $estate['id'] = $estate_obj->id;
            $estate['gps'] = $estate_obj->gps;
            $estate['address'] = $estate_obj->address;
            $estate['date'] = $estate_obj->date;
            $estate['is_featured'] = $estate_obj->is_featured;
                        
            foreach($options_name as $key2=>$row2)
            {
                $key1 = $row2->option_id;
                $estate['has_option_'.$key1] = array();
                
                if(isset($options[$estate_obj->id][$row2->option_id]))
                {
                    $row1 = $options[$estate_obj->id][$row2->option_id];
                    $estate['option_'.$key1] = $row1;
                    $estate['option_chlimit_'.$key1] = character_limiter(strip_tags($row1), 80);
                    
                    if(!empty($row1))
                        $estate['has_option_'.$key1][] = array('count'=>sw_count($row1));
                }
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
            
//            if($this->data['property_id'] == $estate['id']){
//            if(isset($estate['option_6']))
//            {
//                if($estate['option_6'] != '' && $estate['option_6'] != 'empty')
//                {
//                    if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
//                                   '/assets/img/markers/'.$this->data['color_path'].'selected/'.$estate['option_6'].'.png'))
//                    $estate['icon'] = 'assets/img/markers/'.$this->data['color_path'].'selected/'.$estate['option_6'].'.png';
//                }
//            }
//            }
            
            // Url to preview
            if(isset($options[$estate_obj->id][10]))
            {
                $estate['url'] = site_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$this->data['lang_code'].'/'.url_title_cro($options[$estate_obj->id][10]));
            }
            else
            {
                $estate['url'] = site_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$this->data['lang_code']);
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

            $this->data['all_estates'][] = $estate;
        }
        
        $this->data['all_estates_center'] = calculateCenter($estates);
        
        /* End get all estates data */

        // Get slideshow
        $files = $this->file_m->get();
        $rep_file_count = array();
        $this->data['slideshow_images'] = array();
        $num=0;
        foreach($files as $key=>$file)
        {
            if($showroom_data['repository_id'] == $file->repository_id)
            {
                $slideshow_image = array();
                $slideshow_image['num'] = $num;
                $slideshow_image['url'] = base_url('files/'.$file->filename);
                $slideshow_image['first_active'] = '';
                if($num==0)$slideshow_image['first_active'] = 'active';
                
                $this->data['slideshow_images'][] = $slideshow_image;
                $num++;
            }
        }
        // End Get slideshow
        
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
            'question' => array('field'=>'question', 'label'=>'lang:Question', 'rules'=>'trim|required|xss_clean')
        );
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data_t = $this->page_m->array_from_post(array('firstname', 'email', 'phone', 'question'));
            
            // Save enquire to database
            $this->load->model('enquire_m');
            
            $data = array();
            $data['is_readed'] = 0;
            $data['date'] = date('Y-m-d H:i:s');
            $data['type'] = 'QUESTION';
            $data['answer_user_id'] = $showroom_id;
            $data['parent_id'] = 0;
            
            $data_lang = array();
            $data_lang['question_'.$lang_id] = $data_t['question'];
            
            $id = $this->qa_m->save_with_lang($data, $data_lang, NULL);

            $data['name_surname'] = $data_t['firstname'];
            $data['phone'] = $data_t['phone'];
            $data['mail'] = $data_t['email'];
            
            // Send email
            $this->load->library('email');
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
                
            $to_mail = '';
            
            if(!empty($showroom_data['contact_email']))$to_mail = $showroom_data['contact_email'];
            
            if(empty($to_mail))$to_mail = $this->data['settings_email'];
            
            $this->email->from($this->data['settings_noreply'], lang_check('Web page'));
            $this->email->to($to_mail);
            
            $this->email->subject(lang_check('Expert question from real-estate web'));
            
            $data['answer_user_id'] = $showroom_id;
            
            $message='';
            foreach($data as $key=>$value){
            	$message.="$key:\n$value\n";
            }
            
            $message = $this->load->view('email/expert_message', array('data'=>$data), TRUE);
            
            $this->email->message($message);
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
        $this->data['form_error_question'] = form_error('question')==''?'':'error';
        
        // Form values
        $this->data['form_value_firstname'] = set_value('firstname', '');
        $this->data['form_value_email'] = set_value('email', '');
        $this->data['form_value_phone'] = set_value('phone', '');
        $this->data['form_value_question'] = set_value('question', '');
        
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
            
            if(sw_count(explode(',', $row->values)) > 0)
            {
                $options = '<option value="">'.$row->option.'</option>';
                $options_li = '';
                foreach(explode(',', $row->values) as $key2 => $val)
                {
                    $options.='<option value="'.$val.'">'.$val.'</option>';
                    
                    $active = '';
                    $options_li.= '<li class="'.$active.' cat_'.$key2.'"><a href="#">'.$val.'</a></li>';
                }
                $this->data['options_values_'.$row->option_id] = $options;
                $this->data['options_values_li_'.$row->option_id] = $options_li;
            }
        }
        
        /* Define purpose */
        $this->data['is_purpose_rent'] = array();
        $this->data['is_purpose_sale'] = array();
        $this->data['is_purpose_sale'][] = array('count'=>'1');
        
        if(isset($this->data['estate_data_option_4'])){
            if(stripos($this->data['estate_data_option_4'], lang_check('Rent')) !== FALSE)
            {
                $this->data['is_purpose_rent'][] = array('count'=>'1');
            }
            if(stripos($this->data['estate_data_option_4'], lang_check('Sale')) !== FALSE)
            {
                $this->data['is_purpose_sale'][] = array('count'=>'1');
            }
        }
        /* End define purpose */
        
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
        
        /* {MODULE_Q&A} */

        // Fetch all questions
        $this->data['expert_module_all'] = $this->qa_m->get_lang(NULL, FALSE, $lang_id, array('type'=>'QUESTION', 'is_readed'=>1, 'answer_user_id'=> $showroom_data['id']), null, '', 'date_publish DESC');
        
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
        
        $output = $this->parser->parse($this->data['settings_template'].'/expert.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
    public function ajax ($lang_code, $page_id)
    {
        $lang_id = $this->data['lang_id'];
        
        $search_query = $this->input->post('search', TRUE);

        // Fetch all files by repository_id
        $rep_file_count = array();
        $this->data['page_documents'] = array();
        $this->data['page_images'] = array();
        $this->data['page_files'] = array();
        
        $page_data = $this->page_m->get($page_id, TRUE);
        
        $where_in = array($page_data->repository_id);
        
        $all_experts = $this->user_m->get_by(array('qa_id !='=>0, 'type !=' => 'USER'));
        foreach($all_experts as $row)
        {
            $where_in[] = $row->repository_id;
        }
        // Fetch all files by repository_id
        $files = $this->file_m->get_where_in($where_in);
        
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
        
        $pagination_offset = $this->uri->segment(6);
        if(empty($pagination_offset))
            $pagination_offset=0;
        
        // Fetch all pages
        $this->data['categories_expert'] = $this->qa_m->get_no_parents_expert_category($lang_id);
        $this->data['expert_module_all'] = $this->qa_m->get_lang(NULL, FALSE, $lang_id, array_merge($cat_merge, array('type'=>'QUESTION', 'is_readed'=>1)), null, '', 'date_publish DESC', $search_query);
        
        /* Pagination configuration */ 
        $config_2['base_url'] = site_url('expert/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id'].'/'.$category_id.'/');
        //$config_2['first_url'] = site_url($this->uri->uri_string());
        $config_2['total_rows'] = sw_count($this->data['expert_module_all']);
        $config_2['per_page'] = config_item('per_page');
        $config_2['uri_segment'] = 6;
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
                                                          $config_2['per_page'], $pagination_offset, 'date_publish DESC', $search_query);
        
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
            
            if(isset($this->data['images_'.$expert_obj->repository_id]))
            {
                $agent['image_url'] = $this->data['images_'.$expert_obj->repository_id][0]->thumbnail_url;
            }
            else
            {
                $agent['image_url'] = 'assets/img/no_image.jpg';
            }

            $this->data['all_experts'][$expert_obj->id] = $agent;
        }
        
        /* {/MODULE_Q&A} */
        
        $output = $this->parser->parse($this->data['settings_template'].'/results_expert.php', $this->data, TRUE);
        $output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
        
        echo json_encode(array('print' => $output, 'lang_id'=>$lang_id, 'total_rows'=>$config_2['total_rows']));
        exit();
    }
    

}