<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Site URL without suffix
 *
 * Create a local URL based on your basepath. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if (! function_exists('site_url_nosuff'))
{
	function site_url_nosuff($uri = '')
	{
		$CI =& get_instance();
		return str_replace($CI->config->item('url_suffix'), '', $CI->config->site_url($uri));
	}
}

if (! function_exists('site_url_q'))
{
	function site_url_q($uri = '', $q = '')
	{
		$CI =& get_instance();
        
        if($CI->config->item('enable_query_strings') == TRUE || 
           $CI->config->item('uri_protocol') == 'QUERY_STRING')
        {
            return $CI->config->site_url($uri).'&'.$q;
        }
        else
        {
            return $CI->config->site_url($uri).'?'.$q;
        }
	}
}

if (! function_exists('base_url_check'))
{
	function base_url_check($uri = '')
	{
		$CI =& get_instance();
        
        if(substr_count($uri, base_url()) > 0)
        {
            return $uri;
        }
        else
        {
            return base_url($uri);
        }
	}
}

/**
 * Create URL Title
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @access	public
 * @param	string	the string
 * @param	string	the separator: dash, or underscore
 * @return	string
 */
if (! function_exists('url_title_cro'))
{
	function url_title_cro($str, $separator = 'underscore', $lowercase = TRUE)
	{
		if ($separator == 'dash')
		{
			$search		= '_';
			$replace	= '-';
		}
		else
		{
			$search		= '-';
			$replace	= '_';
		}
		
		$dot='';
		if($separator == 'dot'){
			$str = str_replace(' ', '.', $str);
			$dot='.';
		}
		
		$trans = array(
						$search								=> $replace,
						"\s+"								=> $replace,
						"[^a-z0-9".$replace.$dot."]"		=> '',
						$replace."+"						=> $replace,
						$replace."$"						=> '',
						"^".$replace						=> ''
					   );
        
        // For Croatia
		$str = str_replace(array('č','ć','ž','š','đ', 'Č','Ć','Ž','Š','Đ'), 
						   array('c','c','z','s','d', 'c','c','z','s','d'), $str);
                           
        // For Turkish
		$str = str_replace(array('ş','Ş','ı','İ','ğ','Ğ','Ü','ü','Ö','ö','ç','Ç'),
						   array('s','s','i','i','g','g','u','u','o','o','c','c'), $str);  
        
        // Russian alphabet
		$str = str_replace(array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'),
						   array('a','b','v','g','d','e','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','kh','c','ch','sh','sh','','y','','e','yu','ya'), $str);
        $str = str_replace(array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'),
						   array('a','b','v','g','d','e','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','kh','c','ch','sh','sh','','y','','e','yu','ya'), $str);
        
        // Ukrainian alphabet
       	$str = str_replace(array('Ґ','Є','І','Ї'),
						   array('G','E','I','I'), $str);
        $str = str_replace(array('ґ','є','і','ї'),
						   array('g','e','i','i'), $str);
        // Symbols
        $str = str_replace(array("  ","’","–",'«','»','№','„','”'),
						   array("","","-",'','','no','',''), $str);
        
        // Alphabets Czech Croatian Turkish and other
        $str = str_replace(array('Á','Ä','Ď','É','Ě','Ë','Í','Ň','Ń','Ó','Ŕ','Ř','Ť','Ú','Ů','Ý','Ź','Č','Ć','Ž','Š','Đ','Ş','İ','Ğ','Ü','Ö','Ç'),
						   array('a','a','d','e','e','e','i','n','n','o','r','r','t','u','u','y','z','c','c','z','s','d','s','i','g','u','o','c'), $str);
        $str = str_replace(array('á','ä','ď','é','ě','ë','í','ň','ń','ó','ŕ','ř','ť','ú','ů','ý','ź','č','ć','ž','š','đ','ş','ı','ğ','ü','ö','ç'),
						   array('a','a','d','e','e','e','i','n','n','o','r','r','t','u','u','y','z','c','c','z','s','d','s','i','g','u','o','c'), $str);

        // For french
		$str = str_replace(array('â','é','è','û','ê', 'à','Â','ç','ï','î','ä','î'), 
						   array('a','e','e','u','e', 'a','c','c','i','î','a','î'), $str);
        
        // Bulgarian alphabet
//        $str = str_replace(array('Х','Щ','Ъ','Ь'),
//                           array('H','SHT','A','Y'), $str);
//        $str = str_replace(array('х','щ','ъ','ь'),
//                           array('h','sht','a','y'), $str);

        // Greek alphabet
//		$str = str_replace(array('Α','Ά','Β','Γ','Δ','Ε','Έ','Ζ','Η','Ή','Θ','Ι','Ί','Κ','Λ','Μ','Ν','Ξ','Ο','Ό','Π','Ρ','Σ','Τ','Υ','Ύ','Φ','Χ','Ψ','Ω','Ώ'),
//						  array('a','a','v','g','d','e','e','z','i','i','th','i','i','k','l','m','n','x','o','o','p','r','s','t','y','y','f','x','ph','o','o'), $str);
//                $str = str_replace(array('α','ά','β','γ','δ','ε','έ','ζ','η','ή','θ','ι','ί','ϊ','ΐ','κ','λ','μ','ν','ξ','ο','ό','π','ρ','σ','ς','τ','υ','ύ','ϋ','φ','χ','ψ','ω','ώ'),
//						  array('a','a','v','g','d','e','e','z','i','i','th','i','i','i','i','k','l','m','n','x','o','o','p','r','s','s','t','y','y','y','f','x','ph','o','o'), $str);	
//


        $str = strip_tags(strtolower($str));

		
		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#", $val, $str);
		}
	
		return trim(stripslashes($str));
	}
}

/**
 * HTML Redirect
 *
 * Usage in specific situations like on payment provider etc...
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the method: location or redirect
 * @return	string
 */
if ( ! function_exists('redirect_html'))
{
	function redirect_html($uri = '', $message = 'Redirecting ...')
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}
       
        $html = '<!DOCTYPE html>
        <html>
            <head>
                <title>Redirecting...</title>
                <meta http-equiv="refresh" content="0; url='.$uri.'" />
                <script type="text/javascript">
                    window.location.href = "'.$uri.'"
                </script>
            </head>
            <body>
                <h2>'.$message.'</h2>
                <!-- Note: don\'t tell people to `click` the link, just tell them that it is a link. -->
                If you are not redirected automatically, follow the <a href=\''.$uri.'\'>link</a>
            </body>
        </html>';
        
        exit($html);
	}
}

/**
 * Site URL
 *
 * Create a local URL based on your basepath. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('site_url') && config_item('json_url_encoding')==TRUE)
{
	function site_url($uri = '')
	{
            $uri = str_replace('}', '%7D', $uri);
            $uri = str_replace('{', '%7B', $uri);
            
            $CI =& get_instance();
            return $CI->config->site_url($uri);
	}
}


/**
 * Anchor Link
 *
 * Creates an anchor based on the local URL.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the link title
 * @param	mixed	any attributes
 * @return	string
 */
if ( ! function_exists('anchor'))
{
	function anchor($uri = '', $title = '', $attributes = '')
	{
		$title = (string) $title;

		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? slug_url($uri) : $uri;
		}
		else
		{
			$site_url = slug_url($uri);
		}

		if ($title == '')
		{
			$title = $site_url;
		}

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}
?>