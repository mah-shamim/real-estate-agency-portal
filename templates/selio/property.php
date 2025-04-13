<!doctype html>
<html class="no-js" lang="en">
<head>
    <?php _widget('head');?>
    <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/places.js')): ?>
        <script src="assets/js/places.js"></script>
    <?php endif; ?>

</head>
<body>
    <?php if (!empty($settings_facebook_jsdk) && (config_db_item('appId') == '' || !file_exists(FCPATH . 'templates/' . $settings_template . '/assets/js/like2unlock/js/jquery.op.like2unlock.min.js'))): ?>
        <?php
        if (!empty($lang_facebook_code))
            $settings_facebook_jsdk = str_replace('en_EN', $lang_facebook_code, $settings_facebook_jsdk);
        ?>
        <?php echo $settings_facebook_jsdk; ?>
    <?php endif; ?>
    <div class="wrapper">
      	<header>
            <?php _widget('header_bar');?>
            <?php _widget('header_main_panel');?>
        </header><!--header end-->
        <?php _widget('top_search_sec');?>
        <section class="property-single-pg">
            <div class="container">
                <?php _widget('property_top_ads');?>
                <?php _widget('property_top_hd-sec');?>
                <div class="property-single-page-content">
                    <div class="row">
                        <div class="col-lg-8 pl-0 pr-0">
                            <div class="property-pg-left">
                                <?php _widget('property_center_slider');?>
                                <?php _widget('property_center_actions');?>
                                <?php _widget('property_center_description');?>
                                <?php _widget('property_center_property_energygas');?>
                                <?php _widget('property_center_documents');?>
                                <?php _widget('property_center_details');?>
                                <?php _widget('property_center_amenities_indoor');?>
                                <?php _widget('property_center_amenities_outdoor');?>
                                <?php _widget('property_center_distances');?>
                                <?php _widget('property_center_dynamic_categories');?>
                                <?php _widget('property_center_plan');?>
                                <?php _widget('property_center_rates_table');?>
                                <?php _widget('property_center_map');?>
                                <?php _widget('property_center_walkscore');?>
                                <?php _widget('property_center_review');?>
                                <?php _widget('property_center_facebook');?>
                                <?php _widget('property_center_similar-llisitngs');?>
                            </div><!--property-pg-left end-->
                        </div>
                        <div class="col-lg-4 pr-0">
                            <div class="sidebar layout2">
                                 <?php _widget('property_right_form_contact');?>
                                 <?php _widget('property_right_popular_listings');?>
                                 <?php _widget('property_right_mortgage');?>
                                 <?php _widget('property_right_pdf');?>
                                 <?php _widget('property_right_qrcode');?>
                                 <?php _widget('property_right_print');?>
                                 <?php _widget('property_right_compare');?>
                                 <?php _widget('right_ads');?>
                                 <?php _widget('property_right_companydetails');?>
                            </div><!--sidebar end-->
                        </div>
                    </div>
                </div><!--property-single-page-content end-->
            </div>
        </section><!--property-single-pg end-->
        <?php _widget('top_discover_banner_html');?>
        <?php _subtemplate( 'footers', _ch($subtemplate_footer, 'default')); ?>
    </div><!--wrapper end-->
    <?php if(file_exists(APPPATH.'controllers/admin/swin_reviews.php')): ?>
        <script src="assets/libraries/ratings/bootstrap-rating-input.js"></script> 
    <?php endif; ?>
    <?php
    sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_property_favorites.php');
    ?>
    <?php cache_file('fileupload_css.css', NULL, true); ?>
    <?php cache_file('fileupload_js.js', NULL); ?>
    <?php _widget('custom_javascript');?>
</body>
</html>