<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
/* Administration, vérification, configuration */

	
	
	# Constructeur
	public function __construct()
	{	
		parent::__construct();
		
		$this->load->model('admin_model');
		
		$this->lang->load('general', User::lang());
		$this->lang->load('admin', User::lang());
		
		if(User::rights() < 1) show_404();
	}

	
	
	# Index -> redirection à Signalements d'annonces
	public function index()
	{
		$this->adsreports();
	}

	
	
	# Rapports -> redirection à Signalements d'annonces
	public function reports()
	{
		$this->adsreports();
	}

	
	
	# Singalements d'annonces
	public function adsreports()
	{
		$curpage = get_page();
		$rescount = $this->admin_model->count_ads_reports();
		
		if(isset($_POST['mark-ads']) && check_csrf_token())
			$this->admin_model->mark_ads_reports();
		if(isset($_POST['unmark-ads']) && check_csrf_token())
			$this->admin_model->unmark_ads_reports();
		
		$headerConfig = array(
			'title' => lang('adsreports_title'),
			'path' => array(
				lang('admin_path_title') => base_url('/admin'),
				lang('reports_path_title') => base_url('/admin/reports'),
				lang('adsreports_path_title') => NULL
			)
		);
		
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/admin/reports/ads'),
			'cur_page' => $curpage,
			'total_rows' => $rescount,
			'per_page' => SMALL_ITEMS_PER_PAGE
		));
		
		$data = array(
			'reports' => $this->admin_model->get_ads_reports(($curpage - 1) * SMALL_ITEMS_PER_PAGE, SMALL_ITEMS_PER_PAGE),
			'pagination' => $this->pagination->create_links()
		);
		
		$basedata = array(
			'view' => $this->load->view('admin/reports/ads', $data, true),
			'active' => 'adsreports',
			'newadsreports' => $this->admin_model->count_new_ads_reports(),
			'newprofilesreports' => $this->admin_model->count_new_profiles_reports()
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('admin/base', $basedata);
		$this->load->view('common/footer');
	}

	
	
	# Singalements de profils
	public function profilesreports()
	{
		$this->kick_mods();
		
		$curpage = get_page();
		$rescount = $this->admin_model->count_profiles_reports();
		
		if(isset($_POST['mark-profiles']) && check_csrf_token())
			$this->admin_model->mark_profiles_reports();
		if(isset($_POST['unmark-profiles']) && check_csrf_token())
			$this->admin_model->unmark_profiles_reports();
		
		$headerConfig = array(
			'title' => lang('profilesreports_title'),
			'path' => array(
				lang('admin_path_title') => base_url('/admin'),
				lang('reports_path_title') => base_url('/admin/reports'),
				lang('profilesreports_path_title') => NULL
			)
		);
		
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/admin/reports/profiles'),
			'cur_page' => $curpage,
			'total_rows' => $rescount,
			'per_page' => SMALL_ITEMS_PER_PAGE
		));
		
		$data = array(
			'reports' => $this->admin_model->get_profiles_reports(($curpage - 1) * SMALL_ITEMS_PER_PAGE, SMALL_ITEMS_PER_PAGE),
			'pagination' => $this->pagination->create_links()
		);
		
		$basedata = array(
			'view' => $this->load->view('admin/reports/profiles', $data, true),
			'active' => 'profilesreports',
			'newadsreports' => $this->admin_model->count_new_ads_reports(),
			'newprofilesreports' => $this->admin_model->count_new_profiles_reports()
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('admin/base', $basedata);
		$this->load->view('common/footer');
	}

	
	
	# Gestion -> redirection à Domaines d'études
	public function management()
	{
		$this->domains();
	}

	
	
	# Gestion des domaines
	public function domains()
	{
		$curpage = get_page();
		$rescount = $this->admin_model->count_domains();
		
		if(!empty($_POST['french-name']) && !empty($_POST['english-name']) && check_csrf_token())
			$this->admin_model->update_or_create_domain() and addFlash(lang('domains_success_flash'));
		
		$headerConfig = array(
			'title' => lang('domains_title'),
			'path' => array(
				lang('admin_path_title') => base_url('/admin'),
				lang('management_path_title') => base_url('/admin/management'),
				lang('domains_path_title') => NULL
			)
		);
		
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => base_url('/admin/management/domains'),
			'cur_page' => $curpage,
			'total_rows' => $rescount,
			'per_page' => SMALL_ITEMS_PER_PAGE
		));
		
		$data = array(
			'domains' => $this->admin_model->get_domains(($curpage - 1) * SMALL_ITEMS_PER_PAGE, SMALL_ITEMS_PER_PAGE),
			'pagination' => $this->pagination->create_links()
		);
		
		$basedata = array(
			'view' => $this->load->view('admin/management/domains', $data, true),
			'active' => 'domains',
			'newadsreports' => $this->admin_model->count_new_ads_reports(),
			'newprofilesreports' => $this->admin_model->count_new_profiles_reports()
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('admin/base', $basedata);
		$this->load->view('common/footer');
	}

	
	
	# Maintenance -> redirection à Journaux
	public function maintenance()
	{
		$this->logs();
	}

	
	
	# Configuration
	/*public function config()
	{
		$this->kick_mods();
		
		$headerConfig = array(
			'title' => lang('config_title'),
			'path' => array(
				lang('admin_path_title') => base_url('/admin'),
				lang('maintenance_path_title') => base_url('/admin/maintenance'),
				lang('config_path_title') => NULL
			)
		);
		
		$data = array(
			'filedata' => htmlspecialchars(file_get_contents('application/settings/textchange.xml'))
		);
		
		$basedata = array(
			'view' => $this->load->view('admin/maintenance/config', $data, true),
			'active' => 'config'
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('admin/base', $basedata);
		$this->load->view('common/footer');
	}*/

	
	
	# Journaux
	public function logs()
	{
		$this->kick_mods();
		
		try {
			$logdate = new DateTime(trim($this->input->get('date')) == NULL ? "now" : $this->input->get('date'));
		}
		catch(Exception $e) {
			$logdate = new DateTime();
		}
		
		if(isset($_POST['flush-one-log']) && check_csrf_token()) {
			$this->admin_model->flush_log($logdate);
			addFlash(lang('logs_flushone_flash'));
		}
		elseif(isset($_POST['flush-all-logs']) && check_csrf_token()) {
			$this->admin_model->flush_all_logs();
			addFlash(lang('logs_flushall_flash'));
		}
		
		$headerConfig = array(
			'title' => lang('logs_title'),
			'path' => array(
				lang('admin_path_title') => base_url('/admin'),
				lang('maintenance_path_title') => base_url('/admin/maintenance'),
				lang('logs_path_title') => NULL
			)
		);
		
		$data = array(
			'logslist' => $this->admin_model->scan_logs_dir(),
			'logdate' => $logdate->format('Y-m-d'),
			'content' => $this->admin_model->read_log_from_date($logdate)
		);
		
		$basedata = array(
			'view' => $this->load->view('admin/maintenance/logs', $data, true),
			'active' => 'logs',
			'newadsreports' => $this->admin_model->count_new_ads_reports(),
			'newprofilesreports' => $this->admin_model->count_new_profiles_reports()
		);
		
		$this->load->view('common/header', $headerConfig);
		$this->load->view('admin/base', $basedata);
		$this->load->view('common/footer');
	}
	
	
	
	# Exclut les modérateurs d'une section
	private function kick_mods()
	{
		if(!User::is_admin()) show_404();
	}
	
	
	
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */