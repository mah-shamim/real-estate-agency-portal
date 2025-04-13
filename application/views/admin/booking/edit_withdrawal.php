<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Withdrawals')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo lang_check('Edit withdrawal'); ?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking/withdrawals')?>"><?php echo lang_check('Withdrawals')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Withdrawal data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('User')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('user_id', $users, $item->user_id, 'class="form-control"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Amount')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('amount', set_value('amount', $item->amount), 'class="form-control" id="inputAmount" placeholder="'.lang_check('Amount').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Currency')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('currency', set_value('currency', $item->currency), 'class="form-control" id="inputCurrency" placeholder="'.lang_check('Currency').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date requested')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker1">
                                    <?php echo form_input('date_requested', set_value('date_requested', $item->date_requested), 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date completed')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker2">
                                    <?php echo form_input('date_completed', set_value('date_completed', $item->date_completed), 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                                  
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Withdrawal email')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('withdrawal_email', set_value('withdrawal_email', $item->withdrawal_email), 'class="form-control" id="inputwithdrawal_email" placeholder="'.lang_check('Withdrawal email').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Completed')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('completed', '1', set_value('completed', $item->completed), 'id="inputCompleted"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/booking/withdrawals')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>
</div>

        </div>
		  </div>

<script>

/* CL Editor */
$(document).ready(function(){
    $(".cleditor2").cleditor({
        width: "auto",
        height: 250,
        docCSSFile: "<?php echo $template_css?>",
        baseHref: '<?php echo base_url('templates/'.$settings['template'])?>/'
    });
});

</script>