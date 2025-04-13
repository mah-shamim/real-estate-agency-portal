<?php

    function is_defined_category_din ($array = NULL) {
        if($array === NULL) return false;
        $flag = false;
        foreach ($array as $v) {
            if(!empty($v['is_checkbox'])) {
                $flag = true;
                break;
            } elseif(!empty($v['is_text'])) {
                $flag = true;
                break;
            } elseif(!empty($v['is_dropdown'])) {
                $flag = true;
                break;
            } elseif(!empty($v['is_tree'])) {
                $flag = true;
                break;
            } 
        }
        return $flag;
    }

    /* Show new categories */

    $CI = &get_instance();
    $CI->load->model('option_m');
    $categories = $CI->option_m->get_by(array('type'=>'CATEGORY'));
    /* skip categories */
    $already_exists_categories = array(1,21,65,66,52,43,42);
    foreach ($categories as $key => $value):
        if(in_array($value->id, $already_exists_categories)) continue;
        $category_id = $value->id;
        ?>
        <?php if(isset(${"category_options_$category_id"}) && ${"category_options_count_$category_id"}>0 && is_defined_category_din(${"category_options_$category_id"})): ?>
        <div class="details-info white">
            <h3><?php echo ${"options_name_$category_id"} ?></h3>
            <ul> 
                <?php foreach(${"category_options_$category_id"} as $key=>$row): ?>
                <?php if($row['option_type'] == 'CHECKBOX'): ?>
                <li>
                    <h4><?php _che($row['option_name']);?>:</h4>&nbsp;&nbsp;
                    <span>
                        <?php if(_ch($row['option_value']) == 'true'):?>
                            <i class="fa fa-check ok"></i>
                        <?php else:?>
                            <i class="fa fa-close"></i>
                        <?php endif;?>
                    </span>
                </li>
                <?php elseif(!empty($row['option_value'])): ?>
                    <?php if($row['option_type'] == 'INPUTBOX' || $row['option_type'] == 'INTEGER'|| $row['option_type'] == 'DATETIME'): ?>
                    
                        <?php
                            // iframe support
                            if(strpos(_ch($row['option_value']), 'iframe') !== FALSE) {
                                echo (_ch($row['option_value']));
                            }
                            elseif(strpos(_ch($row['option_value']), 'vimeo.com') !== FALSE)
                            {
                                ?>
                                <li class="wide">
                                    <iframe width="600" height="338" 
                                    src="<?php echo _ch($row['option_value']);?>"
                                    frameborder="0" 
                                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                <?php
                            }
                            elseif(strpos(_ch($row['option_value']), 'watch?v=') !== FALSE)
                            {
                                $embed_code = substr(_ch($row['option_value']), strpos(_ch($row['option_value']), 'watch?v=')+8);
                                ?>
                                <li class="wide">
                                    <iframe width="600" height="338" 
                                    src="<?php echo 'https://www.youtube.com/embed/'.$embed_code;?>"
                                    frameborder="0" 
                                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                <?php
                            }
                            // version for youtube link
                            elseif(strpos(_ch($row['option_value']), 'youtu.be/') !== FALSE)
                            {
                                $embed_code = substr(_ch($row['option_value']), strpos(_ch($row['option_value']), 'youtu.be/')+9);
                                ?>
                                <li class="wide">  
                                    <iframe width="600" height="338" 
                                    src="<?php echo 'https://www.youtube.com/embed/'.$embed_code;?>"
                                    frameborder="0" 
                                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                <?php
                            }
                            // basic text
                            else
                            {
                                ?>
                                <li>    
                                    <h4><?php _che($row['option_name']);?>:</h4>
                                    <span><?php _che($row['option_prefix']);?> <?php _che($row['option_value']);?> <?php _che($row['option_suffix']);?></span>
                                <?php
                            }
                        ?>
                    </li><!-- /.property-detail-overview-item -->
                    <?php elseif(sw_count($row['is_dropdown']) > 0): ?>
                    <li>
                        <h4><?php _che($row['option_name']);?>:</h4>
                        <span><?php _che($row['option_prefix']);?> <?php _che($row['option_value']);?> <?php _che($row['option_suffix']);?></span>
                    </li><!-- /.property-detail-overview-item -->
                    <?php elseif(sw_count($row['is_tree']) > 0): ?>
                    <li>
                        <h4><?php _che($row['option_name']);?>:</h4>
                        <span><?php _che($row['option_prefix']);?> <?php _che($row['option_value']);?> <?php _che($row['option_suffix']);?></span>
                    </li><!-- /.property-detail-overview-item -->
                    <?php endif; ?>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div><!--details-info end-->
        <?php endif; ?>
    <?php endforeach; 
    /* END Show new categories */
    ?>