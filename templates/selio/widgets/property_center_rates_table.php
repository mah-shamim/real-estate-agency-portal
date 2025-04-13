<?php if(file_exists(APPPATH.'controllers/admin/booking.php') && sw_count($property_rates)>0):?>
    <div class="features-dv widget-rates">
    <h3><?php echo lang_check('Rates');?></h3>
<table class="table table-striped">
    <thead>
    <tr>
    <th>{lang_From}</th>
    <th>{lang_To}</th>
    <th>{lang_Nightly}</th>
    <th>{lang_Weekly}</th>
    <th>{lang_Monthly}</th>
    <th>{lang_MinStay}</th>
    <th>{lang_ChangeoverDay}</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($property_rates as $key=>$rate): ?>
    <tr>
    <td><?php echo date('Y-m-d', strtotime($rate->date_from)); ?></td>
    <td><?php echo date('Y-m-d', strtotime($rate->date_to)); ?></td>
    <td><?php echo $rate->rate_nightly.' '.$rate->currency_code; ?></td>
    <td><?php echo $rate->rate_weekly.' '.$rate->currency_code; ?></td>
    <td><?php echo $rate->rate_monthly.' '.$rate->currency_code; ?></td>
    <td><?php echo $rate->min_stay; ?></td>
    <td><?php echo $changeover_days[$rate->changeover_day]; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<h2>{lang_AvailabilityCalender}</h2>
<div class="av_calender row">
<?php
    $row_break=0;

    foreach($months_availability as $v_month)
    {
        echo '<div class="month_container col-sm-4">'.$v_month.'</div>';

        $row_break++;
        //if($row_break%3 == 0)
        //echo '<div style="clear: both;height:10px;"></div>';
    }
?>
<br style="clear: both;" />
</div>
</div> 
<?php endif;?>