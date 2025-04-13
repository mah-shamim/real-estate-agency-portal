<?php if(file_exists(APPPATH.'controllers/propertycompare.php')):?>
<?php
//for php 5.3
$session_compare=$this->session->userdata('property_compare');
?>
<div class="widget widget-posts widget-compare">
    <h3 class="widget-title"><?php echo lang_check('Compare');?></h3>
    <div class="clearfix text-left">
        <a class="btn2" id='add_to_compare' style="<?php echo (empty($session_compare) || !isset($session_compare[$estate_data_id]))?'':'display:none;'; ?>" href='#'> <?php echo lang_check('Add to comparison list');?> </a>
        <a class="btn btn-success" id='remove_from_compare' style="<?php echo (!empty($session_compare)&&isset($session_compare[$estate_data_id]))?'':'display:none;'; ?>" href='#'> <?php echo lang_check('Remove from comparison list');?> </a>
    </div>
    <div class="compare-content">
        <ul class='compare-list'>
            <?php if(!empty($session_compare)&&sw_count($session_compare)>0):?>
            <?php foreach ($session_compare as $key => $value):?>
                <li data-id="<?php _che($key);?>"> 
                    <a href="<?php echo slug_url($listing_uri.'/'.$key.'/'.$lang_code.'/'.url_title_cro($value));?>"> <?php _che($key);?>, <?php _che($value);?></a>
                </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <a class="btn2" style="<?php echo (!empty($session_compare)&&sw_count($session_compare)>1)?'':'display:none;'; ?>" href='<?php echo site_url('/propertycompare/'.$estate_data_id.'/'.$lang_code); ?>'> <?php echo lang_check('Compare listings');?> </a>
    </div>
</div><!--widget-posts end-->

<?php

/* dinamic per listing */
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_property_compare_js.php');
?>
<?php endif;?>
