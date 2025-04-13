<?php

class Privateapi extends CI_Controller
{
   
   private $user_id = NULL;
   
   private $data = array();
   
   private $settings = array();
    
   public function __construct()
   {
        parent::__construct();
        
        header('Content-Type: application/json');
        
        //load settings
        $this->load->model('settings_m');
        $this->settings = $this->settings_m->get_fields();
        
        //load language files
        $this->load->model('language_m');
        $lang_code_uri = $this->uri->segment(3);
        $lang_name = $this->language_m->get_name($lang_code_uri);
        if($lang_name != NULL)
            $this->lang->load('frontend_template', $lang_name, FALSE, TRUE, FCPATH.'templates/'.$this->settings['template'].'/');
        
        // Check login and fetch user id
        $this->load->library('session');
        $this->load->model('user_m');
        if($this->user_m->loggedin() == TRUE)
        {
            $this->user_id = $this->session->userdata('id');
        }
        else
        {
            $this->data['message'] = lang_check('Login required!');
            $this->data['success'] = false;
            echo json_encode($this->data);
            exit();
        }
   }

	public function index()
	{
		$this->data['message'] = lang_check('Hello, Private API here!');
        echo json_encode($this->data);
        exit();
	}
    
    public function design_save($lang_code='')
    {
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        // To fetch user_id use: $this->user_id

        $this->load->model('settings_m');
        
        $this->data['success'] = false;
        
        if($this->session->userdata('type') == 'ADMIN')
        {
            if(isset($this->data['parameters']['design_parameters']))
            {
                $post_data = array('design_parameters' => $this->data['parameters']['design_parameters'],
                                   'css_variant' =>  isset($this->data['parameters']['css_variant'])? $this->data['parameters']['css_variant'] : '',
                                   'color' => isset($this->data['parameters']['color'])? $this->data['parameters']['color'] : '');
                $this->settings_m->save_settings($post_data);
                if(isset($this->data['parameters']['color']))
                    $this->session->set_userdata( array('color'=>$this->data['parameters']['color']));
                
                $this->data['message'] = lang_check('Changes saved!');
                $this->data['success'] = true;
            }
            else
            {
                $this->data['message'] = lang_check('Parameters not defined!');
                $this->data['success'] = true;
            }
        }
        
        echo json_encode($this->data);
        exit();
    }
    
    public function add_to_favorites($lang_code='')
    {
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        $property_id = $this->input->post('property_id');
        // To fetch user_id use: $this->user_id

        $this->load->model('favorites_m');
        
        $this->data['success'] = false;
        // Check if property_id already saved, stop and write message
        if($this->favorites_m->check_if_exists($this->user_id, $property_id)>0)
        {
            $this->data['message'] = lang_check('Favorite already exists!');
            $this->data['success'] = true;
        }
        // Save favorites to database
        else
        {
            $data = $this->favorites_m->get_new_array();
            $data['user_id'] = $this->user_id;
            $data['property_id'] = $property_id;
            $data['lang_code'] = $lang_code;
            $data['date_last_informed'] = date('Y-m-d H:i:s');
            
            $this->favorites_m->save($data);
            
            $this->data['message'] = lang_check('Favorite added!');
            $this->data['success'] = true;
        }
        
        echo json_encode($this->data);
        exit();
    }
    
