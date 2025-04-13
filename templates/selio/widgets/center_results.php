<?php
/*
Widget-title: Center results
Widget-preview-image: /assets/img/widgets_preview/center_results.webp
 */
?>
<div class="-directs">
    
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