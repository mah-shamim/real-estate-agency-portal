<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{page_title}</title>
    <meta name="description" content="{page_description}" />
    <meta name="keywords" content="{page_keywords}" />
    <meta name="author" content="" />
    <meta name="robots" content="noindex,nofollow"/>
    
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png" />
    <meta property="og:image" content="assets/img/logo.png" />

    <!-- Le styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="assets/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="assets/css/blueimp-gallery.min.css" rel="stylesheet">
    <link href="assets/css/jquery.cleditor.css" rel="stylesheet">
    <link href="assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    {is_rtl}
    <link href="assets/css/styles_rtl.css" rel="stylesheet">
    {/is_rtl}
    {has_color}
    <link href="assets/css/styles_{color}.css" rel="stylesheet">
    {/has_color}
    <link href="assets/css/disable-responsive.css" rel="stylesheet">

    
    <?php load_map_api(config_db_item('map_version'), $lang_code);?>
    
    <script src="assets/js/jquery-1.8.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <?php if(config_db_item('map_version') !=='open_street'):?>
    <script src="assets/js/gmap3.js"></script>
     <?php endif;?>
    <script src="assets/js/bootstrap-select.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/blueimp-gallery.min.js"></script>
    <script src="assets/js/jquery.helpers.js"></script>

    {has_extra_js}
    <script src="assets/js/jquery.cleditor.min.js"></script>
    <script src="assets/js/load-image.js"></script>
    <script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script> <!-- jQuery UI -->
    
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="assets/css/jquery.fileupload-ui.css" />
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="assets/css/jquery.fileupload-ui-noscript.css" /></noscript>    
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="assets/js/fileupload/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="assets/js/fileupload/jquery.fileupload.js"></script>
    <!-- The File Upload file processing plugin -->
    <script src="assets/js/fileupload/jquery.fileupload-fp.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="assets/js/fileupload/jquery.fileupload-ui.js"></script>
    <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
    <!--[if gte IE 8]><script src="assets/js/cors/jquery.xdr-transport.js')?>"></script><![endif]-->
    {/has_extra_js}
    
    <?php if(config_item('ad_gallery_enabled') === TRUE): ?>
        <link rel="stylesheet" type="text/css" href="assets/js/adgallery/jquery.ad-gallery.css">
        <script src="assets/js/adgallery/jquery.ad-gallery.js"></script>
    <?php endif; ?>
    
    <script src="assets/js/jquery.custom.js"></script>
    
  <style type="text/css">
  
  

  @media screen {

  }

  @media print {
    .print_hidden{
        display:none;
    }
  }
