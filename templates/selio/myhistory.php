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
                        <h2 class="title"><?php echo lang_check('My History'); ?></h2>
                    </div> <!-- ./ title --> 
                    <table class="table table-striped data_table">
                      <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo lang_check('Property');?></th>
                            <th><?php echo lang_check('Date');?></th>
                            <th data-priority="1" data-orderable="false" class="control"><?php echo lang_check('Open');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($historyads)): foreach($historyads as $historyads_item):?>
                                    <tr>
                                        <td><?php echo $historyads_item->id; ?></td>
                                        <td><?php echo $properties[$historyads_item->listing_id]; ?></td>
                                        <td><?php echo $historyads_item->date; ?></td>
                                        <td>
                                        <a href="<?php echo site_url($listing_uri.'/'.$historyads_item->listing_id.'/'.$lang_code); ?>" class="btn"><i class="icon-search"></i><?php echo lang_check('Open');?></a>
                                        </td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                        <?php endif;?>           
                      </tbody>
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