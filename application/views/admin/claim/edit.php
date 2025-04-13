<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Claimed')?>
      <!-- page meta -->
          <span class="page-meta"><?php echo empty($claim->id) ? lang('Add a new claim') : lang('Edit claim').' "' . $claim->name.'"'?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/claim')?>"><?php echo lang_check('Claim')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">
              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Claim data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Model')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('model', array('user_m' => 'user_m'), set_value('model', $claim->model), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('model_id')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('model_id', set_value('model_id', $claim->model_id), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Name')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('name', set_value('name', $claim->name), 'class="form-control" id="inputNamee" placeholder="'.lang('Name').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Surname')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('surname', set_value('surname', $claim->surname), 'class="form-control" id="inputNameSurname" placeholder="'.lang_check('Surname').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Phone')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('phone', set_value('phone', $claim->phone), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Mail')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('email', set_value('email', $claim->email), 'class="form-control" id="inputMail" placeholder="'.lang('Mail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Submit Date')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker1">
                                    <?php echo form_input('date_submit', set_value('date_submit', $claim->date_submit), 'class="picker" data-format="yyyy-MM-dd"')?>
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
                                    <?php echo form_textarea('message', set_value('message', $claim->message), 'placeholder="'.lang('Message').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>    

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('allow_contact')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('allow_contact','1', set_value('allow_contact', $claim->allow_contact), 'placeholder="'.lang('allow_contact').'" rows="3" class=""')?>
                                  </div>
                                </div>
                                <?php if(isset($claim->repository_id)): ?>
                                <?php
                                $file_rep = $this->file_m->get_by(array('repository_id'=>$claim->repository_id));
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
                                    <a href="<?php echo site_url('admin/claim')?>" class="btn btn-primary" type="button"><?php echo lang('Cancel')?></a>
                                    <?php if(isset($claim->email)):?>
                                    <a href="mailto:<?php echo $claim->email?>?subject=<?php echo lang_check('Reply on claim for real estate')?>:&amp;body=<?php echo $claim->message?>" class="btn btn-default" target="_blank"><?php echo lang('Reply to email')?></a>
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