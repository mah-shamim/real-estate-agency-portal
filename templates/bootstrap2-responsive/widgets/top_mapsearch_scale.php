 <?php
 /*
  * scale used nouislider
  *     assets/js/nouislider/nouislider.min.js
  *     assets/js/nouislider/nouislider.min.css
  *    
  */
 
 ?>


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
                <select id="search_category_21" class="span3 selectpicker custom-select nomargin" title="{options_name_21}" multiple style="margin-top:5px;">
                    <option value="true{options_name_11}">{options_name_11}</option>
                    <option value="true{options_name_22}">{options_name_22}</option>
                    <option value="true{options_name_25}">{options_name_25}</option>
                    <option value="true{options_name_27}">{options_name_27}</option>
                    <option value="true{options_name_28}">{options_name_28}</option>
                    <option value="true{options_name_29}">{options_name_29}</option>
                    <option value="true{options_name_32}">{options_name_32}</option>
                    <option value="true{options_name_30}">{options_name_30}</option>
                    <option value="true{options_name_33}">{options_name_33}</option>
                </select>
                <div class="form-row-space"></div>
                <div class='custom-scale custom-scale-conteiner span6' style="margin:0;">
                <div class="span4 scale-conteiner scale-price custom-span4" id="scale-price" style="">
                    <div class='row-fluid'>
                        <span>{options_name_36} ({options_prefix_36}{options_suffix_36})</span>
                    </div>
                    <div class='row-fluid scale-form'>
                        <input id="search_option_36_from" type="text" class="span5 minScale mPrice scale-input" data-suffix='{options_suffix_36}' data-prefix='{options_prefix_36}'  value="<?php echo search_value('36_from'); ?>"  placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})"/>
                        <img src="assets/img/glyphicons/glyphicons-434-minus.png" class="scale-icon-minus" alt=""/>
                        <input id="search_option_36_to" type="text" class="span5 maxScale xPrice scale-input" data-suffix='{options_suffix_36}' data-prefix='{options_prefix_36}' value="<?php echo search_value('36_to'); ?>"  placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})"/>
                    </div>
                    <div class='row-fluid'>
                        <div class="span12 scale scale-price" id="slide-scale"></div>
                    </div>
                </div>
                </div>
                <select id="search_option_19_from" class="span3 selectpicker  ">
                        <option value="">{options_name_19}</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                        <option value="6">6+</option>
                </select>
                <select id="search_option_20_from" class="span3 selectpicker  ">
                        <option value="">{options_name_20}</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                        <option value="6">6+</option>
                </select>

                <br style="clear:both;" />
                <button id="search-start" type="submit" class="btn btn-info btn-large">&nbsp;&nbsp;{lang_Search}&nbsp;&nbsp;</button>
            </form>
        </div>
    </div>
</div>



<script>
    
   $('document').ready(function(){
    
    scaleFilter('#scale-price',  0, 1000001, <?php echo (!empty(search_value('36_from')))? search_value('36_from') : '0'; ?>, <?php echo (!empty(search_value('36_to')))? search_value('36_to') : '1000001'; ?>,'{options_prefix_36}{options_suffix_36} ','',1)
    
    
<?php if(empty(search_value('36_from')) and empty(search_value('36_to'))): ?> 
    //    $('.scale-price .minScale, .scale-price .maxScale').val('');
<?php endif;?>  
     
})
   
   
   
/*
 * ScaleFilter add scale
 * @param {string} object's selector
 * @param {int} min value
 * @param {int} max value
 * @param {int} minStart value
 * @param {int} maxStart value
 * @param {str} prefix value
 * @param {str} suffix value
 * @param {int} positionInfin '+' after or before  = 1 before
 * 
 */
    function scaleFilter(object, min, max,minStart, maxStart, prefix, suffix, positionInfin){
        var marginSlider = document.querySelector(object+ ' #slide-scale');
        noUiSlider.create(marginSlider, {
                start: [ minStart, maxStart ],
                margin: 1,
                step:1,
                connect: true,
                range: {
                        'min': min,
                        'max': max
                }
        });

        marginSlider.noUiSlider.on('update', function ( values, handle ) {
           // var prefix='R5';
                if ( handle ) {
                         var value=parseInt(values[handle]);
                         var infinity1='';
                         var infinity2='';
                         if(value==max) {
                             if(positionInfin==1) {
                                 infinity1='+'; 
                             } else {
                                  infinity2='+'; 
                             }
                            value--;
                         }
                         
                        $(object+' .maxScale').val(prefix+$.number(value, 0, ',', '.')+infinity1+suffix+infinity2);
                } else {
                        $(object+' .minScale').val(prefix+$.number(parseInt(values[handle]),  0, ',', '.')+suffix);
                }
                if(values[0]!='' && values[1]!=''){
                if( $(object+' .mPrice').val()=='' ||  $(object+' .maxScale').val()=='' ||
                    $('.mPrice').val()=='' ||  $('.xPrice').val()=='') {
                        var value=parseInt(values[1]);
                         var infinity1='';
                         var infinity2='';
                         if(value==max) {
                             if(positionInfin==1) {
                                 infinity1='+'; 
                             } else {
                                  infinity2='+'; 
                             }
                            value--;
                         }
                    $(object+' .maxScale').val(prefix+$.number(value)+infinity1+suffix+infinity2);
                    $(object+' .minScale').val(prefix+$.number(parseInt(values[0]))+suffix);
                    
                }
            }
        });

         
      //  $(object+' .minScale').val('');
      //  $(object+' .maxScale').val('');
      
        $(object+' .maxScale,'+object+' .minScale').change(function(){
            $(document).click(function(){
                var minNew='';
                minNew=parseInt($.number($(object+' .minScale').val()).replace(/,/g, ""));
                var maxNew='';
                maxNew=parseInt($.number($(object+' .maxScale').val()).replace(/,/g, ""));
                var max_t=max-1;
                // console.log(maxNew)
                // filt 1
                if(maxNew =='' || maxNew>max_t) var maxNew=max_t;
                if(minNew =='' || minNew<min) var minNew=min;
                if(maxNew<min) var maxNew=min;
                if(minNew>max_t) var minNew=max_t;

                marginSlider.noUiSlider.set( [minNew,maxNew] );
                $(document).unbind('click');
                return false;
            })
         }); 
    }   
    
</script>