 <?php
$CI = &get_instance();
$CI->load->model('page_m');
$categories = $CI->page_m->get_no_parents_news_category($lang_id);
?>
<?php foreach($news_module_all as $key=>$row):?>
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
                <a href="#" class="no_click"><i class="la la-bookmark-o"></i><?php echo trim($val); ?> </a>
            </li>
            <?php endforeach; ?>
            <?php if(_ch($categories[$row->parent_id], 'false')):?>
            <li>
                <a href="<?php echo slug_url($lang_code.'/142');?>?cat=<?php echo $row->parent_id; ?>" class="label label-danger"><i class="la la-bookmark-o"></i><?php echo $categories[$row->parent_id]?></a>
            </li> 
            <?php endif;?>
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
<nav class="text-center">
    <div class="pagination news">
        <?php echo $news_pagination; ?>
    </div>
</nav>