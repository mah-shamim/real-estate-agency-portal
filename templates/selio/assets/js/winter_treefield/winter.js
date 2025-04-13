
/*
Item Name: Winter Treefield
Author: sanljiljan
Author URI: http://codecanyon.net/user/sanljiljan
Version: 1.0
*/

jQuery.fn.winterTreefield = function (options) 
{
    var defaults = {
        ajax_url: null,
        ajax_param: {},
        text_search: 'Search term',
        text_no_results: 'No results found',
        per_page: 10,
        offset: 0,
        attribute_id: 'id',
        attribute_value: 'value',
        language_id: null,
        skip_id: null,
        empty_value: ' - ',
        callback_selected: function(key) {
            console.log('called callback: '+key);
        }
    };
    
    var options = jQuery.extend(defaults, options);
    
    var jqxhr;
    var is_loading = false;
    var request;
    
    /* Public API */
    /*
    this.getCurrent = function()
    {
        return options.currElImg;
    }

    this.getIndex = function(){
        return options.currIndex;
    };
    */
        
    return this.each (function () 
    {
        options.obj = jQuery(this);
        
        options.firstLoad=true;
        options.endLoad=false;
        
        options.currValue=options.empty_value;
        if(options.obj.val() != '')
            options.currValue = options.obj.val();
        
        generateHtml();

        
        // Add loading indicator
        options.obj.parent().find(".circle-loading-bar").addClass(options.progressBar);

        // open scroll part
        options.obj.parent().find('.btn-group:first button').click(function() {
            var container = options.obj.parent().find('.list_container');

            if( container.hasClass('win_visible') )
            {
                container.hide();
                container.removeClass('win_visible');
                container.parent().removeClass('win_open');
            }
            else
            {
                container.show();
                container.parent().addClass('win_open');
                container.addClass('win_visible');
                if(options.firstLoad)
                    options.obj.parent().find('.list_scroll').scrollTop(0);
                options.firstLoad=false;
                
                jQuery(options.obj.parent().find('.search_term')).focus();
            }
            
            return false;
        });
        
        // hide when click outside
        jQuery(document).mouseup(function (e)
        {
            var container = options.obj.parent().find('.winter_dropdown_tree');
            var container_hidder = options.obj.parent().find('.list_container');

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container_hidder.hide();
                container_hidder.parent().removeClass('win_open');
                container_hidder.removeClass('win_visible');
            }
        });
        
        // load first n values
        loadMore();
        
        // scroll
        var scroll_container = options.obj.parent().find('.list_scroll');
        var list_items = options.obj.parent().find('.list_items');
        
        jQuery(scroll_container).scroll(function() {
           if(jQuery(scroll_container).scrollTop() + jQuery(scroll_container).height() == jQuery(list_items).height()) {
               if(!options.endLoad)
                loadMore();
           }
        });
        
        // keypress/typing event
        jQuery(options.obj.parent().find('.search_term')).keyup(function() {
            options.endLoad=false;
            options.offset=0;
            
            //if(is_loading === false)
                loadMore();
            
        }).keypress(function(event) {
            if ( event.which == 13 ) {
                return false;
            }
        });
        
        jQuery(options.obj.parent().find('span.input-group-addon')).click(function() {
            options.endLoad=false;
            options.offset=0;
            
            if(is_loading === false)
                loadMore();
        });
        
        
        
        return this;
    });
    
    function loadMore()
    {
        showSpinner();
        
        var search_term_val = options.obj.parent().find('.search_term').val();
        
        if(options.offset == 0)
            options.obj.parent().find('ul').html('');
        
        var param = {   offset: options.offset, 
                        per_page: options.per_page, 
                        curr_id: options.obj.val(),
                        attribute_id: options.attribute_id,
                        attribute_value: options.attribute_value,
                        search_term: search_term_val,
                        language_id: options.language_id,
                        skip_id: options.skip_id
                      };
        
        jQuery.extend( param, options.ajax_param );
        
        // Assign handlers immediately after making the request,
        // and remember the jqxhr object for this request
        if(jqxhr != null)
            jqxhr.abort();
        
        options.callback_selected(options.obj.val());
        
        is_loading=true;
        jqxhr = jQuery.post( options.ajax_url, param, function(data) {
            hideSpinner();
            var list_items = options.obj.parent().find('ul');
            
            if(data.success == false)
            {
                options.endLoad=true;
                list_items.html('<span class="no_results">'+options.text_no_results+'</span>');
                alert(data.message);
            }
            else
            {
                jQuery.each( data.results, function( key, row ) {
                    if(key == 'lang_id') return true;
                    list_items.append("<li key='"+row.key+"'>"+row.value+"</li>");
                });
                
                if(options.offset == 0)
                    options.obj.parent().find('.list_scroll').scrollTop(0);
                
                options.obj.parent().find('button:first-child').html(data.curr_val);
                
                if(data.results.length == 0)
                    options.endLoad=true;
                    
                if(options.offset == 0 && data.results.length == 0)
                    list_items.html('<span class="no_results">'+options.text_no_results+'</span>');
                
                options.offset+=options.per_page;
                resetElements();
            }
            
            is_loading=false;
        })
        .fail(function() {
            //alert( "error" );
            console.log( "abort" );
            hideSpinner();
            is_loading=false;
        });
    }
    
    function hideSpinner()
    {
        options.obj.parent().find('.loader-spiner').removeClass('fa-spinner');
        options.obj.parent().find('.loader-spiner').removeClass('fa-spin');
        options.obj.parent().find('.loader-spiner').addClass('fa-search');
    }
    
    function showSpinner()
    {
        options.obj.parent().find('.loader-spiner').addClass('fa-spinner');
        options.obj.parent().find('.loader-spiner').addClass('fa-spin');
        options.obj.parent().find('.loader-spiner').removeClass('fa-search');
    }
    
    function resetElements()
    {
        options.obj.parent().find("li *").unbind();
        options.obj.parent().find("li").click(function() {
            options.obj.parent().find('button:first-child').html(jQuery(this).html());
            options.obj.val(jQuery(this).attr('key'));
            options.obj.parent().find('.list_container').hide();
            options.obj.parent().find('.list_container').parent().removeClass('win_open');
            options.obj.parent().find('.list_container').removeClass('win_visible');
            
            options.callback_selected(jQuery(this).attr('key'));
        });
        
    }
    
    function generateHtml()
    {
        // hide input element
        options.obj.css('display', 'none');

        options.obj.before(
            '<div class="winter_dropdown_tree color-secondary">'+
            // showing value, always visible
            '<div class="btn-group ">'+
            '<button class="btn btn-default color-secondary" type="button">'+
            options.currValue+'&nbsp;'+
            '</button>'+
            '<button type="button" class="btn btn-default dropdown-toggle color-secondary"> <span class="glyphicon glyphicon-menu-down"></span> </button>'+
            '</div>'+
            // hidden part with scroll and search
            '<div class="list_container color-primary">'+
            '<div class="list_scroll">'+
            '<ul class="list_items">'+
//            '<li key="key_1">test text adr 1</li>'+
            '</ul>'+
            '</div>'+
            // search input and loading indicator
            '<div class="input-group">'+
            '<input type="text" class="form-control color-secondary search_term" placeholder="'+options.text_search+'" aria-describedby="basic-addon2"  autocomplete="off">'+
            '<span class="input-group-addon color-secondary"><i class="loader-spiner fa fa-spinner fa-spin"></i></span>'+
            '</div>'+
            '</div>'+
            '</div>'
        );
    }

}




















