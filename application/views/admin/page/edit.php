<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Page')?>
          <!-- page meta -->
          <span class="page-meta"><?php echo empty($page->id) ? lang('Add a page') : lang('Edit page').' "' . $page->id.'" <a href="'._ch($edit_page_url).'">('.lang_check('View page').')</a>';?> </span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/page')?>"><?php echo lang('Pages')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">
              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Page data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang('Parent')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('parent_id', $pages_no_parents, $this->input->post('parent_id') ? $this->input->post('parent_id') : $page->parent_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Template')?></label>
                                  <div class="col-lg-4">
                                    <?php echo form_dropdown('template', $templates_page, $this->input->post('template') ? $this->input->post('template') : $page->template, 'class="form-control select_template"'); ?>
                                  </div>
                                  <div class="col-lg-6">
                                    <a href="#" rel="<?php echo site_url('admin/templates/edit/'); ?>" target="_blank" class="hide edit_template btn btn-info"><?php _l('Edit template'); ?></a>
                                  </div>
<script type="text/javascript">
$(function() {
    $('select.select_template').on('change', function() {
        if(this.value.substr(0, 7) == 'custom_')
        {
            $('a.edit_template').removeClass('hide');
            $('a.edit_template').attr('href', $('a.edit_template').attr('rel')+'/'+this.value.substr(7));
        }
        else
        {
            $('a.edit_template').addClass('hide');
        }
    });
    console.log($('select.select_template').val());
    if($('select.select_template').val().substr(0, 7) == 'custom_')
    {
        $('a.edit_template').removeClass('hide');
        $('a.edit_template').attr('href', $('a.edit_template').attr('rel')+'/'+$('select.select_template').val().substr(7));
    }
});




</script>
                                  
                                </div>
                                
                                <?php if(sw_count($templates_headers) > 0): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Template headers')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('template_header', $templates_headers, $this->input->post('template_header') ? $this->input->post('template_header') : $page->template_header, 'class="form-control"'); ?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(sw_count($templates_footers) > 0): ?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Template footers')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('template_footer', $templates_footers, $this->input->post('template_footer') ? $this->input->post('template_footer') : $page->template_footer, 'class="form-control"'); ?>
                                  </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Show as')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('type', array('PAGE'=>lang_check('Page'), 'ARTICLE'=>lang_check('Article')), $this->input->post('type') ? $this->input->post('type') : $page->type, 'class="form-control"'); ?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Visible in menu')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_visible', '1', set_value('is_visible', $page->is_visible), 'id="inputVisible"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Visible for logged users')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_private', '1', set_value('is_private', $page->is_private), 'id="inputPrivate"')?>
                                  </div>
                                </div>
                                
                                <hr />
                                <h5><?php echo lang('Translation data')?></h5>
                                <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($this->page_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?> lang"><a data-toggle="tab" href="#<?php echo $key_lang?>"><?php echo $val_lang?></a></li>
                                    <?php endforeach;?>
                                    
                                    <?php if(sw_count($this->page_m->languages) > 1): ?>
                                    <li class="pull-right"><a href="#" id="copy-lang" class="btn btn-default" type="button"><?php echo lang_check('Copy to other languages')?></a></li>
                                    <li class="pull-right"><a href="#" id="translate-lang" rel="<?php echo site_url('api/translate/');  ?>" class="btn btn-default" type="button"><?php echo lang_check('Translate to other languages')?></a></li>
                                    <?php endif; ?>
                                    
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->page_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <div id="<?php echo $key_lang?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Title')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('title_'.$key_lang, set_value('title_'.$key_lang, $page->{'title_'.$key_lang}), 'class="form-control copy_to_next" id="inputOption_'.$key_lang.'_1" placeholder="'.lang('Title').'"')?>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Navigation title')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('navigation_title_'.$key_lang, set_value('navigation_title_'.$key_lang, $page->{'navigation_title_'.$key_lang}), 'class="form-control" id="inputOption_'.$key_lang.'_2" placeholder="'.lang('Navigation title').'"')?>
                                          </div>
                                        </div>
                                        <?php if(config_db_item('slug_enabled') === TRUE): ?>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('URI slug')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('slug_'.$key_lang, set_value('slug_'.$key_lang, $page->{'slug_'.$key_lang}), 'class="form-control" id="inputOption_'.$key_lang.'_slug" placeholder="'.lang_check('URI slug').'"')?>
                                          </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Keywords')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('keywords_'.$key_lang, set_value('keywords_'.$key_lang, $page->{'keywords_'.$key_lang}), 'class="form-control" id="inputOption_'.$key_lang.'_3" placeholder="'.lang('Keywords').'"')?>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Description')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_textarea('description_'.$key_lang, set_value('description_'.$key_lang, $page->{'description_'.$key_lang}), 'placeholder="'.lang('Description').'" rows=4" class="form-control" id="inputOption_'.$key_lang.'_4"')?>
                                          </div>
                                        </div>  
                                        
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Body')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_textarea('body_'.$key_lang, set_value('body_'.$key_lang, $page->{'body_'.$key_lang}), 'placeholder="'.lang('Body').'" rows="10" class="ckeditor form-control" id="inputOption_'.$key_lang.'_5"')?>
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

<?php if(!isset($page->id)):?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else:?>
<div id="page-files-<?php echo $page->id?>" rel="page_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo site_url('files/upload/'.$page->id);?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="<?php echo site_url('admin/page/edit/'.$page->id);?>"></noscript>
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
<?php if(isset($files[$page->repository_id]))foreach($files[$page->repository_id] as $file ):?>
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
                    <a class="btn btn-xs btn-info iedit visible-inline-lg" rel="<?php echo $file->filename?>" href="#<?php echo $file->filename?>"><i class="icon-pencil icon-white"></i></a>
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

<link rel="stylesheet" href="<?php echo base_url('admin-assets/js/zebra/css/flat/zebra_dialog.css')?>">
<script src="<?php echo base_url('admin-assets/js/zebra/javascript/zebra_dialog.src.js')?>"></script>
<script>

/* CL Editor */
$(document).ready(function(){    
    $('.files a.iedit').click(function (event) {
        new $.Zebra_Dialog('', {
            source: {'iframe': {
                'src':  '<?php echo site_url('admin/imageeditor/edit'); ?>/'+$(this).attr('rel'),
                'height': 700
            }},
            width: 950,
            title:  '<?php _jse(lang_check('Edit image')); ?>',
            type: false,
            buttons: false
        });
        return false;
    });
});

</script>


<script>

/* CL Editor */
$(document).ready(function(){
    $(".cleditor2").cleditor({
        width: "auto",
        height: 250,
        //controls: "undo redo | source",
        docCSSFile: "<?php echo $template_css?>",
        baseHref: '<?php echo base_url('templates/'.$settings['template'])?>/'
    });
});

</script>