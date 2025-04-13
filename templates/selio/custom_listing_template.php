<!doctype html>
<html class="no-js" lang="en">
<head>
    <?php _widget('head');?>
</head>
<body>
    <?php if (!empty($settings_facebook_jsdk) && (config_db_item('appId') == '' || !file_exists(FCPATH . 'templates/' . $settings_template . '/assets/js/like2unlock/js/jquery.op.like2unlock.min.js'))): ?>
        <?php
        if (!empty($lang_facebook_code))
            $settings_facebook_jsdk = str_replace('en_EN', $lang_facebook_code, $settings_facebook_jsdk);
        ?>
        <?php echo $settings_facebook_jsdk; ?>
        <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/places.js')): ?>
            <script src="assets/js/places.js"></script>
        <?php endif; ?>
    <?php endif; ?>
    <div class="wrapper">
      	<header>
            <?php _widget('header_bar');?>
            <?php _widget('header_main_panel');?>
        </header><!--header end-->
        <?php _widget('top_search_sec');?>
        <section class="property-single-pg">
            <div class="container">
                <?php _widget('property_top_hd-sec');?>
                <div class="property-single-page-content">
                    <div class="row">
                        <div class="col-lg-8 pl-0 pr-0">
                            <div class="property-pg-left">
                                <?php
                                foreach ($widgets_order->center as $widget_filename) {
                                    _widget('property_'.$widget_filename);
                                }
                                ?>
                            </div><!--property-pg-left end-->
                        </div>
                        <div class="col-lg-4 pr-0">
                            <div class="sidebar layout2">
                                <?php
                                foreach ($widgets_order->right as $widget_filename) {
                                    _widget('property_'.$widget_filename);
                                }
                                ?>
                            </div><!--sidebar end-->
                        </div>
                    </div>
                </div><!--property-single-page-content end-->
            </div>
        </section><!--property-single-pg end-->
        <?php _widget('top_discover_banner_html');?>
        <?php _subtemplate( 'footers', _ch($subtemplate_footer, 'default')); ?>
    </div><!--wrapper end-->
    <?php cache_file('fileupload_css.css', NULL, true); ?>
    <?php cache_file('fileupload_js.js', NULL); ?>
    <?php if(file_exists(APPPATH.'controllers/admin/swin_reviews.php')): ?>
        <script src="assets/libraries/ratings/bootstrap-rating-input.js"></script> 
    <?php endif; ?>
    <?php _widget('custom_javascript');?>
</body>
</html>