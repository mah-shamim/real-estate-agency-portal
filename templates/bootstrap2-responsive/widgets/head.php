    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{page_title}</title>
    <meta name="description" content="{page_description}" />
    <meta name="keywords" content="{page_keywords}" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo $website_favicon_url;?>" type="image/png" />
    <link rel="image_src" href="assets/img/logo.png" />
    
    <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo site_url('api/rss/'.$lang_code); ?>" />
    <link rel="canonical" href="<?php echo slug_url(uri_string());?>" />
    
    <?php if(isset($slideshow_property_images[0]['url'])): ?>
    <meta property="og:image" content="<?php echo $slideshow_property_images[0]['url']; ?>" />
    <?php else: ?>
    <meta property="og:image" content="assets/img/logo-transparent-og.png" />
    <?php endif; ?>
    <meta property="og:title" content="{page_title}" />
    
    <?php if(config_db_item('multi_domains_enabled') === TRUE): ?>
    <?php foreach($this->language_m->db_languages_code_obj as $lang_obj): ?>
    <?php if(!empty($lang_obj->domain) && substr_count($lang_obj->domain, 'http') > 0): ?>
    <link rel="alternate" hreflang="<?php echo $lang_obj->code; ?>" href="<?php echo $lang_obj->domain; ?>"/>
    <?php endif;endforeach;endif; ?>

    <!-- Le styles -->
    <link href="assets/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    
    <?php if(config_item('disable_responsive') === TRUE): ?>
    <?php else: ?>
    <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
    <?php endif; ?>

    <link href="assets/css/styles.css" rel="stylesheet">
    
    <?php if(config_item('disable_responsive') === TRUE): ?>
    <?php else: ?>
    <link href="assets/css/enable-responsive.css" rel="stylesheet">
    <?php endif; ?>
    
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
    
    <?php if(config_item('disable_responsive') === TRUE): ?>
    <link href="assets/css/disable-responsive.css" rel="stylesheet">
    <?php else: ?>
    <?php endif; ?>
    
    <link href="assets/css/custom.css" rel="stylesheet">
   
    <?php load_map_api(config_db_item('map_version'), $lang_code);?>
    
    <script src="assets/js/jquery-1.8.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <?php if(config_db_item('map_version') !='open_street'):?>
    <script src="assets/js/gmap3.js"></script>
     <?php endif;?>
    <script src="assets/js/bootstrap-select.js"></script>
    
    <script src="assets/js/blueimp-gallery.min.js"></script>
    <script src="assets/js/jquery.helpers.js"></script>
    <script src="assets/js/jquery.placeholder.min.js"></script>
    
    <script src="assets/js/jquery.number.js"></script>
    <script src="assets/js/jquery.h5validate.js"></script>
    
    <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/dpejes/dpe.js')): ?>
    <script src="assets/js/dpejes/dpe.js"></script>
    <?php endif; ?>
    
    <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/jquery-contact-tabs/js/jquery.contact.tabs.1.0.js')): ?>
        <script src="assets/js/jquery-contact-tabs/js/jquery.contact.tabs.1.0.js"></script>
        <link href="assets/js/jquery-contact-tabs/css/dcjct.css" rel="stylesheet" type="text/css" />
        <script>
        
        $(document).ready(function($){
        	$('#contact-tabs').dcContactTabs({
            		tabs: [{
            			form: {
            				intro: {type: 'textblock', text: 'This is an example of a contact form with phone.'},
            				name: {type: 'text', label: 'Your name', validate: 'required,fake'},
            				emailfrom: {type: 'emailfrom', label: 'Your email'},
            				phone: {type: 'text', label: 'Your phone', validate: 'required'},
            				message: {type: 'textarea', label: 'Your message', validate: 'required'},
            				url: {type: 'url'},
            				ip: {type: 'ip'},
            				submit: {type: 'submit', text: 'Submit'}
            			},
            			title: 'Contact Form',
            			subject: 'Contact Form',
            			icon: 'mail.png',
            			success: 'Thank you! Your message has been received'
            		}],
                    errors: {
    					required: 'required',
    					email: 'enter a valid email',
    					numeric: 'numbers only',
    					fake: 'valid text only',
    					send: 'There has been an error processing your email. Please try again'
				    },
                    height: 500,
                    location: 'left',
                    align: 'top',
                    offset: 88,
                    //loadOpen: true,
                    width: 280,
                    imagePath: 'assets/js/jquery-contact-tabs/images/icons/',
                    action: 'assets/js/jquery-contact-tabs/email.php'
            });
        });
        </script>
    <?php endif; ?>

    <?php if(config_db_item('appId') != '' && file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/like2unlock/js/jquery.op.like2unlock.min.js')): ?>
    <script src="assets/js/like2unlock/js/jquery.op.like2unlock.min.js"></script>
    <link rel="stylesheet" href="assets/js/like2unlock/css/jquery.op.like2unlock.min.css" />
    <?php endif; ?>

    <script src="assets/js/nouislider/nouislider.min.js"></script>
    <link rel="stylesheet" href="assets/js/nouislider/nouislider.min.css" />
    
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
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <?php if(config_item('ad_gallery_enabled') === TRUE): ?>
        <link rel="stylesheet" type="text/css" href="assets/js/adgallery/jquery.ad-gallery.css" />
        <script src="assets/js/adgallery/jquery.ad-gallery.js"></script>
    <?php endif; ?>
    
    <?php if(config_db_item('agent_masking_enabled') == TRUE || config_db_item('report_property_enabled') == TRUE): ?>
        <link rel="stylesheet" type="text/css" href="assets/js/magnific-popup/magnific-popup.css" /> 
        <script src="assets/js/magnific-popup/jquery.magnific-popup.js"></script> 
    <?php endif; ?>
    
    <?php if(file_exists(APPPATH.'controllers/admin/swin_reviews.php')): ?>
        <script src="assets/js/ratings/bootstrap-rating-input.js"></script> 
    <?php endif; ?>
    
    <script src="assets/js/jquery.custom.js"></script>
    
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
        var rent_inc_id = '55';
        var scrollWheelEnabled = false;
        var myLocationEnabled = true;
        var rectangleSearchEnabled = true;
        <?php if(config_db_item('map_version') !='open_street'):?>
        var c_mapTypeId = "style1"; // google.maps.MapTypeId.ROADMAP;
        var c_mapTypeIds = ["style1",
                            google.maps.MapTypeId.ROADMAP,
                            google.maps.MapTypeId.HYBRID];    
        <?php endif;?>             
                      
        //google.maps.MapTypeId.ROADMAP
        //google.maps.MapTypeId.SATELLITE
        //google.maps.MapTypeId.HYBRID
        //google.maps.MapTypeId.TERRAIN
        
        var selectorResults = '.results-properties-list';
        var markers = [];
        var map = '';
        <?php if(config_db_item('map_version') =='open_street'):?>
        var clusters ='';
        clusters = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
        <?php endif;?>
             
        $(document).ready(function()
        {
            // Cluster config start //
            clusterConfig = {
              radius: 60,
              // This style will be used for clusters with more than 2 markers
//              2: {
//                content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
//                width: 53,
//                height: 52
//              },
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
            <?php if(config_item('google_map_poi_enable') ===FALSE):?>
            mapStyle.push({"featureType": "poi","stylers": [{ "visibility": "off" }]});
            <?php endif;?>
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
            
            if($(selectorResults).length <= 0)
                selectorResults = '.wrap-content .container';
            
            // Calendar translation End //
            
            // Slider range Start //   
            // Slider range End //
            
            // [START] Save search //  
            
            $("#search-save").click(function(){
                manualSearch(0, '#content', true);
                
                return false;
            });
            
            // [END] Save search //
            
            <?php if(config_db_item('agent_masking_enabled') == TRUE && isset($property_id) && isset($agent_id)): ?>
            // Popup form Start //
                /* visible */
                $('.hidden-agent-details,.hidden-file-details').css('display','block');
                
                $('.popup-with-form').magnificPopup({
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
                
                
                $('#unhide-agent-mask').click(function(){
                    
                    var data = $('#test-form').serializeArray();
                    data.push({name: 'property_id', value: "<?php echo $property_id; ?>"});
                    data.push({name: 'agent_id', value: "<?php echo $agent_id; ?>"});
                    
                    //console.log( data );
                    $('#ajax-indicator-masking').css('display', 'inline');
                    
                    // send info to agent
                    $.post("<?php echo site_url('frontend/maskingsubmit/'.$lang_code); ?>", data,
                    function(data){
                        if(data=='successfully')
                        {
                            // Display agent details
                            $('.popup-with-form').css('display', 'none');
                            // Close popup
                            $.magnificPopup.instance.close();
                        }
                        else
                        {
                            $('.alert.hidden').css('display', 'block');
                            $('.alert.hidden').css('visibility', 'visible');
                            
                            $('#popup-form-validation').html(data);
                            
                            //console.log("Data Loaded: " + data);
                        }
                        $('#ajax-indicator-masking').css('display', 'none');
                    });

                    return false;
                });
                
            <?php endif; ?>
            // Popup form End //      
            
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
                  
            
            // Filters Start //
            
            $(".checkbox_am").click((function(){
                var option_id = $(this).attr('data-option_id');
                
                if($(this).prop('checked'))
                {
                    $("#search_option_"+option_id).prop('checked', true);
                }
                else
                {
                    $("#search_option_"+option_id).prop('checked', false);
                }
                //console.log(option_id);
            }));
            
            $(".input_am").focusout((function(){
                var option_id = $(this).attr('data-option_id');
                
                if($(this).hasClass('multi_am')) {
                    $("#search_option_"+option_id+"_multi").val($(this).val());
                } else {
                    $("#search_option_"+option_id).val($(this).val());
                }
                
                //console.log(option_id);
            }));
            
            $(".input_am_from").focusout((function(){
                var option_id = $(this).attr('data-option_id');
                $("#search_option_"+option_id+"_from").val($(this).val());
                //console.log(option_id);
            }));
            
            $(".input_am_to").focusout((function(){
                var option_id = $(this).attr('data-option_id');
                
                $("#search_option_"+option_id+"_to").val($(this).val());
                //console.log(option_id);
            }));
            
            <?php if(empty($_GET['search']) && empty($search_query)): ?>
            $(".checkbox_am, .search-form .advanced-form-part label.checkbox input").prop('checked', false);
            $(".input_am, .input_am_from, .input_am_to, .search-form input[type=text]/*, .search-form select*/").val('');
            <?php endif; ?>
            
            $('.search-form select.selectpicker').selectpicker('render');
            
            $("button.refresh_filters").click(function () { 
                manualSearch(0);
                return false;
            });
            
            showCounters([]);
            // Filters End //            
            
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
            
            $('#search-start-map').click(function () { 
              $('#wrap-map-1').attr('id', 'wrap-map').empty();
              if(!map)
                  map ='init';
              
              manualSearch(0, false);
              return false;
            });
            
            /*
            $(".scroll").click(function(event){
                 event.preventDefault();
                 //calculate destination place
                 var dest=0;
                 if($(this.hash).offset().top > $(document).height()-$(window).height()){
                      dest=$(document).height()-$(window).height();
                 }else{
                      dest=$(this.hash).offset().top;
                 }
                 //go to destination
                 $('html,body').animate({scrollTop:dest}, 2000,'swing');
             });
             
             */

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
                if(!$('.menu-onmap li:first').hasClass('list-property-button'))
                    $('.menu-onmap li:first').addClass('active');
            }
            <?php else: ?>
            if($('.menu-onmap li.active').length == 0)
            {
                $('.menu-onmap li.all-button').addClass('active');
            }
            <?php endif; ?>
            
            $('#search-start,.sw_search-start').click(function (e) { 
                e.preventDefault();
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

            $('.datetimepicker_standard').datepicker().on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });

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
            
            // If alredy selected
            
            <?php if(config_db_item('map_version') =='open_street'):?>
            var edit_map_marker;
            var edit_map
            if($('#mapsAddress').length){
                if($('#inputGps').length && $('#inputGps').val() != '')
                {
                    savedGpsData = $('#inputGps').val().split(", ");

                    edit_map = L.map('mapsAddress', {
                        center: [parseFloat(savedGpsData[0]), parseFloat(savedGpsData[1])],
                        zoom: {settings_zoom}+1,
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
                        center: [{settings_gps}],
                        zoom: {settings_zoom}+1,
                    });     
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(edit_map);
                    var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(edit_map);
                    edit_map_marker = L.marker(
                        [{settings_gps}],
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
                clearTimeout(timerMap);
                timerMap = setTimeout(function () {
                    $.get('https://nominatim.openstreetmap.org/search?format=json&q='+$('#inputAddress').val(), function(data){
                        if(data.length && typeof data[0]) {
                            edit_map_marker.setLatLng([data[0].lat, data[0].lon]).update(); 
                            edit_map.panTo(new L.LatLng(data[0].lat, data[0].lon));
                            $('#inputGps').val(data[0].lat+', '+data[0].lon);
                        } else {
                            ShowStatus.show('<?php echo str_replace("'", "\'", lang_check('Address not found!')); ?>');
                            return;
                        }
                    });
                }, 2000);
                
            });
            }
            <?php else:?>
            
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
                          if($("#inputAddress").attr("readonly"))
                          {
                            alert('<?php _l('Location change disabled', '', array("'"=>"\'")); ?>');
                            return false;
                          }
                          else
                          {
                            $('#inputGps').val(marker.getPosition().lat()+', '+marker.getPosition().lng());
                          }
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
                    },
                marker:{
                    values:[
                      {latLng:[{settings_gps}]},
                    ],
                    options:{
                      draggable: true
                    },
                    events:{
                        dragend: function(marker){
                          if($("#inputAddress").attr("readonly"))
                          {
                            alert('<?php _l('Location change disabled'); ?>');
                            return false;
                          }
                          else
                          {
                            $('#inputGps').val(marker.getPosition().lat()+', '+marker.getPosition().lng());
                          }
                        }
                  }}
                  });
                  
                  firstSet = true;
            }
                
            $('#inputAddress').keyup(function (e) {
                clearTimeout(timerMap);
                timerMap = setTimeout(function () {
                    
                    $("#mapsAddress").gmap3({
                      getlatlng:{
                        address:  $('#inputAddress').val(),
                        callback: function(results){
                          if ( !results ){
                            ShowStatus.show('<?php echo str_replace("'", "\'", lang_check('Address not found!')); ?>');
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
                                  if($("#inputAddress").attr("readonly"))
                                  {
                                    alert('<?php _l('Location change disabled'); ?>');
                                    return false;
                                  }
                                  else
                                  {
                                    $('#inputGps').val(marker.getPosition().lat()+', '+marker.getPosition().lng());
                                  }
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
            
            $('.tabbable li.rtab a').click(function () { 
                var tab_width = 0;
                var tab_width_real = 0;
                $('.tab-content').find('div.cleditorToolbar:first .cleditorGroup').each(function (i) {
                    tab_width += $(this).width();
                });
                
                tab_width_real = $('.tab-content').find('div.cleditorToolbar').width();
                var rows = parseInt(tab_width/tab_width_real+1)
                
                $('.tab-content').find('div.cleditorToolbar').height(rows*27);
                
                try {
                    $('.tab-content').find('.cleditor').refresh();
                }
                catch(err) {
                    // console.log(err.message);
                    // $(...).find(...).refresh is not a function
                }
                
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
            
            <?php if(config_item('enable_ajax_url') == true): ?>
            if(support_history_api() == true)
            {
                // Adding popstate event listener to handle browser back button  
                window.addEventListener("popstate", function(e) {
                    // Get State value using e.state
                    //console.log(e.state);
                    
                    // if return NULL
                    if(typeof e.state !== 'undefined'&& e.state!== null && e.state)
                        window.location.reload();
                });
            }
            <?php endif; ?>  
            
            // palceholder fix for IE 9
            $('input, textarea').placeholder();
            
            // Universal pagination solution
            reloadPaginationUniversal();
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
        
        function reloadPaginationUniversal()
        {
            $('.pagination-ajax-results a').click(function () { 
                var page_num = $(this).attr('href');
                var n = page_num.lastIndexOf("/"); 
                page_num = page_num.substr(n+1);
                
                var results_dom_id = '#'+$(this).parent().parent().parent().attr('rel');
                
                $.post($(this).attr('href'), {'page_num':page_num}, function(data){
                    $(results_dom_id).html(data.print);
                    
                    reloadPaginationUniversal();
                }, "json");
                
                return false;
            });
        }
        
        function manualSearch(v_pagenum, scroll_enabled, onlysave)
        {
            if (typeof scroll_enabled === 'undefined') scroll_enabled = "#content";
            if (typeof onlysave === 'undefined') onlysave = false;
            
            // Order ASC/DESC
            var v_order = $('.selectpicker-small').val();
            
            // View List/Grid
            var v_view = $('.view-type.active').attr('data-ref');          
            
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
            /*
            if($('#search_option_4 .active a').length > 0)
            {
                data['v_search_option_4'] = $('#search_option_4 .active a').html();
            }
            */
            
            // Improved tabbed selector code
            $(".tabbed-selector").each(function() {
              var selected_text = $(this).find(".active:not(.all-button) a").html();
              data['v_'+$(this).attr('id')] = selected_text;
            });
            
            // Add custom data values, automatically by fields inside search-form
            $('.search-form form input, .search-form form select').each(function (i) {
                if($(this).attr('type') == 'checkbox')
                {
                    if ($(this).attr('checked'))
                    {
                        data['v_'+$(this).attr('id')] = $(this).val();
                    }
                }
                else if($(this).hasClass('tree-input'))
                {
                    if($(this).val() != '')
                    {
                        var tre_id_split = $(this).attr('id').split('_');
                        //console.log($(this).find("option:selected").attr('value'));
                        //console.log(tre_id_split);
                        if(data['v_search_option_'+tre_id_split[2]] == undefined)
                            data['v_search_option_'+tre_id_split[2]] = '';
                        
                        data['v_search_option_'+tre_id_split[2]]+= $(this).find("option:selected").text()+' - ';
                    }
                }
                else
                {
                    if(typeof $(this).attr('id') !='undefined' && $(this).attr('id') !='undefined' && $(this).val() != '' && $(this).val() != 'NULL' && $(this).val() != null){
                        data['v_'+$(this).attr('id')] = $(this).val();
                    }
                }
            });
            
            // Custom tags filter Start
            if($('#tags-filters').length > 0)
            {
                var tags_html = '';
                
                // Add custom data values, automatically by fields inside search-form
                $('.search-form form input, .search-form form select').each(function (i) {
                    if($(this).attr('type') == 'checkbox')
                    {
                        if ($(this).attr('checked'))
                        {
                            data['v_'+$(this).attr('id')] = $(this).val();
                            
                            var option_name = '';
                            //var attr = $(this).attr('placeholder');
                            var attr = $(this).attr('value').substring(4);
                            if(typeof attr !== 'undefined' && attr !== false)
                            {
                                option_name = attr;
                            }
                            
                            if($(this).val() != '' && $(this).val() != null)
                                tags_html+='<button class="btn btn-small btn-warning filter-tag ck" rel="'+$(this).attr('id')+'" type="button"><span class="icon-remove icon-white"></span> '+option_name+'</button>&nbsp;';
                        
                        }
                    }
                    else if($(this).hasClass('tree-input'))
                    {
                        // different way
                    }
                    else
                    {
                        data['v_'+$(this).attr('id')] = $(this).val();
                        
                        var option_name = '';
                        var attr = $(this).attr('placeholder');
                        if(typeof attr !== 'undefined' && attr !== false)
                        {
                            option_name = attr+': ';
                        }
                        
                        if($(this).val() != '' && $(this).val() != null)
                            tags_html+='<button class="btn btn-small btn-primary filter-tag" rel="'+$(this).attr('id')+'" type="button"><span class="icon-remove icon-white"></span> '+option_name+$(this).val()+'</button>&nbsp;';
                    
                    }
                });
                
                if(typeof data['v_search_option_4'] != 'undefined')
                if(data['v_search_option_4'].length > 0)
                    tags_html+='<button class="btn btn-small btn-danger filter-tag" rel="4" type="button"><span class="icon-remove icon-white"></span> '+data['v_search_option_4']+'</button>&nbsp;';
                
                if(tags_html != '')
                {
                    $("#tags-filters").css('display', 'block');
                    
                    if(tags_html.indexOf("script") == -1)
                    {
                        $("#tags-filters").html(tags_html);
                    }
                    else
                    {
                        return;
                    }
                    
                    $(".filter-tag").click(function(){
                        var m_id = $(this).attr('rel').substring(14);
                        
                        if($(this).hasClass('ck'))
                        {
                            $('#'+$(this).attr('rel')).prop('checked', false);
                        }
                        else
                        {
                            $("input.id_"+m_id).val('');
                            $("input#"+$(this).attr('rel')).val('');
                            
                            $("select#"+$(this).attr('rel')).val('');
                            $("select.id_"+m_id).val('');
                            $("select#"+$(this).attr('rel')+".selectpicker").selectpicker('render');
                            $("select.id_"+m_id+".selectpicker").selectpicker('render');
                        }
                        
                        $(this).remove();
                        
                        
                        if($(this).attr('rel') == '4')
                        {
                            $('#search_option_4 .active').removeClass('active');
                        }
                        
                        if($(this).hasClass('ck'))
                        {
                            $("input.checkbox_am[data-option_id='"+m_id+"']").prop('checked', false);
                        }
                        
                        manualSearch(0);
                    });
                }
                else
                {
                    $("#tags-filters").css('display', 'none');
                }
            }
            // Custom tags filter End
            
            /* suctom field infinity scale */
            /*
            if(/\+$/.test(data['v_search_option_36_to'])) {
                data['v_search_option_36_to']='';
            }
            
            if($('#search_option_36_to').length)
            data['v_search_option_36_to']=parseInt($.number(data['v_search_option_36_to'].replace("{options_prefix_36}", "").replace("{options_suffix_36}", "")).replace(",", ""));
            
            if($('#search_option_36_from').length)
            data['v_search_option_36_from']=parseInt($.number(data['v_search_option_36_from'].replace("{options_prefix_36}", "").replace("{options_suffix_36}", "")).replace(",", ""));
            
            if(data['v_search_option_36_to']=='0')
                 data['v_search_option_36_to']='';
            if(data['v_search_option_36_from']=='0')
                 data['v_search_option_36_from']='';
             */
            
             $('.scale-input').each(function(i){
                var _prefix='',_suffix='';
                
                var self = $(this);
                var _option= 'v_'+self.attr('id'); // search key
                
                if(self.attr('data-prefix'))
                    _prefix = self.attr('data-prefix');
                
                if(self.attr('data-suffix'))
                    _suffix = self.attr('data-suffix');
                
                if(/\+$/.test(self)) {
                    data[_option]='';
                }
            
                if(self.length)
                    data[_option]=parseInt($.number(data[_option].replace(_prefix, "").replace(_prefix, "").replace(/\./g, ",")).replace(/,/g,""));
                
                if(data[_option]=='0')
                 data[_option]='';
             })
             
            /* end suctom field infinity scale */
            
            
            
            
            
            $("#ajax-indicator-1").show();
            
            if(onlysave == true)
            {
                $.post("{api_private_url}/save_search/{lang_code}", data, 
                       function(data){
                    //console.log(data);
                    //console.log(data.message);
                    
                    ShowStatus.show(data.message);
                                    
                    $("#ajax-indicator-1").hide();
                });
                
                return;
            }
            
            <?php if(config_item('enable_ajax_url') == true): ?>
            if(support_history_api() == true)
            {
                if(data.page_num)
                    data.page_num = data.page_num.replace("#content", "");
                    
                /* fix for search_category_21 select */
                var new_data = {};
                $.each(data, function(i,v){
                    if(i.indexOf('category') != -1) {
                        if($('#'+i.substr(2)).length && v){
                            var sel = $('#'+i.substr(2));
                            $.each(v, function(c_i,c_v){
                                var option_id = sel.find('option[value="'+c_v+'"]').attr('data-input_id')
                                if(option_id) {
                                    new_data['v_search_option_'+option_id]=c_v
                                }
                            })
                        }
                    } else {
                        if(v!="")
                            new_data[i] = v;
                    }
                })
            	var json_string=JSON.stringify(new_data);
                
                json_string = json_string.replace("&amp;", "%26"); 
                
                if(window.history && history.pushState)
                    history.pushState(data, '', '{page_current_url}?search='+json_string);
            }
            <?php endif; ?>
            
            
            <?php if(config_item('search_listing_page')&&$page_id!=config_item('search_listing_page')): ?>
                
                if( data['v_search_radius']==0)
                    data['v_search_radius'] ='';
                <?php
                
                // get title;
                $CI =& get_instance();
               $CI->load->model('page_m');
               $_page = $CI->page_m->get_lang(config_item('search_listing_page'),false,$lang_id);
                $_title=$_page->{'navigation_title_'.$lang_id};
                
                ?>
                window.location.href='<?php echo site_url($lang_code.'/'.config_item('search_listing_page').'/'.url_title_cro($_title, '-', TRUE))?>?search='+JSON.stringify(data);
                return false;
                
            <?php endif;?>
            
            showCounters(data); 
            
            $.post("{ajax_load_url}/"+v_pagenum, data,
            function(data){
                
                if(mapRefresh && map)
                {
                    <?php if(config_db_item('map_version') =='open_street'):?>
                    if(map=="init") {       
                        map = L.map('wrap-map', {
                            <?php if(config_item('custom_map_center') === FALSE): ?>
                            center: [{all_estates_center}],
                            <?php else: ?>
                            center: [<?php echo config_item('custom_map_center'); ?>],
                            <?php endif; ?>
                            zoom: {settings_zoom}+1,
                            scrollWheelZoom: scrollWheelEnabled,
                            dragging: !L.Browser.mobile,
                            tap: !L.Browser.mobile
                        });     
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(map);
                        map.addLayer(clusters);
                    }
                                
                    //Loop through all the markers and remove
                    for (var i = 0; i < markers.length; i++) {
                        clusters.removeLayer(markers[i]);
                    }
                    markers = [];

                    if(data.results.length > 0)
                    {
                        $.each(data.results, function(index, listing) {
                            /* fix if missing latLng */
                            if(typeof listing.latLng == 'undefined'){
                                return;
                            }
                            
                            var marker = L.marker(
                                [listing.latLng[0], listing.latLng[1]],
                                {icon: L.divIcon({
                                        html: '<img src="'+listing.options.icon+'">',
                                        className: 'open_steet_map_marker '+data.results[index].options.cssclass,
                                        iconSize: [31, 46],
                                        popupAnchor: [1, -35],
                                        iconAnchor: [15, 45],
                                    })
                                }
                            )/*.addTo(map)*/;

                            marker.bindPopup(listing.data);
                            clusters.addLayer(marker);
                            markers.push(marker);
                        })
                        
                        <?php if(config_db_item('map_fixed_position') == FALSE): ?>
                        /* set center */
                        if(markers.length){
                            var limits_center = [];
                            for (var i in markers) {
                                if(typeof markers[i]['_latlng'] == 'undefined') continue;
                                var latLngs = [ markers[i].getLatLng() ];
                                limits_center.push(latLngs)
                            };
                            var bounds = L.latLngBounds(limits_center);
                            <?php if(config_db_item('auto_set_zoom_disabled') != FALSE): ?>
                                map.setView(bounds.getCenter());
                           <?php else:?>
                                map.fitBounds(bounds);
                           <?php endif;?>
                        }
                        <?php endif;?>
                    }
                    <?php else:?>

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
                                   mapTypeIds: c_mapTypeIds,
                                   position: google.maps.ControlPosition.TOP_RIGHT
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
                    
                    <?php endif;?>
                }
                
                $(selectorResults).html(data.print);
                    reloadElements();
                
                $("#ajax-indicator-1").hide();
                if(scroll_enabled != false && $(scroll_enabled).length)
                    $(document).scrollTop( $(scroll_enabled).offset().top );
                
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
            ShowStatus.show('<?php echo str_replace("'", "\'", lang('Data editing disabled in demo')); ?>');
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
                    /*
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
                    */
                    var row = $('<div> </div>');
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
                    
                    var added=false;
                    
                    if (file.error) {
                        ShowStatus.show(file.error);
                        
//                        row.find('.name').text(file.name);
//                        row.find('.error').text(
//                            file.error
//                        );
                    } else {
                        added=true;
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
                    ShowStatus.show('<?php echo lang_check('Unsuccessful, possible permission problems or file not exists'); ?>');
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
            
            if(rectangleSearchEnabled)
            {
                var controlDiv2 = document.createElement('div');
                controlDiv2.index = 2;
                map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv2);
                RectangleControl(controlDiv2, map)
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
                
                if(true){
                    map_rectangle = map;
                    if(rectangle != null) {
                        $('#rectangle_ne').val('');
                        $('#rectangle_sw').val('');
                        infoWindow_rectangle.setMap(null);
                        rectangle.setMap(null);
                        rectangle = null;
                    }
                    var map_zoom = map.getZoom();
                    var map_center = map.getCenter();

                    var size_index = 0.4;

                    if(map_zoom > 11)
                      size_index = 0.02

                    var bounds = new google.maps.LatLngBounds(
                        new google.maps.LatLng(map_center.lat()-(size_index/2), map_center.lng()-size_index),
                        new google.maps.LatLng(map_center.lat()+(size_index/2), map_center.lng()+size_index)
                    );
                    // Define the rectangle and set its editable property to true.
                    rectangle = new google.maps.Rectangle({
                      bounds: bounds,
                      editable: true,
                          draggable: true
                    });
                    rectangle.setMap(map);
                    // Add an event listener on the rectangle.
                    google.maps.event.addListener(rectangle, 'bounds_changed', showNewRect);
                    // Define an info window on the map.
                    infoWindow_rectangle = new google.maps.InfoWindow();
                    // define first rectangle dimension
                    var ne = rectangle.getBounds().getNorthEast();
                    var sw = rectangle.getBounds().getSouthWest();
                    $('#rectangle_ne').val(ne.lat() + ', ' + ne.lng());
                    $('#rectangle_sw').val(sw.lat() + ', ' + sw.lng());
                }
                
            }, function(error) {
                console.log(error);
            });
          });
        }
        
        var rectangle;
        var infoWindow_rectangle;
        var map_rectangle;
        
        function RectangleControl(controlDiv2, map) {
          
          map_rectangle = map;
          
          // Set CSS styles for the DIV containing the control
          // Setting padding to 5 px will offset the control
          // from the edge of the map.
          controlDiv2.style.padding = '5px';
        
          // Set CSS for the control border.
          var controlUI = document.createElement('div');
          controlUI.style.backgroundColor = 'white';
          controlUI.style.borderStyle = 'solid';
          controlUI.style.borderWidth = '2px';
          controlUI.style.cursor = 'pointer';
          controlUI.style.textAlign = 'center';
          controlUI.title = '{lang_DrawRectangle}';
          controlDiv2.appendChild(controlUI);
        
          // Set CSS for the control interior.
          var controlText = document.createElement('div');
          controlText.style.fontFamily = 'Arial,sans-serif';
          controlText.style.fontSize = '12px';
          controlText.style.paddingLeft = '4px';
          controlText.style.paddingRight = '4px';
          controlText.innerHTML = '<strong>{lang_DrawRectangle}</strong>';
          controlUI.appendChild(controlText);
        
          // Setup the click event listeners: simply set the map to Chicago.
          google.maps.event.addDomListener(controlUI, 'click', function() {
              
              if(rectangle != null) {
                /*console.log('is_open');*/
                
                $('#rectangle_ne').val('');
                $('#rectangle_sw').val('');
                infoWindow_rectangle.setMap(null);
                rectangle.setMap(null);
                rectangle = null;
                return false;  
              }
              
              var map_zoom = map.getZoom();
              var map_center = map.getCenter();
                
              var size_index = 0.4;
              
              if(map_zoom > 11)
                size_index = 0.02
                
              var bounds = new google.maps.LatLngBounds(
                  map_center,
                  new google.maps.LatLng(map_center.lat()+size_index, map_center.lng()+size_index*2)
              );
            
              // Define the rectangle and set its editable property to true.
              rectangle = new google.maps.Rectangle({
                bounds: bounds,
                editable: true,
                draggable: true
              });
            
              rectangle.setMap(map);
            
              // Add an event listener on the rectangle.
              google.maps.event.addListener(rectangle, 'bounds_changed', showNewRect);
            
              // Define an info window on the map.
              infoWindow_rectangle = new google.maps.InfoWindow();
              
              // define first rectangle dimension
              var ne = rectangle.getBounds().getNorthEast();
              var sw = rectangle.getBounds().getSouthWest();
              $('#rectangle_ne').val(ne.lat() + ', ' + ne.lng());
              $('#rectangle_sw').val(sw.lat() + ', ' + sw.lng());
            
          });
        }
        
        // Show the new coordinates for the rectangle in an info window.
        
        /** @this {google.maps.Rectangle} */
        function showNewRect(event) {
          var ne = rectangle.getBounds().getNorthEast();
          var sw = rectangle.getBounds().getSouthWest();
        
          var contentString = '<b><?php echo lang_check('Rectangle moved'); ?>:</b><br>' +
              '<?php echo lang_check('New north-east corner'); ?>: ' + ne.lat() + ', ' + ne.lng() + '<br>' +
              '<?php echo lang_check('New south-west corner'); ?>: ' + sw.lat() + ', ' + sw.lng();
          
          $('#rectangle_ne').val(ne.lat() + ', ' + ne.lng());
          $('#rectangle_sw').val(sw.lat() + ', ' + sw.lng());
          
          // Set the info window's content and position.
          infoWindow_rectangle.setContent(contentString);
          infoWindow_rectangle.setPosition(ne);
        
          infoWindow_rectangle.open(map_rectangle);
        }


        /* [START] NumericFields */
        
        $(function() {
            <?php if(config_db_item('swiss_number_format') == TRUE): ?>
            
            $('input.DECIMAL').number( true, 2, '.', '\'' );
            $('input.INTEGER').number( true, 0, '.', '\'' );
             
            <?php else: ?>
            
            $('input.DECIMAL').number( true, 2 );
            $('input.INTEGER').number( true, 0 );
            
            <?php endif; ?>
        });
    
        /* [END] NumericFields */
        
        /* [START] ValidateFields */
        
        $(function() {
            $('form.form-estate').h5Validate({
                invalidCallback: function () {
                    $('#ajax-indicator-1').hide();
                }
            });
        });
        
        /* [END] ValidateFields */
        
        function showCounters(data_params)
        {
            // load json
            
            //var data_post = $('#test-form').serializeArray();
            //data.push({name: 'property_id', value: "<?php //echo $property_id; ?>"});
            //data.push({name: 'agent_id', value: "<?php //echo $agent_id; ?>"});
            
            //console.log('data_params');
            //console.log(data_params);

            $.post('<?php echo site_url('api/get_all_counters/'.$lang_id.'/3')?>', data_params, function(data){
                //console.log('data');
                //console.log(data);
                
                // remove all
                $("input.checkbox_am").parent().find('span').html('');
                
                $.each(data.counters, function( index, obj ) {
                  //console.log(obj.option_id);
                  var m_id = obj.option_id;
                  if(!$("input.checkbox_am[data-option_id='"+m_id+"']").is(":checked"))
                  $("input.checkbox_am[data-option_id='"+m_id+"']").parent().find('span').html('&nbsp('+obj.count+')');
                });

            }, "json");
        }
        
    </script>
    {settings_tracking}
    
    <?php
    /* [START] Search background settings */
    $this->data['search_background'] = 'assets/img/texture.jpg';
    if(isset($this->data['settings']['search_background']))
    {
        if(is_numeric($this->data['settings']['search_background']))
        {
            $files_search_background = $this->file_m->get_by(array('repository_id' => $this->data['settings']['search_background']), TRUE);
            if( is_object($files_search_background) && file_exists(FCPATH.'files/thumbnail/'.$files_search_background->filename))
            {
                $this->data['search_background'] = base_url('files/'.$files_search_background->filename);
            }
        }
    }
    ?>
    <style>
    .wrap-search {
        background-image: url('<?php echo $this->data['search_background']; ?>');   
    }
    </style>
    <?php
    /* [END] Search background settings */
    ?>