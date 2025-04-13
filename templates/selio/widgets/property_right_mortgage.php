<?php
    $def_value = '';
    $def_int = '';
    $def_yea = '';

    // Fetch default values
    $CI =& get_instance();

    if(!empty($estate_data_option_36))
    {
        $default_value = $estate_data_option_36;

        $def_value = $estate_data_option_36;
        $def_value = str_replace(',', '', $def_value);
        $def_value = number_format($def_value, 2, '.', '');

    } else {
        return false;
    }
    $default_currency = '$';
    if(!empty($estate_data_option_36))
    {
        if(!empty($options_suffix_36))
        {
            $default_currency = $options_suffix_36;
        }
        else
        {
            $default_currency = $options_prefix_36;
        }
        
    }
    else if(!empty($estate_data_option_37))
    {
        if(!empty($options_suffix_37))
        {
            $default_currency = $options_suffix_37;
        }
        else
        {
            $default_currency = $options_prefix_37;
        }

    }
    
?>

<div class="">
    <div class="widget widget-calculator mortgage_widget clearfix">
         <form method="get" action="#" class="" id="mortgage_calculator">
            <ul>
                <li>
                    <i><?php echo $default_currency;?></i>
                    <input id="mortgage_balance" type="text" value="<?php echo ($def_value); ?>" placeholder="<?php echo lang_check('House price'); ?>*">
                </li>
                <li>
                    <i><?php echo $default_currency;?></i>
                    <input id="mortgage_interest" type="text" value="<?php echo ($def_int); ?>" placeholder="<?php echo lang_check('Interest'); ?>*">
                </li>
                <li>
                    <i><?php echo $default_currency;?></i>
                    <input id="mortgage_downpayment"  type="text" placeholder="<?php echo lang_check('Down payment'); ?>">
                </li>
                <li>
                    <i>Y</i>
                    <input id="mortgage_years" type="text" value="<?php echo ($def_yea); ?>" placeholder="<?php echo lang_check('Years'); ?>*">
                </li>
                <li class="form-group-result" style="display: none">
                    <label><?php echo lang_check('Monthly Repayments'); ?></label>
                    <p id="results_monthly" class="form-control-static center"><?php echo (_ch($options_prefix_36)); ?> 0 <?php echo (_ch($options_suffix_36)); ?></p>
                </li><!-- /.form-group -->
                <li class="form-group-result" style="display: none">
                    <label><?php echo lang_check('Weekly Repayments');?> </label>
                    <p id="results_weekly" class="form-control-static center"><?php echo (_ch($options_prefix_36)); ?> 0 <?php echo (_ch($options_suffix_36)); ?></p>
                </li><!-- /.form-group -->
            
                <li>
                    <button type="submit" class="btn2"><?php echo lang_check('Calculate'); ?></button>
                </li>
            </ul>
        </form>
    </div>
</div>


<?php

$custom_js ="";
$custom_js .="

jQuery(document).ready(function($) {
    
	$('#mortgage_calculator').on('submit', function(e) {
		e.preventDefault();
		var \$params = {
			balance: $('#mortgage_balance').val() - $('#mortgage_downpayment').val(),
			rate: $('#mortgage_interest').val(),
			term: $('#mortgage_years').val(),
			period: 12
		};
		
		$(this).calculateMortgage({
			params: \$params,
            results_weekly: $('#results_weekly'),
            results_monthly: $('#results_monthly')
		})
	
	});	
    
	$.fn.calculateMortgage = function(options) {
		var defaults = {
                        currency_prefix: '"._ch($options_prefix_36)."',
                        currency_suffix: '". _ch($options_suffix_36)."',
			params: {}
		};
		options = $.extend(defaults, options);
		
		var calculate = function(params) {
			params = $.extend({
				balance: 0,
				rate: 0,
				term: 0,
				period: 0,
                results_weekly: null,
                results_monthly: null
			}, params);
			
			var N = params.term * params.period;
			var I = (params.rate / 100) / params.period;
			var v = Math.pow((1 + I), N);
			var t = (I * v) / (v - 1);
			var result = params.balance * t;
			
			return result;
		};
		
		return this.each(function() {
			var \$element = $(this);
			var \$result_custom = calculate(options.params);
            var \$result_month = calculate($.extend(options.params, {period: 12}));
            var \$result_week = calculate($.extend(options.params, {period: 52}));
            
            \$element.find('div.alert').remove();
            
			var output_week = options.currency_prefix + ' ' + \$result_week.toFixed(2) + ' ' + options.currency_suffix;
            if(selio_mortgage_is_numeric(\$result_week.toFixed(2)) && selio_mortgage_is_numeric(\$result_month.toFixed(2)) && selio_mortgage_is_numeric(\$result_week.toFixed(2)) && selio_mortgage_is_numeric(parseInt($('#mortgage_years').val()).toFixed(2)))
            {
                options.results_weekly.html(output_week);
                options.results_weekly.parent().slideDown();
            }
            else
            {
                \$element.prepend('<div class=\"alert alert-danger\" role=\"alert\">".lang_check('Please fill empty fields')."</div>');
                 $('html,body').animate({scrollTop: \$element.offset().top-170}, 'slow');
                    options.results_weekly.parent().slideUp();
            }
			     
		
			var output_month = options.currency_prefix + ' ' + \$result_month.toFixed(2) + ' ' + options.currency_suffix;
			if(selio_mortgage_is_numeric(\$result_month.toFixed(2))){
                            options.results_monthly.html(output_month);
                            options.results_monthly.parent().slideDown();
                        }
		});

	};

});


function selio_mortgage_is_numeric(mixed_var) {
  var whitespace =
    ' \\n\\r\\t\\f\\x0b\\xa0\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u200b\\u2028\\u2029\\u3000';
  return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
    1)) && mixed_var !== '' && !isNaN(mixed_var);
}
";
/* dinamic per listing */


sw_add_inline_script('page_js_'.md5(current_url_q()), $custom_js);

?>






