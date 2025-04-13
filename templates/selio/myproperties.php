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
            <?php if(file_exists(APPPATH.'controllers/admin/promocode.php')):?>
            <div class="widget-panel widget-promocode">
                <form action="" method="post">
                    <label><?php echo lang_check('Do you have a voucher ? Please insert your promo code here');?></label>
                    <?php if($this->session->flashdata('promocode_error')):?>
                        <p class="alert alert-error"><?php echo $this->session->flashdata('promocode_error')?></p>
                    <?php endif;?>
                    <?php if($this->session->flashdata('promocode_message')):?>
                        <p class="alert alert-success"><?php echo $this->session->flashdata('promocode_message')?></p>
                    <?php endif;?>
                    <input type="text" name="promocode"><input type="submit" value="<?php echo lang_check('Apply');?>" />
                </form>
                <?php 
                    $CI = &get_instance();
                    $CI->load->model('promocode_m');
                    $codes_activated = $CI->promocode_m->get_user_promocodes_activated($this->session->userdata('id'));
                    $codes_available = $CI->promocode_m->get_user_promocodes($this->session->userdata('id'));
                    
                    $discount_by_pac = $CI->promocode_m->get_user_discount_by_pac($this->session->userdata('id'));
                ?>
                <?php if($codes_activated || $codes_available):?>
                <div class="promocode_activated">
                    <h3><?php echo lang_check('Activated voucher');?></h3>
                    <ul class="promos">
                        <?php if($codes_activated)foreach($codes_activated as $promo):?>
                            <li><?php echo $promo->code_name;?> <span class="label label-important"><?php echo lang_check("activated");?></span></li>
                        <?php endforeach;?>
                        <?php if($codes_available)foreach($codes_available as $promo):?>
                            <li><?php echo $promo->code_name;?> <span class="label label-success"><?php echo lang_check("available");?></span></li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <?php endif;?>
            </div> <!-- /. widget --> 
            <?php endif;?>
            <div class="widget-panel">
                <div class="widget-header header-styles">
                    <h2 class="title"><?php echo lang_check('Myproperties'); ?></h2>
                </div>
                <div class="content-box">
                    <div class="content widget-controls"> 
                        <?php echo anchor('frontend/editproperty/'.$lang_code.'#content', '<i class="fa fa-plus"></i>&nbsp;&nbsp;'.lang_check('Addproperty'), 'class="btn btn-middle btn-info"')?>

                        <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
                            <?php echo anchor('rates/payments/'.$lang_code.'#content', '<i class="fa fa-book"></i>&nbsp;&nbsp;'.lang_check('Payments received'), 'class="btn btn-middle btn-primary pull-right"')?>
                            <?php echo anchor('rates/index/'.$lang_code.'#content', '<i class="fa fa-calendar"></i>&nbsp;&nbsp;'.lang_check('Edit availability and rates'), 'class="btn btn-middle pull-right" style="margin-right:5px;"')?>
                        <?php endif; ?>
                    </div>
                    <div class="box-alert">
                            <?php if($this->session->flashdata('error')):?>
                            <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                            <?php endif;?>
                            <?php if($this->session->flashdata('message')):?>
                            <?php echo $this->session->flashdata('message')?>
                            <?php endif;?>
                            <?php if(isset($_GET['subcription_id_created'])): ?>
                                <p class="alert alert-success"><?php echo lang_check('Thank you very much, subscription created and will be activated soon: '); ?><?php echo $_GET['subcription_id_created']; ?></p>
                            <?php endif; ?>
                        </div>
                        <table class="table table-striped data_table">
                            <thead>
                                <th data-priority="1">#</th>
                                <!--<th data-priority="1"><?php echo lang('Address');?></th>-->
                                <!-- Dynamic generated -->
                                <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                <th data-priority="5"><?php echo $row->option?></th>
                                <?php endforeach;?>
                                <!-- End dynamic generated -->
                                <th data-priority="2"></th>
                                <th data-priority="1" data-orderable="false"><?php echo lang('Edit');?></th>
                                <th data-priority="1" data-orderable="false"><?php echo lang('Delete');?></th>
                            </thead>
                            <tbody>
                                <?php if(sw_count($estates)): foreach($estates as $estate):?>
                                <tr>
                                    <td><?php echo $estate->id?></td>
                                    <!--<td>
                                    <?php echo anchor('frontend/editproperty/'.$lang_code.'/'.$estate->id, _ch($estate->address) )?>
                                    <?php if($estate->is_activated == 0):?>
                                    &nbsp;<span class="label label-important"><?php echo lang_check('Not activated')?></span>
                                    <?php endif;?>

                                    <?php if( isset($settings_listing_expiry_days) && $settings_listing_expiry_days > 0 && strtotime($estate->date_modified) <= time()-$settings_listing_expiry_days*86400): ?>
                                    &nbsp;<span class="label label-warning"><?php echo lang_check('Expired')?></span>
                                    <?php endif; ?>
                                    </td>-->
                                    <!-- Dynamic generated -->
                                    <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                    <td><?php echo isset($options[$estate->id][$row->option_id])?$options[$estate->id][$row->option_id]:''?></td>
                                    <?php endforeach;?>
                                    <!-- End dynamic generated -->
                                    <td>
                                    <?php if($estate->is_activated == 0 && $settings_activation_price > 0  && config_db_item('payments_enabled') == 1):?>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                                <?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Pay for activation'); ?>
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php if(!_empty(config_db_item('paypal_email'))): ?>
                                                <a class="dropdown-item" href="<?php echo site_url('frontend/do_purchase_activation/'.$lang_code.'/'.$estate->id.'/'.$settings_activation_price); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Pay for activation').' '.lang_check('with PayPal'); ?></a>
                                                <?php endif; ?>

                                                <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('authorize_api_login_id'))): ?>
                                                <a class="dropdown-item" href="<?php echo site_url('paymentconsole/authorize_payment/'.$lang_code.'/'.$settings_activation_price.'/'.$settings_default_currency.'/'.$estate->id.'/ACT'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Pay for activation').' '.lang_check('with CreditCard'); ?></a>
                                                <?php endif; ?>

                                                <?php if(!empty($settings_withdrawal_details) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
                                                <a class="dropdown-item" href="<?php echo site_url('paymentconsole/invoice_payment/'.$lang_code.'/'.$settings_activation_price.'/'.$settings_default_currency.'/'.$estate->id.'/ACT'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').' '.lang_check('with bank payment'); ?></a>
                                                <?php endif; ?>
                                                
                                                <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_item('stripe_enabled') !== FALSE): ?>
                                                    <li><a href="<?php echo site_url('paymentconsole/strip_payment/'.$lang_code.'/'.$settings_featured_price.'/'.$settings_default_currency.'/'.$estate->id.'/ACT'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Pay').' '.lang_check('via stripe.com'); ?></a></li>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>

                                    <?php if($estate->is_featured == 0 && $estate->is_activated == 1):?>
                                        <?php if($this->packages_m->get_available_featured() > 0): ?>
                                        <a class="btn btn-middle btn-primary" href="<?php echo site_url('fproperties/make_featured/'.$lang_code.'/'.$estate->id.'/'); ?>">
                                        <?php echo '<i class="fa fa-circle-arrow-up"></i> '.lang_check('Make featured'); ?>
                                        </a>
                                    <?php elseif($settings_featured_price > 0 && config_db_item('payments_enabled') == 1): ?>    
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                              <?php echo '<i class="fa fa-shopping-cart"></i> '.lang_check('Pay for featured'); ?>
                                            </button>
                                            <div class="dropdown-menu">
                                                <?php if(!_empty(config_db_item('paypal_email'))): ?>
                                                <li><a class="dropdown-item" href="<?php echo site_url('frontend/do_purchase_featured/'.$lang_code.'/'.$estate->id.'/'.$settings_featured_price); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Pay for featured').' '.lang_check('with PayPal'); ?></a></li>
                                                <?php endif; ?>

                                                <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('authorize_api_login_id'))): ?>
                                                <li><a class="dropdown-item" href="<?php echo site_url('paymentconsole/authorize_payment/'.$lang_code.'/'.$settings_featured_price.'/'.$settings_default_currency.'/'.$estate->id.'/FEA'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Pay for featured').' '.lang_check('with CreditCard'); ?></a></li>
                                                <?php endif; ?>

                                                <?php if(!empty($settings_withdrawal_details) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
                                                <li><a class="dropdown-item" href="<?php echo site_url('paymentconsole/invoice_payment/'.$lang_code.'/'.$settings_featured_price.'/'.$settings_default_currency.'/'.$estate->id.'/FEA'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').' '.lang_check('with bank payment'); ?></a></li>
                                                <?php endif; ?>
                                                                                                
                                                <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_item('stripe_enabled') !== FALSE): ?>
                                                <li><a href="<?php echo site_url('paymentconsole/strip_payment/'.$lang_code.'/'.$settings_featured_price.'/'.$settings_default_currency.'/'.$estate->id.'/FEA'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Pay for featured').' '.lang_check('via stripe.com'); ?></a></li>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <?php endif;?>
                                    </td>
                                    <td><?php echo anchor('frontend/editproperty/'.$lang_code.'/'.$estate->id, '<i class="fa fa-edit"></i> '.lang('Edit'), array('class'=>'btn btn-middle btn-info'))?></td>
                                    <td><?php echo anchor('frontend/deleteproperty/'.$lang_code.'/'.$estate->id, '<i class="fa fa-remove"></i> '.lang('Delete'), array('onclick' => 'return confirm(\''.lang_check('Are you sure?').'\')', 'class'=>'btn btn-middle btn-danger'))?></td>
                                </tr>
                                <?php endforeach;?>
                                <?php else:?>
                                <?php endif;?>      
                            </tbody>
                        </table>
                        <div class="box-body">
                        <?php if(isset($settings_activation_price) && isset($settings_featured_price) &&
                                $settings_activation_price > 0 || $settings_featured_price > 0): ?>
                            <p class="row-fluid clearfix">
                                <?php if($settings_activation_price > 0): ?>
                                    <?php echo lang_check('* Property activation price:').' '.$settings_activation_price.' '.$settings_default_currency; ?><br />
                                 <?php endif;?>
                                 <?php if($settings_featured_price > 0): ?>
                                    <?php echo lang_check('* Property featured price:').' '.$settings_featured_price.' '.$settings_default_currency; ?>
                                 <?php endif;?>
                            </p>
                        <?php endif;?>                               
                        </div>
                </div>
            </div> <!-- /. widget -->   
            <?php if(file_exists(APPPATH.'controllers/admin/packages.php') && !empty($packages)): ?>
            <div class="widget-panel">
                <div class="widget-header header-styles">
                    <h2 class="title"><?php echo lang_check('Mypackage'); ?></h2>
                </div>
                <div class="content-box">
                    <div class="box-alert">
                        <?php if($this->session->flashdata('error_package')):?>
                            <p class="alert alert-error"><?php echo $this->session->flashdata('error_package')?></p>
                        <?php endif;?>
                    </div>
                    <table class="table table-striped data_table">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="1"><?php echo lang_check('Package name');?></th>
                                <th data-priority="1"><?php echo lang_check('Price');?></th>
                                <th data-priority="5"><?php echo lang_check('Free property activation');?></th>
                                <th data-priority="5"><?php echo lang_check('Days limit');?></th>
                                <th data-priority="5"><?php echo lang_check('Listings limit');?></th>
                                <th data-priority="5"><?php echo lang_check('Free featured limit');?></th>
                                <th data-priority="1" data-orderable="false"><?php echo lang('Buy/Extend');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(sw_count($packages)): foreach($packages as $package):

                            if(!empty($user['package_id']) && 
                               $user['package_id'] != $package->id &&
                               strtotime($user['package_last_payment']) >= time() &&
                               $packages_days[$package->id] > 0 &&
                               $packages_price[$user['package_id']] > 0)
                            {
                                continue;
                            }
                            else if(!empty($package->user_type) && $package->user_type != 'USER' && $user['package_id'] != $package->id)
                            {
                                continue;
                            }
                            ?>
                            <tr>
                                <td><?php echo $package->id; ?></td>
                                <td>
                                <?php echo $package->package_name; ?>
                                <?php echo $package->show_private_listings==1?'&nbsp;<i class="fa fa-eye-open"></i>':'&nbsp;<i class="fa fa-eye-close"></i>'; ?>
                                <?php if($user['package_id'] == $package->id):?>
                                &nbsp;<span class="label label-success"><?php echo lang_check('Activated'); ?></span>
                                <?php else: ?>
                                &nbsp;<span class="label label-important"><?php echo lang_check('Not activated'); ?></span>
                                <?php endif;?>

                                <?php if($package->package_price > 0 && $user['package_id'] == $package->id && strtotime($user['package_last_payment']) < time() && $packages_days[$package->id] > 0): ?>
                                &nbsp;<span class="label label-warning"><?php echo lang_check('Expired'); ?></span>
                                <?php endif; ?>
                                </td>
                                <td>
                                <?php echo $package->package_price; ?>
                                <?php 
                                if(file_exists(APPPATH.'controllers/admin/promocode.php'))
                                    if(isset($discount_by_pac[$package->id]) && !empty($discount_by_pac[$package->id]) && $discount_by_pac[$package->id]<=$package->package_price) {
                                        echo '(-'.$discount_by_pac[$package->id].')';
                                    }
                                ?>
                                 <?php echo $package->currency_code; ?>
                                </td>
                                <td><?php echo $package->auto_activation?'<i class="fa fa-check"></i>':''; ?></td>
                                <td>
                                <?php 
                                    echo $package->package_days;
                                    if($user['package_id'] == $package->id && $package->package_price > 0 &&
                                       strtotime($user['package_last_payment']) >= time() && $packages_days[$package->id] > 0 )
                                    {
                                        echo ', '.$user['package_last_payment'];
                                    }
                                ?>
                                </td>
                                <td>
                                <?php echo $package->num_listing_limit?>
                                </td>
                                <td>
                                <?php echo $package->num_featured_limit?>
                                </td>
                                <td>
                                <?php if($package->package_price > 0  && config_db_item('payments_enabled') == 1): ?>                     
                                <div class="btn-group">
                                <a class="btn btn-middle btn-info dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend'); ?>
                                <span class="caret"></span>
                                </a>
                                <?php 
                                $discount = 0;
                                if(file_exists(APPPATH.'controllers/admin/promocode.php'))
                                    if(isset($discount_by_pac[$package->id]) && !empty($discount_by_pac[$package->id]) && $discount_by_pac[$package->id]<=$package->package_price) {
                                        $discount = $discount_by_pac[$package->id];
                                    }
                                
                                ?> 
                                    
                                    
                                <ul class="dropdown-menu">
                                    <?php if(!_empty(config_db_item('paypal_email'))): ?>
                                    <li><a href="<?php echo site_url('frontend/do_purchase_package/'.$lang_code.'/'.$package->id.'/'.($package->package_price-$discount)); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').' '.lang_check('with PayPal'); ?></a></li>
                                    <?php endif; ?>
                                    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('authorize_api_login_id'))): ?>
                                    <li><a href="<?php echo site_url('paymentconsole/authorize_payment/'.$lang_code.'/'.($package->package_price-$discount).'/'.$package->currency_code.'/'.$package->id.'/PAC'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').' '.lang_check('with CreditCard'); ?></a></li>
                                    <?php endif; ?>
                                    <?php if(!empty($settings_withdrawal_details) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
                                    <li><a href="<?php echo site_url('paymentconsole/invoice_payment/'.$lang_code.'/'.($package->package_price-$discount).'/'.$package->currency_code.'/'.$package->id.'/PAC'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').' '.lang_check('with bank payment'); ?></a></li>
                                    <?php endif; ?>

                                    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_item('stripe_enabled') !== FALSE): ?>
                                    <li><a href="<?php echo site_url('paymentconsole/strip_payment/'.$lang_code.'/'.$package->package_price.'/'.$package->currency_code.'/'.$package->id.'/PAC'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').' '.lang_check('via stripe.com'); ?></a></li>
                                    <?php endif; ?>

                                    <?php if(file_exists(APPPATH.'controllers/paypalrest.php') && !_empty(config_db_item('recurring_client_id'))): ?>
                                    <li><a href="<?php echo site_url('paypalrest/payment_recurring/'.$lang_code.'/'.$package->package_price.'/'.$package->currency_code.'/'.$package->id.'/PAC'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').' '.lang_check('via PayPal Recurring'); ?></a></li>
                                    <?php endif; ?>

                                </ul>
                                </div>
                                <?php else: ?> 
                                    <?php if(config_item('def_package') !== FALSE && config_item('def_package') == $package->id && strtotime($user['package_last_payment'])<=time() && $package->id !== $user['package_id']):?>
                                        <a href="<?php echo site_url('frontend/do_package_activate/'.$lang_code.'/'.config_item('def_package')); ?>" target="_blank" class="btn btn-middle btn-info"><?php echo lang_check('Activate');?></a>
                                    <?php endif; ?>                               
                                <?php endif; ?>                              
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                                <tr>
                                    <td ><?php echo lang_check('Not available');?></td>
                                </tr>
                            <?php endif;?>     
                        </tbody>
                    </table>
                </div>
            </div> 
            <?php endif;?>

            <div class="widget-panel">
                <div class="widget-header header-styles">
                    <h2 class="title"><?php echo lang_check('WithdrawalDetails'); ?></h2>
                </div>
                <div class="content-box">
                    <?php echo $settings_withdrawal_details; ?><br />
                    <?php _l('WithdrawalDetailsNotice');?>
                </div>
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