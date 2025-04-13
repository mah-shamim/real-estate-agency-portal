<?php

$CI = &get_instance();
$CI->load->model('estate_m');
$CI->load->model('option_m');
$max_price = 250000;
$results_obj = $CI->estate_m->get_by(array('language_id'=>$lang_id), FALSE, 1, $CI->estate_m->get_table_name().'_lang.field_36_int DESC');
if($results_obj and !empty($results_obj) && isset($results_obj[0])) {
    if(!empty($results_obj[0]->field_36_int) &&  $results_obj[0]->field_36_int > 25000) 
        $max_price = $results_obj[0]->field_36_int+1000;
}
$field_data = $CI->option_m->get_field_data(36, $lang_id);

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


if(empty($field_data)) {
    echo '<div class="'._ch($class_add).'">';
    echo '<div class="clearfix"></div><div class="alert alert-danger">';
    echo lang_check("PRICE RANGE can't load missng field #36");
    echo '</div>';
    echo '</div>';
    return;
}

$suf = $field_data->suffix;
$pre = $field_data->prefix;

?>

<div class="form-control <?php echo _ch($class_add); ?>" style="margin:0; margin-right: 10px;">
    <div class="form-group">
        <div class="scale-range sw_scale_range" id="nonlinear-price">
            <div class="hidden config-range"
              data-min="0"
              data-max="<?php echo _ch($max_price, '');?>"
              data-sufix="<?php echo _ch($suf, '');?>"
              data-prefix="<?php echo _ch($pre, '');?>"
              data-infinity="false"
              data-predifinedMin="<?php echo search_value('36_from'); ?>"
              data-predifinedMax="<?php echo search_value('36_to'); ?>"
            >
            </div>
            <div class="scale-range-value">
                <span class="scale-range-label"><?php echo lang_check('Price');?></span>
                <?php //echo _ch__('from','nexos');?> 
                <span class="nonlinear-min"></span> -
                <?php //echo _ch__('to','nexos');?> 
                <span class="nonlinear-max"></span>
            </div>
            <div class="nonlinear"></div>
            <input id="search_option_36_from" name="search_option_36_from" type="text" class="value-min hidden" value="<?php echo search_value('36_from'); ?>" />
            <input id="search_option_36_to" name="search_option_36_to" type="text" class="value-max hidden" value="<?php echo search_value('36_to'); ?>" />
        </div>
    </div>
</div>