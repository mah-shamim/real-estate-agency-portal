<?php
//for php 5.3
$session_compare=$this->session->userdata('property_compare');
?>

<div class="widget widget-compare contact" >
    <div class="title">
        <h2 class="block-title"><?php echo lang_check('Compare');?></h2>
    </div><!-- /.title -->

    <div class="content">
        <div class="clearfix text-left">
            <a class="btn btn-warning" id='add_to_compare' style="<?php echo (empty($session_compare) || !isset($session_compare[$estate_data_id]))?'':'display:none;'; ?>" href='#'> <?php echo lang_check('Add to comparison list');?> </a>
            <a class="btn btn-success" id='remove_from_compare' style="<?php echo (!empty($session_compare)&&isset($session_compare[$estate_data_id]))?'':'display:none;'; ?>" href='#'> <?php echo lang_check('Remove from comparison list');?> </a>
        </div>
        <div class="compare-content">
            
            <ul class='compare-list'>
                <?php if(!empty($session_compare)&&sw_count($session_compare)>0):?>
                <?php foreach ($session_compare as $key => $value):?>
                    <li data-id="<?php _che($key);?>"> 
                        <a href="<?php echo slug_url($listing_uri.'/'.$key.'/'.$lang_code.'/'.url_title_cro($value));?>"> <?php _che($key);?>, <?php _che($value);?></a>
                    </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <a class="btn btn-primary" style="<?php echo (!empty($session_compare)&&sw_count($session_compare)>1)?'':'display:none;'; ?>" href='<?php echo site_url('/propertycompare/'.$estate_data_id.'/'.$lang_code); ?>'> <?php echo lang_check('Compare listings');?> </a>
        </div>
        
    </div>
</div>

<script>
   $('document').ready(function(){
       
       
    $("#add_to_compare").click(function(e){
        e.preventDefault();
        var data = { property_id: {estate_data_id} };
        
        $.post("<?php echo site_url('api/add_to_compare/'.$lang_code);?>", data, 
            function(data){
            /*var data=jQuery.parseJSON(data);  */             
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
                    $(".compare-content .btn").css('display', 'inline-block');
            }
        });
        return false;
    });
    
    $("#remove_from_compare").click(function(e){
        e.preventDefault();
        var data = { property_id: {estate_data_id} };
        
        $.post("<?php echo site_url('api/remove_from_compare/'.$lang_code);?>", data, 
            function(data){
           /* var data=jQuery.parseJSON(data);   */            
            ShowStatus.show(data.message);
            
            if(data.success)
            {
                $('.compare-list li').filter('[data-id="'+data.property_id+'"]').remove();
                $("#remove_from_compare").css('display', 'none');
                $("#add_to_compare").css('display', 'inline-block');
                if( !$('.compare-list li').length || $('.compare-list li').length<2 )
                    $(".compare-content .btn").css('display', 'none');
            }
        });
        return false;
    });
   })
   
   
</script>
    
