<!doctype html>
<html class="no-js" lang="en">
    <head>
        <?php _widget('head'); ?>
    </head>
    <body>
        <div class="wrapper hp_4">
            <header>
                <?php _widget('header_bar'); ?>
                <?php _widget('header_main_panel'); ?>
            </header><!--header end-->

            <?php _widget('top_geo_map'); ?>
            <?php _widget('top_search_sec'); ?>
            <?php _widget('top_popular_listings_rent'); ?>
            <?php _widget('top_popular_listings_sale'); ?>
            <?php _widget('top_discover-propt'); ?>
            <?php _widget('top_locations'); ?>

            <?php _widget('top_explore_features_html'); ?>
            <?php _widget('top_discover_banner_html'); ?>
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
    </body>

</html>