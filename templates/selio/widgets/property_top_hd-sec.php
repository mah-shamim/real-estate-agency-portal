 <div class="property-hd-sec">
    <div class="card">
        <div class="card-body">
            <a href="#">
                <h3><?php echo _ch($estate_data_option_10,'');?>
                    <?php
                    $title = $page_title;
                    $permalink = $page_current_url;

                    $facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink;
                    $instagram = 'https://plus.instagram.com/share?url=' . $permalink;	
                    $pinterest = 'https://pinterest.com/pin/create/button/?url=' . $permalink .'&media='. $permalink .'&description=' . urlencode($title);
                    $twitter = 'https://twitter.com/home?status=' .urlencode($title);

                    echo '
                    <ul class="social-links">
                     <li class="listing_share_tw"><a target="_blank" href="' . ($twitter) . '" data-csshare-type="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                     <li class="listing_share_fb"><a target="_blank" href="' . ($facebook) . '" data-csshare-type="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                     <li class="listing_share_p"><a target="_blank" href="' . ($pinterest) . '" data-csshare-type="pinterest"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                     <!--<li class="listing_share_inst"><a target="_blank" href="' . ($instagram) . '"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>-->
                    </ul>';
                    ?>
                </h3>
                <p><i class="la la-map-marker"></i><?php echo _ch($estate_data_address,'');?></p>
            </a>
            <ul>
                <?php
                      $custom_elements = _get_custom_items();
                      $i=0;
                      if(sw_count($custom_elements) > 0):
                      foreach($custom_elements as $key=>$elem):
                      if(!empty(${"estate_data_option_".$elem->f_id}) && $i++<3)
                      if($elem->type == 'DROPDOWN' || $elem->type == 'INPUTBOX'):
                       ?>
                  <li><?php echo _ch(${"estate_data_option_".$elem->f_id}, '-'); ?> <?php echo _ch(${"options_suffix_$elem->f_id"}, ''); ?> <span style="<?php _che($elem->f_style); ?>"><?php echo _ch(${"options_name_$elem->f_id"}, '-'); ?></span></li>
                       <?php 
                      elseif($elem->type == 'CHECKBOX'):
                       ?>
                  <li><strong class="<?php echo (!empty(${"estate_data_option_".$elem->f_id})) ? 'glyphicon glyphicon-ok':'glyphicon glyphicon-remove';  ?>"></strong> <?php echo _ch(${"options_name_$elem->f_id"}, '-'); ?></li>
                       <?php 
                      endif;                    
                      endforeach;  
                      else:
                  ?>
                    <?php if(isset($estate_data_option_19)):?>
                    <li><?php echo _ch($options_prefix_19, ''); ?> <?php echo _ch($estate_data_option_19, '-'); ?><?php echo _ch($options_suffix_19, ''); ?> <?php echo _ch($options_name_19, '-'); ?></li>
                    <?php endif; ?>
                    <?php if(isset($estate_data_option_20)):?>
                    <li><?php echo _ch($options_prefix_20, ''); ?> <?php echo _ch($estate_data_option_20, '-'); ?><?php echo _ch($options_suffix_20, ''); ?> <?php echo _ch($options_name_20, '-'); ?></li>
                    <?php endif; ?>
                    <?php if(isset($estate_data_option_57)):?>
                    <li><?php echo _ch($options_prefix_57, ''); ?> <?php echo _ch($estate_data_option_57, '-'); ?><?php echo _ch($options_suffix_57, ''); ?> <?php echo _ch($options_name_57, '-'); ?></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div><!--card-body end-->
        <div class="rate-info">
            <h5> 
                <?php if(!empty($estate_data_option_36) || !empty($estate_data_option_37)): ?>
                    <?php if(_ch($estate_data_option_4, false) && stripos($estate_data_option_4, lang_check('Rent'))!==FAlSE):?>
                        <?php 
                            if(!empty($estate_data_option_37))echo ' '.show_price($estate_data_option_37, $options_prefix_37, $options_suffix_37, $lang_id);
                            if(!empty($estate_data_option_37) && !empty($estate_data_option_36)) echo ' / ';
                            if(!empty($estate_data_option_36))echo show_price($estate_data_option_36, $options_prefix_36, $options_suffix_36, $lang_id);
                        ?>
                    <?php else:?>
                        <?php 
                            if(!empty($estate_data_option_36))echo show_price($estate_data_option_36, $options_prefix_36, $options_suffix_36, $lang_id);
                            if(!empty($estate_data_option_37) && !empty($estate_data_option_36)) echo ' / ';
                            if(!empty($estate_data_option_37))echo ' '.show_price($estate_data_option_37, $options_prefix_37, $options_suffix_37, $lang_id);
                        ?>
                    <?php endif;?>
                <?php endif; ?>
            </h5>
            <?php if(_ch($estate_data_option_4, false)):?>
            <span class="purpose-<?php $a='';$a=strtolower($estate_data_option_4);echo url_title_cro( str_replace(' ','_',$a)); ?>"><?php echo _ch($estate_data_option_4,'');?></span>
            <?php endif;?>
            <?php if(!empty($estate_data_option_56)): ?>
                <div><br><span class="review_stars_<?php echo $estate_data_option_56; ?>"> </span></div>
            <?php endif;?>
        </div><!--rate-info end-->
    </div><!--card end-->
</div><!---property-hd-sec end-->