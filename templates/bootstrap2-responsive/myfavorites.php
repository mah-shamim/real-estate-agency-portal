<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
   <?php _widget('head');?>
    <script>
    $(document).ready(function(){

        
        $('.load_search').click(function(){
            
            //reset form
            $('.search-form form')[0].reset();
//                $(':input','.search-form form')
//                     .not(':button, :submit, :reset, :hidden')
//                     .val('')
//                     .removeAttr('checked')
//                     .removeAttr('selected');
            
            $(this).parent().parent().find('.par_key').each(function( index ) {
                var key = $(this).html();
                var value = $(this).parent().find('.par_value').html();
                
                //console.log('#'+key.substr(2));
                //console.log(value);

                $('#'+key.substr(2)).val(value);
                
                // selectpicker custom render
                if($("#"+key.substr(2)).hasClass('selectpicker'))
                    $("select#"+key.substr(2)+".selectpicker").selectpicker('render');
                
                // checkbox
                $("#"+key.substr(2)+"[type=checkbox]").prop('checked', true);
                
            });

            $(".search-form form select.selectpicker").selectpicker('render');
            
            return false;            
        });
        
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
            <h2 id="content">{lang_Myfavorites}</h2>
            <div class="property_content">
                <div class="widget-content">
                
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                        	<th>#</th>
                            <th><?php echo lang_check('Property');?></th>
                            <th><?php echo lang_check('Language');?></th>
                            <th class="control"><?php echo lang_check('Open');?></th>
                        	<th class="control"><?php echo lang_check('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td><?php echo $properties[$listing_item->property_id]; ?></td>
                                        <td><?php echo '['.strtoupper($listing_item->lang_code).']'; ?></td>
                                        <td>
                                        <a href="<?php echo site_url($listing_uri.'/'.$listing_item->property_id.'/'.$listing_item->lang_code); ?>" class="btn"><i class="icon-search"></i><?php echo lang_check('Open');?></a>
                                        </td>
                                        <td><?php echo btn_delete('ffavorites/myfavorites_delete/'.$lang_code.'/'.$listing_item->id)?></td>
                                    
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang_check('We could not find any');?></td>
                                    </tr>
                        <?php endif;?>           
                      </tbody>
                    </table>

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