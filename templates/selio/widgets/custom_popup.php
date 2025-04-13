<?php
$CI = &get_instance();
if(!isset($login_url_facebook)){
$login_url_facebook = '';
    if($CI->config->item('facebook_api_version') == '2.4' || floatval($this->config->item('facebook_api_version')) >= 2.4
          || version_compare($CI->config->item('facebook_api_version'), 2.4, '>') 
        )
    {
        $user_facebook = FALSE;
        if($CI->config->item('appId') != '')
        {   
            $CI->load->library('facebook/Facebook'); // Automatically picks appId and secret from config
            $user_facebook = $CI->facebook->getUser();
        }
        if ($user_facebook) {
        } else if($CI->config->item('appId') != ''){
            $login_url_facebook = $CI->facebook->login_url();
        }
    }
    else
    {
        $user_facebook = FALSE;
        if($CI->config->item('appId') != '')
        {
            $CI->load->library('facebook'); // Automatically picks appId and secret from config
            $user_facebook = $CI->facebook->getUser();
        }   
        $login_url_facebook = '';
        if ($user_facebook) {
        } else if($CI->config->item('appId') != ''){
            $login_url_facebook = $CI->facebook->getLoginUrl(array(
                'redirect_uri' => site_url('frontend/login/'.$this->data['lang_code']), 
                'scope' => array("email") // permissions here
            ));
        }
    }

}
?>

<div class="popup" id="sign-popup">
    <h3><?php echo lang_check('Sign In to your Account'); ?></h3>
    <div class="popup-form form-wr">
        <form id="popup_form_login">
            <?php if (config_item('app_type') == 'demo'): ?>
                <div class="alert alert-success m0" role="alert">
                    <b><?php echo lang_check('Demo login details for Admin'); ?>:</b><br />
                    <?php echo lang_check('Username'); ?>: <?php echo 'admin'; ?><br />
                    <?php echo lang_check('Password'); ?>:  <?php echo 'admin'; ?><br /><br />
                    <b> <?php echo lang_check('Demo login details for User'); ?>:</b><br />
                    <?php echo lang_check('Username'); ?>:  <?php echo 'user'; ?><br />
                    <?php echo lang_check('Password'); ?>:  <?php echo 'user'; ?>
                </div>
            <?php endif; ?>
            <div class="alerts-box"></div>
            <div class="form-field">
                <?php echo form_input('username', $this->input->get('username'), 'class="form-control" id="inputUsername" placeholder="'.lang('Username').'"')?>
            </div>
            <div class="form-field">
                <?php echo form_password('password', $this->input->get('password'), 'class="form-control" id="inputPassword" placeholder="'.lang('Password').'"')?>
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
                <a href="<?php echo site_url('/admin/user/forgetpassword');?>" class="forgot-password" title="<?php echo lang_check('Forgot Password');?>?"><?php echo lang_check('Forgot Password');?>?</a>
            </div><!--form-cp end-->
            <button type="submit" class="btn2"><?php echo lang_check('Sign In');?><i class="fa fa-spinner fa-spin fa-ajax-indicator hidden ajax-indicator"></i></button>
        </form>
        <a href="{front_login_url}#sw_register" class="link-bottom"><?php echo lang_check('Create new account');?></a>
        <?php if(config_item('appId') != '' && !empty($login_url_facebook)): ?>
            <a href="<?php echo $login_url_facebook;?>" style=""  class="fb-btn"><i class="fa fa-facebook" aria-hidden="true"></i><?php echo lang_check('Sign in with facebook'); ?></a>
        <?php endif;?>
        <?php if(config_item('glogin_enabled')): ?>
            <a href="<?php echo site_url('api/google_login/'.$lang_id); ?>" style=""  class="gl-btn"><i class="fa fa-google" aria-hidden="true"></i><?php echo lang_check('Sign in with Google'); ?></a>
        <?php endif;?>
    </div>
</div><!--popup end-->

<?php
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_popup_js.php');
?>
