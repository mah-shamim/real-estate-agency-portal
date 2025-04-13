<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php _l('Custom fields')?>
          <!-- page meta -->
          <span class="page-meta"><?php _l('Manage custom user fields'); ?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/user')?>"><?php echo lang('Users')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/user/custom_fields')?>"><?php echo lang('Custom fields')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">
        
          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Custom fields')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Custom fields code')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('custom_fields_code', set_value('custom_fields_code', $custom_fields_code), 'class="form-control" id="input_custom_fields_code" placeholder="'.lang_check('Custom fields code').'" readonly="" ')?>
                                  </div>
                                </div>
                                
                                    <hr />
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/user')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
              </div>  

            </div>
            
            
            
            
            

          </div>
          
          <div class="row">
            <div class="col-md-3">


              <div class="widget worange">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Drag from here')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
<div class="container">
<div class="row">
  <div class="col-md-12">
        <div class="drag_visual_container">
        <table>
            <tr>
                <td class="box header">
                <span>FIELDS</span>

<?php

    $available_items = array('INPUTBOX', 'TEXTAREA', 'CHECKBOX'/*, 'DROPDOWN'*/);

    foreach($available_items as $key=>$row)
    {
        echo '<div class="el_drag el_style '.$row.'" f_direction="NONE" f_type="'.$row.'" f_id="" rel="">'.lang_check($row).'</div>';
    }          
?>
                </td>
            </tr>
        </table>
        </div>
  </div>

</div>

</div> 
                  </div>
                </div>
                  <div class="widget-foot">

</head>
<style>

.drag_visual_container
{
    width:100%;
    border:1px solid black;
    padding:5px;
    background: white;
    max-width:600px;
    margin:auto;
}

.drag_visual_container table
{
    width:100%;
}

.drag_visual_container .box
{
    border:1px solid #EEEEEE;
    height:40px;
    position: relative;
    vertical-align: top;
}

.drag_visual_container .box span
{
    display:block;
    text-align: center;
    background:#EEEEEE;
}

div.el_style
{
    background: #67BDC4;
    border:1px solid white;
    display:block;
    text-align: center;
    color:white;
    padding:5px;
    margin:2px 2px 2px 2px;
    float:left;
    width:100%;
    z-index: 100;
    cursor:move;
}

div.el_style.ui-draggable-dragging
{
    border:1px solid black;
    cursor: move;
}

div.el_style.custom
{
    background: #699057;
}

div.el_style.CHECKBOX
{
    background: #CC470C;
}

div.el_style.DROPDOWN
{
    background: #1E0D38;
}

div.el_style.INPUTBOX
{
    background: #4C8AB4;
}


</style>

                  </div>
              </div>  

            </div>
            <div class="col-md-9">


              <div class="widget wred">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Drop to here')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
<div class="container">
<div class="row">
  <div class="col-md-12">
        <div class="drop_visual_container">
        <table>
            <tr>
                <td class="box PRIMARY" colspan="2"><span class="">ELEMENTS</span>
<?php

$obj_widgets = json_decode($custom_fields_code);

if(isset($obj_widgets->PRIMARY) && is_object($obj_widgets->PRIMARY))
foreach($obj_widgets->PRIMARY as $key=>$obj)
{
    $title = '';
    $rel = $obj->rel;
    $class_color = $obj->type;
    $direction = 'NONE';
    $title.=lang_check($obj->type);
    
    $title_lab = $title.', '.$obj->{"label_$content_language_id"};
    
    if(!empty($title))
    echo '<div class="el_sort el_style '.$class_color.'" f_style="'.$obj->style.'" f_class="'.$obj->class.'" f_direction="'.$direction.'" f_type="'.$obj->type.'" f_id="" rel="'.$rel.'" style="width:100%;"><span>'.$title_lab.
         '</span><a href="#test-form" target="_blank" class="btn btn-success btn-xs popup-with-form"><i class="icon-edit"></i></a>'.
         '<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button></div>';
}
?>
                </td>
            </tr>

        </table>
        </div>
    </div>
</div>



</div> 
                  </div>
                </div>
                  <div class="widget-foot">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('admin-assets/js/magnific-popup/magnific-popup.css')?>" /> 
<script src="<?php echo base_url('admin-assets/js/magnific-popup/jquery.magnific-popup.js')?>"></script> 
<script>

var id_autoincrement = 1;

