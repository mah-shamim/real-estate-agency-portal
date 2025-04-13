
<?php if (!empty($page_images)): ?>
<div class="images-gallery widget-gallery widget widget-preloadigallery"> 
    <h3><?php echo lang_check('Gallery'); ?></h3>
    <div class="row">
        <?php foreach ($page_images as $val):?>
            <div class="col-sm-6 col-md-3">
                <div class="card card-gallery">
                    <a href="<?php _che($val->url);?>" title="<?php _che($val->filename);?>" download="<?php _che($val->url);?>" class="preview">
                        <img src="<?php echo _simg($val->thumbnail_url, '430x300', true);?>" class="image-cover" alt="<?php _che($val->filename);?>" />
                    </a>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
<?php endif; ?>