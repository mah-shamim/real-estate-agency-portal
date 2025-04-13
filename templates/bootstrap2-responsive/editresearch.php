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
            <h2>{lang_Editresearch}, #<?php echo $listing['id']; ?></h2>
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
                                  <label class="control-label"><?php echo lang_check('Activated')?></label>
                                  <div class="controls">
                                    <?php echo form_checkbox('activated', '1', set_value('activated', $listing['activated']), 'id="inputActivated"')?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Parameters')?></label>
                                  <div class="controls">
                                        <?php echo lang_check('Lang code').': '; ?><?php echo '['.strtoupper($listing['lang_code']).']'; ?><br />
                                        <?php
                                        
                                        $parameters = json_decode($listing['parameters']);
                                        
                                        foreach($parameters as $key=>$value){
                                            if(!empty($value) && $key != 'view' && $key != 'order')
                                            {
                                                if(is_array($value))
                                                {
                                                    $value = implode(', ', $value);
                                                }
                                                
                                                echo $key.': <b>'.$value.'</b><br />';
                                            }
                                        }
                    
                                        ?>
                                  </div>
                                </div>
                                                        
                              <div class="control-group">
                                <label class="control-label" for="inputPassword"><?php _l('Frequency of the delivery'); ?></label>
                                <div class="controls">
                                  <?php 
                                  
                                $options = array(
                                                  '0'   => lang_check('Instant'),
                                                  '24'  => lang_check('Daily'),
                                                  '168' => lang_check('Weekly')
                                                );
                                                
                                echo form_dropdown('delivery_frequency_h', $options, set_value('delivery_frequency_h', $listing['delivery_frequency_h']));
                                  
                                  ?>
                                </div>
                              </div>

                                <div class="control-group">
                                  <div class="controls">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('fresearch/myresearch/'.$lang_code)?>#content" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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

<?php _widget('custom_javascript');?> 

  </body>
</html>