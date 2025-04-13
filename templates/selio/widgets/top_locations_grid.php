<?php
/*
Widget-title: Locations grid
Widget-preview-image: /assets/img/widgets_preview/top_locations_grid.webp
 */
?>
<?php
$treefields = generate_treefields_list(64,'limit_3_level_0');
?>

<?php

if(sw_is_safari()) {
    $defaul_images = array('assets/images/cities/1.jpg',
                           'assets/images/cities/2.jpg',
                           'assets/images/cities/3.jpg',
                        );
} else {
    $defaul_images = array('assets/images/cities/1.webp',
                            'assets/images/cities/2.webp',
                            'assets/images/cities/3.webp',
                        );
}


if(empty($treefields)) {
    echo '<div class="container"><p class="alert alert-info">'.lang_check("Any location are missing, please check Location list, #widget top_locations_grid").'</p></div><br/>';
    return  false;
}
?>

<section class="popular-cities hp_s1 section-padding widget_edit_enabled">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-heading">
                    <span><?php echo lang_check('Popular Cities');?></span>
                    <h3><?php echo lang_check('Find Perfect Place');?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <?php $i=0;  foreach ($treefields as $key=>$item): ?>
            <?php if ($i>=3) break; ?>
                <div class="col-lg-4 col-md-6">
                    <a href='<?php _che($item['url']);?>'>
                        <div class="card">
                            <div class="overlay"></div>
                            <?php 
                                if(!isset($defaul_images[$key])){
                                    $defaul_images[$key] = 'assets/img/no_image.jpg';
                                }
                            ?>
                            <img src="<?php _che($item['thumbnail_url'], $defaul_images[$key]);?>" alt="<?php _che($item['title']);?>" class="img-fluid">
                            <div class="card-body">
                                <h4><?php _che($item['title']);?></h4>
                                <p>
                                    <?php if(_ch($item['count'], 0) == 1):?>
                                        <?php _che($item['count'], 0);?> <?php echo lang_check('Listing');?>                                                                    
                                    <?php else:?>
                                        <?php _che($item['count'], 0);?> <?php echo lang_check('Listings');?>                                                                    
                                    <?php endif;?>
                                </p>
                                <i class="fa fa-angle-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            <?php $i++; endforeach; ?>
        </div>
    </div>
</section>