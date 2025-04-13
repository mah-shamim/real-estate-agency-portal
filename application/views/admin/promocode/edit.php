<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('promocodeed')?>
      <!-- page meta -->
          <span class="page-meta"><?php echo empty($promocode->id) ? lang_check('Add a new promocode') : lang_check('Edit promocode').' "' . $promocode->code_name.'"'?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/promocodes')?>"><?php echo lang_check('promocode')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">
              <div class="widget wgreen">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('promocode data')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <?php if($this->session->flashdata('message')):?>
                    <p class="label label-success validation"><?php echo $this->session->flashdata('message')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?php echo lang_check('Code Name')?></label>
                                    <div class="col-lg-10">
                                        <?php echo form_input('code_name', set_value('code_name', $promocode->code_name), 'class="form-control" placeholder="'.lang_check('Code name').'"');?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?php echo lang_check('Value')?></label>
                                    <div class="col-lg-10">
                                        <?php echo form_input('value', set_value('value', $promocode->value), 'class="form-control" id="inputvalue" placeholder="'.lang_check('value').'"')?>
                                    </div>
                                </div>
                                
                                
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?php echo lang_check('Quantity')?></label>
                                    <div class="col-lg-10">
                                        <?php echo form_input('quantity', set_value('quantity', $promocode->quantity), 'class="form-control" id="inputquantity" placeholder="'.lang_check('quantity').'"')?>
                                    </div>
                                </div>
                    
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Used')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('used', set_value($promocode->used, 0), 'class="form-control" id="inputused" readonly="readonly" placeholder="'.lang_check('used').'"')?>
                                  </div>
                                </div>
                    
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Usage')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('usage', array(''=>lang_check('Set value'))+$this->promocode_m->usage_array, $this->input->post('usage') ? $this->input->post('usage') : $promocode->usage, 'class="form-control"')?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Packages')?></label>
                                  <div class="col-lg-10">
                                      <div class="fieldset_checkboxes">
                                    <?php foreach ($this->promocode_m->packages_array as $package_id => $package):?>
                                        <?php $checked = '';
                                            if(in_array($package_id, $promocode->packages) !== FALSE)
                                                $checked = 'checked="checked"';
                                        ?>
                                      <label>
                                        <?php echo form_checkbox('packages[]', $package_id, $checked, 'id="inputRandom"')?>
                                           <?php echo $package;?>
                                      </label>
                                    <?php endforeach;?>
                                      </div>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?php echo lang_check('Date Start')?></label>
                                    <div class="col-lg-10">
                                        <div class="input-append" id="datetimepicker1">
                                            <?php echo form_input('date_start', set_value('date_start', $promocode->date_start), 'class="picker" data-format="yyyy-MM-dd"')?>
                                            <span class="add-on">
                                                &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                                </i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?php echo lang_check('Date End')?></label>
                                    <div class="col-lg-10">
                                        <div class="input-append" id="datetimepicker2">
                                            <?php echo form_input('date_end', set_value('date_end', $promocode->date_end), 'class="picker" data-format="yyyy-MM-dd"')?>
                                            <span class="add-on">
                                                &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
                                                </i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Promocode')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('promocode', set_value('promocode', $promocode->promocode), 'class="form-control" id="inputpromocode" placeholder="'.lang_check('promocode').'"')?>
                                  </div>
                                </div>
                                          
                                <?php if(!empty($promocode->date_created)):?>
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Date Created')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_input('date_created', set_value('date_created', $promocode->date_created), 'class="form-control" id="inputpdate_created" readonly="readonly" placeholder="'.lang_check('date_created').'"')?>
                                  </div>
                                </div>
                                <?php endif;?>
                                <hr />
                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang_check('Save'), 'class="btn btn-success"')?>
                                    <a href="<?php echo site_url('admin/promocodes')?>" class="btn btn-primary" type="button"><?php echo lang_check('Cancel')?></a>
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

<style>
    .fieldset_checkboxes label {
        display: -webkit-align-items;
        display: flex;
        padding: 5px 0;
        -webkit-align-items: center;
        align-items: center;
        cursor: pointer;
    }
</style>

