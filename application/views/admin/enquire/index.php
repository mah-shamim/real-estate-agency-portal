<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Enquires')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang('View all enquires')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/enquire')?>"><?php echo lang('Enquires')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
            <div class="row">
                <div class="col-md-12"> 
                    <?php echo anchor('admin/enquire/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang('Add a enquire'), 'class="btn btn-primary"')?>
                </div>
            </div>
          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Enquires')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                      <div class="form-group">
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('smart_search_enquire'); ?>" type="text" />
                      </div>
                      <div class="form-group">
                     <input class="form-control" name="message" id="message" value="<?php echo set_value_GET('message', '', true); ?>" placeholder="<?php echo lang_check('Message part'); ?>" type="text" />
                      </div>
                      <div class="form-group">
                          <label for="readed_to"><?php echo form_checkbox('readed_to', 1,  set_value_GET('readed_to', '', true),'class="" id="readed_to"'); ?> <span style="margin-right: 10px;"> <?php echo lang_check('Unreaded'); ?></span> </label> 
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
                    
                    <?php echo form_open('admin/enquire/delete_multiple', array('class' => '', 'style'=> 'padding:0px;margin:0px;', 'role'=>'form'))?> 
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th><?php echo lang('Date');?></th>
                            <th data-hide="phone,tablet"><?php echo lang('Mail');?></th>
                            <th data-hide="phone,tablet"><?php echo lang('Message');?></th>
                            <th data-hide="phone,tablet"><?php echo lang('Estate');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>
                        	<th class="control"><?php echo lang('Delete');?></th>
                            <?php if(check_acl('enquire/delete_multiple')):?>
                            <th data-hide="phone" class="control">
                            <button type="submit" onclick="return confirm('<?php _l('Are you sure?'); ?>');" class="btn btn-xs btn-warning"><i class="icon-remove"></i></button>
                            </th>
                            <?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($enquires)): foreach($enquires as $enquire):?>
                                    <tr>
                                    	<td><?php echo anchor('admin/enquire/edit/'.$enquire->id, $enquire->date)?>&nbsp;&nbsp;<?php echo $enquire->readed == 0? '<span class="label label-warning">'.lang('Not readed').'</span>':''?></td>
                                        <td><?php echo $enquire->mail?></td>
                                        <td><?php echo word_limiter(strip_tags($enquire->message), 5);?></td>
                                        <td><?php 
                                        if(empty($enquire->property_id))
                                        	echo '-';
                                        else
                                        	echo '#'.$enquire->property_id.', '._ch($enquire->p_address);
                                        ?></td>
                                    	<td><?php echo btn_edit('admin/enquire/edit/'.$enquire->id)?></td>
                                    	<td><?php echo btn_delete('admin/enquire/delete/'.$enquire->id)?></td>
                                        <?php if(check_acl('enquire/delete_multiple')):?>
                                            <td>
                                            <?php echo form_checkbox('delete_multiple[]', $enquire->id, FALSE); ?>
                                            </td>
                                        <?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="10"><?php echo lang('We could not find any messages')?></td>
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
          <?php if(sw_count($maskings) && config_db_item('agent_masking_enabled') === TRUE): ?>
          <div class="row">

            <div class="col-md-12">

                <div class="widget wblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Masking submissions')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th><?php _l('Date');?></th>
                            <th data-hide="tablet"><?php _l('Name and surname');?></th>
                            <th data-hide="phone,tablet"><?php _l('Mail');?></th>
                            <th data-hide="phone,tablet"><?php _l('Phone');?></th>
                            <th data-hide="phone,tablet"><?php _l('Visitor type');?></th>
                            <th data-hide="phone,tablet"><?php _l('Allow contact');?></th>
                            <th data-hide="phone,tablet"><?php _l('Estate');?></th>
                        	<th class="control"><?php echo lang('Delete');?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($maskings)): foreach($maskings as $item):?>
                                    <tr>
                                    	<td><?php echo $item->date_submit; ?></td>
                                        <td><?php echo $item->name?></td>
                                        <td><?php if(!empty($item->email)): ?><a href="mailto:<?php echo $item->email?>"><?php echo $item->email?></a><?php endif; ?></td>
                                        <td><?php echo $item->phone?></td>
                                        <td><?php echo $item->visitor_type?></td>
                                        <td>
                                        <?php echo $item->allow_contact == 0? '<i class="icon-remove"></i>':'<i class="icon-ok"></i>'?>
                                        </td>
                                        <td><a target="_blank" href="<?php echo site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$item->property_id); ?>"><?php echo '#'.$item->property_id; ?></a></td>
                                    	<td><?php echo btn_delete('admin/enquire/delete_masking/'.$item->id)?></td>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="10"><?php echo lang('We could not find any messages')?></td>
                                    </tr>
                        <?php endif;?>                   
                      </tbody>
                    </table>

                  </div>
                </div>
            </div>
          </div>
        <?php endif;?>
      </div>
</div>