<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <script>
    $(document).ready(function(){

    });    
    </script>
  </head>

  <body>
  
{template_header}

<?php _subtemplate('headers', _ch($subtemplate_header, 'empty')); ?>


<a id="content"></a>
<div class="wrap-content">
    <div class="container">
    
        <h2>{page_title}</h2>
        <!-- ARTICLES -->
        <div class="property_content_position">
        <div class="row-fluid">
        <ul class="thumbnails">
            <?php foreach($news_articles as $key=>$row):?>
              <li class="span12 li-list">
                <div class="thumbnail span4">
                <?php if(isset($row->image_filename)):?>
                  <img alt="300x200" data-src="holder.js/300x200" style="width: 300px; height: 200px;" src="<?php echo _simg('files/'.$row->image_filename, '600x400'); ?>" />
                <?php else:?>
                  <img alt="300x200" data-src="holder.js/300x200" style="width: 300px; height: 200px;" src="assets/img/no_image.jpg" />
                <?php endif;?>
                  <a href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>" class="over-image"> </a>
                </div>
                  <div class="caption span8">
                    <p class="bottom-border"><strong><?php echo $row->title; ?></strong></p>
                    <p class="prop-description"><?php echo $row->description; ?></p>
                    <p>
                    <a class="btn btn-info" href="<?php echo site_url($lang_code.'/'.$row->id.'/'.url_title_cro($row->title)); ?>">
                    {lang_Details}
                    </a>
                    </p>
                  </div>
              </li>
            <?php endforeach;?>
            </ul>
        </div>
        </div>
        <!-- /ARTICLES -->
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>