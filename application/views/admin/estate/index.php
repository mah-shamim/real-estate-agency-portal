<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang('Estates')?>
      <!-- page meta -->
      <span class="page-meta"><?php echo lang('View all estates')?></span>
    </h2>
    
    
    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="<?php echo site_url('admin/estate')?>"><?php echo lang('Estates')?></a>
    </div>
    
    <div class="clearfix"></div>
</div>

<style>
    .widget .table-bordered td {
        vertical-align: middle;
    }
</style>

<div class="matter">
        <div class="container">
        
        <div class="row">
            <div class="col-md-12"> 
                
                <?php echo anchor('admin/estate/edit', '<i class="icon-plus"></i>&nbsp;&nbsp;'.lang('Add a estate'), 'class="btn btn-primary"')?>
               
                <?php if(
                        (config_db_item('csv_import_enabled') == TRUE && ($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN')) ||
                        (config_db_item('import_foursquare') == TRUE) ||
                        (config_db_item('import_google_places') == TRUE) ||
                        (file_exists(APPPATH.'libraries/Xml2u.php') && $this->session->userdata('type')=='ADMIN') || 
                        (file_exists(APPPATH.'libraries/Eventful.php') && $this->session->userdata('type')=='ADMIN')
                        ):?>
                <div class="dropdown pull-right hidden-emptydropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownImport" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-arrow-down"></i>&nbsp;&nbsp;<?php echo _l('Imports');?>
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownImport">
                        <?php if(config_db_item('csv_import_enabled') == TRUE && ($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN')): ?>
                           <li> <?php echo anchor('admin/estate/import_csv/', lang_check('Import from CSV'), '')?></li>
                        <?php endif;?>
                        <?php if(config_db_item('import_foursquare') == TRUE): ?>
                            <li><?php echo anchor('admin/estate/import_foursquare/', lang_check('Import from foursquare'), 'class=""')?></li>
                        <?php endif;?>
                        <?php if(config_db_item('import_google_places') == TRUE): ?>
                            <li><?php echo anchor('admin/estate/import_google_places/', lang_check('Import from google places'), 'class=""')?></li>
                        <?php endif;?>
                        <?php if(file_exists(APPPATH.'libraries/Xml2u.php') && $this->session->userdata('type')=='ADMIN'): ?>
                            <li><?php echo anchor('admin/estate/import_xml2u/', lang_check('Import from xml2u'), '')?></li>
                        <?php endif;?>    
                        <?php if(file_exists(APPPATH.'libraries/Eventful.php') && $this->session->userdata('type')=='ADMIN'): ?>
                            <li><?php echo anchor('admin/estate/import_eventful/', lang_check('Import from eventful'), '')?></li>
                        <?php endif;?>  
                    </ul>
                </div>
                <?php endif;?>
                
                <?php if(
                    (config_db_item('csv_import_enabled') == TRUE && ($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN')) ||
                    (file_exists(APPPATH.'libraries/Xml2u.php') && $this->session->userdata('type')=='ADMIN')
                    ):?>
                <div class="dropdown pull-right hidden-emptydropdown" style="margin-right:10px;">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownExport" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="icon-arrow-up"></i>&nbsp;&nbsp;<?php echo _l('Exports');?>
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownExport">
                        <?php if(config_db_item('csv_import_enabled') == TRUE && ($this->session->userdata('type') == 'ADMIN' || $this->session->userdata('type') == 'AGENT_ADMIN')): ?>
                            <li><?php echo anchor('admin/estate/export_csv/', lang_check('Export CSV'), '')?></li>
                        <?php endif;?>
                        <?php if(file_exists(APPPATH.'libraries/Xml2u.php') && $this->session->userdata('type')=='ADMIN'): ?>
                            <li> <?php echo anchor('api/xml2u/', lang_check('Export xml2u'), '')?></li>
                        <?php endif;?>    
                    </ul>
                </div>
                <?php endif;?>
                
            </div>
        </div>

          <div class="row">

            <div class="col-md-12">

                <div class="widget wlightblue">

                <div class="widget-head">
                  <div class="pull-left"><?php echo lang('Estates')?> <?php _che($total_rows);?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content">
                  
                    <form class="search-admin form-inline" action="<?php echo site_url($this->uri->uri_string()); ?>" method="GET" autocomplete="off">

                      <div class="form-group">
                        <input class="form-control" name="smart_search" id="smart_search" value="<?php echo set_value_GET('smart_search', '', true); ?>" placeholder="<?php echo lang_check('Smart item search'); ?>" type="text" />
                      </div>
                      <div class="form-group">
                        <?php echo form_dropdown('field_4', $this->option_m->get_field_values($content_language_id, 4, lang_check('Purpose')), set_value_GET('field_4', '', true), 'class="form-control"'); ?>
                      </div>
                      <div class="form-group">
                        <?php if($settings['template'] == 'local' || $settings['template'] == 'selio'):?> 
                            <?php
                            $tree_field_id = 79;
                            $values = array();
                            $CI = &get_instance();
                            $this->load->model('treefield_m');
                            $this->load->model('file_m');
                            $check_option = $CI->treefield_m->get_lang(NULL, FALSE, $content_language_id);
                            foreach ($check_option as $key => $value) {
                                if($value->field_id==$tree_field_id){
                                    $values['"field_79":"'.$value->value_path]= $value->value_path;
                                }
                            }
                            ?>
                            <?php echo form_dropdown('json_object', array_merge(array(''=>lang_check('Category')),$values), set_value_GET('json_object', '', true), 'class="form-control"'); ?>
                        <?php else:?>
                            <?php echo form_dropdown('field_2', $this->option_m->get_field_values($content_language_id, 2, lang_check('Type')), set_value_GET('field_2', '', true), 'class="form-control"'); ?>
                        <?php endif;?>
                      
                      </div>
                      <?php if($this->session->userdata('type') == 'ADMIN'):?>
                      <div class="form-group">
                        <input class="form-control" name="name_surname" id="name_surname" value="<?php echo set_value_GET('name_surname', '', true); ?>" placeholder="<?php echo lang_check('Agent name'); ?>" type="text" />
                      </div>
                     <?php endif;?>
                      <button type="submit" class="btn btn-default"><i class="icon icon-search"></i>&nbsp;&nbsp;<?php echo lang_check('Search'); ?></button>

                    </form>
                  
                    <?php if(isset($_GET['subcription_id_created'])): ?>
                    <p class="label label-info validation"><?php echo lang_check('Thank you very much, subscription created and will be activated soon: '); ?><?php echo $_GET['subcription_id_created']; ?></p>
                    <?php endif; ?>

                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <?php echo form_open('admin/estate/delete_multiple', array('class' => '', 'style'=> 'padding:0px;margin:0px;', 'role'=>'form'))?> 
                    <table class="table table-bordered footable">
                      <thead>
                        <tr>
                            <th style='white-space:nowrap'># <?php echo field_order_by($field_title = 'property.id');?></th>
                            <th data-hide="phone"><?php echo lang_check('Picture');?></th>
                            <th><?php echo lang('Address');?>
                                <?php echo field_order_by($field_title = 'property.address');?>
                            </th>
                            <th data-hide="phone"><?php echo lang('Agent');?></th>
                            <!-- Dynamic generated -->
                            <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                            <th data-hide="phone,tablet"><?php echo $row->option?></th>
                            <?php endforeach;?>
                            <!-- End dynamic generated -->
                            <th data-hide="phone"><?php echo lang_check('Views');?> <?php echo field_order_by($field_title = 'property.counter_views');?></th>
                            <th data-hide="phone"><?php echo lang_check('Preview');?></th>
                        	<th class="control"><?php echo lang('Edit');?></th>

                            <?php if(config_item('status_enabled') === TRUE && 
                                        ($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE' || 
                                         $this->session->userdata('type') == 'ADMIN'
                                        )):?>
                            <th class="control"><?php _l('Status');?></th>
                            <?php elseif(check_acl('estate/delete')):?><th class="control"><?php echo lang('Delete');?></th>
                            <?php endif;?>
                            
                            <?php if(check_acl('estate/delete_multiple')):?>
                            <th data-hide="phone" class="control">
                            <button type="submit" onclick="return confirm('<?php _l('Are you sure?'); ?>');" class="btn btn-xs btn-warning"><i class="icon-remove"></i></button>
                            </th>
                            <?php endif;?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(sw_count($estates)): foreach($estates as $estate):?>
                                    <tr>
                                    	<td>
                                                    
                                            <?php echo $estate->id?>
                                        
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <?php if(!empty($estate->image_filename) && file_exists(FCPATH.'files/thumbnail/'.$estate->image_filename)):?>
                                            <a class="preview" target="_blank" href="<?php echo site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$estate->id);?>">
                                                <img src="<?php echo _simg('files/thumbnail/'.$estate->image_filename, '60x60');?>" width="60px" height="60px" alt=""/>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tooltip_preview">
                                            <?php if(false && !empty($estate->image_filename) && file_exists(FCPATH.'files/thumbnail/'.$estate->image_filename)):?>
                                            <a class="tooltipbox" target="_blank" href="<?php echo site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$estate->id);?>">
                                                <img src="<?php echo _simg('files/thumbnail/'.$estate->image_filename, '56x40');?>" width="56px" height="40px" alt=""/>
                                            </a>
                                            <?php endif; ?>
                                            <?php echo anchor('admin/estate/edit/'.$estate->id, _ch($estate->address) )?>
                                            <?php if($estate->is_activated == 0):?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-danger"><?php echo lang_check('Not Activated')?></span>
                                            <?php endif;?>
                                            <?php if(isset($settings['listing_expiry_days']) && $settings['listing_expiry_days'] > 0 && strtotime($estate->date_modified) <= time()-$settings['listing_expiry_days']*86400): ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning"><?php echo lang_check('Expired'); ?></span>
                                            <?php endif; ?>
                                            <?php if(!empty($estate->activation_paid_date)):?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-success"><?php echo lang_check('Paid'); ?></span>
                                            <?php endif; ?>
                                            <?php if(!empty($estate->status)):?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"><?php echo $estate->status; ?></span>
                                            <?php endif; ?>
                                            <br style="margin-bottom:10px">
                                            <label style="cursor:pointer;margin-right:7px">
                                              <input type="checkbox" name="is_featured" <?php if(!empty($estate->is_featured)):?> checked="checked" <?php endif;?> data-property_id="<?php echo $estate->id; ?>" class='estate_option_change_event_checkbox' value='1'> <?php echo lang_check('featured');?>
                                            </label>
                                            <label style="cursor:pointer;margin-right:7px">
                                              <input type="checkbox" name="is_activated" <?php if(!empty($estate->is_activated)):?> checked="checked" <?php endif;?> data-property_id="<?php echo $estate->id; ?>" class='estate_option_change_event_checkbox' value='1'>  <?php echo lang_check('activated');?>
                                            </label>
                                            <label style="cursor:pointer;margin-right:7px">
                                              <input type="checkbox" name="is_visible" <?php if(!empty($estate->is_visible)):?> checked="checked" <?php endif;?> data-property_id="<?php echo $estate->id; ?>" class='estate_option_change_event_checkbox' value='1'>  <?php echo lang_check('visible for public');?>
                                            </label>
                                        </td>
                                        <td><?php echo check_set($available_agent[$this->estate_m->get_user_id($estate->id)], '')?></td>
                                        <!-- Dynamic generated -->
                                        <?php foreach($this->option_m->get_visible($content_language_id) as $row):?>
                                        <td>
                                        <?php
                                            $value = $this->estate_m->get_field_from_listing($estate, $row->option_id);
                                            if($row->option_id==79)
                                                $value = trim($value,' - ');
                                            echo $value;
                                        ?>
                                        </td>
                                        <?php endforeach;?>
                                        <!-- End dynamic generated -->
                                        <td><?php echo $estate->counter_views; ?></td>
                                        <td style="white-space: nowrap;">
                                            <a class="btn btn-info" target="_blank" href="<?php echo site_url((config_item('listing_uri')===false?'property':config_item('listing_uri')).'/'.$estate->id.'?preview=true');?>"><i class="icon-search"></i></a>
                                        </td>
                                    	<td><?php echo btn_edit('admin/estate/edit/'.$estate->id)?></td>
                                    	
                            <?php if(config_item('status_enabled') === TRUE && 
                                        ($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE' || 
                                         $this->session->userdata('type') == 'ADMIN'
                                        )):?>
                                     
                            
                                <td>
<?php if(empty($estate->status) || $estate->status == 'REDUCED_PRICE' || $estate->status == 'RESUBMIT'): ?>
<div class="btn-group">
<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo lang_check('Status'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <li>
        <?php if($estate->status == 'RESUBMIT'): ?>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE_RESUBMIT'); ?>">
        <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
        <?php else: ?>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE'); ?>">
        <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
        <?php endif; ?>
    </li>
    <li>
    <?php if($estate->status == 'REDUCED_PRICE'): ?>
    <a class="" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/HOLD_REDUCED/statuses'); ?>">
    <i class="icon-pause"></i> <?php echo lang_check('On hold'); ?></a>
    <?php elseif($estate->status == 'RESUBMIT'): ?>
    <a class="" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/HOLD_RESUBMIT/statuses'); ?>">
    <i class="icon-pause"></i> <?php echo lang_check('On hold'); ?></a>
    <?php else: ?>
    <a class="" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/HOLD/statuses'); ?>">
    <i class="icon-pause"></i> <?php echo lang_check('On hold'); ?></a>
    <?php endif; ?>
    </li>
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/DECLINE'); ?>">
        <i class="icon-remove-sign"></i> <?php echo lang_check('Decline'); ?></a>
    </li>
</ul>
<?php elseif($estate->status == 'HOLD' || $estate->status == 'HOLD_REDUCED'): ?>
<div class="btn-group">
<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo lang_check('Status'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <li>
    <?php if($estate->status == 'HOLD_REDUCED'): ?>
    <a class="" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE_REDUCED/statuses'); ?>">
    <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    <?php else: ?>
    <a class="" href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE/statuses'); ?>">
    <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    <?php endif;?> 
    </li>
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/CONTRACT'); ?>">
        <i class="icon-briefcase"></i> <?php echo lang_check('Contract property'); ?></a>
    </li>
</ul>
<?php elseif($estate->status == 'HOLD_ADMIN'): ?>
<div class="btn-group">
<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
<?php echo lang_check('Status'); ?>
<span class="caret"></span>
</a>
<ul class="dropdown-menu">
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/APPROVE'); ?>">
        <i class="icon-ok"></i> <?php echo lang_check('Approve'); ?></a>
    </li>
    <li>
        <a href="<?php echo site_url('admin/estate/status/'.$estate->id.'/DECLINE'); ?>">
        <i class="icon-remove-sign"></i> <?php echo lang_check('Decline'); ?></a>
    </li>
</ul>
<?php endif;?> 
                                </td>
                             
                            
                            <?php elseif(check_acl('estate/delete')):?><td><?php echo btn_delete('admin/estate/delete/'.$estate->id); ?></td>
                            <?php endif;?>
                                        

                                        
                                        <?php if(check_acl('estate/delete_multiple')):?>
                                            <td>
                                            <?php echo form_checkbox('delete_multiple[]', $estate->id, FALSE); ?>
                                            </td>
                                        <?php endif;?>
                                    
                                    </tr>
                        <?php endforeach;?>
                        <?php else:?>
                                    <tr>
                                    	<td colspan="20"><?php echo lang('We could not find any');?></td>
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
          <?php if(config_db_item('disable_faq_for_agent') == TRUE && $this->session->userdata('type') != 'ADMIN'): ?>
          <div class="row">

            <div class="col-md-12">


              <div class="widget worange">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('Frequently asked questions')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                  <div class="widget-content" style="padding: 10px;">
                        <div class="list-group">
                            <a href="http://iwinter.com.hr/support/?p=162" target="_blank" class="list-group-item"><?php echo lang_check('How to add / edit or delete the menu tabs on map ? i.e Sale , Rent and both ?')?></a>
                        </div>
                  </div>

              </div>  

            </div>
          </div>
          <?php endif; ?>
        </div>
</div>
    
    
    
    
    
</section>

<script>

 $('document').ready(function(){
    /* hidden dropdown.hidden-emptydropdown without items */
    $('.hidden-emptydropdown').each(function() {
        if(!$(this).find('ul li').length) {
            $(this).hide();
        }
    })

    /* event send */
    $(".estate_option_change_event_checkbox").on('change',function(e){
        e.preventDefault();
        var selft,property_id;
        self = $(this);
        property_id = $(this).attr('data-property_id');

        var data = [];
        data.push({name: 'property_id', value: property_id});
        if(self.prop('checked'))
          data.push({name: self.attr('name'), value: 1});
        else
          data.push({name: self.attr('name'), value: 0});

        self.addClass('loading');
        $.post("<?php echo site_url('privateapi/estate_option_change/');?>", data, 
          function(data){
              ShowStatus.show(data.message);
          }
        ).success(function(){
          self.removeClass('loading');
        });

        return false;
    });

 })

</script>