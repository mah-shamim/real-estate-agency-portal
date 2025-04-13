<?php if(config_item('tree_field_enabled') === TRUE):?>
<script>
    
    /* [START] TreeField */

    $(function() {
        $(".search-form .TREE-GENERATOR select").change(function(){
            var s_value = $(this).val();
            var s_name_splited = $(this).attr('name').split("_"); 
            var s_level = parseInt(s_name_splited[3]);
            var s_lang_id = s_name_splited[1];
            var s_field_id = s_name_splited[0].substr(6);
            // console.log(s_value); console.log(s_level); console.log(s_field_id);
            
            load_by_field($(this));
            
            // Reset child selection and value generator
            var generated_val = '';
            $(this).parent().parent()
            .find('select').each(function(index){
                // console.log($(this).attr('name'));
                if(index > s_level)
                {
                    //$(this).html('<option value=""><?php echo lang_check('No values found'); ?></option>');
                    
                    $(this).find("option:gt(0)").remove();
                    $(this).val('');
                    $(this).selectpicker('refresh');
                }
                else
                    generated_val+=$(this).find("option:selected").text()+" - ";
            });
            //console.log(generated_val);
            $("#sinputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);

        });

    });
    
    function load_by_field(field_element, autoselect_next, s_values_splited)
    {
        if (typeof autoselect_next === 'undefined') autoselect_next = false;
        if (typeof s_values_splited === 'undefined') s_values_splited = [];

        var s_value = field_element.val();
        var s_name_splited = field_element.attr('name').split("_"); 
        var s_level = parseInt(s_name_splited[3]);
        var s_lang_id = s_name_splited[1];
        var s_field_id = s_name_splited[0].substr(6);
        // console.log(s_value); console.log(s_level); console.log(s_field_id);
        
        // Load values for next select
        var ajax_indicator = field_element.parent().parent().parent().find('.ajax_loading');
        var select_element = $("select[name=option"+s_field_id+"_"+s_lang_id+"_level_"+parseInt(s_level+1)+"]");
        if(select_element.length > 0 && s_value != '')
        {
            ajax_indicator.css('display', 'block');
            $.getJSON( "<?php echo site_url('api/get_level_values_select'); ?>/"+s_lang_id+"/"+s_field_id+"/"+s_value+"/"+parseInt(s_level+1), function( data ) {
                //console.log(data.generate_select);
                //console.log("select[name=option"+s_field_id+"_"+s_lang_id+"_level_"+parseInt(s_level+1)+"]");
                ajax_indicator.css('display', 'none');
                
                select_element.html(data.generate_select);
                select_element.selectpicker('refresh');
                
                if(autoselect_next)
                {
                    if(s_values_splited[s_level+1] != '')
                    {
                        select_element.find('option').filter(function () { return $(this).html() == s_values_splited[s_level+1]; }).attr('selected', 'selected');
                        $('.search-form select.selectpicker').selectpicker('render');
                        load_by_field(select_element, true, s_values_splited);
                        
                        
                    }
                }
            });
        }
    }
    
    /* [END] TreeField */

</script>
<?php endif; ?>
<div class="wrap-search">
    <div class="container">

        <ul id="search_option_4" class="menu-onmap tabbed-selector">
            <li class="all-button"><a href="#"><?php echo lang_check('All'); ?></a></li>
            {options_values_li_4}
            <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
                <?php if(config_db_item('enable_qs') == 1): ?>
                <li class="list-property-button"><a href="<?php echo site_url('fquick/submission/'.$lang_code); ?>"><?php _l('Quick add listing'); ?></a></li>
                <?php else: ?>
                <li class="list-property-button"><a href="{myproperties_url}">{lang_Listproperty}</a></li>
                <?php endif; ?>
            <?php endif;?>
        </ul>
        
        <div class="search-form">
            <form class="form-inline">
                <input id="rectangle_ne" type="text" class="hide" />
                <input id="rectangle_sw" type="text" class="hide" />
            
                <!-- [START] TreeSearch -->
                <?php if(config_item('tree_field_enabled') === TRUE):?>
                <?php
                
                    $CI =& get_instance();
                    $CI->load->model('treefield_m');
                    $field_id = 64;
                    $drop_options = $CI->treefield_m->get_level_values($lang_id, $field_id);
                    $drop_selected = array();
                    echo '<div class="tree TREE-GENERATOR tree-'.$field_id.'">';
                    echo '<div class="field-tree">';
                    echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_0', $drop_options, $drop_selected, 'class="form-control selectpicker tree-input" id="sinputOption_'.$lang_id.'_'.$field_id.'_level_0'.'"');
                    echo '</div>';
    
                    $levels_num = $CI->treefield_m->get_max_level($field_id);
                    
                    if($levels_num>0)
                    for($ti=1;$ti<=$levels_num;$ti++)
                    {
                        $lang_empty = lang('treefield_'.$field_id.'_'.$ti);
                        if(empty($lang_empty))
                            $lang_empty = lang_check('Please select parent');
                        
                        echo '<div class="field-tree">';
                        echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_'.$ti, array(''=>$lang_empty), array(), 'class="form-control selectpicker tree-input" id="sinputOption_'.$lang_id.'_'.$field_id.'_level_'.$ti.'"');
                        echo '</div>';
                    }
                    echo '</div>';
                
                ?>
                
                <script>
                
                $(function() {
                    var load_val = '<?php echo search_value(64); ?>';
                    var s_values_splited = (load_val+" ").split(" - "); 
//            $.each(s_values_splited, function( index, value ) {
//                alert( index + ": " + value );
//            });
                    if(s_values_splited[0] != '')
                    {
                        var first_select = $('.tree-64').find('select:first');
                        first_select.find('option').filter(function () { return $(this).html() == s_values_splited[0]; }).attr('selected', 'selected');
                    
                        load_by_field(first_select, true, s_values_splited);
                    }
                });
                
                </script>
                <?php endif; ?>
                <!-- [END] TreeSearch -->
                
                <input id="search_option_smart" type="text" class="span6" value="{search_query}" placeholder="{lang_CityorCounty}" autocomplete="off" />
                <select id="search_option_2" class="span3 selectpicker">
                    {options_values_2}
                </select>
                <select id="search_option_3" class="span3 selectpicker nomargin">
                    {options_values_3}
                </select>
                
                <div class="advanced-form-part">
                <div class="form-row-space"></div>
                <input id="search_option_36_from" type="text" class="span3 mPrice DECIMAL" placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})" value="<?php echo search_value('36_from'); ?>" />
                <input id="search_option_36_to" type="text" class="span3 xPrice DECIMAL" placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})" value="<?php echo search_value('36_to'); ?>" />
                <input id="search_option_19" type="text" class="span3 Bathrooms INTEGER" placeholder="{options_name_19}"  value="<?php echo search_value(19); ?>" />
                <input id="search_option_20" type="text" class="span3 INTEGER" placeholder="{options_name_20}"  value="<?php echo search_value(20); ?>" />
                <div class="form-row-space"></div>
                <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
                <input id="booking_date_from" type="text" class="span3 mPrice" placeholder="{lang_Fromdate}" value="<?php echo search_value('date_from'); ?>" />
                <input id="booking_date_to" type="text" class="span3 xPrice" placeholder="{lang_Todate}" value="<?php echo search_value('date_to'); ?>" />
                <div class="form-row-space"></div>
                <?php endif; ?>
                <?php if(config_db_item('search_energy_efficient_enabled') === TRUE): ?>
                <select id="search_option_59_to" class="span3 selectpicker nomargin">
                    <option value="">{options_name_59}</option>
                    <option value="50">A</option>
                    <option value="90">B</option>
                    <option value="150">C</option>
                    <option value="230">D</option>
                    <option value="330">E</option>
                    <option value="450">F</option>
                    <option value="999999">G</option>
                </select>
                <div class="form-row-space"></div>
                <?php endif; ?>
                <label class="checkbox">
                <input id="search_option_11" type="checkbox" class="span1" value="true{options_name_11}" <?php echo search_value('11', 'checked'); ?>/>{options_name_11}
                </label>
                <label class="checkbox">
                <input id="search_option_22" type="checkbox" class="span1" value="true{options_name_22}" <?php echo search_value('22', 'checked'); ?>/>{options_name_22}
                </label>
                <label class="checkbox">
                <input id="search_option_25" type="checkbox" class="span1" value="true{options_name_25}" <?php echo search_value('25', 'checked'); ?>/>{options_name_25}
                </label>
                <label class="checkbox">
                <input id="search_option_27" type="checkbox" class="span1" value="true{options_name_27}" <?php echo search_value('27', 'checked'); ?>/>{options_name_27}
                </label>
                <label class="checkbox">
                <input id="search_option_28" type="checkbox" class="span1" value="true{options_name_28}" <?php echo search_value('28', 'checked'); ?>/>{options_name_28}
                </label>
                <label class="checkbox">
                <input id="search_option_29" type="checkbox" class="span1" value="true{options_name_29}" <?php echo search_value('29', 'checked'); ?>/>{options_name_29}
                </label>
                <label class="checkbox">
                <input id="search_option_32" type="checkbox" class="span1" value="true{options_name_32}" <?php echo search_value('32', 'checked'); ?>/>{options_name_32}
                </label>
                <label class="checkbox">
                <input id="search_option_30" type="checkbox" class="span1" value="true{options_name_30}" <?php echo search_value('30', 'checked'); ?>/>{options_name_30}
                </label>
                <label class="checkbox">
                <input id="search_option_33" type="checkbox" class="span1" value="true{options_name_33}" <?php echo search_value('33', 'checked'); ?>/>{options_name_33}
                </label>
                
                <label class="checkbox">
                <input id="search_option_is_featured" type="checkbox" class="span1" value="true<?php _l('is_featured'); ?>" <?php echo search_value('is_featured', 'checked'); ?>/><?php _l('is_featured'); ?>
                </label>

                
                </div>
                <br style="clear:both;" />
                <button id="search-start" type="submit" class="btn btn-info btn-large">&nbsp;&nbsp;{lang_Search}&nbsp;&nbsp;</button>
                <a id="search-start-map" href="#wrap-map" class="scroll"><button type="button" class="btn btn-success btn-large">{lang_ShowOnMap}</button></a>
                <img id="ajax-indicator-1" src="assets/img/ajax-loader.gif"  alt=""/>
            </form>
        </div>
    </div>
</div>
