<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Backups')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all backups')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/backup')?>"><?php echo lang_check('Backups')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
            <div class="row">
                <div class="col-md-12"> 
                    <?php echo anchor('admin/backup/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add a backup'), 'class="btn btn-primary"')?>
                </div>
            </div>
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Backups')?></div>
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
                        	<th><?php echo lang_check('Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Script version');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('SQL file');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('ZIP file');?></th>
                        	<th class="control"><?php echo lang_check('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($backups)): foreach($backups as $item):?>
                                    <tr>
                                    	<td><?php echo $item->date_created; ?></td>
                                        <td><?php echo $item->script_version; ?></td>
                                        <td><a href="<?php echo site_url('admin/backup/download/sql/'.$item->id); ?>" class="btn btn-success"><?php echo $item->sql_file; ?></a></td>
                                        <td><a href="<?php echo site_url('admin/backup/download/zip/'.$item->id); ?>" class="btn btn-warning"><?php echo $item->zip_file; ?></a></td>
                                        <td><?php echo btn_delete('admin/backup/delete/'.$item->id)?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="10"><?php echo lang('We could not find any')?></td>
                                    </tr>
                        <?php endif;?>                   
                      </tbody>
                    </table>
                  </div>
                  
                  <div>
                  <br />
                  <p class="label label-warning"><?php echo lang_check('backup_suggestions'); ?></p>
                  <br />
                  <p class="label label-important"><?php echo lang_check('backup_available'); ?></p>
                  <br />
                  <p class="label label-info"><?php echo lang_check('backup_note'); ?></p>
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>