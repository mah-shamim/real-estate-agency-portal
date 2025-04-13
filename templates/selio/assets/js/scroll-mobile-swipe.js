;(function(factory) {
    if ((typeof jQuery === 'undefined' || !jQuery) && typeof define === "function" && define.amd) {
        define(["jquery"], function (jQuery) {
            return factory(jQuery, document, window, navigator);
        });
    } else if ((typeof jQuery === 'undefined' || !jQuery) && typeof exports === "object") {
        factory(require("jquery"), document, window, navigator);
    } else {
        factory(jQuery, document, window, navigator);
    }
} (function ($, document, window, navigator, undefined) {
    "use strict";

    // =================================================================================================================
    // Service

    var plugin_count = 0;

    if (!Function.prototype.bind) {
        Function.prototype.bind = function bind(that) {

            var target = this;
            var slice = [].slice;

            if (typeof target != "function") {
                throw new TypeError();
            }

            var args = slice.call(arguments, 1),
                bound = function () {

                    if (this instanceof bound) {

                        var F = function(){};
                        F.prototype = target.prototype;
                        var self = new F();

                        var result = target.apply(
                            self,
                            args.concat(slice.call(arguments))
                        );
                        if (Object(result) === result) {
                            return result;
                        }
                        return self;

                    } else {

                        return target.apply(
                            that,
                            args.concat(slice.call(arguments))
                        );

                    }

                };

            return bound;
        };
    }
    if (!Array.prototype.indexOf) {
        Array.prototype.indexOf = function(searchElement, fromIndex) {
            var k;
            if (this == null) {
                throw new TypeError('"this" is null or not defined');
            }
            var O = Object(this);
            var len = O.length >>> 0;
            if (len === 0) {
                return -1;
            }
            var n = +fromIndex || 0;
            if (Math.abs(n) === Infinity) {
                n = 0;
            }
            if (n >= len) {
                return -1;
            }
            k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);
            while (k < len) {
                if (k in O && O[k] === searchElement) {
                    return k;
                }
                k++;
            }
            return -1;
        };
    }



    // =================================================================================================================
    // Template


    // =================================================================================================================
    // Core

    /**
     * Main plugin constructor
     *
     * @param object {Object} link to base input element
     * @param options {Object} slider config
     * @param plugin_count {Number}
     * @constructor
     */
    var WdkScrollMobileSwipe = function (object, options, plugin_count) {
        this.VERSION = "1";
        this.plugin_count = plugin_count;
        this.plugin_count = plugin_count;
        this.object = $(object);
        
        this.is_key = false;
        this.is_update = false;
        this.is_start = true;
        this.is_finish = false;
        this.is_active = false;
        this.is_resize = false;
        this.is_click = false;
        var conf_data = this.object.find('.config') || '';
        var config;
        if(conf_data)
            var config = {
                'predifinedMax' : conf_data.attr('data-predifinedMax') || '',
                'onChange' : ''
            }

        options = options || {};

        this.animate = {
            'duration' : 800
        };

        this.scrollallowwidth = 10; // in %
        this.touchstartX = 0;
        this.touchendX = 0;
        this.childs_positions = {};
        this.child_width = 0;
        this.childrens = null;

        // js config extends default config
        $.extend(config, options);

        this.options = config;

        // validate config, to be sure that all data types are correct
        this.update_check = {};

        this.init();
    };

    WdkScrollMobileSwipe.prototype = {
        /**
         * Starts or updates the plugin instance
         *
         * @param [is_update] {boolean}
         */

        init: function (is_update) {
            if(!this.is_mobile())
                return false;

            var that = this;
            that.object.addClass('WdkScrollMobileSwipe');
            that.childrens = that.object.children();

            that.childrens.each(function(i,v){
                if($(this).offset().left < 0) {
                    that.childs_positions[0] = i;  
                } else {
                    that.childs_positions[$(this).offset().left] = i;  
                }
            });

            that.object.off('touchstart').on( "touchstart", function(event){that.touchstart(event, that)});
            that.object.off('touchend').on( "touchend",  function(event){that.touchend(event, that)});

            jQuery(window).on( "resize",  function(event){that.resized(event, that)});

            return that;
        },

        touchstart: function (e, that) {
            e.stopPropagation();
            that.object.stop();

            if(!that.is_allow()) 
                return false;

            e = e.originalEvent || e;

            that.touchstartX = that.touchendX  = 0;
            if(typeof e.changedTouches[0] != 'undefined') {
                that.touchstartX = e.changedTouches[0].screenX
            } else if(typeof e.targetTouches[0] != 'undefined') {
                that.touchstartX = e.targetTouches[0].screenX
            }
        },

        touchend: function (e, that) {
            if(!that.is_allow()) 
                return false;

            e = e.originalEvent || e;

            if(typeof e.changedTouches[0] != 'undefined') {
                that.touchendX = e.changedTouches[0].screenX
            } else if(typeof e.targetTouches[0] != 'undefined') {
                that.touchendX = e.targetTouches[0].screenX
            }

            that.checkDirection();
        },

        checkDirection: function() {
            if (this.touchendX < this.touchstartX) {
                //alert('swiped left!');
                this.swipeleft();
            } 
            if (this.touchendX > this.touchstartX) {
                //alert('swiped right!');
                this.swiperight()
            }
        },

        swiperight: function() {
            var that = this;

            var scrollLeft = that.object.scrollLeft();
            var element_index = 0;
            var element_posX = 0;

            jQuery.each(that.childs_positions, function(i,v){
                if(+i>+scrollLeft) {
                    return false; // breaks
                }
                element_index = v;
                element_posX = i;
            });

            if(((scrollLeft - element_posX) / that.childrens.eq(element_index).width()) * 100 > that.scrollallowwidth) {
                this.object.stop().animate( { scrollLeft: element_posX }, that.animate.duration);
            } else if(false) {
                this.object.stop().animate( { scrollLeft: element_posX }, that.animate.duration);
            }
        },

        swipeleft: function() {
            var that = this;
            var scrollRight = that.object.scrollLeft() +  that.object.width();
            var element_index = 0;
            var element_posX = 0;

            jQuery.each(that.childs_positions, function(i,v){
                if(+i>+scrollRight) {
                    return false; // breaks
                }
                element_index = v;
                element_posX = i;
            });

            if(element_index > 0 && (((scrollRight - element_posX) / that.childrens.eq(element_index).width()) * 100 > that.scrollallowwidth)) {
                this.object.stop().animate( { scrollLeft: element_posX }, that.animate.duration);
            } else if(false) {
                this.object.stop().animate( { scrollLeft: element_posX }, that.animate.duration);
            }
        },

        /**
         * Allow events
         */
        is_allow: function () {
            if ($(window).width() > 767) {
                return false;
            }

            return true;
        },

        /**
         * Is mobile
         */
        is_mobile: function () {
            let check = false;
            (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
            
            return check;
        },

        /**
         * Resized
         */
        resized: function (event, that) {
            that.childs_positions = {};
            that.childrens.each(function(i,v){
                if($(this).offset().left < 0) {
                    that.childs_positions[0] = i;  
                } else {
                    that.childs_positions[$(this).offset().left] = i;  
                }
            });

        },

        /**
         * Remove slider instance
         * and unbind all events
         */
        remove: function () {
            this.remove();
        }

    };

    $.fn.WdkScrollMobileSwipe = function (options) {
        return this.each(function() {
            if (!$.data(this, "WdkScrollMobileSwipe")) {
                $.data(this, "WdkScrollMobileSwipe", new WdkScrollMobileSwipe(this, options, plugin_count++));
            }
        });
    };

}));
