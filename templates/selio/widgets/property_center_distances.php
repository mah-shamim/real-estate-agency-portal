<div class="details-info">
     <h3><?php echo ${'options_name_43'};?></h3>
    <ul>
    <?php if(isset($category_options_43)) foreach($category_options_43 as $key=>$row):?>
        <?php if(!empty($row['is_text'])): ?>
            <?php if(filter_var($row['option_value'], FILTER_VALIDATE_URL)):?>
                <li class="icon earth"><?php _che($row['icon']);?> <a href="<?php _che($row['option_value']);?>"><?php _che($row['option_value']);?></a></li>    
            <?php else:?>
                <li>
                    <h4><?php _che($row['icon']);?> <?php _che($row['option_name']);?>:</h4>
                    <span><?php _che($row['option_prefix']);?> <?php _che($row['option_value']);?> <?php _che($row['option_suffix']);?></span>
                </li><!-- /.property-detail-overview-item -->
            <?php endif;?>
        <?php endif;?>
        <?php if(!empty($row['is_dropdown'])): ?>
            <li>
                <h4><?php _che($row['icon']);?> <?php _che($row['option_name']);?>:</h4>
                <span class="label label-success"><?php _che($row['option_value']);?></span>
            </li><!-- /.property-detail-overview-item -->
        <?php endif;?>
        <?php if(!empty($row['is_checkbox'])): ?>
            <li>
                <h4><?php _che($row['icon']);?> <?php _che($row['option_name']);?>:</h4>
                <span><img src="assets/img/checkbox_<?php _che($row['option_value']);?>.png" alt="<?php _che($row['option_value']);?>" /></span>
            </li><!-- /.property-detail-overview-item -->
        <?php endif;?>
    <?php endforeach;?>
    </ul>
</div><!--details-info end-->