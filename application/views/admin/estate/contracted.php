<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Estates')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang('View contracted estates')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
          <div class="row">

            <div class="col-md-12">

                <div class="widget wlightblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Estates')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                  
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                      <div class="form-group">
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('Smart item search'); ?>" type="text" />
                      </div>
                      <div class="form-group">
                        <?php echo form_dropdown('field_4', $this->option_m->get_field_values($content_language_id, 4, lang_check('Purpose')), set_value_GET('field_4', '', true), 'class="form-control"'); ?>
                      </div>
                      <div class="form-group">
                        <?php echo form_dropdown('field_2', $this->option_m->get_field_values($content_language_id, 2, lang_check('Type')), set_value_GET('field_2', '', true), 'class="form-control"'); ?>
                      </div>
                      <button type="submit" class="btn btn-default"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>

                    </form>
                  
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    
                    <?php echo form_open('admin/estate/delete_multiple', array('class' => '', 'style'=> 'padding:0px;margin:0px;', 'role'=>'form'))?> 
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang('Address');?></th>
                            <th data-hide="phone"><?php echo lang('Agent');?></th>
                            <!-- Dynamic generated -->
                            <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                            <th data-hide="phone,tablet"><?php echo $row->option?></th>
                            <?php endforeach;?>
                            <!-- End dynamic generated -->
                            <th data-hide="phone"><?php echo lang_check('Views');?></th>
                            <th data-hide="phone"><?php echo lang_check('Preview');?></th>

                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($estates)): foreach($estates as $estate):?>
                                    <tr>
                                    	<td><?php echo $estate->id?></td>
                                        <td>
                                        <?php echo $estate->address; ?>
                                        <?php if($estate->is_activated == 0 && FALSE):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-danger"><?php echo lang_check('Not Activated')?></span>
                                        <?php endif;?>
                                        <?php if(isset($settings['listing_expiry_days']) && $settings['listing_expiry_days'] > 0 && strtotime($estate->date_modified) <= time()-$settings['listing_expiry_days']*86400): ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning"><?php echo lang_check('Expired'); ?></span>
                                        <?php endif; ?>
                                        <?php if(!empty($estate->activation_paid_date)):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-success"><?php echo lang_check('Paid'); ?></span>
                                        <?php endif; ?>
                                        <?php if(!empty($estate->status)):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"><?php echo $estate->status; ?></span>
                                        <?php endif; ?>
                                        </td>
                                        <td><?php echo check_set($available_agent[$this->estate_m->get_user_id($estate->id)], '')?></td>
                                        <!-- Dynamic generated -->
                                        <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                        <td>
                                        <?php
                                            echo $this->estate_m->get_field_from_listing($estate, $row->option_id);
                                        ?>
                                        </td>
                                        <?php endforeach;?>
                                        <!-- End dynamic generated -->
                                        <td><?php echo $estate->counter_views; ?></td>
                                        <td><a class="btn btn-info" target="_blank" href="<?php echo site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$estate->id).'?preview=true';?>"><i class="icon-search"></i></a></td>

                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>
                    <?php echo form_close()?>
                    <div style="text-align: center;"><?php echo $pagination; ?></div>

                  </div>
                </div>
            </div>
          </div>

        </div>
</div>
    
    
    
    
    
</section>