<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profiles extends CI_Model {
/* Profils, membres, etc. */



	# Recherche
	public function get_all_profiles($start = 0, $length = 10)
	{
		$rescount = intval($this->db->query("SELECT COUNT(*) AS UsersCount FROM users")->row()->UsersCount);
		
		$results = $this->db->query("SELECT Username, RealName, Picture, Rights, (SELECT ".ucfirst(User::lang())."Name FROM schools WHERE SchoolID = users.SchoolID) AS SchoolName FROM users ORDER BY UserID DESC LIMIT ?,?", array($start, $length))->result();
		
		$results[] = $rescount;
		return $results;
	}
	
	public function search_profiles($keywords, $start = 0, $length = 10)
	{
		$hash = md5(strtolower(trim($keywords)));
		$cachefile = 'application/cache/search/users/'.$hash.'.tmp';
		$return = array();
		
		if(file_exists($cachefile) && time() - filemtime($cachefile) < 1800) { # Récupération des résultats depuis le cache
			$results = unserialize(file_get_contents($cachefile));
		}
		else {
			$results = $this->db->query("SELECT Username, RealName, Picture, Rights, (SELECT ".ucfirst(User::lang())."Name FROM schools WHERE SchoolID = u.SchoolID) AS SchoolName FROM (SELECT Username, RealName, Picture, Rights, SchoolID, Relevance FROM (SELECT Username, RealName, Picture, Rights, SchoolID, MATCH(Username, RealName) AGAINST(?) AS Relevance FROM users) AS u WHERE Relevance) AS u ORDER BY Relevance DESC", array($keywords))->result();
			file_put_contents($cachefile, serialize($results));
		}
		
		for($i = $start; $i < $start + $length; $i++)
			if(isset($results[$i]))
				$return[] = $results[$i];
			else
				break;
		
		$return[] = count($results);
		return $return;
	}



	# Vue
	public function get_profile_data($username)
	{
		if(empty($username)) return false;
		
		$Q = $this->db->query("SELECT UserID, Email, Username, RealName, PhoneNumber, SchoolID, Information, Picture, Rights, DisplayEmail, (SELECT ".ucfirst(User::lang())."Name FROM schools WHERE SchoolID = users.SchoolID) AS SchoolName FROM users WHERE username = ? LIMIT 1", array($username));
		
		if($Q->num_rows() == 0) return false;
		else return $Q->row();
	}
	
	
	
	# Édition
	public function get_profile_edit_data($username)
	{
		if(empty($username)) return false;
		
		$Q = $this->db->query("SELECT UserID, Email, Username, RealName, PhoneNumber, SchoolID, Information, Picture, DisplayEmail, NotifyPM, UnregContact, Language FROM users WHERE username = ? LIMIT 1", array($username));
		if($Q->num_rows() == 0) return false;
		
		$P = $Q->row();
		$P->PhoneNumber = array('area' => substr($P->PhoneNumber, 0, 3), 'prefix' => substr($P->PhoneNumber, 3, 3), 'suffix' => substr($P->PhoneNumber, 6, 4));
		
		return $P;
	}
	
	public function update_profile($id)
	{
		User::session()->RealName = $this->input->post('realname');
		User::session()->SchoolID = $this->input->post('school');
		$data = array(	'RealName' => $this->input->post('realname'),
						'PhoneNumber' => $this->input->post('phone'),
						'SchoolID' => $this->input->post('school'),
						'Information' => $this->input->post('information'),
						'DisplayEmail' => (bool)$this->input->post('displayemail') ? 1 : 0,
						'NotifyPM' => $this->input->post('newpm') == 'true',
						'UnregContact' => $this->input->post('unreg') == 'true');
		
		if(in_array($this->input->post('language'), array('french', 'english')))
			$data['Language'] = $this->input->post('language');
		
		$this->db->where('UserID', $id);
		return $this->db->update('users', $data);
	}
	
	public function get_potential_schools($email)
	{
		$Q = $this->db->query("SELECT SchoolID, ".ucfirst(User::lang())."Name AS SchoolName FROM schools WHERE EmailSuffix = ?", array(substr($email, strpos($email, "@") + 1)));
		$S = array();
		
		foreach($Q->result() as $D)
			$S[$D->SchoolID] = $D->SchoolName;
		
		return $S;
	}
	
	
	
	# Changement de mot de passe
	public function get_profile_changepass_data($username)
	{
		if(empty($username)) return false;
		
		$Q = $this->db->query("SELECT UserID, Username, RealName FROM users WHERE username = ? LIMIT 1", array($username));
		if($Q->num_rows() == 0) return false; 
		
		return $Q->row();
	}
	
	public function change_password($id)
	{
		$this->db->where('UserID', $id);
		
		User::session()->PasswordPreSalt = generate_salt();
		User::session()->PasswordPostSalt = generate_salt();
		User::session()->PasswordSalted = hash('sha512', User::session()->PasswordPreSalt.$this->input->post('newpass').User::session()->PasswordPostSalt);
		
		$this->db->query("UPDATE users SET PasswordPreSalt = ?, PasswordSalted = ?, PasswordPostSalt = ?, Password = '0' WHERE UserID = ?",
				array(User::session()->PasswordPreSalt, User::session()->PasswordSalted, User::session()->PasswordPostSalt, User::id()));
		
		return true;
	}
	
	
	
	# Changement d'adresse courriel
	public function get_profile_changemail_data($username)
	{
		if(empty($username)) return false;
		
		$Q = $this->db->query("SELECT UserID, Username, Email, RealName FROM users WHERE username = ? LIMIT 1", array($username));
		if($Q->num_rows() == 0) return false; 
		
		return $Q->row();
	}
	
	public function create_newmail_token($id)
	{
		$this->db->delete('changemail', array('UserID' => $id));
		
		$data = array(	'Token' => $id.'x'.md5(uniqid(rand(), TRUE)),
						'UserID' => $id,
						'NewEmail' => $this->input->post('newmail'));
		
		$this->db->insert('changemail', $data);
		
		return $data['Token'];				
	}
	
	public function get_changemail_token_data($token)
	{
		if(empty($token)) return false;
		
		$Q = $this->db->query("SELECT Token, UserID, NewEmail FROM changemail WHERE Token = ? AND UserID = ? LIMIT 1", array($token, User::id()));
		if($Q->num_rows() == 0) return false; 
		
		return $Q->row();
	}
	
	public function change_email($id, $email, $resetSchool = false)
	{
		$data = array('Email' => $email);
		User::session()->Email = $email;
		if($resetSchool) {
			User::session()->SchoolID = '';
			$data['SchoolID'] = "";
		}
		
		$this->db->where('UserID', $id);
		return $this->db->update('users', $data);
	}
	
	public function destroy_changemail_token($token)
	{
		return $this->db->delete('changemail', array('Token' => $token));
	}
	
	
	
	# Changement d'avatar
	public function get_profile_changepic_data($username)
	{
		if(empty($username)) return false;
		
		$Q = $this->db->query("SELECT UserID, Username, RealName, Picture FROM users WHERE username = ? LIMIT 1", array($username));
		if($Q->num_rows() == 0) return false; 
		
		return $Q->row();
	}
	
	public function upload_pic($id)
	{
		$F =& $_FILES['new-pic'];
		$savename = $id.'_'.time();
		list($w, $h) = getimagesize($F['tmp_name']);
		$ext = get_file_ext($F['name']);
		
		$I = create_image_from_file($F['tmp_name'], $ext);
		if($w > 160 || $h > 160) $I = resize_image($I, 160, 160, true);
		save_image_to_file($I, 'uploads/avatars/'.$savename, $ext, 75);
		
		User::session()->Picture = $savename.'.'.$ext;
		return $this->db->query("UPDATE users SET Picture = ? WHERE UserID = ?", array(User::session()->Picture, $id));
	}
	
	public function delete_pic($id)
	{
		User::session()->Picture = '';
		return $this->db->query("UPDATE users SET Picture = '' WHERE UserID = ? LIMIT 1", array($id));
	}
	
	
	
	# Signalement
	public function get_profile_report_data($username)
	{
		if(empty($username)) return false;
		
		$Q = $this->db->query("SELECT UserID, Username, RealName FROM users WHERE username = ? LIMIT 1", array($username));
		if($Q->num_rows() == 0) return false; 
		
		return $Q->row();
	}
	
	public function report_profile($UserID)
	{
		$data = array(
			'UserID' => $UserID,
			'ByUserID' => User::is_connected() ? User::id() : 0,
			'Reason' => $this->input->post('reason')
		);
		
		$this->db->insert('profiles_reports', $data);
		
		return $this->db->insert_id();
	}
	
	
	
}

/* End of file profiles.php */
/* Location: ./application/models/profiles.php */