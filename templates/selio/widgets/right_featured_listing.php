<?php
/*
Widget-title: Featured listing
Widget-preview-image: /assets/img/widgets_preview/right_featured_listing.webp
 */
?>
 <?php
 $CI = &get_instance();
 $CI->load->model('estate_m');
 $CI->load->model('option_m');

$last_n = 1;

$top_n_estates = $this->estate_m->get_by(array('is_activated' => 1,'is_featured' => 1, 'language_id'=>$lang_id), FALSE, $last_n, 'RAND()');
$options_name = $this->option_m->get_lang(NULL, FALSE, $lang_id);

$top_estates_num = $last_n;
$top_estates = array();
$CI->generate_results_array($top_n_estates, $top_estates, $options_name); 
 
?>


<?php if(sw_count($top_estates)):?>
<div class="widget widget-featured-property widget_edit_enabled">
    <h3 class="widget-title"><?php echo lang_check('Featured Property');?></h3>
    <?php foreach($top_estates as $key=>$item): ?>
        <div class="card">
             <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
                 <div class="img-block">
                     <div class="overlay"></div>
                     <img src="<?php echo _simg($item['thumbnail_url'], '851x678', true); ?>" alt="<?php echo _ch($item['option_10']); ?>" class="img-fluid">
                     <div class="rate-info">
                         <?php if(!empty($item['option_36']) || !empty($item['option_37'])): ?>
                         <h5>
                         <?php 
                            if(!empty($item['option_37']))echo ' '.show_price($item['option_37'], $options_prefix_37, $options_suffix_37, $lang_id);
                            if(!empty($item['option_37']) && !empty($item['option_36'])) echo ' / ';
                            if(!empty($item['option_36']))echo show_price($item['option_36'], $options_prefix_36, $options_suffix_36, $lang_id);
                         ?>
                         </h5>
                         <?php endif; ?>
                         <span><?php echo _ch($item['option_4'], ''); ?></span>
                     </div>
                 </div>
             </a>
             <div class="card-body">
                 <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
                     <h3><?php echo _ch($item['option_10']); ?></h3>
                     <p><i class="la la-map-marker"></i><?php _che($item['address']); ?></p>
                 </a>
             </div>
             <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>" class="ext-link"></a>
         </div>
    <?php endforeach;?>
</div><!--widget-featured-property end-->
<?php endif;?>