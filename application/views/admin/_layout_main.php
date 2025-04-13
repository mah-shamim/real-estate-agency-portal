<?php $this->load->view('admin/components/page_head_main')?>
<body>
<div class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
  
    <div class="containerk">
      <!-- Menu button for smallar screens -->
		<div class="navbar-header">
		  <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a href="<?php echo site_url('admin/dashboard')?>" class="navbar-brand">
      <?php if(_ch($website_admin_logo_url, false)):?>
        <img style="max-height: 30px;" src="<?php echo _che($website_admin_logo_url, '')?>">
      <?php else:?>
        <?php if(file_exists(FCPATH.'/templates/'.$settings['template'].'/assets/img/logo-system-mini.png')):?>
            <img src="<?php echo base_url('templates/'.$settings['template'].'/assets/img/logo-system-mini.png')?>">
        <?php else:?>
            <img src="<?php echo base_url('admin-assets/img/custom/logo-system-mini.png');?>" />
        <?php endif;?>
        Real estate <span class="bold">point</span>
      <?php endif;?>
      
      </a>
		</div>
      <!-- Site name for smallar screens -->

      <!-- Navigation starts -->
      <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">     

        <!-- Links -->
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">            
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              <?php if($this->session->userdata('profile_image') != ''):?><img src="<?php echo base_url($this->session->userdata('profile_image'));?>" alt="" class="nav-user-pic img-responsive" /> <?php endif;?><?php echo $this->session->userdata('name_surname')?> <b class="caret"></b>              
            </a>
            
            <!-- Dropdown menu -->
            <ul class="dropdown-menu">
              <li><a href="<?php echo site_url('admin/user/edit/'.$this->session->userdata('id'))?>"><i class="icon-user"></i> <?php echo lang_check('Profile');?></a></li>
              <?php if(check_acl('settings')):?><li><a href="<?php echo site_url('admin/settings')?>"><i class="icon-cogs"></i> <?php echo lang_check('Settings');?></a></li><?php endif;?>
              <?php if(config_db_item('frontend_disabled') === FALSE): ?>
              <li><a target="_blank" href="<?php echo site_url(''); ?>"><i class="icon-globe"></i> <?php echo lang_check('Website link');?></a></li>
              <?php endif; ?>
              <li><a href="<?php echo site_url('admin/user/logout')?>"><i class="icon-off"></i> <?php echo lang_check('Logout');?></a></li>
            </ul>
          </li>
          
        </ul>

        <!-- Notifications -->
        <ul class="nav navbar-nav navbar-right">
            
            <?php if(check_acl('enquire') && config_db_item('frontend_disabled') === FALSE):?>
            <!-- Message button with number of latest messages count-->
            <li class="dropdown dropdown-big">
              <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="icon-envelope-alt"></i> <?php echo lang_check('Enquires');?> <span class="badge badge-important"><?php echo $this->enquire_m->total_unreaded();?></span> 
              </a>

                <ul class="dropdown-menu">
                  <li>
                    <!-- Heading - h5 -->
                    <h5><i class="icon-envelope-alt"></i> <?php echo lang_check('Enquires');?></h5>
                    <!-- Use hr tag to add border -->
                    <hr />
                  </li>
                  <?php if(!empty($enquire_3)):?>
                    <?php foreach($enquire_3 as $enquire):?>
                  <li>
                    <!-- List item heading h6 -->
                    <a href="<?php echo site_url('admin/enquire/edit/'.$enquire->id)?>"><?php echo $enquire->name_surname?></a>
                    <!-- List item para -->
                    <p><?php echo word_limiter(strip_tags($enquire->message), 9);?></p>
                    <hr />
                  </li>
                    <?php endforeach;?>    
                  <?php else:?>
                      <li>
                        <p class="label label-info validation"><?php echo lang_check('Messages not found');?></p>
                        <hr />
                      </li>
                  <?php endif;?>
                  <li>
                    <div class="drop-foot">
                      <a href="<?php echo site_url('admin/enquire')?>"><?php echo lang_check('View All');?></a>
                    </div>
                  </li>                                    
                </ul>
            </li>
            <?php endif;?>
            
            <?php if(check_acl('user')):?>
            <!-- Members button with number of latest members count -->
            <li class="dropdown dropdown-big">
              <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="icon-user"></i> <?php echo lang_check('Users');?> <span   class="badge badge-warning"><?php echo $this->user_m->total_unactivated();?></span> 
              </a>

                <ul class="dropdown-menu">
                  <li>
                    <!-- Heading - h5 -->
                    <h5><i class="icon-user"></i> <?php echo lang_check('Users');?></h5>
                    <!-- Use hr tag to add border -->
                    <hr />
                  </li>
                    <?php foreach($users_3 as $user):?>
                  <li>
                    <!-- List item heading h6-->
                    <a href="<?php echo site_url('admin/user/edit/'.$user->id)?>"><?php echo $user->name_surname?></a> 
                    <span class="label label-<?php echo isset($this->user_m->user_type_color[$user->type])?$this->user_m->user_type_color[$user->type]:'';?> pull-right"><?php echo isset($this->user_m->user_types[$user->type])?$this->user_m->user_types[$user->type]:'';?></span>
                    <div class="clearfix"></div>
                    <hr />
                  </li>
                    <?php endforeach;?>               
                  <li>
                    <div class="drop-foot">
                      <a href="<?php echo site_url('admin/user')?>"><?php echo lang_check('View All');?></a>
                    </div>
                  </li>                                    
                </ul>
            </li>
            <?php endif;?>

        </ul>
		</nav>
      </div>

    </div>
  



