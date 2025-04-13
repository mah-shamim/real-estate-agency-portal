<?php

class Cronjob extends CI_Controller
{

    private $enable_output= false;
    private $enable_debug = false;
    private $enable_test = true;
    private $default_language = 'english';

    public function __construct()
    {
        parent::__construct();
        
        $this->config->set_item('language', $this->default_language);
        $this->lang->load('backend_base');
    }
    
	public function index()
	{
		echo 'Hello, cronjob here!';
        exit();
	}
    
    /*
        Hold expire
        
        *User receives a notification saying: We are so sorry; your submission has been escalated
        *County affiliate: Receives notification letting him know that hers in violation of the terms
        *Main admin: Receives notification letting me know so I can take action against county affiliate, 
                     and also so I can review the property myself and decide what to do.
    */
    public function hold_period_expiration($output = NULL)
    {
        error_reporting(E_ALL^E_NOTICE);
        
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('user_m');
        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Hold expiration script started!'."\n";
        
        
        $expiration_to_date = date('Y-m-d H:i:s', time()-2*86400);
        $lang_id = $this->language_m->get_default_id();
        
        $related_properties = $this->estate_m->get_by(array('language_id'=> $lang_id, 'date_status <'=>$expiration_to_date, 'status'=>'HOLD'), FALSE, 20);
        
        // alert related and change status for 20 properties at each call
        if(sw_count($related_properties) > 0)
        {
            $batch_data = array();
            
            foreach($related_properties as $prop)
            {
                if($this->enable_output) echo $prop->id."\n";
                
                $user_related = $this->user_m->get_agent($prop->id);
                $affilate_related = $this->user_m->get($prop->affilate_id);
                
                // Send email to admin
                
                if(!empty($settings['email']))
                {
                    $admin_email = $settings['email'];
                    
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($admin_email);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/admin_affilate_violation_terms', $data, TRUE);
                    
                    //echo $message;
                    $this->email->message($message);
                    $this->email->send();
                }
                
                // Send email to county affilate
                
                if(!empty($affilate_related->mail))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($affilate_related->mail);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/agent_affilate_violation_terms', $data, TRUE);
                    
                    //echo $message;
                    $this->email->message($message);
                    $this->email->send();
                }

                // Send email to property submitter

                if(!empty($user_related['mail']))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($user_related['mail']);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/user_affilate_violation_terms', $data, TRUE);
                    
                    //echo $message;
                    $this->email->message($message);
                    $this->email->send();
                }
                
                $batch_data[] = array(
                                        'date_status' => date('Y-m-d H:i:s'),
                                        'status' => 'HOLD_ADMIN' ,
                                        'id' => $prop->id
                                     );
            }
            
