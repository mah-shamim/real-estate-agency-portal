
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
      <a class="" href="<?php echo site_url('admin/packages')?>"><?php echo lang_check('Packages')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/packages/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add package'), 'class="btn btn-primary"')?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Packages')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th data-hide=""><?php echo lang_check('Package name');?></th>
                            <th data-hide=""><?php echo lang_check('Price');?></th>
                            <th data-hide="phone"><?php echo lang_check('Days limit');?></th>
                            <th data-hide="phone"><?php echo lang_check('Listings limit');?></th>
                            <th data-hide="phone"><?php echo lang_check('Num featured limit');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('packages/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($packages)): foreach($packages as $listing):?>
                        <tr>
                        	<td><?php echo $listing->id?></td>
                            <td>
                            <a href="<?php echo site_url('admin/packages/edit/'.$listing->id); ?>"><?php echo $listing->package_name; ?></a>
                            <?php if(!empty($listing->user_type)): ?>
                            &nbsp;<span class="label label-danger"><?php echo $listing->user_type; ?></span>
                            <?php endif;?>  
                            <?php echo $listing->show_private_listings==1?'&nbsp;<i class="icon-eye-open"></i>':'&nbsp;<i class="icon-eye-close"></i>'; ?>
                            </td>
                            <td>
                            <?php echo $listing->package_price.' '.$listing->currency_code; ?>
                            </td>
                            <td>
                            <?php echo $listing->package_days?>
                            </td>
                            <td>
                            <?php echo $listing->num_listing_limit?>
                            </td>
                            <td>
                            <?php echo $listing->num_featured_limit?>
                            </td>
                        	<td><?php echo btn_edit('admin/packages/edit/'.$listing->id)?></td>
                        	<?php if(check_acl('packages/delete')):?><td><?php echo btn_delete('admin/packages/delete/'.$listing->id)?></td><?php endif;?>
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

