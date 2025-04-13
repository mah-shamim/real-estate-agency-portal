// Trigger CSS animations on scroll.
// Scroll animation v0.01

/* TOOLS */

jQuery(function($) {
    
    $.fn.sw_isInViewport  = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();

        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height()-40;

        return elementBottom > viewportTop && elementTop < viewportBottom;
    }; 

    sw_anim_scroll();

});

function sw_anim_scroll() {
    var $ = jQuery;
    var that = this;
    
    that.sw_anim_isMobile = function () {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
    }
    
    if($(window).width()<790 || that.sw_anim_isMobile()) {
        $('.sw_animatable').removeClass('sw_animatable').addClass('sw_animated sw_disabled_animated');
    }
    
    // Calc current offset and get all animatables
    var $animatables = $('.sw_animatable');

    // Function which adds the 'animated' class to any '.animatable' in view
    var doAnimations = function() {
        if($(window).width()<790 || sw_anim_isMobile()) {
            $('.sw_animatable').removeClass('sw_animatable').addClass('sw_animated sw_disabled_animated');
        }

        
        // Unbind scroll handler if we have no animatables
        if ($animatables.length == 0) {
            $(window).off('scroll', doAnimations);
        }

        // Check all animatables and animate them if necessary
        $animatables.each(function(i) {
        var $animatable = $(this);

        if ($animatable.sw_isInViewport()) {
                $animatable.removeClass('sw_animatable').addClass('sw_animated');
        } else {
            //$animatable.removeClass('sw_animated').addClass('sw_animatable');
        } 
        });
    };

    // Hook doAnimations on scroll, and trigger a scroll
    $(window).on('scroll', doAnimations);
    $(window).trigger('scroll');
}

