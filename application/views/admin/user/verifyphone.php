<!-- Form area -->
<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="widget wred">
              <!-- Widget head -->
              <div class="widget-head">
                <i class="icon-lock"></i> <?php echo lang_check('Verify your phone number')?> 
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
                <?php if($is_logged && $this->session->flashdata('message')!=lang_check('Thank you, phone number verified!')): ?>
                  <!-- Login form -->
                  <?php echo form_open(NULL, array('class' => 'form-horizontal'))?>
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang_check('Your phone number')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_input('phone', set_value('phone', $this->data['user']->phone), 'class="form-control" id="inputPassword" placeholder="'.lang_check('Your phone number').'" autocomplete="new-password"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-4 control-label"><?php echo lang_check('Your verification code')?></label>
                                  <div class="col-lg-8">
                                    <?php echo form_input('code', set_value('code', ''), 'class="form-control" id="inputPassword" placeholder="'.lang_check('Your verification code').'" autocomplete="new-password"')?>
                                  </div>
                                </div>
                                
                    <div class="col-lg-8 col-lg-offset-4">
						<button type="submit" class="btn btn-danger"><?php echo lang_check('Send new verification message')?></button>
                        <div style="clear: both; padding-top: 3px;"> </div>
                        <button type="submit" class="btn btn-info"><?php echo lang_check('Confirm your verification code')?></button>
                        <br style="clear: both;" />
					</div>
                    <br style="clear: both;" />
                  <?php echo form_close()?>
			    <?php endif; ?>
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