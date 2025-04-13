<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Sample Invoice</title>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap-responsive.css" type="text/css">
    <style>
        .table td.text-right{
            text-align: right;
        }
    </style>
  </head>
  
  <body>
    <div class="container">
      <div class="row">
        <div class="span6">
          <h1>
            <img src="assets/img/logo.png"  alt="logo"/>
          </h1>
        </div>
        <div class="span6 text-right">
          <h1><?php _l('Invoice'); ?></h1>
          <h1><small><?php _l('Invoice'); ?> <?php echo $invoice_num; ?></small></h1>
        </div>
      </div>
      <div class="row">
        <div class="span5">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4><?php _l('From'); ?>:</h4>
            </div>
            <div class="panel-body">
              <p>
                <?php echo $settings['address_footer']; ?> <br>
              </p>
            </div>
          </div>
        </div>
        <div class="span5 offset2 text-right">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4><?php _l('To'); ?>:</h4>
            </div>
            <div class="panel-body">
              <p>
                <strong><?php echo $user->company_name; ?></strong><br>
                <?php echo $user->address; ?> <br>
                <?php _l('ZIP / City')?>: <?php echo $user->zip_city; ?> <br>
                <?php _l('VAT number')?>: <?php echo $user->vat_number; ?> <br>
              </p>
            </div>
          </div>
        </div>
      </div>
      <!-- / end client details section -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>
              <h4><?php _l('Item'); ?></h4>
            </th>
            <th>
              <h4><?php _l('Description'); ?></h4>
            </th>
            <th>
              <h4><?php _l('Qty'); ?></h4>
            </th>
            <th>
              <h4><?php _l('Price'); ?></h4>
            </th>
            <th>
              <h4><?php _l('Sub total'); ?></h4>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $reference_code.$reference_id; ?></td>
            <td><?php echo $description; ?></td>
            <td class="text-right">1</td>
            <td class="text-right"><?php echo $price.' '.$currency_code; ?></td>
            <td class="text-right"><?php echo $price.' '.$currency_code; ?></td>
          </tr>
        </tbody>
      </table>
      <div class="row text-right">
        <div class="span2 offset8">
          <p>
            <strong>
            <?php _l('Total'); ?>: <br>
            </strong>
          </p>
        </div>
        <div class="span2">
          <strong>
          <?php echo $price.' '.$currency_code; ?> <br>
          </strong>
        </div>
      </div>
      <div class="row">
        <div class="span5">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h4><?php _l('Bank payment details'); ?></h4>
            </div>
            <div class="panel-body">
              <?php echo $settings['withdrawal_details']; ?>
            </div>
          </div>
        </div>
        <div class="span7 hidden">
          <div class="span7">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h4>Contact Details</h4>
              </div>
              <div class="panel-body">
                <p>
                  Email : you@example.com <br><br>
                  Mobile : -------- <br> <br>
                  Twitter : <a href="https://twitter.com/tahirtaous">@TahirTaous</a>
                </p>
                <h4>Payment should be made by Bank Transfer</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    <div class="row">
        <div class="span12">
            <p style="text-align: center;">
                <!--<a class="btn btn-large btn-info" href="#" ><i class="icon-print icon-white"></i> <?php _l('Print invoice'); ?></a>-->
                <a class="btn btn-large btn-success" href="?confirmed=true" ><i class="icon-shopping-cart icon-white"></i> <?php _l('Confirm purchase'); ?></a>
                <a class="btn btn-large btn-danger" href="<?php echo site_url('frontend/myproperties/'.$lang_code); ?>" ><i class="icon-remove icon-white"></i> <?php _l('Cancel purchase'); ?></a>
            </p>
        </div>
    </div>
      
    </div>
  </body>
</html>