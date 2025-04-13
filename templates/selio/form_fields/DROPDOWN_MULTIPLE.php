<?php
    $col=3;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=3;
        $direction = '';
    }
    else
    {
        $placeholder = lang_check($direction);
        $direction=strtolower('_'.$direction);
    }
    
    
    $f_id = $field->id;
    $class_add = $field->class;
    
    $values_arr = array('' => lang_check('Select').' '.$placeholder) ;
    foreach (${'options_values_arr_'.$f_id} as $key => $value) {
        $values_arr[_ch($value)] = _ch(${'options_prefix_'.$f_id}, '')._ch($value)._ch(${'options_suffix_'.$f_id}, '');
    }

    if(function_exists('sw_filter_search_slidetoggle')) 
    sw_filter_search_slidetoggle();
    
    $search_values = search_value($f_id.'_multi');

    if($f_id == 4) {
        $values_arr = ${'options_values_arr_4'};
        $CI = &get_instance();

        if(!function_exists('_get_purpose')) {
            function _get_purpose($CI)
            {
                if(isset($CI->select_tab_by_title))
                if($CI->select_tab_by_title != '')
                {
                    $CI->data['purpose_defined'] = $CI->select_tab_by_title;
                    return $CI->select_tab_by_title;
                }
                
                if(isset($CI->data['is_purpose_sale'][0]['count']))
                {
                    $CI->data['purpose_defined'] = lang('Sale');
                    return lang('Sale');
                }
                
                if(isset($CI->data['is_purpose_rent'][0]['count']))
                {
                    $CI->data['purpose_defined'] = lang('Rent');
                    return lang('Rent');
                }
                
                if(search_value(4))
                    return search_value(4);
                
                return '';
                
            }
        }
        
        $purpose = _get_purpose($CI);
		
        $purpose = array_search(strtolower($purpose), array_map('strtolower', $values_arr));
		$search_values_title = array();
        if($purpose !== FALSE || $purpose!=0) {
            $search_values_title[] =$values_arr[$purpose];
        }
        
        if(empty($search_values))
            $search_values = $search_values_title;

    }

?>

<div class="form_field <?php echo _ch($class_add);?> field_search_<?php echo _ch($f_id); ?>">
    <div class="form-group">
        <select id="search_option_<?php echo $f_id; ?>_multi" class="form-control selectpicker" multiple="multiple" title="<?php echo $placeholder;?>">
        <?php foreach ($values_arr as $key => $value):?>
            <option value="<?php echo $value;?>" <?php if(is_array($search_values) && (in_array($value, $search_values) || in_array($key, $search_values))):?> selected="selected" <?php endif;?>><?php echo $value;?></option>
        <?php endforeach;?>
        </select>
    </div>
</div>