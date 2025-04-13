<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/editable_table/mindmup-editabletable.js"></script>
    <script>
    
    // init copy features
$(document).ready(function(){
    
    $('#copy-lang').click(function(){
        $('.tabbable .tab-pane.active select, '+
          '.tabbable .tab-pane.active input[type=checkbox], '+
          '.tabbable .tab-pane.active input[type=text], '+
          '.tabbable .tab-pane.active textarea').each(function(){
            
            if($(this).attr('id') == null)return;
            
            var option_id = $(this).attr('id').substr($(this).attr('id').lastIndexOf('_')+1);
            var lang_active_id = $(this).attr('name').substr($(this).attr('name').lastIndexOf('_')+1);
            var option_val = $(this).val();
            var is_input = $(this).is('input');
            var is_input_text = $(this).is('input[type=text]');
            var is_area = $(this).is('textarea');
            var r_id = $(this).attr('id');
            var is_level = false;
            var is_tree_input = $(this).hasClass('tree-input-value');
            var is_level_splited;
            var is_level_parent_id;
            var is_HTMLTABLE = $(this).hasClass('HTMLTABLE');
            var is_PEDIGREE = $(this).hasClass('PEDIGREE');
            var curr_level = 0;
            
            if($(this).hasClass('skip-input'))
                return;

            if(!$(this).attr('id'))return;
            
            //if(is_tree_input)
            //    console.log('test: '+r_id);
            
            if(r_id.indexOf("level") > 0)
            {
                is_level_splited = r_id.split("_"); 
                is_level = true;
                option_id = is_level_splited[2];
            }
            
            if(is_input)
            {
                if($(this).attr('type') == 'checkbox')
                {
                    option_val = $(this).is(':checked');
                }
                else
                {
                    
                }
            }
            else if(is_HTMLTABLE)
            {
                option_val = $(this).parent().find('table > tbody').clone();
            }
            else if(is_PEDIGREE)
            {
                option_val = $(this).parent().find('ul.tree');
            }
            else if(is_area)
            {
                option_val = $(this).val();
                if(typeof CKEDITOR !== 'undefined' && typeof CKEDITOR.instances[r_id] !== 'undefined') {
                    option_val = CKEDITOR.instances[r_id].getData();
                }
            }
            else if(is_level)
            {
                curr_level = parseInt(is_level_splited[4]);
                is_level_parent_id = 0;
                if(curr_level > 0)
                {
                    is_level_parent_id = $('.controls #inputOption_'+is_level_splited[1]+'_'+option_id+'_level_'+parseInt(curr_level-1)).val();
                }

                option_val = $(this).val();
            }
            else
            {
                option_val = $(this).prop('selectedIndex');
            }
            
//            console.log('option_id: '+option_id);
//            console.log('lang_active_id: '+lang_active_id);
//            console.log('option_val: '+option_val);
//            console.log('is_input: '+is_input);
            
            $('.nav.nav-tabs li.lang a').each(function(){
                if(!$(this).parent().hasClass('active'))
                {
                    var lang_key = $(this).attr('href').substr(1);
                    
//                    console.log('lang_key: '+lang_key);
//                    console.log('#inputOption_'+lang_key+'_'+option_id);
                    
                    if(is_input)
                    {
                        if(is_tree_input)
                        {
                            $('#inputOption_'+lang_key+'_'+option_id).parent().parent().find('select').val('');
                            $('#inputOption_'+lang_key+'_'+option_id).val('');
                            
//                            console.log('#inputOption_'+lang_key+'_'+option_id);
//                            console.log($('#inputOption_'+lang_key+'_'+option_id).val());
                        }
                        else if(is_input_text)
                        {
                            if($('#inputOption_'+lang_key+'_'+option_id).val() == '' ||
                               $.isNumeric(option_val))
                                $('#inputOption_'+lang_key+'_'+option_id).val(option_val);
                        }
                        else
                        {
                            $('#inputOption_'+lang_key+'_'+option_id).prop('checked', option_val);
                        }
                    }
                    else if(is_PEDIGREE)
                    {
                        //$('#inputOption_'+lang_key+'_'+option_id).parent().find('ul.tree').html(option_val.html());
                    }
                    else if(is_HTMLTABLE)
                    {
                        // replace based on dropdown translation
                        // console.log('lang_from_id: '+lang_active_id);
                        // console.log('lang_to_id: '+lang_key);
                        // console.log(option_id);
                        
                        //col_1_76_0
                        var option_val_clone = option_val.clone();
                        option_val_clone.find('tr td').each(function( index ) {
                            var col_index = $(this).index();
                            var row_index = $(this).parent().index();
                            var cur_content = $(this).html();
                            var lang_col_from = $('#col_'+lang_active_id+'_'+option_id+'_'+col_index);
                            var lang_col_to = $('#col_'+lang_key+'_'+option_id+'_'+col_index);
                            
                            if(lang_col_to.length == 1 && cur_content != '')
                            {
                                var dropdown_index = lang_col_from.find("span:contains('"+cur_content+"')").index();
                                var rep_text = lang_col_to.find('span').eq(dropdown_index).html();
                                option_val_clone.find('tr').eq(row_index).find('td').eq(col_index).html(rep_text);
                                
                                //console.log(dropdown_index + '|' + $( this ).html() );
                                //console.log(rep_text);
                            }
                        });
                        
                        $('#inputOption_'+lang_key+'_'+option_id).parent().find('table > tbody').html(option_val_clone.html());
                        
                        table_add_events(option_id+'_'+lang_key);
                        save_changes_table(option_id+'_'+lang_key);
                    }
                    else if(is_area)
                    {
                        var option_val_lang = $('#inputOption_'+lang_key+'_'+option_id).val();

                        if(typeof CKEDITOR !== 'undefined' && typeof CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id] !== 'undefined') {
                            option_val_lang = CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id].getData();
                        }
                        
                        if(option_val_lang == '' ||
                           option_val_lang == '<br>' )
                        {
                            $('#inputOption_'+lang_key+'_'+option_id).val(option_val).blur();
                            if(typeof CKEDITOR !== 'undefined' && typeof CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id] !== 'undefined') {
                                CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id].setData(option_val);
                            }
                        }
                    }
                    else if(is_level)
                    {
                        if (typeof load_and_select_index === 'function') {
                            load_and_select_index($('#inputOption_'+lang_key+'_'+option_id+'_level_'+is_level_splited[4]), option_val, is_level_parent_id);
                        }
                    }
                    else
                    {
                        //console.log('#inputOption_'+lang_key+'_'+option_id);
                        //console.log(option_val);
                        $('#inputOption_'+lang_key+'_'+option_id).prop('selectedIndex', parseInt(option_val)); 
                        $('#inputOption_'+lang_key+'_'+option_id).trigger('change');
                    }
                }
            });
        });
        
        return false;
    });
    
    $('#translate-lang').click(function(){
        $('.tabbable .tab-pane.active select, '+
          '.tabbable .tab-pane.active input[type=checkbox], '+
          '.tabbable .tab-pane.active input[type=text], '+
          '.tabbable .tab-pane.active textarea').each(function(){

            if($(this).attr('id') == null)return;
            
            var option_id = $(this).attr('id').substr($(this).attr('id').lastIndexOf('_')+1);
            var lang_active_id = $(this).attr('name').substr($(this).attr('name').lastIndexOf('_')+1);
            var option_val = $(this).val();
            var is_input = $(this).is('input');
            var is_input_text = $(this).is('input[type=text]');
            var is_area = $(this).is('textarea');
            var r_id = $(this).attr('id');
            var is_level = false;
            var is_tree_input = $(this).hasClass('tree-input-value');
            var is_level_splited;
            var is_level_parent_id;
            var is_HTMLTABLE = $(this).hasClass('HTMLTABLE');
            var is_PEDIGREE = $(this).hasClass('PEDIGREE');
            var curr_level = 0;
            
            if($(this).hasClass('skip-input'))
                return;

            if(!$(this).attr('id'))return;
            
            if(r_id.indexOf("level") > 0)
            {
                is_level_splited = r_id.split("_"); 
                is_level = true;
                option_id = is_level_splited[2];
            }
            
            if(is_input)
            {
                if($(this).attr('type') == 'checkbox')
                {
                    option_val = $(this).is(':checked');
                }
                else
                {
                    
                }
            }
            else if(is_HTMLTABLE)
            {
                option_val = $(this).parent().find('table > tbody').clone();
            }
            else if(is_PEDIGREE)
            {
                option_val = $(this).parent().find('ul.tree');
            }
            else if(is_area)
            {
                option_val = $(this).val();
                if(typeof CKEDITOR !== 'undefined' && typeof CKEDITOR.instances[r_id] !== 'undefined') {
                    option_val = CKEDITOR.instances[r_id].getData();
                }
            }
            else if(is_level)
            {
                curr_level = parseInt(is_level_splited[4]);
                is_level_parent_id = 0;
                if(curr_level > 0)
                {
                    is_level_parent_id = $('.controls #inputOption_'+is_level_splited[1]+'_'+option_id+'_level_'+parseInt(curr_level-1)).val();
                }

                option_val = $(this).val();
            }
            else
            {
                option_val = $(this).prop('selectedIndex');
            }
            
            $('.nav.nav-tabs li.lang a').each(function(){
                if(!$(this).parent().hasClass('active'))
                {
                    var lang_key = $(this).attr('href').substr(1);
                    //console.log('lang_key: '+lang_key);
                    
                    if(is_input)
                    {
                        if(is_tree_input)
                        {
                            $('#inputOption_'+lang_key+'_'+option_id).parent().parent().find('select').val('');
                            $('#inputOption_'+lang_key+'_'+option_id).val('');
                            
//                            console.log('#inputOption_'+lang_key+'_'+option_id);
//                            console.log($('#inputOption_'+lang_key+'_'+option_id).val());
                        }
                        else if(is_input_text)
                        {
                            if($.isNumeric(option_val))
                            {
                                $('#inputOption_'+lang_key+'_'+option_id).val(option_val);
                            }
                            else if($('#inputOption_'+lang_key+'_'+option_id).val() == '' && option_val != '')
                            {
                                $.getJSON($('#translate-lang').attr('rel'), {from: lang_active_id, to: lang_key, value: option_val}, function( data ) {
                                    if(data.result != '')
                                    {
                                        $('#inputOption_'+lang_key+'_'+option_id).val(data.result);
                                    }
                                    else
                                    {
                                        $('#inputOption_'+lang_key+'_'+option_id).val(option_val);
                                    }
                                });
                            }  
                        }
                        else
                        {
                            //console.log('#inputOption_'+lang_key+'_'+option_id);
                            //console.log(option_val);
                            //$('#inputOption_'+lang_key+'_'+option_id).val(option_val);
                            $('#inputOption_'+lang_key+'_'+option_id).prop('checked', option_val);
                        }
                    }
                    else if(is_PEDIGREE)
                    {
                        
                    }
                    else if(is_HTMLTABLE)
                    {
                        // replace based on dropdown translation
                        //console.log('lang_from_id: '+lang_active_id);
                        //console.log('lang_to_id: '+lang_key);
                        //col_1_76_0
                        var option_val_clone = option_val.clone();
                        option_val_clone.find('tr td').each(function( index ) {
                            var col_index = $(this).index();
                            var row_index = $(this).parent().index();
                            var cur_content = $(this).html();
                            var lang_col_from = $('#col_'+lang_active_id+'_'+option_id+'_'+col_index);
                            var lang_col_to = $('#col_'+lang_key+'_'+option_id+'_'+col_index);
                            
                            if(lang_col_to.length == 1 && cur_content != '')
                            {
                                var dropdown_index = lang_col_from.find("span:contains('"+cur_content+"')").index();
                                var rep_text = lang_col_to.find('span').eq(dropdown_index).html();
                                option_val_clone.find('tr').eq(row_index).find('td').eq(col_index).html(rep_text);
                                
                                //console.log(dropdown_index + '|' + $( this ).html() );
                                //console.log(rep_text);
                            }
                        });
                        
                        $('#inputOption_'+lang_key+'_'+option_id).parent().find('table > tbody').html(option_val_clone.html());
                        
                        table_add_events(option_id+'_'+lang_key);
                        save_changes_table(option_id+'_'+lang_key);
                    }
                    else if(is_area)
                    {
                        
                        var option_val_lang = $('#inputOption_'+lang_key+'_'+option_id).val();

                        if(typeof CKEDITOR !== 'undefined' && typeof CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id] !== 'undefined') {
                            option_val_lang = CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id].getData();
                        }
                        if(option_val_lang == '' ||
                           option_val_lang == '<br>' )
                        {
                            $.getJSON($('#translate-lang').attr('rel'), {from: lang_active_id, to: lang_key, value: option_val}, function( data ) {
                                if(data.result != '')
                                {
                                    $('#inputOption_'+lang_key+'_'+option_id).val(data.result).blur();
                                    if(typeof CKEDITOR !== 'undefined' && typeof CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id] !== 'undefined') {
                                        CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id].setData(data.result);
                                    }
                                }
                                else
                                {
                                    $('#inputOption_'+lang_key+'_'+option_id).val(option_val).blur();
                                    CKEDITOR.instances['inputOption_'+lang_key+'_'+option_id].setData(option_val);
                                }
                            });
                        }
                    }
                    else if(is_level)
                    {
                        if (typeof load_and_select_index === 'function') {
                            load_and_select_index($('#inputOption_'+lang_key+'_'+option_id+'_level_'+is_level_splited[4]), option_val, is_level_parent_id);
                        }
                    }
                    else
                    {
                        //console.log('#inputOption_'+lang_key+'_'+option_id);
                        //console.log(option_val);
                        $('#inputOption_'+lang_key+'_'+option_id).prop('selectedIndex', parseInt(option_val)); 
                        $('#inputOption_'+lang_key+'_'+option_id).trigger('change');
                    }
                }
            });
        });
        
        return false;
    });
        
});

