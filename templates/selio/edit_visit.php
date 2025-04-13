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
            <div class="container m-padding">
                                <div class="widget-panel widget-submit">
                    <div class="widget-header header-styles">
                        <h2 class="title"><?php _l('Edit message'); ?> #<?php echo $enquire->id; ?></h2>
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
                    <div class="widget-content">
                        <?php echo form_open(NULL, array('class' => 'form-estate', 'role'=>'form'))?>                    
                        <div class="form-group">
                            <label class="control-label"><?php echo lang_check('Estate') ?></label>
                            <div class="controls">
                                <?php echo form_dropdown('property_id', $estates_list, set_value('property_id', $listing->property_id), 'class="form-control" readonly disabled'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php _l('Client') ?></label>
                            <div class="controls">
                                <?php echo form_dropdown('client_id', $users_list, set_value('client_id', $listing->client_id), 'class="form-control" readonly disabled'); ?>
                            </div>
                        </div>
                        <?php if (!empty($listing->date_visit)): ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo lang_check('Date of visit') ?></label>
                                <div class="controls">
                                    <?php echo form_input('date_visit', set_value('date_visit', $listing->date_visit), 'class="form-control" id="inputNameSurname" readonly disabled') ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($listing->date_created)): ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo lang_check('Date of created') ?></label>
                                <div class="controls">
                                    <?php echo form_input('date_created', set_value('date_created', $listing->date_created), 'class="form-control" id="inputNameSurname" readonly disabled') ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($listing->date_confirmed)): ?>

                            <div class="form-group">
                                <label class="control-label"><?php echo lang_check('Date of confirmed') ?></label>
                                <div class="controls">
                                    <?php echo form_input('date_confirmed', set_value('date_confirmed', $listing->date_confirmed), 'class="form-control" id="inputNameSurname" readonly disabled') ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($listing->date_canceled)): ?>

                            <div class="form-group">
                                <label class="control-label"><?php echo lang_check('Date of canceled') ?></label>
                                <div class="controls">
                                    <?php echo form_input('date_canceled', set_value('date_canceled', $listing->date_canceled), 'class="form-control" id="inputNameSurname" readonly disabled') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="control-label"><?php echo lang_check('Message') ?></label>
                            <div class="controls">
                                <?php echo form_textarea('message', set_value('message', $listing->message), 'placeholder="' . lang_check('Message') . '" rows="3" class="form-control"') ?>
                            </div>
                        </div>    

                        <div class="form-group">
                            <label class="control-label"><?php echo lang_check('Confirmed') ?></label>
                            <div class="controls">
                                <?php echo form_checkbox('confirmed', '1', set_value('confirmed', $listing->confirmed), 'id="inputConfirmed"') ?>
                            </div>
                        </div>
                            <div class="form-group">
                                <div class="controls">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"') ?>
                                </div>
                            </div>
                        <?php echo form_close() ?>
                    </div>
                </div> <!-- ./ widget-submit --> 
                
                
            </div>
        <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
        <?php
        /* dinamic per listing */
        _generate_js('_generate_login_page_js_' . md5(current_url_q()), 'widgets/_generate_login_page_js.php', false, 0);
        ?>
    </body>

</html>