<?php

//API KEY, get your api key on: http://openweathermap.org/appid
$api_key = 'ace65559040c2beeeb9f7ed82657b365';

// For temperature in Fahrenheit use imperial
// For temperature in Celsius use metric
// Temperature in Kelvin is used by default, no need to use units parameter in API call
$units = 'metric';

// Any wanted symbol
$unit_symbol = '°C';

/*
 * API http://api.openweathermap.org
 * icon http://openweathermap.org/weather-conditions
 * 
 */

if(config_item('weather_enabled') === TRUE):
$_CI = & get_instance();
$_CI->load->model('weathercacher_m');

?>

<div class="panel panel-default panel-sidebar-1">
    <div class="panel-heading"><h2><?php echo _l('Weather');?></h2></div>
    <div class="panel-body text-center">

<div class="weather-box" id='weather-1'>
    
<?php if(!$_CI->weathercacher_m->check_expire_status($property_id, 'openweathermap', $lang_id)):?> 
    <?php   
    //gps
    if(strrpos($estate_data_gps,','))
        list($lat, $lon)=explode(',', $estate_data_gps);
    
    if(!empty($lat) && !empty($lon)):
    
    if(empty($units))
    {
        $units_arg = "";
    }
    else
    {
        $units_arg = "&units=$units";
    }
    
    $json_api = 'http://api.openweathermap.org/data/2.5/forecast/daily?lat='.trim($lat).'&lon='.trim($lon).'&cnt=8'.$units_arg.'&appid='.$api_key.'&lang='.$lang_code;
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
            
            
            $.get("<?php echo $json_api;?>", function(data){
                var _data ='';
                var weather_count=0;
                if(data.list)
                $.each( data.list, function(index, value){
                    
                    var description='';
                    switch (value.weather[0].id) {
                        case 200 :  description = "<?php echo lang_check("thunderstorm with light rain");?>";
                                    break;
                        case 201 :  description = "<?php echo lang_check("thunderstorm with rain");?>";
                                    break;
                        case 202 :  description = "<?php echo lang_check("thunderstorm with heavy rain");?>";
                                    break;
                        case 210 :  description = "<?php echo lang_check("light thunderstorm");?>";
                                    break;
                        case 211 :  description = "<?php echo lang_check("thunderstorm");?>";
                                    break;
                        case 212 :  description = "<?php echo lang_check("heavy thunderstorm");?>";
                                    break;
                        case 221 :  description = "<?php echo lang_check("ragged thunderstorm");?>";
                                    break;
                        case 230 :  description = "<?php echo lang_check("thunderstorm with light drizzle");?>";
                                    break;
                        case 231 :  description = "<?php echo lang_check("thunderstorm with drizzle");?>";
                                    break;
                        case 232 :  description = "<?php echo lang_check("thunderstorm with heavy drizzle");?>";
                                    break;
                        case 300 :  description = "<?php echo lang_check("light intensity drizzle");?>";
                                    break;
                        case 301 :  description = "<?php echo lang_check("drizzle");?>";
                                    break;
                        case 302 :  description = "<?php echo lang_check("heavy intensity drizzle");?>";
                                    break;
                        case 310 :  description = "<?php echo lang_check("light intensity drizzle rain");?>";
                                    break;
                        case 311 :  description = "<?php echo lang_check("drizzle rain");?>";
                                    break;
                        case 312 :  description = "<?php echo lang_check("heavy intensity drizzle rain");?>";
                                    break;
                        case 313 :  description = "<?php echo lang_check("shower rain and drizzle");?>";
                                    break;
                        case 314 :  description = "<?php echo lang_check("heavy shower rain and drizzle");?>";
                                    break;
                        case 321 :  description = "<?php echo lang_check("shower drizzle");?>";
                                    break;
                        case 500 :  description = "<?php echo lang_check("light rain");?>";
                                    break;
                        case 501 :  description = "<?php echo lang_check("moderate rain");?>";
                                    break;
                        case 502 :  description = "<?php echo lang_check("heavy intensity rain");?>";
                                    break;
                        case 503 :  description = "<?php echo lang_check("very heavy rain");?>";
                                    break;
                        case 504 :  description = "<?php echo lang_check("extreme rain");?>";
                                    break;
                        case 511 :  description = "<?php echo lang_check("freezing rain");?>";
                                    break;
                        case 520 :  description = "<?php echo lang_check("light intensity shower rain");?>";
                                    break;
                        case 521 :  description = "<?php echo lang_check("shower rain");?>";
                                    break;
                        case 522 :  description = "<?php echo lang_check("heavy intensity shower rain");?>";
                                    break;
                        case 531 :  description = "<?php echo lang_check("ragged shower rain");?>";
                                    break;
                        case 600 :  description = "<?php echo lang_check("light snow");?>";
                                    break;
                        case 601 :  description = "<?php echo lang_check("snow");?>";
                                    break;
                        case 602 :  description = "<?php echo lang_check("heavy snow");?>";
                                    break;
                        case 611 :  description = "<?php echo lang_check("sleet");?>";
                                    break;
                        case 612 :  description = "<?php echo lang_check("shower sleet");?>";
                                    break;
                        case 615 :  description = "<?php echo lang_check("light rain and snow");?>";
                                    break;
                        case 616 :  description = "<?php echo lang_check("rain and snow");?>";
                                    break;
                        case 620 :  description = "<?php echo lang_check("light shower snow");?>";
                                    break;
                        case 621 :  description = "<?php echo lang_check("shower snow");?>";
                                    break;
                        case 622 :  description = "<?php echo lang_check("heavy shower snow");?>";
                                    break;
                        case 701 :  description = "<?php echo lang_check("mist");?>";
                                    break;
                        case 711 :  description = "<?php echo lang_check("smoke");?>";
                                    break;
                        case 721 :  description = "<?php echo lang_check("haze");?>";
                                    break;
                        case 731 :  description = "<?php echo lang_check("sand, dust whirls");?>";
                                    break;
                        case 741 :  description = "<?php echo lang_check("fog");?>";
                                    break;
                        case 751 :  description = "<?php echo lang_check("sand");?>";
                                    break;
                        case 761 :  description = "<?php echo lang_check("dust");?>";
                                    break;
                        case 762 :  description = "<?php echo lang_check("volcanic ash");?>";
                                    break;
                        case 771 :  description = "<?php echo lang_check("squalls");?>";
                                    break;
                        case 781 :  description = "<?php echo lang_check("tornado");?>";
                                    break;
                        case 800 :  description = "<?php echo lang_check("clear sky");?>";
                                    break;
                        case 801 :  description = "<?php echo lang_check("few clouds");?>";
                                    break;
                        case 802 :  description = "<?php echo lang_check("scattered clouds");?>";
                                    break;
                        case 803 :  description = "<?php echo lang_check("broken clouds");?>";
                                    break;
                        case 804 :  description = "<?php echo lang_check("overcast clouds");?>";
                                    break;
                        case 900 :  description = "<?php echo lang_check("tornado");?>";
                                    break;
                        case 901 :  description = "<?php echo lang_check("tropical storm");?>";
                                    break;
                        case 902 :  description = "<?php echo lang_check("hurricane");?>";
                                    break;
                        case 903 :  description = "<?php echo lang_check("cold");?>";
                                    break;
                        case 904 :  description = "<?php echo lang_check("hot");?>";
                                    break;
                        case 905 :  description = "<?php echo lang_check("windy");?>";
                                    break;
                        case 906 :  description = "<?php echo lang_check("hail");?>";
                                    break;
                        case 951 :  description = "<?php echo lang_check("calm");?>";
                                    break;
                        case 952 :  description = "<?php echo lang_check("light breeze");?>";
                                    break;
                        case 953 :  description = "<?php echo lang_check("gentle breeze");?>";
                                    break;
                        case 954 :  description = "<?php echo lang_check("moderate breeze");?>";
                                    break;
                        case 955 :  description = "<?php echo lang_check("fresh breeze");?>";
                                    break;
                        case 956 :  description = "<?php echo lang_check("strong breeze");?>";
                                    break;
                        case 957 :  description = "<?php echo lang_check("high wind, near gale");?>";
                                    break;
                        case 958 :  description = "<?php echo lang_check("gale");?>";
                                    break;
                        case 959 :  description = "<?php echo lang_check("severe gale");?>";
                                    break;
                        case 960 :  description = "<?php echo lang_check("storm");?>";
                                    break;
                        case 961 :  description = "<?php echo lang_check("violent storm");?>";
                                    break;
                        case 962 :  description = "<?php echo lang_check("hurricane");?>";
                                    break;

                        default :   description = value.weather[0].description;
                                    break;
                    }
                    
                    
                   var day = new Date(value["dt"]*1000);
                   var day =day.getDay();
                    _data+='<div class="weather-item">\n\
                                <div class="date">'+days[day]+'</div>\n\
                                <div class="weather-img">\n\
                                    <img src="assets/img/weather/'+value.weather[0].icon+'.png" alt="" />\n\
                                </div>\n\
                                <div class="title"></div>\n\
                                <div class="description">'+description+' </div>\n\
                                <div class=""><?php echo _l('Low');?>: '+value.temp.min+' <?php echo $unit_symbol; ?> / <?php echo _l('Hight');?>: '+value.temp.max+' <?php echo $unit_symbol; ?></div>\n\
                            </div>'
                    weather_count++;
                    if(weather_count>2)  return false;
                })
                $('.weather-box#weather-1').html(_data);
                
                /* save cache */
                data_api ={
                        'value':JSON.stringify(data),
                        'weather_api':'openweathermap',
                    }
                $.post("<?php echo site_url('api/save_weather_cache/'.$property_id.'/'.$lang_code) ;?>", data_api, 
                       function(data_api){
                });

            })
        })
    </script>
    <?php endif;?>
    
