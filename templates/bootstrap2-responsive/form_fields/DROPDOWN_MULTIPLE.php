<?php
    $col=3;

    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=3;
        $direction = '';
    }
    
    $f_id = $field->id;
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' span'.$col;
    
    //bootstrap 2
    $class_add = str_replace(array('col-md-','col-sm-','col-xl-','col-xs-'), 'span', $class_add);
    
?>
<select id="search_option_<?php echo $f_id; ?>_multi" class="<?php echo $class_add; ?> selectpicker form-control" style="<?php _che($field->style); ?>"  title="<?php echo _l('Choose');?> <?php _che(${'options_name_'.$f_id}); ?>" multiple="multiple">
    <?php if(isset(${'options_values_arr_'.$f_id}) && !empty(${'options_values_arr_'.$f_id}))
            foreach (${'options_values_arr_'.$f_id} as $key => $value):?>
               <option value="<?php _che($value);?>" <?php if(stripos(search_value($f_id), $value) !== FALSE):?>selected="selected"<?php endif;?> ><?php _che($value);?></option>
    <?php endforeach;?>
</select>
