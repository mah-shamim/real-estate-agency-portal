<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*
 * 
 *  Address from gps for export was commented. If need address, plese uncomment "Address from gps" Line 659/*
 * 
 */


class Facebook_feed {
    
    /* list all option, witch use on site */
    protected $option_list;
    
    /* array id of langs, witchuse on sile */
    protected $langs_id;
    
    /* default lang code */
    protected $default_lang_code;
    protected $default_lang_id;
    
    /* options script*/
    private $options = array(
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            'max_properties_import' => '20',
    );
    
    private $dom;
    public $ajax_limit = 5;

    public $params = array(
        'name'=> '10',
        'description'=> '8',
        'component_city'=> '7',
        'component_region'=> '87',
        'component_country'=> '5',
        'component_postal_code'=> '40',
        'neighborhood'=> '64',
        'air_conditioner'=> '22', //checkbox field
        'balcony'=> '11', //checkbox field
        'cable_tv'=> '23', //checkbox field
        'dishwasher'=> '25', //checkbox field
        'elevator'=> '27', //checkbox field
        'microwave'=> '31', //checkbox field
        'parking'=> '32', //checkbox field
        'swimming_pool'=> '33', //checkbox field
        'listing_type'=> '86',
        'num_baths'=> '19',
        'num_beds'=> '20',
        'num_units'=> '57',
        'heating_type'=> '80',
        'area_unit'=> '81',
        'laundry_type'=> '82',
        'parking_type'=> '84',
        'property_type'=> '2',
        'video'=> '12',
    );

