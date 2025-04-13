<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Reported')?>
      <!-- page meta -->
          <span class="page-meta"><?php echo empty($report->id) ? lang('Add a new report') : lang('Edit report').' "' . $report->name.'"'?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/reports')?>"><?php echo lang_check('Reports')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">
              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Report data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Listing ID')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('property_id', set_value('property_id', $report->property_id), 'class="form-control" id="inputproperty_id" placeholder="'.lang_check('Listing ID').'"')?>
                                    <?php //echo form_dropdown('property_id', $all_estates, set_value('property_id', $report->property_id), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('User ID')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('agent_id', set_value('agent_id', $report->agent_id), 'class="form-control" id="inputagent_id" placeholder="'.lang_check('User ID').'"')?>
                                    <?php //echo form_dropdown('agent_id', $all_users, set_value('agent_id', $report->agent_id), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Name')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('name', set_value('name', $report->name), 'class="form-control" id="inputNamee" placeholder="'.lang('Name').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Surname')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('surname', set_value('surname', $report->surname), 'class="form-control" id="inputNameSurname" placeholder="'.lang_check('Surname').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Phone')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('phone', set_value('phone', $report->phone), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Mail')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('email', set_value('email', $report->email), 'class="form-control" id="inputMail" placeholder="'.lang('Mail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Submit Date')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker1">
                                    <?php echo form_input('date_submit', set_value('date_submit', $report->date_submit), 'class="picker" data-format="yyyy-MM-dd"')?>
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
                                    <?php echo form_textarea('message', set_value('message', $report->message), 'placeholder="'.lang('Message').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>    

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('allow_contact')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('allow_contact','1', set_value('allow_contact', $report->allow_contact), 'placeholder="'.lang('allow_contact').'" rows="3" class=""')?>
                                  </div>
                                </div>
                                <?php if(isset($report->repository_id)): ?>
                                <?php
                                $file_rep = $this->file_m->get_by(array('repository_id'=>$report->repository_id));
                                if(sw_count($file_rep)) :
                                ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Files:')?></label>
                                  <div class="col-lg-10">
                                      <?php
                                            echo '<ul style="padding-left: 15px;">';
                                            //Fetch repository
                                            foreach($file_rep as $file_r)
                                            {
                                                $delete_url = site_url_q('files/upload/rep_'.$file_r->repository_id, '_method=DELETE&amp;file='.rawurlencode($file_r->filename));

                                                echo "<li><a target=\"_blank\" href=\"".base_url('files/'.$file_r->filename)."\">$file_r->filename</a>".
                                                     '</li>';
                                            }
                                            echo '</ul>';
                                      
                                      ?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                                <hr />
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-success"')?>
                                    <a href="<?php echo site_url('admin/reports')?>" class="btn btn-primary" type="button"><?php echo lang('Cancel')?></a>
                                    <?php if(isset($report->email)):?>
                                    <a href="mailto:<?php echo $report->email?>?subject=<?php echo lang_check('Reply on report for real estate')?>: <?php echo _ch($all_estates[$report->property_id]);?>&amp;body=<?php echo $report->message?>" class="btn btn-default" target="_blank"><?php echo lang('Reply to email')?></a>
                                    <?php endif;?>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
              </div>  

            </div>
          </div>
        </div>
        </div>