<?php if(isset($package_num_amenities_limit)): ?>
$(document).ready(function(){

    $('.control-group .controls input[type=checkbox]').change(function(event){
        var selected_checkboxes = $('.tab-pane.active .control-group .controls input[type=checkbox]:checked').length;
        
        if(selected_checkboxes > <?php echo $package_num_amenities_limit; ?>)
        {
            $(this).prop('checked', false);
            ShowStatus.show('<?php echo lang_check('Limitation by package'); ?>: '+'<?php echo $package_num_amenities_limit; ?>');
        }
    });

});
<?php endif; ?>
    
    $(document).ready(function(){

    });    
    

    </script>
    
    <?php if(config_item('onmouse_gallery_enabled') === TRUE): ?>    
    <style>
    .onmouse_gallery-title-files {
        padding: 0;
        margin: 0;
        list-style: none;
        height: 30px;
        overflow: hidden;
        width: 100%;
    }
    
    .onmouse_gallery-title-files li {
        color: red;
        float:left;
        margin: 3px 3px 3px 0;
        padding: 1px;
        display: block;
        float: left;
        width: 164px;
    }
    
    .onmouse_gallery-notice {
        color: red;
        font-size: 16px;
        margin-bottom: 5px;
    }
    </style>
    <?php endif;?>
    <style type="text/css">
        .tab-content {
            overflow: visible;
        }
    </style>
  </head>

  <body>
  
{template_header}

