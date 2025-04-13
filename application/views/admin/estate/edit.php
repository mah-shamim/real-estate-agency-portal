<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Estate')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($estate->id) ? lang('Add a estate') : lang('Edit estate').' "' . $estate->id.'"'?></span>
        </h2>
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>
    
    <div class="clearfix"></div>
 
</div>

<div class="matter">
        <div class="container">
        
            <div class="row">
                <div class="col-md-12" style="text-align:right;"> 
                <?php if(!empty($estate->id) && file_exists(APPPATH.'controllers/admin/booking.php')): ?>
                    <?php echo anchor('admin/booking/rates?smart_search='.$estate->id, '<i class="icon-shopping-cart"></i>&nbsp;&nbsp;'.lang_check('Property rates'), 'class="btn btn-primary"')?>
                <?php endif; ?>
                <?php if(!empty($estate->id) && check_acl('estate/clone_listing')): ?>
                    <?php echo anchor('admin/estate/clone_listing/'.$estate->id, '<i class="icon-share"></i>&nbsp;&nbsp;'.lang('Clone Listing'), 'class="btn btn-primary"')?>
                <?php endif; ?>
                <?php if(!empty($estate->id) && file_exists(APPPATH.'controllers/admin/swin_reviews.php') && check_acl('reviews')): ?>
                    <?php echo anchor('admin/reviews/index/'.$estate->id, '<i class="icon-star"></i>&nbsp;&nbsp;'.lang('Reviews'), 'class="btn btn-primary"')?>
                <?php endif; ?>
                </div>
            </div>

          <div class="row">

            <div class="col-md-8">
              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Estate data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal form-estate', 'role'=>'form'))?>                              
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Address')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('address', set_value('address', $estate->address), 'class="form-control" id="inputAddress" placeholder="'.lang('Address').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Gps')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('gps', set_value('gps', $estate->gps), 'class="form-control" id="inputGps" placeholder="'.lang('Gps').'" readonly')?>
                                  </div>
                                </div>
                                <?php if(config_item('required_images') !== FALSE):?>
                                <div class="form-group hidden">
                                  <label class="col-lg-3 control-label"><?php echo lang('repository_id')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('repository_id', _ch($estate->repository_id, ''), 'class="form-control" id="inputrepository_id" placeholder="'.lang('Repository_id').'" readonly')?>
                                  </div>
                                </div>
                                <?php endif;?>
                    
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('DateTime')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('date', set_value('date', $estate->date), 'class="form-control" id="inputDate" readonly placeholder="'.lang('DateTime').'"')?>
                                  </div>
                                </div>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('DateModified')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('date_modified', set_value('date_modified', $estate->date_modified), 'class="form-control" id="input_date_modified" placeholder="'.lang('DateModified').'"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN'):?>
                                
                                <?php if(config_db_item('transitions_id_enabled') === TRUE): ?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php _l('Transitions id')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('id_transitions', set_value('id_transitions', $estate->id_transitions), 'class="form-control" id="input_id_transitions" placeholder="'.lang_check('Transitions id').'"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Agent')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_dropdown_ajax('agent', 'user_m', set_value('agent', $estate->agent), 'name_surname');?>
                                  </div>
                                </div>
                                <?php elseif(isset($this->data['related_users_id'][$this->data['estate']->agent])):?>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Agent')?></label>
                                  <div class="col-lg-9">
                                     <?php echo form_dropdown('agent', array(''=>lang_check('Related agents'))+$related_users_dropdown, set_value('agent', $estate->agent), 'class="form-control"'); ?>
                                  </div>
                                </div>
                    
                                <?php endif;?>
                                
                                <?php if($this->session->userdata('type') == 'AGENT_LIMITED'):?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Featured')?></label>
                                  <div class="col-lg-9">
                                  <?php
                                  $status = '<i class="icon-remove"></i>';
                                  if(set_value('is_featured', $estate->is_featured) == '1')
                                  {
                                       $status = '<i class="icon-ok"></i>';
                                  }
                                  echo $status;
                                  ?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Activated')?></label>
                                  <div class="col-lg-9">
                                  <?php
                                  $status = '<i class="icon-remove"></i>';
                                  if(set_value('is_activated', $estate->is_activated) == '1')
                                  {
                                       $status = '<i class="icon-ok"></i>';
                                  }
                                  echo $status;
                                  ?>
                                  </div>
                                </div>
                                <?php else:?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Featured')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('is_featured', '1', set_value('is_featured', $estate->is_featured), 'id="inputFeatured"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Activated')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('is_activated', '1', set_value('is_activated', $estate->is_activated), 'id="inputActivated"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang_check('Listing visible for public');?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('is_visible', '1', set_value('is_visible', $estate->is_visible), 'id="inputVisible"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <hr />
                                <h5><?php echo lang('Translation data')?></h5>
                                <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs language_tabs">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?> lang"><a data-toggle="tab" href="#<?php echo $key?>"><?php echo $val?></a></li>
                                    <?php endforeach;?>
                                    
                                    <li class="pull-right top-bar-btn"><?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?></li>
                                    
                                    <?php if(sw_count($this->option_m->languages) > 1): ?>
                                    <li class="pull-right"><a href="#" id="copy-lang" class="btn btn-default" type="button"><?php echo lang_check('Copy to other languages')?></a></li>
                                    <li class="pull-right"><a href="#" id="translate-lang" rel="<?php echo site_url('api/translate/');  ?>" class="btn btn-default" type="button"><?php echo lang_check('Translate to other languages')?></a></li>
                                    <?php endif; ?>
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->option_m->languages as $key=>$val):$i++;?>
                                    <div id="<?php echo $key?>" class="tab-pane <?php echo $i==1?'active':''?> row row-listingedit">
                                    
                                        <?php if(config_db_item('slug_enabled') === TRUE): ?>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"><?php echo lang_check('URI slug')?></label>
                                          <div class="col-lg-9">
                                            <?php echo form_input('slug_'.$key, set_value('slug_'.$key, $estate->{'slug_'.$key}), 'class="form-control" id="inputOption_'.$key.'_slug" placeholder="'.lang_check('URI slug').'"')?>
                                          </div>
                                        </div>
                                        <?php endif; ?>
                                    
                                        <?php foreach($options as $key_option=>$val_option):?>
                                        
                                        <?php
                                        $required_text = '';
                                        $required_notice = '';
                                        if($val_option->is_required == 1)
                                        {
                                            $required_text = 'required';
                                            $required_notice = '*';
                                        }
                                        
                                        $max_length_text = '';
                                        if($val_option->max_length > 0)
                                        {
                                            $max_length_text = 'maxlength="'.$val_option->max_length.'"';
                                        }
                                        $is_not_translatable = false;
                                        
                                        
                                        
                                        if($key != $this->language_m->get_default_id() && isset($val_option->is_not_translatable) && $val_option->is_not_translatable==1) {
                                            $is_not_translatable = true;
                                        }
                                        
                                        $columns ='col-sm-12';

                                        if($val_option->columns_number)
                                            switch ($val_option->columns_number) {
                                                case 1: $columns ='col-sm-12 multi_columns';
                                                        break;
                                                case 2: $columns ='col-sm-6 multi_columns';
                                                        break;
                                                case 3: $columns ='col-sm-4 multi_columns';
                                                        break;
                                            }
                                        ?>
                                        
                                        <?php if($val_option->type == 'CATEGORY'):?>
                                        <hr />
                                        <h5><?php echo $val_option->option?> <span class="checkbox-visible"><?php echo form_checkbox('option'.$val_option->id.'_'.$key, 'true', set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'id="inputOption_'.$key.'_'.$val_option->id.'"')?> <?php echo lang_check('Hidden on preview page'); ?></span></h5>
                                        <hr />
                                        <?php elseif($val_option->type == 'INPUTBOX' || $val_option->type == 'DECIMAL' || $val_option->type == 'INTEGER'):?>
                                            <div class="form-group <?php _che($columns);?> <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                                <label class="col-lg-3 control-label"><?php echo $required_notice.$val_option->option?> <?php if(!empty($val_option->hint)):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option->hint;?>"></i><?php endif;?></label>
                                                <?php if($is_not_translatable):?>
                                                 <div class="col-lg-9">
                                                <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                    <?php else:?>
                                              <div class="<?php echo empty($options_lang[$key][$key_option]->prefix)&&empty($options_lang[$key][$key_option]->suffix)?'col-lg-9':'col-lg-6'; ?>">
                                                <?php echo form_input('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control '.$val_option->type.'" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'" '.$required_text.' '.$max_length_text)?>
                                              </div>
                                              <?php if(!empty($options_lang[$key][$key_option]->prefix) || !empty($options_lang[$key][$key_option]->suffix)): ?>
                                              <div class="col-lg-3">
                                                <?php echo $options_lang[$key][$key_option]->prefix.$options_lang[$key][$key_option]->suffix?>
                                              </div>
                                              <?php endif; ?>
                                                <?php endif;?>
                                            </div>
                                        <?php elseif($val_option->type == 'DROPDOWN'):?>
                                            <div class="form-group <?php _che($columns);?> <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                              <label class="col-lg-3 control-label"><?php echo $required_notice.$val_option->option?>  <?php if(!empty($val_option->hint)):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option->hint;?>"></i><?php endif;?></label>
                                              <div class="col-lg-9">
                                                <?php if($is_not_translatable):?>
                                                <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                <?php else:?>
                                                <?php
                                                if(isset($options_lang[$key][$key_option]))
                                                    $drop_options = array_combine(explode(',',check_combine_set(isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', $val_option->values, '')),explode(',',check_combine_set($val_option->values, isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', '')));
                                                else
                                                    $drop_options = array();
                                                
                                                /* add first empty value */
                                                $drop_options = array(''=>lang_check('Select').' '.$val_option->option) + $drop_options;
                                                
                                                // If you don't want translation to admin interface langauge uncomment this 1 line below:
                                                //$drop_options = array_combine(explode(',', $options_lang[$key][$key_option]->values), explode(',', $options_lang[$key][$key_option]->values));

                                                $drop_selected = set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:'');
                                                
                                                echo form_dropdown('option'.$val_option->id.'_'.$key, $drop_options, $drop_selected, 'class="form-control" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'" '.$required_text)
                                                
                                                ?>
                                                <?php //=form_dropdown('option'.$val_option->id.'_'.$key, explode(',', $options_lang[$key][$key_option]->values), set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control" id="inputOption_'.$val_option->id.'" placeholder="'.$val_option->option.'"')?>
                                                <?php endif;?>
                                              </div>
                                              
                                            </div>
                                        <?php elseif($val_option->type == 'DROPDOWN_MULTIPLE' && config_item('field_dropdown_multiple_enabled') === TRUE):?>
                                            <div class="form-group <?php _che($columns);?> <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                              <label class="col-lg-3 control-label"><?php echo $required_notice.$val_option->option?>  <?php if(!empty($val_option->hint)):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option->hint;?>"></i><?php endif;?></label>
                                              <div class="col-lg-9">
                                                <?php if($is_not_translatable):?>
                                                <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                <?php else:?>
                                                <?php
                                                if(isset($options_lang[$key][$key_option]))
                                                    $drop_options = array_combine(explode(',',check_combine_set(isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', $val_option->values, '')),explode(',',check_combine_set($val_option->values, isset($options_lang[$key])?$options_lang[$key][$key_option]->values:'', '')));
                                                else
                                                    $drop_options = array();
                                                
                                                /* add first empty value */
                                                $drop_options = array(''=>lang_check('Select').' '.$val_option->option) + $drop_options;

                                                // If you don't want translation to admin interface langauge uncomment this 1 line below:
                                                //$drop_options = array_combine(explode(',', $options_lang[$key][$key_option]->values), explode(',', $options_lang[$key][$key_option]->values));

                                                
                                                $drop_selected = set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:'');
                                                
                                                echo form_dropdown('option'.$val_option->id.'_'.$key, $drop_options, $drop_selected, 'class="form-control" id="inputOption_'.$key.'_'.$val_option->id.'"  placeholder="'.$val_option->option.'" '.$required_text)
                                                
                                                ?>
                                                <?php //=form_dropdown('option'.$val_option->id.'_'.$key, explode(',', $options_lang[$key][$key_option]->values), set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control" id="inputOption_'.$val_option->id.'" placeholder="'.$val_option->option.'"')?>
                                                <?php endif;?>
                                              </div>
                                            </div>
                                        <?php elseif($val_option->type == 'TEXTAREA'):?>
                                            <?php
                                            if(!empty($required_text)) {
                                                $required_text = 'data-required ="true"';
                                            }
                                            
                                            ?>
                                            <?php if($is_not_translatable):?>
                                            <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                            <?php else:?>
                                            <div class="form-group <?php _che($columns);?> <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                              <label class="col-lg-3 control-label"><?php echo $required_notice.$val_option->option?>  <?php if(!empty($val_option->hint)):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option->hint;?>"></i><?php endif;?></label>
                                              <div class="col-lg-9">
                                                <?php echo form_textarea('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="ckeditor form-control" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'" '.$required_text)?>
                                              </div>
                                            </div>
                                            <?php endif;?>
                                        <?php elseif($val_option->type == 'TREE' && config_item('tree_field_enabled') === TRUE):?>
                                            <?php if($settings['template']!='boomerang'){
                                                        $required_text = '';
                                                        $required_notice = '';
                                                    }
                                            ?>
                                        
                                            <div class="form-group <?php _che($columns);?> TREE-GENERATOR">
                                              <label class="col-lg-3 control-label">
                                              <?php echo $required_notice.$val_option->option?>
                                              <div class="ajax_loading"> </div>
                                              </label>
                                              <div class="col-lg-9">
                                                <?php if($is_not_translatable):?>
                                                <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                <?php else:?>
                                                <?php
                                                $drop_options = $this->treefield_m->get_level_values($key, $key_option);
                                                $drop_selected = array();
                                                
                                                echo '<div class="field-row">';
                                                echo form_dropdown('option'.$val_option->id.'_'.$key.'_level_0', $drop_options, $drop_selected, 'class="form-control" id="inputOption_'.$key.'_'.$val_option->id.'_level_0'.'" placeholder="'.$val_option->option.'" '.$required_text.'');
                                                echo '</div>';
                                                
                                                
                                                $levels_num = $this->treefield_m->get_max_level($key_option);
                                                
                                                if($levels_num>0)
                                                for($ti=1;$ti<=$levels_num;$ti++)
                                                {
                                                    echo '<div class="field-row">';
                                                    echo form_dropdown('option'.$val_option->id.'_'.$key.'_level_'.$ti, array(''=>lang_check('Please select parent')), array(), 'class="form-control" id="inputOption_'.$key.'_'.$val_option->id.'_level_'.$ti.'" placeholder="'.$val_option->option.'"');
                                                    echo '</div>';
                                                }

                                                ?>
                                                <div class="field-row hidden">
                                                <?php echo form_input('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control tree-input-value" id="inputOption_'.$key.'_'.$val_option->id.'" rel="" placeholder="'.$val_option->option.'"')?>
                                                </div>
                                                <?php endif;?>
                                              </div>
                                            </div>
                                        <?php elseif($val_option->type == 'UPLOAD'):?>
                                            <div class="form-group  <?php _che($columns);?> UPLOAD-FIELD-TYPE">
                                              <label class="col-lg-3 control-label">
                                              <?php echo $val_option->option?>
                                              <div class="ajax_loading"> </div>
                                              </label>
                                                <?php if($is_not_translatable):?>
                                                <div class="col-lg-9">
                                                <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                 </div>
                                                <?php else:?>
                                              <div class="col-lg-9">
<div class="field-row hidden">
<?php echo form_input('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:'SKIP_ON_EMPTY'), 'class="form-control skip-input" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'"')?>
</div>
<?php //if(empty($estate->id) || !isset($estate->{'option'.$val_option->id.'_'.$key})): ?>
<?php if( empty($estate->id) ): ?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else: ?>
<!-- Button to select & upload files -->
<span class="btn btn-success fileinput-button">
    <span>Select files...</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload_<?php echo $val_option->id.'_'.$key; ?>" class="FILE_UPLOAD file_<?php echo $val_option->id.'_'.$key; ?>" type="file" name="files[]" multiple>
</span><br style="clear: both;" />
<!-- The global progress bar -->
<p>Upload progress</p>
<div id="progress_<?php echo $val_option->id.'_'.$key; ?>" class="progress progress-success progress-striped">
    <div class="bar"></div>
</div>
<!-- The list of files uploaded -->
<p>Files uploaded:</p>
<ul id="files_<?php echo $val_option->id.'_'.$key; ?>">
<?php 
if(isset($estate->{'option'.$val_option->id.'_'.$key})){
    $rep_id = $estate->{'option'.$val_option->id.'_'.$key};
    
    //Fetch repository
    $file_rep = $this->file_m->get_by(array('repository_id'=>$rep_id));
    if(sw_count($file_rep)) foreach($file_rep as $file_r)
    {
        $delete_url = site_url_q('files/upload/rep_'.$file_r->repository_id, '_method=DELETE&amp;file='.rawurlencode($file_r->filename));
        
        echo "<li><a target=\"_blank\" href=\"".base_url('files/'.$file_r->filename)."\">$file_r->filename</a>".
             '&nbsp;&nbsp;<button class="btn btn-xs btn-danger" data-type="POST" data-url='.$delete_url.'><i class="icon-trash icon-white"></i></button></li>';
    }
}
?>
</ul>

<!-- JavaScript used to call the fileupload widget to upload files -->
<script language="javascript">
// When the server is ready...
$( document ).ready(function() {
    
    // Define the url to send the image data to
    var url_<?php echo $val_option->id.'_'.$key; ?> = '<?php echo site_url('files/upload_field/'.$estate->id.'_'.$val_option->id.'_'.$key);?>';
    
    // Call the fileupload widget and set some parameters
    $('#fileupload_<?php echo $val_option->id.'_'.$key; ?>').fileupload({
        url: url_<?php echo $val_option->id.'_'.$key; ?>,
        autoUpload: true,
        dropZone: $('#fileupload_<?php echo $val_option->id.'_'.$key; ?>'),
        dataType: 'json',
        done: function (e, data) {
            // Add each uploaded file name to the #files list
            $.each(data.result.files, function (index, file) {
                if(!file.hasOwnProperty("error"))
                {
                    $('#files_<?php echo $val_option->id.'_'.$key; ?>').append('<li><a href="'+file.url+'" target="_blank">'+file.name+'</a>&nbsp;&nbsp;<button class="btn btn-xs btn-danger" data-type="POST" data-url='+file.delete_url+'><i class="icon-trash icon-white"></i></button></li>');
                    added=true;
                }
                else
                {
                    ShowStatus.show(file.error);
                }

            });
            
            //console.log(data.result.repository_id);
            //console.log('<?php echo '#inputOption_'.$key.'_'.$val_option->id; ?>');
            $('<?php echo '#inputOption_'.$key.'_'.$val_option->id; ?>').attr('value', data.result.repository_id);
            
            reset_events_<?php echo $val_option->id.'_'.$key; ?>();
        },
        progressall: function (e, data) {
            // Update the progress bar while files are being uploaded
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress_<?php echo $val_option->id.'_'.$key; ?> .bar').css(
                'width',
                progress + '%'
            );
        }
    });
    
    reset_events_<?php echo $val_option->id.'_'.$key; ?>();
});

function reset_events_<?php echo $val_option->id.'_'.$key; ?>(){
    $("#files_<?php echo $val_option->id.'_'.$key; ?> li button").unbind();
    $("#files_<?php echo $val_option->id.'_'.$key; ?> li button.btn-danger").click(function(){
        var image_el = $(this);
        
        $.post($(this).attr('data-url'), function( data ) {
            var obj = jQuery.parseJSON(data);
            
            if(obj.success)
            {
                image_el.parent().remove();
            }
            else
            {
                ShowStatus.show('<?php echo _js(lang_check('Unsuccessful, possible permission problems or file not exists')); ?>');
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
                                        <?php elseif($val_option->type == 'CHECKBOX'):?>
                                            <div class="form-group <?php _che($columns);?> type_checkbox <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                              <label class="col-lg-3 control-label"><?php echo $required_notice.$val_option->option?></label>
                                              <div class="col-lg-9">
                                                <?php if($is_not_translatable):?>
                                                    <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                                <?php else:?>
                                                <?php echo form_checkbox('option'.$val_option->id.'_'.$key, 'true', set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'id="inputOption_'.$key.'_'.$val_option->id.'" class="valid_parent" '.$required_text)?>
                                                <?php
                                                    if(file_exists(FCPATH.'templates/'.$settings['template'].'/assets/img/icons/option_id/'.$val_option->id.'.png'))
                                                    {
                                                        echo '<img class="results-icon" src="'.base_url('templates/'.$settings['template'].'/assets/img/icons/option_id/'.$val_option->id.'.png').'" alt="'.$val_option->option.'"/>';
                                                    }
                                                ?>
                                                <?php endif;?>
                                              </div>
                                            </div>
                                        <?php elseif($val_option->type == 'SCHEDULING_TABLE' && config_item('enable_field_scheduling_table') === TRUE):?>
                                            <?php if($is_not_translatable):?>
                                              <div class="col-lg-12">
                                                <div class="alert alert-warning non-translatable" role="alert"><?php echo lang_Check('Not translatable');?></div>
                                              </div>
                                            <?php else:?>
                                            <div class="form-group type_SCHEDULING_TABLE <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                                 <div class="" style="padding:15px 15px;">
                                                     <?php

                                                       $field_value = $estate->{'option'.$val_option->id.'_'.$key};
                                                       $field_value_json = false;
                                                       if(!empty($field_value)) {
                                                            @$obj = json_decode($field_value,true); 
                                                            
                                                            if(!json_last_error()) {
                                                                $field_value_json = $obj;
                                                            }
                                                       }
                                                        // $field_value=preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $field_value);
                                                       $scheduling_days = array('cal_sunday','cal_monday','cal_tuesday','cal_wednesday','cal_thursday','cal_friday','cal_saturday');
                                                       $scheduling_type = array('from','to');

                                                       $col_list_explode = range(0, 23);
                                                       $col_list_explode_2 = array('00','15','30','45');
                                                       $scheduling_values = array();
                                                       $scheduling_values['']='-';
                                                       foreach($col_list_explode as $val)
                                                       {
                                                           foreach ($col_list_explode_2 as $value) {
                                                               $scheduling_values[$val.':'.$value]=$val.':'.$value;
                                                           }
                                                       }


                                                     ?>
                                                       <table id="editable_table_scheduling_<?php echo $val_option->id.'_'.$key; ?>" class="<?php echo 'option'.$val_option->id.'_'.$key;?> editable_table_scheduling table table-striped table-bordered" style="border-left: 1px solid #CCC !important;border-top: 1px solid #CCC !important;">
                                                       <thead>
                                                       <tr>
                                                       <th><?php echo $val_option->option?></th>
                                                       <?php foreach($scheduling_days as $col_val): ?>
                                                           <th><?php echo lang_check($col_val); ?></th>
                                                       <?php endforeach; ?>
                                                       </tr>
                                                       </thead>
                                                       <tbody>
                                                       <?php foreach($scheduling_type as $type): ?>
                                                       <tr>
                                                       <td>
                                                           <?php if($type=='from'):?>
                                                           <?php echo lang_check('Open');?>
                                                           <?php elseif($type=='to'):?>
                                                           <?php echo lang_check('Close');?>
                                                           <?php endif;?>
                                                       </td>
                                                       <?php foreach($scheduling_days as $col_val): ?>
                                                           <td>
                                                               <?php
                                                               $drop_selected = '';
                                                               $scheduling_values_cur = array();
                                                               
                                                               if($field_value_json && isset($field_value_json[trim($col_val.'_'.$type)]))
                                                                    $drop_selected = $field_value_json[$col_val.'_'.$type];
                                                              
                                                               echo form_dropdown('edit_table'.$val_option->id.'_'.$key.'_'.$type.$col_val, $scheduling_values, $drop_selected, ' data-name="'.$col_val.'_'.$type.'" class="form-control" placeholder="" ');
                                                               ?>
                                                           </td>
                                                       <?php endforeach; ?>
                                                       </tr>
                                                       <?php endforeach; ?>
                                                       </tbody>
                                                       </table>
                                                       <?php echo form_textarea('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control hidden" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'" '.$required_text)?>
                                                 </div>
                                               <script>

                                               $(document).ready(function(){
                                                   $('.<?php echo 'option'.$val_option->id.'_'.$key;?>').find('select').change(function(){
                                                       var js_gen = {};
                                                       var f_e = true;
                                                       $('.<?php echo 'option'.$val_option->id.'_'.$key;?>').find('select').each(function(){
                                                           js_gen[$(this).attr('data-name')]=$(this).val();
                                                           
                                                           if($(this).val()!='')
                                                               f_e = false;
                                                       })
                                                       var str = JSON.stringify(js_gen)
                                                       
                                                       str.replace(/[\x00-\x1F\x80-\xFF]/g,"");
                                                       
                                                        if(f_e) {
                                                         str ='';  
                                                        }
                                                       
                                                       $('#<?php echo 'inputOption_'.$key.'_'.$val_option->id;?>').val(str);
                                                   })
                                               })
                                               </script>
                                               </div>
					    <?php endif;?>			
                                        <?php elseif($val_option->type == 'HTMLTABLE' && config_item('enable_table_input') === TRUE):?>
                                            <div class="form-group type_HTMLTABLE <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                              <label class="col-lg-3 control-label"><?php echo $val_option->option?></label>
                                              <div class="col-lg-9">
                                              <?php 
                                                $columns = explode(',', $val_option->values);
                                                $columns[] = lang_check('Edit');
                                              ?>
                                              
                                                    <table id="editable_table_<?php echo $val_option->id.'_'.$key; ?>" class="table table-striped table-bordered table-hover" style="border-left: 1px solid #CCC !important;border-top: 1px solid #CCC !important;">
                                                    <thead>
                                                    <tr>
                                                    <?php foreach($columns as $col_val): ?>
                                                    
                                                        <?php
                                                        $to = strpos($col_val, '[');
                                                        if($to !== FALSE)$col_val =substr($col_val, 0, $to);
                                                        ?>
                                                    
                                                        <th><?php echo $col_val; ?></th>
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
                                                    
                                                    <?php echo form_textarea('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control hidden HTMLTABLE" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'" '.$required_text)?>

                                              </div>
                                              
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
            
            echo '<div id="col_'.$key.'_'.$key_option.'_'.$col_key.'" class="hidden">';            
            foreach($col_list_explode as $val)
            {
                echo '<span>'.trim($val).'</span>';
            }
            echo '</div>';
        }
    }

?>
                                              
                                            </div>

                                            <script type="text/javascript">
                                            
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

                                                
                                                generate_table('<?php echo $val_option->id.'_'.$key; ?>', table_options);
                                                
                                                table_add_events('<?php echo $val_option->id.'_'.$key; ?>');
                                            });

                                            </script>
                                        <?php elseif($val_option->type == 'PEDIGREE' && config_item('enable_pedigree_input') === TRUE):?>
                                            <div class="form-group type_PEDIGREE <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                              <label class="col-lg-3 control-label"><?php echo $val_option->option?></label>
                                              <div class="col-lg-9">
                                              <?php 
                                                $columns = explode(',', $val_option->values);
                                                $columns[] = lang_check('Edit');
                                              ?>
                                              
                                              <div class="PEDIGREE_wrap1">
                                              <div class="PEDIGREE_container overflow <?php echo 'id'.$val_option->id.'_'.$key; ?>">
<div>
<ul id="<?php echo 'id'.$val_option->id.'_'.$key; ?>" class='tree'>
<?php 


$val =  set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:'');

if(empty($val))
{
    echo "<li><div id=$key><span class='first_name'>1</span></div></li>";
}
else
{
    echo html_entity_decode($val);
}

?>

</ul>
</div>
                                              </div>
                                              </div>
                                                    
                                              <?php echo form_textarea('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="form-control hidden PEDIGREE" id="inputOption_'.$key.'_'.$val_option->id.'" placeholder="'.$val_option->option.'" '.$required_text)?>

                                              </div>
                                            </div>
<script type="text/javascript">

$(function () {
    generate_pedigree_tree('<?php echo $val_option->id.'_'.$key; ?>');
});

</script>
                                        <?php elseif($val_option->type == 'DATETIME' && config_item('field_datetime_enabled')=== TRUE):?>
                                            <div class="form-group <?php _che($columns);?> <?php echo (!$val_option->is_frontend && $this->session->userdata('type') == 'AGENT_LIMITED'?' hidden':'') ?>">
                                                <label class="col-lg-3 control-label"><?php echo $required_notice.$val_option->option?> <?php if(!empty($val_option->hint)):?><i class="icon-question-sign hint" data-hint="<?php echo $val_option->hint;?>"></i><?php endif;?></label>
                                              <div class="col-lg-9">
                                                <div class="input-append" id="datetimepicker_field_<?php _che($key);?>_<?php _che($val_option->id);?>">
                                                    <?php
                                                    $date_format = 'yyyy-MM-dd';
                                                    if(isset($settings['js_date_format']) && !empty($settings['js_date_format'])){
                                                        $date_format = $settings['js_date_format'];

                                                    }
                                                    ?>
                                                    
                                                    <?php echo form_input('option'.$val_option->id.'_'.$key, set_value('option'.$val_option->id.'_'.$key, isset($estate->{'option'.$val_option->id.'_'.$key})?$estate->{'option'.$val_option->id.'_'.$key}:''), 'class="picker '.$val_option->type.'" id="inputOption_'.$key.'_'.$val_option->id.'"  data-format="'.$date_format.'" placeholder="'.$val_option->option.'" '.$required_text.' '.$max_length_text)?>
                                                    <span class="add-on">
                                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                                      </i>
                                                    </span>
                                                </div> 
                                              </div>
                                            </div>

                                            <script>
                                              $(function() {
                                                    $('#datetimepicker_field_<?php _che($key);?>_<?php _che($val_option->id);?>').datetimepicker({
                                                      pickTime: false
                                                    });
                                                    
                                                    <?php 
                                                    /* updated by date format */
                                                    if(isset($estate->{'option'.$val_option->id.'_'.$key}) && !empty($estate->{'option'.$val_option->id.'_'.$key})):?>
                                                        $('#inputOption_<?php _che($key);?>_<?php _che($val_option->id);?>').change();
                                                    <?php endif;?>
                                                });
                                            </script>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <div class="col-lg-offset-3 col-lg-9">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/estate')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>
            
            
            <div class="col-md-4">
              <div class="widget wblue">
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Location')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="gmap" id="mapsAddress">

                  </div>
                </div>
                <?php if(!empty($estate->id)):?>
                <div class="clearfix" style="margin-top: 15px;">
                    <a href="<?php echo site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$estate->id);?>?preview=true" target="_blank" class="btn btn-default pull-right"><?php echo lang_check('Preview listing');?></a>
                </div>
                <?php endif;?>

              </div> 
            </div>

          </div>
          
          <div class="row">
        <div class="col-md-12">

              <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Listing Images')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">


    <?php if(!isset($estate->id)):?>
    <span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
    <?php else:?>
    <div id="page-files-<?php echo $estate->id?>" rel="estate_m">
        <!-- The file upload form used as target for the file upload widget -->
        <form class="fileupload" action="<?php echo site_url('files/upload_estate/'.$estate->id);?>" method="POST" enctype="multipart/form-data">
            <!-- Redirect browsers with JavaScript disabled to the origin page -->
            <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/estate/edit/'.$estate->id);?>"></noscript>
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="fileupload-buttonbar">
                <div class="span7 col-md-7">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span><?php echo lang('add_files...')?></span>
                        <input type="file" name="files[]" multiple>
                    </span>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="icon-ban-circle icon-white"></i>
                        <span><?php echo lang('cancel_upload')?></span>
                    </button>
                    <button type="button" class="btn btn-danger delete">
                        <i class="icon-trash icon-white"></i>
                        <span><?php echo lang('delete_selected')?></span>
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

                <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">      
    <?php if(isset($files[$estate->repository_id]))foreach($files[$estate->repository_id] as $file ):?>
                <li class="img-rounded template-download fade in">
                    <div class="preview">
                        <img class="img-rounded" alt="<?php echo $file->filename?>" data-src="<?php echo $file->thumbnail_url?>" src="<?php echo $file->thumbnail_url?>">
                    </div>
                    <div class="filename">
                        <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                    </div>
                    <div class="options-container">
                        <?php if($file->zoom_enabled):?>
                        <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>                  
                        <a class="btn btn-xs btn-info iedit visible-inline-lg" rel="<?php echo $file->id?>" href="#<?php echo $file->filename?>"><i class="icon-pencil icon-white"></i></a>
                        <?php else:?>
                        <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>
                        <?php endif;?>
                        <span class="delete">
                            <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="icon-trash icon-white"></i></button>
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
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  
            <?php if(config_item('plan_gallery_enabled') == TRUE):?>
              <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Plan Images')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">

<?php if(!isset($estate->id)):?>
<span class="label label-danger"><?php echo lang('After saving, you can add plan images');?></span>
<?php else:?>
<div id="page-files-<?php echo $estate->id?>" rel="estate_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo site_url('files/upload_repository/'.$estate->planimages_repository_id.'/');?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/estate/edit/'.$estate->id);?>"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <div class="span7 col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo lang('add_files...')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span><?php echo lang('cancel_upload')?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo lang('delete_selected')?></span>
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
            <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">   
                
        <?php if(isset($files[$estate->planimages_repository_id]))foreach($files[$estate->planimages_repository_id] as $file ):?>
            <li class="img-rounded template-download fade in">
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo $file->filename?>" data-src="<?php echo $file->thumbnail_url?>" src="<?php echo $file->thumbnail_url?>">
                </div>
                <div class="filename">
                    <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>                  
                    <a class="btn btn-xs btn-info iedit visible-inline-lg" rel="<?php echo $file->id?>" href="#<?php echo $file->filename?>"><i class="icon-pencil icon-white"></i></a>
                    <?php else:?>
                    <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="icon-trash icon-white"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
<?php endforeach;?>
            </ul>
            <br style="clear:both;"/>
          </div>
    </form>

    
    <script>
    
    
    
    
    </script>
</div>
<?php endif;?>

                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  
            <?php endif;?>    
            </div>
          </div>

        </div>
		  </div>
          
<script language="javascript">
    
    /* [START] Dependent fields */
    $(document).ready(function(){
        //console.log('Dependent fields loading');
        <?php 
        // Fetch dependent fields
        $CI =& get_instance();
        $CI->load->model('dependentfield_m');
        $dependent_fields = $CI->dependentfield_m->get();
        
        $dependent_fields_pre_prepare = array();
        $dependent_fields_prepare = array();
        foreach($dependent_fields as $key_d_field=>$d_field)
        {
            $dependent_fields_pre_prepare[$d_field->field_id][$d_field->selected_index] = $d_field->hidden_fields_list;
        }
        
        // Dependent fields right ordering
        
        foreach($options as $key_option=>$val_option)
        {
            if(isset($dependent_fields_pre_prepare[$val_option->id]))
            {
                $dependent_fields_prepare[$val_option->id] = $dependent_fields_pre_prepare[$val_option->id];
            }
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
            <?php if(config_item('auto_category_display_fields') !== FALSE):?>
                parent_elem_hide.nextAll().removeClass('hide')
                parent_elem_hide.prevAll().removeClass('hide')
            <?php else:?>
                parent_elem_hide.nextAll().removeClass('hide')
                parent_elem_hide.prevAll().removeClass('hide')
            <?php endif;?>
                
            //parent_elem_hide.nextAll().removeClass('hide')
            $(this).closest('.tab-pane').find('textarea, select, input').each(function(){
                if($(this).attr('data-required') == 'true') {
                    $(this).attr('required', 'required')
                }
            })
            
            <?php if(config_item('auto_category_display_fields') !== FALSE):?>
            if($(this).val() == '' && index == '') {
                var el = $(this).closest('.form-group');
                parent_elem_hide.nextAll().addClass('hide')
                parent_elem_hide.prevAll().addClass('hide')
                return;
            }
            <?php endif;?>
                
            if (typeof index_tree !== typeof undefined && index_tree !== false) {

              // include all parent elements

            var val = '';  
              $(this).parent().parent().find('select').each(function(){
                if($(this).val() != '')
                {
                    val = $(this).val();  
                }
              });

              hide_related_<?php echo $d_field_id.'_'.$id_lang; ?>(parent_elem, parent_elem_hide, val);
            }

            else
            {
                hide_related_<?php echo $d_field_id.'_'.$id_lang; ?>(parent_elem, parent_elem_hide, index);
            }
            
            //console.log(index);
        });
        
        $("select[name='option<?php echo $d_field_id.'_'.$id_lang; ?>']").trigger('change');
        
        <?php if(config_item('auto_category_display_fields') !== FALSE):?>
            $("input[rel][name='option<?php echo $d_field_id.'_'.$id_lang; ?>']").trigger('change');
        <?php endif;?>
        
        
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
                    else if(this.type=='select-one'){
                        this.value = $(this).find("option:first").val();
                        //if(this.value != '')this.value ='-';
                    }
                    else if(this.type=='select-multiple'){
                        this.value='';
                    }
                    
                    if($(this).attr('required')) {
                        $(this).removeAttr('required')
                        $(this).attr('data-required', 'true')
                    }
                    
                    if($(this).hasClass('tree-input-value')) {
                        $(this).closest('.form-group').find('select').each(function(){
                            if($(this).attr('required')) {
                                $(this).removeAttr('required')
                                $(this).attr('data-required', 'true')
                            }
                        })
                    }
                });
                
                // hide all below
                //parent_elem.find("<?php echo $generate_selector; ?>").parent().parent().addClass('hide');
                parent_elem.find("<?php echo $generate_selector; ?>").closest('.form-group,h5').addClass('hide');
                
                // hide all below <hr> if found below
                parent_elem.find("<?php echo $generate_selector; ?>").closest('.form-group,h5').each( function() {
                    var curr_elem = $(this);
                    if(!curr_elem.hasClass('form-group') &&
                       curr_elem.parent().hasClass('form-group') )
                    {
                        curr_elem = curr_elem.closest('.form-group');
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
                
                if(!find_selected.length && s_values_splited[0].indexOf('&')>=0) {
                    s_values_splited[0] =  s_values_splited[0].replace(/&/g, "&amp;");
                    find_selected = first_select.find('option').filter(function () { return $(this).html() == s_values_splited[0]; });
                }
                
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
            })
            .success(function(data){
                <?php if(config_item('auto_category_display')=== TRUE):?>
                //console.log(Object.keys(data.values_arr).length);
                // For old browser
                var count = 0;
                var i;
                for (i in data.values_arr) {
                    if (data.values_arr.hasOwnProperty(i)) {
                        count++;
                    }
                }
                //count = object.keys(data.values_arr).length;
                if(field_element.val() !='' &&  count > 1) {
                    field_element.closest('.field-row').next().show();
                } else {
                    field_element.closest('.field-row').nextAll().hide();
                }
                <?php endif;?>
            });
        } else {
            <?php if(config_item('auto_category_display')=== TRUE):?>
            field_element.closest('.field-row').nextAll().hide();
            <?php endif;?>
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
                $("#inputOption_"+s_lang_id+"_"+s_field_id).attr('rel', last_selected_val);
                $("#inputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);
                $("#inputOption_"+s_lang_id+"_"+s_field_id).trigger('change');
            }

        })
        .success(function(data){
            <?php if(config_item('auto_category_display')=== TRUE):?>
            //console.log(Object.keys(data.values_arr).length);
            // For old browser
            var count = 0;
            var i;
            for (i in data.values_arr) {
                if (data.values_arr.hasOwnProperty(i)) {
                    count++;
                }
            }
            //count = object.keys(data.values_arr).length;
            if(field_element.val() !='' &&  count > 1) {
                field_element.closest('.field-row').next().show();
            } else {
                field_element.closest('.field-row').nextAll().hide();
            }
            <?php endif;?>
        });

    }
    
    function isNumber(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    /* [END] TreeField */
    
    /* [START] NumericFields */
    
    $(function() {
        <?php if(config_db_item('swiss_number_format') == TRUE): ?>
        
        $('input.DECIMAL').number( true, 2, '.', '\'' );
        $('input.INTEGER').number( true, 0, '.', '\'' );
         
        <?php else: ?>
        
        $('input.DECIMAL').number( true, 2 );
        $('input.INTEGER').number( true, 0 );
        
        <?php endif; ?>
    });

    /* [END] NumericFields */
    
    /* [START] ValidateFields */
    
    $(function() {
        $('form.form-estate').h5Validate();
    });
    
    /* [END] ValidateFields */
    
    <?php if(isset($package_num_amenities_limit)): ?>
    $(document).ready(function(){
        $('.tab-pane .form-group input[type=checkbox]').change(function(event){
            var selected_checkboxes = $('.tab-pane.active .form-group input[type=checkbox]:checked').length;
            //console.log('changed');
            //console.log(selected_checkboxes);
            if(selected_checkboxes > <?php echo $package_num_amenities_limit; ?>)
            {
                $(this).prop('checked', false);
                ShowStatus.show('<?php echo lang_check('Limitation by package'); ?>: '+'<?php echo $package_num_amenities_limit; ?>');
            }
        });
    
    });
    <?php endif; ?>
    

    
    function generate_table(key_id, table_options)
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
        $('#editable_table_'+key_id).editableTableWidget(table_options);
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
            
            $(this).parent().parent().parent().parent().parent().find('input').val('');
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
<script src="<?php echo base_url('admin-assets/js/editable_table/mindmup-editabletable.js')?>"></script>

<link rel="stylesheet" href="<?php echo base_url('admin-assets/js/zebra/css/flat/zebra_dialog.css')?>">
<script src="<?php echo base_url('admin-assets/js/zebra/javascript/zebra_dialog.src.js')?>"></script>
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

<link rel="stylesheet" href="<?php echo base_url('admin-assets/js/pedigree/style.css')?>">
<script src="<?php echo base_url('admin-assets/js/pedigree/jquery-migrate-1.2.1.min.js')?>"></script>
<script src="<?php echo base_url('admin-assets/js/pedigree/jquery.tree.js')?>"></script>

<script>

function generate_pedigree_tree(id_key)
{
    var key_id_split = id_key.split("_"); 
    
    $('#id'+id_key+'.tree').tree_structure({
        'add_option': true,
        'edit_option': true,
        'delete_option': true,
        'confirm_before_delete': false,
        'animate_option': false,
        'fullwidth_option': true,
        'align_option': 'center',
        'draggable_option': true,
        'click_to_add' : '<?php _l('Click for Add'); ?>',
        'click_to_edit' : '<?php _l('Click for Edit'); ?>',
        'click_to_delete' : '<?php _l('Click for Delete'); ?>',
        'first_name' : '<?php _l('Title'); ?>',
        'submit' : '<?php _l('Submit'); ?>',
        'base_path': '<?php echo base_url('admin-assets/js/pedigree')?>/',
        'on_change': function(){
            var html_structire = $('#id'+id_key+'.tree').parent();
            html_structire.find('img.load').remove();            
            var html_generated = html_structire.find('.tree').html();
            
            $('#inputOption_'+key_id_split[1]+'_'+key_id_split[0]).val(html_generated);
            
        }
    });
}

</script>
