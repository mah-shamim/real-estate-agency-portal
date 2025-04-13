<?php
/*
 * 
 * Hidden and encoding string (for robots), add decoding btn for user
 * 
 * @param $str (string)  string for hiden
 * @param $class (string) css class`es
 * @param $preview_length (int), max preview character
 * 
 * return html string  (<div id="%id%" class="%$class%">
                            <span class="val_protected_mask">%$str%xxxxxxx</span> 
                            <a href="#" class="val_protected_spoiler">show</a>
                        </div>)
 */
if ( ! function_exists('anti_spam_field'))
{
    function anti_spam_field($str=NULL, $class='', $preview_length=2)
    { 
      if($str === NULL) return false;  
      $type ='';

      if(filter_var($str, FILTER_VALIDATE_EMAIL))
        $type = 'mail';

      $character_set = "+-.,0123456789@() ~!#$%^&*?ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz";

      $key = str_shuffle($character_set); 
      $cipher_text = ''; $id = 'e'.rand(1,999999999);
      for ($i=0;$i<strlen($str);$i+=1) {
        if(strpos($character_set, $str[$i]) !== FALSE )
            $cipher_text.= $key[strpos($character_set,$str[$i])];
      }

      $script = '$("#'.$id.' .val_protected_spoiler").click(function(e){e.preventDefault();';
      $script.= 'var str = "'.$key.'";var length="'.$cipher_text.'";';
      $script.= 'var character_un = "'.$character_set.'";var r="";';
      $script.= 'for(var e=0;e<length.length;e++)r+=character_un.charAt(str.indexOf(length.charAt(e)));';
      $script.= '$(this).parent().find(".val_protected_mask").remove();';

      if($type == 'mail')
        $script.= 'var x = "<a href=\\"mailto:"+r+"\\">"+r+"</a>";';
      else 
        $script.= 'var x = "<span>"+r+"</span>";';

      $script.= '$(this).parent().prepend(x);$(this).remove();})';
      $script = '<script>'.$script.'</script>';

      return '<span id="'.$id.'" class="'.$class.'"><span class="val_protected_mask">'.substr($str, 0, $preview_length).'xxxxxx</span> <a href="#" class="val_protected_spoiler">'.lang_check('unhide').'</a></span>'.$script;
    }
}
?>
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
                <?php _widget('top_title'); ?>
            <section class="listing-main-sec section-padding2">
                <div class="container">
                    <div class="listing-main-sec-details">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="agent-profile">
                                    <div class="agent-img">
                                        <img src="{agent_image_url}" alt="{page_title}">
                                    </div><!--agent-img end-->
                                    <div class="agent-info">
                                        <h3>{page_title}</h3>
                                        <h4><?php echo $agent_profile['address']; ?></h4>

                                        <p class="profile-description">

                                            <?php echo $agent_profile['description']; ?>
                                        </p>
                                        <!-- Example to print all custom fields in list -->
                                            <?php profile_cf_li(); ?>

                                        <!-- Example to print specific custom field with label -->
                                            <?php //profile_cf_single(1, TRUE);  ?>

                                        <ul class="cont-links">
                                            <li><span><i class="la la-phone"></i><?php echo anti_spam_field(_ch($agent_phone)); ?></span></li>
                                            <li><a href="mailto:<?php echo _ch($agent_mail); ?>" title="<?php echo _ch($agent_mail); ?>"><i class="la la-envelope"></i></a><?php echo anti_spam_field(_ch($agent_mail)); ?></li>
                                        </ul>

                                        <ul class="socio-links">
                                            <?php if (!empty($agent_profile['facebook_link'])): ?>
                                                <li><a class="facebook"  href="<?php echo $agent_profile['facebook_link']; ?>"><i class="fa fa-facebook facebook"></i></a></li>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_profile['youtube_link'])): ?>
                                                <li><a class="twitter" href="<?php echo $agent_profile['youtube_link']; ?>"><i class="fa fa-youtube youtube"></i></a></li>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_profile['gplus_link'])): ?>
                                                <li><a class="google-plus" href="<?php echo $agent_profile['gplus_link']; ?>"><i class="fa fa-google-plus google"></i></a></li>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_profile['twitter_link'])): ?>
                                                <li><a class="twitter" href="<?php echo $agent_profile['twitter_link']; ?>"><i class="fa fa-twitter twitter"></i></a></li>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_profile['linkedin_link'])): ?>
                                                <li><a class="google-plus" href="<?php echo $agent_profile['linkedin_link']; ?>"><i class="fa fa-linkedin linkedin"></i></a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div><!--agent-info end-->
                                </div>
                                <?php
                                if (!empty($agent_profile['embed_video_code'])):?>
                                <div class="clear_both"></div>
                                <p class="section-padding sec-profile-video">
                                    <?php
                                        echo $agent_profile['embed_video_code'];
                                    ?>
                                </p>
                                <?php endif;?>
                                <div class="similar-listings-posts">
                                    <h3 class='title'><?php _l('Assigned Properties'); ?></h3>
                                    <div id="ajax_results">
                                    <div class="list-products" id="ajax_results">
                                            <?php foreach ($agent_estates as $key => $item): ?>
                                            <div class="card">
                                                <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
                                                    <div class="img-block">
                                                        <div class="overlay"></div>
                                                        <img src="<?php echo _simg($item['thumbnail_url'], '851x678', true); ?>" alt="<?php echo _ch($item['option_10']); ?>" class="img-fluid">
                                                        <div class="rate-info">
                                                                <?php if (!empty($item['option_36']) || !empty($item['option_37'])): ?>
                                                                <h5>
                                                                    <?php if (!empty($item['option_36']) || !empty($item['option_37'])): ?>
                                                                        <?php if (_ch($item['option_4'], false) && stripos($item['option_4'], lang_check('Rent')) !== FAlSE): ?>
                                                                            <?php 
                                                                                if(!empty($item['option_37']))echo ' '.show_price($item['option_37'], $options_prefix_37, $options_suffix_37, $lang_id);
                                                                                if(!empty($item['option_37']) && !empty($item['option_36'])) echo ' / ';
                                                                                if(!empty($item['option_36']))echo show_price($item['option_36'], $options_prefix_36, $options_suffix_36, $lang_id);
                                                                            ?>
                                                                        <?php else: ?>
                                                                           <?php 
                                                                                if(!empty($item['option_36']))echo show_price($item['option_36'], $options_prefix_36, $options_suffix_36, $lang_id);
                                                                                if(!empty($item['option_37']) && !empty($item['option_36'])) echo ' / ';
                                                                                if(!empty($item['option_37']))echo ' '.show_price($item['option_37'], $options_prefix_37, $options_suffix_37, $lang_id);
                                                                            ?>
                                                                        <?php endif; ?>
                                                                <?php endif; ?>
                                                                </h5>
                                                                <?php endif; ?>
                                                                <?php if (_ch($item['option_4'], false)): ?>
                                                                <span><?php echo _ch($item['option_4'], ''); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="card_bod_full">
                                                    <div class="card-body">
                                                        <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>">
                                                            <h3><?php echo _ch($item['option_10']); ?></h3>
                                                            <p><i class="la la-map-marker"></i><?php _che($item['address']); ?></p>
                                                        </a>
                                                        <ul>
                                                            <?php
                                                            $custom_elements = _get_custom_items();
                                                            $i = 0;
                                                            if (sw_count($custom_elements) > 0):
                                                                foreach ($custom_elements as $key => $elem):

                                                                if (!empty($item['option_' . $elem->f_id]) && $i++ < 3)
                                                                    if ($elem->type == 'DROPDOWN' || $elem->type == 'INPUTBOX'):
                                                                        ?>
                                                                        <li class=""><i class="fa <?php _che($elem->f_class); ?>"></i><small><?php echo _ch($item['option_' . $elem->f_id], '-'); ?> <?php echo _ch(${"options_suffix_$elem->f_id"}, ''); ?> <span style="<?php _che($elem->f_style); ?>"><?php echo _ch(${"optionssw_name_$elem->f_id"}, '-'); ?></span></li>
                                                                        <?php
                                                                    elseif ($elem->type == 'CHECKBOX'):
                                                                        ?>
                                                                        <li class=""><i class="fa <?php _che($elem->f_class); ?>"></i><span class="<?php echo (!empty($item['option_' . $elem->f_id])) ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove'; ?>"></span> <?php echo _ch(${"optionssw_name_$elem->f_id"}, '-'); ?></li>
                                                                        <?php
                                                                        endif;
                                                                    endforeach;
                                                                else:
                                                                ?>
                                                                <li class=""><?php echo _ch($item['option_19'], '-'); ?> <?php echo _ch($options_name_19, '-'); ?></li>
                                                                <li class=""><?php echo _ch($item['option_20'], '-'); ?> <?php echo _ch($options_name_20, '-'); ?></li>
                                                                <li class=""><?php echo _ch($item['option_57'], '-'); ?> <?php echo _ch($options_name_57, '-'); ?></li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="crd-links">
                                                            <span class="favorites-actions pull-left">
                                                                <a href="#" data-id="<?php echo _ch($item['id']); ?>" class="add-to-favorites" style="<?php echo ($item['is_favorite']) ? 'display:none;' : ''; ?>">
                                                                    <i class="la la-heart-o"></i>
                                                                </a>
                                                                <a href="#" data-id="<?php echo _ch($item['id']); ?>" class="remove-from-favorites" style="<?php echo (!$item['is_favorite']) ? 'display:none;' : ''; ?>">
                                                                    <i class="la la-heart-o"></i>
                                                                </a>
                                                                <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
                                                            </span>
                                                            <a href="#" class="plf" title='<?php echo _ch($item['date']); ?>'>
                                                                <i class="la la-calendar-check-o"></i> 
                                                                <?php
                                                                $date_modified = $item['date'];
                                                                $date_modified_str = strtotime($date_modified);
                                                                echo human_time_diff($date_modified_str);
                                                                ?>
                                                            </a>
                                                        </div><!--crd-links end-->
                                                        <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>" class="btn-default"><?php echo lang_check('View Details'); ?></a>
                                                    </div>
                                                </div><!--card_bod_full end-->
                                                <a href="<?php echo $item['url']; ?>" title="<?php echo _ch($item['option_10']); ?>" class="ext-link"></a>
                                            </div><!--card end-->
                                        <?php endforeach; ?>
                                    </div><!-- list-products end-->
                                    <div class="row-fluid clearfix text-center">
                                        <div class="pagination-ajax-results pagination  wp-block default product-list-filters light-gray pagination" rel="ajax_results">
                                            <?php echo $pagination_links_agent; ?>
                                        </div>
                                    </div>
                                    </div>
                                </div>   
                            </div>
                            <div class="col-lg-4">
                                <div class="widget widget_sw_win_contactform_widget side"><h3 class="widget-title"><?php echo lang_check('Contact Agent'); ?></h3>
                                    <div class="contact-agent widget-form" id="contact-form">
                                        <form method="post" class="contact-form" action="{page_current_url}#contact-form">
                                            <div class="box-container widget-body">

                                                <input type="hidden" name="form" value="contact" />
                                                {validation_errors} {form_sent_message}
                                                <!-- The form name must be set so the tags identify it -->
                                                <input type="hidden" name="form" value="contact" />

                                                <div class="form-field {form_error_firstname}">
                                                    <input class="" id="firstname" name="firstname" type="text" placeholder="{lang_FirstLast}" value="{form_value_firstname}" />
                                                </div>
                                                <div class="form-field {form_error_email}">
                                                    <input class="" id="email" name="email" type="text" placeholder="{lang_Email}" value="{form_value_email}" />
                                                </div>
                                                <div class="form-field {form_error_phone}">
                                                    <input class="" id="phone" name="phone" type="text" placeholder="{lang_Phone}" value="{form_value_phone}" />
                                                </div>
                                                <div class="form-field {form_error_address}">
                                                    <input class="" id="address" name="address" type="text" placeholder="{lang_Address}" value="{form_value_address}" />
                                                </div>

                                                <div class="form-field {form_error_message}">
                                                    <textarea id="message" name="message" rows="1" class="" type="text" placeholder="{lang_Message}">{form_value_message}</textarea>
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
                            </div>
                        </div>
                    </div><!--listing-main-sec-details end-->
                </div>    
            </section><!--listing-main-sec end-->
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'default')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
    </body>
</html>

