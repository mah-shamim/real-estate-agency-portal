
<div class="widget widget_sw_win_contactform_widget side"><h3 class="widget-title">{lang_AskExpert}</h3>
    <div class="contact-agent widget-form" id="contact-form">
        <form method="post" class="contact-form" action="{page_current_url}#contact-form">
            <div class="box-container widget-body">
                {validation_errors} {form_sent_message}
                <!-- The form name must be set so the tags identify it -->
                <div class="form-field {form_error_firstname}">
                    <input type="text" id="firstname" name='firstname' class="" placeholder="<?php _l('FirstLast');?>" value="{form_value_firstname}">
                </div>
                <div class="form-field {form_error_email}">
                    <input type="text" name="email" id="email" class="" placeholder="<?php _l('Email');?>" value="{form_value_email}">
                </div>
                <div class="form-field {form_error_phone}">
                    <input type="text" name="phone" id="phone" class="" placeholder="<?php _l('Phone');?>" value="{form_value_phone}" >
                </div>
                <div class="form-field {form_error_question}">
                    <textarea id="question" name="question" rows="3" class="" placeholder="<?php _l('Question');?>">{form_value_question}</textarea>
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
                <button type="submit" class="btn2">{lang_Send}</button>
            </div>
        </form>
    </div>
</div>