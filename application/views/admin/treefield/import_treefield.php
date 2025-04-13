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
                  <div class="pull-left"><?php echo lang_check('Rates data')?></div>
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
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open_multipart(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang('XML Url')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('xml_url', set_value('xml_url'), 'class="form-control" id="inputMinStay" placeholder="'.lang('XML Url').'"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('XML File')?></label>
                                  <div class="col-lg-10">
                                    <input id="userfile_xml" type="file" name="userfile_xml" size="20" />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Import root, country value')?></label>
                                  <div class="col-lg-10">
                                  <?php echo form_checkbox('root_country', '1', false, 'id="root_country"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang_check('Import'), 'class="btn btn-primary" onclick="return confirm(\' All values will be removed, are you sure?\')"')?>
                                    <a href="<?php echo site_url('admin/treefield/edit/'.$option->id)?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
<?php if(!empty($import_end)): ?>
<p><?php _l('Imports completed'); ?></p>
<?php else: ?>
                    <p><?php _l('Example XML file'); ?>:</p>
                    <pre>
<?php echo htmlentities('<Records>
    <co00>00000UNITED STATES</co00>
    <co01>01000ALABAMA</co01>
    <co01>01001Autauga, AL</co01>
    <co01>01003Baldwin, AL</co01>
    <co01>01005Barbour, AL</co01>
    <co01>01007Bibb, AL</co01>
    <co01>01009Blount, AL</co01>
    <co01>01011Bullock, AL</co01>
    <co01>01013Butler, AL</co01>
</Records>');
?>
                    </pre>
                    
<p><?php _l('Example XML Url'); ?>:</p>
                    <pre>
<?php echo htmlentities('
http://geniuscript.com/xml/counties_USA.xml
');
?>
<?php echo htmlentities('
http://geniuscript.com/xml/cites_NL.xml
');
?>
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
    $(".cleditor2").cleditor({
        width: "auto",
        height: 250,
        docCSSFile: "<?php echo $template_css?>",
        baseHref: '<?php echo base_url('templates/'.$settings['template'])?>/'
    });
});

</script>