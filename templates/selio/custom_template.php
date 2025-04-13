<!doctype html>
<html class="no-js" lang="en">
<head>
    <?php _widget('head');?>
    <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?> 
    <!-- Start masonry -->
    <script src='assets/libraries/masonry/dist/masonry.pkgd.min.js'></script>
    <script src='assets/libraries/masonry/dist/imagesloaded.pkgd.min.js'></script>
    <!-- End masonry -->
    <?php endif;?>
</head>
<body class='modal-backdrop-effect dissable-sticky <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?> admin_access <?php endif;?>'>
    <div class="wrapper">
        <header>
            <?php _widget('custom_header_menu-for-loginuser_wide');?>
            <div class="wpart">
            <?php
            foreach ($widgets_order->header as $widget_filename) {
                _widget($widget_filename);
            }
            ?>
            <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?>
            <div class="add-widget-area">
                <a href="#" class="modal_event btn-add" data-type="header" class="btn-add"><i class="fa fa-plus"></i></a>
            </div>
            <?php endif;?>
            </div>
        </header><!--header end-->
        <div class="wpart">
            <?php
            foreach ($widgets_order->top as $widget_filename) {
                _widget($widget_filename);
            }
            ?>
        <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?>
        <div class="add-widget-area">
            <a href="#" class="modal_event btn-add" data-type="top" class="btn-add"><i class="fa fa-plus"></i></a>
        </div>
        <?php endif;?>
        </div>
        
        <?php if(
               !empty($widgets_order->center) ||
               !empty($widgets_order->right) ||
               (!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN')
               ):?>
        
        <section class="listing-main-sec section-padding2 pb15">
            <div class="container">
                <div class="listing-main-sec-details">
                    <div class="row">
                        <div class="col-lg-8 wpart <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?> dashed <?php endif;?>">
                            <?php
                            foreach ($widgets_order->center as $widget_filename) {
                                _widget($widget_filename);
                            }
                            ?>
                            <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?>
                            <div class="add-widget-area">
                                <a href="#" class="modal_event btn-add" data-type="center" class="btn-add"><i class="fa fa-plus"></i></a>
                            </div>
                            <?php endif;?>
                        </div>
                        <div class="col-lg-4">
                            <div class="sidebar layout2">
                                <div class="wpart <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?> dashed <?php endif;?>">
                                    <?php
                                    foreach ($widgets_order->right as $widget_filename) {
                                        _widget($widget_filename);
                                    }
                                    ?>
                                    <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?>
                                    <div class="add-widget-area">
                                        <a href="#" class="modal_event btn-add" data-type="right" class="btn-add"><i class="fa fa-plus"></i></a>
                                    </div>
                                    <?php endif;?>
                                </div>
                            </div><!--sidebar end-->
                        </div>
                    </div>
                </div><!--listing-main-sec-details end-->
            </div>    
        </section><!--listing-main-sec end-->
        <?php endif;?>
        <div class="wpart">
        <?php
        foreach ($widgets_order->bottom as $widget_filename) {
            _widget($widget_filename);
        }
        ?>
        <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?>
        <div class="add-widget-area">
            <a href="#" class="modal_event btn-add" data-type="bottom" class="btn-add"><i class="fa fa-plus"></i></a>
        </div>
        <?php endif;?>
        </div>




        <section class="bottom section-padding">
            <div class="container placeholder-container">
                <div class="row wpart">
                    <?php
                    foreach ($widgets_order->footer as $widget_filename) {
                        _widget($widget_filename);
                    }
                    ?>
                    <?php if(!empty($is_logged_other) && $this->session->userdata('type') == 'ADMIN'):?>
                    <div class="col-sm-12 add-widget-area">
                        <a href="#" class="modal_event btn-add" data-type="footer" class="btn-add"><i class="fa fa-plus"></i></a>
                    </div>
                    <?php endif;?>
                </div>
                <img src="assets/img/<?php echo (sw_is_safari()) ? 'placeholder-footer.png' : 'placeholder-footer.webp';?>" alt="placeholder" class="footer-placeholder">
            </div>
        </section>
        <footer class="footer">
            <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-12 ">
                            <div class="copyright text-center footer-content ">
                                <p>© <?php echo lang_check('Selio theme made in EU. All Rights Reserved');?>.</p>
                            </div>
                        </div>
                    </div>
            </div>
        </footer><!--footer end-->
    </div><!--wrapper end-->
    <?php _widget('custom_javascript');?>


             
        <?php if(!empty($is_logged_other)):?>
        <!-- Modal -->
        <div class="modal md-effect-1 location_modal visual_widgets" id="visual_widgets" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo lang_check("Add widget for");?> <span class="badge badge-info"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body result_preload_box">
                        <div class="w_cont">
                            <div class="row"></div>
                        </div>
                        <div class="result_preload_indic">
                            <div class="loader">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="result_preload_indic_fixed_full">
            <div class="cssload-square">
                <div class="cssload-square-part cssload-square-green"></div>
                <div class="cssload-square-part cssload-square-pink"></div>
                <div class="cssload-square-blend"></div>
            </div>
        </div>
        <script>
            $('.modal_event').click(function(e){
                e.preventDefault();
                var self = $(this);
                var $modal = $('#visual_widgets');
                $modal.find('.modal-body .w_cont').html('');
                $modal.modal('show');
                var widget_type = $(this).attr('data-type');
                $modal.find('.result_preload_indic').show();
                $modal.find('.modal-title .badge').html(widget_type);
                $modal.find('.modal-body').css({'height':'calc( 100vh - 250px)','max-height':'calc( 100vh - 180px)','overflow':'hidden'});
               
                $.post("{api_private_url}/visual_edit_getwidgets/{lang_code}/"+widget_type, [], 
                    function(data){
                        
                    if(data.message)
                    {
                        ShowStatus.show(data.message)
                    }    
                        
                    /* load widgets preview */
                    var html = '';
                    if(data.success)
                    {
                        var html = '<div class="row">';
                        $.each(data.widgets, function(key, val){
                            html += '<div class="col-sm-4 col-md-4">\n\
                                            <a href="#" class="visual_widget-thumbnail" title="'+val.title+'" data-type="'+widget_type+'" data-filename="'+val.filename+'">\n\
                                                <div class="visual_widget-thumbnail-box">\n\
                                                    <img src="'+val.thumbnail+'" alt="widget">\n\
                                                </div>\n\
\n\                                             <h2 class="title">'+val.title+'</h2>\n\
                                            </a>\n\
                                        </div>'
                        });
                        html += '</div>';
                        
                        $modal.find('.modal-body .w_cont').html(html)
                        
                        $modal.find('.modal-body .w_cont').imagesLoaded()
                            .done( function( instance ) {
                                setTimeout(function(){
                                    $modal.find('.modal-body .w_cont .row').masonry({})
                                    $modal.find('.modal-body').css({'height':'initial','max-height':'initial','overflow':'initial'});
                                    $modal.find('.result_preload_indic').hide();
                                },1500);
                            })
                    }
                    /* end load widgets */
                 }).success (function(){
                     /* add widget*/
                     $('.modal-body .w_cont .row .visual_widget-thumbnail').click(function(e){
                        e.preventDefault();
                        var widget_filename = $(this).attr('data-filename');
                        var widget_type = $(this).attr('data-type');
                        $('.result_preload_indic_fixed_full').show();
                        $.post("{api_private_url}/visual_edit_getwidget_content/{lang_code}/"+widget_filename, [], 
                            function(data){
                                
                            if(data.message)
                            {
                                ShowStatus.show(data.message)
                            } 
                                
                            if(data.success)
                            {
                                $('.result_preload_indic_fixed_full').hide();
                                var $obj_widgets = $('.visual_widgets_save form #widgets_order_json').val();
                                if($obj_widgets =='')
                                    $obj_widgets = [];
                                else
                                    $obj_widgets = $.parseJSON($obj_widgets)
                                
                                if(typeof $obj_widgets[widget_type] == 'undefined') {
                                    $obj_widgets[widget_type]=new Array;
                                }
                                
                                if($obj_widgets[widget_type].indexOf(widget_filename) == 'undefined' || $obj_widgets[widget_type].indexOf(widget_filename) >=0) {
                                    ShowStatus.show('<?php echo lang_check('Widget alredy exists');?>');
                                    return false;
                                }
                                
                                $obj_widgets[widget_type].push(widget_filename)
                                $obj_widgets = JSON.stringify($obj_widgets);
                                $('.visual_widgets_save form #widgets_order_json').val($obj_widgets);
                                
                                var this_widget = $( data.content ).insertBefore( self.parent() );
                                
                                
                                $modal.modal('hide');
                                $('.visual_widgets_save').slideDown();
                                
                                /* features control widget */
                                
                                this_widget.append( '<div class="widget_controls_panel">\n\
                                    <a href="#" class="btn btn-move btn-up" data-diff="-1" data-widgetfilename="'+widget_filename+'"><i class="fa fa-arrow-up"></i></a>\n\
                                    <a href="#" class="btn btn-move btn-down" data-diff="+1"  data-widgetfilename="'+widget_filename+'"><i class="fa fa-arrow-down"></i></a>\n\
                                    <a href="#" class="btn btn-remove" data-widgetfilename="'+widget_filename+'"><i class="fa fa-trash"></i></a>\n\
                                </div>');

                                /* removed */
                                this_widget.find('.btn.btn-remove').on('click', function(e){
                                    e.preventDefault();

                                    $('.result_preload_indic_fixed_full').hide();
                                    var self = $(this);

                                    var $obj_widgets = $('.visual_widgets_save form #widgets_order_json').val();
                                    if($obj_widgets =='') 
                                        return true;

                                    $obj_widgets = $.parseJSON($obj_widgets)

                                    if(typeof $obj_widgets[widget_type] == 'undefined') {
                                        $obj_widgets[widget_type]=new Array;
                                    }

                                    var i = $obj_widgets[widget_type].indexOf(widget_filename);
                                    if( i >= 0) {

                                        ShowStatus.show('<?php echo lang_check('Widget removed');?>');
                                        //delete $obj_widgets[widget_type][i]; 
                                        $obj_widgets[widget_type].remove(i); 

                                        $obj_widgets = JSON.stringify($obj_widgets);
                                        $('.visual_widgets_save form #widgets_order_json').val($obj_widgets);

                                        $('.visual_widgets_save').slideDown();
                                        self.closest('.widget_edit_enabled,.widget_edit').remove();
                                    }
                                })
                                
                                /* moved */
                                this_widget.find('.btn.btn-move').on('click', function(e){
                                    e.preventDefault();

                                    $('.result_preload_indic_fixed_full').hide();
                                    var self = $(this);
                                    var widget_diff= self.attr('data-diff') || '';
                                    
                                    var $obj_widgets = $('.visual_widgets_save form #widgets_order_json').val();
                                    if($obj_widgets =='') 
                                        return true;

                                    $obj_widgets = $.parseJSON($obj_widgets)

                                    if(typeof $obj_widgets[widget_type] == 'undefined') {
                                        $obj_widgets[widget_type]=new Array;
                                    }

                                    var i = $obj_widgets[widget_type].indexOf(widget_filename);
                                    if( i >= 0) {
                                        var elem = '';
                                        switch(widget_diff) {
                                            case '+1' : 
                                                        if(!this_widget.next().length) break;
                                                        if(this_widget.next().length  && this_widget.next().hasClass('add-widget-area')) break;
                                                        $obj_widgets[widget_type] = array_move($obj_widgets[widget_type], i, i+1);
                                                        elem = this_widget.next();
                                                        /* move element */
                                                        $( this_widget.detach() ).insertAfter(elem);
                                                        break;

                                            case '-1' : 
                                                        if(!this_widget.prev().length) break;
                                                        $obj_widgets[widget_type] = array_move($obj_widgets[widget_type], i, i-1);
                                                        elem = this_widget.prev();
                                                        /* move element */
                                                        $( this_widget.detach() ).insertBefore(elem);
                                                        break;
                                            }
                                        if(elem =='') return;
                                        $obj_widgets = JSON.stringify($obj_widgets);
                                        $('.visual_widgets_save form #widgets_order_json').val($obj_widgets);
                                        $('.visual_widgets_save').slideDown();
                                    }
                                })
                                
                                /* end features control widget */
                            }
                         }).success(function(){render_parallax();})
                     })
                    /* end add widget*/
                 })
            })
        </script>
            <?php
            $template_id = substr($page_template, 7);
            $CI = &get_instance();
            $CI->load->model('customtemplates_m');
            $listing = $CI->customtemplates_m->get($template_id);
            $listing = (array)$listing;
            ?>
            <div class="visual_widgets_save" style="display: none;">
                <form action="#" method="post">                              
                    <div class="hidden">
                        <input type="text" name="template_id" value="<?php echo _ch($template_id); ?>" class="form-control">                                  
                        <input type="text" name="theme" value="<?php echo _ch($listing['theme']); ?>" class="form-control" readonly="">                                 
                        <input type="text" name="template_name" value="<?php echo _ch($listing['template_name']); ?>" placeholder="Template name" class="form-control">                                  
                        <input type="text" name="type" value="<?php echo _ch($listing['type']); ?>" class="form-control" readonly="">                                  
                        <textarea name="widgets_order" cols="40" rows="3" placeholder="Widgets order" class="form-control" id="widgets_order_json" value="" readonly="">
                            <?php echo _ch($listing['widgets_order']); ?>
                        </textarea>                                  
                    </div>
                <button class="btn" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php _l('Save Design');?></button>
                </form>
            </div>
        <script>
            $('.visual_widgets_save form').submit(function(e){
                e.preventDefault();
                var data = $('.visual_widgets_save form').serializeArray();
                // send info to agent
                $.post("{api_private_url}/visual_edit_save_template/{lang_code}/", data, 
                function(data){
                    if(data.success)
                    {
                        ShowStatus.show('<?php echo lang_check('Saved');?>');
                        $('.visual_widgets_save').slideUp();
                    }
                });
            })
            
            $(function(){
            
            
                /* control feature */
                $('.widget_edit_enabled,.widget_edit').each(function(){
                    var self = $(this);
                    var widgetname = self.find('.widget_controls_panel').attr('data-widgetfilename') || '';
                    
                    if(widgetname == '') return true;
                    
                    if(self.find('.widget_controls_panel').length) {
                        self.find('.widget_controls_panel').prepend( '<a href="#" class="btn btn-move btn-up" data-diff="-1" data-widgetfilename="'+widgetname+'"><i class="fa fa-arrow-up"></i></a>');
                        self.find('.widget_controls_panel').prepend( '<a href="#" class="btn btn-move btn-down" data-diff="+1"  data-widgetfilename="'+widgetname+'"><i class="fa fa-arrow-down"></i></a>');
                        self.find('.widget_controls_panel').append( '<a href="#" class="btn btn-remove" data-widgetfilename="'+widgetname+'"><i class="fa fa-trash"></i></a>');
                    } 
                    
                    /* remove */
                    self.find('.btn.btn-remove').on('click', function(e){
                        e.preventDefault();
                        
                        $('.result_preload_indic_fixed_full').hide();
                        var self = $(this);
                        var widget_filename = self.attr('data-widgetfilename') || '';
                        
                        var widget_type = widget_filename.substring(0,widget_filename.indexOf("_"));
                        
                        var $obj_widgets = $('.visual_widgets_save form #widgets_order_json').val();
                        if($obj_widgets =='') 
                            return true;
                        
                        $obj_widgets = $.parseJSON($obj_widgets)

                        if(typeof $obj_widgets[widget_type] == 'undefined') {
                            $obj_widgets[widget_type]=new Array;
                        }
                        
                        var i = $obj_widgets[widget_type].indexOf(widget_filename);
                        if( i >= 0) {
                            
                            ShowStatus.show('<?php echo lang_check('Widget removed');?>');
                            //delete $obj_widgets[widget_type][i]; 
                            $obj_widgets[widget_type].remove(i); 
                            
                            $obj_widgets = JSON.stringify($obj_widgets);
                            $('.visual_widgets_save form #widgets_order_json').val($obj_widgets);

                            self.closest('.widget_edit_enabled,.widget_edit').remove();
                            $('.visual_widgets_save').slideDown();
                        }

                    })
                    
                    /* moved */
                    self.find('.btn.btn-move').on('click', function(e){
                        e.preventDefault();
                        
                        $('.result_preload_indic_fixed_full').hide();
                        var self_btn = $(this);
                        var widget_filename = self_btn.attr('data-widgetfilename') || '';
                        var widget_diff= self_btn.attr('data-diff') || '';
                        
                        var widget_type = widget_filename.substring(0,widget_filename.indexOf("_"));
                        
                        var $obj_widgets = $('.visual_widgets_save form #widgets_order_json').val();
                        if($obj_widgets =='') 
                            return true;
                        
                        $obj_widgets = $.parseJSON($obj_widgets)

                        if(typeof $obj_widgets[widget_type] == 'undefined') {
                            $obj_widgets[widget_type]=new Array;
                        }
                        
                        var i = $obj_widgets[widget_type].indexOf(widget_filename);
                        if( i >= 0) {
                            
                            var elem = '';
                            
                            switch(widget_diff) {
                                case '+1' : 
                                            if(!self.next().length) break;
                                            if(self.next().length  && self.next().hasClass('add-widget-area')) break;
                                            $obj_widgets[widget_type] = array_move($obj_widgets[widget_type], i, i+1);
                                            elem = self.next();
                                            /* move element */
                                            $( self.detach() ).insertAfter(elem);
                                            break;
                                            
                                case '-1' : 
                                            if(!self.prev().length) break;
                                            $obj_widgets[widget_type] = array_move($obj_widgets[widget_type], i, i-1);
                                            elem = self.prev();
                                            /* move element */
                                            $( self.detach() ).insertBefore(elem);
                                            break;
                                }
                            
                            if(elem == '') return true;
                            
                            $obj_widgets = JSON.stringify($obj_widgets);
                            $('.visual_widgets_save form #widgets_order_json').val($obj_widgets);
                            $('.visual_widgets_save').slideDown();
                        }

                    })
                    
                    
                })
                /* remove feature */
            })
        </script>
        <?php endif;?>
</body>
</html>