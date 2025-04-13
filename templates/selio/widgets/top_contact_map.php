<?php
/*
Widget-title: Contact map
Widget-preview-image: /assets/img/widgets_preview/top_contact_map.jpg
 */
?>
<section id="map-container" class="fullwidth-home-map hp3">
    <h3 class="vis-hid">Invisible</h3>
    <div id="contact-map" data-map-zoom="9" style="height: 100%"></div>
</section>

<?php
/* dinamic per listing */
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_contact_page_js.php', true, 0);
?>
