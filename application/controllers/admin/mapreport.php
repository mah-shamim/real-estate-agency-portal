<?php

class Mapreport extends Admin_Controller 
{
	public function __construct(){
		parent::__construct();
        
        //$this->output->enable_profiler(TRUE);
        
        $this->load->model('mapreport_m');
        
        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    public function index($pagination_offset=0)
	{
	    $this->load->library('pagination');

        prepare_search_query_GET(array(), array('date_removed'));
       
	    // Fetch all users
		$this->data['listings'] = $this->mapreport_m->get();
        $this->data['estates'] = $this->data['listings'];
        
        // calculate totals
        $this->data['total_sold'] = 0;
        $this->data['total_sold_type'] = array();
        $this->data['total_sold_purpose'] = array();
        $this->data['total_days_to_sold'] = 0;
        $this->data['total_days_to_sold_type'] = array();
        $this->data['avarage_size_type'] = array();
        foreach($this->data['listings'] as $key=>$row)
        {
            $this->mapreport_m->purposes[$row->purpose] = $row->purpose;
            $this->mapreport_m->types[$row->type] = $row->type;
            $this->mapreport_m->outcomes[$row->outcome] = $row->outcome;
            
            if($row->outcome == 'SOLD')
            {
                $this->data['total_sold']++;
                
                if(!isset($this->data['total_sold_type'][$row->type]))
                    $this->data['total_sold_type'][$row->type] = 0;
                $this->data['total_sold_type'][$row->type]++;
                
                if(!isset($this->data['total_sold_purpose'][$row->purpose]))
                    $this->data['total_sold_purpose'][$row->purpose] = 0;
                $this->data['total_sold_purpose'][$row->purpose]++;
                
                $days_between = ceil(abs(strtotime($row->date_removed) - strtotime($row->date_submited)) / 86400);
                $this->data['total_days_to_sold'] += $days_between;
                
                if(!isset($this->data['total_days_to_sold_type'][$row->type]))
                    $this->data['total_days_to_sold_type'][$row->type] = 0;
                $this->data['total_days_to_sold_type'][$row->type] += $days_between;
                
                if(!isset($this->data['avarage_size_type'][$row->type]))
                    $this->data['avarage_size_type'][$row->type] = 0;
                $this->data['avarage_size_type'][$row->type] += $row->area;
                
            }
                
        }
        
        // pagination
        $config['base_url'] = site_url('admin/mapreport/index');
        $config['uri_segment'] = 4;
        $config['total_rows'] = sw_count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['link_suffix'] = '#content';
        $config['additional_query_string'] = regenerate_query_string();
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        prepare_search_query_GET(array(), array('date_removed'));
        $this->data['listings'] = $this->mapreport_m->get_pagination($config['per_page'], $pagination_offset);
        
        // Load view
		$this->data['subview'] = 'admin/mapreport/index';
        $this->load->view('admin/_layout_main', $this->data);
	}

    
}