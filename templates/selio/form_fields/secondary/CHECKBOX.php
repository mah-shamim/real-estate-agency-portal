<?php
    $col=6;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=1;
        $direction = '';
    }
    else
    {
        $placeholder = $direction;
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = _ch(${'options_prefix_'.$f_id}, '')._ch(${'options_suffix_'.$f_id}, '');
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
    
    $class_add = $field->class;
    
?>
<div class="input-field checkbox-field field_search_<?php echo _ch($f_id); ?> <?php echo _ch($class_add, ''); ?>" style="<?php echo _ch($field->style, ''); ?>">
    <input type="checkbox" name="search_option_<?php echo _ch($f_id); ?>" id="search_option_<?php echo _ch($f_id); ?>"    value="true<?php _che(${'options_name_'.$f_id}); ?>" <?php echo search_value($f_id, 'checked'); ?>>
    <label for="search_option_<?php echo _ch($f_id); ?>">
        <span></span>
        <small><?php _che(${'options_name_'.$f_id}); ?></small>
        <b class="count"></b>
    </label>
</div>