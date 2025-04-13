<?php
/* get from listings */
if(_ch($estate_data_option_67, false))
    $agent_name_surname = $estate_data_option_67;

if(_ch($estate_data_option_68, false))
    $agent_phone = $estate_data_option_68;

?>

<div class="widget widget-form" id="form-contact">
    <h3 class="widget-title"><?php echo lang_check('Contact Listing Agent');?></h3>
    {has_agent}
    <div class="contct-info">
        <?php
        $image_added = false;
        if (!empty($estate_data_option_74)):
            //Fetch repository
            $rep_id = $estate_data_option_74;
            $file_rep = $this->file_m->get_by(array('repository_id' => $rep_id));
            $rep_value = '';
            if (sw_count($file_rep)) {
                echo '<img src="' ._simg(base_url('files/' . $file_rep[0]->filename), "133x133", true) . '" alt="' . $estate_data_option_67 . '" />';
                $image_added = true;
            }
        ?>
        <?php endif;?>
        <?php if(!$image_added):?>
            <?php if(strripos($agent_name_surname, 'user-agent.png') === FALSE):?>
                <img src="<?php echo _simg($agent_image_url, "133x133", true);?>" alt="<?php  _che($agent_name_surname);?>" />
            <?php else:?>
                <img src="<?php _ch($agent_image_url);?>" alt="<?php  _che($agent_name_surname);?>" />
            <?php endif;?>
        <?php endif;?>
        <div class="contct-nf">
            <h3><a href="<?php  _che($agent_url);?>" title='<?php  _che($agent_name_surname);?>'><?php  _che($agent_name_surname);?></a></h3>
            <h4>{agent_address}</h4>
            <span><i class="la la-phone"></i>{agent_phone}</span>
            <span><i class="la la-envelope-o"></i><a href="mailto:<?php  _che($agent_mail);?>?subject=<?php _l('Estateinqueryfor');?>: {estate_data_id}, {page_title}" title="<?php  _che($agent_mail);?>" class=""><?php  _che($agent_mail);?></a></span>
        </div>
    </div><!--contct-info end-->
    {/has_agent}
    <div class="contct-info-sec">
        <div class="desc">
            <?php if(!empty($estate_data_option_72)): ?><div class="description"><em><?php echo $estate_data_option_72; ?></em></div><?php endif; ?>
        </div>     
        <?php if(!empty($estate_data_option_73)): ?><div class="hours"><?php echo lang_check('Office hours'); ?>: <?php echo $estate_data_option_73; ?></div><?php endif; ?>
        <ul class="socio-links">
            <?php if(!empty($estate_data_option_70)): ?>
            <li><a class="facebook" href="<?php echo $estate_data_option_70; ?>"><i class="fa fa-facebook facebook"></i></a></li>
            <?php endif;?>
            
            <?php if(!empty($estate_data_option_71)): ?>
            <li><a class="twitter" href="<?php echo $estate_data_option_71; ?>"><i class="fa fa-twitter twitter"></i></a></li>
            <?php endif;?>
            
            <?php if(!empty($estate_data_option_69)): ?>
            <li><a class="twitter" href="<?php echo $estate_data_option_69; ?>"><i class="fa fa-globe twitter"></i></a></li>
            <?php endif;?>
        </ul>
    </div><!--contct-info end-->
    <div class="post-comment-sec">
        <form action="{page_current_url}#form-contact" method="post" class="contact-form" id="form">
            {validation_errors} {form_sent_message}
            <input type="hidden" name="form" value="contact" />
            <?php if(config_item('reservations_disabled') === FALSE ||
            (file_exists(APPPATH.'controllers/admin/booking.php') && sw_count($is_purpose_rent) && $this->session->userdata('type')=='USER' && config_item('reservations_disabled') === FALSE)): ?>
                {is_purpose_rent}
                <div class="form-field {form_error_fromdate}">
                    <input class=""  id="datetimepicker1" name="fromdate" type="text" placeholder="{lang_FromDate}" value="{form_value_fromdate}" />
                </div><!-- /.form-field -->
                <div class="form-field {form_error_todate}">
                    <input class="" id="datetimepicker2" name="todate" type="text" placeholder="{lang_ToDate}" value="{form_value_todate}" />
                </div><!-- /.form-field -->
            {/is_purpose_rent}
            <?php endif; ?>
            
            <div class="form-field {form_error_firstname}">
                <input class="" id="firstname" name="firstname" type="text" placeholder="<?php _l('FirstLast'); ?>" value="{form_value_firstname}" />
            </div>
            <div class="form-field {form_error_email}">
                <input class="" id="email" name="email" type="text" placeholder="<?php _l('Email'); ?>" value="{form_value_email}" />
            </div>
            <div class="form-field {form_error_phone}">
                <input class="" id="phone" name="phone" type="text" placeholder="<?php _l('Phone'); ?>" value="{form_value_phone}" />
            </div>
            <div class="form-field {form_error_address}">
                <input class="" id="address" name="address" type="text" placeholder="<?php _l('Address'); ?>" value="{form_value_address}" />
            </div>
            <div class="form-field {form_error_message}">
                <textarea id="message" name="message" rows="3" class=" resize-vertical" placeholder="<?php _l('Message'); ?>">{form_value_message}</textarea>
            </div>
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
            <?php if (config_item('recaptcha_site_key') !== FALSE): ?>
                <div class="form-field" >
                    <div class="controls">
                        <?php _recaptcha(); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (config_db_item('terms_link') !== FALSE): ?>
                <?php
                $site_url = site_url();
                $urlparts = parse_url($site_url);
                $basic_domain = $urlparts['host'];
                $terms_url = config_db_item('terms_link');
                $urlparts = parse_url($terms_url);
                $terms_domain = '';
                if (isset($urlparts['host']))
                    $terms_domain = $urlparts['host'];

                if ($terms_domain == $basic_domain) {
                    $terms_url = str_replace('en', $lang_code, $terms_url);
                }
                ?>
                <div class="form-field input-field checkbox-field" >
                    <?php echo form_checkbox('option_agree_terms', 'true', set_value('option_agree_terms', false), 'class="novalidate" required="required" id="inputOption_terms"') ?>
                    <label for="inputOption_terms">
                        <span></span>
                        <a target="_blank" href="<?php echo $terms_url; ?>"><?php echo lang_check('I Agree To The Terms & Conditions, Privacy and GDPR Policies'); ?></a>
                    </label>
                </div>
            <?php endif; ?>
            <?php if (config_db_item('privacy_link') !== FALSE && sw_count($not_logged) > 0): ?>
                <?php
                $site_url = site_url();
                $urlparts = parse_url($site_url);
                $basic_domain = $urlparts['host'];
                $privacy_url = config_db_item('privacy_link');
                $urlparts = parse_url($privacy_url);
                $privacy_domain = '';
                if (isset($urlparts['host']))
                    $privacy_domain = $urlparts['host'];

                if ($privacy_domain == $basic_domain) {
                    $privacy_url = str_replace('en', $lang_code, $privacy_url);
                }
                ?>
                <div class="form-field input-field checkbox-field" >
                        <?php echo form_checkbox('option_privacy_link', 'true', set_value('option_privacy_link', false), 'class="novalidate" required="required" id="inputOption_privacy_link"') ?>
                    <label for="inputOption_privacy_link">
                        <span></span>
                        <a target="_blank" href="<?php echo $terms_url; ?>"><?php echo lang_check('I Agree The Privacy'); ?></a>
                    </label>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn2"><?php echo lang_check('Send Message'); ?></button>
        </form>
    </div><!--post-comment-sec end-->
</div><!--widget-form end-->
<?php
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_booking_js.php');
?>