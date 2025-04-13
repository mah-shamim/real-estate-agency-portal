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
                
                                <div class="widget-panel border widget-submit">
                    <div class="widget-header header-styles">
                        <h2 class="title">
                            <?php if(!empty($rate->id)):?>
                                <?php echo lang_check('Edit rate'); ?>, #<?php echo $rate->id; ?>
                            <?php else: ?>
                                <?php echo lang_check('Add rate'); ?>
                            <?php endif; ?>
                            <?php echo anchor('rates/index/'.$lang_code.'#content', '<i class="icon-book"></i>&nbsp;&nbsp;'.lang_check('View rates'), 'class="btn pull-right"')?>
                        </h2>
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
                        <div class="form-group form-group row">
                            <label class="col-lg-2 control-label"><?php echo lang_check('Property')?></label>
                            <div class="col-lg-10 controls">
                              <?php echo form_dropdown('property_id', $properties, $this->input->post('property_id') ? $this->input->post('property_id') : $rate->property_id, 'class="form-control"')?>
                            </div>
                          </div>

                          <div class="form-group form-group row">
                            <label class="col-lg-2 control-label"><?php echo lang_check('From date')?></label>
                            <div class="col-lg-10 controls">
                            <div class="input-append">
                              <?php echo form_input('date_from', $this->input->post('date_from') ? $this->input->post('date_from') : $rate->date_from, 'class="form-control"  data-format="yyyy-MM-dd hh:mm:ss" id="booking_date_from"'); ?>
                            </div>
                            </div>
                          </div>

                          <div class="form-group form-group row">
                            <label class="col-lg-2 control-label"><?php echo lang_check('To date')?></label>
                            <div class="col-lg-10 controls">
                            <div class="input-append">
                              <?php echo form_input('date_to', $this->input->post('date_to') ? $this->input->post('date_to') : $rate->date_to, 'class="form-control"  data-format="yyyy-MM-dd hh:mm:ss" id="booking_date_to"'); ?>

                            </div>
                            </div>
                          </div>

                          <div class="form-group form-group row">
                            <label for="inputMinStay" class="col-lg-2 control-label"><?php echo lang_check('Min stay')?></label>
                            <div class="col-lg-10 controls">
                              <?php echo form_input('min_stay', set_value('min_stay', $rate->min_stay), 'class="form-control" id="inputMinStay" placeholder="'.lang_check('Min stay').'"')?>
                            </div>
                          </div>

                          <div class="form-group form-group row">
                            <label class="col-lg-2 control-label"><?php echo lang_check('Changeover day')?></label>
                            <div class="col-lg-10 controls">
                              <?php echo form_dropdown('changeover_day', $changeover_days, set_value('changeover_day', $rate->changeover_day), 'class="form-control" id="inputChangeoverDay" placeholder="'.lang_check('Changeover day').'"')?>
                            </div>
                          </div>

                         <div style="margin-bottom: 0px;" class="tabbable">
                              <ul class="nav nav-tabs">
                                  <?php $i=0;foreach($this->rates_m->languages as $key_lang=>$val_lang):$i++;?>
                                      <li class="nav-item <?php echo $i == 1 ? 'active' : '' ?>"><a class="nav-link <?php echo $i == 1 ? 'active' : '' ?>" data-toggle="tab" href="#lang_id_<?php echo $key_lang?>"><?php echo $val_lang?></a></li>
                                   <?php endforeach;?>
                              </ul>
                              <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                  <?php $i=0;foreach($this->rates_m->languages as $key_lang=>$val_lang):$i++;?>
                                  <div id="lang_id_<?php echo $key_lang?>" class="tab-pane fade <?php echo $i == 1 ? 'in active' : '' ?>">
                                      <div class="form-group form-group row">
                                        <label  class="col-lg-2 control-label"><?php echo lang_check('Rate nightly')?></label>
                                        <div class="col-lg-10 controls">
                                          <?php echo form_input('rate_nightly_'.$key_lang, set_value('rate_nightly_'.$key_lang, $rate->{'rate_nightly_'.$key_lang}), 'class="form-control" id="inputRateNightly'.$key_lang.'" placeholder="'.lang_check('Rate nightly').'"')?>
                                        </div>
                                      </div>
                                      <div class="form-group form-group row">
                                        <label class="col-lg-2 control-label"><?php echo lang_check('Rate weekly')?></label>
                                        <div class="col-lg-10 controls">
                                          <?php echo form_input('rate_weekly_'.$key_lang, set_value('rate_weekly_'.$key_lang, $rate->{'rate_weekly_'.$key_lang}), 'class="form-control" id="inputRateWeekly'.$key_lang.'" placeholder="'.lang_check('Rate weekly').'"')?>
                                        </div>
                                      </div>
                                      <div class="form-group form-group row">
                                        <label class="col-lg-2 control-label"><?php echo lang_check('Rate monthly')?></label>
                                        <div class="col-lg-10 controls">
                                          <?php echo form_input('rate_monthly_'.$key_lang, set_value('rate_monthly_'.$key_lang, $rate->{'rate_monthly_'.$key_lang}), 'class="form-control" id="inputRateMonthly'.$key_lang.'" placeholder="'.lang_check('Rate monthly').'"')?>
                                        </div>
                                      </div>
                                      <div class="form-group form-group row">
                                        <label class="col-lg-2 control-label"><?php echo lang_check('Currency code')?></label>
                                        <div class="col-lg-10 controls">
                                          <?php 
                                          // get all langauge data to fetch default paypal currency
                                          $lang_data = $this->language_m->get($key_lang);
                                          echo form_input('currency_code_'.$key_lang, set_value('currency_code_'.$key_lang, $lang_data->currency_default), 
                                                             'class="form-control" id="inputCurrencyCode'.$key_lang.'" placeholder="'.lang_check('Currency code').'" readonly');
                                          ?>
                                        </div>
                                      </div>
                                  </div>
                              <?php endforeach;?>
                            </div>
                          </div>
                          <hr style="clear: both;" />
                          <div class="form-group">
                            <div class="controls">
                              <?php echo form_submit('submit', lang_check('Save'), 'class="btn btn-primary"')?>
                              <a href="<?php echo site_url('rates/index/'.$lang_code)?>#content" class="btn btn-default" type="button"><?php echo lang_check('Cancel')?></a>
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