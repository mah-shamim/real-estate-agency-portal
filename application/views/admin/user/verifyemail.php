<!-- Form area -->
<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="widget wred">
              <!-- Widget head -->
              <div class="widget-head">
                <i class="icon-lock"></i> <?php echo lang_check('Verify your email')?> 
              </div>

              <div class="widget-content">
                <div class="padd">
                <?php if(isset($message)):?>
                <?php echo $message; ?>
                <?php endif;?>
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