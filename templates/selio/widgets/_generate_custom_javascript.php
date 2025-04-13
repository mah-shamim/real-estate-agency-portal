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
var rent_inc_id = '55';
var scrollWheelEnabled = false;
var myLocationEnabled = true;
var rectangleSearchEnabled = true;
var mapSearchbox = true;
var mapRefresh = true;
var map_main;
var styles;
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
var timerMap;
var firstSet = false;
var selectorResults = '#results_conteiner';
<?php if(config_db_item('map_version') =='open_street'):?>
var clusters ='';
clusters = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
<?php endif;?>
var jpopup_customOptions =
{
'maxWidth': 'initial',
'width': 'initial',
'className' : 'popupCustom'
}
        
<?php if(config_db_item('map_version') !='open_street'):?>
var c_mapTypeId = "style1"; // google.maps.MapTypeId.ROADMAP;
var c_mapTypeIds = ["style1",
                    google.maps.MapTypeId.ROADMAP,
                    google.maps.MapTypeId.HYBRID];   
  // Cluster config start //
clusterConfig = {
        gridSize: 40,
        styles: [
                {
                    height: 52,
                    url: 'assets/img/cluster/m1.png',
                    width: 52,
                    textColor: '#fff'
                }
        ]
    };
<?php endif;?>

/* TOOLS */

