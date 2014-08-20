<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schools_model extends CI_Model {
/* Profils, membres, etc. */



	const PICTURES_PATH = 'uploads/schools';
	
	
	
	# Tous les établissements
	public function get_all_schools($start = 0, $length = 10)
	{
		$rescount = intval($this->db->query("SELECT COUNT(*) AS SchoolsCount FROM schools")->row()->SchoolsCount);
		
		$results = $this->db->query("SELECT SchoolID, Town, Province, Picture, ".ucfirst(User::lang())."Name AS SchoolName FROM schools ORDER BY SchoolName ASC LIMIT ?,?", array($start, $length))->result();
		
		$results[] = $rescount;
		return $results;
	}
	
	
	
	# Information d'établissement
	public function get_school_info($id)
	{
		$Q = $this->db->query("SELECT SchoolID, FrenchName, EnglishName, Town, Province, Picture, EmailSuffix FROM schools WHERE SchoolID = ? LIMIT 1", array($id));
		
		if($Q->num_rows == 0) return FALSE;
		else return $Q->row();
	}
	
	
	
	# Ajout d'établissement
	public function register_school()
	{
		$data = array(
			'FrenchName' => $this->input->post('frenchname'),
			'EnglishName' => $this->input->post('englishname'),
			'Town' => $this->input->post('town'),
			'Province' => $this->input->post('province'),
			'EmailSuffix' => $this->input->post('emailsuffix')
		);
		
		if(isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK)
			$data['Picture'] = $this->upload_school_picture($_FILES['picture']);
		
		return $this->db->insert('schools', $data);
	}
	
	
	
	# Mise à jour d'établissement
	public function update_school_info($id)
	{
		$data = array(
			'FrenchName' => $this->input->post('frenchname'),
			'EnglishName' => $this->input->post('englishname'),
			'Town' => $this->input->post('town'),
			'Province' => $this->input->post('province'),
			'EmailSuffix' => $this->input->post('emailsuffix')
		);
		
		if(isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK)
			$data['Picture'] = $this->upload_school_picture($_FILES['picture']);
		
		$this->db->where(array('SchoolID' => intval($id)));
		return $this->db->update('schools', $data);
	}
	
	
	
	# Upload d'image
	private function upload_school_picture(&$F)
	{
		$savename = time();
		list($w, $h) = getimagesize($F['tmp_name']);
		if($w > 50 || $h > 50) {
			$ext = get_file_ext($F['name']);
			$I = create_image_from_file($F['tmp_name'], $ext);
			$I = resize_image($I, 50, 50, true);
			save_image_to_file($I, self::PICTURES_PATH.'/'.$savename, $ext);
		}
		else move_uploaded_file($F['tmp_name'], self::PICTURES_PATH.'/'.$savename.'.'.$ext);
		return $savename.'.'.$ext;
	}
	
	
	
}

/* End of file schools_model.php */
/* Location: ./application/models/schools_model.php */