<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages_model extends CI_Model {
/* Profils, membres, etc. */
	
	
	
	# Lister les conversations
	public function get_conversations($start = 0, $length = 10)
	{
		$rescount = intval($this->db->query("SELECT COUNT(DISTINCT OtherUserID) AS ConvCount FROM messages WHERE UserID = ?", array(User::id()))->row()->ConvCount);
		
		$results = $this->db->query("
			SELECT
				C.LastMessageID,
				C.OtherUserID,
				C.UnreadMessages,
				M.Message,
				M.DateSent,
				M.Way,
				U.Username,
				U.RealName,
				U.Picture
			FROM
				(SELECT
					C.LastMessageID,
					C.OtherUserID,
					C.UnreadMessages
				FROM
					(SELECT
						MAX(MessageID) AS LastMessageID,
						OtherUserID,
						SUM(IsNew) AS UnreadMessages
					FROM messages
					WHERE UserID = ?
					GROUP BY OtherUserID)
				AS C
				ORDER BY C.LastMessageID DESC
				LIMIT ?,?)
			AS C
			INNER JOIN messages AS M
				ON M.MessageID = C.LastMessageID
			INNER JOIN users AS U
				ON U.UserID = C.OtherUserID
			ORDER BY C.LastMessageID DESC
		", array(User::id(), $start, $length))->result();
		
		$results[] = $rescount;
		return $results;
	}
	
	
	
	# Information sur l'interlocuteur
	public function get_contact_info($username = NULL)
	{
		if(empty($username)) return false;
		
		$Q = $this->db->query("SELECT UserID, Username, RealName, Email, Picture, Rights, Language, NotifyPM FROM users WHERE username = ? LIMIT 1", array($username));
		if($Q->num_rows() == 0) return false; 
		
		return $Q->row();
	}
	
	
	
	# Récupération des messages d'une conversation
	public function get_messages($OtherUserID, $start, $length)
	{
		$rescount = $this->db->query("SELECT COUNT(MessageID) AS MessageCount FROM messages WHERE UserID = ? AND OtherUserID = ?", array(User::id(), $OtherUserID))->row()->MessageCount;
		
		$results = $this->db->query("SELECT MessageID, Way, DateSent, Message, IsNew FROM messages WHERE UserID = ? AND OtherUserID = ? ORDER BY MessageID ASC LIMIT ?,?", array(User::id(), $OtherUserID, $start, $length))->result();
		
		$results[] = $rescount;
		return $results;
	}
	
	
	
	# Suppression de messages sélectionnés
	public function delete_selected_messages($OtherUserID)
	{
		if(is_array($this->input->post('delete_msg')) && count($this->input->post('delete_msg')) > 0 && check_csrf_token()) {
			$ids = $this->input->post('delete_msg');
			array_walk($ids, 'intval');
			$this->db->query("DELETE FROM messages WHERE UserID = ? AND OtherUserID = ? AND MessageID IN (".implode(',', $ids).")",
				array(User::id(), $OtherUserID));
			return $this->db->affected_rows() > 0;
		}
		return false;
	}
	
	
	
	# Messages non-lus
	public function mark_as_read($unread)
	{
		if(is_array($unread) && count($unread) > 0)
			$this->db->query("UPDATE messages SET IsNew = 0 WHERE MessageID IN (".implode(',',$unread).")");
	}
	
	
	
	# Marquer tous les messages comme lus
	public function mark_all_as_read($OtherUserID = NULL)
	{
		$append = !empty($OtherUserID) ? "AND OtherUserID = ".intval($OtherUserID) : NULL;
		$this->db->query("UPDATE messages SET IsNew = 0 WHERE UserID = ? ".$append, array(User::id()));
		return $this->db->affected_rows() > 0;
	}
	
	
	
	# Envoi de message à un utilisateur défini
	public function try_send_answer($OtherUserID)
	{
		if(strlen(trim($this->input->post('message'))) > 0 && check_csrf_token()) {
			$this->create_message($OtherUserID, User::id(), $this->input->post('message'));
			return true;
		}
		else return false;
	}
	
	
	
	# Informations sur le dernier message posté
	public function last_answer_info($OtherUserID)
	{
		$id = $this->db->insert_id();
		return array(
			ceil(($this->db->query("SELECT COUNT(MessageID) AS PrevMessages FROM messages WHERE UserID = ? AND OtherUserID = ? AND MessageID < ?", array(User::id(), $OtherUserID, $id))->row()->PrevMessages + 1) / LARGE_ITEMS_PER_PAGE),
			$id
		);
	}
	
	
	
	# Envoi d'un message
	private function create_message($toUserID, $fromUserID, $message, $visibleToSender = true)
	{
		$data = array('Message' => nl2br(htmlspecialchars($message)));
		
		$data['UserID'] = $toUserID;
		$data['OtherUserID'] = $fromUserID;
		$data['Way'] = 'in';
		$data['IsNew'] = 1;
		
		$this->db->insert('messages', $data);
		
		if($visibleToSender) {
			$data['UserID'] = $fromUserID;
			$data['OtherUserID'] = $toUserID;
			$data['Way'] = 'out';
			$data['IsNew'] = 0;
			
			$this->db->insert('messages', $data);
		}
	}
	
	
	
}

/* End of file messages_model.php */
/* Location: ./application/models/messages_model.php */