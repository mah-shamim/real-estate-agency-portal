<?php

class Backup_m extends MY_Model {
    
    protected $_table_name = 'backup';
    protected $_order_by = 'id DESC';
    public $rules = array(
    );
    public $rules_admin = array(
        'date_created' => array('field'=>'date_created', 'label'=>'lang:Date', 'rules'=>'trim|required|xss_clean'),
        'sql_file' => array('field'=>'sql_file', 'label'=>'lang:SQL file', 'rules'=>'trim|required|xss_clean'),
        'zip_file' => array('field'=>'zip_file', 'label'=>'lang:ZIP file', 'rules'=>'trim|required|xss_clean'),
        'script_version' => array('field'=>'script_version', 'label'=>'lang:Script version', 'rules'=>'trim|required|xss_clean'),
    );

	public function __construct(){
		parent::__construct();
	}
    
    public function get_new()
	{
        $enquire = new stdClass();
        $enquire->date_created = date('Y-m-d H:i:s');
        $enquire->sql_file = '';
        $enquire->zip_file = '';
        return $enquire;
	}
    
    public function delete($id)
    {
        // Remove files
        $item_data = $this->get($id, TRUE);
        if(sw_count($item_data))
        {
            if(file_exists(APPPATH.'../backups/'.$item_data->sql_file))
                unlink(APPPATH.'../backups/'.$item_data->sql_file);
            
            if(file_exists(APPPATH.'../backups/'.$item_data->zip_file))
                unlink(APPPATH.'../backups/'.$item_data->zip_file);
        }
        
        parent::delete($id);
    }
    
}



