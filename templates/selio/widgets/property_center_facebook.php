<?php if(!empty($settings_facebook_comments)): ?>
<div class="details-info details-info-transparent">
    <h3><?php echo lang_check( 'Facebook comments'); ?></h3>
    <?php echo str_replace('http://example.com/comments', $page_current_url, $settings_facebook_comments); ?>
</div><!-- /. widget-facebook -->   
<?php endif;?>