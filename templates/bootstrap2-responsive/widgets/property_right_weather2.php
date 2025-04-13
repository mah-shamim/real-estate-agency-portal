<?php
/*
 * API https://api.forecast.io
 * icon http://www.iconarchive.com/tag/weather
 * 
 */

$_CI = & get_instance();
$_CI->load->model('weathercacher_m');

?>

<h2><?php echo _l('Weather');?></h2>
<div class="weather-box" id='weather-2'>

<?php if(!$_CI->weathercacher_m->check_expire_status($property_id, 'forecast', $lang_id)):?> 
    <?php  
    //gps
    if(strrpos($estate_data_gps,','))
        list($lat, $lon)=explode(',', $estate_data_gps);
    
    if(!empty($lat) && !empty($lon)):
    //API KEY
    $api_key= '15c003466a47e24e132ce7758d3ee661';
    $json_api= 'https://api.forecast.io/forecast/'.$api_key.'/'.trim($lat).','.trim($lon).'?lang='.$lang_code.'&units=si';
    ?>
    <script>
    $('document').ready(function(){

            // days translatable
            var days = {
                0: "<?php echo _l('cal_sunday');?>",
                1: "<?php echo _l('cal_monday');?>",
                2: "<?php echo _l('cal_tuesday');?>",
                3: "<?php echo _l('cal_wednesday');?>",
                4: "<?php echo _l('cal_thursday');?>",
                5: "<?php echo _l('cal_friday');?>",
                6: "<?php echo _l('cal_saturday');?>",
            }
        $.ajax({
            url: '<?php echo $json_api;?>',
            dataType: 'jsonp',
            success: function(data){
                var _data ='';

                var weather_count=0;
                if(data.daily.data)
                $.each(data.daily.data, function(index, value){
                    var day = new Date(value.time*1000);
                    var day =day.getDay();
                    _data+='<div class="weather-item">\n\
                                <div class="date">'+days[day]+'</div>\n\
                                <div class="weather-img">\n\
                                    <img src="assets/img/weather/custom/'+value.icon+'.png" alt="" />\n\
                                </div>\n\
                                <div class=""><?php echo _l('Low');?>: '+value.temperatureMin+' °C</div>\n\
                                <div class=""><?php echo _l('Hight');?>: '+value.temperatureMax+' °C</div>\n\
                                <div class="description">'+value.summary+'</div>\n\
                            </div>'
                            weather_count++;
                            if(weather_count>2)  return false;
    
                      } )
                $('.weather-box#weather-2').html(_data)
                
                /* save cache */
                    data_api ={
                        'value':JSON.stringify(data),
                        'weather_api':'forecast',
                    }
                    $.post("<?php echo site_url('api/save_weather_cache/'.$property_id.'/'.$lang_code) ;?>", data_api, 
                           function(data_api){
                    });
               }
        });
    })
    </script>
    <?php endif;?>
<?php else:?>
    <?php
    $weather = json_decode($_CI->weathercacher_m->get_cache($property_id, 'forecast', $lang_id));
    ?>
    <?php $weather_count=0; foreach ($weather->daily->data as $key => $value):?>
    <?php if(date('z',$value->time)>=date('z')):?>
    <div class="weather-item">
        <div class="date"><?php echo _l('cal_'.strtolower(date('l',$value->time)));?></div>
        <div class="weather-img">
            <img src="assets/img/weather/custom/<?php _che($value->icon);?>.png" alt="" />
        </div>
        <div class=""><?php echo _l('Low');?>: <?php  echo round($value->temperatureMin);?> °C</div>
        <div class=""><?php echo _l('Hight');?>: <?php echo round($value->temperatureMax);?> °C</div>
        <div class="description"><?php _che($value->summary);?></div>
    </div>
    <?php endif;?>
    <?php $weather_count++;if($weather_count>2)break; endforeach;?>

<?php endif; ?>
</div>