<a id="content"></a>
<div class="wrap-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span8 affix-parent">
            <h2>{lang_Propertydata}</h2>
            <div class="property_content">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal form-estate', 'role'=>'form', 'id'=>'property-submition'))?>                              
                                <?php
                                if(!isset($estate) || !isset($estate['repository_id'])):
                                    $repository_id = NULL;
                                    $CI = &get_instance('repository_m');
                                    $CI->load->model('repository_m');
                                    if(isset($_POST['repository_id']))
                                    {
                                        $repository_id = $estate['repository_id'] = $_POST['repository_id'];
                                    }
                                    else
                                    {
                                        // Create new repository
                                        $repository_id = $estate['repository_id'] = $CI->repository_m->save(array('name'=>'estate_m', 'is_activated'=>0));
                                    }
                                    ?>
                                    <div class="control-group hidden">
                                      <label class="control-label"><?php _l('Repository')?></label>
                                      <div class="controls">
                                        <?php echo form_input('repository_id', $repository_id, 'class="form-control" id="repository_id" placeholder="'.lang_check('Repository').'"  readonly')?>
                                      </div>
                                    </div>
                                <?php endif;?>
                    
                                <div class="control-group">
                                  <label class="control-label"><?php if(config_db_item('address_not_required') !== TRUE):?>*<?php endif;?><?php echo lang('Address')?></label>
                                  <div class="controls">
                                    <?php 
                                    
                                    $read_only_addr='';
                                    if(config_db_item('address_edit_disabled') === TRUE && $estate['is_activated'])
                                        $read_only_addr=' readonly';
                                    
                                    echo form_input('address', set_value('address', $estate['address']), 'class="form-control" id="inputAddress" placeholder="'.lang('Address').'"'.$read_only_addr)?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang('Gps')?></label>
                                  <div class="controls">
                                    <?php echo form_input('gps', set_value('gps', $estate['gps']), 'class="form-control" id="inputGps" placeholder="'.lang('Gps').'"  readonly')?>
                                  </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo lang_check('Listing visible for public') ?></label>
                                    <div class="controls">
                                        <?php echo form_checkbox('is_visible', '1', set_value('is_visible', $estate['is_visible']), 'id="inputVisible"')?>
                                    </div>
                                </div>
                                <h5><?php echo lang('Translation data')?></h5>
                                <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs lang-tabs" data-spy="affix">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <li class="lang rtab <?php echo $i==1?'active':''?>"><a data-toggle="tab" href="#<?php echo $key?>"><?php echo $val?></a></li>
                                    <?php endforeach;?>
                                    
                                    <?php if(sw_count($this->option_m->languages) > 1): ?>
                                    <li class="pull-right"><a href="#" id="copy-lang" class="btn btn-default" type="button"><?php echo lang_check('Copy to other languages')?></a></li>
                                    <li class="pull-right"><a href="#" id="translate-lang" rel="<?php echo site_url('api/translate/');  ?>" class="btn btn-default" type="button"><?php echo lang_check('Translate to other languages')?></a></li>
                                    <?php endif; ?>
                                    
                                  </ul>
                                  <div style="padding-top: 9px;" class="tab-content">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <div id="<?php echo $key?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                    
                                        <?php if(config_db_item('slug_enabled') === TRUE): ?>
                                        <div class="control-group form-group hidden">
                                          <label class="col-lg-3 control-label"><?php echo lang_check('URI slug')?></label>
                                          <div class="col-lg-9 controls">
                                            <?php echo form_input('slug_'.$key, set_value('slug_'.$key, $estate['slug_'.$key]), 'class="form-control skip-input" id="inputOption_'.$key.'_slug" placeholder="'.lang_check('URI slug').'"')?>
                                          </div>
                                        </div>
                                        <?php endif; ?>
 
                                        <?php foreach($options as $key_option=>$val_option):?>
                                        
                                        <?php

                                        if(isset(${'parent_field_is_hidden'.$val_option['parent_id']}) && ${'parent_field_is_hidden'.$val_option['parent_id']} == TRUE) {
                                            $val_option['is_frontend'] = 0;
                                        }

                                        if($val_option['type'] == 'CATEGORY') {
                                            if(!$val_option['is_frontend'])
                                                ${'parent_field_is_hidden'.$val_option['id']} = TRUE;
                                        }
                                        
                                        if($val_option['type'] == 'CATEGORY' && $val_option['id']=='42' ) continue;
                                        
                                        $required_text = '';
                                        $required_notice = '';
                                        if($val_option['is_required'] == 1 && $val_option['is_frontend'] != 0)
                                        {
                                            $required_text = 'required';
                                            $required_notice = '*';
                                        }
                                        
                                        $max_length_text = '';
                                        if($val_option['max_length'] > 0)
                                        {
                                            $max_length_text = 'maxlength="'.$val_option['max_length'].'"';
                                        }
                                        $is_not_translatable = false;

                                        if($key != $this->language_m->get_default_id() && isset($val_option['is_not_translatable']) && $val_option['is_not_translatable']==1) {
                                            $is_not_translatable = true;
                                        }
                                        ?>
                                        
                                        <?php if($val_option['type'] == 'CATEGORY'):?>
                                        
                                        <h5 class="<?php echo ($val_option['is_frontend']?'':' hidden') ?>"><hr /><?php echo $val_option['option']?> <span class="checkbox-visible"><?php echo form_checkbox('option'.$val_option['id'].'_'.$key, 'true', set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:''), 'id="inputOption_'.$key.'_'.$val_option['id'].'"')?> <?php echo lang_check('Hidden on preview page'); ?></span><hr /></h5>
                                        
                                        <?php elseif($val_option['type'] == 'INPUTBOX' || $val_option['type'] == 'DECIMAL' || $val_option['type'] == 'INTEGER'):?>
                                            <div class="control-group<?php echo ($val_option['is_frontend']?'':' hidden') ?>">
                                              <label class="control-label"><?php echo $required_notice.$val_option['option']?><?php if(!empty($val_option['hint'])):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option['hint'];?>"></i><?php endif;?></label>
                                                <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="controls">
                                                <?php 
                                                
                                                $cur_value = isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:'';
                                                
                                                echo form_input('option'.$val_option['id'].'_'.$key, set_value('option'.$val_option['id'].'_'.$key, $cur_value), 'class="form-control '.$val_option['type'].'" id="inputOption_'.$key.'_'.$val_option['id'].'" strlen="'.strlen($cur_value).'" placeholder="'.$val_option['option'].'" '.$required_text.' '.$max_length_text)?>
                                              <?php if(!empty($options_lang[$key][$key_option]->prefix) || !empty($options_lang[$key][$key_option]->suffix)): ?>
                                                <?php echo $options_lang[$key][$key_option]->prefix.$options_lang[$key][$key_option]->suffix?>
                                              <?php endif; ?>
                                              </div>
                                              <?php endif;?>
                                            </div>
                                        <?php elseif($val_option['type'] == 'DROPDOWN'):?>
                                            <div class="control-group<?php echo ($val_option['is_frontend']?'':' hidden') ?>">
                                              <label class="control-label"><?php echo $required_notice.$val_option['option']?><?php if(!empty($val_option['hint'])):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option['hint'];?>"></i><?php endif;?></label>
                                                <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="controls">
                                                <?php
                                                if(isset($options_lang[$key][$key_option]))
                                                {
                                                    $drop_options = array_combine(explode(',',check_combine_set(isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', $val_option['values'], '')),explode(',',check_combine_set($val_option['values'], isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', '')));
                                                }
                                                else
                                                {
                                                    $drop_options = array();
                                                }
                                                
                                                // If you don't want translation to website langauge uncomment this 1 line below:
                                                // $drop_options = array_combine(explode(',', $options_lang[$key][$key_option]->values), explode(',', $options_lang[$key][$key_option]->values));
                                                
                                                $drop_selected = set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:'');

                                                echo form_dropdown('option'.$val_option['id'].'_'.$key, $drop_options, $drop_selected, 'class="form-control" id="inputOption_'.$key.'_'.$val_option['id'].'" placeholder="'.$val_option['option'].'" '.$required_text)
                                                
                                                ?>
                                              </div>
                                              <?php endif;?>
                                            </div>
                                        <?php elseif($val_option['type'] == 'DROPDOWN_MULTIPLE' && config_item('field_dropdown_multiple_enabled') === TRUE):?>
                                            <div class="control-group<?php echo ($val_option['is_frontend']?'':' hidden') ?>">
                                              <label class="control-label"><?php echo $required_notice.$val_option['option']?><?php if(!empty($val_option['hint'])):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option['hint'];?>"></i><?php endif;?></label>
                                                <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="controls">
                                                <?php
                                                if(isset($options_lang[$key][$key_option]))
                                                {
                                                    $drop_options = array_combine(explode(',',check_combine_set(isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', $val_option['values'], '')),explode(',',check_combine_set($val_option['values'], isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', '')));
                                                }
                                                else
                                                {
                                                    $drop_options = array();
                                                }
                                                
                                                // If you don't want translation to website langauge uncomment this 1 line below:
                                                // $drop_options = array_combine(explode(',', $options_lang[$key][$key_option]->values), explode(',', $options_lang[$key][$key_option]->values));
                                                
                                                $drop_selected = set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:'');

                                                echo form_dropdown('option'.$val_option['id'].'_'.$key, $drop_options, $drop_selected, 'class="form-control" id="inputOption_'.$key.'_'.$val_option['id'].'" placeholder="'.$val_option['option'].'" '.$required_text)
                                                
                                                ?>
                                              </div>
                                              <?php endif;?>
                                            </div>
                                        <?php elseif($val_option['type'] == 'TEXTAREA'):?>
                                            <div class="control-group<?php echo ($val_option['is_frontend']?'':' hidden') ?>">
                                              <label class="control-label"><?php echo $required_notice.$val_option['option']?><?php if(!empty($val_option['hint'])):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option['hint'];?>"></i><?php endif;?></label>
                                                <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="controls">
                                                <?php 
                                                $cur_value = isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:'';
                                                
                                                echo form_textarea('option'.$val_option['id'].'_'.$key, set_value('option'.$val_option['id'].'_'.$key, $cur_value), 'class="ckeditor form-control" id="inputOption_'.$key.'_'.$val_option['id'].'" strlen="'.strlen($cur_value).'" placeholder="'.$val_option['option'].'" '.$required_text)?>
                                              </div>
                                              <?php endif;?>
                                            </div>
                                        <?php elseif($val_option['type'] == 'TREE' && config_item('tree_field_enabled') === TRUE):?>
                                            <div class="control-group TREE-GENERATOR">
                                              <label class="col-lg-3 control-label">
                                              <?php echo $val_option['option']?>
                                              <div class="ajax_loading"> </div>
                                              </label>
                                                <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="col-lg-9 controls">
                                                <?php
                                                $drop_options = $this->treefield_m->get_level_values($key, $val_option['id']);
                                                $drop_selected = array();
                                                
                                                echo '<div class="field-row">';
                                                echo form_dropdown('option'.$val_option['id'].'_'.$key.'_level_0', $drop_options, $drop_selected, 'class="form-control" id="inputOption_'.$key.'_'.$val_option['id'].'_level_0'.'" placeholder="'.$val_option['option'].'"');
                                                echo '</div>';

                                                $levels_num = $this->treefield_m->get_max_level($val_option['id']);
                                                
                                                if($levels_num>0)
                                                for($ti=1;$ti<=$levels_num;$ti++)
                                                {
                                                    echo '<div class="field-row">';
                                                    echo form_dropdown('option'.$val_option['id'].'_'.$key.'_level_'.$ti, array(''=>lang_check('Please select parent')), array(), 'class="form-control" id="inputOption_'.$key.'_'.$val_option['id'].'_level_'.$ti.'" placeholder="'.$val_option['option'].'"');
                                                    echo '</div>';
                                                }

                                                ?>
                                                <div class="field-row hidden">
                                                <?php echo form_input('option'.$val_option['id'].'_'.$key, set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:''), 'class="form-control tree-input-value"  rel="" id="inputOption_'.$key.'_'.$val_option['id'].'" placeholder="'.$val_option['option'].'"')?>
                                                </div>
                                              </div>
                                                <?php endif;?>
                                            </div>
                                        <?php elseif($val_option['type'] == 'UPLOAD'):?>
                                            <div class="control-group form-group UPLOAD-FIELD-TYPE <?php echo ($val_option['is_frontend']?'':' hidden') ?>">
                                              <label class="col-lg-3 control-label">
                                              <?php echo $val_option['option']?>
                                              <div class="ajax_loading"> </div>
                                              </label>
                                                                                              <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="col-lg-9 controls">
