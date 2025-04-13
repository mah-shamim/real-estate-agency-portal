<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Users')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang('View all users')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/user')?>"><?php echo lang('Users')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                <?php echo anchor('admin/user/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang('Add a new user'), 'class="btn btn-primary"')?>
                <?php echo anchor('admin/user/export', '<i class="icon-arrow-down"></i>&nbsp;&nbsp;'.lang('Export user list'), 'class="btn btn-info"')?>
                <?php if($this->session->userdata('type') == 'ADMIN' && config_db_item('user_custom_fields_enabled') === TRUE): ?>
                    <?php echo anchor('admin/user/custom_fields/', '<i class="icon-list-alt"></i>&nbsp;&nbsp;'.lang_check('Custom fields'), 'class="btn btn-success pull-right"  style=""')?>
                <?php endif;?>
                <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                    <?php echo anchor('admin/user/import_csv/', ''.lang_check('Import users csv'), 'class="btn btn-success pull-right"  style="margin-right: 7px;"')?>
                <?php endif;?>
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget worange">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Users')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                    
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                      <div class="form-group">
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('Smart agent search'); ?>" type="text" />
                      </div>
                      <div class="form-group">
                        <?php echo form_dropdown('type', array_merge(array(''=>lang_check('Type')),$this->user_m->user_types), set_value_GET('type', '', true), 'class="form-control"'); ?>
                      </div>
                      <button type="submit" class="btn btn-default"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>

                    </form>
                  
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>     
                    <?php echo form_open('admin/user/delete_multiple', array('class' => '', 'style'=> 'padding:0px;margin:0px;', 'role'=>'form'))?> 
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                        	<th><?php echo lang('Username');?></th>
                            <th data-hide="phone,tablet"><?php echo lang('Name and surname');?></th>
                            <th data-hide="phone,tablet"><?php echo lang('Type');?></th>
                            <?php if(config_item('agency_agent_enabled') == TRUE): ?>
                            <th data-hide="phone,tablet"><?php _l('Agency');?></th>
                            <?php endif;?>
                        	<th class="control"><?php echo lang('Edit');?></th>
                            <?php if($this->session->userdata('type') != 'AGENT_ADMIN'): ?>
                        	<th class="control"><?php echo lang('Delete');?></th>
                            <?php endif;?> 
                            <?php if(check_acl('user/delete_multiple')):?>
                                <th data-hide="phone" class="control text-center" >
                                    <button type="submit" onclick="return confirm('<?php _l('Are you sure?'); ?>');" class="btn btn-xs btn-warning"><i class="icon-remove"></i></button>
                                </th>
                            <?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($users)): foreach($users as $user):?>
                                    <tr>
                                    	<td><?php echo anchor('admin/user/edit/'.$user->id, $user->username)?>&nbsp;&nbsp;<?php echo $user->activated == 0? '<span class="label label-warning"><i class="icon-remove"></i></span>':''?></td>
                                        <td>
                                            <?php echo $user->name_surname?>
                                            <?php if(file_exists(APPPATH.'controllers/admin/packages.php')):?>
                                                <?php if($user->type !="ADMIN" && ($user->package_last_payment<date('Y-m-d H:i:s') || $user->package_id == 0)):?>
                                                     <span class="label label-danger"><?php echo lang_check('package expired');?></span>
                                                <?php endif;?>
                                            <?php endif;?>
                                        </td>
                                        <td>
                                            <span class="label label-<?php echo $this->user_m->user_type_color[$user->type]?>">
                                            <?php echo $this->user_m->user_types[$user->type]?>
                                            </span>
                                            <?php if(file_exists(APPPATH.'controllers/admin/expert.php')): ?>
                                            <?php echo (!empty($user->qa_id))?'&nbsp;<span class="label label-info">'.$expert_categories[$user->qa_id].'</span>':''; ?>
                                            <?php endif; ?>
                                        </td>
                                        <?php if(config_item('agency_agent_enabled') == TRUE): ?>
                                        <td data-hide="phone,tablet"><?php 
                                        
                                        $agency_title = $user->agency_name;
                                        
                                        if(!empty($user->agency_custom_fields))
                                        {
                                            $json_decoded = json_decode($user->agency_custom_fields);
                                            
                                            if(isset($json_decoded->cinput_4))
                                                $agency_title = $json_decoded->cinput_4;
                                        }

                                        echo $agency_title; 

                                        ?></td>
                                        <?php endif;?>
                                        
                                        <td>
                                        <?php if($this->session->userdata('type') == 'ADMIN'): ?>
                                    	   <?php echo btn_edit('admin/user/edit/'.$user->id)?>
                                        <?php elseif($this->session->userdata('type') == 'AGENT_ADMIN' && $user->type != 'ADMIN'): ?>
                                            <?php echo btn_edit('admin/user/edit/'.$user->id)?>
                                        <?php endif;?> 
                                        </td>
                                        
                                        <?php if($this->session->userdata('type') != 'AGENT_ADMIN'): ?>
                                    	<td><?php echo btn_delete('admin/user/delete/'.$user->id)?></td>
                                        <?php endif;?> 
                                        <?php if(check_acl('user/delete_multiple')):?>
                                        <td class="text-center">
                                            <?php echo form_checkbox('delete_multiple[]', $user->id, FALSE); ?>
                                        </td>
                                        <?php endif;?>
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="3"><?php _l('We could not find any'); ?></td>
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