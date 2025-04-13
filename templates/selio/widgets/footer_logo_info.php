<?php
/*
Widget-title: footer logo info
Widget-preview-image: /assets/img/widgets_preview/footer_logo_info.webp
 */
?>

<div class="col-xl-3 col-sm-6 col-md-4 widget_edit_enabled">
    <div class="bottom-logo">
        <?php if(!empty($website_logo_url) && stripos($website_logo_url, 'assets/img/logo.png') === FALSE):?>
            <img src="<?php echo $website_logo_url; ?>" alt="{settings_websitetitle}" class="img-fluid">
        <?php elseif(false):?>
            <?php
            $first_w =  strtok($settings_websitetitle, " "); // Test
            ?>
            <b class="text-color-primary"> <?php echo $first_w;?></b>
            <?php echo str_replace($first_w, '', $settings_websitetitle);?>
        <?php else:?>
            <img src="<?php echo $website_logo_url; ?>" alt="{settings_websitetitle}" class="img-fluid">
        <?php endif;?>
        <div class="content">       
            <p class="description"><?php echo lang_check('footer_description');?></p>  
        </div> 
    </div>
</div>