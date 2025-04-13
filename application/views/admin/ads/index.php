<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Ads')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all ads')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/ads')?>"><?php echo lang_check('Ads')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/ads/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add ads'), 'class="btn btn-primary"')?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wred">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Ads')?></div>
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
                            <th data-hide="phone,tablet"><?php echo lang('Code');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Type');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Activated');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('ads/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($ads)): foreach($ads as $ad):?>
                                    <tr>
                                    	<td><?php echo $ad->id?></td>
                                        <td>
                                        <?php echo anchor('admin/ads/edit/'.$ad->id, $ad->title)?>
                                        <?php echo ($ad->is_hardlocked == 1)?'&nbsp;&nbsp;<i class="icon-lock" style="color:red;"></i>':''?>
                                        </td>
                                        <td>
                                        <span class="label label-danger"><?php if(isset($this->ads_m->ads_types[$ad->type]))echo $this->ads_m->ads_types[$ad->type]; ?></span>
                                        </td>
                                        <td><?php echo ($ad->is_activated == 1)?'<i class="icon-ok"></i>':''?></td>
                                    	<td><?php echo btn_edit('admin/ads/edit/'.$ad->id)?></td>
                                    	<?php if(check_acl('ads/delete')):?><td><?php echo btn_delete('admin/ads/delete/'.$ad->id)?></td><?php endif;?>
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