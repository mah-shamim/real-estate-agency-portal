
<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Affilate package')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all packages')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/packages/affilatepackage')?>"><?php echo lang_check('Affilate package')?></a>
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
                            <th data-hide="phone,tablet"><?php echo lang_check('County');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Price');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Days limit');?></th>
                        	<th class=""><?php echo lang('Buy/Extend');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                            if(sw_count($listings)): foreach($listings as $listing):
                            
                            if(! $listing->affilate_price > 0)
                            {
                                continue;
                            }
                        ?>
                        <tr>
                        	<td><?php echo $listing->id; ?></td>
                            <td>
                                <?php echo $listing->value; 
                                
                                $can_be_purchased = true;
                                
                                // If owner, show date expire
                                if(isset($affilate_users[$listing->id][$this->session->userdata('id')]))
                                {
                                    $item = $affilate_users[$listing->id][$this->session->userdata('id')];
                                    
                                    echo ' <span class="label label-warning">'.$item->date_expire.'</span>';
                                }
                                else if(isset($affilate_users[$listing->id]))
                                {
                                    $can_be_purchased = false;
                                }
                                
                                
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo custom_number_format($listing->affilate_price*2).' ('.custom_number_format($listing->affilate_price).') '.$currency; 
                                
                                $payment_price = $listing->affilate_price*2;
                                if($can_be_purchased && isset($item) && strtotime($item->date_expire) > time())
                                {
                                    $payment_price = $listing->affilate_price;
                                }
                                
                                ?>
                            </td>
                            <td>
                                60 (30)
                            </td>

                        	<td>
<?php if($listing->affilate_price > 0  && config_db_item('payments_enabled') == 1 && $can_be_purchased): ?>

<?php if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE'): ?>
<div class="btn-group">
<a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo '<i class="icon-shopping-cart"></i> '.lang('Buy/Extend'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <?php if(!_empty(config_db_item('paypal_email'))): ?>
    <li><a href="<?php echo site_url('admin/packages/do_purchase_affilate/'.$listing->id.'/'.$payment_price); ?>"><?php echo lang_check('with PayPal'); ?></a></li>
    <?php endif; ?>
    <?php if(false): ?>
        <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && config_db_item('authorize_api_login_id') !== FALSE): ?>
        <li><a href="<?php echo site_url('paymentconsole/authorize_payment/'.$this->language_m->db_languages_id[$content_language_id].'/'.$payment_price.'/'.$currency.'/'.$listing->id.'/AFF'); ?>"><?php echo lang_check('with CreditCard'); ?></a></li>
        <?php endif; ?>
        
        <?php if(file_exists(APPPATH.'controllers/paymentconsole.php') && !_empty(config_db_item('payu_api_pos_id'))): ?>
        <li><a href="<?php echo site_url('paymentconsole/payu_payment/'.$this->language_m->db_languages_id[$content_language_id].'/'.$payment_price.'/'.$currency.'/'.$listing->id.'/AFF'); ?>"><?php echo lang_check('with CreditCard payu'); ?></a></li>
        <?php endif; ?>
        
        <?php if(!_empty(config_db_item('withdrawal_details')) && file_exists(APPPATH.'controllers/paymentconsole.php')):?>
        <li><a href="<?php echo site_url('paymentconsole/invoice_payment/'.$this->language_m->db_languages_id[$content_language_id].'/'.$payment_price.'/'.$currency.'/'.$listing->id.'/AFF'); ?>"><?php echo lang_check('with bank payment'); ?></a></li>
        <?php endif; ?>
    <?php endif; ?>
</ul>
<?php else: ?>
 <span class="label label-success"><?php _l('Available'); ?></span>
<?php endif; ?>


<?php elseif(!$can_be_purchased): ?>

<?php if($this->session->userdata('type') == 'ADMIN'): ?>
 <a href="<?php echo site_url('admin/user/edit/'.key($affilate_users[$listing->id])); ?>"><span class="label label-warning"><?php _l('Already purchased'); ?></span></a>
 
<?php else: ?>
 <span class="label label-warning"><?php _l('Already purchased'); ?></span>
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

