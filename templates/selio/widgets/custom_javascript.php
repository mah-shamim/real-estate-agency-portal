<?php _widget('custom_popup');?>
<div class="wdk_mobile_footer_menu">
    <a href="#" title="Home">
        <i class="fa fa-home" aria-hidden="true"></i>
    </a>
    <?php 
        // get title;
        $CI =& get_instance();
        $CI->load->model('page_m');
        $_page = $CI->page_m->get_lang(get_results_page_id(),false,$lang_id);
        $_title=$_page->{'navigation_title_'.$lang_id};
    ?>
    <a href="<?php echo site_url($lang_code.'/'.get_results_page_id().'/'.url_title_cro($_title, '-', TRUE))?>" title="<?php echo lang_check('Search');?>">
        <i class="fa fa-search" aria-hidden="true"></i>
    </a>
    {not_logged}
        <a href="{front_login_url}#content" class="sign_in" title="<?php echo lang_check('Log In');?>">
            <i class="fa fa-user" aria-hidden="true"></i>
        </a>
    {/not_logged}
    {is_logged_user}
        <a href="{myproperties_url}#content" class="sign_in" title="<?php echo lang_check('Dash');?>">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
        </a>
        <a href="{logout_url}" class="sign_in" title="<?php echo lang_check('Log Out');?>">
            <i class="fa fa-user-times" aria-hidden="true"></i>
        </a>
    {/is_logged_user}
    {is_logged_other}
        <a href="{myproperties_url}#content" class="sign_in" title="<?php echo lang_check('Dash');?>">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
        </a>
        <a href="{logout_url}" class="sign_in" title="<?php echo lang_check('Log Out');?>">
            <i class="fa fa-user-times" aria-hidden="true"></i>
        </a>
    {/is_logged_other}
    <div class="wdk-footer-menu">
        <label for="wdk_mobile_footer_menu_gumb" class="wdk_mobile_footer_menu_gumb-open trigger">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </label>
    </div>

    <script>
        $('.wdk-footer-menu .wdk_mobile_footer_menu_gumb-open').on('click', function(e){
            e.preventDefault();
            $('.menu-button').trigger('click');
        });
    </script>
</div>

<div class="se-pre-con"></div>

<!-- Start JS MAP  -->
<?php load_map_api(config_db_item('map_version'), $lang_code);?>

<?php cache_file('big_js_footer.js', NULL, true); ?>
<?php cache_file('big_js_orig.js', NULL); ?>

<?php
//_generate_js('_generate_custom_javascript_'.md5(current_url_q()), 'widgets/_generate_custom_javascript.php');

sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_custom_javascript.php');
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_calendar_js.php');
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_dependentfields.php');

sw_add_script('page_js_'.md5(current_url_q()), NULL);


?>
<script src="assets/js/scroll-mobile-swipe.js"></script>
<script src="assets/js/scroll-mobile-swipe.js"></script>
<script>
$('document').ready(function() {
    if (typeof $.fn.WdkScrollMobileSwipe == 'function') {
        $('.WdkScrollMobileSwipe_enable').WdkScrollMobileSwipe();
    }
});
</script>
<!-- jquery.cookiebar -->
<!-- url  http://www.primebox.co.uk/projects/jquery-cookiebar/ -->
<?php if(config_item('cookie_warning_enabled') === TRUE): ?>
<script src="assets/libraries/cookie_bar/jquery.cookiebar.js"></script>
<script>
$('document').ready(function() {
    $.cookieBar({
        //declineButton: true,
        message: "<p><?php _l('Accept cookiebar');?></p><br>",
        acceptText: "<?php _l('I Agree');?>",
        //declineText: "<?php _l('I dont agree');?>",
    });
})
</script>
<?php endif;?>
<!--end jquery.cookiebar -->

<!-- Generate time: <?php echo (microtime(true) - $time_start)?>, version: <?php echo APP_VERSION_REAL_ESTATE; ?> -->