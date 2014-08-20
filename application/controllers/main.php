<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
/* Contrôleur principal :
 * Connexion, inscription, fonctions connexes */

	
	
	# Constructeur
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('general', User::lang());
		$this->lang->load('main', User::lang());
	}
	
	
	
	# Page d'accueil temporaire
	public function index()
	{
		$headerConfig = array(
			'title' => lang('home_title'),
			'path' => array(lang('home_path_title') => ''),
		);
		
		$this->load->model('books_model');
		$this->load->helper('books');
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('main/home', array('ads' => $this->books_model->get_recent_ads()));
		$this->load->view('common/footer');
	}
	
	
	
	# Formulaire de connexion
	public function login()
	{
		if(User::is_connected()) {
			addFlash(lang('alreadyconnected_flash'));
			redirect(BASE_URL);
		}
		
		$headerConfig = array(
			'title' => lang('login_title'),
			'path' => array(lang('login_path_title') => '')
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('main/login');
		$this->load->view('common/footer');
	}
	
	
	
	# Déconnecte, puis redirige
	public function logoff()
	{
		if(User::is_connected()) $this->sessions->logoff();
		redirect(BASE_URL);
	}
	
	
	
	# Formulaire d'inscription
	public function signup()
	{
		if(User::is_connected()) {
			addFlash(lang('alreadyconnected_flash'));
			redirect(BASE_URL);
		}

		#addFlash(bil('Les inscriptions sont bloquées pour le moment. Nous sommes désolés des inconvénients.', 'Registration is currently blocked. We are sorry for the inconvenience.', true), 'error');

		$this->load->library('form_validation');
		$this->lang->load('form_validation', User::lang());
		$this->form_validation->set_error_delimiters("", "");
		
		# Règles de validation
		$this->form_validation->set_rules('subsEmail', 'E-mail', 'trim|required|valid_email|callback_is_suffix_accepted|is_unique[users.Email]');
		$this->form_validation->set_rules('subsUsername', 'Username', 'trim|required|min_length[6]|max_length[16]|callback_is_valid_username|is_unique[users.Username]');
		$this->form_validation->set_rules('subsPass', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('subsPassConf', 'Password confirmation', 'required|matches[subsPass]');
		
		# Messages de validation
		$this->form_validation->set_message('is_suffix_accepted', lang('signup_emailnotaccepted_error'));
		$this->form_validation->set_message('is_valid_username', lang('signup_invalidusername_error'));
		
		$headerConfig = array(
			'title' => lang('signup_title'),
			'path' => array(lang('signup_path_title') => '')
		);
		
		if($this->form_validation->run()) {
			$this->load->model('accounts');
			$this->load->library('TC_Mail');
			
			$token = $this->accounts->create_account();
			$this->tc_mail->send_activation($token, $this->input->post('subsEmail'));
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('main/signup_after');
			$this->load->view('common/footer');
			
		}
		
		else {
			$this->load->view('common/header', $headerConfig);
			$this->load->view('main/signup');
			$this->load->view('common/footer');
		}
	}
	
		# Adresse acceptée
		public function is_suffix_accepted($email)
		{
			$this->load->model('accounts');
			return $this->accounts->verify_email_suffix($email);
		}
	
		# Nom d'utilisateur valide
		public function is_valid_username($username)
		{
			return preg_match("#[A-za-z]#", $username) && preg_match("#^[A-za-z0-9_\-\.]+$#", $username);
		}
	
	
	
	# Activation d'un compte
	public function activate()
	{
		$this->load->model('accounts');
		
		$A = $this->accounts->get_activation_details($this->input->get('token'));
		if($A !== FALSE) {
			if($this->accounts->activate_account($A->UserID)) {
				addFlash(lang('activation_success_flash'));
				redirect(BASE_URL.'/login');
			}
		}
		
		if(strlen($this->input->get('token')) > 0) addFlash(lang('activation_failure_flash'), 'error');
		redirect(BASE_URL.'/login');
	}
	
	
	
	# Renvoi du mail d'activation
	public function resend()
	{
		if(User::is_connected()) {
			addFlash(lang('alreadyconnected_flash'));
			redirect(BASE_URL);
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('accEmail', NULL, 'trim|required|valid_email');
		$this->form_validation->set_rules('chgEmail', NULL, 'trim|valid_email|is_unique[users.Email]|callback_is_suffix_accepted');
		$this->form_validation->set_message('is_suffix_accepted', lang('signup_emailnotaccepted_error'));
		
		$headerConfig = array(
			'title' => lang('resend_title'),
			'path' => array(lang('resend_path_title') => '')
		);
		
		if($this->form_validation->run()) {
			$this->load->model('accounts');
			
			list($actid, $token) = $this->accounts->get_activation_token($this->input->post('accEmail'));
			if($token) {
				if($this->input->post('chgEmail')) {
					$this->accounts->change_account_email($this->input->post('accEmail'), $this->input->post('chgEmail'));
					$token = $this->accounts->reset_activation_token($actid, $token);
					$email = $this->input->post('chgEmail');
				}
				else
					$email = $this->input->post('accEmail');
				
				$this->load->library('TC_Mail');
				$this->tc_mail->send_activation($token, $email);
				$this->load->view('common/header', $headerConfig);
				$this->load->view('main/resend_after');
				$this->load->view('common/footer');
			}
			else {
				addFlash(lang('resend_accountnotfound_flash'), 'error');
				redirect(base_url('/activate/resend'));
			}
		}
		
		else {
			$this->load->view('common/header', $headerConfig);
			$this->load->view('main/resend');
			$this->load->view('common/footer');
		}
	}
	
		# is_suffix_accepted est après la méthode d'inscription
	
	
	
	public function recover()
	{
		if(User::is_connected()) {
			addFlash(lang('alreadyconnected_flash'));
			redirect(BASE_URL);
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->form_validation->set_rules('accEmail', 'Account E-mail', 'trim|required|valid_email');
		
		if($this->form_validation->run()) {
			$this->load->model('accounts');
			$this->load->library('TC_Mail');
			
			$password = $this->accounts->reset_password($this->input->post('accEmail'));
			if($password !== FALSE) $this->tc_mail->send_recover($password, $this->input->post('accEmail'));
			addFlash(lang('recover_emailsent_flash'));
			
			redirect(base_url('/login'));
		}
		
		else {
			$headerConfig = array(
				'title' => lang('recover_title'),
				'path' => array(lang('recover_path_title') => '')
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('main/reset');
			$this->load->view('common/footer');
		}
	}
	
	
	
	# Formulaire de contact
	public function contact()
	{
		$this->load->library('form_validation');
		$this->lang->load('form_validation', User::lang());
		$this->form_validation->set_error_delimiters("", "");
		
		$this->form_validation->set_rules('name', 'Name', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|htmlspecialchars|required|min_length[40]');
		
		$this->form_validation->set_message('min_length', lang('contact_messagetooshort_error'));
		
		if(!User::is_connected()) {
			$this->form_validation->set_rules('captcha', 'Captcha', 'required|callback_matches_captcha');
			$this->form_validation->set_message('matches_captcha', lang('contact_captcha_error'));
		}
		
		if($this->form_validation->run()) {
			$this->load->library('TC_Mail');
			if(!User::is_connected()) unset($_SESSION['captcha']);
			$this->tc_mail->send_contact($this->input->post('name'), $this->input->post('email'), $this->input->post('subject'), $this->input->post('message'));
			addFlash(lang('contact_success'));
			redirect(base_url());
		}
		
		else {
			if(!User::is_connected()) {
				$this->load->helper('captcha');
				$captcha = create_captcha(array(
					'img_path' => './static/captchas/',
					'img_url' => ASSETS_ROOT.'/captchas/',
					'font_path' => './static/fonts/junkos.ttf'
				));
				$_SESSION['captcha'] = $captcha['word'];
				$data = array('captcha' => $captcha['image']);
			}
			else $data = array();
			
			$headerConfig = array(
				'title' => lang('contact_title'),
				'path' => array(lang('contact_path_title') => ''),
				'scripts' => array(ASSETS_ROOT.'/javascript/autosize.js')
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('main/contact', $data);
			$this->load->view('common/footer');
		}
	}
	
		# Captcha correspond
		public function matches_captcha($input)
		{
			return isset($_SESSION['captcha']) && strtoupper($input) == strtoupper($_SESSION['captcha']);
		}
	
	
	
	# Page statique additionnelle
	public function info($doc = NULL)
	{
		if(file_exists('static/content/'.$doc.'.php')) {
			$B = $this->load->view('main/info', array('doc' => $doc), true);
			$hStart = strpos($B, '<h1>');
			$hEnd = strpos($B, '</h1>');
			
			if($hStart === FALSE || $hEnd === FALSE)
				$title = 'Information';
			else
				$title = substr($B, $hStart + 4, $hEnd - $hStart - 4);
		
			$headerConfig = array(
				'title' => $title,
				'path' => array($title => '')
			);
			
			$H = $this->load->view('common/header', $headerConfig, true);
			$F = $this->load->view('common/footer', NULL, true);
			
			echo $H . $B . $F;
		}
		else
			show_404();
	}
	
	
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */