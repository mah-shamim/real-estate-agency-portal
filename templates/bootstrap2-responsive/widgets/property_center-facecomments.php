<?php if(!empty($settings_facebook_comments)): ?>
<h2><?php echo lang_check('Facebook comments'); ?></h2>
<?php echo str_replace('http://example.com/comments', $page_current_url, $settings_facebook_comments); ?>
<?php endif;?>




