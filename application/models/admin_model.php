<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
/* Profils, membres, etc. */
	
	
	
	# Compte des signalements sur des annonces
	public function count_ads_reports()
	{
		return $this->db->count_all('ads_reports');
	}
	
	
	
	# Compte des NOUVEAUX signalements sur des annonces
	public function count_new_ads_reports()
	{
		return intval($this->db->query("SELECT COUNT(AdReportID) AS Count FROM ads_reports WHERE IsNew = 1")->row()->Count);
	}
	
	
	
	# Récupération de tous les signalements sur des annonces
	public function get_ads_reports($start = 0, $length = 10)
	{
		return $this->db->query("SELECT AR.AdReportID, AR.AdID, (SELECT Title FROM ads WHERE AdID = AR.AdID LIMIT 1) AS Title, AR.ByUserID, U.Username, U.RealName, AR.Reason, AR.Date, AR.IsNew FROM (SELECT AdReportID, AdID, ByUserID, Reason, Date, IsNew FROM ads_reports ORDER BY IsNew DESC, AdReportID DESC LIMIT ?,?) AS AR LEFT JOIN users AS U ON. U.UserID = AR.ByUserID LIMIT ?,?", array($start, $length, $start, $length))->result();
	}
	
	
	
	# Marque des signalements sur des annonces comme traités
	public function mark_ads_reports()
	{
		$list = $this->input->post('sel-reports');	
		if(is_array($list)) {
			array_walk($list, 'intval');
			return $this->db->query("UPDATE ads_reports SET IsNew = 0 WHERE AdReportID IN (".implode(',',$list).")");
		}
		else return false;
	}
	
	
	
	# Marque des signalements sur des annonces comme non-traités
	public function unmark_ads_reports()
	{
		$list = $this->input->post('sel-reports');		
		if(is_array($list)) {
			array_walk($list, 'intval');
			return $this->db->query("UPDATE ads_reports SET IsNew = 1 WHERE AdReportID IN (".implode(',',$list).")");
		}
		else return false;
	}
	
	
	
	# Compte des NOUVEAUX signalements sur des profils
	public function count_new_profiles_reports()
	{
		return intval($this->db->query("SELECT COUNT(ProfileReportID) AS Count FROM profiles_reports WHERE IsNew = 1")->row()->Count);
	}
	
	
	
	# Compte des signalements sur des profils
	public function count_profiles_reports()
	{
		return $this->db->count_all('profiles_reports');
	}
	
	
	
	# Récupération de tous les signalements sur des profils
	public function get_profiles_reports($start = 0, $length = 10)
	{
		return $this->db->query("SELECT AR.ProfileReportID, AR.UserID, RU.Username AS RepUsername, RU.RealName AS RepRealName, AR.ByUserID, U.Username, U.RealName, AR.Reason, AR.Date, AR.IsNew FROM (SELECT ProfileReportID, UserID, ByUserID, Reason, Date, IsNew FROM profiles_reports ORDER BY IsNew DESC, ProfileReportID DESC LIMIT ?,?) AS AR NATURAL JOIN users AS RU LEFT JOIN users AS U ON. U.UserID = AR.ByUserID LIMIT ?,?", array($start, $length, $start, $length))->result();
	}
	
	
	
	# Marque des signalements sur des profils comme traités
	public function mark_profiles_reports()
	{
		$list = $this->input->post('sel-reports');	
		if(is_array($list) && count($list)) {
			array_walk($list, 'intval');
			return $this->db->query("UPDATE profiles_reports SET IsNew = 0 WHERE ProfileReportID IN (".implode(',',$list).")");
		}
		else return false;
	}
	
	
	
	# Marque des signalements sur des profils comme non-traités
	public function unmark_profiles_reports()
	{
		$list = $this->input->post('sel-reports');		
		if(is_array($list) && count($list)) {
			array_walk($list, 'intval');
			return $this->db->query("UPDATE profiles_reports SET IsNew = 1 WHERE ProfileReportID IN (".implode(',',$list).")");
		}
		else return false;
	}
	
	
	
	# Compte le nombre de domaines, pour pagination
	public function count_domains()
	{
		return $this->db->count_all('domains');
	}
	
	
	
	# Compte le nombre de domaines, pour pagination
	public function get_domains($start = 0, $length = 10)
	{
		return $this->db->query("SELECT DomainID, FrenchName, EnglishName FROM domains ORDER BY DomainID ASC LIMIT ?,?", array($start, $length))->result();
	}
	
	
	
	# Met à jour ou crée un domaine
	public function update_or_create_domain()
	{
		$data = array(
			'FrenchName' => $this->input->post('french-name'),
			'EnglishName' => $this->input->post('english-name')
		);
		
		if(intval($this->input->post('domain-id')) > 0) {
			$this->db->where('DomainID', intval($this->input->post('domain-id')));
			$this->db->update('domains', $data);
			return $this->db->affected_rows() > 0;
		}
		else
			return $this->db->insert('domains', $data);
	}
	
	
	
	# Liste les logs
	public function scan_logs_dir()
	{
		$list = array();
		$dir = opendir('application/logs');
		while(($file = readdir($dir)) !== FALSE)
			if($this->is_log_file($file))
				$list[substr($file, 4, 10)] = csdate(substr($file, 4, 10), true, true, true);
		if(!array_key_exists(date('Y-m-d'), $list)) $list[date('Y-m-d')] = lang('logs_today');
		krsort($list);
		return $list;
	}
	
	
	
	# Vérifie qu'un nom de fichier correspond à un nom de log
	public function is_log_file($file)
	{
		return substr($file, 0, 4) == 'log-' && substr($file, -4) == '.php' && preg_match("#log\-[1-2][0-9]{3}\-[0-1][0-9]\-[0-3][0-9]\.php#", $file);
	}
	
	
	
	# Lit un log selon un objet DateTime
	public function read_log_from_date(DateTime &$logdate)
	{
		$filename = 'application/logs/log-'.$logdate->format('Y-m-d').'.php';
		if(!file_exists($filename))
			return false;
		else {
			$return = array();
			$file = fopen($filename, 'r');
			while($line = fgets($file))
				$return[] = $this->format_log_line($line);
			return $return;
		}
	}
	
	
	
	# Formate une ligne du log pour l'affichage selon CI
	private function format_log_line($line)
	{
		$endtype = strpos($line, '-');
		$enddate = strpos($line, '-->');
		if($enddate === FALSE || $enddate === FALSE) return NULL;
		
		$data = array('Type' => trim(substr($line, 0, $endtype)));
		if(!in_array($data['Type'], array('INFO', 'DEBUG', 'ERROR'))) return NULL;
		
		try { $data['Time'] = new DateTime(trim(substr($line, $endtype + 1, $enddate - $endtype - 1))); }
		catch(Exception $e) { return NULL; }
		
		$data['Message'] = trim(substr($line, $enddate + 3));
		
		return (object)$data;
	}
	
	
	
	# Supprime un log
	public function flush_log(DateTime &$logdate)
	{
		$file = 'application/logs/log-'.$logdate->format('Y-m-d').'.php';
		if(file_exists($file)) unlink($file);
		else return false;
	}
	
	
	
	# Supprime un log
	public function flush_all_logs()
	{
		$path = 'application/logs';
		foreach(scandir($path) as $file)
			if($this->is_log_file($file))
				unlink($path.'/'.$file);
	}
	
	
	
}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */