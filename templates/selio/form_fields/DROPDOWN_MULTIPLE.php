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
?>

<div class="form_field <?php echo _ch($class_add);?> field_search_<?php echo _ch($f_id); ?>">
    <div class="form-group">
        <select id="search_option_<?php echo $f_id; ?>_multi" class="form-control selectpicker" multiple="multiple" title="<?php echo $placeholder;?>">
        <?php foreach ($values_arr as $key => $value):?>
            <option value="<?php echo $key;?>" <?php if(!empty($key) && is_array($search_values) && in_array($key, $search_values)):?> selected="selected" <?php endif;?>><?php echo $value;?></option>
        <?php endforeach;?>
        </select>
    </div>
</div>