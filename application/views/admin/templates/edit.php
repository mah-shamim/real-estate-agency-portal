<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Visual templates editor')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo empty($listing->id) ? lang_check('Add template') : lang_check('Edit template').' "' . $listing->id.'"'?></span>
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
                                    <?php echo form_input('type', 'RIGHT', 'class="form-control" readonly=""')?>
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
                <td class="box header">
                <span>HEADER</span>
<?php
    $get_type = 'header';
    
    foreach($this->widgets as $key=>$val)
    {
        if(substr($key, 0, strlen($get_type)) == $get_type)
        {
            /* hint */
            $hint='';
            $widget_title = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-title');
            $widget_image = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-preview-image');
            if(!empty($widget_title) || !empty($widget_image) ) {
                $hint .='<div class="widget-hint">'
                        . '<div class="title">'.lang_check($widget_title).'</div>';

                if(!empty($widget_image) && file_exists(FCPATH."templates/".$settings["template"].$widget_image))
                        $hint.='<div class="widget-preview">'
                                 . '<img src="'.base_url("/templates/".$settings["template"].$widget_image).'" alt="" />'
                             . '</div>';

                $hint .= '</div>';
            }
            /* end hint */
            
            echo '<div class="el_drag el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'">'.substr($key, strlen($get_type)+1).$hint.'</div>';
        }
        
    }          
?>
                </td>
            </tr>
            <tr>
                <td class="box top">
                <span>TOP</span>
<?php
    $get_type = 'top';
    
    foreach($this->widgets as $key=>$val)
    {
        if(substr($key, 0, strlen($get_type)) == $get_type)
        {
            /* hint */
            $hint='';
            $widget_title = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-title');
            $widget_image = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-preview-image');
            if(!empty($widget_title) || !empty($widget_image) ) {
                $hint .='<div class="widget-hint">'
                        . '<div class="title">'.lang_check($widget_title).'</div>';

                if(!empty($widget_image) && file_exists(FCPATH."templates/".$settings["template"].$widget_image))
                        $hint.='<div class="widget-preview">'
                                 . '<img src="'.base_url("/templates/".$settings["template"].$widget_image).'" alt="" />'
                             . '</div>';

                $hint .= '</div>';
            }
            /* end hint */
            
            echo '<div class="el_drag el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'">'.substr($key, strlen($get_type)+1).$hint.'</div>';
        }
        
    }          
?>
                </td>
            </tr>
            <tr>
                <td class="box center">
                <span>CENTER</span>
<?php
    $get_type = 'center';
    
    foreach($this->widgets as $key=>$val)
    {
        if(substr($key, 0, strlen($get_type)) == $get_type)
        {
            
            $hint='';
            $widget_title = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-title');
            $widget_image = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-preview-image');
            if(!empty($widget_title) || !empty($widget_image) ) {
                $hint .='<div class="widget-hint">'
                        . '<div class="title">'.lang_check($widget_title).'</div>';

                if(!empty($widget_image) && file_exists(FCPATH."templates/".$settings["template"].$widget_image))
                        $hint.='<div class="widget-preview">'
                                 . '<img src="'.base_url("/templates/".$settings["template"].$widget_image).'" alt="" />'
                             . '</div>';

                $hint .= '</div>';
            }
            
            echo '<div class="el_drag el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'">'.substr($key, strlen($get_type)+1).$hint.'</div>';
        }
        
    }          
?>
                </td>
            </tr>
            <tr>
                <td class="box right"><span><?php echo lang_check('Side');?></span>
<?php
    $get_type = 'right';
    
    foreach($this->widgets as $key=>$val)
    {
        if(substr($key, 0, strlen($get_type)) == $get_type)
        {
            
            /* hint */
            $hint='';
            $widget_title = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-title');
            $widget_image = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-preview-image');
            if(!empty($widget_title) || !empty($widget_image) ) {
                $hint .='<div class="widget-hint">'
                        . '<div class="title">'.lang_check($widget_title).'</div>';

                if(!empty($widget_image) && file_exists(FCPATH."templates/".$settings["template"].$widget_image))
                        $hint.='<div class="widget-preview">'
                                 . '<img src="'.base_url("/templates/".$settings["template"].$widget_image).'" alt="" />'
                             . '</div>';

                $hint .= '</div>';
            }
            /* end hint */
            
            echo '<div class="el_drag el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'">'.substr($key, strlen($get_type)+1).$hint.'</div>';
        }
        
    }          
?>
                </td>
            </tr>
            <tr>
                <td class="box bottom"><span>BOTTOM</span>
