<?php
/*
Widget-title: Slider
Widget-preview-image: /assets/img/widgets_preview/top_slider.webp
 */
?>
<section class="main-banner-sec widget_edit_enabled">
    <div class="banner-carousel">
        <?php foreach($slideshow_images as $key=>$file): ?>
        <div class="banner-slide">
            <img src="<?php echo _simg($file['url'], '1800x600', true); ?>" alt="<?php echo basename($file['url']);?>">
            <?php if(config_item('property_slider_enabled')===TRUE&&!empty($file['property_details'])):?>
            <?php
            $listing_id = $file['property_details']['property_id'];
            $CI = &get_instance();
            $CI -> load->model('estate_m');
            $listing = $CI->estate_m->get_dynamic_array($listing_id);
            
            ?>
            <div class="banner_text">
                <div class="rate-info">
                    <span><?php echo _ch($listing['option4_'.$lang_id], ''); ?></span>
                    <h5>
                    <?php if(!empty($listing['option36_'.$lang_id]) || !empty($listing['option37_'.$lang_id])): ?>
                        <?php if(_ch($listing['option4_'.$lang_id], false) && stripos($listing['option4_'.$lang_id], lang_check('Rent'))!==FAlSE):?>
                            <?php 
                                if(!empty($listing['option37_'.$lang_id]))echo ' '.$options_prefix_37.price_format($listing['option37_'.$lang_id], $lang_id).$options_suffix_37;
                                if(!empty($listing['option36_'.$lang_id]))echo $options_prefix_36.price_format($listing['option36_'.$lang_id], $lang_id).$options_suffix_36;
                            ?>
                        <?php else:?>
                            <?php 
                                if(!empty($listing['option36_'.$lang_id]))echo $options_prefix_36.price_format($listing['option36_'.$lang_id], $lang_id).$options_suffix_36;
                                if(!empty($listing['option37_'.$lang_id]))echo ' '.$options_prefix_37.price_format($listing['option37_'.$lang_id], $lang_id).$options_suffix_37;
                            ?>
                        <?php endif;?>
                    <?php endif; ?>
                    </h5>
                </div>
                <div class="card">
                    <div class="card-body">
                        <a href="<?php _che($file['link']);?>">
                            <h3><?php _che($file['property_details']['title']);?></h3>
                            <p> <i class="la la-map-marker"></i><?php echo _ch($listing['address']);?></p>
                        </a>
                        <ul>
                            <li><?php echo _ch($listing['option20_'.$lang_id]);?> <?php echo _ch($options_name_20);?></li>
                            <li><?php echo _ch($listing['option19_'.$lang_id]);?> <?php echo _ch($options_name_19);?></li>
                            <li><?php echo _ch($listing['option57_'.$lang_id]);?> <?php echo _ch($options_name_57);?></li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="<?php _che($file['link']);?>" title=""><?php echo lang_check('Read More');?> <i class="la la-arrow-right"></i></a>
                    </div>
                </div>
            </div><!--banner_text end-->
            <?php else: ?>
                <?php if(!empty($file['title']) || !empty($file['description'])): ?>
                <div class="banner_text">
                    <?php if(!empty($file['title'])): ?>
                    <div class="rate-info">
                        <h5>
                            <?php if(!empty($file['link'])):?>
                                <a style="color: inherit;" href="<?php _che($file['link']);?>" title="<?php _che($file['title']);?>"> <?php _che($file['title']);?></a>
                            <?php else: ?>
                                <?php _che($file['title']);?>
                            <?php endif;?>
                        </h5>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($file['description'])):?>
                    <div class="card">
                        <div class="card-body">
                            <?php _che($file['description']);?>
                        </div>
                        <div class="card-footer">
                            <?php if(!empty($file['link'])):?>
                                <a href="<?php _che($file['link']);?>" title=""><?php echo lang_check('Read More');?>  <i class="la la-arrow-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div><!--banner_text end-->
                <?php endif; ?>
            <?php endif; ?>
        </div><!--banner-slide end-->
        <?php endforeach;?>
    </div><!--banner-carousel end-->
</section><!--main-banner-sec end-->