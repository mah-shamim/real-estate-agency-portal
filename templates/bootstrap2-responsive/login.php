<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <script>
    $(document).ready(function(){

    });    
    </script>
  </head>

  <body>
  
{template_header}

<?php if(config_item('enable_restricted_mode') === TRUE && sw_count($not_logged) > 0): ?>
<?php else: ?>

<?php endif; ?>

<div class="wrap-content" id="content">
    <div class="container">

        <?php if(file_exists(APPPATH.'controllers/admin/packages.php') && config_item('def_package') !== FALSE): ?>
        <div class="row-fluid">
            <div class="span12">
            <h2>{lang_AvailablePackages}</h2>
            <div class="property_content">
                <div class="widget-content">
                    <?php if($this->session->flashdata('error_package')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error_package')?></p>
                    <?php endif;?>
                    <table class="table table-striped" style="margin-bottom: 0px;">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang_check('Package name');?></th>
                            <th><?php echo lang_check('Price');?></th>
                            <th><?php echo lang_check('Free property activation');?></th>
                            <th><?php echo lang_check('Days limit');?></th>
                            <th><?php echo lang_check('Listings limit');?></th>
                            <th><?php echo lang_check('Free featured limit');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if(sw_count($packages)): foreach($packages as $package):
                        ?>
                                    <tr>
                                    	<td><?php echo $package->id; ?></td>
                                        <td>
                                        <?php echo $package->package_name; ?>
                                        <?php echo $package->show_private_listings==1?'&nbsp;<i class="icon-eye-open"></i>':'&nbsp;<i class="icon-eye-close"></i>'; ?>
                                        </td>
                                        <td><?php echo $package->package_price.' '.$package->currency_code; ?></td>
                                        <td><?php echo $package->auto_activation?'<i class="icon-ok"></i>':'<i class="icon-remove"></i>'; ?></td>
                                        <td><?php echo $package->package_days; ?></td>
                                        <td><?php echo $package->num_listing_limit?></td>
                                        <td><?php echo $package->num_featured_limit?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang_check('Not available');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>

                  </div>
            </div>
            </div>
        </div>
        
        <?php endif; ?>
        
        <?php if(isset($settings_activation_price) && isset($settings_featured_price) &&
                 $settings_activation_price > 0 || $settings_featured_price > 0): ?>
        <div class="row-fluid">
            <div class="span12">
            <div class="property_content">
                <div class="widget-content">
                <?php if($settings_activation_price > 0): ?>
                    <?php echo lang_check('* Property activation price:').' '.$settings_activation_price.' '.$settings_default_currency; ?><br />
                 <?php endif;?>
                 <?php if($settings_featured_price > 0): ?>
                    <?php echo lang_check('* Property featured price:').' '.$settings_featured_price.' '.$settings_default_currency; ?>
                 <?php endif;?>
                 </div>
            </div>
            </div>
        </div>
        <?php endif;?>
        
        <div class="row-fluid">
            <div class="span6 login-form">
            <h2>{lang_Login}</h2>
            <div class="property_content">
                <?php if($is_login):?>
                <?php echo validation_errors()?>
                <?php if($this->session->flashdata('error')):?>
                <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                <?php endif;?>
                <?php flashdata_message();?>
                <?php endif;?>
                
                  <!-- Login form -->
                  <?php echo form_open(NULL, array('class' => 'form-horizontal'))?>
                    <!-- Email -->
                    <div class="control-group">
                      <label class="control-label" for="inputUsername"><?php echo lang('Username')?></label>
                      <div class="controls">
                        <?php echo form_input('username', $this->input->get('username'), 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
                      </div>
                    </div>
                    <!-- Password -->
                    <div class="control-group">
                      <label class="control-label" for="inputPassword"><?php echo lang('Password')?></label>
                      <div class="controls">
                        <?php echo form_password('password', $this->input->get('password'), 'class="form-control" id="inputPassword" placeholder="'.lang('Password').'"')?>
                      </div>
                    </div>
                    <!-- Remember me checkbox and sign in button -->
                    <div class="control-group">
					<div class="controls">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="remember" id="remember" value="true" /> <?php echo lang('Remember me')?>
                        </label>
						</div>
					</div>
					</div>
                    <div class="control-group">
					   <div class="controls">
							<button type="submit" class="btn btn-danger"><?php echo lang('Sign in')?></button>
							<button type="reset" class="btn btn-default"><?php echo lang('Reset')?></button>
                            <a href="<?php echo site_url('admin/user/forgetpassword'); ?>"><em><?php echo lang_check('Forget password?')?></em></a>
						</div>
                    </div>
                  <?php echo form_close()?>
				  
                <?php if(config_item('app_type') == 'demo'):?>
                <p class="alert alert-info"><?php echo lang_check('User creditionals: user, user')?></p>
                <?php endif;?>
                
                <?php if(config_item('appId') != '' && !empty($login_url_facebook)): ?>
                <a href="<?php echo $login_url_facebook; ?>" style="text-align:center;display:block;"><img src="assets/img/login-facebook.png"  alt=""/></a>
                <?php endif;?>
                
                <?php if(config_item('glogin_enabled')): ?>
                <a href="<?php echo site_url('api/google_login/'.$lang_id); ?>" style="text-align:center;display:block;"><img src="assets/img/login-google.png"  alt=""/></a>
                <?php endif;?>
                <?php if(file_exists(APPPATH.'libraries/Twlogin.php')): ?>
                    <?php 
                        $CI = &get_instance();
                        $CI->load->library('twlogin');
                    ?>
                    <?php if($CI->twlogin->__get('consumerKey') && $CI->twlogin->__get('consumerSecret')): ?>
                        <a href="<?php echo site_url('api/twitter_login/'.$lang_id); ?>" title style="text-align:center;display:block;"><img src="assets/img/twitter_signin.png" alt="twitter_login" /></a>
                    <?php endif;?>
                <?php endif;?>
                
            </div></div>
            <div class="span6 register-form">
            <h2>{lang_Register}</h2>
            <a id="content"></a>
            <div class="property_content">
                <?php if($this->session->flashdata('error_registration') != ''):?>
                <p class="alert alert-success"><?php echo $this->session->flashdata('error_registration')?></p>
                <?php endif;?>
                <?php if($is_registration):?>
                <?php echo validation_errors()?>
                <?php endif;?>
                  <!-- Login form -->
                  <?php echo form_open(NULL, array('class' => 'form-horizontal'))?>
                    <?php if(config_db_item('dropdown_register_enabled') === TRUE): ?>
                    <div class="control-group">
                      <label class="control-label"><?php echo lang_check('Account type')?></label>
                      <div class="controls">
                        <?php 
                        $values = array('USER' => lang_check('USER'), 'AGENT' => lang_check('AGENT'));
                        
                        echo form_dropdown('type', $values, set_value('type', ''), 'class="form-control" id="input_type"')?>
                      </div>
                    </div>
                    <?php endif; ?>

                  
                  <?php if(config_db_item('register_reduced') == FALSE): ?>
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang('FirstLast')?></label>
                                  <div class="controls">
                                    <?php echo form_input('name_surname', set_value('name_surname', ''), 'class="form-control" id="inputNameSurname" placeholder="'.lang('FirstLast').'"')?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang('Username')?></label>
                                  <div class="controls">
                                    <?php echo form_input('username', set_value('username', ''), 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
                                  </div>
                                </div>
                  <?php endif; ?>
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang('Email')?></label>
                                  <div class="controls">
                                    <?php echo form_input('mail', set_value('mail', ''), 'class="form-control" id="inputMail" placeholder="'.lang('Email').'"')?>
                                  </div>
                                </div>
                  
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang('Password')?></label>
                                  <div class="controls">
                                    <?php echo form_password('password', set_value('password', ''), 'class="form-control" id="inputPassword" placeholder="'.lang('Password').'" autocomplete="new-password"')?>
                                  </div>
                                </div>
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang('Confirmpassword')?></label>
                                  <div class="controls">
                                    <?php echo form_password('password_confirm', set_value('password_confirm', ''), 'class="form-control" id="inputPasswordConfirm" placeholder="'.lang('Confirmpassword').'" autocomplete="new-password"')?>
                                  </div>
                                </div>
                  <?php if(config_db_item('register_reduced') == FALSE): ?>
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang('Address')?></label>
                                  <div class="controls">
                                    <?php echo form_textarea('address', set_value('address', ''), 'placeholder="'.lang('Address').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>          
                                
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Phone')?> <?php echo lang_check('PhoneAdd')?></label>
                                  <div class="controls">
                                    <?php echo form_input('phone', set_value('phone', ''), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                  </div>
                                </div>
                                <?php if(config_db_item('phone_mobile_enabled') === TRUE): ?>
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Mobile phone')?> <?php echo lang_check('PhoneAdd')?></label>
                                  <div class="controls">
                                    <?php echo form_input('phone2', set_value('phone2', ''), 'class="form-control" id="inputPhone2" placeholder="'.lang('Mobile phone').'"')?>
                                  </div>
                                </div>
                                <?php endif; ?>
                   <?php endif; ?>

                                <?php if(config_db_item('terms_link') !== FALSE): ?>
                                <?php
                                    $site_url = site_url();
                                    $urlparts = parse_url($site_url);
                                    $basic_domain = $urlparts['host'];
                                    $terms_url = config_db_item('terms_link');
                                    $urlparts = parse_url($terms_url);
                                    $terms_domain ='';
                                    if(isset($urlparts['host']))
                                        $terms_domain = $urlparts['host'];

                                    if($terms_domain == $basic_domain) {
                                        $terms_url = str_replace('en', $lang_code, $terms_url);
                                    }
                                ?>
                                <div class="control-group">
                                  <label class="control-label"> <?php echo lang_check('Accept tems');?></label>
                                  <div class="controls">
                                      <input type="checkbox" value="1" name="terms_user" class="terms_user" required="required"> <a target="_blank" href="<?php echo $terms_url; ?>" style="line-height: 24px;vertical-align: top;display: inline-block;"><?php echo lang_check('I accept the Terms and Conditions'); ?></a>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(config_db_item('privacy_link') !== FALSE): ?>
                                <?php

                                    $site_url = site_url();
                                    $urlparts = parse_url($site_url);
                                    $basic_domain = $urlparts['host'];
                                    $privacy_url = config_db_item('privacy_link');
                                    $urlparts = parse_url($privacy_url);
                                    $privacy_domain ='';
                                    if(isset($urlparts['host']))
                                        $privacy_domain = $urlparts['host'];

                                    if($privacy_domain == $basic_domain) {
                                        $privacy_url = str_replace('en', $lang_code, $privacy_url);
                                    }
                                ?>
                                <div class="control-group">
                                  <label class="control-label"><?php echo lang_check('Accept privacy');?></label>
                                  <div class="controls">
                                    <input type="checkbox" value="1" name="privacy_user" class="privacy_user" required="required"> <a target="_blank" href="<?php echo $privacy_url; ?>" style="line-height: 24px;vertical-align: top;display: inline-block;"><?php echo lang_check('I accept the Privacy'); ?></a>
                                  </div>
                                </div>
                                <?php endif;?>
                                <?php if(config_item('captcha_disabled') === FALSE): ?>
                                <div class="control-group" >
                                    <label class="control-label captcha"><?php echo $captcha['image']; ?></label>
                                    <div class="controls">
                                        <input class="captcha" name="captcha" type="text" placeholder="{lang_Captcha}" value="" />
                                        <input class="hidden" name="captcha_hash" type="text" value="<?php echo $captcha_hash; ?>" />
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(config_item('recaptcha_site_key') !== FALSE): ?>
                                <div class="control-group" >
                                    <label class="control-label captcha"></label>
                                    <div class="controls">
                                        <?php _recaptcha(true); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                    <div class="control-group">
                        <div class="controls">
    						<button type="submit" class="btn btn-danger"><?php echo lang('Register')?></button>
    						<button type="reset" class="btn btn-success"><?php echo lang('Reset')?></button>
    					</div>
                    </div>
                  <?php echo form_close()?>
            </div></div>
        </div>
        <?php if(false):?>
        <br />
        <div class="property_content">
        {page_body}
        </div>
        <?php endif;?>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>