<?php

class Tools extends Admin_Controller
{
	public function __construct(){
		parent::__construct();
        $this->load->model('page_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
    }
    
    public function index()
	{
	   echo 'Hello from tools!';
	}
    
    // fix gps to lat, lng convert
    public function fix_gps()
	{
        echo 'FIX GPS START'.'<br />';
        
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
        
        echo 'FOR UPDATE: '.sw_count($data_batch).'<br />';
        
        if(sw_count($data_batch) > 0)
            $this->db->update_batch('property', $data_batch, 'id'); 
        
        echo 'FIX GPS END'.'<br />';
	}
    
    // fix image_filename column in property convert
    public function fix_image_filename()
	{
        echo 'FIX image_filename START'.'<br />';
        
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
        
        echo 'FOR UPDATE PROPERTY: '.sw_count($data_batch).'<br />';
        
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
        
        echo 'FOR UPDATE USERS: '.sw_count($data_batch).'<br />';
        
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
        
        echo 'FOR UPDATE PAGE: '.sw_count($data_batch).'<br />';
        
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
        
        echo 'FOR UPDATE SHOWROOM: '.sw_count($data_batch).'<br />';
        
        if(sw_count($data_batch) > 0)
            $this->db->update_batch('showroom', $data_batch, 'id'); 
        /* [/SHOWROOM] */
        
        echo 'FIX image_filename END'.'<br />';
	}
    
    // fix data_structure changed
    public function fix_data_structure()
	{
	   //$this->output->enable_profiler(TRUE);
       
        echo 'FIX DATA property_lang START'.'<br />';
        
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
        
        echo 'FOR INSERT: '.sw_count($data_batch).'<br />';

        if(sw_count($data_batch) > 0)
        {
            $this->db->truncate('property_lang');
            $this->db->insert_batch('property_lang', $data_batch); 
        }
        
        echo 'FIX DATA property_lang END'.'<br />';
	}
    
        /* added field 81(Start date) and 82 (End date), generate random date  */    
        public function dates_udora() {

              $this->db->query("
              INSERT INTO `option` (`id`,`parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`) VALUES
              (81, 1, 11, 'DATETIME', 0, 1, 1, 0);
              ");

              $this->db->query("
              INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
              (81, 1,'Start date','','','');
              ");

              $this->db->query("
              INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
              (81, 2,'Start date','','','');
              ");

              $this->db->query("
              INSERT INTO `option` (`id`,`parent_id`, `order`, `type`, `visible`, `is_locked`, `is_frontend`, `is_hardlocked`) VALUES
              (82, 1, 12, 'DATETIME', 0, 1, 1, 0);
              ");

              $this->db->query("
              INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
              (82, 1,'End date','','','');
              ");

              $this->db->query("
              INSERT INTO `option_lang` (`option_id`, `language_id`, `option`, `values`, `prefix`, `suffix`) VALUES
              (82, 2,'End date','','','');
              ");
              $this->session->set_userdata('type','ADMIN');

              $this->load->model('estate_m');
              $this->load->model('language_m');
              $this->load->model('option_m');
                    $langs_object = $this->language_m->get();
                    $results_obj_id = $this->estate_m->get();

                    if($results_obj_id and !empty($results_obj_id))
                        foreach ($results_obj_id as $key => $estate_id) {
                            $estate_id = $estate_id->id;


                            $options_data_dynamic = $this->estate_m->get_dynamic_array($estate_id);

                            foreach ( $langs_object as $lang_ob) {

                                $min = strtotime('2016-12-30 17:41:01');
                                $max = strtotime('2017-03-15 17:41:01');
                                $val = rand($min, $max);
                                $options_data_dynamic['option81_'.$lang_ob->id] = date('Y-m-d H:i:s', $val);  

                                $min = strtotime('2017-03-15 17:41:01');
                                $max = strtotime('2018-01-02 17:41:01');
                                $val = rand($min, $max);
                                $options_data_dynamic['option82_'.$lang_ob->id] = date('Y-m-d H:i:s', $val);  
                            }

                            $this->estate_m->save_dynamic($options_data_dynamic, $estate_id);
                        }

                  $this->option_m->numeric_field_range(81, 'DATETIME');    
                  $this->option_m->numeric_field_range(82, 'DATETIME');    

        }  
    
    
}