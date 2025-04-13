<?php

class Treefield extends Admin_Controller
{
    
	public function __construct()
    {
		parent::__construct();
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('treefield_m');
        
        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	
	}
    
    public function index($pagination_offset=0)
	{
	    echo 'Hello, index here!';
	}

    
    public function delete($field_id=NULL, $treefield_id = NULL)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled in demo'));
            redirect('treefield/edit/'.$field_id);
            exit();
        }
        
        //$this->output->enable_profiler(TRUE);
        
		$this->treefield_m->delete_value($field_id, $treefield_id);
        redirect('admin/treefield/edit/'.$field_id.'/');
	}
    
    public function edit($field_id = NULL, $treefield_id = NULL)
	{
        
        $treefield_id_basic = $treefield_id;
        
        if(!$field_id) {
                    redirect('admin/estate/options');
                    exit();
            return false;
        }
        
	    // Fetch a record or set a new one
        if($field_id)
        {
            $this->data['option'] = $this->option_m->get_lang($field_id, FALSE, $this->data['content_language_id']);
            sw_count($this->data['option']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['option'] = $this->option_m->get_new();
        }
        
	    if($treefield_id)
        {
            $this->data['treefield'] = $this->treefield_m->get_lang($treefield_id, FALSE, $this->data['content_language_id']);
            
            sw_count($this->data['treefield']) || $this->data['errors'][] = 'Could not be found';

            $repository_id = $this->data['treefield']->repository_id;
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('name'=>'treefield_m'));
                
                // Update page with new repository_id
                 $this->treefield_m->save(array('repository_id'=>$repository_id), $treefield_id);
                 $this->data['treefield']->repository_id = $repository_id;
            }
            
        }
        else
        {
            $this->data['treefield'] = $this->treefield_m->get_new();
        }
        
		// Options for dropdown
        $this->data['treefield_no_parents'] = $this->treefield_m->get_no_parents($this->data['content_language_id'], $field_id, $treefield_id);
        //$this->data['pages_no_parents'] = $this->page_m->get_no_parents_news($this->data['content_language_id'], 'No parent');
        
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->load->model('page_m');
        $this->data['templates_trefield'] = $this->page_m->get_templates('treefield_');
        
        // [START]Table datamodel
        
        $this->data['tree_listings'] = $this->treefield_m->get_table_tree($this->data['content_language_id'], $field_id, $treefield_id, TRUE,'.order, treefield.id');
        
        // [END] Table datamodel

        // Set up the form
        $rules = $this->treefield_m->get_all_rules();
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang_check('Data editing disabled in demo'));
                redirect('admin/estate/edit_option/'.$field_id);
                exit();
            }
            
            $data = $this->treefield_m->array_from_post($this->treefield_m->get_post_fields());
            if($treefield_id == NULL)
            {
                //get max order in parent id and set
                $parent_id = $this->input->post('parent_id');
                $data['order'] = $this->treefield_m->max_order($parent_id);
                $data['level'] = 0;
            }
            
            if( config_db_item('enable_county_affiliate_roles') !== TRUE || 
                $this->session->userdata('type') != 'ADMIN')
            {
                unset($data['affilate_price']);
            }
            
            if(isset($data['affilate_price']) && $data['affilate_price'] == 0)
            {
                $data['affilate_price']=NULL;
            }
            
            // Update page with new repository_id
            /*$data['repository_id']=$repository_id;*/
            
            $data_lang = $this->treefield_m->array_from_post($this->treefield_m->get_lang_post_fields());
            
            $multi_add = false;
            $multi_values = array();
            $i=0;
            