<?php
    $get_type = 'bottom';
    
    foreach($this->widgets as $key=>$val)
    {
        if(substr($key, 0, strlen($get_type)) == $get_type)
        {
            /* hint */
            $hint='';
            $widget_title = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-title');
            $widget_image = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-preview-image');
            if(!empty($widget_title) || !empty($widget_image) ) {
                $hint .='<div class="widget-hint">'
                        . '<div class="title">'.lang_check($widget_title).'</div>';

                if(!empty($widget_image) && file_exists(FCPATH."templates/".$settings["template"].$widget_image))
                        $hint.='<div class="widget-preview">'
                                 . '<img src="'.base_url("/templates/".$settings["template"].$widget_image).'" alt="" />'
                             . '</div>';

                $hint .= '</div>';
            }
            /* end hint */
            
            echo '<div class="el_drag el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'">'.substr($key, strlen($get_type)+1).$hint.'</div>';
        }
        
    }          
?>
                </td>
            </tr>
            <tr>
                <td class="box footer"><span>FOOTER</span>
<?php
    $get_type = 'footer';
    
    foreach($this->widgets as $key=>$val)
    {
        if(substr($key, 0, strlen($get_type)) == $get_type)
        {
            /* hint */
            $hint='';
            $widget_title = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-title');
            $widget_image = get_widget_params("templates/".$settings["template"]."/widgets/".$key.".php", 'Widget-preview-image');
            if(!empty($widget_title) || !empty($widget_image) ) {
                $hint .='<div class="widget-hint">'
                        . '<div class="title">'.lang_check($widget_title).'</div>';

                if(!empty($widget_image) && file_exists(FCPATH."templates/".$settings["template"].$widget_image))
                        $hint.='<div class="widget-preview">'
                                 . '<img src="'.base_url("/templates/".$settings["template"].$widget_image).'" alt="" />'
                             . '</div>';

                $hint .= '</div>';
            }
            /* end hint */
            
            echo '<div class="el_drag el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'">'.substr($key, strlen($get_type)+1).$hint.'</div>';
        }
        
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
                <td class="box header" colspan="2"><span>HEADER</span>
<?php
$obj_widgets = json_decode($widgets_value_json);

if(isset($obj_widgets->header) && is_array($obj_widgets->header))
foreach($obj_widgets->header as $key)
{
    $exp = explode('_', $key);
    $get_type = $exp[0];
    
    echo '<div class="el_sort el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'" style="width:100%;">'.substr($key, strlen($get_type)+1).
         '<a href="'.site_url('admin/templatefiles/edit/'.$key.'.php/widgets').'" target="_blank" class="btn btn-success btn-xs"><i class="icon-edit"></i></a>'.
         '<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button></div>';
}
?>
                </td>
            </tr>
            <tr>
                <td class="box top" colspan="2"><span>TOP</span>
<?php
$obj_widgets = json_decode($widgets_value_json);

if(isset($obj_widgets->top) && is_array($obj_widgets->top))
foreach($obj_widgets->top as $key)
{
    $exp = explode('_', $key);
    $get_type = $exp[0];
    
    echo '<div class="el_sort el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'" style="width:100%;">'.substr($key, strlen($get_type)+1).
         '<a href="'.site_url('admin/templatefiles/edit/'.$key.'.php/widgets').'" target="_blank" class="btn btn-success btn-xs"><i class="icon-edit"></i></a>'.
         '<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button></div>';
}
?>
                </td>
            </tr>
            <tr>
                <td class="box center" style="width: 60%;"><span>CENTER</span>
<?php
$obj_widgets = json_decode($widgets_value_json);

if(isset($obj_widgets->center) && is_array($obj_widgets->center))
foreach($obj_widgets->center as $key)
{
    $exp = explode('_', $key);
    $get_type = $exp[0];
    
    echo '<div class="el_sort el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'" style="width:100%;">'.substr($key, strlen($get_type)+1).
         '<a href="'.site_url('admin/templatefiles/edit/'.$key.'.php/widgets').'" target="_blank" class="btn btn-success btn-xs"><i class="icon-edit"></i></a>'.
         '<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button></div>';
}
?>
                </td>
                <td class="box right" style="width: 40%;"><span><?php echo lang_check('Side');?></span>
<?php
$obj_widgets = json_decode($widgets_value_json);

if(isset($obj_widgets->right) && is_array($obj_widgets->right))
foreach($obj_widgets->right as $key)
{
    $exp = explode('_', $key);
    $get_type = $exp[0];
    
    echo '<div class="el_sort el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'" style="width:100%;">'.substr($key, strlen($get_type)+1).
         '<a href="'.site_url('admin/templatefiles/edit/'.$key.'.php/widgets').'" target="_blank" class="btn btn-success btn-xs"><i class="icon-edit"></i></a>'.
         '<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button></div>';
}
?>
                </td>
            </tr>
            <tr>
                <td class="box bottom" colspan="2"><span>BOTTOM</span>
<?php
$obj_widgets = json_decode($widgets_value_json);

if(isset($obj_widgets->bottom) && is_array($obj_widgets->bottom))
foreach($obj_widgets->bottom as $key)
{
    $exp = explode('_', $key);
    $get_type = $exp[0];
    
    echo '<div class="el_sort el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'" style="width:100%;">'.substr($key, strlen($get_type)+1).
         '<a href="'.site_url('admin/templatefiles/edit/'.$key.'.php/widgets').'" target="_blank" class="btn btn-success btn-xs"><i class="icon-edit"></i></a>'.
         '<button type="button" class="btn btn-danger btn-xs"><i class="icon-remove"></i></button></div>';
}
?>
                </td>
            </tr>
            <tr>
                <td class="box footer" colspan="2"><span>FOOTER</span>
<?php
$obj_widgets = json_decode($widgets_value_json);

if(isset($obj_widgets->footer) && is_array($obj_widgets->footer))
foreach($obj_widgets->footer as $key)
{
    $exp = explode('_', $key);
    $get_type = $exp[0];
    
    echo '<div class="el_sort el_style '.$get_type.'" r_type="'.$get_type.'" rel="'.$key.'" style="width:100%;">'.substr($key, strlen($get_type)+1).
         '<a href="'.site_url('admin/templatefiles/edit/'.$key.'.php/widgets').'" target="_blank" class="btn btn-success btn-xs"><i class="icon-edit"></i></a>'.
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

<script>

$(function() {
    
    $('#widgets_order_json').val('<?php echo $widgets_value_json; ?>');
    
    <?php $widget_positions = array('header', 'top', 'center', 'right', 'bottom', 'footer');
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
        new_el.append('<a href="<?php echo site_url('admin/templatefiles/edit'); ?>/'+new_el.attr('rel')+'.php/widgets" target="_blank" class="btn btn-success btn-xs"><i class="icon-edit"></i></a>');
        
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
});


function save_json_changes()
{
    var js_gen = '{ ';
    <?php foreach($widget_positions as $position_box): ?>
    js_gen+= ' "<?php echo $position_box; ?>": [  ';
    
    jQuery.each($(".drop_visual_container .box.<?php echo $position_box; ?> div"), function( i, val ) {
       if($(this).attr('rel'))
        js_gen+= '"'+$(this).attr('rel')+'", ';
    });
    
    js_gen = js_gen.slice(0,-2);
        
    js_gen+= ' ],';
    <?php endforeach; ?>
    js_gen = js_gen.slice(0,-1);
    js_gen+= ' }';
    
    $('#widgets_order_json').val(js_gen);
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

<style type="text/css">
    
    /* [START] widget-hint */
    
    .el_style  {
        position: relative;
    }
    
    .widget-hint {
        position: absolute !important;
        width: 200px;
        background: white;
        bottom: 100%;
        left: 50%;
        margin-left: -100px;
        border: 1px solid #CCCCCC;
        color: black;
        text-align: left;
        padding: 5px;
        display: none;
        margin-bottom: 9px;
        -webkit-box-shadow: 0px 0px 5.75px 0.25px rgba(0, 0, 0, 0.25) !important;
        -moz-box-shadow: 0px 0px 5.75px 0.25px rgba(0, 0, 0, 0.25) !important;
        -o-box-shadow: 0px 0px 5.75px 0.25px rgba(0, 0, 0, 0.25) !important;
        box-shadow: 0px 0px 5.75px 0.25px rgba(0, 0, 0, 0.25) !important;
    }
    
    .widget-hint:hover {
        display: none;
    }
    
    .el_style:hover .widget-hint {
        display: block;
    }
    
    .widget-hint .widget-preview {
        width: 100%;
        margin-top: 5px;
    }
    
    .widget-hint .title {
        font-weight: 600;
    }
    
    .widget-hint .widget-preview img {    
        max-width: 100%;
    }
    
    .el_style:hover .widget-hint:after {
        display: block;
    }
    
    .widget-hint:after {
        content: '';
        display: none;
        position: absolute;
        width: 0px;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 13px solid rgb(220, 220, 220);
        transform: translateX(-50%);
        -webkit-transform: translateX(-50%);
        left: 50%;
        bottom: -13px;
        border-radius: 5px;
    }
    
    /* [/END] widget-hint */
</style>

<?php

//if(!function_exists('get_widget_params')){
    function get_widget_params($file = NULL, $key='') {
        if($file== NULL || empty($key)) return false;
        $file= FCPATH.$file;
        
	if ( file_exists( $file ) && is_file( $file ) ) {
            if ( preg_match( '|'.$key.':(.*)$|mi', file_get_contents($file), $name )) {
                return trim($name[1]);
            }
	}
        return false;
    }
    
//}





?>