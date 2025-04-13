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