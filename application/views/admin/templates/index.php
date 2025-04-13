<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Visual templates editor')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all templates')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread" href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Settings')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/templates')?>"><?php echo lang_check('Visual templates editor')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/templates/index')?>"><?php echo lang_check('Manage')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">
            <div class="col-md-12"> 
                <a class="btn btn-primary" href="<?php echo site_url('admin/templates/edit'); ?>"><i class=" icon-plus"></i>&nbsp;&nbsp;<?php echo lang_check('Add page template'); ?></a>
            </div>
          </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Page templates')?></div>
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
                            <th><?php echo lang_check('Template name');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('templates/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('admin/templates/edit/'.$listing_item->id); ?>">
                                            <?php echo $listing_item->template_name; ?>
                                            </a>
                                        </td>
                                    	<td><?php echo btn_edit('admin/templates/edit/'.$listing_item->id)?></td>
                                    	<?php if(check_acl('templates/delete')):?><td><?php echo btn_delete('admin/templates/delete/'.$listing_item->id)?></td><?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>
                    <?php if(isset($pagination)):?>
                    <div style="text-align: center;"><?php echo $pagination; ?></div>
                    <?php endif;?>
                  </div>
                </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12"> 
                <a class="btn btn-warning" href="<?php echo site_url('admin/templates/edit_listing'); ?>"><i class=" icon-plus"></i>&nbsp;&nbsp;<?php echo lang_check('Add listing template'); ?></a>
            </div>
          </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Listing templates')?></div>
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
                            <th><?php echo lang_check('Template name');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('templates/delete_listing')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings_property)): foreach($listings_property as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('admin/templates/edit_listing/'.$listing_item->id); ?>">
                                            <?php echo $listing_item->template_name; ?>
                                            </a>
                                        </td>
                                    	<td><?php echo btn_edit('admin/templates/edit_listing/'.$listing_item->id)?></td>
                                    	<?php if(check_acl('templates/delete_listing')):?><td><?php echo btn_delete('admin/templates/delete_listing/'.$listing_item->id)?></td><?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>
                    
                    <?php if(isset($pagination)):?>
                    <div style="text-align: center;"><?php echo $pagination; ?></div>
                    <?php endif;?>
                  </div>
                </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12"> 
                <a class="btn btn-danger" href="<?php echo site_url('admin/templates/edit_item'); ?>"><i class=" icon-plus"></i>&nbsp;&nbsp;<?php echo lang_check('Add result item template'); ?></a>
            </div>
          </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wred">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Result item templates')?></div>
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
                            <th><?php echo lang_check('Template name');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('templates/delete_item')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings_item)): foreach($listings_item as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('admin/templates/edit_item/'.$listing_item->id); ?>">
                                            <?php echo $listing_item->template_name; ?>
                                            </a>
                                        </td>
                                    	<td><?php echo btn_edit('admin/templates/edit_item/'.$listing_item->id)?></td>
                                    	<?php if(check_acl('templates/delete_item')):?><td><?php echo btn_delete('admin/templates/delete_item/'.$listing_item->id)?></td><?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>
                    
                    <?php if(isset($pagination)):?>
                    <div style="text-align: center;"><?php echo $pagination; ?></div>
                    <?php endif;?>
                  </div>
                </div>
            </div>
          </div>
          
        </div>
</div>
    
    
    
    
    
</section>