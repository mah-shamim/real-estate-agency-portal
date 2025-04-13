<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
 
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo lang_check('configurator')?></title>
	<!-- Bootstrap -->
	<link href="<?php echo base_url('configurator-assets/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('configurator-assets/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('configurator-assets/css/styles.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('configurator-assets/css/admin.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('configurator-assets/js/jquery-1.9.1.js'); ?>"></script>
	<script src="<?php echo base_url('configurator-assets/js/bootstrap.js'); ?>"></script>
</head>
<body style="background: #555; padding-top:20px; margin:auto;">

    <div class="modal" role="dialog" style="top: 20px; position:absolute;">
<div class="modal-header">
    <h3><?php echo lang('configurator')?></h3>
</div>

<div class="">
<div class="alert alert-error"><?php echo lang('alredy_installed')?></div>
<div style="padding:  10px;">
<p>
<?php echo lang('configurator_info')?>
</p>

    <div>
    <p>
        <a href="http://codecanyon.net/downloads" target="_blank"><img src="<?php echo base_url('configurator-assets/img/rating-we_zps2cd7af1c.png'); ?>" /></a>
        <strong>Thanks you for buying my application!</strong><br />
        If you enjoy this application please rate &amp; share it, this will <b>motivate me for more and more updates!</b> If you are rating it with less than 5 stars 
        please drop me a mail why it didn't achieve a full score and what could be improved in your opinion.<br />
        <a href="http://codecanyon.net/downloads" target="_blank">Link to application rating page</a>
    </p>
    </div>

</div>

</div>
</div>
</body>
</html>