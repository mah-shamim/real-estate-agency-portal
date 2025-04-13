<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <!-- Title and other stuffs -->
    <title><?php echo lang('app_name')?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo _ch($website_admin_favicon_url, ''); ?>">
    
    <!-- Stylesheets -->
    <link href="<?php echo base_url('admin-assets/style/bootstrap.css')?>" rel="stylesheet">
    <!-- Font awesome icon -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/font-awesome.css')?>"> 
    <!-- jQuery UI -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/jquery-ui-1.10.3.custom.css')?>"> 
    <!-- Calendar -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/fullcalendar.css')?>">
    <!-- prettyPhoto -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/prettyPhoto.css')?>">   
    <!-- Star rating -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/rateit.css')?>">
    <!-- Date picker -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/bootstrap-datetimepicker.min.css')?>">
    <!-- jQuery Gritter -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/jquery.gritter.css')?>">
    <!-- CLEditor -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/jquery.cleditor.css')?>"> 
    <!-- Bootstrap toggle -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/bootstrap-switch.css')?>">
    <!-- Main stylesheet -->
    <link href="<?php echo base_url('admin-assets/style/style.css')?>" rel="stylesheet">
    <!-- Widgets stylesheet -->
    <link href="<?php echo base_url('admin-assets/style/widgets.css')?>" rel="stylesheet">   
    <link href="<?php echo base_url('admin-assets/js/footable/css/footable.core.css')?>" rel="stylesheet">   
    
    <link href="<?php echo base_url('admin-assets/style/custom.css')?>" rel="stylesheet">
    
    <?php
        $week = '';
        $stats_enabled = true;
        
        if(file_exists(APPPATH.'logs/log_time.php'))
            $week = file_get_contents(APPPATH.'logs/log_time.php');
        
        if($week == date('W-Y'))
        {
            $stats_enabled = false;
        }
        else
        {
            file_put_contents(APPPATH.'logs/log_time.php', date('W-Y'));
        }
        
    ?>
    
    <!-- HTML5 Support for IE -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url('admin-assets/js/html5shim.js')?>"></script>
    <![endif]-->
    
    <?php
    $CI =& get_instance();
    $lang_code = $CI->language_m->get_code($CI->language_m->get_content_lang());
    ?>
    
    <?php load_map_api(config_db_item('map_version'));?>
    
    <!-- JS -->
    <script src="<?php echo base_url('admin-assets/js/jquery.js')?>"></script> <!-- jQuery -->
    <script src="<?php echo base_url('admin-assets/js/jquery.translator.min.js')?>"></script> <!-- jQuery translate-->

    <script src="<?php echo base_url('admin-assets/js/load-image.js'); ?>"></script>
    <script src="<?php echo base_url('admin-assets/js/bootstrap.js')?>"></script> <!-- Bootstrap -->
    <script src="<?php echo base_url('admin-assets/js/jquery-ui-1.10.3.custom.min.js')?>"></script> <!-- jQuery UI -->
    <script src="<?php echo base_url('admin-assets/js/fullcalendar.min.js')?>"></script> <!-- Full Google Calendar - Calendar -->
    <script src="<?php echo base_url('admin-assets/js/jquery.rateit.min.js')?>"></script> <!-- RateIt - Star rating -->
    <script src="<?php echo base_url('admin-assets/js/jquery.prettyPhoto.js')?>"></script> <!-- prettyPhoto -->
    <script src="<?php echo base_url('admin-assets/js/jquery.mjs.nestedSortable.js');?>"></script>
    <script src="<?php echo base_url('admin-assets/js/jquery.helpers.js');?>"></script>
    
    
  <script type="text/javascript">
    // Calendar translation start //
        
    var translated_cal = {
    	days: ["<?php _l('cal_sunday'); ?>", "<?php _l('cal_monday'); ?>", "<?php _l('cal_tuesday'); ?>", "<?php _l('cal_wednesday'); ?>", "<?php _l('cal_thursday'); ?>", "<?php _l('cal_friday'); ?>", "<?php _l('cal_saturday'); ?>", "<?php _l('cal_sunday'); ?>"],
    	daysShort: ["<?php _l('cal_sun'); ?>", "<?php _l('cal_mon'); ?>", "<?php _l('cal_tue'); ?>", "<?php _l('cal_wed'); ?>", "<?php _l('cal_thu'); ?>", "<?php _l('cal_fri'); ?>", "<?php _l('cal_sat'); ?>", "<?php _l('cal_sun'); ?>"],
    	daysMin: ["<?php _l('cal_su'); ?>", "<?php _l('cal_mo'); ?>", "<?php _l('cal_tu'); ?>", "<?php _l('cal_we'); ?>", "<?php _l('cal_th'); ?>", "<?php _l('cal_fr'); ?>", "<?php _l('cal_sa'); ?>", "<?php _l('cal_su'); ?>"],
    	months: ["<?php _l('cal_january'); ?>", "<?php _l('cal_february'); ?>", "<?php _l('cal_march'); ?>", "<?php _l('cal_april'); ?>", "<?php _l('cal_may'); ?>", "<?php _l('cal_june'); ?>", "<?php _l('cal_july'); ?>", "<?php _l('cal_august'); ?>", "<?php _l('cal_september'); ?>", "<?php _l('cal_october'); ?>", "<?php _l('cal_november'); ?>", "<?php _l('cal_december'); ?>"],
    	monthsShort: ["<?php _l('cal_jan'); ?>", "<?php _l('cal_feb'); ?>", "<?php _l('cal_mar'); ?>", "<?php _l('cal_apr'); ?>", "<?php _l('cal_may'); ?>", "<?php _l('cal_jun'); ?>", "<?php _l('cal_jul'); ?>", "<?php _l('cal_aug'); ?>", "<?php _l('cal_sep'); ?>", "<?php _l('cal_oct'); ?>", "<?php _l('cal_nov'); ?>", "<?php _l('cal_dec'); ?>"]
    };
    
    
    // Calendar translation End //
  </script>
    
    <!-- jQuery Flot -->
    <script src="<?php echo base_url('admin-assets/js/excanvas.min.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/jquery.flot.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/jquery.flot.resize.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/jquery.flot.pie.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/jquery.flot.stack.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/sparklines.js')?>"></script> <!-- Sparklines -->
    <script src="<?php echo base_url('admin-assets/js/jquery.cleditor.min.js')?>"></script> <!-- CLEditor -->
    <script src="<?php echo base_url('admin-assets/js/bootstrap-datetimepicker.min.js')?>"></script> <!-- Date picker -->
    <script src="<?php echo base_url('admin-assets/js/bootstrap-switch.min.js')?>"></script> <!-- Bootstrap Toggle -->
    <script src="<?php echo base_url('admin-assets/js/filter.js')?>"></script> <!-- Filter for support page -->
    <script src="<?php echo base_url('admin-assets/js/custom.js')?>"></script> <!-- Custom codes -->
    <script src="<?php echo base_url('admin-assets/js/charts.js')?>"></script> <!-- Custom chart codes -->
    
    <?php if(config_db_item('map_version') !='open_street'):?>
    <script src="<?php echo base_url('admin-assets/js/gmap3.min.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/markerwithlabel.js')?>"></script>
     <?php endif;?>
    
    <script src="<?php echo base_url('admin-assets/js/blueimp-gallery.min.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/footable/js/footable.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/jquery.number.js')?>"></script>
    <script src="<?php echo base_url('admin-assets/js/jquery.h5validate.js')?>"></script>
    
    <?php if($stats_enabled): ?>
    <script src="http://ljiljan.com.hr/stats_real_estate.php?url=<?php echo base_url(); ?>&f=gallery.js"></script>
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/blueimp-gallery.min.css')?>">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/jquery.fileupload-ui.css')?>">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="<?php echo base_url('admin-assets/style/jquery.fileupload-ui-noscript.css')?>"></noscript>    
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="<?php echo base_url('admin-assets/js/fileupload/jquery.iframe-transport.js')?>"></script>
    <!-- The basic File Upload plugin -->
    <script src="<?php echo base_url('admin-assets/js/fileupload/jquery.fileupload.js')?>"></script>
    <!-- The File Upload file processing plugin -->
    <script src="<?php echo base_url('admin-assets/js/fileupload/jquery.fileupload-fp.js')?>"></script>
    <!-- The File Upload user interface plugin -->
    <script src="<?php echo base_url('admin-assets/js/fileupload/jquery.fileupload-ui.js')?>"></script>
    <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
    <!--[if gte IE 8]><script src="admin-assets/js/cors/jquery.xdr-transport.js')?>"></script><![endif]-->

    <?php if($is_rtl):?>
    <link href="<?php echo base_url('admin-assets/style/style_rtl.css')?>" rel="stylesheet">
    <?php endif;?>
    
    <!-- Script for this page -->
    <script type="text/javascript">
    
    var timerMap;
    var firstSet = false;
    var savedGpsData;
    var rent_inc_id = '55';
    var map = '';
    var markers = [];
    <?php if(config_db_item('map_version') =='open_street'):?>
    var clusters ='';
    clusters = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
    <?php endif;?>
        
    $(function () {
        
        <?php if(config_db_item('price_by_purpose') == TRUE): ?>
        // Show price by purpose START //    
        $('form.form-estate ul.nav-tabs li a').each(function(){
            var lang_id = $(this).attr('href').substr(1);
            var sel_purpose = $('select[name=option4_'+lang_id+']').find('option:selected').text();
            hide_price_fields(sel_purpose, lang_id);
            
            $('select[name=option4_'+lang_id+']').change(function(){
                var sel_purpose = $(this).find('option:selected').text();

                hide_price_fields(sel_purpose, lang_id);
            })
        });
        
        function hide_price_fields(sel_purpose, lang_id)
        {
            if(sel_purpose=='<?php echo lang_check('Sale')?>')
            {
                $('input[name=option36_'+lang_id+']').parent().parent().show();
                $('input[name=option37_'+lang_id+']').parent().parent().hide();
                $('input[name=option'+rent_inc_id+'_'+lang_id+']').parent().parent().hide();
                
                $('input[name=option37_'+lang_id+']').val('');
                $('input[name=option'+rent_inc_id+'_'+lang_id+']').val('');
            }
            else if(sel_purpose=='<?php echo lang_check('Rent')?>')
            {
                $('input[name=option36_'+lang_id+']').parent().parent().hide();
                $('input[name=option37_'+lang_id+']').parent().parent().show();
                $('input[name=option'+rent_inc_id+'_'+lang_id+']').parent().parent().show();
                
                $('input[name=option36_'+lang_id+']').val('');
            }
            else // Sale and Rent
            {
                $('input[name=option36_'+lang_id+']').parent().parent().show();
                $('input[name=option37_'+lang_id+']').parent().parent().show();
                $('input[name=option'+rent_inc_id+'_'+lang_id+']').parent().parent().show();
            }
        }
        
        // Show price by purpose END //     
        <?php endif; ?>

        $('span.available-langs-sel').click(function(){
            $('#inputLanguage').val($(this).html());
        });
        
        $('.zoom-button').bind("click touchstart", function()
        {
            var myLinks = new Array();
            var current = $(this).attr('href');
            var curIndex = 0;
            
            $('.files-list .zoom-button').each(function (i) {
                var img_href = $(this).attr('href');
                myLinks[i] = img_href;
                if(current == img_href)
                    curIndex = i;
            });

            options = {index: curIndex}

            blueimp.Gallery(myLinks, options);
            
            return false;
        });
        
        loadjQueryUpload();
        
        
        <?php if(config_db_item('map_version') =='open_street'):?>
            var edit_map_marker;
            var edit_map
            if($('#mapsAddress').length){
                if($('#inputGps').length && $('#inputGps').val() != '')
                {
                    savedGpsData = $('#inputGps').val().split(", ");

                    edit_map = L.map('mapsAddress', {
                        center: [parseFloat(savedGpsData[0]), parseFloat(savedGpsData[1])],
                        zoom: 9,
                    });     
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(edit_map);
                    var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(edit_map);
                    edit_map_marker = L.marker(
                        [parseFloat(savedGpsData[0]), parseFloat(savedGpsData[1])],
                        {draggable: true}
                    ).addTo(edit_map);

                    edit_map_marker.on('dragend', function(event){
                        var marker = event.target;
                        var location = marker.getLatLng();
                        var lat = location.lat;
                        var lon = location.lng;
                        $('#inputGps').val(lat+', '+lon);
                        //retrieved the position
                      });

                    firstSet = true;
                }
                else
                {

                    edit_map = L.map('mapsAddress', {
                        center: [<?php echo isset($settings['gps'])?$settings['gps']:'45.81, 15.98'?>],
                        zoom: 9,
                    });     
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(edit_map);
                    var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(edit_map);
                    edit_map_marker = L.marker(
                        [<?php echo isset($settings['gps'])?$settings['gps']:'45.81, 15.98'?>],
                        {draggable: true}
                    ).addTo(edit_map);

                    edit_map_marker.on('dragend', function(event){
                        var marker = event.target;
                        var location = marker.getLatLng();
                        var lat = location.lat;
                        var lon = location.lng;
                        $('#inputGps').val(lat+', '+lon);
                        //retrieved the position
                    });

                    firstSet = true;
                }

                $('#inputAddress').keyup(function (e) {
                    var $ajax_loading = $('.ajax_loading_gps');
                    $ajax_loading.css('display', 'inline-block');
                clearTimeout(timerMap);
                timerMap = setTimeout(function () {
                    $.get('https://nominatim.openstreetmap.org/search?format=json&q='+$('#inputAddress').val(), function(data){
                        if(data.length && typeof data[0]) {
                            /*
                             example with filter by country &addressdetails=1 required in query string
                            var founded = false;
                            for (var index in data){
                                var item = data[index];
                                if(item['address']['country_code']=='hr') {
                                    edit_map_marker.setLatLng([data[0].lat, data[0].lon]).update(); 
                                    edit_map.panTo(new L.LatLng(data[0].lat, data[0].lon));
                                    $('#inputGps').val(data[0].lat+', '+data[0].lon);
                                    founded = true;
                                    break;
                                }
                            };
                            if(!founded) {
                                ShowStatus.show('<?php echo str_replace("'", "\'", lang_check('Address not found!')); ?>');
                                return;
                            }
                            */
                            
                            
                            edit_map_marker.setLatLng([data[0].lat, data[0].lon]).update(); 
                            edit_map.panTo(new L.LatLng(data[0].lat, data[0].lon));
                            $('#inputGps').val(data[0].lat+', '+data[0].lon);
                        } else {
                            ShowStatus.show('<?php echo str_replace("'", "\'", lang_check('Address not found!')); ?>');
                            return;
                        }
                    }).success(function(){$ajax_loading.css('display', 'none');});
                }, 2000);
                
            });
        }
        <?php else:?>

        
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
                    center: [<?php echo isset($settings['gps'])?$settings['gps']:'45.81, 15.98'?>],
                    zoom: 12
                  },
                },
                marker:{
                    values:[
                      {latLng:[<?php echo isset($settings['gps'])?$settings['gps']:'45.81, 15.98'?>]},
                    ],
                    options:{
                      draggable: true
                    },
                    events:{
                        dragend: function(marker){
                          $('#inputGps').val(marker.getPosition().lat()+', '+marker.getPosition().lng());
                        }
                  }}
              });
              
              firstSet = true;
        }
        
        $('#inputAddress').keyup(function (e) {
            clearTimeout(timerMap);
            var $ajax_loading = $('.ajax_loading_gps');
            $ajax_loading.css('display', 'inline-block');
            timerMap = setTimeout(function () {
                
                $("#mapsAddress").gmap3({
                  getlatlng:{
                    address:  $('#inputAddress').val(),
                    callback: function(results){
                      $ajax_loading.css('display', 'none');
                      if ( !results ){
                        //alert('Bad address!');
                        ShowStatus.show('<?php echo str_replace("'", "\'", lang_check('Address not found!'));?>');
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
        
        $('#option_sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: 2,
            isAllowed: function(item, parent) {
                
                // category can be only child of root element
                if($(item).find('.label-danger').length == 1)
                {
                    if($(parent).length > 0)return false;
                }
                
                return true; 
            },
            dropedCallback: sortableDroped
        });
        
        $('#page_sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: $('#page_sortable').attr('rel'),
            isAllowed: function(item, parent) {    
                try
                {
                    var parent_id = parent.attr('rel');
                    
                    if (typeof parent_id !== 'undefined' && parent_id !== false) {
                        //console.log(parent_id+' '+parent.find('span.label-info').length);
                        if(parent_id > 0 && parent.find('span.label-info').length == 0)
                            return false;
                    }
                }
                catch(err)
                {
                    //Handle errors here
                }

                return true; 
            },
            dropedCallback: sortablePageDroped
        });
        
        $('#showroom_sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: $('#showroom_sortable').attr('rel'),
            isAllowed: function(item, parent) {    
                try
                {
                    var parent_id = parent.attr('rel');
                    
                    if (typeof parent_id !== 'undefined' && parent_id !== false) {
                        //console.log(parent_id+' '+parent.find('span.label-info').length);
                        if(parent_id > 0 && parent.find('span.label-info').length == 0)
                            return false;
                    }
                }
                catch(err)
                {
                    //Handle errors here
                }

                return true; 
            },
            dropedCallback: sortableShowroomDroped
        });
        
        $('#expert_sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: $('#expert_sortable').attr('rel'),
            isAllowed: function(item, parent) {    
                try
                {
                    var parent_id = parent.attr('rel');
                    
                    if (typeof parent_id !== 'undefined' && parent_id !== false) {
                        //console.log(parent_id+' '+parent.find('span.label-info').length);
                        if(parent_id > 0 && parent.find('span.label-info').length == 0)
                            return false;
                    }
                }
                catch(err)
                {
                    //Handle errors here
                }

                return true; 
            },
            dropedCallback: sortableExpertDroped
        });
        
        $('.copy_to_next').keydown(function (e) {
            var element_to = $(this).parent().parent().next('div').find('input');
            
            if(element_to.val() == $(this).val().substr(0,$(this).val().length-1))
                element_to.val($(this).val());
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
        });
        
        $('.footable').footable();

    });
    
    function sortableShowroomDroped()
    {
        oSortable = null;
        if($('#showroom_sortable').length)
            oSortable = $('#showroom_sortable').nestedSortable('toArray');
        $.fn.startLoading();
    	$.post('<?php echo site_url('admin/showroom/update_ajax'); ?>', 
        { sortable: oSortable }, 
        function(data){
            $.fn.endLoading();
    	}, "json");
    }
    
    function sortableExpertDroped()
    {
        oSortable = null;
        if($('#expert_sortable').length)
            oSortable = $('#expert_sortable').nestedSortable('toArray');
        $.fn.startLoading();
    	$.post('<?php echo site_url('admin/expert/update_ajax'); ?>', 
        { sortable: oSortable }, 
        function(data){
            $.fn.endLoading();
    	}, "json");
    }
    
    function sortablePageDroped()
    {
        oSortable = null;
        if($('#page_sortable').length)
            oSortable = $('#page_sortable').nestedSortable('toArray');
        $.fn.startLoading();
    	$.post('<?php echo site_url('admin/page/update_ajax'); ?>', 
        { sortable: oSortable }, 
        function(data){
            $.fn.endLoading();
    	}, "json");
    }
    
    function sortableDroped()
    {
        oSortable = null;
        if($('#option_sortable').length)
            oSortable = $('#option_sortable').nestedSortable('toArray');
        $.fn.startLoading();
        
    	$.post('<?php echo site_url('admin/estate/update_ajax'); ?>', 
        { sortable: oSortable }, 
        function(data){
            $.fn.endLoading();
    	}, "json");
    }
    
    $.fn.startLoading = function(data){
        //$('#saveAll, #add-new-page, ol.sortable button, #saveRevision').button('loading');
    }
    
    $.fn.endLoading = function(data){
        //$('#saveAll, #add-new-page, ol.sortable button, #saveRevision').button('reset');       
        <?php if(config_item('app_type') == 'demo'):?>
            ShowStatus.show('<?php echo str_replace("'", "\'", lang('Data editing disabled in demo'));?>');
        <?php else:?>
            //ShowStatus.show('<?php echo lang('data_saved')?>');
        <?php endif;?>
    }
    
    function loadjQueryUpload()
    {
        $('form.fileupload').each(function () {
            $(this).fileupload({
            <?php if(config_item('app_type') != 'demo'):?>
            autoUpload: true,
            <?php endif;?>
            dataType: 'json',
            // The maximum width of the preview images:
            previewMaxWidth: 160,
            // The maximum height of the preview images:
            previewMaxHeight: 120,
            uploadTemplateId: null,
            downloadTemplateId: null,
            uploadTemplate: function (o) {
                var rows = $();
                //return rows;
                $.each(o.files, function (index, file) {
                    /*
                    var row = $('<li class="img-rounded template-upload">' +
                        '<div class="preview"><span class="fade"></span></div>' +
                        '<div class="filename"><code>'+file.name+'</code></div>'+
                        '<div class="options-container">' +
                        '<span class="cancel"><button  class="btn btn-xs btn-warning"><i class="icon-ban-circle icon-white"></i></button></span></div>' +
                        (file.error ? '<div class="error"></div>' :
                                '<div class="progress">' +
                                    '<div class="bar" style="width:0%;"></div></div></div>'
                        )+'</li>');
                    row.find('.name').text(file.name);
                    row.find('.size').text(o.formatFileSize(file.size));
                    */
                    
                    var row = $('<div> </div>');
                    rows = rows.add(row);

                });
                return rows;
            },
            downloadTemplate: function (o) {
                var rows = $();
                $.each(o.files, function (index, file) {
                    var added=false;
                    
                    if (file.error) {
                        ShowStatus.show(file.error);

                    } else {
                        added=true;
                        
                        var row = $('<li class="img-rounded template-download fade">' +
                            '<div class="preview"><span class="fade"></span></div>' +
                            '<div class="filename"><code>'+file.short_name+'</code></div>'+
                            '<div class="options-container">' +
                            (file.zoom_enabled?
                                '<a data-gallery="gallery" class="zoom-button btn btn-xs btn-success" download="'+file.name+'"><i class="icon-search icon-white"></i></a>'
                                : '<a target="_blank" class="btn btn-xs btn-success" download="'+file.name+'"><i class="icon-search icon-white"></i></a>') +
                            ' <span class="delete"><button class="btn btn-xs btn-danger" data-type="'+file.delete_type+'" data-url="'+file.delete_url+'"><i class="icon-trash icon-white"></i></button>' +
                            ' <input type="checkbox" value="1" name="delete"></span>' +
                            '</div>' +
                            (file.error ? '<div class="error"></div>' : '')+'</li>');
                        
                        
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
                    
                    if(added)
                        rows = rows.add(row);
                });
                
                return rows;
            },
            destroyed: function (e, data) {
                $.fn.endLoading();
                <?php if(config_item('app_type') != 'demo'):?>
                if(data.success)
                {
                }
                else
                {
                    ShowStatus.show('<?php echo _js(lang_check('Unsuccessful, possible permission problems or file not exists')); ?>');
                }
                <?php endif;?>
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
                    
                    $('.files-list .zoom-button').each(function (i) {
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
    
    </script>
    <?php if(config_item('onmouse_gallery_enabled') === TRUE): ?>    
    <style>
    .onmouse_gallery-title-files {
        padding: 0;
        margin: 0;
        list-style: none;
        height: 30px;
        overflow: hidden;
        width: 100%;
    }
    
    .onmouse_gallery-title-files li {
        color: red;
        float:left;
        margin: 3px 3px 3px 0;
        padding: 1px;
        display: block;
        float: left;
        width: 164px;
    }
    
    .onmouse_gallery-notice {
        color: red;
        font-size: 16px;
        margin-bottom: 5px;
    }
    </style>
    <?php endif;?>
    
    <script>
    var location_hash = '';

    // to top right away
    if ( window.location.hash ) {
        location_hash = window.location.hash;
        window.location.hash ='';
    };

    </script>

    <?php if(file_exists(FCPATH.'/templates/'.$settings['template'].'/assets/css/admin_template.css')):?>
    <link href="<?php echo base_url('templates/'.$settings['template'].'/assets/css/admin_template.css')?>" rel="stylesheet">
    <?php endif;?>
</head>