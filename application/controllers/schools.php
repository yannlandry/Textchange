<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schools extends CI_Controller {

	# Provinces
	private $provinces = array('NB', 'NS', 'PE', 'NL', 'QC', 'ON', 'MB', 'SK', 'AB', 'BC', 'YK', 'NT', 'NV');

	
	
	# Constructeur
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('general', User::lang());
		$this->lang->load('schools', User::lang());
		
		$this->load->model('schools_model');
	}

	
	
	# Liste des établissements
	public function index()
	{
		$data = array();
		$curpage = get_page();
		
		# Résultats et compte
		$data['results'] = $this->schools_model->get_all_schools(($curpage - 1) * SMALL_ITEMS_PER_PAGE, SMALL_ITEMS_PER_PAGE);
		$data['rescount'] = intval(array_pop($data['results']));
		
		# Pagination
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/schools'),
			'cur_page' => $curpage,
			'total_rows' => $data['rescount'],
			'per_page' => SMALL_ITEMS_PER_PAGE
		));
		
		$data['pagination'] = $this->pagination->create_links();
		
		# En-tête
		$headerConfig = array(
			'title' => lang('listschools_title'),
			'path' => array(lang('schools_path_title') => NULL)
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('schools/list', $data);
		$this->load->view('common/footer');
	}
	
	
	
	# Ajout d'un établissement
	public function add()
	{
		if(!User::is_admin()) show_404();
		
		if(count($_POST) > 0)
			$_POST['picture'] = ( !empty($S->Picture) && file_exists('uploads/schools/'.$S->Picture) )
								|| ( isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK && is_image($_FILES['picture']['tmp_name']) );
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('frenchname', NULL, 'trim|required');
		$this->form_validation->set_rules('englishname', NULL, 'trim|required');
		$this->form_validation->set_rules('town', NULL, 'trim|required');
		$this->form_validation->set_rules('province', NULL, 'required|callback_valid_province');
		$this->form_validation->set_rules('emailsuffix', NULL, 'trim|required|callback_valid_suffix');
		$this->form_validation->set_rules('picture', NULL, 'required');
		
		$this->form_validation->set_message('valid_province', lang('editschool_invalidprovince_error'));
		$this->form_validation->set_message('valid_suffix', lang('editschool_invalidsuffix_error'));
		
		if($this->form_validation->run() && check_csrf_token()) {
			$this->schools_model->register_school();
			addFlash(lang('addschool_success_flash'));
			redirect(base_url('/schools'));
		}
		
		else {
			# En-tête
			$headerConfig = array(
				'title' => lang('addschool_title'),
				'path' => array(lang('schools_path_title') => '/schools', lang('addschool_path_title') => NULL)
			);
			
			# Corps
			$data = array(
				'pageTitle' => lang('addschool_header_title'),
				'prf' => array(
					'frenchname' => NULL,
					'englishname' => NULL,
					'town' => NULL,
					'province' => NULL,
					'emailsuffix' => NULL,
					'picture' => NULL
				),
				'provinces' => $this->provinces
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('schools/edit', $data);
			$this->load->view('common/footer');
		}
	}
	
		# Les fonctions de validation sont sous la méthode "edit"
	
	
	
	# Édition d'un établissements
	public function edit($id = 0)
	{
		if(!User::is_admin() || intval($id) < 1) show_404();
		
		$S = $this->schools_model->get_school_info($id);
		if($S === FALSE) show_404();
		
		if(count($_POST) > 0)
			$_POST['picture'] = ( !empty($S->Picture) && file_exists('uploads/schools/'.$S->Picture) )
								|| ( isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK && is_image($_FILES['picture']['tmp_name']) );
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters("", "");
		$this->lang->load('form_validation', User::lang());
		
		$this->form_validation->set_rules('frenchname', NULL, 'trim|required');
		$this->form_validation->set_rules('englishname', NULL, 'trim|required');
		$this->form_validation->set_rules('town', NULL, 'trim|required');
		$this->form_validation->set_rules('province', NULL, 'required|callback_valid_province');
		$this->form_validation->set_rules('emailsuffix', NULL, 'trim|required|callback_valid_suffix');
		$this->form_validation->set_rules('picture', NULL, 'required');
		
		$this->form_validation->set_message('valid_province', lang('editschool_invalidprovince_error'));
		$this->form_validation->set_message('valid_suffix', lang('editschool_invalidsuffix_error'));
		
		if($this->form_validation->run() && check_csrf_token()) {
			$this->schools_model->update_school_info($id);
			addFlash(lang('editschool_success_flash'));
			redirect(base_url('/schools'));
		}
		
		else {
			# En-tête
			$headerConfig = array(
				'title' => lang('editschool_title'),
				'path' => array(lang('schools_path_title') => '/schools', lang('editschool_path_title') => NULL)
			);
			
			# Corps
			$data = array(
				'pageTitle' => lang('editschool_header_title'),
				'prf' => array(
					'frenchname' => $S->FrenchName,
					'englishname' => $S->EnglishName,
					'town' => $S->Town,
					'province' => $S->Province,
					'emailsuffix' => $S->EmailSuffix,
					'picture' => $S->Picture
				),
				'provinces' => $this->provinces
			);
			
			$this->load->view('common/header', $headerConfig);
			$this->load->view('schools/edit', $data);
			$this->load->view('common/footer');
		}
	}
	
		public function valid_province($province)
		{
			return in_array($province, $this->provinces);
		}
		
		public function valid_suffix($suffix)
		{
			return (bool)preg_match("#^([A-Za-z]+\.)+[A-Za-z]{2,4}$#i", $suffix);
		}
	
	
	
}

/* End of file schools.php */
/* Location: ./application/controllers/schools.php */