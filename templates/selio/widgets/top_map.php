<?php
/*
Widget-title: Map with search
Widget-preview-image: /assets/img/widgets_preview/top_map.webp
*/
?>

<?php

/* dinamic per listing */
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_top_map_js.php', true, 0);
?>

<section id="map-container" class="fullwidth-home-map wmap widget_edit_enabled">
    <?php if(config_db_item('map_version') !='open_street'):?>
    <input id="pac-input" class="controls" type="text" placeholder="{lang_Search}" />
    <?php endif;?>
    <h3 class="vis-hid">Invisible</h3>
    <div id="main-map" class="map"></div>
</section>