<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Map report')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View Map report')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/mapreport')?>"><?php echo lang_check('Map report')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php _l('Table report')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                      <div class="form-group" style="position: relative;">
                        <?php
                        
                            echo form_dropdown('date_removed', $this->mapreport_m->years, set_value_GET('date_removed', '', true), 'class="form-control" style="float:left;display:inline-block;width:100px;margin-right:5px;"');
                            echo form_dropdown('purpose', $this->mapreport_m->purposes, set_value_GET('purpose', '', true), 'class="form-control" style="float:left;display:inline-block;width:100px;margin-right:5px;"');
                            echo form_dropdown('type', $this->mapreport_m->types, set_value_GET('type', '', true), 'class="form-control" style="float:left;display:inline-block;width:100px;margin-right:5px;"');
                            echo form_dropdown('outcome', $this->mapreport_m->outcomes, set_value_GET('outcome', '', true), 'class="form-control" style="float:left;display:inline-block;width:100px;margin-right:5px;"');
                            echo form_input('area_from', set_value_GET('area_from', '', true), 'class="form-control" style="float:left;display:inline-block;width:100px;margin-right:5px;" placeholder="'.lang_check('Area from').'"');
                            echo form_input('area_to', set_value_GET('area_to', '', true), 'class="form-control" style="float:left;display:inline-block;width:100px;margin-right:5px;" placeholder="'.lang_check('Area to').'"');
                        ?>
                      </div>
                      <button type="submit" class="btn btn-default"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Load'); ?></button>
                      <a href="<?php echo site_url('admin/mapreport'); ?>" class="btn btn-default"><i class="icon icon-repeat"></i>&nbsp;&nbsp;<?php echo lang_check('Reset'); ?></a>  
                    </form>
                  
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>     

                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php _l('Purpose');?></th>
                            <th data-hide="phone"><?php _l('Type');?></th>
                            <th data-hide="phone,tablet"><?php _l('Area');?></th>
                            <th><?php _l('Price');?></th>
                            <th><?php _l('MQ2');?></th>
                            <th><?php _l('Reason');?></th>
                            <th data-hide="phone,tablet"><?php _l('Date submited');?></th>
                            <th><?php _l('Date removed');?></th>
                            <th data-hide="phone,tablet"><?php _l('Address');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $item):?>
                                    <tr>
                                    	<td><?php echo $item->id_mapreport; ?></td>
                                        <td><?php echo $item->purpose; ?></td>
                                        <td><?php echo $item->type; ?></td>
                                        <td><?php echo $item->area; ?></td>
                                        <td><?php echo $item->price; ?></td>
                                        <td><?php echo number_format($item->price / $item->area, 1); ?></td>
                                        <td><?php echo $item->outcome; ?></td>
                                        <td><?php echo $item->date_submited; ?></td>
                                        <td><?php echo $item->date_removed; ?></td>
                                        <td><?php echo $item->address; ?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="13"><?php _l('We could not find any item'); ?></td>
                                    </tr>
                        <?php endif;?>                   
                      </tbody>
                    </table>
                    
                    <div style="text-align: center;"><?php echo $pagination; ?></div>
                    
                  </div>
                </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
                <div class="widget wblue">
                    <div class="widget-head">
                        <div class="pull-left"><?php _l('Map report')?></div>
                        <div class="widget-icons pull-right">
                            <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="widget-content">
                        <div class="gmap_mapsReport" id="mapsReport">
                        
                        </div>
<script language="javascript">
        $(function () {
            $("#mapsReport").gmap3({
             map:{
                options:{
                 center: [<?php echo calculateCenter($estates)?>],
                 zoom: 8,
                 scrollwheel: false
                }
             },
             marker:{
                values:[
                <?php if(sw_count($estates)): foreach($estates as $estate):
                
                    $icon_url = base_url('admin-assets/img/markers/marker_blue.png');
                    $days_between = ceil(abs(strtotime($estate->date_removed) - strtotime($estate->date_submited)) / 86400);
                    $json = json_decode($estate->property_json);
                    
                    if(isset($json->{'option6_1'}))
                    {
                        if($json->{'option6_1'} != '' && $json->{'option6_1'} != 'empty')
                        {
                            if(file_exists(FCPATH.'admin-assets/img/markers/'.$json->{'option6_1'}.'.png'))
                            $icon_url = base_url('admin-assets/img/markers/'.$json->{'option6_1'}.'.png');
                        }
                    }
                
                    echo '{latLng:['.$estate->lat.', '.$estate->lng.'], options:{ icon: "'.$icon_url.'"}, data:"'.lang_check('Address').': '.strip_tags($estate->address);
                    echo '<br />'.lang_check('Purpose').': '.$estate->purpose;
                    echo '<br />'.lang_check('Type').': '.$estate->type;
                    echo '<br />'.lang_check('Reason').': '.$estate->outcome;
                    echo '<br />'.lang_check('Price').': '.$estate->price.' EUR';
                    echo '<br />'.lang_check('Area').': '.$estate->area.' m2';
                    echo '<br />'.lang_check('Days on portal').': '.$days_between;
                    echo '<br />'.lang_check('Year of remove').': '.date('Y', strtotime($estate->date_removed));
                    echo '"},';
                endforeach;
                endif;?> 
                ],
                
            options:{
              draggable: false
            },
            events:{
              mouseover: function(marker, event, context){
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
                var infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  //infowindow.close();
                }
              }
            }
             }
            });
        });
</script>
                    </div>
                </div>
            </div>
          </div>
          
          <div class="row">

            <div class="col-md-12">

                <div class="widget wgreen">

                <div class="widget-head">
                  <div class="pull-left"><?php _l('Totals')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">

                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                            <th><?php _l('Description');?></th>
                            <th><?php _l('Total');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td><?php _l('The number of properties Total Sold or Rented');?></td>
                            <td><?php echo $total_sold;?></td>
                        </tr>
                        <?php foreach($total_sold_type as $key=>$val): ?>
                        <tr>
                            <td><?php _l('The number of properties Total Sold or Rented'); echo ': '; _l($key);?></td>
                            <td><?php echo $val;?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><?php _l('Average days of sale or rent');?></td>
                            <td><?php echo $total_sold>0?intval($total_days_to_sold / $total_sold):'-';?></td>
                        </tr>
                        <?php foreach($total_days_to_sold_type as $key=>$val): ?>
                        <tr>
                            <td><?php _l('Average days of sale or rent'); echo ': '; _l($key);?></td>
                            <td><?php echo intval($val / $total_sold_type[$key]);?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php foreach($avarage_size_type as $key=>$val): ?>
                        <tr>
                            <td><?php _l('Average size'); echo ': '; _l($key);?></td>
                            <td><?php echo intval($val / $total_sold_type[$key]);?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        
                        
                      </tbody>
                    </table>
                    
                    <div style="text-align: center;"><?php echo $pagination; ?></div>
                    
                  </div>
                </div>
            </div>
          </div>
          
          
          
        </div>
</div>