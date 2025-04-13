<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Research')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all researches')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/savesearch')?>"><?php echo lang_check('Research')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/savesearch/index')?>"><?php echo lang_check('Manage')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">
            <div class="col-md-12"> 
                <a class="btn btn-primary pull-right" href="<?php echo site_url('cronjob/research/output'); ?>" target="_blank"><i class="icon-filter"></i>&nbsp;&nbsp;<?php echo lang_check('Test CronJob'); ?></a>
            </div>
          </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Research')?></div>
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
                            <th><?php echo lang_check('User');?></th>
                            <th><?php echo lang_check('Lang code');?></th>
                            <th><?php echo lang_check('Activated');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('savesearch/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('admin/savesearch/index/'.$listing_item->user_id); ?>" class="label label-danger">
                                            <?php echo $users[$listing_item->user_id]; ?>
                                            </a>
                                        </td>
                                        <td><?php echo '['.strtoupper($listing_item->lang_code).']'; ?></td>
                                        <td>
                                            <?php echo $listing_item->activated?'<i class="icon-ok"></i>':'<i class="icon-remove"></i>'; ?>
                                        </td>
                                    	<td><?php echo btn_edit('admin/savesearch/edit/'.$listing_item->id)?></td>
                                    	<?php if(check_acl('savesearch/delete')):?><td><?php echo btn_delete('admin/savesearch/delete/'.$listing_item->id)?></td><?php endif;?>
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