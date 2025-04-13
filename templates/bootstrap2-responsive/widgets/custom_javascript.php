
<?php if(config_item('enable_search_details_on_top') == TRUE): ?>

<script>
$(document).ready(function(){
	if($('.top_content').length == 0 && $(window).width() > 767)
    {
    	var content = $('.wrap-search');
    	var pos = content.offset();
    	
    	$(window).scroll(function(){
    		if($(this).scrollTop() > pos.top && $(window).width() > 767){
              content.addClass('search_on_top');
    		} else if($(this).scrollTop() <= pos.top){
              content.removeClass('search_on_top');
    		}
    	});
    }
});
</script>
<?php endif; ?>


<script>

$(document).ready(function(){
    // add calendar for all inputs with class .field_datepicker (required unique id)
    $('.field_datepicker').each(function(){
        $(this).datepicker({
            pickTime: false
        });
    })
    $('.to-top').on('click', function(e){
        e.preventDefault();
         $('html,body').animate({scrollTop:0}, 1500,'swing');
    })
    
    $('.field_datepicker_time').each(function(){
    $(this).datepicker({
        pickTime: true
    });
    });
    
    if ($('.sw_scale_range').length){
        $('.sw_scale_range').each(function(){
            var th_scale = $(this);
            var th_scale_id = $(this).attr('id');
            var conf_min = '0';
            var conf_max = '100000';
            var conf_sufix= '';
            var conf_prefix= '';
            var conf_infinity = '';
            var conf_predifinedMin = '';
            var conf_predifinedMax =  '';

            if(th_scale.find('.config-range').length ) {
                var $config = th_scale.find('.config-range');
                conf_min = $config.attr('data-min') || 0;
                conf_max = $config.attr('data-max') || '';
                conf_sufix= $config.attr('data-sufix') || '';
                conf_prefix= $config.attr('data-prefix') || '';
                conf_infinity = $config.attr('data-infinity') || "false";
                conf_predifinedMin = $config.attr('data-predifinedMin') || '';
                conf_predifinedMax = $config.attr('data-predifinedMax') || '';
            }
            scale_range('#'+th_scale_id,conf_min,conf_max,conf_prefix,conf_sufix,conf_infinity,conf_predifinedMin,conf_predifinedMax);
       
        })
    }
})


 /*
 * Scale range
 * @param {type} object selector for scale-range box
 * @param {type} min min value
 * @param {type} max max value
 * @param {type} prefix
 * @param {type} sufix
 * @param {type} infinity, is infinity
 * @param {type} predifinedMin value
 * @param {type} predifinedMax value
 * @returns {Boolean}
 * 
 * 
 * Example html :
    <div class="scale-range" id="nonlinear-price">
        <label>Price</label>
        <div class="nonlinear"></div>
        <div class="scale-range-value">
            <span class="nonlinear-min"></span>
            <span class="nonlinear-max"></span>
        </div>
        <input id="from" type="text" class="value-min hidden" placeholder="" value="" />
        <input id="to" type="text" class="value-max hidden" placeholder="" value="" />
    </div>
* Example js :                                                                                                                                                                                                                           
     nexos_scale_range('#nonlinear-price',0, 500000, '$', 'k', true, '','');
*/

function scale_range(object, min, max, prefix, sufix, infinity, predifinedMin, predifinedMax) {
    if (typeof object == 'undefined')
        return false;
    if (typeof min == 'undefined' || min=='')
        var min = 0;
    if (typeof max == 'undefined' || max=='')
        return false;
    if (typeof prefix == 'undefined' || prefix=='')
        var prefix = '';
    if (typeof sufix == 'undefined' || sufix=='')
        var sufix = '';
    if (typeof infinity === 'infinity' || infinity=='')
        var infinity = true;
    if(infinity == "false") infinity = false;
    
    var $ = jQuery;
    if (typeof predifinedMin == 'undefined' || predifinedMin ==''){
        var predifinedMin = min || 0;
        predifinedMin-=10;
    }
    if (typeof predifinedMax == 'undefined' || predifinedMax==''){
        var predifinedMax = max || 100000;
        predifinedMax+=10;
    }
    
    /* errors */
    
    if(!$(object + ' .value-min').length || !$(object + ' .value-max').length) {
        console.log('Scale range: missing input min or max');
        return false;
    }
    
    var r = 0;
    if(infinity) {
        r = 10;
    }
    
    if ($(object + ' .nonlinear').length) {
        var nonLinearSlider = document.querySelector(object + ' .nonlinear');
        noUiSlider.create(nonLinearSlider, {
            connect: true,
            behaviour: 'tap',
            start: [predifinedMin, predifinedMax],
            range: {
                'min': [parseInt(min)-r],
                'max': [parseInt(max)+r]
            }
        });

        var nodes = [
            document.querySelector(object + ' .nonlinear-min'), // 0
            document.querySelector(object + ' .nonlinear-max')  // 1
        ];
        
        var inputs = [
            document.querySelector(object + ' .value-min'), // 0
            document.querySelector(object + ' .value-max')  // 1
        ];

        // Display the slider value and how far the handle moved
        nonLinearSlider.noUiSlider.on('update', function (values, handle, unencoded, isTap, positions) {

            if(parseInt(values[handle]) > max && infinity){
                nodes[handle].innerHTML = prefix + parseInt(max).toFixed() + sufix+'+';
            }
            else if(parseInt(values[handle]) < min && infinity){
                nodes[handle].innerHTML = prefix + parseInt(min).toFixed() + sufix+'-';
            }
            else
                nodes[handle].innerHTML = prefix + parseInt(values[handle]).toFixed() + sufix;
            
            if(parseInt(values[handle]) > max && infinity){
                inputs[handle].value = '';
            }
            else if(parseInt(values[handle]) < min && infinity){
                inputs[handle].value = '';
            }
            else
                inputs[handle].value = parseInt(values[handle]).toFixed();
            
            $(object + ' .value-min, '+object + ' .value-max').trigger('change')
        });
    }
    
}
</script>

<?php
    $output ='';
    $CI =& get_instance();
    //Get template settings
    $template_name = $CI->data['settings']['template'];
    $cache_time_sec = 604800; /* one week */
    $cache_file_name = FCPATH.'templates/'.$template_name.'/assets/cache/_generate_dependentfields.js';
    //Load view
    if(file_exists(FCPATH.'templates/'.$template_name.'/widgets/_generate_dependentfields_js.php'))
        if(file_exists($cache_file_name) /*&& filemtime($cache_file_name) > time()-$cache_time_sec*/)
        {
            $output = $CI->load->view($template_name.'/widgets/_generate_dependentfields_js.php', false, true);
            require_once APPPATH."helpers/min-js.php";
            $jSqueeze = new JSqueeze();
            $output = $jSqueeze->squeeze($output, true, false);
            file_put_contents(FCPATH.'templates/'.$template_name.'/assets/cache/_generate_dependentfields.js', $output);
        } else {
            $output = $CI->load->view($template_name.'/widgets/_generate_dependentfields_js.php', false, true);
            require_once APPPATH."helpers/min-js.php";
            $jSqueeze = new JSqueeze();
            $output = $jSqueeze->squeeze($output, true, false);
            file_put_contents(FCPATH.'templates/'.$template_name.'/assets/cache/_generate_dependentfields.js', $output);
        }

    echo '<script src="assets/cache/_generate_dependentfields.js?v='.rand(000,999).'"></script>';
?>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"> </h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>


