<!doctype html>
<html class="no-js" lang="en">
<head>
    <?php _widget('head');?>
</head>
<body>
    <div class="wrapper">
        <header>
            <?php _widget('header_bar');?>
            <?php _widget('header_main_panel');?>
        </header><!--header end-->
        <?php _widget('top_search_over_image');?>
        <section class="listing-main-sec section-padding2 pb15">
            <div class="container">
                <div class="listing-main-sec-details">
                    <div class="row">
                        <div class="col-lg-8">
                                <?php if(!empty($page_body)):?>
                                    <div class="blog-single-post single">
                                        <h3>{page_title}</h3>
                                        {page_body}
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
                                    <div class="post-line">
                                    </div><!--post-share end-->
                                </div>
                                <?php endif;?>
                            <?php _widget('center_results');?>
                        </div>
                        <div class="col-lg-4">
                            <div class="sidebar layout2">
                                <?php _widget('right_filterform');?>
                                <?php _widget('right_featured_listing');?>
                                <?php _widget('right_categories');?>
                                <?php _widget('right_agents');?>
                                <?php _widget('right_latest_listings');?>
                                <?php _widget('right_ads');?>
                            </div><!--sidebar end-->
                        </div>
                    </div>
                </div><!--listing-main-sec-details end-->
            </div>    
        </section><!--listing-main-sec end-->
        <?php _widget('top_partners'); ?>
        <?php _widget('top_discover_banner_html'); ?>
        <?php _subtemplate( 'footers', _ch($subtemplate_footer, 'alternative')); ?>
    </div><!--wrapper end-->
    <?php _widget('custom_javascript');?>
</body>
</html>