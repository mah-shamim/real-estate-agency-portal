<?php

function add_meta_title ($string)
{
	$CI =& get_instance();
	$CI->data['meta_title'] = e($string) . ' - ' . $CI->data['meta_title'];
}


function get_numeric_val(&$string_val)
{
    $val_numeric = NULL;
    $value_n = trim($string_val);
    $value_n = str_replace("'", '', $value_n);
    $value_n = str_replace("�", '', $value_n);

    //If we have both , and .
    $comma_pos = strpos($string_val, ',');
    $dot_pos = strpos($string_val, '.');
    if($dot_pos !== FALSE && $dot_pos !== FALSE)
    {
        if($dot_pos > $comma_pos)
        {
            // Example 120,000.00
            $value_n = str_replace(",", '', $value_n);
            
        }
        else
        {
            // Example 120.000,00
            $value_n = str_replace(".", '', $value_n);
            $value_n = str_replace(",", '.', $value_n);
        }
    }
    
    // Example for 100,000
    $comma_pos = strpos($value_n, ',');
    if($comma_pos < strlen($value_n)-3)
    {
        $pre_val = substr($value_n,0,-3);
        $pos_val = substr($value_n,-3);
        
        // remove , if not decimal
        $pre_val = str_replace(",", '', $pre_val);
        
        $value_n = $pre_val.$pos_val;
    }

    // Example 100000,00
    $value_n = str_replace(",", '.', $value_n);

    if( is_numeric($value_n) && strlen($value_n)<=11 )
    {
        $val_numeric = floatval($value_n);
    }
    
    if(abs($val_numeric) > 2147483647) {
        $val_numeric = NULL;
    }
    
    return $val_numeric;
}

function parseTable($html)
{
    
    
  // Iterate each row
  preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $html, $matches);

  $table = array();
  foreach($matches[1] as $row_html)
  {
    preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
    $row = array();
    for($i=0; $i<sw_count($td_matches[1]); $i++)
    {
      $td = strip_tags(html_entity_decode($td_matches[1][$i]));
      $row[$i] = $td;
    }

    if(sw_count($row) > 0)
      $table[] = $row;
  }

  return $table;
}

function parse_tags($content)
{
/*
    $content = $nc_item['description'];
    $needle = '[';
    while (($lastPos = strpos($content, $needle, $lastPos))!== false) {
        $to = strpos($content, ']', $lastPos+1);
        $from = $lastPos+strlen($needle);
        $length = $to-$lastPos-strlen($needle);
        $code = substr($content, $from, $length);
    
        $search = substr($content, $lastPos, $to-$lastPos+1);
        $replace = '<iframe width="420" height="315" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen></iframe>';
        $content = str_replace($search, $replace, $content);
        
        $lastPos = $lastPos + strlen($needle);
    }
*/
}

function _get_custom_items()
{
    static $template_array = NULL;
    
    if($template_array !== NULL)
        return $template_array;
    
    $CI =& get_instance();
    $CI->load->model('customtemplates_m');
    $listing_selected = array();
    $listing_selected['theme'] = $CI->data['settings_template'];
    $listing_selected['type'] = 'RESULT_ITEM';

    $custom_template = $CI->customtemplates_m->get_by($listing_selected, TRUE);

    if(is_object($custom_template))
    {
        $results_template_data = json_decode($custom_template->widgets_order);
        if(is_object($results_template_data))
        {
            if(is_object($results_template_data->center))
            {
                $template_array = (array) $results_template_data->center;
                return $template_array;
            }
        }
    }
    
    $template_array = array();
    
    return array();
}

