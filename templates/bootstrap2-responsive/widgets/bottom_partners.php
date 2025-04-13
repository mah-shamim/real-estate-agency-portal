<div class="wrap-content2">
    <div class="container">
        <h2>{lang_Agencies}</h2>
        <!-- AGENCIES -->
        <div class="property_content_position">
        <div class="row-fluid bottom_partners">
        <?php if(!empty($all_agents)):foreach($all_agents as $agent): ?>
                <?php if(isset($agent['image_sec_url'])): ?>
                  <div class="span2 agencies-logo"><a href="<?php echo $agent['agent_url']; ?>"><img src="<?php echo $agent['image_sec_url']; ?>" alt="" /></a></div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="alert alert-success">
            <?php echo lang_check('Not found');?>
        </div>
        <?php endif; ?>
        </div>
        <br />
        </div>
        <!-- AGENCIES -->
    </div>
</div>