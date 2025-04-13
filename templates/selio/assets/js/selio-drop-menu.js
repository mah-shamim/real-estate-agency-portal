/*
 * 
 * @param {type} $
 * @returns {undefined}
 * 

    bind:
    selio_drop_down.on('changed.selio_drop_down', function (event) {
        return event.value
        return event.text
    })
 * 
 * 
 */
!function ($) {

    "use strict";

    $.expr[":"].icontains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    var Selio_Drop_Down = function (element, options, e) {
        this.$element = $(element);
        this.$map = null;
        this.settings = $.extend(true, {}, $.fn.selio_drop_down.defaults_settings, options);
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        this.init();
    };

    Selio_Drop_Down.prototype = {
        constructor: Selio_Drop_Down,
        init: function (options) {
            
            /* check config */
            var self = this;
            
            this.generate_dropdown();
            return self;
        },
        
        generate_dropdown: function (param) {
            var self = this;
            self.$element.on('click', function () {
                self.$element.attr('tabindex', 1).focus();
                self.$element.toggleClass('active');
                self.$element.find('.dropeddown').slideToggle(300);
                $('.drop-menu').not(this).removeClass('active').find('.dropeddown').slideUp(300);
            });
            
            if(self.$element.hasClass('links')) return true;
            
            self.$element.on("focusout", function () {
                $(this).removeAttr('tabindex', 1).focus();
                $(this).removeClass('active');
                $(this).find('.dropeddown').slideUp(300);
            });
            
            self.$element.find('.dropeddown li').on('click', function () {
                var value = $(this).attr('data-value') || $(this).text();
                self.set(value);
            });
            
            /* load in defined value */
            if(self.$element.find('input').val()) {
                self.set(self.$element.find('input').val());
            }
        },

        set : function (value) {
            var self = this;
            var txt = self.$element.find('li[data-value="'+value+'"]').text() || value;
            var val = value;
            if(!self.$element.find('li[data-value="'+value+'"]').length)
                val ='';
            
            self.$element.find('span').text(txt);
            self.$element.find('span').addClass("selected");
            self.$element.find('input').attr('value', val).change();
            self.$element.trigger({
                type: 'changed',
                value: val,
                text: txt
            });
            return true;
        },
        
    };

    $.fn.selio_drop_down = function (option, event) {
        //get the args of the outer function..
        var args = arguments;
        var value;
        var chain = this.each(function () {
            var $this = $(this),
                    data = $this.data('selio_drop_down'),
                    options = typeof option == 'object' && option;

            if (!data) {
                $this.data('selio_drop_down', (data = new Selio_Drop_Down(this, options, event)));
            } else if (options) {
                for (var i in options) {
                    data.options[i] = options[i];
                }
            }

            if (typeof option == 'string') {
                //Copy the value of option, as once we shift the arguments
                //it also shifts the value of option.
                var property = option;
                if (data[property] instanceof Function) {
                    [].shift.apply(args);
                    value = data[property].apply(data, args);
                } else {
                    value = data.options[property];
                }
            }
        });

        if (value != undefined) {
            return value;
        } else {
            return chain;
        }
    };

    $.fn.selio_drop_down.defaults_settings = {
        'translatable': {
            'test': 'test'
        },
    }

}(window.jQuery);
