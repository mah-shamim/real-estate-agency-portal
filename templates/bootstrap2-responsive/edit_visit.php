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
            <h2><?php _l('Edit visit'); ?> #<?php echo $listing->id; ?></h2>
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
                                  <label class="control-label"><?php echo lang_check('Estate')?></label>
                                  <div class="controls">
                                    <?php echo form_dropdown('property_id',$estates_list, set_value('property_id', $listing->property_id), 'class="form-control" readonly disabled'); ?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php _l('Client')?></label>
                                  <div class="controls">
                                       <?php echo form_dropdown('client_id',$users_list, set_value('client_id', $listing->client_id), 'class="form-control" readonly disabled'); ?>
                                  </div>
                                </div>
                                <?php if(!empty($listing->date_visit)):?>
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Date of visit')?></label>
                                  <div class="controls">
                                    <?php echo form_input('date_visit', set_value('date_visit', $listing->date_visit), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(!empty($listing->date_created)):?>
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Date of created')?></label>
                                  <div class="controls">
                                    <?php echo form_input('date_created', set_value('date_created', $listing->date_created), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(!empty($listing->date_confirmed)):?>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Date of confirmed')?></label>
                                  <div class="controls">
                                    <?php echo form_input('date_confirmed', set_value('date_confirmed', $listing->date_confirmed), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(!empty($listing->date_canceled)):?>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Date of canceled')?></label>
                                  <div class="controls">
                                    <?php echo form_input('date_canceled', set_value('date_canceled', $listing->date_canceled), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Message')?></label>
                                  <div class="controls">
                                    <?php echo form_textarea('message', set_value('message', $listing->message), 'placeholder="'.lang_check('Message').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>    

                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Confirmed')?></label>
                                  <div class="controls">
                                    <?php echo form_checkbox('confirmed', '1', set_value('confirmed', $listing->confirmed), 'id="inputConfirmed"')?>
                                  </div>
                                </div>

                                <div class="control-group">
                                  <div class="controls">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
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