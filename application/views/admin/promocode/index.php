<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Promocodeed')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all promocodes')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/promocode')?>"><?php echo lang_check('promocode')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
            <div class="row">
                <div class="col-md-12"> 
                    <?php echo anchor('admin/promocode/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add a promocode'), 'class="btn btn-primary"')?>
                </div>
            </div>
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('promocode')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                    <div class="form-group">
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('Id, Code Name'); ?>" type="text" />
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
                            <th><?php echo lang_check('Code');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Value');?></th>
                            <th ><?php echo lang_check('Quanitity');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Usage');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Package');?></th>
                            <th><?php echo lang_check('Start');?> </th>
                            <th><?php echo lang_check('End');?></th>
                            <th><?php echo lang_check('Used');?></th>
                            <th class="control"><?php echo lang_check('Edit');?></th>
                            <th class="control"><?php echo lang_check('Delete');?></th> 
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($all_promocodes)): foreach($all_promocodes as $promocode):?>
                                    <tr>
                                    	<td><?php _che($promocode->id);?></td>
                                    	<td><?php echo anchor('admin/promocode/edit/'.$promocode->id, $promocode->code_name)?></td>
                                        <td><?php _che($promocode->value)?></td>
                                        <td><?php _che($promocode->quantity)?></td>
                                        <td><?php _che($promocode->usage)?></td>
                                        <td>
                                            <?php echo $this->promocode_m->get_packages($promocode->packages, true)?>
                                        </td>
                                        <td><?php _che($promocode->date_start)?></td>
                                        <td><?php _che($promocode->date_end)?></td>
                                        <td><?php _che($promocode->used)?></td>
                                    	<td><?php echo (btn_edit('admin/promocode/edit/'.$promocode->id))?></td>
                                    	<td><?php echo (btn_delete('admin/promocode/delete/'.$promocode->id))?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                        <td colspan="20"><?php echo lang_check('We could not find any promocodes')?></td>
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