<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lang extends CI_Controller {

	
	
	# Changement de langue
	public function index($lang = 'fr')
	{
		if($lang == 'en') $newlang = 'english';
		else $newlang = 'french';
		
		# Si connecté, mise à jour du profil
		if(User::is_connected())
			$this->db->query("UPDATE users SET Language = ? WHERE UserID = ?", array($newlang, User::id()));
		
		# On règle un cookie
		setcookie('tc_lang', $newlang, time() + 5184000);
		
		# On stocke dans une session
		$_SESSION['lang'] = $newlang;
		
		# On retourne
		/*if($this->input->get('goto'))
			redirect(BASE_URL.$this->input->get('goto'));
		else*/if(isset($_SERVER['HTTP_REFERER']) && preg_match("#^".BASE_URL."#i", $_SERVER['HTTP_REFERER']))
			redirect($_SERVER['HTTP_REFERER']);
		else
			redirect(BASE_URL);
	}
	
	
	
}

/* End of file lang.php */
/* Location: ./application/controllers/lang.php */