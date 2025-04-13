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
        <?php _widget('top_search_sec');?>
        <section class="listing-main-sec section-padding2">
            <div class="container">
                <div class="listing-main-sec-details">
                    <div class="row">
                        <div class="col-lg-8">
                            <?php _widget('center_results');?>
                        </div>
                        <div class="col-lg-4">
                            <div class="sidebar layout2">
                                <?php _widget('right_filterform');?>
                                <?php _widget('right_featured_listing');?>
                                <?php _widget('right_categories');?>
                                <?php _widget('right_latest_listings');?>
                            </div><!--sidebar end-->
                        </div>
                    </div>
                </div><!--listing-main-sec-details end-->
            </div>    
        </section><!--listing-main-sec end-->
        <?php _subtemplate( 'footers', _ch($subtemplate_footer, 'default')); ?>
    </div><!--wrapper end-->
    <?php _widget('custom_javascript');?>
</body>
</html>