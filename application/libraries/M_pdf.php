<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include(APPPATH.'libraries/mpdf/vendor/autoload.php');

class Sw_Mpdf extends \Mpdf\Mpdf
{
    
}
class M_pdf {

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        $this->prefix ='';
        $this->prefix_url ='';
        
        /* include */
        $this->CI = &get_instance();
        
        $this->CI->load->model('estate_m');
        $this->CI->load->model('option_m');
        $this->CI->load->model('file_m');
        $this->CI->load->model('language_m');
        $this->CI->load->model('settings_m');
        $this->CI->load->model('user_m');
        /* end  include */
        $this->prefix = FCPATH;
        $this->prefix_url = FCPATH;
    }

    /*
     * Put remote image 
     * 
     * @param $url_img string link with img
     * @param $x string/int position X
     * @param $y string/int position Y
     * @param $w string/int width of image
     * @param $h string/int height of image
     *      
     */

    public function set_image_by_link($url_img, $filename=NULL) {
        
        if($filename === NULL)
            $filename = time() . rand(000, 999) . '.jpg';
        else {
            $same = explode(', ', $filename);
            $rand_lat = round($same[0], 3);
            $rand_lan = round($same[1], 3);
            $filename = $rand_lat.'x'.$rand_lan;
            $filename = str_replace('.', '_', $filename);
            $filename .='.jpg';
        }
        if(!file_exists($this->prefix.'files/strict_cache/'.$filename)) {
            $f = $this->file_get_contents_curl($url_img);
            file_put_contents($this->prefix.'files/strict_cache/'.$filename, $f);
        }
        
        return $this->prefix_url.'/files/strict_cache/'.$filename;
    }
    
    
    /*
     * Function convert string to requested character encoding
     * 
     * @param string $lang code lang
     * @param string $str string for character encoding
     * retur encoded string;
     */
    public function charset_prepare($lang = 'en', $str) {
        $_str = ' ';
        /*if ($lang == 'hr') {
            //some conversion
            $_str = iconv(mb_detect_encoding($str), 'CP1250//TRANSLIT//IGNORE', html_entity_decode($str));
        } elseif ($lang == 'en') {
            $_str = iconv(mb_detect_encoding($str), 'CP1250//TRANSLIT//IGNORE', html_entity_decode($str));
        } elseif ($lang == 'pl') {
            $_str = iconv(mb_detect_encoding($str), 'CP1250//IGNORE', html_entity_decode($str));
        }else if ($lang == 'tr' || $lang == 'es') {
            //some conversion
            $_str = iconv(mb_detect_encoding($str), 'CP1254//TRANSLIT//IGNORE', html_entity_decode($str));
        } else {
            $_str = $str;
        }*/
        return $str;
    }

    public function generate_by_property($listing_id = '', $lang_code = 'en', $api_key = null, $lang_id = '') {


        /* data var */

        /* data var */
        $lang_code = strtolower($lang_code);
        /* var int id lang */
        $language_id = $this->CI->language_m->get_id($lang_code);

        /* var array website settings */
        $settings = $this->CI->settings_m->get_fields();

        if($lang_id) {
            $language_id = $lang_id;
        }
        $language_data = $this->CI->language_m->get($language_id);
        
        /* var array website settings */
        $settings = $this->CI->settings_m->get_fields();

        /* var array listing field */
        $_listing = '';

        /* var array listing options */
        $_listing = '';

        /* var array category options */
        $category = '';

        /* var array option names */
        $option_name = '';

        /* var array listing images */
        $images = '';

        /* end data */

        /* listing */
        $where_in = array($listing_id);
        $_listing = $_listing_compare = $this->CI->estate_m->get_by(array('is_activated' => 1, 'language_id' => $language_id), FALSE, NULL, 'id DESC', NULL, FALSE, $where_in);
        if (empty($_listing)) {
            exit(lang_check('Listing not found'));
        }

        $_listing = $_listing[0];
        $json_obj = json_decode($_listing->json_object);
        foreach ($_listing as $key => $value) {
            if (is_string($value))
                $_listing->$key = $this->charset_prepare($lang_code, $value);
        }

        /* fetch category */
        $options_name = $this->CI->option_m->get_fields($language_id);
        $category = array();  
        $option_name = array();
        foreach ($options_name as $key => $row) {
            $field = 'field_' . $row->option_id;
            $type = $row->type;
            //skip
            if ($type == 'UPLOAD')
                continue;
            if ($type == 'HTMLTABLE')
                continue;
            if ($type == 'PEDIGREE')
                continue;
            if ($type == 'TREE')
                continue;

            if (!isset($row->option))
                continue;
            $option_name['option_' . $row->option_id] = $this->charset_prepare($lang_code, $row->option);
            if (empty($row->option))
                continue;
            
            /* hide empty */
            if (!isset($json_obj->$field))
                continue;
            $option_name['option_' . $row->option_id] = $this->charset_prepare($lang_code, $row->option);
            if (empty($json_obj->$field))
                continue;

            // echo $json_obj->$field.PHP_EOL;
            $category['category_options_' . $row->parent_id][$row->option_id]['type'] =  $type;
            $category['category_options_' . $row->parent_id][$row->option_id]['option_value'] =  $json_obj->$field;
            $category['category_options_' . $row->parent_id][$row->option_id]['option_name'] =  $this->charset_prepare($lang_code, strip_tags($row->option));
            $category['category_options_' . $row->parent_id][$row->option_id]['option'] = 'option_' . $row->option_id;
            $category['category_options_' . $row->parent_id][$row->option_id]['option_suffix'] = $this->charset_prepare($lang_code, $row->suffix) ;
            $category['category_options_' . $row->parent_id][$row->option_id]['option_prefix'] = $this->charset_prepare($lang_code, $row->prefix) ;
        }
        
        /* end fetch category */

        $images = array();
        $_listing->image_repository = json_decode($_listing->image_repository);
        if(!empty($_listing->image_repository))
        foreach ($_listing->image_repository as $key => $value) {
            if (isset($_listing->image_filename)) {
                $images[] = $value;
            }
        }
        
        /* [START] Fetch logo URL */
        $settings['website_logo_url'] = FCPATH . '/templates/' . $settings["template"] . '/assets/img/logo.png';
        if (isset($settings['website_logo'])) {
            if (is_numeric($settings['website_logo'])) {
                $files_logo = $this->CI->file_m->get_by(array('repository_id' => $settings['website_logo']), TRUE);
                if (is_object($files_logo) && file_exists(FCPATH . 'files/thumbnail/' . $files_logo->filename)) {
                    $settings['website_logo_url'] = base_url('files/' . $files_logo->filename);
                }
            }
        }
        /* [END] Fetch logo URL */

        /* end listing */

        
        // START CREATE PDF

        $html = array();
        
        $html['title'] = _ch($json_obj->field_10);
        $html['address'] = _ch($_listing->address);
        $html['gps'] = _ch($_listing->gps);
        
        $html_images='';
        /* images */
        for ($i = 0; $i < sw_count($images) && $i < 3; $i++) {
            $html_images .='<div><a href="#"><img src="'._simg($images[$i], '230x150').'" alt=""></a></div>';
        }
        $html['images'] = $html_images;
        /* end images */
        
        // description
        $html['description'] = _ch($json_obj->field_17,'');
        
        /* Create Overview tanble */
        
        $table = '';
        if(isset($option_name['option_1'])){
            $html['option_name_1'] = _ch($option_name['option_1']);
            $table_category = array();
            if(isset($category['category_options_1'])) {
            foreach ($category['category_options_1'] as $key => $value) {
                $table_category[] = $option_name[$value['option']] . ': ' . $value['option_prefix'] . $value['option_value'] . $value['option_suffix'];
            }
            
            $c=0;
            $columns = 3;
            $arr=array_values($table_category);           
            $n_max=sw_count($arr);    
            $tr=ceil($n_max/$columns);  

            $table="<table border='0' class='overview'>";

            for($i=0;$i<$tr;$i++)
            {
                $table.="<tr>";
                 for($y=0;$y<$columns;$y++){
                  $even = "";
                  if($c %2 != 0)
                    $even = "class='even'";
                  
                  
                  $table.="<td ".$even.">";
                   if($c<$n_max)
                      $table.= $arr[$c]; 
                    else 
                      $table.='';

                  $table.="</td>";
                  $c++;
                }
                $table.="</tr>";
            }

            $table.="</table>";
            }
        }    
        
        $html['table'] = $table;
        
        /* Indoor amenities */
        $html_options_21 ='';
        if(isset($category['category_options_21'])&&!empty($category['category_options_21'])){
            foreach ($category['category_options_21'] as $key => $value) {
                $key = str_replace('option_','',$value['option']);
                if($value['type']=='CHECKBOX'){
                    if(true || isset($json_obj->{"field_".$key}) && $json_obj->{"field_".$key} ==1) {
                        $html_options_21.="<div><img class='d-table-img1' src='".base_url( '/admin-assets/img/checked-icon.jpg')."'/>"._ch($option_name[$value['option']])."</div>";
                    } else {
                        $html_options_21.="<div><img class='d-table-img2' src='".base_url( '/admin-assets/img/cross-remove-sign.png')."'/>"._ch($option_name[$value['option']])."</div>";
                    }
                } else {
                    if(!empty($value['option_value']))
                        $html_options_21.="<div>"._ch($option_name[$value['option']]).": ".$value['option_prefix'].character_limiter(strip_tags($value['option_value']), 25,'').$value['option_suffix']."</div>";
                }
            }
        }

        $html['section_options_21'] = '';
        if(!empty($html_options_21)) {
            $html['section_options_21'] = '<div class="section">
                                            <h2 class="title"><b>'._ch($option_name['option_21'], '').'</b></h2>
                                            <div class="d-table">'.$html_options_21.'</div>
                                        </div>';
        }

        /* end Indoor amenities */
        
        /* outdoor amenities */
        $html_options_52 ='';
        if(isset($category['category_options_52'])&&!empty($category['category_options_52'])){
            foreach ($category['category_options_52'] as $key => $value) {
                $key = str_replace('option_','',$value['option']);
                if($value['type']=='CHECKBOX'){
                    if(true || isset($json_obj->{"field_".$key}) && $json_obj->{"field_".$key} ==1) {
                        $html_options_52.="<div><img class='d-table-img1' src='".base_url( '/admin-assets/img/checked-icon.jpg')."'/>"._ch($option_name[$value['option']])."</div>";
                    } else {
                        $html_options_52.="<div><img class='d-table-img2' src='".base_url( '/admin-assets/img/cross-remove-sign.png')."'/>"._ch($option_name[$value['option']])."</div>";
                    }
                } else {
                    if(!empty($value['option_value']))
                        $html_options_52.="<div>"._ch($option_name[$value['option']]).": ".$value['option_prefix'].character_limiter(strip_tags($value['option_value']), 25,'').$value['option_suffix']."</div>";
                }
            }
        }
        
        $html['section_options_52'] = '';
        if(!empty($html_options_52)) {
            $html['section_options_52'] = '<div class="section">
                                            <h2 class="title"><b>'._ch($option_name['option_52'], '').'</b></h2>
                                            <div class="d-table">'.$html_options_52.'</div>
                                        </div>';
        }

        /* end outdoor amenities */

        /* Distance */
        $html_options_43 ='';
        if(isset($category['category_options_43'])&&!empty($category['category_options_43'])){
            foreach ($category['category_options_43'] as $key => $value) {
                $key = str_replace('option_','',$value['option']);
                if(!empty($value['option_value']))
                    $html_options_43.="<div>".$option_name[$value['option']].' '.$value['option_prefix'].$value['option_value'].$value['option_suffix'] ."</div>";
            }
        }

        /* end Distance */
                
        $html['section_options_43'] = '';
        if(!empty($html_options_43)) {
            $html['section_options_43'] = '<div class="section">
                                            <h2 class="title"><b>'._ch($option_name['option_43'], '').'</b></h2>
                                            <div class="d-table">'.$html_options_43.'</div>
                                        </div>';
        }

        // map
        $html['map_img'] ='';
        if (!empty($api_key) && !empty($_listing->gps)) {
            $src = $this->set_image_by_link('https://www.mapquestapi.com/staticmap/v5/map?key=' . $api_key . '&zoom=10&size=715,300&center=' . str_replace(' ', '', $_listing->gps) . '&imagetype=jpeg&locations=' . str_replace(' ', '', $_listing->gps) . '');

            $html['map_img'] = '<img src="'.$src.'" class="map-img" alt="">';
        }
        
        
        
        $agent_details ='';
        $agent = $this->CI->user_m->get_agent($listing_id);
        if($agent) {
            $agent_details .= '<h3 class="t_title"><b>'.lang_check('Agent Details').'</b></h3><br/>';
            $agent_details .= '<div class="t_items">'._ch($agent['mail']).' - '._ch($agent['name_surname']).'</div><br/>';
        }
        $html['agent_details'] = $agent_details;
        
        $websitetitle = $settings['websitetitle'];
        $contact_details ='';
        $contact_details .= '<h3 class="t_title"><b>'.$websitetitle.'</b></h3><br/>';
        if($settings['website_logo_url'])
            $contact_details .= '<div class="t_items"><img src="'.$settings['website_logo_url'].'"></img></div><br/>';
        $html['contact_details'] = $contact_details;
        
        
        $filename='listing_'.$listing_id.'_'.$lang_code.'.pdf';
        
        $output = file_get_contents(APPPATH.'libraries/mpdf/listing.html');
        foreach ($html as $key => $value) {
            $output = str_replace('{'.$key.'}', $value, $output);
        }
        
        // uncomment for use only utf-8
        
        /* uncomment if output return PDF error
         ob_clean();
        header('Content-type: application/pdf');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
         */
        
        if(in_array($lang_code, ['ar','kw','pa','ur','fa', 'tr']) !== FALSE ) {
            $mpdf = new Sw_Mpdf(['autoArabic' => true]);
        } else {
            $mpdf = new Sw_Mpdf(['mode' => 'utf-8', 'format' => 'A4','default_font' => 'XBRiyaz']);
        }
        
        if($language_data->is_rtl == 1) {
            $mpdf->SetDirectionality('rtl');
        }
        
        $mpdf->curlAllowUnsafeSslRequests = true;
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoLangToFont = true;
        $mpdf->autoArabic = true;
        $mpdf->WriteHTML($output);
        $mpdf->Output($filename, 'I');
        exit();
        
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

}
