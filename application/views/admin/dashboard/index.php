<?php if(config_db_item('map_version') !='open_street' && empty($settings['maps_api_key'])):?>
<div class="col-md-12">
    <br/>
    <div class="alert alert-warning alert-dismissible fade in">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       <?php echo lang_check('To use script please enter google maps api key in ');?> <a href="<?php echo site_url('admin/settings/system');?>"> <?php echo lang_check('settings->system settings, add key');?>.</a> <a href="http://iwinter.com.hr/support/?p=17200"><strong><?php echo lang_check('All details here');?></strong></a>
    </div>
</div>
<?php endif;?>
<?php 

if($settings_template=='local') {
    $tree_field_id = 79;
    $CI = & get_instance();
    $values = array();
    $CI->load->model('treefield_m');
    $CI->load->model('file_m');
    $check_option = $CI->treefield_m->get_lang(NULL, FALSE, $content_language_id);
    foreach ($check_option as $key => $value) {
        if($value->field_id==$tree_field_id){
            $icon = 'assets/img/markers/piazza.png';
            // Thumbnail and big image
            if(!empty($value->image_filename))
            {
                $files_r = $CI->file_m->get_by(array('repository_id' => $value->repository_id),FALSE, 5,'id ASC');
                // check second image
                if($files_r and isset($files_r[1]) and file_exists(FCPATH.'files/thumbnail/'.$files_r[1]->filename)) {
                    $icon = base_url('files/'.$files_r[1]->filename);
                }
            }
            $values[$value->value_path.' -']= $icon;
        }
    }
}

