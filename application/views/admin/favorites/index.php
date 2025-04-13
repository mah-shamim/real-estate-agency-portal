<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Favorites')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all favorites')?></span>
    </h2>
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/favorites')?>"><?php echo lang_check('Favorites')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/favorites/index')?>"><?php echo lang_check('Manage')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">
            <div class="col-md-12"> 
                <a class="btn btn-primary pull-right" href="<?php echo site_url('cronjob/favorites/output'); ?>" target="_blank"><i class="icon-filter"></i>&nbsp;&nbsp;<?php echo lang_check('Test CronJob'); ?></a>
            </div>
          </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Favorites')?></div>
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
                            <th><?php echo lang_check('Listing');?></th>
                            <th><?php echo lang_check('User');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Date');?></th>
                        	<?php if(check_acl('favorites/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <?php if(false): ?>
                                        <td>
                                        <?php 
                                        
                                        $json_obj = json_decode($listing_item->json_object);
                                        
                                        if(!empty($json_obj->field_10))
                                            echo $json_obj->field_10;
                                        
                                        ?></td>
                                        <?php endif; ?>
                                        
                                        <td>
                                            <a href="<?php echo site_url('admin/favorites/index/'.$listing_item->property_id); ?>" class="label label-danger">
                                            <?php echo '#'.$listing_item->property_id.', '.$listing_item->address; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('admin/favorites/index/0/'.$listing_item->user_id); ?>" class="label label-warning">
                                            <?php echo $listing_item->username; ?>
                                            </a>
                                        </td>
                                        <td>
                                        <?php echo $listing_item->date_saved; ?>
                                        </td>
                                    	<?php if(check_acl('favorites/delete')):?><td><?php echo btn_delete('admin/favorites/delete/'.$listing_item->id)?></td><?php endif;?>
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