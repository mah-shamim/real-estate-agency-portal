<?php

class Enquire_m extends MY_Model {
    
    protected $_table_name = 'enquire';
    protected $_order_by = 'id DESC';
    
    public $rules = array(
        'firstname' => array('field'=>'firstname', 'label'=>'lang:FirstLast', 'rules'=>'trim|required|xss_clean'),
        'email' => array('field'=>'email', 'label'=>'lang:Email', 'rules'=>'trim|required|valid_email|xss_clean'),
        'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|required|xss_clean'),
        'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim|xss_clean'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|required|xss_clean'),
        'fromdate' => array('field'=>'fromdate', 'label'=>'lang:FromDate', 'rules'=>'trim|xss_clean'),
        'todate' => array('field'=>'todate', 'label'=>'lang:ToDate', 'rules'=>'trim|xss_clean')
    );
    
    public $rules_admin = array(
        'property_id' => array('field'=>'property_id', 'label'=>'lang:Estate', 'rules'=>'trim|required|xss_clean'),
        'agent_id' => array('field'=>'agent_id', 'label'=>'lang:Agent', 'rules'=>'trim|xss_clean'),
        'name_surname' => array('field'=>'name_surname', 'label'=>'lang:Name and surname', 'rules'=>'trim|required|xss_clean'),
        'phone' => array('field'=>'phone', 'label'=>'lang:Phone', 'rules'=>'trim|required|xss_clean'),
        'mail' => array('field'=>'mail', 'label'=>'lang:Mail', 'rules'=>'trim|required|xss_clean'),
        'message' => array('field'=>'message', 'label'=>'lang:Message', 'rules'=>'trim|required|xss_clean'),
        'address' => array('field'=>'address', 'label'=>'lang:Address', 'rules'=>'trim|xss_clean'),
        'readed' => array('field'=>'readed', 'label'=>'lang:Readed', 'rules'=>'trim'),
        'fromdate' => array('field'=>'fromdate', 'label'=>'lang:FromDate', 'rules'=>'trim|xss_clean'),
        'todate' => array('field'=>'todate', 'label'=>'lang:ToDate', 'rules'=>'trim|xss_clean')
    );
    
    public $rules_reply = array(
        'last_reply' => array('field'=>'last_reply', 'label'=>'lang:Reply message', 'rules'=>'trim|required|xss_clean'),
    );

	public function __construct(){
		parent::__construct();
	}
    
    public function get_new()
	{
        $enquire = new stdClass();
        $enquire->name_surname = '';
        $enquire->address = '';
        $enquire->message = '';
        $enquire->phone = '';
        $enquire->mail = '';
        $enquire->date = date('Y-m-d H:i:s');
        $enquire->readed = 0;
        $enquire->fromdate = '';
        $enquire->todate = '';
        $enquire->property_id = NULL;
        $enquire->agent_id = '';
        return $enquire;
	}
    
    public function get($id = NULL, $single = FALSE)
    {
        $this->db->select($this->_table_name.'.*, property.address as p_address');
        
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->db->select($this->_table_name.'.*, property_user.user_id, property.address as p_address');
            $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        /* filter private messages */
        if(file_exists(APPPATH.'libraries/Messenger.php') && config_item('private_messages_enabled') !== FALSE) {
            $this->db->where('to_id', 'NULL');
            $this->db->where('from_id', 'NULL');
        }
        
        $this->db->join('property', $this->_table_name.'.property_id = property.id', 'left');
        
        return parent::get($id, $single);
    }

    public function get_pagination($where=array(), $order = NULL, $limit = NULL, $offset = '')
    {
        $this->db->select($this->_table_name.'.*, property.address as p_address');
        
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->db->select($this->_table_name.'.*, property_user.user_id, property.address as p_address');
            $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        /* filter private messages */
        if(file_exists(APPPATH.'libraries/Messenger.php') && config_item('private_messages_enabled') !== FALSE) {
            $this->db->where('to_id', 'NULL');
            $this->db->where('from_id', 'NULL');
        }
        
        $this->db->join('property', $this->_table_name.'.property_id = property.id', 'left');
        if($where)
            $this->db->where($where);

            if($limit !== NULL) $this->db->limit($limit, $offset);

        if($order) {
            $this->db->order_by($order);
        } else {
            $this->db->order_by($this->_order_by);
        }

        $query = $this->db->get($this->_table_name);
        
        if(!is_object($query))
            exit($this->db->last_query());
        
        if ($query->num_rows() > 0)
            return $query->result();
            
        return array();
    }
    
    public function total_unreaded()
    {
        $this->db->where('(readed=0 or readed IS NULL)');
        
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->db->select($this->_table_name.'.*, property_user.user_id');
            $this->db->join('property_user', $this->_table_name.'.property_id = property_user.property_id', 'left');
            $this->db->where('user_id', $this->session->userdata('id'));
        }
        
        $query = $this->db->get($this->_table_name);
        return $query->num_rows();
    }
    
    public function get_dialogs ($last_id = NULL)
    {
        $user_id = $this->session->userdata('id');
        
        $where_id = '';
        
        if($last_id !== NULL && !empty($last_id)) {
            $where_id = "AND m.id > '".$last_id."'";
        }
        
        $sq1 = 
           'SELECT m.*, property.address as p_address, '
            . 'SUM(if (p.`readed`= 1, 0, 1)) AS unreaded,'
            . 'user.id AS interlocutor_id,'
            . 'user.name_surname AS interlocutor_name_surname,'
            . 'user.phone AS interlocutor_phone,'
            . 'user.mail AS interlocutor_mail,'
            . 'user.username AS interlocutor_username,'
            . 'user.repository_id AS interlocutor_repository_id,'
            . 'user.image_user_filename AS interlocutor_image_user_filename,'
            . 'user.image_agency_filename AS interlocutor_image_agency_filename 
            FROM `'.$this->_table_name.'`m  
                
            LEFT JOIN `enquire`p  ON m.`from_id` = p.`from_id` and m.`to_id` = p.`to_id` and  m.`property_id` = p.`property_id` 
            LEFT JOIN `property` ON m.`property_id` = `property`.`id`
            LEFT JOIN `user` ON (
                                    ( m.`from_id` = user.id AND m.from_id != '.$user_id.') 
                                    OR 
                                    (m.to_id = user.id AND m.to_id != '.$user_id.')
                                )
            LEFT JOIN `'.$this->_table_name.'` m2 ON (
                        (m.to_id = m2.to_id  AND m.from_id = m2.from_id ) 
                        AND
                        m.property_id = m2.property_id AND m.id < m2.id)
            where /*m.date = 
                    (select max(date) FROM `'.$this->_table_name.'`n  where n.from_id=m.from_id and n.to_id=m.to_id) 
                    AND*/ 
                    m2.id IS NULL AND
                    (m.to_id = '.$user_id.' OR m.from_id = '.$user_id.')
                    AND (m.to_id NOT LIKE  '.$user_id.' OR m.from_id NOT LIKE  '.$user_id.')
                    AND ((m.to_id = '.$user_id.' AND m.del_to = 0) OR (m.from_id = '.$user_id.' AND m.del_from = 0))
                    AND user.`id` > 0
                    AND m.`property_id` > 0
                    /*AND p.to_id = '.$user_id.'*/
                    '.$where_id.'
                    GROUP BY m.`property_id`, m.`from_id`, m.`to_id`, m.`id`,user.id
                    ORDER BY id DESC   
            ';
               
        $field_data  = $this->db->query($sq1);
        if($field_data)
            return $field_data->result_array();
        else
            return array();
    }
    
    public function get_all_messages($id = NULL, $single = FALSE)
    {
        $this->db->select($this->_table_name.'.*, property.address as p_address');
        $this->db->where('to_id', $this->session->userdata('id'));
        $this->db->or_where('from_id', $this->session->userdata('id'));
        
        $this->db->join('property', $this->_table_name.'.property_id = property.id', 'left');
        
        return parent::get($id, $single);
    }
    
    public function get_dialog($interlocutor_id, $property_id = null, $latest_id = NULL)
    {
        $user_id = $this->session->userdata('id');
        
        $this->db->select($this->_table_name.'.*');
        
        $where = '((`to_id` = '.$interlocutor_id.' AND `from_id` = '.$user_id.') OR (`from_id` = '.$interlocutor_id.' AND `to_id` = '.$user_id.'))';
        
        $where .= ' AND ((to_id = '.$user_id.' AND del_to = 0) OR (from_id = '.$user_id.' AND del_from = 0))';
        
        if($property_id != NULL)
            $where .= ' AND property_id='.$property_id;
               
        if($latest_id !== NULL  && !empty($latest_id))
             $where .= ' AND (id > '.$latest_id.')';
        
        $this->db->where($where);
        
        $this->db->limit(400);
        
        $this->db->order_by('id DESC');
        
        return parent::get();
    }
    
    public function check_new_dialogs($last_id = NULL){
        $where_date = '';
        
        if($last_id == NULL || empty($last_id))  return true;
        
        $where_date = "AND `".$this->_table_name."`.id > '".$last_id."'";
        
        $user_id = $this->session->userdata('id');
        
        $this->db->select($this->_table_name.'.*');
        
        $where = '(`from_id` = '.$user_id.' OR `to_id` = '.$user_id.') '.$where_date;
        
                
        $this->db->where($where);
        
        
        $query = $this->db->get($this->_table_name);
        //echo $this->db->last_query(); exit();
        if($query && $query->num_rows()>0)
            return true;
        else 
            return false;
        
    }
    
    public function check_new_messages_by_dialog($interlocutor_id, $property_id = null, $last_id = NULL){
        $where_id = '';
        
        if($interlocutor_id==NULL)  return false;
        if($last_id == NULL || empty($last_id)) return true;
        
        $where_id = "AND `".$this->_table_name."`.id > '".$last_id."'";
        
        $user_id = $this->session->userdata('id');
        
        $this->db->select($this->_table_name.'.*');
        
        $where = '((`to_id` = '.$interlocutor_id.' AND `from_id` = '.$user_id.') OR (`from_id` = '.$interlocutor_id.' AND `to_id` = '.$user_id.')) '.$where_id;
        if($property_id != NULL)
            $where .= ' AND property_id='.$property_id;
                
        $this->db->where($where);
        
        $query = $this->db->get($this->_table_name);
        
        if($query && $query->num_rows()>0)
            return true;
        else 
            return false;
        
    }
    
    public function show_counter_dialogs($where_in = array()){
        $where_date = "AND `".$this->_table_name."`.date > '".$last_date."'";
        
        $user_id = $this->session->userdata('id');
        
        $this->db->select('to_id, from_id,SUM(if ('.$this->_table_name.'.`readed`= 1, 0, 1)) AS unreaded');
        
        $where = '`to_id` = '.$user_id.' and (readed=0 or readed IS NULL) ';
        
        $this->db->where($where);
        $this->db->where_in('from_id', $where_in);
        $this->db->group_by('to_id, from_id');
        
        return parent::get();
    }
    
    public function readed ($id=NULL, $interlocutor_id = NULL,  $property_id = NULL) {
        
        if($id == NULL) {
        if($interlocutor_id == NULL || !$this->session->userdata('id'))  return false;
            $user_id = $this->session->userdata('id');
            $this->db->set('readed', 1);
            $this->db->where(array('to_id'=> $user_id,'from_id' => $interlocutor_id));
            $this->db->update($this->_table_name);
        } else {
            
        }
        return true;
    }
    
    public function remove_dialog($del_dialog, $property_id = NULL) {
        if($property_id == NULL || $del_dialog == NULL || !$this->session->userdata('id'))  return false;
        
        $user_id = $this->session->userdata('id');
         
        $this->db->set('del_from', 1);
        $this->db->where(array('from_id'=> $user_id, 'to_id' => $del_dialog, 'property_id'=>$property_id));
        $this->db->update($this->_table_name); 
        
        $this->db->set('del_to', 1);
        $this->db->where(array('to_id'=> $user_id, 'from_id' => $del_dialog));
        $this->db->update($this->_table_name); 
        
        //echo $this->db->last_query();
        return true;
    }
    
    public function save($data, $id=NULL) {
        
        if(isset($data['message'])) {
            $data['message']= str_replace(array("\r\n", PHP_EOL), '<br/>', $data['message']);
        }
        
        return parent::save($data, $id);
    }
    
}



