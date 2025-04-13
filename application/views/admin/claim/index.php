<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Claimed')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all Claimed')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/claim')?>"><?php echo lang_check('Claim')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
            <div class="row">
                <div class="col-md-12"> 
                    <?php echo anchor('admin/claim/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add a Claim'), 'class="btn btn-primary"')?>
                </div>
            </div>
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Claim')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                    <div class="form-group">
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('smart_search_enquire'); ?>" type="text" />
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="message" id="message" value="<?php echo set_value_GET('message', '', true); ?>" placeholder="<?php echo lang_check('Message part'); ?>" type="text" />
                    </div>
                    <button type="submit" class="btn btn-default"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>

                    </form>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>

                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                            <th><?php echo lang_check('id');?></th>
                            <th><?php echo lang_check('Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Name');?></th>
                            <th ><?php echo lang_check('Phone');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Mail');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Message');?></th>
                            <th class="control"><?php echo lang_check('Edit object');?> </th>
                            <th class="control"><?php echo lang_check('Preview');?></th>
                            <th class="control"><?php echo lang_check('Edit');?></th>
                            <th class="control"><?php echo lang_check('Delete');?></th> 
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($all_claims)): foreach($all_claims as $claim):?>
                                    <tr>
                                    	<td><?php _che($claim->id);?></td>
                                    	<td><?php echo anchor('admin/claim/edit/'.$claim->id, $claim->date_submit)?></td>
                                        <td><?php _che($claim->name)?></td>
                                        <td><?php _che($claim->phone)?></td>
                                        <td><?php _che($claim->email)?></td>
                                        <td><?php _che(word_limiter(strip_tags($claim->message), 5));?></td>
                                    	<td><?php echo btn_edit('admin/user/edit/'.$claim->model_id)?></td>
                                        <td><a class="btn btn-info" target="_blank" href="<?php echo site_url('admin/user/edit/'.$claim->model_id)?>"><i class="icon-search"></i></a></td>
                                        
                                    	<td><?php _che(btn_edit('admin/claim/edit/'.$claim->id))?></td>
                                    	<td><?php _che(btn_delete('admin/claim/delete/'.$claim->id))?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any messages')?></td>
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