<?php

class Slideshow_m extends MY_Model {
    
    protected $_table_name = 'slideshow';
    protected $_order_by = 'id';
    public $rules = array();

    public function get_new()
	{
	}
    
    public function get_repository_images()
    {
        $slideshow = $this->get_array(NULL, TRUE);
        
        if(!empty($slideshow))
        {
            
            $this->db->where(array('repository_id' => $slideshow['repository_id']));
            $this->db->order_by('order');
            
            $query = $this->db->get_where('file');
            
            if ($query->num_rows() > 0)
            {
               return $query->result();
            } 
        }
        
        
        return array();
    }

}


