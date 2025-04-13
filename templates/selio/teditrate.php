<!doctype html>
<html class="no-js" lang="en">
    <head>
        <?php _widget('head'); ?>
    </head>
    <body>
        <div >
            <header>
                <?php _widget('header_bar'); ?>
                <?php _widget('header_main_panel'); ?>
            </header><!--header end-->
             <?php _widget('top_title'); ?>
            <div class="container m-padding">
                    <div class="widget-panel border widget-submit">
                    <div class="widget-header header-styles">
                        <h2 class="title">
                            <?php if(!empty($rate->id)):?>
                                <?php echo lang_check('Edit rate'); ?>, #<?php echo $rate->id; ?>
                            <?php else: ?>
                                <?php echo lang_check('Add rate'); ?>
                            <?php endif; ?>
                            <?php echo anchor('trates/index/'.$lang_code.'#content', '<i class="icon-book"></i>&nbsp;&nbsp;'.lang_check('View properties'), 'class="btn pull-right"')?>
                        </h2>
                    </div> <!-- ./ title --> 
                    <div class="validation m25">
                        <?php echo validation_errors()?>
                        <?php if($this->session->flashdata('message')):?>
                        <?php echo $this->session->flashdata('message')?>
                        <?php endif;?>
                        <?php if($this->session->flashdata('error')):?>
                        <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                        <?php endif;?>
                    </div>
                    <div class="widget-content">
                        <?php echo form_open(NULL, array('class' => 'form-estate', 'role'=>'form'))?>                    
                            <div class="form-group control-group">
                              <label class="control-label"><?php echo lang_check('Property')?></label>
                              <div class="controls">
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
                            <div class="form-group control-group">
                              <label class="control-label"><?php echo lang_check('Table row')?></label>
                              <div class="controls">
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
                            <div class="form-group control-group">
                              <label class="control-label"><?php echo lang_check('Dates')?></label>
                              <div class="controls">
                                <?php 
                                echo form_textarea('dates', $this->input->post('dates') != '' ? $this->input->post('dates') : $rate->dates, 
                                                         'id="dates_list" placeholder="'.lang_check('Dates').'" rows="3" class="form-control" readonly')?>
                              </div>
                            </div>
                            <div class="form-group control-group">
                              <label class="control-label"><?php echo lang_check('Rates')?></label>
                              <div class="controls">
                                <?php 
                                echo form_textarea('rates', $this->input->post('rates') != '' ? $this->input->post('rates') : $rate->rates, 
                                                         'id="rates_list" placeholder="'.lang_check('Rates').'" rows="3" class="form-control" readonly')?>
                              </div>
                            </div>  
                          <hr style="clear: both;" />
                          <div class="form-group">
                            <div class="controls">
                              <?php echo form_submit('submit', lang_check('Save'), 'class="btn btn-primary"')?>
                              <a href="<?php echo site_url('rates/index/'.$lang_code)?>#content" class="btn btn-default" type="button"><?php echo lang_check('Cancel')?></a>
                            </div>
                          </div>
                        <?php echo form_close() ?>
                          <div class="calendar_box">
                            <div class="property_content">
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
                              <div class="property_content" id="rates_h">
                                  <h2 class="title"><?php echo lang_check('Rates') ?></h2>
                                  <div class="">
                                      <div class="widget wblue">
                                          <div class="widget-content">
                                              <div class="padd">
                                                  <div class="form_rate">
                                                      <form class="form-estate">
                                                          <div class="form-group control-group">
                                                              <label class="control-label"><?php echo lang_check('From date') ?></label>
                                                              <div class="controls">
                                                                  <div class="input-append">
                                                                      <?php echo form_input('date_from', $this->input->post('date_from') ? $this->input->post('date_from') : $rate->date_from, 'data-format="yyyy-MM-dd hh:mm:ss" class="form-control"  id="booking_date_from"'); ?>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="form-group control-group">
                                                              <label class="control-label"><?php echo lang_check('To date') ?></label>
                                                              <div class="controls">
                                                                  <div class="input-append">
                                                                      <?php echo form_input('date_to', $this->input->post('date_to') ? $this->input->post('date_to') : $rate->date_to, 'data-format="yyyy-MM-dd hh:mm:ss" class="form-control" id="booking_date_to"'); ?>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="form-group">
                                                              <div class="input-append">
                                                                  <?php echo form_input('min_stay', '', 'placeholder="' . lang_check('Min stay') . '" class="form-control"  style=""'); ?>
                                                              </div>
                                                          </div>
                                                          <div class="form-group">
                                                              <div class="input-append">
                                                                  <?php echo form_input('prefix', '', 'placeholder="' . lang_check('Prefix') . '" class="form-control"  style="display:none;"'); ?>
                                                              </div>
                                                          </div>
                                                          <div class="form-group">
                                                              <div class="input-append">
                                                                  <?php echo form_input('price', '', 'placeholder="' . lang_check('Price') . '" class="form-control" '); ?>
                                                              </div>
                                                          </div>
                                                          <div class="form-group">
                                                              <div class="input-append">
                                                                  <?php echo form_input('suffix', 'EUR', 'placeholder="' . lang_check('Suffix') . '" class="form-control"  style="" readonly'); ?>
                                                              </div>
                                                          </div>
                                                          <hr />
                                                          <button type="button" id="add_rate" class="btn btn-primary"><?php _l('Add'); ?></button>
                                                      </form>
                                                      <hr />
                                                  </div>
                                                  <div class="form_rate_table" style="min-height: 250px;">
                                                      <table class="table table-striped">
                                                          <thead> <tr> <th><?php _l('From date'); ?></th> <th><?php _l('To date'); ?></th> <th><?php _l('Min stay'); ?></th> <th><?php _l('Price'); ?></th> <th></th> </tr> </thead>
                                                          <tbody id="rates_table_body"> 
                                                          </tbody> 
                                                      </table>
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
                </div> <!-- ./ widget-submit --> 
            </div>
        <?php _subtemplate('footers', _ch($subtemplate_footer, 'alternative')); ?>
        </div><!--wrapper end-->
        <?php _widget('custom_javascript'); ?>
        <?php
        /* dinamic per listing */
        _generate_js('_generate_login_page_js_' . md5(current_url_q()), 'widgets/_generate_login_page_js.php', false, 0);
        ?>

        <style>
            
            .calendar_box {
                display: -webkit-flex;
                display: flex;
            }
            .property_content {
                -webkit-flex: 1 2 auto;
                flex: 1 2 auto;
            }

            .property_content#rates_h {
                flex: 0 0 250px;
                -webkit-flex: 0 0 250px;
            } 
            
        table.av_calender {
            float: left;
        }
            
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

        select option:disabled {
            background: #dddddd;
        } 

        .form-inline .form-group{
            flaot:left;
            display: inline-block;
        }


        </style>

        <script>

        var state_range = 0;
        var selectableDatesItem;
        var start_index = 0;
        var end_index = 0;
        var end_index_temp = 0;
        var date;

        $(document).ready(function(){

            $('input#booking_date_from, input#booking_date_to').click(function (){
                $('html, body').animate({
                    scrollTop: $("#rates_h").offset().top
                }, 1000);
            });

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

                if(gen_html.length > 125)
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

            console.log($('#rates_list').val());

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
    </body>

</html>