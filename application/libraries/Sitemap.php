<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap {

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
    }
    
    public function generate_sitemap()
    {
//        if(!is_writable(FCPATH))return;
//        if(file_exists(FCPATH.'sitemap.xml') && !is_writable(FCPATH.'sitemap.xml'))return;

        $base_url = config_item('base_url');
        
        $this->CI->load->model('estate_m');
        $this->CI->load->model('page_m');
        $this->CI->load->model('option_m');
        
        $this->data['listing_uri'] = config_item('listing_uri');
        if(empty($this->data['listing_uri']))$this->data['listing_uri'] = 'property';
        
        $sitemap = $this->CI->page_m->get_sitemap();
        $properties = $this->CI->estate_m->get_sitemap();
        
        //For all visible languages, get options
        $langs = $this->CI->language_m->get_array_by(array('is_frontend'=>1));
        
        $options = array();
        foreach($langs as $key=>$row_lang)
        {
            $options[$row_lang['id']] = $this->CI->option_m->get_options($row_lang['id'], array(10));
        }
        
        $content = '';
        $content.= '<?xml version="1.0" encoding="UTF-8"?>'."\n".
                   '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'."\n".
                   '  	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\n".
                   '  	xmlns:xhtml="http://www.w3.org/1999/xhtml"'."\n".
                   '  	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9'."\n".
                   '			    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";
        
        $available_langs_array = array();
        $available_langs_domains = array();
        foreach($langs as $lang_code=>$lang)
        {
            $available_langs_array[] = $lang['id'];
            $available_langs_domains[$lang['id']] = $lang['domain'];
        }
        
        foreach($sitemap as $page_obj)
        {
            if(in_array($page_obj->language_id ,$available_langs_array))
            {
                $domain = $available_langs_domains[$page_obj->language_id];
                if(!empty($domain))
                    $this->CI->config->set_item('base_url', $domain);
                
                $last_mod = '';
                if(!empty($page_obj->date))
                    $last_mod = '	<lastmod>'.date('c',strtotime($page_obj->date)).'</lastmod>'."\n";
                
                if( ($page_obj->type == 'MODULE_NEWS_CATEGORY' || $page_obj->type == 'MODULE_NEWS_POST') && 
                    !file_exists(APPPATH.'controllers/admin/news.php'))
                {
                    //If news related and news module is not installed, don't add to sitemap.xml'
                }
                else
                {
                    
                if($page_obj->keywords == '#') continue;
                    
                $content.= '<url>'."\n".
                        	'	<loc>'.slug_url($this->CI->language_m->get_code($page_obj->language_id).'/'.$page_obj->id.'/'.url_title_cro($page_obj->navigation_title, '-', TRUE), 'page_m').'</loc>'."\n".
                        	$last_mod.
                        	'	<changefreq>monthly</changefreq>'."\n".
                        	'	<priority>0.5</priority>'."\n";
                
                /* alternate links */
                foreach ($langs as $lang) {
                    if($lang['code'] == $this->CI->language_m->get_code($page_obj->language_id)) continue;
                    
                    $base_url_2 = config_item('base_url');
                    
                    $domain = $available_langs_domains[$lang['id']];
                    if(!empty($domain))
                        $this->CI->config->set_item('base_url', $domain);
                    
                    $content.=  '    <xhtml:link'.PHP_EOL.
                                '        rel="alternate"'.PHP_EOL.
                                '        hreflang="'.$lang['code'].'"'.PHP_EOL.
                                '        href="'.slug_url($lang['code'].'/'.$page_obj->id.'/'.url_title_cro($page_obj->navigation_title, '-', TRUE), 'page_m').'"'.PHP_EOL.
                                '    />'.PHP_EOL;
                                
                    $this->CI->config->set_item('base_url', $base_url_2);
                }
                /* end alternate links */
                
                $content.= '</url>'."\n";
                }
                
                $this->CI->config->set_item('base_url', $base_url);
            }
        }
        
        foreach($properties as $estate_obj)
        {
            foreach($langs as $lang_code=>$lang)
            {
                if(!empty($lang['domain']))
                    $this->CI->config->set_item('base_url', $lang['domain']);
                
                $last_mod = '';
                if(!empty($estate_obj->date_modified))
                    $last_mod = '	<lastmod>'.date('c',strtotime($estate_obj->date_modified)).'</lastmod>'."\n";

                $content.= '<url>'."\n".
                        	'	<loc>'.slug_url($this->data['listing_uri'].'/'.$estate_obj->id.'/'.$lang['code'].'/'.(isset($options[$lang['id']][$estate_obj->id][10])?url_title_cro($options[$lang['id']][$estate_obj->id][10], '-', TRUE):''), 'estate_m').'</loc>'."\n".
                        	$last_mod.
                        	'	<changefreq>monthly</changefreq>'."\n".
                        	'	<priority>0.5</priority>'."\n".
                        	'</url>'."\n";
                            
                $this->CI->config->set_item('base_url', $base_url);
            }
        }
        
        // [Showroom START] //
        if(file_exists(APPPATH.'controllers/admin/showroom.php'))
        {
            $this->CI->load->model('showroom_m');
            $showrooms = $this->CI->showroom_m->get_by(array('type'=>'COMPANY'));
            
            foreach($showrooms as $showroom_obj)
            {
                foreach($langs as $lang_code=>$lang)
                {
                    if(!empty($lang['domain']))
                        $this->CI->config->set_item('base_url', $lang['domain']);
                    
                    $last_mod = '';
                    if(!empty($showroom_obj->date_modified))
                        $last_mod = '	<lastmod>'.date('c',strtotime($showroom_obj->date_modified)).'</lastmod>'."\n";
                        
                    $content.= '<url>'."\n".
                            	'	<loc>'.site_url('showroom/'.$showroom_obj->id.'/'.$lang['code']).'</loc>'."\n".
                            	$last_mod.
                            	'	<changefreq>monthly</changefreq>'."\n".
                            	'	<priority>0.5</priority>'."\n".
                            	'</url>'."\n";
                                
                    $this->CI->config->set_item('base_url', $base_url);
                }
            }
        }
        // [Showroom END] //
        
        // [Treefield START] //
        if(file_exists(APPPATH.'controllers/admin/treefield.php'))
        {
            $this->CI->load->model('treefield_m');
            
            foreach($langs as $lang_code=>$lang)
            {
                if(!empty($lang['domain']))
                    $this->CI->config->set_item('base_url', $lang['domain']);
                
                //TODO: Add for all fields not only id:64
                $tree_listings = $this->CI->treefield_m->get_table_tree($lang['id'], 64);
                
                if(sw_count($tree_listings) > 0)
                foreach($tree_listings as $listing_item)
                {
                    if(!empty($listing_item->template) && !empty($listing_item->body))
                    {
                        $content.= '<url>'."\n".
                                	'	<loc>'.slug_url('treefield/'.$lang['code'].'/'.$listing_item->id.'/'.url_title_cro($listing_item->value), 'treefield_m').'</loc>'."\n".
                                	//'	<lastmod>'.$page_obj->date.'</lastmod>'.
                                	'	<changefreq>monthly</changefreq>'."\n".
                                	'	<priority>0.5</priority>'."\n".
                                	'</url>'."\n";
                    }
                }
                
                $this->CI->config->set_item('base_url', $base_url);
            }
        }
        // [Treefield END] //

        $content.= '</urlset>';
        
        $fp = fopen(FCPATH.'sitemap.xml', 'w');
        fwrite($fp, $content);
        fclose($fp);
        
        $this->CI->config->set_item('base_url', $base_url);
    }
    
}

?>