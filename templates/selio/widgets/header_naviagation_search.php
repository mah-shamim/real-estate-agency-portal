<?php
/*
Widget-title: Header naviagation search
Widget-preview-image: /assets/img/widgets_preview/header_naviagation_search.webp
 */
?>

<header class="pb widget_edit_enabled">
    <?php _widget('header_bar');?>
    <div class="header">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a href="{homepage_url_lang}"  class="navbar-brand">
                    <?php if(!empty($website_logo_secondary_url) && stripos($website_logo_secondary_url, 'assets/img/logo.png') === FALSE):?>
                        <img src="<?php echo $website_logo_secondary_url; ?>" alt="{settings_websitetitle}">
                     <?php elseif(false):?>
                        <?php
                        $first_w =  strtok($settings_websitetitle, " "); // Test
                        ?>
                        <b class="text-color-primary"> <?php echo $first_w;?></b>
                        <?php echo str_replace($first_w, '', $settings_websitetitle);?>
                     <?php else:?>
                        <img src="assets/img/logo2.png" alt="{settings_websitetitle}">
                    <?php endif;?>
                    </a>
                    
                    <button class="menu-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent">
                        <span class="icon-spar"></span>
                        <span class="icon-spar"></span>
                        <span class="icon-spar"></span>
                    </button>
                    <div class="navbar-collapse" id="navbarSupportedContent">
                        <?php _widget('custom_mainmenu'); ?>
                        <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
                        <div class="d-inline my-2 my-lg-0">
                           <ul class="navbar-nav">
                                 <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
                                <li class="nav-item signin-btn">
                                    {not_logged}
                                     <span class="nav-link">
                                        <i class="la la-sign-in"></i>
                                        <span>
                                            <a href="{front_login_url}#sw_login" class="login_popup_enabled ">
                                                <b class="signin-op"><?php echo lang_check('Sign in');?></b> 
                                            </a>
                                            <?php echo lang_check('or');?>
                                            <a href="{front_login_url}#sw_register" class="">
                                                <b class="reg-op"><?php echo lang_check('Register');?></b>
                                            </a>
                                        </span>
                                    {/not_logged}
                                    {is_logged_user}
                                    <span class="">
                                        <i class="la la-sign-in"></i>
                                        <span>
                                            <a href="{logout_url}" class="btn btn-clear"><?php _l('Logout');?></a>
                                        </span>
                                    {/is_logged_user}
                                    {is_logged_other}
                                    <span class="">
                                            <a href="{logout_url}" class="nav-link">
                                                <i class="la la-sign-in"></i>
                                                <span><b class="signin-op"><?php _l('Sign out');?></b></span>
                                            </a>
                                    {/is_logged_other}
                                </span>
                                    </li>
                                    <?php endif;?>
                                <?php if(config_db_item('enable_qs') == 1): ?>
                                    <li class="nav-item submit-btn">
                                        <a href="<?php echo site_url('fquick/submission/'.$lang_code); ?>" class="my-2 my-sm-0 nav-link sbmt-btn">
                                            <i class="icon-plus"></i>
                                            <span><?php echo lang_check('Submit Listing');?></span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item submit-btn">
                                        <a href="<?php echo site_url('frontend/editproperty/'.$lang_code.'#content');?>" class="my-2 my-sm-0 nav-link sbmt-btn">
                                            <i class="icon-plus"></i>
                                            <span><?php echo lang_check('Submit Listing');?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php endif;?>
                        <a href="#" title="" class="close-menu"><i class="la la-close"></i></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
</header><!--header end-->
<section class="banner hp7 widget_edit">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="banner-content">
                    <h1><?php echo lang_check('Find Best Properties');?> <br /> <?php echo lang_check('in One Place');?></h1>
                </div>
                <div class="widget-property-search custom-search-b">
                    <form action="#" class="row banner-search search-form">
                        {is_logged_other}
                        <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                        <div class="widget-controls-panel widget_controls_panel" data-widgetfilename="right_filterform">
                            <a href="<?php echo site_url('admin/forms/edit/6');?>" target="_blunk" class="btn btn-edit"><i class="ion-edit"></i></a>
                        </div>
                        <?php endif;?>
                        {/is_logged_other}
                        <?php _search_form_primary(6) ;?> 
                        <div class="feat-srch">
                            <div class="more-feat">
                                <h3> <i class="la la-cog"></i> <?php echo lang_check('Show More Features');?></h3>
                            </div><!--more-feat end-->
                            <div class="form_field <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')):?> form_field_save <?php endif;?>">
                                <div class="form_field_row">
                                    <button class="btn btn-outline-primary sw-search-start" type="submit">
                                        <span><?php echo lang_check('Search');?><i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i></span>
                                    </button>
                                    <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                                        <button type="button" id="search-save" class="btn btn-custom btn-savesearch btn-custom-secondary btn-icon"><i class="fa fa-save icon-white fa-ajax-hide"></i><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></button>
                                    <?php endif; ?>
                                </div> 
                            </div>
                        </div><!--more-feat end-->
                        <div class="features_list">
                            <div class="group">
                                <?php  _search_form_secondary(6); ?>
                            </div>
                        </div><!--features_list end-->
                    </form><!--baner-form end-->
                    <div id="tags-filters">
                    </div>
                </div><!--widget-property-search end-->
            </div>
        </div>
    </div>
</section>