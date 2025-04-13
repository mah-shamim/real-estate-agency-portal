<!-- Form area -->
<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="widget wred">
              <!-- Widget head -->
              <div class="widget-head">
                <i class="icon-lock"></i> <?php echo lang_check('Forget password?')?> 
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
                                  <label class="col-lg-4 control-label"><?php echo lang('Mail')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_input('mail', set_value('mail', ''), 'class="form-control" id="inputMail" placeholder="'.lang('Mail').'"')?>
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
                  <a href="<?php echo site_url('frontend/login')?>"><?php echo lang('Login here')?></a>
                </div>
            </div>  
      </div>
    </div>
  </div> 
</div>