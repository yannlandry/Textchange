<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// ------------------------------------------------------------------------
// Append GET vars - Append all current GET vars to an URI
if ( ! function_exists('append_get_vars'))
{
	function append_get_vars($uri = '', $append = array(), $exclude_keys = array())
	{
		$get_vars = !empty($_GET) ? $_GET : array();
		
		if(!empty($exclude_keys) && !empty($get_vars))
			foreach($get_vars as $key => $val)
				if(in_array($key, $exclude_keys))
					unset($get_vars[$key]);
		
		if(!empty($append))
			$get_vars = array_merge($get_vars, $append);
		$appendix = array();
		foreach($get_vars as $key => $val)
			$appendix[] = $key.'='.$val;
		
		if(!empty($appendix)) return $uri."?".implode("&", $appendix);
		else return $uri;
	}
}


// ------------------------------------------------------------------------
// Pass GET vars - Send GET vars over a search form
if ( ! function_exists('pass_get_vars'))
{
	function pass_get_vars($exclude_keys = array('q', 'page'))
	{
		$get_vars = !empty($_GET) ? $_GET : array();
		$output = '';
		
		if(!empty($exclude_keys) && !empty($get_vars))
			foreach($get_vars as $key => $val)
				if(in_array($key, $exclude_keys))
					unset($get_vars[$key]);
		
		foreach($get_vars as $key => $val)
			$output.= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
		
		return $output;
	}
}


// ------------------------------------------------------------------------
// Get page - Get page passed via GET
if ( ! function_exists('get_page'))
{
	function get_page($var = 'page')
	{
		if(!empty($_GET[$var]) && intval($_GET[$var]) > 0) return intval($_GET[$var]);
		else return 1;
	}
}


// ------------------------------------------------------------------------
// Header redirect - Added "Continue" link so browsers not following
// headers won't be stuck
if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri);
		}

		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit('<a href="'.$uri.'">Continue</a>');
	}
}


// ------------------------------------------------------------------------
// Prompt login - Ask to login to see page
if ( ! function_exists('prompt_login'))
{
	function prompt_login($return = '', $showFlash = true)
	{
		if($showFlash) addFlash(lang('logintocontinue_flash'), 'warning');
		
		$uri = base_url('/login');
		
		if(!empty($return)) {
			$uri.= '?goto=';
			if($return[0] == '/') $return = substr($return, 1);
			$uri.= $return;
		}
		
		redirect($uri);
	}
}


/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */