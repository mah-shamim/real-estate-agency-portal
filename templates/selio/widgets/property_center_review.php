<?php if (file_exists(APPPATH . 'controllers/admin/swin_reviews.php') && $settings_reviews_enabled):;?>
<div class="comments-dv widget-reviews" id="form_review">
    <h3><?php echo lang_check('Reviews');?></h3>
    <div class="comment-section">
            <?php if($settings_reviews_public_visible_enabled): ?>
                <?php if(sw_count($not_logged) && !$settings_reviews_public_visible_enabled): ?>
                <div class="content-box">
                <p class="alert alert-success">
                    <?php echo lang_check('LoginToReadReviews'); ?>
                </p>
                </div>
                <?php else: ?>
                    <?php if(sw_count($reviews_all) > 0): ?>
                        <ul class="list-reviews">
                        <?php foreach($reviews_all as $review_data): ?>
                            <li>
                                <div class="cm-info-sec">
                                    <div class="cm-img">
                                        <div class="user-logo">
                                            <?php if(isset($review_data['image_user_filename'])): ?>
                                                <img src="<?php echo _simg('files/thumbnail/'.$review_data['image_user_filename'], '70x70', true);?>" alt="<?php echo $review_data['name_surname']; ?>" />
                                            <?php else: ?>
                                                <img src="assets/img/user-agent.png" alt="<?php echo $review_data['name_surname']; ?>" />
                                            <?php endif; ?>
                                        </div>
                                    </div><!--author-img end-->
                                    <div class="cm-info">
                                        <h3><?php echo $review_data['name_surname']; ?></h3>
                                        <h4>
                                            <?php
                                            $timestamp = strtotime($review_data['date_publish']);
                                            $m = strtolower(date("F", $timestamp));
                                            echo lang_check('cal_' . $m) . ' ' . date("j, Y", $timestamp);
                                            ?>
                                        </h4>
                                    </div>
                                    <ul class="rating-lst">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review_data['stars']): ?>
                                                <li><span class="la la-star"></span></li>
                                            <?php else: ?>
                                                <li><span class="la la-star innactive"></span></li>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </ul><!--rating-lst end-->
                                </div><!--cm-info-sec end-->
                                <p>
                                    <?php if($review_data['is_visible']): ?>
                                        <?php echo $review_data['message']; ?>
                                    <?php else: ?>
                                        <?php echo lang_check('HiddenByAdmin'); ?>
                                    <?php endif; ?>
                                </p>
                                <?php if($reviews_submitted == 0): ?><a href="#form_review_reply" title="<?php echo lang_check('Reply');?>" class="cm-reply  <?php if(sw_count($not_logged)>0):?>login_popup_enabled<?php endif;?>"><?php echo lang_check('Reply');?></a><?php endif;?>
                            </li>
            
                        <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <div class="content-box">
                            <p class="alert alert-success">
                                <?php echo lang_check('SubmitFirstReview'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
    </div>
    
    <div class="review-hd">
        <?php if($reviews_submitted == 0): ?>
        <div class="rev-hd clearfix">
        <h3><?php echo lang_check('Write a Review');?></h3>
            <div class="form-group-rating rating-lst <?php if(sw_count($not_logged)>0):?>login_popup_enabled<?php endif;?>">
                <input type="radio" name="stars" value=""  class="hidden" checked="checked" />
                <fieldset class="rating-action rating" required>
                    <input type="radio" id="star1" name="stars" value="5" />
                    <label class="full" for="star1" title="<?php echo lang_check('Awesome - 5 stars');?>"></label>
                    <input type="radio" id="star2" name="stars" value="4" />
                    <label class="full" for="star2" title="<?php echo lang_check('Pretty good - 4 stars');?>"></label>
                    <input type="radio" id="star3" name="stars" value="3" />
                    <label class="full" for="star3" title="<?php echo lang_check('Meh - 3 stars');?>"></label>
                    <input type="radio" id="star4" name="stars" value="2" />
                    <label class="full" for="star4" title="<?php echo lang_check('Kinda bad - 2 stars');?>"></label>
                    <input type="radio" id="star5" name="stars" value="1" />
                    <label class="full" for="star5" title="<?php echo lang_check('Very bad - 1 star');?>"></label>
                </fieldset>
            </div>
        </div><!--rev-hd end-->
        <?php endif;?>
        <?php if(sw_count($not_logged)): ?>
        <p class="alert alert-success">
            <?php echo lang_check('Please');?> 
            <a href="{front_login_url}#content" class="login_popup_enabled"><?php echo lang_check('login');?></a> 
            <?php echo lang_check('or');?> 
            <a href="{front_login_url}#content"><?php echo lang_check('register');?></a> 
            <?php echo lang_check('to write your review');?>
        </p>
        <?php else: ?>
            <?php if($reviews_submitted == 0): ?>
             <div class="post-comment-sec">
                <form action="{page_current_url}#form_review" method="post" id="form_review_reply" class="review-form">
                    <?php if(isset($reviews_validation_errors) && !empty($reviews_validation_errors)):?>
                    <?php echo $reviews_validation_errors;?>
                    <?php endif;?>
                    <input type="text" name="stars" value="" id="review_star_input"  class="hidden" />
                    <div class="form-field">
                        <textarea name="message" class="" rows="5" placeholder="<?php echo lang_check('Help other to choose perfect place');?>"></textarea>
                    </div>
                    <div class="cliearfix">
                        <button type="submit" class="btn-default"><?php echo lang_check('Submit Review');?></button>
                    </div>
                </form>
            </div>  
            <?php else: ?>
            <div class="clearfix"></div>
            <p class="alert alert-info">
                <?php echo lang_check('ThanksOnReview'); ?>
            </p>
            <?php endif; ?>
        <?php endif; ?>
    </div><!--review-hd end-->
</div><!--comments-dv end-->
<?php endif;?>