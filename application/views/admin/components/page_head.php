<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title><?php echo lang('app_name')?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- Stylesheets -->
  <link href="<?php echo base_url('admin-assets/style/bootstrap.css')?>" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('admin-assets/style/font-awesome.css')?>">
  <link href="<?php echo base_url('admin-assets/style/style.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('admin-assets/style/custom.css')?>" rel="stylesheet">
  
  <!-- HTML5 Support for IE -->
  <!--[if lt IE 9]>
  <script src="<?php echo base_url('admin-assets/js/html5shim.js')?>"></script>
  <![endif]-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo _ch($website_admin_favicon_url, ''); ?>">
  
  

<?php if($is_rtl):?>
<link href="<?php echo base_url('admin-assets/style/style_rtl.css')?>" rel="stylesheet">
<?php endif;?>

<?php if(file_exists(FCPATH.'/templates/'.$settings['template'].'/assets/css/admin_template.css')):?>
<link href="<?php echo base_url('templates/'.$settings['template'].'/assets/css/admin_template.css')?>" rel="stylesheet">
<?php endif;?>
</head>