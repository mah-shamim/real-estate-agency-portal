<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Reservations')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo empty($rate->id) ? lang_check('Add reservation') : lang_check('Edit reservation').' "' . $rate->id.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Reservations')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget worange">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Reservations data')?></div>
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
                                    <?php echo form_dropdown('user_id', $users, $this->input->post('user_id') ? $this->input->post('user_id') : $rate->user_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                                 
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Property')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('property_id', $properties, $this->input->post('property_id') ? $this->input->post('property_id') : $rate->property_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('From date')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker1">
                                    <?php echo form_input('date_from', $this->input->post('date_from') ? $this->input->post('date_from') : $rate->date_from, 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('To date')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker2">
                                    <?php echo form_input('date_to', $this->input->post('date_to') ? $this->input->post('date_to') : $rate->date_to, 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                <hr />
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Total price')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('total_price', set_value('total_price', $rate->total_price), 'class="form-control" id="inputTotalprice" placeholder="'.lang('Total price').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Total paid')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('total_paid', set_value('total_paid', $rate->total_paid), 'class="form-control" id="inputTotalpaid" placeholder="'.lang_check('Total paid').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Currency code')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('currency_code', $currencies, $this->input->post('currency_code') ? $this->input->post('currency_code') : $rate->currency_code, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Date paid advance')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker3">
                                    <?php echo form_input('date_paid_advance', $this->input->post('date_paid_advance') ? $this->input->post('date_paid_advance') : $rate->date_paid_advance, 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Date paid total')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker4">
                                    <?php echo form_input('date_paid_total', $this->input->post('date_paid_total') ? $this->input->post('date_paid_total') : $rate->date_paid_total, 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Confirmed')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_confirmed', '1', set_value('is_confirmed', $rate->is_confirmed), 'id="inputIsConfirmed"')?>
                                  </div>
                                </div>
                                
                                <hr />

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/booking')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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