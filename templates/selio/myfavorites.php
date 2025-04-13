<!doctype html>
<html class="no-js" lang="en">
    <head>
        <?php _widget('head'); ?>
    </head>
    <body>
        <div >
            <header>
                <?php _widget('header_bar'); ?>
                <?php _widget('header_main_panel'); ?>
            </header><!--header end-->
             <?php _widget('top_title'); ?>
            <div class="container m-padding">
                 <div class="widget-panel widget-submit">
                    <div class="widget-header header-styles">
                        <h2 class="title"><?php echo lang_check('Saved jobs'); ?></h2>
                    </div> <!-- ./ title --> 
                    <table class="table table-striped data_table">
                        <thead>
                            <th data-priority="1">#</th>
                            <th data-priority="1"><?php echo lang_check('Property');?></th>
                            <th data-priority="3"><?php echo lang_check('Language');?></th>
                            <th data-priority="1" data-orderable="false"><?php echo lang_check('Open');?></th>
                            <th data-priority="1" data-orderable="false"><?php echo lang_check('Delete');?></th>
                        </thead>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                            <tr>
                                <td><?php echo $listing_item->id; ?></td>
                                <td><?php echo $properties[$listing_item->property_id]; ?></td>
                                <td><?php echo '['.strtoupper($listing_item->lang_code).']'; ?></td>
                                <td>
                                <a href="<?php echo site_url($listing_uri.'/'.$listing_item->property_id.'/'.$listing_item->lang_code); ?>" class="btn"><i class="icon-white icon-search"></i><?php echo lang_check('Open');?></a>
                                </td>
                                <td><?php echo btn_delete('ffavorites/myfavorites_delete/'.$lang_code.'/'.$listing_item->id)?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                        <?php endif;?>   
                    </table>
                </div> <!-- ./ widget-submit --> 
                
                
            </div>
        <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
        <?php
        /* dinamic per listing */
        _generate_js('_generate_login_page_js_' . md5(current_url_q()), 'widgets/_generate_login_page_js.php', false, 0);
        ?>
    </body>

</html>