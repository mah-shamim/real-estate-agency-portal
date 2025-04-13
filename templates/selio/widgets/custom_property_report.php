<?php
/* Popup form 
 * lib 
 *  css: assets/libraries/magnific-popup/magnific-popup.css
 *  js: assets/libraries/magnific-popup/jquery.magnific-popup.js
 *  link: https://plugins.jquery.com/magnific-popup/ ???
 * 
 */

?>
<?php if(config_item('report_property_enabled') == TRUE && isset($property_id)): ?>

    <?php if(!is_array($this->session->userdata('reported')) || !in_array($property_id, $this->session->userdata('reported'))): ?>
        <a class="btn2 btn-custom-default popup-with-form-report" id="report_property" href="#popup_report_property" style=""><i class="icon-flag"></i> <?php echo lang_check('Claim Listing'); ?> <i class="load-indicator"></i></a>
        <a class="btn2 btn-custom-default report-claimed hidden" id="report_claimed_property" href="#"><i class="icon-flag"></i> <?php echo lang_check('Claimed'); ?> <i class="load-indicator"></i></a>
    <?php else: ?>    
        <a class="btn2 btn-custom-default report-claimed" id="report_claimed_property" href="#"><i class="icon-flag"></i> <?php echo lang_check('Claimed'); ?> <i class="load-indicator"></i></a>
    <?php endif; ?>    

