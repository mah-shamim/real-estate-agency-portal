<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Invoices')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all invoices')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/monetize/payments')?>"><?php echo lang_check('Monetize')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/monetize/invoices')?>"><?php echo lang_check('Invoices')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Invoices')?></div>
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
                        	<th class="control">#</th>
                            <th><?php echo lang_check('Item type');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Payer email');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Date paid');?></th>
                            <th><?php echo lang_check('Price');?></th>
                            <th class="control"><?php echo lang_check('Paid');?></th>
                            <th class="control" data-hide="phone,tablet"><?php echo lang_check('Activated');?></th>
                            <th class="control" data-hide="phone,tablet"><?php echo lang_check('Mark as paid');?></th>
                        	<th class="control"><?php echo lang_check('Edit');?></th>
                            <th class="control" data-hide="phone,tablet"><?php echo lang_check('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($invoices)): foreach($invoices as $item):?>
                                    <tr>
                                    	<td><?php echo $item->id_invoice?></td>
                                        <td>
                                        <?php 
                                            $inv_ex = explode('_', $item->invoice_num);
                                            $pay_type = $inv_ex[1];
                                            if(!empty($pay_type))
                                            echo '<a href="'.site_url('admin/monetize/invoices/'.$pay_type).'" class="label label-danger">#'.$pay_type.$item->reference_id.'</a>';
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                            $data_json = json_decode($item->data_json);

                                            if(!empty($data_json->user->mail))
                                            echo '<a href="'.site_url('admin/user/edit/'.$item->user_id).'" class="label label-warning">'.$data_json->user->mail.'</a>';
                                        ?>
                                        </td>
                                        <td>
                                        <?php echo $item->date_created?>
                                        </td>
                                        <td>
                                        <?php echo $item->price.' '.$item->currency_code; ?>
                                        </td>
                                        <td>
                                        <?php
                                            if($item->is_paid)
                                            {
                                                echo '<i class="icon-ok"></i>';
                                            }
                                            else
                                            {
                                                echo '<i class="icon-remove"></i>';
                                            }
                                        ?>
                                        </td>
                                        <td>
                                        <?php
                                            if($item->is_activated)
                                            {
                                                echo '<i class="icon-ok"></i>';
                                            }
                                            else
                                            {
                                                echo '<i class="icon-remove"></i>';
                                            }
                                        ?>
                                        </td>
                                        <td>
                                            <?php if(!$item->is_paid): ?>
                                            <a class="btn btn-success" href="<?php echo site_url('admin/monetize/mark_as_paid/'.$item->id_invoice); ?>"><i class="icon-usd"></i></a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo btn_edit('admin/monetize/edit_invoice/'.$item->id_invoice)?></td>
                                    	<td>
                                            <?php if(!$item->is_paid): ?>
                                            <?php echo btn_delete('admin/monetize/delete_invoice/'.$item->id_invoice)?>
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
                    
                    <div style="text-align: center;"><?php echo $pagination; ?></div>

                  </div>
                </div>
            </div>
          </div>
        </div>
</div>
    
    
    
    
    
</section>