    public function __construct() {
        
        /* add libraries and model */
        $this->CI = &get_instance();
        $this->CI->load->model('estate_m');
        $this->CI->load->model('option_m');
        $this->CI->load->model('file_m');
        $this->CI->load->model('language_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->model('conversions_m');
        $this->CI->load->library('uploadHandler', array('initialize'=>FALSE));
        $this->CI->load->library('ghelper');
        $this->CI->load->helper('text');

        
        /* class var */
        $this->langs_id=$this->CI->option_m->languages;
        
        $this->default_lang_code=$this->CI->language_m->get_default();
        $this->default_lang_id = $this->CI->language_m->get_default_id();
        $this->_count_key_skip = 0;
        $this->_count=0;
        $this->_count_skip=0;
        $this->output_mode = false;
    }
    
    
    /*
     * parser Dynamic fields from xml property node
     * 
     * @param object $xml_property node <Property> from xml 
     * 
     * return fields with index from db
     */
    
 
    function export($lang_code = 'en', $limit_properties=NULL, $offset_properties=0, $feed_type) {
        
        $options = $this->optionDetails();
           
        // Fetch settings
        $this->CI->load->model('settings_m');
        $settings = $this->CI->settings_m->get_fields();
        
        $lang_id = $this->CI->language_m->get_id($lang_code);
        $lang_name = $this->CI->language_m->get_name($lang_id);
        $this->CI->lang->load('frontend_template', $lang_name, FALSE, TRUE, FCPATH.'templates/'.$settings['template'].'/');
 
        
        // Property
        
        $where['language_id']  = $lang_id;
        $where['is_activated'] = 1;

        if($feed_type == 'sale'){
            $where['field_4'] = lang_check('Sale'); 
        }
        if($feed_type == 'rent'){
            $where['field_4'] = lang_check('Rent');
        }

                
        if(isset($settings['listing_expiry_days']))
        {
            if(is_numeric($settings['listing_expiry_days']) && $settings['listing_expiry_days'] > 0)
            {
                 $where['property.date_modified >']  = date("Y-m-d H:i:s" , time()-$settings['listing_expiry_days']*86400);
            }
        }

        $allProperties = $this->CI->estate_m->get_by($where, false, $limit_properties, 'property.id DESC', $offset_properties, array(), NULL, FALSE, TRUE);
        //$allProperties= $this->get_allProperies();
      

        $content_xml = '';
        $content_xml.= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $content_xml.= '<listings>'."\n";

        $content_xml.= '<title>'._ch($settings['websitetitle'], "Title").'</title>'."\n";
        $content_xml.= '<link rel="self" href="'.site_url().'"/>'."\n";

       
        foreach ($allProperties as $key => $value) {
            if(empty($value->json_object)) continue;
            /* special */
            $fields=json_decode($value->json_object);
            /* special */
            /* end special */

            /* skip if missing required */
            if(!_ch($value->id, false) || !_ch($fields->{'field_'.$this->params['name']}, false) || !_ch($fields->{'field_'.$this->params['listing_type']}, false) || !_ch($fields->{'field_'.$this->params['property_type']}, false)
                || !_ch($fields->{'field_'.$this->params['num_baths']}, false) || !_ch($fields->{'field_'.$this->params['component_region']}, false) || !_ch($fields->{'field_'.$this->params['num_beds']}, false)){
                continue;
            }
            
            $listing_xml = '<listing>'."\n";

            if(_ch($value->id, false))
                $listing_xml.= '<home_listing_id>'.$value->id.'</home_listing_id>'."\n";

            if(_ch($fields->{'field_'.$this->params['name']}, false))
                $listing_xml.= '<name>'._ch($fields->{'field_'.$this->params['name']}, "").'</name>'."\n";

            $listing_xml.= '<availability>for_'.strtolower(_ch($feed_type, "")).'</availability>'."\n";
           
            if(_ch($fields->{'field_'.$this->params['description']}, false))
                $listing_xml.= '<description>'.character_limiter(htmlentities(strip_tags($fields->{'field_'.$this->params['description']})), 480).'</description>'."\n";
            else if(_ch($fields->field_17, false)) {
                $listing_xml.= '<description>'.character_limiter(htmlentities(strip_tags($fields->field_17)), 480).'</description>'."\n";
            } else {
                continue;
            }

            $listing_xml.= '<address format="simple">'."\n";
            if(_ch($value->address, false))
                $listing_xml.= '<component  name="addr1">'._ch($value->address, "").'</component >'."\n";
            if(_ch($fields->{'field_'.$this->params['component_city']}, false))
                $listing_xml.= '<component  name="city">'._ch($fields->{'field_'.$this->params['component_city']}, "").'</component >'."\n";
            if(_ch($fields->{'field_'.$this->params['component_region']}, false))
                $listing_xml.= '<component  name="region">'._ch($fields->{'field_'.$this->params['component_region']}, "").'</component >'."\n";
            if(_ch($fields->{'field_'.$this->params['component_country']}, false))
                $listing_xml.= '<component  name="country">'._ch($fields->{'field_'.$this->params['component_country']}, "").'</component >'."\n";
            if(_ch($fields->{'field_'.$this->params['component_postal_code']}, false))
                $listing_xml.= '<component  name="postal_code">'._ch($fields->{'field_'.$this->params['component_postal_code']}, "").'</component >'."\n";
            $listing_xml.= '</address>'."\n";
            
            if(_ch($value->lat, false))
                $listing_xml.= '<latitude>'._ch($value->lat, "").'</latitude>'."\n";
            if(_ch($value->lng, false))
                $listing_xml.= '<longitude>'._ch($value->lng, "").'</longitude>'."\n";
            if(_ch($fields->{'field_'.$this->params['neighborhood']}, false))
                $listing_xml.= '<neighborhood>'.str_replace(' -', '',_ch($fields->{'field_'.$this->params['neighborhood']}, "")).'</neighborhood>'."\n";
            
            /* IMAGES */
            //images
            $images = json_decode($value->image_repository);
            $images_included = false;
            if(!empty($images)){
                foreach ($images as $key => $img) {
                    // image
                    $listing_xml.= '<image><url>'.base_url('files/'.$img).'</url></image>'."\n";
                    $images_included = true;
                }
            }
            if(!$images_included ) continue;
            /*  End IMAGES */
            
            /*
            $listing_xml.= '<unit_features>'._ch($fields->field_2, "").'</unit_features>'."\n";
            */
            if($fields->{'field_'.$this->params['air_conditioner']} == 'true')
                $listing_xml.= '<unit_features>air_conditioner</unit_features>'."\n";
            if($fields->{'field_'.$this->params['balcony']} == 'true')
                $listing_xml.= '<unit_features>balcony</unit_features>'."\n";
            if($fields->{'field_'.$this->params['cable_tv']} == 'true')
                $listing_xml.= '<unit_features>cable_tv</unit_features>'."\n";
            if($fields->{'field_'.$this->params['dishwasher']} == 'true')
                $listing_xml.= '<unit_features>dishwasher</unit_features>'."\n";
            if($fields->{'field_'.$this->params['elevator']} == 'true')
                $listing_xml.= '<unit_features>elevator</unit_features>'."\n";
            if($fields->{'field_'.$this->params['microwave']} == 'true')
                $listing_xml.= '<unit_features>microwave</unit_features>'."\n";
            if($fields->{'field_'.$this->params['parking']} == 'true')
                $listing_xml.= '<unit_features>parking</unit_features>'."\n";
            if($fields->{'field_'.$this->params['swimming_pool']} == 'true')
                $listing_xml.= '<unit_features>swimming_pool</unit_features>'."\n";

            if(_ch($fields->{'field_'.$this->params['listing_type']}, false))
                $listing_xml.= '<listing_type>'.strtolower(_ch($fields->{'field_'.$this->params['listing_type']}, "")).'</listing_type>'."\n";
            if(_ch($fields->{'field_'.$this->params['num_baths']}, false))
                $listing_xml.= '<num_baths>'._ch($fields->{'field_'.$this->params['num_baths']}, "").'</num_baths>'."\n";
            if(_ch($fields->{'field_'.$this->params['num_beds']}, false))
                $listing_xml.= '<num_beds>'._ch($fields->{'field_'.$this->params['num_beds']}, "").'</num_beds>'."\n";
            if(_ch($fields->{'field_'.$this->params['num_units']}, false))
                $listing_xml.= '<num_units>'._ch($fields->{'field_'.$this->params['num_units']}, "").'</num_units>'."\n";

            /* agent */
            if(_ch($value->agent_id, false))
                $listing_xml.= '<agent_id>'._ch($value->agent_id, "").'</agent_id>'."\n";
            if(_ch($value->name_surname, false))
                $listing_xml.= '<agent_name>'._ch($value->name_surname, "").'</agent_name>'."\n";
            if(_ch($value->phone, false))
                $listing_xml.= '<agent_phone>'.str_replace(array(' ','(',')'),'',_ch($value->phone, "")).'</agent_phone>'."\n";
            if(_ch($value->agent_facebook_id, false))
                $listing_xml.= '<agent_fb_page_id>'._ch($value->agent_facebook_id, "").'</agent_fb_page_id>'."\n";

            $listing_xml.= '<partner_verification>none</partner_verification>'."\n";
                
                /* special */
            if(_ch($fields->{'field_'.$this->params['heating_type']}, false))
                $listing_xml.= '<heating_type>'.strtolower(_ch($fields->{'field_'.$this->params['heating_type']}, "")).'</heating_type>'."\n";
            if(_ch($fields->{'field_'.$this->params['area_unit']}, false))
                $listing_xml.= '<area_unit>'.strtolower(_ch($fields->{'field_'.$this->params['area_unit']}, "")).'</area_unit>'."\n";
            if(_ch($fields->{'field_'.$this->params['laundry_type']}, false))
                $listing_xml.= '<laundry_type>'.strtolower(_ch($fields->{'field_'.$this->params['laundry_type']}, "")).'</laundry_type>'."\n";
            if(_ch($fields->{'field_'.$this->params['parking_type']}, false))
                $listing_xml.= '<parking_type>'.strtolower(_ch($fields->{'field_'.$this->params['parking_type']}, "")).'</parking_type>'."\n";
            if(_ch($fields->{'field_'.$this->params['year_built']}, false))
                $listing_xml.= '<year_built>'.strtolower(_ch($fields->{'field_'.$this->params['year_built']}, "")).'</year_built>'."\n";

            if(_ch($fields->{'field_'.$this->params['video']}, false) && filter_var($fields->{'field_'.$this->params['video']}, FILTER_VALIDATE_URL)) {
                $listing_xml.= '<video>'."\n";
                    $listing_xml.= '<url>'._ch($fields->{'field_'.$this->params['video']}, "").'</url >'."\n";
                    $listing_xml.= '<tag>preRecordedVideo</tag >'."\n";
                $listing_xml.= '</video>'."\n";
            }

            if(_ch($fields->field_2, false))
                $listing_xml.= '<property_type>'.strtolower(_ch($fields->field_2, "")).'</property_type>'."\n";   
                
            $_price = '';
            if($feed_type=="rent") 
                $_price=$fields->field_37;

            if($feed_type=="sale") 
                $_price=$fields->field_36;

            if(_ch($_price, false))
                $listing_xml.= '<price>'._ch($_price, "").' '.$currency.'</price>'."\n";
            else 
                continue;
                
                
                
            $listing_xml.= '<url>'.site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$value->id.'/'.$lang_code.'/'.url_title_cro($fields->field_10)).'</url>'."\n";
           
            $listing_xml.= '</listing>'."\n";

            $content_xml.= $listing_xml;
        }
        /* end properties */
        
        /* get the xml printed */
        $content_xml.= '</listings>'."\n";
        return $content_xml;
        
    }
    