//            echo '<pre>';
//            var_dump($data_lang);
//            echo '</pre>';
//            exit();
            
            /* filter ' - ' */
            foreach($data_lang as $key=>$value)
            {
                if(substr($key, 0, 5) != 'value')continue;
                $data_lang[$key] = str_replace(' - ', ' ', $value);
            }
            
            foreach($data_lang as $key=>$value)
            {
                if(substr($key, 0, 5) != 'value')continue;
                if(substr_count($value, ',')>0)
                {
                    foreach(explode(',', $value) as $key_1=>$value_l)
                    {
                        $multi_values[$key_1][$key] = $value_l;
                    }
                    $multi_add = true;
                }
                $i++;
            }
            
            if($multi_add)
            {
                foreach($multi_values as $l_data)
                {
                    
//                    echo '<pre>';
                    $treefield_id = $this->treefield_m->save_with_lang($data, $l_data, $field_id, NULL);
//                    print_r($treefield_id);
//                    print_r($data);
//                    print_r($l_data);
//                    print_r($field_id);
//                    return false;
                }
                
                if(empty($treefield_id))
                {
                    //$this->output->enable_profiler(TRUE);
                }
                else
                {
                    $this->session->set_flashdata('message', 
                            '<p class="label label-success validation">'.lang_check('Multi values added').'</p>');
                    redirect('admin/treefield/edit/'.$field_id);
                }
            }
            else
            {
                if(config_db_item('slug_enabled') === TRUE)
                {
                    // save slug
                    $this->load->model('slug_m');
                    $this->slug_m->save_slug('treefield_m', $treefield_id, $data_lang, $data);
                }
                $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id, $treefield_id);
                 //   return false;
            }
            
            /* re save listings */
            
            if($treefield_id_basic)
            {
                $this->load->model('option_m');
                $this->load->model('estate_m');
                $treefield_basic = $this->data['treefield'];
                /* check if changed */
                $array_changed_langs = array();
                foreach ($this->option_m->languages as $key => $val) {
                    if($treefield_basic->{'value_'.$key} != $data_lang['value_'.$key]) {
                        $array_changed_langs[]=$key;
                    }
                }
                
                /* if changed value */
                if(!empty($array_changed_langs)) {
                    $treefield_new = $this->treefield_m->get_lang($treefield_id, FALSE, $this->data['content_language_id']);

                    foreach ($array_changed_langs as $lang_id) {
                        $listings = $this->option_m->get_options($lang_id,$field_id);
                        foreach ($listings as $listing_id => $listing) {
                            if(strpos($listing[$field_id], $treefield_basic->{'value_path_'.$lang_id}) === 0 ) {
                                $data_lang = $this->estate_m->get_dynamic_array($listing_id);
                                $data_lang['option'.$field_id.'_'.$lang_id] = str_replace($treefield_basic->{'value_path_'.$lang_id}, $treefield_new->{'value_path_'.$lang_id}, $listing[$field_id]);  
                                $this->estate_m->save_dynamic($data_lang, $listing_id);
                            }
                        }
                    }
                }
            }
            
            /* end re save listings */
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(empty($treefield_id))
            {
                //$this->output->enable_profiler(TRUE);
            }
            else
            {
                redirect('admin/treefield/edit/'.$field_id.'/'.$treefield_id);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/treefield/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function regenerate_fields()
    {
        $this->treefield_m->regenerate_fields();
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
    
    public function values_correction(&$str)
    {
        $str = str_replace(', ', ',', $str);
        
        if(substr_count($str, ',')>0 && is_numeric($this->uri->segment(5)))
        {
            $this->form_validation->set_message('values_correction', lang_check('Multiple values disabled in edit mode'));
            return FALSE;
        }
        
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
            $values_post = $this->input->post("value_$key");
            
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
    
    public function import_treefield($field_id=null)
    {
        if(!$field_id) {
            $this->session->set_flashdata('error', 
                    lang_check('Empty field'));
                    redirect('admin/treefield/edit/');
                    exit();
            return false;
        }

        // Fetch a record
        if($field_id)
        {
            $this->data['option'] = $this->option_m->get_lang($field_id, FALSE, $this->data['content_language_id']);
            sw_count($this->data['option']) || $this->data['errors'][] = 'Could not be found';
        }
        
        $this->load->model('language_m');
        
        $config['allowed_types'] = 'xml|txt|text';

        $config['upload_path'] = './files/';
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);

        if($this->input->post()) :
        
            
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Import disabled in demo'));
                redirect('admin/estate/');
                exit();
            }
        
            if($this->input->post('xml_url')) {
                if(preg_match('/\.(xml|txt|text)$/i', $this->input->post('xml_url')) && @$xml = file_get_contents_curl($this->input->post('xml_url'))){
                // Load xml file for import
                $dom = new DOMDocument();   
                $dom->preserveWhiteSpace = false;
                $dom->strictErrorChecking = false;
                $dom->recover=true;
                $dom->loadXml($xml);
                } else {
                    $this->data['error'] = 'Set current xml link';
                }
            } else if($this->upload->do_upload('userfile_xml')){
                $this->upload->do_upload('userfile_xml');
                $upload_data = $this->upload->data();
                $file_path = $upload_data['full_path'];
                
                // Load xml file for import
                $xmlurl = $file_path;
                $dom = new DOMDocument();   
                $dom->preserveWhiteSpace = false;
                $dom->load($xmlurl);
            } else {
                /* error */
                $this->data['error'] = $this->upload->display_errors('', ' or URL');
            }
            if($dom):
                
             $root = $dom->getElementsByTagName('Records');

             if($root->length==0)   {
                   $this->session->set_flashdata('error', 
                    lang_check('XML file is not correct'));
                    redirect('admin/treefield/import_treefield/'.$field_id);
                    exit();
             }
             
            $rootChilds=$root->item(0)->childNodes;
            /* fetch childs */
            $co = array();
            foreach ($rootChilds as $key => $childNode) {
                if(empty($co[$childNode->nodeName]))
                    $co[$childNode->nodeName]=TRUE;
            }
            
            $root_id='0';
            /* remove old values */
            $this->treefield_m->delete($field_id);
            /* end remove old values */
            $level=0;
            foreach ($co as $key => $value) {
               
                $data=array();
                
                /* root */
                if($key=='co00') {
                    $data['field_id']=$field_id;
                    $data['parent_id']=0;
                    $data['order']=0;
                    $data['level']=$level;
                    $data['font_icon_code']='';
                    $data['template']='treefield_treefield';
                    $data['notifications_sent']=1;
                    
                    /* if use country */
                    if($this->input->post('root_country')){
                        $this->db->insert('treefield', $data);
                        $root_id=$treefield_id=$this->db->insert_id();
                        $data_rates_lang= array();
                        foreach($this->language_m->db_languages_code_obj as $lang_obj)
                            {
                                $date_prepare = array();
                                $date_prepare['treefield_id'] = $treefield_id;
                                $date_prepare['value'] =  substr($root->item(0)->getElementsByTagName($key)->item(0)->nodeValue,5);
                                $date_prepare['value_path'] = substr($root->item(0)->getElementsByTagName($key)->item(0)->nodeValue,5);
                                $date_prepare['language_id'] = $lang_obj->id;
                                $data_rates_lang[] = $date_prepare;
                            }
                         $this->db->insert_batch('treefield_lang', $data_rates_lang);
                         $level++;
                    }
                    continue;
                }
                
                /* end root */
                
                /* state */
                $states_array= $root->item(0)->getElementsByTagName($key);
                $state='';
                $parent_id='';
                $county_arr=array();
               
                /* fetch states and county */
                foreach ($states_array as $k=>$v) {
                    if(!$v->nodeName || !$v->nodeValue) continue;
                    
                    if(preg_match('/^'.substr($v->nodeName,2).'/', $v->nodeValue))
                    if($k==0) {
                        $state= substr($v->nodeValue,5);
                    } else {
                       //$county_arr[]=substr($v->nodeValue,5);  
                       $county_arr[]=$v->nodeValue;  
                    }
                }
                /* end fetch states and county */
                
                /* state insert */
                $data['field_id']=$field_id;
                $data['parent_id']=$root_id;
                $data['order']=0;
                $data['font_icon_code']='';
                $data['level']=$level;
                $data['template']='treefield_treefield';
                $data['notifications_sent']=1;

                $this->db->insert('treefield', $data);
                
                $parent_id=$treefield_id=$this->db->insert_id();
                $data_rates_lang= array();
                foreach($this->language_m->db_languages_code_obj as $lang_obj)
                    {
                        $date_prepare = array();
                        $date_prepare['treefield_id'] = $treefield_id;
                        $date_prepare['value'] =  $state;
                        $date_prepare['value_path'] = $state;
                        $date_prepare['language_id'] = $lang_obj->id;
                        $data_rates_lang[] = $date_prepare;
                    }
                $this->db->insert_batch('treefield_lang', $data_rates_lang);
                /* end state insert */
       
                /* county insert */
                
                foreach ($county_arr as $key => $county) :
                $treefield_id ='';
                $data = array();
                $data['field_id']=$field_id;
                $data['parent_id']=$parent_id;
                $data['order']=0;
                $data['level']=$level+1;
                $data['font_icon_code']='';
                $data['template']='treefield_treefield';
                $data['notifications_sent']=1;

                $this->db->insert('treefield', $data);
                
                $treefield_id=$this->db->insert_id();
                $data_rates_lang= array();
                foreach($this->language_m->db_languages_code_obj as $lang_obj)
                    {
                        $date_prepare = array();
                        $date_prepare['treefield_id'] = $treefield_id;
                        //$date_prepare['value'] =substr($county,5);
                        // $county = substr($county,5);
                        $value_explode = explode(',', substr($county,5));
                        $date_prepare['value'] = $value_explode[0];
                        
                        if(isset($value_explode[1]) || empty($value_explode[1])) {
                            $value_explode[1]='';
                        }
                        
                        $date_prepare['value_path'] = $state.'-'.$date_prepare['value'];
                        $date_prepare['keywords'] =','.$value_explode[1];
                        $date_prepare['language_id'] = $lang_obj->id;
                        $data_rates_lang[] = $date_prepare;
                    }
                $this->db->insert_batch('treefield_lang', $data_rates_lang);
                /* end county insert */
                endforeach;
                
            }
            $this->data['import_end'] = true;
        endif;
        endif;
        // Load the view
        $this->data['subview'] = 'admin/treefield/import_treefield';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function generate_geo_map($field_id=null)
    {
        if(!$field_id) {
            $this->session->set_flashdata('error', 
                    lang_check('Empty field'));
                    redirect('admin/treefield/edit/');
                    exit();
            return false;
        }

        // Fetch a record
        if($field_id)
        {
            $this->data['option'] = $this->option_m->get_lang($field_id, FALSE, $this->data['content_language_id']);
            sw_count($this->data['option']) || $this->data['errors'][] = 'Could not be found';
        }
        
        $this->load->model('language_m');
        /*
        $this->data['geo_map_prepared'] = array(
            'ca' => 'Canada',
            'us' => 'Usa',
            'europe' => 'Europe',
            'map_Svg_croatia' => 'map_Svg_croatia',
        );
        */
        $errors_svg = array();
        $geo_map_prepared = array();
        if( file_exists(FCPATH.'templates/'.$this->data['settings']['template'].'/assets/svg_maps/')) {
            $svg_files = array_diff( scandir(FCPATH.'templates/'.$this->data['settings']['template'].'/assets/svg_maps/'), array('..', '.'));
        
            foreach ($svg_files  as $svg) {
                $sql_o = file_get_contents(FCPATH.'templates/'.$this->data['settings']['template'].'/assets/svg_maps/'.$svg);
                $match = '';
                preg_match_all('/(data-title-map)=("[^"]*")/i', $sql_o, $match);
                
                
                if(!empty($match[2])) {
                    $geo_map_prepared[$svg] = trim(str_replace('"', '', $match[2][0]));
                } else if(stristr($sql_o, "http://amcharts.com/ammap") != FALSE ) {
                    $geo_map_prepared[$svg] = 'undefined';
                    $match='';
                    preg_match_all('/(SVG map) of ([^"]* -)/i', $sql_o, $match2);
                    if(!empty($match2) && isset($match2[2][0])) {
                        $title = str_replace(array(" -","High","Low"), '', $match2[2][0]);
                        $geo_map_prepared[$svg] = trim($title);
                    }
                }
                else {
                    $errors_svg[] = "<p class='label label-info validation'>Map ".$svg." is not formatted correctly</p>";
                }
            }
        }
        
        asort($geo_map_prepared);
        $this->data['geo_map_prepared'] = $geo_map_prepared;
        $this->data['errors_svg'] = $errors_svg;
        
        $this->form_validation->set_rules('geo_map', "lang: Map", 'trim|required');
        
        if($this->form_validation->run()) {
            
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang_check('Map generate disabled in demo'));
                redirect('admin/treefield/generate_geo_map/'.$field_id);
                exit();
            }
            
            $geo_map = $this->input->post('geo_map'); 

            $accept_generate = false;
            if($this->input->post('accept_generate') && $this->input->post('accept_generate')== 1){
               $accept_generate = true;
            } 
            
            $random_locations = false;
            if($this->input->post('random_locations') && $this->input->post('random_locations')== 1){
               $random_locations = true;
            } 
            
            if($accept_generate) {
                if( file_exists(FCPATH.'templates/'.$this->data['settings']['template'].'/assets/svg_maps/'.$geo_map)) {
                        
                    // replace default svg
                    $svg = file_get_contents(FCPATH.'templates/'.$this->data['settings']['template'].'/assets/svg_maps/'.$geo_map);

                        
                                        
                    /* changed map from $match2 */
                    if(stristr($svg, "http://amcharts.com/ammap") != FALSE ) {
                        $svg = str_replace('title', 'data-name', $svg);
                        $match2='';
                        preg_match_all('/(SVG map) of ([^"]* -)/i', $svg, $match2);
                        if(!empty($match2) && isset($match2[2][0])) {
                            $title='';
                            $title = str_replace(array(" -","High","Low"), '', $match2[2][0]);
                            $title = trim($title);
                            $svg = str_replace('<svg', '<svg data-title-map="'.trim($title).'"', $svg);
                        }
                        
                    }
                    /* end changed map from $match2 */
                    $this->treefield_m->delete($field_id);
                    $langs_object = $this->language_m->get();
                    $treefield_data_dynamic = array();
                    
                    /* multimap */
                    if(stripos($svg, 'data-map-type="multimap"') !== FALSE || stripos($svg, "data-map-type='multimap'") !== FALSE) {
                        
                        $treefield_array = array();
                        $treefield_lvl_0_id = array();
                        
                        $dom = new DOMDocument();
                        $dom->preserveWhiteSpace = false; 
                        $dom->formatOutput = true; 
                        $dom->loadXml($svg);
                        /* set version */
                        $root_svg = $dom->getElementsByTagName('svg')->item(0);
                        $root_svg->setAttribute('data-sw_geomodule-version', '2.0');
                         
                        $paths = $dom->getElementsByTagName('path'); //here you have an corresponding object
                        foreach ($paths as $path) {
                            $lvl_0 = $path->getAttribute('data-name-lvl_0');
                            $lvl_1 = $path->getAttribute('data-name');
                            if(($lvl_0 && !empty($lvl_0)) && ($lvl_1 && !empty($lvl_1))){
                                $lvl_0 = trim($lvl_0);
                                $lvl_1 = trim($lvl_1);
                                
                                $path->setAttribute('data-name-lvl_0', $lvl_0);
                                $path->setAttribute('data-name-lvl_1', $lvl_1);
                                $path->setAttribute('data-name', $lvl_1.', '.$lvl_0);
                                
                                /* if first added to root */
                                if(!isset($treefield_array[$lvl_0])) {
                                    $data = array
                                    (
                                        'parent_id' => 0,
                                        'template' => 'treefield_treefield',
                                        'level'=>0
                                    );
                                    
                                    $data_lang= array();
                                    foreach ( $langs_object as $key => $v) {
                                        $data_lang['value_'.$v->id] = $lvl_0;
                                    }
                                    $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                                    $treefield_lvl_0_id[$lvl_0]=$treefield_id;
                                }
                                
                                $treefield_array[$lvl_0][]=$lvl_1;
                            }
                        }     
                        
                        foreach ($treefield_array as $root => $v) {
                            foreach ($v as $key=>$value) {
                                $data = array(
                                    'parent_id' => $treefield_lvl_0_id[$root],
                                    'template' => 'treefield_treefield'
                                );

                                $data_lang= array();
                                foreach ( $langs_object as $lang_ob) {
                                    $data_lang['value_'.$lang_ob->id] = $value;
                                }
                                $treefield_data_dynamic[] = $root.' - '.$value. ' -';
                                $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                            }
                        }
                        $svg= $dom->saveXML();
                    } else {
                    
                    $data = array
                        (
                            'parent_id' => 0,
                            'template' => 'treefield_treefield',
                            'level'=>0
                        );

                    
                    $data_lang= array();
                    foreach ( $langs_object as $key => $value) {
                        $data_lang['value_'.$value->id] = $this->data['geo_map_prepared'][$geo_map];
                    }
                    
                    if($this->input->post('in_root')!=1) {
                        $treefield_root_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                    }
                    
                    $root_name = $this->data['geo_map_prepared'][$geo_map];
                    $treefield_array = array();
                    $treefield_lvl_0_id = array();

                    $dom = new DOMDocument();
                    $dom->preserveWhiteSpace = false; 
                    $dom->formatOutput = true; 
                    $dom->loadXml($svg);
                    
                    /* set version */
                    $root_svg = $dom->getElementsByTagName('svg')->item(0);
                    $root_svg->setAttribute('data-sw_geomodule-version', '2.0');
                    
                    $paths = $dom->getElementsByTagName('path'); //here you have an corresponding object
                        foreach ($paths as $path) {
                            $lvl_1 = $path->getAttribute('data-name');
                            if($lvl_1 && !empty($lvl_1)){
                                $lvl_1 = trim($lvl_1);
                                
                                $path->setAttribute('data-name-lvl_0', $root_name);
                                $path->setAttribute('data-name-lvl_1', $lvl_1);
                                
                                if($this->input->post('in_root')==1) {
                                    $path->setAttribute('data-name', $lvl_1);
                                } else {
                                    $path->setAttribute('data-name', $lvl_1.', '.$root_name);
                                }
                                
                                $treefield_array[]=$lvl_1;
                            }
                        }     
                    
                    $g = $dom->getElementsByTagName('g'); //here you have an corresponding object
                        foreach ($g as $path) {
                            $lvl_1 = $path->getAttribute('data-name');
                            if($lvl_1 && !empty($lvl_1)){
                                $lvl_1 = trim($lvl_1);
                                
                                $path->setAttribute('data-name-lvl_0', $root_name);
                                $path->setAttribute('data-name-lvl_1', $lvl_1);
                                if($this->input->post('in_root')==1) {
                                    $path->setAttribute('data-name', $lvl_1);
                                } else {
                                    $path->setAttribute('data-name', $lvl_1.', '.$root_name);
                                }
                                $treefield_array[]=$lvl_1;
                            }
                        }     
                    $svg= $dom->saveXML();
                    
                    if(!empty($treefield_array))
                        foreach ($treefield_array as $value) {
                            if($this->input->post('in_root')==1) {
                                $data = array
                                (
                                    'parent_id' => 0,
                                    'template' => 'treefield_treefield',
                                    'level'=>0
                                );

                                $data_lang= array();
                                foreach ( $langs_object as $lang_ob) {
                                    $data_lang['value_'.$lang_ob->id] = $value;
                                }
                                $treefield_data_dynamic[] = $value. ' -';
                                $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                            } else {
                                $data = array(
                                    'parent_id' => $treefield_root_id,
                                    'template' => 'treefield_treefield'
                                );

                                $data_lang= array();
                                foreach ( $langs_object as $lang_ob) {
                                    $data_lang['value_'.$lang_ob->id] = $value;
                                }
                                $treefield_data_dynamic[] = $this->data['geo_map_prepared'][$geo_map].' - '.$value. ' -';
                                $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                            }
                        }
                    }
                    
                    file_put_contents(FCPATH.'files/treefield_64_map.svg', $svg);
                   
                    if($random_locations) {
                        $this->load->model('estate_m');

                        $results_obj_id = $this->estate_m->get();

                        if($results_obj_id and !empty($results_obj_id))
                            foreach ($results_obj_id as $key => $estate_id) {
                                $estate_id = $estate_id->id;

                                $random_region = $treefield_data_dynamic[array_rand($treefield_data_dynamic)];

                                $options_data_dynamic = $this->estate_m->get_dynamic_array($estate_id);

                                foreach ( $langs_object as $lang_ob) {
                                    $options_data_dynamic['option'.$field_id.'_'.$lang_ob->id] = $random_region;  
                                }

                                $this->estate_m->save_dynamic($options_data_dynamic, $estate_id);
                            }
                    }

                    redirect('admin/treefield/edit/'.$this->data['option']->id);
                } 
            } else {
                $this->data['error']= lang_check('Must accept checkbox. Current map will be replaced with new one');
            }
        }
        
        // Load the view
        $this->data['subview'] = 'admin/treefield/generate_geo_map';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
function generate_world($field_id=null) {
    if(!$field_id) {
        $this->session->set_flashdata('error', 
                lang_check('Empty field'));
                redirect('admin/treefield/edit/');
                exit();
        return false;
    }
    // At start of script
    $time_start = microtime(true);
        // Fetch a record
        if($field_id)
        {
            $this->data['option'] = $this->option_m->get_lang($field_id, FALSE, $this->data['content_language_id']);
            sw_count($this->data['option']) || $this->data['errors'][] = 'Could not be found';
        }
        
        $this->load->model('language_m');
        if(true) {
            set_time_limit(99999999);
            if( file_exists(FCPATH.'world-cities.csv')) {
                // replace default svg
                $file = FCPATH.'world-cities.csv';
                /* changed map from $match2 */
                $csv = file($file);
                /* header csv */
                $header=array();
                $_header=str_getcsv(array_shift($csv),';');;

                function remove_utf8_bom($text)
                {
                    $bom = pack('H*','EFBBBF');
                    $text = preg_replace("/^$bom/", '', $text);
                    return $text;
                }
                
                foreach ($_header  as $key => $value) {
                  $header[]=strtolower(remove_utf8_bom(trim($value)));
                }
                /* end header csv */

                $csv_t=array();
                $csv_codes=array();
                foreach ($csv as $key => $line) {
                    $csv_line_array =  str_getcsv($line,';');
                    if(sw_count($csv_line_array)!= sw_count($header)) {
                           continue;
                    }
                    $row =  array_combine($header, $csv_line_array);
                    if($row['country']==$row['city']) continue;
                    $csv_t[$row['country']][] = $row['city'];
                    $csv_codes[$row['country']] =  strtolower($row['iso2']);
                }
                /* generate sql */
                
                $this->data['locations_list'] = array_keys($csv_codes);
                sort($this->data['locations_list']);
                
                $this->form_validation->set_rules('locations', "lang: Locations", 'required');
                if($this->form_validation->run()) {

                    if($this->config->item('app_type') == 'demo')
                    {
                        $this->session->set_flashdata('error', 
                                lang_check('Map generate disabled in demo'));
                        redirect('admin/treefield/generate_locations/'.$field_id);
                        exit();
                    }
                    
                $locations_enabled = $this->input->post('locations');
                    
                $langs_object = $this->language_m->get();
                $sql = '';
                $sql ="DELETE FROM `treefield` WHERE field_id =64;";
                $this->db->query($sql);
                
                foreach ($csv_t as $key => $value) {
                    $key = str_replace("'", "", $key);
                    $path = $key;
                    
                    if(!in_array($key, $locations_enabled)) continue;
                    
                    
                    $sql = " INSERT INTO `treefield` (`field_id`, `parent_id`, `level`, `template`, `code`) "
                          . "VALUES (64, 0, 0,'treefield_treefield','"._ch($csv_codes[$key], '')."');";
                    $this->db->query($sql);
                    
                    $sql =" SET @last_id_in_table1 = LAST_INSERT_ID();";
                    $this->db->query($sql);
                    $lang_count  = (sw_count($langs_object));
                   
                    /* lang */
                    $sql =" INSERT INTO `treefield_lang` (`treefield_id`, `language_id`, `value`, `value_path`) VALUES";
                    foreach ( $langs_object as $l_k => $l) {
                        
                        $sql .=" (@last_id_in_table1, ".$l->id.",'".$key."','".$path."')";
                        if($lang_count>($l_k+1)) $sql .= ",";
                        else  $sql .= ";";
                    }
                    $this->db->query($sql);
                    
                    foreach ($value as $k => $v) {
                        $v = str_replace("'", "", $v);
                        
                        $sql = "INSERT INTO `treefield` (`field_id`, `parent_id`, `level`, `template`, `code`) "
                              . "VALUES ('64', @last_id_in_table1, '1','treefield_treefield','"._ch($csv_codes[$key], '')."');";
                        $this->db->query($sql);
                        $sql ="SET @last_id_in_table1_city = LAST_INSERT_ID();";
                        $this->db->query($sql);
                        
                        /* lang */
                       // $sql ="INSERT INTO `treefield_lang` (`treefield_id`, `language_id`, `value`, `value_path`) VALUES (@last_id_in_table1_city, 1,'".$v."','".$path." - ".$v."'),(@last_id_in_table1_city, 2,'".$v."','".$path." - ".$v."');";
                        /* lang */
                        $sql =" INSERT INTO `treefield_lang` (`treefield_id`, `language_id`, `value`, `value_path`) VALUES";
                        foreach ( $langs_object as $l_k => $l) {
                            $sql .=" (@last_id_in_table1_city, ".$l->id.",'".$v."','".$path." - ".$v."')";
                            if($lang_count>($l_k+1)) $sql .= ",";
                            else  $sql .= ";";
                        }
                        $this->db->query($sql);
                        }
                    
                    }
                    
                    $this->session->set_flashdata('message', 
                        '<p class="label label-success validation">'.lang_check('Location generated').'</p>');
                    redirect('admin/treefield/edit/'.$field_id);
                }
            } 
        } else {
            $this->data['error']= lang_check('Must accept checkbox. Current map will be replaced with new one');
        }
        
        
        // Load the view
        $this->data['subview'] = 'admin/treefield/generate_locations';
        $this->load->view('admin/_layout_main', $this->data);
}    
    function import_vehicle ($field_id = NULL) {
        set_time_limit(0);
        if($field_id == NULL) {
            $this->session->set_flashdata('error', 
                    lang_check('Missing field_id in link'));
            redirect('admin/treefield/edit/'.$field_id);
            exit();
        }
        
        if(config_db_item('import_vehicle_enable')== FALSE ) {
            $this->session->set_flashdata('error', 
                    lang_check('Feature not available for this script'));
            redirect('admin/treefield/edit/'.$field_id);
            exit();
        }
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Import doesn\'t support in demo'));
            redirect('admin/treefield/edit/'.$field_id);
            exit();
        }
            
        // Set up the form
        $rules = array();
        
        $this->form_validation->set_rules('option79_1', "lang: Category into import", 'trim|');
        $this->form_validation->set_rules('modelyear', "lang: Model Year", 'trim|is_numeric');
        
        /* test */
        //$this->form_validation->set_rules('models', "lang: Models", 'trim|required');
        //$this->form_validation->set_rules('makes', "lang: Makes", 'trim|required');
        
        
        $types_list = array (
            'Passenger car'=> 'Passenger car',
            'Multipurpose passenger vehicle'=> 'Multipurpose passenger vehicle',
            'Truck'=> 'Truck',
            'Bus'=> 'Bus',
            'Motorcycle'=> 'Motorcycle',
        );
        
        $this->data['types_list'] = $types_list;
        
        if($this->form_validation->run() == TRUE) {

            /* checkboxes */
            $types_enabled = $this->input->post('types');
            $makes_enabled = $this->input->post('makes');
            $models_enabled = $this->input->post('models');
            
            if($types_enabled ==1){
                $makes_enabled = $models_enabled = false;
            }
            /* end checkboxes */
            
            $parent_id = $this->input->post('option79_1');
            
            if(empty($parent_id)){
                $parent_id = 0;
                $lvl = 0;
            }
            
            if(!empty($parent_id)){
                $field_parent = $this->treefield_m->get_lang($parent_id);
                $lvl = $field_parent->level;
            }
            
            $remove_all_existing = $this->input->post('remove_all_existing');
            $type_vahicle = $this->input->post('type_vahicle');
            if(empty($type_vahicle))
                $type_vahicle = 'car';
            
            $make_name = $this->input->post('make_name');
            
            //$this->treefield_m->delete($field_id);
            $langs_object = $this->language_m->get();
            $treefield_data_dynamic = array();
            
            /* START types */
            if($types_enabled){
                if($remove_all_existing)
                    $this->treefield_m->delete_value($field_id,$parent_id, TRUE);
                
                foreach ($types_list as $key => $value) {
                    
                        $query = $this->db->get_where('treefield_lang', array('value' =>trim($value)));
                        if($query->row()) {
                            continue;
                        }
                        if($lvl == 0)
                            $data = array
                            (
                                'parent_id' => $parent_id,
                                'template' => 'treefield_treefield',
                                'level' => 0,
                            );
                        else
                            $data = array
                            (
                                'parent_id' => $parent_id,
                                'template' => 'treefield_treefield'
                            );

                        $data_lang= array();
                        foreach ( $langs_object as $key => $v) {
                            $data_lang['value_'.$v->id] = trim($value);
                        }

                        $treefield_root_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                }
            }
            /* END types */
            
            /* START makes */
            if($makes_enabled){
                if($remove_all_existing)
                    $this->treefield_m->delete_value($field_id,$parent_id, TRUE);
                
                $all_makes_json = file_get_contents_curl('https://vpic.nhtsa.dot.gov/api/vehicles/GetMakesForVehicleType/'.rawurlencode($type_vahicle).'?format=json');
                $all_makes = json_decode($all_makes_json);
                /*dump($all_makes); exit();*/
                
                if(isset($all_makes->count) && $all_makes->count<1) {
                    $this->session->set_flashdata('error', 
                        lang_check('Missing results'));
                        redirect('admin/treefield/import_vehicle/'.$field_id);
                    exit();
                }

                foreach ($all_makes->Results as $key => $value) {
                    
                        $this->db->select('treefield_id, parent_id, language_id, value');
                        $this->db->from('treefield_lang');
                        $this->db->join('treefield', 'treefield.id = treefield_lang.treefield_id');
                        $this->db->where('parent_id', $parent_id);
                        $this->db->where('value', trim($value->MakeName));
                        $query = $this->db->get();
                        if($query->row()) {
                            continue;
                        }
                    
                        if($lvl == 0)
                            $data = array
                            (
                                'parent_id' => $parent_id,
                                'template' => 'treefield_treefield',
                                'level' => 0,
                            );
                        else
                            $data = array
                            (
                                'parent_id' => $parent_id,
                                'template' => 'treefield_treefield'
                            );

                        $data_lang= array();
                        foreach ( $langs_object as $key => $v) {
                            $data_lang['value_'.$v->id] = trim($value->MakeName);
                        }

                        $treefield_root_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                }
                $lvl++;
            }
            /* END makes */
            
            /* START models */
            if($models_enabled) {
                if(!empty($make_name) && empty($makes_enabled))
                    $results = array($parent_id=>$make_name);
                else
                    $results = $this->treefield_m->get_level_values ('1', $field_id, $parent_id, 1);
                
                    foreach ($results as $make_key => $make) {
                    if(empty($make_key)) continue;
                    
                    if($remove_all_existing)
                        $this->treefield_m->delete_value($field_id,$make_key, TRUE);
                    
                    $modelyear ='';
                    if($this->input->post('modelyear'))
                        $modelyear ='/modelyear/'.$this->input->post('modelyear');
                        
                    
                    $all_models_json = file_get_contents_curl('https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformakeyear/make/'.rawurlencode($make).$modelyear.'/vehicleType/'.rawurlencode($type_vahicle).'?format=json');
                    $all_models = json_decode($all_models_json);
                    
                    if(!isset($all_models->Count) || $all_models->Count<1) {
                        continue;
                    }
                    
                    if(isset($all_models->Results))
                    foreach ($all_models->Results as $key => $value) {
                        
                        $this->db->select('treefield_id, parent_id, language_id, value');
                        $this->db->from('treefield_lang');
                        $this->db->join('treefield', 'treefield.id = treefield_lang.treefield_id');
                        $this->db->where('parent_id', $parent_id);
                        $this->db->where('value', trim($value->Model_Name));
                        $query = $this->db->get();
                        
                        if($query->row()) {
                            continue;
                        }
                        
                        if($lvl == 0)
                            $data = array
                            (
                                'parent_id' => $make_key,
                                'template' => 'treefield_treefield',
                                'level' => 0,
                            );
                        else
                            $data = array
                            (
                                'parent_id' => $make_key,
                                'template' => 'treefield_treefield'
                            );

                        $data_lang= array();
                        foreach ( $langs_object as $key => $v) {
                            $data_lang['value_'.$v->id] = trim($value->Model_Name);
                        }

                        $treefield_root_id = $this->treefield_m->save_with_lang($data, $data_lang, $field_id);
                    }
                }
            }
            /* END models */
            
            $this->session->set_flashdata('message', 
                '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            redirect('admin/treefield/import_vehicle/'.$field_id);
        }
        // Load the view
        $this->data['subview'] = 'admin/treefield/import_vehicle';
        $this->load->view('admin/_layout_main', $this->data);
        
    }
    
}