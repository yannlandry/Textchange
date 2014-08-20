<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * MY_Pagination Class : Refaite pour subvenir aux besoins de Textchange
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		Yann Landry
 * @link		http://textchange.ca
 */

class MY_Pagination {

	var $base_url			= ''; // The page we are linking to

	// Informations obligatoires
	var $total_rows			=  0; // Total number of items (database results)
	var $per_page			= 10; // Max number of items you want shown per page
	var $num_links			=  3; // Number of "digit" links to show before/after the currently viewed page
	var $cur_page			=  0; // The current page being viewed
	
	// Préfixe/suffixe des liens
	var $first_link			= '<i class="icon-fast-backward"></i>';
	var $next_link			= '<i class="icon-step-forward"></i>';
	var $prev_link			= '<i class="icon-step-backward"></i>';
	var $last_link			= '<i class="icon-fast-forward"></i>';
	var $full_tag_open		= '<ul>';
	var $full_tag_close		= '</ul>';
	var $first_tag_open		= '<li>';
	var $first_tag_close	= '</li>';
	var $last_tag_open		= '<li>';
	var $last_tag_close		= '</li>';
	var $cur_tag_open		= '<li class="active">';
	var $cur_tag_close		= '</li>';
	var $next_tag_open		= '<li>';
	var $next_tag_close		= '</li>';
	var $prev_tag_open		= '<li>';
	var $prev_tag_close		= '</li>';
	var $num_tag_open		= '<li>';
	var $num_tag_close		= '</li>';
	
	// Autres options
	var $page_var_name		= 'page';
	var $display_pages		= TRUE;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array())
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}

		log_message('debug', __CLASS__ . " Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	function create_links()
	{
		// Get CodeIgniter
		$CI =& get_instance();
		
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Set the base page index for starting page number
		$base_page = 1;

		// Determine the current page number, if not already set
		if(empty($this->cur_page))
		{
			$this->cur_page = get_page();
		}

		// Convertir le nombre de liens avant/après en int
		$this->num_links = intval($this->num_links);

		if ($this->num_links < 1)
		{
			$this->num_links = 1;
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = 1;
		}

		$uri_page_number = $this->cur_page;

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links :
			($this->cur_page > $num_pages ? $this->cur_page : $num_pages);
		#exit("Fin: ".$end." / Curpage: ".$this->cur_page." / Num links : ".$this->num_links." / Num pages :".$num_pages);

		// And here we go...
		$output = '';

		// Render the "First" link
		if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
		{
			$output .= $this->first_tag_open.'<a href="'.append_get_vars($this->base_url, array($this->page_var_name => 1)).'">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			$output .= $this->prev_tag_open.'<a href="'.append_get_vars($this->base_url, array($this->page_var_name => $this->cur_page - 1)).'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
		}

		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			// Write the digit links
			for ($i = $start; $i <= $end; $i++)
			{
				if($i == $this->cur_page)
				{
					$output .= $this->cur_tag_open.'<a href="'.append_get_vars($this->base_url, array($this->page_var_name => $i)).'">'.$i.'</a>'.$this->cur_tag_close;
				}
				else
				{
					$output .= $this->num_tag_open.'<a href="'.append_get_vars($this->base_url, array($this->page_var_name => $i)).'">'.$i.'</a>'.$this->num_tag_close;
				}
			}
		}

		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
		{
			$output .= $this->next_tag_open.'<a href="'.append_get_vars($this->base_url, array($this->page_var_name => $this->cur_page + 1)).'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
		{
			$output .= $this->last_tag_open.'<a href="'.append_get_vars($this->base_url, array($this->page_var_name => $num_pages)).'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;

		return $output;
	}
}
// END MY_Pagination Class

/* End of file Pagination.php */
/* Location: ./application/libraries/Pagination.php */