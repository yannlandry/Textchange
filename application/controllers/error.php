<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {
/* Erreurs 403, 404, etc. */

	
	
	# Page d'accueil
	public function index($code = 404)
	{
		$this->lang->load('general', User::lang());
		$this->lang->load('error', User::lang());
		
		$code = intval($code);
		
		$headerConfig = array(
			'title' => lang('error')." ".$code,
			'styles' => array('error'),
			'path' => array(lang('error')." ".$code => ''),
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('error/error', array('code' => $code));
		$this->load->view('common/footer');
	}
	
	
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */