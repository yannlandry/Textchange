<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Books extends CI_Controller {



	private $possible_domains = array();

	
	
	# Constructeur
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('books_model');
		$this->load->helper('books');
		
		$this->lang->load('general', User::lang());
		$this->lang->load('books', User::lang());
	}

	
	
	# Accueil des livres
	public function index()
	{
		$headerConfig = array(
			'title' => lang('books_title'),
			'path' => array(lang('books_path_title') => NULL)
		);
		
		$data = array(
			'searchLang' => $this->books_model->get_searchlang(),
			'domains' => $this->books_model->get_domains()
		);
		$data['showcase'] = $this->books_model->get_home_showcase($data['searchLang']);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('books/home', $data);
		$this->load->view('common/footer');
	}

	
	
	# Affichage des annonces pour un domaine
	public function domain($id = 0)
	{
		$D = $this->books_model->get_domain_data($id);
		if($D === FALSE) show_404();
		
		$headerConfig = array(
			'title' => lang('domain_title_prefix').' '.$D->DomainName,
			'path' => array(
				lang('books_path_title') => '/books',
				$D->DomainName => NULL
			),
			'scripts' => array(ASSETS_ROOT.'/javascript/dropdown.js')
		);
		
		$data = array(
			'searchLang' => $this->books_model->get_searchlang(),
			'searchSort' => $this->books_model->get_searchsort(),
			'searchOrder' => $this->books_model->get_searchorder(),
			'curpage' => get_page(),
			'D' => $D
		);
		$data['ads'] = $this->books_model->get_ads_from_domain($id, ($data['curpage'] - 1) * 24, 24, $data['searchLang'], $data['searchSort'], $data['searchOrder']);
		if(count($data['ads']) > 24) {
			$data['nextlink'] = true;
			array_pop($data['ads']);
		}
		if($data['curpage'] > 1)
			$data['prevlink'] = true;
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('books/domain', $data);
		$this->load->view('common/footer');
	}

	
	
	# Affichage des annonces pour un ISBN
	public function isbn($isbn = '')
	{
		$isbn = strip_isbn($isbn);
		
		# En-tête
		$headerConfig = array(
			'title' => lang('isbn_title_prefix').' '.$isbn,
			'path' => array(
				lang('books_path_title') => '/books',
				lang('isbn_path_prefix').' '.$isbn => NULL
			)
		);
		
		# Corps
		$data = array(
			'searchSort' => $this->books_model->get_searchsort(),
			'searchOrder' => $this->books_model->get_searchorder(),
			'curpage' => get_page(),
			'isbn' => $isbn
		);
		$data['ads'] = $this->books_model->get_ads_with_isbn($isbn, ($data['curpage'] - 1) * 24, 24, $data['searchSort'], $data['searchOrder']);
		if(count($data['ads']) > 24) {
			$data['nextlink'] = true;
			array_pop($data['ads']);
		}
		if($data['curpage'] > 1)
			$data['prevlink'] = true;
		
		# Surveillance
		if(User::is_connected()) {
			if($this->input->get('alerts') == 'watch' && $this->books_model->watch_isbn($isbn, isset($data['ads'][0]->Title) ? $data['ads'][0]->Title : ''))
				$data['watched'] = true;
			elseif($this->input->get('alerts') == 'unwatch' && $this->books_model->unwatch_isbn($isbn))
				$data['watched'] = false;
			else
				$data['watched'] = $this->books_model->is_isbn_watched($isbn);
		}
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('books/isbn', $data);
		$this->load->view('common/footer');
	}

	
	
	# Affichage les annonces d'un utilisateur
	public function user($username = NULL)
	{
		$U = $this->books_model->get_user_data($username);
		if($U === FALSE) show_404();
		
		$headerConfig = array(
			'title' => lang('user_title_prefix').' '.coalesce($U->RealName, $U->Username),
			'path' => array(
				lang('books_path_title') => '/books',
				lang('user_path_prefix').' '.coalesce($U->RealName, $U->Username) => NULL
			),
			'scripts' => array(ASSETS_ROOT.'/javascript/dropdown.js')
		);
		
		$data = array(
			'searchLang' => $this->books_model->get_searchlang(),
			'searchSort' => $this->books_model->get_searchsort(),
			'searchOrder' => $this->books_model->get_searchorder(),
			'curpage' => get_page(),
			'U' => $U
		);
		$data['ads'] = $this->books_model->get_all_user_ads($U->UserID, ($data['curpage'] - 1) * 24, 24, $data['searchLang'], $data['searchSort'], $data['searchOrder']);
		if(count($data['ads']) > 24) {
			$data['nextlink'] = true;
			array_pop($data['ads']);
		}
		if($data['curpage'] > 1)
			$data['prevlink'] = true;
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('books/user', $data);
		$this->load->view('common/footer');
	}

	
	
	# Page de recherche
	public function search()
	{
		$Q = trim($this->input->get('q'));
		if(valid_isbn($Q)) redirect(base_url('/books/isbn/'.$Q));
		
		$headerConfig = array(
			'title' => lang('search_title_prefix').' '.lang('quote_open').htmlspecialchars($Q).lang('quote_close'),
			'path' => array(
				lang('books_path_title') => '/books',
				lang('search_path_prefix').' '.lang('quote_open').htmlspecialchars($Q).lang('quote_close') => NULL
			),
			'scripts' => array(ASSETS_ROOT.'/javascript/dropdown.js')
		);
		
		$data = array(
			'searchLang' => $this->books_model->get_searchlang(),
			'searchSort' => $this->books_model->get_searchsort(),
			'searchOrder' => $this->books_model->get_searchorder(),
			'searchSubjects' => $this->books_model->get_searchsubjects(),
			'curpage' => get_page(),
			'Q' => $Q
		);
		$data['ads'] = !empty($Q) ? $this->books_model->search($Q, ($data['curpage'] - 1) * LARGE_ITEMS_PER_PAGE, LARGE_ITEMS_PER_PAGE, $data['searchSubjects'], $data['searchLang'], $data['searchSort'], $data['searchOrder']) : array();
		if(count($data['ads']) > LARGE_ITEMS_PER_PAGE + 1) {
			$data['nextlink'] = true;
			array_pop($data['ads']);
		}
		if($data['curpage'] > 1)
			$data['prevlink'] = true;
		
		$this->load->helper('form');
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('books/search', $data);
		$this->load->view('common/footer');
	}
	
	
	
	# Affchage d'une annonce
	public function ad($id = 0)
	{
		if(User::is_connected()) {
			if($this->input->get('alerts') == 'watch' && $this->books_model->watch_isbn_from_ad($id))
				redirect(base_url('/books/ad/'.$id));
			elseif($this->input->get('alerts') == 'unwatch' && $this->books_model->unwatch_isbn_from_ad($id))
				redirect(base_url('/books/ad/'.$id));
		}
		
		$A = $this->books_model->get_ad($id);
		if($A === FALSE) show_404();
		
		$headerConfig = array(
			'title' => $A->Title,
			'styles' => array(
				ASSETS_ROOT . '/javascript/fancybox/jquery.fancybox.css'
			),
			'scripts' => array(
				ASSETS_ROOT . '/javascript/fancybox/jquery.fancybox.pack.js?v=2.1.5',
				ASSETS_ROOT . '/javascript/fireFancyboxAd.js',
				ASSETS_ROOT . '/javascript/dropdown.js',
				ASSETS_ROOT . '/javascript/fb-share.js'
			),
			'path' => array(
				lang('books_path_title') => '/books',
				$A->DomainName => 'books/domain/'.$A->DomainID,
				$A->Title => NULL
			)
		);
		
		$data = array(
			'A' => $A,
			'ads' => $this->books_model->get_similar_isbn($A->ISBN, 0, 6, $A->AdID)
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('books/ad', $data);
		$this->load->view('common/footer');
	}
	
	
	
	# Ajout d'une annonces
	public function add()
	{
		if(!User::is_connected()) prompt_login('books/add');
		
		$this->possible_domains = $this->books_model->get_possible_domains();
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('title', NULL, 'trim|required');
		$this->form_validation->set_rules('isbn', NULL, 'trim|required|callback_valid_isbn');
		$this->form_validation->set_rules('authors', NULL, 'trim');
		$this->form_validation->set_rules('publisher', NULL, 'trim');
		$this->form_validation->set_rules('pubyear', NULL, 'trim|intval|greater_than[1950]|less_than['.date('Y').']');
		$this->form_validation->set_rules('domain', NULL, 'trim|required|callback_existing_domain');
		$this->form_validation->set_rules('lang', NULL, 'trim|required|callback_valid_lang');
		$this->form_validation->set_rules('information', NULL, 'trim');
		$this->form_validation->set_rules('price', NULL, 'trim|required|callback_valid_price');
		
		$this->form_validation->set_message('valid_isbn', lang('editad_invalidisbn_error'));
		$this->form_validation->set_message('existing_domain', lang('editad_invaliddomain_error'));
		$this->form_validation->set_message('valid_lang', lang('editad_invalidlang_error'));
		$this->form_validation->set_message('valid_price', lang('editad_invalidprice_error'));
		
		if($this->form_validation->run() && check_csrf_token()) {
			list($AdID, $Picture) = $this->books_model->create_ad();
			addFlash(lang('createad_success_flash'));
			
			$watchers = $this->books_model->get_watchers($this->input->post('isbn'));
			if(!empty($watchers)) {
				$this->load->library('TC_Mail');
				$this->tc_mail->send_alert(User::name(true), User::name(), $AdID, $this->input->post('title'), $Picture, $watchers);
			}
			
			redirect(base_url('/books/ad/'.$AdID));
		}
		
		else {
			$this->load->helper('form');
		
			$headerConfig = array(
				'title' => lang('createad_title'),
				'path' => array(
					lang('books_path_title') => '/books',
					lang('createad_path_title') => NULL
				),
				'scripts' => array(ASSETS_ROOT.'/javascript/autosize.js')
			);
			
			$data = array(
				'headerTitle' => lang('createad_header_title'),
				'domains' => $this->possible_domains,
				'prf' => array(
					'title' => NULL,
					'isbn' => NULL,
					'authors' => NULL,
					'publisher' => NULL,
					'pubyear' => NULL,
					'domain' => NULL,
					'lang' => NULL,
					'information' => NULL,
					'picture' => NULL,
					'price' => NULL
				),
				'cancelReturnLink' => base_url('/books')
			);
		
			$this->load->view('common/header', $headerConfig);
			$this->load->view('books/edit', $data);
			$this->load->view('common/footer');
		}
	}
	
		# Les fonctions de validation sont sous la méthode edit()
	
	
	
	# Édition d'une offre
	public function edit($id = 0)
	{
		$A = $this->books_model->get_ad_edit($id);
		if($A === FALSE || (!User::is_mod() && User::id() != $A->UserID)) show_404();
		
		$this->possible_domains = $this->books_model->get_possible_domains();
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('title', NULL, 'trim|required');
		$this->form_validation->set_rules('isbn', NULL, 'trim|required|callback_valid_isbn');
		$this->form_validation->set_rules('authors', NULL, 'trim');
		$this->form_validation->set_rules('publisher', NULL, 'trim');
		$this->form_validation->set_rules('pubyear', NULL, 'trim|intval|greater_than[1950]|less_than['.date('Y').']');
		$this->form_validation->set_rules('domain', NULL, 'trim|required|callback_existing_domain');
		$this->form_validation->set_rules('lang', NULL, 'trim|required|callback_valid_lang');
		$this->form_validation->set_rules('information', NULL, 'trim');
		$this->form_validation->set_rules('price', NULL, 'trim|required|callback_valid_price');
		
		$this->form_validation->set_message('valid_isbn', lang('editad_invalidisbn_error'));
		$this->form_validation->set_message('existing_domain', lang('editad_invaliddomain_error'));
		$this->form_validation->set_message('valid_lang', lang('editad_invalidlang_error'));
		$this->form_validation->set_message('valid_price', lang('editad_invalidprice_error'));
		
		if($this->form_validation->run() && check_csrf_token()) {
			$this->books_model->update_ad($A->AdID);
			addFlash(lang('editad_success_flash'));
			redirect(base_url('/books/ad/'.$A->AdID));
		}
		
		else {
			$this->load->helper('form');
		
			$headerConfig = array(
				'title' => lang('editad_title'),
				'path' => array(
					lang('books_path_title') => '/books',
					$A->DomainName => 'books/domain/'.$A->DomainID,
					$A->Title => 'books/ad/'.$A->AdID,
					lang('editad_path_title') => NULL
				),
				'scripts' => array(
					ASSETS_ROOT.'/javascript/autosize.js'
				)
			);
			
			$data = array(
				'headerTitle' => lang('editad_header_title'),
				'domains' => $this->possible_domains,
				'prf' => array(
					'title' => $A->Title,
					'isbn' => $A->ISBN,
					'authors' => $A->Authors,
					'publisher' => $A->Publisher,
					'pubyear' => $A->PubYear,
					'domain' => $A->DomainID,
					'lang' => $A->Language,
					'information' => $A->Information,
					'picture' => $A->Picture,
					'price' => $A->Price
				),
				'cancelReturnLink' => base_url('/books/ad/'.$A->AdID)
			);
		
			$this->load->view('common/header', $headerConfig);
			$this->load->view('books/edit', $data);
			$this->load->view('common/footer');
		}
	}
	
		public function valid_isbn($isbn)
		{
			return valid_isbn($isbn);
		}
	
		public function valid_lang($lang)
		{
			return in_array($lang, array('french', 'english', 'other'));
		}
	
		public function existing_domain($domain)
		{
			return array_key_exists($domain, $this->possible_domains);
		}
		
		public function valid_price($price)
		{
			return input_price($price);
		}
	
	
	
	# Suppression d'une annonce
	public function delete($AdID = 0)
	{
		$A = $this->books_model->get_ad_delete($AdID);
		if($A === FALSE || (!User::is_mod() && User::id() != $A->UserID)) show_404();
		
		if(isset($_POST['action-confirm']) && check_csrf_token()) {
			$this->books_model->delete_ad($AdID);
			addFlash(lang('deletead_success_flash'));
			redirect(base_url('/books'));
		}
		
		else {
			$headerConfig = array(
				'title' => lang('deletead_title'),
				'path' => array(
					lang('books_path_title') => '/books',
					$A->DomainName => 'books/domain/'.$A->DomainID,
					$A->Title => 'books/ad/'.$A->AdID,
					lang('deletead_path_title') => NULL
				)
			);
			
			$data = array(
				'confirmText' => lang('deletead_confirmation_text'),
				'confirmButtonText' => lang('deletead_confirm_button'),
				'cancelButtonText' => lang('deletead_cancel_button'),
				'cancelReturnLink' => base_url('/books/ad/'.$A->AdID)
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('common/confirmation', $data);
			$this->load->view('common/footer');
		}
	}
	
	
	
	# Envoyer un courriel au annonce
	public function email($AdID = NULL)
	{
		$A = $this->books_model->get_ad_email($AdID);
		if($A === FALSE || (!User::is_connected() && $A->UnregContact == '0')) show_404();
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('message', NULL, 'required|htmlspecialchars');
		
		if(!User::is_connected()) {
			$this->form_validation->set_rules('name', NULL, 'required');
			$this->form_validation->set_rules('email', NULL, 'required|valid_email');
			$this->form_validation->set_rules('captcha', NULL, 'trim|required|callback_captcha_matches');
			$this->form_validation->set_message('captcha_matches', lang('emailad_captcha_error'));
		}
		
		if($this->form_validation->run()) {
			if(User::is_connected()) {
				$replyTo = User::email();
				$replyName = User::name();
			}
			else {
				$replyTo = $this->input->post('email');
				$replyName = $this->input->post('name');
			}
			
			$this->load->library('TC_Mail');
			$this->tc_mail->send_email_seller($replyTo, $replyName, $A->AdID, $A->Title, $this->input->post('message'), $A->UserLanguage, $A->Email);
			addFlash(lang('emailad_success_flash'));
			redirect(base_url('/books/ad/'.$AdID));
		}
		
		else {
			#En-tête
			$headerConfig = array(
				'title' => lang('emailad_title'),
				'path' => array(
					lang('books_path_title') => '/books',
					$A->DomainName => 'books/domain/'.$A->DomainID,
					$A->Title => 'books/ad/'.$A->AdID,
					lang('emailad_path_title') => NULL
				),
				'scripts' => array(ASSETS_ROOT.'/javascript/autosize.js')
			);
			
			# Captcha
			$data = array('A' => $A);
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
			$this->load->view('books/email', $data);
			$this->load->view('common/footer');
		}
	}
	
	
	
	# Signaler une annonce
	public function report($AdID = 0)
	{
		$A = $this->books_model->get_ad_report($AdID);
		if($A === FALSE) show_404();
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('reason', NULL, 'trim|htmlspecialchars');
		
		if(!User::is_connected()) {
			$this->form_validation->set_rules('captcha', NULL, 'trim|required|callback_captcha_matches');
			$this->form_validation->set_message('captcha_matches', lang('reportad_captcha_error'));
		}
		
		if($this->form_validation->run()) {
			$this->books_model->report_ad($AdID);
			addFlash(lang('reportad_success_flash'));
			redirect(base_url('/books/ad/'.$AdID));
		}
		
		else {
			#En-tête
			$headerConfig = array(
				'title' => lang('reportad_title'),
				'path' => array(
					lang('books_path_title') => '/books',
					$A->DomainName => 'books/domain/'.$A->DomainID,
					$A->Title => 'books/ad/'.$A->AdID,
					lang('reportad_path_title') => NULL
				)
			);
			
			# Corps
			$data = array(
				'headerTitle' => lang('reportad_header_title'),
				'reportIntro' => lang('reportad_intro_text'),
				'reasonPrompt' => lang('reportad_reason_prompt'),
				'captchaPrompt' => lang('reportad_captcha_prompt'),
				'captchaHelper' => lang('reportad_captcha_helper'),
				'reportButton' => lang('reportad_submit_button'),
				'cancelButton' => lang('reportad_cancel_button'),
				'cancelUrl' => base_url('/books/ad/'.$A->AdID)
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
	
	
	
	# Alertes sur les nouvelles annonces
	public function alerts()
	{
		if(!User::is_connected()) prompt_login('books/alerts');
		
		if(isset($_POST['unwatch-button']) && is_array($this->input->post('unwatch')) && $this->books_model->unwatch_isbn($this->input->post('unwatch')))
			addFlash(lang('alerts_unwatched_flash'));
		
		if(isset($_POST['watch-button']) && $this->input->post('watch-isbn')) {
			if(valid_isbn($this->input->post('watch-isbn'))) {
				$this->books_model->watch_isbn(convert_isbn($this->input->post('watch-isbn')), htmlspecialchars($this->input->post('watch-title')));
				addFlash(lang('alerts_watched_flash'));
			}
			else
				addFlash(lang('alerts_invalidisbn_flash'), 'error');
		}
		
		$data = array(
			'curpage' => get_page(),
			'rescount' => $this->books_model->count_user_alerts()
		);
		
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/books/alerts'),
			'cur_page' => $data['curpage'],
			'total_rows' => $data['rescount'],
			'per_page' => 20
		));
		
		$data['pagination'] = $this->pagination->create_links();
		$data['alerts'] = $this->books_model->get_user_alerts(($data['curpage'] - 1) * 20, 20);
		
		$headerConfig = array(
			'title' => lang('alerts_title'),
			'path' => array(
				lang('books_path_title') => '/books',
				lang('alerts_path_title') => NULL
			)
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('books/alerts', $data);
		$this->load->view('common/footer');
	}
	
	
	
}

/* End of file books.php */
/* Location: ./application/controllers/books.php */