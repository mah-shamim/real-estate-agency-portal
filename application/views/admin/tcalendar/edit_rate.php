<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Dates')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo lang_check('Add multiple dates'); ?></span>
        </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/tcalendar')?>"><?php echo lang_check('TCalendar')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="#"><?php echo lang_check('Add date range')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget worange">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Data')?></div>
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
                                    <?php echo form_dropdown('property_id', $properties, $this->input->post('property_id') != '' ? $this->input->post('property_id') : $rate->property_id, 
                                                             'class="form-control" id="property_id"')?>
                                                             
                                    <?php if(!empty($rate->property_id)): ?>
<script>

$(document).ready(function(){
    $('#property_id option:not(:selected)').attr('disabled', true);   
});
                     
</script>
                                    <?php endif;?>
    
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Table row')?></label>
                                  <div class="col-lg-10">
                                    <?php echo form_dropdown('table_row_index', $t_rows, $this->input->post('table_row_index') != '' ? $this->input->post('table_row_index') : $rate->table_row_index, 
                                                             'class="form-control" id="table_row_index"')?>
                                    <?php if(!empty($rate->table_row_index)): ?>
<script>

$(document).ready(function(){
    $('#table_row_index option:not(:selected)').attr('disabled', true);   
});
                     
</script>
                                    <?php endif;?>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Dates')?></label>
                                  <div class="col-lg-10">
                                    
                                    <?php 

                                    echo form_textarea('dates', $this->input->post('dates') != '' ? $this->input->post('dates') : $rate->dates, 
                                                             'id="dates_list" placeholder="'.lang_check('Dates').'" rows="3" class="form-control" readonly')?>
                                 
                                  </div>
                                </div>  
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('Rates')?></label>
                                  <div class="col-lg-10">
                                    
                                    <?php 

                                    echo form_textarea('rates', $this->input->post('rates') != '' ? $this->input->post('rates') : $rate->rates, 
                                                             'id="rates_list" placeholder="'.lang_check('Rates').'" rows="3" class="form-control" readonly')?>
                                 
                                  </div>
                                </div>       
                                
                                <hr />

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/tcalendar/available')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
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

          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Calendar')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <div class="av_calender">
                    <?php
                        $row_break=0;
                        
                        foreach($months_availability as $v_month)
                        {
                            echo $v_month;
                            
                            $row_break++;
                            if($row_break%3 == 0)
                            echo '<div style="clear: both;height:10px;"></div>';
                        }
                    ?>
                    <br style="clear: both;" />
                    </div>
                  </div>
                </div>
                  <div class="widget-foot">
                    <!-- Footer goes here -->
                  </div>
              </div>  

            </div>
            </div>
            
          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Rates')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <div class="form_rate">
