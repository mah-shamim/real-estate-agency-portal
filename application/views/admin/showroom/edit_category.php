<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Category')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($showroom->id) ? lang('Add a category') : lang('Edit category').' "' . $showroom->id.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a href="<?php echo site_url('admin/showroom')?>"><?php echo lang_check('Showrooms')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/showroom/categories')?>"><?php echo lang_check('Categories')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">

              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Category data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Parent')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('parent_id', $showrooms_no_parents, $this->input->post('parent_id') ? $this->input->post('parent_id') : $showroom->parent_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <hr />
                                <h5><?php echo lang('Translation data')?></h5>
                               <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($this->showroom_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?>"><a data-toggle="tab" href="#<?php echo $key_lang?>"><?php echo $val_lang?></a></li>
                                    <?php endforeach;?>
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->showroom_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <div id="<?php echo $key_lang?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Title')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('title_'.$key_lang, set_value('title_'.$key_lang, $showroom->{'title_'.$key_lang}), 'class="form-control copy_to_next" id="inputTitle'.$key_lang.'" placeholder="'.lang('Title').'"')?>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('Window Title')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('window_title_'.$key_lang, set_value('window_title_'.$key_lang, $showroom->{'window_title_'.$key_lang}), 'class="form-control" id="inputWindowTitle'.$key_lang.'" placeholder="'.lang_check('Window Title').'"')?>
                                          </div>
                                        </div>
                                        
                                         <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Keywords')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('keywords_'.$key_lang, set_value('keywords_'.$key_lang, $showroom->{'keywords_'.$key_lang}), 'class="form-control" id="inputKeywords'.$key_lang.'" placeholder="'.lang('Keywords').'"')?>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Description')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_textarea('description_'.$key_lang, set_value('description_'.$key_lang, $showroom->{'description_'.$key_lang}), 'placeholder="'.lang('Description').'" rows=4" class="form-control"')?>
                                          </div>
                                        </div>  
                                        
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Body')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_textarea('body_'.$key_lang, set_value('body_'.$key_lang, $showroom->{'body_'.$key_lang}), 'placeholder="'.lang('Body').'" rows="10" class="ckeditor form-control"')?>
                                          </div>
                                        </div>  

                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/page')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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
                  <div class="pull-left"><?php echo lang('Files')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">

<?php if(!isset($showroom->id)):?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else:?>
<div id="page-files-<?php echo $showroom->id?>" rel="showroom_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo site_url('files/upload_showroom/'.$showroom->id);?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin showroom -->
        <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/showroom/edit/'.$showroom->id);?>"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <div class="span7 col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo lang('add_files...')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span><?php echo lang('cancel_upload')?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo lang('delete_selected')?></span>
                </button>
                <input type="checkbox" class="toggle" />
            </div>
            <!-- The global progress information -->
            <div class="span5 col-md-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br />
        <!-- The table listing the files available for upload/download -->
        <!--<table role="presentation" class="table table-striped">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">-->

          <div role="presentation" class="fieldset-content">
            <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">      
<?php if(isset($files[$showroom->repository_id]))foreach($files[$showroom->repository_id] as $file ):?>
            <li class="img-rounded template-download fade in">
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo $file->filename?>" data-src="<?php echo $file->thumbnail_url?>" src="<?php echo $file->thumbnail_url?>">
                </div>
                <div class="filename">
                    <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>                  
                    <?php else:?>
                    <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-xs btn-success"><i class="icon-search icon-white"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="icon-trash icon-white"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
<?php endforeach;?>
            </ul>
            <br style="clear:both;"/>
          </div>
    </form>

</div>
<?php endif;?>

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

<script>

/* CL Editor */
$(document).ready(function(){
    $(".cleditor2").cleditor({
        width: "auto",
        height: 250,
        docCSSFile: "<?php echo $template_css?>",
        baseHref: '<?php echo base_url('templates/'.$settings['template'])?>/'
    });
});

</script>