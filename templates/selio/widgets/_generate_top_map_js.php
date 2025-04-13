<?php
$tree_field_id = 79;
$CI = &get_instance();
$tree_field_icons = array();
$tree_field_font_icons = array();
$check_option = generate_treefields_list(79);
foreach ($check_option as $key => $value) {
    if(!empty($value['icon'])) {
        $tree_field_icons[$value['value_path']] = $value['icon'];
    }
    if(!empty($value['font_icon_code']))
    {
        $tree_field_font_icons[$value['value_path']] = $value['font_icon_code'];
    }
}
?>
<script>


var markers = new Array();
var map;
var marker_clusterer ;


if(typeof mapStyle == 'undefined') {
    var mapStyle = [
  {
    "featureType": "landscape",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#fcf4dc"
      }
    ]
  },
  {
    "featureType": "landscape",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#c0c0c0"
      },
      {
        "visibility": "on"
      }
    ]
  }
];
}


$(document).ready(function(){
    var myLocationEnabled = true;
    var style_map = mapStyle;
    var scrollwheelEnabled = true;

    <?php if(config_db_item('map_version') =='open_street'):?>

    if($('#main-map').length){    
        map = L.map('main-map', {
            <?php if(config_item('custom_map_center') === FALSE): ?>
            center: [{all_estates_center}],
            <?php else: ?>
            center: [<?php echo config_item('custom_map_center'); ?>],
            <?php endif; ?>
            zoom: {settings_zoom}-2,
            scrollWheelZoom: scrollwheelEnabled,
            dragging: !L.Browser.mobile,

            
            tap: !L.Browser.mobile
        });     
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(map);

        <?php foreach($all_estates as $item): ?>
            <?php
                if(!isset($item['gps']))break;
                if(empty($item['gps']))continue;
            ?>
            <?php 
                $item['icon'] = '';
                if(!empty($item['option_79']) && isset($values[$item['option_79']])){
                    $item['icon'] = $values[$item['option_79']];
                }
            ?>           
                        
            <?php 
            if(isset($tree_field_font_icons[_ch($item['option_79'])])){
                 echo "var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"".($tree_field_font_icons[_ch($item['option_79'])])."\"></i></div><div class=\"back face\"> <i class=\"".($tree_field_font_icons[_ch($item['option_79'])])."\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
            } elseif(isset($tree_field_icons[_ch($item['option_79'])])){
                echo "var image = '".$tree_field_icons[_ch($item['option_79'])]."';; var innerMarker = '<div class=\"marker-container marker-container-image\"><div class=\"marker-card\"><div class=\"front face\"><img src='+image+'></img></div></div><div class=\"marker-arrow\"></div></div></div>';";
            }else{
                echo "var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"la la-home\"></i></div><div class=\"back face\"> <i class=\"la la-home\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
            }
            ?>    

            var marker = L.marker(
                [<?php _che($item['gps']); ?>],
                {icon: L.divIcon({
                        html: innerMarker,
                        className: 'open_steet_map_marker google_marker',
                        iconSize: [40, 46],
                        popupAnchor: [1, -35],
                        iconAnchor: [20, 46],
                    })
                }
            )/*.addTo(map)*/;

            marker.bindPopup("<?php echo _generate_popup($item, true); ?>", jpopup_customOptions);
            clusters.addLayer(marker);
            markers.push(marker);
        <?php endforeach; ?>
        map.addLayer(clusters);   
        
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
        
        if(!markers.length){
            <?php if(search_value(64)):?>
                $.get('https://nominatim.openstreetmap.org/search?format=json&q=<?php echo search_value(64);?>', function(data){
                    if(data.length && typeof data[0]) {
                        map.setView([data[0].lat, data[0].lon]); 
                    } else {
                    }
                });
            <?php endif;?>
        }
    } 
    <?php else:?>
    // option
    if($('#main-map').length){

    var mapOptions = {
        <?php if(config_item('custom_map_center') === FALSE): ?>
        center: new google.maps.LatLng({all_estates_center}),
        <?php else: ?>
        center: new google.maps.LatLng(<?php echo config_item('custom_map_center'); ?>),
        <?php endif; ?>
        zoom: {settings_zoom},
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: scrollwheelEnabled,
        mapTypeControlOptions: {
          mapTypeIds: c_mapTypeIds,
          position: google.maps.ControlPosition.TOP_RIGHT
        },
        styles: mapStyle
    };


            map = new google.maps.Map(document.getElementById('main-map'), mapOptions);

            <?php foreach($all_estates as $item): ?>
                <?php
                    if(!isset($item['gps']))break;
                    if(empty($item['gps']))continue;
                ?>

                <?php 
                    $item['icon'] = '';
                    if(!empty($item['option_79']) && isset($values[$item['option_79']])){
                        $item['icon'] = $values[$item['option_79']];
                    }
                ?>
                            
            <?php 
            if(isset($tree_field_font_icons[_ch($item['option_79'])])){
                 echo "var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"".($tree_field_font_icons[_ch($item['option_79'])])."\"></i></div><div class=\"back face\"> <i class=\"".($tree_field_font_icons[_ch($item['option_79'])])."\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
            } elseif(isset($tree_field_icons[_ch($item['option_79'])])){
                echo "var image = '".$tree_field_icons[_ch($item['option_79'])]."'; var innerMarker = '<div class=\"marker-container marker-container-image\"><div class=\"marker-card\"><div class=\"front face\"><img src='+image+'></img></div></div><div class=\"marker-arrow\"></div></div></div>'";
            }else{
                echo "var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"la la-home\"></i></div><div class=\"back face\"> <i class=\"la la-home\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
            }
            ?> 
                        
            var myLatlng = new google.maps.LatLng(<?php _che($item['gps']); ?>);
            var callback = {
                            'click': function(map, e){
                                var activemarker = e.activemarker;
                                jQuery.each(markers, function(){
                                    this.activemarker = false;
                                })

                                sw_infoBox.close();
                                if(activemarker) {
                                    e.activemarker = false;
                                    return true;
                                }

                                var boxOptions = {
                                    content: "<?php echo _generate_popup($item, true); ?>",
                                    disableAutoPan: false,
                                    alignBottom: true,
                                    maxWidth: 0,
                                    pixelOffset: new google.maps.Size(-159, -65),
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
                    
            var marker = new CustomMarker(myLatlng,map,innerMarker,callback);
            markers.push(marker);

            <?php endforeach; ?>

            marker_clusterer = new MarkerClusterer(map, markers, clusterConfig);

    if(mapSearchbox){   
        init_map_searchbox(map);
    }  

    if(myLocationEnabled){
        var controlDiv = document.createElement('div');
        controlDiv.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv);
        HomeControl(controlDiv, map)
        }
    }


    if(rectangleSearchEnabled)
     {
         var controlDiv2 = document.createElement('div');
         controlDiv2.index = 2;
         map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv2);
         RectangleControl(controlDiv2, map)
     } 

    <?php endif;?>
})

</script>