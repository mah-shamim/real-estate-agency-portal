<?php

class User_m extends MY_Model {
    
    protected $_table_name = 'user';
    protected $_order_by = 'name_surname ';
    public $rules = array(
        'username' => array('field'=>'username', 'label'=>'lang:Username', 'rules'=>'trim|required|xss_clean'),
        'password' => array('field'=>'password', 'label'=>'lang:Password', 'rules'=>'trim|required')
    );
    public $rules_admin = array(
        'name_surname' => array('field'=>'name_surname', 'label'=>'lang:Name and surname', 'rules'=>'trim|required|xss_clean'),
        'username' => array('field'=>'username', 'label'=>'lang:Username', 'rules'=>'trim|required|callback__unique_username|xss_clean'),
        'mail' => array('field'=>'mail', 'label'=>'lang:Mail', 'rules'=>'trim|required|xss_clean|callback__unique_mail'),
        'password' => array('field'=>'password', 'label'=>'lang:Password', 'rules'=>'trim|matches[password_confirm]|min_length[8]'),
        'password_confirm' => array('field'=>'password_confirm', 'label'=>'lang:PasswordConfirm', 'rules'=>'trim'),
        'agency_id' => array('field'=>'agency_id', 'label'=>'lang:Agency Related', 'rules'=>'trim'),
        'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim|xss_clean'),
        'description' => array('field'=>'description', 'label'=>'lang:Description', 'rules'=>'trim'),
        'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|xss_clean'),
        'phone2' => array('field'=>'phone2', 'label'=>'lang:Mobile phone', 'rules'=>'trim|xss_clean'),
        'type' => array('field'=>'type', 'label'=>'lang:Type', 'rules'=>'trim|required|xss_clean'),
        'language' => array('field'=>'language', 'label'=>'lang:language', 'rules'=>'trim|required|xss_clean'),
        'mail_verified' => array('field'=>'mail_verified', 'label'=>'lang:Mail verified', 'rules'=>'trim|xss_clean'),
        'phone_verified' => array('field'=>'phone_verified', 'label'=>'lang:Phone verified', 'rules'=>'trim|xss_clean'),
        'facebook_id' => array('field'=>'facebook_id', 'label'=>'lang:Facebook ID', 'rules'=>'trim|xss_clean'),
        'facebook_link' => array('field'=>'facebook_link', 'label'=>'lang:facebook_link', 'rules'=>'trim|xss_clean'),
        'youtube_link' => array('field'=>'youtube_link', 'label'=>'lang:youtube_link', 'rules'=>'trim|xss_clean'), 
        'gplus_link' => array('field'=>'gplus_link', 'label'=>'lang:gplus_link', 'rules'=>'trim|xss_clean'),
        'twitter_link' => array('field'=>'twitter_link', 'label'=>'lang:twitter_link', 'rules'=>'trim|xss_clean'), 
        'linkedin_link' => array('field'=>'linkedin_link', 'label'=>'lang:linkedin_link', 'rules'=>'trim|xss_clean'),
        'county_affiliate_values' => array('field'=>'county_affiliate_values', 'label'=>'lang:County', 'rules'=>'trim|xss_clean'),
        'payment_details' => array('field'=>'payment_details', 'label'=>'lang:Payment details', 'rules'=>'trim|xss_clean'),
        'embed_video_code' => array('field'=>'embed_video_code', 'label'=>'lang:Embed video code', 'rules'=>'trim'),
        'research_mail_notifications' => array('field'=>'research_mail_notifications', 'label'=>'lang:Enable Email alerts', 'rules'=>'trim'),
        'research_sms_notifications' => array('field'=>'research_sms_notifications', 'label'=>'lang:Enable SMS alerts', 'rules'=>'trim'),
        
        'promotional_emails' => array('field'=>'promotional_emails', 'label'=>'lang:Promotional Emails', 'rules'=>'trim'),
        'favorites_notifications' => array('field'=>'favorites_notifications', 'label'=>'lang:Favorites Notifications', 'rules'=>'trim'),
        'reviews_notifications' => array('field'=>'reviews_notifications', 'label'=>'lang:Reviews Notifications', 'rules'=>'trim'),
        'information_disclosed' => array('field'=>'information_disclosed', 'label'=>'lang:Information Disclosed', 'rules'=>'trim')
    );
    
    public $rules_billing = array(
        'company_name' => array('field'=>'company_name', 'label'=>'lang:Company name', 'rules'=>'trim|xss_clean'),
        'zip_city' => array('field'=>'zip_city', 'label'=>'lang:ZIP / City', 'rules'=>'trim|required|xss_clean'),
        'mail' => array('field'=>'mail', 'label'=>'lang:Mail', 'rules'=>'trim|required|xss_clean'),
        'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim|required|xss_clean'),
        'prename_name' => array('field'=>'prename_name', 'label'=>'lang:Prename Name', 'rules'=>'trim|required|xss_clean'),
        'vat_number' => array('field'=>'vat_number', 'label'=>'lang:VAT number', 'rules'=>'trim|xss_clean')
    );
    
    public $user_types = array('ADMIN', 'AGENT', 'USER');
    public $user_type_color = array('ADMIN'=>'danger', 'AGENT'=>'warning', 'USER'=>'success');
    
    public $current_user = NULL;
    
	public function __construct(){
		parent::__construct();
        
        $this->user_types = array('ADMIN'=>lang_check('ADMIN'), 'AGENT'=>lang_check('AGENT'), 'USER'=>lang_check('USER'));
        $this->user_type_color = array('ADMIN'=>'danger', 'AGENT'=>'warning', 'USER'=>'success');
	
        if(config_db_item('enable_additional_roles') === TRUE)
        {
            $this->user_types['AGENT_ADMIN'] = lang_check('AGENT_ADMIN');
            $this->user_types['AGENT_LIMITED'] = lang_check('AGENT_LIMITED');
            
            $this->user_type_color['AGENT_ADMIN'] = 'warning';
            $this->user_type_color['AGENT_LIMITED'] = 'warning';
        }
        
        if(config_db_item('enable_county_affiliate_roles') === TRUE)
        {
            $this->user_types['AGENT_COUNTY_AFFILIATE'] = lang_check('AGENT_COUNTY_AFFILIATE');
            $this->user_type_color['AGENT_COUNTY_AFFILIATE'] = 'warning';
        }
        
        if((config_db_item('clickatell_api_id') != '' || config_db_item('clickatell_api_key') != '') && file_exists(APPPATH.'controllers/admin/savesearch.php'))
        {
            $this->rules_admin['research_sms_notifications'] = array('field'=>'research_sms_notifications', 'label'=>'lang:SMS notifications enabled', 'rules'=>'trim|xss_clean');
        }

    }
    
    public $user = NULL;
    public $user_session_data = NULL;
    
    public function login($username = NULL, $password = NULL)
	{
        if($username === NULL)
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
        }
        
        if(empty($username) || empty($password))
            return FALSE;

		$user = $this->get_by(array(
            'username' => $username,
            'password' => $this->hash($password),
        ), TRUE);
        
        // Additional check to login with email
        if(sw_count($user) == 0)
        {
    		$user = $this->get_by(array(
                'mail' => $username,
                'password' => $this->hash($password),
            ), TRUE);
        }
        
        if(sw_count($user))
        {   
            if($user->activated == FALSE && ($user->type == 'USER' || $user->type == 'AGENT'))
            {
                // User and not activated
            }
            else
            {
                // Update last login data
                $this->db->where('id', $user->id);
                $this->db->update($this->_table_name, array('last_login' => date('Y-m-d H:i:s'))); 
                
                $profile_image = '';
                if(!empty($user->repository_id))
                {
                    $this->_table_name = 'file';
                    $this->_order_by = 'id';
                    // Get profile image from repository
            		$image = $this->get_by(array(
                        'repository_id' => $user->repository_id
                    ), TRUE);
                    $this->_table_name = 'user';
                    $this->_order_by = 'name_surname ';
                    if(sw_count($image))
                    {
                        $profile_image = 'files/thumbnail/'.$image->filename;
                    }
                }

                // Log in user
                $data = array(
                    'name_surname'=>$user->name_surname,
                    'username'=>$user->username,
                    'remember'=>(bool)$this->input->post('remember'),
                    'id'=>$user->id,
                    'lang'=>$user->language,
                    'last_login'=>$user->last_login,
                    'loggedin'=>TRUE,
                    'type'=>$user->type,
                    'profile_image'=>$profile_image,
                    'last_activity'=>time()
                );
                $this->session->set_userdata($data);
                
                $this->user_session_data = $data;
                
                return TRUE;                
            }
        }
        
        return FALSE;
	}
        
    public function is_note_activated($username = NULL, $password = NULL)
	{
            if($username === NULL)
            {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
            }

            if(empty($username) || empty($password))
                return FALSE;

            $user = $this->get_by(array(
                'username' => $username,
                'password' => $this->hash($password),
            ), TRUE);

            // Additional check to login with email
            if(sw_count($user) == 0)
            {
                    $user = $this->get_by(array(
                    'mail' => $username,
                    'password' => $this->hash($password),
                ), TRUE);
            }


            if(sw_count($user))
            if($user->activated == FALSE && ($user->type == 'USER' || $user->type == 'AGENT'))
                {
                    return TRUE;
                }
                
            return FALSE;
	}
    
    public function logout()
	{
		$this->session->sess_destroy();
        
        if(config_item('appId') != '')
        {   
            if(config_item('facebook_api_version') == '2.4')
            {
                $this->load->library('facebook/Facebook');
            }
            else
            {
                $this->load->library('facebook');
            }
            
            $this->facebook->destroySession();
        }
	}
    
    public function check_user_type($type)
    {
        if($this->loggedin() && $this->session->userdata('type') == $type)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function if_exists($username_email)
    {
        $this->db->where('username', $username_email);
        $this->db->or_where('mail', $username_email); 
        $query = $this->db->get('user');
        $results = $query->result();

        if(sw_count($results) > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function is_related_repository($user_id, $repository_id)
    {
        $this->db->select('repository_id, property_id, user_id');
        $this->db->from('property_user');
        $this->db->join('property', 'property_user.property_id = property.id');
        $this->db->where('repository_id', $repository_id);
        $this->db->where('user_id', $user_id);
        
        $query = $this->db->get();
        $results = $query->result();

        if(sw_count($results) > 0)
        {
            return true;
        }
        
        $this->db->select('repository_id, id');
        $this->db->from('user');
        $this->db->where('repository_id', $repository_id);
        $this->db->where('id', $user_id);
        
        $query = $this->db->get();
        $results = $query->result();
        
        if(sw_count($results) > 0)
        {
            return true;
        }
        
        $this->db->select('repository_id, user_id, value_num');
        $this->db->from('property_user');
        $this->db->join('property', 'property_user.property_id = property.id');
        $this->db->join('property_value', 'property_value.property_id = property.id');
        $this->db->where('value_num', $repository_id);
        $this->db->where('user_id', $user_id);
        
        $query = $this->db->get();
        $results = $query->result();
        
        if(sw_count($results) > 0)
        {
            return true;
        }
        
        $this->db->select('planimages_repository_id, property_id, user_id');
        $this->db->from('property_user');
        $this->db->join('property', 'property_user.property_id = property.id');
        $this->db->where('planimages_repository_id', $repository_id);
        $this->db->where('user_id', $user_id);
        
        $query = $this->db->get();
        $results = $query->result();

        if(sw_count($results) > 0)
        {
            return true;
        }
        
        return false;
    }
    
    private $loged_in = NULL;
    
    public function loggedin()
	{
	    if(!is_null($this->loged_in))
        {
            if($this->loged_in === TRUE)
                return TRUE;
            
            return FALSE;
        }
	   
        //print_r($this->session->all_userdata());
        
        //Check if user exists
        if((bool) $this->session->userdata('loggedin'))
        {
            $user_id = $this->session->userdata('id');
            if($this->current_user === NULL)
            {
                $this->db->where('id', $user_id);
                $query = $this->db->get($this->_table_name);
    
                if ($query->num_rows() == 0)
                {
                    $this->logout();
                    redirect(site_url());
                }
                else
                {
                    $this->current_user = $query->row();
                }
            }
        }
       
        // Logged in with remember checkbox
        if((bool) $this->session->userdata('loggedin') && (bool) $this->session->userdata('remember'))
        {
            $this->loged_in = true;
            return true;
        }
        // Logged in without remember checkbox
        else if((bool) $this->session->userdata('loggedin') && $this->session->userdata('last_activity') > time()-7200)
        {
            $this->session->set_userdata('last_activity', time());
            $this->loged_in = true;
            return true;
        }
        
        // Logged in without remember checkbox and no activity last 2 hours
        //else if((bool) $this->session->userdata('loggedin'))
        //{
            //$this->session->sess_destroy();
        //}
       
        $this->loged_in = false;
        return false;
	}
    
    public function get_new()
	{
        $user = new stdClass();
        $user->name_surname = '';
        $user->username = '';
        $user->password = '';
        $user->password_confirm = '';
        $user->address = '';
        $user->description = '';
        $user->phone = '';
        $user->phone2 = '';
        $user->payment_details = '';
        $user->embed_video_code = '';
        $user->facebook_link = '';
        $user->youtube_link = '';
        $user->gplus_link = '';
        $user->twitter_link = '';
        $user->linkedin_link = '';
        $user->research_mail_notifications = '';
        $user->mail = '';
        $user->last_login = NULL;
        $user->qa_id = NULL;
        $user->type = 'USER';
        $user->language = 'english';
        $user->registration_date = date('Y-m-d H:i:s');
        $user->activated = 0;
        $user->package_id = NULL;
        $user->package_last_payment = NULL;
        $user->facebook_id = ' ';
        $user->mail_verified = 0;
        $user->phone_verified = 0;
        
        return $user;
	}
    
    public function hash($string)
	{
	   //return $string;
       
       if(config_item('hash_function') == '')
       {
           if (function_exists('hash')) {
                return substr(hash('sha512', $string.config_item('encryption_key')), 0, 10);
           }
    
           return substr(md5($string.config_item('encryption_key')), 0, 10);
       }
       else if(config_item('hash_function') == 'hash')
       {
            return substr(hash('sha512', $string.config_item('encryption_key')), 0, 10);
       }
       else if(config_item('hash_function') == 'md5')
       {
            return substr(md5($string.config_item('encryption_key')), 0, 10);
       }
	}
    
    public function hash_token($string)
	{
	   //return $string;
       
       if(config_item('hash_function') == '')
       {
           if (function_exists('hash')) {
                return substr(hash('sha512', $string.config_item('encryption_key')), 0, 32);
           }
    
           return substr(md5($string.config_item('encryption_key')), 0, 32);
       }
       else if(config_item('hash_function') == 'hash')
       {
            return substr(hash('sha512', $string.config_item('encryption_key')), 0, 32);
       }
       else if(config_item('hash_function') == 'md5')
       {
            return substr(md5($string.config_item('encryption_key')), 0, 32);
       }
	}
    
    public function total_unactivated()
    {
        $this->db->where('(activated=0 or activated IS NULL)');
        $this->db->where('type', 'USER');
        $query = $this->db->get($this->_table_name);
        return $query->num_rows();
    }
    
    public function total_admins()
    {
        $this->db->where('activated', '1');
        $this->db->where('type', 'ADMIN');
        $query = $this->db->get($this->_table_name);
        return $query->num_rows();
    }
    
    public function get_agent($property_id)
    {

        $this->db->where('property_id ', $property_id);
        $this->db->limit(1);
        $query = $this->db->get('property_user');
        
        if ($query->num_rows() > 0)
        {
           $row = $query->row_array();
           return $this->get_array($row['user_id']);
        }
        
        return array();
    }
    
    public function get_experts($expert_categories = array(), $not_selected = 'Not selected')
    {
        $this->db->where('qa_id !=', 0);
        $this->db->where('type !=', 'USER');
        $users = parent::get();
        
        // Return key => value pair array
        $array = array(0 => lang_check($not_selected));
        if(sw_count($users))
        {
            foreach($users as $user)
            {
                $array[$user->id] = $user->username.', '.$user->name_surname;
                
                if(isset($expert_categories[$user->qa_id]))
                {
                    $array[$user->id].=', '.$expert_categories[$user->qa_id];
                }
            }
        }
        
        return $array;
    }
    
    public function get_estates($user_id)
    {

        $this->db->where('user_id', $user_id);
        $query = $this->db->get('property_user');
        
        if ($query->num_rows() > 0)
        {
            $estates = array();
            foreach ($query->result_array() as $row)
            {
               $estates[] = $row['property_id'];
            }
            return $estates;
        }
        
        return array();
    }
    
    public function get_pagination($limit, $offset)
    {
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get($this->_table_name);
        
        if(!is_object($query))
        {
            echo $this->db->last_query();
            exit();
        }

        if ($query->num_rows() > 0)
            return $query->result();
            
        return array();
    }
    
    public function get_counted($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = "", $search = '')
    {
        $this->db->cache_on();
        
        $this->db->select('user.*, user.id as user_id, MIN(property_user.property_id) as property_id, COUNT(*) as properties_count');
        
        $this->db->from('user');
        $this->db->join('property_user', 'user.id = property_user.user_id', 'left');
        
        if($where !== NULL) $this->db->where($where);
        
        if(!empty($search))
        {
            $this->db->where("(address LIKE '%$search%' OR name_surname LIKE '%$search%')");
        }
        
        $this->db->group_by(array('user.id'));

        if($order_by !== NULL) $this->db->order_by($order_by);
        if($limit !== NULL) $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        
        $results =array();
        if ($query && $query->num_rows() > 0)
            $results = $query->result();
        
        $this->db->cache_off();
        return $results;
    }
    
    public function save($data, $id=NULL)
    {       
        // [Save first/second image in repository]
        $repository_id = NULL;
        if(!is_null($id)){
            $curr_item = $this->get($id);
            if(is_object($curr_item))
            {
                $repository_id = $curr_item->repository_id;
            }
            
            /* blocked password if is_password_locked */
            if($this->session->userdata('type') != 'ADMIN')
                if($curr_item->is_password_locked == 1) {
                    unset($data['password']);
                }
                
            if($this->session->userdata('type') == 'ADMIN') {
                if($curr_item->is_password_locked == 1 && isset($data['password'])) {
                    $data['is_password_locked'] = 0;
                }
            }
        }
        if(!empty($repository_id))
        {
            $this->load->model('file_m');
            $files = $this->file_m->get_by(array('repository_id'=>$repository_id));
            
            $image_repository = array();
            foreach($files as $key_f=>$file_row)
            {
                if(is_object($file_row))
                {
                    if(file_exists(FCPATH.'files/thumbnail/'.$file_row->filename))
                    {
                        if(empty($data['image_user_filename']))
                        {
                            $data['image_user_filename'] = $file_row->filename;
                            continue;
                        }
                            
                        if(!empty($data['image_user_filename']) && empty($data['image_agency_filename']))
                        {
                            $data['image_agency_filename'] = $file_row->filename;
                            break;
                        }
                    }
                }

            }
        }
        // [/Save first/second image in repository]
        $data['last_edit_ip']=$this->input->ip_address();
        return parent::save($data, $id);
    }

    public function deactivate_user($id)
    {       
        $data = array('activated' => '0');

        return parent::save($data, $id);
    }
    
    public function delete($id)
    {
        // Remove repository
        $user_data = $this->get($id, TRUE);
        if(sw_count($user_data))
        {
            $this->load->model('repository_m');
            $this->repository_m->delete($user_data->repository_id);
        }
        
        $this->db->where('user_id', $id);
        $this->db->delete('property_user');
        
        $this->db->where('user_id', $id);
        $this->db->delete('reservations');
        
        $this->db->where('user_id', $id);
        $this->db->delete('favorites');
        
        $this->db->where('user_id', $id);
        $this->db->delete('saved_search');
        
        $this->db->where('user_id', $id);
        $this->db->delete('affilate_packages');
        
        parent::delete($id);
    }

}



