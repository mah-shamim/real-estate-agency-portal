<meta charset="UTF-8">
<title>{page_title}</title>
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="{page_description}" />
<meta name="keywords" content="{page_keywords}" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="" />

<meta property="og:site_name" content="<?php _che($settings_websitetitle, '');?>" />
<meta property="og:title" content="<?php _che($settings_websitetitle, '');?> - {page_title}" />
<meta property="og:url" content="<?php echo current_url(); ?>" />
<meta property="og:description" content="{page_description}" />
<?php if(isset($page_images) && !empty($page_images)):?>
<meta property="og:image" content="<?php _che($page_images[0]->url);?>" />
<?php else:?>
<meta property="og:image" content="assets/img/default-image-og.webp" />
<?php endif;?>

<link rel="shortcut icon" href="<?php echo $website_favicon_url;?>" type="image/png" />
<link rel="canonical" href="<?php echo slug_url(uri_string());?>" />
<link href="https://fonts.googleapis.com/css?family=Lora%7COpen+Sans:300,400,600,700%7CPlayfair+Display:400,700%7CPoppins:300,400,500,600,700%7CRaleway:300,400,500,600,700,800%7CRoboto:300,400,500,700&display=swap&subset=cyrillic&display=swap" rel="stylesheet">
<?php if(config_item('cookie_warning_enabled') === TRUE): ?>
<link href="assets/css/jquery.cookiebar.css" rel="stylesheet"></link>
<?php endif;?>
<!-- Start Template files -->
<?php cache_file('big_css.css', 'assets/icons/font-awesome/css/font-awesome.min.css'); ?>
<?php cache_file('big_css.css', 'assets/icons/simple-line-icons/css/simple-line-icons.css'); ?>
<?php cache_file('big_css.css', 'assets/icons/simple-line-icons/css/line-awesome.min.css'); ?>
<?php cache_file('big_css.css', 'assets/css/bootstrap.min.css'); ?>

<?php cache_file('big_css.css', 'assets/css/animate.min.css'); ?>
<?php cache_file('big_css.css', 'assets/js/lib/slick/slick.css'); ?>
<?php cache_file('big_css.css', 'assets/js/lib/slick/slick-theme.css'); ?>
<?php cache_file('big_css.css', 'assets/css/style.css'); ?>
<?php cache_file('big_css.css', 'assets/css/responsive.css'); ?>
<?php cache_file('big_css.css', 'assets/css/color.css'); ?>
<?php cache_file('big_js_header.js', 'assets/js/jquery-3.3.1.min.js'); ?>
<?php cache_file('big_js_header.js', 'assets/js/jquery-ui.min.js'); ?>
<?php cache_file('big_js_header.js', 'assets/js/modernizr-3.6.0.min.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/popper.min.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/bootstrap.min.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/lib/slick/slick.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/scripts.js'); ?>
<!-- End  Template files -->

<!-- Start BOOTSTRAP -->
<?php cache_file('big_css.css', 'assets/css/bootstrap-select.min.css'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/bootstrap-select.min.js'); ?>
<!-- End Bootstrap -->

<!-- Start blueimp  -->
<?php cache_file('big_css.css', 'assets/css/blueimp-gallery.min.css'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/blueimp-gallery.min.js'); ?>
<!-- End blueimp  -->

    
<?php if(config_db_item('map_version') !='open_street'):?>
<?php cache_file('big_js_footer.js', 'assets/js/map_infobox.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/markerclusterer.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/google-custom-marker.js'); ?>
<?php endif;?>

<?php cache_file('big_js_footer.js', 'assets/libraries/bootstrap-3-typeahead/bootstrap3-typeahead.min.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/libraries/customd-jquery-number/jquery.number.min.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/libraries/h5Validate-master/jquery.h5validate.js'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/jquery.helpers.js'); ?>

<?php cache_file('big_js_orig.js', 'assets/js/moment-with-locales.min.js'); ?>
<?php cache_file('big_js_orig.js', 'assets/js/moment-timezone-with-data.js'); ?>

<!-- fileupload -->
<?php cache_file('fileupload_css.css', 'assets/css/jquery.fileupload-ui.css'); ?>
<?php cache_file('fileupload_css.css', 'assets/css/jquery.fileupload-ui-noscript.css'); ?> 

<?php cache_file('fileupload_js.js', 'assets/js/fileupload/jquery.iframe-transport.min.js'); ?>
<?php cache_file('fileupload_js.js', 'assets/js/fileupload/jquery.fileupload.min.js'); ?>
<?php cache_file('fileupload_js.js', 'assets/js/fileupload/jquery.fileupload-fp.min.js'); ?>
<?php cache_file('fileupload_js.js', 'assets/js/fileupload/jquery.fileupload-ui.min.js'); ?>
<!-- end fileupload -->

<!-- Start bootstrap-datetimepicker-master -->
<?php cache_file('big_css.css', 'assets/libraries/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css'); ?>
<?php cache_file('big_js_footer.js', 'assets/libraries/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js'); ?>
<!-- End bootstrap-datetimepicker-master -->


<!-- magnific-popup -->
<!-- url  https://plugins.jquery.com/magnific-popup/ -->
<?php if(config_item('report_property_enabled') == TRUE): ?>
<?php cache_file('big_js_footer.js', 'assets/libraries/magnific-popup/jquery.magnific-popup.js'); ?>
<?php cache_file('big_css.css', 'assets/libraries/magnific-popup/magnific-popup.css'); ?>
<?php endif;?>
<!--end magnific-popup -->

<!-- Start data-table -->	
<?php cache_file('big_css.css', 'assets/libraries/datatables/datatables.min.css'); ?>
<?php cache_file('big_js_footer.js', 'assets/libraries/datatables/datatables.min.js'); ?>
<!-- End data-table  -->

<!-- url  http://www.primebox.co.uk/projects/jquery-cookiebar/ -->
<?php cache_file('big_js_footer.js', 'assets/libraries/nouislider/nouislider.min.js'); ?>
<?php cache_file('big_css.css', 'assets/libraries/nouislider/nouislider.min.css'); ?>
<!--end jquery.cookiebar -->

<!-- Start custom styles  -->
<?php cache_file('big_css.css', 'assets/js/winter_treefield/winter.css'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/winter_treefield/winter.js'); ?>

<?php cache_file('big_css.css', 'assets/js/winter_treefield_alt/winter_treefield_alt.css'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/winter_treefield_alt/winter_treefield_alt.js'); ?>

<?php cache_file('big_js_footer.js', 'assets/js/selio-drop-menu.js'); ?>
<?php cache_file('big_css.css', 'assets/css/custom.css'); ?>
<?php cache_file('big_css.css', 'assets/css/custom_media.css'); ?>
<?php cache_file('big_js_footer.js', 'assets/js/custom.js'); ?>
<!-- End custom styles  -->


<?php cache_file('big_css.css', NULL, true); ?>
<?php cache_file('big_js_header.js', NULL, false); ?>
<?php if(sw_is_safari()):?>
    <link href="assets/css/safari.css" rel="stylesheet">
<?php endif;?>
{has_color}
<?php if(isset($color) && !empty($color)):?>
    <link href="assets/css/styles_{color}.css" rel="stylesheet">
<?php endif;?>
{/has_color}
<!-- Maps -->

{is_rtl}
    <link href="assets/css/rtl.css" rel="stylesheet">
{/is_rtl}

{settings_tracking}


