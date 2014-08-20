<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_model extends CI_Model {
/* Profils, membres, etc. */
	
	
	
	# Compteur de messages privés
	public function count_new_messages()
	{
		return intval($this->db->query("SELECT COUNT(OtherUserID) AS NewMessages FROM (SELECT OtherUserID FROM messages WHERE UserID = ? AND IsNew = 1 GROUP BY OtherUserID) AS M", array(User::id()))->row()->NewMessages);
	}
	
	
	
	# Compteur de nouveaux signalements
	public function count_new_reports()
	{
		$Q = "SELECT (SELECT COUNT(AdReportID) FROM ads_reports WHERE IsNew = 1)";
		if(User::is_admin())
			$Q.= " + (SELECT COUNT(ProfileReportID) FROM profiles_reports WHERE IsNew = 1)";
		$Q.= " AS Count";
		return intval($this->db->query($Q)->row()->Count);
	}
	
	
	
}

/* End of file profiles.php */
/* Location: ./application/models/profiles.php */