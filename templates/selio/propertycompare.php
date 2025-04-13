
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
                        <div class="treefield_sitemap">
                            <?php if(isset($property_compare['address'])&&sw_count($property_compare['address'])>0):?>
                            <table class="table table-bordered  table-hover table-compare">
                                <thead>
                                    <th></th>
                                    <?php $i=1; foreach ($property_compare['url']['values'] as $k => $val):?>
                                    <th>
                                        <a href='<?php _che($val); ?>'><?php echo lang_check('Estate');?>  <?php echo $i;?></a>
                                    </th>
                                    <?php $i++; endforeach; ?>
                                </thead>
                                <tr>
                                    <?php _che($property_compare['address']['tr']);?>
                                </tr>
                                <tr>
                                    <?php _che($property_compare['agent_name']['tr']);?>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo lang_check('Image');?>
                                    </td>
                                    <?php foreach ($property_compare['thumbnail_url']['values'] as $k => $val):?>
                                    <td style="text-align:center">
                                        <img src='<?php echo _simg($val, '150x100')?>'/>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php 
                                // options fetch
                                foreach ($property_compare as $field_key => $values):
                                ?>
                                <?php if(!preg_match('/^option_/', $field_key)) continue;?>
                                <?php if(isset($values['empty'])&&$values['empty']!==false) continue;?>
                                <?php /*video skip*/ if($field_key=='option_12') continue;?>
                                 <tr data-option_id='<?php echo $field_key;?>'>
                                    <?php _che($values['tr']);?>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td>
                                    </td>
                                    <?php foreach ($property_compare['url']['values'] as $k => $val):?>
                                    <td class="text-center">
                                        <a class="btn btn2 btn-inline center btn-info" href='<?php _che($val); ?>'> <?php echo lang_check('open property');?></a>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                            </table>
                            <?php endif;?>
                        </div>
                        <div class="post-line">
                        </div><!--post-share end-->
                    </div>
                </div>
            </section>
        
            <?php _subtemplate('footers', _ch($subtemplate_footer, 'default')); ?>
    </div><!--wrapper end-->
    <?php _widget('custom_javascript'); ?>
</body>

</html>