?>


    <!-- Page heading -->
    <div class="page-head">
    <!-- Page heading -->
        <h2 class="pull-left"><?php echo lang('Dashboard')?>
		  <!-- page meta -->
		  <span class="page-meta"><?php echo lang('Short, basic informations')?></span>
		</h2>

		<!-- Breadcrumb -->
		<div class="bread-crumb pull-right">
		  <a href="#"><i class="icon-home"></i> <?php _l('Dashboard')?></a> 
		</div>

		<div class="clearfix"></div>

    </div>
    <!-- Page heading ends -->

    <!-- Matter -->

    <div class="matter">
    <div class="container">
    

    
        <div class="row">
            <div class="col-md-12"> 
                <?php if(check_acl('page') && config_db_item('frontend_disabled') === FALSE):?><?php echo anchor('admin/page/edit', '<i class="icon-sitemap"></i>&nbsp;&nbsp;'.lang('Add a page'), 'class="btn btn-success"')?><?php endif;?>
                <?php echo anchor('admin/estate/edit', '<i class="icon-map-marker"></i>&nbsp;&nbsp;'.lang('Add a estate'), 'class="btn btn-info"')?>
            </div>
        </div>
                          
        <?php if($this->session->flashdata('error')):?>
        <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
        <?php endif;?>
          <div class="row">
            <div class="col-md-12">
              <div class="widget worange">
                <div class="widget-head">
                  <div class="pull-left"><?php _l('View last added properties')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="gmap" id="mapsProperties">

                  </div>
                </div>
              </div> 
            </div>
            </div>
            <div class="row">
            <?php if(check_acl('page') && config_db_item('frontend_disabled') === FALSE):?>
            <div class="col-md-6">
                <div class="widget wgreen">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Pages')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content widget-pages">
                    <!-- Nested Sortable -->
                    <div id="orderResult">
                    <?php echo get_ol_pages($pages_nested)?>
                    </div>
                  </div>
                </div>
            </div>
            <?php endif;?>

            <div class="col-md-6">

                <div class="widget wlightblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Last added estates')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">

                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang('Address');?></th>
                            <!-- Dynamic generated -->
                            <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                            <th data-hide="phone,tablet"><?php echo $row->option?></th>
                            <?php endforeach;?>
                            <!-- End dynamic generated -->
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('estate/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($estates)): foreach($estates as $estate):?>
                                    <tr>
                                        <?php if($estate->is_activated == 0):?>
                                        <td><span class="label label-danger"><?php echo $estate->id?></span></td>
                                        <?php else:?>
                                        <td><?php echo $estate->id?></td>
                                        <?php endif;?>
                                        <td><?php echo anchor('admin/estate/edit/'.$estate->id, _ch($estate->address) )?>
                                        </td>
                                        <!-- Dynamic generated -->
                                        <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                        <td>
                                        <?php
                                            echo $this->estate_m->get_field_from_listing($estate, $row->option_id);
                                        ?>
                                        </td>
                                        <?php endforeach;?>
                                        <!-- End dynamic generated -->
                                    	<td><?php echo btn_edit('admin/estate/edit/'.$estate->id)?></td>
                                    	<?php if(check_acl('estate/delete')):?><td><?php echo btn_delete('admin/estate/delete/'.$estate->id)?></td><?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="5"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>

                  </div>
                </div>
            </div>
            
<?php if(check_acl('page') && FALSE):?>
            <div class="col-md-6">

                <div class="widget wred">

                <div class="widget-head">
                  <div class="pull-left"><?php _l('Last script news')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">

                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th data-hide="phone"><?php _l('Date');?></th>
                            <th><?php _l('Title');?></th>
                        </tr>
                      </thead>
                      <tbody id="script_news_table">
                        <tr>
                        	<td colspan="5"><?php _l('Loading in progress');?></td>
                        </tr>      
                      </tbody>
                    </table>
<script type="text/javascript">
$(function () {
    
    $.getJSON("https://ljiljan.com.hr/last_news.php?f=news.json", function( data ) {
      var content = '';
      
      $.each( data, function( key, val ) {
        content+='<tr><td>'+val.date+'</td><td><a href="'+val.link+'" target="_blank">'+val.title+'</a></td></tr>';
      });
        
      $('#script_news_table').html(content);
    });

});
</script>
                  </div>
                </div>
            </div>
<?php endif; ?>
            
          </div>

    </div>
    </div>

	<!-- Matter ends -->

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>
       <!-- Script for this page -->
       
<style>
    
    .leaflet-popup-content { 
        
    }
    
    .leaflet-popup-content .opt {
        margin-bottom: 5px;
        max-width: 150px;
    }
    
    .leaflet-popup-content .opt.title {
        font-weight: 600;
    }
    
    .leaflet-popup-content .opt span {
        display: inline-block;
    }
    
    .leaflet-popup-content .opt a {
        font-size: 13px;
    }
    
    .labels-marker {
            overflow: visible !important;
    }
     <?php if($settings_template=='realsite' || $settings_template=='disabled-cityguide' || $settings_template=='worlddealer'):?>
        /* realsite marker */  
       .marker {
           background-color: #2196F3 ;
           border-radius: 50% !important;
           border: 2px solid #fff;
           height: 40px;
           line-height: 32px;
           text-align: center;
           width: 40px;
       }
       .marker img {
           vertical-align: middle;
           width: 16px;
       }          
      .marker.apartment-mark-color {
        background-color: #FF9800; }
      .marker.house-mark-color {
        background-color: #00BCD4; }
      .marker.land-mark-color {
        background-color: #2196F3; }
      .marker.deep-purple {
        background-color: #673AB7; }
      .marker.comersial-property-mark-color {
        background-color: #3F51B5; }
      .marker.teal {
        background-color: #009688; }
      .marker.green {
        background-color: #4CAF50; }
      .marker.light-green {
        background-color: #8BC34A; }
      .marker.lime {
        background-color: #CDDC39; }
      .marker.yellow {
        background-color: #FBC02D; }
      .marker.amber {
        background-color: #FFC107; }
      .marker.orange {
        background-color: #FF9800; }
      .marker.deep-orange {
        background-color: #FF5722; }
      .marker.brown {
        background-color: #795548; }
     /* end realsite marker */  
        .leaflet-container .leaflet-marker-pane img {
            max-width: 20px !important;
            max-height: 22px !important;
        }
    <?php  elseif($settings_template=='realocation') :?>
        /* realocation */
       .marker {
          background-color: #fff;
          border: 3px solid #cd2213;
          -webkit-border-radius: 50% !important;
          -moz-border-radius: 50% !important;
          border-radius: 50% !important;
          height: 40px;
          line-height: 40px;
          position: relative;
          text-align: center;
          width: 40px;
          line-height: 34px;
        }
        
        .marker img {
             width: 20px;
             filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=70);
             opacity: 0.7;
         }
         
        .leaflet-container .leaflet-marker-pane img {
            max-width: 20px !important;
            max-height: 22px !important;
        }
    /* end realocation */
    <?php  elseif($settings_template=='bootstrap4') :?>
        .marker {
            position: relative; 
            cursor: pointer;
            width: 35px; 
            height: 45px; 
            border-color: #0F71D6;
        } 

        .marker:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 34px;
            height: 35px;
            background-color: #0F71D6;
            -webkit-border-radius: 63px 63px 63px 63px/108px 108px 72px 72px;
            border-radius: 50% 50% 50% 50%/60% 60% 40% 40%;
            transform: rotate(180deg);
        }

        .marker:after {
            content: "";
            position: absolute;
            top: 22px;
            left: 1px;
            width: 0;
            height: 0;
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
            border-top: 30px solid;
            border-top-color: inherit;
            border-radius: 15px 15px 25px 0;
        } 

        .leaflet-container .leaflet-marker-pane img,
        .marker img {
            position: absolute;
            z-index: 2;
            max-width: 20px !important;
            max-height: 22px !important;
            left: 50%;
            transform: translateX(-50%);
            top: 7px;
        }
    <?php  elseif($settings_template=='realia') :?>
    /* realia */
    .marker {
       background: url(<?php echo base_url('templates/'.$settings_template.'/assets/img/markers/marker-blue.png');?>);
        background-position: center top;
        background-repeat: no-repeat;
        background-size: 42px 57px;
        height: 57px;
        opacity: 0.7;
        transition: margin-top 0.2s linear 0s, padding-bottom 0.2s linear 0s, opacity 0.2s linear 0s;
        width: 42px;
       line-height: 34px;
       text-align: center;
   }
   
   .marker img {
        height: 28px;
        margin-left: 0px;
        width: 27px;
    }
    /* end realia */
    .leaflet-container .leaflet-marker-pane img {
            max-width: 28px !important;
            max-height: 27px !important;
        }
    <?php  elseif($settings_template=='rento') :?>
    /* rento */
    .marker {
      position: relative;
      cursor: pointer;
      width: 40px;
      height: 40px;
    }

    .marker:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 40px;
      height: 40px;
      display: block;
      border-radius: 50% 50% 0 50%;
      background: #DA3743;
      -webkit-transform: rotate(45deg);
         -moz-transform: rotate(45deg);
           -o-transform: rotate(45deg);
              transform: rotate(45deg);
      cursor: pointer;
    }

    .marker img {
      position: absolute;
      z-index: 2;
      max-width: 20px;
      max-height: 22px;
      left: 50%;
      transform: translate(-50%, -50%);
      top: 50%;
    }
    
    .marker,
    .marker:before {
        background: #f44336;
    }
        .leaflet-container .leaflet-marker-pane img {
            max-width: 20px !important;
            max-height: 22px !important;
        }
    /* end rento */
    
    <?php  elseif($settings_template=='local' || $settings_template=='jobworld') :?>
    /* rento */

        .marker {
          position: relative;
          cursor: pointer;
          width: 40px;
          height: 40px;
        }

        .marker:before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 40px;
          height: 40px;
          box-shadow: -3px 0 5px 0 rgba(0, 0, 0, 0.3);
          display: block;
          transition: all 0.2s ease-in-out;
          border-radius: 50% 50% 0 50%;
          background: #DA3743;
          -webkit-transform: rotate(45deg);
             -moz-transform: rotate(45deg);
               -o-transform: rotate(45deg);
                  transform: rotate(45deg);
          cursor: pointer;
        }

        .marker img {
          position: absolute;
          z-index: 2;
          max-width: 20px;
          max-height: 22px;
          left: 50%;
          transform: translate(-50%, -50%);
          top: 50%;
        }
        .leaflet-container .leaflet-marker-pane img {
            max-width: 20px !important;
            max-height: 22px !important;
        }
    /* end rento */
    <?php  elseif($settings_template=='selio') :?>
     .marker {
            position: relative; 
            cursor: pointer;
            width: 35px; 
            height: 45px; 
            border-color: #6a7be7;
        } 

        .marker:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 34px;
            height: 35px;
            background-color: #6a7be7;
            -webkit-border-radius: 63px 63px 63px 63px/108px 108px 72px 72px;
            border-radius: 50% 50% 50% 50%/60% 60% 40% 40%;
            transform: rotate(180deg);
        }

        .marker:after {
            content: "";
            position: absolute;
            top: 22px;
            left: 1px;
            width: 0;
            height: 0;
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
            border-top: 30px solid;
            border-top-color: inherit;
            border-radius: 15px 15px 25px 0;
        } 

        .leaflet-container .leaflet-marker-pane img,
        .marker img {
            position: absolute;
            z-index: 2;
            max-width: 20px !important;
            max-height: 22px !important;
            left: 50%;
            transform: translateX(-50%);
            top: 7px;
        }
    
    <?php endif;?>
</style> 
       
       
    <script type="text/javascript">
        $(function () {
            var pictureLabel = document.createElement("img");
                pictureLabel.src = '<?php echo base_url('admin-assets/img/markers/marker_blue.png');?>';
            var markers = new Array();
            <?php if(config_db_item('map_version') =='open_street'):?>  
                
                map = L.map('mapsProperties', {
                   <?php if(config_item('custom_map_center') === FALSE): ?>
                   center: [<?php echo calculateCenter($estates)?>],
                   <?php else: ?>
                   center: [<?php echo config_item('custom_map_center'); ?>],
                   <?php endif; ?>
                   zoom: <?php _che($settings['zoom_dashboard'], 9);?>,
                   scrollWheelZoom: false,
                });     
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(map);

                <?php if(sw_count($estates_all)): foreach($estates_all as $estate):?>
                   <?php
                       
                        // skip if gps is not defined
                        if(empty($estate->gps))continue;

                        $icon_url = base_url('admin-assets/img/markers/marker_blue.png');

                        if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/markers/marker_blue.png')) {
                            $icon_url = base_url('templates/'.$settings_template.'/assets/img/markers/marker_blue.png');
                        }elseif(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/icons/marker_blue.png'))
                            $icon_url = base_url('templates/'.$settings_template.'/assets/img/icons/marker_blue.png');

                        $value = $this->estate_m->get_field_from_listing($estate, 6);

                        if($settings_template=='local' || $settings_template=='jobworld' || $settings_template=='selio') {
                            $value = $this->estate_m->get_field_from_listing($estate, 79);
                            $icon_url = base_url('templates/'.$settings_template.'/assets/img/markers/apartment.png');
                            if(!empty($value) && isset($values[$value])){
                                $icon_url = $values[$value];
                            }
                        } else {
                            if(isset($value))
                            {
                                if($value != '' && $value != 'empty')
                                {
                                   if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/markers/'.$value.'.png'))
                                    $icon_url = base_url('templates/'.$settings_template.'/assets/img/markers/'.$value.'.png');
                                   elseif(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/icons/'.$value.'.png'))
                                    $icon_url = base_url('templates/'.$settings_template.'/assets/img/icons/'.$value.'.png');
                                }
                            }
                        }

                        $marker_transparent = base_url('templates/'.$settings_template.'/assets/img/marker-transparent.png');
                       
                        $data = '<div class="opt title">'.strip_tags($estate->address).'</div>';
                        foreach($this->option_m->get_visible($content_language_id) as $row):
                            $value = $this->estate_m->get_field_from_listing($estate, $row->option_id);
                            $value = htmlentities(strip_tags($value), ENT_QUOTES, "UTF-8");
                            if(empty($value)) continue;
                            if($row->type == 'DROPDOWN')
                            {
                                $data .='<div class="opt"><span class="label label-warning">'.$value.'</span></div>';
                            }
                            else
                            {
                                $data .='<div class="opt">'.$value.'</div>';
                            }
                        endforeach;
                        $data .= '<div class="footer"><a style="font-weight:bold;" href="'.site_url('admin/estate/edit/'.$estate->id).'">'.lang('Edit').'</a></div>';
                   ?>
                           
                   var marker = L.marker(
                       [<?php _che($estate->gps); ?>],
                       {icon: L.divIcon({
                               html: '<div class=\"marker  <?php echo $value;?>-mark-color\"><img src=\"<?php echo $icon_url;?>\"></div>',
                               className: 'open_steet_map_marker',
                                <?php if($settings_template=='local' || $settings_template=='jobworld'  || $settings_template=='selio'):?>
                                   iconSize: [31, 46],
                                   popupAnchor: [2, -35],
                                   iconAnchor: [15, 45],
                                <?php else:?>
                                   iconSize: [31, 46],
                                   popupAnchor: [1, -35],
                                   iconAnchor: [15, 45],
                                <?php endif;?>
                           })
                       }
                   )/*.addTo(map)*/;
                   marker.bindPopup('<?php _jse($data);?>');
                   clusters.addLayer(marker);
                   markers.push(marker);
                <?php endforeach; ?>
                <?php endif;?>
                map.addLayer(clusters);
                /* set center */
                if(markers.length){
                    var limits_center = [];
                    for (var i in markers) {
                        var latLngs = [ markers[i].getLatLng() ];
                        limits_center.push(latLngs)
                    };
                    var bounds = L.latLngBounds(limits_center);
                    map.fitBounds(bounds);
                }
            <?php else:?>
            $("#mapsProperties").gmap3({
                defaults:{ 
                    classes:{
                      Marker:MarkerWithLabel
                    }
                },
            map:{
                options:{
                <?php if(config_item('custom_map_center') === FALSE): ?>
                 center: [<?php echo calculateCenter($estates)?>],
                <?php else: ?>
                 center: [<?php echo config_item('custom_map_center'); ?>],
                <?php endif; ?>
                 zoom: <?php _che($settings['zoom_dashboard'], 9);?>,
                 scrollwheel: false
                }
             },
             marker:{
                values:[
                <?php if(sw_count($estates_all)): foreach($estates_all as $estate):
                    
                    // skip if gps is not defined
                    if(empty($estate->gps))continue;
                    
                    $icon_url = base_url('admin-assets/img/markers/marker_blue.png');
                    
                    if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/markers/marker_blue.png')) {
                        $icon_url = base_url('templates/'.$settings_template.'/assets/img/markers/marker_blue.png');
                    }elseif(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/icons/marker_blue.png'))
                        $icon_url = base_url('templates/'.$settings_template.'/assets/img/icons/marker_blue.png');
                    
                    $value = $this->estate_m->get_field_from_listing($estate, 6);
                    
                    if($settings_template=='local' || $settings_template=='jobworld' || $settings_template=='selio') {
                        $value = $this->estate_m->get_field_from_listing($estate, 79);
                        $icon_url = base_url('templates/'.$settings_template.'/assets/img/markers/apartment.png');
                        if(!empty($value) && isset($values[$value])){
                            $icon_url = $values[$value];
                        }
                    } else {
                        if(isset($value))
                        {
                            if($value != '' && $value != 'empty')
                            {
                               /* if(file_exists(FCPATH.'admin-assets/img/markers/'.$value.'.png'))
                                $icon_url = base_url('admin-assets/img/markers/'.$value.'.png');*/
                               if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/markers/'.$value.'.png'))
                                $icon_url = base_url('templates/'.$settings_template.'/assets/img/markers/'.$value.'.png');
                               elseif(file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/icons/'.$value.'.png'))
                                $icon_url = base_url('templates/'.$settings_template.'/assets/img/icons/'.$value.'.png');
                            }
                        }
                    }
                    
                    $marker_transparent = base_url('templates/'.$settings_template.'/assets/img/marker-transparent.png');
                
                    echo '{latLng:['.$estate->gps.'], ';
                    
                    // HTML MARKER;
                    if($settings_template=='realsite' ||  $settings_template=='worlddealer' || $settings_template=='realocation' || $settings_template=='disabled-cityguide'){  
                    echo 'options:{ icon: "'.$marker_transparent.'", 
                                labelAnchor: new google.maps.Point(20,50),
                                labelClass: "labels-marker ",
                                labelContent: "<div class=\"marker  '.$value.'-mark-color\"><img src=\"'.$icon_url.'\"></div>"
                                    },';
                    } elseif($settings_template=='realia'){  
                    echo 'options:{ icon: "'.$marker_transparent.'", 
                                labelAnchor: new google.maps.Point(20,60),
                                labelClass: "labels-marker ",
                                labelContent: "<div class=\"marker  '.$value.'-mark-color\"><img src=\"'.$icon_url.'\"></div>"
                                    },';
                    } elseif($settings_template=='bootstrap4'){  
                    echo 'options:{ icon: "'.$marker_transparent.'", 
                                labelAnchor: new google.maps.Point(18,50),
                                labelClass: "labels-marker ",
                                labelContent: "<div class=\"marker  '.$value.'-mark-color\"><img src=\"'.$icon_url.'\"></div>"
                                    },';
                    } elseif($settings_template=='rento'){  
                    echo 'options:{ icon: "'.$marker_transparent.'", 
                                labelAnchor: new google.maps.Point(20,25),
                                labelClass: "labels-marker ",
                                labelContent: "<div class=\"marker  '.$value.'-mark-color\"><img src=\"'.$icon_url.'\"></div>"
                                    },';
                   } elseif($settings_template=='local' || $settings_template=='jobworld' || $settings_template=='selio'){  
                    echo 'options:{ icon: "'.$marker_transparent.'", 
                                labelAnchor: new google.maps.Point(20,50),
                                labelClass: "labels-marker ",
                                labelContent: "<div class=\"marker  '.$value.'-mark-color\"><img src=\"'.$icon_url.'\"></div>"
                                    },';
                   }else {
                        echo 'options:{ icon: "'.$icon_url.'"},'; 
                    }
                    echo 'data:"'.strip_tags($estate->address);
                    foreach($this->option_m->get_visible($content_language_id) as $row):
                        $value = $this->estate_m->get_field_from_listing($estate, $row->option_id);
                        $value = htmlentities(strip_tags($value), ENT_QUOTES, "UTF-8");
                        if(empty($value)) continue;
                        if($row->type == 'DROPDOWN')
                        {
                            echo '<br /><span class=\"label label-warning\">'.$value.'</span>';
                        }
                        else
                        {
                            echo '<br />'.$value;
                        }
                    endforeach;
                    echo '<br /><a style=\"font-weight:bold;\" href=\"'.site_url('admin/estate/edit/'.$estate->id).'\">'.lang('Edit').'</a>"},';
                endforeach;
                endif;?> 
                ],
                
            options:{
              draggable: false
            },
            events:{
               click: function(marker, event, context){
                var map = $(this).gmap3("get"),
                  infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  infowindow.open(map, marker);
                  infowindow.setContent(context.data);
                } else {
                  $(this).gmap3({
                    infowindow:{
                      anchor:marker,
                      options:{content: context.data}
                    }
                  });
                }
              },
              mouseout: function(){
               /* var infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  //infowindow.close();
                }*/
              }
            }
             }
            });
            <?php endif;?>
            
        });
    </script>