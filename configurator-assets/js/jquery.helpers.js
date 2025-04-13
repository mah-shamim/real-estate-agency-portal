/**
 * This tiny script just helps us show save status
 */
var ShowStatus = (function() {
    "use strict";

    var elem,
        hideHandler,
        that = {};

    that.init = function(options) {
        $('body').append('<div style="display: none; z-index:1040;" class="bb-alert alert alert-error"><span>Data saved...</span></div>');
        elem = $('div.bb-alert');
    };

    that.show = function(text) {
        clearTimeout(hideHandler);
        
        if($('div.bb-alert').length == 0)
        {
            that.init();
        }

        elem.find("span").html(text);
        elem.fadeIn(200);

        hideHandler = setTimeout(function() {
            that.hide();
        }, 4000);
    };

    that.hide = function() {
        elem.fadeOut(200);
    };

    return that;
}());