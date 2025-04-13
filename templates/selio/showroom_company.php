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
                        <div class="row">
                            <div class="col-lg-8">
                                
                                <?php if(!empty($page_body)):?>
                                <div class="blog-single-post single">
                                    <h3>{page_title}</h3>
                                    <div class="blog-img">
                                    <?php if(isset($page_images) && !empty($page_images)):?>
                                        <img src="<?php _che($page_images[1]->url);?>" class="wp-post-image" alt="{page_title}" />
                                    <?php endif;?>
                                    </div>
                                    {page_body}
                                    <?php _widget('center_imagegallery');?>
                                    {has_page_documents}
                                    <h2>{lang_Filerepository}</h2>
                                    <ul>
                                    {page_documents}
                                    <li>
                                        <a href="{url}">{filename}</a>
                                    </li>
                                    {/page_documents}
                                    </ul>
                                    {/has_page_documents}
                                    <div class="post-share">
                                        <ul class="social-links">
                                            <li><a href="https://www.facebook.com/share.php?u={page_current_url}&amp;title={page_title}" title=""><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="https://twitter.com/intent/tweet?text={page_current_url}" title=""><i class="fa fa-twitter"></i></a></li>
                                            <li><a href="#" title=""><i class="fa fa-instagram"></i></a></li>
                                            <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={page_current_url}&title{page_title}=&summary=&source=" title=""><i class="fa fa-linkedin"></i></a></li>
                                        </ul>
                                    </div><!--post-share end-->
                                </div>
                                <div class="blog-single-post single">
                                    <h3>{page_title}</h3>
                                    <div id="location-map" style="height: 400px;" class="sh_map">
                                    </div>
                                    <div class="post-line">
                                    </div><!--post-share end-->
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="col-lg-4">
                                <div class="sidebar layout2">
                                    <div class="widget widget-catgs">
                                        <h3 class="widget-title">{lang_Overview}</h3>
                                        <div class="block">
                                            <p class="bottom-border"><strong>
                                            {lang_Company}
                                            </strong> <span>{page_title}</span>
                                            <br style="clear: both;" />
                                            </p>
                                            <p class="bottom-border"><strong>
                                            {lang_Address}
                                            </strong> <span>{showroom_data_address}</span>
                                            <br style="clear: both;" />
                                            </p>
                                            <p class="bottom-border"><strong>
                                            {lang_Keywords}
                                            </strong> <span>{page_keywords}</span>
                                            <br style="clear: both;" />
                                            </p>
                                        </div>
                                    </div><!--widget-catgs end-->
                                    <?php _widget('right_categories');?>
                                    <?php _widget('right_latest_listings');?>
                                    <?php _widget('right_ads');?>
                                </div><!--sidebar end-->
                            </div>
                        </div>
                    </div><!--listing-main-sec-details end-->
                </div>    
            </section><!--listing-main-sec end-->
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'default')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
        <?php

        /* dinamic per listing */
        _generate_js('_generate_showroom_company_js_'.md5(current_url_q()), 'widgets/_generate_showroom_company_js.php', false);
        ?>
    </body>
</html>

