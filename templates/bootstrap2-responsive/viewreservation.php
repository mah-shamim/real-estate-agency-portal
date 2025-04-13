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
                <div class="widget-content">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                        	<th class="span5">#</th>
                            <th><?php echo lang_check('Info');?></th>
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
                            <td><a href="<?php echo site_url('frontend/do_purchase/'.$this->data['lang_code'].'/'.$reservation['id'].'/'.number_format($reservation['total_price']*0.2, 2)); ?>"><img style="height:36px;" src="assets/img/pay-now-paypal.png" alt="" /></a></td>
                            <?php endif; ?>
                        
                        </tr>
                        <?php endif; ?>
                        <?php if($reservation['total_paid'] < $reservation['total_price']): ?>
                        <tr>
                        	<td><?php echo lang_check('Pay total');?>, <?php echo number_format($reservation['total_price']-$reservation['total_paid'], 2).' '.$reservation['currency_code']; ?></td>
                            <?php if(config_db_item('payments_enabled') == 1 && !_empty(config_db_item('paypal_email'))): ?>
                            <td><a href="<?php echo site_url('frontend/do_purchase/'.$this->data['lang_code'].'/'.$reservation['id'].'/'.number_format($reservation['total_price']-$reservation['total_paid'], 2)); ?>"><img style="height:36px;" src="assets/img/pay-now-paypal.png" alt="" /></a></td>
                            <?php endif; ?>
                        </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
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