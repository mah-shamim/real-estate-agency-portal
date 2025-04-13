<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

	/**
	 * Error String
	 *
	 * Returns the error messages as a string, wrapped in the error delimiters
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	str
	 */
	public function error_string($prefix = '', $suffix = '')
	{

		// No errrors, validation passes!
		if (sw_count($this->_error_array) === 0)
		{
			return '';
		}

		if ($prefix == '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '')
		{
			$suffix = $this->_error_suffix;
		}

		// Generate the error string
		$str = '';
        
		foreach ($this->_error_array as $key=>$val)
		{
			if ($val != '')
			{
			    $lang_id = substr(strrchr($key, '_'), 1);
                
                if(!empty($lang_id) && is_numeric($lang_id))
                {
                    $CI =& get_instance();
                    if(isset($CI->form_languages[$lang_id]))
                        $val.=' ('.$CI->form_languages[$lang_id].')';
                }
             
				$str .= $prefix.$val.$suffix."\n";
			}
		}

		return $str;
	}

	public function exists($str, $field)
	{
		list($table, $field)=explode('.', $field);
		$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
		
		return $query->num_rows() > 0;
    }
    
	public function quote_fix(&$str)
	{        
        $str = str_replace('\'', '', $str);
        $str = str_replace('"', '', $str);
        
        return TRUE;
	}

	/**
	 * Valid Email
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix", $str)) ? FALSE : TRUE;
	}

}