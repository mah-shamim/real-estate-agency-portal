<?php
/*
Widget-title: Contacts data
Widget-preview-image: /assets/img/widgets_preview/top_contacts.jpg
 */
?>
<section class="widget_edit_enabled sect-contact-featured section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
            </div>
        </div>
        <div class="row justify-content-center row-contact">
            <div class="col-xl-4 col-sm-6 col-md-6 col-lg-4">
                <div class="contact-card">
                    <div class="logo">
                        <h2 class="title">
                            <?php if(!empty($website_logo_url) && stripos($website_logo_url, 'assets/img/logo.png') === FALSE):?>
                                <img src="<?php echo $website_logo_url; ?>" alt="{settings_websitetitle}" class="img-fluid">
                            <?php elseif(false):?>
                                <?php
                                $first_w =  strtok($settings_websitetitle, " "); // Test
                                ?>
                                <b class="text-color-primary"> <?php echo $first_w;?></b>
                                <?php echo str_replace($first_w, '', $settings_websitetitle);?>
                            <?php else:?>
                                <img src="<?php echo $website_logo_url; ?>" alt="{settings_websitetitle}" class="img-fluid">
                            <?php endif;?>
                        </h2>
                        <span class="mini-title"><?php echo lang_check('Selio Group Headquarters');?></span>
                    </div>
                    <div class="address">{settings_address}</div>
                </div><!--card end-->
            </div>
            <div class="col-xl-4 col-sm-6 col-md-6 col-lg-4">
                <div class="contact-card featured">
                    <img src="assets/images/cities/<?php echo (sw_is_safari()) ? '3.jpg' : '3.webp';?>" alt="" class="cover">
                    <h2 class="title"><?php echo lang_check('I’m agent broker');?></h2>
                    <a href="#" class="btn-default"><?php echo lang_check('Request info');?></a>
                </div><!--card end-->
            </div>
            <div class="col-xl-4 col-sm-6 col-md-6 col-lg-4">
                <div class="contact-card">
                    <div class="contact_info">
                        <h3 class="elementor-inline-editing"><?php echo lang_check('Other Contact Information');?></h3>
                        <ul class="cont_info">
                            <li><i class="la la-map-marker"></i> <span class="address">{settings_address}</span></li>
                            <?php if(_ch($settings_phone, false)):?>
                                <?php 
                                    $justNums = preg_replace("/[^0-9]/", '',  _ch($settings_phone,'#'));
                                ?>
                                <li><i class="la la-phone"></i> <a href="tel://<?php echo $justNums;?>"><?php _che($settings_phone);?></a></li>
                            <?php endif;?>
                            <?php if(_ch($settings_email, false)):?>
                                <li><i class="la la-envelope"></i><a href="mailto:<?php _che($settings_email);?>"><?php _che($settings_email);?></a></li>
                            <?php endif;?>
                        </ul>
                        <ul class="social_links">
                            <li><a href="https://www.facebook.com/share.php?u={homepage_url}" title=""><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://twitter.com/home?status={homepage_url}" title=""><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://www.instagram.com" title=""><i class="fa fa-instagram"></i></a></li>
                            <li><a href="https://www.linkedin.com/shareArticle?mini=true&amp;url={homepage_url}&amp;title=&amp;summary=&amp;source=" title=""><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div><!--contact_info end-->
                </div><!--card end-->
            </div>
        </div>
    </div>
</section>