<?php

class Configurator extends MY_Controller
{

	public function __construct ()
	{
		parent::__construct();
        
        error_reporting(E_ALL);

        $this->load->library('form_validation');
        $this->lang->load('configurator', 'english');
        $this->form_validation->set_error_delimiters('<p class="alert alert-error">', '</p>');
        $this->load->model('user_m');
        
        $CI =& get_instance();
        $CI->form_languages = array();
        
        if(config_item('installed') == true && substr_count(uri_string(), 'installed') == 0)
        {
            redirect('configurator/installed');
        }
	}
    
    public function installed()
    {
        $this->load->view('configurator/installed');
    }
    
	public function index()
	{
	    $this->data['show_error'] = false;

		// Set up the form
		$rules = array(
               array(
                     'field'   => 'app_type',
                     'label'   => 'lang:app_type',
                     'rules'   => 'required'
                  ),  
               array(
                    'field'   => 'your_email',
                    'label'   => 'lang:Your email',
                    'rules'   => 'required'
                 ),
               array(
                     'field'   => 'mysql_db_name',
                     'label'   => 'lang:mysql_db_name',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'mysql_db_host',
                     'label'   => 'lang:mysql_db_host',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'mysql_db_driver',
                     'label'   => 'lang:mysql_db_driver',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'mysql_db_port',
                     'label'   => 'lang:mysql_db_port',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'db_username',
                     'label'   => 'lang:db_username',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'db_password',
                     'label'   => 'lang:db_password',
                     'rules'   => 'trim'
                  ),
               array(
                     'field'   => 'admin_username',
                     'label'   => 'lang:admin_username',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'admin_password',
                     'label'   => 'lang:admin_password',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'agent_username',
                     'label'   => 'lang:agent_username',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'agent_password',
                     'label'   => 'lang:agent_password',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'codecanyon_username',
                     'label'   => 'lang:codecanyon_username',
                     'rules'   => 'required'
                  ),               
               array(
                     'field'   => 'codecanyon_code',
                     'label'   => 'lang:codecanyon_code',
                     'rules'   => 'required|callback_check_purchase'
                  )
            );
            
		$this->form_validation->set_rules($rules);
        
        if (PHP_VERSION_ID < 50600)
            $this->data['warning_php'] = 'PHP 5.6 is required, please update your PHP version before install';

		// Process the form
		if ($this->form_validation->run() == TRUE) {            

            $write_error = check_global_writing_permissions();
            
            if($write_error == '')
            {
                // try mysql db connection
                $dsn = $_POST['mysql_db_driver'].'://'.$_POST['db_username'].':'.urlencode($_POST['db_password']).'@'.$_POST['mysql_db_host'].':'.$_POST['mysql_db_port'].'/'.$_POST['mysql_db_name'];
    
                $this->db = $this->load->database($dsn, TRUE);

                if (!empty($this->db) && $this->db->conn_id !== false) {
                    
                    $db_error = $this->db->_error_message();
                     
                    if(empty($db_error))
                    {
                        $list_tables = $this->db->list_tables();
                        if(sw_count($list_tables) > 0)
                        {
                            exit(lang_check('Please enter empty database!'));
                        }
                     
                        if(!file_exists(FCPATH.'sql_scripts/db-example-1.sql'))
                        {
                            $db_error.= '<br />Missing file: sql_scripts/db-example-1.sql';
                        }
                        
                        $sql=file_get_contents(FCPATH.'sql_scripts/db-example-1.sql');
                        
                        $explode_var = ";\n";
                        if(substr_count($sql, ";\n")==0)
                        {
                            $explode_var = ";\r";
                        }
                        
                        foreach (explode($explode_var, $sql) as $sql) 
                        {
                         $sql = trim($sql);
                          //echo  $sql.'<br/>============<br/>';
                            if($sql) 
                          {
                            if(empty($db_error))
                            {
                                $this->db->query($sql);
                                if($this->db->_error_message() != '')
                                    $db_error.= '<br />'.$this->db->_error_message();
                            }
                           } 
                        }  
                        
                        /*
                        $sql = "set global sql_mode= REPLACE(@@SQL_MODE, 'ONLY_FULL_GROUP_BY', '')";
                        $this->db->query($sql);*/
                        
                        // Update agent, admin, other passwords
                        $data_db_update = array(
                                       'password' => substr(md5(time()+rand(0,1000)),0,5)
                                    );
                        $this->db->update('user', $data_db_update); 
    
                        if($this->db->_error_message() != '')
                            $db_error.= '<br />'.$this->db->_error_message();
    
                        $data_db_update = array(
                                       'username' => $_POST['admin_username'],
                                       'password' => $this->user_m->hash($_POST['admin_password'])
                                    );
    
                        $this->db->where('id', 8);
                        $this->db->update('user', $data_db_update); 
    
                        if($this->db->_error_message() != '')
                            $db_error.= '<br />'.$this->db->_error_message();
                        
                        $data_db_update = array(
                                       'username' => $_POST['agent_username'],
                                       'password' => $this->user_m->hash($_POST['agent_password'])
                                    );
    
                        $this->db->where('id', 9);
                        $this->db->update('user', $data_db_update); 
                        
                        $this->data['admin_username'] = $_POST['admin_username'];
                        $this->data['admin_password'] = $_POST['admin_password'];
                        $this->data['agent_username'] = $_POST['agent_username'];
                        $this->data['agent_password'] = $_POST['agent_password'];
                        
                        if($this->db->_error_message() != '')
                            $db_error.= '<br />'.$this->db->_error_message();

                        $data_db_update = array(
                                       'username' => 'user',
                                       'password' => $this->user_m->hash('user')
                                    );
                        
                        $this->db->where('id', 18);
                        $this->db->update('user', $data_db_update); 
                        
                        // autoincrement for fields
                        $this->db->query('ALTER TABLE `option` auto_increment = 1000;');
                        if($this->db->_error_message() != '')
                            $db_error.= '<br />'.$this->db->_error_message();
        
                        //if($this->db->_error_message() != '')
                        //    $db_error.= '<br />'.$this->db->_error_message();
                        
                    }

                    if(empty($db_error))
                    {
                        $this->data['db_message'] = lang('database_updated');
                        
                        // Save configuration data
                        $this->data['file_message'] = $this->write_file();
                        
                        $this->write_db_production_file();
                        
                        $this->data['show_error'] = false;
                    }
                    else
                    {
                    	$this->data['db_message'] = $db_error;
                        
                        // Show error
                        $this->data['file_message'] = lang('configuration_not_saved');
                        
                        $this->data['show_error'] = true;
                    }
                }
                else
                {
                    $this->lang->load('db');
                    $this->data['db_message'] = lang('db_unable_to_connect');
                    $this->data['file_message'] = lang('configuration_not_saved');
                    $this->data['show_error'] = true;
                }
            }
            else
            {
                $this->data['db_message'] = '';
                
                // Files not writable error
                $this->data['file_message'] = $write_error;
                
                $this->data['show_error'] = true;
            }
            
    		// Load the view
    		$this->load->view('configurator/results', $this->data);
            return;
		}
		
        // Load data
        $this->data['config_data'] = array();
        $this->data['config_data']['l_site_name'] = config_item('site_name');
        $this->data['config_data']['l_language'] = config_item('language');
        $user = config_item('user');
        if(!$user) 
            $user = array();
        $this->data['config_data']['agent_username'] = _ch($user['username'], '');
        $this->data['config_data']['agent_password'] = _ch($user['password'], '');
        $user = config_item('admin');
        if(!$user) 
            $user = array();
        $this->data['config_data']['admin_username'] = _ch($user['username'], '');
        $this->data['config_data']['admin_password'] = _ch($user['password'], '');
        $this->data['config_data']['item_purchase_code'] = config_item('item_purchase_code');
        $this->data['config_data'][''] = config_item('');
        
        $this->data['l_lang_options'] = array(
                  'croatian'    => lang('croatian'),
                  'english'     => lang('english'),
                );

        $this->data['l_type_options'] = array(
                  'demo'        => 'demo',
                  'cms'         => 'cms'
                );
                
        $this->data['l_driver_options'] = array(
                  /*'mysql'        => 'mysql',*/
                  'mysqli'       => 'mysqli'//,
                  //'pdo'          => 'pdo'
                );
        
        if ($handle = opendir(FCPATH.'/templates')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $this->data['l_template_options'][$entry] = $entry;
                }
            }
            closedir($handle);
        }
        
		// Load the view
		$this->load->view('configurator/edit', $this->data);
	}
    
    private function write_file()
    {
        $message = true;
        
        $file_content = '<?php '."\n\n";
        $file_content.= '// App types: demo, cms'."\n";
        $file_content.= '$config[\'app_type\'] = \''.$_POST['app_type'].'\';'."\n\n";
        $file_content.= '// estates pagination'."\n";
        $file_content.= '$config[\'per_page\'] = 8;'."\n\n";
        $file_content.= '//Last approved estates'."\n";
        $file_content.= '$config[\'last_estates_limit\'] = 4;'."\n\n";
        $file_content.= '$config[\'version\'] = \''.APP_VERSION_REAL_ESTATE.'\';'."\n\n";
        $file_content.= '$config[\'default_template_css\'] = \'assets/css/bootstrap.min.css\';'."\n\n";
        $file_content.= '$config[\'codecanyon_username\'] = \''.$_POST['codecanyon_username'].'\';'."\n\n";
        $file_content.= '$config[\'codecanyon_code\'] = \''.$_POST['codecanyon_code'].'\';'."\n\n";
        
        if (function_exists('hash')) {
        $file_content.= '$config[\'hash_function\'] = \'hash\';'."\n\n";
        }
        else
        {
        $file_content.= '$config[\'hash_function\'] = \'md5\';'."\n\n";
        }
        
        $file_content.= '$config[\'installed\'] = true;'."\n\n";
        $file_content.= '$config[\'captcha_disabled\'] = false;'."\n\n";
        //$file_content.= '$config[\'color\'] = \'blue\';'."\n\n";
        $file_content.= '$config[\'admin_beginner_enabled\'] = true;'."\n\n";
        $file_content.= '$config[\'all_results_default\'] = true;'."\n\n";
        
        $filename = APPPATH.'config/cms_config.php';
        
        // In our example we're opening $filename in append mode.
        // The file pointer is at the bottom of the file hence
        // that's where $somecontent will go when we fwrite() it.
        if (!$handle = fopen($filename, 'w')) {
             $message = lang('cannot_open_file')." ($filename)";
             exit;
        }
        
        // Write $somecontent to our opened file.
        if (fwrite($handle, $file_content) === FALSE) {
            $message = lang('cannot_write_file')." ($filename)";
            exit;
        }
        
        fclose($handle);
        
        if($message === true)
            $message = lang('configuration_saved');
        
        return $message;
    }
    
    private function write_db_production_file()
    {
        $message = true;
        
        $file_content = '<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'."\n";
        $file_content.= '/*'."\n";
        $file_content.= '| -------------------------------------------------------------------'."\n";
        $file_content.= '| DATABASE CONNECTIVITY SETTINGS'."\n";
        $file_content.= '| -------------------------------------------------------------------'."\n";
        $file_content.= '| This file will contain the settings needed to access your database.'."\n";
        $file_content.= '|'."\n";
        $file_content.= '| For complete instructions please consult the \'Database Connection\''."\n";
        $file_content.= '| page of the User Guide.'."\n";
        $file_content.= '|'."\n";
        $file_content.= '| -------------------------------------------------------------------'."\n";
        $file_content.= '| EXPLANATION OF VARIABLES'."\n";
        $file_content.= '| -------------------------------------------------------------------'."\n";
        $file_content.= '|'."\n";
        $file_content.= '|	[\'hostname\'] The hostname of your database server.'."\n";
        $file_content.= '|	[\'username\'] The username used to connect to the database'."\n";
        $file_content.= '|	[\'password\'] The password used to connect to the database'."\n";
        $file_content.= '|	[\'database\'] The name of the database you want to connect to'."\n";
        $file_content.= '|	[\'dbdriver\'] The database type. ie: mysql.  Currently supported:'."\n";
        $file_content.= '				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8'."\n";
        $file_content.= '|	[\'dbprefix\'] You can add an optional prefix, which will be added'."\n";
        $file_content.= '|				 to the table name when using the  Active Record class'."\n";
        $file_content.= '|	[\'pconnect\'] TRUE/FALSE - Whether to use a persistent connection'."\n";
        $file_content.= '|	[\'db_debug\'] TRUE/FALSE - Whether database errors should be displayed.'."\n";
        $file_content.= '|	[\'cache_on\'] TRUE/FALSE - Enables/disables query caching'."\n";
        $file_content.= '|	[\'cachedir\'] The path to the folder where cache files should be stored'."\n";
        $file_content.= '|	[\'char_set\'] The character set used in communicating with the database'."\n";
        $file_content.= '|	[\'dbcollat\'] The character collation used in communicating with the database'."\n";
        $file_content.= '|				 NOTE: For MySQL and MySQLi databases, this setting is only used'."\n";
        $file_content.= '| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7'."\n";
        $file_content.= '|				 (and in table creation queries made with DB Forge).'."\n";
        $file_content.= '| 				 There is an incompatibility in PHP with mysql_real_escape_string() which'."\n";
        $file_content.= '| 				 can make your site vulnerable to SQL injection if you are using a'."\n";
        $file_content.= '| 				 multi-byte character set and are running versions lower than these.'."\n";
        $file_content.= '| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.'."\n";
        $file_content.= '|	[\'swap_pre\'] A default table prefix that should be swapped with the dbprefix'."\n";
        $file_content.= '|	[\'autoinit\'] Whether or not to automatically initialize the database.'."\n";
        $file_content.= '|	[\'stricton\'] TRUE/FALSE - forces \'Strict Mode\' connections'."\n";
        $file_content.= '|							- good for ensuring strict SQL while developing'."\n\n";
        $file_content.= '| The $active_group variable lets you choose which connection group to'."\n";
        $file_content.= '| make active.  By default there is only one group (the \'default\' group).'."\n\n";
        $file_content.= '| The $active_record variables lets you determine whether or not to load'."\n";
        $file_content.= '| the active record class'."\n";
        $file_content.= '*/'."\n\n";
        $file_content.= '// Examples: mysql'."\n";
        $file_content.= '$active_group = \'mysql\';'."\n";
        $file_content.= '$active_record = TRUE;'."\n\n";
        $file_content.= '//MySQL example'."\n";
        $file_content.= '$db[\'mysql\'][\'hostname\'] = \''.$_POST['mysql_db_host'].'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'username\'] = \''.$_POST['db_username'].'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'password\'] = \''.str_replace("'", "\\'", $_POST['db_password']).'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'database\'] = \''.$_POST['mysql_db_name'].'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'dbdriver\'] = \''.$_POST['mysql_db_driver'].'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'dbprefix\'] = \'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'pconnect\'] = FALSE;'."\n";
        $file_content.= '$db[\'mysql\'][\'db_debug\'] = FALSE;'."\n";
        $file_content.= '$db[\'mysql\'][\'cache_on\'] = FALSE;'."\n";
        $file_content.= '$db[\'mysql\'][\'cachedir\'] = \'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'char_set\'] = \'utf8\';'."\n";
        $file_content.= '$db[\'mysql\'][\'dbcollat\'] = \'utf8_general_ci\';'."\n";
        $file_content.= '$db[\'mysql\'][\'swap_pre\'] = \'\';'."\n";
        $file_content.= '$db[\'mysql\'][\'autoinit\'] = TRUE;'."\n";
        $file_content.= '$db[\'mysql\'][\'stricton\'] = FALSE;'."\n\n";
        $file_content.= '$db[\'mysql\'][\'port\'] = '.$_POST['mysql_db_port'].';'."\n\n";
        $file_content.= '/* End of file database.php */'."\n";
        $file_content.= '/* Location: ./application/config/database.php */'."\n";
        
        $filename = APPPATH.'config/production/database.php';
        
        // In our example we're opening $filename in append mode.
        // The file pointer is at the bottom of the file hence
        // that's where $somecontent will go when we fwrite() it.
        if (!$handle = fopen($filename, 'w')) {
             $message = lang('cannot_open_file')." ($filename)";
             exit;
        }
    
        // Write $somecontent to our opened file.
        if (fwrite($handle, $file_content) === FALSE) {
            $message = lang('cannot_write_file')." ($filename)";
            exit;
        }

        fclose($handle);
        
        if($message === true)
            $message = lang('configuration_saved');
        
        return $message;
    }
    
	public function check_purchase($str)
	{
		return TRUE;
	    if(!function_exists('curl_version'))
            return TRUE;
       
        $purchase_code = $str;
        $codecanyon_username = $this->input->post('codecanyon_username');
        $your_email = $this->input->post('your_email');
        $my_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        // jSON URL which should be requested
        $json_url = 'http://iwinter.com.hr/real-estate/check_purchase.php?purchase_code='.$purchase_code.'&username='.$codecanyon_username.'&email='.$your_email.'&url='.$my_url;
        
        // Initializing curl
        $ch = curl_init( $json_url );
        
        // Configuring curl options
        $options = array(
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json')
        );
        
        // Setting curl options
        curl_setopt_array( $ch, $options );
        
        // Getting results
        $json = curl_exec($ch); // Getting jSON result string
        
        $decoded_json = json_decode($json);

        if(!is_object($decoded_json))
            return true;
        
        if($decoded_json->result == 'confirmed' || ($str == 'remove_my_website' && ENVIRONMENT == 'development') || $_SERVER['HTTP_HOST']=='localhost')
            return TRUE;
        
    	$this->form_validation->set_message('check_purchase', lang_check('Wrong purchase code, on troubles or if you try install on new domain, contact support team'));

    	return FALSE;
	}

}