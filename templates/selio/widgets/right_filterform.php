<?php
/*
Widget-title: Filter form
Widget-preview-image: /assets/img/widgets_preview/right_filterform.webp
 */
?>


<div class="widget widget-property-search side widget_edit_enabled">
    <?php if(isset($is_logged_other) && !empty($is_logged_other)):?>
    <div class="widget-controls-panel widget_controls_panel" data-widgetfilename="top_searchvisual">
        <a href="<?php echo site_url('admin/forms/edit/7');?>" target="_blunk" class="btn btn-edit"><i class="ion-edit"></i></a>
    </div>
    <?php endif;?>
    <h3 class="widget-title"><?php echo lang_check('Filter');?></h3>
    <form action="#" class="row banner-search sw_search_form search-form">
        <input id="rectangle_ne" type="text" class="hidden" />
        <input id="rectangle_sw" type="text" class="hidden" />
        <?php _search_form_secondary(7) ;?> 
        <div class="form_field">
            <button class="btn btn-outline-primary sw-search-start" type="submit">
                <span><?php echo lang_check('Filter');?><i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i></span>
            </button>
        </div>
    </form>
</div><!--widget-property-search end-->