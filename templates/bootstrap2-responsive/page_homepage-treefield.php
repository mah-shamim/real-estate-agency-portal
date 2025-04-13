<?php
$CI = & get_instance();
$treefield_id = 64;

$CI->load->model('treefield_m');

$treefields = array();

$tree_listings = $CI->treefield_m->get_table_tree($lang_id, $treefield_id, NULL, FALSE, '.order', ',image_filename');
$_treefields = $tree_listings[0];

$treefields = array();
foreach ($_treefields as $val) {

    $options = $tree_listings[0][$val->id];
    $treefield = array();
    $field_name = 'value' ;
    $treefield['title'] = $options->$field_name;
    $treefield['title_chlimit'] = character_limiter($options->$field_name, 15);

    $field_name = 'body';
    $treefield['descriotion'] = $options->$field_name;
    $treefield['description_chlimit'] = character_limiter($options->$field_name, 50);
    
    $treefield['url'] = '';
    
    /* link if have body */
    if(!empty($options->$field_name))
    {
        $href = slug_url('treefield/'.$lang_code.'/'.$options->id.'/'.url_title_cro($options->value), 'treefield_m');
        $treefield['url'] = $href;
    }
    /* end if have body */
    
    // Thumbnail and big image
    if(!empty($options->image_filename) and file_exists(FCPATH.'files/thumbnail/'.$options->image_filename))
    {
        $treefield['thumbnail_url'] = base_url('files/thumbnail/'.$options->image_filename);
        $treefield['image_url'] = base_url('files/'.$options->image_filename);
    }
    else
    {
        $treefield['thumbnail_url'] = 'assets/img/no_image.jpg';
        $treefield['image_url'] = 'assets/img/no_image.jpg';
    }
    
    $childs = array();
    if (isset($tree_listings[$val->id]) && sw_count($tree_listings[$val->id]) > 0)
        foreach ($tree_listings[$val->id] as $key => $_child) {
            $child = array();
            $options = $tree_listings[$_child->parent_id][$_child->id];
            $field_name = 'value';
            $child['title'] = $options->$field_name;
            $child['title_chlimit'] = character_limiter($options->$field_name, 10);

            $field_name = 'body';
            $child['descriotion'] = $options->$field_name;
            $child['descriotion_chlimit'] = character_limiter($options->$field_name, 50);
            
            $child['url'] = '';
            $href = slug_url($lang_code.'/6/'.url_title_cro('map', '-', TRUE), 'page_m');
            
            $child['url'] = '';
            
            /* link if have body */
                if(!empty($options->$field_name))
                {
                    // If slug then define slug link
                    $href = slug_url('treefield/'.$lang_code.'/'.$options->id.'/'.url_title_cro($options->value), 'treefield_m');
                    $child['url'] = $href;
                }
            /* end if have body */
            
            $childs[] = $child;
        }

    $treefield['childs'] = $childs;
    $treefield['childs_4'] = array_slice($childs, 0, 4);
    $treefields[] = $treefield;
}
?>

<!DOCTYPE html>
<html lang="{lang_code}">
    <head>
        <?php _widget('head'); ?>
    </head>

    <body>
        {template_header}

        <?php _subtemplate('headers', _ch($subtemplate_header, 'map_and_search')); ?>

        <?php _widget('top_ads'); ?>
        <a id="content"></a>
        <div class="wrap-content">
            <div class="container">
                <div class="row-fluid">
                    <div class="span9">
                        <div class="results-properties-list with-sidebar clearfix">
                            <h2><?php echo lang_check('Treefield');?></h2>
                            <div class="row-fluid  treefield-list clearfix" style="margin-top: -0.5%;">
                                <?php foreach ($treefields as $key=>$item): ?>
                                    <?php
                                       if($key==0)echo '<div class="row-fluid">';
                                    ?>

                                    <div class='span4 treefield-item'>
                                        <div class='treefield-item-box thumbnail'>

                                                <?php if(!empty($item['url'])) : ?>
                                                <div class="tf-title"><a href="<?php _che($item['url']);?>"><?php _che($item['title_chlimit']); ?></a></div>
                                                <div class="tf-image">
                                                    <a href="<?php _che($item['url']);?>">
                                                        <img src="<?php echo _simg($item['thumbnail_url'], '275x150'); ?>" alt=""/>
                                                    </a>
                                                </div>
                                                <?php else: ?> 
                                                <div class="tf-title"><?php _che($item['title_chlimit']); ?></div>
                                                <div class="tf-image">
                                                    <img src="<?php echo _simg($item['thumbnail_url'], '275x150'); ?>" alt=""/>
                                                </div>
                                                <?php endif;?>
                                            <div class="tf-description"><?php _che($item['description_chlimit']); ?></div>
                                            <div class="pull-right tf-item-dist">
                                                <span class='title'><?php echo lang_check('Top destination') ?>: </span>
                                                <ul >
                                                    <?php if (sw_count($item['childs_4']) > 0) foreach ($item['childs_4'] as $child): ?>
                                                        <li>
                                                            <?php if(!empty($child['url'])): ?>
                                                               <a href='<?php _che($child['url']); ?>'><?php _che($child['title']); ?></a>
                                                            <?php else:?>
                                                               <span><?php _che($child['title']); ?></span>
                                                            <?php endif;?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <br style="clear:both;" />
                                            <div class="tf-item-dist-other">
                                                <div><span class="title">
                                                    <?php if (sw_count($item['childs']) > 0): ?>    
                                                        <?php echo lang_check('Other destination'); ?>:
                                                    <?php else: ?>
                                                        <?php //echo lang_check('No other destinations'); ?>
                                                    <?php endif; ?>
                                                    </span></div>
                                                <div class='tf-item-childs'>
                                                    <?php if (sw_count($item['childs']) > 0):
                                                            end($item['childs']);
                                                          $lastElementKey = key($item['childs']);
                                                    
                                                          foreach ($item['childs'] as $key_c=>$child): ?>
                                                        <?php if(!empty($child['url'])): ?>
                                                           <a href='<?php _che($child['url']); ?>'><?php _che($child['title']); ?></a>
                                                        <?php else:?>
                                                           <span><?php _che($child['title']); ?></span>
                                                        <?php endif;
                                                        if($lastElementKey != $key_c)echo ' - ';
                                                        ?>
                                                        
                                                    <?php endforeach;endif; ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                
                                    <?php
                                       if( ($key+1)%3==0 )
                                        {
                                            echo '</div><div class="row-fluid">';
                                        }
                                        if( ($key+1)==sw_count($treefields) ) echo '</div>';
                                    ?>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                    <div class='span3'>
                        <?php _widget('right_featuredproperties');?>
                    </div>
                </div>
            </div>
        </div>

        <?php _widget('bottom_defaultcontent'); ?>
        <?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>
        <?php _widget('custom_javascript'); ?> 
        <script>
            
            $('document').ready(function(){
                $('.treefield-item .tf-item-dist-other .title').on('click', function(){
                    $(this).closest('.treefield-item').find('.tf-item-childs').slideToggle('fast');
                    $('.treefield-item').not($(this).closest('.treefield-item')).find('.tf-item-childs').slideUp('fast')
                })
            })
            
        </script>
    </body>
</html>