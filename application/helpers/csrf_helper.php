<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/* Génère un jeton CSRF */
function generate_csrf_token()
{
	return uniqid(mt_rand(), true);
}


/* Compare le jeton CSRF de l'utilisateur avec celui passé en paramètre */
function check_csrf_token($token = 'none', $issue_message = true)
{
	if(User::is_connected() && $_SESSION['user']->CSRF == ($token == 'none' ? get_instance()->input->post('csrf_token') : $token) )
		return true;
	if($issue_message)
		addFlash(lang('invalidcsrf_flash'), 'error');
	return false;
}


/* Insère le jeton CSRF dans un formulaire */
function csrf_token_input($name = 'csrf_token') {
	return '<input type="hidden" name="'.$name.'" value="'.User::csrf_token().'" />';
}


/* End of file csrf_helper.php */
/* Location: ./application/helpers/csrf_helper.php */