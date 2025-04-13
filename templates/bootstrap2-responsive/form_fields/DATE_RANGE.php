<?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
<input id="booking_date_from" type="text" class="form-control span3" value="<?php echo search_value('date_from'); ?>"  placeholder="<?php _l('Fromdate'); ?>" autocomplete="off" />
<input id="booking_date_to" type="text" class="form-control span3" value="<?php echo search_value('date_to'); ?>"  placeholder="<?php _l('Todate'); ?>" autocomplete="off" />
        
<?php endif; ?>
