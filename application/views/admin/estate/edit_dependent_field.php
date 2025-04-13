<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php _l('Dependent field')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($item->id_dependent_field) ? lang_check('Add dependent field') : lang('Edit dependent field').' #' . $item->id_dependent_field.''?></span>
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

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php _l('Dependent field data')?></div>
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
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              


                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php _l('Dependent field')?></label>
                                  <div class="col-lg-9">
                                  <?php if( empty($item->id_dependent_field) ): ?>
                                    <?php echo form_dropdown('field_id', $available_fields, $this->input->post('field_id') ? $this->input->post('field_id') : $item->field_id, 'class="form-control"')?>
                                  <?php else: ?>
                                    <?php echo form_dropdown('field_id_x', $available_fields, $this->input->post('field_id') ? $this->input->post('field_id') : $item->field_id, 'class="form-control" disabled')?>
                                    <?php echo form_input('field_id', $this->input->post('field_id') ? $this->input->post('field_id') : $item->field_id, 'class="hidden"')?>
                                  <?php endif; ?>
                                  </div>
                                </div>

                                <?php if( empty($item->id_dependent_field) ): ?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"></label>
                                  <div class="col-lg-9">
                                    <span class="label label-danger"><?php _l('After saving, you can define other parameters');?></span>
                                  </div>
                                </div>
                                <?php else: 
                                    $depended_field = $this->option_m->get($item->field_id);
                                    if(!empty($depended_field)):
                                ?>
                                
                                <?php if($depended_field->type == 'DROPDOWN' || $depended_field->type == 'DROPDOWN_MULTIPLE'): ?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php _l('Selected index')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_dropdown('selected_index', $available_indexes, $this->input->post('selected_index') ? $this->input->post('selected_index') : $item->selected_index, 'class="form-control"')?>
                                  </div>
                                </div>
                                <?php elseif($depended_field->type == 'TREE'): ?>
                                <div class="form-group search-form">
                                  <label class="col-lg-3 control-label"><?php _l('Selected index')?></label>
                                  <div class="col-lg-9">
                <!-- [START] TreeSearch -->
                <?php if(config_item('tree_field_enabled') === TRUE):?>
                <?php
                
                    $CI =& get_instance();
                    $CI->load->model('treefield_m');
                    $field_id = $depended_field->id;
                    $lang_id = $content_language_id;
                    $drop_options = $CI->treefield_m->get_level_values($lang_id, $field_id);
                    $drop_selected = array();
                    echo '<div class="tree TREE-GENERATOR tree-'.$field_id.'">';
                    echo '<div class="field-tree">';
                    echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_0', $drop_options, $drop_selected, 'class="form-control selectpicker tree-input" id="sinputOption_'.$lang_id.'_'.$field_id.'_level_0'.'"');
                    echo '</div>';
                    
                    $levels_num = $CI->treefield_m->get_max_level($field_id);
                    
                    if($levels_num>0)
                    for($ti=1;$ti<=$levels_num;$ti++)
                    {
                        $lang_empty = lang_check('treefield_'.$field_id.'_'.$ti);
                        if(empty($lang_empty))
                            $lang_empty = lang_check('Please select parent');
                        
                        echo '<div class="field-tree">';
                        echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_'.$ti, array(''=>$lang_empty), array(), 'class="form-control selectpicker tree-input" id="sinputOption_'.$lang_id.'_'.$field_id.'_level_'.$ti.'"');
                        echo '</div>';
                    }
                    echo '</div>';
                
                ?>
                
                <script language="javascript">
                
                $(function() {
                    var load_index = '<?php echo set_value('selected_index', $item->selected_index); ?>';
                    
                    var load_val = '<?php 
                    $treefield_id = set_value('selected_index', $item->selected_index);
                    
                    if(!empty($treefield_id))
                    {
                        $path = $CI->treefield_m->get_path($field_id, $treefield_id, $lang_id);
                        echo $path;
                    }
                    ?>';
                    
                    $('#input_county_affiliate_values').val(load_index);
                    
                    var s_values_splited = (load_val+" ").split(" - "); 
//            $.each(s_values_splited, function( index, value ) {
//                alert( index + ": " + value );
//            });
                    if(s_values_splited[0] != '')
                    {
                        var first_select = $('.tree-<?php _che($item->field_id); ?>').find('select:first');
                        first_select.find('option').filter(function () { return $(this).html() == s_values_splited[0]; }).attr('selected', 'selected');
                        
                        if(first_select.length > 0)
                            load_by_field(first_select, true, s_values_splited);
                    }
                });
                
                </script>
                <?php endif; ?>
                <!-- [END] TreeSearch -->
                                  
                                    <?php echo form_input('selected_index', $this->input->post('selected_index') ? $this->input->post('selected_index') : $item->selected_index, 'class="form-control hidden" id="input_county_affiliate_values"')?>
                                  </div>
                                </div>
                                <?php else: ?>
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"> </label>
                                  <div class="col-lg-9">
                                    <span class="label label-warning"><?php _l('Type is not suitable'); echo ' - '.$depended_field->type;?></span>
                                  </div>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                                
                                <hr />
                                <h5><?php _l('Hidden fields under selected')?></h5>
                                <hr />
                                
                                <?php foreach($fields_under_selected as $key=>$field): ?>
                                
                                <?php if($field->type == 'CATEGORY'): ?>
                                <hr />
                                <?php endif; ?>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo $field->option; ?></label>
                                  <div class="col-lg-9">
                                    <?php 
                                    
                                    $val = $this->input->post('field_'.$field->id);
                                    
                                    if(empty($val))
                                    {
                                        if(isset($item->{'field_'.$field->id}))
                                        $val = $item->{'field_'.$field->id};
                                    }
                                    
                                    echo form_checkbox('field_'.$field->id, '1', $val, 'class="type_'.$field->type.'"')?>
                                  </div>
                                </div>
                                
                                <?php if($field->type == 'CATEGORY'): ?>
                                <hr />
                                <?php endif; ?>
                                
                                <?php endforeach; ?>
                                
                                <?php endif; ?>

                                <div class="form-group">
                                  <div class="col-lg-offset-3 col-lg-9">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/estate/dependent_fields')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>

          </div>
        </div>
    </div>

