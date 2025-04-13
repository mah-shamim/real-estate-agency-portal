<?php

class Tokenapi extends CI_Controller
{
    private $data = array();
    private $settings = array();
    
    private $key='4PcY4Dku0JkuretevfEPMnG9BGBPi';
    private $enabled=false;
    
    public function __construct()
    {
        parent::__construct();
        
        if(!$this->enabled && ENVIRONMENT != 'development')exit('DISABLED');
        
        $this->load->model('settings_m');
        $this->settings = $this->settings_m->get_fields();
        
        $this->load->model('user_m');
        $this->load->model('token_m');
        
        header('Content-Type: application/json');
    }
   
	public function index()
	{
        $this->data['message'] = lang_check('Hello, API here!');

        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call:
    /index.php/tokenapi/authenticate/?username=admin&password=admin&key=4PcY4Dku0JkuretevfEPMnG9BGBPi
    
    */
    public function authenticate()
    {
        $this->data['message'] = lang_check('Something is wrong with request');
        $POST = $this->input->get_post(NULL, TRUE);
        //$this->data['parameters'] = $POST;

        if(isset($POST['key'], $POST['username'], $POST['password']) && $POST['key'] == $this->key)
        {
            $this->load->library('session');
            
            // Check if user exists
            if($this->user_m->login($POST['username'], $POST['password']) === TRUE)
            {
                $this->data['user_data'] = $this->user_m->user_session_data;
                
                // Generate, return token
                $token = $this->user_m->hash_token($POST['username'].$POST['password'].time().rand(1,9999));
                $this->data['token'] = $token;
                
                $ip = '';
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
            
                // Delete all previous user token logs
                $this->db->where('user_id', $this->data['user_data']['id']);
                $this->db->delete('token_api');
                        
                // Save token
                $data = array();
                $data['date_last_access'] = date('Y-m-d H:i:s');
                $data['ip'] = $ip;
                $data['username'] = $POST['username'];
                $data['user_id'] = $this->data['user_data']['id'];
                $data['token'] = $this->data['token'];
                $data['other'] = '';
                $this->token_m->save($data);
                
                $this->data['message'] = lang_check('Results available');
            }
        }
        
        echo json_encode($this->data);
        exit();
    }
    
    /*
    
    Example call:
    /index.php/tokenapi/user/?token=b02ec8d9b3d7ca1bb8e9e8880245166c
    
    */
	public function user()
	{
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
        
        $POST = $this->input->get_post(NULL, TRUE);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        if(is_object($token))
        {
            $user = $this->user_m->get_by(array(
                                            'id' => $token->user_id,
                                            'activated' => 1,
                                        ), TRUE);
            $res_user = array();
            $res_user['username'] = $user->username;
            $res_user['name_surname'] = $user->name_surname;
            $res_user['address'] = $user->address;
            $res_user['phone'] = $user->phone;
            $res_user['mail'] = $user->mail;
            $res_user['type'] = $user->type;
            $res_user['language'] = $user->language;
            $res_user['description'] = $user->description;
            $res_user['image_user_filename'] = $user->image_user_filename;
            
            $this->data['results'] = $res_user;
            
            $this->data['message'] = lang_check('Results available');
        }

        echo json_encode($this->data);
        exit();
	}

    /*
    Example call:
    /index.php/tokenapi/delete_user/?token=b02ec8d9b3d7ca1bb8e9e8880245166c
    
    */
	public function deactivate_user()
	{
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
        
        $POST = $this->input->get_post(NULL, TRUE);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        if(is_object($token))
        {
            $user = $this->user_m->get_by(array(
                                            'id' => $token->user_id,
                                            'activated' => 1,
                                        ), TRUE);

            $this->user_m->deactivate_user($user->id);

            $this->data['message'] = lang_check('User deactivated');
        }

        echo json_encode($this->data);
        exit();
	}

    /*
    Example call:
    /index.php/tokenapi/delete_user/?token=b02ec8d9b3d7ca1bb8e9e8880245166c
    
    */
	public function delete_user ()
	{

        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
        
        $POST = $this->input->get_post(NULL, TRUE);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        if(is_object($token))
        {
            $user = $this->user_m->get_by(array(
                                            'id' => $token->user_id,
                                            'activated' => 1,
                                        ), TRUE);

            if(!$user){
                $this->data['message'] = lang_check('User missing');
                echo json_encode($this->data);
                exit();
            }

            if($this->config->item('app_type') == 'demo')
            {
                if($user->id < 30){
                    $this->data['message'] = lang_check('Disabled in demo');
                    echo json_encode($this->data);
                    exit();
                }
            }

            /* remove all listings data */
            if(true) {
                $listings = $this->user_m->get_estates($user->id);
                $this->load->model('estate_m');
                foreach($listings as $listing_id)
                {
                    if(is_numeric($listing_id))
                        $this->estate_m->delete($listing_id);
                }
            }

            /* remove all data */
            $models = array('userattend_m','savedsearch_m','reviews_m','reservations_m','payments_m','invoice_m','historyads_m','favorites_m');

            foreach ($models as $model) {
                if(!file_exists(APPPATH.'models/'.$model.'.php')) continue;

                $this->load->model($model);
                $items =  $this->{$model}->get_by(array('user_id', $user->id));
                if ($items)
                {
                    foreach ($items as $item)
                    {
                        if(!empty($item['id']))
                            $this->{$model}->delete($item['id']);
                    }
                }
            }

            /* remove all data where user is agent */
            $models = array('reports_m','masking_m','enquire_m');
            $this->load->library('session');

            foreach ($models as $model) {
                if(!file_exists(APPPATH.'models/'.$model.'.php')) continue;
                
                $this->load->model($model);
                $items =  $this->{$model}->get_by(array('agent_id', $user->id));
                if ($items)
                {
                    foreach ($items as $item)
                    {
                        if(!empty($item['id']))
                            $this->{$model}->delete($item['id']);
                    }
                }
            }
            /* remove user mapreport_m */
            if(file_exists(APPPATH.'models/mapreport_m.php')){
                $this->load->model('mapreport_m');
                $items = $this->mapreport_m->get_by(array('user_id_agent', $user->id));
                if ($items)
                {
                    foreach ($items as $item)
                    {
                        if(!empty($item['id']))
                            $this->mapreport_m->delete($item['id']);
                    }
                }
            }

            // remove user
            $this->user_m->delete($user->id);
            $this->data['message'] = lang_check('User and data removed, user id ').'#'.$user->id;
        }

        echo json_encode($this->data);
        exit();
	}
    /*
    
    Example call:
    /index.php/tokenapi/register/?mail=sandi1@gmail.com&password=sandi1&key=4PcY4Dku0JkuretevfEPMnG9BGBPi
    
    */
	public function register()
	{
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['success'] = FALSE;
        $POST = $this->input->get_post(NULL, TRUE);
        //$this->data['parameters'] = $POST;

        if(config_db_item('property_subm_disabled')==TRUE)
        {
            $this->data['message'] = lang_check('Registration disabled on server');
        }
        else if(isset($POST['key'], $POST['mail'], $POST['password']) && $POST['key'] == $this->key)
        {
            $this->load->library('session');
            
            $user_exists = $this->user_m->get_by(array(
                'username = \''.$POST['mail'].'\' OR mail = \''.$POST['mail'].'\'' => NULL
            ), TRUE);
        
            // Additional check to login with email
            if(sw_count($user_exists) > 0)
            {
                $this->data['message'] = lang_check('Email already exists');
            }            
            else if (!filter_var($POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $this->data['message'] = lang_check('Invalid email');
            }
            else if(strlen($POST['password']) < 6)
            {
                $this->data['message'] = lang_check('Longer password required');
            }
            else
            {
                $this->load->model('language_m');
                $this->data['message'] = '';
                
                $data = array();
                $data['name_surname'] = $POST['mail'];
                $data['username'] = $POST['mail'];
                $data['mail'] = $POST['mail'];
                $data['password'] = $this->user_m->hash($POST['password']);
                $data['type'] = 'USER';
                $data['activated'] = '1';
                if(config_db_item('email_activation_enabled') === TRUE)
                    $data['activated'] = '0';
                $data['description'] = '';
                $data['language'] = $this->language_m->get_name($this->language_m->get_default());
                $data['registration_date'] = date('Y-m-d H:i:s');
                $data['mail_verified'] = 0;
                $data['phone_verified'] = 0;

                if($this->config->item('def_package_agent') !== FALSE && $data['type'] == 'AGENT')
                {
                    $data['package_id'] = $this->config->item('def_package_agent');
                    
                    $this->load->model('packages_m');
                    $package = $this->packages_m->get($data['package_id']);
                    
                    if(is_object($package))
                    {
                        $days_extend = $package->package_days;
                    
                        if($days_extend > 0)
                            $data['package_last_payment'] = date('Y-m-d H:i:s', time() + 86400*intval($days_extend));
                    }
                }
                
                $user_id = $this->user_m->save($data, NULL);
                
                $message_mail = '';
                if($data['mail'] && config_db_item('email_activation_enabled') === TRUE)
                {
                    $data['mail_verified'] = 0;
                    // [START] Activation email

                    if(!empty($data['mail']))
                    {
                        $this->load->library('email');
                        $config_mail['mailtype'] = 'html';
                        $this->email->initialize($config_mail);
                        $this->email->from($this->settings['noreply'], lang_check('Web page'));
                        $this->email->to($data['mail']);

                        $this->email->subject(lang_check('Activate your account'));

                        $new_hash = substr($this->user_m->hash($data['mail'].$user_id), 0, 5);

                        $data_m = array();
                        $data_m['name_surname'] = $data['name_surname'];
                        $data_m['username'] = $data['username'];
                        $data_m['activation_link'] = '<a href="'.site_url('admin/user/verifyemail/'.$user_id.'/'.$new_hash).'">'.lang_check('Activate your account').'</a>';
                        $data_m['login_link'] = '<a href="'.site_url('frontend/login/').'?username='.$data['username'].'#content">'.lang_check('login_link').'</a>';

                        $message = $this->load->view('email/email_activation', array('data'=>$data_m), TRUE);

                        $this->email->message($message);
                        if ( ! $this->email->send())
                        {
                            $message_mail = ', '.lang_check('Problem sending email to user');
                        }
                    }
                    // [END] Activation email
                }
                
                if(!empty($user_id))
                {
                    $this->data['message'] = lang_check('Register success');
                    $this->data['success'] = TRUE;
                } 
            }
        }
        
        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call for GET:
    /index.php/tokenapi/favorites/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&lang_code=en
    
    Example call for POST:
    /index.php/tokenapi/favorites/POST/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&lang_code=en&property_id=8
    
    Example call for DELETE:
    /index.php/tokenapi/favorites/DELETE/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&property_id=8
    
    */
	public function favorites($method='GET')
	{
        $data_tmp['listing_uri'] = config_item('listing_uri');
        if(empty($data_tmp['listing_uri']))$data_tmp['listing_uri'] = 'property';    
            
        $this->load->model('language_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('favorites_m');
       
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
        $POST = $this->input->get_post(NULL, TRUE);
        
        if(isset($POST['lang_code']))
        {
            $lang_id = $this->language_m->get_id($POST['lang_code']);
        }
        
        if(empty($lang_id))$lang_id=$this->language_m->get_default_id();
        $lang_code = $this->language_m->get_code($lang_id);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        if(is_object($token))
        {
            if($method == 'GET')
            {
                $options = $this->option_m->get_field_list($this->language_m->get_default_id());
                
                $this->db->join('favorites', 'property.id = favorites.property_id', 'right');
                $this->db->where('user_id', $token->user_id);
                $estates = $this->estate_m->get_by(array('is_activated' => 1, 'language_id'=>$lang_id));
                
                // Set website details
                $json_data = array();
                // Add listings to rss feed     
                foreach($estates as $key=>$row){
                    $estate_date = array();
                    $title = $this->estate_m->get_field_from_listing($row, 10);
                    $url = site_url($data_tmp['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.url_title_cro($title));
                    
                    $row->json_object = json_decode($row->json_object);
                    $row->image_repository = json_decode($row->image_repository);
                    $estate_date['url'] = $url;
                    $estate_date['listing'] = $row;
                    
                    $json_data[] = $estate_date;
                }
                
                $this->data['results'] = $json_data;
                
                $this->data['message'] = lang_check('Results available');
            }
            elseif($method == 'POST' && isset($POST['property_id']))
            {
                $property_id = $POST['property_id'];
                
                $this->data['success'] = false;
                // Check if property_id already saved, stop and write message
                if($this->favorites_m->check_if_exists($token->user_id, $property_id)>0)
                {
                    $this->data['message'] = lang_check('Favorite already exists!');
                    $this->data['success'] = true;
                }
                // Save favorites to database
                else
                {
                    $data = $this->favorites_m->get_new_array();
                    $data['user_id'] = $token->user_id;
                    $data['property_id'] = $property_id;
                    $data['lang_code'] = $lang_code;
                    $data['date_last_informed'] = date('Y-m-d H:i:s');
                    
                    $this->favorites_m->save($data);
                    
                    $this->data['message'] = lang_check('Favorite added!');
                    $this->data['success'] = true;
                } 
            }
            elseif($method == 'DELETE' && isset($POST['property_id']))
            {
                $property_id = $POST['property_id'];
                
                $this->data['success'] = false;
                // Check if property_id already saved, stop and write message
                if($this->favorites_m->check_if_exists($token->user_id, $property_id)>0)
                {
                    $favorite_selected = $this->favorites_m->get_by(array('property_id'=>$property_id, 'user_id'=>$token->user_id), TRUE);
                    $this->favorites_m->delete($favorite_selected->id);
                    
                    $this->data['message'] = lang_check('Favorite removed!');
                    $this->data['success'] = true;
                }
                // Save favorites to database
                else
                {
                    $this->data['message'] = lang_check('Favorite doesnt exists!');
                    $this->data['success'] = true;
                }
            }
        }

        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call:
    /index.php/tokenapi/submission/?token=b02ec8d9b3d7ca1bb8e9e8880245166c
                                    &lang_code=en
                                    &input_address=Vukovar
                                    &input_title=nice home
                                    &input_4=nice home
                                    &input_description=my description
                                    &input_36=10000
                                    
    To edit or send iamges separated send also property_id=XX
    
    */
    
    public function submission()
    {
        $title_field_id = 10;
        $content_short_field_id = 8;
        $content_long_field_id = 17;
        $auto_activate = 1;
        
        $this->load->model('language_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['success'] = FALSE;
        $this->data['token_available'] = FALSE;
        $POST = $this->input->get_post(NULL, TRUE);

        $POST = array_merge($_GET, $_POST);

        if(isset($POST['lang_code']))
        {
            $lang_id_selected = $this->language_m->get_id($POST['lang_code']);
        }
        
        $lang_id_def = $this->language_m->get_default_id();
        $lang_code_def = $this->language_m->get_code($lang_id_def);
        
        $token = $this->token_m->get_token($POST);
        
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        //var_dump($POST);
        
        if(config_db_item('property_subm_disabled')==TRUE)
        {
            $this->data['message'] = lang_check('Registration disabled on server');
        }
        else if(isset($POST['lang_code']) && $lang_id_selected != $lang_id_def)
        {
            $this->data['message'] = lang_check('Only default lang is supported');
        }
        else if(is_object($token) && isset($POST['lang_code']) && isset($POST['input_address'], 
                                                   $POST['input_title'],
                                                   $POST['input_description'],
                                                   $POST['input_4']))
        {

            $existing_fields = $this->option_m->get_field_list($lang_id_def);

            // check if fields exists
            foreach($POST as $key=>$val)
            {
                $exp = explode('_', $key);
                
                if(count($exp) == 2 && $exp[0]=='input' && is_numeric($exp[1]))
                {
                    if(!isset($existing_fields[$exp[1]]))
                    {    
                        unset($POST['input_'.$exp[1]]);

                        $this->data['message'] = lang_check('Field not found: #').$exp[1];
                        echo json_encode($this->data);
                        exit();
                    }
                }
            }
            
            if(isset($POST['property_id']))
            {
                
                if(empty($POST['input_description']) || empty($POST['input_title']))
                {
                    $this->data['message'] = lang_check('Please populate all fields!');
                    echo json_encode($this->data);
                    exit();
                }

                $this->load->library('session');

                // check permission for edit
                if($this->estate_m->check_user_permission($POST['property_id'], $token->user_id)>0)
                {

                    // edit
                    $data = array();
                    $data['date'] = date('Y-m-d H:i:s');
                    $data['date_modified'] = date('Y-m-d H:i:s');
                    $data['address'] = $POST['input_address'];
                    $data['search_values'] = $data['address'];
                    
                    // fetch gps
                    $this->load->library('ghelper');
                    $coor = $this->ghelper->getCoordinates($data['address']);
                    $data['gps'] = $coor['lat'].', '.$coor['lng'];
                    
                    // other dynamic data
                    $dynamic_data = array();
                    $dynamic_data['agent'] = $token->user_id;
                    
                    // get title
                    $dynamic_data["option".$title_field_id."_".$lang_id_def] = $POST['input_title'];
                    
                    // get description
                    $dynamic_data["option".$content_short_field_id."_".$lang_id_def] = $POST['input_description'];
                    $dynamic_data["option".$content_long_field_id."_".$lang_id_def] = $POST['input_description'];
                    
                    // prepare other fields
                    foreach($POST as $key=>$val)
                    {
                        $exp = explode('_', $key);
                        
                        if(sw_count($exp) == 2 && $exp[0]=='input' && is_numeric($exp[1]))
                        {
                            $dynamic_data["option".$exp[1]."_".$lang_id_def] = $POST['input_'.$exp[1]];
                        }
                    }
    
                    // save basic data
                    $insert_id = $this->estate_m->save($data, $POST['property_id']);
                    
                    if(empty($insert_id))
                    {
                        echo 'EMPTY insert_id:<br />';
                        echo $this->db->last_query();
                        exit();
                    }
                    
                    $this->config->set_item('multilang_on_qs', 0);
                    
                    $this->estate_m->save_dynamic($dynamic_data, $insert_id);
                    
                    // echo $this->db->last_query();
                    
                    if(!empty($insert_id))
                    {
                        $this->uploadfiles($insert_id);
                        
                        $this->data['message'] = lang_check('Listing saved');
                        $this->data['success'] = TRUE;
                    }
                    else
                    {
                        $this->data['message'] = lang_check('Edit declined');
                    }
                }

            }
            else
            {
                // add
                
                if(empty($POST['input_description']) || empty($POST['input_title']))
                {
                    $this->data['message'] = lang_check('Please populate all fields!');
                    echo json_encode($this->data);
                    exit();
                }

                $data = array();
                $data['is_activated'] = $auto_activate;
                $data['date'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');
                $data['address'] = $POST['input_address'];
                $data['search_values'] = $data['address'];
                
                if($data['is_activated'])
                    $data['date_activated'] = date('Y-m-d H:i:s');
                    
                // fetch gps
                $this->load->library('ghelper');
                $coor = $this->ghelper->getCoordinates($data['address']);
                $data['gps'] = $coor['lat'].', '.$coor['lng'];
                
                // other dynamic data
                $dynamic_data = array();
                $dynamic_data['agent'] = $token->user_id;
                
                // get title
                $dynamic_data["option".$title_field_id."_".$lang_id_def] = $POST['input_title'];
                
                // get description
                $dynamic_data["option".$content_short_field_id."_".$lang_id_def] = $POST['input_description'];
                $dynamic_data["option".$content_long_field_id."_".$lang_id_def] = $POST['input_description'];
                
                // prepare other fields
                foreach($POST as $key=>$val)
                {
                    $exp = explode('_', $key);
                    
                    if(sw_count($exp) == 2 && $exp[0]=='input' && is_numeric($exp[1]))
                    {
                        $dynamic_data["option".$exp[1]."_".$lang_id_def] = $POST['input_'.$exp[1]];
                    }
                }

                // save basic data
                $insert_id = $this->estate_m->save($data, NULL);
                
                if(empty($insert_id))
                {
                    echo 'EMPTY insert_id:<br />';
                    echo $this->db->last_query();
                    exit();
                }
                
                $this->config->set_item('multilang_on_qs', 0);
                
                $this->estate_m->save_dynamic($dynamic_data, $insert_id);
                
                // echo $this->db->last_query();
                
                if(!empty($insert_id))
                {
                    $this->uploadfiles($insert_id);
                    
                    $this->data['message'] = lang_check('Listing added');
                    $this->data['success'] = TRUE;
                    
                    if($auto_activate == 0){
                        $email_activator = config_db_item('email');

                        // Send email alert to contact address
                        $this->load->library('email');

                        $config_mail['mailtype'] = 'html';
                        $this->email->initialize($config_mail);

                        $this->email->from($this->settings['noreply'], lang_check('Web page not-activated property'));
                        $this->email->to($email_activator);

                        $subject = lang_check('New not-activated property from user');

                        $this->email->subject($subject);
                        $this->load->model('user_m');
                        $data_user = $this->user_m->get($token->user_id);
                        
                        $data_m = array();
                        $data_m['subject'] = $subject;
                        $data_m['username'] = $data_user->username;
                        $data_m['link'] = '<a href="'.site_url('admin/estate/edit/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
                        $message = $this->load->view('email/waiting_for_activation', array('data'=>$data_m), TRUE);

                        $this->email->message($message);
                        if ( ! $this->email->send())
                        {
                            //$this->session->set_flashdata('email_sent', 'email_sent_false');
                        }
                        else
                        {
                            //$this->session->set_flashdata('email_sent', 'email_sent_true');
                        }
                    }
                }
                else
                {
                    $this->data['message'] = lang_check('Added declined');
                }
            }
        }     
        
        echo json_encode($this->data);
        exit();
    }
    
    private function uploadfiles($listing_id)
    {
        $uploadFolder = dirname($_SERVER['SCRIPT_FILENAME']).'/files/';
        
        $this->data['estate'] = $this->estate_m->get_dynamic($listing_id);
        // Fetch file repository
        $repository_id = $this->data['estate']->repository_id;
        if(empty($repository_id))
        {
            // Create repository
            $repository_id = $this->repository_m->save(array('name'=>'estate_m'));
            
            // Update page with new repository_id
            $this->estate_m->save(array('repository_id'=>$repository_id), $this->data['estate']->id);
        }
            
        $this->load->library('uploadHandler', array('initialize'=>FALSE));
        
        $this->data['message_image'] = array();
        if (isset($_FILES["files"]) && is_array($_FILES["files"])) {
            $numberOfFiles = sw_count($_FILES["files"]["name"]);
            for ($i = 0; $i < $numberOfFiles; $i++) { //ignore this comment >

                $filename_db = $this->uploadhandler->get_file_name(basename($_FILES["files"]["name"][$i]), null, null, null);
            
                $uploadFile = $uploadFolder . "/" . $filename_db;
                $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        
                if (!(getimagesize($_FILES["files"]["tmp_name"][$i]) !== false)) {
                    $this->data['message_image'][$i] = lang_check("Sorry, your image is invalid");
                }
                else if ($_FILES["files"]["size"][$i] > 10000000) {
                    $this->data['message_image'][$i] = lang_check("Sorry, your file is too large.");
                }
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $this->data['message_image'][$i] = lang_check("Sorry, only JPG, JPEG & PNG files are allowed.");
                }
                else if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $uploadFile)) {
                    $this->data['message_image'][$i] = lang_check("Upload image successfully: ").$filename_db;

                    $this->uploadhandler->regenerate_versions($filename_db, '');
                    
                    $this->file_m->delete_cache($filename_db);
                    
                    $next_order = $this->file_m->get_max_order()+1;
                    
                    // Add file to repository
                    $file_id = $this->file_m->save(array(
                        'repository_id' => $repository_id,
                        'order' => $next_order,
                        'filename' => $filename_db,
                        'filetype' => $imageFileType
                    ));
                    
                    if(empty($file_id))
                    {
                        $this->data['errors'][] = lang_check("Insert image into DB failed.");
                    }
                    
                } else {
                    $this->data['message_image'][$i] = lang_check("Sorry, there was an error uploading your file.");
                }
            }
            
            // insert files into repository
            
            // run to resave cached image repository details
            $insert_id = $this->estate_m->save(array(), $listing_id);
        }
    }
    
    // TODO: below still need to be done

	public function searches($method='GET')
	{
        $this->data['message'] = lang_check('Hello, API here!');

        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call for GET:
    /index.php/tokenapi/listings/?token=784cc4bcf5fe2a183d1197128b690ef9&lang_code=en

    Example call for DELETE:
    /index.php/tokenapi/listings/DELETE/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&property_id=8
    
    1654c1115bac220aab7d599ffc75de0d
    
        
    */
	public function listings($method='GET')
	{
            
        $data_tmp['listing_uri'] = config_item('listing_uri');
        if(empty($data_tmp['listing_uri']))$data_tmp['listing_uri'] = 'property';   
            
        $this->load->model('language_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
       
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
        $POST = $this->input->get_post(NULL, TRUE);
        
        if(isset($POST['lang_code']))
        {
            $lang_id = $this->language_m->get_id($POST['lang_code']);
        }
        
        if(empty($lang_id))$lang_id=$this->language_m->get_default_id();
        $lang_code = $this->language_m->get_code($lang_id);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
            
        if(is_object($token))
        {
            if($method == 'GET')
            {
                $options = $this->option_m->get_field_list($this->language_m->get_default_id());
                $this->db->join('property_user', 'property_user.property_id = property.id', 'right');
                $this->db->where('user_id', $token->user_id);
                $estates = $this->estate_m->get_by(array('language_id'=>$lang_id));
                
                // Set website details
                $json_data = array();
                // Add listings to rss feed     
                foreach($estates as $key=>$row){
                    $estate_date = array();
                    $title = $this->estate_m->get_field_from_listing($row, 10);
                    $url = site_url($data_tmp['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.url_title_cro($title));
                    
                    $row->json_object = json_decode($row->json_object);
                    $row->image_repository = json_decode($row->image_repository);
                    $estate_date['url'] = $url;
                    $estate_date['listing'] = $row;
                    
                    $json_data[] = $estate_date;
                }
                
                $this->data['results'] = $json_data;
                
                $this->data['message'] = lang_check('Results available');
            }
            elseif($method == 'DELETE' && isset($POST['property_id']))
            {
                $this->load->library('session');
                $property_id = $POST['property_id'];
                
                $this->data['success'] = false;
                // Check if property_id already saved, stop and write message
                if($this->estate_m->check_user_permission($property_id, $token->user_id)>0)
                {
                    $this->estate_m->delete($property_id);
                    
                    $this->data['message'] = lang_check('Listing removed!');
                    $this->data['success'] = true;
                }
                // Save favorites to database
                else
                {
                    $this->data['message'] = lang_check('Listing doesnt exists!');
                    $this->data['success'] = true;
                }
            }
        }

        echo json_encode($this->data);
        exit();
	}
    
        
        
    /*     
    Example call for GET:
    /index.php/tokenapi/push_notifications/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&lang_code=en
    */
        
    public function push_notifications($method='GET')
	{
        
        $data_tmp['listing_uri'] = config_item('listing_uri');
        if(empty($data_tmp['listing_uri']))$data_tmp['listing_uri'] = 'property';
        
        $this->load->model('language_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('enquire_m');
        $this->load->model('packages_m');
        $this->load->library('session');
       
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
        $POST = $this->input->get_post(NULL, TRUE);
        
        $on_last_days = 50;
        
        if(isset($POST['lang_code']))
        {
            $lang_id = $this->language_m->get_id($POST['lang_code']);
        }
        
        if(empty($lang_id))$lang_id=$this->language_m->get_default_id();
        $lang_code = $this->language_m->get_code($lang_id);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        if(is_object($token))
        {
            if($method == 'GET')
            {
                $json_data= array();
                
                // Enquire  fetch
                $min_date = date('Y-m-d H:i:s', time()- $on_last_days*24*60*60);
                $enquire = $this->enquire_m->get_by(array('enquire.date >'=>$min_date), FALSE, NULL, 'enquire.id DESC');
                foreach($enquire as $key=>$row){
                    $data = array();
                    
                    $url = site_url('admin/enquire/edit/'.$row->id);
                    $data['id'] = $row->id.'-enquire';
                    $data['type'] = 'enquire';
                    $data['url'] = $url;
                    $data['date'] = $row->date;
                    $data['message'] = lang_check('You receive new message').': '.$row->message;
                    
                    $json_data[] = $data;
                }
                
                // Listings will expired fetch
                if($this->settings['listing_expiry_days'] >0)  {
                    $max_date = date('Y-m-d H:i:s', time() - $this->settings['listing_expiry_days']*86400);

                    $listings_soon_expired = $this->estate_m->get_by(array('date_modified >'=>$max_date));
                    foreach($listings_soon_expired as $key=>$row){
                        $data = array();

                        $title = $this->estate_m->get_field_from_listing($row, 10);
                        $url = site_url($data_tmp['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.url_title_cro($title));
                        $date_expired = date('Y-m-d H:i:s', strtotime($row->date_modified+$this->settings['listing_expiry_days']*86400));

                        $data['id'] = $row->id.'-listings_soon_expired';
                        $data['type'] = 'listings_soon_expired';
                        $data['url'] = $url;
                        $data['date'] =  $date_expired;
                        $data['message'] = lang_check('You receive new message').': '.lang_check('Listing').' '.$title.' ('.$row->address.'), '.lang_check('will expired at').'  '.$date_expired;

                        $json_data[] = $data;
                    }
                    // Listings expired fetch
                    $max_date = date('Y-m-d H:i:s', time() - $this->settings['listing_expiry_days']*86400);
                    $listings_expired = $this->estate_m->get_by(array('date_modified <'=>$max_date));
                    foreach($listings_expired as $key=>$row){
                        $data = array();
                        $title = $this->estate_m->get_field_from_listing($row, 10);
                        $url = site_url($data_tmp['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.url_title_cro($title));
                        $date_expired = date('Y-m-d H:i:s', strtotime($row->date_modified+$this->settings['listing_expiry_days']*86400));
                        $data['id'] = $row->id.'-listings_expired';
                        $data['type'] = 'listings_expired';
                        $data['url'] = $url;
                        $data['date'] =  $date_expired;
                        $data['message'] = lang_check('You receive new message').': '.lang_check('Listing').' '.$title.' ('.$row->address.'), '.lang_check('expired at').'  '.$date_expired;

                        $json_data[] = $data;
                    }
                }
                
                // Package expired fetch
                $user = $this->user_m->get($this->session->userdata('id'));
                $package = $this->packages_m->get($user->package_id);
                if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
                {
                    $data = array();
                    $url = site_url('frontend/myproperties/');
                    $data['id'] = $row->id.'-package_expired';
                    $data['type'] = 'package_expired';
                    $data['url'] = $url;
                    $data['date'] =  $user->package_last_payment;
                    $data['message'] = lang_check('You receive new message').': '.lang_check('Date for your package expired, please extend');
                    $json_data[] = $data;
                }
                $this->data['results'] = $json_data;
                $this->data['message'] = lang_check('Notifications');
            }
        }

        echo json_encode($this->data);
        exit();
    }
       
}
