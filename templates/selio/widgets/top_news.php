<?php
/*
Widget-title: Top News
Widget-preview-image: /assets/img/widgets_preview/top_news.webp
 */

?>
<?php if(isset($news_module_latest_5) && !empty($news_module_latest_5)):?>
<section class="blog-grid hp6 section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-heading">
                    <span><?php echo lang_check('Our Blog');?></span>
                    <h3><?php echo lang_check('Recent Posts');?></h3>
                </div>
            </div>
        </div>
        <div class="blog-grid-posts mg">
            <div class="row WdkScrollMobileSwipe_enable">
                <?php $i=0; foreach($news_module_latest_5 as $key=>$row):?> 
                <?php if ($i>2) break; ?>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="blog-single-post">
                        <div class="blog-img">
                            <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title="<?php echo $row->title; ?>">
                                <img src="<?php echo _simg('files/'.$row->image_filename, '810x465'); ?>" alt="">
                            </a>
                            <div class="view-post">
                                <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title="<?php echo $row->title; ?>" class="view-posts"><?php echo lang_check('View Post');?></a>
                            </div>
                        </div><!--blog-img end-->
                        <div class="post_info">
                            <ul class="post-nfo">
                                <li><i class="la la-calendar"></i><?php echo date("M d, Y", strtotime($row->date_publish));?></li>
                            </ul>
                            <h3><a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title=""><?php echo $row->title; ?></a></h3>
                            <p><?php echo $row->description; ?></p>
                            <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title=""><?php echo lang_check('Read More');?> <i class="la la-long-arrow-right"></i></a>
                        </div>
                        <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" title="" class="ext-link"></a>
                    </div><!--blog-single-post end--> 
                </div>
                <?php $i++; endforeach;?>
            </div>
        </div><!--blog-grid-posts end-->
    </div>
</section>
<?php endif;?>