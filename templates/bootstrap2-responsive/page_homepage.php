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

<?php _subtemplate('headers', _ch($subtemplate_header, 'map_and_search')); ?>

<?php _widget('top_ads');?>
<a id="content"></a>
<div class="wrap-content">
    <div class="container">
    <div class="row-fluid">
        <div class="span12">
            <?php _widget('center_recentproperties');?>
        </div>
    </div>
    </div>
</div>


<?php _widget('bottom_recentnews');?>

<?php _widget('bottom_defaultcontent');?>

<?php _widget('bottom_partners');?>

<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 
    
  </body>
</html>