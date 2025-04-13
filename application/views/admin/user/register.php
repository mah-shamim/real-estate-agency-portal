<!-- Form area -->
<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="widget wred">
              <!-- Widget head -->
              <div class="widget-head">
                <i class="icon-lock"></i> <?php echo lang_check('Register')?> 
              </div>

              <div class="widget-content">
                <div class="padd">
                <?php echo validation_errors()?>
                <?php if($this->session->flashdata('error')):?>
                <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                <?php endif;?>
                
                  <!-- Login form -->
                  <?php echo form_open(NULL, array('class' => 'form-horizontal'))?>
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang('Name and surname')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_input('name_surname', set_value('name_surname', $user->name_surname), 'class="form-control" id="inputNameSurname" placeholder="'.lang('Name and surname').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang('Username')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_input('username', set_value('username', $user->username), 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label">Password</label>
                                  <div class="col-lg-8">
                                    <?php echo form_password('password', set_value('password', ''), 'class="form-control" id="inputPassword" placeholder="'.lang('Password').'" autocomplete="new-password"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label">Confirm Password</label>
                                  <div class="col-lg-8">
                                    <?php echo form_password('password_confirm', set_value('password_confirm', ''), 'class="form-control" id="inputPasswordConfirm" placeholder="'.lang('PasswordConfirm').'" autocomplete="new-password"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang('Address')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_textarea('address', set_value('address', $user->address), 'placeholder="'.lang('Address').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>          
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang('Phone')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_input('phone', set_value('phone', $user->phone), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang('Mail')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_input('mail', set_value('mail', $user->mail), 'class="form-control" id="inputMail" placeholder="'.lang('Mail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang('Language')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_dropdown('language', $this->language_m->backend_languages, set_value('language', 'english'), 'class="form-control"');?>
                                  </div>
                                </div>
                            <?php if(config_item('recaptcha_site_key') !== FALSE): ?>
                            <div class="form-group" >
                                <label class="col-lg-4 control-label"></label>
                                <div class="col-lg-8">
                                    <?php _recaptcha(true); ?>
                               </div>
                            </div>
                            <?php endif; ?>  
                                
                    <div class="col-lg-8 col-lg-offset-4">
						<button type="submit" class="btn btn-danger"><?php echo lang('Register')?></button>
						<button type="reset" class="btn btn-success"><?php echo lang('Reset')?></button>
					</div>
                    <br />
                  <?php echo form_close()?>
				  
				</div>
                </div>
              
                <div class="widget-foot">
                  <?php echo lang('Already Registred?')?> <a href="<?php echo site_url('admin/user/login')?>"><?php echo lang('Login here')?></a>
                </div>
            </div>  
      </div>
    </div>
  </div> 
</div>