    public function remove_from_favorites($lang_code='')
    {
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        $property_id = $this->input->post('property_id');
        // To fetch user_id use: $this->user_id

        $this->load->model('favorites_m');
        
        $this->data['success'] = false;
        // Check if property_id already saved, stop and write message
        if($this->favorites_m->check_if_exists($this->user_id, $property_id)>0)
        {
            $favorite_selected = $this->favorites_m->get_by(array('property_id'=>$property_id, 'user_id'=>$this->user_id), TRUE);
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
        
        echo json_encode($this->data);
        exit();
    }
    
    public function dropdown($table)
    {
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        // To fetch user_id use: $this->user_id

        $this->data['success'] = false;
        
        if(empty($this->data['parameters']['limit']))
            $this->data['parameters']['limit'] = 10;
            
        if(empty($this->data['parameters']['offset']))
            $this->data['parameters']['offset'] = 0;
            
        if(empty($this->data['parameters']['attribute_id']))
            $this->data['parameters']['attribute_id'] = 'id';
            
        if(empty($this->data['parameters']['show_empty']))
            $this->data['parameters']['show_empty'] = false;
            
        if(empty($this->data['parameters']['attribute_value']))
            $this->data['parameters']['attribute_value'] = 'address';
        
        if(substr($table,-2, 2) == '_m')
        {
            // it's model
            $attr_id = $this->data['parameters']['attribute_id'];
            $attr_val = $this->data['parameters']['attribute_value'];
            $attr_search = $this->data['parameters']['search_term'];
            
            $id_part="";
            if(is_numeric($attr_search))
                $id_part = "$attr_id=$attr_search OR ";
            
            $this->load->model($table);
            
            $where = array();
            if(!empty($this->data['parameters']['language_id']))
                $where["language_id"] = $this->data['parameters']['language_id'];
            
            if(!empty($attr_search))
                $where["($id_part $attr_val LIKE '%$attr_search%')"] = NULL;
            
            //get_by($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = NULL, 
            //$search = array(), $where_in = NULL, $check_user = FALSE, $fetch_user_details=FALSE)
            if($table == 'estate_m')
            {
                $q_results = $this->$table->get_by($where, FALSE, $this->data['parameters']['limit'], 
                                                    "$attr_id DESC", $this->data['parameters']['offset'],
                                                    array(), NULL, TRUE);
            }
            else
            {
                $q_results = $this->$table->get_by($where, FALSE, $this->data['parameters']['limit'], 
                                                    "$attr_id DESC", $this->data['parameters']['offset']);
            }
            
            $results = array();
            
            if($this->data['parameters']['show_empty'] == true && $this->data['parameters']['offset'] == 0)
            {
                $results[-1]['key'] = '';
                $results[-1]['value'] = '-';
            }
            
            foreach ($q_results as $key=>$row)
            {
                $results[$key]['key'] = $row->id;
                $results[$key]['value'] = $row->{$this->data['parameters']['attribute_id']}.', '.
                                            _ch($row->{$this->data['parameters']['attribute_value']});
            }
            
            // get current value by ID
            $row = $this->$table->get($this->data['parameters']['curr_id']);
            if(is_object($row))
            {
                $this->data['curr_val'] = $row->{$this->data['parameters']['attribute_id']}.', '.
                                                _ch($row->{$this->data['parameters']['attribute_value']});
            }
            else
            {
                $this->data['curr_val'] = '-';
            }
            
            $this->data['success'] = true;
        }
        else
        {
            // it's table
            if($this->session->userdata('type') == 'ADMIN')
            {
                if(!empty($this->data['parameters']['search_term']))
                {
                    $attr_id = $this->data['parameters']['attribute_id'];
                    $attr_val = $this->data['parameters']['attribute_value'];
                    $attr_search = $this->data['parameters']['search_term'];
                    
                    $id_part="";
                    if(is_numeric($attr_search))
                        $id_part = "$attr_id=$attr_search OR ";
                    
                    $this->db->where("($id_part $attr_val LIKE '%$attr_search%')", NULL, FALSE);
                }
                
                $this->db->order_by("id desc"); 
                $query = $this->db->get($table, $this->data['parameters']['limit'], $this->data['parameters']['offset']);
                
                $results = array();
                foreach ($query->result() as $key=>$row)
                {
                    $results[$key]['key'] = $row->id;
                    $results[$key]['value'] = $row->{$this->data['parameters']['attribute_id']}.', '.
                                                _ch($row->{$this->data['parameters']['attribute_value']});
                }
                
                // get current value by ID
                $this->db->where("id", $this->data['parameters']['curr_id']); 
                $query = $this->db->get($table, 1);
                $row = $query->row();
                if(!empty($row))
                {
                    $this->data['curr_val'] = $row->{$this->data['parameters']['attribute_id']}.', '.
                                                    _ch($row->{$this->data['parameters']['attribute_value']});
                }
                else
                {
                    $this->data['curr_val'] = '-';
                }
                
                $this->data['success'] = true;
            }
            else
            {
                $this->data['success'] = false;
            }
        }
        
        $this->data['results'] = $results;
        
        echo json_encode($this->data);
        exit();
    }

    public function save_search($lang_code='')
    {
        $this->data['message'] = lang_check('No message returned!');
        $this->data['success'] = true;
        if(sw_count($_POST > 0))
        {
            // [START] Radius search
            $search_radius = $_POST['v_search_radius'];
            if(isset($search_radius) && isset($_POST['v_search_option_smart']) && $search_radius > 0)
            {
                $this->load->library('ghelper');
                $coordinates_center = $this->ghelper->getCoordinates($search_array['v_search_option_smart']);
                
                if(sw_count($coordinates_center) >= 2 && $coordinates_center['lat'] != 0)
                {
                    // calculate rectangle
                    $rectangle_ne = $this->ghelper->getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 315, $search_radius);
                    $rectangle_sw = $this->ghelper->getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 135, $search_radius);
                    
                    $_POST['v_rectangle_ne'] = $rectangle_ne;
                    $_POST['v_rectangle_sw'] = $rectangle_sw;
                    unset($_POST['v_search_option_smart'], $_POST['v_undefined'], $_POST['v_search_radius']);
                }
            }
            // [END] Radius search
        }

        $this->data['parameters'] = $_POST;
        $parameters = json_encode($_POST);
        // To fetch user_id use: $this->user_id
        
        $this->load->model('savedsearch_m');
        
        // Check if parameters already saved, stop and write message
        if($this->savedsearch_m->check_if_exists($this->user_id, $parameters, $lang_code)>0)
        {
            $this->data['message'] = lang_check('Search already exists!');
        }
        // Save parameters to database
        else
        {
            $data = $this->savedsearch_m->get_new_array();
            $data['user_id'] = $this->user_id;
            $data['parameters'] = $parameters;
            $data['lang_code'] = $lang_code;
            
            // Check if there is some parameters
            $values_exists = false;
            foreach($this->data['parameters'] as $key=>$value){
                if(!empty($value) && $key != 'view' && $key != 'order' && 
                    $key != 'page_num' && $key != 'v_search-start')
                $values_exists = true;
            }
            
            if(!$values_exists)
            {
                $this->data['message'] = lang_check('No values selected!');
                echo json_encode($this->data);
                exit();
            }
            
            $this->savedsearch_m->save($data);
            
            $this->data['message'] = lang_check('Search saved!');
        }
        
