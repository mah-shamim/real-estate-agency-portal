<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('TreeField import')?>
          <!-- page meta -->
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate/options')?>"><?php echo lang('Fields')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate/edit_option/'.$option->id)?>"><?php echo lang('Field').' #'.$option->id?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Generate locations')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <a href="#" id='selcect_deselect_checkbox' data-status='checked' class="btn btn-danger" type="button"><i class="icon-check"></i> <?php echo lang_check('select all / deselect all');?></a>
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
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open_multipart(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                    <div class="clearfix">   
                        <?php
                        foreach ($locations_list as $key => $location):?>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label style="cursor: pointer;">
                                    <?php echo form_checkbox('locations[]', $location, FALSE, 'class="check-box-location"')?>
                                    <?php echo $location;?>
                                </label>
                            </div>
                        </div>
                    
                        <?php endforeach;?>
                    </div>  
                    <hr><br/>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <?php echo form_submit('submit', lang_check('Generate'), 'class="btn btn-primary" onclick="return confirm(\' All values will be removed, are you sure?\')"')?>
                                <a href="<?php echo site_url('admin/treefield/edit/'.$option->id)?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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


<script>
$(document).ready(function(){
    $('#selcect_deselect_checkbox').click(function(e){
        e.preventDefault();
        $(".check-box-location").prop('checked', $(this).attr('data-status'));
        
        if($(this).attr('data-status')=='checked'){
           $(this).attr('data-status','')
        } else {
           $(this).attr('data-status','checked')
        }
    })
})
</script>