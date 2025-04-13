
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php _widget('head'); ?>
</head>
<body>
    <div class="wrapper">
        <header>
            <?php _widget('header_bar'); ?>
            <?php _widget('header_main_panel'); ?>
        </header><!--header end-->
            <?php _widget('top_title'); ?>
            <section class="listing-main-sec section-padding2">
                <div class="container">
                    <div class="listing-main-sec-details">
                        <div class="treefield_sitemap">
                            <?php if(file_exists(APPPATH.'controllers/admin/treefield.php')):?>
                            <h2><?php echo lang_check('Neighborhood Sitemap');?></h2>
                            <?php echo treefield_sitemap(64, $lang_id, 'ul'); ?>
                            <br/>
                            <?php endif;?>
                            <h2>  <?php echo lang_check('Website sitemap');?> </h2>
                            <?php echo website_sitemap($lang_id, 'ul'); ?>
                        </div>
                        <div class="post-share">
                            <ul class="social-links">
                                <li><a href="https://www.facebook.com/share.php?u={page_current_url}&amp;title={page_title}" title=""><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://twitter.com/intent/tweet?text={page_current_url}" title=""><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#" title=""><i class="fa fa-instagram"></i></a></li>
                                <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={page_current_url}&title{page_title}=&summary=&source=" title=""><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div><!--post-share end-->
                        <?php _widget('center_image_gallery');?>
                    </div>
                </div>
            </section>
        
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'default')); ?>
    </div><!--wrapper end-->
    <?php _widget('custom_javascript'); ?>
</body>

</html>