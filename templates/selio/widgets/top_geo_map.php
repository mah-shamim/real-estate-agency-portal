<?php
/*
Widget-title: Geo map
Widget-preview-image: /assets/img/widgets_preview/top_geo_map.webp
*/

$CI = &get_instance();
$CI->config->set_item('auto_map_search', TRUE);
?>
<div class="section widget-geomap widget_edit_enabled">
<?php

$CI = & get_instance();
$treefield_id = 64;

$CI->load->model('treefield_m');

// init varibles
$treefields = array();
$tree_listings_default = array();
$tree_list = array();
$tmpfile ='';
$error_svg_widget='';
$widget_fatal_error = false;

$check_option= $CI->option_m->get_by(array('id'=>$treefield_id));
// check if option exists
if(!$check_option)
    $widget_fatal_error = true;

if($check_option[0]->type!='TREE')
    $widget_fatal_error = true;

if(isset($_GET['geo_map_preview']) &&  !empty($_GET['geo_map_preview'])) {
    $geo_map_preview = trim($_GET['geo_map_preview']);
    
    $tmpfile = 'assets/cache/'.$geo_map_preview.'.svg';
    
     if(file_exists(FCPATH.'templates/'.$this->data['settings']['template'].'/assets/svg_maps/'.$geo_map_preview.'.svg')) {
        // get svg
        $svg = file_get_contents(FCPATH.'templates/'.$this->data['settings']['template'].'/assets/svg_maps/'.$geo_map_preview.'.svg');

        $match = '';
        $title = '';
        $root_name ='';
        preg_match_all('/(data-title-map)=("[^"]*")/i', $svg, $match);
        if(!empty($match[2])) {
            $title = trim(str_replace('"', '', $match[2][0]));
            $root_name = trim($title);
        } else if(stristr($svg, "http://amcharts.com/ammap") != FALSE ) {
            $svg = str_replace('title', 'data-name', $svg);
            $match2='';
            preg_match_all('/(SVG map) of ([^"]* -)/i', $svg, $match2);
            if(!empty($match2) && isset($match2[2][0])) {
                $title='';
                $title = str_replace(array(" -","High","Low"), '', $match2[2][0]);
                $title = trim($title);
                $svg = str_replace('<svg', '<svg data-title-map="'.trim($title).'"', $svg);
                $root_name = trim($title);
            }
        }
        // tmp svg map save in cache
        
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false; 
        $dom->formatOutput = true; 
        $dom->loadXml($svg);
        /* set version */
        $root_svg = $dom->getElementsByTagName('svg')->item(0);
        $root_svg->setAttribute('data-sw_geomodule-version', '2.0');
        $paths = $dom->getElementsByTagName('path'); //here you have an corresponding object
            foreach ($paths as $path) {
                $lvl_1 = $path->getAttribute('data-name');
                if($lvl_1 && !empty($lvl_1)){
                    $lvl_1 = trim($lvl_1);
                    $path->setAttribute('data-name-lvl_0', $root_name);
                    $path->setAttribute('data-name-lvl_1', $lvl_1);
                    $path->setAttribute('data-name', $lvl_1.', '.$root_name);
                }
            }     
        $g = $dom->getElementsByTagName('g'); //here you have an corresponding object
            foreach ($g as $path) {
                $lvl_1 = $path->getAttribute('data-name');
                if($lvl_1 && !empty($lvl_1)){
                    $lvl_1 = trim($lvl_1);
                    $path->setAttribute('data-name-lvl_0', $root_name);
                    $path->setAttribute('data-name-lvl_1', $lvl_1);
                    $path->setAttribute('data-name', $lvl_1.', '.$root_name);
                }
            }     
        $svg= $dom->saveXML();
        
        
        if(!empty($title)){
            file_put_contents(FCPATH.'templates/'.$this->data['settings']['template'].'/'.$tmpfile, $svg);

            /* changed emulations arrays */
            $treefields = array();
            $treefields_parent = new stdClass();
            $treefields_parent->id = '1';
            $treefields_parent-> value = $title;
            $treefields_parent-> parent_id = '0';
            $treefields_parent-> body = '';
            $treefields_parent-> repository_id = '';
            preg_match_all('/(data-name-lvl_1)=("[^"]*")/i', $svg, $matches);

            $_k=2;
            $treefields_childs = array();
            if(!empty($matches[2]))
                foreach ($matches[2] as $value) {
                    $value = str_replace('"', '', $value);      
                    $data= new stdClass();;
                    $data->id = $_k;
                    $data->value = $value;
                    $data->parent_id = '1';
                    $data->body = '';
                    $data->repository_id = '';
                    $treefields_childs[$_k] = $data;
                    $_k++;
                }
            $treefields[0][1]=$treefields_parent;
            $treefields[1]=$treefields_childs;
            $tree_listings=$treefields;

            $tree_listings_default= array();
            $tree_listings_default = $treefields_childs;
            $tree_listings_default[1] = $treefields_parent;
        } else {
            
        }
    } 

} // if not demo map preview
else {
    $tree_listings = $CI->treefield_m->get_table_tree($lang_id, $treefield_id, NULL, FALSE, '.order', ',repository_id,value_path');
    $tree_listings_default = $CI->treefield_m->get_table_tree('1', $treefield_id, NULL, true, '.order', ',repository_id');
}

