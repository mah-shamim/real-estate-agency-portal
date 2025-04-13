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
            <h2><?php echo $page_title; ?></h2>
            <div class="property_content">
                <div class="widget-controls"> 
                    <span>
                    <?php _l('You can withdraw up to:'); ?>
                    <?php
                        $index=0;
                        $currencies = array(''=>'');
                        
                        if(sw_count($withdrawal_amounts) == 0)echo '0';
                        
                        foreach($withdrawal_amounts as $currency=>$amount)
                        {
                            $currencies[$currency] = $currency;
                            echo '<span class="label label-success">'.$amount.' '.$currency.'</span>&nbsp;';
                        }
                    ?>
                    </span>
                </div>
            
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal form-estate', 'role'=>'form'))?>                              
                        <div class="form-group control-group">
                          <label class="col-lg-2 control-label"><?php _l('Amount')?></label>
                          <div class="col-lg-10 controls">
                          <div class="input-append">
                            <?php echo form_input('amount', $this->input->post('amount') ? $this->input->post('amount') : '', ''); ?>
                          </div>
                          </div>
                        </div>
                        
                        <div class="form-group control-group">
                          <label class="col-lg-2 control-label"><?php _l('Currency code')?></label>
                          <div class="col-lg-10 controls">
                            <?php echo form_dropdown('currency', $currencies, $this->input->post('currency') ? $this->input->post('currency') : '', 'class="form-control"')?>
                          </div>
                        </div>

                        <div class="form-group control-group">
                          <label class="col-lg-2 control-label"><?php _l('Withdrawal email')?></label>
                          <div class="col-lg-10 controls">
                          <div class="input-append">
                            <?php echo form_input('withdrawal_email', $this->input->post('withdrawal_email') ? $this->input->post('withdrawal_email') : '', ''); ?>
                          </div>
                          </div>
                        </div>

                        <div class="control-group">
                          <div class="controls">
                            <?php echo form_submit('submit', lang_check('Request withdrawal'), 'class="btn btn-primary"')?>
                            <a href="<?php echo site_url('rates/payments/'.$lang_code)?>#content" class="btn btn-default" type="button"><?php echo lang_check('Cancel')?></a>
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