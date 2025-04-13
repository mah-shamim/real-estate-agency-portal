<?php
    $col=3;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=3;
        $direction = '';
    }
    else if(empty($field->class)) {
        $placeholder = $placeholder.' ('.lang_check($direction).')';
        $direction=strtolower('_'.$direction);
    }
    else
    {
        $placeholder = lang_check($direction);
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = _ch(${'options_prefix_'.$f_id}, '')._ch(${'options_suffix_'.$f_id}, '');
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
        
    $class_add = $field->class;
    if(function_exists('sw_filter_search_slidetoggle')) 
    sw_filter_search_slidetoggle();
    ?>
<div class="form_field <?php echo $class_add; ?>">
    <div class="form-group field_datepicker_box" style="<?php _che($field->style); ?>">
        <input id="search_option_<?php echo $f_id.$direction; ?>" type="text" class="form-control field_datepicker" placeholder="<?php echo $placeholder ?><?php echo $suf_pre; ?>" value="<?php echo search_value($f_id); ?>" />
    </div><!-- /.form-group -->
</div><!-- /.form-group -->