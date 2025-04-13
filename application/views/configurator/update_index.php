<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo lang_check('System check and updater')?></title>
	<!-- Bootstrap -->
	<link href="<?php echo base_url('configurator-assets/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('configurator-assets/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('configurator-assets/css/styles.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('configurator-assets/css/admin.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('configurator-assets/js/jquery-1.9.1.js'); ?>"></script>
	<script src="<?php echo base_url('configurator-assets/js/bootstrap.js'); ?>"></script>
    
    <style>
        li a.disabled{
            cursor:default;
        }
    </style>
    <script language="javascript">
        $( document ).ready(function() {
            $("a").click(function() {
                $('a').addClass('disabled'); // Disables visually
                $('a').on('click', function(event) {
                  event.preventDefault(); // To prevent following the link (optional)
                });
            });
        });
    </script>
    
</head>
<body style="background: #555; padding-top:20px; margin:auto;">

<div class="modal" role="dialog" style="top: 20px; position:absolute;">
    <div class="modal-header">
        <h3><?php echo lang_check('System check and updater')?></h3>
    </div>
    <div>
        <?php if(!empty($custom_errors)): ?>
        <div class="alert alert-error"><?php echo $custom_errors; ?></div>
        <?php endif; ?>
        <?php if(defined('APP_VERSION_REAL_ESTATE')): ?>
        <div class="alert alert-warning"><?php echo lang_check('Script version in index.php: ').APP_VERSION_REAL_ESTATE; ?></div>
        <?php endif; ?>
        <?php if(!empty($script_version_db)): ?>
        <div class="alert alert-info"><?php echo lang_check('Script version in database: ').$script_version_db; ?></div>
        <?php endif; ?>
        
        <?php if(!empty($update_output)): ?>
        <div class="alert alert-error"><?php echo $update_output; ?></div>
        <?php endif; ?>
    </div>
    
    <div class="modal-header">
        <h3>Actions</h3>
    </div>
    <div style="padding: 10px 15px;">
        <ul class="nav nav-tabs nav-stacked">
            <?php 
            $CI =& get_instance(); 
            $CI->load->library('session');
            if($script_version_db != APP_VERSION_REAL_ESTATE || $CI->session->userdata('type') == 'ADMIN'): ?>
            <li><a href="<?php echo site_url('updater/index/backup_sql'); ?>"><?php echo lang_check('Backup database'); ?></a></li>
            <li><a href="<?php echo site_url('updater/index/backup_files'); ?>"><?php echo lang_check('Backup files'); ?></a></li>
            <?php else: ?>
            <li><span class="label label-info"><?php echo lang_check('Login or upload files to enable actions'); ?></span></li>
            <?php endif; ?>
            
            <?php if(!empty($update_to_version)): ?>
            <li><a onclick="return confirm('<?php echo lang_check('Did you test if your backup works?'); ?>')" href="<?php echo site_url('updater/index/'.str_replace('.','',$update_to_version)); ?>"><?php echo lang_check('Update to version: ').$update_to_version; ?></a></li>
            <?php elseif(!empty($update_output) && $script_version_db != APP_VERSION_REAL_ESTATE): ?>
            <li><a href="<?php echo site_url('updater'); ?>"><?php echo lang_check('Run updater again for next version'); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

</div>
</body>
</html>