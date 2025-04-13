<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Estates')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang('View estate statuses')?></span>
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

                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    
 
                    <table class="table table-bordered table-statuses footable">
                      <thead>
                        <tr>
                        	<th><?php _l('Properties Pending Review'); ?></th>
                            <th><?php _l('Properties on Hold'); ?></th>
                            <th><?php _l('Properties contracted'); ?></th>
                        </tr>
                      </thead>
                      <tbody>
<tr>
	<td>
    <?php if(sw_count($estates_pending)): foreach($estates_pending as $estate):?>
    <div class="status_d">
    <span style="color: #2E367E;"><?php echo $estate->address; ?></span> 
    <span style="color: black;"><?php echo lang_check('Submitted').' '.format_d($estate->date); ?></span>
    <br />
    <span style="color: black;"><?php echo lang_check('You have').' '; ?></span>
    <span style="color: red;"><?php
    
    $date_change = strtotime($estate->date_modified);
    
    if(strtotime($estate->date_status) > $date_change)
        $date_change = strtotime($estate->date_status);
    
    $hours_left = (time()-$date_change)/3600;
    
    if($estate->status == 'REDUCED_PRICE')
    {
        echo intval(24-$hours_left);
    }
    else
    {
        echo intval(48-$hours_left);
    }
    
     ?></span>
    <span style="color: black;"><?php echo lang_check('hours left to review').' '; ?></span>
    
    <?php if($estate->status == 'RESUBMIT'): ?>
    <a class="btn btn-success" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE_RESUBMIT/statuses'); ?>">
    <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    <?php else: ?>
    <a class="btn btn-success" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE/statuses'); ?>">
    <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    <?php endif; ?>

    
    <?php if($estate->status == 'REDUCED_PRICE'): ?>
    <a class="btn btn btn-warning" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/HOLD_REDUCED/statuses'); ?>">
    <i class="icon-pause"></i> <?php echo lang_check('On hold'); ?></a>
    <?php elseif($estate->status == 'RESUBMIT'): ?>
    <a class="btn btn btn-warning" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/HOLD_RESUBMIT/statuses'); ?>">
    <i class="icon-pause"></i> <?php echo lang_check('On hold'); ?></a>
    <?php else: ?>
    <a class="btn btn btn-warning" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/HOLD/statuses'); ?>">
    <i class="icon-pause"></i> <?php echo lang_check('On hold'); ?></a>
    <?php endif; ?>
    <a class="btn btn btn-danger" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/DECLINE/statuses'); ?>">
    <i class="icon-remove-sign"></i> <?php echo lang_check('Decline'); ?></a>
    </div>
    <?php endforeach;?>
    <?php else:?>
    <div class="status_d">
    <?php echo lang('We could not find any');?>
    </div>
    <?php endif;?>    
    </td>
    <td>
    <?php if(sw_count($estates_hold)): foreach($estates_hold as $estate):?>
    <div class="status_d">
    <span style="color: #2E367E;"><?php echo $estate->address; ?></span> 
    <span style="color: black;"><?php echo lang_check('Placed on hold').' '.format_d($estate->date_status); ?></span><br />
    <span style="color: black;"><?php echo lang_check('You have').' '; ?></span>
    <span style="color: red;"><?php
    
    $hours_left = (time()-strtotime($estate->date_status))/3600;

    if($estate->status == 'HOLD_REDUCED')
    {
        echo intval(24-$hours_left);
    }
    else
    {
        echo intval(48-$hours_left);
    }
    
    
     ?></span>
    <span style="color: black;"><?php echo lang_check('hours left to take final action').' '; ?></span>
    <?php if($estate->status == 'HOLD_REDUCED'): ?>
    <a class="btn btn-success" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE_REDUCED/statuses'); ?>">
    <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    <?php elseif($estate->status == 'HOLD_RESUBMIT'): ?>
    <a class="btn btn-success" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE_RESUBMIT/statuses'); ?>">
    <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    <?php else: ?>
    <a class="btn btn-success" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE/statuses'); ?>">
    <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    <?php endif;?> 
    
    <a class="btn btn-info" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/CONTRACT/statuses'); ?>">
    <i class="icon-briefcase"></i> <?php echo lang_check('Contract property'); ?></a>
    
    
    </div>
    <?php endforeach;?>
    <?php else:?>
    <div class="status_d">
    <?php echo lang('We could not find any');?>
    </div>
    <?php endif;?>  
    </td>
    <td>
    <?php if(sw_count($estates_contracted)): foreach($estates_contracted as $estate):?>
    <div class="status_d">
    <span style="color: #2E367E;"><?php echo $estate->address; ?></span> 
    <span style="color: black;"><?php echo lang_check('Contracted on').' '.format_d($estate->date_status); ?></span>
    
    
    </div>
    <?php endforeach;?>
    <?php else:?>
    <div class="status_d">
    <?php echo lang('We could not find any');?>
    </div>
    <?php endif;?>  
    </td>
</tr>       
                      </tbody>
                    </table>


                  </div>
                </div>
            </div>
          </div>

        </div>
</div>
    
    
    
    
    
</section>