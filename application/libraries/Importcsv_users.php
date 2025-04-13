<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class Importcsv_users {
    
    protected $filename; 
    protected $delim=','; 		
    protected $enclosed='"';	 
    protected $escaped='\\';	 	
    protected $lineend='\\r\\n';  	
    
    /* list all option, witch use on site */
    protected $option_list;
    public $time_start;
    public $ajax_limit = 5;
    public $agent_id = 1;
    /* array id of langs, witchuse on sile */
    protected $langs_id;
    
    /* options script*/
    private $options = array(
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            'max_properties_import' => '20',
    );
    
    public function __construct() {
        
        /* add libraries and model */
        $this->CI = &get_instance();
        $this->CI->load->model('file_m');
        $this->CI->load->model('language_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->library('uploadHandler', array('initialize'=>FALSE));
        $this->CI->load->library('ghelper');
        $this->CI->load->model('settings_m');
        
        $this->settings = $this->CI->settings_m->get_fields();
        
        /* class var */
        $this->agent_id=$this->CI->session->userdata('id');
    }
    
    public function get_csv() {
        $file=$this->startExport();
        
        if(!empty($file)){
           // $file="\xEF\xBB\xBF".$file;
            return $file;
        } else {
            return false;
        }
        //return $this->startExport();
    }
        
    /*
     * Function for public for start import csv
     * 
     * @param string $file path and file name with csv
     * return array with id of new estate => address
     */
    public function start_import($file, $limit = 10) {
        return $this->import($file, $overwrite, $max_images,  $google_gps, $limit);
    }
    
    
    /*
     * Start Import
     * 
     * @param string $file path and file name with csv
     * return array with id of new estate => address
     */
    protected function import($file=null, $limit = 10, $ajax = true) {
        $time_start = microtime(true);
        $this->time_start = $time_start;
        
        $this->CI->load->model('option_m');
        
        if(empty($file)) {
            return false;
        }
        
        $csv = file($file);
        /* header csv */
        $header=array();
        $_header=str_getcsv(array_shift($csv),';');;
        foreach ($_header  as $key => $value) {
          $header[]=strtolower($this->remove_utf8_bom(trim($value)));
        }
        /* end header csv */
        
        $csv_t=array();
        foreach ($csv as $key => $line) {
            $csv_line_array =  str_getcsv($line,';');
            if(sw_count($csv_line_array)< 5) {
                    $this->CI->session->set_flashdata('error', 
                            lang_check('Supported only semicolon format'));
                    redirect('admin/estate/import_csv');
                    exit();
            }
            
            if(sw_count($csv_line_array)!= sw_count($header)) {
                   continue;
            }
            
            $csv_t[$key] = array_combine($header, $csv_line_array);
            
          //  break; 
        }
        
        if($ajax) {
            $new_file_name='importcsv_user_'.time().rand(000, 999).'.data';
            file_put_contents(FCPATH.'/files/'.$new_file_name, base64_encode(serialize($csv_t)));
            
            return array(
                'file_name'=>$new_file_name,
                'listings'=>sw_count($csv_t)
            );                    
        }
        
        $this->_count_key_skip=0;
        $this->_count_key=0;
        $this->_count=0;
        $this->_count_skip=0;
       
        /* start add new estate */
        if(!$ajax)
            return $this->import_process($csv_t, $limit);
        
    }
    protected function import_process($csv_t=null, $limit = 10) {
        $time_start = microtime(true);
        if(!empty($this->time_start))
            $time_start = $this->time_start;
        
        $max_exec_time = 120;
        if(config_item('max_exec_time'))
            $max_exec_time = config_item ('max_exec_time');
        
        $this->CI->load->model('file_m');
        $this->CI->load->model('language_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->model('user_m');
        $this->CI->load->library('uploadHandler', array('initialize'=>FALSE));
        $this->CI->load->library('ghelper');
        $this->CI->load->library('form_validation');
        
        if(empty($csv_t)) {
            return false;
        }
        $this->_count_all = sw_count($csv_t);
        /* start add new estate */
        $info=array();
        foreach ($csv_t as $key => $value) {
            $print_data = '';
            $this->_count_key = $key;
            if($this->_count_key_skip > $key) continue;
            
            $id=NULL;
            $insert_id=NULL;
            
            $time_end = microtime(true);
            $execution_time = $time_end - $time_start;
            
            if($execution_time>=$max_exec_time){
                // break import
                return array(
                    'info'=> $info,
                    'count_skip' => $this->_count_skip,
                    'message' => lang_check('max_exec_time reached, you can import again')
                    );
            }
            
            // skip
            if(empty($value['mail'])){
                $this->_count_skip++;
                continue;
            }
            
            if( $this->_count >= $limit) {
                break;
            }
           
            // Set up the form
            // Process the form
            if(!$this->unique_mail($value['mail'])) {
                $print_data = lang_check('user with mail').' '.$value['mail'].' '.lang_check('already exists');
                $this->_count_skip++;
            } else {
                // Set up the form
                $rules = $this->CI->user_m->rules_admin;
                $rules['password']['rules'] = 'trim';

                $this->CI->form_validation->set_rules($rules);
                $_POST = $value;
                // Process the form
                if($this->CI->form_validation->run() == TRUE)
                {
                    //array_from_rules($rules)
                    $data = $this->CI->user_m->array_from_post(array('name_surname', 'password', 'username', 'research_sms_notifications',
                                                                 'address', 'description', 'mail', 'phone', 'phone2', 'type', 
                                                                 'qa_id', 'language', 'activated', 'package_id', 
                                                                 'package_last_payment', 'facebook_id', 'mail_verified', 
                                                                 'phone_verified', 'facebook_link', 'youtube_link', 'payment_details',
                                                                 'gplus_link', 'twitter_link', 'linkedin_link', 'county_affiliate_values', 'embed_video_code',
                                                                 'research_mail_notifications', 'research_sms_notifications', 'agency_id'));

                    if(config_db_item('phone_mobile_enabled') === TRUE)
                        $data['phone2'] = $this->CI->user_m->input->post('phone2');

                    $data['registration_date'] = date('Y-m-d H:i:s');

                    /* check is date valid or remove */
                    if(isset($data['package_last_payment'])){
                        if(sw_is_date($data['package_last_payment']) && (bool)strtotime($data['package_last_payment'])) {
                        } else {
                            unset($data['package_last_payment']);
                        }
                    }
                    
                    if(empty($data['password']) || !isset($data['password'])){
                        $data['password'] = $this->user_m->hash($data['mail']);
                    }
                    
                    if(!isset($data['password'])){
                        $data['activated'] = 1;
                    }
                    
                    // [Custom fields]
                    custom_fields_save($data, 'custom_fields_code');
                    // [/Custom fields]

                    // Create repository
                    $data['repository_id'] = $this->CI->repository_m->save(array('name'=>'user_m'));

                    /* Add file to repository */
                    // upload foto;
                    if(!empty($value['images'])) {
                    $next_order=0;
                    $images= str_replace('"', '', $value['images']);
                    $images = explode(',', $images);
                    foreach ($images as $key => $image_link) {
                        if($file_name = $this->do_upload_optimization(trim($image_link))){
                            $file_id = $this->CI->file_m->save(array(
                            'repository_id' => $data['repository_id'],
                            'order' => $next_order,
                            'filename' => $file_name,
                            'filetype' => 'image/jpeg'
                            )); 
                            $next_order++;
                        } 
                      }  
                    }
                    /* create  image_repository and image_filename */
                    /* end Add file to repository */

                    $insert_id = $this->CI->user_m->save($data, $id);
                    if(empty($insert_id))
                    {
                        $print_data = $this->CI->db->_error_message();
                        $this->_count_skip++;
                    } else {
                        
                    $print_data = lang_check('success added').' <a target="_blank" href="'.site_url('admin/user/edit/'.$insert_id).'">'.lang_check('Open new use #'.$insert_id).'</a>';
                    
                    // [START] Email user about new changes
                    $message_mail = '';
                    $this->CI->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->CI->email->initialize($config_mail);
                    $this->CI->email->from($this->settings['noreply'], lang_check('Web page'));
                    $this->CI->email->to($data['mail']);

                    $this->CI->email->subject(lang_check('Changes on your user profile'));

                    unset($data['qa_id'], $data['activated']);

                    $data['profile_link'] = '<a href="'.site_url('frontend/login/').'?username='.$data['username'].'&$password='.$data['password'].'#content">'.lang_check('Edit profile link').'</a>';

                    $message='';
                    foreach($data as $key=>$mes_value){
                            $message.="$key:\$mes_value\n";
                    }

                    $message = $this->CI->load->view('email/changed_profile_by_admin', array('data'=>$data), TRUE);

                    $this->CI->email->message($message);
                    $this->CI->email->send();
                    // [END] Email user about new changes
                    }

                } else {
                    $error = validation_errors();
                    $print_data = strip_tags($error);
                 
                    $this->_count_skip++;
                }
            }
            $this->_count++;
            $info[]=array(
                'print_data'=> $print_data,
                'id'=> $value["internalid"],
                'preview_id'=> $insert_id
                
            );
            //sleep(3);
            //break;
            
            /* clear var */
            $data = NULL;
            $update_data = NULL;
        }
        /* end start add new estate */
        return array(
                    'info'=> $info,
                    'count_skip' => $this->_count_skip,
                    );
    }
    
    protected  function get_mine_type_from_string($file)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($file);
    }
    
    protected function do_upload_optimization($file_link)
    {
        if(empty($file_link)) return false;

        /* commemt for optimization
        $file_name=substr(strrchr($file_link, '/'), 1);
        $file_link=  str_replace($file_name, rawurlencode($file_name), $file_link);*/

        if(!$this->url_exists($file_link)) return false;

        $file=$this->file_get_contents_curl($file_link);
        $mime_type = $this->get_mine_type_from_string($file);
        if(!$mime_type) {
            return false;
        }
        
        $file_exs = substr($file_link, strripos($file_link, '.')+1);
        
        $allowedTypes = array( 
                        'image/gif',  // [] gif 
                        'image/pjpeg',  // [] jpg 
                        'image/jpeg',  // [] jpg 
                        'image/png',  // [] png 
                        'image/bmp',   // [] bmp 
                        'text/plain'   // possible csv or text 
        ); 				
        if (!in_array($mime_type, $allowedTypes)) { 
            return false; 
        } 
        $is_image = true;
        switch($mime_type) {
            case 'image/gif': $type ='gif'; break;
            case 'image/pjpeg': $type ='jpg'; break;
            case 'image/jpeg': $type ='jpg'; break;
            case 'image/png': $type ='png'; break;
            case 'text/plain': $type = $file_exs;$is_image=false; break;
        }
        $new_file_name=time().rand(000, 999).'.'.$type;
        file_put_contents(FCPATH.'/files/'.$new_file_name, $file);
        /* create thumbnail */
        if($is_image)
            $this->CI->uploadhandler->regenerate_versions($new_file_name);
        /* end create thumbnail */
        return $new_file_name;
    }
    
    /* rename to "do_upload" (old method do_upload, rename to  other name ) for optimization, whithout crop and always save like .jpg */
    protected function do_upload($file_link)
    {   
        if(empty($file_link)) return false;

        $file=$this->file_get_contents_curl($file_link);

        $type ='jpg';
        $new_file_name=time().rand(00, 99).'.'.$type;
        file_put_contents(FCPATH.'/files/'.$new_file_name, $file);
        copy(FCPATH.'/files/'.$new_file_name, FCPATH.'/files/thumbnail/'.$new_file_name);
        return $new_file_name;
    }
        
    private function get_var ($var=null, $default='') {
        return (!empty($var)) ? trim($var,'"') : $default ;
    }
    
    function nl2br2($string) { 
        $string = str_replace(array("\r\n", "\r", "\n"), " ", $string); 
        return $string; 
    } 
    
    /*
     * convert_charset
     * @param string $value - string for convert charset
     * return text after convert charset
     */
    private function convert_charset($value) {
        $value = iconv('UTF-8', 'Windows-1250', $value);
        return $value;
    }
    
    public function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set cURL to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    
    function url_exists($url) {
        $handle   = curl_init($url);
        if (false === $handle)
        {
                return false;
        }

        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
        curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 3);
        $connectable = curl_exec($handle);
        ##print $connectable;
        curl_close($handle);
        if($connectable){
            return true;
        }
        return false;
    }
    
    //Remove UTF8 Bom

    function remove_utf8_bom($text)
    {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }
    
    public function ajax_import ( $import_ini = false, $file=null, $offset = 0) {
        
        if($import_ini) {
            $data_output = $this->import($file, $limit = 10, $ajax_import = TRUE);
            return $data_output;
        } else {
            $this->_count_key_skip = $offset;
            $this->_count=0;
            $this->_count_skip=0;
            
            $new_file_name = $file;
            $csv_t = file_get_contents(FCPATH.'/files/'.$new_file_name);
            $csv_t = unserialize(base64_decode($csv_t));
            $data_output = $this->import_process($csv_t, $this->ajax_limit);
            $data_output['count_key'] = $this->_count_key;
            $data_output['count_all'] = $this->_count_all;
            return $data_output;
        }
    }
    
        
    public function unique_mail($str)
    {
        // Do NOT validate if mail alredy exists
        // UNLESS it's the mail for the current user
        $this->CI->db->where('mail', $str);
        $user = $this->CI->user_m->get();
        
        if(sw_count($user))
        {
            return FALSE;
        }
        
        return TRUE;
    }
}