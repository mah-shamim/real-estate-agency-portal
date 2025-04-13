<?php
$icon=true;
if(isset($icons) && $icons==false) {
    $icon=false;
}

/* view counter */
$counter=true;
if(isset($view_counter) && $view_counter==false) {
    $counter=false;
}

/* column */
$col_sm = '3';
if(isset($columns) && $columns == 3)
{
    $col_sm = '4';
}
?>


<li class="span<?php echo $col_sm;?> li-grid">
    <div class="thumbnail f_<?php echo _ch($item['is_featured']); ?>">
      <h3><?php echo _ch($item['option_10']); ?>&nbsp;</h3>
      <img alt="300x200" data-src="holder.js/300x200" src="<?php echo _simg($item['thumbnail_url'], '300x200'); ?>"/>
      <?php if(!empty($item['option_38'])):?>
      <div class="badget"><img src="<?php echo _ch($item['badget']); ?>" alt="<?php echo _ch($item['option_38']); ?>"/></div>
      <?php endif; ?>
      <?php if(!empty($item['option_4'])):?>
      <div class="purpose-badget fea_<?php echo _ch($item['is_featured']); ?>"><?php echo _ch($item['option_4']); ?></div>
      <?php endif; ?>
      <?php if(!empty($item['option_54'])):?>
      <div class="ownership-badget fea_<?php echo _ch($item['is_featured']); ?>"><?php echo _ch($item['option_54']); ?></div>
      <?php endif;?>
      <img class="featured-icon" alt="Featured" src="assets/img/featured-icon.png" />
      <a href="<?php echo _ch($item['url']); ?>" class="over-image"> </a>
      <div class="caption">
        <p class="bottom-border"><strong class="f_<?php echo _ch($item['is_featured']); ?>"><?php echo _ch($item['address']); ?></strong></p>
        <p class="bottom-border"><?php echo _ch($options_name_2); ?> <span><?php echo _ch($item['option_2']); ?></span></p>
        <p class="bottom-border"><?php echo _ch($options_name_3); ?> <span><?php echo _ch($item['option_3']); ?></span></p>
        <p class="bottom-border"><?php echo _ch($options_name_19); ?> <span><?php echo _ch($item['option_19']); ?></span></p>
        <?php if(!empty($item['icons'])):?>
        <p class="prop-icons">
            <?php 
                foreach ($item['icons'] as $icon) {
                    echo $icon['icon'];
                }
            ?>
        </p>
        <?php endif;?>
        
        <p class="prop-description"><i><?php echo _ch($item['option_chlimit_8']); ?></i></p>
        <p class="prop-details">


        <?php if(!empty($item['option_36'])):?>
        <span class="price"><?php _che($options_prefix_36); ?> <?php echo number_format((int)_ch($item['option_36'], ''), 2, ',', '.'); ?></span>
        <?php endif;?>

        <?php if(!empty($item['option_37'])):?>
        <span class="price"><?php _che($options_prefix_37); ?> <?php echo number_format((int)_ch($item['option_37'], ''), 2, ',', '.'); ?>&nbsp;</span>
        <?php endif;?>

        <?php if(!empty($counter)): ?>
        <span class="res_counter">{lang_ViewsCounter}: <?php echo _ch($item['counter_views']); ?></span>
        <?php endif;?>
        
        <a class="btn btn-info" href="<?php echo _ch($item['url']); ?>">
        {lang_Details}
        </a>
        </p>
      </div>
    </div>
  </li>