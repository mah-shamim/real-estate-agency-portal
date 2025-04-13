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
                        <h2 class="title"><?php echo lang_check('Myresearch'); ?></h2>
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
                                <th data-priority="1"><?php echo lang_check('Parameters');?></th>
                                <th data-priority="2"><?php echo lang_check('Lang code');?></th>
                                <th data-priority="2"><?php echo lang_check('Activated');?></th>
                                <?php if(false): ?><th  data-priority="1"><?php echo lang_check('Load');?></th><?php endif;?>
                                <th  data-priority="1" data-orderable="false"><?php echo lang_check('Edit');?></th>
                                <th  data-priority="1" data-orderable="false"><?php echo lang_check('Delete');?></th>
                            </thead>
                                <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td>
                                        <?php

                                        $parameters = json_decode($listing_item->parameters);

                                        foreach($parameters as $key=>$value){
                                            if(!empty($value) && $key != 'view' && $key != 'order')
                                            echo '<span><span class="par_key">'.$key.'</span>: <b class="par_value">'.$value.'</b></span><br />';
                                        }

                                        ?>
                                        </td>
                                        <td><?php echo '['.strtoupper($listing_item->lang_code).']'; ?></td>
                                        <td>
                                            <?php echo $listing_item->activated?'<i class="icon-ok"></i>':'<i class="icon-remove"></i>'; ?>
                                        </td>
                                        <?php if(false): ?>
                                        <td>
                                        <?php if($lang_code == $listing_item->lang_code): ?>
                                        <button class="load_search btn"><i class="icon-search"></i></button>
                                        <?php else: ?>
                                        <?php echo '->'.strtoupper($listing_item->lang_code).'<-'; ?>
                                        <?php endif; ?>
                                        </td>
                                        <?php endif;?>
                                        <td><?php echo btn_edit('fresearch/myresearch_edit/'.$lang_code.'/'.$listing_item->id.'#content')?></td>
                                        <td><?php echo btn_delete('fresearch/myresearch_delete/'.$lang_code.'/'.$listing_item->id)?></td>
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