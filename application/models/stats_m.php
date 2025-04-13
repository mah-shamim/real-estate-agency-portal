<?php

class Stats_m extends MY_Model {
    
    protected $_table_name = 'stats';
    protected $_order_by = 'id DESC';

    public function get_where_in($where_in)
    {
        $this->db->where_in('property_id', $where_in);
        return $this->get();
    }
    
    public function save_stats($property_id)
    {
        $this->load->library('user_agent');
        
        $time = time(); // get sec
        $time = intval($time-($time%300)); // get base based on 5min (300sec)
        
        $update = false;
        $this->db->where('property_id', $property_id);
        $this->db->where('time_part_5min', date('Y-m-d H:i:s', $time));
        $q = $this->db->get($this->_table_name);
        if ( $q->num_rows() > 0 )
            $update = true;
        
        // If not robot
        if(!$this->agent->is_robot())
        {
            if($update)
            {
                $this->db->set('views', 'views+1', FALSE);
                $this->db->where('property_id', $property_id);
                $this->db->where('time_part_5min', date('Y-m-d H:i:s', $time));
                $this->db->update($this->_table_name);
            }
            else
            {
                $this->db->set('property_id', $property_id);
                $this->db->set('time_part_5min', date('Y-m-d H:i:s', $time));
                $this->db->set('views', 1);
                $this->db->insert($this->_table_name);
            }
        }
        
        // Additional for period (WEEK) //
        
        $time_week = mktime(0, 0, 0, date("n"), date("j") - date("N") + 1);
        $time_day = mktime(0, 0, 0);
        $time_month = mktime(0, 0, 0, date("n"), 1);
        $time_year = mktime(0, 0, 0, 1, 1, date("Y"));
        
        $update = false;
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'WEEK');
        $this->db->where('date', date('Y-m-d H:i:s', $time_week));
        $q = $this->db->get('stats_periods');
        if ( $q->num_rows() > 0 )
            $update = true;
        
        // If not robot
        if(!$this->agent->is_robot())
        {
            if($update)
            {
                $this->db->set('views', 'views+1', FALSE);
                
                $this->db->where('property_id', $property_id);
                $this->db->where('period', 'WEEK');
                $this->db->where('date', date('Y-m-d H:i:s', $time_week));
                
                $this->db->update('stats_periods');
            }
            else
            {
                $this->db->set('property_id', $property_id);
                $this->db->set('period', 'WEEK');
                $this->db->set('date', date('Y-m-d H:i:s', $time_week));
                $this->db->set('views', 1);
                
                $this->db->insert('stats_periods');
            }
        }
        
