<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('User')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($user->id) ? lang('Add a new user') : lang('Edit user').' "' . $user->name_surname.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/user')?>"><?php echo lang('Users')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php if(!empty($user->id) && file_exists(APPPATH.'controllers/admin/swin_reviews.php') && check_acl('reviews')): ?>
                    <?php echo anchor('admin/reviews/index/0/'.$user->id, '<i class="icon-star"></i>&nbsp;&nbsp;'.lang('Reviews'), 'class="btn btn-primary pull-right" style="margin-left:5px;"')?>
                <?php endif; ?>
            
                <?php echo anchor('admin/user/all_user_listings/'.$user->id, '<i class="icon-remove"></i>&nbsp;&nbsp;'.lang_check('Remove all listings'), 'class="btn btn-danger pull-right" style="margin-left:5px;"')?>
                <?php if(check_acl('user/all_deactivate') && !empty($user->id)):?>
                <?php echo anchor('admin/user/all_deactivate/'.$user->id, '<i class="icon-remove"></i>&nbsp;&nbsp;'.lang_check('Deactivate all estates'), 'class="btn btn-danger pull-right" style="margin-left:5px;"')?>
                <?php echo anchor('admin/user/all_activate/'.$user->id, '<i class="icon-ok"></i>&nbsp;&nbsp;'.lang_check('Activate all estates'), 'class="btn btn-success pull-right" style="margin-left:5px;"')?>
                <?php endif;?>                
            </div>
        </div>
        
          <div class="row">

            <div class="col-md-12">
              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('User data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>     
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Name and surname')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('name_surname', set_value('name_surname', $user->name_surname), 'class="form-control" id="inputNameSurname" placeholder="'.lang('Name and surname').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Username')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('username', set_value('username', $user->username), 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
                                  </div>
                                </div>
                    
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Password')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_password('password', set_value('password', ''), 'class="form-control" id="inputPassword" placeholder="'.lang('Password').'" '.$password_attr.' autocomplete="new-password"')?>
                                  </div>
                                </div>
                                <?php if($user->is_password_locked == 1 && $this->session->userdata('type') == 'ADMIN'):?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"></label>
                                  <div class="col-lg-10">
                                      <b style="color:#fa3031"><?php echo lang_check('User use fb/google account for login, if change password fb/google login stop work'); ?></b>
                                  </div>
                                </div>
                                <?php endif;?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('PasswordConfirm')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_password('password_confirm', set_value('password_confirm', ''), 'class="form-control" id="inputPasswordConfirm" placeholder="'.lang('PasswordConfirm').'" '.$password_attr.' autocomplete="new-password"')?>
                                  </div>
                                </div>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Type')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('type', $this->user_m->user_types, set_value('type', $user->type), 'class="form-control"');?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN' && file_exists(APPPATH.'controllers/admin/expert.php')): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Expert category')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('qa_id', $expert_categories, set_value('qa_id', $user->qa_id), 'class="form-control"');?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN' && file_exists(APPPATH.'controllers/admin/packages.php')): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Package')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('package_id', $packages, set_value('package_id', $user->package_id), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Package expire date')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker1">
                                    <?php echo form_input('package_last_payment', $this->input->post('package_last_payment') ? $this->input->post('package_last_payment') : $user->package_last_payment, 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($this->session->userdata('type') != 'AGENT_LIMITED'): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Address')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('address', set_value('address', $user->address), 'placeholder="'.lang('Address').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>       
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Description')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('description', set_value('description', $user->description), 'placeholder="'.lang('Description').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>     
                    
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Phone')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('phone', set_value('phone', $user->phone), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                  </div>
                                </div>
                    
                                <?php if(config_db_item('phone_mobile_enabled') === TRUE): ?>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?php echo lang_check('Mobile phone')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('phone2', set_value('phone2', $user->phone2), 'class="form-control" id="inputPhone2" placeholder="'.lang('Mobile phone').'"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Mail')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('mail', set_value('mail', $user->mail), 'class="form-control" id="inputMail" placeholder="'.lang('Mail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Language')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('language', $this->language_m->backend_languages, set_value('language', $user->language), 'class="form-control"');?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Activated')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('activated', '1', set_value('activated', $user->activated), 'id="inputActivated"')?>
                                  </div>
                                </div>
                                <?php endif; ?>

                                <?php if($this->session->userdata('type') != 'AGENT_LIMITED'): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Facebook ID')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('facebook_id', set_value('facebook_id', $user->facebook_id), 'class="form-control" id="inputMail" placeholder="'.lang_check('Facebook ID').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Embed video code')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('embed_video_code', set_value('embed_video_code', $user->embed_video_code), 'class="form-control" id="input_embed_video_code" placeholder="'.lang_check('Embed video code').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Payment details')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('payment_details', set_value('payment_details', $user->payment_details), 'class="form-control" id="input_payment_details" placeholder="'.lang_check('Payment details').'"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('facebook_link')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('facebook_link', set_value('facebook_link', $user->facebook_link), 'class="form-control" id="input_facebook_link" placeholder="'.lang_check('facebook_link').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('youtube_link')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('youtube_link', set_value('youtube_link', $user->youtube_link), 'class="form-control" id="input_youtube_link" placeholder="'.lang_check('youtube_link').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('gplus_link')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('gplus_link', set_value('gplus_link', $user->gplus_link), 'class="form-control" id="input_gplus_link" placeholder="'.lang_check('gplus_link').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('twitter_link')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('twitter_link', set_value('twitter_link', $user->twitter_link), 'class="form-control" id="input_twitter_link" placeholder="'.lang_check('twitter_link').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('linkedin_link')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('linkedin_link', set_value('linkedin_link', $user->linkedin_link), 'class="form-control" id="input_linkedin_link" placeholder="'.lang_check('linkedin_link').'"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($this->session->userdata('type') == 'ADMIN' && config_db_item('phone_verification_enabled') === TRUE && file_exists(APPPATH.'libraries/Clickatellapi.php')): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Phone verified')?></label>
                                  <div class="col-lg-10">
                                  <?php echo form_checkbox('phone_verified', '1', set_value('phone_verified', $user->phone_verified), 'id="inputPhoneVerified"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Mail verified')?></label>
                                  <div class="col-lg-10">
                                  <?php echo form_checkbox('mail_verified', '1', set_value('mail_verified', $user->mail_verified), 'id="inputMailVerified"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if( config_db_item('agency_agent_enabled') === TRUE && $this->session->userdata('type') == 'ADMIN' ): ?>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Agency related')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown_ajax('agency_id', 'user_m', set_value('agency_id', $user->agency_id), 'username', NULL, TRUE);?>
                                  </div>
                                </div>
                                
                                <?php endif; ?>
                                
                                <?php if(config_db_item('enable_county_affiliate_roles') === TRUE && $this->session->userdata('type') == 'ADMIN' && FALSE): ?>
                                <div class="form-group search-form">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('County')?></label>
                                  <div class="col-lg-10">
                                  
                <!-- [START] TreeSearch -->
                <?php if(config_item('tree_field_enabled') === TRUE):?>
                <?php
                
                    $CI =& get_instance();
                    $CI->load->model('treefield_m');
                    $field_id = 64;
                    $lang_id = $content_language_id;
                    $drop_options = $CI->treefield_m->get_level_values($lang_id, $field_id);
                    $drop_selected = array();
                    echo '<div class="tree TREE-GENERATOR tree-'.$field_id.'">';
                    echo '<div class="field-tree">';
                    echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_0', $drop_options, $drop_selected, 'class="form-control selectpicker tree-input" id="sinputOption_'.$lang_id.'_'.$field_id.'_level_0'.'"');
                    echo '</div>';
                    
                    $levels_num = $CI->treefield_m->get_max_level($field_id);
                    
                    if($levels_num>0)
                    for($ti=1;$ti<=$levels_num;$ti++)
                    {
                        $lang_empty = lang_check('treefield_'.$field_id.'_'.$ti);
                        if(empty($lang_empty))
                            $lang_empty = lang_check('Please select parent');
                        
                        echo '<div class="field-tree">';
                        echo form_dropdown('option'.$field_id.'_'.$lang_id.'_level_'.$ti, array(''=>$lang_empty), array(), 'class="form-control selectpicker tree-input" id="sinputOption_'.$lang_id.'_'.$field_id.'_level_'.$ti.'"');
                        echo '</div>';
                    }
                    echo '</div>';
                
                ?>
                
                <script language="javascript">
                
                $(function() {
                    var load_val = '<?php echo set_value('county_affiliate_values', $user->county_affiliate_values); ?>';
                    var s_values_splited = (load_val+" ").split(" - "); 
//            $.each(s_values_splited, function( index, value ) {
//                alert( index + ": " + value );
//            });
                    if(s_values_splited[0] != '')
                    {
                        var first_select = $('.tree-64').find('select:first');
                        first_select.find('option').filter(function () { return $(this).html() == s_values_splited[0]; }).attr('selected', 'selected');
                    
                        load_by_field(first_select, true, s_values_splited);
                    }
                });
                
                </script>
                <?php endif; ?>
                <!-- [END] TreeSearch -->
                                    <?php echo form_input('county_affiliate_values', set_value('county_affiliate_values', $user->county_affiliate_values), 'class="form-control hidden" id="input_county_affiliate_values" placeholder="'.lang_check('County').'"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if((config_db_item('clickatell_api_id') != '' || config_db_item('clickatell_api_key') != '') && file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('SMS notifications enabled')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('research_sms_notifications', '1', set_value('research_sms_notifications', $user->research_sms_notifications), 'id="input_research_sms_notifications"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(file_exists(APPPATH.'controllers/admin/savesearch.php')): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Enable Email alerts')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('research_mail_notifications', '1', set_value('research_mail_notifications', $user->research_mail_notifications), 'id="input_alerts_email"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
<!-- [Custom fields] -->                       
<?php
custom_fields_print('custom_fields_code');
?>          
<!-- [/Custom fields] -->

                                    <hr />
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/user')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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
            
        <div class="col-md-12">

              <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Images')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                  <span class="label label-info"><?php _l('Profile gallery instructions');?></span>
                  </div>  
                  <div class="padd">

<?php if(!isset($user->id)):?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else:?>
<div id="page-files-<?php echo $user->id?>" rel="user_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo site_url('files/upload_user/'.$user->id);?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/user/edit/'.$user->id);?>"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <div class="span7 col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo lang('add_files...')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span><?php echo lang('cancel_upload')?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo lang('delete_selected')?></span>
                </button>
                <input type="checkbox" class="toggle" />
            </div>
            <!-- The global progress information -->
            <div class="span5 col-md-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br />
        <!-- The table listing the files available for upload/download -->
        <!--<table role="presentation" class="table table-striped">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">-->

          <div role="presentation" class="fieldset-content">
            <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">      
<?php if(isset($files[$user->repository_id]))foreach($files[$user->repository_id] as $file ):?>
            <li class="img-rounded template-download fade in">
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo $file->filename?>" data-src="<?php echo $file->thumbnail_url?>" src="<?php echo $file->thumbnail_url?>">
                </div>
                <div class="filename">
                    <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>                  
                    <?php else:?>
                    <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="icon-trash icon-white"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
<?php endforeach;?>
            </ul>
            <br style="clear:both;"/>
          </div>
    </form>

</div>
<?php endif;?>

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
          
<?php if(config_item('tree_field_enabled') === TRUE && config_db_item('enable_county_affiliate_roles') === TRUE):?>
<script language="javascript">
    
    /* [START] TreeField */

    $(function() {
        $(".search-form .TREE-GENERATOR select").change(function(){
            var s_value = $(this).val();
            var s_name_splited = $(this).attr('name').split("_"); 
            var s_level = parseInt(s_name_splited[3]);
            var s_lang_id = s_name_splited[1];
            var s_field_id = s_name_splited[0].substr(6);
            // console.log(s_value); console.log(s_level); console.log(s_field_id);
            
            load_by_field($(this));
            
            // Reset child selection and value generator
            var generated_val = '';
            $(this).parent().parent()
            .find('select').each(function(index){
                // console.log($(this).attr('name'));
                if(index > s_level)
                {
                    //$(this).html('<option value=""><?php echo lang_check('No values found'); ?></option>');
                    
                    $(this).find("option:gt(0)").remove();
                    $(this).val('');
                }
                else
                {
                    if($(this).find("option:selected").index() > 0)
                    generated_val+=$(this).find("option:selected").text()+" - ";
                }
            });
            //console.log(generated_val);
            $("#input_county_affiliate_values").val(generated_val);

        });

    });
    
    function load_by_field(field_element, autoselect_next, s_values_splited)
    {
        if (typeof autoselect_next === 'undefined') autoselect_next = false;
        if (typeof s_values_splited === 'undefined') s_values_splited = [];

        var s_value = field_element.val();
        var s_name_splited = field_element.attr('name').split("_"); 
        var s_level = parseInt(s_name_splited[3]);
        var s_lang_id = s_name_splited[1];
        var s_field_id = s_name_splited[0].substr(6);
        // console.log(s_value); console.log(s_level); console.log(s_field_id);
        
        // Load values for next select
        var ajax_indicator = field_element.parent().parent().parent().find('.ajax_loading');
        var select_element = $("select[name=option"+s_field_id+"_"+s_lang_id+"_level_"+parseInt(s_level+1)+"]");
        if(select_element.length > 0 && s_value != '')
        {
            ajax_indicator.css('display', 'block');
            $.getJSON( "<?php echo site_url('privateapi/get_level_values_select'); ?>/"+s_lang_id+"/"+s_field_id+"/"+s_value+"/"+parseInt(s_level+1), function( data ) {
                //console.log(data.generate_select);
                //console.log("select[name=option"+s_field_id+"_"+s_lang_id+"_level_"+parseInt(s_level+1)+"]");
                ajax_indicator.css('display', 'none');
                
                select_element.html(data.generate_select);

                if(autoselect_next)
                {
                    if(s_values_splited[s_level+1] != '')
                    {
                        select_element.find('option').filter(function () { return $(this).html() == s_values_splited[s_level+1]; }).attr('selected', 'selected');
                        //$('.search-form select.selectpicker').selectpicker('render');
                        load_by_field(select_element, true, s_values_splited);
                        
                        
                    }
                }
            });
        }
    }
    
    /* [END] TreeField */

</script>
<?php endif; ?>