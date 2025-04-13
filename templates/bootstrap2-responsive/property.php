<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    
    
    <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/places.js')): ?>
    <script src="assets/js/places.js"></script>
    <?php endif; ?>
    
    <script>
    
    var IMG_FOLDER = "assets/js/dpejes";
    
    $(document).ready(function(){
        
        <?php if(config_db_item('appId') != '' && file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/like2unlock/js/jquery.op.like2unlock.min.js')): ?>
        $(".agent").toLike({
            text: "<?php echo lang_check('Like to view hidden content.');?>",
            facebook: { appId: "<?php echo config_db_item('appId'); ?>" } 
            });
        <?php endif; ?>
    
<?php if(file_exists(APPPATH.'controllers/admin/favorites.php')):?>
    // [START] Add to favorites //  
    
    $("#add_to_favorites").click(function(){
        
        var data = { property_id: {estate_data_id} };
        
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
        $.post("{api_private_url}/add_to_favorites/{lang_code}", data, 
               function(data){
            
            ShowStatus.show(data.message);
                            
            load_indicator.css('display', 'none');
            
            if(data.success)
            {
                $("#add_to_favorites").css('display', 'none');
                $("#remove_from_favorites").css('display', 'inline-block');
            }
        });

        return false;
    });
    
    $("#remove_from_favorites").click(function(){
        
        var data = { property_id: {estate_data_id} };
        
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
        $.post("{api_private_url}/remove_from_favorites/{lang_code}", data, 
               function(data){
            
            ShowStatus.show(data.message);
                            
            load_indicator.css('display', 'none');
            
            if(data.success)
            {
                $("#remove_from_favorites").css('display', 'none');
                $("#add_to_favorites").css('display', 'inline-block');
            }
        });

        return false;
    });
    
    // [END] Add to favorites //  
<?php endif; ?>


<?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/dpejes/dpe.js')): ?>
/* start impl�ment dpe-->   */
	
    dpe.show_numbers = true;
<?php
if(!empty($estate_data_option_59) && !is_numeric($estate_data_option_59))
{
    echo 'dpe.show_numbers = false;';
}
?>
    dpe.energy_title1 = "{options_name_59}";
	dpe.energy_title2 = "{options_suffix_59}";
	dpe.energy_title3 = "{lang_Logement}";
    dpe.gg_title2 = "{options_suffix_60}";
	dpe.gg_title1 = "{options_name_60}";
	
    if(!dpe.show_numbers)
    {
    	dpe.energy_title2 = "";
        dpe.gg_title2 = "";
    }
    
	/* adjusts the height of the large thumbnails (the width is automatically adjusted proportionally)
	 * possible values: de 180 a 312 
	 */
	dpe.canvas_height = 250;
	/*not to display the unit gas emissions greenhouse in the right column: */
	dpe.gg_unit = '';
	/*  adjusts the height of the small thumbnails
	 * possible values: 35
	 */
	dpe.sticker_height = 35;
	/* can change the attribute of div tags that indicates it is a tag */
	dpe.tag_attribute = 'attributdpe';
	dpe.tag_attribute = 'title';
	/* Launches replacing the contents of the div right by good vignettes */
	dpe.all();
/* end implement dpe-->   */
<?php endif; ?>


        /* [Carousel fix center image START] */
//        var carousel_height = $(".propertyCarousel").height();
//
//        $(".propertyCarousel .carousel-inner div.item img").one("load", function() {
//            var image_height = $(this)[0].height;
//            
//            if(image_height > carousel_height)
//            {
//                $(this).css('top', '-'+ parseInt((image_height-carousel_height)/2) +'px');
//                $(this).css('position', 'relative');
//            }
//        });
        /* [Carousel fix center image END] */

       $("#route_from_button").click(function () { 
            window.open("https://maps.google.hr/maps?saddr="+$("#route_from").val()+"&daddr={estate_data_address}@{estate_data_gps}&hl={lang_code}",'_blank');
            return false;
        });

 <?php if(config_db_item('map_version') =='open_street'):?>
        var property_map;
        if($('#propertyLocation').length){
            property_map = L.map('propertyLocation', {
                center: [{estate_data_gps}],
                zoom: {settings_zoom}+6,
                scrollWheelZoom: scrollWheelEnabled,
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
             zoom: {settings_zoom}+6,
             scrollwheel: scrollWheelEnabled
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
        
        $("#wrap-map").gmap3({
         map:{
            options:{
             center: [{estate_data_gps}],
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
                {latLng:[{gps}], adr:"{address}", options:{icon: "{icon}"}, data:"<img style=\"width: 150px; height: 100px;\" src=\"{thumbnail_url}\"  alt=\"\"/><br />{address}<br />{option_2}<br /><span class=\"label label-info\">&nbsp;&nbsp;{option_4}&nbsp;&nbsp;</span><br /><a href=\"{url}\">{lang_Details}</a>"},
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
        
        
        if (typeof swplaces_init_directions == 'function')
        {
            setTimeout( load_near_places, 2000 );
        }
    
     <?php endif;?>
    }); 
    
    <?php if(config_db_item('map_version') !='open_street'):?>
    function load_near_places()
    {
        $(".places_select a").click(function(){
            init_places($(this).attr('data-rel'), $(this).find('img').attr('src'));
        });
        
        var selected_place_type = 4;
        
        swplaces_init_directions();
        init_places($(".places_select a:eq("+selected_place_type+")").attr('data-rel'), $(".places_select a:eq("+selected_place_type+") img").attr('src'));
    }
    
    var map_propertyLoc;
    var markers = [];
    var generic_icon;
    
    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var placesService;

    function init_places(places_types, icon) {
        var pyrmont = new google.maps.LatLng({estate_data_gps});

        setAllMap_near(null);
        
        generic_icon = icon;

        map_propertyLoc = $("#propertyLocation").gmap3({
            get: { name:"map" }
        });    
        
        map_propertyLoc.setZoom(10); // here you can change zoom for near places map
        
        var places_type_array = places_types.split(','); 
        
        var request = {
            location: pyrmont,
            radius: 2000,
            types: places_type_array
        };
        
        infowindow = new google.maps.InfoWindow();
        placesService = new google.maps.places.PlacesService(map_propertyLoc);
        placesService.nearbySearch(request, callback);

    }

    function callback(results, status) {
      if (status == google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
          createMarker(results[i]);
        }
      }
    }
    
    function setAllMap_near(map) {
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
      }
    }

    function calcRoute(source_place, dest_place) {
      var selectedMode = 'WALKING';
      var request = {
          origin: source_place,
          destination: dest_place,
          // Note that Javascript allows us to access the constant
          // using square brackets and a string value as its
          // "property."
          travelMode: google.maps.TravelMode[selectedMode]
      };
      
      directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
          directionsDisplay.setDirections(response);
          //console.log(response.routes[0].legs[0].distance.value);
        }
      });
    }
    
    function createMarker(place) {
      var placeLoc = place.geometry.location;
      var propertyLocation = new google.maps.LatLng({estate_data_gps});
      
        if(place.icon.indexOf("generic") > -1)
        {
            place.icon = generic_icon;
        }
      
        var image = {
          url: place.icon,
          size: new google.maps.Size(71, 71),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(25, 25)
        };

      var marker = new google.maps.Marker({
        map: map_propertyLoc,
        icon: image,
        position: place.geometry.location
      });
      
      markers.push(marker);
      
      var distanceKm = (calcDistance(propertyLocation, placeLoc)*1.2).toFixed(2);
      var walkingTime = parseInt((distanceKm/5)*60+0.5);

      google.maps.event.addListener(marker, 'click', function() {
        
            //drawing route
            calcRoute(propertyLocation, placeLoc);
        
        // Fetch place details
        placesService.getDetails({ placeId: place.place_id }, function(placeDetails, statusDetails){
            

            
            //open popup infowindow
            infowindow.setContent(place.name+'<br />{lang_Distance}: '+distanceKm+'{lang_Km}'+
                                  '<br />{lang_WalkingTime}: '+walkingTime+'{lang_Min}'+
                                  '<br /><a target="_blank" href="'+placeDetails.url+'">{lang_Details}</a>');
            infowindow.open(map_propertyLoc, marker);
        });

      });
    }
    
    //calculates distance between two points
    function calcDistance(p1, p2){
      return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
    }
<?php endif;?>

       
    </script>
    

  </head>

  <body>
<?php if (!empty($settings_facebook_jsdk) && (config_db_item('appId') == '' || !file_exists(FCPATH . 'templates/' . $settings_template . '/assets/js/like2unlock/js/jquery.op.like2unlock.min.js'))): ?>
<?php
if (!empty($lang_facebook_code))
    $settings_facebook_jsdk = str_replace('en_EN', $lang_facebook_code, $settings_facebook_jsdk);
?>
<?php echo $settings_facebook_jsdk; ?>
<?php endif; ?>
{template_header}

<?php if(config_item('map_on_property_enabled') === TRUE): ?>
<?php _widget('top_mapsearch');?>
<?php endif;?>

<?php _widget('top_ads');?>

<a id="content"></a>
<div class="wrap-content">
    <div class="container container-property">
        <div class="row-fluid">
            <div class="span9">
            <h2>{page_title}, #{estate_data_id}</h2>
            {has_page_images}
            <div class="propertyCarousel">
                <div id="myCarousel" class="carousel slide">
                <ol class="carousel-indicators">
                {slideshow_property_images}
                <li data-target="#myCarousel" data-slide-to="{num}" class="{first_active}"></li>
                {/slideshow_property_images}
                </ol>
                <!-- Carousel items -->
                <div class="carousel-inner">
                <?php foreach ($slideshow_property_images as $key => $file):?>
                    <div class="item <?php _che($file['first_active']);?>" data-link="<?php echo $file['url']; ?>">

                    <div style="background:url('<?php echo _simg($file['url'], '870x469'); ?>') center center; background-size:cover;" class="slider-size">
                    </div>

                    </div>
                <?php endforeach; ?>
                </div>
                <!-- Carousel nav -->
                <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
                </div>
            </div>
            {/has_page_images}
              <div class="property_content">
              
            <?php if(file_exists(APPPATH.'controllers/admin/favorites.php')):?>
            <?php
                $favorite_added = false;
                if(sw_count($not_logged) == 0)
                {
                    $CI =& get_instance();
                    $CI->load->model('favorites_m');
                    $favorite_added = $CI->favorites_m->check_if_exists($this->session->userdata('id'), 
                                                                        $estate_data_id);
                    if($favorite_added>0)$favorite_added = true;
                }
            ?>
            
            <a class="btn btn-warning" id="add_to_favorites" href="#" style="<?php echo ($favorite_added)?'display:none;':''; ?>"><i class="icon-star icon-white"></i> <?php echo lang_check('Add to favorites'); ?> <i class="load-indicator"></i></a>
            <a class="btn btn-success" id="remove_from_favorites" href="#" style="<?php echo (!$favorite_added)?'display:none;':''; ?>"><i class="icon-star icon-white"></i> <?php echo lang_check('Remove from favorites'); ?> <i class="load-indicator"></i></a>
           

            <?php endif; ?>
            <?php _widget('report_property');?>
            <?php _widget('property_custom_submit_visit');?>
            
            <div class="facebook_top_share">
                {settings_facebook_top}
            </div>
            

                <h2>{lang_Description}</h2>
                {page_body}
                
                <div class="visible-phone">
                    <?php _widget('property_right_overview');?>
                </div>

                <?php if(isset($category_options_21) && $category_options_count_21>0): ?>
                <h2>{options_name_21}</h2>
                <ul class="amenities">
                {category_options_21}
                {is_checkbox}
                <li>
                <img src="assets/img/checkbox_{option_value}.png" alt="{option_value}" class="check" />&nbsp;&nbsp;{option_name}&nbsp;&nbsp;{icon}
                </li>
                {/is_checkbox}
                {/category_options_21}
                </ul>
                <br style="clear: both;" />
                <?php endif; ?>
                
                <?php if(isset($category_options_52) && $category_options_count_52>0): ?>
                <h2>{options_name_52}</h2>
                <ul class="amenities">
                {category_options_52}
                {is_checkbox}
                <li>
                <img src="assets/img/checkbox_{option_value}.png" alt="{option_value}" class="check" />&nbsp;&nbsp;{option_name}&nbsp;&nbsp;{icon}
                </li>
                {/is_checkbox}
                {/category_options_52}
                </ul>
                <br style="clear: both;" />
                <?php endif; ?>
                
                <?php if(isset($category_options_43) && $category_options_count_43>0): ?>
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
                
                <?php if(config_db_item('enable_table_input') === TRUE && !empty($estate_data_option_76) && !empty($options_values_76)):?>
                <h2><?php _che($options_name_76); ?></h2>
                <?php if(config_item('onmouse_gallery_enabled') === TRUE): ?>
                <div class="row-fluid onmouse_gallery">
                    <?php foreach ($page_images as $key => $image): ?>
                    <div class="onmouse_gallery-image">
                        <img src="<?php _che($image->thumbnail_url);?>" data-index="<?php echo $key;?>" data-src="<?php _che($image->url);?>" alt="<?php _che($image->alt);?>" />
                    </div>
                    <?php endforeach;?>
                </div>
                <?php endif;?>
                <table id="table_field_76" class="table table-striped">
                    <thead>
                    <tr>
                    <?php
                    foreach($options_values_arr_76 as $col_val)
                    {
                        $to = strpos($col_val, '[');
                        if($to !== FALSE)$col_val =substr($col_val, 0, $to);

                        echo '<th>'.$col_val.'</th>';
                    }
                    ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php _che($estate_data_option_76); ?>
                    </tbody>
                </table>
                <?php _widget('property_center-tablecalendar');?>
                <?php endif;?>
                
                <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/dpejes/dpe.js')): ?>
                <?php if(!empty($options_name_59) || !empty($options_name_60)): ?>
                  <!--energy version full -->
                  <h2>{options_name_59}</h2>
                  <div class="divider"></div>
                  <div class="amenities">
                  <?php
                  
                    $values_chars = array(  'A'=>'40',
                                            'B'=>'80',
                                            'C'=>'140',
                                            'D'=>'220',
                                            'E'=>'320',
                                            'F'=>'440',
                                            'G'=>'460');
                    
                    if(isset($estate_data_option_59) && !is_numeric($estate_data_option_59))
                    {
                        if(isset($values_chars[$estate_data_option_59]))
                            $estate_data_option_59 = $values_chars[$estate_data_option_59];
                    }
                    
                    $values_chars = array(  'A'=>'4',
                                            'B'=>'9',
                                            'C'=>'19',
                                            'D'=>'34',
                                            'E'=>'54',
                                            'F'=>'79',
                                            'G'=>'81');
                    
                    if(isset($estate_data_option_60) && !is_numeric($estate_data_option_60))
                    {
                        if(isset($values_chars[$estate_data_option_60]))
                            $estate_data_option_60 = $values_chars[$estate_data_option_60];
                    }
                  ?>
                    <div title="energie:<?php echo get_numeric_val($estate_data_option_59); ?>" style="float:left;width:280;margin-right:50px;">
                      <div class="alert alert-warning"><?php _l('No Efficiency');?> </div>
                      <br/>
                      <br/>
                      </a>
                    </div>
                    
                    <div title="ges:<?php echo get_numeric_val($estate_data_option_60); ?>" style="float:left;">
                      <div class="alert alert-warning"> <?php _l('No Gas');?> </div>
                    </div>
                    
                  </div>
                  <br style="clear: both;" />
                  
                  <!--energy --> 
                <?php endif; ?>
                  
                  <?php _widget('property_center_custom-energy');?>
                  
                  
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
                <h2>{lang_AvailabilityCalender}</h2>
                <div class="av_calender">
                <?php
                    $row_break=0;
                    
                    foreach($months_availability as $v_month)
                    {
                        echo '<div class="month_container">'.$v_month.'</div>';
                        
                        $row_break++;
                        //if($row_break%3 == 0)
                        //echo '<div style="clear: both;height:10px;"></div>';
                    }
                ?>
                <br style="clear: both;" />
                </div>
                <?php endif;?>
                
                <?php if(!empty($estate_data_gps)): ?>
                <h2 id="hNearProperties">{lang_Propertylocation}</h2>
                <div class="places_select" style="display: none;">
                    <a class="btn btn-large"  data-rel="hospital,health"><img src="assets/img/places_icons/hospital.png"  alt=""/> {lang_Health}</a>
                    <a class="btn btn-large"  data-rel="park"><img src="assets/img/places_icons/park.png"  alt=""/> {lang_Park}</a>
                    <a class="btn btn-large"  data-rel="atm,bank"><img src="assets/img/places_icons/atm.png"  alt=""/> {lang_ATMBank}</a>
                    <a class="btn btn-large"  data-rel="gas_station"><img src="assets/img/places_icons/petrol.png"  alt=""/> {lang_PetrolPump}</a>
                    <a class="btn btn-large"  data-rel="food,bar,cafe,restourant"><img src="assets/img/places_icons/restourant.png"  alt=""/> {lang_Restourant}</a>
                    <a class="btn btn-large"  data-rel="store"><img src="assets/img/places_icons/store.png"  alt=""/> {lang_Store}</a>
                </div>
                <div id="propertyLocation">
                </div>
                <div class="route_suggestion">
                <input id="route_from" class="inputtext w360" type="text" value="" placeholder="{lang_Typeaddress}" name="route_from" />
                <a id="route_from_button" href="#" class="btn">{lang_Suggestroutes}</a>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($estate_data_option_12)): ?>
                <h2>{options_name_9}</h2>
                    <?php 
                    $estate_data_option_12 = trim($estate_data_option_12);
                    if(filter_var($estate_data_option_12, FILTER_VALIDATE_URL) && 
                        (strpos($estate_data_option_12, 'youtube.com')!==FALSE || strpos($estate_data_option_12, 'youtu.be')!==FALSE)
                      ):
                        
                        $youtube_id='';
                        if(strpos($estate_data_option_12, 'youtube.com')!==FALSE)
                            $youtube_id= substr($estate_data_option_12, strpos($estate_data_option_12, "v=") + 2);
                        if(strpos($estate_data_option_12, 'youtu.be')!==FALSE)
                            $youtube_id = substr($estate_data_option_12, strpos($estate_data_option_12, "e/") + 2); 
                    ?>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $youtube_id;?>" frameborder="0" allowfullscreen></iframe>
                    <?php else :?>
                    {estate_data_option_12}
                    <?php endif; ?>
                    <br style="clear:both;" />
                <?php endif; ?>
                
                <?php if(config_db_item('walkscore_enabled') == TRUE && !empty($estate_data_address) && !empty($estate_data_gps)): ?>
                <div class="hidden-phone">
                <br />
                <script>
                var ws_wsid = '';
                <?php
                if(!empty($estate_data_gps))
                {
                    $GPS_DATA = explode(',', $estate_data_gps);
                    
                    if(sw_count($GPS_DATA) == 2)
                    {
                        echo "var ws_lat = '".trim($GPS_DATA[0])."';\n";
                        echo "var ws_lon = '".trim($GPS_DATA[1])."';\n";
                    }
                }
                else
                {
                    echo "var ws_address = '$estate_data_address';";
                }
                ?>
                var ws_width = '500';
                var ws_height = '336';
                var ws_layout = 'horizontal';
                var ws_commute = 'true';
                var ws_transit_score = 'true';
                var ws_map_modules = 'all';
                <?php
                $base_url = base_url();
                $url_protocol='http://';
                if(strpos( $base_url,'https')!== false){
                    $url_protocol='https://';
                }
                ?> 
                </script>
                <div id='ws-walkscore-tile'><div id='ws-footer' style='position:absolute;top:318px;left:8px;width:488px'><form id='ws-form'><a id='ws-a' href='<?php echo $url_protocol;?>www.walkscore.com/' target='_blank'>What's Your Walk Score?</a><input type='text' id='ws-street' style='position:absolute;top:0px;left:170px;width:286px' /><input type='submit' id='ws-go' value="<?php echo lang_check('Go');?>" style='position:absolute;top:0px;right:0px' /></form></div></div><script src='<?php echo $url_protocol;?>www.walkscore.com/tile/show-walkscore-tile.php'></script>
                </div>
                <?php endif; ?>

                <?php if(config_item('ad_gallery_enabled') == TRUE): ?>
                {has_page_images}
                <div class="ad-gallery" id="gallery">
                      <div class="ad-image-wrapper"><div class="ad-image" style="width: 600px; height: 400px; top: 0px; left: 0px;"><img width="600" height="400" src="images/5.jpg"><p class="ad-image-description" style="width: 586px; bottom: 0px;"><strong class="ad-description-title">A title for 5.jpg</strong><span>This is a nice, and incredibly descriptive, description of the image 5.jpg</span></p></div><img src="loader.gif" class="ad-loader" style="display: none;"  alt=""/><div class="ad-next" style="height: 400px;"><div class="ad-next-image" style="opacity: 0.7; display: none;"></div></div><div class="ad-prev" style="height: 400px;"><div class="ad-prev-image" style="opacity: 0.7; display: none;"></div></div></div>
                      <div class="ad-controls">
                      <p class="ad-info"></p><div class="ad-slideshow-controls"><span class="ad-slideshow-countdown" style="display: none;"></span></div></div>
                      <div class="ad-nav"><div class="ad-back" style="opacity: 0.6;"></div>
                        <div class="ad-thumbs">
                          <ul class="ad-thumb-list" style="width: 1353px;">
                            {page_images}
                            <li>
                              <a href="{url}" class="">
                                <img class="image0" src="{thumbnail_url}" style="opacity: 0.7;"  alt=""/>
                              </a>
                            </li>
                            <li>
                            {/page_images}
                          </ul>
                        </div>
                      <div class="ad-forward" style="opacity: 0.6;"></div></div>
                    </div>
                {/has_page_images}
                <?php else: ?>
                {has_page_images}
                
                <h2>{lang_Imagegallery}</h2>
                <ul data-target="#modal-gallery" data-toggle="modal-gallery" class="files files-list ui-sortable">  
                    <?php foreach ($page_images as $val):?>
                    <li class="template-download fade in">
                        <a data-gallery="gallery" href="<?php _che($val->url);?>" download="<?php _che($val->url);?>" title="<?php _che($val->filename);?>" class="preview show-icon">
                            <img src="assets/img/preview-icon.png" class="" alt="<?php echo $val->alt;?>"/>
                        </a>
                        <div class="preview-img"><img src="<?php echo _simg($val->thumbnail_url, '480x360');?>" data-src="<?php _che($val->url);?>" alt="<?php echo $val->alt;?>" class="" /></div>
                    </li>
                    <?php endforeach;?>
                </ul>
                {/has_page_images}
                
                <?php endif; ?>
                
                <br style="clear:both;" />
                
                {has_page_documents}
                <h2>{lang_Filerepository}</h2>
                <ul class="file-repository">
                <?php if(is_array($this->session->userdata('contacted_agents'))&&in_array($agent_id, $this->session->userdata('contacted_agents'))): ?>
                <?php else: ?>
                <a class="popup-with-form hidden-file-details" href="#test-form"><?php echo lang_check('Show file details'); ?></a>
                <?php endif; ?>
                {page_documents}
                <li>
                    <a href="{url}">{filename}</a>
                </li>
                {/page_documents}
                </ul>
                <br style="clear:both;" />
                {/has_page_documents}
                
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
                
                <?php if(file_exists(APPPATH.'controllers/admin/swin_reviews.php') && $settings_reviews_enabled): ?>
                <h2 id="form_review"><?php echo lang_check('YourReview'); ?></h2>
                <?php if(sw_count($not_logged) && config_item('reviews_without_login') === FALSE): ?>
                <p class="alert alert-success">
                    <?php echo lang_check('LoginToReview'); ?>
                </p>
                <?php else: ?>
                
                <?php if($reviews_submitted == 0): ?>
                
                <?php _che($reviews_validation_errors); ?>
                
                <?php
                
                
                if(config_db_item('events_qr_confirmation') === TRUE && $this->userattend_m->check_notattend($estate_data_id, 
                                                                                    $this->session->userdata('id'))): ?>
                
                <p class="alert alert-info">
                    <?php echo lang_check('You can review after attend on event'); ?>
                </p>
                
                <?php else: ?>
                
                <form class="form-horizontal" method="post" action="{page_current_url}#form_review">
                
                <?php if(sw_count($not_logged) && config_item('reviews_without_login') === TRUE): ?>
                <div class="control-group">
                    <label class="control-label" for="inputMailR"><?php echo lang_check('Email'); ?></label>
                    <div class="controls">
                        <input id="inputMailR" type="text" name="mail" placeholder="{lang_Email}" />
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="control-group">
                <label class="control-label" for="inputRating"><?php echo lang_check('Rating'); ?></label>
                <div class="controls">
                    <input type="number" data-max="5" data-min="1" name="stars" id="stars" class="rating" data-empty-value="5" value="5" data-active-icon="icon-star" data-inactive-icon="icon-star-empty" />
                </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputMessageR"><?php echo lang_check('Message'); ?></label>
                    <div class="controls">
                        <textarea id="inputMessageR" rows="3" name="message" rows="3" placeholder="{lang_Message}"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn"><?php echo lang_check('Send'); ?></button>
                    </div>
                </div>
                </form>
                <?php endif; ?>
                <?php else: ?>
                <p class="alert alert-info">
                    <?php echo lang_check('ThanksOnReview'); ?>
                </p>
                <?php endif; ?>
                
                <?php endif; ?>
                
                
                <?php if($settings_reviews_public_visible_enabled): ?>
                <h2><?php echo lang_check('Reviews'); ?></h2>
                <?php if(sw_count($not_logged) && !$settings_reviews_public_visible_enabled): ?>
                <p class="alert alert-success">
                    <?php echo lang_check('LoginToReadReviews'); ?>
                </p>
                <?php else: ?>
                <?php if(sw_count($reviews_all) > 0): ?>
                <ul class="media-list">
                <?php foreach($reviews_all as $review_data): ?>
                <?php //print_r($review_data); ?>
                <li class="media">
                <div class="pull-left">
                <?php if(isset($review_data['image_user_filename'])): ?>
                <img  alt="" class="media-object" data-src="holder.js/64x64" style="width: 64px; height: 64px;" src="<?php echo base_url('files/thumbnail/'.$review_data['image_user_filename']); ?>" />
                <?php else: ?>
                <img alt="" class="media-object" data-src="holder.js/64x64" style="width: 64px; height: 64px;" src="assets/img/user-agent.png" />
                <?php endif; ?>
                </div>
                <div class="media-body">
                <h4 class="media-heading"><div class="review_stars_<?php echo $review_data['stars']; ?>"> </div> <?php if(!empty($review_data['user_mail']))echo ' <span style="font-size:12px;">'.$review_data['user_mail'].'</span>';?></h4>
                <?php if($review_data['is_visible']): ?>
                <?php echo $review_data['message']; ?>
                <?php else: ?>
                <?php echo lang_check('HiddenByAdmin'); ?>
                <?php endif; ?>
                </div>
                </li>
                <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="alert alert-success">
                    <?php echo lang_check('SubmitFirstReview'); ?>
                </p>
                <?php endif; ?>
                <?php endif; ?>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php _widget('property_center-facecomments');?>
                
                <?php if(sw_count($agent_estates) > 0): ?>
                <h2>{lang_Agentestates}</h2>
                <div id="ajax_results">
                <?php foreach($agent_estates as $key=>$item): ?>
                <?php
                   if($key==0)echo '<ul class="thumbnails agent-property">';
                ?>
                    <?php _generate_results_item(array('columns'=>3, 'key'=>$key, 'item'=>$item, 'icons'=>false, 'view_counter'=>false)); ?>
                <?php
                   if( ($key+1)%3==0 )
                    {
                        echo '</ul><ul class="thumbnails agent-property">';
                    }
                    if( ($key+1)==sw_count($agent_estates) ) echo '</ul>';
                    endforeach;
                ?>
                
                
                <div class="pagination-ajax-results pagination" rel="ajax_results">
                {pagination_links_agent}
                </div>
                </div>
                <?php endif;?>
                <br style="clear:both;" />

              </div>
            </div>
            <div class="span3">
                <div class="hidden-phone">
                    <?php _widget('property_right_overview');?>
                </div>
                
<?php _widget('property_right_currency-conversions'); ?> 
          
                  <?php _widget('right_ads'); ?> 
            
        <?php if(!isset($estate_data_option_66) && !empty($estate_data_option_67)): ?>
        <h2><?php echo lang_check('Company details'); ?></h2>
          <div class="agent">
            <?php if($is_private_listing): ?>
            <div class="purchase_package">
            <a href="{front_login_url}#content">{lang_PurchaseToShow}</a>
            </div>
            <?php else: ?>
            <?php if(is_array($this->session->userdata('contacted_agents'))&&in_array($agent_id, $this->session->userdata('contacted_agents'))): ?>
            <?php else: ?>
            <a class="popup-with-form hidden-agent-details" href="#test-form"><?php echo lang_check('Show agent contact details'); ?></a>
            <?php endif; ?>
            <?php
            if(!empty($estate_data_option_74))
            {
                //Fetch repository
                $rep_id = $estate_data_option_74;
                $file_rep = $this->file_m->get_by(array('repository_id'=>$rep_id));
                
                // If not defined in this language
//                if(sw_count($file_rep) == 0)
//                {
//                    //Fetch option for default language
//                    $def_lang_id = $this->language_m->get_default_id();
//                    if(!empty($def_lang_id))
//                    {
//                        $def_lang_rep_id = $this->option_m->get_property_value($def_lang_id, $estate_data_id, 74);
//                        
//                        if(!empty($def_lang_rep_id))
//                        $file_rep = $this->file_m->get_by(array('repository_id'=>$def_lang_rep_id));
//                    }
//                }
                
                $rep_value = '';
                if(sw_count($file_rep))
                {
                    echo '<div class="image-company"><img src="'.base_url('files/'.$file_rep[0]->filename).'" alt="'.$estate_data_option_67.'" /></div>';
                }
            }
            ?>
            <?php if(!empty($estate_data_option_67)): ?><div class="name"><a href="{agent_url}#content"><?php echo $estate_data_option_67; ?></a></div><?php endif; ?>
            <?php if(!empty($estate_data_option_68)): ?><div class="phone"><?php echo $estate_data_option_68; ?></div><?php endif; ?>
            <?php if(!empty($estate_data_option_73)): ?><div class="hours"><?php echo lang_check('Office hours'); ?>: <?php echo $estate_data_option_73; ?></div><?php endif; ?>
            <?php if(!empty($estate_data_option_69)): ?><div class="website"><a target="_blank" style="color: blue;" href="<?php echo $estate_data_option_69; ?>"><?php echo lang_check('Website'); ?></a></div><?php endif; ?>
            <div style="padding: 5px;">
                <?php if(!empty($estate_data_option_72)): ?><div class="description"><em><?php echo $estate_data_option_72; ?></em></div><?php endif; ?>
                <?php if(!empty($estate_data_option_70)): ?><a target="_blank" href="<?php echo $estate_data_option_70; ?>"><img src="assets/img/social-facebook-button-blue-icon.png" alt="" /></a><?php endif; ?>
                <?php if(!empty($estate_data_option_71)): ?><a target="_blank" href="<?php echo $estate_data_option_71; ?>"><img src="assets/img/social-twitter-button-blue-icon.png" alt="" /></a><?php endif; ?>
            </div>
            
            <?php endif; ?>
          </div>
        <?php endif ?>
                  
                  
                  {has_agent}
                  <?php if(isset($agent_profile['type']) && $agent_profile['type']=='USER'):?>
                      <h2><?php echo lang_check('Owner');?></h2>
                  <?php else: ?>
                      <h2>{lang_Agent}</h2>
                  <?php endif;?>
                  <div class="agent">
                    <?php if($is_private_listing): ?>
                    <div class="purchase_package">
                    <a href="{front_login_url}#content">{lang_PurchaseToShow}</a>
                    </div>
                    <?php else: ?>
                    <?php if(is_array($this->session->userdata('contacted_agents'))&&in_array($agent_id, $this->session->userdata('contacted_agents'))): ?>
                    <?php else: ?>
                    <a class="popup-with-form hidden-agent-details" href="#test-form"><?php echo lang_check('Show agent contact details'); ?></a>
                    <?php endif; ?>
                    
                    <div class="image"><img src="{agent_image_url}" alt="{agent_name_surname}" /></div>
                    <div class="name"><a href="{agent_url}#content">{agent_name_surname}</a></div>
                    <div class="phone">{agent_phone}</div>
                    <?php if(config_db_item('phone_mobile_enabled') === TRUE): ?>
                    <div class="phone">{agent_phone2}</div>
                    <?php endif; ?>
                    <div class="mail"><a href="mailto:{agent_mail}?subject=<?php echo urlencode(lang_check('Estateinqueryfor'));?>:<?php echo urlencode(_ch($estate_data_id));?>,<?php echo urlencode(_ch($page_title));?>">{agent_mail}</a></div>
                    <!--<div class="address">{agent_address}</div>-->
                    <?php endif; ?>
                  </div>
                  {/has_agent}
                  
                  <?php _widget('property_right_qrcode');?>
                  <?php _widget('property_right_weather');?>
                  <?php _widget('property_right_pdf');?>
                  <?php if(file_exists(APPPATH.'controllers/propertycompare.php')):?>
                        <?php _widget('property_compare'); ?>
                    <?php endif;?>
                  
                  <?php if(file_exists(APPPATH.'libraries/Messenger.php') && config_item('private_messages_enabled') !== FALSE) : ?>
                    <?php if($agent_id != $this->session->userdata('id')):?>
                        <h2><?php echo lang_check('Private message');?></h2>
                      <a href="<?php echo site_url('fmessages/messenger?sel='.$agent_id.'&property_id='.$property_id) ;?>" class="btn btn-warning btn-property"><?php _l('Send message');?></a>
                    <?php endif;?>
                    <?php else:?>
                  <?php if(file_exists(APPPATH.'controllers/admin/booking.php') && sw_count($is_purpose_rent) && $this->session->userdata('type')=='USER' && config_item('reservations_disabled') === FALSE):?>
                  <h2>{lang_Bookingform}</h2>
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
                        {is_purpose_rent}
                        <label>{lang_FromDate}</label>
                        <input name="fromdate" type="text" id="datetimepicker1" value="{form_value_fromdate}" class="{form_error_fromdate}" placeholder="{lang_FromDate}" />
                        <label>{lang_ToDate}</label>
                        <input class="{form_error_todate}" id="datetimepicker2" name="todate" type="text" placeholder="{lang_ToDate}" value="{form_value_todate}" />
                        {/is_purpose_rent}
                        <label>{lang_Message}</label>
                        <textarea class="{form_error_message}" name="message" rows="3" placeholder="{lang_Message}">{form_value_message}</textarea>
                        
                        <?php if(config_item('captcha_disabled') === FALSE): ?>
                        <label class="captcha"><?php echo $captcha['image']; ?></label>
                        <input class="captcha {form_error_captcha}" name="captcha" type="text" placeholder="{lang_Captcha}" value="" />
                        <br style="clear: both;" />
                        <input class="hidden" name="captcha_hash" type="text" value="<?php echo $captcha_hash; ?>" />
                        <?php endif; ?>
                        
                        <?php if(config_item('recaptcha_site_key') !== FALSE): ?>
                        <div class="control-group" >
                            <label class="control-label captcha"></label>
                            <div class="controls">
                                <?php _recaptcha(true); ?>
                           </div>
                        </div>
                       <?php endif; ?>
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
                        <label style="margin-top: 10px;">
                            <?php echo lang_check('Accept tems');?>
                        </label>
                        <div class="" style="width: 160px;padding-top: 5px;">
                            <input type="checkbox" value="1" name="terms_user" class="terms_user" required="required" style="margin: 0;display: inline-block;width: 20px;"> <a target="_blank" href="<?php echo $terms_url; ?>"><?php echo lang_check('I accept the Terms and Conditions'); ?></a>
                        </div>
                        <?php endif;?>
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
                        <label style="margin-top: 10px;">
                            <?php echo lang_check('Accept privacy');?>
                        </label>
                        <div class="" style="width: 160px;padding-top: 5px;">
                            <input type="checkbox" value="1" name="privacy_user" class="privacy_user" required="required" style="margin: 0;display: inline-block;width: 20px;"> <a target="_blank" href="<?php echo $privacy_url; ?>"><?php echo lang_check('I accept the Privacy'); ?></a>
                        </div>
                        <?php endif;?>
                        <br style="clear: both;" />
                        <p style="text-align:right;">
                        <button type="submit" class="btn btn-info">{lang_CalculateBook}</button>
                        </p>
                    </form>
                  </div>
                  <?php else:?>
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
                        
                        <?php if(config_item('reservations_disabled') === FALSE): ?>
                        {is_purpose_rent}
                        <label>{lang_FromDate}</label>
                        <input name="fromdate" type="text" id="datetimepicker1" value="{form_value_fromdate}" class="{form_error_fromdate}" placeholder="{lang_FromDate}" />
                        <label>{lang_ToDate}</label>
                        <input class="{form_error_todate}" id="datetimepicker2" name="todate" type="text" placeholder="{lang_ToDate}" value="{form_value_todate}" />
                        {/is_purpose_rent}
                        <?php endif; ?>
                        <label>{lang_Message}</label>
                        <textarea class="{form_error_message}" name="message" rows="3" placeholder="{lang_Message}">{form_value_message}</textarea>
                        
                        <?php if(config_item('captcha_disabled') === FALSE): ?>
                        <label class="captcha"><?php echo $captcha['image']; ?></label>
                        <input class="captcha {form_error_captcha}" name="captcha" type="text" placeholder="{lang_Captcha}" value="" />
                        <br style="clear: both;" />
                        <input class="hidden" name="captcha_hash" type="text" value="<?php echo $captcha_hash; ?>" />
                        <?php endif; ?>
                        
                        <?php if(config_item('recaptcha_site_key') !== FALSE): ?>
                        <div class="control-group" >
                            <label class="control-label captcha"></label>
                            <div class="controls">
                                <?php _recaptcha(true); ?>
                           </div>
                        </div>
                        <?php endif; ?>
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
                        <label style="margin-top: 10px;">
                            <?php echo lang_check('Accept tems');?>
                        </label>
                        <div class="" style="width: 160px;padding-top: 5px;">
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
                        <label style="margin-top: 10px;">
                            <?php echo lang_check('Accept privacy');?>
                        </label>
                        <div class="" style="width: 160px;padding-top: 5px;">
                            <input type="checkbox" value="1" name="privacy_user" class="privacy_user" required="required" style="margin: 0;display: inline-block;width: 20px;"> <a target="_blank" href="<?php echo $privacy_url; ?>"><?php echo lang_check('I accept the Privacy'); ?></a>
                        </div>
                        <?php endif;?>
                        <br style="clear: both;" />
                        <p style="text-align:right;">
                        <button type="submit" class="btn btn-info">{lang_Send}</button>
                        </p>
                    </form>
                  </div>
                  <?php endif;?>
                  <?php endif;?>

                  <?php _widget('right_adssmall'); ?> 
                  
            </div>
        </div>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

<?php if(config_item('enable_property_details_on_top') == TRUE): ?>
<div id="top_content" class="on_top_fixed">

<?php if(!empty($estate_data_option_2)): ?>
<div class="alert alert-success span2">
<?php echo $estate_data_option_2; ?>
</div>
<?php endif;?>

<?php if(!empty($estate_data_option_4)): ?>
<div class="alert alert-info span1">
<?php echo $estate_data_option_4; ?>
</div>
<?php endif;?>

<?php if(!empty($estate_data_option_36)): ?>
<div class="alert span2">
{options_prefix_36} <?php echo $estate_data_option_36; ?> {options_suffix_36}
</div>
<?php endif;?>

<?php if(!empty($estate_data_option_57)): ?>
<div class="alert span1">
{options_prefix_57} <?php echo $estate_data_option_57; ?> {options_suffix_57}
</div>
<?php endif;?>
</div>

<?php if(!empty($estate_data_option_36) && !empty($estate_data_option_57)): ?>
<div class="alert span1">
<?php echo $estate_data_option_36/$estate_data_option_57; ?> {options_prefix_36}{options_suffix_36}/{options_suffix_57}
</div>
<?php endif;?>

<script>
$(document).ready(function(){
	
	var content = $('#content');
	var pos = content.offset();
	var top_content = $('#top_content');
	
	$(window).scroll(function(){
		if($(this).scrollTop() > pos.top){
          top_content.fadeIn('fast');
		} else if($(this).scrollTop() <= pos.top){
          top_content.fadeOut('fast');
		}
	});
    
});
</script>
<?php endif; ?>

<?php if(isset($views_last_30_min)): ?>
<link href="assets/js/pnotify/pnotify.custom.css" rel="stylesheet">
<script src="assets/js/pnotify/pnotify.custom.js"></script>

<script>
$(document).ready(function(){
    
    PNotify.prototype.options.styling = "bootstrap2";
    var stack_bottomleft = {"dir1": "right", "dir2": "up", "push": "top"};
    
    new PNotify({
        title: false,
        text: '<?php echo lang_check('This property has been seen by other'); ?> <?php echo $views_last_30_min; ?> <?php echo lang_check('users in the last 30 minutes'); ?>',
        addclass: "stack-bottomleft",
        stack: stack_bottomleft,
        hide: false,
        icon: 'icon-user'
    });
    
});
</script>
<?php endif; ?>
<?php if(config_item('onmouse_gallery_enabled') === TRUE): ?>
<script>
   $(document).ready(function(){ 
    
    $('.t_cal_link').mouseover(function(){
        $('.onmouse_gallery-image img').filter("[data-index='"+$(this).attr('rel')+"']").parent().css('display', 'block').show();
    })
    
    $('.t_cal_link').mouseout(function(){
        $('.onmouse_gallery-image').css('display', 'none');
    })
    
  });  
  
</script>
<?php endif; ?>

<script>
    
$(document).ready(function(){
        
    $(document).on('click', '.propertyCarousel .carousel-inner .item', function () {
        var myLinks = new Array();
        var current_link = $(this).attr('data-link');
        var curIndex=0;
        $('.propertyCarousel .carousel-inner .item').each(function (i) {
            var img_link = $(this).attr('data-link');
            myLinks[i] = img_link;
            if(current_link == img_link)
                curIndex = i;
        });
        options = {index: curIndex};
        blueimp.Gallery(myLinks,options);
        
        return false;
    });   
    
    if (!$('#blueimp-gallery').length)
    $('body').append('<div id="blueimp-gallery" class="blueimp-gallery">\n\
                     <div class="slides"></div>\n\
                     <h3 class="title"> </h3>\n\
                     <a class="prev">&lsaquo;</a>\n\
                     <a class="next">&rsaquo;</a>\n\
                     <a class="close">&times;</a>\n\
                     <a class="play-pause"></a>\n\
                     <ol class="indicator"></ol>\n\
                     </div>');
})
</script> 

  </body>
</html>