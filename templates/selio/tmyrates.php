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
  <div class="widget-panel">
                    <div class="widget-header header-styles">
                        <h2 class="title"><?php echo lang_check('My reservations and payments'); ?></h2>
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
                        <table class="table table-striped data_table">
                            <thead>
                                <th data-priority="1">#</th>
                                <th data-priority="2"><?php echo lang_check('Table row');?></th>
                                <th data-priority="2"><?php echo lang_check('Property');?></th>
                                <th data-priority="0" data-orderable="false"><?php echo lang_check('Edit');?></th>
                                <th data-priority="0" data-orderable="false"><?php echo lang_check('Delete');?></th>
                              </thead>
                              <tbody>
                                <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                <tr>
                                    <td><?php echo $listing_item->id; ?></td>
                                    <td>                                        
                                    <?php 
                                    $prows = $this->trates_m->get_property_rows($listing_item->property_id, $lang_id);
                                    if(isset($prows[$listing_item->table_row_index]))
                                    {
                                        echo $prows[$listing_item->table_row_index];
                                    }
                                    else
                                    {
                                        echo '-';
                                    }
                                    ?>
                                    </td>
                                    <td><?php echo $properties[$listing_item->property_id]; ?></td>
                                    <td><?php echo btn_edit('trates/rate_edit/'.$lang_code.'/'.$listing_item->id)?></td>
                                    <td><?php echo btn_delete('trates/rate_delete/'.$lang_code.'/'.$listing_item->id)?></td>
                                </tr>
                                <?php endforeach;?>
                                <?php else:?>
                                <?php endif;?>           
                              </tbody>
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