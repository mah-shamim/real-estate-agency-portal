<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Benchmark tools')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all benchmark tools')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/benchmarktool')?>"><?php echo lang_check('Benchmark tools')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Benchmark tools')?></div>
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
                        	<th><?php echo lang_check('Tool description');?></th>
                        	<th class="control"><?php echo lang_check('Run tool');?></th>
                        </tr>
                      </thead>
                      <tbody>
                            <tr>
                            	<td><?php echo lang_check('Remove all caches'); ?></td>
                              <td><a href="<?php echo site_url('admin/benchmarktool/remove_cache')?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php echo lang_check('Remove all listings'); ?></td>
                              <td><a href="<?php echo site_url('admin/benchmarktool/remove_listings')?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Find unused images'); ?></td>
                              <td><a href="<?php echo site_url('admin/benchmarktool/unused_images/0')?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Find missing translation definitions'); ?></td>
                              <td><a href="<?php echo site_url('admin/benchmarktool/translation_definitions/')?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Generate fake properties'); ?> x100</td>
                                <td><a href="<?php echo site_url('admin/benchmarktool/fake_listings/100')?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Generate fake properties'); ?> x1000</td>
                                <td><a href="<?php echo site_url('admin/benchmarktool/fake_listings/1000')?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Generate sitemap'); ?></td>
                                <td><a href="<?php echo site_url('admin/benchmarktool/generate_sitemap')?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Generate script archive');echo ' BASIC '.$settings['template']; ?></td>
                                <td><a href="<?php echo site_url('admin/benchmarktool/generate_script_basic/'.$settings['template'])?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Generate script archive');echo ' FULL '.$settings['template']; ?></td>
                                <td><a href="<?php echo site_url('admin/benchmarktool/generate_script_full/'.$settings['template'])?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Generate script archive');echo ' CLASSIFIEDS '.$settings['template']; ?></td>
                                <td><a href="<?php echo site_url('admin/benchmarktool/generate_script_classifieds/'.$settings['template'])?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                            <tr>
                            	<td><?php _l('Generate script LOCAL');echo ' LOCAL '.$settings['template']; ?></td>
                                <td><a href="<?php echo site_url('admin/benchmarktool/generate_script_local/'.$settings['template'])?>" class="btn btn-info btn-sm"><?php _l('Run tool'); ?></a></td>
                            </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>