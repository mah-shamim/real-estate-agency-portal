<script>

/*
 * Set item in geo menu
 * @param dataPath (string) value-path for treefield field ("Croatia - Zagreb")
 * 
 */

/*
 * 
 * Styles for svg
 * 
 */

var svg_default_area_color = '#fff' /* default color*/
var svg_selected_area_color = '#6a7be7' /* selected color*/
var svg_hover_area_color = '#6a7be7' /* hover color*/
var svg_selected_country_color = '#fff';
var svg_stroke_color = '#000';

var firstload = true;
var geo_trigger_treefield = false;

var tree_list = [];

function geo_strclear(str) {
    return str.replace(/./g, '');
}

function set_path (dataPath, apply_in_search, tree_field) {
        /*console.log(true)*/
        /*console.log(dataPath);*/
        
        if (typeof apply_in_search === 'undefined') apply_in_search = true;
        if (typeof tree_field === 'undefined') tree_field = true;
        var dataPath_origin = dataPath;
        // refresh
        var s_values_splited = (dataPath+" ").split(" - "); 
        
        var _last_element = $.trim(s_values_splited[s_values_splited.length-1]);
        
        if($('.geo-menu a[data-region="'+_last_element+'"]').closest('li').hasClass('active')) {
            delete s_values_splited[s_values_splited.length-1];
        }
        
        $('.geo-menu li').removeClass('active');
        
        $('.geo-menu ul > li li').css('display', 'none');
        $('.geo-menu ul > li').css('display', 'inline-block');
        $('.geo-menu ul a').css('display', 'initial')
    
        var _dataPath = '';
        
        $.each(s_values_splited, function(key, val){
            if(key>1) return false;
            /*console.log('key: '+key, 'val: '+val)*/
            
            val = $.trim(val);
            
            if(!$('.geo-menu a[data-region="'+val+'"]').length) return false;
            var _this =  $('.geo-menu a[data-region="'+val+'"]');
            var parent = _this.closest('li');
            
            if( parent.hasClass('active')) {
                parent.removeClass('active')
                return false;
            }
            
            parent.addClass('active')
               
            if(parent.find('li').length){
                $(' > li', parent.parent()).not(parent).css('display', 'none');
                _this.css('display', 'none')
                $(' li', parent).css('display', 'inline-block');
                
                $('.geo-menu ul'+geo_strclear(_this.attr('href'))).show();
            }
            if(_dataPath == '')
                _dataPath += '' + val;
            else
                _dataPath += ' - ' + val;
        })
        
        if(apply_in_search){
            $('.geo-menu-breadcrumb').html(_dataPath);
            /*console.log(_dataPath)*/
            if(_dataPath !='')
                $('.search_option_<?php _che($treefield_id);?>').val(dataPath_origin);
            else 
                $('.search_option_<?php _che($treefield_id);?>').val('');
        }
        
        if(apply_in_search && tree_field){
            var dataPath = _dataPath;
            
            var e = $('.geo-menu a[data-path-map="'+dataPath+'"]');
            if(!e.length)
                e = $('.geo-menu a[data-path="'+dataPath+'"]')
            
            var id = e.attr('data-id');
            var s_values_splited = (dataPath+" - ").split(" - "); 

            geo_trigger_treefield = true;
            if(s_values_splited[0] != '' && jQuery('.group_location_id .winter_dropdown_tree.primary').length)
            {
                jQuery('.group_location_id .winter_dropdown_tree.primary').find('.list_items li[key="'+s_values_splited[0]+' -"]').trigger('click');
                setTimeout(() => {
                    geo_trigger_treefield = true;
                    jQuery('.group_location_id .winter_dropdown_tree.secondary').find('.list_items li[key="'+dataPath+' -"]').trigger('click');
                    geo_trigger_treefield = false;
                }, 500);
            } else {
                var dropdown = jQuery('.group_location_id .winter_dropdown_tree');
                dropdown.find('.list_items li[key="'+id+'"]').trigger('click');
                dropdown.find('.list_items li[key="'+dataPath+' -"]').trigger('click');
            }

            geo_trigger_treefield = false;
        }
        

        /* short-more tags*/
        dataPath_origin = $.trim(dataPath_origin)
        
        if(firstload && dataPath_origin[dataPath_origin.length-1] == '-') {
            dataPath_origin = dataPath_origin.slice(0, -1);
            dataPath_origin = $.trim(dataPath_origin)
        }
        
        var _p = (dataPath_origin+"  ").split(" - ") || false;
        if(_p && _p[_p.length-2]) {
            var selector = $.trim(_p[_p.length-2]);
            if($('a[data-region="'+selector+'"]').closest('li').find('ul li .more-tags').length && $('a[data-region="'+selector+'"]').closest('li').find('ul li .more-tags').attr('data-close') == 'false'){
            } else {
                hideShow_tags(selector);
            }
        } else if(firstload && _p && _p[_p.length-1]){
            var selector = $.trim(_p[_p.length-1]);
            if($('a[data-region="'+selector+'"]').closest('li').find('ul li .more-tags').length && $('a[data-region="'+selector+'"]').closest('li').find('ul li .more-tags').attr('data-close') == 'false'){
            } else {
                hideShow_tags(selector);
            }
        }
        
        firstload = false;
}

