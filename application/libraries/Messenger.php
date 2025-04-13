<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Messenger {
    
    protected $user_id;
    
    public function __construct() {
        
        /* add libraries and model */
        $this->CI = &get_instance();
        $this->CI->load->model('user_m');
        $this->CI->load->model('enquire_m');
        $this->CI->load->model('settings_m');
        $this->CI->load->helper('text');
        
        $this->settings = $this->CI->settings_m->get_fields();
        $this->user_id = $this->CI->session->userdata('id');
    }
    
    public function get_dialogs($limit = FALSE, $last_id = NULL)
    {
        if(!$this->user_id) {
            return array();
        }
        $_dialogs = $this->CI->enquire_m->get_dialogs($last_id);
        //dump($this->CI->db->last_query());
        //dump($_dialogs);
        $dialogs = array();
        foreach ($_dialogs as $key => $value) {
            // filter bad message
            if($value['from_id'] == $this->user_id && $value['to_id'] == $this->user_id )
                continue;
            
            if(empty($value['from_id']) || empty($value['to_id']))
                continue;
            
            $opponent_id = '';
            if($this->user_id != $value['from_id']){
                $opponent_id = $value['from_id'];
            } else {
                $opponent_id = $value['to_id'];
            }
            $dialog_id = $value['property_id'].'_'.$this->user_id.'_'.$opponent_id;
            
            $unreaded = 0 ;
            $unreaded = $value['unreaded'];
            unset($value['unreaded']);
            
            // added message (only latest);
            if(!isset($dialogs[$dialog_id])) {
                $dialogs[$dialog_id] = $value;
            }
            
            if($value['to_id'] == $this->user_id){
                $dialogs[$dialog_id]['unreaded'] = $unreaded;
            } elseif(!isset($dialogs[$dialog_id]['unreaded'])) {
                $dialogs[$dialog_id]['unreaded'] = 0;
            }
        }
        
        // dump($dialogs);
        /*
        echo $this->db->last_query();
        echo '<pre>';
        print_r($_dialogs); exit();*/
        if($limit && !empty($limit))
            $dialogs = array_slice($dialogs, 0, $limit);
        
        return $dialogs;
        
    }

    public function get_dialog($sel= NULL, $property_id= NULL, $latest_id = NULL) {
        $array = $this->CI->enquire_m->get_dialog($sel, $property_id, $latest_id);
        $array = array_reverse($array);
        return $array;
    }
    
    
    /*
     * Return new dialogs only if have one or more new messages
     * 
     */
    public function refresh_dialogs($limit = NULL, $last_id = NULL, $only_new = FALSE) {
        if($this->CI->enquire_m->check_new_dialogs($last_id)) {
            if(!$only_new) $last_id = NULL;
            return $this->get_dialogs($limit, $last_id);
        }
    }
    
    /*
     * Return new dialogs only if have one or more new messages
     * 
     */
    public function refresh_messages_by_dialog($interlocutor_id = NULL, $property_id = null, $latest_id = NULL) {
        if($this->CI->enquire_m->check_new_messages_by_dialog($interlocutor_id,$property_id, $latest_id)) {
            return $this->get_dialog($interlocutor_id, $property_id, $latest_id);
        }
        
    }
    
    function _generate_dialogs($array = array(), $lang_code = 'en', $api = false) {
        $all_dialogs = array();
        if(!empty($array))
        foreach ($array as $key => $value) {
            // Thumbnail agent
            if(!empty($value['interlocutor_image_user_filename']) && file_exists(FCPATH.'/files/thumbnail/'.$value['interlocutor_image_user_filename']) )
            {
               $value['interlocutor_image_url'] = base_url('files/thumbnail/'.$value['interlocutor_image_user_filename']);
            }
            else
            {
                $value['interlocutor_image_url'] =  'assets/img/user-agent.png';
                if($api)
                   $value['interlocutor_image_url'] =  base_url('templates/'.$this->settings['template'].'/assets/img/user-agent.png');
            }

            $value['message_chlimit'] = character_limiter(trim(preg_replace('/\s+/', ' ', $value['message'])), 40);
            if(strpos( $value['message_chlimit'], "<br/>") !== FALSE) {
                $value['message_chlimit'] = substr($value['message_chlimit'], strpos( $value['message_chlimit'], "<br/>")); 
            }
           
            $value['interlocutor_url'] = slug_url('profile/'.$value['interlocutor_id'].'/'.$lang_code.'/'.url_title_cro($value['interlocutor_name_surname']));

            $value['date_interval'] = $this->date_interval($value['date']);
            $timestamp = strtotime($value['date']);
            $m = strtolower(date("F", $timestamp));
            $d = strtolower(date("j", $timestamp));
            $y = strtolower(date("Y", $timestamp));
            $clock = strtolower(date("H:i:s", $timestamp));

            $value['date_formated']= $d.' '.lang_check('cal_' . $m).' '.$y.'</br>'.$clock;
           
           $all_dialogs[$key] = $value;
        }
        
        return $all_dialogs;
    }
    
    function _generate_diolog($array = array(), $lang_code = 'en', $api = false) {
        if(is_array($array) && !empty($array))
            foreach ($array as $key => $value) {

                $array[$key]->date_interval = $this->date_interval($value->date);

                $timestamp = strtotime($value->date);
                $m = strtolower(date("F", $timestamp));
                $d = strtolower(date("j", $timestamp));
                $y = strtolower(date("Y", $timestamp));
                $clock = strtolower(date("H:i:s", $timestamp));

                $array[$key]->date_format = $d.' '.lang_check('cal_' . $m).' '.$y.'</br>'.$clock;
            }
        else 
            $array = array();
        return $array;
    }
    
    function _generate_speakers($sel_id = NULL, $lang_code = 'en', $api = false) {
        
        $speakers = array();
        $speakers[$sel_id] = $this->CI->user_m->get($sel_id);
        $speakers[$this->user_id] = $this->CI->user_m->get($this->user_id);
        foreach ($speakers as $key => $value) {

            // Thumbnail agent
            if(!empty($value->image_user_filename) && file_exists(FCPATH.'/files/thumbnail/'.$value->image_user_filename) )
            {
                $value->image_url = base_url('files/thumbnail/'.$value->image_user_filename);
            }
            else
            {
                $value->image_url = 'assets/img/user-agent.png';
                
                if($api)
                    $value->image_url = base_url('templates/'.$this->settings['template'].'/assets/img/user-agent.png');
            }

            $value->user_url = slug_url('profile/'.$value->id.'/'.$lang_code.'/'.url_title_cro($value->name_surname));


            $speakers[$key] = $value;
         }
         
         return $speakers;
         
    }
    

    function date_interval($value) {
        $date_message=date_create($value);
        $date_now=date_create();
        
        $diff=date_diff($date_message,$date_now);

        if($date_message == $date_now) return lang_check('now');
        
        // %a outputs the total number of days
        if($diff->format("%a") > 7){
            $date_interval = $value;
        } elseif($diff->format("%a") > 1) {
            $date_interval = $diff->format("%a").' '.lang_check('days ago');
        } elseif($diff->format("%a") == 1) {
            $date_interval = $diff->format("%a").' '.lang_check('day ago');
        } elseif($diff->format("%h") > 1) {
            $date_interval = $diff->format("%h").' '.lang_check('hours ago');
        } elseif($diff->format("%h") == 1) {
            $date_interval = $diff->format("%h").' '.lang_check('hour ago');
        } elseif($diff->format("%i") > 1) {
            $date_interval = $diff->format("%i").' '.lang_check('minutes ago');
        } elseif($diff->format("%i") == 1) {
            $date_interval = $diff->format("%i").' '.lang_check('minute ago');
        } elseif($diff->format("%i") < 1 && $diff->format("%i") == 0  ) {
            $date_interval = lang_check('now');
        } else {
            $date_interval = $value;
        }

        return  $date_interval;
    }
    
    
}