if(empty($tree_listings) || !isset($tree_listings[0]))
    $widget_fatal_error = true;

if(!$widget_fatal_error){
$this->db->select('property_value.value, COUNT(value) as count');

$this->db->join('property_value', 'property.id = property_value.property_id');

$this->db->group_by('property_value.value');  
$this->db->where('option_id', $treefield_id);
$this->db->where('language_id', $lang_id);
$this->db->where('is_activated', 1);
$this->db->where('is_visible', 1);

if(config_db_item('listing_expiry_days') !== FALSE)
{
    if(is_numeric(config_db_item('listing_expiry_days')) && config_db_item('listing_expiry_days') > 0)
    {
        $this->db->where('date_modified >', date("Y-m-d H:i:s" , time() - config_db_item('listing_expiry_days')*86400));
    }
}

$query= $this->db->get('property');


$result_count = array();

if($query){
    $result = $query->result();
    foreach ($result as $key => $value) {
        if(!empty($value->value))
            $result_count[$value->value]= $value->count;
    }
}



$_treefields = $tree_listings[0];

$root_value = '';
$ariesInfo = array();
$treefields = array();
foreach ($_treefields as $val) {
   
    $options = $tree_listings[0][$val->id];
    $treefield = array();
    $field_name = 'value' ;
    $treefield['id'] = $val->id;
    $treefield['title'] = $options->$field_name;
    $tree_list[$val->id] = $tree_listings[0][$val->id];
    if(empty($root_value))
        $root_value = $options->$field_name;
    
    $treefield['title_chlimit'] = character_limiter($options->$field_name, 15);

    $field_name = 'body';
    $treefield['descriotion'] = $options->$field_name;
    $treefield['description_chlimit'] = character_limiter($options->$field_name, 50);
    
    $treefield['count'] = 0;
    if(isset($result_count[$treefield['title'].' -']))
        $treefield['count'] = $result_count[$treefield['title'].' -'];
    
    $treefield['url'] = '';
    
    /* link if have body */
    if(!empty($options->$field_name))
    {
        $href = slug_url('treefield/'.$lang_code.'/'.$options->id.'/'.url_title_cro($options->value), 'treefield_m');
        $treefield['url'] = $href;
    }
    /* end if have body */
    
    $treefield['repository_id'] = $options->repository_id;
    
    $ariesInfo[$tree_listings_default[$val->id]->value]['name']=$treefield['title'];
    $ariesInfo[$tree_listings_default[$val->id]->value]['count']=$treefield['count'];
     
    $childs = array();
    if (isset($tree_listings[$val->id]) && sw_count($tree_listings[$val->id]) > 0)
        foreach ($tree_listings[$val->id] as $key => $_child) {
            $child = array();
            $options = $tree_listings[$_child->parent_id][$_child->id];
            $child['id'] = $_child->id;
            $tree_list[$_child->id] = $options;
            
            $field_name = 'value';
            $child['title'] = $options->$field_name;
            $child['title_chlimit'] = character_limiter($options->$field_name, 10);

            $field_name = 'body';
            $child['descriotion'] = $options->$field_name;
            $child['descriotion_chlimit'] = character_limiter($options->$field_name, 50);
            
            $child['count']= 0;
            if(isset($result_count[$treefield['title'].' - '.$child['title'].' -']))
                $child['count'] = $result_count[$treefield['title'].' - '.$child['title'].' -'];
          
            $child['url'] = '';
            
            /* link if have body */
                if(!empty($options->$field_name))
                {
                    // If slug then define slug link
                    $href = slug_url('treefield/'.$lang_code.'/'.$options->id.'/'.url_title_cro($options->value), 'treefield_m');
                    $child['url'] = $href;
                }
            /* end if have body */
            
            if (isset($tree_listings[$_child->id]) && sw_count($tree_listings[$_child->id]) > 0){
                $parent_title = $treefield['title'].' - '.$child['title'];
                recursion_calc_count($result_count, $tree_listings, $parent_title, $_child->id, $child['count']);
            }       
                
            $childs[] = $child;
            $ariesInfo[$tree_listings_default[$_child->id]->value.', '.$tree_listings_default[$_child->parent_id]->value]['name']=$child['title'].', '.$treefield['title'];
            $ariesInfo[$tree_listings_default[$_child->id]->value.', '.$tree_listings_default[$_child->parent_id]->value]['count']=$child['count'];
            $ariesInfo[$tree_listings_default[$val->id]->value]['count'] += $child['count'];
        }
        
         
    $treefield['childs'] = $childs;
    $treefields[] = $treefield;
}

$CI->load->model('file_m');
$svg_map='';
if(isset($treefields[0]['repository_id']) && !empty($treefields[0]['repository_id'])) {
    $repository = $CI->file_m->get_by(array('repository_id'=>$treefields[0]['repository_id']));
    if($repository){
        $filename = $repository[0]->filename;
        if(!empty($filename) and file_exists(FCPATH.'files/'.$filename))
        {
            $svg_map = base_url('files/'.$filename);
        }
    }
}

}

