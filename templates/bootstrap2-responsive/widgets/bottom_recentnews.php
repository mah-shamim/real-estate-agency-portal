<div class="wrap-content2">
    <div class="container">
        <h2>{lang_Latestnews}</h2>
        <!-- NEWS -->
        <div class="property_content_position">
        <div class="row-fluid">
        <ul class="thumbnails">
            <?php foreach($news_module_latest_5 as $key=>$row):?>
              <li class="span12 li-list">
                <div class="thumbnail span4">
                <?php if(isset($row->image_filename)):?>
                 <img alt="300x200" data-src="holder.js/300x200" style="width: 300px; height: 200px;" src="<?php echo _simg('files/'.$row->image_filename, '600x400'); ?>" />
                <?php else:?>
                  <img alt="300x200" data-src="holder.js/300x200" style="width: 300px; height: 200px;" src="assets/img/no_image.jpg" />
                <?php endif;?>
                  <a href="<?php echo site_url($lang_code.'/'.$row->id); ?>" class="over-image"> </a>
                </div>
                  <div class="caption span8">
                    <p class="bottom-border"><strong><?php echo $row->title.', '.date("Y-m-d", strtotime($row->date_publish)); ?></strong></p>
                    <p class="prop-description"><?php echo $row->description; ?></p>
                    <p>
                    <a class="btn btn-info" href="<?php echo site_url($lang_code.'/'.$row->id); ?>">
                    {lang_Details}
                    </a>
                    </p>
                  </div>
              </li>
            <?php endforeach;?>
            </ul>
        </div>
        </div>
        <!-- /NEWS -->
    </div>
</div>