/* menu geo */
$(document).ready(function(){

    $('.geo-menu  a').click(function(e){
        e.preventDefault();
        
        var dataPath =  $(this).attr('data-path')  || '';
        set_path (dataPath)
    })
    
})

/* additional methds for svg map */
jQuery.fn.myAddClass = function (classTitle) {
   return this.each(function() {
     var oldClass = jQuery(this).attr("class") || '';
     oldClass = oldClass ? oldClass : '';
     jQuery(this).attr("class", (oldClass+" "+classTitle));
   });
 }
 $.fn.myRemoveClass = function (classTitle) {
   return this.each(function() {
       var oldClass = $(this).attr("class") || '';
       var newClass = oldClass.replace(classTitle, '');
       $(this).attr("class", newClass);
   });
 }
 $.fn.myHasClass = function (classTitle) {
    var current_class = $(this).attr("class") || '';

    if(current_class.indexOf(classTitle)=='-1') {
        return false;
    } else {
        return true;
    }
 }

 // map
 $(window).load(function () {
    if($('#svgmap').length) { 
     
    var nameAreaRoot = false;
    var nameArea = [];
    var nameCount = [];
    var trigger = false;
    var first_load_map = true; 
    
    <?php if(isset($ariesInfo) && !empty($ariesInfo)) foreach ($ariesInfo as $key => $value):?>
        if(nameAreaRoot==false)  
            nameAreaRoot = "<?php echo $value['name'];?>";
        
        nameArea["<?php echo $key;?>"] = "<?php echo $value['name'];?>";
        nameCount["<?php echo $key;?>"] = "<?php echo $value['count'];?>";
    <?php endforeach;?>
    var svgobject = document.getElementById('svgmap');
    if ('contentDocument' in svgobject) {             
        var svgdom = jQuery(svgobject.contentDocument);  
    }
    
    if(!$('[data-map-type="multimap"]', svgdom).length) {
        svg_selected_country_color = svg_default_area_color;
    }
    
    /* colors */
    $('*', svgdom).css('stroke', 'rgb(64, 64, 64)');

    
    /* change primary color*/
    $('*[data-name]', svgdom).not('.area').css('fill', svg_default_area_color);
    /* end colors */
    
    
    /* from dropdown if null id*/
    $('.TREE-GENERATOR#TREE-GENERATOR_ID_<?php echo $treefield_id;?> select').change(function(){
        if(!$(this).val()) {
            $('*[data-name]', svgdom).myRemoveClass('highlight');
        }
    })
    
 
    $('.treefield-tags a:not(.geo-menu-back)').click(function(){
        
        // country hover
        if($('.geo-menu .treefield-tags >li.active >a').attr('data-name-lvl_0'))
            $('*[data-name-lvl_0="'+$('.geo-menu .treefield-tags >li.active >a').attr('data-name-lvl_0')+'"]:not(.highlight)', svgdom).css('fill', svg_selected_country_color);
        
        if($(this).attr('data-region') && $(this).attr('data-name-lvl_0')) {
            if($('*[data-name="'+$(this).attr('data-path-map-origin')+'"]', svgdom).length) {
                 trigger = true
                $('*[data-name="'+$(this).attr('data-path-map-origin')+'"]', svgdom).trigger('click');
                
            } else {
                $('*[data-name]', svgdom).myRemoveClass('highlight');
            }
        } 
        else {
            $('*[data-name]', svgdom).myRemoveClass('highlight');
        }
    })
    
    $('.geo-menu .geo-menu-back').click(function(e){
        e.preventDefault();
        $('*[data-name]', svgdom).myRemoveClass('highlight');
        $('*[data-name]', svgdom).not('.area').css('fill', svg_default_area_color);
    })
    
    /* start selected area */
    $('*[data-name]', svgdom).click(function(){
        
        if($(this).myHasClass('highlight')) {
            $('*[data-name]', svgdom).myRemoveClass('highlight'); 
            $('*[data-name]', svgdom).not('.area').css('fill', svg_default_area_color);
           
           if(!trigger && $(this).attr('data-name-lvl_1') && nameArea[$(this).attr('data-name')]) {
                var dataPath = $('.geo-menu a[data-region="'+nameArea[$(this).attr('data-name')]+'"]').attr('data-path')  || '';
                dataPath = dataPath.replace($(this).attr('data-name-lvl_1')+' - ',"");
                set_path (dataPath);
           }
           
            <?php  if(config_item('auto_map_search')===TRUE):?>
            if(!firstload && !trigger) {
                setTimeout(() => {
                    manualSearch(0);
                }, 600);
            }
            <?php endif;?>
            return false;
        }
        
        $('*[data-name]', svgdom).myRemoveClass('highlight');
        $('*[data-name]', svgdom).not('.area').css('fill', svg_default_area_color);
        
        /* highlight country */ 
        $('*[data-name-lvl_0="'+$(this).attr('data-name-lvl_0')+'"]', svgdom).css('fill', svg_selected_country_color);
        
        $(this).myAddClass('highlight');
        if(!$(this).myHasClass('area'))
            $(this).css('fill', svg_selected_area_color);
        if(!trigger && $(this).attr('data-name-lvl_1') && nameArea[$(this).attr('data-name')]) {
            var dataPath = $('.geo-menu a[data-path-map="'+nameArea[$(this).attr('data-name')]+'"]').attr('data-path')  || '';
            set_path (dataPath);
        }
        
        <?php  if(config_item('auto_map_search')===TRUE):?>
        if(!firstload && !trigger) {
            setTimeout(() => {
                manualSearch(0);
            }, 600);
        }
        <?php endif;?>
       
       trigger = false;
    })
    /* end selected area */  
    
    $('*[data-name]', svgdom).hover(function(){
        if(!$(this).myHasClass('highlight') && !$(this).myHasClass('area'))
            $(this).css('fill', svg_hover_area_color);
        }, function(){
        if(!$(this).myHasClass('highlight') && !$(this).myHasClass('area'))
            $(this).css('fill', svg_default_area_color);
        
            if($('.geo-menu .treefield-tags >li.active >a').attr('data-name-lvl_0') && ($('#sinputOption_1_64_level_0').val() || $('#search_option_64').val()))
                $('*[data-name-lvl_0="'+$('.geo-menu .treefield-tags >li.active >a').attr('data-name-lvl_0')+'"]:not(.highlight)', svgdom).css('fill', svg_selected_country_color);
        }
    )
    /* end hover area */   
    
    // init map first load with data
    if(first_load_map) {
        var init_dataPath = '<?php echo search_value($treefield_id); ?>' || '<?php echo $root_value;?> - ' || 'Croatia - ';
       
        /* do empty for geo in root */
        init_dataPath='';
        
        var init_s_values_splited = (init_dataPath+" ").split(" - "); 
        $.each(init_s_values_splited, function(key, val){
            val = $.trim(val);
            if(val) {
                if($('*[data-name="'+val+'"]', svgdom).length) {
                     trigger = true
                    $('*[data-name="'+val+'"]', svgdom).trigger('click');
                    hideShow_tags('Europe');
                } 
            } 
        })
        
        /* fix proporties svg file from amcharts */
        var attr = $('svg', svgdom).attr('xmlns:amcharts');
        if(typeof attr !== typeof undefined && attr !== false) {
            /*console.log($('svg', svgdom).find('g'));*/
            var _h = $('svg', svgdom).find('g').get(0).getBoundingClientRect().height || 500;
            var _w = $('svg', svgdom).find('g').get(0).getBoundingClientRect().width || 1000;
            $('svg', svgdom).attr('viewBox', '0 0 '+_w+' '+(_h+10)+'');
        }
        
        /* end fix proporties svg file */
        
    }
    first_load_map = false;
    
    /* start hint */
    $('*[data-name]', svgdom).hover(function(e){
        var textHin = '';
        $(this).css('cursor', 'pointer');
        
        
        if($(this).attr('data-name') && nameArea[$(this).attr('data-name')]) {
            
            textHint = '<span class="location">'+nameArea[$(this).attr('data-name')] +'</span>';
            if(nameCount[$(this).attr('data-name')]) 
                textHint+=' '+' <span class="count">'+nameCount[$(this).attr('data-name')]+'<br/><?php echo lang_check("Listing");?></span>';
            else
                textHint+=' '+' <span class="count">0<br/><?php echo lang_check("Listings");?></span>';
        } else if($(this).attr('title-hint')) {
            textHint = $(this).attr('title-hint');
        } else {
           return false; 
        }
        
        <?php if(sw_count($treefields)<2):?>
        textHint = textHint.replace(', <?php _che($tree_listings_default[$treefields[0]['id']]->value);?> ', '');
        <?php endif;?>
        
        $('body').append('<div id="map-geo-tooltip-alt">'+textHint+'</div>')
        
        var mapCoord = document.getElementById("svgmap").getBoundingClientRect();
        $(this).mouseover(function(){
            $('#map-geo-tooltip-alt').css({opacity:0.8, display:"none"}).fadeIn(200);
        }).mousemove(function(kmouse){
            
            var max_right = mapCoord.right - 150;
            if(max_right<kmouse.pageX)
                $('#map-geo-tooltip-alt').css({left: 'initial',right:mapCoord.right-kmouse.pageX-32, top:mapCoord.top+kmouse.pageY-65});
            else 
                $('#map-geo-tooltip-alt').css({right: 'initial',left:mapCoord.left+kmouse.pageX-32, top:mapCoord.top+kmouse.pageY-65});
            
        });
        
    }, function() {
        $('#map-geo-tooltip-alt').fadeOut(100).remove();
    })
    /* end hint */
    
}

})
 