    /*
     * get All properties + property_lang + language.code
     * return array
     */
    protected function get_allProperies ($lang_id =NULL) {
        if($lang_id ===NULL)
            $lang_id = $this->default_lang_id;
            
        $property=$this->CI->db->select('property.*, property_lang.*, language.code',FALSE);
        //$property=$this->CI->db->select('property.*, property_lang.*',FALSE);
        $this->CI->db->join('property_lang', 'property.id=property_lang.property_id');
        $this->CI->db->join('language', 'language.id=property_lang.language_id','left');
        $property=$this->CI->db->where('property_lang.language_id =', $lang_id);
        $this->CI->db->order_by('id', 'asc');
        $property=$property->get('property');
        
        return $property->result();
    }
    
    function optionDetails () {
        $options_names = $this->CI->option_m->get_lang(NULL, FALSE, $this->default_lang_id );
        
        $options = array();
        
        foreach($options_names as $key=>$row)
        {
            $options['options_name_'.$row->option_id] = $row->option;
            $options['options_suffix_'.$row->option_id] = $row->suffix;
            $options['options_prefix_'.$row->option_id] = $row->prefix;
            $options['options_values_'.$row->option_id] = $row->values;
            $options['options_type_'.$row->option_id] = $row->type;
        }
        
        return $options;
    }
    
        
}