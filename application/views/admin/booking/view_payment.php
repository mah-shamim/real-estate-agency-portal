<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Payment')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo '#'.$payment->id; ?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking/payments')?>"><?php echo lang_check('Payments')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Payment').' #'.$payment->id; ?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang_check('Payer email');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Date paid');?></th>
                            <th><?php echo lang_check('Paid');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Reservation id');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Transaction id');?></th>
                            <th><?php echo lang_check('Invoice num');?></th>
                        </tr>
                      </thead>
                      <tbody>
                            <tr>
                            	<td><?php echo $payment->id?></td>
                                <td>
                                <?php echo $payment->payer_email?>
                                </td>
                                <td>
                                <?php echo $payment->date_paid?>
                                </td>
                                <td>
                                <?php echo $payment->paid.' '.$payment->currency_code; ?>
                                </td>
                                <td>
                                <?php 
                                    $inv_ex = explode('_', $payment->invoice_num);
                                    $reservation_id = $inv_ex[0];
                                    if(!empty($reservation_id))
                                    echo '<a href="'.site_url('admin/booking/edit/'.$reservation_id).'" class="label label-info">#'.$reservation_id.'</a>';
                                ?>
                                </td>
                                <td>
                                <?php echo $payment->txn_id; ?>
                                </td>
                                <td><?php echo $payment->invoice_num; ?></td>
                            </tr>         
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </div>
          
          
          
          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Details'); ?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <table class="table table-bordered ">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang_check('Value');?></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        $values = unserialize($payment->data_post);
                        if(sw_count($values) > 0 && is_array($values))
                        foreach($values as $key=>$val):
                      ?>
                            <tr>
                            	<td><?php echo $key; ?></td>
                                <td><?php echo $val; ?></td>
                            </tr>    
                      <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </div>
          
          
          
          
          
          
          
          
          
          
        </div>
</div>
    
    
    
    
    
</section>