            if(sw_count($batch_data) > 0)
                $this->db->update_batch('property', $batch_data, 'id'); 
        }
        
        if($this->enable_output) echo 'Status changed (HOLD_ADMIN): '.sw_count($related_properties).''."\n";
        if($this->enable_output) echo 'Hold expiration script completed!'."\n";
        exit();
    }
    
    /*
        Hold expire reduced 24h, when user reduce price 10%
        
        *User receives a notification saying: We are so sorry; your submission has been escalated
        *County affiliate: Receives notification letting him know that hers in violation of the terms
        *Main admin: Receives notification letting me know so I can take action against county affiliate, 
                     and also so I can review the property myself and decide what to do.
    */
    public function hold_period_expiration_reduced($output = NULL)
    {
        error_reporting(E_ALL^E_NOTICE);
        
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('user_m');
        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Hold expiration script started!'."\n";
        
        
        $expiration_to_date = date('Y-m-d H:i:s', time()-2*86400);
        $lang_id = $this->language_m->get_default_id();
        
        $related_properties = $this->estate_m->get_by(array('language_id'=> $lang_id, 'date_status <'=>$expiration_to_date, 'status'=>'HOLD_REDUCED'), FALSE, 20);
        
        // alert related and change status for 20 properties at each call
        if(sw_count($related_properties) > 0)
        {
            $batch_data = array();
            
            foreach($related_properties as $prop)
            {
                if($this->enable_output) echo $prop->id."\n";
                
                $user_related = $this->user_m->get_agent($prop->id);
                $affilate_related = $this->user_m->get($prop->affilate_id);
                
                // Send email to admin
                
                if(!empty($settings['email']))
                {
                    $admin_email = $settings['email'];
                    
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($admin_email);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/admin_affilate_violation_terms', $data, TRUE);
                    
                    //echo $message;
                    $this->email->message($message);
                    $this->email->send();
                }
                
                // Send email to county affilate
                
                if(!empty($affilate_related->mail))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($affilate_related->mail);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/agent_affilate_violation_terms', $data, TRUE);
                    
                    //echo $message;
                    $this->email->message($message);
                    $this->email->send();
                }

                // Send email to property submitter

                if(!empty($user_related['mail']))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($user_related['mail']);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/user_affilate_violation_terms', $data, TRUE);
                    
                    //echo $message;
                    $this->email->message($message);
                    $this->email->send();
                }
                
                $batch_data[] = array(
                                        'date_status' => date('Y-m-d H:i:s'),
                                        'status' => 'HOLD_ADMIN' ,
                                        'id' => $prop->id
                                     );
            }
            
            if(sw_count($batch_data) > 0)
                $this->db->update_batch('property', $batch_data, 'id'); 
        }
        
        if($this->enable_output) echo 'Status changed (HOLD_ADMIN): '.sw_count($related_properties).''."\n";
        if($this->enable_output) echo 'Hold expiration script completed!'."\n";
        exit();
    }
    
    /*
        Property expiration soon message (7 days before listing expire)
        Delete all proeprties from system which is not updated (2x expire days)
    */
    public function alert_property_expiration($output = NULL)
    {
        error_reporting(E_ALL^E_NOTICE);
        
        $this->load->model('language_m');
        $this->load->model('settings_m');
        $this->load->model('option_m');
        $this->load->model('user_m');
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('treefield_m');

        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Alert property expiration script started!'."\n";
        
        if(empty($settings['listing_expiry_days'])) exit('listing_expiry_days not defined!'."\n");
        
        $lang_id = $this->language_m->get_default_id();
        
        
        // Fetch all properties 7 days before expiration
        $count_emails_sent = 0;
        
        $this->db->where('(property.date_notify < property.date_modified OR property.date_notify IS NULL)');
        $estates_to_alert = $this->estate_m->get_by(array('language_id'=>$lang_id, 'property.date_modified <'=>date("Y-m-d H:i:s" , time()-
                                                         ($settings['listing_expiry_days']-7)*86400)), FALSE, NULL, 
                                                         NULL, NULL, array(), NULL, FALSE, TRUE);
        
//        echo $this->db->last_query();
//        echo '<pre>';
//        var_dump($estates_to_alert);
//        echo '</pre>';
        
        // Sent email
        $batch_data = array();
        foreach($estates_to_alert as $key=>$row)
        {
            //echo $row->mail.'<br />';
            if(!empty($row->mail))
            {
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                $this->email->from($settings['noreply'], lang_check('Web page'));
                $this->email->to($row->mail);
                
                $this->email->subject(lang_check('Property expiration soon!'));
                
                $data = array();
                $data['title'] = lang_check('Property expiration soon!');
                $data['property'] = (array)$row;
                
                $message = $this->load->view('email/property_expiration', $data, TRUE);
                
                //echo $message;
                
                $this->email->message($message);
                if ( ! $this->email->send() )
                {
                    if($this->enable_debug) echo 'Email sanding failed to: '.$row->mail.''."\n";
                }
                else
                {
                    $batch_data[] = array('id'=>$row->id, 'date_notify'=>date("Y-m-d H:i:s"));
                }

                $count_emails_sent++;
            }
        }
        
        // Set new date_notify
        if(sw_count($batch_data) > 0)
            $this->db->update_batch('property', $batch_data, 'id'); 
            
        // [ALERT AFTER EXPIRE DAYS]
            
        $this->db->where('(property.date_notify_expired < property.date_modified OR property.date_notify_expired IS NULL)');
        $estates_to_alert = $this->estate_m->get_by(array('language_id'=>$lang_id, 'property.date_modified <'=>date("Y-m-d H:i:s" , time()-
                                                         ($settings['listing_expiry_days'])*86400)), FALSE, NULL, 
                                                         NULL, NULL, array(), NULL, FALSE, TRUE);
        
        // Sent email
        $batch_data = array();
        foreach($estates_to_alert as $key=>$row)
        {
            //echo $row->mail.'<br />';
            if(!empty($row->mail))
            {
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                $this->email->from($settings['noreply'], lang_check('Web page'));
                $this->email->to($row->mail);
                
                $this->email->subject(lang_check('Property expired!'));
                
                $data = array();
                $data['title'] = lang_check('Property expired!');
                $data['property'] = (array)$row;
                
                $message = $this->load->view('email/property_expired', $data, TRUE);
                
                //echo $message;
                
                $this->email->message($message);
                if ( ! $this->email->send() )
                {
                    if($this->enable_debug) echo 'Email sanding failed to: '.$row->mail.''."\n";
                }
                else
                {
                    $batch_data[] = array('id'=>$row->id, 'date_notify_expired'=>date("Y-m-d H:i:s"));
                }

                $count_emails_sent++;
            }
        }
        
        // Set new date_notify_expired
        if(sw_count($batch_data) > 0)
            $this->db->update_batch('property', $batch_data, 'id'); 
            
        // [/ALERT AFTER EXPIRE DAYS]
        
        // Fetch all proeprties which is not updated (2x expire days)
        $count_removed = 0;
        
        $estates_to_delete = $this->estate_m->get_by(array('language_id'=>$lang_id, 'date_modified <'=> date("Y-m-d H:i:s" , time()-$settings['listing_expiry_days']*86400*2)), FALSE, NULL, NULL, NULL, array(), NULL, FALSE, FALSE);

        // Remove it
        foreach($estates_to_delete as $key=>$row)
        {
            if(!$this->enable_test)
                $this->estate_m->delete($row->id);
            
            $count_removed++;
        }
        
        if($this->enable_output) echo 'Alert emails sent (1x Expire days): '.$count_emails_sent.''."\n";
        if($this->enable_output) echo 'Property removed  (2x Expire days): '.$count_removed.''."\n";
        if($this->enable_output) echo 'Alert property expiration script completed!'."\n";
        exit();
    }
    
    public function alert_affilate_available($output = NULL)
    {
        error_reporting(E_ALL);
        
        $this->load->model('language_m');
        $this->load->model('settings_m');
        $this->load->model('option_m');
        $this->load->model('user_m');
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('treefield_m');

        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Alert affilate script started!'."\n";

        $lang_id = $this->language_m->get_default_id();
        
        // search for available treefields
        $treefields_available = $this->treefield_m->get_affilate_available();

        // set notified
        $where_in_notified = array();
        foreach($treefields_available as $row)
        {
            $where_in_notified[$row->id] = $row->id;
        }
        $data = array('notifications_sent' => 1);
        $this->db->where_in('id', $where_in_notified);
        $this->db->update('treefield', $data); 

        // search for saved search
        $search_neiberhood = array();
        foreach($treefields_available as $row)
        {
            $search_neiberhood[$row->value_path] = $row->value_path;
        }

        $users_to_alert = $this->savedsearch_m->get_by_search($search_neiberhood);
        
        // generate email array
        $email_list = array();
        foreach($users_to_alert as $row)
        {
            if(!empty($row->mail))
            {
                $json = json_decode($row->parameters);
                
                if(!empty($json->v_search_option_64))
                    $email_list[$row->mail][] = substr($json->v_search_option_64, 0, -3);
            }
        }
        
        // alert each member
        $count_emails_sent = 0;
        
        foreach($email_list as $user_email=>$neiberhood_list)
        {
            $this->load->library('email');
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            $this->email->from($settings['noreply'], lang_check('Web page'));
            $this->email->to($user_email);
            
            $this->email->subject(lang_check('New county affilates available!'));
            
            $data = array();
            $data['title'] = lang_check('New county affilates available!');
            $data['neiberhood_list'] = $neiberhood_list;
            
            $message = $this->load->view('email/county_affilates_available', $data, TRUE);
            
            //echo $message;
            
            $this->email->message($message);
            if ( ! $this->email->send() )
            {
                if($this->enable_debug) echo 'Email sanding failed to: '.$user_email.''."\n";
            }
            
            $count_emails_sent++;
        }
        
        if($this->enable_output) echo 'Alert emails sent: '.$count_emails_sent.''."\n";
        if($this->enable_output) echo 'Alert affilate script completed!'."\n";
        exit();
    }
    
    /*
        Submission expired after first 48 hours
        
        *User receives an email saying: Sorry for the delay. We are experiencing some internal problems in our process, however, 
                                        your submission has been escalated to our support team to expedite the process, and you will 
                                        receive a confirmation in a matter of hours. Stay tuned. Thank you!
        *County affiliate receives email letting him know that he is in violation of our terms for not having reviewed said property.
        *Website administrator: receives an alert informing of the situation.
    */
    public function county_affilate_hold($output = NULL)
    {
        error_reporting(E_ALL^E_NOTICE);
        
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('user_m');
        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Hold script started!'."\n";
        
        
        $activate_from_date = date('Y-m-d H:i:s', time()-2*86400);
        $lang_id = $this->language_m->get_default_id();
        
        $related_properties = $this->estate_m->get_by(array('language_id'=> $lang_id, 
                                                            'date_modified <'=>$activate_from_date, 
                                                            'is_activated'=>0, 
                                                            '(status = "" OR status is NULL)'=>NULL,
                                                            'sent_to_affiliate'=>1), FALSE, 20);
        
        $batch_data = array();
        
        // update
        if(sw_count($related_properties) > 0)
        {

            foreach($related_properties as $prop)
            {
                //if($this->enable_output) echo $prop->id."\n";
                
                $user_related = $this->user_m->get_agent($prop->id);
                $affilate_related = $this->user_m->get($prop->affilate_id);
                
                // Send email to admin
                
                if(!empty($settings['email']))
                {
                    $admin_email = $settings['email'];
                    
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($admin_email);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/admin_affilate_review_violation_terms', $data, TRUE);
                    
                    if($this->enable_test)
                    {
                        echo $message;
                    }
                    else
                    {
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                
                // Send email to county affilate
                
                if(!empty($affilate_related->mail))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($affilate_related->mail);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/agent_affilate_review_violation_terms', $data, TRUE);
                    
                    if($this->enable_test)
                    {
                        echo $message;
                    }
                    else
                    {
                        $this->email->message($message);
                        $this->email->send();
                    }
                    

                }

                $batch_data[] = array(
                                        'date_status' => date('Y-m-d H:i:s'),
                                        'status' => 'HOLD_ADMIN' ,
                                        'id' => $prop->id
                                     );
            }
            
            if(sw_count($batch_data) > 0 && !$this->enable_test)
                $this->db->update_batch('property', $batch_data, 'id'); 
                
            if($this->enable_test)
            {
                echo '<pre>';
                var_dump($batch_data);
                echo '</pre>';
            }
        }

        if($this->enable_output) echo 'Change status (HOLD_ADMIN): '.sw_count($batch_data).''."\n";
        if($this->enable_output) echo 'Hold script completed!'."\n";
        exit();
    }
    
    /*
        Submission expired after first 24 hours of reduce price
        
        It will be delivered to the county affiliate first. At this point it will follow the process like if it was a new submission. 
        HOWEVER, this time the reviewing time for the county affiliate will only be 24 hours, and then the holding time will only be 
        24 hours as well. Not 48 hours like when it is a brand new submission. Keep in mind that we would have to update all the 
        notifications to reflect the new timing of 24 hours to review and 24 hours to hold it.
    */
    public function county_affilate_hold_reduced($output = NULL)
    {
        error_reporting(E_ALL^E_NOTICE);
        
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('user_m');
        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Hold script started!'."\n";
        
        
        $activate_from_date = date('Y-m-d H:i:s', time()-1*86400);
        $lang_id = $this->language_m->get_default_id();
        
        $related_properties = $this->estate_m->get_by(array('language_id'=> $lang_id, 
                                                            'date_status <'=>$activate_from_date, 
                                                            'is_activated'=>1, 
                                                            '(status = "REDUCED_PRICE")'=>NULL,
                                                            'sent_to_affiliate'=>1), FALSE, 20);
        
        $batch_data = array();
        
        // update
        if(sw_count($related_properties) > 0)
        {

            foreach($related_properties as $prop)
            {
                //if($this->enable_output) echo $prop->id."\n";
                
                $user_related = $this->user_m->get_agent($prop->id);
                $affilate_related = $this->user_m->get($prop->affilate_id);
                
                // Send email to admin
                
                if(!empty($settings['email']))
                {
                    $admin_email = $settings['email'];
                    
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($admin_email);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/admin_affilate_review_violation_terms', $data, TRUE);
                    
                    if($this->enable_test)
                    {
                        echo $message;
                    }
                    else
                    {
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
                
                // Send email to county affilate
                
                if(!empty($affilate_related->mail))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($affilate_related->mail);
                    
                    $this->email->subject(lang_check('Agent affilate violation of terms!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Agent affilate violation of terms!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/agent_affilate_review_violation_terms', $data, TRUE);
                    
                    if($this->enable_test)
                    {
                        echo $message;
                    }
                    else
                    {
                        $this->email->message($message);
                        $this->email->send();
                    }
                    

                }

                $batch_data[] = array(
                                        'date_status' => date('Y-m-d H:i:s'),
                                        'status' => 'HOLD_ADMIN' ,
                                        'id' => $prop->id
                                     );
            }
            
            if(sw_count($batch_data) > 0 && !$this->enable_test)
                $this->db->update_batch('property', $batch_data, 'id'); 
                
            if($this->enable_test)
            {
                echo '<pre>';
                var_dump($batch_data);
                echo '</pre>';
            }
        }

        if($this->enable_output) echo 'Changed status (HOLD_ADMIN): '.sw_count($batch_data).''."\n";
        if($this->enable_output) echo 'Hold script completed!'."\n";
        exit();
    }
    
    /*
    
        7 fays after decline properties are auto removed
    
    */
    public function declined_properties_delete($output = NULL)
    {
        error_reporting(E_ALL);
        
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('user_m');
        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Decline delete script started!'."\n";
        
        
        $remove_to_date = date('Y-m-d H:i:s', time()-7*86400);
        $lang_id = $this->language_m->get_default_id();
        
        $delate_properties = $this->estate_m->get_by(array('language_id'=> $lang_id, 'date_status <'=>$remove_to_date, 'is_activated'=>0, 'status'=>'DECLINE'), FALSE, NULL);
        
        // delete
        if(sw_count($delate_properties) > 0)
        {
            foreach($delate_properties as $prop)
            {
                if($this->enable_output) echo $prop->id."\n";

                $user_related = $this->user_m->get_agent($prop->id);

                if(!empty($user_related['mail']))
                {
                    $this->load->library('email');
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    $this->email->from($settings['noreply'], lang_check('Web page'));
                    $this->email->to($user_related['mail']);
                    
                    $this->email->subject(lang_check('Property deleted for non-compliance!'));
                    
                    $data = array();
                    $data['title'] = lang_check('Property deleted for non-compliance!');
                    $data['property'] = (array)$prop;
                    
                    $message = $this->load->view('email/property_auto_deleted', $data, TRUE);
                    $this->email->message($message);
                    $this->email->send();
                }
                
                if(!$this->enable_test)
                    $this->estate_m->delete($prop->id);
            }
        }
        
        if($this->enable_output) echo 'Deleted (7 days after declined): '.sw_count($delate_properties).''."\n";
        if($this->enable_output) echo 'Decline delete script completed!'."\n";
        exit();
    }
    
    /*
        Version to send notifiation only on renew, not on each modification
    */
    public function research_alert($output = NULL)
    {
        return $this->research($output, 'date_alert');
    }
    
    /*
        Alert users about new listing based on saved search criteria
    */
    public function research($output = NULL, $date_column='date_modified')
    {
        error_reporting(E_ALL);
        
        $this->load->model('savedsearch_m');
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('user_m');
        
        if(file_exists(APPPATH.'libraries/admin/Clickatellapi.php') && (config_db_item('clickatell_api_id') != '' || config_db_item('clickatell_api_key') != '') && file_exists(APPPATH.'controllers/admin/savesearch.php'))
        {
            $this->load->library('clickatellapi');
        }
        
        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Research started!'."\n";
        
        // Fetch last date from research database
        $research_from_date = date('Y-m-d H:i:s');
        $row = $this->savedsearch_m->get_by(array('date_last_informed IS NOT NULL'=>NULL), true, 1, 'date_last_informed');
        if(!empty($row))
        {
            if($row->date_last_informed != NULL)
                $research_from_date = $row->date_last_informed;
            
            if($this->enable_output) echo 'Research from date: '.$research_from_date.''."\n";
        }
        else
        {
            exit('No researches found!');
        }
        
        // For all properties
        $options_c = array();
        foreach($this->language_m->db_languages_id as $id=>$code)
        {
            //$options_c[$code] = $this->option_m->get_options($id);
        }
        
        $options_name = $this->option_m->get_lang(NULL, FALSE, $this->language_m->get_default_id());
        $researches_all = $this->savedsearch_m->get_by(array(),FALSE,NULL);
        
        $estates_to_research = $this->estate_m->get_by(array($date_column.' >'=>$research_from_date, 'is_activated'=>1), FALSE, NULL);
        
//        echo '<pre>';
//        var_dump($estates_to_research[0]);
//        var_dump($estates_to_research[1]);
//        var_dump($estates_to_research[2]);
//        echo '</pre>';
//        exit();
        
        //print_r($estates_to_research);
        if($this->enable_output) echo 'Total estates for research: '.sw_count($estates_to_research).''."\n";
        $count_emails_try = 0;
        $count_emails_success = 0;
        foreach($estates_to_research as $key_e=>$row_e){
            
            $options = array();
            //Fill $options[$row['id']][$option_num]
            $json_obj = json_decode($row_e->json_object);
            foreach($options_name as $key2=>$row2)
            {
                $key1 = $row2->option_id;
                $estate['has_option_'.$key1] = array();
                
                if(isset($json_obj->{"field_$key1"}))
                {
                    $row1 = $json_obj->{"field_$key1"};
                    $options[$row_e->id][$key1] = $row1;
                }
                else
                {
                    $options[$row_e->id][$key1] = '';
                }
            }
            $json_obj = NULL;
            
            // For each researches
            foreach($researches_all as $key_r=>$row_r){
            
            $lang_id = $this->language_m->get_id($row_r->lang_code);
            if($lang_id != $row_e->language_id)
                continue;
            
            if(strtotime($row_r->date_last_informed) > strtotime($row_e->{$date_column}) || 
               strtotime($row_e->{$date_column}) > time() ||
               strtotime($row_r->date_last_informed) + 3600*$row_r->delivery_frequency_h > time())
                continue;

            $parameters = json_decode($row_r->parameters);
            $acceptable_research = true;
            //print_r($parameters);
            
            // Check if research $parameters include that property
            //$options = $options_c[$row_r->lang_code];
            //print_r($options);
            $parameters_array =  (array) $parameters;
            $post_option = array();
            $post_option_sum = ' ';
            foreach($parameters_array as $key=>$val)
            {
                $tmp_post = $parameters_array[$key];
                if(!empty($tmp_post) && strrpos($key, 'tion_') > 0){
                    $post_option[substr($key, strrpos($key, 'tion_')+5)] = $tmp_post;
                    $post_option_sum.=$tmp_post.' ';
                }
                
                if(is_array($tmp_post))
                {
                    $category_num = substr($key, strrpos($key, 'gory_')+5);
                    
                    foreach($tmp_post as $key=>$val)
                    {
                        $post_option['0'.$category_num.'9999'.$key] = $val;
                        $post_option_sum.=$val.' ';
                    }
                }
                
                if($key == 'v_rectangle_ne' || $key == 'v_rectangle_sw')
                {
                    $post_option[$key] = $parameters_array[$key];
                }
                
            }
            
            /* Define purpose */
            $this->data['is_purpose_rent'] = array();
            $this->data['is_purpose_sale'] = array();
            //$this->data['is_purpose_sale'][] = array('count'=>'1');
            
            if(strpos($post_option_sum, lang_check('Rent')) !== FALSE)
            {
                $this->data['is_purpose_rent'][] = array('count'=>'1');
            }
            if(strpos($post_option_sum, lang_check('Sale')) !== FALSE)
            {
                $this->data['is_purpose_sale'][] = array('count'=>'1');
            }
            
            // Special situation for properties available for rent
            if(sw_count($this->data['is_purpose_rent']) > 0)
            {
                if(isset($post_option['v_search_option_36_from']))
                    $post_option['v_search_option_37_from'] = $post_option['v_search_option_36_from'];
                if(isset($post_option['v_search_option_36_to']))
                    $post_option['v_search_option_37_to'] = $post_option['v_search_option_36_to'];
                unset($post_option['v_search_option_36_from'], $post_option['v_search_option_36_to']);
            }
            
            // print_r($post_option);echo ''; Before check
            // End fetch post values
            
            foreach($post_option as $key=>$val)
            {
                if(is_numeric($key) || $key == 'smart')
                {
                    if(strpos($row_e->search_values, $val) === FALSE)
                    {
                        // acceptable rule
                        $acceptable_research = false;
                    }
                }
                else if($key == 'v_rectangle_ne')
                {
                    if(!empty($post_option['v_rectangle_sw']))
                    {
                        $gps_ne = explode(', ', $post_option['v_rectangle_ne']);
                        $gps_sw = explode(', ', $post_option['v_rectangle_sw']);
            
                        if($row_e->lat < $gps_ne[0] && $row_e->lat > $gps_sw[0] &&
                           $row_e->lng < $gps_ne[1] && $row_e->lng > $gps_sw[1] )
                        {
                            
                        }
                        else
                        {
                            $acceptable_research = false;
                        }
                    }
                }
                else if(is_numeric($val))
                {
                    $option_num = $key;
                    $row = (array) $row_e; // row from estate, convert to array
                    $val1 = $val;
                    
                    if(strrpos($option_num, 'from') > 0)
                    {
                        $option_num = substr($option_num,0,-5);
                        
                        // For rentable
                        if($option_num == 36 && isset($this->data['is_purpose_rent'][0]['count']))
                            $option_num++;
                        
                        if(!isset($this->data['is_purpose_rent'][0]['count']) &&
                                !isset($this->data['is_purpose_sale'][0]['count']))
                        {
                            
                            if( ($options[$row['id']][$option_num] < $val1 || empty($options[$row['id']][$option_num])) && 
                                ($options[$row['id']][$option_num+1] < $val1 || empty($options[$row['id']][$option_num+1]))  )
                            {
                                $acceptable_research = false;
                            }
                        }
                        else if(!isset($options[$row['id']][$option_num]))
                        {
                            $acceptable_research = false;
                        }
                        else if($options[$row['id']][$option_num] < $val1)
                        {
                            $acceptable_research = false;
                        }
                    }
                    else if(strrpos($option_num, 'to') > 0)
                    {
                        $option_num = substr($option_num,0,-3);
                        
                        // For rentable
                        if($option_num == 36 && isset($this->data['is_purpose_rent'][0]['count']))
                            $option_num++;
                        
                        if(!isset($this->data['is_purpose_rent'][0]['count']) &&
                                !isset($this->data['is_purpose_sale'][0]['count']))
                        {
                            if(!isset($options[$row['id']][$option_num]))
                            {
                                $acceptable_research = false;
                            }
                            else
                            {
//                                echo $val1."\r\n";
//                                echo $options[$row['id']][$option_num]."\r\n";
//                                echo $options[$row['id']][$option_num+1]."\r\n";
                                
                                if( ($options[$row['id']][$option_num] > $val1 || empty($options[$row['id']][$option_num])) && 
                                    ($options[$row['id']][$option_num+1] > $val1 || empty($options[$row['id']][$option_num+1]) || $row['id'] != 36 )  )
                                {
                                    $acceptable_research = false;
//                                    echo "unset\r\n";
                                }
                            }
                        }
                        else if(!isset($options[$row['id']][$option_num]) || empty($options[$row['id']][$option_num]))
                        {
                            $acceptable_research = false;
                        }
                        else if($options[$row['id']][$option_num] > $val1)
                        {
                            $acceptable_research = false;
                        }
                    }
                    else
                    {
                        if(!isset($options[$row['id']][$option_num]))
                        {
                            $acceptable_research = false;
                        }
                        else if($options[$row['id']][$option_num] != $val1)
                        {
                            $acceptable_research = false;
                        }
                    }
                }
            }
            
            if($acceptable_research)
            {
                // Send message to user
                if($this->enable_debug) echo 'Property: '.$row_e->id.'';
                if($this->enable_debug) { print_r($post_option); echo ''; }
                
                // Add email to sending stack
                $email_data = array();
                $email_data['property_id'] = $row_e->id;
                $email_data['lang_code'] = $row_r->lang_code;
                $email_data['research_id'] = $row_r->id;
                $email_data['property_status'] = $row_e->status;
                $emails_stack[$row_r->user_id][$row_e->id.'_'.$row_r->id] = $email_data;
            }
            }
        }
        
        $estates_to_research = NULL;

        // Send emails
        $count_emails_try = sw_count($emails_stack);
        //print_r($emails_stack);
        foreach($emails_stack as $user_id_k=>$emails_data){
            
            //print_r($emails_data);
            // Send email
            $user = $this->user_m->get($user_id_k);

            if(!empty($user->mail))
            {
                $user_email = $user->mail;
                if($this->enable_debug) echo 'Email to: '.$user_email.''."\n";

                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                $this->email->from($settings['noreply'], lang_check('Web page'));
                $this->email->to($user_email);
                
                $this->email->subject(lang_check('New listing from your saved research!'));
                
                $sms_message = lang_check('New listings:');
                $data = array();
                $data['message'] = lang_check('New listings from your saved research!');
                $researches_to_reset = array();
                foreach($emails_data as $email_data_v){
                    $link = '<a href="'.site_url('property/'.$email_data_v['property_id'].'/'.$email_data_v['lang_code']).'#content">'.lang_check('Check out property').' #'.$email_data_v['property_id'].'</a>';
                    
                    if($email_data_v['property_status'] == 'APPROVE_REDUCED')
                    {
                        $link.=' <strong>'.lang_check('price-reduced-badget').'</strong>';
                    }
                    
                    $data['property_links'][$email_data_v['property_id']] = $link;
                    
                    $researches_to_reset[$email_data_v['research_id']] = $email_data_v['research_id'];
                    
                    $sms_message.="\n".site_url('property/'.$email_data_v['property_id'].'/'.$email_data_v['lang_code']);
                }

                $data['research_link'] = '<a href="'.site_url('frontend/login/').'#content">'.lang_check('Manage your saved researches').'</a>';
            
                $message = $this->load->view('email/research_new_listing', $data, TRUE);
                
                if($this->enable_test)echo $message.'<br />';
                
                $this->email->message($message);
                if ( ! $this->email->send() )
                {
                    if($this->enable_debug) echo 'Email sanding failed to: '.$user_email.''."\n";
                }
                else
                {
                    // update research date_last_informed
                    if(!$this->enable_test)
                    foreach($researches_to_reset as $res_id)
                    {
                        $this->savedsearch_m->save(array('date_last_informed'=>date('Y-m-d H:i:s')), $res_id);
                    }
                    
                    $count_emails_success++;
                }
                
                // Send SMS notification
                if((config_db_item('clickatell_api_id') != '' || config_db_item('clickatell_api_key') != '') && file_exists(APPPATH.'controllers/admin/savesearch.php') &&
                   $user->research_sms_notifications == 1 && !empty($user->phone) )
                {
                    $return_sms = $this->clickatellapi->send_sms($sms_message, $user->phone);
                }
            }
            
        }
        
        if($this->enable_output) echo 'Email try: '.$count_emails_try.''."\n";
        if($this->enable_output) echo 'Email sent: '.$count_emails_success.''."\n";
        
        if($this->enable_output) echo 'Research completed!'."\n";
        exit();
    }
    
    /*
        Packages, cronjob to deactivate properties on package expire
    */
    public function package_expire($output = NULL)
    {
        $this->load->model('user_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $this->load->model('settings_m');
        $settings = $this->settings_m->get_fields();
        
        error_reporting(E_ERROR);
        
        if($output == 'output')
            $this->enable_output = true;
        
        $count_expired_packages = 0;
        $count_deactivated_properties = 0;
        
        // Fetch all USER-s with package expire
        $users = $this->user_m->get_by(array('package_id >'=>0, 'package_last_payment <'=>date('Y-m-d H:i:s')), FALSE, 20);
        
        foreach($users as $key_u=>$user)
        {
            $count_expired_packages++;
            
            // Fetch all estates for related user
            $estates = $this->user_m->get_estates($user->id);
            
            foreach($estates as $estate_id)
            {
                $count_deactivated_properties++;
                
                // Deactivate all properties for this users
                if(!$this->enable_test) $this->estate_m->save(array('is_activated' => NULL), $estate_id);
            }
            
            // Inform users via email to extend package
            
            if(!empty($user->mail) && ($user->data_expire_package_sent == NULL || $user->package_last_payment > $user->data_expire_package_sent))
            {
                
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                $this->email->from($settings['noreply'], lang_check('Web page'));
                $this->email->to($user->mail);
                
                $this->email->subject(lang_check('Your package expire!'));
                
                $data = array();
                $data['title'] = lang_check('Your package expire!');
                $data['user'] = (array)$user;
                
                $message = $this->load->view('email/package_expire', $data, TRUE);

                $this->email->message($message);
                $this->email->send();
                $data_u = array();
                $data_u['data_expire_package_sent'] = date('Y-m-d H:i:s');
                $this->user_m->save($data_u,$user->id);
            }
        }
        
        if($this->enable_output) echo 'Exired packages: '.$count_expired_packages.''."\n";
        if($this->enable_output) echo 'Deactivated properties: '.$count_deactivated_properties.''."\n";
        
        if($this->enable_output) echo 'package_expire completed!'."\n";
        exit();
    }
    
    /*
        Packages, cronjob to remove listings after 7 days on package expire
    */
    public function package_expire_after_7($output = NULL)
    {
        $this->load->model('user_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $this->load->model('settings_m');
        $settings = $this->settings_m->get_fields();
        
        error_reporting(E_ERROR);
        
        if($output == 'output')
            $this->enable_output = true;
        
        $count_expired_packages = 0;
        $count_removed_properties_all = 0;
        
        // Fetch all USER-s with package expire
        $users = $this->user_m->get_by(array('package_id >'=>0, 'package_last_payment <'=>date('Y-m-d H:i:s', strtotime('-7 day'))), FALSE, 20);
        
        foreach($users as $key_u=>$user)
        {
            $count_expired_packages++;
            
            // Fetch all estates for related user
            $estates = $this->user_m->get_estates($user->id);
            
            foreach($estates as $estate_id)
            {
                $count_removed_properties_all++;
                // Deactivate all properties for this users
                if(!$this->enable_test) $this->estate_m->delete($estate_id);
            }
            
            // Inform users via email to removed listings, if user had some listings

            if(!empty($estates) && !empty($user->mail))
            { 
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                $this->email->from($settings['noreply'], lang_check('Web page'));
                $this->email->to($user->mail);
                
                $this->email->subject(lang_check('Your listings has been removed!'));
                
                $data = array();
                $data['title'] = lang_check('Your listings has been removed!');
                $data['user'] = (array)$user;
                
                $message = $this->load->view('email/package_expire', $data, TRUE);

                $this->email->message($message);
                $this->email->send();
            }
            // Inform users via email to extend package
        }
        
        if($this->enable_output) echo 'Exired packages: '.$count_expired_packages.''."\n";
        if($this->enable_output) echo 'Removed properties: '.$count_removed_properties_all.''."\n";
        
        if($this->enable_output) echo 'package_expire_after_7 listings completed!'."\n";
        exit();
    }
    
    /*
        Email alert on favorites changed
    */
    public function favorites($output = NULL)
    {
        error_reporting(E_ERROR);
        
        $this->load->model('favorites_m');
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('user_m');
        $settings = $this->settings_m->get_fields();
        $emails_stack = array();
        
        if($output == 'output')
            $this->enable_output = true;
        
        if($this->enable_output) echo 'Favorites alert started!'."\n";
        
        // Fetch last date from favorites database
        $research_from_date = date('Y-m-d H:i:s');
        $row = $this->favorites_m->get_by(array('date_last_informed !='=>'NULL'), true, 1, 'date_last_informed');
        if(!empty($row))
        {
            if($row->date_last_informed != NULL)
                $research_from_date = $row->date_last_informed;

            if($this->enable_output) echo 'Changes from date: '.$research_from_date.''."\n";
        }
        else
        {
            exit('No favorites found!');
        }
        
        $def_langauge_id = $this->language_m->get_default_id();
        $researches_all = $this->favorites_m->get();
        
        $estates_to_research = $this->estate_m->get_by(array('date_modified >'=>$research_from_date, 'is_activated'=>1, 'language_id'=>$def_langauge_id), FALSE, NULL);

        //print_r($estates_to_research);
        if($this->enable_output) echo 'Total estates for check: '.sw_count($estates_to_research).''."\n";
        $count_emails_try = 0;
        $count_emails_success = 0;
        foreach($estates_to_research as $key_e=>$row_e){
            
            // For all favorites
            foreach($researches_all as $key_r=>$row_r){ 
                if(strtotime($row_r->date_last_informed) > strtotime($row_e->date_modified))
                    continue;

                // Send message to user
                if($this->enable_debug) echo 'Property: '.$row_e->id.'';
                
                // Add email to sending stack
                $email_data = array();
                $email_data['property_id'] = $row_e->id;
                $email_data['lang_code'] = $row_r->lang_code;
                $email_data['research_id'] = $row_r->id;
                $emails_stack[$row_r->user_id][$row_e->id.'_'.$row_r->id] = $email_data;
            }
        }
        
        $estates_to_research = NULL;
        
        // Send emails
        $count_emails_try = sw_count($emails_stack);
        //print_r($emails_stack);
        foreach($emails_stack as $user_id_k=>$emails_data){
            // Send email
            $user = $this->user_m->get($user_id_k);
            
            if(!empty($user->mail))
            {
                $user_email = $user->mail;
                if($this->enable_debug) echo 'Email to: '.$user_email.''."\n";

                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                $this->email->from($settings['noreply'], lang_check('Web page'));
                $this->email->to($user_email);
                
                $this->email->subject(lang_check('Changes on your property favorites!'));
                
                $researches_to_reset = array();
                $data = array();
                $data['message'] = lang_check('Changes on your property favorites!');
                foreach($emails_data as $email_data_v){
                    $data['property_links'][$email_data_v['property_id']] = '<a href="'.site_url('property/'.$email_data_v['property_id'].'/'.$email_data_v['lang_code']).'#content">'.lang_check('Check out property').' #'.$email_data_v['property_id'].'</a>';
                    $researches_to_reset[$email_data_v['research_id']] = $email_data_v['research_id'];
                }

                $data['research_link'] = '<a href="'.site_url('frontend/login/').'#content">'.lang_check('Manage your favorites').'</a>';
            
                $message = $this->load->view('email/favorites_changed_listing', $data, TRUE);
                
                if($this->enable_test)echo $message.'<br />';
                
                $this->email->message($message);
                if ( ! $this->email->send() )
                {
                    if($this->enable_debug) echo 'Email sanding failed to: '.$user_email.''."\n";
                }
                else
                {
                    // update research date_last_informed
                    foreach($researches_to_reset as $res_id)
                    {
                        $this->favorites_m->save(array('date_last_informed'=>date('Y-m-d H:i:s')), $res_id);
                    }

                    $count_emails_success++;
                }
            }
            
        }
        
        if($this->enable_output) echo 'Email try: '.$count_emails_try.''."\n";
        if($this->enable_output) echo 'Email sent: '.$count_emails_success.''."\n";
        
        if($this->enable_output) echo 'Favorites alerts completed!'."\n";
        exit();
    }

    /*
        Remove expire events
    */
    public function event_expire($output = NULL){
        $this->load->model('ads_m');
        $this->load->library('eventful');
        error_reporting(E_ERROR);
        
        // delete expired events, search expired by table ads.enddate and find listing for it events and removed too
        $this->eventful->delete_expiredevents_by_ads();
        
        if($this->enable_output) echo 'Events_expire removed!'."\n";
        exit();
    }

    /*
    * Added new events EventFul
    */
    public function events_fetch($output = NULL){
        
        $allowed_execution_time  = ini_get('max_execution_time')-180;
        
        $this->load->library('eventful', array('allowed_execution_time'=>$allowed_execution_time));
        error_reporting(E_ERROR);
        if($output == 'output')
            $this->enable_output = true;
        
        /*
         * Avaible categories http://api.eventful.com/rest/categories/list?app_key=mqNC3DgLpFk26frd
         * 
         * music,conference,comedy,learning_education,family_fun_kids,festivals_parades,movies_film,food, fundraisers,art,support,
         * holiday, books,attractions,community,business,singles_social,schools_alumni,clubs_associations,outdoors_recreation,
         * performing_arts,animals,politics_activism,sales,science,religion_spirituality,sports,technology,other
         *  
         */
 
        // Example predifined category ( fetch in array categories for import)
        $event_categories=array('music', 'comedy', 'learning_education');
        
        if(isset($event_categories) && !empty($event_categories))
            foreach ($event_categories as $event_category) {
                // get and set max pages as limit
                $limit = $this->eventful->get_count_pages($event_category);
                $result = $this->eventful->start_import(FALSE, $event_category, '', TRUE, $limit, 0);
                
                if($this->enable_output) echo 'Events import completed!'.PHP_EOL;
                if($this->enable_output) echo 'Event category: '.$event_category.PHP_EOL;
                if($this->enable_output) echo 'All property:'.sw_count($result['info']).PHP_EOL;
                if($this->enable_output) echo 'Added new:'.(sw_count($result['info']) - $result['count_skip']).PHP_EOL;
                if($this->enable_output) echo 'Skipped '.$result['count_skip'].PHP_EOL;
            }
        exit();
    }
    
    /*
     * Site map generate
     * 
     */
    public function sitemap_generate ($output = NULL) {
        
        if($output == 'output')
            $this->enable_output = true;
        
        $this->load->library('Sitemap');
        $this->sitemap->generate_sitemap();
        
        if($this->enable_output) echo 'Sitemap generated!'."\n";
        exit();
    }
    
    /* Featured listings cronjob, Featured page listings will auto cancelled after the event is done, add cronjob */
    function featured_udora ($output = NULL) {
        
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('file_m');
        
        $def_langauge_id = $this->language_m->get_default_id();
        
        /* auto cancelled after the event is done */
        $search = array('v_search_option_82_from'=> date('Y-m-d H:i:s'));
        
        $results_obj_id = $this->estate_m->get_by(array('is_featured' =>'1','language_id'=>$def_langauge_id), FALSE,NULL,NULL,NULL, $search);
        if($results_obj_id and !empty($results_obj_id))
        foreach ($results_obj_id as $key => $estate) {
            $estate_id = $estate->id;
            $data = array();
            
            $data['is_activated'] = 0;
            $this->estate_m->save($data, $estate_id);
            //echo $this->db->last_query();
        }
        
        if($this->enable_output) echo 'Deactiveted:'.sw_count($results_obj_id).' events'."\n";
    }
    
    /* Listings cronhob */
    function import_xml($file_name='', $output = NULL) {
        $json_output = array();
        $this->load->library('xml2u');
        $file = base_url($file_name);
        set_time_limit(99999999999);
        if($output == 'output')
            $this->enable_output = true;
        
        if(!file_exists(FCPATH.'/'.$file_name))
        {
            echo 'File not exists'; exit();
        }
        if($this->enable_output){
            header("X-Accel-Buffering:no");
            echo'<pre>';
            # Internal Server Error fix in case no apache_setenv() function exists
            if (function_exists('apache_setenv'))
            {
                @apache_setenv('no-gzip', 1);
            }
            @ini_set('zlib.output_compression', 0);
            @ini_set('implicit_flush', 1);
            for ($i = 0; $i < ob_get_level(); $i++)
                ob_end_flush();
            ob_implicit_flush(1);
            
            $output = $this->xml2u->import($file, FALSE, 15 , FALSE, FALSE, FALSE);
            echo 'Deactiveted:'.sw_count($output).' events'."\n";
        } else {
            $output = $this->xml2u->import($file, FALSE, 15 , FALSE, FALSE, FALSE);
        }
    }
}