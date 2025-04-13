<?php
/*
Widget-title: Footer social
Widget-preview-image: /assets/img/widgets_preview/footer_social.webp
 */
?>

<div class="col-xl-6 col-sm-12 col-md-5 widget_edit_enabled">
    <div class="bottom-list widget-follow-us">
        <h3><?php echo lang_check('Follow Us');?></h3>
        <div class="footer-social">
            <a href="https://www.facebook.com/share.php?u={homepage_url}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-facebook"></i></a>
            <a href="https://twitter.com/home?status={homepage_url}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-twitter"></i></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&amp;url={homepage_url}&amp;title=&amp;summary=&amp;source=" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-linkedin"></i></a>
            <a href="https://www.instagram.com" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-instagram"></i></a>
        </div>
    </div>
</div>      