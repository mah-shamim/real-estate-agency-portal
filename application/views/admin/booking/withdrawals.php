<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Withdrawals')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('All Withdrawals')?></span>
    </h2>
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking/withdrawals')?>"><?php echo lang_check('Withdrawals')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/booking/withdrawals_export', '<i class="icon-arrow-down"></i>&nbsp;&nbsp;'.lang_check('Export not completed'), 'class="btn btn-info"')?>
            </div>
        </div>
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Withdrawals')?></div>
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
                            <th><?php _l('User id');?></th>
                            <th data-hide="phone,tablet"><?php _l('Email');?></th>
                            <th data-hide="phone,tablet"><?php _l('Date requested');?></th>
                            <th data-hide="phone,tablet"><?php _l('Date completed');?></th>
                            <th><?php _l('Amount');?></th>
                            <th><?php _l('Completed');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('booking/delete_rate')):?><th data-hide="phone,tablet" class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                    	<td><?php echo $listing_item->id_withdrawal?></td>
                                        <td>
                                        <a class="label label-danger" href="<?php echo site_url('admin/booking/withdrawals/'.$listing_item->user_id); ?>">
                                        <?php echo '#'.$listing_item->user_id?>
                                        </a>
                                        </td>
                                        <td>
                                        <?php echo $listing_item->withdrawal_email?>
                                        </td>
                                        <td>
                                        <?php echo $listing_item->date_requested?>
                                        </td>
                                        <td>
                                        <?php echo $listing_item->date_completed?>
                                        </td>
                                        <td>
                                        <?php echo $listing_item->amount.' '.$listing_item->currency?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($listing_item->completed)
                                        {
                                            echo '<span class="label label-success"><i class="icon-ok icon-white"></i></span>';
                                        }
                                        else
                                        {
                                            echo '<span class="label label-important"><i class="icon-remove icon-white"></i></span>';
                                        }
                                        ?>
                                        </td>
                                    	<td><?php echo btn_edit('admin/booking/edit_withdrawal/'.$listing_item->id_withdrawal)?></td>
                                    	<td>
                                        <?php
                                        
                                        if(!$listing_item->completed)
                                            echo btn_delete('admin/booking/delete_withdrawal/'.$listing_item->id_withdrawal);
                                        
                                        ?>
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