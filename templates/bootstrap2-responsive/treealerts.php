<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <script>
    $(document).ready(function(){
        

        
    });    
    </script>
  </head>

  <body>
  
{template_header}


<a id="content"></a>
<div class="wrap-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
            <h2 id="content"><?php _l('My properties of interest'); ?></h2>
            <div class="property_content">
                <div class="widget-content">
                    <?php if($this->session->flashdata('error_registration') != ''):?>
                    <p class="alert alert-success"><?php echo $this->session->flashdata('error_registration')?></p>
                    <?php endif;?>
                    <p class="alert">
                    <?php _l('Select counties'); ?>
                    </p>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?> 
                    
                    
                    <table class="table" id="treefield_table">
                      <thead>
                        <tr>
                            <th><?php echo lang_check('Location');?></th>
                            <th><?php echo lang_check('Selected');?></th>
                        </tr>
                      </thead>
                      <tbody>
<?php if(sw_count($tree_listings)): foreach($tree_listings as $listing_item):?>
<tr style="<?php echo $listing_item->level>0?'display:none;':''; ?>" rel_id="<?php echo $listing_item->id; ?>" rel="<?php echo $listing_item->level; ?>" >
    <td><a href="#"><?php echo $listing_item->visual.$listing_item->value; ?></a></td>
    <td><?php echo form_checkbox('select_tree[]', $listing_item->id, FALSE); ?></td>                                     
</tr>
<?php endforeach;?>
<?php else:?>
<tr>
	<td colspan="20"><?php echo lang('We could not find any');?></td>
</tr>
<?php endif;?>       
                      </tbody>
                    </table>
                    
                    
                    
    <div class="form-horizontal">
    <h2><?php _l('Type of property'); ?></h2>
    <?php
        $property_types = $options_values_arr_2;
        foreach($property_types as $row):
    ?>
      <div class="control-group">
        <label class="control-label" for="inputEmail"><?php echo $row; ?></label>
        <div class="controls">
          <?php echo form_checkbox('property_type[]', $row, FALSE); ?>
        </div>
      </div>
    <?php endforeach; ?>
    
    <h2><?php _l('Price range'); ?></h2>
                <input name="search_option_36_from" id="search_option_36_from" type="text" class="span3 mPrice DECIMAL" placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})" value="<?php echo search_value('36_from'); ?>" />
                <input name="search_option_36_to" id="search_option_36_to" type="text" class="span3 xPrice DECIMAL" placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})" value="<?php echo search_value('36_to'); ?>" />
    
    <h2><?php _l('Size range'); ?></h2>
                <select name="search_option_3" id="search_option_3" class="span3 selectpicker nomargin">
                    {options_values_3}
                </select>
    
    <h2><?php _l('Delivery method'); ?></h2>
      <div class="control-group">
        <label class="control-label" for="inputEmail"><?php _l('Enable Email alerts'); ?></label>
        <div class="controls">
          <?php echo form_checkbox('research_mail_notifications', '1', TRUE); ?>
        </div>
      </div>
      <?php if(config_db_item('clickatell_api_id') != ''): ?>
      <div class="control-group">
        <label class="control-label" for="inputPassword"><?php _l('Enable SMS alerts'); ?></label>
        <div class="controls">
          <?php echo form_checkbox('research_sms_notifications', '1', FALSE); ?>
        </div>
      </div>
      <?php endif; ?>
      <div class="control-group">
        <label class="control-label" for="inputPassword"><?php _l('Frequency of the delivery'); ?></label>
        <div class="controls">
          <?php 
          
        $options = array(
                          '0'   => lang_check('Instant'),
                          '24'  => lang_check('Daily'),
                          '168' => lang_check('Weekly')
                        );
                        
        echo form_dropdown('delivery_frequency_h', $options, '24');
          
          ?>
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <input type="submit" class="btn" value="<?php _l('Save'); ?>" />
        </div>
      </div>

    </div>                   
<?php echo form_close()?>

<script>
    
// init copy features
$(document).ready(function(){
    
    $('#treefield_table a').click(function(){
        var level = parseInt($(this).parent().parent().attr('rel'));
        var count_sel = 0;
        
        $(this).parent().parent().nextAll('tr').each(function( index ) {
            if(parseInt($(this).attr('rel')) == level+1)
            {
                $(this).show();
                count_sel++;
            }
            else if(parseInt($(this).attr('rel')) < level+1)
            {
                return false;
            }
        });
        
        console.log(count_sel);
        
        if(count_sel == 0)
        {
            $(this).parent().parent().find('input[type=checkbox]').prop( "checked", true );
        }
        
        
        return false;
    });
    
    $('#treefield_table input[type=checkbox]').change(function(){
        var level = parseInt($(this).parent().parent().attr('rel'));
        
        var set_checked = false;
        if($(this).attr('checked'))
        {
            set_checked=true;
        }
        
        $(this).parent().parent().nextAll('tr').each(function( index ) {

            if(parseInt($(this).attr('rel')) > level)
            {
                $(this).find('input[type=checkbox]').prop( "checked", set_checked );
            }
            else if(parseInt($(this).attr('rel')) < level+1)
            {
                return false;
            }

        });
        
        if(set_checked == false)
        {
            var prev_level = level;
            
            $(this).parent().parent().prevAll('tr').each(function( index ) {

                if(parseInt($(this).attr('rel')) < prev_level)
                {
                    $(this).find('input[type=checkbox]').prop( "checked", set_checked );
                    prev_level--;
                }
                else if(parseInt($(this).attr('rel')) ==0)
                {
                    return false;
                }

            });
        }
        
        return false;
    });
    
    
    
});

</script>
                  </div>
            </div>
            </div>
        </div>
        <?php if(false):?>
        <br />
        <div class="property_content">
        {page_body}
        </div>
        <?php endif;?>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>