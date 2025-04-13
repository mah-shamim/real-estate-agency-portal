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
                        <h2 class="title"><?php echo lang_check('My messages'); ?></h2>
                    </div> <!-- ./ title --> 
                    <div class="content-box">
                        <div class="validation m25">
                            <?php if($this->session->flashdata('message')):?>
                            <?php echo $this->session->flashdata('message')?>
                            <?php endif;?>
                            <?php if($this->session->flashdata('error')):?>
                            <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                            <?php endif;?>
                        </div>
                        <table class="table table-striped data_table" data-sorting="true">
                            <thead>
                                <th data-priority="1">#</th>
                                <th data-priority="1" data-type="html"><?php _l('Date');?></th>
                                <th data-priority="2"><?php _l('Mail');?></th>
                                <th data-priority="2"><?php _l('Message');?></th>
                                <th data-priority="2"><?php _l('Estate');?></th>
                                <th data-priority="1" data-orderable="false"><?php _l('Edit');?></th>
                                <th data-priority="1" data-orderable="false"><?php _l('Delete');?></th>
                            </thead>
                            <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                <tr>
                                    <td><?php echo $listing_item->id; ?>&nbsp;&nbsp;<?php echo $listing_item->readed == 0? '<span class="label label-warning">'.lang_check('Not readed').'</span>':''?></td>
                                    <td><?php echo $listing_item->date; ?></td>
                                    <td><?php echo $listing_item->mail; ?></td>
                                    <td><?php echo $listing_item->message; ?></td>
                                    <td><?php echo $all_estates[$listing_item->property_id]; ?></td>
                                    <td><?php echo btn_edit('fmessages/edit/'.$lang_code.'/'.$listing_item->id)?></td>
                                    <td><?php echo btn_delete('fmessages/delete/'.$lang_code.'/'.$listing_item->id)?></td>
                                </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <?php endif;?>     
                        </table>
                    </div>
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