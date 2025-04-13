<?php if(config_item('custom_energy_widget')==TRUE && !empty($estate_data_option_59)): ?>
<h2><?php _l('Energy');?></h2>

<div class="energy_widget">
    <div class="energy_widget-graph">
        <div class="energy_widget-graph-skin">
            <div class="energy_widget-graph-pointer-box" style="width:<?php echo ($estate_data_option_59/420)*100; ?>%">
                <div class="energy_widget-graph-pointer"></div>
            </div>
        </div>
    </div>
    
    <div class="energy_widget-content">
        <ul class="energy_widget-content-list">
            <?php if(isset($category_options_79) && $category_options_count_79>0): ?>
            {category_options_79}
            <li>
                <span class="title">{option_name}</span>
                <span class="value">{option_prefix}{option_value}{option_suffix}</span>
            </li>
            {/category_options_79}
            <?php endif;?>
            <li>
                <span class="title"><?php _che($options_name_59);?></span>
                <span class="value"><?php _che($options_prefix_59);?><?php echo $estate_data_option_59;?><?php _che($options_suffix_59);?></span>
            </li>
        </ul>
    </div>
</div>

<?php endif;?>
