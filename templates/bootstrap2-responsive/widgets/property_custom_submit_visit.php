<?php
/* Popup form 
 * lib 
 *  css: assets/js/magnific-popup/magnific-popup.css
 *  js: assets/js/magnific-popup/jquery.magnific-popup.js
 *  link: https://plugins.jquery.com/magnific-popup/ ???
 * 
 */

?>
<?php if(file_exists(APPPATH.'controllers/admin/visits.php') && isset($property_id) && empty($not_logged)): ?>

    <?php if(!is_array($this->session->userdata('submit_visit')) || !in_array($property_id, $this->session->userdata('submit_visit'))): ?>
        <a class="btn btn-info popup-with-form-report" style="display:inline-block;margin-left: 5px" id="submit_visit" href="#popup_submit_visit"><i class="icon-flag icon-white"></i> <?php echo lang_check('Submit visit'); ?> <i class="load-indicator"></i></a>
    <?php endif; ?>    

<!-- form itself -->
<form id="popup_submit_visit" class="form-horizontal mfp-hide white-popup-block">
    <div id="popup-form-validation-report" class="alert-box">
    </div>
    <div class="control-group">
        <label class="control-label" for="inputDate"><?php echo lang_check('Date and time'); ?></label>
        <div class="controls">
            <input id="inputDate" name="date_visit" type="text" id="inputDate" class="field_datepicker_time" />
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
            <button id="unhide-report-mask" type="button" class="btn"><?php echo lang_check('Submit'); ?></button>
            <img id="ajax-indicator-masking" src="assets/img/ajax-loader.gif" style="display: none;" alt="" />
        </div>
    </div>
</form>

<script>
    // transfer to down page
    $('document').ready(function(){
        $('body').append($('#popup_submit_visit').detach());
    })
     
    $('document').ready(function(){
            // Popup form Start //
                $('#submit_visit.popup-with-form-report').magnificPopup({
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
                                        $('#popup_submit_visit #popup-form-validation-report').html('');
                                        $('#popup_submit_visit input, #popup_submit_visit textarea').val('');
                		}
                	}
                });
                
                
                $('#popup_submit_visit #unhide-report-mask').click(function(){
                    
                    var data = $('#popup_submit_visit').serializeArray();
                    data.push({name: 'property_id', value: "<?php echo $property_id; ?>"});
                    
                    //console.log( data );
                    $('#popup_submit_visit #ajax-indicator-masking').css('display', 'inline');
                    
                    // send info to agent
                    $.post("<?php echo site_url('api/submit_visit/'.$property_id.'/'.$lang_code); ?>", data,
                    function(data){
                        if(data.success)
                        {
                            // Display agent details
                            $('#popup_submit_visit input, #popup_submit_visit textarea').val('');
                            $('#popup_submit_visit #popup-form-validation-report').html('');
                            // Close popup
                            $.magnificPopup.instance.close();
                            ShowStatus.show('<?php echo lang_check('Visit submited');?>')
                        }
                        else
                        {
                            $('.alert.hidden').css('display', 'block');
                            $('.alert.hidden').css('visibility', 'visible');
                            
                            $('#popup_submit_visit #popup-form-validation-report').html(data.message);
                            
                            //console.log("Data Loaded: " + data);
                        }
                        $('#popup_submit_visit #ajax-indicator-masking').css('display', 'none');
                    });

                    return false;
                });
                
            // Popup form End //     
    })
</script>
<?php endif; ?>

