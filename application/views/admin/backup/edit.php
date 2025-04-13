<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Enquire')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($user->id) ? lang('Add a new enquire') : lang('Edit enquire').' "' . $enquire->name_surname.'"'?></span>
        </h2>

    <!-- Breadcrumb -->
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/enquire')?>"><?php echo lang('Enquires')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Enquire data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Estate')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('property_id', $all_estates, set_value('property_id', $enquire->property_id), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Name and surname')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('name_surname', set_value('name_surname', $enquire->name_surname), 'class="form-control" id="inputNameSurname" placeholder="'.lang('Name and surname').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Phone')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('phone', set_value('phone', $enquire->phone), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Mail')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('mail', set_value('mail', $enquire->mail), 'class="form-control" id="inputMail" placeholder="'.lang('Mail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('FromDate')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker1">
                                    <?php echo form_input('fromdate', set_value('fromdate', $enquire->fromdate), 'class="picker" data-format="yyyy-MM-dd"')?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('ToDate')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker2">
                                    <?php echo form_input('todate', set_value('todate', $enquire->todate), 'class="picker" data-format="yyyy-MM-dd"')?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Message')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('message', set_value('message', $enquire->message), 'placeholder="'.lang('Message').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>    

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Address')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('address', set_value('address', $enquire->address), 'placeholder="'.lang('Address').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Readed')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('readed', '1', set_value('readed', $enquire->readed), 'id="inputReaded"')?>
                                  </div>
                                </div>
                                
                                <hr />
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-success"')?>
                                    <a href="<?php echo site_url('admin/enquire')?>" class="btn btn-primary" type="button"><?php echo lang('Cancel')?></a>
                                    <?php if(isset($enquire->mail)):?>
                                    <a href="mailto:<?php echo $enquire->mail?>?subject=<?php echo lang('Reply on question for real estate')?>: <?php echo $all_estates[$enquire->property_id]?>&amp;body=<?php echo $enquire->message?>" class="btn btn-default" target="_blank"><?php echo lang('Reply to email')?></a>
                                    <?php endif;?>
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