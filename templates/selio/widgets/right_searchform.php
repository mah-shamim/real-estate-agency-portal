<?php
/*
Widget-title: Search form
Widget-preview-image: /assets/img/widgets_preview/right_searchform.webp
 */
?>
<div class="widget widget-property-search side widget_edit_enabled">
    {is_logged_other}
    <?php if($this->session->userdata('type') == 'ADMIN'): ?>
    <div class="widget-controls-panel widget_controls_panel" data-widgetfilename="right_filterform">
        <a href="<?php echo site_url('admin/forms/edit/5');?>" target="_blunk" class="btn btn-edit"><i class="ion-edit"></i></a>
    </div>
    <?php endif;?>
    {/is_logged_other}
    <h3 class="widget-title"><?php echo lang_check('Property Search');?></h3>
    <div id="tags-filters"></div>
    <form action="#" class="row banner-search sw_search_form search-form">
        <input id="rectangle_ne" type="text" class="hidden" />
        <input id="rectangle_sw" type="text" class="hidden" />
        <?php _search_form_primary(5) ;?> 
        <div class="sw_search_form-secondary">
            <h4><?php echo lang_check('More Features');?></h4>
            <?php  _search_form_secondary(5); ?>
        </div>
        <div class="form_field  <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')):?> form_field_save <?php endif;?>">
            <button class="btn btn-outline-primary sw-search-start" type="submit">
                <span><?php echo lang_check('Search');?><i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i></span>
            </button>
            <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                <button type="button" id="search-save" class="btn btn-custom btn-savesearch btn-custom-secondary btn-icon"><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i><span>{lang_SaveResearch}</span></button>
            <?php endif; ?>
        </div>
    </form>
</div><!--widget-property-search end-->