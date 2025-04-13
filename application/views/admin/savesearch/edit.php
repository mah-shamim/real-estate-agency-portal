<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Research')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo empty($listing->id) ? lang_check('Add research') : lang_check('Edit research').' "' . $listing->id.'"'?></span>
        </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/savesearch')?>"><?php echo lang_check('Research')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="#"><?php echo lang_check('Research')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Research data')?></div>
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
                                  <label class="col-lg-2 control-label"><?php echo lang_check('User')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('user_id', $users, $this->input->post('user_id') ? $this->input->post('user_id') : $listing->user_id, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('Activated')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_checkbox('activated', '1', set_value('activated', $listing->activated), 'id="inputActivated"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php _l('Frequency of the delivery'); ?></label>
                                  <div class="col-lg-10">
                                  <?php 
                                  
                                $options = array(
                                                  '0'   => lang_check('Instant'),
                                                  '24'  => lang_check('Daily'),
                                                  '168' => lang_check('Weekly')
                                                );
                                                
                                echo form_dropdown('delivery_frequency_h', $options, set_value('delivery_frequency_h', $listing->delivery_frequency_h), 'class="form-control"');
                                  
                                  ?>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/savesearch')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                    <b><?php echo lang_check('Parameters').':'; ?></b><br />
                    <?php echo lang_check('Lang code').': '; ?><?php echo '['.strtoupper($listing->lang_code).']'; ?><br />
                    <?php
                    
                    $parameters = json_decode($listing->parameters);
                    if(isset($parameters) && !empty($parameters))
                    foreach($parameters as $key=>$value){
                        if(!empty($value)){
                            if(is_array($value))
                            {
                                $value = implode(', ', $value);
                            }
                            
                            echo $key.': <b>'.$value.'</b><br />';
                        }
                        
                    }

                    ?>
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