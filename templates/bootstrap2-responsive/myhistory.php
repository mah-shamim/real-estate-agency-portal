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
            <h2 id="content"><?php echo lang_check('My History');?></h2>
            <div class="property_content">
                <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo lang_check('Property');?></th>
                            <th><?php echo lang_check('Date');?></th>
                            <th class="control"><?php echo lang_check('Open');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($historyads)): foreach($historyads as $historyads_item):?>
                                    <tr>
                                        <td><?php echo $historyads_item->id; ?></td>
                                        <td><?php echo $properties[$historyads_item->listing_id]; ?></td>
                                        <td><?php echo $historyads_item->date; ?></td>
                                        <td>
                                        <a href="<?php echo site_url($listing_uri.'/'.$historyads_item->listing_id.'/'.$lang_code); ?>" class="btn"><i class="icon-search"></i><?php echo lang_check('Open');?></a>
                                        </td>
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