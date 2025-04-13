document.addEventListener("DOMContentLoaded", function() {
  var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
  var active = false;

  lazyLoad = function() {
    if (active === false) {
      active = true;

      setTimeout(function() {
        lazyImages.forEach(function(lazyImage) {
          if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {
            lazyImage.src = lazyImage.dataset.src;
            lazyImage.classList.remove("lazy");

            lazyImages = lazyImages.filter(function(image) {
              return image !== lazyImage;
            });

            if (lazyImages.length === 0) {
              document.removeEventListener("scroll", lazyLoad);
              window.removeEventListener("resize", lazyLoad);
              window.removeEventListener("orientationchange", lazyLoad);
            }
          }
        });

        active = false;
      }, 200);
    }
  };

  document.addEventListener("scroll", lazyLoad);
  window.addEventListener("resize", lazyLoad);
  window.addEventListener("orientationchange", lazyLoad);
});

$(document).ready(function(){
    
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
        var allImagesInGallery = $(this).parents('.images-gallery').find('a.preview');
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
    
    /* images gellary for listing preview images */
    $('.property-imgs .property-img img').bind("click", function()
    {
        var myLinks = new Array();
        var current = $(this).attr('data-fullsrc');
        var curIndex = 0;

        $('.property-imgs .property-img img').each(function (i) {
            if(!$(this).attr('data-fullsrc')) return true;
            var img_href = $(this).attr('data-fullsrc');
            myLinks[i] = img_href;
            if(current == img_href)
                curIndex = i;
        });

        var options = {index: curIndex}

        blueimp.Gallery(myLinks, options);

        return false;
    });
    /* end images gellary fro reviews images */
    
    $(".page_content p, .page_content br").each(function(){
         if( $.trim($(this).text()) == "" ){
             $(this).remove();
         }
    });    
        
    if(!$('.bootstrap-wrapper').length) {
        $('body').append('<div class="bootstrap-wrapper"></div>');
    }
    
    $(".login_popup_enabled").on("click", function(e) {
       if($(window).width()>768 && $("#sign-popup").length) {
           e.preventDefault();
           $("#sign-popup").toggleClass("active");
           $("body").addClass("overlay-bgg");
       }
   });
    
    $("html").on("click", function(){
        $("#sign-popup").removeClass("active");
        $("body").removeClass("overlay-bgg");
    });
    
    $(".login_popup_enabled, .popup").on("click", function(e) {
        e.stopPropagation();
    });
    
    
    $(".toggle").each(function(){
        $(this).find('.content').hide();
        $(this).find('h2:first').addClass('active').next().slideDown(500).parent().addClass("activate");
        $('h2', this).click(function() {
            if ($(this).next().is(':hidden')) {
                $(this).parent().parent().find("h2").removeClass('active').next().slideUp(500).removeClass('animated fadeInUp').parent().removeClass("activate");
                $(this).toggleClass('active').next().slideDown(500).addClass('animated fadeInUp').parent().toggleClass("activate");
            }
        });
    });
    
    add_to_favorite();
    remove_from_favorites();

    $('.search-form').find('input[type="text"],input[type="mail"],input[type="password"],input[type="date"], select').change(function(){
        /* selectpicker */
        if($(this).hasClass('selectpicker')) {
            if($(this).val() != '') {
                $(this).parent().find('.btn').addClass('sel_class')
            } else {
                $(this).parent().find('.btn').removeClass('sel_class')
            }
            
        } else {
            if($(this).val() != '') {
                $(this).addClass('sel_class')
            } else {
                $(this).removeClass('sel_class')
            }
        }
    })
    
    // add calendar for all inputs with class .field_datepicker (required unique id)
    $('.field_datepicker').each(function(){
        $(this).datepicker({
            place: function(){
                    var element = this.component ? this.component : this.element;
                    element.after(this.picker);
		},   
            pickTime: false
        });
    })
    
    $('.rating-lst input[name="stars"]').on('change', function(){
        $('#review_star_input').val($(this).val());
    })
    
        
    //dropdown select
    $('.selectpicker').selectpicker({
        style: 'selectpicker-primary',
    });
    
        
    $('#search-additional').on('click', function () {
        if ($('#form-addittional').length) {
            var _fa = $('#form-addittional');
            var _ai = $('#search-additional i');
            var form = $(this).closest('.banner-search_init ');
            var _searchbtn =$('#search-btn').parent();
            form.toggleClass('open-form');
            if (form.hasClass('open-form')) {
                _ai.removeClass('fa-minus-circle').addClass('fa-plus-circle');
                _fa.slideDown();
            } else {
                _ai.removeClass('fa-plus-circle').addClass('fa-minus-circle');
                _fa.slideUp();
            }
        }
    })
    if($('.banner-search.banner-search_init #form-addittional .form_field').length) {
        $('.banner-search.banner-search_init #form-addittional .form_field').each(function(i,k){
            $(this).css('transition-delay', i*0.15+'s').css('-webkit-transition-delay', i*0.15+'s');
        })
        
        $('.banner-search.banner-search_init').append($('.banner-search.banner-search_init #form-addittional').detach());
        
    }
    
    
    
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
            var conf_direction =  '';

            if(th_scale.find('.config-range').length ) {
                var $config = th_scale.find('.config-range');
                conf_min = $config.attr('data-min') || 0;
                conf_max = $config.attr('data-max') || '';
                conf_sufix= $config.attr('data-sufix') || '';
                conf_prefix= $config.attr('data-prefix') || '';
                conf_infinity = $config.attr('data-infinity') || "false";
                conf_predifinedMin = $config.attr('data-predifinedMin') || '';
                conf_predifinedMax = $config.attr('data-predifinedMax') || '';
                conf_direction = $config.attr('data-direction') || '';
            }
            scale_range('#'+th_scale_id,conf_min,conf_max,conf_prefix,conf_sufix,conf_infinity,conf_predifinedMin,conf_predifinedMax,conf_direction);
       
        })
    }
    
    if($('[autocomplete="off"]').length)
        if(navigator.userAgent.toLowerCase().indexOf('firefox')<0)
            $('[autocomplete="off"]').attr('autocomplete', 'nope')
    
    $('.list-maps a').click(function(e){
        $(this).find('.fa-ajax-indicator').removeClass('hidden');
    });
})

