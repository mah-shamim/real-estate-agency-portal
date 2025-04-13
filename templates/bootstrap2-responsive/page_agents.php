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
    <div class="container container-property">
        <div class="row-fluid">
            <div class="span9">
                <h2>{page_title}</h2>
                <div class="property_content">
                <div class="page_content">{page_body}</div>
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
            </div>
            <div class="span3">
                <?php _widget('right_agents'); ?>  
            </div>
        </div>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>