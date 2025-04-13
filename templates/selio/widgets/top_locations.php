<?php
/*
Widget-title: Locations
Widget-preview-image: /assets/img/widgets_preview/top_locations.webp
 */
?>
<?php
$treefields = generate_treefields_list(64,'level=0&limit=5');
$defaul_icons = array('la la-home','la la-hand-pointer-o','la la-unlock','la la-star-o');

if(empty($treefields)) {
    echo '<div class="container"><p class="alert alert-info">'.lang_check("Any categories are missing, please check Categories list, #widget top_categories_icons").'</p></div><br/>';
    return  false;
}
?>

<section class="popular-cities hp7 section-popular-cities-flexbox widget_edit_enabled">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-heading">
                    <span><?php echo lang_check('Popular Cities');?></span>
                    <h3><?php echo lang_check('Find Perfect Place');?></h3>
                </div>
            </div>
        </div>
        <div class="row row-cities-flexbox">
            <?php if (isset($treefields[0])):?>
            <div class="col-lg-6">
                <?php $item=$treefields[0]; ?>
                <a href='<?php _che($item['url']);?>'>
                    <div class="card cities-flexbox-1">
                        <div class="overlay"></div>
                        <div class="overlay-stick"></div>
                        <img src="<?php echo (sw_is_safari()) ? _che($item['image_url'],'assets/images/resources/pl-img1.jpg') : _che($item['image_url'],'assets/images/resources/pl-img1.webp');?>" alt="<?php _che($item['title']); ?>" class="img-fluid">
                        <div class="card-body">
                            <h4><?php _che($item['title']); ?></h4>
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif;?>
            <div class="col-lg-6">
                <div class="row">
                    <?php if (isset($treefields[1])):?>
                    <div class="col-lg-12">
                        <?php $item=$treefields[1]; ?>
                        <a href='<?php _che($item['url']);?>'>
                            <div class="card cities-flexbox-2">
                                <div class="overlay"></div>
                                <div class="overlay-stick"></div>
                                <img src="<?php echo (sw_is_safari()) ? _che($item['image_url'],'assets/images/resources/pl-img2.jpg') : _che($item['image_url'],'assets/images/resources/pl-img2.webp');?>" alt="<?php _che($item['title']); ?>" class="img-fluid">
                                <div class="card-body">
                                    <h4><?php _che($item['title']); ?></h4>
                                    <i class="fa fa-angle-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif;?>
                    <?php if (isset($treefields[2])):?>
                    <div class="col-lg-6">
                        <?php $item=$treefields[2]; ?>
                        <a href='<?php _che($item['url']);?>'>
                            <div class="card cities-flexbox-3">
                                <div class="overlay"></div>
                                <div class="overlay-stick"></div>
                                <img src="<?php echo (sw_is_safari()) ? _che($item['image_url'],'assets/images/resources/pl-img3.jpg') : _che($item['image_url'],'assets/images/resources/pl-img3.webp');?>" alt="<?php _che($item['title']); ?>" class="img-fluid">
                                <div class="card-body">
                                    <h4><?php _che($item['title']); ?></h4>
                                    <i class="fa fa-angle-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif;?>
                    <?php if (isset($treefields[3])):?>
                    <div class="col-lg-6">
                        <?php $item=$treefields[3]; ?>
                        <a href='<?php _che($item['url']);?>'>
                            <div class="card cities-flexbox-4">
                                <div class="overlay"></div>
                                <div class="overlay-stick"></div>
                                <img src="<?php echo (sw_is_safari()) ? _che($item['image_url'],'assets/images/resources/pl-img4.jpg') : _che($item['image_url'],'assets/images/resources/pl-img4.webp');?>" alt="<?php _che($item['title']); ?>" class="img-fluid">
                                <div class="card-body">
                                    <h4><?php _che($item['title']); ?></h4>
                                    <i class="fa fa-angle-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</section>