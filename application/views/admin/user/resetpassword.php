<!-- Form area -->
<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="widget wred">
              <!-- Widget head -->
              <div class="widget-head">
                <i class="icon-lock"></i> <?php echo lang_check('Reset your password')?> 
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
                                
                    <div class="col-lg-8 col-lg-offset-4">
						<button type="submit" class="btn btn-danger"><?php echo lang('Reset password')?></button>
					</div>
                    <br />
                  <?php echo form_close()?>
				  
				</div>
                </div>
              
                <div class="widget-foot">
                  <a href="<?php echo site_url('admin/user/login')?>"><?php echo lang('Login here')?></a>
                </div>
            </div>  
      </div>
    </div>
  </div> 
</div>