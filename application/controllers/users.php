<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {
/* Contrôleur utilisateurs :
 * Profil, édition, recherche d'utilisateurs */



	private $potential_schools; # Variable utile pour certaines fonctions de validation

	
	
	# Constructeur
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('general', User::lang());
		$this->lang->load('users', User::lang());
		
		$this->load->model('profiles');
		
		$this->load->helper('profile');
		
		$this->potential_schools = array();
	}

	
	
	# Liste des membres
	public function index()
	{
		$Q = $this->input->get('q');
		$data = array('Q' => $Q);
		$curpage = get_page();
		$isSearch = !empty($Q);
		
		# Résultats
		if(!empty($Q))
			$data['results'] = $this->profiles->search_profiles($Q, ($curpage - 1) * SMALL_ITEMS_PER_PAGE, SMALL_ITEMS_PER_PAGE);
		else
			$data['results'] = $this->profiles->get_all_profiles(($curpage - 1) * SMALL_ITEMS_PER_PAGE, SMALL_ITEMS_PER_PAGE);
		
		# Nombre de résultats
		$rescount = intval(array_pop($data['results']));
		
		if($rescount == 0) $data['rescount'] = lang('listusers_rescount_noresult');
		if($rescount == 1) $data['rescount'] = '1 '.lang('listusers_rescount_oneresult');
		else $data['rescount'] = $rescount.' '.lang('listusers_rescount_suffix');
		
		# Présentation des résultats
		$data['resintro'] = !$isSearch ? lang('listusers_listall_allusers') : lang('listusers_search_prefix').htmlspecialchars($Q).lang('listusers_search_suffix');
		
		# Pagination
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/users'),
			'cur_page' => $curpage,
			'total_rows' => $rescount,
			'per_page' => SMALL_ITEMS_PER_PAGE
		));
		
		$data['pagination'] = $this->pagination->create_links();
		$data['tomsg'] = $this->input->get('after') == 'message';
		
		# En-tête
		$headerConfig = array('title' => $isSearch ? $data['resintro'] : lang('listusers_title'));
		if(!$isSearch) {
			$headerConfig['path'] = array(lang('users_path_title') => NULL);
		}
		else {
			$headerConfig['path'] = array(
				lang('users_path_title') => base_url('/users'),
				$data['resintro'] => NULL
			);
		}
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('users/list', $data);
		$this->load->view('common/footer');
	}
	
	
	
	# Vue d'un profil
	public function view($username = NULL)
	{
		$P = $this->profiles->get_profile_data($username);
		if($P === FALSE) show_404();
		
		$this->load->model('books_model');
		$this->load->helper('books');
		$this->load->helper('profile');
		
		$headerConfig = array('title' => lang('profile_prefix_title').' '.coalesce($P->RealName, $P->Username));
		$headerConfig['path'] = array(lang('users_path_title') => '/users', coalesce($P->RealName, $P->Username) => '');
		
		$data = array(
			'P' => $P,
			'ads' => $this->books_model->get_user_ads($P->UserID, 0, 6)
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('users/view', $data);
		$this->load->view('common/footer');
	}
	
	
	
	# Édition d'un profil
	public function edit($username = NULL)
	{
		if(User::name(TRUE) != $username && !User::is_admin()) show_404();
		
		$P = $this->profiles->get_profile_edit_data($username);
		if($P === FALSE) show_404();
		
		$this->potential_schools = $this->profiles->get_potential_schools($P->Email);
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('realname', NULL, 'trim|min_length[2]|max_length[32]');
		$this->form_validation->set_rules('school', NULL, 'callback_is_potential_school');
		$this->form_validation->set_rules('information', NULL, 'trim|htmlspecialchars');
		
		$this->form_validation->set_rules('phone', NULL, 'is_numeric|exact_length[10]');
		$this->form_validation->set_rules('phone_area');
		$this->form_validation->set_rules('phone_prefix');
		$this->form_validation->set_rules('phone_suffix');
		
		$this->form_validation->set_rules('displayemail');
		$this->form_validation->set_rules('newpm');
		$this->form_validation->set_rules('unreg');
		$this->form_validation->set_rules('language');
		
		$this->form_validation->set_message('is_potential_school', lang('editprofile_invalidschool_error'));
		$this->form_validation->set_message('is_numeric', lang('editprofile_invalidphone_error'));
		
		if(isset($_POST['phone_area'], $_POST['phone_prefix'], $_POST['phone_suffix']))
			$_POST['phone'] = $_POST['phone_area'].$_POST['phone_prefix'].$_POST['phone_suffix'];
		
		if($this->form_validation->run() && check_csrf_token()) {
			$this->profiles->update_profile($P->UserID);
			addFlash(lang('editprofile_success_flash'));
			redirect(base_url('/users/'.$username));
		}
		
		else {
			$this->load->helper('form');
			
			$headerConfig = array(
				'title' => lang('editprofile_title'),
				'scripts' => array(ASSETS_ROOT.'/javascript/autosize.js'));
			$headerConfig['path'] = array(
				lang('users_path_title') => '/users',
				coalesce($P->RealName, $P->Username) => '/users/'.$P->Username,
				lang('editprofile_path_title') => ''
			); 
			
			$data = array(
				'P' => $P,
				'Schools' => $this->potential_schools
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('users/edit', $data);
			$this->load->view('common/footer');
		}
	}
	
		public function is_potential_school($SchoolID)
		{
			return array_key_exists($SchoolID, $this->potential_schools) || $SchoolID == 0;
		}
	
	
	
	# Changement de mot de passe
	public function changepass($username = NULL)
	{
		if(User::name(TRUE) != $username && !User::is_admin()) show_404();
		
		$P = $this->profiles->get_profile_changepass_data($username);
		if($P === FALSE) show_404();
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('newpass', NULL, 'required|min_length[6]');
		$this->form_validation->set_rules('confpass', NULL, 'required|matches[newpass]');
		
		if(User::id($P->UserID)) {
			$this->form_validation->set_rules('oldpass', NULL, 'required|callback_verify_old_password');
			$this->form_validation->set_message('verify_old_password', lang('changepass_wrongpassword_error'));
		}
		
		if($this->form_validation->run() && check_csrf_token()) {
			$this->profiles->change_password($P->UserID);
			addFlash(lang('changepass_success_flash'));
			redirect(base_url('/users/'.$username.'/edit'));
		}
		
		else {
			$this->load->helper('form');
			
			$headerConfig = array('title' => lang('changepass_title'));
			$headerConfig['path'] = array(
				lang('users_path_title') => '/users',
				coalesce($P->RealName, $P->Username) => '/users/'.$P->Username,
				lang('editprofile_path_title') => '/users/'.$P->Username.'/edit',
				lang('changepass_path_title') => ''
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('users/changepass', array('P' => $P));
			$this->load->view('common/footer');
		}
	}
	
	
	
	# Changement d'adresse courriel
	public function changemail($username = NULL)
	{
		# Validation ?
		if($this->input->get('token')) $this->validate_changemail_token($username);
		
		if(User::name(TRUE) != $username && !User::is_admin()) show_404();
		
		$P = $this->profiles->get_profile_changemail_data($username);
		if($P === FALSE) show_404();
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('newmail', NULL, 'trim|required|valid_email|callback_is_suffix_accepted|is_unique[users.Email]');
		$this->form_validation->set_message('is_suffix_accepted', lang('changemail_emailnotaccepted_error'));
		
		if(User::id($P->UserID)) {
			$this->form_validation->set_rules('password', NULL, 'required|callback_verify_old_password');
			$this->form_validation->set_message('verify_old_password', lang('changemail_wrongpassword_error'));
		}
		
		if($this->form_validation->run() && check_csrf_token()) {
			if(User::id($P->UserID)) {
				$this->load->library('TC_Mail');
				$token = $this->profiles->create_newmail_token($P->UserID);
				$this->tc_mail->send_changemail($P->Username, $token, $this->input->post('newmail'));
				addFlash(lang('changemail_emailsent_flash'));
			}
			else {
				$this->profiles->change_email($P->UserID, $this->input->post('newmail'),
					substr($P->Email, strpos('@', $P->Email)) != substr($this->input->post('newmail'), strpos('@', $this->input->post('newmail'))));
				addFlash(lang('changemail_success_flash'));
			}
			redirect(base_url('/users/'.$username.'/edit'));
		}
		
		else {
			$this->load->helper('form');
			
			$headerConfig = array('title' => lang('changemail_title'));
			$headerConfig['path'] = array(
				lang('users_path_title') => '/users',
				coalesce($P->RealName, $P->Username) => '/users/'.$P->Username,
				lang('editprofile_path_title') => '/users/'.$P->Username.'/edit',
				lang('changemail_path_title') => ''
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('users/changemail', array('P' => $P));
			$this->load->view('common/footer');
		}
	}
	
		# Mot de passe actuel correct
		public function verify_old_password($password)
		{
			$U =& User::session();
			return $U->PasswordSalted == hash('sha512', $U->PasswordPreSalt.$password.$U->PasswordPostSalt);
		}
	
		# Adresse acceptée
		public function is_suffix_accepted($email)
		{
			$this->load->model('accounts');
			return $this->accounts->verify_email_suffix($email);
		}
	
	
	
	# Validation du changement d'adresse courriel
	private function validate_changemail_token($username)
	{
		if(!User::is_connected()) { # Pas connecté, le faire en premier
			addFlash(lang('changemail_contoval_flash'), 'error');
			redirect(base_url('/login?goto=users/'.$username.'/edit/email?token='.$this->input->get('token')));
		}
		elseif(User::name(TRUE) != $username) show_404(); # Pas le bon utilisateur, 404
		
		$T = $this->profiles->get_changemail_token_data($this->input->get('token')); # Informations du token
		if($T === FALSE) {
			addFlash(lang('changemail_invalidtoken_flash'), 'error');
			redirect(base_url('/users/'.$username.'/edit'));
		}
		
		$this->profiles->change_email(User::id(), $T->NewEmail,
			substr(User::session()->Email, strpos('@', User::session()->Email)) != substr($T->NewEmail, strpos('@', $T->NewEmail)));
		
		$this->profiles->destroy_changemail_token($T->Token);
		
		addFlash(lang('changemail_confirmed_flash'));
		redirect(base_url('/users/'.$username));
	}
	
	
	
	# Changement d'avatar
	public function changepic($username = NULL)
	{
		if(User::name(TRUE) != $username && !User::is_admin()) show_404();
		
		$P = $this->profiles->get_profile_changepic_data($username);
		if($P === FALSE) show_404();
		
		# Nous utiliserons un système de validation personnalisé pour cette page
		if(isset($_POST['pic-action']) && check_csrf_token()) {
			if($_POST['pic-action'] == 'pic-delete') {
				$this->profiles->delete_pic($P->UserID);
				addFlash(lang('changepic_picdeleted_flash'));
				redirect(base_url('/users/'.$username.'/edit'));
			}
			
			elseif($_POST['pic-action'] == 'pic-replace') {
				if(isset($_FILES['new-pic']) && $_FILES['new-pic']['error'] == 0 && $_FILES['new-pic']['size'] < 307200 && is_image($_FILES['new-pic']['tmp_name'])) {
					$this->profiles->upload_pic($P->UserID);
					addFlash(lang('changepic_uploaddone_flash'));
					redirect(base_url('/users/'.$username.'/edit'));
				}
				else
					addFlash(lang('changepic_uploaderr_flash'), 'error');
			}
			
			else redirect(base_url('/users/'.$username.'/edit'));
		}
		
		# Affichage
		$headerConfig = array('title' => lang('changepic_title'));
		$headerConfig['path'] = array(
			lang('users_path_title') => '/users',
			coalesce($P->RealName, $P->Username) => '/users/'.$P->Username,
			lang('editprofile_path_title') => '/users/'.$P->Username.'/edit',
			lang('changepic_path_title') => ''
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('users/changepic', array('P' => $P));
		$this->load->view('common/footer');
	}
	
	
	
	# Signaler un profil
	public function report($username = NULL)
	{
		$P = $this->profiles->get_profile_report_data($username);
		if($P === FALSE) show_404();
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('reason', NULL, 'trim|xss_clean');
		
		if(!User::is_connected()) {
			$this->form_validation->set_rules('captcha', NULL, 'trim|required|callback_captcha_matches');
			$this->form_validation->set_message('captcha_matches', lang('reportprofile_captcha_error'));
		}
		
		if($this->form_validation->run()) {
			$this->profiles->report_profile($P->UserID);
			addFlash(lang('reportprofile_success_flash'));
			redirect(base_url('/users/'.$username));
		}
		
		else {
			# En-tête
			$headerConfig = array(
				'title' => lang('reportprofile_title'),
				'path' => array(
					lang('users_path_title') => '/users',
					coalesce($P->RealName, $P->Username) => '/users/'.$P->Username,
					lang('reportprofile_path_title') => NULL
				)
			);
			
			# Corps
			$data = array(
				'headerTitle' => lang('reportprofile_header_title'),
				'reportIntro' => lang('reportprofile_intro_text'),
				'reasonPrompt' => lang('reportprofile_reason_prompt'),
				'captchaPrompt' => lang('reportprofile_captcha_prompt'),
				'captchaHelper' => lang('reportprofile_captcha_helper'),
				'reportButton' => lang('reportprofile_submit_button'),
				'cancelButton' => lang('reportprofile_cancel_button'),
				'cancelUrl' => base_url('/users/'.$P->Username)
			);
			
			# Captcha
			if(!User::is_connected()) {
				$this->load->helper('captcha');
				$captcha = create_captcha(array(
					'img_path' => './static/captchas/',
					'img_url' => ASSETS_ROOT.'/captchas/',
					'font_path' => './static/fonts/junkos.ttf'
				));
				$_SESSION['captcha'] = $captcha['word'];
				$data['captcha'] = $captcha['image'];
			}
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('common/report', $data);
			$this->load->view('common/footer');
		}
	}
	
		public function captcha_matches($captcha)
		{
			return isset($_SESSION['captcha']) && strtoupper($captcha) == strtoupper($_SESSION['captcha']);
		}
	
	
	
	# Suppression de son compte
	public function delete()
	{
	}
	
	
	
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */