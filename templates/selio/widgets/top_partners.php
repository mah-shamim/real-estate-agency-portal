<?php
/*
Widget-title: Real Estate Partners
Widget-preview-image: /assets/img/widgets_preview/top_partners.webp
 */

$is_enable = false;

foreach($all_agents as $agent) {
    if(isset($agent['image_sec_url'])) {
        $is_enable = true;
        break;
    }
}

?>
<?php if($is_enable):?>
<section class="partner-sec section-padding widget_edit_enabled">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-heading">
                    <span><?php echo lang_check('Trusted by the Best');?></span>
                    <h3><?php echo lang_check('Real Estate Partners');?></h3>
                </div>
            </div>
        </div><!--justify-content-center end-->
        <div class="partner-carousel">
            <?php foreach($all_agents as $agent): ?>
            <?php if(isset($agent['image_sec_url'])): ?>
             <div class="partner-logo">
                <a href="<?php echo $agent['agent_url']; ?>" title=""><img src="<?php echo $agent['image_sec_url']; ?>" alt="<?php echo $agent['name_surname']; ?>"></a>
            </div><!--partner-logo end-->
            <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach($all_agents as $agent): ?>
            <?php if(isset($agent['image_sec_url'])): ?>
             <div class="partner-logo">
                <a href="<?php echo $agent['agent_url']; ?>" title=""><img src="<?php echo $agent['image_sec_url']; ?>" alt="<?php echo $agent['name_surname']; ?>"></a>
            </div><!--partner-logo end-->
            <?php endif; ?>
            <?php endforeach; ?>
        </div><!--partner-carousel end-->
    </div>
</section><!--agents-sec end-->
<?php endif;?>
