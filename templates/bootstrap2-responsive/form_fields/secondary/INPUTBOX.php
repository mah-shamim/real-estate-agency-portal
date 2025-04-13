<?php
    $col=12;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
    }
    else
    {
        $placeholder.= ', '.lang_check($direction);
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = _ch(${'options_prefix_'.$f_id}, '')._ch(${'options_suffix_'.$f_id}, '');
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
        
    $class_add = $field->class;
    if(empty($class_add))
    {
        $class_add = ' col-sm-'.$col;
    }
    $class_add = str_replace(array('col-md-','col-sm-','col-xl-','col-xs-'), 'span', $class_add);
?>
<div class="<?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <input type="text" data-option_id="<?php echo $f_id; ?>" class="form-control input_am<?php echo $direction;?>  id_<?php echo $f_id; ?> <?php echo $class_add; ?>" placeholder="<?php echo $placeholder ?><?php echo $suf_pre; ?>">
</div><!-- /.form-group -->