<!-- form itself -->
<form id="popup_report_property" class="form-horizontal mfp-hide white-popup-block">
    <div id="main">
        <div id="popup-form-validation-report">
        <p class="hidden alert alert-error"><?php echo lang_check('Submit failed, please populate all fields!'); ?></p>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputName"><?php echo lang_check('YourName'); ?></label>
            <div class="controls">
                <input type="text" name="name" class="form-control" id="inputName" value="<?php echo $this->session->userdata('name'); ?>" placeholder="<?php echo lang_check('YourName'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputSurName"><?php echo lang_check('Surname'); ?></label>
            <div class="controls">
                <input type="text" name="surname" class="form-control" id="inputSurName" value="<?php echo $this->session->userdata('surname'); ?>" placeholder="<?php echo lang_check('Your Surname'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPhone"><?php echo lang_check('Phone'); ?></label>
            <div class="controls">
                <input type="text" name="phone" class="form-control" id="inputPhone" value="<?php echo $this->session->userdata('phone'); ?>" placeholder="<?php echo lang_check('Phone'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputEmail"><?php echo lang_check('Email'); ?></label>
            <div class="controls">
                <input type="text" name="email" class="form-control" id="inputEmail" value="<?php echo $this->session->userdata('email'); ?>" placeholder="<?php echo lang_check('Email'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputmessage"><?php echo lang_check('Message'); ?></label>
            <div class="controls">
                <textarea name="message" class="form-control" style='width: 220px;' id="inputmessage"><?php echo $this->session->userdata('message'); ?></textarea>
            </div>
        </div>
        
        <?php 
            $CI = &get_instance();
            $CI->load->model('repository_m');
            $repository_id = NULL;
            if(isset($_POST['repository_id']))
            {
                $repository_id = $CI->data['repository_id'] = $_POST['repository_id'];
            }
            else
            {
                // Create new repository
                $repository_id = $CI->repository_m->save(array('name'=>'file_m', 'is_activated'=>0));
                $this->data['repository_id'] = $repository_id;
            }
            ?>
            <div class="control-group UPLOAD-FIELD-TYPE" id="main">
            <div class="controls">
                <div class="field-row hidden">
                    <?php echo form_input('repository_id', $repository_id, 'class="form-control skip-input" id="repository_id" placeholder="repository_id"')?>
                </div>
                <?php if(empty($repository_id)): ?>
                <span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
                <br style="clear:both;" />
                <?php else: ?>
                <!-- Button to select & upload files -->
                <div class="row_upload">
                    <span class="btn btn-success fileinput-button">
                        <span><?php echo lang_check('Upload Document');?></span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input id="fileupload_<?php echo $repository_id; ?>" class="FILE_UPLOAD file_<?php echo $repository_id; ?>" type="file" name="files[]" multiple>
                    </span><br style="clear: both;" />
                    <!-- The global progress bar -->
                    <div class="fileupload-progress fade" id="progress_<?php echo $repository_id; ?>">
                        <!-- The global progress bar -->
                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="bar" style="width:0%;"></div>
                        </div>
                    </div>
                </div>
                <!-- The list of files uploaded -->
                <ul id="files_<?php echo $repository_id; ?>">
                    <?php 
                    if(isset($repository_id)){
                        //Fetch repository
                        $file_rep = $this->file_m->get_by(array('repository_id'=>$repository_id));
                        if(sw_count($file_rep)) foreach($file_rep as $file_r)
                        {
                            $delete_url = site_url_q('files/upload/rep_'.$file_r->repository_id, '_method=DELETE&amp;file='.rawurlencode($file_r->filename));

                            echo "<li><a target=\"_blank\" href=\"".base_url('files/'.$file_r->filename)."\">$file_r->filename</a>".
                                 '&nbsp;&nbsp;<button class="btn btn-xs btn-mini btn-danger" data-type="POST" data-url='.$delete_url.'><i class="icon-trash icon-white"></i></button></li>';
                        }
                    }
                    ?>
                </ul>

                <!-- JavaScript used to call the fileupload widget to upload files -->
                <script >
                // When the server is ready...
                $( document ).ready(function() {

                    // Define the url to send the image data to
                    var url_<?php echo $repository_id; ?> = '<?php echo site_url('files/upload_repository/'.$repository_id);?>';

                    // Call the fileupload widget and set some parameters
                    $('#fileupload_<?php echo $repository_id; ?>').fileupload({
                        url: url_<?php echo $repository_id; ?>,
                        autoUpload: true,
                        dropZone: $('#fileupload_<?php echo $repository_id; ?>'),
                        dataType: 'json',
                        done: function (e, data) {
                            // Add each uploaded file name to the #files list
                            var added=false;
                            $.each(data.result.files, function (index, file) {
                                if(!file.hasOwnProperty("error"))
                                {
                                    $('#files_<?php echo $repository_id; ?>').append('<li><a href="'+file.url+'" target="_blank">'+file.name+'</a>&nbsp;&nbsp;<button class="btn btn-xs btn-mini btn-danger" data-type="POST" data-url='+file.delete_url+'><i class="icon-trash icon-white"></i></button></li>');
                                    added=true;
                                }
                                else
                                {
                                    ShowStatus.show(file.error);
                                }
                            });
                            
                            var $progress = $('#progress_<?php echo $repository_id; ?>');
                            $progress.removeClass('in');
                            $progress.find('.bar').css('width', '0%');
                            
                            if(added)
                            {
                                $('<?php echo '#repository_id'; ?>').val(data.result.repository_id);
                                reset_events_<?php echo $repository_id; ?>();
                            }
                        },
                        progressall: function (e, data) {
                            // Update the progress bar while files are being uploaded
                            var $progress = $('#progress_<?php echo $repository_id; ?>');
                            $progress.addClass('in');
                            $progress.find('.bar').css('width', '0%');
                            
                            var progress = parseInt(data.loaded / data.total * 100, 10);
                            $progress.find('.bar').css(
                                'width',
                                progress + '%'
                            );
                        }
                    });

                    reset_events_<?php echo $repository_id; ?>();
                });

                function reset_events_<?php echo $repository_id; ?>(){
                    $("#files_<?php echo $repository_id; ?> li button").unbind();
                    $("#files_<?php echo $repository_id; ?> li button.btn-danger").click(function(){
                        var image_el = $(this);

                        $.post($(this).attr('data-url'), function( data ) {
                            var obj = jQuery.parseJSON(data);

                            if(obj.success)
                            {
                                image_el.parent().remove();
                            }
                            else
                            {
                                ShowStatus.show('<?php echo lang_check('Unsuccessful, possible permission problems or file not exists'); ?>');
                            }
                            //console.log("Data Loaded: " + obj.success );
                        });

                        return false;
                    });
                }

                </script>
                <?php endif;?>
           </div>
        </div>
        
            <?php if (config_item('captcha_disabled') === FALSE): ?>
                <div class="control-group {form_error_captcha}">
                    <div class="form_captcha">
                        <label class="control-label"><?php echo $captcha['image']; ?></label>
                        <div class="controls">
                            <input class="captcha  {form_error_captcha}" name="captcha" type="text" placeholder="<?php _l('Captcha'); ?>" value="" />
                            <input class="hidden" name="captcha_hash" type="text" value="<?php echo $captcha_hash; ?>" />
                        </div>
                    </div>
                </div>
            <?php endif; ?>
              <?php if(config_item('recaptcha_site_key') !== FALSE): ?>
              <div class="control-group" >
                  <div class="controls">
                      <?php _recaptcha(false); ?>
                 </div>
              </div>
        <?php endif; ?>
        
        <div class="control-group">
            <div class="controls">
                <div class="checkbox">
                    <div class="form-field input-field checkbox-field" >
                        <input name="allow_contact" class=""id="allow_contact" value="1" type="checkbox"> 
                        <label for="allow_contact">
                            <span></span>
                            <?php echo lang_check('I allow agent and affilities to contact me'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="control-group">
            <div class="controls">
                <button id="unhide-report-mask" type="button" class="btn"><?php echo lang_check('Submit'); ?></button>
                <img id="ajax-indicator-masking" alt="load" src="assets/img/ajax-loader.gif" style="display: none;" />
            </div>
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
                    $('#popup_report_property #ajax-indicator-masking').css('display', 'inline');
                    
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
                            $('.report-claimed').removeClass('hidden');
                        }
                        else
                        {
                            $('.alert.hidden').css('display', 'block');
                            $('.alert.hidden').css('visibility', 'visible');
                            
                            $('#popup_report_property #popup-form-validation-report').html(data);
                            
                            //console.log("Data Loaded: " + data);
                        }
                        $('#popup_report_property #ajax-indicator-masking').css('display', 'none');
                    });

                    return false;
                });
                
            // Popup form End //     
    })
</script>
<?php endif; ?>