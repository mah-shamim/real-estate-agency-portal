<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Estates')?>
      <!-- page meta -->
      <span class="page-meta"><?php _l('Removed')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
          <div class="row">

            <div class="col-md-12">

                <div class="widget wlightblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Estates')?></div>
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
                            <th><?php echo lang('Address');?></th>
                            <th data-hide="phone"><?php _l('Gps');?></th>
                            <th data-hide="phone"><?php _l('Removed');?></th>
                            <th data-hide="phone,tablet"><?php _l('Submission');?></th>
                            <th data-hide="phone,tablet"><?php _l('Expire');?></th>
                            <th data-hide="phone,tablet"><?php _l('Price');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $item):?>
                                    <tr>
                                    	<td><?php echo $item->id?></td>
                                        <td><?php echo $item->address?></td>
                                        <td><?php echo $item->lat.', '.$item->lng?></td>
                                        <td><?php echo $item->date_removed?></td>
                                        <td><?php echo $item->submission_date?></td>
                                        <td><?php echo $item->expire_date?></td>
                                        <td><?php echo $item->price_0?></td>
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