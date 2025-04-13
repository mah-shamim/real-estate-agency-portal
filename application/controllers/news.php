<?php

class News extends Frontend_Controller
{

	public function __construct ()
	{
		parent::__construct();
	}
    
    /*
    public function _remap($method)
    {
        $this->index();
    }
    */

    public function index()
    {
    }
    
    public function ajax ($lang_code, $page_id)
    {
        $lang_id = $this->data['lang_id'];

        // Fetch all files by repository_id
        $rep_file_count = array();
        $this->data['page_documents'] = array();
        $this->data['page_images'] = array();
        $this->data['page_files'] = array();
        
        
        // Fetch all files by repository_id
        if(isset($this->temp_data['page']->repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$this->temp_data['page']->repository_id));
        foreach($files as $key=>$file)
        {
            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
            $file->url = base_url('files/'.$file->filename);

            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
            {
                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                $this->data['images_'.$file->repository_id][] = $file;
                
                if($this->temp_data['page']->repository_id == $file->repository_id)
                {
                    $this->data['page_images'][] = $file;
                }
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                $this->data['documents_'.$file->repository_id][] = $file;
                if($this->temp_data['page']->repository_id == $file->repository_id)
                {
                    $this->data['page_documents'][] = $file;
                }
            }
            
            /* not need all
            $this->data['files_'.$file->repository_id][] = $file;

            if($this->temp_data['page']->repository_id == $file->repository_id)
            {
                $this->data['page_files'][] = $file;
            }*/
        }
        }
        
        // Has attributes
        $this->data['has_page_documents'] = array();
        if(sw_count($this->data['page_documents']))
            $this->data['has_page_documents'][] = array('count'=>sw_count($this->data['page_documents']));
        
        $this->data['has_page_images'] = array();
        if(sw_count($this->data['page_images']))
            $this->data['has_page_images'][] = array('count'=>sw_count($this->data['page_images']));
            
        $this->data['has_page_files'] = array();
        if(sw_count($this->data['page_files']))
            $this->data['has_page_files'][] = array('count'=>sw_count($this->data['page_files']));
        /* End fetch files */
        
        /* 
        $pagination_offset = $this->uri->segment(5);
        if(empty($pagination_offset))
            $pagination_offset=0;
        
        // Fetch all pages
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['categories'] = $this->page_m->get_no_parents_news_category($lang_id);
        $this->data['news_module_all'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, array('type'=>'MODULE_NEWS_POST'), null, '', 'date_publish DESC');


        $config_2['base_url'] = site_url('news/ajax/'.$this->data['lang_code'].'/0/');
        //$config_2['first_url'] = site_url('news/ajax/'.$this->data['lang_code'].'/0/');
        $config_2['total_rows'] = sw_count($this->data['news_module_all']);
        $config_2['per_page'] = 1;
        $config_2['uri_segment'] = 5;
    	$config_2['num_tag_open'] = '<li>';
    	$config_2['num_tag_close'] = '</li>';
        $config_2['full_tag_open'] = '<ul>';
        $config_2['full_tag_close'] = '</ul>';
        $config_2['cur_tag_open'] = '<li class="active"><span>';
        $config_2['cur_tag_close'] = '</span></li>';
    	$config_2['next_tag_open'] = '<li>';
    	$config_2['next_tag_close'] = '</li>';
    	$config_2['prev_tag_open'] = '<li>';
    	$config_2['prev_tag_close'] = '</li>';


        //$this->pagination->initialize($config_2);
        $pagination_2 = new CI_Pagination($config_2);
        //$pagination_2->initialize($config_2);
        $this->data['news_pagination'] = $pagination_2->create_links();
        
        $this->data['news_module_all'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, 
                                                          array('type'=>'MODULE_NEWS_POST'), 
                                                          $config_2['per_page'], $pagination_offset, 'date_publish DESC');
        */
        
        /* {MOULE_NEWS} */
        
        $category_id = 0;

        // Check for contained category/parent_id
        $news_category = $this->page_m->get_contained_news_category($page_id);
        $cat_merge = array();
        if(sw_count($news_category)>0)
        {
            $cat_merge = array('parent_id' => $news_category->id);
            $category_id = $news_category->id;
        }
        
        $category_id_get = $this->uri->segment(5);
        if(!empty($category_id_get))
        {
            if($category_id_get != 0)
                $cat_merge = array('parent_id' => $category_id_get);
            $category_id = $category_id_get;
        }
        
        
        $pagination_offset = $this->uri->segment(6);
        if(empty($pagination_offset))
            $pagination_offset=0;
        
        $search = $this->input->get_post('search');
        
        // Fetch all pages
        $this->data['page_languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['categories'] = $this->page_m->get_no_parents_news_category($lang_id);
        $this->data['news_module_all'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, array_merge($cat_merge, array('type'=>'MODULE_NEWS_POST')), null, '', 'date_publish DESC', $search);

        /* Pagination configuration */ 
        $config_2['base_url'] = site_url('news/ajax/'.$this->data['lang_code'].'/'.$page_id.'/'.$category_id.'/');
        //$config_2['first_url'] = site_url($this->uri->uri_string());
        $config_2['total_rows'] = sw_count($this->data['news_module_all']);
        $config_2['per_page'] = config_item('per_page');
        $config_2['uri_segment'] = 6;
    	$config_2['num_tag_open'] = '<li>';
    	$config_2['num_tag_close'] = '</li>';
        $config_2['full_tag_open'] = '<ul class="pagination">';
        $config_2['full_tag_close'] = '</ul>';
        $config_2['cur_tag_open'] = '<li class="active"><span>';
        $config_2['cur_tag_close'] = '</span></li>';
    	$config_2['next_tag_open'] = '<li>';
    	$config_2['next_tag_close'] = '</li>';
    	$config_2['prev_tag_open'] = '<li>';
    	$config_2['prev_tag_close'] = '</li>';
        
        if(!empty($search))
            $config_2['suffix'] = '?search='.$search;
        
        /* End Pagination */

        //$this->pagination->initialize($config_2);
        $pagination_2 = new CI_Pagination($config_2);
        //$pagination_2->initialize($config_2);
        $this->data['news_pagination'] = $pagination_2->create_links();
        $this->data['news_module_all'] = $this->page_m->get_lang(NULL, FALSE, $lang_id, 
                                                          array_merge($cat_merge, array('type'=>'MODULE_NEWS_POST')), 
                                                          $config_2['per_page'], $pagination_offset, 'date_publish DESC', $search);
               
        /* {/MOULE_NEWS} */
        
        $output = $this->parser->parse($this->data['settings_template'].'/results_news.php', $this->data, TRUE);
        $output = str_replace('assets/', base_url('templates/'.$this->data['settings_template']).'/assets/', $output);
        
        echo json_encode(array('print' => $output, 'lang_id'=>$lang_id, 'total_rows'=>$config_2['total_rows']));
        exit();
    }
    

}