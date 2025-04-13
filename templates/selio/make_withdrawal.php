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
                            <?php echo $page_title; ?>
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
                                  <label class="col-lg-2 control-label"><?php _l('Amount')?></label>
                                  <div class="col-lg-10 controls">
                                  <div class="input-append">
                                    <?php echo form_input('amount', $this->input->post('amount') ? $this->input->post('amount') : '', 'class="form-control"'); ?>
                                  </div>
                                  </div>
                                </div>

                                <div class="form-group form-group row">
                                  <label class="col-lg-2 control-label"><?php _l('Currency code')?></label>
                                  <div class="col-lg-10 controls">
                                    <?php echo form_dropdown('currency', $currencies, $this->input->post('currency') ? $this->input->post('currency') : '', 'class="form-control"')?>
                                  </div>
                                </div>

                                <div class="form-group form-group row">
                                  <label class="col-lg-2 control-label"><?php _l('Withdrawal email')?></label>
                                  <div class="col-lg-10 controls">
                                  <div class="input-append">
                                    <?php echo form_input('withdrawal_email', $this->input->post('withdrawal_email') ? $this->input->post('withdrawal_email') : '', 'class="form-control"'); ?>
                                  </div>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                  <div class="controls">
                                    <?php echo form_submit('submit', lang_check('Request withdrawal'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('rates/payments/'.$lang_code)?>#content" class="btn btn-default" type="button"><?php echo lang_check('Cancel')?></a>
                                  </div>
                                </div>
                        <?php echo form_close() ?>
                    </div>
                </div> <!-- ./ widget-submit --> 
                
                </div>
           
                
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