<section class="bottom section-padding">
    <div class="container placeholder-container">
        <div class="row">
            <?php _widget('footer_logo_info');?>
            <?php _widget('footer_contact');?>
            <?php _widget('footer_social');?>
        </div>
        <img src="assets/img/<?php echo (sw_is_safari()) ? 'placeholder-footer.png' : 'placeholder-footer.webp';?>" alt="placeholder" class="footer-placeholder">
    </div>
</section>
<footer class="footer">
    <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-12 ">
                    <div class="copyright text-center footer-content ">
                        <p>© <?php echo lang_check('Selio theme made in EU. All Rights Reserved');?>.</p>
                    </div>
                </div>
            </div>
    </div>
</footer><!--footer end-->