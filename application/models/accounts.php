<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends CI_Model {
/* Comptes, activations, etc. */



	public function create_account()
	{
		# Création du compte
		$ins = array(
			'Email' => $this->input->post('subsEmail'),
			'Username' => $this->input->post('subsUsername'),
			'PasswordPreSalt' => generate_salt(),
			'PasswordPostSalt' => generate_salt(),
			'Language' => User::lang()
		);
		$ins['PasswordSalted'] = hash('sha512', $ins['PasswordPreSalt'].$this->input->post('subsPass').$ins['PasswordPostSalt']);
		$this->db->insert('users', $ins);
		
		# Création du token de validation
		$ins = array(
			'Token' => $this->db->insert_id().'x'.md5(uniqid(rand(), TRUE)),
			'UserID' => $this->db->insert_id()
		);
		$this->db->insert('activations', $ins);
		
		return $ins['Token'];
	}
	
	
	
	public function get_activation_details($token)
	{
		if(empty($token)) return false;
		
		else {
			$Q = $this->db->query("SELECT UserID FROM activations WHERE token = ? LIMIT 1", array(trim($token)));
			if($Q->num_rows() == 0) return false;
			return $Q->row();
		}
	}
	
	
	
	public function change_account_email($old, $new)
	{
		$this->db->query("UPDATE users SET Email = ? WHERE Email = ?", array($new, $old));
		return $this->db->affected_rows();
	}
	
	
	
	public function activate_account($id)
	{
		return $this->db->query("UPDATE users SET Active = 1 WHERE UserID = ".intval($id)." LIMIT 1");
	}
	
	
	
	public function get_activation_token($email)
	{
		# Récupération du compte
		$Q = $this->db->query("SELECT UserID FROM users WHERE Email = ? AND Active = 0", array($email));
		if($Q->num_rows() == 0) return false;
		
		# Récupération et renvoi du token
		$T = $this->db->query("SELECT ActivationID, Token FROM activations WHERE UserID = ?", array($Q->row()->UserID))->row();
		return array($T->ActivationID, $T->Token);
	}
	
	
	
	public function reset_activation_token($ActivationID, $Token)
	{
		$newToken = substr($Token, 0, strpos($Token, 'x')).'x'.md5(uniqid(rand(), TRUE));
		$this->db->query("UPDATE activations SET Token = ? WHERE ActivationID = ?", array($newToken, $ActivationID));
		return $newToken;
	}
	
	
	
	public function reset_password($email)
	{
		$password = "";
		for($i = 0; $i < 10; $i++)
			$password.= chr(mt_rand(33,126));

		$presalt = generate_salt();
		$postsalt = generate_salt();

		$this->db->query("UPDATE users SET PasswordPreSalt = ?, PasswordSalted = ?, PasswordPostSalt = ?, Password = '0' WHERE Email = ?", array($presalt, hash('sha512', $presalt.$password.$postsalt), $postsalt, $email));
		
		return $this->db->affected_rows() > 0 ? $password : FALSE;
	}
	
	
	
	public function verify_email_suffix($email)
	{
		return $this->db->query("SELECT SchoolID FROM schools WHERE EmailSuffix = ? LIMIT 1", array(substr($email, strpos($email, '@') + 1)))->num_rows() == 1;
	}


	
}

/* End of file accounts.php */
/* Location: ./application/models/accounts.php */
