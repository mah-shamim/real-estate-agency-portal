<?php

class Favorites extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('showroom_m');
        $this->load->model('rates_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        $this->load->model('favorites_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index($property_id = 0, $user_id = 0, $pagination_offset=0)
	{
	    $this->load->library('pagination');
        
        $listing_selected = array();
        if($property_id != 0)
        {
            $listing_selected['favorites.property_id'] = $property_id;
        }
        if($user_id != 0)
        {
            $listing_selected['user_id'] = $user_id;
        }

        // Fetch all pages
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address');
        $this->data['listings'] = $this->favorites_m->get_joined($listing_selected);

        $config['base_url'] = site_url('admin/favorites/index/'.$property_id.'/0/');
        $config['uri_segment'] = 6;
        $config['total_rows'] = sw_count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['listings'] = $this->favorites_m->get_joined($listing_selected, $config['per_page'], NULL, $pagination_offset);
        
        // Load view
		$this->data['subview'] = 'admin/favorites/index';
        $this->load->view('admin/_layout_main', $this->data);
	}

    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/favorites');
            exit();
        }
       
		$this->favorites_m->delete($id);
        redirect('admin/favorites');
	}
    
}