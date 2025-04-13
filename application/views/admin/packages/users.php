
<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Users')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all users')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/packages')?>"><?php echo lang_check('Packages')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/packages/users')?>"><?php echo lang_check('Users')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Users')?></div>
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
                            <th><?php echo lang_check('Username');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Package name');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Package expire date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Days limit');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Listings limit');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Curr listings');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($users)): foreach($users as $listing):?>
                        <tr>
                        	<td><?php echo $listing->id; ?></td>
                            <td><a href="<?php echo site_url('admin/user/edit/'.$listing->id); ?>"><?php echo $listing->username; ?></a>
                            </td>
                            <td>
                            <?php echo $packages[$listing->package_id]; ?>
                            </td>
                            <td>
                            <?php 
                            if($packages_price[$listing->package_id] > 0)
                            if(strtotime($listing->package_last_payment) <= time() ||
                              (empty($listing->package_last_payment) && !empty($packages_days[$listing->package_id])) )
                             {
                                echo '<span class="label label-danger"><i class="icon-remove"></i>&nbsp;'._ch($listing->package_last_payment, lang_check('Missing payment')).'</span>';
                            }
                            else
                            {
                                echo $listing->package_last_payment; 
                            }
                            ?>
                            </td>
                            <td>
                            <?php echo $packages_days[$listing->package_id]; ?>
                            </td>
                            <td>
                            <?php echo $packages_listings[$listing->package_id]; ?>
                            </td>
                            <td>
                            <?php 
                            
                            if(isset($curr_listings[$listing->id]))
                            {
                                if($curr_listings[$listing->id] > $packages_listings[$listing->package_id])
                                {
                                    echo '<span class="label label-danger"><i class="icon-remove"></i>&nbsp;'.$curr_listings[$listing->id].'</span>';
                                }
                                else
                                {
                                    echo $curr_listings[$listing->id];
                                }
                            }
                            
                            ?>
                            </td>
                        	<td><?php echo btn_edit('admin/user/edit/'.$listing->id); ?></td>
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

