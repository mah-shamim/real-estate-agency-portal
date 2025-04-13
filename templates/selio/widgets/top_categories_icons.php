<?php
/*
Widget-title: Categories icons
Widget-preview-image: /assets/img/widgets_preview/top_categories_icons.webp
 */
?>
<?php
$treefields = generate_treefields_list(79,'level_0');

$defaul_icons = array('la la-home','la la-hand-pointer-o','la la-unlock','la la-star-o');

if(empty($treefields)) {
    echo '<div class="container"><p class="alert alert-info">'.lang_check("Any categories are missing, please check Categories list, #widget top_categories_icons").'</p></div><br/>';
    return  false;
}
?>

<section class="categories-sec section-padding widget_edit_enabled">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-heading">
                    <span><?php echo lang_check('Categories');?></span>
                    <h3><?php echo lang_check('What you looking for?');?></h3>
                </div>
            </div>
        </div>
        <div class="categories-details">
            <div class="row">
                <?php $i=0;  foreach ($treefields as $key=>$item): ?>
                <?php if ($i>3) break; ?>
                <?php 
                    if(!isset($defaul_icons[$key])){
                        $defaul_icons[$key] = 'la la-home';
                    }
                ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-6 full">
                    <div class="categories-info">
                        <a href='<?php _che($item['url']);?>' title="<?php _che($item['title']);?>">
                            <div class="catg-icon">
                                <i class="<?php _che($item['font_icon_code'], $defaul_icons[$key]);?>"></i>
                            </div>
                        </a>
                        <h3><a href='<?php _che($item['url']);?>' title="<?php _che($item['title']);?>"><?php _che($item['title']);?></a></h3>
                        <a href='<?php _che($item['url']);?>' title="<?php _che($item['title']);?>" class="ext-link"></a>
                    </div><!--categories-info end-->
                </div>
                <?php $i++; endforeach; ?>

            </div>
        </div><!--categories-details end-->
    </div>
</section>