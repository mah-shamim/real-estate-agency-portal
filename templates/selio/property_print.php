<!doctype html>
<html class="no-js" lang="en">
<head>
    <?php _widget('head');?>
    <!-- Essential styles -->
    <?php if(file_exists(FCPATH.'templates/'.$settings_template.'/assets/js/places.js')): ?>
        <script src="assets/js/places.js"></script>
    <?php endif; ?>
</head>
<body>
    <div class="wrapper">
        <section class="property-single-pg">
            <div class="container">
                <?php _widget('property_top_hd-sec');?>
                <div class="property-single-page-content">
                    <div class="row">
                        <div class="col-8 pl-0 pr-0">
                            <div class="property-pg-left">
                                <?php _widget('property_center_slider');?>
                                <?php _widget('property_center_description');?>
                                <?php _widget('property_center_details');?>
                                <?php _widget('property_center_amenities_indoor');?>
                                <?php _widget('property_center_amenities_outdoor');?>
                                <?php _widget('property_center_distances');?>
                                <?php _widget('property_center_plan');?>
                                <?php _widget('property_center_map');?>
                            </div><!--property-pg-left end-->
                        </div>
                        <div class="col-4 pr-0">
                            <div class="sidebar layout2">
                                <p style="text-align:left;">
                                    <button class="print_hidden" onclick="myFunction()"><?php echo lang_check('Print'); ?></button> <script> function myFunction() { window.print(); } </script>
                                </p>
                                <?php _widget('property_right_qrcode');?>
                            </div><!--sidebar end-->
                        </div>
                    </div>
                </div><!--property-single-page-content end-->
            </div>
        </section><!--property-single-pg end-->
        <?php _widget('top_discover_banner_html');?>
        <?php _subtemplate( 'footers', _ch($subtemplate_footer, 'default')); ?>
    </div><!--wrapper end-->
    <?php _widget('custom_javascript');?>
</body>
</html>