<script language="javascript">

$(document).ready(function() {

        $("input.type_CATEGORY").change(function(){
            var is_checked = $(this).is(":checked");

            $.each( $(this).parent().parent().nextAll(), function( key, value ) {
                if($(value).find('input.type_CATEGORY').length>0)return false;
                $(value).find('input').prop('checked', is_checked);
            });
            
        });

});


</script>

<?php if(config_item('tree_field_enabled') === TRUE):?>
<script language="javascript">
    
    /* [START] TreeField */

    $(function() {

        $(".search-form .TREE-GENERATOR select").change(function(){
            var s_value = $(this).val();
            var s_name_splited = $(this).attr('name').split("_"); 
            var s_level = parseInt(s_name_splited[3]);
            var s_lang_id = s_name_splited[1];
            var s_field_id = s_name_splited[0].substr(6);
            // console.log(s_value); console.log(s_level); console.log(s_field_id);
            
            load_by_field($(this));
            
            // Reset child selection and value generator
            var generated_val = '';
            $(this).parent().parent()
            .find('select').each(function(index){
                // console.log($(this).attr('name'));
                if(index > s_level)
                {
                    //$(this).html('<option value=""><?php echo lang_check('No values found'); ?></option>');
                    
                    $(this).find("option:gt(0)").remove();
                    $(this).val('');
                }
                else
                {
                    if($(this).find("option:selected").index() > 0)
                    generated_val+=$(this).find("option:selected").text()+" - ";
                }
            });
            //console.log(generated_val);
            //$("#input_county_affiliate_values").val(generated_val);
            $("#input_county_affiliate_values").val(s_value);
            

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
                        select_element.find('option').filter(function () { return $(this).html() == s_values_splited[s_level+1]; }).attr('selected', 'selected');
                        //$('.search-form select.selectpicker').selectpicker('render');
                        load_by_field(select_element, true, s_values_splited);
                        
                        
                    }
                }
            });
        }
    }
    
    /* [END] TreeField */

</script>
<?php endif; ?>