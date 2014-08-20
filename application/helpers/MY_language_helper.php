<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Additions et modifications au helper de langue */

if ( ! function_exists('lang'))
{
	function lang($key, $id = '')
	{
		$CI =& get_instance();
		$line = $CI->lang->line($key);
		
		if(empty($line) && defined('ENVIRONMENT') && (ENVIRONMENT == 'development' || ENVIRONMENT == 'testing'))
		{
			$line = "(NT)[".$key."]";
		}

		if ($id != '')
		{
			$line = '<label for="'.$id.'">'.$line."</label>";
		}

		return $line;
	}
}

if ( ! function_exists('bil'))
{
	function bil($french, $english, $noprint = FALSE)
	{
		$out = User::lang() == 'french' ? $french : $english;
		
		if($noprint) return $out;
		else print $out;
	}
}

// ------------------------------------------------------------------------
/* End of file MY_language_helper.php */
/* Location: ./application/helpers/MY_language_helper.php */