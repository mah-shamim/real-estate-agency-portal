<?php
/*
Widget-title: Ads
Widget-preview-image: /assets/img/widgets_preview/right_ads.webp
 */
?>

<!-- RECENTLY VIEWED -->
<?php if(file_exists(APPPATH.'controllers/admin/ads.php')):?>
    {has_ads_180x150px}
    <div class="widget_edit_enabled">
          <a href="{random_ads_180x150px_link}" target="_blank"><img src="{random_ads_180x150px_image}" /></a>
    </div>
  {/has_ads_180x150px}
<?php elseif(!empty($settings_adsense160_600)): ?>
    <div class="widget_edit_enabled">
          <?php echo $settings_adsense160_600; ?>
    </div>
<?php endif; ?>
