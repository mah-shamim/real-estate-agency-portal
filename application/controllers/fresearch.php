<?php

class Fresearch extends Frontuser_Controller
{

	public function __construct ()
	{
		parent::__construct();
	}
    
    public function index()
    {
        echo 'index';
    }
    
    public function treealerts()
    {
        $this->load->model('savedsearch_m');
        $this->load->model('treefield_m');
       
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        $user_id = $this->uri->segment(4);
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('My county of interest');
        $this->data['page_title'] = lang_check('My county of interest');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        // [START]Table datamodel
        
        $this->data['tree_listings'] = $this->treefield_m->get_table_tree($this->data['content_language_id'], 64, 0);
        
        // [END] Table datamodel
        
        // Set up the form
        $rules = $this->savedsearch_m->rules_treealerts;
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error',
                        lang('Data editing disabled in demo'));
                redirect('fresearch/myresearch/'.$this->data['lang_code'].'#content');
                exit();
            }

            $data = $this->input->post();
            
//            echo '<pre>';
//            var_dump($data);
//            echo '</pre>';
            
            //Prepare basic data
            $researches_batch = array();
            $researches_batch_json = array();
            
            $basic_data = array();
            $basic_data_json = array();
            foreach($data as $key=>$val)
            {
                if(strpos($key, 'search_option') === 0 && !empty($val))
                {
                    $basic_data_json['v_'.$key] = $val;
                }
            }
            
            $basic_data['user_id'] = $user_id;
            $basic_data['lang_code'] = $this->data['lang_code'];
            $basic_data['activated'] = 1;
            $basic_data['date_created'] = date('Y-m-d H:i:s');
            $basic_data['date_last_informed'] = date('Y-m-d H:i:s');
            
            if(isset($date['delivery_frequency_h']))
                $basic_data['delivery_frequency_h'] = $date['delivery_frequency_h'];
            
            // Generate treefields lists
            $treefield_list = array();
            $treefields_selected = $this->treefield_m->get_by_in($data['select_tree'], $lang_id);
            foreach($treefields_selected as $tree_sel_row)
            {
                $finded = false;
                foreach($treefield_list as $key=>$a_tree_row)
                {
                    if(strpos($a_tree_row, $tree_sel_row->value_path) !== FALSE)
                    {
                        $treefield_list[$tree_sel_row->id] = $tree_sel_row->value_path;
//                        echo $tree_sel_row->value_path.'=>'.$a_tree_row.'<br />';
//                        echo 'UNS: '.$treefield_list[$key].'<br />';
                        unset($treefield_list[$key]);
                        $finded=true;
                    }
                    elseif(strpos($tree_sel_row->value_path, $a_tree_row) !== FALSE)
                    {
                        $finded=true;
                        break;
                    }
                }
                
                if(!$finded)
                {
//                    echo 'SET: '.$tree_sel_row->value_path.'<br />';
                    $treefield_list[$tree_sel_row->id] = $tree_sel_row->value_path;
                }
            }
            
            foreach($treefield_list as $val)
            {
                $researches_batch_json[] = array_merge($basic_data_json, array('v_search_option_64'=>$val));
            }
            
            // Type
            if(isset($data['property_type']) && sw_count($data['property_type']) > 0)
            {
                foreach($researches_batch_json as $key=>$val)
                {
                    foreach($data['property_type'] as $key_type=>$type_selected)
                    {
                        $researches_batch_json[$key.'_'.$key_type] = array_merge($val, array('v_search_option_2'=>$type_selected));
                    }
                    
                    if(is_array($data['property_type']) && sw_count($data['property_type']) > 0)
                    {
                        unset($researches_batch_json[$key]);
                    }
                }
            }
            
            foreach($researches_batch_json as $key=>$val)
            {
                $researches_batch[$key] = array_merge($basic_data, array('parameters'=>json_encode($val)));
            }
            
            // Save researches
            $this->db->insert_batch('saved_search', $researches_batch); 
            
            // Save alerts details to user_m
            $user_details = array();
            $user_details['research_mail_notifications'] = isset($data['research_mail_notifications']);
            $user_details['research_sms_notifications'] = isset($data['research_sms_notifications']);
            
            $user_id = $this->user_m->save($user_details, $user_id);

            $this->session->set_flashdata('message',
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');

            if(!empty($user_id))
            {
                redirect('frontend/login/'.$this->data['lang_code']);
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

        $output = $this->parser->parse($this->data['settings_template'].'/treealerts.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function myresearch()
	{
	    $this->load->model('savedsearch_m');
       
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Myresearch');
        $this->data['page_title'] = lang_check('Myresearch');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');
        
        // Fetch all listings
        $this->data['listings'] = $this->savedsearch_m->get_by(array('user_id'=>$user_id));
        
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/myresearch.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function myresearch_edit()
	{
	   $this->load->model('savedsearch_m');
	   $research_id = $this->uri->segment(4);
       
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Myresearch');
        $this->data['page_title'] = lang_check('Myresearch');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');
        
        // Fetch all listings
        $this->data['listing'] = $this->savedsearch_m->get_array($research_id);

        //Check if user have permision
        if(empty($this->data['listing']['user_id']) || $user_id != $this->data['listing']['user_id'])
        {
            redirect('fresearch/myresearch/'.$this->data['lang_code'].'#content');
            exit();  
        }

        // Set up the form
        $rules = $this->savedsearch_m->rules_admin;
        unset($rules['user_id']);
        
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('fresearch/myresearch/'.$this->data['lang_code'].'#content');
                exit();
            }
            
            $data = $this->savedsearch_m->array_from_post(array('activated', 'delivery_frequency_h'));
            
            $id = $this->savedsearch_m->save($data, $research_id, TRUE);
            
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('fresearch/myresearch/'.$this->data['lang_code'].'#content');
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/editresearch.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function myresearch_delete()
	{
	   $this->load->model('savedsearch_m');
	   $research_id = $this->uri->segment(4);
       
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('fresearch/myresearch/'.$this->data['lang_code'].'#content');
            exit();
        }
       
		$this->savedsearch_m->delete($research_id);
        redirect('fresearch/myresearch/'.$this->data['lang_code'].'#content');
    }
    

}