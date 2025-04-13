<?php
$class='';
if(isset($custom_class) && !empty($custom_class))
{
    $class = $custom_class;
} else {
    $class = 'col-md-6';
}

$slideshow_images_obj = array();
$slideshow_images = array();
if(config_db_item('results_listings_slider') == 1) {
    $slideshow_images_obj = explode(',', str_replace(array('"','[',']'), '', $item['image_repository']));
}

$i = 0;
foreach($slideshow_images_obj as $value) {
    if($i>=3) break;
    if(file_exists(FCPATH.'/files/'.$value)) {
        $slideshow_images[] = $value;
        $i++;
    }
}
?>
  
<div class="<?php echo $class;?>">
    <div class="card">
        <?php if(config_db_item('results_listings_video') == 1 && _ch($item['option_'.config_db_item('multimedia_field_id')], false)):?>
            <div>
        <?php else:?>
            <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
        <?php endif;?>
            <div class="img-block <?php if(config_db_item('results_listings_video') == 1 && _ch($item['option_'.config_db_item('multimedia_field_id')], false)):?> video-block <?php endif;?>">
                <div class="overlay"></div>
                <?php if(($item['is_featured'])):?>
                    <div class="budget"><i class="fa fa-star"></i></div>
                <?php endif;?>
                <?php if(_ch($item['option_38'], false) && _ch($item['option_38'], false) !='empty'):?>
                <?php
                    // check for version with category related marker
                    $badge=_ch($item['option_38'], false);
                    $badge=strtolower($badge);
                    $badge=url_title_cro(str_replace(' ','_',$badge));
                    echo "<span class='listing_badge badge-".($badge)."'><span class='lab'>"._ch($item['option_38'], false)."</span></span>";
                ?>
                <?php endif;?>
                <?php if(config_db_item('results_listings_video') == 1 && _ch($item['option_'.config_db_item('multimedia_field_id')], false)):?>
                    <?php echo generate_iframe_multimedia(_ch($item['option_'.config_db_item('multimedia_field_id')], false));?>
                <?php elseif(config_db_item('results_listings_slider') == 1 && sw_count($slideshow_images)>1):?>
                    <div id="listing_carousel_<?php echo _ch($item['id']); ?>" class="carousel slide carousel-listing" data-ride="carousel" data-interval="false">
                        <ol class="carousel-indicators">
                        <?php foreach($slideshow_images as $key => $img): ?>
                            <?php if($key>2) break;?>
                            <li data-target="#listing_carousel_<?php echo _ch($item['id']); ?>" data-slide-to="<?php echo $key;?>" class=" <?php echo ($key==0) ? 'active': '';?>"></li>
                        <?php endforeach; ?>
                        </ol>
                        <div class="carousel-inner">
                        <?php foreach($slideshow_images as $key => $img): ?>
                            <?php if($key>2) break;?>
                              <div class="carousel-item <?php echo ($key==0) ? 'active': '';?>">
                                <img src="<?php echo _simg($img, '851x678', true);?>" alt="<?php echo _ch($item['option_10']); ?>" class="d-block w-100 img-fluid">
                              </div>
                        <?php endforeach; ?>
                        </div>
                            <?php if(sw_count($slideshow_images)>1):?>
                                    <span class="carousel-control-prev disable_scroll" href="#listing_carousel_<?php echo _ch($item['id']); ?>" role="button" data-slide="prev">
                                        <i class="fa fa-angle-left"></i>
                                    </span>
                                    <span class="carousel-control-next disable_scroll" href="#listing_carousel_<?php echo _ch($item['id']); ?>" role="button" data-slide="next">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                <!-- Carousel nav -->
                            <?php endif;?> 
                        </div>
                    <?php else:?>
                        <img src="<?php echo _simg($item['thumbnail_url'], '851x678', true); ?>" alt="<?php echo _ch($item['option_10']); ?>" class="img-fluid">
                    <?php endif;?>
                <div class="rate-info">
                    <?php if(!empty($item['option_36']) || !empty($item['option_37'])): ?>
                    <h5>
                    <?php if(!empty($item['option_36']) || !empty($item['option_37'])): ?>
                        <?php if(_ch($item['option_4'], false) && stripos($item['option_4'], lang_check('Rent'))!==FAlSE):?>
                            <?php 
                                if(!empty($item['option_37']))echo ' '.show_price($item['option_37'], $options_prefix_37, $options_suffix_37, $lang_id);
                                if(!empty($item['option_36']))echo show_price($item['option_36'], $options_prefix_36, $options_suffix_36, $lang_id);
                            ?>
                        <?php else:?>
                            <?php 
                                if(!empty($item['option_36']))echo show_price($item['option_36'], $options_prefix_36, $options_suffix_36, $lang_id);
                                if(!empty($item['option_37']))echo ' '.show_price($item['option_37'], $options_prefix_37, $options_suffix_37, $lang_id);
                            ?>
                        <?php endif;?>
                    <?php endif; ?>
                    </h5>
                    <?php endif; ?>
                    <?php if(_ch($item['option_4'], false)):?>
                    <span class="purpose-<?php $a='';$a=strtolower($item['option_4']);echo url_title_cro( str_replace(' ','_',$a)); ?>"><?php echo _ch($item['option_4'], ''); ?></span>
                    <?php endif;?>
                </div>
            </div>
        <?php if(config_db_item('results_listings_video') == 1 && _ch($item['option_'.config_db_item('multimedia_field_id')], false)):?>
            </div>
        <?php else:?>
            </a>
        <?php endif;?>
        <div class="card-body">
            <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
                <h3><?php echo _ch($item['option_10']); ?></h3>
                <p><i class="la la-map-marker"></i><?php _che($item['address']); ?></p>
            </a>
            <ul>
                <?php
                    $custom_elements = _get_custom_items();
                    $i=0;
                    if(sw_count($custom_elements) > 0):
                    foreach($custom_elements as $key=>$elem):

                    if(!empty($item['option_'.$elem->f_id]) && $i++<3)
                    if($elem->type == 'DROPDOWN' || $elem->type == 'INPUTBOX'):
                     ?>
                        <li class="">

                            <i class="fa <?php _che($elem->f_class); ?>"></i> 

                            <?php echo _ch($item['option_'.$elem->f_id], '-'); ?> <?php echo _ch(${"options_suffix_$elem->f_id"}, ''); ?>  
                            <span style="<?php _che($elem->f_style); ?>">
                                <?php echo _ch(${"options_name_$elem->f_id"}, ''); ?>
                            </span>
                        </li>
                     <?php 
                    elseif($elem->type == 'CHECKBOX'):
                     ?>
                        <li class=""><i class="fa <?php _che($elem->f_class); ?>"></i><span class="<?php echo (!empty($item['option_'.$elem->f_id])) ? 'glyphicon glyphicon-ok':'glyphicon glyphicon-remove';  ?>"></span> <?php echo _ch(${"options_name_$elem->f_id"}, ''); ?></li>
                     <?php 
                    endif;                    
                    endforeach;  
                    else:
                ?>
                <li class=""><?php echo _ch($item['option_19'], '-'); ?> <?php echo _ch($options_name_19, '-'); ?></li>
                <li class=""><?php echo _ch($item['option_20'], '-'); ?> <?php echo _ch($options_name_20, '-'); ?></li>
                <li class=""><?php echo _ch($options_name_57, '-'); ?> <?php echo _ch($item['option_57'], '-'); ?> <?php echo _ch($options_suffix_57, '-'); ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-footer">
            <span class="favorites-actions pull-left">
                <a href="#" data-id="<?php echo _ch($item['id']); ?>" class="add-to-favorites" style="<?php echo ($item['is_favorite'])?'display:none;':''; ?>">
                    <i class="la la-heart-o"></i>
                </a>
                <a href="#" data-id="<?php echo _ch($item['id']); ?>" class="remove-from-favorites" style="<?php echo (!$item['is_favorite'])?'display:none;':''; ?>">
                    <i class="la la-heart-o"></i>
                </a>
                <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
            </span>
            <a href="#" title='<?php echo sw_in_date_format(_ch($item['date'])); ?>' class="pull-right">
                <i class="la la-calendar-check-o"></i> 
                <?php 
                    $date_modified = $item['date'];
                    $date_modified_str = strtotime($date_modified);
                    echo human_time_diff($date_modified_str);
                ?>
            </a>
        </div>
        <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>" class="ext-link"></a>
    </div>
</div>
              