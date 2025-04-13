<?php if(config_item('tree_field_enabled') === TRUE):?>

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
    
    $suf_pre = _ch(${'options_prefix_'.$f_id}, '')._ch(${'options_suffix_'.$f_id}, '');
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
        
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' span'.$col;
    
    //bootstrap 2
    $class_add = str_replace(array('col-md-','col-sm-','col-xl-','col-xs-'), 'span', $class_add);
    
?>

<script>
    
    /* [START] TreeField */
    var load_val_<?php echo $f_id;?> = false;
    
    $(function() {
        $(".search-form .TREE-GENERATOR#TREE-GENERATOR_ID_<?php echo $f_id;?> select").change(function(){
            console.log('change');
            
            // if init treefield tree with value
            if(load_val_<?php echo $f_id;?>) return false;
            
            var s_value = $(this).val();
            var s_name_splited = $(this).attr('name').split("_"); 
            var s_level = parseInt(s_name_splited[3]);
            var s_lang_id = s_name_splited[1];
            var s_field_id = s_name_splited[0].substr(6);
            // console.log(s_value); console.log(s_level); console.log(s_field_id);
            
            load_by_field($(this));
            
            // Reset child selection and value generator
            var generated_val = '';
            var last_selected_numeric = '';
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
                {
                    last_selected_numeric = $(this).val();
                    generated_val+=$(this).find("option:selected").text()+" - ";
                }    
            });
            //console.log(generated_val);
            $("#sinputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);
            
            $("#inputOption_"+s_lang_id+"_"+s_field_id).attr('rel', last_selected_numeric);
            $("#inputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);
            $("#inputOption_"+s_lang_id+"_"+s_field_id).trigger("change");

        });
        
        

    });
    
    function load_by_field(field_element, autoselect_next, s_values_splited)
    {
        if (typeof autoselect_next === 'undefined') autoselect_next = false;
        if (typeof s_values_splited === 'undefined') s_values_splited = [];
        
       /* console.log('load_by_field');*/
        
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
                
                //cuSel({changedEl: ".select_styled", visRows: 8, scrollArrows: true});
                
                if(autoselect_next)
                {
                    if(s_values_splited[s_level+1] != '')
                    {
                        var id = select_element.find('option').filter(function () { return $(this).html() == s_values_splited[s_level+1]; }).attr('selected', 'selected').val();
                        /*console.log(id.toString())*/
                        select_element.selectpicker('val', id);
                        load_by_field(select_element, true, s_values_splited);
                        
                        
                    } else {
                         load_val_<?php echo $f_id;?> = false;
                    }
                }
            });
        }
    }
    
    /* [END] TreeField */

</script>

<!-- [START] TreeSearch -->
<?php if(config_item('tree_field_enabled') === TRUE):?>
<?php

    $CI =& get_instance();
    $CI->load->model('treefield_m');
    $field_id = $f_id;
    $drop_options = $CI->treefield_m->get_level_values($lang_id, $field_id);
    $drop_selected = array();
    echo '<div class="tree TREE-GENERATOR" id="TREE-GENERATOR_ID_'.$f_id.'">';
    echo '<div class="field-tree">';
    echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_0', $drop_options, $drop_selected, 'class="form-control selectpicker base no-padding tree-input " id="sinputOption_'.$lang_id.'_'.$field_id.'_level_0'.'"');
    echo '</div>';

    $levels_num = $CI->treefield_m->get_max_level($field_id);
    
    if($levels_num>0)
    for($ti=1;$ti<=$levels_num;$ti++)
    {
        $lang_empty = lang('treefield_'.$field_id.'_'.$ti);
        if(empty($lang_empty))
            $lang_empty = lang_check('Please select parent');
        
        echo '<div class="field-tree">';
        echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_'.$ti, array(''=>$lang_empty), array(), 'class="form-control selectpicker base no-padding tree-input " id="sinputOption_'.$lang_id.'_'.$field_id.'_level_'.$ti.'"');
        echo '</div>';
    }
    echo '</div>';

?>

                <script>
                
                $(window).load(function() {
                    var load_val = '<?php echo search_value($field_id); ?>';
                    <?php if(search_value($field_id)):?>
                        load_val_<?php echo $f_id;?> = true;
                    <?php endif;?>
                        
                    var s_values_splited = (load_val+" ").split(" - "); 
                    
                    if(s_values_splited[0] != '')
                    {
                        var first_select = $('.TREE-GENERATOR#TREE-GENERATOR_ID_<?php echo $f_id;?>').find('select:first');
                        var id = first_select.find('option').filter(function () { return $(this).html() ==  s_values_splited[0]; }).attr('selected', 'selected').val();
                        /*console.log(id)*/
                        
                        /* test fix */
                        first_select.val(id)
                        first_select.selectpicker('refresh')
                        /* end test fix */
                        
                        //first_select.selectpicker('val', id);
                        load_by_field(first_select, true, s_values_splited);
                    }
                    
                });
                
                </script>

<?php endif; ?>
<!-- [END] TreeSearch -->

<?php 

//echo form_input('option' . $field_id . '_' . $lang_id, '', 'class="form-control tree-input-value hidden skip" id="inputOption_' . $lang_id . '_' . $field_id . '" rel=""'); 

?>

<?php endif; ?>
