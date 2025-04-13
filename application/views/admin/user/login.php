<!-- Form area -->
<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="widget worange">
              <!-- Widget head -->
              <div class="widget-head">
                <i class="icon-lock"></i> <?php echo lang('Login')?> 
              </div>

              <div class="widget-content">
                <div class="padd">
                
                <?php echo validation_errors()?>
                <?php if($this->session->flashdata('error')):?>
                <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                <?php endif;?>
                
                  <!-- Login form -->
                  <?php echo form_open(NULL, array('class' => 'form-horizontal'))?>
                    <!-- Email -->
                    <div class="form-group">
                      <label class="control-label col-lg-4" for="inputUsername"><?php echo lang('Username')?></label>
                      <div class="col-lg-8">
                        <?php echo form_input('username', NULL, 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
                      </div>
                    </div>
                    <!-- Password -->
                    <div class="form-group">
                      <label class="control-label col-lg-4" for="inputPassword"><?php echo lang('Password')?></label>
                      <div class="col-lg-8">
                        <?php echo form_password('password', NULL, 'class="form-control" id="inputPassword" placeholder="'.lang('Password').'"')?>
                      </div>
                    </div>
                    <!-- Remember me checkbox and sign in button -->
                    <div class="form-group">
					<div class="col-lg-8 col-lg-offset-4">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="remember" id="remember" value="true" /> <?php echo lang('Remember me')?>
                        </label>
						</div>
					</div>
					</div>
                        <div class="col-lg-8 col-lg-offset-4">
							<button type="submit" class="btn btn-danger"><?php echo lang('Sign in')?></button>
							<button type="reset" class="btn btn-default"><?php echo lang('Reset')?></button>
						</div>
                    <br />
                  <?php echo form_close()?>
				  
                <?php if(config_item('app_type') == 'demo'):?>
                <p class="label label-info validation"><?php echo lang_check('Admin creditionals: admin, admin')?></p>
                <p class="label label-info validation"><?php echo lang_check('Agent creditionals: agent, agent')?></p>
                <?php endif;?>
                  
				</div>
                </div>
              
                <div class="widget-foot">
                  <a href="<?php echo site_url('admin/user/register')?>"><?php echo lang('Not Registred?')?></a>
                  <a href="<?php echo site_url('admin/user/forgetpassword')?>"><?php echo lang_check('Forget password?')?></a>
                  <?php if(config_db_item('frontend_disabled') === FALSE): ?>
                  <span class="pull-right"><a href="<?php echo site_url('')?>"><?php echo lang_check('Back to homepage')?></a></span>
                  <?php endif; ?>
                </div>
            </div>  
      </div>
    </div>
  </div> 
</div>