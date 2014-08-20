<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller {
/* Contrôleur utilisateurs :
 * Profil, édition, recherche d'utilisateurs */

	
	
	# Constructeur
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('general', User::lang());
		$this->lang->load('messages', User::lang());
		
		$this->load->model('messages_model');
		
		$this->potential_schools = array();
	}

	
	
	# Liste des conversations
	public function index()
	{
		if(!User::is_connected()) prompt_login('messages');
		
		# Marquage de tous les messages comme lus
		if($this->input->get('mark') == 'read' && check_csrf_token($this->input->get('csrf_token')))
			$this->messages_model->mark_all_as_read();
		
		$curpage = get_page();
		$data = array('results' => $this->messages_model->get_conversations(($curpage - 1) * SMALL_ITEMS_PER_PAGE, SMALL_ITEMS_PER_PAGE));
		$data['rescount'] = intval(array_pop($data['results']));
		
		# Pagination
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/messages'),
			'cur_page' => $curpage,
			'total_rows' => $data['rescount'],
			'per_page' => SMALL_ITEMS_PER_PAGE
		));
		
		$data['pagination'] = $this->pagination->create_links();
		
		# En-tête
		$headerConfig = array(
			'title' => lang('messages_title'),
			'path' => array(
				lang('messages_path_title') => NULL
		));
		
		$this->load->helper('text');
		$this->load->helper('form');
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('messages/list', $data);
		$this->load->view('common/footer');
	}
	
	
	
	# Vue d'une conversation
	public function conversation($username = NULL)
	{
		if(!User::is_connected()) prompt_login('messages/'.$username);
		
		if(empty($username)) show_404();
		elseif(User::name(true) == trim($username)) {
			addFlash(lang('conversation_toself_flash'), 'error');
			redirect(base_url('/messages'));
		}
		
		$C = $this->messages_model->get_contact_info($username);
		if($C === FALSE) show_404();
		
		# Envoi d'une réponse
		if($this->messages_model->try_send_answer($C->UserID)) {
			addFlash(lang('conversation_answersent_flash'));
			list($tpage, $tid) = $this->messages_model->last_answer_info($C->UserID);
			
			if($C->NotifyPM == '1') { # Notification par courriel
				$this->load->library('TC_Mail');
				$debug = $this->tc_mail->send_newpm(User::name(true), User::name(), $this->input->post('message'), $C->Language, $C->Email);
			}
			
			redirect(base_url('/messages/'.$C->Username.'?page='.$tpage.'#message-'.$tid));
		}
		
		# Suppression de messages
		if($this->messages_model->delete_selected_messages($C->UserID))
			addFlash(lang('conversation_msgdeleted_flash'));
		
		# Marquage de tous les messages comme lus
		if($this->input->get('mark') == 'read' && check_csrf_token($this->input->get('csrf_token')))
			$this->messages_model->mark_all_as_read($C->UserID);
		
		# Pagination, compte, résultats
		$curpage = get_page();
		$data = array('results' => $this->messages_model->get_messages($C->UserID, ($curpage - 1) * LARGE_ITEMS_PER_PAGE, LARGE_ITEMS_PER_PAGE));
		$data['rescount'] = intval(array_pop($data['results']));
		
		# Marquage des messages non-lus
		$unread = array();
		foreach($data['results'] as $M)
			if((bool)intval($M->IsNew))
				$unread[] = intval($M->MessageID);
		$this->messages_model->mark_as_read($unread);
		
		# Pagination
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/messages/'.$C->Username),
			'cur_page' => $curpage,
			'total_rows' => $data['rescount'],
			'per_page' => LARGE_ITEMS_PER_PAGE
		));
		
		$data['pagination'] = $this->pagination->create_links();
		
		# En-tête
		$headerConfig = array(
			'title' => lang('conversation_path_title').' '.coalesce($C->RealName, $C->Username),
			'path' => array(
				lang('messages_path_title') => '/messages',
				lang('conversation_path_title').' '.coalesce($C->RealName, $C->Username) => NULL
			),
			'scripts' => array(ASSETS_ROOT.'/javascript/autosize.js')
		);
		
		$data['C'] =& $C;
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('messages/conversation', $data);
		$this->load->view('common/footer');
	}
	
	
	
}

/* End of file messages.php */
/* Location: ./application/controllers/messages.php */