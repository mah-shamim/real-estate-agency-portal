jQuery(document).ready(function($) {
    "use strict";

    $(".features-dv form ul li input:checkbox").on("click", function() { return false; });

    $(".rtl-select").on("click", function() {
        window.location.href="17_Features_Example_Alt_Titlebar.rtl.html"
    });
    $(".eng-select").on("click", function() {
        window.location.href="17_Features_Example_Alt_Titlebar.html"
    });

    /*==============================================
                      Custom Dropdown
    ===============================================*/

    $('.drop-menu').selio_drop_down();

    $('.drop-menu').on('changed.selio_drop_down', function (event) {
        
    })

    /*==============================================
                FEATURES TOGGLE FUNCTION
    ===============================================*/

    $(".more-feat > h3").on("click", function(){
        $(".features_list").slideToggle();
    });

    /*==============================================
                    HALF MAP POSITIONING
    ===============================================*/

    var hd_height = $("header").outerHeight();
    var pre_h = 0;
    if($(".admin-bar").length) 
        pre_h = 35;
    
    $(".half-map-sec #map-container.fullwidth-home-map").css({
        "top": hd_height+pre_h
    });
    $(".half-map-sec").css({
        "margin-top": hd_height
    });

    /*==============================================
        SETTING POSITION ACRD TO CONTAINER
    ===============================================*/
    
    $(".close-menu").on("click", function(){
        $(".navbar-collapse").removeClass("show");
        return false;
    });


    /*==============================================
                    SMOOTH SCROLLING
    ===============================================*/

    /* anchor */
    $('a[href^="#"]:not([data-toggle="tab"]):not(.disable_scroll)').on('click',function(e){
        e.preventDefault();
        var hash = $(this).attr('href');
        if(hash.length > 1  && $(hash).length){
            document.querySelector(hash).scrollIntoView({
                    behavior: 'smooth'
                });
            }
    });

    /*==============================================
                      DROPDOWN EFFECT
    ===============================================*/

    $('.dropdown').on('show.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });

    $('.dropdown').on('hide.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });

    /*==============================================
                      ALERT FUNCTIONS
    ===============================================*/

    $(".close-alert").on("click", function(){
        $(".alert-success").removeClass("active");
        return false;
    });
    
    /*==============================================
                      WordPress
    ===============================================*/

    /* Start Image gallery 
        *    use css/blueimp-gallery.min.css
        *    use js/blueimp-gallery.min.js
        *    Site https://github.com/blueimp/Gallery/blob/master/README.md#setup
        */
        if(!$('#blueimp-gallery').length){
            $('body').append('<div id="blueimp-gallery" class="blueimp-gallery">\n\
                <div class="slides"></div>\n\
                <h3 class="title"></h3>\n\
                <div class="description"></div>\n\
                <a class="prev">&lsaquo;</a>\n\
                <a class="next">&rsaquo;</a>\n\
                <a class="close">&times;</a>\n\
                <a class="play-pause"></a>\n\
                <ol class="indicator"></ol>\n\
                </div>')
        }
        $('.images-gallery a.preview').on('click', function(e){
            e.preventDefault();
            var myLinks = new Array();
            var current = $(this).attr('href');
            var curIndex = 0;
            var descriptions = new Array();
            var allImagesInGallery = $(this).parents('#custom-gallery').find('a.preview');
            allImagesInGallery.each(function (i) {
                var img_href = $(this).attr('href');
                myLinks[i] = img_href;
                if (current === img_href)
                    curIndex = i;
                descriptions[i] = $(this).attr('data-description') || '';
            });
            var options = {index: curIndex, onslide: function (index, slide) {
                $('#blueimp-gallery .description').html(descriptions[index]);
            }}
            blueimp.Gallery(myLinks, options);
            return false;
        });
});
