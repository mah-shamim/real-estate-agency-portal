<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Q&A')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo empty($expert->id) ? lang_check('Add question') : lang_check('Edit question').' "' . $expert->id.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/expert')?>"><?php echo lang_check('Q&A')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Question data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Category')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('parent_id', $experts_no_parents, $this->input->post('parent_id') ? $this->input->post('parent_id') : $expert->parent_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Expert')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('answer_user_id', $experts_user, $this->input->post('answer_user_id') ? $this->input->post('answer_user_id') : $expert->answer_user_id, 'class="form-control"')?>
                                  </div>
                                </div>
                            
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date publish')?></label>
                                  <div class="col-lg-10">
                                  <div class="input-append" id="datetimepicker1">
                                    <?php echo form_input('date_publish', $this->input->post('date_publish') ? $this->input->post('date_publish') : $expert->date_publish, 'class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
                                    <span class="add-on">
                                      &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                      </i>
                                    </span>
                                  </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Readed')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_readed', '1', set_value('is_readed', $expert->is_readed), 'id="inputReaded"')?>
                                  </div>
                                </div>

                                <hr />
                                <h5><?php echo lang('Translation data')?></h5>
                               <div style="margin-bottom: 0px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($this->qa_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?>"><a data-toggle="tab" href="#<?php echo $key_lang?>"><?php echo $val_lang?></a></li>
                                    <?php endforeach;?>
                                  </ul>
                                  <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($this->qa_m->languages as $key_lang=>$val_lang):$i++;?>
                                    <div id="<?php echo $key_lang?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('Question')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_textarea('question_'.$key_lang, set_value('question_'.$key_lang, $expert->{'question_'.$key_lang}), 'placeholder="'.lang('Question').'" rows=4" class="form-control"')?>
                                          </div>
                                        </div> 
                                        
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang_check('Answer')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_textarea('answer_'.$key_lang, set_value('answer_'.$key_lang, $expert->{'answer_'.$key_lang}), 'placeholder="'.lang('Answer').'" rows=4" class="form-control"')?>
                                          </div>
                                        </div> 
                                        
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label"><?php echo lang('Keywords')?></label>
                                          <div class="col-lg-10">
                                            <?php echo form_input('keywords_'.$key_lang, set_value('keywords_'.$key_lang, $expert->{'keywords_'.$key_lang}), 'class="form-control" id="inputKeywords'.$key_lang.'" placeholder="'.lang('Keywords').'"')?>
                                          </div>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/expert')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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