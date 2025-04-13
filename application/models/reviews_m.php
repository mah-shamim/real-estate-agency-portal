<?php

class Reviews_m extends MY_Model {
    
    protected $_table_name = 'reviews';
    protected $_order_by = 'reviews.id DESC';
    
    public $rules = array(
        'stars' => array('field'=>'stars', 'label'=>'lang:Stars', 'rules'=>'trim|required|xss_clean'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|xss_clean')
   );
    
    public $rules_admin = array(
        'listing_id' => array('field'=>'listing_id', 'label'=>'lang:Listing', 'rules'=>'trim|required|intval'),
        'user_id' => array('field'=>'user_id', 'label'=>'lang:User', 'rules'=>'trim|required|intval'),
        'stars' => array('field'=>'stars', 'label'=>'lang:Stars', 'rules'=>'trim|required|xss_clean'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|xss_clean'),
        'is_visible' => array('field'=>'is_visible', 'label'=>'lang:Visible', 'rules'=>'trim|xss_clean'),
        'user_mail' => array('field'=>'user_mail', 'label'=>'lang:Mail', 'rules'=>'trim|xss_clean')
   );
   
   public $rules_lang = array();
   
	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $listing = new stdClass();
        $listing->listing_id = 0;
        $listing->user_id = 0;
        $listing->date_publish = date('Y-m-d H:i:s');
        $listing->stars = 0;
        $listing->user_mail = '';
        $listing->message = '';
        $listing->is_visible = 1;
        
        return $listing;
	}
    
    public function check_if_exists($user_id, $listing_id)
    {
        $query = $this->db->get_where($this->_table_name, array('user_id'   => $user_id, 
                                                                'listing_id'=>$listing_id));
        return $query->num_rows();
    }
    
    public function save($data, $id = NULL, $edit=FALSE)
    {
        if ($this->check_if_exists($data['user_id'], $data['listing_id']) == 0 || $edit)
        {
            parent::save($data, $id);
        }
    }
    
    public function get_avarage_rating($property_id)
    {
        $this->db->where('listing_id', $property_id);
        $this->db->select_avg('stars');
        $query = $this->db->get($this->_table_name);
        
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
           return $row->stars;
        } 
        
        return '';
    }
    
    public function get_joined($where = null, $limit = null, $order_by = null, $offset = null)
    {
        $this->db->select('reviews.*, user.username, property.address');
        $this->db->from($this->_table_name);
        $this->db->join('user', 'user.id = '.$this->_table_name.'.user_id', 'left');
        $this->db->join('property', 'property.id = '.$this->_table_name.'.listing_id');
        
        if(!empty($where))
        {
            $this->db->where($where);
        }
        
        if($limit != null || $offset != null)
            $this->db->limit($limit, $offset);
        
        if($order_by == null)
        {
            $this->db->order_by($this->_order_by);
        }
        else
        {
            $this->db->order_by($order_by);
        }
        
        $query = $this->db->get();
        
        if (is_object($query))
        {
            return $query->result();
        }
        
        return array();
    }
    
    public function get_listing($where=null)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join('user', 'user.id = '.$this->_table_name.'.user_id', 'left');
        
        if(!empty($where))
        {
            $this->db->where($where);
        }
        
        $this->db->order_by($this->_order_by); 
        
        $query = $this->db->get();
        
        if (is_object($query))
        {
            return $query->result_array();
        }
        
        return array();
    }

    public function delete($id)
    {
        //$this->db->delete('rates_lang', array('rates_id' => $id));         
        parent::delete($id);
    }

}


