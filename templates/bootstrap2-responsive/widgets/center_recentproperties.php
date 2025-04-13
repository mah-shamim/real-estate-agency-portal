<div class="results-properties-list with-sidebar">
    <h2>{lang_Realestates}: <?php echo $total_rows; ?></h2>
    <div class="options">
        <a class="view-type active hidden-phone" data-ref="grid" href="#"><img src="assets/img/glyphicons/glyphicons_156_show_thumbnails.png" alt="" /></a>
        <a class="view-type hidden-phone" data-ref="list" href="#"><img src="assets/img/glyphicons/glyphicons_157_show_thumbnails_with_lines.png" alt="" /></a>

        <select class="span3 selectpicker-small pull-right">
            <option value="id ASC" {order_dateASC_selected}>{lang_DateASC}</option>
            <option value="id DESC" {order_dateDESC_selected}>{lang_DateDESC}</option>
            <option value="price ASC" {order_priceASC_selected}>{lang_PriceASC}</option>
            <option value="price DESC" {order_priceDESC_selected}>{lang_PriceDESC}</option>
        </select>
        <span class="pull-right" style="padding-top: 5px;">{lang_OrderBy}&nbsp;&nbsp;&nbsp;</span>
    </div>

    <br style="clear:both;" />

    <div class="row-fluid">
        {has_no_results}
        <ul class="thumbnails">
        <li class="span12">
        <div class="alert alert-success">
        {lang_Noestates}
        </div>
        </li>
        </ul>
        {/has_no_results}

        <?php foreach($results as $key=>$item): ?>
        <?php
           if($key==0)echo '<ul class="thumbnails">';
        ?>
            <?php _generate_results_item(array('key'=>$key, 'item'=>$item)); ?>
        <?php
           if( ($key+1)%4==0 )
            {
                echo '</ul><ul class="thumbnails">';
            }
            if( ($key+1)==sw_count($results) ) echo '</ul>';
            endforeach;
        ?>
        
      </div>
      <div class="pagination properties">
      {pagination_links}
      </div>
</div>