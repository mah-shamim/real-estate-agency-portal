<!-- Page heading -->
<div class="page-head">
<!-- Page heading -->
<h2 class="pull-left"><?php echo lang_check('Foursquare import')?>
              <!-- page meta -->
              <span class="page-meta"><?php echo lang_check('Foursquare import')?></span>
            </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>

    <div class="clearfix"></div>


</div>
<!-- Page heading ends -->

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Import data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
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
                    <?php if(!empty($message)):?>
                    <p class="label label-important validation"> <?php echo $message; ?> </p>
                    <?php endif;?>
                    <?php if(!empty($message_successful)):?>
                    <p class="label label-success validation"> <?php echo $message_successful; ?> </p>
                    <?php endif;?>
                    
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open_multipart(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Address')?></label>
                          <div class="col-lg-10">
                            <?php echo form_input('address', $this->input->post('address') ? $this->input->post('address') : '', 'class="form-control" id="inputAddress" placeholder="'.lang_check("Address").'"')?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Gps')?>*</label>
                          <div class="col-lg-10">
                            <?php echo form_input('gps_google', $this->input->post('gps_google') ? $this->input->post('gps_google') : $gps, 'class="form-control" id="inputGps" placeholder="38.575147, -0.064002"')?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Radius')?> (m)*</label>
                          <div class="col-lg-10">
                            <?php echo form_input('radius', $this->input->post('radius') ? $this->input->post('radius') : '250', 'class="form-control" id="inputRadius" placeholder="500"')?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Categories')?></label>
                          <div class="col-lg-10">
                              <select class="form-control selectpicker" name="type[]" data-size="8" data-live-search="true" multiple>
                              <?php foreach ($types_list as $key => $value):?>
                                  <?php
                                  $selected ='';
                                  if(strripos($type,$key ) !== FALSE ) {
                                      $selected ='selected="selected"';
                                  }
                                  ?>
                                  <option title="<?php echo str_replace(' - ', '', $value);?>" value="<?php echo $key;?>" <?php echo $selected;?> ><?php echo $value;?></option>
                              <?php endforeach;?>
                              </select>
                          </div>
                        </div>
                        <!--<div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Name')?></label>
                          <div class="col-lg-10">
                            <?php echo form_input('name', $this->input->post('name') ? $this->input->post('name') : '', 'class="form-control" id="inputName" placeholder="Cruise"')?>
                          </div>
                        </div> -->
                        <div class="form-group">
                          <div class="col-lg-offset-2 col-lg-10">
                            <?php echo form_submit('submit', lang_check('Preview'), 'class="btn btn-primary"')?>
                            <a href="<?php echo site_url('admin/estate/')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                          </div>
                        </div>
                    <?php echo form_close()?>
                  </div>
                </div>
                <div class="widget-foot">
<?php if(!empty($preview_data)&&$imported!==TRUE): ?>
<p><?php  _l('Preview'); ?>:</p>
<?php echo form_open_multipart(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                 
<table class="table table-striped">
<tr>
<th><?php _l('#'); ?></th>
<th><?php _l('Title'); ?></th>
<th><?php _l('Address'); ?></th>
<th><?php _l('Gps'); ?></th>
<th><a href="#" id='selcect_deselect_chackbox' data-status='' class="btn btn-danger" type="button"><i class="icon-check"></i></a></th>
</tr>
<?php foreach($preview_data as $key=>$item): ?>
<tr class="<?php echo (isset( $item['exists'])&&!empty($item['exists']))?'tr-red': '';?>" >
   
<td><?php echo ++$key; ?></td>
<td class='tr-title'><?php echo $item['name']; ?></td>
<td><?php echo $item['address']; ?></td>
<td><a href="http://www.google.com/maps/place/<?php echo urlencode($item['gps']); ?>" target="_blunk"><?php echo $item['gps']; ?></a></td>
<td>
    <?php if(!isset( $item['exists']) || empty($item['exists'])): ?>
    <input type="checkbox" name="add_multiple[]" class='check-box-places' value="<?php echo $item['id'];?>" checked="checked">
    <?php endif;?>
</td>
</tr>
<?php endforeach; ?>
</table>
 </div>
<div class="widget-content">
    <div class="padd clearfix">
    <div class="form-group clearfix">
      <label class="col-lg-2 control-label"><?php echo lang_check('Choose category for import')?></label>
      <div class="col-lg-10">
            <?php echo form_dropdown('type_db', array_merge(array(''=>'Select category'),$category_list), $this->input->post('type_db'), 'class="form-control ui-state-valid"');?>
      </div>
    </div>     
    <div class="form-group clearfix">
      <label class="col-lg-2 control-label"><?php echo lang_check('Choose marker for import')?></label>
      <div class="col-lg-10">
            <?php echo form_dropdown('marker_category', array_merge(array(''=>'Select marker'),$marker_list), $this->input->post('marker_category'), 'class="form-control ui-state-valid"');?>
      </div>
    </div>     
    <div class="form-group clearfix">
      <label class="col-lg-2 control-label"><?php echo lang_check('Max images per property')?></label>
      <div class="col-lg-10">
            <?php echo form_input('max_images', $this->input->post('max_images') ? $this->input->post('max_images') : '1', 'class="form-control ui-state-valid"');?>
      </div>
    </div>     
                         
    <div class="form-group clearfix">
      <div class="col-lg-offset-2 col-lg-10">
        <?php echo form_submit('submit', lang_check('Import'), 'class="btn btn-primary" onclick="return confirm(\' All selected places will be import\')"')?>
        <a href="<?php echo site_url('admin/estate/')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
      </div>
    </div>
              
        
        <input type="hidden" name="form_import" value='1' />
        <input type="hidden" name="gps_google" value='<?php echo $gps_google;?>' />
        <input type="hidden" name="type" value='<?php echo $type;?>' />
        <input type="hidden" name="radius" value='<?php echo $radius;?>' />
        <input type="hidden" name="name" value='<?php echo $name;?>' />
<?php echo form_close()?>
</div> </div>
<?php elseif($imported==TRUE&&!empty($preview_data)): ?>
    <p><?php  _l('Added new location'); ?>:</p>
    <table class="table table-striped">
    <tr>
    <th><?php _l('#'); ?></th>
    <th><?php _l('Title'); ?></th>
    <th><?php _l('Address'); ?></th>
    <th><?php _l('Gps'); ?></th>
    </tr>
    <?php foreach($preview_data as $key=>$item): ?>
    <tr>
    <td><?php echo ++$key; ?></td>
    <td> <a href="<?php echo site_url('admin/estate/edit/'.$item['preview_id']); ?>"><?php echo $item['name']; ?></a></td>
    <td><?php echo $item['address']; ?></td>
    <td><?php echo $item['gps']; ?></td>
    </tr>
    <?php endforeach; ?>
    </table>
     </div>         
<?php endif; ?>
                  
                 
              </div>  

            </div>
        </div>

    </div>
</div>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
<script>

$(document).ready(function(){
    
    $('.selectpicker').selectpicker();
    
    $('#selcect_deselect_chackbox').click(function(e){
        e.preventDefault();
        $(".check-box-places").prop('checked', $(this).attr('data-status'));
        
        if($(this).attr('data-status')=='checked'){
           $(this).attr('data-status','')
        } else {
           $(this).attr('data-status','checked')
        }
    })
})

$(document).ready(function(){
    var autocomplete;
    function initialize() {
       var input = document.getElementById('inputAddress');
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', fillInAddress);

    }
    google.maps.event.addDomListener(window, 'load', initialize);


    function fillInAddress(){
        var place = autocomplete.getPlace().geometry.location;
        var lat = place.lat(),
            lng = place.lng();

        $('#inputGps').val(lat+', '+lng)   
    }
})
</script>