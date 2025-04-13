<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Reservations')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo lang_check('Import reservations'); ?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Reservations data')?></div>
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
                      <label class="col-lg-2 control-label"><?php echo lang_check('XML File')?></label>
                      <div class="col-lg-10">
                        <input id="userfile_xml" type="file" name="userfile_xml" size="20" />
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <div class="col-lg-offset-2 col-lg-10">
                        <?php echo form_submit('submit', lang_check('Import'), 'class="btn btn-primary"')?>
                        <a href="<?php echo site_url('admin/booking')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                      </div>
                    </div>
                    <?php echo form_close()?>
                  </div>
                </div>
                  <div class="widget-foot">
<?php if(sw_count($import) > 0): ?>
<p><?php _l('Imports completed'); ?>:</p>
<table class="table table-striped">
<tr>
<th>#</th>
<th><?php _l('Imports'); ?></th>
</tr>
<tr>
<?php foreach($import as $property_id=>$property_imports): ?>
<td><?php echo $property_id; ?></td>
<td><?php echo $property_imports; ?></td>
<?php endforeach; ?>
</tr>
</table>
<?php else: ?>
                    <p><?php _l('Example XML file'); ?>:</p>
                    <pre>
<?php echo htmlentities('
<?xml version="1.0" encoding="UTF-8"?>
<properties>
    <property id="4034">
        <date>2015-05-18</date>
        <date>2015-05-19</date>
        <date>2015-05-20</date>
        <date>2015-05-21</date>
        <date>2015-05-22</date>
    </property>
    <property id="4035">
        <date>2015-05-18</date>
        <date>2015-05-19</date>
        <date>2015-05-20</date>
        <date>2015-05-21</date>
        <date>2015-05-22</date>
    </property>
</properties>
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