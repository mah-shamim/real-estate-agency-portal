<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <title><?php _l('Billing information'); ?></title>
    
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap-responsive.css" type="text/css">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width:500px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading{
        margin-bottom: 15px;
        padding-bottom:10px;
        border-bottom:1px solid #EEE;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 0px;
        padding: 7px 9px;
      }

    </style>
</head>
<body>
    <div class="container">
        <?php echo form_open(NULL, array('class' => 'form-signin form-horizontal', 'role'=>'form'))?>
        <h2 class="form-signin-heading"><?php _l('Billing information'); ?></h2>
        <?php echo validation_errors()?>
        <?php if($this->session->flashdata('message')):?>
        <?php echo $this->session->flashdata('message')?>
        <?php endif;?>
        <?php if($this->session->flashdata('error')):?>
        <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
        <?php endif;?>     
        <div class="control-group">
            <label class="control-label" for="input_company_name"><?php _l('Company name')?></label>
            <div class="controls">
              <input name="company_name" type="text" id="input_company_name" value="<?php echo set_value('company_name', $user->company_name); ?>" placeholder="<?php _l('Company name')?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="input_address"><?php _l('Address')?>*</label>
            <div class="controls">
              <input name="address" type="text" id="input_address" value="<?php echo set_value('address', $user->address); ?>" placeholder="<?php _l('Address')?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="input_zip_city"><?php _l('ZIP / City')?>*</label>
            <div class="controls">
              <input name="zip_city" type="text" id="input_zip_city" value="<?php echo set_value('zip_city', $user->zip_city); ?>" placeholder="<?php _l('ZIP / City')?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="input_mail"><?php _l('Email')?>*</label>
            <div class="controls">
              <input name="mail" type="text" id="input_mail" value="<?php echo set_value('mail', $user->mail); ?>" placeholder="<?php _l('Email')?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="input_prename_name"><?php _l('Prename Name')?>*</label>
            <div class="controls">
              <input name="prename_name" type="text" id="input_prename_name" value="<?php echo set_value('prename_name', $user->prename_name); ?>" placeholder="<?php _l('Prename Name')?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="input_vat_number"><?php _l('VAT number')?></label>
            <div class="controls">
              <input name="vat_number" type="text" id="input_vat_number" value="<?php echo set_value('vat_number', $user->vat_number); ?>" placeholder="<?php _l('VAT number')?>" />
            </div>
        </div>
        
        <div class="control-group">
        <div class="controls">
          <button type="submit" class="btn"><?php _l('Show invoice'); ?></button>
        </div>
        </div>
      </form>

    </div> <!-- /container -->
    
    

</body>
</html>