<!doctype html>
<html class="no-js" lang="en">
<head>
    <?php _widget('head');?>
</head>
<body>
    <div class="wrapper">
        <header>
            <?php _widget('header_bar');?>
            <?php _widget('header_main_panel');?>
        </header><!--header end-->
        <?php _widget('top_title');?>
        <section class="listing-main-sec section-padding2">
            <div class="container">
                <div class="listing-main-sec-details">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="clearfix section-agents agents-list" id="agent-search">
                                <div class="agents-details">
                                    <div class="row">
                                        <?php foreach($paginated_agents as $item): ?>
                                        <div class="col-lg-4 col-md-4 col-sm-6">
                                            <div class="agent">
                                                <div class="agent_img">
                                                        <a href="<?php  _che($item['agent_url']);?>" title="<?php  _che($item['name_surname']);?>">
                                                                <img src="<?php echo _simg($item['image_url'], '640x561', true); ?>" alt="<?php  _che($item['name_surname']);?>">
                                                        </a>
                                                        <div class="view-post">
                                                                <a href="<?php  _che($item['agent_url']);?>" title="<?php  _che($item['name_surname']);?>" class="view-posts"><?php echo lang_check('View Profile');?></a>
                                                        </div>
                                                </div><!--agent-img end-->
                                                <div class="agent_info">
                                                        <h3><a href="<?php  _che($item['agent_url']);?>" title="<?php  _che($item['name_surname']);?>" class=""><?php  _che($item['name_surname']);?></a></h3>
                                                        <span class=""><?php  _che($item['address']);?></span>
                                                        <strong><i class="la la-phone"></i><span class=""><?php  _che($item['phone']);?></span></strong>
                                                </div><!--agent-info end-->
                                                <a href="<?php  _che($item['agent_url']);?>" title="<?php  _che($item['name_surname']);?>" class="ext-link"></a>
                                            </div><!--agent end-->
                                        </div>
                                        <?php endforeach;?>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pagination pagination-centered">
                                                <?php echo $agents_pagination; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--agents-details end-->
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="sidebar layout2">
                                <div class="widget widget-search">
                                    <form action="<?php echo current_url().'#agent-search'; ?>" method="get">
                                        <input type="text" name="search-agent" value="<?php echo $this->input->get('search-agent'); ?>" placeholder="<?php _l('Search');?>">
                                        <button type="submit"><i class="la la-search"></i></button>
                                    </form>
                                </div>
                                <?php _widget('right_categories');?>
                                <?php _widget('right_latest_listings');?>
                                <?php _widget('right_ads');?>
                            </div><!--sidebar end-->
                        </div>
                    </div>
                </div><!--listing-main-sec-details end-->
            </div>    
        </section><!--listing-main-sec end-->
        <?php _subtemplate( 'footers', _ch($subtemplate_footer, 'default')); ?>
    </div><!--wrapper end-->
    <?php _widget('custom_javascript');?>
</body>
</html>