function _search_form_primary($form_id, $subfolder='')
{
    $CI =& get_instance();
    
    $CI->load->model('forms_m');  
    $form = $CI->forms_m->get($form_id);
    
    $CI->load->model('option_m');
    $CI->fields = $CI->option_m->get_field_list($CI->data['lang_id']);
    
    $template_name = $CI->data['settings']['template'];
    

    
    if(!is_object($form))
    {
        echo('<pre>FORM MISSING</pre>');
        return;
    }
    
    $fields_value_json_1 = $form->fields_order_primary;
    $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);

    $obj_widgets = json_decode($fields_value_json_1);

    if(is_object($obj_widgets->PRIMARY))
    foreach($obj_widgets->PRIMARY as $key=>$obj)
    {
        $title = '';
        $rel = $obj->type;
        $direction = 'NONE';
        if($obj->id != 'NONE')
        {
            if(isset($CI->fields[$obj->id]))
            {
                $title.='#'.$obj->id.', ';
                $title.=$CI->fields[$obj->id]->option;
                $rel = $CI->fields[$obj->id]->type.'_'.$obj->id;
                
                if($obj->direction != 'NONE')
                {
                    $direction = $obj->direction;
                    $title.=', '.$direction;
                    $rel.='_'.$obj->direction;
                }
            }
        }
        else
        {
            $title.=lang_check($obj->type);
        }
    
        if(!empty($title))
        {
            if($obj->type == 'C_PURPOSE' || $obj->type == 'SMART_SEARCH' || $obj->type == 'DATE_RANGE' || $obj->type == 'BREAKLINE' || $obj->type == 'QUICK_SEARCH'
                 || ($obj->type == 'C_PRICE_RANGE' && file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php' ))
                 || ($obj->type == 'C_YEAR_RANGE' && file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php' ))
                )
            {
                if(!empty($subfolder)&&file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php')){
                    echo $CI->load->view($template_name.'/form_fields/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                } elseif(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$obj->type.'.php')){
                    echo $CI->load->view($template_name.'/form_fields/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                }
                
            }
            else
            {
                
                if(!empty($subfolder)&&file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php')){
                     echo $CI->load->view($template_name.'/form_fields/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                }
                elseif(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$obj->type.'.php'))
                {
                    echo $CI->load->view($template_name.'/form_fields/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                }
                else
                {
                    echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                }
            }
        }
    }
}

function _search_form_secondary($form_id, $subfolder='')
{
    $CI =& get_instance();
    
    $CI->load->model('forms_m');  
    $form = $CI->forms_m->get($form_id);
    
    $CI->load->model('option_m');
    $CI->fields = $CI->option_m->get_field_list($CI->data['lang_id']);
    
    $template_name = $CI->data['settings']['template'];
    

    
    if(!is_object($form))
    {
        echo('<pre>FORM MISSING</pre>');
        return;
    }
    
    $fields_value_json_1 = $form->fields_order_primary;
    $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);

    $obj_widgets = json_decode($fields_value_json_1);

    if(is_object($obj_widgets->SECONDARY))
    foreach($obj_widgets->SECONDARY as $key=>$obj)
    {
        $title = '';
        $rel = $obj->type;
        $direction = 'NONE';
        if($obj->id != 'NONE')
        {
            if(isset($CI->fields[$obj->id]))
            {
                $title.='#'.$obj->id.', ';
                $title.=$CI->fields[$obj->id]->option;
                $rel = $CI->fields[$obj->id]->type.'_'.$obj->id;
                
                if($obj->direction != 'NONE')
                {
                    $direction = $obj->direction;
                    $title.=', '.$direction;
                    $rel.='_'.$obj->direction;
                }
            }
        }
        else
        {
            $title.=lang_check($obj->type);
        }
    
        if(!empty($title))
        {
            if($obj->type == 'C_PURPOSE' || $obj->type == 'SMART_SEARCH' || $obj->type == 'DATE_RANGE' || $obj->type == 'BREAKLINE'
                 || ($obj->type == 'C_PRICE_RANGE' && file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php' ))
                 || ($obj->type == 'C_YEAR_RANGE' && file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php' ))    
                )
            {
                if(!empty($subfolder)&&file_exists(FCPATH.'templates/'.$template_name.'/form_fields/secondary/'.$subfolder.$obj->type.'.php')){
                    echo $CI->load->view($template_name.'/form_fields/secondary/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                } elseif(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/secondary/'.$obj->type.'.php')){
                    echo $CI->load->view($template_name.'/form_fields/secondary/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                }
            }
            else
            {
                if(!empty($subfolder)&&file_exists(FCPATH.'templates/'.$template_name.'/form_fields/secondary/'.$subfolder.$obj->type.'.php')){
                     echo $CI->load->view($template_name.'/form_fields/secondary/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                }
                elseif(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/secondary/'.$obj->type.'.php'))
                {  
                    echo $CI->load->view($template_name.'/form_fields/secondary/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>!empty($CI->fields[$obj->id]) ? $CI->fields[$obj->id] : array())), true);
                }
                else
                {
                    echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                }
            }
        }
    }
}

function _search_form_secondary_hidden($form_id, $subfolder='')
{
    $CI =& get_instance();
    
    $CI->load->model('forms_m');  
    $form = $CI->forms_m->get($form_id);
    
    $CI->load->model('option_m');
    $CI->fields = $CI->option_m->get_field_list($CI->data['lang_id']);
    
    $template_name = $CI->data['settings']['template'];
    

    
    if(!is_object($form))
    {
        echo('<pre>FORM MISSING</pre>');
        return;
    }
    
    $fields_value_json_1 = $form->fields_order_primary;
    $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);

    $obj_widgets = json_decode($fields_value_json_1);

    if(is_object($obj_widgets->SECONDARY))
    foreach($obj_widgets->SECONDARY as $key=>$obj)
    {
        $title = '';
        $rel = $obj->type;
        $direction = 'NONE';
        if($obj->id != 'NONE')
        {
            if(isset($CI->fields[$obj->id]))
            {
                $title.='#'.$obj->id.', ';
                $title.=$CI->fields[$obj->id]->option;
                $rel = $CI->fields[$obj->id]->type.'_'.$obj->id;
                
                if($obj->direction != 'NONE')
                {
                    $direction = $obj->direction;
                    $title.=', '.$direction;
                    $rel.='_'.$obj->direction;
                }
            }
        }
        else
        {
            $title.=lang_check($obj->type);
        }
    
        if(!empty($title))
        {
            if($obj->type == 'TREE') continue;

            if($obj->type == 'C_PURPOSE' || $obj->type == 'SMART_SEARCH' || $obj->type == 'DATE_RANGE' || $obj->type == 'BREAKLINE'
                || ($obj->type == 'C_PRICE_RANGE' && file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php' ))
                || ($obj->type == 'C_YEAR_RANGE' && file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php' ))     
            )
            {
                if(!empty($subfolder)&&file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php')){
                    echo $CI->load->view($template_name.'/form_fields/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                } elseif(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$obj->type.'.php')){
                    echo $CI->load->view($template_name.'/form_fields/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                }
            }
            else
            {
                if(!empty($subfolder)&&file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$subfolder.$obj->type.'.php')){
                     echo $CI->load->view($template_name.'/form_fields/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                }
                elseif(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$obj->type.'.php'))
                {
                    echo $CI->load->view($template_name.'/form_fields/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                }
                else
                {
                    echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                }
            }
        }
    }
}


function price_format($value, $lang_id=NULL)
{
    $CI =& get_instance();
    
    $currency_format = 'EUR';
    if(isset($CI->data['settings']['currency_format']) && !empty($CI->data['settings']['currency_format']))
        $currency_format = $CI->data['settings']['currency_format'];
    
    $value = sw_money_format($value, $currency_format);

    if(substr($value, -3, 3) === ",00") {
        $value = substr($value, 0, -3);
    }

    if(substr($value, -3, 3) === ".00") {
        $value = substr($value, 0, -3);
    }

    return $value;
}

function show_price($value, $prefix, $suffix, $lang_id)
{
    $CI =& get_instance();


    if(isset($CI->data['set_currency']))
    {
        $CI->load->model('conversions_m');
        
        $conversions = $CI->conversions_m->get();

        $current = NULL;
        $wanted = NULL;

        foreach($conversions as $key=>$currency)
        {
            if($prefix == $currency->currency_code || ($prefix == $currency->currency_symbol && !empty($currency->currency_symbol)) ||
                $suffix == $currency->currency_code || ($suffix == $currency->currency_symbol && !empty($currency->currency_symbol)))
            {
                $current = $currency;
            }

            if($CI->data['set_currency'] == $currency->currency_code)
            {
                $wanted = $currency;
            }
        }

        if($current != $wanted && $current != NULL && $wanted != NULL)
        {
            if(!empty($prefix))
            {
                if(!empty($wanted->currency_symbol))
                    $prefix = $wanted->currency_symbol;
                else
                    $prefix = $wanted->currency_code;
            }
            else
            {
                if(!empty($wanted->currency_symbol))
                    $suffix = $wanted->currency_symbol;
                else
                    $suffix = $wanted->currency_code;
            }
            if(!empty($value)){
                $value = str_replace(',', '', $value);

                $value = intval(($value/$wanted->conversion_index) * $current->conversion_index);
            }
        }
    }

    return $prefix.price_format($value, $lang_id).$suffix;
}

function custom_number_format($value, $lang_id=NULL)
{
    $CI =& get_instance();
    
    $value = number_format($value, 2, '.', '');
    
    return $value;
}

function format_d($value)
{
    $CI =& get_instance();
    
    $value = date("m/d/y", strtotime($value));
    
    return $value;
}

function _jse($content)
{
    echo _js($content);
}

function _js($content)
{
    $output = $content;
    
    $output = str_replace("'", "\'", $output);
    $output = str_replace('"', '\"', $output);
    $output = str_replace(array("\n", "\r"), '', $output);
    
    return $output;
}

function print_var($var, $var_name)
{
    if(is_array($var))
    {
        foreach($var as $key=>$value)
        {
            echo '$'.$var_name."['$key']='$value';<br />";
        }
    }
}

function _empty($var)
{
    return empty($var);
}

function search_value($field_id, $custom_return = NULL)
{
    $CI =& get_instance();
    
    if(!empty($CI->g_post_option[$field_id]))
    {
        if($custom_return !== NULL)
            return $custom_return;
        
        return $CI->g_post_option[$field_id];
    }        
    
    return '';
}

function _ch(&$var, $empty = '-')
{
    if(empty($var) && $var!==0)
        return $empty;
        
    return $var;
}

function _che(&$var = NULL, $empty = '')
{
    if(empty($var) && $var!==0)
        echo $empty;
        
    echo $var;
}

function flashdata_message()
{
    $CI =& get_instance();
    
    if($CI->session->flashdata('message'))
    {
        echo $CI->session->flashdata('message');
    }
}

function _simg($filename, $dim = '640x480', $cut_enabled=false)
{
    $filename = basename($filename);
    $filename = str_replace('%20', ' ', $filename);
    $filename_encode = rawurlencode($filename);
    
    if(file_exists(FCPATH.'files/strict_cache/'.$dim.$filename))
    {
        return base_url('files/strict_cache/'.$dim.$filename_encode);
    }
    
    if(config_item('strict_image_speed_enable')!==FALSE && !sw_is_safari() && function_exists('imagewebp')) {
        if(file_exists(FCPATH.'files/strict_cache/'.$dim.str_replace(array('.jpeg','.jpg','.png','.gif'), '.webp', $filename)))
        {
            return base_url('files/strict_cache/'.$dim.str_replace(array('.jpeg','.jpg','.png','.gif'), '.webp', $filename_encode));
        }
    }
    
    if(!file_exists('files/'.$filename))
    {
        $filename_encode = basename('admin-assets/img/no_image.jpg');
    }
    
    if($cut_enabled === true)
    {
        if(config_item('strict_image_speed_enable')!==FALSE && !sw_is_safari() && function_exists('imagewebp')) {
            return base_url("strict_image_speed.php?d=$dim&f=$filename_encode&cut=true");
        } else {
            return base_url("strict_image.php?d=$dim&f=$filename_encode&cut=true");
        }
    }
    
    if(config_item('strict_image_speed_enable')!==FALSE && !sw_is_safari() && function_exists('imagewebp')) {
        return base_url("strict_image_speed.php?d=$dim&f=$filename_encode");
    } else {
        return base_url("strict_image.php?d=$dim&f=$filename_encode");
    }
}

function sw_is_safari(){
    $user_agent = $_SERVER['HTTP_USER_AGENT']; 
    if(stripos( $user_agent, 'Safari') !== FALSE && stripos( $user_agent, 'Chrome') == FALSE) {
       return true;
    }
    
    return false;
}

function _generate_popup($estate_data, $json_output = false)
{
    $CI =& get_instance();
    
    //Get template settings
    $template_name = $CI->data['settings']['template'];
    
    //Load view
    if(file_exists(FCPATH.'templates/'.$template_name.'/widgets/map_popup.php'))
    {
        $output = $CI->load->view($template_name.'/widgets/map_popup.php', $estate_data, true);
        
        if($json_output)
        {
            $output = str_replace("'", "\'", $output);
            $output = str_replace('"', '\"', $output);
            $output = str_replace(array("\n", "\r"), '', $output);
        }
        
        return $output;
    }
    else
    {
        return NULL;
    }
}

function _generate_results_item($estate_data, $json_output = false)
{
    $CI =& get_instance();
    
    //Get template settings
    $template_name = $CI->data['settings']['template'];
    
    //Load view
    if(file_exists(FCPATH.'templates/'.$template_name.'/widgets/results_item.php'))
    {
        $output = $CI->load->view($template_name.'/widgets/results_item.php', $estate_data, true);
        
        if($json_output)
        {
            $output = str_replace('"', '\"', $output);
            $output = str_replace(array("\n", "\r"), '', $output);
            return $output;
        }
        
        echo $output;
    }
    else
    {
        echo 'NOT FOUND: results_item.php';
    }
}


/**
 * _widget()
 * 
 * echo widget if exists or nothing
 * 
 * @param mixed $filename
 * @return
 */
function _widget($filename)
{
    $CI =& get_instance();
    
    if(file_exists(FCPATH.'templates/'.$CI->data['settings_template'].'/widgets/'.$filename.'.php'))
    {
        if(config_item('pseudo_varialbes_disabled') === TRUE)
        {
            $output = $CI->load->view($CI->data['settings_template'].'/widgets/'.$filename.'.php', $CI->data, TRUE);
        }
        else
        {
            $output = $CI->parser->parse($CI->data['settings_template'].'/widgets/'.$filename.'.php', $CI->data, TRUE);
        }
        
        if(config_item('widget_edit_button_enabled') !== FALSE) {
            // Check login and if ADMIN show edit widget
            $CI->load->library('session');
            $CI->load->model('user_m');
            if($CI->user_m->loggedin() == TRUE && $CI->session->userdata('type')=='ADMIN') {
                preg_match('#<[A-Za-z0-9 _-]*class="[A-Za-z0-9 _-]*widget_edit_enabled[A-Za-z0-9 _-]*"\s*[^>]*>#Usi', $output, $m);
                if($m && isset($m[0])){
                    $control='<div class="widget_controls_panel" data-widgetfilename="'.$filename.'">
                                   <a href="'.site_url('admin/templatefiles/edit/'.$filename.'.php/widgets').'" target="_blank" class="btn btn-edit"><i class="ion-edit"></i></a>
                               </div>';
                    $t1 = str_replace('>', '>'.$control, $m[0]);
                    $output = str_replace($m[0], $t1, $output);  
                }
            }
        }
        
        echo $output;
    }
}

/**
 * _widget()
 * 
 * echo widget if exists or nothing
 * 
 * @param mixed $filename
 * @return
 */
function _subtemplate($subfolder = 'headers', $selected_header='empty')
{
    $CI =& get_instance();
    $filename = $selected_header;

    if(file_exists(FCPATH.'templates/'.$CI->data['settings_template'].'/'.$subfolder.'/'.$filename.'.php'))
    {
        $output = $CI->load->view($CI->data['settings_template'].'/'.$subfolder.'/'.$filename.'.php', $CI->data, TRUE);
        echo $output;
    }
}

function btn_view($uri)
{
	return anchor($uri, '<i class=" icon-search"></i> '.lang('view'), array('class'=>'btn btn-primary'));
}

function btn_view_curr($uri)
{
	return anchor($uri, '<i class=" icon-search"></i> '.lang('view_curr'), array('class'=>'btn btn-primary'));
}

function btn_view_sent($uri)
{
	return anchor($uri, '<i class=" icon-th-list"></i> '.lang('view_sent'), array('class'=>'btn btn btn-info'));
}

function btn_edit($uri)
{
	return anchor($uri, '<i class="icon-edit"></i> '.lang('edit'), array('class'=>'btn btn-primary'));
}

function btn_edit_invoice($uri)
{
	return anchor($uri, '<i class="icon-edit"></i> '.lang('edit_invoice'), array('class'=>'btn btn-primary'));
}

function btn_delete($uri)
{
	return anchor($uri, '<i class="icon-remove"></i> '.lang('delete'), array('onclick' => 'return confirm(\''.lang('Are you sure?').'\')', 'class'=>'btn btn-danger'));
}

function btn_delete_debit($uri)
{
	return anchor($uri, '<i class="icon-remove"></i> '.lang('delete_debit'), array('onclick' => 'return confirm(\''.lang('delete_debit?').'\')', 'class'=>'btn btn-danger'));
}

if ( ! function_exists('get_file_extension'))
{
    function get_file_extension($filepath)
    {
        return substr($filepath, strrpos($filepath, '.')+1);
    }
}

if ( ! function_exists('character_hard_limiter'))
{
    function character_hard_limiter($string, $max_len)
    {
        if(strlen($string)>$max_len)
        {
            return substr($string, 0, $max_len-3).'...';
        }
        
        return $string;
    }
}

function article_link($article){
	return 'article/' . intval($article->id) . '/' . e($article->slug);
}

function article_links($articles){
	$string = '<ul>';
	foreach ($articles as $article) {
		$url = article_link($article);
		$string .= '<li>';
		$string .= '<h3>' . anchor($url, e($article->title)) .  ' &rsaquo;</h3>';
		$string .= '<p class="pubdate">' . e($article->pubdate) . '</p>';
		$string .= '</li>';
	}
	$string .= '</ul>';
	return $string;
}

function get_excerpt($article, $numwords = 50){
	$string = '';
	$url = article_link($article);
	$string .= '<h2>' . anchor($url, e($article->title)) .  '</h2>';
	$string .= '<p class="pubdate">' . e($article->pubdate) . '</p>';
	$string .= '<p>' . e(limit_to_numwords(strip_tags($article->body), $numwords)) . '</p>';
	$string .= '<p>' . anchor($url, 'Read more &rsaquo;', array('title' => e($article->title))) . '</p>';
	return $string;
}

function limit_to_numwords($string, $numwords){
	$excerpt = explode(' ', $string, $numwords + 1);
	if (sw_count($excerpt) >= $numwords) {
		array_pop($excerpt);
	}
	$excerpt = implode(' ', $excerpt);
	return $excerpt;
}

function e($string){
	return htmlentities($string);
}

function slug_url($uri, $model_name='', $is_menu = false)
{
    $slug_extension = '.htm';
    //$slug_extension = '';
    
    if(config_db_item('slug_enabled') === FALSE) return site_url($uri);
    $CI =& get_instance();
    $uri_exp = explode('/', $uri);
    $base_url = base_url();
    
    $CI->load->model('slug_m');
    $CI->load->model('page_m');
    
    $def_lang_code = $CI->language_m->get_default();
    
    if($model_name == 'page_m' && sw_count($uri_exp) > 1)
    {
        $model_lang_code = $uri_exp[0];
        $model_id = $uri_exp[1];
        
        $first_page = $CI->page_m->get_first();
        $page_id = NULL;
        if(isset($first_page->id) && $first_page->id ==$model_id)
        {
            if(isset($CI->data['lang_code']) && $CI->data['lang_code'] == $CI->language_m->get_default())
                return $base_url;
            
            return $base_url.'index.php/'.$model_lang_code;
        }
        
        $slug_data = $CI->slug_m->get_slug($model_name.'_'.$model_id.'_'.$model_lang_code);
        
        if($slug_data !== FALSE)
        {   
            //remove index.php/xx from base url
            if($is_menu)
            $base_url = substr($base_url, 0, strrpos($base_url, 'index.php'));

            return $base_url.$slug_data->slug.$slug_extension;
        }
            
    }
    else
    {
        // try autodetect $model_name
        $listing_uri = config_item('listing_uri');
        if(empty($listing_uri))$listing_uri = 'property';

        if($uri_exp[0] == $listing_uri)
        {
            //detected, property url
            $model_name = 'estate_m';
            $model_lang_code = $uri_exp[2];
            $model_id = $uri_exp[1];

            $slug_data = $CI->slug_m->get_slug($model_name.'_'.$model_id.'_'.$model_lang_code);
            
            if($slug_data !== FALSE)
            {   
                //remove index.php/xx from base url
                if($is_menu)
                $base_url = substr($base_url, 0, strrpos($base_url, 'index.php'));
    
                return $base_url.$slug_data->slug.$slug_extension;
            }
        }
        else if($uri_exp[0] == 'treefield')
        {
            //detected, property url
            $model_name = 'treefield_m';
            $model_lang_code = $uri_exp[1];
            $model_id = $uri_exp[2];

            $slug_data = $CI->slug_m->get_slug($model_name.'_'.$model_id.'_'.$model_lang_code);

            if($slug_data !== FALSE)
            {   
                //remove index.php/xx from base url
                if($is_menu)
                $base_url = substr($base_url, 0, strrpos($base_url, 'index.php'));
    
                return $base_url.$slug_data->slug.$slug_extension;
            }
        }
        else if($uri_exp[0] == 'profile')
        {
            if(isset($uri_exp[2]))
                $model_lang_code = $uri_exp[2];
            else 
                $model_lang_code = $def_lang_code;
                
            $model_id = $uri_exp[1];
            
            // fetch user username
            $user = $CI->user_m->get($model_id);
            
            if(isset($user->username))
            {
                $slug_data = $user->username;
            }
            
            if($def_lang_code != $model_lang_code)
            {
                $slug_data.='.'.$model_lang_code;
            }

            if(!empty($slug_data))
                return $base_url.$slug_data.$slug_extension;
        } else if(!empty($uri_exp) && isset($uri_exp[1]) && isset($uri_exp[0])) {
            
            $slug_data = FALSE;
            foreach ($CI->slug_m->cache_slugs_lang_id as $k => $v) {
                if($v->model_id== $uri_exp[1] || $v->model_lang_code== $uri_exp[0]) {
                    $slug_data = $CI->slug_m->get_slug($v->model_name.'_'.$uri_exp[1].'_'.$uri_exp[0]);
                }
            }
                
            if($slug_data !== FALSE)
            {   
                //remove index.php/xx from base url
                if($is_menu)
                $base_url = substr($base_url, 0, strrpos($base_url, 'index.php'));
    
                return $base_url.$slug_data->slug.$slug_extension;
            }
        }
    }
    
    if(strpos($uri, '.htm') !== FALSE){
        return str_replace('/index.php','',site_url($uri));
    } else {
        return site_url($uri);
    }
}

function get_menu ($array, $child = FALSE, $lang_code='en')
{
	$CI =& get_instance();
    
    if($CI->config->item('custom_menu') == 'saeedo')
    {
        return get_menu_saeedo($array, $child = FALSE, $lang_code);
    }
    
    if(isset($CI->data['settings_template']))
    {
        if($CI->data['settings_template'] == 'saeedo')
            return get_menu_saeedo($array, $child = FALSE, $lang_code);
    }

	$str = '';
    
    $first_page = $CI->page_m->get_first();
    $default_lang_code = $CI->language_m ->get_default();
    
    $is_logged_user = ($CI->user_m->loggedin() == TRUE);
	
	if (sw_count($array)) {
		$str .= $child == FALSE ? '<ul class="nav navbar-nav nav-collapse collapse navbar-main" id="main-top-menu">' . PHP_EOL : '<ul class="dropdown-menu">' . PHP_EOL;
		$position = 0;
		foreach ($array as $key=>$item) {
		    if($item['is_visible'] == '0')
                continue;
          
			$position++;
            
            $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;
            
            if($position == 1 && $child == FALSE){
                $item['navigation_title'] = '<img src="assets/img/home-icon.png" alt="'.$item['navigation_title'].'" />';
                
                if($CI->uri->segment(2) == '')
                    $active = TRUE;
            }
            
            if(empty($item['is_private']) || $item['is_private'] == '1' && $is_logged_user)
			if (isset($item['children']) && sw_count($item['children'])) {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                
                $target = '';
                
                if(substr($item['keywords'],0,4) == 'http')
                {
                    $href = $item['keywords'];
                    if(substr($item['keywords'],0,10) != substr(site_url(),0,10))
                    {
                        $target=' target="_blank"';
                    }
                }
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="menuparent dropdown active">' : '<li class="menuparent dropdown">';
				$str .= '<a class="dropdown-toggle" data-toggle="dropdown" href="' . $href . '" '.$target.'>' . $item['navigation_title'];
				$str .= '<b class="caret"></b></a>' . PHP_EOL;
				$str .= get_menu($item['children'], TRUE, $lang_code);
                
			}
			else {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                
                if(is_object($first_page))
                    if($first_page->id == $item['id'] && $default_lang_code == $lang_code)
                    {
                        $href = base_url();
                    }
                    else if($first_page->id == $item['id'] && $default_lang_code != $lang_code)
                    {
                        $href = site_url($lang_code);
                    }

                $target = '';
                
                if(substr($item['keywords'],0,4) == 'http')
                {
                    $href = $item['keywords'];
                    if(substr($item['keywords'],0,10) != substr(site_url(),0,10))
                    {
                        $target=' target="_blank"';
                    }
                }
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="active">' : '<li>';
				$str .= '<a href="' . $href . '" '.$target.'>' . $item['navigation_title'] . '</a>';
                
			}
			$str .= '</li>' . PHP_EOL;
		}
		
		$str .= '</ul>' . PHP_EOL;
	}
	
	return $str;
}

function get_menu_saeedo ($array, $child = FALSE, $lang_code='en')
{
	$CI =& get_instance();
	$str = '';
    $is_logged_user = ($CI->user_m->loggedin() == TRUE);
	
	if (sw_count($array)) {
		$str .= $child == FALSE ? '<ul id="menu" class="menu nav navbar-nav">' . PHP_EOL : '<ul>' . PHP_EOL;
		$position = 0;
		foreach ($array as $key=>$item) {
			$position++;
            
            $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;
            
            if($position == 1 && $child == FALSE){
                $item['navigation_title'] = '<i class="fa fa-home"></i> '.$item['navigation_title'].'';
                
                if($CI->uri->segment(2) == '')
                    $active = TRUE;
            }
            else if($child == FALSE)
            {
                $item['navigation_title'] = '<i class="fa "></i> '.$item['navigation_title'].''; 
            }
            
            if($item['is_visible'] == '1')
            if(empty($item['is_private']) || $item['is_private'] == '1' && $is_logged_user)
			if (isset($item['children']) && sw_count($item['children'])) {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li>' : '<li>';
				$str .= '<a href="' . $href . '">' . $item['navigation_title'];
				$str .= '</a>' . PHP_EOL;
				$str .= get_menu_saeedo($item['children'], TRUE, $lang_code);
                
			}
			else {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li>' : '<li>';
				$str .= '<a href="' . $href . '">' . $item['navigation_title'] . '</a>';
                
			}
			$str .= '</li>' . PHP_EOL;
		}
		
		$str .= '</ul>' . PHP_EOL;
	}
	
	return $str;
}

function get_menu_realia ($array, $child = FALSE, $lang_code='en')
{
	$CI =& get_instance();
	$str = '';
    
    $is_logged_user = ($CI->user_m->loggedin() == TRUE);
	
	if (sw_count($array)) {
		$str .= $child == FALSE ? '<ul class="nav">' . PHP_EOL : '<ul>' . PHP_EOL;
		$position = 0;
		foreach ($array as $key=>$item) {
			$position++;
            
            $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;
            
            if($position == 1 && $child == FALSE){
                //$item['navigation_title'] = '<img src="assets/img/home-icon.png" alt="'.$item['navigation_title'].'" />';
                
                if($CI->uri->segment(2) == '')
                    $active = TRUE;
            }
            
            if($item['is_visible'] == '1')
            if(empty($item['is_private']) || $item['is_private'] == '1' && $is_logged_user)
			if (isset($item['children']) && sw_count($item['children'])) {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="menuparent">' : '<li class="menuparent">';
				$str .= '<span class="menuparent nolink">' . $item['navigation_title'];
				$str .= '</span>' . PHP_EOL;
				$str .= get_menu_realia($item['children'], TRUE, $lang_code);
                
			}
			else {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="active">' : '<li>';
				$str .= '<a href="' . $href . '">' . $item['navigation_title'] . '</a>';
                
			}
			$str .= '</li>' . PHP_EOL;
		}
		
		$str .= '</ul>' . PHP_EOL;
	}
	
	return $str;
}

function get_lang_menu ($array, $lang_code, $extra_ul_attributes = '')
{
    $CI =& get_instance();
    $property_data = NULL;
    
    if(sw_count($array) == 1)
        return '';
    
    if(empty($CI->data['listing_uri']))
    {
        $listing_uri = 'property';
    }
    else
    {
        $listing_uri = $CI->data['listing_uri'];
    }
    
    $default_base_url = config_item('base_url');
    $default_index_page = config_item('index_page');
    $default_lang_code = $CI->language_m ->get_default();
    $first_page = $CI->page_m->get_first();

    $str = '<ul '.$extra_ul_attributes.'>';
    foreach ($array as $item) {
        $active = $lang_code == $item['code'] ? TRUE : FALSE;
        
        $custom_domain_enabled=false;
        if(config_db_item('multi_domains_enabled') === TRUE)
        {
            if(!empty($item['domain']) && substr_count($item['domain'], 'http') > 0)
            {
                $domain = $item['domain'];
                
                if(substr_count($domain, 'index.php/') > 0)
                {
                    $domain = substr($domain, 0, strpos($domain, 'index.php/'));
                    $CI->config->set_item('base_url', $domain);
                    $CI->config->set_item('index_page', '');
                }
                else
                {
                    $custom_domain_enabled=true;
                    $CI->config->set_item('base_url', $item['domain']);
                }
            }
            else
            {
                $CI->config->set_item('base_url', $default_base_url);
            }
        }
        
        $flag_icon = '';
        
        if(isset($CI->data['settings_template']))
        {
            $template_name = $CI->data['settings_template'];
            if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/flags/'.$item['code'].'.png'))
            {
                $flag_icon = '&nbsp; <img src="'.'assets/img/flags/'.$item['code'].'.png" alt="" />';
            }
        }

        if($CI->uri->segment(1) == $listing_uri)
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url($listing_uri.'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4), '', true), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $property_title = '';
                if($property_data === NULL)
                    $property_data = $CI->estate_m->get_dynamic($CI->uri->segment(2));
                
                if(isset($property_data->{'option10_'.$item['id']}))
                    $property_title = $property_data->{'option10_'.$item['id']};
                
                $str.='<li class="'.$item['code'].'" >'.anchor(slug_url($listing_uri.'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.url_title_cro($property_title), '', true), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'showroom')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url('showroom/'.$CI->uri->segment(2).'/'.$item['code']), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url('showroom/'.$CI->uri->segment(2).'/'.$item['code']), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'profile')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url('profile/'.$CI->uri->segment(2).'/'.$item['code'], '', true), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url('profile/'.$CI->uri->segment(2).'/'.$item['code'], '', true), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'propertycompare')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url('propertycompare/'.$CI->uri->segment(2).'/'.$item['code'], '', true), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url('propertycompare/'.$CI->uri->segment(2).'/'.$item['code'], '', true), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'treefield')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url('treefield/'.$item['code'].'/'.$CI->uri->segment(3).'/'.$CI->uri->segment(4), '', true), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url('treefield/'.$item['code'].'/'.$CI->uri->segment(3).'/'.$CI->uri->segment(4), '', true), $item['code'].$flag_icon).'</li>';
            }
        }
        else if(is_numeric($CI->uri->segment(2)))
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url($item['code'].'/'.$CI->uri->segment(2), 'page_m', true), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url($item['code'].'/'.$CI->uri->segment(2), 'page_m', true), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(2) != '')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4)), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4)), $item['code'].$flag_icon).'</li>';
            }
        }
        else
        {
            $url_lang_code = $item['code'];
            if($custom_domain_enabled)
            {
                $url_lang_code='';
            }
            else if($default_lang_code == $item['code'])
            {
                $url_lang_code = base_url();
            }
            
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor($url_lang_code, $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor($url_lang_code, $item['code'].$flag_icon).'</li>';
            }
        }
    }
    $str.='</ul>';
    
    $CI->config->set_item('base_url', $default_base_url);
    $CI->config->set_item('index_page', $default_index_page);
    
    return $str;
}

function treefield_sitemap($field_id, $lang_id, $view='text')
{
    if(!file_exists(APPPATH.'controllers/admin/treefield.php'))
    {
        return;
    }
    
    $CI =& get_instance();
    $CI->load->model('treefield_m');
    
    $lang_code = 'en';
    if(empty($CI->lang_code))
    {
        $lang_code = $CI->language_m->get_code($lang_id);
    }
    else
    {
        $lang_code = $CI->lang_code;
    }

    if($view == 'text')
    {
        $tree_listings = $CI->treefield_m->get_table_tree($lang_id, $field_id);
        
        foreach($tree_listings as $listing_item)
        {
            if(!empty($listing_item->template) && !empty($listing_item->body))
            {
                echo "<br />$listing_item->visual<a class='link_defined' href='".
                slug_url('treefield/'.$lang_code.'/'.$listing_item->id.'/'.url_title_cro($listing_item->value), 'treefield_m').
                "'>$listing_item->value</a>";
            }
            else
            {
                echo "<br />$listing_item->visual$listing_item->value";
            }
        }
    }
    else
    {
        $tree_listings = $CI->treefield_m->get_table_tree($lang_id, $field_id, NULL, false);
        
        echo_by_parent($tree_listings, 0, $field_id, $lang_code);
    }
}

function website_sitemap($lang_id, $view='text')
{
    
    $CI =& get_instance();
    $CI->load->model('page_m');
    
    $lang_code = 'en';
    if(empty($CI->lang_code))
    {
        $lang_code = $CI->language_m->get_code($lang_id);
    }
    else
    {
        $lang_code = $CI->lang_code;
    }

    if($view == 'text')
    {
       $tree_listings = $CI->page_m->get_nested($lang_id);
        foreach($tree_listings as $item)
        {
            if($item['is_visible']==1 )
            {
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title']), 'page_m');
                echo '<br /><a class="link_defined" href="'.$href.'">' . $item['title'] .'</a>';
            
                if (isset($item['children']) && sw_count($item['children'])) {
                    foreach($item['children'] as $_item)
                    {
                        if($_item['is_visible']==1 )
                        { 
                            $href = slug_url($lang_code.'/'.$_item['id'].'/'.url_title_cro($_item['navigation_title']), 'page_m');
                            echo '<br /><a class="link_defined" href="'.$href.'">' . $_item['title'] .'</a>';
                        }
                    }
                }
            }
        }
    }
    else
    {
     $tree_listings = $CI->page_m->get_nested($lang_id);
     echo  get_menu_tree($tree_listings, $lang_code);
    }
}

function echo_by_parent($tree_listings, $id, $field_id, $lang_code)
{
    if(!isset($tree_listings[$id])) return;
    echo '<ul>';
    foreach($tree_listings[$id] as $key=>$listing_item)
    {
        $print_link = "$listing_item->value";
        if(!empty($listing_item->template) && !empty($listing_item->body))
        {
            $print_link = "<a class='link_defined' href='".
                          slug_url('treefield/'.$lang_code.'/'.$listing_item->id.'/'.url_title_cro($listing_item->value), 'treefield_m').
                          "'>$listing_item->value</a>";
        }
        
        echo '<li>';
        echo $print_link;
        echo_by_parent($tree_listings, $listing_item->id, $field_id, $lang_code);
        echo '</li>';
    }
    echo '</ul>';
}

function get_menu_tree($array,$lang_code='en', $child = FALSE)
{
	$str = '';
    	if (sw_count($array)) {
    		$str .= $child == FALSE ? '<ul>' : '<ul>';
    		
    		foreach ($array as $item) {  
                    if($item['is_visible']!=1) continue;
                    
                      $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
    			$str .= '<li>';
    			$str .= '<a class="link_defined" href="'.$href.'">' . $item['title'] .'</a>';
    			
                        // Do we have any children?
    			if (isset($item['children']) && sw_count($item['children'])) {
    				$str .= get_menu_tree($item['children'],$lang_code, TRUE);
    			}
    			$str .= '</li>' . PHP_EOL;
    		}
    		$str .= '</ul>' . PHP_EOL;
    	}
    	
    	return $str;
}


function get_admin_menu($array)
{
    $CI =& get_instance();
    
    $str = '<ul class="nav">';
    foreach ($array as $item) {
        $active = $CI->uri->segment(1).'/'.$CI->uri->segment(2) == $item['uri'] ? TRUE : FALSE;
        
        if($active)
        {
            $str.='<li class="active">'.anchor($item['uri'], $item['title']).'</li>';
        }
        else
        {
            $str.='<li>'.anchor($item['uri'], $item['title']).'</li>';
        }
    }
    $str.='</ul>';
    
    return $str;
}

/**
* Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
* @author Joost van Veen
* @version 1.0
*/
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        
        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
        
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}
 
 
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE)
    {
        dump ($var, $label, $echo);
        exit;
    }
}


if ( ! function_exists('get_ol'))
{
    function get_ol ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (sw_count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="option_sortable">' : '<ol>';
    		
    		foreach ($array as $item) {
    		  
                if($child == FALSE){
                    $item_children = null;
                    if(isset($item['children']))$item_children = $item['children'];
                    $item = $item['parent'];
                    if(isset($item_children))$item['children'] = $item_children;
                }
              
                $visible = '';
                if($item['visible'] == 1)
                    $visible = '<i class="icon-th-large"></i>';
                
                $locked='';
                if($item['is_hardlocked'])
                    $locked = '<i class="icon-lock" style="color:red;"></i>';
                else if($item['is_locked'] == 1)
                    $locked = '<i class="icon-lock"></i>';
                    
                $frontend='';
                if($item['is_frontend'] == 0)
                    $frontend = '<i class="icon-eye-close"></i>';
                    
                $required='';
                if($item['is_required'] == 1)
                    $required = '*';
                
                $icon = '';
                $CI =& get_instance();
                $template_name = $CI->data['settings']['template'];
                if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png'))
                {
                    $icon = '<img class="results-icon" src="'.base_url('templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png').'" alt="'.$item['option'].'"/>&nbsp;&nbsp;';
                }
                
    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" >#'.$item['id'].'&nbsp;&nbsp;&nbsp;'.$icon.$required.$item['option'].'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-'.$item['color'].'">'.$item['type'].'</span>&nbsp;&nbsp;'.$visible.'&nbsp;&nbsp;'.$locked.'&nbsp;&nbsp;'.$frontend.'<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/estate/edit_option/'.$item['id']).'"><i class="icon-edit"></i></a>'.
                              ($item['is_locked']||$item['is_hardlocked']?'':'<a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/estate/delete_option/'.$item['id']).'"><i class="icon-remove"></i></a>')
                            .'</div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && sw_count($item['children'])) {
    				$str .= get_ol($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_pages'))
{
    function get_ol_pages ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (sw_count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="page_sortable" rel="2">' : '<ol>';
    		
    		foreach ($array as $item) {  

    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;<span class="page_sortable_title"><span">' . strip_tags($item['title']) .'</span></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="list_content"><span class="label label-warning">'.$item['template'].'</span></span>';
                if($item['type'] == 'ARTICLE')
                    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/page/edit/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/page/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && sw_count($item['children'])) {
    				$str .= get_ol_pages($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_pages_tree'))
{
    function get_ol_pages_tree ($array, $parent_id = 0, $custom_templates_names = array())
    {
    	$str = '';
    	
    	if (sw_count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="page_sortable" rel="3">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    		  
                if(isset($custom_templates_names[$item['template']]))
                {
                    $item['template'] = $custom_templates_names[$item['template']];
                }
              
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . strip_tags($item['title']) .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span>';
                if($item['type'] == 'ARTICLE')
                    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                if($item['is_visible'] == '0')
                    $str .= '&nbsp;&nbsp;<i class="icon-eye-close"></i>';
                    
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/page/edit/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/page/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_pages_tree($array, $k_parent_id, $custom_templates_names);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_news'))
{
    function get_ol_news ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (sw_count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="page_sortable" rel="2">' : '<ol>';
    		
    		foreach ($array as $item) {                
    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . strip_tags($item['title']) .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span><span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/news/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/news/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/news/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && sw_count($item['children'])) {
    				$str .= get_ol_news($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_showroom_tree'))
{
    function get_ol_showroom_tree ($array, $parent_id=0)
    {        
    	$str = '';
    	
    	if (sw_count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="showroom_sortable" rel="2">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . strip_tags($item['title']);
                //if($item['type'] == 'ARTICLE')
                //    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/showroom/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/showroom/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/showroom/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_showroom_tree($array, $k_parent_id);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_expert_tree'))
{
    function get_ol_expert_tree ($array, $parent_id=0)
    {        
    	$str = '';
    	
    	if (sw_count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="expert_sortable" rel="2">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['question'];
                //if($item['type'] == 'ARTICLE')
                //    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/expert/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/expert/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/expert/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_expert_tree($array, $k_parent_id);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

function calculateCenter($object_locations) 
{
    $minlat = false;
    $minlng = false;
    $maxlat = false;
    $maxlng = false;

    foreach ($object_locations as $estate) {
         $geolocation = array();
         
         $gps_string_explode = array();
         if(is_array($estate))
         {
            $gps_string_explode = explode(', ', $estate['gps']);
         }
         else
         {
            $gps_string_explode = explode(', ', $estate->gps);
         }

         if(!isset($gps_string_explode[1]) && isset($estate->lat))
         {
            $gps_string_explode[0] = $estate->lat;
            $gps_string_explode[1] = $estate->lng;
         }

         if(sw_count($gps_string_explode)>1)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
         }
    }

    // Calculate the center
    $lat = $maxlat - (($maxlat - $minlat) / 2);
    $lon = $maxlng - (($maxlng - $minlng) / 2);

    return $lat.', '.$lon;
}

function calculateCenterArray($array_locations) 
{
    if(sw_count($array_locations) == 0)
        return array(0,0);
    
    $minlat = false;
    $minlng = false;
    $maxlat = false;
    $maxlng = false;
    
    if(is_object($array_locations[0]))
    foreach ($array_locations as $estate) {
         $geolocation = array();
         $gps_string_explode = explode(', ', $estate->gps);
         
         if(sw_count($gps_string_explode)>1)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
        
         }
    }
    
    if(is_array($array_locations[0]))
    foreach ($array_locations as $estate) {
         $geolocation = array();
         $gps_string_explode = explode(', ', $estate['gps']);
         
         if(sw_count($gps_string_explode)>1)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
        
         }
    }

    // Calculate the center
    $lat = $maxlat - (($maxlat - $minlat) / 2);
    $lon = $maxlng - (($maxlng - $minlng) / 2);

    return array($lat, $lon);
}

function lang_check($line, $id = '')
{
	$r_line = lang($line, $id);

    if(empty($r_line))
        $r_line = $line;
    
	return $r_line;
}

function _l($line, $id = '', $replace_array = array())
{
    if(sw_count($replace_array) > 0)
    {
        $line = lang_check($line, $id);
        foreach($replace_array as $key=>$val)
        {
            $line = str_replace($key, $val, $line);
        }
        echo $line;        
    }
    else
    {
        echo lang_check($line, $id);
    }
}

function check_set($test, $default)
{
    if(isset($test))
        return $test;
        
    return $default;
}

function check_combine_set($main, $test, $default)
{
    if(sw_count(explode(',', $main)) == sw_count(explode(',', $test)) && 
       sw_count(explode(',', $main)) > 0 && sw_count(explode(',', $test)) > 0)
    {
        return $main;
    }

    return $default;
}

/* Extra simple acl implementation */
function check_acl($uri_for_check = NULL)
{
    $CI =& get_instance();
    $user_type = $CI->session->userdata('type');
    $acl_config = $CI->acl_config;
    //echo $CI->uri->uri_string();
    //echo $user_type;
    
    if($uri_for_check !== NULL)
    {
        if(in_array($uri_for_check, $acl_config[$user_type]))
        {
            return true;
        }
        
        $uri_for_check_explode = explode('/', $uri_for_check);
        if(in_array($uri_for_check_explode[0], $acl_config[$user_type]))
        {
            return true;
        }
        
        return false;
    }
    
    if(in_array($CI->uri->segment(2), $acl_config[$user_type]))
    {
        return true;
    }
    
    if(in_array($CI->uri->segment(2).'/index', $acl_config[$user_type]) && $CI->uri->segment(3) == '')
    {
        return true;
    }
    
    if(in_array($CI->uri->segment(2).'/'.$CI->uri->segment(3), $acl_config[$user_type]))
    {
        return true;
    }
    
    return false;
}

if ( ! function_exists('return_value'))
{
    function return_value($array, $key, $default='')
    {
        if(isset($array[$key]))
        {
            return $array[$key];
        }
        
        return $default;
    }
}

if ( ! function_exists('return_value_nempty'))
{
    function return_value_nempty($array, $key, $default='')
    {
        if(isset($array[$key]) && !empty($array[$key]))
        {
            return $array[$key];
        }
        
        return $default;
    }
}

/**
* Returns the specified config item
*
* @access	public
* @return	mixed
*/
if ( ! function_exists('config_db_item'))
{
	function config_db_item($item)
	{

        if(config_item('installed') != TRUE) return FALSE;

		static $_config_item = array();
        static $_db_settings = array();

		if ( ! isset($_config_item[$item]))
		{
			$config =& get_config();
            
            // [check-database]
            if(sw_count($_db_settings) == 0)
            {
                $CI =& get_instance();
                $CI->load->model('masking_m');
                $CI->load->model('settings_m');
                $_db_settings = $CI->settings_m->get_fields();
            }

            if(isset($_db_settings[$item]))
            {
                $_config_item[$item] = $_db_settings[$item];
                return $_config_item[$item];
            }
            // [/check-database]
            
			if ( ! isset($config[$item]))
			{
				return FALSE;
			}
			$_config_item[$item] = $config[$item];
		}

		return $_config_item[$item];
	}
}

if ( ! function_exists('map_event'))
{
	function map_event()
	{
		if(config_db_item('map_event') == 'mouseover')
        {
            return 'mouseover';
        }
        
        return 'click';
	}
}

function check_language_writing_permissions($template_folder)
{
    $write_error = '';

    if(!is_writable(FCPATH.'templates/'.$template_folder.'/language/'))
    {
        $write_error.='Folder templates/'.$template_folder.'/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'application/language/'))
    {
        $write_error.='Folder application/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'system/language/'))
    {
        $write_error.='Folder system/language/ is not writable<br />';
    }
    
    return $write_error;
}

function check_template_writing_permissions($template_folder)
{
    $write_error = '';

    if(!is_writable(FCPATH.'templates/'.$template_folder))
    {
        $write_error.='Folder templates/'.$template_folder.' is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'templates/'.$template_folder.'/widgets/'))
    {
        $write_error.='Folder templates/'.$template_folder.'/widgets/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'templates/'.$template_folder.'/config/'))
    {
        $write_error.='Folder templates/'.$template_folder.'/config/ is not writable<br />';
    }
    
    return $write_error;
}

function check_email_writing_permissions($template_folder)
{
    $write_error = '';
    
    if(!is_writable(APPPATH.'views/email/'))
    {
        $write_error.='File application/views/email is not writable<br />';
    }

    return $write_error;
}

function check_global_writing_permissions()
{
    $write_error = '';
    
    if(!is_writable(APPPATH.'config/cms_config.php'))
    {
        $write_error.='File application/config/cms_config.php is not writable<br />';
    }
    
    if(!is_writable(APPPATH.'config/production/database.php'))
    {
        $write_error.='File application/config/production/database.php is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/'))
    {
        $write_error.='Folder files/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'backups/'))
    {
        $write_error.='Folder backups/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/captcha/'))
    {
        $write_error.='Folder files/captcha/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/strict_cache/'))
    {
        $write_error.='Folder files/strict_cache/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/thumbnail/'))
    {
        $write_error.='Folder files/thumbnail/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'application/language/'))
    {
        $write_error.='Folder application/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'system/language/'))
    {
        $write_error.='Folder system/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'sitemap.xml'))
    {
        $write_error.='File sitemap.xml is not writable<br />';
    }
    
    return $write_error;
}


function print_breadcrump ($lang_id=NULL, $delimiter=' > ', $extra_ul_attributes = 'class="breadcrumb pull-left"') {
    $breadcrump_parts=array();
    $page='';
    $str='<ul '.$extra_ul_attributes.'>';
    
    $CI =& get_instance();
    $CI->load->model('page_m');
    
    // define lang id
    if($lang_id==null)
        $lang_id=$CI->data['lang_id'];
    
    $lang_code = 'en';
    if(empty($CI->lang_code))
    {
        $lang_code = $CI->language_m->get_code($lang_id);
    }
    else
    {
        $lang_code = $CI->lang_code;
    }
    
    
        $field_title='navigation_title_'.$lang_id;
        if(!empty($CI->data['page_id']))
        {
            $page_id=$CI->data['page_id'];
            $page=$CI->page_m->get_lang($page_id);
        }
    
    // if not frontend controller
        if($CI->uri->segment(1) == 'property')
        {
            /*
            $page=$CI->page_m->get_lang($page_id);
            $href=slug_url('property/'.$CI->uri->segment(2).'/'.$lang_code.'/'.url_title_cro($CI->data['page_title'], '-', TRUE), 'page_m');
            $_str="<a href='$href'>";
            $_str.=$CI->data['page_title'];
            $_str.='</a>';
            array_unshift($breadcrump_parts,$_str);
            */
            
            //property
            //$href= site_url($lang_code);
            $_str="<span>";
            $_str.=lang_check('Property preview');
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
        }
        else if($CI->uri->segment(1) == 'showroom')
        {
                        
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // showroom id
            $page_id=160;
        }
        else if($CI->uri->segment(1) == 'profile')
        {
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // showroom id
            $page_id=169;
        }
        else if($CI->uri->segment(1) == 'propertycompare')
        {
        }
        else if($CI->uri->segment(1) == 'treefield')
        {
        }
        else if($CI->uri->segment(1) == 'frontend' || $CI->uri->segment(1) == 'fresearch' || $CI->uri->segment(1) == 'fmessages' || $CI->uri->segment(1) == 'ffavorites')
        {
            //property login pages
            $_str="<span>";
            
            if(isset($CI->data['page_title']) && !empty($CI->data['page_title']))
                $_str.=$CI->data['page_title'];
            else
                $_str.=lang_check('My properties');
            
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
        }
        else if(!empty($page)&&$page->type == 'MODULE_NEWS_POST')
        {
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // page news id
            $page_id=142;
        }
        else if(!empty($page)&&$page->type == 'ARTICLE')
        {
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // page article
            $page_id=157;
        }
        else
        {

        }
        
        
    if(!empty($page)&& $page_id!=1){
       do {
           $page=$CI->page_m->get_lang($page_id);

           $_str="<span>";
           $_str.=$page->$field_title;
           $_str.='</span>';
           array_unshift($breadcrump_parts,$_str);

       } while (!empty($page->parent_id) && $page_id=$page->parent_id);
    }   
        
    // add homepage
    $_page=$CI->page_m->get_first();
    $href= site_url($lang_code);
    $_page=$CI->page_m->get_lang($_page->id);
    
    $_str="<a href='$href'>";
    $_str.=$_page->$field_title;
    $_str.='</a>';
    array_unshift($breadcrump_parts,$_str);
    
    $breadcrump_parts[0] ='<li>'. $breadcrump_parts[0];
    $breadcrump_parts[sw_count($breadcrump_parts)-1].= '</li>';
    
    $str.= implode('<span class="delimiter">'.$delimiter.'</span>'.'</li><li>', $breadcrump_parts);
    $str.= '</ul>';
    return $str;
}

/*
 * 
 * $compress_enabled=false to compatibility with old themes
 * 
 */

function cache_file($new_file, $original_file = NULL, $compress_enabled=false)
{
    static $file_list;
    $CI =& get_instance();
    $template_name = $CI->data['settings_template'];
    $cache_time_sec = 86400;
    
    // prefix
    if(strpos($new_file, 'css') !== FALSE)
    {
        $new_file = substr(md5($_SERVER['HTTP_HOST']),0,5).$new_file ;
    }
    
    //? in file name
    if(!empty($original_file) && strpos($original_file, '?'))
    {
        $original_file = substr($original_file,0,strpos($original_file, '?'));
    }
    
    if(!empty($original_file))
    {
        $file_list[$new_file][] = $original_file;
    }
    
    $cache_dir_abs = FCPATH.'templates/'.$template_name.'/assets/cache/';
    $cache_file_abs = $cache_dir_abs.$new_file;
    
    if(!file_exists($cache_dir_abs))
        mkdir($cache_dir_abs);

    // if original file is not defined, then print file
    if($original_file === NULL)
    {
        if(file_exists($cache_file_abs) && config_db_item('jscsscache_enabled') === TRUE)
        if(filemtime($cache_file_abs) > time()-$cache_time_sec)
        {
            if(strpos($new_file, 'css') !== FALSE)
            {
                echo '<link rel="stylesheet" type="text/css" href="assets/cache/'.$new_file.'" media="all">';
            }
            elseif(strpos($new_file, 'js') !== FALSE)
            {
                echo '<script src="assets/cache/'.$new_file.'"></script>';
            }
            
            return;
        }
        
        $all_content = '';
        // read al lfiles
        foreach($file_list[$new_file] as $key=>$file)
        {
            $file_content = file_get_contents(FCPATH.'templates/'.$template_name.'/'.$file);
            

            if(strpos($new_file, 'css') !== FALSE){
                $file_content = str_replace('../',base_url("/templates/$template_name").'/'.dirname($file).'/../',$file_content);
                
                $config_base_url = config_item('base_url');
                if(!empty($config_base_url)&& strpos( $config_base_url,'https')!== false){
                     $file_content = str_replace('http://','https://',$file_content);
                }
            }
            
            $all_content.=$file_content;
        }
        
        if(strpos($new_file, 'css') !== FALSE)
        {
            file_put_contents($cache_file_abs, $compress_enabled?compress_css($all_content):$all_content);
            echo '<link rel="stylesheet" type="text/css" href="assets/cache/'.$new_file.'" media="all">';
        }
        elseif(strpos($new_file, 'js') !== FALSE)
        {
            file_put_contents($cache_file_abs, $compress_enabled?compress_js($all_content):$all_content);
            echo '<script src="assets/cache/'.$new_file.'"></script>';
        }
    }
}

function compress_css ($code) {
    $code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code);
    $code = str_replace(array("\r\n", "\r", "\n", "\t", /*'  ',*/ '    ', '    '), '', $code);
    $code = str_replace('{ ', '{', $code);
    $code = str_replace(' }', '}', $code);
    $code = str_replace('; ', ';', $code);

    return $code;
}

function compress_js($code)
{
    //return $code;
    require_once APPPATH."helpers/min-js.php";
    
    $jSqueeze = new JSqueeze();
    $code = $jSqueeze->squeeze($code, true, false);
    
    return $code;
}

function listing_field($listing, $field_id, $empty='-')
{
    if(isset($listing->{"field_$field_id"}))
    {
        return $listing->{"field_$field_id"};
    }
    
    if(isset($listing->{"field_".$field_id."_int"}))
    {
        return $listing->{"field_".$field_id."_int"};
    }
    
    if(isset($listing->json_object))
    {
        $json_dec = json_decode($listing->json_object);
        
        if(isset($json_dec->{"field_$field_id"}))
        {
            return $json_dec->{"field_$field_id"};
        }
    }
    
    return $empty;    
}

/**
 * Convert a multi-dimensional array into a single-dimensional array.
 * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
 * @param  array $array The multi-dimensional array.
 * @return array
 */
function array_flatten($array) { 
  if (!is_array($array)) { 
    return false; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
}


if (! function_exists('field_order_by'))
{
	function field_order_by($field_title = '')
	{
            $CI =& get_instance();
            $str ='';
            $get = array();
            
            if($CI->input->get())
                $get = $CI->input->get();

            if(isset($get['order_by']))
                unset($get['order_by']);

            if($CI->input->get('order_by') && stripos($CI->input->get('order_by'), $field_title) !== FALSE){
                if(stripos($CI->input->get('order_by'), 'DESC') !== FALSE){
                    $str.='<a href="'.site_url($CI->uri->uri_string()).'?'.http_build_query(array_merge($get,array('order_by'=>$field_title.' ASC'))).'" class="glyphicon glyphicon-sort-by-attributes-alt"></a>';
                } elseif(stripos($CI->input->get('order_by'), 'ASC') !== FALSE){
                    $str.='<a href="'.site_url($CI->uri->uri_string()).'?'.http_build_query(array_merge($get,array('order_by'=>$field_title.' DESC'))).'" class="glyphicon glyphicon-sort-by-attributes"></a>';
                }
            } else {
                $str.='<a href="'.site_url($CI->uri->uri_string()).'?'.http_build_query(array_merge($get,array('order_by'=>$field_title.' ASC'))).'" class="glyphicon glyphicon-sort"></a>';
            }  
                        
            return $str;        
        
	}
}

if (! function_exists('get_widget_option'))
{
	function get_widget_option($option_name,$widget_name,$page_id,$lang_id,$default_value='')
	{
            $CI =& get_instance();
            $CI->load->model('widgetoptions_m');
            $template_name = $CI->data['settings']['template'];
            $value = $CI->widgetoptions_m->get_option_page_lang($option_name, $widget_name, $page_id, $template_name);
            
            if($value && $value->{'value_'.$lang_id})
                return $value->{'value_'.$lang_id};
            
            return $default_value;
	}
}

if (! function_exists('load_map_api'))
{
	function load_map_api($version='google', $lang_code ='en')
	{
            $CI =& get_instance();
            $template_name = $CI->data['settings']['template'];
           
            $src = '';
            switch ($version) {
                
                case 'open_street' :
                                $load = '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />';
                                $load .= '<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>';
                                $load .= '<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />';
                                $load .= '<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />';
                                $load .= '<script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>';
                                break;
                
                case 'google' : $maps_api_key = config_db_item('maps_api_key');;
                                $src = '//maps.google.com/maps/api/js?v=3.3&amp;libraries=places,geometry&amp;key='.$maps_api_key.'&amp;language='.$lang_code;
                                $load = '<script src="'.$src.'"></script>';
                                break;
                            
                default:        $maps_api_key = config_db_item('maps_api_key');
                                $src = '//maps.google.com/maps/api/js?v=3.3&amp;libraries=places,geometry&amp;key='.$maps_api_key.'&amp;language='.$lang_code;
                                $load = '<script src="'.$src.'"></script>';
                                break;
            }
            
            echo $load;
	}
}

if(!function_exists('sw_count')) {
    function sw_count($mixed='') {
        $count = 0;
        
        if(!empty($mixed) && (is_array($mixed))) {
            $count = count($mixed);
        } else if(function_exists('is_countable') && version_compare(PHP_VERSION, '7.3', '<') && is_countable($mixed)) {
            $count = count($mixed);
        }
        else if(!empty($mixed) && is_object($mixed)) {
            $count = 1;
        }
        return $count;
    }
}

function filter_lazyload($content) {
     // Performa search for all images
     return preg_replace_callback('/(<\s*img[^>]+)(src\s*=\s*"[^"]+")([^>]+>)/i', 'lazy_preg_replace_callback', $content);
}

if(!function_exists('lazy_preg_replace_callback')) {
    function lazy_preg_replace_callback($matches) {
        // Step 1: Replace our source attribute with a placeholder, and add a "data-original" attribute with our image source
        $img_replace = $matches[1] . 'src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-original' . substr($matches[2], 3) . $matches[3];
        // Step 2: Add the class "lazy" to the image
        $img_replace = preg_replace('/class\s*=\s*"/i', 'class="lazy ', $img_replace);
        // Step 3: Add a noscript tag as a fallback
        //$img_replace .= '<noscript>' . $matches[0] . '</noscript>';
        return $img_replace;
   }
}


function _generate_js($new_fielname, $original_file = NULL, $compress_enabled=true, $cache_time_sec = 86400)
{
    static $file_list;
    $CI =& get_instance();
    $template_name = $CI->data['settings_template'];
    
    if($CI->session->userdata('type')=='ADMIN')
        $cache_time_sec = 0;
    
    $output ='';
    $cache_file_name = FCPATH.'templates/'.$template_name.'/assets/cache/'.$new_fielname.'.js';
    //Load view
    if(file_exists(FCPATH.'templates/'.$template_name.'/'.$original_file))
        if(file_exists($cache_file_name) && filemtime($cache_file_name) > time()-$cache_time_sec)
        {
        } else {
            $output = $CI->parser->parse($template_name.'/'.$original_file, $CI->data, TRUE);
            $output = str_replace(array('<script>','</script>'), '', $output);
            if($compress_enabled) {
                require_once APPPATH."helpers/min-js.php";
                $jSqueeze = new JSqueeze();
                $output = $jSqueeze->squeeze($output, true, false);
            }
            file_put_contents(FCPATH.'templates/'.$template_name.'/assets/cache/'.$new_fielname.'.js', $output);
        }

    echo '<script src="assets/cache/'.$new_fielname.'.js?v='.rand(000,999).'"></script>';
    
}

function _generate_inline_js($new_fielname, $js_inline = NULL, $compress_enabled=true, $cache_time_sec = 86400)
{
    static $file_list;
    $CI =& get_instance();
    $template_name = $CI->data['settings_template'];
        
    if($CI->session->userdata('type')=='ADMIN')
        $cache_time_sec = 0;
    
    $output ='';
    $cache_file_name = FCPATH.'templates/'.$template_name.'/assets/cache/'.$new_fielname.'.js';
    //Load view
    if(file_exists($cache_file_name) && filemtime($cache_file_name) > time()-$cache_time_sec)
    {
    } else {
        $output = $js_inline;
        $output = str_replace(array('<script>','</script>'), '', $output);
        if($compress_enabled) {
            require_once APPPATH."helpers/min-js.php";
            $jSqueeze = new JSqueeze();
            $output = $jSqueeze->squeeze($output, true, false);
        }
        file_put_contents(FCPATH.'templates/'.$template_name.'/assets/cache/'.$new_fielname.'.js', $output);
    }

    echo '<script src="assets/cache/'.$new_fielname.'.js?v='.rand(000,999).'"></script>';
    
}

function sw_add_inline_script($handle, $js_inline = NULL, $compress_enabled=false, $cache_time_sec = 86400) {
    $CI =& get_instance();
    $CI->load->library('cache_assets');
    $caches = $CI->cache_assets;
    $caches::getInstance()->sw_add_inline_script($handle, $js_inline, $compress_enabled, $cache_time_sec);
}
        
function sw_add_script($handle, $original_file = NULL, $compress_enabled=true, $cache_time_sec = 86400) {
    $CI =& get_instance();
    $CI->load->library('cache_assets');
    $caches = $CI->cache_assets;
    $caches::getInstance()->sw_add_script($handle, $original_file, $compress_enabled, $cache_time_sec);
}


        

/**
 * Determines the difference between two timestamps.
 *
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "2 days".
 *
 * @since 1.5.0
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to   Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 * @return string Human readable time difference.
 */
if(!function_exists('human_time_diff')){
    function human_time_diff( $from, $to = '' ) {
            if ( empty( $to ) ) {
                    $to = time();
            }
            $minute_in_seconds = 60;
            $hour_in_seconds = 60 * $minute_in_seconds;
            $day_in_seconds = 24 * $hour_in_seconds;
            $week_in_seconds = 7 * $day_in_seconds;
            $month_in_seconds = 30 * $day_in_seconds ;
            $year_in_seconds = 365 * $day_in_seconds;
            $diff = (int) abs( $to - $from );

            if ( $diff < $hour_in_seconds ) {
                    $mins = round( $diff / $minute_in_seconds );
                    if ( $mins <= 1 ) {
                            $mins = 1;
                    }
                    /* translators: Time difference between two dates, in minutes (min=minute). %s: Number of minutes */
                    $since = sprintf( sw_n( '%s '.lang_check('min'), '%s '.lang_check('mins'), $mins ), $mins );
            } elseif ( $diff < $day_in_seconds && $diff >= $hour_in_seconds ) {
                    $hours = round( $diff / $hour_in_seconds );
                    if ( $hours <= 1 ) {
                            $hours = 1;
                    }
                    /* translators: Time difference between two dates, in hours. %s: Number of hours */
                    $since = sprintf( sw_n( '%s '.lang_check('hour'), '%s '.lang_check('hours'), $hours ), $hours );
            } elseif ( $diff < $month_in_seconds && $diff >= $day_in_seconds ) {
                    $days = round( $diff / $day_in_seconds );
                    if ( $days <= 1 ) {
                            $days = 1;
                    }
                    /* translators: Time difference between two dates, in days. %s: Number of days */
                    $since = sprintf( sw_n( '%s '.lang_check('day'), '%s '.lang_check('days'), $days ), $days );
            } elseif ( $diff < $month_in_seconds && $diff >= $month_in_seconds ) {
                    $weeks = round( $diff / $month_in_seconds );
                    if ( $weeks <= 1 ) {
                            $weeks = 1;
                    }
                    /* translators: Time difference between two dates, in weeks. %s: Number of weeks */
                    $since = sprintf( sw_n( '%s '.lang_check('week'), '%s '.lang_check('weeks'), $weeks ), $weeks );
            } elseif ( $diff < $year_in_seconds && $diff >= $month_in_seconds ) {
                    $months = round( $diff / $month_in_seconds );
                    if ( $months <= 1 ) {
                            $months = 1;
                    }
                    /* translators: Time difference between two dates, in months. %s: Number of months */
                    $since = sprintf( sw_n( '%s '.lang_check('month'), '%s '.lang_check('months'), $months ), $months );
            } elseif ( $diff >= $year_in_seconds ) {
                    $years = round( $diff / $year_in_seconds );
                    if ( $years <= 1 ) {
                            $years = 1;
                    }
                    /* translators: Time difference between two dates, in years. %s: Number of years */
                    $since = sprintf( sw_n( '%s '.lang_check('year'), '%s '.lang_check('years'), $years ), $years );
            }

            return $since;
    }
}

if(!function_exists('sw_n')){
    function sw_n ($single = '', $plural = '', $number = '') {
        $string = '';
        if($number==1) {
            $string = $single;
        } else {
            $string = $plural;
        }
        return $string;
    }
}

if(!function_exists('recursion_calc_count')) {
    function recursion_calc_count ($result_count, $tree_listings, $parent_title, $id, &$child_count){
        if (isset($tree_listings[$id]) && sw_count($tree_listings[$id]) > 0){
            foreach ($tree_listings[$id] as $key => $_child) {
                $options = $tree_listings[$_child->parent_id][$_child->id];
                if(isset($result_count[strtolower($parent_title.' - '.$options->value.' -')]))
                    $child_count+= $result_count[strtolower($parent_title.' - '.$options->value.' -')];

                $_parent_title = $parent_title.' - '.$options->value;
                if (isset($tree_listings[$_child->id]) && sw_count($tree_listings[$_child->id]) > 0){    
                    recursion_calc_count($result_count, $tree_listings, $_parent_title, $_child->id, $child_count);
                }
            }
        }
    }
}

if(!function_exists('generate_treefields_list')) {
    /*
     * 
     * @param $qr_string 
     * 
     *    - limit=3&level=1, example query + limit, results_limit - special
     *    - defined level_0  - show only level 0
     *              limit_3  - limit 3 with level 0, childs included
     *              limit_3_level_0  - limit 3 with level 0, without childs
     * 
     */
function generate_treefields_list($treefield_id = 64, $qr_string = '', $lang_id= NULL) {
    static $_gen_treefields;
    $var_case = $treefield_id.''.$qr_string;
    
    if(!empty($_gen_treefields[$var_case])) {
       return $_gen_treefields[$var_case];
    }
        
    $CI = & get_instance();

    $CI->load->model('treefield_m');
    $CI->load->model('cacher_m');
    $CI->load->model('option_m');
    $CI->load->model('file_m');
        
    // define lang id
    if($lang_id==null)
        $lang_id=$CI->data['lang_id'];
    
    /* check in cacher table */
    if($CI->session->userdata('type')!=='ADMIN'){
        $cacher_tree = $CI->cacher_m->load('treefield_'.$var_case.'_'.$lang_id);
        if($cacher_tree) {
            $_gen_treefields[$var_case] = unserialize($cacher_tree);
            return $_gen_treefields[$var_case];
        }
    }
    
    $conditions = array();
    $results_limit = '';
    if(!empty($qr_string)){
        switch ($qr_string){
            case 'level_0' : $qr_string = 'level=0';
                      break;
            case 'limit_3' : $qr_string = ''; $results_limit = 3;
                      break;
            case 'limit_3_level_0' : $qr_string = 'level=0&limit=3';
                      break;
        }
        $qr_string = strtolower($qr_string);
        $qr_string = trim($qr_string,'?');
        parse_str($qr_string, $conditions);
    }
    
    $lang_code=$CI->data['lang_code'];
    

    $check_option = $CI->option_m->get_by(array('id' => $treefield_id));
    // check if option exists
    if (!$check_option)
        return false;

    if ($check_option[0]->type != 'TREE')
        return false;

    if(isset($conditions['limit'])){
        $CI->db->limit($conditions['limit']);
        unset ($conditions['limit']);
    }
    
    $where = [];
    if(!empty($conditions)){
        $where = $conditions;
    }
    
    $tree_listings = $CI->treefield_m->get_table_tree($lang_id, $treefield_id, NULL, FALSE, '.order', ',value_path,image_filename, repository_id, description, font_icon_code, code', $where);
    
    if (!empty($tree_listings) && !empty($where)){
        if(sw_count($tree_listings))
        {
            $t_array = array();
            foreach($tree_listings as $option)
            {
                $t_array[$option->parent_id][$option->id] = $option;
            }
            $tree_listings = $t_array;
        }
    } elseif (!empty($tree_listings) && isset($tree_listings[0])){
        
    } else {
        return false;
    }

    

    $CI->db->select('property_value.value, COUNT(value) as count');

    $CI->db->join('property_value', 'property.id = property_value.property_id');

    $CI->db->group_by('property_value.value');
    $CI->db->where('option_id', $treefield_id);
    $CI->db->where('language_id', $lang_id);
    $CI->db->where('is_activated', 1);
    $CI->db->where('is_visible', 1);

    if (config_db_item('listing_expiry_days') !== FALSE) {
        if (is_numeric(config_db_item('listing_expiry_days')) && config_db_item('listing_expiry_days') > 0) {
            $CI->db->where('date_modified >', date("Y-m-d H:i:s", time() - config_db_item('listing_expiry_days') * 86400));
        }
    }

    $query = $CI->db->get('property');

    $result_count = array();

    if ($query) {
        $result = $query->result();
        foreach ($result as $key => $value) {
            if (!empty($value->value)) {
                $v = strtolower($value->value);
                $v = trim($v);
                $result_count[$v] = $value->count;
            }
        }
    }

    $_treefields = $tree_listings[0];
    $treefields = array();
    $res_count = 0;
    foreach ($_treefields as $val) {
        $options = $tree_listings[0][$val->id];
        $treefield = array();
        $field_name = 'value';
        $treefield['id'] = trim($options->id);
        $treefield['title'] = (!empty($options->$field_name)) ? trim($options->$field_name) : '';;
        $treefield['value_path'] = (!empty($options->value_path)) ? trim($options->value_path) : '';;
        $treefield['title_chlimit'] = character_limiter($options->$field_name, 15);
        $treefield['description'] = (!empty($options->description)) ? trim($options->description) : '';

        $treefield['description_chlimit'] = character_limiter($options->description, 50);
        $treefield['font_icon_code'] = _ch($options->font_icon_code, '');
        $treefield['code'] = _ch($options->code, '');

        $treefield['count'] = 0;
        if (isset($result_count[strtolower($treefield['title'] . ' -')]))
            $treefield['count'] = $result_count[strtolower($treefield['title'] . ' -')];

        $treefield['url'] = '';
        /* link if have body */
        if (!empty($options->{'body'})) {
            $href = slug_url('treefield/' . $lang_code . '/' . $options->id . '/' . url_title_cro($options->value), 'treefield_m');
            $treefield['url'] = $href;
        } else {
            $href = site_url_nosuff($lang_code . '/' . get_results_page_id() . '/?search={"v_search_option_' . $treefield_id . '":"' . rawurlencode($treefield['title'] . ' - ') . '"}');
            if(config_item('json_url_encoding')!==FALSE){
                $href = str_replace('}', '%7D', $href);
                $href = str_replace('{', '%7B', $href);
            }
            $treefield['url'] = $href;
        }
        /* end if have body */

        // Thumbnail and big image
        $treefield['thumbnail_url'] = '';
        $treefield['image_url'] = '';
        $treefield['icon'] = '';
        $treefield['files_obj'] = '';
        if (!empty($options->image_filename) and file_exists(FCPATH . 'files/thumbnail/' . $options->image_filename)) {
            $files_r = $CI->file_m->get_by(array('repository_id' => $options->repository_id), FALSE, 5, 'id ASC');

            // check first image
            $treefield['thumbnail_url'] = base_url('files/thumbnail/' . $options->image_filename);
            $treefield['image_url'] = base_url('files/' . $options->image_filename);

            // check second image
            if ($files_r and isset($files_r[1]) and file_exists(FCPATH . 'files/thumbnail/' . $files_r[1]->filename)) {
                $treefield['icon'] = base_url('files/' . $files_r[1]->filename);
                $treefield['files_obj'] = $files_r;
            }
        }

        $childs_count = 0;
        $childs = array();
        if (isset($tree_listings[$val->id]) && sw_count($tree_listings[$val->id]) > 0)
            foreach ($tree_listings[$val->id] as $key => $_child) {
                $child = array();
                $options = $tree_listings[$_child->parent_id][$_child->id];
                $field_name = 'value';
                $child['title'] = trim($options->$field_name);
                $child['title_chlimit'] = character_limiter($options->$field_name, 10);
                $child['value_path'] = trim($options->value_path);

                $child['count'] = 0;
                if (isset($result_count[strtolower($treefield['title'] . ' - ' . $child['title'] . ' -')]))
                    $child['count'] = $result_count[strtolower($treefield['title'] . ' - ' . $child['title'] . ' -')];

                $child['url'] = '';
                /* link if have body */
                if (!empty($options->{'body'})) {
                    // If slug then define slug link
                    $href = slug_url('treefield/' . $lang_code . '/' . $options->id . '/' . url_title_cro($options->value), 'treefield_m');
                    $child['url'] = $href;
                }
                /* end if have body */
                $child['url'] = '';
                /* link if have body */
                if (!empty($options->{'body'})) {
                    $href = slug_url('treefield/' . $lang_code . '/' . $options->id . '/' . url_title_cro($options->value), 'treefield_m');
                    $child['url'] = $href;
                } else {
                    $href = site_url_nosuff($lang_code . '/' . get_results_page_id() . '/?search={"v_search_option_' . $treefield_id . '":"' . rawurlencode($treefield['title'] . ' - ' . $child['title'] . ' - ') . '"}');
                    if(config_item('json_url_encoding')!==FALSE) {
                        $href = str_replace('}', '%7D', $href);
                        $href = str_replace('{', '%7B', $href);
                    }
                    $child['url'] = $href;
                }
                /* end if have body */

                // Thumbnail and big image
                $child['font_icon_code'] = _ch($options->font_icon_code, '');
                $child['code'] = _ch($options->code, '');
                $child['thumbnail_url'] = '';
                $child['image_url'] = '';
                $child['icon'] = '';
                $child['files_obj'] = '';
                if (!empty($options->image_filename) and file_exists(FCPATH . 'files/thumbnail/' . $options->image_filename)) {
                    $files_r = $CI->file_m->get_by(array('repository_id' => $options->repository_id), FALSE, 5, 'id ASC');

                    // check first image
                    $child['thumbnail_url'] = base_url('files/thumbnail/' . $options->image_filename);
                    $child['image_url'] = base_url('files/' . $options->image_filename);

                    // check second image
                    if ($files_r and isset($files_r[1]) and file_exists(FCPATH . 'files/thumbnail/' . $files_r[1]->filename)) {
                        $child['icon'] = base_url('files/' . $files_r[1]->filename);
                        $child['files_obj'] = $files_r;
                    }
                }

                if (isset($tree_listings[$_child->id]) && sw_count($tree_listings[$_child->id]) > 0) {
                    $parent_title = $treefield['title'] . ' - ' . $child['title'];
                    recursion_calc_count($result_count, $tree_listings, $parent_title, $_child->id, $child['count']);
                }

                $childs_count += $child['count'];
                $childs[] = $child;
            }


        $treefield['count'] += $childs_count;
        $treefield['childs'] = $childs;
        $treefield['childs_more'] = array_slice($childs, 5);
        $treefield['childs_5'] = array_slice($childs, 0, 5);
        $treefields[] = $treefield;
        
        $res_count ++;
        if(!empty($results_limit))
            if($res_count >=$results_limit)
                break;
    }
     
    $_gen_treefields[$var_case] = $treefields;
    // cache results 
    $CI->cacher_m->cache_update('treefield_'.$var_case.'_'.$lang_id, serialize($treefields), 7);
    
    return $_gen_treefields[$var_case];
}
}

function get_results_page_id($empty_id = 1){
    static $get_results_page_id;
    
    if(!empty($get_results_page_id)){
        return $get_results_page_id;
    }
    
    if(config_db_item('results_page_id')) {
        $get_results_page_id = config_db_item('results_page_id');
        return config_db_item('results_page_id');
    }
    
    /* if not set results page, try search page with template page_customsearch*/
    $CI =& get_instance();
    $CI->load->model('page_m');
    
    // define lang id
    $lang_id=$CI->data['lang_id'];
    
    $default_search_template = 'page_customsearch';
    
     if(config_item('default_search_template')) {
        $default_search_template = config_item('default_search_template');
    }
    
    
    $_page = $CI->page_m->get_lang(NULL,false,$lang_id, array('template'=>$default_search_template));
    
    if($_page) {
        $get_results_page_id = $_page[0]->id;
        return $_page[0]->id;
    }
    
    return $empty_id;
}


if(!function_exists('generate_iframe_multimedia')) {
    function generate_iframe_multimedia ($input = '') {
        if(empty($input)) {
            return '';
        }
        $output = '';
        // iframe support
        if(strpos($input, 'iframe') !== FALSE) {
            /* filter */
            $input = str_replace('""', '"', $input);
            $input= str_replace( '&quot;','', $input );

            /* if set not correct iframe code */
            $input= str_replace( '[','<', $input );
            $input= str_replace( ']','></iframe>', $input );

            $output .=  $input;
        }
        elseif(strpos($input, 'vimeo.com') !== FALSE)
        {
            $output .='<iframe width="800" height="455" class="generic_iframe" src="'.$input.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        elseif(strpos($input, 'watch?v=') !== FALSE)
        {
            $embed_code = substr($input, strpos($input, 'watch?v=')+8);
            $output .='<iframe width="560" height="315" class="generic_iframe" src="https://www.youtube.com/embed/'.$embed_code.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        // version for youtube link
        elseif(strpos($input, 'youtu.be/') !== FALSE)
        {
            $embed_code = substr($input, strpos($input, 'youtu.be/')+9);
            $output .='<iframe width="800" height="455" class="generic_iframe" src="https://www.youtube.com/embed/'.$embed_code.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        // version for youtube link
        elseif(strpos($input, 'instagram') !== FALSE)
        {
            
            $embed_code = substr($input, strpos($input, 'instagram.com/p/')+16);
            if(strpos($embed_code,  '/') !== FALSE)
                $embed_code = substr($embed_code, 0, strpos($embed_code, '/'));
            
            $link = 'https://www.instagram.com/p/'.$embed_code.'/?__a=1';
            $api_inst = file_get_contents_curl('https://www.instagram.com/p/'.$embed_code.'/?__a=1');
            
            if(get_furl($link)) {
                $api_inst = file_get_contents_curl('https://www.instagram.com/p/'.$embed_code.'/?__a=1');
                $api_inst = json_decode($api_inst);
                if($api_inst && isset($api_inst->graphql->shortcode_media->video_url)) {
                    $placeholder = $api_inst->graphql->shortcode_media->display_url;
                    $output .='<div class="inst_video"><div style="background: url('.$placeholder.');"  onclick="jQuery(this).hide().next()[0].play()"><svg class="video-overlay-play-button" viewBox="0 0 200 200" alt="Play video"><circle cx="100" cy="100" r="90" fill="none" stroke-width="15" stroke="#fff"></circle><polygon points="70, 55 70, 145 145, 100" fill="#fff"></polygon></svg></div><video controls="controls" name="media"><source src="'.$api_inst->graphql->shortcode_media->video_url.'" type="video/mp4"></video></div>';

                }
            }
        }
        // basic iframe
        else
        {
           $output .'<iframe width="560" height="315" class="generic_iframe" src="'.($input).'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        
        return $output;

    }
}



/*
 * sw_get_option_icon - return icon of field
 * @param $id = int, if of field
 * #param $html = boolen, by default true, return with tab img or just link
 * 
 * return string or empty string if not found value or missing some data
 * 
 * 
 */
if(!function_exists('sw_get_option_icon')) {
    function sw_get_option_icon($id = NULL, $html = TRUE) {
        if(is_null($id)) return false;
        
        $CI = &get_instance();
        $field_data = false;
        $output = '';
        if(isset($CI->data['options_obj_'.$id])) {
            $field_data = $CI->data['options_obj_'.$id];
        } else {
            $CI->load->model('option_m');
            $field_data = $CI->option_m->get($id);
        }
        
        if(empty($field_data)) return false;
       
        if(!empty($field_data->image_filename))
        {
            $output = base_url('files/'.$field_data->image_filename);
        }
        elseif(file_exists(FCPATH.'templates/'.$CI->data['settings_template'].
            '/assets/img/icons/option_id/'.$id.'.png'))
        {
            $output = "assets/img/icons/option_id/".$id.".png";
        }
        
        if(!empty($output) && $html) {
            $output = '<img class="results-icon" src="'.$output.'" alt="field_'.$id.'"/>';;
        }
        
        return $output;
    }
}

if(!function_exists('sw_is_date')){
    function sw_is_date($date, $format = 'Y-m-d H:i:s') 
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

if(!function_exists('current_url_q')){
    function current_url_q() 
    {
        if(!empty($_SERVER['QUERY_STRING'])) 
            return current_url().'?'.$_SERVER['QUERY_STRING'];
        else    
            return current_url();
    }
}

if(!function_exists('get_user_location')){
    function get_user_location($user_id = NULL) 
    {
        static $location;
        if(!empty(($location))) return $location;
        
        $location = '';
        $CI = &get_instance();
        if(empty($user_id) && $CI->session->userdata('id'))
            $user_id = $CI->session->userdata('id');
        
        if($CI->session->userdata('set_country'))
        {
            $location = $CI->session->userdata('set_country');
        }
        
        return $location;
    }
}


if(!function_exists('get_user_location_value')){
    function get_user_location_value() 
    {
        static $location;
        if(!empty(($location))) return $location;
        
        $location = '';
        $location_id = get_user_location();
        if(!$location_id) return $location;
        $CI = &get_instance();
        $lang_id=$CI->data['lang_id'];
        $CI->load->model('treefield_m');
        $treefield_list = $CI->treefield_m->get_lang($location_id, FALSE,$lang_id);
        if($treefield_list && isset($treefield_list->{'value_'.$lang_id})){
            $location = $treefield_list->{'value_'.$lang_id};
        }
        
        return $location;
    }
}

if(!function_exists('get_user_location_icon')){
    function get_user_location_icon() 
    {
        static $icon;
        if(!empty(($icon))) return $icon;
        
        $code = '';
        $icon = '';
        $location_id = get_user_location();
        if(!$location_id) return '';
        $CI = &get_instance();
        $lang_id=$CI->data['lang_id'];
        $CI->load->model('treefield_m');
        $treefield_list =  $CI->treefield_m->get_lang($location_id, FALSE,$lang_id);
        
        if($treefield_list && isset($treefield_list->{'code'})){
            $code = $treefield_list->{'code'};
        }
        
        if($code && file_exists(FCPATH.'templates/'.$CI->data['settings_template'].'/assets/img/flags/'.$code.'.png'))
            $icon = 'assets/img/flags/'.$code.'.png';
        
        return $icon;
    }
}

if(!function_exists('sw_money_format')){
    function sw_money_format($floatcurr, $curr = 'EUR')
    {
        if(empty($floatcurr)) return '';
		$floatcurr = str_replace(array(',', ' '), '', $floatcurr);
        $currencies = [];
        $currencies['ARS'] = array(2, ',', '.');          //  Argentine Peso
        $currencies['AMD'] = array(2, '.', ',');          //  Armenian Dram
        $currencies['AWG'] = array(2, '.', ',');          //  Aruban Guilder
        $currencies['AUD'] = array(2, '.', ' ');          //  Australian Dollar
        $currencies['BSD'] = array(2, '.', ',');          //  Bahamian Dollar
        $currencies['BHD'] = array(3, '.', ',');          //  Bahraini Dinar
        $currencies['BDT'] = array(2, '.', ',');          //  Bangladesh, Taka
        $currencies['BZD'] = array(2, '.', ',');          //  Belize Dollar
        $currencies['BMD'] = array(2, '.', ',');          //  Bermudian Dollar
        $currencies['BOB'] = array(2, '.', ',');          //  Bolivia, Boliviano
        $currencies['BAM'] = array(2, '.', ',');          //  Bosnia and Herzegovina, Convertible Marks
        $currencies['BWP'] = array(2, '.', ',');          //  Botswana, Pula
        $currencies['BRL'] = array(2, ',', '.');          //  Brazilian Real
        $currencies['BND'] = array(2, '.', ',');          //  Brunei Dollar
        $currencies['CAD'] = array(2, '.', ',');          //  Canadian Dollar
        $currencies['KYD'] = array(2, '.', ',');          //  Cayman Islands Dollar
        $currencies['CLP'] = array(0,  '', '.');          //  Chilean Peso
        $currencies['CNY'] = array(2, '.', ',');          //  China Yuan Renminbi
        $currencies['COP'] = array(2, ',', '.');          //  Colombian Peso
        $currencies['CRC'] = array(2, ',', '.');          //  Costa Rican Colon
        $currencies['HRK'] = array(2, ',', '.');          //  Croatian Kuna
        $currencies['CUC'] = array(2, '.', ',');          //  Cuban Convertible Peso
        $currencies['CUP'] = array(2, '.', ',');          //  Cuban Peso
        $currencies['CYP'] = array(2, '.', ',');          //  Cyprus Pound
        $currencies['CZK'] = array(2, '.', ',');          //  Czech Koruna
        $currencies['DKK'] = array(2, ',', '.');          //  Danish Krone
        $currencies['DOP'] = array(2, '.', ',');          //  Dominican Peso
        $currencies['XCD'] = array(2, '.', ',');          //  East Caribbean Dollar
        $currencies['EGP'] = array(2, '.', ',');          //  Egyptian Pound
        $currencies['SVC'] = array(2, '.', ',');          //  El Salvador Colon
        $currencies['ATS'] = array(2, ',', '.');          //  Euro
        $currencies['BEF'] = array(2, ',', '.');          //  Euro
        $currencies['DEM'] = array(2, ',', '.');          //  Euro
        $currencies['EEK'] = array(2, ',', '.');          //  Euro
        $currencies['ESP'] = array(2, ',', '.');          //  Euro
        $currencies['EUR'] = array(2, ',', '.');          //  Euro
        $currencies['FIM'] = array(2, ',', '.');          //  Euro
        $currencies['FRF'] = array(2, ',', '.');          //  Euro
        $currencies['GRD'] = array(2, ',', '.');          //  Euro
        $currencies['IEP'] = array(2, ',', '.');          //  Euro
        $currencies['ITL'] = array(2, ',', '.');          //  Euro
        $currencies['LUF'] = array(2, ',', '.');          //  Euro
        $currencies['NLG'] = array(2, ',', '.');          //  Euro
        $currencies['PTE'] = array(2, ',', '.');          //  Euro
        $currencies['GHC'] = array(2, '.', ',');          //  Ghana, Cedi
        $currencies['GIP'] = array(2, '.', ',');          //  Gibraltar Pound
        $currencies['GTQ'] = array(2, '.', ',');          //  Guatemala, Quetzal
        $currencies['HNL'] = array(2, '.', ',');          //  Honduras, Lempira
        $currencies['HKD'] = array(2, '.', ',');          //  Hong Kong Dollar
        $currencies['HUF'] = array(0,  '', '.');          //  Hungary, Forint
        $currencies['ISK'] = array(0,  '', '.');          //  Iceland Krona
        $currencies['INR'] = array(2, '.', ',');          //  Indian Rupee
        $currencies['IDR'] = array(2, ',', '.');          //  Indonesia, Rupiah
        $currencies['IRR'] = array(2, '.', ',');          //  Iranian Rial
        $currencies['JMD'] = array(2, '.', ',');          //  Jamaican Dollar
        $currencies['JPY'] = array(0,  '', ',');          //  Japan, Yen
        $currencies['JOD'] = array(3, '.', ',');          //  Jordanian Dinar
        $currencies['KES'] = array(2, '.', ',');          //  Kenyan Shilling
        $currencies['KWD'] = array(3, '.', ',');          //  Kuwaiti Dinar
        $currencies['LVL'] = array(2, '.', ',');          //  Latvian Lats
        $currencies['LBP'] = array(0,  '', ' ');          //  Lebanese Pound
        $currencies['LTL'] = array(2, ',', ' ');          //  Lithuanian Litas
        $currencies['MKD'] = array(2, '.', ',');          //  Macedonia, Denar
        $currencies['MYR'] = array(2, '.', ',');          //  Malaysian Ringgit
        $currencies['MTL'] = array(2, '.', ',');          //  Maltese Lira
        $currencies['MUR'] = array(0,  '', ',');          //  Mauritius Rupee
        $currencies['MXN'] = array(2, '.', ',');          //  Mexican Peso
        $currencies['MZM'] = array(2, ',', '.');          //  Mozambique Metical
        $currencies['NPR'] = array(2, '.', ',');          //  Nepalese Rupee
        $currencies['ANG'] = array(2, '.', ',');          //  Netherlands Antillian Guilder
        $currencies['ILS'] = array(2, '.', ',');          //  New Israeli Shekel
        $currencies['TRY'] = array(2, '.', ',');          //  New Turkish Lira
        $currencies['NZD'] = array(2, '.', ',');          //  New Zealand Dollar
        $currencies['NOK'] = array(2, ',', '.');          //  Norwegian Krone
        $currencies['PKR'] = array(2, '.', ',');          //  Pakistan Rupee
        $currencies['PEN'] = array(2, '.', ',');          //  Peru, Nuevo Sol
        $currencies['UYU'] = array(2, ',', '.');          //  Peso Uruguayo
        $currencies['PHP'] = array(2, '.', ',');          //  Philippine Peso
        $currencies['PLN'] = array(2, '.', ' ');          //  Poland, Zloty
        $currencies['GBP'] = array(2, '.', ',');          //  Pound Sterling
        $currencies['OMR'] = array(3, '.', ',');          //  Rial Omani
        $currencies['RON'] = array(2, ',', '.');          //  Romania, New Leu
        $currencies['ROL'] = array(2, ',', '.');          //  Romania, Old Leu
        $currencies['RUB'] = array(2, ',', '.');          //  Russian Ruble
        $currencies['SAR'] = array(2, '.', ',');          //  Saudi Riyal
        $currencies['SGD'] = array(2, '.', ',');          //  Singapore Dollar
        $currencies['SKK'] = array(2, ',', ' ');          //  Slovak Koruna
        $currencies['SIT'] = array(2, ',', '.');          //  Slovenia, Tolar
        $currencies['ZAR'] = array(2, '.', ' ');          //  South Africa, Rand
        $currencies['KRW'] = array(0,  '', ',');          //  South Korea, Won
        $currencies['SZL'] = array(2, '.', ', ');         //  Swaziland, Lilangeni
        $currencies['SEK'] = array(2, ',', '.');          //  Swedish Krona
        $currencies['CHF'] = array(2, '.', '\'');         //  Swiss Franc
        $currencies['TZS'] = array(2, '.', ',');          //  Tanzanian Shilling
        $currencies['THB'] = array(2, '.', ',');          //  Thailand, Baht
        $currencies['TOP'] = array(2, '.', ',');          //  Tonga, Paanga
        $currencies['AED'] = array(2, '.', ',');          //  UAE Dirham
        $currencies['UAH'] = array(2, ',', ' ');          //  Ukraine, Hryvnia
        $currencies['USD'] = array(2, '.', ',');          //  US Dollar
        $currencies['VUV'] = array(0,  '', ',');          //  Vanuatu, Vatu
        $currencies['VEF'] = array(2, ',', '.');          //  Venezuela Bolivares Fuertes
        $currencies['VEB'] = array(2, ',', '.');          //  Venezuela, Bolivar
        $currencies['VND'] = array(0,  '', '.');          //  Viet Nam, Dong
        $currencies['ZWD'] = array(2, '.', ' ');          //  Zimbabwe Dollar
        // custom function to generate: ##,##,###.##
        $formatinr = function ($input)
        {
            $dec = "";
            $pos = strpos($input, ".");
            if ($pos === FALSE)
            {
                //no decimals
            }
            else
            {
                //decimals
                $dec   = substr(round(substr($input, $pos), 2), 1);
                $input = substr($input, 0, $pos);
            }
            $num   = substr($input, -3);    // get the last 3 digits
            $input = substr($input, 0, -3); // omit the last 3 digits already stored in $num
            // loop the process - further get digits 2 by 2
            while (strlen($input) > 0)
            {
                $num   = substr($input, -2).",".$num;
                $input = substr($input, 0, -2);
            }
            return $num.$dec;
        };
        
        if ($curr == "INR")
        {
            return $formatinr($floatcurr);
        }
        else
        {
            if(is_intval($floatcurr))
                return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
        }
    }
}

if(!function_exists('is_intval'))
{
    function is_intval($string)
    {
        if(!is_numeric($string))
            return FALSE;
    
        return TRUE;
    }
}

function sw_xss_clean($data)
{
    //if($data == '0000-00-00 00:00:00')return '';
    if(is_array($data))
        return sw_xss_clean_array($data);

    if(is_object($data))
        return sw_xss_clean_object($data);

    if($data === NULL)
        return '';

    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    //$data = sanitize_textarea_field($data);

    // we are done...
    return $data;
}

function sw_xss_clean_array($array)
{
    if(!is_array($array))return array();

    $arr_cleaned = array();
    foreach($array as $key=>$val)
    {
        if($key == 'newcontent')
        {
            $arr_cleaned[sw_xss_clean($key)] = $val;
        }
        else
        {
            $arr_cleaned[sw_xss_clean($key)] = sw_xss_clean($val);
        }
        
    }

    //dump($arr_cleaned);

    return $arr_cleaned;
}

function sw_xss_clean_object($object)
{
    if(!is_object($object))return NULL;

    $array = get_object_vars($object);
    foreach($array as $key=>$val)
    {
        $object->{$key} = sw_xss_clean($object->{$key});
    }

    return $object;
}

if(!function_exists('sw_in_date_format'))
{
    function sw_in_date_format($date = '')
    {
        if(!empty($date) && sw_is_date($date)){
            $CI =& get_instance();
            if(!empty($CI->data['settings_php_date_format'])){
                $date = date($CI->data['settings_php_date_format'], strtotime($date));
            }
        }
        return $date;
    }
}

if(!function_exists('sw_is_intval'))
{
    function sw_is_intval($string)
    {
        if(!is_numeric($string))
            return FALSE;
    
        return TRUE;
    }
}
