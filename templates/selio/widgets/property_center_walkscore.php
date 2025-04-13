<?php if(config_db_item('walkscore_enabled') == TRUE && !empty($estate_data_address) && !empty($estate_data_gps)): ?>
<div class="descp-text widget-listing-walkscore">
    <h3><?php _l('Walkscore');?></h3>
        <script type='text/javascript'>
        var ws_wsid = '';
        <?php
        if(!empty($estate_data_gps))
        {
            $GPS_DATA = explode(',', $estate_data_gps);

            if(sw_count($GPS_DATA) == 2)
            {
                echo "var ws_lat = '".trim($GPS_DATA[0])."';\n";
                echo "var ws_lon = '".trim($GPS_DATA[1])."';\n";
            }
        }
        else
        {
            echo "var ws_address = '$estate_data_address';";
        }
        ?>
        <?php
        $base_url = base_url();
        $url_protocol='http://';
        if(strpos( $base_url,'https')!== false){
            $url_protocol='https://';
        }
        ?> 
        var ws_width = '500';
        var ws_height = '336';
        var ws_layout = 'horizontal';
        var ws_commute = 'true';
        var ws_transit_score = 'true';
        var ws_map_modules = 'all';
        </script><div id='ws-walkscore-tile'><div id='ws-footer' style='position:absolute;top:318px;left:8px;width:488px'><form id='ws-form'><a id='ws-a' href='<?php echo $url_protocol;?>www.walkscore.com/' target='_blank'>What's Your Walk Score?</a><input type='text' id='ws-street' style='position:absolute;top:0px;left:170px;width:286px' /><input type='submit' id='ws-go' value="<?php echo lang_check('Go');?>" style='position:absolute;top:0px;right:0px' /></form></div></div><script type='text/javascript' src='<?php echo $url_protocol;?>www.walkscore.com/tile/show-walkscore-tile.php'></script>
</div>
<?php endif; ?>
