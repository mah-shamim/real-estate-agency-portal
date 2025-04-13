<!doctype html>
<html class="no-js" lang="en">
<head>
<?php _widget('head'); ?>
    <style>
        #codeigniter_profiler {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="wrapper half_map">
      	<header class="fix">
            <?php _widget('header_bar'); ?>
            <?php _widget('header_main_panel'); ?>
        </header><!--header end-->
        <section class="half-map-sec">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <div id="map-container" class="fullwidth-home-map">
                            <div id="main-map" data-map-zoom="9"></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12">
                        <div class="widget-property-search widget_edit_enabled">
                            <form action="#" class="row banner-search search-form">
                                {is_logged_other}
                                <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                                <div class="widget-controls-panel widget_controls_panel" data-widgetfilename="right_filterform">
                                    <a href="<?php echo site_url('admin/forms/edit/5');?>" target="_blunk" class="btn btn-edit"><i class="ion-edit"></i></a>
                                </div>
                                <?php endif;?>
                                {/is_logged_other}
                                <?php _search_form_primary(5) ;?> 
                                <div class="feat-srch">
                                    <div class="more-feat">
                                        <h3> <i class="la la-cog"></i> <?php echo lang_check('Show More Features')?></h3>
                                    </div><!--more-feat end-->
                                    <div class="form_field <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')):?> form_field_save <?php endif;?>">
                                        <div class="form_field_row">
                                            <button class="btn btn-outline-primary sw-search-start" type="submit">
                                                <span><?php echo lang_check('Search');?><i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i></span>
                                            </button>
                                            <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                                                <button type="button" id="search-save" class="btn btn-custom btn-savesearch btn-custom-secondary btn-icon"><i class="fa fa-save icon-white fa-ajax-hide"></i><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></button>
                                            <?php endif; ?>
                                        </div> 
                                    </div>
                                </div><!--more-feat end-->
                                <div class="features_list">
                                    <div class="group">
                                        <?php  _search_form_secondary(5); ?>
                                    </div>
                                </div><!--features end-->
                            </form>
                        </div><!--widget widget-property-searche end-->
                        <div class="listing-directs">
    
                        <?php

                        $order_dropdown_def = 'id DESC';
                        if(isset($_MERG['search_order']) && !empty($_MERG['search_order']))
                        {
                            $order_dropdown_def = $_MERG['search_order'];
                        }

                        $order_dropdown = array('id DESC'    => lang_check('Relevant'),
                                                'id ASC'   => lang_check('Oldest'),
                                                'counter_views DESC, id DESC' => lang_check('Most View'),
                                                'field_36_int DESC, id DESC' => lang_check('Higher price'),
                                                'field_36_int ASC, id DESC'=> lang_check('Lower price'));

                        ?>

                        <div class="list-head">
                            <div class="sortby">
                                <span><?php echo lang_check('Sort by');?>:</span>
                                <div class="drop-menu">
                                    <div class="select">
                                        <span><?php echo $order_dropdown[$order_dropdown_def];?></span>
                                        <i class="la la-caret-down"></i>
                                    </div>
                                    <input type="hidden" name="search_order" id="search_order">
                                    <ul class="dropeddown">
                                         <?php if(sw_count($order_dropdown)>0) foreach ($order_dropdown as $key => $value):?>
                                            <li data-value="<?php echo $key;?>"><?php echo $value;?></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                            </div><!-- sortby end-->
                            <div class="view-count">
                                <?php echo lang_check('Results');?>: <span class="total_rows"><?php echo $total_rows; ?></span>
                            </div><!--view-change end-->
                            <div class="view-change">
                                <ul class="nav nav-tabs grid-type">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link grid view-type active <?php _che($view_grid_selected); ?>" data-ref="grid"><i class="la la-th-large"></i></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link list view-type <?php _che($view_list_selected); ?>" data-ref="list"><i class="la la-th-list"></i></a>
                                    </li>
                                </ul>
                            </div><!--view-change end-->
                        </div><!--list-head end-->

                        <div class="results-container result_preload_box" id="results_conteiner">
                            {has_no_results}
                            <div class="list_products">
                                <div class="alert alert-info" role="alert"><?php echo lang_check('Results not found'); ?></div>
                            </div>
                            {/has_no_results}
                            <div class="list_products">
                                <div class="row">
                                <?php foreach($results as $key=>$item): ?>
                                    <?php _generate_results_item(array('key'=>$key, 'item'=>$item, 'custom_class'=>'')); ?>
                                <?php endforeach;?>
                                </div>
                            </div>
                            <nav aria-label="Page navigation example" class="pagination properties">
                                {pagination_links}
                            </nav><!--pagination end-->

                            <div class="result_preload_indic"></div>
                        </div><!--tab-content end-->
                        </div><!---directs end-->
                    </div>
                </div>
            </div>
        </section><!--half-map-sec end-->
    </div><!--wrapper end-->
    <?php
    /* dinamic per listing */
    _generate_js('_generate_top_map_js_'.md5(current_url_q()), 'widgets/_generate_top_map_js.php', false, 0);
    ?>
    <?php _widget('custom_javascript'); ?>
</body>
</html>