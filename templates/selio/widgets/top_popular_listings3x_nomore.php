<?php
/*
Widget-title: Popular Listings without more btn
Widget-preview-image: /assets/img/widgets_preview/top_popular_listings.webp
 */
?>
<?php
 $CI = &get_instance();
 $CI->load->model('estate_m');
 $CI->load->model('option_m');

$last_n = 3;

$top_n_estates = $CI->estate_m->get_by(array('is_activated' => 1, 'language_id'=>$lang_id), FALSE, $last_n, 'counter_views DESC');
$options_name = $CI->option_m->get_lang(NULL, FALSE, $lang_id);

$top_estates_num = $last_n;
$top_estates = array();
$CI->generate_results_array($top_n_estates, $top_estates, $options_name); 
 
?>

<section class="popular-listing hp2 section-padding widget_edit_enabled">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-heading">
                    <span><?php echo lang_check('Discover');?></span>
                    <h3><?php echo lang_check('Popular Listing');?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach($top_estates as $key=>$item): ?>
                <?php _generate_results_item(array('key'=>$key, 'item'=>$item, 'custom_class'=>'col-lg-4 col-md-6')); ?>
            <?php endforeach;?>
        </div>
    </div>
</section>