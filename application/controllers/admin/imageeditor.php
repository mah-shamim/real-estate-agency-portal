<?php

class Imageeditor extends Admin_Controller
{
	public function __construct(){
		parent::__construct();
        $this->load->model('page_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
        
        $this->data['user_id'] = $this->session->userdata('id');
    }
    
    public function edit($filename=NULL, $dim = '800x600')
	{
        $input_data = $_POST;
        $this->data['resize'] = $dim;
        $this->data['rename_enable'] = false;
        $this->load->model('file_m');

        if(sw_is_intval($filename)) {
            $this->data['form'] = $this->file_m->get($filename, TRUE);
            $this->data['rename_enable'] = true;
            $filename = $this->data['form']->filename;
        }

        $filename_db = urldecode($filename);
        $filename_db = str_replace("&#40;","(",$filename_db);
        $filename_db = str_replace("&#41;",")",$filename_db);
        
        $filename_url = $filename;
        $filename_url = str_replace("&#40;","(",$filename_url);
        $filename_url = str_replace("&#41;",")",$filename_url);

        $this->data['filename'] = substr($filename_db,0, strripos($filename_db , '.'));


        if(!$this->data['rename_enable'])
            $this->data['form'] = $this->file_m->get_by(array('filename'=>$filename_db), TRUE);
 
        if(empty($this->data['form']->id))
            exit('Not exists');
        
        $this->data['model']= '';
        $this->load->model('repository_m');
        $repository_m= $this->repository_m->get_by(array('id'=>$this->data['form']->repository_id), TRUE);
        $this->data['model']= $repository_m->name;
        
        $this->load->model('estate_m');
        $this->data['properties'] = $this->estate_m->get_form_dropdown('address', FALSE, TRUE, TRUE);
        
        // Check if user have permission on this file
        if($this->session->userdata('type') == 'USER')
        if(!$this->user_m->is_related_repository($this->data['user_id'], $this->data['form']->repository_id))
        {
            exit('No permissions');
        }

        if(sw_count($input_data) > 0)
        {
            
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('message', 
                    '<p class="alert alert-danger validation">'.lang_check('Data editing disabled in demo').'</p>');
                redirect(uri_string());
            }

            $new_file_name = '';
            if($this->data['rename_enable'] && $this->input->post('filename') && $this->data['filename'] != $this->input->post('filename')) {
                $new_file_name  = $this->input->post('filename').substr($filename_db,strripos($filename_db , '.'));
                $index = 1;
                while(file_exists(FCPATH.'/files/'.$new_file_name)) {
                    $new_file_name = $this->input->post('filename').'('.$index.')'.substr($filename_db,strripos($filename_db , '.'));
                    $index++;
                }
                //dump(FCPATH.'/files/'.$new_file_name);
                //dump(FCPATH.'/files/'.$filename_db);
                $ret = rename(FCPATH.'/files/'.$filename_db, FCPATH.'/files/'.$new_file_name);
                //dump( $ret);
                $filename_db = $new_file_name;

                $this->load->library('uploadHandler', array('initialize'=>FALSE));
                $this->uploadhandler->regenerate_versions($filename_db, '');
            }
    
            if(isset($input_data['image-data']) && isset($input_data['confirmresize']) && $input_data['confirmresize'] == 'true')
            {
                $data_im = explode(',', $input_data['image-data']);
                $data_im = base64_decode($data_im[1]);
                $fpath = FCPATH.'files/'.$filename_db;
                
                $im = imagecreatefromstring($data_im);
                switch (strtolower(substr(strrchr($filename_db, '.'), 1))) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($im, $fpath);
                        break;
                    case 'gif':
                        imagegif($im, $fpath);
                        break;
                    case 'png':
                        @imagealphablending($im, false);
                        @imagesavealpha($im, true);
                        imagepng($im, $fpath);
                        break;
                    default:
                }
                
                // generate image versions
                
                $this->load->library('uploadHandler', array('initialize'=>FALSE));
                $this->uploadhandler->regenerate_versions($filename_db, '');
                
                $this->file_m->delete_cache($filename_db);
            }
            
            $data = $this->user_m->array_from_post(array('alt', 'description', 'title', 'link', 'listing_id'));
            if(!empty($new_file_name)) {
                $data['filename'] = $new_file_name;
            }
            $this->file_m->save($data, $this->data['form']->id);
            
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');

            redirect(site_url('admin/imageeditor/edit/'.$filename_db.'#content'));
        }
       
        if($dim!=='false'):
            $dim_exp = explode('x', $dim);
            $this->data['width'] = $dim_exp[0];
            $this->data['height'] = $dim_exp[1];
            $wanted_ratio = $this->data['width'] / $this->data['height'];

            $this->data['filepath'] = base_url('files/'.$filename_url);

            $dim_real = getimagesize(FCPATH.'files/'.$filename_db);
            $this->data['width_r'] = $dim_real[0];
            $this->data['height_r'] = $dim_real[1];
            $real_ratio = $this->data['width_r'] / $this->data['height_r'];

            //     800x600       700x600
            if($wanted_ratio > $real_ratio)
            {
                $this->data['width'] = $this->data['width_r'];
                $this->data['height'] = $this->data['width_r'] * 1/$wanted_ratio;
            }
            //          800x600       900x600
            else if($wanted_ratio <= $real_ratio)
            {
                $this->data['width'] = $this->data['height_r'] * $wanted_ratio;
                $this->data['height'] = $this->data['height_r'];
            }

            // for larger images
            if($this->data['width'] > $dim_exp[0])
            {
                $this->data['width'] = $dim_exp[0];
                $this->data['height'] = $dim_exp[1];
            }
        endif;
        
        
        $this->load->view('admin/imageeditor/edit', $this->data);
	}

    
}