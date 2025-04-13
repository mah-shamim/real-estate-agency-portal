<?php
    $sel_values = array(0,50,100,200,500);
    $suffix = lang_check('km');
    $curr_value=NULL;
    
    if(isset($_GET['search']))$search_json = json_decode($_GET['search']);
    if(isset($search_json->v_search_radius))
    {
        $curr_value=$search_json->v_search_radius;
    }
            
    
    $search_query = '';
    if(isset($search_json->v_search_option_smart))
    {
        $search_query=$search_json->v_search_option_smart;
    }
    
    $search_radius = '';
    if(isset($search_json->v_search_radius))
    {
        $search_radius=$search_json->v_search_radius;
    }
    $class_add = $field->class;
    
    if(function_exists('sw_filter_search_slidetoggle')) 
    sw_filter_search_slidetoggle();
?>

<div class="form_field <?php echo _ch($class_add, ''); ?>">
    <div class="form-group field_search_<?php echo _ch($f_id); ?>" style="<?php echo _ch($field->style, ''); ?>">
        <input id="search_option_smart" name="search_option_smart" value="<?php echo _ch($search_query,''); ?>" type="text" class="form-control" placeholder="<?php echo lang_check('What?');?>" />
    </div>
</div>
<?php if(true):?>
<div class="form_field <?php echo $class_add; ?> sf_input">
    <div class="form-group  field_search_search_radius" style="<?php _che($field->style); ?>">
        <div class="drop-menu">
            <div class="select">
                <?php if(!empty($search_radius)):?>
                    <span><?php echo $search_radius; ?> <?php echo lang_check('km');?></span>
                <?php else:?>
                    <span><?php echo lang_check('Distance');?></span>
                <?php endif;?>
                <i class="fa fa-angle-down"></i>
            </div>
            <input type="hidden" id="search_radius" name="search_radius"  value="<?php echo $search_radius; ?>" />
            <ul class="dropeddown">
                <li data-value="0">0 <?php echo lang_check('km');?></li>
                <li data-value="50">50 <?php echo lang_check('km');?></li>
                <li data-value="100">100 <?php echo lang_check('km');?></li>
                <li data-value="200">200 <?php echo lang_check('km');?></li>
            </ul>
        </div>
    </div><!-- /.form-group -->
</div>
<?php endif;?>


