<?php
    $values_arr = ${'options_values_arr_4'};
    $f_id = 4;
    $val = search_value($f_id);

    if(in_array($page_title,$values_arr)!== FALSE){
        global $_GET;
        $val = $page_title;
    }
    
    $CI = &get_instance();

    if(!function_exists('_get_purpose')) {
        function _get_purpose($CI)
        {
            if(isset($CI->select_tab_by_title))
            if($CI->select_tab_by_title != '')
            {
                $CI->data['purpose_defined'] = $CI->select_tab_by_title;
                return $CI->select_tab_by_title;
            }
            
            if(isset($CI->data['is_purpose_sale'][0]['count']))
            {
                $CI->data['purpose_defined'] = lang('Sale');
                return lang('Sale');
            }
            
            if(isset($CI->data['is_purpose_rent'][0]['count']))
            {
                $CI->data['purpose_defined'] = lang('Rent');
                return lang('Rent');
            }
            
            if(search_value(4))
                return search_value(4);
            
            return '';
            
        }
    }
    
    $purpose = _get_purpose($CI);
    $purpose = array_search(strtolower($purpose), array_map('strtolower', $values_arr));
    if($purpose !== FALSE) {
        $val =$values_arr[$purpose];
    }
    

    if(function_exists('sw_filter_search_slidetoggle')) 
    sw_filter_search_slidetoggle();
?>

<ul class="menu-onmap tabbed-selector">
    <li class="all-button">
        <label>
            <?php echo lang_check('All'); ?>
            <input type="radio" rel="<?php echo lang_check('All'); ?>" name="search_option_4" value="">
        </label>
    </li>
    <?php foreach ($options_values_arr_4 as $key=>$value):if(empty($value)) continue;?>
    <li class="<?php echo (strtolower(_get_purpose($CI))==strtolower($value)) ? 'active' : '';?>">
        <label class="">
            <?php _che($value);?>
            <input type="radio" rel="<?php _che($value);?>" name="search_option_4" value="<?php _che($value);?>" <?php echo (strtolower(_get_purpose($CI))==strtolower($value)) ? 'checked="checked"' : '';?>>
        </label>
    </li>
    <?php endforeach;?>
</ul>

<style>
    .banner-content {
        margin-bottom: 65px;
    }
    
  .form_sec {
        padding-top: 70px;
    }
    
    @media (max-width: 767px){
        .banner-content {
            margin-bottom: 20px;
        }
    }
</style>

