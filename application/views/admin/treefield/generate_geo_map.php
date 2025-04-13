<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('TreeField import')?>
          <!-- page meta -->
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate/options')?>"><?php echo lang('Fields')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate/edit_option/'.$option->id)?>"><?php echo lang('Field').' #'.$option->id?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Generate map')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php if(!empty($errors_svg)) foreach ($errors_svg as $key => $value): ?>
                        <?php _che($value);?>
                    <?php endforeach;?>
                      
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error'); ?></p>
                    <?php endif;?>
                    <?php if(!empty($error)):?>
                    <p class="label label-important validation"> <?php echo $error; ?> </p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open_multipart(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo _l('Map')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('geo_map', $geo_map_prepared, $this->input->post('geo_map'), 'class="form-control" id="inputgeo_map"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Current map will be replaced with new on')?></label>
                                  <div class="col-lg-10">
                                  <?php echo form_checkbox('accept_generate', 1, false, '')?>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Random locations for existing listings')?></label>
                                  <div class="col-lg-10">
                                  <?php echo form_checkbox('random_locations', 1, false, '')?>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Add country in root')?></label>
                                  <div class="col-lg-10">
                                  <?php echo form_checkbox('in_root', 1, false, '')?>
                                  </div>
                                </div>
                    
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                      <?php echo _l('Current map with all related categories will be removed/replaced with new map');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang_check('Generate'), 'class="btn btn-primary" onclick="return confirm(\' All values will be removed, are you sure?\')"')?>
                                    <a href="<?php echo site_url('admin/treefield/edit/'.$option->id)?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <p><strong><?php _l('Preview'); ?>:</strong></p>
                    
                    <!--
                    <p> - <?php _l('For active place only add attribute data-name, with value on default lang'); ?>:</p>
                    <img src="<?php echo base_url('admin-assets/img/example_svg.jpg');?>" alt="" style="max-width: 100%" />
                    -->
                    
                    <div class="row preload-box">
                        <div class="col-sm-7" id="map_preload"></div>
                        <div class="col-sm-5" id="region_list_preload"></div>
                        <img src="<?php echo base_url('admin-assets/img/loading.gif')?>"  style="display: none;" alt="" />
                    </div>
                  </div>
              </div>  

            </div>
</div>

        </div>
		  </div>

<script>

/* CL Editor */
$(document).ready(function(){
    $(".cleditor2").cleditor({
        width: "auto",
        height: 250,
        docCSSFile: "<?php echo $template_css?>",
        baseHref: '<?php echo base_url('templates/'.$settings['template'])?>/'
    });
});

</script>
<style type="text/css">

    .preload-box {
        position: relative;
        min-height: 150px;
    }
    
    .preload-box img {
        position: absolute;
        left: 50%;
        top: 50%;
        -moz-transform: translate(-50%,-50%);
        -webkit-transform: translate(-50%,-50%);
        transform: translate(-50%,-50%);
        width: 45px;
    }
    
</style>
<script type="text/javascript">

$(document).ready(function(){
    
    $('#inputgeo_map').change(function(){
        var _svg_file= $(this).val();
        $('.preload-box img').show();
        $.post("<?php echo site_url('privateapi'); ?>/parse_svg_map/"+$(this).val(),[], function(data){
            
            if(data.success) {
                
              $('#region_list_preload').html('');
              $('#map_preload').html('')
              
              $('#region_list_preload').append('<p><strong>'+data.title_map+'</strong></p>') ; 
              $('#region_list_preload').append('<ul> </ul>');
                $.each(data.region_names, function(key, value){
                     $('#region_list_preload ul').append('<li>'+value+'</li>');  
                })
                
              $('#map_preload').html('<object data="<?php echo base_url('templates/'.$this->data['settings']['template'].'/assets/svg_maps') ;?>/'+_svg_file+'" type="image/svg+xml" id="svgmap" width="500" height="420"></object>');
              $('.preload-box img').hide();
            }
            
        });
    })
    
    $('#inputgeo_map').trigger('change');
})



</script>