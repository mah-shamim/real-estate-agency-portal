<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <!-- [START] scale -->
    <link rel="stylesheet"  href="assets/js/nouislider/nouislider.css">
    <script src="assets/js/nouislider/nouislider.js"></script>
    <link rel="stylesheet"  href="assets/js/nouislider/nouislider.pips.css">
    <link rel="stylesheet"  href="assets/js/nouislider/nouislider.tooltips.css">
    <!-- [END] scale -->
  </head>

  <body>
  
{template_header}

<?php _widget('top_mapsearch_scale');?>

<a id="content"></a>
<div class="wrap-content">
    <div class="container">
    
        <h2>{page_title}</h2>
        <div class="property_content">
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
        </div>
        
        <br style="clear:both;" />
        
        
        <h2>{lang_Lastaddedproperties}</h2>
        <div class="row-fluid">
            <?php foreach($last_estates as $key=>$item): ?>
            <?php
               if($key==0)echo '<ul class="">';
            ?>
                <?php _generate_results_item(array('key'=>$key, 'item'=>$item,'icons'=>false, 'view_counter'=>false)); ?>
            <?php
               if( ($key+1)%4==0 )
                {
                    echo '</ul><ul class="">';
                }
                if( ($key+1)==sw_count($last_estates) ) echo '</ul>';
                endforeach;
            ?>
          </div>
        
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

<!-- [START] scale -->
<link rel="stylesheet"  href="assets/js/nouislider/nouislider.css">
<script src="assets/js/nouislider/nouislider.js"></script>
<link rel="stylesheet"  href="assets/js/nouislider/nouislider.pips.css">
<link rel="stylesheet"  href="assets/js/nouislider/nouislider.tooltips.css">
<!-- [END] scale -->

  </body>
</html>