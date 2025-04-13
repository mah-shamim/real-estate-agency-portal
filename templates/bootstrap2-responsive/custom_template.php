<!DOCTYPE html>
<html lang="{lang_code}">
<head>
    <?php _widget('head');?>
    <script>
    $(document).ready(function(){


    });
    </script>
    
</head>
<body>
    <div class="always-top">
    <?php $c_code = config_item('codecanyon_code'); if(empty($c_code)): ?>
    <div class="top-wrapper">
          <div class="container">
                Please insert:
                <pre>$config['codecanyon_username'] = 'your_codecanyon_username';<br />$config['codecanyon_code'] = 'your_purchase_code';</pre>
                Into your application/config/cms_config.php to remove this message.<br />
                <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-"><em>Where to find purchase code?</em></a>
                <br /><br />
          </div> <!-- /.container -->
    </div>
    <?php endif; ?>

    <?php if(config_item('cookie_warning_enabled') === TRUE): ?>

    <script>

    function displayNotification(c_action) {

        // this sets the page background to semi-transparent black should work with all browsers
        var message = "<div id='cookiewarning' >";

        // center vert
        message = message + "<div style='text-align:center;margin:0px;padding:10px;width:auto;background:white;color:black;font-size:90%;'>";

        // this is the message displayed to the user.
        message = message + "<?php _l('cookie_warning_message'); ?>";

        // Displays the I agree/disagree buttons.
        // Feel free to change the address of the I disagree redirection to either a non-cookie site or a Google or the ICO web site 
        message = message + "<br /><INPUT TYPE='button' VALUE='<?php _l('I Agree'); ?>' onClick='JavaScript:doAccept();' /> <INPUT TYPE='button' VALUE=\"<?php _l('I dont agree'); ?>\" onClick='JavaScript:doNotAccept("
            message = message + c_action;
            message = message + ");' />";

        // and this closes everything off.
        message = message + "</div></div>";

        document.writeln(message);
    }
    </script>

    <div class="top-wrapper">
          <div class="container">
                <script src="assets/js/cookiewarning4.js"></script>
          </div> <!-- /.container -->
    </div>
    <?php endif; ?>
    <?php _widget('custom_palette'); ?>

    <?php 
        foreach($widgets_order->header as $widget_filename){
            _widget($widget_filename);
        } 
    ?>
</div>
    <?php 
        $conflict_widgets = array('top_mapsearch'=>1, 
                                  'top_mapsearch2'=>1, 
                                  'top_searchonly'=>1,
                                  'top_slider'=>1,
                                  'top_slidersearch'=>1,
                                  'top_slidersearchcenter'=>1
                                  );
        $conflict_active=false;

        foreach($widgets_order->top as $widget_filename){
            if(!$conflict_active || !isset($conflict_widgets[$widget_filename]))
                _widget($widget_filename);

            if(isset($conflict_widgets[$widget_filename]))
            {
                $conflict_active=true;
            }
        } 
    ?>
    <a id="content"></a>
    <div class="wrap-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span9">
                <?php 
                    foreach($widgets_order->center as $widget_filename){
                        _widget($widget_filename);
                    } 
                ?>
            </div>
            <div class="span3">
                <?php 
                 foreach($widgets_order->right as $widget_filename){
                     _widget($widget_filename);
                 } 
                ?>
            </div>
        </div>
    </div>
    </div>
    <?php 
        foreach($widgets_order->bottom as $widget_filename){
            _widget($widget_filename);
        } 
    ?>
    
    <div class="wrap-bottom">
    <div class="container">
      <div class="row-fluid">
          <?php 
                foreach($widgets_order->footer as $widget_filename){
                    _widget($widget_filename);
                } 
            ?>
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

    <?php _widget('custom_javascript');?> 
</body>
</html>