<?php
/*
Widget-title: Header bar
Widget-preview-image: /assets/img/widgets_preview/header_bar.webp
 */
?>
<?php echo _widget('custom_palette');?>
<?php
    $lang_array = $this->language_m->get_array_by(array('is_frontend'=>1));
?> 
    
<?php
    $treefields = generate_treefields_list(64, 'level_0');   
?>

<div class="top-header widget_edit_enabled">
    <div class="container">
        <div class="row align-items-center">
            {is_logged_user}
            <div class="col-xl-12 col-12 d-none d-lg-block d-sm-none d-md-none">
                <div class="header-address">
                    <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
                    <a href="{myreservations_url}#content"><i class="fa fa-shopping-cart"></i><span> <?php _l('Myreservations');?></span></a>
                    <?php endif; ?>
                         <a href="{myproperties_url}#content"><i class="fa fa-list"></i> <span><?php _l('Myproperties');?></span></a>
                    <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                    <a href="{myresearch_url}#content"><i class="fa fa-filter"></i><span> <?php _l('Myresearch');?></span></a>  
                    <?php endif; ?>
                    <?php if(file_exists(APPPATH.'controllers/admin/favorites.php')):?>
                    <a href="{myfavorites_url}#content"><i class="fa fa-star"></i> <span><?php _l('Myfavorites');?></span></a>
                    <?php endif; ?>
                    <?php if(file_exists(APPPATH.'models/historyads_m.php')):?>
                    <a href="{myhistory_url}#content"><i class="fa fa-list"></i> <span><?php echo lang_check('My history');?></span></a>
                    <?php endif; ?>
                    <a href="<?php _che($mymessages_url); ?>#content"><i class="fa fa-envelope"></i> <span><?php _l('My messages'); ?></span></a>
                    <a href="{myprofile_url}#content"><i class="fa fa-user"></i> <span><?php _l('Myprofile');?></span></a>
                    <?php if(isset($page_edit_url)&&!empty($page_edit_url)):?>
                    <a href="{page_edit_url}"><i class="fa fa-edit"></i><span>  <?php echo _l('edit page');?></span></a>
                    <?php endif;?>
                </div>
            </div>
            {/is_logged_user}
            {is_logged_other}
            <div class="col-xl-12 col-12 d-none d-lg-block d-sm-none d-md-none">
                <div class="header-address">
                    <a href="{login_url}"><i class="fa fa-wrench"></i> <span><?php _l('Admininterface');?></span></a>
                    <?php if(isset($page_edit_url)&&!empty($page_edit_url)):?>
                    <a href="{page_edit_url}"><i class="fa fa-edit"></i><span> <?php echo _l('edit page');?></span></a>
                    <?php endif;?>
                    <?php if(isset($category_edit_url)&&!empty($category_edit_url)) :?>
                    <a href="{category_edit_url}"><i class="fa fa-edit"></i> <span><?php echo _l('edit category');?></span></a>
                    <?php endif;?>
                    <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                        <?php
                        $CI = &get_instance();
                        if($CI->uri->segment(1) == $listing_uri && false):?>
                            <a href="<?php echo site_url('admin/estate/options');?>"><i class="fa fa-edit"></i><span> <?php echo lang_check('edit fields');?></span></a>
                        <?php endif; ?>
                        <?php if($CI->uri->segment(1) != $listing_uri && isset($page_template) && substr($page_template, 0, 7) == 'custom_'): $template_id = substr($page_template, 7);?>
                            <a href="<?php echo site_url('admin/templates/edit/'.$template_id);?>"><i class="fa fa-edit"></i> <span><?php echo lang_check('Designer editing');?></span></a>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </div>
            {/is_logged_other}
            <div class="resp-grid flex-dynamic">
                <div class="header-address">
                    <?php if(config_db_item('country_dropdown_disable') == FALSE):?>
                        <?php if(sw_count($treefields) && sw_count($treefields)>1): ?>
                        <a href='#' class="country_selector" data-toggle="modal" data-target="#country-modal">
                            <?php
                            $d_country = lang_check('Global');
                            if(get_user_location_value())
                                $d_country = get_user_location_value();

                            $sw_location_img = '';
                            if(get_user_location_icon())
                                $sw_location_img = ' <img src="'.get_user_location_icon().'" />';
                            ?>
                            <?php if(get_user_location_value()):?>
                                <span><?php echo $sw_location_img;?> <?php echo $d_country;?></span>
                            <?php else:?>
                                <i class="fa fa-globe"></i><span><?php echo lang_check('Select country'); ?>: <?php echo $sw_location_img;?> <?php echo $d_country;?></span>
                            <?php endif;?>
                        </a>
                        <?php endif;?>
                    <?php endif;?>
                    <?php if(_ch($settings_phone, false)):?>
                    <?php 
                        $justNums = preg_replace("/[^0-9]/", '',  _ch($settings_phone,'#'));
                    ?>
                    <a class='' href="tel://<?php echo $justNums;?>">
                        <i class="la la-phone-square"></i>
                        <span> {settings_phone}</span>
                    </a>
                    <?php endif;?>
                    <a href="mailto:{settings_email}" class="hide_table">
                        <i class="la la-envelope-o"></i>
                        <span>{settings_email}</span>
                    </a>
                    <?php if(false && config_db_item('property_subm_disabled')==FALSE):  ?>
                    <a href="{front_login_url}#content" class="login_popup_enabled"><i class="la la-user"></i> <span>{lang_Login}</span></a>
                    <?php endif;?>
                </div>
            </div>
            <?php if(sw_count($lang_array) > 1):?> 
            <div class="resp-grid flex-dynamic">
            <?php else:?>
            <div class="resp-grid flex-dynamic">
            <?php endif;?>
                <div class="header-social d-none d-sm-none d-md-block">
                    <a href="#">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="#">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="#">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="#">
                        <i class="fa fa-linkedin"></i>
                    </a>
                </div>
            </div>
            <div class="resp-grid flex">
            <?php if(sw_count($lang_array) > 1):?> 
                <div class="d-none d-sm-none d-md-block">
                    <?php _widget('custom_langmenu');?>
                </div>
            <?php endif;?>
                <?php if(config_db_item('currency_dropdown_disable') == FALSE):?>
                <?php 
                    $CI = &get_instance();
                    $CI->load->model('conversions_m');
                    $conversions = $CI->conversions_m->get()
                ?>
                <?php if(sw_count($conversions) > 1):?>
                    <div class="currency-selector">
                        <div class="drop-menu links">
                            <?php
                            foreach($conversions as $key=>$currency)
                            {
                                $currency_class = '';
                                if(isset($set_currency) && !empty($set_currency && $set_currency == $currency->currency_code))
                                {
                                    $currency_class = 'current';
                                }
                                else if( (isset($options_prefix_36) || isset($options_suffix_36))  && !isset($set_currency)  )
                                {
                                    if($options_prefix_36 == $currency->currency_code || ($options_prefix_36 == $currency->currency_symbol && !empty($currency->currency_symbol)) ||
                                        $options_suffix_36 == $currency->currency_code || ($options_suffix_36 == $currency->currency_symbol && !empty($currency->currency_symbol)))
                                    {
                                        $currency_class = 'current';
                                    }
                                }
                                if(!empty($currency_class)){
                                    echo '<div class="select">';
                                    echo ' <span>';
                                    echo $currency->currency_code.' '.$currency->currency_symbol;
                                    echo ' </span>';
                                    echo ' <i class="la la-caret-down"></i>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        <ul class="dropeddown">
                        <?php 
                            foreach($conversions as $key=>$currency)
                            {
                                $currency_class = '';

                                if(isset($set_currency) && !empty($set_currency && $set_currency == $currency->currency_code))
                                {
                                    $currency_class = 'current';
                                }
                                else if( (isset($options_prefix_36) || isset($options_suffix_36))  && !isset($set_currency)  )
                                {
                                    if($options_prefix_36 == $currency->currency_code || ($options_prefix_36 == $currency->currency_symbol && !empty($currency->currency_symbol)) ||
                                        $options_suffix_36 == $currency->currency_code || ($options_suffix_36 == $currency->currency_symbol && !empty($currency->currency_symbol)))
                                    {
                                        $currency_class = 'current';
                                    }
                                }

                                echo '<li class="'.$currency_class.'">';
                                echo '<a href="?set_currency='.$currency->currency_code.'" class="dropdown-item">'.$currency->currency_code.' '.$currency->currency_symbol.'</a>';
                                echo '</li>';
                            }
                        ?>
                        </ul>
                        </div>
                    </div>
                <?php endif;?>
            <?php endif;?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-country-list" id="country-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo lang_check('Select country');?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-maps row">
                    <li class="col-md-3 col-sm-4">
                        <a href="<?php echo current_url().'?set_country=empty'; ?>">
                            <i class="fa fa-globe"></i>
                            <span><?php echo lang_check('All countries'); ?></span><i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i>
                        </a>
                    </li>
                    <?php if(sw_count($treefields))foreach ($treefields as $key=>$item): ?>
                    <li class="col-md-3 col-sm-4">
                        <a href="<?php echo current_url().'?set_country='.$item['id']; ?>">
                            <?php
                                if(_ch($item['code'], false) && file_exists(FCPATH.'templates/'.$settings_template.'/assets/img/flags/'.$item['code'].'.png'))
                                    echo '<img src="assets/img/flags/'.$item['code'].'.png" alt="'.$item['code'].'"/>';
                            ?>
                            <span><?php _che($item['title']); ?></span><i class="fa fa-spinner fa-spin fa-ajax-indicator hidden"></i>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    