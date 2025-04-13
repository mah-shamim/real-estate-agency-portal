<?php

class Treefield extends Frontend_Controller
{

	public function __construct ()
	{
		parent::__construct();
        
        $this->load->model('treefield_m');
	}
    
    public function _remap($method)
    {
        $this->index();
    }
    
    private function _get_purpose()
    {
        if(config_item('all_results_default') === TRUE)
        {
            $this->data['purpose_defined'] = '';
            return '';
        }
        
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

    public function index()
    {
        $model_s = (string) $this->uri->segment(1);
        $lang_code = (string) $this->uri->segment(2);
        $treefield_id = (string) $this->uri->segment(3);

        $lang_id = $this->data['lang_id'];

        $date['treefield_data'] = array();
        
        if(!empty($treefield_id) && is_numeric($treefield_id)){
            $this->data['treefield_data'] = $this->treefield_m->get_lang($treefield_id, TRUE, $lang_id);

            $this->data['page_navigation_title'] = $this->data['treefield_data']->{'value_'.$lang_id};
            $this->data['value_path'] = $this->data['treefield_data']->{'value_path_'.$lang_id};
            $this->data['page_title'] = $this->data['treefield_data']->{'title_'.$lang_id};
            $this->data['page_body']  = $this->data['treefield_data']->{'body_'.$lang_id};
            $this->data['page_address']  = $this->data['treefield_data']->{'address_'.$lang_id};
            $this->data['page_description'] = character_limiter(strip_tags($this->data['treefield_data']->{'description_'.$lang_id}), 160);
            $this->data['keywords'] = character_limiter(strip_tags($this->data['treefield_data']->{'keywords_'.$lang_id}), 160);
            $this->data['page_keywords'] = $this->data['keywords'];
        }
        else
        {
            show_404(current_url());
        }
        
        $field_id = $this->data['treefield_data']->field_id;

        /* Fetch options names */
        //$this->data['options'] = $options = $this->option_m->get_options($lang_id);
        
        
        $options_name = $this->option_m->get_lang(NULL, FALSE, $lang_id);
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
            $this->data['category_options_'.$row->parent_id][$row->option_id]['is_upload'] = array();
            
            $this->data['category_options_count_'.$row->parent_id] = 0;
            
            $option_categories[$row->option_id] = $row->parent_id;
        }
        /* End fetch options names */
        
        $where_in = array($this->data['treefield_data']->repository_id);
        
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
                
                if($this->data['treefield_data']->repository_id == $file->repository_id)
                {
                    $this->data['page_images'][] = $file;
                }
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                $this->data['documents_'.$file->repository_id][] = $file;
                if($this->data['treefield_data']->repository_id == $file->repository_id)
                {
                    $this->data['page_documents'][] = $file;
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
        
        /* Fetch treefield data end */
        
        /* Get last n properties */
        $last_n = 4;
        if(config_item('last_estates_limit'))
            $last_n = config_item('last_estates_limit');
        
        $last_n_estates = $this->estate_m->get_by(array('is_activated' => 1, 'is_visible'=>1, 'language_id'=>$lang_id), FALSE, $last_n, 'id DESC');
        
        $this->data['last_estates_num'] = $last_n;
        $this->data['last_estates'] = array();
        foreach($last_n_estates as $key=>$estate_arr)
        {
            $estate = array();
            $estate['id'] = $estate_arr->id;
            $estate['gps'] = $estate_arr->gps;
            $estate['address'] = $estate_arr->address;
            $estate['date'] = $estate_arr->date;
            $estate['repository_id'] = $estate_arr->repository_id;
            $estate['is_featured'] = $estate_arr->is_featured;
            
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
//            $estate['custom_price'] = '';
//            if(!empty($estate['option_36']))
//                $estate['custom_price'].=$this->data['options_prefix_36'].$estate['option_36'].$this->data['options_suffix_36'];
//            if(!empty($estate['option_37']))
//                $estate['custom_price'].=$this->data['options_prefix_37'].$estate['option_37'].$this->data['options_suffix_37'];
//            if(empty($estate['option_37']) && !empty($estate['option_56']))
//                $estate['custom_price'].=$this->data['options_prefix_56'].$estate['option_56'].$this->data['options_suffix_56'];
            // [END] custom price field
            
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

            $this->data['last_estates'][] = $estate;
        }
        
        /* END Get last n properties */

        // Get slideshow
        //$files = $this->file_m->get();
        $rep_file_count = array();
        $this->data['slideshow_property_images'] = array();
        $num=0;
//        foreach($files as $key=>$file)
//        {
//            if($estate_data['repository_id'] == $file->repository_id)
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

        foreach($this->data['page_images']  as $key=>$file)
        {
            if(isset($estate_data['repository_id'])&&$estate_data['repository_id'] == $file->repository_id)
            {
                $slideshow_image = array();
                $slideshow_image['num'] = $num;
                $slideshow_image['url'] = str_replace(' ', '%20', base_url('files/'.$file->filename));
                $slideshow_image['first_active'] = '';
                if($num==0)$slideshow_image['first_active'] = 'active';
                
                $this->data['slideshow_property_images'][] = $slideshow_image;
                $num++;
            }
        }
        // End Get slideshow
        
        /* Helpers */
        $this->data['year'] = date('Y');
        /* End helpers */
        
        /* Widgets functions */
        $this->data['print_menu'] = get_menu($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_menu_realia'] = get_menu_realia($this->temp_data['menu'], false, $this->data['lang_code']);
        $this->data['print_lang_menu'] = get_lang_menu($this->language_m->get_array_by(array('is_frontend'=>1)), $this->data['lang_code']);
        $this->data['page_template'] = $this->temp_data['page']->template;
        /* End widget functions */

        /* Fetch options data */
        $options_name = $this->option_m->get_lang(NULL, FALSE, $this->data['lang_id']);
        
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
                $post_option[substr($key, strrpos($key, 'tion_')+5)] = $tmp_post;
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

        // add treefield value
        $post_option[$field_id] = $this->data['value_path'].' - ';
        //$post_option[88] = $this->data['value_path'].' - ';

        // [JSON_SEARCH]
        // Example: ?search={"search_option_smart": "zagreb"}
        $search_json = NULL;
        if(isset($_GET['search']))$search_json = json_decode($_GET['search']);
        
        if($search_json !== FALSE && $search_json !== NULL)
        {
            $post_option = array();
            $post_option_sum = ' ';
            
            foreach($search_json as $key=>$val)
            {
                $tmp_post = $val;
                if(!empty($tmp_post) && strrpos($key, 'tion_') > 0){
                    $post_option[substr($key, strrpos($key, 'tion_')+5)] = $tmp_post;
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

            $this->data['search_query'] = '';
            if(!empty($post_option['smart']))
                $this->data['search_query'] = $post_option['smart'];
        }
        
        $this->g_post_option = &$post_option;
        //print_r($this->g_post_option);

        // [/JSON_SEARCH]
        
        // End fetch post values  
        
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
                $options_o = '<option value="">'.$row->option.'</option>';
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
                    
                    $options_o.='<option value="'.$val.'" '.$o_selected.'>'.$val.'</option>';
                    $this->data['options_values_arr_'.$row->option_id][] = $val;
                    
                    $active = '';
                    if($this->_get_purpose() == strtolower($val))$active = 'active';
                    $options_li.= '<li class="'.$active.' cat_'.$key2.'"><a href="#">'.$val.'</a></li>';
                    
                    $radio_li.='<label class="checkbox">
                                <input type="radio" rel="'.$val.'" name="search_option_'.$row->option_id.'" value="'.$key2.'" checked> '.$val.'
                                </label>';
                }
                $this->data['options_values_'.$row->option_id] = $options_o;
                $this->data['options_values_li_'.$row->option_id] = $options_li;
                $this->data['options_values_radio_'.$row->option_id] = $radio_li;
            }
        }
        
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

        /* Define order */
        if(empty($order))$order='id DESC';
        
        $this->data['order_dateASC_selected'] = '';
        if($order=='id ASC')
            $this->data['order_dateASC_selected'] = 'selected';
            
        $this->data['order_dateDESC_selected'] = '';
        if($order=='id DESC')
            $this->data['order_dateDESC_selected'] = 'selected';
        
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

        /* Search */
        $offset_properties = $this->data['pagination_offset'];
        
        $search_array = $search_json;
        
        if(empty($search_array)) {
            $search_array = new stdClass();
        }
        
        if(!empty($this->data['search_query']))
        {
            $search_array->search_option_smart = 
                                $this->data['search_query'];
        }
        

        // add treefield value
        $search_array->{'v_search_option_'.$field_id} = $this->data['value_path'].' - ';
        //$search_array['v_search_option_88'] = $this->data['value_path'].' - ';
        
        if(!empty($lang_purpose))
        {
            $search_array['v_search_option_4'] = 
                                $lang_purpose;
        }
        
        if(!empty($address)){
            $where['property.address']  = $address;
        }

        $config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.is_featured DESC, property.'.$order, 
                                               NULL, $search_array);
        
        /* Pagination in query */
        $this->data['total_rows'] = $config['total_rows'];
        
        $results_obj = $this->estate_m->get_by($where, false, $config['per_page'], 'property.is_featured DESC, property.'.$order, 
                                               $offset_properties, $search_array);


        $this->data['has_no_results'] = array();
        if(sw_count($results_obj) == 0)
            $this->data['has_no_results'][] = array('count'=>sw_count($results_obj));

        /* Get all estates data */
        $this->data['results'] = array();
        $this->generate_results_array($results_obj, $this->data['results'], $options_name); 
        
        /* Pagination load */ 
        $this->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->pagination->create_links();
        /* End Pagination */
        
        /* [Get all estates data] */
        $this->data['all_estates'] = array();
        $results_obj = $this->estate_m->get_by($where, false, 100, 'property.is_featured DESC, '.$order, 
                                               0, $search_array);
        $this->generate_results_array($results_obj, $this->data['all_estates'], $options_name); 
        $this->data['all_estates_center'] = calculateCenter($this->data['all_estates']);
        
        $this->data['has_no_all_estates'] = array();
        if(sw_count($this->data['all_estates']) == 0)
        {
            $this->data['has_no_all_estates'][] = array('count'=>sw_count($this->data['all_estates']));
        }
        /* [/Get all estates data] */
        
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

        if (empty ($this->data['treefield_data']->template ))
            $this->data['treefield_data']->template = 'treefield_treefield';
        
        $output = $this->parser->parse($this->data['settings_template'].'/'.$this->data['treefield_data']->template.'.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }

}