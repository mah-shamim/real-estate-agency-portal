<div class="page-head">
    <!-- Page heading -->
      <h2 class="pull-left"><?php echo lang_check('File editor')?>
          <!-- showroom meta -->
          <span class="page-meta"><?php echo lang_check('Edit file').' "' . $filename.'"'?></span>
        </h2>

    <!-- Breadcrumb -->
    <div class="bread-crumb pull-right">
      <a href="<?php echo site_url('admin')?>"><i class="icon-home"></i> <?php echo lang('Home')?></a> 
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread" href="<?php echo site_url('admin/settings/template')?>"><?php echo lang('Settings')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="" href="<?php echo site_url('admin/templatefiles')?>"><?php echo lang_check('Template files list')?></a>
      <!-- Divider -->
      <span class="divider">/</span> 
      <a class="bread-current" href="#"><?php echo lang_check('Edit file')?></a>
    </div>
    
    <div class="clearfix"></div>

</div>

<div class="matter">
        <div class="container">

          <div class="row">

            <div class="col-md-12">


              <div class="widget wblue">
                
                <div class="widget-head">
                  <div class="pull-left"><?php echo lang_check('File editor')?></div>
                  <div class="widget-icons pull-right">
                    <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> 
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">
                    <div style='margin: 0 10px;'>
                        <?php if(stripos($file_content,'_generate_results_item')):?>
                        <p class="label label-info validation" style='margin-bottom: 10px !important;'>
                            "_generate_results_item()" <?php echo lang_check('Available for edit results in this file,');?> <a href="<?php echo site_url('/admin/templatefiles/edit/results_item.php/widgets');?>" target="_blank"><?php echo lang_check('Edit results');?></a>
                        </p>
                        <p class="label label-info validation" style='margin-bottom: 10px !important;'>
                            <?php 
                                $this->load->model('customtemplates_m');
                                $listing_selected = array();
                                $listing_selected['theme'] = $settings['template'];
                                $listing_selected['type'] = 'RESULT_ITEM';
                                $listings_item = $this->customtemplates_m->get_by($listing_selected, FALSE, NULL, NULL, NULL);
                                
                                $link = site_url('/admin/templates/edit_item');
                                if($listings_item){
                                    $link = site_url('/admin/templates/edit_item/'.$listings_item[0]->id);
                                }
                                
                            ?>
                            <?php echo lang_check('Visual edit result item');?> <a href="<?php echo $link;?>" target="_blank"><?php echo lang_check('Edit result item');?></a>
                        
                        </p>
                        <?php endif;?>
                    </div>
                    <?php
                    $widgets = array();
                    preg_match_all('/_widget\((.*?)\)/is', $file_content, $matches);
                    if($matches && isset($matches[1])){
                        foreach ($matches[1] as $key => $value) {
                            $value= trim($value, "'");
                            $value= trim($value, '"');
                            $widgets[] = $value;
                        }
                    }
                    
                    ?>
                    <?php if(!empty($widgets)):?>
                    <div style='margin: 0 10px;'>
                        <?php foreach ($widgets as $key => $value):?>
                        <p class="label label-info validation" style='margin-bottom: 10px !important;'>
                           <?php echo lang_check('Widget');?> <b>"_widget('<?php _che($value);?>')"</b> <a href="<?php echo site_url('/admin/templatefiles/edit/'._ch($value).'.php/widgets');?>" target="_blank"><?php echo lang_check('Edit widget');?></a>
                        </p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif;?>
                      
                    <?php
                    $strings = array();
                    preg_match_all('/\_l\((.*?)\)/is', $file_content, $matches);
                    if($matches && isset($matches[1])){
                        foreach ($matches[1] as $key => $value) {
                            $value= trim($value, "'");
                            $value= trim($value, '"');
                            $strings[] = $value;
                        }
                    }
                    preg_match_all('/lang_check\((.*?)\)/is', $file_content, $matches);
                    if($matches && isset($matches[1])){
                        foreach ($matches[1] as $key => $value) {
                            $value= trim($value, "'");
                            $value= trim($value, '"');
                            $strings[] = $value;
                        }
                    }
                    /*
                    preg_match_all('/{lang_(.*?)}/is', $file_content, $matches);
                    if($matches && isset($matches[1])){
                        foreach ($matches[1] as $key => $value) {
                            $value= trim($value, "'");
                            $value= trim($value, '"');
                            $strings[] = $value;
                        }
                    }*/
                    
                    $strings = array_unique($strings);
                    
                    /* widget-options */
                    $widget_options_str = '';
                    $widget_options = array();
                    if ( preg_match( '|Widget-options:(.*)$|mi', $file_content, $name )) {
                       $widget_options_str = trim($name[1]);
                    }
                    if(!empty($widget_options_str))
                        $widget_options = explode(',', $widget_options_str);
                    /* end widget-options */
                    
                    ?>
                    <?php echo form_open(NULL, array('class' => 'form-horizontal', 'role'=>'form'))?>         
                    
                    <?php if(!empty($strings)):?>
                        <p class="label label-info validation">
                           <?php echo lang_check('You can change text for language');?> "<?php _che($content_language_code);?>"
                        </p>
                        <br/>
                        
                        <?php foreach ($strings as $key => $value):?>
                            <?php
                            $anchor = url_title_cro('label_'.$value);
                            ?>
                            <div class="form-group row">
                              <label class="col-lg-3 control-label control-label-right"><?php echo $value; ?></label>
                              <div class="col-lg-9">
                                <?php 

                                $translated_value = $value;

                                if(isset($language_translations_content[$value])) {
                                    $translated_value = $language_translations_content[$value];
                                }

                                echo form_input('lang[\''.$value.'\']', $translated_value, 'class="form-control" id="inputAddress" placeholder="'.$translated_value.'"')?>
                              </div>
                            </div>
                        <?php endforeach; ?>
                        <?php
                            /* widget-options */
                            if(!empty($widget_options)):
                                
                                $CI = &get_instance();
                                $CI->load->model('language_m');
                                $CI->load->model('page_m');
                                $CI->load->model('widgetoptions_m');
                                $CI -> load -> model('customtemplates_m');
                                  
                                $filename_o = str_replace('.php', '', $filename);
                                
                                $def_wid_options = array();
                                $CI->load->model('widgetoptions_m');
                                
                                $def_wid_options = $CI ->widgetoptions_m->get_widget_options_lang($filename_o, $this->data['settings']['template']);
                                        
                                $languages = $CI->language_m->get();
                                $pages_widget = array();
                                
                                    $pages  = $CI->page_m->get_lang();
                                    foreach ($pages as $key => $value) {
                                        
                                        /* visual template */
                                        if(substr($value->template, 0, 7) == 'custom_'){ 
                                            $template_id = substr($value->template, 7)  ;  
                                            $template_data = $CI->customtemplates_m->get($template_id);
                                            if(!empty($template_data) && is_object($template_data)){
                                                if(strpos($template_data->widgets_order, $filename_o) !==FALSE){
                                                    $pages_widget[$value->page_id]='#'.$value->page_id.', '.$value->title;
                                                }
                                            }
                                        }
                                         /* static template */
                                        elseif(file_exists(FCPATH.'/templates/'.$this->data['settings']['template'].'/'.$value->template.'.php')){
                                            $content = file_get_contents(FCPATH.'/templates/'.$this->data['settings']['template'].'/'.$value->template.'.php');
                                            
                                            if(strpos($content, $filename_o) !==FALSE){
                                                $pages_widget[$value->page_id]='#'.$value->page_id.', '.$value->title;
                                            }
                                        }
                                    }
                                if(!empty($pages_widget))   
                                foreach ($widget_options as $widget_option):
                                    
                            ?>
                                <h6><?php echo lang_check('Widget option').' <b>'.$widget_option.'</b>';?>:</h6>
                                <div style="margin-top: 10px;" class="tabbable">
                                  <ul class="nav nav-tabs">
                                    <?php $i=0;foreach($languages as $lang):$i++;?>
                                    <li class="<?php echo $i==1?'active':''?> lang"><a data-toggle="tab" href="#<?php echo $widget_option.'_'.$lang->id?>"><?php echo $lang->language?></a></li>
                                    <?php endforeach;?>
                                  </ul>
                                    <div style="padding-top: 9px; border-bottom: 1px solid #ddd;" class="tab-content">
                                    <?php $i=0;foreach($languages as $lang):$i++;?>
                                    <div id="<?php echo $widget_option.'_'.$lang->id?>" class="tab-pane <?php echo $i==1?'active':''?>">
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"><?php echo lang_check('Page');?></label>
                                          <label class="col-lg-9" style="padding-top: 7px;margin-top: 0;margin-bottom: 0;"><?php echo lang_check('Option value');?></label>
                                        </div>
                                        <?php foreach ($pages_widget as $key => $page):?>
                                        <div class="form-group">
                                          <label class="col-lg-3 control-label"><?php echo $page?></label>
                                          <div class="col-lg-9">
                                              <input type="text" value='<?php _che($def_wid_options[$widget_option.'_'.$key.'_'.$lang->id],'');?>' name="widget_options[<?php echo $widget_option;?>][<?php echo $key;?>][<?php echo $lang->id;?>]" placeholder="<?php echo lang_check('default value');?>" class="form-control">
                                          </div>
                                        </div>
                                        <?php endforeach;?>

                                    </div>
                                    <?php endforeach;?>
                                    </div>
                                </div>
                        <?php
                            endforeach;
                            endif;
                            /* end widget-options */
                        ?>
                        
                        
                        <div class="form-group">
                            <div class="col-lg-offset-4 col-lg-8">
                              <?php echo form_submit('submit', lang_check('Change'), 'class="btn btn-primary"')?>
                            </div>
                        </div>
                    <?php endif;?>
                    
                    <?php echo validation_errors()?>
                    <?php if($this->session->flashdata('message')):?>
                    <?php echo $this->session->flashdata('message')?>
                    <?php endif;?>
                    <?php if($this->session->flashdata('error')):?>
                    <p class="label label-important validation"><?php echo $this->session->flashdata('error')?></p>
                    <?php endif;?>
                    <hr />
                    <!-- Form starts.  -->
                                
                                <div class="form-group">
                                  <label class="col-lg-2 control-label"><?php echo lang_check('File content')?></label>
                                  <div class="col-lg-10">
                                    <?php 
                                    echo form_textarea('file_content', $file_content, 'placeholder="'.lang_check('File content').'" rows="20" style="height:800px;width:100%;" class="form-control" id="file_content"')?>
                                  </div>
                                </div>   

                                <div class="form-group">
                                  <div class="col-lg-offset-2 col-lg-10">
                                    <?php echo form_submit('submit', lang('Save'), 'class="btn btn-primary"')?>
                                    <a href="<?php echo site_url('admin/templatefiles')?>" class="btn btn-default" type="button"><?php echo lang('Cancel')?></a>
                                  </div>
                                </div>
                       <?php echo form_close()?>
                  </div>
                </div>
              </div>  
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('admin-assets/js/codemirror/lib/codemirror.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('admin-assets/js/codemirror/lib/codemirror.css'); ?>">
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/javascript/javascript.js'); ?>"></script>

<script src="<?php echo base_url('admin-assets/js/codemirror/addon/edit/matchbrackets.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/htmlmixed/htmlmixed.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/xml/xml.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/css/css.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/clike/clike.js'); ?>"></script>
<script src="<?php echo base_url('admin-assets/js/codemirror/mode/php/php.js'); ?>"></script>

<script type="text/javascript">
$(function() {

    var editor = CodeMirror.fromTextArea(document.getElementById("file_content"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "<?php 
        if(substr($filename,-3) == 'css')
        {
            echo 'css';
        }
        elseif(substr($filename,-3) == 'php')
        {
            echo 'application/x-httpd-php';
        }
        elseif(substr($filename,-3) == '.js')
        {
            echo 'javascript';
        }
        
        ?>",
        indentUnit: 4,
        indentWithTabs: true
    });
});
</script>

<style>
.CodeMirror {
  border: 1px solid #eee;
  height: 600px;
}
</style>