$(function() {
    
    // fetch dfield data details from string
    var first_json = '';
    if($('#input_custom_fields_code').val() != '')
    {
        first_json = jQuery.parseJSON($('#input_custom_fields_code').val());
        $.each( first_json.PRIMARY, function( key, value ) {
            $('.el_sort[rel='+key+']').data('f_json',value);
            if(key > id_autoincrement)id_autoincrement = parseInt(key)+1;
        });
        id_autoincrement++;
    }
    
    $( ".el_drag" ).draggable({
        revert: "invalid",
        zIndex: 9999,
        helper: "clone"
    });
    
    <?php $widget_positions = array('PRIMARY');
          foreach($widget_positions as $position_box): ?>
    
    $( ".box.<?php echo $position_box; ?>" ).sortable({items: "div.el_sort"});
    
    $(".drop_visual_container .box.<?php echo $position_box; ?>" ).droppable({
      accept: ".el_drag",
      activeClass: "ui-state-hover",
      hoverClass: "ui-state-active",
      drop: function( event, ui ) {
        var exists = false;
        
//        jQuery.each($('.el_sort'), function( i, val ) {
//            if(ui.draggable.attr('rel') == $(this).attr('rel') && ui.draggable.attr('rel') != 'BREAKLINE')
//            {
//                exists = true;
//            }
//        });
//        
//        if(exists)
//        {
//            ShowStatus.show('<?php echo lang_check('Already added'); ?>');
//            return;   
//        }
        
        var new_el = ui.draggable.clone();
        new_el.attr('rel', id_autoincrement);
        new_el.css('top', 'auto');
        new_el.css('left', 'auto');
        new_el.css('width', '100%');
        new_el.removeClass('el_drag');
        new_el.addClass('el_sort');
        new_el.attr('f_style', '');
        new_el.attr('f_json', '');
        new_el.attr('f_class', '');
        new_el.html('<span>'+new_el.html()+'</span>');
        new_el.append('<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button>');
        new_el.append('<a href="#test-form" class="btn btn-success btn-xs popup-with-form"><i class="icon-edit"></i></a>');
        
        new_el.clone().appendTo( this );
        
        id_autoincrement++;
        
        $(this).sortable("refresh"); 
        
        $('.drop_visual_container .box .btn-danger').click(function(){
           $(this).parent().remove();
           save_json_changes();
        });
        
        save_json_changes();
      }
    }).sortable({
      update: function( event, ui ) {
        save_json_changes();
      },
      items: "div.el_sort"
    });
    <?php endforeach;?>
    
    $('.drop_visual_container .box .btn-danger').click(function(){
       $(this).parent().remove();
       save_json_changes();
    });
    
    define_popup_trigers();
    
    $('#unhide-agent-mask').click(function(){
        
        var data = $('#test-form').serializeArray();

        $('.el_sort[rel='+data[0].value+']').attr('f_style', filterInput(data[1].value));
        $('.el_sort[rel='+data[0].value+']').attr('f_class', filterInput(data[2].value));

        var res = data[0].value.split("_");
        var res2 = $('.el_sort[rel='+data[0].value+'] span').html().split(", ");
        
        var data_prepare = {};
        $.each( data, function( key, value ) {
          data_prepare[value.name] = value.value;
        });

        $('.el_sort[rel='+data[0].value+']').data('f_json',data_prepare);
        $('.el_sort[rel='+data[0].value+'] span').html(res2[0]+', '+data[3].value)
        
        save_json_changes();

        // Display agent details
        //$('.popup-with-form').css('display', 'none');
        // Close popup
        $.magnificPopup.instance.close();

        return false;
    });
    
});

function filterInput(input){
    return input.replace(/[^a-zA-Z0-9:;-]/g, '');
}

function define_popup_trigers()
{
    $('.popup-with-form').magnificPopup({
    	type: 'inline',
    	preloader: false,
    	focus: '#inputStyle',
                        
    	// When elemened is focused, some mobile browsers in some cases zoom in
    	// It looks not nice, so we disable it:
    	callbacks: {
    		beforeOpen: function() {
    			if($(window).width() < 700) {
    				this.st.focus = false;
    			} else {
    				this.st.focus = '#inputStyle';
    			}
    		},
            
    		open: function() {
                var magnificPopup = $.magnificPopup.instance,
                cur = magnificPopup.st.el.parent();
                
                $('#test-form').find('input').val('');
                
                $('#inputRel').val(cur.attr('rel'));
                $('#inputStyle').val(cur.attr('f_style'));
                $('#inputClass').val(cur.attr('f_class'));
                
                if(cur.data('f_json'))
                $.each( cur.data('f_json'), function( key, value ) {
                    if($('#'+key))
                        $('#'+key).val(value);
                });
                
                // select first tab
                $('#test-form .tabbable a:first').tab('show');

    		}
    	}
    });
}