<?php else:?>
    <?php
    $weather = json_decode($_CI->weathercacher_m->get_cache($property_id, 'openweathermap', $lang_id));
    ?>
    
    <?php $weather_count=0; foreach ($weather->list as $key => $value):
        
        $description='';
        switch ($value->weather[0]->id) {
            case 200 :  $description =  lang_check("thunderstorm with light rain");
                        break;
            case 201 :  $description =  lang_check("thunderstorm with rain");
                        break;
            case 202 :  $description =  lang_check("thunderstorm with heavy rain");
                        break;
            case 210 :  $description =  lang_check("light thunderstorm");
                        break;
            case 211 :  $description =  lang_check("thunderstorm");
                        break;
            case 212 :  $description =  lang_check("heavy thunderstorm");
                        break;
            case 221 :  $description =  lang_check("ragged thunderstorm");
                        break;
            case 230 :  $description =  lang_check("thunderstorm with light drizzle");
                        break;
            case 231 :  $description =  lang_check("thunderstorm with drizzle");
                        break;
            case 232 :  $description =  lang_check("thunderstorm with heavy drizzle");
                        break;
            case 300 :  $description =  lang_check("light intensity drizzle");
                        break;
            case 301 :  $description =  lang_check("drizzle");
                        break;
            case 302 :  $description =  lang_check("heavy intensity drizzle");
                        break;
            case 310 :  $description =  lang_check("light intensity drizzle rain");
                        break;
            case 311 :  $description =  lang_check("drizzle rain");
                        break;
            case 312 :  $description =  lang_check("heavy intensity drizzle rain");
                        break;
            case 313 :  $description =  lang_check("shower rain and drizzle");
                        break;
            case 314 :  $description =  lang_check("heavy shower rain and drizzle");
                        break;
            case 321 :  $description =  lang_check("shower drizzle");
                        break;
            case 500 :  $description =  lang_check("light rain");
                        break;
            case 501 :  $description =  lang_check("moderate rain");
                        break;
            case 502 :  $description =  lang_check("heavy intensity rain");
                        break;
            case 503 :  $description =  lang_check("very heavy rain");
                        break;
            case 504 :  $description =  lang_check("extreme rain");
                        break;
            case 511 :  $description =  lang_check("freezing rain");
                        break;
            case 520 :  $description =  lang_check("light intensity shower rain");
                        break;
            case 521 :  $description =  lang_check("shower rain");
                        break;
            case 522 :  $description =  lang_check("heavy intensity shower rain");
                        break;
            case 531 :  $description =  lang_check("ragged shower rain");
                        break;
            case 600 :  $description =  lang_check("light snow");
                        break;
            case 601 :  $description =  lang_check("snow");
                        break;
            case 602 :  $description =  lang_check("heavy snow");
                        break;
            case 611 :  $description =  lang_check("sleet");
                        break;
            case 612 :  $description =  lang_check("shower sleet");
                        break;
            case 615 :  $description =  lang_check("light rain and snow");
                        break;
            case 616 :  $description =  lang_check("rain and snow");
                        break;
            case 620 :  $description =  lang_check("light shower snow");
                        break;
            case 621 :  $description =  lang_check("shower snow");
                        break;
            case 622 :  $description =  lang_check("heavy shower snow");
                        break;
            case 701 :  $description =  lang_check("mist");
                        break;
            case 711 :  $description =  lang_check("smoke");
                        break;
            case 721 :  $description =  lang_check("haze");
                        break;
            case 731 :  $description =  lang_check("sand, dust whirls");
                        break;
            case 741 :  $description =  lang_check("fog");
                        break;
            case 751 :  $description =  lang_check("sand");
                        break;
            case 761 :  $description =  lang_check("dust");
                        break;
            case 762 :  $description =  lang_check("volcanic ash");
                        break;
            case 771 :  $description =  lang_check("squalls");
                        break;
            case 781 :  $description =  lang_check("tornado");
                        break;
            case 800 :  $description =  lang_check("clear sky");
                        break;
            case 801 :  $description =  lang_check("few clouds");
                        break;
            case 802 :  $description =  lang_check("scattered clouds");
                        break;
            case 803 :  $description =  lang_check("broken clouds");
                        break;
            case 804 :  $description =  lang_check("overcast clouds");
                        break;
            case 900 :  $description =  lang_check("tornado");
                        break;
            case 901 :  $description =  lang_check("tropical storm");
                        break;
            case 902 :  $description =  lang_check("hurricane");
                        break;
            case 903 :  $description =  lang_check("cold");
                        break;
            case 904 :  $description =  lang_check("hot");
                        break;
            case 905 :  $description =  lang_check("windy");
                        break;
            case 906 :  $description =  lang_check("hail");
                        break;
            case 951 :  $description =  lang_check("calm");
                        break;
            case 952 :  $description =  lang_check("light breeze");
                        break;
            case 953 :  $description =  lang_check("gentle breeze");
                        break;
            case 954 :  $description =  lang_check("moderate breeze");
                        break;
            case 955 :  $description =  lang_check("fresh breeze");
                        break;
            case 956 :  $description =  lang_check("strong breeze");
                        break;
            case 957 :  $description =  lang_check("high wind, near gale");
                        break;
            case 958 :  $description =  lang_check("gale");
                        break;
            case 959 :  $description =  lang_check("severe gale");
                        break;
            case 960 :  $description =  lang_check("storm");
                        break;
            case 961 :  $description =  lang_check("violent storm");
                        break;
            case 962 :  $description =  lang_check("hurricane");
                        break;

            default :   $description = $value->weather[0]->description;
                        break;
        }
        ?>
    
        <div class="weather-item">
            <div class="date"><?php echo _l('cal_'.strtolower(date('l',$value->dt)));?></div>
            <div class="weather-img">
                <img src="assets/img/weather/<?php _che($value->weather[0]->icon);?>.png" alt="" />
            </div>
            <div class="title"></div>
            <div class="description"><?php _che($description);?></div>
            <div class=""><?php echo _l('Low');?>: <?php echo round($value->temp->min);?> <?php echo $unit_symbol; ?> / <?php echo _l('Hight');?>: <?php echo round($value->temp->max);?> <?php echo $unit_symbol; ?></div>
        </div>
    <?php $weather_count++;if($weather_count>2)break; endforeach;?>
    
<?php endif; ?>
</div>

    </div>
</div>

<?php endif;?>