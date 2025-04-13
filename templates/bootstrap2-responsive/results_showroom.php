        <div class="row-fluid">
        <ul class="thumbnails">
            <?php foreach($showroom_module_all as $key=>$row):?>
              <li class="span12 li-list">
                <div class="thumbnail span4">
                <?php if(isset(${'images_'.$row->repository_id})):?>
                  <img alt="300x200" data-src="holder.js/300x200" style="width: 300px; height: 200px;" src="<?php echo ${'images_'.$row->repository_id}[0]->thumbnail_url?>" />
                <?php else:?>
                  <img alt="300x200" data-src="holder.js/300x200" style="width: 300px; height: 200px;" src="assets/img/no_image.jpg" />
                <?php endif;?>
                  <a href="{url}" class="over-image"> </a>
                </div>
                  <div class="caption span8">
                    <p class="bottom-border"><strong><?php echo $row->title.', '.date("Y-m-d", strtotime($row->date_publish)); ?></strong></p>
                    <p class="prop-description"><?php echo $row->description; ?></p>
                    <p>
                    <a class="btn btn-info" href="<?php echo site_url('showroom/'.$row->id.'/'.$lang_code); ?>#content-position">
                    {lang_Details}
                    </a>
                    </p>
                  </div>
              </li>
            <?php endforeach;?>
            </ul>
            <div class="pagination news">
            <?php echo $showroom_pagination; ?>
            </div>
        </div>