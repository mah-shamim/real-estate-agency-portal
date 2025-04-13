<?php if(!empty($estate_data_gps)): ?>
<?php
$tree_field_id = 79;
$CI = & get_instance();
$values_icon = array();
$CI->load->model('treefield_m');
$CI->load->model('file_m');

$font_icon ='';
$pin_icon ='';
if(_ch($estate_data_option_79,false)) {
    $category_id = $CI->treefield_m->id_by_path(79, $lang_id, $estate_data_option_79);
    if($category_id) {
        $category = $CI->treefield_m->get_lang($category_id, $lang_id);
        if(!empty($category->image_filename))
        {
            $files_r = $CI->file_m->get_by(array('repository_id' => $category->repository_id),FALSE, 5,'id ASC');
            // check second image
            if($files_r and isset($files_r[1]) and file_exists(FCPATH.'files/thumbnail/'.$files_r[1]->filename)) {
                $pin_icon = base_url('files/'.$files_r[1]->filename);
            }
        }

        if(!empty($category->font_icon_code))
        {
            $font_icon = $category->font_icon_code;
        }
    }
}
?>
<script>
(function(){
var IMG_FOLDER = "assets/js/dpejes";
var map;
$(document).ready(function(){
    
        <?php 
        $estate_data_icon = '';
        if(!empty($estate_data_option_79) && isset($values[$estate_data_option_79])){
            $estate_data_icon = $values[$estate_data_option_79];
        }

        if($font_icon){
             echo "var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"".($font_icon)."\"></i></div><div class=\"back face\"> <i class=\"".($font_icon)."\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
        } elseif($pin_icon){
            echo "var image = '".$pin_icon."'; var innerMarker = '<div class=\"marker-container marker-container-image\"><div class=\"marker-card\"><div class=\"front face\"><img src='+image+'></img></div></div><div class=\"marker-arrow\"></div></div></div>'";
        }else{
            echo "var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"la la-home\"></i></div><div class=\"back face\"> <i class=\"la la-home\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
        }
        ?>    
                    
    
        <?php if(config_db_item('map_version') =='open_street'):?>
        var content= '<div class="infobox map-box">\n\
                <a href="#" class="listing-img-container">\n\
                    <div class="infoBox-close"><i class="fa fa-times"></i>\n\
                    </div><img src="<?php echo _simg($slideshow_images[0]['url'], '575x700', true); ?>" alt="<?php echo _ch($estate_data_option_10); ?>">\n\
                    <div class="listing-item-content">\n\
                        <h3><?php echo _ch($estate_data_option_10); ?></h3>\n\
                        <span><i class="la la-map-marker"></i><?php echo _ch($estate_data_address); ?></span>\n\
                    </div>\n\
                </a>\n\
            </div>';
            

                    
            var property_map;
            property_map = L.map('property-map', {
                center: [{estate_data_gps}],
                zoom: {settings_zoom}+6,
                scrollWheelZoom: scrollWheelEnabled,
                dragging: !L.Browser.mobile,
                tap: !L.Browser.mobile
            });     
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(property_map);
            var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(property_map);
            var property_marker = L.marker(
                [{estate_data_gps}],
                {icon: L.divIcon({
                        html: innerMarker,
                        className: 'open_steet_map_marker google_marker',
                        iconSize: [40, 46],
                        popupAnchor: [1, -35],
                        iconAnchor: [20, 46],
                    })
                }
            ).addTo(property_map);

            property_marker.bindPopup(content);

       <?php else:?>   
        
    // map init    
    if($('#property-map').length){

        var markers1 = new Array();
        var mapOptions1 = {
            center: new google.maps.LatLng({estate_data_gps}),
            zoom: {settings_zoom},
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: scrollWheelEnabled,
            styles: mapStyle
        };

        map = new google.maps.Map(document.getElementById('property-map'), mapOptions1);
        map_propertyLoc = map  




        var content= '<div class="infobox map-box">\n\
                        <a href="#" class="listing-img-container">\n\
                            <div class="infoBox-close"><i class="fa fa-times"></i>\n\
                            </div><img src="<?php echo _simg($slideshow_images[0]['url'], '575x700', true); ?>" alt="<?php echo _ch($estate_data_option_10); ?>">\n\
                            <div class="listing-item-content">\n\
                                <h3><?php echo _ch($estate_data_option_10); ?></h3>\n\
                                <span><i class="la la-map-marker"></i><?php echo _ch($estate_data_address); ?></span>\n\
                            </div>\n\
                        </a>\n\
                    </div>';
        var myLatlng = new google.maps.LatLng({estate_data_gps});
        var callback = {
                    'click': function(map, e){
                        var activemarker = e.activemarker;
                        this.activemarker = false;

                        sw_infoBox.close();
                        if(activemarker) {
                            e.activemarker = false;
                            return true;
                        }
                        
                        var boxOptions = {
                            content: content,
                            disableAutoPan: false,
                            alignBottom: true,
                            maxWidth: 0,
                            pixelOffset: new google.maps.Size(-157, -15),
                            zIndex: null,
                            closeBoxMargin: "0",
                            closeBoxURL: "",
                            infoBoxClearance: new google.maps.Size(1, 1),
                            isHidden: false,
                            pane: "floatPane",
                            enableEventPropagation: false,
                            closeBoxURL: "<?php echo base_url('templates/selio/assets/img/close.png');?>"
                        };

                        sw_infoBox.setOptions( boxOptions);
                        sw_infoBox.open( map, e );

                        e.activemarker = true;
                        
                    }
            };
            
        var marker = new CustomMarker(myLatlng,map_propertyLoc,innerMarker,callback);

        markers1.push(marker);
        

        if(myLocationEnabled){
            var controlDiv = document.createElement('div');
            controlDiv.index = 1;
            map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv);
            HomeControl(controlDiv, map)
        }

    } 
     
    <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/places.js')): ?>       
    // init_gmap_searchbox();
    if (typeof swplaces_init_directions == 'function')
     {
         $(".places_select a").click(function(){
             init_places($(this).attr('data-rel'), $(this).find('img').attr('src'));
         });

         var selected_place_type = 4;

         swplaces_init_directions();
         directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});

         directionsDisplay.setMap(map);
         init_places($(".places_select a:eq("+selected_place_type+")").attr('data-rel'), $(".places_select a:eq("+selected_place_type+") img").attr('src'));

     }
    <?php endif;?>
    <?php endif;?>   
        
}); 

<?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/places.js') && config_db_item('map_version') !='open_street'): ?>  
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

    var places_type_array = places_types.split(','); 

    var request = {
        location: pyrmont,
        radius: 2000,
        types: places_type_array
    };

    infowindow = new google.maps.InfoWindow();
    placesService = new google.maps.places.PlacesService(map);
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
    map: map,
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
        infowindow.setContent(place.name+'<br /><?php _l('Distance');?>: '+distanceKm+'<?php _l('Km');?>'+
                              '<br /><?php _l('WalkingTime');?>: '+walkingTime+'<?php _l('Min');?>'+
                              '<br /><a target="_blank" href="'+placeDetails.url+'"><?php _l('Details');?></a>');
        infowindow.open(map_propertyLoc, marker);
    });

  });
}

//calculates distance between two points
function calcDistance(p1, p2){
  return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
}
<?php endif;?>

$(".route_suggestion").submit(function (e) { 
    e.preventDefault();
    window.open("https://maps.google.hr/maps?saddr="+$("#route_from").val()+"&daddr={estate_data_address}@{estate_data_gps}&hl={lang_code}",'_blank');
    return false;
}); 
 })()  
       
</script>
<?php endif;?>

