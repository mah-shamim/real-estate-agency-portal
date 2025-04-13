<?php
/*
Widget-title: Search form
Widget-preview-image: /assets/img/widgets_preview/top_search2.webp 
*/


global $selio_button_search_defined;
$selio_button_search_defined=false;

if(!function_exists('sw_filter_search_slidetoggle')) {
    function sw_filter_search_slidetoggle ($class_add = NULL){
        static $sw_visible_filters = 0;
        global $button_search_defined;
        global $sw_filter_search_slidetoggle_enable;
        global $sw_filter_search_slidetoggle_slim;

        if(!isset($sw_filter_search_slidetoggle_enable)) return false;
        
        $sw_visible_filters++;

        if($sw_visible_filters == 5){
            $button_search_defined=true;

        ?>
            <?php if(isset($sw_filter_search_slidetoggle_slim)):?>
            <div class="form_field srch-btn <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')):?> form_field_save <?php endif;?>">
                <a href="#" class="btn btn-outline-primary sw-search-start slim">
                    <i class="la la-search"></i>
                    <i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i>
                </a>
                <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                    <button type="button" id="search-save"  class="btn btn-custom btn-savesearch btn-custom-secondary btn-icon"><i class="fa fa-save icon-white fa-ajax-hide"></i><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></button>
                <?php endif;?>
            </div>
            <?php else:?>
            <div class="form_field srch-btn <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')):?> form_field_save <?php endif;?>">
                <a href="#" class="btn btn-outline-primary sw-search-start">
                    <i class="la la-search"></i>
                    <span><?php echo lang_check('Search');?></span>
                    <i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i>
                </a>
                <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                    <button type="button" id="search-save"  class="btn btn-custom btn-savesearch btn-custom-secondary btn-icon"><i class="fa fa-save icon-white fa-ajax-hide"></i><span><?php echo lang_check('Save'); ?></span><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></button>
                <?php endif;?>
            </div>
            <?php endif;?>
            <a class="search-additional-btn" id="search-additional">
                <i class="fa fa-angle-double-down"></i>
            </a>
            <div class="clearfix row-flex" id='form-addittional' style="display: none;">
        <?php
        }
    }
}
global $sw_filter_search_slidetoggle_enable;
$sw_filter_search_slidetoggle_enable = true;
global $sw_filter_search_slidetoggle_slim;
$sw_filter_search_slidetoggle_slim = true;
?>
<section class="form_sec widget_edit_enabled">
    <h3 class="vis-hid">Invisible</h3>
    <div class="container">
        <form action="#" class="row banner-search search-form top-search banner-search_init">
            {is_logged_other}
            <?php if($this->session->userdata('type') == 'ADMIN'): ?>
            <div class="widget-controls-panel widget_controls_panel" data-widgetfilename="right_filterform">
                <a href="<?php echo site_url('admin/forms/edit/7');?>" target="_blunk" class="btn btn-edit"><i class="ion-edit"></i></a>
            </div>
            <?php endif;?>
            {/is_logged_other}
            <div class="banner-search_box banner-search row">
            <?php  _search_form_primary(7); ?>
                <?php if(!$selio_button_search_defined): ?>
                <?php global $button_search_defined; if(!$button_search_defined): ?>
                    <div class="form_field srch-btn">
                        <a href="#" class="btn btn-outline-primary sw-search-start sw-search-start-slim">
                            <i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i>
                            <i class="la la-search"></i>
                        </a>
                    </div>
                <?php else: ?>
                </div>
                <?php endif;?>
                <?php endif;?>
            </div>

        </form>
    </div>
</section><!--form_sec end-->