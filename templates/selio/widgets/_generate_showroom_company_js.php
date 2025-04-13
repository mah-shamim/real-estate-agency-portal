<script>
    var map;
    $(document).ready(function(){

    $("#route_from_button").click(function () { 
         window.open("https://maps.google.hr/maps?saddr="+$("#route_from").val()+"&daddr={showroom_data_address}@{showroom_data_gps}&hl={lang_code}",'_blank');
         return false;
     });

    if($('#location-map').length){

    var myLocationEnabled = true;
    var style_map = '';
    var scrollwheelEnabled = false;
    <?php if(config_db_item('map_version') =='open_street'):?>


    var property_map;
    property_map = L.map('location-map', {
        center: [{showroom_data_gps}],
        zoom: {settings_zoom},
        scrollWheelZoom: scrollWheelEnabled,
        dragging: !L.Browser.mobile,
        tap: !L.Browser.mobile
    });     
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(property_map);
    var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(property_map);
    var property_marker = L.marker(
        [{showroom_data_gps}]
    ).addTo(property_map);

    property_marker.bindPopup("<div class='pin_box'>{showroom_data_address}<br />{lang_GPS}: {showroom_data_gps}</div>");

    <?php else:?>
    var markers = new Array();
    var mapOptions = {
        center: new google.maps.LatLng({showroom_data_gps}),
        zoom: {settings_zoom},
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: scrollwheelEnabled,
        styles:style_map
    };

    var map = new google.maps.Map(document.getElementById('location-map'), mapOptions);

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng({showroom_data_gps}),
        map: map,
    });

    var myOptions = {
        content: "<div class='pin_box'>{showroom_data_address}<br /><?php _l('GPS');?>: {showroom_data_gps}</div>",
        disableAutoPan: false,
        maxWidth: 0,
        pixelOffset: new google.maps.Size(-108, -15),
        zIndex: null,
        closeBoxURL: "",
        infoBoxClearance: new google.maps.Size(1, 1),
        position: new google.maps.LatLng({showroom_data_gps}),
        isHidden: false,
        pane: "floatPane",
        enableEventPropagation: false
    };

    marker.infobox = new InfoBox(myOptions);
    marker.infobox.isOpen = false;
    markers.push(marker);

    // action        
    google.maps.event.addListener(marker, "click", function (e) {
        var curMarker = this;

        $.each(markers, function (index, marker) {
            // if marker is not the clicked marker, close the marker
            if (marker !== curMarker) {
                marker.infobox.close();
                marker.infobox.isOpen = false;
            }
        });

        if(curMarker.infobox.isOpen === false) {
            curMarker.infobox.open(map, this);
            curMarker.infobox.isOpen = true;
            map.panTo(curMarker.getPosition());
        } else {
            curMarker.infobox.close();
            curMarker.infobox.isOpen = false;
        }
    });

    if(myLocationEnabled){
        var controlDiv = document.createElement('div');
        controlDiv.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(controlDiv);
        HomeControl(controlDiv, map)
        }

         <?php endif;?>
        }
    })
</script>