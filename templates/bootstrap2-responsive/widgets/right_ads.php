<?php if(file_exists(APPPATH.'controllers/admin/ads.php')):?>
    {has_ads_160x600px}
    <h2>{lang_Ads}</h2>
    <div class="sidebar-ads-1">
        <a href="{random_ads_160x600px_link}" target="_blank"><img src="{random_ads_160x600px_image}"  alt=""/></a>
    </div>
    {/has_ads_160x600px}
<?php endif;?>