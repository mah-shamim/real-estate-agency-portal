<div class="wrap-bottom">
    <div class="container">
      <div class="row-fluid footer-row">
          <?php _widget('footer_logo');?>
          <?php _widget('footer_contactus');?>
          <?php _widget('footer_share');?>
      </div>
    </div>
    <!-- Generate time: <?php echo (microtime(true) - $time_start)?>, version: <?php echo APP_VERSION_REAL_ESTATE; ?> -->
</div>

<?php if(config_db_item('agent_masking_enabled') == TRUE): ?>
<!-- form itself -->
<form id="test-form" class="form-horizontal mfp-hide white-popup-block">
    <div id="popup-form-validation">
    <p class="hidden alert alert-error"><?php echo lang_check('Submit failed, please populate all fields!'); ?></p>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputAre"><?php echo lang_check('YouAre'); ?></label>
        <div class="controls">
            <label class="radio inline">
            <input type="radio" name="visitor_type" id="optionsRadios1" value="Individual" <?php echo $this->session->userdata('visitor_type')=='Individual'?'checked':''; ?>>
            <?php echo lang_check('Individual'); ?>
            </label>
            <label class="radio inline">
            <input type="radio" name="visitor_type" id="optionsRadios2" value="Dealer" <?php echo $this->session->userdata('visitor_type')=='Dealer'?'checked':''; ?>>
            <?php echo lang_check('Dealer'); ?>
            </label>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputName"><?php echo lang_check('YourName'); ?></label>
        <div class="controls">
            <input type="text" name="name" id="inputName" value="<?php echo $this->session->userdata('name'); ?>" placeholder="<?php echo lang_check('YourName'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPhone"><?php echo lang_check('Phone'); ?></label>
        <div class="controls">
            <input type="text" name="phone" id="inputPhone" value="<?php echo $this->session->userdata('phone'); ?>" placeholder="<?php echo lang_check('Phone'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail"><?php echo lang_check('Email'); ?></label>
        <div class="controls">
            <input type="text" name="email" id="inputEmail" value="<?php echo $this->session->userdata('email'); ?>" placeholder="<?php echo lang_check('Email'); ?>">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <input name="allow_contact" value="1" type="checkbox"> <?php echo lang_check('I allow agent and affilities to contact me'); ?>
            </label>
            <button id="unhide-agent-mask" type="button" class="btn"><?php echo lang_check('Submit'); ?></button>
            <img id="ajax-indicator-masking" src="assets/img/ajax-loader.gif" style="display: none;" alt="" />
        </div>
    </div>
</form>
<?php endif; ?>



<?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/jquery-contact-tabs/js/jquery.contact.tabs.1.0.js')): ?>
<div id="contact-tabs"></div>
<?php endif; ?>

