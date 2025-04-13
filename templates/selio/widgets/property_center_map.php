<div class="map-dv widget-listing-map">
    <h3><?php _l('Propertylocation');?></h3>
    <div id="" class="fullwidth-home-map">
        <?php if(!empty($estate_data_gps)): ?>
        <div class="places_select" style="display: none;">
            <a class="btn btn-large" data-rel="hospital,health"><img src="assets/img/places_icons/hospital.png" alt='hospital,health'/> <?php _l('Health');?></a>
            <a class="btn btn-large" data-rel="park"><img src="assets/img/places_icons/park.png" alt='park' /> <?php _l('Park');?></a>
            <a class="btn btn-large" data-rel="atm,bank"><img src="assets/img/places_icons/atm.png" alt='atm'/> <?php _l('ATMBank');?></a>
            <a class="btn btn-large" data-rel="gas_station"><img src="assets/img/places_icons/petrol.png" alt="gas_station"/> <?php _l('PetrolPump');?></a>
            <a class="btn btn-large" data-rel="food,bar,cafe,restourant"><img src="assets/img/places_icons/restourant.png" alt="food" /> <?php _l('Restourant');?></a>
            <a class="btn btn-large" data-rel="store"><img src="assets/img/places_icons/store.png" alt="store"/> <?php _l('Store');?></a>
        </div>
        <div class="property-map" id='property-map' style='height: 385px;'></div>
        <?php else: ?>
            <p class="alert alert-success"><?php _l('Not available'); ?></p>
        <?php endif;?>
        <form class="route_suggestion local-form form-inline" action="">
            <input id="route_from" class="inputtext w360 form-spc" type="text" value="" placeholder="<?php echo lang_check('Type your address');?>" name="route_from" required="required" />
            <button id="route_from_button" href="#" class="btn-default submit btn-spc"><?php echo _l('Suggest route');?></button>
        </form>
    </div>
</div>

<?php

/* dinamic per listing */
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_property_center_map_js.php');

?>