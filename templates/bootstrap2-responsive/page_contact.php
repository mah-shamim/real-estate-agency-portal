<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>    
    <script>
    $(document).ready(function(){

        <?php if(config_db_item('map_version') =='open_street'):?>

        var contact_map;
        if($('#contactMap').length){
            contact_map = L.map('contactMap', {
                center: [{settings_gps}],
                zoom: 12,
                scrollWheelZoom: scrollWheelEnabled,
            });     
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(contact_map);
            var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(contact_map);
            var property_marker = L.marker(
                [{settings_gps}],
                {icon: L.divIcon({
                        html: '<img src="assets/img/marker_blue.png">',
                        className: 'open_steet_map_marker',
                        iconSize: [19, 34],
                        popupAnchor: [-5, -45],
                        iconAnchor: [15, 45],
                    })
                }
            ).addTo(contact_map);
        
            property_marker.bindPopup("{settings_address},<br />{lang_GPS}: {settings_gps}");
        }

        <?php else:?>
        $("#contactMap").gmap3({
         map:{
            options:{
             center: [{settings_gps}],
             zoom: 12,
             scrollwheel: scrollWheelEnabled
            }
         },
         marker:{
            values:[
              {latLng:[{settings_gps}], options:{icon: "assets/img/marker_blue.png"}, data:"{settings_address},<br />{lang_GPS}: {settings_gps}"}
            ],
            
        options:{
          draggable: false
        },
        events:{
          mouseover: function(marker, event, context){
            var map = $(this).gmap3("get"),
              infowindow = $(this).gmap3({get:{name:"infowindow"}});
            if (infowindow){
              infowindow.open(map, marker);
              infowindow.setContent('<div style="width:400px;display:inline;">'+context.data+'</div>');
            } else {
              $(this).gmap3({
                infowindow:{
                  anchor:marker,
                  options:{disableAutoPan: mapDisableAutoPan, content: '<div style="width:400px;display:inline;">'+context.data+'</div>'}
                }
              });
            }
          },
          mouseout: function(){
            //var infowindow = $(this).gmap3({get:{name:"infowindow"}});
            //if (infowindow){
            //  infowindow.close();
            //}
          }
        }}});
        <?php endif;?>
    });
    
    </script>
  </head>

  <body>
  
{template_header}

<?php _subtemplate('headers', _ch($subtemplate_header, 'empty')); ?>

