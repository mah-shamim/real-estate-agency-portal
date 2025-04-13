<?php
/*
Widget-title: Right ads
Widget-preview-image: /assets/img/widgets_preview/right_ads.webp
 */
?>
<?php if(config_item('show_placeholders_enabled') === TRUE):?>
    <img src="assets/img/<?php echo (sw_is_safari()) ? 'ads.jpg' : 'ads.webp';?>" alt='ads'/>
<?php else:?>
    <?php if(file_exists(APPPATH.'controllers/admin/ads.php')):?>
      {has_ads_160x600px}
          <a href="{random_ads_160x600px_link}" target="_blank" class="widget_edit_enabled"><img src="{random_ads_160x600px_image}" /></a>
      {/has_ads_160x600px}
    <?php elseif(!empty($settings_adsense160_600)): ?>
      <?php echo $settings_adsense160_600; ?>
    <?php endif;?>
<?php endif;?>