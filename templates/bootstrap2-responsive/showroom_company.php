<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <script>
    $(document).ready(function(){
        
       $("#route_from_button").click(function () { 
            window.open("https://maps.google.hr/maps?saddr="+$("#route_from").val()+"&daddr={showroom_data_address}@{showroom_data_gps}&hl={lang_code}",'_blank');
            return false;
        });

        <?php if(config_db_item('map_version') =='open_street'):?>

        var property_map;
        if($('#propertyLocation').length){
            property_map = L.map('propertyLocation', {
                center: [{showroom_data_gps}],
                zoom: {settings_zoom},
                scrollWheelZoom: scrollWheelEnabled,
            });     
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(property_map);
            var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(property_map);
            var property_marker = L.marker(
                [{showroom_data_gps}],
                {icon: L.divIcon({
                        html: '<img src="assets/img/marker_blue.png">',
                        className: 'open_steet_map_marker',
                        iconSize: [19, 34],
                        popupAnchor: [-5, -45],
                        iconAnchor: [15, 45],
                    })
                }
            ).addTo(property_map);
        
            property_marker.bindPopup("{showroom_data_address}<br />{lang_GPS}: {showroom_data_gps}");
        }

        <?php else:?>

        $('#propertyLocation').gmap3({
         map:{
            options:{
             center: [{showroom_data_gps}],
             zoom: {settings_zoom},
             scrollwheel: scrollWheelEnabled
            }
         },
         marker:{
            values:[
                {latLng:[{showroom_data_gps}], options:{icon: "assets/img/marker_blue.png"}, data:"{showroom_data_address}<br />{lang_GPS}: {showroom_data_gps}"},
            ],
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
          }
        }
         }});
        
        $("#wrap-map").gmap3({
         map:{
            options:{
             <?php if(config_item('custom_map_center') === FALSE): ?>
             center: [{all_estates_center}],
             <?php else: ?>
             center: [<?php echo config_item('custom_map_center'); ?>],
             <?php endif; ?>
             zoom: {settings_zoom},
             scrollwheel: scrollWheelEnabled,
             mapTypeId: c_mapTypeId,
             mapTypeControlOptions: {
               mapTypeIds: c_mapTypeIds
             }
            }
         },
        styledmaptype:{
          id: "style1",
          options:{
            name: "<?php echo lang_check('CustomMap'); ?>"
          },
          styles: mapStyle
        },
         marker:{
            values:[
            {all_estates}
                {latLng:[{gps}], adr:"{address}", options:{icon: "{icon}"}, data:"<img style=\"width: 150px; height: 100px;\" src=\"{thumbnail_url}\" /><br />{address}<br />{option_2}<br /><span class=\"label label-info\">&nbsp;&nbsp;{option_4}&nbsp;&nbsp;</span><br /><a href=\"{url}\">{lang_Details}</a>"},
            {/all_estates}
            ],
            cluster: clusterConfig,
            options: markerOptions,
        events:{
          <?php echo map_event(); ?>: function(marker, event, context){
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
        init_gmap_searchbox();
    <?php endif;?>    
        
    });    
    </script>
  </head>

  <body>
  
{template_header}

<?php _widget('top_mapsearch');?>

<?php _widget('top_ads');?>

<div class="wrap-content" id="content-position">
    <div class="container container-property">
    <div class="row-fluid">
    <div class="span9">
        <h2>{page_title}</h2>
        <div class="property_content">
        <?php if(isset($showroom_image_url)): ?>
        <img class="showroom_logo" src="{showroom_image_url}" alt="" />
        <?php endif; ?>
        
        <?php _widget('center_defaultcontent');?>
        
        <br style="clear: both;" />
        <h2>{lang_Locationonmap}</h2>
        <div id="propertyLocation">
        </div>
        <div class="route_suggestion">
        <input id="route_from" class="inputtext w360" type="text" value="" placeholder="{lang_Typeaddress}" name="route_from" />
        <a id="route_from_button" href="#" class="btn">{lang_Suggestroutes}</a>
        </div>

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
            <div class="span3">
                  <h2>{lang_Overview}</h2>
                  <div class="property_options">
                    <p class="bottom-border"><strong>
                    {lang_Company}
                    </strong> <span>{page_title}</span>
                    <br style="clear: both;" />
                    </p>
                    <p class="bottom-border"><strong>
                    {lang_Address}
                    </strong> <span>{showroom_data_address}</span>
                    <br style="clear: both;" />
                    </p>
                    <p class="bottom-border"><strong>
                    {lang_Keywords}
                    </strong> <span>{page_keywords}</span>
                    <br style="clear: both;" />
                    </p>
                  </div>

                  <?php _widget('right_adssmall'); ?> 
                  <h2>{lang_Enquireform}</h2>
                  <div id="form" class="property-form">
                    {validation_errors}
                    {form_sent_message}
                    <form method="post" action="{page_current_url}#form">
                        <label>{lang_FirstLast}</label>
                        <input class="{form_error_firstname}" name="firstname" type="text" placeholder="{lang_FirstLast}" value="{form_value_firstname}" />
                        <label>{lang_Phone}</label>
                        <input class="{form_error_phone}" name="phone" type="text" placeholder="{lang_Phone}" value="{form_value_phone}" />
                        <label>{lang_Email}</label>
                        <input class="{form_error_email}" name="email" type="text" placeholder="{lang_Email}" value="{form_value_email}" />
                        <label>{lang_Address}</label>
                        <input class="{form_error_address}" name="address" type="text" placeholder="{lang_Address}" value="{form_value_address}" />
                        <label>{lang_Message}</label>
                        <textarea class="{form_error_message}" name="message" rows="3" placeholder="{lang_Message}">{form_value_message}</textarea>
                        <br style="clear: both;" />
                        <?php if(config_db_item('terms_link') !== FALSE): ?>
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
                        <div class="" style="width: 160px;padding-top: 10px;">
                            <input type="checkbox" value="1" name="terms_user" class="terms_user" required="required" style="margin: 0;display: inline-block;width: 20px;"> <a target="_blank" href="<?php echo $terms_url; ?>"><?php echo lang_check('I accept the Terms and Conditions'); ?></a>
                        </div>
                        <?php endif;?>
                        <?php if(config_db_item('privacy_link') !== FALSE  && sw_count($not_logged)>0): ?>
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
                        <div class="" style="width: 160px;padding-top: 10px;">
                            <input type="checkbox" value="1" name="privacy_user" class="privacy_user" required="required" style="margin: 0;display: inline-block;width: 20px;"> <a target="_blank" href="<?php echo $privacy_url; ?>"><?php echo lang_check('I accept the Privacy'); ?></a>
                        </div>
                        <?php endif;?>
                        <p style="text-align:right;">
                        <button type="submit" class="btn btn-info">{lang_Send}</button>
                        </p>
                    </form>
                  </div>
            </div>
    </div>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>