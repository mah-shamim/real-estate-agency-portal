<?php
    $col=6;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
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
    if(empty($class_add))
        $class_add = ' col-sm-6';
    
    $class_add = str_replace(array('col-md-','col-sm-','col-xl-','col-xs-'), 'span', $class_add);
    
?>
<label class="checkbox">
<?php echo sw_get_option_icon($f_id);?>
<input rel="<?php _che(${'options_name_'.$f_id}); ?>" id="search_option_<?php echo $f_id; ?>" name="search_option_<?php echo $f_id; ?>" type="checkbox" class="span1" value="true<?php _che(${'options_name_'.$f_id});?>" <?php echo search_value($f_id, 'checked'); ?>/><?php _che(${'options_name_'.$f_id}); ?>
</label>