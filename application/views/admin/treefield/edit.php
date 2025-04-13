<div class="page-head">
    <!-- Page heading -->
    <h2 class="pull-left">
        <?php
        $field_name = '';
        if(isset($option->id) && !empty($option->id)) {
            $option_treefield = $this->option_m->get_lang($option->id);
            if($option_treefield)
                $field_name = $option_treefield->{'option_'.$content_language_id};
        }
        if(!empty($field_name)) {
            echo $field_name;
        }
        else {
            echo lang('TreeField values');
        }
        
        
        ?>
        <!-- page meta -->
        <span class="page-meta"><?php echo empty($option->id) ? lang('Add a TreeField') : lang('Edit TreeField') . ' #' . $option->id . '' ?></span>
    </h2>


    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
        <a href="<?php echo site_url('admin') ?>"><i class="icon-home"></i> <?php echo lang('Home') ?></a> 
        <!-- Divider -->
        <span class="divider">/</span> 
        <a class="bread-current" href="<?php echo site_url('admin/estate') ?>"><?php echo lang('Estates') ?></a>
        <!-- Divider -->
        <span class="divider">/</span> 
        <a class="bread-current" href="<?php echo site_url('admin/estate/options') ?>"><?php echo lang('Fields') ?></a>
        <!-- Divider -->
        <span class="divider">/</span> 
        <a class="bread-current" href="<?php echo site_url('admin/estate/edit_option/' . $option->id) ?>"><?php echo lang('Field') . ' #' . $option->id ?></a>
    </div>

    <div class="clearfix"></div>

</div>

