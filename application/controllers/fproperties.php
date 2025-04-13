<?php

class Fproperties extends Frontuser_Controller
{

	public function __construct ()
	{
		parent::__construct();
        
        $this->load->model('packages_m');
	}
    
    public function index()
    {
        echo 'index';
    }
    
	public function make_featured()
	{
	    $listing_id = $this->uri->segment(4);
        $lang_id = $this->data['lang_id'];
        $user_id = $this->session->userdata('id');
        
        if($this->packages_m->get_available_featured() > 0)
        {
            $data_r = array();
            $data_r['is_featured'] = '1';
            $data_r['featured_paid_date'] = date('Y-m-d H:i:s');
            
            $this->estate_m->save($data_r, $listing_id);
        }
        else
        {
            $this->session->set_flashdata('error_package', lang_check('Featured limitation reached in your package!'));
        }
        
        redirect('frontend/myproperties/'.$this->data['lang_code']);
    }
    
    public function events_qr()
    {
	    $listing_id = $this->uri->segment(4);
        $lang_id = $this->data['lang_id'];
        $user_id = $this->session->userdata('id');
        
        if($this->estate_m->check_user_permission($listing_id, $user_id))
        {
            $url_confirmation = site_url('fquick/events_qr_confirm/'.$this->data['lang_code'].'/'.$listing_id.'/'.$this->user_m->hash($listing_id).'/');
            

            echo '<a href="'.$url_confirmation.'" style="text-align:center;display:block;">';
            echo '<img style="max-width: 100%;" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$url_confirmation.'&choe=UTF-8"/>';
            echo '</a>';
            
            echo '<p style="text-align:center;display:block;">';
            echo lang_check('Print above QR code on event');
            echo '</p>';
            
        }
        else
        {
            exit(lang_check('Missing permissions'));
        }

    }
    



}