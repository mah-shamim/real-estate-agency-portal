<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Visual templates editor')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo empty($listing->id) ? lang_check('Add result item template') : lang_check('Edit result item template').' "' . $listing->id.'"'?></span>
        </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread" href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Settings')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/templates')?>"><?php echo lang_check('Visual templates editor')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="#"><?php echo lang_check('Edit template')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Visual templates editor')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Theme')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('theme', $this->data['settings']['template'], 'class="form-control" readonly=""')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Template name')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('template_name', $this->input->post('template_name') ? $this->input->post('template_name') : $listing->template_name, 'placeholder="'.lang_check('Template name').'" class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Type')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('type', 'RESULT_ITEM', 'class="form-control" readonly=""')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Widgets order')?></label>
                                  <div class="col-lg-10">
                                    <?php 
                                    $widgets_value_json = set_value('widgets_order', $listing->widgets_order);
                                    $widgets_value_json = htmlspecialchars_decode($widgets_value_json);
                                    
                                    echo form_textarea('widgets_order', $widgets_value_json, 'placeholder="'.lang_check('Widgets order').'" rows="2" class="form-control" id="widgets_order_json" readonly="" ')?>
                                  </div>
                                </div>   

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/templates')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
              </div>  

            </div>
</div>

          <div class="row">
            <div class="col-md-6">


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
                <td class="box center">
                <span>ELEMENTS</span>
<?php
    $enabled_items = array('DROPDOWN', 'INPUTBOX', 'CHECKBOX');

    foreach($this->fields as $key=>$row)
    {
        if(in_array($row->type, $enabled_items))
            echo '<div class="el_drag el_style center '.$row->type.'" f_style="" f_class="" f_direction="NONE" f_type="'.$row->type.'" f_id="'.$row->id.'" f_title="'.$row->option.'" rel="id_'.$row->id.'">#'.$row->id.', '.$row->option.'</div>';
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
    width:32%;
    z-index: 100;
    cursor:move;
}

div.el_style.ui-draggable-dragging
{
    border:1px solid black;
    cursor: move;
}

.center div.el_style
{
    background: #699057;
}

.right div.el_style
{
    background: #CC470C;
}

.bottom div.el_style
{
    background: #1E0D38;
}

.footer div.el_style
{
    background: #4C8AB4;
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
            <div class="col-md-6">


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
                <td class="box center" style="width: 100%;"><span>ELEMENTS</span>                
<?php
$obj_widgets = json_decode($widgets_value_json);

if(isset($obj_widgets->center) && is_object($obj_widgets->center))
foreach($obj_widgets->center as $key=>$row)
{
    echo '<div class="el_sort el_style '.$row->type.'" f_direction="NONE" f_style="'.$row->f_style.'" f_class="'.$row->f_class.'" f_type="'.$row->type.'" f_id="'.$row->f_id.'" f_title="'.$row->f_title.'" rel="id_'.$row->f_id.'" style="width:100%;">#'.$row->f_id.', '.$row->f_title.
         '<a href="#test-form" class="btn btn-success btn-xs popup-with-form"><i class="icon-edit"></i></a>'.
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

$(function() {
    
    $('#widgets_order_json').val('<?php echo $widgets_value_json; ?>');
    
    <?php $widget_positions = array('center');
          foreach($widget_positions as $position_box): ?>
    
    $( ".box.<?php echo $position_box; ?>" ).sortable({items: "div.el_sort"});
    
    $( ".el_drag.<?php echo $position_box; ?>" ).draggable({
        revert: "invalid",
        zIndex: 9999,
        helper: "clone"
    });
    
    $(".drop_visual_container .box.<?php echo $position_box; ?>" ).droppable({
      accept: ".el_drag.<?php echo $position_box; ?>",
      activeClass: "ui-state-hover",
      hoverClass: "ui-state-active",
      drop: function( event, ui ) {
        var exists = false;
        
        jQuery.each($(this).find('div'), function( i, val ) {
            if(ui.draggable.attr('rel') == $(this).attr('rel'))
            {
                console.log('finded');
                exists = true;
            }
        });
        
        if(exists)
        {
            ShowStatus.show('<?php echo lang_check('Already added'); ?>');
            return;   
        }
        
        var new_el = ui.draggable.clone();
        new_el.css('top', 'auto');
        new_el.css('left', 'auto');
        new_el.css('width', '100%');
        new_el.removeClass('el_drag');
        new_el.addClass('el_sort');
        new_el.append('<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button>');
        new_el.append('<a href="#test-form" class="btn btn-success btn-xs popup-with-form"><i class="icon-edit"></i></a>');
        new_el.clone().appendTo( this );

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
                
                $('#inputRel').val(cur.attr('rel'));
                $('#inputStyle').val(cur.attr('f_style'));
                $('#inputClass').val(cur.attr('f_class'));

    		}
    	}
    });
}

function save_json_changes()
{
    var js_gen = '{ ';
    <?php foreach($widget_positions as $position_box): ?>
    js_gen+= ' "<?php echo $position_box; ?>":  {  ';
    
    jQuery.each($(".drop_visual_container .box.<?php echo $position_box; ?> div"), function( i, val ) {
       if($(this).attr('rel'))
        js_gen+= '"'+$(this).attr('rel')+'":{"f_id":"'+$(this).attr('f_id')+'", "f_title":"'
                    +$(this).attr('f_title')+'", "f_style":"'+$(this).attr('f_style')+'", "f_class":"'+
                    $(this).attr('f_class')+'", "type":"'+$(this).attr('f_type')+'"} ,';
    });
    
    js_gen = js_gen.slice(0,-2);
        
    js_gen+= ' },';
    <?php endforeach; ?>
    js_gen = js_gen.slice(0,-1);
    js_gen+= ' }';
    
    $('#widgets_order_json').val(js_gen);
    
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
    height:200px;
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
            <input type="text" name="rel" id="inputRel" value="" placeholder="<?php echo lang_check('Rel'); ?>" readonly="" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputStyle"><?php echo lang_check('Style'); ?></label>
        <div class="controls">
            <input type="text" name="style" id="inputStyle" value="" placeholder="<?php echo lang_check('Style'); ?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputClass"><?php echo lang_check('Class'); ?></label>
        <div class="controls">
            <input type="text" name="class" id="inputClass" value="" placeholder="<?php echo lang_check('Class'); ?>">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button id="unhide-agent-mask" type="button" class="btn"><?php echo lang_check('Submit'); ?></button>
            <img id="ajax-indicator-masking" src="<?php echo base_url(); ?>admin-assets/img/loading.gif" style="display: none;" />
        </div>
    </div>
</form>