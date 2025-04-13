<h2><?php _l('Featuredproperties'); ?></h2>
    <?php foreach($featured_properties as $key=>$item): ?>
   <div class="agent featured_properties">
       <div class="image"><a href="<?php echo $item['url']; ?>"><img src="<?php echo _simg($item['thumbnail_url'], '95x89'); ?>" alt="{name_surname}" /></a></div>
       <div class="name"><a href="<?php echo $item['url']; ?>"><?php echo _ch($item['option_10']); ?></a></div>
       <div class="phone"><?php echo _ch($item['option_40'], ''); ?> <?php echo _ch($item['option_7'], ''); ?></div>
       <div class="mail">
           <?php if(!empty($item['option_36']) || !empty($item['option_37'])): ?>
                <?php 
                    if(!empty($item['option_36']))echo $options_prefix_36.price_format($item['option_36'], $lang_id).$options_suffix_36;
                    if(!empty($item['option_37']))echo ' '.$options_prefix_37.price_format($item['option_37'], $lang_id).$options_suffix_37
                ?>
            <?php endif; ?>
       </div>
   </div>
   <?php endforeach;?>