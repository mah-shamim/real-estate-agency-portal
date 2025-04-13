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
            <?php if(!empty($rate->id)):?>
            <h2><?php echo lang_check('Edit rate'); ?>, #<?php echo $rate->id; ?></h2>
            <?php else: ?>
            <h2><?php echo lang_check('Add rate'); ?></h2>
            <?php endif; ?>
            <div class="property_content">
                <div class="widget-controls"> 
                    <?php echo anchor('rates/index/'.$lang_code.'#content', '<i class="icon-book"></i>&nbsp;&nbsp;'.lang_check('View rates'), 'class="btn pull-right"')?>
                    <br style="clear: both;" />
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Property')?></label>
                                  <div class="col-lg-10 controls">
                                    <?php echo form_dropdown('property_id', $properties, $this->input->post('property_id') ? $this->input->post('property_id') : $rate->property_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group control-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('From date')?></label>
                                  <div class="col-lg-10 controls">
                                  <div class="input-append">
                                    <?php echo form_input('date_from', $this->input->post('date_from') ? $this->input->post('date_from') : $rate->date_from, 'data-format="yyyy-MM-dd hh:mm:ss" id="booking_date_from"'); ?>
                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group control-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('To date')?></label>
                                  <div class="col-lg-10 controls">
                                  <div class="input-append">
                                    <?php echo form_input('date_to', $this->input->post('date_to') ? $this->input->post('date_to') : $rate->date_to, 'data-format="yyyy-MM-dd hh:mm:ss" id="booking_date_to"'); ?>

                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group control-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Min stay')?></label>
                                  <div class="col-lg-10 controls">
                                    <?php echo form_input('min_stay', set_value('min_stay', $rate->min_stay), 'class="form-control" id="inputMinStay" placeholder="'.lang_check('Min stay').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group control-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Changeover day')?></label>
                                  <div class="col-lg-10 controls">
                                    <?php echo form_dropdown('changeover_day', $changeover_days, set_value('changeover_day', $rate->changeover_day), 'class="form-control" id="inputChangeoverDay" placeholder="'.lang_check('Changeover day').'"')?>
                                  </div>
                                </div>

                               <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($this->rates_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?>"><a data-toggle="tab" href="#<?php echo $key_lang?>"><?php echo $val_lang?></a></li>
                                    <?php endforeach;?>
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->rates_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <div id="<?php echo $key_lang?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <div class="form-group control-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('Rate nightly')?></label>
                                          <div class="col-lg-10 controls">
                                            <?php echo form_input('rate_nightly_'.$key_lang, set_value('rate_nightly_'.$key_lang, $rate->{'rate_nightly_'.$key_lang}), 'class="form-control" id="inputRateNightly'.$key_lang.'" placeholder="'.lang_check('Rate nightly').'"')?>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group control-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('Rate weekly')?></label>
                                          <div class="col-lg-10 controls">
                                            <?php echo form_input('rate_weekly_'.$key_lang, set_value('rate_weekly_'.$key_lang, $rate->{'rate_weekly_'.$key_lang}), 'class="form-control" id="inputRateWeekly'.$key_lang.'" placeholder="'.lang_check('Rate weekly').'"')?>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group control-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('Rate monthly')?></label>
                                          <div class="col-lg-10 controls">
                                            <?php echo form_input('rate_monthly_'.$key_lang, set_value('rate_monthly_'.$key_lang, $rate->{'rate_monthly_'.$key_lang}), 'class="form-control" id="inputRateMonthly'.$key_lang.'" placeholder="'.lang_check('Rate monthly').'"')?>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group control-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('Currency code')?></label>
                                          <div class="col-lg-10 controls">
                                            <?php 
                                            // get all langauge data to fetch default paypal currency
                                            $lang_data = $this->language_m->get($key_lang);
                                            
//                                            echo form_dropdown('currency_code_'.$key_lang, $currencies, 
//                                                               set_value('currency_code_'.$key_lang, $lang_data->currency_default), 
//                                                               'class="form-control" id="inputCurrencyCode'.$key_lang.'" placeholder="'.lang_check('Currency code').'"');
                                            
                                            echo form_input('currency_code_'.$key_lang, set_value('currency_code_'.$key_lang, $lang_data->currency_default), 
                                                               'class="form-control" id="inputCurrencyCode'.$key_lang.'" placeholder="'.lang_check('Currency code').'" readonly');
                                            
                                            ?>
                                          </div>
                                        </div>

                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>
                                <br style="clear: both;" />
                                <div class="control-group">
                                  <div class="controls">
                                    <?php echo form_submit('submit', lang_check('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('rates/index/'.$lang_code)?>#content" class="btn btn-default" type="button"><?php echo lang_check('Cancel')?></a>
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