</script>

<script>
// change dropdown tree if exist
$('document').ready(function(){
    
     $('.group_location_id input[name="search_option_64"]').change(function(e, trigger){
        if(geo_trigger_treefield) {
            return false;
        }
        
        if(firstload) {
            return false;
        }
        
        var id_region = $(this).val();
        dataPath = $('.geo-menu a[data-id="'+id_region+'"').attr('data-path') || '';
        
        set_path (dataPath, true, false);
        
        dataRegion= $('.geo-menu a[data-id="'+id_region+'"').attr('data-path-map-origin') || '';
        
        var tre_id_split = dataPath.split('_');
        /* start selected area */
        if($('#svgmap').length /*&& tre_id_split[4]<*/){   
            var svgobject = document.getElementById('svgmap');
            if ('contentDocument' in svgobject) {             
                var svgdom = jQuery(svgobject.contentDocument);  
            }
            $('*[data-name]', svgdom).myRemoveClass('highlight');
            
            $('*[data-name]', svgdom).not('.area').css('fill', svg_default_area_color);
            
            $('*[data-name="'+dataRegion+'"]', svgdom).myAddClass('highlight');
            $('*[data-name="'+dataRegion+'"]', svgdom).not('.area').css('fill', svg_selected_area_color);
        }
        /* end selected area */   
    })
    
})

