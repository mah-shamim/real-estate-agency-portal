<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Visits')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($listing->id) ? lang_check('Add a new visits') : lang_check('Edit visits').' "' . $listing->id.'"'?></span>
        </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/visits')?>"><?php echo lang_check('Visits')?></a>
    </div>
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="widget wgreen">
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Visit data')?></div>
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
                    <?php if($this->session->flashdata('message')):?>
                    <p class="label label-success validation"><?php echo $this->session->flashdata('message')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Estate')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown_ajax('property_id', 'estate_m', set_value('property_id', $listing->property_id), 'address', $content_language_id);?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Client')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown_ajax('client_id', 'user_m', set_value('agent_id', $listing->client_id), 'username');?>
                                  </div>
                                </div>
                                <?php if(!empty($listing->date_visit)):?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date of visit')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('date_visit', set_value('date_visit', $listing->date_visit), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(!empty($listing->date_created)):?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date of created')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('date_created', set_value('date_created', $listing->date_created), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(!empty($listing->date_confirmed)):?>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date of confirmed')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('date_confirmed', set_value('date_confirmed', $listing->date_confirmed), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(!empty($listing->date_canceled)):?>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date of canceled')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('date_canceled', set_value('date_canceled', $listing->date_canceled), 'class="form-control" id="inputNameSurname" readonly disabled')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Message')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('message', set_value('message', $listing->message), 'placeholder="'.lang_check('Message').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>    

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Confirmed')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('confirmed', '1', set_value('confirmed', $listing->confirmed), 'id="inputConfirmed"')?>
                                  </div>
                                </div>
                                
                                <hr />
                                
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                      <?php echo form_submit('submit', lang_check('Save'), 'class="btn btn-success"')?>
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