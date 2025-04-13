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
                                <?php if(!empty($page_body)):?>
                                <div class="blog-single-post single">
                                    <h3>{page_title}</h3>
                                    {page_body}
                                    <?php _widget('center_imagegallery');?>
                                    {has_page_documents}
                                    <h2>{lang_Filerepository}</h2>
                                    <ul>
                                    {page_documents}
                                    <li>
                                        <a href="{url}">{filename}</a>
                                    </li>
                                    {/page_documents}
                                    </ul>
                                    {/has_page_documents}
                                    <div class="post-line">
                                    </div><!--post-share end-->
                                </div>
                                <?php endif;?>
                                <?php if(file_exists(APPPATH.'controllers/admin/expert.php')):?>
                                <div class="faqs-sec page-content">
                                    <div class="maison-accordion toggle">
                                        <div class="accordion">
                                            <?php foreach($expert_module_all as $key=>$row):?>
                                            <div class="toggle-item <?php if($key==0):?> activate<?php endif;?>">
                                                <h2 class="title <?php if($key==0):?>active<?php endif;?>"><?php echo $row->question; ?>?</h2>
                                                <div class="content" style="<?php if($key==0):?>display: block;<?php endif;?>">
                                                    <p class="description"><?php echo $row->answer; ?></p>
                                                </div>
                                            </div><!-- Toggle Item -->
                                            <?php endforeach; ?>
                                        </div>
                                    </div><!-- Provide Accordion -->
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="col-lg-4">
                                <div class="sidebar layout2">
                                    <?php _widget('right_askexpert');?>
                                    <?php _widget('right_categories');?>
                                    <?php _widget('right_latest_listings');?>
                                    <?php _widget('right_ads');?>
                                </div><!--sidebar end-->
                            </div>
                        </div>
                    </div><!--listing-main-sec-details end-->
                </div>    
            </section><!--listing-main-sec end-->
            <?php _widget('top_discover_banner_html');?>
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'default')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
    </body>
</html>

