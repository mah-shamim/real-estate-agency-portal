<?php

class SWIN_Reviews extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('showroom_m');
        $this->load->model('rates_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        $this->load->model('reviews_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();

        $this->data['template_css'] = base_url('templates/' . $this->data['settings']['template']) . '/' . config_item('default_template_css');
    }

    public function index($listing_id = 0, $user_id = 0, $pagination_offset = 0) {
        $this->load->library('pagination');

        $listing_selected = array();
        if ($listing_id != 0) {
            $listing_selected['listing_id'] = $listing_id;
        }
        if ($user_id != 0) {
            $listing_selected['user_id'] = $user_id;
        }

        // Fetch all pages
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address');
        $this->data['listings'] = $this->reviews_m->get_joined($listing_selected);

        $config['base_url'] = site_url('admin/reviews/index/' . $listing_id . '/');
        $config['uri_segment'] = 5;
        $config['total_rows'] = sw_count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';

        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        $this->data['listings'] = $this->reviews_m->get_joined($listing_selected, $config['per_page'], NULL, $pagination_offset);

        // Load view
        $this->data['subview'] = 'admin/reviews/index';
        $this->load->view('admin/_layout_main', $this->data);
    }

    public function edit($id = NULL) {
        // Fetch a page or set a new one
        if ($id) {
            $this->data['listing'] = $this->reviews_m->get($id);
            sw_count($this->data['listing']) || $this->data['errors'][] = 'Could not be found';
        } else {
            $this->data['listing'] = $this->reviews_m->get_new();
        }

        // Pages for dropdown
        $this->data['users'] = $this->user_m->get_form_dropdown('username');

        //Simple way to featch only address:        
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address', FALSE, TRUE, TRUE);


        // Set up the form
        $rules = $this->reviews_m->rules_admin;
        $this->form_validation->set_rules($rules);

        // Process the form
        if ($this->form_validation->run() == TRUE) {
            if ($this->config->item('app_type') == 'demo') {
                $this->session->set_flashdata('error',
                        lang('Data editing disabled in demo'));
                redirect('admin/reviews/edit/' . $id);
                exit();
            }

            $data = $this->reviews_m->array_from_post(array('listing_id', 'user_id', 'stars', 'message', 'is_visible'));

            $id = $this->reviews_m->save($data, $id, TRUE);

            $this->session->set_flashdata('message',
                    '<p class="label label-success validation">' . lang_check('Changes saved') . '</p>');

            if (!empty($id)) {
                redirect('admin/reviews/edit/' . $id);
            } else {
                
            }
        }

        // Load the view
        $this->data['subview'] = 'admin/reviews/edit';
        $this->load->view('admin/_layout_main', $this->data);
    }

    public function delete($id) {
        if ($this->config->item('app_type') == 'demo') {
            $this->session->set_flashdata('error',
                    lang('Data editing disabled in demo'));
            redirect('admin/reviews');
            exit();
        }

        $this->reviews_m->delete($id);
        redirect('admin/reviews');
    }

}