<div class="matter">
    <div class="container" id="edit-form">

        <div class="row">
            <div class="col-md-12"> 
                <a href="<?php echo site_url('admin/treefield/edit/' . $option->id) . '#edit-form' ?>" class="btn btn-primary" type="button"><i class="icon-plus"></i>&nbsp;&nbsp;<?php echo lang('Add new') ?></a>                

                <?php echo anchor('admin/treefield/import_treefield/' . $option->id, '<i class="icon-arrow-up"></i>&nbsp;&nbsp;' . lang_check('Import from XML'), 'class="btn btn-success pull-right"') ?>
                
                <?php if(config_db_item('import_vehicle_enable')!== FALSE && isset($option->id) && $option->id == 79):?>
                    <?php echo anchor('admin/treefield/import_vehicle/' . $option->id, lang_check('Import Vehicle'), 'class="btn btn-success pull-right" style="margin-right: 5px;"') ?>
                <?php endif;?>
                
                <?php if(isset($option->id) && $option->id == 64 && file_exists(FCPATH.'templates/'.$settings['template'].'/assets/svg_maps/')):?>
                    <?php echo anchor('admin/treefield/generate_geo_map/' . $option->id, lang_check('Generate geo map'), 'class="btn btn-success pull-right" style="margin-right: 5px;"') ?>
                <?php endif;?>
                <?php if(isset($option->id) && $option->id == 64 && file_exists(FCPATH.'/world-cities.csv')):?>
                    <?php echo anchor('admin/treefield/generate_world/' . $option->id, lang_check('Generate Cities & Countries'), 'class="btn btn-success pull-right" style="margin-right: 5px;"') ?>
                <?php endif;?>
                
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">


                <div class="widget wlightblue">

                    <div class="widget-head">
                        <div class="pull-left"><?php echo lang_check('TreeField value data') ?></div>
                        <div class="widget-icons pull-right">
                            <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="widget-content">
                        <div class="padd">
                            <?php echo validation_errors() ?>
                            <?php if ($this->session->flashdata('message')): ?>
                                <?php echo $this->session->flashdata('message') ?>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error')): ?>
                                <p class="label label-important validation"><?php echo $this->session->flashdata('error') ?></p>
                            <?php endif; ?>
                            <hr />
                            <!-- Form starts.  -->
                            <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role' => 'form')) ?>                              

                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?php echo lang('Parent') ?></label>
                                <div class="col-lg-9">
                                    <?php echo form_dropdown('parent_id', $treefield_no_parents, $this->input->post('parent_id') ? $this->input->post('parent_id') : $treefield->parent_id, 'class="form-control"') ?>
                                </div>
                            </div>
                            <?php if (!empty($treefield->id)): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('Template') ?></label>
                                    <div class="col-lg-9">
                                        <?php
                                        echo form_dropdown('template', $templates_trefield, $this->input->post('template') ? $this->input->post('template') : $treefield->template, 'class="form-control"');
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (config_db_item('enable_county_affiliate_roles') === TRUE && $this->session->userdata('type') == 'ADMIN'): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php _l('Affilate price') ?></label>
                                    <div class="col-lg-9">
                                        <?php echo form_input('affilate_price', set_value('affilate_price', $treefield->affilate_price), 'class="form-control" id="inputAffilatePrice" placeholder="' . lang_check('Affilate price') . '"') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($treefield->id)): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang_check('Order') ?></label>
                                    <div class="col-lg-9">
                                        <?php echo form_input('order', set_value('order', $treefield->order), 'class="form-control" id="inputAffilatePrice" placeholder="' . lang_check('Order') . '"') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (config_item('tree_font_icon_code_list')!==FALSE && !empty(config_item('tree_font_icon_code_list'))): ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang_check('Font icon code') ?></label>
                                    <div class="col-lg-9">
                                        <?php
                                          $icons_code_list =  config_item('tree_font_icon_code_list');
                                          $icons_code_list = explode(',', $icons_code_list);
                                          $icons_code_list = array_map('trim', $icons_code_list);
                                          /*$icons_code_list = array_combine($icons_code_list,$icons_code_list);
                                          $icons_code_list = array_merge(array(''=>lang_check('Select icon')), $icons_code_list);*/
                                        ?>
                                        <select class="form-control selectpicker" name="font_icon_code" id="input_font_icon_code">
                                            <option value=""><?php echo lang_check('Select icon');?></option>
                                        <?php foreach ($icons_code_list as $key => $value):?>
                                            <?php
                                            $val = $this->input->post('font_icon_code') ? $this->input->post('font_icon_code') : $treefield->font_icon_code;
                                            ?>
                                            <option value="<?php echo $value;?>" data-icon="<?php echo $value;?>" <?php echo ($val==$value) ? 'selected="selected"': '';?>><?php echo $value;?></option>
                                        <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($treefield->id)): ?>

                                <div class="form-group UPLOAD-FIELD-TYPE">
                                    <label class="col-lg-3 control-label">
                                        <?php echo lang_check('Images'); ?>
                                        <div class="tooltip_tree">
                                            <span class="hintlabel"><i class="icon-question-sign hint" aria-hidden="true"></i></span>
                                            <span class="tooltiptext">
                                                <?php echo lang_check("1st image - preview image");?><div class="br"></div>
                                                <?php echo lang_check("2st image - icon image");?>
                                            </span>
                                        </div>
                                        <div class="ajax_loading"> </div>
                                    </label>
                                    <div class="col-lg-9">
                                        <div class="field-row hidden">
                                            <?php 
                                            $field_name = 'repository_id';
                                            echo form_input($field_name, $treefield->repository_id, 'class="form-control skip-input" id="' . $field_name . '" placeholder=""')
                                            ?>
                                        </div>
                                        <?php //if(empty($estate->id) || !isset($estate->{'option'.$field_name})): ?>
                                        <?php if (empty($treefield->repository_id)): ?>
                                            <span class="label label-danger"><?php echo lang('After saving, you can add files and images'); ?></span>
                                        <?php else: ?>
                                            <!-- Button to select & upload files -->
                                            <span class="btn btn-success fileinput-button">
                                                <span>Select files...</span>
                                                <!-- The file input field used as target for the file upload widget -->
                                                <input id="fileupload_<?php echo $field_name; ?>" class="FILE_UPLOAD file_<?php echo $field_name; ?>" type="file" name="files[]" multiple>
                                            </span><br style="clear: both;" />
                                            <!-- The global progress bar -->
                                            <p>Upload progress</p>
                                            <div id="progress_<?php echo $field_name; ?>" class="progress progress-success progress-striped">
                                                <div class="bar"></div>
                                            </div>
                                            <!-- The list of files uploaded -->
                                            <p>Files uploaded:</p>
                                            <ul id="files_<?php echo $field_name; ?>">
                                                <?php
                                                if (isset($treefield->repository_id)) {
                                                    $rep_id = $treefield->repository_id;

                                                    //Fetch repository
                                                    $file_rep = $this->file_m->get_by(array('repository_id' => $rep_id));
                                                    if (sw_count($file_rep))
                                                        foreach ($file_rep as $file_r) {
                                                            $delete_url = site_url_q('files/upload/rep_' . $file_r->repository_id, '_method=DELETE&amp;file=' . rawurlencode($file_r->filename));

                                                            echo "<li><a target=\"_blank\" href=\"" . base_url('files/' . $file_r->filename) . "\">$file_r->filename</a>" .
                                                            '&nbsp;&nbsp;<button class="btn btn-xs btn-danger" data-type="POST" data-url=' . $delete_url . '><i class="icon-trash icon-white"></i></button></li>';
                                                        }
                                                }
                                                ?>
                                            </ul>

                                            <!-- JavaScript used to call the fileupload widget to upload files -->
                                            <script language="javascript">
                                            // When the server is ready...
                                                $(document).ready(function () {
                
                                                    // Define the url to send the image data to
                                                    var url = '<?php echo site_url('files/upload_treefield/' . $treefield->id); ?>';
                
                                                    // Call the fileupload widget and set some parameters
                                                    $('#fileupload_<?php echo $field_name; ?>').fileupload({
                                                        url: url,
                                                        autoUpload: true,
                                                        dropZone: $('#fileupload_<?php echo $field_name; ?>'),
                                                        dataType: 'json',
                                                        done: function (e, data) {
                                                            // Add each uploaded file name to the #files list
                                                            $.each(data.result.files, function (index, file) {
                                                                if (!file.hasOwnProperty("error"))
                                                                {
                                                                    $('#files_<?php echo $field_name; ?>').append('<li><a href="' + file.url + '" target="_blank">' + file.name + '</a>&nbsp;&nbsp;<button    onkeypress="return searchKeyPress(event);" class="btn btn-xs btn-danger" data-type="POST" data-url=' + file.delete_url + '><i class="icon-trash icon-white"></i></button></li>');
                                                                    added = true;
                                                                } else
                                                                {
                                                                    ShowStatus.show(file.error);
                                                                }
                            
                                                            });
                        
                                                            //console.log(data.result.repository_id);
                                                            $('<?php echo '#inputOption_' . $field_name; ?>').attr('value', data.result.repository_id);
                        
                                                            reset_events_<?php echo $field_name; ?>();
                                                        },
                                                        progressall: function (e, data) {
                                                            // Update the progress bar while files are being uploaded
                                                            var progress = parseInt(data.loaded / data.total * 100, 10);
                                                            $('#progress_<?php echo $field_name; ?> .bar').css(
                                                                    'width',
                                                                    progress + '%'
                                                                    );
                                                        }
                                                    });
                
                                                    reset_events_<?php echo $field_name; ?>();
                                                    
                                                });
            
                                                function reset_events_<?php echo $field_name; ?>() {
                                                    $("#files_<?php echo $field_name; ?> li button").unbind();
                                                    
                                                   /* $("button.btn-danger").submit(function(e){
                                                        e.preventDefault();
                                                   })*/
                                                    
                                                    $("#files_<?php echo $field_name; ?> li button.btn-danger").click(function () {
                                                        var image_el = $(this);
                    
                                                        $.post($(this).attr('data-url'), function (data) {
                                                            var obj = jQuery.parseJSON(data);
                        
                                                            if (obj.success)
                                                            {
                                                                image_el.parent().remove();
                                                            } else
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
                        </div>


                    <?php endif; ?>
                    <hr />
                    <h5><?php echo lang('Translation data') ?></h5>
                    <div style="margin-bottom: 18px;" class="tabbable">
                        <ul class="nav nav-tabs">
                            <?php $i = 0;
                            foreach ($this->option_m->languages as $key => $val):$i++; ?>
                                <li class="<?php echo $i == 1 ? 'active' : '' ?>"><a data-toggle="tab" href="#<?php echo $key ?>"><?php echo $val ?></a></li>
<?php endforeach; ?>
                        </ul>
                        <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
<?php $i = 0;
foreach ($this->option_m->languages as $key => $val):$i++; ?>
                                <div id="<?php echo $key ?>" class="tab-pane <?php echo $i == 1 ? 'active' : '' ?>">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?php echo lang_check('Value') ?></label>
                                        <div class="col-lg-9">
                                            <?php echo form_input('value_' . $key, set_value('value_' . $key, $treefield->{"value_$key"}), 'class="form-control" id="inputValue_' . $key . '" placeholder="' . lang_check('Value') . '"') ?>
                                            <?php if (empty($option->id)): ?>
                                                <p class="help-block"><?php echo lang_check('You can also add multiple values (without spaces) "test1,test2" when adding.'); ?></p>
    <?php endif; ?>
                                        </div>
                                    </div>

    <?php if (!empty($treefield->id)): ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?php echo lang_check('Address') ?></label>
                                            <div class="col-lg-9">
        <?php echo form_input('address_' . $key, set_value('address_' . $key, $treefield->{'address_' . $key}), 'class="form-control" id="inputOption_' . $key . '_0" placeholder="' . lang_check('Address') . '"') ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?php echo lang_check('Page Title') ?></label>
                                            <div class="col-lg-9">
        <?php echo form_input('title_' . $key, set_value('title_' . $key, $treefield->{'title_' . $key}), 'class="form-control copy_to_next" id="inputOption_' . $key . '_1" placeholder="' . lang_check('Page Title') . '"') ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?php echo lang_check('Custom path title') ?></label>
                                            <div class="col-lg-9">
        <?php echo form_input('path_title_' . $key, set_value('path_title_' . $key, $treefield->{'path_title_' . $key}), 'class="form-control" id="inputOption_' . $key . '_2" placeholder="' . lang_check('Custom path title') . '"') ?>
                                            </div>
                                        </div>

        <?php if (config_db_item('slug_enabled') === TRUE): ?>
                                            <div class="form-group">
                                                <label class="col-lg-3 control-label"><?php echo lang_check('URI slug') ?></label>
                                                <div class="col-lg-9">
                                            <?php echo form_input('slug_' . $key, set_value('slug_' . $key, $treefield->{'slug_' . $key}), 'class="form-control" id="inputOption_' . $key . '_slug" placeholder="' . lang_check('URI slug') . '"') ?>
                                                </div>
                                            </div>
        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?php echo lang('Keywords') ?></label>
                                            <div class="col-lg-9">
        <?php echo form_input('keywords_' . $key, set_value('keywords_' . $key, $treefield->{'keywords_' . $key}), 'class="form-control" id="inputOption_' . $key . '_3" placeholder="' . lang('Keywords') . '"') ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?php echo lang('Description') ?></label>
                                            <div class="col-lg-9">
        <?php echo form_textarea('description_' . $key, set_value('description_' . $key, $treefield->{'description_' . $key}), 'placeholder="' . lang('Description') . '" rows=4" class="form-control" id="inputOption_' . $key . '_4"') ?>
                                            </div>
                                        </div>  

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?php echo lang('Body') ?></label>
                                            <div class="col-lg-9">
        <?php echo form_textarea('body_' . $key, set_value('body_' . $key, $treefield->{'body_' . $key}), 'placeholder="' . lang('Body') . '" rows="10" class="ckeditor form-control" id="inputOption_' . $key . '_5"') ?>
                                            </div>
                                        </div> 

        <?php for ($i = 1; $i <= 6; $i++): ?>
                                            <div class="form-group">
                                                <label class="col-lg-3 control-label"><?php echo lang_check('Ads code') . ' ' . $i ?></label>
                                                <div class="col-lg-9">
                                            <?php echo form_textarea('adcode' . $i . '_' . $key, set_value('adcode' . $i . '_' . $key, $treefield->{'adcode' . $i . '_' . $key}), 'placeholder="' . lang_check('Ads code') . ' ' . $i . '" rows=4" class="form-control" id="inputOption_' . $key . '_' . ($i + 5) . '"') ?>
                                                </div>
                                            </div>  
        <?php endfor; ?>



                                <?php endif; ?> 
                                </div>
<?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"') ?>
                        </div>
                    </div>
<?php echo form_close() ?>
                </div>
            </div>
            <div class="widget-foot">
                <!-- Footer goes here -->
            </div>
        </div>  

    </div>

</div>

<div class="row">

    <div class="col-md-12">

        <div class="widget wblue">

            <div class="widget-head">
                <div class="pull-left"><?php echo lang_check('Tree values') ?></div>
                <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="widget-content">
                <table class="table table-bordered footable table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo lang_check('Root'); ?></th>
                            <th><?php echo lang_check('Parent #'); ?></th>
                            <th><?php echo lang_check('Level'); ?></th>
                            <th class="control"><?php echo lang('Edit'); ?></th>
<?php if (check_acl('savesearch/delete')): ?><th class="control"><?php echo lang('Delete'); ?></th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
<?php if (sw_count($tree_listings)): foreach ($tree_listings as $listing_item): ?>
                                <tr>
                                    <td><?php echo $listing_item->id; ?></td>
                                    <td><?php echo $listing_item->visual . $listing_item->value; ?></td>
                                    <td><?php echo $listing_item->parent_id; ?></td>
                                    <td><?php echo $listing_item->level; ?></td>
                                    <td><?php echo btn_edit('admin/treefield/edit/' . $option->id . '/' . $listing_item->id) ?></td>
                                <?php if (check_acl('treefield/delete')): ?><td><?php echo btn_delete('admin/treefield/delete/' . $option->id . '/' . $listing_item->id) ?></td><?php endif; ?>
                                </tr>
    <?php endforeach; ?>
<?php else: ?>
                            <tr>
                                <td colspan="20"><?php echo lang('We could not find any'); ?></td>
                            </tr>
<?php endif; ?>           
                    </tbody>
                </table>

                <div style="text-align: center;"><?php //echo $pagination;  ?></div>

            </div>
        </div>
    </div>
</div>

</div>
</div>
<?php if(config_item('tree_font_icon_code_list_css_array')!==FALSE && !empty(config_item('tree_font_icon_code_list_css_array'))):?>
    <?php foreach (config_item('tree_font_icon_code_list_css_array') as $key => $value):?>
        <link rel="stylesheet" href="<?php echo $value;?>">
    <?php endforeach;?>
<?php endif;?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
<script>
    
    /* CL Editor */
    $(document).ready(function () {
        $('input').keypress(function(e) {
            var code = e.keyCode || e.which;
            if(code == 13) { //Enter keycode
                e.preventDefault();
                $(this).closest('form').find('input[type="submit"]').click();
            }
        });
    
        $('.selectpicker').selectpicker();
    });
    
</script>
<style>
    .tooltip_tree {
        position: relative;
        display: inline-block;
    }

    .tooltip_tree .tooltiptext {
        visibility: hidden;
        width: 185px;
        background-color: #0f163c;
        color: #fff;
        text-align: left;
        border-radius: 6px !important;
        padding: 8px 10px;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        margin-bottom: 5px;
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
    }
    
    .tooltip_tree .hintlabel i {
        color: #0f163c;
        font-size: 15px;
    }

    .tooltip_tree:hover .tooltiptext {
        visibility: visible;
    }

    .tooltip_tree:hover .tooltiptext .br {
        margin-bottom: 5px;
    }
    
    .tooltip_tree .hintlabel {
        color: white;
        display: inline-block;
        border-radius: 50% !important;
        font-size: 11px;
        width: 15px;
        height: 15px;
        text-align: center;
        margin-left: 5px;
        cursor: pointer;
    }

</style>