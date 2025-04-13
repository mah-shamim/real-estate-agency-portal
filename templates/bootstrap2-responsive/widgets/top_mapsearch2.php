 <script>
    $(document).ready(function(){
        
        $('.menu-onmap li a').click(function () {
            var tab_index = $('ul.menu-onmap li').index($(this).parent()[0]);
            
            if(tab_index == 0)
            {
                // fields manipulation for tab 0
                $('#search_option_19').show();
                $('#search_option_20').show();
            }
            else if(tab_index == 1)
            {
                // fields manipulation for tab 1
                $('#search_option_19').show();
                $('#search_option_20').show();
            }
            else if(tab_index == 2)
            {
                // fields manipulation for tab 2
                $('#search_option_19').hide();
                $('#search_option_20').hide();
            }
            
            //Auto search when click on property purpose
            manualSearch(0);
        });
        
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
<div class="wrap-search">
    <div class="container">
        <ul id="search_option_4" class="menu-onmap tabbed-selector">
            {options_values_li_4}
            <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
                <?php if(config_db_item('enable_qs') == 1): ?>
                <li class="list-property-button"><a href="<?php echo site_url('fquick/submission/'.$lang_code); ?>"><?php _l('Quick add listing'); ?></a></li>
                <?php else: ?>
                <li class="list-property-button"><a href="{myproperties_url}">{lang_Listproperty}</a></li>
                <?php endif; ?>
            <?php endif;?>
        </ul>
        <div class="search-form">
            <form class="form-inline">
                <input id="search_option_smart"  value="{search_query}" type="text" class="span6" placeholder="{lang_CityorCounty}" />
                <select id="search_option_2" class="span3 selectpicker">
                    {options_values_2}
                </select>
                
                <select id="search_option_3" class="span3 selectpicker nomargin">
                    {options_values_3}
                </select>
                <div class="form-row-space"></div>
                <input id="search_option_36_from" type="text" class="span3 mPrice" placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})" />
                <input id="search_option_36_to" type="text" class="span3 xPrice" placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})" />
                <input id="search_option_19" type="text" class="span3 Bathrooms" placeholder="{options_name_19}" />
                <input id="search_option_20" type="text" class="span3" placeholder="{options_name_20}" />
                <div class="form-row-space"></div>
                
                <select id="search_category_21" class="span7 selectpicker" title="{options_name_21}" multiple>
                    <option value="true{options_name_11}" data-input_id="11" <?php echo search_value(11,'selected');?>>{options_name_11}</option>
                    <option value="true{options_name_22}" data-input_id="22" <?php echo search_value(22,'selected');?>>{options_name_22}</option>
                    <option value="true{options_name_25}" data-input_id="25" <?php echo search_value(25,'selected');?>>{options_name_25}</option>
                    <option value="true{options_name_27}" data-input_id="27" <?php echo search_value(27,'selected');?>>{options_name_27}</option>
                    <option value="true{options_name_28}" data-input_id="28" <?php echo search_value(28,'selected');?>>{options_name_28}</option>
                    <option value="true{options_name_29}" data-input_id="29" <?php echo search_value(29,'selected');?>>{options_name_29}</option>
                    <option value="true{options_name_32}" data-input_id="32" <?php echo search_value(32,'selected');?>>{options_name_32}</option>
                    <option value="true{options_name_30}" data-input_id="30" <?php echo search_value(30,'selected');?>>{options_name_30}</option>
                    <option value="true{options_name_33}" data-input_id="33" <?php echo search_value(33,'selected');?>>{options_name_33}</option>
                </select>

                <br style="clear:both;" />
                <button id="search-start" type="submit" class="btn btn-info btn-large">&nbsp;&nbsp;{lang_Search}&nbsp;&nbsp;</button>
            </form>
        </div>
    </div>
</div>
