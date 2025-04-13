<?php
/*
Widget-title: Contact page body (bpdy from secription of page)
Widget-preview-image: /assets/img/widgets_preview/top_contact_body.jpg
*/
?>

<div class="contact-sec widget_edit_enabled">
    <div class="container">
        <div class="contact-details-sec">
            <div class="row">
                <div class="col-lg-8 col-md-8 pl-0 pr-0">
                    <div class="contact_form">
                        <h3>{page_title}</h3>
                        <form action="{page_current_url}#form-contact" method="post" class="contact-form">
                            <?php _che($validation_errors); ?>
                            <?php _che($form_sent_message); ?>
                            <div class="form-fieldss">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-field {form_error_firstname}">
                                            <input type="text" id="firstname" name="firstname" class="" placeholder="<?php _l('FirstLast'); ?>" value="{form_value_firstname}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-field {form_error_email}">
                                            <input type="email" id="email" name="email" class="" placeholder="<?php _l('Email'); ?>" value="{form_value_email}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-field {form_error_phone}">
                                            <input type="text" id="phone" name="phone" class="" placeholder="<?php _l('Phone'); ?>"  value="{form_value_phone}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-field {form_error_message}">
                                            <textarea class=""  id="message" name="message" rows="10" placeholder="<?php _l('Message'); ?>">{form_value_message}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
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
                                                    <?php _recaptcha(true); ?>
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
                                    </div>
                                    <div class="col-lg-12 col-md-12 pl-0">
                                        <button type="submit" class="btn-default submit"><?php echo lang_check('Send Message'); ?></button>
                                    </div>
                                </div>
                            </div><!--form-fieldss end-->
                        </form>
                    </div><!--contact_form end-->
                </div>
                <div class="col-lg-4 col-md-4 pr-0">
                    <div class="contact_info">
                        {page_body}
                    </div>
                </div>
            </div>
        </div><!--contact-details-sec end-->
    </div>
</div><!--contact-sec end-->