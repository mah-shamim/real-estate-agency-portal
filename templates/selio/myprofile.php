<!doctype html>
<html class="no-js" lang="en">
    <head>
        <?php _widget('head'); ?>
    </head>
    <body>
        <div >
            <header>
                <?php _widget('header_bar'); ?>
                <?php _widget('header_main_panel'); ?>
            </header><!--header end-->
             <?php _widget('top_title'); ?>
            <div class="container m-padding" id='main'>
                  <div class="widget-panel border  widget-submit">
                    <div class="widget-header header-styles">
                        <h2 class="title"><?php echo lang_check('Profile'); ?></h2>
                    </div> <!-- ./ title --> 
                    <div class="validation m25">
                        <?php echo validation_errors()?>
                        <?php if($this->session->flashdata('message')):?>
                        <?php echo $this->session->flashdata('message')?>
                        <?php endif;?>
                        <?php if($this->session->flashdata('error')):?>
                        <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                        <?php endif;?>
                    </div>
                     <?php echo form_open(NULL, array('class' => 'form-estate', 'role'=>'form'))?>                              
                        <div class="form-group">
                          <label for="inputNameSurname" class="control-label"><?php echo lang('FirstLast')?></label>
                          <div class="controls">
                            <?php echo form_input('name_surname', set_value('name_surname', $user_data['name_surname']), 'class="form-control" id="inputNameSurname" placeholder="'.lang('FirstLast').'"')?>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputUsername" class="control-label"><?php echo lang('Username')?></label>
                          <div class="controls">
                            <?php echo form_input('username', set_value('username', $user_data['username']), 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputPassword" class="control-label"><?php echo lang('Password')?></label>
                          <div class="controls">
                            <?php echo form_password('password', set_value('password', ''), 'class="form-control" id="inputPassword" autocomplete="new-password" '.$password_attr.' placeholder="'.lang('Password').'"')?>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputPasswordConfirm" class="control-label"><?php echo lang('PasswordConfirm')?></label>
                          <div class="controls">
                            <?php echo form_password('password_confirm', set_value('password_confirm', ''), 'class="form-control" id="inputPasswordConfirm" autocomplete="new-password" '.$password_attr.' placeholder="'.lang('PasswordConfirm').'"')?>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputAddress" class="control-label"><?php echo lang('Address')?></label>
                          <div class="controls">
                            <?php echo form_textarea('address', set_value('address', $user_data['address']), 'placeholder="'.lang('Address').'" id="inputAddress" rows="3" class="form-control"')?>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputDescription" class="control-label"><?php echo lang('Description')?></label>
                          <div class="controls">
                            <?php echo form_textarea('description', set_value('description', $user_data['description']), 'placeholder="'.lang('Description').'" rows="3" id="inputDescription" class="form-control"')?>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputPhone" class="control-label"><?php echo lang('Phone')?></label>
                          <div class="controls">
                            <?php echo form_input('phone', set_value('phone', $user_data['phone']), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail" class="control-label"><?php echo lang('Email')?></label>
                          <div class="controls">
                            <?php echo form_input('mail', set_value('mail', $user_data['mail']), 'class="form-control" id="inputEmail" placeholder="'.lang('Email').'"')?>
                          </div>
                        </div>

                        <!-- [Custom fields] -->     
                        <div class="b2_ini">
                        <?php
                        custom_fields_print_f('custom_fields_code');
                        ?>  
                        </div>
                        <!-- [/Custom fields] -->
                        <hr>
                        <div class="form-group">
                          <div class="controls">
                            <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                          </div>
                        </div>
                    <?php echo form_close()?>
                </div> <!-- ./ widget-submit --> 
                
                <div class="widget-panel border widget-submit" id='main'>
                    <div class="widget-header header-styles">
                        <h2 class="title"><?php echo lang_check('Files (avatar,docs)'); ?></h2>
                    </div> <!-- ./ title --> 
                        <div class="content-box">
                            <?php if(!isset($user_data['id'])):?>
                            <span class="label label-danger"><?php echo lang_check('After saving, you can add files and images');?></span>
                            <?php else:?>
                            <div id="page-files-<?php echo $user_data['id']?>" rel="user_m">
                                <!-- The file upload form used as target for the file upload widget -->
                                <form class="fileupload" action="<?php echo site_url('files/upload_user/'.$user_data['id']);?>" method="POST" enctype="multipart/form-data">
                                    <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                    <noscript><input type="hidden" name="redirect" value="<?php echo site_url('frontend/myprofile/'.$lang_code);?>"></noscript>
                                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                    <div class="fileupload-buttonbar row">
                                            <div class="col-md-6">
                                                <!-- The fileinput-button span is used to style the file input field as button -->
                                                <span class="btn btn-success fileinput-button">
                                                    <i class="fa fa-plus"></i>
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
                                            <div class="col-md-6 fileupload-progress fade">
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
                                                        <?php if(isset($files[$user_data['repository_id']]))foreach($files[$user_data['repository_id']] as $file ):?>
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
                </div> <!-- ./ widget-submit --> 
               
                
            </div>
        <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
        </div><!--wrapper end-->
        <?php cache_file('fileupload_css.css', NULL, true); ?>
        <?php cache_file('fileupload_js.js', NULL); ?>
        <?php _widget('custom_javascript'); ?>
        <?php
        /* dinamic per listing */
        _generate_js('_generate_login_page_js_' . md5(current_url_q()), 'widgets/_generate_login_page_js.php', false, 0);
        ?>
    </body>

</html>