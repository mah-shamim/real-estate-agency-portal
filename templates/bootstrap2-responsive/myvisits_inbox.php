<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <link href="assets/js/footable/css/footable.core.css" rel="stylesheet">  
    <script src="assets/js/footable/js/footable.js"></script>
    <script>
    $(document).ready(function(){
        $('.footable').footable();
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
            <a id="content"></a>
            <h2><?php echo lang_check('My visits (inbox)');?></h2>
            <div class="property_content">
                <div class="widget-controls clearfix"> 
                    <?php echo anchor('frontend/myvisits/'.$lang_code.'#content', lang_check('My submited visits (outbox)'), 'class="btn btn-info"')?>
                </div>
                <div class="widget-content">
                    <?php if($this->session->flashdata('error')):?>
                    <p class="alert alert-error"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <table class="table table-striped footable">
                        <thead>
                        <tr>
                            <th data-hide="phone,tablet"><?php echo lang_check('Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Client');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Message');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Listing');?></th>
                            <th class="control"><?php echo lang_check('Edit');?></th>
                            <th class="control"><?php echo lang_check('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($visits)): foreach($visits as $listing):?>
                            <tr>
                                <td><?php echo anchor('frontend/edit_visit/'.$lang_code.'/'.$listing->id, $listing->date_visit)?>&nbsp;&nbsp;
                                    <?php echo (empty($listing->date_canceled) && $listing->date_confirmed == 0)? '<span class="label label-warning">'.lang_check('Not confirmed').'</span>':''?>
                                    <?php echo !empty($listing->date_canceled)? '<span class="label label-info">'.lang_check('Canceled').'</span>':''?>
                                </td>
                                <td>
                                    <?php 
                                    if(empty($listing->client_id))
                                            echo '-';
                                    else
                                        echo anchor('profile/'.$listing->client_id.'/'.$lang_code, $listing->client_id.'.'.$listing->client_name_surname);
                                    ?>
                                </td>
                                <td><?php echo word_limiter(strip_tags($listing->message), 5);?></td>
                                <td>
                                    <?php 
                                    if(empty($listing->property_id))
                                            echo '-';
                                    else
                                        echo  anchor($listing_uri.'/'.$listing->property_id.'/'.$lang_code, '#'.$listing->property_id.', '._ch($listing->p_address));
                                    ?>
                                </td>
                                <td>
                                    <?php if(empty($listing->date_canceled)):?>
                                        <?php echo btn_edit('frontend/edit_visit/'.$lang_code.'/'.$listing->id)?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php if(empty($listing->date_canceled)):?>
                                        <?php echo btn_delete('frontend/cancel_visit/'.$lang_code.'/'.$listing->id)?>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="10"><?php echo lang_check('We could not find any messages')?></td>
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