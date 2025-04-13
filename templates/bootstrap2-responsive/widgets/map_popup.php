
<img style="width: 150px; height: 100px;" src="<?php _che($thumbnail_url, ''); ?>" alt="" />
<br />
<?php _che($address, ''); ?>
<br />
<?php _che($option_2, ''); ?>
<br />
<span class="label label-info">&nbsp;&nbsp;<?php _che($option_4, ''); ?>&nbsp;&nbsp;</span>

<?php if(!empty($option_37)): ?>
<span class="price"><?php _che($options_prefix_37, ''); ?> <?php _che($option_37, ''); ?> <?php _che($options_suffix_37, ''); ?></span>
<?php endif; ?>
<?php if(!empty($option_36)): ?>
<span class="price"><?php _che($options_prefix_36, ''); ?> <?php _che($option_36, ''); ?> <?php _che($options_suffix_36, ''); ?></span>
<?php endif; ?>
<br />
<a href="<?php echo $url; ?>"><?php _l('Details'); ?></a>
