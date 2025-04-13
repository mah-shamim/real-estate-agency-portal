
    <!-- Page heading -->
    <div class="page-head">
    <!-- Page heading -->
        <h2 class="pull-left"><?php echo lang('Searching')?>
		  <!-- page meta -->
		  <span class="page-meta"><?php echo lang('Results of property searching')?></span>
		</h2>


		<!-- Breadcrumb -->
		<div class="bread-crumb pull-right">
		  <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
          <span class="divider">/</span> 
          <a class="bread-current" href="#"><?php echo lang('Searching')?></a>
		</div>

		<div class="clearfix"></div>

    </div>
    <!-- Page heading ends -->



    <!-- Matter -->

    <div class="matter">
    <div class="container">  
            <div class="row">
            <div class="col-md-12">

                <div class="widget wlightblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Results of property searching')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">

                    <table class="table table-bordered ">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang('Address');?></th>
                            <!-- Dynamic generated -->
                            <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                            <th><?php echo $row->option?></th>
                            <?php endforeach;?>
                            <!-- End dynamic generated -->
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<?php if(check_acl('estate/delete')):?><th class="control"><?php echo lang('Delete');?></th><?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($estates)): foreach($estates as $estate):?>
                                    <tr>
                                    	<td><?php echo $estate->id?></td>
                                        <td><?php echo anchor('admin/estate/edit/'.$estate->id, $estate->address)?>
                                        <?php if($estate->is_activated == 0):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-danger"><?php echo lang_check('Not Activated')?></span>
                                        <?php endif;?>
                                        <?php if(isset($settings['listing_expiry_days']) && $settings['listing_expiry_days'] > 0 && strtotime($estate->date_modified) <= time()-$settings['listing_expiry_days']*86400): ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning"><?php echo lang_check('Expired'); ?></span>
                                        <?php endif; ?>
                                        <?php if(!empty($estate->activation_paid_date)):?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-success"><?php echo lang_check('Paid'); ?></span>
                                        <?php endif; ?>
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
                                    	<td colspan="8"><?php echo lang('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>

                  </div>
                </div>
            </div>
          </div>

    </div>
    </div>

	<!-- Matter ends -->

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>
   
       <!-- Script for this page -->
    <script type="text/javascript">
    
        $(function () {
            $("#mapsProperties").gmap3({
             map:{
                options:{
                 center: [45.81, 15.98],
                 zoom: 12,
                 scrollwheel: false
                }
             },
             marker:{
                values:[
                <?php if(sw_count($estates)): foreach($estates as $estate):?>
                    {latLng:[<?php echo $estate->gps?>], data:"<?php echo $estate->address?>"},
                <?php endforeach;?>
                <?php endif;?> 
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
                  infowindow.close();
                }
              }
            }
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
             }
            });
        });
    
    </script>