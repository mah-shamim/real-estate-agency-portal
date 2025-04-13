<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Ads')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($ad->id) ? lang_check('Add a new ad') : lang_check('Edit ad').' "' . $ad->id.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/ads')?>"><?php echo lang('Ads')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">        
          <div class="row">

            <div class="col-md-12">


              <div class="widget wred">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Ad data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang('Type')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('type', $this->ads_m->ads_types, set_value('type', $ad->type), 'class="form-control"');?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Title')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('title', set_value('title', $ad->title), 'class="form-control" id="inputTitle" placeholder="'.lang_check('Title').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Link')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('link', set_value('link', $ad->link), 'class="form-control" id="inputLink" placeholder="'.lang_check('Link').'"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Description')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('description', set_value('description', $ad->description), 'placeholder="'.lang('Description').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>      

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Activated')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_activated', '1', set_value('is_activated', $ad->is_activated), 'id="inputActivated"')?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Random')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_random', '1', set_value('is_random', $ad->is_random), 'id="inputRandom"')?>
                                  </div>
                                </div>

                                    <hr />
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/ads')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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
            
        <div class="col-md-12">

              <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Images')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">

<?php if(!isset($ad->id)):?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else:?>
<div id="page-files-<?php echo $ad->id?>" rel="ads_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo site_url('files/upload_ads/'.$ad->id);?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/ads/edit/'.$ad->id);?>"></noscript>
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
<?php if(isset($files[$ad->repository_id]))foreach($files[$ad->repository_id] as $file ):?>
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