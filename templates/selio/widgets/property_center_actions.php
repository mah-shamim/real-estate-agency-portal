<div class="listing_preview_actions">
    <div class="fav">
        <?php if(file_exists(APPPATH.'controllers/admin/favorites.php')):?>
        <?php
            $favorite_added = false;
            if(sw_count($not_logged) == 0)
            {
                $CI =& get_instance();
                $CI->load->model('favorites_m');
                $favorite_added = $CI->favorites_m->check_if_exists($this->session->userdata('id'), 
                                                                    $estate_data_id);
                if($favorite_added>0)$favorite_added = true;
            }
        ?>
        <a href="#" id="add_to_favorites" class="btn2" style="<?php echo ($favorite_added)?'display:none;':''; ?>"><i class="la la-star-o"></i><?php echo lang_check('Add to favorites'); ?></a>  
        <a href="#" id="remove_from_favorites" class="btn2"  style="<?php echo (!$favorite_added)?'display:none;':''; ?>"><i class="la la-star"></i><?php echo lang_check('Remove from favorites'); ?></a>  
        <?php endif; ?>
    </div>
    <div class="rep">
        <?php _widget('custom_property_report');?>
    </div>
</div>