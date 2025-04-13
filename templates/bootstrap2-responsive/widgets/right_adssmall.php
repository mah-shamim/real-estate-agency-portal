<?php if(file_exists(APPPATH.'controllers/admin/ads.php')):?>
  {has_ads_180x150px}
  <h2>{lang_Ads}</h2>
  <div class="sidebar-ads-1">
      <a href="{random_ads_180x150px_link}" target="_blank"><img src="{random_ads_180x150px_image}" alt="" /></a>
  </div>
  {/has_ads_180x150px}
<?php elseif(!empty($settings_adsense160_600)): ?>
    <h2 class="bottom-line  line-before main-heading">{lang_Ads}</h2>
    <div class="sidebar-ads-1 bottom-line  line-before " style="padding-bottom: 30px;">
       <?php echo $settings_adsense160_600; ?>
    </div>
<?php endif; ?>