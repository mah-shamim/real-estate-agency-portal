<h2>{lang_Results}: <?php echo $total_rows; ?></h2>
<div class="options">
    <a class="view-type {view_grid_selected}" data-ref="grid" href="#"><img src="assets/img/glyphicons/glyphicons_156_show_thumbnails.png" alt="" /></a>
    <a class="view-type {view_list_selected}" data-ref="list" href="#"><img src="assets/img/glyphicons/glyphicons_157_show_thumbnails_with_lines.png" alt="" /></a>

    <select class="span3 selectpicker-small pull-right">
        <option value="id ASC" {order_dateASC_selected}>{lang_DateASC}</option>
        <option value="id DESC" {order_dateDESC_selected}>{lang_DateDESC}</option>
        <option value="price ASC" {order_priceASC_selected}>{lang_PriceASC}</option>
        <option value="price DESC" {order_priceDESC_selected}>{lang_PriceDESC}</option>
    </select>
    <span class="pull-right" style="padding-top: 5px;">{lang_OrderBy}&nbsp;&nbsp;&nbsp;</span>
</div>
<br style="clear:both;" />

<div class="row-fluid">
    
    {has_no_results}
    <ul class="thumbnails">
    <li class="span12">
    <div class="alert alert-success">
    {lang_Noestates}
    </div>
    </li>
    </ul>
    {/has_no_results}
    {has_view_grid}
        <?php foreach($results as $key=>$item): ?>
        <?php
           if($key==0)echo '<ul class="thumbnails">';
        ?>
            <?php _generate_results_item(array('key'=>$key, 'item'=>$item)); ?>
        <?php
           if( ($key+1)%4==0 )
            {
                echo '</ul><ul class="thumbnails">';
            }
            if( ($key+1)==sw_count($results) ) echo '</ul>';
            endforeach;
        ?>
    {/has_view_grid}
    {has_view_list}
    <ul class="thumbnails">
    <?php foreach($results as $key=>$item): ?>
      <li class="span12 li-list">
        <div class="thumbnail span4 f_<?php echo _ch($item['is_featured']); ?>">
           <h3><?php echo _ch($item['option_10']); ?>&nbsp;</h3>
           <img alt="" data-src=""  style="width: 300px; height: 200px;"  src="<?php echo _simg($item['thumbnail_url'], '260x191'); ?>" />
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
        </div>
          <div class="caption span8">
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
            <p class="prop-button-container">
            <a class="btn btn-info" href="<?php echo _ch($item['url']); ?>">
            {lang_Details}
            </a>

            <?php if(!empty($item['option_36'])):?>
            <span class="price"><?php echo _ch($options_prefix_36); ?> <?php echo _ch($item['option_36']); ?><?php echo _ch($options_suffix_36, ''); ?></span>
            <?php endif;?>

            <?php if(!empty($item['option_37'])):?>
            <span class="price"><?php echo _ch($options_prefix_37); ?> <?php echo _ch($item['option_37']); ?><?php echo _ch($options_suffix_37, ''); ?></span>
            <?php endif;?>

            <?php if(!empty($counter)): ?>
            <span class="res_counter">{lang_ViewsCounter}: <?php echo _ch($item['counter_views']); ?></span>
            <?php endif;?>
            </p>
          </div>
      </li>
    <?php endforeach;?>
    {/has_view_list}
    </ul>
  </div>
  <div class="pagination properties">
  {pagination_links}
  </div>



