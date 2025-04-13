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
  
<div class="always-top">
<?php $c_code = config_item('codecanyon_code'); if(empty($c_code)): ?>
<div class="top-wrapper">
      <div class="container">
            Please insert:
            <pre>$config['codecanyon_username'] = 'your_codecanyon_username';<br />$config['codecanyon_code'] = 'your_purchase_code';</pre>
            Into your application/config/cms_config.php to remove this message.<br />
            <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-"><em>Where to find purchase code?</em></a>
            <br /><br />
      </div> <!-- /.container -->
</div>
<?php endif; ?>

<?php if(config_item('cookie_warning_enabled') === TRUE): ?>

<script>
    
function displayNotification(c_action) {

    // this sets the page background to semi-transparent black should work with all browsers
    var message = "<div id='cookiewarning' >";

    // center vert
    message = message + "<div style='text-align:center;margin:0px;padding:10px;width:auto;background:white;color:black;font-size:90%;'>";

    // this is the message displayed to the user.
    message = message + "<?php _l('cookie_warning_message'); ?>";

    // Displays the I agree/disagree buttons.
    // Feel free to change the address of the I disagree redirection to either a non-cookie site or a Google or the ICO web site 
    message = message + "<br /><INPUT TYPE='button' VALUE='<?php _l('I Agree'); ?>' onClick='JavaScript:doAccept();' /> <INPUT TYPE='button' VALUE=\"<?php _l('I dont agree'); ?>\" onClick='JavaScript:doNotAccept("
	message = message + c_action;
	message = message + ");' />";

    // and this closes everything off.
    message = message + "</div></div>";

    document.writeln(message);
}
</script>


<div class="top-wrapper">
      <div class="container">
            <script src="assets/js/cookiewarning4.js"></script>
      </div> <!-- /.container -->
</div>
<?php endif; ?>

<?php _widget('custom_palette'); ?>
<?php _widget('header_loginmenu'); ?>
<?php _widget('header_mainmenu'); ?>




</div>

<?php _subtemplate('headers', _ch($subtemplate_header, 'empty')); ?>

<a id="content"></a>
<div class="wrap-content">
    <div class="container">
     <div id="main">
            <h2 class="page-header">{page_title}</h2>
            <div class="our-agents-large box-white property-detail property_content">
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
                            <img src='<?php echo _simg($val, '150x100')?>' alt=""/>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <?php 
                    // options fetch
                    foreach ($property_compare as $field_key => $values):
                    ?>
                    <?php if(!preg_match('/^option_/', $field_key)) continue;?>
                    <?php if($values['empty']!==false) continue;?>
                    <?php /*video skip*/ if($field_key=='option_12') continue;?>
                    <tr>
                        <?php _che($values['tr']);?>
                    </tr>
                    <?php endforeach; ?>
                    
                    <tr>
                        <td>
                        </td>
                        <?php foreach ($property_compare['url']['values'] as $k => $val):?>
                        <td>
                            <a class="btn btn-info" href='<?php _che($val); ?>'> <?php echo lang_check('open property');?></a>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </table>
                <?php endif;?>
            </div><!-- /.our-agents -->        
        </div>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>