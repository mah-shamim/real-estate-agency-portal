<?php
/*
Widget-title: Categories menu
Widget-preview-image: /assets/img/widgets_preview/right_categories.webp
 */
?>

<?php
$treefields = generate_treefields_list(79, 'level_0');

if(empty($treefields)) {
    echo '<p class="alert alert-info">'.lang_check("Any categories are missing, please check Categories list, #widget right_categories").'</p><br/>';
    return  false;
}
?>

<div class="widget widget-catgs widget_edit_enabled">
    <h3 class="widget-title"><?php echo lang_check('Categories');?></h3>
    <ul>
        <?php foreach ($treefields as $key=>$item): ?>
            <li>
                <a href='<?php _che($item['url']); ?>' title="<?php _che($item['title']); ?>"><i class="la la-angle-right"></i><span><?php _che($item['title']); ?></span></a>
                <span><?php _che($item['count']);?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div><!--widget-catgs end-->