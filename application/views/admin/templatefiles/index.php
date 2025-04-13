<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Template files list')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('Template files list')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread" href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Settings')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/templatefiles')?>"><?php echo lang_check('Template files list')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Template files list')?></div>
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
                    
                    <?php if($writing_permissions != ''):?>
                    <p class="label label-important validation"><?php echo $writing_permissions;?></p>
                    <?php endif; ?>
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                            <th><?php echo lang_check('Folder');?></th>
                            <th><?php echo lang_check('Filename');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)):
                              $label_index = -1;
                              $label_classes = array('default', 'primary', 'success', 'info', 'warning', 'danger');
                              foreach($listings as $folder_name=>$listings_folder):
                        ?>
                        <?php if(sw_count($listings_folder)): 
                              $label_index++;
                              if($label_index>=sw_count($label_classes))$label_index = 0;
                              foreach($listings_folder as $key=>$listing_item):
                              if($folder_name == 'language')continue;
                              if(is_array($listing_item)):
                              foreach($listing_item as $key1=>$listing_item1):
                              if(substr_count($listing_item1, 'min') > 0)continue;
                        ?>
                                    <tr>
                                        <td><?php echo '<span class="label label-'.$label_classes[$label_index].'">'.$folder_name.'</span>'; ?></td>
                                        <td><?php echo '<a href="'.site_url('admin/templatefiles/edit/'.$listing_item1.'/'.$folder_name.'/'.$key).'" />'.$listing_item1.'</a>'; ?></td>
                                    	<td><?php echo btn_edit('admin/templatefiles/edit/'.$listing_item1.'/'.$folder_name.'/'.$key)?></td>
                                    </tr>
                        <?php endforeach;else: ?>
                                    <tr>
                                        <td><?php echo '<span class="label label-'.$label_classes[$label_index].'">'.$folder_name.'</span>'; ?></td>
                                        <td><?php echo '<a href="'.site_url('admin/templatefiles/edit/'.$listing_item.'/'.$folder_name).'" />'.$listing_item.'</a>'; ?></td>
                                    	<td><?php echo btn_edit('admin/templatefiles/edit/'.$listing_item.'/'.$folder_name)?></td>
                                    </tr>
                        <?php endif;endforeach;endif;?>
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