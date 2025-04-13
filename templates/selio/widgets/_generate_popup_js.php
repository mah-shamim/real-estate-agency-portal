<script>

$('document').ready(function(){
    
    $('form#popup_form_login').submit(function(e){
        e.preventDefault();
        var data = $('form#popup_form_login').serializeArray();
        //console.log( data );
        $('form#popup_form_login .ajax-indicator').removeClass('hidden');
        // send info to agent
        $.post("<?php echo site_url('api/login_form/'.$lang_code); ?>", data,
        function(data){
            if(data.success)
            {
                // Display agent details
                $('form#popup_form_login .alerts-box').html('');
                if(data.message){
                    ShowStatus.show(data.message)
                }
                if(data.redirect) {
                    //location.href = data.redirect;
                }
                location.reload();
            }
            else
            { 
                if(data.message){
                    ShowStatus.show(data.message)
                }
                $('form#popup_form_login .alerts-box').html(data.errors);
            }
        }).success(function(){$('form#popup_form_login .ajax-indicator').addClass('hidden');});
        return false;
    });
})


</script>