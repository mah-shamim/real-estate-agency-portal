                <?php if(config_item('enable_table_calendar') === TRUE): ?>
                    
                
                <?php
$CI =& get_instance();
$CI->load->model('trates_m');
$t_rows = $CI->trates_m->get_property_rows($property_id, $lang_id);
         
foreach($t_rows as $table_row_index => $row_title)
{
    $available_dates = array();
    $cal_data = array();
    
    $available_dates = $CI->trates_m->get_dates($property_id, $table_row_index);
    $available_dates = explode("\n", $available_dates);
    
    //if(sw_count($available_dates) <=1)
    //    continue;
    
    echo '<div id="row_index_'.$table_row_index.'" title="'.$row_title.'" rel="'.$table_row_index.'" class="acal_rindex" style="display:none;"><h2>{lang_AvailabilityCalender} :: '.$row_title.'</h2><div class="av_calender">';
    
    $rates_table = $CI->trates_m->get_rates($property_id, $table_row_index);
    
    if(!empty($rates_table)):
?>

<div class="form_rate_table">

<table class="table table-striped">
<thead> <tr> <th><?php _l('From date'); ?></th> <th><?php _l('To date'); ?></th> <th><?php _l('Min stay'); ?></th> <th><?php _l('Price'); ?></th> <th></th> </tr> </thead>
<tbody id="rates_table_body"> 
<?php echo $rates_table; ?>
</tbody> </table>
</div>

<?php
    endif;
    
    foreach($available_dates as $key=>$row_time)
    {
        if(empty($row_time))continue;
        
        $row_time = strtotime($row_time);
        
        //$cal_data[date("m", $row_time)][date("j", $row_time)] = 
        //    'class="available selectable" data-ref="'.date("Y-m-d", $row_time).'" ref_to="'.date("Y-m-d", strtotime(date("Y-m-d", $row_time).' +7 day')).'"';
    
        $cal_data[date("m", $row_time)][date("j", $row_time)] = 
            'class="available not_selectable" data-ref="'.date("Y-m-d", $row_time).'" ref_to="'.date("Y-m-d", strtotime(date("Y-m-d", $row_time).' +7 day')).'"';
    
    }
    
    for($i=0;$i < 12; $i++)
    {
        $next_month_time = strtotime("+$i month", strtotime(date("F") . "1"));
    
        if(!isset($cal_data[date("m", $next_month_time)]))
            $cal_data[date("m", $next_month_time)] = array();
    
        echo '<div class="month_container">'.$this->calendar->generate(date("Y", $next_month_time), 
                                       date("m", $next_month_time),
                                       $cal_data[date("m", $next_month_time)]).'</div>';
    }
    
    echo '<br style="clear: both;" /></div></div>';
}
                ?>

<script>

var def_message_f1 = '';
var cal_titles = [];

$(document).ready(function(){
	
    def_message_f1 = $('.property-form textarea').val();
    
    $('div.acal_rindex').hide();
    
    $("#table_field_76 tbody tr").each(function( index ) {
        var title = $(this).find("td:first").html();
        cal_titles[index] = title;
    });
    
	$("div.acal_rindex").each(function( index ) {

        var row_index = $(this).attr('rel');
        var title = $("#table_field_76 tbody tr:nth-child("+row_index+") td:first").html();

        title = '<a class="t_cal_link btn btn-warning" rel="'+row_index+'" href="#"><img style="vertical-align:top;margin-top:2px;" src="assets/img/euro30.png" alt="" /> <i class="icon-calendar icon-white"></i>&nbsp;'+title+'</a>';
        $("#table_field_76 tbody tr:nth-child("+row_index+") td:first").html(title);
        
        if(index == 0)
        {
            //$('div#row_index_'+row_index).show();
        }
        
        $("a.t_cal_link").click(function(){
            var row_index2 = $(this).attr('rel');
            $('div.acal_rindex').hide();
            $('div#row_index_'+row_index2).show();
            $('.property-form textarea').val(def_message_f1+': '+cal_titles[$(this).parent().parent().index()]);
            return false;
        });
    });
    
    $('#table_field_76 tr td').click(function(){
        
        //console.log(cal_titles);
        //console.log($(this).parent().index());
        if($(this).index()==0)
        $('.property-form textarea').val(def_message_f1+': '+cal_titles[$(this).parent().index()]);
        
    });
    
});


</script>
                <?php endif; ?>