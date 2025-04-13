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
                        <h2 class="title"><?php echo lang_check('My rates and availability'); ?></h2>
                    </div> <!-- ./ title --> 
                    <div class="content-box">
                        <div class="validation m25"> 
                            <?php echo anchor('rates/rate_edit/'.$lang_code.'#content', '<i class="fa fa-plus"></i>&nbsp;&nbsp;'.lang_check('Add rate'), 'class="btn btn-info"')?>
                        </div>
                        <div class="validation m25">
                            <?php if($this->session->flashdata('message')):?>
                            <?php echo $this->session->flashdata('message')?>
                            <?php endif;?>
                            <?php if($this->session->flashdata('error')):?>
                            <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                            <?php endif;?>
                        </div>
                        <table class="table table-striped data_table">
                           <thead>
                                <tr>
                                    <th  data-priority="1" class="span5">#</th>
                                    <th  data-priority="1"><?php echo lang_check('Info');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo lang_check('Reservation id');?></td>
                                    <td>#<?php echo $reservation['id']; ?></td>
                                </tr>       
                                <tr>
                                    <td><?php echo lang_check('Dates range');?></td>
                                    <td><?php echo date('Y-m-d', strtotime($reservation['date_from'])).' - '.date('Y-m-d', strtotime($reservation['date_to'])); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang_check('Property');?></td>
                                    <td><?php echo isset($options[$reservation['property_id']][10])?'<a href="'.site_url('property/'.$reservation['property_id'].'/'.$lang_code).'">'.$options[$reservation['property_id']][10].', #'.$reservation['property_id'].'</a>':''?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang_check('Total price');?></td>
                                    <td><?php echo $reservation['total_price'].' '.$reservation['currency_code']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang_check('Total paid');?></td>
                                    <td><?php echo $reservation['total_paid'].' '.$reservation['currency_code']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo lang_check('Is booked');?></td>
                                    <td>
                                        <?php if($reservation['is_confirmed'] == 0):?>
                                        &nbsp;<span class="label label-important"><?php echo lang_check('Not confirmed')?></span>
                                        <?php else: ?>
                                        &nbsp;<span class="label label-success"><?php echo lang_check('Confirmed')?></span>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <?php if($reservation['total_paid'] == 0): ?>
                                <tr>
                                    <td><?php echo lang_check('Pay advance and reservation');?>, <?php echo number_format($reservation['total_price']*0.2, 2).' '.$reservation['currency_code']; ?></td>

                                    <?php if(config_db_item('payments_enabled') == 1 && !_empty(config_db_item('paypal_email'))): ?>
                                    <td><a href="<?php echo site_url('frontend/do_purchase/'.$this->data['lang_code'].'/'.$reservation['id'].'/'.number_format($reservation['total_price']*0.2, 2)); ?>"><img style="height:36px;" src="assets/img/pay-now-paypal.png" /></a></td>
                                    <?php endif; ?>
                                    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_item('stripe_enabled') !== FALSE): ?>
                                        <a href="<?php echo site_url('paymentconsole/strip_payment/'.$this->data['lang_code'].'/'.number_format($reservation['total_price']*0.2, 2).'/'.$reservation['currency_code'].'/'.$reservation['id'].'/RES'); ?>"><img style="height:36px; margin-right:10px;" src="assets/img/stripe-logo.png" alt="" /></a>
                                    <?php endif; ?>
                                </tr>
                                <?php endif; ?>
                                <?php if($reservation['total_paid'] < $reservation['total_price']): ?>
                                <tr>
                                    <td><?php echo lang_check('Pay total');?>, <?php echo number_format($reservation['total_price']-$reservation['total_paid'], 2).' '.$reservation['currency_code']; ?></td>
                                    <?php if(config_db_item('payments_enabled') == 1 && !_empty(config_db_item('paypal_email'))): ?>
                                    <td><a href="<?php echo site_url('frontend/do_purchase/'.$this->data['lang_code'].'/'.$reservation['id'].'/'.number_format($reservation['total_price']-$reservation['total_paid'], 2)); ?>"><img style="height:36px;" src="assets/img/pay-now-paypal.png" /></a></td>
                                    <?php endif; ?>
                                    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_item('stripe_enabled') !== FALSE): ?>
                                        <a href="<?php echo site_url('paymentconsole/strip_payment/'.$this->data['lang_code'].'/'.number_format($reservation['total_price']-$reservation['total_paid'], 2).'/'.$reservation['currency_code'].'/'.$reservation['id'].'/RES'); ?>"><img style="height:36px; margin-right:10px;" src="assets/img/stripe-logo.png" alt="" /></a>
                                    <?php endif; ?>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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