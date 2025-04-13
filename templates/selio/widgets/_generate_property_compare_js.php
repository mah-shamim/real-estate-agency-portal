<script>
$('document').ready(function(){
    $("#add_to_compare").click(function(e){
        e.preventDefault();
        var data = { property_id: {estate_data_id} };
        
        $.post("<?php echo site_url('api/add_to_compare/'.$lang_code);?>", data, 
            function(data){
            ShowStatus.show(data.message);
            
            if(data.success)
            {
                if( data.remove_first){
                    $('.compare-list li').first().remove();
                }
                 $('.compare-list').append('<li data-id="'+data.property_id+'"><a href="'+data.property_url+'">'+data.property+'</a></li>')
                 $("#add_to_compare").css('display', 'none');
                 $("#remove_from_compare").css('display', 'inline-block');
                 
                if( $('.compare-list li').length>1)
                    $(".compare-content .btn2").css('display', 'inline-block');
            }
        });
        return false;
    });
    
    $("#remove_from_compare").click(function(e){
        e.preventDefault();
        var data = { property_id: {estate_data_id} };
        
        $.post("<?php echo site_url('api/remove_from_compare/'.$lang_code);?>", data, 
            function(data){
            ShowStatus.show(data.message);
            
            if(data.success)
            {
                $('.compare-list li').filter('[data-id="'+data.property_id+'"]').remove();
                $("#remove_from_compare").css('display', 'none');
                $("#add_to_compare").css('display', 'inline-block');
                if( !$('.compare-list li').length || $('.compare-list li').length<2 )
                    $(".compare-content .btn2").css('display', 'none');
            }
        });
        return false;
    });
})
</script>