?>
    
<?php if(!$widget_fatal_error):?>

    
<?php


$CI->data['tree_listings']=$tree_listings;
$CI->data['tree_listings_default']=$tree_listings_default;
$CI->data['treefield_id']=$treefield_id;
$CI->data['ariesInfo']=$ariesInfo;
$CI->data['treefields']=$treefields;
$CI->data['root_value']=$root_value;
$CI->data['tree_list']=$tree_list;
/* dinamic per listing */
sw_add_script('page_js_'.md5(current_url_q()), 'widgets/_generate_top_geo_map_js.php');
?>
    
<?php endif;?>
        
<section class="map-sec">
            <h3 class="vis-hid">Invisible</h3>
            <div class="container">
                <div class="map-details">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="map-city-links">
                            <?php if(!$widget_fatal_error):?>
                            <div class="geo-menu">
                                <div class="geo-menu-breadcrumb"></div>
                                    <ul class="treefield-tags">
                                        <?php foreach ($treefields as $key => $item) : ?>
                                            <li class=''><a href="#<?php echo str_replace(' ', '-', $item['title']); ?>" data-region="<?php _che($item['title']); ?>" data-path-map-origin="<?php _che($tree_listings_default[$item['id']]->value);?>"  data-path-map="<?php _che($item['title']); ?>" data-name-lvl_0="<?php _che($item['title']); ?>" data-path="<?php _che($item['title']); ?> - " data-id='<?php _che($item['id']); ?>'><?php _che($item['title']); ?></a>
                                                <ul class='' id="<?php echo str_replace(' ', '-', $item['title']); ?>">
                                                    <li><a href="#back" class='geo-menu-back' data-path=''> <i class="fa fa-arrow-left"></i> <?php echo _l('back'); ?> </a></li>
                                                    <?php if (sw_count($item['childs']) > 0): ?> 
                                                    <?php
                                                     /* show empty childs in bottom */
                                                        $top=array();
                                                        $bottom = array();
                                                        foreach ($item['childs'] as $v) {
                                                            if(!empty($v['count'])) {
                                                                $top [] = $v;
                                                            } else {
                                                                $bottom [] = $v;
                                                            }
                                                        }
                                                        $item['childs'] = $top;
                                                        foreach ($bottom as $v) {
                                                            $item['childs'][] = $v;
                                                        }
                                                    ?>
                                                        <?php foreach ($item['childs'] as $child): ?>
                                                            <li><a href="#" data-path-map-origin="<?php _che($tree_listings_default[$child['id']]->value);?>, <?php _che($tree_listings_default[$item['id']]->value);?>" data-path-map="<?php _che($child['title']); ?>, <?php _che($item['title']); ?>" data-region="<?php _che($child['title']); ?>" data-name-lvl_0="<?php _che($item['title']); ?>" data-path="<?php _che($item['title']); ?> - <?php _che($child['title']); ?>" data-id='<?php _che($child['id']); ?>'><?php _che($child['title']); ?> <span class="item-count">(<?php _che($child['count']); ?>)</span></a>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </ul>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div><!--map-city-links end-->
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <?php if(!$widget_fatal_error):?>
                            <div class="" id="map-geo">
                                <?php if (isset($_GET['geo_map_preview']) && !empty($_GET['geo_map_preview']) && isset($svg)): ?>
                                    <object  data="<?php echo $tmpfile; ?>" type="image/svg+xml" id="svgmap" width="500" height="360">
                                    </object>                                 
                                <?php else: ?>
                                    <?php if (file_exists(FCPATH . 'files/treefield_64_map.svg')): ?>
                                        <object data="<?php echo base_url('files/treefield_64_map.svg');?>" type="image/svg+xml" id="svgmap" width="500" height="360"></object>                                 
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php else:?>
                                <p class="alert alert-success" style="margin: 15px 0;">
                                <?php echo lang_check('Map didn`t create, please contact on mail: '.$settings_email);?>
                                </p>
                            <?php endif;?>
                        </div>
                    </div>
                </div><!--map-details end-->
            </div>
        </section>
        </div>