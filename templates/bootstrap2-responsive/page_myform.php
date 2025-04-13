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

<?php _widget('top_ads');?>
<a id="content"></a>
<div class="wrap-content">
    <div class="container">
    
        <h2>{page_title}</h2>
        <div class="property_content">
        <?php _widget('center_defaultcontent');?>
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
        
        <div class="myform">
        
            <form method="post" action="{page_current_url}#form">
              First name:<br>
              <input type="text" name="firstname" value="Mickey"><br>
              Last name:<br>
              <input type="text" name="lastname" value="Mouse"><br><br>
              <input type="submit" value="Submit">
            </form>
        
        </div>
        
        <?php
        
            dump($_POST);
        
            // Functionaly for your form put here, 
            // so all submitted data is available in $_POST
        
        ?>
        
        <style>
            
            /* Your custom CSS style for form */
            
            div.myform
            {
                background: white;
                padding:10px;
            }
        
        </style>
        
    </div>

</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>