<div class="field-row hidden">
<?php echo form_input('option'.$val_option['id'].'_'.$key, set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:'SKIP_ON_EMPTY'), 'class="form-control skip-input" id="inputOption_'.$key.'_'.$val_option['id'].'" placeholder="'.$val_option['option'].'"')?>
</div>
<?php if(empty($estate['id'])): ?>
<span class="label label-danger label-important"><?php echo lang('After saving, you can add files and images');?></span>
<br style="clear:both;" />
<?php else: ?>
<!-- Button to select & upload files -->
<span class="btn btn-success fileinput-button">
    <span><?php _l('Select files...'); ?></span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload_<?php echo $val_option['id'].'_'.$key; ?>" class="FILE_UPLOAD file_<?php echo $val_option['id'].'_'.$key; ?>" type="file" name="files[]" multiple>
</span><br style="clear: both;" />
<!-- The global progress bar -->
<p><?php _l('Upload progress'); ?></p>
<div id="progress_<?php echo $val_option['id'].'_'.$key; ?>" class="progress progress-success progress-striped">
    <div class="bar"></div>
</div>
<!-- The list of files uploaded -->
<p><?php _l('Files uploaded:'); ?></p>
<ul id="files_<?php echo $val_option['id'].'_'.$key; ?>">
<?php 

if(isset($estate['option'.$val_option['id'].'_'.$key])){
    $rep_id = $estate['option'.$val_option['id'].'_'.$key];
    
    //Fetch repository
    $file_rep = $this->file_m->get_by(array('repository_id'=>$rep_id));
    if(sw_count($file_rep)) foreach($file_rep as $file_r)
    {
        $delete_url = site_url_q('files/upload/rep_'.$file_r->repository_id, '_method=DELETE&amp;file='.rawurlencode($file_r->filename));
        
        echo "<li><a target=\"_blank\" href=\"".base_url('files/'.$file_r->filename)."\">$file_r->filename</a>".
             '&nbsp;&nbsp;<button class="btn btn-xs btn-mini btn-danger" data-type="POST" data-url='.$delete_url.'><i class="icon-trash icon-white"></i></button></li>';
    }
}
?>
</ul>

<!-- JavaScript used to call the fileupload widget to upload files -->
<script>
// When the server is ready...
$( document ).ready(function() {
    
    // Define the url to send the image data to
    var url_<?php echo $val_option['id'].'_'.$key; ?> = '<?php echo site_url('files/upload_field/'.$estate['id'].'_'.$val_option['id'].'_'.$key);?>';
    
    // Call the fileupload widget and set some parameters
    $('#fileupload_<?php echo $val_option['id'].'_'.$key; ?>').fileupload({
        url: url_<?php echo $val_option['id'].'_'.$key; ?>,
        autoUpload: true,
        dropZone: $('#fileupload_<?php echo $val_option['id'].'_'.$key; ?>'),
        dataType: 'json',
        done: function (e, data) {
            // Add each uploaded file name to the #files list
            var added=false;
            $.each(data.result.files, function (index, file) {
                if(!file.hasOwnProperty("error"))
                {
                    $('#files_<?php echo $val_option['id'].'_'.$key; ?>').append('<li><a href="'+file.url+'" target="_blank">'+file.name+'</a>&nbsp;&nbsp;<button class="btn btn-xs btn-mini btn-danger" data-type="POST" data-url='+file.delete_url+'><i class="icon-trash icon-white"></i></button></li>');
                    added=true;
                }
                else
                {
                    ShowStatus.show(file.error);
                }
            });
            
            if(added)
            {
                $('<?php echo '#inputOption_'.$key.'_'.$val_option['id']; ?>').val(data.result.repository_id);
                reset_events_<?php echo $val_option['id'].'_'.$key; ?>();
            }
        },
        progressall: function (e, data) {
            // Update the progress bar while files are being uploaded
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress_<?php echo $val_option['id'].'_'.$key; ?> .bar').css(
                'width',
                progress + '%'
            );
        }
    });
    
    reset_events_<?php echo $val_option['id'].'_'.$key; ?>();
});

function reset_events_<?php echo $val_option['id'].'_'.$key; ?>(){
    $("#files_<?php echo $val_option['id'].'_'.$key; ?> li button").unbind();
    $("#files_<?php echo $val_option['id'].'_'.$key; ?> li button.btn-danger").click(function(){
        var image_el = $(this);
        
        $.post($(this).attr('data-url'), function( data ) {
            var obj = jQuery.parseJSON(data);
            
            if(obj.success)
            {
                image_el.parent().remove();
            }
            else
            {
                ShowStatus.show('<?php echo lang_check('Unsuccessful, possible permission problems or file not exists'); ?>');
            }
            //console.log("Data Loaded: " + obj.success );
        });
        
        return false;
    });
}

</script>
<?php endif; ?>
                                               </div>
                                                <?php endif;?>
                                            </div>
                                        <?php elseif($val_option['type'] == 'CHECKBOX'):?>
                                            <div class="control-group<?php echo ($val_option['is_frontend']?'':' hidden') ?>">
                                              <label class="control-label"><?php echo $required_notice.$val_option['option']?><?php if(!empty($val_option['hint'])):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option['hint'];?>"></i><?php endif;?></label>
                                                <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="controls">
                                                <?php echo form_checkbox('option'.$val_option['id'].'_'.$key, 'true', set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:''), 'id="inputOption_'.$key.'_'.$val_option['id'].'" class="valid_parent" '.$required_text)?>
                                                <?php
                                                    if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/icons/option_id/'.$val_option['id'].'.png'))
                                                    {
                                                        echo '<img class="results-icon" src="assets/img/icons/option_id/'.$val_option['id'].'.png" alt="'.$val_option['option'].'"/>';
                                                    }
                                                ?>
                                              </div>
                                              <?php endif;?>
                                            </div>
                                        <?php elseif($val_option['type'] == 'HTMLTABLE' && config_item('enable_table_input') === TRUE):?>
                                            <div class="control-group type_HTMLTABLE <?php echo (!$val_option['is_frontend'] && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                              <label class="control-label"><?php echo $val_option['option']?></label>
                                            <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="controls">
                                              <?php 
                                                $columns = explode(',', $val_option['values']);
                                                $columns[] = lang_check('Edit');
                                              ?>
                                              
                                                    <table id="editable_table_<?php echo $val_option['id'].'_'.$key; ?>" class="table table-striped table-bordered table-hover" style="">
                                                    <thead>
                                                    <tr>
                                                    <?php foreach($columns as $col_val): ?>
                                                        <?php
                                                        $to = strpos($col_val, '[');
                                                        if($to !== FALSE)$col_val =substr($col_val, 0, $to);
                                                        ?>
                                                        <th style="font-weight: normal;"><?php echo $col_val; ?></th>
                                                    <?php endforeach; ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                    <?php foreach($columns as $col_key => $col_val): ?>
                                                    <?php if($col_val == lang_check('Edit')): ?>
                                                        <td class="disabled">
                                                            <a href="#" class="table_plus btn btn-xs btn-primary"><i class="icon-plus"></i></a>
                                                        </td>
                                                    <?php else: ?>
                                                        <td></td>
                                                    <?php endif ?>
                                                    <?php endforeach; ?>
                                                    </tr>
                                                    </tbody>
                                                    </table>
                                                    
                                                    <?php echo form_textarea('option'.$val_option['id'].'_'.$key, set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:''), 'class="form-control HTMLTABLE hidden" id="inputOption_'.$key.'_'.$val_option['id'].'" style="height:2px;padding:0px;margin:0px;" placeholder="'.$val_option['option'].'" '.$required_text)?>

                                              </div>
                                              <?php endif;?>
                                              
                                              
