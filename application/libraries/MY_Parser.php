<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Parser extends CI_Parser {

	/**
	 *  Parse a template
	 *
     *  sandi.winter: fix for multiple elements
     * 
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function _parse($template, $data, $return = FALSE)
	{
		if ($template == '')
		{
			return FALSE;
		}

		foreach ($data as $key => $val)
		{
			if (is_array($val))
			{
			    for($i=0;$i<5;$i++)
                {
                    $template = $this->_parse_pair($key, $val, $template);
                    if(substr_count($template, $key)==0)
                    {
                        break;
                    }
                }
				
			}
			else
			{
			    for($i=0;$i<5;$i++)
                {
                    if(gettype($val) != 'string')
                        continue;
                    
                    $template = $this->_parse_single($key, (string)$val, $template);
                    if(!empty($key) && substr_count($template, $key)==0)
                    {
                        break;
                    }
                }
			}
		}

		if ($return == FALSE)
		{
			$CI =& get_instance();
			$CI->output->append_output($template);
		}

		return $template;
	}
    
	// --------------------------------------------------------------------

	/**
	 *  Parse a tag pair
	 *
	 * Parses tag pairs:  {some_tag} string... {/some_tag}
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @param	string
	 * @return	string
	 */
	function _parse_pair($variable, $data, $string)
	{
		if (FALSE === ($match = $this->_match_pair($string, $variable)))
		{
			return $string;
		}

		$str = '';
		foreach ($data as $row)
		{
			$temp = $match['1'];
            
            if(is_array($row) || is_object($row))
    			foreach ($row as $key => $val)
    			{
    				if ( ! is_array($val))
    				{
    					$temp = $this->_parse_single($key, $val, $temp);
    				}
    				else
    				{
    					$temp = $this->_parse_pair($key, $val, $temp);
    				}
    			}

			$str .= $temp;
		}

		return str_replace($match['0'], $str, $string);
	}

}
// END Parser Class

/* End of file Parser.php */
/* Location: ./system/libraries/Parser.php */
