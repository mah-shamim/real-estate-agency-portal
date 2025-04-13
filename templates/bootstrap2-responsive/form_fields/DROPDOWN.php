<?php
    $col=3;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=3;
        $direction = '';
    } else
    {
        $placeholder.= ', '.lang_check($direction);
        $direction=strtolower('_'.$direction);
    }
    
    $f_id = $field->id;
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' span'.$col;
    
    //bootstrap 2
    $class_add = str_replace(array('col-md-','col-sm-','col-xl-','col-xs-'), 'span', $class_add);
    
?>
<select id="search_option_<?php echo $f_id; ?><?php echo $direction;?>" class="<?php echo $class_add; ?> selectpicker form-control" style="<?php _che($field->style); ?>">
    <?php _che(${'options_values_'.$f_id}); ?>
</select>

                