
/*
Item Name: Winter Dropdown
Author: sanljiljan
Author URI: http://codecanyon.net/user/sanljiljan
Version: 1.0
*/

jQuery.fn.winterDropdown = function (options) 
{
    var defaults = {
        ajax_url: null,
        text_search: 'Search term',
        text_no_results: 'No results found',
        per_page: 10,
        offset: 0,
        show_empty: 0,
        attribute_id: 'id',
        attribute_value: 'address',
        language_id: null
    };
    
    var options = jQuery.extend(defaults, options);
    
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
        options.obj = $(this);
        
        options.firstLoad=true;
        options.endLoad=false;
        
        options.currValue=' - ';
        if(options.obj.val() != '')
            options.currValue = options.obj.val();
        
        generateHtml();

        
        // Add loading indicator
        options.obj.parent().find(".circle-loading-bar").addClass(options.progressBar);

        // open scroll part
        options.obj.parent().find('button').click(function() {
            var container = options.obj.parent().find('.list_container');

            if( container.hasClass('win_visible') )
            {
                container.hide();
                container.removeClass('win_visible');
            }
            else
            {
                container.show();
                container.addClass('win_visible');
                if(options.firstLoad)
                    options.obj.parent().find('.list_scroll').scrollTop(0);
                options.firstLoad=false;
            }
            
            return false;
        });
        
        // hide when click outside
        $(document).mouseup(function (e)
        {
            var container = options.obj.parent().find('.winter_dropdown');
            var container_hidder = options.obj.parent().find('.list_container');

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container_hidder.hide();
                container_hidder.removeClass('win_visible');
            }
        });
        
        // load first n values
        loadMore();
        
        // scroll
        var scroll_container = options.obj.parent().find('.list_scroll');
        var list_items = options.obj.parent().find('.list_items');
        
        $(scroll_container).scroll(function() {
           if($(scroll_container).scrollTop() + $(scroll_container).height() == $(list_items).height()) {
               if(!options.endLoad)
                loadMore();
           }
        });
        
        // keypress/typing event
        $(options.obj.parent().find('.search_term')).keyup(function() {
            options.endLoad=false;
            options.offset=0;
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
        
        // Assign handlers immediately after making the request,
        // and remember the jqxhr object for this request
        var jqxhr = $.post( options.ajax_url, { offset: options.offset, 
                                                per_page: options.per_page, 
                                                curr_id: options.obj.val(),
                                                attribute_id: options.attribute_id,
                                                attribute_value: options.attribute_value,
                                                search_term: search_term_val,
                                                language_id: options.language_id,
                                                show_empty: options.show_empty
                                              }, function(data) {
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
                $.each( data.results, function( key, row ) {
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
        })
        .fail(function() {
            alert( "error" );
            hideSpinner();
        });
    }
    
    function hideSpinner()
    {
        options.obj.parent().find('.loader-spiner').removeClass('icon-spinner');
        options.obj.parent().find('.loader-spiner').removeClass('icon-spin');
        options.obj.parent().find('.loader-spiner').addClass('icon-search');
    }
    
    function showSpinner()
    {
        options.obj.parent().find('.loader-spiner').addClass('icon-spinner');
        options.obj.parent().find('.loader-spiner').addClass('icon-spin');
        options.obj.parent().find('.loader-spiner').removeClass('icon-search');
    }
    
    function resetElements()
    {
        options.obj.parent().find("li *").unbind();
        options.obj.parent().find("li").click(function() {
            options.obj.parent().find('button:first-child').html($(this).html());
            options.obj.val($(this).attr('key'));
            options.obj.parent().find('.list_container').hide();
            options.obj.parent().find('.list_container').removeClass('win_visible');
        });
        
    }
    
    function generateHtml()
    {
        // hide input element
        options.obj.css('display', 'none');

        options.obj.before(
            '<div class="winter_dropdown">'+
            // showing value, always visible
            '<div class="btn-group">'+
            '<button class="btn btn-default" type="button">'+
            options.currValue+'&nbsp;'+
            '</button>'+
            '<button type="button" class="btn btn-default dropdown-toggle"> <span class="caret"></span> </button>'+
            '</div>'+
            // hidden part with scroll and search
            '<div class="list_container">'+
            '<div class="list_scroll">'+
            '<ul class="list_items">'+
//            '<li key="key_1">test text adr 1</li>'+
            '</ul>'+
            '</div>'+
            // search input and loading indicator
            '<div class="input-group">'+
            '<input type="text" class="form-control search_term" placeholder="'+options.text_search+'" aria-describedby="basic-addon2">'+
            '<span class="input-group-addon"><i class="loader-spiner icon-spinner icon-spin"></i></span>'+
            '</div>'+
            '</div>'+
            '</div>'
        );
    }

}




