<form class="form-inline">
    <div class="form-group">
      <div class="input-append" id="datetimepicker1">
        <?php echo form_input('date_from', '', 'placeholder="'.lang('From date').'" class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
        <span class="add-on">
          &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
          </i>
        </span>
      </div>
    </div>
    
    <div class="form-group">
      <div class="input-append" id="datetimepicker2">
        <?php echo form_input('date_to', '', 'placeholder="'.lang('To date').'" class="picker" data-format="yyyy-MM-dd hh:mm:ss"'); ?>
        <span class="add-on">
          &nbsp;<i data-date-icon="icon-calendar" data-time-icon="icon-time" class="icon-calendar">
          </i>
        </span>
      </div>
    </div>

    <div class="form-group">
      <div class="input-append">
        <?php echo form_input('min_stay', '', 'placeholder="'.lang_check('Min stay').'" class="" style="width:70px;"'); ?>
      </div>
    </div>

    <div class="form-group">
      <div class="input-append">
        <?php echo form_input('prefix', '', 'placeholder="'.lang_check('Prefix').'" class="" style="width:50px;display:none;"'); ?>
      </div>
    </div>
    
    <div class="form-group">
      <div class="input-append">
        <?php echo form_input('price', '', 'placeholder="'.lang_check('Price').'" class=""'); ?>
      </div>
    </div>
    
    <div class="form-group">
      <div class="input-append">
        <?php echo form_input('suffix', 'EUR', 'placeholder="'.lang_check('Suffix').'" class="" style="width:50px;" readonly'); ?>
      </div>
    </div>
    <button type="button" id="add_rate" class="btn btn-default"><?php _l('Add'); ?></button>
</form>
                    </div>
                    <hr />
<div class="form_rate_table" style="min-height: 250px;">

<table class="table table-striped">
<thead> <tr> <th><?php _l('From date'); ?></th> <th><?php _l('To date'); ?></th> <th><?php _l('Min stay'); ?></th> <th><?php _l('Price'); ?></th> <th></th> </tr> </thead>
<tbody id="rates_table_body"> 


</tbody> </table>
</div>
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

<style>

table.av_calender a.selected_date{
    background: orange;
}

table.av_calender a.selected_date_temp{
    background: violet;
}

table.av_calender a.disabled
{
    text-decoration: line-through;
    background: black;
}

.form_rate .input-append input
{
    padding: 5px;
}

</style>

<script>

var state_range = 0;
var selectableDatesItem;
var start_index = 0;
var end_index = 0;
var end_index_temp = 0;

$(document).ready(function(){
    
    $('#add_rate').click(function(){
        
        var from_d = $('.form_rate input[name=date_from]').val();
        var to_d = $('.form_rate input[name=date_to]').val();
        var min_stay = $('.form_rate input[name=min_stay]').val();
        var price = $('.form_rate input[name=prefix]').val()+$('.form_rate input[name=price]').val()+$('.form_rate input[name=suffix]').val();
        
        res = from_d.split(" "); 
        from_d = res[0];
        
        res = to_d.split(" "); 
        to_d = res[0];
        
        var gen_html = '<tr>'+
                        '<td>'+ from_d +
                        '</td>'+
                        '<td>'+ to_d +
                        '</td>'+
                        '<td>'+ min_stay +
                        '</td>'+
                        '<td>'+ price +
                        '</td>'+
                        '<td><button type="button" class="b_remove_rate btn btn-warning">X</button>'
                        '</td>'+
                       '</tr>';
        
        //console.log(gen_html.length);
        
        if(gen_html.length > 110)
            $('#rates_table_body').append(gen_html);
        
        $('.b_remove_rate').unbind();
        $('.b_remove_rate').click(function(){
            $(this).parent().parent().remove();
            save_rates_changes();
        });
        
        //save changes
        save_rates_changes();
        
        $('.form_rate input[name=date_from]').val('');
        $('.form_rate input[name=date_to]').val('');
        $('.form_rate input[name=min_stay]').val('');
        $('.form_rate input[name=price]').val('');
        
    });
    
    if($('#rates_list').val() != '')
    {
        $('#rates_table_body').html($('#rates_list').val());
        $('#rates_table_body tr').append('<td><button type="button" class="b_remove_rate btn btn-warning">X</button></td>');
    }
    
    $('.b_remove_rate').click(function(){
        $(this).parent().parent().remove();
        save_rates_changes();
    });
    
    selectableDatesItem = $('a.selectable');
    /*
    $("#property_id").change(function() {

        $.post("<?php echo site_url('privateapi'); ?>/load_reservations/"+$(this).val(), [], 
               function(data){
            
            $.each( data.existing_dates, function( key, value ) {
                //console.log(value);
                $('a[ref='+value+']').addClass('disabled');
            });

        });
        
    });
    */
    
    
    $('a.selectable').click(function(){
        if(state_range == 0)
        {
            start_index = selectableDatesItem.index(this);
            $(this).addClass('selected_date_temp');
            state_range = 1;
        }
        else
        {
            end_index = selectableDatesItem.index(this);
            
            selectableDatesItem.each(function(index, value){
                if(end_index > start_index)
                {
                    if(index >= start_index && index <= end_index)
                    {
                        if($(this).hasClass('selected_date'))
                        {
                            $(this).removeClass('selected_date')
                        }
                        else
                        {
                            $(this).addClass('selected_date');
                        }
                    }
                }
                else
                {
                    if(index >= end_index && index <= start_index)
                    {
                        if($(this).hasClass('selected_date'))
                        {
                            $(this).removeClass('selected_date')
                        }
                        else
                        {
                            $(this).addClass('selected_date');
                        }
                    }
                }
            });
            
                
            state_range = 0;
            
            selectableDatesItem.each(function(index, value){
                $(this).removeClass('selected_date_temp');
            });
            
            $('a.disabled').removeClass('selected_date');
            
            refresh_dates();
        }
        
        return false;
    });
    
    $("a.selectable").mouseover(function() {
        end_index_temp = selectableDatesItem.index(this);
        
        if(state_range == 1)
        selectableDatesItem.each(function(index, value){
            if(end_index_temp > start_index)
            {
                if(index >= start_index && index <= end_index_temp)
                {
                    $(this).addClass('selected_date_temp');
                }
                else
                {
                    $(this).removeClass('selected_date_temp');
                }
            }
            else
            {
                if(index >= end_index_temp && index <= start_index)
                {
                    $(this).addClass('selected_date_temp');
                }
                else
                {
                    $(this).removeClass('selected_date_temp');
                }
            }
        });
    });
    
    if($('#dates_list').val() != '')
    {
        $.each( $('#dates_list').val().split("\n"), function( key, value ) {
            //console.log(value);
            $('a[ref='+value+']').addClass('selected_date');
        });
    }
    
});

function refresh_dates()
{
    $('#dates_list').val('');
    
    selectableDatesItem.each(function(index, value){
        if($(this).hasClass('selected_date'))
        {
            $('#dates_list').val($('#dates_list').val() + $(this).attr('ref') + "\n");
        }
    });
}

function save_rates_changes()
{
    //save changes
    var table_body = $('#rates_table_body').clone();
    table_body.find('.b_remove_rate').parent().remove();
    $('#rates_list').val($.trim(table_body.html()));
}

</script>