<!-- Page heading -->
<div class="page-head">
<!-- Page heading -->
<h2 class="pull-left"><?php echo lang_check('Test Mail Send')?>
              <!-- page meta -->
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
                  <div class="pull-left"><?php echo lang_check('Data')?></div>
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
                    <?php echo form_open_multipart(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                        <div class="form-group">
                          <label class="col-lg-2 control-label"><?php echo lang_check('Email')?></label>
                          <div class="col-lg-10">
                            <?php echo form_input('email', $this->input->post('email') ? $this->input->post('email') : '', 'class="form-control" id="inputMinStay" placeholder="'.lang_check('Received Email').'"')?>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-lg-offset-2 col-lg-10">
                            <?php echo form_submit('submit', lang_check('Send'), 'class="btn btn-primary"')?>
                            <a href="<?php echo site_url('admin/settings/')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                          </div>
                        </div>
                    <?php echo form_close()?>
                  </div>
                </div>
                <div class="widget-foot">
                    <?php if(isset($output_log) && !empty($output_log)):?>
                        <div class="" id="ajax_output" style="background: white;padding: 15px;border: 1px solid #e8e8e8;height: 200px;overflow: auto;"><?php echo($output_log);?></div>
                    <?php endif;?> 
              </div>  
            </div>
        </div>
    </div>
</div>