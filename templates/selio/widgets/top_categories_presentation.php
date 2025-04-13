<?php
/*
Widget-title: Categories presentation
Widget-preview-image: /assets/img/widgets_preview/top_categories_presentation.webp
 */
?>
<?php
$treefields = generate_treefields_list(79,'limit_3_level_0');

if(sw_is_safari()) {
    $defaul_images = array('assets/images/intro/thumb1.jpg',
                           'assets/images/intro/thumb2.jpg',
                           'assets/images/intro/thumb3.jpg',
                        );
} else {
    $defaul_images = array('assets/images/intro/thumb1.webp',
                           'assets/images/intro/thumb2.webp',
                           'assets/images/intro/thumb3.webp',
                        );
}

$href_btn = site_url($lang_code . '/' . get_results_page_id() . '/?search={"v_search_option_4":"Rent"}');
?>
<section class="intro section-padding section-categories-presentation widget_edit_enabled">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 pl-0">
                <div class="intro-content ">
                    <h3 class=""><?php echo lang_check('Homes around the world');?></h3>
                    <p class=""><?php echo lang_check('categories_presentation_description');?></p>
                    <a href='<?php echo $href_btn;?>' class="btn btn-outline-primary view-btn">
                        <i class="icon-arrow-right-circle"></i><?php echo lang_check('View for rent');?></a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 pr-0">
                <div class="intro-img">
                    <img src="assets/images/intro/<?php echo (sw_is_safari()) ? '1.jpg' : '1.webp';?>" alt="<?php echo lang_check('View for rent');?>" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="intro-thumb-row">
            <?php if(empty($treefields)):?>
                <p class="alert alert-info"><?php echo lang_check("Any categories are missing, please check Categories list, #widget top_categories_presentation");?></p>
            <?php else:?>
            <?php $i=0;  foreach ($treefields as $key=>$item): ?>
            <?php if ($i>=3) break; ?>
            <?php 
                if(!isset($defaul_images[$key])){
                    $defaul_images[$key] = 'assets/img/no_image.jpg';
                }
            ?>
            <a href='<?php _che($item['url']);?>' class="intro-thumb" title='<?php _che($item['title']);?>'>
                <img src="<?php _che($item['thumbnail_url'], $defaul_images[$key]);?>" alt="<?php _che($item['title']);?>">
                <h6 class=""><?php _che($item['title']);?></h6>
            </a>
            <?php $i++; endforeach; ?>
            <?php endif;?>
        </div>
    </div>
</section>