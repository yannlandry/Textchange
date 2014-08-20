<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Classe Users, utilise les sessions natives de PHP pour gérer les utilisateurs */

class Sessions {


	public function __construct()
	{
		#ini_set('session.cookie_domain', '.localhost');
		#session_name("somename");
		session_set_cookie_params(0, '/', '.'.GLOBAL_URL);
		session_start();


		$CI =& get_instance();


		if($this->is_connected()):
			# Nothing


		# Connexion par POST
		elseif(isset($_POST['log_email'], $_POST['log_password'])):
			$targetField = strpos($CI->input->post('log_email'), '@') === FALSE ? 'Username' : 'Email';
			$Q = $CI->db->query("SELECT UserID, Username, Email, PasswordPreSalt, PasswordSalted, PasswordPostSalt, RealName, Rights, Banned, Active, SchoolID, Picture FROM users
										WHERE ".$targetField." = ? LIMIT 1", array(trim($_POST['log_email'])));

			try {
				if($Q->num_rows() == 0) # Utilisateur inexistant
					throw new Exception('BAD');

				$R = $Q->row();
				if(hash('sha512', $R->PasswordPreSalt.$_POST['log_password'].$R->PasswordPostSalt) != $R->PasswordSalted) # Mauvais mot de passe
					throw new Exception('BAD');

				if($R->Banned == 1)
					throw new Exception('BAN');
				if($R->Active == 0)
					throw new Exception('INA');

				# On a survécu, it's session time
				$_SESSION['user'] = (object)array(
					'UserID' => $R->UserID,
					'Username' => $R->Username,
					'RealName' => $R->RealName,
					'Email' => $R->Email,
					'PasswordPreSalt' => $R->PasswordPreSalt,
					'PasswordSalted' => $R->PasswordSalted,
					'PasswordPostSalt' => $R->PasswordPostSalt,
					'Rights' => $R->Rights,
					'SchoolID' => $R->SchoolID,
					'Picture' => $R->Picture,
					'CSRF' => generate_csrf_token()
				);

				# Cookies ?
				if(isset($_POST['keep_me_in'])) {
					setcookie('tc_id', $R->UserID, time() + 604800, '/', GLOBAL_URL);
					setcookie('tc_pass', $R->PasswordSalted, time() + 604800, '/', GLOBAL_URL);
				}

				# Redirection
				if(!empty($_GET['goto'])) redirect(BASE_URL.'/'.$_GET['goto']); # S'il fallait rediriger ailleurs, on le fait
				else redirect(BASE_URL); # Sinon, on redirige vers l'accueil
			}
			catch(Exception $e) {
				$M = $e->getMessage();

				if($M == 'BAD')
					addFlash('wrongids_flash', 'error', true);
				elseif($M == 'BAN')
					addFlash('wasbanned_flash', 'error', true);
				elseif($M == 'INA')
					addFlash('notactivated_flash', 'error', true);

				redirect(base_url('/login').(!empty($_GET['goto']) ? ('?goto='.$_GET['goto']) : NULL));
			}


		# Connexion par cookies
		elseif(isset($_COOKIE['tc_id'], $_COOKIE['tc_pass'])):
			$Q = $CI->db->query("SELECT UserID, Username, Email, PasswordPreSalt, PasswordSalted, PasswordPostSalt, RealName, Rights, Banned, Active, SchoolID, Picture FROM users WHERE UserID = ? AND PasswordSalted = ? LIMIT 1",
					array($_COOKIE['tc_id'], $_COOKIE['tc_pass']));
			if($Q->num_rows() == 1) {
				$R = $Q->row();
				unset($Q);
				if($R->Banned == 0 && $R->Active == 1) {
					$_SESSION['user'] = (object)array(
						'UserID' => $R->UserID,
						'Username' => $R->Username,
						'RealName' => $R->RealName,
						'Email' => $R->Email,
						'PasswordPreSalt' => $R->PasswordPreSalt,
						'PasswordSalted' => $R->PasswordSalted,
						'PasswordPostSalt' => $R->PasswordPostSalt,
						'Rights' => $R->Rights,
						'SchoolID' => $R->SchoolID,
						'Picture' => $R->Picture,
						'CSRF' => generate_csrf_token()
					);
				}
			}
			else {
				$this->destroy_autoconnect();
			}


		# Cas 4 : Session vierge
		else:
			$_SESSION['user'] = NULL;


		endif;
	}



	public function is_connected()
	{
		# On vérifie les variables principales, les autres étant simplement réinitialisées sur demande
		return isset($_SESSION['user']) && is_object($_SESSION['user']);
	}



	public function logoff($goto = NULL)
	{
		if($this->is_connected()) {
			# On crée/modifie quelques sessions pour le bon fonctionnement
			$this->set_blank_session();

			# Destruction des cookies de connexion automatique
			$this->destroy_autoconnect();
		}
	}



	public function set_blank_session()
	{
		$_SESSION['user'] = NULL;
	}



	public function destroy_autoconnect()
	{
		setcookie('tc_id', '', 0, '/', GLOBAL_URL);
		setcookie('tc_pass', '', 0, '/', GLOBAL_URL);
	}


}
// END Users Class

/* End of file Users.php */
/* Location: ./application/libraries/Users.php */
