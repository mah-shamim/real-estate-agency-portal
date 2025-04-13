<div class="row-fluid">
    <?php foreach($featured_properties as $key=>$item): ?>
    <?php
       if($key==0)echo '<ul class="thumbnails">';
    ?>
        <?php _generate_results_item(array('key'=>$key, 'item'=>$item,'icons'=>false, 'view_counter'=>false)); ?>
    <?php
       if( ($key+1)%4==0 )
        {
            echo '</ul><ul class="thumbnails">';
        }
        if( ($key+1)==sw_count($featured_properties) ) echo '</ul>';
        endforeach;
    ?>
</div>