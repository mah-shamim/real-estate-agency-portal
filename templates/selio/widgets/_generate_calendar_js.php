<script>
/* Date picker */

// Calendar translation start //

var translated_cal = {
            days: ["{lang_cal_sunday}", "{lang_cal_monday}", "{lang_cal_tuesday}", "{lang_cal_wednesday}", "{lang_cal_thursday}", "{lang_cal_friday}", "{lang_cal_saturday}", "{lang_cal_sunday}"],
            daysShort: ["{lang_cal_sun}", "{lang_cal_mon}", "{lang_cal_tue}", "{lang_cal_wed}", "{lang_cal_thu}", "{lang_cal_fri}", "{lang_cal_sat}", "{lang_cal_sun}"],
            daysMin: ["{lang_cal_su}", "{lang_cal_mo}", "{lang_cal_tu}", "{lang_cal_we}", "{lang_cal_th}", "{lang_cal_fr}", "{lang_cal_sa}", "{lang_cal_su}"],
            months: ["{lang_cal_january}", "{lang_cal_february}", "{lang_cal_march}", "{lang_cal_april}", "{lang_cal_may}", "{lang_cal_june}", "{lang_cal_july}", "{lang_cal_august}", "{lang_cal_september}", "{lang_cal_october}", "{lang_cal_november}", "{lang_cal_december}"],
            monthsShort: ["{lang_cal_jan}", "{lang_cal_feb}", "{lang_cal_mar}", "{lang_cal_apr}", "{lang_cal_may}", "{lang_cal_jun}", "{lang_cal_jul}", "{lang_cal_aug}", "{lang_cal_sep}", "{lang_cal_oct}", "{lang_cal_nov}", "{lang_cal_dec}"]
    };

if(typeof(DPGlobal) != 'undefined'){
    DPGlobal.dates = translated_cal;
}
// Calendar translation End //

var nowTemp = new Date();

var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

$('.datetimepicker_standard').datepicker().on('changeDate', function(ev) {
    $(this).datepicker('hide');
});

var checkin = $('#datetimepicker1').datepicker({
    onRender: function(date) {
        var dd = date.getDate();
        var mm = date.getMonth()+1;//January is 0!`
        var yyyy = date.getFullYear();
        if(dd<10){dd='0'+dd}
        if(mm<10){mm='0'+mm}
        var today_formated = yyyy+'-'+mm+'-'+dd;
        if(date.valueOf() < now.valueOf()) // Just for performance
        {
            return 'disabled';
        }
        <?php if(file_exists(APPPATH.'controllers/admin/booking.php')): ?>
        else if(dates_list.indexOf(today_formated )>= 0)
        {
            return '';
        }

        return 'disabled red';
        <?php else: ?>
        return '';
        <?php endif;?>
    }
}).on('changeDate', function(ev) {
    if (ev.date.valueOf() > checkout.date.valueOf()) {
        var newDate = new Date(ev.date)
        newDate.setDate(newDate.getDate() + 7);
        checkout.setValue(newDate);
    }
    checkin.hide();
    $('#datetimepicker2')[0].focus();
}).data('datetimepicker');
    var checkout = $('#datetimepicker2').datepicker({
    onRender: function(date) {

        var dd = date.getDate();
        var mm = date.getMonth()+1;//January is 0!`

        var yyyy = date.getFullYear();
        if(dd<10){dd='0'+dd}
        if(mm<10){mm='0'+mm}
        var today_formated = yyyy+'-'+mm+'-'+dd;


        if(date.valueOf() <= now.valueOf()) // Just for performance
        {
            return 'disabled';
        }                    
        <?php if(file_exists(APPPATH.'controllers/admin/booking.php')): ?>
        else if(dates_list.indexOf(today_formated )>= 0)
        {
            return '';
        }

        return 'disabled red';
        <?php else: ?>
        return '';
        <?php endif;?>
}
}).on('changeDate', function(ev) {
    checkout.hide();
}).data('datepicker');

$('a.available.selectable').click(function(){
    $('#datetimepicker1').val($(this).attr('ref'));
    $('#datetimepicker2').val($(this).attr('ref_to'));
    $('div.property-form form input:first').focus();

    var nowTemp = new Date($(this).attr('ref'));
    var date_from = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    //console.log(date_from);

    $('#datetimepicker1').datepicker('setValue',date_from);
    date_from.setDate(date_from.getDate() + 7);
    checkout.setValue(date_from);
});

/* Date picker end */
</script>