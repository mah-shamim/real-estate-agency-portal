
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
          <span class="divider">/</span> ?
          <a class="bread" href="<?php echo site_url('admin/settings')?>"><?php echo lang('Settings')?></a>
          <span class="divider">/</span> 
          <a class="bread-current" href="<?php echo site_url('admin/settings/language')?>"><?php echo lang('Language')?></a>
		</div>

		<div class="clearfix"></div>

    </div>
    <!-- Page heading ends -->

    <!-- Matter -->

    <div class="matter-settings">
    
    <div style="margin-bottom: 8px;" class="tabbable">
      <ul class="nav nav-tabs settings-tabs">
        <li><a href="<?php echo site_url('admin/settings/contact')?>"><?php echo lang('Company contact')?></a></li>
        <li class="active"><a href="<?php echo site_url('admin/settings/language')?>"><?php echo lang('Languages')?></a></li>
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

            <div class="col-md-12">


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Language')?></div>
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
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Language from lang file')?></label>
                                  <div class="col-lg-2">
                                    <?php echo form_input('language', set_value('language', $language->language), 
                                                          'class="form-control" id="inputLanguage" placeholder="'.lang('Language').'"')?>
                                  </div>
                                  <div class="col-lg-8">
                                    <?php echo $available_langs; ?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Code')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('code', set_value('code', $language->code), 
                                                          'style="max-width:50px;display:inline-block;margin-right:5px;" class="form-control" id="inputCode" placeholder="'.lang('Code').'"')?>
                                    <?php echo anchor('http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2', lang_check('What is ISO code?'), 'target="_blank"');?>
                                  </div>
                                </div>
                    
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?php echo lang_check('Facebook lang code')?></label>
                                  <div class="col-lg-1">
                                    <?php echo form_input('facebook_lang_code', set_value('facebook_lang_code', $language->facebook_lang_code), 
                                                          'class="form-control" id="inputCode" placeholder="'.lang_check('en_EN').'"')?>
                                  </div>
                                </div>
                                
                                <?php if(config_db_item('multi_domains_enabled') === TRUE): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Custom domain')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('domain', set_value('domain', $language->domain), 
                                                          'class="form-control" id="input_domain" placeholder="'.lang_check('Custom domain').'"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Default PayPal currency code')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('currency_default', 
                                                             $currencies, set_value('currency_default', $language->currency_default), 'class="form-control"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Default')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_default', '1', set_value('is_default', $language->is_default), 'id="inputDefault"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Visible in menu')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_frontend', '1', set_value('is_frontend', $language->is_frontend), 'id="inputFrontend"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label">
                                      <?php echo lang_check('Is RTL')?>
                                        <div class="tooltip_tree">
                                            <span class="hintlabel"><i class="icon-question-sign hint" aria-hidden="true"></i></span>
                                            <span class="tooltiptext">
                                                <?php echo lang_check("Check this only for Arabic and similar RTL languages");?>
                                            </span>
                                        </div>
                                  </label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_rtl', '1', set_value('is_rtl', $language->is_rtl), 'id="inputRtl"')?>
                                    
                                    <?php 
                                        if(config_db_item('is_rtl_supported') !== TRUE)
                                        {
                                            echo '<span class="badge badge-warning">';
                                            _l('Not supported by selected template');
                                            echo '</span>';
                                        }
                                    
                                    ?>
                                  </div>
                                </div>
                                
                                <hr />

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/settings/language')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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
          </div>
    </div>
    </div>

	<!-- Matter ends -->

   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>
   <style>
    .tooltip_tree {
        position: relative;
        display: inline-block;
    }

    .tooltip_tree .tooltiptext {
        visibility: hidden;
        width: 185px;
        background-color: #0f163c;
        color: #fff;
        text-align: left;
        border-radius: 6px !important;
        padding: 8px 10px;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        margin-bottom: 5px;
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
    }
    
    .tooltip_tree .hintlabel i {
        color: #0f163c;
        font-size: 15px;
    }

    .tooltip_tree:hover .tooltiptext {
        visibility: visible;
    }

    .tooltip_tree:hover .tooltiptext .br {
        margin-bottom: 5px;
    }
    
    .tooltip_tree .hintlabel {
        color: white;
        display: inline-block;
        border-radius: 50% !important;
        font-size: 11px;
        width: 15px;
        height: 15px;
        text-align: center;
        margin-left: 5px;
        cursor: pointer;
    }

</style>