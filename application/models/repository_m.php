<?php

class Repository_m extends MY_Model {
    
    protected $_table_name = 'repository';
    protected $_order_by = 'id';
    
    public function save($data, $id = NULL)
    {
        // Remove never activated repositories
        // created before 24h ago
        $not_activated_repositories = $this->get_by(array('is_activated'=>NULL, 
                                        'date_submit <'=>date('Y-m-d H:i:s', time()-0.5*60*60)));
        foreach($not_activated_repositories as $rep)
        {
            $this->delete($rep->id);
        }
        
        // Save proccess
        
        $now = date('Y-m-d H:i:s');
        if($id===NULL) $data['date_submit'] = $now;
        $data['date_modified'] = $now;
    
        return parent::save($data, $id);
    }
    
	public function delete ($id)
	{
        // Delete all files from filesystem
        $this->load->model('file_m');
        $files = $this->file_m->get_by(array(
            'repository_id' => $id
        ));
        
        foreach($files as $file)
        {
            if(file_exists(FCPATH.'files/'.$file->filename))
                unlink(FCPATH.'files/'.$file->filename);
            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
                unlink(FCPATH.'files/thumbnail/'.$file->filename);
        }
       
        // Delete all files from db
        $this->db->where('repository_id', $id);
        $this->db->delete($this->file_m->get_table_name()); 
        
        // Delete repository row
        parent::delete($id);
	}
    
    
    
}