<!-- Main content starts -->

<div class="content">

  	<!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">

          <!-- Search form -->
          <div class="sidebar-widget">
             <?php echo form_open('admin/dashboard/search');?>
              	<input type="text" class="form-control" name="search" placeholder="<?php echo lang_check('Search')?>" />
            <?php echo form_close();?>
          </div>

          <!--- Sidebar navigation -->
          <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
          <ul class="navi">

            <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->

            <li class="nred<?php echo (strpos($this->uri->uri_string(),'dashboard')!==FALSE || $this->uri->uri_string() == 'admin')?' current':'';?>"><a href="<?php echo site_url('admin/dashboard')?>"><i class="icon-desktop"></i> <?php echo lang_check('Dashboard');?></a></li>
            
            <?php if(check_acl('page') && config_db_item('frontend_disabled') === FALSE):?>
            <li class="ngreen<?php echo (strpos($this->uri->uri_string(),'page')!==FALSE)?' current':'';?>"><a href="<?php echo site_url('admin/page')?>"><i class="icon-sitemap"></i> <?php echo lang_check('Pages & menu');?></a></li>
            <?php endif;?>
            
            <!-- Menu with sub menu -->
            <li class="has_submenu nlightblue<?php echo (strpos($this->uri->uri_string(),'estate')!==FALSE || strpos($this->uri->uri_string(),'reports')==TRUE || strpos($this->uri->uri_string(),'treefield')==TRUE)?' current open':'';?>">
              <a href="#">
                <!-- Menu name with icon -->
                <i class="icon-map-marker"></i> <?php echo lang_check('Real estates');?> 
                <!-- Icon for dropdown -->
                <span class="pull-right"><i class="icon-angle-right"></i></span>
              </a>

              <ul>
                <li><a href="<?php echo site_url('admin/estate')?>"><?php echo lang_check('Manage');?></a></li>
                <?php if(check_acl('estate/options')):?>
                <li><a href="<?php echo site_url('admin/estate/options')?>"><?php echo lang_check('Options');?></a></li>
                <li><a href="<?php echo site_url('admin/estate/dependent_fields')?>"><?php echo lang_check('Dependent fields');?></a></li>
                <?php endif;?>
                <?php if(check_acl('estate/forms') && config_item('search_forms_editor_enabled') == TRUE):?>
                <li><a href="<?php echo site_url('admin/estate/forms')?>"><?php echo lang_check('Search forms');?></a></li>
                <?php endif;?>
                
                <?php
                    if(file_exists(APPPATH.'controllers/admin/treefield.php') && $this->session->userdata('type') == 'ADMIN')
                    {
                        $CI =& get_instance();
                        $CI->load->model('option_m');
                        $option_treefield = $CI->option_m->get_lang(64);
                        if(!empty($option_treefield) && $option_treefield->type == 'TREE')
                        {
                echo '<li><a href="'.site_url('admin/treefield/edit/64').'">'.$option_treefield->{'option_'.$content_language_id}.'</a></li>';        
                        }
                        
                        $option_treefield = $CI->option_m->get_lang(79);
                        if(!empty($option_treefield) && $option_treefield->type == 'TREE')
                        {
                echo '<li><a href="'.site_url('admin/treefield/edit/79').'">'.$option_treefield->{'option_'.$content_language_id}.'</a></li>';        
                        }
                    }
                
                ?>
                <?php if(config_item('admin_beginner_enabled') === TRUE):?>
                <?php
                    if($this->session->userdata('type') == 'ADMIN')
                    {
                        $CI =& get_instance();
                        $CI->load->model('option_m');
                        $option_treefield = $CI->option_m->get(2);
                        if(!empty($option_treefield) && $option_treefield->type == 'DROPDOWN')
                        {
                echo '<li><a href="'.site_url('admin/estate/edit_option/2').'">'.lang_check('Type values').'</a></li>';        
                        }
                    }
                
                ?>
                
                <?php
                    if($this->session->userdata('type') == 'ADMIN')
                    {
                        $CI =& get_instance();
                        $CI->load->model('option_m');
                        $option_treefield = $CI->option_m->get(4);
                        if(!empty($option_treefield) && $option_treefield->type == 'DROPDOWN')
                        {
                echo '<li><a href="'.site_url('admin/estate/edit_option/4').'">'.lang_check('Purpose values').'</a></li>';        
                        }
                    }
                
                ?>
                <?php endif;?>
                <?php if(config_item('report_property_enabled') == TRUE && $this->session->userdata('type') == 'ADMIN'):?>
                <li><a href="<?php echo site_url('admin/reports')?>"><?php echo lang_check('Reported');?></a></li>
                <?php endif;?>
                <?php if(config_item('status_enabled') === TRUE && 
                         $this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE'):?>
                <li><a href="<?php echo site_url('admin/estate/contracted')?>"><?php echo lang_check('Contracted');?></a></li>
                <li><a href="<?php echo site_url('admin/estate/statuses')?>"><?php echo lang_check('Statuses');?></a></li>
                <?php endif;?>
                <?php if(config_item('removed_reports_enabled') === TRUE && 
                         ( $this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE' ||
                           $this->session->userdata('type') == 'ADMIN' )
                         ):?>
                <li><a href="<?php echo site_url('admin/estate/removed')?>"><?php echo lang_check('Removed');?></a></li>
                <?php endif;?>
                <?php if(!empty($this->data['settings']['word_filtering'])):?>
                <li><a href="<?php echo site_url('admin/estate/word_filtering')?>"><?php echo lang_check('Word filtering');?></a></li>
                <?php endif;?>
              </ul>
            </li>
            
            <?php if(config_item('admin_beginner_enabled') === TRUE):?>
                <?php if(check_acl('user')):?>
                <li class="norange<?php echo (strpos($this->uri->uri_string(),'admin/user')!==FALSE && strpos($this->uri->uri_string(),'user/edit/'.$this->session->userdata('id'))===FALSE)?' current':'';?>"><a href="<?php echo site_url('admin/user')?>"><i class="icon-list-alt"></i> <?php echo lang_check('Agents & Users');?></a></li>
                <?php endif;?>
                
                
                <?php if(check_acl('enquire') && config_db_item('frontend_disabled') === FALSE):?>
                    <li class="has_submenu nred<?php echo (strpos($this->uri->uri_string(),'enquire')!==FALSE)?' current open':'';?>">
                      <a href="#">
                        <!-- Menu name with icon -->
                        <i class="icon-envelope-alt"></i> <?php echo lang_check('Enquires');?> 
                        <!-- Icon for dropdown -->
                        <span class="pull-right"><i class="icon-angle-right"></i></span>
                      </a>
                    
                      <ul>
                        <li class="<?php echo (strpos($this->uri->uri_string(),'enquire')!==FALSE)?' current':'';?>"><a href="<?php echo site_url('admin/enquire')?>"><?php echo lang_check('Enquires');?></a></li>
                        <?php if(config_item('private_messages_enabled') !== FALSE):?>
                        <li><a href="<?php echo site_url('admin/enquire/messenger')?>"><?php echo lang_check('Messages');?></a></li>
                        <?php endif;?>
                      </ul>
                    </li>
                <?php endif;?>
                
                <li class="nblue<?php echo (strpos($this->uri->uri_string(),'user/edit/'.$this->session->userdata('id'))!==FALSE)?' current':'';?>"><a href="<?php echo site_url('admin/user/edit/'.$this->session->userdata('id'))?>"><i class="icon-user"></i> <?php echo lang_check('Profile');?></a></li>
                
                <?php if(check_acl('settings')):?>
                    <li class="has_submenu nred<?php echo (strpos($this->uri->uri_string(),'settings')!==FALSE)?' current open':'';?>">
                      <a href="#">
                        <!-- Menu name with icon -->
                        <i class="icon-cogs"></i> <?php echo lang_check('Settings');?> 
                        <!-- Icon for dropdown -->
                        <span class="pull-right"><i class="icon-angle-right"></i></span>
                      </a>
                    
                      <ul>
                        <li><a href="<?php echo site_url('admin/settings')?>"><?php echo lang_check('Company details');?></a></li>
                        <li><a href="<?php echo site_url('admin/settings/language')?>"><?php echo lang_check('Languages');?></a></li>
                        <li><a href="<?php echo site_url('admin/settings/template')?>"><?php echo lang_check('Template');?></a></li>
                        <li><a href="<?php echo site_url('admin/settings/system')?>"><?php echo lang_check('System');?></a></li>
                        <?php if(config_db_item('slug_enabled') === TRUE): ?>
                            <li><a href="<?php echo site_url('admin/settings/slug')?>"><?php echo lang_check('SEO slugs')?></a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo site_url('admin/settings/addons')?>"><?php echo lang_check('Addons');?></a></li>
                        <?php if(config_db_item('currency_conversions_enabled') === TRUE): ?>
                        <li><a href="<?php echo site_url('admin/settings/currency_conversions')?>"><?php echo lang_check('Currency Conversions');?></a></li>
                        <?php endif; ?>
                      </ul>
                    </li>
                <?php endif;?>
                

            <?php endif;?>

            <?php if(file_exists(APPPATH.'controllers/admin/claim.php') && check_acl('claim')):?>
                <li class="has_submenu nred<?php echo (strpos($this->uri->uri_string(),'claim')!==FALSE)?' current open':'';?>">
                  <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-file-text-alt"></i> <?php echo lang_check('Claim');?> 
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                  </a>

                  <ul>
                    <li><a href="<?php echo site_url('admin/claim')?>"><?php echo lang_check('List');?></a></li>
                    <li><a href="<?php echo site_url('admin/claim/edit')?>"><?php echo lang_check('Add Claim');?></a></li>
                  </ul>
                </li>
            <?php endif;?>

            <?php if(file_exists(APPPATH.'controllers/admin/promocode.php') && check_acl('promocode')):?>
                <li class="has_submenu nred<?php echo (strpos($this->uri->uri_string(),'promocode')!==FALSE)?' current open':'';?>">
                  <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-file-text-alt"></i> <?php echo lang_check('Promocode');?> 
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                  </a>

                  <ul>
                    <li><a href="<?php echo site_url('admin/promocode')?>"><?php echo lang_check('List');?></a></li>
                    <li><a href="<?php echo site_url('admin/promocode/edit')?>"><?php echo lang_check('Add Promocode');?></a></li>
                  </ul>
                </li>
            <?php endif;?>
                
            <?php if(file_exists(APPPATH.'controllers/admin/visits.php') && check_acl('visits')):?>
                <li class="has_submenu nred<?php echo (strpos($this->uri->uri_string(),'visits')!==FALSE)?' current open':'';?>">
                  <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-map-marker"></i> <?php echo lang_check('Visits');?> 
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                  </a>

                  <ul>
                    <li><a href="<?php echo site_url('admin/visits')?>"><?php echo lang_check('Inbox');?></a></li>
                    <li><a href="<?php echo site_url('admin/visits/outbox')?>"><?php echo lang_check('Outbox');?></a></li>
                  </ul>
                </li>
            <?php endif;?>

            <?php if(check_acl('slideshow') && config_db_item('frontend_disabled') === FALSE):?>
            <li class="ngreen<?php echo (strpos($this->uri->uri_string(),'slideshow')!==FALSE)?' current':'';?>"><a href="<?php echo site_url('admin/slideshow')?>"><i class="icon-picture"></i> <?php echo lang_check('Slideshow')?></a></li>
            <li class="nlightblue<?php echo (strpos($this->uri->uri_string(),'statistics')!==FALSE)?' current':'';?>"><a target="_blank" href="https://www.google.com/analytics/web"><i class="icon-bar-chart"></i> <?php echo lang_check('Statistics');?></a></li>
            <?php endif;?>
            
            <?php if(check_acl('backup')):?>
            <li class="norange<?php echo (strpos($this->uri->uri_string(),'backup')!==FALSE)?' current':'';?>"><a href="<?php echo site_url('admin/backup')?>"><i class="icon-hdd"></i> <?php echo lang_check('Backup')?></a></li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/news.php') && check_acl('news')):?>
            <li class="has_submenu nblue<?php echo (strpos($this->uri->uri_string(),'news')!==FALSE)?' current open':'';?>">
                <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-book"></i> <?php echo lang_check('News');?>
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                </a>
              <ul>
                <li><a href="<?php echo site_url('admin/news')?>"><?php echo lang_check('Manage');?></a></li>
                <li><a href="<?php echo site_url('admin/news/categories')?>"><?php echo lang_check('Categories');?></a></li>
              </ul>
            </li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/ads.php') && check_acl('ads')):?>
            <li class="nred<?php echo (strpos($this->uri->uri_string(),'ads')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/ads')?>">
                    <!-- Menu name with icon -->
                    <i class="icon-globe"></i> <?php echo lang_check('Ads');?>
                </a>
            </li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/showroom.php') && check_acl('showroom')):?>
            <li class="has_submenu ngreen<?php echo (strpos($this->uri->uri_string(),'showroom')!==FALSE)?' current open':'';?>">
                <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-briefcase"></i> <?php echo lang_check('Showroom');?>
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                </a>
              <ul>
                <li><a href="<?php echo site_url('admin/showroom')?>"><?php echo lang_check('Manage');?></a></li>
                <li><a href="<?php echo site_url('admin/showroom/categories')?>"><?php echo lang_check('Categories');?></a></li>
              </ul>
            </li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/expert.php') && check_acl('expert')):?>
            <li class="has_submenu nlightblue<?php echo (strpos($this->uri->uri_string(),'expert')!==FALSE)?' current open':'';?>">
                <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-comment"></i> <?php echo lang_check('Q&A');?>
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                </a>
              <ul>
                <li><a href="<?php echo site_url('admin/expert')?>"><?php echo lang_check('Manage');?></a></li>
                <li><a href="<?php echo site_url('admin/expert/categories')?>"><?php echo lang_check('Categories');?></a></li>
              </ul>
            </li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/booking.php') && check_acl('booking')):?>
            <li class="has_submenu norange<?php echo (strpos($this->uri->uri_string(),'booking')!==FALSE)?' current open':'';?>">
                <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-shopping-cart"></i> <?php echo lang_check('Booking');?>
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                </a>
              <ul>
                <li><a href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Reservations');?></a></li>
                <li><a href="<?php echo site_url('admin/booking/rates')?>"><?php echo lang_check('Rates');?></a></li>
                <li><a href="<?php echo site_url('admin/booking/payments')?>"><?php echo lang_check('Payments');?></a></li>
                <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                <li><a href="<?php echo site_url('admin/booking/withdrawals')?>"><?php echo lang_check('Withdrawals');?></a></li>
                <?php endif; ?>
              </ul>
            </li>
            <?php endif;?>
            
            <?php if(config_item('enable_table_calendar') === TRUE && check_acl('tcalendar')): ?>
            <li class="has_submenu norange<?php echo (strpos($this->uri->uri_string(),'tcalendar')!==FALSE)?' current open':'';?>">
                <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-calendar"></i> <?php echo lang_check('TCalendar');?>
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                </a>
              <ul>
                <li><a href="<?php echo site_url('admin/tcalendar/available')?>"><?php echo lang_check('Available');?></a></li>
              </ul>
            </li>
            <?php endif; ?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/packages.php') && check_acl('packages')):?>
            <li class="has_submenu nviolet<?php echo (strpos($this->uri->uri_string(),'packages')!==FALSE)?' current open':'';?>">
                <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-gift"></i> <?php echo lang_check('Packages');?>
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                </a>
              <ul>
                <li><a href="<?php echo site_url('admin/packages')?>"><?php echo lang_check('Manage');?></a></li>
                <li><a href="<?php echo site_url('admin/packages/users')?>"><?php echo lang_check('Users');?></a></li>
                <?php if(config_db_item('enable_county_affiliate_roles') === TRUE): ?>
                <li><a href="<?php echo site_url('admin/packages/affilatepackage')?>"><?php echo lang_check('Affilate package');?></a></li>
                <?php endif; ?>
                <li><a href="<?php echo site_url('admin/packages/payments')?>"><?php echo lang_check('Payments');?></a></li>
              </ul>
            </li>
            <?php elseif(file_exists(APPPATH.'controllers/admin/packages.php') && check_acl('packages/mypackage')): ?>
            <li class="nviolet<?php echo (strpos($this->uri->uri_string(),'packages')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/packages/mypackage')?>">
                    <!-- Menu name with icon -->
                    <i class="icon-gift"></i> <?php echo lang_check('My package');?>
                </a>
            </li>
            <?php elseif(config_db_item('enable_county_affiliate_roles') === TRUE && 
                     $this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE' && check_acl('packages/affilatepackage')): ?>
            <li class="nviolet<?php echo (strpos($this->uri->uri_string(),'packages')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/packages/affilatepackage'); ?>">
                    <!-- Menu name with icon -->
                    <i class="icon-gift"></i> <?php echo lang_check('Affilate package');?>
                </a>
            </li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/swin_reviews.php') && check_acl('reviews')): ?>
            <li class="nblue<?php echo (strpos($this->uri->uri_string(),'reviews')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/reviews')?>">
                    <!-- Menu name with icon -->
                    <i class="icon-tags"></i> <?php echo lang_check('Reviews');?>
                </a>
            </li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/favorites.php') && check_acl('favorites')): ?>
            <li class="nblue<?php echo (strpos($this->uri->uri_string(),'favorites')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/favorites')?>">
                    <!-- Menu name with icon -->
                    <i class="icon-star"></i> <?php echo lang_check('Favorites');?>
                </a>
            </li>
            <?php endif;?>
            
            <?php if(check_acl('monetize') && config_db_item('frontend_disabled') === FALSE):?>
            <li class="has_submenu nred<?php echo (strpos($this->uri->uri_string(),'monetize')!==FALSE)?' current open':'';?>">
                <a href="#">
                    <!-- Menu name with icon -->
                    <i class="icon-usd"></i> <?php echo lang_check('Payments');?>
                    <!-- Icon for dropdown -->
                    <span class="pull-right"><i class="icon-angle-right"></i></span>
                </a>
              <ul>
                <li><a href="<?php echo site_url('admin/monetize/payments')?>"><?php echo lang_check('Activations');?></a></li>
                <li><a href="<?php echo site_url('admin/monetize/payments_featured')?>"><?php echo lang_check('Featured');?></a></li>
                <?php if(file_exists(APPPATH.'controllers/paymentconsole.php')): ?>
                <li><a href="<?php echo site_url('admin/monetize/invoices')?>"><?php echo lang_check('Invoices');?></a></li>
                <?php endif; ?>
              </ul>
            </li>
            <?php endif;?>
            
            <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php') && check_acl('savesearch')): ?>
            <li class="ngreen<?php echo (strpos($this->uri->uri_string(),'savesearch')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/savesearch')?>">
                    <!-- Menu name with icon -->
                    <i class="icon-filter"></i> <?php echo lang_check('Research');?>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if(config_item('map_report_enabled') === TRUE && check_acl('mapreport')): ?>
            <li class="nviolet<?php echo (strpos($this->uri->uri_string(),'mapreport')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/mapreport')?>">
                    <!-- Menu name with icon -->
                    <i class="icon-bar-chart"></i> <?php echo lang_check('Map report');?>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if(config_item('enable_benchmark_tools') === TRUE && check_acl('benchmarktool')): ?>
            <li class="nblue<?php echo (strpos($this->uri->uri_string(),'benchmarktool')!==FALSE)?' current open':'';?>">
                <a href="<?php echo site_url('admin/benchmarktool')?>">
                    <!-- Menu name with icon -->
                    <i class="icon-fire"></i> <?php echo lang_check('Benchmark tools');?>
                </a>
            </li>
            <?php endif; ?>

          </ul>
  
          <?php if(false):?>
          <!-- Date -->
          <div class="sidebar-widget">
            <div id="todaydate"></div>
          </div>
          <?php endif;?>

        </div>

    </div>

    <!-- Sidebar ends -->

  	<!-- Main bar -->
  	<div class="mainbar">
        <?php if(config_item('results_page_id_enabled')!=FALSE && config_db_item('results_page_id')==FALSE):?>
        <div class="col-md-12">
            <br/>
            <div class="alert alert-danger alert-dismissible fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo lang_check('Results page not defined, please set results page in settings');?> <a href="<?php echo site_url('admin/settings/system');?>"> <?php echo lang_check('settings->system settings');?>.</a>
            </div>
        </div>
        <?php endif;?>
            
    <?php $this->load->view($subview)?>
    </div>
</div>
<!-- Content ends -->

<?php $this->load->view('admin/components/page_tail_main')?>