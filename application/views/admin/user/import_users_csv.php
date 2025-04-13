<!-- Page heading -->
<div class="page-head">
<!-- Page heading -->
    <h2 class="pull-left"><?php echo lang('Import estates')?>
              <!-- page meta -->
              <span class="page-meta"><?php echo lang('import_csv')?></span>
            </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>

            <div class="clearfix"></div>

</div>
<!-- Page heading ends -->

<div class="matter">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="widget wgreen">
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Import data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error'); ?></p>
                    <?php endif;?>
                    <?php if(!empty($error)):?>
                    <p class="label label-important validation"> <?php echo $error; ?> </p>
                    <?php endif;?>
                    <?php if(!empty($message)):?>
                    <p class="label label-important validation"> <?php echo $message; ?> </p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open_multipart(NULL, array('class' => 'form-horizontal import_csv', 'role'=>'form'))?>                              
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('CSV File')?></label>
                          <div class="col-lg-10">
                            <input id="userfile_xml" type="file" name="userfile_csv" size="20" />
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-lg-offset-2 col-lg-10">
                            <?php echo form_submit('submit', lang_check('Import'), 'class="btn btn-primary"')?>
                            <a href="<?php echo site_url('admin/estate')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                          </div>
                        </div>
                    <?php echo form_close()?>
                </div>
            </div>
                <div class="widget-foot">
                    <div class="ajax_box hidden">
                        <div class="header clearfix">
                            <div class="counts">
                                <span class="current">1</span>/<span class="all">500</span>
                            </div>
                        </div>
                        <div class="progress progress-success progress-striped active">
                            <div class="bar" style="width: 0%;"></div>
                        </div>
                        <div class="" id="ajax_output" style="background: white;padding: 15px;border: 1px solid #e8e8e8;height: 200px;overflow: auto;"></div>
                    </div>
                    <?php if(isset($imports)): ?>                     
                        <p><?php _l('Added/Overwritten'); ?>: <?php echo sw_count($imports);?></p>
                        <p><?php _l('Skipped'); ?>: <?php echo $skipped;?></p>
                    <?php endif; ?>

                    <?php if (!empty($imports)): ?>
                    <p><?php _l('Update/Import completed'); ?>:</p>
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th><?php _l('Address'); ?></th>
                        </tr>
                        <?php foreach ($imports as $item): ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td> <a href="<?php echo site_url('admin/estate/edit/'.$item['preview_id']); ?>"><?php echo $item['address']; ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p><?php _l('Example CSV file'); ?>:</p>
                    <br />
                    <a class="label label-warning" target="_blank" href="http://iwinter.com.hr/support/?p=7867"><?php _l('Examples and guides'); ?></a>
                    <br /><br />
                    <pre>
                        <img src='<?php echo base_url('admin-assets/img/example_csv_user.jpg') ?>' style="max-width: 100%;">
                    </pre>
                <?php endif; ?>
            </div>
        </div>  
      </div>
    </div>
</div>
</div>
<script>

/* CL Editor */
$(document).ready(function(){
    $('.import_csv').submit(function(e){
            e.preventDefault();
            
            var form = $('.import_csv');
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }
            var php_data =  formdata ? formdata : form.serialize();
            var import_ajax = function () {
                $.ajax({
                  url: "<?php echo site_url('privateapi/importcsv_users');?>",
                  data        : php_data,
                  cache       : false,
                  contentType : false,
                  processData : false,
                  async: true, 
                  //very important: else php_data will be returned even before we get Json from the url
                  //global: false,
                  type: 'post',
                  complete: function (json) {
                        json = $.parseJSON(json.responseText);
                        $('.ajax_box').removeClass('hidden')
                        php_data= new FormData();
                        
                        $.each(json.reference, function(i, v) {
                            php_data.append(i, v);
                        })
                        php_data.append('importing', json.importing);
                        
                        $.each(json.log, function(i, v) {
                            $('#ajax_output').append('<p>'+v+'</p>');
                            $('#ajax_output').scrollTop(Number.MAX_SAFE_INTEGER);
                        })
                    
                        /* header output data */
                        var ajax_box = $('.ajax_box');
                        ajax_box.find('.counts .all').html(json.output.count_all)
                        ajax_box.find('.counts .current').html((json.output.count_key+1))
                        
                        ajax_box.find('.bar').css('width', (((json.output.count_key+1 ) / json.output.count_all))*100+'%')
                        /* end header output data */

                        /* recursion start */
                        if(json.importing == 1 ) {
                            setTimeout(function(){import_ajax()}, 2000);
                        }
                    }
                });
            }
            import_ajax();
        
    })
});

</script>