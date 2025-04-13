<?php if(sw_count($slideshow_property_images)):?>
    <div class="property-imgs">
        <div class="property-main-img">
            <?php foreach($slideshow_property_images as $file): ?>
            <div class="property-img">
                <img data-fullsrc="<?php echo $file['url'];?>" src="<?php echo _simg($file['url'], '1300x800', true);?>" alt="<?php echo _ch($file['alt'], '');?>">
            </div><!--property-img end-->
            <?php endforeach;?>
        </div><!--property-main-img end-->
        <?php if(sw_count($slideshow_property_images) > 1):?>
        <div class="property-thumb-imgs">
            <div class="row thumb-carous">
                <?php foreach($slideshow_property_images as $file): ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4 thumb-img">
                    <div class="property-img">
                        <img data-fullsrc="<?php echo $file['url'];?>"  src="<?php echo _simg($file['url'], '770x483', true);?>" alt="<?php echo _ch($file['alt'], '');?>" title="<?php echo _ch($file['title'], '');?>">
                    </div><!--property-img end-->
                </div>
                <?php endforeach;?>
            </div>
        </div><!--property-thumb-imgs end-->
        <?php endif;?>
    </div><!--property-imgs end-->
<?php endif;?>