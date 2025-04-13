<?php

class Slideshow extends Admin_Controller
{
	public function __construct(){
		parent::__construct();
        $this->load->model('slideshow_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
	}
    
    public function index()
	{
        // Get slideshow
        $this->data['slideshow'] = $this->slideshow_m->get(NULL, TRUE);
        
        // If not exists
        if(sw_count($this->data['slideshow']) == 0)
        {
            // Add one
            $this->slideshow_m->save(array('date'=>date('Y-m-d H:i:s')));
            // Get slideshow
            $this->data['slideshow'] = $this->slideshow_m->get(NULL, TRUE);
            
            // Fetch file repository
            $repository_id = $this->data['slideshow']->repository_id;
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('name'=>'slideshow_m'));
                
                // Update page with new repository_id
                $this->slideshow_m->save(array('repository_id'=>$repository_id), $this->data['slideshow']->id);
            }
        }
        
        if(!isset($repository_id)) {
             $repository_id = $this->data['slideshow']->repository_id;
        }
        
        // Fetch all files by repository_id
        if(isset($repository_id))
        {
            $files = $this->file_m->get_by(array('repository_id'=>$repository_id));
            foreach($files as $key=>$file)
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
                $file->zoom_enabled = false;
                $file->download_url = base_url('files/'.$file->filename);
                $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));

                if(file_exists(FCPATH.'/files/thumbnail/'.$file->filename))
                {
                    $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                    $file->zoom_enabled = true;
                }
                else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
                {
                    $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                }

                $this->data['files'][$file->repository_id][] = $file;
            }
        }
        
        // Load view
		$this->data['subview'] = 'admin/slideshow/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}

}