<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Invoice')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($invoice->id_invoice) ? lang('Add Invoice') : lang('Edit Invoice').' "' . $invoice->id_invoice.'"'?></span>
        </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/monetize/payments')?>"><?php echo lang_check('Monetize')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/monetize/invoices')?>"><?php echo lang_check('Invoices')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Invoice data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Paid')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox($f_name = 'is_paid', '1', set_value($f_name, $invoice->$f_name), 'id="input_'.$f_name.'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Activated')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox($f_name = 'is_activated', '1', set_value($f_name, $invoice->$f_name), 'id="input_'.$f_name.'"')?>
                                  </div>
                                </div>
                                
                                <hr />
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-success"')?>
                                    <a href="<?php echo site_url('admin/monetize/invoices')?>" class="btn btn-primary" type="button"><?php echo lang('Cancel')?></a>
                                    <?php
                                        $data_json = json_decode($invoice->data_json);
                                        $user = $data_json->user;
                                        if(!empty($data_json->user->mail))
                                        {
                                            ?>
                                            <a href="mailto:<?php echo $data_json->user->mail?>?subject=<?php echo lang_check('Reply on invoice')?>: <?php echo $invoice->invoice_num; ?>&amp;body=<?php echo $invoice->description?>" class="btn btn-default" target="_blank"><?php echo lang_check('Reply to email')?></a>
                                            <?php 
                                        }
                                    ?>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>
          </div>
          
          <div class="row">

            <div class="col-md-12">


              <div class="widget worange">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Invoice details')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
<div class="container">
      <div class="row">
        <div class="col-sm-6">

        </div>
        <div class="col-sm-6 text-right">
          <h1><?php _l('Invoice'); ?></h1>
          <h1><small><?php _l('Invoice'); ?> <?php echo $invoice->invoice_num; ?></small></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
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
        <div class="col-sm-5 col-sm-offset-2 text-right">
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
      <table class="table table-bordered" style="border-top: 1px solid #DDD;border-left: 1px solid #DDD;">
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
            <td><?php echo  _ch($invoice->reference_code, ''). _ch($invoice->reference_id,''); ?></td>
            <td><?php echo  $invoice->description; ?></td>
            <td class="text-right">1</td>
            <td class="text-right"><?php echo  $invoice->price.' '. $invoice->currency_code; ?></td>
            <td class="text-right"><?php echo  $invoice->price.' '. $invoice->currency_code; ?></td>
          </tr>
        </tbody>
      </table>
      <div class="row text-right">
        <div class="col-sm-2 col-sm-offset-8">
          <p>
            <strong>
            <?php _l('Total'); ?>: <br>
            </strong>
          </p>
        </div>
        <div class="col-sm-2">
          <strong>
          <?php echo $invoice->price.' '.$invoice->currency_code; ?>&nbsp;&nbsp;<br />
          </strong>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h4><?php _l('Bank payment details'); ?></h4>
            </div>
            <div class="panel-body">
              <?php echo $settings['withdrawal_details']; ?>
            </div>
          </div>
        </div>
        <div class="col-sm-7">

        </div>
      </div>
    </div>
    
    <style>
        .table td.text-right{
            text-align: right;
        }
    </style>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>
          </div>
          
        </div>
        </div>