</style>
    
    <script>
    
        var timerMap;
        var ad_galleries;
        var firstSet = false;
        var mapRefresh = true;
        var loadOnTab = true;
        var zoomOnMapSearch = 9;
        var clusterConfig = null;
        var markerOptions = null;
        var mapDisableAutoPan = false;
        var mapStyle = null;
        var myLocationEnabled = true;
        var c_mapTypeId = "style1"; // google.maps.MapTypeId.ROADMAP;
        
        <?php if(config_db_item('map_version') !='open_street'):?>
        var c_mapTypeIds = ["style1",
                            google.maps.MapTypeId.ROADMAP,
                            google.maps.MapTypeId.HYBRID];    
        <?php endif;?>   
        var markers = [];
        var map = '';
        <?php if(config_db_item('map_version') =='open_street'):?>
        var clusters ='';
        clusters = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
        <?php endif;?>
                        
        //google.maps.MapTypeId.ROADMAP
        //google.maps.MapTypeId.SATELLITE
        //google.maps.MapTypeId.HYBRID
        //google.maps.MapTypeId.TERRAIN

        $(document).ready(function()
        {
            // Cluster config start //
            clusterConfig = {
              radius: 60,
              // This style will be used for clusters with more than 2 markers
              2: {
                content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
                width: 53,
                height: 52
              },
              // This style will be used for clusters with more than 5 markers
              5: {
                content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
                width: 53,
                height: 52
              },
              // This style will be used for clusters with more than 20 markers
              20: {
                content: "<div class='cluster cluster-2'>CLUSTER_COUNT</div>",
                width: 56,
                height: 55
              },
              // This style will be used for clusters with more than 50 markers
              50: {
                content: "<div class='cluster cluster-3'>CLUSTER_COUNT</div>",
                width: 66,
                height: 65
              },
              events: {
                click:function(cluster, event, object) {
                    try {
                        var same_address = true;
                        var adr = '';
                        $.each(object.data.markers, function(item) {
                            
                            if(adr == '')
                                adr = object.data.markers[item].adr;
                            
                            if(adr != object.data.markers[item].adr)
                                same_address = false;
                        });
                        
                        if(same_address)
                        {
                            cluster.main.map.panTo(object.data.latLng);
                            cluster.main.map.setZoom(19);
                        }
                        else
                        {
                            cluster.main.map.panTo(object.data.latLng);
                            cluster.main.map.setZoom(cluster.main.map.getZoom()+1);
                        }
                    }
                    catch(err) {
                        cluster.main.map.panTo(object.data.latLng);
                        cluster.main.map.setZoom(cluster.main.map.getZoom()+1);
                    }
                }
              }
            };
            // Cluster config end //
            
            // Map style start //
            
            //mapStyle = [{"featureType":"water","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"landscape","stylers":[{"color":"#f2f2f2"}]},{"featureType":"road","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]}];
            //mapStyle = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.business","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]}];
            mapStyle = [{"featureType":"landscape","stylers":[{"hue":"#FFA800"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#53FF00"},{"saturation":-73},{"lightness":40},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FBFF00"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#00FFFD"},{"saturation":0},{"lightness":30},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#00BFFF"},{"saturation":6},{"lightness":8},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#679714"},{"saturation":33.4},{"lightness":-25.4},{"gamma":1}]}];

            // Map style end //
            
            // Map Marker options start //
            markerOptions = {
              draggable: false
            };
            // Map Marker options  end //
            
            // Calendar translation start //
            
            var translated_cal = {
    			days: ["{lang_cal_sunday}", "{lang_cal_monday}", "{lang_cal_tuesday}", "{lang_cal_wednesday}", "{lang_cal_thursday}", "{lang_cal_friday}", "{lang_cal_saturday}", "{lang_cal_sunday}"],
    			daysShort: ["{lang_cal_sun}", "{lang_cal_mon}", "{lang_cal_tue}", "{lang_cal_wed}", "{lang_cal_thu}", "{lang_cal_fri}", "{lang_cal_sat}", "{lang_cal_sun}"],
    			daysMin: ["{lang_cal_su}", "{lang_cal_mo}", "{lang_cal_tu}", "{lang_cal_we}", "{lang_cal_th}", "{lang_cal_fr}", "{lang_cal_sa}", "{lang_cal_su}"],
    			months: ["{lang_cal_january}", "{lang_cal_february}", "{lang_cal_march}", "{lang_cal_april}", "{lang_cal_may}", "{lang_cal_june}", "{lang_cal_july}", "{lang_cal_august}", "{lang_cal_september}", "{lang_cal_october}", "{lang_cal_november}", "{lang_cal_december}"],
    			monthsShort: ["{lang_cal_jan}", "{lang_cal_feb}", "{lang_cal_mar}", "{lang_cal_apr}", "{lang_cal_may}", "{lang_cal_jun}", "{lang_cal_jul}", "{lang_cal_aug}", "{lang_cal_sep}", "{lang_cal_oct}", "{lang_cal_nov}", "{lang_cal_dec}"]
    		};
            
            if(typeof(DPGlobal) != 'undefined'){
                DPGlobal.dates = translated_cal;
            }
            
            // Calendar translation End //
            
            <?php if(config_item('ad_gallery_enabled') == TRUE): ?>
            ad_galleries = $('.ad-gallery').adGallery();
            <?php endif; ?>
            
            /*
            $('#your_button_id').click(function(){
                $("#wrap-map").gmap3({
                 map:{
                    options:{
                     center: [{all_estates_center}],
                     zoom: {settings_zoom}
                    }
                 }});
               return false; 
            });
            */
            
            //Init carousel
            
            $('#myCarousel').carousel();         

            /* Search start */

            $('.menu-onmap li a').click(function () { 
              if(!$(this).parent().hasClass('list-property-button'))
              {
                  $(this).parent().parent().find('li').removeClass("active");
                  $(this).parent().addClass("active");
                  
                  if(loadOnTab) manualSearch(0);
                  return false;
              }
            });
            
            <?php if(config_item('all_results_default') !== TRUE): ?>
            if($('.menu-onmap li.active').length == 0)
            {
                $('.menu-onmap li:first').addClass('active');
            }
            <?php endif; ?>
            
            $('#search-start').click(function () { 
              manualSearch(0);
              return false;
            });
            /* Search end */
            
            <?php $dates_list = ''; if(isset($available_dates) && file_exists(APPPATH.'controllers/admin/booking.php')): ?>
            var dates_list = [];
            <?php foreach($available_dates as $date_format => $unix_format): ?>
            <?php
                $dates_list.='"'.$date_format.'", ';
            ?>
            <?php endforeach; ?>
            <?php
                if($dates_list != '')
                    $dates_list = substr($dates_list, 0, -2);
            ?>dates_list = [<?php echo $dates_list; ?>];
            <?php endif; ?>
            
            /* Date picker */
            var nowTemp = new Date();
            
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
             
            var checkin = $('#datetimepicker1').datepicker({
                onRender: function(date) {
                    
                    //console.log(date.valueOf());
                    //console.log(date.toString());
                    //console.log(now.valueOf());
                    
                    var dd = date.getDate();
                    var mm = date.getMonth()+1;//January is 0!`
                    
                    var yyyy = date.getFullYear();
                    if(dd<10){dd='0'+dd}
                    if(mm<10){mm='0'+mm}
                    var today_formated = yyyy+'-'+mm+'-'+dd;
                    
                    
                    if(date.valueOf() < now.valueOf()) // Just for performance
                    {
                        return 'disabled';
                    }
                    <?php if(file_exists(APPPATH.'controllers/admin/booking.php')): ?>
                    else if(dates_list.indexOf(today_formated )>= 0)
                    {
                        return '';
                    }
                    
                    return 'disabled red';
                    <?php else: ?>
                    return '';
                    <?php endif;?>
                }
            }).on('changeDate', function(ev) {
                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    var newDate = new Date(ev.date)
                    newDate.setDate(newDate.getDate() + 7);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('#datetimepicker2')[0].focus();
            }).data('datepicker');
                var checkout = $('#datetimepicker2').datepicker({
                onRender: function(date) {

                    var dd = date.getDate();
                    var mm = date.getMonth()+1;//January is 0!`
                    
                    var yyyy = date.getFullYear();
                    if(dd<10){dd='0'+dd}
                    if(mm<10){mm='0'+mm}
                    var today_formated = yyyy+'-'+mm+'-'+dd;
                    
                    
                    if(date.valueOf() <= checkin.date.valueOf()) // Just for performance
                    {
                        return 'disabled';
                    }                    
                    <?php if(file_exists(APPPATH.'controllers/admin/booking.php')): ?>
                    else if(dates_list.indexOf(today_formated )>= 0)
                    {
                        return '';
                    }
                    
                    return 'disabled red';
                    <?php else: ?>
                    return '';
                    <?php endif;?>
            }
            }).on('changeDate', function(ev) {
                checkout.hide();
            }).data('datepicker');
            
            <?php if(file_exists(APPPATH.'controllers/admin/booking.php')): ?>
            /* Search booking form */
            
            var checkin_booking = $('#booking_date_from').datepicker({
                onRender: function(date) {
                    var dd = date.getDate();
                    var mm = date.getMonth()+1;//January is 0!`
                    
                    var yyyy = date.getFullYear();
                    if(dd<10){dd='0'+dd}
                    if(mm<10){mm='0'+mm}
                    var today_formated = yyyy+'-'+mm+'-'+dd;
                    
                    
                    if(date.valueOf() < now.valueOf())
                    {
                        return 'disabled';
                    }
                    
                    return '';
                }
            }).on('changeDate', function(ev) {
                if (ev.date.valueOf() > checkout_booking.date.valueOf()) {
                    var newDate = new Date(ev.date)
                    newDate.setDate(newDate.getDate() + 7);
                    checkout_booking.setValue(newDate);
                }
                checkin_booking.hide();
                $('#booking_date_to')[0].focus();
            }).data('datepicker');
                var checkout_booking = $('#booking_date_to').datepicker({
                onRender: function(date) {

                    var dd = date.getDate();
                    var mm = date.getMonth()+1;//January is 0!`
                    
                    var yyyy = date.getFullYear();
                    if(dd<10){dd='0'+dd}
                    if(mm<10){mm='0'+mm}
                    var today_formated = yyyy+'-'+mm+'-'+dd;
                    
                    
                    if(date.valueOf() <= checkin_booking.date.valueOf())
                    {
                        return 'disabled';
                    }
                    
                    return '';
            }
            }).on('changeDate', function(ev) {
                checkout_booking.hide();
            }).data('datepicker');
            <?php endif;?>
            
            $('a.available.selectable').click(function(){
                $('#datetimepicker1').val($(this).attr('ref'));
                $('#datetimepicker2').val($(this).attr('ref_to'));
                $('div.property-form form input:first').focus();
                
                var nowTemp = new Date($(this).attr('ref'));
                var date_from = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

                checkin.setValue(date_from);
                date_from.setDate(date_from.getDate() + 7);
                checkout.setValue(date_from);
            });
            
            
            /* Date picker end */
            
            /* Edit property */
            <?php if(config_db_item('map_version') !='open_street'):?>
            // If alredy selected
            if($('#inputGps').length && $('#inputGps').val() != '')
            {
                savedGpsData = $('#inputGps').val().split(", ");
                
                $("#mapsAddress").gmap3({
                    map:{
                      options:{
                        center: [parseFloat(savedGpsData[0]), parseFloat(savedGpsData[1])],
                        zoom: 14
                      }
                    },
                    marker:{
                    values:[
                      {latLng:[parseFloat(savedGpsData[0]), parseFloat(savedGpsData[1])]},
                    ],
                    options:{
                      draggable: true
                    },
                    events:{
                        dragend: function(marker){
                          $('#inputGps').val(marker.getPosition().lat()+', '+marker.getPosition().lng());
                        }
                  }}});
                
                firstSet = true;
            }
            else
            {
                $("#mapsAddress").gmap3({
                    map:{
                      options:{
                        center: [{settings_gps}],
                        zoom: 12
                      },
                    }
                  });
            }
                
            $('#inputAddress').keyup(function (e) {
                clearTimeout(timerMap);
                timerMap = setTimeout(function () {
                    
                    $("#mapsAddress").gmap3({
                      getlatlng:{
                        address:  $('#inputAddress').val(),
                        callback: function(results){
                          if ( !results ){
                            alert('Bad address!');
                            return;
                          } 
                          
                            if(firstSet){
                                $(this).gmap3({
                                    clear: {
                                      name:["marker"],
                                      last: true
                                    }
                                });
                            }
                          
                          // Add marker
                          $(this).gmap3({
                            marker:{
                              latLng:results[0].geometry.location,
                               options: {
                                  id:'searchMarker',
                                  draggable: true
                              },
                              events: {
                                dragend: function(marker){
                                  $('#inputGps').val(marker.getPosition().lat()+', '+marker.getPosition().lng());
                                }
                              }
                            }
                          });
                          
                          // Center map
                          $(this).gmap3('get').setCenter( results[0].geometry.location );
                          
                          $('#inputGps').val(results[0].geometry.location.lat()+', '+results[0].geometry.location.lng());
                          
                          firstSet = true;
    
                        }
                      }
                    });
                }, 2000);
                
            });
            <?php endif;?>
            
            
            //Typeahead
            
            $('#search_option_smart').typeahead({
                minLength: 1,
                source: function(query, process) {
                    $.post('{typeahead_url}/smart', { q: query, limit: 8 }, function(data) {
                        process(JSON.parse(data));
                    });
                }
            });
            
            {has_extra_js}
            $(".cleditor").cleditor({
                width: "400px",
                height: "auto"
            });
            
            
            $('.tabbable li a').click(function () { 
                var tab_width = 0;
                var tab_width_real = 0;
                $('.tab-content').find('div.cleditorToolbar:first .cleditorGroup').each(function (i) {
                    tab_width += $(this).width();
                });
                
                tab_width_real = $('.tab-content').find('div.cleditorToolbar').width();
                var rows = parseInt(tab_width/tab_width_real+1)
                
                $('.tab-content').find('div.cleditorToolbar').height(rows*27);
                

                $('.tab-content').find('.cleditor').refresh();
            });
            {/has_extra_js}
            
        $('.zoom-button').bind("click touchstart", function()
        {
            var myLinks = new Array();
            var current = $(this).attr('href');
            var curIndex = 0;
            
            $('.files-list-u .zoom-button').each(function (i) {
                var img_href = $(this).attr('href');
                myLinks[i] = img_href;
                if(current == img_href)
                    curIndex = i;
            });

            options = {index: curIndex}
            
            blueimp.Gallery(myLinks, options);
            
            return false;
        });
            {has_extra_js}
            loadjQueryUpload();
            {/has_extra_js}
            reloadElements();    
        });
        
        function reloadElements()
        {            
            $('.selectpicker-small').selectpicker({
                style: 'btn-default'
            });
            
            $('.selectpicker-small').change(function() {
                manualSearch(0);
                return false;
            });
            
            $('.view-type').click(function () { 
              $(this).parent().find('.view-type').removeClass("active");
              $(this).addClass("active");
              manualSearch(0);
              return false;
            });
            
            $('.pagination.properties a').click(function () { 
              var page_num = $(this).attr('href');
              var n = page_num.lastIndexOf("/"); 
              page_num = page_num.substr(n+1);
              
              manualSearch(page_num);
              return false;
            });
            
            $('.pagination.news a').click(function () { 
                var page_num = $(this).attr('href');
                var n = page_num.lastIndexOf("/"); 
                page_num = page_num.substr(n+1);
                
                $.post($(this).attr('href'), {search: $('#search_showroom').val()}, function(data){
                    $('.property_content_position').html(data.print);
                    
                    reloadElements();
                }, "json");
                
                return false;
            });
        }
        
        function manualSearch(v_pagenum)
        {
            // Order ASC/DESC
            var v_order = $('.selectpicker-small').val();
            
            // View List/Grid
            var v_view = $('.view-type.active').attr('ref');          
            
            //Define default data values for search
            var data = {
                order: v_order,
                view: v_view,
                page_num: v_pagenum
            };
            
            if($('#booking_date_from').length > 0)
            {
                if($('#booking_date_from').val() != '')
                    data['v_booking_date_from'] = $('#booking_date_from').val();
            }
            
            if($('#booking_date_to').length > 0)
            {
                if($('#booking_date_to').val() != '')
                    data['v_booking_date_to'] = $('#booking_date_to').val();
            }
            
            // Purpose, "for custom tabbed selector"
            if($('#search_option_4 .active a').length > 0)
            {
                data['v_search_option_4'] = $('#search_option_4 .active a').html();
            }
            
            // Add custom data values, automatically by fields inside search-form
            $('.search-form form input, .search-form form select').each(function (i) {
                if($(this).attr('type') == 'checkbox')
                {
                    if ($(this).attr('checked'))
                    {
                        data['v_'+$(this).attr('id')] = $(this).val();
                    }
                }
                else
                {
                    data['v_'+$(this).attr('id')] = $(this).val();
                }
            });
            
            $("#ajax-indicator-1").show();
            $.post("{ajax_load_url}/"+v_pagenum, data,
            function(data){
                
                <?php if(config_db_item('map_version') !='open_street'):?>
                if(mapRefresh)
                {
                    //Remove all markers
                    $("#wrap-map").gmap3({
                        clear: {
                            name:["marker"]
                        }
                    });
                    
                    if(data.results.length > 0)
                    {
                        //Add new markers
                        $("#wrap-map").gmap3({
                            map:{
                              options:{
                                <?php if(config_item('custom_map_center') === FALSE): ?>
                                center: data.results_center,
                                <?php else: ?>
                                center: [<?php echo config_item('custom_map_center'); ?>],
                                <?php endif; ?>
                                zoom: {settings_zoom},
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
                            values: data.results,
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
                          }
                        }}
                        });
                        
                        if($('#pac-input').length==0)
                        {
                            // Add SearchBox
                            $('#wrap-map').before('<input id="pac-input" class="controls" type="text" placeholder="{lang_Search}" />');
                            init_gmap_searchbox();
                        }
                    }
                }
                <?php endif;?>
                $(selectorResults).html(data.print);
                    reloadElements();
                
                $("#ajax-indicator-1").hide();
                
//                $(selectorResults).hide(1000,function(){
//                    $(selectorResults).html(data.print);
//                    reloadElements();
//                    $(selectorResults).show(1000);
//                });
            }, "json");
            return false;
        }
        
    $.fn.startLoading = function(data){
        //$('#saveAll, #add-new-page, ol.sortable button, #saveRevision').button('loading');
    }
    
    $.fn.endLoading = function(data){
        //$('#saveAll, #add-new-page, ol.sortable button, #saveRevision').button('reset');       
        <?php if(config_item('app_type') == 'demo'):?>
            ShowStatus.show('<?php echo lang('Data editing disabled in demo')?>');
        <?php else:?>
            //ShowStatus.show('<?php echo lang('data_saved')?>');
        <?php endif;?>
    }
    {has_extra_js}
    function loadjQueryUpload()
    {
        $('form.fileupload').each(function () {
            $(this).fileupload({
            <?php if(config_item('app_type') != 'demo'):?>
            autoUpload: true,
            <?php endif;?>
            // The maximum width of the preview images:
            previewMaxWidth: 160,
            // The maximum height of the preview images:
            previewMaxHeight: 120,
            uploadTemplateId: null,
            downloadTemplateId: null,
            uploadTemplate: function (o) {
                var rows = $();
                $.each(o.files, function (index, file) {
                    var row = $('<li class="img-rounded template-upload">' +
                        '<div class="preview"><span class="fade"></span></div>' +
                        '<div class="filename"><code>'+file.name+'</code></div>'+
                        '<div class="options-container">' +
                        '<span class="cancel"><button  class="btn btn-mini btn-warning"><i class="icon-ban-circle icon-white"></i></button></span></div>' +
                        (file.error ? '<div class="error"></div>' :
                                '<div class="progress">' +
                                    '<div class="bar" style="width:0%;"></div></div></div>'
                        )+'</li>');
                    row.find('.name').text(file.name);
                    row.find('.size').text(o.formatFileSize(file.size));
                    if (file.error) {
                        row.find('.error').text(
                            locale.fileupload.errors[file.error] || file.error
                        );
                    }
                    rows = rows.add(row);
                });
                return rows;
            },
            downloadTemplate: function (o) {
                var rows = $();
                $.each(o.files, function (index, file) {
                    var row = $('<li class="img-rounded template-download fade">' +
                        '<div class="preview"><span class="fade"></span></div>' +
                        '<div class="filename"><code>'+file.short_name+'</code></div>'+
                        '<div class="options-container">' +
                        (file.zoom_enabled?
                            '<a data-gallery="gallery" class="zoom-button btn btn-mini btn-success" download="'+file.name+'"><i class="icon-search icon-white"></i></a>'
                            : '<a target="_blank" class="btn btn-mini btn-success" download="'+file.name+'"><i class="icon-search icon-white"></i></a>') +
                        ' <span class="delete"><button class="btn btn-mini btn-danger" data-type="'+file.delete_type+'" data-url="'+file.delete_url+'"><i class="icon-trash icon-white"></i></button>' +
                        ' <input type="checkbox" value="1" name="delete"></span>' +
                        '</div>' +
                        (file.error ? '<div class="error"></div>' : '')+'</li>');
    
                    if (file.error) {
                        row.find('.name').text(file.name);
                        row.find('.error').text(
                            file.error
                        );
                    } else {
                        row.find('.name a').text(file.name);
                        if (file.thumbnail_url) {
                            row.find('.preview').html('<img class="img-rounded" alt="'+file.name+'" data-src="'+file.thumbnail_url+'" src="'+file.thumbnail_url+'">');  
                        }
                        row.find('a').prop('href', file.url);
                        row.find('a').prop('title', file.name);
                        row.find('.delete button')
                            .attr('data-type', file.delete_type)
                            .attr('data-url', file.delete_url);
                    }
                    rows = rows.add(row);
                });
                
                return rows;
            },
            destroyed: function (e, data) {
                $.fn.endLoading();
                return false;
            },
            <?php if(config_item('app_type') == 'demo'):?>
            added: function (e, data) {
                $.fn.endLoading();
                return false;
            },
            <?php endif;?>
            finished: function (e, data) {
                $('.zoom-button').unbind('click touchstart');
                $('.zoom-button').bind("click touchstart", function()
                {
                    var myLinks = new Array();
                    var current = $(this).attr('href');
                    var curIndex = 0;
                    
                    $('.files-list-u .zoom-button').each(function (i) {
                        var img_href = $(this).attr('href');
                        myLinks[i] = img_href;
                        if(current == img_href)
                            curIndex = i;
                    });
            
                    options = {index: curIndex}
            
                    blueimp.Gallery(myLinks, options);
                    
                    return false;
                });
            },
            dropZone: $(this)
        });
        });       
        
        $("ul.files").each(function (i) {
            $(this).sortable({
                update: saveFilesOrder
            });
            $(this).disableSelection();
        });
    
    }
    
    function filesOrderToArray(container)
    {
        var data = {};

        container.find('li').each(function (i) {
            var filename = $(this).find('.options-container a:first').attr('download');
            data[i+1] = filename;
        });
        
        return data;
    }
    
    function saveFilesOrder( event, ui )
    {
        var filesOrder = filesOrderToArray($(this));
        var pageId = $(this).parent().parent().parent().attr('id').substring(11);
        var modelName = $(this).parent().parent().parent().attr('rel');
        
        $.fn.startLoading();
		$.post('<?php echo site_url('files/order'); ?>/'+pageId+'/'+modelName, 
        { 'page_id': pageId, 'order': filesOrder }, 
        function(data){
            $.fn.endLoading();
		}, "json");
    }
    
    {/has_extra_js}
        
        function init_gmap_searchbox()
        {
            if( $('#pac-input').length==0 || $('#wrap-map').length==0 )return;
            
            var map = $("#wrap-map").gmap3({
                get: { name:"map" }
            });    
            
            var markers = [];

            // Create the search box and link it to the UI element.
            var input = /** @type {HTMLInputElement} */(
              document.getElementById('pac-input'));
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            
            var searchBox = new google.maps.places.SearchBox(
            /** @type {HTMLInputElement} */(input));
            
            // [START region_getplaces]
            // Listen for the event fired when the user selects an item from the
            // pick list. Retrieve the matching places for that item.
            google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();
            
            for (var i = 0, marker; marker = markers[i]; i++) {
              marker.setMap(null);
            }
            
            // For each place, get the icon, place name, and location.
            markers = [];
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0, place; place = places[i]; i++) {
              var image = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
              };
            
              // Create a marker for each place.
              var marker = new google.maps.Marker({
                map: map,
                icon: image,
                title: place.name,
                position: place.geometry.location
              });
            
              markers.push(marker);
            
              bounds.extend(place.geometry.location);
            }
            
            map.fitBounds(bounds);
            var zoom = map.getZoom();
            map.setZoom(zoom > zoomOnMapSearch ? zoomOnMapSearch : zoom);
            });
            // [END region_getplaces]
            
            if(myLocationEnabled){
                // [START gmap mylocation]
                
                // Construct your control in whatever manner is appropriate.
                // Generally, your constructor will want access to the
                // DIV on which you'll attach the control UI to the Map.
                var controlDiv = document.createElement('div');
                
                // We don't really need to set an index value here, but
                // this would be how you do it. Note that we set this
                // value as a property of the DIV itself.
                controlDiv.index = 1;
                
                // Add the control to the map at a designated control position
                // by pushing it on the position's array. This code will
                // implicitly add the control to the DOM, through the Map
                // object. You should not attach the control manually.
                map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv);
                
                HomeControl(controlDiv, map)
    
                // [END gmap mylocation]
            }
        }
        
        function HomeControl(controlDiv, map) {
        
          // Set CSS styles for the DIV containing the control
          // Setting padding to 5 px will offset the control
          // from the edge of the map.
          controlDiv.style.padding = '5px';
        
          // Set CSS for the control border.
          var controlUI = document.createElement('div');
          controlUI.style.backgroundColor = 'white';
          controlUI.style.borderStyle = 'solid';
          controlUI.style.borderWidth = '2px';
          controlUI.style.cursor = 'pointer';
          controlUI.style.textAlign = 'center';
          controlUI.title = '{lang_MyLocation}';
          controlDiv.appendChild(controlUI);
        
          // Set CSS for the control interior.
          var controlText = document.createElement('div');
          controlText.style.fontFamily = 'Arial,sans-serif';
          controlText.style.fontSize = '12px';
          controlText.style.paddingLeft = '4px';
          controlText.style.paddingRight = '4px';
          controlText.innerHTML = '<strong>{lang_MyLocation}</strong>';
          controlUI.appendChild(controlText);
        
          // Setup the click event listeners: simply set the map to Chicago.
          google.maps.event.addDomListener(controlUI, 'click', function() {
            var myloc = new google.maps.Marker({
                clickable: false,
                icon: new google.maps.MarkerImage('//maps.gstatic.com/mapfiles/mobile/mobileimgs2.png',
                                                                new google.maps.Size(22,22),
                                                                new google.maps.Point(0,18),
                                                                new google.maps.Point(11,11)),
                shadow: null,
                zIndex: 999,
                map: map
            });
            
            if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(pos) {
                var me = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                myloc.setPosition(me);
                
                // Zoom in
                var bounds = new google.maps.LatLngBounds();
                bounds.extend(me);
                map.fitBounds(bounds);
                var zoom = map.getZoom();
                map.setZoom(zoom > zoomOnMapSearch ? zoomOnMapSearch : zoom);
            }, function(error) {
                console.log(error);
            });
          });
        }

        
    </script>
    
    
    <script>
    $(document).ready(function(){
 <?php if(config_db_item('map_version') =='open_street'):?>
        var property_map;
        if($('#propertyLocation').length){
            property_map = L.map('propertyLocation', {
                center: [{estate_data_gps}],
                zoom: {settings_zoom}+6,
                scrollWheelZoom: false,
            });     
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(property_map);
            var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(property_map);
            var property_marker = L.marker(
                [{estate_data_gps}],
                {icon: L.divIcon({
                        html: '<img src="{estate_data_icon}">',
                        className: 'open_steet_map_marker',
                        iconSize: [31, 46],
                        popupAnchor: [1, -45],
                        iconAnchor: [15, 45],
                    })
                }
            ).addTo(property_map);
        
            property_marker.bindPopup("{estate_data_address}<br />{lang_GPS}: {estate_data_gps}");
        }

   <?php else:?>
        $('#propertyLocation').gmap3({
         map:{
            options:{
             center: [{estate_data_gps}],
             zoom: {settings_zoom},
             scrollwheel: false
            }
         },
         marker:{
            values:[
                {latLng:[{estate_data_gps}], options:{icon: "{estate_data_icon}"}, data:"{estate_data_address}<br />{lang_GPS}: {estate_data_gps}"},
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
        <?php endif;?>
    });    
    </script>
    {settings_tracking}
  </head>

  <body>

<div class="wrap-content-print">
    <div class="container container-property">
        <div class="row-fluid">
            <div class="span9">
            <h2>{page_title}</h2>
            <?php if(isset($slideshow_property_images[0])): ?>
            <img src="<?php echo $slideshow_property_images[0]['url']; ?>" alt="" />
            <?php endif; ?>
              <div class="property_content property_content_print">
                <h2>{lang_Description}</h2>
                {page_body}
                <?php if(isset($category_options_21) && sw_count($category_options_21)>1): ?>
                <h2>{options_name_21}</h2>
                <ul class="amenities">
                {category_options_21}
                {is_checkbox}
                <li>
                <img src="assets/img/checkbox_{option_value}.png" alt="{option_value}" class="check" alt="" />&nbsp;&nbsp;{option_name}&nbsp;&nbsp;{icon}
                </li>
                {/is_checkbox}
                {/category_options_21}
                </ul>
                <br style="clear: both;" />
                <?php endif; ?>
                
                <?php if(isset($category_options_52) && sw_count($category_options_52)>1): ?>
                <h2>{options_name_52}</h2>
                <ul class="amenities">
                {category_options_52}
                {is_checkbox}
                <li>
                <img src="assets/img/checkbox_{option_value}.png" alt="{option_value}" class="check" alt="" />&nbsp;&nbsp;{option_name}&nbsp;&nbsp;{icon}
                </li>
                {/is_checkbox}
                {/category_options_52}
                </ul>
                <br style="clear: both;" />
                <?php endif; ?>
                <?php if(isset($category_options_43) && sw_count($category_options_43)>1): ?>
                <h2>{options_name_43}</h2>
                <ul class="amenities">
                {category_options_43}
                {is_text}
                <li>
                {icon} {option_name}:&nbsp;&nbsp;{option_prefix}{option_value}{option_suffix}
                </li>
                {/is_text}
                {/category_options_43}
                </ul>
                <br style="clear: both;" />
                <?php endif; ?>

                <?php if(file_exists(APPPATH.'controllers/admin/booking.php') && sw_count($property_rates)>0):?>
                <h2>{lang_Rates}</h2>
                <table class="table table-striped">
                    <thead>
                    <tr>
                    <th>{lang_From}</th>
                    <th>{lang_To}</th>
                    <th>{lang_Nightly}</th>
                    <th>{lang_Weekly}</th>
                    <th>{lang_Monthly}</th>
                    <th>{lang_MinStay}</th>
                    <th>{lang_ChangeoverDay}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($property_rates as $key=>$rate): ?>
                    <tr>
                    <td><?php echo date('Y-m-d', strtotime($rate->date_from)); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($rate->date_to)); ?></td>
                    <td><?php echo $rate->rate_nightly.' '.$rate->currency_code; ?></td>
                    <td><?php echo $rate->rate_weekly.' '.$rate->currency_code; ?></td>
                    <td><?php echo $rate->rate_monthly.' '.$rate->currency_code; ?></td>
                    <td><?php echo $rate->min_stay; ?></td>
                    <td><?php echo $changeover_days[$rate->changeover_day]; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif;?>

                {has_page_images}
                <h2>{lang_Imagegallery}</h2>
                <ul data-target="#modal-gallery" data-toggle="modal-gallery" class="files files-list ui-sortable">  
                    {page_images}
                    <li class="template-download fade in">
                        <div class="preview-img"><img src="{thumbnail_url}" data-src="{url}" alt="{filename}" class="" /></div>
                    </li>
                    {/page_images}
                </ul>
                {/has_page_images}
                
                <br style="clear:both;" />
                
                                
                <?php
                if(!empty($estate_data_option_65) && is_numeric($estate_data_option_65)):
                //Fetch repository
                $file_rep = array();
                
                $rep_id = $estate_data_option_65;
                
                if(config_item('plan_gallery_enabled') == TRUE && isset($estate_data_planimages_repository_id)) {
                    $rep_id = $estate_data_planimages_repository_id;
                }
                
                if(!empty($rep_id) && is_numeric($rep_id)){
                    $file_rep = $this->file_m->get_by(array('repository_id'=>$rep_id));
                }
                
                // If not defined in this language
                if(sw_count($file_rep) == 0)
                {
                    //Fetch option for default language
                    $def_lang_id = $this->language_m->get_default_id();
                    if(!empty($def_lang_id))
                    {
                        $def_lang_rep_id = $this->option_m->get_property_value($def_lang_id, $estate_data_id, 65);
                        
                        if(!empty($def_lang_rep_id))
                        $file_rep = $this->file_m->get_by(array('repository_id'=>$def_lang_rep_id));
                    }
                }
                
                $rep_value = '';
                if(sw_count($file_rep))
                {
                    echo '<h2>'.$options_name_65.'</h2>';
                    $rep_value.= '<ul data-target="#modal-gallery" data-toggle="modal-gallery" class="files files-list ui-sortable">';
                    foreach($file_rep as $file_r)
                    {
                        if(file_exists(FCPATH.'/files/thumbnail/'.$file_r->filename))
                        {
                            $rep_value.=
                            '<li class="template-download fade in">'.
                            '    <a data-gallery="gallery" href="'.base_url('files/'.$file_r->filename).'" title="'.$file_r->filename.'" download="'.base_url('files/'.$file_r->filename).'" class="preview show-icon">'.
                            '        <img src="assets/img/preview-icon.png" class=""  alt=""/>'.
                            '    </a>'.
                            '    <div class="preview-img"><img src="'.base_url('files/thumbnail/'.$file_r->filename).'" data-src="'.base_url('files/'.$file_r->filename).'" alt="'.$file_r->filename.'" class="" /></div>'.
                            '</li>';
                        }
                        else if(file_exists(FCPATH.'/templates/'.$settings_template.'/assets/img/icons/filetype/'.get_file_extension($file_r->filename).'.png'))
                        {
                            $rep_value.=
                            '<li class="template-download fade in">'.
                            '    <a target="_blank" href="'.base_url('files/'.$file_r->filename).'" title="'.$file_r->filename.'" download="'.base_url('files/'.$file_r->filename).'" class="preview show-icon direct-download">'.
                            '        <img src="assets/img/preview-icon.png" class=""  alt=""/>'.
                            '    </a>'.
                            '    <div class="preview-img"><img src="assets/img/icons/filetype/'.get_file_extension($file_r->filename).'.png" data-src="'.base_url('files/'.$file_r->filename).'" alt="'.$file_r->filename.'" class="" /></div>'.
                            '</li>';
                        }
                    }
                    $rep_value.= '</ul>';

                    echo $rep_value;
                    echo '<br style="clear:both;" />';
                }
                endif;
                ?>
                
                {has_page_documents}
                <h2>{lang_Filerepository}</h2>
                <ul>
                {page_documents}
                <li>
                    <a href="{url}">{filename}</a>
                </li>
                {/page_documents}
                </ul>
                <br style="clear:both;" />
                {/has_page_documents}

                <br style="clear:both;" />

              </div>
            </div>
            <div class="span3">
                  <h2>{lang_Overview}</h2>
                  <div class="property_options">
                    <?php if(!empty($estate_data_address)): ?>
                    <p class="bottom-border"><strong>
                    {lang_Address}
                    </strong> <span>{estate_data_address}</span>
                    <br style="clear: both;" />
                    </p>
                    <?php endif; ?>
                    {category_options_1}
                    {is_text}
                    <p class="bottom-border"><strong>{option_name}:</strong> <span>{option_value} {option_suffix}</span></p>
                    {/is_text}
                    {is_dropdown}
                    <p class="bottom-border"><strong>{option_name}:</strong> <span class="label label-success">&nbsp;&nbsp;{option_value}&nbsp;&nbsp;</span></p>
                    {/is_dropdown}
                    {is_checkbox}
                    <img src="assets/img/checkbox_{option_value}.png" alt="{option_value}" />&nbsp;&nbsp;{option_name}
                    {/is_checkbox}
                    {/category_options_1}
                    <button class="print_hidden" onclick="myFunction()"><?php echo lang_check('Print'); ?></button> <script> function myFunction() { window.print(); } </script>
                  </div>

                  {has_agent}
                  <h2>           
                      <?php if (isset($agent_profile['type']) && $agent_profile['type'] == 'USER'): ?>
                          <?php echo lang_check('Owner'); ?>
                      <?php else: ?>
                          {lang_Agent}
                      <?php endif; ?>
                  </h2>
                  <div class="agent">
                    <div class="image"><img src="{agent_image_url}" alt="{agent_name_surname}" /></div>
                    <div class="name">{agent_name_surname}</div>
                    <div class="phone">{agent_phone}</div>
                    <div class="mail">{agent_mail}</div>
                  </div>
                  {/has_agent}
                  <?php if(!empty($estate_data_gps)): ?>
                    <h2>{lang_Propertylocation}</h2>
                    <div id="propertyLocation">
                    </div>
                  <?php endif;?>
            </div>
        </div>
    </div>
</div>
    
<div class="wrap-bottom-print">
    <div class="container">
      <div class="row-fluid">
        <div class="span3">
            <table>
                <tr>
                    <td><i class="icon-map-marker"></i></td>
                    <td>
                        {settings_address_footer}
                    </td>
                </tr>
            </table>
        </div>
        <div class="span3">
            <table>
                <tr>
                    <td><i class="icon-phone"></i></td>
                    <td>{settings_phone}</td>
                </tr>
                <tr>
                    <td><i class="icon-print"></i></td>
                    <td>{settings_fax}</td>
                </tr>
                <tr>
                    <td><i class="icon-envelope"></i></td>
                    <td>{settings_email}</td>
                </tr>
            </table>
        </div>
      </div>
    </div>
    <!-- Generate time: <?php echo (microtime(true) - $time_start)?>, version: <?php echo APP_VERSION_REAL_ESTATE; ?> -->
</div>

  </body>
</html>