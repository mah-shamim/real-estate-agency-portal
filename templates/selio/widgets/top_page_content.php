<?php
/*
Widget-title: Content
Widget-preview-image: /assets/img/widgets_preview/top_page_content.webp
 */
?>
<?php if(!empty($page_body)):?>
<section class="section-padding widget_edit_enabled">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-heading">
                    <?php if(!empty($page_description)):?>
                    <span>{page_description}</span>
                    <?php endif;?>
                    <h3>{page_title}</h3>
                </div>
            </div>
        </div><!--justify-content-center end-->
        <div class="page_content">
            {page_body}
        </div><!--partner-carousel end-->
    </div>
</section><!--agents-sec end-->
<?php endif;?>
