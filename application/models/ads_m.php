<?php

class Ads_m extends MY_Model {
    
    protected $_table_name = 'ads';
    protected $_order_by = 'id DESC ';
    
    public $rules_admin = array(
        //'code' => array('field'=>'code', 'label'=>'lang:Code', 'rules'=>'trim|required|callback__unique_code|alpha|max_length[20]|xss_clean'),
        'title' => array('field'=>'title', 'label'=>'lang:Title', 'rules'=>'trim|required|callback__unique_title|xss_clean'),
        'description' => array('field'=>'description', 'label'=>'lang:Description', 'rules'=>'trim|required|xss_clean'),
        'link' => array('field'=>'link', 'label'=>'lang:Link', 'rules'=>'trim|required|xss_clean'),
        'type' => array('field'=>'type', 'label'=>'lang:Type', 'rules'=>'trim|required|xss_clean'),
        //'width' => array('field'=>'width', 'label'=>'lang:Width', 'rules'=>'trim|is_natural_no_zero|required'),
        //'height' => array('field'=>'height', 'label'=>'lang:Height', 'rules'=>'trim|is_natural_no_zero|required'),
        'is_activated' => array('field'=>'is_activated', 'label'=>'lang:Activated', 'rules'=>'trim'),
        'is_random' => array('field'=>'is_random', 'label'=>'lang:Random', 'rules'=>'trim')
    );
    
    public $ads_types = array('180x150px', '160x600px', '728x90px');
    
	public function __construct(){
		parent::__construct();
	}
    
    public function get_new()
	{
        $ads = new stdClass();
        //$ads->code = '';
        $ads->title = '';
        $ads->description = '';
        $ads->link = '';
        $ads->type = '';
        //$ads->width = '';
        //$ads->height = '';
        $ads->is_activated = '1';
        $ads->is_hardlocked = '0';
        $ads->is_random = '0';
        return $ads;
	}
    
    public function delete($id)
    {
        // Remove repository
        $ads_data = $this->get($id, TRUE);
        if(sw_count($ads_data))
        {
            $this->repository_m->delete($ads_data->repository_id);
        }
        
        parent::delete($id);
    }

}