function save_json_changes()
{
    var js_gen = '{ ';
    <?php foreach($widget_positions as $position_box): ?>
    js_gen+= ' "<?php echo $position_box; ?>": {  ';
    
    jQuery.each($(".drop_visual_container .box.<?php echo $position_box; ?> div"), function( i, val ) {
       if($(this).attr('rel'))
       {
            var element_string = '{';
            if($(this).data('f_json'))
            {
                element_string+='"type":"'+$(this).attr('f_type')+'" ,';
                
                $.each( $(this).data('f_json'), function( key, value ) {
                    element_string+='"'+key+'":"'+value+'" ,';
                });
                
                element_string = element_string.slice(0,-2);
            }

            element_string+='}';
        
            js_gen+= '"'+$(this).attr('rel')+'":'+element_string+' ,';
       }
        
    });
    
    js_gen = js_gen.slice(0,-2);
        
    js_gen+= ' },';
    <?php endforeach; ?>
    js_gen = js_gen.slice(0,-1);
    js_gen+= ' }';
    
    $('#input_custom_fields_code').val(js_gen);
    
    define_popup_trigers();
}

</script>

<style>

.drop_visual_container
{
    width:100%;
    border:1px solid black;
    padding:5px;
    background: white;
    max-width:600px;
    margin:auto;
}

.drop_visual_container table
{
    width:100%;
}

.drop_visual_container .box
{
    border:1px solid #EEEEEE;
    height:400px;
    position: relative;
    vertical-align: top;
}

.drop_visual_container .box.bottom,
.drop_visual_container .box.footer,
.drop_visual_container .box.header
{
    height:100px;
}

.drop_visual_container .box span
{
    display:block;
    text-align: center;
    background:#EEEEEE;
}

.drop_visual_container .box .el_sort span
{
    background:none;
}

.drop_visual_container .box div
{
    position:relative;
}

.drop_visual_container .box .btn-danger
{
    right:5px;
    position:absolute;
    top:5px;
}

.drop_visual_container .box .btn-success
{
    right:28px;
    position:absolute;
    top:5px;
}

<?php if(config_db_item('secondary_disabled') === TRUE): ?>
td.box.SECONDARY
{
    display:none;
} 
<?php endif; ?>

</style>

                  </div>
              </div>  

            </div>
</div>

        </div>
		  </div>
          
<!-- form itself -->
<form id="test-form" class="form-horizontal mfp-hide white-popup-block">
    <div id="popup-form-validation">
    <p class="hidden alert alert-error"><?php echo lang_check('Submit failed, please populate all fields!'); ?></p>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputRel"><?php echo lang_check('Rel'); ?></label>
        <div class="controls">
            <input type="text" name="rel" id="inputRel" value="" placeholder="<?php echo lang_check('Rel'); ?>" readonly="" />
        </div>
    </div>
    <div class="control-group hide">
        <label class="control-label" for="inputStyle"><?php echo lang_check('Style'); ?></label>
        <div class="controls">
            <input type="text" name="style" id="inputStyle" value="" placeholder="<?php echo lang_check('Style'); ?>">
        </div>
    </div>
    <div class="control-group hide">
        <label class="control-label" for="inputClass"><?php echo lang_check('Class'); ?></label>
        <div class="controls">
            <input type="text" name="class" id="inputClass" value="" placeholder="<?php echo lang_check('Class'); ?>">
        </div>
    </div>
    
                               <h5><?php echo lang('Translation data')?></h5>
                               <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($this->page_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?> lang"><a data-toggle="tab" href="#<?php echo $key_lang?>"><?php echo $val_lang?></a></li>
                                    <?php endforeach;?>                                    
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->page_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <div id="<?php echo $key_lang?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php _l('Label')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('label_'.$key_lang, "", 'class="form-control" id="label_'.$key_lang.'" placeholder="'.lang('Label').'"')?>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group hide">
                                          <label class="col-lg-2 control-label"><?php _l('Values')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('values_'.$key_lang, "", 'class="form-control" id="values_'.$key_lang.'" placeholder="'.lang('Values').'"')?>
                                          </div>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>

    <div class="control-group">
        <div class="controls">
            <button id="unhide-agent-mask" type="button" class="btn"><?php echo lang_check('Submit'); ?></button>
            <img id="ajax-indicator-masking" src="<?php echo base_url(); ?>admin-assets/img/loading.gif" style="display: none;" />
        </div>
    </div>
</form>