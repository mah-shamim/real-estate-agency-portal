<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('Visits')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang_check('View all visits')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang_check('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/visits')?>"><?php echo lang_check('Visits')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Visits')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                      <div class="form-group">
                          <?php echo form_dropdown_ajax('property_id', 'estate_m',set_value_GET('property_id', '', true), 'address', $content_language_id);?>
                      </div>
                      <div class="form-group">
                          <label for="not_expired"><?php echo form_checkbox('not_expired', 1,  set_value_GET('not_expired', '', true),'class="" id="not_expired"'); ?> <span style="margin-right: 10px;"> <?php echo lang_check('Not expired'); ?></span> </label> 
                      </div>
                      <div class="form-group">
                          <label for="not_confirmed"><?php echo form_checkbox('not_confirmed', 1,  set_value_GET('not_confirmed', '', true),'class="" id="not_confirmed"'); ?> <span style="margin-right: 10px;"> <?php echo lang_check('Not confirmed'); ?></span> </label> 
                      </div>
                      <button type="submit" class="btn btn-default"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>

                    </form>
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <?php if($this->session->flashdata('message')):?>
                    <p class="label label-success validation"><?php echo $this->session->flashdata('message')?></p>
                    <?php endif;?>
                    
                    <?php echo form_open('admin/visits/delete_multiple', array('class' => '', 'style'=> 'padding:0px;margin:0px;', 'role'=>'form'))?> 
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                            <th data-hide="phone,tablet"><?php echo lang_check('Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Client');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Message');?></th>
                            <th data-hide="phone,tablet"><?php echo lang_check('Listing');?></th>
                            <th class="control"><?php echo lang_check('Edit');?></th>
                            <th class="control"><?php echo lang_check('Delete');?></th>
                            <?php if(check_acl('visits/delete_multiple')):?>
                            <th data-hide="phone" class="control">
                            <button type="submit" onclick="return confirm('<?php _l('Are you sure?'); ?>');" class="btn btn-xs btn-warning"><i class="icon-remove"></i></button>
                            </th>
                            <?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($visits)): foreach($visits as $listing):?>
                                    <tr>
                                    	<td><?php echo anchor('admin/visits/edit/'.$listing->id, $listing->date_visit)?>&nbsp;&nbsp;
                                            <?php echo (empty($listing->date_canceled) && $listing->date_confirmed == 0)? '<span class="label label-warning">'.lang_check('Not confirmed').'</span>':''?>
                                            <?php echo !empty($listing->date_canceled)? '<span class="label label-info">'.lang_check('Canceled').'</span>':''?>
                                        </td>
                                        <td>
                                            <?php 
                                            if(empty($listing->client_id))
                                                    echo '-';
                                            else
                                                    echo anchor('admin/user/edit/'.$listing->client_id, $listing->client_id.'.'.$listing->client_name_surname);
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
                                    	<td><?php echo btn_edit('admin/visits/edit/'.$listing->id)?></td>
                                    	<td><?php echo btn_delete('admin/visits/cancele/'.$listing->id)?></td>
                                        <?php if(check_acl('visits/delete_multiple')):?>
                                            <td>
                                            <?php echo form_checkbox('delete_multiple[]', $listing->id, FALSE); ?>
                                            </td>
                                        <?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="10"><?php echo lang_check('We could not find any messages')?></td>
                                    </tr>
                        <?php endif;?>                   
                      </tbody>
                    </table>
                    <?php echo form_close()?>
                    <div style="text-align: center;"><?php echo $pagination; ?></div>
                  </div>
                </div>
            </div>
          </div>
        </div>
</div>