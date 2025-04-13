<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Rates')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo empty($rate->id) ? lang_check('Add rate') : lang_check('Edit rate').' "' . $rate->id.'"'?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking')?>"><?php echo lang_check('Booking')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/booking/rates')?>"><?php echo lang_check('Rates')?></a>
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
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>                              
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Property')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('property_id', $properties, $this->input->post('property_id') ? $this->input->post('property_id') : '', 'class="form-control"')?>
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
                              
                                    <?php $lang_data = $this->language_m->get($key_lang);?>
                                        <table class="table-rates">
                                            <tr class="tabel-rates-header">
                                                <th><?php echo lang('Months');?></th>
                                                <th><?php echo lang('Rate weekly');?></th>
                                                <th><?php echo lang('Rate weekly');?></th>
                                                <th><?php echo lang('Rate weekly');?></th>
                                                <th><?php echo lang('Rate weekly');?></th>
                                                <th><?php echo lang('Rate weekly');?></th>
                                            </tr>
                                            
                                            <?php 
                                            for ($month=6;$month<=9;$month++) :
                                            ?>
                                            <tr>
                                                <td rowspan="2"><?php _che($months[$month]);?></td>
                                                <?php
                                                foreach ($cal_weeks[$month] as $week) {
                                                    echo '<td>'.date('d',$week[0]).'/'.date('d', ($week[sw_count($week)-1]+86400)).'</td>';
                                                }
                                                ?>
                                                
                                                <?php if(sw_count($cal_weeks[$month])<5):?>  
                                                    <td class="empty-td">
                                                    </td>
                                                <?php endif;?>
                                            </tr>
                                            <tr>
                                                <?php foreach ($cal_weeks[$month] as $key=>$week) : ?>
                                                    <td>
                                                        <?php echo form_input("rate_weekly_". $month."_".$key."_".$key_lang, set_value("rate_nightly_". $month."_".$key."_".$key_lang), 'class="table-rates-input perday"')?>
                                                        <?php echo $lang_data->currency_default;?>
                                                        <?php if($key_lang==1):?>  
                                                         <input class="table-rates-input perday" type="hidden" name="date_from_<?php echo $month;?>_<?php echo $key;?>" value="<?php echo date('Y-m-d 12:00:00', $week[0]);?>">
                                                        <input class="table-rates-input perday" type="hidden" name="date_to_<?php echo $month;?>_<?php echo $key;?>" value="<?php echo date('Y-m-d 12:00:00', $week[sw_count($week)-1]+86400);?>">
                                                        <?php endif;?>
                                                    </td>
                                                <?php endforeach;?>
                                                <?php if(sw_count($cal_weeks[$month])<5):?>  
                                                    <td class="empty-td">
                                                    </td>
                                                <?php endif;?>
                                            </tr>
                                            
                                            <?php endfor;?>
                                        </table>
                                        
                                    </div>
                                    <?php endforeach;?>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/booking/rates')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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

/* calculate per mont and week */
$('document').ready(function(){
    $('.perday').change(function(){
        var value= $(this).val();
        console.log(value)
        $(this).parent().parent().find('.week').val((value*7));
        $(this).parent().parent().find('.month').val((value*30));
        
    })
    
    
})



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