<a id="content"></a>
<div class="wrap-content">
    <div class="container">
        <h2>{page_title}</h2>
        <div class="property_content">
        {page_body}
        
        {has_settings_gps}
        <h2>{lang_Locationonmap}</h2>
        <div id="contactMap">
        </div>
        {/has_settings_gps}
        
        {has_settings_email}
        <h2 id="form">{lang_Contactform}</h2>
        <div id="contactForm"  class="contact-form">
        {validation_errors}
        {form_sent_message}
        <form method="post" action="{page_current_url}#form">
            
            <!-- The form name must be set so the tags identify it -->
            <input type="hidden" name="form" value="contact" />

                    <div class="row-fluid">
                    <div class="span5">
                        <div class="control-group {form_error_firstname}">
                            <div class="controls">
                                <div class="input-prepend input-block-level">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input class="input-block-level" id="firstname" name="firstname" type="text" placeholder="{lang_FirstLast}" value="{form_value_firstname}" />
                                </div>
                            </div>
                        </div>
                        <div class="control-group {form_error_email}">
                            <div class="controls">
                                <div class="input-prepend input-block-level">
                                    <span class="add-on"><i class="icon-envelope"></i></span>
                                    <input class="input-block-level" id="email" name="email" type="text" placeholder="{lang_Email}" value="{form_value_email}" />
                                </div>
                            </div>
                        </div>
                        <div class="control-group {form_error_phone}">
                            <div class="controls">
                                <div class="input-prepend input-block-level">
                                    <span class="add-on"><i class="icon-phone"></i></span>
                                    <input class="input-block-level" id="phone" name="phone" type="text" placeholder="{lang_Phone}" value="{form_value_phone}" />
                                </div>
                            </div>
                        </div>
                        <?php if(config_item('captcha_disabled') === FALSE ): ?>
                        <div class="control-group" >
                            <?php echo $captcha['image']; ?>
                            <input class="captcha" name="captcha" type="text" placeholder="{lang_Captcha}" value="" />
                            <input class="hidden" name="captcha_hash" type="text" value="<?php echo $captcha_hash; ?>" />
                        </div>
                        <?php endif; ?>
                        
                        <?php if(config_item('recaptcha_site_key') !== FALSE): ?>
                            <div class="control-group" >
                                <label class="control-label captcha"></label>
                                <div class="controls">
                                    <?php _recaptcha(true); ?>
                               </div>
                            </div>
                        <?php endif; ?>
                        <?php if(config_db_item('terms_link') !== FALSE || (config_db_item('privacy_link') !== FALSE && sw_count($not_logged)>0)): ?>
                            <button class="btn btn-info" type="submit" style="margin-top: 10px;">{lang_Send}</button>
                        <?php endif; ?>
                    </div>
                    <div class="span-mini"></div>
                    <div class="span6">
                        <div class="control-group {form_error_message}">
                            <div class="controls">
                                <textarea id="message" name="message" rows="4" class="input-block-level" type="text" placeholder="{lang_Message}">{form_value_message}</textarea>
                            </div>
                        </div>
                                                        
                        <?php if(config_db_item('terms_link') !== FALSE || (config_db_item('privacy_link') !== FALSE && sw_count($not_logged)>0)): ?>
                            <?php if(config_db_item('terms_link') !== FALSE ): ?>
                            <?php
                                $site_url = site_url();
                                $urlparts = parse_url($site_url);
                                $basic_domain = $urlparts['host'];
                                $terms_url = config_db_item('terms_link');
                                $urlparts = parse_url($terms_url);
                                $terms_domain ='';
                                if(isset($urlparts['host']))
                                    $terms_domain = $urlparts['host'];

                                if($terms_domain == $basic_domain) {
                                    $terms_url = str_replace('en', $lang_code, $terms_url);
                                }
                            ?>
                            <div class="control-group">
                                <div class="controls text-right">
                                    <a target="_blank" href="<?php echo $terms_url; ?>"><?php echo lang_check('I accept the Terms and Conditions'); ?></a>
                                    <input type="checkbox" value="1" name="terms" required="required"  style="margin: 0;"> 
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(config_db_item('privacy_link') !== FALSE && sw_count($not_logged)>0): ?>
                            <?php

                                $site_url = site_url();
                                $urlparts = parse_url($site_url);
                                $basic_domain = $urlparts['host'];
                                $privacy_url = config_db_item('privacy_link');
                                $urlparts = parse_url($privacy_url);
                                $privacy_domain ='';
                                if(isset($urlparts['host']))
                                    $privacy_domain = $urlparts['host'];

                                if($privacy_domain == $basic_domain) {
                                    $privacy_url = str_replace('en', $lang_code, $privacy_url);
                                }
                            ?>
                            <div class="control-group">
                                <div class="controls text-right">
                                    <a target="_blank" href="<?php echo $privacy_url; ?>"><?php echo lang_check('I accept the Privacy'); ?></a>
                                    <input type="checkbox" value="1" name="terms" required="required"  style="margin: 0;"> 
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-info pull-right" type="submit">{lang_Send}</button>
                        <?php endif; ?>
                    </div>
                    </div>
		</form>
        </div>
        {/has_settings_email}
        
       <?php _widget('center_imagegallery');?>
        
        {has_page_documents}
        <h2>{lang_Filerepository}</h2>
        <ul>
        {page_documents}
        <li>
            <a href="{url}">{filename}</a>
        </li>
        {/page_documents}
        </ul>
        {/has_page_documents}
        </div>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>