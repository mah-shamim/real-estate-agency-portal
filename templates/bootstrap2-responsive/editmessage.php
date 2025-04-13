<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <script>
    $(document).ready(function(){

    });    
    </script>
  </head>

  <body>

{template_header}

<a id="content"></a>
<div class="wrap-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
            <h2><?php _l('Edit message'); ?> #<?php echo $enquire->id; ?></h2>
            <div class="property_content">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal form-estate', 'role'=>'form'))?>                              
                                
                                <div class="control-group">
                                  <label class="control-label"><?php _l('Estate'); ?></label>
                                  <div class="controls">
                                    <?php echo form_dropdown('property_id', $all_estates, set_value('property_id', $enquire->property_id), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php _l('Name and surname')?></label>
                                  <div class="controls">
                                    <?php echo form_input('name_surname', set_value('name_surname', $enquire->name_surname), 'class="form-control" id="inputNameSurname" placeholder="'.lang_check('Name and surname').'"')?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php _l('Phone')?></label>
                                  <div class="controls">
                                    <?php echo form_input('phone', set_value('phone', $enquire->phone), 'class="form-control" id="inputPhone" placeholder="'.lang_check('Phone').'"')?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php _l('Mail')?></label>
                                  <div class="controls">
                                    <?php echo form_input('mail', set_value('mail', $enquire->mail), 'class="form-control" id="inputMail" placeholder="'.lang_check('Mail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php _l('FromDate')?></label>
                                  <div class="controls">
                                    <?php echo form_input('fromdate', set_value('fromdate', $enquire->fromdate), 'class="form-control datetimepicker_standard" data-format="yyyy-MM-dd"')?>
                                  </div>
                                </div>

                                <div class="control-group">
                                  <label class="control-label"><?php _l('ToDate')?></label>
                                  <div class="controls">
                                    <?php echo form_input('todate', set_value('todate', $enquire->todate), 'class="form-control datetimepicker_standard" data-format="yyyy-MM-dd"')?>
                                  </div>
                                </div>

                                <div class="control-group">
                                  <label class="control-label"><?php _l('Message')?></label>
                                  <div class="controls">
                                    <?php echo form_textarea('message', set_value('message', $enquire->message), 'placeholder="'.lang_check('Message').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>

                                <div class="control-group">
                                  <label class="control-label"><?php _l('Address')?></label>
                                  <div class="controls">
                                    <?php echo form_textarea('address', set_value('address', $enquire->address), 'placeholder="'.lang_check('Address').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>

                                <div class="control-group">
                                  <label class="control-label"><?php _l('Readed')?></label>
                                  <div class="controls">
                                    <?php echo form_checkbox('readed', '1', set_value('readed', $enquire->readed), 'id="inputReaded"')?>
                                  </div>
                                </div>

                                <div class="control-group">
                                  <div class="controls">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <?php if(isset($enquire->mail)):?>
                                    <a href="mailto:<?php echo $enquire->mail?>?subject=<?php echo lang_check('Reply on question for real estate')?>: <?php echo $all_estates[$enquire->property_id]?>&amp;body=<?php echo $enquire->message?>" class="btn btn-default"><?php echo lang_check('Reply to email')?></a>
                                    <?php endif;?>
                                  </div>
                                </div>


                       <?php echo form_close()?>
            </div>
            </div>
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

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->

<?php _widget('custom_javascript');?> 
  </body>
</html>