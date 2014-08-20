<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


abstract class User
{
	
	// Utilisateur connecté ?
	public function is_connected()
	{
		return isset($_SESSION['user']) && is_object($_SESSION['user']);
	}
	
	// Renvoie l'ID. Si $id est > 0, compare plutôt l'entier
	public function id($id = 0)
	{
		if(!empty($id)) return User::is_connected() && $_SESSION['user']->UserID == $id;
		elseif(User::is_connected()) return $_SESSION['user']->UserID;
		else return 0;
	}
	
	// Renvoie le vrai nom si disponible, sinon le nom d'utilisateur
	public function name($noreal = FALSE)
	{
		if(!$noreal && $_SESSION['user']->RealName) return $_SESSION['user']->RealName;
		else return $_SESSION['user']->Username;
	}
	
	// Renvoie l'e-mail
	public function email()
	{
		if(User::is_connected()) return $_SESSION['user']->Email;
		else return false;
	}
	
	// Renvoie le niveau de droits
	public function rights()
	{
		if(!User::is_connected()) return 0;
		else return $_SESSION['user']->Rights;
	}
	
	// Vérifie les droits
	public function is_mod() { return User::rights() >= 1; }
	public function is_admin() { return User::rights() >= 2; }
	
	// Renvoie la langue sélectionnée
	public function lang()
	{
		if(defined('TEXTCHANGE_LANG')) return TEXTCHANGE_LANG;
		else return 'english';
	}
	
	// Renvoie l'objet de session
	public function &session()
	{
		if(User::is_connected()) return $_SESSION['user'];
		else return NULL;
	}
	
	public function searchprefs($key, $assign = NULL)
	{
		if(!empty($assign)) $_SESSION['searchpref_'.$key] = $assign;
		
		if(!empty($_SESSION['searchpref_'.$key])) return $_SESSION['searchpref_'.$key];
		else return false;
	}
	
	public function csrf_token()
	{
		if(User::is_connected()) return $_SESSION['user']->CSRF;
		else return NULL;
	}
}


/* End of file user_helper.php */
/* Location: ./application/helpers/user_helper.php */