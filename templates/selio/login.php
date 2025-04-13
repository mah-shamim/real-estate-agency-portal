<!doctype html>
<html class="no-js" lang="en">
    <head>
        <?php _widget('head'); ?>
    </head>
    <body>
        <div class="wrapper">
            <header>
                <?php _widget('header_bar'); ?>
                <?php _widget('header_main_panel'); ?>
            </header><!--header end-->
            <?php if(file_exists(APPPATH.'controllers/admin/packages.php')): ?>
            <div class="container">
                <h2 class="widget-header text-uppercase">
                    {lang_AvailablePackages}
                </h2>
                <div class="widget-content">
                    <?php if($this->session->flashdata('error_package')):?>
                        <p class="alert alert-error"><?php echo $this->session->flashdata('error_package')?></p>
                    <?php endif;?>
                    <table class="table table-striped data_table" style="margin-bottom: 0px;" >
                        <thead>
                            <th  data-priority="1" data-breakpoints="xs" data-type="number">#</th>
                            <th  data-priority="2" data-type="html"><?php echo lang_check('Package name');?></th>
                            <th  data-priority="2" data-type="html"><?php echo lang_check('Price');?></th>
                            <th  data-priority="2" data-breakpoints="xs sm md" data-type="html"><?php echo lang_check('Free property activation');?></th>
                            <th  data-priority="2" data-breakpoints="xs sm md" data-type="html"><?php echo lang_check('Days limit');?></th>
                            <th  data-priority="2" data-breakpoints="xs sm" data-type="html"><?php echo lang_check('Listings limit');?></th>
                            <th  data-priority="2" data-breakpoints="xs sm md" data-type="html"><?php echo lang_check('Free featured limit');?></th>
                        </thead>
                        <tbody>
                            <?php
                            if(sw_count($packages)): foreach($packages as $package):
                            ?>
                            <tr>
                                <td><?php echo $package->id; ?></td>
                                <td>
                                <?php echo $package->package_name; ?>
                                <?php echo $package->show_private_listings==1?'&nbsp;<i class="fa fa-eye-open"></i>':'&nbsp;<i class="fa fa-eye-close"></i>'; ?>
                                </td>
                                <td><?php echo $package->package_price.' '.$package->currency_code; ?></td>
                                <td><?php echo $package->auto_activation?'<i class="fa fa-check"></i>':'<i class="fa fa-remove"></i>'; ?></td>
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
                    <?php if(isset($settings_activation_price) && isset($settings_featured_price) &&
                            $settings_activation_price > 0 || $settings_featured_price > 0): ?>
                    <p class="row-fluid clearfix">
                        <br/>
                            <?php if($settings_activation_price > 0): ?>
                                <?php echo lang_check('* Property activation price:').' '.$settings_activation_price.' '.$settings_default_currency; ?><br />
                            <?php endif;?>
                            <?php if($settings_featured_price > 0): ?>
                                <?php echo lang_check('* Property featured price:').' '.$settings_featured_price.' '.$settings_default_currency; ?>
                            <?php endif;?>
                    </p>
                    <?php endif;?>
                </div>
            </div> <!-- /. widget-AVAILABLE PACKAGES -->   
            <?php endif; ?>

            <main class="main-clear">
                <div class="selio_sw_win_wrapper">
                    <div class="ci sw_widget sw_wrap">
                        <ul class="nav nav-tabs d-none sw-sign-form-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link log-in" href="#log-in-form" role="tab" data-toggle="tab"><?php echo lang_Check('Log in');?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link sign-up" href="#sign-up-form" role="tab" data-toggle="tab"><?php echo lang_Check('Sign Up');?></a>
                            </li>
                        </ul>
                        <div class="sign-form-wr">
                            <div class="sign-form-inner tab-content">
                                <!-- Log In -->
                                <div class="form-wr log-in-form tab-pane fade" role="tabpanel" id="log-in-form">
                                    <h3><?php echo lang_check('Sign In to your Account');?></h3>
                                    <div class="form-wr-content">
                                        <form method="post" action="#sw_login">
                                            <?php if($this->session->flashdata('error_registration') != ''):?>
                                            <p class="alert alert-success"><?php echo $this->session->flashdata('error_registration')?></p>
                                            <?php endif;?>
                                            <?php if($is_registration):?>
                                            <?php echo validation_errors()?>
                                            <?php endif;?>
                                            <?php if(config_item('app_type') == 'demo'):?>
                                            <p class="alert alert-info"><?php echo lang_check('User creditionals: user, user')?></p>
                                            <?php endif;?>
                                            <?php if($is_login):?>
                                            <?php echo validation_errors()?>
                                            <?php if($this->session->flashdata('error')):?>
                                            <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                                            <?php endif;?>
                                            <?php flashdata_message();?>
                                            <?php endif;?>
                                            <div class="form-field">
                                                <?php echo form_input('username', $this->input->get('username'), 'class="form-control" id="inputUsername_l" placeholder="'.lang('Username').'"')?>
                                            </div>
                                            <div class="form-field">
                                                <?php echo form_password('password', $this->input->get('password'), 'class="form-control" id="inputPassword_l" placeholder="'.lang('Password').'"')?>
                                            </div>
                                            <div class="form-cp">
                                                <div class="form-field">
                                                    <div class="input-field">
                                                        <input type="checkbox" name="remember" id="remember" value="true">
                                                        <label for="remember">
                                                            <span></span>
                                                            <small><?php echo lang('Remember me')?></small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <a href="#" class="forgot-password create-op" title="<?php echo lang_check('Create');?>?"><?php echo lang_check('Create');?>?</a> <span class="or"> / </span>
                                                <a href="<?php echo site_url('/admin/user/forgetpassword');?>" class="forgot-password" title="<?php echo lang_check('Forgot Password');?>?"><?php echo lang_check('Forgot Password');?>?</a>
                                            </div><!--form-cp end-->
                                            <button type="submit" class="btn2"><?php echo lang_check('Sign In');?></button>
                                        </form>
                                        <?php if(config_item('appId') != '' && !empty($login_url_facebook)): ?>
                                            <a href="<?php echo $login_url_facebook; ?>" style=""  class="fb-btn"><i class="fa fa-facebook" aria-hidden="true"></i><?php echo lang_check('Sign in with facebook'); ?></a>
                                        <?php endif;?>
                                        <?php if(config_item('glogin_enabled')): ?>
                                            <a href="<?php echo site_url('api/google_login/'.$lang_id); ?>" style=""  class="gl-btn"><i class="fa fa-google" aria-hidden="true"></i><?php echo lang_check('Sign in with Google'); ?></a>
                                        <?php endif;?>
                                        <?php if(file_exists(APPPATH.'libraries/Twlogin.php') && false): ?>
                                            <?php 
                                                $CI = &get_instance();
                                                $CI->load->library('twlogin');
                                            ?>
                                            <?php if($CI->twlogin->__get('consumerKey') && $CI->twlogin->__get('consumerSecret')): ?>
                                                <a href="<?php echo site_url('api/twitter_login/'.$lang_id); ?>" style="">  class="fb-btn"<i class="fa fa-twitter" aria-hidden="true"></i><?php echo lang_check('Sign in with Twitter'); ?></a>
                                            <?php endif;?>
                                        <?php endif;?>
                                    </div>
                                </div>
                                <!-- End Log In -->
                                <!-- Sign In -->
                                <div class="form-wr sign-up-form tab-pane fade" role="tabpanel" id="sign-up-form">
                                    <h3><?php echo lang_check('Register');?></h3>
                                    <div class="form-wr-content">
                                        <form method="post" action="#sw_register">
                                            <?php if($this->session->flashdata('error_registration') != ''):?>
                                            <p class="alert alert-success"><?php echo $this->session->flashdata('error_registration')?></p>
                                            <?php endif;?>
                                            <?php if($is_registration):?>
                                            <?php echo validation_errors()?>
                                            <?php endif;?>
                                            <?php if(config_db_item('dropdown_register_enabled') === TRUE): ?>
                                            <div class="form-field">
                                                <?php 
                                                $values = array('USER' => lang_check('USER'), 'AGENT' => lang_check('AGENT'));
                                                echo form_dropdown('type', $values, set_value('type', ''), 'class="form-control" id="input_type"')?>
                                            </div>
                                            <?php endif; ?>
                                            <?php if(config_db_item('register_reduced') == FALSE): ?>
                                            <div class="form-field">
                                               <?php echo form_input('name_surname', set_value('name_surname', ''), 'class="form-control" id="inputNameSurname" placeholder="'.lang('FirstLast').'"')?>
                                            </div>
                                            <div class="form-field">
                                                <?php echo form_input('username', set_value('username', ''), 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
                                            </div>
                                            <?php endif;?>
                                            <div class="form-field">
                                               <?php echo form_input('mail', set_value('mail', ''), 'class="form-control" id="inputMail" placeholder="'.lang('Email').'"')?>
                                            </div>
                                            <div class="form-field">
                                                <?php echo form_password('password', set_value('password', ''), 'class="form-control" id="inputPassword" placeholder="'.lang('Password').'" autocomplete="new-password"')?>
                                            </div>
                                            <div class="form-field">
                                               <?php echo form_password('password_confirm', set_value('password_confirm', ''), 'class="form-control" id="inputPasswordConfirm" placeholder="'.lang('Confirmpassword').'" autocomplete="new-password"')?>
                                            </div>
                                            <?php if(config_db_item('register_reduced') == FALSE): ?>
                                            <div class="form-field">
                                                <?php echo form_textarea('address', set_value('address', ''), 'placeholder="'.lang('Address').'" rows="3" class="form-control"')?>
                                            </div>
                                            
                                            <div class="form-field">
                                                <?php echo form_input('phone', set_value('phone', ''), 'class="form-control" id="inputPhone" placeholder="'.lang('Phone').'"')?>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (config_item('captcha_disabled') === FALSE): ?>
                                                <div class="form-field {form_error_captcha}">
                                                    <div class="form_captcha">
                                                        <?php echo $captcha['image']; ?>
                                                        <div class="input-control">
                                                            <input class="captcha  {form_error_captcha}" name="captcha" type="text" placeholder="<?php _l('Captcha'); ?>" value="" />
                                                            <input class="hidden" name="captcha_hash" type="text" value="<?php echo $captcha_hash; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if(config_item('recaptcha_site_key') !== FALSE): ?>
                                            <div class="form-field form-field-captcha" >
                                                <div class="controls">
                                                    <?php _recaptcha(true); ?>
                                               </div>
                                            </div>
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
                                            <div class="form-cp">
                                                <div class="form-field">
                                                    <div class="input-field">
                                                        <input type="checkbox" name="registr_terms" required="" id="registr_terms">
                                                        <label for="registr_terms">
                                                            <span></span>
                                                            <small> <a target="_blank" href="<?php echo $terms_url; ?>"><?php echo lang_check('I accept the GDPR'); ?></a></small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <a href="#log-in-form" title="Have an account?" class="signin-op"><?php echo lang_check('Have an account?');?></a>
                                            </div>
                                            <?php endif;?>
                                            <button type="submit" class="btn2"><?php echo lang_check('Create Account');?></button>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Sign In -->
                            </div>
                        </div>
                    </div>
                </div>                                
            </main>
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
        <?php
        /* dinamic per listing */
        _generate_js('_generate_login_page_js_' . md5(current_url_q()), 'widgets/_generate_login_page_js.php', false, 0);
        ?>
    </body>

</html>