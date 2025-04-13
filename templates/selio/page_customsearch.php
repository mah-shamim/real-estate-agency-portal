
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php _widget('head'); ?>
</head>
<body>
    <?php _widget('header_naviagation_search');?>
    <div class="wrapper">
        <?php _widget('top_categories_icons');?>
        <?php _widget('top_popular_listings');?>
        <?php _widget('top_locations');?>
        <?php _widget('top_explore_features_html');?>
        <?php _widget('top_discover_banner_html');?>
        <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
    </div><!--wrapper end-->
    <?php _widget('custom_javascript'); ?>
</body>

</html>