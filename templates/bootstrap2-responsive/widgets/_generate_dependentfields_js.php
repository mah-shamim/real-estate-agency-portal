/* [START] Dependent fields */
$(document).ready(function(){
    //console.log('Dependent fields for search loading');
    <?php 
    // Fetch dependent fields
    $CI =& get_instance();
    $CI->load->model('dependentfield_m');
    $dependent_fields = $CI->dependentfield_m->get();
    $dependent_fields_prepare = array();
    foreach($dependent_fields as $key_d_field=>$d_field)
    {
        $dependent_fields_prepare[$d_field->field_id][$d_field->selected_index] = $d_field->hidden_fields_list;
    }

    $id_lang = $lang_id;

    foreach($dependent_fields_prepare as $d_field_id=>$d_field_indexes):
    ?>
    //console.log('fields: <?php echo $d_field_id; ?>');

    $(".search-form form select[name='option<?php echo $d_field_id.'_'.$id_lang; ?>'], "+
        ".search-form form #search_option_<?php echo $d_field_id; ?>,"+
        
      ".search-form form input[rel][name='option<?php echo $d_field_id.'_'.$id_lang; ?>']").change(function () {
      
        var index = $(this).children('option:selected').index();
        var parent_elem = $(this).parent().parent();
        var parent_elem_hide = $(this).parent();

        var index_tree = $(this).attr('rel');
        if (typeof index_tree !== typeof undefined && index_tree !== false) {
          index = index_tree;
          parent_elem = parent_elem.parent();
          parent_elem_hide = parent_elem_hide.parent();
        }

        $('.dependenthide').removeClass('dependenthide');

        //console.log('changed1');

        if (typeof index_tree !== typeof undefined && index_tree !== false) {
          // include all parent elements
          $(this).parent().parent().find('select').each(function(){
            if($(this).val() != '')
            {
                hide_related_<?php echo $d_field_id.'_'.$id_lang; ?>(parent_elem, parent_elem_hide, $(this).val());
            }
          });
        }
        else
        {
            hide_related_<?php echo $d_field_id.'_'.$id_lang; ?>(parent_elem, parent_elem_hide, index);
        }

    });
    
    $(".search-form form #search_option_<?php echo $d_field_id; ?>.tabbed-selector li a").click(function () {

        var index = $(this).parent().attr('class').replace('cat_','').replace('active','');
        index = $.trim(index);
        var parent_elem = '';
        var parent_elem_hide = '';


        $('.dependenthide').removeClass('dependenthide');

        //console.log(index);
               
        if (typeof index_tree !== undefined)
        {
            hide_related_<?php echo $d_field_id.'_'.$id_lang; ?>(parent_elem, parent_elem_hide, index);
        }

    });

    //$("select[name='option<?php echo $d_field_id.'_'.$id_lang; ?>']").trigger('change');

    function hide_related_<?php echo $d_field_id.'_'.$id_lang; ?>(parent_elem, parent_elem_hide, index)
    {
        <?php foreach($d_field_indexes as $d_selected_index=>$d_hidden_fields_list): ?>
        if(index == '<?php echo $d_selected_index; ?>')
        {
            // console.log('Hide now it all ;-)');
            <?php 
            $hidden_fields_list = explode(',', $d_hidden_fields_list);
            $generate_selector_list = array();
            $generate_selector = '';
            foreach($hidden_fields_list as $hide_field_id)
            {
                //for standard form
                $generate_selector_list[] = "[data-id='search_option_".$hide_field_id."_from']";
                $generate_selector_list[] = "[data-id='search_option_".$hide_field_id."_to']";
                $generate_selector_list[] = "[data-id='search_option_".$hide_field_id."']";
                $generate_selector_list[] = "#search_option_".$hide_field_id."_from";
                $generate_selector_list[] = "#search_option_".$hide_field_id."_to";
                $generate_selector_list[] = "#search_option_".$hide_field_id;
                //for secondr form
                $generate_selector_list[] = "[data-option_id='".$hide_field_id."']";
            }
            $generate_selector = implode(',', $generate_selector_list);
            ?>

            // empty values
            $("<?php echo $generate_selector; ?>").not('.skip-input').each( function() {
                if(this.type=='text' || this.type=='textarea'){
                    this.value = '';
                }
                else if(this.type=='radio' || this.type=='checkbox'){
                    this.checked=false;
                }
                else if(this.type=='select-one' || this.type=='select-multiple'){
                    this.value ='';
                    if(this.value != '')this.value ='-';
                }
                else if(this.type == 'hidden')
                {
                    this.value ='';
                } else if($(this).hasClass('dropdown-toggle')) {
                    
                }
            });

            // hide all related
            $("<?php echo $generate_selector; ?>").each( function() {
                if(this.type=='text' || this.type=='textarea'){
                    $(this).not('form').addClass('dependenthide');
                }
                else if(this.type=='radio' || this.type=='checkbox'){
                    $(this).parent().not('form').addClass('dependenthide');
                }
                else if(this.type=='select-one' || this.type=='select-multiple'){
                    $(this).not('form').addClass('dependenthide');
                }
                else if(this.type == 'hidden')
                {
                    $(this).not('form').addClass('dependenthide');
                } else if($(this).hasClass('dropdown-toggle')) {
                    $(this).parent().not('form').addClass('dependenthide');
                }

            });

            // hide secondary form if no elements visible

            if($('form.secondary-form div:not(.dependenthide) input[data-option_id]').length == 0)
            {
                $('form.secondary-form').parent().parent().addClass('dependenthide');
            }

        }
        <?php endforeach; ?>
    }

    <?php endforeach; ?>

    $(".search-form .TREE-GENERATOR select").trigger('change');

});

/* [END] Dependent fields */