<?php
    $trans = array();
    $trans['FROM'] = lang_check('Min' );
    $trans['TO'] = lang_check('Max' );
    $trans['NONE'] = '';
    $trans[''] = '';

    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=3;
        $direction = '';
    }
    else
    {
        $placeholder = $trans[$field->direction].' '.$placeholder;
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = _ch(${'options_prefix_'.$f_id}, '')._ch(${'options_suffix_'.$f_id}, '');
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
        
    $class_add = $field->class;
    
    ?>


<?php
/* if definend '|', example '2000,3000,5000,10000,20000|3000,5000,10000,20000,100000' */
if($direction != 'NONE' && !empty(${'options_obj_'.$f_id}->values) && strpos(${'options_obj_'.$f_id}->values, '|') !== FALSE) :?>
<?php
   $from_to_values_arr = explode('|', ${'options_obj_'.$f_id}->values);

   $value= '';
   if($direction == '_from') {
       $values = $from_to_values_arr[0];
   } else if($direction == '_to') {
       $values = $from_to_values_arr[1];
   }
   /* parse values */
   $values_arr = explode(',', $values);
   
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
                        <li data-value="<?php echo _ch($value);?>"><?php echo _ch($value);?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div><!-- /.form-group -->
    </div>
<?php else: ?>
    <div class="form_field <?php echo $class_add; ?> sf_input">
        <div class="form-group  field_search_<?php echo _ch($f_id); ?>" style="<?php _che($field->style); ?>">
            <input id="search_option_<?php echo $f_id.$direction; ?>" type="text" class="form-control" placeholder="<?php echo $placeholder ?><?php echo $suf_pre; ?>" value="<?php echo search_value($f_id); ?>" />
        </div><!-- /.form-group -->
    </div>
<?php endif;?>