<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Reviews')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all reviews')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/reviews')?>"><?php echo lang_check('Reviews')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/reviews/index')?>"><?php echo lang_check('Manage')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Reviews')?></div>
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
                            <th data-hide="phone,tablet"><?php echo lang_check('Stars');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Date');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('reviews/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                        <?php 
                                            /*<a href="<?php echo site_url('admin/booking/rates/'.$news_post->property_id); ?>" class="label label-danger"><?php echo '#'.$news_post->property_id.' - '.$properties[$news_post->property_id]?></a>
                                    	*/ ?>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('admin/reviews/index/'.$listing_item->listing_id); ?>" class="label label-danger">
                                            <?php echo '#'.$listing_item->listing_id.', '.$listing_item->address; ?>
                                            </a>
                                        </td>
                                        <td>
                                        <?php if(!empty($listing_item->user_id)): ?>
                                            <a href="<?php echo site_url('admin/reviews/index/0/'.$listing_item->user_id); ?>" class="label label-warning">
                                            <?php echo $listing_item->username; ?>
                                            </a>
                                        <?php elseif(!empty($listing_item->user_mail)): ?>
                                            <?php echo $listing_item->user_mail; ?>
                                        <?php endif; ?>
                                        </td>
                                        <td><div class="review_stars_<?php echo $listing_item->stars; ?>"> </div></td>
                                        <td>
                                        <?php echo $listing_item->date_publish; ?>
                                        </td>
                                    	<td><?php echo btn_edit('admin/reviews/edit/'.$listing_item->id)?></td>
                                    	<?php if(check_acl('reviews/delete')):?><td><?php echo btn_delete('admin/reviews/delete/'.$listing_item->id)?></td><?php endif;?>
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