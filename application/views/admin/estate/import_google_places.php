<!-- Page heading -->
<div class="page-head">
<!-- Page heading -->
<h2 class="pull-left"><?php echo lang_check('Google import')?>
              <!-- page meta -->
              <span class="page-meta"><?php echo lang_check('Google import places')?></span>
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
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open_multipart(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Gps')?></label>
                          <div class="col-lg-10">
                            <?php echo form_input('gps_google', $this->input->post('gps_google') ? $this->input->post('gps_google') : $gps, 'class="form-control" id="inputMinStay" placeholder="-33.8670,151.1957"')?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Radius')?> (m)</label>
                          <div class="col-lg-10">
                            <?php echo form_input('radius', $this->input->post('radius') ? $this->input->post('radius') : '500', 'class="form-control" id="inputRadisu" placeholder="500"')?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Types')?></label>
                          <div class="col-lg-10">
                                <?php echo form_dropdown('type', array_merge(array(''=>'Select type'),$types_list), $this->input->post('type'), 'class="form-control ui-state-valid"');?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Name')?></label>
                          <div class="col-lg-10">
                            <?php echo form_input('name', $this->input->post('name') ? $this->input->post('name') : '', 'class="form-control" id="inputRadisu" placeholder="Cruise"')?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Language')?></label>
                          <div class="col-lg-10">
                            <?php echo form_dropdown('lang_api', array_merge(array(''=>'Select lang'),$langs_api), $this->input->post('lang_api') ? $this->input->post('lang_api') : $lang_code, 'class="form-control" id="inputLang" placeholder="Lang"')?>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="inputGeocode_api"><?php echo lang_check('Use Geocode Api (address, city, country)')?></label>
                            <div class="col-lg-10">
                                <?php echo form_checkbox('geocode_api', 1, $this->input->post('geocode_api') ? $this->input->post('geocode_api') : false, 'id="inputGeocode_api"')?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="inputplace_in_detail"><?php echo lang_check('Import place in detail (phone number, open hours, website)')?></label>
                            <div class="col-lg-10">
                                <?php echo form_checkbox('place_in_detail', 1, $this->input->post('place_in_detail') ? $this->input->post('place_in_detail') : false, 'id="inputplace_in_detail"')?>
                            </div>
                        </div>
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
    <?php if(/*!isset( $item['exists']) || empty($item['exists'])*/ TRUE): ?>
    <input type="checkbox" name="add_multiple[]" class='check-box-places' value="<?php echo $item['id'];?>" checked="checked">
    <?php endif;?>
</td>
</tr>
<?php endforeach; ?>
</table>
 </div>
<div class="widget-content">
    <div class="padd clearfix">
    <div class="form-group clearfix <?php if($settings['template'] !=='realsite') echo 'hidden';?>">
      <label class="col-lg-2 control-label"><?php echo lang_check('Choose category for import')?></label>
      <div class="col-lg-10">
            <?php echo form_dropdown('type_db', array_merge(array(''=>'Select category'),$category_list), $this->input->post('type_db'), 'class="form-control ui-state-valid"');?>
      </div>
    </div>
    <div class="form-group TREE-GENERATOR <?php if($settings['template'] =='realsite') echo 'hidden';?>">
        <label class="col-lg-2 control-label">
            <?php echo lang_check('Category into import') ?>
            <div class="ajax_loading"> </div>
        </label>
        <div class="col-lg-10">
            <?php
            $this->load->model('treefield_m');
            $drop_options = $this->treefield_m->get_level_values(1, 79);
            $drop_selected = array();

            echo '<div class="field-row">';
            echo form_dropdown('option79_1_level_0', $drop_options, $drop_selected, 'class="form-control" id="inputOption_1_79_level_0" placeholder=""');
            echo '</div>';


            $levels_num = $this->treefield_m->get_max_level(79);
            if ($levels_num > 0)
                for ($ti = 1; $ti <= $levels_num; $ti++) {
                    echo '<div class="field-row">';
                    echo form_dropdown('option79_1_level_' . $ti, array('' => lang_check('Please select parent')), array(), 'class="form-control" id="inputOption_1_79_level_' . $ti . '" placeholder=""');
                    echo '</div>';
                }
            ?>
            <div class="field-row hidden">
            <?php echo form_input('option79_1', set_value('option79_1', isset($estate->{'option79_1'}) ? $estate->{'option79_1'} : ''), 'class="form-control tree-input-value" id="inputOption_1_79" rel="" placeholder=""') ?>
            </div>
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
        <input type="hidden" name="lang_api" value='<?php echo $lang_api;?>' />
        <input type="hidden" id="inputGeocode_api_secondary" name="geocode_api" value='<?php echo $geocode_api;?>' />
        <input type="hidden" id="place_in_detail" name="place_in_detail" value='<?php echo $place_in_detail;?>' />
        <input type="hidden" name="cache_results" value='<?php echo $cache_results;?>' />
<?php echo form_close()?>
</div> </div>
<?php elseif($imported==TRUE&&!empty($preview_data)): ?>
    <p><?php  _l('Added/updated location'); ?>:</p>
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
              
        <br/>
        <?php if(isset($output_log) && !empty($output_log)):?>
        <div class="row">
            <div class="col-md-12">
                <div class="widget wlightblue">
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Developer log Google Api output')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-down"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>
                  <div class="widget-content" style="display: none;">
                    <!-- Nested Sortable -->
                    <div class="padd clearfix">
                        <textarea name="" id="" width="100%" style="width:100%" rows="10"><?php echo($output_log);?></textarea>
                    </div>
                  </div>
                </div>
            </div>
        </div>       
        <?php endif;?> 
           
              </div>  

            </div>
</div>

        </div>
</div>

<script>

$(document).ready(function(){
    $('#selcect_deselect_chackbox').click(function(e){
        e.preventDefault();
        $(".check-box-places").prop('checked', $(this).attr('data-status'));
        
        if($(this).attr('data-status')=='checked'){
           $(this).attr('data-status','')
        } else {
           $(this).attr('data-status','checked')
        }
    })
    
    $('#inputGeocode_api').change(function(){
        $('#inputGeocode_api_secondary').val($('#inputGeocode_api').val());
    })
})

</script>


<script type="text/javascript">

    /* [START] TreeField */
    
    $(function() {
        $(".TREE-GENERATOR select").change(function(){
            var s_value = $(this).val();
            var s_name_splited = $(this).attr('name').split("_"); 
            var s_level = parseInt(s_name_splited[3]);
            var s_lang_id = s_name_splited[1];
            var s_field_id = s_name_splited[0].substr(6);
            // console.log(s_value); console.log(s_level); console.log(s_field_id);
            
            load_by_field($(this));
            
            // Reset child selection and value generator
            var generated_val = '';
            var last_selected_numeric = '';
            $(this).parent().parent()
            .find('select').each(function(index){
                // console.log($(this).attr('name'));
                if(index > s_level)
                {
                    $(this).html('<option value=""><?php echo lang_check('No values found'); ?></option>');
                    $(this).val('');
                }
                else if($(this).val() != '')
                {
                    last_selected_numeric = $(this).val();
                    generated_val+=$(this).find("option:selected").text()+" - ";
                }
                    
            });

            $("#inputOption_"+s_lang_id+"_"+s_field_id).attr('rel', last_selected_numeric);
            $("#inputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);
            $("#inputOption_"+s_lang_id+"_"+s_field_id).trigger("change");

        });
        
        // Autoload selects
        $(".TREE-GENERATOR input.tree-input-value").each(function(index_1){
            var s_values_splited = ($(this).val()+" ").split(" - "); 
//            $.each(s_values_splited, function( index, value ) {
//                alert( index + ": " + value );
//            });
            if(s_values_splited[0] != '')
            {
                var first_select = $(this).parent().parent().find('select:first');
                var find_selected = first_select.find('option').filter(function () { return $(this).html() == s_values_splited[0]; });
                find_selected.attr('selected', 'selected');
                
                var index_tree = find_selected.val();
                if (typeof index_tree !== typeof undefined && index_tree !== false)
                {
                    if($(this).attr('rel') != index_tree)
                    {
                        $(this).attr('rel', index_tree);
                        $(this).trigger("change");
                    }
                }

                load_by_field(first_select, true, s_values_splited);
            }
            
            //console.log('value: '+s_values_splited[0]);
        });

    });
    
    function load_by_field(field_element, autoselect_next, s_values_splited)
    {
        if (typeof autoselect_next === 'undefined') autoselect_next = false;
        if (typeof s_values_splited === 'undefined') s_values_splited = [];

        var s_value = field_element.val();
        var s_name_splited = field_element.attr('name').split("_"); 
        var s_level = parseInt(s_name_splited[3]);
        var s_lang_id = s_name_splited[1];
        var s_field_id = s_name_splited[0].substr(6);
        // console.log(s_value); console.log(s_level); console.log(s_field_id);
        
        // Load values for next select
        var ajax_indicator = field_element.parent().parent().parent().find('.ajax_loading');
        var select_element = $("select[name=option"+s_field_id+"_"+s_lang_id+"_level_"+parseInt(s_level+1)+"]");
        if(select_element.length > 0 && s_value != '')
        {
            ajax_indicator.css('display', 'block');
            $.getJSON( "<?php echo site_url('privateapi/get_level_values_select'); ?>/"+s_lang_id+"/"+s_field_id+"/"+s_value+"/"+parseInt(s_level+1), function( data ) {
                //console.log(data.generate_select);
                //console.log("select[name=option"+s_field_id+"_"+s_lang_id+"_level_"+parseInt(s_level+1)+"]");
                ajax_indicator.css('display', 'none');
                
                select_element.html(data.generate_select);
                
                if(autoselect_next)
                {
                    if(s_values_splited[s_level+1] != '')
                    {
                        var find_selected = select_element.find('option').filter(function () { return $(this).html() == s_values_splited[s_level+1]; });
                        
                        find_selected.attr('selected', 'selected');
                        var index_tree = find_selected.val();
                        if (typeof index_tree !== typeof undefined && index_tree !== false)
                        {
                            var input_element = field_element.parent().parent().find("input.tree-input-value");

                            if(input_element.attr('rel') != index_tree)
                            {
                                input_element.attr('rel', index_tree);
                                $(input_element).trigger("change");
                            }
                        }
                        
                        load_by_field(select_element, true, s_values_splited);
                    }
                }
            });
        }
    }
    
    function load_and_select_index(field_element, field_select_id, field_parent_select_id)
    {
        var s_name_splited = field_element.attr('name').split("_"); 
        var s_level = parseInt(s_name_splited[3]);
        var s_lang_id = s_name_splited[1];
        var s_field_id = s_name_splited[0].substr(6);
        
        // Load values for current select
        var ajax_indicator = field_element.parent().parent().parent().find('.ajax_loading');
        if(s_level == 0)$("#inputOption_"+s_lang_id+"_"+s_field_id).attr('value', '');

        ajax_indicator.css('display', 'block');
        $.getJSON( "<?php echo site_url('privateapi/get_level_values_select'); ?>/"+s_lang_id+"/"+s_field_id+"/"+field_parent_select_id+"/"+parseInt(s_level), function( data ) {
            ajax_indicator.css('display', 'none');
            
            field_element.html(data.generate_select);
            //console.log(field_select_id);
            if(isNumber(field_select_id))
                field_element.val(field_select_id);
            else
                field_element.val('');
            
            var generated_val = '';
            var last_selected_val = '';
            
            field_element.parent().parent()
            .find('select').each(function(index){
                if($(this).val() != '' && $(this).val() != null)
                {
                    last_selected_val = $(this).val();
                    generated_val+=$(this).find("option:selected").text()+" - ";
                }
            });

            if(generated_val.length > $("#inputOption_"+s_lang_id+"_"+s_field_id).val().length)
            {
                $("#inputOption_"+s_lang_id+"_"+s_field_id).attr('rel', last_selected_val);
                $("#inputOption_"+s_lang_id+"_"+s_field_id).val(generated_val);
                $("#inputOption_"+s_lang_id+"_"+s_field_id).trigger('change');
            }

        });

    }
    
    function isNumber(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    /* [END] TreeField */

</script>
