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
                                <div class="blog-posts property_content_position">
                                    <?php foreach ($news_articles as $key => $row): ?>
                                    <div class="blog-single-post selio-cover post-308 post type-post status-publish format-standard has-post-thumbnail hentry category-lifestyle tag-business tag-construction tag-real-estate">
                                        <div class="blog-img-cover">
                                            <div class="blog-img">
                                                <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title="<?php echo $row->title; ?>">
                                                    <?php if (file_exists(FCPATH . 'files/thumbnail/' . $row->image_filename)): ?>
                                                        <img src="<?php echo _simg('files/' . $row->image_filename, '768x481', TRUE); ?>" alt="<?php _che($row->title); ?>" />
                                                    <?php else: ?>
                                                        <img src="assets/img/<?php echo (sw_is_safari()) ? 'no_image.webp.jpg' : 'no_image.webp.webp';?>" alt="new-image">
                                                    <?php endif; ?>
                                                <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title="<?php echo $row->title; ?>" class="hover"></a>
                                            </div><!--blog-img end-->
                                        </div><!--blog-img end-->
                                        <div class="post_info">
                                            <ul class="post-nfo">
                                                <li><i class="la la-calendar"></i>
                                                    <?php
                                                    $timestamp = strtotime($row->date_publish);
                                                    $m = strtolower(date("F", $timestamp));
                                                    echo lang_check('cal_' . $m) . ' ' . date("j, Y", $timestamp);
                                                    ?>
                                                </li>
                                                <?php foreach (explode(',', $row->keywords) as $val): ?>
                                                <li>
                                                    <a href="#"><i class="la la-bookmark-o"></i><?php echo trim($val); ?> </a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <h3>
                                                <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title="<?php echo $row->title; ?>"><?php echo $row->title; ?></a>
                                            </h3>
                                            <div class="post-content clearfix">
                                                <p></p>
                                                <?php echo $row->description; ?>
                                                <p></p>
                                            </div>
                                            <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title="<?php echo lang_check('Read more');?>" class="btn-default"><?php echo lang_check('Read more');?></a>
                                        </div>
                                    </div><!--blog-single-post end-->   
                                    <?php endforeach;?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="sidebar layout2">
                                    <?php _widget('right_categories');?>
                                    <?php _widget('right_latest_listings');?>
                                    <?php _widget('right_ads');?>
                                </div><!--sidebar end-->
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