</script>

<script>
/* for first load */
$(window).load(function(){
    var dataPath = '<?php echo search_value($treefield_id); ?>' || '';
    set_path (dataPath, false);
})


function hideShow_tags(parent_seletor) {
    if (typeof parent_seletor === 'undefined') return false;
    if($('a[data-region="'+parent_seletor+'"]').closest('li').find('ul li').length>5) {
        
        var _Liselector = $('a[data-region="'+parent_seletor+'"]').closest('li').find('ul li');
        var _count = 0;
        
        if(_Liselector.hasClass('active'))
            _count = 1;
        
        $.each(_Liselector, function(key, value){
            if(!$(this).hasClass('active') && !$(this).find('a').hasClass('more-tags') && _count>5){
                $(this).css('display', 'none');
            } else {
                $(this).css('display', 'inline-block');
            }
            if(!$(this).hasClass('active'))
                _count++;
        })
        
        if(!$('a[data-region="'+parent_seletor+'"]').closest('li').find('ul li .more-tags').length) {
            $('<li> <a href="#" class="more-tags c-base" data-close="true">more</a></li>').appendTo($('a[data-region="'+parent_seletor+'"]').closest('li').find('ul')).find('.more-tags').click(function(){
               if($(this).attr('data-close') == 'true') {
                    $(this).closest('ul').find('li').css('display', 'inline-block');
                    $(this).attr('data-close', 'false').html('<?php echo _l('short');?>')
                } else if($(this).attr('data-close') == 'false') {
                    hideShow_tags(parent_seletor);
                }
            })
        } else {
          $('a[data-region="'+parent_seletor+'"]').closest('li').find('ul li .more-tags').attr('data-close', 'true').html('<?php echo _l('more');?>')
        }
    } else {
    
    }
}
</script>