        // Additional for period (DAY) //       
        $update = false;
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'DAY');
        $this->db->where('date', date('Y-m-d H:i:s', $time_day));
        $q = $this->db->get('stats_periods');
        if ( $q->num_rows() > 0 )
            $update = true;
        
        // If not robot
        if(!$this->agent->is_robot())
        {
            if($update)
            {
                $this->db->set('views', 'views+1', FALSE);
                
                $this->db->where('property_id', $property_id);
                $this->db->where('period', 'DAY');
                $this->db->where('date', date('Y-m-d H:i:s', $time_day));
                
                $this->db->update('stats_periods');
            }
            else
            {
                $this->db->set('property_id', $property_id);
                $this->db->set('period', 'DAY');
                $this->db->set('date', date('Y-m-d H:i:s', $time_day));
                $this->db->set('views', 1);
                
                $this->db->insert('stats_periods');
            }
        }
        
        // Additional for period (MONTH) //       
        $update = false;
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'MONTH');
        $this->db->where('date', date('Y-m-d H:i:s', $time_month));
        $q = $this->db->get('stats_periods');
        if ( $q->num_rows() > 0 )
            $update = true;
        
        // If not robot
        if(!$this->agent->is_robot())
        {
            if($update)
            {
                $this->db->set('views', 'views+1', FALSE);
                
                $this->db->where('property_id', $property_id);
                $this->db->where('period', 'MONTH');
                $this->db->where('date', date('Y-m-d H:i:s', $time_month));
                
                $this->db->update('stats_periods');
            }
            else
            {
                $this->db->set('property_id', $property_id);
                $this->db->set('period', 'MONTH');
                $this->db->set('date', date('Y-m-d H:i:s', $time_month));
                $this->db->set('views', 1);
                
                $this->db->insert('stats_periods');
            }
        }
        
        // Additional for period (YEAR) //       
        $update = false;
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'YEAR');
        $this->db->where('date', date('Y-m-d H:i:s', $time_year));
        $q = $this->db->get('stats_periods');
        if ( $q->num_rows() > 0 )
            $update = true;
        
        // If not robot
        if(!$this->agent->is_robot())
        {
            if($update)
            {
                $this->db->set('views', 'views+1', FALSE);
                
                $this->db->where('property_id', $property_id);
                $this->db->where('period', 'YEAR');
                $this->db->where('date', date('Y-m-d H:i:s', $time_year));
                
                $this->db->update('stats_periods');
            }
            else
            {
                $this->db->set('property_id', $property_id);
                $this->db->set('period', 'YEAR');
                $this->db->set('date', date('Y-m-d H:i:s', $time_year));
                $this->db->set('views', 1);
                
                $this->db->insert('stats_periods');
            }
        }
        
    }
    
    public function views_last_minutes($property_id, $n_minutes)
    {
        $parts_5_min = intval($n_minutes/5);
        
        $this->db->select_sum('views');
        $this->db->where('property_id', $property_id);
        $this->db->where('time_part_5min >', date('Y-m-d H:i:s', time()-35*60));
        $this->db->order_by('id DESC');
        $this->db->limit($parts_5_min);
        
        $query = $this->db->get($this->_table_name);
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
        
           return $row->views;
        } 
        
        return 0;
    }
    
    public function get_days_dataset($property_id, $last_days = 12)
    {
        $dataset = array();
        // ["January", "February", "March", "April", "May", "June", "July"]
        $dataset['labels'] = '[]';
        // [65, 59, 80, 81, 56, 55, 40]
        $dataset['data'] = '[]';
        
        $days = array();
        for ($i = $last_days-1; $i>=0; $i--)
        {
            $days[] = date("j.m.", strtotime("-$i days"));
        }
        
        $dataset['labels'] = '["'.implode('", "', $days).'"]';
        
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'DAY');
        $this->db->limit($last_days);
        $q = $this->db->get('stats_periods');
        $logged = array();
        if ( $q->num_rows() > 0 )
        {
            foreach ($q->result() as $row)
            {
                $logged[date("j.m.", strtotime($row->date))] = $row->views;
            }
        }
        
        $prepare_dataset = array();
        foreach($days as $key=>$r_days)
        {
            if(isset($logged[$r_days]))
            {
                $prepare_dataset[] = $logged[$r_days];
            }
            else
            {
                $prepare_dataset[] = 0;
            }
        }
        
        $dataset['data'] = '['.implode(', ', $prepare_dataset).']';
        
        return $dataset;
    }

    public function get_months_dataset($property_id, $last_months= 12)
    {
        $dataset = array();
        // ["January", "February", "March", "April", "May", "June", "July"]
        $dataset['labels'] = '[]';
        // [65, 59, 80, 81, 56, 55, 40]
        $dataset['data'] = '[]';
        
        $months = array();
        for ($i = $last_months-1; $i>=0; $i--)
        {
            $months[] = date("m.Y.", strtotime("-$i months"));
        }
        
        $dataset['labels'] = '["'.implode('", "', $months).'"]';
        
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'MONTH');
        $this->db->limit($last_months);
        $q = $this->db->get('stats_periods');
        $logged = array();
        if ( $q->num_rows() > 0 )
        {
            foreach ($q->result() as $row)
            {
                $logged[date("m.Y.", strtotime($row->date))] = $row->views;
            }
        }
        
        $prepare_dataset = array();
        foreach($months as $key=>$r_months)
        {
            if(isset($logged[$r_months]))
            {
                $prepare_dataset[] = $logged[$r_months];
            }
            else
            {
                $prepare_dataset[] = 0;
            }
        }
        
        $dataset['data'] = '['.implode(', ', $prepare_dataset).']';
        
        return $dataset;
    }
    
    public function get_years_dataset($property_id, $last_years = 12)
    {
        $dataset = array();
        // ["January", "February", "March", "April", "May", "June", "July"]
        $dataset['labels'] = '[]';
        // [65, 59, 80, 81, 56, 55, 40]
        $dataset['data'] = '[]';
        
        $years = array();
        for ($i = $last_years-1; $i>=0; $i--)
        {
            $years[] = date("Y.", strtotime("-$i years"));
        }
        
        $dataset['labels'] = '["'.implode('", "', $years).'"]';
        
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'YEAR');
        $this->db->limit($last_years);
        $q = $this->db->get('stats_periods');
        $logged = array();
        if ( $q->num_rows() > 0 )
        {
            foreach ($q->result() as $row)
            {
                $logged[date("Y.", strtotime($row->date))] = $row->views;
            }
        }
        
        $prepare_dataset = array();
        foreach($years as $key=>$r_years)
        {
            if(isset($logged[$r_years]))
            {
                $prepare_dataset[] = $logged[$r_years];
            }
            else
            {
                $prepare_dataset[] = 0;
            }
        }
        
        $dataset['data'] = '['.implode(', ', $prepare_dataset).']';
        
        return $dataset;
    }
    
    public function get_weeks_dataset($property_id, $last_weeks = 12)
    {
        $dataset = array();
        // ["January", "February", "March", "April", "May", "June", "July"]
        $dataset['labels'] = '[]';
        // [65, 59, 80, 81, 56, 55, 40]
        $dataset['data'] = '[]';
        
        $weeks = array();
        for ($i = $last_weeks-1; $i>=0; $i--)
        {
            $weeks[] = date("W.Y.", strtotime("-$i weeks"));
        }
        
        $dataset['labels'] = '["'.implode('", "', $weeks).'"]';
        
        $this->db->where('property_id', $property_id);
        $this->db->where('period', 'WEEK');
        $this->db->limit($last_weeks);
        $q = $this->db->get('stats_periods');
        $logged = array();
        if ( $q->num_rows() > 0 )
        {
            foreach ($q->result() as $row)
            {
                $logged[date("W.Y.", strtotime($row->date))] = $row->views;
            }
        }
        
        $prepare_dataset = array();
        foreach($weeks as $key=>$r_weeks)
        {
            if(isset($logged[$r_weeks]))
            {
                $prepare_dataset[] = $logged[$r_weeks];
            }
            else
            {
                $prepare_dataset[] = 0;
            }
        }
        
        $dataset['data'] = '['.implode(', ', $prepare_dataset).']';
        
        return $dataset;
    }

}