<?php
    // Generate translation data
    
    if(isset($options_lang[$key]))
    {
        $translated_values = $options_lang[$key][$key_option]->values;
        $columns = explode(',', $translated_values);
    }
    
    $elem_list = array();
    foreach($columns as $col_key=>$col_val)
    {
        
        $from = strpos($col_val, '[');
        $to = strpos($col_val, ']');
        if($from !== FALSE)
        {
            $col_list =substr($col_val, $from+1, $to-$from-1);
            $col_list_explode = explode('|',$col_list);
            
            echo '<div id="col_'.$key.'_'.$val_option['id'].'_'.$col_key.'" class="hidden">';  
            if(sw_count($col_list_explode) > 0)echo '<span></span>';          
            foreach($col_list_explode as $val)
            {
                echo '<span>'.trim($val).'</span>';
            }
            echo '</div>';
        }
    }

?>
                                              

                                            </div>
                                            
                                            
                                            <script>

                                            $(function () {
                                                var table_options = {};
<?php
    
    //translate columns
    if(isset($options_lang[$key]))
    {
        $translated_values = $options_lang[$key][$key_option]->values;
        $columns = explode(',', $translated_values);
    }
    
    $elem_list = array();
    foreach($columns as $col_val)
    {
        $from = strpos($col_val, '[');
        $to = strpos($col_val, ']');
        if($from !== FALSE)
        {
            $col_list =substr($col_val, $from+1, $to-$from-1);
            $col_list_explode = explode('|',$col_list);
            $options_gen = '<select>';
            if(sw_count($col_list_explode) > 0)$options_gen.='<option val=""></option>';
            foreach($col_list_explode as $val)
            {
                $options_gen.='<option val="'.$val.'">'.$val.'</option>';
            }
            $options_gen.='</select>';
            $elem_list[] = "$('$options_gen')";
        }
        else
        {
            $elem_list[] = "$('<input>')";
        }
    }
    
    if(isset($options_gen))echo "table_options = {'editors':[".implode(',', $elem_list)."]};";

?>

                                                
                                                generate_table('<?php echo $val_option['id'].'_'.$key; ?>', table_options);
                                                
                                                table_add_events('<?php echo $val_option['id'].'_'.$key; ?>');
                                            });

                                            </script>
                                            
                                            
                                            <?php elseif($val_option['type'] == 'DATETIME' && config_item('field_datetime_enabled')=== TRUE):?>
                                                <div class="control-group<?php echo ($val_option['is_frontend']?'':' hidden') ?>">
                                                    <label class="control-label"><?php echo $required_notice.$val_option['option']?> <?php if(!empty($val_option['hint'])):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option['hint'];?>"></i><?php endif;?></label>
                                                <?php if($is_not_translatable):?>
                                                <div class="controls">
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                                    <div class="controls">
                                                    <div class="input-append" id="datetimepicker_field_<?php _che($key);?>_<?php _che($val_option['id']);?>">
                                                        <?php echo form_input('option'.$val_option['id'].'_'.$key, set_value('option'.$val_option['id'].'_'.$key, isset($estate['option'.$val_option['id'].'_'.$key])?$estate['option'.$val_option['id'].'_'.$key]:''), 'class="picker '.$val_option['type'].'" id="inputOption_'.$key.'_'.$val_option['id'].'"  data-format="yyyy-MM-dd" placeholder="'.$val_option['option'].'" '.$required_text.' '.$max_length_text)?>
                                                        <span class="add-on">
                                                          &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                                          </i>
                                                        </span>
                                                    </div> 
                                                  </div>
                                                  <?php endif;?>
                                                </div>

                                                <script>
                                                  $(function() {
                                                        $('#inputOption_<?php _che($key);?>_<?php _che($val_option['id']);?>').datepicker({
                                                          pickTime: false
                                                        });
                                                        
                                                        $('#datetimepicker_field_<?php _che($key);?>_<?php _che($val_option['id']);?> span').click(function(){
                                                            $('#inputOption_<?php _che($key);?>_<?php _che($val_option['id']);?>').trigger( "focus" );
                                                        });
                                                    });
                                                </script>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>
                                
                                <?php if(config_db_item('terms_link') !== FALSE): ?>
                                <?php
                                    $site_url = site_url();
                                    $urlparts = parse_url($site_url);
                                    $basic_domain = $urlparts['host'];
                                    $terms_url = config_db_item('terms_link');
                                    $urlparts = parse_url($terms_url);
                                    $terms_domain ='';
                                    if(isset($urlparts['host']))
                                        $terms_domain = $urlparts['host'];

                                    if($terms_domain == $basic_domain) {
                                        $terms_url = str_replace('en', $lang_code, $terms_url);
                                    }
                                ?>
                                <div class="control-group">
                                  <label class="control-label"><a target="_blank" href="<?php echo $terms_url; ?>"><?php echo lang_check('I Agree To The Terms & Conditions'); ?></a></label>
                                  <div class="controls">
                                    <?php echo form_checkbox('option_agree_terms', 'true', set_value('option_agree_terms', false), 'class="novalidate" required="required" id="inputOption_terms"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(config_db_item('privacy_link') !== FALSE && sw_count($not_logged)>0): ?>
                                                            <?php

                                $site_url = site_url();
                                $urlparts = parse_url($site_url);
                                $basic_domain = $urlparts['host'];
                                $privacy_url = config_db_item('privacy_link');
                                $urlparts = parse_url($privacy_url);
                                $privacy_domain ='';
                                if(isset($urlparts['host']))
                                    $privacy_domain = $urlparts['host'];

                                if($privacy_domain == $basic_domain) {
                                    $privacy_url = str_replace('en', $lang_code, $privacy_url);
                                }
                            ?>
                                <div class="control-group">
                                  <label class="control-label"><a target="_blank" href="<?php echo $privacy_url; ?>"><?php echo lang_check('I Agree The Privacy'); ?></a></label>
                                  <div class="controls">
                                    <?php echo form_checkbox('option_privacy_link', 'true', set_value('option_privacy_link', false), 'class="novalidate" required="required" id="inputOption_privacy_link"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="control-group">
                                  <div class="controls">
                                    <?php echo form_submit('', lang('Save'), 'class="btn btn-primary ajax-indicator"')?>
                                    <a href="<?php echo site_url('admin/estate')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  
                                    <?php if(isset($estate['id'])):?>
                                    <a target="blank" href="<?php echo slug_url($this->data['listing_uri'].'/'.$estate['id'].'/'.$this->data['lang_code']).'?preview=true'; ?>" class="btn btn-success" type="button"><?php echo lang_check('Preview last saved')?></a>
                                    <?php else: ?>
                                    <span><em><?php echo lang_check('Save for preview')?></em></span>
                                    <?php endif; ?>
                                    <img id="ajax-indicator-1" src="assets/img/ajax-loader.gif"  alt=""/>
                                  </div>
                                </div>
                       <?php echo form_close()?>
            </div>
            </div>

            <div class="span4">
            <h2>{lang_Location}</h2>
                <div class="property_content">
                  <div class="gmap" id="mapsAddress">

                  </div>
                </div>
            </div>
        </div>
        
        <br />
        
        <h2><?php echo lang_check('Images gallery');?></h2>
        <div class="property_content">
            
<?php if(isset($estate['id'])):?>
    <div id="page-files-<?php echo $estate['id']?>">
<?php endif;?>
        
<?php if(!isset($estate['repository_id'])):?>
<span class="label label-danger label-important"><?php _l('Repository ID not available');?></span>
<?php else:?>
<div id="page-files-<?php echo $estate['repository_id']?>" rel="repository_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo site_url('files/upload_repository/'.$estate['repository_id']);?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="<?php echo site_url('frontend/editproperty/'.$lang_code)?>"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <div class="span7 col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo lang_check('Addfiles')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span><?php echo lang_check('Cancelupload')?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo lang_check('Deleteselection')?></span>
                </button>
                <input type="checkbox" class="toggle" />
            </div>
            <!-- The global progress information -->
            <div class="span5 col-md-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br />
        <!-- The table listing the files available for upload/download -->
        <!--<table role="presentation" class="table table-striped">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">-->

          <div role="presentation" class="fieldset-content">
            <?php if(config_item('onmouse_gallery_enabled') === TRUE): ?>
                <div class="onmouse_gallery-notice clearfix"> <?php echo lang_check('Please order images as described');?>:</div> 
                <ul class="onmouse_gallery-title-files clearfix"> 
                    <li class="item text-center">
                    <div class="img-rounded-title text-center"><?php echo lang_check('Main image');?></div>
                    </li>
                    <li class="item text-center">
                    <div class="img-rounded-title text-center"><?php echo lang_check('Apartment').' 1' ;?></div>
                    </li>
                    <li class="item text-center">
                    <div class="img-rounded-title text-center"><?php echo lang_check('Apartment').' 2' ;?></div>
                    </li>
                    <li class="item text-center">
                    <div class="img-rounded-title text-center"><?php echo lang_check('Apartment').' 3' ;?></div>
                    </li>
                    <li class="item text-center">
                    <div class="img-rounded-title text-center"><?php echo lang_check('...Other') ;?></div>
                    </li>
                </ul>  
            <?php endif;?>
            <ul class="files files-list-u" data-toggle="modal-gallery" data-target="#modal-gallery">      
            <?php if(isset($files[$estate['repository_id']]))foreach($files[$estate['repository_id']] as $key=>$file ):?>
                <li class="img-rounded template-download fade in">   
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo $file->filename?>" data-src="<?php echo $file->thumbnail_url?>" src="<?php echo $file->thumbnail_url?>">
                </div>
                <div class="filename">
                    <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-mini btn-success"><i class="icon-search icon-white"></i></a>                  
                    <a class="btn btn-mini btn-info iedit visible-inline-block-lg" rel="<?php echo $file->filename?>" href="#<?php echo $file->filename?>"><i class="icon-pencil icon-white"></i></a>
                    <?php else:?>
                    <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-mini btn-success"><i class="icon-search icon-white"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-mini btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="icon-trash icon-white"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
            <?php endforeach;?>
            </ul>
            <br style="clear:both;"/>
          </div>
    </form>

</div>
<?php endif;?>
        </div>
               <br /> 
    <?php if(config_item('plan_gallery_enabled') == TRUE):?>       
    <h2><?php echo lang_check('Plan gallery');?></h2>
    <div class="property_content">
        <?php if(!isset($estate['id'])):?>
        <span class="label label-danger label-important"><?php echo lang_check('After saving, you can add files and images');?></span>
        <?php else:?>
        <div id="page-files-<?php echo $estate['planimages_repository_id']?>" rel="estate_m">
            <!-- The file upload form used as target for the file upload widget -->
            <form class="fileupload" action="<?php echo site_url('files/upload_repository/'.$estate['planimages_repository_id']);?>" method="POST" enctype="multipart/form-data">
                <!-- Redirect browsers with JavaScript disabled to the origin page -->
                <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/estate/edit/'.$estate['id']);?>"></noscript>
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="fileupload-buttonbar">
                    <div class="span7 col-md-7">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="icon-plus icon-white"></i>
                            <span><?php echo lang_check('Addfiles')?></span>
                            <input type="file" name="files[]" multiple>
                        </span>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="icon-ban-circle icon-white"></i>
                            <span><?php echo lang_check('Cancelupload')?></span>
                        </button>
                        <button type="button" class="btn btn-danger delete">
                            <i class="icon-trash icon-white"></i>
                            <span><?php echo lang_check('Deleteselection')?></span>
                        </button>
                        <input type="checkbox" class="toggle" />
                    </div>
                    <!-- The global progress information -->
                    <div class="span5 col-md-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="bar" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress information -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The loading indicator is shown during file processing -->
                <div class="fileupload-loading"></div>
                <br />
                <!-- The table listing the files available for upload/download -->
                <!--<table role="presentation" class="table table-striped">
                <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">-->

                  <div role="presentation" class="fieldset-content">
                    <ul class="files files-list-u" data-toggle="modal-gallery" data-target="#modal-gallery">      
                    <?php if(isset($files[$estate['planimages_repository_id']]))foreach($files[$estate['planimages_repository_id']] as $key=>$file ):?>
                        <li class="img-rounded template-download fade in">   
                        <div class="preview">
                            <img class="img-rounded" alt="<?php echo $file->filename?>" data-src="<?php echo $file->thumbnail_url?>" src="<?php echo $file->thumbnail_url?>">
                        </div>
                        <div class="filename">
                            <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                        </div>
                        <div class="options-container">
                            <?php if($file->zoom_enabled):?>
                            <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-mini btn-success"><i class="icon-search icon-white"></i></a>                  
                            <a class="btn btn-mini btn-info iedit visible-inline-block-lg" rel="<?php echo $file->filename?>" href="#<?php echo $file->filename?>"><i class="icon-pencil icon-white"></i></a>
                            <?php else:?>
                            <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-mini btn-success"><i class="icon-search icon-white"></i></a>
                            <?php endif;?>
                            <span class="delete">
                                <button class="btn btn-mini btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="icon-trash icon-white"></i></button>
                                <input type="checkbox" value="1" name="delete">
                            </span>
                        </div>
                    </li>
                    <?php endforeach;?>
                    </ul>
                    <br style="clear:both;"/>
                  </div>
            </form>

        </div>
        <?php endif;?>
        <?php endif;?>
        
        </div>
        
        <?php if(false):?>
        <br />
        <div class="property_content">
        {page_body}
        </div>
        <?php endif;?>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 
<script src="assets/libraries/ckeditor_4.6.2_standard/ckeditor/ckeditor.js"></script>
<script>
  /* [START] Dependent fields */
    $(document).ready(function(){
        //console.log('Dependent fields loading');
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
        
        foreach($CI->language_m->db_languages_code as $key_lang=>$id_lang):
        foreach($dependent_fields_prepare as $d_field_id=>$d_field_indexes):
        ?>
        //console.log('fields: <?php echo $d_field_id; ?>');
        $("select[name='option<?php echo $d_field_id.'_'.$id_lang; ?>'], input[rel][name='option<?php echo $d_field_id.'_'.$id_lang; ?>']").change(function () {

            var index = $(this).children('option:selected').index();
            var parent_elem = $(this).parent().parent().parent();
            var parent_elem_hide = $(this).parent().parent();
            
            var index_tree = $(this).attr('rel');
            if (typeof index_tree !== typeof undefined && index_tree !== false) {
              index = index_tree;
              parent_elem = parent_elem.parent();
              parent_elem_hide = parent_elem_hide.parent();
            }

            // show all below
            parent_elem_hide.nextAll().removeClass('hide')
            $(this).closest('.tab-pane').find('textarea, select, input').each(function(){
                if($(this).attr('data-required') == 'true') {
                    $(this).attr('required', 'required')
                }
            })
            
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
            
            
            //console.log(index);
        });
        
        $("select[name='option<?php echo $d_field_id.'_'.$id_lang; ?>']").trigger('change');
        
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
                    $generate_selector_list[] = "[name='option".$hide_field_id.'_'.$id_lang."']";
                }
                $generate_selector = implode(',', $generate_selector_list);
                ?>
                
                // empty values
                parent_elem.find("<?php echo $generate_selector; ?>").not('.skip-input').each( function() {
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
                    
                    if($(this).attr('required')) {
                        $(this).removeAttr('required')
                        $(this).attr('data-required', 'true')
                    }
                    
                });
                
                // hide all below
                //parent_elem.find("<?php echo $generate_selector; ?>").parent().parent().addClass('hide');
                
                // hide all below <hr> if found below
                parent_elem.find("<?php echo $generate_selector; ?>").parent().parent().each( function() {
                    var curr_elem = $(this);
                    if(!(curr_elem.hasClass('control-group') || curr_elem.hasClass('form-group')) &&
                       (curr_elem.parent().hasClass('control-group') || curr_elem.parent().hasClass('form-group')) )
                    {
                        curr_elem = curr_elem.parent();
                    }
                    
                    curr_elem.addClass('hide');
                    
                    if(curr_elem.prev().is('hr'))
                    {
                        curr_elem.prev().addClass('hide');
                    }
                    
                    if(curr_elem.next().is('hr'))
                    {
                        curr_elem.next().addClass('hide');
                    }
                });
            }
            <?php endforeach; ?>
        }
        
        
        <?php endforeach;endforeach; ?>
        
    });
    
    /* [END] Dependent fields */
        
    /* [START] TreeField */
    
    $(function() {
        $(".TREE-GENERATOR select").change(function(){
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
                    $(this).html('<option value=""><?php echo lang_check('No values found'); ?></option>');
                    $(this).val('');
                }
                else if($(this).val() != '')
                {
                    last_selected_numeric = $(this).val();
                    generated_val+=$(this).find("option:selected").text()+" - ";
                }
                    
            });

            $("#inputOption_"+s_lang_id+"_"+s_field_id).attr('rel', last_selected_numeric);
            $("#inputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);
            $("#inputOption_"+s_lang_id+"_"+s_field_id).trigger("change");

        });
        
        // Autoload selects
        $(".TREE-GENERATOR input.tree-input-value").each(function(index_1){
            var s_values_splited = ($(this).val()+" ").split(" - "); 
//            $.each(s_values_splited, function( index, value ) {
//                alert( index + ": " + value );
//            });
            if(s_values_splited[0] != '')
            {
                var first_select = $(this).parent().parent().find('select:first');
                var find_selected = first_select.find('option').filter(function () { return $(this).html() == s_values_splited[0]; });
                find_selected.attr('selected', 'selected');
                
                var index_tree = find_selected.val();
                if (typeof index_tree !== typeof undefined && index_tree !== false)
                {
                    if($(this).attr('rel') != index_tree)
                    {
                        $(this).attr('rel', index_tree);
                        $(this).trigger("change");
                    }
                }

                load_by_field(first_select, true, s_values_splited);
            }
            
            //console.log('value: '+s_values_splited[0]);
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
            $.getJSON( "<?php echo site_url('privateapi/get_level_values_select'); ?>/"+s_lang_id+"/"+s_field_id+"/"+s_value+"/"+parseInt(s_level+1), function( data ) {
                //console.log(data.generate_select);
                //console.log("select[name=option"+s_field_id+"_"+s_lang_id+"_level_"+parseInt(s_level+1)+"]");
                ajax_indicator.css('display', 'none');
                
                select_element.html(data.generate_select);
                
                if(autoselect_next)
                {
                    if(s_values_splited[s_level+1] != '')
                    {
                        var find_selected = select_element.find('option').filter(function () { return $(this).html() == s_values_splited[s_level+1]; });
                        
                        find_selected.attr('selected', 'selected');
                        var index_tree = find_selected.val();
                        if (typeof index_tree !== typeof undefined && index_tree !== false)
                        {
                            var input_element = field_element.parent().parent().find("input.tree-input-value");

                            if(input_element.attr('rel') != index_tree)
                            {
                                input_element.attr('rel', index_tree);
                                $(input_element).trigger("change");
                            }
                        }
                        
                        load_by_field(select_element, true, s_values_splited);
                    }
                }
            });
        }
    }
    
    function load_and_select_index(field_element, field_select_id, field_parent_select_id)
    {
        var s_name_splited = field_element.attr('name').split("_"); 
        var s_level = parseInt(s_name_splited[3]);
        var s_lang_id = s_name_splited[1];
        var s_field_id = s_name_splited[0].substr(6);
        
        // Load values for current select
        var ajax_indicator = field_element.parent().parent().parent().find('.ajax_loading');
        if(s_level == 0)$("#inputOption_"+s_lang_id+"_"+s_field_id).attr('value', '');

        ajax_indicator.css('display', 'block');
        $.getJSON( "<?php echo site_url('privateapi/get_level_values_select'); ?>/"+s_lang_id+"/"+s_field_id+"/"+field_parent_select_id+"/"+parseInt(s_level), function( data ) {
            ajax_indicator.css('display', 'none');
            
            field_element.html(data.generate_select);
            //console.log(field_select_id);
            if(isNumber(field_select_id))
                field_element.val(field_select_id);
            else
                field_element.val('');
            
            var generated_val = '';
            var last_selected_val = '';
            
            field_element.parent().parent()
            .find('select').each(function(index){
                if($(this).val() != '' && $(this).val() != null)
                {
                    last_selected_val = $(this).val();
                    generated_val+=$(this).find("option:selected").text()+" - ";
                }
            });

            if(generated_val.length > $("#inputOption_"+s_lang_id+"_"+s_field_id).val().length)
            {
                
                //alert(generated_val);
                //console.log(generated_val);
                $("#inputOption_"+s_lang_id+"_"+s_field_id).attr('rel', last_selected_val);
                $("#inputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);
                $("#inputOption_"+s_lang_id+"_"+s_field_id).trigger('change');
            }

        });

    }
    
    function isNumber(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    /* [END] TreeField */
    
    function generate_table(key_id, generate_table)
    {
        var key_id_split = key_id.split("_"); 
        
        // [generate table]
        var existed_table = $('#inputOption_'+key_id_split[1]+'_'+key_id_split[0]).val();
        if(existed_table != "0")
        $('#editable_table_'+key_id+' tbody tr:last-child').before(existed_table);
        
        var button_append = '<td tabindex="1" class="disabled"><a href="#" class="btn btn-xs table_remove btn-warning">'+
                            '<i class="icon-remove"></i></a></td>';
        
        $('#editable_table_'+key_id+' tbody tr:not(tr:last-child)').append(button_append);
        
        // [/generate table]
        
        // [load widget]
        $('#editable_table_'+key_id).editableTableWidget(generate_table);
        // [/load widget]
    }
    
    function table_add_events(key_id)
    {
        // [add events]
        $('#editable_table_'+key_id+' tr td a.table_remove').click(function() {
            $(this).parent().parent().remove();
            
            save_changes_table(key_id);
            return false;
        });
        
        $('#editable_table_'+key_id+' .table_plus').click(function() {
            
            var clone_row = $(this).parent().parent().clone();
            clone_row.find('i.icon-plus').addClass('icon-remove').removeClass('icon-plus');
            clone_row.find('a.table_plus').addClass('table_remove').addClass('btn-warning').removeClass('table_plus').removeClass('btn-primary');
            
            clone_row.find('a.table_remove').click(function() {
                $(this).parent().parent().remove();
                
                save_changes_table(key_id);
                return false;
            });
            
            clone_row.find('td').on('change', function(evt, newValue) {
            	save_changes_table(key_id);
            });

            $(this).parent().parent().before(clone_row);
            
            $(this).parent().parent().parent().parent().parent().find('input,select').val('');
            $(this).parent().parent().find('td:not(.disabled)').html('');
            
            save_changes_table(key_id);
            return false;
        });
        
        $('#editable_table_'+key_id+' td').on('change', function(evt, newValue) {
        	save_changes_table(key_id);
        });
        
        // [/add events]
    }
    
    
    function save_changes_table(key_id)
    {
        var part_table = $('#editable_table_'+key_id+' tbody').clone();
        part_table.find('tr td:last-child').remove();
        part_table.find('td').removeAttr("tabindex");
        
        // check last row if not empty
        var last_tr_remove = true;
        part_table.find('tr:last-child td').each(function() {
            if($(this).html() != '')
                last_tr_remove = false;
        });
        
        if(last_tr_remove)
            part_table.find('tr:last-child').remove();

        var key_id_split = key_id.split("_"); 

        $('#inputOption_'+key_id_split[1]+'_'+key_id_split[0]).val(part_table.htmlClean().html());
    }

</script>

<link rel="stylesheet" href="assets/js/zebra/css/flat/zebra_dialog.css" />
<script src="assets/js/zebra/javascript/zebra_dialog.src.js"></script>
<script>

/* CL Editor */
$(document).ready(function(){    
    $('.files a.iedit').click(function (event) {
        new $.Zebra_Dialog('', {
            source: {'iframe': {
                'src':  '<?php echo site_url('admin/imageeditor/edit'); ?>/'+$(this).attr('rel'),
                'height': 700
            }},
            width: 950,
            title:  '<?php _jse(lang_check('Edit image')); ?>',
            type: false,
            buttons: false
        });
        return false;
    });
});

</script>

<script src="assets/js/jquery.confirm.js"></script>
<?php
if(config_item('removed_reports_enabled') === TRUE):
?>

<script>

// Reduced price detection
$(document).ready(function(){  

    $('#property-submition').submit(function() {
        
        if($("#inputAddress").val() == "" || $("#inputGps").val() == "")
        {
            alert("<?php _l("Address or coordinates not available"); ?>");
            return false;
        }

        if($(this).hasClass("confirmed"))
        {
<?php
if(!empty($estate['date_renew']) && config_item('price_reduce_enabled') === TRUE && time()-2*86400 > strtotime($estate['date_renew'])):
?> 
        if($(this).hasClass("confirmed2"))return true;
        return false;
<?php else: ?>
        return true;
<?php endif; ?> 
        }

        $.post("<?php echo site_url('privateapi/property_exists/'.$lang_code); ?>", 
                { id: <?php echo isset($estate['id'])?$estate['id']:'0';   ?>, address: $("#inputAddress").val(), gps: $("#inputGps").val() }, 
        function( data ) {
            if(data.success == true)
            {
                $('#property-submition').addClass("confirmed");
                $('#property-submition').submit();
            }
            else if(data.exists == true)
            {
                // check if same property exists in database
                $('#ajax-indicator-1').hide();
                $('#property-submition').removeClass("confirmed");
                
                // Show message that this property already exists
                alert("<?php _l("Property already exists"); ?>");
            }
            else if(data.removed == true)
            {
                // check if same property are deleted before expiration period
                $('#ajax-indicator-1').hide();
                $('#property-submition').removeClass("confirmed");

                // Show message that this property recently-added-deleted
                //console.log(data.removed_list[0].price_0);
                
                var regular_price = data.removed_list[0].price_0;
                var reduced_price = parseFloat(regular_price)*0.9;
                var date_expire = data.removed_list[0].expire_date;
                var price_new = $("#inputOption_1_36").val().replace(',', '');
                
                if($.isNumeric(reduced_price))
                {
                    var price_already_reduced=true;
                    
                    $('div.tabbable>div.tab-content>div.tab-pane').each(function(){
                        if( parseFloat($("#inputOption_"+$(this).attr('id')+"_36").val()) <= 0 ||
                            parseFloat($("#inputOption_"+$(this).attr('id')+"_36").val()) > reduced_price
                        )
                        {
                            price_already_reduced=false;
                        }
                    });
                    
                    if(price_already_reduced)
                    {
                        $('#property-submition').addClass("confirmed");
                        $('#property-submition').submit();
                    }
                    
                    reduced_price = format_number_message(reduced_price);
                    price_new = format_number_message(price_new);
                    if(price_new == "")price_new = "undefined";
                    
                    var message_confirm = "<?php _l('recently-added-deleted', '', array("\r\n"=>"")); ?>";
                    message_confirm = message_confirm.replace("{price-reducted}", reduced_price); 
                    
                    var message_yes = "<?php _l('recently-reduction-yes', '', array("\r\n"=>"")); ?>";
                    message_yes = message_yes.replace("{price-reducted}", reduced_price); 
                    
                    var message_no = "<?php _l('recently-reduction-no', '', array("\r\n"=>"")); ?>";
                    message_no = message_no.replace("{price-reducted}", price_new); 
                    
                    var message_cancel = "<?php _l('recently-reduction-cancel', '', array("\r\n"=>"")); ?>";
                    message_cancel = message_cancel.replace("{until-date}", date_expire); 
                    
                    //Show message with options
                    $.confirm({
                        text: message_confirm,
                        confirmButton: message_yes,
                        cancelButton: message_no,
                        customButton: message_cancel,
                        confirm: function(button) {
                            // reduce price automatically
                            $('div.tabbable>div.tab-content>div.tab-pane').each(function(){
                                $("#inputOption_"+$(this).attr('id')+"_36").val( parseFloat(reduced_price).toFixed(2) );
                            });
                            $('#property-submition').addClass("confirmed");
                            $('#property-submition').submit();
                        },
                        cancel: function(button) {
                            $('#property-submition').addClass("confirmed");
                            $('#property-submition').submit();
                        },
                        custom: function(button) {
                            $('#property-submition').removeClass("confirmed");
                            $('#ajax-indicator-1').hide();
                        },
                        hidden: function(button) {
                            $('#property-submition').removeClass("confirmed");
                            $('#ajax-indicator-1').hide();
                        }
                    });
                }
                else
                {
                    return true;
                }
                
            }
        });

        return false;
    });

});

</script>

<?php endif; ?>

<?php
if(!empty($estate['date_renew']) && config_item('price_reduce_enabled') === TRUE)
if(time()-2*86400 > strtotime($estate['date_renew'])):
?> 

<script>

// Reduced price detection
$(document).ready(function(){  

    // Read old prices
    $('div.tabbable>div.tab-content>div.tab-pane').each(function(){
        var price_old = $("#inputOption_"+$(this).attr('id')+"_36").val();
        $("#price_detection").append('<span class="old_price_'+$(this).attr('id')+'">'+price_old+'</span>');
    });
    
    // On submit check if price is reduced and if not, then show popup message
    $('#property-submition').submit(function() {
        
        if($(this).hasClass("confirmed2") && $(this).hasClass("confirmed"))return true;
        if($(this).hasClass("confirmed"))return false;
        
        var price_reduced = true;
        var price_valid = true;
        $('div.tabbable>div.tab-content>div.tab-pane').each(function(){
            var price_new = $("#inputOption_"+$(this).attr('id')+"_36").val().replace(',', '');
            var price_old = $("span.old_price_"+$(this).attr('id')).html().replace(',', '');
            
            //console.log(parseFloat(price_new));
            //console.log('>');
            //console.log(parseFloat(price_new)*0.9);
            
            if(parseFloat(price_new) > parseFloat(price_old)*0.9 && $.isNumeric(price_new))
                price_reduced=false;
            
            if(!$.isNumeric(price_new))
            {
                price_valid=false;
            }
        });
        
        if(!price_valid)
        {
            $('#property-submition').addClass("confirmed2");
            return true;
        }
        
        if(price_reduced)
        {
            var message_confirm = "<?php _l('price-reduction-benefits-own', '', array("\r\n"=>"")); ?>";
            var message_yes = "<?php _l('price-reduction-yes-own', '', array("\r\n"=>"")); ?>";
            var message_no = "<?php _l('price-reduction-no-own', '', array("\r\n"=>"")); ?>";
            
            $.confirm({
                text: message_confirm,
                confirmButton: message_yes,
                cancelButton: message_no,
                confirm: function(button) {
                    var action_url = $('#property-submition').attr('action');
                    action_url = action_url.replace("?date_alert=false", "");
                    $('#property-submition').attr('action', action_url);
                    
                    $('#property-submition').addClass("confirmed2");
                    $('#property-submition').submit();
                },
                cancel: function(button) {
                    
                    var action_url = $('#property-submition').attr('action');
                    action_url = action_url.replace("#content", "?date_alert=false");
                    $('#property-submition').attr('action', action_url);

                    $('#property-submition').addClass("confirmed2");
                    $('#property-submition').submit();
                },
                hidden: function(button) {
                    $('#ajax-indicator-1').hide();
                }
            });
        }
        else
        {
            var reduced_price = $("#price_detection span:first").html();
            reduced_price = parseFloat(reduced_price)*0.9;
            
            if($.isNumeric(reduced_price))
            {
                
                reduced_price = format_number_message(reduced_price);
                
                var message_confirm = "<?php _l('price-reduction-benefits', '', array("\r\n"=>"")); ?>";
                message_confirm = message_confirm.replace("{price-reducted}", reduced_price); 
                
                var message_yes = "<?php _l('price-reduction-yes', '', array("\r\n"=>"")); ?>";
                message_yes = message_yes.replace("{price-reducted}", reduced_price); 
                
                var message_no = "<?php _l('price-reduction-no', '', array("\r\n"=>"")); ?>";
                message_no = message_no.replace("{price-reducted}", reduced_price); 
                
                $.confirm({
                    text: message_confirm,
                    confirmButton: message_yes,
                    cancelButton: message_no,
                    confirm: function(button) {
                        // reduce price automatically
                        $('div.tabbable>div.tab-content>div.tab-pane').each(function(){
                            var price_new = $("#inputOption_"+$(this).attr('id')+"_36").val().replace(',', '');;
                            var price_old = $("span.old_price_"+$(this).attr('id')).html().replace(',', '');;
                            
                            $("#inputOption_"+$(this).attr('id')+"_36").val( (parseFloat(price_old)*0.9).toFixed(2) );
                        });
                        $('#property-submition').addClass("confirmed2");
                        $('#property-submition').submit();
                    },
                    cancel: function(button) {
                        $('#property-submition').addClass("confirmed2");
                        $('#property-submition').submit();
                    },
                    hidden: function(button) {
                        $('#property-submition').removeClass("confirmed2");
                        $('#ajax-indicator-1').hide();
                    }
                });
            }
            else
            {
                $('#property-submition').addClass("confirmed2");
                return true;
            }
            
        }
        
        return false;
    });

});
<?php endif; ?>

</script>


<span class="hidden" id="price_detection">
</span>

<script>

/* hint */
$(document).ready(function(){

    $('.hint').on({
      "click": function(e) {
            e.preventDefault();
            if(!$(this).find('.hint-notice').length){
                $(this).append('<div class="hint-notice"><span class="hint-message"> '+$(this).attr("data-hint")+' </span> \n\
                    </div><i class="hint-arrow"></i>\n\
                ')
              
              // if small message
                if($(this).find('.hint-message').width() < 60) {
                    $(this).find('.hint-notice').css('left','-10px')
                }
              
                $('.hint-notice').animate({
                    opacity:1, bottom: '16px'
                }, 300, "easeInOutCubic");
                
                $('.hint .hint-arrow').animate({
                    opacity:1, bottom: '11px'
                }, 300, "easeInOutCubic");
            
          }
      },
      "hover": function(e) {
            e.preventDefault();
          if(!$(this).find('.hint-notice').length){
                $(this).append('<div class="hint-notice"><span class="hint-message"> '+$(this).attr("data-hint")+' </span> \n\
                    </div><i class="hint-arrow"></i>\n\
                ')
              
              // if small message
                if($(this).find('.hint-message').width() < 60) {
                    $(this).find('.hint-notice').css('left','-10px')
                }
              
                $('.hint-notice').animate({
                    opacity:1, bottom: '16px'
                }, 300, "easeInOutCubic");
                
                $('.hint .hint-arrow').animate({
                    opacity:1, bottom: '11px'
                }, 300, "easeInOutCubic");
            
          }
      },
    "mouseout": function() {      
       $('.hint-notice', this).remove();   
       $('.hint-arrow', this).remove();   
    }
    });
})

/* end hint */

/* Fixed lang bar */
$(function(){
    $('.lang-tabs').affix({
      offset: {
        top: function(){
            return (this.top = $('.lang-tabs').offset().top);
        }
      }
    })
    
    // first load
    if($('.lang-tabs').hasClass('affix')) {
        $('.lang-tabs').css('width', $('.lang-tabs').closest('.affix-parent').width());

        if($('.head-wrapper').length && $('.head-wrapper').css('background-color'))
            $('.lang-tabs').css('background', $('.head-wrapper').css('background-color'));
    }

    $(window).scroll(function() {
        if($('.lang-tabs').hasClass('affix')) {
           $('.lang-tabs').css('width', $('.lang-tabs').closest('.affix-parent').width());

            if($('.head-wrapper').length && $('.head-wrapper').css('background-color'))
                $('.lang-tabs').css('background', $('.head-wrapper').css('background-color'));
        } else {
            $('.lang-tabs').css('width', '100%'); 
            if($('.head-wrapper').length && $('.head-wrapper').css('background-color'))
                $('.lang-tabs').css('background', 'inherit');
        }
    })
})
/* end Fixed lang bar */
</script>
  </body>
</html>