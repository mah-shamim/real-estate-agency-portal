
/*
Item Name: Winter Treefield
Author: sanljiljan
Author URI: http://codecanyon.net/user/sanljiljan
Version: 1.0
*/

jQuery.fn.winterTreefieldAlt = function (options) 
{
    var defaults = {
        ajax_url: null,
        ajax_url_second: null,
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
    var blocker = 10;
    
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
        
        // load first n values
        loadMore(0, options.obj.val());

        return this;
    });
    
    function loadMore(parent_id, current_id)
    {
        if (typeof(parent_id)==='undefined') parent_id = 0;
        if (typeof(current_id)==='undefined') current_id = 0;

        showSpinner(); 
        if(blocker == 0)return;
        
        
        var param = {   
                        curr_id: current_id,
                        language_id: options.language_id,
                        attribute_id: options.attribute_id,
                        attribute_value: options.attribute_value,
                        pare_id: parent_id
                      };
        jQuery.extend( param, options.ajax_param );

        // Assign handlers immediately after making the request,
        // and remember the jqxhr object for this request
        if(jqxhr != null)
            jqxhr.abort();
        
        is_loading=true;
        jqxhr = jQuery.post( options.ajax_url, param, function(data) {
            hideSpinner();
            options.obj.parent().parent().find('.winter_dropdown_tree').remove();
            if(data.success == false)
            {
                options.endLoad=true;
                alert(data.message);
            }
            else
            {
                if(parent_id == 0){
                    parent_id = 0;
                    if(data.results.length > 1)
                    if(!options.obj.parent().find('.level_'+parent_id).length)
                    {
                        
                    options.obj.parent().find('.winter_dropdown_treeselect_alt_container').append('<select class="level_'+parent_id+'"></select>');
                    
                    options.obj.parent().find('.level_'+parent_id).change(function() 
                    {
                        jQuery( this ).nextAll().remove();

                        //Change value
                        options.obj.val(jQuery( this ).val());

                        if(jQuery( this ).val() == '')
                        {
                            if(jQuery( this ).prev().length > 0)
                            options.obj.val(jQuery( this ).prev().val());
                        }

                        loadMore(jQuery( this ).val());
                    });
                }
                } else {
                    
                }
            if(data.results.length > 1)
                jQuery('#'+options.obj.attr('id')).winterTreefield({
                    ajax_url: options.ajax_url_second,
                    ajax_param: { 
                                  "page": 'frontendajax_treefieldidalt',
                                  "action": 'ci_action',
                                  "table": param.table,
                                  "field_id": param.field_id,
                                  "empty_value": param.empty_value,
                                  "filter_ids": parent_id+'_fetch_child',
                                  "sub_empty_value": param.sub_empty_value,
                                  "start_id": parent_id,
                                  "city": 'true',
                                },
                    attribute_id: param.attribute_id,
                    language_id: param.language_id,
                    attribute_value:param.attribute_value,
                    skip_id: '',
                    empty_value: ' - ',
                    text_search: 'Search term',
                    text_no_results: 'No results found',
                    callback_selected: function(key) {
                        options.callback_selected(options.obj.val());
                    }
                });
                if(!options.obj.parent().find('.level_'+parent_id).find('option').length)
                {
                    jQuery.each( data.results, function( key, row ) {
                        if(key == 'lang_id') return true;

                        append_option = '<option value="'+row.key+'">'+row.value +'</option>';
                        
                        var s_values_splited = options.obj.val().split(" - "); 
                        if(s_values_splited[0] != '')
                        if(s_values_splited[0]+' -' == row.key || s_values_splited[0] == row.key){
                            append_option = '<option value="'+row.key+'" selected="selected">'+row.value +'</option>';
                        }

                        options.obj.parent().find('.level_'+parent_id).append( append_option );
                    });
                }
                
                if(options.offset == 0)
                    options.obj.parent().find('.list_scroll').scrollTop(0);
                
                options.obj.parent().find('button:first-child').html(data.curr_val);
                
                if(data.results.length == 0)
                    options.endLoad=true;

                options.offset+=options.per_page;
                resetElements();

                if(data.next_parent_id != 0)
                {
                    blocker--;
                    loadMore(data.next_parent_id, options.obj.val());
                }
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
        options.obj.parent().find('.loader-spiner_alt').removeClass('fa-spinner');
        options.obj.parent().find('.loader-spiner_alt').removeClass('fa-spin');
    }
    
    function showSpinner()
    {
        options.obj.parent().find('.loader-spiner_alt').addClass('fa-spinner');
        options.obj.parent().find('.loader-spiner_alt').addClass('fa-spin');
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
            '<div class="winter_dropdown_treeselect_alt color-secondary">'+
            '<span class="winter_dropdown_treeselect_alt_container">'+
            '</span>'+
            '<i class="loader-spiner_alt fa fa-spinner fa-spin"></i>'+
            '</div>'
        );
    }

    function generateHtml2()
    {
        // hide input element
        options.obj.css('display', 'none');

        options.obj.before(
            '<div class="winter_dropdown_treeselect_alt color-secondary">'+
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
            '<span class="input-group-addon color-secondary"><i class="loader-spiner_alt fa fa-spinner fa-spin"></i></span>'+
            '</div>'+
            '</div>'+
            '</div>'
        );
    }
    
}