function isScrolledIntoView(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function support_history_api()
{
    return !!(window.history && history.pushState);
}

/* End Image gallery script. Big Image */ 

function custom_number_format(val)
{
    return val.toFixed(2);
}

function encodeHTML(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
}

function cleanerHTML(s) {
    return s.replace(/&/g, '').replace(/</g, '').replace(/"/g, '');
}

// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};

function array_move(arr, old_index, new_index) {
    if (new_index >= arr.length) {
        var k = new_index - arr.length + 1;
        while (k--) {
            arr.push(undefined);
        }
    }
    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
    return arr; // for testing
};


function scale_range(object, min, max, prefix, sufix, infinity, predifinedMin, predifinedMax, direction) {
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
    if (typeof direction == 'undefined' || direction=='')
        var direction = 'ltr';
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
            direction: direction,
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
        
        var noUiSlider_ini = 0;
        
        nonLinearSlider.noUiSlider.on('update', function (values, handle, unencoded, isTap, positions) {
            noUiSlider_ini++;
            
            if(parseInt(values[handle]) > max && infinity){
                nodes[handle].innerHTML = prefix + selio_number_format(parseInt(max)) + sufix+'+';
            }
            else if(parseInt(values[handle]) < min && infinity){
                nodes[handle].innerHTML = prefix +selio_number_format(parseInt(min)) + sufix+'-';
            }
            else
                nodes[handle].innerHTML = prefix + selio_number_format(parseInt(values[handle])) + sufix;
            
            if(parseInt(values[handle]) > max && infinity){
                inputs[handle].value = '';
            }
            else if(parseInt(values[handle]) < min && infinity){
                inputs[handle].value = '';
            }
            else if(noUiSlider_ini>2)
                inputs[handle].value = parseInt(values[handle]).toFixed();
                   
            if(noUiSlider_ini>2 && $(object + ' .value-max').val()=='') {
                $(object + ' .value-max').val(predifinedMax);
            }
            
            $(object + ' .value-min, '+object + ' .value-max').trigger('change')
        });
    }
    
}

function selio_number_format(number, format) {
    if(typeof format == 'undefined') var  format = true;
    
    if(format)
        return new Intl.NumberFormat('de-DE').format(number.toFixed());
    else
        return number.toFixed();
        
}