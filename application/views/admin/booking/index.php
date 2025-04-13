<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Reservations')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all reservations')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Reservations')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        <?php if($this->session->userdata('type') == 'ADMIN'): ?>
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/booking/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add reservation'), 'class="btn btn-primary"')?>
                
                <?php if(config_db_item('booking_multi_reservations') === TRUE): ?>
                <?php echo anchor('admin/booking/add_multiple', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang_check('Add multiple reservations'), 'class="btn btn-info"')?>
                <?php endif; ?>
                
                <?php if(config_db_item('booking_import_enabled') === TRUE && ($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN')): ?>
                    <?php echo anchor('admin/booking/import_booked/', '<i class="icon-arrow-up"></i>&nbsp;&nbsp;'.lang_check('Import booked dates via XML'), 'class="btn btn-success pull-right"')?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off" style="margin: 10px 0;">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang_check('Client')?></label>
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('smart_search_reservations'); ?>" type="text" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo lang_check('Date from')?></label>
                        <div class="input-append input-date" id="datetimepicker1">
                            <input type="text" name="date_from" value="<?php echo set_value_GET('date_from', '', true); ?>" class="picker" placeholder="yyyy-MM-dd" data-format="yyyy-MM-dd">                                    <span class="add-on">
                              &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                              </i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo lang_check('Date to')?></label>
                        <div class="input-append input-date" id="datetimepicker2">
                            <input type="text" name="date_to" value="<?php echo set_value_GET('date_to', '', true); ?>" class="picker" placeholder="yyyy-MM-dd" data-format="yyyy-MM-dd">                                    <span class="add-on">
                                &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                </i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" style="height: 17px;display: block;"></label>
                        <button type="submit" class="btn btn-default" style="padding: 8px 12px;"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>
                    </div>
                </form>

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Reservations')?></div>
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
                            <th data-hide="phone,tablet"><?php echo lang_check('User');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Property');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('From Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('To Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Paid');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('booking/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($reservations)): foreach($reservations as $listing):?>
                        <tr>
                        	<td><?php echo $listing->id?></td>
                            <td><a href="<?php echo site_url('admin/booking/edit/'.$listing->id); ?>"><?php echo '#'.$listing->user_id.' - '._ch($users[$listing->user_id])?></a>
                            <?php echo $listing->is_confirmed==1?'<i class="icon-ok"></i>':'<i class="icon-remove"></i>'; ?>
                            </td>
                            <td>
                            <a href="<?php echo site_url('admin/booking/index/'.$listing->property_id); ?>" class="label label-danger"><?php echo '#'.$listing->property_id.' - '._ch($properties[$listing->property_id])?></a>
                            </td>
                            <td>
                            <?php echo $listing->date_from?>
                            </td>
                            <td>
                            <?php echo $listing->date_to?>
                            </td>
                            <td>
                            <?php if($listing->total_paid == $listing->total_price): ?>
                            <span class="label label-success"><?php echo $listing->total_paid.'/'.$listing->total_price.' '.$listing->currency_code; ?></span>
                            <?php elseif($listing->total_paid > 0):?>
                            <span class="label label-warning"><?php echo $listing->total_paid.'/'.$listing->total_price.' '.$listing->currency_code; ?></span>
                            <?php else: ?>
                            <span class="label label-default"><?php echo $listing->total_paid.'/'.$listing->total_price.' '.$listing->currency_code; ?></span>
                            <?php endif; ?>
                            </td>
                        	<td><?php echo btn_edit('admin/booking/edit/'.$listing->id)?></td>
                        	<?php if(check_acl('booking/delete')):?>
                            <td>
                            <?php if($listing->total_paid <= 0): ?>
                            <?php echo btn_delete('admin/booking/delete/'.$listing->id)?>
                            <?php endif;?>
                            </td><?php endif;?>
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