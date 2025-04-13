<?php

class Backup extends Admin_Controller 
{

	public function __construct(){
		parent::__construct();
        
        $this->load->model('backup_m');
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    public function index()
	{
	    // Fetch all backups
		$this->data['backups'] = $this->backup_m->get();
        
        // Load view
		$this->data['subview'] = 'admin/backup/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit($id = NULL)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/backup');
            exit();
        }
       
	    // Fetch a user or set a new one
	    if($id)
        {
            exit();
        }
        else
        {
            $this->data['backup'] = $this->backup_m->get_new();
        }
        
        //echo 'Backup started';
        
        // Array of tables to backup.
        $tables =array('language',
                       'update_debug', 
                       'treelevel',
                       'stats_periods',
                       'stats',
                       'slugs',
                       'saved_search',
                       'favorites',
                       'cacher',
                       'backup',
                       'reviews',
                       'masking',
                       'payments',
                       'packages',
                       'ads',
                       'user',
                       'slideshow',
                       'settings',
                       'repository',
                       'property',
                       'property_lang',
                       'page',
                       'option',
                       'ci_sessions',
                       'option_lang',
                       'page_lang',
                       'showroom',
                       'showroom_lang',
                       'reservations',
                       'rates',
                       'rates_lang',
                       'qa',
                       'qa_lang',
                       'file',
                       'property_value',
                       'enquire',
                       'property_user',
                       'treefield',
                       'treefield_lang'
                       );
        
        // Add additional tables if exists in database
        $list_tables = $this->db->list_tables();
        if(sw_count($list_tables) == 0)
        {
            exit(lang_check('SHOW TABLES Syntax - MySQL, access denied'));
        }
        
        foreach($list_tables as $item)
        {
            if(!in_array($item, $tables))
            {
                $tables[] = $item;
            }
        }
        
        // Remove tables if not exists in database
        foreach($tables as $key=>$item)
        {
            if(!$this->db->table_exists($item))
            {
                unset($tables[$key]);
            }
        }

        // Load the DB utility class
        $this->load->dbutil();
                
        $prefs = array(
            'tables'      => $tables,  
            'ignore'      => array(),           // List of tables to omit from the backup
            'format'      => 'txt',             // gzip, zip, txt
            'filename'    => '',    // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'    => FALSE,              // Whether to add DROP TABLE statements to backup file
            'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
            'newline'     => "\n"               // Newline character used in backup file
        );
        
        // Backup your entire database and assign it to a variable
        $backup = &$this->dbutil->backup($prefs);
        
        $filename_sql = date('Y-m-d-H-i-s-').$this->user_m->hash(date('Y-m-d H:i:s')).rand(1,1000).'.sql';
        $filename_zip = date('Y-m-d-H-i-s-').$this->user_m->hash(date('Y-m-d H:i:s')).rand(1,1000).'.zip';
        
        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file(APPPATH.'../backups/'.$filename_sql, 
                    $backup);
                    
        //echo 'SQL generated';
        
        // Backup files folder
        $this->load->library('zip');

        // Fetch all image
        if ($handle = opendir(FCPATH.'files')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && is_file(FCPATH.'files/'.$entry)) {
                    $name = 'files/'.$entry;
                    $data = read_file(FCPATH.'files/'.$entry);
                    $this->zip->add_data($name, $data); 
                }
            }
            closedir($handle);
        }
        
        // Fetch all image
        if ($handle = opendir(FCPATH.'files/thumbnail')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && is_file(FCPATH.'files/thumbnail/'.$entry)) {
                    $name = 'files/thumbnail/'.$entry;
                    $data = read_file(FCPATH.'files/thumbnail/'.$entry);
                    $this->zip->add_data($name, $data); 
                }
            }
            closedir($handle);
        }
        $this->zip->archive(APPPATH.'../backups/'.$filename_zip); 
        
        $data_b = array();
        $data_b['date_created'] = date('Y-m-d H:i:s');
        $data_b['sql_file'] = $filename_sql;
        $data_b['zip_file'] = $filename_zip;
        $data_b['script_version'] = APP_VERSION_REAL_ESTATE;
        
        $this->backup_m->save($data_b);
        
        redirect('admin/backup');
	}
    
    public function download($file_type, $id)
	{
        $item_data = $this->backup_m->get($id, TRUE);
        
        $file_name = $item_data->sql_file;
        if($file_type == 'zip')
        {
            $file_name = $item_data->zip_file;
        }
        
        $this->load->helper('download');
       
        $data = file_get_contents(APPPATH.'../backups/'.$file_name); // Read the file's contents
        $name = 'v'.$item_data->script_version.'_'.$file_name;
        
        force_download($name, $data); 
	   
    } 
    
    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/backup');
            exit();
        }
       
        $this->data['backup'] = $this->backup_m->get($id);
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN')
        {
            redirect('admin/backup');
        }
       
		$this->backup_m->delete($id);
        redirect('admin/backup');
	}
}