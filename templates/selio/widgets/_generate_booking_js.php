<script>
    <?php $dates_list = ''; if(isset($available_dates) && file_exists(APPPATH.'controllers/admin/booking.php')): ?>
    var dates_list = [];
    <?php foreach($available_dates as $date_format => $unix_format): ?>
    <?php
        $dates_list.='"'.$date_format.'", ';
    ?>
    <?php endforeach; ?>
    <?php
        if($dates_list != '')
            $dates_list = substr($dates_list, 0, -2);
    ?>dates_list = [<?php echo $dates_list; ?>];
    <?php endif; ?>
</script>