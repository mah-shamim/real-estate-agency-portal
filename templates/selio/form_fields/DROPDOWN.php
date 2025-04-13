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
    $values_arr = ${'options_values_arr_'.$f_id};
    
    if(function_exists('sw_filter_search_slidetoggle')) 
    sw_filter_search_slidetoggle();
?>

<div class="form_field <?php echo $class_add; ?> sf_input">
    <div class="form-group  field_search_<?php echo _ch($f_id); ?>" style="<?php _che($field->style); ?>">

        <div class="drop-menu">
            <div class="select">
                <?php reset($values_arr); if(key($values_arr)=='' && key($values_arr) !=0):?>
                    <span><?php echo current($values_arr);?></span>
                <?php else:?>
                    <span><?php echo $placeholder;?></span>
                <?php endif;?>
                <i class="fa fa-angle-down"></i>
            </div>
            <input type="hidden" id="search_option_<?php echo _ch($f_id)._ch($direction,''); ?>" name="search_option_<?php echo _ch($f_id)._ch($direction,''); ?>"  value="<?php echo search_value($f_id.$direction); ?>" />
            <ul class="dropeddown">
                <?php reset($values_arr); if(key($values_arr)=='' && key($values_arr) !=0):?>
                    <li><?php echo current($values_arr);?></li>
                <?php else:?>
                    <li><?php echo $placeholder;?></li>
                <?php endif;?>
                <?php if(sw_count($values_arr)>0) foreach ($values_arr as $key => $value):?>
                    <?php $value = trim($value); if(empty($value)|| (empty($value) && empty($value)!=0))continue;?>
                    <li data-value="<?php echo _ch($value);?>"><?php echo _ch(${'options_prefix_'.$f_id}, '')._ch($value)._ch(${'options_suffix_'.$f_id}, '');?></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div><!-- /.form-group -->
</div>

