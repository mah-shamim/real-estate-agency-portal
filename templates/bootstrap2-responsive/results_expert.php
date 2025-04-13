        <div class="row-fluid"
        <ul class="thumbnails">
            <?php foreach($expert_module_all as $key=>$row):?>
              <li class="span12 li-list">
                  <div class="caption span12">
                    <p class="bottom-border">
                        <i class="qmark">?</i>
                        <strong><?php echo $row->question; ?></strong>
                    </p>
                    <p class="prop-description">
                        <?php if(!empty($row->answer_user_id) && isset($all_experts[$row->answer_user_id])): ?>
                        <a class="image_expert" href="<?php echo site_url('profile/'.$row->answer_user_id.'/'.$lang_code); ?>#content-position">
                            <img src="<?php echo $all_experts[$row->answer_user_id]['image_url']?>" alt="" />
                        </a>
                        <?php else:?>
                        <span class="image_expert"> </span>
                        <?php endif;?>
                        <?php echo $row->answer; ?>
                    </p>
                  </div>
              </li>
            <?php endforeach;?>
            </ul>
            <div class="pagination news">
            <?php echo $expert_pagination; ?>
            </div>
        </div>