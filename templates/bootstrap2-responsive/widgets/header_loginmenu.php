<div class="top-wrapper">
      <div class="container">
        <div class="masthead">
        {not_logged}
        <ul class="nav pull-left top-small">
          <li><span><i class="icon-phone"></i> {settings_phone}</span></li>
          <li class="hidden-xs"><a href="mailto:{settings_email}"><i class="icon-envelope"></i> {settings_email}</a></li>
          <?php if(config_db_item('property_subm_disabled')==FALSE):  ?>
          <li><a href="{front_login_url}#content"><i class="icon-user"></i> {lang_Login}</a></li>
          <?php endif;?>
        </ul>
        {/not_logged}
        {is_logged_user}
        <ul class="nav pull-left top-small">
        <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
          <li><a href="{myreservations_url}#content"><i class="icon-shopping-cart"></i> {lang_Myreservations}</a></li>
        <?php endif; ?>
          <li><a href="{myproperties_url}#content"><i class="icon-list"></i> {lang_Myproperties}</a></li>
        <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
          <li><a href="{myresearch_url}#content"><i class="icon-filter"></i> {lang_Myresearch}</a></li>  
        <?php endif; ?>
        <?php if(file_exists(APPPATH.'controllers/admin/favorites.php')):?>
          <li><a href="{myfavorites_url}#content"><i class="icon-star"></i> {lang_Myfavorites}</a></li>
        <?php endif; ?>
        <?php if(file_exists(APPPATH.'models/historyads_m.php')):?>
          <li><a href="{myhistory_url}#content"><i class="icon-list-alt"></i> <?php echo lang_check('My history');?></a></li>
        <?php endif; ?>
        <?php if(file_exists(APPPATH.'controllers/admin/visits.php')):?>
          <li><a href="<?php echo site_url('frontend/myvisits');?>"><i class="icon-star"></i> <?php _l('My visits'); ?></a></li>
        <?php endif; ?>
          <li><a href="<?php _che($mymessages_url); ?>#content"><i class="icon-envelope"></i> <?php _l('My messages'); ?></a></li>
          <li><a href="{myprofile_url}#content"><i class="icon-user"></i> {lang_Myprofile}</a></li>
          <li><a href="{logout_url}"  onclick="return alert('<?php echo lang_check('If you are on a shared computer, remember to log out of your Open ID provided (Facebook, Google)');?>')"><i class="icon-off"></i> {lang_Logout}</a></li>
          <?php if(isset($page_edit_url)&&!empty($page_edit_url)):?>
            <li><a href="{page_edit_url}"><i class="icon-edit"></i>  <?php echo _l('edit page');?></a></li>
          <?php endif;?>
              <?php _widget('custom_messenger_item');?>
        </ul>
        {/is_logged_user}
        {is_logged_other}
        <ul class="nav pull-left top-small">
          <li><a href="{login_url}"><i class="icon-wrench"></i> {lang_Admininterface}</a></li>
          <li><a href="{logout_url}"><i class="icon-off"></i> {lang_Logout}</a></li>
          <?php if(isset($page_edit_url)&&!empty($page_edit_url)):?>
            <li><a href="{page_edit_url}"><i class="icon-edit"></i> <?php echo _l('edit page');?></a></li>
          <?php endif;?>
          <?php if(isset($category_edit_url)&&!empty($category_edit_url)) :?>
            <li><a href="{category_edit_url}"><i class="icon-edit"></i> <?php echo _l('edit category');?></a></li>
          <?php endif;?>
            <?php _widget('custom_messenger_item');?>
        </ul>
        {/is_logged_other}
        <?php echo print_breadcrump(null, ' > ', 'class="breadcrumb nav pull-right"');?>
        </div>
      </div> <!-- /.container -->
</div>