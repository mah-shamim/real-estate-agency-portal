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
                                <th data-priority="1"><?php _l('From date');?></th>
                                <th data-priority="2"><?php _l('To date');?></th>
                                <th data-priority="2"><?php _l('Property');?></th>
                                <th data-priority="2" ><?php _l('Client');?></th>
                                <th data-priority="3"><?php _l('Paid');?></th>
                                <th data-priority="1" ><?php _l('Available');?></th>
                            </thead>
                            <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                <tr>
                                    <td><?php echo $listing_item->id; ?></td>
                                    <td><?php echo $listing_item->date_from; ?></td>
                                    <td><?php echo $listing_item->date_to; ?></td>
                                    <td>
                                        <?php if(isset($properties[$listing_item->property_id])):?>
                                            <?php echo $properties[$listing_item->property_id]; ?>
                                        <?php endif;?>
                                    </td>
                                    <td><?php echo $listing_item->buyer_username; ?></td>
                                    <td>
                                    <?php if($listing_item->total_paid == $listing_item->total_price): ?>
                                    <span class="label label-success"><?php echo $listing_item->total_paid.'/'.$listing_item->total_price.' '.$listing_item->currency_code; ?></span>
                                    <?php elseif($listing_item->total_paid > 0):?>
                                    <span class="label label-warning"><?php echo $listing_item->total_paid.'/'.$listing_item->total_price.' '.$listing_item->currency_code; ?></span>
                                    <?php else: ?>
                                    <span class="label label-default"><?php echo $listing_item->total_paid.'/'.$listing_item->total_price.' '.$listing_item->currency_code; ?></span>
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                    <?php
                                        $earned = $listing_item->total_paid-$listing_item->total_paid*$listing_item->booking_fee/100;

                                        if(strtotime($listing_item->date_from)+24*60*60 < time())
                                        {
                                            echo $earned.' '.$listing_item->currency_code;
                                        }
                                        else
                                        {
                                            echo '-';
                                        }
                                     ?></td>
                                </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <?php endif;?>           
                        </table>
                        <div class="pagination">
                            <?php echo $pagination_links; ?>
                        </div>
                    </div>
                </div> <!-- ./ widget-submit --> 
                <div class="widget-panel widget-submit">
                    <div class="widget-header header-styles">
                        <h2 class="title"><?php _l('Last 5 withdrawal request'); ?></h2>
                    </div> <!-- ./ title --> 
                    <div class="content-box">
                        <div class="validation m25">
                            <span>
                            <?php _l('You can withdraw up to:'); ?>
                            <?php
                                $index=0;

                                if(sw_count($withdrawal_amounts) == 0)echo '0';

                                foreach($withdrawal_amounts as $currency=>$amount)
                                {
                                    echo '<span class="label label-success">'.$amount.' '.$currency.'</span>&nbsp;';
                                }
                            ?>
                            <?php _l('Waiting to check in pass 1 day:'); ?>
                            <?php
                                $index=0;

                                if(sw_count($pending_amounts) == 0)echo '0';

                                foreach($pending_amounts as $currency=>$amount)
                                {
                                    echo '<span class="label label-info">'.$amount.' '.$currency.'</span>&nbsp;';
                                }
                            ?>
                            </span>
                            <?php if(sw_count($withdrawal_amounts) > 0): ?>
                            <?php echo anchor('rates/make_withdrawal/'.$lang_code.'#content', '<i class="icon-briefcase icon-white"></i>&nbsp;&nbsp;'.lang_check('Make a withdrawal'), 'class="btn btn-primary pull-right"')?>
                            <?php endif;?>
                            
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
                                <th data-priority="1"><?php _l('Email');?></th>
                                <th data-priority="2"><?php _l('Date requested');?></th>
                                <th data-priority="2"><?php _l('Date completed');?></th>
                                <th data-priority="2"><?php _l('Amount');?></th>
                                <th data-priority="1"><?php _l('Completed');?></th>
                            </thead>
                            <tbody>
                                <?php if(sw_count($listings_withdrawal)): foreach($listings_withdrawal as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id_withdrawal; ?></td>
                                        <td><?php echo $listing_item->withdrawal_email; ?></td>
                                        <td><?php echo $listing_item->date_requested; ?></td>
                                        <td><?php echo $listing_item->date_completed; ?></td>
                                        <td><?php echo $listing_item->amount.' '.$listing_item->currency; ?></td>
                                        <td>
                                        <?php 
                                        if($listing_item->completed)
                                        {
                                            echo '<span class="label label-success"><i class="icon-ok icon-white"></i></span>';
                                        }
                                        else
                                        {
                                            echo '<span class="label label-important"><i class="fa fa-remove"></i></span>';
                                        }
                                        ?>
                                        </td>
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