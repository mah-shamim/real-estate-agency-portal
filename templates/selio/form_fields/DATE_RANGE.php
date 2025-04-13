<?php
if(function_exists('sw_filter_search_slidetoggle')) 
sw_filter_search_slidetoggle();
?>
<?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
    <div class="row" >
        <div class="col-sm-6">
            <div class="form_field">
                <div class="form-group field_select field_select_booking_date_from">
                    <input id="booking_date_from" type="text" class="form-control" value="<?php echo search_value('date_from'); ?>"  placeholder="<?php _l('Fromdate'); ?>" autocomplete="off" />
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form_field">
                <div class="form-group field_select field_select_booking_date_to">
                    <input id="booking_date_to" type="text" class="form-control" value="<?php echo search_value('date_to'); ?>"  placeholder="<?php _l('Todate'); ?>" autocomplete="off" />
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
