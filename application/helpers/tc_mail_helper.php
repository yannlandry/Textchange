<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Formate et envoie des messages stylisés selon Textchange, avec le support
 * du bilinguisme et en utilisant le module de courriels de CodeIgniter. */


class TC_Mail
{
	# Constantes de classe
	const TEMPLATES_DIR = 'content/emails';
	const FROM_ADDRESS = 'noreply@textchange.ca';
	const FROM_NAME = 'Textchange';
	
	const SMTP_HOST = 'smtp.factusscribo.com';
	const SMTP_USER = 'contact@factusscribo.com';
	const SMTP_PASS = '$FS-BSCZ-2012^';
	
	
	# Attributs
	private $CI;
	
	private $subTemplate = "simple";
	
	private $frSubject = "";
	private $enSubject = "";
	
	private $frData = array('search' => array(), 'replace' => array());
	private $enData = array('search' => array(), 'replace' => array());
	
	private $frDestinations = array();
	private $enDestinations = array();
	
	private $frMail;
	private $enMail;
	
	
	# Constructeur
	public function __construct($subTemplate = NULL)
	{
		$this->CI =& get_instance();
		if(!empty($subTemplate)) $this->set_subtemplate($subTemplate);
	}
	
	# Définir la structure du contenu du courriel
	public function set_subtemplate($subTemplate)
	{
		if(file_exists(self::TEMPLATES_DIR.'/french/'.$subTemplate.'.html') && file_exists(self::TEMPLATES_DIR.'/english/'.$subTemplate.'.html'))
				$this->subTemplate = $subTemplate;
		else	throw new Exception("Template does not exist.");
		
		return $this;
	}
	
	# Définir le titre du courriel
	public function set_subject($title, $lang = 'french')
	{
		if($this->filter_lang($lang) == 'french')	$this->frSubject = (string)$title;
		else										$this->enSubject = (string)$title;
		
		return $this;
	}
	
	# Définir le contenu du courriel
	public function set_data($data, $lang = 'french')
	{
		if(!is_array($data)) throw new InvalidArgumentException("Data must be in an array.");
		
		if($this->filter_lang($lang) == 'french') {
			foreach($data as $search => $replace) {
				$this->frData['search'][] = '{{'.$search.'}}';
				$this->frData['replace'][] = $replace;
			}
		}
		else {
			foreach($data as $search => $replace) {
				$this->enData['search'][] = '{{'.$search.'}}';
				$this->enData['replace'][] = $replace;
			}
		}
		
		return $this;
	}
	
	# Définir les destinataires du courriel
	public function add_destination($email, $lang = 'french')
	{
		if($this->filter_lang($lang) == 'french')	$this->frDestinations[] = $email;
		else										$this->enDestinations[] = $email;
		
		return $this;
	}
	
	# Préparer le courriel
	private function prepare_mail($lang)
	{
		$lang = substr($this->filter_lang($lang), 0, 2);
		
		$mail = file_get_contents(self::TEMPLATES_DIR.'/base.html');
		$mail = str_replace(
			array('{{HEADER_BG_URL}}', '{{LOGO_URL}}', '{{SUBTEMPLATE}}'),
			array(ASSETS_ROOT.'/images/backgrounds/banner.png', ASSETS_ROOT.'/images/logos/logo_'.$lang.'.png', file_get_contents(self::TEMPLATES_DIR.'/'.$this->filter_lang($lang).'/'.$this->subTemplate.'.html')),
			$mail
		);
		
		if($this->filter_lang($lang) == 'french')	$mail = str_replace($this->frData['search'], $this->frData['replace'], $mail);
		else										$mail = str_replace($this->enData['search'], $this->enData['replace'], $mail);
		
		return $mail;
	}
	
	# Trouver une langue valide
	private function filter_lang($lang)
	{
		if(in_array(strtolower($lang), array('en','english')))	return 'english';
		else													return 'french'; 
	}
	
	
	/**********************
	* ENVOYER LE COURRIEL *
	**********************/
	public function send()
	{
		$this->CI->load->library('email');
		
		$this->CI->email->initialize(array(
			'smtp_host' => self::SMTP_HOST,
			'smtp_user' => self::SMTP_USER,
			'smtp_pass' => self::SMTP_PASS,
			'mailtype' => 'html'
		));
		
		$this->CI->email->from(self::FROM_ADDRESS, self::FROM_NAME);
			
		if(!empty($this->frDestinations)) {
			$this->CI->email->to($this->frDestinations);
			$this->CI->email->subject($this->frSubject);
			$this->CI->email->message($this->prepare_mail('french'));
			
			$this->CI->email->send();
		}
			
		if(!empty($this->enDestinations)) {
			$this->CI->email->to($this->enDestinations);
			$this->CI->email->subject($this->enSubject);
			$this->CI->email->message($this->prepare_mail('english'));
			
			$this->CI->email->send();
		}	
	}
}


function mail_new_account($email, $token)
{
	$mail = new TC_Mail('new_account');
	$mail->add_destination($email, User::lang());
	$mail->set_subject(bil('Bievenue sur Textchange', 'Welcome to Textchange', true), User::lang());
	$mail->set_data(array('ACTIVATION_LINK' => BASE_URL.'/activate?token='.$token), 'french')->set_data(array('ACTIVATION_LINK' => BASE_URL.'/activate?token='.$token), 'english');
	$mail->send();
}


/* End of file tc_mail_helper.php */
/* Location: ./application/helpers/tc_mail_helper.php */