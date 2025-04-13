<?php if (config_item('agency_agent_enabled') === FALSE): ?>
    <?php if (!empty($estate_data_option_67)): ?>
        <div class="widget widget-box box-container widget-company">
            <div class="widget-title">
                <?php echo lang_check('Company details'); ?>
            </div>
            <div class="">
                <?php
                if (!empty($estate_data_option_74)) {
                    //Fetch repository
                    $rep_id = $estate_data_option_74;
                    $file_rep = $this->file_m->get_by(array('repository_id' => $rep_id));
                    $rep_value = '';
                    if (sw_count($file_rep)) {
                        echo '<div class="image-company"><img src="' . base_url('files/' . $file_rep[0]->filename) . '" alt="' . $estate_data_option_67 . '" /></div>';
                    }
                }
                ?>
                <?php if (!empty($estate_data_option_67)): ?><div class="name"><a href="{agent_url}#content"><?php echo $estate_data_option_67; ?></a></div><?php endif; ?>
                <?php if (!empty($estate_data_option_68)): ?><div class="phone"><?php echo $estate_data_option_68; ?></div><?php endif; ?>
                <?php if (!empty($estate_data_option_73)): ?><div class="hours"><?php echo lang_check('Office hours'); ?>: <?php echo $estate_data_option_73; ?></div><?php endif; ?>
                <?php if (!empty($estate_data_option_69)): ?><div class="website"><a target="_blank" style="color: blue;" href="<?php echo $estate_data_option_69; ?>"><?php echo lang_check('Website'); ?></a></div><?php endif; ?>
                <div style="padding: 5px;">
                <?php if (!empty($estate_data_option_73)): ?><div class="description"><em><?php echo $estate_data_option_73; ?></em></div><?php endif; ?>
                    <?php if (!empty($estate_data_option_72)): ?><div class="description"><em><?php echo $estate_data_option_72; ?></em></div><?php endif; ?>
                    <?php if (!empty($estate_data_option_70)): ?><a target="_blank" href="<?php echo $estate_data_option_70; ?>"><img src="assets/img/social-facebook-button-blue-icon.png" /></a><?php endif; ?>
                    <?php if (!empty($estate_data_option_71)): ?><a target="_blank" href="<?php echo $estate_data_option_71; ?>"><img src="assets/img/social-twitter-button-blue-icon.png" /></a><?php endif; ?>
                </div>
            </div>
        </div><!-- /. widget-OVERVIEW -->   
    <?php endif; ?>
<?php else: ?>
    {has_agent}
    <div class="widget widget-box box-container widget-company">
        <div class="widget-title">
            <?php echo lang_check('Company details'); ?>
        </div>
        <div class="">
            <?php if($is_private_listing): ?>
            <div class="purchase_package">
                <a class="btn2" href="{front_login_url}#content"><?php echo lang_check('Purchase To Show');?></a>
            </div>
            <?php else: ?>
                <?php
                if (!empty($agency_image_url)) {
                    echo '<div class="image-company"><img src="' .$agency_image_url . '" alt="' . _ch($estate_data_option_67,'') . '" /></div>';
                }
                ?>
                <?php if (!empty($estate_data_option_67)): ?><div class="name"><a href="{agent_url}#content"><?php echo $estate_data_option_67; ?></a></div><?php endif; ?>
                <?php if (!empty($estate_data_option_68)): ?><div class="phone"><?php echo $estate_data_option_68; ?></div><?php endif; ?>
                <?php if (!empty($estate_data_option_73)): ?><div class="hours"><?php echo lang_check('Office hours'); ?>: <?php echo $estate_data_option_73; ?></div><?php endif; ?>
                <?php if (!empty($estate_data_option_69)): ?><div class="website"><a target="_blank" style="color: blue;" href="<?php echo $estate_data_option_69; ?>"><?php echo lang_check('Website'); ?></a></div><?php endif; ?>
                <div style="padding: 5px;">
                <?php if (!empty($estate_data_option_73)): ?><div class="description"><em><?php echo $estate_data_option_73; ?></em></div><?php endif; ?>
                <?php if (!empty($estate_data_option_72)): ?><div class="description"><em><?php echo $estate_data_option_72; ?></em></div><?php endif; ?>
                <?php if (!empty($estate_data_option_70)): ?><a target="_blank" href="<?php echo $estate_data_option_70; ?>"><img src="assets/img/social-facebook-button-blue-icon.png" /></a><?php endif; ?>
                    <?php if (!empty($estate_data_option_71)): ?><a target="_blank" href="<?php echo $estate_data_option_71; ?>"><img src="assets/img/social-twitter-button-blue-icon.png" /></a><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div><!-- /. widget-OVERVIEW -->   
    {/has_agent}
<?php endif; ?>