
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
          <a class="bread-current" href="<?php echo site_url('admin/settings/languages')?>"><?php echo lang('Languages')?></a>
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
                <?php echo '<a href="?translate=mymemory" class="btn btn-primary"><i class="icon-share"></i>&nbsp;&nbsp;'.lang_check('Translate with MyMemory API').'</a>'; ?>
                <?php echo '<a href="?translate=google" class="btn btn-primary"><i class="icon-share"></i>&nbsp;&nbsp;'.lang_check('Translate with Google API').'</a>'; ?>
            </div>
        </div>
        
          <div class="row">

            <div class="col-md-12">


              <div class="widget wlightblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Language').': '.$lang_name.', '.lang('File').': '.$file?> </div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo $message?>   
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>   
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>            
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>     
                                <?php foreach($language_translations_english as $key=>$value):?>
                                    <div class="form-group <?php echo (!empty($value) && empty($language_translations_current[$key]))?'has-error':''; ?>" id="<?php echo url_title_cro('label_'.$value);?>">
                                      <label class="col-lg-4 control-label control-label-right"><?php echo $value; ?></label>
                                      <div class="col-lg-7">
                                        <?php 
                                        
                                        $translated_value = '';
                                        
                                        if(!empty($language_translations_current[$key]) && $language_translations_current[$key] != $value)
                                        {
                                            $translated_value = $language_translations_current[$key];
                                        }
                                        elseif(!empty($value) && isset($this->mymemorytranslation) ||
                                               $language_translations_current[$key] == $value && isset($this->mymemorytranslation) && !empty($value))
                                        {
                                            if(isset($_GET['translate']) && $_GET['translate'] == 'mymemory')
                                            {
                                                $translated_value = $this->mymemorytranslation->translate($value, 'en', $lang_code);
                                            }
                                            else if(isset($_GET['translate']) && $_GET['translate'] == 'google')
                                            {
                                                $translated_value = $this->gtranslation->translate($value, 'en', $lang_code);
                                            }
                                            else if(!empty($language_translations_current[$key]))
                                            {
                                                $translated_value = $language_translations_current[$key];
                                            }                                    
                                        }
                                        else
                                        {
                                            
                                        }
                                        
                                        echo form_input(md5($key), set_value(md5($key), $translated_value), 'class="form-control" id="inputAddress" placeholder="'.$value.'"')?>
                                      </div>
                                      <div class="col-lg-1">
                                      <a target="_blank" href="https://translate.google.com/#en/<?php echo $lang_code.'/'.$value; ?>"><i class="icon-share"></i></a>
                                      </div>
                                    </div>
                                <?php endforeach;?>
                                <hr />

                                <div class="form-group">
                                  <div class="col-lg-offset-4 col-lg-8">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/settings/language_files/'.$lang_id)?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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
   
   <script>
   
   // *only* if we have anchor on the url
    if(typeof location_hash !== 'undefined' && location_hash != '') {
        if($(location_hash).length)
            $(location_hash).find('input').css("cssText","border: 2px solid #f88529 !important");
    }
   
   </script>