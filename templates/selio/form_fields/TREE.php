
<?php
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $direction = '';
    }
    else
    {
        $placeholder = lang_check($direction);
        $direction=strtolower('_'.$direction);
    }
    
    
    $f_id = $field->id;
    $class_add = $field->class;
    
    if(function_exists('sw_filter_search_slidetoggle')) 
    sw_filter_search_slidetoggle();
    
?>

<?php if($f_id == 64 && config_db_item('show_separeted_location_fields')):?>
    <?php    
    $placeholder = lang_check('Countries');
    $value = search_value($f_id);
    if(empty($value) && get_user_location_value()) {
        $value = get_user_location_value().' -';
    }
    ?>
    <div class="form_field form_field_alt <?php echo $class_add; ?>" style=" width:50%;<?php _che($field->style); ?>" >
        <div class="form-group form-group-tree-alt">
            <?php echo form_treefield_alt('search_option_'.$f_id, 'treefield_m', $value, 'value', $lang_id, 'field_search_', true, $placeholder, $f_id, 'class="form-control locationautocomplete"','value_path', lang_check('Cities'), lang_check('Cities'));?>
        </div><!-- /.select-wrapper -->
    </div><!-- /.form-group -->
<?php else:?>
    <?php if($f_id == 64) {
        if(!search_value($f_id)) {
            $CI =& get_instance();
            $CI->g_post_option[64] = get_user_location_value();
        }
    }
    ?>

    <div class="form_field <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
        <div class="form-group">
            <?php echo form_treefield('search_option_'.$f_id, 'treefield_m', search_value($f_id), 'value', $lang_id, 'field_search_', true, $placeholder, $f_id, 'class="form-control locationautocomplete"','value_path');?>
        </div><!-- /.select-wrapper -->
    </div><!-- /.form-group -->
<?php endif;?>