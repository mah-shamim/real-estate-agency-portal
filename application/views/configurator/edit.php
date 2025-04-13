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
    <h3><?php echo lang_check('configurator')?></h3>
</div>

<div class="">
    
<?php if(!function_exists('curl_version') || !function_exists('curl_exec')):?>
    <div class="alert alert-danger"><?php echo lang_check('CURL is required for some script functionalities, please enable')?></div>
<?php endif;?>

<div class="alert alert-info"><?php echo lang_check('start_configuration')?></div>

<?php if(isset($warning_sqlite)):?>
<div class="alert alert-warning"><?php echo $warning_sqlite?></div>
<?php endif;?>
<?php if(isset($warning_php)):?>
<div class="alert alert-danger"><?php echo $warning_php?></div>
<?php endif;?>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>
<div class="form-horizontal">
    <div class="control-group success">
        <label class="control-label" for="app_type"><?php echo lang_check('app_type')?></label>
        <div class="controls">
            <?php echo form_dropdown('app_type', $l_type_options, 'cms');?>
        </div>
    </div>

    <div class="control-group warning">
        <label class="control-label" for="your_email"><?php echo lang_check('Your email')?></label>
        <div class="controls">
            <?php echo form_input('your_email', set_value('your_email', ''))?>
        </div>
    </div>
    
    <div class="control-group info">
        <label class="control-label" for="admin_username"><?php echo lang_check('admin_username')?></label>
        <div class="controls">
            <?php echo form_input('admin_username', set_value('admin_username', 'admin'))?>
        </div>
    </div>
    <div class="control-group info">
        <label class="control-label" for="admin_password"><?php echo lang_check('admin_password')?></label>
        <div class="controls">
            <?php echo form_input('admin_password', set_value('admin_password', substr(md5(time()+rand(0,1000)),0,5)))?>
        </div>
    </div>
    
    <div class="control-group info">
        <label class="control-label" for="agent_username"><?php echo lang_check('agent_username')?></label>
        <div class="controls">
            <?php echo form_input('agent_username', set_value('agent_username', 'agent'))?>
        </div>
    </div>
    <div class="control-group info">
        <label class="control-label" for="agent_password"><?php echo lang_check('agent_password')?></label>
        <div class="controls">
            <?php echo form_input('agent_password', set_value('agent_password', substr(md5(time()+rand(0,1000)),0,5)))?>
        </div>
    </div>
    
    
    
    <div class="control-group error">
        <label class="control-label" for="mysql_db_name"><?php echo lang_check('mysql_db_name')?></label>
        <div class="controls">
            <?php echo form_input('mysql_db_name', set_value('mysql_db_name', ''))?>
        </div>
    </div>
    <div class="control-group error">
        <label class="control-label" for="mysql_db_host"><?php echo lang_check('mysql_db_host')?></label>
        <div class="controls">
            <?php echo form_input('mysql_db_host', set_value('mysql_db_host', 'localhost'))?>
        </div>
    </div>
    <div class="control-group error">
        <label class="control-label" for="mysql_db_port"><?php echo lang_check('mysql_db_port')?></label>
        <div class="controls">
            <?php echo form_input('mysql_db_port', set_value('mysql_db_port', '3306'))?>
        </div>
    </div>
    <div class="control-group error">
        <label class="control-label" for="mysql_db_driver"><?php echo lang_check('mysql_db_driver')?></label>
        <div class="controls">
            <?php echo form_dropdown('mysql_db_driver', $l_driver_options, 'mysqli');?>
        </div>
    </div>
    <div class="control-group error">
        <label class="control-label" for="db_username"><?php echo lang_check('db_username')?></label>
        <div class="controls">
            <?php echo form_input('db_username', set_value('db_username', ''))?>
        </div>
    </div>
    <div class="control-group error">
        <label class="control-label" for="db_password"><?php echo lang_check('db_password')?></label>
        <div class="controls">
            <?php echo form_input('db_password', set_value('db_password', ''))?>
        </div>
    </div>
    <div class="control-group warning">
        <label class="control-label" for="codecanyon_username"><?php echo lang_check('codecanyon_username')?></label>
        <div class="controls">
            <?php echo form_input('codecanyon_username', set_value('codecanyon_username', ''))?>
        </div>
    </div>
    <div class="control-group warning">
        <label class="control-label" for="codecanyon_code"><?php echo lang_check('codecanyon_code')?></label>
        <div class="controls">
            <?php echo form_input('codecanyon_code', set_value('codecanyon_code', ''))?> 
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <?php echo form_submit('submit', lang_check('save'), 'class="btn btn-primary"')?>
        </div>
    </div>
</div>
<?php echo form_close();?>
</div>
</div>
</body>
</html>