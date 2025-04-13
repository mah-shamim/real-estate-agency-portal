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
            <h2 id="content">{lang_Myresearch}</h2>
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
                            <th><?php echo lang_check('Parameters');?></th>
                            <th><?php echo lang_check('Lang code');?></th>
                            <th><?php echo lang_check('Activated');?></th>
                            <?php if(false): ?><th class="control"><?php echo lang_check('Load');?></th><?php endif;?>
                        	<th class="control"><?php echo lang_check('Edit');?></th>
                        	<th class="control"><?php echo lang_check('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($listings)): foreach($listings as $listing_item):?>
                                    <tr>
                                        <td><?php echo $listing_item->id; ?></td>
                                        <td>
                                        <?php
                                        
                                        $parameters = json_decode($listing_item->parameters);
                                        
                                        foreach($parameters as $key=>$value){
                                            if(!empty($value) && $key != 'view' && $key != 'order')
                                            echo '<span><span class="par_key">'.lang_check($key).'</span>: <b class="par_value">'.$value.'</b></span><br />';
                                        }
                    
                                        ?>
                                        </td>
                                        <td><?php echo '['.strtoupper($listing_item->lang_code).']'; ?></td>
                                        <td>
                                            <?php echo $listing_item->activated?'<i class="icon-ok"></i>':'<i class="icon-remove"></i>'; ?>
                                        </td>
                                        <?php if(false): ?>
                                        <td>
                                        <?php if($lang_code == $listing_item->lang_code): ?>
                                        <button class="load_search btn"><i class="icon-search"></i></button>
                                        <?php else: ?>
                                        <?php echo '->'.strtoupper($listing_item->lang_code).'<-'; ?>
                                        <?php endif; ?>
                                        </td>
                                        <?php endif;?>
                                    	<td><?php echo btn_edit('fresearch/myresearch_edit/'.$lang_code.'/'.$listing_item->id.'#content')?></td>
                                    	<td><?php echo btn_delete('fresearch/myresearch_delete/'.$lang_code.'/'.$listing_item->id)?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
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