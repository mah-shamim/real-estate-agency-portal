<!doctype html>
<html class="no-js" lang="en">
    <head>
        <?php _widget('head'); ?>
    </head>
    <body>
        <div class="wrapper">
            <header>
                <?php _widget('header_bar'); ?>
                <?php _widget('header_main_panel'); ?>
            </header><!--header end-->

           <?php _widget('top_contact_map');?>
           <?php _widget('top_contacts');?>
           <?php _widget('top_contact_body');?>

            <?php _widget('top_discover_banner_html'); ?>
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>

    </body>
</html>