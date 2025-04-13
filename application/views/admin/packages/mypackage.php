
<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Packages')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all packages')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/packages/mypackage')?>"><?php echo lang_check('My package')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
          <div class="row">

            <div class="col-md-12">

                <div class="widget wviolet">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Packages')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Package name');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Price');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Days limit');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Listings limit');?></th>
                            <th data-hide="phone"><?php echo lang_check('Num featured limit');?></th>
                        	<th class="control"><?php echo lang('Buy/Extend');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                            if(sw_count($packages)): foreach($packages as $listing):
                            
                            if(!empty($user['package_id']) && 
                               $user['package_id'] != $listing->id &&
                               strtotime($user['package_last_payment']) >= time() &&
                               $packages_days[$listing->id] > 0 &&
                               $packages_price[$user['package_id']] > 0)
                            {
                                continue;
                            }
                            else if(!empty($listing->user_type) && $listing->user_type != 'AGENT' && $user['package_id'] != $listing->id)
                            {
                                continue;
                            }
                        ?>
                        <tr>
                        	<td><?php echo $listing->id; ?></td>
                            <td>
                                <?php echo $listing->package_name; ?>
                                <?php if(!empty($listing->user_type)): ?>
                                &nbsp;<span class="label label-danger"><?php echo $listing->user_type; ?></span>
                                <?php endif;?>  
                                <?php if($user['package_id'] == $listing->id):?>
                                &nbsp;<span class="label label-success"><?php echo lang_check('Activated'); ?></span>
                                <?php else: ?>
                                &nbsp;<span class="label label-important"><?php echo lang_check('Not activated'); ?></span>
                                <?php endif;?>
                                
                                <?php if($listing->package_price > 0 && $user['package_id'] == $listing->id && strtotime($user['package_last_payment']) < time() && $packages_days[$listing->id] > 0): ?>
                                &nbsp;<span class="label label-warning"><?php echo lang_check('Expired'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                            <?php echo $listing->package_price.' '.$listing->currency_code; ?>
                            </td>
                            <td>
                            <?php 
                                echo $listing->package_days;
                            
                                if($user['package_id'] == $listing->id && $listing->package_price > 0 &&
                                   strtotime($user['package_last_payment']) >= time() && $packages_days[$listing->id] > 0 )
                                {
                                    echo ', '.$user['package_last_payment'];
                                }
                            
                            ?>
                            </td>
                            <td>
                            <?php echo $listing->num_listing_limit; ?>
                            </td>
                            <td>
                            <?php echo $listing->num_featured_limit?>
                            </td>
                        	<td>
<?php if($listing->package_price > 0  && config_db_item('payments_enabled') == 1): ?>
<div class="btn-group">
<a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo '<i class="icon-shopping-cart"></i> '.lang('Buy/Extend'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <?php if(!_empty(config_db_item('paypal_email'))): ?>
    <li><a href="<?php echo site_url('admin/packages/do_purchase_package/'.$listing->id.'/'.$listing->package_price); ?>"><?php echo lang_check('with PayPal'); ?></a></li>
    <?php endif; ?>
    
    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_db_item('authorize_api_login_id') !== FALSE): ?>
    <li><a href="<?php echo site_url('paymentconsole/authorize_payment/'.$this->language_m->db_languages_id[$content_language_id].'/'.$listing->package_price.'/'.$listing->currency_code.'/'.$listing->id.'/PAC'); ?>"><?php echo lang_check('with CreditCard'); ?></a></li>
    <?php endif; ?>
    
    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('payu_api_pos_id'))): ?>
    <li><a href="<?php echo site_url('paymentconsole/payu_payment/'.$this->language_m->db_languages_id[$content_language_id].'/'.$listing->package_price.'/'.$listing->currency_code.'/'.$listing->id.'/PAC'); ?>"><?php echo lang_check('with CreditCard payu'); ?></a></li>
    <?php endif; ?>
    
    <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_item('stripe_enabled') !== FALSE): ?>
    <li><a href="<?php echo site_url('paymentconsole/strip_payment/'.$this->language_m->db_languages_id[$content_language_id].'/'.$listing->package_price.'/'.$listing->currency_code.'/'.$listing->id.'/PAC'); ?>"><?php echo lang_check('with strip.com'); ?></a></li>
    <?php endif; ?>
    
    <?php if(!_empty(config_db_item('withdrawal_details')) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
    <li><a href="<?php echo site_url('paymentconsole/invoice_payment/'.$this->language_m->db_languages_id[$content_language_id].'/'.$listing->package_price.'/'.$listing->currency_code.'/'.$listing->id.'/PAC'); ?>"><?php echo lang_check('with bank payment'); ?></a></li>
    <?php endif; ?>

    <?php if(file_exists(APPPATH.'controllers/paypalrest.php') && !_empty(config_db_item('recurring_client_id'))): ?>
    <li><a href="<?php echo site_url('paypalrest/payment_recurring/'.$this->language_m->db_languages_id[$content_language_id].'/'.$listing->package_price.'/'.$listing->currency_code.'/'.$listing->id.'/PAC'); ?>"><?php echo '<i class="fa fa-shopping-cart"></i> '.lang('Buy/Extend').'<br />'.lang_check('via PayPal Recurring'); ?></a></li>
    <?php endif; ?>
</ul>
<?php else: ?> 
    <?php if(config_item('enable_free_package_reactication') !== FALSE && config_item('def_package') !== FALSE && config_item('def_package') == $listing->id &&  strtotime($user['package_last_payment'])<=time() && $listing->id !== $user['package_id']):?>
        <a href="<?php echo site_url('admin/packages/do_package_activate/'.$this->language_m->db_languages_id[$content_language_id].'/'.config_item('def_package')); ?>" class="btn btn-middle btn-info"><?php echo lang_check('Activate');?></a>
    <?php endif; ?>                               
<?php endif; ?>       
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <?php else:?>
                        <tr>
                        	<td colspan="20"><?php echo lang('We could not find any');?></td>
                        </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>

</section>

