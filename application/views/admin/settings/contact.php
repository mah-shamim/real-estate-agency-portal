
    <!-- Page heading -->
    <div class="page-head">
    <!-- Page heading -->
        <h2 class="pull-left"><?php echo lang('Settings')?>
		  <!-- page meta -->
		  <span class="page-meta"><?php echo lang('System settings')?></span>
		</h2>


		<!-- Breadcrumb -->
		<div class="bread-crumb pull-right">
          <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
          <!-- Divider -->
          <span class="divider">/</span> 
          <a class="bread" href="<?php echo site_url('admin/settings')?>"><?php echo lang('Settings')?></a>
          <span class="divider">/</span> 
          <a class="bread-current" href="<?php echo site_url('admin/settings/contact')?>"><?php echo lang('Company contact')?></a>
		</div>

		<div class="clearfix"></div>

    </div>
    <!-- Page heading ends -->



    <!-- Matter -->

    <div class="matter-settings">
    
    <div style="margin-bottom: 8px;" class="tabbable">
      <ul class="nav nav-tabs settings-tabs">
        <li class="active"><a href="<?php echo site_url('admin/settings/contact')?>"><?php echo lang('Company contact')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/language')?>"><?php echo lang('Languages')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Template')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/system')?>"><?php echo lang('System settings')?></a></li>
        <li><a href="<?php echo site_url('admin/settings/addons')?>"><?php echo lang_check('Addons')?></a></li>
        <?php if(config_db_item('slug_enabled') === TRUE): ?>
        <li><a href="<?php echo site_url('admin/settings/slug')?>"><?php echo lang_check('SEO slugs')?></a></li>
        <?php endif; ?>
        <?php if(config_db_item('currency_conversions_enabled') === TRUE): ?>
        <li><a href="<?php echo site_url('admin/settings/currency_conversions')?>"><?php echo lang_check('Currency Conversions')?></a></li>
        <?php endif; ?>
      </ul>
    </div>
    
    <div class="container">
          <div class="row">

            <div class="col-md-8">


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Company data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?> 
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>    
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('WebsiteTitle')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('websitetitle', set_value('websitetitle', isset($settings['websitetitle'])?$settings['websitetitle']:''), 'class="form-control" id="inputWebTitle" placeholder="'.lang('WebsiteTitle').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Address')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('address', set_value('address', isset($settings['address'])?$settings['address']:''), 'class="form-control" id="inputAddress" placeholder="'.lang('Address').'"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Gps')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('gps', set_value('gps', isset($settings['gps'])?$settings['gps']:''), 'class="form-control" id="inputGps" placeholder="'.lang('Gps').'"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang_check('Use as map center')?></label>
                                  <div class="col-lg-9">
                                      <?php echo form_checkbox('map_center', '1', set_value('map_center', isset($settings['map_center'])?$settings['map_center']:'0'), 'id="inputContactMapCenter"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('ContactMail')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('email', set_value('email', isset($settings['email'])?$settings['email']:''), 'class="form-control" id="inputContactMail" placeholder="'.lang('ContactMail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang_check('Email alerts on new not actiated property')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_checkbox('email_alert', '1', set_value('email_alert', isset($settings['email_alert'])?$settings['email_alert']:'1'), 'id="inputContactMailAlert"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Phone')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('phone', set_value('phone', isset($settings['phone'])?$settings['phone']:''), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Fax')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_input('fax', set_value('fax', isset($settings['fax'])?$settings['fax']:''), 'class="form-control" id="inputPhone" placeholder="'.lang('Fax').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-3 control-label"><?php echo lang('Address Footer')?></label>
                                  <div class="col-lg-9">
                                    <?php echo form_textarea('address_footer', set_value('address_footer', isset($settings['address_footer'])?$settings['address_footer']:''), 'placeholder="'.lang('Address Footer').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>
                                
                                <hr />

                                <div class="form-group">
                                  <div class="col-lg-offset-3 col-lg-9">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/settings')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>
            
            
            <div class="col-md-4">
              <div class="widget wblue">
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Location')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="gmap" id="mapsAddress">

                  </div>
                </div>


              </div> 
            </div>
          </div>
          <?php if(config_db_item('disable_faq_for_agent') == TRUE && $this->session->userdata('type') != 'ADMIN'): ?>
          <div class="row">

            <div class="col-md-12">


              <div class="widget worange">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Frequently asked questions')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content" style="padding: 10px;">
                        <div class="list-group">
                          <a href="http://iwinter.com.hr/support/?p=168" target="_blank" class="list-group-item"><?php echo lang_check('How to change logo real estate point?')?></a>
                        </div>
                  </div>

              </div>  

            </div>
          </div>
          <?php endif; ?>
    </div>
    </div>

	<!-- Matter ends -->

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>