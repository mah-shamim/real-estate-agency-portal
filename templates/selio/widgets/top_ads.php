<?php
/*
Widget-title: Ads
Widget-preview-image: /assets/img/widgets_preview/bottom_ads.jpg
*/
?>
<div class="widget">
    <div class="container text-center">
        <?php if(config_item('show_placeholders_enabled') === TRUE):?>
            <img src="assets/img/<?php echo (sw_is_safari()) ? '728x900.jpg' : '728x900.webp';?>" alt='ads'/>
        <?php else:?>
            <?php if(file_exists(APPPATH.'controllers/admin/ads.php')):?>
                {has_ads_728x90px}
                    <a href="{random_ads_728x90px_link}" target="_blank"><img src="{random_ads_728x90px_image}" alt='ads'/></a>
                {/has_ads_728x90px}
            <?php elseif(!empty($settings_adsense728_90)): ?>     
                <?php echo $settings_adsense728_90; ?>
            <?php endif;?>
        <?php endif;?>
    </div>
</div>