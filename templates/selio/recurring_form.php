<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <title><?php _l('Billing information'); ?></title>
    
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/custom.css" type="text/css">
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

<?php if($allow_create_subscription): ?>
    <script
        src="https://www.paypal.com/sdk/js?client-id=<?php echo config_db_item('recurring_client_id'); ?>&vault=true&intent=subscription">
    </script>
    <div class="container">
        <h2 class="form-signin-heading"><?php _l('Billing information'); ?></h2>
        <p>
        <?php _l('Please complete subscription payment via button'); ?>
        </p>
        <p>
        <?php _l('Package will renew automatically every month'); ?>
        </p>
        <?php if($user->type == 'AGENT'): ?>
            <p><a class="button btn btn-info" href="<?php echo site_url('admin/estate'); ?>"><?php _l('Back to my properties'); ?></a></p>
        <?php else: ?>
            <p><a class="button btn btn-info" href="<?php echo site_url('frontend/myproperties'); ?>"><?php _l('Back to my properties'); ?></a></p>
        <?php endif; ?>
        <br>
        <div class="control-group">
        <div class="controls">
        <div id="paypal-button-container"></div>
        </div>
        </div>

    </div> <!-- /container -->

    <script>
        paypal.Buttons({
            createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': '<?php echo $plan_id; ?>'
                });
            },
            onApprove: function(data, actions) {
                console.log('You have successfully created subscription ' + data.subscriptionID);
                <?php if($user->type == 'AGENT'): ?>
                    window.location = '<?php echo site_url('admin/estate'); ?>?subcription_id_created='+data.subscriptionID;
                <?php else: ?>
                    window.location = '<?php echo site_url('frontend/myproperties'); ?>?subcription_id_created='+data.subscriptionID;
                <?php endif; ?>
            }
        }).render('#paypal-button-container');
    </script>
<?php else: ?>

    <div class="container">
        <h2 class="form-signin-heading"><?php _l('Subscription information'); ?></h2>
        <p>
        <?php _l('You have already active subscription and because of that not possible to create new one'); ?>
        </p>
        <p>
        <?php _l('But you can cancel subscription here:'); ?>
        </p>
        <?php if($user->type == 'AGENT'): ?>
            <p><a class="button btn btn-danger" href="<?php echo site_url('paypalrest/cancel_subscription/'.$subscription_id); ?>"><?php _l('Cancel subscription'); ?></a> <a class="button btn btn-info" href="<?php echo site_url('admin/estate'); ?>"><?php _l('Back to my properties'); ?></a></p>
        <?php else: ?>
            <p><a class="button btn btn-danger" href="<?php echo site_url('paypalrest/cancel_subscription/'.$subscription_id); ?>"><?php _l('Cancel subscription'); ?></a> <a class="button btn btn-info" href="<?php echo site_url('frontend/myproperties'); ?>"><?php _l('Back to my properties'); ?></a></p>
        <?php endif; ?>
        
    </div> <!-- /container -->

<?php endif; ?>
</body>
</html>