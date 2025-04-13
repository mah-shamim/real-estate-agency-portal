<?php
/* Popup form 
 * lib 
 *  css: assets/js/magnific-popup/magnific-popup.css
 *  js: assets/js/magnific-popup/jquery.magnific-popup.js
 *  link: https://plugins.jquery.com/magnific-popup/ ???
 * 
 */

?>
<?php if(config_item('report_property_enabled') == TRUE && isset($property_id)): ?>

    <?php if(!is_array($this->session->userdata('reported')) || !in_array($property_id, $this->session->userdata('reported'))): ?>
        <a class="btn btn-info popup-with-form-report" style="display:inline-block;margin-left: 5px" id="report_property" href="#popup_report_property"><i class="icon-flag icon-white"></i> <?php echo lang_check('Report property'); ?> <i class="load-indicator"></i></a>
    <?php endif; ?>    

<!-- form itself -->
<form id="popup_report_property" class="form-horizontal mfp-hide white-popup-block">
    <div id="popup-form-validation-report">
    <p class="hidden alert alert-error"><?php echo lang_check('Submit failed, please populate all fields!'); ?></p>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputrepName"><?php echo lang_check('YourName'); ?></label>
        <div class="controls">
            <input type="text" name="name" id="inputrepName" value="<?php echo $this->session->userdata('name'); ?>" placeholder="<?php echo lang_check('YourName'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputrepPhone"><?php echo lang_check('Phone'); ?></label>
        <div class="controls">
            <input type="text" name="phone" id="inputrepPhone" value="<?php echo $this->session->userdata('phone'); ?>" placeholder="<?php echo lang_check('Phone'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputrepEmail"><?php echo lang_check('Email'); ?></label>
        <div class="controls">
            <input type="text" name="email" id="inputrepEmail" value="<?php echo $this->session->userdata('email'); ?>" placeholder="<?php echo lang_check('Email'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="message"><?php echo lang_check('Message'); ?></label>
        <div class="controls">
            <textarea name="message" id="message"><?php echo $this->session->userdata('message'); ?></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <input name="allow_contact" value="1" type="checkbox"> <?php echo lang_check('I allow agent and affilities to contact me'); ?>
            </label>
            <button id="unhide-report-mask" type="button" class="btn"><?php echo lang_check('Submit'); ?></button>
            <img class="ajax-indicator-masking" src="assets/img/ajax-loader.gif" style="display: none;" alt="" />
        </div>
    </div>
</form>

<script>
    // transfer to down page
    $('document').ready(function(){
      $('body').append($('#popup_report_property').detach());
    })
     
    $('document').ready(function(){
            // Popup form Start //
                $('#report_property.popup-with-form-report').magnificPopup({
                	type: 'inline',
                	preloader: false,
                	focus: '#name',
                                    
                	// When elemened is focused, some mobile browsers in some cases zoom in
                	// It looks not nice, so we disable it:
                	callbacks: {
                		beforeOpen: function() {
                			if($(window).width() < 700) {
                				this.st.focus = false;
                			} else {
                				this.st.focus = '#name';
                			}
                		}
                	}
                });
                
                
                $('#popup_report_property #unhide-report-mask').click(function(){
                    
                    var data = $('#popup_report_property').serializeArray();
                    data.push({name: 'property_id', value: "<?php echo $property_id; ?>"});
                    data.push({name: 'agent_id', value: "<?php echo _ch($agent_id, ''); ?>"});
                    
                    //console.log( data );
                    $('#popup_report_property .ajax-indicator-masking').css('display', 'inline');
                    
                    // send info to agent
                    $.post("<?php echo site_url('frontend/reportsubmit/'.$lang_code); ?>", data,
                    function(data){
                        if(data=='successfully')
                        {
                            // Display agent details
                            $('#report_property.popup-with-form-report').css('display', 'none');
                            // Close popup
                            $.magnificPopup.instance.close();
                            ShowStatus.show('<?php echo lang_check('Report send');?>')
                        }
                        else
                        {
                            $('.alert.hidden').css('display', 'block');
                            $('.alert.hidden').css('visibility', 'visible');
                            
                            $('#popup_report_property #popup-form-validation-report').html(data);
                            
                            //console.log("Data Loaded: " + data);
                        }
                        $('#popup_report_property .ajax-indicator-masking').css('display', 'none');
                    });

                    return false;
                });
                
            // Popup form End //     
    })
</script>
<?php endif; ?>

