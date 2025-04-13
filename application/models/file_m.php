<?php

class File_m extends MY_Model {
    
    protected $_table_name = 'file';
    protected $_order_by = 'order, id';
    
    protected $_revision_id = 1;

    public function set_revision($revision_id)
    {
        if($revision_id != NULL)
            $this->_revision_id = $revision_id;
    }
    
    public function get_max_order()
    {
        // get max order
        return parent::max_order();
    }
    
    public function get_where_in($where_in)
    {
        $this->db->where_in('repository_id', $where_in);
        return $this->get();
    }
    
    public function count_in_repository($repository_id)
    {
        $this->db->where('repository_id', $repository_id);
        $this->db->from($this->_table_name);
        $query = $this->db->get();
        
        //echo $this->db->last_query();
        
        $rows = $query->result();
        $count = sw_count($rows);
        
        //$count = $this->db->count_all_results();
        
        if(!empty($count))
            return $count;
        
        return 0;
    }
    
    public function delete($id)
    {
        $file = $this->get($id);
        if(!empty($file->filename))
        {
            $this->delete_cache($file->filename);
            parent::delete($id);
        }
    }
    
    public function delete_cache($filename)
    {
        if ($handle = opendir(FCPATH.'files/strict_cache/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    if(strpos($entry, $filename) !== FALSE)
                    {
                        @unlink(FCPATH.'files/strict_cache/'.$entry);
                    }
                }
            }
            closedir($handle);
        }
    }

}



