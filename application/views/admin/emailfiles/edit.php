<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('File editor')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo lang_check('Edit file').' "' . $filename.'"'?></span>
        </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread" href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Settings')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/emailfiles')?>"><?php echo lang_check('Template files list')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="#"><?php echo lang_check('Edit file')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('File editor')?></div>
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
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('File content')?></label>
                                  <div class="col-lg-10">
                                    <?php 
                                    echo form_textarea('file_content', $file_content, 'placeholder="'.lang_check('File content').'" rows="20" style="height:800px;width:100%;" class="form-control" id="file_content"')?>
                                  </div>
                                </div>   

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/emailfiles')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
              </div>  
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('admin-assets/js/codemirror/lib/codemirror.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('admin-assets/js/codemirror/lib/codemirror.css'); ?>">
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/javascript/javascript.js'); ?>"></script>

<script src="<?php echo base_url('admin-assets/js/codemirror/addon/edit/matchbrackets.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/htmlmixed/htmlmixed.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/xml/xml.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/css/css.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/clike/clike.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/php/php.js'); ?>"></script>

<script type="text/javascript">
$(function() {

    var editor = CodeMirror.fromTextArea(document.getElementById("file_content"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true
    });
});
</script>

<style>
.CodeMirror {
  border: 1px solid #eee;
  height: 600px;
}
</style>
