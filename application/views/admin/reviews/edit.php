<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Review')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo empty($listing->id) ? lang_check('Add review') : lang_check('Edit review').' "' . $listing->id.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/reviews')?>"><?php echo lang_check('Reviews')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="#"><?php echo lang_check('Review')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Review data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Property')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('listing_id', $properties, $this->input->post('listing_id') ? $this->input->post('listing_id') : $listing->listing_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('User')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('user_id', $users, $this->input->post('user_id') ? $this->input->post('user_id') : $listing->user_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Stars')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('stars', array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'), $this->input->post('stars') ? $this->input->post('stars') : $listing->stars, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Visible')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('is_visible', '1', set_value('is_visible', $listing->is_visible), 'id="inputVisible"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Mail')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('user_mail', set_value('user_mail', $listing->user_mail), 'class="form-control" id="inputUserMail" placeholder="'.lang_check('Mail').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Message')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_textarea('message', set_value('message', $listing->message), 'placeholder="'.lang('Message').'" rows="3" class="form-control"')?>
                                  </div>
                                </div>   

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/reviews')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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