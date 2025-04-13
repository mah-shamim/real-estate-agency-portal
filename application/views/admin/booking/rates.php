<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Rates')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all rates')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking/rates')?>"><?php echo lang_check('Rates')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/booking/edit_rate', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add rate'), 'class="btn btn-primary"')?>
                <?php if(config_db_item('add_multiple_rates_summer_enabled') === TRUE ): ?>
                    <?php echo anchor('admin/booking/add_multiple_rates_summer/', '<i class="icon-arrow-up"></i>&nbsp;&nbsp;'.lang_check('Add multiple rates (summer)'), 'class="btn btn-success"')?>
                <?php endif; ?>
                <?php if(config_db_item('booking_import_enabled') === TRUE && ($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN')): ?>
                    <?php echo anchor('admin/booking/import_rate/', '<i class="icon-arrow-up"></i>&nbsp;&nbsp;'.lang_check('Import rates via XML'), 'class="btn btn-success pull-right"')?>
                <?php endif; ?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off" style="margin: 10px 0;">
                        <div class="form-group">
                            <label class="control-label"><?php echo lang_check('Smart')?></label>
                            <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('Property id'); ?>" type="text" />
                        </div>
                        <div class="form-group">
                            <label class="control-label" style="height: 17px;display: block;"></label>
                            <button type="submit" class="btn btn-default" style="padding: 8px 12px;"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>
                        </div>
                    </form>

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Rates')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th data-hide="phone,tablet"><?php echo lang_check('From Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('To Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Property');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('booking/delete_rate')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($rates)): foreach($rates as $news_post):?>
                                    <tr>
                                    	<td><?php echo $news_post->id?></td>
                                        <td>
                                        <?php echo $news_post->date_from?>
                                        </td>
                                        <td>
                                        <?php echo $news_post->date_to?>
                                        </td>
                                        <td>
                                        <a href="<?php echo site_url('admin/booking/rates/'.$news_post->property_id); ?>" class="label label-danger"><?php echo '#'.$news_post->property_id.' - '.$properties[$news_post->property_id]?></a>
                                        </td>
                                    	<td><?php echo btn_edit('admin/booking/edit_rate/'.$news_post->id)?></td>
                                    	<?php if(check_acl('booking/delete_rate')):?><td><?php echo btn_delete('admin/booking/delete_rate/'.$news_post->id)?></td><?php endif;?>
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