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
<?php _subtemplate('headers', _ch($subtemplate_header, 'slider_and_compact')); ?>
<a id="content"></a>
<div class="wrap-content">
<div class="container">
    <div class="row-fluid">
        <div class="span9">
            <?php _widget('center_recentproperties');?>
        </div>
        <div class="span3">
        <?php _widget('right_customfiltervisual'); ?>  
        <?php _widget('right_mortgage'); ?>  
        <?php _widget('right_adssmall'); ?>  
        <?php _widget('right_agents'); ?>  
        </div>
    </div>
</div>
</div>
<?php _widget('bottom_defaultcontent');?>
<?php _widget('bottom_partners');?>

    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>
<?php _widget('custom_javascript');?> 
  </body>
</html>