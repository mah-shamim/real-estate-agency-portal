    <script>
    $(document).ready(function(){
        <?php if(config_db_item('map_version') =='open_street'):?>
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
        
        <?php foreach($all_estates as $item): ?>
            <?php
                if(!isset($item['gps']))break;
                if(empty($item['gps']))continue;
            ?>
            var marker = L.marker(
                [<?php _che($item['gps']); ?>],
                {icon: L.divIcon({
                        html: '<img src="<?php _che($item['icon'])?>">',
                        className: 'open_steet_map_marker',
                        iconSize: [31, 46],
                        popupAnchor: [1, -35],
                        iconAnchor: [15, 45],
                    })
                }
            )/*.addTo(map)*/;
            marker.bindPopup("<?php echo _generate_popup($item, true); ?>");
            clusters.addLayer(marker);
            markers.push(marker);
        <?php endforeach; ?>
            map.addLayer(clusters);
            <?php if(config_db_item('map_fixed_position') === FALSE): ?>
                /* set center */
                if(markers.length){
                    var limits_center = [];
                    for (var i in markers) {
                        var latLngs = [ markers[i].getLatLng() ];
                        limits_center.push(latLngs)
                    };
                    var bounds = L.latLngBounds(limits_center);
                    <?php if(config_db_item('auto_set_zoom_disabled') !== FALSE): ?>
                        map.setView(bounds.getCenter());
                   <?php else:?>
                        map.fitBounds(bounds);
                   <?php endif;?>
                }
            <?php endif;?>
        <?php else:?>
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
               values:[
               {all_estates}
                   {latLng:[{gps}], adr:"{address}", options:{icon: "{icon}"}, data:"<img style=\"width: 150px; height: 100px;\" src=\"{thumbnail_url}\" alt=\"\" /><br />{address}<br />{option_2}<br /><span class=\"label label-info\">&nbsp;&nbsp;{option_4}&nbsp;&nbsp;</span><br /><a href=\"{url}\">{lang_Details}</a>"},
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
            }}
            });
        init_gmap_searchbox();
            
        <?php endif;?>
    });    
    </script>
<?php if(config_db_item('map_version') !='open_street'):?>
<input id="pac-input" class="controls" type="text" placeholder="{lang_Search}" />
<?php endif;?>
<div class="wrap-map" id="wrap-map">
</div>