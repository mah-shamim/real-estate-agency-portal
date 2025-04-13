<?php
/*
Widget-title: Latest listings
Widget-preview-image: /assets/img/widgets_preview/right_latest_listings.webp
 */
?>

 <?php
 $CI = &get_instance();
 $CI->load->model('estate_m');
 $CI->load->model('option_m');

$last_n = 3;
$top_n_listings = $this->estate_m->get_by(array('is_activated' => 1, 'language_id'=>$lang_id,'field_4' =>'Rent' ), FALSE, $last_n, 'RAND()');
$options_name = $this->option_m->get_lang(NULL, FALSE, $lang_id);

$top_listings = array();
$CI->generate_results_array($top_n_listings, $top_listings, $options_name); 
 
?>

<div class="widget widget-posts widget_edit_enabled">
    <h3 class="widget-title"><?php echo lang_check('Popular Listings');?></h3>
    <ul>
         <?php foreach($top_listings as $key=>$item): ?>
        <li>
            <div class="wd-posts">
                <div class="ps-img">
                    <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
                        <img src="<?php echo _simg($item['thumbnail_url'], '112x89', true); ?>" alt="<?php echo _ch($item['option_10']); ?>">
                    </a>
                </div><!--ps-img end-->
                <div class="ps-info">
                    <h3><a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>"><?php echo _ch($item['option_10']); ?></a></h3>
                    <strong>
                    <?php 
                        if(!empty($item['option_37']))echo ' '.show_price($item['option_37'], $options_prefix_37, $options_suffix_37, $lang_id);
                        
                        if(!empty($item['option_36']) && !empty($item['option_37'])) echo ' / ';
                        
                        if(!empty($item['option_36']))echo show_price($item['option_36'], $options_prefix_36, $options_suffix_36, $lang_id);
                    ?>
                    </strong>
                    <span><i class="la la-map-marker"></i><?php _che($item['address']); ?></span>
                </div><!--ps-info end-->
            </div><!--wd-posts end-->
        </li>
        <?php endforeach;?>
    </ul>
</div><!--widget-posts end-->