$(document).ready(function() {
    $("search_showroom_form").submit( function() {
        if($('#search_showroom').val().length > 2 || $('#search_showroom').val().length == 0)
        {
            $.post('<?php echo $ajax_showroom_load_url; ?>', {search: $('#search_showroom').val()}, function(data){
                $('.property_content_position').html(data.print);
                reloadElements();
            }, "json");
        }
    });

    $("#search_news_form").submit(function (e) {
        e.preventDefault();
        if ($('#search_news').val().length > 2 || $('#search_news').val().length == 0)
        {
            $.post('<?php echo $ajax_news_load_url; ?>', {search: $('#search_news').val()}, function (data) {
                $('.list-result').html(data.print);
                reloadElements();
            }, "json");
        }
    });

    $("#search_expert").keyup( function() {
        if($(this).val().length > 2 || $(this).val().length == 0)
        {
            $.post('<?php echo $ajax_expert_load_url; ?>', {search: $('#search_expert').val()}, function(data){
                $('.property_content_position').html(data.print);
                reloadElements();
            }, "json");
        }
    });
        
    /* DataTable ini */
    var table = $('.data_table').DataTable({
            "responsive": true,
            'oLanguage': {
                'sPrevious': "<?php _jse(lang_check('Search')); ?>",
                'sNext': "<?php _jse(lang_check('Search')); ?>",
                'sSearch': "<?php _jse(lang_check('Search')); ?>",
                "sLengthMenu": "<?php _jse(lang_check('Show _MENU_ entries')); ?>",
                "sInfoEmpty": "<?php _jse(lang_check('Showing 0 to 0 of 0 entries')); ?>",
                "sInfo": "<?php _jse( lang_check('Showing _START_ to _END_ of _TOTAL_ entries')); ?>",
                "sEmptyTable": "<?php _jse(lang_check('No data available in table')); ?>",
            },
    });
    /* End DataTable ini */
            // Filters Start //
            
            <?php if(empty($_GET['search']) && empty($search_query)): ?>
            /*$(".checkbox_am, .search-form .advanced-form-part label.checkbox input").prop('checked', false);
            $(".input_am, .input_am_from, .input_am_to, .search-form input[type=text], .search-form select").val('');*/
            <?php endif; ?>
            
            $("button.refresh_filters").click(function () { 
                manualSearch(0);
                return false;
            });
            
            
            manualSearch(0,false,false,true);
            
            <?php if(isset($_GET['search'])&& !empty($_GET['search'])): ?>
            <?php else:?>
                if($('.counter_by_id_enabled').length){
                    showCountersBy_id([],79); 
                }
            <?php endif;?>
            // Filters End //    
            
    
        // [START] Save search //  
        
        $("#search-save").click(function(){
            
            manualSearch(0, '#content-block', true);
            
            return false;
        });
        
        // [END] Save search //
        //Typeahead

            $('#search_option_smart').typeahead({
                minLength: 1,
                source: function(query, process) {
                    $.post('{typeahead_url}/smart', { q: query, limit: 8 }, function(data) {
                        process(JSON.parse(data));
                    });
                }
            });
            
            $('.twitter-typeahead input:first-child').attr('style', 'position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; opacity: 1')
            $('#search_option_smart').attr('style', 'position: relative; vertical-align: top;')
            
            /* Search start */

            $('.menu-onmap li a').click(function () { 
              if(!$(this).parent().hasClass('list-property-button'))
              {
                  $(this).parent().parent().find('li').removeClass("active");
                  $(this).parent().addClass("active");
                  
                  if(loadOnTab) manualSearch(0, false);
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
                
            if($('.menu-onmap').length){
                $('.menu-onmap li label').click(function(){
                    $('.menu-onmap li').removeClass('active');
                    $(this).parent().addClass('active');
                })
                
            }    
                
            
            $('.sw-search-start').click(function (e) { 
                e.preventDefault();
                manualSearch(0);
                return false;
            });
            
            $('.sw-search-start').closest('form').find('input').on('keypress', function (e) { 
                if (e.which == 13) {
                    e.preventDefault();
                    manualSearch(0);
                    return false;
                }
            });
            /* Search end */
            
            /* Date picker */
            
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
            
            var nowTemp = new Date();
            
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            $('.datetimepicker_standard').datepicker().on('changeDate', function(ev) {
                $(this).datepicker('hide');
            });

         <?php if(file_exists(APPPATH.'controllers/admin/booking.php')): ?>
            /* Search booking form */
            
            var checkin_booking = $('#booking_date_from').datepicker({
                place: function(){
                    var element = this.component ? this.component : this.element;
                    element.after(this.picker);
		},   
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
                place: function(){
                    var element = this.component ? this.component : this.element;
                    element.after(this.picker);
		},   
                    
                    
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
            
            /* Date picker end */

            {has_extra_js}
            
            loadjQueryUpload();

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
            
            {/has_extra_js}

     reloadElements();

});

        function manualSearch(v_pagenum, scroll_enabled, onlysave, only_update)
        {
            if (typeof scroll_enabled === 'undefined') scroll_enabled = "#results_conteiner";
            if (typeof onlysave === 'undefined') onlysave = false;
            if (typeof only_update === 'undefined') only_update = false;

            
            // Order ASC/DESC
            var v_order = $('#search_order').val();
            
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
            
            // Improved tabbed selector code
            /*$(".tabbed-selector").each(function() {
                var selected_text = $(this).find(".active:not(.all-button) a").html();
                data['v_'+$(this).attr('id')] = selected_text;
            });*/
            
            // Add custom data values, automatically by fields inside search-form
            $('.search-form  input:not(.skip), .search-form  select:not(.skip)').each(function (i) {
                if($(this).attr('type') == 'checkbox')
                {
                    if($(this).prop('checked'))
                    {
                        data['v_'+$(this).attr('id')] = $(this).val();
                    }
                }
                else if($(this).attr('type') == 'radio')
                {   
                    if($(this).attr('id') !='undefined')
                    if ($(this).prop('checked'))
                    {
                        data['v_'+$(this).attr('name')] = $(this).val();
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
                $('.search-form input, .search-form select').each(function (i) {
                    if($(this).attr('type') == 'checkbox')
                    {
                        if ($(this).attr('checked') && typeof $(this).attr('id') !='undefined' && $(this).attr('id') !='undefined')
                        {
                            data['v_'+$(this).attr('id')] = $(this).val();
                            
                            var option_name = '';
                            //var attr = $(this).attr('placeholder');
                            var attr = $(this).attr('value').substring(4);
                            if(typeof attr !== 'undefined' && attr !== false)
                            {
                                option_name = attr;
                            }
                            
                            if($(this).val() != '')
                                tags_html+='<button class="btn btn-small btn-warning filter-tag ck" rel="'+$(this).attr('id')+'" type="button"><span class="fa fa-remove"></span> '+option_name+'</button>&nbsp;';
                        
                        }
                    }
                    else if($(this).hasClass('tree-input'))
                    {
                        // different way
                    }
                    else if(typeof $(this).attr('id') !='undefined' && $(this).attr('id') !='undefined')
                    {
                        data['v_'+$(this).attr('id')] = $(this).val();
                        
                        var option_name = '';
                        var attr = $(this).attr('placeholder');
                        if(typeof attr !== 'undefined' && attr !== false)
                        {
                            option_name = attr+': ';
                        }
                        
                        if($(this).val() != '' && $(this).val() != 'NULL' && $(this).val() != null && $(this).val() != '0')
                            tags_html+='<button class="btn btn-small btn-primary filter-tag" rel="'+$(this).attr('id')+'" type="button"><span class="fa fa-remove"></span> '+option_name+$(this).val()+'</button>&nbsp;';
                    
                    }
                });
                

                if(typeof data['v_search_option_4'] != 'undefined')
                if(data['v_search_option_4'].length > 0)
                    tags_html+='<button class="btn btn-small btn-danger filter-tag" rel="4" type="button"><span class="fa fa-remove"></span> '+data['v_search_option_4']+'</button>&nbsp;';
                
                if(tags_html != '')
                {
                    $("#tags-filters").css('display', 'block');
                    
                    $("#tags-filters").html(tags_html);
                    
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
                            $("input.checkbox_am[option_id='"+m_id+"']").prop('checked', false);
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
            // 
            // Sets the map on all markers in the array.
            showCounters(data); 
            if($('.counter_by_id_enabled').length)
                showCountersBy_id(data,79); 

            if(only_update) {
                return false;
            }
            $("#ajax-indicator-1").show();
            
            
            if(onlysave == true)
            {
                $.post("{api_private_url}/save_search/{lang_code}", data, 
                       function(data){
                    
                    ShowStatus.show(data.message);
                    if(!data.success){
                        $("#sign-popup").toggleClass("active");
                        $("body").addClass("overlay-bgg");
                    }
                          
                    $("#ajax-indicator-1").hide();
                });
                
                return;
            }
                        
            $('.result_preload_indic').show();
            $('.sw-search-start').find('.fa-ajax-indicator').removeClass('hidden').parent().addClass('loading');
            
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
                                let option_id = sel.find('option[value="'+c_v+'"]').attr('data-input_id')
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
                
            <?php if(_ch($page_id, false) && $page_id!=get_results_page_id()): ?>
                if(!$('#results_conteiner').length){
                    if( data['v_search_radius']==0)
                        data['v_search_radius'] ='';
                    <?php

                    if(!get_results_page_id()) {
                        echo 'ShowStatus.show("'._js(lang_check("Please define results page in settings")).'");';
                        echo 'return false;';
                    }
                    
                    // get title;
                    $CI =& get_instance();
                    $CI->load->model('page_m');
                    $_page = $CI->page_m->get_lang(get_results_page_id(),false,$lang_id);
                    
                    $_title=$_page->{'navigation_title_'.$lang_id};

                    ?>
                    window.location.href='<?php echo site_url($lang_code.'/'.get_results_page_id().'/'.url_title_cro($_title, '-', TRUE))?>?search='+JSON.stringify(data);
                    return false;
                }
            <?php endif;?>    
 
            var ajax_data = data;
            $.post("{ajax_load_url}/"+v_pagenum, data,
                        function(data){
                 
                // [START] Remove all markers
                if($('#main-map').length){
                    
                    <?php if(config_db_item('map_version') =='open_street'):?>
                                
                    if(map=="init") {       
                        map = L.map('wrap-map', {
                            <?php if(config_item('custom_map_center') === FALSE): ?>
                            center: [{all_estates_center}],
                            <?php else: ?>
                            center: [<?php echo config_item('custom_map_center'); ?>],
                            <?php endif; ?>
                            zoom: {settings_zoom}-2,
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
                    
                    <?php else:?>
                    deleteMarkers(markers)
                    sw_infoBox.close();
                    markers = [];
                    if(marker_clusterer != null)
                        marker_clusterer.clearMarkers();
                    <?php endif;?>
                    
                }
                // [END] Remove all markers
                if(mapRefresh && $('#main-map').length > 0)
                {
                //Remove all markers
               /* map_main.aviators_map('removeMarkers');*/
                if(data.results.length > 0)
                {

                var $tree_field_icons = [];
                var $tree_field_font_icons = [];
                <?php
                $tree_field_icons = array();
                $tree_field_font_icons = array();
                $check_option = generate_treefields_list(79);
                foreach ($check_option as $key => $value) {
                    // check second image
                    if(!empty($value['icon'])) {
                        ?>
                        $tree_field_icons['<?php _jse($value['value_path']);?>']= '<?php echo $value['icon'];?>';
                        <?php
                    }
                    if(!empty($value['font_icon_code']))
                    {
                        ?>
                        $tree_field_font_icons['<?php _jse($value['value_path']);?>']= '<?php _che($value['font_icon_code']);?>';
                        <?php
                    }
                }
                ?>
                  <?php if(config_db_item('map_version') =='open_street'):?>

                    $.each(data.results, function(index, listing) {
                      if( typeof listing.latLng !== 'undefined'){
                            var category_path = listing.data.match(/Widget-preview-category-path:(.*)/) || '';
                            category_path = $.trim(category_path[1]);
                            
                          if(typeof $tree_field_font_icons[category_path] !='undefined') {
                                var innerMarker = '<div class="marker-container"><div class="marker-card"><div class="front face"><i class="'+$tree_field_font_icons[category_path]+'"></i></div><div class="back face"> <i class="'+$tree_field_font_icons[category_path]+'"></i></div><div class="marker-arrow"></div></div></div>';
                            } else if(typeof $tree_field_icons[category_path] !='undefined'){
                                var image = $tree_field_icons[category_path];
                                var innerMarker = '<div class="marker-container marker-container-image"><div class="marker-card"><div class="front face"><img src='+image+'></img></div></div><div class="marker-arrow"></div></div></div>';
                            }else{
                                var innerMarker = '<div class="marker-container"><div class="marker-card"><div class="front face"><i class="la la-home"></i></div><div class="back face"> <i class="la la-home"></i></div><div class="marker-arrow"></div></div></div>';
                            }
                            
                            
                            // marker
                            var marker = L.marker(
                                [listing.latLng[0], listing.latLng[1]],
                                {icon: L.divIcon({
                                        html: innerMarker,
                                        className: 'open_steet_map_marker google_marker',
                                        iconSize: [50, 46],
                                        popupAnchor: [0, -18],
                                        iconAnchor: [25, 46],
                                    })
                                }
                            )/*.addTo(map)*/;

                            marker.bindPopup(listing.data,jpopup_customOptions);
                            clusters.addLayer(marker);
                            markers.push(marker);
                        }
                    });
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

                    $.each(data.results, function(index, listing) {
                      if( typeof listing.latLng !== 'undefined'){

                            var category_path = listing.data.match(/Widget-preview-category-path:(.*)/) || '';
                            category_path = $.trim(category_path[1]);

                            if(typeof $tree_field_font_icons[category_path] !='undefined') {
                                var innerMarker = '<div class="marker-container"><div class="marker-card"><div class="front face"><i class="'+$tree_field_font_icons[category_path]+'"></i></div><div class="back face"> <i class="'+$tree_field_font_icons[category_path]+'"></i></div><div class="marker-arrow"></div></div></div>';
                            } else if(typeof $tree_field_icons[category_path] !='undefined'){
                                var image = $tree_field_icons[category_path];
                                var innerMarker = '<div class="marker-container marker-container-image"><div class="marker-card"><div class="front face"><img src='+image+'></img></div></div><div class="marker-arrow"></div></div></div>';
                            }else{
                                var innerMarker = '<div class="marker-container"><div class="marker-card"><div class="front face"><i class="la la-home"></i></div><div class="back face"> <i class="la la-home"></i></div><div class="marker-arrow"></div></div></div>';
                            }
                            // marker


                            var myLatlng = new google.maps.LatLng(listing.latLng[0], listing.latLng[1]);
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
                                                    content: listing.data,
                                                    disableAutoPan: false,
                                                    alignBottom: true,
                                                    maxWidth: 0,
                                                    pixelOffset: new google.maps.Size(-157, -15),
                                                    zIndex: null,
                                                    closeBoxMargin: "0",
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
                            var args = {};
                            var marker = new CustomMarker(myLatlng,map,innerMarker,callback,args);
                            markers.push(marker);          
                      }
                    });

                    map.setCenter({lat:data.results_center[0], lng: data.results_center[1]});

                    marker_clusterer = new MarkerClusterer(map, markers, clusterConfig);

                    <?php endif;?>
                    } else {
                        
                        <?php if(config_db_item('map_version') =='open_street'):?>
                            if(!markers.length){
                                if(typeof ajax_data['v_search_option_64'] != 'undefined') {
                                    $.get('https://nominatim.openstreetmap.org/search?format=json&q='+ajax_data['v_search_option_64'], function(data){
                                        if(data.length && typeof data[0]) {
                                            map.setView([data[0].lat, data[0].lon]); 
                                        } else {
                                        }
                                    });
                                }
                            }
                        <?php else:?>
                        <?php endif;?>
                        }
                }
                
                $(selectorResults).html(data.print);
                
                $('.total_rows').html(data.total_rows);
                
                //dropdown select
                $('.options .selectpicker').selectpicker({
                    style: 'selectpicker-primary',
                });
                
                reloadElements();
                
                $("#ajax-indicator-1").hide();
                if($(scroll_enabled).length)
                if(scroll_enabled && $('.fullscreen-inner-md').length){
                    $(document).scrollTop( $(scroll_enabled).offset().top-450 );
                } else if(scroll_enabled && !$('.fullscreen-inner-md').length){
                    $(document).scrollTop( $(scroll_enabled).offset().top-150 );
                }
                
            }, "json").success(function(){
            add_to_favorite();
            remove_from_favorites();
            $('.sw-search-start').find('.fa-ajax-indicator').addClass('hidden').parent().removeClass('loading');
        });
            return false;
        }
        
    $.fn.startLoading = function(data){
    }
    
    $.fn.endLoading = function(data){
        <?php if(config_item('app_type') == 'demo'):?>
            ShowStatus.show('<?php echo _js("'", "\'", lang('Data editing disabled in demo')); ?>');
        <?php else:?>
            //ShowStatus.show('<?php echo lang('data_saved')?>');
        <?php endif;?>
    }

            
        function reloadElements()
        {         
            
            $('#search_order').off().change(function(){
                manualSearch(0);
                return false;
            });
            
            $('.view-type').off().click(function () { 
              $(this).parent().parent().find('.view-type').removeClass("active");
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
                }, "json").success(function(){});
                
                return false;
            });

            //InitChosen();
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
            $('form.form-estate')
                .h5Validate({});
                $("form.form-estate").on("formValidated", function()
                {
                    if($('form.form-estate .ui-state-error').length) {
                        var offsetTop = $('form.form-estate .ui-state-error').first().offset().top-150;
                        $(window).scrollTop(offsetTop)
                    }
                });
            });
        
        /* [END] ValidateFields */
        
    $('document').ready(function(){
        reloadPaginationUniversal();
    }) 
    
    function reloadPaginationUniversal()
    {
        $('.pagination-ajax-results a').click(function () { 
            var page_num = $(this).attr('href');
            var n = page_num.lastIndexOf("/"); 
            page_num = page_num.substr(n+1);

            var results_dom_id = '#ajax_results';

            $.post($(this).attr('href'), {'page_num':page_num}, function(data){
                $(results_dom_id).html(data.print);
                reloadPaginationUniversal();
            }, "json").success(function(){});
            
            return false;
        });
    }     
    
    if(typeof $.fn.fileupload  === 'function'){
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
    }
    
    function showCounters(data_params)
        {  
            
            // load json
            $.post('<?php echo site_url('api/get_all_counters/'.$lang_id.'')?>', data_params, function(data){
                $("[name*=\"search_option_\"]").parent().find('b').html('');
                
                $.each(data.counters, function( index, obj ) {
                    var m_id = obj.option_id;
                    if(!$("[name=\"search_option_"+m_id+"\"]").is(":checked"))
                        $("[name=\"search_option_"+m_id+"\"]").parent().find('b').html('&nbsp('+obj.count+')');
                });
            }, "json");
        }
    
    function showCountersBy_id(data_params,option_id)
        {
            // load json
            var pre_params = data_params;
            $.post('<?php echo site_url('api/get_all_counters_by_id/'.$lang_id)?>/'+option_id, pre_params, function(data){
                
                // remove all
                $(".counter_by_id_enabled .live").find('b').html('&nbsp(0)');
                
                $.each(data.counters, function( index, obj ) {
                  var m_id = obj.value;
                  if(!$(".counter_by_id_enabled .live[data-value='"+m_id+"']").hasClass('active'))
                    $(".counter_by_id_enabled .live[data-value='"+m_id+"']").find('b').html('&nbsp('+obj.count+')');
                  else
                    $(".counter_by_id_enabled .live[data-value='"+m_id+"']").find('b').html('&nbsp('+obj.count+')');
                      
                });

            }, "json");
        }
    
  /* additionals for map */  
  
    var rectangle;
    var infoWindow_rectangle;
    var map_rectangle;

    function RectangleControl(controlDiv2, map) {

        map_rectangle = map;
        controlDiv2.style.padding = '5px';

        var controlUI = document.createElement('div');
        controlUI.id = 'my_location';
        controlUI.style.backgroundColor = 'white';
        controlUI.style.borderStyle = 'solid';
        controlUI.style.borderWidth = '2px';
        controlUI.style.marginRight = '5px';
        controlUI.style.cursor = 'pointer';
        controlUI.style.textAlign = 'center';
        controlUI.title = '{lang_DrawRectangle}';
        controlDiv2.appendChild(controlUI);

        var controlText = document.createElement('div');
        controlText.style.fontFamily = 'Arial,sans-serif';
        controlText.style.fontSize = '12px';
        controlText.style.paddingLeft = '4px';
        controlText.style.paddingRight = '4px';
        controlText.innerHTML = '<strong>{lang_DrawRectangle}</strong>';
        controlUI.appendChild(controlText);

        // Setup the click event listeners: simply set the map to Chicago.
        google.maps.event.addDomListener(controlUI, 'click', function() {

            if(rectangle != null)return;

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

    function showNewRect(event) {
        var ne = rectangle.getBounds().getNorthEast();
        var sw = rectangle.getBounds().getSouthWest();
        var contentString = '<b><?php echo _js(lang_check('Rectangle moved')); ?>:</b><br>' +
                '<?php echo _js(lang_check('New north-east corner')); ?>: ' + ne.lat().toFixed(3).slice(0,-1) + ', ' + ne.lng().toFixed(3).slice(0,-1) + '<br>' +
                '<?php echo _js(lang_check('New south-west corner')); ?>: ' + sw.lat().toFixed(3).slice(0,-1) + ', ' + sw.lng().toFixed(3).slice(0,-1);
        $('#rectangle_ne').val(ne.lat() + ', ' + ne.lng());
        $('#rectangle_sw').val(sw.lat() + ', ' + sw.lng());
        infoWindow_rectangle.setContent(contentString);
        infoWindow_rectangle.setPosition(ne);
        infoWindow_rectangle.open(map_rectangle);
    }


function init_map_searchbox(map){
    
    if(!$('#pac-input').length) return false;
    
    var input = /** @type {!HTMLInputElement} */(
    document.getElementById('pac-input'));

    var types = document.getElementById('type-selector');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
      map: map,
      anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    marker.setIcon(/** @type {google.maps.Icon} */({
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(35, 35)
    }));
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }

    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
  }); 
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
    controlUI.style.marginTop = '5px';
    controlUI.style.marginRight = '5px';
    controlUI.style.textAlign = 'center';
    controlUI.title = 'My Location';
    controlUI.id = 'google_my_location';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.fontFamily = 'Arial,sans-serif';
    controlText.style.fontSize = '12px';
    controlText.style.paddingLeft = '4px';
    controlText.style.paddingRight = '4px';
    controlText.innerHTML = '<strong><?php echo _l('My Location');?></strong>';
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
  
// Sets the map on all markers in the array.
function setAllMap(map) {
    $.each(markers, function (index, marker) {
        marker.infobox.close();
        marker.infobox.isOpen = false;
        marker.marker.setMap(map);
        marker.setMap(map);
    });
}
/* End additionals for map */        
        
 function add_to_favorite () {
    // [START] Add to favorites //  
    $(".add-to-favorites").on('click',function(e){
        e.preventDefault();
        var self = $(this);
        var self_parent = self.parent()
        var id = $(this).attr('data-id')
        var data = { property_id: id };
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
        self_parent.addClass('loading');
        $.post("{api_private_url}/add_to_favorites/{lang_code}", data, 
            function(data){
            load_indicator.css('display', 'none');
            ShowStatus.show(data.message);
            if(data.success)
            {
                self.parent().find(".add-to-favorites").css('display', 'none');
                self.parent().find(".remove-from-favorites").css('display', 'block');
            } else {
                $("#sign-popup").toggleClass("active");
                $("body").addClass("overlay-bgg");
            }
        }).success(function(){
            self_parent.removeClass('loading');
        });

        return false;
    });
    // [END] Add to favorites //  
}     
        
 function remove_from_favorites () {
    // [START] Add to favorites //  
    $(".remove-from-favorites").on('click',function(e){
        e.preventDefault();
        var self = $(this);
        var self_parent = self.parent()
        var id = $(this).attr('data-id')
        var data = { property_id: id };
        var load_indicator = $(this).find('.load-indicator');
        self_parent.addClass('loading');
        load_indicator.css('display', 'inline-block');
        $.post("{api_private_url}/remove_from_favorites/{lang_code}", data, 
               function(data){

            ShowStatus.show(data.message);

            load_indicator.css('display', 'none');
            if(data.success)
            {
                self.parent().find(".add-to-favorites").css('display', 'block');
                self.parent().find(".remove-from-favorites").css('display', 'none');
            }
        }).success(function(){
            self_parent.removeClass('loading');
        });
        return false;
    });
    // [END] Add to favorites //  
}     
</script>