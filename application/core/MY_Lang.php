<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Lang extends CI_Lang {

	// --------------------------------------------------------------------

	/**
	 * Load a language file
	 *
	 * @access	public
	 * @param	mixed	the name of the language file to be loaded. Can be an array
	 * @param	string	the language (english, etc.)
	 * @param	bool	return loaded array of translations
	 * @param 	bool	add suffix to $langfile
	 * @param 	string	alternative path to look for language file
	 * @return	mixed
	 */
	function load($langfile = '', $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
	{       
        parent::load($langfile, $idiom, $return, $add_suffix, $alt_path);
	}
    
    function get_array()
    {
        return $this->language;
    }
    
	function line($line = '')
	{
		$value = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];

		// Because killer robots like unicorns!
		if ($value === FALSE)
		{
			log_message('debug', 'Could not find the language line "'.$line.'"');
		}

		return $value;
	}

}

