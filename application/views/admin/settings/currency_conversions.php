
    <!-- Page heading -->
    <div class="page-head">
    <!-- Page heading -->
        <h2 class="pull-left"><?php echo lang('Settings')?>
		  <!-- page meta -->
		  <span class="page-meta"><?php echo lang('System settings')?></span>
		</h2>

		<!-- Breadcrumb -->
		<div class="bread-crumb pull-right">
          <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a class="bread" href="<?php echo site_url('admin/settings')?>"><?php echo lang('Settings')?></a>
          <span class="divider">/</span> 
          <a class="bread-current" href="<?php echo site_url('admin/settings/currency_conversions')?>"><?php echo lang_check('Currency Conversions')?></a>
		</div>

		<div class="clearfix"></div>

    </div>
    <!-- Page heading ends -->

    <!-- Matter -->
    <div class="matter-settings">
    
    <div style="margin-bottom: 8px;" class="tabbable">
      <ul class="nav nav-tabs settings-tabs">
        <li><a href="<?php echo site_url('admin/settings/contact')?>"><?php echo lang('Company contact')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/language')?>"><?php echo lang('Languages')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Template')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/system')?>"><?php echo lang('System settings')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/addons')?>"><?php echo lang_check('Addons')?></a></li>
        <?php if(config_db_item('slug_enabled') === TRUE): ?>
        <li><a href="<?php echo site_url('admin/settings/slug')?>"><?php echo lang_check('SEO slugs')?></a></li>
        <?php endif; ?>
        <?php if(config_db_item('currency_conversions_enabled') === TRUE): ?>
        <li class="active"><a href="<?php echo site_url('admin/settings/currency_conversions')?>"><?php echo lang_check('Currency Conversions')?></a></li>
        <?php endif; ?>
      </ul>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/settings/currency_conversions_edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add currency'), 'class="btn btn-primary"')?>
                <?php echo anchor('admin/settings/currency_import_from_ecb/', '<i class="icon-arrow-up"></i>&nbsp;&nbsp;'.lang_check('Import from XML'), 'class="btn btn-success pull-right"')?>
            </div>
        </div>
          <div class="row">

            <div class="col-md-12">


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Currency Conversions')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    
                    <?php if(isset($language_writing_permissions) && $language_writing_permissions != ''):?>
                    <p class="label label-important validation"><?php echo $language_writing_permissions;?></p>
                    <?php endif; ?>
                    
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th><?php echo lang_check('Currency code');?></th>
                            <th><?php echo lang_check('Conversion index');?></th>
                            <th><?php echo lang_check('Currency symbol');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<th class="control"><?php echo lang('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($conversions)): foreach($conversions as $item):?>
                                    <tr>
                                    	<td>
                                            <?php echo anchor('admin/settings/currency_conversions_edit/'.$item->id, lang_check($item->currency_code))?>
                                        </td>
                                        <td><?php echo $item->conversion_index?></td>
                                        <td><?php echo $item->currency_symbol?></td>
                                        <td><?php echo btn_edit('admin/settings/currency_conversions_edit/'.$item->id)?></td>
                                    	<td><?php echo btn_delete('admin/settings/currency_conversions_delete/'.$item->id)?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="6"><?php echo lang('We could not find any')?></td>
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

	<!-- Matter ends -->

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>