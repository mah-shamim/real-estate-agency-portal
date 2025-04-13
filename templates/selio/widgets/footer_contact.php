<?php
/*
Widget-title: Contact
Widget-preview-image: /assets/img/widgets_preview/footer_contact.webp
 */
?>

<div class="col-xl-3 col-sm-6 col-md-3 widget_edit_enabled">
    <div class="widget-footer-contacts">
        <h3><?php echo lang_check('Contact Us');?></h3>
        <ul class="footer-list">                
            <li><i class="la la-map-marker"></i> 
            <span class="value"><?php echo lang_check('432 Park Ave, New York, NY 10022');?></span></li>
            <?php if(_ch($settings_phone, false)):?>
                <?php 
                    $justNums = preg_replace("/[^0-9]/", '',  _ch($settings_phone,'#'));
                ?>
                <li><i class="la la-phone"></i> <span class="value"><a href="tel://<?php echo $justNums;?>"><?php _che($settings_phone);?></a></span></li>
            <?php endif;?>
            <li><i class="la la-envelope"></i> <span class="value"><a href="mailto:<?php _che($settings_email);?>"><?php _che($settings_email);?></a></span></li>
            <li><i class="la la-chevron-circle-right"></i><span class="value"><a href="#"><?php echo lang_check('Contact Us');?></a></span></li></ul>
    </div>
</div>