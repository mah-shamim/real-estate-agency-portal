<?php
    $col=12;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
    } else
    {
        $placeholder.= ', '.lang_check($direction);
        $direction=strtolower('_'.$direction);
    }
    
    
    $f_id = $field->id;
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = 'span'.$col;
    
    $class_add = str_replace(array('col-md-','col-sm-','col-xl-','col-xs-'), 'span', $class_add);
?>

    <div class="<?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <select data-option_id="<?php echo $f_id; ?>" class="nomargin input_am<?php echo $direction;?> id_<?php echo $f_id; ?> <?php echo $class_add; ?>">
        <?php _che(${'options_values_'.$f_id}); ?>
    </select>
    </div>