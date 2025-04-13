<?php

class Ffavorites extends Frontuser_Controller
{

	public function __construct ()
	{
		parent::__construct();
	}
    
    public function index()
    {
        echo 'index';
    }
    
	public function myfavorites()
	{
	    $this->load->model('favorites_m');
       
        $lang_id = $this->data['lang_id'];
        $this->data['content_language_id'] = $this->data['lang_id'];
        
        // Main page data
        $this->data['page_navigation_title'] = lang_check('Myfavorites');
        $this->data['page_title'] = lang_check('Myfavorites');
        $this->data['page_body']  = '';
        $this->data['page_description']  = '';
        $this->data['page_keywords']  = '';
        
        $user_id = $this->session->userdata('id');
        
        // Fetch all listings
        $this->data['listings'] = $this->favorites_m->get_by(array('user_id'=>$user_id));
        
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address', FALSE, TRUE, TRUE, FALSE);
        $this->data['properties'] = array();
        $where_in = array();
        
        foreach ($this->data['listings'] as $key => $value) {
           $where_in[]= $value->property_id;
        }
        
        $where = array();
        $where['language_id']  = $lang_id;
       
        $estates = $this->estate_m->get_by($where,FALSE, NULL, NULL, NULL, array(), $where_in);
        
        $this->data['option_10'] = array();
        foreach($estates as $key=>$estate)
        {
            $this->data['option_10'][$estate->id] = $this->estate_m->get_field_from_listing($estate, 10);
            $this->data['properties'][$estate->id] = $estate->address;
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
        

        $output = $this->parser->parse($this->data['settings_template'].'/myfavorites.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
    }
    
	public function myfavorites_delete()
	{
	   $this->load->model('favorites_m');
	   $favorites_id = $this->uri->segment(4);
       
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('ffavorites/myfavorites/'.$this->data['lang_code'].'#content');
            exit();
        }
       
		$this->favorites_m->delete($favorites_id);
        redirect('ffavorites/myfavorites/'.$this->data['lang_code'].'#content');
    }
    

}