        echo json_encode($this->data);
        exit();
    }
    
    public function get_level_values_select($lang_id, $field_id, $parent_id=0, $level=0)
    {
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        $parameters = json_encode($_POST);
        // To fetch user_id use: $this->user_id
        
        $this->load->model('language_m');
        $this->load->model('treefield_m');

        $lang_name = $this->session->userdata('lang');
        if(!empty($lang_id))
            $lang_name = $this->language_m->get_name($lang_id);
            
        $this->lang->load('backend_base', $lang_name);
        
        $values_arr = $this->treefield_m->get_level_values ($lang_id, $field_id, $parent_id, $level);
        
        $generate_select = '';
        foreach($values_arr as $key=>$value)
        {
            $generate_select.= "<option value=\"$key\">$value</option>\n";
        }
        
        $this->data['generate_select'] = $generate_select;
        $this->data['values_arr'] = $values_arr;
        
        echo json_encode($this->data);
        exit();
    }
    
    public function load_reservations($property_id)
    {
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        $parameters = json_encode($_POST);
        // To fetch user_id use: $this->user_id
        
        $this->load->model('reservations_m');
        
        $existing_dates = array();
        $query = $this->db->get_where('reservations', array('property_id' => $property_id, 'date_to >' => date('Y-m-d 00:00:00')));
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                /* [get days] */
                $days_between = $this->reservations_m->days_between($row->date_from, $row->date_to);
                
                $days = array();
                for($i=0; $i < $days_between;  $i++)
                {
                    $row_time = strtotime($row->date_from." + $i day");
                    $row_time_00 = date("Y-m-d", $row_time);
                    $existing_dates[$row_time_00] = $row_time_00;
                }
                /* [/get days] */
            }
        }
        
        $this->data['existing_dates'] = $existing_dates;     

        echo json_encode($this->data);
        exit();
    }
    
    public function property_exists($lang_code='')
    {
        $this->load->model('estate_m');
        $this->load->model('removedlistings_m');
        
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        // To fetch user_id use: $this->user_id

        $this->load->model('settings_m');
        
        $this->data['success'] = false;
        $this->data['exists'] = true;
        $this->data['removed'] = true;
        $this->data['removed_list'] = array();
        
        if(empty($this->data['parameters']['address']) || empty($this->data['parameters']['gps']))
        {}
        else
        {
            $address = $this->data['parameters']['address'];
            $gps = explode(', ', $this->data['parameters']['gps']);
            $lat = floatval($gps[0]);
            $lng = floatval($gps[1]);
            
            $id=0;
            if(isset($this->data['parameters']['id']))
                $id = $this->data['parameters']['id'];
            
            $listings_similar = $this->estate_m->get_similar($address, $lat, $lng, array(), $id);
            //$this->data['similar_query'] = $this->db->last_query();
            
            $listings_removed = $this->removedlistings_m->get_similar($address, $lat, $lng, array());

            if($listings_similar === NULL)
            {
                $this->data['exists'] = false;
            }
            
            if($listings_removed === NULL)
            {
                $this->data['removed'] = false;
            }
            else
            {
                $this->data['removed_list'] = $listings_removed;
            }
            
            if($listings_similar === NULL && $listings_removed === NULL)
                $this->data['success']=TRUE;
            
        }

        echo json_encode($this->data);
        exit();
    }
    

    function parse_svg_map($file_name= NULL) {
        if($file_name == NULL) return false;
            
        // Fetch settings
        $this->load->model('settings_m');
        $settings= $this->settings_m->get_fields();
        
        $this->data = array();
        $this->data['success'] = false;
        $region_names = array();
        
        if(file_exists(FCPATH.'templates/'.$settings['template'].'/assets/svg_maps/'.$file_name)){
            $svg = file_get_contents(FCPATH.'templates/'.$settings['template'].'/assets/svg_maps/'.$file_name);
            $region_names = array();
            $match = '';
            preg_match_all('/(data-title-map)=("[^"]*")/i', $svg, $match);

            if(!empty($match[2])) {
                preg_match_all('/(data-name)=("[^"]*")/i', $svg, $matches);
                if(!empty($matches[2]))
                    foreach ($matches[2] as $value) {
                       $value = str_replace('"', '', $value);                       
                        $region_names[] = $value;
                    }
            } else if(stristr($svg, "http://amcharts.com/ammap") != FALSE ) {
                preg_match_all('/(title)=("[^"]*")/i', $svg, $matches);
                if(!empty($matches[2]))
                    foreach ($matches[2] as $value) {
                       $value = str_replace('"', '', $value);                       
                        $region_names[] = $value;
                    }
            }   
            
            $match = '';
            $this->data['title_map']='';
            preg_match_all('/(data-title-map)=("[^"]*")/i', $svg, $match);

            if(!empty($match[2])) {
               $this->data['title_map'] = str_replace('"', '', $match[2][0]);
            }
                
                
          $this->data['success'] = true; 
          $this->data['region_names'] = $region_names;
        }    
        
        
        echo json_encode($this->data);
        exit();        
    }
    
    /*
     * For Eventful lib
     * return count_pages
     */
    function eventful_get_count_pages($eventful_category = NULL){
        $this->data['success'] = false; 
        if($eventful_category == NULL)  {
            echo json_encode($this->data);
            exit();      
        }
        
        if(!file_exists(APPPATH.'libraries/Eventful.php') || $this->session->userdata('type')!='ADMIN') {
            echo json_encode($this->data);
            exit(); 
        }
        
        $this->load->library('eventful');
        $result = $this->eventful->get_count_pages($eventful_category);
        
        if($result != FALSE) {
            $this->data['success'] = true; 
            $this->data['eventful_get_count_pages'] = $result; 
            echo json_encode($this->data);
            exit();  
        } else {
            echo json_encode($this->data);
            exit();
        }
    }
    
    /*
     * 
     * Messenger
     * get_dialogs
     */
    
    function get_dialogs ($lang_code='en', $limit = NULL) {
        $this->data['success'] = false;
        /* check if user login */
        if(!file_exists(APPPATH.'libraries/Messenger.php') || !$this->session->userdata('id')) {
            $this->data['success'] = false;
            echo json_encode($this->data);
            exit();
        }
        $this->load->model('enquire_m');
        $this->load->library('messenger');
         
        $post = $_POST;
        $_all_dialogs = $this->messenger->refresh_dialogs($limit, $post['latest_id']);
        $this->data['all_dialogs'] = $this->messenger->_generate_dialogs($_all_dialogs,$lang_code, true);
        $this->data['unreaded_message']= 0;
        if(!empty($_all_dialogs)){
            $this->data['latest_id'] = $this->data['all_dialogs'][current(array_keys($this->data['all_dialogs']))]['id'];
            $this->data['success'] = true;
            foreach($this->data['all_dialogs'] as $key => $dialog) {
                $this->data['unreaded_message'] += intval($dialog['unreaded']);
            }
        }
        
        //echo $this->db->last_query();
        //$this->data['latest_date'] = date('Y-m-d H:i:s');
        
        echo json_encode($this->data);
        exit();
    }
       
    /*
     * 
     * Messenger
     * get_messages
     */
    
    function get_dialog ($lang_code='en') {
        $this->data['success'] = false;
        /* check if user login */
        if(!file_exists(APPPATH.'libraries/Messenger.php') || !$this->session->userdata('id')) {
            $this->data['success'] = false;
            echo json_encode($this->data);
            exit();
        }
        
        $this->load->model('enquire_m');
        $this->load->library('messenger');
        
        $post = $_POST;
        $_dialog = $this->messenger->refresh_messages_by_dialog($post['sel_id'], $post['property_id'], $post['latest_id']);
        $this->data['dialog'] = $this->messenger->_generate_diolog($_dialog, $lang_code, true);
        
         if(!empty($this->data['dialog'])) {
            $this->data['speakers'] = $this->messenger->_generate_speakers($post['sel_id'], $lang_code, true);
            $this->data['latest_id'] = $this->data['dialog'][end(array_keys($this->data['dialog']))]->id;
         }
        
        if(!empty($this->data['dialog']))
            $this->data['success'] = true;
        
        echo json_encode($this->data);
        exit();
        
    }
    
    function save_message ($lang_code='en') {
        
        /* check if user login */
        if(!file_exists(APPPATH.'libraries/Messenger.php') || !$this->session->userdata('id')) {
            $this->data['success'] = false;
            echo json_encode($this->data);
            exit();
        }
        
        $user_id = $this->session->userdata('id');
        $user = $this->user_m->get($user_id);
        
        $this->load->model('enquire_m');
        $this->data['success'] = false;
        
        $post = $_POST;
        
        $data = array();
        $data['name_surname'] = $user->name_surname;
        $data['phone'] =$user->phone;
        $data['mail'] = $user->mail;
        $data['message'] = $post['message'];
        $data['property_id'] = $post['property_id'];
        $data['address'] = $post['address'];
        $data['readed'] = 0;
        $data['from_id'] = $this->session->userdata('id');
        $data['to_id'] = $post['to_id'];
        $data['date'] = date('Y-m-d H:i:s');
        
        $this->enquire_m->save($data);
        
        $this->data['success'] = true;
        echo json_encode($this->data);
        exit();
        
    }
    
    function readed ($lang_code='en') {
        /* check if user login */
        if(!file_exists(APPPATH.'libraries/Messenger.php') || !$this->session->userdata('id')) {
            $this->data['success'] = false;
            echo json_encode($this->data);
            exit();
        }
        
        $user_id = $this->session->userdata('id');
        
        $this->load->model('enquire_m');
        $this->data['success'] = false;
        
        $post = $_POST;
        
        $this->enquire_m->readed(null, $post['sel_id'], $post['property_id']);
        
        $this->data['success'] = true;
        echo json_encode($this->data);
        exit();
        
    }
    
    function visual_edit_getwidgets ($lang_code='en',$get_type = NULL) {
        
        $this->data['success'] = true;

        $this->load->model('page_m');
        $CI = &get_instance();
        $settings= $this->settings_m->get_fields();
        $CI->app_settings = $settings;
        $_widgets = $this->page_m->get_subtemplates('widgets');
        
        if(empty($get_type)){
            $this->data['success'] = true;
            echo json_encode($this->data);
            exit();
        }
        
        $get_widget_params = function($file = NULL, $key='') {
            if($file== NULL || empty($key)) return false;
            $file = FCPATH.$file;
            if ( file_exists( $file ) && is_file( $file ) ) {
                if ( preg_match( '|'.$key.':(.*)$|mi', file_get_contents($file), $name )) {
                    return trim($name[1]);
                }
            }
            return false;
        };
        
        $widgets = array();
        foreach($_widgets as $key=>$val)
        {
            if(substr($key, 0, strlen($get_type)) == $get_type)
            {
                $widget = array();
                $widget_title = $get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-title');
                $widget_image = $get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-preview-image');
                if(!empty($widget_title) || !empty($widget_image) ) {
                    if(!empty($widget_image) && file_exists(FCPATH."templates/".$settings["template"].$widget_image)) {
                        $widget['title'] = lang_check($widget_title);
                        $widget['filename'] = $key;
                        $widget['thumbnail'] = base_url("/templates/".$settings["template"].$widget_image);
                        $widgets[]=$widget;
                    }
                }
            }
        }  
        $this->data['widgets'] = $widgets;
        
        
        $this->data['success'] = true;
        echo json_encode($this->data);
        exit();
        
    }
    
    function visual_edit_getwidget_content ($lang_code='en',$widget_filename = NULL) {
        
        $this->data['success'] = false;
        $CI = &get_instance();
        $settings= $this->settings_m->get_fields();
        $this->load->helper('form_helper');
        
        //$widget_filename = 'center_categories_tiles';
        
        $_content = file_get_contents_curl(site_url($lang_code.'/inc_widget_preview/'.$widget_filename));
        
        preg_match("/<body[^>]*>(.*?)<\/body>/is", $_content, $matches);
        
        if(isset($matches[1])) {
            $inc = $matches[1];
        }
        
        $this->data['content'] = $inc;
        $this->data['success'] = true;
        echo json_encode($this->data);
        exit();
        
    }
    
        
    function visual_edit_save_template ($lang_code='en') {
        
        $this->data['success'] = false;
        $this->load->helper('form_helper');
        $this->load->model('customtemplates_m');
        
        //$widget_filename = 'center_categories_tiles';
        $post = $_POST;
        $data = array();
        $id = $post['template_id'];
        
        $data['theme'] = $post['theme'];
        $data['template_name'] = $post['template_name'];
        $data['type'] = $post['type'];
        
        if($this->config->item('app_type') == 'demo')
        {
            $this->data['success'] = false;
            echo json_encode($this->data);
            exit();
        }
        
        /* filter widgets_order */
        $post['widgets_order'] = json_decode($post['widgets_order'],'JSON_OBJECT_AS_ARRAY');
        $widgets_order = array();
        foreach ($post['widgets_order'] as $type => $widgets) {
            $widgets_order[$type] = array();
            foreach ($widgets as $value) {
                if(!empty($value))
                    $widgets_order[$type][]=$value;
            }
        }
        $post['widgets_order'] = json_encode($widgets_order);
         /* end filter widgets_order */
        
        $data['widgets_order'] = $post['widgets_order'];
        
        $id = $this->customtemplates_m->save($data, $id, TRUE);
        $this->data['success'] = true;
        echo json_encode($this->data);
        exit();
        
    }
    
    
    /*
     * 
     * $json_output['importing']
                                0 - befor start
                                1 - importing
                                2 - end import
     * 
     */
    
    public function importcsv () {
            $json_output = array();

        $this->load->library('importcsv');
        
        //qr_code
        $post = $_POST;
        /*$post['importing'] = true;*/
        $json_output['reference'] =array();
        $json_output['message'] = '';
        $json_output['success'] = false;
       
        /* input data */
        $json_output['reference']['overwrite_existing'] = 0;
        if(isset($post['overwrite_existing']))
            $json_output['reference']['overwrite_existing'] = $post['overwrite_existing'];

        $json_output['reference']['max_images'] = 1;
        if(isset($post['max_images']))
            $json_output['reference']['max_images'] = $post['max_images'];

        $json_output['reference']['google_gps'] = false;
        if(isset($post['google_gps']))
            $json_output['reference']['google_gps'] = $post['google_gps'];

        $json_output['reference']['file_name'] = false;
        if(isset($post['file_name']))
            $json_output['reference']['file_name'] = $post['file_name'];

        $json_output['reference']['count_key'] = 0;
        if(isset($post['count_key']))
            $json_output['reference']['count_key'] = $post['count_key'];
        /* end input data */
        
        $json_output['output'] = array();
        $json_output['log'] = array();  
        
        if(!isset($post['importing'])) {
            if(isset($_FILES['userfile_csv'])) {
                $csv_file = $_FILES['userfile_csv'];
                $csv_file = $csv_file['tmp_name'];
                $output = $this->importcsv->ajax_import(true, $csv_file, $json_output['reference']['overwrite_existing'],  $json_output['reference']['max_images'], $json_output['reference']['google_gps']);
                 
                $json_output['reference']['file_name'] = $output['file_name'];
                $json_output['reference']['count_all'] = $json_output['output']['count_all'] = $output['listings'];
                $json_output['reference']['count_key'] = $json_output['output']['count_key'] = 0;
                $json_output['reference']['count_skip'] = $json_output['output']['count_skip'] = 0;
                $json_output['output']['proccess'] = 0;
                 
                if(!empty($output['listings']))
                    $json_output['log'][] = lang_check('Csv uploaded, founded: ').$output['listings'].' '.lang_check('listings');
                else
                    $json_output['log'][] = lang_check('Csv file did not attached or empty');
                
                $json_output['importing'] = '1';  
            }
            else { 
                dump($post);
                exit('ajax error import');
            }
        } elseif(isset($post['importing']) && $post['importing'] == '1') {
            $csv_file = 'importcsv_1562776472874.data';
            $csv_file = $json_output['reference']['file_name'];
            $output_data = $this->importcsv->ajax_import(false, $csv_file, $json_output['reference']['overwrite_existing'],  $json_output['reference']['max_images'], $json_output['reference']['google_gps'], $json_output['reference']['count_key']);
            
            foreach ($output_data['info'] as $value) {
                $json_output['log'][] = lang_check('Added new listing').': '.$value['address'];
            }
      
            $json_output['reference']['count_all'] = $json_output['output']['count_all'] = $output_data['count_all'];
            $json_output['reference']['count_key'] = $json_output['output']['count_key'] = $output_data['count_key'];
            $json_output['reference']['count_skip'] = $json_output['output']['count_skip'] = $post['count_skip'] + $output_data['count_skip'];
            $json_output['importing'] = '1';
            
            if($json_output['reference']['count_all'] <= ($json_output['reference']['count_key'] + 1)) {
                $json_output['importing'] = '2';
                $json_output['log'][] = lang_check('Import done, added').': '.($json_output['output']['count_all'] -  $json_output['output']['count_skip']);
                $json_output['log'][] = lang_check('Skipped').': '.$json_output['output']['count_skip'];
            }
                          
        } elseif(isset($post['importing']) && $post['importing'] == '2') {
            exit('ajax import done');
        }
        echo json_encode($json_output);
        exit();
    }
    
    /*
     * 
     * $json_output['importing']
                                0 - befor start
                                1 - importing
                                2 - end import
     * 
     */
    
    public function importcsv_users () {
        $json_output = array();

        $this->load->library('importcsv_users');
        
        //qr_code
        $post = $_POST;
        /*$post['importing'] = true;*/
        $json_output['reference'] =array();
        $json_output['message'] = '';
        $json_output['success'] = false;
       
        /* input data */
        $json_output['reference']['file_name'] = false;
        if(isset($post['file_name']))
            $json_output['reference']['file_name'] = $post['file_name'];

        $json_output['reference']['count_key'] = 0;
        if(isset($post['count_key']))
            $json_output['reference']['count_key'] = $post['count_key'];
        /* end input data */
        
        $json_output['output'] = array();
        $json_output['log'] = array();  
        
        if(!isset($post['importing'])) {
            if(isset($_FILES['userfile_csv'])) {
                $csv_file = $_FILES['userfile_csv'];
                $csv_file = $csv_file['tmp_name'];
                $output = $this->importcsv_users->ajax_import(true, $csv_file);
                 
                $json_output['reference']['file_name'] = $output['file_name'];
                $json_output['reference']['count_all'] = $json_output['output']['count_all'] = $output['listings'];
                $json_output['reference']['count_key'] = $json_output['output']['count_key'] = 0;
                $json_output['reference']['count_skip'] = $json_output['output']['count_skip'] = 0;
                $json_output['output']['proccess'] = 0;
                 
                if(!empty($output['listings']))
                    $json_output['log'][] = lang_check('Csv uploaded, founded: ').$output['listings'].' '.lang_check('elements');
                else
                    $json_output['log'][] = lang_check('Csv file did not attached or empty');
                
                $json_output['importing'] = '1';  
            }
            else { 
                dump($post);
                exit('ajax error import');
            }
        } elseif(isset($post['importing']) && $post['importing'] == '1') {
            $csv_file = 'importcsv_1562776472874.data';
            $csv_file = $json_output['reference']['file_name'];
            $output_data = $this->importcsv_users->ajax_import(false, $csv_file, $json_output['reference']['count_key']);
            
            foreach ($output_data['info'] as $value) {
                $json_output['log'][] = lang_check('New user').': #'.$value['id'].' - '.$value['print_data'];
            }
      
            $json_output['reference']['count_all'] = $json_output['output']['count_all'] = $output_data['count_all'];
            $json_output['reference']['count_key'] = $json_output['output']['count_key'] = $output_data['count_key'];
            $json_output['reference']['count_skip'] = $json_output['output']['count_skip'] = $post['count_skip'] + $output_data['count_skip'];
            $json_output['importing'] = '1';
            
            if($json_output['reference']['count_all'] <= ($json_output['reference']['count_key'] + 1)) {
                $json_output['importing'] = '2';
                $json_output['log'][] = lang_check('Import done, added').': '.($json_output['output']['count_all'] -  $json_output['output']['count_skip']);
                $json_output['log'][] = lang_check('Skipped').': '.$json_output['output']['count_skip'];
            }
                          
        } elseif(isset($post['importing']) && $post['importing'] == '2') {
            exit('ajax import done');
        }
        echo json_encode($json_output);
        exit();
    }
    
    /*
     * 
     * $json_output['importing']
                                0 - befor start
                                1 - importing
                                2 - end import
     * 
     */
    
    public function importcsv_xml2u () {
        $json_output = array();
        $this->load->library('xml2u');

        //qr_code
        $post = $_POST;
        /*$post['importing'] = true;*/
        $json_output['reference'] =array();
        $json_output['message'] = '';
        $json_output['success'] = false;
       
        /* input data */
        $json_output['reference']['overwrite_existing'] = 0;
        if(isset($post['overwrite_existing']))
            $json_output['reference']['overwrite_existing'] = $post['overwrite_existing'];

        $json_output['reference']['max_images'] = 1;
        if(isset($post['max_images']))
            $json_output['reference']['max_images'] = $post['max_images'];

        $json_output['reference']['google_gps'] = 0;
        if(isset($post['google_gps']))
            $json_output['reference']['google_gps'] = $post['google_gps'];

        $json_output['reference']['file_name'] = false;
        if(isset($post['file_name']))
            $json_output['reference']['file_name'] = $post['file_name'];

        $json_output['reference']['count_key'] = 0;
        if(isset($post['count_key']))
            $json_output['reference']['count_key'] = $post['count_key'];
        
        $json_output['reference']['user_id'] = $this->session->userdata('id');
        if(isset($post['user_id']))
            $json_output['reference']['user_id'] = $post['user_id'];

        $json_output['reference']['activated'] = 0;
        if(isset($post['activated']))
            $json_output['reference']['activated'] = $post['activated'];
        /* end input data */
       
        $json_output['output'] = array();
        $json_output['log'] = array();  
        
        if(!isset($post['importing'])) {
            if(isset($post['xml_url'])) {
                $csv_file = $post['xml_url'];
                $output = $this->xml2u->ajax_import(true, $csv_file, $json_output['reference']['overwrite_existing'],  $json_output['reference']['max_images'], $json_output['reference']['google_gps'], 0, $json_output['reference']['user_id'],$json_output['reference']['activated']);
                 
                $json_output['reference']['file_name'] = $output['file_name'];
                $json_output['reference']['count_all'] = $json_output['output']['count_all'] = $output['listings'];
                $json_output['reference']['count_key'] = $json_output['output']['count_key'] = 0;
                $json_output['reference']['count_skip'] = $json_output['output']['count_skip'] = 0;
                $json_output['output']['proccess'] = 0;
                 
                if(!empty($output['listings']))
                    $json_output['log'][] = lang_check('Xml uploaded, founded: ').$output['listings'].' '.lang_check('listings');
                else
                    $json_output['log'][] = lang_check('Xml file did not attached or empty');
                
                $json_output['importing'] = '1';  
            }
            else { 
                dump($post);
                exit('ajax error import');
            }
        } elseif(isset($post['importing']) && $post['importing'] == '1') {
            $csv_file = 'importcsv_1562776472874.data';
            $csv_file = $json_output['reference']['file_name'];
            $output_data = $this->xml2u->ajax_import(false, $csv_file, $json_output['reference']['overwrite_existing'],  $json_output['reference']['max_images'], $json_output['reference']['google_gps'], $json_output['reference']['count_key'], $json_output['reference']['user_id'],$json_output['reference']['activated']);
            
            foreach ($output_data['info'] as $value) {
                $json_output['log'][] = lang_check('Added/Overwrite new listing').': '.$value['address'];
            }
      
            $json_output['reference']['count_all'] = $json_output['output']['count_all'] = $output_data['count_all'];
            $json_output['reference']['count_key'] = $json_output['output']['count_key'] = $output_data['count_key'];
            $json_output['reference']['count_skip'] = $json_output['output']['count_skip'] = $post['count_skip'] + $output_data['count_skip'];
            $json_output['importing'] = '1';
            
            if($json_output['reference']['count_all'] <= ($json_output['reference']['count_key'] + 1)) {
                $json_output['importing'] = '2';
                $json_output['log'][] = lang_check('Import done, added').': '.($json_output['output']['count_all'] -  $json_output['output']['count_skip']);
                $json_output['log'][] = lang_check('Skipped').': '.$json_output['output']['count_skip'];
            }
                          
        } elseif(isset($post['importing']) && $post['importing'] == '2') {
            exit('ajax import done');
        }
        echo json_encode($json_output);
        exit();
    }
        
    function xmlstr_to_array($xmlstr) {
        $doc = new DOMDocument();
        
        if ( !@$doc->loadXML($xmlstr) ) {
            return false;
        }
        
        $root = $doc->documentElement;
        $output = $this->domnode_to_array($root);
        $output['@root'] = $root->tagName;
        return $output;
    }
    
    function domnode_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
          case XML_CDATA_SECTION_NODE:
          case XML_TEXT_NODE:
            $output = trim($node->textContent);
          break;
          case XML_ELEMENT_NODE:
            for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
              $child = $node->childNodes->item($i);
              $v = $this->domnode_to_array($child);
              if(isset($child->tagName)) {
                $t = $child->tagName;
                if(!isset($output[$t])) {
                  $output[$t] = array();
                }
                $output[$t][] = $v;
              }
              elseif($v || $v === '0') {
                $output = (string) $v;
              }
            }
            if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
              $output = array('@content'=>$output); //Change output into an array.
            }
            if(is_array($output)) {
              if($node->attributes->length) {
                $a = array();
                foreach($node->attributes as $attrName => $attrNode) {
                  $a[$attrName] = (string) $attrNode->value;
                }
                $output['@attributes'] = $a;
              }
              foreach ($output as $t => $v) {
                if(is_array($v) && count($v)==1 && $t!='@attributes') {
                  $output[$t] = $v[0];
                }
              }
            }
          break;
        }
        return $output;
    }

    public function estate_option_change($lang_code = NULL) {
        $this->data['message'] = lang_check('Listing Updated');
        $this->data['success'] = false;
        $this->data['parameters'] = $_POST;
        $this->load->model('estate_m');
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        $this->load->model('packages_m');

        $this->load->library('form_validation');

        if($lang_code != NULL){
            $lang_name = $this->language_m->get_name($lang_code);
            $this->lang->load('frontend_template', $lang_name, FALSE, TRUE, FCPATH.'templates/'.$this->settings['template'].'/');
        }

        $property_id = $this->input->post('property_id');
        $error='';

        // Process form
        if($property_id){
            unset($_POST['property_id']);

            // Set form
            $rules = array();
            foreach($_POST as $field => $value){

                if($field == 'is_featured') {
                    $rules [] = array('field'=>$field, 'label'=>'lang:'.$field, 'rules'=>'trim|callback_featured_limitation_check');
                } else {
                    $rules [] = array('field'=>$field, 'label'=>'lang:'.$field, 'rules'=>'trim|xss_clean');
                }

            }
            
            $this->form_validation->set_rules($rules);
            //Check if user have permissions
            $property_user_id = $this->estate_m->get_user_id($property_id);
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                if($property_user_id == $this->session->userdata('id'))
                {
                    
                }
                else
                {
                    $error .= lang_check('Access danied, object not related use');
                }
            }

            if($this->config->item('app_type') == 'demo')
            {
                $error .= lang_check('Data editing disabled in demo');
            }

            
            if(file_exists(APPPATH.'controllers/admin/packages.php')){
                $user = $this->user_m->get($this->session->userdata('id'));
                if($user->package_id > 0 && $this->session->userdata('type') == 'AGENT')
                {
                    $package = $this->packages_m->get($user->package_id);
                    $listing_num = $this->packages_m->get_curr_listings(array('user_id'=>$user->id));
                    
                    if(config_item('enable_num_amenities_listing') == true)
                        $this->data['package_num_amenities_limit'] = $package->num_amenities_limit;
                    
                    if(isset($listing_num[$user->id]))
                    {
                        if($listing_num[$user->id] >= $package->num_listing_limit && !$property_id)
                        {
                            $error .= lang_check('Num listings max. reached for your package');
                        }
                        else if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
                        {
                            $error .= lang_check('Date for your package expired, please extend');
                        }
                    }
                } else if($this->session->userdata('type') != 'ADMIN') {
                    $error .= lang_check('Please activate one package, for add new listing');
                }
            }

            if(empty($error))
                if($this->form_validation->run() == TRUE)
                {
                    $insert_id = $this->estate_m->save($_POST, $property_id);
                    if(empty($insert_id))
                    {
                        $error .= lang_check('Option can not udpate, please contact with admin');
                    }
                }
                else
                {
                    $error .= validation_errors();
                }
        } else{
            $error .= lang_check('Missing property id');
        }  

        if(!empty($error))
            $this->data['message'] = $error;

        $this->data['errors_text'] = strip_tags($error);
        
        echo json_encode($this->data);
        exit();
    }

	public function featured_limitation_check($str)
	{
        if($str=='1')
        {
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                // Get exception property_id
                $exception_property_id = NULL;
                if(isset($this->data['estate']))
                    $exception_property_id = $this->data['estate']->id;
                
                $this->load->model('user_m');
                $user = $this->user_m->get($this->session->userdata('id'));
                if(empty($user->package_id)) return TRUE;
                
                if($this->packages_m->get_available_featured(NULL, $exception_property_id) == 0)
                {
                	$this->form_validation->set_message('featured_limitation_check', 
                                                        lang_check('Featured limitation reached in your package!'));
                	return FALSE;
                }
            }
        }
        
        return TRUE;
	}
}